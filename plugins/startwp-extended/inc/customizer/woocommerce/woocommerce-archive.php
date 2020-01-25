<?php

/****************************************************
* Section
****************************************************/

// WooCommerce Archive
Kirki::add_section( 'swp_woocommerce_archive', array(
    'title'          => __( 'Woo Archive', 'start'  ),
    'panel'          => 'theme_styles',
    'capability'     => 'edit_theme_options',
) );



/****************************************************
* Fields
****************************************************/

// WooCommerce Shop / Archive Tabs
start_tabs('woocommerce_archive_setting', 'swp_woocommerce_archive');

start_shortcuts('woocommerce_archive_shortcut', 'woocommerce_archive_setting', 'partial_woocommerce', '.site-footer .site-info .container');


// Shop / Archive Layout
Kirki::add_field( 'start', array(
    'type'        => 'sortable',
    'settings'    => 'swp_shop_layout',
    'label'       => __( 'Shop Products Layout', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'default'     => array(
        'image',
        'category',
        'title',
        'ratings',
        'price',
        'cart',
    ),
    'choices'     => array(
        'image' => __( 'Product Image', 'start' ),
        'category' => __( 'Category', 'start' ),
        'title'          => __( 'Title', 'start' ),
        'ratings'           => __( 'Ratings', 'start' ),
        'price'           => __( 'Price', 'start' ),
        'description'        => __( 'Description', 'start' ),
        'cart'        => __( 'Add to Cart', 'start' ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );


// Link Shop Thumbnails
Kirki::add_field( 'start', array(
    'type'        => 'toggle',
    'settings'    => 'swp_shop_thumbnail_linkable',
    'label'       => __( 'Link Thumbnail', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'default'     => '1',
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );

// Link Shop Titles
Kirki::add_field( 'start', array(
    'type'        => 'toggle',
    'settings'    => 'swp_shop_title_linkable',
    'label'       => __( 'Link Title', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'default'     => '1',
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );

// Products per row
Kirki::add_field( 'start', array(
    'type'        => 'select',
    'settings'    => 'swp_shop_products_per_row',
    'label'       => __( 'Products Per Row', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'default'     => 'swp-shop-3',
    'multiple'    => 1,
    'choices'     => array(
        'swp-shop-1' => esc_attr__( '1', 'start' ),
        'swp-shop-2' => esc_attr__( '2', 'start' ),
        'swp-shop-3' => esc_attr__( '3', 'start' ),
        'swp-shop-4' => esc_attr__( '4', 'start' ),
        'swp-shop-5' => esc_attr__( '5', 'start' ),
        'swp-shop-6' => esc_attr__( '6', 'start' ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );


/*********** Style Fields ***********/

start_headlines('swp_shop_sale_badge_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Sale Badge', '10px');

// Sale Badge Background
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_sale_badge_bg',
    'label'       => __( 'Sale Badge Background', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#77a464',
    'output' => array(
        array(
            'element'  => '.swp-archive-image .onsale',
            'property' => 'background-color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-image .onsale',
        'function' => 'css',
        'property' => 'background-color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Sale Badge Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_sale_badge_text_color',
    'label'       => __( 'Sale Badge Text Color', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#ffffff',
    'output' => array(
        array(
            'element'  => '.swp-archive-image .onsale',
            'property' => 'color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-image .onsale',
        'function' => 'css',
        'property' => 'color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

start_headlines('swp_woo_archive_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Title', '10px');

// Title Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_title_color',
    'label'       => __( 'Title', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp-archive-title .swp-loop-product__link .product_title, .swp-archive-title .product_title',
            'property' => 'color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-title .swp-loop-product__link .product_title, .swp-archive-title .product_title',
        'function' => 'css',
        'property' => 'color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Title Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_title_hover_color',
    'label'       => __( 'Title Hover', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#1e73be',
    'output' => array(
        array(
            'element'  => '.swp-archive-title .swp-loop-product__link:hover .product_title',
            'property' => 'color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-title .swp-loop-product__link:hover .product_title',
        'function' => 'css',
        'property' => 'color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );	


// Title Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_shop_title_typography',
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-archive-title .swp-loop-product__link .product_title, .swp-archive-title .product_title',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element' => '.swp-archive-title .swp-loop-product__link .product_title, .swp-archive-title .product_title',
            'suffix'  => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Category Styles
start_headlines('swp_woo_category_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Category', '10px');

// Category Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_category_color',
    'label'       => __( 'Category', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp-archive-category',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-category',
        'function' => 'css',
        'property' => 'color',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Category Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_shop_category_typography',
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '14px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-archive-category',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Rating Styles
start_headlines('swp_woo_rating_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Rating', '10px');

// Rating Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_rating_color',
    'label'       => __( 'Rating', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#0274be',
    'output' => array(
        array(
            'element'  => '.swp-archive-rating .star-rating',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-rating .star-rating',
        'function' => 'css',
        'property' => 'color',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Price Styles
start_headlines('swp_woo_price_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Price', '10px');

// Price Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_price_color',
    'label'       => __( 'Price', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#77a464',
    'output' => array(
        array(
            'element'  => '.swp-archive-price .price',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-archive-price .price',
            'function' => 'css',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Price Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_shop_price_typography',
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '16px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-archive-price .price',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Short Description Styles
start_headlines('swp_woo_short_desc_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Description', '10px');

// Description Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_desc_color',
    'label'       => __( 'Short Description', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp-archive-description',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-archive-description',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Description Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_shop_desc_typography',
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '16px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-archive-description',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-archive-description',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



// Add To Cart
start_headlines('swp_woo_car_btn_headline', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'style', 'Add To Cart', '10px');

// Cart Button Background
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_cart_btn_bg',
    'label'       => __( 'Background', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#ebe9eb',
    'output' => array(
        array(
            'element'  => '.swp-archive-cart .add_to_cart_button',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-cart .add_to_cart_button',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Cart Button Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_cart_btn_text_color',
    'label'       => __( 'Text', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.swp-archive-cart .add_to_cart_button',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-cart .add_to_cart_button',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Background Hover
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_cart_btn_bg_hover',
    'label'       => __( 'Hover Background', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#dad8da',
    'output' => array(
        array(
            'element'  => '.swp-archive-cart .add_to_cart_button:hover',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-cart .add_to_cart_button:hover',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Text Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_shop_cart_btn_text_hover_color',
    'label'       => __( 'Hover Text', 'start' ),
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.swp-archive-cart .add_to_cart_button:hover',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-archive-cart .add_to_cart_button:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_cart_btn_typography',
    'section'     => 'swp_woocommerce_archive',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '16px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-archive-cart .add_to_cart_button',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



/*********** Advanced Fields ***********/

start_no_options('woo_archive_no_options', 'swp_woocommerce_archive', 'woocommerce_archive_setting', 'advanced');




/*********** Functions ***********/

// products per row
add_filter('loop_shop_columns', 'loop_columns');
function loop_columns() {
    $swp_col_per_row = get_theme_mod( 'swp_shop_products_per_row', 'swp-shop-3');
    if($swp_col_per_row == 'swp-shop-1'){
        return 1;
    } elseif ($swp_col_per_row == 'swp-shop-2') {
        return 2;
    } elseif ($swp_col_per_row == 'swp-shop-3') {
        return 3;
    } elseif ($swp_col_per_row == 'swp-shop-4') {
        return 4;
    } elseif ($swp_col_per_row == 'swp-shop-5') {
        return 5;
    } elseif ($swp_col_per_row == 'swp-shop-6') {
        return 6;
    }
}




/*********** Archive Page Hooks ***********/
 
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
add_action('swp_woocommerce_show_product_loop_sale_flash', 'woocommerce_show_product_loop_sale_flash');

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
add_action('swp_woocommerce_template_loop_product_thumbnail', 'woocommerce_template_loop_product_thumbnail');

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_single_title');
add_action('swp_woocommerce_template_single_title', 'woocommerce_template_single_title');

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating');
add_action('swp_woocommerce_template_loop_rating', 'woocommerce_template_loop_rating');

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
add_action('swp_woocommerce_template_loop_price', 'woocommerce_template_loop_price');

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
add_action('swp_woocommerce_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart');