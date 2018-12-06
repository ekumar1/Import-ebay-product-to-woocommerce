<?php

/**
 
 *
 * @link              http://ekumar.com.np
 * @since             1.0.0
 * @package           Import_Ebay_Product_To_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Import eBay Product to Woocommerce
 * Plugin URI:        http://ekumar.com.np/plugins/wp/
 * Description:       This plugin is good for import ebay product to woocommerce
 * Version:           1.0.0
 * Author:            E Kumar Shrestha
 * Author URI:        http://ekumar.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       import-ebay-product-to-woocommerce
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-import-ebay-product-to-woocommerce-activator.php
 */
function activate_import_ebay_product_to_woocommerce() {
    
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-import-ebay-product-to-woocommerce-activator.php';
	Import_Ebay_Product_To_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-import-ebay-product-to-woocommerce-deactivator.php
 */
function deactivate_import_ebay_product_to_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-import-ebay-product-to-woocommerce-deactivator.php';
	Import_Ebay_Product_To_Woocommerce_Deactivator::deactivate();
	
	
}

register_activation_hook( __FILE__, 'activate_import_ebay_product_to_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_import_ebay_product_to_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-import-ebay-product-to-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_import_ebay_product_to_woocommerce() {

	$plugin = new Import_Ebay_Product_To_Woocommerce();
	$plugin->run();

}
run_import_ebay_product_to_woocommerce();


