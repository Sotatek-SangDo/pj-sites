<?php

/**
 * BetterMag WooCommerce Compatibility
 */
class BM_WooCommerce extends BF_WooCommerce{


    function __construct(){

        parent::__construct();

        /*
         * Hook in on activation
         */
        global $pagenow;

        if( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
            add_action( 'init', array($this, 'image_sizing'), 1);
        }
    }

    function init(){

        parent::init();

        // Number of columns on listing?
        add_filter( 'loop_shop_columns', array( $this, 'loop_columns') );

        add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'add_to_cart_text') );

        add_filter( 'loop_shop_per_page', array( $this, 'loop_shop_per_page' ), 20 );
    }

    public function loop_shop_per_page( $col ){
        return Better_Mag::get_option( 'shop_posts_per_page' );
    }

    /**
     * Filter Callback: Used for adding shopping cart icon to add to cart button
     *
     * @param $text
     * @return string
     */
    public function add_to_cart_text( $text ){
        return '<i class="fa fa-shopping-cart"></i> ' . $text;
    }

    /**
     * Setup image sizes for WooCommerce
     */
    public function image_sizing(){

        update_option( 'shop_catalog_image_size', array(
            'width' 	=> '300',
            'height'	=> '300',
            'crop'		=> 1
        ));

        update_option( 'shop_single_image_size', array(
            'width' 	=> '600',
            'height'	=> '600',
            'crop'		=> 1
        ));

        update_option( 'shop_thumbnail_image_size', array(
            'width' 	=> '180',
            'height'	=> '180',
            'crop'		=> 1
        ));

    }


    /**
     * Specifying Loop columns
     *
     * @return int
     */
    public function loop_columns(){

        if( self::current_sidebar_layout() )
            return 3;
        else
            return 4;

    }


    /**
     * Action callback: Add WooCommerce assets
     */
    public function register_assets(){

        wp_enqueue_style( 'better-mag-woocommerce', get_template_directory_uri()  . '/css/woocommerce.css');

        if( is_rtl() ){
            wp_enqueue_style( 'better-mag-woocommerce-rtl', get_template_directory_uri()  . '/css/woocommerce-rtl.css');
        }

    }


    /**
     * Used for specifying shop page have sidebar or not
     *
     * @return bool
     */
    public function have_sidebar(){

        return self::current_sidebar_layout();

    }

    /**
     * Used for retiveing shop page sidebar
     *
     * @param string $layout
     * @return bool
     */
    public function is_sidebar_layout( $layout = '' ){

        return self::current_sidebar_layout() == $layout ;

    }

    /**
     * Used for retrieving shop page sidebar
     *
     * @return string
     */
    public function current_sidebar_layout( ){

        // current page is single post or page
        if( is_singular( 'product' ) ){

            // custom field values saved before
            if( false != ( $_default_layout = get_post_meta( get_the_ID(), '_default_sidebar_layout', true ) )){

                switch( $_default_layout ){

                    // Default settings from theme options
                    case 'default':
                        if( Better_Mag::get_option( 'shop_sidebar_layout' ) == 'no-sidebar' )
                            return false;
                        else
                            return Better_Mag::get_option( 'shop_sidebar_layout' );

                        break;

                    // No Sidebar
                    case 'no-sidebar':
                        return false;

                        break;

                    // Right And Left Side Sidebars
                    default:
                        return $_default_layout;

                }

            }

        }

        if( Better_Mag::get_option( 'shop_sidebar_layout' ) == 'no-sidebar' )
            return false;
        else
            return Better_Mag::get_option( 'shop_sidebar_layout' );

    }


    /**
     * Used for generating li for menu
     *
     * @param bool $echo
     * @return bool
     */
    public function get_menu_icon( $echo = true ){
        global $woocommerce;

        ob_start();
        ?>
        <li class="random-post shop-cart-item menu-title-hide alignright">

            <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="cart-link"><i class="fa fa-shopping-cart"></i> <span class="hidden"><?php Better_Translation()->_echo( 'woo_cart' ); ?></span>
                <span class="better-custom-badge "><?php echo $woocommerce->cart->cart_contents_count ? $woocommerce->cart->cart_contents_count : 0;?></span>
            </a>
            <?php

            the_widget( 'WC_Widget_Cart', 'title= ',
                array(
                    'before_widget' => '<div class="mega-menu cart-widget widget_shopping_cart">',
                    'before_title'  => '',
                    'after_title'  => '',
                )
            );

            ?>
        </li>

        <?php
        $output = ob_get_clean();

        if( $echo )
            echo $output;
        else
            return $echo;

    }

}