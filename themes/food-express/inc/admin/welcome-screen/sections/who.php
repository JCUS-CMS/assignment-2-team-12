<?php
/**
 * Welcome screen who are woo template
 */
?>
<?php
	$my_theme = wp_get_theme();
	$authorURI = $my_theme->get( 'AuthorURI' );
 ?>
<hr />
<div id="who" class="feature-section col three-col" style="margin-bottom: 1.618em; padding-top: 1.618em; overflow: hidden;">

	<div class="col-1">

		<h4><?php _e( 'Who are Template Express?', 'food-express' ); ?></h4>
		<p><?php _e( 'Template Express are a small team of passionate WordPress enthusists who love producing themes that people are excited to use.', 'food-express' ); ?></p>
		<p><?php echo sprintf( esc_html__('%1$sVisit Template Express%2$s', 'food-express'), '<a href="' . $authorURI . '" class="button"  target="_blank">', '</a>'); ?></p>
	</div>

	<div class="col-2">
		<h4><?php _e( 'Are you enjoying Food Express?', 'food-express' ); ?></h4>
		<p><?php echo sprintf( esc_html__( 'Why not leave a review on %1$sWordPress.org%2$s? We\'d really appreciate it! :-)', 'food-express' ), '<a href="https://wordpress.org/themes/food-express"  target="_blank">', '</a>' ); ?></p>
	</div>

	<div class="col-3 last-feature">
		<img src="<?php echo esc_url( get_template_directory_uri() ) . '/inc/admin/welcome-screen/img/TE-logo.png'; ?>" alt="<?php _e( 'The Template Express Team', 'food-express' ); ?>" class="image-50" width="220" />
	</div>

</div>
