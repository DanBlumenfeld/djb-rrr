<?php
/**
 * The file that defines the route custom post type metabox in the admin screen
 *
 * 
 *
 * @since      1.0.0
 *
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 */

 /**
 * The metabox helper
 *
 * This is used to generate and process metaboxes for the Route custom type
 * @since      1.0.0
 * @package    DJB_RRR
 * @subpackage DJB_RRR/includes/routes
 * @author     Dan Blumenfeld <dan@danieljblumenfeld.com>
 */
class Route_Metabox_Helper {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );        
		add_action( 'save_post', array( $this, 'save' ), 10, 2);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = array('route');   //limit meta box to certain post types
		if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'route_metabox'
				,__( 'Route Information', 'djb-rrr' )
				,array( $this, 'render_meta_box_content' )
				,$post_type
				,'normal'
				,'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id , $post) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */
         
		// Check if our nonce is set.
		if ( ! isset( $_POST['djb_rrr_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['djb_rrr_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'djb_rrr_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) )
		    return $post_id;

		/* OK, its safe for us to save the data now. Call all the registered route types to do their thing.*/

       // die(var_dump($_POST));

        $type_id = sanitize_text_field( $_POST['djb_rrr_route_type_val'] );
        
		// Update the meta field.
		update_post_meta( $post_id, '_djb_rrr_route_type_id', $type_id );

        do_action('djb-rrr-save-route', $post_id, $post);

        

	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'djb_rrr_inner_custom_box', 'djb_rrr_inner_custom_box_nonce' );
        
        //Get current data, set up rendering of stuff
        $currType = get_post_meta( $post->ID, '_djb_rrr_route_type_id', true );
        $general_markup = ''; 
        $type_combo = '<select id="djb_rrr_route_type_val" name="djb_rrr_route_type_val">';
        $type_specific_markup = '';
        
        //Get the known route types. Will be an array of Route_Type_Data instances
        $route_types = array();         
        $route_types_filtered = apply_filters('djb-rrr-route-type-data', $route_types, $post->ID, $currType);  
        foreach($route_types_filtered as $curr_data){
            if(get_class($curr_data) === 'Route_Type_Data') {
                //process general markup
                $general_markup .= $curr_data->general_metabox_markup;

                //process route type
                if($curr_data->is_route_provider == 'true')
                {
                    $selected = '';
                    if($currType === $curr_data->type_id){
                        $selected .= ' selected ';
                    }
                    $new_option = sprintf('<option data-route-type="%s" value="%s"%s>%s</option>', $curr_data->type_id, $curr_data->type_id, $selected, $curr_data->type_friendly_name);
                    $type_combo .= $new_option;
                }

                //process type-specific markup
                $type_specific_markup .= $curr_data->type_specific_markup;
            }
        }   
                
        $type_combo .= '</select>'; //Close the type combo
        
        //Render
        ?>
        <table>
            <?php echo $general_markup ?>
            <tr><td colspan="2"><hr /></td></tr>
            <tr>
                <td><?php _e( 'Route Source', 'djb-rrr' ); ?></td>
                <td>
                    <?php echo $type_combo ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo $type_specific_markup ?>
                </td>
            </tr>
        </table>
<?php
	}
}
