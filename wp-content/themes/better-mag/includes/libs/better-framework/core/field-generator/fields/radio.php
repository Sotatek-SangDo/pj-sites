<?php

if( empty( $options['options'] ) )
    return;

foreach( $options['options'] as $id => $val ){
    $container = Better_Framework::html()->add( 'div' )->class( 'bf-radio-button-option' );
    $label	   = Better_Framework::html()->add( 'label' );

    $input	   = Better_Framework::html()->add( 'input' )->type( 'radio' )->name( $options['input_name'] )->val( $id );
    if( !empty( $options['value'] ) && $options['value'] == $id )
        $input->attr( 'checked', 'checked' );

    $label->text( $input );
    $label->text( $val );

    $container->text( $label );

    echo $container->display();
}