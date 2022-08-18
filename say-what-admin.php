<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Say What admin class - controller for all of the admin pages
 */
class SayWhatAdmin {

	/**
	 * @var SayWhatSettings
	 */
	private $settings;

	/**
	 * Constructor
	 */
	function __construct( $settings ) {
		$this->settings = $settings;
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->settings->base_file ), array( $this, 'add_upgrade_link' ) );
	}

	/**
	 * Admin init actions. Takes care of saving stuff before redirects
	 */
	public function admin_init() {
		if ( isset( $_POST['say_what_save'] ) ) {
			$this->save();
		}
		if ( isset( $_GET['say_what_action'] ) && ( 'delete-confirmed' == $_GET['say_what_action'] ) ) {
			$this->admin_delete_confirmed();
		}
	}

	/**
	 * Add an upgrade link next to the plugin on the Plugins page.
	 *
	 * @param   array  $links  The existing plugin links.
	 * @return  array          The revised list of plugin links.
	 */
	public function add_upgrade_link( $links ) {
		array_unshift(
			$links,
			'<a href="' . admin_url( 'tools.php?page=say_what_admin' ) . '">' . __( 'Settings', 'say-what' ) . '</a>'
		);
		$links[] = '<a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"><strong>Upgrade to Pro</strong></a>';
		return $links;
	}

	/**
	 * Register the menu item for the admin pages
	 */
	public function admin_menu() {
		if ( current_user_can( 'manage_options' ) ) {
			$page = add_management_page(
				__( 'Text changes', 'say-what' ),
				__( 'Text changes', 'say-what' ),
				'manage_options',
				'say_what_admin',
				array( $this, 'admin' )
			);
			if ( isset( $_GET['page'] ) && 'say_what_admin' == $_GET['page'] ) {
				add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_scripts' ) );
			}
		}
	}

	/**
	 * Add CSS / javascript to admin pages
	 */
	public function enqueue_scripts() {
			wp_register_style( 'say_what_admin_css', plugins_url() . '/say-what/css/admin.css', array() );
			wp_enqueue_style( 'say_what_admin_css' );
	}

	/**
	 * The main admin page controller
	 */
	public function admin() {
		$action = isset( $_GET['say_what_action'] ) ? $_GET['say_what_action'] : 'list';
		switch ( $action ) {
			case 'addedit':
				$this->admin_addedit();
				break;
			case 'delete':
				$this->admin_delete();
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
		require_once( 'say-what-list-table.class.php' );
		require_once( 'html/say-what-admin-list.php' );
	}

	/**
	 * Show the page asking the user to confirm deletion
	 */
	public function admin_delete() {
		global $wpdb, $table_prefix;
		if ( ! wp_verify_nonce( $_GET['nonce'], 'swdelete' ) ) {
			wp_die( __( 'Did you really mean to do that? Please go back and try again.', 'say-what' ) );
		}
		if ( isset( $_GET['id'] ) ) {
			$sql = "SELECT * FROM {$table_prefix}say_what_strings WHERE string_id = %d";
			$replacement = $wpdb->get_row( $wpdb->prepare( $sql, $_GET['id'] ) );
		}
		if ( ! $replacement ) {
			wp_die( __( 'Did you really mean to do that? Please go back and try again.', 'say-what' ) );
		}
		require_once( 'html/say-what-admin-delete.php' );
	}

	/**
	 * Show the page asking the user to confirm deletion
	 */
	public function admin_delete_confirmed() {
		global $wpdb, $table_prefix;
		if ( ! wp_verify_nonce( $_GET['nonce'], 'swdelete' ) ||
			 empty( $_GET['id'] ) ) {
			wp_die( __( 'Did you really mean to do that? Please go back and try again.', 'say-what' ) );
		}
		$sql = "DELETE FROM {$table_prefix}say_what_strings WHERE string_id = %d";
		$wpdb->query( $wpdb->prepare( $sql, $_GET['id'] ) );
		$this->settings->invalidate_caches();
		wp_redirect( 'tools.php?page=say_what_admin', '303' );
		die();
	}

	/**
	 * Render the add/edit page for a replacement
	 */
	public function admin_addedit() {
		global $wpdb, $table_prefix;
		$replacement = false;
		if ( isset( $_GET['id'] ) ) {
			$sql = "SELECT * FROM {$table_prefix}say_what_strings WHERE string_id = %d";
			$replacement = $wpdb->get_row( $wpdb->prepare( $sql, $_GET['id'] ) );
		}
		if ( ! $replacement ) {
			$replacement = new stdClass();
			$replacement->string_id = '';
			$replacement->orig_string = '';
			$replacement->replacement_string = '';
			$replacement->domain = '';
			$replacement->context = '';
		}
		require_once( 'html/say-what-admin-addedit.php' );
	}

	/**
	 * Strip CRs out of strings. array_walk() callback.
	 */
	private function strip_cr_callback( &$val, $key ) {
	        $val = str_replace( "\r\n", "\n", $val );
	}

	/**
	 * Something on the admin pages needs saved. Handle it here
	 * Output error/warning messages as required
	 */
	private function save() {
		global $wpdb, $table_prefix;
		if ( ! wp_verify_nonce( $_POST['nonce'], 'swaddedit' ) ) {
			wp_die( __( 'Did you really mean to do that? Please go back and try again.', 'say-what' ) );
		}
		$_POST = stripslashes_deep( $_POST );
		array_walk( $_POST, array( $this, 'strip_cr_callback' ) );
		if ( isset( $_POST['say_what_string_id'] ) ) {
			$sql = "UPDATE {$table_prefix}say_what_strings
					   SET orig_string = %s,
						   replacement_string = %s,
						   domain = %s,
						   context = %s
					 WHERE string_id = %d";
			$wpdb->query(
				$wpdb->prepare(
					$sql,
					$_POST['say_what_orig_string'],
					$_POST['say_what_replacement_string'],
					$_POST['say_what_domain'],
					$_POST['say_what_context'],
					$_POST['say_what_string_id']
				)
			);
		} else {
			$sql = "INSERT INTO {$table_prefix}say_what_strings
							   (
								   orig_string,
								   domain,
								   replacement_string,
								   context
							   )
						VALUES (
								   %s,
								   %s,
								   %s,
								   %s
							   )";

			$wpdb->query(
				$wpdb->prepare(
					$sql,
					$_POST['say_what_orig_string'],
					$_POST['say_what_domain'],
					$_POST['say_what_replacement_string'],
					$_POST['say_what_context']
				)
			);
		}
		$this->settings->invalidate_caches();
		wp_redirect( 'tools.php?page=say_what_admin', '303' );
		die();
	}
}
