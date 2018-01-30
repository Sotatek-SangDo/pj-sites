<?php

/**
 * BetterMag Theme
 */
class Better_Mag {


    /**
     * Contains key of theme main option panel
     *
     * @var string
     */
    public static $theme_panel_key = '__better_mag__theme_options';


    /**
     * Inner array of objects live instances like generator
     *
     * @var array
     */
    protected static $instances = array();


    /**
     *
     */
    function __construct(){

        // Performs the Bf setup
        add_action( 'better-framework/after_setup', array( $this, 'theme_init' )  );

        // Clears BF caches
        add_action( 'after_switch_theme', array( $this, 'after_theme_switch' ) );
        add_action( 'switch_theme', array( $this, 'after_theme_switch' ) );

    }


    /**
     * clears last BF caches for avoiding conflict
     */
    function after_theme_switch(){

        // Clears BF transients for preventing of happening any problem
        delete_transient( '__better_framework__widgets_css' );
        delete_transient( '__better_framework__panel_css' );
        delete_transient( '__better_framework__menu_css' );
        delete_transient( '__better_framework__terms_css' );
        delete_transient( '__better_framework__final_fe_css' );
        delete_transient( '__better_framework__final_fe_css_version' );
        delete_transient( '__better_framework__backend_css' );

        // Delete all pages css transients
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE %s", '_bf_post_css_%' ) );

    }


    /**
     * Used for retrieving WooCommerce class of BetterMag
     *
     * @return BM_WooCommerce | false
     */
    public static function wooCommerce(){

        if( ! function_exists('is_woocommerce') ){
            return false;
        }

        if ( isset(self::$instances['woocommerce']) ) {
            return self::$instances['woocommerce'];
        }

        require_once BETTER_MAG_PATH . 'includes/class-bm-woocommerce.php';

        $generator = apply_filters( 'better-mag/woocommerce', 'BM_WooCommerce' );

        // if filtered class not exists or not 1 child of BM_WooCommerce class
        if( ! class_exists($generator) || ! is_subclass_of( $generator, 'BM_WooCommerce' ) )
            $generator = 'BM_WooCommerce';

        self::$instances['woocommerce'] = new $generator;
        return self::$instances['woocommerce'];

    }


    /**
     * Used for retrieving bbPress class of BetterMag
     *
     * @return BM_bbPress | false
     */
    public static function bbPress(){

        if( ! class_exists('bbpress') ){
            return false;
        }

        if( isset(self::$instances['bbpress']) ){
            return self::$instances['bbpress'];
        }

        require_once BETTER_MAG_PATH . 'includes/class-bm-bbpress.php';

        $generator = apply_filters( 'better-mag/bbpress', 'BM_bbPress' );

        // if filtered class not exists or not 1 child of BM_bbPress class
        if( ! class_exists($generator) || ! is_subclass_of( $generator, 'BM_bbPress' ) )
            $generator = 'BM_bbPress';

        self::$instances['bbpress'] = new $generator;
        return self::$instances['bbpress'];

    }


    /**
     * Used for retrieving generator of BetterMag
     *
     * @return BM_Block_Generator
     */
    public static function generator(){

        if( isset( self::$instances['generator'] ) ) {
            return self::$instances['generator'];
        }

        $generator = apply_filters( 'better-mag/generator', 'BM_Block_Generator' );

        // if filtered class not exists or not 1 child of BM_Block_Generator class
        if( ! class_exists( $generator ) || ! is_subclass_of( $generator, 'BF_Block_Generator' ) )
            $generator = 'BM_Block_Generator';

        self::$instances['generator'] = new $generator;
        return self::$instances['generator'];

    }


    /**
     * Used for retrieving post meta
     *
     * uses BM_Posts
     *
     * @param null $key
     * @param bool $default
     * @param null $post_id
     * @return string
     */
    public static function get_meta( $key = null, $default = true, $post_id = null ){

        if( is_null( $post_id ) )
            $post_id = get_the_ID();

        return bf_get_post_meta( '_' . $key, $post_id );

    }


    /**
     * Used for printing post meta
     *
     * uses BM_Posts
     *
     * @param   null    $key
     * @param   bool    $deprecated
     * @return  string
     */
    public static function echo_meta( $key = null, $deprecated = true ){

        return apply_filters( 'better-mag/meta/' . $key, bf_get_post_meta( '_' . $key, get_the_ID() ) );

    }


    /**
     * Used for retrieving options simply and safely for next versions
     *
     * @param $option_key
     * @return mixed|null
     */
    public static function get_option( $option_key ){

        return bf_get_option( $option_key, self::$theme_panel_key );

    }


    /**
     * Used for printing options simply and safely for next versions
     *
     * @param $option_key
     * @return mixed|null
     */
    public static function echo_option( $option_key ){

        bf_echo_option( $option_key, self::$theme_panel_key );

    }


    /**
     * Used for handling functionality related to posts and pages
     *
     * @return  BM_Posts
     */
    public static function posts(){

        if ( isset(self::$instances['bm-posts']) ) {
            return self::$instances['bm-posts'];
        }

        $bm_posts = apply_filters( 'better-mag/posts', 'BM_Posts' );

        // if filtered class not exists or not 1 child of BM_Posts class
        if( ! class_exists( $bm_posts ) || ! is_subclass_of( $bm_posts, 'BM_Posts' ) )
            $bm_posts = 'BM_Posts';

        self::$instances['bm-posts'] = new $bm_posts;
        return self::$instances['bm-posts'];

    }


    /**
     * Usd for detect Better Reviews plugin is active or not
     *
     * @return bool
     */
    public static function is_review_active(){

        return function_exists( 'Better_Reviews' );

    }


