<?php
/**
 * Product loop card.
 *
 * @package Big_Easy_Bodega
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'product-card', $product ); ?>>
	<a class="product-card__media" href="<?php echo esc_url( get_permalink() ); ?>">
		<?php woocommerce_show_product_loop_sale_flash(); ?>
		<?php echo $product->get_image( 'beb-product-card', array( 'class' => 'product-card__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
	<div class="product-card__body">
		<h2 class="woocommerce-loop-product__title product-card__title">
			<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
		</h2>
		<?php
		beb_loop_short_description();
		woocommerce_template_loop_price();
		woocommerce_template_loop_add_to_cart();
		?>
	</div>
</li>
