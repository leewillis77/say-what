import * as lodash from 'lodash';
import '@wordpress/hooks';

// Initialise
const sayWhatPro = {
	// Filter callbacks for each translation call. Uses handle() to do the heavy lifting.
	gettext( translation, text, domain ) {
		return sayWhatPro.handle(
			translation,
			text,
			text,
			undefined,
			undefined,
			domain
		);
	},
	gettext_with_context( translation, text, context, domain ) {
		return sayWhatPro.handle(
			translation,
			text,
			text,
			undefined,
			context,
			domain
		);
	},
	ngettext( translation, single, plural, number, domain ) {
		return sayWhatPro.handle(
			translation,
			single,
			plural,
			number,
			undefined,
			domain
		);
	},
	ngettext_with_context(
		translation,
		single,
		plural,
		number,
		context,
		domain
	) {
		return sayWhatPro.handle(
			translation,
			single,
			plural,
			number,
			context,
			domain
		);
	},
	/**
	 * Handle a call to a translation function.
	 *
	 * Looks for a replacement and filters the return value if required. If String Discovery is active
	 * also check whether this is a known available string or not, and queues discovery if not.
	 *
	 * @param {string} translation        The translated string.
	 * @param {string} single             Text to translate if non-plural.
	 * @param {string} plural             Text to translate if plural.
	 * @param {string|undefined} number   The number used to decide on plural/non-plural.
	 * @param {string|undefined} context  Context information for the translators.
	 * @param {string} domain             Domain to retrieve the translated text.
	 */
	handle( translation, single, plural, number, context, domain ) {
		// Adjust inputs to expected format.
		if ( typeof domain === 'undefined' ) {
			domain = '';
		}
		if ( typeof context === 'undefined' ) {
			context = '';
		}

		// Assume we're using plural for now.
		let compositeKey = domain + '|' + plural + '|' + context + '|';
		// Revert to the single form if required.
		if ( number === undefined || number === 1 ) {
			compositeKey = domain + '|' + single + '|' + context + '|';
		}

		/**
		 * Look for replacements, and use them if configured.
		 */

		// Look for a language-specific replacement first.
		if (
			lodash.has(
				window.swp_data.replacements,
				compositeKey + window.swp_data.lang
			)
		) {
			return window.swp_data.replacements[
				compositeKey + window.swp_data.lang
			];
		}

		// If not found, look for an "all languages" replacement.
		if ( lodash.has( window.swp_data.replacements, compositeKey ) ) {
			return window.swp_data.replacements[ compositeKey ];
		}

		// No replacement. Return the value unchanged.
		return translation;
	},
};

/**
 * Attach filters.
 */
if ( window.swp_data.domain_specific_filters ) {
	window.swp_data.domains.forEach( function ( domain ) {
		wp.hooks.addFilter(
			'i18n.gettext_' + domain,
			'say-what-pro',
			sayWhatPro.gettext,
			99
		);
		wp.hooks.addFilter(
			'i18n.ngettext_' + domain,
			'say-what-pro',
			sayWhatPro.ngettext,
			99
		);
		wp.hooks.addFilter(
			'i18n.gettext_with_context_' + domain,
			'say-what-pro',
			sayWhatPro.gettext_with_context,
			99
		);
		wp.hooks.addFilter(
			'i18n.ngettext_with_context_' + domain,
			'say-what-pro',
			sayWhatPro.ngettext_with_context,
			99
		);
	} );
} else {
	// Fall back to generic filters
	wp.hooks.addFilter(
		'i18n.gettext',
		'say-what-pro',
		sayWhatPro.gettext,
		99
	);
	wp.hooks.addFilter(
		'i18n.ngettext',
		'say-what-pro',
		sayWhatPro.ngettext,
		99
	);
	wp.hooks.addFilter(
		'i18n.gettext_with_context',
		'say-what-pro',
		sayWhatPro.gettext_with_context,
		99
	);
	wp.hooks.addFilter(
		'i18n.ngettext_with_context',
		'say-what-pro',
		sayWhatPro.ngettext_with_context,
		99
	);
}
