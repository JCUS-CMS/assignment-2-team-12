<?php
namespace WPSynchro\Pages;

class AdminOverviewTable extends \WP_List_Table
{

    function __construct()
    {
        global $status, $page;

        // Set parent defaults
        parent::__construct(
            array(
                'singular' => 'wpsynchro-setup',
                'plural' => 'wpsynchro-setups',
                'ajax' => false,
            )
        );
    }

    function column_default($item, $column_name)
    {
        return print_r($item, true);
    }

    function column_name($item)
    {
        $editlink = add_query_arg('syncid', $item->id, menu_page_url('wpsynchro_addedit', false));
        $deletelink = add_query_arg('delete', $item->id, menu_page_url('wpsynchro_overview', false));
        $duplicatelink = add_query_arg('duplicate', $item->id, menu_page_url('wpsynchro_overview', false));
        $actions = array();
        $actions['duplicate'] = sprintf("<a href='%s'>%s</a>", $duplicatelink, __("Duplicate", "wpsynchro"));
        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            $actions['schedule'] = "<schedule-job name='" . htmlspecialchars($item->name) . "' id='" . $item->id . "'>" . __('Schedule', 'wpsynchro') . "</schedule-job>";
        }
        $actions['delete'] = sprintf("<a href='%s' onclick='return confirm(\"" . __('%s', 'wpsynchro') . "\"); '>%s</a>", $deletelink, __("Are you sure you want to delete this?", "wpsynchro"), __("Delete", "wpsynchro"));

        $name = "<a href='" . $editlink . "'>" . htmlspecialchars($item->name) . '</a>';

        return sprintf('%1$s %2$s', $name, $this->row_actions($actions));
    }

    function column_type($item)
    {
        return strtoupper($item->type);
    }

    function column_site($item)
    {
        return stripslashes($item->site_url);
    }

    function column_location($item)
    {

        return __('Local installation', 'wpsynchro');
    }

    function column_synchronizes($item)
    {
        $synchronized_text = '';

        if ($item->sync_database) {
            $synchronized_text .= __('Database', 'wpsynchro');
        }
        if ($item->sync_files) {
            $synchronized_text .= __('Files', 'wpsynchro');
        }
        return $synchronized_text;
    }

    function column_description($item)
    {
        $desc = $item->getOverviewDescription();
        return $desc;
    }

    function column_actions($item)
    {

        $actions = '';

        if ($item->canRun()) {
            $runlink = add_query_arg(array('syncid' => $item->id), menu_page_url('wpsynchro_run', false));
            $actions .= "<a class='button runsyncjob' href='" . $runlink . "'>" . __('Run now', 'wpsynchro') . '</a>';
        } else {
            $actions .= "<a class='button runsyncjob' style='cursor:not-allowed;' title='" . __('Installation can not be run - See description', 'wpsynchro') . "' href='#' disabled>" . __('Run now', 'wpsynchro') . '</a>';
        }

        return $actions;
    }

    function get_columns()
    {
        $columns = array(
            'name' => __('Name', 'wpsynchro'),
            'type' => __('Type', 'wpsynchro'),
            'description' => __('Description', 'wpsynchro'),
            'actions' => __('Actions', 'wpsynchro'),
        );
        return $columns;
    }

    function get_table_classes()
    {
        return array('widefat', 'striped', $this->_args['plural']);
    }

    function get_views()
    {
        $views = array();
        $current = (!empty($_REQUEST['type']) ? $_REQUEST['type'] : 'all' );

        // All link
        $class = ( $current == 'all' ? ' class="current"' : '' );
        $all_url = remove_query_arg('type');
        $views['all'] = "<a href='{$all_url }' {$class} >" . __('ALL TYPES', 'wpsynchro') . '</a>';

        global $wpsynchro_container;
        $installation_class = $wpsynchro_container->get('class.Installation');
        foreach ($installation_class::SYNC_TYPES as $type) {
            $type = strtoupper($type);
            $class = ( $current == $type ? ' class="current"' : '' );
            $temp_url = add_query_arg(
                array(
                    'type' => $type,
                    'paged' => 1,
                )
            );
            $views[$type] = "<a href='{$temp_url}' {$class} >{$type}</a>";
        }

        return $views;
    }

    function prepare_items()
    {
        $per_page = 20;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /*
         *  Get data to present in table
         */

        global $wpsynchro_container;
        $inst_factory = $wpsynchro_container->get('class.InstallationFactory');
        $data = $inst_factory->getAllInstallations();

        if (isset($_GET['type']) && strlen($_GET['type']) > 0) {
            $type_sort = strtolower($_GET['type']);
            foreach ($data as $key => $installation) {
                if (strtolower($installation->type) != $type_sort) {
                    unset($data[$key]);
                }
            }
        }

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
