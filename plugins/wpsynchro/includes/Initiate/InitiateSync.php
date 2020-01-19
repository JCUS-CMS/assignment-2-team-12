<?php
namespace WPSynchro\Initiate;

/**
 * Class for handling the initiate of the sync
 *
 * @since 1.0.0
 */
class InitiateSync
{

    // Base data
    public $installation = null;
    public $job = null;
    public $logger = null;
    public $timer = null;

    /**
     *  Constructor
     */
    public function __construct()
    {

    }

    /**
     *  Initiate sync
     *  @since 1.0.0
     */
    public function initiateSynchronization(&$installation, &$job)
    {
        $this->installation = $installation;
        $this->job = $job;

        // Start timer
        global $wpsynchro_container;
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");
        $initiate_timer = $this->timer->startTimer("initiate", "overall", "timer");

        // Init logging
        global $wpsynchro_container;
        $this->logger = $wpsynchro_container->get("class.Logger");
        $common = $wpsynchro_container->get("class.CommonFunctions");

        $this->logger->log("INFO", "Initating with remote and local host with remaining time:" . $this->timer->getRemainingSyncTime());

        // Start synchronization in metadatalog
        $metadatalog = $wpsynchro_container->get('class.SyncMetadataLog');
        $metadatalog->startSynchronization($this->job->id, $this->installation->id, $this->installation->getOverviewDescription());

        // Start by getting local transfer token
        $local_host = trailingslashit(get_rest_url());
        $clientip = $common->getClientIPAddress();
        $local_url = $local_host . "wpsynchro/v1/initiate/";
        $local_url = add_query_arg(array('type' => "local", 'frontend_ip' => $clientip[0]), $local_url);
        $local_token = $this->retrieveTransferTokenFromURL($local_url);
        // Check token
        if (strlen($local_token) < 20) {
            $this->logger->log("CRITICAL", __("Failed initializing - Could not get a valid token from local server", "wpsynchro"));
        }

        // Get remote transfertoken
        $remote_host = trailingslashit($this->installation->site_url) . "wp-json/";
        $remote_url = $remote_host . "wpsynchro/v1/initiate/?type=" . $this->installation->type;
        $remote_token = $this->retrieveTransferTokenFromURL($remote_url);
        // Check token
        if (strlen($remote_token) < 20) {
            $this->logger->log("CRITICAL", __("Failed initializing - Could not get a valid token from remote server", "wpsynchro"));
        }

        // If no errors, set transfer tokens in job object
        if (count($this->job->errors) == 0) {
            // Set tokens in job
            $local_transfer_token = $common->getTransferToken($common->getAccessKey(), $local_token);
            $remote_transfer_token = $common->getTransferToken($this->installation->access_key, $remote_token);

            if ($this->installation->type == 'pull') {
                $this->job->from_rest_base_url = $remote_host;
                $this->job->from_token = $remote_transfer_token;
                $this->job->from_accesskey = $this->installation->access_key;
                $this->job->to_rest_base_url = $local_host;
                $this->job->to_token = $local_transfer_token;
                $this->job->to_accesskey = $common->getAccessKey();
            } else {
                $this->job->to_rest_base_url = $remote_host;
                $this->job->to_token = $remote_transfer_token;
                $this->job->to_accesskey = $this->installation->access_key;
                $this->job->from_rest_base_url = $local_host;
                $this->job->from_token = $local_transfer_token;
                $this->job->from_accesskey = $common->getAccessKey();
            }

            $this->job->local_transfer_token = $local_transfer_token;
            $this->job->remote_transfer_token = $remote_transfer_token;

            // Final checks
            if (strlen($this->job->to_token) < 10) {
                $this->logger->log("CRITICAL", __("Failed initializing - No 'to' token could be found after initialize", "wpsynchro"));
            }
            if (strlen($this->job->from_token) < 10) {
                $this->logger->log("CRITICAL", __("Failed initializing - No 'from' token could be found after initialize", "wpsynchro"));
            }
        }

        $this->logger->log("INFO", "Initation completed on: " . $this->timer->endTimer($initiate_timer) . " seconds");

        if (count($this->job->errors) == 0) {
            $this->job->initiation_completed = true;
        }
    }

    /**
     *  Retrieve transfer token from url
     *  @since 1.2.0
     */
    public function retrieveTransferTokenFromURL($url)
    {
        if (count($this->job->errors) > 0) {
            return;
        }

        $this->logger->log("DEBUG", "Calling initate remote service with url: " . $url);
        
        // Get remote transfer object
        global $wpsynchro_container;
        $remotetransport = $wpsynchro_container->get('class.RemoteTransfer');
        $remotetransport->init();
        $remotetransport->setUrl($url);
        $initiate_result = $remotetransport->remotePOST();
  
        if ($initiate_result->isSuccess()) {
            $body = $initiate_result->getBody();
            if (isset($body->token)) {
                $this->logger->log("DEBUG", "Got initiate token: " . $body->token);
                return $body->token;
            } else {
                $this->logger->log("DEBUG", "Failed initializing - Could not fetch a initiation token from remote -  Response body:", $body);
            }
        } else {
            $this->job->errors[] = __("Failed during initialization, which means we can not continue the synchronization.", "wpsynchro");   
        }
        return "";
    }
}
