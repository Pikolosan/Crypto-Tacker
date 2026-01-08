<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Parth_Crypto_Tracker
 *
 * @wordpress-plugin
 * Plugin Name:       Parth Crypto Tracker
 * Plugin URI:        http://example.com/
 * Description:       Fetches and caches Crypto prices using the WP HTTP API. Built for performance.
 * Version:           1.0.0
 * Author:            Parth Chaudhary
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * Text Domain:       parth-crypto-tracker
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PARTH_CRYPTO_TRACKER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_parth_crypto_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parth-crypto-tracker-activator.php';
	Parth_Crypto_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_parth_crypto_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parth-crypto-tracker-deactivator.php';
	Parth_Crypto_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parth_crypto_tracker' );
register_deactivation_hook( __FILE__, 'deactivate_parth_crypto_tracker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-parth-crypto-tracker.php';

/**
 * Begins execution of the plugin.
 */
function run_parth_crypto_tracker() {

	$plugin = new Parth_Crypto_Tracker();
	$plugin->run();

}
run_parth_crypto_tracker();