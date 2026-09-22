<?php
/**
 * Home hero — full-bleed yellow brand banner + CTA.
 *
 * @package Big_Easy_Bodega
 */

$cta_label = beb_get_option( 'beb_hero_cta_label', __( 'Start shopping', 'big-easy-bodega' ) );
$tagline   = beb_get_option(
	'beb_hero_tagline',
	__( 'Your neighborhood bodega for 10X residents — stocked shelves, quick checkout, self-serve pickup.', 'big-easy-bodega' )
);
$png       = BEB_THEME_DIR . '/assets/images/logo-banner-yellow.png';
$logo      = file_exists( $png )
	? beb_asset( 'images/logo-banner-yellow.png' )
	: beb_asset( 'images/logo-banner-yellow.svg' );
$shop_url  = beb_shop_url();
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Big Easy Bodega', 'big-easy-bodega' ); ?>">
	<div class="hero__plane">
		<img
			class="hero__logo"
			src="<?php echo esc_url( $logo ); ?>"
			alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			width="900"
			height="280"
			decoding="async"
			fetchpriority="high"
		/>
	</div>
	<div class="hero__content container">
		<p class="hero__tagline"><?php echo esc_html( $tagline ); ?></p>
		<div class="hero__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
		</div>
	</div>
</section>
