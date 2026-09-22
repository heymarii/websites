<?php
/**
 * Search results.
 *
 * @package Big_Easy_Bodega
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container content-flow">
		<header class="entry__header">
			<h1 class="entry__title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search: %s', 'big-easy-bodega' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article <?php post_class( 'entry' ); ?>>
					<h2 class="entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No results. Try another search or browse the shop.', 'big-easy-bodega' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
