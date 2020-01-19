<?php
namespace WPSynchro\Transport;

/**
 * Class for handling result of transport
 * @since 1.3.0
 */
class RemoteTransportResult
{

    public $url;
    public $args;
    // Data
    public $response_object;
    public $body;
    public $files;
    public $body_length;
    // HTTP
    public $statuscode;
    // Errors, warnings etc
    public $errors = array();
    public $warnings = array();
    public $infos = array();
    public $debugs = array();
    // Success or not
    public $success = false;

    public function getBody()
    {
        return $this->body;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getBodyLength()
    {
        return $this->body_length;
    }

    public function getStatuscode()
    {
        return $this->statuscode;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function getDebugs()
    {
        return $this->debugs;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function getResponseObject()
    {
        return $this->response_object;
    }

    public function writeMessagesToLog()
    {
        global $wpsynchro_container;
        $logger = $wpsynchro_container->get("class.Logger");

        foreach ($this->errors as $errortext) {
            $logger->log("ERROR", $errortext);
        }
        $this->errors = array();

        foreach ($this->warnings as $warningtext) {
            $logger->log("WARNING", $warningtext);
        }
        $this->warnings = array();

        foreach ($this->infos as $infolog) {
            $logger->log("INFO", $infolog);
        }
        $this->infos = array();

        foreach ($this->debugs as $debuglog) {
            $logger->log("DEBUG", $debuglog);
        }
        $this->debugs = array();
    }

    public function parseResponse(&$response, $url, $args, $job)
    {
        $this->url = $url;
        $this->args = $args;
        $this->response_object = $response;

        global $wpsynchro_container;
        $commonfunctions = $wpsynchro_container->get('class.CommonFunctions');

        // Check if WP error
        if (is_wp_error($this->response_object)) {
            $errormsg = $this->response_object->get_error_message();
            if (strpos($errormsg, "cURL error 60") > -1) {
                $this->errors[] = __("Remote or local SSL certificate is not valid or self-signed. To allow non-valid SSL certificates, you need to edit the installation and change it.", "wpsynchro");
                $this->errors[] = "Remote or local SSL certificate is not valid or self-signed.";
                $this->debugs[] = print_r($this->response_object, true);
            } else {             
                $this->debugs[] = "Remote service '" . $this->url . "' failed with WP error: " . $errormsg;
                $this->debugs[] = print_r($this->response_object, true);
            }
        } else {

            // Check statuscode
            $this->statuscode = wp_remote_retrieve_response_code($this->response_object);

            // check if wpsynchrotransfer or json         
            $body_data = wp_remote_retrieve_body($this->response_object);
            $this->body_length = strlen($body_data);
            if (substr($body_data, 0, strlen("WPSYNCHROTRANSFER")) == "WPSYNCHROTRANSFER") {
                global $wpsynchro_container;
                $transfer = $wpsynchro_container->get('class.Transfer');
                // Check if it is local or remote  
                if (strpos($url, $job->from_rest_base_url) !== false) {
                    $transfer->setEncryptionKey($job->from_accesskey);
                } else {
                    $transfer->setEncryptionKey($job->to_accesskey);
                }
                $transfer->populateFromString($body_data);
                $this->body = $transfer->getDataObject();
                $this->files = $transfer->getFiles();
            } else {
                // JSON
                $body_json = $commonfunctions->cleanRemoteJSONData($body_data);
                $this->body = json_decode($body_json);
            }

            if ($this->statuscode == 200) {
                $this->success = true;
            } else {
                $this->debugs[] = "Error calling REST service - Got HTTP " . $this->statuscode . " on this url: " . $this->url;
                $this->debugs[] = htmlentities(substr(print_r($this->response_object, true),0,5000));
            }

            // Check for errors
            if (isset($this->body->errors)) {
                $this->errors = array_merge($this->errors, $this->body->errors);
                unset($this->body->errors);
            }

            // Check for warnings
            if (isset($this->body->warnings)) {
                $this->warnings = array_merge($this->warnings, $this->body->warnings);
                unset($this->body->warnings);
            }

            // Check for infos
            if (isset($this->body->infos)) {
                $this->infos = array_merge($this->infos, $this->body->infos);
                unset($this->body->infos);
            }

            // Check for debugs
            if (isset($this->body->debugs)) {
                $this->debugs = array_merge($this->debugs, $this->body->debugs);
                unset($this->body->debugs);
            }
        }
    }
}
