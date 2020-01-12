<?php
/**
 * Template part for displaying hero section on homepage.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */

  $pageID = get_theme_mod( 'food_express_hero_page_id' );
  if(! intval( $pageID ) ){
    return;
  }
  $primaryBtnID = get_theme_mod( 'food_express_hero_btn_one' );
  $secondaryBtnID = get_theme_mod( 'food_express_hero_btn_two' );

  if( did_action( 'pll_init' ) && PLL() instanceof PLL_Frontend ){
    $translatedPageID = pll_get_post($pageID);
    if( $translatedPageID ){
      // if there is a translated page then use this instead.
      $pageID = $translatedPageID;
    }
    $translatedPrimaryBtnID = pll_get_post($primaryBtnID);
    if( $translatedPrimaryBtnID ){
      // if there is a translated page then use this instead.
      $primaryBtnID = $translatedPrimaryBtnID;
    }
    $translatedSecondaryBtnID = pll_get_post($secondaryBtnID);
    if( $translatedSecondaryBtnID ){
      // if there is a translated page then use this instead.
      $secondaryBtnID = $translatedSecondaryBtnID;
    }
  }
  $logo = get_theme_mod( 'food_express_hero_section_logo');



  echo '<div id="hero" class="hero-section">';
    // If hero logo - show it.
    if( $logo ){
      echo '<img src="' . esc_attr( $logo ) . '" alt="' . __('website call to action logo', 'food-express') . '" />';
    }
    // Only show if checkbox to show is checked.
    if( get_theme_mod( 'food_express_show_hero_title') ){
      echo '<h2>' . esc_html( get_the_title( $pageID ) ) . '</h2>';
      echo '<h3>' . esc_html( get_the_excerpt( $pageID ) ) . '</h3>';
    }
    if( intval( $primaryBtnID ) || intval( $secondaryBtnID ) ){
      echo '<div class="cta-btns">';
        if( $primaryBtnID ){
          echo '<a class="primary-label" href="' . esc_url( get_the_permalink( $primaryBtnID ) ) . '">' . esc_html( get_the_title( $primaryBtnID ) ) . '</a>';
        }
        if($secondaryBtnID){
          echo '<a class="secondary-label" href="' . esc_url( get_the_permalink( $secondaryBtnID ) )  . '">' . esc_html( get_the_title( $secondaryBtnID ) ) . '</a>';
        }
      echo '</div>';
    }

  echo '</div>';
?>
