<?php
/**
 * The file that defines a route based on a Garmin Connect course
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
 * This is used to provide Garmin-specific data on request
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class Route_Type_Garmin {
    
    /**
	 * Register actions, hooks, and shortcodes
	 * 
	 */
	public function __construct() {
        
        add_action('djb-rrr-save-route', array( $this, 'save'), 10, 2);

	    add_filter('djb-rrr-route-type-data', array($this, 'add_garmin_route_type_data'), 10, 3);
        add_filter('djb-rrr-render-route-summary', array( $this, 'render_route_summary'), 10, 2);
        add_filter('djb-rrr-render-route-details', array( $this, 'render_route_details'), 10, 2);

        //Note different prefix ('dbj-garmin-' versus 'djb-rrr-'; this is to facilitate use of the generic Garmin shortcodes outside of the RCubed plugin
        //At some point, this class might be extracted into a Garmin utility plugin
        add_shortcode('djb-garmin-map', array($this,'map_embed_shortcode'));        
        add_shortcode('djb-garmin-link', array($this,'route_link_shortcode'));  
        
	}

    /**
	 * Add Garmin-specific metabox data
	 * 
	 */
    function add_garmin_route_type_data($route_types, $post_id, $currType) {     
        
        $currId = get_post_meta( $post_id, '_djb_rrr_garmin_id', true );   
        
        $garmin_route_type = new Route_Type_Data();
        $garmin_route_type->type_id ='Garmin';
        $garmin_route_type->type_friendly_name = 'Garmin Connect';
        $garmin_route_type->is_route_provider = 'true';
        $hideStyle = 'style="display: none"';
        if($currType === $garmin_route_type->type_id)
        {
            $hideStyle = "";
        }
        $garmin_route_type->general_metabox_markup = '';
        $garmin_route_type->type_specific_markup = sprintf('<div %s class="hideable_route_type_data" data-route-type="%s">Garmin Course ID:<input type="text" id="djb_rrr_garmin_id_val" name="djb_rrr_garmin_id_val" value="%s" /></div>', $hideStyle, $garmin_route_type->type_id, $currId);
        $route_types[] = $garmin_route_type;
        
        return $route_types;
    }

    /**
	 * Save Garmin-specific data to the database
	 * 
	 */
    function save( $post_id, $post ) {
        
        // Sanitize the user input.
		$rwgps_id = sanitize_text_field( $_POST['djb_rrr_garmin_id_val'] );

		// Update the meta field.
		update_post_meta( $post_id, '_djb_rrr_garmin_id', $rwgps_id );

    }
    
    /**
	 * Render route summary information
	 * 
	 */
    function render_route_summary($output, $post_id) {
        $currType = get_post_meta( $post_id, '_djb_rrr_route_type_id', true );
        if($currType === 'Garmin'){            
            //TODO: render any general information?
            //$output .= '<div>RWGPS Summary placeholder</div>';
        }
        return $output;
    }

    /**
	 * Render detailed route information: link to Garmin site, embed Garmin course
	 * 
	 */
    function render_route_details($output, $post_id) {                
        $currType = get_post_meta( $post_id, '_djb_rrr_route_type_id', true );
        if($currType === 'Garmin'){            
            $currId = get_post_meta( $post_id, '_djb_rrr_garmin_id', true );
            if(empty($currId)){
                $output .= '<!-- No Garmin course ID -->';
            }
            else {
                //Link
                $shortcode = sprintf('[djb-garmin-link route=%s]', $currId);
                $output .= sprintf('<span class="dbj-rrr-link">%s</span>', do_shortcode($shortcode));
                //Embed map
                $shortcode = sprintf('[djb-garmin-map width=500 height=500 route=%s]', $currId);
                $output .= sprintf('<div class="dbj-rrr-map">%s</div>', do_shortcode($shortcode));
            }
        }
        return $output;
    }

   /**************************************************************************/
   /* Mapping Shortcodes                                                     */ 
   /**************************************************************************/ 

   /**
	 * Embed the map image for the supplied route id.
	 * [djb-garmin-map route=1234567 width=460 height=600], resolves to <iframe width="460" height="600" frameborder='0' src="https://connect.garmin.com/course/embed/1234567"></iframe>
	 */
   function map_embed_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
            'width' => '400',
            'height' => '300',
        ), $atts, 'map'));

        return sprintf('<iframe width="%s" height="%s" frameborder="0" src="https://connect.garmin.com/course/embed/%s"></iframe>', $width, $height, $route);
    }

     /**
	 * Embed a link to the Garmin website for the supplied route id.
	 * [djb-garmin-link route=1234567], resolves to <a href="https://connect.garmin.com/modern/course/1234567">Full Map</a>
	 */
    function route_link_shortcode($atts) {
        extract( shortcode_atts( array(
            'route' => '0000000',
        ), $atts, 'map'));

        return sprintf('<a href="https://connect.garmin.com/modern/course/%s">Full Map</a>', $route);
    }
}
