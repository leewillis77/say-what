<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings class. Possibly overkill at the moment
 */
class SayWhatSettings {

	public $base_file = '';

	/**
	 * @var array
	 */
	public $replacements = array();

	/**
	 * @var array
	 */
	private $flattened_replacements = [];

	/**
	 * Constructor.
	 *
	 * Loads the settings from the database.
	 */
	public function __construct( $base_file ) {
		global $wpdb, $table_prefix;
		$this->base_file    = $base_file;
		$current_db_version = get_option( 'say_what_db_version' );
		if ( false === $current_db_version ) {
			return;
		}
		$sql = "SELECT * FROM {$table_prefix}say_what_strings";
		$this->replacements = $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * Get a flattened array of the currently configured replacements.
	 *
	 * @return array
	 */
	public function get_flattened_replacements() {
		if ( [] !== $this->flattened_replacements ) {
			return $this->flattened_replacements;
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

		return $this->flattened_replacements;
	}
}
