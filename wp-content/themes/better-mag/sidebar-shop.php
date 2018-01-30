<?php do_action( 'better-mag/sidebar/before'); ?>
<aside  <?php better_attr( 'sidebar', 'col-lg-4 col-md-4 col-sm-4 col-xs-12 main-sidebar ' . ( Better_Mag::wooCommerce()->is_sidebar_layout( 'right' ) == 'right' ? 'vertical-left-line' : 'vertical-right-line' ), 'woocommerce-sidebar' ); ?>>
    <?php do_action( 'better-mag/sidebar/start'); ?>
    <?php if( ! dynamic_sidebar( 'woocommerce-sidebar' ) ) : ?>
        <div class="primary-sidebar-widget widget">
            <?php Better_Mag::generator()->blocks()->get_block_title( Better_Translation()->_get( 'widget_sample_title' ) ); ?>
            <p><?php Better_Translation()->_echo( 'widget_nothing_yet' ); ?></p>
        </div>
    <?php endif; ?>
    <?php do_action( 'better-mag/sidebar/end'); ?>
</aside>
<?php do_action( 'better-mag/sidebar/after'); ?>