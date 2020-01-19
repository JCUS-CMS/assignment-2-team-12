<?php
namespace WPSynchro\Database;

/**
 * Class for handling database finalize
 * @since 1.0.0
 */
class DatabaseFinalize
{

    // Data objects
    public $job = null;
    public $installation = null;
    public $databasesync = null;
    public $logger = null;
    public $timer = null;

    /**
     * Constructor
     * @since 1.0.0
     */
    public function __construct()
    {
        
    }

    /**
     *  Calculate completion percent
     *  @since 1.0.0
     */
    public function finalize(&$installation, &$job)
    {

        // Timer
        global $wpsynchro_container;
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");

        $this->installation = &$installation;
        $this->job = &$job;

        global $wpsynchro_container;
        $commonfunctions = $wpsynchro_container->get('class.CommonFunctions');
        $table_prefix = $commonfunctions->getDBTempTableName();
        $this->logger = $wpsynchro_container->get("class.Logger");
        $this->databasesync = $wpsynchro_container->get('class.DatabaseSync');
        $this->databasesync->job = $this->job;
        $this->databasesync->installation = $this->installation;

        $this->logger->log("INFO", "Starting database finalize with remaining time: " . $this->timer->getRemainingSyncTime());

        // Handle preserving data
        $sql_queries = array();

        // Handle data to keep
        $sql_queries = array_merge($sql_queries, $this->handleDataToKeep());

        // Get latest and greatest from target db     
        $dbtables = $this->retrieveDatabaseTables();

        // Create lookup array
        $to_table_lookup = array();
        foreach ($dbtables as $to_table) {
            $to_table_lookup[$to_table->name] = $to_table->rows;
        }

        // Run finalize checks     
        foreach ($this->job->from_dbmasterdata as $from_table) {

            $from_rows = $from_table->rows;
            // If its old temp table on source, just ignore
            if (strpos($from_table->name, $table_prefix) > -1) {
                $this->logger->log("DEBUG", "Table " . $from_table->name . " is a old temp table, so ignore");
                continue;
            }

            // Check if table exists on "to", which it should
            if (!isset($to_table_lookup[$from_table->temp_name])) {
                // Not transferred - Error
                $this->logger->log("CRITICAL", "Table " . $from_table->name . " does not exist on target, but it should. It is not transferred. Temp name is " . $from_table->temp_name);
                $this->job->errors[] = sprintf(__("Finalize: Error in database synchronization for table %s - It is not transferred", "wpsynchro"), $from_table->name);
                continue;
            }

            $to_rows = $to_table_lookup[$from_table->temp_name];
            $this->checkRowCountCompare($from_table->name, $from_rows, $to_rows);
        }

        // Get tables to be renamed
        $tables_to_be_expected_on_target = array();
        foreach ($this->job->from_dbmasterdata as $table) {
            if (!isset($from_table->temp_name) || strlen($from_table->temp_name) == 0) {
                continue;
            }

            $table_name = $table->name;
            $table_temp_name = $table->temp_name;

            // If table prefix change is enabled
            if ($this->installation->db_table_prefix_change) {
                // Check if we need to change prefixes and therefore need to rewrite table name
                $table_name = $this->handleTablePrefixChange($table_name);

                // Handle the data updates in table when doing prefix change
                $prefix_change_sql_queries = $this->handleDataChangeOnPrefixChange($table_name, $table_temp_name);
                $sql_queries = array_merge($sql_queries, $prefix_change_sql_queries);
            }

            // Add tables to the list for "expected to be on target"
            $tables_to_be_expected_on_target[] = $table_name;

            // Add sql statements
            $this->logger->log("DEBUG", "Add drop table in database on " . $table_name);
            $sql_queries[] = 'drop table if exists `' . $table_name . '`';
            $this->logger->log("DEBUG", "Add rename in database from " . $table_temp_name . " to: " . $table_name);
            $sql_queries[] = 'rename table `' . $table_temp_name . '` to `' . $table_name . '`';
        }

        $body = new \stdClass();
        $body->sql_inserts = $sql_queries;
        $body->type = 'finalize'; // For executing sql

        $this->logger->log("DEBUG", "Calling remote client db service with " . count($body->sql_inserts) . " SQL statements");
        $this->databasesync->callRemoteClientDBService($body, 'to');

        // Remove any excess temporary tables on target
        if (count($this->job->errors) == 0) {
            $this->cleanUpAfterFinalizing();
        }

        // Check for table case issues on the migration
        if (count($this->job->errors) == 0) {
            $this->checkTableCasesCorrect($tables_to_be_expected_on_target);
        }

        if (count($this->job->errors) > 0) {
            // Errors during finalize
            return;
        } else {
            // All good
            $this->job->finalize_db_completed = true;
        }
    }

