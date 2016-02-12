<?php
/**
 * The file that defines the route custom post type and related data
 *
 * 
 *
 * @link       https://github.com/devinsays/team-post-type
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 */

 /**
 * The core route class.
 *
 * This is used to register the route post type, taxonomies, and related data
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
 class Route_Post_Type {
     
	public $post_type = 'route';

	public $taxonomies = array( 'route-category' );

	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Route_Post_Type::register_post_type()
	 * @uses Route_Post_Type::register_taxonomy_category()
	 */
	public function register() {
		$this->register_post_type();
		$this->register_taxonomy_category();
		$this->register_shortcodes();
	}
	/**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name' => __( 'Routes', 'post type general name', 'djb-rrr' ), 
            'singular_name' => __( 'Route', 'post type singular name', 'djb-rrr' ), 
            'menu_name' => __( 'Routes', 'admin menu', 'djb-rrr' ), 
            'name_admin_bar'=> __( 'Route', 'add new on admin bar', 'djb-rrr' ), 
            'add_new'   => __( 'Add New', 'route', 'djb-rrr' ), 
            'add_new_item'=> __( 'Add New Route', 'djb-rrr' ), 
            'new_item'    => __( 'New Route', 'djb-rrr' ), 
            'edit_item'     => __( 'Edit Route', 'djb-rrr' ), 
            'view_item'   => __( 'View Route', 'djb-rrr' ), 
            'all_items'     => __( 'All Routes', 'djb-rrr' ), 
            'search_items'=> __( 'Search Routes', 'djb-rrr' ), 
            'parent_item_colon' => __( 'Parent Route:', 'djb-rrr' ), 
            'not_found'  => __( 'No routes found.', 'djb-rrr' ), 
            'not_found_in_trash' => __( 'No routes found in Trash.', 'djb-rrr' ),
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
            'rewrite'            => array( 'slug' => 'route' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
			'menu_position'   => 30,
		);
		$args = apply_filters( 'route_post_type_args', $args );
		register_post_type( $this->post_type, $args );
	}
	/**
	 * Register a taxonomy for Ride Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( 'Route Categories', 'djb-rrr' ),
			'singular_name'              => __( 'Route Category', 'djb-rrr' ),
			'menu_name'                  => __( 'Route Categories', 'djb-rrr' ),
			'edit_item'                  => __( 'Edit Route Category', 'djb-rrr' ),
			'update_item'                => __( 'Update Route Category', 'djb-rrr' ),
			'add_new_item'               => __( 'Add New Route Category', 'djb-rrr' ),
			'new_item_name'              => __( 'New Route Category Name', 'djb-rrr' ),
			'parent_item'                => __( 'Parent Route Category', 'djb-rrr' ),
			'parent_item_colon'          => __( 'Parent Route Category:', 'djb-rrr' ),
			'all_items'                  => __( 'All Route Categories', 'djb-rrr' ),
			'search_items'               => __( 'Search Route Categories', 'djb-rrr' ),
			'popular_items'              => __( 'Popular Route Categories', 'djb-rrr' ),
			'separate_items_with_commas' => __( 'Separate route categories with commas', 'djb-rrr' ),
			'add_or_remove_items'        => __( 'Add or remove route categories', 'djb-rrr' ),
			'choose_from_most_used'      => __( 'Choose from the most used route categories', 'djb-rrr' ),
			'not_found'                  => __( 'No route categories found.', 'djb-rrr' ),
		);
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'route-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);
		$args = apply_filters( 'route_post_type_category_args', $args );
		register_taxonomy( $this->taxonomies[0], $this->post_type, $args );
	}

    protected function register_shortcodes() {
        add_shortcode('route_summary', array($this, 'display_route_summary'));
        add_shortcode('route_details', array($this, 'display_route_details'));
    }

    function display_route_summary($atts, $content = NULL) {
        global $post;
        $output = '<div class="djb-rrr-route-summary">';
        $output = apply_filters('djb-rrr-render-route-summary', $output, $post->ID);
        $output .= '</div>';
        return $output;
    }

    function display_route_details($atts, $content = NULL) {
        global $post;
        $output = '';
        $output = apply_filters('djb-rrr-render-route-details', $output, $post->ID);
        return $output;
    }
    
}    


 /**
 * Data specific to a type of Route
 *
 * This is used to define the data for a given route type.
 * It includes:
 *    the unique type id (example: rwgps, for the RideWithGPS route type)
 *    the friendly/localized name of the type for display (example: 'RideWithGPS')
 *    any markup to be added to the metabox for ALL route types (example: ride distance)
 *    any markup to be added to the metabox only for routes of this type (example: in a RideWithGPS route, we need the id of the route from RideWithGPS.com)
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class Route_Type_Data {

    public $type_id;
    public $type_friendly_name;
    public $general_metabox_markup;
    public $type_specific_metabox_markup;
    public $is_route_provider;
        
}