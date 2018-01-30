<?php

// TODO: change style for showing demo of uploaded image
$is_img = !empty( $options['value'] ) && preg_match( '/(gif)|(jpg)|(png)(jpeg)/i', pathinfo( $options['value'], PATHINFO_EXTENSION ) );

// Text Input
$input_text = Better_Framework::html()->add( 'input' )->type('text');
$input_text->name( $options['input_name'] );
if( $options['value'] !== false )
    $input_text->value( $options['value'] );
echo $input_text->display();

// Upload Button
$btn = Better_Framework::html()->add('label')->class('bf-button');
$btn->add( __( 'Upload', 'better-studio' ) );
$btn->add( Better_Framework::html()->add( 'input' )->name( 'bf_image_upload_' . $options['id'] )->class( 'bf-image-upload-choose-file hidden bf-button bf-main-button' )->type('file') );
echo $btn->display();


// Progress Bar
$bar = Better_Framework::html()->add( 'div' )->class( 'bf-image-upload-progress-bar' )->add( '<div class="bar"></div>' );
echo $bar->display();

// Image Preview
if( $is_img )
    echo Honar::html()->add( 'div' )->class( 'bf-image-upload-preview' )->add(  Better_Framework::html()->add( 'img' )->src( $options['value'] )  );