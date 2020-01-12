<?php
/**
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Food Express
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses food_express_header_style()
 */
function food_express_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'food_express_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'e1e1e1',
		'width'                  => 2048,
		'height'                 => 900,
		'flex-height'            => true,
		'wp-head-callback'       => 'food_express_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'food_express_custom_header_setup' );



if ( ! function_exists( 'food_express_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @see food_express_custom_header_setup().
 */
function food_express_header_style() {
	$header_text_color = get_header_textcolor();
	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: HEADER_TEXTCOLOR.
	 */
	if ( 'e1e1e1' === $header_text_color ) {
		return;
	}
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that.
		else :
	?>
		.site-branding .site-title,
		.site-branding .site-title a,
		.site-branding .site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;
