<?php
/**
 * 404 template.
 *
 * @package Big_Easy_Bodega
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container content-flow">
		<h1><?php esc_html_e( 'Page not found', 'big-easy-bodega' ); ?></h1>
		<p><?php esc_html_e( 'That shelf is empty. Head back to the shop.', 'big-easy-bodega' ); ?></p>
		<p><a class="btn btn--primary" href="<?php echo esc_url( beb_shop_url() ); ?>"><?php esc_html_e( 'Start shopping', 'big-easy-bodega' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
