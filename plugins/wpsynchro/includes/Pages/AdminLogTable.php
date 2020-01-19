<?php
namespace WPSynchro\Pages;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AdminLogTable extends \WP_List_Table
{

    function __construct()
    {
        global $status, $page;

        // Set parent defaults
        parent::__construct(
            array(
                'singular' => 'wpsynchro-logs',
                'plural' => 'wpsynchro-logs',
                'ajax' => false,
            )
        );
    }

    function column_default($item, $column_name)
    {
        return print_r($item, true);
    }

    function column_date($item)
    {
        $date = $item->start_time;
        //$dateobj = new \DateTime($date);

        return date_i18n(get_option('date_format') . " " . get_option('time_format'), $date);
    }

    function column_description($item)
    {
        $desc = $item->description;
        return $desc;
    }

    function column_state($item)
    {
        $state = $item->state;
        if ($state == 'started') {
            return __('Started', 'wpsynchro');
        } else if ($state == 'completed') {
            return __('Completed', 'wpsynchro');
        } else {
            return __('Unknown', 'wpsynchro');
        }
    }

    function column_logfile($item)
    {
        $job_id = $item->job_id;

        // Check if file exist
        global $wpsynchro_container;
        $common = $wpsynchro_container->get('class.CommonFunctions');

        $logpath = $common->getLogLocation();
        $filename = $common->getLogFilename($job_id);

        if (file_exists($logpath . $filename)) {
            $showloglink = add_query_arg(array('showlog' => $item->job_id, 'inst' => $item->installation_id), menu_page_url('wpsynchro_log', false));
            $downloadloglink = rest_url("wpsynchro/v1/downloadlog/?job_id=" . $item->job_id . "&inst_id=" . $item->installation_id . "&_wpnonce=" . wp_create_nonce('wp_rest'));
            $loglinks = "<a href='" . $showloglink . "' class='button'>" . __('Show log', 'wpsynchro') . "</a>";
            $loglinks .= " <a href='" . $downloadloglink . "' class='button'>" . __('Download log', 'wpsynchro') . "</a>";
            return $loglinks;
        } else {
            return "N/A";
        }
    }

    function get_columns()
    {
        $columns = array(
            'date' => __('Synchronization date', 'wpsynchro'),
            'state' => __('Status', 'wpsynchro'),
            'description' => __('Description', 'wpsynchro'),
            'logfile' => __('Logfile', 'wpsynchro'),
        );
        return $columns;
    }

    function get_table_classes()
    {
        return array('widefat', 'striped', $this->_args['plural']);
    }

    function prepare_items()
    {
        $per_page = 25;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /*
         *  Get data to present in table
         */

        global $wpsynchro_container;
        $metadatalog = $wpsynchro_container->get('class.SyncMetadataLog');
        $data = $metadatalog->getAllLogs();
        $data = array_reverse($data);

        $current_page = $this->get_pagenum();

        $total_items = count($data);

        $this->items = array_slice($data, ( ( $current_page - 1 ) * $per_page), $per_page);

        $this->set_pagination_args(
            array(
                'total_items' => $total_items, // WE have to calculate the total number of items
                'per_page' => $per_page, // WE have to determine how many items to show on a page
                'total_pages' => ceil($total_items / $per_page), // WE have to calculate the total number of pages
            )
        );
    }
}
