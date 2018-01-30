<?php

/**
 * WooCommerce Main Template Catch-All
 */

get_header();

?>
<div class="row main-section woo-commerce-main-section">
    <?php
    if( Better_Mag::wooCommerce()->is_sidebar_layout( 'left' ) ) Better_Mag::get_sidebar();
    ?>
    <div class="<?php echo Better_Mag::wooCommerce()->current_sidebar_layout() ? 'col-lg-8 col-md-8 col-sm-8 col-xs-12 with-sidebar content-column' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 no-sidebar '; ?>">
        <?php

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();

                wc_get_template_part( 'content', 'single-product' );

            endwhile;

        } else {

            if ( apply_filters( 'woocommerce_show_page_title', true ) ) :

                Better_Mag::generator()->blocks()->get_page_title( woocommerce_page_title( false ) );

            endif;

            do_action( 'woocommerce_archive_description' );

            if ( have_posts() ) :

                do_action('woocommerce_before_shop_loop');

                woocommerce_product_loop_start();

                woocommerce_product_subcategories();

                while ( have_posts() ) : the_post();

                    wc_get_template_part( 'content', 'product' );

                endwhile; // end of the loop.

                woocommerce_product_loop_end();

                do_action('woocommerce_after_shop_loop');

            elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) :

                wc_get_template( 'loop/no-products-found.php' );
            endif;

        }

        ?>
    </div>
    <?php if( Better_Mag::wooCommerce()->is_sidebar_layout( 'right' ) ) Better_Mag::get_sidebar(); ?>
</div>
<?php

get_footer(); ?>