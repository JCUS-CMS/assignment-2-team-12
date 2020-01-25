<?php

/****************************************************
* Section
****************************************************/

// WooCommerce Single
Kirki::add_section( 'swp_woocommerce_single', array(
    'title'          => __( 'Woo Single', 'start'  ),
    'panel'          => 'theme_styles',
    'capability'     => 'edit_theme_options',
) );



/****************************************************
* Fields
****************************************************/

// WooCommerce Single
start_tabs('woocommerce_single_setting', 'swp_woocommerce_single');

start_shortcuts('woocommerce_single_shortcut', 'swp_woocommerce_single', 'partial_woocommerce', '.site-footer .site-info .container');


// Single Product Layout
Kirki::add_field( 'start', array(
    'type'        => 'sortable',
    'settings'    => 'swp_single_layout',
    'label'       => __( 'Single Products Layout', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'default'     => array(
        'title',
        'ratings',
        'price',
        'description',
        'cart',
        'meta'
    ),
    'choices'     => array(
        'title' => __( 'Title', 'start' ),
        'ratings'          => __( 'Rating', 'start' ),
        'price'           => __( 'Price', 'start' ),
        'description'        => __( 'Description', 'start' ),
        'cart'        => __( 'Add to Cart', 'start' ),
        'meta' => __( 'Meta', 'start' )
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );


/*********** Style Fields ***********/

start_headlines('swp_woo_single_title_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Title', '10px');

// Title Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_woo_single_title_color',
    'label'       => __( 'Title', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp-single-title .product_title',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-single-title .product_title',
            'function' => 'css',
            'property' => 'color',
            'suffix' => ' !important'
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Title Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_woo_single_title_typo',
    'section'     => 'swp_woocommerce_single',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '32px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp-single-title .product_title',
            'suffix' => '!important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



// Rating Styles
start_headlines('swp_woo_single_rating_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Rating', '10px');

// Rating Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_rating_color',
    'label'       => __( 'Rating', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => 'red',
    'output' => array(
        array(
            'element'  => '.swp-single-rating .star-rating',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-single-rating .star-rating',
        'function' => 'css',
        'property' => 'color',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



// Price Styles
start_headlines('swp_woo_single_price_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Price', '10px');

// Price Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_price_color',
    'label'       => __( 'Price', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#77a464',
    'output' => array(
        array(
            'element'  => '.swp-single-price .price',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-single-price .price',
            'function' => 'css',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Price Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_price_typography',
    'section'     => 'swp_woocommerce_single',
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
            'element' => '.swp-single-price .price',
            'suffix' => ' !important',
        ),
    ),
   'js_vars'   => array(
        array(
            'element' => '.swp-single-price .price',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Short Description Styles
start_headlines('swp_woo_single_short_desc_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Description', '10px');

// Description Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_desc_color',
    'label'       => __( 'Short Description', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp-single-description',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-single-description',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Description Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_desc_typography',
    'section'     => 'swp_woocommerce_single',
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
            'element' => '.swp-single-description',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp-single-description',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );




// Add To Cart
start_headlines('swp_woo_single_cart_btn_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Add To Cart', '10px');

// Cart Button Background
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_cart_btn_bg',
    'label'       => __( 'Background', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#a46497',
    'output' => array(
        array(
            'element'  => '.swp-single-cart .single_add_to_cart_button',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-single-cart .single_add_to_cart_button',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Cart Button Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_cart_btn_text_color',
    'label'       => __( 'Text', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#ffffff',
    'output' => array(
        array(
            'element'  => '.swp-single-cart .single_add_to_cart_button',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-single-cart .single_add_to_cart_button',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Background Hover
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_cart_btn_bg_hover',
    'label'       => __( 'Hover Background', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#935386',
    'output' => array(
        array(
            'element'  => '.swp-single-cart .single_add_to_cart_button:hover',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-single-cart .single_add_to_cart_button:hover',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Text Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_cart_btn_text_hover_color',
    'label'       => __( 'Hover Text', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#ffffff',
    'output' => array(
        array(
            'element'  => '.swp-single-cart .single_add_to_cart_button:hover',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp-single-cart .single_add_to_cart_button:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_cart_btn_typography',
    'section'     => 'swp_woocommerce_single',
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
            'element' => '.swp-single-cart .single_add_to_cart_button',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



// Product Tab 
start_headlines('swp_woo_single_producttab_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Product Tabs', '10px');

// Product tab Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_product_tab_text_color',
    'label'       => __( 'Text', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li a',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li a',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Product tab Text Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_product_tab_hover_text_color',
    'label'       => __( 'Hover Text', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Product tab Active Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_product_tab_active_text_color',
    'label'       => __( 'Active Text', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Product tab Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_product_tab_typography',
    'section'     => 'swp_woocommerce_single',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce div.product .woocommerce-tabs ul.tabs li a',
            'suffix' => '!important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Product Tab Title
start_headlines('swp_woo_single_producttab_title_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Product Tabs Title', '10px');

// Product tab title Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_product_tab_title_color',
    'label'       => __( 'Title', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel h2',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel h2',
            'function' => 'css',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



// Product tab title Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_product_tab_title_typography',
    'section'     => 'swp_woocommerce_single',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '42px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce div.product .woocommerce-tabs .panel h2',
            'suffix' => '!important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel h2',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );




// Product Tab Desc
start_headlines('swp_woo_single_producttab_desc_headline', 'swp_woocommerce_single', 'woocommerce_single_setting', 'style', 'Product Tabs Description', '10px');

// Product tab Desc Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_single_product_tab_desc_color',
    'label'       => __( 'Text Color', 'start' ),
    'section'     => 'swp_woocommerce_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel',
            'function' => 'css',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Product tab Desc Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_single_product_tab_desc_typography',
    'section'     => 'swp_woocommerce_single',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce div.product .woocommerce-tabs .panel',
            'suffix' => '!important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.woocommerce div.product .woocommerce-tabs .panel',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



/*********** Advanced Fields ***********/

start_no_options('woo_single_no_options', 'swp_woocommerce_single', 'woocommerce_single_setting', 'advanced');



/*********** Single Page Hooks ***********/
 
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title');
add_action('swp_woocommerce_template_single_title', 'woocommerce_template_single_title');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating');
add_action('swp_woocommerce_template_single_rating', 'woocommerce_template_single_rating');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
add_action('swp_woocommerce_template_single_price', 'woocommerce_template_single_price');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt');
add_action('swp_woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');
add_action('swp_woocommerce_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta');
add_action('swp_woocommerce_template_single_meta', 'woocommerce_template_single_meta');