<?php

if( empty( $options['options'] ) )
    return;

if( isset( $options['value'] ) && ! empty( $options['value'] ) ){
	if( is_string( $options['value'] ) ){
		$value = array_flip( explode( ',', $options['value'] ) );
		$options['value'] = array_fill_keys( array_keys( $value ), true );
	}
}else{
	$options['value'] = array();
}


$value      = $options['value'];
$check_all  = count( $value ) ? false : true;

$groups_ids	= array();

$selected_items = array();

// Options That Saved Before
foreach( $value as $item_id => $item ) {

	if( ! $item ) continue;

	$selected_items[ $item_id ] = $item;
}

$input      = Better_Framework::html()->add( 'input' )->type( 'hidden' )->name( $options['input_name'] )->attr( 'value', implode( ',', array_keys( $selected_items ) ) );

if( isset( $options['input_class'] ) )
    $input->class($options['input_class']);

echo $input->display();

?>
<div class="bf-sorter-groups-container">
    <ul id="bf-sorter-group-<?php echo esc_attr( $options['id'] ); ?>" class="bf-vc-sorter-list bf-vc-sorter-checkbox-list bf-sorter-<?php echo esc_attr( $options['id'] ); ?>" >
        <?php
        // Options That Saved Before
        foreach( $selected_items as $item_id => $item ) {
            ?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $options['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>" class="<?php echo isset( $options['options'][$item_id]['css-class'] ) ? esc_attr( $options['options'][$item_id]['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?> checked-item" style="<?php echo isset( $options['options'][$item_id]['css'] ) ? $options['options'][$item_id]['css'] : ''; ?>">
                <label>
                    <input name="<?php echo esc_attr( $item_id ); ?>" value="<?php echo esc_attr( $item_id ); ?>" type="checkbox" checked="checked" />
                    <?php echo $options['options'][$item_id]['label']; ?>
                </label>
            </li>
            <?php

	        unset( $options['options'][$item_id] ) ;

        }

        // Options That Not Saved but are Active
        foreach( $options['options'] as $item_id => $item ) {

            // Skip Disabled Items
            if( isset( $item['css-class'] ) && strpos( $item['css-class'], 'active-item' ) === false ) continue;
            ?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $options['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>" class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?>" style="<?php echo isset( $item['css'] ) ? $item['css'] : ''; ?>">
                <label>
                    <input name="<?php echo esc_attr( $item_id ); ?>" value="<?php echo esc_attr( $item_id ); ?>" type="checkbox" <?php echo $check_all ? ' checked="checked" ' : ''; ?>/>
                    <?php echo is_array( $item) ? $item['label'] : $item; ?>
                </label>
            </li>
            <?php

            unset( $options['options'][$item_id] ) ;
        }

        // Disable Items
        foreach( $options['options'] as $item_id => $item ) { ?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $options['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>" class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?>" style="<?php echo isset( $item['css'] ) ? $item['css'] : ''; ?>">
                <label>
                    <input name="<?php echo esc_attr( $item_id ); ?>" value="<?php echo esc_attr( $item_id ); ?>" type="checkbox" disabled />
                    <?php echo is_array( $item) ? $item['label'] : $item; ?>
                </label>
            </li>
        <?php
        }

        ?>
    </ul>
</div>