<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://danieljblumenfeld.com/rrr-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class DJB_RRR_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'djb-rrr',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
