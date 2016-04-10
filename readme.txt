=== Advanced Term Fields ===
Contributors: dbmartin
Tags: termmeta, term_meta, term, meta, metadata, taxonomy
Requires at least: 4.4
Tested up to: 4.4.1
Stable tag: 0.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A framework for managing custom term meta for categories, tags, and custom taxonomies.

== Description ==

With the launch of version 4.4, WordPress added metadata capabilities for taxonomy terms.  **Advanced Term Fields** leverages this new capability by providing developers an easy-to-use, yet powerful framework for adding custom meta fields to taxonomy terms.

Through the use of hooks and filters, it's completely customizable for your project requirements, while also providing a standardized way of building a UI for managing term metadata.

Use it for tags, categories, even custom taxonomies.  Advanced term meta, your way!

= Usage =

This is a parent framework, meant to be extended by child classes.  See any one of the following extensions for examples:

* [Advanced Term Fields: Colors](https://wordpress.org/plugins/advanced-term-fields-colors/) Color-code your terms!
* [Advanced Term Fields: Icons](https://wordpress.org/plugins/advanced-term-fields-icons/) Icons for categories, tags, and custom taxonomy terms.
* [Advanced Term Fields: Featured Images](https://wordpress.org/plugins/advanced-term-fields-featured-images/) Featured images for terms!


== Installation ==

= From the WordPress.org plugin repository: =

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

= From GitHub: =

* Download the [latest stable version](https://github.com/dboutote/Advanced-Term-Fields/archive/master.zip).
* Extract the zip folder to your plugins directory.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.


== Frequently Asked Questions ==

= Where can I find documentation? =

The plugin's official page: http://darrinb.com/advanced-term-fields

= Does this plugin depend on any others? =

Nope!

= Does this create/modify/destroy database tables? =

This leverages the term meta capabilities added in WordPress 4.4.  No database modifications needed!

== Screenshots ==

1. Accessible from the Quick Edit form
2. Shown with color picker extension.
3. Shown with icons extension.
4. Shown with featured image extension.


== Changelog ==

= 0.1.2 =
* WP 4.5 Compatibility updates: added 'load-term.php' action hook.
* Bug fix: on quick edit form when $taxonomy is not defined.

= 0.1.1 =
* Added `$meta_slug` property for localizing js files and HTML attributes for form fields.
* Added check for update functionaliy to test for latest version.

= 0.1.0 =
* Initial release
