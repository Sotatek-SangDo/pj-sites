<?php
$checkbox = BF()->html()->add( 'input' )->type( 'input' )->name( $options['input_name'] )->val('')->class('checkbox');


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

if( $options['value'] ){
    $on_checked = 'selected';
    $off_checked = '';
    $checkbox->val(1);

}else{
    $on_checked = '';
    $off_checked = 'selected';
    $checkbox->val(0);
}

if( isset( $options['input_class'] ) ){
    $checkbox->class($options['input_class']);
}

?><div class="bf-switch bf-clearfix">

    <label class="cb-enable <?php echo esc_attr( $on_checked ); ?>"><span><?php echo $on_label; ?></span></label>
    <label class="cb-disable <?php echo esc_attr( $off_checked ); ?>"><span><?php echo $off_label; ?></span></label>
    <?php

    echo $checkbox->display();

    ?>
</div>