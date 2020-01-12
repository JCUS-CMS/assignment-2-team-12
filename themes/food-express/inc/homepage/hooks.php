<?php
/**
 * Food Express homepage hooks
 *
 * @package Food Express
 */

 /*
 * HOMEPAGE
 */

add_action( 'homepage', 'food_express_homepage_block_1', 		     10 );
add_action( 'homepage', 'food_express_homepage_cta', 		         20 );
add_action( 'homepage', 'food_express_homepage_block_2', 		     30 );
add_action( 'homepage', 'food_express_homepage_content', 		     40 );
add_action( 'homepage', 'food_express_homepage_menu', 		       50 );
add_action( 'homepage', 'food_express_homepage_recent', 		     60 );
add_action( 'homepage', 'food_express_homepage_testimonials',    70);
add_action( 'homepage', 'food_express_homepage_gallery', 		     80 );
add_action( 'homepage', 'food_express_homepage_contact', 		     90 );
add_action( 'homepage', 'food_express_homepage_map', 		         100 );
