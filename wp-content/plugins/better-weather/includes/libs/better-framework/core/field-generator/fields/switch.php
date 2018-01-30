<?php
$hidden = Better_Framework::html()->add( 'input' )->type( 'hidden' )->name( $options['input_name'] )->val('0')->class('checkbox');

// On Label
$on_label = __( 'On', 'better-studio' );
if( isset( $options['on-label'] ) ){
    $on_label = $options['on-label'];
}

// On Label
$off_label = __( 'Off', 'better-studio' );
if( isset( $options['off-label'] ) ){
    $off_label = $options['off-label'];
}

//var_dump($options['value']);

if( $options['value'] ){
    $on_checked = 'selected';
    $off_checked = '';
    $hidden->attr( 'value', '1');

}else{
    $on_checked = '';
    $off_checked = 'selected';
}

if( isset( $options['input_class'] ) ){
    $hidden->class($options['input_class']);
}

?><div class="bf-switch bf-clearfix">

    <label class="cb-enable <?php echo esc_attr( $on_checked ); ?>"><span><?php echo $on_label; ?></span></label>
    <label class="cb-disable <?php echo esc_attr( $off_checked ); ?>"><span><?php echo $off_label; ?></span></label>
    <?php

    echo $hidden->display();

    ?>
</div>