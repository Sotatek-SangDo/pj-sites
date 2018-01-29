<?php


/**
 * BetterMag Content Listing 7
 */
class BM_Content_Listing_7_Shortcode extends BM_Listing_Shortcode{

    function __construct(  ){

        $id = 'bm_content_listing_7';

        $this->name = __( 'Content Listing 7', 'better-studio');

        $this->description = __( '1 Blog , 3 Column thumbnail listing', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $options = array(
            'defaults'  => array(
                'title'     =>  '',
                'hide_title'=>  0,
                'icon'      =>  '',
                'category'  =>  '',
                'tag'       =>  '',
                'post_type' =>  '',
                'count'     =>  10,
                'order_by'  =>  'date',
                'order'     =>  'DESC',
                'show_read_more'    =>  0,
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

        if( empty( $atts['count'] ) || intval( $atts['count'] ) < 1 )
            $atts['count'] = 10;

        $args = array(
            'post_type'         =>  array( 'post' ),
            'posts_per_page'    =>  $atts['count'],
            'order'             =>  $atts['order'],
            'orderby'             =>  $atts['order_by'],
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
            $args['tag_slug__and'] = explode( ',', $atts['tag'] );
        }

        if( $atts['post_type'] ){
            $args['post_type'] = explode( ',', $atts['post_type'] );
        }

        $this->decision_showing_of_term_banner( $atts );

        Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/content-listing-7/args', $args ) ) );

        $this->the_block_title( $atts );

        Better_Mag::generator()->set_attr( "hide-meta-author-if-review", true );

        ?>
        <div class="row block-listing block-listing-7">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php
                        if( Better_Mag::posts()->have_posts() ){
                            Better_Mag::posts()->the_post();
                            Better_Mag::generator()->blocks()->block_blog();
                        }
                        ?></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"><?php

                        Better_Mag::generator()->set_attr( 'hide-meta-author', true );
                        Better_Mag::generator()->set_attr( 'hide-meta-comment', true );
                        Better_Mag::generator()->set_attr( "hide-meta-review", true );

                        Better_Mag::generator()->set_attr( 'counter', 1 );

                        Better_Mag::generator()->set_attr_count_multi_column( Better_Mag::posts()->get_query()->post_count-1, 3, 1 );

                        if( Better_Mag::posts()->have_posts() ){
                            Better_Mag::generator()->blocks()->listing_thumbnail();
                        }

                        ?>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 hidden-xs"><?php

                        Better_Mag::generator()->set_attr_count_multi_column( Better_Mag::posts()->get_query()->post_count-1, 3, 2 );

                        Better_Mag::generator()->set_attr( 'counter', 1 );

                        if( Better_Mag::posts()->have_posts() ){
                            Better_Mag::generator()->blocks()->listing_thumbnail();
                        }

                        ?>
                    </div>
                    <div class="col-lg-4 hidden-md hidden-sm hidden-xs"><?php

                        Better_Mag::generator()->set_attr_count_multi_column( Better_Mag::posts()->get_query()->post_count-1, 3, 3 );

                        Better_Mag::generator()->set_attr( 'counter', 1 );

                        if( Better_Mag::posts()->have_posts() ){
                            Better_Mag::generator()->blocks()->listing_thumbnail();
                        }

                        ?>
                    </div>

                </div>
            </div>

        </div>
        <?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }
}

class WPBakeryShortCode_bm_content_listing_7 extends BM_VC_Shortcode_Extender {}