<?php

// set options from deferred callback
if( isset( $options['deferred-options'] ) ){
    if( is_string( $options['deferred-options'] ) && is_callable( $options['deferred-options'] ) ){
        $options['options'] = call_user_func( $options['deferred-options'] );
    }elseif( is_array( $options['deferred-options'] ) && ! empty( $options['deferred-options']['callback'] ) && is_callable( $options['deferred-options']['callback'] ) ){
        if( isset( $options['deferred-options']['args'] ) ){
            $options['options'] = call_user_func_array( $options['deferred-options']['callback'], $options['deferred-options']['args'] );
        }else{
            $options['options'] = call_user_func( $options['deferred-options']['callback'] );
        }
    }
}

if( empty( $options['options'] ) )
    $options['options'] = array();

$value 		   = isset( $options['value'] ) && is_array( $options['value'] ) ? $options['value'] : array();
$groups_ids	   = array();

?>
<div class="bf-sorter-groups-container">
    <ul id="bf-sorter-group-<?php echo esc_attr( $options['id'] ); ?>" class="bf-sorter-list bf-sorter-<?php echo esc_attr( $options['id'] ); ?>" >
        <?php
        if ( is_array( $value) && count( $value) > 0 ) {

            foreach( $value as $item_id => $item ) { ?>
                <li id="bf-sorter-group-item-<?php echo esc_attr( $options['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>" class="<?php echo isset( $options['options'][$item_id]['css-class'] ) ? esc_attr( $options['options'][$item_id]['css-class'] ) : ''; ?>" style="<?php echo isset( $options['options'][$item_id]['css'] ) ? esc_attr( $options['options'][$item_id]['css'] ) : ''; ?>">
                    <?php echo is_array( $item) ? $item['label'] : $item; ?>
                    <input name="<?php echo esc_attr( $options['input_name'] ) ?>[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo is_array( $item ) ? esc_attr( $item['label'] ) : esc_attr( $item ); ?>" type="hidden"/>
                </li>
            <?php }
        }
        else {
            foreach( $options['options'] as $item_id => $item ) { ?>
                <li id="bf-sorter-group-item-<?php echo esc_attr( $options['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>" class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?>" style="<?php echo isset( $item['css'] ) ? $item['css'] : ''; ?>">
                    <?php echo is_array( $item) ? $item['label'] : $item; ?>
                    <input name="<?php echo esc_attr( $options['input_name'] )?>[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo is_array( $item) ? esc_attr( $item['label'] ) : esc_attr( $item ); ?>" type="hidden"/>
                </li>
            <?php }
        }
        ?>
    </ul>
</div>
