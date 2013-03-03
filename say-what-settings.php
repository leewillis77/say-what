<?php

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

class say_what_settings {

	public $replacements;

	function __construct() {

		global $wpdb, $table_prefix;

		// @TODO - Read other settings in
		$sql = "SELECT * FROM {$table_prefix}say_what_strings";

		$this->replacements = $wpdb->get_results ( $sql, ARRAY_A );

	}

}
