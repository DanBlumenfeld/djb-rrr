<?php
/**
 * The file that defines a route based on the RideWithGPS.com website
 * 
 *
 * @link       http://ridewithgps.com/
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 */

 /**
 * The core route class.
 *
 * This is used to provide RWGPS-specific data on request
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class Route_Type_RWGPS {
    
    /**
	 * Register actions, hooks, and shortcodes
	 * 
	 */
	public function __construct() {
        add_action('djb-rrr-save-route', array( $this, 'save'), 10, 2);

		add_filter('djb-rrr-route-type-data', array($this, 'add_rwgps_route_type_data'), 10, 3);
        add_filter('djb-rrr-render-route-summary', array( $this, 'render_route_summary'), 10, 2);
        add_filter('djb-rrr-render-route-details', array( $this, 'render_route_details'), 10, 2);

        //Note different prefix ('dbj-rwgps-' versus 'djb-rrr-'; this is to facilitate use of the generic RWGPS shortcodes outside of the RCubed plugin
        //At some point, this class might be extracted into a RWGPS utility plugin
        add_shortcode('djb-rwgps-elevation', array($this,'elevation_img_shortcode'));
        add_shortcode('djb-rwgps-map', array($this,'map_img_shortcode'));        
        add_shortcode('djb-rwgps-link', array($this,'route_link_shortcode'));  
        add_shortcode('djb-rwgps-cuesheet', array($this,'cuesheet_link_shortcode'));
        add_shortcode('djb-rwgps-gpx', array($this,'gpx_link_shortcode'));
        add_shortcode('djb-rwgps-tcx', array($this,'tcx_link_shortcode'));
	}

    /**
	 * Add RWGPS-specific metabox data
	 * 
	 */
    function add_rwgps_route_type_data($route_types, $post_id, $currType) {     
        
        $currId = get_post_meta( $post_id, '_djb_rrr_rwgps_id', true );   
        
        $default_route_type = new Route_Type_Data();
        $default_route_type->type_id ='RWGPS';
        $hideStyle = 'style="display: none"';
        if($currType === $default_route_type->type_id)
        {
            $hideStyle = "";
        }
        $default_route_type->type_friendly_name = 'Ride With GPS';
        $default_route_type->general_metabox_markup = '';
        $default_route_type->type_specific_markup = sprintf('<div %s class="hideable_route_type_data" data-route-type="%s">RWGPS Route ID:<input type="text" id="djb_rrr_rwgps_id_val" name="djb_rrr_rwgps_id_val" value="%s" /></div>', $hideStyle, $default_route_type->type_id, $currId);
        $route_types[] = $default_route_type;
        
        return $route_types;
    }

    /**
	 * Save RWGPS-specific data to the database
	 * 
	 */
    function save( $post_id, $post ) {
        
        // Sanitize the user input.
		$rwgps_id = sanitize_text_field( $_POST['djb_rrr_rwgps_id_val'] );

		// Update the meta field.
		update_post_meta( $post_id, '_djb_rrr_rwgps_id', $rwgps_id );

    }
    
    /**
	 * Render route summary information
	 * 
	 */
    function render_route_summary($output, $post_id) {
        $currType = get_post_meta( $post_id, '_djb_rrr_route_type_id', true );
        if($currType === 'RWGPS'){            
            //TODO: render any general information?
            //$output .= '<div>RWGPS Summary placeholder</div>';
        }
        return $output;
    }

    /**
	 * Render detailed route information: links to RWGPS site and cue sheet, map, and elevation profile
	 * 
	 */
    function render_route_details($output, $post_id) {                
        $currType = get_post_meta( $post_id, '_djb_rrr_route_type_id', true );
        if($currType === 'RWGPS'){            
            $currId = get_post_meta( $post_id, '_djb_rrr_rwgps_id', true );
            if(empty($currId)){
                $output .= '<!-- No RWGPS ID -->';
            }
            else {
                //Link
                $shortcode = sprintf('[djb-rwgps-link route=%s]', $currId);
                $output .= sprintf('<span class="dbj-rrr-link">%s</span>', do_shortcode($shortcode));
                //Cue sheet
                $shortcode = sprintf('[djb-rwgps-cuesheet route=%s]', $currId);
                $output .= sprintf(' - <span class="dbj-rrr-link">%s</span>', do_shortcode($shortcode));
                //GPX
                $shortcode = sprintf('[djb-rwgps-gpx route=%s]', $currId);
                $output .= sprintf(' - <span class="dbj-rrr-link">%s</span>', do_shortcode($shortcode));
                //TCX
                $shortcode = sprintf('[djb-rwgps-tcx route=%s]', $currId);
                $output .= sprintf(' - <span class="dbj-rrr-link">%s</span>', do_shortcode($shortcode));
                //Map thumbnail
                $shortcode = sprintf('[djb-rwgps-map route=%s]', $currId);
                $output .= sprintf('<div class="dbj-rrr-map">%s</div>', do_shortcode($shortcode));
                //Elevation Profile
                $shortcode = sprintf('[djb-rwgps-elevation route=%s]', $currId);
                $output .= sprintf('<div class="dbj-rrr-elevation">%s</div>', do_shortcode($shortcode));
            }
        }
        return $output;
    }

   /**************************************************************************/
   /* Mapping Shortcodes                                                     */ 
   /**************************************************************************/ 

    /**
	 * Embed the elevation profile image for the supplied route id.
	 * [djb-rwgps-elevation route=1234567], resolves to <img src="http://ridewithgps.com/routes/1234567/elevation_profile" />
	 */
    function elevation_img_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'elevation'));

        return sprintf('<img style="-webkit-user-select: none;" src="http://ridewithgps.com/routes/%1$s/elevation_profile"  alt="Elevation Profile" />', $route);
    }
    
   /**
	 * Embed the map image for the supplied route id.
	 * [djb-rwgps-map route=1234567], resolves to <img src="http://ridewithgps.com/routes/1234567/full.png" />
	 */
   function map_img_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<img style="-webkit-user-select: none;" src="http://ridewithgps.com/routes/%1$s/full.png"  alt="Route Image" />', $route);
    }

     /**
	 * Embed a link to the RWGPS website for the supplied route id.
	 * [djb-rwgps-link route=1234567], resolves to <a href="http://ridewithgps.com/routes/1234567">Full Map</a>
	 */
    function route_link_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<a href="http://ridewithgps.com/routes/%s">Full Map</a>', $route);
    }
    
    /**
	 * Embed a link to the RWGPS website cue sheet for the supplied route id.
	 * [djb-rwgps-cuesheet route=1234567], resolves to <a href="http://ridewithgps.com/routes/1234567/cue_sheet">Cue Sheet</a>
	 */
    function cuesheet_link_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<a href="http://ridewithgps.com/routes/%s/cue_sheet">Cue Sheet</a>', $route);
    }
    
    /**
	 * Embed a link to the RWGPS website GPX track for the supplied route id.
	 * [djb-rwgps-gpx route=1234567], resolves to <a href="http://ridewithgps.com/routes/1234567.gpx?sub_format=track">GPX</a>
	 */
    function gpx_link_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<a href="http://ridewithgps.com/routes/%s.gpx?sub_format=track">GPX</a>', $route);
    }

    /**
	 * Embed a link to the RWGPS website TCX track for the supplied route id.
	 * [djb-rwgps-tcx route=1234567], resolves to <a href="http://ridewithgps.com/routes/1234567.tcx">TCX</a>
	 */
    function tcx_link_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<a href="http://ridewithgps.com/routes/%s.tcx">TCX</a>', $route);
    }
}
