=== Say what? ===
Contributors: leewillis77
Donate link: http://www.leewillis.co.uk/wordpress-plugins/?utm_source=wordpress&utm_medium=www&utm_campaign=say-what
Tags: string, change, translation
Requires at least: 3.5
Tested up to: 4.5
Stable tag: 1.7.1

== Description ==
An easy-to-use plugin that allows you to alter strings on your site without editing WordPress core, or plugin code. Simply enter the current string, and what you want to replace it with and the plugin will automatically do the rest!

The plugin's available for forking and contribution over on [GitHub](https://github.com/leewillis77/say-what)

*Don't have the time/expertise to find strings in the code - check out [Say What Pro](https://plugins.leewillis.co.uk/downloads/say-what-pro/) which includes String Discovery letting you autocomplete the information you need for quick string replacements.*

== Installation ==

* Install it as you would any other plugin
* Activate it
* Head over to Tools &raquo; Text changes and configure some string replacements

== Frequently Asked Questions ==

= Can I use it to change any string? =
You can only use the plugin to translate strings which are marked for translation.

= How do I find the string to translate? =
You can either have a guess, or checkout the plugin in question's source code, translatable strings are generally wrapped in __(), _e(), _n(), or _x(), for example:

`$foo = __('This is a translatable string', 'plugin-domain');`

= Is there any support for importing replacements? =
"Say What?" has preliminary support for exporting, and importing replacements via [http://wp-cli.org/](WP-CLI). The following commands are currently
supported:
* export - Export all current string replacements.
* import - Import string replacements from a CSV file.
* list - Export all current string replacements. Synonym for 'export'.
* update - update string replacements from a CSV file.

See the [GitHub homepage](https://github.com/leewillis77/say-what) for examples.

== Screenshots ==

1. Finding a string to replace
2. The admin screen - setting up a replacement
3. The result


== Changelog ==

= 1.7.1 =
* Update to admin marketing message. No functional changes.

= 1.7 =
* Support for _n() and _nx()
* Support for multi-line strings

= 1.6 =
Introduce filters that allows back compatibility for plugins that change their text-domain. Props Pippin Williamson

= 1.5 =
Avoid warnings on initial activation.
Avoid issues where strings contain HTML / entities

= 1.4 =
Add info box about Pro version

= 1.3 =
Support for WP-CLI import and export.

= 1.2 =
Swap database to UTF-8 to fix problems entering non-ASCII strings.

= 1.1 =
Fix incorrect escaping on the admin screens.

= 1.0.1 =
Fix initial DB table creation
Fix translations for strings with no domain

= 1.0 =
Allow strings with context to be replaced

= 0.9.3 =
Documentation improvements

= 0.9.2 =
Avoid wpdb->prepare warning
Minor admin fixes, don't double translate strings

= 0.9.1 =
Fix issue with fields being swapped when first entered

= 0.9 =
Beta ready for testing and feedback
