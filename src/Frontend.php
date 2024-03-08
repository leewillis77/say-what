<?php

namespace Ademti\SayWhat;

/**
 * The frontend class, responsible for performing the actual replacements
 */
class Frontend {

	// Dependencies.
	private Settings $settings;

	private array $replacements;

	/**
	 * Read the settings in and put them into a format optimised for the final filter
	 */
	public function __construct( $settings ) {

		$this->settings = $settings;
		foreach ( $settings->replacements as $value ) {
			if ( empty( $value['domain'] ) ) {
				$value['domain'] = 'default';
			}
			if ( empty( $value['context'] ) ) {
				$value['context'] = 'sw-default-context';
			}
			$this->replacements[ $value['domain'] ][ $value['orig_string'] ][ $value['context'] ] = $value['replacement_string'];
		}
		add_filter( 'gettext', [ $this, 'gettext' ], 10, 3 );
		add_filter( 'ngettext', [ $this, 'ngettext' ], 10, 5 );
		add_filter( 'gettext_with_context', [ $this, 'gettext_with_context' ], 10, 4 );
		add_filter( 'ngettext_with_context', [ $this, 'ngettext_with_context' ], 10, 6 );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
	}

	/**
	 * Perform a string replacement without context.
	 */
	public function gettext( $translated, $original, $domain ) {
		return $this->ngettext_with_context( $translated, $original, '', null, 'sw-default-context', $domain );
	}

	/**
	 * Perform a string replacement with context.
	 */
	public function gettext_with_context( $translated, $original, $context, $domain ) {
		return $this->ngettext_with_context( $translated, $original, '', null, $context, $domain );
	}

	/**
	* Perform a (possibly) pluralised translation without context.
	*/
	public function ngettext( $translated, $single, $plural, $number, $domain ) {
		return $this->ngettext_with_context( $translated, $single, $plural, $number, 'sw-default-context', $domain );
	}

	/**
	 * Perform a (possibly) pluralised translation with context.
	 *
	 * Note: This also handles the main logic for all other replacements.
	 *
	 * @param  string $translated The current string.
	 * @param  string $single     The original (singular) string.
	 * @param  string $plural     The original (pluralised) string.
	 *                            [May be NULL for non _n()-type calls]
	 * @param  int    $number     The number used to determine if singular or pluralised should be used.
	 *                            [May be NULL for non _n()-type calls]
	 * @param  string $context    The context, may be null for non _x()-type calls.
	 * @param  string $domain     The domain.
	 * @return string             The replaced string.
	 */
	public function ngettext_with_context( $translated, $single, $plural, $number, $context, $domain ) {
		/*
		 * Plugins can use the say_what_domain_aliases filter to return an alias for their domain
		 * if for any reason they change their text domain and want existing replacements to continue
		 * working. The filter should return an array keyed on the current text domain with the value
		 * set to an array of alternative domains to search for replacements. E.g
		 *   $aliases['easy-digital-downloads'][] = 'edd';
		 *   return $aliases;
		 */
		static $domain_aliases = null;
		if ( $domain_aliases === null ) {
			$domain_aliases = apply_filters( 'say_what_domain_aliases', [] );
		}
		$original = $single;
		if ( ! is_null( $number ) && (int) $number !== 1 ) {
				$original = $plural;
		}
		if ( isset( $this->replacements[ $domain ][ $original ][ $context ] ) ) {
			return $this->replacements[ $domain ][ $original ][ $context ];
		}
		if ( isset( $domain_aliases[ $domain ] ) ) {
			foreach ( $domain_aliases[ $domain ] as $alias ) {
				if ( isset( $this->replacements[ $alias ][ $original ][ $context ] ) ) {
					return $this->replacements[ $alias ][ $original ][ $context ];
				}
			}
			return $translated;
		}
		return $translated;
	}

	public function wp_enqueue_scripts(): void {
		$asset_file = include plugin_dir_path( __FILE__ ) . '/../assets/build/frontend.asset.php';
		wp_register_script(
			'say-what-js',
			plugins_url( '/say-what/assets/build/frontend.js' ),
			$asset_file['dependencies'],
			$asset_file['version'],
			false
		);
		wp_localize_script(
			'say-what-js',
			'say_what_data',
			[
				'replacements' => $this->settings->get_flattened_replacements(),
			]
		);
		wp_enqueue_script( 'say-what-js' );
	}
}
