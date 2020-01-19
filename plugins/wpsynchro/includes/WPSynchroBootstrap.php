<?php
namespace WPSynchro;

/**
 * Primary plugin class
 * Loads all the needed stuff to get the plugin off the ground and make the user a happy panda
 *
 * @since 1.0.0
 */
class WPSynchroBootstrap
{

    /**
     *  Initialize plugin, setting some defines for later use
     *  @since 1.0.0
     */
    public function __construct()
    {
        define('WPSYNCHRO_PLUGIN_DIR', WP_PLUGIN_DIR . '/wpsynchro/');
        define('WPSYNCHRO_PLUGIN_URL', trailingslashit(plugins_url('/wpsynchro')));        
    }

    /**
     * Run method, that will kickstart all the needed initialization
     * @since 1.0.0
     */
    public function run()
    {
        // Initialize service controller
        $this->loadServiceController();

        // Check database need update
        if (is_admin()) {
            global $wpsynchro_container;
            $commonfunctions = $wpsynchro_container->get("class.CommonFunctions");
            $commonfunctions->checkDBVersion();
        }

        // Load WP CLI command, if WP CLI request
        if (defined('WP_CLI') && WP_CLI && \WPSynchro\CommonFunctions::isPremiumVersion()) {
            global $wpsynchro_container;
            $wpsynchrocli = $wpsynchro_container->get("class.WPSynchroCLI");
            \WP_CLI::add_command('wpsynchro', $wpsynchrocli);
        }

        // Load REST API endpoints
        $this->loadRESTApi();    

        // Only load backend stuff when needed
        if (is_admin()) {
            if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
                // Check licensing for wp-admin calls, and only if pro version
                global $wpsynchro_container;
                $licensing = $wpsynchro_container->get("class.Licensing");
                $licensing->verifyLicense();

                // Check for updates
                $updatechecker = $wpsynchro_container->get("class.UpdateChecker");
            }

            $this->loadBackendAdmin();
            $this->loadTextdomain();

            // Check if MU plugin needs update
            global $wpsynchro_container;
            $muplugin_handler = $wpsynchro_container->get("class.MUPluginHandler");
            $muplugin_handler->checkNeedsUpdate();
        }
    }

    /**
     *  Load service controller
     *  @since 1.0.0
     */
    private function loadServiceController()
    {

        ServiceController::init();
    }

    /**
     *  Load admin related functions (menus,etc)
     *  @since 1.0.0
     */
    private function loadBackendAdmin()
    {
        $this->addMenusToBackend();
        $this->addStylesAndScripts();
        $this->loadActions();
    }

    /**
     *  Load REST services used by WP Synchro
     *  Will be loaded always, because its the "server" part of WP Synchro
     *  @since 1.0.0
     */
    private function loadRESTApi()
    {
        
        $restservices = new \WPSynchro\REST\RESTServices();
        $restservices->setup();    
        
    }

    /**
     *  Load other actions
     *  @since 1.0.3
     */
    private function loadActions()
    {
        add_action('admin_init', function() {
            $dismiss_option = filter_input(INPUT_GET, 'wpsynchro_dismiss_review_request', FILTER_SANITIZE_STRING);
            if (is_string($dismiss_option)) {
                update_site_option("wpsynchro_dismiss_review_request", true);
                wp_die();
            }
        });
    }

    /**
     *  Load text domain
     *  @since 1.0.0
     */
    private function loadTextdomain()
    {
        add_action(
            'plugins_loaded', function () {
            load_plugin_textdomain('wpsynchro', false, 'wpsynchro/languages');
        }
        );
    }

    /**
     *   Add menu to backend
     *   @since 1.0.0
     */
    private function addMenusToBackend()
    {
   
        add_action(
            'admin_menu', function () {

            add_menu_page('WP Synchro', 'WP Synchro', 'manage_options', 'wpsynchro_menu', array(__NAMESPACE__ . '\\Pages\AdminOverview', 'render'), 'dashicons-update', 76);
            add_submenu_page('wpsynchro_menu', '', '', 'manage_options', 'wpsynchro_menu', '');
            add_submenu_page('wpsynchro_menu', __('Overview', 'wpsynchro'), __('Overview', 'wpsynchro'), 'manage_options', 'wpsynchro_overview', array(__NAMESPACE__ . '\\Pages\AdminOverview', 'render'));
            add_submenu_page('wpsynchro_menu', __('Logs', 'wpsynchro'), __('Logs', 'wpsynchro'), 'manage_options', 'wpsynchro_log', array(__NAMESPACE__ . '\\Pages\AdminLog', 'render'));
            add_submenu_page('wpsynchro_menu', __('Setup', 'wpsynchro'), __('Setup', 'wpsynchro'), 'manage_options', 'wpsynchro_setup', array(__NAMESPACE__ . '\\Pages\AdminSetup', 'render'));
            add_submenu_page('wpsynchro_menu', __('Support', 'wpsynchro'), __('Support', 'wpsynchro'), 'manage_options', 'wpsynchro_support', array(__NAMESPACE__ . '\\Pages\AdminSupport', 'render'));
            if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
                add_submenu_page('wpsynchro_menu', __('Licensing', 'wpsynchro'), __('Licensing', 'wpsynchro'), 'manage_options', 'wpsynchro_licensing', array(__NAMESPACE__ . '\\Pages\AdminLicensing', 'render'));
            }

            // Run installation page (not in menu)
            add_submenu_page('wpsynchro_menu', '', '', 'manage_options', 'wpsynchro_run', array(__NAMESPACE__ . '\\Pages\AdminRunSync', 'render'));
            // Add installation page (not in menu)
            add_submenu_page('wpsynchro_menu', '', '', 'manage_options', 'wpsynchro_addedit', array(__NAMESPACE__ . '\\Pages\AdminAddEdit', 'render'));
        }
        );
    }

    /**
     *   Add CSS and JS to backend
     *   @since 1.0.0
     */
    private function addStylesAndScripts()
    {

        // Admin scripts
        add_action('admin_enqueue_scripts', function ($hook) {

            if (strpos($hook, 'wpsynchro') > -1) {
                global $wpsynchro_container;
                $commonfunctions = $wpsynchro_container->get("class.CommonFunctions");

                wp_enqueue_script('wpsynchro_admin_js', $commonfunctions->getAssetUrl("main.js"), array(), WPSYNCHRO_VERSION, true);

                // Localize the healthcheck check, used on multiple pages  

                $healthcheck_localize = array(
                    'rest_nonce' => wp_create_nonce('wp_rest'),
                    'basic_check_resturl' => get_rest_url(get_current_blog_id(), 'wpsynchro/v1/healthcheck/'),
                    'timeout_check_resturl' => get_rest_url(get_current_blog_id(), 'wpsynchro/v1/timeoutcheck/'),
                    'timeout_expected_timeout' => $commonfunctions->getPHPMaxExecutionTime(),
                    'introtext' => __("Health check for WP Synchro on this installation", "wpsynchro"),
                    'helptitle' => __("Check if this installation will work with WP Synchro. It checks REST access, php extensions, hosting setup and more.", "wpsynchro"),
                    'basic_check_desc' => __("Performing basic health check", "wpsynchro"),
                    'timeout_check_desc' => __("Performing timeout test (will take up to {0} seconds)", "wpsynchro"),
                    'errorsfound' => __("Errors found", "wpsynchro"),
                    'warningsfound' => __("Warnings found", "wpsynchro"),
                    'rerunhelp' => __("Tip: These tests can be rerun in 'Support' menu.", "wpsynchro"),
                    'errorunknown' => __("Critical - Request to local WP Synchro health check REST service could not be sent or did not get no response.", "wpsynchro"),
                    'errornoresponse' => __("Critical - Request to local WP Synchro health check REST service did not get a response at all.", "wpsynchro"),
                    'errorwithstatuscode' => __("Critical - Request to REST service did not respond properly - HTTP {0} - Maybe REST is blocked or returns invalid content. Response JSON:", "wpsynchro"),
                    'errorwithoutstatuscode' => __("Critical - Request to REST service did not respond properly - Maybe REST is blocked or returns invalid content. Response JSON:", "wpsynchro"),
                    'timeouterror' => __("Critical - Connection was cut off before the expected timeout in PHP. We got a timeout in ~{0} seconds, but expected {1}. This means that the webserver or another components is cutting PHP scripts off before it should - Contact your hosting to get this fixed", "wpsynchro"),
                );
                wp_localize_script('wpsynchro_admin_js', 'wpsynchro_healthcheck', $healthcheck_localize);
            }
        }
        );

        // Admin styles
        add_action('admin_enqueue_scripts', function($hook) {
            if (strpos($hook, 'wpsynchro') > -1) {
                global $wpsynchro_container;
                $commonfunctions = $wpsynchro_container->get("class.CommonFunctions");
                wp_enqueue_style('wpsynchro_admin_css', $commonfunctions->getAssetUrl("main.css"), array(), WPSYNCHRO_VERSION);
            }
        });
    }
}
