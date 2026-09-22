<?php
/**
 * Front page template.
 *
 * @package Big_Easy_Bodega
 */

get_header();
?>
<main id="primary" class="site-main front-page">
	<?php get_template_part( 'template-parts/hero' ); ?>

	<section class="section section--carousel" aria-labelledby="newly-heading">
		<div class="container">
			<header class="section__header">
				<h2 id="newly-heading" class="section__title"><?php esc_html_e( 'Newly added', 'big-easy-bodega' ); ?></h2>
				<p class="section__lede"><?php esc_html_e( 'Fresh stock on the shelves this week.', 'big-easy-bodega' ); ?></p>
			</header>
			<?php get_template_part( 'template-parts/product-carousel', null, array( 'type' => 'newly' ) ); ?>
		</div>
	</section>

	<section class="section section--carousel section--alt" aria-labelledby="sale-heading">
		<div class="container">
			<header class="section__header">
				<h2 id="sale-heading" class="section__title"><?php esc_html_e( 'On sale', 'big-easy-bodega' ); ?></h2>
				<p class="section__lede"><?php esc_html_e( 'Neighborhood deals while they last.', 'big-easy-bodega' ); ?></p>
			</header>
			<?php get_template_part( 'template-parts/product-carousel', null, array( 'type' => 'on_sale' ) ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/location' ); ?>
	<?php get_template_part( 'template-parts/request-form' ); ?>
</main>
<?php
get_footer();
