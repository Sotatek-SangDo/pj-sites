<?php

/**
 * Login Register Widget
 */
class BM_Login_Register_Widget extends BF_Widget{


    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array(

            array(
                'name'          =>  __( 'Show Register Form', 'better-studio' ),
                'input-desc'    =>  __( 'Register form will be shown if user registration was enable.', 'better-studio' ),
                'attr_id'       =>  'show_register',
                'id'            =>  'show_register',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Yes', 'better-studio' ),
                'off-label'     =>  __( 'No', 'better-studio' ),

            ),

        );

        parent::__construct(
            'bm-login-register',
            __( 'BetterStudio - Login & Register', 'better-studio' ),
            array( 'description' => __( 'Login & Register Widget', 'better-studio' ) )
        );
    }
}