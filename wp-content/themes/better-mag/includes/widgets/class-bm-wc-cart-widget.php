<?php

/**
 * Recent Tab Widget
 */
class BM_WC_Cart_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array();

        parent::__construct(
            'bm-wc-cart',
            __( 'BetterStudio - WooCommerce Cart', 'better-studio' ),
            array( 'description' => __( 'Shop cart widget ( use in aside logo and topbar)', 'better-studio' ) )
        );
    }
}