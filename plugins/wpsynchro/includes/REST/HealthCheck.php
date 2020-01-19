<?php
namespace WPSynchro\REST;

/**
 * Class for handling REST service "healthcheck"
 * Call should already be verified by permissions callback
 *
 * @since 1.1
 */
class HealthCheck
{

    public function service($request)
    {

        global $wpsynchro_container;
        $commonfunctions = $wpsynchro_container->get('class.CommonFunctions');

        $healthcheck = new \stdClass();
        $healthcheck->errors = array();
        $healthcheck->warnings = array();

        /**
         *  Check environment, WP/PHP/SQL
         */
        $errors_from_env = $commonfunctions->checkEnvCompatability();
        $env_okay = true;
        if (count($errors_from_env) > 0) {
            $healthcheck->errors = array_merge($healthcheck->errors, $errors_from_env);
            $env_okay = false;
        }

        /**
         *  Check that local installation has access key set
         */
        $accesskey = $commonfunctions->getAccessKey();
        $accesskey_okay = true;
        if (strlen(trim($accesskey)) < 20) {
            $healthcheck->errors[] = __("Access key for this site is not set - This needs to be configured for WP Synchro to work.", "wpsynchro");
            $accesskey_okay = false;
        }

        /*
         *  Check proper PHP extensions
         */
        $required_php_extensions = array("curl", "mbstring", "openssl", "mysqli");
        $php_extensions_loaded = get_loaded_extensions();
        $missing_extensions = array();
        foreach ($required_php_extensions as $required_php_extension) {
            if (!in_array($required_php_extension, $php_extensions_loaded)) {
                $missing_extensions[] = $required_php_extension;
            }
        }
        if (count($missing_extensions) > 0) {
            $healthcheck->errors[] = sprintf(__("Missing PHP extensions for WP Synchro to work. Add %s to php.ini and reload.", "wpsynchro"), implode(",", $missing_extensions));
        }

        /*
         *  Check that permalink structure is NOT plain 
         */
        $permalink_structure = get_option('permalink_structure');
        $permalinks_okay = true;
        if (trim($permalink_structure) == "") {
            $healthcheck->errors[] = __("Plain permalinks is not supported in WP Synchro. You should change it to %postname% instead", "wpsynchro");
            $permalinks_okay = false;
        }

        /**
         *  Check that SAVEQUERIES are not active
         */
        if (defined("SAVEQUERIES") && SAVEQUERIES == true) {
            $healthcheck->errors[] = __("SAVEQUERIES constant is set. This is normally only for debugging. It will generate out of memory errors with WP Synchro synchronizations", "wpsynchro");
        }

        /**
         *  Check license okay, if PRO
         */
        $licenseokay = true;
        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            $licensing = $wpsynchro_container->get("class.Licensing");
            if ($licensing->hasProblemWithLicensing()) {
                $licenseokay = false;
                $healthcheck->errors[] = $licensing->getLicenseErrorMessage();
            }
        }

        /**
         *  Check local REST urls for connectivity and proper response
         */
        if ($accesskey_okay && $permalinks_okay && $env_okay && $licenseokay) {

            $initiate_server_okay = false;

            // Check that initiate url response is well
            $initiate_url = get_home_url(null, "wp-json/wpsynchro/v1/initiate?type=local");
            $args = array(
                'method' => 'POST',
                'timeout' => 30,
                'redirection' => 0,
                'sslverify' => true,
                'httpversion' => '1.0',
                'blocking' => true,
            );
            $initiate_response = wp_remote_post($initiate_url, $args);

            if (is_wp_error($initiate_response)) {
                $errormsg = $initiate_response->get_error_message();
                if (strpos($errormsg, "cURL error 60") > -1) {
                    $healthcheck->warnings[] = __("Local SSL certificate is not valid or self-signed. To allow non-valid SSL certificates when running a synchronization, make sure it is set to allowed.", "wpsynchro");
                } else {
                    $healthcheck->warnings[] = sprintf(__("REST error - Can not reach 'initiate' REST service. Error message: %s", "wpsynchro"), $errormsg);
                }
            } else {
                if (wp_remote_retrieve_response_code($initiate_response) == 200) {
                    $body_initiate = json_decode(wp_remote_retrieve_body($initiate_response));
                    // Check we receive a token
                    if (isset($body_initiate->token)) {
                        $initiate_server_okay = true;
                    } else {
                        $healthcheck->errors[] = __("REST error - Initiate REST service returns improper response - No token was returned - Check PHP error log", "wpsynchro");
                    }
                } else {
                    $healthcheck->errors[] = __("REST error - Can not reach 'initiate' REST service - Maybe REST services are blocked?", "wpsynchro");
                }
            }

            if ($initiate_server_okay) {
                // Check masterdata url
                $localtransfertoken = $commonfunctions->getLocalTransferToken();
                $masterdata_url = get_home_url(null, "wp-json/wpsynchro/v1/masterdata/?type[]=dbtables&token=" . $localtransfertoken);
                $args = array(
                    'method' => 'POST',
                    'timeout' => 30,
                    'redirection' => 0,
                    'sslverify' => true,
                    'httpversion' => '1.0',
                    'blocking' => true,
                );
                $masterdata_response = wp_remote_post($masterdata_url, $args);            
                if (wp_remote_retrieve_response_code($masterdata_response) === 200) {
                    $body_masterdata = json_decode(wp_remote_retrieve_body($masterdata_response));

                    // Check we receive a token
                    if (!isset($body_masterdata->dbtables)) {
                        $healthcheck->errors[] = __("REST error - Masterdata REST service returns improper response - Data was not returned in usable way - Check PHP error log", "wpsynchro");
                    } else {
                        
                    }
                } else {
                    $healthcheck->errors[] = __("REST error - Can not reach 'masterdata' REST service - Maybe REST services are blocked?", "wpsynchro");
                }
            }
        }

        /*
         *  Check writable log directory
         */
        $log_dir = realpath($commonfunctions->getLogLocation());
        if (!is_writable($log_dir)) {
            $healthcheck->errors[] = sprintf(__("WP Synchro log dir is not writable for PHP - Path: %s ", "wpsynchro"), $log_dir);
        }

        /**
         *  Check other relevant dir for writability (typically for files sync)
         */
        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            $paths_check = array(
                // Document root
                $_SERVER['DOCUMENT_ROOT'],
                // Absolut directory of WP_CONTENT folder, or whatever it is called
                WP_CONTENT_DIR,
                // One dir above webroot
                dirname($_SERVER['DOCUMENT_ROOT'])
            );
            foreach ($paths_check as $path) {
                if (!$commonfunctions->checkReadWriteOnDir($path)) {
                    $healthcheck->warnings[] = sprintf(__("Path that WP Synchro might use for synchronization is not writable- Path: %s -  This can be caused by PHP's open_basedir setting or file permissions", "wpsynchro"), $path);
                }
            }
        }

        /**
         *  If no errors or warnings, set timestamp in database
         */
        if (count($healthcheck->errors) == 0) {
            update_site_option("wpsynchro_healthcheck_timestamp", time());
        }

        return new \WP_REST_Response($healthcheck, 200);
    }
}
