<?php
/*
Plugin Name: Better News Ticker Widget
Plugin URI: http://betterstudio.com
Description: BetterStudio News Ticker Widget
Version: 1.0.2
Author: BetterStudio
Author URI: http://betterstudio.com
License: GPL2
*/

// Initialize Up Better News Ticker
Better_News_Ticker::self();


/**
 * Class Better_News_Ticker
 */
class Better_News_Ticker{


    /**
     * Contains BNT version number that used for assets for preventing cache mechanism
     *
     * @var string
     */
    private static $version = '1.0.2';


    /**
     * Inner array of instances
     *
     * @var array
     */
    protected static $instances = array();


    function __construct(){

        // Enable needed sections
        add_filter( 'better-framework/sections', array( $this, 'better_framework_sections' ) );

        // Active and new shortcodes
        add_filter( 'better-framework/shortcodes', array( $this, 'setup_shortcodes' ) );

        // Initialize
        add_action( 'better-framework/after_setup', array( $this, 'init' ) );

        // Enqueue admin scripts
//        add_action( 'admin_enqueue_scripts', array( $this , 'admin_enqueue' ) );

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
     * Returns BSC current Version
     *
     * @return string
     */
    public static function get_version(){

        return self::$version ;

    }


    /**
     * Build the required object instance
     *
     * @param string $object
     * @param bool $fresh
     * @param bool $just_include
     * @return null
     */
    public static function factory( $object = 'self', $fresh = false , $just_include = false ){

        if( isset( self::$instances[$object] ) && ! $fresh ){
            return self::$instances[$object];
        }

        switch( $object ){

            /**
             * Main Better_News_Ticker Class
             */
            case 'self':
                $class = 'Better_News_Ticker';
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
     * Used for accessing alive instance of Better_News_Ticker
     *
     * static
     * @since 1.0
     * @return Better_News_Ticker
     */
    public static function self(){

        return self::factory();

    }


    /**
     * Activate BF needed sections
     *
     * @param $sections
     * @return mixed
     */
    function better_framework_sections( $sections ){

        $sections['vc-extender'] = true;

        return $sections;

    }


    /**
     *  Init the plugin
     */
    function init(){

        load_plugin_textdomain( 'better-studio', false, 'better-news-ticker/languages' );

    }


    /**
     * Enqueue css and js files
     *
     * todo move styles inside plugin
     */
    function enqueue_assets(){

    }


    /**
     *  Enqueue admin scripts
     */
    function admin_enqueue(){

        wp_enqueue_style( 'better-new-ticker-admin', $this->dir_url( 'css/admin-style.css' ), array(), self::get_version() );

    }


    /**
     * Setups Shortcodes
     *
     * @param $shortcodes
     */
    function setup_shortcodes( $shortcodes ){

        require_once $this->dir_path( 'includes/shortcodes/class-better-news-ticker-shortcode.php' );

        require_once $this->dir_path( 'includes/widgets/class-better-news-ticker-widget.php' );

        $shortcodes['better-news-ticker'] = array(
            'shortcode_class'   =>  'Better_News_Ticker_Shortcode',
            'widget_class'      =>  'Better_News_Ticker_Widget',
        );

        return $shortcodes;
    }

}
