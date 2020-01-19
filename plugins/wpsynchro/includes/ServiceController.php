<?php
namespace WPSynchro;

/**
 * Class for setting up the service controller
 *
 * @since 1.0.0
 */
class ServiceController
{

    private $map = array();
    private $singletons = array();

    public function add($identifier, $function)
    {
        $this->map[$identifier] = $function;
    }

    public function get($identifier)
    {
        if (isset($this->singletons[$identifier])) {
            return $this->singletons[$identifier];
        }
        return $this->map[$identifier]();
    }

    public function share($identifier, $function)
    {
        $this->singletons[$identifier] = $function();
    }

    public static function init()
    {

        global $wpsynchro_container;
        $wpsynchro_container = new ServiceController();

        /*
         *  InstallationFactory
         */
        $wpsynchro_container->share(
            'class.InstallationFactory', function() {
            return new \WPSynchro\InstallationFactory();
        }
        );

        /*
         *  Installation
         */
        $wpsynchro_container->add(
            'class.Installation', function() {
            return new \WPSynchro\Installation();
        }
        );

        /*
         *  Job
         */
        $wpsynchro_container->add(
            'class.Job', function() {
            return new \WPSynchro\Job();
        }
        );

        /*
         *  InitiateSync
         */
        $wpsynchro_container->add(
            'class.InitiateSync', function() {
            return new \WPSynchro\Initiate\InitiateSync();
        }
        );

        /*
         *  MasterdataSync
         */
        $wpsynchro_container->add(
            'class.MasterdataSync', function() {
            return new \WPSynchro\Masterdata\MasterdataSync();
        }
        );

        /*
         *  DatabaseBackup
         */
        $wpsynchro_container->add(
            'class.DatabaseBackup', function() {
            return new \WPSynchro\Database\DatabaseBackup();
        }
        );

        /*
         *  DatabaseSync
         */
        $wpsynchro_container->add(
            'class.DatabaseSync', function() {
            return new \WPSynchro\Database\DatabaseSync();
        }
        );

        /*
         *  DatabaseFinalize
         */
        $wpsynchro_container->add(
            'class.DatabaseFinalize', function() {
            return new \WPSynchro\Database\DatabaseFinalize();
        }
        );

        /*
         *  FilesSync
         */
        $wpsynchro_container->add(
            'class.FilesSync', function() {
            return new \WPSynchro\Files\FilesSync();
        }
        );

        /*
         *  SyncList
         */
        $wpsynchro_container->add(
            'class.SyncList', function() {
            return new \WPSynchro\Files\SyncList();
        }
        );

        /*
         *  PopulateListHandler
         */
        $wpsynchro_container->add(
            'class.PopulateListHandler', function() {
            return new \WPSynchro\Files\PopulateListHandler();
        }
        );

        /*
         *  PathHandler
         */
        $wpsynchro_container->add(
            'class.PathHandler', function() {
            return new \WPSynchro\Files\PathHandler();
        }
        );

        /*
         *  TransferFiles
         */
        $wpsynchro_container->add(
            'class.TransferFiles', function() {
            return new \WPSynchro\Files\TransferFiles();
        }
        );

        /*
         *  TransportHandler
         */
        $wpsynchro_container->add(
            'class.TransportHandler', function() {
            return new \WPSynchro\Files\TransportHandler();
        }
        );

        /*
         *  FinalizeFiles
         */
        $wpsynchro_container->add(
            'class.FinalizeFiles', function() {
            return new \WPSynchro\Files\FinalizeFiles();
        }
        );

        /*
         *  FinalizeSync
         */
        $wpsynchro_container->add(
            'class.FinalizeSync', function() {
            return new \WPSynchro\Finalize\FinalizeSync();
        }
        );

        /*
         *  Location
         */
        $wpsynchro_container->add(
            'class.Location', function() {
            return new \WPSynchro\Files\Location();
        }
        );

        /*
         *  SynchronizeController - Singleton
         */
        $wpsynchro_container->share(
            'class.SynchronizeController', function() {
            return new \WPSynchro\SynchronizeController();
        }
        );

        /*
         *  SynchronizeStatus
         */
        $wpsynchro_container->add(
            'class.SynchronizeStatus', function() {
            return new \WPSynchro\Status\SynchronizeStatus();
        }
        );

        /*
         *  CommonFunctions
         */
        $wpsynchro_container->share(
            'class.CommonFunctions', function() {
            return new \WPSynchro\CommonFunctions();
        }
        );

        /*
         *  DebugInformation
         */
        $wpsynchro_container->add(
            'class.DebugInformation', function() {
            return new \WPSynchro\Utilities\DebugInformation();
        }
        );

        /*
         *  Licensing 
         */
        $wpsynchro_container->add(
            'class.Licensing', function() {
            return new \WPSynchro\Licensing();
        }
        );

        /**
         *  UpdateChecker
         */
        $wpsynchro_container->add(
            'class.UpdateChecker', function() {

            if (!class_exists("Puc_v4_Factory")) {
                require dirname(__FILE__) . '/Updater/Puc/v4p5/Factory.php';
                require dirname(__FILE__) . '/Updater/Puc/v4/Factory.php';
                require dirname(__FILE__) . '/Updater/Puc/v4p5/Autoloader.php';
                new \Puc_v4p5_Autoloader();
                \Puc_v4_Factory::addVersion('Plugin_UpdateChecker', 'Puc_v4p5_Plugin_UpdateChecker', '4.5');
            }

            $updatechecker = \Puc_v4_Factory::buildUpdateChecker(
                    'https://wpsynchro.com/update/?action=get_metadata&slug=wpsynchro', WPSYNCHRO_PLUGIN_DIR . 'wpsynchro.php', 'wpsynchro'
            );

            return $updatechecker;
        }
        );

        /**
         *  Logger
         */
        $wpsynchro_container->share(
            'class.Logger', function() {

            $logpath = wp_upload_dir()['basedir'] . "/wpsynchro/";
            $logger = new \WPSynchro\Logger\FileLogger;
            $logger->setFilePath($logpath);

            $enable_debuglogging = get_option('wpsynchro_debuglogging_enabled');
            if ($enable_debuglogging && strlen($enable_debuglogging) > 0) {
                $logger->log_level_threshold = "DEBUG";
            } else {
                $logger->log_level_threshold = "INFO";
            }

            return $logger;
        }
        );

        /**
         *  MetadataLog - for saving data on a sync run
         */
        $wpsynchro_container->share(
            'class.SyncMetadataLog', function() {
            return new \WPSynchro\Logger\SyncMetadataLog();
        }
        );

        /**
         *  SyncTimerList - Controls all the timers during sync
         */
        $wpsynchro_container->share(
            'class.SyncTimerList', function() {
            return new \WPSynchro\Utilities\SyncTimerList();
        }
        );

        /**
         *  Transfer - Get transfer object
         */
        $wpsynchro_container->add(
            'class.Transfer', function() {
            return new \WPSynchro\Transport\Transfer();
        }
        );

        /**
         *  RemoteTransfer - Get transfer object to move and receive data
         */
        $wpsynchro_container->add(
            'class.RemoteTransfer', function() {
            return new \WPSynchro\Transport\RemoteTransport();
        }
        );

        /**
         *  RemoteTransferResult - Result of remote transfer, to be used in code
         */
        $wpsynchro_container->add(
            'class.RemoteTransferResult', function() {
            return new \WPSynchro\Transport\RemoteTransportResult();
        }
        );

        /**
         *  ReturnResult - Return data from REST service (wrapper for Transfer object)
         */
        $wpsynchro_container->add(
            'class.ReturnResult', function() {
            return new \WPSynchro\Transport\ReturnResult();
        }
        );

        /**
         *  MU Plugin handler
         */
        $wpsynchro_container->share(
            'class.MUPluginHandler', function() {
            return new \WPSynchro\Utilities\Compatibility\MUPluginHandler();
        }
        );

        /**
         *  WP CLI command Handler
         */
        $wpsynchro_container->add(
            'class.WPSynchroCLI', function() {
            return new \WPSynchro\CLI\WPCLICommand();
        }
        );
    }
}
