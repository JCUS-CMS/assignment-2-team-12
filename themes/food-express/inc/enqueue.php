<?php
/**
 * Food Express Theme Enqueue scripts and styles.
 *
 * @package Food Express
 */

/**
 * Enqueue scripts and styles.
 */
function food_express_scripts() {

	// Styles
	wp_enqueue_style('food-express-styles', get_stylesheet_uri() );

	// Google fonts
	wp_enqueue_style( 'food-express-default-google-fonts', '//fonts.googleapis.com/css?family=Cabin|Merriweather');

	// Icon font
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0');

	// Parallax
		wp_enqueue_script( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.min.js', array('jquery'), '1.4.2', true);

	// Masonry
	wp_enqueue_script( 'masonry');

	// == Scripts
	wp_enqueue_script( 'food-express-scripts', get_template_directory_uri() . '/js/theme.min.js', array('jquery', 'masonry'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'food_express_scripts' );



/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function food_express_customize_preview_js() {
  wp_enqueue_script( 'food_express_customizer', get_template_directory_uri() . '/js/customizer.min.js', array( 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'food_express_customize_preview_js' );



/**
 * Registers an editor stylesheet for the current theme.
 *
 * @global WP_Post $post Global post object.
 */
function food_express_add_editor_styles() {
  // Load 'theme.min.css' as most of it is necessary for editor as well.
  add_editor_style( array( 'css/editor-style.min.css', 'style.css' ) );
}
add_action( 'admin_init', 'food_express_add_editor_styles' );



/**
 * Activate Google Fonts
 */
 function food_express_google_fonts() {

 	$fonts          = '';
  $enqueueFonts   = 0;
	$bodyFont = esc_attr( get_theme_mod( 'body_font' ) );
	$headingFont = esc_attr( get_theme_mod( 'header_font' ) );

	if($bodyFont && $bodyFont != 'Merriweather' ){
		$fonts .= '|' . $bodyFont;
		$enqueueFonts = 1;
	}
	if($headingFont && $headingFont != 'Cabin' ){
		$fonts .= '|' . $headingFont;
		$enqueueFonts = 1;
	}

	if($enqueueFonts == 1){
	 $fonts = ltrim($fonts, '|');
	 $url = add_query_arg('family', urlencode($fonts), "//fonts.googleapis.com/css" );
	 wp_enqueue_style('food-express-custom-google-fonts', $url);

	} else {
	   // Return nothing if google fonts have not been selected from the customizer or are the same as default.
	   return;
	}

 }
 add_action('wp_enqueue_scripts', 'food_express_google_fonts');
