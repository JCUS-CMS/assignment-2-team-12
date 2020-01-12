<?php
/**
 * Food ExpressWidget Areas Initializations.
 *
 * @package Food Express
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function food_express_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Sidebar', 'food-express' ),
    'id'            => 'sidebar-1',
    'description'   => __( 'Add widgets here.', 'food-express' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'food_express_widgets_init' );

/*------------------------------------*\
  #HOMEPAGE CONTACT WIDGET AREAS
\*------------------------------------*/

function food_express_contact_widgets_init() {

  register_sidebar( array(
    'name'          => __( 'Homepage contact widget area', 'food-express' ),
    'id'            => 'food_express_contact_single_widgets',
    'description'   => __( 'Widget area split into half screen width for contact info on homepage.', 'food-express' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>',
  ) );

}
add_action( 'widgets_init', 'food_express_contact_widgets_init', 800);
/*------------------------------------*\
  #FOOTER WIDGET AREAS
\*------------------------------------*/

function food_express_footer_widgets_init() {

  /* #FOOTER WIDGETS */
  register_sidebar( array(
    'name'          => __( 'Footer 1 Column', 'food-express' ),
    'id'            => 'food_express_footer_single_widgets',
    'description'   => __( 'Single Widget for footer.', 'food-express' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>',
  ) );

  register_sidebar( array(
    'name'          => __( 'Footer 3 Columns', 'food-express' ),
    'id'            => 'food_express_footer_widgets',
    'description'   => __( 'Widgets for footer.', 'food-express' ),
    'before_widget' => '<div id="%1$s" class="widget four columns %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>',
  ) );

}
add_action( 'widgets_init', 'food_express_footer_widgets_init', 1000);
