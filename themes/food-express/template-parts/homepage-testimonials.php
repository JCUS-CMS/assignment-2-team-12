<?php

// check for Good Reviews WP
if ( ! class_exists('grfwpInit') ) {
  //End if no active plugin
  return;
}

 ?>
<section id="testimonials" class="testimonial-section">
  <?php
    $testimonialTitle = get_theme_mod('food_express_testimonial_title', __('Our Happy Customers', 'food-express'));
    $testShortcode = get_theme_mod('food_express_testimonial_shortcode', __( '[good-reviews limit=5]', 'food-express') );
    $container_class_testimonials = get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';

  ?>

  <h1><?php echo esc_html( $testimonialTitle ); ?></h1>

  <div class="content-wrap <?php echo esc_attr( $container_class_testimonials ); ?>">

    <div class="twelve columns">
      <?php echo wp_kses( do_shortcode( $testShortcode ), 'post'); ?>
    </div>


  </div>

</div>
