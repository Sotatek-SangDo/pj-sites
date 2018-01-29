<?php

/**
 * BetterFramework Social Share Widget
 */
class BF_Advertisement_Code_Widget extends BF_Widget{

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
                'name'          =>  __( 'Code:', 'better-studio' ),
                'attr_id'       =>  'code',
                'type'          =>  'textarea',
            ),

        );

        parent::__construct(
            'advertisement-code',
            __( 'BetterStudio - Advertisement Code', 'better-studio' ),
            array( 'description' => __( 'Advertisement Code Widget', 'better-studio' ) )
        );
    }

}