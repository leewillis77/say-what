<?php

namespace Ademti\SayWhat;

use function array_walk;
use function get_option;
use function is_array;
use function wp_cache_delete_multiple;
use function wp_cache_get;
use function wp_cache_set;
use function wp_using_ext_object_cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings class.
 */
class Settings {

	/**
	 * @var string
	 */
	public string $base_file = '';

	/**
	 * @var array
	 */
	public ?array $replacements = null;

	/**
	 * @var array
	 */
	private $flattened_replacements;

	/**
	 * Constructor.
	 *
	 * Loads the settings from the database iff we have completed installation.
	 */
	public function __construct( string $base_file ) {
		$this->base_file    = $base_file;
		$current_db_version = get_option( 'say_what_db_version' );
		if ( false === $current_db_version ) {
			$this->replacements           = [];
			$this->flattened_replacements = [];

			return;
		}
		$this->load_replacements();
	}

	/**
	 * Get a flattened array of the currently configured replacements.
	 *
	 * @return array
	 */
	public function get_flattened_replacements(): array {
		if ( isset( $this->flattened_replacements ) ) {
			return $this->flattened_replacements;
		}
		// Try and retrieve from the cache.
		if ( wp_using_ext_object_cache() ) {
			$flattened_replacements = wp_cache_get( 'say_what_flattened_replacements', 'swp' );
			if ( is_array( $flattened_replacements ) ) {
				$this->flattened_replacements = $flattened_replacements;
				return $this->flattened_replacements;
			}
		}
		// Otherwise, generate them...
		$this->flattened_replacements = [];
		array_walk(
			$this->replacements,
			function ( $replacement ) {
				$key                                  = $replacement['domain'] . '|' .
														$replacement['orig_string'] . '|' .
														$replacement['context'];
				$this->flattened_replacements[ $key ] = $replacement['replacement_string'];
			}
		);
		// ...and cache them.
		if ( wp_using_ext_object_cache() ) {
			wp_cache_set( 'say_what_flattened_replacements', $this->flattened_replacements, 'swp', 3600 );
		}

		return $this->flattened_replacements;
	}

	/**
	 * @return void
	 */
	public function invalidate_caches(): void {
		wp_cache_delete_multiple( [ 'say_what_strings', 'say_what_flattened_replacements' ], 'swp' );
	}

	/**
	 * Load the replacements using cache if possible.
	 * @return void
	 */
	private function load_replacements(): void {
		// Try and load them from the cache.
		if ( wp_using_ext_object_cache() ) {
			$this->load_replacements_from_cache();
		}
		if ( ! isset( $this->replacements ) ) {
			// We haven't loaded from the cache. Load from the DB.
			$this->load_replacements_from_database();
		}
	}

	/**
	 * Try to load the replacements from the cache.
	 *
	 * @return void
	 */
	private function load_replacements_from_cache(): void {
		$replacements = wp_cache_get( 'say_what_strings', 'swp' );
		if ( is_array( $replacements ) ) {
			$this->replacements = $replacements;
		}
	}

	/**
	 * Load the replacements from the database, and cache if ext. object cache in use.
	 *
	 * @return void
	 */
	private function load_replacements_from_database(): void {
		global $wpdb, $table_prefix;

		$table_name         = "{$table_prefix}say_what_strings";
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$this->replacements = $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM %i", [ $table_name ] ),
			ARRAY_A
		);
		// Cache them if we're using an external object cache.
		if ( wp_using_ext_object_cache() ) {
			wp_cache_set( 'say_what_strings', $this->replacements, 'swp', 3600 );
		}
	}
}
