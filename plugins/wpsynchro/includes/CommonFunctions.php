<?php
namespace WPSynchro;

/**
 * Class for common functions
 *
 * @since 1.0.0
 */
class CommonFunctions
{

    /**
     * Generate access key (used in REST access)
     * @since 1.0.0
     */
    public static function generateAccesskey()
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        return $token;
    }

    /**
     * Get transfer token based
     * @since 1.0.0
     */
    public function getTransferToken($accesskey, $jobtoken)
    {

        return hash("sha256", $accesskey . $jobtoken);
    }

    /**
     * Get local transfer token 
     * @since 1.0.0
     */
    public function getLocalTransferToken()
    {
        // Accesskey
        $local_accesskey = $this->getAccessKey();
        // Get initiate token
        $request = new \WP_REST_Request('POST', '/wpsynchro/v1/initiate');
        $request->set_query_params(array("type" => "local"));
        $response = rest_do_request($request);
        $data = $response->get_data();

        if (isset($data->token)) {
            return $this->getTransferToken($local_accesskey, $data->token);
        } else {
            return "";
        }
    }

    /**
     * Validate transfer token 
     * @since 1.5.0
     */
    public function isIPSecurityCheckEnabled()
    {

        $enable_ip_check = get_option('wpsynchro_ip_security_enabled');
        if ($enable_ip_check && strlen($enable_ip_check) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check that IP security is valid and okay
     * @since 1.5.0
     */
    public function checkIPSecurity($allowed_ip_arr)
    {
        // Get IP or list of IP's and make sure all of them are in IP array
        $current_ip_list = $this->getClientIPAddress();

        // Add localhost to allowed
        $allowed_ip_arr[] = "127.0.0.1";
        $allowed_ip_arr[] = "::1";

        $all_matched = true;
        foreach ($current_ip_list as $checkip) {
            if (!in_array($checkip, $allowed_ip_arr)) {
                $all_matched = false;
            }
        }
        return $all_matched;
    }

    /**
     * Validate transfer token 
     * @since 1.0.0
     */
    public function validateTransferToken($token_to_validate)
    {

        $jobtoken = "";

        // Check if is valid transfer token
        $current_transfer = get_option("wpsynchro_current_transfer", null);
        $is_ipcheck_enabled = $this->isIPSecurityCheckEnabled();

        if (is_object($current_transfer)) {
            // Transfer exist, so check if it has activity or old
            if ($current_transfer->last_activity > (time() - $current_transfer->lifetime)) {

                // Check if IP security check is disabled     
                if ($is_ipcheck_enabled === false) {
                    $jobtoken = $current_transfer->token;
                    // update last_activity
                    $current_transfer->last_activity = time();
                    update_option('wpsynchro_current_transfer', $current_transfer, false);
                } else {
                    if ($this->checkIPSecurity($current_transfer->clientip)) {
                        // IP check passed, so set token
                        $jobtoken = $current_transfer->token;
                        // And update last_activity
                        $current_transfer->last_activity = time();
                        update_option('wpsynchro_current_transfer', $current_transfer, false);
                    } else {
                        // If IP check does not pass
                        return false;
                    }
                }
            } else {
                // Too old   
                return false;
            }
        } else {
            // Does not exist        
            return false;
        }

        $expected_transfer_token = $this->getTransferToken($this->getAccessKey(), $jobtoken);
        if (hash_equals($expected_transfer_token, $token_to_validate)) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve the client IP
     * @since 1.2.0
     */
    public function getClientIPAddress()
    {
        $ip_address = array();
        $temp_ip = "";

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $temp_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $temp_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $temp_ip = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $temp_ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $temp_ip = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $temp_ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $temp_ip = 'UNKNOWN';
        }

        // Check if it contains multiple IP's, that need to be split up
        if (strpos($temp_ip, ",") > -1) {
            $exploded = explode(",", $temp_ip);
            foreach ($exploded as $ip) {
                $ip_address[] = trim($ip);
            }
        } else {
            $ip_address[] = trim($temp_ip);
        }

        return $ip_address;
    }

    /**
     * Return this installation access key
     * @since 1.0.0
     */
    public function getAccessKey()
    {
        return get_option('wpsynchro_accesskey', "");
    }

    /**
     * Get DB temp table prefix
     * @since 1.0.0
     */
    public function getDBTempTableName()
    {
        return 'wpsyntmp_';
    }

    /**
     * Get log location
     * @since 1.0.0
     */
    public function getLogLocation()
    {
        return wp_upload_dir()['basedir'] . "/wpsynchro/";
    }

    /**
     * Return the WP option name used for job's
     * @since 1.0.0
     */
    public function getJobWPOptionName($installation_id, $job_id)
    {
        return 'wpsynchro_' . $installation_id . '_' . $job_id;
    }

    /**
     * Get log filename
     * @since 1.0.0
     */
    public function getLogFilename($job_id)
    {
        return "runsync_" . $job_id . ".txt";
    }

    /**
     * Verify php/mysql/wp compatability
     * @since 1.0.0
     */
    public function checkEnvCompatability()
    {
        $errors = [];

        // Check PHP version 
        $required_php_version = "5.6";
        if (version_compare(PHP_VERSION, $required_php_version, '<')) {
            // @codeCoverageIgnoreStart
            $errors[] = sprintf(__("WP Synchro requires PHP version %s or higher - Please update your PHP", "wpsynchro"), $required_php_version);
            // @codeCoverageIgnoreEnd
        }

        // Check MySQL version
        global $wpdb;
        $required_mysql_version = "5.5";
        $mysqlversion = $wpdb->get_var("SELECT VERSION()");
        if (version_compare($mysqlversion, $required_mysql_version, '<')) {
            // @codeCoverageIgnoreStart
            $errors[] = sprintf(__("WP Synchro requires MySQL version %s or higher - Please update your MySQL", "wpsynchro"), $required_mysql_version);
            // @codeCoverageIgnoreEnd
        }

        // Check WP version
        global $wp_version;
        $required_wp_version = "4.7";
        if (version_compare($wp_version, $required_wp_version, '<')) {
            // @codeCoverageIgnoreStart
            $errors[] = sprintf(__("WP Synchro requires WordPress version %s or higher - Please update your WordPress", "wpsynchro"), $required_wp_version);
            // @codeCoverageIgnoreEnd
        }

        return $errors;
    }

    /**
     *  Converts a php.ini settings like 500M to convert to bytes     
     *  @since 1.0.0
     */
    public function convertPHPSizeToBytes($sSize)
    {

        $sSuffix = strtoupper(substr($sSize, -1));
        if (!in_array($sSuffix, array('P', 'T', 'G', 'M', 'K'))) {
            return (float) $sSize;
        }
        $iValue = substr($sSize, 0, -1);
        switch ($sSuffix) {
            case 'P':
                $iValue *= 1024;
            // Fallthrough intended
            case 'T':
                $iValue *= 1024;
            // Fallthrough intended
            case 'G':
                $iValue *= 1024;
            // Fallthrough intended
            case 'M':
                $iValue *= 1024;
            // Fallthrough intended
            case 'K':
                $iValue *= 1024;
                break;
        }
        return (float) $iValue;
    }

    /**
     *  Check WP Synchro database version and compare with current   
     *  @since 1.0.3
     */
    public function checkDBVersion()
    {
        $dbversion = get_option('wpsynchro_dbversion');

        // If not set yet, just set it and continue with life
        if (!$dbversion || $dbversion == "") {
            $dbversion = 0;
        }

        // Check if it is same as current
        if ($dbversion == WPSYNCHRO_DB_VERSION) {
            // Puuurfect, all good, so return
            return;
        } else {
            // Database is different than current version
            if ($dbversion > WPSYNCHRO_DB_VERSION) {
                // Its newer? :| 
                return __("WP Synchro database version is newer than the plugin version - Please upgrade plugin to newest version - Continue at own risk", "wpsynchro");
            } else {
                // Its older, so lets upgrade
                $this->handleDBUpgrade($dbversion);
            }
        }
    }

    /**
     *  Handle upgrading of DB versions
     *  @since 1.0.3
     */
    public function handleDBUpgrade($current_version)
    {

        if ($current_version > WPSYNCHRO_DB_VERSION) {
            return false;
        }

        // Version 1 - First DB version, no upgrades needed
        if ($current_version < 1) {
            // nothing to do for first version
        }

        // Version 1 > 2
        if ($current_version < 2) {
            // Enable MU Plugin by default
            update_option('wpsynchro_muplugin_enabled', "yes", true);
        }

        // Version 2 > 3
        if ($current_version < 3) {
            // Update installations with the new preset setting
            global $wpsynchro_container;
            $inst_factory = $wpsynchro_container->get("class.InstallationFactory");
            $inst_factory->getAllInstallations();
            foreach ($inst_factory->installations as &$installation) {
                $installation->sync_preset = 'none';
                $installation->db_make_backup = false;
                $installation->searchreplaces = array();
            }
            $inst_factory->save();
        }

        // Version 3 > 4
        if ($current_version < 4) {
            // Update installations with the new table prefix setting
            global $wpsynchro_container;
            $inst_factory = $wpsynchro_container->get("class.InstallationFactory");
            $inst_factory->getAllInstallations();
            foreach ($inst_factory->installations as &$installation) {
                $installation->db_table_prefix_change = true;
            }
            $inst_factory->save();
        }

        // Set to the db version for this release
        update_option('wpsynchro_dbversion', WPSYNCHRO_DB_VERSION, true);
        return true;
    }

    /**
     *  Path fix with convert to forward slash
     *  @since 1.0.3
     */
    public function fixPath($path)
    {
        $path = str_replace("/\\", "/", $path);
        $path = str_replace("\\/", "/", $path);
        $path = str_replace("\\\\", "/", $path);
        $path = str_replace("\\", "/", $path);
        return $path;
    }

    /**
     * Recursively delete files in directory (with max timer)
     * @since 1.0.3
     */
    function removeDirectory($dir, &$timer)
    {

        if ($timer->getRemainingSyncTime() < 2) {
            return false;
        }
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $response = $this->removeDirectory($dir . "/" . $object, $timer);
                        if ($response === false) {
                            return false;
                        }
                    } else {
                        @unlink($dir . "/" . $object);
                    }
                }
            }
            @rmdir($dir);
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Get asset full url
     *  @since 1.0.3
     */
    public function getAssetUrl($asset)
    {
        static $manifest = null;
        if ($manifest === null) {
            $manifest = json_decode(file_get_contents(WPSYNCHRO_PLUGIN_DIR . '/dist/manifest.json'));
        }

        if (isset($manifest->$asset)) {
            return untrailingslashit(WPSYNCHRO_PLUGIN_URL) . $manifest->$asset;
        } else {
            return "";
        }
    }

    /**
     *  Cleanup response body data from posts/gets. Such as remove UTF8 which json_decode pukes over
     *  @since 1.0.3
     */
    public function cleanRemoteJSONData($response_body)
    {
        // Remove UTF8 BOM which json_decode does not like
        if (substr($response_body, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $response_body = substr($response_body, 3);
        }
        return $response_body;
    }

    /**
     *  Clean up WP Synchro installation (used in setup)
     */
    public function cleanUpPluginInstallation()
    {

        global $wpsynchro_container;
        $synclist = $wpsynchro_container->get("class.SyncList");

        // Setup
        $log_dir = $this->getLogLocation();
        $db_prefix = "wpsynchro_";
        $dir_prefix = $synclist->tmp_prefix;
        $dir_prefix_length = strlen($dir_prefix);

        // Clean files
        @array_map('unlink', glob("$log_dir*.log"));
        @array_map('unlink', glob("$log_dir*.sql"));
        @array_map('unlink', glob("$log_dir*.txt"));
        @array_map('unlink', glob("$log_dir*.tmp"));

        // Delete from database
        $options_to_keep = array(
            "wpsynchro_license_key",
            "wpsynchro_dbversion",
            "wpsynchro_accesskey",
            "wpsynchro_allowed_methods",
            "wpsynchro_muplugin_enabled",
            "wpsynchro_debuglogging_enabled",
        );

        global $wpdb;
        $wpdb->query("delete FROM " . $wpdb->options . " WHERE option_name like '" . $db_prefix . "%' and option_name not in ('" . implode("','", $options_to_keep) . "') ");
    }

    /**
     *  Get and output template file
     *  @since 1.2.0
     */
    public function getTemplateFile($template_filename)
    {
        include("Templates/" . $template_filename . ".php");
    }

    /**
     *  Get files in web root to exclude
     *  @since 1.2.0
     */
    public function getWPFilesInWebrootToExclude()
    {
        $files = array(
            "wp-activate.php",
            "wp-blog-header.php",
            "wp-comments-post.php",
            "wp-config.php",
            "wp-config-sample.php",
            "wp-cron.php",
            "wp-links-opml.php",
            "wp-load.php",
            "wp-login.php",
            "wp-mail.php",
            "wp-settings.php",
            "wp-signup.php",
            "wp-trackback.php",
            "xmlrpc.php",
        );

        return $files;
    }

    /**
     *  Function to check if dir can be read/written to
     */
    public function checkReadWriteOnDir($dir)
    {
        // Default error handler is required
        set_error_handler(null);
        @trigger_error('__clean_error_info');

        // Testing...
        @is_writable($dir);
        @is_readable($dir);

        // Restore previous error handler
        restore_error_handler();

        $error = error_get_last();
        return $error['message'] === '__clean_error_info';
    }

    /**
     *  Get PHP max_execution_time
     *  @since 1.4.0
     */
    public function getPHPMaxExecutionTime()
    {
        $max_execution_time = intval(ini_get('max_execution_time'));
        if ($max_execution_time > 30) {
            $max_execution_time = 30;
        }
        if ($max_execution_time < 1) {
            $max_execution_time = 30;
        }
        return $max_execution_time;
    }

    /**
     *   Check if premium version
     *   @since 1.0.5
     */
    public static function isPremiumVersion()
    {
        static $is_premium = null;

        if ($is_premium === null) {
            // Check if premium version
            if (file_exists(WPSYNCHRO_PLUGIN_DIR . '/.premium')) {
                $is_premium = true;
            } else {
                $is_premium = false;
            }
        }

        return $is_premium;
    }
}
