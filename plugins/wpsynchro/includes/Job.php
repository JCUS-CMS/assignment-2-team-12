<?php
namespace WPSynchro;

/**
 * Class for handling an instance of a synchronization (aka. one pull or one push)
 *
 * @since 1.0.0
 */
class Job
{

    public $id = '';
    public $installation_id = null;
    // Run lock
    public $run_lock = false;
    public $run_lock_timer = 0;
    public $run_lock_problem_time = 0;
    // Errors and warnings
    public $errors = array();
    public $warnings = array();
    // Triggers
    public $request_full_timeframe = false;

    /**
     *  Progress
     */
    public $initiation_completed = false;
    public $masterdata_completed = false;
    public $masterdata_progress = 0;
    public $database_backup_completed = false;
    public $database_backup_progress = 0;
    public $database_backup_progress_description = "";
    public $database_completed = false;
    public $database_progress = 0;
    public $database_progress_description = "";
    public $files_progress = 0;
    public $files_progress_description = "";
    public $finalize_completed = false;
    public $finalize_progress = 0;
    public $finalize_progress_description = "";
    public $is_completed = false;
    public $first_time_setup_done = false;

    /**
     *  Initiate tokens
     */
    public $local_transfer_token = "";
    public $remote_transfer_token = "";

    /**
     *  Data from step: Masterdata
     */
    // From
    public $from_token = null;      // From initiate
    public $from_accesskey = "";    // Used in encryption
    public $from_dbmasterdata = null;
    public $from_client_home_url = null;
    public $from_rest_base_url = null;
    public $from_wpdb_prefix = null;
    public $from_wp_options_table = null;
    public $from_wp_usermeta_table = null;
    public $from_max_allowed_packet_size = 0;
    public $from_max_post_size = 0;
    public $from_max_file_uploads = 20;
    public $from_upload_max_filesize = 0;
    public $from_memory_limit = 0;
    public $from_sql_version = "";
    public $from_plugin_version = "";
    public $from_files_above_webroot_dir = "";
    public $from_files_home_dir = "";
    public $from_files_wp_content_dir = "";
    public $from_files_wp_dir = "";
    public $from_files_uploads_dir = "";
    public $from_files_plugins_dir = "";
    public $from_files_themes_dir = "";
    public $from_files_plugin_list = array();
    public $from_files_theme_list = array();
    public $from_debug = array();
    // to
    public $to_token = null;    // From initiate
    public $to_accesskey = "";    // Used in encryption
    public $to_dbmasterdata = null;
    public $to_client_home_url = null;
    public $to_rest_base_url = null;
    public $to_wpdb_prefix = null;
    public $to_wp_options_table = null;
    public $to_wp_usermeta_table = null;
    public $to_max_allowed_packet_size = 0;
    public $to_max_post_size = 0;
    public $to_max_file_uploads = 20;
    public $to_upload_max_filesize = 0;
    public $to_memory_limit = 0;
    public $to_sql_version = "";
    public $to_plugin_version = "";
    public $to_files_above_webroot_dir = "";
    public $to_files_home_dir = "";
    public $to_files_wp_content_dir = "";
    public $to_files_wp_dir = "";
    public $to_files_uploads_dir = "";
    public $to_files_plugins_dir = "";
    public $to_files_themes_dir = "";
    public $to_files_plugin_list = array();
    public $to_files_theme_list = array();
    public $to_debug = array();

    /**
     *  Data from step: database backup
     */
    public $db_backup_tables = null;

    /**
     *  Data from step: Database
     */
    public $db_first_run_setup = false;
    public $db_rows_per_sync = 500;
    public $db_rows_per_sync_default = 500;                 // 500 rows as default
    public $db_response_size_wanted_default = 1000000;       // 500 kb as default
    public $db_response_size_wanted_max = 5000000;          // Can max scale to 5mb, to prevent all sorts of trouble with memory and other stuff
    public $db_throttle_table = "";
    public $db_last_response_length = 0;

    /**
     *  Data from step: Files
     */
    public $files_sections = array();
    // Counters
    public $files_needs_transfer = 0;                   // Count of files that need trnasfer
    public $files_needs_transfer_size = 0;              // Total size of files that needs to be transferred
    public $files_needs_delete = 0;                     // Files that need to be deleted during finalize
    // Sync list init
    public $files_sync_list_initialized = false;        // Is sync list initialized
    // Population
    public $files_population_sections_validated = false; // Is file sections validated
    public $files_population_source = false;            // Is source files populated
    public $files_population_target = false;            // Is target files populated    
    public $files_population_source_count = 0;          // Count of files found on source to this point (can increase)
    public $files_population_target_count = 0;          // Count of files found on target to this point (can increase) 
    // Transfer
    public $files_transfer_completed_counter = 0;       // Number of files transferred
    public $files_transfer_completed_size = 0;          // Size of files transferred
    // Stages completed
    public $files_all_sections_populated = false;       // Make a list of files from source that is included in sync    
    public $files_all_sections_path_handled = false;    // Determine which files to transfer and which to delete   
    public $files_all_completed = false;                // All files have been transferred and awaiting finalize deletes

    /**
     *   Finalize data
     */
    public $finalize_files_paths_reduced = false;
    public $finalize_files_completed = false;
    public $finalize_db_completed = false;

    /**
     *  Load data from DB 
     *  @since 1.0.0
     */
    public function load($installation_id, $job_id)
    {
        $this->id = $job_id;
        $this->installation_id = $installation_id;

        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");

        $job_option = get_option($common->getJobWPOptionName($installation_id, $job_id), false);
        if ($job_option !== false) {
            foreach ($job_option as $key => $value) {
                $this->$key = $value;
            }

            return true;
        }
        return false;
    }

    /**
     *  Save job to DB
     *  @since 1.0.0
     */
    public function save()
    {
        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");
        update_option($common->getJobWPOptionName($this->installation_id, $this->id), (array) $this, false);
    }
}
