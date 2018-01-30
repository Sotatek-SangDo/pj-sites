<?php

class BM_Posts_Slider_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_posts_slider';

        $this->widget_id = 'bm posts slider widget';

        $_options = array(
            'defaults' => array(
                'title'         =>  Better_Translation()->_get( 'widget_posts' ),
                'show_title'    =>  0,
                'order'         =>  'recent',
                'category'      =>  '',
                'tag'           =>  '',
                'post_type'     =>  '',
                'count'         =>  5,
            ),

            'have_widget'       => true,
            'have_vc_add_on'    => false,
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

        if( empty( $atts['count'] ) )
            $atts['count'] = 5;

        $args = array(
            'post_type'         =>  array( 'post' ),
            'posts_per_page'    =>  $atts['count'],
        );

        if( $atts['order'] == 'popular' ){
            $args['offset'] = 0;
            $args['orderby'] = 'comment_count';
        }

        if( $atts['category'] == 'bm-review-posts' ){
            $atts['category'] = 'All Posts';
            $args['meta_key'] = '_bs_review_enabled';
            $args['meta_value'] = '1';
        }

        if( $atts['order'] == 'views' ){
            $atts['category'] = 'All Posts';
            $args['meta_key'] = 'better-views-count';
            $args['orderby'] = 'meta_value_num';
        }

        if( $atts['category'] != 'All Posts' ){
            $args['cat'] = $atts['category'];
        }

        if( $atts['tag'] ){
            $args['tag__and'] = explode( ',', $atts['tag'] );
        }

        if( $atts['post_type'] ){
            $args['post_type'] = explode( ',', $atts['post_type'] );
        }

        Better_Mag::posts()->set_query( new WP_Query( $args ) );

        ?>
        <div class="bf-shortcode bm-posts-slider">
            <div class="gallery-slider slider-arrows">
                <div class="flexslider">
                    <ul class="slides">
                        <?php
                        Better_Mag::generator()->set_attr( "hide-meta", true );

                        while( Better_Mag::posts()->have_posts() ){
                            Better_Mag::posts()->the_post();

                            echo '<li>';
                            Better_Mag::generator()->blocks()->block_highlight();
                            echo '</li>';

                        }

                        ?>
                    </ul>
                </div>

            </div>
        </div>
        <?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }

}