<?php

if( empty( $options['options'] ) )
    return;

$name_format = empty( $options['input_name'] ) ? '%s[%d][%s]' : $options['input_name'];

// Add New Item Label
if(isset($options['add_label']) && !empty($options['add_label']))
    $add_label = $options['add_label'];
else
    $add_label = __( 'Add','better-studio' );

// Delete Item Label
if(isset($options['delete_label']) && !empty($options['delete_label']))
    $delete_label = $options['delete_label'];
else
    $delete_label = __('Delete','better-studio');

// Item title
if(isset($options['item_title']) && !empty($options['item_title']))
    $item_title = $options['item_title'];
else
    $item_title = __('Item','better-studio');


echo '<!-- Repeater Container --><div class="bf-repeater-items-container bf-clearfix">';


$repeater_item_start = '<!-- Repeater Item --><div class="bf-repeater-item"><div class="bf-repeater-item-title"><h5><span class="handle-repeater-title-label">'.$item_title.'</span><span class="handle-repeater-item"></span><span class="bf-remove-repeater-item-btn"><span class="dashicons dashicons-trash"></span>'.$delete_label.'</span></div><div class="repeater-item-container bf-clearfix">';
$repeater_item_end = '</div></div><!-- /Repeater Item -->';

// Lst saved values
if( isset( $options['value'] ) ){

    $counter = 0;

    foreach( (array) $options['value'] as $saved_key => $saved_val ){

        echo $repeater_item_start;

        foreach( (array) $options['options'] as $field_id => $field_options ){

            $this->generate_repeater_field( $options, $field_options, $saved_val, $name_format, $counter );

        }

        echo $repeater_item_end;

        $counter++;
    }

}else{ // Default value

    // multiple default value
    if( count( $options['default'] ) > 1 ){

        $counter = 0;

        foreach( (array) $options['default'] as $saved_key => $saved_val ){

            echo $repeater_item_start;

            foreach( (array) $options['options'] as $field_id => $field_options ){

                $this->generate_repeater_field( $options, $field_options, $saved_val, $name_format, $counter );

            }

            $counter++;

            echo $repeater_item_end;

        }
    }else{ // single default value

        $default = current( $options['default'] );

        echo $repeater_item_start;

        foreach( (array) $options['options'] as $field_id => $field_options ){

            $this->generate_repeater_field( $options, $field_options, $default, $name_format, 0 );

        }

        echo $repeater_item_end;
    }
}

echo '</div><!-- / Repeater Container -->';

// HTML Stuff for when user is adding new item to repeater
$script = Better_Framework::html()->add( 'script' )->type( 'text/html' );
ob_start();
echo '<!-- Repeater Item --><div class="bf-repeater-item"><div class="bf-repeater-item-title"><h5><span class="handle-repeater-title-label">'.$item_title.'</span><span class="handle-repeater-item"></span><span class="bf-remove-repeater-item-btn"><span class="dashicons dashicons-trash"></span>'.$delete_label.'</span></div><div class="repeater-item-container bf-clearfix">';

if( is_null( $options['default'] ) ){
    $options['default'] = array();
}

$default = current( $options['default'] );

foreach( $options['options'] as $script_option ){

    $this->generate_repeater_field_script( $options, $script_option, $default );

}

echo '</div></div><!-- /Repeater Item -->';
$script->text( ob_get_clean() );
echo $script->display();


// Add new item to repeater button
$new_btn = Better_Framework::html()->add( 'button' )->class( 'bf-clone-repeater-item bf-button bf-main-button' )->text( $add_label );

// Repeater in widgets
if( isset( $options['widget_field'] ) ){
    $new_btn = Better_Framework::html()->add( 'button' )->class( 'bf-widget-clone-repeater-item bf-button bf-main-button' )->text( $add_label );
}
// General Repeater
else{
    $new_btn = Better_Framework::html()->add( 'button' )->class( 'bf-clone-repeater-item bf-button bf-main-button' )->text( $add_label );
}

if( ! empty( $options['clone-name-format'] ) )
    $new_btn->data( 'name-format', $options['clone-name-format'] );

echo $new_btn->display();