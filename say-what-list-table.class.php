<?php


if ( ! class_exists ( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * List table class for the admin pages
 */
class say_what_list_table extends WP_List_Table {



	function __construct ( $settings ) {

		$this->items = $settings->replacements;

	}



	function get_columns(){

		$columns = array(
			'orig_string' => __( 'Original String', 'say_what' ),
			'domain' => __( 'Domain', 'say_what' ),
			'context' => __( 'Context', 'say_what' ), // FIXME - do we access to context in gettext filter?
			'replacement_string'    => __( 'Replacement', 'say_what' ),
			'edit_links'      => _x( '', 'Header for edit links on admin list table', 'say_what' ),
		);
		return $columns;

	}



	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();

		$sortable = array();
		// $sortable = $this->get_sortable_columns();// FIXME - implement sorting
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->items; // FIXME - implement sorting
	}



	function get_sortable_columns() {
		return array (
          'orig_string' => array ( 'orig_string', false ),
          'domain' => array ( 'domain', false ),
          'context' => array ( 'context', false ),
          'replacement_string' => array ( 'replacement_string', false ) );
	}



	function column_default ( $item, $column_name ) {

		return $item[$column_name];

	}



	function column_edit_links ( $item, $column_name ) {

		return __( 'Edit', 'say_what' ); // FIXME

	}

}