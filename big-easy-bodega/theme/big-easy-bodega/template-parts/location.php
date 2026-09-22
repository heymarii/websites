<?php
/**
 * Location + maps embed section.
 *
 * @package Big_Easy_Bodega
 */

$title = beb_get_option( 'beb_location_title', __( 'Find us at 10X Apartments', 'big-easy-bodega' ) );
$text  = beb_get_option(
	'beb_location_text',
	__( 'We are inside 10X Apartments, Building 26, next to apartment 26102. Browse live stock, check out online, then pick up with your door code.', 'big-easy-bodega' )
);
$map   = beb_get_option( 'beb_maps_embed_url', '' );
?>
<section class="section section--location" aria-labelledby="location-heading">
	<div class="container location-grid">
		<div class="location-grid__copy">
			<h2 id="location-heading" class="section__title"><?php echo esc_html( $title ); ?></h2>
			<p class="section__lede"><?php echo esc_html( $text ); ?></p>
			<ul class="location-facts">
				<li><?php esc_html_e( '10X Apartments', 'big-easy-bodega' ); ?></li>
				<li><?php esc_html_e( 'Building 26', 'big-easy-bodega' ); ?></li>
				<li><?php esc_html_e( 'Next to apartment 26102', 'big-easy-bodega' ); ?></li>
			</ul>
		</div>
		<div class="location-grid__map">
			<?php if ( $map ) : ?>
				<iframe
					class="location-map"
					src="<?php echo esc_url( $map ); ?>"
					title="<?php esc_attr_e( 'Map to Big Easy Bodega', 'big-easy-bodega' ); ?>"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					allowfullscreen
				></iframe>
			<?php else : ?>
				<div class="location-map location-map--placeholder" role="img" aria-label="<?php esc_attr_e( 'Map placeholder — add embed URL in Customizer', 'big-easy-bodega' ); ?>">
					<p><?php esc_html_e( 'Google Maps embed goes here. Set the embed URL under Appearance → Customize → Big Easy Bodega.', 'big-easy-bodega' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
