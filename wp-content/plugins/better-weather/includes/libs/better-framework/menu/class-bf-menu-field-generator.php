<?php

class BF_Menu_Field_Generator extends BF_Admin_Fields{

    /**
     * Holds Fields Array
     *
     * @since 1.0
     * @access public
     * @var array|null
     */
    public $fields;


    /**
     * Menu item that contains values
     *
     * @since 1.0
     * @access public
     * @var array
     */
    public $menu_item;


    /**
     * Constructor Function
     *
     * @param array $items
     * @param array $fields
     * @param object $menu_item
     *
     * @since 1.0
     * @access public
     * @return \BF_Menu_Field_Generator
     */
    public function __construct( array $items, array $fields, $menu_item = null ){
        $default_options = array(
            'templates_dir'	=>	 BF_PATH . 'menu/templates/',
        );
        $items = array_merge( $default_options, $items );

        // Parent Constructor
        parent::__construct( $items );

        $this->fields  = $fields;

        $this->menu_item = $menu_item;
    }


    /**
     * Display HTML output of fields
     *
     * @since 1.0
     * @access public
     * @return string
     */
    public function get_fields(){

        $output	 = '';
        $group_counter = 0;

        foreach( $this->fields as $key => $field ){

            if( isset( $field['panel-id'] ) ){
                $std = Better_Framework::options()->get_std_field_id( $field['panel-id'] );
            }else{
                $std = 'std';
            }

            $field['value'] = isset( $this->menu_item->{$field['id']} ) ? $this->menu_item->{$field['id']} : false;

            if( $field['value'] == false && isset( $field[$std] ) )
                $field['value'] = $field[$std];

            if( $field['type'] == 'group_close'  ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $output .= $this->get_fields_group_close( $field );
                }
                continue;
            }

            if( $field['type'] == 'group'  ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $output .= $this->get_fields_group_close( $field );
                }

                $output .= $this->get_fields_group_start( $field );

                $group_counter++;
            }

            if( !in_array( $field['type'], $this->supported_fields ) )
                continue;

            // for image checkbox sortable option
            if( isset($field['is_sortable']) && ($field['is_sortable']=='1') )
                $field['section_class'] .=' is-sortable';

            $field['input_name'] = $this->generate_field_ID( $key , $this->menu_item->ID );

            $output .= $this->section(
                call_user_func(
                    array( $this, $field['type'] ),
                    $field
                ),
                $field
            );

        } // foreach

        // close tag for latest group in tab
        if( $group_counter != 0 ){
            $output .= $this->get_fields_group_close( $field );
        }

        return $output;
    }


    /**
     * Generate valid names for fields
     *
     * @param $key
     * @param $parent_id
     * @return string
     */
    public function generate_field_ID( $key , $parent_id ){

        return  'bf-m-i[' . esc_attr($key) . '][' . $parent_id . ']';

    }

}