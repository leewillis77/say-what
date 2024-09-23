<?php

/**
 * Plugin Name: Say What?
 * Plugin URI: https://github.com/leewillis77/say-what
 * Description: An easy-to-use plugin that allows you to alter strings on your site without editing WordPress core, or plugin code
 * Version: 2.2.5
 * Author: Ademti Software
 * Author URI: https://plugins.leewillis.co.uk/
 * Text Domain: say-what
 * License: GPLv2
 */

/**
 * Copyright (c) 2016-2024 Ademti Software Ltd. All rights reserved.
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

use Ademti\SayWhat\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

const SAY_WHAT_VERSION = '2.2.5';

const SAY_WHAT_DB_VERSION = 3;

require __DIR__ . '/vendor/autoload.php';

/**
 * Install function. Create the table to store the replacements
 */
function say_what_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'say_what_strings';
	$sql        = "CREATE TABLE $table_name (
						 string_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
						 orig_string text NOT NULL,
						 domain varchar(255),
						 replacement_string text,
						 context text
						 ) DEFAULT CHARACTER SET utf8";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	update_option( 'say_what_db_version', SAY_WHAT_DB_VERSION );
}
register_activation_hook( __FILE__, 'say_what_install' );

global $say_what;
$say_what = new Main();