    /**
     *  Handle the data to keep (such as WP Synchro data etc.)
     *  @since 1.2.0
     */
    public function handleDataToKeep()
    {
        // Figure out if we actually migrate the options table
        $target_options_table_tempname = "";
        $sql_queries = array();
        foreach ($this->job->from_dbmasterdata as $table) {
            if ($table->name == $this->job->from_wp_options_table) {
                $target_options_table_tempname = $table->temp_name;
                break;
            }
        }
        if ($target_options_table_tempname == "") {
            return $sql_queries;
        }


        // Preserving data in options table, if it is migrated
        if ($this->installation->include_all_database_tables || in_array($this->job->from_wp_options_table, $this->installation->only_include_database_table_names)) {

            $delete_from_sql = "delete from `" . $target_options_table_tempname . "`  where option_name like 'wpsynchro_%'";
            $insert_into_sql = "insert into `" . $target_options_table_tempname . "` (option_name,option_value,autoload) select option_name,option_value,autoload from " . $this->job->to_wp_options_table . " where option_name like 'wpsynchro_%'";

            $sql_queries[] = $delete_from_sql;
            $this->logger->log("INFO", "Add sql statement to delete WP Synchro options: " . $delete_from_sql);
            $sql_queries[] = $insert_into_sql;
            $this->logger->log("INFO", "Add sql statement to copy current WP Synchro options to temp table: " . $insert_into_sql);

            if ($this->installation->db_preserve_activeplugins) {
                $delete_from_sql = "delete from `" . $target_options_table_tempname . "`  where option_name = 'active_plugins'";
                $insert_into_sql = "insert into `" . $target_options_table_tempname . "` (option_name,option_value,autoload) select option_name,option_value,autoload from " . $this->job->to_wp_options_table . " where option_name = 'active_plugins'";

                $sql_queries[] = $delete_from_sql;
                $this->logger->log("INFO", "Add sql statement to delete active plugin setting: " . $delete_from_sql);
                $sql_queries[] = $insert_into_sql;
                $this->logger->log("INFO", "Add sql statement to copy current active plugin setting to temp table: " . $insert_into_sql);
            }
        }

        return $sql_queries;
    }

    /**
     *  Handle data to be renamed inside tables when changing prefix
     *  @since 1.3.2
     */
    public function handleDataChangeOnPrefixChange($table_name, $table_temp_name)
    {

        $source_prefix = $this->job->from_wpdb_prefix;
        $target_prefix = $this->job->to_wpdb_prefix;
        $sql_queries = array();
        global $wpdb;

        if ($source_prefix != $target_prefix) {
            // Add sql queries to change meta data if options table or user_meta table
            if ($table_name == $this->job->to_wp_usermeta_table) {
                // Update prefixes in usermeta table
                $sql_queries[] = "update `" . $table_temp_name . "` set meta_key = replace(meta_key, '" . $source_prefix . "', '" . $target_prefix . "') where meta_key like '" . $wpdb->esc_like($source_prefix) . "%'";
                $this->logger->log("DEBUG", "update data in temp table " . $table_temp_name . " (" . $table_name . ") to replace source prefix " . $source_prefix . " with target prefix " . $target_prefix);
            } else if ($table_name == $this->job->to_wp_options_table) {
                // Update prefix in options table
                $this->logger->log("DEBUG", "update data in temp table " . $table_temp_name . " (" . $table_name . ") to replace source prefix " . $source_prefix . " with target prefix " . $target_prefix);
                $sql_queries[] = "update `" . $table_temp_name . "` set option_name = replace(option_name, '" . $source_prefix . "', '" . $target_prefix . "') where option_name like '" . $wpdb->esc_like($source_prefix) . "%'";
            }
        }
        return $sql_queries;
    }

    /**
     *  Handle table prefix name changes, if needed
     *  @since 1.3.2
     */
    public function handleTablePrefixChange($table_name)
    {

        $source_prefix = $this->job->from_wpdb_prefix;
        $target_prefix = $this->job->to_wpdb_prefix;

        // Check if we need to change prefixes
        if ($source_prefix != $target_prefix) {
            if (substr($table_name, 0, strlen($source_prefix)) == $source_prefix) {
                $table_name = substr($table_name, strlen($source_prefix));
                $table_name = $target_prefix . $table_name;
            }
        }
        return $table_name;
    }

