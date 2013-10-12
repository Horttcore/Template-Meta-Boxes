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
	
	$Template_Boxes = Template_Meta_Boxes::get_instance();
	$Template_Boxes->remove_meta_box( 'default', 'postimagediv', 'page', 'side' );

### Add the post thumbnail meta box only for the default page template

	$Template_Boxes = Template_Meta_Boxes::get_instance();
	$Template_Boxes->add_meta_box( 'default', 'postimagediv', 'page', 'side' );
	
### What are the parameters?

	$Template_Boxes->add_meta_box( $template_name, $meta_box_id, $post_type, $context );
	$Template_Boxes->remove_meta_box( $template_name, $meta_box_id, $post_type, $context );
	
## FAQ

### I changed the template but nothing happens

Did you save the post? As for the moment it's a php solution, but I might change it in a future to work instantly.

## Changelog

### v1.1

* Use 'advanced' as default context

### v1.0

* Initial release
