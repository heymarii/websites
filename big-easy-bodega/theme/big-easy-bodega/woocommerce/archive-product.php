<?php
/**
 * Product archive / Shop.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Big_Easy_Bodega
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content
 */
do_action( 'woocommerce_before_main_content' );
?>
<header class="shop-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="shop-header__title woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>
	<p class="shop-header__lede"><?php esc_html_e( 'Pantry, drinks, paper products, and misc — live stock for 10X residents.', 'big-easy-bodega' ); ?></p>
	<?php
	$cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
			'exclude'    => array_filter( array( (int) get_option( 'default_product_cat' ) ) ),
		)
	);
	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) :
		?>
		<ul class="shop-cats">
			<?php foreach ( $cats as $cat ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php do_action( 'woocommerce_archive_description' ); ?>
</header>
<?php
if ( woocommerce_product_loop() ) {
	do_action( 'woocommerce_before_shop_loop' );
	woocommerce_product_loop_start();
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );
		}
	}
	woocommerce_product_loop_end();
	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
