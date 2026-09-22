<?php
/**
 * Admin settings: door code under WooCommerce menu.
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings page for door code.
 */
final class BEB_Admin_Settings {

	/**
	 * Hooks.
	 */
	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
		add_action( 'admin_head', array( __CLASS__, 'hide_last_name_css' ) );
	}

	/**
	 * Submenu under WooCommerce when available.
	 */
	public static function menu(): void {
		$parent = class_exists( 'WooCommerce' ) ? 'woocommerce' : 'options-general.php';
		add_submenu_page(
			$parent,
			__( 'Bodega Settings', 'beb-bodega-core' ),
			__( 'Bodega Settings', 'beb-bodega-core' ),
			'manage_options',
			'beb-bodega-settings',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Register option.
	 */
	public static function register(): void {
		register_setting(
			'beb_bodega_settings',
			BEB_Door_Code::OPTION_KEY,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);
	}

	/**
	 * Settings screen.
	 */
	public static function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Big Easy Bodega Settings', 'beb-bodega-core' ); ?></h1>
			<p><?php esc_html_e( 'The door code is shown on the order thank-you page when an order status is Processing or Completed (after you confirm payment).', 'beb-bodega-core' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'beb_bodega_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="beb_door_code"><?php esc_html_e( 'Pickup door code', 'beb-bodega-core' ); ?></label>
						</th>
						<td>
							<input
								name="<?php echo esc_attr( BEB_Door_Code::OPTION_KEY ); ?>"
								id="beb_door_code"
								type="text"
								class="regular-text"
								value="<?php echo esc_attr( BEB_Door_Code::get_code() ); ?>"
								autocomplete="off"
							/>
							<p class="description"><?php esc_html_e( 'Building 26 entry code for residents who have paid. Change it anytime; new thank-you pages use the latest value.', 'beb-bodega-core' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
			<hr />
			<h2><?php esc_html_e( 'Also configure', 'beb-bodega-core' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Appearance → Customize → Big Easy Bodega — Venmo (@heymarii), PayPal, Cash App ($heymarii); Apple Pay optional; maps embed; pickup copy.', 'beb-bodega-core' ); ?></li>
				<li><?php esc_html_e( 'WooCommerce → Settings → Payments — enable “Bodega payment links”.', 'beb-bodega-core' ); ?></li>
				<li><?php esc_html_e( 'WooCommerce → Settings → Tax — Texas sales tax (see SETUP.md).', 'beb-bodega-core' ); ?></li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Hide unused last-name field on checkout via admin? No — front CSS.
	 * Keep a tiny admin note style only.
	 */
	public static function hide_last_name_css(): void {
		// Intentionally empty; front CSS handled in theme for .beb-visually-hidden if needed.
	}
}
