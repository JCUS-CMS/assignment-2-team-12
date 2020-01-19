<?php

function load_css()
{

		wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css',
			array(), false, 'all' );
		wp_enqueue_style('bootstrap');


}
add_action('wp_enqueue_scripts', 'load_css');

//function load javascript
function load_js()
{
	wp_enqueue_script('jquery');

	wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', 'jquery', false, true);

	wp_enqueue_script('bootstrap');

}
add_action('wp_enqueue_scripts', 'load_js');

function airi_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Airi, use a find and replace
		 * to change 'airi' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'airi', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'airi-720', 720 );
		add_image_size( 'airi-360-360', 360, 360, true );
		add_image_size( 'airi-850-485', 850, 485, true );
		add_image_size( 'airi-390-280', 390, 280, true );
		add_image_size( 'airi-969-485', 969, 485, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'airi' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'airi_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

	}
endif;
add_action( 'after_setup_theme', 'airi_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function airi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'airi_content_width', 1170 );
}
add_action( 'after_setup_theme', 'airi_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
}

function airi_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Airi, use a find and replace
		 * to change 'airi' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'airi', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'airi-720', 720 );
		add_image_size( 'airi-360-360', 360, 360, true );
		add_image_size( 'airi-850-485', 850, 485, true );
		add_image_size( 'airi-390-280', 390, 280, true );
		add_image_size( 'airi-969-485', 969, 485, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'airi' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'airi_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

	}
endif;
add_action( 'after_setup_theme', 'airi_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function airi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'airi_content_width', 1170 );
}
add_action( 'after_setup_theme', 'airi_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function airi_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'airi' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'airi' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	//Footer widget areas
	$widget_areas = get_theme_mod( 'footer_widget_areas', '4' );
	for ( $i=1; $i <= $widget_areas; $i++ ) {
		register_sidebar( array(
			'name'          => __( 'Footer ', 'airi' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	register_widget( 'Airi_Social' );
	register_widget( 'Airi_Recent_Posts' );

}
