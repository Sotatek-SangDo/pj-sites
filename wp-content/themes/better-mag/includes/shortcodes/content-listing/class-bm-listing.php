<?php

class BM_Listing_Shortcode extends BF_Shortcode{


    function __construct( $id, $options ){

        parent::__construct( $id , $options );

    }


    /**
     * Used for showing listing title
     */
    function the_block_title( &$atts ){

        if( $atts['hide_title'] ){
            return false;
        }

        $atts['title-class'] = '';

        // Add Category ID class to title for styling
        if( $atts['category'] != __( 'All Posts', 'better-studio' ) && ! empty( $atts['category'] ) ){
            $term = get_term_by( 'slug', $atts['category'], 'category');
            $atts['title-class'] .= ' term-' . $term->term_id;
        }

        // Add Tag ID class to title for styling
        if( $atts['tag'] ){
            $tags = explode( ',', $atts['tag'] );
            $term = get_term_by( 'slug', $tags[0], 'post_tag');
            $atts['title-class'] .= ' term-' . $term->term_id;
        }

        // Add icon
        if( $atts['icon'] )
            $atts['icon'] = '<i class="fa ' . $atts['icon'] . '"></i> ';
        else
            $atts['icon'] = '';

        // Adds more link
        // Priority: 1- Category 2- First Tag
        $more_link = false;
        if( $atts['show_read_more'] ){
            if(  $atts['category'] != __( 'All Posts', 'better-studio' ) && ! is_wp_error( $atts['category'] ) ){
                $more_link = array(
                    'href'      =>  get_term_link( $atts['category'], 'category' ),
                    'title'     =>  Better_Translation()->_get( 'post_readmore_dot' ) . '<i class="fa fa-chevron-right"></i>',
                    'class'     =>  'listing-read-more'
                );
            }
            elseif( $atts['tag'] ){
                $more_link = array(
                    'href'      =>  get_term_link( $term, 'post_tag' ),
                    'title'     =>  Better_Translation()->_get( 'post_readmore_dot' ),
                    'class'     =>  'listing-read-more'
                );
            }

        }

        // Custom Title
        if( $atts['title'] ){
            if( $more_link == false ){
                Better_Mag::generator()->blocks()->get_block_title( $atts['icon'] . $atts['title'], false, true, $atts['title-class'] );
            }else{
                Better_Mag::generator()->blocks()->get_extended_block_title( $atts['icon'] . $atts['title'], false, array( $more_link ), true, $atts['title-class'] );
            }
        }

        // Default Title
        else{

            if( $atts['tag'] )
                $title = BM_Helper::get_combined_term_title( $atts['category'], explode( ',', $atts['tag'] ) );
            else
                $title = BM_Helper::get_combined_term_title( $atts['category'], $atts['tag'] );

            if( $title )
                if( $more_link == false ){
                    Better_Mag::generator()->blocks()->get_block_title( $atts['icon'] . $title, false, true, $atts['title-class'] );
                }else{
                    Better_Mag::generator()->blocks()->get_extended_block_title( $atts['icon'] . $title, false, array( $more_link ), true, $atts['title-class'] );
                }
            else
                if( $more_link == false ){
                    Better_Mag::generator()->blocks()->get_block_title( $atts['icon'] . Better_Translation()->_get( 'post_latest_posts' ), false, true, $atts['title-class'] );
                }else{
                    Better_Mag::generator()->blocks()->get_extended_block_title( $atts['icon'] . Better_Translation()->_get( 'post_latest_posts' ), false, array( $more_link ), true, $atts['title-class'] );
                }

        }


    }


    /**
     * Used for Making decision term banner must shown or not
     * @param $atts
     */
    function decision_showing_of_term_banner( $atts ){

        if(
            ( $atts['category'] == __( 'All Posts', 'better-studio' ) &&
                empty( $atts['tag'] ) ) ||
            ( $atts['hide_title'] ) &&
            ( ! isset( $atts['inside-tab'] ) )
        ){
            Better_Mag::generator()->set_attr( 'show-term-banner', true );
        }

    }


    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map( array(
            "name"      => $this->name,
            "base"      => $this->id,
            "description"=> $this->description,
            "icon"      => $this->icon,
            "weight"    => 10,
            "wrapper_height"    => 'full',

            "category"  => __( 'BetterMag Addons', 'better-studio' ),
            "params"    => array(
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
                    "type"      =>  'textfield',
                    "admin_label" => true,
                    "heading"   =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"=>  'count',
                    "value"     =>  $this->defaults['count'],
                    "description"=> __( 'Configures posts to show. Leave empty to use theme default number of posts.', 'better-studio' )
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
        ) );
    }
}