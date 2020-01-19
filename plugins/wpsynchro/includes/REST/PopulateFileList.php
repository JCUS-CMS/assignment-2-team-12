<?php
namespace WPSynchro\REST;

use WPSynchro\Utilities\DatabaseTables;

/**
 * Class for handling REST service "PopulateFileList" - Returns file lists 
 * Call should already be verified by permissions callback
 * @since 1.0.3
 */
class PopulateFileList
{

    public $exclusions;
    public $common = null;
    public $file_list = array();
    public $file_excludes_in_webroot = array();
    public $state = null;
    public $timer = null;
    public $basepath = null;
    // Result to be send back
    public $result = null;

    public function __construct()
    {

        // Initialize return data
        $this->result = new \stdClass();
        $this->result->errors = array();
        $this->result->warnings = array();
        $this->result->debugs = array();
    }

    public function service($request)
    {

        // Init timer
        global $wpsynchro_container;
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");
        $this->timer->init();

        // Get transfer object, so we can get data
        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");
        $transfer = $wpsynchro_container->get("class.Transfer");
        $transfer->setEncryptionKey($common->getAccessKey());
        $transfer->populateFromString($request->get_body());
        $body = $transfer->getDataObject();

        // Extract parameters      
        $section = $body->section;
        $type = $body->type;
        $allotted_time = $body->allotted_time * 0.9;
        $jobid = $body->requestid;
        $this->exclusions = $body->exclusions;

        $this->timer->addOtherSyncTimeLimit($allotted_time);
        global $wpsynchro_container;
        $this->common = $wpsynchro_container->get("class.CommonFunctions");
        $this->file_excludes_in_webroot = $this->common->getWPFilesInWebrootToExclude();

        // Figure out where we are in the process
        $this->state = get_option('wpsynchro_filepopulation_current');
        if (!$this->state || $this->state->jobid != $jobid || $this->state->section_id != $section->id) {
            // Does not exist or has wrong jobid or section_id, so create new and clear database
            $this->state = new PopulateFileListState();
            $this->state->jobid = $jobid;
            $this->state->section_id = $section->id;

            // Delete/create database table
            if (!DatabaseTables::createFilePopulationTable()) {
                $this->result->errors[] = __("Could not create database table needed for file population on the source site - This is normally because database user does not have access to create tables on the database.", "wpsynchro");
            }

            $this->result->state = $this->state;

            // Check if we have errors
            if (count($this->result->errors) > 0) {
                global $wpsynchro_container;
                $returnresult = $wpsynchro_container->get('class.ReturnResult');
                $returnresult->init();
                $returnresult->setDataObject($this->result);
                return $returnresult->echoDataFromRestAndExit();
            }
        }

        $this->result->state = $this->state;

        // Do some setup
        if ($type == "source") {
            $this->basepath = $section->source_basepath;
        } else {
            $this->basepath = $section->target_basepath;
        }

        // Check if state is completed, so we should start returning file lists
        if ($this->state->state == 'completed') {
            // Handle returning file lists
            $this->result->filelist = $this->getFileList(990, $this->state->files_downloaded_id_offset);
            $this->result->debugs[] = "PopulateFilelist REST: State is completed, returning: " . count($this->result->filelist) . " files";
        } else {
            $section->temp_locations_in_basepath = (array) $section->temp_locations_in_basepath;

            // If we are just getting started, set the root entries in database
            if ($this->state->state == 'start') {
                $this->handleStart($section);
            }

            // From this point on, we are in running mode, meaning doing expansion of dirs until no more time            
            $this->handleRunning($this->result);
        }

        // Update state in database
        update_option("wpsynchro_filepopulation_current", $this->state, false);

        // Return 
        global $wpsynchro_container;
        $returnresult = $wpsynchro_container->get('class.ReturnResult');
        $returnresult->init();
        $returnresult->setDataObject($this->result);
        return $returnresult->echoDataFromRestAndExit();
    }

