<?php

/****************************************************
* Section
****************************************************/

// WooCommerce General
Kirki::add_section( 'swp_woocommerce_general', array(
    'title'          => __( 'Woo General', 'start'  ),
    'panel'          => 'theme_styles',
    'capability'     => 'edit_theme_options',
) );



/****************************************************
* Fields
****************************************************/

// WooCommerce General Tabs
start_tabs('woocommerce_general_setting', 'swp_woocommerce_general');

start_shortcuts('woocommerce_general_shortcut', 'swp_woocommerce_general', 'partial_woocommerce', '.site-footer .site-info .container');

/* General Fields */
start_no_options('woo_general_no_options', 'swp_woocommerce_general', 'woocommerce_general_setting', 'general');



/* Style Fields */


// Success Message Headline
start_headlines('swp_success_message_headline', 'swp_woocommerce_general', 'woocommerce_general_setting', 'style', 'Success Message', '10px');


// Success message background Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_success_message_bg_color',
    'label'       => __( 'Success Message Background Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#f7f6f7',
    'output' => array(
        array(
            'element'  => '.woocommerce-message',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-message',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Success message Text Color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_success_message_text_color',
    'label'       => __( 'Success Message Text Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.woocommerce-message',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-message',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Success message Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_success_message_typography',
    'section'     => 'swp_woocommerce_general',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce-message',
            'suffix' => '!important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element' => '.woocommerce-message',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Info Message Headline
start_headlines('swp_info_message_headline', 'swp_woocommerce_general', 'woocommerce_general_setting', 'style', 'Info Message', '10px');

// Info message background color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_info_message_bg_color',
    'label'       => __( 'Info Message Background Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#f7f6f7',
    'output' => array(
        array(
            'element'  => '.woocommerce-info',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-info',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Info message Text color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_info_message_text_color',
    'label'       => __( 'Info Message Text Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.woocommerce-info',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-info',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Info message Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_info_message_typography',
    'section'     => 'swp_woocommerce_general',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce-info',
            'suffix' => '!important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element' => '.woocommerce-info',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Error Message Headline
start_headlines('swp_error_message_headline', 'swp_woocommerce_general', 'woocommerce_general_setting', 'style', 'Error Message', '10px');

// Error message BG color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_error_message_bg_color',
    'label'       => __( 'Error Message Background Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#f7f6f7',
    'output' => array(
        array(
            'element'  => '.woocommerce-error',
            'property' => 'background-color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-error',
        'function' => 'css',
        'property' => 'background-color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );

// Error message Text color
Kirki::add_field( 'start', array(
    'type'        => 'color',
    'settings'    => 'swp_error_message_text_color',
    'label'       => __( 'Error Message Text Color', 'start' ),
    'section'     => 'swp_woocommerce_general',
    'transport' => 'postMessage',
    'default'     => '#515151',
    'output' => array(
        array(
            'element'  => '.woocommerce-error',
            'property' => 'color',
            'suffix' => ' !important',
        ),
    ),
    'js_vars'   => array(
    array(
        'element'  => '.woocommerce-error',
        'function' => 'css',
        'property' => 'color',
        'suffix' => ' !important',
    ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );


// Error message Typography
Kirki::add_field( 'start', array(
    'type'        => 'typography',
    'settings'    => 'swp_error_message_typography',
    'section'     => 'swp_woocommerce_general',
    'transport' => 'auto',
    'default'     => array(
        'font-family'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'font-size'      => '18px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'none',
    ),
    'output'      => array(
        array(
            'element' => '.woocommerce-error',
            'suffix' => '!important',
        ),
    ),
    'js_vars'   => array(
        array(
            'element' => '.woocommerce-error',
            'suffix' => ' !important',
        ),
    ),
    'active_callback'  => array(
        array(
            'setting'  => 'woocommerce_general_setting',
            'operator' => '==',
            'value'    => 'style',
        ),
    ),
) );





/* Advanced Fields */
start_no_options('woo_advance_no_options', 'swp_woocommerce_general', 'woocommerce_general_setting', 'advanced');