<?php
echo '<div class="bf-ajax_select-field-container">';

$search_input = Better_Framework::html()->add( 'input' )->type( 'text' )->class( 'bf-ajax-suggest-input' );

if( isset( $options['placeholder'] ) && ! empty( $options['placeholder'] ))
    $search_input->attr( "placeholder", $options['placeholder'] );

echo $search_input->display();

echo Better_Framework::html()->add( 'span' )->class( 'bf-search-loader' )->text( '<i class="fa fa-search"></i>')->display();

$input = Better_Framework::html()->add( 'input' )->type( 'hidden' )->name( $options['input_name'] );

if( !empty( $options['value'] ) )
    $input->val( $options['value'] );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);


$input->data( 'callback', $options['callback'] );
$input->data( 'token', Better_Framework::callback_token($options['callback']) );

echo $input->display();

echo Better_Framework::html()->add( 'ul' )->class( 'bf-ajax-suggest-search-results' )->display();

$controls = Better_Framework::html()->add( 'ul' )->class( 'bf-ajax-suggest-controls' );

if( !empty( $options['value'] ) )
    foreach( explode( ',', $options['value'] ) as $val ) {
        $name = isset( $options['get_name'] ) && is_callable( $options['get_name'] ) ? call_user_func( $options['get_name'], $val ) : $val;
        $sub = Better_Framework::html()->add( 'li' )->data( 'id', $val )->text( $name )->text( '<i class="del fa fa-remove"></i>' );
        $controls->text( $sub );
    }

echo $controls->display();

echo '</div>';