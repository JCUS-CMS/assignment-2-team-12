<?php
namespace WPSynchro\Transport;

/**
 * Simple data class for transfer file
 *
 * @since 1.3.0
 */
class TransferFile
{

    public $key;
    public $section;
    public $filename;
    public $is_partial = false;
    public $is_dir = false;
    public $partial_start = -1;
    public $partial_end = -1;
    public $data = null;
    public $hash = "";
    public $size = 0;
    public $target_file = "";
    public $is_error = false;   // Such as cannot read anymore or permission error

    public static function mapper($object_stdclass)
    {
        $transferfile = new TransferFile();
        foreach ($transferfile as $key => $value) {
            if (isset($object_stdclass->$key)) {
                $transferfile->$key = $object_stdclass->$key;
            }
        }
        return $transferfile;
    }
 
}