    /**
     * Setup and recommend plugins
     */
    public function setup_plugins(){

        require_once BETTER_MAG_PATH . '/includes/libs/class-tgm-plugin-activation.php';

        $plugins = array(

            // http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=Better-Studio
            array(
                'name'      =>  'WPBakery Visual Composer',
                'slug'      =>  'js_composer',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/js_composer.zip',
                'required'  =>  true,
                'version'   =>  '4.12.1'
            ),

            // http://codecanyon.net/item/better-weather-wordpress-and-visual-composer-widget/7724257?ref=Better-Studio
            array(
                'name'      =>  'BetterWeather - Better Weather Widget!',
                'slug'      =>  'better-weather',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-weather.zip',
                'required'  =>  false,
                'version'   =>  '3.0.2'
            ),

            // http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=Better-Studio
            array(
                'name'      =>  'Slider Revolution',
                'slug'      =>  'revslider',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/revslider.zip',
                'required'  =>  false,
                'version'   =>  '5.2.6'
            ),

            array(
                'name'      =>  'BetterStudio Shortcodes',
                'slug'      =>  'betterstudio-shortcodes',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/betterstudio-shortcodes.zip',
                'required'  =>  false,
                'version'   =>  '1.1'
            ),

            array(
                'name'      =>  'Better Social Counter Widget',
                'slug'      =>  'better-social-counter',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-social-counter.zip',
                'required'  =>  false,
                'version'   =>  '1.4.8'
            ),

            array(
                'name'      =>  'Better News Ticker Widget',
                'slug'      =>  'better-news-ticker',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-news-ticker.zip',
                'required'  =>  false,
                'version'   =>  '1.0.2'
            ),

            array(
                'name'      =>  'Better Reviews',
                'slug'      =>  'better-reviews',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-reviews.zip',
                'required'  =>  false,
                'version'   =>  '1.0.3'
            ),

            array(
                'name'      =>  'Better Google Custom Search',
                'slug'      =>  'better-google-custom-search',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-google-custom-search.zip',
                'required'  =>  false,
                'version'   =>  '1.0.0'
            ),

            array(
                'name'      =>  'Better Post Views',
                'slug'      =>  'better-post-views',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-post-views.zip',
                'required'  =>  false,
                'version'   =>  '1.0.0'
            ),

            array(
                'name'      =>  'Better Disqus Comments',
                'slug'      =>  'better-disqus-comments',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-disqus-comments.zip',
                'required'  =>  false,
                'version'   =>  '1.0.0'
            ),

            array(
                'name'      =>  'Better Facebook Comments',
                'slug'      =>  'better-facebook-comments',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-facebook-comments.zip',
                'required'  =>  false,
                'version'   =>  '1.1'
            ),

            array(
                'name'      =>  'Better Ads Manager',
                'slug'      =>  'better-adsmanager',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-adsmanager.zip',
                'required'  =>  false,
                'version'   =>  '1.3.1'
            ),

            array(
                'name'      =>  'Better Sticky Sidebar',
                'slug'      =>  'better-sticky-sidebar',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-sticky-sidebar.zip',
                'required'  =>  false,
                'version'   =>  '1.1.1'
            ),

            array(
                'name'      =>  'Better Custom 404 Template',
                'slug'      =>  'better-custom-404-template',
                'source'    =>  get_template_directory_uri() . '/includes/libs/plugins/better-custom-404-template.zip',
                'required'  =>  false,
                'version'   =>  '1.0.0'
            ),

            // https://wordpress.org/plugins/custom-sidebars/changelog/
            array(
                'name'      =>  'Custom sidebars',
                'slug'      =>  'custom-sidebars',
                'required'  =>  false,
                'version'   =>  '2.1.0.2'
            ),

            // https://wordpress.org/plugins/contact-form-7/changelog/
            array(
                'name'      =>  'Contact Form 7',
                'slug'      =>  'contact-form-7',
                'required'  =>  false,
                'version'   =>  '4.3'
            ),

        );

        $config = array(
            'is_automatic' => true,
        );

        tgmpa( $plugins, $config );

        if( function_exists('vc_set_as_theme') ) vc_set_as_theme();
    }


    function theme_init() {

        // Setup plugins before WP and BF init
        $this->setup_plugins();

        // Include Helper
        require_once BETTER_MAG_PATH . 'includes/class-bm-helper.php';

        // include main generator file
        require_once BETTER_MAG_PATH . 'includes/class-bm-block-generator.php';
        require_once BETTER_MAG_PATH . 'includes/class-bm-blocks.php';

        // Include functionality for posts
        require_once BETTER_MAG_PATH . 'includes/class-bm-posts.php';

        // Init WooCommerce Support
        self::wooCommerce();

        // Init bbPress Support
        self::bbPress();

        /*
		 * Enqueue assets (css, js)
		 */
        add_action( 'wp_enqueue_scripts', array($this, 'register_assets') );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

        /*
		 * Featured images settings
		 */
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 90, 60, array( 'center', 'center' ));                      // Image Thumbnail Size

        add_image_size( 'bigger-thumbnail', 110,    80,     array( 'center', 'center' ) );  // Main Post Image In Full Width

        add_image_size( 'main-full',        1140,   530,    array( 'center', 'center' ) );  // Main Post Image In Full Width

        add_image_size( 'main-post',        750,    350,    array( 'center', 'center' ) );  // Post Image In Normal ( With Sidebar )
        add_image_size( 'main-block',       360,    200,    array( 'center', 'center' ) );  // Main Post Image For Block Listings

        add_image_size( 'slider-1',         360,    165,    array( 'center', 'center' ) );
        add_image_size( 'slider-2',         360,    195,    array( 'center', 'center' ) );
        add_image_size( 'slider-3',         165,    135,    array( 'center', 'center' ) );
        add_image_size( 'slider-4',         263,    350,    array( 'center', 'center' ) );
        add_image_size( 'slider-5',         555,    350,    array( 'center', 'center' ) );
        add_image_size( 'slider-6',         1140,   350,    array( 'center', 'center' ) );

        add_image_size( 'bgs-375',          0,      375,    true                        );


        add_theme_support( 'title-tag' );

        // Backwards Compatibility
        if( ! function_exists( '_wp_render_title_tag' ) ) {
            add_action( 'wp_head', array( $this, 'better_theme_slug_render_title' ) );
        }


        /*
         * Post formats
         */
        add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio' ) );

        /*
         * This feature enables post and comment RSS feed links to head.
         */
        add_theme_support( 'automatic-feed-links' );

        /*
         * Register menus
         */
        register_nav_menu( 'main-menu', __( 'Main Navigation', 'better-studio' ) );


        // in 3.5 content_width removed, add it for oebmed
        global $content_width;

        if ( ! isset( $content_width ) )
            $content_width = 1170;

        // Add Ability to setting short code in text widget
        add_filter( 'widget_text', 'do_shortcode' );

        // Implements editor styling
        add_editor_style();

        // Add filters to generating custom menus
        add_filter( 'better-framework/menu/mega/end_lvl', array($this, 'generate_better_menu'));

        // enqueue in header
        add_action( 'wp_head', array( $this, 'wp_head' ));

        // enqueue in footer
        add_action( 'wp_footer', array( $this, 'wp_footer' ));

        // add custom classes to body
        add_filter( 'body_class' , array( $this, 'filter_body_class' ) );

        // add custom classes to post class
        add_filter( 'post_class' , array( $this, 'filter_post_class' ), 10, 3 );

        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', array( $this , 'admin_enqueue' ) );

        // Used for adding orderby rand to WP_User_Query
        add_action( 'pre_user_query', array( $this , 'action_pre_user_query' ) );

        // config Better Sticky Sidebar
        add_action( 'better-sticky-sidebar/config', array( $this , 'better_sticky_sidebar_config' ), 10, 2 );

        /*
         * Register Sidebars
         */
        $this->register_sidebars();

        // Setup theme update
        if( is_admin() && $this->get_option( 'themeforest_user_name' ) && $this->get_option( 'themeforest_api_key' ) ){

            require_once  BETTER_MAG_PATH . 'includes/libs/better-studio-theme-updater/init.php';
            add_filter( 'better-studio-theme-updater/update-data', array( $this, 'filter_theme_update_manager_data' ) );

        }

