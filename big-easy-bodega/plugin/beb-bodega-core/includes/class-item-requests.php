<?php
/**
 * Item request CPT + AJAX handler.
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stores resident item requests as admin-visible posts.
 */
final class BEB_Item_Requests {

	public const POST_TYPE = 'beb_item_request';

	/**
	 * Hook registrations.
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'wp_ajax_beb_submit_item_request', array( __CLASS__, 'handle_submit' ) );
		add_action( 'wp_ajax_nopriv_beb_submit_item_request', array( __CLASS__, 'handle_submit' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'column_content' ), 10, 2 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'meta_box' ) );
	}

	/**
	 * Register CPT.
	 */
	public static function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => array(
					'name'          => __( 'Item Requests', 'beb-bodega-core' ),
					'singular_name' => __( 'Item Request', 'beb-bodega-core' ),
					'add_new_item'  => __( 'Add Item Request', 'beb-bodega-core' ),
					'edit_item'     => __( 'View Item Request', 'beb-bodega-core' ),
					'search_items'  => __( 'Search Requests', 'beb-bodega-core' ),
					'not_found'     => __( 'No requests yet.', 'beb-bodega-core' ),
					'menu_name'     => __( 'Item Requests', 'beb-bodega-core' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => 'dashicons-clipboard',
				'menu_position'       => 56,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'supports'            => array( 'title' ),
				'has_archive'         => false,
				'exclude_from_search' => true,
			)
		);
	}

	/**
	 * AJAX submit from front-end form.
	 */
	public static function handle_submit(): void {
		if ( ! check_ajax_referer( 'beb_item_request', 'beb_request_nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed. Refresh and try again.', 'beb-bodega-core' ) ), 403 );
		}

		$name       = isset( $_POST['beb_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['beb_name'] ) ) : '';
		$apartment  = isset( $_POST['beb_apartment'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['beb_apartment'] ) ) : '';
		$phone      = isset( $_POST['beb_phone'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['beb_phone'] ) ) : '';
		$item       = isset( $_POST['beb_item'] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST['beb_item'] ) ) : '';

		if ( '' === $name || '' === $apartment || '' === $phone || '' === $item ) {
			wp_send_json_error( array( 'message' => __( 'Please fill in all fields.', 'beb-bodega-core' ) ), 400 );
		}

		$title = sprintf(
			/* translators: 1: item snippet, 2: apartment */
			__( '%1$s (Apt %2$s)', 'beb-bodega-core' ),
			wp_trim_words( $item, 8, '…' ),
			$apartment
		);

		$post_id = wp_insert_post(
			array(
				'post_type'   => self::POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => $title,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Could not save request. Try again later.', 'beb-bodega-core' ) ), 500 );
		}

		update_post_meta( $post_id, '_beb_name', $name );
		update_post_meta( $post_id, '_beb_apartment', $apartment );
		update_post_meta( $post_id, '_beb_phone', $phone );
		update_post_meta( $post_id, '_beb_item', $item );

		wp_send_json_success(
			array(
				'message' => __( 'Thanks — we got your request and will try to stock it.', 'beb-bodega-core' ),
			)
		);
	}

	/**
	 * Admin list columns.
	 *
	 * @param array<string,string> $columns Columns.
	 * @return array<string,string>
	 */
	public static function columns( array $columns ): array {
		return array(
			'cb'            => $columns['cb'] ?? '',
			'title'         => __( 'Summary', 'beb-bodega-core' ),
			'beb_name'      => __( 'Name', 'beb-bodega-core' ),
			'beb_apartment' => __( 'Apartment', 'beb-bodega-core' ),
			'beb_phone'     => __( 'Phone', 'beb-bodega-core' ),
			'beb_item'      => __( 'Item', 'beb-bodega-core' ),
			'date'          => __( 'Date', 'beb-bodega-core' ),
		);
	}

	/**
	 * Admin column values.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public static function column_content( string $column, int $post_id ): void {
		$map = array(
			'beb_name'      => '_beb_name',
			'beb_apartment' => '_beb_apartment',
			'beb_phone'     => '_beb_phone',
			'beb_item'      => '_beb_item',
		);
		if ( ! isset( $map[ $column ] ) ) {
			return;
		}
		$value = (string) get_post_meta( $post_id, $map[ $column ], true );
		echo esc_html( $value );
	}

	/**
	 * Detail meta box on edit screen.
	 */
	public static function meta_box(): void {
		add_meta_box(
			'beb_item_request_details',
			__( 'Request details', 'beb-bodega-core' ),
			array( __CLASS__, 'render_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * @param WP_Post $post Post.
	 */
	public static function render_meta_box( WP_Post $post ): void {
		$fields = array(
			'_beb_name'      => __( 'Name', 'beb-bodega-core' ),
			'_beb_apartment' => __( 'Apartment', 'beb-bodega-core' ),
			'_beb_phone'     => __( 'Phone', 'beb-bodega-core' ),
			'_beb_item'      => __( 'Item request', 'beb-bodega-core' ),
		);
		echo '<table class="form-table"><tbody>';
		foreach ( $fields as $key => $label ) {
			$value = (string) get_post_meta( $post->ID, $key, true );
			printf(
				'<tr><th>%1$s</th><td>%2$s</td></tr>',
				esc_html( $label ),
				esc_html( $value )
			);
		}
		echo '</tbody></table>';
	}
}
