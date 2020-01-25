<?php
/*
* Plugin Name: StartWP Extended
* Plugin URI: http://www.getstartwp.com
* Description: This plugin extends the functionality of startwp theme.
* Version: 1.1
* Author: munirkamal
* Author URI: http://cakewp.com
* License:      GPL2
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:  start

{StartWP Extended} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{StartWP Extended} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {StartWP Extended}. If not, see {GPL2}.

*/


add_action('plugins_loaded', 'swp_extended_init');

if (!function_exists('swp_extended_init')){
    function swp_extended_init() {
        add_action('init', 'swp_extended', 9999);
    }
}  



if(!function_exists('swp_extended')) {
function swp_extended() {

// Check Start Theme is active
$theme = wp_get_theme(); 
if ( 'Start' == $theme->name || 'Start' == $theme->parent_theme ) {



$start_extended_path =  plugin_dir_path( __FILE__ );
if ( file_exists( $start_extended_path . 'inc/admin/class-startwp-extensions-page.php' ) ) {
require_once( $start_extended_path . 'inc/admin/class-startwp-extensions-page.php' );
}
require_once( $start_extended_path  . 'inc/customizer.php');


function swp_extended_scripts() {   
    wp_enqueue_style( 'swp_extended_css', plugin_dir_url( __FILE__ ) . 'inc/css/swp_extended.css' );
}
add_action('wp_enqueue_scripts', 'swp_extended_scripts');


} // Theme Check
} // function check
} // function exist


?>