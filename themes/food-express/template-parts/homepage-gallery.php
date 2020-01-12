<?php
/**
 * Template part for displaying gallery section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */
 // check for Enjoy Plugin for Instagram
if (! function_exists( 'enjoyinstagram_require_activation_class' ) ) {
   //End if no active plugin
   return;
 }


$galleryTitle = get_theme_mod('food_express_instagram_title');
$galleryShortcode = get_theme_mod('food_express_instagram_shortcode');

?>

<section id="gallery" class="home-gallery fullwidth">
  <?php
    if($galleryTitle){
      echo '<h2 class="text-center">' . esc_html( $galleryTitle ) . '</h2>';
    }
    echo do_shortcode( $galleryShortcode );
  ?>
</section>
