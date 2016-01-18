<?php

class Route_Type_RWGPS {
    
	public function __construct() {
		add_filter('djb-rrr-route-type-data', array($this, 'add_rwgps_route_type_data'), 10, 3);
        add_action('djb-rrr-save-route', array( $this, 'save'), 10, 2);
        add_filter('djb-rrr-render-route-summary', array( $this, 'render_route_summary'), 10, 2);
        add_filter('djb-rrr-render-route-details', array( $this, 'render_route_details'), 10, 2);
	}

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

    function save( $post_id, $post ) {
        
        // Sanitize the user input.
		$rwgps_id = sanitize_text_field( $_POST['djb_rrr_rwgps_id_val'] );

		// Update the meta field.
		update_post_meta( $post_id, '_djb_rrr_rwgps_id', $rwgps_id );

    }
    
    function render_route_summary($output, $post_id) {
        
        //TODO: render general information
        $output .= '<div>RWGPS Summary placeholder</div>';
        return $output;
    }

    function render_route_details($output, $post_id) {
        
        //TODO: render detail information: link to RWGPS, map, elevation, cue sheet
        $output .= '<div>RWGPS Detail placeholder</div>';
        return $output;
    }
    
}
