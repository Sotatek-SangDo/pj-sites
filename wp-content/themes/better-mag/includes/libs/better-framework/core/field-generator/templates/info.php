<div class="bf-section-container bf-clearfix">
    <div class="bf-section-info <?php echo esc_attr( $options['info-type'] ). ' ' . esc_attr( $options['state'] ); ?> bf-clearfix">
        <div class="bf-section-info-title bf-clearfix">
            <h3><?php echo $options['name']; ?></h3>
        </div>
        <div class="<?php echo esc_attr( $controls_classes ); ?>  bf-clearfix">
            <?php echo $input; ?>
        </div>
    </div>
</div>