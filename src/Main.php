<?php

namespace Ademti\SayWhat;

use WP_CLI;

/**
 * Main plugin class, responsible for triggering everything
 */
class Main {

	// Dependencies.
	private Settings $settings_instance;
	private Frontend $frontend_instance;
	private Admin $admin_instance;

	/**
	 * @var int
	 */
	private int $db_version = SAY_WHAT_DB_VERSION;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settings_instance = new Settings( plugin_basename( dirname( __DIR__ ) . '/say-what.php' ) );
		if ( is_admin() ) {
			$this->admin_instance = new Admin( $this->settings_instance );
		}
		$this->frontend_instance = new Frontend( $this->settings_instance );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'say-what', WpCliSupport::class );
		}
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_filter( 'say_what_domain_aliases', [ $this, 'register_domain_alias' ] );
	}

	/**
	 * Fires on init().
	 * Set up translation for the plugin itself.
	 */
	public function init(): void {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'say-what' );
		load_textdomain( 'say-what', WP_LANG_DIR . '/say_what/say-what-' . $locale . '.mo' );
		load_plugin_textdomain( 'say-what', false, basename( __DIR__ ) . '/languages/' );
	}

	/**
	 * Register an alias for our old text domain so any existing replacements
	 * keep working.
	 */
	public function register_domain_alias( array $aliases ): array {
		$aliases['say-what'][] = 'say_what';

		return $aliases;
	}

	/**
	 * Fires on admin_init().
	 * Check for any required database schema updates.
	 */
	public function admin_init(): void {
		$this->check_db_version();
	}

	/**
	 * Check for pending upgrades, and run them if required.
	 */
	public function check_db_version(): void {
		$current_db_version = (int) get_option( 'say_what_db_version', 1 );
		// Bail if we're already up to date.
		if ( $current_db_version >= $this->db_version ) {
			return;
		}
		// Otherwise, check for, and run updates.
		foreach ( range( $current_db_version + 1, $this->db_version ) as $version ) {
			if ( is_callable( [ $this, 'upgrade_db_to_' . $version ] ) ) {
				$this->{'upgrade_db_to_' . $version}();
				update_option( 'say_what_db_version', $version );
			} else {
				update_option( 'say_what_db_version', $version );
			}
		}
	}

	/**
	 * Database v2 upgrade.
	 *
	 * Add context to database schema.
	 *
	 * @SuppressWarnings(PMD.UnusedPrivateMethod)
	 */
	private function upgrade_db_to_2(): void {
		global $wpdb;
		$table_name = $wpdb->prefix . 'say_what_strings';
		$sql        = "CREATE TABLE $table_name (
							 string_id int(11) NOT NULL AUTO_INCREMENT,
							 orig_string text NOT NULL,
							 domain varchar(255),
							 replacement_string text,
							 context text
							 )";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Database v3 upgrade.
	 *
	 * Convert character set to utf8.
	 *
	 * @SuppressWarnings(PMD.UnusedPrivateMethod)
	 */
	private function upgrade_db_to_3(): void {
		global $wpdb;
		$table_name = $wpdb->prefix . 'say_what_strings';
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query( $wpdb->prepare( 'ALTER TABLE %i CONVERT TO CHARACTER SET utf8', [ $table_name ] ) );
		// phpcs:enable
	}

	/**
	 * @return Settings
	 */
	public function get_settings_instance(): Settings {
		return $this->settings_instance;
	}

	/**
	 * @return Frontend
	 */
	public function get_frontend_instance(): Frontend {
		return $this->frontend_instance;
	}

	/**
	 * @return Admin
	 */
	public function get_admin_instance(): Admin {
		return $this->admin_instance;
	}
}
