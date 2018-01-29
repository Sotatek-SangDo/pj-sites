<?php

/**
 * Posts Slider Widget
 */
class BM_Posts_Slider_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Title:', 'better-studio'),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),
            array(
                'name'          =>  __( 'Order:', 'better-studio'),
                'attr_id'       =>  'order',
                'type'          =>  'select',
                'section_class' =>  'widefat',
                "options"       =>  array(
                    'recent'    =>  __( 'Recent Posts', 'better-studio' ),
                    'popular'   =>  __( 'Popular Posts ( by Comment )', 'better-studio' ),
                    'views'     =>  __( 'Popular Posts ( by Views )', 'better-studio' ),
                ) ,
            ),
            array(
                'name'          =>  __( 'Category:', 'better-studio'),
                'attr_id'       =>  'category',
                'type'          =>  'select',
                'section_class' => 'widefat',
                "options"       =>  array(
                    'All Posts'         =>  __( 'All Posts', 'better-studio' ),
                    'bm-review-posts'   =>  __( 'Review Posts', 'better-studio' ),
                    'category'  => array(
                        'label'     =>  __( 'Category', 'better-studio' ),
                        'options'     =>  array(
                            'category_walker'    => true,
                        ),
                    )
                ),
            ),
            array(
                'name'          =>  __( 'Tags:', 'better-studio'),
                'attr_id'       =>  'tag',
                'type'          =>  'ajax_select',
                "callback"      =>  'BF_Ajax_Select_Callbacks::tags_callback' ,
                "get_name"      =>  'BF_Ajax_Select_Callbacks::tag_name',
                'placeholder'   =>  __( 'Select Tags...', 'better-studio' ),
                'section_class' =>  'widefat',
            ),
            array(
                "name"          =>  __( 'Custom Post Type:', 'better-studio' ),
                "attr_id"       =>  'post_type',
                "type"          =>  'text',
            ),
            array(
                'name'          =>  __( 'Number Of Posts:', 'better-studio'),
                'attr_id'       =>  'count',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),
        );

        parent::__construct(
            'bm-posts-slider',
            __( 'BetterStudio - Posts Slider', 'better-studio' ),
            array( 'description' => __( 'Display posts in slider.', 'better-studio' ) )
        );
    }
}