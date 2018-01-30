<?php

/**
 * Panels Options Handler
 */
class BF_Options{

    /**
     * Contains options only in key => value that are saved in db before
     *
     * @var array
     */
    public  $cache   = array();


    /**
     * Contains all options with base config
     *
     * @var array
     */
    public $options = array();


    /**
     * Contains theme panel id
     *
     * @var bool
     */
    public $theme_panel_id = false;


    /**
     * notice thumbnail url
     *
     * @var string
     */
    public  $notice_icon = '';


    /**
     * Loads all options and save them to db if needed
     */
    function __construct(){

        /**
         * Filters all active panels options
         *
         * @since 1.0
         *
         * @param string    $panels        panels
         */
        $options = apply_filters( 'better-framework/panel/options', array() );

        $lang = bf_get_current_language_option_code();


        foreach( $options as $panel_id => $panel ){

            // If panel has not valid ID, Continue the loop!
            if( preg_match( '/(^[^_a-zA-Z])/', $panel_id ) )
                continue;

            if( ! isset( $panel['config'] ) || ! isset( $panel['config']['name'] ) || ! isset( $panel['fields'] ) || ! is_array( $panel['fields'] ) )
                continue;

            /**
             * Fires before setup and adding panel options into BF cache
             *
             * @since 2.0
             *
             * @param string    $panel        panel data
             */
            do_action( 'better-framework/panel/' . $panel_id . '/options/setup/before', $panel );

            $this->options[$panel_id] = $panel;
            $saved_value = get_option( $panel_id . $lang );
            if( $saved_value == false )
                $saved_value = get_option( $panel_id );

            // adds default style option if needed
            if( isset( $panel['fields']['style'] ) ){

                if( get_option( $panel_id . $lang . '_current_style' ) == false ){
                    if( get_option( $panel_id . '_current_style' ) != false )
                        update_option( $panel_id . $lang . '_current_style', get_option( $panel_id . '_current_style' ) );
                    else
                        update_option( $panel_id . $lang . '_current_style', 'default' );
                }

            }

            if ( isset( $panel['config']['notice-icon'] ) && $this->is_current_admin_page( $panel['config'] ) ) {
                $this->notice_icon = $panel['config']['notice-icon'];
            }

            // save options value to database if is not saved before
            if( $saved_value == false ){

                // save to db
                $this->save_panel_options( $panel_id );

                // refresh $saved_value because that will be added to cache
                $saved_value = get_option( $panel_id );
            }

            // Adds saved value to cache
            $this->cache[$panel_id . $lang] = $saved_value;

        }

        if( $this->notice_icon ) {
            add_filter( 'better-framework/admin-notices/show', array( $this, 'admin_notice_thumbnail' ) );
        }

    }

    function admin_notice_thumbnail( $notices ) {
        foreach ( $notices as $index => $notice ) {
            if ( empty( $notice['thumbnail'] ) || ! filter_var( $notice['thumbnail'], FILTER_VALIDATE_URL ) ) {
                $notices[ $index ]['thumbnail'] = $this->notice_icon;
            }
        }

        return $notices;
    }

    /**
     * Used for detecting current page is a BF admin page or not
     *
     * @param array $config_array
     *
     * @return bool
     */
    function is_current_admin_page($config_array){

        if( ! isset( $_GET['page'] ) )
            return false;

        return  isset($_GET['page']) && $_GET['page'] === $config_array['slug'];
    }

