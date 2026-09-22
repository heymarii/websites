<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Front-end assets.
 */
function beb_enqueue_assets(): void {
	wp_enqueue_style(
		'beb-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'beb-main',
		BEB_THEME_URI . '/assets/css/main.css',
		array( 'beb-fonts' ),
		BEB_THEME_VERSION
	);

	wp_enqueue_script(
		'beb-main',
		BEB_THEME_URI . '/assets/js/main.js',
		array(),
		BEB_THEME_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	if ( is_front_page() ) {
		wp_enqueue_script(
			'beb-carousel',
			BEB_THEME_URI . '/assets/js/carousel.js',
			array(),
			BEB_THEME_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	wp_localize_script(
		'beb-main',
		'bebTheme',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'beb_theme' ),
			'i18n'    => array(
				'added'   => __( 'Added to cart', 'big-easy-bodega' ),
				'error'   => __( 'Something went wrong. Please try again.', 'big-easy-bodega' ),
				'submit'  => __( 'Send request', 'big-easy-bodega' ),
				'sending' => __( 'Sending…', 'big-easy-bodega' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'beb_enqueue_assets' );

/**
 * Editor styles for block consistency.
 */
function beb_editor_styles(): void {
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'after_setup_theme', 'beb_editor_styles' );
