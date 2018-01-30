<?php

class BM_WC_Cart_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm-wc-cart';

        $this->widget_id = 'bm wc cart';

        $_options = array(
            'defaults' => array(

            ),

            'have_widget'       => true,
            'have_vc_add_on'    => false,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

    }

    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        global $woocommerce;

        if( is_cart() )
            return '';

        ob_start();

        ?>
        <div class="bf-shortcode bm-wc-cart <?php echo $woocommerce->cart->cart_contents_count ? '' : 'empty-cart' ; ?>">
            <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="cart-link">
                <span class="total-items <?php echo $woocommerce->cart->cart_contents_count ? '' : 'empty' ; ?>"><?php echo $woocommerce->cart->cart_contents_count ? $woocommerce->cart->cart_contents_count : 0 ; ?></span>
                <?php Better_Translation()->_echo( 'woo_shopping_cart' ); ?>
                <i class="fa fa-shopping-cart"></i>
            </a>
            <div class="items-list">
                <?php

                the_widget( 'WC_Widget_Cart', 'title= ',
                    array(
                        'before_title'  => '',
                        'after_title'  => '',
                    )
                );
                ?>
            </div>
        </div>
        <?php

        return ob_get_clean();

    }

}