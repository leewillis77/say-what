<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The frontend class, responsible for performing the actual replacements
 */
class SayWhatFrontend {

	private $replacements;

	/**
	 * Read the settings in and put them into a format optimised for the final filter
	 */
	function __construct( $settings ) {
		foreach ( $settings->replacements as $value ) {
			if ( empty ( $value['domain'] ) ) {
				$value['domain'] = 'default';
			}
			if ( empty ( $value['context'] ) ) {
				$value['context'] = 'sw-default-context';
			}
			$this->replacements[ $value['domain'] ][ $value['orig_string'] ][ $value['context'] ] = $value['replacement_string'];
		}
		add_filter( 'gettext', array( $this, 'gettext' ), 10, 3 );
		add_filter( 'gettext_with_context', array( $this, 'gettext_with_context' ), 10, 4 );
	}

	/**
	 * Perform a string replacement without context.
	 */
	public function gettext( $translated, $original, $domain ) {
		return $this->gettext_with_context( $translated, $original, 'sw-default-context', $domain );
	}

	/**
	 * Perform a string replacement with context.
	 */
	public function gettext_with_context( $translated, $original, $context, $domain ) {
	
		$domain = apply_filters( 'say_what_gettext_domain', $domain, $translated, $original, $context );
	
		if ( isset ( $this->replacements[ $domain ][ $original ][ $context ] ) ) {
			return $this->replacements[ $domain ][ $original ][ $context ];
		} else {
			return $translated;
		}
	}

}
