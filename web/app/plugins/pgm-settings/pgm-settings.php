<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.gimond.fr
 * @since             1.0.0
 * @package           Pgm_Settings
 *
 * @wordpress-plugin
 * Plugin Name:       PGM Global Settings
 * Plugin URI:        https://www.gimond.fr
 * Description:       Global site settings for performance and security enhancements. Mainly back-office with some front office <head> cleanup and customization for the login page.
 * Version:           1.0.0
 * Author:            Gimond
 * Author URI:        https://www.gimond.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pgm-settings
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
define( 'PGM_SETTINGS_VERSION', '1.0.0' );
function activate_pgm_settings() {
}
function deactivate_pgm_settings() {
}
//register_activation_hook( __FILE__, 'activate_pgm_settings' );
//register_deactivation_hook( __FILE__, 'deactivate_pgm_settings' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pgm-settings.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
new Pgm_Settings();
