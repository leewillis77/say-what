<?php

/**
 * Provides WP-CLI features for interacting with the "Say what?" plugin.
 */
class SayWhatCli extends \WP_CLI\CommandWithDBObject {

	protected $obj_type = 'stdClass';
	protected $obj_fields = array(
		'string_id',
		'orig_string',
		'domain',
		'replacement_string',
		'context',
	);

	/**
	 * Export all current string replacements.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Accepted values: table, csv, json, count. Default: table
	 *
	 * ## EXAMPLES
	 *
	 * wp say-what export
	 */
	public function export( $args, $assoc_args ) {
		$formatter    = $this->get_formatter( $assoc_args );
		$replacements = $this->get_replacements();
		$formatter->display_items( $replacements );
	}

	/**
	 * Export all current string replacements. Synonym for 'export'.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Accepted values: table, csv, json, count. Default: table
	 *
	 * ## EXAMPLES
	 *
	 * wp say-what list
	 *
	 * @subcommand list
	 */
	public function _list( $args, $assoc_args ) {
		return $this->export( $args, $assoc_args );
	}

	/**
	 * Import string replacements from a CSV file.
	 *
	 * All items in the import sheet will be added as new items.
	 *
	 * ## OPTIONS
	 *
	 * None.
	 *
	 * ## EXAMPLES
	 *
	 * wp say-what import {file}
	 *
	 * @subcommand import
	 */
	public function _import( $args, $assoc_args ) {
		$filename = $args[0];
		foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $item ) {
			$this->insert_replacement( $item );
			error_log( __FUNCTION__ . ": item " . print_r( $item, 1 ) );
		}
	}

	protected function get_replacements() {
		global $wpdb, $table_prefix;
		$table = $table_prefix . 'say_what_strings';
		return $wpdb->get_results( "SELECT * FROM $table" );
	}

	protected function insert_replacement( $item ) {
		global $wpdb, $table_prefix;
		$sql = "INSERT INTO {$table_prefix}say_what_strings
			         VALUES ( NULL,
			                  %s,
	                          %s,
			                  %s,
			                  %s
			                )";

		$wpdb->query(
			$wpdb->prepare(
				$sql,
				$item['orig_string'],
				$item['domain'],
				$item['replacement_string'],
				$item['context']
			)
		);
	}
}
