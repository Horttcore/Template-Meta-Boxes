Template Meta Boxes
===================

## Description

Display meta boxes depending on the selected template

## Installation

* I recommend to ship this file within your template as an include
* Or put the plugin file in your plugin directory and activate it in your WP backend.

## Usage

* Register any meta box as usual with the WordPress API ( [Codex](http://codex.wordpress.org/Function_Reference/add_meta_box) )
* Add or remove meta boxes in your `functions.php`

### Remove the post thumbnail meta box for the default page template

<pre class="language-php"><code class="language-php">
<?php
// Get singleton instance
$tpl = Template_Meta_Boxes::get_instance();

// Parameter for adding meta boxes
// add_meta_box ( string|array $template_name , string $meta_box_id [, string $post_type [, string $context ]] )

// Adding meta boxes only on a specific template
$tpl->add_meta_box( 'slideshow.php', 'slideshow-meta-box', 'page' ); // Add post thumbnail meta box

// Parameter for removing meta boxes
// remove_meta_box ( string|array $template_name , string $meta_box_id [, string $post_type [, string $context ]] )

// Removing meta boxes from default template
$tpl->remove_meta_box( 'default', 'postimagediv', 'page' ); // Remove post thumbnail meta box
$tpl->remove_meta_box( 'default', 'submitdiv', 'page' ); // Remove publishing meta box
$tpl->remove_meta_box( 'default', 'formatdiv', 'page' ); // Remove post formats meta box
$tpl->remove_meta_box( 'default', 'pageparentdiv', 'page' ); // Remove page attributes meta box
$tpl->remove_meta_box( 'default', 'revisionsdiv', 'page' ); // Remove revisions meta box
$tpl->remove_meta_box( 'default', 'attachment-id3', 'page' ); // Remove audio meta box
$tpl->remove_meta_box( 'default', 'postexcerpt', 'page' ); // Remove excerpt meta box
$tpl->remove_meta_box( 'default', 'trackbacksdiv', 'page' ); // Remove trackback meta box
$tpl->remove_meta_box( 'default', 'postcustom', 'page' ); // Remove custom meta meta box
$tpl->remove_meta_box( 'default', 'commentstatusdiv', 'page' ); // Remove comment status meta box
$tpl->remove_meta_box( 'default', 'commentsdiv', 'page' ); // Remove comments meta box
$tpl->remove_meta_box( 'default', 'slugdiv', 'page' ); // Remove slug meta box
$tpl->remove_meta_box( 'default', 'authordiv', 'page' ); // Remove author meta box

// Removing meta box from specific template
$tpl->remove_meta_box( 'showcase.php', 'postimagediv', 'page' ); // Remove post thumbnail meta box on showcase template

// Removing meta box for every post type
$tpl->remove_meta_box( 'default', 'postimagediv' ); // Remove post thumbnail meta box everywhere

// Advanced removing
$tpl->remove_meta_box( array( 'post_parent' => 0 ), 'postimagediv', 'page' ); // Remove author meta box on first level pages
$tpl->remove_meta_box( array( 'post_parent' => 0, 'post_author' => 1 ), 'postimagediv', array( 'page' ) ); // Remove author meta box on first level pages for author #1

// Adding meta boxes works the same way as removing them
?>
</code></pre>

## FAQ

### I changed the template but nothing happens

Did you save the post? As for the moment it's a php solution, but I might change it in a future to work instantly.

## Changelog

### v1.2

* Changed: `add_meta_box()` first parameter can be an array with post fields to check against
* Changed: `add_meta_box()` can handle multiple post_types if passed as an array - default is 'any' post type
* Changed: `add_meta_box()` No context needed for core meta boxes
* Changed: `remove_meta_box()` first parameter can be an array with post fields to check against
* Changed: `remove_meta_box()` can handle multiple post_types if passed as an array - default is 'any' post type
* Changed: `remove_meta_box()` No context needed for core meta boxes

### v1.1.1

* Code cleanup

### v1.1

* Use 'advanced' as default context

### v1.0

* Initial release
