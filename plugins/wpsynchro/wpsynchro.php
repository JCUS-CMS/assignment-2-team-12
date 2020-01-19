<?php
/*
  Plugin Name: WP Synchro
  Plugin URI: https://wpsynchro.com/home
  Description: Complete migration plugin for WordPress - Synchronization of database and files made easy.
  Version: 1.5.2
  Author: WPSynchro
  Author URI: https://wpsynchro.com
  Domain Path: /languages/
  Text Domain: wpsynchro
  License: GPLv3
  License URI: http://www.gnu.org/licenses/gpl-3.0
 */

/**
 * 	Copyright (C) 2018 WPSynchro (email: support@wpsynchro.com)
 *
 * 	This program is free software; you can redistribute it and/or
 * 	modify it under the terms of the GNU General Public License
 * 	as published by the Free Software Foundation; either version 2
 * 	of the License, or (at your option) any later version.
 *
 * 	This program is distributed in the hope that it will be useful,
 * 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * 	GNU General Public License for more details.
 *
 * 	You should have received a copy of the GNU General Public License
 * 	along with this program; if not, write to the Free Software
 * 	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

define('WPSYNCHRO_VERSION', '1.5.2');
define('WPSYNCHRO_DB_VERSION', '4');
define('WPSYNCHRO_NEWEST_MU_COMPATIBILITY_VERSION', '1.0.3');

// Load composer autoloader
require_once(dirname(__FILE__) . "/vendor/autoload.php");

/**
 *  On plugin activation
 *  @since 1.0.0
 */
function wpsynchroActivation($networkwide)
{
    if (is_multisite() && $networkwide) {
        wp_die(__("WP Synchro does not support being network activated - Activate it on the sites needed instead. If you want to migrate the entire multisite, activate the plugin on the root site. If you want to migrate a subsite, activate WP Synchro on this specific subsite.", "wpsynchro"), "", array("back_link" => true));
    }

    \WPSynchro\Utilities\AdminActivation::activation();
}
register_activation_hook(__FILE__, 'wpsynchroActivation');

/**
 *  On plugin deactivation
 *  @since 1.0.0
 */
function wpsynchroDeactivation()
{
    \WPSynchro\Utilities\AdminDeactivation::deactivation();
}
register_deactivation_hook(__FILE__, 'wpsynchroDeactivation');

/**
 *  Run WP Synchro
 *  @since 1.0.0
 */
$wpsynchro = new WPSynchro\WPSynchroBootstrap();
$wpsynchro->run();
