<?php
/**
 * General functions used to integrate this theme homepage
 *
 * @package Food Express
 */

 function food_express_homepage_testimonials() {

     get_template_part( 'template-parts/homepage', 'testimonials' );

 }

 function food_express_homepage_recent() {

     get_template_part( 'template-parts/homepage', 'recent' );

 }
 function food_express_homepage_menu() {

     get_template_part( 'template-parts/homepage', 'menu' );

 }
function food_express_homepage_block_1() {

    get_template_part( 'template-parts/homepage', 'block1' );

}
function food_express_homepage_cta() {

    get_template_part( 'template-parts/homepage', 'cta' );

}
function food_express_homepage_block_2() {

    get_template_part( 'template-parts/homepage', 'block2' );

}
function food_express_homepage_gallery() {

    get_template_part( 'template-parts/homepage', 'gallery' );

}
function food_express_homepage_contact() {

    get_template_part( 'template-parts/homepage', 'contact' );

}

function food_express_homepage_map() {

    get_template_part( 'template-parts/homepage', 'map' );

}

function food_express_homepage_content() {
  while ( have_posts() ) : the_post();

    get_template_part( 'template-parts/content', 'homepage' );

  endwhile; // End of the loop.
}
