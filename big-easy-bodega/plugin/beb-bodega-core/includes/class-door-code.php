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
 *
 * Single source of truth: option `beb_door_code`, default {@see BEB_Door_Code::DEFAULT_CODE}.
 */
final class BEB_Door_Code {

	public const OPTION_KEY   = 'beb_door_code';
	public const DEFAULT_CODE = '12345';

	/**
	 * Hooks.
	 */
	public static function init(): void {
		// Settings UI lives in BEB_Admin_Settings.
	}

	/**
	 * Ensure the option exists with the default on first install.
	 */
	public static function maybe_seed_default(): void {
		if ( false === get_option( self::OPTION_KEY, false ) ) {
			add_option( self::OPTION_KEY, self::DEFAULT_CODE, '', false );
		}
	}

	/**
	 * Current door code (falls back to DEFAULT_CODE when unset).
	 *
	 * @return string
	 */
	public static function get_code(): string {
		$value = get_option( self::OPTION_KEY, self::DEFAULT_CODE );
		if ( ! is_string( $value ) || '' === $value ) {
			return self::DEFAULT_CODE;
		}
		return $value;
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
