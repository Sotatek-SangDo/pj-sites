<?php

/**
 * BetterMag Dribbble Widget
 */
class BM_Dribbble_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Title:', 'better-studio' ),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Dribbble ID:', 'better-studio' ),
                'attr_id'       =>  'user_id',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Dribbble Access Token:', 'better-studio' ),
                'attr_id'       =>  'access_token',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Number of Shots:', 'better-studio' ),
                'attr_id'       =>  'photo_count',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Columns:', 'better-studio' ),
                'attr_id'       =>  'column',
                'type'          =>  'select',
                'options'       =>  array(
                    2   =>  __( '2 Column', 'better-studio' ),
                    3   =>  __( '3 Column', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Show More Shots Link:', 'better-studio' ),
                'attr_id'       =>  'more',
                'type'          =>  'select',
                'options'       =>  array(
                    'show'  =>  __( 'Show', 'better-studio' ),
                    'hide'  =>  __( 'Hide', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),

        );

        parent::__construct(
            'bm-dribbble',
            __( 'BetterStudio - Dribbble', 'better-studio' ),
            array( 'description' => __( 'Display latest shots from Dribbble.', 'better-studio' ) )
        );
    }
}