<?php
/**
 * Welcome screen getting started template
 */
?>
<?php
// get theme customizer url
$url 	= admin_url() . 'customize.php?';
$url 	.= '&return=' . urlencode( admin_url() . 'themes.php?page=food-express-welcome' );
$url 	.= '&food-express-customizer=true';
?>
<div id="getting_started" class="col two-col panel" style="margin-bottom: 1.618em; padding-top: 1.618em; overflow: hidden;">

	<div class="col-1">

		<!-- MENUS -->
		<h4><?php _e( 'Configure menu locations <span class="dashicons dashicons-menu"></span>' ,'food-express' ); ?></h4>
		<p><?php _e( 'Food Express includes <strong>two</strong> menu locations for Primary and Social navigation. Social navigation menu will display links of the social network\'s logo.', 'food-express' ); ?></p>
		<p><a href="<?php echo esc_url( self_admin_url( 'nav-menus.php' ) ); ?>" class="button"><?php _e( 'Configure menus', 'food-express' ); ?></a></p>

		<!-- CUSTOMIZER -->
		<h4><?php _e( 'Hundreds of options available <span class="dashicons dashicons-admin-generic"></span>' ,'food-express' ); ?></h4>
		<p><?php _e( 'Using the WordPress Customizer you can change Food Express\'s appearance to match your brand and create a unique look.', 'food-express' ); ?></p>
		<p><a href="<?php echo esc_url( $url ); ?>" class="button"><?php _e( 'Open the Customizer', 'food-express' ); ?></a></p>

	</div>

	<div class="col-2 last-feature">

		<!-- HOMEPAGE -->
		<h4><?php _e( 'Configure homepage template <span class="dashicons dashicons-admin-home"></span>', 'food-express' ); ?></h4>
		<ol>
			<li><?php echo __('Create a new page,', 'food-express'); ?></li>
			<li><?php echo __('assign it the Homepage template,', 'food-express'); ?></li>
			<li><?php echo sprintf( esc_html__('set the new page as a static homepage in the %1$sReading%2$s settings,', 'food-express'), '<a href="' . esc_url( self_admin_url( 'options-reading.php' ) ) . '">', '</a>' ); ?></li>
			<li><?php echo __('customize the settings in the customizer.', 'food-express'); ?></li>
		</ol>

		<p><?php echo sprintf( esc_html__( 'Once you set up the Homepage you can toggle and re-order the homepage components using the %1$sHomepage Control%2$s plugin.', 'food-express' ), '<a href="https://wordpress.org/plugins/homepage-control/" target="_blank">', '</a>' ); ?></p>

		<?php if ( ! class_exists( 'Homepage_Control' ) ) { ?>
			<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=homepage-control' ), 'install-plugin_homepage-control' ) ); ?>" class="button button-primary" target="_blank"><?php _e( 'Install Homepage Control', 'food-express' ); ?></a></p>
		<?php } ?>


	</div>
</div>
