<?php
// Prevent Direct Access
defined( 'ABSPATH' ) or die;

/**
 * This class handles all functionality of BetterFramework Users Meta box feature for creating, saving, editing
 *
 * @package	BetterFramework
 * @since   1.4
 */
class BF_User_Metabox_Core {


    /**
     * Contain all metaboxe's data, config and all fields
     *
     * @since   1.4
     * @access  private
     * @var     array       all meta options in this field
     */
    public $options;


    /**
     * Flag used for detecting if $options are loaded or not
     *
     * @since   1.4
     * @var     bool
     */
    public $options_loaded = false;


    /**
     * Used to add action for constructing the meta box
     *
     * @since	1.4
     * @access	public
     */
    public function __construct(){

        // Add options form
        add_action( 'show_user_profile', array( $this, 'add_meta_boxes' ) );
        add_action( 'edit_user_profile', array( $this, 'add_meta_boxes' ) );

        add_action( 'edit_user_profile_update', array( $this, 'save' ), 1 );
        add_action( 'personal_options_update',  array( $this, 'save' ), 1 );

    }


    /**
     * Used for retrieving raw data of all options
     *
     * @since	1.4
     */
    public function get_all_raw_options(){

        $this->load_options();

        return $this->options;

    }


    /**
     * Gets meta box options from filter and save in inner $options variable
     *
     * @since	1.4
     * @access	public
     */
    public function load_options(){

        // detect loaded or not
        if( $this->options_loaded )
            return;

        $options = apply_filters( 'better-framework/user-metabox/options', array() );

        $this->options = array();
        foreach( $options as $key => $val ){

            if(
                   preg_match( '/(\d+)|(^[^_a-zA-Z])(^\d)/', $key )
                || empty( $val['config'] )
                || empty( $val['fields'] )
            )
                continue;

            $this->options[ $key ] = $val;
        }
    }


    /**
     * Used for retrieve meta data values
     *
     * @param   string $id meta box id
     * @param $user
     *
     * @since    1.4
     * @return  array
     * @access  public
     */
    public function get_full_meta_data( $id = '', $user ){

        global $pagenow;

        $output = array();

        if( isset( $this->options[ $id ]['panel-id'] ) ){
            $std_id = Better_Framework::options()->get_std_field_id( $this->options[ $id ]['panel-id'] );
        }else{
            $std_id = 'std';
        }

        if( $pagenow == 'user-new.php' ){

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

        $meta = get_user_meta( $user->ID );

        foreach( (array) $meta as $key => $val ){

            if( is_serialized( $val[0] ) )
                $output[ $key ] = unserialize( $val[0] );
            else
                $output[ $key ] = $val[0];

        }
        return $output;
    }


    /**
     * Deprecated: Use bf_get_user_meta
     *
     * Used for finding user meta field value.
     *
     * @since   1.4
     *
     * @param $field_key    string              User field ID
     * @param $user         string|WP_User      User ID or object
     *
     * @return mixed
     */
    public function get_meta( $field_key , $user ){

        return bf_get_user_meta( $field_key, $user );

    }


    /**
     * Callback: Used for creating meta boxes
     *
     * Action: show_user_profile
     * Action: edit_user_profile
     *
     * @since   1.4
     * @access  public
     *
     * @param   $user   string|WP_User      User ID or object
     */
    public function add_meta_boxes( $user ){

        $this->load_options();

        require_once 'class-bf-user-metabox-front-end-generator.php';

        foreach( (array) $this->options as $metabox_id => $metabox ){

            $val = $this->get_full_meta_data( $metabox_id, $user );

            if( empty( $metabox['config']['title'] ) )
                $metabox['config']['title'] = __( 'Better Options', 'better-studio' );

            $front_end = new BF_User_Metabox_Front_End_Generator( $metabox, $metabox_id, $val );

            $front_end->callback();

        }

    } // add


    /**
     * Updates user meta in safely
     *
     * @param   string|WP_User  $user   User ID or object
     * @param   string          $key    User meta key name
     * @param   string          $value  User meta value
     *
     * @static
     * @since   1.4
     * @return  bool
     */
    public static function add_meta( $user, $key, $value ) {

        if( ! is_object( $user ) ){
            $user = get_user_by( 'id', $user );
        }

        $old_value = get_user_meta( $user->ID, $key, true );

        if( $old_value === false ){
            return add_user_meta( $user->ID, $key, $value );
        }else{
            if( $old_value === $value ){
                return true;
            }else{
                delete_user_meta( $user->ID, $key );
                return add_user_meta( $user->ID, $key, $value );
            }
        }

    }


    /**
     * Callback: Save user meta box values
     *
     * Action: edit_user_profile_update
     * Action: personal_options_update
     *
     * @param   int     $user_id
     *
     * @static
     * @return  mixed
     * @since   1.4
     */
    public function save( $user_id ){

        if( ! current_user_can( 'edit_user', $user_id ) )
            return false;

        $this->load_options();

        // Iterate all meta boxes
        foreach( (array) $this->options as $metabox_id => $metabox ){

            if( isset( $this->options[ $metabox_id ]['panel-id'] ) ){
                $std_id = Better_Framework::options()->get_std_field_id( $this->options[ $metabox_id ]['panel-id'] );
            }else{
                $std_id = 'std';
            }

            // Iterate all fields
            foreach( (array) $metabox['fields'] as $field_id => $field ) {

                if( ! isset( $_POST[$field_id] ) ){
                    continue;
                }

                // Save value if save-std is true or not defined
                if( ! isset( $field['save-std'] ) ||
                    $field['save-std'] == true
                ){
                    self::add_meta( $user_id, $field_id, $_POST[$field_id] );
                }

                // Don't Save Default Value
                elseif( isset( $field['save-std'] ) ){
                    // If style std defined then save it
                    if( isset( $field[$std_id] ) ){

                        if( $field[$std_id] != $_POST[$field_id]  )
                            self::add_meta( $user_id, $field_id, $_POST[$field_id] );
                        else
                            delete_user_meta( $user_id, $field_id );

                    }
                    // If style std defined then save it
                    elseif( isset( $field['std'] ) ){

                        if( $field['std'] != $_POST[$field_id]  )
                            self::add_meta( $user_id, $field_id, $_POST[$field_id] );
                        else
                            delete_user_meta( $user_id, $field_id );

                    }

                }
                // Delete Custom field
                else{
                    delete_user_meta( $user_id, $field_id );
                }

            }

        }

    } // save
}