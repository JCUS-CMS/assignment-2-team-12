<?php
namespace WPSynchro\REST;

/**
 * Class for handling REST service "initate" - Starting a synchronization
 * @since 1.0.0
 */
class Initiate
{

    public function service($request)
    {

        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");

        $sync_response = new \stdClass();
        $sync_response->errors = array();
        $token_lifespan = 10800;

        $allowed_types = array("push", "pull", "local");
        if (isset($request['type']) && in_array($request['type'], $allowed_types)) {
            $type = $request['type'];
            // Get allowed methods for this site
            $methods_allowed = get_option('wpsynchro_allowed_methods', false);
            if (!$methods_allowed) {
                $methods_allowed = new \stdClass();
                $methods_allowed->pull = false;
                $methods_allowed->push = false;
            }

            // Check the type and if it is allowed
            if ($type == "pull" && !$methods_allowed->pull) {
                $sync_response->errors[] = __("Pulling from this site is not allowed - Change configuration on remote server", "wpsynchro");
                return new \WP_REST_Response($sync_response, 200);
            } else if ($type == "push" && !$methods_allowed->push) {
                $sync_response->errors[] = __("Pushing to this site is not allowed - Change configuration on remote server", "wpsynchro");
                return new \WP_REST_Response($sync_response, 200);
            }
        } else {
            $sync_response->errors[] = __("Remote host does not allow that - Make sure it is same WP Synchro version", "wpsynchro");
            return new \WP_REST_Response($sync_response, 200);
        }

        // Create a new transfer object
        $transfer = new \stdClass();
        $transfer->token = hash("sha256", openssl_random_pseudo_bytes(30));
        $transfer->last_activity = time();
        $transfer->lifetime = $token_lifespan;
        $transfer->clientip = array();
        $transfer->clientip = array_merge($transfer->clientip, $common->getClientIPAddress());
        // If type local, add the frontend ip, so user frontend can use the transfer token
        if ($type == "local") {
            $transfer->clientip[] = $request['frontend_ip'];
        }
        update_option('wpsynchro_current_transfer', $transfer, false);
        $sync_response->token = $transfer->token;

        // Check licensing
        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            global $wpsynchro_container;
            $licensing = $wpsynchro_container->get("class.Licensing");
            $licensecheck = $licensing->verifyLicense();

            if ($licensecheck == false) {
                $sync_response->errors[] = $licensing->getLicenseErrorMessage();
            }
        }

        // Check if MU plugin needs update
        global $wpsynchro_container;
        $muplugin_handler = $wpsynchro_container->get("class.MUPluginHandler");
        $muplugin_handler->checkNeedsUpdate();

        return new \WP_REST_Response($sync_response, 200);
    }
}
