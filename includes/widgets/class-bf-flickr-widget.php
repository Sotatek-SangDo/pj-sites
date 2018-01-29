<?php

/**
 * BetterFramework Flickr Widget
 */
class BF_Flickr_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Instructions', 'better-studio' ),
                'attr_id'       =>  'help',
                'type'          =>  'info',
                'std'           =>  __('<p>You need to get the user id from your Flickr account.</p>
                <ol>
                    <li>Attain your user id using <a href="http://goo.gl/pHx7LV" target="_blank">this tool</a></li>
                    <li>Copy the user id</li>
                    <li>Paste it in the "Flickr ID" input box below</li>
                </ol>
                ', 'better-studio' ),
                'state'         =>  'open',
                'info-type'     =>  'help',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Title:', 'better-studio' ),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Flickr ID:', 'better-studio' ),
                'attr_id'       =>  'user_id',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Number of Photos:', 'better-studio' ),
                'attr_id'       =>  'photo_count',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Tags (comma separated, optional):', 'better-studio' ),
                'attr_id'       =>  'tags',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
        );

        parent::__construct(
            'flickr',
            __( 'BetterStudio - Flickr', 'better-studio' ),
            array( 'description' => __( 'Display latest photos from Flickr.', 'better-studio' ) )
        );
    }
}