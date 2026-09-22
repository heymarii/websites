<?php
/**
 * Offline payment-instructions gateway (Venmo / PayPal / Cash App links).
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register gateway with WooCommerce once WC_Payment_Gateway exists.
 */
final class BEB_Payment_Gateway {

	/**
	 * @param string[] $gateways Gateway class names.
	 * @return string[]
	 */
	public static function register( array $gateways ): array {
		if ( ! class_exists( 'BEB_Gateway_Payment_Links', false ) ) {
			self::load_gateway_class();
		}
		$gateways[] = 'BEB_Gateway_Payment_Links';
		return $gateways;
	}

	/**
	 * Define gateway class extending WC_Payment_Gateway.
	 */
	public static function load_gateway_class(): void {
		if ( ! class_exists( 'WC_Payment_Gateway' ) || class_exists( 'BEB_Gateway_Payment_Links', false ) ) {
			return;
		}

		/**
		 * Payment links gateway — places order on-hold until owner confirms payment.
		 */
		class BEB_Gateway_Payment_Links extends WC_Payment_Gateway {

			/**
			 * Constructor.
			 */
			public function __construct() {
				$this->id                 = 'beb_payment_links';
				$this->method_title       = __( 'Bodega payment links', 'beb-bodega-core' );
				$this->method_description = __( 'Residents pay via Venmo (@heymarii), PayPal (paypal.me/heymarii), or Cash App ($heymarii) using the links shown at checkout. Mark the order Processing after you confirm payment so the door code appears. Apple Pay is optional and off by default for v1.', 'beb-bodega-core' );
				$this->has_fields         = false;
				$this->supports           = array( 'products' );

				$this->init_form_fields();
				$this->init_settings();

				$this->title       = $this->get_option( 'title', __( 'Pay with Venmo / PayPal / Cash App', 'beb-bodega-core' ) );
				$this->description = $this->get_option(
					'description',
					__( 'Place your order, then pay the total via Venmo (@heymarii), PayPal, or Cash App ($heymarii). Include your apartment number in the payment note.', 'beb-bodega-core' )
				);
				$this->enabled     = $this->get_option( 'enabled', 'yes' );

				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			/**
			 * Admin fields.
			 */
			public function init_form_fields(): void {
				$this->form_fields = array(
					'enabled'     => array(
						'title'   => __( 'Enable/Disable', 'beb-bodega-core' ),
						'type'    => 'checkbox',
						'label'   => __( 'Enable Bodega payment links', 'beb-bodega-core' ),
						'default' => 'yes',
					),
					'title'       => array(
						'title'       => __( 'Title', 'beb-bodega-core' ),
						'type'        => 'text',
						'description' => __( 'Shown to residents at checkout.', 'beb-bodega-core' ),
						'default'     => __( 'Pay with Venmo / PayPal / Cash App', 'beb-bodega-core' ),
						'desc_tip'    => true,
					),
					'description' => array(
						'title'       => __( 'Description', 'beb-bodega-core' ),
						'type'        => 'textarea',
						'description' => __( 'Payment instructions under the method title. Edit the actual app links under Appearance → Customize → Big Easy Bodega.', 'beb-bodega-core' ),
						'default'     => __( 'Place your order, then pay the total via Venmo (@heymarii), PayPal, or Cash App ($heymarii). Include your apartment number in the payment note.', 'beb-bodega-core' ),
					),
				);
			}

			/**
			 * Place order on-hold pending manual payment confirmation.
			 *
			 * @param int $order_id Order ID.
			 * @return array{result:string,redirect:string}
			 */
			public function process_payment( $order_id ): array {
				$order = wc_get_order( $order_id );
				if ( ! $order ) {
					wc_add_notice( __( 'Order could not be found.', 'beb-bodega-core' ), 'error' );
					return array(
						'result'   => 'failure',
						'redirect' => '',
					);
				}

				$order->update_status(
					'on-hold',
					__( 'Awaiting Venmo/PayPal/Cash App payment. Mark Processing after you confirm funds.', 'beb-bodega-core' )
				);

				if ( function_exists( 'wc_reduce_stock_levels' ) ) {
					wc_reduce_stock_levels( $order_id );
				}

				WC()->cart->empty_cart();

				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}
		}
	}
}

add_action(
	'plugins_loaded',
	static function (): void {
		BEB_Payment_Gateway::load_gateway_class();
	},
	20
);
