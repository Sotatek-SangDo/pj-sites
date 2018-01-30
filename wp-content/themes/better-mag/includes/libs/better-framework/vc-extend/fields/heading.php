<div class="bf-section-container bf-clearfix">
    <div class="bf-section-heading bf-clearfix" data-id="<?php echo esc_attr( $options['id'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>">
        <div class="bf-section-heading-title bf-clearfix">
            <h3><?php echo $options['name']; ?></h3>
        </div>
        <?php if ( !empty( $options['desc'] ) ) { ?>
            <div class="bf-section-heading-desc bf-clearfix"><?php echo $options['desc']; ?></div>
        <?php } ?>
    </div>
</div>