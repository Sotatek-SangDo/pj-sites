<?php
$parent_only= isset( $option['parent_only'] ) ? ' data-parent_only="true"' : '';

// Block width
$block_class= array();
if(isset($options['width']) && !empty($options['width']))
    $block_class[] = 'description-'.$options['width'];
else
    $block_class[] = 'description-wide';

$block_class[] = 'bf-field-'. $options['id'];

// Block Classes
if(isset($options['class']) && !empty($options['class']))
    $block_class[] = $options['class'];

$block_class = apply_filters( 'better-framework/menu/fields-class' , $block_class );

?>
<div class="bf-menu-custom-field better-custom-field-<?php echo esc_attr( $options['type'] ); ?> description <?php echo esc_attr( implode( ' ' , $block_class ) ); ?>" <?php $parent_only; ?> >
    <label for="<?php echo esc_attr( $options['input_name'] ); ?>">
        <div class="bf-section-container bf-menus bf-clearfix"><label><?php echo $input; ?> <span class="better-custom-field-label"><?php echo $options['name']; ?></span></label></div>
    </label>
</div>