<?php
/**
 * BF automatic custom css generator
 */
class BF_Front_End_CSS extends BF_Custom_CSS {


    /**
     * Temp
     * 
     * @var
     */
    var $final_css;


    /**
     * prepare functionality
     */
    function __construct(){

        // register custom css
        add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'), 99);
        add_action( 'wp_head', array($this, 'wp_head'), 99);

        // Callbacks function for clearing cache when widgets changed
        add_filter( 'widget_update_callback', array( $this , 'clear_widgets_cache_on_update') ,10,3);
        add_action( 'sidebar_admin_setup', array( $this , 'clear_widgets_cache_on_add_delete') );

        // Callbacks functions for clearing cache when terms changed
        add_filter( 'create_term', array( $this , 'clear_terms_cache_on_update') );
        add_filter( 'edit_term', array( $this , 'clear_terms_cache_on_update') );
        add_filter( 'delete_term', array( $this , 'clear_terms_cache_on_update') );

        // Callback function for clearing cache when Menus updated
        add_action('wp_update_nav_menu', array( $this, 'clear_menus_cache_on_update' ) );

    }


    /**
     * Callback: Register BF custom css codes for theme specified fields
     *
     * action: wp_enqueue_scripts
     */
    function wp_enqueue_scripts(){

        $this->final_css = $this->prepare_final_css();

        // Adds fonts to page
        if( isset( $this->final_css['fonts'] ) && ! empty( $this->final_css['fonts'] ) ){
            foreach( (array) $this->final_css['fonts'] as $key => $font ){
                if( $key == 0 ){
                    wp_enqueue_style( 'better-framework-main-fonts', $font, array(), NULL );
                }else{
                    wp_enqueue_style( 'better-framework-font-' . $key, $font, array(), NULL );
                }
            }
        }

    }


    /**
     * Callback: Print auto generated css in header
     *
     * Action: wp_head
     */
    function wp_head(){

        if( ! empty( $this->final_css['css'] ) ){
            echo "\n<!-- BetterFramework Auto Generated CSS -->\n<style type='text/css' media='screen'>" .  $this->final_css['css'] . "</style>\n<!-- /BetterFramework Auto Generated CSS -->\n";
        }

        // clear memory
        unset( $this->final_css );
    }


    /**
     * Action callback: Output Custom CSS
     */
    public function global_custom_css(){

        // just when custom css requested
        if( empty( $_GET['better_framework_css'] ) OR intval( $_GET['better_framework_css'] ) != 1 )  return;

        $this->display();

        exit;
    }


