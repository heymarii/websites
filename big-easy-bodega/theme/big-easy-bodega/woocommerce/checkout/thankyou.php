<?php
/**
 * Thank you / order received.
 *
 * @see woocommerce/templates/checkout/thankyou.php
 * @package Big_Easy_Bodega
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-order beb-thankyou">
	<?php if ( $order ) : ?>
		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<p class="woocommerce-notice woocommerce-notice--error"><?php esc_html_e( 'Unfortunately your order cannot be processed. Please try again.', 'big-easy-bodega' ); ?></p>
			<p class="woocommerce-notice">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn--primary"><?php esc_html_e( 'Pay', 'big-easy-bodega' ); ?></a>
			</p>
		<?php else : ?>
			<p class="woocommerce-notice woocommerce-notice--success beb-thankyou__hello">
				<?php esc_html_e( 'Thanks — your order is in. Pay with the links below if you have not already, then pick up with your door code once we confirm payment.', 'big-easy-bodega' ); ?>
			</p>
			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details beb-order-overview">
				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'big-easy-bodega' ); ?>
					<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
				</li>
				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'big-easy-bodega' ); ?>
					<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
				</li>
				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'big-easy-bodega' ); ?>
						<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
					</li>
				<?php endif; ?>
				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'big-easy-bodega' ); ?>
					<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
				</li>
				<?php if ( $order->get_meta( '_beb_apartment' ) ) : ?>
					<li class="woocommerce-order-overview__apartment">
						<?php esc_html_e( 'Apartment:', 'big-easy-bodega' ); ?>
						<strong><?php echo esc_html( (string) $order->get_meta( '_beb_apartment' ) ); ?></strong>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>
		<p class="woocommerce-notice woocommerce-notice--success"><?php esc_html_e( 'Thank you. Your order has been received.', 'big-easy-bodega' ); ?></p>
	<?php endif; ?>
</div>
