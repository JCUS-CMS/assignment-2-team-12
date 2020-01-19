<?php
namespace WPSynchro\Masterdata;

/**
 * Class for handling the masterdata of the sync
 *
 * @since 1.0.0
 */
class MasterdataSync
{

    // Base data
    public $starttime = 0;
    public $installation = null;
    public $job = null;
    public $remote_wpdb = null;
    public $logger = null;
    public $timer = null;

    /**
     *  Constructor
     */
    public function __construct()
    {
        
    }

    /**
     *  Handle masterdata step
     *  @since 1.0.3
     */
    public function runMasterdataStep(&$installation, &$job)
    {

        // Start timer
        global $wpsynchro_container;
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");
        $masterdata_timer = $this->timer->startTimer("masterdata", "overall", "timer");

        $this->installation = &$installation;
        $this->job = &$job;

        $this->logger = $wpsynchro_container->get("class.Logger");
        $this->logger->log("INFO", "Getting masterdata from source and target with remaining time:" . $this->timer->getRemainingSyncTime());

        // Figure out what data is needed
        $data_to_retrieve = array();
        $data_to_retrieve[] = "dbdetails";
        $data_to_retrieve[] = "filedetails";

        // Retrieve data
        $metadata_results = array();
        $metadata_results['from'] = $this->retrieveMasterdata($this->job, 'from', $data_to_retrieve, $this->timer->getRemainingSyncTime());
        $metadata_results['to'] = $this->retrieveMasterdata($this->job, 'to', $data_to_retrieve, $this->timer->getRemainingSyncTime());

        // Check for errors
        foreach ($metadata_results as $prefix => $masterdata) {
            if (!$masterdata->isSuccess()) {
                $errormsg = sprintf(__("Could not retrieve masterdata from target '%s', which means we can not continue the synchronization.", "wpsynchro"), $prefix);
                $this->job->errors[] = $errormsg;
                $this->logger->log("CRITICAL", $errormsg, $metadata_results);
                return;
            }
        }

        foreach ($metadata_results as $prefix => $masterdata) {

            $masterdata_content = $masterdata->getBody();

            if (in_array("dbdetails", $data_to_retrieve)) {
                if (!isset($masterdata_content->dbdetails) || $masterdata_content->dbdetails == null) {
                    $errormsg = sprintf(__("Did not retrieve correct database masterdata from target '%s' - See log file", "wpsynchro"), $prefix);
                    $this->job->errors[] = $errormsg;
                    $this->logger->log("CRITICAL", $errormsg, $metadata_results);
                    return;
                }
            }

            // Process and map the data to Job object
            $this->handleMasterdataMapping($prefix, $masterdata_content);
        }

        if (count($this->job->errors) == 0) {
            // Check that plugin versions are identical on both sides, otherwise raise error  
            if ($this->job->from_plugin_version != $this->job->to_plugin_version) {
                $this->job->errors[] = sprintf(__("WP Synchro plugin versions do not match on both sides. One runs version %s and other runs %s. Make sure they use same version to prevent problems caused by different versions of plugin.", "wpsynchro"), $this->job->from_plugin_version, $this->job->to_plugin_version);
            }

            // Check that prefix are the same or issue warning                  
            if ($this->installation->sync_database && !$this->installation->db_table_prefix_change && $this->job->from_wpdb_prefix != $this->job->to_wpdb_prefix) {
                $prefix_warning = sprintf(__("Database table prefixes are different on the source and target site. Source uses '%s' and target uses '%s'. Table prefix migration is not enabled in the installation configuration. This is just a warning, as the synchronization can complete, but the tables will not be used by the target site. Recommended action is to turn on the table prefix migration in the installation configuration.", "wpsynchro"), $this->job->from_wpdb_prefix, $this->job->to_wpdb_prefix);
                $this->job->warnings[] = $prefix_warning;
                $this->logger->log("WARNING", $prefix_warning);
            }

            // Check licensing 
            if (\WPSynchro\CommonFunctions::isPremiumVersion() && count($this->job->errors) === 0) {
                global $wpsynchro_container;
                $licensing = $wpsynchro_container->get("class.Licensing");
                $licens_sync_result = $licensing->verifyLicenseForSynchronization($this->job->from_client_home_url, $this->job->to_client_home_url);

                if ($licens_sync_result->state === false) {
                    $this->job->errors[] = array_merge($this->job->errors, $licens_sync_result->errors);
                }
            }
        }

        $this->logger->log("INFO", "Completed masterdata on: " . $this->timer->endTimer($masterdata_timer) . " seconds");

        if (count($this->job->errors) === 0) {
            $this->job->masterdata_completed = true;
        }
    }

