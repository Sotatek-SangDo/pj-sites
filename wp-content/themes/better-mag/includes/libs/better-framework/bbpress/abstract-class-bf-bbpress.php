<?php

/**
 * BetterFramework bbPress Functionality For Themes
 */
abstract class BF_bbPress {

    public function __construct(){

        add_theme_support( 'bbpress' );

        add_filter( 'init', array( $this, 'init' ) );
    }


    /**
     * Register WooCommrece related hooks
     */
    public function init(){

        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

    }


    /**
     * Action callback: Add WooCommerce assets
     */
    abstract public function register_assets();

}