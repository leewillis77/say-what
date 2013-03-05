<?php



if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly


/**
 * FIXME
 */
class say_what_admin {



	private $settings;

	/**
	 * Constructor
	 */
	function __construct($settings) {

		$this->settings = $settings;
		add_action( 'admin_menu', array ( $this, 'admin_menu' ) );
		add_action ( 'admin_init', array ( $this, 'admin_init' ) );

	}



	public function admin_init() {

		if ( isset ( $_POST['say_what_save'] ) ) {
			$this->save();
		}

	}



	/**
	 * Register the menu item for the admin pages
	 */
	public function admin_menu() {

        if ( current_user_can ( 'manage_options' ) ) {

            $page = add_management_page ( __('Text changes', 'say_what'),
                                          __('Text changes', 'say_what'),
                                          'manage_options',
                                          'say_what_admin',
                                          array ( $this, 'admin' ) );

			if ( isset ( $_GET['page'] ) && $_GET['page'] == 'say_what_admin' ) {
	            add_action('admin_print_styles-' . $page, array ( &$this, 'enqueue_scripts' ) );
	        }

        }

	}



	/**
	 * Add CSS / javascript to admin pages
	 */
	public function enqueue_scripts() {

			wp_register_style ( 'say_what_admin_css', plugins_url().'/say-what/css/admin.css', array() );
			wp_enqueue_style ( 'say_what_admin_css' );

	}



	/**
	 * The main admin page controller
	 */
	public function admin() {

		switch ( $_GET['say_what_action'] ) {

			case 'addedit':
				$this->admin_addedit();
				break;
			case 'list':
			default:
				$this->admin_list();
				break;
		}

	}



	/**
	 * Render the list of currently configured replacement strings
	 */
	public function admin_list() {

		require_once('html/say_what_admin_list.php');

	}



	/**
	 * Render the add/edit page for a replacement
	 */
	public function admin_addedit() {

		global $wpdb, $table_prefix;

		$replacement = false;

		if ( isset ( $_GET['id'] ) ) {

			$sql = "SELECT * FROM {$table_prefix}say_what_strings WHERE string_id = %d";
			$replacement = $wpdb->get_row ( $wpdb->prepare ( $sql, $_GET['id'] ) );

		}

		if ( ! $replacement ) {
			$replacement = new stdClass();
			$replacement->string_id = '';
			$replacement->orig_string = '';
			$replacement->replacement_string = '';
			$replacement->domain = '';
		}

		require_once('html/say_what_admin_addedit.php');

	}



	/**
	 * Something on the admin pages needs saved. handle it all here
	 * Output error/warning messages as required
	 */
	private function save() {

		global $wpdb, $table_prefix;

		if ( isset ( $_POST['say_what_string_id'] ) ) {

			$sql = "UPDATE {$table_prefix}say_what_strings
			           SET orig_string = %s,
			               replacement_string = %s,
			               domain = %s
			         WHERE string_id = %d";

			$wpdb->query ( $wpdb->prepare ( $sql,
			                                $_POST['say_what_orig_string'],
											$_POST['say_what_replacement_string'],
											$_POST['say_what_domain'],
											$_POST['say_what_string_id']
											)
			);

		} else {

			$sql = "INSERT INTO {$table_prefix}say_what_strings
			            VALUES ( NULL,
			                     %s,
			                     %s,
			                     %s )";

			$wpdb->query ( $wpdb->prepare ( $sql,
			                                $_POST['say_what_orig_string'],
											$_POST['say_what_replacement_string'],
											$_POST['say_what_domain']
											)
			);

		}

		wp_redirect( 'tools.php?page=say_what_admin', '303' );
		die();

	}



	private function show_current() {

		require_once ( 'say-what-list-table.class.php' );
		$list_table_instance = new say_what_list_table ( $this->settings );
		$list_table_instance->prepare_items();
  		$list_table_instance->display();

	}

}
