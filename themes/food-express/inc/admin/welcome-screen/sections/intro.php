<?php
/**
 * Welcome screen intro template
 */
?>
<?php
	$my_theme = wp_get_theme();
	$version = $my_theme->get( 'Version' );
	$authorURI = $my_theme->get( 'AuthorURI' );
 ?>
<div class="col two-col" style="margin-bottom: 1.618em; overflow: hidden;">
	<div class="col-1">
		<h1 style="margin-right: 0;"><?php echo '<strong>Food Express</strong> <sup style="font-weight: bold; font-size: 50%; padding: 5px 10px; color: #666; background: #fff;">' . esc_attr( $version ) . '</sup>'; ?></h1>

		<p style="font-size: 1.2em;"><?php _e( 'Excellent! You\'ve activated Food Express, we hope you enjoy the theme.', 'food-express' ); ?></p>
		</p><?php _e( 'If you would like to see any features added or want to report bug with this theme, send us an email at <a href="mailto:support@templateexpress.com">support@templateexpress.com</a>.', 'food-express' ); ?>

		<!-- DOCUMENTATION -->
		<h4><?php _e( 'View documentation <span class="dashicons dashicons-welcome-learn-more"></span>', 'food-express' ); ?></h4>
		<p><?php _e( 'You can read detailed information on Food Express\'s features and how to develop on top of it in the documentation.', 'food-express' ); ?></p>
		<p><?php echo sprintf( esc_html('%1$sView documentation%2$s', 'food-express'), '<a href="' . $authorURI . '/docs/food-express" target="_blank" class="button">', '</a>'); ?></p>

	</div>

	<div class="col-2 last-feature">
		<img src="<?php echo esc_url( get_template_directory_uri() ) . '/screenshot.png'; ?>" alt="<?php echo __('Food Express Screenshot', 'food-express'); ?>" class="image-50" width="440" />
	</div>
</div>
