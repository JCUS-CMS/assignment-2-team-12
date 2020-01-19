<?php
namespace WPSynchro\REST;

/**
 * Class for handling REST service "filetransfer" - Receiving files
 * @since 1.0.3
 */
class FileTransfer
{

    public function service(\WP_REST_Request $request)
    {

        // init 
        global $wpsynchro_container;
        $common = $wpsynchro_container->get("class.CommonFunctions");
        $timer = $wpsynchro_container->get("class.SyncTimerList");
        $timer->init();

        // Get transfer object, so we can get data
        $transfer = $wpsynchro_container->get("class.Transfer");
        $transfer->setEncryptionKey($common->getAccessKey());
        $transfer->populateFromString($request->get_body());
        $data = $transfer->getDataObject();
        $files = $transfer->getFiles();

        // Handle the files and filedata, writing it to disk as needed
        $transporthandler = $wpsynchro_container->get("class.TransportHandler");
        $result = $transporthandler->handleFileTransport($data, $files);

        // Return the result
        $returnresult = $wpsynchro_container->get('class.ReturnResult');
        $returnresult->init();
        $returnresult->setDataObject($result);
        return $returnresult->echoDataFromRestAndExit();
    }
}
