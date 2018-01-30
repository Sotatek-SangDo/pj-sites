<?php

/**
 * Metabox Fields Generator
 */
class BF_Metabox_Front_End_Generator extends BF_Admin_Fields{


    /**
     * Constructor Function
     *
     * @param array $items      Panel All Options
     * @param int   $id         Panel ID
     * @param array $values     Panel Saved Values
     *
     * @since 1.0
     * @access public
     */
    public function __construct( array &$items, &$id, &$values = array() ){
        // Parent Constructor
        parent::__construct( array(
            'templates_dir' => BF_PATH . 'metabox/templates/'
        ));

        $this->items  = $items;
        $this->id	  = $id;
        $this->values = $values;
    }


    /**
     * Used for creating input name
     *
     * @param $options
     *
     * @return string
     */
    public function input_name( &$options ){
        $id   = isset($options['id']) ? $options['id'] : '';
        $type = isset($options['type']) ? $options['type'] : '';

        switch( $type ){

            case( 'repeater' ):
                return "bf-metabox-option[%s][%s][%d][%s]";
                break;

            default:
                return "bf-metabox-option[{$this->id}][{$id}]";
                break;

        }

    }


    /**
     *  Metabox panel generator
     */
    public function callback(){

        $items_has_tab = $this->has_tab();
        $has_tab 	   = false;
        $wrapper   	   = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-wrap bf-clearfix' )->data( 'id', $this->id );

        // Add Class For Post Format Filter
        if(isset($this->items['config']['post_format'])){
            $wrapper->data('bf_pf_filter', implode(',',$this->items['config']['post_format']));
        }

        $container 	   = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-container' );
        $tab_counter   = 0;

        $group_counter = 0;

        if( $items_has_tab ) {
            $container->class( 'bf-with-tabs' );
            $tabs_container = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-tabs' );
            $tabs_container->text( $this->get_tabs() );
            $wrapper->text( $tabs_container->display() );
            BF()->assets_manager()->add_admin_css( '#bf_' .  $this->id . ' .inside{margin: 0 !important; padding: 0 !important;}' );
        }

        if( isset( $this->items['panel-id'] ) ){
            $std_id = Better_Framework::options()->get_std_field_id( $this->items['panel-id'] );
        }else{
            $std_id = 'std';
        }


        foreach( $this->items['fields'] as $field ){

            $field['input_name'] = $this->input_name( $field );

            $field['value'] = isset($field['id']) && isset( $this->values[ $field['id'] ] ) ? $this->values[ $field['id'] ] : null;

            if( is_null( $field['value'] ) && isset( $field[$std_id] ) ){
                $field['value'] = $field[$std_id];
            }elseif( is_null( $field['value'] ) && isset( $field['std'] ) ){
                $field['value'] = $field['std'];
            }

            if( isset( $field['filter-field'] ) && $field['filter-field-value'] ){

                // Get value if is available
                $filter_field_value = isset( $this->values[ $field['filter-field'] ] ) ? $this->values[ $field['filter-field'] ] : null;

                // is null means this is post create screen and filter field default value should be used for
                // default comparison
                if( is_null( $filter_field_value ) ){

                    if( isset( $this->items['fields'][$field['filter-field']][$std_id] ) ){
                        $filter_field_value = $this->items['fields'][$field['filter-field']][$std_id];
                    }elseif( isset( $this->items['fields'][$field['filter-field']]['std'] ) ){
                        $filter_field_value = $this->items['fields'][$field['filter-field']]['std'];
                    }else{
                        $filter_field_value = false;
                    }

                }

                if( $field['filter-field-value'] !== $filter_field_value ){
                    $field['section-css']['display'] = "none";
                }

            }

            if( $field['type'] == 'repeater' ){
                $field['clone-name-format'] = 'bf-metabox-option[$3][$4][$5][$6]';
                $field['metabox-id'] = $this->id;
                $field['metabox-field'] = true;
            }

            if( $field['type'] == 'tab' || $field['type'] == 'subtab' ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $container->text( $this->get_fields_group_close( $field ) );
                }

                $is_subtab = $field['type'] == 'subtab';

                if( $tab_counter != 0 )
                    $container->text( '</div><!-- /Section -->' );

                if( $is_subtab )
                    $container->text( "\n\n<!-- Section -->\n<div class='group subtab-group' id='bf-metabox-{$this->id}-{$field["id"]}'>\n" );
                else
                    $container->text( "\n\n<!-- Section -->\n<div class='group' id='bf-metabox-{$this->id}-{$field["id"]}'>\n" );
                $has_tab = true;
                $tab_counter++;
                continue;
            }

            if( $field['type'] == 'group_close'  ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $container->text( $this->get_fields_group_close( $field ) );
                }
                continue;
            }
            

            if( $field['type'] == 'group'  ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $container->text( $this->get_fields_group_close( $field ) );
                }

                $container->text( $this->get_fields_group_start( $field ) );

                $group_counter++;
            }


            if( !in_array( $field['type'], $this->supported_fields ) )
                continue;

            // Filter Each Field For Post Formats!
            if( isset( $field['post_format'] )){

                if( is_array( $field['post_format'] ) ){
                    $field_post_formats = implode( ',', $field['post_format'] );
                }else{
                    $field_post_formats = $field['post_format'];
                }
                $container->text( "<div class='bf-field-post-format-filter' data-bf_pf_filter='{$field_post_formats}'>");
            }

            $container->text(
                $this->section(
                    call_user_func(
                        array( $this, $field['type'] ),
                        $field
                    ),
                    $field
                )
            );

            // End Post Format Filter Wrapper
            if( isset( $field['post_format'] ) ){

                $container->text( "</div>");
            }
        }

        // close tag for latest group in tab
        if( $group_counter != 0 ){
            $container->text( $this->get_fields_group_close( $field ) );
        }

        // last sub tab closing
        if( $has_tab )
            $container->text( "</div><!-- /Section -->");

        $wrapper->text( $container->display() );

        echo $wrapper;
    } // callback
}