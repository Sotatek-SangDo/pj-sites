<?php
/*
Plugin Name: Better Custom 404 Template
Plugin URI: http://betterstudio.com
Description: Redirect all of your site's 404 errors to a custom page that you have complete control over. Easily create any layout you want using page templates, shortcodes, and more!
Version: 1.0.0
Author: BetterStudio
Author URI: http://betterstudio.com
License: GPL2
*/


/**
 * Better_Custom_404_Template class wrapper for make changes safe in future
 *
 * @return Better_Custom_404_Template
 */
function Better_Custom_404_Template(){
    return Better_Custom_404_Template::self();
}


// Initialize Better Custom 404 Template
Better_Custom_404_Template();


/**
 * Class Better_Custom_404_Template
 */
class Better_Custom_404_Template{


    /**
     * Contains Better_Custom_404_Template version number that used for assets for preventing cache mechanism
     *
     * @var string
     */
    private static $version = '1.0.0';


    /**
     * Contains plugin option panel ID
     *
     * @var string
     */
    private static $panel_id = 'better_custom_404_template';


    /**
     * Inner array of instances
     *
     * @var array
     */
    protected static $instances = array();


    function __construct(){

        // Admin panel options
        add_filter( 'better-framework/panel/options' , array( $this , 'setup_option_panel' ) );

        // Initialize
        add_action( 'better-framework/after_setup', array( $this, 'init' ) );

    }


    /**
     * Used for accessing plugin directory URL
     *
     * @param string $address
     *
     * @return string
     */
    public static function dir_url( $address = '' ){

        return plugin_dir_url( __FILE__ ) . $address;

    }


    /**
     * Used for accessing plugin directory path
     *
     * @param string $address
     *
     * @return string
     */
    public static function dir_path( $address = '' ){

        return plugin_dir_path( __FILE__ ) . $address;

    }


    /**
     * Returns plugin current Version
     *
     * @return string
     */
    public static function get_version(){

        return self::$version ;

    }


    /**
     * Build the required object instance
     *
     * @param   string    $object
     * @param   bool      $fresh
     * @param   bool      $just_include
     *
     * @return  Better_Custom_404_Template|null
     */
    public static function factory( $object = 'self', $fresh = false , $just_include = false ){

        if( isset( self::$instances[$object] ) && ! $fresh ){
            return self::$instances[$object];
        }

        switch( $object ){

            /**
             * Main Better_Custom_404_Template Class
             */
            case 'self':
                $class = 'Better_Custom_404_Template';
                break;

            default:
                return null;
        }


        // Just prepare/includes files
        if( $just_include )
            return;

        // don't cache fresh objects
        if( $fresh ){
            return new $class;
        }

        self::$instances[$object] = new $class;

        return self::$instances[$object];
    }


    /**
     * Used for accessing alive instance of Better_Custom_404_Page
     *
     * @since 1.0
     *
     * @return Better_Custom_404_Page
     */
    public static function self(){

        return self::factory();

    }


    /**
     * Used for retrieving options simply and safely for next versions
     *
     * @param $option_key
     *
     * @return mixed|null
     */
    public static function get_option( $option_key ){

        return bf_get_option( $option_key, self::$panel_id );

    }


    /**
     * Callback: Adds included BetterFramework to BF loader
     *
     * Filter: better-framework/loader
     *
     * @param $frameworks
     *
     * @return array
     */
    function better_framework_loader( $frameworks ){

        $frameworks[] = array(
            'version'   =>  '2.0.0',
            'path'      =>  $this->dir_path( 'includes/libs/better-framework/' ),
            'uri'       =>  $this->dir_url('includes/libs/better-framework/' ),
        );

        return $frameworks;

    }


    /**
     *  Init the plugin
     */
    function init(){

        load_plugin_textdomain( 'better-studio', false, 'better-custom-404-template/languages' );

        // Redirect to custom 404 page if is set
        if( $this->get_option( 'template' ) != 'default' )
            add_filter( '404_template', array( $this, 'custom_404_template' ) );

    }


    /**
     * Callback: Setup setting panel
     *
     * Filter: better-framework/panel/options
     *
     * @param $options
     *
     * @return array
     */
    function setup_option_panel( $options ){

        $field['template'] = array(
            'name'          =>  __( 'Custom 404 Template', 'better-studio' ),
            'id'            =>  'template',
            'desc'          =>  __( 'Select the page to be used in place of your site\'s standard 404 page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  array(
                'default'   => __( 'Default 404 Template', 'better-studio' ),
                array(
                    'label'     =>  __( 'Pages', 'better-studio' ),
                    'options'   =>  Better_Framework::helper_query()->get_pages()
                )
            )
        );

        // Language  name for smart admin texts
        $lang = bf_get_current_lang_raw();
        if( $lang != 'none' ){
            $lang = bf_get_language_name( $lang );
        }else{
            $lang = '';
        }

        $options[self::$panel_id] = array(
            'config' => array(
                'parent'                =>  'better-studio',
                'slug' 			        =>  'better-studio/better-custom-404-template',
                'name'                  =>  __( 'Better Custom 404 Template', 'better-studio' ),
                'page_title'            =>  __( 'Better Custom 404 Template', 'better-studio' ),
                'menu_title'            =>  __( 'Custom 404', 'better-studio' ),
                'capability'            =>  'manage_options',
                'icon_url'              =>  null,
                'position'              =>  80.06,
                'exclude_from_export'   =>  false,
            ),
            'texts'         =>  array(

                'panel-desc-lang'       =>  '<p>' . __( '%s Language Options.', 'better-studio' ) . '</p>',
                'panel-desc-lang-all'   =>  '<p>' . __( 'All Languages Options.', 'better-studio' ) . '</p>',

                'reset-button'      => ! empty( $lang ) ? sprintf( __( 'Reset %s Options', 'better-studio' ), $lang ) : __( 'Reset Options', 'better-studio' ),
                'reset-button-all'  => __( 'Reset All Options', 'better-studio' ),

                'reset-confirm'     =>  ! empty( $lang ) ? sprintf( __( 'Are you sure to reset %s options?', 'better-studio' ), $lang ) : __( 'Are you sure to reset options?', 'better-studio' ),
                'reset-confirm-all' => __( 'Are you sure to reset all options?', 'better-studio' ),

                'save-button'       =>  ! empty( $lang ) ? sprintf( __( 'Save %s Options', 'better-studio' ), $lang ) : __( 'Save Options', 'better-studio' ),
                'save-button-all'   =>  __( 'Save All Options', 'better-studio' ),

                'save-confirm-all'  =>  __( 'Are you sure to save all options? this will override specified options per languages', 'better-studio' )

            ),
            'panel-name'        => _x( 'Better Custom 404 Template', 'Panel title', 'better-studio' ),
            'panel-desc'        =>  '<p>' . __( 'Replace the default theme 404 page with customized page.', 'better-studio' ) . '</p>',
            'fields'            => $field
        );

        return $options;
    }


    /**
     * Used for redirect to custom 404 page
     */
    function custom_404_template(){

        wp_redirect( get_permalink( $this->get_option( 'template' ) ), 301 );

    }

}
