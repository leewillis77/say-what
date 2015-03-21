# Say what?
## An easy-to-use plugin that allows you to alter strings on your site without editing WordPress core, or plugin code. Simply enter the current string, and what you want to replace it with and the plugin will automatically do the rest!

### Installation
* Install it as you would any other plugin
* Activate it
* Head over to Tools &raquo; Text changes and configure some string replacements

Note: This plugin may produce performance issues if you configure a *large* number of string translations. I'd be interested in feedback if that's the case.

### Frequently Asked Questions

#### Can I use it to change any string?
You can only use the plugin to translate strings which are marked for translation.

#### How do I find the string to translate?
You can either have a guess, or checkout the plugin in question's source code, translatable strings are generally wrapped in __(), _e(), or _x(), for example:

$foo = __('This is a translatable string', 'plugin-domain');

### WP-CLI Support

"Say What?" has preliminary support for exporting, and importing replacements via [http://wp-cli.org/](WP-CLI). The following commands are currently
supported:
* export - Export all current string replacements.
* import - Import string replacements from a CSV file.
* list - Export all current string replacements. Synonym for 'export'.
* update - update string replacements from a CSV file.

Examples:
```
$ wp say-what export
+-----------+-------------+--------+--------------------+---------+
| string_id | orig_string | domain | replacement_string | context |
+-----------+-------------+--------+--------------------+---------+
| 3         | Tools       |        | Yada dada tools!   |         |
+-----------+-------------+--------+--------------------+---------+
```

```
$ wp say-what import import-file.csv
Success: 27 new items created.
```

```
$ wp say-what update update-file.csv
Success: 14 records updated, 19 new items created.
```




### Changelog

#### 1.2
Swap database to UTF-8 to fix problems entering non-ASCII strings.

#### 1.1
Fix incorrect escaping on the admin screens.

#### 1.0.1
Fix initial DB table creation
Fix translations for strings with no domain

#### 1.0
Allow strings with context to be replaced

#### 0.9.3
Documentation fixes, and help information

#### 0.9.1
Fix issue with fields being swapped when first entered

#### 0.9
Beta ready for testing and feedback