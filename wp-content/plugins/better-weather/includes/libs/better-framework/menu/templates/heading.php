<?php
$parent_only= isset( $option['parent_only'] ) ? ' data-parent_only="true"' : '';

// Block Classes
$block_class= array();
if(isset($options['width']) && !empty($options['width']))
    $block_class[] = 'description-'.$options['width'];
else
    $block_class[] = 'description-wide';

if(isset($options['class']) && !empty($options['class']))
    $block_class[] = $options['class'];

$block_class[] = 'bf-field-'. $options['id'];

$block_class = apply_filters( 'better-framework/menu/fields-class' , $block_class );

if( !isset($options['id']) )
    $options['id'] = '';

?>
<div class="bf-section-container bf-menus bf-clearfix <?php echo esc_attr( implode( ' ' , $block_class ) ); ?>" <?php echo $parent_only; ?>>
    <div class="bf-section-heading bf-clearfix" data-id="<?php echo esc_attr( $options['id'] ); ?>"  id="<?php echo esc_attr( $options['id'] ); ?>">
        <div class="bf-section-heading-title bf-clearfix">
            <h3><?php echo $options['name']; ?></h3>
        </div>
        <?php if ( !empty( $options['desc'] ) ) { ?>
            <div class="bf-section-heading-desc bf-clearfix"><?php echo $options['desc']; ?></div>
        <?php } ?>
    </div>
</div>
