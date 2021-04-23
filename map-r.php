<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://robert.austin
 * @since             1.0.0
 * @package           Map_R
 *
 * @wordpress-plugin
 * Plugin Name:       Map-R
 * Plugin URI:        http://map_r.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Robert Austin
 * Author URI:        http://robert.austin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       map_r
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MAP_R_VERSION', '1.0.1' );

define( 'BASE_NAME', plugin_basename( __FILE__ ) );
define( 'BASE_PATH',  plugin_dir_path( __DIR__ ) .'map-r'   );
define( 'ADMIN_PATH',  plugin_dir_path( __DIR__ ) .'map-r/admin'   );
define( 'PUBLIC_PATH',  plugin_dir_path( __DIR__ ) .'map-r/public'   );
define( 'LOG_PATH',  plugin_dir_path( __DIR__ ) .'map-r/logs/debug.log'   );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-map-r-activator.php
 */
function activate_map_r() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-map-r-activator.php';
	Map_R_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-map-r-deactivator.php
 */
function deactivate_map_r() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-map-r-deactivator.php';
	Map_R_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_map_r' );
register_deactivation_hook( __FILE__, 'deactivate_map_r' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-map-r.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_map_r() {

	$plugin = new Map_R();
	$plugin->run();

}
run_map_r();
