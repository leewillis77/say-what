<?php

/*
Plugin Name: Say What?
Plugin URI: https://github.com/leewillis77/say-what
Description: An easy-to-use plugin that allows you to alter strings on your site without editing WordPress core, or plugin code
Version: 1.0.1
Author: Lee Willis
Author URI: http://www.leewillis.co.uk/
*/

/**
 * Copyright (c) 2013-2014 Lee Willis. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

/**
 * Main plugin class, responsible for triggering everything
 */
class say_what {

	private $settings_instance;
	private $frontend_instance;
	private $admin_instance;
	private $db_version = 2;

	/**
	 * Constructor
	 */
	public function __construct(){
		require_once ( 'say-what-settings.php' );
		$this->settings_instance = new say_what_settings();
		if ( is_admin() ) {
			require_once ( 'say-what-admin.php' );
			$this->admin_instance = new say_what_admin( $this->settings_instance );
		}
		require_once ( 'say-what-frontend.php' );
		$this->frontend_instance = new say_what_frontend( $this->settings_instance );

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Fires on init().
	 * Set up translation for the plugin itself.
	 */
	public function init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'say_what' );
		load_textdomain( 'say_what', WP_LANG_DIR.'/say_what/say_what-' . $locale . '.mo' );
		load_plugin_textdomain( 'say_what', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Fires on admin_init().
	 * Check for any required database schema updates.
	 */
	public function admin_init() {
		$this->check_db_version();
	}

	/**
	 * Check for pending upgrades, and run them if required.
	 */
	public function check_db_version() {
		$current_db_version = (int) get_option( 'say_what_db_version', 1 );
		// Bail if we're already up to date.
		if ( $current_db_version >= $this->db_version ) {
			return;
		}
		// Otherwise, check for, and run updates.
		foreach ( range( $current_db_version + 1, $this->db_version ) as $version ) {
			if ( is_callable( array( $this, 'upgrade_db_to_' . $version ) ) ) {
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
	 */
	private function upgrade_db_to_2() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'say_what_strings';
		$sql = "CREATE TABLE $table_name (
		                     `string_id` int(11) NOT NULL AUTO_INCREMENT,
		                     `orig_string` text NOT NULL,
		                     `domain` varchar(255),
		                     `replacement_string` text,
		                     `context` text
		                     )";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/**
 * Install function. Create the table to store the replacements
 */
function say_what_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'say_what_strings';
    $sql = "CREATE TABLE $table_name (
                         `string_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `orig_string` text NOT NULL,
                         `domain` varchar(255),
                         `replacement_string` text,
                         `context` text
                         )";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'say_what_install' );

$say_what = new say_what();
