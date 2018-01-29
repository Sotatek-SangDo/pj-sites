<?php
/**
 * Loop Add to Cart
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if( $product->is_type( 'external' ) ){
    echo apply_filters( 'woocommerce_loop_add_to_cart_link',
        sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s"> %s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $product->id ),
            esc_attr( $product->get_sku() ),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            esc_attr( $product->product_type ),
            $product->add_to_cart_text()
        ),
        $product );

}else{
    echo apply_filters( 'woocommerce_loop_add_to_cart_link',
        sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s"><i class="fa fa-shopping-cart cart-icon"></i> %s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $product->id ),
            esc_attr( $product->get_sku() ),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            esc_attr( $product->product_type ),
            $product->add_to_cart_text()
        ),
        $product );

}
