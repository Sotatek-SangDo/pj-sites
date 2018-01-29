<?php

class BM_Slider_Listing_Shortcode extends BM_Listing_Shortcode{

    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map(array(
            "name"      => $this->name,
            "base"      => $this->id,
            "icon"      => $this->icon,
            "description"      => $this->description,
            "weight"    => 10,

            "wrapper_height"    => 'full',

            "category" => __( 'BetterMag Addons', 'better-studio' ),
            "params" => array(
                array(
                    "type"      =>  'bf_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Post Formats', 'better-studio' ),
                    "param_name"=>  'format',
                    "value"     =>  $this->defaults['format'],
                    "options"   =>  array(
                        'all'       =>  __( 'All Post Formats', 'better-studio') ,
                        'video'     =>  __( 'Videos', 'better-studio'),
                        'audio'     =>  __( 'Audios', 'better-studio'),
                        'gallery'   =>  __( 'Gallery', 'better-studio'),
                    ) ,
                ),
                array(
                    "type"      =>  'bf_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Category', 'better-studio' ),
                    "param_name"=>  'category',
                    "value"     =>  $this->defaults['category'],
                    "options"   =>  array( __('All Posts','better-studio') => __('All Posts','better-studio') ) + BF_Query::get_categories_by_slug(),
                ),
                array(
                    "type"      =>  'bf_ajax_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Tags', 'better-studio' ),
                    "param_name"=>  'tag',
                    "value"     =>  $this->defaults['tag'],
                    "callback"  =>  'BF_Ajax_Select_Callbacks::tags_slug_callback' ,
                    "get_name"  =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   => __( "Search tag...", 'better-studio' ),
                    'description'   => __( "Search and select tags. You can use combination of Category and Tags!", 'better-studio' )
                ),
                array(
                    "type"          => 'textfield',
                    "admin_label"   => true,
                    "heading"       => __( 'Custom Post Type', 'better-studio' ),
                    "param_name"    => 'post_type',
                    "value"         => $this->defaults['post_type'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show read more button?', 'better-studio' ),
                    "param_name"    =>  'show_read_more',
                    "value"         =>  $this->defaults['show_read_more'],
                ),
                array(
                    "type"          =>  'bf_select',
                    "heading"       =>  __( 'Order By', 'better-studio' ),
                    "param_name"    =>  'order_by',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order_by'],
                    "options"   =>  array(
                        'date'      =>  __( 'Published Date', 'better-studio' ),
                        'modified'  =>  __( 'Modified Date', 'better-studio' ),
                        'rand'      =>  __( 'Random', 'better-studio' ),
                        'comment_count' =>  __( 'Number of Comments', 'better-studio' ),
                        'reviews'   =>  __( 'Reviews', 'better-studio' ),
                        'views'     =>  __( 'Views', 'better-studio' ),
                    )
                ),
                array(
                    "type"          =>  'bf_select',
                    "heading"       =>  __( 'Order', 'better-studio' ),
                    "param_name"    =>  'order',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order'],
                    "options"   =>  array(
                        'DESC'       =>  __( 'Latest First - Descending', 'better-studio' ),
                        'ASC'      =>  __( 'Oldest First - Ascending', 'better-studio' ),
                    )
                ),
                array(
                    "type"      =>  'textfield',
                    "admin_label" => true,
                    "heading"   =>  __( 'Custom Heading (Optional)', 'better-studio' ),
                    "param_name"=>  'title',
                    "value"     =>  $this->defaults['title'],
                ),
                array(
                    "type"      =>  'bf_icon_select',
                    "heading"   =>  __( 'Custom Heading Icon (Optional)', 'better-studio' ),
                    "param_name"=>  'icon',
                    "admin_label" => true,
                    "value"     =>  $this->defaults['icon'],
                    "description"=> __( 'Select custom icon for listing.', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Hide listing Heading?', 'better-studio'),
                    "param_name"=>  'hide_title',
                    "value"     =>  $this->defaults['hide_title'],
                    'section_class' =>  'style-floated-left bordered',
                    "description"   => __( 'You can hide listing heading with turning on this field.', 'better-studio'),
                ),
            )
        ));
    }
}




