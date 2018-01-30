<?php

/**
 * BetterFramework Advertisement Image Widget
 */
class BF_Advertisement_Image_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // haven't title in any location
        $this->with_title = true;

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Title (Optional):', 'better-studio'),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),

            array(
                'name'          =>  __( 'Image:', 'better-studio' ),
                'attr_id'       =>  'image',
                'type'          =>  'media_image',
                'upload_label'  =>  __( 'Upload Ad Image', 'better-studio' ),
                'remove_label'  =>  __( 'Remove Ad Image', 'better-studio' ),
                'media_title'   =>  __( 'Upload Ad Image', 'better-studio' ),
                'media_button'  =>  __( 'Select As Ad Image', 'better-studio' ),
            ),

            array(
                'name'          =>  __( 'Caption (Optional):', 'better-studio'),
                'attr_id'       =>  'caption',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),

            array(
                'name'          =>  __( 'Link:', 'better-studio'),
                'attr_id'       =>  'link',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),

            array(
                'name'          =>  __( 'Link Target:', 'better-studio'),
                'attr_id'       =>  'target',
                'type'          =>  'select',
                'section_class' => 'widefat',
                "options"       =>  array(
                    '_blank'    => __( '_blank - in new window or tab' , 'better-studio' ),
                    '_self'     => __( '_self - in the same frame as it was clicked' , 'better-studio' ),
                    '_parent'   => __( '_parent - in the parent frame' , 'better-studio' ),
                    '_top'      => __( '_top - in the full body of the window' , 'better-studio' ),
                ),
            ),


        );

        parent::__construct(
            'advertisement-image',
            __( 'BetterStudio - Advertisement Image', 'better-studio' ),
            array( 'description' => __( 'Advertisement Image Widget', 'better-studio' ) )
        );
    }

}