    /**
     * Get file list on completion, in parts
     * @since 1.4.0
     */
    public function getFileList($max_count = 990, $id_offset)
    {
        global $wpdb;
        $filelist = array();
        $filelist_sqlresult = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix . "wpsynchro_file_population_list where id > %d order by id limit %d", $id_offset, $max_count));

        if ($filelist_sqlresult) {
            foreach ($filelist_sqlresult as $filedir) {
                $filelist[] = $this->getFileObject($filedir->source_file, $filedir->size, ($filedir->is_dir == 0 ? false : true), $filedir->hash);
                if ($filedir->id > $this->state->files_downloaded_id_offset) {
                    $this->state->files_downloaded_id_offset = $filedir->id;
                }
            }
        }

        return $filelist;
    }

    /**
     * Handle running phase of section population
     * @since 1.4.0
     */
    public function handleRunning()
    {
        // Get dirs that need expanding
        global $wpdb;

        // Run until there is less than 4 seconds remaining
        while ($this->timer->getRemainingSyncTime() > 4) {
            $dir_to_expand = $wpdb->get_row("select * from " . $wpdb->prefix . "wpsynchro_file_population_list where is_expanded=0 and is_dir=1 order by id limit 1");
            if ($dir_to_expand == null) {
                // No more dirs to expand, so we are complete     
                $this->state->state = "completed";
                $this->result->debugs[] = "PopulateFilelist REST: No more dirs - Set state to completed";
                return;
            }

            // Debug logging
            $this->result->debugs[] = "PopulateFilelist REST: Expanding " . $this->basepath . $dir_to_expand->source_file;

            // Set current item in state, so we can continue from it later
            $resume = false;
            if ($this->state->current_dir_state->id == 0) {
                $this->state->current_dir_state->id = $dir_to_expand->id;
            } else {
                $resume = true;
            }

            // Get files/dirs in dir
            $filter_iterator = $this->getPathIterator($this->basepath . $dir_to_expand->source_file);
            if ($filter_iterator === false) {
                return;
            }
            $fileobj_list = array();
            $is_completed = true;
            $check_timeout_timer = microtime(true);
            foreach ($filter_iterator as $fileinfo) {
                if ($fileinfo->isDot()) {
                    continue;
                }

                // Check if we should continue last session
                if ($resume) {
                    $path = $fileinfo->getPathname();
                    if ($path == $this->state->current_dir_state->current_path) {
                        $resume = false;
                    }
                    continue;
                }

                // Get pathname and set it in state, so we can continue, if we have to break out
                $pathname = $fileinfo->getPathname();
                $this->state->current_dir_state->current_path = $pathname;

                // Get data on this file
                $path = $this->common->fixPath($pathname);
                $fileobj_list[] = $this->getFileObject($path, ($fileinfo->isDir() ? 0 : $fileinfo->getSize()), $fileinfo->isDir());
                $this->state->files_found++;

                // check every 1 seconds, if we need to break out because of time or large filelist
                if ((microtime(true) - $check_timeout_timer) > 1) {
                    if ($this->timer->getRemainingSyncTime() < 5) {
                        $is_completed = false;
                        break;
                    }
                    $check_timeout_timer = microtime(true);

                    // check that file list does not contain more than X files - If so, break out, so we can write to db        
                    if (count($fileobj_list) > 990) {
                        $is_completed = false;
                        break;
                    }
                }
            }

            // If completed, mark the dir as expanded
            if ($is_completed) {
                $this->setPathExpanded($dir_to_expand->id);
            }

            // Insert data to db            
            $this->insertPathsToDB($fileobj_list);

            // Reset current dir, to prevent it continuing
            if ($is_completed) {
                $this->state->resetCurrentDirState();
            }
        }
    }

    /**
     * Get file/dir iterator on single dir
     * @since 1.4.0
     */
    public function getPathIterator($path)
    {
        try {
            $dir_iterator = new \DirectoryIterator($path);
            $filter_iterator = new FilterFilterIterator($dir_iterator);
            $filter_iterator::$FILTERS = $this->exclusions;
            $filter_iterator::$common = $this->common;
            $filter_iterator::$file_excludes = $this->file_excludes_in_webroot;
            return $filter_iterator;
        } catch (\Throwable $e) { // For PHP 7
            $this->result->debugs[] = "PopulateFilelist REST: Exception when trying to read dir " . $path . " with error: " . $e->getMessage();
            $this->result->errors[] = "Error during file population on " . get_home_url() . ": Can't read path: " . $path . ". Reason can be that path does not exist anymore or a permission issue.";
        } catch (\Exception $e) { // For PHP 5
            $this->result->debugs[] = "PopulateFilelist REST: Exception when trying to read dir: " . $e->getMessage();
            $this->result->errors[] = "Error during file population on " . get_home_url() . ": Can't read path: " . $path . ". Reason can be that path does not exist anymore or a permission issue.";
        }

        return false;
    }

    /**
     * Handle start phase of section population
     * @since 1.4.0
     */
    public function handleStart($section)
    {
        // If we have a preset to move all files, iterate through the file location.
        if ($section->type == "sync_preset_all_files") {
            // If preset is set, populate locations in basepath with all content of web root
            $section->temp_locations_in_basepath = array();
            $filter_iterator = $this->getPathIterator($this->basepath);

            if ($filter_iterator === false) {
                return;
            }

            foreach ($filter_iterator as $fileinfo) {
                if ($fileinfo->isDot()) {
                    continue;
                }

                $section->temp_locations_in_basepath["/" . $fileinfo->getFilename()] = true;
            }
        }

        // Check what work we need to do
        $paths = array();
        if (count($section->temp_locations_in_basepath) > 0) {
            foreach ($section->temp_locations_in_basepath as $relativepath => $whatever) {
                $paths[] = trailingslashit($this->basepath) . trim($relativepath, "/");
            }
        } else {
            $paths[] = $this->basepath;
        }
        $fileobj_list = array();
        foreach ($paths as $path) {
            $path = $this->common->fixPath($path);
            $found = true;
            if (!file_exists($path)) {
                // Try with utf8_decode
                $found = false;
                if (file_exists(utf8_decode($path))) {
                    $path = utf8_decode($path);
                    $found = true;
                }
            }
            if ($found) {
                $fileobj_list[] = $this->getFileObject($path, (is_dir($path) ? 0 : filesize($path)), is_dir($path));
                $this->state->files_found++;
            }
        }

        // Add filelist to database, so we can start digging in
        $this->insertPathsToDB($fileobj_list);

        // Change state to running
        $this->state->state = 'running';
    }

    /**
     * Get standard file structure from data
     * @since 1.4.0
     */
    public function getFileObject($name, $size, $is_dir, $hash = null)
    {

        $file_tmp = new \stdClass();
        $file_tmp->source_file = str_replace($this->basepath, "", $name);
        $file_tmp->size = $size;
        $file_tmp->is_dir = $is_dir;

        if ($hash == null) {
            if ($file_tmp->is_dir) {
                $file_tmp->hash = null;
            } else {
                if ($file_tmp->size == 0) {
                    $file_tmp->hash = 'd41d8cd98f00b204e9800998ecf8427e';
                } else {
                    if (file_exists($name) && is_readable($name)) {
                        $file_tmp->hash = md5_file($name);
                    } else {
                        $file_tmp->hash = "file_not_exist";
                    }
                }
            }
        } else {
            $file_tmp->hash = $hash;
        }

        return $file_tmp;
    }

    /**
     * Insert path to database
     * @since 1.4.0
     */
    public function insertPathsToDB($pathlist)
    {
        global $wpdb;
        $insert_query_part = "INSERT INTO " . $wpdb->prefix . "wpsynchro_file_population_list (source_file, hash, is_expanded, is_dir, size) VALUES ";
        $insert_value_part_arr = array();
        $insert_counter = 0;
        $insert_total_counter = 0;

        foreach ($pathlist as $path) {
            $insert_counter++;
            $insert_total_counter++;
            if ($insert_counter > 995) {
                $wpdb->query($insert_query_part . " " . implode(",", $insert_value_part_arr));
                $insert_counter = 0;
                $insert_value_part_arr = array();
            }

            // Add value part
            $insert_value_part_arr[] = $wpdb->prepare("(%s,%s,%d,%d,%d)", $path->source_file, $path->hash, ($path->is_dir ? 0 : 1), $path->is_dir, $path->size);
        }
        if ($insert_counter > 0) {
            $wpdb->query($insert_query_part . " " . implode(",", $insert_value_part_arr));
        }
    }

    /**
     * Set path as expanded in db
     * @since 1.4.0
     */
    public function setPathExpanded($id)
    {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . "wpsynchro_file_population_list", array(
            'is_expanded' => 1,
            ), array('id' => $id), array(
            '%d'
            ), array('%d')
        );
    }
}

class PopulateFileListState
{

    public $jobid = "";
    public $section_id = "";
    public $state = "start";
    public $files_found = 0;
    public $files_downloaded_id_offset = 0;
    public $current_dir_state = null;

    function __construct()
    {
        $this->current_dir_state = new \stdClass();
        $this->resetCurrentDirState();
    }

    public function resetCurrentDirState()
    {
        $this->current_dir_state->id = 0;
        $this->current_dir_state->current_path = "";
    }
}

class FilterFilterIterator extends \FilterIterator
{

    public static $FILTERS;
    public static $common;
    public static $file_excludes;

    public function accept()
    {

        $file = $this->current()->getPathname();
        $file = self::$common->fixPath($file);
        $filename = $this->current()->getFilename();

        if (in_array($filename, self::$file_excludes)) {
            return false;
        }

        foreach (self::$FILTERS as $filter) {
            if (strpos($file, $filter) > -1) {
                return false;
            }
        }
        return true;
    }
}
