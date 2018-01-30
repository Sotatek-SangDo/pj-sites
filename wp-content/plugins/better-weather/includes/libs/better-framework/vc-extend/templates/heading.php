<div class="bf-section-container  vc-input bf-clearfix">
    <div class="bf-section-heading bf-clearfix" data-id="<?php echo esc_attr( $options['id'] ); ?>"  id="<?php echo esc_attr( $options['id'] ); ?>">
        <div class="bf-section-heading-title bf-clearfix">
            <h4><?php echo $options['title']; ?></h4>
        </div>
        <?php if ( !empty( $options['desc'] ) ) { ?>
            <div class="bf-section-heading-desc bf-clearfix"><?php echo $options['desc']; ?></div>
        <?php } ?>
    </div>
</div>