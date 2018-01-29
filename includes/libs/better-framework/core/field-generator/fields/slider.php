<?php

$dimension = empty( $options['dimension'] ) ? '' : $options['dimension'];
$min	   = isset( $options['min'] )  		&& is_numeric( $options['min']  ) ? $options['min']  : 0;
$max	   = isset( $options['max'] )  		&& is_numeric( $options['max'] )  ? $options['max']  : 100;
$step	   = isset( $options['step'] )	    && is_numeric( $options['step'] ) ? $options['step'] : 1;
$animation = isset( $options['animation'] ) && !$options['animation']		  ? 'disable' 		 : 'enable';
$value     = empty( $options['value'] ) ? '0' : $options['value'];

$slider = Better_Framework::html()->add( 'div' )->class( 'bf-slider-slider' )->
    data( 'dimension', $dimension )->
    data( 'animation', $animation )->
    data( 'val', $value )->
    data( 'min', $min )->
    data( 'max', $max )->
    data( 'step', $step );

echo $slider->display();

$input = Better_Framework::html()->add( 'input' )->type( 'hidden' )->class( 'bf-slider-input' )->name( $options['input_name'] )->val( $value );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

echo $input->display();
