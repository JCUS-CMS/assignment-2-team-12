<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Food Express
 */

?>

	</div><!-- #content -->


</div><!-- #page -->

<footer id="colophon" class="site-footer" role="contentinfo">
  <?php $container_class = get_theme_mod( 'food_express_footer_container_class', true ) ? 'container' : 'fluid' ?>
  <div class="site-footer__content <?php echo esc_attr( $container_class ); ?>">
		<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
		<?php
			if ( has_nav_menu( 'social' ) ) {
				food_express_get_social_menu();
			}
		?>
		<?php

			$show_pre_head 		= false;
			$contact_phone    = get_theme_mod('contact_section_phone');
			$contact_email    = get_theme_mod('contact_section_email');
			$contact_address  = get_theme_mod('contact_section_address');

			if( !empty($contact_address) || !empty( $contact_phone ) || !empty($contact_email) ){
				$show_pre_head = true;
			}

			if( $show_pre_head == true ){

				echo '<div class="contact-info">';

					if( !empty( $contact_address ) ){
							echo '<span class="ce-address"><i class="fa fa-map-marker" aria-hidden="true"></i> ' . esc_html( $contact_address ) . '</span>';
					}

					if( !empty( $contact_phone ) ){
							echo '<span class="ce-phone"><i class="fa fa-phone" aria-hidden="true"></i> ' . esc_html( $contact_phone ) . '</span>';
					}

					if( !empty( $contact_email ) ){
							echo '<span class="ce-email"><a href="mailto:' . esc_attr( $contact_email ) . '"><i class="fa fa-envelope" aria-hidden="true"></i> ' . esc_html( $contact_email ) . '</a></span>';
					}

				echo '</div>';

			}



		?>
		<?php
		get_sidebar('footer');
		?>

  </div>

</footer><!-- #colophon -->

<a href="#top" class="back-to-top">&#8593;</a>
	<!-- Copyright bar -->
	<?php
		$my_theme = wp_get_theme();
		$authorURI = $my_theme->get( 'AuthorURI' );
		$themeURI = $my_theme->get( 'ThemeURI' );
		$defaultCopyright = sprintf( __( 'Theme: %1$s is developed by %2$s.', 'food-express' ), '<a href="' . esc_attr( $themeURI ) . '">Food Express WordPress Food Theme</a>', '<a href="' . esc_attr( $authorURI ) . '" rel="designer">Template Express</a>' );
		$copyrightInfo = get_theme_mod('food_express_pro_copyright', $defaultCopyright);
		$hideCopyright = get_theme_mod('food_express_pro_remove_copyright', false);
		$hidePoweredBy = get_theme_mod('food_express_pro_powered_by', false);
		$copyrightCenter = get_theme_mod('food_express_pro_center_copyright', false);

		if(! $hideCopyright){ ?>

			<div class="site-info  <?php echo esc_attr( $container_class ); ?>">
				<div class="row">

					<?php
						if( $copyrightCenter ){
							echo '<div class="twelve columns text-center">';
						}else{
							echo '<div class="six columns">';
						}
					?>
						<p><?php echo wp_kses_post( $copyrightInfo ) ?></p>
					</div>

					<?php if(! $hidePoweredBy ){ ?>
						<div class="six columns text-right">
							<p><?php echo __('Proudly powered by', 'food-express');?> <a href="<?php echo esc_url( 'https://wordpress.org/' ); ?>"><?php echo __('WordPress', 'food-express'); ?></a>
						</div>
					<?php } ?>

				</div>
			</div>

		<?php

		}

wp_footer(); ?>

</body>
</html>
