<?php
/**
 * Food Express Theme Customizer.
 *
 * @package Food Express
 */

/*------------------------------------*\
  #CUSTOM SANITIZERS
\*------------------------------------*/
function food_express_sanitize_checkbox( $checked ) {
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
// Sanitize Text
function food_express_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}
// Sanitize Email
function food_express_santitize_email( $input ){
	return sanitize_email( $input );
}
// Sanitize website address
function food_express_sanitize_url($input){
	return esc_url_raw($input);
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function food_express_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
  $wp_customize->get_section( 'colors' )->priority = 55;
  $wp_customize->get_section( 'colors' )->title = __('Color Settings', 'food-express');

  if (class_exists('WP_Customize_Control')) {

    if ( ! class_exists( 'WP_Customize_TE_Control' ) ) {
			class WP_Customize_TE_Control extends WP_Customize_Control {
				public $content = '';

				/**
				 * Constructor
				 */
				function __construct( $manager, $id, $args ) {
					// Just calling the parent constructor here
					parent::__construct( $manager, $id, $args );
				}

				/**
				 * This function renders the control's content.
				 */
				public function render_content() {
					echo $this->content;
				}
			}
		}

  }
}
add_action( 'customize_register', 'food_express_customize_register' );

/**
 * Customizer - Add Custom Styling
 */
function food_express_customizer_style()
{
	wp_enqueue_style('food_express-customizer', get_template_directory_uri() . '/css/customizer.min.css');
}
add_action('customize_controls_print_styles', 'food_express_customizer_style');


/**
 * Pro Link
 */
 function food_express_get_pro_link( $content ) {
	return esc_url( 'https://www.templateexpress.com/food-express-pro-theme' );
}

