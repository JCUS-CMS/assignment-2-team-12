<?php
/**
 * Template part for displaying menu section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */

 // check for Food and drink menu
 if ( ! class_exists('fdmFoodAndDrinkMenu') ) {
   //End if no active plugin
   return;
 }

 $menuTitle = get_theme_mod('food_express_menu_title', __( 'Todays Specials', 'food-express'));
 $menuSubTitle = get_theme_mod('food_express_menu_subtitle', __( 'Fresh from the kitchen', 'food-express'));
 $menuTagLine = get_theme_mod('food_express_menu_tagline', __( 'Have a look at our unique dishes prepared by our chef especially for you.', 'food-express'));
 $menuShortcode = get_theme_mod('food_express_menu_shortcode');
 $menuLabel = get_theme_mod('food_express_menu_label', __( 'Full Menu', 'food-express' ));
 $menuUrl = get_permalink( get_theme_mod('food_express_menu_url') );
 $container_class_content = get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';

if( $menuShortcode ){
  echo '<section class="homepage-menu">';
    echo '<div class="content-wrap ' . esc_attr( $container_class_content ) . '">';
      echo '<div class="menu-head">';
         echo '<h2>' . esc_html( $menuTitle ) . '</h2>';
         echo '<p class="sub-title">' . esc_html( $menuSubTitle ) . '</p>';
         echo '<p class="tagline">' . esc_html( $menuTagLine ) . '</p>';
      echo '</div>';
     echo wp_kses( do_shortcode( $menuShortcode ), 'post');
     echo '<div class="menu-link text-center"><a class="btn" href="' . esc_url( $menuUrl ) . '">'. esc_html( $menuLabel ) . '</a></div>';
   echo '</div>';
  echo '</section>';
}
