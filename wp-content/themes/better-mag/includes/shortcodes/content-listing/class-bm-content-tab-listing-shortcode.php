<?php

/**
 * BetterMag Content Tab Listing
 */
class BM_Content_Tab_Listing_Shortcode extends BM_Listing_Shortcode{

    function __construct(){

        $id = 'bm_content_tab_listing';

        $this->name = __( 'Content Tab Listing', 'better-studio' );

        $this->description = __( 'Tabs listing of unlimited categories.', 'better-studio' );

        $this->icon = BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png';

        $options = array(
            'defaults' => array(

                'category'      =>  '',
                'tag'           =>  '',
                'post_type'     =>  '',
                'count'         =>  '',
                'block_listing' =>  'Content_Listing_3',
                'order_by'      =>  'date',
                'order'         =>  'DESC',
                'show_read_more'=>  '0',
            ),

            'have_widget' => false,
            'have_vc_add_on' => true,
        );

        parent::__construct( $id, $options );

    }


    /**
     * Handle displaying of shortcode
     *
     * @param $atts
     * @param $content
     * @return string
     */
    function display( array $atts, $content = '' ){
        ob_start();
        ?>
        <div class="row tab-content-listing block-listing">
            <div class="col-lg-12"><?php

                $other_tabs = array();
                $first_tab = array();

                if( intval($atts['count']) < 1 )
                    $atts['count'] = '';

                if( empty( $atts['category'] ) )
                    $atts['category'] = array();
                else
                    $atts['category'] = explode(',', $atts['category']);

                foreach( $atts['category'] as $cat ){

                    $_ar = array();
                    $term = get_term_by('slug', $cat, 'category');
                    $_ar['title'] = $term->name;
                    $_ar['href'] = '#' . $term->term_id;
                    $_ar['id'] = $term->term_id;
                    $_ar['slug'] = $cat;

                    if( ! count($first_tab) ){
                        $_ar['active'] = true;
                        $_ar['class'] = 'main-term';
                        $first_tab = $_ar;
                    }

                    $other_tabs[$cat] = $_ar;
                }

                // Collect Tags
                if( ! empty( $atts['tag'] ) ){
                    $atts['tag'] = explode(',', $atts['tag']);
                    foreach( (array) $atts['tag'] as $cat ){

                        $_ar = array();
                        $term = get_term_by('slug', $cat, 'post_tag');
                        $_ar['title'] = $term->name;
                        $_ar['href'] = '#' . $term->term_id;
                        $_ar['id'] = $term->term_id;
                        $_ar['slug'] = $cat;

                        if( ! count($first_tab) ){
                            $_ar['active'] = true;
                            $_ar['class'] = 'main-term';
                            $first_tab = $_ar;
                        }

                        $other_tabs[$cat] = $_ar;

                    }

                }

                Better_Mag::generator()->blocks()->get_tab_block_title( $other_tabs );

                ?>
                <div class="tab-content">
                    <?php

                    if( count( $atts['category'] ) ){
                        // First Tab ( Active )
                        $this->show_tab_content( $other_tabs[$atts['category'][0]], 'category', 'active', $atts['block_listing'], $atts );
                        unset( $atts['category'][0] );

                        // Other Tabs
                        foreach( $atts['category'] as $cat ){
                            $this->show_tab_content( $other_tabs[$cat], 'category', '', $atts['block_listing'], $atts );
                        }

                    }

                    if( ! empty( $atts['tag'] ) && count( $atts['tag'] ) ){

                        // First Tab ( Active )
                        if( ! count( $atts['category'] ) ){
                            $this->show_tab_content( $other_tabs[$atts['tag'][0]], 'post_tag', 'active', $atts['block_listing'], $atts );
                            unset( $atts['tag'][0] );
                        }

                        // Other Tabs
                        foreach( $atts['tag'] as $cat ){
                            $this->show_tab_content( $other_tabs[$cat], 'post_tag', '', $atts['block_listing'], $atts );
                        }

                    }
                    ?>
                </div>

            </div>
        </div>
        <?php

        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }

