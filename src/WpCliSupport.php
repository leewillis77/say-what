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
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function _import( $args, $assoc_args ) {
		global $say_what;

		$filename = $args[0];
		$inserted = 0;
		foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $item ) {
			$this->insert_replacement( $item );
			$inserted++;
		}
		$say_what->get_settings_instance()->invalidate_caches();
		WP_CLI::success( sprintf( '%d new items created.', $inserted ) );
	}

	/**
	 * update string replacements from a CSV file.
	 *
	 * Items with a string ID will have their information updated. Items without a string ID
	 * will be inserted as a new item.
	 *
	 * ## OPTIONS
	 *
	 * None.
	 *
	 * ## EXAMPLES
	 *
	 * wp say-what update {file}
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function update( $args, $assoc_args ) {
		global $say_what;
		$filename = $args[0];
		$updated = $inserted = 0;
		foreach ( new \WP_CLI\Iterators\CSV( $filename ) as $item ) {
			if ( ! empty( $item['string_id'] ) ) {
				$this->update_replacement( $item );
				$updated++;
			} else {
				$this->insert_replacement( $item );
				$inserted++;
			}
		}
		$say_what->get_settings_instance()->invalidate_caches();
		WP_CLI::success( sprintf( '%d records updated, %d new items created.', $updated, $inserted ) );
	}

	/**
	 * Gets a list of the currentl set replacements.
	 *
	 * @return array    An array of replacement objects.
	 */
	protected function get_replacements() {
		global $wpdb, $table_prefix;
		$table = $table_prefix . 'say_what_strings';
		return $wpdb->get_results( "SELECT * FROM $table" );
	}

	/**
	 * updates an existing replacement into the database.
	 *
	 * @param  array  $item  The item to be updated..
	 */
	protected function update_replacement( $item ) {
		global $wpdb, $table_prefix;

		$sql = "UPDATE {$table_prefix}say_what_strings
		           SET orig_string = %s,
		               domain = %s,
		               replacement_string = %s,
		               context = %s
		         WHERE string_id = %d";
		$wpdb->query(
			$wpdb->prepare(
				$sql,
				$item['orig_string'],
				$item['domain'],
				$item['replacement_string'],
				$item['context'],
				$item['string_id']
			)
		);
	}

	/**
	 * Inserts a replacement into the database.
	 *
	 * @param  array  $item  The item to be inserted.
	 */
	protected function insert_replacement( $item ) {
		global $wpdb, $table_prefix;
		$sql = "INSERT INTO {$table_prefix}say_what_strings (orig_string, domain, replacement_string, context)
			         VALUES (
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