    /**
     * clear cache (transient)
     *
     * @param string $type
     */
    public static function clear_cache( $type = 'all' ){

        global $wpdb;

        switch( $type ){

            case 'widgets':
                delete_transient( '__better_framework__widgets_css' );
                self::clear_cache( 'final' );
                break;

            case 'panel':
                $wpdb->query( $wpdb->prepare( "
                      DELETE
                      FROM $wpdb->options
                      WHERE option_name LIKE %s
                  ", '_transient___better_framework__panel_css%' ) );
                self::clear_cache( 'final' );
                break;

            case 'menu':
                delete_transient( '__better_framework__menu_css' );
                self::clear_cache( 'final' );
                break;

            case 'terms':
                delete_transient( '__better_framework__terms_css' );
                self::clear_cache( 'final' );
                break;

            case 'final':
                $wpdb->query( $wpdb->prepare( "
                      DELETE
                      FROM $wpdb->options
                      WHERE option_name LIKE %s
                  ", '_transient___better_framework__final_fe_css%' ) );
                $wpdb->query( $wpdb->prepare( "
                      DELETE
                      FROM $wpdb->options
                      WHERE option_name LIKE %s
                  ", '_transient___better_framework__final_fe_css_version%' ) );

                break;


            case 'all':
                delete_transient( '__better_framework__widgets_css' );
                self::clear_cache( 'panel' );
                delete_transient( '__better_framework__menu_css' );
                delete_transient( '__better_framework__terms_css' );
                self::clear_cache( 'final' );

        }

    }


    /**
     * Clear terms cache when 1 term added, updated or deleted
     */
    function clear_terms_cache_on_update() {

        $this->clear_cache( 'terms' );

    }


    /**
     * Clear menu cache when 1 menu updated
     *
     * @param $nav_menu_selected_id
     */
    function clear_menus_cache_on_update( $nav_menu_selected_id ) {

        $this->clear_cache( 'menu' );

    }


    /**
     * Clear widgets cache when update changed
     *
     * @param   $instance
     * @param   $new_instance
     * @param   $old_instance
     *
     * @return  mixed
     */
    function clear_widgets_cache_on_update( $instance, $new_instance, $old_instance){

        self::clear_cache( 'widgets' );

        return $instance;
    }


    /**
     * Clear widgets cache when add new or delete widget
     */
    function clear_widgets_cache_on_add_delete() {

        if( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) ){

            if( isset( $_POST['delete_widget'] ) && ( 1 === (int) $_POST['delete_widget'] ) ){
                if( 1 === (int) $_POST['delete_widget'] ){
                    self::clear_cache('widgets');
                }
            }
            elseif( isset( $_POST['add_new'] ) && ( 1 === (int) $_POST['add_new'] ) ){
                if( 1 === (int) $_POST['add_new'] ){
                    self::clear_cache('widgets');
                }
            }

        }
    }


    /**
     * display css
     */
    function display(){

        status_header( 200 );
        header( "Content-type: text/css; charset: utf-8" );

        $final_fe_css = $this->prepare_final_css();

        echo $final_fe_css['css'];

    }


    /**
     * Load all fields
     */
    function load_all_fields(){

        /**
         * Filter custom css code
         *
         * @since 1.0.0
         *
         * @param array    $fields   All active fields that should be rendered
         */
        $this->fields = apply_filters( 'better-framework/css/main/fields' , $this->fields );

        // load and prepare panel css
        $this->load_panel_fields();

        // Load and prepare widgets css
        $this->load_widget_fields();

        // Load and prepare menus css
        $this->load_menus_css();

        // Load and prepare taxonomies css
        $this->load_terms_css();

    }


    /**
     * Prepare final CSS
     */
    function prepare_final_css(){

        $lang = bf_get_current_language_option_code();

        // Checks both theme version number and last update date for loading again fields
        if( ( $final_css_version = get_transient( '__better_framework__final_fe_css_version' . $lang ) ) !== false ){

            $final_css_version = explode( '-', $final_css_version );

            if( $final_css_version[0] != Better_Framework::theme()->get('Version') ){
                $final_css_version = true;
            }else{
                $final_css_version = false;
            }
        }else{
            $final_css_version = true;
        }

        if( $final_css_version || ( false == ( $final_css = get_transient( '__better_framework__final_fe_css' . $lang ) ) ) ){
            $this->load_all_fields();

            $final_css = array();
            $final_css['css'] = $this->render_css();

            // Include theme Fonts to top
            $theme_fonts = $this->render_fonts( 'theme-fonts' );
            if( ! empty( $theme_fonts ) ){
                $theme_fonts = '/* ' . __( 'Theme Fonts', 'better-studio' ) . ' */' . $theme_fonts . "\n";
                $final_css['css'] = $theme_fonts . $final_css['css'];
            }

            // Include custom Fonts to top
            $custom_fonts = $this->render_fonts( 'custom-fonts' );
            if( ! empty( $custom_fonts ) ){
                $custom_fonts = '/* ' . __( 'Custom Fonts', 'better-studio' ) . ' */' . $custom_fonts . "\n";
                $final_css['css'] = $custom_fonts . $final_css['css'];
            }

            $final_css['fonts'] = $this->render_fonts( 'google-fonts' );

            set_transient( '__better_framework__final_fe_css' . $lang , $final_css );
            set_transient( '__better_framework__final_fe_css_version' . $lang , Better_Framework::theme()->get('Version') . '-' . time() );
        }

        return $final_css;
    }


    /**
     * Load all taxonomies custom css and add them to queue
     */
    function load_terms_css(){

        //load from cache if available
        if( true == ( $cached_fields = get_transient( '__better_framework__terms_css' ) ) ) {
            $this->fields = array_merge( $this->fields , $cached_fields );
            return;
        }else{
            $cached_fields = array();
        }

        $taxonomy_options = Better_Framework::factory('taxonomy-meta')->get_taxonomy_options();

        // Find taxonomies that have field
        foreach( $taxonomy_options as $taxonomy_option ){

            if( isset( $taxonomy_option['panel-id'] ) ){
                $std_id = Better_Framework::options()->get_std_field_id( $taxonomy_option['panel-id'] );
                $css_id = $this->get_css_id( $taxonomy_option['panel-id'] );
            }else{
                $std_id = 'std';
                $css_id = 'css';
            }

            // Iterate each taxonomy in config
            foreach( (array) $taxonomy_option['config']['taxonomies'] as $tax_key ){

                // load all terms of taxonomy
                $all_tax_terms = get_terms( $tax_key , array( 'fields' => 'all' ));

                // each term of taxonomy
                foreach( $all_tax_terms as $term ){

                    // each taxonomy custom field
                    foreach( $taxonomy_option['fields'] as $field_option_key => $field_option_value ){

                        // continue when haven't css field
                        if( ! isset( $field_option_value[$css_id] ) )
                            if( ! isset( $field_option_value['css'] ) )
                                continue;

                        // continue if haven't saved value for this field
                        if( !( $term_value = bf_get_term_meta($field_option_key,$term->term_id)) ){
                            continue;
                        }

                        // if value saved and is difference than default value
                        if( isset( $field_option_value[$std_id] ) && $term_value == $field_option_value[$std_id] ){
                            continue;
                        }
                        elseif( isset( $field_option_value['std'] ) && $term_value == $field_option_value['std'] ){
                            continue;
                        }

                        if( isset( $field_option_value[$css_id] ) ){
                            $_temp_css_field = $field_option_value[$css_id];
                        }elseif( isset( $field_option_value['css'] ) ){
                            $_temp_css_field = $field_option_value['css'];
                        }else{
                            continue;
                        }

                        // prepare selectors
                        foreach( $_temp_css_field as $_temp_css_field_key => $_temp_css_field_value ){

                            // prepare selectors
                            if( is_array( $_temp_css_field[$_temp_css_field_key]['selector'] ) ){

                                foreach( $_temp_css_field[$_temp_css_field_key]['selector'] as $selector_key => $selector ){
                                    if( strpos( $selector, '%%id%%' ) !== false){
                                        $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] = str_replace( '%%id%%', $term->term_id, $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] );
                                    }
                                }

                            }else{
                                $_temp_css_field[$_temp_css_field_key]['selector'] = str_replace( '%%id%%', $term->term_id, $_temp_css_field[$_temp_css_field_key]['selector'] );
                            }

                        }

                        if( is_array($term_value) ){
                            $_temp_css_field['value'] = $term_value;
                        }else{
                            $_temp_css_field['value'] = stripcslashes( $term_value );
                        }

                        $cached_fields[] = $_temp_css_field;

                    }

                }

            }

        }

        if( count($cached_fields) ){
            array_unshift( $cached_fields , array(
                'value' => 'c',
                'type'  => 'comment',
                array(
                    'comment' => ' ' . __( 'Terms Custom CSS', 'better-studio' ) . ' '
                )
            ));
            array_unshift( $cached_fields , array( 'newline'=> true ) );
            array_unshift( $cached_fields , array( 'newline'=> true ) );
            $this->fields = array_merge( $this->fields , $cached_fields );
        }
        set_transient( '__better_framework__terms_css', $cached_fields );
    }


    /**
     * Load Menus fields and add theme to queue
     */
    function load_menus_css(){

        // Load from cache if available
        if( true == ( $cached_fields = get_transient( '__better_framework__menu_css' ) ) ){
            $this->fields = array_merge( $this->fields , $cached_fields );
            return;
        }else{
            $cached_fields = array();
        }

        $menu_fields = Better_Framework::factory('better-menu')->fields;

        // each registered navigation menu locations that a menu assigned to it
        // TODO menus that have not assigned to location but used in widgets not included in this! fix this
        foreach( ( (array) get_nav_menu_locations() ) as $menu_id => $menu_slug ){

            if( is_null( $menu_slug ) )
                continue;

            // each item of menu
            foreach( (array)wp_get_nav_menu_items( $menu_slug ) as $menu_item ){

                // all fields that registered to menus
                foreach( (array) $menu_fields as $field_id => $field){

                    // prepare std and css id
                    if( isset( $field['panel-id'] ) && isset( Better_Framework::options()->options[$field['panel-id']]['style'] ) ){

                        $current_style_of_panel = get_option( $field['panel-id'] . '_current_style' );

                        if( $current_style_of_panel == 'default' ){
                            $std_id = 'std';
                            $css_id = 'css';
                        }
                        else{
                            $std_id = 'std-' . $current_style_of_panel;
                            $css_id = 'css-' . $current_style_of_panel;
                        }
                    }else{

                        $std_id = 'std';
                        $css_id = 'css';

                    }

                    // just fields with css
                    if( ! isset( $field[$css_id] ) )
                        if( ! isset( $field['css'] ) )
                            continue;

                    if( ! isset( $menu_item->{$field_id} ) ){
                        continue;
                    }

                    // if item has key and value is difference than default color
                    if( isset( $field[$std_id] ) && $menu_item->{$field_id} == $field[$std_id] ){
                        continue;
                    }
                    elseif( isset( $field['std'] ) && $menu_item->{$field_id} == $field['std'] ){
                        continue;
                    }


                    if( isset( $field[$css_id] ) ){
                        $_temp_css_field = $field[$css_id];
                    }else{
                        $_temp_css_field = $field['css'];
                    }

                    // prepare selectors
                    foreach( $_temp_css_field as $_temp_css_field_key => $_temp_css_field_value ){

                        // prepare selectors
                        if( is_array( $_temp_css_field[$_temp_css_field_key]['selector'] ) ){
                            foreach( $_temp_css_field[$_temp_css_field_key]['selector'] as $selector_key => $selector ){

                                if( strpos( $selector, '%%id%%' ) !== false){
                                    $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] = str_replace( '%%id%%' , '#menu-item-'.$menu_item->ID , $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] );
                                }

                                if( strpos( $selector, '%%class%%' ) !== false){
                                    $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] = str_replace( '%%class%%' , '.menu-item-'.$menu_item->ID , $_temp_css_field[$_temp_css_field_key]['selector'][$selector_key] );
                                }

                            }

                        }else{
                            $_temp_css_field[$_temp_css_field_key]['selector'] = str_replace( '%%class%%' , '.menu-item-'.$menu_item->ID , $_temp_css_field[$_temp_css_field_key]['selector'] );
                            $_temp_css_field[$_temp_css_field_key]['selector'] = str_replace( '%%id%%' , '#menu-item-'.$menu_item->ID , $_temp_css_field[$_temp_css_field_key]['selector'] );
                        }

                    }

                    $_temp_css_field['value'] = $menu_item->{$field_id};

                    $cached_fields[] = $_temp_css_field;

                }
            }
        }

        if( count($cached_fields) ){
            array_unshift( $cached_fields , array(
                'value' => 'c',
                'type'  => 'comment',
                array(
                    'comment' => ' ' . __( 'Menus Custom CSS', 'better-studio' ) . ' '
                )
            ));
            array_unshift( $cached_fields , array( 'newline'=> true ) );
            array_unshift( $cached_fields , array( 'newline'=> true ) );
            $this->fields = array_merge( $this->fields , $cached_fields );
        }
        set_transient( '__better_framework__menu_css' , $cached_fields );

    }


