<?php

/**
 * Used for generating listing, blocks and other display related things
 */
class BM_Block_Generator{


    /**
     * Contains data that used in listings
     *
     * @var array
     */
    static protected $block_atts = array();


    /**
     * Getter for block atts
     *
     * @param string $key
     * @param string $default
     * @return array
     */
    public static function get_attr( $key = '', $default ='' ){

        if( empty( $key ) || ! isset( self::$block_atts[$key] ) )
            return $default;

        return self::$block_atts[$key];
    }


    /**
     * Setter for block_atts
     *
     * @param string $key
     * @param string $value
     */
    public static function set_attr( $key = '', $value = '' ){

        if( empty( $key ) ) return;

        self::$block_atts[$key] = $value;

    }


    /**
     * Used For Removing Attr
     *
     * @param string $key
     */
    public static function unset_attr( $key = '' ){

        if( empty( $key ) ) return;

        unset( self::$block_atts[$key] );

    }


    /**
     * Clears all attributes that saved in $block_atts
     */
    public static function clear_atts(){

        self::$block_atts = array();

    }


    /**
     * Used For Finding Best Count For Multiple columns
     *
     * @param int $count_all
     * @param int $columns
     * @param int $current_column
     */
    public static function set_attr_count_multi_column( $count_all = 0, $columns = 1, $current_column = 1 ){

        if( $count_all == 0 )
            return;

        $count = floor( $count_all / $columns );

        $reminder = $count_all % $columns;

        if( $reminder >= $current_column )
            $count++;

        self::set_attr( "count", $count );
    }


    /**
     * Used For Specifying Count
     */
    public static function set_attr_count( $count ){

        self::set_attr( "count", $count );

    }


    /**
     * Used for adding class to block
     *
     * @param $value
     */
    public static function set_attr_class( $value ){

        if( isset( self::$block_atts['block-class'] ) ){
            self::$block_atts['block-class'] .= ' ' . $value;
        }else{
            self::$block_atts['block-class'] = $value;
        }

    }


    /**
     * Used for retrieving block class attr
     *
     * @param $add_this
     * @return array|string
     */
    public static function get_attr_class( $add_this = '' ){

        if( $add_this )
            return self::get_attr( 'block-class' ) . ' ' . $add_this;
        else
            return self::get_attr( 'block-class' );

    }


    /**
     * Used for specifying thumbnail size
     *
     * @param $value
     */
    public static function set_attr_thumbnail_size( $value ){

        self::$block_atts['thumbnail-size'] = $value;

    }


    /**
     * Used for retrieving block class attr
     *
     * @param $default
     * @return array|string
     */
    public static function get_attr_thumbnail_size( $default = '' ){

        return self::get_attr( 'thumbnail-size', $default );

    }


    /**
     * Used for including block elements
     *
     * @param string $block
     * @param bool $echo
     * @param bool $load
     * @return string
     */
    public static function get_block( $block = '' , $echo = true, $load = true ){

        if(  empty($block) ) return '';

        $template = 'blocks/' . $block . '.php';

        if( $echo ){
            locate_template( $template, $load, false);
        }else{
            ob_start();
            locate_template( $template, $load, false);
            return ob_get_clean();
        }

    }


    /**
     * Used for including menus
     *
     * @param       string      $menu       menu file id
     * @param       bool        $echo
     * @param       bool        $load
     * @return      string
     */
    public static function get_menu( $menu = '' , $echo = true, $load = true ){

        if(  empty($menu) ) return '';

        $template = 'blocks/menu/' . $menu . '.php';

        if( $echo ){
            locate_template( $template, $load, false);
        }else{
            ob_start();
            locate_template( $template, $load, false);
            return ob_get_clean();
        }

    }


