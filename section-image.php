<?php

/**
 * @link              clarknikdelpowell.com/agency/people/glenn
 * @since             1.0.0
 * @package           Section_Image
 *
 * @wordpress-plugin
 * Plugin Name:       Section Image
 * Plugin URI:        clarknikdelpowell.com
 * Description:       Retrieves a media object by term.
 * Version:           1.0.0
 * Author:            Glenn Welser
 * Author URI:        clarknikdelpowell.com/agency/people/glenn
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       section-image
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-section-image-activator.php
 */
function activate_section_image() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-section-image-activator.php';
	Section_Image_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-section-image-deactivator.php
 */
function deactivate_section_image() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-section-image-deactivator.php';
	Section_Image_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_section_image' );
register_deactivation_hook( __FILE__, 'deactivate_section_image' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-section-image.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_section_image() {

	$plugin = new Section_Image();
	$plugin->run();

}

run_section_image();

function get_section_image( $term ) {

	return Section_Image::get_media_by_term( $term );
}


function find_section_image() {

	return Section_Image::find_section_image();
}
