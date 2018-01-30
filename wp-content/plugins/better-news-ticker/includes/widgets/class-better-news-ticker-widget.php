<?php

/**
 * Better News Ticker Widget
 */
class Better_News_Ticker_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // haven't title in any location
        $this->with_title = false;

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'News Ticker Text', 'better-studio' ),
                'attr_id'       =>  'ticker_text',
                'type'          =>  'text',
                'section_class' =>  'widefat',
                'desc'          =>  __( 'Enter the text you wish to display before the headlines in the ticker.', 'better-studio' )
            ),
            array(
                'name'          =>  __( 'Speed', 'better-studio' ),
                'attr_id'       =>  'speed',
                'type'          =>  'slider',
                'dimension'     =>  'second',
                'min'           =>  '3',
                'max'           =>  '60',
                'step'          =>  '1',
                'std'           =>  '15',
                'desc'          =>  __( 'Set the speed of the ticker cycling, in second.', 'better-studio' ),
            ),
            array(
                'name'          =>  __( 'Category','better-studio' ),
                'attr_id'       =>  'cat',
                'type'          =>  'select',
                'std'           =>  'all',
                'value'         =>  'all',
                "options"       =>  array(
                    'all' => __('All Posts','better-studio'),
                    'category'  => array(
                        'label'     =>  __( 'Category', 'better-studio' ),
                        'options'     =>  array(
                            'category_walker'    => true,
                        ),
                    )
                ),
            ),
            array(
                'name'          =>  __( 'Number of Posts', 'better-studio' ),
                'attr_id'       =>  'count',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
        );

        parent::__construct(
            'better-news-ticker',
            __( 'Better News Ticker', 'better-studio' ),
            array( 'description' => __( 'News Ticker widget', 'better-studio' ) )
        );
    }

}