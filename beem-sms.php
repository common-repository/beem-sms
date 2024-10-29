<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://beem.africa/
 * @since             2.0.0
 * @package           Beem_Sms
 *
 * @wordpress-plugin
 * Plugin Name:       Beem Sms
 * Plugin URI:        https://https://beem.africa/
 * Description:       Beem Sms is a lightweight SMS notification plugin for WordPress woocommerce stores.
 * Version:           2.0.0
 * Author:            Beem Africa
 * Author URI:        https://https://beem.africa/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       beem-sms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 2.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BEEM_SMS_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-beem-sms-activator.php
 */
function activate_beem_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-beem-sms-activator.php';
	Beem_Sms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-beem-sms-deactivator.php
 */
function deactivate_beem_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-beem-sms-deactivator.php';
	Beem_Sms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_beem_sms' );
register_deactivation_hook( __FILE__, 'deactivate_beem_sms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-beem-sms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_beem_sms() {

	$plugin = new Beem_Sms();
	$plugin->run();

}
run_beem_sms();
