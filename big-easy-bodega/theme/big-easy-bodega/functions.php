<?php
/**
 * Big Easy Bodega theme functions.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEB_THEME_VERSION', '1.0.0' );
define( 'BEB_THEME_DIR', get_template_directory() );
define( 'BEB_THEME_URI', get_template_directory_uri() );

require_once BEB_THEME_DIR . '/inc/setup.php';
require_once BEB_THEME_DIR . '/inc/enqueue.php';
require_once BEB_THEME_DIR . '/inc/customizer.php';
require_once BEB_THEME_DIR . '/inc/template-tags.php';
require_once BEB_THEME_DIR . '/inc/fallback-menu.php';
require_once BEB_THEME_DIR . '/inc/woocommerce.php';

/**
 * Fallback notice when WooCommerce is inactive.
 */
function beb_woocommerce_missing_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'Big Easy Bodega theme works best with WooCommerce. Please install and activate WooCommerce.', 'big-easy-bodega' );
	echo '</p></div>';
}
add_action( 'admin_notices', static function (): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		beb_woocommerce_missing_notice();
	}
} );
