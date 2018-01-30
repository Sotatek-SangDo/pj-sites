<?php

/**
 * Handles enqueue scripts and styles for preventing conflict and also multiple version of assets in on page
 */
class BF_Assets_Manager {


    /**
     * Contains footer js codes
     *
     * @var array
     */
    private $footer_js = array();


    /**
     * Contains head js codes
     *
     * @var array
     */
    private $head_js = array();


    /**
     * Contains footer js codes
     *
     * @var array
     */
    private $footer_jquery_js = array();


    /**
     * Contains head js codes
     *
     * @var array
     */
    private $head_jquery_js = array();


    /**
     * Contains footer css codes
     *
     * @var array
     */
    private $footer_css = array();


    /**
     * Contains head css codes
     *
     * @var array
     */
    private $head_css = array();


    /**
     * Contains admin footer js codes
     *
     * @var array
     */
    private $admin_footer_js = array();


    /**
     * Contains admin head js codes
     *
     * @var array
     */
    private $admin_head_js = array();


    /**
     * Contains admin footer css codes
     *
     * @var array
     */
    private $admin_footer_css = array();


    /**
     * Contains admin head css codes
     *
     * @var array
     */
    private $admin_head_css = array();

    /**
     * Contains header codes
     *
     * @var array
     */
    private $head_codes = array();


    function __construct(){

        // Front End Inline Codes
        add_action( 'wp_head', array( $this, 'print_head' ), 100 );
        add_action( 'wp_head', array( $this, 'force_head_print' ), 100 );
        add_action( 'wp_footer', array( $this, 'print_footer' ), 100 );

        // Backend Inline Codes
        add_action( 'admin_head', array( $this, 'force_head_print' ), 100 );
        add_action( 'admin_head', array( $this, 'print_admin_head' ), 100 );
        add_action( 'admin_footer', array( $this, 'print_admin_footer' ), 100 );

	    // Backend Modal
	    if( is_admin() )
            add_action( 'admin_footer', array( 'BF_Assets_Manager', 'enqueue_modals' ) );

    }


    /**
     * DRY!
     *
     * @param array  $code
     * @param string $type
     * @param string $comment
     * @param string $before
     * @param string $after
     */
    private function _print( $code = array(), $type = 'style', $comment = '', $before = '', $after = ''  ){

        $output = '';

        foreach ( (array) $code as $_code )
            $output .= $_code . "\n";

        if( $output )
            echo "\n<!-- {$comment} -->\n<{$type}>{$before}\n{$output}\n{$after}</{$type}>\n<!-- /{$comment}-->\n";

    }


    /**
     * Filter Callback: used for printing style and js codes in header
     */
    function print_head(){

        $this->_print( $this->head_css, 'style', 'BetterFramework Head Inline CSS' );
        $this->head_css = array();

        $this->_print( $this->head_js, 'script', 'BetterFramework Head Inline JS' );
        $this->head_js = array();

        $this->_print( $this->head_jquery_js, 'script', 'BetterFramework Head Inline jQuery Code', 'jQuery(function($){', '});' );
        $this->head_jquery_js = array();

    }


    /**
     * Filter Callback: used for printing style and js codes in footer
     */
    function print_footer(){

        // Print header lagged CSS
        $this->_print( $this->head_css, 'style', 'BetterFramework Header Lagged Inline CSS' );

        // Print footer CSS
        $this->_print( $this->footer_css, 'style', 'BetterFramework Footer Inline CSS' );

        // Print header lagged JS
        $this->_print( $this->head_js, 'script', 'BetterFramework Header Lagged Inline JS' );

        // Print header lagged jQuery JS
        $this->_print( $this->head_jquery_js, 'script', 'BetterFramework Header Lagged Inline jQuery JS', 'jQuery(function($){', '});' );

        // Print footer JS
        $this->_print( $this->footer_js, 'script', 'BetterFramework Footer Inline JS' );

        // Print footer jQuery JS
        $this->_print( $this->footer_jquery_js, 'script', 'BetterFramework Footer Inline jQuery JS', 'jQuery(function($){', '});' );

    }


    /**
     * Filter Callback: used for printing style and js codes in admin header
     */
    function print_admin_head(){

        // Print admin header CSS
        $this->_print( $this->admin_head_css, 'style', 'BetterFramework Admin Head Inline CSS' );
        $this->admin_head_css = array();

        // Print admin header JS
        $this->_print( $this->admin_head_js, 'script', 'BetterFramework Head Inline JS' );
        $this->admin_head_js = array();

    }


    /**
     * Filter Callback: used for printing style and js codes in admin footer
     */
    function print_admin_footer(){

        // Print header lagged CSS
        $this->_print( $this->admin_head_css, 'style', 'BetterFramework Admin Header Lagged Inline CSS' );

        // Print footer CSS
        $this->_print( $this->admin_footer_css, 'style', 'BetterFramework Admin Footer Inline CSS' );

        // Print header lagged JS
        $this->_print( $this->admin_head_js, 'script', 'BetterFramework Admin Footer Inline JS' );

        // Print footer JS
        $this->_print( $this->admin_footer_js, 'script', 'BetterFramework Admin Footer Inline JS' );

    }

