<?php
// Prevent Direct Access
defined( 'ABSPATH' ) or die;


/**
 * This class handles all functionality of BetterFramework Meta box feature for creating, saving, editing
 * and another functionality like filtering metaboxe's for post types, pages and etc
 *
 * @package	BetterFramework
 * @since   1.0
 */
class BF_Metabox_Core {


    /**
     * Contain all metaboxe's data, config and all fields
     *
     * @since   1.0
     * @access  private
     * @var     array       all meta options in this field
     */
    public $options;


    /**
     * Used to add action for constructing the meta box
     *
     * @since	1.0
     * @access	public
     */
    public function __construct(){

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

        add_action( 'pre_post_update', array( $this, 'save' ), 1 );

    }


    /**
     * Gets meta box options from filter and save in inner $options variable
     *
     * @since	1.0
     * @access	public
     */
    public function load_options(){

        $options = apply_filters( 'better-framework/metabox/options', array() );

        foreach( $options as $key => $val ){

            if(
                   preg_match( '/(\d+)|(^[^_a-zA-Z])(^\d)/', $key )
                || empty( $val['config'] )
                || empty( $val['config']['pages'] )
                || empty( $val['fields'] )
            )
                continue;

            $this->options[ $key ] = $val;
        }
    }


    /**
     * Used for retrieve meta data values
     *
     * @param   string  $id     meta box id
     *
     * @since	1.0
     * @return  array
     * @access  public
     */
    public function get_meta_data( $id = '' ){

        global $pagenow;

        $output = array();

        if( isset( $this->options[ $id ]['panel-id'] ) ){
            $std_id = Better_Framework::options()->get_std_field_id( $this->options[ $id ]['panel-id'] );
        }else{
            $std_id = 'std';
        }

        if( $pagenow == 'post-new.php' ){

            if( ! empty( $this->options[ $id ]['fields'] ) ){

                foreach( (array) $this->options[ $id ]['fields'] as $field ){

                    if( isset( $field[$std_id] ) ){
                        $output[ $field['id'] ] = $field[$std_id];
                    }
                    elseif( isset( $field['std'] ) ){
                        $output[ $field['id'] ] = $field['std'];

                    }
                }

            }

            return $output;
        }

        $meta = get_post_custom();

        foreach( (array) $meta as $key => $val ){

            if( is_serialized( $val[0] ) )
                $output[ $key ] = unserialize( $val[0] );
            else
                $output[ $key ] = $val[0];

        }
        return $output;
    }


    /**
     * Used for creating meta boxes
     *
     * Callback: add_meta_boxes action
     *
     * @since   1.0
     * @access  public
     */
    public function add_meta_boxes(){

        // Load Options
        $this->load_options();

        require_once BF_PATH . 'metabox/class-bf-metabox-front-end-generator.php';

        foreach( (array) $this->options as $id => $stuff ){

            if( ! $this->can_output( $stuff['config'] ) )
                continue;

            $val	   = $this->get_meta_data( $id );
            $title	   = empty( $stuff['config']['title'] ) ? '' : $stuff['config']['title'];
            $front_end = new BF_Metabox_Front_End_Generator( $stuff, $id, $val );

            if( is_array( $stuff['config']['pages'] ) ){

                foreach( $stuff['config']['pages'] as $page ){
                    add_meta_box(
                        'bf_' . $id,
                        $title,
                        array( $front_end, 'callback' ),
                        $page,
                        isset( $stuff['config']['context'] ) ? $stuff['config']['context'] : 'normal',
                        isset( $stuff['config']['priority'] ) ? $stuff['config']['priority'] : 'default'
                    );
                }

            }
            else if( is_string( $stuff['config']['pages'] ) ){

                add_meta_box(
                    'bf_' . $id,
                    $title,
                    array( $front_end, 'callback' ),
                    $stuff['config']['pages'],
                    isset( $stuff['config']['context'] ) ? $stuff['config']['context'] : 'normal',
                    isset( $stuff['config']['priority'] ) ? $stuff['config']['priority'] : 'default'
                );

            }
        }// foreach

    } // add