    /**
     *  Retrieve new database data from target
     *  @since 1.2.0
     */
    public function retrieveDatabaseTables($temp_table = true)
    {
        // Retrieve new db tables list from destination
        global $wpsynchro_container;
        $masterdata_obj = $wpsynchro_container->get('class.MasterdataSync');
        $data_to_retrieve = array("dbdetails");
        $masterdata_obj->installation = $this->installation;
        $masterdata_obj->job = $this->job;
        $this->logger->log("DEBUG", "Retrieving new masterdata from target");
        $masterdata_result = $masterdata_obj->retrieveMasterdata($this->job, 'to', $data_to_retrieve, $this->timer->getRemainingSyncTime());
        $masterdata = $masterdata_result->getBody();

        if (!is_object($masterdata) || !isset($masterdata->tmptables_dbdetails)) {
            $this->job->errors[] = __("Could not retrieve data from remote site for finalizing", "wpsynchro");
            $this->logger->log("CRITICAL", "Could not retrieve data from target site for finalizing");
            return;
        }
        $this->logger->log("DEBUG", "Retrieving new masterdata completed");

        if ($temp_table) {
            return $masterdata->tmptables_dbdetails;
        } else {
            return $masterdata->dbdetails;
        }
    }

    /**
     *  Try to clean up if any temporary tables are left on target
     *  @since 1.2.0
     */
    public function cleanUpAfterFinalizing()
    {
        $temp_tables_left = $this->retrieveDatabaseTables();
        $sql_queries = array();
        foreach ($temp_tables_left as $table) {
            $sql_queries[] = 'drop table if exists `' . $table->name . '`';
            $this->logger->log("DEBUG", "Add sql to delete excess temp table: " . $table->name);
        }

        if (count($sql_queries) > 0) {
            $body = new \stdClass();
            $body->sql_inserts = $sql_queries;
            $body->type = 'finalize'; // For executing sql

            $this->logger->log("DEBUG", "Calling remote client db service with " . count($body->sql_inserts) . " SQL statements to delete excess temp tables");
            $this->databasesync->callRemoteClientDBService($body, 'to');
        } else {
            $this->logger->log("DEBUG", "No excess temp tables to delete");
        }

        return;
    }

    /**
     *  Check that tables have correct case
     *  @since 1.3.0
     */
    public function checkTableCasesCorrect($tables_to_be_expected_on_target)
    {
        $tables_on_target = $this->retrieveDatabaseTables(false);

        foreach ($tables_to_be_expected_on_target as $checktablename) {
            $found = false;
            foreach ($tables_on_target as $targettable) {
                if ($checktablename == $targettable->name) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Not found in correct case, now check for case insensitive
                foreach ($tables_on_target as $targettable) {
                    $found_case_insensitive = false;
                    $found_table_case = $targettable->name;
                    if (strcasecmp($checktablename, $targettable->name) == 0) {
                        $found_case_insensitive = true;
                        break;
                    }
                }

                if ($found_case_insensitive) {
                    $warningmsg = sprintf(__("Finalize: Table %s is not found with the correct case. We found a table called %s. This may or may not give you problems. This happens due to SQL server configuration.", "wpsynchro"), $checktablename, $found_table_case);
                    $this->job->warnings[] = $warningmsg;
                    $this->logger->log("WARNING", $warningmsg);
                } else {
                    $warningmsg = sprintf(__("Finalize: Table %s is not found on target. It may be a problem with the rename from temp table name.", "wpsynchro"), $checktablename, $found_table_case);
                    $this->job->warnings[] = $warningmsg;
                    $this->logger->log("WARNING", $warningmsg);
                }
            }
        }
    }

    /**
     *  Function to help with finalizing database data and checks if rows are with reasonable limits
     *  @since 1.0.0
     */
    public function checkRowCountCompare($from_tablename, $from_rows, $to_rows)
    {
 
        $margin_for_warning_rows_equal = 5; // 5%       
      
        // If from has no rows, the to table should also be empty
        if ($from_rows == 0 && $to_rows != 0) {
            $this->job->errors[] = sprintf(__("Finalize: Error in database synchronization for table %s - It should not contain any rows", "wpsynchro"), $from_tablename);
            return;
        }

        // If from has rows, but the to table is empty, could be memory limit hit, exceeding post max size or mysql max_packet_size
        if ($from_rows > 0 && $to_rows == 0) {
            $this->job->errors[] = sprintf(__("Finalize: Error in database synchronization for table %s - No rows has been transferred, but should contain %d rows. Normally this is because the ressource limits has been hit and the database content is too large. Contact support is this continues to fail.", "wpsynchro"), $from_tablename, $from_rows);
            return;
        }

        // Check that rows approximately equal. Could have been changed a bit while synching, which is okay, but raises a warning if too much. Its okay if it is bigger    
        if ($to_rows < ((1 - ($margin_for_warning_rows_equal / 100)) * $from_rows)) {
            $this->job->warnings[] = sprintf(__("Finalize: Warning in database synchronization for table %s - It differs more than %d%% in size, which indicate something has gone wrong during transfer. We found %d rows, but expected around %d rows.", "wpsynchro"), $from_tablename, $margin_for_warning_rows_equal, $to_rows,$from_rows );
        }
    }
}