    /**
     * Generates bread crumb with BF Breadcrumb
     *
     * @param bool $echo
     * @return bool|string
     */
    public static function breadcrumb( $echo = true ){

        $output = Better_Framework::breadcrumb()->generate( false );

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }

    }


    /**
     * Inner array of objects live instances like blocks
     *
     * @var array
     */
    protected static $instances = array();


    /**
     * Used for retrieving generator of BetterMag
     *
     * @return BM_Blocks
     */
    public static function blocks(){

        if ( isset(self::$instances['blocks']) ) {
            return self::$instances['blocks'];
        }

        $blocks = apply_filters( 'better-mag/blocks', 'BM_Blocks' );
        // It's version -1.5 compatibility
        $blocks = apply_filters( 'better_mag-blocks', $blocks );

        // if filtered class not exists or not child of BM_Blocks class
        if( ! class_exists( $blocks ) || ! is_subclass_of( $blocks, 'BM_Blocks' ) )
            $blocks = 'BM_Blocks';

        self::$instances['blocks'] = new $blocks;
        return self::$instances['blocks'];

    }


    /**
     * Setter for block_atts
     *
     *
     * ==> Parameters
     *
     * -> 'block-class'     => contain class that must be added to listing Ex: vertical-left-line
     *
     * -> 'count'           => count of posts for listing
     *
     * -> 'counter'         => current post location in loop
     *
     * -> 'hide-summary'    => used for hiding in listings
     *
     * -> 'hide-meta'=> used for hiding Meta
     *
     * -> 'hide-meta-author'=> used for hiding Post Author in Meta
     *
     * -> 'hide-meta-comment'=> used for hiding Comment in Meta
     *
     * -> 'thumbnail-size'  => used for specifying thumbnail size
     *
     * -> 'excerpt-length'  => used for specifying thumbnail size
     *
     * -> 'show-term-banner'  => used for showing term banner
     *
     * -> 'hide-meta-author-if-review'  =>
     *
     *
     * @param string $key
     * @param string $value
     * @internal param array $block_atts
     */
