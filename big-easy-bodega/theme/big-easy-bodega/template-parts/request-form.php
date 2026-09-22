<?php
/**
 * Request-an-item form (submissions handled by beb-bodega-core plugin).
 *
 * @package Big_Easy_Bodega
 */
?>
<section class="section section--request section--alt" aria-labelledby="request-heading" id="request-item">
	<div class="container request-layout">
		<header class="section__header">
			<h2 id="request-heading" class="section__title"><?php esc_html_e( 'Request an item', 'big-easy-bodega' ); ?></h2>
			<p class="section__lede"><?php esc_html_e( 'Tell us what you need — name, apartment, phone, and the item. We will try to stock it.', 'big-easy-bodega' ); ?></p>
		</header>
		<form class="request-form" data-request-form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
			<input type="hidden" name="action" value="beb_submit_item_request" />
			<?php wp_nonce_field( 'beb_item_request', 'beb_request_nonce' ); ?>
			<div class="request-form__row">
				<label for="beb-req-name"><?php esc_html_e( 'Name', 'big-easy-bodega' ); ?></label>
				<input id="beb-req-name" name="beb_name" type="text" required autocomplete="name" />
			</div>
			<div class="request-form__row">
				<label for="beb-req-apt"><?php esc_html_e( 'Apartment number', 'big-easy-bodega' ); ?></label>
				<input id="beb-req-apt" name="beb_apartment" type="text" required autocomplete="off" placeholder="26102" />
			</div>
			<div class="request-form__row">
				<label for="beb-req-phone"><?php esc_html_e( 'Phone', 'big-easy-bodega' ); ?></label>
				<input id="beb-req-phone" name="beb_phone" type="tel" required autocomplete="tel" />
			</div>
			<div class="request-form__row">
				<label for="beb-req-item"><?php esc_html_e( 'Item request', 'big-easy-bodega' ); ?></label>
				<textarea id="beb-req-item" name="beb_item" rows="3" required placeholder="<?php esc_attr_e( 'e.g. oat milk, paper towels, sparkling water', 'big-easy-bodega' ); ?>"></textarea>
			</div>
			<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Send request', 'big-easy-bodega' ); ?></button>
			<p class="request-form__status" data-request-status role="status" aria-live="polite" hidden></p>
		</form>
	</div>
</section>
