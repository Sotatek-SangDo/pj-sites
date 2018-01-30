<?php

$input = Better_Framework::html()->add('input')->type( 'text' )->name( $options['input_name'] );

if( !$options['value'] == false )
    $input->val( $options['value']);

$media_title = empty( $options['media_title'] ) ? __( 'Upload', 'better-studio' ) : $options['media_title'];
$button_text = empty( $options['button_text'] ) ? __( 'Upload', 'better-studio' ) : $options['button_text'];

$a = Better_Framework::html()->add( 'a' )->class( 'bf-button' )->class( 'bf-main-button bf-button bf-media-upload-btn' )->data( 'mediatitle', $media_title )->data( 'buttontext', esc_attr( $button_text ) );

$a->text( '<i class="fa fa-upload"></i> ' . $button_text );

echo $input->display();
echo $a->display();