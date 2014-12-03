<?php
/*
Plugin Name: Template Meta Boxes
Plugin URI: http://horttcore.de
Description: Display meta boxes depending on the selected template
Version: 1.2
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

		add_action( 'do_meta_boxes', array( $this, 'do_meta_boxes' ), 10, 3 );

	} // end __construct



	/**
	 * Add a meta box for a special template
	 *
	 * @access public
	 * @param str|array $conditions Conditions to check against
	 * @param str $meta_box_id Meta Box ID
	 * @param str|array $post_type Post type - default 'any'
	 * @param str $context Meta box context
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function add_meta_box( $conditions, $meta_box_id, $post_type = 'any', $context = FALSE )
	{

		$post_type = ( 'any' == $post_type ) ? get_post_types() : $post_type;
		$post_type = ( !is_array( $post_type ) ) ? array( $post_type ) : $post_type;
		$context = ( FALSE !== $context ) ? $context : $this->get_meta_box_context( $meta_box_id );

		if ( FALSE === $context )
			return;

		if ( is_array( $conditions ) && !empty( $conditions ) ) :
			$this->add_meta_boxes[$context][] = array( 'id' => $meta_box_id, 'post_type' => $post_type, 'conditions' => $conditions );
		else :
			$this->add_meta_boxes[$context][] = array( 'id' => $meta_box_id, 'post_type' => $post_type, 'conditions' => array( 'template' => $conditions ) );
		endif;

	} // end add_meta_box



	/**
	 * Filter to add a certain meta box only for a specific template
	 *
	 * @access protected
	 * @param str $post_type Post Type
	 * @param str $context Meta box context
	 * @param obj $post Post object
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	protected function add_template_meta_boxes( $post_type, $context, $post )
	{

		// Skip if wrong context
		if ( !isset( $this->add_meta_boxes[$context] ) || empty( $this->add_meta_boxes[$context] ) )
			return;

		// Loop through meta boxes
		foreach ( $this->add_meta_boxes[$context] as $meta_box ) :

			$remove = FALSE;

			// Remove if wrong post type
			if ( !in_array( $post_type, $meta_box['post_type'] ) )
				$remove = TRUE;

			// Remove if conditions fail
			foreach ( $meta_box['conditions'] as $key => $value ) :

				if ( 'template' == $key && $value != $this->get_template( $post->ID ) )
					$remove = TRUE;
				elseif ( 'post_format' == $key && $value != get_post_format( $post->ID ) ) 
					$remove = TRUE;
				elseif ( get_post_field( $key, $post->ID ) != $value )
					$remove = TRUE;

			endforeach;

			// Remove meta box
			if ( TRUE === $remove )
				remove_meta_box( $meta_box['id'], $post_type, $context );

		endforeach;

	} // end add_template_meta_boxes




	/**
	 * Filter the meta boxes
	 *
	 * @access public
	 * @param str $post_type Post type
	 * @param str $context Context
	 * @param obj $post Post object
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function do_meta_boxes( $post_type, $context, $post )
	{

		$screen = get_current_screen();

		if ( !$screen || 'post' != $screen->base )
			return;

		$this->remove_template_meta_boxes( $post_type, $context, $post );
		$this->add_template_meta_boxes( $post_type, $context, $post );

	} // end do_meta_boxes



	/**
	 * Creates or returns an instance of this class.
	 *
	 * @static
	 * @access public
	 * @return obj A single instance of this class.
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public static function get_instance() {

		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;

	} // end get_instance;



	/**
	 * Get meta box context
	 *
	 * @access protected
	 * @param str $meta_box_id Meta box ID
	 * @return str Meta box context
	 * @since v1.2
	 * @author Ralf Hortt
	 **/
	protected function get_meta_box_context( $meta_box_id )
	{

		if ( is_tax( str_replace( 'div', '', $meta_box_id ) ) || is_category( str_replace( 'div', '', $meta_box_id ) ) ||	is_tag( str_replace( 'div', '', $meta_box_id ) ) )
			$meta_box_id = 'taxonomy';

		switch ( $meta_box_id ) :

			case 'taxonomy' :
			case 'submitdiv' : 
			case 'formatdiv' : 
			case 'pageparentdiv' : 
			case 'postimagediv' : 
				$context = 'side';
				break;

			case 'revisionsdiv' : 
			case 'attachment-id3' : 
			case 'postexcerpt' : 
			case 'trackbacksdiv' : 
			case 'postcustom' : 
			case 'commentstatusdiv' : 
			case 'commentsdiv' : 
			case 'slugdiv' : 
			case 'authordiv' : 
				$context = 'normal';
				break;

			default :
				$context = FALSE;
				break;

		endswitch;

		return $context;

	} // end get_meta_box_context



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
	 * @param str/array $condition Conditions
	 * @param str $meta_box_id Meta Box ID
	 * @param str|array $post_type Post type - empty means all post types
	 * @param str $context Context
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	public function remove_meta_box( $conditions, $meta_box_id, $post_type = 'any', $context = FALSE )
	{

		$post_type = ( 'any' == $post_type ) ? get_post_types() : $post_type;
		$post_type = ( !is_array( $post_type ) ) ? array( $post_type ) : $post_type;
		$context = ( FALSE !== $context ) ? $context : $this->get_meta_box_context( $meta_box_id );

		if ( FALSE === $context )
			return;
		
		// Extended condition
		if ( is_array( $conditions ) && !empty( $conditions ) ) :
			$this->remove_meta_boxes[$context][] = array( 'id' => $meta_box_id, 'post_type' => $post_type, 'conditions' => $conditions );
		// Template condition
		else :
			$this->remove_meta_boxes[$context][] = array( 'id' => $meta_box_id, 'post_type' => $post_type, 'conditions' => array( 'template' => $conditions ) );
		endif;

	} // end remove_meta_box



	/**
	 * Remove meta boxes
	 *
	 * @access protected
	 * @param str $post_type Post Type
	 * @param str $context Meta box context
	 * @param obj $post Post object	
	 * @since v1.0
	 * @author Ralf Hortt
	 **/
	protected function remove_template_meta_boxes( $post_type, $context, $post )
	{

		// Skip if wrong context
		if ( !isset( $this->remove_meta_boxes[$context] ) || empty( $this->remove_meta_boxes[$context] ) )
			return;

		foreach ( $this->remove_meta_boxes[$context] as $meta_box ) :

			// Skip if wrong post type
			if ( !in_array( $post_type, $meta_box['post_type'] ) )
				continue;
			
			// Skip if there are no conditions
			if ( !is_array( $meta_box['conditions'] ) || empty( $meta_box['conditions'] ) )
				continue;

			// Skip if conditions fail
			$skip = FALSE;
		
			foreach ( $meta_box['conditions'] as $key => $value ) :

				if ( 'template' == $key && $value != $this->get_template( $post->ID ) )
					$skip = TRUE;
				elseif ( 'post_format' == $key && $value != get_post_format( $post->ID ) ) 
					$skip = TRUE;
				elseif ( get_post_field( $key, $post->ID ) != $value )
					$skip = TRUE;

			endforeach;

			if ( TRUE === $skip )
				continue;

			// Remove meta box
			remove_meta_box( $meta_box['id'], $post_type, $context );

		endforeach;

	} // end remove_meta_boxes



} // end Template_Meta_Boxes
