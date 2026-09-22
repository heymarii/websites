<?php
/**
 * Plugin Name: Big Easy Bodega Core
 * Plugin URI: https://bigeasybodega.com
 * Description: Checkout contact/apartment fields, door code for pickup, item-request submissions, and payment-instruction gateway for Big Easy Bodega.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Big Easy Bodega
 * Text Domain: beb-bodega-core
 * License: GPLv2 or later
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEB_CORE_VERSION', '1.0.0' );
define( 'BEB_CORE_FILE', __FILE__ );
define( 'BEB_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BEB_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once BEB_CORE_DIR . 'includes/class-item-requests.php';
require_once BEB_CORE_DIR . 'includes/class-checkout-fields.php';
require_once BEB_CORE_DIR . 'includes/class-door-code.php';
require_once BEB_CORE_DIR . 'includes/class-payment-gateway.php';
require_once BEB_CORE_DIR . 'includes/class-admin-settings.php';

/**
 * Boot plugin features.
 */
function beb_core_init(): void {
	BEB_Item_Requests::init();
	BEB_Door_Code::init();
	BEB_Admin_Settings::init();

	if ( class_exists( 'WooCommerce' ) ) {
		BEB_Checkout_Fields::init();
		add_filter( 'woocommerce_payment_gateways', array( 'BEB_Payment_Gateway', 'register' ) );
	}
}
add_action( 'plugins_loaded', 'beb_core_init' );

/**
 * Public helper used by the theme thank-you template.
 *
 * @return string
 */
function beb_get_door_code(): string {
	return BEB_Door_Code::get_code();
}

/**
 * Activation: flush rewrite rules for CPT.
 */
function beb_core_activate(): void {
	BEB_Item_Requests::register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'beb_core_activate' );

/**
 * Deactivation cleanup.
 */
function beb_core_deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'beb_core_deactivate' );
