<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Food Express
 */

if ( ! is_active_sidebar( 'food_express_footer_widgets' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area row" role="complementary">
	<?php dynamic_sidebar( 'food_express_footer_widgets' ); ?>
</aside><!-- #secondary -->
