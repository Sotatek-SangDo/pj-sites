<?php


/**
 * Better Studio Theme Updater
 *
 *
 * @package  BetterStudio Theme Updater
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.0.0
 * @access   public
 * @see      http://www.betterstudio.com/
 */
class Better_Studio_Theme_Updater{


    /**
     * Contains URL of our update manager
     *
     * @var
     */
    var $api_url = 'http://updates.betterstudio.com/theme-update-manager/';


    /**
     * Contains theme ThemeForest ID
     *
     * @var
     */
    var $theme_id;


    /**
     * Contains theme name
     *
     * @var
     */
    var $theme_name;


    /**
     * Contains theme slug
     *
     * @var
     */
    var $theme_slug;


    /**
     * Contains user username
     *
     * @var
     */
    var $user_name;


    /**
     * Contains user API secret key
     *
     * @var
     */
    var $api_key;


    function __construct( $theme_name, $theme_slug, $theme_id, $user_name, $api_key ){

        $this->theme_name = $theme_name;

        $this->theme_slug = $theme_slug;

        $this->theme_id = $theme_id;

        $this->user_name = $user_name;

        $this->api_key = $api_key;

        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_update' ) );

    }


    /**
     * Filter Callback: Used for checking theme update and adding updates to native theme updater
     *
     * @param $transient
     * @return mixed
     */
    function check_update( $transient ){

        if( empty( $transient->checked ) ){
            return $transient;
        }

        // Create request arguments
        $request_args = $this->prepare_args( $transient );

        // Create request raw string
        $request_string = $this->prepare_request( $request_args );

        // Call request
        $request_response = wp_remote_post( $this->api_url, $request_string );

        $response = null;

        // Check for valid data and save it in $response
        if( ! is_wp_error( $request_response ) && ( $request_response['response']['code'] == 200 ) ){
            $response = json_decode( $request_response['body'], true );
        }

        // Inject update data ino WordPress updater
        if( isset(  $response['status'] ) && $response['status'] == 'success' ){

            unset( $response['status'] );

            $transient->response[$this->theme_slug] = $response;

        }

        return $transient;
    }


    /**
     * Prepares request arguments for our theme update manager
     *
     * @param $transient
     * @return array
     */
    function prepare_args( $transient ){

        return array(
            'id'                =>  $this->theme_id,
            'name'              =>  $this->theme_name,
            'slug'              =>  $this->theme_slug,
            'username'          =>  $this->user_name,
            'api_key'           =>  $this->api_key,
            'version'           =>  $transient->checked[$this->theme_slug],
            'url'               =>  esc_url( home_url( '/' ) ),
        );

    }


    /**
     * Prepares request for our theme update manager
     *
     * @param $args
     * @return array
     */
    function prepare_request( $args ) {

        return array(
            'body' => array(
                'action'    =>  'update',
                'request'   =>  $args
            ),
        );

    }

}
