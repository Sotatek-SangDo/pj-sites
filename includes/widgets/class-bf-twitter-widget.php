<?php

/**
 * BetterFramework Twitter Widget
 */
class BF_Twitter_Widget extends BF_Widget{

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
                'name'          =>  __( 'Style:', 'better-studio'),
                'attr_id'       =>  'style',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left',
                'value'         =>  'style-1',
                'options'       =>  array(
                    'style-1'       =>  array(
                        'label'     => __( 'Style 1', 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-1.png'
                    ),
                    'style-2' => array(
                        'label'     => __( 'Style 2' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-2.png'
                    ),
                    'style-3'=> array(
                        'label'     => __( 'Style 3' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-3.png'
                    ),
                    'style-4'=> array(
                        'label'     => __( 'Style 4' , 'better-studio' ),
                        'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-4.png'
                    ),
                ),
            ),
            array(
                'name'          =>  __( 'Instructions', 'better-studio' ),
                'attr_id'       =>  'help',
                'type'          =>  'info',
                'std'           =>  __('
<p>You need to authenticate yourself to Twitter with creating an app for get access information to retrieve your tweets and display them on your page.</p><ol>
    <li>Go to <a href="http://goo.gl/tyCR5W" target="_blank">https://apps.twitter.com/app/new</a> and log in, if necessary</li>
    <li>Enter your Application Name, Description and your website address. You can leave the callback URL empty.</li>
    <li>Submit the form by clicking the Create your Twitter Application</li>
    <li>Go to the "Keys and Access Token" tab and copy the consumer key (API key) and consumer secret</li>
    <li>Paste them in the following input boxes</li>
    <li>Click on the "Create my access token" in bottom of page for creating access token and copy them</li>
    <li>Paste them in the following input boxes</li>
  </ol>
                ', 'better-studio' ),
                'state'         =>  'open',
                'info-type'     =>  'help',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Username:', 'better-studio' ),
                'attr_id'       =>  'username',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Consumer Key:', 'better-studio' ),
                'attr_id'       =>  'consumer_key',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Consumer Secret:', 'better-studio' ),
                'attr_id'       =>  'consumer_secret',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Access Token:', 'better-studio' ),
                'attr_id'       =>  'access_token',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Access Secret:', 'better-studio' ),
                'attr_id'       =>  'access_secret',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Number of Tweets:', 'better-studio' ),
                'attr_id'       =>  'tweets_count',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Links Target:', 'better-studio' ),
                'attr_id'       =>  'link_new_page',
                'id'            =>  'link_new_page',
                'type'          =>  'switch',
                'on-label'      =>  __( 'Yes', 'better-studio' ),
                'off-label'     =>  __( 'No', 'better-studio' ),
            ),
        );

        parent::__construct(
            'twitter',
            __( 'BetterStudio - Twitter', 'better-studio' ),
            array( 'description' => __( 'Latest tweets widget.', 'better-studio' ) )
        );
    }
}