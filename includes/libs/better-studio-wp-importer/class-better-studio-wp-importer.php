<?php

/**
 * Better Studio WP Data Importer
 *
 * Used for importing all types of content simply into WordPress
 *
 *
 * @package  BetterStudio WP Importer
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.0.0
 * @access   public
 * @see      http://www.betterstudio.com
 */
class Better_Studio_WP_Importer {


    function __construct(){

        // Load WP Importer plugin
        $this->load_wp_importer();

    }


    /**
     * Loads importer plugin in safely way.
     * After this you can use WP_Import class.
     *
     * Link: http://wordpress.org/extend/plugins/wordpress-importer/
     */
    function load_wp_importer(){

        // We are loading importers
        if( ! defined( 'WP_LOAD_IMPORTERS' ) ){
            define('WP_LOAD_IMPORTERS', true);
        }

        // If main importer class doesn't exist
        if( ! class_exists( 'WP_Importer' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        }

        // If WP importer doesn't existw
        if( ! class_exists( 'WP_Import' ) ) {
            require_once 'importer/wordpress-importer.php';
        }

    }


    /**
     * Import Posts, Pages, Portfolio Content, FAQ, Images, Menus
     *
     * @param $xml_file
     * @param bool $fetch_attachments
     */
    function import_wp_xml( $xml_file, $fetch_attachments = true ){

        // Action happens before import
        do_action( 'better-wp-importer/wordpress/before' );

        if( ! $xml_file )
            return false;

        $xml_file = apply_filters( 'better-wp-importer/wordpress/file', $xml_file );

        $importer = new WP_Import();

        $importer->fetch_attachments = $fetch_attachments;

        ob_start();

        $importer->import( $xml_file );

        ob_end_clean();


        // Action happens after import
        do_action( 'better-wp-importer/wordpress/after' );
    }


    /**
     * Imports base wp + WooCommerce data
     *
     * @param $xml_file
     * @param bool $fetch_attachments
     */
    function import_woo_wp_xml( $xml_file, $fetch_attachments = true ){

        // Action happens before import
        do_action( 'better-wp-importer/woocommerce/before' );

        $xml_file = apply_filters( 'better-wp-importer/woocommerce/file', $xml_file );

        if( ! $xml_file )
            return false;

        $importer = new WP_Import();

        $importer->fetch_attachments = $fetch_attachments;

        ob_start();

        $importer->import( $xml_file );

        ob_end_clean();

        // Set pages
        $woo_commerce_pages = apply_filters( 'better-wp-importer/woocommerce/pages',
            array(
                'woocommerce_shop_page_id'              =>  'Shop',
                'woocommerce_cart_page_id'              =>  'Cart',
                'woocommerce_checkout_page_id'          =>  'Checkout',
                'woocommerce_pay_page_id'               =>  'Checkout &#8594; Pay',
                'woocommerce_thanks_page_id'            =>  'Order Received',
                'woocommerce_myaccount_page_id'         =>  'My Account',
                'woocommerce_edit_address_page_id'      =>  'Edit My Address',
                'woocommerce_view_order_page_id'        =>  'View Order',
                'woocommerce_change_password_page_id'   =>  'Change Password',
                'woocommerce_logout_page_id'            =>  'Logout',
                'woocommerce_lost_password_page_id'     =>  'Lost Password'
            )
        );

        // Adds WooCommerce pages to options
        foreach( $woo_commerce_pages as $woo_page_name => $woo_page_title ){

            $woo_commerce_page = get_page_by_title( $woo_page_title );

            if( isset( $woo_commerce_page ) && $woo_commerce_page->ID ){
                update_option( $woo_page_name, $woo_commerce_page->ID );
            }

        }

        // We no longer need to install pages
        delete_option( '_wc_needs_pages' );
        delete_transient( '_wc_activation_redirect' );

        // Flush rules after install
        flush_rewrite_rules();

        // Action happens after import
        do_action( 'better-wp-importer/woocommerce/after' );
    }


    /**
     * Used to set imported menus to registered theme locations
     *
     * @param array $menus_config
     */
    function import_menus( $menus_config = array() ){

        // Action happens before import
        do_action( 'better-wp-importer/menus/before' );

        $menus_config = apply_filters( 'better-wp-importer/menus/config', $menus_config );

        if( count( $menus_config ) <= 0 )
            return;

        // Registered menu locations in theme
        $locations = get_theme_mod( 'nav_menu_locations' );

        // Registered menus
        $menus = wp_get_nav_menus();

        foreach( $menus as $menu ){

            if( isset( $menus_config[ $menu->slug ] ) ){

                $locations[$menus_config[ $menu->slug ]] = $menu->term_id;

            }

        }

        // Set menus to locations
        set_theme_mod( 'nav_menu_locations', $locations );

        // Action happens after import
        do_action( 'better-wp-importer/menus/after' );

    }


    /**
     * Used for importing theme options from json file
     *
     * @param $json_url
     * @return bool
     */
    function import_options( $json_url ){

        // Action happens before import
        do_action( 'better-wp-importer/options/before' );

        if( ! $json_url )
            return false;


        // Read option json file
        $theme_options_raw = wp_remote_get( apply_filters( 'better-wp-importer/options/file', $json_url ) );

        if( is_wp_error( $theme_options_raw ) )
            return false;

        $data = apply_filters( 'better-wp-importer/options/data', json_decode( $theme_options_raw['body'], true ) );

        if( ! isset( $data['panel-id'] ) || empty( $data['panel-id'] ) || ! isset( $data['panel-data'] )){

            return false;

        }

        // Save options
        update_option( $data['panel-id'], $data['panel-data'] ) ;

        // Imports style
        if( isset( $data['panel-data']['style'] ) && ! empty( $data['panel-data']['style'] ) ){

            update_option( $data['panel-id'] . '_current_style', $data['panel-data']['style'] );

        }

        // Action happens after import
        do_action( 'better-wp-importer/options/after' );

    }


    /**
     * Advanced widgets importer
     *
     * Thanks to: https://wordpress.org/plugins/widget-importer-exporter/
     *
     * @param $widgets_file
     * @return mixed|void
     */
    function import_widgets( $widgets_file ){

        // Action happens before import
        do_action( 'better-wp-importer/widgets/before' );

        if( ! $widgets_file )
            return false;

        // Read option json file
        $data = wp_remote_get( apply_filters( 'better-wp-importer/widgets/file', $widgets_file )  );

        if( is_wp_error( $data ) )
            return false;

        $data = apply_filters( 'better-wp-importer/widgets/data', json_decode( $data['body'], true ) );

        global $wp_registered_sidebars;

        // Get all available widgets site supports
        $available_widgets = $this->available_widgets();

        // Get all existing widget instances
        $widget_instances = array();
        foreach( $available_widgets as $widget_data ){

            $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );

        }


        // Loop import data's sidebars
        foreach( $data as $sidebar_id => $widgets ){

            // Skip inactive widgets
            // ( should not be in export file )
            if( 'wp_inactive_widgets' == $sidebar_id ){
                continue;
            }

            // Check if sidebar is available on this site
            // Otherwise add widgets to inactive, and say so
            if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
                $sidebar_available = true;
                $use_sidebar_id = $sidebar_id;
            } else {
                $sidebar_available = false;
                $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
            }

            // Loop widgets
            foreach( $widgets as $widget_instance_id => $widget ){

                $fail = false;

                // Get id_base (remove -# from end) and instance ID number
                $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
                $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

                // Does site support this widget?
                if( ! isset( $available_widgets[$id_base] ) ) {
                    $fail = true;
                }

                // Does widget with identical settings already exist in same sidebar?
                if( ! $fail && isset( $widget_instances[$id_base] ) ){

                    // Get existing widgets in this sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' );

                    // check Inactive if that's where will go
                    $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array();

                    // Loop widgets with ID base
                    $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();

                    foreach( $single_widget_instances as $check_id => $check_widget ){

                        // Is widget in same sidebar and has identical settings?
                        if( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ){

                            $fail = true;

                            break;

                        }

                    }

                }

                // No failure
                if( ! $fail ){

                    // Add widget instance
                    // all instances for that widget ID base, get fresh every time
                    $single_widget_instances = get_option( 'widget_' . $id_base );
                    // start fresh if have to
                    $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );
                    // add it
                    $single_widget_instances[] = (array) $widget;

                    // Get the key it was given
                    end( $single_widget_instances );
                    $new_instance_id_number = key( $single_widget_instances );

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                    if ( '0' === strval( $new_instance_id_number ) ) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset( $single_widget_instances[0] );
                    }

                    // Move _multiwidget to end of array for uniformity
                    if( isset( $single_widget_instances['_multiwidget'] ) ){

                        $multiwidget = $single_widget_instances['_multiwidget'];

                        unset( $single_widget_instances['_multiwidget'] );

                        $single_widget_instances['_multiwidget'] = $multiwidget;

                    }

                    // Update option with new widget
                    update_option( 'widget_' . $id_base, $single_widget_instances );

                    // Assign widget instance to sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
                    $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                    $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                    update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

                }

            }

        }


        // Action happens after import
        do_action( 'better-wp-importer/widgets/after' );

    }


    /**
     * Used for findin avaliable widgets
     *
     * @return mixed|void
     */
    function available_widgets() {

        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach( $widget_controls as $widget ){

            if( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ){ // no dupes

                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];

            }

        }

        return apply_filters( 'better-wp-importer/widgets/available', $available_widgets );

    }
}