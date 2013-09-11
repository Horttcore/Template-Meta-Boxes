Template-Meta-Boxes
===================

## Description

Display meta boxes depending on the selected template

## Installation

* I recommend to ship this file within your template as an include
* Or put the plugin file in your plugin directory and activate it in your WP backend.

## Usage

* Register any meta box as usual with the WorPress API ( http://codex.wordpress.org/Function_Reference/add_meta_box )
* Add or remove meta boxes in your `functions.php`

### Remove the post thumbnail meta box for the default page template

`$Template_Boxes = Template_Meta_Boxes::get_instance();
$Template_Boxes->remove_meta_box( 'default', 'postimagediv', 'page', 'side' );`

### Add the post thumbnail meta box only for the default page template

`$Template_Boxes = Template_Meta_Boxes::get_instance();
$Template_Boxes->add_meta_box( 'default', 'postimagediv', 'page', 'side' );`