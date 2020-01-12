<?php
/**
 * Template part for displaying Block 1 section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */

  $pageID = get_theme_mod( 'food_express_block2_page' );
  if(! intval($pageID)){
    return;
  }
  $block2_url = get_theme_mod( 'food_express_block_2_btn_url' );

  if( did_action( 'pll_init' ) && PLL() instanceof PLL_Frontend ){
    $translatedPageID = pll_get_post($pageID);
    if( $translatedPageID ){
      // if there is a translated page then use this instead.
      $pageID = $translatedPageID;
    }
    $translatedLinkID = pll_get_post($block2_url);
    if( $translatedLinkID ){
      // if there is a translated page then use this instead.
      $block2_url = $translatedLinkID;
    }
  }
  $block2_image = get_the_post_thumbnail_url( $pageID );
  $showRight = get_theme_mod( 'food_express_block_2_img_right' );
  $container_class_block2 = get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';
?>

<section id="block-2" class="home-block homepage-block-2">
  <div class="content-wrap  <?php echo  esc_attr( $container_class_block2 ); ?>">
    <?php // user chose to show image on left of text.
    $showRight = get_theme_mod('food_express_block_2_img_right');

    if( ! $showRight ){
      echo '<div class="six columns"> <img src="' . esc_url( $block2_image ) . '" alt="' . esc_html( get_the_title( $pageID ) ) . __(' featured image', 'food-express') . '"/> </div>';
    } ?>

    <div class="six columns text-area">
      <?php

      $h2Class = '';
      $block2_subtitle = "";
      $readMore = __( 'Read more', 'food-express' );

      if ( function_exists( 'food_express_pro_customize_register' ) ) {
        $block2_subtitle = get_theme_mod('food_express_pro_block2_sub-title');
        $readMore = get_theme_mod('food_express_pro_block2_btn_txt', __( 'Read more', 'food-express' ) );
        $h2Class = 'hasPro';
      }

        if( intval($pageID) ){
          echo '<h2 class="' . $h2Class . '">' . esc_html( get_the_title( $pageID ) ) . '</h2>';
          if( $block2_subtitle ){
            echo '<p class="sub-title">' . esc_html( $block2_subtitle ) . '</p>';
          }
          if( get_the_excerpt( $pageID ) ){
            echo '<p class="description">' . esc_html( get_the_excerpt( $pageID ) ) . '</p>';
          }
        }
        if( intval($block2_url)){
          echo '<a class="btn" href="' . esc_url( get_the_permalink( $block2_url ) ) . '">' . esc_html( $readMore ) . '</a>';
        }
      ?>
    </div>

    <?php // default show image to the left of the text.
    if($showRight ){
      echo '<div class="six columns"> <img src="' . esc_url( $block2_image ) . '" alt="' . esc_html( get_the_title( $pageID ) ) . __('featured image', 'food-express') . '"/> </div>';
    }?>
  </div>
</section>
