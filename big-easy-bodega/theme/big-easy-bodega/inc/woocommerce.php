<?php
/**
 * WooCommerce theme integration and template hooks.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce support sizing and remove default wrappers.
 */
function beb_woocommerce_setup(): void {
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	add_action( 'woocommerce_before_main_content', 'beb_wc_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'beb_wc_wrapper_end', 10 );

	// Custom product cards: strip default link/title/thumb wrappers.
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'beb_loop_short_description', 7 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 5 );

	add_filter( 'loop_shop_per_page', static fn(): int => 12 );
	add_filter( 'loop_shop_columns', static fn(): int => 3 );

	// Building pickup only — no shipping address required at checkout.
	add_filter( 'woocommerce_cart_needs_shipping', '__return_false' );
	add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );
}
add_action( 'after_setup_theme', 'beb_woocommerce_setup' );

/**
 * Open WooCommerce content wrapper.
 */
function beb_wc_wrapper_start(): void {
	echo '<main id="primary" class="site-main woocommerce-page"><div class="container">';
}

/**
 * Close WooCommerce content wrapper.
 */
function beb_wc_wrapper_end(): void {
	echo '</div></main>';
}

/**
 * Short description under product title in loops.
 */
function beb_loop_short_description(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$short = $product->get_short_description();
	if ( '' === $short ) {
		return;
	}
	echo '<p class="product-card__excerpt">' . esc_html( wp_strip_all_tags( $short ) ) . '</p>';
}

/**
 * Checkout: show payment instruction links after order review.
 */
function beb_checkout_payment_instructions(): void {
	$note = beb_get_option(
		'beb_payment_note',
		__( 'Pay the order total using one of the links below. Include your apartment number in the payment note.', 'big-easy-bodega' )
	);
	echo '<section class="beb-payment-instructions" aria-labelledby="beb-pay-heading">';
	echo '<h3 id="beb-pay-heading">' . esc_html__( 'How to pay', 'big-easy-bodega' ) . '</h3>';
	echo '<p class="beb-payment-instructions__note">' . esc_html( $note ) . '</p>';
	beb_render_payment_links();
	echo '</section>';
}
add_action( 'woocommerce_review_order_before_payment', 'beb_checkout_payment_instructions', 5 );

/**
 * Thank-you page enhancements: pickup + door code when processing/completed.
 *
 * @param int $order_id Order ID.
 */
function beb_thankyou_pickup_block( int $order_id ): void {
	if ( $order_id < 1 ) {
		return;
	}
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	$status = $order->get_status();
	$show_code = in_array( $status, array( 'processing', 'completed' ), true );

	$instructions = beb_get_option(
		'beb_pickup_instructions',
		__( 'After payment is confirmed, use the door code to enter Building 26 and pick up next to apartment 26102.', 'big-easy-bodega' )
	);
	$phone = beb_get_option( 'beb_contact_phone', '' );
	$email = beb_get_option( 'beb_contact_email', 'hello@bigeasybodega.com' );

	$door_code = '';
	if ( $show_code && function_exists( 'beb_get_door_code' ) ) {
		$door_code = beb_get_door_code();
	} elseif ( $show_code ) {
		$door_code = (string) get_option( 'beb_door_code', '' );
	}

	echo '<section class="beb-pickup-receipt" aria-labelledby="beb-pickup-heading">';
	echo '<h2 id="beb-pickup-heading">' . esc_html__( 'Pickup instructions', 'big-easy-bodega' ) . '</h2>';
	echo '<p>' . esc_html( $instructions ) . '</p>';

	if ( $show_code && '' !== $door_code ) {
		echo '<div class="beb-door-code" role="status">';
		echo '<span class="beb-door-code__label">' . esc_html__( 'Door code', 'big-easy-bodega' ) . '</span>';
		echo '<span class="beb-door-code__value">' . esc_html( $door_code ) . '</span>';
		echo '</div>';
	} else {
		echo '<p class="beb-pickup-receipt__pending">';
		echo esc_html__( 'Your door code will appear here once we confirm payment and mark the order as Processing.', 'big-easy-bodega' );
		echo '</p>';
		echo '<div class="beb-payment-instructions beb-payment-instructions--receipt">';
		echo '<p>' . esc_html__( 'Still need to pay? Use a link below and include your apartment number.', 'big-easy-bodega' ) . '</p>';
		beb_render_payment_links();
		echo '</div>';
	}

	echo '<p class="beb-pickup-receipt__contact">';
	echo esc_html__( 'Something wrong?', 'big-easy-bodega' ) . ' ';
	if ( $phone ) {
		printf(
			'<a href="tel:%1$s">%2$s</a>',
			esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ?? $phone ),
			esc_html( $phone )
		);
		echo ' · ';
	}
	printf(
		'<a href="mailto:%1$s">%1$s</a>',
		esc_attr( $email )
	);
	echo '</p></section>';
}
add_action( 'woocommerce_thankyou', 'beb_thankyou_pickup_block', 15 );

/**
 * Body class for WC pages.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function beb_body_classes( array $classes ): array {
	$classes[] = 'beb-theme';
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'beb-woocommerce';
	}
	return $classes;
}
add_filter( 'body_class', 'beb_body_classes' );