        // Theme options into admin bar
        add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 1000);

        // Initialize Better Studio Rebuild Thumbnails
        if( is_admin() ){

            require BETTER_MAG_PATH . 'includes/libs/better-studio-rebuild-thumbnails/class-better-studio-rebuild-thumbnails.php';
            new Better_Studio_Rebuild_Thumbnails( array(
                'dir-uri'   => get_template_directory_uri() . '/includes/libs/better-studio-rebuild-thumbnails/'
            ) );

            require BETTER_MAG_PATH . 'includes/demo-content-importer/class-bm-content-importer.php';
            new BM_Content_Importer( array() );
        }

        // Filter WP_Query
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

        // BetterMag compatibility with Better Facebook Comments
        add_filter( 'better-facebook-comments/js/global-vars', array( $this, 'better_facebook_comments_vars' ) );

        // Avatar
        add_filter( 'get_avatar', array( $this, 'get_avatar' ), 10, 5 );

        // Main navigation Bigger style
        add_filter( 'better-framework/menu/show-parent-desc', array( $this, 'better_menu_show_parent_desc' ) );

    }


    // Backwards Compatibility For Theme title-tag Feature
    function better_theme_slug_render_title() {
        ?>
        <title><?php wp_title( '|', TRUE, 'right' ); ?></title>
        <?php
    }


    /**
     * Callback: Configuration for big menu style
     *
     * Filter: better-framework/menu/show-parent-desc
     *
     * @param $show
     * @return bool
     */
    function better_menu_show_parent_desc( $show ){

        switch( $this->get_option( 'main_menu_style' ) ){

            case 'normal':
            case 'normal-center':
                $show = false;
                break;

            case 'large':
            case 'large-center':
                $show = true;
                break;

        }

        return $show;
    }


    /**
     * Action Callback: Used for adding theme options into admin bar
     */
    function admin_bar_menu(){

        global $wp_admin_bar;

        if( ! is_super_admin() || ! is_admin_bar_showing() ){
            return;
        }

        // Theme Options
        $wp_admin_bar->add_menu(array(
            'id'        => 'bs-theme-options',
            'parent'    => 'site-name',
            'title'     => __( 'Theme Options', 'better-studio' ),
            'href'      => admin_url( 'admin.php?page=better-studio/better-mag' ),
        ));

        // Theme Translation
        $wp_admin_bar->add_menu( array(
            'id'        => 'bs-translate-theme',
            'parent'    => 'site-name',
            'title'     => __( 'Translate Theme', 'better-studio' ),
            'href'      => admin_url( 'admin.php?page=better-studio/translations/better-mag-translation' )
        ));
    }


    /**
     * Enqueue css and js files
     *
     * Action Callback: wp_enqueue_scripts
     *
     */
    function register_assets(){

        // jquery and bootstrap
        wp_enqueue_script( 'better-mag-libs', get_template_directory_uri() . '/js/better-mag-libs.min.js', array( 'jquery' ), BF()->theme()->get( 'Version' ), true );

        // Element Query
        BF()->assets_manager()->enqueue_script( 'element-query' );

        // PrettyPhoto
        if( Better_Mag::get_option( 'lightbox_is_enable' ) ){
            BF()->assets_manager()->enqueue_script( 'pretty-photo' );
            BF()->assets_manager()->enqueue_style( 'pretty-photo' );
        }

        // BetterMag core scripts
        wp_enqueue_script( 'better-mag', get_template_directory_uri() . '/js/better-mag.js', array( 'jquery' ), BF()->theme()->get( 'Version' ), true );

        wp_localize_script(
            'better-mag',
            'better_mag_vars',
            apply_filters(
                'better-mag/js/global-vars',
                array(
                    'text_navigation'       =>  Better_Translation()->_get( 'navigation' ),

                    'main_slider'           =>  array(
                        'animation'         => self::get_option( 'better_slider_animation' ),
                        'slideshowSpeed'    => self::get_option( 'better_slider_slideshowSpeed' ),
                        'animationSpeed'    => self::get_option( 'better_slider_animationSpeed' ),
                    )
                )
            )
        );

        // Bootstrap style
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' , array(), BF()->theme()->get( 'Version' ) );

        // Fontawesome
        BF()->assets_manager()->enqueue_style( 'fontawesome' );

        // If a child theme is active, add the parent theme's style. this is good for performance and cache.
        if( is_child_theme() ){
            wp_enqueue_style( 'better-mag', trailingslashit( get_template_directory_uri() ) . 'style.css', array(), BF()->theme()->get( 'Version' ) );
            // adds child theme version to the end of url of child theme style file
            wp_enqueue_style( 'better-mag-child', get_stylesheet_uri(), array(), BF()->theme( false, true, false )->get('Version') );
        }
        // BetterMage core style
        else{
            wp_enqueue_style( 'better-mag', get_stylesheet_uri(), array(), BF()->theme()->get('Version') );
        }

        if( is_rtl() ){
            wp_enqueue_style( 'better-mag-rtl', get_template_directory_uri()  . '/css/rtl.css', array( 'better-mag' ), BF()->theme()->get( 'Version' ) );
        }

        // BetterMag Languages
        $lang = bf_get_current_language_option_code();

        // BetterMag Skins
        if( ( $style = get_option( self::$theme_panel_key . $lang . '_current_style' ) ) != 'default' ){
            wp_enqueue_style( 'better-mag-style-' . $style . $lang, get_template_directory_uri() . '/css/style-' . $style . '.css', array( 'better-mag' ), BF()->theme()->get( 'Version' ) );
        }

        if( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
            wp_enqueue_script( 'comment-reply' );
        }

    }


    /**
     * Registers dynamic sidebars
     */
    function register_sidebars(){

        register_sidebar( array(
            'name'          =>  __( 'Main Sidebar', 'better-studio' ),
            'id'            =>  'primary-sidebar',
            'description'   =>  __( 'Widgets in this area will be shown in the default sidebar.', 'better-studio' ),
            'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
            'after_title'   =>  '</span></h4>',
            'before_widget' =>  '<div id="%1$s" class="primary-sidebar-widget widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );

        register_sidebar( array(
            'name'          =>  __( 'Aside Logo', 'better-studio' ),
            'id'            =>  'aside-logo',
            'description'   =>  __( ' Widgets in this area will shown in logo aside. Please place only one line widgets.', 'better-studio'),
            'before_title'  =>  '',
            'after_title'   =>  '',
            'before_widget' =>  '<div  id="%1$s" class="aside-logo-widget widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );

        register_sidebar( array(
            'name'          =>  __( 'Top Bar - Left Column', 'better-studio' ),
            'id'            =>  'top-bar-left',
            'description'   =>  __('Please place only one line widgets.', 'better-studio'),
            'before_title'  =>  '',
            'after_title'   =>  '',
            'before_widget' =>  '<div  id="%1$s" class="top-bar-widget widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );

        register_sidebar( array(
            'name'          =>  __('Top Bar - Right Column', 'better-studio'),
            'id'            =>  'top-bar-right',
            'description'   =>  __('Please place only one line widgets.', 'better-studio'),
            'before_title'  =>  '',
            'after_title'   =>  '',
            'before_widget' =>  '<div  id="%1$s" class="top-bar-widget widget %2$s">',
            'after_widget'  =>  '</div>'
        ));


        // Footer Larger Sidebars
        register_sidebar( array(
            'name'          =>  __( 'Larger Footer - Column 1', 'better-studio'),
            'id'            =>  'footer-column-1',
            'description'   =>   __( 'Widgets in this area will be shown in the footer larger column 1.', 'better-studio' ),
            'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
            'after_title'   =>  '</span></h4>',
            'before_widget' =>  '<div id="%1$s" class="footer-larger-widget larger-column-1 widget %2$s">',
            'after_widget'  =>  '</div>'
        ));

        register_sidebar( array(
            'name'          =>  __( 'Larger Footer - Column 2', 'better-studio'),
            'id'            =>  'footer-column-2',
            'description'   =>   __( 'Widgets in this area will be shown in the footer larger column 2.', 'better-studio' ),
            'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
            'after_title'   =>  '</span></h4>',
            'before_widget' =>  '<div id="%1$s" class="footer-larger-widget larger-column-2 widget %2$s">',
            'after_widget'  =>  '</div>'
        ));

        register_sidebar( array(
            'name'          =>  __( 'Larger Footer - Column 3', 'better-studio'),
            'id'            =>  'footer-column-3',
            'description'   =>   __( 'Widgets in this area will be shown in the footer larger column 3.', 'better-studio' ),
            'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
            'after_title'   =>  '</span></h4>',
            'before_widget' =>  '<div id="%1$s" class="footer-larger-widget larger-column-3 widget %2$s">',
            'after_widget'  =>  '</div>'
        ));

        register_sidebar( array(
            'name'          =>  __( 'Larger Footer - Column 4', 'better-studio'),
            'id'            =>  'footer-column-4',
            'description'   =>   __( 'Widgets in this area will be shown in the footer larger column 4.', 'better-studio' ),
            'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
            'after_title'   =>  '</span></h4>',
            'before_widget' =>  '<div id="%1$s" class="footer-larger-widget larger-column-4 widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );


        // Footer Lower Sidebars
        register_sidebar( array(
            'name'          =>  __( 'Lower Footer - Left Column', 'better-studio'),
            'id'            =>  'footer-lower-left-column',
            'description'   =>  __('Please place only one line widgets.', 'better-studio'),
            'before_title'  =>  '',
            'after_title'   =>  '',
            'before_widget' =>  '<div id="%1$s" class="footer-lower-widget lower-left-column widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );

        register_sidebar( array(
            'name'          =>  __( 'Lower Footer - Right Column', 'better-studio'),
            'id'            =>  'footer-lower-right-column',
            'description'   =>  __('Please place only one line widgets.', 'better-studio'),
            'before_title'  =>  '',
            'after_title'   =>  '',
            'before_widget' =>  '<div id="%1$s" class="footer-lower-widget lower-right-column widget %2$s">',
            'after_widget'  =>  '</div>'
        ) );

    }


    /**
     *  Enqueue anything in header
     */
    function wp_head(){

        // Add custom css
        $this->add_panel_custom_css();

        // Favicon
        $favicon_16_16 = Better_Mag::get_option( 'favicon_16_16' );
        if( $favicon_16_16 ) {
            ?><link rel="shortcut icon" href="<?php echo esc_url( $favicon_16_16 ); ?>"><?php
        }

        $favicon_57_57 = Better_Mag::get_option( 'favicon_57_57' );
        if( $favicon_57_57 ) {
            ?><link rel="apple-touch-icon" href="<?php echo esc_url( $favicon_57_57 ); ?>"><?php
        }

        $favicon_114_114 = Better_Mag::get_option( 'favicon_114_114' );
        if( $favicon_114_114 ) {
            ?><link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( $favicon_114_114 ); ?>"><?php
        }

        $favicon_72_72 = Better_Mag::get_option( 'favicon_72_72' );
        if( $favicon_72_72 ) {
            ?><link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url( $favicon_72_72 ); ?>"><?php
        }

        $favicon_144_144 = Better_Mag::get_option( 'favicon_144_144' );
        if( $favicon_144_144 ) {
            ?><link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url( $favicon_144_144 ); ?>"><?php
        }

        // Header HTML Code
        echo Better_Mag::get_option( 'custom_header_code' );

    }


    /**
     * Used for adding theme panels custom css to page
     */
    function add_panel_custom_css(){

        // Custom CSS Code
        $custom_css_code = Better_Mag::get_option( 'custom_css_code' );
        if( $custom_css_code )
            BF()->assets_manager()->add_css( $custom_css_code, true );

        $custom_css_desktop_code = Better_Mag::get_option( 'custom_css_desktop_code' );
        if( $custom_css_desktop_code )
            BF()->assets_manager()->add_css( '/* responsive monitor */ @media(min-width: 1200px){ ' . $custom_css_desktop_code . '}', true );

        $custom_css_ipad_landscape_code = Better_Mag::get_option( 'custom_css_ipad_landscape_code' );
        if( $custom_css_ipad_landscape_code )
            BF()->assets_manager()->add_css( '/* responsive landscape tablet */ @media(min-width: 1019px) and (max-width: 1199px){ ' .$custom_css_ipad_landscape_code . '}', true );

        $custom_css_ipad_portrait_code = Better_Mag::get_option( 'custom_css_ipad_portrait_code' );
        if( $custom_css_ipad_portrait_code )
            BF()->assets_manager()->add_css( '/* responsive portrait tablet */ @media(min-width: 768px) and (max-width: 1018px){' . $custom_css_ipad_portrait_code . '}', true );

        $custom_css_phones_code = Better_Mag::get_option( 'custom_css_phones_code' );
        if( $custom_css_phones_code )
            BF()->assets_manager()->add_css( '/* responsive phone */ @media(max-width: 767px){ ' . $custom_css_phones_code . ' }', true );


        // Custom CSS For Singulars
        if( is_singular() ){

            $custom_css_code = Better_Mag::get_meta( 'custom_css_code' );
            if( $custom_css_code )
                BF()->assets_manager()->add_css( $custom_css_code, true );

            $custom_css_desktop_code = Better_Mag::get_meta( 'custom_css_desktop_code' );
            if( $custom_css_desktop_code )
                BF()->assets_manager()->add_css( '/* responsive monitor */ @media(min-width: 1200px){ ' . $custom_css_desktop_code . '}', true );

            $custom_css_ipad_landscape_code = Better_Mag::get_meta( 'custom_css_ipad_landscape_code' );
            if( $custom_css_ipad_landscape_code )
                BF()->assets_manager()->add_css( '/* responsive landscape tablet */ @media(min-width: 1019px) and (max-width: 1199px){ ' .$custom_css_ipad_landscape_code . '}', true );

            $custom_css_ipad_portrait_code = Better_Mag::get_meta( 'custom_css_ipad_portrait_code' );
            if( $custom_css_ipad_portrait_code )
                BF()->assets_manager()->add_css( '/* responsive portrait tablet */ @media(min-width: 768px) and (max-width: 1018px){' . $custom_css_ipad_portrait_code . '}', true );

            $custom_css_phones_code = Better_Mag::get_meta( 'custom_css_phones_code' );
            if( $custom_css_phones_code )
                BF()->assets_manager()->add_css( '/* responsive phone */ @media(max-width: 767px){ ' . $custom_css_phones_code . ' }', true );

        }
        // Custom CSS for categories & Tags
        elseif( is_tag() || is_category() ){

            if( is_category() ){
                $term_id = get_query_var('cat');
            }else{
                $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
                $term_id = $tag->term_id;
            }

            $custom_css_code = BF()->taxonomy_meta()->get_term_meta( $term_id, 'custom_css_code', '' );
            if( $custom_css_code )
                BF()->assets_manager()->add_css( $custom_css_code, true );

            $custom_css_desktop_code = BF()->taxonomy_meta()->get_term_meta( $term_id, 'custom_css_desktop_code', '' );
            if( $custom_css_desktop_code )
                BF()->assets_manager()->add_css( '/* responsive monitor */ @media(min-width: 1200px){ ' . $custom_css_desktop_code . '}', true );

            $custom_css_ipad_landscape_code = BF()->taxonomy_meta()->get_term_meta( $term_id, 'custom_css_ipad_landscape_code' );
            if( $custom_css_ipad_landscape_code )
                BF()->assets_manager()->add_css( '/* responsive landscape tablet */ @media(min-width: 1019px) and (max-width: 1199px){ ' .$custom_css_ipad_landscape_code . '}', true );

            $custom_css_ipad_portrait_code = BF()->taxonomy_meta()->get_term_meta( $term_id, 'custom_css_ipad_portrait_code' );
            if( $custom_css_ipad_portrait_code )
                BF()->assets_manager()->add_css( '/* responsive portrait tablet */ @media(min-width: 768px) and (max-width: 1018px){' . $custom_css_ipad_portrait_code . '}', true );

            $custom_css_phones_code = BF()->taxonomy_meta()->get_term_meta( $term_id, 'custom_css_phones_code' );
            if( $custom_css_phones_code )
                BF()->assets_manager()->add_css( '/* responsive phone */ @media(max-width: 767px){ ' . $custom_css_phones_code . ' }', true );

        }
        // Custom CSS for Authors
        elseif( is_author() ){

            $current_user = bf_get_author_archive_user();

            $custom_css_code = BF()->user_meta()->get_meta( 'custom_css_code', $current_user );
            if( $custom_css_code )
                BF()->assets_manager()->add_css( $custom_css_code, true );

            $custom_css_desktop_code = BF()->user_meta()->get_meta( 'custom_css_desktop_code', $current_user );
            if( $custom_css_desktop_code )
                BF()->assets_manager()->add_css( '/* responsive monitor */ @media(min-width: 1200px){ ' . $custom_css_desktop_code . '}', true );

            $custom_css_ipad_landscape_code = BF()->user_meta()->get_meta( 'custom_css_ipad_landscape_code', $current_user );
            if( $custom_css_ipad_landscape_code )
                BF()->assets_manager()->add_css( '/* responsive landscape tablet */ @media(min-width: 1019px) and (max-width: 1199px){ ' .$custom_css_ipad_landscape_code . '}', true );

            $custom_css_ipad_portrait_code = BF()->user_meta()->get_meta( 'custom_css_ipad_portrait_code', $current_user );
            if( $custom_css_ipad_portrait_code )
                BF()->assets_manager()->add_css( '/* responsive portrait tablet */ @media(min-width: 768px) and (max-width: 1018px){' . $custom_css_ipad_portrait_code . '}', true );

            $custom_css_phones_code = BF()->user_meta()->get_meta( 'custom_css_phones_code', $current_user );
            if( $custom_css_phones_code )
                BF()->assets_manager()->add_css( '/* responsive phone */ @media(max-width: 767px){ ' . $custom_css_phones_code . ' }', true );

        }
    }


    /**
     * Callback: Enqueue anything in footer
     *
     * Action: wp_footer
     */
    function wp_footer(){

        // Footer HTML Code
        echo Better_Mag::get_option( 'custom_footer_code' );
    }


    /**
     *  Enqueue admin scripts
     */
    function admin_enqueue(){

        wp_enqueue_style( 'better-mag-admin', BETTER_MAG_ADMIN_ASSETS_URI .'css/admin-style.css', array(), Better_Framework::theme()->get( 'Version' ) );

    }


    /**
     * Callback: Customize body classes
     *
     * Filter: body_class
     *
     * @param $classes
     * @return array
     */
    function filter_body_class( $classes ){

        $_default_layout = '';

        // Add scroll animation class if is enabled
        if( Better_Mag::get_option( 'animation_scroll' ) ){
            $classes[] = 'animation_scroll';
        }

        // Add image zoom animation class if is enabled
        if( Better_Mag::get_option( 'animation_image_zoom' ) ){
            $classes[] = 'animation_image_zoom';
        }

        // Adds enabled_back_to_top class for animation and style of bac to top button
        if( Better_Mag::get_option( 'back_to_top' ) ){
            $classes[] = 'enabled_back_to_top';
        }

        // Activates lighbox
        if( Better_Mag::get_option( 'lightbox_is_enable' ) ){
            $classes[] = 'active-lighbox';
        }

        // General Custom Body Class
        $classes[] = Better_Mag::get_option( 'custom_css_class_general' );

        if( is_singular() ){

            // Emphasize first p
            if( Better_Mag::get_option( 'content_emphasize_first_p' ) ){
                $classes[] = 'active-emphasize-first-p';
            }

            // Pages Custom Body Class
            if( is_page() ){
                $classes[] = Better_Mag::get_option( 'custom_css_class_page' );
                $classes[] = $this->get_meta( 'custom_css_class' );
            }elseif( is_singular( 'post' ) ){
                $classes[] = Better_Mag::get_option( 'custom_css_class_post' );
                $classes[] = $this->get_meta( 'custom_css_class' );
            }

            // Force Gallery Post Format With BG Slide Show as Boxed
            if( get_post_format() == 'gallery' && $this->get_meta( 'gallery_images_bg_slides' ) ){
                $_default_layout = 'boxed-padded';
            }else{
                $_default_layout = get_post_meta( get_the_ID(), '_layout_style', true ) ;
            }

        }

        // Categories layout style
        elseif( is_category() ){

            $classes[] = Better_Mag::get_option( 'custom_css_class_category' );
            $classes[] = BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'custom_css_class', '' );

            $_default_layout = BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'layout_style' );

        }

        // Tags layout style
        elseif( is_tag() ){


            $current_term = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );

            $classes[] = Better_Mag::get_option( 'custom_css_class_tag' );
            $classes[] = BF()->taxonomy_meta()->get_term_meta( $current_term->term_id, 'custom_css_class', '' );

            $_default_layout = BF()->taxonomy_meta()->get_term_meta( $current_term->term_id, 'layout_style' );

        }

        // Author layout style
        elseif( is_author() ){

            $current_user = bf_get_author_archive_user();

            $classes[] = Better_Mag::get_option( 'custom_css_class_author' );
            $classes[] = BF()->user_meta()->get_meta( 'custom_css_class', $current_user );

            $_default_layout = BF()->user_meta()->get_meta( 'layout_style', $current_user );

        }

        // Other Pages layout style
        if( empty( $_default_layout ) || $_default_layout == false || $_default_layout == 'default' ){

            $_default_layout = Better_Mag::get_option( 'layout_style' );

        }



        switch( $_default_layout ){

            case 'boxed':
                $classes[] = 'boxed';
                return $classes;
                break;

            case 'boxed-padded':
                $classes[] = 'boxed';
                $classes[] = 'boxed-padded';
                return $classes;
                break;

            case 'full-width':
                $classes[] = 'full-width';
                return $classes;
                break;

        }
    }


    /**
     * Callback: Customize post classes
     *
     * Filter: post_class
     *
     * @param $classes
     * @return array
     */
    function filter_post_class( $classes, $class, $post_id ){

        // Featured Post
        if( Better_Mag::get_meta( 'bm_featured_post', false, $post_id ) ){
            $classes[] = 'featured-post';
        }


        return $classes;
    }


    /**
     * Generate Custom Mega Menu HTML
     *
     * @param array $args
     * @return string
     */
    public function generate_better_menu( $args ){

        switch( $args['current-item']->mega_menu ){

            // Category Mega Menu
            case 'category-right':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_category_right( false );

                break;

            case 'category-left':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_category_left( false );

                break;

            case 'category-simple-right':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_simple_right( false );

                break;

            case 'category-simple-left':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_simple_left( false );

                break;


            case 'category-recent-left':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_category_recent_left( false );

                break;


            case 'category-recent-right':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );
                Better_Mag::generator()->set_attr( 'mega-menu-item', $args['current-item'] );
                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_category_recent_right( false );

                break;

            case 'link-3-column':
            case 'link':
            case 'link-4-column':

                Better_Mag::generator()->set_attr( 'mega-menu-sub-menu', $args['sub-menu'] );

                if( $args['current-item']->mega_menu == 'link'){
                    Better_Mag::generator()->set_attr( 'mega-menu-columns', 'link-2-column' );
                }else{
                    Better_Mag::generator()->set_attr( 'mega-menu-columns', $args['current-item']->mega_menu );
                }

                $args['output'] = Better_Mag::generator()->blocks()->mega_menu_link( false );

                break;

        }

        return $args;
    }


    /**
     * Include Main Sidebar
     *
     * @see get_sidebar()
     */
    public static function get_sidebar( $sidebar = '' ){

        if( self::current_sidebar_layout() )
            get_sidebar( $sidebar );

    }


    /**
     * Return Sidebar Layout of Current Page if Any Sidebar Defined
     */
    public static function current_sidebar_layout(){

        // From cached before
        if( isset( self::$instances['current_sidebar_layout'] ) && ! empty( self::$instances['current_sidebar_layout'] ) ){
            return  self::$instances['current_sidebar_layout'];
        }

        // Custom sidebar for posts and pages
        if( is_singular() || is_page() ){

            // custom field values saved before
            if( false != ( $_default_layout = get_post_meta( get_the_ID(), '_default_sidebar_layout', true ) )){

                switch( $_default_layout ){

                    // Default settings from theme options
                    case 'default':
                        if( Better_Mag::get_option( 'default_sidebar_layout' ) == 'no-sidebar' ){
                            return self::$instances['current_sidebar_layout'] = false;
                        }
                        else{
                            return self::$instances['current_sidebar_layout'] = Better_Mag::get_option( 'default_sidebar_layout' );
                        }
                        break;

                    // No Sidebar
                    case 'no-sidebar':
                        return  self::$instances['current_sidebar_layout'] = false;
                        break;

                    // Right And Left Side Sidebars
                    default:
                        return self::$instances['current_sidebar_layout'] = $_default_layout;

                }

            }

        }

        // Custom sidebar layout for categories & Tags
        elseif( is_category() || is_tag() ){

            if( is_category() ){
                $term_id = get_query_var( 'cat' );
            }else{
                $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
                $term_id = $tag->term_id;
            }

            // custom field values saved before
            $_default_layout = BF()->taxonomy_meta()->get_term_meta( $term_id, 'sidebar_layout', 'default' );
            switch( $_default_layout ){

                // Default settings from theme options
                case 'default':
                    if( Better_Mag::get_option( 'default_sidebar_layout' ) == 'no-sidebar' ){
                        return self::$instances['current_sidebar_layout'] = false;
                    }
                    else{
                        return self::$instances['current_sidebar_layout'] = Better_Mag::get_option( 'default_sidebar_layout' );
                    }
                    break;

                // No Sidebar
                case 'no-sidebar':
                    return  self::$instances['current_sidebar_layout'] = false;
                    break;

                // Right And Left Side Sidebars
                default:
                    return self::$instances['current_sidebar_layout'] = $_default_layout;

            }

        }

        // Custom sidebar for authors archive page
        elseif( is_author() ){

            $current_user = bf_get_author_archive_user();

            $_default_layout = BF()->user_meta()->get_meta( 'sidebar_layout', $current_user );

            switch( $_default_layout ){

                // Default settings from theme options
                case 'default':

                    if( Better_Mag::get_option( 'default_sidebar_layout' ) == 'no-sidebar' ){
                        self::$instances['current_sidebar_layout'] = false;
                        return false;
                    }
                    else{
                        self::$instances['current_sidebar_layout'] = Better_Mag::get_option( 'default_sidebar_layout' );
                        return self::$instances['current_sidebar_layout'];
                    }
                    break;

                // No Sidebar
                case 'no-sidebar':
                    return  self::$instances['current_sidebar_layout'] = false;
                    break;

                // Right And Left Side Sidebars
                default:
                    return self::$instances['current_sidebar_layout'] = $_default_layout;

            }
        }

        // Custom sidebar for search result page
        elseif( is_search() ){

            $_default_layout = Better_Mag::get_option( 'search_sidebar_layout' );

            switch( $_default_layout ){

                // No Sidebar
                case 'no-sidebar':
                    return  self::$instances['current_sidebar_layout'] = false;
                    break;

                // Right And Left Side Sidebars
                default:
                    return self::$instances['current_sidebar_layout'] = $_default_layout;

            }
        }



        if( Better_Mag::get_option( 'default_sidebar_layout' ) == 'no-sidebar' ){
            self::$instances['current_sidebar_layout'] = false;
            return false;
        }
        else{
            self::$instances['current_sidebar_layout'] = Better_Mag::get_option( 'default_sidebar_layout' );
            return self::$instances['current_sidebar_layout'];
        }

    }


    /**
     * Used for finding the content listing style of archive pages
     *
     * @return string
     */
    public static function get_page_listing_template(){

        // Category Page Listing Type
        if( is_category() ){

            // Retrieve from each category not general option
            if( BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'listing_style', 'default' ) != 'default' ){

                if( BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'listing_style', 'blog' ) == 'blog' )
                    return 'loop';
                else
                    return 'loop-' . BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'listing_style', 'blog' );

            }
            // General options that specified for categories
            elseif( Better_Mag::get_option( 'categories_listing_style' ) == 'blog' ){
                return 'loop';
            }else{
                return 'loop-' . Better_Mag::get_option( 'categories_listing_style' );
            }

        }

        // Tag Page Listing Type
        elseif( is_tag() ){

            $current_term = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
            // Retrieve from each tag not general option
            if( BF()->taxonomy_meta()->get_term_meta( $current_term->term_id, 'listing_style', 'default' ) != 'default' ){

                if( BF()->taxonomy_meta()->get_term_meta( $current_term->term_id, 'listing_style', 'blog' ) == 'blog' )
                    return 'loop';
                else
                    return 'loop-' . BF()->taxonomy_meta()->get_term_meta( $current_term->term_id, 'listing_style', 'blog' );

            }
            // General options that specified for tags
            elseif( Better_Mag::get_option( 'tags_listing_style' ) == 'blog' ){
                return 'loop';
            }else{
                return 'loop-' . Better_Mag::get_option( 'tags_listing_style' );
            }

        }

        // Authors Page Listing Type
        elseif( is_author() ){

            $current_user = bf_get_author_archive_user();

            // Retrieve from each tag not general option
            if( BF()->user_meta()->get_meta( 'listing_style', $current_user ) != 'default' ){

                if( BF()->user_meta()->get_meta( 'listing_style', $current_user ) == 'blog' )
                    return 'loop';
                else
                    return 'loop-' . BF()->user_meta()->get_meta( 'listing_style', $current_user );

            }
            // General options that specified for user
            elseif( Better_Mag::get_option( 'authors_listing_style' ) == 'blog' ){
                return 'loop';
            }else{
                return 'loop-' . Better_Mag::get_option( 'authors_listing_style' );
            }

        }

        // Search Result Listing Type
        elseif( is_search() ){

            // General options that specified for user
            if( Better_Mag::get_option( 'search_listing_style' ) == 'blog' ){
                return 'loop';
            }else{
                return 'loop-' . Better_Mag::get_option( 'search_listing_style' );
            }

        }

        // Other Pages Like Front Simple Page, Search, Date...
        else{
            if( Better_Mag::get_option( 'archive_listing_style' ) == 'blog' ){
                return 'loop';
            }else{
                return 'loop-' . Better_Mag::get_option( 'archive_listing_style' );
            }
        }

    }


    /**
     * Adds random order by feature to WP_User_Query
     *
     * Action: pre_user_query
     *
     * @param $class
     * @return mixed
     */
    public function action_pre_user_query( $class ){

        if( 'rand' == $class->query_vars['orderby'] )
            $class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );

        return $class;

    }


    /**
     * Resets typography options to default
     *
     * Callback
     *
     * @return array
     */
    public static function reset_typography_options(){

        $theme_options = get_option( self::$theme_panel_key );

        $fields = BF()->options()->options[self::$theme_panel_key]['fields'];

        $std_id = BF()->options()->get_std_field_id( self::$theme_panel_key );

        if( isset( $fields['typo_body'][$std_id] ) ){
            $theme_options['typo_body'] = $fields['typo_body'][$std_id] ;
        }else{
            $theme_options['typo_body'] = $fields['typo_body']['std'] ;
        }

        if( isset( $fields['typo_heading'][$std_id] ) ){
            $theme_options['typo_heading'] = $fields['typo_heading'][$std_id] ;
        }else{
            $theme_options['typo_heading'] = $fields['typo_heading']['std'] ;
        }

        if( isset( $fields['typo_heading_page'][$std_id] ) ){
            $theme_options['typo_heading_page'] = $fields['typo_heading_page'][$std_id] ;
        }else{
            $theme_options['typo_heading_page'] = $fields['typo_heading_page']['std'] ;
        }

        if( isset( $fields['typo_heading_section'][$std_id] ) ){
            $theme_options['typo_heading_section'] = $fields['typo_heading_section'][$std_id] ;
        }else{
            $theme_options['typo_heading_section'] = $fields['typo_heading_section']['std'] ;
        }

        if( isset( $fields['typo_meta'][$std_id] ) ){
            $theme_options['typo_meta'] = $fields['typo_meta'][$std_id] ;
        }else{
            $theme_options['typo_meta'] = $fields['typo_meta']['std'] ;
        }

        if( isset( $fields['typo_excerpt'][$std_id] ) ){
            $theme_options['typo_excerpt'] = $fields['typo_excerpt'][$std_id] ;
        }else{
            $theme_options['typo_excerpt'] = $fields['typo_excerpt']['std'] ;
        }

        if( isset( $fields['typ_content_text'][$std_id] ) ){
            $theme_options['typ_content_text'] = $fields['typ_content_text'][$std_id] ;
        }else{
            $theme_options['typ_content_text'] = $fields['typ_content_text']['std'] ;
        }

        if( isset( $fields['typ_content_blockquote'][$std_id] ) ){
            $theme_options['typ_content_blockquote'] = $fields['typ_content_blockquote'][$std_id] ;
        }else{
            $theme_options['typ_content_blockquote'] = $fields['typ_content_blockquote']['std'] ;
        }

        if( isset( $fields['typ_header_menu'][$std_id] ) ){
            $theme_options['typ_header_menu'] = $fields['typ_header_menu'][$std_id] ;
        }else{
            $theme_options['typ_header_menu'] = $fields['typ_header_menu']['std'] ;
        }

        if( isset( $fields['typ_header_menu_badges'][$std_id] ) ){
            $theme_options['typ_header_menu_badges'] = $fields['typ_header_menu_badges'][$std_id] ;
        }else{
            $theme_options['typ_header_menu_badges'] = $fields['typ_header_menu_badges']['std'] ;
        }

        if( isset( $fields['typ_header_logo'][$std_id] ) ){
            $theme_options['typ_header_logo'] = $fields['typ_header_logo'][$std_id] ;
        }else{
            $theme_options['typ_header_logo'] = $fields['typ_header_logo']['std'] ;
        }

        if( isset( $fields['typ_header_site_desc'][$std_id] ) ){
            $theme_options['typ_header_site_desc'] = $fields['typ_header_site_desc'][$std_id] ;
        }else{
            $theme_options['typ_header_site_desc'] = $fields['typ_header_site_desc']['std'] ;
        }

        if( isset( $fields['typo_listing_blog_heading'][$std_id] ) ){
            $theme_options['typo_listing_blog_heading'] = $fields['typo_listing_blog_heading'][$std_id] ;
        }else{
            $theme_options['typo_listing_blog_heading'] = $fields['typo_listing_blog_heading']['std'] ;
        }

        if( isset( $fields['typo_listing_blog_meta'][$std_id] ) ){
            $theme_options['typo_listing_blog_meta'] = $fields['typo_listing_blog_meta'][$std_id] ;
        }else{
            $theme_options['typo_listing_blog_meta'] = $fields['typo_listing_blog_meta']['std'] ;
        }

        if( isset( $fields['typo_listing_blog_excerpt'][$std_id] ) ){
            $theme_options['typo_listing_blog_excerpt'] = $fields['typo_listing_blog_excerpt'][$std_id] ;
        }else{
            $theme_options['typo_listing_blog_excerpt'] = $fields['typo_listing_blog_excerpt']['std'] ;
        }

        if( isset( $fields['typo_listing_modern_heading'][$std_id] ) ){
            $theme_options['typo_listing_modern_heading'] = $fields['typo_listing_modern_heading'][$std_id] ;
        }else{
            $theme_options['typo_listing_modern_heading'] = $fields['typo_listing_modern_heading']['std'] ;
        }

        if( isset( $fields['typo_listing_modern_meta'][$std_id] ) ){
            $theme_options['typo_listing_modern_meta'] = $fields['typo_listing_modern_meta'][$std_id] ;
        }else{
            $theme_options['typo_listing_modern_meta'] = $fields['typo_listing_modern_meta']['std'] ;
        }

        if( isset( $fields['typo_listing_modern_excerpt'][$std_id] ) ){
            $theme_options['typo_listing_modern_excerpt'] = $fields['typo_listing_modern_excerpt'][$std_id] ;
        }else{
            $theme_options['typo_listing_modern_excerpt'] = $fields['typo_listing_modern_excerpt']['std'] ;
        }

        if( isset( $fields['typo_listing_highlight_heading'][$std_id] ) ){
            $theme_options['typo_listing_highlight_heading'] = $fields['typo_listing_highlight_heading'][$std_id] ;
        }else{
            $theme_options['typo_listing_highlight_heading'] = $fields['typo_listing_highlight_heading']['std'] ;
        }

        if( isset( $fields['typo_listing_highlight_meta'][$std_id] ) ){
            $theme_options['typo_listing_highlight_meta'] = $fields['typo_listing_highlight_meta'][$std_id] ;
        }else{
            $theme_options['typo_listing_highlight_meta'] = $fields['typo_listing_highlight_meta']['std'] ;
        }

        if( isset( $fields['typo_listing_thumbnail_heading'][$std_id] ) ){
            $theme_options['typo_listing_thumbnail_heading'] = $fields['typo_listing_thumbnail_heading'][$std_id] ;
        }else{
            $theme_options['typo_listing_thumbnail_heading'] = $fields['typo_listing_thumbnail_heading']['std'] ;
        }

        if( isset( $fields['typo_listing_thumbnail_meta'][$std_id] ) ){
            $theme_options['typo_listing_thumbnail_meta'] = $fields['typo_listing_thumbnail_meta'][$std_id] ;
        }else{
            $theme_options['typo_listing_thumbnail_meta'] = $fields['typo_listing_thumbnail_meta']['std'] ;
        }

        if( isset( $fields['typo_listing_simple_heading'][$std_id] ) ){
            $theme_options['typo_listing_simple_heading'] = $fields['typo_listing_simple_heading'][$std_id] ;
        }else{
            $theme_options['typo_listing_simple_heading'] = $fields['typo_listing_simple_heading']['std'] ;
        }

        if( isset( $fields['typo_read_more'][$std_id] ) ){
            $theme_options['typo_read_more'] = $fields['typo_read_more'][$std_id] ;
        }else{
            $theme_options['typo_read_more'] = $fields['typo_read_more']['std'] ;
        }

        if( isset( $fields['typo_listing_blog_read_more'][$std_id] ) ){
            $theme_options['typo_listing_blog_read_more'] = $fields['typo_listing_blog_read_more'][$std_id] ;
        }else{
            $theme_options['typo_listing_blog_read_more'] = $fields['typo_listing_blog_read_more']['std'] ;
        }

        if( isset( $fields['typo_category_banner'][$std_id] ) ){
            $theme_options['typo_category_banner'] = $fields['typo_category_banner'][$std_id] ;
        }else{
            $theme_options['typo_category_banner'] = $fields['typo_category_banner']['std'] ;
        }

        if( isset( $fields['typo_listing_blog_category_banner'][$std_id] ) ){
            $theme_options['typo_listing_blog_category_banner'] = $fields['typo_listing_blog_category_banner'][$std_id] ;
        }else{
            $theme_options['typo_listing_blog_category_banner'] = $fields['typo_listing_blog_category_banner']['std'] ;
        }

        if( isset( $fields['typo_listing_modern_category_banner'][$std_id] ) ){
            $theme_options['typo_listing_modern_category_banner'] = $fields['typo_listing_modern_category_banner'][$std_id] ;
        }else{
            $theme_options['typo_listing_modern_category_banner'] = $fields['typo_listing_modern_category_banner']['std'] ;
        }

        if( isset( $fields['typo_listing_highlight_category_banner'][$std_id] ) ){
            $theme_options['typo_listing_highlight_category_banner'] = $fields['typo_listing_highlight_category_banner'][$std_id] ;
        }else{
            $theme_options['typo_listing_highlight_category_banner'] = $fields['typo_listing_highlight_category_banner']['std'] ;
        }

        if( isset( $fields['typo_slider_category_banner'][$std_id] ) ){
            $theme_options['typo_slider_category_banner'] = $fields['typo_slider_category_banner'][$std_id] ;
        }else{
            $theme_options['typo_slider_category_banner'] = $fields['typo_slider_category_banner']['std'] ;
        }

        if( isset( $fields['typo_slider_heading'][$std_id] ) ){
            $theme_options['typo_slider_heading'] = $fields['typo_slider_heading'][$std_id] ;
        }else{
            $theme_options['typo_slider_heading'] = $fields['typo_slider_heading']['std'] ;
        }

        update_option( self::$theme_panel_key, $theme_options );

        delete_transient( '__better_framework__panel_css' );
        delete_transient( '__better_framework__final_fe_css' );
        delete_transient( '__better_framework__final_fe_css_version' );

        BF()->admin_notices()->add_notice( array(
            'msg' => __( 'Typography options resets to default.', 'better-studio' )
        ) );

        return array(
            'status'  => 'succeed',
            'msg'	  => __( 'Typography resets to default.', 'better-studio' ),
            'refresh' => true
        );

    }


    /**
     * Filter Callback: adds theme update data to Better Studio Theme Updater
     *
     * @param $data
     * @return array
     */
    public function filter_theme_update_manager_data( $data ){

        return array(
            'theme_name'    =>  'BetterMag',
            'theme_slug'    =>  'better-mag',
            'theme_id'      =>  '8746038',
            'user_name'     =>  $this->get_option( 'themeforest_user_name' ),
            'api_key'       =>  $this->get_option( 'themeforest_api_key' ),
        );

    }


    /**
     * Callback: Used for changing WP_Query, specifically for posts per page in archives
     *
     * @param   WP_Query    $query      WP_Query instance
     */
    function pre_get_posts( $query ) {

        // This is only for front end and main query
        if( ! is_admin() && $query->is_main_query() ){

            // Posts per page for categories
            if( $query->is_category() ){

                $term = get_category( $query->get_queried_object_id() );

                // Custom count per category
                if( BF()->taxonomy_meta()->get_term_meta( $term, 'term_posts_count', '' ) != '' ){

                    $query->set( 'posts_per_page', BF()->taxonomy_meta()->get_term_meta( $term, 'term_posts_count', '' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );


                }
                // Custom count for all categories
                elseif( Better_Mag::get_option( 'archive_cat_posts_count' ) != '' && intval( Better_Mag::get_option( 'archive_cat_posts_count' ) ) > 0 ){

                    $query->set( 'posts_per_page', Better_Mag::get_option( 'archive_cat_posts_count' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }

            }
            // Posts per page for tags
            elseif( $query->is_tag() ){

                $term = get_term( $query->get_queried_object_id(), 'post_tag' );

                // Custom count per tag
                if( BF()->taxonomy_meta()->get_term_meta( $term, 'term_posts_count', '' ) != '' ){

                    $query->set( 'posts_per_page', BF()->taxonomy_meta()->get_term_meta( $term, 'term_posts_count', '' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }
                // Custom count for all tags
                elseif( Better_Mag::get_option( 'archive_tag_posts_count' ) != '' && intval( Better_Mag::get_option( 'archive_tag_posts_count' ) ) > 0 ){

                    $query->set( 'posts_per_page', Better_Mag::get_option( 'archive_tag_posts_count' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }

            }
            // Posts per page for authors
            elseif( $query->is_author() ){

                $current_user = $query->query_vars['author_name'];
                $current_user = get_user_by( 'slug', $current_user );

                // Custom Post Types
                if( BF()->user_meta()->get_meta( 'author_post_types', $current_user ) != '' ){
                    $query->set( 'post_type', explode( ',', BF()->user_meta()->get_meta( 'author_post_types', $current_user ) ) );
                }

                // Custom count per author
                if( BF()->user_meta()->get_meta( 'author_posts_count', $current_user ) != '' && intval( BF()->user_meta()->get_meta( 'author_posts_count', $current_user ) ) > 0 ){

                    $query->set( 'posts_per_page', BF()->user_meta()->get_meta( 'author_posts_count', $current_user ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }
                // Custom count for all tags
                elseif( Better_Mag::get_option( 'archive_author_posts_count' ) != '' && intval( Better_Mag::get_option( 'archive_author_posts_count' ) ) > 0 ){

                    $query->set( 'posts_per_page', Better_Mag::get_option( 'archive_author_posts_count' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }



            }
            // Posts per page for search
            elseif( $query->is_search() ){

                if( Better_Mag::get_option( 'archive_search_posts_count' ) != '' && intval( Better_Mag::get_option( 'archive_search_posts_count' ) ) > 0 ){

                    $query->set( 'posts_per_page', Better_Mag::get_option( 'archive_search_posts_count' ) );
                    $query->set( 'paged', get_query_var('paged') ? get_query_var('paged') : 1 );

                }

                // Customize search result content
                switch( Better_Mag::get_option( 'search_result_content' ) ){

                    case 'post':
                        $query->set( 'post_type', 'post' );
                        break;

                    case 'page':
                        $query->set( 'post_type', 'page' );
                        break;

                    case 'post-page':
                        $query->set( 'post_type', array( 'post', 'page' ) );
                        break;

                }// switch

            }// is_search

        }// if
    }


    /**
     * Callback: Change Better Facebook Comments text
     *
     * Filter: better-facebook-comments/js/global-vars
     *
     * @param $vars
     * @return mixed
     */
    function better_facebook_comments_vars( $vars ){

        $vars['text_0'] = '<i class="fa fa-comment-o"></i> 0';
        $vars['text_1'] = '<i class="fa fa-comment"></i> 1';
        $vars['text_2'] = '<i class="fa fa-comments-o"></i> 2';
        $vars['text_more'] = '<i class="fa fa-comments-o"></i> %%NUMBER%%';

        return $vars;

    }


    /**
     * Callback: Used for using user avatar field
     *
     * Filter: get_avatar
     *
     * @param $avatar
     * @param $id_or_email
     * @param $size
     * @param $default
     * @param $alt
     *
     * @return string
     */
    function get_avatar( $avatar, $id_or_email, $size, $default, $alt ){

        if( is_numeric( $id_or_email ) ){

            $id = (int) $id_or_email;

        }elseif( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ){

            $id = (int) $id_or_email->user_id;

        }else{
            return $avatar;
        }

        if( BF()->user_meta()->get_meta( 'avatar', $id ) != '' ){

            $out = BF()->user_meta()->get_meta( 'avatar', $id );
            $avatar = "<img alt='{$alt}' src='{$out}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";

        }

        return $avatar;

    }


    function better_sticky_sidebar_config( $config, $sidebar_id ){

        if( $sidebar_id == 'primary-sidebar' && Better_Mag::get_option( 'main_menu_sticky' ) ){

            if( is_admin_bar_showing() ){
                $config['top'] = 76;
            }else{
                $config['top'] = 44;
            }

        }

        return $config;
    }

}