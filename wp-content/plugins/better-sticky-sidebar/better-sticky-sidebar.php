<?php
/*
Plugin Name: Better Sticky Sidebar
Plugin URI: http://betterstudio.com
Description: Make sidebars permanently visible while scrolling.
Version: 1.1.1
Author: BetterStudio
Author URI: http://betterstudio.com
License: GPL2
*/



/**
 * Better_Sticky_Sidebar class wrapper for make changes safe in future
 *
 * @return Better_Sticky_Sidebar
 */
function Better_Sticky_Sidebar(){
    return Better_Sticky_Sidebar::self();
}


// Initialize plugin
Better_Sticky_Sidebar();


/**
 * Class Better_Sticky_Sidebar
 */
class Better_Sticky_Sidebar{


    /**
     * Contains plugin version number that used for assets for preventing cache mechanism
     *
     * @var string
     */
    private static $version = '1.1.0';


    /**
     * Inner array of instances
     *
     * @var array
     */
    protected static $instances = array();


    /**
     * Contains list of replacement sidebar locations that are changed from 3rd plugins like Custom Sidebars
     *
     * @var array
     */
    public $replacements = array();


    /**
     * Plugin initialize
     */
    function __construct(){

        // Initialize after bf init
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

        // Ads plugin textdomain
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

    }


