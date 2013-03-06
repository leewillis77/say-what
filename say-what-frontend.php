<?php



if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly


/**
 * @TODO - docblocks
 */
class say_what_frontend {



	private $replacements;



	function __construct($settings) {

		foreach ( $settings->replacements as $key => $value ) {

			if ( empty ( $value['domain'] ) )
				$value['domain'] = 'default';

			$this->replacements[$value['domain']][$value['orig_string']] = $value['replacement_string'];
		}

		add_filter ( 'gettext', array ( $this, 'gettext' ), 10, 3 );

	}



	function gettext( $translated, $original, $domain ) {

		if ( isset ( $this->replacements[$domain][$original] ) ) {
			return $this->replacements[$domain][$original];
		} else {
			return $translated;
		}

	}



}
