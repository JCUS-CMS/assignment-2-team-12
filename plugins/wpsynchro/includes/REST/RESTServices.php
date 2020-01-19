<?php
namespace WPSynchro\REST;

/**
 * Class for handling REST for WP Synchro
 *
 * @since 1.0.0
 */
class RESTServices
{

    /**
     * Setup the REST routes needed for WP Synchro
     *
     * @since 1.0.0
     */
    public function setup()
    {
        // Add "initiate" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/initiate/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\Initiate();
                    return $restservice->service($request);
                }
                )
            );
        }
        );

        // Add "masterdata" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/masterdata/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\MasterData();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "backupdatabase" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/backupdatabase/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\DatabaseBackup();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "clientsyncdatabase" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/clientsyncdatabase/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\ClientSyncDatabase();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "populatefilelist" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/populatefilelist/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\PopulateFileList();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "filetransfer" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/filetransfer/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\FileTransfer();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "getfiles" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/getfiles/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\GetFiles();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "finalize" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/finalize/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\Finalize();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "filesystem" REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/filesystem/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\Filesystem();
                    return $restservice->service($request);
                },
                'permission_callback' => array($this, 'permissionCheck'),
                )
            );
        }
        );

        // Add "synchronize"  REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/synchronize/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\Synchronize();
                    return $restservice->service($request);
                },
                'permission_callback' => function($request) {
                    if ($this->permissionCheck($request)) {
                        return true;
                    } else {
                        return current_user_can('manage_options');
                    }
                },
                )
            );
        }
        );

        // Add "status"  REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/status/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\Status();
                    return $restservice->service($request);
                },
                'permission_callback' => function($request) {
                    if ($this->permissionCheck($request)) {
                        return true;
                    } else {
                        return current_user_can('manage_options');
                    }
                },
                )
            );
        }
        );

        // Add "downloadlog"  REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/downloadlog/', array(
                'methods' => 'GET',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\DownloadLog();
                    return $restservice->service($request);
                },
                'permission_callback' => function() {
                    return current_user_can('manage_options');
                },
                )
            );
        }
        );

        // Add "healthcheck"  REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/healthcheck/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\HealthCheck();
                    return $restservice->service($request);
                },
                'permission_callback' => function() {
                    return current_user_can('manage_options');
                },
                )
            );
        }
        );

        // Add "timeoutcheck"  REST endpoint
        add_action(
            'rest_api_init', function () {
            register_rest_route(
                'wpsynchro/v1', '/timeoutcheck/', array(
                'methods' => 'POST',
                'callback' => function($request) {
                    $restservice = new \WPSynchro\REST\TimeoutCheck();
                    return $restservice->service($request);
                },
                'permission_callback' => function() {
                    return current_user_can('manage_options');
                },
                )
            );
        }
        );
    }

    /**
     *  Validates access to WP Synchro REST services
     */
    public function permissionCheck($request)
    {

        $token = $request->get_param("token");
        if ($token == null || strlen($token) < 20) {
            return false;
        }
        $token = trim($token);

        // Get current correct token to compare with
        $common = new \WPSynchro\CommonFunctions();

        // Check if it is a transfer token
        return $common->validateTransferToken($token);
    }
}
