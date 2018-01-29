<?php
/**
 * BetterMag Content Listing 16
 */
class BM_Content_Listing_16_Shortcode extends BM_Listing_Shortcode{

    function __construct(){

        $id = 'bm_content_listing_16';

        $this->name = __( 'Content Listing 16', 'better-studio');

        $this->description = __( '3 Column of different category', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $options = array(
            'defaults'  => array(
                'title1'     =>  '',
                'hide_title1'=>  0,
                'icon1'      =>  '',
                'category1'  =>  '',
                'tag1'       =>  '',
                'post_type1' =>  '',
                'count1'     =>  4,
                'show_read_more1'     =>  0,
                'order_by1'  =>  'date',
                'order1'     =>  'DESC',

                'title2'     =>  '',
                'hide_title2'=>  0,
                'icon2'      =>  '',
                'category2'  =>  '',
                'tag2'       =>  '',
                'post_type2' =>  '',
                'count2'     =>  4,
                'show_read_more2'     =>  0,
                'order_by2'  =>  'date',
                'order2'     =>  'DESC',

                'title3'     =>  '',
                'hide_title3'=>  0,
                'icon3'      =>  '',
                'category3'  =>  '',
                'tag3'       =>  '',
                'post_type3' =>  '',
                'count3'     =>  4,
                'show_read_more3'     =>  0,
                'order_by3'  =>  'date',
                'order3'     =>  'DESC',
            ),

            'have_widget'   => false,
            'have_vc_add_on'=> true,
        );

        parent::__construct( $id , $options );

    }


    /**
     * Handle displaying of shortcode
     *
     * @param $atts
     * @param $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();
        ?>
        <div class="row block-listing block-listing-16">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"><?php

                if( empty( $atts['count1'] ) || intval( $atts['count1'] ) < 1 )
                    $atts['count1'] = 4;

                $args = array(
                    'post_type'         =>  array( 'post' ),
                    'posts_per_page'    =>  $atts['count1'],
                    'order'             =>  $atts['order1'],
                    'orderby'             =>  $atts['order_by1'],
                );

                if( $atts['order_by1'] == 'reviews' ){
                    $args['orderby'] = 'date';
                    $args['meta_key'] = '_bs_review_enabled';
                    $args['meta_value'] = '1';
                }

                if( $atts['order_by1'] == 'views' ){
                    $args['meta_key'] = 'better-views-count';
                    $args['orderby'] = 'meta_value_num';
                }

                if( $atts['category1'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category1'] ) ){
                    $args['category_name'] = $atts['category1'];
                }

                if( $atts['tag1'] ){
                    $args['tag_slug__and'] = explode( ',', $atts['tag1'] );
                }

                if( $atts['post_type1'] ){
                    $args['post_type'] = explode( ',', $atts['post_type1'] );
                }

                Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/content-listing-16/args', $args ) ) );

                $_atts['hide_title'] = $atts['hide_title1'];
                $_atts['category'] = $atts['category1'];
                $_atts['tag'] = $atts['tag1'];
                $_atts['icon'] = $atts['icon1'];
                $_atts['title'] = $atts['title1'];
                $_atts['show_read_more'] = $atts['show_read_more1'];
                $this->the_block_title( $_atts );

                // Showing Term Banner
                if( isset( $atts['inside-tab'] ) )
                    $_atts['inside-tab'] = $atts['inside-tab'];
                $this->decision_showing_of_term_banner( $_atts );


                Better_Mag::generator()->set_attr( 'hide-meta-review-if-views', true );

                Better_Mag::generator()->set_attr( 'hide-meta-author', true );
                Better_Mag::generator()->set_attr( 'hide-meta-comment', true );
                Better_Mag::generator()->set_attr( "hide-meta-review", false );

                Better_Mag::generator()->set_attr( 'excerpt-length', 14 );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::posts()->the_post();
                    Better_Mag::generator()->blocks()->block_modern();
                }

                Better_Mag::generator()->set_attr( "hide-meta-review", true );
                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::generator()->blocks()->listing_thumbnail();
                }

                // Clear showing Term Title Banner
                Better_Mag::generator()->set_attr( 'show-term-banner', false );

            ?></div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"><?php

                if( empty( $atts['count2'] ) || intval( $atts['count2'] ) < 1 )
                    $atts['count2'] = 4;

                $args = array(
                    'post_type'         =>  array( 'post' ),
                    'posts_per_page'    =>  $atts['count2'],
                    'order'             =>  $atts['order2'],
                    'orderby'             =>  $atts['order_by2'],
                );

                if( $atts['order_by2'] == 'reviews' ){
                    $args['orderby'] = 'date';
                    $args['meta_key'] = '_bs_review_enabled';
                    $args['meta_value'] = '1';
                }

                if( $atts['order_by2'] == 'views' ){
                    $args['meta_key'] = 'better-views-count';
                    $args['orderby'] = 'meta_value_num';
                }

                if( $atts['category2'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category2'] ) ){
                    $args['category_name'] = $atts['category2'];
                }

                if( $atts['tag2'] ){
                    $args['tag_slug__and'] = explode( ',', $atts['tag2'] );
                }

                if( $atts['post_type2'] ){
                    $args['post_type'] = explode( ',', $atts['post_type2'] );
                }

                Better_Mag::posts()->set_query( new WP_Query( $args ) );

                $_atts['hide_title'] = $atts['hide_title2'];
                $_atts['category'] = $atts['category2'];
                $_atts['tag'] = $atts['tag2'];
                $_atts['icon'] = $atts['icon2'];
                $_atts['title'] = $atts['title2'];
                $_atts['show_read_more'] = $atts['show_read_more2'];
                $this->the_block_title( $_atts );

                // Showing Term Banner
                $this->decision_showing_of_term_banner( $_atts );

                Better_Mag::generator()->set_attr( "hide-meta-review", false );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::posts()->the_post();
                    Better_Mag::generator()->blocks()->block_modern();
                }

                Better_Mag::generator()->set_attr( "hide-meta-review", true );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::generator()->blocks()->listing_thumbnail();
                }

                // Clear showing Term Title Banner
                Better_Mag::generator()->set_attr( 'show-term-banner', false );

            ?></div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"><?php

                if( empty( $atts['count3'] ) || intval( $atts['count3'] ) < 1 )
                    $atts['count3'] = 4;

                $args = array(
                    'post_type'         =>  array( 'post' ),
                    'posts_per_page'    =>  $atts['count3'],
                    'order'             =>  $atts['order3'],
                    'orderby'             =>  $atts['order_by3'],
                );

                if( $atts['order_by3'] == 'reviews' ){
                    $args['orderby'] = 'date';
                    $args['meta_key'] = '_bs_review_enabled';
                    $args['meta_value'] = '1';
                }

                if( $atts['order_by3'] == 'views' ){
                    $args['meta_key'] = 'better-views-count';
                    $args['orderby'] = 'meta_value_num';
                }

                if( $atts['category3'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category3'] ) ){
                    $args['category_name'] = $atts['category3'];
                }

                if( $atts['tag3'] ){
                    $args['tag_slug__and'] = explode( ',', $atts['tag3'] );
                }

                if( $atts['post_type3'] ){
                    $args['post_type'] = explode( ',', $atts['post_type3'] );
                }

                Better_Mag::posts()->set_query( new WP_Query( $args ) );

                $_atts['hide_title'] = $atts['hide_title3'];
                $_atts['category'] = $atts['category3'];
                $_atts['tag'] = $atts['tag3'];
                $_atts['icon'] = $atts['icon3'];
                $_atts['title'] = $atts['title3'];
                $_atts['show_read_more'] = $atts['show_read_more3'];
                $this->the_block_title( $_atts );

                // Showing Term Banner
                $this->decision_showing_of_term_banner( $_atts );

                Better_Mag::generator()->set_attr( "hide-meta-review", false );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::posts()->the_post();
                    Better_Mag::generator()->blocks()->block_modern();
                }

                Better_Mag::generator()->set_attr( "hide-meta-review", true );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::generator()->blocks()->listing_thumbnail();
                }

            ?></div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }



    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map( array(
            "name"      => $this->name,
            "base"      => $this->id,
            "icon"      => $this->icon,
            "description"=> $this->description,
            "weight"    => 10,
            "wrapper_height"    => 'full',

            "category"  => __( 'BetterMag Addons', 'better-studio' ),
            "params"    => array(

                array(
                    "type"      =>  'bf_heading',
                    "class"     =>  '',
                    "heading"   =>  '',
                    "param_name"=>  'heading1',
                    "title"     => __( 'Column 1', 'better-studio' ),
                ),

                array(
                    "type"      =>  'bf_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Column 1 Category', 'better-studio' ),
                    "param_name"=>  'category1',
                    "value"     =>  $this->defaults['category1'],
                    "options"   =>  array( __('All Posts','better-studio') => __('All Posts','better-studio') ) + BF_Query::get_categories_by_slug(),
                ),

                array(
                    "type"      =>  'bf_ajax_select',
                    "heading"   =>  __( 'Column 1 Tags', 'better-studio' ),
                    "param_name"=>  'tag1',
                    "admin_label" => true,
                    "value"     =>  $this->defaults['tag1'],
                    "callback"  =>  'BF_Ajax_Select_Callbacks::tags_slug_callback' ,
                    "get_name"  =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   => __( "Search and find tag...", 'better-studio' ),
                    'description'   => __( "Search and select tags. You can use combination of Category and Tags!", 'better-studio' )
                ),
                array(
                    "type"          => 'textfield',
                    "admin_label"   => true,
                    "heading"       => __( 'Column 1 Custom Post Type', 'better-studio' ),
                    "param_name"    => 'post_type1',
                    "value"         => $this->defaults['post_type1'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show read more button?', 'better-studio' ),
                    "param_name"    =>  'show_read_more1',
                    "value"         =>  $this->defaults['show_read_more1'],
                ),
                array(
                    "type"          =>  'bf_select',
                    "heading"       =>  __( 'Order By', 'better-studio' ),
                    "param_name"    =>  'order_by1',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order_by1'],
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
                    "param_name"    =>  'order1',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order1'],
                    "options"   =>  array(
                        'DESC'       =>  __( 'Latest First - Descending', 'better-studio' ),
                        'ASC'      =>  __( 'Oldest First - Ascending', 'better-studio' ),
                    )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"=>  'count1',
                    "value"     =>  $this->defaults['count1'],
                    "description"=> __( 'Configures posts to show in column. Leave empty to use theme default number of posts.', 'better-studio' )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Custom Heading (Optional)', 'better-studio' ),
                    "param_name"=>  'title1',
                    "value"     =>  $this->defaults['title1'],
                ),
                array(
                    "type"      =>  'bf_icon_select',
                    "heading"   =>  __( 'Custom Heading Icon (Optional)', 'better-studio' ),
                    "param_name"=>  'icon1',
                    "value"     =>  $this->defaults['icon1'],
                    "description"=> __( 'Select custom icon for column.', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Hide Content listing Heading?', 'better-studio'),
                    "param_name"=>  'hide_title1',
                    "value"     =>  $this->defaults['hide_title1'],
                    'section_class' =>  'style-floated-left bordered',
                    "description"   => __( 'You can hide content listing heading with turning on this field.', 'better-studio'),
                ),
                array(
                    "type"      =>  'bf_heading',
                    "heading"   =>  '',
                    "param_name"=>  'heading2',
                    "title"     => __( 'Column 2', 'better-studio' ),
                    "description"   => '' ,
                ),
                array(
                    "type"      =>  'bf_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Column 2 Category', 'better-studio' ),
                    "param_name"=>  'category2',
                    "value"     =>  $this->defaults['category2'],
                    "options"   =>  array( __('All Posts','better-studio') => __('All Posts','better-studio') ) + BF_Query::get_categories_by_slug(),
                ),
                array(
                    "type"      =>  'bf_ajax_select',
                    "heading"   =>  __( 'Column 2 Tags', 'better-studio' ),
                    "admin_label" => true,
                    "param_name"=>  'tag2',
                    "value"     =>  $this->defaults['tag2'],
                    "callback"  =>  'BF_Ajax_Select_Callbacks::tags_slug_callback' ,
                    "get_name"  =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   => __( "Search and find tag...", 'better-studio' ),
                    'description'   => __( "Search and select tags. You can use combination of Category and Tags!", 'better-studio' )
                ),
                array(
                    "type"          => 'textfield',
                    "admin_label"   => true,
                    "heading"       => __( 'Custom Post Type', 'better-studio' ),
                    "param_name"    => 'post_type2',
                    "value"         => $this->defaults['post_type2'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show read more button?', 'better-studio' ),
                    "param_name"    =>  'show_read_more2',
                    "value"         =>  $this->defaults['show_read_more2'],
                ),
                array(
                    "type"          =>  'bf_select',
                    "heading"       =>  __( 'Order By', 'better-studio' ),
                    "param_name"    =>  'order_by2',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order_by2'],
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
                    "param_name"    =>  'order2',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order2'],
                    "options"   =>  array(
                        'DESC'       =>  __( 'Latest First - Descending', 'better-studio' ),
                        'ASC'      =>  __( 'Oldest First - Ascending', 'better-studio' ),
                    )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"=>  'count2',
                    "value"     =>  $this->defaults['count2'],
                    "description"=> __( 'Configures posts to show in column. Leave empty to use theme default number of posts.', 'better-studio' )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Custom Heading (Optional)', 'better-studio' ),
                    "param_name"=>  'title2',
                    "value"     =>  $this->defaults['title2'],
                ),
                array(
                    "type"      =>  'bf_icon_select',
                    "heading"   =>  __( 'Custom Heading Icon (Optional)', 'better-studio' ),
                    "param_name"=>  'icon2',
                    "value"     =>  $this->defaults['icon2'],
                    "description"=> __( 'Select custom icon for column.', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Hide Content listing Heading?', 'better-studio'),
                    "param_name"=>  'hide_title2',
                    "value"     =>  $this->defaults['hide_title2'],
                    'section_class' =>  'style-floated-left bordered',
                    "description"   => __( 'You can hide content listing heading with turning on this field.', 'better-studio'),
                ),
                array(
                    "type"      =>  'bf_heading',
                    "heading"   =>  '',
                    "param_name"=>  'heading3',
                    "title"     => __( 'Column 3', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_select',
                    "admin_label" => true,
                    "heading"   =>  __( 'Column 3 Category', 'better-studio' ),
                    "param_name"=>  'category3',
                    "value"     =>  $this->defaults['category3'],
                    "options"   =>  array( __('All Posts','better-studio') => __('All Posts','better-studio') ) + BF_Query::get_categories_by_slug(),
                ),
                array(
                    "type"      =>  'bf_ajax_select',
                    "heading"   =>  __( 'Column 3 Tags', 'better-studio' ),
                    "admin_label" => true,
                    "param_name"=>  'tag3',
                    "value"     =>  $this->defaults['tag2'],
                    "callback"  =>  'BF_Ajax_Select_Callbacks::tags_slug_callback' ,
                    "get_name"  =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   => __( "Search and find tag...", 'better-studio' ),
                    'description'   => __( "Search and select tags. You can use combination of Category and Tags!", 'better-studio' )
                ),
                array(
                    "type"          => 'textfield',
                    "admin_label"   => true,
                    "heading"       => __( 'Custom Post Type', 'better-studio' ),
                    "param_name"    => 'post_type3',
                    "value"         => $this->defaults['post_type3'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show read more button?', 'better-studio' ),
                    "param_name"    =>  'show_read_more3',
                    "value"         =>  $this->defaults['show_read_more3'],
                ),
                array(
                    "type"          =>  'bf_select',
                    "heading"       =>  __( 'Order By', 'better-studio' ),
                    "param_name"    =>  'order_by3',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order_by3'],
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
                    "param_name"    =>  'order3',
                    "admin_label" => true,
                    "value"         =>  $this->defaults['order3'],
                    "options"   =>  array(
                        'DESC'       =>  __( 'Latest First - Descending', 'better-studio' ),
                        'ASC'      =>  __( 'Oldest First - Ascending', 'better-studio' ),
                    )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"=>  'count3',
                    "value"     =>  $this->defaults['count3'],
                    "description"=> __( 'Configures posts to show in column. Leave empty to use theme default number of posts.', 'better-studio' )
                ),
                array(
                    "type"      =>  'textfield',
                    "heading"   =>  __( 'Custom Heading (Optional)', 'better-studio' ),
                    "param_name"=>  'title3',
                    "value"     =>  $this->defaults['title3'],
                ),
                array(
                    "type"      =>  'bf_icon_select',
                    "heading"   =>  __( 'Custom Heading Icon (Optional)', 'better-studio' ),
                    "param_name"=>  'icon3',
                    "value"     =>  $this->defaults['icon2'],
                    "description"=> __( 'Select custom icon for column.', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Hide Content listing Heading?', 'better-studio'),
                    "param_name"=>  'hide_title3',
                    "value"     =>  $this->defaults['hide_title2'],
                    'section_class' =>  'style-floated-left bordered',
                    "description"   => __( 'You can hide content listing heading with turning on this field.', 'better-studio'),
                ),

            )
        ) );

    }

}

class WPBakeryShortCode_bm_content_listing_16 extends BM_VC_Shortcode_Extender { }