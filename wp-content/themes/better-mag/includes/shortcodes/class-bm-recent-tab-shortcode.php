<?php

class BM_Recent_Tab_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_recent_tab';

        $this->widget_id = 'bm recent tab widget';

        $_options = array(
            'defaults' => array(
                'tabs' =>  array(
                    array(
                        'tab_title'     =>  Better_Translation()->_get( 'widget_recent' ),
                        'icon'          =>  'fa-clock-o',
                        'style'         =>  'thumbnail',
                        'count'         =>  '5',
                        'category'      =>  'recent',
                        'tag'           =>  '',
                        'post_type'     =>  '',
                        'read_more'     =>  1,
                    ),
                    array(
                        'tab_title'     =>  Better_Translation()->_get( 'widget_popular' ),
                        'icon'          =>  'fa-fire',
                        'style'         =>  'thumbnail',
                        'category'      =>  'popular',
                        'count'         =>  '5',
                        'tag'           =>  '',
                        'post_type'     =>  '',
                        'read_more'     =>  1,
                    ),
                    array(
                        'tab_title'     =>  Better_Translation()->_get( 'widget_review' ),
                        'icon'          =>  'fa-fire',
                        'style'         =>  'thumbnail',
                        'category'      =>  'bm-review-posts',
                        'tag'           =>  '',
                        'post_type'     =>  '',
                        'count'         =>  '5',
                        'read_more'     =>  1,
                    ),
                ),
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

        $other_tabs = array();
        $first_tab = array();

        foreach( (array) $atts['tabs'] as $tab_id => $tab ){

            $_ar = array();

            if( ! in_array( $tab['category'], array( 'popular', 'recent', 'bm-review-posts', 'views' ) ) ){
                $term = get_term( $tab['category'], 'category' );
                $_ar['title'] = $term->name;
                $_ar['slug'] = $term->term_id . '-' . rand( 100, 100000 );
                $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                $_ar['id'] = $term->term_id;
                $_ar['href'] = '#' . $_ar['slug'];
                $atts['tabs'][$tab_id]['href'] = $_ar['href'];
            }
            elseif( $tab['tag'] ){

                $_tags = explode(',', $tab['tag']);
                $term = get_term( $_tags[0], 'post_tag' );

                $_ar['title'] = $term->name;
                $_ar['slug'] = $term->term_id . '-' . rand( 100, 100000 );
                $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                $_ar['id'] = $term->term_id;
                $_ar['href'] = '#' . $_ar['slug'];
                $atts['tabs'][$tab_id]['href'] = $_ar['href'];

            }else{

                if( $tab['category'] == 'recent' ){
                    $_ar['title'] = __( 'Recent Posts', 'better-studio' );
                    $_ar['slug'] = 'recent-' . rand( 100, 100000 );;
                    $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                    $_ar['id'] = 'recent';
                    $_ar['href'] = '#' . $_ar['slug'];
                    $atts['tabs'][$tab_id]['href'] = $_ar['href'];
                }
                elseif( $tab['category'] == 'popular' ){
                    $_ar['title'] = __( 'Popular Posts', 'better-studio' );
                    $_ar['slug'] = 'popular-' . rand( 100, 100000 );
                    $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                    $_ar['id'] = 'popular';
                    $_ar['href'] = '#' . $_ar['slug'];
                    $atts['tabs'][$tab_id]['href'] = $_ar['href'];
                }
                elseif( $tab['category'] == 'views' ){
                    $_ar['title'] = __( 'Popular Posts', 'better-studio' );
                    $_ar['slug'] = 'views-' . rand( 100, 100000 );
                    $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                    $_ar['id'] = 'views';
                    $_ar['href'] = '#' . $_ar['slug'];
                    $atts['tabs'][$tab_id]['href'] = $_ar['href'];
                }

                elseif( $tab['category'] == 'bm-review-posts' ){
                    $_ar['title'] = __( 'Review Posts', 'better-studio' );
                    $_ar['slug'] = 'reviews-' . rand( 100, 100000 );
                    $atts['tabs'][$tab_id]['slug'] = $_ar['slug'];
                    $_ar['id'] = 'review';
                    $_ar['href'] = '#' . $_ar['slug'];
                    $atts['tabs'][$tab_id]['href'] = $_ar['href'];
                }


            }

            // title
            if( ! empty( $tab['tab_title'] ) ){
                $_ar['title'] = $tab['tab_title'];
            }

            $_ar['class'] = '';

            if( ! empty( $tab['icon'] ) ){
                $_ar['title'] = '<i class="fa ' . $tab['icon'].  '"></i> ' . $_ar['title'];
                $_ar['class'] = ' have-icon ';
            }

            if( ! count( $first_tab ) ){
                $_ar['active'] = true;
                $atts['tabs'][$tab_id]['active-tab'] = true;
                $_ar['class'] .= ' main-term ';
                $atts['tabs'][$tab_id]['class'] = $_ar['class'];
                $first_tab = $_ar;
            }

            $other_tabs[$tab_id] = $_ar;

        }

        Better_Mag::generator()->blocks()->get_tab_block_title( $other_tabs );

        ?>
        <div class="bf-shortcode bm-recent-tab">
            <div class="tab-content">

                <?php
                foreach( (array) $atts['tabs'] as $tab_id => $tab ){

                    $this->show_tab_content( $tab );
                }
                ?>
            </div>
        </div>
        <?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();

    }



    function show_tab_content( $tab ){

        if( isset( $tab['class'] ) )
            $class= $tab['class'];
        else
            $class = '';

        if( isset( $tab['active-tab'] ) ){
            $class .= ' active';
        }


        if( empty( $tab['count'] ) )
            $tab['count'] = 5;

        $args = array(
            'post_type'         =>  array( 'post' ),
            'posts_per_page'    =>  $tab['count'],
        );

        $term = false;

        if( $tab['category'] == 'bm-review-posts' ){
            $tab['category'] = 'recent';
            $args['meta_key'] = '_bs_review_enabled';
            $args['meta_value'] = '1';
        }

        if( $tab['category'] == 'popular' ){
            $args['orderby'] = 'comment_count';
        }
        elseif( $tab['category'] == 'views' ){
            $tab['category']    = 'recent';
            $args['meta_key']   = 'better-views-count';
            $args['orderby']    = 'meta_value_num';
        }
        elseif( $tab['category'] != 'recent' ){
            $args['cat'] = $tab['category'];
            $term = $args['cat'];
        }

        if( $tab['tag'] ){
            $args['tag__and'] = explode( ',', $tab['tag'] );
            if( $term == false )
                $term = current( $args['tag__and'] );
        }

        if( ! empty( $tab['post_type'] ) ){
            $args['post_type'] = explode( ',', $tab['post_type'] );
        }

        Better_Mag::posts()->set_query( new WP_Query( $args ) );

        ?>
        <div class="tab-pane <?php echo $class; ?>" id="<?php echo $tab['slug']; ?>"><?php

            if( $tab['style'] == 'thumbnail' ){
                Better_Mag::generator()->set_attr( 'hide-meta-author-if-review', true );
                Better_Mag::generator()->blocks()->listing_thumbnail();
            }

            elseif( $tab['style'] == 'modern' ){

                Better_Mag::generator()->set_attr( 'show-term-banner', true );

                if( Better_Mag::posts()->have_posts() ){

                    while( Better_Mag::posts()->have_posts() ){
                        Better_Mag::posts()->the_post();

                        Better_Mag::generator()->blocks()->block_modern();

                    }

                }
            }

            elseif( $tab['style'] == 'highlight' ){

                Better_Mag::generator()->set_attr( 'show-term-banner', true );
                if( Better_Mag::posts()->have_posts() ){

                    while( Better_Mag::posts()->have_posts() ){
                        Better_Mag::posts()->the_post();

                        Better_Mag::generator()->blocks()->block_highlight();

                    }

                }
            }

            if( $tab['read_more'] && $term ){

                if( isset( $args['cat'] ) ){
                    $term = get_term( $term, 'category' );
                    if( ! is_wp_error( $term ) )
                        echo '<div class="tab-read-more term-' . $term->term_id . '"><a href="' . get_term_link( $term, 'category' ) .'" title="'. Better_Translation()->_get_esc_attr( 'post_readmore' ) .'">'. Better_Translation()->_get( 'post_readmore' ) .'<i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) .'"></i></a></div>';
                }else{
                    $term = get_term( $term, 'post_tag' );
                    if( ! is_wp_error( $term ) )
                        echo '<div class="tab-read-more term-' . $term->term_id . '"><a href="' . get_term_link( $term, 'post_tag' ) .'" title="'. Better_Translation()->_get_esc_attr( 'post_readmore' ) .'">'. Better_Translation()->_get( 'post_readmore' ) .'<i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) .'"></i></a></div>';
                }

            }
            ?>
        </div>
    <?php
    }
}