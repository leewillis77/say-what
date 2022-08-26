<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings class. Possibly overkill at the moment
 */
class SayWhatSettings {

	/**
	 * @var string
	 */
	public $base_file = '';

	/**
	 * @var array
	 */
	public $replacements = null;

	/**
	 * @var array
	 */
	private $flattened_replacements = null;

	/**
	 * Constructor.
	 *
	 * Loads the settings from the database.
	 */
	public function __construct( $base_file ) {
		$this->base_file    = $base_file;
		$current_db_version = get_option( 'say_what_db_version' );
		if ( false === $current_db_version ) {
			return;
		}
		$this->load_replacements();
	}

	/**
	 * Get a flattened array of the currently configured replacements.
	 *
	 * @return array
	 */
	public function get_flattened_replacements() {
		if ( null !== $this->flattened_replacements ) {
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
		if ( empty( $this->replacements ) ) {
			$this->flattened_replacements = [];
		}
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
	public function invalidate_caches() {
		wp_cache_delete_multiple( [ 'say_what_strings', 'say_what_flattened_replacements' ], 'swp' );
	}

	/**
	 * Load the replacements using cache if possible.
	 * @return void
	 */
	private function load_replacements() {
		// Try and load them from the cache.
		if ( wp_using_ext_object_cache() ) {
			$this->load_replacements_from_cache();
		}
		if ( null === $this->replacements ) {
			// We haven't loaded from the cache. Load from the DB.
			$this->load_replacements_from_database();
		}
	}

	/**
	 * Try to load the replacements from the cache.
	 *
	 * @return void
	 */
	private function load_replacements_from_cache() {
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
	private function load_replacements_from_database() {
		global $wpdb, $table_prefix;

		$sql = "SELECT * FROM {$table_prefix}say_what_strings";
		$this->replacements = $wpdb->get_results( $sql, ARRAY_A );
		// Cache them if we're using an external object cache.
		if ( wp_using_ext_object_cache() ) {
			wp_cache_set( 'say_what_strings', $this->replacements, 'swp', 3600 );
		}
	}
}