class BM_Slider_Listing_1_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_1';

        $this->name = __( 'Slider Listing 1', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 1 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-1 block-listing">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

                $args = array(
                    'post_type'         =>  array('post'),
                    'posts_per_page'    =>  10,
                    'order'             =>  $atts['order'],
                    'orderby'           =>  $atts['order_by'],
                );

                if( $atts['order_by'] == 'reviews' ){
                    $args['orderby'] = 'date';
                    $args['meta_key'] = '_bs_review_enabled';
                    $args['meta_value'] = '1';
                }

                if( $atts['order_by'] == 'views' ){
                    $args['meta_key'] = 'better-views-count';
                    $args['orderby'] = 'meta_value_num';
                }


                if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                    $args['category_name'] = $atts['category'];
                }

                if( $atts['tag'] ){
                    $args['tag_slug__and'] = explode(',', $atts['tag']);
                }

                if( $atts['post_type'] ){
                    $args['post_type'] = explode( ',', $atts['post_type'] );
                }

                if( $atts['format'] != 'all' ){
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_format',
                            'field' => 'slug',
                            'terms' => 'post-format-' . $atts['format'],
                        )
                    );
                }

                Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

                Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-1/args', $args ) ) );

                $this->the_block_title( $atts );

                Better_Mag::generator()->blocks()->slider_style_1();

                ?>
            </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }

}

class WPBakeryShortCode_bm_slider_listing_1 extends BM_VC_Shortcode_Extender { }





class BM_Slider_Listing_2_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_2';

        $this->name = __( 'Slider Listing 2', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 2 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-2 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-2/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_2();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_2 extends BM_VC_Shortcode_Extender { }



class BM_Slider_Listing_3_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_3';

        $this->name = __( 'Slider Listing 3', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 3 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-3 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-3/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_3();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_3 extends BM_VC_Shortcode_Extender { }



class BM_Slider_Listing_4_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_4';

        $this->name = __( 'Slider Listing 4', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 4 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-4 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-4/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->set_attr( "hide-meta-comment", true );
            Better_Mag::generator()->set_attr( "hide-meta-review", true );

            Better_Mag::generator()->blocks()->slider_style_4();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_4 extends BM_VC_Shortcode_Extender { }


class BM_Slider_Listing_5_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_5';

        $this->name = __( 'Slider Listing 5', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 5 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-5 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-5/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( "hide-meta-comment", true );
            Better_Mag::generator()->set_attr( "hide-meta-review", true );
            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_5();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_5 extends BM_VC_Shortcode_Extender { }



class BM_Slider_Listing_6_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_6';

        $this->name = __( 'Slider Listing 6', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 6 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-6 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-6/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->blocks()->slider_style_6();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_6 extends BM_VC_Shortcode_Extender { }




class BM_Slider_Listing_7_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_7';

        $this->name = __( 'Slider Listing 7', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 7 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-7 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-7/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_7();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_7 extends BM_VC_Shortcode_Extender { }


class BM_Slider_Listing_8_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_8';

        $this->name = __( 'Slider Listing 8', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 8 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-8 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-8/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_8();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_8 extends BM_VC_Shortcode_Extender { }


class BM_Slider_Listing_9_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_9';

        $this->name = __( 'Slider Listing 9', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 9 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-9 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-9/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_9();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_9 extends BM_VC_Shortcode_Extender { }


class BM_Slider_Listing_10_Shortcode extends BM_Slider_Listing_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_slider_listing_10';

        $this->name = __( 'Slider Listing 10', 'better-studio' );

        $this->description = __( 'Slider listing with main slider 10 style', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $_options = array(
            'defaults' => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_posts' ),
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
                'format'            =>  'all',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        ?>
        <div class="row gallery-listing gallery-listing-10 block-listing">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

            $args = array(
                'post_type'         =>  array('post'),
                'posts_per_page'    =>  10,
                'order'             =>  $atts['order'],
                'orderby'           =>  $atts['order_by'],
            );

            if( $atts['order_by'] == 'reviews' ){
                $args['orderby'] = 'date';
                $args['meta_key'] = '_bs_review_enabled';
                $args['meta_value'] = '1';
            }

            if( $atts['order_by'] == 'views' ){
                $args['meta_key'] = 'better-views-count';
                $args['orderby'] = 'meta_value_num';
            }

            if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
                $args['category_name'] = $atts['category'];
            }

            if( $atts['tag'] ){
                $args['tag_slug__and'] = explode(',', $atts['tag']);
            }

            if( $atts['post_type'] ){
                $args['post_type'] = explode( ',', $atts['post_type'] );
            }

            if( $atts['format'] != 'all' ){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => 'post-format-' . $atts['format'],
                    )
                );
            }

            Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/slider-listing-10/args', $args ) ) );

            $this->the_block_title( $atts );

            Better_Mag::generator()->set_attr( "hide-meta-comment", true );
            Better_Mag::generator()->set_attr( "hide-meta-review", true );
            Better_Mag::generator()->set_attr( 'meta-views-template', '%VIEW_COUNT%' );

            Better_Mag::generator()->blocks()->slider_style_10();

            ?>
        </div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }
}

class WPBakeryShortCode_bm_slider_listing_10 extends BM_VC_Shortcode_Extender { }
