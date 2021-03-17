import '@wordpress/hooks';

// Initialise
const sayWhat = {
	// Filter callbacks for each translation call. Uses handle() to do the heavy lifting.
	gettext( translation, text, domain ) {
		return sayWhat.handle(
			translation,
			text,
			text,
			undefined,
			undefined,
			domain
		);
	},
	gettext_with_context( translation, text, context, domain ) {
		return sayWhat.handle(
			translation,
			text,
			text,
			undefined,
			context,
			domain
		);
	},
	ngettext( translation, single, plural, number, domain ) {
		return sayWhat.handle(
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
		return sayWhat.handle(
			translation,
			single,
			plural,
			number,
			context,
			domain
		);
	},
	has_translation( result, single, context, domain ) {
		const swpHasReplacement =
			sayWhat.handle(
				single,
				single,
				single,
				undefined,
				context,
				domain
			) !== single;
		return result || swpHasReplacement;
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
		let compositeKey = domain + '|' + plural + '|' + context;
		// Revert to the single form if required.
		if ( number === undefined || number === 1 ) {
			compositeKey = domain + '|' + single + '|' + context;
		}

		/**
		 * Look for replacements, and use them if configured.
		 */
		if ( typeof window.say_what_data.replacements[ compositeKey ] !== 'undefined' ) {
			return window.say_what_data.replacements[ compositeKey ];
		}

		// No replacement. Return the value unchanged.
		return translation;
	},
};

/**
 * Attach filters.
 */
wp.hooks.addFilter( 'i18n.gettext', 'say-what', sayWhat.gettext, 99 );
wp.hooks.addFilter( 'i18n.ngettext', 'say-what', sayWhat.ngettext, 99 );
wp.hooks.addFilter(
	'i18n.gettext_with_context',
	'say-what',
	sayWhat.gettext_with_context,
	99
);
wp.hooks.addFilter(
	'i18n.ngettext_with_context',
	'say-what',
	sayWhat.ngettext_with_context,
	99
);
wp.hooks.addFilter(
	'i18n.has_translation',
	'say-what',
	sayWhat.has_translation,
	99
);
