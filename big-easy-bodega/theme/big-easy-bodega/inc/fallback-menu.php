<?php
/**
 * Fallback primary menu when none assigned.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Simple fallback menu: Home + Shop.
 */
function beb_fallback_menu(): void {
	$shop = beb_shop_url();
	echo '<ul class="primary-nav__list">';
	printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Home', 'big-easy-bodega' ) );
	printf( '<li><a href="%s">%s</a></li>', esc_url( $shop ), esc_html__( 'Shop', 'big-easy-bodega' ) );
	if ( function_exists( 'wc_get_cart_url' ) ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( wc_get_cart_url() ), esc_html__( 'Cart', 'big-easy-bodega' ) );
	}
	echo '</ul>';
}