    protected function force_print( $code = array(), $type = 'style', $comment = '', $before = '', $after = '' ) {
        if(  did_action( is_admin() ? 'admin_head' : 'wp_head' ) ) {
            $this->_print($code,$type,$comment,$before,$after);
        } else {
            $this->head_codes[] = func_get_args();
        }
    }

    public function force_head_print(  ) {
        if( $this->head_codes ) {
            foreach($this->head_codes as $args) {
                call_user_func_array(array($this, '_print'), $args);
            }

            $this->head_codes = array();
        }
    }
    /**
     * Used for adding inline js
     *
     * @param string $code
     * @param bool   $to_top
     * @param bool   $force
     */
    function add_js( $code = '', $to_top = false, $force = false ) {

        if ( $force ) {
            $this->force_print($code, 'script');
            return;
        }

        if ( $to_top ) {
            $this->head_js[] = $code;
        } else {
            $this->footer_js[] = $code;
        }
    }


    /**
     * Used for adding inline js
     *
     * @param string $code
     * @param bool   $to_top
     * @param bool   $force
     */
    function add_jquery_js( $code = '', $to_top = false, $force = false ) {

        if ( $force ) {
            $this->force_print($code, 'script','jQuery(function($){','});');
            return;
        }

        if ( $to_top ) {
            $this->head_jquery_js[] = $code;
        } else {
            $this->footer_jquery_js[] = $code;
        }

    }


    /**
     * Used for adding inline css
     *
     * @param string $code
     * @param bool $to_top
     * @param bool $force
     */
    function add_css( $code = '', $to_top = false, $force = false ) {

        if ( $force ) {
            $this->force_print($code, 'style');
            return;
        }


        if ( $to_top ) {
            $this->head_css[] = $code;
        } else {
            $this->footer_css[] = $code;
        }
    }


    /**
     * Used for adding inline js
     *
     * @param string $code
     * @param bool   $to_top
     * @param bool   $force
     */
    function add_admin_js( $code = '', $to_top = false, $force = false ) {

        if ( $force ) {
            $this->force_print($code, 'script');
            return;
        }

        if ( $to_top ) {
            $this->admin_head_js[] = $code;
        } else {
            $this->admin_footer_js[] = $code;
        }

    }


    /**
     * Used for adding inline css
     *
     * @param string $code
     * @param bool $to_top
     * @param bool $force
     */
    function add_admin_css( $code = '', $to_top = false,$force = false ){

        if ( $force ) {
            $this->force_print($code, 'style');
            return;
        }

        if( $to_top )
            $this->admin_head_css[] = $code;
        else
            $this->admin_footer_css[] = $code;

    }


    /**
     * Enqueue styles safely
     *
     * @param $style_key
     */
    function enqueue_style( $style_key = '' ){

        $version = Better_Framework()->self()->version;

        switch( $style_key ){

            //
            //
            // General
            //
            //

            // Fontawesome
            case 'fontawesome':

                wp_enqueue_style( 'fontawesome', BF_URI . 'assets/css/font-awesome.min.css', array(), $version );
                break;

            // Better Social Font Icon
            case 'better-social-font-icon':

                wp_enqueue_style( 'bf-better-social-font-icon', BF_URI . 'assets/css/better-social-font-icon.css', array(), $version );
                break;

            // Better Studio Admin Icon
            case 'better-studio-admin-icon':

                wp_enqueue_style( 'bf-better-studio-admin-icon', BF_URI . 'assets/css/better-studio-admin-icon.css', array(), $version );
                break;


            // Pretty Photo
            case 'pretty-photo':

                wp_enqueue_style( 'bf-pretty-photo', BF_URI . 'assets/css/pretty-photo.css', array(), $version );
                break;


            //
            //
            // Admin Styles
            //
            //

            // BF Used Plugins CSS
            case 'admin-pages':

                wp_enqueue_style( 'bf-admin-pages', BF_URI . 'assets/css/admin-pages.css', array(), $version );
                break;

            // modal
            case 'better-modals':

                wp_enqueue_style( 'better-modals', BF_URI . 'assets/css/better-modals.css', array(), $version );
                break;

            // BF Used Plugins CSS
            case 'admin-plugins':

                wp_enqueue_style( 'bf-admin-plugins', BF_URI . 'assets/css/admin-plugins.css', array(), $version );
                break;

            // Codemirror (syntax highlighter code editor) CSS
            case 'codemirror-packs':

                wp_enqueue_style( 'bf-codemirror-packs', BF_URI.'assets/css/codemirror-pack.css', array(), $version );
                break;

            case 'bs-modal':

                wp_enqueue_style( 'bs-modal-style', BF_URI . 'assets/css/bs-modal.css', array(), $version );

            break;

            // BetterFramework admin style
            case 'better-framework-admin':

                $this->enqueue_style( 'fontawesome' );
                $this->enqueue_style( 'better-social-font-icon' );
                $this->enqueue_style( 'better-studio-admin-icon' );
                $this->enqueue_style( 'better-modals' );
                $this->enqueue_style( 'admin-plugins' );
                $this->enqueue_style( 'codemirror-packs' );
                $this->enqueue_style( 'bs-modal' );
                wp_enqueue_style( 'bf-better-framework-admin', BF_URI . 'assets/css/admin-style.css', array(
                    'better-modals',
                    'fontawesome',
                    'bf-better-social-font-icon',
                    'bf-better-studio-admin-icon',
                    'bf-admin-plugins',
                    'bf-codemirror-packs',
                ), $version );

                if( is_rtl() ){
                    wp_enqueue_style( 'bf-better-framework-admin-rtl', BF_URI . 'assets/css/rtl-admin-style.css', array(
                        'bf-better-framework-admin',
                    ), $version );
                }

                break;

            default:
                wp_enqueue_style( $style_key );

        }

    }


