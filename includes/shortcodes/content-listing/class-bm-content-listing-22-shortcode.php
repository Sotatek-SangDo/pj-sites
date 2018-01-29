<?php
/**
 * BetterMag Content Listing 22
 */
class BM_Content_Listing_22_Shortcode extends BM_Listing_Shortcode{

    function __construct(  ){

        $id = 'bm_content_listing_22';

        $this->name = __( 'Content Listing 22', 'better-studio');

        $this->description = __( '1 Column', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_23.png' );

        $options = array(
            'defaults'  => array(

                'title'     =>  '',
                'hide_title'=>  0,
                'icon'      =>  '',
                'category'  =>  '',
                'tag'       =>  '',
                'post_type' =>  '',
                'count'     =>  4,
                'order_by'  =>  'date',
                'order'     =>  'DESC',
                'show_read_more'     =>  0,

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
        <div class="row block-listing block-listing-22">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

                if( empty( $atts['count1'] ) || intval( $atts['count1'] ) < 1 )
                    $atts['count1'] = 4;

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

                Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/content-listing-22/args', $args ) ) );

                $this->the_block_title( $atts );

                $this->decision_showing_of_term_banner( $atts );

                Better_Mag::generator()->set_attr( 'hide-meta-author-if-review', true );

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::posts()->the_post();
                    Better_Mag::generator()->blocks()->block_modern();
                }

                if( Better_Mag::posts()->have_posts() ){
                    Better_Mag::generator()->blocks()->listing_thumbnail();
                }
            ?></div>
        </div><?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }

}

class WPBakeryShortCode_bm_content_listing_22 extends BM_VC_Shortcode_Extender { }