    /**
     * Calculate when meta box can added
     *
     * @param   array $config Configuration values of meta box
     *
     * @return bool
     * @since   1.1.1
     * @access  public
     */
    public function can_output( $config ){

        $post_id = bf_get_admin_current_post_id();

        // post types
        switch( true ){
            case ( ! isset( $config['pages'] ) || empty( $config['pages'] ) ):
                $post_types = array();
                break;
            case ( is_array( $config['pages'] ) ):
                $post_types = $config['pages'];
                break;
            case ( is_string( $config['pages'] ) ):
                $post_types[] = $config['pages'];
                break;
        }

        // include_template
        switch( true ){

            case( ! isset( $config['include_template'] ) || empty( $config['include_template'] ) ):
                $include_template = array();
                break;

            case( is_array( $config['include_template'] ) ):
                $include_template = $config['include_template'];
                break;

            case ( is_string( $config['include_template'] ) ):
                $include_template[] = $config['include_template'];
                break;
        }

        // exclude_template
        switch( true ){

            case ( !isset($config['exclude_template']) || empty($config['exclude_template']) ):
                $exclude_template = array();
                break;

            case ( is_array( $config['exclude_template'] ) ):
                $exclude_template = $config['exclude_template'];
                break;

            case ( is_string( $config['exclude_template'] ) ):
                $exclude_template[] = $config['exclude_template'];
                break;

        }


        if ( ! empty( $include_template ) || ! empty( $exclude_template ) ){
            $template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
        }

        $can_output = TRUE;

        // processing order: "exclude" then "include"
        // processing order: "template"

        if( ! empty( $include_template ) || ! empty( $exclude_template ) ){

            if( ! empty( $exclude_template ) ){
                if( in_array( $template_file , $exclude_template ) ){
                    $can_output = FALSE;
                }
            }

            // excludes are not set use "include only" mode
            if( empty( $exclude_template ) ){
                $can_output = FALSE;
            }

            if( ! empty( $include_template ) ){

                if ( in_array( $template_file,$include_template ) ){
                    $can_output = TRUE;
                }

            }
        }

        // Filter for post types
        $current_post_type = bf_get_admin_current_post_type();

        if( isset( $current_post_type ) && ! in_array( $current_post_type, $post_types ) ){
            $can_output = FALSE;
        }

        return $can_output;
    }


    /**
     * Update Post Meta
     *
     * @param   string  $id     The id post
     * @param   string  $key    Post meta key name
     * @param   string  $val    Post meta key value
     *
     * @static
     * @since   1.0
     * @return  bool
     */
    public static function update( $id, $key, $val ) {

        return update_post_meta( $id, $key, $val);

    }


    /**
     * Save post meta box values
     *
     * Callback: pre_post_update action
     *
     * @param   int     $post_id
     *
     * @static
     * @return  mixed
     * @since   1.0
     */
    public function save( $post_id ){

        if(
            empty( $_POST['bf-metabox-option'] )
            || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            || ( !isset( $_POST['post_ID'])
            || $post_id != $_POST['post_ID'] )
            || !current_user_can( 'edit_post', $post_id )
        )
            return $post_id;

        $this->load_options();



	    foreach ( $this->options as $metabox_key => $option ) {
		    if ( empty( $option['fields'] ) || ! is_array( $option['fields'] ) ) {
			    continue;
		    }
		    if ( ! isset( $_POST['bf-metabox-option'][ $metabox_key ] ) ) {
			    continue;
		    }

		    $metabox_value = &$_POST['bf-metabox-option'][ $metabox_key ];
		    foreach ( $option['fields'] as $field_key => $_field_value ) {
			    if ( ! isset( $metabox_value[ $field_key ] ) ) {
				    if ( ! in_array( $_field_value['type'], array( 'group', 'tab' ) ) ) {
					    delete_post_meta( $post_id, $field_key );
				    }
				    continue;
			    }
			    $field_value = &$metabox_value[ $field_key ];

                // If Metabox and Field are Valid
                if( isset( $this->options[$metabox_key]['fields'][$field_key] ) ){

                    if( isset( $this->options[ $metabox_key ]['panel-id'] ) ){
                        $std_id = Better_Framework::options()->get_std_field_id( $this->options[ $metabox_key ]['panel-id'] );
                    }else{
                        $std_id = 'std';
                    }

                    // Save value if save-std is true or not defined
                    if( ! isset( $this->options[$metabox_key]['fields'][$field_key]['save-std'] ) ||
                        $this->options[$metabox_key]['fields'][$field_key]['save-std'] == true
                    ){
                        self::update( $post_id, $field_key, $field_value );
                    }

                    // Don't Save Default Value
                    elseif( isset( $this->options[$metabox_key]['fields'][$field_key]['save-std'] ) ){
                        // If style std defined then save it
                        if( isset( $this->options[$metabox_key]['fields'][$field_key][$std_id] ) ){

                            if( $this->options[$metabox_key]['fields'][$field_key][$std_id] != $field_value  )
                                self::update( $post_id, $field_key, $field_value );
                            else
                                delete_post_meta( $post_id, $field_key );

                        }
                        // If style std defined then save it
                        elseif( isset( $this->options[$metabox_key]['fields'][$field_key]['std'] ) ){

                            if( $this->options[$metabox_key]['fields'][$field_key]['std'] != $field_value  )
                                self::update( $post_id, $field_key, $field_value );
                            else
                                delete_post_meta( $post_id, $field_key );

                        }

                    }
                    // Delete Custom field
                    else{
                        delete_post_meta( $post_id, $field_key );
                    }
                }
            }
        } // foreach
    } // save
}