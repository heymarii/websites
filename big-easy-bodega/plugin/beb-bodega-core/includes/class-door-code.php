<?php
/**
 * Door code option for confirmed orders.
 *
 * @package BEB_Bodega_Core
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stores and retrieves the building door code.
 */
final class BEB_Door_Code {

	public const OPTION_KEY = 'beb_door_code';

	/**
	 * Hooks.
	 */
	public static function init(): void {
		// Reserved for future hooks; settings UI lives in BEB_Admin_Settings.
	}

	/**
	 * Current door code (empty if unset).
	 *
	 * @return string
	 */
	public static function get_code(): string {
		$value = get_option( self::OPTION_KEY, '' );
		return is_string( $value ) ? $value : '';
	}

	/**
	 * Update door code.
	 *
	 * @param string $code Code.
	 */
	public static function set_code( string $code ): void {
		update_option( self::OPTION_KEY, sanitize_text_field( $code ), false );
	}
}
