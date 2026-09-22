<?php
/**
 * Checkout fields: full name, phone, apartment number.
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required resident contact fields on checkout.
 */
final class BEB_Checkout_Fields {

	/**
	 * Hooks.
	 */
	public static function init(): void {
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'customize_fields' ) );
		add_action( 'woocommerce_checkout_process', array( __CLASS__, 'validate' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( __CLASS__, 'save_meta' ) );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( __CLASS__, 'admin_display' ) );
		add_filter( 'woocommerce_email_order_meta_fields', array( __CLASS__, 'email_meta' ), 10, 3 );
	}

	/**
	 * Shape billing fields for a bodega pickup flow (no shipping address needed).
	 *
	 * @param array<string,mixed> $fields Checkout fields.
	 * @return array<string,mixed>
	 */
	public static function customize_fields( array $fields ): array {
		if ( ! isset( $fields['billing'] ) || ! is_array( $fields['billing'] ) ) {
			return $fields;
		}

		$fields['billing']['billing_first_name']['label']    = __( 'Full name', 'beb-bodega-core' );
		$fields['billing']['billing_first_name']['required'] = true;
		$fields['billing']['billing_first_name']['class']    = array( 'form-row-wide' );
		$fields['billing']['billing_first_name']['priority'] = 10;

		// Hide last name; keep optional technical presence for WC.
		$fields['billing']['billing_last_name']['required'] = false;
		$fields['billing']['billing_last_name']['class']    = array( 'form-row-wide', 'beb-visually-hidden' );
		$fields['billing']['billing_last_name']['default']  = '.';

		$fields['billing']['billing_phone']['label']    = __( 'Phone', 'beb-bodega-core' );
		$fields['billing']['billing_phone']['required'] = true;
		$fields['billing']['billing_phone']['priority'] = 20;
		$fields['billing']['billing_phone']['class']    = array( 'form-row-wide' );

		$fields['billing']['billing_email']['required'] = false;
		$fields['billing']['billing_email']['priority'] = 30;

		$fields['billing']['beb_apartment'] = array(
			'type'        => 'text',
			'label'       => __( 'Apartment number', 'beb-bodega-core' ),
			'placeholder' => '26102',
			'required'    => true,
			'class'       => array( 'form-row-wide' ),
			'priority'    => 25,
			'clear'       => true,
		);

		// Soften address requirements for on-site pickup.
		foreach ( array( 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country' ) as $key ) {
			if ( isset( $fields['billing'][ $key ] ) ) {
				$fields['billing'][ $key ]['required'] = false;
			}
		}

		if ( isset( $fields['billing']['billing_country'] ) ) {
			$fields['billing']['billing_country']['default'] = 'US';
		}
		if ( isset( $fields['billing']['billing_state'] ) ) {
			$fields['billing']['billing_state']['default'] = 'TX';
		}

		return $fields;
	}

	/**
	 * Extra validation.
	 */
	public static function validate(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WC checkout nonce verified upstream.
		$apartment = isset( $_POST['beb_apartment'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['beb_apartment'] ) ) : '';
		if ( '' === $apartment ) {
			wc_add_notice( __( 'Please enter your apartment number.', 'beb-bodega-core' ), 'error' );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['billing_phone'] ) ) : '';
		if ( '' === $phone ) {
			wc_add_notice( __( 'Please enter your phone number.', 'beb-bodega-core' ), 'error' );
		}

		// Ensure last name placeholder so WC does not complain if shown.
		if ( empty( $_POST['billing_last_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$_POST['billing_last_name'] = '.';
		}
	}

	/**
	 * Persist apartment meta.
	 *
	 * @param int $order_id Order ID.
	 */
	public static function save_meta( int $order_id ): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['beb_apartment'] ) ) {
			return;
		}
		$apartment = sanitize_text_field( wp_unslash( (string) $_POST['beb_apartment'] ) );
		$order     = wc_get_order( $order_id );
		if ( $order ) {
			$order->update_meta_data( '_beb_apartment', $apartment );
			$order->save();
		} else {
			update_post_meta( $order_id, '_beb_apartment', $apartment );
		}
	}

	/**
	 * Show apartment in admin order screen.
	 *
	 * @param WC_Order $order Order.
	 */
	public static function admin_display( WC_Order $order ): void {
		$apartment = $order->get_meta( '_beb_apartment' );
		if ( '' === $apartment ) {
			return;
		}
		echo '<p><strong>' . esc_html__( 'Apartment', 'beb-bodega-core' ) . ':</strong> ' . esc_html( (string) $apartment ) . '</p>';
	}

	/**
	 * Include apartment in emails.
	 *
	 * @param array<string,array<string,string>> $fields Fields.
	 * @param bool                               $sent_to_admin Admin flag.
	 * @param WC_Order                           $order Order.
	 * @return array<string,array<string,string>>
	 */
	public static function email_meta( array $fields, bool $sent_to_admin, $order ): array {
		if ( ! $order instanceof WC_Order ) {
			return $fields;
		}
		$apartment = $order->get_meta( '_beb_apartment' );
		if ( '' !== $apartment ) {
			$fields['beb_apartment'] = array(
				'label' => __( 'Apartment', 'beb-bodega-core' ),
				'value' => (string) $apartment,
			);
		}
		return $fields;
	}
}
