<?php

/**
 * BetterFramework About Widget
 */
class BF_About_Widget extends BF_Widget{

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
                'name'          =>  __( 'About Site:', 'better-studio' ),
                'attr_id'       =>  'text',
                'type'          =>  'textarea',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Logo Text:', 'better-studio' ),
                'attr_id'       =>  'logo_text',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Logo Image:', 'better-studio' ),
                'attr_id'       =>  'logo_img',
                'type'          =>  'media_image',
                'section_class' => 'widefat',
                'upload_label'  => __( 'Upload Logo', 'better-studio' ),
                'remove_label'  => __( 'Remove Logo', 'better-studio' ),
                'media_title'   => __( 'Upload Logo', 'better-studio' ),
                'media_button'  => __( 'Select As Logo', 'better-studio' ),
            ),

        );

        parent::__construct(
            'about',
            __( 'BetterStudio - About', 'better-studio' ),
            array( 'description' => __( '"About" site widget.', 'better-studio' ) )
        );
    }
}