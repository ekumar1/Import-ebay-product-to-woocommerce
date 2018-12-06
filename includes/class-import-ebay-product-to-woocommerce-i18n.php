<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://ekumar.com.np
 * @since      1.0.0
 *
 * @package    Import_Ebay_Product_To_Woocommerce
 * @subpackage Import_Ebay_Product_To_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Import_Ebay_Product_To_Woocommerce
 * @subpackage Import_Ebay_Product_To_Woocommerce/includes
 * @author     E Kumar Shrestha <shresthaekumar@gmail.com>
 */
class Import_Ebay_Product_To_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'import-ebay-product-to-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
