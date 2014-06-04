<?php

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

/**
 * The frontend class, responsible for performing the actual replacements
 */
class say_what_frontend {

	private $replacements;

    /**
     * Read the settings in and put them into a format optimised for the final filter
     */
	function __construct($settings) {
		foreach ( $settings->replacements as $key => $value ) {
			if ( empty ( $value['domain'] ) )
				$value['domain'] = 'default';
			$this->replacements[$value['domain']][$value['orig_string']] = $value['replacement_string'];
		}
		add_filter ( 'gettext', array ( $this, 'gettext' ), 10, 3 );
	}

    /**
     * The main function - optionally perform a string replacement
     */
	function gettext( $translated, $original, $domain ) {
		if ( isset ( $this->replacements[$domain][$original] ) ) {
			return $this->replacements[$domain][$original];
		} else {
			return $translated;
		}
	}

}
