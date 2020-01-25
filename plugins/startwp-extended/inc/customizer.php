<?php

/******************************************************************************************
* Modules
*******************************************************************************************/

// Plugin Path
$start_extended_path =  plugin_dir_path( __FILE__ );


// Woocommerce
$start_extended_woo = get_option( 'swp_woo' );
if( $start_extended_woo[0] == 'Enable' && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){

include_once( $start_extended_path  . 'customizer/woocommerce/woocommerce-general.php'); 
include_once( $start_extended_path  . 'customizer/woocommerce/woocommerce-archive.php'); 
include_once( $start_extended_path  . 'customizer/woocommerce/woocommerce-single.php'); 
} 


// Easy Digital Download
$start_extended_edd = get_option( 'swp_edd' );
if( $start_extended_edd[0] == 'Enable' && is_plugin_active('easy-digital-downloads/easy-digital-downloads.php') ){

include_once( $start_extended_path  . 'customizer/EDD/edd-archive.php'); 
include_once( $start_extended_path  . 'customizer/EDD/edd-single.php'); 
}