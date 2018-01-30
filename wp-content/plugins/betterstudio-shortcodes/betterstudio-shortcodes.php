<?php
/*
Plugin Name: BetterStudio Shortcodes
Plugin URI: http://betterstudio.com
Description: BetterStudio Shortcodes adds multiple shortcode functionality for themes.
Version: 1.1.0
Author: BetterStudio
Author URI: http://betterstudio.com
License: GPL2
*/

BetterStudio_Shortcodes::instance();

class BetterStudio_Shortcodes{

    /**
     * Contains alive instance used for factory pattern
     *
     * @var
     */
    protected static $instance;


    /**
     * Contains alive instance of BetterStudio_Editor_Shortcodes
     *
     * @var
     */
    protected static $editor_instance;


    /**
     * Contains plugin base directory path
     *
     * @var
     */
    public $plugin_dir_path;


    /**
     * Contains plugin base directory url
     *
     * @var
     */
    public $plugin_dir_url;


    /**
     * Contain list of shortcodes
     *
     * @var array
     */
    public $shortcodes = array();


    /**
     * Contains temporary data that used for rendering tabs and etc...
     *
     * @var array
     */
    public $temp = array();


    function __construct(){

        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_url = plugin_dir_url( __FILE__ );

        add_action( 'after_setup_theme', array( $this, 'setup'));

    }


    /**
     * Return URL
     *
     * @param $path
     * @return string
     */
    public function url( $path ){
        return $this->plugin_dir_url . $path;
    }


