<?php
/**
 * Template part for displaying Block 1 section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */

  $pageID = get_theme_mod( 'food_express_block1_page' );
  if(! intval( $pageID ) ){
    return;
  }
  $block1_url = get_theme_mod( 'food_express_block_1_btn_url' );

  if( did_action( 'pll_init' ) && PLL() instanceof PLL_Frontend ){
    $translatedPageID = pll_get_post($pageID);
    if( $translatedPageID ){
      // if there is a translated page then use this instead.
      $pageID = $translatedPageID;
    }
    $translatedLinkID = pll_get_post($block1_url);
    if( $translatedLinkID ){
      // if there is a translated page then use this instead.
      $block1_url = $translatedLinkID;
    }
  }

  $block1_image = get_the_post_thumbnail_url( $pageID );
  $showLeft = get_theme_mod( 'food_express_block_1_img_left' );
  $container_class_block1 = get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';

?>

<section id="block-1" class="home-block homepage-block-1">
  <div class="content-wrap  <?php echo  esc_attr( $container_class_block1 ); ?>">
    <?php // user chose to show image on left of text.
    $showLeft = get_theme_mod('food_express_block_1_img_left');

    if( $showLeft ){
      echo '<div class="six columns"> <img src="' . esc_url( $block1_image ) . '" alt="' . esc_html( get_the_title( $pageID ) ) . __(' featured image', 'food-express') . '"/> </div>';
    } ?>

    <div class="six columns text-area">
      <?php

        $h2Class = '';
        $block1_subtitle = "";
        $readMore = __( 'Read more', 'food-express' );

        if ( function_exists( 'food_express_pro_customize_register' ) ) {
          $block1_subtitle = get_theme_mod('food_express_pro_block1_sub-title');
          $readMore = get_theme_mod('food_express_pro_block1_btn_txt', __( 'Read more', 'food-express' ));
          $h2Class = 'hasPro';
        }

        if( intval($pageID) ){

          echo '<h2 class="' . $h2Class . '">' . esc_html( get_the_title( $pageID ) ) . '</h2>';
          if( $block1_subtitle ){
            echo '<p class="sub-title">' . esc_html( $block1_subtitle ) . '</p>';
          }
          if( get_the_excerpt( $pageID ) ){
            echo '<p class="description">' . esc_html( get_the_excerpt( $pageID ) ) . '</p>';
          }

        }
        if( intval($block1_url) ){
          echo '<a class="btn" href="' . esc_url( get_the_permalink( $block1_url ) ) . '">' . esc_html( $readMore ) . '</a>';
        }
      ?>
    </div>

    <?php // default show image to the right of the text.
    if(! $showLeft ){
      echo '<div class="six columns"> <img src="' . esc_url( $block1_image ) . '" alt="' . esc_html( get_the_title( $pageID ) ) . __('featured image', 'food-express') . '"/> </div>';
    }?>
  </div>
</section>