function food_express_customizer_setup( $wp_customize ) {

  // Start our customize panels below the Page Settings Panel.
  $priority = 50;


  /*
	* //////////////////// Pro Panel ////////////////////////////
	*/
  if(! function_exists('food_express_pro_customize_register')){

    $wp_customize->add_section( 'food_express_pro', array(
			'title' => __( 'PRO FEATURES', 'food-express' ),
			'priority' => 1
		) );

		$wp_customize->add_setting(
			'food_express_pro', // IDs can have nested array keys
			array(
				'default' => false,
				'type' => 'food_express_pro',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_TE_Control(
				$wp_customize,
				'food_express_pro',
				array(
					'content'  => sprintf(
						__( '
            <h3>Some benefits of going with the pro extension plugin</h3>
            <ul>
              <li>Fast and in-depth support</li>
              <li>More theme options</li>
              <li>Woocommerce support</li>
              <li>Remove Template Express from footer</li>
              <li>More updates than free version</li>
              <li>No need to re-configure settings when upgrading</li>
            </ul>
            <h4>%s</h4>', 'food-express' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( food_express_get_pro_link( 'customizer' ) ),
							__( 'Check out the pro version', 'food-express' )
						)
					),
					'section' => 'food_express_pro',
				)
			)
		);


  }




/*------------------------------------*\
  #PAGE SETTINGS
\*------------------------------------*/
  $wp_customize->add_section( 'food_express_page_settings_section' , array(
      'title'       => __( 'Page settings', 'food-express' ),
      'description' => __( 'Max-width of container is 1184px or 80%', 'food-express' ),
      'priority'	 	=> $priority++,
  ));

  /* SETTING: LIMIT HEADER TO CONTAINER */
  $wp_customize->add_setting( 'food_express_header_container_class', array(
      'default'   => true,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_header_container_class', array(
      'label'     => __( 'Limit header content to container', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => true
  ));

  /* SETTING: LIMIT HOMEPAGE CONTENT TO CONTAINER */
  $wp_customize->add_setting( 'food_express_homepage_container_class', array(
      'default'   => true,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_homepage_container_class', array(
      'label'     => __( 'Limit homepage content to container', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => true
  ));

  /* SETTING: LIMIT CONTENT TO CONTAINER */
  $wp_customize->add_setting( 'food_express_content_container_class', array(
      'default'   => true,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_content_container_class', array(
      'label'     => __( 'Limit other content to container', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => true
  ));

  /* SETTING: LIMIT FOOTER TO CONTAINER */
  $wp_customize->add_setting( 'food_express_footer_container_class', array(
      'default'   => true,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_footer_container_class', array(
      'label'     => __( 'Limit footer content to container', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => true
  ));

  /* SETTING: SHOW OR HIDE SIDEBAR ON POST */
  $wp_customize->add_setting( 'food_express_sidebar_hide_post', array(
      'default'   => false,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_sidebar_hide_post', array(
      'label'     => __( 'Hide sidebar on single post', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => false
  ));

  /* SETTING: SHOW OR HIDE SIDEBAR ON POST */
  $wp_customize->add_setting( 'food_express_sidebar_hide_page', array(
      'default'   => false,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
  ));
  $wp_customize->add_control( 'food_express_sidebar_hide_page', array(
      'label'     => __( 'Hide sidebar on pages', 'food-express' ),
      'section'   => 'food_express_page_settings_section',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => false
  ));



  /*------------------------------------*\
    #HOMEPAGE PANEL
  \*------------------------------------*/
    $wp_customize->add_panel( 'food_express_homepage_panel', array(
      'priority'       => $priority,
      'title'          => __('Homepage Settings', 'food-express'),
      'description'    => __('Add and remove homepage sections', 'food-express'),
    ) );
    /*------------------------------------*\
      #HERO SECTION
    \*------------------------------------*/
    $wp_customize->add_section( 'food_express_hero_section' , array(
      'title'       => __( 'Hero settings', 'food-express' ),
      'description' => __( 'Add some wow factor to your homepage with this Hero area', 'food-express' ),
      'panel'       => 'food_express_homepage_panel',
      'priority'	 	=> $priority++,
    ));
    $wp_customize->add_setting( 'food_express_hero_page_id', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_hero_page_id', array(
      'label' 		        => __( 'Select Page', 'food-express' ),
      'description'       => __( 'Make sure the page has a featured image, a snappy title and excerpt.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_hero_section',
      'priority'	 	      => $priority++,
    ));
    //Show Hero Title & SubTitle
    $wp_customize->add_setting( 'food_express_show_hero_title', array(
      'default'						=> false,
      'sanitize_callback' => 'food_express_sanitize_checkbox',
    ));
    $wp_customize->add_control( 'food_express_show_hero_title', array(
      'label'      				=> __('Show title and excerpt', 'food-express'),
      'section'    				=> 'food_express_hero_section',
      'type'		 					=> 'checkbox',
      'default'						=> false,
      'priority'	 				=> $priority++,
    ));
    // Show Hero
    $wp_customize->add_setting( 'food_express_hide_hero', array(
    	'default'						=> false,
    	'sanitize_callback' => 'food_express_sanitize_checkbox',
    ));
    $wp_customize->add_control( 'food_express_hide_hero', array(
    	'label'      				=> __('Hide Hero Section', 'food-express'),
    	'section'    				=> 'food_express_hero_section',
    	'type'		 					=> 'checkbox',
      'default'						=> false,
    	'priority'	 				=> $priority++,
    ));

    // Hero Foreground Image
    $wp_customize->add_setting( 'food_express_hero_section_logo', array(
      'sanitize_callback' => 'food_express_sanitize_url',
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'food_express_hero_section_logo', array(
      'label'    		=> __( 'Foreground Image', 'food-express' ),
      'description' => __( 'Have a large logo or design for this section', 'food-express'),
      'section'  		=> 'food_express_hero_section',
      'priority'	 	=> $priority++,
    )));

    $wp_customize->add_setting( 'food_express_hero_btn_one', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_hero_btn_one', array(
      'label' 		        => __( 'Add Button', 'food-express' ),
      'description'       => __( 'Select a page to display a button that links to it. Short titles work best!', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_hero_section',
      'priority'	 	      => $priority++,
    ));

    $wp_customize->add_setting( 'food_express_hero_btn_two', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_hero_btn_two', array(
      'label' 		        => __( 'Second Button', 'food-express' ),
      'description'       => __( 'Use this for a second button.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_hero_section',
      'priority'	 	      => $priority++,
    ));


    /*------------------------------------*\
      #HOMEPAGE CONTENT BLOCK ONE
    \*------------------------------------*/
    $wp_customize->add_section( 'food_express_content_block_one' , array(
      'title'       => __( 'Content block 1', 'food-express' ),
      'description' => __( 'show off your specials, newest chef or even an upcoming event', 'food-express' ),
      'panel'       => 'food_express_homepage_panel',
      'priority'	 	=> $priority++,
    ));
    // link to page for title, image and description.
    $wp_customize->add_setting( 'food_express_block1_page', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_block1_page', array(
      'label' 		        => __( 'Select Page', 'food-express' ),
      'description'       => __( 'Make sure the page has a featured image, a snappy title and excerpt.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_content_block_one',
      'priority'	 	      => $priority++,
    ));
    // link to page for button
    $wp_customize->add_setting( 'food_express_block_1_btn_url', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_block_1_btn_url', array(
      'label' 		        => __( 'Link for Button', 'food-express' ),
      'description'       => __( 'Leave blank if you dont want a button.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_content_block_one',
      'priority'	 	      => $priority++,
    ));
    // Image left or right
    $wp_customize->add_setting( 'food_express_block_1_img_left', array(
      'default'   => false,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
    ));
    $wp_customize->add_control( 'food_express_block_1_img_left', array(
      'label'     => __( 'Show Image on the left', 'food-express' ),
      'section'   => 'food_express_content_block_one',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => false
    ));


    /*------------------------------------*\
      #HOMEPAGE CALL TO ACTION
    \*------------------------------------*/
    $wp_customize->add_section( 'food_express_highlight_section' , array(
      'title'       => __( 'Call to Action', 'food-express' ),
      'description' => __( 'Highlight a page on your site with this section.', 'food-express' ),
      'panel'       => 'food_express_homepage_panel',
      'priority'	 	=> $priority++,
    ));

    $wp_customize->add_setting('food_express_highlight_page', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_highlight_page', array(
      'label' 		        => __( 'Select Page', 'food-express' ),
      'description'       => __( 'Select the page you want to highlight. Make sure it has a featured image and a snappy title.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_highlight_section',
      'priority'	 	      => $priority++,
    ));
    // button label
    $wp_customize->add_setting( 'food_express_highlight_btn_label', array(
      'default'   => __( 'Book a table', 'food-express'),
      'transport' => 'postMessage',
      'sanitize_callback' => 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'food_express_highlight_btn_label', array(
      'label'             => __( 'Button Label', 'food-express' ),
      'section'           => 'food_express_highlight_section',
      'priority'	 				=> $priority++,
    ));
    // Align txt
    $wp_customize->add_setting( 'food_express_highlight_txt_align', array(
      'default'   => 'right',
      'sanitize_callback' => 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'food_express_highlight_txt_align', array(
      'label'             => __( 'Align Text', 'food-express' ),
      'section'           => 'food_express_highlight_section',
      'type'              => 'radio',
  		'choices'           => array(
    			'left'   => __('left', 'food-express'),
          'center' => __('center', 'food-express'),
    			'right'  => __('right', 'food-express'),

  		),
      'priority'	 				=> $priority++,
    ));

    /*------------------------------------*\
      #HOMEPAGE CONTENT BLOCK TWO
    \*------------------------------------*/
    $wp_customize->add_section( 'food_express_content_block_two' , array(
      'title'       => __( 'Content block 2', 'food-express' ),
      'description' => __( 'show off your specials, newest chef or even an upcoming event', 'food-express' ),
      'panel'       => 'food_express_homepage_panel',
      'priority'	 	=> $priority++,
    ));
    // link to page for title, image and description.
    $wp_customize->add_setting( 'food_express_block2_page', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_block2_page', array(
      'label' 		        => __( 'Select Page', 'food-express' ),
      'description'       => __( 'Make sure the page has a featured image, a snappy title and excerpt.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_content_block_two',
      'priority'	 	      => $priority++,
    ));
    // link to page for button
    $wp_customize->add_setting( 'food_express_block_2_btn_url', array(
      'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'food_express_block_2_btn_url', array(
      'label' 		        => __( 'Link for Button', 'food-express' ),
      'description'       => __( 'Leave blank if you dont want a button.', 'food-express'),
      'type'              => 'dropdown-pages',
      'section' 		      => 'food_express_content_block_two',
      'priority'	 	      => $priority++,
    ));
    // Image left or right
    $wp_customize->add_setting( 'food_express_block_2_img_right', array(
      'default'   => false,
      'transport' => 'refresh',
      'sanitize_callback' => 'food_express_sanitize_checkbox',
    ));
    $wp_customize->add_control( 'food_express_block_2_img_right', array(
      'label'     => __( 'Show Image on the Right', 'food-express' ),
      'section'   => 'food_express_content_block_two',
      'type'      => 'checkbox',
      'priority'	=> $priority++,
      'default'   => false
    ));

    /*------------------------------------*\
      #MENU SECTION
    \*------------------------------------*/
    if ( class_exists('fdmFoodAndDrinkMenu') ) {

      $wp_customize->add_section( 'food_express_menu_section' , array(
        'title'       => __( 'Food Menu Section', 'food-express' ),
        'description' => __( 'Show off your menu, offers, or todays specials.', 'food-express' ),
        'panel'       => 'food_express_homepage_panel',
        'priority'	 	=> $priority++,
      ));

      // Title
      $wp_customize->add_setting( 'food_express_menu_title', array(
        'default'   => __( 'Todays Specials', 'food-express'),
        'transport' => 'postMessage',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_menu_title', array(
        'label'             => __( 'Title', 'food-express' ),
        'section'           => 'food_express_menu_section',
        'priority'	 				=> $priority++,
      ));
      // Sub Title
      $wp_customize->add_setting( 'food_express_menu_subtitle', array(
        'default'   => __( 'Fresh from the kitchen', 'food-express'),
        'transport' => 'postMessage',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_menu_subtitle', array(
        'label'             => __( 'Sub Title', 'food-express' ),
        'section'           => 'food_express_menu_section',
        'priority'	 				=> $priority++,
      ));
      // Tagline
      $wp_customize->add_setting( 'food_express_menu_tagline', array(
        'default'   => __( 'Our experienced chef creates a delicious dish everyday using only the freshest of ingredients.', 'food-express'),
        'transport' => 'postMessage',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_menu_tagline', array(
        'label'             => __( 'Tagline', 'food-express' ),
        'section'           => 'food_express_menu_section',
        'priority'	 				=> $priority++,
      ));
      // Shortcode
      $wp_customize->add_setting( 'food_express_menu_shortcode', array(
        'transport' => 'refresh',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_menu_shortcode', array(
        'label'             => __( 'Menu Shortcode', 'food-express' ),
        'description'       => __( 'Activate the Food and Drink plugin, build your menu then paste the shortcode here for it to appear on the homepage.', 'food-express'),
        'section'           => 'food_express_menu_section',
        'priority'	 				=> $priority++,
      ));
      // Button
      $wp_customize->add_setting('food_express_menu_label', array(
        'default'			      => __( 'Full Menu', 'food-express' ),
        'transport' => 'postMessage',
        'sanitize_callback'	=> 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_menu_label', array(
        'label' 		        => __( 'Link Button Label', 'food-express' ),
        'description'       => __( 'Clear this field to hide button.', 'food-express'),
        'section' 		      => 'food_express_menu_section',
        'priority'	 	      => $priority++,
      ));
      $wp_customize->add_setting('food_express_menu_url', array(
        'sanitize_callback' => 'absint',
      ));
      $wp_customize->add_control( 'food_express_menu_url', array(
        'label' 		        => __( 'Select Page', 'food-express' ),
        'description'       => __( 'Select the page your full menu is on.', 'food-express'),
        'type'              => 'dropdown-pages',
        'section' 		      => 'food_express_menu_section',
        'priority'	 	      => $priority++,
      ));
    }
    /*------------------------------------*\
      #Homepage Recent posts
    \*------------------------------------*/
    $wp_customize->add_section( 'food_express_recent_section' , array(
      'title'       => __( 'Recent News', 'food-express' ),
      'description' => __( 'Configure the recent news section on the homepage.', 'food-express' ),
      'panel'       => 'food_express_homepage_panel',
      'priority'	 	=> $priority++,
    ));
    // Title
    $wp_customize->add_setting( 'food_express_recent_title', array(
      'default'   => __( 'Blog & News', 'food-express'),
      'transport' => 'postMessage',
      'sanitize_callback' => 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'food_express_recent_title', array(
      'label'             => __( 'Title', 'food-express' ),
      'section'           => 'food_express_recent_section',
      'priority'	 				=> $priority++,
    ));
    // Sub-Title
    $wp_customize->add_setting( 'food_express_recent_section_subtitle', array(
      'default'   => __( 'To present an original cuisine where texture, purity of flavour and balance is paramount.', 'food-express'),
      'transport' => 'postMessage',
      'sanitize_callback' => 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'food_express_recent_section_subtitle', array(
      'label'             => __( 'Sub Title', 'food-express' ),
      'section'           => 'food_express_recent_section',
      'priority'	 				=> $priority++,
    ));
    $cats = array();
    foreach ( get_categories() as $categories => $category ){
        $cats[$category->term_id] = $category->name;
    }
    $wp_customize->add_setting( 'food_express_recent_cat', array(
        'default' => 1,
        'sanitize_callback' => 'absint'
    ) );
    $wp_customize->add_control( 'cat_contlr', array(
        'label'   => __('Select Category', 'food-express'),
        'settings' => 'food_express_recent_cat',
        'type' => 'select',
        'choices' => $cats,
        'section' => 'food_express_recent_section',
        'priority'	 				=> $priority++,
    ) );


    /*------------------------------------*\
      #TESTIMONIAL SECTION
    \*------------------------------------*/
    if ( class_exists('grfwpInit') ) {

      $wp_customize->add_section( 'food_express_testimonial_section' , array(
        'title'       => __( 'Testimonial Section', 'food-express' ),
        'description' => __( 'Configure the Testimonial section on the homepage.', 'food-express' ),
        'panel'       => 'food_express_homepage_panel',
        'priority'	 	=> $priority++,
      ));

      // Title
      $wp_customize->add_setting( 'food_express_testimonial_title', array(
        'default'   => __( 'Our Happy Customers', 'food-express'),
        'transport' => 'postMessage',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_testimonial_title', array(
        'label'             => __( 'Title', 'food-express' ),
        'section'           => 'food_express_testimonial_section',
        'priority'	 				=> $priority++,
      ));
      // Shortcode
      $wp_customize->add_setting( 'food_express_testimonial_shortcode', array(
        'default'   => __('[good-reviews limit=5]', 'food-express'),
        'transport' => 'refresh',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_testimonial_shortcode', array(
        'label'             => __( 'Shortcode', 'food-express' ),
        'description'       => sprintf(
          __( 'Shortcode help: %s', 'food-express' ),
          sprintf(
            '<a href="%1$s" target="_blank">%2$s</a>',
            esc_url('http://doc.themeofthecrop.com/plugins/good-reviews-wp/user/faq#shortcode'),
            __( 'Documentation', 'food-express' )
          )
        ),
        'type'              => 'textarea',
        'section'           => 'food_express_testimonial_section',
        'priority'	 				=> $priority++,
      ));

    }

    /*------------------------------------*\
      #HOMEPAGE CONTENT INSTAGRAM SECTION
    \*------------------------------------*/

    if ( function_exists( 'enjoyinstagram_require_activation_class' ) ) {

      $wp_customize->add_section( 'food_express_instagram_section' , array(
        'title'       => __( 'Instgram Gallery', 'food-express' ),
        'description' => __( 'Show your customers the atmosphere, interior and whats on offer using this great looking gallery.', 'food-express' ),
        'panel'       => 'food_express_homepage_panel',
        'priority'	 	=> $priority++,
      ));

      // Instagram Title
      $wp_customize->add_setting( 'food_express_instagram_title', array(
        'default' => __('Instagram', 'food-express'),
        'transport' => 'postMessage',
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_instagram_title', array(
        'label'             => __( 'Title', 'food-express' ),
        'description'       => __('Clear this field if you want to hide the title.', 'food-express'),
        'section'           => 'food_express_instagram_section',
        'priority'	 				=> $priority++,
      ));
      // Gallery Type
      $wp_customize->add_setting( 'food_express_instagram_shortcode', array(
        'sanitize_callback' => 'food_express_sanitize_text',
      ));
      $wp_customize->add_control( 'food_express_instagram_shortcode', array(
        'label'             => __( 'Type of gallery', 'food-express' ),
        'type'              => 'select',
        'choices'           => array(
            '[enjoyinstagram_mb]' => __('Carousel View', 'food-express'),
            '[enjoyinstagram_mb_grid]' => __('Grid View', 'food-express'),
        ),
        'section'           => 'food_express_instagram_section',
        'priority'	 				=> $priority++,
      ));

    }


    /*------------------------------------*\
      #MAP SECTION
    \*------------------------------------*/
    if ( function_exists( 'wpgmaps_activate' ) ) {

      $wp_customize->add_section( 'map_section', array(
      	'title'							=>	__('MAP Settings', 'food-express'),
      	'description'				=> __('Options for displaying a map on the homepage. Make sure you have WP Google Maps installed and activated', 'food-express'),
        'panel'            => 'food_express_homepage_panel',
      	'priority'					=> $priority++,
      ));

        $wp_customize->add_setting('map_show', array(
        	'default'						=> false,
        	'sanitize_callback'	=> 'food_express_sanitize_checkbox',
        ));
        $wp_customize->add_control( 'map_show', array(
        	'type' 					=> 'checkbox',
        	'label' 				=> __('Show Map','food-express'),
        	'section' 			=> 'map_section',
        	'priority' 			=> $priority++,
        ));
        $wp_customize->add_setting('map_section_shortcode', array(
        	'sanitize_callback'	=> 'food_express_sanitize_text',
        ));
        $wp_customize->add_control( 'map_section_shortcode', array(
        	'label' 		        => __( 'Map Shortcode', 'food-express' ),
          'description'       => __('Paste in your map shortcode. WP Google Maps plugin provides the needed shortcode', 'food-express'),
        	'section' 		      => 'map_section',
        	'priority'	 	      => $priority++,
        ));

      }

  /*------------------------------------*\
    #CONTACT SETTINGS
  \*------------------------------------*/
    $wp_customize->add_section( 'food_express_contact_section' , array(
      'title'       => __( 'Contact settings', 'food-express' ),
      'description' => __( 'Control the contact information and display', 'food-express' ),
      'priority'	 	=> $priority++,
    ));

    // Phone
    $wp_customize->add_setting('contact_section_phone', array(
  		'sanitize_callback'	=> 'food_express_sanitize_text',
  	));
  	$wp_customize->add_control( 'contact_section_phone', array(
  		'label' 		        => __( 'Phone', 'food-express' ),
  		'section' 		      => 'food_express_contact_section',
  		'priority'	 	      => $priority++,
  	));
    // Email
  	$wp_customize->add_setting('contact_section_email', array(
  		'sanitize_callback'	=> 'food_express_santitize_email',
  	));
  	$wp_customize->add_control( 'contact_section_email', array(
  		'label' 		        => __( 'Email', 'food-express' ),
  		'section' 		      => 'food_express_contact_section',
  		'priority'	 	      => $priority++,
  	));
    // Address
  	$wp_customize->add_setting('contact_section_address', array(
  		'sanitize_callback'	=> 'food_express_sanitize_text',
  	));
  	$wp_customize->add_control( 'contact_section_address', array(
  		'label' 		        => __( 'Address', 'food-express' ),
  		'section' 		      => 'food_express_contact_section',
  		'priority'	 	      => $priority++,
  	));

    /*------------------------------------*\
      HEADER IMAGE
    \*------------------------------------*/

    /* SETTING: SHOW OR HIDE SIDEBAR ON POST */
    $wp_customize->add_setting( 'food_express_use_header_image', array(
        'default'   => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'food_express_sanitize_checkbox',
        ));
    $wp_customize->add_control( 'food_express_use_header_image', array(
        'label'     => __( 'Use this header image instead of featured images', 'food-express' ),
        'section'   => 'header_image',
        'type'      => 'checkbox',
        'priority'	=> $priority++,
        'default'   => false
        ));
    $wp_customize->add_setting( 'food_express_only_bg_color', array(
        'default'   => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'food_express_sanitize_checkbox',
        ));
    $wp_customize->add_control( 'food_express_only_bg_color', array(
        'label'     => __( 'show only background color for header area', 'food-express' ),
        'section'   => 'header_image',
        'type'      => 'checkbox',
        'priority'	=> $priority++,
        'default'   => false
        ));

    $wp_customize->add_setting( 'food_express_head_bg_color', array(
        'default'       => '#000000',
        'type'          => 'theme_mod',
        'capability'    => 'edit_theme_options',
        'transport'     => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'food_express_head_bg_color',
        array(
            'label'     => __( 'Header Background Color', 'food-express' ),
            'section'   => 'header_image',
            'priority'	=> $priority++,
    )));

    /* Darken the image */
    $wp_customize->add_setting( 'food_express_header_overlay', array(
      'default'           => 0.3,
      'sanitize_callback' => 'food_express_sanitize_text',
    ));
    $wp_customize->add_control( 'food_express_header_overlay_control', array(
        'label'       => __( 'Pull background color through image.', 'food-express' ),
        'description' => __( 'You can have the background color bleed through the image with this setting.', 'food-express' ),
        'section'     => 'header_image',
        'settings'    => 'food_express_header_overlay',
        'type'        => 'range',
        'priority'	  => $priority++,
        'input_attrs' => array(
          'min'   => 0,
          'max'   => 1,
          'step'  => 0.01,
          )
      ));

}
add_action('customize_register', 'food_express_customizer_setup');

/*------------------------------------*\
  #COLORS OUTPUT
\*------------------------------------*/
function food_express_customizer_styles_output() {

    // Start output buffering
    ob_start();

    if(get_theme_mod('food_express_only_bg_color')){
    ?>
      /* remove overlay if set to background color
      .site-header:before { opacity:1;}
    <?php
  }else{
    ?>
    /* header image overlay */
    .site-header:before { opacity: <?php echo esc_attr( get_theme_mod( 'food_express_header_overlay', '0.3' ) ); ?>; }
    <?php
  }
    // Release output buffering
    return ob_get_clean();
  }

  /* Front-end custom styles */
  function food_express_styles_customizer_wp_head() {
      echo '<style type="text/css">' . food_express_customizer_styles_output() . '</style>';
  }
  add_action('wp_head', 'food_express_styles_customizer_wp_head', 20);
