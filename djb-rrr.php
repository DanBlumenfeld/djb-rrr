<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://danieljblumenfeld.com/rrr-wordpress-plugin/
 * @since             1.0.0
 * @package           djb-rrr
 *
 * @wordpress-plugin
 * Plugin Name:       Routes, Rides, Reports
 * Plugin URI:        http://danieljblumenfeld.com/rrr-wordpress-plugin/
 * Description:       A Wordpress plugin to help manage cycling routes, rides, and ride reports
 * Version:           1.0.0
 * Author:            Dan Blumenfeld
 * Author URI:        http://danieljblumenfeld.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       djb-rrr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-djb-rrr-activator.php
 */
function activate_djb_rrr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-djb-rrr-activator.php';
	DJB_RRR_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-djb-rrr-deactivator.php
 */
function deactivate_djb_rrr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-djb-rrr-deactivator.php';
	DJB_RRR_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_djb_rrr' );
register_deactivation_hook( __FILE__, 'deactivate_djb_rrr' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-djb-rrr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_djb_rrr() {

	$plugin = new DJB_RRR();
	$plugin->run();

}
run_djb_rrr();
