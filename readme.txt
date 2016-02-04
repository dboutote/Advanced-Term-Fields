=== WP Bootstrap Comments ===
Contributors: dbmartin
Tags: comments, bootstrap
Requires at least: 2.7
Tested up to: 4.4.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Nested Bootstrap (v3) comments for WordPress.

== Description ==

A comment Walker class that creates native WordPress comment lists using Bootstrap Media Object markup/classes. See: http://getbootstrap.com/components/#media

For support and official documentation see the plugin's home page here: http://darrinb.com/plugins/wp-bootstrap-comments

== Installation ==

= From the WordPress.org plugin repository: =

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

= From GitHub: =

* Download the [latest stable version] (https://github.com/dboutote/WP-Bootstrap-Comments/archive/master.zip).
* Extract the zip folder to your plugins directory.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.


== Usage ==

Add a call to the `WP_Bootstrap_Comments_Walker()` class in `wp_list_comments()` in your `comments.php` template.

`
<?php
   wp_list_comments( array(
      'style'       => 'div',
      'short_ping'  => true,
      'avatar_size' => 42,
      'walker' => new WP_Bootstrap_Comments_Walker(),
   ) );
?>
`

To use Bootstrap's native media list styling change `<ol class="comment-list">` to `<ul class="media-list">`.

`
<ul class="media-list">
    <?php
        wp_list_comments( array(
            'style'       => 'ul',
            'short_ping'  => true,
            'avatar_size' => 42,
            'walker' => new WP_Bootstrap_Comments_Walker(),
        ) );
    ?>
</ul><!-- .media-list -->
`


== Frequently Asked Questions ==

= Where can I get support? =

The plugin's official page: http://darrinb.com/plugins/wp-bootstrap-comments

= Where can I find documentation? =

http://darrinb.com/plugins/wp-bootstrap-comments

== Changelog ==

= 0.1.0 =
* Initial release
