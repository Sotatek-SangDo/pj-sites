<?php

/**
 * BetterMag Video Widget
 */
class BM_Video_Widget extends BF_Widget{

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
                'name'          =>  __( 'Video URL:', 'better-studio' ),
                'attr_id'       =>  'url',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
        );

        parent::__construct(
            'bm-video',
            __( 'BetterStudio - Video', 'better-studio' ),
            array( 'description' => __( 'Display video in sidebar.', 'better-studio' ) )
        );
    }
}