    /**
     * Return Path
     *
     * @param $path
     * @return string
     */
    public function path( $path ){
        return $this->plugin_dir_path . $path;
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
     * Used for retrieving instance
     *
     * @param $fresh
     * @return BetterStudio_Shortcodes
     */
    public static function instance( $fresh = false ){

        if( self::$instance != null && ! $fresh ){
            return self::$instance;
        }

        return self::$instance = new BetterStudio_Shortcodes();
    }


    /**
     * Used for retrieving instance
     *
     * @param $fresh
     * @return mixed
     */
    public static function editor( $fresh = false ){

        if( self::$editor_instance != null && ! $fresh ){
            return self::$editor_instance;
        }

        if( ! class_exists( 'BetterStudio_Editor_Shortcodes' ) )
            require_once plugin_dir_path(__FILE__) . 'includes/editor/class-betterstudio-editor-shortcodes.php';

        return self::$editor_instance = new BetterStudio_Editor_Shortcodes();
    }


    /**
     * Setup shortcodes
     */
    function setup(){

        // loads all shortcode
        $this->load_all_shortcodes();

        // registers shortcodes
        add_action( 'init', array( $this, 'register_all_shortcodes' ), 50 );

        if( is_admin() ){
            self::editor();
        }

    }


    /**
     * Loads all active shortcodes
     *
     * TODO Add popup box
     * TODO Add icons
     */
    function load_all_shortcodes(){

        $this->shortcodes = apply_filters( 'betterstudio-editor-shortcodes',
            array(
                'typography' => array(
                    'type'      =>  'menu',
                    'label'     =>  __( 'Typography', 'betterstudio-shortcodes' ),
                    'items'     =>  array(

                        'pullquote' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Pull Quote', 'betterstudio-shortcodes' ),
                            'callback'  =>  'pull_quote',
                            'content'   =>  '[pullquote align="right"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/pullquote]'
                        ),

                        'dropcap' => array(
                            'type'      => 'menu',
                            'label'     => __( 'Dropcap', 'betterstudio-shortcodes' ),
                            'items'     => array(

                                'dropcap' => array(
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Dropcap - Simple', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'dropcap',
                                    'content'   =>  '[dropcap]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/dropcap]'
                                ),
                                'dropcap-square' => array(
                                    'register'  =>  false,
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Dropcap - Square', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'dropcap',
                                    'content'   =>  '[dropcap style="square"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/dropcap]'
                                ),
                                'dropcap-square-outline' => array(
                                    'register'  =>  false,
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Dropcap - Square Outline', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'dropcap',
                                    'content'   =>  '[dropcap style="square-outline"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/dropcap]'
                                ),
                                'dropcap-circle' => array(
                                    'register'  =>  false,
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Dropcap - Circle', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'dropcap',
                                    'content'   =>  '[dropcap style="circle"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/dropcap]'
                                ),
                                'dropcap-circle-outline' => array(
                                    'register'  =>  false,
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Dropcap - Circle Outline', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'dropcap',
                                    'content'   =>  '[dropcap style="circle-outline"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/dropcap]'
                                ),



                            )
                        ),

                        'highlight' => array(
                            'type'      => 'menu',
                            'label'     => __( 'Highlighted Text', 'betterstudio-shortcodes' ),
                            'items'     => array(

                                'highlight' => array(
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Highlight Yellow', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'highlight',
                                    'content'   =>  '[highlight]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/highlight]'
                                ),

                                'highlight-red' => array(
                                    'register'  =>  false,
                                    'type'      =>  'button',
                                    'label'     =>  __( 'Highlight Red', 'betterstudio-shortcodes' ),
                                    'callback'  =>  'highlight',
                                    'content'   =>  '[highlight style="red"]' . __( 'INSERT HERE' ,'betterstudio-shortcodes' ) . '[/highlight]'
                                ),

                            )
                        ),
                    )
                ), // /Typography

                'tabs' => array(
                    'type'      =>  'button',
                    'label'     =>  __( 'Tabs', 'betterstudio-shortcodes' ),
                    'callback'  =>  'tabs',
                    'content'   =>  '[tabs]<br />\
                                        [tab title="' . __( 'Tab 1', 'betterstudio-shortcodes' )  . '"]' . __( 'Tab 1 content...', 'betterstudio-shortcodes' )  . '[/tab]<br />\
                                        [tab title="' . __( 'Tab 2', 'betterstudio-shortcodes' )  . '"]' . __( 'Tab 2 content...', 'betterstudio-shortcodes' )  . '[/tab]<br />\
                                    [/tabs]<br />\ '
                ),
                'tab' => array( 'callback'  => 'tab' ),

                'columns' => array(
                    'type'      => 'menu',
                    'label'     => __( 'Columns', 'betterstudio-shortcodes' ),
                    'items'     => array(
                        'columns' => array(
                            'type'      =>  'button',
                            'label'     =>  __( '2 Column', 'betterstudio-shortcodes' ),
                            'callback'  =>  'columns',
                            'content'   =>  '[columns]<br />\
                                                [column size="1/2"]' . __( 'Column 1...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/2"]' . __( 'Column 2...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                            [/columns]<br />\ '
                        ),
                        'column' => array( 'callback'  => 'column' ),

                        'columns-3' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( '3 Column', 'betterstudio-shortcodes' ),
                            'content'   =>  '[columns]<br />\
                                                [column size="1/3"]' . __( 'Column 1...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/3"]' . __( 'Column 2...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/3"]' . __( 'Column 3...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                            [/columns]<br />\ '
                        ),

                        'columns-4' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( '4 Column', 'betterstudio-shortcodes' ),
                            'content'   =>  '[columns]<br />\
                                                [column size="1/4"]' . __( 'Column 1...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/4"]' . __( 'Column 2...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/4"]' . __( 'Column 3...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                                [column size="1/4"]' . __( 'Column 4...', 'betterstudio-shortcodes' )  . '[/column]<br />\
                                            [/columns]<br />\ '
                        ),

                    )
                ), // /Columns

                'button' => array(
                    'type'      => 'menu',
                    'label'     => __( 'Buttons', 'betterstudio-shortcodes' ),
                    'items'     => array(

                        'button' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Button - Large', 'betterstudio-shortcodes' ),
                            'callback'  =>  'button',
                            'content'   =>  '[button link="#link" size="large"]' . __( 'Large Button', 'betterstudio-shortcodes' )  . '[/button]'
                        ),

                        'button-medium' => array(
                            'register'  =>  false,
                            'type'      =>  'button',
                            'label'     =>  __( 'Button - Medium', 'betterstudio-shortcodes' ),
                            'callback'  =>  'button',
                            'content'   =>  '[button link="#link" size="medium"]' . __( 'Medium Button', 'betterstudio-shortcodes' )  . '[/button]'
                        ),

                        'button-small' => array(
                            'register'  =>  false,
                            'type'      =>  'button',
                            'label'     =>  __( 'Button - Small', 'betterstudio-shortcodes' ),
                            'callback'  =>  'button',
                            'content'   =>  '[button link="#link" size="small"]' . __( 'Small Button', 'betterstudio-shortcodes' )  . '[/button]'
                        ),
                    )
                ),




                'accordions' => array(
                    'type'      =>  'button',
                    'label'     =>  __( 'Accordions', 'betterstudio-shortcodes' ),
                    'callback'  =>  'accordions',
                    'content'   =>  '[accordions ]<br>[accordion title="Accordion 1 Title" load="show"]Accordion 1 content...[/accordion]<br>[accordion title="Accordion 2 Title" load="hide"]Accordion 2 content...[/accordion]<br>[/accordions]'
                ),
                'accordion' => array( 'callback'  => 'accordion_pane' ),


                'list-drop' => array(
                    'type'      => 'menu',
                    'label'     => __( 'Custom List', 'betterstudio-shortcodes' ),
                    'items'     => array(
                        'list' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Check List', 'betterstudio-shortcodes' ),
                            'callback'  =>  'list_shortcode',
                            'content'   => '[list style="check"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'li' => array( 'callback'  => 'list_item' ),

                        'list-star' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Star List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="star"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'list-edit' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Edit List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="edit"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'list-folder' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Folder List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="folder"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'list-file' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'File List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="file"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'list-heart' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Heart List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="heart"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                        'list-asterisk' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Asterisk List', 'betterstudio-shortcodes' ),
                            'content'   => '[list style="asterisk"]<br>[li]' . __( 'List item 1...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 2...', 'betterstudio-shortcodes' )  . '[/li]<br>[li]' . __( 'List item 3...', 'betterstudio-shortcodes' )  . '[/li]<br>[/list]'
                        ),
                    )
                ), // /Custom List

                'divider-drop' => array(
                    'type'      => 'menu',
                    'label'     => __( 'Dividers', 'betterstudio-shortcodes' ),
                    'items'     => array(
                        'divider' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Divider - Full', 'betterstudio-shortcodes' ),
                            'callback'  =>  'divider',
                            'content'   => '[divider size="full"]'
                        ),
                        'divider-large' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Divider - Large', 'betterstudio-shortcodes' ),
                            'callback'  =>  'divider',
                            'content'   => '[divider size="large"]'
                        ),
                        'divider-small' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Divider - Small', 'betterstudio-shortcodes' ),
                            'callback'  =>  'divider',
                            'content'   => '[divider size="small"]'
                        ),
                        'divider-tiny' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Divider - Tiny', 'betterstudio-shortcodes' ),
                            'callback'  =>  'divider',
                            'content'   => '[divider size="tiny"]'
                        ),
                        'divider-double' => array(
                            'type'      =>  'button',
                            'register'  =>  false,
                            'label'     =>  __( 'Divider - Double Line', 'betterstudio-shortcodes' ),
                            'callback'  =>  'divider',
                            'content'   => '[divider style="double-line"]'
                        ),

                    )
                ), // /Dividers

                'alert-drop' => array(
                    'type'      => 'menu',
                    'label'     => __( 'Alerts', 'betterstudio-shortcodes' ),
                    'items'     => array(
                        'alert' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Alert - Simple', 'betterstudio-shortcodes' ),
                            'callback'  =>  'alert',
                            'content'   => '[alert]' . __( '<strong>Simple!</strong> This is an alert message.', 'betterstudio-shortcodes' ) . '[/alert]',
                        ),
                        'alert-success' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Alert - Success', 'betterstudio-shortcodes' ),
                            'register'  =>  false,
                            'content'   => '[alert type="success"]' . __( '<strong>Well done!</strong> You successfully read this important alert message.', 'betterstudio-shortcodes' ) . '[/alert]',
                        ),
                        'alert-info' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Alert - Info', 'betterstudio-shortcodes' ),
                            'register'  =>  false,
                            'content'   => '[alert type="info"]' . __( "<strong>Heads up!</strong> This alert needs your attention, but it\'s not super important.", 'betterstudio-shortcodes' ) . '[/alert]',
                        ),
                        'alert-warning' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Alert - Warning', 'betterstudio-shortcodes' ),
                            'register'  =>  false,
                            'content'   => '[alert type="warning"]' . __( "<strong>Warning!</strong> Better check yourself, you\'re not looking too good.", 'betterstudio-shortcodes' ) . '[/alert]',
                        ),
                        'alert-danger' => array(
                            'type'      =>  'button',
                            'label'     =>  __( 'Alert - Danger', 'betterstudio-shortcodes' ),
                            'register'  =>  false,
                            'content'   => '[alert type="danger"]' . __( '<strong>Oh snap!</strong> Change a few things up and try submitting again.', 'betterstudio-shortcodes' ) . '[/alert]',
                        ),
                    )
                ), // /Alerts
            )
        );

    }


    /**
     * Register shortcode from nested array
     *
     * @param $shortcode_key
     * @param $shortcode
     */
    function register_shortcode( $shortcode_key, $shortcode ){

        // Menu
        if( isset( $shortcode['type'] ) && $shortcode['type'] == 'menu' ){

            foreach( (array) $shortcode['items'] as $_shortcode_key => $_shortcode_value ){

                $this->register_shortcode( $_shortcode_key, $_shortcode_value );

            }

            return;

        }

        // Do not register shortcode
        if( isset( $shortcode['register'] ) && $shortcode['register'] == false ){
            return;
        }

        // External callback
        if( isset( $shortcode['external-callback'] ) && $shortcode['external-callback'] ){
            add_shortcode( $shortcode_key, $shortcode['external-callback'] );
        }elseif( isset( $shortcode['callback'] ) ){
            add_shortcode( $shortcode_key, array( $this, $shortcode['callback'] ) );
        }

    }


    /**
     * Registers all active shortcodes
     */
    function register_all_shortcodes(){

        foreach ($this->shortcodes as $shortcode_key => $shortcode){

            $this->register_shortcode( $shortcode_key, $shortcode );

        }

    }


    /**
     * Shortcode: Pull Quote
     */
    public function pull_quote($atts, $content = null) {

        extract( shortcode_atts( array( 'align' => 'right' ), $atts ), EXTR_SKIP );

        return '<blockquote class="pullquote align' . $align . '">' . do_shortcode( $content ) . '</blockquote>';

    }


    /**
     * Shortcode: Dropcap
     */
    public function dropcap( $atts, $content = null ){

        extract( shortcode_atts( array( 'style' => '' ), $atts ), EXTR_SKIP );

        return '<span class="dropcap ' . $style . '">' . do_shortcode( $content ) . '</span>';

    }


    /**
     * Shortcode: Highlight
     */
    public function highlight( $atts, $content = null ){

        extract( shortcode_atts( array( 'style' => 'yellow' ), $atts ), EXTR_SKIP );

        return '<mark class="highlight ' . $style . '">' . do_shortcode( $content ) . '</mark>';

    }


    /**
     * Shortcode: Divider
     */
    public function divider( $atts, $content = null ){

        extract( shortcode_atts( array( 'style' => 'full', 'size' => 'large' ), $atts ), EXTR_SKIP );

        return '<hr class="bs-divider ' . $style . ' ' . $size . '">';

    }


    /**
     * Shortcode: Alert
     */
    public function alert( $atts, $content = null ){

        extract( shortcode_atts( array( 'type' => 'simple' ), $atts ), EXTR_SKIP );


        return '<div class="bs-shortcode-alert alert alert-' . $type . '" role="alert">' . do_shortcode( $content ) . '</div>';

    }


    /**
     * Shortcode: Button
     */
    public function button( $atts, $content = null ){

        extract(
            shortcode_atts(
                array(
                    'style'     => 'default',
                    'link'      => '#link',
                    'size'      => 'medium',
                    'target'    => ''
                ),
                $atts
            ),
            EXTR_SKIP
        );

        $size = str_replace(
            array(
                'large',
                'medium',
                'small',
            ),
            array(
                'lg',
                'sm',
                'xs'
            ),
            $size
        );

        return '<a class="btn btn-' . $style . ' btn-' . $size . '" href="' . $link . '" target="' . $target . '">' . do_shortcode( $content ) . '</a>';
    }


    /**
     * Shortcode: Tabs
     */
    public function tabs( $atts, $content = null ){

        $this->temp['tab_count'] = 0;

        // parse nested shortcodes and collect data to temp
        do_shortcode($content);

        if( is_array( $this->temp['tabs'] ) ){

            $count = 0;

            foreach( $this->temp['tabs'] as $tab ){
                $count++;

                $tab_class = ( $count == 1 ? ' class="active"' : '' );

                $tab_pane_class = ( $count == 1 ? ' class="active tab-pane"' : ' class="tab-pane"' );

                $tabs[]  = '<li'. $tab_class .'><a href="#tab-'. $count .'" data-toggle="tab">' . $tab['title'] . '</a></li>';
                $panes[] = '<li id="tab-'. $count .'"'. $tab_pane_class .'>'. $tab['content'] . '</li>';
            }

            $output =
                '<div class="bs-tab-shortcode">
                    <ul class="nav nav-tabs" role="tablist">' . implode( '', $tabs ) . '</ul>
                    <div class="tab-content">' . implode( "\n", $panes ) . '</div>
                </div>';
        }

        unset( $this->temp['tabs'], $this->temp['tab_count'] );

        return $output;

    }


    /**
     * Shortcode Helper: Part of Tabs
     */
    public function tab( $atts, $content = null ){

        extract( shortcode_atts( array( 'title' => 'Tab %d' ), $atts ), EXTR_SKIP );

        $this->temp['tabs'][$this->temp['tab_count']] = array('title' => sprintf( $title, $this->temp['tab_count'] ), 'content' => do_shortcode( $content ) );

        $this->temp['tab_count']++;

    }


    /**
     * Shortcode: Columns
     */
    public function columns( $atts, $content = null ){

        extract( shortcode_atts( array( 'class' => '' ), $atts ) );

        $classes = array( 'row', 'bs-row-shortcode' );

        if( $class ){
            $classes = array_merge( $classes, explode( ' ', $class ) );
        }

        $output  = '<div class="'. implode(' ', $classes) .'">';

        $this->temp['columns'] = array();

        // parse nested shortcodes and collect data
        do_shortcode( $content );

        foreach( $this->temp['columns'] as $column ){
            $output .= $column;
        }

        unset( $this->temp['columns'] );

        return $output .'</div>';
    }


    /**
     * Shortcode Helper: Column
     */
    public function column( $atts, $content = null ){
        extract(
            shortcode_atts( array(
                    'size'      => '1/1',
                    'class'     => '',
                    'text_align'=> ''
                ),
                $atts
            ),
            EXTR_SKIP
        );

        $classes = array( 'column' );

        if( $class ) {
            $classes = array_merge( $classes, explode( ' ', $class ) );
        }

        if( stristr( $size, '/' ) ){

            $size = str_replace(
                array(
                    '1/1',
                    '1/2',
                    '1/3',
                    '1/4',
                ),
                array(
                    'col-lg-12',
                    'col-lg-6',
                    'col-lg-4',
                    'col-lg-3',
                ),
                $size
            );

        }else{
            $size = 'col-lg-6';
        }

        // Add size to column classes
        array_push( $classes, $size );

        // Add style such as text-align
        $style = '';
        if( in_array( $text_align, array('left', 'center', 'right') ) ){
            array_push( $classes, esc_attr( strip_tags( $text_align ) ) );
        }

        $this->temp['columns'][] = $column = '<div class="'. implode( ' ', $classes ) .'"'. $style . '>' . do_shortcode( $content ) . '</div>';

        return $column;
    }

    /**
     * Shortcode: Accordion
     */
    public function accordions($atts, $content = null){

        $this->temp['accordion_panes'] = array();

        // parse nested shortcodes and collect data
        do_shortcode( $content );

        $id = rand( 1, 999999 );

        $output = '<div class="panel-group bs-accordion-shortcode" id="accordion-' . $id . '">';

        $count = 0;

        foreach( $this->temp['accordion_panes'] as $pane ){

            $count++;

            $active = $pane['load'] == 'show' ? ' in' : '';

            $output .= '<div class="panel panel-default">
                            <div class="panel-heading ' .  ( $active == ' in' ? 'active' : '' )  .'">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-' . $id . '" href="#accordion-' . $id . '-pane-' . $count . '">';
            $output .= ! empty( $pane['title'] ) ? $pane['title'] : __( 'Accordion', 'betterstudio-shortcodes' ) . ' ' . $count;
            $output .= '</a>
                              </h4>
                            </div>
                            <div id="accordion-' . $id . '-pane-' . $count . '" class="panel-collapse collapse ' . $active . '">
                              <div class="panel-body">';
            $output .= $pane['content'];
            $output .= '
                                </div>
                            </div>
                        </div>';

        }

        unset( $this->temp['accordion_panes'] );

        return $output . '</div>';
    }

    /**
     * Shortcode Helper: Accordion
     */
    public function accordion_pane( $atts, $content = null ){

        extract( shortcode_atts( array( 'title' => '', 'load' => 'hide' ), $atts ), EXTR_SKIP );

        $this->temp['accordion_panes'][] = array( 'title' => $title, 'load' => $load, 'content' => do_shortcode( $content ) );

    }

    /**
     * Shortcode: List
     */
    public function list_shortcode( $atts, $content = null ){

        extract( shortcode_atts( array( 'style' => 'check', 'class' => '' ), $atts ), EXTR_SKIP );

        $this->temp['list_style'] = $style;

        // parse nested shortcodes and collect data
        $content = do_shortcode( $content );
        $content = preg_replace( '#^<\/p>|<div>|<\/div>|<p>$#', '', $content );
        $content = preg_replace( '#<\/li><br \/>#', '</li>', $content );
        // no list?
        if( ! preg_match( '#<(ul|ol)[^<]*>#i', $content ) ){

            $content = '<ul>' . $content . '</ul>';

        }

        $content = preg_replace( '#<ul><br \/>#', '<ul>', $content );

        return '<div class="bs-shortcode-list list-style-'. esc_attr( $style ) . $class .'">'. $content .'</div>';
    }


    /**
     * Shortcode Helper: List item
     */
    public function list_item( $atts, $content = null ){

        $icon = '<i class="fa fa-' . $this->temp['list_style'] .'"></i>';

        return '<li>' . $icon . do_shortcode( $content ) . '</li>';

    }


}