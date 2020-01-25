<?php

/****************************************************
* Section
****************************************************/

// EDD Archive
Kirki::add_section( 'swp_edd_archive', array(
    'title'          => __( 'EDD Archive', 'start'  ),
    'panel'          => 'theme_styles',
    'capability'     => 'edit_theme_options',
) );


/****************************************************
* Fields
****************************************************/

// EDD Archive Tabs
start_tabs('edd_archive_setting', 'swp_edd_archive');

start_shortcuts('edd_archive_shortcut', 'swp_edd_archive', 'partial_edd', '.site-footer .site-info .container');

// Archive Layout
Kirki::add_field( 'start', array(
    'type'        => 'sortable',
    'settings'    => 'swp_edd_archive_layout',
    'label'       => __( 'Products Layout', 'start' ),
    'section'     => 'swp_edd_archive',
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
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );

// Link Shop Thumbnails
Kirki::add_field( 'start', array(
    'type'        => 'toggle',
    'settings'    => 'swp_edd_archive_thumbnail_linkable',
    'label'       => __( 'Link Thumbnail', 'start' ),
    'section'     => 'swp_edd_archive',
    'default'     => '1',
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );

// Link Shop Titles
Kirki::add_field( 'start', array(
    'type'        => 'toggle',
    'settings'    => 'swp_edd_archive_title_linkable',
    'label'       => __( 'Link Title', 'start' ),
    'section'     => 'swp_edd_archive',
    'default'     => '1',
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'general',
        ),
    ),
) );


/*********** Style Fields ***********/

start_headlines('swp_edd_archive_headline', 'swp_edd_archive', 'edd_archive_setting', 'style', 'Title', '10px');

// Title Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_title_color',
    'label'       => __( 'Title', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_title a, .swp_edd_archive .edd_download_title',
            'property' => 'color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_archive .edd_download_title a, .swp_edd_archive .edd_download_title',
        'function' => 'css',
        'property' => 'color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Title Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_title_hover_color',
    'label'       => __( 'Title Hover', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#1e73be',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_title a:hover',
            'property' => 'color',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_archive .edd_download_title a:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix'  => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );	


// Title Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_archive_title_typography',
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '30px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.swp_edd_archive .edd_download_title a, .swp_edd_archive .edd_download_title',
            'suffix'  => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element' => '.swp_edd_archive .edd_download_title a, .swp_edd_archive .edd_download_title',
            'suffix'  => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Short Description Styles
start_headlines('swp_edd_archive_short_desc_headline', 'swp_edd_archive', 'edd_archive_setting', 'style', 'Description', '10px');

// Description Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_desc_color',
    'label'       => __( 'Short Description', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_excerpt',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_excerpt',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Description Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_archive_desc_typography',
    'section'     => 'swp_edd_archive',
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
            'element' => '.swp_edd_archive .edd_download_excerpt',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_excerpt',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Price Options
start_headlines('swp_edd_archive_price_headline', 'swp_edd_archive', 'edd_archive_setting', 'style', 'Price Options', '10px');

// Price Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_price_color',
    'label'       => __( 'Price', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#000000',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_price_options',
            'property' => 'color',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_price_options',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Price Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_archive_price_typography',
    'section'     => 'swp_edd_archive',
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
            'element' => '.swp_edd_archive .edd_price_options',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_price_options',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Add To Cart
start_headlines('swp_edd_archive_cart_btn_headline', 'swp_edd_archive', 'edd_archive_setting', 'style', 'Add To Cart', '10px');

// Cart Button Background
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_cart_btn_bg',
    'label'       => __( 'Background', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#3276b1',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a',
            'property' => 'background-color',
            'suffix'   => ' !important',
        ),
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a',
            'property' => 'border-color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a',
            'function' => 'css',
            'property' => 'background-color',
            'suffix'   => ' !important',
        ),
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a',
            'function' => 'css',
            'property' => 'border-color',
            'suffix'   => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Cart Button Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_cart_btn_text_color',
    'label'       => __( 'Text', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#ffffff',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_archive .edd_download_buy_button a',
        'function' => 'css',
        'property' => 'color',
        'suffix'   => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Background Hover
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_cart_btn_bg_hover',
    'label'       => __( 'Hover Background', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#3276b1',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
            'property' => 'border-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
            'function' => 'css',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
            'function' => 'css',
            'property' => 'border-color',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Text Hover Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_edd_archive_cart_btn_text_hover_color',
    'label'       => __( 'Hover Text', 'start' ),
    'section'     => 'swp_edd_archive',
    'transport' => 'postMessage',
    'default'     => '#ffffff',
    'output' => array(
        array(
            'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
            'property' => 'color',
            'suffix'   => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.swp_edd_archive .edd_download_buy_button a:hover',
        'function' => 'css',
        'property' => 'color',
        'suffix'   => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Cart Button Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_edd_archive_btn_typography',
    'section'     => 'swp_edd_archive',
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
            'element' => '.swp_edd_archive .edd_download_buy_button a',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'edd_archive_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );



/*********** Advanced Fields ***********/

start_no_options('edd_archive_no_options', 'swp_edd_archive', 'edd_archive_setting', 'advanced');