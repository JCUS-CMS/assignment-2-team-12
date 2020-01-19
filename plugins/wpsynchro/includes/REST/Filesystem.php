<?php
namespace WPSynchro\REST;

/**
 * Class for handling REST service "filesystem"
 * Call should already be verified by permissions callback
 *
 * @since 1.2.0
 */
class Filesystem
{

    public function service($request)
    {

        // Extract parameters
        $parameters = $request->get_json_params();
        if (isset($parameters['path'])) {
            $path = $parameters['path'];
        } else {
            return new \WP_REST_Response(null, 400);
        }

        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");

        // Paths that should NOT be syncable

        $locked_paths = array();
        $locked_paths[] = $common->fixPath(trim($common->getLogLocation(), '/'));
        $locked_paths[] = $common->fixPath(trim(WPSYNCHRO_PLUGIN_DIR, '/'));
        $locked_paths[] = $common->fixPath(ABSPATH . "wp-admin");
        $locked_paths[] = $common->fixPath(ABSPATH . "wp-includes");
        $files_in_webroot = $common->getWPFilesInWebrootToExclude();
        foreach ($files_in_webroot as $filewebroot) {
            $locked_paths[] = $common->fixPath(ABSPATH . $filewebroot);
        }
 
        $result = new \stdClass();
        $pathdata_list = array();

        if (file_exists($path)) {
            $files = array();
            $presorteddata = array_diff(scandir($path), array('..', '.'));
            foreach ($presorteddata as $file) {
                if (is_file($file))
                    array_push($files, $file);
                else
                    array_unshift($files, $file);
            }

            foreach ($files as $file) {
                $pathdata = new PathData();
                $pathdata->absolutepath = trailingslashit($path) . $file;
                if (is_file($pathdata->absolutepath)) {
                    $pathdata->is_file = true;
                } else {
                    // is dir, check for subdirs
                    $directories = array_diff(scandir($pathdata->absolutepath), array('..', '.'));
                    if ($directories != false && count($directories) > 0) {
                        $pathdata->dir_has_content = true;
                        $pathdata->is_expanded = false;
                    }
                }
                $pathdata->basename = basename($pathdata->absolutepath);

                // Check for locked paths
                foreach ($locked_paths as $lpath) {
                    if (strpos($pathdata->absolutepath, $lpath) !== false) {
                        $pathdata->locked = true;
                        break;
                    }
                }

                $pathdata_list[] = $pathdata;
            }
        }

        $result->pathdata = $pathdata_list;

        return new \WP_REST_Response($result, 200);
    }
}

class PathData
{

    public $pathkey = "";
    public $absolutepath = "";
    public $basename = "";
    public $is_file = false;
    public $dirname = "";
    public $dir_has_content = false;
    public $children = array();
    public $locked = false;

    function __construct()
    {
        $this->pathkey = uniqid();
    }
}