    /**
     * Load Panel options fields and add them to queue
     *
     */
    function load_panel_fields(){

        $_lang = bf_get_current_language_option_code();

        //load from cache if available
        if( true == ( $cached_fields = get_transient( '__better_framework__panel_css' . $_lang ) ) ){
            $this->fields = array_merge( $this->fields, $cached_fields );
            return;
        } else {
            $cached_fields = array();
        }

        // iterates all panels for css and adds them to css render list
        foreach( Better_Framework::options()->options as $panel_id => $panel_value ) {

            // Prepare std id
            $std_id = Better_Framework::options()->get_std_field_id( $panel_id );
            $css_id = $this->get_css_id( $panel_id );

            // load saved value
            $panel_saved_options = get_option( $panel_id . $_lang );

            // load options from filter
            $panel_options = $panel_value['fields'];

            // check each option field
            foreach( (array) $panel_options as $panel_option_key => $panel_option_value ){

                // must have css field
                if( ! isset( $panel_option_value[$css_id] ) )
                    if( ! isset( $panel_option_value['css'] ) )
                        continue;

                // if field hasn't value
                if( ! isset( $panel_saved_options[$panel_option_key] ) )
                    continue;

                if( isset( $panel_option_value[$std_id] ) ){
                    if( $panel_saved_options[$panel_option_key] == $panel_option_value[$std_id] ){
                        if( ! isset( $panel_option_value['css-echo-default'] ) || ! $panel_option_value['css-echo-default'] ){
                            continue;
                        }
                    }
                }
                elseif( isset( $panel_option_value['std'] ) ){
                    if( $panel_saved_options[$panel_option_key] == $panel_option_value['std'] ){
                        if( ! isset( $panel_option_value['css-echo-default'] ) || ! $panel_option_value['css-echo-default'] ){
                            continue;
                        }
                    }
                }

                if( isset( $panel_option_value[$css_id] ) ){
                    $_field = $panel_option_value[$css_id];
                } else {
                    $_field = $panel_option_value['css'];
                }

                $_field['value'] = $panel_saved_options[$panel_option_key];

                $cached_fields[] = $_field;

            }
        }

        if( count( $cached_fields ) ){
            array_unshift( $cached_fields , array(
                'value' => 'c',
                'type'  => 'comment',
                array(
                    'comment' => ' ' . __( 'Panel Options Custom CSS', 'better-studio' ) . ' '
                )
            ));
            $this->fields = array_merge( $this->fields , $cached_fields );

        }
        set_transient( '__better_framework__panel_css' . $_lang, $cached_fields );
    }