    /**
     *  Masterdata mapping
     *  @since 1.5.0
     */
    public function handleMasterdataMapping($prefix, $masterdata_content)
    {
        /**
         *  Base mappings
         */
        if (isset($masterdata_content->base)) {
            $mappings = array(
                "_client_home_url" => "client_home_url",
                "_rest_base_url" => "rest_base_url",
                "_wpdb_prefix" => "wpdb_prefix",
                "_wp_options_table" => "wp_options_table",
                "_wp_usermeta_table" => "wp_usermeta_table",
                "_max_allowed_packet_size" => "max_allowed_packet_size",
                "_max_post_size" => "max_post_size",
                "_memory_limit" => "memory_limit",
                "_upload_max_filesize" => "upload_max_filesize",
                "_max_file_uploads" => "max_file_uploads",
                "_sql_version" => "sql_version",
                "_plugin_version" => "plugin_version",
            );

            foreach ($mappings as $job_key => $masterdata_key) {
                if (!isset($masterdata_content->base->$masterdata_key)) {
                    continue;
                }
                $tmp_var = $prefix . $job_key;
                $this->job->$tmp_var = $masterdata_content->base->$masterdata_key;
            }
        }

        /**
         *  DB details mapping
         */
        if (isset($masterdata_content->dbdetails)) {

            // Set column types to arrays instead of objects (which happens doing json transfer)
            foreach ($masterdata_content->dbdetails as &$table) {
                foreach ($table->column_types as $key => $value) {
                    $table->column_types->$key = (array) $table->column_types->$key;
                }
            }

            $tmp_var = $prefix . '_dbmasterdata';
            $this->job->$tmp_var = $masterdata_content->dbdetails;
        }

        /**
         *  Files data mapping
         */
        if (isset($masterdata_content->files)) {
            $mappings = array(
                "_files_above_webroot_dir" => "files_above_webroot_dir",
                "_files_home_dir" => "files_home_dir",
                "_files_wp_content_dir" => "files_wp_content_dir",
                "_files_wp_dir" => "files_wp_dir",
                "_files_uploads_dir" => "files_uploads_dir",
                "_files_plugins_dir" => "files_plugins_dir",
                "_files_themes_dir" => "files_themes_dir",
                "_files_plugin_list" => "files_plugin_list",
                "_files_theme_list" => "files_theme_list",
            );

            foreach ($mappings as $job_key => $masterdata_key) {
                if (!isset($masterdata_content->files->$masterdata_key)) {
                    continue;
                }
                $tmp_var = $prefix . $job_key;
                $this->job->$tmp_var = $masterdata_content->files->$masterdata_key;
            }
        }

        /**
         *  Debug data mapping
         */
        if (isset($masterdata_content->debug)) {
            $tmp_var = $prefix . '_debug';
            $this->job->$tmp_var = $masterdata_content->debug;
        }
    }

    /**
     *  Retrieve masterdata 
     *  @since 1.0.0
     */
    public function retrieveMasterdata(&$job, $to_or_from = 'from', $slugs_to_retrieve = array(), $allotted_time)
    {
        global $wpsynchro_container;
        $logger = $wpsynchro_container->get("class.Logger");

        // Generate query string
        $querystring = "";
        foreach ($slugs_to_retrieve as $slug) {
            $querystring .= "&type[]=" . $slug;
        }
        $querystring = trim($querystring, "&");
        $querystring .= "&transport=1";

        // Get webservice url
        if (($this->installation->type == 'pull' && $to_or_from == 'to') || ($this->installation->type == 'push' && $to_or_from == 'from')) {
            $baseurl = rest_url("wpsynchro/v1/masterdata/?" . $querystring);
        } else if (($this->installation->type == 'pull' && $to_or_from == 'from') || ($this->installation->type == 'push' && $to_or_from == 'to')) {
            $baseurl = trailingslashit($this->installation->site_url) . "wp-json/wpsynchro/v1/masterdata/?" . $querystring;
        }

        $logger->log("DEBUG", "Calling masterdata service on: " . $baseurl . " with intent to user as '" . $to_or_from . "'");

        // Get remote transfer object
        $remotetransport = $wpsynchro_container->get('class.RemoteTransfer');
        $remotetransport->init();
        $remotetransport->setUrl($baseurl);
        $transportresult = $remotetransport->remotePOST();
        return $transportresult;
    }
}
