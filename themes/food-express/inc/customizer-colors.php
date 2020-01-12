<?php
/**
 * Food Express Color Customizer.
 *
 * @package Food Express
 */

/*------------------------------------*\
  #SETTINGS
\*------------------------------------*/
function food_express_colors_customizer( $wp_customize ) {

    // ======== Google Font Setup

  	$list_fonts        		= array(); // 1
  	$webfonts_array    		= file( get_template_directory() . '/inc/fonts.json');
  	$webfonts          		= implode( '', $webfonts_array );
  	$list_fonts_decode 		= json_decode( $webfonts, true );
  	$priority 						= 10;

  	foreach ( $list_fonts_decode['items'] as $key => $value ) {
  		$item_family                     = $list_fonts_decode['items'][$key]['family'];
  		$list_fonts[$item_family]        = $item_family;
  	}

  	$wp_customize->add_section( 'google_font_section', array(
  		'title'			=> __('Font Settings', 'food-express'),
  		'priority'	=> 50,
  	));

    $wp_customize->add_setting( 'body_font', array(
        'default'     			=> 'Merriweather',
        'sanitize_callback'	=> 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'body_font_control', array(
      'type'     		=> 'select',
      'label'    		=> __( 'Body Font', 'food-express' ),
      'description'	=> __( 'Ordered by popularity - Default = Merriweather', 'food-express'),
      'section'  		=> 'google_font_section',
      'settings'   	=> 'body_font',
      'priority' 		=> $priority++,
      'choices'  		=> $list_fonts,
    ));

  	$wp_customize->add_setting( 'header_font', array(
  			'default'     			=> 'Cabin',
  			'sanitize_callback'	=> 'food_express_sanitize_text',
  	));
  	$wp_customize->add_control( 'header_font_control', array(
  		'type'     		=> 'select',
  		'label'    		=> __( 'Headings Font', 'food-express' ),
  		'description'	=> __( 'Change the titles of your website h1 - h6. Default = Cabin', 'food-express'),
  		'section'  		=> 'google_font_section',
  		'settings'   	=> 'header_font',
  		'priority' 		=> $priority++,
  		'choices'  		=> $list_fonts,
  	));


    /* SETTING: TEXT COLOR */
    $wp_customize->add_setting( 'food_express_text_color', array(
        'default'       => '#2f2f2f',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_text_color',
        array(
            'label'     => __( 'Text Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_text_color',
            'priority'	=> $priority++,
    )));


    /* SETTING: HEADING COLOR */
    $wp_customize->add_setting( 'food_express_heading_color', array(
        'default'       => '#cb7152',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_heading_color',
        array(
            'label'     => __( 'Headings Color', 'food-express' ),
            'section'   => 'colors',
            'priority'	=> $priority++,
    )));

    /* SETTING: HIGHLIGHT COLOR */
    $wp_customize->add_setting( 'food_express_highlight_color', array(
        'default'       => '#cb7152',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_highlight_color',
        array(
            'label'     => __( 'Highlight Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_highlight_color',
            'priority'	=> $priority++,
    )));

    /* SETTING: HEAD TXT COLOR */
    $wp_customize->add_setting( 'food_express_menu_color', array(
        'default'       => '#e1e1e1',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_menu_color',
        array(
            'label'     => __( 'Header Menu Text Color', 'food-express' ),
            'section'   => 'colors',
            'priority'	=> $priority++,
    )));

    /* SETTING: BUTTON TXT COLOR */
    $wp_customize->add_setting( 'food_express_button_text_color', array(
        'default'       => '#fff',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_button_text_color',
        array(
            'label'     => __( 'Button Text Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_button_text_color',
            'priority'	=> $priority++,
    )));

    /* SEPARATOR: Footer Widget Area */
    $wp_customize->add_setting( 'food_express_footer_separator', array(
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'sanitize_callback'    => 'sanitize_text_field',
    ));
    $wp_customize->add_control( new Food_Express_Customize_Separator_Control(
      $wp_customize,
      'food_express_footer_separator',
      array(
          'label'     => __( 'Footer Widget Area', 'food-express' ),
          'section'   => 'colors',
          'priority'	=> $priority++,
          'settings'  => 'food_express_hero_separator',
    )));

    /* SEPARATOR: Footer Section */
    $wp_customize->add_setting( 'food_express_hero_btns_separator', array(
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'sanitize_callback'    => 'sanitize_text_field',
    ));
    $wp_customize->add_control( new Food_Express_Customize_Separator_Control(
      $wp_customize,
      'food_express_hero_btns_separator',
      array(
          'label'     => __( 'Footer Section', 'food-express' ),
          'section'   => 'colors',
          'priority'	=> $priority++,
          'settings'  => 'food_express_hero_btns_separator',
    )));


    /* SETTING: FOOTER BACKGROUND COLOR */
    $wp_customize->add_setting( 'food_express_footer_bg_color', array(
        'default'       => '#23282f',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_footer_bg_color',
        array(
            'label'     => __( 'Background Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_footer_bg_color',
            'priority'	=> $priority++,
    )));

    /* SETTING: FOOTER TXT COLOR */
    $wp_customize->add_setting( 'food_express_footer_txt_color', array(
        'default'       => '#ffffff',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_footer_txt_color',
        array(
            'label'     => __( 'Text Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_footer_txt_color',
            'priority'	=> $priority++,
    )));

    /* SETTING: FOOTER LINK COLOR */
    $wp_customize->add_setting( 'food_express_footer_link_color', array(
        'default'       => '#cb7152',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_footer_link_color',
        array(
            'label'     => __( 'Link Color', 'food-express' ),
            'section'   => 'colors',
            'settings'  => 'food_express_footer_link_color',
            'priority'	=> $priority++,
    )));

}
add_action('customize_register', 'food_express_colors_customizer');


/*------------------------------------*\
  #COLORS OUTPUT
\*------------------------------------*/
function food_express_colors_customizer_get_output() {

    // Start output buffering
    ob_start();

    ?>

        /* Custom Google fonts */
        body { font-family: <?php echo esc_attr( get_theme_mod( 'body_font', '"Merriweather", Georgia, serif' ) ); ?>; }
        h1,h2,h3,h4,h5,h6 { font-family: <?php echo esc_attr( get_theme_mod( 'header_font', '"Cabin", Helvetica, sans-serif' ) ); ?>; }

        /* default text color */
        body {
          color: <?php echo esc_attr( get_theme_mod( 'food_express_text_color', '#2f2f2f' ) ); ?>;
        }
        /* Headings */
        h1, h2, h3, h4, h5, h6 {
          color: <?php echo esc_attr( get_theme_mod( 'food_express_heading_color', '#cb7152' ) ); ?>;
        }
        h1:after, h2:after, h3:after, h4:after, h5:after, h6:after{
          border-color: <?php echo esc_attr( get_theme_mod( 'food_express_heading_color', '#cb7152' ) ); ?>;
        }

        /* HEADER AREA */
        header.site-header:before{
          background-color: <?php echo esc_attr( get_theme_mod( 'food_express_head_bg_color', '#000000' ) ); ?>;
          <?php if( get_theme_mod('food_express_only_bg_color') ): ?>
            opacity: 1;
          <?php endif; ?>
        }
        .site-header a{
          color: <?php echo esc_attr( get_theme_mod( 'food_express_menu_color', '#e1e1e1' ) ); ?>;
        }

        /* Highlights */
        .site-content a,
        .back-to-top,
        .home-block .text-area .sub-title,
        .site-info a,
        .home-recent-posts .section-main .recent-post-content h1:hover{
          color: <?php echo esc_attr( get_theme_mod( 'food_express_highlight_color', '#cb7152' ) ); ?>;
        }
        .site-content a:hover,
        .site-content a:focus,
        .site-content a:active,
        .site-info a:hover, .site-info a:focus, .site-info:active{
          color: <?php echo esc_attr( food_express_adjust_brightness( esc_attr( get_theme_mod( 'food_express_highlight_color', '#cb7152' ) ), -25 ) . (is_customize_preview() ? ' !important' : '') ); ?>;
        }
        .home-block .text-area .sub-title:before,
        .home-block .text-area .sub-title:after,
        .home-recent-posts h2:after,
        .home-recent-posts .section-main .recent-post-content h1:after,
        #pirate-forms-contact-submit,
        .back-to-top:hover,
        a.btn,
        .page-template-homepage .btn,
        .book-a-table-section .cta a,
        .site-footer .widget h4:before {
          background-color: <?php echo esc_attr( get_theme_mod( 'food_express_highlight_color', '#cb7152' ) ); ?>;
        }
        .back-to-top,
        .page-template-homepage .btn:before{
          border-color: <?php echo esc_attr( get_theme_mod( 'food_express_highlight_color', '#cb7152' ) ); ?>;
        }

        /* Default Buttons */
        .button,
        button,
        input[type=submit],
        input[type=reset],
        input[type=button],
        a.btn,
        .page-template-homepage .btn {
            color: <?php echo esc_attr( get_theme_mod( 'food_express_button_text_color', '#e1e1e1' ) ); ?>;
        }
        a.btn:hover, a.btn:focus, a.btn:active,
        .page-template-homepage .btn:hover,
        .page-template-homepage .btn:visited,
        .page-template-homepage .btn:active,
        .button:hover, button:hover, input[type=submit]:hover, input[type=reset]:hover, input[type=button]:hover,
        .button:focus, button:focus, input[type=submit]:focus, input[type=reset]:focus, input[type=button]:focus,
        .button:active, button:active, input[type=submit]:active, input[type=reset]:active, input[type=button]:active,
        #pirate-forms-contact-submit {
            color: <?php echo esc_attr( get_theme_mod( 'food_express_button_text_color', '#e1e1e1' ) ); ?>;
        }

        /* Footer colors */

        .site-footer{ background-color: <?php echo esc_attr( get_theme_mod( 'food_express_footer_bg_color', '#23282f' ) ); ?>; }
        .site-footer, .site-footer h2, .site-footer h3, .site-footer h4 { color: <?php echo esc_attr( get_theme_mod( 'food_express_footer_txt_color', '#ffffff' ) ); ?>;}
        .site-footer a{ color: <?php echo esc_attr( get_theme_mod( 'food_express_footer_link_color', '#cb7152' ) ); ?>;}
        .site-footer a:hover, .site-footer a:active, .site-footer a:focus{ color: <?php echo esc_attr( food_express_adjust_brightness( esc_attr( get_theme_mod( 'food_express_highlight_color', '#cb7152' ) ), -25 ) . (is_customize_preview() ? ' !important' : '') ); ?>;}

    <?php

    // Release output buffering
    return ob_get_clean();
}

/* Front-end custom styles */
function food_express_colors_customizer_wp_head() {
    echo '<style type="text/css">' . food_express_colors_customizer_get_output() . '</style>';
}
add_action('wp_head', 'food_express_colors_customizer_wp_head', 20);
