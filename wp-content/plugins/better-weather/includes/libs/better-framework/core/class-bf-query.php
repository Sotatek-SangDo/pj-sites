<?php

/**
 *
 * Deprecated!
 *
 * Functions of this file was moved to BF/functions/query.php
 *
 */
class BF_Query {
    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_pages( $extra = array() ){
        return bf_get_pages( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_posts( $extra = array() ){
        return bf_get_posts( $extra );
    }

    /**
     * Deprecated!
     *
     * @return bool|string
     */
    static function get_random_post_link( $echo = true ){
        if( $echo )
            echo bf_get_random_post_link( $echo );
        else
            return bf_get_random_post_link( $echo );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_categories( $extra = array()) {
        return bf_get_categories( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_categories_by_slug( $extra = array() ){
        return bf_get_categories_by_slug( $extra );
    }

    /**
     * Deprecated!
     *
     * @return mixed
     */
    public static function get_tags( $extra = array() )	{
        return bf_get_tags( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_users( $extra = array(), $advanced_ouput = false ){
        return bf_get_users( $extra, $advanced_ouput );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_post_types( $extra = array() ){
        return bf_get_post_types( $extra );
    }

    /**
     * Deprecated!
     *
     * @return mixed
     */
    public static function get_page_templates( $extra = array() ){
        return bf_get_page_templates( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_taxonomies( $extra = array() ){
        return bf_get_taxonomies( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_terms( $tax = 'category', $extra = array() ){
        return bf_get_terms( $tax, $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_roles( $extra = array() ){
        return bf_get_roles( $extra );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_menus( $hide_empty = false ){
        return bf_get_menus( $hide_empty );
    }

    /**
     * Deprecated!
     *
     * @return bool|mixed
     */
    public static function is_a_category( $id = null ){
        return bf_is_a_category( $id );
    }

    /**
     * Deprecated!
     *
     * @return bool|mixed
     */
    public static function is_a_tag( $id = null ){
        return bf_is_a_tag( $id );
    }

    /**
     * Deprecated!
     *
     * @return array
     */
    public static function get_rev_sliders(){
        return bf_get_rev_sliders();
    }
} // BF_Query