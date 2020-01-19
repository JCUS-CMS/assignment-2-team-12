<?php
namespace WPSynchro\Finalize;

/**
 * Class for handling the finalization of the sync
 *
 * @since 1.0.0
 */
class FinalizeSync
{

    // Base data
    private $job = null;
    private $installation = null;
    public $timer = null;

    /**
     *  Run finalize method
     *  @since 1.0.0
     */
    public function runFinalize(&$installation, &$job)
    {
        // Timer
        global $wpsynchro_container;
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");

        $this->installation = &$installation;
        $this->job = &$job;

        // Init logging
        global $wpsynchro_container;
        $logger = $wpsynchro_container->get("class.Logger");
        $logger->log("INFO", "Starting finalize - Remaining time: " . $this->timer->getRemainingSyncTime());

        $this->job->finalize_progress = 10;

        /**
         *  Check what we need to do
         */
        if (!$this->installation->sync_files) {
            $this->job->finalize_files_completed = true;
            $this->job->finalize_progress += 45;
        }
        if (!$this->installation->sync_database) {
            $this->job->finalize_db_completed = true;
            $this->job->finalize_progress += 45;
        }

        /**
         *  If we have errors, return
         */
        if (count($this->job->errors) > 0) {
            return;
        }

        /**
         *  Files finalize
         */
        if (!$this->job->finalize_files_completed) {
            $this->finalizefiles();
            if ($this->job->finalize_files_completed) {
                $this->job->finalize_progress += 45;
            }
            return;
        }

        /**
         *  DB finalize
         */
        if (!$this->job->finalize_db_completed) {
            $this->finalizeDB();
            $this->job->finalize_progress += 45;
            return;
        }

        $logger->log("INFO", "Completed finalize - remaining time: " . $this->timer->getRemainingSyncTime());

        if ($this->job->finalize_files_completed && $this->job->finalize_db_completed) {
            // Update progress
            $this->job->finalize_progress = 100;
            $this->job->finalize_completed = true;
            $this->job->finalize_progress_description = "";

            // Update option with counted success times
            $success_count = get_site_option("wpsynchro_success_count", 0);
            $success_count++;
            update_site_option("wpsynchro_success_count", $success_count);
        }
    }

    /**
     *  Finalize Database stuff
     *  @since 1.0.0
     */
    private function finalizeDB()
    {

        global $wpsynchro_container;
        $databasefinalize = $wpsynchro_container->get('class.DatabaseFinalize');
        $databasefinalize->finalize($this->installation, $this->job);
    }

    /**
     *  Finalize files
     *  @since 1.0.3
     */
    private function finalizefiles()
    {

        global $wpsynchro_container;

        $sync_list = $wpsynchro_container->get("class.SyncList");
        $sync_list->init($this->installation, $this->job);

        $finalize_files_handler = $wpsynchro_container->get("class.FinalizeFiles");
        $finalize_files_handler->init($sync_list, $this->installation, $this->job);

        $finalize_files_handler->finalizeFiles();
    }
}
