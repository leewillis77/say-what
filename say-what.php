<?php

/*
Plugin Name: Say What?
Plugin URI: https://github.com/leewillis77/say-what
Description: An easy-to-use plugin that allows you to alter strings on your site without editing WordPress core, or plugin code
Version: 0.9.3
Author: Lee Willis
Author URI: http://www.leewillis.co.uk/
*/

/**
 * Copyright (c) 2013 Lee Willis. All rights reserved.
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



	/**
	 * Constructor
	 */
	public function __construct(){

		add_action ( 'init', array ( $this, 'init' ) );

		require_once ( 'say-what-settings.php' );
		$this->settings_instance = new say_what_settings();

		if ( is_admin() ) {

			require_once ( 'say-what-admin.php' );
			$this->admin_instance = new say_what_admin ( $this->settings_instance );

		}

		require_once ( 'say-what-frontend.php' );
		$this->frontend_instance = new say_what_frontend ( $this->settings_instance );

	}



	/**
	 * Fires on init()
	 * Set up translation for the plugin itself
	 */
	public function init() {

		$locale = apply_filters ( 'plugin_locale', get_locale(), "say_what");
	    load_textdomain ( 'say_what', WP_LANG_DIR.'/say_what/say_what-'.$locale.'.mo');
    	load_plugin_textdomain ( 'say_what', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

}



/**
 * Install function. Create the table to store the replacements
 */
function say_what_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "say_what_strings";

    $sql = "CREATE TABLE $table_name (
                         `string_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `orig_string` text NOT NULL,
                         `domain` varchar(255),
                         `replacement_string` text
                         )";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

}

register_activation_hook ( __FILE__, 'say_what_install' );



$say_what = new say_what();
