<?php
/**
 * The file that defines the ride post type and related data
 *
 * 
 *
 * @link       https://github.com/devinsays/team-post-type
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/rides
 */

 /**
 * The core ride class.
 *
 * This is used to register the ride post type and taxonomies
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/rides
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
 class Ride_Post_Type {
     
	public $post_type = 'ride';

	public $taxonomies = array( 'ride-category' );

	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}
	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses ride_Post_Type::register_post_type()
	 * @uses ride_Post_Type::register_taxonomy_category()
	 */
	public function register() {
		$this->register_post_type();
		$this->register_taxonomy_category();
	}
	/**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name' => __( 'Rides', 'post type general name', 'djb-rrr' ), 
            'singular_name' => __( 'Ride', 'post type singular name', 'djb-rrr' ), 
            'menu_name' => __( 'Rides', 'admin menu', 'djb-rrr' ), 
            'name_admin_bar'=> __( 'Ride', 'add new on admin bar', 'djb-rrr' ), 
            'add_new'   => __( 'Add New', 'ride', 'djb-rrr' ), 
            'add_new_item'=> __( 'Add New Ride', 'djb-rrr' ), 
            'new_item'    => __( 'New Ride', 'djb-rrr' ), 
            'edit_item'     => __( 'Edit Ride', 'djb-rrr' ), 
            'view_item'   => __( 'View Ride', 'djb-rrr' ), 
            'all_items'     => __( 'All Rides', 'djb-rrr' ), 
            'search_items'=> __( 'Search Rides', 'djb-rrr' ), 
            'parent_item_colon' => __( 'Parent Ride:', 'djb-rrr' ), 
            'not_found'  => __( 'No rides found.', 'djb-rrr' ), 
            'not_found_in_trash' => __( 'No rides found in Trash.', 'djb-rrr' ),
		);
		$supports = array(
			'title',
			'editor',
			'thumbnail',
            'excerpt',
			'custom-fields',
			'revisions',
            'comments',
		);
		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'ride' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
			'menu_position'   => 30,
		);
		$args = apply_filters( 'ride_post_type_args', $args );
		register_post_type( $this->post_type, $args );
	}
	/**
	 * Register a taxonomy for Ride Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( 'Ride Categories', 'djb-rrr' ),
			'singular_name'              => __( 'Ride Category', 'djb-rrr' ),
			'menu_name'                  => __( 'Ride Categories', 'djb-rrr' ),
			'edit_item'                  => __( 'Edit Ride Category', 'djb-rrr' ),
			'update_item'                => __( 'Update Ride Category', 'djb-rrr' ),
			'add_new_item'               => __( 'Add New Ride Category', 'djb-rrr' ),
			'new_item_name'              => __( 'New Ride Category Name', 'djb-rrr' ),
			'parent_item'                => __( 'Parent Ride Category', 'djb-rrr' ),
			'parent_item_colon'          => __( 'Parent Ride Category:', 'djb-rrr' ),
			'all_items'                  => __( 'All Ride Categories', 'djb-rrr' ),
			'search_items'               => __( 'Search Ride Categories', 'djb-rrr' ),
			'popular_items'              => __( 'Popular Ride Categories', 'djb-rrr' ),
			'separate_items_with_commas' => __( 'Separate ride categories with commas', 'djb-rrr' ),
			'add_or_remove_items'        => __( 'Add or remove ride categories', 'djb-rrr' ),
			'choose_from_most_used'      => __( 'Choose from the most used ride categories', 'djb-rrr' ),
			'not_found'                  => __( 'No ride categories found.', 'djb-rrr' ),
		);
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'ride-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);
		$args = apply_filters( 'ride_post_type_category_args', $args );
		register_taxonomy( $this->taxonomies[0], $this->post_type, $args );
	}
}    

