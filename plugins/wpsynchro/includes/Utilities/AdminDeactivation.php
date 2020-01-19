<?php
namespace WPSynchro\Utilities;

/**
 * Class for handling deactivate tasks for WP Synchro
 *
 * @since 1.1.0
 */
class AdminDeactivation
{

    public static function deactivation()
    {

        // Deactivate MU plugin if exists
        $mupluginhandler = new \WPSynchro\Utilities\Compatibility\MUPluginHandler();
        $mupluginhandler->disablePlugin();

        // Remove database tables
        global $wpdb;
        $tablename = $wpdb->prefix . "wpsynchro_file_population_list";
        $wpdb->query('drop table if exists `' . $tablename . '`');
        $tablename = $wpdb->prefix . "wpsynchro_sync_list";
        $wpdb->query('drop table if exists `' . $tablename . '`');
    }
}
