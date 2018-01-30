<?php


/**
 * BetterFramework Social Share Widget
 */
class BF_Social_Share_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // haven't title in any location
        $this->with_title = true;

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Title:', 'better-studio'),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' => 'widefat',
            ),

            array(
                'name'          =>  __( 'Style:', 'better-studio'),
                'attr_id'       =>  'style',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left',
                'value'         =>  'clean',
                'options'       =>  array(
                    'button' => array(
                        'label'     => __( 'Button Style' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-button.png'
                    ),
                    'button-no-text' => array(
                        'label'     => __( 'Icon Button Style' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-button-no-text.png'
                    ),
                    'outline-button' => array(
                        'label'     => __( 'Outline Style' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-outline-button.png'
                    ),
                    'outline-button-no-text' => array(
                        'label'     => __( 'Icon Outline Style' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-outline-button-no-text.png'
                    ),
                ),
            ),

            array(
                'name'          =>  __( 'Colored Style:', 'better-studio' ),
                'attr_id'       =>  'colored',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Yes', 'better-studio' ),
                'off-label'     =>  __( 'No', 'better-studio' ),
            ),

            array(
                'name'          =>  __( 'Active Sites:', 'better-studio' ),
                'attr_id'       =>  'sites',
                'type'          =>  'sorter_checkbox',
                'options'       =>  array(
                    'facebook'      => array(
                        'label'     => '<i class="fa fa-facebook"></i> ' . __( 'Facebook', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'twitter'  => array(
                        'label'     => '<i class="fa fa-twitter"></i> ' . __( 'Twitter', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'google_plus'  => array(
                        'label'     => '<i class="fa fa-google-plus"></i> ' . __( 'Google+', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'pinterest'  => array(
                        'label'     => '<i class="fa fa-pinterest"></i> ' . __( 'Pinterest', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'linkedin'  => array(
                        'label'     => '<i class="fa fa-linkedin"></i> ' . __( 'Linkedin', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'tumblr'  => array(
                        'label'     => '<i class="fa fa-tumblr"></i> ' . __( 'Tumblr', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                    'email'  => array(
                        'label'     => '<i class="fa fa-envelope "></i> ' . __( 'Email', 'better-studio' ),
                        'css-class' => 'active-item'
                    ),
                ),
                'section_class' =>  'bf-social-share-sorter',
            ),

        );

        parent::__construct(
            'social-share',
            __( 'BetterStudio - Social Share', 'better-studio' ),
            array( 'description' => __( 'Social Share Widget', 'better-studio' ) )
        );
    }

}