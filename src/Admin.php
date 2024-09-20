<?php

namespace Ademti\SayWhat;

use Exception;
use stdClass;
use function add_action;
use function admin_url;
use function array_walk;
use function sanitize_key;
use function stripslashes_deep;
use function wp_unslash;
use function wp_verify_nonce;
use function wp_die;

/**
 * Say What admin class - controller for all the admin pages.
 */
class Admin {

	/**
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Constructor
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_filter(
			'plugin_action_links_' . plugin_basename( $this->settings->base_file ),
			[ $this, 'add_upgrade_link' ]
		);
	}

	/**
	 * Admin init actions. Takes care of saving stuff before redirects
	 */
	public function admin_init() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
		if ( isset( $_POST['say_what_save'] ) ) {
			$this->save();
		}
		if ( isset( $_GET['say_what_action'] ) && ( 'delete-confirmed' === $_GET['say_what_action'] ) ) {
			$this->admin_delete_confirmed();
		}
		// phpcs:enable
	}

	/**
	 * Add an upgrade link next to the plugin on the Plugins page.
	 *
	 * @param array $links The existing plugin links.
	 *
	 * @return  array          The revised list of plugin links.
	 * @throws Exception
	 */
	public function add_upgrade_link( array $links ): array {
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
	public function admin_menu(): void {
		if ( current_user_can( 'manage_options' ) ) {
			$page = add_management_page(
				__( 'Text changes', 'say-what' ),
				__( 'Text changes', 'say-what' ),
				'manage_options',
				'say_what_admin',
				[ $this, 'admin' ]
			);
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && 'say_what_admin' === $_GET['page'] ) {
				add_action( 'admin_print_styles-' . $page, [ $this, 'enqueue_scripts' ] );
			}
			// phpcs:enable
		}
	}

	/**
	 * Add CSS / javascript to admin pages
	 */
	public function enqueue_scripts(): void {
			wp_register_style(
				'say_what_admin_css',
				plugins_url() . '/say-what/css/admin.css',
				[],
				SAY_WHAT_VERSION
			);
			wp_enqueue_style( 'say_what_admin_css' );
	}

	/**
	 * The main admin page controller
	 */
	public function admin(): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$action = isset( $_GET['say_what_action'] ) ?
			sanitize_key( wp_unslash( $_GET['say_what_action'] ) ) :
			'list';
		// phpcs:enable
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
		require_once __DIR__ . '/../html/say-what-admin-list.php';
	}

	/**
	 * Show the page asking the user to confirm deletion.
	 */
	public function admin_delete(): void {
		global $wpdb, $table_prefix;
		$nonce = isset( $_GET['nonce'] ) ?
			sanitize_key( wp_unslash( $_GET['nonce'] ) ) :
			null;
		if ( ! wp_verify_nonce( $nonce, 'swdelete' ) ) {
			wp_die(
				esc_html(
					__( 'Did you really mean to do that? Please go back and try again.', 'say-what' )
				)
			);
		}
		$table_name = $table_prefix . 'say_what_strings';
		$id = isset( $_GET['id'] ) ?
			sanitize_key( wp_unslash( $_GET['id'] ) ) :
			null;
		if ( ! empty( $id ) ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$replacement = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE string_id = %d',
					$table_name,
					$id
				)
			);
		}
		if ( ! $replacement ) {
			wp_die(
				esc_html(
					__( 'Did you really mean to do that? Please go back and try again.', 'say-what' )
				)
			);
		}
		require_once __DIR__ . '/../html/say-what-admin-delete.php';
	}

	/**
	 * Show the page asking the user to confirm deletion
	 */
	public function admin_delete_confirmed(): void {
		global $wpdb, $table_prefix;
		$id = isset( $_GET['id'] ) ?
			sanitize_key( wp_unslash( $_GET['id'] ) ) :
			null;
		if ( ! isset ($_GET['nonce'] ) ||
		     ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['nonce'] ) ), 'swdelete' ) ||
			empty( $id )
		) {
			wp_die(
				esc_html(
					__( 'Did you really mean to do that? Please go back and try again.', 'say-what')
				)
			);
		}
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				'DELETE FROM %i WHERE string_id = %d',
				$table_prefix . 'say_what_strings',
				$id
			)
		);
		$this->settings->invalidate_caches();
		wp_safe_redirect( 'tools.php?page=say_what_admin', '303' );
		die();
	}

	/**
	 * Render the add/edit page for a replacement
	 */
	public function admin_addedit(): void {
		global $wpdb, $table_prefix;
		$replacement = false;
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$id = isset( $_GET['id'] ) ?
			sanitize_key( wp_unslash( $_GET['id'] ) ) :
			null;
		// phpcs:enable
		if ( ! is_null( $id ) ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$replacement = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE string_id = %d',
					$table_prefix . 'say_what_strings',
					$id
				)
			);
		}
		if ( ! $replacement ) {
			$replacement                     = new stdClass();
			$replacement->string_id          = '';
			$replacement->orig_string        = '';
			$replacement->replacement_string = '';
			$replacement->domain             = '';
			$replacement->context            = '';
		}
		require_once __DIR__ . '/../html/say-what-admin-addedit.php';
	}

	/**
	 * Strip CRs out of strings. array_walk() callback.
	 *
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	private function strip_cr_callback( &$val, $key ): void {
			$val = str_replace( "\r\n", "\n", $val );
	}
	// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

	/**
	 * Something on the admin pages needs saved. Handle it here
	 * Output error/warning messages as required
	 */
	private function save(): void {
		global $wpdb, $table_prefix;
		$nonce = isset( $_POST['nonce'] )  ?
			sanitize_key( wp_unslash( $_POST['nonce'] ) ) :
			'';
		if ( ! wp_verify_nonce( $nonce, 'swaddedit' ) ) {
			wp_die(
				esc_html(
					__( 'Did you really mean to do that? Please go back and try again.', 'say-what' )
				)
			);
		}
		$table_name    = $table_prefix . 'say_what_strings';
		$stripped_post = stripslashes_deep( $_POST );
		array_walk( $stripped_post, [ $this, 'strip_cr_callback' ] );

		if ( isset( $stripped_post['say_what_string_id'] ) ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE %i
							   SET orig_string = %s,
								   replacement_string = %s,
								   domain = %s,
								   context = %s
							 WHERE string_id = %d',
					$table_name,
					$stripped_post['say_what_orig_string'],
					$stripped_post['say_what_replacement_string'],
					$stripped_post['say_what_domain'],
					$stripped_post['say_what_context'],
					$stripped_post['say_what_string_id']
				)
			);
		} else {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					'INSERT INTO %i
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
									   )',
					$table_name,
					$stripped_post['say_what_orig_string'],
					$stripped_post['say_what_domain'],
					$stripped_post['say_what_replacement_string'],
					$stripped_post['say_what_context']
				)
			);
		}
		$this->settings->invalidate_caches();
		wp_safe_redirect( 'tools.php?page=say_what_admin', '303' );
		die();
	}
}
