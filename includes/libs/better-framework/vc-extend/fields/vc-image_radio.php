<?php
$value = !empty( $options['value'] ) ? $options['value'] : '';


$wrapper = Better_Framework::html()->add( 'div' )->class( 'bf-clearfix' );
$input   = Better_Framework::html()->add( 'input' )->type( 'hidden' )->name( $options['input_name'] );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

if(  !empty( $options['value'] ) ) {
    $input->value( $options['value'] )->css('border-color', $options['value']);
}

$wrapper->add( $input );

echo $wrapper->display();

foreach ( $options['options'] as $key => $item ) {

    $is_checked  	= !empty( $value ) && ( $key==$value );

    $image  = Better_Framework::html()->add( 'img' )->src( $item['img'] )->alt( $item['label'] )->title( $item['label'] );
    $label  = Better_Framework::html()->add( 'label' );


    $label->text( $image );
    if(isset($item['label'])){
        $p  = Better_Framework::html()->add( 'p' )->text( $item['label'] )->class('item-label');
        $label->text( $p );
    }

    $object = Better_Framework::html()->add( 'div' )->class( 'vc-bf-image-radio-option' )->data( 'id', $key );

    if( $is_checked )
        $object->class( 'checked' );

    $object->text( $label->display() );

    echo $object->display();
}