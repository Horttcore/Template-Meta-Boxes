<?php
/*
Plugin Name: Template Meta Boxes
Plugin URI: http://horttcore.de
Description: Display meta boxes depending on the selected template
Version: 1.1.1
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/



// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}



/**
* Template Meta Boxes
*/
class Template_Meta_Boxes
{



	/** Refers to a single instance of this class. */
	private static $instance = null;

	/** Stores added meta boxes */
	private $add_meta_boxes = array();

	/** Stores removed meta boxes */
	private $remove_meta_boxes = array();



	/**
	 * Contstructor
	 *
	 * @access public
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function __construct()
	{

		add_action( 'do_meta_boxes', array( $this, 'do_meta_boxes' ) );

	} // end __construct



	/**
	 * Add a meta box for a special template
	 *
	 * @access public
	 * @param str $template_name Template name
	 * @param str $meta_box_id Meta Box ID
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function add_meta_box( $template_name, $meta_box_id, $page, $context = 'advanced' )
	{

		$this->add_meta_boxes[$template_name][] = array( 'id' => $meta_box_id, 'page' => $page, 'context' => $context );

	} // end add_meta_box



	/**
	 * Filter to add a certain meta box only for a specific template
	 *
	 * @access protected
	 * @param str $current_template Current template
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	protected function add_template_meta_boxes( $current_template )
	{

		global $wp_meta_boxes, $post;

		if ( !$wp_meta_boxes || empty( $this->add_meta_boxes ) || !$post )
			return;

		foreach ( $this->add_meta_boxes as $template => $meta_boxes ) :

			foreach ( $meta_boxes as $key => $meta_box ) :

				if ( $current_template == $template || !isset( $wp_meta_boxes[$meta_box['page']][$meta_box['context']] ) )
					break;

				if (
						isset( $wp_meta_boxes[$meta_box['page']][$meta_box['context']]['core'][$meta_box['id']] ) ||
						isset( $wp_meta_boxes[$meta_box['page']][$meta_box['context']]['default'][$meta_box['id']] ) ||
						isset( $wp_meta_boxes[$meta_box['page']][$meta_box['context']]['high'][$meta_box['id']] ) ||
						isset( $wp_meta_boxes[$meta_box['page']][$meta_box['context']]['low'][$meta_box['id']] )
				)
					remove_meta_box( $meta_box['id'], $meta_box['page'], $meta_box['context'] );

			endforeach;

		endforeach;

	} // end add_template_meta_boxes




	/**
	 * Filter the meta boxes
	 *
	 * @access public
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function do_meta_boxes()
	{

		global $post;

		$screen = get_current_screen();

		if ( !$screen || 'post' != $screen->base )
			return;

		$template_name = ( $post->ID ) ? $this->get_template( $post->ID ) : 'default';

		$this->remove_template_meta_boxes( $template_name);
		$this->add_template_meta_boxes( $template_name );

	} // end do_meta_boxes



	/**
	 * Creates or returns an instance of this class.
	 *
	 * @static
	 * @access public
	 * @return obj A single instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;

	} // end get_instance;



	/**
	 * Rempve a meta box for a special template
	 *
	 * @access protected
	 * @param int $post_id Post ID
	 * @return str Template ID
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	protected function get_template( $post_id )
	{

		return get_post_meta( $post_id, '_wp_page_template', TRUE );

	} // end get_template



	/**
	 * Rempve a meta box for a special template
	 *
	 * @access public
	 * @param str $template_name Template name
	 * @param str $meta_box_id Meta Box ID
	 * @param str $context Context
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function remove_meta_box( $template_name, $meta_box_id, $page, $context = 'advanced' )
	{

		$this->remove_meta_boxes[$template_name][] = array( 'id' => $meta_box_id, 'page' => $page, 'context' => $context );

	} // end remove_meta_box



	/**
	 * Remove meta boxes
	 *
	 * @access protected
	 * @param str $current_template Current template id
	 * @since v1.0
	 * @author Ralf Hortt
	 **/
	protected function remove_template_meta_boxes( $current_template )
	{

		if ( !$this->remove_meta_boxes || !isset( $this->remove_meta_boxes[$current_template] ) || empty( $this->remove_meta_boxes[$current_template] ) )
			return;

		foreach ( $this->remove_meta_boxes[$current_template] as $meta_box ) :

			remove_meta_box( $meta_box['id'], $meta_box['page'], $meta_box['context'] );

		endforeach;

	} // end remove_meta_boxes



} // end Template_Meta_Boxes