    /**
     * Saves panel all options to database
     *
     * @param $id
     * @param null $lang
     * @return bool
     */
    public function save_panel_options( $id, $lang = null ){

        $data 	= array();

        $std_id = $this->get_std_field_id( $id );

        if( is_null( $lang ) || empty( $lang ) )
            $lang = bf_get_current_lang();

        if( $lang == 'none' || $lang == 'all' ){
            $lang = '';
        }

        $current_style = false;

        if( $lang != '' )
            $current_style = get_option( $id . '_' . $lang . '_current_style' );

        if( $current_style == false )
            $current_style = get_option( $id . '_current_style' );

        foreach( $this->options[$id]['fields'] as $field ){

            // Not save if field have style filter
            if( isset( $field['style'] ) && ! in_array( $current_style, $field['style'] ) )  continue;

            // Field is not valid or haven't std value
            if( ! isset( $field['id'] ) || ! isset( $field['type'] ) || $field['type'] == 'info' )
                continue;

            if( isset( $field[$std_id] ) && $field['type'] != 'repeater' ){
                $data[ $field['id'] ] = $field[$std_id];
            }
            elseif( isset( $field['std'] ) && $field['type'] != 'repeater' ){
                $data[$field['id']] = $field['std'];
            }
            elseif( isset( $field['default'] ) && $field['type'] == 'repeater' ){
                $data[$field['id']] = $field['default'];
            }

        }

        delete_transient( $id . 'panel_css' );

        return $this->add_option( $id, $data, $lang ) ;
    }


    /**
     * Deprecated! Use bf_get_option function.
     *
     * Get an option from the database (cached) or the default value provided
     * by the options setup.
     *
     * @param   string  $key        Option ID
     * @param   string  $panel_key  Panel ID
     * @param   string  $lang       Language
     *
     * @return  mixed|null
     */
    public function get( $key, $panel_key = '', $lang = null ){

        return bf_get_option( $key, $panel_key, $lang );

    }


    /**
     * Remove all cache options
     */
    public function clear(){

        $this->cache = array();

        return $this;

    }


    /**
     * Updates local cache
     * Note DOES NOT saves to DB. Use update() to save.
     *
     * @param string|array $key
     * @param mixed $value  a value of null will unset the option
     * @return BF_Options
     */
    public function set( $key, $value = null ){

        // array? merge it!
        if( is_array( $key ) ){
            $this->cache = array_merge( $this->cache, $key );
            return $this;
        }

        if( $value === null ){
            unset( $this->cache[$key] );
        }
        else{
            $this->cache[$key] = $value;
        }

        return $this;
    }


    /**
     * Return default value field id
     *
     * @param $panel_id
     * @return string
     */
    public function get_std_field_id( $panel_id = false, $lang = null ){

        // if panel id is not defined then uses theme panel id
        if( $panel_id == false ){

            $panel_id = $this->get_theme_panel_id();

            if( $panel_id == false )
                return 'std';

        }

        if( isset( $this->options[$panel_id]['fields']['style'] ) ){

            $current_style = get_option( $panel_id . bf_get_current_language_option_code( $lang ) . '_current_style' );

            if( $current_style == 'default' )
                return 'std';
            else
                return 'std-' . $current_style;

        }

        return 'std';

    }


    /**
     * Used for finding theme panel id
     *
     * @return bool|int|string
     */
    public function get_theme_panel_id(){

        if( $this->theme_panel_id != false )
            return $this->theme_panel_id;

        foreach( $this->options as $p_id => $panel_value ){

            if( isset( $panel_value['theme-panel'] ) ){

                $this->theme_panel_id = $p_id;

            }

        }

        return $this->theme_panel_id;

    }


    /**
     * Used for safe add option
     *
     * @param   Int     $ID         Option ID
     * @param   Array   $value      Option Value
     * @param   string  $lang       Option Language
     * @return  bool
     */
    public function add_option( $ID = null, $value = null, $lang = null ) {

        // if the parameters are not defined stop the process.
        if ( $ID === null || $value === null )
            return false;

        if( is_null( $lang ) )
            $lang = bf_get_current_lang();

        if( $lang != 'none' && ! empty( $lang ) && $lang != 'all' )
            $ID .= '_' . $lang;

        $old_value = get_option( $ID );

        if( $old_value === false ){
            return add_option( $ID, $value );
        }else{
            if( $old_value === $value ){
                return true;
            }else{
                delete_option( $ID );
                return add_option( $ID, $value );
            }
        }
    }
}