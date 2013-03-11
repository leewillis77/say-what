<?php


if ( ! class_exists ( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}



/**
 * List table class for the admin pages
 */
class say_what_list_table extends WP_List_Table {



	private $settings;



	function __construct ( $settings ) {

		$this->settings = $settings;
		parent::__construct();

	}



	/**
	 * Description shown when no replacements configured
	 */
	function no_items() {
	  _e( 'No string replacements configured yet.', 'say_what' );
	}



	/**
	 * Specify the list of columns in the table
	 * @return array The list of columns
	 */
	function get_columns(){

		$columns = array(
            'cb'        => 'Checkboxes',
		    'string_id' => 'String replacement ID (Internal)',
			'orig_string' => __( 'Original string', 'say_what' ),
			'domain' => __( 'Domain', 'say_what' ),
			'replacement_string'    => __( 'Replacement string', 'say_what' ),
			'edit_links'      => _x( '', 'Header for edit links on admin list table', 'say_what' ),
			'delete_links'      => _x( '', 'Header for delete links on admin list table', 'say_what' ),
		);
		return $columns;

	}



	/**
	 * Retrieve the items for display
	 * @return [type] [description]
	 */
	function prepare_items() {

		global $wpdb, $table_prefix;

		$columns = $this->get_columns();
		$hidden = array('string_id');
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		//$this->search_box(__('Search', 'say_what'), 'search_id'); // FIXME - implement searching

		// We don't use the replacements from the settings object, we query them separately to make
		// ordering/searhing/pagination easier. This may turn out bad if people have "lots"
		$sql = "SELECT * FROM {$table_prefix}say_what_strings";
		if ( isset ( $_GET['orderby'] ) ) {

			$sql .= " ORDER BY ".$wpdb->escape ( $_GET['orderby'] );

			if ( isset ( $_GET['order'] ) ) {
				$sql .= " ".$wpdb->prepare ( $_GET['order'] );
			}

		} else {

			$sql .= ' ORDER BY orig_string ASC';
		}

		$this->items = $wpdb->get_results ( $sql, ARRAY_A );

	}



	function get_sortable_columns() {

		return array (
          'orig_string' => array ( 'orig_string', true ),
          'domain' => array ( 'domain', false ),
          'replacement_string' => array ( 'replacement_string', false ) );

	}



	function get_bulk_actions() {

		// FIXME - implement bulk actions
		//$actions = array ( 'delete' => __( 'Delete', 'say_what' ) );

		return array();

	}



	function column_cb ( $item ) {

		return sprintf ( '<input type="checkbox" name="string_id[]" value="%d" />', $item['string_id'] );

	}



	function column_default ( $item, $column_name ) {

		return $item[$column_name];

	}



	function column_edit_links ( $item ) {

		return '<a href="tools.php?page=say_what_admin&amp;say_what_action=addedit&amp;id='.urlencode($item['string_id']).'&amp;nonce='.urlencode(wp_create_nonce('swaddedit')).'">'.__( 'Edit', 'say_what').'</a>';

	}



	function column_delete_links ( $item ) {

		return '<a href="tools.php?page=say_what_admin&say_what_action=delete&id='.urlencode($item['string_id']).'&nonce='.urlencode(wp_create_nonce('swdelete')).'">'.__( 'Delete', 'say_what').'</a>';

	}

}