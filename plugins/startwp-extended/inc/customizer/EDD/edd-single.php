<?php

/****************************************************
* Section
****************************************************/

// EDD General
Kirki::add_section( 'swp_edd_single', array(
    'title'          => __( 'EDD Single', 'start'  ),
    'panel'          => 'theme_styles',
    'capability'     => 'edit_theme_options',
) );

/****************************************************
* Fields
****************************************************/

// EDD Single Tabs
start_tabs('edd_single_setting', 'swp_edd_single');

start_shortcuts('edd_single_shortcut', 'swp_edd_single', 'partial_edd', '.site-footer .site-info .container');


// Edd Single Layout
Kirki::add_field( 'start', array(
    'type'        => 'sortable',
    'settings'    => 'swp_edd_single_layout',
    'label'       => __( 'Products Layout', 'start' ),
    'section'     => 'swp_edd_single',
    'default'     => array(
        'image',
        'title',
        'description',
        'cart',
    ),
    'choices'     => array(
        'image' => __( 'Product Image', 'start' ),
        'title'          => __( 'Title', 'start' ),
        'description'        => __( 'Description', 'start' ),
        'cart'        => __( 'Add to Cart', 'start' ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );



/*********** Style Fields ***********/

start_headlines('swp_edd_single_title_headline', 'swp_edd_single', 'edd_single_setting', 'style', 'Title', '10px');

// Title Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_title_color',
    'label'       => __( 'Title', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_title',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_download_title',
            'function' => 'css',
            'property' => 'color',
            'suffix' => ' !important'
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Title Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_single_title_typo',
    'section'     => 'swp_edd_single',
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
            'element' => '.swp_edd_single .edd_download_title',
            'suffix' => '!important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Short Description Styles
start_headlines('swp_edd_single_short_desc_headline', 'swp_edd_single', 'edd_single_setting', 'style', 'Description', '10px');

// Description Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_desc_color',
    'label'       => __( 'Short Description', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_full_content',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_download_full_content',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Description Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_single_desc_typography',
    'section'     => 'swp_edd_single',
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
            'element' => '.swp_edd_single .edd_download_full_content',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_download_full_content',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Price Options
start_headlines('swp_edd_single_price_headline', 'swp_edd_single', 'edd_single_setting', 'style', 'Price Options', '10px');

// Price Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_price_color',
    'label'       => __( 'Price', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_price_options',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_price_options',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Price Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_single_price_typography',
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '17px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp_edd_single .edd_price_options',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_price_options',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );






// Add To Cart
start_headlines('swp_edd_single_cart_btn_headline', 'swp_edd_single', 'edd_single_setting', 'style', 'Add To Cart', '10px');

// Cart Button Background
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_cart_btn_bg',
    'label'       => __( 'Background', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#ebe9eb',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a',
            'property' => 'border-color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a',
            'function' => 'css',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
         array(
                'element'  => '.swp_edd_single .edd_download_buy_button a',
                'function' => 'css',
                'property' => 'border-color',
                'suffix'   => ' !important',
         ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Cart Button Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_cart_btn_text_color',
    'label'       => __( 'Text', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_single .edd_download_buy_button a',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Background Hover
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_cart_btn_bg_hover',
    'label'       => __( 'Hover Background', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#dad8da',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
            'property' => 'border-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
            'function' => 'css',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
            'function' => 'css',
            'property' => 'border-color',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Text Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_single_cart_btn_text_hover_color',
    'label'       => __( 'Hover Text', 'start' ),
    'section'     => 'swp_edd_single',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_single .edd_download_buy_button a:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_single_btn_typography',
    'section'     => 'swp_edd_single',
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
            'element' => '.swp_edd_single .edd_download_buy_button a',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_single_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



/*********** Advanced Fields ***********/

start_no_options('edd_single_no_options', 'swp_edd_single', 'edd_single_setting', 'advanced');

