<?php

/**
 * Base class for widgets
 */
class BF_Widget extends WP_Widget{


    /**
     * Widget Position in wp-admin/widgets.php
     * @var int
     */
    var $position = 30;

    /**
     * $default values for widget fields
     *
     * @var array
     */
    var $defaults = array();


    /**
     * Contains shortcode id of widget
     * @var string
     */
    var $base_widget_id;


    /**
     * Contain all fields of widget
     *
     * @var array
     */
    var $fields = array();


    /**
     * Show widget title
     *
     * @var bool
     */
    var $with_title = true;


    /**
     * Register widget with WordPress.
     *
     * @param string $shortcode_id
     * @param string $title
     * @param array $desc
     * @param bool $widget_id
     */
    function __construct( $shortcode_id = '', $title = '', $desc = array(), $widget_id = false ){

        if( empty( $shortcode_id ) ) return;

        $this->defaults = BF_Shortcodes_Manager::factory( $shortcode_id )->defaults;

        $this->base_widget_id = $shortcode_id;

        if( $widget_id != false ){
            parent::__construct(
                $widget_id,
                $title,
                $desc
            );
        }else{
            parent::__construct(
                $shortcode_id,
                $title,
                $desc
            );
        }

    }


    /**
     * Prepare fields for field generator
     */
    function prepare_fields(){

        for( $i=0 ; $i < count( $this->fields ) ; $i++ ){

            // do not do anything on fields that haven't ID, ex: group fields
            if( ! isset( $this->fields[$i]['attr_id'] ) ){
                continue;
            }

            $this->fields[$i]['input_name'] = $this->get_field_name( $this->fields[$i]['attr_id'] );

            $this->fields[$i]['id'] = $this->get_field_id( $this->fields[$i]['attr_id'] );

            if( $this->fields[$i]['type'] == 'repeater' ){

                for( $j=0 ; $j < count( $this->fields[$i]['options'] ) ; $j++ ){

                    $this->fields[$i]['options'][$j]['input_name'] = $this->fields[$i]['input_name'].'[%d]['.$this->fields[$i]['options'][$j]['id'] .']';

                }

            }
        }

    }


    /**
     * Merge two arrays to one, if $atts key not defined or is empty then $default value will be set.
     *
     * @param $default
     * @param $atts
     * @internal param bool $empties
     * @return mixed
     */
    function parse_args( $default , $atts ){

        foreach( (array)$default as $key => $value  ){

            // empty fields in $atts is ok!
            if( ! isset( $atts[$key] ) /*|| ( empty( $atts[$key] ) )*/ ){
                $atts[$key] = $value;
            }

        }
        return $atts;
    }


    /**
     * Front-end display of widget.
     *
     * @see BetterWidget::widget()
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance){

        $instance = $this->parse_args( $this->defaults , $instance  );

        echo $args['before_widget'];

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->base_widget_id );
        if( ! empty($title) && $this->with_title ){
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo BF_Shortcodes_Manager::factory( $this->base_widget_id )->handle_widget( $instance );

        echo $args['after_widget'];
    }


    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ){
        return $this->parse_args( $this->defaults , $new_instance );
    }


    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return string|void
     */
    public function form($instance){

        $instance = $this->parse_args( $this->defaults ,$instance );

        Better_Framework::factory( 'widgets-field-generator' , false , true );

        // prepare fields for generator
        $this->prepare_fields();
        $options = array(
            'fields'    => $this->fields
        );

        $generator = new BF_Widgets_Field_Generator( $options , $instance );

        echo $generator->get_fields();

    }
}