    /**
     * Load plugin textdomain.
     *
     * @since 2.0.1
     */
    function load_textdomain() {

        // Register text domain
        load_plugin_textdomain( 'better-studio', false, 'better-sticky-sidebar/languages' );

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
     * Used for accessing plugin directory Path
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
     * @return  Better_Sticky_Sidebar|null
     */
    public static function factory( $object = 'self', $fresh = false , $just_include = false ){

        if( isset( self::$instances[$object] ) && ! $fresh ){
            return self::$instances[$object];
        }

        switch( $object ){

            /**
             * Main Better_Sticky_Sidebar Class
             */
            case 'self':
                $class = 'Better_Sticky_Sidebar';
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
     * Used for accessing alive instance of plugin
     *
     * @since 1.0
     *
     * @return Better_Sticky_Sidebar
     */
    public static function self(){

        return self::factory();

    }


    /**
     *  Init the plugin
     */
    function after_setup_theme(){

        // Enqueue Backend End Scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_wp_enqueue_scripts') );

        if( is_admin() )
            add_action( 'dynamic_sidebar_after', array( $this, 'admin_dynamic_sidebar_after' ), 99, 1 );
        else
            add_action( 'dynamic_sidebar_after', array( $this, 'front_end_dynamic_sidebar_after' ), 10, 2 );

        add_action( 'wp_ajax_better_sticky_sidebar', array( $this, 'ajax_callback' ) );

        // Add Custom Sidebars support
        add_filter( 'cs_replace_sidebars', array( $this, 'cs_replace_sidebars' ) );

    }


    /**
     * Callback: Custom Sidebar replacements action
     *
     * Filter: cs_replace_sidebars
     *
     * @param $replacements
     *
     * @return mixed
     */
    function cs_replace_sidebars( $replacements ){

        // save them to cache
        $this->replacements = $replacements;

        return $replacements;
    }


    /**
     * Ajax Callback: Used to save sidebars fields in admin
     */
    function ajax_callback(){

        // prepare variables
        $sidebar_id = @$_POST["sidebar"];
        $active     = @$_POST["active"];
        $input      = @$_POST["input"];

        // Load saved options
        $sidebar_options = $this->get_sidebar_options();


        if( ! isset( $sidebar_options[ $sidebar_id ] ) ){
            $sidebar_options[ $sidebar_id ] = array(
                'active'    =>  $active
            );
        }else{
            $sidebar_options[ $sidebar_id ]['active'] = $active;
        }


        // Update nwq options to DB
        $this->update_sidebar_options( $sidebar_options );

        // Prepare result
        $result['status'] = 'success';
        $result['active'] = $active;
        $result['input']  = $input;
        $result['options']= $sidebar_options;

        die( json_encode( $result ) );

    }


    /**
     * Used to get all sidebar options
     *
     * @return mixed|void
     */
    function get_sidebar_options(){
        return get_option( 'better-sticky-sidebar-fields-options' );
    }


    /**
     * Used to update all sidebar options
     *
     * @param $option
     */
    function update_sidebar_options( $option ){
        update_option( 'better-sticky-sidebar-fields-options', $option );
    }


    /**
     * Adds setting fields after sidebar locations in admin
     *
     * @param $sidebar_id
     */
    function admin_dynamic_sidebar_after( $sidebar_id ){

        if( $sidebar_id == 'wp_inactive_widgets' )
            return;

        $sidebar_options = $this->get_sidebar_options();

        $checked = 'unchecked';

        if( isset( $sidebar_options[$sidebar_id]['active'] ) && $sidebar_options[$sidebar_id]['active'] == 'true' ){
            $checked = 'checked';
        }

        ?>
        </div>
        <div class="better-sticky-sidebar-fields <?php echo $checked == 'checked' ? 'is-sticky' : ''; ?>" data-sidebar="<?php echo $sidebar_id; ?>">

            <label for="<?php echo $sidebar_id; ?>-sticky">
                <input id="<?php echo $sidebar_id; ?>-sticky" name="<?php echo $sidebar_id; ?>-sticky" type="checkbox" <?php echo $checked; ?>> <?php _e( 'Sticky Sidebar', 'better-studio' ); ?>
            </label>

            <div class="loader">
                <div class="spinner" style="display: block"></div>
            </div>
        <?php
    }


    /**
     * Prints codes to make sidebar sticky
     *
     * @param $sidebar_id
     * @param $have_widget
     */
    function front_end_dynamic_sidebar_after( $sidebar_id, $have_widget ){

        $original_sidebar_id = $sidebar_id;

        // Find correct sidebar ID with support Custom Sidebars plugin
        if( isset( $this->replacements[$sidebar_id] ) && $this->replacements[$sidebar_id] != false ){
            $sidebar_id = $this->replacements[$sidebar_id][0];
        }

        $sidebar_options = $this->get_sidebar_options();

        if( ! isset( $sidebar_options[$sidebar_id]['active'] ) || $sidebar_options[$sidebar_id]['active'] == 'false' || ! $have_widget ){
            return;
        }

        wp_enqueue_script( 'better-sticky-sidebar', $this->dir_url( 'js/better-sticky-sidebar.js' ), array( 'jquery' ), $this->get_version(), true );

        /**
         * Filter selectors
         *
         * todo Add multiple themes support fallback or a mechanism for compatibility
         *
         * @since 1.0.0
         *
         * @param string $config        configurations
         * @param string $sidebar_id    Sidebar ID
         */
        $selectors = apply_filters( 'better-sticky-sidebar/selectors', array( '#sidebar-' . $original_sidebar_id ), $original_sidebar_id );


        /**
         * Filter BetterTranslation config
         *
         * Configuration options list:
         *  - top           Int             Sidebar distance from the top of Window
         *  - bottom        Int             Sidebar distance from the bottom of Window
         *  - innerTop      Int             Distance from the top inside of the sidebar content
         *  - innerSticker  $Selector       Same rules apply as to innerTop. Be aware of the element margins, it includes them too.
         *  - bottomEnd     Int             Sidebar bottom distance referring to it's referring element (container or document), for when to trigger stop
         *  - followScroll  Boolean         Don't follow scroll
         *  - noContainer   Boolean         Element is referring to the document
         *  - on            Boolean         On or Off
         *
         * @since 1.0.0
         *
         * @param string $config        Default configurations
         * @param string $sidebar_id    Current sidebar ID
         */
        $config = apply_filters( 'better-sticky-sidebar/config', array(), $original_sidebar_id );

        ?>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(function($) {
                if( $(window).width() > 780 ){
                    $('<?php echo implode( ",", $selectors ); ?>').hcSticky(<?php echo json_encode( $config ); ?>);
                }
            });
            //]]>
        </script>
    <?php

    }


    /**
     * Callback: Admin enqueue assets
     *
     * Action: admin_enqueue_scripts
     */
    function admin_wp_enqueue_scripts(){

        global $pagenow;

        if( $pagenow != 'widgets.php' )
            return;

        wp_enqueue_style( 'admin-better-sticky-sidebar', $this->dir_url( 'css/admin-better-sticky-sidebar.css' ), '', $this->get_version() );

        wp_enqueue_script( 'admin-better-sticky-sidebar', $this->dir_url( 'js/admin-better-sticky-sidebar.js' ), array( 'jquery' ), $this->get_version(), true );

        wp_localize_script(
            'admin-better-sticky-sidebar',
            'better_sticky_sidebar_loc',
            apply_filters(
                'better-sticky-sidebar/localized-items',
                array(
                    'ajax_url'  =>  admin_url( 'admin-ajax.php' ),
                )
            )
        );

    }

}
