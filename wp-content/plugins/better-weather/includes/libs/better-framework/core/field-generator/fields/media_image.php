<?php

$input = Better_Framework::html()->add('input')->type( 'hidden' )->name( $options['input_name'] )->class('bf-media-image-input');
if( !$options['value'] == false )
    $input->val( $options['value']);

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

$media_title = empty( $options['media_title'] ) ? __( 'Upload', 'better-studio' ) : $options['media_title'];
$button_text = empty( $options['media_button'] ) ? __( 'Upload', 'better-studio' ) : $options['media_button'];

$upload_label = empty( $options['upload_label'] ) ? __( 'Upload', 'better-studio' ) : $options['upload_label'];
$remove_label = empty( $options['remove_label'] ) ? __( 'Remove', 'better-studio' ) : $options['remove_label'];

$upload_button = Better_Framework::html()->add( 'a' )->class( 'bf-button bf-main-button bf-media-image-upload-btn' )->data( 'media-title', $media_title )->data( 'button-text', $button_text );
$upload_button->text( '<i class="fa fa-upload"></i> ' . $upload_label );

$remove_button = Better_Framework::html()->add( 'a' )->class( 'bf-button bf-media-image-remove-btn' );
$remove_button->text( '<i class="fa fa-remove"></i> ' . $remove_label );


if( isset( $options['data-type'] ) && $options['data-type'] == 'id' ){

    if( ! isset( $options['preview-size'] ) ){
        $options['preview-size'] = 'thumbnail';
    }
    $upload_button->class( 'bf-media-type-id' )->data( 'size', $options['preview-size'] );
}

if( $options['value'] == false )
    $remove_button->css('display:none');

echo $input->display();
echo $upload_button->display();
echo $remove_button->display();


if( $options['value'] != false ){
    echo '<div class="bf-media-image-preview">';
}else{
    echo '<div class="bf-media-image-preview" style="display: none">';
}

if( isset( $options['data-type'] ) && $options['data-type'] == 'id' && ! empty( $options['value'] ) ){
    $options['value'] = wp_get_attachment_image_src( $options['value'], $options['preview-size'] );
    $options['value'] = $options['value'][0];
}

echo '<img src="' . $options['value'] . '" />';

echo '</div>';
