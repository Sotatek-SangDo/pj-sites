<?php
$hidden = Better_Framework::html()->add( 'input' )->type( 'hidden' )->name( $options['input_name'] )->val('0');

$checkbox = Better_Framework::html()->add( 'input' )->type( 'checkbox' )->name( $options['input_name'] )->val('1');
if( ! empty( $options['value'] ) ){
    if( $options['value'] == 'on' || $options['value'] == 'checked' || $options['value'] == '1' )
        $checkbox->attr( 'checked', 'checked');
}

echo $hidden->display(), $checkbox->display();
