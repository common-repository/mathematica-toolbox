<?php

/**
 * The plugin bootstrap file.
 *
 * This file includes all of the dependencies used by the plugin
 * and defines a function that starts the plugin.
 *
 * @since             1.0.0
 * @package           Mathematica_WP_Toolbox
 *
 * @wordpress-plugin
 * Plugin Name:       Mathematica Toolbox
 * Plugin URI:        https://wordpress.org/plugins/Mathematica-Toolbox/
 * Description:       A "toolbox" of shortcodes for people who write articles about Mathematica code/the Wolfram Language. Includes source code highlighting for Mathematica code/Wolfram Language, automated CDF embedding, Wolfram Cloud API utility shortcode, and a seamless cached interface to Mathematica.Stackexchange.com.
 * Version:           1.0.4
 * Author:            Calle Ekdahl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Mathematica-WP-Toolbox-deactivator.php
 */
function deactivate_Mathematica_WP_Toolbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Mathematica-WP-Toolbox-deactivator.php';
	Mathematica_WP_Toolbox_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_Mathematica_WP_Toolbox' );

/**
 * The core plugin class that is used to define admin-specific hooks, 
 * and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/Mathematica-WP-Toolbox.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_Mathematica_WP_Toolbox() {

	$plugin = new Mathematica_WP_Toolbox();
	$plugin->run();

}
run_Mathematica_WP_Toolbox();
