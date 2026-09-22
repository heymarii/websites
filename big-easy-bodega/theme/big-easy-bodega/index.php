<?php
/**
 * Main index template.
 *
 * @package Big_Easy_Bodega
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container content-flow">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article <?php post_class( 'entry' ); ?>>
					<header class="entry__header">
						<h1 class="entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<div class="entry__content">
						<?php the_excerpt(); ?>
					</div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'big-easy-bodega' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
