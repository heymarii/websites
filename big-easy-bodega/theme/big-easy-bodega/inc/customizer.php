<?php
/**
 * Theme Customizer settings (payment links, location, maps, door code fallback display copy).
 *
 * Door code value itself is managed by the beb-bodega-core plugin option for security clarity.
 *
 * @package Big_Easy_Bodega
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function beb_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'beb_bodega',
		array(
			'title'    => __( 'Big Easy Bodega', 'big-easy-bodega' ),
			'priority' => 30,
		)
	);

	$settings = array(
		'beb_location_title'   => array(
			'default'     => __( 'Find us at 10X Apartments', 'big-easy-bodega' ),
			'label'       => __( 'Location heading', 'big-easy-bodega' ),
			'type'        => 'text',
		),
		'beb_location_text'    => array(
			'default'     => __( 'We are inside 10X Apartments, Building 26, next to apartment 26102. Browse live stock, check out online, then pick up with your door code.', 'big-easy-bodega' ),
			'label'       => __( 'Location copy', 'big-easy-bodega' ),
			'type'        => 'textarea',
		),
		'beb_maps_embed_url'   => array(
			'default'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3456!2d-95.3698!3d29.7604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjnCsDQ1JzM3LjQiTiA5NcKwMjInMTEuMyJX!5e0!3m2!1sen!2sus!4v1',
			'label'       => __( 'Google Maps embed URL', 'big-easy-bodega' ),
			'type'        => 'url',
			'description' => __( 'Paste the src URL from Google Maps → Share → Embed a map.', 'big-easy-bodega' ),
		),
		'beb_contact_phone'    => array(
			'default'     => '',
			'label'       => __( 'Contact phone (shown on thank-you)', 'big-easy-bodega' ),
			'type'        => 'text',
		),
		'beb_contact_email'    => array(
			'default'     => 'hello@bigeasybodega.com',
			'label'       => __( 'Contact email', 'big-easy-bodega' ),
			'type'        => 'email',
		),
		'beb_pickup_instructions' => array(
			'default'     => __( 'After payment is confirmed, use the door code below to enter Building 26 and pick up your order next to apartment 26102. Leave the bodega tidy for your neighbors.', 'big-easy-bodega' ),
			'label'       => __( 'Pickup instructions', 'big-easy-bodega' ),
			'type'        => 'textarea',
		),
		'beb_venmo_url'        => array(
			'default'     => 'https://venmo.com/u/heymarii',
			'label'       => __( 'Venmo payment link', 'big-easy-bodega' ),
			'type'        => 'url',
			'description' => __( 'Display handle: @heymarii', 'big-easy-bodega' ),
		),
		'beb_paypal_url'       => array(
			'default'     => 'https://paypal.me/heymarii',
			'label'       => __( 'PayPal payment link', 'big-easy-bodega' ),
			'type'        => 'url',
		),
		'beb_cashapp_url'      => array(
			'default'     => 'https://cash.app/$heymarii',
			'label'       => __( 'Cash App payment link', 'big-easy-bodega' ),
			'type'        => 'url',
			'description' => __( 'Cashtag: $heymarii (Cash App uses $)', 'big-easy-bodega' ),
		),
		'beb_applepay_url'     => array(
			'default'     => '',
			'label'       => __( 'Apple Pay link (optional)', 'big-easy-bodega' ),
			'type'        => 'url',
			'description' => __( 'Leave blank for v1 — Apple Pay is not offered yet. Add a URL later to show a button at checkout.', 'big-easy-bodega' ),
		),
		'beb_payment_note'     => array(
			'default'     => __( 'Pay the order total with Venmo (@heymarii), PayPal, or Cash App ($heymarii). Include your apartment number in the payment note. We will mark your order as processing once payment is confirmed, and your door code will appear on the order confirmation page.', 'big-easy-bodega' ),
			'label'       => __( 'Checkout payment note', 'big-easy-bodega' ),
			'type'        => 'textarea',
		),
		'beb_hero_cta_label'   => array(
			'default'     => __( 'Start shopping', 'big-easy-bodega' ),
			'label'       => __( 'Hero CTA label', 'big-easy-bodega' ),
			'type'        => 'text',
		),
		'beb_hero_tagline'     => array(
			'default'     => __( 'Your neighborhood bodega for 10X residents — stocked shelves, quick checkout, self-serve pickup.', 'big-easy-bodega' ),
			'label'       => __( 'Hero supporting line', 'big-easy-bodega' ),
			'type'        => 'textarea',
		),
	);

	foreach ( $settings as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args['default'],
				'sanitize_callback' => 'beb_sanitize_customizer_value',
				'transport'         => 'refresh',
			)
		);

		$control_type = $args['type'];
		if ( 'textarea' === $control_type ) {
			$wp_customize->add_control(
				$id,
				array(
					'label'       => $args['label'],
					'description' => $args['description'] ?? '',
					'section'     => 'beb_bodega',
					'type'        => 'textarea',
				)
			);
		} else {
			$wp_customize->add_control(
				$id,
				array(
					'label'       => $args['label'],
					'description' => $args['description'] ?? '',
					'section'     => 'beb_bodega',
					'type'        => $control_type,
				)
			);
		}
	}
}
add_action( 'customize_register', 'beb_customize_register' );

/**
 * Sanitize Customizer values by setting id.
 *
 * @param mixed                $value   Raw value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string
 */
function beb_sanitize_customizer_value( $value, WP_Customize_Setting $setting ): string {
	$value = is_string( $value ) ? $value : '';
	$id    = $setting->id;

	if ( str_contains( $id, '_url' ) || str_ends_with( $id, '_url' ) ) {
		return esc_url_raw( $value );
	}
	if ( str_contains( $id, 'email' ) ) {
		return sanitize_email( $value );
	}
	if ( in_array( $id, array( 'beb_location_text', 'beb_pickup_instructions', 'beb_payment_note', 'beb_hero_tagline' ), true ) ) {
		return sanitize_textarea_field( $value );
	}
	return sanitize_text_field( $value );
}

/**
 * Helper to read a theme mod with default.
 *
 * @param string $key     Setting key.
 * @param string $default Default.
 * @return string
 */
function beb_get_option( string $key, string $default = '' ): string {
	$value = get_theme_mod( $key, $default );
	return is_string( $value ) ? $value : $default;
}