    function show_tab_content($term, $taxonomy, $class = '', $listing, $atts){

        $listing = bf_convert_string_to_class_name( $listing );

        ?>
        <div class="tab-pane <?php echo $class; ?>" id="<?php echo $term['id']; ?>"><?php

            $args = array(
                'hide_title'=>  true,
                'category'  =>  $term['slug'],
                'count'     =>  $atts['count'],
                'order'     =>  $atts['order'],
                'order_by'  =>  $atts['order_by'],
                'post_type' =>  $atts['post_type'],
                'inside-tab'=>  true,
            );

            if( $taxonomy == 'category' ){

                echo Better_Framework::shortcodes()->factory( $listing, array( 'shortcode_class' => 'BM_' . $listing . '_Shortcode' ) )->display(
                    Better_Framework::shortcodes()->factory( $listing, array( 'shortcode_class' => 'BM_' . $listing . '_Shortcode' ) )->get_atts( $args )
                );
                if( $atts['show_read_more'] )
                    echo '<div class="tab-read-more term-' . $term['id'] . '"><a href="' . get_term_link( $term['id'], 'category' ) .'" title="'. Better_Translation()->_get_esc_attr( 'post_readmore_dot' ) .'">'. Better_Translation()->_get( 'post_readmore_dot' ) .'<i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) .'"></i></a></div>';
            }
            else{

                $args = array(
                    'hide_title'=>  true,
                    'tag'       =>  $term['slug'],
                    'count'     =>  $atts['count'],
                    'order'     =>  $atts['order'],
                    'order_by'  =>  $atts['order_by'],
                    'post_type' =>  $atts['post_type'],
                    'inside-tab'=>  true,
                );

                echo Better_Framework::shortcodes()->factory( $listing, array( 'shortcode_class' => 'BM_' . $listing . '_Shortcode' ) )->display(
                    Better_Framework::shortcodes()->factory( $listing, array( 'shortcode_class' => 'BM_' . $listing . '_Shortcode' ) )->get_atts( $args )
                );

                if( $atts['show_read_more'] )
                    echo '<div class="tab-read-more term-' . $term['id'] . '"><a href="' . get_term_link( $term['id'], 'post_tag' ) .'" title="'. Better_Translation()->_get_esc_attr( 'post_readmore_dot' ) .'">'. Better_Translation()->_get( 'post_readmore_dot' ) .'<i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) .'"></i></a></div>';
            }


            ?>
        </div>
        <?php
    }


    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map(array(
            "name" => $this->name,
            "base" => $this->id,
            "icon" => $this->icon,
            "description" => $this->description,
            "weight" => 10,
            "wrapper_height" => 'full',

            "category" => __( 'BetterMag Addons', 'better-studio' ),
            "params" => array(

                array(
                    "type"          => 'bf_image_radio',
                    "heading"       => __( 'Listing Style', 'better-studio' ),
                    "param_name"    => 'block_listing',
                    'section_class' => 'style-floated-left vc-block-listing-style',
                    "admin_label"   => true,
                    "options"       => array(
                        'Content_Listing_3' => array(
                            'label'     => __( 'Content Listing 3', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_3.png',
                        ),
                        'Content_Listing_4' => array(
                            'label' => __( 'Content Listing 4', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_4.png',
                        ),
                        'Content_Listing_5' => array(
                            'label' => __( 'Content Listing 5', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_5.png',
                        ),
                        'Content_Listing_6' => array(
                            'label' => __( 'Content Listing 6', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_6.png',
                        ),
                        'Content_Listing_7' => array(
                            'label' => __( 'Content Listing 7', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_7.png',
                        ),
                        'Content_Listing_8' => array(
                            'label' => __( 'Content Listing 8', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_8.png',
                        ),
                        'Content_Listing_9' => array(
                            'label' => __( 'Content Listing 9', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_9.png',
                        ),
                        'Content_Listing_10' => array(
                            'label' => __( 'Content Listing 10', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_10.png',
                        ),
                        'Content_Listing_11' => array(
                            'label' => __( 'Content Listing 11', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_11.png',
                        ),
                        'Content_Listing_12' => array(
                            'label' => __( 'Content Listing 12', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_12.png',
                        ),
                        'Content_Listing_13' => array(
                            'label' => __( 'Content Listing 13', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_13.png',
                        ),
                        'Content_Listing_14' => array(
                            'label' => __( 'Content Listing 14', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_14.png',
                        ),
                        'Content_Listing_15' => array(
                            'label' => __( 'Content Listing 15', 'better-studio' ),
                            'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-bm_content_listing_15.png',
                        ),

                    ),
                    "value" => $this->defaults['block_listing'],
                    'description' => __( "Select listing style for tabs.", 'better-studio')
                ),

                array(
                    "type"          =>  'bf_ajax_select',
                    "heading"       =>  __( 'Tab Categories', 'better-studio' ),
                    "param_name"    =>  'category',
                    "admin_label"   =>  true,
                    "value"         =>  $this->defaults['category'],
                    "callback"      =>  'BF_Ajax_Select_Callbacks::cats_slug_callback',
                    "get_name"      =>  'BF_Ajax_Select_Callbacks::cat_by_slug_name',
                    'placeholder'   =>  __( "Search and find category...", 'better-studio' ),
                    'description'   =>  __( "Search, select and sort categories. First category is main tab", 'better-studio' )
                ),

                array(
                    "type"          =>  'bf_ajax_select',
                    "heading"       =>  __( 'Tab Tags', 'better-studio' ),
                    "param_name"    =>  'tag',
                    "admin_label"   =>  true,
                    "value"         =>  $this->defaults['tag'],
                    "callback"      =>  'BF_Ajax_Select_Callbacks::tags_slug_callback',
                    "get_name"      =>  'BF_Ajax_Select_Callbacks::tag_by_slug_name',
                    'placeholder'   =>  __( "Search and find tag...", 'better-studio' ),
                    'description'   =>  __( "Search and select tags.", 'better-studio' )
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
                    "type" => 'textfield',
                    "heading" => __('Number of Posts', 'better-studio'),
                    "param_name" => 'count',
                    "value" => $this->defaults['count'],
                    "description" => __( 'Configures posts to show in listing. Leave empty to use theme default number of content listing.', 'better-studio')
                ),
            )
        ));

    }

}

class WPBakeryShortCode_bm_content_tab_listing extends BM_VC_Shortcode_Extender{}