<?php

/**
 * BetterMag Content Listing 1
 */
class BM_Content_Listing_1_Shortcode extends BM_Listing_Shortcode {

    function __construct(){

        $id = 'bm_content_listing_1';

        $this->name = __( 'Content Listing 1', 'better-studio' );

        $this->description = __( 'Blog style listing', 'better-studio' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $options = array(
            'defaults' => array(
                'title'             =>  '',
                'hide_title'        =>  0,
                'icon'              =>  '',
                'category'          =>  '',
                'tag'               =>  '',
                'post_type'         =>  '',
                'count'             =>  5,
                'show_pagination'   =>  0,
                'show_read_more'    =>  0,
                'order_by'          =>  'date',
                'order'             =>  'DESC',
            ),

            'have_widget' => false,
            'have_vc_add_on' => true,
        );

        parent::__construct($id, $options);

    }


    /**
     * Handle displaying of shortcode
     *
     * @param $atts
     * @param $content
     * @return string
     */
    function display(array $atts, $content = ''){

        ob_start();
        ?>
        <div class="row block-listing">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs"><?php

                if( empty( $atts['count'] ) || intval( $atts['count'] ) < 1 )
                    $atts['count'] = get_option('posts_per_page');

                $args = array(
                    'post_type'     =>  array( 'post' ),
                    'posts_per_page'=>  $atts['count'],
                    'order'         =>  $atts['order'],
                    'orderby'       =>  $atts['order_by'],
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

                if( $atts['show_pagination'] ){
                    $args['paged'] = bf_get_query_var_paged();
                }

                Better_Mag::generator()->set_attr( "hide-meta-comment-if-views-reviews", true );

                $this->decision_showing_of_term_banner( $atts );

                Better_Mag::posts()->set_query( new WP_Query( apply_filters( 'better-mag/content-listing-1/args', $args ) ) );

                $this->the_block_title( $atts );

                Better_Mag::generator()->set_attr_class( 'bottom-line' );

                while( Better_Mag::posts()->have_posts() ){
                    Better_Mag::posts()->the_post();
                    Better_Mag::generator()->blocks()->block_blog();
                }

                if( $atts['show_pagination'] ){
                    Better_Mag::generator()->blocks()->get_pagination();
                }
                ?>
            </div>
        </div>
        <?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }


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
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'Category', 'better-studio' ),
                    "param_name"    => 'category',
                    "value"         => $this->defaults['category'],
                    "options"       => array( __( 'All Posts', 'better-studio' ) => __( 'All Posts', 'better-studio' ) ) + BF_Query::get_categories_by_slug(),
                ),
                array(
                    "type"          =>  'bf_ajax_select',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Tags', 'better-studio' ),
                    "param_name"    =>  'tag',
                    "value"         =>  $this->defaults['tag'],
                    "callback"      =>  'BF_Ajax_Select_Callbacks::tags_slug_callback' ,
                    "get_name"      =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   =>  __( "Search tag...", 'better-studio' ),
                    'description'   =>  __( "Search and select tags. You can use combination of Category and Tags!", 'better-studio' )
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
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"    =>  'count',
                    "value"         =>  $this->defaults['count'],
                    "description"   =>  __( 'Configures posts to show. Leave empty to use theme default number of posts.', 'better-studio' )
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show Pagination?', 'better-studio' ),
                    "param_name"    =>  'show_pagination',
                    "value"         =>  $this->defaults['show_pagination'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Custom Heading (Optional)', 'better-studio' ),
                    "param_name"    =>  'title',
                    "value"         =>  $this->defaults['title'],
                ),
                array(
                    "type"          =>  'bf_icon_select',
                    "heading"       =>  __( 'Custom Heading Icon (Optional)', 'better-studio' ),
                    "param_name"    =>  'icon',
                    "admin_label"   =>  true,
                    "value"         =>  $this->defaults['icon'],
                    "description"   =>  __( 'Select custom icon for listing.', 'better-studio' ),
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

class WPBakeryShortCode_bm_content_listing_1 extends BM_VC_Shortcode_Extender { }
