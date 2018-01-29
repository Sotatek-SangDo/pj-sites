<?php
// stripcslashes for when json is splashed!
if( ! empty( $options['value'] ) ){
    $value = $options['value'];
}else{
    $value = array(
        'img'   => '',
        'type'  => 'cover'
    );
}

$media_title = empty( $options['media_title'] ) ? __( 'Upload', 'better-studio' ) : $options['media_title'];
$button_text = empty( $options['button_text'] ) ? __( 'Upload', 'better-studio' ) : $options['button_text'];

// Upload Button
$upload_button = Better_Framework::html()
    ->add( 'a' )
    ->class( 'bf-button bf-background-image-upload-btn button' )
    ->data( 'mediatitle', $media_title )
    ->data( 'buttontext', $button_text );

if( isset( $options['upload_label'] ) )
    $upload_button->text( $options['upload_label'] );
else
    $upload_button->text( __( 'Upload', 'better-studio' ) );

// Remove Button
$remove_button = Better_Framework::html()
    ->add( 'a' )
    ->class( 'bf-button bf-background-image-remove-btn button' );

if( isset( $options['remove_label'] ) )
    $remove_button->text( $options['remove_label'] );
else
    $remove_button->text( __( 'Remove', 'better-studio' ) );

if( $value['img'] == "" ){
    $remove_button->css( 'display' , 'none' );
}

// Select
$select = Better_Framework::html()
    ->add('select')
    ->attr( 'id', $options['id'] . '-select' )
    ->class( 'bf-background-image-uploader-select' )
    ->name( $options['input_name'] . '[type]' );


$select->text( '<option value="repeat" '.( $value['type'] == 'repeat' ? 'selected="selected"' : '' ).'>' . __( 'Repeat Horizontal and Vertical - Pattern', 'better-studio') .'</option>' );
$select->text( '<option value="cover" '.( $value['type'] == 'cover' ? 'selected="selected"' : '' ).'>' . __( 'Fully Cover Background - Photo', 'better-studio') .'</option>' );
$select->text( '<option value="repeat-y" '.( $value['type'] == 'repeat-y' ? 'selected="selected"' : '' ).'>' . __( 'Repeat Horizontal', 'better-studio') .'</option>' );
$select->text( '<option value="repeat-x" '.( $value['type'] == 'repeat-x' ? 'selected="selected"' : '' ).'>' . __( 'Repeat Vertical', 'better-studio') .'</option>' );
$select->text( '<option value="no-repeat" '.( $value['type'] == 'no-repeat' ? 'selected="selected"' : '' ).'>' . __( 'No Repeat', 'better-studio') .'</option>' );

if( $value['img'] == "" ){
    $select->css( 'display' , 'none' );
}

// Main Input
$input = Better_Framework::html()
    ->add('input')
    ->type( 'hidden' )
    ->class( 'bf-background-image-input' )
    ->name( $options['input_name'] . '[img]' )
    ->val( $value['img'] );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

echo $upload_button->display();
echo $remove_button->display();
echo '<br>';

echo $select->display();
echo $input->display();

if( $value['img'] != "" ){
    echo '<div class="bf-background-image-preview">';
}else{
    echo '<div class="bf-background-image-preview" style="display: none">';
}

    echo '<img src="' . $value['img'] . '" />';
    echo '</div>';
