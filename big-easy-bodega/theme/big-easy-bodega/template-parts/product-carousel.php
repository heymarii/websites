<?php
/**
 * Product carousel partial.
 *
 * @package Big_Easy_Bodega
 *
 * @var array{type?:string} $args
 */

$type     = isset( $args['type'] ) ? (string) $args['type'] : 'newly';
$products = beb_get_carousel_products( $type, 8 );

if ( empty( $products ) ) :
	?>
	<p class="carousel-empty">
		<?php
		if ( 'on_sale' === $type ) {
			esc_html_e( 'No sale items right now — check the full shop.', 'big-easy-bodega' );
		} else {
			esc_html_e( 'Products will appear here once stock is added in WooCommerce.', 'big-easy-bodega' );
		}
		?>
		<a href="<?php echo esc_url( beb_shop_url() ); ?>"><?php esc_html_e( 'Browse shop', 'big-easy-bodega' ); ?></a>
	</p>
	<?php
	return;
endif;
?>
<div class="product-carousel" data-carousel>
	<button type="button" class="product-carousel__nav product-carousel__nav--prev" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous products', 'big-easy-bodega' ); ?>">
		<span aria-hidden="true">&larr;</span>
	</button>
	<div class="product-carousel__track" data-carousel-track tabindex="0">
		<?php foreach ( $products as $product ) : ?>
			<?php
			if ( ! $product instanceof WC_Product ) {
				continue;
			}
			$permalink = $product->get_permalink();
			$image_id  = $product->get_image_id();
			$image     = $image_id
				? wp_get_attachment_image( $image_id, 'beb-product-card', false, array( 'class' => 'product-card__img' ) )
				: wc_placeholder_img( 'beb-product-card' );
			?>
			<article class="product-card product-card--carousel">
				<a class="product-card__media" href="<?php echo esc_url( $permalink ); ?>">
					<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
				<div class="product-card__body">
					<h3 class="product-card__title">
						<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
					</h3>
					<?php if ( $product->get_short_description() ) : ?>
						<p class="product-card__excerpt"><?php echo esc_html( wp_strip_all_tags( $product->get_short_description() ) ); ?></p>
					<?php endif; ?>
					<p class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_loop_add_to_cart_link',
						sprintf(
							'<a href="%s" data-quantity="1" class="btn btn--secondary add_to_cart_button ajax_add_to_cart" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
							esc_url( $product->add_to_cart_url() ),
							esc_attr( (string) $product->get_id() ),
							esc_attr( $product->get_sku() ),
							esc_attr( $product->add_to_cart_description() ),
							esc_html( $product->add_to_cart_text() )
						),
						$product
					);
					?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<button type="button" class="product-carousel__nav product-carousel__nav--next" data-carousel-next aria-label="<?php esc_attr_e( 'Next products', 'big-easy-bodega' ); ?>">
		<span aria-hidden="true">&rarr;</span>
	</button>
</div>
