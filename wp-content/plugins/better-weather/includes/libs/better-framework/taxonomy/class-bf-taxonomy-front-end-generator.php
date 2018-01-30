<?php

/**
 * Taxonomies Field Generator For Admin
 */
class BF_Taxonomy_Front_End_Generator extends BF_Admin_Fields{


    /**
     * Constructor Function
     *
     * @param array $items
     * @param $id
     * @param array $values
     *
     * @since 1.0
     * @access public
     * @return \BF_Taxonomy_Front_End_Generator
     */
    public function __construct( array &$items, &$id, &$values = array() ){

        // Parent Constructor
        $generator_options = array(
            'templates_dir' => BF_PATH . 'taxonomy/templates/'
        );
        parent::__construct( $generator_options );

        $this->items  = $items;
        $this->id	  = $id;
        $this->values = $values;
    }


    /**
     * Make input name from options variable
     *
     * @param (array) $options Options array
     *
     * @since 1.0
     * @access public
     * @return string
     */
    public function input_name( &$options ){

        $id   = @$options['id'];

        $type = @$options['type'];

        switch( $type ){

            case( 'repeater' ):
                return "bf-term-meta[%s][%d][%s]";
                break;

            default:
                return "bf-term-meta[{$id}]";
                break;
        }

    }


    /**
     * Used for generating fields
     */
    public function callback(){

        $items_has_tab = true;
        $has_tab 	   = false;
        $tab_counter   = 0;
        $group_counter = 0;

        $metabox_name = isset( $this->items['config']['name'] ) ? $this->items['config']['name'] : __( 'Better Options', 'better-studio' );

        // Base wrapper
        $wrapper   	   = Better_Framework::html()->add( 'div' )->class( 'bf-tax-meta-wrap bf-metabox-wrap bf-clearfix' )->data( 'id', $this->id );

        // Better Option Title
        $wrapper->text(
            Better_Framework::html()->add( 'div' )->class( 'bf-tax-metabox-title' )->text(
                Better_Framework::html()->add( 'h3' )->text( $metabox_name )
            )
        );

        $container 	   = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-container' );

        // Add Tab
        if( $items_has_tab ) {
            $container->class( 'bf-with-tabs' );
            $tabs_container = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-tabs' );
            $tabs_container->text( $this->get_tabs() );
            $wrapper->text( $tabs_container->display() );
        }

        foreach( $this->items['fields'] as $field ){

            $field['input_name'] = $this->input_name( $field );

            $field['value'] = isset( $this->values[ @$field['id'] ] ) ? $this->values[ $field['id'] ] : false;

            if( $field['type'] == 'repeater' ){
                $field['clone-name-format'] = 'bf-term-meta[%s][%d][%s]';
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

            $container->text(
                $this->section(
                    call_user_func(
                        array( $this, $field['type'] ),
                        $field
                    ),
                    $field
                )
            );

        }

        // close tag for latest group in tab
        if( $group_counter != 0 ){
            $container->text( $this->get_fields_group_close( $field ) );
        }

        // last sub tab closing
        if( $has_tab )
            $container->text( "</div><!-- /Section -->");


        $wrapper->text(
            $container->display()
        );
        echo $wrapper;

    } // callback
}