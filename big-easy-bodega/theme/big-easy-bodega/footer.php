<?php
/**
 * Footer template.
 *
 * @package Big_Easy_Bodega
 */
?>
<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner container">
		<div class="site-footer__brand">
			<img src="<?php echo esc_url( beb_asset( 'images/logo-mark.svg' ) ); ?>" alt="" width="40" height="40" />
			<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
			<p class="site-footer__place"><?php esc_html_e( '10X Apartments · Building 26 · next to apt 26102', 'big-easy-bodega' ); ?></p>
		</div>
		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer', 'big-easy-bodega' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-nav__list',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<p class="site-footer__copy">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<?php bloginfo( 'name' ); ?>
			· <?php esc_html_e( 'Residents of 10X welcome.', 'big-easy-bodega' ); ?>
		</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
