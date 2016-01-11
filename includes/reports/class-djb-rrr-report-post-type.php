<?php
/**
 * The file that defines the report custom post type and related data
 *
 * 
 *
 * @link       https://github.com/devinsays/team-post-type
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/Reports
 */

 /**
 * The core Report class.
 *
 * This is used to register the Report post type and taxonomies
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/reports
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
 class Report_Post_Type {
     
	public $post_type = 'report';

	public $taxonomies = array( 'report-category' );

	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}
	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Report_Post_Type::register_post_type()
	 * @uses Report_Post_Type::register_taxonomy_category()
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
			'name' => __( 'Reports', 'post type general name', 'djb-rrr' ), 
            'singular_name' => __( 'Report', 'post type singular name', 'djb-rrr' ), 
            'menu_name' => __( 'Reports', 'admin menu', 'djb-rrr' ), 
            'name_admin_bar'=> __( 'Report', 'add new on admin bar', 'djb-rrr' ), 
            'add_new'   => __( 'Add New', 'Report', 'djb-rrr' ), 
            'add_new_item'=> __( 'Add New Report', 'djb-rrr' ), 
            'new_item'    => __( 'New Report', 'djb-rrr' ), 
            'edit_item'     => __( 'Edit Report', 'djb-rrr' ), 
            'view_item'   => __( 'View Report', 'djb-rrr' ), 
            'all_items'     => __( 'All Reports', 'djb-rrr' ), 
            'search_items'=> __( 'Search Reports', 'djb-rrr' ), 
            'parent_item_colon' => __( 'Parent Report:', 'djb-rrr' ), 
            'not_found'  => __( 'No reports found.', 'djb-rrr' ), 
            'not_found_in_trash' => __( 'No reports found in Trash.', 'djb-rrr' ),
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
            'rewrite'            => array( 'slug' => 'report' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
			'menu_position'   => 30,
		);
		$args = apply_filters( 'report_post_type_args', $args );
		register_post_type( $this->post_type, $args );
	}
	/**
	 * Register a taxonomy for Ride Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( 'Report Categories', 'djb-rrr' ),
			'singular_name'              => __( 'Report Category', 'djb-rrr' ),
			'menu_name'                  => __( 'Report Categories', 'djb-rrr' ),
			'edit_item'                  => __( 'Edit Report Category', 'djb-rrr' ),
			'update_item'                => __( 'Update Report Category', 'djb-rrr' ),
			'add_new_item'               => __( 'Add New Report Category', 'djb-rrr' ),
			'new_item_name'              => __( 'New Report Category Name', 'djb-rrr' ),
			'parent_item'                => __( 'Parent Report Category', 'djb-rrr' ),
			'parent_item_colon'          => __( 'Parent Report Category:', 'djb-rrr' ),
			'all_items'                  => __( 'All Report Categories', 'djb-rrr' ),
			'search_items'               => __( 'Search Report Categories', 'djb-rrr' ),
			'popular_items'              => __( 'Popular Report Categories', 'djb-rrr' ),
			'separate_items_with_commas' => __( 'Separate Report categories with commas', 'djb-rrr' ),
			'add_or_remove_items'        => __( 'Add or remove report categories', 'djb-rrr' ),
			'choose_from_most_used'      => __( 'Choose from the most used report categories', 'djb-rrr' ),
			'not_found'                  => __( 'No report categories found.', 'djb-rrr' ),
		);
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'report-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);
		$args = apply_filters( 'report_post_type_category_args', $args );
		register_taxonomy( $this->taxonomies[0], $this->post_type, $args );
	}
}    

