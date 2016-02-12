<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://danieljblumenfeld.com/rrr-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class DJB_RRR {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      DJB_RRR_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'djb-rrr';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
        $this->register_custom_post_types();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - DJB_RRR_Loader. Orchestrates the hooks of the plugin.
	 * - DJB_RRR_i18n. Defines internationalization functionality.
	 * - DJB_RRR_Admin. Defines all hooks for the admin area.
	 * - DJB_RRR_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djb-rrr-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-djb-rrr-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-djb-rrr-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-djb-rrr-public.php';

		$this->loader = new DJB_RRR_Loader();

	}

    /**
	 * Register the route post type
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function register_route_post_type() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/routes/class-djb-rrr-route-post-type.php';
        $route_post_registration = new Route_Post_Type;
        $route_post_registration->init();

        //Add the default route type
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/routes/class-djb-rrr-route-type-general.php';
        $route_type_general = new Route_Type_General(); //The constructor will handle hooking up actions and filters as needed

        //Add the RWGPS route type
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/routes/class-djb-rrr-route-type-RWGPS.php';
        $route_type_RWGPS = new Route_Type_RWGPS(); //The constructor will handle hooking up actions and filters as needed
        
        //Add the Garmin route type
       require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/routes/class-djb-rrr-route-type-Garmin.php';
       $route_type_Garmin = new Route_Type_Garmin(); //The constructor will handle hooking up actions and filters as needed

        if( is_admin() ){
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/routes/class-djb-rrr-route-metabox.php';
            $route_meta_helper = new Route_Metabox_Helper();
        }
    }
    
    /**
	 * Register the ride post type
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function register_ride_post_type() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/rides/class-djb-rrr-ride-post-type.php';
        $ride_post_registration = new Ride_Post_Type;
        $ride_post_registration->init();
    }
    
    /**
	 * Register the report post type
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function register_report_post_type() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/reports/class-djb-rrr-report-post-type.php';
        $report_post_registration = new Report_Post_Type;
        $report_post_registration->init();
    }
    
    /**
	 * Register the custom post types used by this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function register_custom_post_types() {
        $this->register_route_post_type();
        $this->register_ride_post_type();
        $this->register_report_post_type();
    }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new DJB_RRR_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new DJB_RRR_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new DJB_RRR_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    DJB_RRR_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
