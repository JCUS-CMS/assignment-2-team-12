<?php
namespace WPSynchro\Pages;

/**
 * Class for handling what to show when clicking on the menu in wp-admin
 *
 * @since 1.0.0
 */
class AdminOverview
{

    public static function render()
    {

        $instance = new self;
        $instance->handleGET();
    }

    private function handleGET()
    {
        // Check php/wp/mysql versions
        global $wpsynchro_container;
        $commonfunctions = $wpsynchro_container->get('class.CommonFunctions');

        // Get success count, so we can ask for 5 star review
        $success_count = get_site_option("wpsynchro_success_count", 0);
        $request_review_dismissed = get_site_option("wpsynchro_dismiss_review_request", false);

        // Check for delete
        if (isset($_GET['delete'])) {
            $delete = $_GET['delete'];
        } else {
            $delete = "";
        }

        // If delete
        if (strlen($delete) > 0) {
            global $wpsynchro_container;
            $inst_factory = $wpsynchro_container->get('class.InstallationFactory');
            $inst_factory->deleteInstallation($delete);
        }

        // Check for duplicate
        if (isset($_GET['duplicate'])) {
            $duplicate = $_GET['duplicate'];
        } else {
            $duplicate = "";
        }

        // If duplicate
        if (strlen($duplicate) > 0) {
            global $wpsynchro_container;
            $inst_factory = $wpsynchro_container->get('class.InstallationFactory');
            $inst_factory->duplicateInstallation($duplicate);
        }

        // Check if healthcheck should be run
        $run_healthcheck = false;
        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            $licensing = $wpsynchro_container->get("class.Licensing");
            if ($licensing->hasProblemWithLicensing()) {
                $run_healthcheck = true;
            }
        }
        // Healthcheck, just run it every week
        if (!$run_healthcheck) {
            $healthcheck_last_success = intval(get_site_option("wpsynchro_healthcheck_timestamp", 0));
            $seconds_in_week = 604800; // 604800 is one week       
            if (($healthcheck_last_success + $seconds_in_week) < time()) {
                $run_healthcheck = true;
            }
        }

        // Localize the script with data
        $adminjsdata = array(
            'text_header' => __("Scheduling a synchronization", "wpsynchro"),
            'text_1' => sprintf(__("To schedule a job to run at a certain time or with a certain interval, you need to have %sWP CLI%s installed.", "wpsynchro"), "<a href='https://wp-cli.org/' target='_blank'>", "</a>"),
            'text_2' => __("With WP CLI installed, you can run this synchronization", "wpsynchro"),
            'text_3' => __("with this command", "wpsynchro"),
            'text_4' => __("Or if you want it in quiet mode, with no output", "wpsynchro"),
            'text_5' => __("You can add this command to cron and run it exactly how you want it.", "wpsynchro"),
        );
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_schedulejob', $adminjsdata);

        ?>
        <div id="wpsynchro-overview" class="wrap wpsynchro">
            <h2>WP Synchro <?= WPSYNCHRO_VERSION ?> <?php echo ( \WPSynchro\CommonFunctions::isPremiumVersion() ? 'PRO' : 'FREE' ); ?> - <?php _e('Overview of synchronizations', 'wpsynchro'); ?></h2>

            <?php
            // Healthcheck
            if ($run_healthcheck) {

                ?>
                <healthcheck></healthcheck>

                <?php
            }

            // Give a review notification
            if ($success_count >= 10 && !$request_review_dismissed) {
                $dismiss_url = add_query_arg(array('wpsynchro_dismiss_review_request' => 1), admin_url());

                ?>
                <div class="notice notice-success wpsynchro-dismiss-review-request" >
                    <p><?php echo sprintf(__("You have used WP Synchro %d times now - We hope you are enjoying it and have saved some time and troubles.<br>We try really hard to give you a high quality tool for WordPress site migrations.<br>If you enjoy using WP Synchro, we would appreciate your review on <a href='%s' target='_blank'>WordPress plugin repository</a>.<br>Thank you for the help.", "wpsynchro"), $success_count, "https://wordpress.org/support/plugin/wpsynchro/reviews/?rate=5#new-post") ?></p>
                    <p><a class="wpsynchrobutton" href="https://wordpress.org/support/plugin/wpsynchro/reviews/?rate=5#new-post" target="_blank"><?php _e('Rate WP Synchro on WordPress.org', 'wpsynchro'); ?></a> <button class="wpsynchrobutton-secondary" data-dismiss-url="<?php echo esc_url($dismiss_url); ?>"><?php _e('Dismiss forever', 'wpsynchro'); ?></button></p>
                </div>
                <?php
            }

            ?>

            <div id="overview-section-container">
                <div class="installations">
                    <?php
                    $table = new AdminOverviewTable();
                    $table->prepare_items();

                    ?>
                    <div class="typefilters addinstallation">                    
                        <?php $table->views(); ?>  
                        <a class="addlink" href="<?php menu_page_url('wpsynchro_addedit', true); ?>"><button class="wpsynchrobutton"><?php _e('Add installation', 'wpsynchro'); ?></button></a>
                    </div>
                    <form id="syncsetups" method="get">                   
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                        <?php $table->display(); ?>
                    </form>
                </div>
                <div class="cardboxes">
                    <?php
                    if (!\WPSynchro\CommonFunctions::isPremiumVersion()) {
                        $commonfunctions->getTemplateFile("card-pro-version");
                    }

                    $commonfunctions->getTemplateFile("card-mailinglist");
                    $commonfunctions->getTemplateFile("card-facebook");

                    ?>
                </div>
            </div>



        </div>
        <?php
    }
}
