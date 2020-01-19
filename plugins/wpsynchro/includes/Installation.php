<?php
namespace WPSynchro;

/**
 * Class for handling a "sync installation"
 *
 * @since 1.0.0
 */
class Installation
{

    public $id = '';
    public $name = '';
    // Type
    public $type = '';
    // From
    public $site_url = '';
    public $access_key = '';  
    // General settings    
    public $verify_ssl = true;    
    // Data to sync
    public $sync_preset = "all";
    public $sync_database = false;
    public $sync_files = false;
    /*
     * Database
     */
    public $db_make_backup = true;
    public $db_table_prefix_change = true;
    public $db_preserve_activeplugins = true;
    // Exclusions DB
    public $include_all_database_tables = true;
    public $only_include_database_table_names = [];
    // Search / replaces in db
    public $searchreplaces = [];
    public $ignore_all_search_replaces = false;

    /*
     *  Files
     */
    public $file_locations = array();
    public $files_exclude_files_match = "node_modules,vendor";


    /*
     * Errors
     */
    public $validate_errors = [];

    // Constants
    const SYNC_TYPES = ['pull', 'push'];
    const SYNC_PRESETS = ['all', 'db_all', 'file_all', 'none'];

    public function __construct()
    {
        
    }

    /**
     *  Get text to show on overview for this installation
     *  @since 1.0.0
     */
    public function getOverviewDescription()
    {
        $desc = __("Synchronize", "wpsynchro") . " ";
        // Type
        if ($this->type == 'push') {
            $desc .= sprintf(__("from <b>this installation</b> to <b>%s</b> ", "wpsynchro"), $this->site_url) . " ";
        } else {
            $desc .= sprintf(__("<b>from %s</b> to <b>this installation</b>", "wpsynchro"), $this->site_url) . " ";
        }

        if (!$this->verify_ssl) {
            $desc .= "<br> - " . __("Self-signed and non-valid SSL certificates allowed", "wpsynchro");
        }

        if ($this->sync_preset == 'all') {
            $desc .= "<br> - " . __("Synchronize entire site (database and files)", "wpsynchro");
        } else if ($this->sync_preset == 'db_all') {
            $desc .= "<br> - " . __("Synchronize entire database", "wpsynchro");
        } else if ($this->sync_preset == 'file_all') {
            $desc .= "<br> - " . __("Synchronize all files", "wpsynchro");
        } else if ($this->sync_preset == 'none') {
            if (!$this->sync_database && !$this->sync_files) {
                $desc .= "<br> - " . __("Custom synchronization - But no data chosen for sync", "wpsynchro");
            } else {
                $desc .= "<br> - " . __("Custom synchronization", "wpsynchro");
            }
        }

        if ($this->sync_database && $this->sync_preset == 'none' && $this->db_make_backup) {
            $desc .= "<br> - ";
            if ($this->include_all_database_tables) {
                $desc .= __("Database backup: All database tables will be exported", "wpsynchro");
            } else {
                $desc .= sprintf(__("Database backup: Will backup %d selected tables. ", "wpsynchro"), count($this->only_include_database_table_names));
            }
        }

        if ($this->sync_database && $this->sync_preset == 'none') {
            $desc .= "<br> - ";
            if ($this->include_all_database_tables) {
                $desc .= __("Database: All database tables will be migrated", "wpsynchro");
            } else {
                $desc .= sprintf(__("Database: Will migrate %d selected tables. ", "wpsynchro"), count($this->only_include_database_table_names));
            }
        }

        if ($this->sync_files && $this->sync_preset == 'none') {
            if (count($this->file_locations) > 0) {
                if (count($this->file_locations) == 1) {
                    $desc .= "<br> - " . __("Files: One location will be migrated", "wpsynchro");
                } else {
                    $desc .= "<br> - " . sprintf(__("Files: %d locations will be migrated", "wpsynchro"), count($this->file_locations));
                }
            } else {
                $desc .= "<br> - " . __("Files: No locations chosen for synchronization", "wpsynchro");
            }
        }

        // check for errors
        $errors = $this->checkErrors();
        if (count($errors) > 0) {
            $desc .= "<br><br>";
            foreach ($errors as $error) {
                $desc .= "<b style='color:red;'>" . $error . "</b><br>";
            }
        }


        return $desc;
    }

    /**
     *  Check for errors, also taking pro/free into account
     *  @since 1.0.0
     */
    public function checkErrors()
    {
        $errors = array();
        $ispro = \WPSynchro\CommonFunctions::isPremiumVersion();

        if (!$ispro && ($this->sync_preset == "all" || $this->sync_preset == "files" || $this->sync_files == true)) {
            $errors[] = __("File migration is only available in PRO version", "wpsynchro");
        }

        if (!$ispro && ($this->sync_preset == "all" || $this->db_make_backup == true)) {
            $errors[] = __("Database backup is only available in PRO version", "wpsynchro");
        }

        return $errors;
    }

    /**
     *  Check if installation can run, taking PRO/FREE and functionalities into account
     *  @since 1.0.0
     */
    public function canRun()
    {
        $errors = $this->checkErrors();
        if (count($errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *  Check if a preset is chosen and change the object accordingly
     *  @since 1.2.0
     */
    public function checkAndUpdateToPreset()
    {

        // Is PRO version
        $is_pro = \WPSynchro\CommonFunctions::isPremiumVersion();

        // Adjust settings to the correct ones
        if ($this->sync_preset == 'all') {
            // DB
            $this->sync_database = true;
            $this->db_make_backup = true;
            $this->db_table_prefix_change = true;
            $this->db_preserve_activeplugins = true;
            $this->include_all_database_tables = true;
            $this->only_include_database_table_names = [];
            // Files
            $this->sync_files = true;
            $this->file_locations = [];
            $this->files_exclude_files_match = "";
        } else if ($this->sync_preset == 'db_all') {
            // DB
            $this->sync_database = true;
            $this->db_make_backup = true;
            $this->db_table_prefix_change = true;
            $this->db_preserve_activeplugins = true;
            $this->include_all_database_tables = true;
            $this->only_include_database_table_names = [];
            // Files
            $this->sync_files = false;
        } else if ($this->sync_preset == 'file_all') {
            // DB
            $this->sync_database = false;
            $this->db_make_backup = false;
            $this->db_table_prefix_change = false;
            // Files
            $this->sync_files = true;
            $this->file_locations = [];
            $this->files_exclude_files_match = "";
        } else if ($this->sync_preset == 'none') {
            
        }

        if (!$is_pro) {
            $this->db_make_backup = false;
            $this->sync_files = false;
        }
    }
}
