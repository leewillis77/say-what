<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings class. Possibly overkill at the moment
 */
class SayWhatSettings {

	public $replacements;

	/**
	 * Constructor.
	 *
	 * Loads the settings from the database.
	 */
	public function __construct() {
		global $wpdb, $table_prefix;
		// @TODO - Read other settings in when we have them
		$sql = "SELECT * FROM {$table_prefix}say_what_strings";
		$this->replacements = $wpdb->get_results( $sql, ARRAY_A );
	}
}