//    public static function set_attr( $key = '', $value = '' ){
//        parent::set_attr( $key, $value );
//    }


    /**
     * Returns post main category object
     *
     * @return array|mixed|null|object|WP_Error
     */
    public static function get_post_main_category(){

        // Fix for in category archive page and having multiple category
        if( is_category() ){
            if( has_category( get_query_var( 'cat' ) ) )
                $category = get_category( get_query_var( 'cat' ) );
            else{
                $category = current( get_the_category() );
            }
        }

        // Primary category for singles
        else{

            $prim_cat = bf_get_post_meta( 'bs_primary_category', get_the_ID() );

            if( $prim_cat === 'auto-detect' || $prim_cat == '' ){

                $category = current( get_the_category() );

            }else{

                $category = get_category( $prim_cat );

            }

        }

        return $category;
    }


    /**
     * Used for printing main slider in whole theme
     */
    public static function get_main_slider(){

        // Slider For Home Page
        if( is_home() || is_front_page() ){

            switch( Better_Mag::get_option( 'show_slider' ) ){

                case 'better':

                    $args = array(
                        'post_type'     => array( 'post' ),
                        'posts_per_page'=> 10
                    );

                    if( Better_Mag::get_option( 'slider_cats' ) ){
                        $args['cat'] = Better_Mag::get_option( 'slider_cats' );
                    }

                    if( Better_Mag::get_option( 'slider_tags' ) ){
                        $args['tag__in'] = explode( ',', Better_Mag::get_option( 'slider_tags' ) );
                    }

                    if( Better_Mag::get_option( 'slider_just_featured' ) ){
                        $args['meta_key'] = '_bm_featured_post';
                        $args['meta_value'] = '1';
                    }

                    if( Better_Mag::get_option( 'slider_post_type' ) ){
                        $args['post_type'] = explode( ',', Better_Mag::get_option( 'slider_post_type' ) );
                    }

                    Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/main-slider/home/args', $args ) ) );

                    unset( $args );

                    Better_Mag::generator()->blocks()->print_main_slider( Better_Mag::get_option( 'slider_style' ) );

                    Better_Mag::posts()->clear_query();
                    Better_Mag::generator()->clear_atts();

                    break;

                case 'rev':

                    if( Better_Mag::get_option( 'slider_rev_id' ) != '0' )
                        Better_Mag::generator()->blocks()->print_rev_slider( Better_Mag::get_option( 'slider_rev_id' ) );

                    break;

            }

        }
        // Slider For Categories & Tags
        elseif( is_category() || is_tag() ){

            if( is_category() ){
                $term_id = get_query_var( 'cat' );
            }else{
                $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
                $term_id = $tag->term_id;
            }

            switch( BF()->taxonomy_meta()->get_term_meta( $term_id, 'show_slider' ) ){

                case 'better':

                    $args = array(
                        'post_type'     => 'post',
                        'posts_per_page'=> 10,
                    );

                    if( is_category() ){
                        $args['cat'] = $term_id;

                        if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_tags' ) ){
                            $args['tag__in'] = BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_tags' );
                        }

                    }else{
                        $args['tag_id'] = $term_id;
                    }

                    if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_just_featured' )){
                        $args['meta_key'] = '_bm_featured_post';
                        $args['meta_value'] = '1';
                    }

                    if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_post_type' ) ){
                        $args['post_type'] = explode( ',', BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_post_type' ) );
                    }

                    Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/main-slider/category/args', $args ) ) );
                    unset( $args );

                    if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_style' ) != 'default' )
                        Better_Mag::generator()->blocks()->print_main_slider( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_style' ) );
                    else
                        Better_Mag::generator()->blocks()->print_main_slider( Better_Mag::get_option( 'slider_style' ) );

                    Better_Mag::posts()->clear_query();
                    break;

                case 'rev':

                    if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_rev_id' ) != '0' )
                        Better_Mag::generator()->blocks()->print_rev_slider( BF()->taxonomy_meta()->get_term_meta( $term_id, 'slider_rev_id') );

                    break;


            }

        }
        // Slider For Pages & Posts
        elseif( is_singular() ){

            switch(  Better_Mag::get_meta( 'show_slider', 'no' ) ){

                case 'better':
                    $args = array(
                        'post_type'     => 'post',
                        'posts_per_page'=> 10,
                    );

                    if( Better_Mag::get_meta( 'slider_cats' ) ){
                        $args['cat'] = Better_Mag::get_meta( 'slider_cats' );
                    }elseif( Better_Mag::get_option( 'slider_cats' ) ){
                        $args['cat'] = Better_Mag::get_option( 'slider_cats' );
                    }

                    if( Better_Mag::get_meta( 'slider_tags' ) ){
                        $args['tag__in'] = Better_Mag::get_meta( 'slider_tags' );
                    }if( Better_Mag::get_option( 'slider_tags' ) ){
                        $args['tag__in'] = Better_Mag::get_option( 'slider_tags' );
                    }

                    if( Better_Mag::get_meta( 'slider_just_featured' ) ){
                        $args['meta_key'] = '_bm_featured_post';
                        $args['meta_value'] = '1';
                    }

                    if( Better_Mag::get_meta( 'slider_post_type' ) ){
                        $args['post_type'] = explode( ',', Better_Mag::get_meta( 'slider_post_type' ) );
                    }

                    Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/main-slider/page/args', $args ) ) );
                    unset( $args );

                    if( Better_Mag::get_meta( 'slider_style' ) != 'default' )
                        Better_Mag::generator()->blocks()->print_main_slider( Better_Mag::get_meta( 'slider_style' ) );
                    else
                        Better_Mag::generator()->blocks()->print_main_slider( Better_Mag::get_option( 'slider_style' ) );

                    Better_Mag::posts()->clear_query();
                    break;

                case 'rev':

                    if( Better_Mag::get_meta( 'slider_rev_id' ) != '0' )
                        Better_Mag::generator()->blocks()->print_rev_slider( Better_Mag::get_meta( 'slider_rev_id' ) );

                    break;

            }


        }
        // Slider for authors
        elseif( is_author() ){

            $current_user = bf_get_author_archive_user();

            switch( BF()->user_meta()->get_meta( 'show_slider', $current_user ) ){

                case 'better':
                    $args = array(
                        'post_type'     => 'post',
                        'posts_per_page'=> 10,
                        'author'        => $current_user->ID,
                    );

                    if( BF()->user_meta()->get_meta( 'slider_cats', $current_user ) ){
                        $args['cat'] = BF()->user_meta()->get_meta( 'slider_cats', $current_user );
                    }elseif( BF()->user_meta()->get_meta( 'slider_cats', $current_user ) ){
                        $args['cat'] = BF()->user_meta()->get_meta( 'slider_cats', $current_user );
                    }

                    if( Better_Mag::get_meta( 'slider_tags' ) ){
                        $args['tag__in'] = Better_Mag::get_meta( 'slider_tags' );
                    }if( Better_Mag::get_option( 'slider_tags' ) ){
                        $args['tag__in'] = Better_Mag::get_option( 'slider_tags' );
                    }

                    if( BF()->user_meta()->get_meta( 'slider_just_featured', $current_user ) ){
                        $args['meta_key'] = '_bm_featured_post';
                        $args['meta_value'] = '1';
                    }

                    if( BF()->user_meta()->get_meta( 'slider_post_type', $current_user ) ){
                        $args['post_type'] = explode( ',', BF()->user_meta()->get_meta( 'slider_post_type', $current_user ) );
                    }

                    Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/main-slider/author/args', $args ) ) );
                    unset( $args );

                    if( BF()->user_meta()->get_meta( 'slider_style', $current_user ) != 'default' )
                        Better_Mag::generator()->blocks()->print_main_slider( BF()->user_meta()->get_meta( 'slider_style', $current_user ) );
                    else
                        Better_Mag::generator()->blocks()->print_main_slider( Better_Mag::get_option( 'slider_style' ) );

                    Better_Mag::posts()->clear_query();
                    break;

                case 'rev':
                    if( BF()->user_meta()->get_meta( 'slider_rev_id', $current_user ) != '0' )
                        Better_Mag::generator()->blocks()->print_rev_slider( BF()->user_meta()->get_meta( 'slider_rev_id', $current_user ) );
            }

        }


    }


    /**
     * Filter For Generating BetterFramework Shortcodes Title
     *
     * @param $atts
     * @return mixed
     */
    public static function filter_bf_shortcodes_title( $atts ){

        if( ! $atts['title'] )
            return '';

        return Better_Mag::generator()->blocks()->get_block_title( $atts['title'], false, false );
    }


    /**
     * Read more link
     *
     * @param string $text
     * @param bool $echo
     * @param string $attr
     * @return string
     */
    public function excerpt_read_more( $text = '', $echo = true, $attr = '' ){

        $output = '';
        if( empty( $text ) ){
            $text = Better_Translation()->_get( 'post_readmore' );
        }

        if( is_feed() ){
            return ' [...]';
        }

        // add more link if enabled in options
        if( Better_Mag::get_option( 'show_read_more_blog_listing' ) ) {
            $output = '<a class="btn btn-read-more" href="'. get_permalink( get_the_ID() ) . '" title="'. esc_attr($text) . '" '. $attr .'>'. $text .'</a>';
        }

        if( $echo )
            echo $output;
        else
            return $output;
    }

}

// Add filter for VC elements add-on
$generator = apply_filters( 'better-mag/generator', 'BM_Block_Generator' );
if( ! class_exists($generator) || ! is_subclass_of( $generator, 'BF_Block_Generator' ) )
    $generator = 'BM_Block_Generator';
add_filter( 'better-framework/shortcodes/title', array( $generator, 'filter_bf_shortcodes_title' ) );