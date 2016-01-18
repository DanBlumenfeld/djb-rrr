<?php

class Route_Type_General {
    
	public function __construct() {
		add_filter('djb-rrr-route-type-data', array($this, 'add_general_route_type_data'), 10, 3 );
        add_action('djb-rrr-save-route', array( $this, 'save'), 10, 2);        
        add_filter('djb-rrr-render-route-summary', array( $this, 'render_route_summary'), 10, 2);
	}

    function add_general_route_type_data($route_types, $post_id, $currType) {     
           
        $curr_distance = get_post_meta( $post_id, '_djb_rrr_route_distance', true ); 
        $curr_units = get_post_meta( $post_id, '_djb_rrr_route_distance_units', true ); 
        $curr_start_location = get_post_meta( $post_id, '_djb_rrr_route_start_location', true ); 
        $curr_start_url = get_post_meta( $post_id, '_djb_rrr_route_start_location_url', true ); 
        $km_selected = '';
        $mi_selected = '';
        if($curr_units === 'km') {
            $km_selected = ' selected ';
        }
        else{            
            $mi_selected = ' selected ';
        }

        $default_route_type = new Route_Type_Data();
        $default_route_type->type_id ='';
        $default_route_type->type_friendly_name = 'Unspecified';
        //distance 
        $default_route_type->general_metabox_markup = sprintf('<tr><td><label for="djb_rrr_distance_val">Distance:</label></td><td><input id="djb_rrr_distance_val" name="djb_rrr_distance_val" value="%s" />', $curr_distance);
        //units
        $default_route_type->general_metabox_markup .= '<select id="djb_rrr_distance_units_val" name="djb_rrr_distance_units_val">';
        $default_route_type->general_metabox_markup .= sprintf('<option value="mi"%s>Miles</option>', $mi_selected);
        $default_route_type->general_metabox_markup .= sprintf('<option value="km"%s>Kilometers</option>', $km_selected);
        $default_route_type->general_metabox_markup .= '</select></td></tr>';
        $default_route_type->general_metabox_markup .= sprintf('<tr><td><label for="djb_rrr_start_location_val">Start Location:</label></td><td><input id="djb_rrr_start_location_val" name="djb_rrr_start_location_val" value="%s" />', $curr_start_location);
        $default_route_type->general_metabox_markup .= '</td></tr>';
        $default_route_type->general_metabox_markup .= sprintf('<tr><td><label for="djb_rrr_start_url_val">Start location URL:</label></td><td><input id="djb_rrr_start_url_val" name="djb_rrr_start_url_val" value="%s" />', $curr_start_url);
        $default_route_type->general_metabox_markup .= '</td></tr>';
        $default_route_type->type_specific_markup = '';
        $route_types[] = $default_route_type;
        
        return $route_types;
    }

     function save( $post_id, $post ) {
        // Sanitize the user input.
		$distance = sanitize_text_field( $_POST['djb_rrr_distance_val'] );
        $units = $_POST['djb_rrr_distance_units_val'];
        $startlocation = $_POST['djb_rrr_start_location_val'];
        $starturl = $_POST['djb_rrr_start_url_val'];

		// Update the meta fields
		update_post_meta( $post_id, '_djb_rrr_route_distance', $distance );
		update_post_meta( $post_id, '_djb_rrr_route_distance_units', $units );
		update_post_meta( $post_id, '_djb_rrr_route_start_location', $startlocation );
		update_post_meta( $post_id, '_djb_rrr_route_start_location_url', $starturl );
    }

    function render_route_summary($output, $post_id) {        
        //Always render general information
        $distance = get_post_meta( $post_id, '_djb_rrr_route_distance', true ); 
        $units = get_post_meta( $post_id, '_djb_rrr_route_distance_units', true );
        $startlocation = get_post_meta( $post_id, '_djb_rrr_route_start_location', true ); 
        $starturl = get_post_meta( $post_id, '_djb_rrr_route_start_location_url', true );
        if(!empty($distance)) {            
            $output .= sprintf('<div class="djb-rrr-route-info"><span class="djb-rrr-route-info-label">Distance:</span><span class="djb-rrr-route-info-value">%s %s</span></div>', $distance, $units);
        }
        if(!empty($startlocation)) {
            $output .= '<div class="djb-rrr-route-info"><span class="djb-rrr-route-info-label">Start Location:</span>';
            if(!empty($starturl)) {
                $output .= sprintf('<a class="dbj-rrr-link dbj-rrr-map-link" href="%s">', $starturl);
            }
            $output .= sprintf('<span class="djb-rrr-route-info-value">%s</span>', $startlocation);
            if(!empty($starturl)) {
                $output .= '</a>';
            }
            $output .= '</div>';
        }
        
        return $output;
    }
}
