<?php

/**
 * BetterMag bbPress Compatibility
 */
class BM_bbPress extends BF_bbPress{

    function __construct(){

        parent::__construct();

    }


    function init(){

        parent::init();

        add_action( 'bbp_after_get_user_favorites_link_parse_args', array( $this, 'get_user_favorites_link' ) );

        add_action( 'bbp_after_get_user_subscribe_link_parse_args', array( $this, 'get_user_subscribe_link' ) );

        add_action( 'bbp_after_get_topic_tag_list_parse_args', array( $this, 'get_topic_tag_list' ) );

    }


    /**
     * Action callback: Add WooCommerce assets
     */
    public function register_assets(){

    }


    /**
     * Action callback: Adding Icon to favorite
     */
    public function get_user_favorites_link( $attr ){

        $attr['favorite'] = '<i class="fa fa-heart-o"></i> ' . $attr['favorite'];
        $attr['favorited'] = '<i class="fa fa-heart"></i> ' . $attr['favorited'];

        return $attr;
    }


    /**
     * Action callback: Adding Icon to subscribe
     */
    public function get_user_subscribe_link( $attr ){

        $attr['subscribe'] = '<i class="fa fa-star-o"></i> ' . $attr['subscribe'];
        $attr['unsubscribe'] = '<i class="fa fa-star"></i> ' . $attr['unsubscribe'];

        return $attr;
    }


    /**
     * Action callback: Adding Icon to tags
     */
    public function get_topic_tag_list( $attr ){

        $attr['before'] =  '<div class="bbp-topic-tags"><p><i class="fa fa-tags"></i> ' . esc_html( Better_Translation()->_get( 'bbp_tagged' ) ) . '&nbsp;';

        return $attr;
    }
}