    /**
     *  Load widget fields and add to queue
     */
    function load_widget_fields(){

        // load from cache if available
        if ( true == ( $cached_widgets_fields = get_transient( '__better_framework__widgets_css' ) ) ) {
            $this->fields = array_merge( $this->fields , $cached_widgets_fields );
            return;
        }else{
            $cached_widgets_fields = array();
        }

        // TODO: Refactor this code to better if you can :D

        // filter widgets css fields
        $fields = apply_filters( 'better-framework/css/widgets' , array() );

        // if fields set
        if( ! is_array( $fields ) || count( $fields ) < 1 ) return;

        // load all active sidebars
        $sidebars = get_option( 'sidebars_widgets' );

        // remove inactive sidebar from all sidebars list
        unset( $sidebars['wp_inactive_widgets'] );
        unset( $sidebars['array_version'] );


        foreach( (array) $sidebars as $sidebar_key => $sidebar_value ){

            if( strpos( $sidebar_key, 'orphaned_widgets' ) !== false ) continue;

            // is sidebar or active sidebar
            if( ! is_active_sidebar( $sidebar_key ) ) continue;

            foreach( (array) $sidebar_value as $widget ){

                // remove widget number from id
                if( preg_match( '/(-[\d]).*+/i', $widget ) )
                    $widget_name = preg_replace( '/(-[\d]).*/i', '',  $widget);
                else
                    $widget_name = $widget;

                preg_match( '/([\d]).*+/i', $widget , $widget_id);
                $widget_id = $widget_id[0];

                // get active instances of this widget
                $sidebar_widgets = get_option( 'widget_' . $widget_name );

                // if widget just is in use but "not active"
                $_is_widget_active = @is_active_widget( false, $widget, $widget_name ) == '';
                if( $_is_widget_active || strpos( $_is_widget_active, 'orphaned_widgets' ) !== false ){
                    continue;
                }

                // check each field for css fields
                foreach( (array) $sidebar_widgets[$widget_id] as $widget_field_key => $widget_field_value){

                    // check each filtered css fields
                    foreach( (array) $fields as $css_field ){

                        // if is a css field then prepare field and add to final fields list
                        if( $widget_field_key == $css_field['field'] ){

                            // skip when value is equal to default!
                            if( BF_Widgets_General_Fields::is_valid_field( $widget_field_key ) ){
                                if( BF_Widgets_General_Fields::get_default_value( $widget_field_key ) == $widget_field_value )
                                    continue;
                            }elseif( isset( $css_field['default_value'] ) && $css_field['default_value'] == $widget_field_value )
                                continue;

                            $_temp_css_field = $css_field;

                            // prepare selectors: replace "%%widget-id%%" with widget id
                            foreach( (array) $_temp_css_field as $_temp_field_key => $temp_field_val ){

                                // skip "value" and "field" fields
                                if( ! is_int($_temp_field_key )  ) continue;

                                foreach( (array) $_temp_css_field[$_temp_field_key] as $_t_key => $_t_value ){

                                    // if is selector field in array
                                    if( $_t_key != 'selector' ) continue;

                                    if( ! isset( $_temp_css_field[$_temp_field_key]['selector'] ) ) continue;

                                    if( is_array( $_temp_css_field[$_temp_field_key]['selector'] ) ){
                                        foreach( $_temp_css_field[$_temp_field_key]['selector'] as $selector_key => $selector ){
                                            if( strpos( $selector, '%%widget-id%%' ) !== false){
                                                $_temp_css_field[$_temp_field_key]['selector'][$selector_key] = str_replace( '%%widget-id%%' , '#'.$widget , $_temp_css_field[$_temp_field_key]['selector'][$selector_key] );
                                            }
                                        }

                                    }else{
                                        $_temp_css_field[$_temp_field_key]['selector'] = str_replace( '%%widget-id%%' , '#'.$widget , $_temp_css_field[$_temp_field_key]['selector'] );
                                    }

                                }

                            }

                            $_temp_css_field['value'] = $widget_field_value;

                            $cached_widgets_fields[] = $_temp_css_field;
                        }
                    }
                }
            }
        }

        if( count($cached_widgets_fields) ){
            array_unshift( $cached_widgets_fields , array(
                'value' => 'c',
                'type'  => 'comment',
                array(
                    'comment' => ' ' . __( 'Widgets Custom CSS', 'better-studio' ) . ' '
                )
            ));
            array_unshift( $cached_widgets_fields , array( 'newline'=> true ) );
            array_unshift( $cached_widgets_fields , array( 'newline'=> true ) );
            $this->fields = array_merge( $this->fields , $cached_widgets_fields );
        }

        set_transient( '__better_framework__widgets_css' , $cached_widgets_fields );
    }

}