    /**
     * Enqueue scripts safely
     *
     * @param $script_key
     */
    function enqueue_script( $script_key ){

        $version = Better_Framework()->self()->version;
        $prefix  = ! bf_is( 'dev' ) ? '.min' : '';

        switch( $script_key ){

            //
            //
            // General
            //
            //

            // Element Query
            case 'element-query':

                wp_enqueue_script( 'element-query', BF_URI . 'assets/js/element-query.min.js', array(), $version, true );
                break;

            // PrettyPhoto
            case 'pretty-photo':

                wp_enqueue_script( 'bf-pretty-photo', BF_URI . 'assets/js/pretty-photo.js', array(), $version, true );
                break;


            //
            //
            // Admin Scripts
            //
            //

            // Better Fonts Manager
            case 'better-fonts-manager':

                wp_enqueue_script( 'bf-better-fonts-manager', BF_URI . 'assets/js/better-fonts-manager.js', array(), $version, true );
                break;

            // BF Used Plugins JS File
            case 'admin-plugins':

                /**
                 * Enqueue Color Picker Dependencies
                 *
                 * uncompressed version also available in assets/js/color-picker.js
                 */
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'bf-admin-plugins', BF_URI . 'assets/js/admin-plugins.js', array('wp-color-picker'),$version, true );
                break;

            // BF Used Plugins JS File
            case 'better-modals':

                wp_enqueue_script( 'bf-better-modals', BF_URI . 'assets/js/better-modals.js', array(), $version, true );
                break;

            // BetterFramework admin script
            case 'better-framework-admin':

                $this->enqueue_script( 'admin-plugins' );
                $this->enqueue_script( 'better-modals' );
                $this->enqueue_script( 'bs-modal' );
                $this->enqueue_script( 'ace-editor' );
                wp_enqueue_script( 'bf-better-framework-admin', BF_URI . 'assets/js/admin-scripts.js', array(
                    'jquery-ui-core',
                    'jquery-ui-widget',
                    'jquery-ui-slider',
                    'jquery-ui-sortable',
                    'bf-admin-plugins',
                ), $version, true );
                break;

            case 'bs-modal':
                wp_enqueue_script( 'mustache', BF_URI . 'assets/js/mustache' . $prefix . '.js', array(), $version, true );
                wp_enqueue_script( 'bs-modal-script', BF_URI . 'assets/js/bs-modal' . $prefix . '.js', array( 'mustache' ), $version, true );
                break;

            case 'ace-editor':
                /**
                 * Enqueue Ace Code Editor
                 */
                wp_enqueue_script( 'ace-editor', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js', array(), $version, true );

                // wp_script_add_data not working on wordpress before 4.2
                // wp_enqueue_script( 'ace-editor-ie', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ext-old_ie.js', array('ace-editor'), $version, true );
                // wp_script_add_data( 'ace-editor-ie', 'conditional', 'lt IE 9' );

                add_action( is_admin() ? 'admin_footer' : 'wp_footer', array($this, 'print_ace_editor_oldie_js') );
                break;

            default:
                wp_enqueue_script( $script_key );

        }

    }

    public function print_ace_editor_oldie_js(  ) {
        ?>
        <!--[if lt IE 9]>
            <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ext-old_ie.js'></script>
        <![endif]-->
        <?php
    }


    /**
     * Contains list of active modals that should be printed in bottom of page
     *
     * @var array
     */
    public static $active_modals;


    /**
     * Adds modals to active modals list
     *
     * @param $modal_id
     */
    public static function add_modal( $modal_id ){
        self::$active_modals[$modal_id] = $modal_id;
    }


    /**
     * Callback: Hooked to admin_footer to print all modals in bottom of page
     */
    public static function enqueue_modals(){

        foreach( (array) self::$active_modals as $modal ){
	        $modal_template_file = BF_PATH .'/core/field-generator/modals/' . $modal . '.php';
            include $modal_template_file;
        }

    } // enqueue_modals

}
