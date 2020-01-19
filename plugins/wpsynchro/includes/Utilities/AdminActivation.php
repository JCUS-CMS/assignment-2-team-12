<?php
namespace WPSynchro\Utilities;

/**
 * Class for handling activate tasks for WP Synchro
 *
 * @since 1.0.0
 */
use WPSynchro\CommonFunctions;
use WPSynchro\Utilities\DatabaseTables;

class AdminActivation
{

    public static function activation()
    {
        /**
         *  Make sure there is a default access key for installation
         */
        $accesskey = get_option('wpsynchro_accesskey');
        if (!$accesskey || strlen($accesskey) < 10) {
            $new_accesskey = CommonFunctions::generateAccesskey();
            update_option('wpsynchro_accesskey', $new_accesskey, false);
        }

        // Use common functions
        $commonfunctions = new \WPSynchro\CommonFunctions();
        // Check PHP/MySQL/WP versions
        $compat_errors = $commonfunctions->checkEnvCompatability();

        // @codeCoverageIgnoreStart
        if (count($compat_errors) > 0) {
            foreach ($compat_errors as $error) {
                echo $error . "<br>";
            }
            die();
        }
        // @codeCoverageIgnoreEnd

        /**
         *  Check that DB contains current WP Synchro DB version
         */
        $commonfunctions->checkDBVersion();

        // Set a license key if empty
        $licensekey = get_option('wpsynchro_license_key');
        if (!$licensekey) {
            update_option("wpsynchro_license_key", "", false);
        }

        /**
         *  Active the MU plugin if enabled 
         */
        $enable_muplugin = get_option('wpsynchro_muplugin_enabled');
        if ($enable_muplugin && strlen($enable_muplugin) > 0) {
            $mupluginhandler = new \WPSynchro\Utilities\Compatibility\MUPluginHandler();
            $mupluginhandler->enablePlugin();
        }

        /**
         *  Create tables
         */
        DatabaseTables::createSyncListTable();
        DatabaseTables::createFilePopulationTable();
    }
}
