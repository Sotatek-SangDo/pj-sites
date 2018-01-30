<?php

$wrapper = Better_Framework::html()->add( 'div' )->class( 'bf-clearfix' );
$input   = Better_Framework::html()->add( 'input' )->type( 'text' )->name( $options['input_name'] );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

if(  !empty( $options['value'] ) ) {
    $input->value( $options['value'] )->css('border-color', $options['value']);
}

$wrapper->add( $input );

echo $wrapper->display();
