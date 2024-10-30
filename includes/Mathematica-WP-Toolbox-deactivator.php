<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/includes
 * @author     Calle Ekdahl <calle@ekdahl.email>
 */
class Mathematica_WP_Toolbox_Deactivator {

	/**
	 * Deactivate the plugin.
	 *
	 * Delete any transients that the plugin created
	 * when interfacing with the Mathematica.SE API.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$APIHandler = new Mathematica_WP_Toolbox_API();
		$APIHandler->clear_cache();
	}

}
