<?php

class Better_Subscribe_Newsletter_Widget extends BF_Widget{

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
                'name'          =>  __( 'FeedBurner ID:', 'better-studio'),
                'input-desc'    =>  __( 'Enter Feedburner ID.', 'better-studio'),
                'attr_id'       =>  'feedburner-id',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),
            array(
                'name'          =>  __( 'Message Before:', 'better-studio'),
                'attr_id'       =>  'msg',
                'type'          =>  'textarea',
                'section_class' => 'widefat',
            ),

        );

        parent::__construct(
            'better-subscribe-newsletter',
            __( 'BetterStudio - Newsletter', 'better-studio' ),
            array( 'description' => __( 'Widget display NewsLetter Subscribe form it support feedburner.', 'better-studio' ) )
        );
    }
}