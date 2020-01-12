<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Food Express
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'food-express' ); ?></a>

	<?php
		$useHeaderImg = get_theme_mod('food_express_use_header_image');
		$hidehero = get_theme_mod('food_express_hide_hero');
		$postthumb = get_the_post_thumbnail_url();
		$showbackgroundColor = get_theme_mod( 'food_express_only_bg_color' );
		$hasSlider = get_theme_mod('food_express_pro_slider_shortcode', false);
		$heroClass = $hidehero ? 'no-hero' : 'show-hero';

		if( $hasSlider ){
			// Hide hero if slider is active
			$hidehero = true;
			echo '<header  id="masthead" class="site-header has-slider ' . $heroClass . '" role="banner">';
		}	elseif ( $showbackgroundColor ){
				echo '<header id="masthead" class="site-header no-image ' . $heroClass . '" role="banner">';
		} else {
			if( has_post_thumbnail() && ! $useHeaderImg ){
				echo '<header id="masthead" class="site-header parallax-window has-header-image featured-image ' . $heroClass . '" data-parallax="scroll" data-image-src="'. esc_url( $postthumb ) . '" role="banner">';
			}elseif( has_header_image() && $useHeaderImg ){
				echo '<header id="masthead" class="site-header parallax-window has-header-image ' . $heroClass . '" data-parallax="scroll" data-image-src="'. get_header_image() . '" role="banner">';
			}else{
				echo '<header id="masthead" class="site-header parallax-window default-header-image ' . $heroClass . '" data-parallax="scroll" data-image-src="'.get_template_directory_uri() . '/inc/images/default-header-img.jpg" role="banner">';
			}
		}

 		$container_class_header = get_theme_mod( 'food_express_header_container_class', true ) ? 'container' : 'fluid';

	?>
		<div class="site-header__content <?php echo esc_attr( $container_class_header ); ?>">

<?php

		$phone =  get_theme_mod('contact_section_phone');
		$email = get_theme_mod('contact_section_email');
		$address = get_theme_mod('contact_section_address');

		if( $phone || $email || $address || has_nav_menu( 'social' )){
			?>
			<div class="row pre-head">
				<?php
					if( $phone || $email || $address ){
						echo '<ul class="contact-block eight columns">';
							if( $phone ){
								echo '<li class="contact-phone"><i class="fa fa-phone"></i> <span>'. esc_html( $phone ) .'</span></li>';
							}
							if( $email ){
								echo '<li class="contact-email"><i class="fa fa-envelope"></i> <span>'. esc_html( $email ) .'</span></li>';
							}
							if( $address ){
								echo '<li class="contact-address"><i class="fa fa-map-marker"></i> <span>'. esc_html( $address ) .'</span></li>';
							}
						echo '</ul>';
					}
					if ( has_nav_menu( 'social' ) ) {
						food_express_get_social_menu();
					}
				?>
			</div>
			<?php
		}
?>


		<div class="row">
			<?php
					echo '<div class="site-branding">';

					if ( function_exists( 'the_custom_logo' ) ) {
						the_custom_logo();
					}

					if ( (is_front_page() && is_home()) ||  is_front_page() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
					<?php endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
					<?php
					endif;

					?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation" role="navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="bars"></span>
						<span class="bars"></span>
						<span class="bars"></span>
					</button>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
				</nav><!-- #site-navigation -->

		</div><!-- .row -->

		<?php
			// If hero not set to hide and is custom homepage
			$hideHero = esc_attr( get_theme_mod( 'food_express_hide_hero' ), false );
			if ( is_page_template( 'page-templates/homepage.php' ) && ! $hideHero ){
				get_template_part( 'template-parts/homepage', 'hero' );
			}
		?>

	</div><!-- .site-header__content -->


	</header><!-- #masthead -->
<?php $container_class_content = get_theme_mod( 'food_express_content_container_class', true ) ? 'container' : 'fluid' ?>
<?php if ( is_page_template( 'page-templates/homepage.php' ) ) : ?>
	<div id="content" class="site-content">
<?php else: ?>
	<div id="content" class="site-content <?php echo esc_attr( $container_class_content ); ?>">
<?php endif; ?>

<?php
	// SHOW SLIDER IF ACTIVE
	if( $hasSlider ){
		food_express_pro_homepage_slider();
	}
