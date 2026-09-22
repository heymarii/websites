<?php
/**
 * WP-CLI eval-file seed for sample products.
 *
 * Usage from WordPress root:
 *   wp eval-file path/to/big-easy-bodega/sample-data/seed-products.php
 *
 * @package Big_Easy_Bodega
 */

if ( ! defined( 'ABSPATH' ) ) {
	fwrite( STDERR, "Run via: wp eval-file seed-products.php\n" );
	exit( 1 );
}

if ( ! class_exists( 'WooCommerce' ) ) {
	WP_CLI::error( 'WooCommerce must be active.' );
}

$json_path = __DIR__ . '/products.json';
$data      = json_decode( (string) file_get_contents( $json_path ), true );
if ( ! is_array( $data ) ) {
	WP_CLI::error( 'Could not read products.json' );
}

$categories = array(
	'pantry'          => 'Pantry',
	'drinks'          => 'Drinks',
	'paper-products'  => 'Paper products',
	'misc'            => 'Misc',
);

foreach ( $categories as $slug => $label ) {
	$term = term_exists( $slug, 'product_cat' );
	if ( ! $term ) {
		$term = wp_insert_term( $label, 'product_cat', array( 'slug' => $slug ) );
		WP_CLI::log( "Created category: $label" );
	}
}

foreach ( $data as $row ) {
	$sku = $row['sku'];
	$existing = wc_get_product_id_by_sku( $sku );
	if ( $existing ) {
		WP_CLI::log( "Skip existing SKU $sku" );
		continue;
	}

	$product = new WC_Product_Simple();
	$product->set_name( $row['name'] );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_short_description( $row['short_description'] );
	$product->set_description( $row['short_description'] );
	$product->set_sku( $sku );
	$product->set_regular_price( $row['regular_price'] );
	if ( ! empty( $row['sale_price'] ) ) {
		$product->set_sale_price( $row['sale_price'] );
	}
	$product->set_manage_stock( true );
	$product->set_stock_quantity( (int) $row['stock'] );
	$product->set_stock_status( 'instock' );
	$product->set_sold_individually( false );

	$term = get_term_by( 'slug', $row['category'], 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		$product->set_category_ids( array( (int) $term->term_id ) );
	}

	$id = $product->save();
	WP_CLI::success( "Created {$row['name']} (#{$id})" );
}

WP_CLI::success( 'Sample catalog ready. Add photos under Products → each product → Product image.' );
