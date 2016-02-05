# Advanced Term Fields

A framework for managing custom meta fields for categories, tags, and custom taxonomies.

With the launch of version 4.4, WordPress added metadata capabilities for taxonomy terms.  Advanced Term Fields leverages this new capability by providing developers an easy-to-use, yet powerful framework for adding custom meta fields to taxonomy terms.

Through the use of hooks and filters, it's completely customizable for your project requirements, while also providing a standardized way of building a UI for managing term metadata.

Use it for tags, categories, even custom taxonomies!

![term admin](assets/screenshot.png?raw=true "Term Meta!")

# Installation

### From the WordPress.org plugin repository:

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

### From GitHub:

* Download the [latest stable version](https://github.com/dboutote/Advanced-Term-Fields/archive/master.zip).
* Extract the zip folder to your plugins directory.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

# Usage

This is a parent framework, meant to be extended by child classes.  See any one of the following for examples:

* [Advanced Term Fields: Colors](https://github.com/dboutote/Advanced-Term-Fields-Colors) Assign colors for categories, tags, and custom taxonomy terms.
* [Advanced Term Fields: Icons](https://github.com/dboutote/Advanced-Term-Fields-Icons) Assign dashicon icons for categories, tags, and custom taxonomy terms.
* [Advanced Term Fields: Featured Images](https://github.com/dboutote/Advanced-Term-Fields-Images) Assign featured images for categories, tags, and custom taxonomy terms.

# FAQ

### Where can I find additional additional documentation?

The plugin's official page: http://darrinb.com/advanced-term-fields

### Does this plugin depend on any others?

Nope!

### Does this create/modify/destroy database tables?

This leverages the term meta capabilities added in WordPress 4.4.  No database modifications needed!