<?php

namespace Ademti\SayWhat;

use Exception;
use WP_List_Table;

/**
 * List table class for the admin pages
 */
class ListTable extends WP_List_Table {

	/**
	 * Description shown when no replacements configured
	 */
	function no_items() {
		_e( 'No string replacements configured yet.', 'say-what' );
	}

	/**
	 * Specify the list of columns in the table
	 * @return array The list of columns
	 *
	 * @throws Exception
	 */
	function get_columns() {
		$columns = array(
			/*'cb'        => 'Checkboxes',*/
			'string_id'          => 'String replacement ID (Internal)',
			'orig_string'        => __( 'Original string', 'say-what' ),
			'domain'             => __( 'Text domain', 'say-what' ),
			'context'            => __( 'Text context', 'say-what' ),
			'replacement_string' => __( 'Replacement string', 'say-what' ),
			'edit_links'         => _x( '', 'Header for edit links on admin list table', 'say-what' ),
			'delete_links'       => _x( '', 'Header for delete links on admin list table', 'say-what' ),
		);

		return $columns;
	}

	/**
	 * Retrieve the items for display
	 */
	function prepare_items() {

		global $wpdb, $table_prefix;

		$columns               = $this->get_columns();
		$hidden                = array( 'string_id' );
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// We don't use the replacements from the settings object, we query them separately to make
		// ordering/pagination easier. This may turn out bad if people have "lots"

		$sql = "SELECT * FROM {$table_prefix}say_what_strings";

		// Handle ordering of the results.
		// The isset() check below validates that the passed data is a valid column name, and the value is
		// not escaped in the query accordingly.
		if ( isset ( $_GET['orderby'] ) && isset( $sortable[ $_GET['orderby'] ] ) ) {
			$sql .= ' ORDER BY ' . $_GET['orderby'];
			if ( isset( $_GET['order'] ) && strtolower( $_GET['order'] ) === 'desc' ) {
				$sql .= ' DESC';
			} else {
				$sql .= ' ASC';
			}
		} else {
			// Default ordering.
			$sql .= ' ORDER BY orig_string ASC';

		}
		$this->items = $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * Indicate which columns are sortable
	 * @return array A list of the columns that are sortable.
	 */
	function get_sortable_columns() {
		return array(
			'orig_string'        => array( 'orig_string', true ),
			'domain'             => array( 'domain', false ),
			'context'            => array( 'context', false ),
			'replacement_string' => array( 'replacement_string', false )
		);
	}

	/**
	 * Set the primary column.
	 *
	 * @return string The name of the primary column.
	 */
	protected function get_primary_column_name() {
		return 'orig_string';
	}

	/**
	 * Specify the bulk actions available. Not used currently
	 */
	function get_bulk_actions() {
		// @TODO - implement bulk actions
		//$actions = array ( 'delete' => __( 'Delete', 'say-what' ) );
		return array();
	}

	/**
	 * Checkboxes for the rows. Not used while we don't have bulk actions
	 */
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="string_id[]" value="%d" />', $item['string_id'] );
	}

	/**
	 * Output column data
	 */
	function column_default( $item, $column_name ) {
		return esc_html( htmlspecialchars( $item[ $column_name ] ) );
	}

	/**
	 * Output an edit link for the row
	 */
	function column_edit_links( $item ) {
		return '<a href="tools.php?page=say_what_admin&amp;say_what_action=addedit&amp;id=' .
			   urlencode( $item['string_id'] ) .
			   '&amp;nonce=' .
			   urlencode( wp_create_nonce( 'swaddedit' ) ) .
			   '">' .
			   __( 'Edit', 'say-what' ) .
			   '</a>';
	}

	/**
	 * Output a delete link for the row
	 */
	function column_delete_links( $item ) {
		return '<a href="tools.php?page=say_what_admin&say_what_action=delete&id=' .
			   urlencode( $item['string_id'] ) .
			   '&nonce=' .
			   urlencode( wp_create_nonce( 'swdelete' ) ) .
			   '">' .
			   __( 'Delete', 'say-what' ) .
			   '</a>';
	}

}
