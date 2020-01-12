<?php
/**
 * Template part for displaying book a table section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */

  $promoBarTitle = get_theme_mod( 'food_express_promo_bar_title', __( 'Challenge your tastebuds', 'food-express') );
  $promoBarSubTitle = get_theme_mod( 'food_express_promo_bar_subtitle', __( 'Our tables fill up fast so book early to avoid dissapointment.', 'food-express') );
  $promoBarLabel = get_theme_mod( 'food_express_promo_bar_label', __( 'Book a Table', 'food-express' ) );
  $promoBarImg = get_theme_mod( 'food_express_promo_bar_img',  get_template_directory_uri() . '/inc/images/promo-bar-bg.jpg');
  $promoBarURL = get_theme_mod( 'food_express_promo_bar_url' );
  $container_class_content = get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';
?>
    <section id="book" class="book-a-table-section parallax-window" data-parallax="scroll" data-image-src="<?php echo esc_attr( $promoBarImg ); ?>">
      <div class="content-wrap  <?php echo esc_attr( $container_class_content ); ?>">
        <div class="cta">
          <?php
            if( $promoBarTitle ){
              echo '<h2>' . esc_html( $promoBarTitle ) . '</h2>';
            }
            if( $promoBarSubTitle ){
              echo '<p class="sub-title">' . esc_html( $promoBarSubTitle ) . '</p>';
            }
            if($promoBarLabel){
              echo '<a class="btn" href="' . esc_attr( $promoBarURL ) . '" >' . esc_html( $promoBarLabel ) . '</a>';
            }
          ?>
        </div>
      </div>
    </section>
