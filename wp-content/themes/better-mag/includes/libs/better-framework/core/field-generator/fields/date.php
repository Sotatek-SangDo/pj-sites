<?php
$date_format = empty( $options['date_format'] ) ? 'mm/dd/yy' : $options['date_format'];

$input = Better_Framework::html()->add( 'input' )->type( 'text' )->class( 'bf-date-picker-input' )->name( $options['input_name'] )->data( 'date-format', $date_format );
if( !empty( $options['value'] ) )
    $input->val( $options['value'] );

echo $input->display();

