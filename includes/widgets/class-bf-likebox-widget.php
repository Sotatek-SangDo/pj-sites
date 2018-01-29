<?php

/**
 * BetterFramework Likebox Widget
 */
class BF_Likebox_Widget extends BF_Widget{

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
                'std'           =>  __('<ol>
    <li>Copy the link to you facebook page</li>
    <li>Paste it in the "Link to you Facebook page" input box below</li>
  </ol>
                ', 'better-studio' ),
                'state'         =>  'open',
                'info-type'     =>  'help',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Title (Optional):', 'better-studio' ),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Facebook Page Link:', 'better-studio' ),
                'attr_id'       =>  'url',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Color Scheme:', 'better-studio'),
                'attr_id'       =>  'style',
                'type'          =>  'select',
                'section_class' =>  'style-floated-left',
                'value'         =>  'light',
                'options'       =>  array(
                    'light'         =>  __( 'Light Schema', 'better-studio' ),
                    'dark'          =>  __( 'Dark Schema' , 'better-studio' ),
                ),
            ),
            array(
                'name'          =>  __( 'Show Posts:', 'better-studio' ),
                'attr_id'       =>  'show_posts',
                'id'            =>  'show_posts',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
            ),
            array(
                'name'          =>  __( 'Show Faces:', 'better-studio' ),
                'attr_id'       =>  'show_faces',
                'id'            =>  'show_faces',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
            ),
            array(
                'name'          =>  __( 'Show Header:', 'better-studio' ),
                'attr_id'       =>  'show_header',
                'id'            =>  'show_header',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
            ),
            array(
                'name'          =>  __( 'Show Border:', 'better-studio' ),
                'attr_id'       =>  'show_border',
                'id'            =>  'show_border',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
            ),

        );

        parent::__construct(
            'likebox',
            __( 'BetterStudio - Like Box', 'better-studio' ),
            array( 'description' => __( 'Display a Facebook Like Box', 'better-studio' ) )
        );
    }
}