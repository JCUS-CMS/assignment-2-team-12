<?php
/*
===========================================================================================================
One ARK - functions.php
===========================================================================================================
This is the most generic template file in a WordPress theme and is one of the two required files for a 
theme (the other being template-tags.php). This functions.php template file allows you to add features and 
functionality to a WordPress theme which is stored in the theme's sub-directory in the theme folder. The 
second template file template-tags.php allows you to add additional features and functionality to the 
WordPress theme which is stored in the includes folder and it's called in the functions.php.

@package        One ARK WordPress Theme
@copyright      Copyright (C) 2018. Benjamin Lu
@license        GNU General Public License v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
@author         Benjamin Lu (https://www.getbenonit.com/)
===========================================================================================================
*/

/*
===========================================================================================================
Table of Content
===========================================================================================================
 1.0 - Theme Setup
 2.0 - Enqueue Scripts and Styles
 3.0 - Content Width
 4.0 - Register Sidebars
 5.0 - Required Files
===========================================================================================================
*/

/*
===========================================================================================================
Table of Content
===========================================================================================================
 1.0 - Theme Setup
===========================================================================================================
*/
function one_ark_theme_setup() {
    /*
    ===========================================================================================================
    Enable and activate add_theme_support('title-tag); for Perfect Choice WordPress Theme. This feature should 
    be used in place instead of wp_title() function. 
    ===========================================================================================================
    */
    add_theme_support('title-tag');
    
    /*
    ===========================================================================================================
    Enable and activate add_theme_support('automatic-feed-links'); for Camaraderie WordPress Theme. This feature 
    when enabled allows feed links for posts and comments in the head. This should be used in place of the 
    deprecated automatic_feed_link(); function.
    ===========================================================================================================
    */
    add_theme_support('automatic-feed-links');

    /*
    =======================================================================================================
    Enable and activate register_nav_menus(); for Nu Snowflakes WordPress Theme. This feature when enabled, 
    allows you to create a Primary Navigation and Social Navigation menus in the dashboard under Menus.
    =======================================================================================================
    */
    register_nav_menus(array(
        'primary-navigation'    => esc_html__('Primary Navigation', 'one-ark'),
        'secondary-navigation'  => esc_html__('Secondary Navigation', 'one-ark'),
        'social-navigation'     => esc_html__('Social Navigation', 'one-ark')
    ));

    /*
    ===========================================================================================================
    Enable and activate add_theme_support('html5'); for Perfect Choice WordPress Theme. This feature allows the 
    use of HTML5 markup for search forms, comment forms, comment list, gallery, and captions.
    ===========================================================================================================
    */
    add_theme_support('html5', array(
        'comment-list', 
        'comment-form', 
        'search-form', 
        'gallery', 
        'caption'
    ));

    /*
    ===========================================================================================================
    Enable and activate add_theme_support('post-thumbnails); for Perfect Choice WordPress Theme. This feature 
    enables Post Thumbnails (Featured Images) support for a theme. If you wish to display thumbnails, use the 
    following to display the_post_thumbnail();. If you need to to check of there is a post thumbnail, then use 
    has_post_thumbnail();.
    ===========================================================================================================
    */
    add_theme_support('post-thumbnails');

    /*
    ===========================================================================================================
    add_image_size('perfect-choice-medium-thumbnails', 300, 300, true); should be used under the following files, 
    content.php
    ===========================================================================================================
    */
    add_image_size('one-ark-medium-thumbnails', 1206, 300, true);
}
add_action('after_setup_theme', 'one_ark_theme_setup');

/*
===========================================================================================================
 2.0 - Enqueue Scripts and Styles
===========================================================================================================
*/
function one_ark_enqueue_scripts_and_styles_setup() {
    /*
    =======================================================================================================
    Enable and activate the main stylesheet and custom stylesheet if available for One ARK WordPress 
    Theme. The main stylesheet should be enqueued rather than using @import.
    =======================================================================================================
    */
    wp_enqueue_style('one-ark-style', get_stylesheet_uri());
    wp_enqueue_style('one-ark-normalize', get_template_directory_uri() . '/css/normalize.css', '05012018', true);

    /*
    =======================================================================================================
    Enable and activate Google Fonts (Sanchez and Merriweather) locally for One ARK WordPress Theme. 
    For more information regarding this feature, please go the following url to begin the awesomeness of 
    Google WebFonts Helper. 
    Reference: (https://google-webfonts-helper.herokuapp.com/fonts)
    =======================================================================================================
    */
    wp_enqueue_style('one-ark-custom-fonts', get_template_directory_uri() . '/extras/fonts/custom-fonts.css', '05012018', true);

    /*
    =======================================================================================================
    Enable and activate Font Awesome 4.7 locally for One ARK WordPress Theme. For more information about 
    Font Awesome, please navigate to the URL for more information. 
    Reference: (http://fontawesome.io/)
    =======================================================================================================
    */
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/extras/font-awesome/css/font-awesome.css', '05012018', true);

    /*
    =======================================================================================================
    Enable and activate (JavaScript/JQuery) to support Navigation Menu for Primary Navigation for One ARK 
    WordPress Theme. This allows you to use click feature for dropdowns and multiple depths, When using this new 
    feature of the navigation. The Menu for mobile side is now at the 
    bottom of the page.
    =======================================================================================================
    */
    wp_enqueue_script('one-ark-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '05012018', true);
    wp_localize_script('one-ark-navigation', 'perfectchoiceScreenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . esc_html__('expand child menu', 'one-ark') . '</span>',
		'collapse' => '<span class="screen-reader-text">' . esc_html__('collapse child menu', 'one-ark') . '</span>',
    ));
    
    /*
    ===========================================================================================================
    Enable and activate the threaded comments for Camaraderie WordPress Theme. This allows users to comment by 
    clicking on reply so that it gets nested to the comments you are trying to response too. Please do remember 
    that you can change the depth of comment's reply in the comments setting in the dashboard.
    ===========================================================================================================
    */
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'one_ark_enqueue_scripts_and_styles_setup');

/*
===========================================================================================================
 3.0 - Content Width
===========================================================================================================
*/
function one_ark_content_width_setup() {
    /*
    ===========================================================================================================
    Using this feature allows you can set the maximum allowed width for any content in the theme like oEmbeds
    and images added to posts.
    ===========================================================================================================
    */
	$GLOBALS['content_width'] = apply_filters('family_grows_content_width_setup', 800);
}
add_action( 'after_setup_theme', 'one_ark_content_width_setup', 0 );

/*
===========================================================================================================
 4.0 - Register Sidebars
===========================================================================================================
*/
function one_ark_regiser_sidebars_setup() {
    /*
    =======================================================================================================
    Enable and activate Primary Sidebar for One ARK WordPress Theme. The Primary Sidebar should only show 
    in the blog posts only rather in the pages. 
    =======================================================================================================
    */
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'one-ark'),
        'description'   => esc_html__('Add widgets here to appear in your sidebar on Blog Posts and Archives only', 'one-ark'),
        'id'            => 'primary-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'one_ark_regiser_sidebars_setup');

/*
===========================================================================================================
 5.0 - Required Files
===========================================================================================================
*/
require_once(get_template_directory() . '/includes/custom-header.php');
require_once(get_template_directory() . '/includes/template-tags.php');
require_once(get_template_directory() . '/includes/theme-customize/control-radio-image.php');
require_once(get_template_directory() . '/includes/theme-posts/function-contents.php');