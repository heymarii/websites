<?php
/**
 * Header template.
 *
 * @package Big_Easy_Bodega
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'big-easy-bodega' ); ?></a>
<header class="site-header" role="banner">
	<div class="site-header__inner container">
		<?php beb_the_header_logo(); ?>
		<button type="button" class="nav-toggle" aria-expanded="false" aria-controls="site-navigation" data-nav-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'big-easy-bodega' ); ?></span>
			<span class="nav-toggle__bars" aria-hidden="true"></span>
		</button>
		<nav id="site-navigation" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'big-easy-bodega' ); ?>" data-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'primary-nav__list',
					'container'      => false,
					'fallback_cb'    => 'beb_fallback_menu',
				)
			);
			?>
			<?php if ( function_exists( 'WC' ) ) : ?>
				<a class="header-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<span class="header-cart__label"><?php esc_html_e( 'Cart', 'big-easy-bodega' ); ?></span>
					<span class="header-cart__count"><?php echo esc_html( (string) WC()->cart->get_cart_contents_count() ); ?></span>
				</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
