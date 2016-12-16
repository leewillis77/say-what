<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings class. Possibly overkill at the moment
 */
class SayWhatSettings {

	public $base_file = '';
	public $replacements = array();

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
}
