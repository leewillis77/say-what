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
            'cb'        => 'Checkboxes',
		    'string_id' => 'String replacement ID (Internal)',
			'orig_string' => __( 'Original String', 'say_what' ),
			'domain' => __( 'Domain', 'say_what' ),
			'replacement_string'    => __( 'Replacement', 'say_what' ),
			'edit_links'      => _x( '', 'Header for edit links on admin list table', 'say_what' ),
			'delete_links'      => _x( '', 'Header for delete links on admin list table', 'say_what' ),
		);
		return $columns;

	}



	function prepare_items() {

		$columns = $this->get_columns();
		$hidden = array('string_id');;
		$sortable = array();
		// $sortable = $this->get_sortable_columns();// FIXME - implement sorting
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->items; // FIXME - implement sorting
		$this->search_box(__('Search', 'say_what'), 'search_id'); // FIXME - implement searching

	}



	function get_sortable_columns() {

		return array (
          'orig_string' => array ( 'orig_string', false ),
          'domain' => array ( 'domain', false ),
          'replacement_string' => array ( 'replacement_string', false ) );

	}



	function get_bulk_actions() {

		// FIXME - implement bulk actions
		$actions = array ( 'delete' => __( 'Delete', 'say_what' ) );

		return $actions;

	}



	function column_cb ( $item ) {

		return sprintf ( '<input type="checkbox" name="string_id[]" value="%d" />', $item['ID'] );

	}



	function column_default ( $item, $column_name ) {

		return $item[$column_name];

	}



	function column_edit_links ( $item, $column_name ) {

		return '<a href="tools.php?page=say_what_admin&swaction=edit&id='.urlencode($item['string_id']).'">'.__( 'Edit', 'say_what').'</a>';

	}



	function column_delete_links ( $item, $column_name ) {

		return '<a href="tools.php?page=say_what_admin&swaction=delete&id='.urlencode($item['string_id']).'&swnonce='.urlencode(wp_create_nonce('swdelete')).'">'.__( 'Delete', 'say_what').'</a>';

	}

}