<?php
/**
 * Template helper functions.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme asset URI helper.
 *
 * @param string $path Relative path under assets/.
 * @return string
 */
function beb_asset( string $path ): string {
	return trailingslashit( BEB_THEME_URI ) . 'assets/' . ltrim( $path, '/' );
}

/**
 * Output primary logo for header (compact mark).
 */
function beb_the_header_logo(): void {
	$custom = get_custom_logo();
	if ( $custom ) {
		echo $custom; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}
	$url  = home_url( '/' );
	$src  = beb_asset( 'images/logo-mark.svg' );
	$name = get_bloginfo( 'name' );
	printf(
		'<a class="site-brand" href="%1$s" rel="home"><img src="%2$s" alt="%3$s" width="48" height="48" class="site-brand__mark" /><span class="site-brand__text">%4$s</span></a>',
		esc_url( $url ),
		esc_url( $src ),
		esc_attr( $name ),
		esc_html( $name )
	);
}

/**
 * Payment method links from Customizer.
 *
 * Defaults match owner handles (@heymarii / $heymarii). Apple Pay omitted unless a URL is set.
 *
 * @return array<string,array{label:string,url:string}>
 */
function beb_payment_links(): array {
	$links = array(
		'venmo'    => array(
			'label' => __( 'Venmo @heymarii', 'big-easy-bodega' ),
			'url'   => beb_get_option( 'beb_venmo_url', 'https://venmo.com/u/heymarii' ),
		),
		'paypal'   => array(
			'label' => __( 'PayPal', 'big-easy-bodega' ),
			'url'   => beb_get_option( 'beb_paypal_url', 'https://paypal.me/heymarii' ),
		),
		'cashapp'  => array(
			'label' => __( 'Cash App $heymarii', 'big-easy-bodega' ),
			'url'   => beb_get_option( 'beb_cashapp_url', 'https://cash.app/$heymarii' ),
		),
		'applepay' => array(
			'label' => __( 'Apple Pay', 'big-easy-bodega' ),
			'url'   => beb_get_option( 'beb_applepay_url', '' ),
		),
	);

	return array_filter(
		$links,
		static fn( array $item ): bool => '' !== trim( $item['url'] )
	);
}

/**
 * Render payment instruction buttons.
 */
function beb_render_payment_links(): void {
	$links = beb_payment_links();
	if ( empty( $links ) ) {
		return;
	}
	echo '<ul class="payment-links">';
	foreach ( $links as $key => $link ) {
		printf(
			'<li class="payment-links__item payment-links__item--%1$s"><a class="payment-links__btn" href="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a></li>',
			esc_attr( $key ),
			esc_url( $link['url'] ),
			esc_html( $link['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Shop URL helper.
 *
 * @return string
 */
function beb_shop_url(): string {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return (string) wc_get_page_permalink( 'shop' );
	}
	return home_url( '/shop/' );
}

/**
 * Query products for carousels.
 *
 * @param string $type newly|on_sale.
 * @param int    $limit Number of products.
 * @return WC_Product[]
 */
function beb_get_carousel_products( string $type = 'newly', int $limit = 8 ): array {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$args = array(
		'status'  => 'publish',
		'limit'   => $limit,
		'orderby' => 'date',
		'order'   => 'DESC',
		'return'  => 'objects',
	);

	if ( 'on_sale' === $type ) {
		$args['include'] = array_map( 'intval', wc_get_product_ids_on_sale() );
		if ( empty( $args['include'] ) ) {
			return array();
		}
		unset( $args['orderby'], $args['order'] );
	}

	$products = wc_get_products( $args );
	return is_array( $products ) ? $products : array();
}
