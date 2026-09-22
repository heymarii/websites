<?php
/**
 * Theme setup.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports, menus, and image sizes.
 */
function beb_theme_setup(): void {
	load_theme_textdomain( 'big-easy-bodega', BEB_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'big-easy-bodega' ),
			'footer'  => __( 'Footer Menu', 'big-easy-bodega' ),
		)
	);

	add_image_size( 'beb-product-card', 480, 480, true );
	add_image_size( 'beb-hero', 1400, 560, true );
}
add_action( 'after_setup_theme', 'beb_theme_setup' );

/**
 * Content width for embeds/media.
 */
function beb_content_width(): void {
	$GLOBALS['content_width'] = 1120;
}
add_action( 'after_setup_theme', 'beb_content_width', 0 );

/**
 * Site favicon fallback to theme mark.
 *
 * @param array<string,mixed> $meta_tags Existing tags.
 * @return array<string,mixed>
 */
function beb_favicon_fallback( array $meta_tags ): array {
	if ( has_site_icon() ) {
		return $meta_tags;
	}
	$icon = BEB_THEME_URI . '/assets/images/favicon.svg';
	echo '<link rel="icon" href="' . esc_url( $icon ) . '" type="image/svg+xml" />' . "\n";
	return $meta_tags;
}
add_filter( 'site_icon_meta_tags', 'beb_favicon_fallback' );
