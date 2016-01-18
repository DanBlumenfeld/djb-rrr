<?php

class Route_Type_General {
    
	public function __construct() {
		add_filter('djb-rrr-route-type-data', array($this, 'add_general_route_type_data'), 10, 3 );
        add_action('djb-rrr-save-route', array( $this, 'save'), 10, 2);        
        add_filter('djb-rrr-render-route-summary', array( $this, 'render_route_summary'), 10, 2);
	}

    function add_general_route_type_data($route_types, $post_id, $currType) {     
           
        $curr_distance = get_post_meta( $post_id, '_djb_rrr_route_distance', true ); 

        $default_route_type = new Route_Type_Data();
        $default_route_type->type_id ='';
        $default_route_type->type_friendly_name = 'General';
        $default_route_type->general_metabox_markup = sprintf('<td>Distance:</td><td><input type="text" id="djb_rrr_distance_val" name="djb_rrr_distance_val" value="%s" /></td>', $curr_distance);
        $default_route_type->type_specific_markup = '';
        $route_types[] = $default_route_type;
        
        return $route_types;
    }

     function save( $post_id, $post ) {
        // Sanitize the user input.
		$distance = sanitize_text_field( $_POST['djb_rrr_distance_val'] );
		// Update the meta field.
		update_post_meta( $post_id, '_djb_rrr_route_distance', $distance );
    }

    function render_route_summary($output, $post_id) {        
        //Always render general information
        $output .= '<div>General route summary placeholder</div>';
        return $output;
    }
}
