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
	 *
	 * Plugins can use the say_what_domain_aliases filter to return an alias for their domain
	 * if for any reason they change their text domain and want existing replacements to continue
	 * working. The filter should return an array keyed on the current text domain with the value
	 * set to an array of alternative domains to search for replacements. E.g
	 *   $aliases['easy-digital-downloads'][] = 'edd';
	 *   return $aliases;
	 */
	public function gettext_with_context( $translated, $original, $context, $domain ) {
		static $domain_aliases = null;
		if ( $domain_aliases === null ) {
			$domain_aliases = apply_filters( 'say_what_domain_aliases', array() );
		}
		if ( isset( $this->replacements[ $domain ][ $original ][ $context ] ) ) {
			return $this->replacements[ $domain ][ $original ][ $context ];
		} elseif ( isset( $domain_aliases[ $domain ] ) ) {
			foreach ( $domain_aliases[ $domain ] as $alias ) {
				if ( isset( $this->replacements[ $alias ][ $original ][ $context ] ) ) {
					return $this->replacements[ $alias ][ $original ][ $context ];
				}
			}
			return $translated;
		} else {
			return $translated;
		}
	}

}
