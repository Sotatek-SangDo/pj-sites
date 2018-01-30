<?php


/******* Table Of Content
 *
 * 1. => BetterFramework Features
 *
 * 2. => Widget Custom CSS
 *
 * 3. => Meta Box Options
 *      3.1. => General Post Options
 *      3.2. => Page Layout Style
 *      3.3. => WooCommerce Product Page Options
 *      3.4. => Pages Slider Options
 *
 * 4. => Taxonomy Options
 *      4.1. => Category Options
 *      4.2. => Tag Options
 *
 * 5. => Admin Panel
 *      5.1. => General Options
 *
 *      5.2. => Header Options
 *
 *      5.3. => Footer Options
 *
 *      5.4. => Content & Listing Options
 *
 *      5.5. => Typography Options
 *              5.5.1. => General Typography
 *              5.5.2. => Blog Listing Typography
 *              5.5.3. => Modern Listing Typography
 *              5.5.4. => Highlight Listing Typography
 *              5.5.5. => Thumbnail Listing Typography
 *              5.5.6. => Simple Listing Typography
 *              5.5.7. => Header Typography
 *              5.5.8. => Pages/Posts Content Typography
 *
 *      5.6. => Color Options
 *              5.6.1. => General Colors
 *              5.6.2. => Header
 *              5.6.3. => Main Navigation
 *              5.6.4. => Main Navigation - Drop Down Sub Menu
 *              5.6.5. => Main Navigation - Mega Menu
 *              5.6.6. => Breadcrumb
 *              5.6.7. => Slider
 *              5.6.8. => News Ticker
 *              5.6.9. => Page Title
 *              5.6.10. => Section/Listing Title
 *              5.6.11. => Sidebar Widget Title
 *              5.6.12. => Footer
 *              5.6.13. => Back to top
 *
 *      5.7. => Social Counter Options ( Removed -> Moved to Better Social Counter Plugin )
 *
 *      5.8. => WooCommerce Options
 *
 *      5.8. => Custom Javascript / CSS
 *
 *      5.10. => Import & Export
 *
 * 6. => Setup Shortcodes
 *      6.1. => BetterFramework Shortcodes
 *      6.2. => BetterMag Shortcodes
 *
 * 7. => Menu Options
 *
 * 8. => Breadcrumb
 *
 */


/**
 * Setup BetterFramework for BetterMag
 */
class Better_Mag_BF_Setup {

    function __construct(){

        define( 'BETTER_MAG_ADMIN_ASSETS_URI' , get_template_directory_uri() . '/includes/admin-assets/' );
        define( 'BETTER_MAG_PATH', get_template_directory().'/' );
        define( 'BETTER_MAG_URI', get_template_directory_uri().'/' );

	    /*
		 * i18n
		 */
	    load_theme_textdomain( 'better-studio', get_template_directory() . '/languages' );

        // Register included BF to loader ( After Plugins )
        add_filter( 'better-framework/loader', array( $this, 'register_better_framework' ), 100 );

        // Enable needed sections
        add_filter( 'better-framework/sections' , array( $this , 'setup_bf_features' ), 100 );

        // Admin panel options
        add_filter( 'better-framework/panel/options' , array( $this , 'setup_option_panel' ), 100 );

        // Meta box options
        add_filter( 'better-framework/metabox/options' , array( $this , 'setup_bf_metabox' ), 100 );

        // User Meta box options
        add_filter( 'better-framework/user-metabox/options' , array( $this , 'setup_bf_user_metabox' ), 100 );

        // Taxonomy options
        add_filter( 'better-framework/taxonomy/options' , array( $this , 'taxonomy_options' ), 100 );

        // Menus options
        add_filter( 'better-framework/menu/options', array( $this, 'setup_custom_menu_fields' ), 100 );

        // Breadcrumb config
        add_filter( 'better-framework/breadcrumb/options', array( $this, 'bf_breadcrumb_options'), 100 );

        // Active and new shortcodes
        add_filter( 'better-framework/shortcodes', array( $this, 'setup_shortcodes' ), 100 );

        // Define special sidebars to BF
        add_filter( 'better-framework/sidebars/locations/top-bar' , array( $this , 'special_top_bar_sidebar_locations' ), 100 );
        add_filter( 'better-framework/sidebars/locations/footer-bar' , array( $this , 'special_footer_sidebar_locations' ), 100 );

        // Define general widget fields and values
        add_filter( 'better-framework/widgets/options/general' , array( $this , 'widgets_general_fields' ), 100 );
        add_filter( 'better-framework/widgets/options/general/bf-widget-title-color/default' , array( $this , 'general_widget_heading_color_field_default' ), 100 );
        add_filter( 'better-framework/widgets/options/general/bf-widget-title-bg-color/default' , array( $this , 'general_widget_heading_bg_field_default' ), 100 );

        // Define custom css for widgets
        add_filter( 'better-framework/css/widgets' , array( $this, 'widgets_custom_css' ), 100 );

        // Init Better Translation Panel
        add_filter( 'better-translation/config', array( $this, 'filter_translations_config' ) );
        add_filter( 'better-translation/translations/fields', array( $this, 'filter_translations_fields' ) );
        require_once  BETTER_MAG_PATH . 'includes/libs/better-studio-translation/class-better-translation.php';

        // Initialize no duplicate posts option
        add_action( 'better-template/duplicate-posts/config', array( $this, 'setup_no_duplicate_posts' ) );

    }


    /**
     * Registers included version of BF to BF loader
     *
     * @param $frameworks
     * @return array
     */
    function register_better_framework( $frameworks ){

        $frameworks[] = array(
            'version'   =>  '2.5.25',
            'path'      =>  dirname( __FILE__ ) . '/libs/better-framework/',
            'uri'       =>  get_template_directory_uri() . '/includes/libs/better-framework/',
        );

        return $frameworks;
    }


    /**
     * Setups features of BetterFramework for BetterMag
     *
     * @param $features
     * @return array
     */
    function setup_bf_features($features){

        /**
         * 1. => BetterFramework Features
         */
        $features['admin_panel']        = true;
        $features['meta_box']           = true;
        $features['taxonomy_meta_box']  = true;
        $features['load_in_frontend']   = false;
        $features['chat_post_formatter']= true;
        $features['better-menu']        = true;
        $features['vc-extender']        = true;
        $features['custom-css-pages']   = true;
        $features['user-meta-box']      = true;
        $features['custom-css-users']   = true;

        if( function_exists( 'is_woocommerce' ) ){
            $features['woocommerce'] = true;
        }

        if( class_exists( 'bbpress' ) ){
            $features['bbpress'] = true;
        }

        return $features;
    }


    /**
     * Filter BetterMag special top-bar sidebar locations for widgets
     */
    function special_top_bar_sidebar_locations( $locations ){

        $locations[] = 'top-bar-left';
        $locations[] = 'top-bar-right';
        $locations[] = 'aside-logo';

        return $locations;

    }

    /**
     * Filter BetterMag special top-bar sidebar locations for widgets
     */
    function special_footer_sidebar_locations( $locations ){

        $locations[] = 'footer-lower-left-column';
        $locations[] = 'footer-lower-right-column';

        return $locations;

    }


    /**
     * Filter BetterMag widgets general fields
     *
     * @param $fields
     * @return array
     */
    function widgets_general_fields( $fields ){

        $fields[] = 'bf-widget-title-color';
        $fields[] = 'bf-widget-title-icon';
        $fields[] = 'bf-widget-title-link';

        $fields[] = 'bf-widget-show-desktop';
        $fields[] = 'bf-widget-show-tablet';
        $fields[] = 'bf-widget-show-mobile';

        return $fields;

    }


    /**
     * Default value for widget title heading color
     *
     * @param $value
     * @return string
     */
    function general_widget_heading_color_field_default( $value ){

        return Better_Mag::get_option( 'color_widget_title_text_bg_color' );

    }


    /**
     * Widgets Custom css parameters
     *
     * @param $fields
     * @return array
     */
    function widgets_custom_css( $fields ){

        /**
         * 2. => Widget Custom CSS
         */

        switch( get_option( '__better_mag__theme_options_current_style' ) ){

            case "dark":
            case "full-dark":
            case "black":
            case "full-black":
            case "green":
            case "blue1":
                $fields[] = array(
                    'field' => 'bf-widget-title-color',
                    array(
                        'selector'  => array(
                            '%%widget-id%% .section-heading'
                        ),
                        'prop'      => array(
                            'border-bottom-color' => '%%value%%'
                        ),
                    ),
                    array(
                        'selector'  => array(
                            '%%widget-id%% .section-heading .h-title',
                            '%%widget-id%%.footer-larger-widget .section-heading',
                        ),
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        ),
                    ),
                    array(
                        'selector'  => '%%widget-id%% .section-heading' ,
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        ),
                    )
                );
                break;

            default:
                $fields[] = array(
                    'field' => 'bf-widget-title-color',
                    array(
                        'selector'  => array(
                            '%%widget-id%% .section-heading'
                        ),
                        'prop'      => array(
                            'border-bottom-color' => '%%value%%'
                        ),
                    ),
                    array(
                        'selector'  => '%%widget-id%% .section-heading .h-title' ,
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        ),
                    )

                );

        }

        return $fields;
    }


    /**
     * Setup custom metaboxes for BetterMag
     *
     * @param $options
     * @return array
     */
    function setup_bf_metabox( $options ){

        /**
         * 3. => Meta Box Options
         */


        $fields = array();

        /**
         * => Post Options
         */
        $fields['_post_options'] = array(
            'name'          =>  __( 'Post', 'better-studio' ),
            'id'            =>  '_post_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-page-text',
        );
            if( ! is_admin() || bf_get_admin_current_post_type() == 'post' ) {

                $fields['_bm_featured_post'] = array(
                    'name'      =>  __('Featured Slider Post?', 'better-studio'),
                    'id'        =>  '_bm_featured_post',
                    'std'       =>  '0',
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Yes', 'better-studio' ),
                    'off-label' =>  __( 'No', 'better-studio' ),
                );

            }
            $fields['_bm_disable_post_featured'] = array(
                'name'      => __( 'Show Featured Image/Video', 'better-studio' ),
                'id'        => '_bm_disable_post_featured',
                'std'       => 'default',
                'type'      => 'select',
                'options'   => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );
            $fields['_featured_video_code'] = array(
                'name'          =>  __( 'Featured Video Code', 'better-studio' ),
                'id'            =>  '_featured_video_code',
                'desc'          =>  __( 'Paste YouTube, Vimeo or self hosted video URL then player automatically will be generated.', 'better-studio' ),
                'type'          =>  'textarea',
                'std'           =>  '',
            );

        if( ! is_admin() || bf_get_admin_current_post_type() == 'post' ) {

            $fields['_bs_primary_category'] = array(
                'name'      => __( 'Primary Category', 'better-studio' ),
                'desc'      => __( 'When you have multiple categories for a post, auto detection chooses one in alphabetical order. These used for show an label above image in listings and breadcrumb.', 'better-studio' ),
                'id'        => '_bs_primary_category',
                'std'       => 'auto-detect',
                'type'      => 'select',
                'options'   => array(
                    'auto-detect' => __( 'Auto Detect', 'better-studio' ),
                    array(
                        'label' => __( 'Categories', 'better-studio' ),
                        'options' => array( 'category_walker' => 'category_walker' ),
                    )
                )
            );
        }

            $fields['_hide_page_title'] = array(
                'name'          =>  bf_get_admin_current_post_type() == 'post' ? __( 'Hide Post Title?', 'better-studio' ) : __( 'Hide Page Title?', 'better-studio' ),
                'id'            =>  '_hide_page_title',
                'type'          =>  'switch',
                'std'           =>  '0',
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
                'desc'          =>  __( 'Enable this for hiding page title', 'better-studio' ),
            );

        $fields['_show_comments'] = array(
            'name'          =>  __( 'Show Comments', 'better-studio' ),
            'id'            =>  '_show_comments',
            'desc'          =>  __( 'Choose to show or hide comments area.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );

        if( ! is_admin() || bf_get_admin_current_post_type() == 'post' ){

            $fields['_hide_post_meta'] = array(
                'name'          =>  __( 'Show Post Meta', 'better-studio' ),
                'id'            =>  '_hide_post_meta',
                'desc'          =>  __( 'Choose to show or hide post meta', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

        }

        if( ! is_admin() || bf_get_admin_current_post_type() == 'page' ) {

            $fields['_social_share'] = array(
                'name'          =>  __( 'Share Box', 'better-studio' ),
                'id'            =>  '_social_share',
                'desc'          =>  __( 'Choose to show or hide share box', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'hide',
                'options'       => array(
                    'hide'      => __( 'Hide', 'better-studio' ),
                    'top'       => __( 'Top', 'better-studio' ),
                    'bottom'    => __( 'Bottom', 'better-studio' ),
                    'bottom-top'=> __( 'Top & Bottom', 'better-studio' ),
                )
            );

        }

        if( ! is_admin() || bf_get_admin_current_post_type() == 'post' ){

            $fields['_show_social_share'] = array(
                'name'          =>  __( 'Show Share Box', 'better-studio' ),
                'id'            =>  '_show_social_share',
                'desc'          =>  __( 'Choose to show or hide share box', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );
            $fields['_show_related_posts'] = array(
                'name'          =>  __( 'Show Related Posts', 'better-studio' ),
                'id'            =>  '_show_related_posts',
                'desc'          =>  __( 'Choose to show or hide related posts on this post', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_show_post_navigation'] = array(
                'name'          =>  __( 'Show Previous/Next Pagination', 'better-studio' ),
                'id'            =>  '_show_post_navigation',
                'desc'          =>  __( 'Choose to show or hide the post navigation', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_show_author_box'] = array(
                'name'          =>  __( 'Show Author Info Box', 'better-studio' ),
                'id'            =>  '_show_author_box',
                'desc'          =>  __( 'Choose to show or hide the author info box', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_show_post_categories'] = array(
                'name'          =>  __( 'Show Post Categories', 'better-studio' ),
                'id'            =>  '_show_post_categories',
                'desc'          =>  __( 'Choose to show or hide the categories', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_show_post_tags'] = array(
                'name'          =>  __( 'Show Post Tags', 'better-studio' ),
                'id'            =>  '_show_post_tags',
                'desc'          =>  __( 'Choose to show or hide the tags', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

        }


        /**
         * => Page Options
         */
        $fields['_page_options'] = array(
            'name'          =>  __( 'Page Style', 'better-studio' ),
            'id'            =>  '_page_options',
            'std'           =>  '0' ,
            'type'          =>  'tab',
            'icon'          =>  'bsai-paint',
        );

            $fields['_layout_style'] = array(
                'name'          =>  __( 'Page Layout Style', 'better-studio' ),
                'id'            =>  '_layout_style',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select page layout style.', 'better-studio' ),
                'options'       =>  array(
                    'default'   =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-' . Better_Mag::get_option( 'layout_style' ) .'.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'full-width'=>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                        'label'     =>  __( 'Full Width', 'better-studio' ),
                    ),
                    'boxed'     =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                        'label'     =>  __( 'Boxed', 'better-studio' ),
                    ),
                    'boxed-padded'=> array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                        'label'     =>  __( 'Boxed (Padded)', 'better-studio' ),
                    ),
                )
            );

            $fields['_bg_color'] = array(
                'name'          =>  __( 'Page Background Color', 'better-studio' ),
                'id'            =>  '_bg_color',
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'bg_color' ),
                'save-std'      =>  false,
                'desc'          =>  __( 'Setting a body background image below will override it.', 'better-studio' ),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body',
                            'body.boxed',
                        ),
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        )
                    )
                ),
            );
            $fields['_bg_image'] = array(
                'name'          =>  __( 'Page Background Image', 'better-studio' ),
                'id'            =>  '_bg_image',
                'type'          =>  'background_image',
                'std'           =>  '',
                'save-std'      =>  false,
                'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
                'desc'          =>  __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.', 'better-studio' ),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body'
                        ),
                        'prop'      => array( 'background-image' ),
                        'type'      => 'background-image'
                    )
                ),
            );

            $fields['_gallery_images_bg_slides'] = array(
                'name'          =>  __( 'Show Gallery Images as Background Slide Show!?', 'better-studio' ),
                'id'            =>  '_gallery_images_bg_slides',
                'desc'          =>  __( 'Enabling this will be shows images of first gallery in post as background slide show in page', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  '0',
                'on-label'      =>  __( 'Yes', 'better-studio' ),
                'off-label'     =>  __( 'No', 'better-studio' ),
            );


            $fields[] = array(
                'name'      =>  __( 'Content Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
                $fields['_content_top_padding'] = array(
                    'name'          =>  __( 'Page Content Top Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  '_content_top_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .post-content',
                                'body .page-content',
                            ),
                            'prop'      => array( 'padding-top' => '%%value%%px' ),
                        )
                    ),
                );
                $fields['_content_bottom_padding'] = array(
                    'name'          =>  __( 'Page Content Bottom Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  '_content_bottom_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .post-content',
                                'body .page-content',
                            ),
                            'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                        )
                    ),
                );
                $fields['_content_left_padding'] = array(
                    'name'          =>  __( 'Page Content Left Padding', 'better-studio' ),
                    'id'            =>  '_content_left_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .post-content',
                                'body .page-content'
                            ),
                            'prop'      => array( 'padding-left' => '%%value%%px' ),
                        )
                    ),
                );
                $fields['_content_right_padding'] = array(
                    'name'          =>  __( 'Page Content Right Padding', 'better-studio' ),
                    'id'            =>  '_content_right_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .post-content',
                                'body .page-content'
                            ),
                            'prop'      => array( 'padding-right' => '%%value%%px' ),
                        )
                    ),
                );



        /**
         * => Header Options
         */
        $fields['_header_options'] = array(
            'name'          =>  __( 'Header', 'better-studio' ),
            'id'            =>  '_header_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-header',
        );
            $fields['_header_show_topbar'] = array(
                'name'          =>  __( 'Display Top Bar', 'better-studio' ),
                'id'            =>  '_header_show_topbar',
                'desc'          =>  __( 'Choose to show or top bar', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_header_show_header'] = array(
                'name'          =>  __( 'Display Header', 'better-studio' ),
                'id'            =>  '_header_show_header',
                'desc'          =>  __( 'Choose to show or header', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'show',
                'options'       => array(
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );
        $fields[] = array(
            'name'      =>  __( 'Main Navigation', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $fields['_main_nav_menu'] = array(
            'name'          =>  __( 'Main Navigation Menu', 'better-studio' ),
            'id'            =>  '_main_nav_menu',
            'desc'          =>  __( 'Select which menu displays on this page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );
        $fields['_main_menu_style'] = array(
            'name'      =>  __( 'Main Navigation Style', 'better-studio' ),
            'id'        => '_main_menu_style',
            'desc'      =>  __( 'Select header menu style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-' . Better_Mag::get_option( 'main_menu_style' ) .'.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal-center.png',
                    'label' =>  __( 'Normal - Center Align', 'better-studio' ),
                ),
                'large' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large.png',
                    'label' =>  __( 'Large', 'better-studio' ),
                ),
                'large-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large-center.png',
                    'label' =>  __( 'Large - Center Align', 'better-studio' ),
                ),
            ),
        );
        $fields['_main_menu_layout'] = array(
            'name'      =>  __( 'Main Navigation Layout', 'better-studio' ),
            'id'        => '_main_menu_layout',
            'desc'      =>  __( 'Select whether you want a boxed or a full width menu. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'boxed' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Boxed', 'better-studio' ),
                ),
                'full-width'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-full-width.png',
                    'label' =>  __( 'Full Width', 'better-studio' ),
                ),
            ),
        );
            $fields[] = array(
                'name'      =>  __( 'Header Background', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
            $fields['_header_bg_color'] = array(
                'name'          =>  __( 'Header Background Color', 'better-studio' ),
                'id'            =>  '_header_bg_color',
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'header_bg_color' ),
                'save-std'      =>  false,
                'desc'          =>  __( 'Setting a header background pattern below will override it.','better-studio'),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .header'
                        ),
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        )
                    )
                )
            );

            $fields['_header_bg_image'] = array(
                'name'          =>  __( 'Header Background Image', 'better-studio' ),
                'id'            =>  '_header_bg_image',
                'type'          =>  'background_image',
                'std'           =>  array( 'img' => '', 'type' => 'cover' ),
                'save-std'      =>  false,
                'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
                'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the header background color option.','better-studio'),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .header'
                        ),
                        'prop'      => array( 'background-image' ),
                        'type'      => 'background-image'
                    )
                ),

            );

            $fields[] = array(
                'name'      =>  __( 'Header Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
                $fields['_header_top_padding'] = array(
                    'name'          =>  __( 'Header Top Padding', 'better-studio' ),
                    'id'            =>  '_header_top_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body.single .header'
                            ),
                            'prop'      => array( 'padding-top' => '%%value%%px' ),
                        )
                    ),
                );
                $fields['_header_bottom_padding'] = array(
                    'name'          =>  __( 'Header Bottom Padding', 'better-studio' ),
                    'id'            =>  '_header_bottom_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value. Values lower than 60px will break the style.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body.single .header'
                            ),
                            'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                        )
                    ),
                );



        /**
         * => Sidebar Options
         */
        $fields['_sidebar_options'] = array(
            'name'          =>  __( 'Sidebar', 'better-studio' ),
            'id'            =>  '_sidebar_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-sidebar',
        );

            $fields['_default_sidebar_layout'] = array(
                'name'          =>  bf_get_admin_current_post_type() == 'post' ? __( 'Post Sidebar Layout', 'better-studio' ) : __( 'Page Sidebar Layout', 'better-studio' ),
                'id'            =>  '_default_sidebar_layout',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select the sidebar layout for page.', 'better-studio' ),
                'options'       =>  array(
                    'default'   =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-' . Better_Mag::get_option( 'default_sidebar_layout' ) . '.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'left'      =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                        'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                    ),
                    'right'     =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                        'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                    ),
                    'no-sidebar'=>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                        'label'     =>  __( 'No Sidebar', 'better-studio' ),
                    ),
                )
            );


        /**
         * => Footer Options
         */
        $fields['_footer_options'] = array(
            'name'          =>  __( 'Footer', 'better-studio' ),
            'id'            =>  '_footer_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-footer',
        );

            $fields['_footer_show_large'] = array(
                'name'          =>  __( 'Display Large Footer', 'better-studio' ),
                'id'            =>  '_footer_show_large',
                'desc'          =>  __( 'Choose to show or hide large footer', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields['_footer_show_lower'] = array(
                'name'          =>  __( 'Display Lower Footer', 'better-studio' ),
                'id'            =>  '_footer_show_lower',
                'desc'          =>  __( 'Choose to show or hide lower footer', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'default',
                'options'       => array(
                    'default'   => __( 'Default', 'better-studio' ),
                    'show'      => __( 'Show', 'better-studio' ),
                    'hide'      => __( 'Hide', 'better-studio' ),
                )
            );

            $fields[] = array(
                'name'      =>  __( 'Large Footer Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
            $fields['_footer_large_top_padding'] = array(
                'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
                'suffix'        =>  __( 'Pixel', 'better-studio' ),
                'id'            =>  '_footer_large_top_padding',
                'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
                'type'          =>  'text',
                'std'           =>  '',
                'css-echo-default'  => false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .footer-larger-wrapper'
                        ),
                        'prop'      => array(
                            'padding-top' => '%%value%%px'
                        ),
                    )
                ),
            );
            $fields['_footer_large_bottom_padding'] = array(
                'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
                'suffix'        =>  __( 'Pixel', 'better-studio' ),
                'id'            =>  '_footer_large_bottom_padding',
                'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
                'type'          =>  'text',
                'std'           =>  '',
                'css-echo-default'  => false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .footer-larger-wrapper'
                        ),
                        'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                    )
                ),
            );

            $fields[] = array(
                'name'      =>  __( 'Lower Footer Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
            $fields['_footer_lower_top_padding'] = array(
                'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
                'suffix'        =>  __( 'Pixel', 'better-studio' ),
                'id'            =>  '_footer_lower_top_padding',
                'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
                'type'          =>  'text',
                'std'           =>  '',
                'css-echo-default'  => false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .footer-lower-wrapper'
                        ),
                        'prop'      => array(
                            'padding-top' => '%%value%%px'
                        ),
                    )
                ),
            );
            $fields['_footer_lower_bottom_padding'] = array(
                'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
                'suffix'        =>  __( 'Pixel', 'better-studio' ),
                'id'            =>  '_footer_lower_bottom_padding',
                'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
                'type'          =>  'text',
                'std'           =>  '',
                'css-echo-default'  => false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .footer-lower-wrapper'
                        ),
                        'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                    )
                ),
            );



        /**
         * => Slider Options
         */
        $fields['_slider_options'] = array(
            'name'          =>  __( 'Slider', 'better-studio' ),
            'id'            =>  '_slider_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-slider',
        );
        $fields['_show_slider'] = array(
            'name'      =>  __( 'Slider Type', 'better-studio' ),
            'desc'      =>  __( 'Select the type of slider that displays.', 'better-studio' ),
            'id'        =>  '_show_slider',
            'std'       =>  'no' ,
            'type'      =>  'select',
            'options'   => array(
                'no'    => __( 'No Slider', 'better-studio' ),
                'better'=> __( 'BetterSlider', 'better-studio' ),
                'rev'   => __( 'Revolution Slider', 'better-studio' ),
            )
        );
        $fields['show_slider'] = array(
            'name'      =>  __( 'BetterSlider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
            $fields['_slider_just_featured'] = array(
                'name'          =>  __( 'Show Only Featured Posts in Slider', 'better-studio' ),
                'id'            =>  '_slider_just_featured',
                'std'           =>  '0' ,
                'type'          =>  'switch',
                'on-label'      =>  __( 'Only Featured', 'better-studio' ),
                'off-label'     =>  __( 'All Posts', 'better-studio' ),
                'desc'          =>  __( 'Turn Off for showing latest posts in slider or On for showing posts that specified as featured posts in slider.', 'better-studio' )
            );

            $fields['_slider_style'] = array(
                'name'          =>  __( 'Slider Style', 'better-studio' ),
                'id'            =>  '_slider_style',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'options'       =>  array(
                    'default' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-' . Better_Mag::get_option( 'slider_style' ) . '.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'style-1' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-1.png',
                        'label'     =>  __( 'Style 1', 'better-studio' ),
                    ),
                    'style-2' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-2.png',
                        'label'     =>  __( 'Style 2', 'better-studio' ),
                    ),
                    'style-3' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-3.png',
                        'label'     =>  __( 'Style 3', 'better-studio' ),
                    ),
                    'style-4' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-4.png',
                        'label'     =>  __( 'Style 4', 'better-studio' ),
                    ),
                    'style-5' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-5.png',
                        'label'     =>  __( 'Style 5', 'better-studio' ),
                    ),
                    'style-6' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-6.png',
                        'label'     =>  __( 'Style 6', 'better-studio' ),
                    ),
                    'style-7' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-7.png',
                        'label'     =>  __( 'Style 7', 'better-studio' ),
                    ),
                    'style-8' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-8.png',
                        'label'     =>  __( 'Style 8', 'better-studio' ),
                    ),
                    'style-9' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-9.png',
                        'label'     =>  __( 'Style 9', 'better-studio' ),
                    ),
                    'style-10' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-10.png',
                        'label'     =>  __( 'Style 10', 'better-studio' ),
                    ),
                )
            );
            $fields['_slider_bg_color'] = array(
                'name'          =>  __( 'Slider Background Color', 'better-studio' ),
                'id'            =>  '_slider_bg_color',
                'desc'          =>  __( 'Customize slider background color.', 'better-studio' ),
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'slider_bg_color' ),
                'save-std'      =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => 'body .main-slider-wrapper' ,
                        'prop'      => array('background-color')
                    )
                ),
            );
            $fields['_slider_cats'] = array(
                'name'          =>  __( 'Filter Slider by Categories', 'better-studio' ),
                'id'            =>  '_slider_cats',
                'type'          =>  'ajax_select',
                'std'           =>  Better_Mag::get_option( 'slider_cats' ),
                'desc'          =>  __( 'Select categories for showing post of them in slider. you can use combination of multiple category and tag.', 'better-studio' ),
                'placeholder'   =>  __("Search and find category...", 'better-studio'),
                "callback"      => 'BF_Ajax_Select_Callbacks::cats_callback',
                "get_name"      => 'BF_Ajax_Select_Callbacks::cat_name',
            );
            $fields['_slider_tags'] = array(
                'name'          =>  __( 'Filter Slider by Tags', 'better-studio' ),
                'id'            =>  '_slider_tags',
                'type'          =>  'ajax_select',
                'std'           =>  Better_Mag::get_option( 'slider_tags' ),
                'desc'          =>  __( 'Select tags for showing post of them in slider. you can use combination of multiple category and tag.', 'better-studio' ),
                'placeholder'   =>  __("Search and find tag...", 'better-studio'),
                "callback"      => 'BF_Ajax_Select_Callbacks::tags_callback',
                "get_name"      => 'BF_Ajax_Select_Callbacks::tag_name',
            );
            $fields[] = array(
                'name'  =>  __( 'Slider Custom Post Type', 'better-studio' ),
                'desc'  =>  __( 'Enter your custom post types here. Separate with ,', 'better-studio' ),
                'id'    =>  'slider_post_type',
                'type'  =>  'text',
                'std'   =>  '',
            );
            $fields[] = array(
                'name'      =>  __( 'Revolution Slider Settings', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'open',
            );
            $fields['_slider_rev_id'] = array(
                'name'      =>  __( 'Select Default Revolution Slider', 'better-studio' ),
                'desc'      =>  __( 'Select the unique name of the slider.', 'better-studio' ),
                'id'        =>  '_slider_rev_id',
                'std'       =>  '0' ,
                'type'      =>  'select',
                'options'   => array(
                        '0'    => __( 'Select A Slider', 'better-studio' ),
                    ) + BF_Query::get_rev_sliders()
            );
            $fields[] = array(
                'name'      =>  __( 'Slider Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
                $fields['_slider_top_padding'] = array(
                    'name'          =>  __( 'Slider Top Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  '_slider_top_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .main-slider-wrapper'
                            ),
                            'prop'      => array(
                                'padding-top' => '%%value%%px'
                            ),
                        )
                    ),
                );
                $fields['_slider_bottom_padding'] = array(
                    'name'          =>  __( 'Slider Bottom Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  '_slider_bottom_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .main-slider-wrapper'
                            ),
                            'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                        )
                    ),
                );

        /**
         * Breadcrumb
         */
        $fields[] = array(
            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
            'id'        =>  'breadcrumb_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link'
        );
        $fields['_breadcrumb_style'] = array(
            'name'      =>  __( 'Breadcrumb Style', 'better-studio' ),
            'id'        => '_breadcrumb_style',
            'desc'      =>  __( 'Select breadcrumb style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-' . Better_Mag::get_option( 'breadcrumb_style' ) . '.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal-center.png',
                    'label' =>  __( 'Center Align', 'better-studio' ),
                ),
            ),
        );


        /**
         * => Custom Javascript / CSS
         */
        $fields['_custom_css_settings'] = array(
            'name'      =>  __( 'Custom CSS' , 'better-studio' ),
            'id'        =>  '_custom_css_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-css3',
            'margin-top'=>  '20',
        );
        $fields['_custom_css_code'] = array(
            'name'      =>  __( 'Custom CSS Code', 'better-studio' ),
            'id'        =>  '_custom_css_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'better-studio' )
        );
        $fields['_custom_css_class'] = array(
            'name'      =>  __( 'Custom Body Class', 'better-studio' ),
            'id'        =>  '_custom_css_class',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added to body.<br> Separate classes with space.', 'better-studio' )
        );
        $fields[] = array(
            'name'          =>  __( 'Responsive CSS', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
            'desc'          =>  'Paste your custom css in the appropriate box, to run only on a specific device',
        );
        $fields['_custom_css_desktop_code'] = array(
            'name'      =>  __( 'Desktop', 'better-studio' ),
            'id'        =>  '_custom_css_desktop_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1200px +', 'better-studio' )
        );
        $fields['_custom_css_ipad_landscape_code'] = array(
            'name'      =>  __( 'iPad Landscape', 'better-studio' ),
            'id'        =>  '_custom_css_ipad_landscape_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1019px - 1199px', 'better-studio' )
        );
        $fields['_custom_css_ipad_portrait_code'] = array(
            'name'      =>  __( 'iPad Portrait', 'better-studio' ),
            'id'        =>  '_custom_css_ipad_portrait_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );
        $fields['_custom_css_phones_code'] = array(
            'name'      =>  __( 'Phones', 'better-studio' ),
            'id'        =>  '_custom_css_phones_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );


        //
        // Support custom post types
        //
        $pages = array( 'post', 'page' );
        if( Better_Mag::get_option( 'advanced_post_options_types' ) != '' )
            $pages = array_merge( explode( ',', Better_Mag::get_option( 'advanced_post_options_types' ) ), $pages );


        /**
         * 3.1. => General Post Options
         */
        $options['better_options'] = array(
            'config' => array(
                'title'         =>  bf_get_admin_current_post_type() == 'page' ? __( 'Better Page Options', 'better-studio' ) : __( 'Better Post Options', 'better-studio' ),
                'pages'         =>  $pages,
                'context'       =>  'normal',
                'prefix'        =>  false,
                'priority'      =>  'high'
            ),
            'panel-id'  => '__better_mag__theme_options',
            'fields' => $fields
        );


        /**
         * 3.3. => WooCommerce Product Page Options
         */
        if( function_exists( 'is_woocommerce' ) ){

            $fields = array();
            $fields['_layout'] = array(
                'name'          =>  __( 'Layout', 'better-studio' ),
                'id'            =>  '_layout',
                'std'           =>  '0' ,
                'type'          =>  'tab',
                'icon'          =>  'bsai-paint',
            );
            $fields['_default_sidebar_layout'] = array(
                'name'          =>  __( 'Sidebar Position', 'better-studio' ),
                'id'            =>  '_default_sidebar_layout',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select the sidebar layout for product. <br><br> <strong>Note:</strong> Default option image shows what style selected for default sidebar layout in theme options.', 'better-studio' ),
                'options'       =>  array(
                    'default'   =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-' . Better_Mag::get_option( 'shop_sidebar_layout' ) . '.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'left'      =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                        'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                    ),
                    'right'     =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                        'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                    ),
                    'no-sidebar'=>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                        'label'     =>  __( 'No Sidebar', 'better-studio' ),
                    ),
                )
            );

            $fields['_style'] = array(
                'name'          =>  __( 'Page Layout', 'better-studio' ),
                'id'            =>  '_style',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select page layout style. <br><br> <strong>Note:</strong> Default option image shows default style that selected for page in theme options.', 'better-studio' ),
                'options'       =>  array(
                    'default'   =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-' . Better_Mag::get_option( 'layout_style' ) .'.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'full-width'=>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                        'label'     =>  __( 'Full Width', 'better-studio' ),
                    ),
                    'boxed'     =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                        'label'     =>  __( 'Boxed', 'better-studio' ),
                    ),
                    'boxed-padded'=> array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                        'label'     =>  __( 'Boxed (Padded)', 'better-studio' ),
                    ),
                )
            );
            $fields['_style_options'] = array(
                'name'          =>  __( 'Page Style', 'better-studio' ),
                'id'            =>  '_style_options',
                'std'           =>  '0' ,
                'type'          =>  'tab',
                'icon'          =>  'bsai-paint',
            );
            $fields['_bg_color'] = array(
                'name'          =>  __( 'Page Background Color', 'better-studio' ),
                'id'            =>  '_bg_color',
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'bg_color' ),
                'save-std'      =>  false,
                'desc'          =>  __( 'Setting a body background image below will override it.', 'better-studio' ),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body',
                            'body.boxed',
                        ),
                        'prop'      => 'background-color'
                    )
                ),
            );

            $fields['_bg_image'] = array(
                'name'          =>  __( 'Page Background Image', 'better-studio' ),
                'id'            =>  '_bg_image',
                'type'          =>  'background_image',
                'std'           =>  '',
                'save-std'      =>  false,
                'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
                'desc'          =>  __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.', 'better-studio' ),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body'
                        ),
                        'prop'      => 'background-image',
                        'type'      => 'background-image'
                    )
                ),
            );
            $fields['_header_options'] = array(
                'name'          =>  __( 'Header', 'better-studio' ),
                'id'            =>  '_header_options',
                'std'           =>  '0' ,
                'type'          =>  'tab',
                'icon'          =>  'bsai-header',
            );
            $fields['_header_bg_color'] = array(
                'name'          =>  __( 'Header Background Color', 'better-studio' ),
                'id'            =>  '_header_bg_color',
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'header_bg_color' ),
                'save-std'      =>  false,
                'desc'          =>  __( 'Setting a header background pattern below will override it.','better-studio'),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .header'
                        ),
                        'prop'      => array(
                            'background-color' => '%%value%%'
                        )
                    )
                )
            );
            $fields['_header_bg_image'] = array(
                'name'          =>  __( 'Header Background Image', 'better-studio' ),
                'id'            =>  '_header_bg_image',
                'type'          =>  'background_image',
                'std'           =>  array( 'img' => '', 'type' => 'cover' ),
                'save-std'      =>  false,
                'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
                'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the header background color option.','better-studio'),
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            'body .header'
                        ),
                        'prop'      => array( 'background-image' ),
                        'type'      => 'background-image'
                    )
                ),

            );
            $options['woocommerce_layout_metabox'] = array(
                'config'    =>  array(
                    'title'     =>  __( 'Better Product Options', 'better-studio' ),
                    'pages'     =>  array( 'product' ),
                    'context'   =>  'normal',
                    'prefix'    =>  false,
                    'priority'  =>  'high'
                ),
                'fields' => $fields,
                'panel-id'  => '__better_mag__theme_options',
            );
        }

        return $options;

    } //setup_bf_metabox


    /**
     * Setup custom taxonomy options for BetterMag
     *
     * @param $options
     * @return array
     */
    function taxonomy_options( $options ){

        /**
         * 4. => Taxonomy Options
         */

        /**
         * 4.1. => Category Options
         */

        /**
         * => Style
         */
        $fields[] = array(
            'name'      =>  __( 'Style' , 'better-studio' ),
            'id'        =>  'tab_style',
            'type'      =>  'tab',
            'icon'      =>  'bsai-paint',
        );

        $fields['term_color'] = array(
            'name'          =>  __( 'Category Color', 'better-studio' ),
            'id'            =>  'term_color',
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'theme_color' ),
            'save-std'      =>  false,
            'desc'          =>  __( 'This color will be used in several areas such as navigation and listing blocks.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .the-content a:hover',
                        '.block-modern.main-term-%%id%% .rating-stars span:before',
                        '.blog-block.main-term-%%id%% .rating-stars span:before',
                        '.block-highlight.main-term-%%id%% .rating-stars span:before',
                        '.listing-thumbnail li.main-term-%%id%% .rating-stars span:before',
                        '.widget .tab-read-more.term-%%id%% a:hover',
                        '.tab-content-listing .tab-read-more.term-%%id%% a',
                    ),
                    'prop'      => array(
                        'color' =>   '%%value%%'
                    )
                ),
                array(
                    'selector'  => array(
                        '.main-menu .menu > li.menu-term-%%id%%:hover > a',
                        '.main-menu .menu > li.current-menu-ancestor.menu-term-%%id%% > a',
                        '.main-menu .menu > li.current-menu-parent.menu-term-%%id%% > a',
                        '.main-menu .menu > li.current-menu-item.menu-term-%%id%% > a',
                        '.section-heading.tab-heading.active-term-%%id%%',
                        '.section-heading.term-%%id%%',
                        '.section-heading.extended.tab-heading.term-%%id%%',
                        'body.category-%%id%% .widget.widget_recent_comments a:hover',
                    ),
                    'prop'      => array(
                        'border-bottom-color' =>   '%%value%%'
                    )
                ),
                array(
                    'selector'  => array(
                        '.term-title.term-%%id%% a',
                        'body.category-%%id%% .main-slider-wrapper .flex-control-nav li a.flex-active,body.category-%%id%% .main-slider-wrapper .flex-control-nav li:hover a',
                        'body.category-%%id%% .page-heading:before',
                        'body.category-%%id%% .btn-read-more',
                        '.section-heading.term-%%id%% span.h-title',
                        '.section-heading.extended.tab-heading li.other-item.main-term.active.term-%%id%% a',
                        '.section-heading.extended.tab-heading li.other-item.term-%%id%%:hover a',
                        '.section-heading.extended.tab-heading.term-%%id%% .other-links .other-item.active a',
                        '.section-heading.extended.term-%%id%% .other-links .other-item.listing-read-more a:hover',
                    ),
                    'prop'      => array(
                        'background-color'  =>   '%%value%%',
                        'color'             =>   '#FFF',
                    )
                ),
                array(
                    'selector'  => array(
                        '.blog-block.main-term-%%id%% .btn-read-more',
                        '.block-modern.main-term-%%id%% .rating-bar span',
                        '.blog-block.main-term-%%id%% .rating-bar span',
                        '.block-highlight.main-term-%%id%% .rating-bar span',
                        '.listing-thumbnail li.main-term-%%id%% .rating-bar span',
                    ),
                    'prop'      => array(
                        'background-color'  =>   '%%value%%',
                    )
                ),
                array(
                    'selector'  =>  array(
                        '.widget.widget_nav_menu li.menu-term-%%id%% > a:hover',
                    ),
                    'prop'      =>  array(
                        'border-color' => "%%value%%",
                        'background-color'  =>   '%%value%%',
                    )
                ),
                array(
                    'selector'  =>  array(
                        'body.category-%%id%% ::selection',
                    ),
                    'prop'      =>  array( 'background' )
                ),
                array(
                    'selector'  =>  array(
                        'body.category-%%id%% ::-moz-selection'
                    ),
                    'prop'      =>  array( 'background' )
                ),

            ),
        );
        $fields['listing_style'] = array(
            'name'          =>  __( 'Category Listing', 'better-studio' ),
            'id'            =>  'listing_style',
            'std'           =>   'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'This style used when browsing category archive page. Default option image shows what default style selected in theme options.', 'better-studio' ),
            'options'       =>  array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-' . Better_Mag::get_option( 'categories_listing_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'blog' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     => __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     => __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );

        $fields['layout_style'] = array(
            'name'          =>  __( 'Category Page Layout', 'better-studio' ),
            'id'            =>  'layout_style',
            'std'           =>  'default',
            'save_default'  =>  false,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select whether you want a boxed or a full width layout. Default option image shows what default style selected in theme options.', 'better-studio' ),
            'options'       =>  array(
                'default'   =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-' . Better_Mag::get_option( 'layout_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'full-width' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                    'label'     =>  __( 'Full Width', 'better-studio' ),
                ),
                'boxed' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                    'label'     =>  __( 'Boxed', 'better-studio' ),
                ),
                'boxed-padded' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                    'label'     =>  __( 'Boxed (Padded)', 'better-studio' ),
                ),
            )
        );
        $fields['term_posts_count'] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'term_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>Default: %s', 'better-studio' ), Better_Mag::get_option( 'archive_cat_posts_count' ) != '' ? Better_Mag::get_option( 'archive_cat_posts_count' ) : get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $fields['show_term_pagination'] = array(
            'name'          =>  __( 'Show Pagination', 'better-studio' ),
            'id'            =>  'show_term_pagination',
            'type'          =>  'switch',
            'std'           =>  '1',
            'on-label'      =>  __( 'Show', 'better-studio' ),
            'off-label'     =>  __( 'Hide', 'better-studio' ),
            'desc'          =>  __( 'Chose to show or hide pagination in category archive', 'better-studio' ),
        );
        $fields['show_rss_link'] = array(
            'name'          =>  __( 'Show RSS Link', 'better-studio' ),
            'id'            =>  'show_rss_link',
            'desc'          =>  __( 'Display RSS icon alongside category title.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['bottom_description'] = array(
            'name'          =>  __( 'Category Bottom Description', 'better-studio' ),
            'id'            =>  'bottom_description',
            'desc'          =>  __( 'You can add some description to bottom of category page right after pagination.<br> You can add HTML tags.', 'better-studio' ),
            'type'          =>  'textarea',
            'std'           =>  '',
        );
        $fields[] = array(
            'name'      =>  __( 'Background Style' , 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $fields['bg_color'] = array(
            'name'      =>  __( 'Body Background Color', 'better-studio' ),
            'id'        =>  'bg_color',
            'type'      =>  'color',
            'std'       =>  Better_Mag::get_option( 'bg_color' ),
            'save-std'  =>  false,
            'desc'      =>  __( 'Setting a body background image below will override it.', 'better-studio' ),
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%%',
                    ),
                    'prop'      => array(
                        'background-color' =>   '%%value%%'
                    )
                ),
            )
        );

        $fields['bg_image'] = array(
            'name'      => __('Body Background Image','better-studio'),
            'id'        => 'bg_image',
            'type'      => 'background_image',
            'std'       => '',
            'upload_label'=> __( 'Upload Image', 'better-studio' ),
            'desc'      => __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.','better-studio'),
            'css'       => array(
                array(
                    'selector'  => array(
                        'body.category-%%id%%'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            )
        );

        /**
         * => Title
         */
        $fields[] = array(
            'name'      =>  __( 'Title' , 'better-studio' ),
            'id'        =>  'tab_title',
            'type'      =>  'tab',
            'icon'      =>  'bsai-title',
        );
        $fields['term_custom_title'] = array(
            'name'          =>  __( 'Custom Category Title', 'better-studio' ),
            'id'            =>  'term_custom_title',
            'type'          =>  'text',
            'std'           =>  '',
            'desc'          =>  __( 'Change category title or leave empty for default title', 'better-studio' ),
        );
        $fields['hide_term_title'] = array(
            'name'          =>  __( 'Hide Category Title', 'better-studio' ),
            'id'            =>  'hide_term_title',
            'type'          =>  'switch',
            'std'           =>  '0',
            'on-label'      =>  __( 'Yes', 'better-studio' ),
            'off-label'     =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enable this for hiding category title', 'better-studio' ),
        );
        $fields['hide_term_description'] = array(
            'name'          =>  __( 'Hide Category Description', 'better-studio' ),
            'id'            =>  'hide_term_description',
            'type'          =>  'switch',
            'std'           =>  '0',
            'on-label'      =>  __( 'Yes', 'better-studio' ),
            'off-label'     =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enable this for hiding category description', 'better-studio' ),
        );


        /**
         * => Header Options
         */
        $fields['header_options'] = array(
            'name'          =>  __( 'Header', 'better-studio' ),
            'id'            =>  'header_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-header',
        );

        $fields['header_show_topbar'] = array(
            'name'          =>  __( 'Display Top Bar', 'better-studio' ),
            'id'            =>  'header_show_topbar',
            'desc'          =>  __( 'Choose to show or top bar', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['header_show_header'] = array(
            'name'          =>  __( 'Display Header', 'better-studio' ),
            'id'            =>  'header_show_header',
            'desc'          =>  __( 'Choose to show or header', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'show',
            'options'       => array(
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'Main Navigation', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $fields['main_nav_menu'] = array(
            'name'          =>  __( 'Main Navigation Menu', 'better-studio' ),
            'id'            =>  'main_nav_menu',
            'desc'          =>  __( 'Select which menu displays on this page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );
        $fields['main_menu_style'] = array(
            'name'      =>  __( 'Main Navigation Style', 'better-studio' ),
            'id'        => 'main_menu_style',
            'desc'      =>  __( 'Select header menu style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-' . Better_Mag::get_option( 'main_menu_style' ) .'.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal-center.png',
                    'label' =>  __( 'Normal - Center Align', 'better-studio' ),
                ),
                'large' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large.png',
                    'label' =>  __( 'Large', 'better-studio' ),
                ),
                'large-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large-center.png',
                    'label' =>  __( 'Large - Center Align', 'better-studio' ),
                ),
            ),
        );
        $fields['main_menu_layout'] = array(
            'name'      =>  __( 'Main Navigation Layout', 'better-studio' ),
            'id'        => 'main_menu_layout',
            'desc'      =>  __( 'Select whether you want a boxed or a full width menu. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'boxed' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Boxed', 'better-studio' ),
                ),
                'full-width'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-full-width.png',
                    'label' =>  __( 'Full Width', 'better-studio' ),
                ),
            ),
        );
        $fields[] = array(
            'name'      =>  __( 'Category Custom Logo', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['logo_text'] = array(
            'name'          =>  __( 'Logo Text', 'better-studio' ),
            'id'            =>  'logo_text',
            'desc'          =>  __( 'The desired text will be used if logo images are not provided below.', 'better-studio' ),
            'std'           =>  '',
            'type'          =>  'text',
            'save-std'      =>  false,
        );
        $fields['logo_image'] = array(
            'name'          =>  __( 'Logo Image', 'better-studio' ),
            'id'            =>  'logo_image',
            'desc'          =>  __( 'By default, a text-based logo is created using your site title. But you can also upload an image-based logo here.', 'better-studio' ),
            'std'           =>  Better_Mag::get_option( 'logo_image' ),
            'type'          =>  'media_image',
            'media_title'   =>  __( 'Select or Upload Logo', 'better-studio'),
            'media_button'  =>  __( 'Select Image', 'better-studio'),
            'upload_label'  =>  __( 'Upload Logo', 'better-studio'),
            'remove_label'  =>  __( 'Remove Logo', 'better-studio'),
            'save-std'      =>  false,
        );
        $fields['logo_image_retina'] = array(
            'name'          =>  __( 'Logo Image Retina (2x)', 'better-studio' ),
            'id'            =>  'logo_image_retina',
            'desc'          =>  __( 'If you want to upload a Retina Image, It\'s Image Size should be exactly double in compare with your normal Logo. It requires WP Retina 2x plugin.', 'better-studio' ),
            'std'           =>  Better_Mag::get_option( 'logo_image_retina' ),
            'type'          =>  'media_image',
            'media_title'   =>  __( 'Select or Upload Retina Logo', 'better-studio'),
            'media_button'  =>  __( 'Select Retina Image', 'better-studio'),
            'upload_label'  =>  __( 'Upload Retina Logo', 'better-studio'),
            'remove_label'  =>  __( 'Remove Retina Logo', 'better-studio'),
            'save-std'      =>  false,
        );
        $fields[] = array(
            'name'      =>  __( 'Header Background', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_bg_color'] = array(
            'name'          =>  __( 'Header Background Color', 'better-studio' ),
            'id'            =>  'header_bg_color',
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'header_bg_color' ),
            'save-std'      =>  false,
            'desc'          =>  __( 'Setting a header background pattern below will override it.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .header'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            )
        );

        $fields['header_bg_image'] = array(
            'name'          =>  __( 'Header Background Image', 'better-studio' ),
            'id'            =>  'header_bg_image',
            'type'          =>  'background_image',
            'std'           =>  array( 'img' => '', 'type' => 'cover' ),
            'save-std'      =>  false,
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the header background color option.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .header'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            ),

        );

        $fields[] = array(
            'name'      =>  __( 'Header Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_top_padding'] = array(
            'name'          =>  __( 'Header Top Padding', 'better-studio' ),
            'id'            =>  'header_top_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .header'
                    ),
                    'prop'      => array( 'padding-top' => '%%value%%px' ),
                )
            ),
        );
        $fields['header_bottom_padding'] = array(
            'name'          =>  __( 'Header Bottom Padding', 'better-studio' ),
            'id'            =>  'header_bottom_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value. Values lower than 60px will break the style.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .header'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );


        /**
         * => Sidebar
         */
        $fields[] = array(
            'name'      =>  __( 'Sidebar' , 'better-studio' ),
            'id'        =>  'tab_sidebar',
            'type'      =>  'tab',
            'icon'      =>  'bsai-sidebar',
        );

            $fields['sidebar_layout'] = array(
                'name'          =>  __( 'Sidebar Layout', 'better-studio' ),
                'id'            =>  'sidebar_layout',
                'std'           =>  'default',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select the sidebar layout to use by default. This can be overridden per-page, per-post and per category.', 'better-studio' ),
                'options'       => array(
                    'default' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-' . Better_Mag::get_option( 'default_sidebar_layout' ) . '.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'left'      =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                        'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                    ),
                    'right'     =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                        'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                    ),
                    'no-sidebar'=>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                        'label'     =>  __( 'No Sidebar', 'better-studio' ),
                    ),
                )
            );


        /**
         * => Footer Options
         */
        $fields['footer_options'] = array(
            'name'          =>  __( 'Footer', 'better-studio' ),
            'id'            =>  'footer_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-footer',
        );

        $fields['footer_show_large'] = array(
            'name'          =>  __( 'Display Large Footer', 'better-studio' ),
            'id'            =>  'footer_show_large',
            'desc'          =>  __( 'Choose to show or hide large footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );

        $fields['footer_show_lower'] = array(
            'name'          =>  __( 'Display Lower Footer', 'better-studio' ),
            'id'            =>  'footer_show_lower',
            'desc'          =>  __( 'Choose to show or hide lower footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );

        $fields[] = array(
            'name'      =>  __( 'Large Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_large_top_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_large_bottom_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        $fields[] = array(
            'name'      =>  __( 'Lower Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_lower_top_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_lower_bottom_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        /**
         * => Slider
         */
        $fields[] = array(
            'name'      =>  __( 'Slider' , 'better-studio' ),
            'id'        =>  'tab_slider',
            'type'      =>  'tab',
            'icon'      =>  'bsai-slider',
        );

        $fields['show_slider'] = array(
            'name'      =>  __( 'Slider Type', 'better-studio' ),
            'desc'      =>  __( 'Select the type of slider that displays.', 'better-studio' ),
            'id'        =>  'show_slider',
            'std'       =>  'no' ,
            'type'      =>  'select',
            'options'   => array(
                'no'    => __( 'No Slider', 'better-studio' ),
                'better'=> __( 'BetterSlider', 'better-studio' ),
                'rev'   => __( 'Revolution Slider', 'better-studio' ),
            )
        );

        $fields[] = array(
            'name'      =>  __( 'BetterSlider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
            $fields['slider_just_featured'] = array(
                'name'          =>  __( 'Show Only Featured Posts in Slider', 'better-studio' ),
                'id'            =>  'slider_just_featured',
                'std'           =>  '1' ,
                'type'          =>  'switch',
                'desc'          =>  __( 'Turn Off for showing latest posts of category in slider or On for showing posts that specified as featured post in this category as slider.', 'better-studio' )
            );

            $fields['slider_style'] = array(
                'name'          =>  __( 'Slider Style', 'better-studio' ),
                'desc'          =>  __( 'Select slider style', 'better-studio' ),
                'id'            =>  'slider_style',
                'std'           =>  'default',
                'save_default'  =>  false,
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'options'       =>  array(
                    'default' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-' . Better_Mag::get_option( 'slider_style' ) . '.png',
                        'label'     =>  __( 'Default', 'better-studio' ),
                    ),
                    'style-1' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-1.png',
                        'label'     =>  __( 'Style 1', 'better-studio' ),
                    ),
                    'style-2' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-2.png',
                        'label'     =>  __( 'Style 2', 'better-studio' ),
                    ),
                    'style-3' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-3.png',
                        'label'     =>  __( 'Style 3', 'better-studio' ),
                    ),
                    'style-4' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-4.png',
                        'label'     =>  __( 'Style 4', 'better-studio' ),
                    ),
                    'style-5' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-5.png',
                        'label'     =>  __( 'Style 5', 'better-studio' ),
                    ),
                    'style-6' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-6.png',
                        'label'     =>  __( 'Style 6', 'better-studio' ),
                    ),
                    'style-7' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-7.png',
                        'label'     =>  __( 'Style 7', 'better-studio' ),
                    ),
                    'style-8' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-8.png',
                        'label'     =>  __( 'Style 8', 'better-studio' ),
                    ),
                    'style-9' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-9.png',
                        'label'     =>  __( 'Style 9', 'better-studio' ),
                    ),
                    'style-10' =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-10.png',
                        'label'     =>  __( 'Style 10', 'better-studio' ),
                    ),
                )
            );

            $fields['slider_bg_color'] = array(
                'name'          =>  __( 'Slider Background Color', 'better-studio' ),
                'id'            =>  'slider_bg_color',
                'desc'          =>  __( 'Customize slider background color.', 'better-studio' ),
                'type'          =>  'color',
                'std'           =>  Better_Mag::get_option( 'slider_bg_color' ),
                'save-std'      =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => 'body.category-%%id%% .main-slider-wrapper' ,
                        'prop'      => array('background-color')
                    )
                ),
            );
        $fields['slider_tags'] = array(
            'name'          =>  __( 'Filter Slider by Tags', 'better-studio' ),
            'id'            =>  'slider_tags',
            'type'          =>  'ajax_select',
            'std'           =>  '',
            'desc'          =>  __( 'Select tags for showing post of them in slider.', 'better-studio' ),
            'placeholder'   =>  __("Search and find tag...", 'better-studio'),
            "callback"      => 'BF_Ajax_Select_Callbacks::tags_callback',
            "get_name"      => 'BF_Ajax_Select_Callbacks::tag_name',
        );
        $fields[] = array(
            'name'  =>  __( 'Slider Custom Post Type', 'better-studio' ),
            'desc'  =>  __( 'Enter your custom post types here. Separate with ,', 'better-studio' ),
            'id'    =>  'slider_post_type',
            'type'  =>  'text',
            'std'   =>  '',
        );
        $fields[] = array(
            'name'      =>  __( 'Revolution Slider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $fields['slider_rev_id'] = array(
            'name'      =>  __( 'Select Default Revolution Slider', 'better-studio' ),
            'desc'      =>  __( 'Select the unique name of the slider.', 'better-studio' ),
            'id'        =>  'slider_rev_id',
            'std'       =>  '0' ,
            'type'      =>  'select',
            'options'   => array(
                    '0'    => __( 'Select A Slider', 'better-studio' ),
                ) + BF_Query::get_rev_sliders()
        );
        $fields[] = array(
            'name'      =>  __( 'Slider Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['slider_top_padding'] = array(
            'name'          =>  __( 'Slider Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .main-slider-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['slider_bottom_padding'] = array(
            'name'          =>  __( 'Slider Bottom Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.category-%%id%% .main-slider-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        /**
         * Breadcrumb
         */
        $fields[] = array(
            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
            'id'        =>  'breadcrumb_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link'
        );
        $fields['breadcrumb_style'] = array(
            'name'      =>  __( 'Breadcrumb Style', 'better-studio' ),
            'id'        => 'breadcrumb_style',
            'desc'      =>  __( 'Select breadcrumb style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-' . Better_Mag::get_option( 'breadcrumb_style' ) . '.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal-center.png',
                    'label' =>  __( 'Center Align', 'better-studio' ),
                ),
            ),
        );

        /**
         * => Custom Javascript / CSS
         */
        $fields['custom_css_settings'] = array(
            'name'      =>  __( 'Custom CSS' , 'better-studio' ),
            'id'        =>  'custom_css_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-css3',
            'margin-top'=>  '20',
        );
        $fields['custom_css_code'] = array(
            'name'      =>  __( 'Custom CSS Code', 'better-studio' ),
            'id'        =>  'custom_css_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'better-studio' )
        );
        $fields['custom_css_class'] = array(
            'name'      =>  __( 'Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added to body.<br> Separate classes with space.', 'better-studio' )
        );
        $fields[] = array(
            'name'          =>  __( 'Responsive CSS', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
            'desc'          =>  'Paste your custom css in the appropriate box, to run only on a specific device',
        );
        $fields['custom_css_desktop_code'] = array(
            'name'      =>  __( 'Desktop', 'better-studio' ),
            'id'        =>  'custom_css_desktop_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1200px +', 'better-studio' )
        );
        $fields['custom_css_ipad_landscape_code'] = array(
            'name'      =>  __( 'iPad Landscape', 'better-studio' ),
            'id'        =>  'custom_css_ipad_landscape_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1019px - 1199px', 'better-studio' )
        );
        $fields['custom_css_ipad_portrait_code'] = array(
            'name'      =>  __( 'iPad Portrait', 'better-studio' ),
            'id'        =>  'custom_css_ipad_portrait_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );
        $fields['custom_css_phones_code'] = array(
            'name'      =>  __( 'Phones', 'better-studio' ),
            'id'        =>  'custom_css_phones_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );


        //
        // Support to custom taxonomies
        //
        $cat_taxonomies = array( 'category' );
        if( Better_Mag::get_option( 'advanced_catgeory_options_tax' ) != '' )
            $cat_taxonomies = array_merge( explode( ',', Better_Mag::get_option( 'advanced_catgeory_options_tax' ) ), $cat_taxonomies );

        $options[] = array(
            'config' => array(
                'taxonomies'    => $cat_taxonomies,
                'name'          => __( 'Better Category Options', 'better-studio' )
            ),
            'panel-id'  => '__better_mag__theme_options',

            'fields' => $fields
        );


        /**
         * 4.2. => Tag Options
         */
        $fields = array();

        /**
         * => Style
         */
        $fields[] = array(
            'name'      =>  __( 'Style' , 'better-studio' ),
            'id'        =>  'tab_style',
            'type'      =>  'tab',
            'icon'      =>  'bsai-paint',
        );
        $fields['listing_style'] = array(
            'name'          =>  __( 'Tag Listing', 'better-studio' ),
            'id'            =>  'listing_style',
            'std'           =>   'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'This style used when browsing tag archive page. Default option image shows what default style selected in theme options.', 'better-studio' ),
            'options'       =>  array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-' . Better_Mag::get_option( 'tags_listing_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'blog' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     => __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     => __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $fields['layout_style'] = array(
            'name'          =>  __( 'Tag Page Layout', 'better-studio' ),
            'id'            =>  'layout_style',
            'std'           =>  'default',
            'save_default'  =>  false,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select whether you want a boxed or a full width layout. Default option image shows what default style selected in theme options.', 'better-studio' ),
            'options'       =>  array(
                'default'   =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-' . Better_Mag::get_option( 'layout_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'full-width' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                    'label'     =>  __( 'Full Width', 'better-studio' ),
                ),
                'boxed' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                    'label'     =>  __( 'Boxed', 'better-studio' ),
                ),
                'boxed-padded' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                    'label'     =>  __( 'Boxed (Padded)', 'better-studio' ),
                ),
            )
        );
        $fields['term_posts_count'] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'term_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>Default: %s', 'better-studio' ), Better_Mag::get_option( 'archive_tag_posts_count' ) != '' ? Better_Mag::get_option( 'archive_tag_posts_count' ) : get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $fields['show_term_pagination'] = array(
            'name'          =>  __( 'Show Pagination', 'better-studio' ),
            'id'            =>  'show_term_pagination',
            'type'          =>  'switch',
            'std'           =>  '1',
            'on-label'      =>  __( 'Show', 'better-studio' ),
            'off-label'     =>  __( 'Hide', 'better-studio' ),
            'desc'          =>  __( 'Chose to show or hide pagination in tag archive', 'better-studio' ),
        );
        $fields['show_rss_link'] = array(
            'name'          =>  __( 'Show RSS Link', 'better-studio' ),
            'id'            =>  'show_rss_link',
            'desc'          =>  __( 'Display RSS icon alongside tag title.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['bottom_description'] = array(
            'name'          =>  __( 'Tag Bottom Description', 'better-studio' ),
            'id'            =>  'bottom_description',
            'desc'          =>  __( 'You can add some description to bottom of tag page right after pagination.<br> You can add HTML tags.', 'better-studio' ),
            'type'          =>  'textarea',
            'std'           =>  '',
        );
        $fields[] = array(
            'name'      =>  __( 'Background Style' , 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $fields['bg_color'] = array(
            'name'      =>  __( 'Body Background Color', 'better-studio' ),
            'id'        =>  'bg_color',
            'type'      =>  'color',
            'std'       =>  Better_Mag::get_option( 'bg_color' ),
            'save-std'  =>  false,
            'desc'      =>  __( 'Setting a body background image below will override it.', 'better-studio' ),
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%%',
                    ),
                    'prop'      => array(
                        'background-color' =>   '%%value%%'
                    )
                ),
            )
        );
        $fields['bg_image'] = array(
            'name'      => __('Body Background Image','better-studio'),
            'id'        => 'bg_image',
            'type'      => 'background_image',
            'std'       => '',
            'upload_label'=> __( 'Upload Image', 'better-studio' ),
            'desc'      => __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.','better-studio'),
            'css'       => array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%%'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            )
        );

        /**
         * => Title
         */
        $fields[] = array(
            'name'      =>  __( 'Title' , 'better-studio' ),
            'id'        =>  'tab_title',
            'type'      =>  'tab',
            'icon'      =>  'bsai-title',
        );
        $fields['term_custom_title'] = array(
            'name'          =>  __( 'Custom Tag Title', 'better-studio' ),
            'id'            =>  'term_custom_title',
            'type'          =>  'text',
            'std'           =>  '',
            'desc'          =>  __( 'Change tag title or leave empty for default title', 'better-studio' ),
        );
        $fields['hide_term_title'] = array(
            'name'          =>  __( 'Hide Tag Title', 'better-studio' ),
            'id'            =>  'hide_term_title',
            'type'          =>  'switch',
            'std'           =>  '0',
            'on-label'      =>  __( 'Yes', 'better-studio' ),
            'off-label'     =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enable this for hiding tag title', 'better-studio' ),
        );
        $fields['hide_term_description'] = array(
            'name'          =>  __( 'Hide Tag Description', 'better-studio' ),
            'id'            =>  'hide_term_description',
            'type'          =>  'switch',
            'std'           =>  '0',
            'on-label'      =>  __( 'Yes', 'better-studio' ),
            'off-label'     =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enable this for hiding tag description', 'better-studio' ),
        );


        /**
         * => Header Options
         */
        $fields['header_options'] = array(
            'name'          =>  __( 'Header', 'better-studio' ),
            'id'            =>  'header_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-header',
        );
        $fields['header_show_topbar'] = array(
            'name'          =>  __( 'Display Top Bar', 'better-studio' ),
            'id'            =>  'header_show_topbar',
            'desc'          =>  __( 'Choose to show or top bar', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['header_show_header'] = array(
            'name'          =>  __( 'Display Header', 'better-studio' ),
            'id'            =>  'header_show_header',
            'desc'          =>  __( 'Choose to show or header', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'show',
            'options'       => array(
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'Main Navigation', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $fields['main_nav_menu'] = array(
            'name'          =>  __( 'Main Navigation Menu', 'better-studio' ),
            'id'            =>  'main_nav_menu',
            'desc'          =>  __( 'Select which menu displays on this page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );
        $fields['main_menu_style'] = array(
            'name'      =>  __( 'Main Navigation Style', 'better-studio' ),
            'id'        => 'main_menu_style',
            'desc'      =>  __( 'Select header menu style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-' . Better_Mag::get_option( 'main_menu_style' ) .'.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal-center.png',
                    'label' =>  __( 'Normal - Center Align', 'better-studio' ),
                ),
                'large' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large.png',
                    'label' =>  __( 'Large', 'better-studio' ),
                ),
                'large-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large-center.png',
                    'label' =>  __( 'Large - Center Align', 'better-studio' ),
                ),
            ),
        );
        $fields['main_menu_layout'] = array(
            'name'      =>  __( 'Main Navigation Layout', 'better-studio' ),
            'id'        => 'main_menu_layout',
            'desc'      =>  __( 'Select whether you want a boxed or a full width menu. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'boxed' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Boxed', 'better-studio' ),
                ),
                'full-width'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-full-width.png',
                    'label' =>  __( 'Full Width', 'better-studio' ),
                ),
            ),
        );
        $fields[] = array(
            'name'      =>  __( 'Tag Custom Logo', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['logo_text'] = array(
            'name'          =>  __( 'Logo Text', 'better-studio' ),
            'id'            =>  'logo_text',
            'desc'          =>  __( 'The desired text will be used if logo images are not provided below.', 'better-studio' ),
            'std'           =>  Better_Mag::get_option( 'logo_text' ),
            'type'          =>  'text',
            'save-std'      =>  false,
        );
        $fields['logo_image'] = array(
            'name'          =>  __( 'Logo Image', 'better-studio' ),
            'id'            =>  'logo_image',
            'desc'          =>  __( 'By default, a text-based logo is created using your site title. But you can also upload an image-based logo here.', 'better-studio' ),
            'std'           =>  Better_Mag::get_option( 'logo_image' ),
            'type'          =>  'media_image',
            'media_title'   =>  __( 'Select or Upload Logo', 'better-studio'),
            'media_button'  =>  __( 'Select Image', 'better-studio'),
            'upload_label'  =>  __( 'Upload Logo', 'better-studio'),
            'remove_label'  =>  __( 'Remove Logo', 'better-studio'),
            'save-std'      =>  false,
        );
        $fields['logo_image_retina'] = array(
            'name'          =>  __( 'Logo Image Retina (2x)', 'better-studio' ),
            'id'            =>  'logo_image_retina',
            'desc'          =>  __( 'If you want to upload a Retina Image, It\'s Image Size should be exactly double in compare with your normal Logo. It requires WP Retina 2x plugin.', 'better-studio' ),
            'std'           =>  Better_Mag::get_option( 'logo_image_retina' ),
            'type'          =>  'media_image',
            'media_title'   =>  __( 'Select or Upload Retina Logo', 'better-studio'),
            'media_button'  =>  __( 'Select Retina Image', 'better-studio'),
            'upload_label'  =>  __( 'Upload Retina Logo', 'better-studio'),
            'remove_label'  =>  __( 'Remove Retina Logo', 'better-studio'),
            'save-std'      =>  false,
        );
        $fields[] = array(
            'name'      =>  __( 'Header Background', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_bg_color'] = array(
            'name'          =>  __( 'Header Background Color', 'better-studio' ),
            'id'            =>  'header_bg_color',
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'header_bg_color' ),
            'save-std'      =>  false,
            'desc'          =>  __( 'Setting a header background pattern below will override it.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .header'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            )
        );

        $fields['header_bg_image'] = array(
            'name'          =>  __( 'Header Background Image', 'better-studio' ),
            'id'            =>  'header_bg_image',
            'type'          =>  'background_image',
            'std'           =>  array( 'img' => '', 'type' => 'cover' ),
            'save-std'      =>  false,
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the header background color option.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .header'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            ),

        );

        $fields[] = array(
            'name'      =>  __( 'Header Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_top_padding'] = array(
            'name'          =>  __( 'Header Top Padding', 'better-studio' ),
            'id'            =>  'header_top_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .header'
                    ),
                    'prop'      => array( 'padding-top' => '%%value%%px' ),
                )
            ),
        );
        $fields['header_bottom_padding'] = array(
            'name'          =>  __( 'Header Bottom Padding', 'better-studio' ),
            'id'            =>  'header_bottom_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value. Values lower than 60px will break the style.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .header'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );


        /**
         * => Sidebar
         */
        $fields[] = array(
            'name'      =>  __( 'Sidebar' , 'better-studio' ),
            'id'        =>  'tab_sidebar',
            'type'      =>  'tab',
            'icon'      =>  'bsai-sidebar',
        );

        $fields['sidebar_layout'] = array(
            'name'          =>  __( 'Sidebar Layout', 'better-studio' ),
            'id'            =>  'sidebar_layout',
            'std'           =>  'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select the sidebar layout for tag.', 'better-studio' ),
            'options'       => array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-' . Better_Mag::get_option( 'default_sidebar_layout' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'left'      =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                    'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                ),
                'right'     =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                    'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                ),
                'no-sidebar'=>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                    'label'     =>  __( 'No Sidebar', 'better-studio' ),
                ),
            )
        );


        /**
         * => Footer Options
         */
        $fields['footer_options'] = array(
            'name'          =>  __( 'Footer', 'better-studio' ),
            'id'            =>  'footer_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-footer',
        );

        $fields['footer_show_large'] = array(
            'name'          =>  __( 'Display Large Footer', 'better-studio' ),
            'id'            =>  'footer_show_large',
            'desc'          =>  __( 'Choose to show or hide large footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );

        $fields['footer_show_lower'] = array(
            'name'          =>  __( 'Display Lower Footer', 'better-studio' ),
            'id'            =>  'footer_show_lower',
            'desc'          =>  __( 'Choose to show or hide lower footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );

        $fields[] = array(
            'name'      =>  __( 'Large Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_large_top_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_large_bottom_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        $fields[] = array(
            'name'      =>  __( 'Lower Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_lower_top_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_lower_bottom_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );


        /**
         * => Slider
         */
        $fields[] = array(
            'name'      =>  __( 'Slider' , 'better-studio' ),
            'id'        =>  'tab_slider',
            'type'      =>  'tab',
            'icon'      =>  'bsai-slider',
        );
        $fields['show_slider'] = array(
            'name'      =>  __( 'Slider Type', 'better-studio' ),
            'desc'      =>  __( 'Select the type of slider that displays.', 'better-studio' ),
            'id'        =>  'show_slider',
            'std'       =>  'no' ,
            'type'      =>  'select',
            'options'   => array(
                'no'    => __( 'No Slider', 'better-studio' ),
                'better'=> __( 'BetterSlider', 'better-studio' ),
                'rev'   => __( 'Revolution Slider', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'BetterSlider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $fields['slider_just_featured'] = array(
            'name'          =>  __( 'Show Only Featured Posts in Slider', 'better-studio' ),
            'id'            =>  'slider_just_featured',
            'std'           =>  '1' ,
            'type'          =>  'switch',
            'desc'          =>  __( 'Turn Off for showing latest posts of category in slider or On for showing posts that specified as featured post in this category as slider.', 'better-studio' )
        );
        $fields['slider_style'] = array(
            'name'          =>  __( 'Slider Style', 'better-studio' ),
            'desc'          =>  __( 'Select slider style', 'better-studio' ),
            'id'            =>  'slider_style',
            'std'           =>  'default',
            'save_default'  =>  false,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'       =>  array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-' . Better_Mag::get_option( 'slider_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'style-1' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-1.png',
                    'label'     =>  __( 'Style 1', 'better-studio' ),
                ),
                'style-2' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-2.png',
                    'label'     =>  __( 'Style 2', 'better-studio' ),
                ),
                'style-3' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-3.png',
                    'label'     =>  __( 'Style 3', 'better-studio' ),
                ),
                'style-4' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-4.png',
                    'label'     =>  __( 'Style 4', 'better-studio' ),
                ),
                'style-5' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-5.png',
                    'label'     =>  __( 'Style 5', 'better-studio' ),
                ),
                'style-6' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-6.png',
                    'label'     =>  __( 'Style 6', 'better-studio' ),
                ),
                'style-7' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-7.png',
                    'label'     =>  __( 'Style 7', 'better-studio' ),
                ),
                'style-8' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-8.png',
                    'label'     =>  __( 'Style 8', 'better-studio' ),
                ),
                'style-9' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-9.png',
                    'label'     =>  __( 'Style 9', 'better-studio' ),
                ),
                'style-10' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-10.png',
                    'label'     =>  __( 'Style 10', 'better-studio' ),
                ),
            )
        );
        $fields[] = array(
            'name'  =>  __( 'Slider Custom Post Type', 'better-studio' ),
            'desc'  =>  __( 'Enter your custom post types here. Separate with ,', 'better-studio' ),
            'id'    =>  'slider_post_type',
            'type'  =>  'text',
            'std'   =>  '',
        );
        $fields['slider_bg_color'] = array(
            'name'          =>  __( 'Slider Background Color', 'better-studio' ),
            'id'            =>  'slider_bg_color',
            'desc'          =>  __( 'Customize slider background color.', 'better-studio' ),
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'slider_bg_color' ),
            'save-std'      =>  false,
            'css'           =>  array(
                array(
                    'selector'  => 'body.tag-%%id%% .main-slider-wrapper' ,
                    'prop'      => array('background-color')
                )
            ),
        );
        $fields[] = array(
            'name'      =>  __( 'Revolution Slider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $fields['slider_rev_id'] = array(
            'name'      =>  __( 'Select Default Revolution Slider', 'better-studio' ),
            'desc'      =>  __( 'Select the unique name of the slider.', 'better-studio' ),
            'id'        =>  'slider_rev_id',
            'std'       =>  '0' ,
            'type'      =>  'select',
            'options'   => array(
                    '0'    => __( 'Select A Slider', 'better-studio' ),
                ) + BF_Query::get_rev_sliders()
        );
        $fields[] = array(
            'name'      =>  __( 'Slider Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['slider_top_padding'] = array(
            'name'          =>  __( 'Slider Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .main-slider-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['slider_bottom_padding'] = array(
            'name'          =>  __( 'Slider Bottom Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.tag-%%id%% .main-slider-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        /**
         * Breadcrumb
         */
        $fields[] = array(
            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
            'id'        =>  'breadcrumb_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link'
        );
        $fields['breadcrumb_style'] = array(
            'name'      =>  __( 'Breadcrumb Style', 'better-studio' ),
            'id'        => 'breadcrumb_style',
            'desc'      =>  __( 'Select breadcrumb style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-' . Better_Mag::get_option( 'breadcrumb_style' ) . '.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal-center.png',
                    'label' =>  __( 'Center Align', 'better-studio' ),
                ),
            ),
        );

        /**
         * => Custom Javascript / CSS
         */
        $fields['custom_css_settings'] = array(
            'name'      =>  __( 'Custom CSS' , 'better-studio' ),
            'id'        =>  'custom_css_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-css3',
            'margin-top'=>  '20',
        );
        $fields['custom_css_code'] = array(
            'name'      =>  __( 'Custom CSS Code', 'better-studio' ),
            'id'        =>  'custom_css_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'better-studio' )
        );
        $fields['custom_css_class'] = array(
            'name'      =>  __( 'Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added to body.<br> Separate classes with space.', 'better-studio' )
        );
        $fields[] = array(
            'name'          =>  __( 'Responsive CSS', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
            'desc'          =>  'Paste your custom css in the appropriate box, to run only on a specific device',
        );
        $fields['custom_css_desktop_code'] = array(
            'name'      =>  __( 'Desktop', 'better-studio' ),
            'id'        =>  'custom_css_desktop_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1200px +', 'better-studio' )
        );
        $fields['custom_css_ipad_landscape_code'] = array(
            'name'      =>  __( 'iPad Landscape', 'better-studio' ),
            'id'        =>  'custom_css_ipad_landscape_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1019px - 1199px', 'better-studio' )
        );
        $fields['custom_css_ipad_portrait_code'] = array(
            'name'      =>  __( 'iPad Portrait', 'better-studio' ),
            'id'        =>  'custom_css_ipad_portrait_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );
        $fields['custom_css_phones_code'] = array(
            'name'      =>  __( 'Phones', 'better-studio' ),
            'id'        =>  'custom_css_phones_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );

        //
        // Support to custom taxonomies
        //
        $tag_taxonomies = array( 'post_tag' );

        if( Better_Mag::get_option( 'advanced_tag_options_tax' ) != '' )
            $tag_taxonomies = array_merge( explode( ',', Better_Mag::get_option( 'advanced_tag_options_tax' ) ), $tag_taxonomies );

        $options[] = array(
            'config' => array(
                'taxonomies'    => $tag_taxonomies,
                'name'          => __( 'Better Tag Options', 'better-studio' )
            ),
            'panel-id'  => '__better_mag__theme_options',
            'fields' => $fields
        );

        return $options;

    } //setup_bf_metabox


    /**
     * Setup setting panel for BetterMag
     *
     * 5. => Admin Panel
     *
     * @param $options
     * @return array
     */
    function setup_option_panel( $options ){

        $field = array();

        /**
         * 5.1. => General Options
         */
        $field[] = array(
            'name'      =>  __( 'General' , 'better-studio' ),
            'id'        =>  'general_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-global'
        );
        $field['layout_style'] = array(
            'name'          =>  __( 'General Layout Style', 'better-studio' ),
            'id'            =>  'layout_style',
            'std'           =>  'full-width',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select the layout you want, whether a boxed or a full width one. It affects every page and the whole layout. This option can be overridden on every page, post, category and tag.', 'better-studio' ),
            'options'       => array(
                'full-width'    => array(
                    'img'           =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                    'label'         =>  __( 'Full Width', 'better-studio' ),
                ),
                'boxed'         => array(
                    'img'           =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                    'label'         =>  __( 'Boxed', 'better-studio' ),
                ),
                'boxed-padded'  => array(
                    'img'           =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                    'label'         =>  __( 'Boxed (Padded)', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'General Sidebar Position', 'better-studio' ),
            'id'            =>  'default_sidebar_layout',
            'std'           =>  'right',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select the general sidebar you want to use by default. This option can be overridden on every page, post, category and tag.', 'better-studio' ),
            'options'       => array(
                'left'      =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                    'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                ),
                'right'     =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                    'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                ),
                'no-sidebar'=>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                    'label'     =>  __( 'No Sidebar', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'      =>  __( 'Show Back To Top Button','better-studio'),
            'id'        =>  'back_to_top',
            'std'       =>  '0' ,
            'type'      =>  'switch',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
            'desc'      =>  __( 'Enabling this option will add a "Back To Top" button to pages.', 'better-studio' ),
        );
        $field[] = array(
            'name'  =>  __( 'Customize Site Width', 'better-studio' ),
            'id'    =>  'theme_width',
            'type'  =>  'group',
            'state' =>  'close',
        );
        $field['site_width'] = array(
            'name'      =>  __( 'Site Width','better-studio'),
            'desc'      =>  __( 'Controls the overall site width. In px or %, ex: 100% or 1180px.','better-studio'),
            'input-desc'=>  __( 'This value should have px or %.','better-studio'),
            'id'        =>  'site_width',
            'std'       =>  '1180px' ,
            'type'      =>  'text',
            'ltr'       =>  true,
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body.full-width .container',
                    ),
                    'prop'      => 'max-width'
                ),
                array(
                    'selector'  => array(
                        'body.boxed .main-wrap',
                        'body.boxed .main-menu.sticky',
                    ),
                    'prop'      => 'max-width'
                ),
            ),
        );
        $field['site_col1_width'] = array(
            'name'      =>  __( 'Main Column Width ( General )','better-studio'),
            'desc'      =>  __( 'Controls site main content column width. In %, ex: 70%.','better-studio'),
            'input-desc'=>  __( 'This value should have %.','better-studio'),
            'id'        =>  'site_col1_width',
            'std'       =>  '' ,
            'css-echo-default'  =>  false,
            'type'      =>  'text',
            'ltr'       =>  true,
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body .content-column',
                    ),
                    'prop'      => 'width',
                    'after' => '@media only screen and (max-width : 480px) {body .content-column{width:100%}body .main-sidebar{width:100%}}'
                ),
            ),
        );
        $field['site_col2_width'] = array(
            'name'      =>  __( 'Sidebar Width ( General )','better-studio'),
            'desc'      =>  __( 'Controls site sidebar column width. In %, ex: 30%.','better-studio'),
            'input-desc'=>  __( 'This value should have %.','better-studio'),
            'id'        =>  'site_col2_width',
            'std'       =>  '' ,
            'css-echo-default'  =>  false,
            'type'      =>  'text',
            'ltr'       =>  true,
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body .main-sidebar',
                    ),
                    'prop'      => 'width',
                    'after' => '@media only screen and (max-width : 480px) {body .content-column{width:100%}body .main-sidebar{width:100%}}'
                ),
            ),
        );
        $field['site_col1_width_tablet'] = array(
            'name'      =>  __( 'Main Column Width ( Tablet )','better-studio'),
            'desc'      =>  __( 'Controls site main content column width. In %, ex: 70%.','better-studio'),
            'input-desc'=>  __( 'This value should have %.','better-studio'),
            'id'        =>  'site_col1_width_tablet',
            'std'       =>  '' ,
            'css-echo-default'  =>  false,
            'type'      =>  'text',
            'ltr'       =>  true,
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body .content-column',
                    ),
                    'prop'      => 'width',
                    'before' => '@media only screen and (max-width : 768px){',
                    'after' => '}@media only screen and (max-width : 480px){body .content-column{width:100%}body .main-sidebar{width:100%}}',
                ),
            ),
        );
        $field['site_col2_width_tablet'] = array(
            'name'      =>  __( 'Sidebar Width ( Tablet )','better-studio'),
            'desc'      =>  __( 'Controls site sidebar column width. In %, ex: 30%.','better-studio'),
            'input-desc'=>  __( 'This value should have %.','better-studio'),
            'id'        =>  'site_col2_width_tablet',
            'std'       =>  '' ,
            'css-echo-default'  =>  false,
            'type'      =>  'text',
            'ltr'       =>  true,
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body .main-sidebar',
                    ),
                    'prop'      => 'width',
                    'before' => '@media only screen and (max-width : 768px){',
                    'after' => '}@media only screen and (max-width : 480px){body .content-column{width:100%}body .main-sidebar{width:100%}}',
                ),
            ),
        );

            $field[] = array(
                'name'  =>  __( 'Effects and Animations', 'better-studio' ),
                'id'    =>  'effects_heading',
                'type'  =>  'group',
            );
                $field[] = array(
                    'name'      =>  __( 'Enable Image Zoom Animation on Hover','better-studio'),
                    'id'        =>  'animation_image_zoom',
                    'std'       =>  '1' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Enable', 'better-studio' ),
                    'off-label' =>  __( 'Disable', 'better-studio' ),
                    'desc'      =>  __( 'Enabling this option will add zoom-in animation for listings and elements main image hover.', 'better-studio' ),
                );
                $field[] = array(
                    'name'      =>  __( 'Use Light Box For Images Link','better-studio'),
                    'id'        =>  'lightbox_is_enable',
                    'std'       =>  '1' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Enable', 'better-studio' ),
                    'off-label' =>  __( 'Disable', 'better-studio' ),
                    'desc'      =>  __( 'With enabling this link for bigger size of images will be opened in same page with beautiful lightbox.', 'better-studio' ),
                );

            $field[] = array(
                'name'  =>  __('Favicons','better-studio'),
                'id'    =>  'favicon_heading',
                'type'  =>  'group',
                'state' =>  'close',
            );
                $field[] = array(
                    'name'  =>  __('Favicon (16x16)','better-studio'),
                    'id'    =>  'favicon_16_16',
                    'type'  =>  'media_image',
                    'std'           =>  '',
                    'desc'  =>  __('Default Favicon. 16px x 16px','better-studio'),
                    'media_title'   =>  __( 'Select or Upload Favicon', 'better-studio'),
                    'media_button'  =>  __( 'Select Favicon', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Favicon', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Favicon', 'better-studio'),
                );
                $field[] = array(
                    'name'  =>  __('Apple iPhone Icon (57x57)','better-studio'),
                    'id'    =>  'favicon_57_57',
                    'type'  =>  'media_image',
                    'desc'  =>  __('Icon for Classic iPhone','better-studio'),
                    'std'           =>  '',
                    'media_title'   =>  __( 'Select or Upload Favicon', 'better-studio'),
                    'media_button'  =>  __( 'Select Favicon', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Favicon', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Favicon', 'better-studio'),
                );
                $field[] = array(
                    'name'  =>  __('Apple iPhone Retina Icon (114x114)','better-studio'),
                    'id'    =>  'favicon_114_114',
                    'type'  =>  'media_image',
                    'desc'  =>  __('Icon for Retina iPhone','better-studio'),
                    'std'           =>  '',
                    'media_title'   =>  __( 'Select or Upload Favicon', 'better-studio'),
                    'media_button'  =>  __( 'Select Favicon', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Favicon', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Favicon', 'better-studio'),
                );
                $field[] = array(
                    'name'  =>  __('Apple iPad Icon (72x72)','better-studio'),
                    'id'    =>  'favicon_72_72',
                    'type'  =>  'media_image',
                    'desc'  =>  __('Icon for Classic iPad','better-studio'),
                    'std'           =>  '',
                    'media_title'   =>  __( 'Select or Upload Favicon', 'better-studio'),
                    'media_button'  =>  __( 'Select Favicon', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Favicon', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Favicon', 'better-studio'),
                );
                $field[] = array(
                    'name'  =>  __('Apple iPad Retina Icon (144x144)','better-studio'),
                    'id'    =>  'favicon_144_144',
                    'type'  =>  'media_image',
                    'desc'  =>  __('Icon for Retina iPad','better-studio'),
                    'std'           =>  '',
                    'media_title'   =>  __( 'Select or Upload Favicon', 'better-studio'),
                    'media_button'  =>  __( 'Select Favicon', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Favicon', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Favicon', 'better-studio'),
                );

        /**
         * 5.2. => Header Options
         */
        $field[] = array(
            'name'  =>  __( 'Header', 'better-studio' ),
            'id'    =>  'header_settings',
            'type'  =>  'tab',
            'icon'  =>  'bsai-header'
        );

            $field[] = array(
                'name'      =>  __( 'Logo', 'better-studio' ),
                'type'      =>  'group',
                'state'     => 'close',
            );
    
                $field['logo_position'] = array(
                    'name'          =>   __( 'Logo Position', 'better-studio' ),
                    'id'            =>  'logo_position',
                    'desc'          =>   __( 'Select logo position in header. This will affect on "Logo Aside" sidebar location. If you select centered Logo Position the "Logo Aside" widget area will be removed.', 'better-studio' ),
                    'std'           =>  'left',
                    'type'          =>  'image_select',
                    'section_class' =>  'style-floated-left bordered',
                    'options' => array(
                        /* translators: For RTL Languages in this situation translate Left to Right and Right to Left!. */
                        'left'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-logo-left.png',
                            'label' =>  __( 'Left', 'better-studio' ),
                        ),
                        'center'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-logo-center.png',
                            'label' =>  __( 'Center', 'better-studio' ),
                        ),
                        'right'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-logo-right.png',
                            'label' =>  __( 'Right', 'better-studio' ),
                        ),
                    ),
                );

                $field['logo_text'] = array(
                    'name'          =>  __( 'Logo Text', 'better-studio' ),
                    'id'            =>  'logo_text',
                    'desc'          =>  __( 'The desired text will be used if logo images are not provided below.', 'better-studio' ),
                    'std'           =>  get_option( 'blogname' ),
                    'type'          =>  'text',
                );

                $field['logo_image'] = array(
                    'name'          =>  __( 'Logo Image', 'better-studio' ),
                    'id'            =>  'logo_image',
                    'desc'          =>  __( 'By default, a text-based logo is created using your site title. But you can also upload an image-based logo here.', 'better-studio' ),
                    'std'           =>  '',
                    'type'          =>  'media_image',
                    'media_title'   =>  __( 'Select or Upload Logo', 'better-studio'),
                    'media_button'  =>  __( 'Select Image', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Logo', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Logo', 'better-studio'),
                );

                $field['logo_image_retina'] = array(
                    'name'          =>  __( 'Logo Image Retina (2x)', 'better-studio' ),
                    'id'            =>  'logo_image_retina',
                    'desc'          =>  __( 'If you want to upload a Retina Image, It\'s Image Size should be exactly double in compare with your normal Logo. It requires WP Retina 2x plugin.', 'better-studio' ),
                    'std'           =>  '',
                    'type'          =>  'media_image',
                    'media_title'   =>  __( 'Select or Upload Retina Logo', 'better-studio'),
                    'media_button'  =>  __( 'Select Retina Image', 'better-studio'),
                    'upload_label'  =>  __( 'Upload Retina Logo', 'better-studio'),
                    'remove_label'  =>  __( 'Remove Retina Logo', 'better-studio'),
                );

                $field[] = array(
                    'name'          =>  __( 'Show Site Tagline Below Logo', 'better-studio' ),
                    'id'            =>  'show_site_description',
                    'std'           =>  '0' ,
                    'type'          =>  'switch',
                    'on-label'      =>  __( 'Show', 'better-studio' ),
                    'off-label'     =>  __( 'Hide', 'better-studio' ),
                    'desc'          =>  __( 'Enabling this will add site Tagline below the logo.','better-studio'),
                );

                $field[] = array(
                    'name'          =>  __( 'Show "Aside Logo" sidebar location on small screens?', 'better-studio' ),
                    'id'            =>  'show_aside_logo_on_small',
                    'std'           =>  '0' ,
                    'type'          =>  'switch',
                    'on-label'      =>  __( 'Show', 'better-studio' ),
                    'off-label'     =>  __( 'Hide', 'better-studio' ),
                    'desc'          =>  __( 'Enabling this will shows Aside Logo sidebar location on tablets and smartphones.','better-studio'),
                );

            $field[] = array(
                'name'      =>  __( 'Top Bar', 'better-studio' ),
                'type'      =>  'group',
                'state'     => 'close',
            );

                $field[] = array(
                    'name'      =>  __('Hide Top Bar','better-studio'),
                    'id'        =>  'disable_top_bar',
                    'std'       =>  '0' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Yes', 'better-studio' ),
                    'off-label' =>  __( 'No', 'better-studio' ),
                    'desc'      =>  __('Enabling this will disable the top bar element that appears above the logo area.','better-studio'),
                );

            $field[] = array(
                'name'      =>  __( 'Main Navigation', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
                $field['main_menu_style'] = array(
                    'name'      =>  __( 'Main Navigation Style', 'better-studio' ),
                    'id'        => 'main_menu_style',
                    'desc'      =>  __( 'Select header menu style. ', 'better-studio' ),
                    'std'       => 'normal',
                    'type'      => 'image_select',
                    'section_class' =>  'style-floated-left bordered',
                    'options'   => array(
                        'normal' =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal.png',
                            'label' =>  __( 'Normal', 'better-studio' ),
                        ),
                        'normal-center'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal-center.png',
                            'label' =>  __( 'Normal - Center Align', 'better-studio' ),
                        ),
                        'large' =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large.png',
                            'label' =>  __( 'Large', 'better-studio' ),
                        ),
                        'large-center'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large-center.png',
                            'label' =>  __( 'Large - Center Align', 'better-studio' ),
                        ),
                    ),
                );
                $field['main_menu_layout'] = array(
                    'name'      =>  __( 'Main Navigation Layout', 'better-studio' ),
                    'id'        => 'main_menu_layout',
                    'desc'      =>  __( 'Select whether you want a boxed or a full width menu. ', 'better-studio' ),
                    'std'       => 'boxed',
                    'type'      => 'image_select',
                    'section_class' =>  'style-floated-left bordered',
                    'options'   => array(
                        'boxed' =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                            'label' =>  __( 'Boxed', 'better-studio' ),
                        ),
                        'full-width'    =>  array(
                            'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-full-width.png',
                            'label' =>  __( 'Full Width', 'better-studio' ),
                        ),
                    ),
                );

                $field[] = array(
                    'name'      =>  __( 'Sticky Navigation', 'better-studio' ),
                    'id'        =>  'main_menu_sticky',
                    'std'       =>  '0' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Enable', 'better-studio' ),
                    'off-label' =>  __( 'Disable', 'better-studio' ),
                    'desc'      =>  __( 'This makes menu always visible at the top when the user scrolls.','better-studio'),
                );
                $field[] = array(
                    'name'      =>  __( 'Show Search Icon in Main Navigation', 'better-studio' ),
                    'id'        =>  'show_search_in_main_navigation',
                    'std'       =>  '1' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Show', 'better-studio' ),
                    'off-label' =>  __( 'Hide', 'better-studio' ),
                    'desc'      =>  __( 'Enabling this will add search icon to the main navigation.', 'better-studio' ),
                );
                $field[] = array(
                    'name'      =>  __( 'Show Random Post Link Icon in Main Navigation','better-studio'),
                    'id'        =>  'show_random_post_link',
                    'std'       =>  '0' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Show', 'better-studio' ),
                    'off-label' =>  __( 'Hide', 'better-studio' ),
                    'desc'      =>  __( 'Enabling this will adds random post icon link to the main navigation.','better-studio'),
                );
                $field[] = array(
                    'name'      =>  __( 'Show User Login Button in Main Navigation', 'better-studio' ),
                    'id'        =>  'main_navigation_show_user_login',
                    'std'       =>  '0' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Show', 'better-studio' ),
                    'off-label' =>  __( 'Hide', 'better-studio' ),
                    'desc'      =>  __( 'Enabling this will add a button in main navigation for user login and also register if it\'s enabled below.','better-studio'),
                );
                $field[] = array(
                    'name'      =>  __( 'Show User Register Form in Login Popup Modal?','better-studio'),
                    'id'        =>  'main_navigation_show_user_register_in_modal',
                    'std'       =>  '0' ,
                    'type'      =>  'switch',
                    'on-label'  =>  __( 'Show', 'better-studio' ),
                    'off-label' =>  __( 'Hide', 'better-studio' ),
                    'desc'      =>  __( 'Enabling this will add register form in popup login modal.', 'better-studio' ),
                );

            $field[] = array(
                'name'      =>  __( 'Header Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );

                $field['header_top_padding'] = array(
                    'name'          =>  __( 'Header Top Padding', 'better-studio' ),
                    'id'            =>  'header_top_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 30px.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'ltr'       =>  true,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .header'
                            ),
                            'prop'      => array( 'padding-top' => '%%value%%px'),
                        )
                    ),
                );
                $field['header_bottom_padding'] = array(
                    'name'          =>  __( 'Header Bottom Padding', 'better-studio' ),
                    'id'            =>  'header_bottom_padding',
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'desc'          =>  __( 'In pixels without ex: 20. <br>Default padding is 60px and lower than that will break the style.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'css-echo-default'  => false,
                    'ltr'       =>  true,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .header'
                            ),
                            'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                        )
                    ),
                );

        $field[] = array(
            'name'  =>  __( 'Slider', 'better-studio' ),
            'id'    =>  'slider_settings',
            'type'  =>  'tab',
            'icon'  =>  'bsai-slider'
        );

            $field[] = array(
                'name'      =>  __( 'Home Page Slider', 'better-studio' ),
                'desc'      =>  __( 'Select the type of slider for display.', 'better-studio' ),
                'id'        =>  'show_slider',
                'std'       =>  'no' ,
                'type'      =>  'select',
                'options'   => array(
                    'no'    => __( 'No Slider', 'better-studio' ),
                    'better'=> __( 'BetterSlider', 'better-studio' ),
                    'rev'   => __( 'Revolution Slider', 'better-studio' ),
                )
            );

        $field[] = array(
            'name'      =>  __( 'BetterSlider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
            $field[] = array(
                'name'      =>  __('Just Featured Posts in Slider?','better-studio'),
                'id'        =>  'slider_just_featured',
                'std'       =>  '1' ,
                'type'      =>  'switch',
                'on-label'  =>  __( 'Only Featured', 'better-studio' ),
                'off-label' =>  __( 'All Posts', 'better-studio' ),
                'desc'      => __( 'With enabling this option only featured posts will be shown in the slider, and with disabling this option recent posts will be shown. ', 'better-studio' )
            );
    
            $field['slider_style'] = array(
                'name'      =>  __( 'Slider Style', 'better-studio' ),
                'id'        =>  'slider_style',
                'std'       =>  'style-1',
                'type'      =>  'image_select',
                'desc'      =>  __( 'Select general slider style for home page and all categories. This can be overridden for every category and pages .', 'better-studio' ),
                'section_class' => 'style-floated-left bordered',
                'options' => array(
                    'style-1'    =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-1.png',
                        'label' =>  __( 'Style 1', 'better-studio' ),
                    ),
                    'style-2' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-2.png',
                        'label' =>  __( 'Style 2', 'better-studio' ),
                    ),
                    'style-3' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-3.png',
                        'label' =>  __( 'Style 3', 'better-studio' ),
                    ),
                    'style-4' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-4.png',
                        'label' =>  __( 'Style 4', 'better-studio' ),
                    ),
                    'style-5' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-5.png',
                        'label' =>  __( 'Style 5', 'better-studio' ),
                    ),
                    'style-6' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-6.png',
                        'label' =>  __( 'Style 6', 'better-studio' ),
                    ),
                    'style-7' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-7.png',
                        'label' =>  __( 'Style 7', 'better-studio' ),
                    ),
                    'style-8' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-8.png',
                        'label' =>  __( 'Style 8', 'better-studio' ),
                    ),
                    'style-9' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-9.png',
                        'label' =>  __( 'Style 9', 'better-studio' ),
                    ),
                    'style-10' =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-10.png',
                        'label' =>  __( 'Style 10', 'better-studio' ),
                    ),
                )
            );
    
            $field[] = array(
                'name'  =>  __( 'Slider Categories', 'better-studio' ),
                'id'    =>  'slider_cats',
                'type'  =>  'ajax_select',
                'desc'  =>  __( 'Select a specified category to filter the posts in the slider. you can use a combination of categories and tags. Leave it empty for showing all posts without any category filter.', 'better-studio' ),
                'placeholder'  =>  __("Search and find category...", 'better-studio'),
                "callback" => 'BF_Ajax_Select_Callbacks::cats_callback',
                "get_name" => 'BF_Ajax_Select_Callbacks::cat_name',
            );
            $field[] = array(
                'name'  =>  __( 'Slider Tags', 'better-studio' ),
                'id'    =>  'slider_tags',
                'type'  =>  'ajax_select',
                'desc'  =>  __( 'Select a specified tag to filter the posts in the slider. you can use a combination of categories and tags. Leave it empty for showing all posts without any tag filter.', 'better-studio' ),
                'placeholder'  =>  __("Search and find tag...", 'better-studio'),
                "callback" => 'BF_Ajax_Select_Callbacks::tags_callback',
                "get_name" => 'BF_Ajax_Select_Callbacks::tag_name',
            );
            $field[] = array(
                'name'  =>  __( 'Slider Custom Post Type', 'better-studio' ),
                'desc'  =>  __( 'Enter your custom post types here. Separate with ,', 'better-studio' ),
                'id'    =>  'slider_post_type',
                'type'  =>  'text',
                'std'   =>  '',
            );
            $field[] = array(
                'name'      =>  __( 'Animation Type', 'better-studio' ),
                'desc'      =>  __( 'Select your animation type, "fade" or "slide".', 'better-studio' ),
                'id'        =>  'better_slider_animation',
                'std'       =>  'fade' ,
                'type'      =>  'select',
                'options'   => array(
                        'fade'    => __( 'Fade', 'better-studio' ),
                        'slide'   => __( 'Slide', 'better-studio' ),
                )
            );
            $field[] = array(
                'name'      =>  __( 'Slide Show Speed', 'better-studio' ),
                'desc'      =>  __( 'Set the speed of the slideshow cycling, in milliseconds.', 'better-studio' ),
                'id'        =>  'better_slider_slideshowSpeed',
                'std'       =>  '7000' ,
                'type'      =>  'text',
                'suffix'    =>  'ms',
                'ltr'       =>  true,
            );
            $field[] = array(
                'name'      =>  __( 'Animation Speed', 'better-studio' ),
                'desc'      =>  __( 'Set the speed of animations, in milliseconds.', 'better-studio' ),
                'id'        =>  'better_slider_animationSpeed',
                'std'       =>  '600' ,
                'type'      =>  'text',
                'suffix'    =>  'ms',
                'ltr'       =>  true,
            );
            $field[] = array(
                'name'      =>  __( 'Revolution Slider Settings', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'open',
            );
                $field[] = array(
                    'name'      =>  __( 'Select Default Revolution Slider', 'better-studio' ),
                    'desc'      =>  __( 'Select the unique name of the slider.', 'better-studio' ),
                    'id'        =>  'slider_rev_id',
                    'std'       =>  '0' ,
                    'type'      =>  'select',
                    'options'   => array(
                        '0'    => __( 'Select A Slider', 'better-studio' ),
                    ) +  BF_Query::get_rev_sliders()
                );


            $field[] = array(
                'name'      =>  __( 'Slider Padding', 'better-studio' ),
                'type'      => 'group',
                'state'     => 'close',
            );
                $field['slider_top_padding'] = array(
                    'name'          =>  __( 'Slider Top Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  'slider_top_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'ltr'       =>  true,
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .main-slider-wrapper'
                            ),
                            'prop'      => array(
                                'padding-top' => '%%value%%px'
                            ),
                        )
                    ),
                );
                $field['slider_bottom_padding'] = array(
                    'name'          =>  __( 'Slider Bottom Padding', 'better-studio' ),
                    'suffix'        =>  __( 'Pixel', 'better-studio' ),
                    'id'            =>  'slider_bottom_padding',
                    'desc'          =>  __( 'In pixels without px, ex: 20. Default padding is 20px.', 'better-studio' ),
                    'type'          =>  'text',
                    'std'           =>  '',
                    'ltr'       =>  true,
                    'css-echo-default'  => false,
                    'css'           =>  array(
                        array(
                            'selector'  => array(
                                'body .main-slider-wrapper'
                            ),
                            'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                        )
                    ),
                );


        /**
         * Breadcrumb
         */
        $field[] = array(
            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
            'id'        =>  'breadcrumb_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link'
        );
        $field[] = array(
            'name'      =>  __( 'Show Breadcrumb?', 'better-studio' ),
            'id'        =>  'show_breadcrumb',
            'desc'      =>  __( 'Breadcrumbs are a hierarchy of links displayed below the main navigation. They are displayed on all pages but the home-page.', 'better-studio' ),
            'std'       =>  '1' ,
            'type'      =>  'switch',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
        );
        $field[] = array(
            'name'      =>  __( 'Show Breadcrumb on Homepage?', 'better-studio' ),
            'id'        =>  'show_breadcrumb_homepage',
            'desc'      =>  __( 'You can show breadcrumb in homepage with enabling this option.','better-studio'),
            'std'       =>  '0' ,
            'type'      =>  'switch',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
        );
        $field['breadcrumb_style'] = array(
            'name'      =>  __( 'Breadcrumb Style', 'better-studio' ),
            'id'        => 'breadcrumb_style',
            'desc'      =>  __( 'Select breadcrumb style. ', 'better-studio' ),
            'std'       => 'normal',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal-center.png',
                    'label' =>  __( 'Center Align', 'better-studio' ),
                ),
            ),
        );
        $field[] = array(
            'name'      =>  __( 'Show Categories on Breadcrumb For Posts', 'better-studio' ),
            'id'        =>  'show_breadcrumb_post_category',
            'desc'      =>  __( 'You can show categories on breadcrumb for single posts with enabling this option.','better-studio'),
            'std'       =>  '1' ,
            'type'      =>  'switch',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
        );

        /**
         * 5.3. => Footer Options
         */
        $field[] = array(
            'name'      =>  __( 'Footer', 'better-studio' ),
            'id'        =>  'footer_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-footer'
        );
        $field[] = array(
            'name'      =>  __( 'Large Footer', 'better-studio' ),
            'type'      => 'group',
        );
        $field[] = array(
            'name'      =>  __( 'Show Large Footer', 'better-studio' ),
            'id'        =>  'footer_large_active',
            'desc'      =>  __( 'Enabling this will adds the large footer to appears above the lowest footer. Used to contain large widgets.', 'better-studio' ),
            'type'      =>  'switch',
            'std'       =>  'checked',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
        );
        $field[] = array(
            'name'          =>  __('Large Footer Columns','better-studio'),
            'id'            =>  'footer_large_columns',
            'std'           =>  '3' ,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select weather you will show larger footer in 2,3 or 4 columns.', 'better-studio' ),
            'options' => array(
                '4' => array(
                    'img' => BETTER_MAG_ADMIN_ASSETS_URI . 'images/footer-4-column.png',
                    'label' => __('4 Column','better-studio'),
                ),
                '3' => array(
                    'img' => BETTER_MAG_ADMIN_ASSETS_URI . 'images/footer-3-column.png',
                    'label' => __('3 Column','better-studio'),
                ),
                '2' => array(
                    'img' => BETTER_MAG_ADMIN_ASSETS_URI . 'images/footer-2-column.png',
                    'label' => __('2 Column','better-studio'),
                ),
            )
        );

        $field[] = array(
            'name'      =>  __( 'Large Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $field['footer_large_top_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'ltr'       =>  true,
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body .footer-larger-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $field['footer_large_bottom_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'ltr'       =>  true,
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body .footer-larger-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        $field[] = array(
            'name'      =>  __( 'Lower Footer', 'better-studio' ),
            'type'      => 'group',
        );
            $field[] = array(
                'name'      =>  __( 'Show Lower Footer?', 'better-studio' ),
                'id'        =>  'footer_lower_active',
                'desc'      =>  __( 'Enabling this will adds the smaller footer at bottom.', 'better-studio' ),
                'type'      =>  'switch',
                'std'       =>  'checked',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );


        $field[] = array(
            'name'      =>  __( 'Lower Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $field['footer_lower_top_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'ltr'       =>  true,
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body .footer-lower-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $field['footer_lower_bottom_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'ltr'       =>  true,
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body .footer-lower-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );




        /**
         * 5.4. => Content & Listing Options
         */
        $field[] = array(
            'name'      =>  __( 'Posts & Contents' , 'better-studio' ),
            'id'        =>  'listings_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-page-text'
        );
        $field[] = array(
            'name'      =>  __( 'General','better-studio' ),
            'type'      =>  'group',
        );
            $field[] = array(
                'name'          =>  __( 'Blog Listing Excerpt Length', 'better-studio' ),
                'id'            =>  'blog_listing_excerpt_length',
                'type'          =>  'text',
                'std'           =>  22,
                'suffix'        => __( 'Word', 'better-studio' )
            );
            $field[] = array(
                'name'          =>  __( 'Show "Read More..." Link In Blog Listing?', 'better-studio' ),
                'id'            =>  'show_read_more_blog_listing',
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Modern Listing Excerpt Length', 'better-studio' ),
                'id'            =>  'modern_listing_excerpt_length',
                'type'          =>  'text',
                'std'           =>  22,
                'suffix'        => __( 'Word', 'better-studio' )
            );
            $field[] = array(
                'name'          =>  __( 'Show Featured Image/Video on top of Posts', 'better-studio' ),
                'id'            =>  'content_show_featured_image',
                'desc'          =>  __( 'You can hide posts featured image with disabling this', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  1,
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Allow Comments on Posts', 'better-studio' ),
                'id'            =>  'content_show_comments',
                'desc'          =>  __( 'Check the box to allow comments on regular posts.', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  1,
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Allow Comments on Pages', 'better-studio' ),
                'id'            =>  'content_show_comments_pages',
                'desc'          =>  __( 'Check the box to allow comments on regular pages.', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  0,
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Show author information box in single page?', 'better-studio' ),
                'id'            =>  'content_show_author_box',
                'desc'          =>  __( 'Enabling this will be adds author information box to bottom of posts page.', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Show post categories in single page?', 'better-studio' ),
                'id'            =>  'content_show_categories',
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Show post tags in single page?', 'better-studio' ),
                'id'            =>  'content_show_tags',
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Use WP-PageNavi Plugin For Pagination?', 'better-studio' ),
                'desc'          =>  __( 'WP-PageNavi plugin will be used for pagination if it was active.', 'better-studio' ),
                'id'            =>  'use_wp_pagenavi',
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Hide Post Category Banner in Listings?', 'better-studio' ),
                'id'            =>  'content_hide_category_banner',
                'type'          =>  'switch',
                'std'           =>  false,
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
                'desc'          =>  __( 'Enabling this will hide small category banner in left side of posts in all listings.', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Hide Post Format Icons?', 'better-studio' ),
                'id'            =>  'content_hide_post_format_icon',
                'type'          =>  'switch',
                'std'           =>  false,
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
                'desc'          =>  __( 'Enabling this will hide small post format icon in right side of posts in all listings.', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Emphasize First Paragraph of Content', 'better-studio' ),
                'id'            =>  'content_emphasize_first_p',
                'type'          =>  'switch',
                'std'           =>  true,
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
                'desc'          =>  __( 'You can emphasize first paragraph of posts and pages.', 'better-studio' ),
            );


        $field[] = array(
            'name'      =>  __( 'Meta Info Settings', 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
            $field[] = array(
                'name'          =>  __( 'Show Post Author', 'better-studio' ),
                'id'            =>  'meta_show_author',
                'type'          =>  'switch',
                'std'           =>  true,
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
                'desc'          =>  __( 'You can hide post author inside post meta info for all content listings with disabling this option.', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Show Post Comments Count', 'better-studio' ),
                'id'            =>  'meta_show_comment',
                'type'          =>  'switch',
                'std'           =>  true,
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
                'desc'          =>  __( 'You can hide post comments count inside post meta info for all content listings with disabling this option.', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Show Post Views Count', 'better-studio' ),
                'id'            =>  'meta_show_views',
                'type'          =>  'switch',
                'std'           =>  true,
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
                'desc'          =>  __( 'You can hide post views count inside post meta info for all content listings with disabling this option.', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Post Date Format Inside Meta Info', 'better-studio' ),
                'id'            =>  'meta_date_format',
                'type'          =>  'text',
                'std'           =>  'M j, Y',
            );
            $field[] = array(
                'name'          =>  __( 'Hide Post Meta in Single Posts', 'better-studio' ),
                'id'            =>  'meta_hide_in_single',
                'type'          =>  'switch',
                'std'           =>  false,
                'on-label'  =>  __( 'Yes', 'better-studio' ),
                'off-label' =>  __( 'No', 'better-studio' ),
                'desc'          =>  __( 'You can hide post meta ( date, author and comments ) inside single posts with enabling this option.', 'better-studio' ),
            );

        $field[] = array(
            'name'      =>  __( 'Post Navigation Links','better-studio' ),
            'type'      =>  'group',
            'state'     => 'close',
        );
            $field[] = array(
                'name'          =>  __( 'Show Previous and Next Posts in Single Page?', 'better-studio' ),
                'id'            =>  'bm_content_show_post_navigation',
                'desc'          =>  __( 'Enabling this will add a Previous and Next post link in the single post page.', 'better-studio' ),
                'type'          =>  'switch',
                'std'           =>  '0',
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Previous and Nest Posts Style', 'better-studio' ),
                'desc'          =>  __( 'Select style of Previous and Next posts link in single page.', 'better-studio' ),
                'id'            =>  'bm_content_post_navigation_style',
                'type'          =>  'image_radio',
                'section_class' =>  'style-floated-left',
                'options'       =>  array(
                    'style-1'      =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/post-navigation-style-1.png',
                        'label'     =>  __( 'Style 1', 'better-studio' ),
                    ),
                    'style-2'    =>  array(
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/post-navigation-style-2.png',
                        'label'     =>  __( 'Style 2', 'better-studio' ),
                    ),
                ),
                'std'           =>  'style-1',
            );
            $field[] = array(
                'name'          =>  __( 'Smart Adjustment Post', 'better-studio' ),
                'desc'          =>  __( 'Show a random post when there is no next/prev post', 'better-studio' ),
                'id'            =>  'bm_content_post_navigation_smart',
                'type'          =>  'switch',
                'std'           =>  '1',
                'on-label'      =>  __( 'Yes', 'better-studio' ),
                'off-label'     =>  __( 'No', 'better-studio' ),
            );
        $field[] = array(
            'name'      =>  __( 'Related Posts','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
            'id'        =>  'related-posts-options',
        );
        $field[] = array(
            'name'          =>  __( 'Show related posts in single page?', 'better-studio' ),
            'id'            =>  'content_show_related_posts',
            'desc'          =>  __( 'Enabling this will be adds related posts in  bottom of posts single page.', 'better-studio' ),
            'type'          =>  'switch',
            'std'           =>  'checked',
            'on-label'  =>  __( 'Show', 'better-studio' ),
            'off-label' =>  __( 'Hide', 'better-studio' ),
        );
        $field[] = array(
            'name'          =>  __( 'Related Posts Algorithm', 'better-studio' ),
            'id'            =>  'content_show_related_posts_type',
            'type'          =>  'select',
            'options'       =>  array(
                'cat'           =>  __( 'by Category', 'better-studio' ),
                'tag'           =>  __( 'by Tag', 'better-studio' ),
                'author'        =>  __( 'by Author', 'better-studio' ),
                'cat-tag'       =>  __( 'by Category & Tag', 'better-studio' ),
                'cat-tag-author'=>  __( 'by Category ,Tag & Author', 'better-studio' ),
            ),
            'std'           =>  'cat',
        );
        $field[] = array(
            'name'          =>  __( 'Related Posts Count', 'better-studio' ),
            'id'            =>  'content_related_posts_count',
            'type'          =>  'text',
            'std'           =>  3,
        );
        $field[] = array(
            'name'      =>  __( 'Comments','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
            'id'        =>  'comments-options',
        );
        $field[] = array(
            'name'          =>  __( 'Comment Form Position', 'better-studio' ),
            'id'            =>  'comment_form_position',
            'std'           =>  'bottom',
            'type'          =>  'select',
            'desc'          =>  __( 'Chose comment form position in pages.', 'better-studio' ),
            'options'       =>  array(
                'top'   =>  __( 'Top of Comments', 'better-studio' ),
                'bottom'=>  __( 'Bottom of Comments', 'better-studio' ),
                'both'  =>  __( 'Both Top & Bottom', 'better-studio' ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'Remove URL Field from Comment Form', 'better-studio' ),
            'id'            =>  'comment_form_remove_url',
            'desc'          =>  __( 'With enabling this URL will removed from comments form.', 'better-studio' ),
            'type'          =>  'switch',
            'std'           =>  false,
            'on-label'  =>  __( 'Yes', 'better-studio' ),
            'off-label' =>  __( 'No', 'better-studio' ),
        );


        /**
         * Archives & Pages
         **/
        $field[] = array(
            'name'      =>  __( 'Archives & Pages','better-studio' ),
            'type'      =>  'tab',
            'icon'      =>  'bsai-archive',
            'id'        =>  'archive_pages-options',
        );
        $field['archive_listing_style'] = array(
            'name'          =>  __( 'Default Archives Listing', 'better-studio' ),
            'id'            =>  'archive_listing_style',
            'std'           =>  'blog',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Used while browsing default blog archive, date archives etc.', 'better-studio' ),
            'options'       =>  array(
                'blog'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     =>  __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     =>  __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'      =>  __( 'Categories Archive','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $field['categories_listing_style'] = array(
            'name'          =>  __( 'Content Listing', 'better-studio' ),
            'id'            =>  'categories_listing_style',
            'std'           =>  'blog',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Used while browsing categories archive pages. <br>This can be overridden for each category.', 'better-studio' ),
            'options'       =>  array(
                'blog'      => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern'    => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     => __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     => __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'archive_cat_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>This can be overridden for each category.<br>Default: %s', 'better-studio' ), get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $field[] = array(
            'name'          =>  __( 'Show RSS Link', 'better-studio' ),
            'id'            =>  'archive_cat_show_rss',
            'desc'          =>  __( 'Display RSS icon alongside category title.', 'better-studio' ),
            'type'          =>  'switch',
            'std'           =>  1,
            'on-label'      =>  __( 'Show', 'better-studio' ),
            'off-label'     =>  __( 'Hide', 'better-studio' ),
        );
        $field[] = array(
            'name'      =>  __( 'Tags Archive','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $field['tags_listing_style'] = array(
            'name'          =>  __( 'Content Listing', 'better-studio' ),
            'id'            =>  'tags_listing_style',
            'std'           =>  'blog',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Used while browsing tags archive pages. <br>This can be overridden for each tag.', 'better-studio' ),
            'options'       =>  array(
                'blog'      =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern'    =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     =>  __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     =>  __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'archive_tag_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>This can be overridden for each tag.<br>Default: %s', 'better-studio' ), get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $field[] = array(
            'name'          =>  __( 'Show RSS Link', 'better-studio' ),
            'id'            =>  'archive_tag_show_rss',
            'desc'          =>  __( 'Display RSS icon alongside tag title.', 'better-studio' ),
            'type'          =>  'switch',
            'std'           =>  1,
            'on-label'      =>  __( 'Show', 'better-studio' ),
            'off-label'     =>  __( 'Hide', 'better-studio' ),
        );
        $field[] = array(
            'name'      =>  __( 'Authors Archive','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $field['authors_listing_style'] = array(
            'name'          =>  __( 'Content Listing', 'better-studio' ),
            'id'            =>  'authors_listing_style',
            'std'           =>  'blog',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Used while browsing authors archive page. <br>This can be overridden for each author.', 'better-studio' ),
            'options'       =>  array(
                'blog'      =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern'    =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     =>  __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     =>  __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'archive_author_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>This can be overridden for each author.<br>Default: %s', 'better-studio' ), get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $field[] = array(
            'name'      =>  __( 'Search Results Archive','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $menus = array();
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $field['archive_search_menu'] = array(
            'name'          =>  __( 'Navigation Menu', 'better-studio' ),
            'id'            =>  'archive_search_menu',
            'desc'          =>  __( 'Select which menu displays on search results page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );
        $field['search_listing_style'] = array(
            'name'          =>  __( 'Content Listing', 'better-studio' ),
            'id'            =>  'search_listing_style',
            'std'           =>  'blog',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Used while browsing search results page.', 'better-studio' ),
            'options'       =>  array(
                'blog'      =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern'    =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     =>  __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     =>  __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $field['search_sidebar_layout'] = array(
            'name'          =>  __( 'Display Sidebar', 'better-studio' ),
            'id'            =>  'search_sidebar_layout',
            'std'           =>  'right',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select position or disable search results page sidebar.', 'better-studio' ),
            'options'       => array(
                'left'      =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                    'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                ),
                'right'     =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                    'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                ),
                'no-sidebar'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                    'label' =>  __( 'No Sidebar', 'better-studio' ),
                ),
            )
        );
        $field['search_result_content'] = array(
            'name'          =>  __( 'Result Content Type', 'better-studio' ),
            'id'            =>  'search_result_content',
            'std'           =>  'post',
            'type'          =>  'select',
            'desc'          =>  __( 'Select the type of content to display in search results.', 'better-studio' ),
            'options'       => array(
                'post'  =>  __( 'Only Posts', 'better-studio' ),
                'page'  =>  __( 'Only Pages', 'better-studio' ),
                'both'  =>  __( 'Posts and Pages', 'better-studio' ),
            )
        );
        $field[] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'archive_search_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>Default: %s', 'better-studio' ), get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $field[] = array(
            'name'      =>  __( '404 Page','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $menus = array();
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $field['archive_404_menu'] = array(
            'name'          =>  __( '404 Page Navigation Menu', 'better-studio' ),
            'id'            =>  'archive_404_menu',
            'desc'          =>  __( 'Select which menu displays on 404 page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );

        /**
         * Share Box
         */
        $field[] = array(
            'name'      =>  __( 'Share Box','better-studio' ),
            'type'      =>  'tab',
            'icon'      =>  'bsai-share-alt',
            'id'        =>  'share-box-options',
        );

            $field[] = array(
                'name'          =>  __( 'Show Share Box In Posts', 'better-studio' ),
                'desc'          =>  __( 'Enabling this will adds share links in posts single page. You can change design and social sites will following options.', 'better-studio' ),
                'id'            =>  'content_show_share_box',
                'type'          =>  'switch',
                'std'           =>  'checked',
                'on-label'      =>  __( 'Show', 'better-studio' ),
                'off-label'     =>  __( 'Hide', 'better-studio' ),
            );


            $field[] = array(
                'name'          =>  __( 'Share Box Style', 'better-studio' ),
                'id'            =>  'share_box_style',
                'desc'          =>  __( 'Select style of sharing buttons.', 'better-studio' ),
                'std'           =>  'button',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left',
                'options'       =>  array(
                    'button'    =>  array(
                        'label'     =>  __( 'Button' , 'better-studio' ),
                        'img'       =>  BF_URI . 'assets/img/vc-social-share-button.png'
                    ),
                    'button-no-text' => array(
                        'label'     =>  __( 'Icon' , 'better-studio' ),
                        'img'       =>  BF_URI . 'assets/img/vc-social-share-button-no-text.png'
                    ),
                    'outline-button' => array(
                        'label'     =>  __( 'Outline' , 'better-studio' ),
                        'img'       =>  BF_URI . 'assets/img/vc-social-share-outline-button.png'
                    ),
                    'outline-button-no-text' => array(
                        'label'     =>  __( 'Outline Icon' , 'better-studio' ),
                        'img'       =>  BF_URI . 'assets/img/vc-social-share-outline-button-no-text.png'
                    ),
                ),
            );
            $field[] = array(
                'name'      =>  __( 'Select Color Style', 'better-studio' ),
                'id'        =>  'share_box_colored',
                'desc'      =>  __( 'Enabling this will be show social share buttons in color mode and disabling this will be show in gray mode.', 'better-studio' ),
                'type'      =>  'switch',
                'std'       =>  'checked',
                'on-label'  =>  __( 'Colored', 'better-studio' ),
                'off-label' =>  __( 'Gray', 'better-studio' ),
            );
            $field[] = array(
                'name'          =>  __( 'Share Box Location', 'better-studio' ),
                'desc'          =>  __( 'Select location of share box in posts single page.', 'better-studio' ),
                'id'            =>  'bm_share_box_location',
                'type'          =>  'select',
                'options'       =>  array(
                    'top'           =>  __( 'Top', 'better-studio' ),
                    'bottom'        =>  __( 'Bottom', 'better-studio' ),
                    'bottom-top'    =>  __( 'Top & Bottom', 'better-studio' ),
                ),
                'std'           =>  'bottom',
            );
            $field[] = array(
                'name'          =>  __( 'Drag and Drop To Sort The Items', 'better-studio' ),
                'id'            =>  'social_share_list',
                'desc'          =>  __( 'Enabling sites will adds share link for them in single pages. You can reorder sites too.', 'better-studio' ),
                'type'          =>  'sorter_checkbox',
                'std'           =>  array(
                    'facebook'      =>  true,
                    'twitter'       =>  true,
                    'google_plus'   =>  true,
                    'pinterest'     =>  true,
                    'linkedin'      =>  true,
                    'tumblr'        =>  true,
                    'email'         =>  true,
                ),
                'options'       =>  array(
                    'facebook'      =>  array(
                        'label'         =>  '<i class="fa fa-facebook"></i> ' . __( 'Facebook', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'twitter'       =>  array(
                        'label'         =>  '<i class="fa fa-twitter"></i> ' . __( 'Twitter', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'google_plus'   =>  array(
                        'label'         =>  '<i class="fa fa-google-plus"></i> ' . __( 'Google+', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'pinterest'     =>  array(
                        'label'         =>  '<i class="fa fa-pinterest"></i> ' . __( 'Pinterest', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'linkedin'      =>  array(
                        'label'         =>  '<i class="fa fa-linkedin"></i> ' . __( 'Linkedin', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'tumblr'        =>  array(
                        'label'         =>  '<i class="fa fa-tumblr"></i> ' . __( 'Tumblr', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                    'email'         =>  array(
                        'label'         =>  '<i class="fa fa-envelope "></i> ' . __( 'Email', 'better-studio' ),
                        'css-class'     =>  'active-item'
                    ),
                ),
                'section_class'     =>  'bf-social-share-sorter',
            );



        /**
         * 5.5. => Typography Options
         */
        $field[] = array(
            'name'      =>  __( 'Typography' , 'better-studio' ),
            'id'        =>  'typo_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-typography'
        );

        /**
         * 5.5.1. => General Typography
         */

        $field[] = array(
            'name'      =>  __( 'Reset Typography settings', 'better-studio' ),
            'id'        =>  'reset_typo_settings',
            'type'      =>  'ajax_action',
            'button-name' =>  '<i class="fa fa-refresh"></i> ' . __( 'Reset Typography', 'better-studio' ),
            'callback'  =>  'Better_Mag::reset_typography_options',
            'confirm'  =>  __( 'Are you sure for resetting typography?', 'better-studio' ),
            'desc'      =>  __( 'This allows you to reset all typography fields to default.', 'better-studio' )
        );


        $field[] = array(
            'name'      =>  __( 'General Typography', 'better-studio' ),
            'type'      =>  'group',
            'state' => 'close',
        );

        $field['typo_body'] = array(
            'name'          =>  __( 'Base Font (Body)', 'better-studio' ),
            'id'            =>  'typo_body',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'    =>  'Roboto',
                'variant'   =>  '500',
                'subset'    =>  'latin',
                'size'      =>  '14',
                'align'     =>  'inherit',
                'transform' =>  'initial',
                'color'     =>  '#5f6569',
            ),
            'std-full-dark' =>  array(
                'family'    =>  'Roboto',
                'variant'   =>  '500',
                'subset'    =>  'latin',
                'size'      =>  '14',
                'align'     =>  'inherit',
                'transform' =>  'initial',
                'color'     =>  '#e6e6e6',
            ),
            'std-full-black'=>  array(
                'family'    =>  'Roboto',
                'variant'   =>  '500',
                'subset'    =>  'latin',
                'size'      =>  '14',
                'align'     =>  'inherit',
                'transform' =>  'initial',
                'color'     =>  '#e6e6e6',
            ),
            'std-beige'     =>  array(
                'family'    =>  'Roboto',
                'variant'   =>  '500',
                'subset'    =>  'latin',
                'size'      =>  '14',
                'align'     =>  'inherit',
                'transform' =>  'initial',
                'color'     =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'Base typography for body that will affect all elements that haven\'t specified typography style. ', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   => 'paragraph',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector'  =>  'body',
                    'type'      =>  'font',
                )
            ),
        );

        $field['typo_heading'] = array(
            'name'          => __( 'Base Heading Typography', 'better-studio' ),
            'id'            => 'typo_heading',
            'type'          => 'typography',
            'std'           => array(
                'family'        => 'Arvo',
                'variant'       => '400',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#444444'
            ),
            'std-full-dark'  => array(
                'family'        => 'Arvo',
                'variant'       => '400',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#e6e6e6'
            ),
            'std-full-black'    => array(
                'family'        => 'Arvo',
                'variant'       => '400',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#e6e6e6'
            ),
            'std-beige'     => array(
                'family'        => 'Arvo',
                'variant'       => '400',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'beige',
                'full-black',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'Base heading typography that will be set to all headings (h1,h2 etc) and all titles of sections and pages that must be bolder than other texts.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector'  => array(
                        '.heading',
                        'h1,h2,h3,h4,h5,h6',
                        '.header .logo a',
                        '.block-modern h2.title a',
                        '.main-menu .block-modern h2.title a',
                        '.blog-block h2 a',
                        '.main-menu .blog-block h2 a',
                        '.block-highlight .title',
                        '.listing-thumbnail h3.title a',
                        '.main-menu .listing-thumbnail h3.title a',
                        '.listing-simple li h3.title a',
                        '.main-menu .listing-simple li h3.title a',
                        '.widget li',
                        '.bf-shortcode.bm-login-register label',
                        '.bf-shortcode.bm-login-register .register-tab .before-message',
                        '.bf-shortcode.bm-login-register .register-tab .statement',
                    ),
                    'type'      => 'font',
                ),
                // WooCommerce Heading Style
                array(
                    'selector'  => array(
                        '.woocommerce ul.cart_list li a',
                        '.woocommerce ul.product_list_widget li a',
                        '.woocommerce-page ul.cart_list li a',
                        '.woocommerce-page ul.product_list_widget li a',
                        '.woocommerce ul.products li.product h3',
                        '.woocommerce-page ul.products li.product h3',
                        '.woocommerce-account .woocommerce .address .title h3',
                        '.woocommerce-account .woocommerce h2',
                        '.cross-sells h2',
                        '.related.products h2',
                        '.woocommerce #reviews h3',
                        '.woocommerce-page #reviews h3',
                        '.woocommerce-tabs .panel.entry-content h2',
                        '.woocommerce .shipping_calculator h2',
                        '.woocommerce .cart_totals h2',
                        'h3#order_review_heading',
                        '.woocommerce-shipping-fields h3',
                        '.woocommerce-billing-fields h3',
                    ),
                    'type'      => 'font',
                    'filter'    => array( 'woocommerce' ),
                ),

                // bbPress Heading Style
                array(
                    'selector'  =>  array(
                        '#bbpress-forums li.bbp-header .forum-titles .bbp-forum-info a',
                        '#bbpress-forums li.bbp-header .forum-titles .bbp-forum-info',
                        '#bbpress-forums li.bbp-header li.bbp-forum-topic-reply-count',
                        'li.bbp-forum-freshness',
                        'li.bbp-topic-freshness',
                        '#bbpress-forums li.bbp-forum-info .bbp-forum-title',
                        '#bbpress-forums p.bbp-topic-meta .bbp-author-name',
                        '#bbpress-forums .bbp-forums-list li',
                        'li.bbp-topic-freshness',
                        'li.bbp-topic-reply-posts-count',
                        'li.bbp-topic-title',
                        '#bbpress-forums p.bbp-topic-meta .bbp-author-name',
                        '#bbpress-forums div.bbp-reply-content .reply-meta .bbp-reply-post-author',
                        '.widget_display_stats dl dt',
                        '.widget_display_topics li a',
                        '.widget_display_topics li a.bbp-forum-title',
                        '.widget_display_replies li a.bbp-reply-topic-title',
                        '.widget_display_forums li a',
                    ),
                    'type'      =>  'font',
                    'filter'    =>  array( 'bbpress' ),
                ),

            ),
        );

        $field['typo_heading_page'] = array(
            'name'          => __( 'Pages/Posts Title Typography', 'better-studio' ),
            'id'            => 'typo_heading_page',
            'type'          => 'typography',
            'std'           => array(
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '18',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#444444',
                'line_height'   =>  '30',
            ),
            'std-full-dark' => array(
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '18',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
                'line_height'   =>  '30',
            ),
            'std-full-black' => array(
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '18',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
                'line_height'   =>  '30',
            ),
            'std-beige'     => array(
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '18',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
                'line_height'   =>  '30',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector'  => array(
                        '.page-heading',
                        '.page-heading span.h-title',
                    ),
                    'type'      => 'font',
                ),
            ),
        );

        $field['typo_heading_section'] = array(
            'name'          => __( 'Sections/Listings Title Typography', 'better-studio' ),
            'id'            => 'typo_heading_section',
            'type'          => 'typography',
            'std'           => array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'size'          =>  '14',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'uppercase',
                'line_height'   =>  '32',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector'  => array(
                        '.section-heading.extended .other-links .other-item a',
                        '.section-heading span.h-title',
                    ),
                    'type'      => 'font',
                ),
            ),
        );

        $field['typo_meta'] = array(
            'name'          =>  __( 'Base Meta Typography', 'better-studio' ),
            'id'            =>  'typo_meta',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#919191',
            ),
            'std-full-dark' =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#f7f7f7',
            ),
            'std-full-black' =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#f7f7f7',
            ),
            'std-beige'     =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'Base style for all posts, pages and listings meta data (date, author etc) sections. This can be overridden for each listings.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   => 'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.mega-menu .meta',
                        '.mega-menu .meta span',
                        '.mega-menu .meta a',
                        '.the-content .meta a',
                        '.meta a',
                        '.meta span',
                        '.meta',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_excerpt'] = array(
            'name'          =>  __( 'Base Excerpt Typography', 'better-studio' ),
            'id'            =>  'typo_excerpt',
            'type'          =>  'typography',
            'std'           => array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#717171',
            ),
            'std-full-dark' => array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black' => array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General excerpts typography. This can overridden for each listing.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.blog-block .summary p, .blog-block .summary',
                        '.block-modern .summary p, .block-modern .summary',
                    ),
                    'type'  => 'font',
                )
            ),
        );
        $field['typo_read_more'] = array(
            'name'          =>  __( 'Base Read More Button Typography', 'better-studio' ),
            'id'            =>  'typo_read_more',
            'type'          =>  'typography',
            'std'           => array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'center',
                'transform'     =>  'uppercase',
                'line_height'   =>  '18',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General Read More typography. This can be overridden for each listing.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.btn-read-more',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_category_banner'] = array(
            'name'          =>  __( 'Base Category Banner Typography', 'better-studio' ),
            'id'            =>  'typo_category_banner',
            'type'          =>  'typography',
            'std'           => array(
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '30',
                'align'         =>  'center',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General category banner typography. This can be overridden for each listing.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.term-title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.5.8. => Pages/Posts Content Typography
         */
        $field[] = array(
            'name'  =>  __( 'Pages & Posts Content', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );

        $field['typ_content_text'] = array(
            'name'          =>  __( 'Posts/Pages Text Typography', 'better-studio' ),
            'id'            =>  'typ_content_text',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'line_height'   =>  '24',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#5f6569',
            ),
            'std-full-dark' =>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'line_height'   =>  '24',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'line_height'   =>  '24',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     =>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'line_height'   =>  '24',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'paragraph',
            'css-echo-default'  => true,
            'css'   => array(
                array(
                    'selector' => array(
                        '.the-content',
                        '.the-content p',
                    ),
                    'type'  => 'font',
                )
            ),
        );
        $field['typ_content_blockquote'] = array(
            'name'          =>  __( 'Blockquote Typography', 'better-studio' ),
            'id'            =>  'typ_content_blockquote',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'line_height'   =>  '24',
            ),
            'std-full-dark' => array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'line_height'   =>  '24',
            ),
            'std-full-black'=>  array(
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'line_height'   =>  '24',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'paragraph',
            'css-echo-default'  => true,
            'css'   => array(
                array(
                    'selector' => array(
                        'blockquote',
                        'blockquote p',
                        '.the-content blockquote p',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.5.7. => Header Typography
         */
        $field[] = array(
            'name'  =>  __('Header','better-studio'),
            'type'  =>  'group',
            'state' => 'close',
        );


        $field['typ_header_menu'] = array(
            'name'          =>  __( 'Menu Typography', 'better-studio' ),
            'id'            =>  'typ_header_menu',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'uppercase',
            ),
            'std-full-dark' =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'uppercase',
            ),
            'std-full-black'=>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.main-menu .menu a',
                        '.main-menu .menu li',
                        '.main-menu .main-menu-container.mobile-menu-container .mobile-button',
                    ),
                    'type'  => 'font',
                )
            ),
        );
        $field['typ_header_menu_badges'] = array(
            'name'          =>  __( 'Menu Badges Typography', 'better-studio' ),
            'id'            =>  'typ_header_menu_badges',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '11',
                'transform'     =>  'uppercase',
            ),
            'std-full-dark' =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '11',
                'transform'     =>  'uppercase',
            ),
            'std-full-black'=>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '500',
                'subset'        =>  'latin',
                'size'          =>  '11',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.main-menu .menu .better-custom-badge',
                    ),
                    'type'  => 'font',
                )
            ),
        );
        $field['typ_header_menu_desc'] = array(
            'name'          =>  __( 'Large Menu Description Typography', 'better-studio' ),
            'id'            =>  'typ_header_menu_desc',
            'type'          =>  'typography',
            'std'           =>  array(
                'family'        =>  'Roboto',
                'variant'       =>  '300',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'preview'           =>  true,
            'preview_tab'       =>  'title',
            'css-echo-default'  => true,
            'css'               => array(
                array(
                    'selector' => array(
                        '.main-menu.style-large .desktop-menu-container .menu > li > a > .description',
                    ),
                    'type'  => 'font',
                )
            ),
        );
        $field['typ_header_logo'] = array(
            'name'          =>  __( 'Logo Text Typography', 'better-studio' ),
            'id'            =>  'typ_header_logo',
            'type'          =>  'typography',
            'std'           =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '30',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark' =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '30',
                'transform'     =>  'initial',
                'color'         =>  '#3e6e6e6'
            ),
            'std-full-black'=>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '30',
                'transform'     =>  'initial',
                'color'         =>  '#3e6e6e6'
            ),
            'std-beige'     =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '30',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can change logo text typography with enabling this option.', '' ),
            'preview'       => true,
            'preview_tab'   => 'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        'body header .logo',
                        'body header .logo a'
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typ_header_site_desc'] = array(
            'name'          =>  __( 'Blog Description Typography', 'better-studio' ),
            'id'            =>  'typ_header_site_desc',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark'  => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-full-black' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-beige' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '14',
                'align'         =>  'inherit',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can change typography of site description (below logo) typography with enabling this option.', '' ),
            'preview'       => true,
            'preview_tab'   => 'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        'body header .site-description'
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * => Slider Typography
         */
        $field[] = array(
            'name'  =>  __( 'Slider', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );
            $field['typo_slider_heading'] = array(
                'name'          =>  __( 'Slider Heading Typography', 'better-studio' ),
                'id'            =>  'typo_slider_heading',
                'type'          =>  'typography',
                'std'           =>  array(
                    'family'        => 'Arvo',
                    'variant'       => '400',
                    'size'          => '17',
                    'subset'        => 'latin',
                    'align'         => 'initial',
                    'transform'     => 'initial',
                ),
                'style'         =>  array(
                    'default',
                    'dark',
                    'full-dark',
                    'black',
                    'full-black',
                    'beige',
                    'green',
                    'blue1',
                    'blue2',
                ),
                'desc'          =>  __( 'You can override heading typography of blog listing elements with enabling this option.', '' ),
                'preview'       =>  true,
                'preview_tab'   => 'title',
                'css-echo-default'  => true,
                'css'           => array(
                    array(
                        'selector' => array(
                            '.main-slider-wrapper .block-highlight .title',
                        ),
                        'type'  => 'font',
                    )
                ),
            );
            $field['typo_slider_category_banner'] = array(
                'name'          =>  __( 'Slider Category Banner Typography', 'better-studio' ),
                'id'            =>  'typo_slider_category_banner',
                'type'          =>  'typography',
                'std'           => array(
                    'enable'        =>  false,
                    'family'        =>  'Roboto',
                    'variant'       =>  '400',
                    'subset'        =>  'latin',
                    'size'          =>  '13',
                    'align'         =>  'center',
                    'transform'     =>  'uppercase',
                ),
                'style'         =>  array(
                    'default',
                    'dark',
                    'full-dark',
                    'black',
                    'full-black',
                    'beige',
                    'green',
                    'blue1',
                    'blue2',
                ),
                'desc'          =>  __( 'Slider category banner typography. This overrides base typography settings.', 'better-studio' ),
                'preview'       =>  true,
                'preview_tab'   =>  'title',
                'css-echo-default'  => true,
                'css'           =>  array(
                    array(
                        'selector' => array(
                            '.main-slider .term-title a',
                        ),
                        'type'  => 'font',
                    )
                ),
            );


        /**
         * 5.5.2. => Blog Listing Typography
         */
        $field[] = array(
            'name'  =>  __( 'Blog Listing', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );

        $field['typo_listing_blog_heading'] = array(
            'name'          =>  __( 'Blog Listing Heading Typography', 'better-studio' ),
            'id'            =>  'typo_listing_blog_heading',
            'type'          =>  'typography',
            'std'           =>  array(
                'enable'        => false,
                'family'        => 'Arvo',
                'variant'       => '400',
                'size'          => '15',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#444444'
            ),
            'std-full-dark' =>  array(
                'enable'        => false,
                'family'        => 'Arvo',
                'variant'       => '400',
                'size'          => '15',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#e6e6e6'
            ),
            'std-full-black' =>  array(
                'enable'        => false,
                'family'        => 'Arvo',
                'variant'       => '400',
                'size'          => '15',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#e6e6e6'
            ),
            'std-beige'     =>  array(
                'enable'        => false,
                'family'        => 'Arvo',
                'variant'       => '400',
                'size'          => '15',
                'subset'        => 'latin',
                'align'         => 'initial',
                'transform'     => 'initial',
                'color'         => '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override heading typography of blog listing elements with enabling this option.', '' ),
            'preview'       =>  true,
            'preview_tab'   => 'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.blog-block h2',
                        '.blog-block h2 a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_blog_meta'] = array(
            'name'          =>  __( 'Blog Listing Meta Typography', 'better-studio' ),
            'id'            =>  'typo_listing_blog_meta',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#919191',
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override meta typography of blog listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  =>  true,
            'css'   => array(
                array(
                    'selector' => array(
                        '.mega-menu .blog-block .meta',
                        '.mega-menu .blog-block .meta span',
                        '.mega-menu .blog-block .meta a',
                        '.the-content .blog-block .meta a',
                        '.blog-block .meta a',
                        '.blog-block .meta span',
                        '.blog-block .meta',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_blog_excerpt'] = array(
            'name'          =>  __( 'Blog Listing Excerpt Typography', 'better-studio' ),
            'id'            =>  'typo_listing_blog_excerpt',
            'type'          =>  'typography',
            'std'           =>  array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#717171',
            ),
            'std-full-dark' =>  array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=>  array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     =>  array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override excerpt typography of blog listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  =>  true,
            'css'   => array(
                array(
                    'selector' => array(
                        '.blog-block .summary p, .blog-block .summary',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_blog_read_more'] = array(
            'name'          =>  __( 'Blog Listing Read More Button Typography', 'better-studio' ),
            'id'            =>  'typo_listing_blog_read_more',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'center',
                'transform'     =>  'Uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General read more typography. This overrides base typography settings.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.blog-block .btn-read-more',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_blog_category_banner'] = array(
            'name'          =>  __( 'Blog Listing Category Banner Typography', 'better-studio' ),
            'id'            =>  'typo_listing_blog_category_banner',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'align'         =>  'center',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General read more typography. This overrides base typography settings.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.blog-block .term-title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.5.3. => Modern Listing Typography
         */
        $field[] = array(
            'name'  =>  __( 'Modern Listing', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );
        $field['typo_listing_modern_heading'] = array(
            'name'          =>  __( 'Modern Listing Heading typography', 'better-studio' ),
            'id'            =>  'typo_listing_modern_heading',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override heading typography of modern listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   => 'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.block-modern h2.title',
                        '.block-modern h2.title a',
                        '.main-menu .block-modern h2.title',
                        '.main-menu .block-modern h2.title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_modern_meta'] = array(
            'name'          =>  __( 'Modern Listing Meta Typography', 'better-studio' ),
            'id'            =>  'typo_listing_modern_meta',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#919191',
            ),
            'std-full-dark'  => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'              =>  __( 'You can override meta typography of modern listing elements with enabling this option.', 'better-studio' ),
            'preview'           =>  true,
            'preview_tab'       =>  'title',
            'css-echo-default'  =>  true,
            'css'               =>  array(
                array(
                    'selector' => array(
                        '.the-content .block-modern .meta a',
                        '.block-modern .meta a',
                        '.block-modern .meta span',
                        '.block-modern .meta',
                        '.mega-menu .block-modern .meta',
                        '.mega-menu .block-modern .meta span',
                        '.mega-menu .block-modern .meta a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_modern_excerpt'] = array(
            'name'          =>  __( 'Modern Listing Excerpt Typography', 'better-studio' ),
            'id'            =>  'typo_listing_modern_excerpt',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#717171',
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Roboto Slab',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'line_height'   =>  '20',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          => __( 'You can override excerpt typography of modern listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  =>  true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.block-modern .summary p',
                        '.block-modern .summary',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_modern_category_banner'] = array(
            'name'          =>  __( 'Modern Listing Category Banner Typography', 'better-studio' ),
            'id'            =>  'typo_listing_modern_category_banner',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'align'         =>  'center',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General read more typography. This overrides base typography settings.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.block-modern .term-title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );



        /**
         * 5.5.4. => Highlight Listing Typography
         */
        $field[] = array(
            'name'  =>  __( 'Highlight Listing', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );
        $field['typo_listing_highlight_heading'] = array(
            'name'          =>  __( 'Highlight Listing Heading Typography', 'better-studio' ),
            'id'            =>  'typo_listing_highlight_heading',
            'type'          =>  'typography',
            'std'           =>  array(
                'enable'        => false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark' =>  array(
                'enable'        => false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-full-black'=>  array(
                'enable'        => false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-beige'     =>  array(
                'enable'        => false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '15',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override heading typography of highlight listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.listing-thumbnail h3.title',
                        '.listing-thumbnail h3.title a',
                        '.mega-menu .listing-thumbnail h3.title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_highlight_meta'] = array(
            'name'          =>  __( 'Highlight Listing Meta Typography', 'better-studio' ),
            'id'            =>  'typo_listing_highlight_meta',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#919191',
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override meta typography of highlight listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  =>  true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.the-content .block-highlight .meta a',
                        '.block-highlight .meta a',
                        '.block-highlight .meta span',
                        '.block-highlight .meta',
                        '.mega-menu .block-highlight .meta',
                        '.mega-menu .block-highlight .meta span',
                        '.mega-menu .block-highlight .meta a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_highlight_category_banner'] = array(
            'name'          =>  __( 'Highlight Listing Category Banner Typography', 'better-studio' ),
            'id'            =>  'typo_listing_highlight_category_banner',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '13',
                'align'         =>  'center',
                'transform'     =>  'uppercase',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'General read more typography. This overrides base typography settings.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           =>  array(
                array(
                    'selector' => array(
                        '.bf-shortcode .block-highlight .term-title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.5.5. => Thumbnail Listing Typography
         */
        $field[] = array(
            'name'  =>  __( 'Thumbnail Listing', 'better-studio' ),
            'id'    =>  'typo_listing_thumbnail_header',
            'type'  =>  'group',
            'state' => 'close',
        );
        $field['typo_listing_thumbnail_heading'] = array(
            'name'          =>  __( 'Thumbnail Listing Heading Typography', 'better-studio' ),
            'id'            =>  'typo_listing_thumbnail_heading',
            'type'          =>  'typography',
            'std'           =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark' =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-full-black'=>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-beige'     =>  array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override heading typography of thumbnail listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.listing-thumbnail h3.title a',
                        '.listing-thumbnail h3.title',
                        '.main-menu .listing-thumbnail h3.title',
                        '.main-menu .listing-thumbnail h3.title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );

        $field['typo_listing_thumbnail_meta'] = array(
            'name'          =>  __( 'Thumbnail Listing Meta Typography', 'better-studio' ),
            'id'            =>  'typo_listing_thumbnail_meta',
            'type'          =>  'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#919191',
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6',
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Roboto',
                'variant'       =>  '400',
                'subset'        =>  'latin',
                'size'          =>  '12',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c',
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override meta typography of thumbnail listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  =>  true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.the-content .listing-thumbnail li .meta a',
                        '.listing-thumbnail li .meta a',
                        '.listing-thumbnail li .meta span',
                        '.listing-thumbnail li .meta',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.5.6. => Simple Listing Typography
         */
        $field[] = array(
            'name'  =>  __( 'Simple Listing', 'better-studio' ),
            'type'  =>  'group',
            'state' => 'close',
        );
        $field['typo_listing_simple_heading'] = array(
            'name'          => __( 'Simple Listing Heading Typography', 'better-studio' ),
            'id'            => 'typo_listing_simple_heading',
            'type'          => 'typography',
            'std'           => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#444444'
            ),
            'std-full-dark' => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-full-black'=> array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#e6e6e6'
            ),
            'std-beige'     => array(
                'enable'        =>  false,
                'family'        =>  'Arvo',
                'variant'       =>  '400',
                'size'          =>  '13',
                'subset'        =>  'latin',
                'align'         =>  'initial',
                'transform'     =>  'initial',
                'color'         =>  '#493c0c'
            ),
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'You can override heading typography of simple listing elements with enabling this option.', 'better-studio' ),
            'preview'       =>  true,
            'preview_tab'   =>  'title',
            'css-echo-default'  => true,
            'css'           => array(
                array(
                    'selector' => array(
                        '.listing-simple li h3.title',
                        '.listing-simple li h3.title a',
                        '.main-menu .listing-simple li h3.title',
                        '.main-menu .listing-simple li h3.title a',
                    ),
                    'type'  => 'font',
                )
            ),
        );


        /**
         * 5.6. => Color Options
         */
        $field[] = array(
            'name'      =>  __( 'Style & Color', 'better-studio' ),
            'id'        =>  'color_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-paint'
        );
        $field['style'] = array(
            'name'          =>  __( 'Pre-defined Styles', 'better-studio' ),
            'id'            =>  'style',
            'std'           =>  'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'       => array(
                'default'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-silver.png',
                    'label'     =>  __( 'Silver Skin (Default)', 'better-studio' ),
                ),
                'beige'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-beige.png',
                    'label'     =>  __( 'Beige Skin', 'better-studio' ),
                ),
                'black'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-black.png',
                    'label'     =>  __( 'Half Black Skin', 'better-studio' ),
                ),
                'full-black'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-full-black.png',
                    'label'     =>  __( 'Full Black Skin', 'better-studio' ),
                ),
                'dark'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-dark.png',
                    'label'     =>  __( 'Half Dark Skin', 'better-studio' ),
                ),
                'full-dark'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-full-dark.png',
                    'label'     =>  __( 'Full Dark Skin', 'better-studio' ),
                ),
                'blue1'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-blue1.png',
                    'label'     =>  __( 'Blue 1 Skin', 'better-studio' ),
                ),
                'blue2'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-blue2.png',
                    'label'     =>  __( 'Blue 2 Skin', 'better-studio' ),
                ),
                'green'  => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-better-mag-green.png',
                    'label'     =>  __( 'Green Skin', 'better-studio' ),
                ),
            ),
            'desc'          => __( 'Select a predefined style or create your own customized one below. <br><br> <strong>WARNING :</strong> With changing style some color and other options will be changes.', 'better-studio' )
        );

        /**
         * 5.6.1. => General Colors
         */
        $field[] = array(
            'name'      =>  __( 'General Colors', 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'open',
        );
        $field['theme_color'] = array(
            'name'          =>   __( 'Theme Color', 'better-studio' ),
            'id'            =>  'theme_color',
            'type'          =>  'color',
            'std'           =>  '#e44e4f',
            'std-green'     =>  '#398315',
            'std-blue1'     =>  '#41638a',
            'std-blue2'     =>  '#0ca4dd',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'It is the contrast color for the theme. It will be used for all links, menu, category overlays, main page and many contrasting elements.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-bg-color,.main-bg-color',
                        '.bf-news-ticker .heading',
                        '.main-menu .menu .better-custom-badge',
                        '.widget.widget_nav_menu .menu .better-custom-badge',
                        'body .mejs-controls .mejs-time-rail .mejs-time-current',
                        '.widget.widget_nav_menu li a:hover',
                        '.btn-read-more',
                        '.button-primary',
                        '.pagination > span,.pagination .wp-pagenavi a:hover,.pagination .page-numbers:hover',
                        '.pagination .wp-pagenavi .current,.pagination .current',
                        '.flex-control-nav li a.flex-active, .flex-control-nav li:hover a',
                        '.term-title a',
                        '.rating-bar span',
                        'input[type=submit]',
                        '.main-menu .menu > li.random-post:hover > a',
                        '.main-menu .main-menu-container.mobile-menu-container .mobile-button .fa',
                        '.section-heading.extended .other-links .other-item:hover a',
                        '.section-heading.extended.tab-heading .other-links .other-item.active a',
                        '.page-heading:before',
                        'body .mejs-controls .mejs-time-rail .mejs-time-current',
                        '.comments li.comment.bypostauthor > article.comment .comment-edit-link',
                        '.comments li.comment.bypostauthor > article.comment .comment-reply-link',
                        '.comments .comment-respond #cancel-comment-reply-link',
                        '.comments .comment-respond .form-submit input[type=submit]',
                        '.widget.widget_nav_menu li a:hover',
                        '.betterstudio-review .verdict .overall',
                        '.error404 .content-column .search-form .search-submit',
                        '.main-menu .search-item .search-form:hover,.main-menu .search-item .search-form.have-focus',
                        'span.dropcap.square',
                        'span.dropcap.circle',
                        '.block-user-row .posts-count',
                        '.block-user-modern .posts-count',
                    ),
                    'prop'      => array(
                        'background-color' =>   '%%value%%',
                    )
                ),
                array(
                    'selector'  =>  array(
                        '.main-color',
                        '.bf-news-ticker ul.news-list li a:hover',
                        '.bf-news-ticker ul.news-list li a:focus',
                        '.rating-stars span:before',
                        '.footer-lower-wrapper a:hover',
                        '.bf-breadcrumb .trail-browse',
                        '.comments li.comment.bypostauthor > article.comment .comment-author a',
                        '.comments li.comment.bypostauthor > article.comment .comment-author',
                        '.widget.widget_calendar table td a',
                        '.widget .tagcloud a:hover',
                        'span.dropcap.circle-outline',
                        'span.dropcap.square-outline',
                        '.the-content.site-map ul li a:hover',
                        '.tab-content-listing .tab-read-more a',
                        '.widget .tab-read-more a:hover',
                        '.archive-section a:hover',
                        '.comments .pingback .comment-edit-link:hover, .comments article.comment .comment-edit-link:hover, .comments article.comment .comment-reply-link:hover',
                    ),
                    'prop'      =>  array( 'color' )
                ),
                array(
                    'selector'  =>  array(
                        '.top-bar .widget.widget_nav_menu li:hover > a',
                    ),
                    'prop'      =>  array(
                        'background-color' => "%%value%% !important",
                    )
                ),
                array(
                    'selector'  =>  array(
                        '.main-menu .menu > li:hover > a',
                        '.main-menu .menu > .current-menu-ancestor > a',
                        '.main-menu .menu > .current-menu-parent > a',
                        '.main-menu .menu > .current-menu-item > a',
                        '.widget.widget_recent_comments a:hover',
                        '.footer-larger-widget.widget.widget_recent_comments a:hover',
                        '.comments li.comment.bypostauthor > article.comment',
                        '.section-heading.extended.tab-heading',
                    ),
                    'prop'      =>  array( 'border-bottom-color' )
                ),
                array(
                    'selector'  => array(
                        '.main-menu .menu .better-custom-badge:after',
                    ),
                    'prop'      => array( 'border-top-color' )
                ),
                array(
                    'selector'  => array(
                        '.bf-news-ticker .heading:after',
                        '.main-menu .menu .sub-menu .better-custom-badge:after',
                        '.rtl .main-menu .mega-menu .menu-badge-right > a > .better-custom-badge:after',
                        'body .main-menu .menu .mega-menu .menu-badge-left > a > .better-custom-badge:after',
                    ),
                    'prop'      => array( 'border-left-color' )
                ),
                array(
                    'selector'  =>  array(
                        '.rtl .bf-news-ticker .heading:after',
                        '.main-menu .mega-menu .menu-badge-right > a > .better-custom-badge:after',
                        '.widget.widget_nav_menu .menu .better-custom-badge:after',
                    ),
                    'prop'      =>  array( 'border-right-color' )
                ),
                array(
                    'selector'  =>  array(
                        '.widget .tagcloud a:hover',
                        'span.dropcap.circle-outline',
                        'span.dropcap.square-outline',
                        '.better-gallery .fotorama__thumb-border',
                    ),
                    'prop'      =>  array( 'border-color' )
                ),
                array(
                    'selector'  =>  array(
                        'div.pp_default .pp_gallery ul li a:hover',
                        'div.pp_default .pp_gallery ul li.selected a',
                    ),
                    'prop'      =>  array( 'border-color' => '%%value%% !important' )
                ),
                array(
                    'selector'  =>  array(
                        '::selection'
                    ),
                    'prop'      =>  array( 'background' )
                ),
                array(
                    'selector'  =>  array(
                        '::-moz-selection'
                    ),
                    'prop'      =>  array( 'background' )
                ),
                // WooCommerce styles if is active
                array(
                    'selector'  =>  array(
                        '.bm-wc-cart .cart-link .total-items',
                        '.main-wrap ul.product_list_widget li del, .main-wrap ul.product_list_widget li .amount',
                        '.woocommerce .star-rating span:before, .woocommerce-page .star-rating span:before',
                        '.woocommerce #content div.product p.price del, .woocommerce #content div.product span.price del, .woocommerce div.product p.price del, .woocommerce div.product span.price del, .woocommerce-page #content div.product p.price del, .woocommerce-page #content div.product span.price del, .woocommerce-page div.product p.price del, .woocommerce-page div.product span.price del, .woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del',
                        '.woocommerce #content div.product p.price, .woocommerce #content div.product span.price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page div.product span.price, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price',
                        '.woocommerce .star-rating span:before,.woocommerce-page .star-rating span:before',
                        '.woocommerce p.stars a.star-1.active:after,.woocommerce p.stars a.star-2.active:after,.woocommerce p.stars a.star-3.active:after,.woocommerce p.stars a.star-4.active:after,.woocommerce p.stars a.star-5.active:after,.woocommerce-page p.stars a.star-1.active:after,.woocommerce-page p.stars a.star-2.active:after,.woocommerce-page p.stars a.star-3.active:after,.woocommerce-page p.stars a.star-4.active:after,.woocommerce-page p.stars a.star-5.active:after',
                        '.woocommerce #content table.cart a.remove,.woocommerce table.cart a.remove,.woocommerce-page #content table.cart a.remove,.woocommerce-page table.cart a.remove',
                    ),
                    'prop'      =>  'color',
                    'filter'    =>  array( 'woocommerce' ),
                ),
                array(
                    'selector'  =>  array(
                        '.woocommerce span.onsale, .woocommerce-page span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale',
                        'a.button.add_to_cart_button:hover',
                        '.woocommerce #content input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce-page #content input.button:hover,.woocommerce-page #respond input#submit:hover,.woocommerce-page a.button:hover,.woocommerce-page button.button:hover,.woocommerce-page input.button:hover,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order,.woocommerce #review_form #respond .form-submit input:hover,.woocommerce-page #review_form #respond .form-submit input:hover,button.button.single_add_to_cart_button.alt:hover',
                        '.woocommerce-account .woocommerce .address .title h3:before,.woocommerce-account .woocommerce h2:before,.cross-sells h2:before,.related.products h2:before,.woocommerce #reviews h3:before,.woocommerce-page #reviews h3:before,.woocommerce-tabs .panel.entry-content h2:before,.woocommerce .shipping_calculator h2:before,.woocommerce .cart_totals h2:before,h3#order_review_heading:before ,.woocommerce-shipping-fields h3:before ,.woocommerce-billing-fields h3:before ',
                        '.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,.woocommerce div.product .woocommerce-tabs ul.tabs li.active,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active',
                        '.woocommerce #content table.cart a.remove:hover,.woocommerce table.cart a.remove:hover,.woocommerce-page #content table.cart a.remove:hover,.woocommerce-page table.cart a.remove:hover',
                        '.woocommerce .cart-collaterals .shipping_calculator .button, .woocommerce-page .cart-collaterals .shipping_calculator .button,.woocommerce .cart .button.checkout-button,.woocommerce .cart .button:hover,.woocommerce .cart input.button:hover,.woocommerce-page .cart .button:hover,.woocommerce-page .cart input.button:hover',
                        '.main-wrap .mega-menu.cart-widget.widget_shopping_cart .buttons a',
                        '.main-wrap .widget.widget_price_filter .ui-slider-range',
                        '.main-wrap .widget.widget_price_filter .ui-slider .ui-slider-handle',
                        '.woocommerce .widget_layered_nav ul li.chosen a,.woocommerce-page .widget_layered_nav ul li.chosen a',
                        '.woocommerce #content .quantity .minus:hover,.woocommerce #content .quantity .plus:hover,.woocommerce .quantity .minus:hover,.woocommerce .quantity .plus:hover,.woocommerce-page #content .quantity .minus:hover,.woocommerce-page #content .quantity .plus:hover,.woocommerce-page .quantity .minus:hover,.woocommerce-page .quantity .plus:hover',
                    ),
                    'prop'      =>  'background-color',
                    'filter'    =>  array( 'woocommerce' ),
                ),
                array(
                    'selector'  =>  array(
                        '.woocommerce #content div.product .woocommerce-tabs ul.tabs,.woocommerce div.product .woocommerce-tabs ul.tabs,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs,.woocommerce-page div.product .woocommerce-tabs ul.tabs',
                        '.woocommerce .widget_layered_nav ul li.chosen a,.woocommerce-page .widget_layered_nav ul li.chosen a',
                    ),
                    'prop'      =>  'border-bottom-color',
                    'filter'    =>  array( 'woocommerce' ),
                ),
                // bbPress styles if is active
                array(
                    'selector'  =>  array(
                        '#bbpress-forums li.bbp-forum-info.single-forum-info .bbp-forum-title:before',
                        '#bbpress-forums .bbp-forums-list li:before',
                        '#bbpress-forums p.bbp-topic-meta .freshness_link a',
                        '#bbpress-forums .bbp-forums-list li a',
                    ),
                    'prop'      =>  'color',
                    'filter'    =>  array( 'bbpress' ),
                ),
                array(
                    'selector'  =>  array(
                        '#bbpress-forums #bbp-search-form #bbp_search_submit',
                        '#bbpress-forums li.bbp-header:before',
                        '#bbpress-forums button.user-submit, .bbp-submit-wrapper button',
                        '#bbpress-forums li.bbp-header:before',
                    ),
                    'prop'      =>  'background-color',
                    'filter'    =>  array( 'bbpress' ),
                ),
                // BuddyPress styles if is active
                array(
                    'selector'  =>  array(
                        '#buddypress .dir-search input[type=submit]',
                        '#buddypress div.item-list-tabs ul li.current a span',
                        '#buddypress div.item-list-tabs ul li.selected a span',
                        '#buddypress div.activity-meta a:hover',
                        '#buddypress div.item-list-tabs ul li a',
                        '#buddypress div#item-header div#item-meta a',
                        '#buddypress .acomment-meta a',
                        '#buddypress .activity-header a',
                        '#buddypress .comment-meta a',
                        '#buddypress .activity-list .activity-content .activity-inner a',
                        '#buddypress .activity-list .activity-content blockquote a',
                        '#buddypress table.profile-fields > tbody > tr > td.data a',
                        '#buddypress table.notifications a',
                        '#buddypress table#message-threads a',
                        '#buddypress div.messages-options-nav a',
                        '#buddypress ul.item-list li div.item-title a',
                        '#buddypress ul.item-list li h5 a',
                    ),
                    'prop'      =>  'color',
                    'filter'    =>  array( 'buddypress' ),
                ),
                array(
                    'selector'  =>  array(
                        '#buddypress .dir-search input[type=submit]',
                        '#buddypress a.button,#buddypress a.button:hover,#buddypress button,#buddypress button:hover,#buddypress div.generic-button a,#buddypress div.generic-button a:hover,#buddypress input[type=button],#buddypress input[type=button]:hover,#buddypress input[type=reset],#buddypress input[type=reset]:hover,#buddypress input[type=submit],#buddypress input[type=submit]:hover,#buddypress ul.button-nav li a,#buddypress ul.button-nav li a:hover,a.bp-title-button:hover ,a.bp-title-button',
                    ),
                    'prop'      =>  'border-color',
                    'filter'    =>  array( 'buddypress' ),
                ),
                array(
                    'selector'  =>  array(
                        '#buddypress a.button,#buddypress a.button:hover,#buddypress button,#buddypress button:hover,#buddypress div.generic-button a,#buddypress div.generic-button a:hover,#buddypress input[type=button],#buddypress input[type=button]:hover,#buddypress input[type=reset],#buddypress input[type=reset]:hover,#buddypress input[type=submit],#buddypress input[type=submit]:hover,#buddypress ul.button-nav li a,#buddypress ul.button-nav li a:hover,a.bp-title-button:hover ,a.bp-title-button',
                        '#buddypress div.item-list-tabs ul li.current a',
                        '#buddypress div.item-list-tabs ul li.selected a',
                        '#buddypress div.activity-meta a:hover span',
                    ),
                    'prop'      =>  array(
                        'background' => '%%value%%',
                        'color'      => '#fff',
                    ),
                    'filter'    =>  array( 'buddypress' ),
                ),
                array(
                    'selector'  =>  array(
                        '#buddypress div.item-list-tabs ul li a:hover span',
                    ),
                    'prop'      =>  array(
                        'background' => '%%value%%',
                        'color'      => '#fff',
                    ),
                    'filter'    =>  array( 'buddypress' ),
                ),
            ),
        );
        $field['bg_color'] = array(
            'name'          =>  __( 'Body Background Color', 'better-studio' ),
            'id'            =>  'bg_color',
            'type'          =>  'color',
            'std'           =>  '#ffffff',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#253545',
            'std-full-black'=>  '#2e2e2e',
            'std-beige'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'Setting a body background image below will override it.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body,body.boxed'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            )
        );
        $field['bg_main_column_color'] = array(
            'name'          =>  __( 'Main Column Background Color', 'better-studio' ),
            'id'            =>  'bg_main_column_color',
            'type'          =>  'color',
            'std-full-dark' =>  '#293b4d',
            'std-full-black'=>  '#2b2b2b',
            'std-beige'     =>  '#ffffff',
            'style'         =>  array(
                'full-dark',
                'full-black',
                'beige',
            ),
            'desc'          =>  __( 'This will be used as main column background in "boxed" and "Boxed (Padded)" layout style.', 'better-studio' ),
            'css-full-dark' =>  array(
                array(
                    'selector'  => array(
                        '.boxed .main-wrap'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            ),
            'css-full-black'=>  array(
                array(
                    'selector'  => array(
                        '.boxed .main-wrap'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            ),
            'css-beige'=>  array(
                array(
                    'selector'  => array(
                        '.boxed .main-wrap'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            ),

        );

        $field['bg_image'] = array(
            'name'          =>  __( 'Body Background Image', 'better-studio' ),
            'id'            =>  'bg_image',
            'type'          =>  'background_image',
            'std'           =>  '',
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            )
        );

        $field['color_content_link'] = array(
            'name'          =>  __( 'Links color in the main content', 'better-studio' ),
            'id'            =>  'color_content_link',
            'type'          =>  'color',
            'desc'          =>  __( 'Changes all the links color within posts and pages.', 'better-studio'),
            'std'           =>  '#e44e4f',
            'std-green'     =>  '#398315',
            'std-blue1'     =>  '#41638a',
            'std-blue2'     =>  '#0ca4dd',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'       =>  array(
                array(
                    'selector'  => '.the-content a' ,
                    'prop'      => 'color'
                )
            )
        );

        $field['color_image_gradient'] = array(
            'name'          =>  __( 'Images overlay gradient color', 'better-studio' ),
            'id'            =>  'color_image_gradient',
            'type'          =>  'color',
            'desc'          =>  __( 'Changes all the links color within posts and pages.', 'better-studio'),
            'std'           =>  '#222222',
            'std-dark'      =>  '#0f1e2c',
            'std-full-dark' =>  '#0f1e2c',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'       =>  array(
                array(
                    'selector'  => array(
                        '.block-modern .meta',
                        '.block-highlight .content',
                    ),
                    'prop'      => array(
                        'background'    =>  "-moz-linear-gradient(top,  rgba(0,0,0,0) 0%, %%value%% 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,%%value%%));
    background: -webkit-linear-gradient(top,  rgba(0,0,0,0) 0%,%%value%% 100%);
    background: -o-linear-gradient(top,  rgba(0,0,0,0) 0%,%%value%% 100%);
    background: -ms-linear-gradient(top,  rgba(0,0,0,0) 0%,%%value%% 100%);
    background: linear-gradient(to bottom,  rgba(0,0,0,0) 0%,%%value%% 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='%%value%%',GradientType=0 );"
                    )
                )
            )
        );

        /**
         * 5.6.2. => Header
         */
        $field[] = array(
            'name'          =>  __( 'Header', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['topbar_bg_color'] = array(
            'name'          =>  __( 'Top Bar Background Color', 'better-studio' ),
            'id'            =>  'topbar_bg_color',
            'type'          =>  'color',
            'std'           =>  '#f2f2f2',
            'std-full-dark' =>  '#3c546b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#f5efd8',
            'std-blue2'=> '#f1f8fb',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'       =>  array(
                array(
                    'selector'  => array(
                        '.top-bar',
                        '.top-bar .widget.widget_nav_menu ul.menu > li > a',
                    ),
                    'prop'      => 'background-color'
                )
            )
        );
        $field['header_bg_color'] = array(
            'name'          =>  __( 'Header Background Color', 'better-studio' ),
            'id'            =>  'header_bg_color',
            'type'          =>  'color',
            'std'           =>  '',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'desc'          =>  __( 'Setting a header background pattern below will override it.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => '.header' ,
                    'prop'      => 'background-color'
                )
            )
        );
        $field['header_bg_image'] = array(
            'name'          =>  __( 'Header Background Image', 'better-studio' ),
            'id'            =>  'header_bg_image',
            'type'          =>  'background_image',
            'std'           =>  '',
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the background color option.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.header'
                    ),
                    'prop'      => 'background-image',
                    'type'      => 'background-image'
                )
            )
        );

        /**
         * 5.6.3. => Main Navigation
         */
        $field[] = array(
            'name'      => __( 'Main Navigation', 'better-studio' ),
            'type'      => 'group',
            'state'     =>  'close',
        );
        $field['menu_bg_color'] = array(
            'name'          =>  __( 'Menu Background Color', 'better-studio' ),
            'id'            =>  'menu_bg_color',
            'type'          =>  'color',
            'std'           =>  '#e0e0e0',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#3c546b',
            'std-black'     =>  '#3b3b3b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#f5edd0',
            'std-green'     =>  '#77bb24',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu',
                        '.main-menu.boxed .main-menu-container',
                    ),
                    'prop'      => array( 'background-color' )
                ),
            ),
        );
        $field['menu_line_border_option_std'] = array(
            'name'          =>  __( 'Menu Separator Color in Responsive Navigation', 'better-studio' ),
            'id'            =>  'menu_line_border_option_std',
            'type'          =>  'color',
            'std'           =>  '#cacaca',
            'std-green'     =>  '#3f8f17',
            'std-blue1'     =>  '#35639a',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css' =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .mobile-menu-container .mega-menu.style-link .sub-menu li:first-child',
                        '.main-menu .mobile-menu-container .mega-menu.style-link li:first-child',
                        '.main-menu .mobile-menu-container .menu .sub-menu li:first-child',
                    ),
                    'prop'      => array(
                        'border-top-color'
                    )
                ),
                array(
                    'selector'  => array(
                        '.main-menu .mobile-menu-container .menu li .sub-menu li',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active .sub-menu li',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li',
                        '.main-menu .mobile-menu-container .menu > li',
                        '.main-menu .main-menu-container.mobile-menu-container.active .mobile-button',
                    ),
                    'prop'      => array(
                        'border-bottom' => '1px solid %%value%%',
                    )
                ),
                array(
                    'selector'  => array(
                        '.main-menu .mobile-menu-container .menu > li.alignright > a.children-button',
                        '.main-menu .mobile-menu-container .menu li .children-button',
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%% !important'
                    )
                ),
            ),

        );
        $field['menu_bottom_line_color'] = array(
            'name'          =>  __( 'Menu Border Below Color', 'better-studio' ),
            'id'            =>  'menu_bottom_line_color',
            'type'          =>  'color',
            'std'           =>  '#b7b7b7',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#707070',
            'std-full-black'=>  '#707070',
            'std-beige'     =>  '#d9c680',
            'std-green'     =>  '#509e29',
            'std-blue1'     =>  '#35639a',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu',
                        '.main-menu.boxed .main-menu-container',
                    ),
                    'prop'      => array( 'border-bottom-color' )
                ),
                array(
                    'selector'  => array(
                        '.main-menu .menu>li.random-post>a',
                        '.main-menu .search-item .search-form',
                    ),
                    'prop'      => array( 'background-color' )
                ),

            ),
        );
        $field['menu_text_color'] = array(
            'name'          =>  __( 'Menu Text Color', 'better-studio' ),
            'id'            =>  'menu_text_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu>li>a',
                        '.main-menu .search-item .search-form .search-submit',
                        '.main-menu .main-menu-container.mobile-menu-container .mobile-button a',
                    ),
                    'prop'      => 'color'
                ),
            ),
        );
        $field['menu_current_bg_color'] = array(
            'name'          =>  __( 'Menu Current Page Background Color', 'better-studio' ),
            'id'            =>  'menu_current_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#e6dab0',
            'std-green'     =>  '#509e29',
            'std-blue1'     =>  '#35639a',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active > a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active:hover > a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active .sub-menu li > a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active:hover > a',
                        '.main-menu .menu>.current-menu-ancestor>a',
                        '.main-menu .menu>.current-menu-parent>a',
                        '.main-menu .menu>.current-menu-item>a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link a',
                        '.main-menu .mobile-menu-container li.active > a',
                    ),
                    'prop'      => 'background-color'
                ),
            ),
        );
        $field['menu_current_font_color'] = array(
            'name'          =>  __( 'Menu Current Page Text Color', 'better-studio' ),
            'id'            =>  'menu_current_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu > .current-menu-ancestor > a',
                        '.main-menu .menu > .current-menu-parent > a',
                        '.main-menu .menu > .current-menu-item > a',
                    ),
                    'prop'      => 'color'
                ),
            ),
        );
        $field['menu_hover_bg_color'] = array(
            'name'          =>  __( 'Menu Hover Background Color', 'better-studio' ),
            'id'            =>  'menu_hover_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#e6dab0',
            'std-green'     =>  '#509e29',
            'std-blue1'     =>  '#537fb1',
            'std-blue2'     =>  '#4eb5db',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active .sub-menu li:hover > a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li.active > a, .main-menu .mobile-menu-container .mega-menu.style-link > li.active:hover > a, .main-menu .mobile-menu-container .mega-menu.style-link > li.active .sub-menu li > a',
                        '.main-menu .menu > li:hover > a',
                        '.main-menu .mobile-menu-container .menu > li.alignright > a.children-button',
                        '.main-menu .mobile-menu-container .menu li .children-button',
                        '.main-menu .mobile-menu-container .menu > li.alignright:hover > a',
                        '.main-menu .mobile-menu-container .mega-menu.style-link > li:hover > a',
                    ),
                    'prop'      => 'background-color'
                ),
            ),
        );
        $field['menu_hover_font_color'] = array(
            'name'          =>  __( 'Menu Hover Page Text Color', 'better-studio' ),
            'id'            =>  'menu_hover_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu > li:hover > a',
                        '.main-menu .mobile-menu-container .menu > li.alignright:hover > a',
                    ),
                    'prop'      => 'color'
                ),
            ),
        );
        $field['menu_large_desc_font_color'] = array(
            'name'          =>  __( 'Large Menu Description Font Color', 'better-studio' ),
            'id'            =>  'menu_large_desc_font_color',
            'type'          =>  'color',
            'std'           =>  '#676767',
            'std-beige'     =>  '#776A38',
            'std-black'     =>  '#B8B8B8',
            'std-full-black'=>  '#B8B8B8',
            'std-dark'      =>  '#B8B8B8',
            'std-full-dark' =>  '#B8B8B8',
            'std-green'     =>  '#EFEFEF',
            'std-blue1'     =>  '#E7E7E7',
            'std-blue2'     =>  '#F9F9F9',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu.style-large .desktop-menu-container .menu > li > a > .description',
                    ),
                    'prop'      => 'color'
                ),
            ),
        );

        /**
         * 5.6.4. => Main Navigation - Drop Down Sub Menu
         */
        $field[] = array(
            'name'      =>  __( 'Main Navigation - Drop Down Sub Menu', 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );

        $field['menu_sub_bg_color'] = array(
            'name'          =>  __( 'Sub Menu Background Color', 'better-studio' ),
            'id'            =>  'menu_sub_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#304254',
            'std-black'     =>  '#3b3b3b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#e6dab0',
            'std-green'     =>  '#73b352',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#55bae0',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu > li > .sub-menu',
                        '.desktop-menu-container .menu > li > .sub-menu .sub-menu',
                    ),
                    'prop'      => array( 'background-color' )
                ),
                array(
                    'filter'    => array( 'woocommerce' ),
                    'selector'  => array(
                        '.desktop-menu-container .mega-menu.cart-widget.widget_shopping_cart ul.cart_list li',
                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_sub_text_color'] = array(
            'name'          =>  __( 'Sub Menu Text Color', 'better-studio' ),
            'id'            =>  'menu_sub_text_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu > li > .sub-menu li a',
                    ),
                    'prop'      => array( 'color' )
                ),
            )
        );
        $field['menu_sub_separator_color'] = array(
            'name'          =>  __( 'Sub Menu Separator Line Color', 'better-studio' ),
            'id'            =>  'menu_sub_separator_color',
            'type'          =>  'color',
            'std'           =>  '#b7b7b7',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#61a33e',
            'std-blue1'     =>  '#4a719e',
            'std-blue2'     =>  '#45b5df',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu>li>.sub-menu li',
                        '.desktop-menu-container .mega-menu.style-link > li',
                        '.desktop-menu-container .menu > li',
                    ),
                    'prop'      => array( 'border-bottom-color' )
                ),
            )
        );
        $field['menu_sub_current_bg_color'] = array(
            'name'          =>  __( 'Sub Menu Current Page Background Color','better-studio'),
            'id'            =>  'menu_sub_current_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#509e29',
            'std-blue1'     =>  '#35639a',
            'std-blue2'     =>  '#3cb2de',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu>li >.sub-menu li.current_page_item>a',
                        '.desktop-menu-container .menu>li >.sub-menu li.current-menu-item>a',
                        '.desktop-menu-container .menu>li >.sub-menu li.current-menu-parent>a',
                        '.desktop-menu-container .menu>li >.sub-menu li.current-menu-ancestor>a',

                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_sub_current_font_color'] = array(
            'name'          =>  __( 'Sub Menu Current Page Text Color', 'better-studio' ),
            'id'            =>  'menu_sub_current_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu>li>.sub-menu li.current_page_item>a',
                        '.desktop-menu-container .menu>li>.sub-menu li.current-menu-item>a',
                        '.desktop-menu-container .menu>li>.sub-menu li.current-menu-parent>a',
                        '.desktop-menu-container .menu>li>.sub-menu li.current-menu-ancestor>a',
                    ),
                    'prop'      => 'color'
                ),
            )
        );
        $field['menu_sub_hover_bg_color'] = array(
            'name'          =>  __( 'Sub Menu Hover Background Color', 'better-studio' ),
            'id'            =>  'menu_sub_hover_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#5aad31',
            'std-blue1'     =>  '#537fb1',
            'std-blue2'     =>  '#3cb2de',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu>li>.sub-menu>li:hover>a',
                        '.desktop-menu-container .menu>li>.sub-menu .sub-menu>li:hover>a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'filter'    => array( 'woocommerce' ),
                    'selector'  => array(
                        '.desktop-menu-container .mega-menu.cart-widget.widget_shopping_cart ul.cart_list li:hover',
                    ),
                    'prop'      => array( 'background-color' )
                ),
            )
        );
        $field['menu_sub_hover_font_color'] = array(
            'name'          =>  __( 'Sub Menu Hover Text Color', 'better-studio' ),
            'id'            =>  'menu_sub_hover_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.desktop-menu-container .menu>li>.sub-menu>li:hover>a',
                        '.desktop-menu-container .menu>li>.sub-menu .sub-menu>li:hover>a',
                    ),
                    'prop'      => 'color'
                ),
                array(
                    'filter'    => array( 'woocommerce' ),
                    'selector'  => array(
                        '.mega-menu.cart-widget.widget_shopping_cart ul.cart_list li',
                        '.mega-menu.cart-widget.widget_shopping_cart ul.cart_list a',
                        '.mega-menu.cart-widget.widget_shopping_cart ul.cart_list p',
                        '.main-wrap .widget_shopping_cart .total',
                        '.main-wrap .widget_shopping_cart .total .amount',
                        '.main-wrap ul.product_list_widget li .quantity',
                    ),
                    'prop'      => array( 'color' )
                ),
            )
        );


        /**
         * 5.6.5. => Main Navigation - Mega Menu
         */
        $field[] = array(
            'name'          =>  __( 'Main Navigation - Mega Menu', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['menu_mega_bg_color'] = array(
            'name'          =>  __( 'Mega Menu Background Color', 'better-studio' ),
            'id'            =>  'menu_mega_bg_color',
            'type'          =>  'color',
            'std'           =>  '#e0e0e0',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#304254',
            'std-black'     =>  '#3b3b3b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#e6dab0',
            'std-green'     =>  '#73b352',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#91d4ef',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .mega-menu',
                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_mega_links_bg_color'] = array(
            'name'          =>  __( 'Mega Menu Links Background Color', 'better-studio' ),
            'id'            =>  'menu_mega_links_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#253442',
            'std-full-dark' =>  '#253442',
            'std-black'     =>  '#242424',
            'std-full-black'=>  '#242424',
            'std-beige'     =>  '#e0d19b',
            'std-green'     =>  '#5aad31',
            'std-blue1'     =>  '#436a97',
            'std-blue2'     =>  '#73cbee',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu > li > .mega-menu .mega-menu-links',
                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_mega_text_color'] = array(
            'name'          =>  __( 'Mega Menu Text Color', 'better-studio' ),
            'id'            =>  'menu_mega_text_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.mega-menu.style-link > li > a',
                        '.main-menu .menu > li .sub-menu > li > a',
                        '.main-menu .mega-menu .listing-simple li h3.title a',
                        '.main-menu .mega-menu .block-modern h2.title a',
                        '.main-menu .mega-menu .listing-thumbnail h3.title a',
                        '.main-menu .mega-menu .blog-block h2 a',
                    ),
                    'prop'      => array( 'color' )
                ),
            )
        );
        $field['menu_mega_separator_color'] = array(
            'name'          =>  __( 'Mega Menu Separator Line Color', 'better-studio' ),
            'id'            =>  'menu_mega_separator_color',
            'type'          =>  'color',
            'std'           =>  '#b7b7b7',
            'std-dark'      =>  '#40576e',
            'std-full-dark' =>  '#40576e',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#489d1e',
            'std-blue1'     =>  '#3d618b',
            'std-blue2'     =>  '#3eb3df',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.mega-menu.style-link > li > a',
                        '.mega-menu.style-category > li > a',
                        '.mega-menu.style-link li .sub-menu li',
                        '.mega-menu.style-category li .sub-menu li',
                        '.mega-menu .listing-simple li',
                        '.mega-menu .listing-thumbnail li',
                        '.main-menu .menu li .sub-menu.mega-menu-links .menu-item-has-children > a',
                        '.main-menu .menu li .mega-menu .sub-menu li',
                    ),
                    'prop'      => array( 'border-bottom-color' )
                ),
                array(
                    'selector'  => array(
                        '.mega-menu.style-link li .sub-menu .sub-menu li:first-child',
                    ),
                    'prop'      => 'border-top-color'
                ),
            )
        );
        $field['menu_mega_current_bg_color'] = array(
            'name'          =>  __( 'Mega Menu Links Current Page Background Color','better-studio'),
            'id'            =>  'menu_mega_current_bg_color',
            'type'          =>  'color',
            'std'           =>  '#c8c8c8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#509e29',
            'std-blue1'     =>  '#3b5e86',
            'std-blue2'     =>  '#4cbeeb',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu .mega-menu .sub-menu li.current_page_item>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-item>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-parent>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-ancestor>a',
                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_mega_current_font_color'] = array(
            'name'          =>  __( 'Mega Menu Links Current Page Text Color', 'better-studio' ),
            'id'            =>  'menu_mega_current_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu .mega-menu .sub-menu li.current_page_item>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-item>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-parent>a',
                        '.main-menu .menu .mega-menu .sub-menu li.current-menu-ancestor>a',
                    ),
                    'prop'      => 'color'
                ),
            )
        );
        $field['menu_mega_hover_bg_color'] = array(
            'name'          =>  __( 'Mega Menu Links Hover Background Color', 'better-studio' ),
            'id'            =>  'menu_mega_hover_bg_color',
            'type'          =>  'color',
            'std'           =>  '#d8d8d8',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#5aad31',
            'std-blue1'     =>  '#537fb1',
            'std-blue2'     =>  '#6bc7ec',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu .mega-menu li .sub-menu li:hover > a',
                        '.main-menu .menu > li > .mega-menu.style-category .mega-menu-links a:hover',
                    ),
                    'prop'      => 'background-color'
                ),
            )
        );
        $field['menu_mega_hover_font_color'] = array(
            'name'          =>  __( 'Mega Menu Links Hover Text Color', 'better-studio' ),
            'id'            =>  'menu_mega_hover_font_color',
            'type'          =>  'color',
            'std'           =>  '#3b3b3b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-menu .menu .mega-menu .sub-menu li:hover>a',
                    ),
                    'prop'      => 'color'
                ),
            )
        );
        $field['menu_mega_section_title_font_color'] = array(
            'name'          =>  __( 'Mega Menu Section Title Text Color', 'better-studio' ),
            'id'            =>  'menu_mega_section_title_font_color',
            'type'          =>  'color',
            'std'           =>  '#626262',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.mega-menu .section-heading span.h-title',
                    ),
                    'prop'      => 'color'
                ),
            )
        );
        $field['menu_mega_section_title_bg_color'] = array(
            'name'          =>  __( 'Mega Menu Section Title Background Color', 'better-studio' ),
            'id'            =>  'menu_mega_section_title_bg_color',
            'type'          =>  'color',
            'std'           =>  '#F4F4F3',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#dbcd9b',
            'std-green'     =>  '#3f8f17',
            'std-blue1'     =>  '#3b5e86',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.mega-menu .section-heading span.h-title',
                    ),
                    'prop'      => 'background-color'
                ),
            ),
        );
        $field['menu_mega_section_title_border_color'] = array(
            'name'          =>  __( 'Mega Menu Section Title Border Color', 'better-studio' ),
            'id'            =>  'menu_mega_section_title_border_color',
            'type'          =>  'color',
            'std'           =>  '#c9c9c9',
            'std-dark'      =>  '#4e7499',
            'std-full-dark' =>  '#4e7499',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#c7b987',
            'std-green'     =>  '#3f8f17',
            'std-blue1'     =>  '#3b5e86',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.mega-menu .section-heading',
                    ),
                    'prop'      => 'border-bottom-color'
                ),
            ),
        );

        /**
         * 5.6.6. => Breadcrumb
         */
        $field[] = array(
            'name'          =>  __( 'Breadcrumb', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['color_breadcrumb_bg_color'] = array(
            'name'          =>  __( 'Breadcrumb Background Color', 'better-studio' ),
            'id'            =>  'color_breadcrumb_bg_color',
            'type'          =>  'color',
            'std'           =>  '#f2f2f2',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#304254',
            'std-black'     =>  '#3b3b3b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#f5edd0',
            'std-green'     =>  '#67b20b',
            'std-blue1'     =>  '#49709c',
            'std-blue2'     =>  '#edf8fc',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.bf-breadcrumb-wrapper',
                        '.bf-breadcrumb-wrapper.boxed .bf-breadcrumb',
                    ),
                    'prop'      =>  'background-color'
                )
            )
        );
        $field['color_breadcrumb_font_color'] = array(
            'name'          =>  __( 'Breadcrumb Font Color', 'better-studio' ),
            'id'            =>  'color_breadcrumb_font_color',
            'type'          =>  'color',
            'std'           =>  '#444444',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#f5fff0',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.bf-breadcrumb a',
                    ),
                    'prop'      =>  'color'
                )
            )
        );
        $field['color_breadcrumb_current_font_color'] = array(
            'name'          =>  __( 'Breadcrumb Current Page Text Color', 'better-studio' ),
            'id'            =>  'color_breadcrumb_current_font_color',
            'type'          =>  'color',
            'std'           =>  '#757d81',
            'std-dark'      =>  '#97b5d1',
            'std-full-dark' =>  '#97b5d1',
            'std-black'     =>  '#c4c4c4',
            'std-full-black'=>  '#c4c4c4',
            'std-beige'     =>  '#705e1f',
            'std-green'     =>  '#e4fada',
            'std-blue1'     =>  '#dae7f6',
            'std-blue2'     =>  '#dae7f6',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.bf-breadcrumb .sep',
                        '.bf-breadcrumb .trail-end',
                    ),
                    'prop'      =>  'color'
                )
            )
        );


        /**
         * 5.6.7. => Slider
         */
        $field[] = array(
            'name'          =>  __( 'Slider', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['slider_bg_color'] = array(
            'name'          =>  __( 'Slider Background Color', 'better-studio' ),
            'id'            =>  'slider_bg_color',
            'type'          =>  'color',
            'std'           =>  '#f2f2f2',
            'std-full-dark' =>  '#3c546b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#f5efd8',
            'std-green'     =>  '#f2f2f2',
            'std-blue2'     =>  '#f1f8fb',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.main-slider-wrapper' ,
                    'prop'      => 'background-color'
                )
            )
        );
        $field['slider_bg_image'] = array(
            'name'          =>  __( 'Slider Background Image', 'better-studio' ),
            'id'            =>  'slider_bg_image',
            'type'          =>  'background_image',
            'std'           =>  '',
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the background color option.', 'better-studio' ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.main-slider-wrapper'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            )
        );


        /**
         * 5.6.8. => News Ticker
         */
        $field[] = array(
            'name'          =>  __( 'News Ticker', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['newsticker_bg_color'] = array(
            'name'          =>  __( 'News Ticker Background Color', 'better-studio' ),
            'id'            =>  'newsticker_bg_color',
            'type'          =>  'color',
            'std'           =>  '#e0e0e0',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4d4d4d',
            'std-full-black'=>  '#4d4d4d',
            'std-beige'     =>  '#f7eecc',
            'std-blue2'     =>  '#ecf7fb',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.bf-news-ticker' ,
                    'prop'      => 'background-color'
                )
            )
        );
        $field['newsticker_link_color'] = array(
            'name'          =>  __( 'News Ticker Links Color', 'better-studio' ),
            'id'            =>  'newsticker_link_color',
            'type'          =>  'color',
            'std'           =>  '#696969',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#705e1f',
            'std-blue2'     =>  '#31a8d5',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.bf-news-ticker ul.news-list li a' ,
                    'prop'      => 'color'
                )
            )
        );


        /**
         * 5.6.9. => Page Title
         */
        $field[] = array(
            'name'      =>  __( 'Page Title', 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );

        $field['color_page_title_border_color'] = array(
            'name'          =>  __( 'Page Title Border Color', 'better-studio' ),
            'id'            =>  'color_page_title_border_color',
            'type'          =>  'color',
            'std'           =>  '#c9c9c9',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#878787',
            'std-full-black'=>  '#878787',
            'std-beige'     =>  '#d9c680',
            'std-blue2'     =>  '#A7E5FC',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.page-heading' ,
                    'prop'      => 'border-bottom-color'
                ),
                array(
                    'filter'    => array( 'bbpress' ),
                    'selector'  => array(
                        '#bbpress-forums li.bbp-header'
                    ),
                    'prop'      => 'border-bottom-color'
                ),
                array(
                    'filter'    => array( 'woocommerce' ),
                    'selector'  => array(
                        'body.woocommerce-account .woocommerce .address .title h3',
                        'body.woocommerce-account .woocommerce h2',
                        'body .cross-sells h2',
                        'body .related.products h2',
                        'body.woocommerce #reviews h3',
                        'body.woocommerce-page #reviews h3',
                        'body .woocommerce-tabs .panel.entry-content h2',
                        'body.woocommerce .shipping_calculator h2',
                        'body.woocommerce .cart_totals h2',
                        'body h3#order_review_heading',
                        'body .woocommerce-shipping-fields h3',
                        'body .woocommerce-billing-fields h3',
                    ),
                    'prop'      => 'border-bottom-color'
                ),

            )
        );


        /**
         * 5.6.10. => Section/Listing Title
         */
        $field[] = array(
            'name'          =>  __( 'Section/Listing Title', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );

        $field['color_section_title_font_color'] = array(
            'name'          =>  __( 'Section/Listing Title Font Color', 'better-studio' ),
            'id'            =>  'color_section_title_font_color',
            'type'          =>  'color',
            'std'           =>  '#626262',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'std-blue1'     =>  '#ffffff',
            'std-blue2'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.section-heading.extended .other-links .other-item a',
                        '.section-heading span.h-title a',
                        '.section-heading span.h-title',
                    ),
                    'prop'      => 'color'
                )
            ),

        );
        $field['color_section_title_bg_color'] = array(
            'name'          =>  __( 'Section/Listing Text Title Background Color', 'better-studio' ),
            'id'            =>  'color_section_title_bg_color',
            'type'          =>  'color',
            'std'           =>  '#e0e0e0',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#4a4a4a',
            'std-full-black'=>  '#4a4a4a',
            'std-beige'     =>  '#f5edd0',
            'std-green'     =>  '#639e1b',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.section-heading.extended .other-links .other-item a',
                        '.section-heading span.h-title'
                    ),
                    'prop'      => 'background-color'
                )
            ),
        );
        $field['color_section_title_border_color'] = array(
            'name'          =>  __( 'Section/Listing Title Border Color', 'better-studio' ),
            'id'            =>  'color_section_title_border_color',
            'type'          =>  'color',
            'std'           =>  '#c9c9c9',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#446280',
            'std-black'     =>  '#3b3b3b',
            'std-full-black'=>  '#3b3b3b',
            'std-beige'     =>  '#d9c680',
            'std-green'     =>  '#568f11',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.section-heading' ,
                    'prop'      => 'border-bottom-color'
                )
            ),
        );

        /**
         * 5.6.11. => Sidebar Widget Title
         */
        $field[] = array(
            'name'      =>  __( 'Sidebar Widget Title', 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $field['color_widget_title_bg_color'] = array(
            'name'          =>  __( 'Widget Title Background Color', 'better-studio' ),
            'id'            =>  'color_widget_title_bg_color',
            'type'          =>  'color',
            'std'           =>  '#f4f4f4',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#3c546b',
            'std-black'     =>  '#4a4a4a',
            'std-full-black'=>  '#4a4a4a',
            'std-beige'     =>  '#f5edd0',
            'std-green'     =>  '#77bb24',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#d2f2ff',
            'style'         => array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.primary-sidebar-widget .section-heading' ,
                    'prop'      => 'background-color'
                )
            ),
            'css-dark'      =>  array(
                array(
                    'selector'  => array(
                        '.primary-sidebar-widget .section-heading',
                        '.footer-larger-widget .section-heading'
                    ),
                    'prop'      => 'background-color'
                )
            ),
            'css-full-dark'      =>  array(
                array(
                    'selector'  => array(
                        '.primary-sidebar-widget .section-heading',
                        '.footer-larger-widget .section-heading'
                    ),
                    'prop'      => 'background-color'
                )
            ),
            'css-black'      =>  array(
                array(
                    'selector'  => array(
                        '.primary-sidebar-widget .section-heading',
                        '.footer-larger-widget .section-heading'
                    ),
                    'prop'      => 'background-color'
                )
            ),
        );
        $field['color_widget_title_text_bg_color'] = array(
            'name'          =>  __( 'Widget Title Text Background Color', 'better-studio' ),
            'id'            =>  'color_widget_title_text_bg_color',
            'type'          =>  'color',
            'std'           =>  '#626262',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#3c546b',
            'std-black'     =>  '#4a4a4a',
            'std-full-black'=>  '#4a4a4a',
            'std-beige'     =>  '#e6d390',
            'std-green'     =>  '#77bb24',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'style'         => array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.widget .section-heading.extended .other-links .other-item a',
                        '.widget .section-heading span.h-title'
                    ),
                    'prop'      => 'background-color'
                )
            ),
        );
        $field['color_widget_title_text_border_color'] = array(
            'name'          =>  __( 'Widget Title Text Border Color', 'better-studio' ),
            'id'            =>  'color_widget_title_text_border_color',
            'type'          =>  'color',
            'std'           =>  '#626262',
            'std-dark'      =>  '#304254',
            'std-full-dark' =>  '#3c546b',
            'std-black'     =>  '#4a4a4a',
            'std-full-black'=>  '#4a4a4a',
            'std-beige'     =>  '#ceb559',
            'std-green'     =>  '#77bb24',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.primary-sidebar-widget .section-heading',
                        '.footer-larger-widget .section-heading',
                        '.widget .section-heading.extended.tab-heading',
                    ),
                    'prop'      => 'border-color'
                )
            )
        );

        /**
         * 5.6.12. => Footer
         */
        $field[] = array(
            'name'          =>  __( 'Footer', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['color_large_footer_bg_color'] = array(
            'name'          =>  __( 'Large Footer Background Color', 'better-studio' ),
            'id'            =>  'color_large_footer_bg_color',
            'type'          =>  'color',
            'std'           =>  '#e0e0e0',
            'std-dark'      =>  '#334a61',
            'std-full-dark' =>  '#334a61',
            'std-black'     =>  '#575757',
            'std-full-black'=>  '#575757',
            'std-beige'     =>  '#f5efd8',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.footer-larger-wrapper' ,
                    'prop'      => 'background-color'
                )
            ),
            'css-dark'      =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'selector'  => array(
                        '.footer-larger-widget .better-social-counter.style-clean .social-item',
                    ),
                    'prop'      => 'border-bottom-color'
                )
            ),
            'css-full-dark'      =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'selector'  => array(
                        '.footer-larger-widget .better-social-counter.style-clean .social-item',
                    ),
                    'prop'      => 'border-bottom-color'
                )
            ),
            'css-green'      =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'selector'  => array(
                        '.footer-larger-widget .better-social-counter.style-clean .social-item',
                    ),
                    'prop'      => 'border-bottom-color'
                )
            ),
            'css-black'      =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'selector'  => array(
                        '.footer-larger-widget .better-social-counter.style-clean .social-item',
                    ),
                    'prop'      => 'border-bottom-color'
                )
            ),
            'css-beige'      =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'background-color'
                ),
                array(
                    'selector'  => array(
                        '.footer-larger-widget .better-social-counter.style-clean .social-item',
                    ),
                    'prop'      => 'border-bottom-color'
                )
            ),
        );

        $field['color_large_footer_text_color'] = array(
            'name'          =>  __( 'Large Footer Text Color', 'better-studio' ),
            'id'            =>  'color_large_footer_text_color',
            'type'          =>  'color',
            'std'           =>  '#5f656b',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.footer-larger-wrapper',
                        '.footer-larger-wrapper .the-content',
                        '.footer-larger-wrapper .the-content p',
                        '.footer-larger-wrapper .the-content a',
                        '.footer-larger-widget .better-social-counter.style-clean .item-count',
                        '.footer-larger-widget .better-social-counter.style-clean .item-title',
                        '.footer-larger-wrapper .widget.widget_nav_menu li a',
                    ),
                    'prop'      => 'color'
                )
            ),

        );
        $field['color_lower_footer_bg_color'] = array(
            'name'          =>  __( 'Lower Footer Background Color', 'better-studio' ),
            'id'            =>  'color_lower_footer_bg_color',
            'type'          =>  'color',
            'std'           =>  '#cfcfcf',
            'std-dark'      =>  '#2c3f52',
            'std-full-dark' =>  '#2c3f52',
            'std-black'     =>  '#333333',
            'std-full-black'=>  '#333333',
            'std-beige'     =>  '#eedd9e',
            'std-green'     =>  '#333333',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => '.footer-lower-wrapper' ,
                    'prop'      => 'background-color'
                )
            )
        );
        $field['color_lower_footer_text_color'] = array(
            'name'          =>  __( 'Lower Footer Text Color', 'better-studio' ),
            'id'            =>  'color_lower_footer_text_color',
            'type'          =>  'color',
            'std'           =>  '#5f6569',
            'std-dark'      =>  '#ffffff',
            'std-full-dark' =>  '#ffffff',
            'std-black'     =>  '#ffffff',
            'std-full-black'=>  '#ffffff',
            'std-beige'     =>  '#493c0c',
            'std-green'     =>  '#ffffff',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        '.footer-lower-wrapper',
                        '.footer-lower-wrapper .the-content',
                        '.footer-lower-wrapper .the-content p',
                    ),
                    'prop'      => 'color'
                )
            )
        );


        /**
         * 5.6.13. => Back to top
         */
        $field[] = array(
            'name'          =>  __( 'Back To Top', 'better-studio' ),
            'type'          =>  'group',
            'state'     =>  'close',
        );
        $field['color_back_top_bg'] = array(
            'name'          =>  __( 'Back to Top Background Color', 'better-studio' ),
            'id'            =>  'color_back_top_bg',
            'type'          =>  'color',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'std'           =>  '#626262',
            'std-beige'     =>  '#626262',
            'std-black'     =>  '#3f3f3f',
            'std-dark'      =>  '#446280',
            'std-full-dark' =>  '#3f3f3f',
            'std-full-black'=>  '#446280',
            'std-green'     =>  '#3f8f17',
            'std-blue1'     =>  '#4c75a4',
            'std-blue2'     =>  '#61bee1',
            'css'       => array(
                array(
                    'selector'  => array(
                        '.back-top'
                    ),
                    'prop'      => 'background',
                )
            ),
        );
        $field['color_back_top_color'] = array(
            'name'          =>  __( 'Back to Top Arrow Color', 'better-studio' ),
            'id'            =>  'color_back_top_color',
            'type'          =>  'color',
            'style'         =>  array(
                'default',
                'dark',
                'full-dark',
                'black',
                'full-black',
                'beige',
                'green',
                'blue1',
                'blue2',
            ),
            'std'           =>  '#ffffff',
            'std-beige'     =>  '#705e1f',
            'css'       => array(
                array(
                    'selector'  => array(
                        '.back-top'
                    ),
                    'prop'      => 'color',
                )
            ),
        );


        /**
         * => Advanced Options
         */
        $field[] = array(
            'name'      =>  __( 'Advanced' , 'better-studio' ),
            'id'        =>  'advanced_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-gear'
        );
        $field[] = array(
            'name'      =>  __( 'No Duplicate Posts','better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $field[] = array(
            'name'          =>  __( 'Enable For Whole Site', 'better-studio' ),
            'id'            =>  'bm_remove_duplicate_posts_full',
            'type'          =>  'switch',
            'on-label'  =>  __( 'Yes', 'better-studio' ),
            'off-label' =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enabling this feature will remove duplicate posts in whole site.', 'better-studio'),
            'std'           =>  0,
        );
        $field[] = array(
            'name'          =>  __( 'Enable In Homepage', 'better-studio' ),
            'id'            =>  'bm_remove_duplicate_posts',
            'type'          =>  'switch',
            'on-label'  =>  __( 'Yes', 'better-studio' ),
            'off-label' =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enabling this feature will remove duplicate posts in home page.', 'better-studio'),
            'std'           =>  0,
        );
        $field[] = array(
            'name'          =>  __( 'Enable In Category Archive Page', 'better-studio' ),
            'id'            =>  'bm_remove_duplicate_posts_categories',
            'type'          =>  'switch',
            'on-label'  =>  __( 'Yes', 'better-studio' ),
            'off-label' =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enabling this feature will remove duplicate posts in category archive pages.', 'better-studio'),
            'std'           =>  0,
        );
        $field[] = array(
            'name'          =>  __( 'Enable In Tag Archive Page', 'better-studio' ),
            'id'            =>  'bm_remove_duplicate_posts_tags',
            'type'          =>  'switch',
            'on-label'  =>  __( 'Yes', 'better-studio' ),
            'off-label' =>  __( 'No', 'better-studio' ),
            'desc'          =>  __( 'Enabling this feature will remove duplicate posts in tag archive pages.', 'better-studio'),
            'std'           =>  0,
        );

        $field[] = array(
            'name'          =>  __( 'Customize Post and Page Options', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
        );
        $field[] = array(
            'name'      =>  __( 'Add Post Options To Other Post Types', 'better-studio' ),
            'id'        =>  'advanced_post_options_types',
            'desc'      =>  __( 'Enter custom post types IDs here to adding post meta box to them.', 'better-studio' ),
            'input-desc'=>  __( 'Separate post types with ","', 'better-studio' ),
            'type'      =>  'text',
            'std'       =>  '',
            'ltr'       =>  true
        );
        $field[] = array(
            'name'          =>  __( 'Customize Category and Tag Options', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
        );
        $field[] = array(
            'name'      =>  __( 'Add Category Options to Other Taxonomies', 'better-studio' ),
            'id'        =>  'advanced_catgeory_options_tax',
            'desc'      =>  __( 'Enter custom taxonomy IDs here to adding category meta box to them.', 'better-studio' ),
            'input-desc'=>  __( 'Separate taxonomies with ","', 'better-studio' ),
            'type'      =>  'text',
            'std'       =>  '',
            'ltr'       =>  true
        );
        $field[] = array(
            'name'      =>  __( 'Add Tag Options to Other Taxonomies', 'better-studio' ),
            'id'        =>  'advanced_tag_options_tax',
            'desc'      =>  __( 'Enter custom taxonomy IDs here to adding tag meta box to them.', 'better-studio' ),
            'input-desc'=>  __( 'Separate taxonomies with ","', 'better-studio' ),
            'type'      =>  'text',
            'std'       =>  '',
            'ltr'       =>  true
        );


        /**
         * 5.8. => WooCommerce Options
         */
        if( function_exists( 'is_woocommerce' ) ){

            $field[] = array(
                'name'      =>  __( 'WooCommerce' , 'better-studio' ),
                'id'        =>  'woocommerce_setings',
                'type'      =>  'tab',
                'icon'      =>  'bsai-woo'
            );

            $field[] = array(
                'name'          =>  __( 'Shop Sidebar Position', 'better-studio' ),
                'id'            =>  'shop_sidebar_layout',
                'std'           =>  'no-sidebar',
                'type'          =>  'image_select',
                'section_class' =>  'style-floated-left bordered',
                'desc'          =>  __( 'Select the sidebar layout to use by default. This can be overridden per-page or per-post basis when creating a page or post.', 'better-studio' ),
                'options'       => array(
                    'left'      =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                        'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                    ),
                    'right'     =>  array(
                        'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                        'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                    ),
                    'no-sidebar'    =>  array(
                        'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                        'label' =>  __( 'No Sidebar', 'better-studio' ),
                    ),
                )
            );

            $field[] = array(
                'name'      =>  __( 'Number Of Products In Shop Archive Page', 'better-studio' ),
                'id'        =>  'shop_posts_per_page',
                'desc'      =>  __( 'Number of products in shop archive pages and product categories page.', 'better-studio' ),
                'type'      =>  'text',
                'std'       =>  '12'
            );

            $field[] = array(
                'name'      =>  __( 'Show Shopping Cart in Main Navigation', 'better-studio' ),
                'id'        =>  'show_shopping_cart_in_menu',
                'std'       =>  '1' ,
                'type'      =>  'switch',
                'desc'      =>  __( 'When enabled, a cart icon is shown in the main navigation to the right side.', 'better-studio' ),
                'on-label'  =>  __( 'Show', 'better-studio' ),
                'off-label' =>  __( 'Hide', 'better-studio' ),
            );

        } // is_woocommerce


        /**
         * => Custom Javascript / CSS
         */
        $field[] = array(
            'name'      =>  __( 'Custom CSS' , 'better-studio' ),
            'id'        =>  'custom_css_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-css3',
            'margin-top'=>  '20',
        );

        $field[] = array(
            'name'      =>  __( 'Custom CSS Code', 'better-studio' ),
            'id'        =>  'custom_css_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'better-studio' ),
            'ltr'       =>  true
        );
        $field[] = array(
            'name'      =>  __( 'Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_general',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of overall site.<br> Separate classes with space.', 'better-studio' )
        );
            $field[] = array(
                'name'          =>  __( 'Responsive CSS', 'better-studio' ),
                'type'          =>  'group',
                'state'         =>  'close',
                'desc'          =>  'Paste your custom css in the appropriate box, to run only on a specific device',
                'ltr'       =>  true
            );

                $field[] = array(
                    'name'      =>  __( 'Desktop', 'better-studio' ),
                    'id'        =>  'custom_css_desktop_code',
                    'type'      =>  'textarea',
                    'std'       =>  '',
                    'desc'      =>  __( '1200px +', 'better-studio' ),
                    'ltr'       =>  true,
                );
                $field[] = array(
                    'name'      =>  __( 'iPad Landscape', 'better-studio' ),
                    'id'        =>  'custom_css_ipad_landscape_code',
                    'type'      =>  'textarea',
                    'std'       =>  '',
                    'desc'      =>  __( '1019px - 1199px', 'better-studio' ),
                    'ltr'       =>  true,
                );
                $field[] = array(
                    'name'      =>  __( 'iPad Portrait', 'better-studio' ),
                    'id'        =>  'custom_css_ipad_portrait_code',
                    'type'      =>  'textarea',
                    'std'       =>  '',
                    'desc'      =>  __( '768px - 1018px', 'better-studio' ),
                    'ltr'       =>  true,
                );
                $field[] = array(
                    'name'      =>  __( 'Phones', 'better-studio' ),
                    'id'        =>  'custom_css_phones_code',
                    'type'      =>  'textarea',
                    'std'       =>  '',
                    'desc'      =>  __( '768px - 1018px', 'better-studio' ),
                    'ltr'       =>  true,
                );
        $field[] = array(
            'name'          =>  __( 'Addvanced Custom Body Class', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
        );

        $field[] = array(
            'name'      =>  __( 'Categories Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_category',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of all categories.<br> Separate classes with space.', 'better-studio' ),
            'ltr'       =>  true,
        );
        $field[] = array(
            'name'      =>  __( 'Tags Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_tag',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of all tags.<br> Separate classes with space.', 'better-studio' ),
            'ltr'       =>  true,
        );
        $field[] = array(
            'name'      =>  __( 'Authors Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_author',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of all authors.<br> Separate classes with space.', 'better-studio' ),
            'ltr'       =>  true,
        );
        $field[] = array(
            'name'      =>  __( 'Posts Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_post',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of all posts.<br> Separate classes with space.', 'better-studio' ),
            'ltr'       =>  true,
        );
        $field[] = array(
            'name'      =>  __( 'Pages Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class_page',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added in body of all post.<br> Separate classes with space.', 'better-studio' ),
            'ltr'       =>  true,
        );



        /**
         * => Analytics & JS
         */
        $field[] = array(
            'name'      =>  __( 'Analytics & JS' , 'better-studio' ),
            'id'        =>  'custom_analytics_code',
            'type'      =>  'tab',
            'icon'      =>  'bsai-analytics1',
        );
            $field[] = array(
                'name'      =>  __( 'Google analytics code', 'better-studio' ),
                'id'        =>  'custom_footer_code',
                'std'       =>  '',
                'type'      =>  'textarea',
                'desc'      =>  __( 'Paste your Google Analytics (or other) tracking code here. This code will be placed before &lt;/body&gt; tag in html. Please put code inside script tags.', 'better-studio' ),
                'ltr'       =>  true,
            );
            $field[] = array(
                'name'      =>  __( 'Code before &lt;/head&gt;', 'better-studio' ),
                'id'        =>  'custom_header_code',
                'std'       =>  '',
                'type'      =>  'textarea',
                'desc'      =>  __( 'This code will be placed before &lt;/head&gt; tag in html. Useful if you have an external script that requires it.', 'better-studio' ),
                'ltr'       =>  true,
            );

        /**
         * => Import
         */
        $field[] = array(
            'name'      =>  __( 'Auto Updates' , 'better-studio' ),
            'id'        =>  'auto_update_tab',
            'type'      =>  'tab',
            'icon'      =>  'bsai-refresh',
            'margin-top'=>  '20',
        );
            $field[] = array(
                    'name'      =>  __( 'ThemeForest Username', 'better-studio' ),
                    'id'        =>  'themeforest_user_name',
                    'std'       =>  '',
                    'type'      =>  'text',
                    'ltr'       =>  true,
                );
                $field[] = array(
                    'name'      =>  __( 'ThemeForest API Key', 'better-studio' ),
                    'id'        =>  'themeforest_api_key',
                    'std'       =>  '',
                    'type'      =>  'text',
                    'ltr'       =>  true,
                );
            $field[] = array(
                'name'          =>  __( 'How to setup automatic update?', 'better-studio' ),
                'id'            =>  'twitter-help',
                'type'          =>  'info',
                'std'           =>  '<p>' . __( 'To use the automatic theme updater you must enter your Themeforest username and API key into theme options. They only need to be entered one time. Once they are entered and we issue a theme update, you will receive a notification message in your WordPress admin “Updates” or “Themes” section.', 'better-studio' ) . '</p>'
                    .'<br><p style="text-align:center;"> <img src="'  .  BETTER_MAG_ADMIN_ASSETS_URI  . 'images/auto-update-help.png"></p>',
                'state'         =>  'open',
                'info-type'     =>  'help',
                'section_class' =>  'widefat',
            );


        /**
         * 5.10. => Import & Export
         */
        $field[] = array(
            'name'      =>  __( 'Backup & Restore' , 'better-studio' ),
            'id'        =>  'backup_restore',
            'type'      =>  'tab',
            'icon'      =>  'bsai-export-import',
            'margin-top'=>  '30',
        );
            $field[] = array(
                'name'      =>  __( 'Backup / Export', 'better-studio' ),
                'id'        =>  'backup_export_options',
                'type'      =>  'export',
                'file_name' =>  'bettermag-options-backup',
                'panel_id'  =>  '__better_mag__theme_options',
                'desc'      =>  __( 'This allows you to create a backup of your options and settings. Please note, it will not backup anything else.', 'better-studio' )
            );
            $field[] = array(
                'name'      =>  __( 'Restore / Import', 'better-studio' ),
                'id'        =>  'import_restore_options',
                'type'      =>  'import',
                'panel_id'  =>  '__better_mag__theme_options',
                'desc'      =>  __( '<strong>It will override your current settings!</strong> Please make sure to select a valid backup file.', 'better-studio' )
            );


        // Language  name for smart admin texts
        $lang =  bf_get_current_lang();

        if( $lang != 'none' ){
            $lang = bf_get_language_name( $lang );
        }else{
            $lang = '';
        }

        $options['__better_mag__theme_options'] = array(
            'panel-name'    =>  _x( 'Theme Options', 'Panel title', 'better-studio' ),
            'panel-desc'    =>  '<p>' . __( 'Configure theme settings, change colors, typography, layout and more...', 'better-studio' ) . '</p>',
            'panel-desc-lang'       =>  '<p>' . __( 'Theme %s Language Options.', 'better-studio' ) . '</p>',
            'theme-panel'   =>  true,
            'fields'        =>  $field,

            'texts'         =>  array(

                'panel-desc-lang'       =>  '<p>' . __( '%s Language Options.', 'better-studio' ) . '</p>',
                'panel-desc-lang-all'   =>  '<p>' . __( 'All Languages Options.', 'better-studio' ) . '</p>',

                'reset-button'      => ! empty( $lang ) ? sprintf( __( 'Reset %s Options', 'better-studio' ), $lang ) : __( 'Reset Options', 'better-studio' ),
                'reset-button-all'  => __( 'Reset All Options', 'better-studio' ),

                'reset-confirm'     =>  ! empty( $lang ) ? sprintf( __( 'Are you sure to reset %s options?', 'better-studio' ), $lang ) : __( 'Are you sure to reset options?', 'better-studio' ),
                'reset-confirm-all' => __( 'Are you sure to reset all options?', 'better-studio' ),

                'save-button'       =>  ! empty( $lang ) ? sprintf( __( 'Save %s Options', 'better-studio' ), $lang ) : __( 'Save Options', 'better-studio' ),
                'save-button-all'   =>  __( 'Save All Options', 'better-studio' ),

                'save-confirm-all'  =>  __( 'Are you sure to save all options? this will override specified options per languages', 'better-studio' )

            ),

            'config' => array(
                'name' 				  => __( 'Theme Options', 'better-studio' ),
                'parent' 			  => 'better-studio',
                'slug' 			      => 'better-studio/better-mag',
                'page_title'		  => __( 'Theme Options', 'better-studio' ),
                'menu_title'		  => __( 'Theme Options', 'better-studio' ),
                'capability' 		  => 'manage_options',
                'menu_slug' 		  => __( 'Theme Options', 'better-studio' ),
                'icon_url'  		  => null,
                'position'  		  => '20',
                'exclude_from_export' => false,
            ),
        );

        return $options;
    } //setup_option_panel


    /**
     * Setups Shortcodes for BetterMag
     *
     * 6. => Setup Shortcodes
     *
     * @param $shortcodes
     */
    function setup_shortcodes( $shortcodes ){

        require_once BETTER_MAG_PATH . 'includes/class-bm-vc-shortcode-extender.php';

        /**
         * 6.1. => BetterFramework Shortcodes
         */

        /**
         * 6.2. => BetterMag Shortcodes
         */

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-block-title-shortcode.php';
        $shortcodes['bm-block-title'] = array(
            'shortcode_class'   =>  'BM_Block_Title_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-feedburner-shortcode.php';
        $shortcodes['feedburner'] = array(
            'shortcode_class'   =>  'BM_Feedburner_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-posts-listing-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-posts-listing-widget.php';
        $shortcodes['bm-posts-listing'] = array(
            'shortcode_class'   =>  'BM_Posts_Listing_Shortcode',
            'widget_class'      =>  'BM_Posts_Listing_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-recent-tab-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-recent-tab-widget.php';
        $shortcodes['bm-recent-tab'] = array(
            'shortcode_class'   =>  'BM_Recent_Tab_Shortcode',
            'widget_class'      =>  'BM_Recent_Tab_Widget',
        );

        // WooCommerce cart widget
        if( function_exists('is_woocommerce') ){
            require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-wc-cart-shortcode.php';
            require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-wc-cart-widget.php';
            $shortcodes['bm-wc-cart'] = array(
                'shortcode_class'   =>  'BM_WC_Cart_Shortcode',
                'widget_class'      =>  'BM_WC_Cart_Widget',
            );
        }

        // Base Class For BetterMag Listings
        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-listing.php';

        // Content Listing Shortcodes + VC Add-ons

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-tab-listing-shortcode.php';
        $shortcodes['bm-content-tab-listing'] = array(
            'shortcode_class'   =>  'BM_Content_Tab_Listing_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-1-shortcode.php';
        $shortcodes['bm-content-listing-1'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_1_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-2-shortcode.php';
        $shortcodes['bm-content-listing-2'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_2_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-3-shortcode.php';
        $shortcodes['bm-content-listing-3'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_3_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-4-shortcode.php';
        $shortcodes['bm-content-listing-4'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_4_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-5-shortcode.php';
        $shortcodes['bm-content-listing-5'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_5_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-6-shortcode.php';
        $shortcodes['bm-content-listing-6'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_6_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-7-shortcode.php';
        $shortcodes['bm-content-listing-7'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_7_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-8-shortcode.php';
        $shortcodes['bm-content-listing-8'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_8_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-9-shortcode.php';
        $shortcodes['bm-content-listing-9'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_9_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-10-shortcode.php';
        $shortcodes['bm-content-listing-10'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_10_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-11-shortcode.php';
        $shortcodes['bm-content-listing-11'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_11_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-12-shortcode.php';
        $shortcodes['bm-content-listing-12'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_12_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-13-shortcode.php';
        $shortcodes['bm-content-listing-13'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_13_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-14-shortcode.php';
        $shortcodes['bm-content-listing-14'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_14_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-15-shortcode.php';
        $shortcodes['bm-content-listing-15'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_15_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-16-shortcode.php';
        $shortcodes['bm-content-listing-16'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_16_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-17-shortcode.php';
        $shortcodes['bm-content-listing-17'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_17_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-18-shortcode.php';
        $shortcodes['bm-content-listing-18'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_18_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-19-shortcode.php';
        $shortcodes['bm-content-listing-19'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_19_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-20-shortcode.php';
        $shortcodes['bm-content-listing-20'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_20_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-21-shortcode.php';
        $shortcodes['bm-content-listing-21'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_21_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-22-shortcode.php';
        $shortcodes['bm-content-listing-22'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_22_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/content-listing/class-bm-content-listing-23-shortcode.php';
        $shortcodes['bm-content-listing-23'] = array(
            'shortcode_class'   =>  'BM_Content_Listing_23_Shortcode',
        );

        // Slider Listings Shortcodes + VC Add-ons
        require_once BETTER_MAG_PATH . 'includes/shortcodes/slider-listing/class-bm-slider-listing-1-10-shortcode.php';
        $shortcodes['bm-slider-listing-1'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_1_Shortcode' );
        $shortcodes['bm-slider-listing-2'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_2_Shortcode' );
        $shortcodes['bm-slider-listing-3'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_3_Shortcode' );
        $shortcodes['bm-slider-listing-4'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_4_Shortcode' );
        $shortcodes['bm-slider-listing-5'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_5_Shortcode' );
        $shortcodes['bm-slider-listing-6'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_6_Shortcode' );
        $shortcodes['bm-slider-listing-7'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_7_Shortcode' );
        $shortcodes['bm-slider-listing-8'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_8_Shortcode' );
        $shortcodes['bm-slider-listing-9'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_9_Shortcode' );
        $shortcodes['bm-slider-listing-10'] = array( 'shortcode_class'   =>  'BM_Slider_Listing_10_Shortcode' );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/slider-listing/class-bm-slider-listing-11-shortcode.php';
        $shortcodes['bm-slider-listing-11'] = array(
            'shortcode_class'   =>  'BM_Slider_Listing_11_Shortcode',
        );
        require_once BETTER_MAG_PATH . 'includes/shortcodes/slider-listing/class-bm-slider-listing-12-shortcode.php';
        $shortcodes['bm-slider-listing-12'] = array(
            'shortcode_class'   =>  'BM_Slider_Listing_12_Shortcode',
        );

        // User Listing Shortcodes + VC Add-ons
        require_once BETTER_MAG_PATH . 'includes/shortcodes/user-listing/class-bm-user-listing-1-shortcode.php';
        $shortcodes['bm-user-listing-1'] = array(
            'shortcode_class'   =>  'BM_User_Listing_1_Shortcode',
        );
        require_once BETTER_MAG_PATH . 'includes/shortcodes/user-listing/class-bm-user-listing-2-shortcode.php';
        $shortcodes['bm-user-listing-2'] = array(
            'shortcode_class'   =>  'BM_User_Listing_2_Shortcode',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-better-subscribe-newsletter-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-better-subscribe-newsletter-widget.php';
        $shortcodes['better-subscribe-newsletter'] = array(
            'shortcode_class'   =>  'Better_Subscribe_Newsletter_Shortcode',
            'widget_class'      =>  'Better_Subscribe_Newsletter_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-dribbble-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-dribbble-widget.php';
        $shortcodes['bm-dribbble'] = array(
            'shortcode_class'   =>  'BM_Dribbble_Shortcode',
            'widget_class'      =>  'BM_Dribbble_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-video-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-video-widget.php';
        $shortcodes['bm-video'] = array(
            'shortcode_class'   =>  'BM_Video_Shortcode',
            'widget_class'      =>  'BM_Video_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-about-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-about-widget.php';
        $shortcodes['bf_about'] = array(
            'shortcode_class'   =>  'BF_About_Shortcode',
            'widget_class'      =>  'BF_About_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-flickr-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-flickr-widget.php';
        $shortcodes['bf_flickr'] = array(
            'shortcode_class'   =>  'BF_Flickr_Shortcode',
            'widget_class'      =>  'BF_Flickr_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-likebox-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-likebox-widget.php';
        $shortcodes['bf_likebox'] = array(
            'shortcode_class'   =>  'BF_Likebox_Shortcode',
            'widget_class'      =>  'BF_Likebox_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-social-share-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-social-share-widget.php';
        $shortcodes['bf_social_share'] = array(
            'shortcode_class'   =>  'BF_Social_Share_Shortcode',
            'widget_class'      =>  'BF_Social_Share_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-twitter-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-twitter-widget.php';
        $shortcodes['bf_twitter'] = array(
            'shortcode_class'   =>  'BF_Twitter_Shortcode',
            'widget_class'      =>  'BF_Twitter_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-advertisement-image-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-advertisement-image-widget.php';
        $shortcodes['bf_advertisement_image'] = array(
            'shortcode_class'   =>  'BF_Advertisement_Image_Shortcode',
            'widget_class'      =>  'BF_Advertisement_Image_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bf-advertisement-code-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bf-advertisement-code-widget.php';
        $shortcodes['bf_advertisement_code'] = array(
            'shortcode_class'   =>  'BF_Advertisement_Code_Shortcode',
            'widget_class'      =>  'BF_Advertisement_Code_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-google-plus-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-google-plus-widget.php';
        $shortcodes['bm-google-plus'] = array(
            'shortcode_class'   =>  'BM_Google_Plus_Shortcode',
            'widget_class'      =>  'BM_Google_Plus_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-posts-slider-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-posts-slider-widget.php';
        $shortcodes['bm-posts-slider'] = array(
            'shortcode_class'   =>  'BM_Posts_Slider_Shortcode',
            'widget_class'      =>  'BM_Posts_Slider_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-login-register-shortcode.php';
        require_once BETTER_MAG_PATH . 'includes/widgets/class-bm-login-register-widget.php';
        $shortcodes['bm-login-register'] = array(
            'shortcode_class'   =>  'BM_Login_Register_Shortcode',
            'widget_class'      =>  'BM_Login_Register_Widget',
        );

        require_once BETTER_MAG_PATH . 'includes/shortcodes/class-bm-gap-shortcode.php';
        $shortcodes['gap'] = array(
            'shortcode_class'   =>  'BM_Gap_Shortcode',
        );

        return $shortcodes;
    }


    /**
     * Filter callback: Custom menu fields
     *
     * 7. => Menu Options
     *
     */
    public function setup_custom_menu_fields( $fields ){

        $_fields = array(

            'mega_menu_heading' =>  array(
                'id'            =>  'mega_menu_heading',
                'type'          =>  'group',
                'name'          =>  __( 'Mega Menu', 'better-studio' ),
                'parent_only'   =>  false,
                'state'         =>  'close',
            ),

            'mega_menu' => array(
                'id'            =>  'mega_menu',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Mega Menu Type', 'better-studio' ),
                'type'          =>  'image_select',
                'class'         =>  '',
                'std'           =>  'disabled',
                'default_text'  =>  'Chose one',
                'list_style'    =>  'grid-2-column', // single-row, grid-2-column, grid-3-column
                'width'         =>  'wide',
                'parent_only'   => false,
                'options'       =>  array(
                    'disabled'  =>  array(
                        'label'     =>  __( 'Disabled', 'better-studio' ),
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-disabled.png'
                    ),
                    'link'      =>  array(
                        'label'     =>  __( 'Links - 2 Column', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-link-2-column.png'
                    ),
                    'link-3-column' =>  array(
                        'label'     =>  __( 'Links - 3 Column', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-link-3-column.png'
                    ),
                    'link-4-column' =>  array(
                        'label'     =>  __( 'Links - 4 Column', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-link-4-column.png'
                    ),
                    'category-recent-left'  =>  array(
                        'label'     =>  __('Category Recent (Menu Left)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-recent-left.png'
                    ),
                    'category-recent-right'  =>  array(
                        'label'     =>  __('Category Recent (Menu Right)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-recent-right.png'
                    ),
                    'category-left'  =>  array(
                        'label'     =>  __( 'Category Recent (Menu Left, Featured & Recent)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-left.png'
                    ),
                    'category-right'  =>  array(
                        'label'     =>  __('Category Recent (Menu Right, Featured & Recent)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-right.png'
                    ),
                    'category-simple-left'  =>  array(
                        'label'     =>  __('Category Recent (Menu Right, Featured & Simple Recent)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-simple-left.png'
                    ),
                    'category-simple-right'  =>  array(
                        'label'     =>  __('Category Recent (Menu Right, Featured & Simple Recent)', 'better-studio' ) ,
                        'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI. 'images/mega-menu-category-simple-right.png'
                    ),

                ),

            ),
            'mega_menu_cat' => array(
                'id'            =>  'mega_menu_cat',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Mega Menu Category', 'better-studio' ),
                'type'          =>  'select',
                'class'         =>  '',
                'std'           =>  'auto',
                'width'         =>  'wide',
                'parent_only'   =>  false,
                'options'       =>  array(
                    'auto'  => __( 'Auto Detect', 'better-studio' ),
                    array(
                        'label' => __( 'Categories', 'better-studio' ),
                        'options' => array(
                            'category_walker' => 'category_walker'
                        ),
                    ),
                    array(
                        'label' => __( 'Tags', 'better-studio' ),
                        'options' => BF()->helper_query()->get_tags()
                    ),
                ),
            ),

            // Icon Options
            'mega_icon_settings' =>array(
                'id'            =>  'mega_icon_settings',
                'name'          =>  __( 'Menu Icon', 'better-studio' ),
                'type'          =>  'group',
                'state'         =>  'close',
                'parent_only'   =>  false,
            ),
                'menu_icon'     =>array(
                    'id'            =>  'menu_icon',
                    'panel-id'      =>  '__better_mag__theme_options',
                    'name'          =>  __( 'Icon', 'better-studio' ),
                    'type'          =>  'icon_select',
                    'class'         =>  '',
                    'options'       =>  array( 'fontawesome' ),
                    'std'           =>  'none',
                    'default_text'  =>  'Chose an Icon',
                    'width'         =>  'thin',
                    'list_style'    =>  'grid-3-column',
                    'parent_only'   =>  false,
                ),
                'hide_menu_title' =>array(
                    'id'            =>  'hide_menu_title',
                    'panel-id'      =>  '__better_mag__theme_options',
                    'name'          =>  __( 'Show Only Icon?', 'better-studio' ),
                    'on-label'      =>  __( 'Yes', 'better-studio' ),
                    'off-label'     =>  __( 'No', 'better-studio' ),
                    'type'          =>  'switch',
                    'class'         =>  '',
                    'std'           =>  '0',
                    'width'         =>  'thin',
                    'parent_only'   =>  false,
                ),

            'mega_badge_settings' => array(
                'id'            =>  'mega_badge_settings',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Menu Badge', 'better-studio' ),
                'type'          =>  'group',
                'parent_only'   =>  false,
                'state'         =>  'close',
            ),
            'badge_label' => array(
                'id'            =>  'badge_label',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Badge Label', 'better-studio' ),
                'type'          =>  'text',
                'std'           =>  '',
                'class'         =>  '',
                'width'         =>  'thin',
                'parent_only'   =>  false
            ),
            'badge_position' => array(
                'id'            =>  'badge_position',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Badge Position', 'better-studio' ),
                'type'          =>  'select',
                'std'           =>  'right',
                'class'         =>  '',
                'width'         =>  'thin',
                'parent_only'   =>  false,
                'options'       =>  array(
                    'left'      =>  __( 'Left', 'better-studio' ),
                    'right'     =>  __( 'Right', 'better-studio' ),
                )
            ),
            'badge_bg_color' => array(
                'id'            =>  'badge_bg_color',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Badge Background Color', 'better-studio' ),
                'type'          =>  'color',
                'class'         =>  '',
                'std'           =>  Better_Mag::get_option( 'theme_color' ),
                'save-std'      =>  false,
                    'width'         =>  'thin',
                    'parent_only'   => false,
                    'css'           => array(
                        array(
                            'selector'  => array(
                                '%%id%% > a > .better-custom-badge',
                                '.widget.widget_nav_menu .menu %%class%% .better-custom-badge',
                            ),
                            'prop'      => array( 'background-color' )
                        ),
                         array(
                            'selector'  => array(
                                '%%id%% > a > .better-custom-badge:after',
                            ),
                            'prop'      => array( 'border-top-color' )
                        ),
                        array(
                            'selector'  => array(
                                '.main-menu .menu .sub-menu %%id%%.menu-badge-left > a >.better-custom-badge:after',
                            ),
                            'prop'      => array( 'border-left-color' )
                        ),
                        array(
                            'selector'  => array(
                                '.widget.widget_nav_menu .menu %%class%% .better-custom-badge:after',
                                '.main-menu .mega-menu %%id%%.menu-badge-right > a > .better-custom-badge:after',
                            ),
                            'prop'      => array( 'border-right-color' )
                        ),

                    )
                ),
            'badge_font_color' => array(
                'id'            =>  'badge_font_color',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Badge Font Color', 'better-studio' ),
                'type'          =>  'color',
                'class'         =>  '',
                'std'           =>  '#fff',
                'save-std'      =>  false,
                'width'         =>  'thin',
                'parent_only'   =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            '%%id%% > a > .better-custom-badge',
                        ),
                        'prop'      => array( 'color' )
                    ),
                ),

            ),

            // BG Options
            'menu_bg_settings' =>array(
                'id'            =>  'menu_bg_settings',
                'name'          =>  __( 'Sub Menu Background & Padding', 'better-studio' ),
                'desc'          =>  __( 'This options only will affects sub menu and mega menus.', 'better-studio' ),
                'type'          =>  'group',
                'state'         =>  'close',
                'parent_only'   =>  false,
            ),
            'menu_bg_image' => array(
                'id'            =>  'menu_bg_image',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Background Image', 'better-studio' ),
                'type'          =>  'background_image',
                'class'         =>  '',
                'std'           =>  '',
                'save-std'      =>  false,
                'width'         =>  'wide',
                'parent_only'   =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            '%%id%% > .mega-menu',
                            '%%id%% > .sub-menu',
                        ),
                        'prop'      => array( 'background-image' ),
                        'type'      => 'background-image'
                    ),
                ),

            ),
            'menu_bg_color' => array(
                'id'            =>  'menu_bg_color',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Background Color', 'better-studio' ),
                'type'          =>  'color',
                'class'         =>  '',
                'std'           =>  '',
                'save-std'      =>  false,
                'width'         =>  'wide',
                'parent_only'   =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            '%%id%% > .mega-menu',
                            '%%id%% > .sub-menu',
                        ),
                        'prop'      => array( 'background-color' )
                    ),
                ),

            ),
            'menu_min_height' => array(
                'id'            =>  'menu_min_height',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Min Height', 'better-studio' ),
                'type'          =>  'text',
                'class'         =>  '',
                'std'           =>  '',
                'suffix'        =>  'px',
                'save-std'      =>  false,
                'width'         =>  'thin',
                'parent_only'   =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            '.desktop-menu-container %%id%% > .mega-menu',
                            '.desktop-menu-container %%id%% > .sub-menu',
                        ),
                        'prop'      => array( 'min-height' => '%%value%%px' )
                    ),
                ),

            ),
            'menu_padding' => array(
                'id'            =>  'menu_padding',
                'panel-id'      =>  '__better_mag__theme_options',
                'name'          =>  __( 'Padding', 'better-studio' ),
                'type'          =>  'text',
                'class'         =>  '',
                'std'           =>  '',
                'save-std'      =>  false,
                'width'         =>  'thin',
                'parent_only'   =>  false,
                'css'           =>  array(
                    array(
                        'selector'  => array(
                            '.desktop-menu-container %%id%% > .mega-menu',
                            '.desktop-menu-container %%id%% > .sub-menu',
                        ),
                        'prop'      => array( 'padding' => '%%value%%' )
                    ),
                ),
            ),
        );

        return array_merge( $fields , $_fields );

    } // setup_custom_menu_fields


    /**
     * Filter callback: Breadcrumb Options
     *
     * 8. => Breadcrumb
     *
     */
    public function bf_breadcrumb_options( $options ){

        $options['labels']  =  array(
            'home'          => Better_Translation()->_get( 'bc_text_home' ),
            'browse'        => Better_Translation()->_get( 'bc_text_your_are_at' ),
            'error_404'     => Better_Translation()->_get( 'bc_text_404' ),
            'archives'      => Better_Translation()->_get( 'bc_text_archives' ),
            'search'        => Better_Translation()->_get( 'bc_text_search' ),
            'paged'         => Better_Translation()->_get( 'bc_text_paged' ),
        );

        if( ! is_rtl() )
            $options['separator'] = '<i class="fa fa-angle-double-right"></i>';
        else
            $options['separator'] = '<i class="fa fa-angle-double-left"></i>';

        // Add categories for posts
        if( Better_Mag::get_option( 'show_breadcrumb_post_category' ) )
            $options['post_taxonomy'] = array(
                'post'  => 'category',
            );

        return $options;

    } // bf_breadcrumb_options


    /**
     * Callback: Ads theme translation words to BetterTranslation
     *
     * Filter: better-translation/translations/fields
     *
     * @param $fields
     * @return array
     */
    function filter_translations_fields( $fields ){

        /**
         * General Words
         */

        $fields[] = array(
            'name'      =>  __( 'Search', 'better-studio' ),
            'id'        =>  'search',
            'std'       =>  'Search',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Search...', 'better-studio' ),
            'id'        =>  'search_dot',
            'std'       =>  'Search...',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Search for', 'better-studio' ),
            'id'        =>  'search_for',
            'std'       =>  'Search for:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Browse Author Articles', 'better-studio' ),
            'id'        =>  'oth_browse_auth_articles',
            'std'       =>  'Browse Author Articles',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( '%s Articles', 'better-studio' ),
            'id'        =>  'oth_author_articles',
            'std'       =>  '%s Articles',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with articles count.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Featured', 'better-studio' ),
            'id'        =>  'featured',
            'std'       =>  'Featured',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Recent', 'better-studio' ),
            'id'        =>  'recent',
            'std'       =>  'Recent',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Select a menu for "Main Navigation"', 'better-studio' ),
            'id'        =>  'select_main_nav',
            'std'       =>  'Select a menu for "Main Navigation"',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Random Post', 'better-studio' ),
            'id'        =>  'random_post',
            'std'       =>  'Random Post',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Profile', 'better-studio' ),
            'id'        =>  'profile',
            'std'       =>  'Profile',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Login', 'better-studio' ),
            'id'        =>  'login',
            'std'       =>  'Login',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Logout', 'better-studio' ),
            'id'        =>  'logout',
            'std'       =>  'Logout',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Register', 'better-studio' ),
            'id'        =>  'register',
            'std'       =>  'Register',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Close', 'better-studio' ),
            'id'        =>  'close',
            'std'       =>  'Close',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Register your account', 'better-studio' ),
            'id'        =>  'register_acc',
            'std'       =>  'Register your account',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Register Account Message in Popup', 'better-studio' ),
            'id'        =>  'register_acc_message',
            'std'       =>  'Sign Up with us and Enjoy!',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Username', 'better-studio' ),
            'id'        =>  'username',
            'std'       =>  'Username',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'E-Mail', 'better-studio' ),
            'id'        =>  'email',
            'std'       =>  'E-Mail',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Register Form Message', 'better-studio' ),
            'id'        =>  'register_form_message',
            'std'       =>  'A password will be e-mailed to you.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Next', 'better-studio' ),
            'id'        =>  'next',
            'std'       =>  'Next',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Previous', 'better-studio' ),
            'id'        =>  'previous',
            'std'       =>  'Previous',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Subscribe', 'better-studio' ),
            'id'        =>  'subscribe',
            'std'       =>  'Subscribe',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Navigation', 'better-studio' ),
            'id'        =>  'navigation',
            'std'       =>  'Navigation',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Post & Pages
         */
//        $fields[] = array(
//            'name'      =>  __( 'Posts & Pages' , 'better-studio' ),
//            'id'        =>  'posts_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-page-text',
//        );
        $fields[] = array(
            'name'      =>  __( 'Categories', 'better-studio' ),
            'id'        =>  'post_categories',
            'std'       =>  'Categories:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Tags', 'better-studio' ),
            'id'        =>  'post_tag',
            'std'       =>  'Tags:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Read More Button', 'better-studio' ),
            'id'        =>  'post_readmore',
            'std'       =>  'Read More',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Read More... Button', 'better-studio' ),
            'id'        =>  'post_readmore_dot',
            'std'       =>  'Read More...',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Related Posts', 'better-studio' ),
            'id'        =>  'post_related_posts',
            'std'       =>  'Related Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'No Related Post Found', 'better-studio' ),
            'id'        =>  'post_related_not_found',
            'std'       =>  'No Related Post Found!',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'No Related Post Found Message', 'better-studio' ),
            'id'        =>  'post_related_not_found_mes',
            'std'       =>  'Apologies, but no related post found for this post.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Previous Article', 'better-studio' ),
            'id'        =>  'post_prev_art',
            'std'       =>  'Previous Article',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Next Article', 'better-studio' ),
            'id'        =>  'post_next_art',
            'std'       =>  'Next Article',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Pages Pagination Title', 'better-studio' ),
            'id'        =>  'post_pages',
            'std'       =>  'Pages:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Latest Posts', 'better-studio' ),
            'id'        =>  'post_latest_posts',
            'std'       =>  'Latest Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Share Box Title', 'better-studio' ),
            'id'        =>  'content_show_share_title',
            'std'       =>  'Share',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Comments
         */
//        $fields[] = array(
//            'name'      =>  __( 'Comments Section' , 'better-studio' ),
//            'id'        =>  'comments_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-comment',
//        );
        $fields[] = array(
            'name'      =>  __( 'Enter password to view comment message', 'better-studio' ),
            'id'        =>  'enter_pass_to_see_comment',
            'std'       =>  'This post is password protected. Enter the password to view comments.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'No Comments Title', 'better-studio' ),
            'id'        =>  'no_comment_title',
            'std'       =>  'No Comments',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Comments Count Title', 'better-studio' ),
            'id'        =>  'comments_count_title',
            'std'       =>  '% Comments',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with comments count number.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( '1 Comment Title', 'better-studio' ),
            'id'        =>  'comments_1_comment',
            'std'       =>  '1 Comment',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Older Comments', 'better-studio' ),
            'id'        =>  'comments_older',
            'std'       =>  '&larr; Older Comments',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Newer Comments', 'better-studio' ),
            'id'        =>  'comments_newer',
            'std'       =>  'Newer Comments &rarr;',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Comments are closed', 'better-studio' ),
            'id'        =>  'comments_closed',
            'std'       =>  'Comments are closed',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Leave A Reply', 'better-studio' ),
            'id'        =>  'comments_leave_reply',
            'std'       =>  'Leave A Reply',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Reply', 'better-studio' ),
            'id'        =>  'comments_reply',
            'std'       =>  'Reply',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Reply To', 'better-studio' ),
            'id'        =>  'comments_reply_to',
            'std'       =>  'Reply To %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with user name.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Logged in as', 'better-studio' ),
            'id'        =>  'comments_logged_as',
            'std'       =>  'Logged in as',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Log out of this account', 'better-studio' ),
            'id'        =>  'comments_logout_this',
            'std'       =>  'Log out of this account',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Log out?', 'better-studio' ),
            'id'        =>  'comments_logout',
            'std'       =>  'Log out?',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Your Comment', 'better-studio' ),
            'id'        =>  'comments_your_comment',
            'std'       =>  'Your Comment',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Post Comment', 'better-studio' ),
            'id'        =>  'comments_post_comment',
            'std'       =>  'Post Comment',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Cancel Reply', 'better-studio' ),
            'id'        =>  'comments_cancel_reply',
            'std'       =>  'Cancel Reply',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Your Name', 'better-studio' ),
            'id'        =>  'comments_your_name',
            'std'       =>  'Your Name',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Your Email', 'better-studio' ),
            'id'        =>  'comments_your_email',
            'std'       =>  'Your Email',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Your Website', 'better-studio' ),
            'id'        =>  'comments_your_website',
            'std'       =>  'Your Website',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Pingback', 'better-studio' ),
            'id'        =>  'comments_pingback',
            'std'       =>  'Pingback:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Edit', 'better-studio' ),
            'id'        =>  'comments_edit',
            'std'       =>  'Edit',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Comment Awaiting Message', 'better-studio' ),
            'id'        =>  'comments_awaiting_message',
            'std'       =>  'Your comment is awaiting moderation.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'          =>  __( 'Note Before Comment Form', 'better-studio' ),
            'id'            =>  'comment_notes_before',
            'desc'          =>  __( 'Note to be displayed before the comment form fields.', 'better-studio' ),
            'input-desc'    =>  __( 'Will be shown only for not logged in users.', 'better-studio' ),
            'type'          =>  'textarea',
            'std'           =>  'Your email address will not be published.',
        );
        $fields[] = array(
            'name'          =>  __( 'Note After Comment Form', 'better-studio' ),
            'id'            =>  'comment_notes_after',
            'desc'          =>  __( 'Note to be displayed after the comment form fields.', 'better-studio' ),
            'type'          =>  'textarea',
            'std'           =>  '',
        );
        $fields[] = array(
            'name'      =>  __( 'Leave a comment on', 'better-studio' ),
            'id'        =>  'leave_comment_on',
            'std'       =>  'Leave a comment on: &ldquo;%s&rdquo;',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Archive
         */
//        $fields[] = array(
//            'name'      =>  __( 'Archive Pages' , 'better-studio' ),
//            'id'        =>  'archive_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-archive',
//        );
        $fields[] = array(
            'name'      =>  __( 'Category Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_cat_title',
            'std'       =>  'Browsing: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with category title.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Tag Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_tag_title',
            'std'       =>  'Browsing: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with tag title.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Custom Taxonomy Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_tax_title',
            'std'       =>  'Browsing: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with term title.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Search Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_search_title',
            'std'       =>  'Search Results: %s (%s)',
            'type'      =>  'textarea',
            'desc'      =>  __( 'First %s will be replaced with search keyword and second %s will be replaced with search result count.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Daily Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_daily_title',
            'std'       =>  'Daily Archives: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with day.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Month Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_monthly_title',
            'std'       =>  'Monthly Archives: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with month.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Month Archive Page Date Format', 'better-studio' ),
            'id'        =>  'archive_monthly_format',
            'std'       =>  'F Y',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Year Archive Page Title', 'better-studio' ),
            'id'        =>  'archive_yearly_title',
            'std'       =>  'Yearly Archives: %s',
            'type'      =>  'text',
            'desc'      =>  __( '%s will be replaced with year.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Year Archive Page Date Format', 'better-studio' ),
            'id'        =>  'archive_year_format',
            'std'       =>  'Y',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Nothing Found', 'better-studio' ),
            'id'        =>  'nothing_found',
            'std'       =>  'Nothing Found!',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Nothing Found Message', 'better-studio' ),
            'id'        =>  'nothing_found_message',
            'std'       =>  'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Search Page Nothing Found Message', 'better-studio' ),
            'id'        =>  'search_nothing_found_message',
            'std'       =>  'Sorry, but nothing matched your search criteria. Please try again with some different keywords.',
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );

        /**
         * 404 Page
         */
//        $fields[] = array(
//            'name'      =>  __( '404 Page' , 'better-studio' ),
//            'id'        =>  '404_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-404',
//        );
        $fields[] = array(
            'name'      =>  __( 'Page Not Found', 'better-studio' ),
            'id'        =>  '404_not_found',
            'std'       =>  'Page Not Found!',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( '404 Not Found Message', 'better-studio' ),
            'id'        =>  '404_not_found_message',
            'std'       =>  "We're sorry, but we can't find the page you were looking for. It's probably some thing we've done wrong but now we know about it and we'll try to fix it. In the meantime, try one of these options:",
            'type'      =>  'textarea',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Go to Previous Page', 'better-studio' ),
            'id'        =>  '404_go_previous_page',
            'std'       =>  'Go to Previous Page',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Go to Homepage', 'better-studio' ),
            'id'        =>  '404_go_homepage',
            'std'       =>  'Go to Homepage',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Widgets
         */
//        $fields[] = array(
//            'name'      =>  __( 'Widgets & Shortcodes' , 'better-studio' ),
//            'id'        =>  'widgets_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-pin',
//        );
        $fields[] = array(
            'name'      =>  __( 'Sample Widget Title', 'better-studio' ),
            'id'        =>  'widget_sample_title',
            'std'       =>  'Sample Widget Title',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Nothing yet.', 'better-studio' ),
            'id'        =>  'widget_nothing_yet',
            'std'       =>  'Nothing yet.',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Archives', 'better-studio' ),
            'id'        =>  'widget_archives',
            'std'       =>  'Archives',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Categories', 'better-studio' ),
            'id'        =>  'widget_categories',
            'std'       =>  'Categories',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Meta', 'better-studio' ),
            'id'        =>  'widget_meta',
            'std'       =>  'Meta',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Pages', 'better-studio' ),
            'id'        =>  'widget_pages',
            'std'       =>  'Pages',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Recent Posts', 'better-studio' ),
            'id'        =>  'widget_recent_posts',
            'std'       =>  'Recent Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Recent Comments', 'better-studio' ),
            'id'        =>  'widget_recent_comments',
            'std'       =>  'Recent Comments',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Tags', 'better-studio' ),
            'id'        =>  'widget_tags',
            'std'       =>  'Tags',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Block Title', 'better-studio' ),
            'id'        =>  'widget_block_title',
            'std'       =>  'Block Title',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Posts', 'better-studio' ),
            'id'        =>  'widget_posts',
            'std'       =>  'Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Recent', 'better-studio' ),
            'id'        =>  'widget_recent',
            'std'       =>  'Recent',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Popular', 'better-studio' ),
            'id'        =>  'widget_popular',
            'std'       =>  'Popular',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Review', 'better-studio' ),
            'id'        =>  'widget_review',
            'std'       =>  'Review',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Authors', 'better-studio' ),
            'id'        =>  'widget_authors',
            'std'       =>  'Authors',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Latest Posts', 'better-studio' ),
            'id'        =>  'widget_latest_posts',
            'std'       =>  'Latest Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Latest Galleries', 'better-studio' ),
            'id'        =>  'widget_latest_galleries',
            'std'       =>  'Latest Galleries',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Newsletter', 'better-studio' ),
            'id'        =>  'widget_newsletter',
            'std'       =>  'Newsletter',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Subscribe', 'better-studio' ),
            'id'        =>  'widget_subscribe',
            'std'       =>  'Subscribe',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Enter your e-mail ..', 'better-studio' ),
            'id'        =>  'widget_enter_email',
            'std'       =>  'Enter your e-mail ..',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Total', 'better-studio' ),
            'id'        =>  'widget_total',
            'std'       =>  'Total',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Dribbble Shots', 'better-studio' ),
            'id'        =>  'widget_dribbble_shots',
            'std'       =>  'Dribbble Shots',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'More shots...', 'better-studio' ),
            'id'        =>  'widget_dribbble_more',
            'std'       =>  'More shots...',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Video', 'better-studio' ),
            'id'        =>  'widget_video',
            'std'       =>  'Video',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'About', 'better-studio' ),
            'id'        =>  'widget_about',
            'std'       =>  'About',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Flickr Photos', 'better-studio' ),
            'id'        =>  'widget_flickr_photos',
            'std'       =>  'Flickr Photos',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Share', 'better-studio' ),
            'id'        =>  'widget_share',
            'std'       =>  'Share',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Email', 'better-studio' ),
            'id'        =>  'widget_email',
            'std'       =>  'Email',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Latest Tweets', 'better-studio' ),
            'id'        =>  'widget_latest_tweets',
            'std'       =>  'Latest Tweets',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'ago', 'better-studio' ),
            'id'        =>  'widget_ago',
            'std'       =>  'ago',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Reply', 'better-studio' ),
            'id'        =>  'widget_reply',
            'std'       =>  'Reply',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Retweet', 'better-studio' ),
            'id'        =>  'widget_retweet',
            'std'       =>  'Retweet',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Favorite', 'better-studio' ),
            'id'        =>  'widget_favorite',
            'std'       =>  'Favorite',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Google+', 'better-studio' ),
            'id'        =>  'widget_google_plus',
            'std'       =>  'Google+',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Your Profile', 'better-studio' ),
            'id'        =>  'widget_your_profile',
            'std'       =>  'Your Profile',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Archive Page
         */
//        $fields[] = array(
//            'name'      =>  __( 'Archive Page Template' , 'better-studio' ),
//            'id'        =>  'temp_arch_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-archive',
//        );
        $fields[] = array(
            'name'      =>  __( 'Latest posts', 'better-studio' ),
            'id'        =>  'temp_arch_latest',
            'std'       =>  'Latest posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Posts by month', 'better-studio' ),
            'id'        =>  'temp_arch_by_month',
            'std'       =>  'Posts by month',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Posts by year', 'better-studio' ),
            'id'        =>  'temp_arch_by_year',
            'std'       =>  'Posts by year',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Categories', 'better-studio' ),
            'id'        =>  'temp_arch_categories',
            'std'       =>  'Categories',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Writers', 'better-studio' ),
            'id'        =>  'temp_arch_writers',
            'std'       =>  'Writers',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );


        /**
         * Site Map
         */
//        $fields[] = array(
//            'name'      =>  __( 'Site Map Page Template' , 'better-studio' ),
//            'id'        =>  'temp_map_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-sitemap',
//        );
        $fields[] = array(
            'name'      =>  __( 'Pages', 'better-studio' ),
            'id'        =>  'temp_map_pages',
            'std'       =>  'Pages',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Categories', 'better-studio' ),
            'id'        =>  'temp_map_categories',
            'std'       =>  'Categories',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Tags', 'better-studio' ),
            'id'        =>  'temp_map_tags',
            'std'       =>  'Tags',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Authors', 'better-studio' ),
            'id'        =>  'temp_map_authors',
            'std'       =>  'Authors',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );

        /**
         * Breadcrumb
         */
//        $fields[] = array(
//            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
//            'id'        =>  'breadcrumb_settings',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-link'
//        );
        $fields[] = array(
            'name'      =>  __( 'Text for "You are at"', 'better-studio' ),
            'id'        =>  'bc_text_your_are_at',
            'type'      =>  'text',
            'std'       =>  'You are at',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Text for "Home" Page', 'better-studio' ),
            'id'        =>  'bc_text_home',
            'type'      =>  'text',
            'std'       =>  '<i class="fa fa-home"></i> Home',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Text for "404 Not Found" Page', 'better-studio' ),
            'id'        =>  'bc_text_404',
            'type'      =>  'text',
            'std'       =>  '404 Not Found',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Text for "Archives" Page', 'better-studio' ),
            'id'        =>  'bc_text_archives',
            'type'      =>  'text',
            'std'       =>  'Archives',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Text for "Search" Page', 'better-studio' ),
            'id'        =>  'bc_text_search',
            'type'      =>  'text',
            'std'       =>  'Search results for &#8220;%s&#8221;',
            'desc'      =>  __( '%s will be replaced with search keyword.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Text for "Paged" Pages', 'better-studio' ),
            'id'        =>  'bc_text_paged',
            'type'      =>  'text',
            'std'       =>  __( 'Page %s', 'better-studio' ),
            'desc'      =>  __( '%s will be replaced with page number.', 'better-studio' ),
            'container_class'      =>  'highlight-hover',
        );


        /**
         * WooCommerce
         */
//        $fields[] = array(
//            'name'      =>  __( 'WooCommerce' , 'better-studio' ),
//            'id'        =>  'woo_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-woo',
//        );
        $fields[] = array(
            'name'      =>  __( 'Default sorting', 'better-studio' ),
            'id'        =>  'woo_default_sort',
            'std'       =>  'Default sorting',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Sort by popularity', 'better-studio' ),
            'id'        =>  'woo_sort_pop',
            'std'       =>  'Sort by popularity',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Sort by average rating', 'better-studio' ),
            'id'        =>  'woo_sort_rat',
            'std'       =>  'Sort by average rating',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Sort by newness', 'better-studio' ),
            'id'        =>  'woo_sort_newness',
            'std'       =>  'Sort by newness',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Sort by price: low to high', 'better-studio' ),
            'id'        =>  'woo_sort_pri_l',
            'std'       =>  'Sort by price: low to high',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Sort by price: high to low', 'better-studio' ),
            'id'        =>  'woo_sort_pri_h',
            'std'       =>  'Sort by price: high to low',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Related Products', 'better-studio' ),
            'id'        =>  'woo_related',
            'std'       =>  'Related Products',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'You may also like', 'better-studio' ),
            'id'        =>  'woo_you_may',
            'std'       =>  'You may also like&hellip;',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Cart', 'better-studio' ),
            'id'        =>  'woo_cart',
            'std'       =>  'Cart',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Shopping Cart', 'better-studio' ),
            'id'        =>  'woo_shopping_cart',
            'std'       =>  'Shopping Cart',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );

        /**
         * bbPRess
         */
//        $fields[] = array(
//            'name'      =>  __( 'bbPress' , 'better-studio' ),
//            'id'        =>  'bbp_tab',
//            'type'      =>  'tab',
//            'icon'      =>  'bsai-bbpress',
//        );
        $fields[] = array(
            'name'      =>  __( 'Tagged', 'better-studio' ),
            'id'        =>  'bbp_tagged',
            'std'       =>  'Tagged:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Topics', 'better-studio' ),
            'id'        =>  'bbp_topics',
            'std'       =>  'Topics',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Replies', 'better-studio' ),
            'id'        =>  'bbp_replies',
            'std'       =>  'Replies',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Posts', 'better-studio' ),
            'id'        =>  'bbp_posts',
            'std'       =>  'Posts',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Freshness', 'better-studio' ),
            'id'        =>  'bbp_freshness',
            'std'       =>  'Freshness',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Posted In:', 'better-studio' ),
            'id'        =>  'bbp_posted_in',
            'std'       =>  'Posted In:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'by', 'better-studio' ),
            'id'        =>  'bbp_by',
            'std'       =>  'by',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'On', 'better-studio' ),
            'id'        =>  'bbp_on',
            'std'       =>  'On',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'in reply to', 'better-studio' ),
            'id'        =>  'bbp_in_reply_to',
            'std'       =>  'in reply to:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Voices', 'better-studio' ),
            'id'        =>  'bbp_voices',
            'std'       =>  'Voices',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Started by:', 'better-studio' ),
            'id'        =>  'bbp_started_by',
            'std'       =>  'Started by:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'in:', 'better-studio' ),
            'id'        =>  'bbp_in',
            'std'       =>  'in:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );
        $fields[] = array(
            'name'      =>  __( 'Last post:', 'better-studio' ),
            'id'        =>  'bbp_last_post',
            'std'       =>  'Last post:',
            'type'      =>  'text',
            'container_class'      =>  'highlight-hover',
        );

        return $fields;
    }


    function filter_translations_config( $config ){

        $config['dir-url']  = BETTER_MAG_URI . 'includes/libs/better-studio-translation/';
        $config['dir-path'] = BETTER_MAG_PATH . 'includes/libs/better-studio-translation/';
        $config['theme-id'] = 'better-mag';
        $config['theme-name'] = 'BetterMag';

        $config['translations'] = array(
            'en_US' => array(
                'name'  =>  __( 'English - US', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/en_US.json',
            ),
            'fr_FR' => array(
                'name'  =>  __( 'French - France', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/fr_FR.json',
            ),
            'es_MX' => array(
                'name'  =>  __( 'Spanish - Mexico', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/es_MX.json',
            ),
            'es_ES' => array(
                'name'  =>  __( 'Spanish - Spain', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/es_ES.json',
            ),
            'el_GR' => array(
                'name'  =>  __( 'Greek - Greece', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/el_GR.json',
            ),
            'fa_IR' => array(
                'name'  =>  __( 'Persian - Iran', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/fa_IR.json',
            ),
            'tr_TR' => array(
                'name'  =>  __( 'Turkish - Turkey', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/tr_TR.json',
            ),
            'he_IL' => array(
                'name'  =>  __( 'Hebrew - Israel', 'better-studio' ),
                'url'   =>  BETTER_MAG_URI . 'includes/admin-assets/translations/he_IL.json',
            ),
        );

        return $config;
    }


    /**
     * Setup users metaboxe's
     *
     * @param $options
     * @return array
     */
    function setup_bf_user_metabox( $options ){

        /**
         * 3. => Meta Box Options
         */

        $fields = array();
        /**
         * => Style
         */
        $fields[] = array(
            'name'      =>  __( 'Style' , 'better-studio' ),
            'id'        =>  'tab_style',
            'type'      =>  'tab',
            'icon'      =>  'bsai-paint',
        );
        $fields['avatar'] = array(
            'name'      => __( 'User Avatar', 'better-studio' ),
            'id'        => 'avatar',
            'type'      => 'media_image',
            'std'       => '',
            'upload_label'=> __( 'Upload Avatar', 'better-studio' ),
            'remove_label'=> __( 'Upload Avatar', 'better-studio' ),
            'desc'      => __( 'Upload your avatar. Use this to override Gravatar and default WordPress avatar.','better-studio'),
        );
        $fields['listing_style'] = array(
            'name'          =>  __( 'Page Listing', 'better-studio' ),
            'id'            =>  'listing_style',
            'std'           =>   'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'This style used when browsing user archive page. Default option image shows what default style selected in theme options panel.', 'better-studio' ),
            'options'       =>  array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-' . Better_Mag::get_option( 'categories_listing_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'blog' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-blog.png',
                    'label'     =>  __( 'Blog Listing', 'better-studio' ),
                ),
                'modern' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-modern.png',
                    'label'     => __( 'Modern Listing', 'better-studio' ),
                ),
                'highlight' => array(
                    'img'       => BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-highlight.png',
                    'label'     => __( 'Highlight Listing', 'better-studio' ),
                ),
                'classic' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/listing-style-classic.png',
                    'label'     =>  __( 'Classic Listing', 'better-studio' ),
                ),
            )
        );
        $fields['layout_style'] = array(
            'name'          =>  __( 'Page Layout', 'better-studio' ),
            'id'            =>  'layout_style',
            'std'           =>  'default',
            'save_default'  =>  false,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select whether you want a boxed or a full width layout. Default option image shows what default style selected in theme options panel.', 'better-studio' ),
            'options'       =>  array(
                'default'   =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-' . Better_Mag::get_option( 'layout_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'full-width' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-full-width.png',
                    'label'     =>  __( 'Full Width', 'better-studio' ),
                ),
                'boxed' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed.png',
                    'label'     =>  __( 'Boxed', 'better-studio' ),
                ),
                'boxed-padded' => array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/style-boxed-padded.png',
                    'label'     =>  __( 'Boxed (Padded)', 'better-studio' ),
                ),
            )
        );
        $fields['author_posts_count'] = array(
            'name'          =>  __( 'Number of Post to Show', 'better-studio' ),
            'id'            =>  'author_posts_count',
            'desc'          =>  sprintf( __( 'Leave this empty for default. <br>Default: %s', 'better-studio' ), Better_Mag::get_option( 'archive_author_posts_count' ) != '' ? Better_Mag::get_option( 'archive_author_posts_count' ) : get_option( 'posts_per_page' ) ),
            'type'          =>  'text',
            'std'           =>  '',
        );
        $fields['author_post_types'] = array(
            'name'          =>  __( 'Custom Post Types', 'better-studio' ),
            'id'            =>  'author_post_types',
            'desc'          =>  __( 'You can add custom post types to author posts page. for display default post type you should enter the "post".', 'better-studio' ),
            'input_desc'    =>  'Enter comma-separated post types',
            'type'          =>  'text',
            'std'           =>  '',
        );
        $fields['show_author_pagination'] = array(
            'name'          =>  __( 'Show Pagination', 'better-studio' ),
            'id'            =>  'show_author_pagination',
            'type'          =>  'switch',
            'std'           =>  '1',
            'on-label'      =>  __( 'Show', 'better-studio' ),
            'off-label'     =>  __( 'Hide', 'better-studio' ),
            'desc'          =>  __( 'Chose to show or hide pagination in user archive page', 'better-studio' ),
        );
        $fields[] = array(
            'name'      =>  __( 'Background Style' , 'better-studio' ),
            'type'      =>  'group',
            'state'     =>  'close',
        );
        $fields['bg_color'] = array(
            'name'      =>  __( 'Body Background Color', 'better-studio' ),
            'id'        =>  'bg_color',
            'type'      =>  'color',
            'std'       =>  Better_Mag::get_option( 'bg_color' ),
            'save-std'  =>  false,
            'desc'      =>  __( 'Setting a body background image below will override this.', 'better-studio' ),
            'css'       =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%%',
                    ),
                    'prop'      => array(
                        'background-color' =>   '%%value%%'
                    )
                ),
            )
        );
        $fields['bg_image'] = array(
            'name'      => __('Body Background Image','better-studio'),
            'id'        => 'bg_image',
            'type'      => 'background_image',
            'std'       => '',
            'upload_label'=> __( 'Upload Image', 'better-studio' ),
            'desc'      => __( 'Use light patterns in non-boxed layout. For patterns, use a repeating background. Use photo to fully cover the background with an image. Note that it will override the background color option.','better-studio'),
            'css'       => array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%%'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            )
        );


        /**
         * => Social Links
         */
        $fields[] = array(
            'name'      =>  __( 'Social Links' , 'better-studio' ),
            'id'        =>  'tab_social_links',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link',
        );
        $fields['twitter_url'] = array(
            'name'          =>  __( 'Twitter URL', 'better-studio' ),
            'id'            =>  'twitter_url',
            'type'          =>  'text',
            'std'           =>  '',
            'desc'          =>  __( 'Without @', 'better-studio' ),
        );
        $fields['facebook_url'] = array(
            'name'          =>  __( 'Facebook URL', 'better-studio' ),
            'id'            =>  'facebook_url',
            'type'          =>  'text',
            'std'           =>  '',
            'desc'          =>  __( 'Facebook page or account link', 'better-studio' ),
        );
        $fields['gplus_url'] = array(
            'name'          =>  __( 'Google+ URL', 'better-studio' ),
            'id'            =>  'gplus_url',
            'type'          =>  'text',
            'std'           =>  '',
            'desc'          =>  __( 'Google+ page link', 'better-studio' ),
        );
        $fields['linkedin_url'] = array(
            'name'          =>  __( 'Linkedin URL', 'better-studio' ),
            'id'            =>  'linkedin_url',
            'type'          =>  'text',
            'std'           =>  '',
        );
        $fields['github_url'] = array(
            'name'          =>  __( 'Github URL', 'better-studio' ),
            'id'            =>  'github_url',
            'type'          =>  'text',
            'std'           =>  '',
        );


        /**
         * => Header Options
         */
        $fields['header_options'] = array(
            'name'          =>  __( 'Header', 'better-studio' ),
            'id'            =>  'header_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-header',
        );
        $fields['header_show_topbar'] = array(
            'name'          =>  __( 'Display Top Bar', 'better-studio' ),
            'id'            =>  'header_show_topbar',
            'desc'          =>  __( 'Choose to show or top bar', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['header_show_header'] = array(
            'name'          =>  __( 'Display Header', 'better-studio' ),
            'id'            =>  'header_show_header',
            'desc'          =>  __( 'Choose to show or header', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'show',
            'options'       => array(
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'Main Navigation', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $menus['default'] = __( 'Default Main Navigation', 'better-studio' );
        $menus[] = array(
            'label' => __( 'Menus', 'better-studio' ),
            'options' => BF_Query::get_menus(),
        );
        $fields['main_nav_menu'] = array(
            'name'          =>  __( 'Main Navigation Menu', 'better-studio' ),
            'id'            =>  'main_nav_menu',
            'desc'          =>  __( 'Select which menu displays on this page.', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       =>  $menus
        );
        $fields['main_menu_style'] = array(
            'name'      =>  __( 'Main Navigation Style', 'better-studio' ),
            'id'        => 'main_menu_style',
            'desc'      =>  __( 'Select header menu style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-' . Better_Mag::get_option( 'main_menu_style' ) .'.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-normal-center.png',
                    'label' =>  __( 'Normal - Center Align', 'better-studio' ),
                ),
                'large' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large.png',
                    'label' =>  __( 'Large', 'better-studio' ),
                ),
                'large-center'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/menu-style-large-center.png',
                    'label' =>  __( 'Large - Center Align', 'better-studio' ),
                ),
            ),
        );
        $fields['main_menu_layout'] = array(
            'name'      =>  __( 'Main Navigation Layout', 'better-studio' ),
            'id'        => 'main_menu_layout',
            'desc'      =>  __( 'Select whether you want a boxed or a full width menu. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'boxed' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-boxed.png',
                    'label' =>  __( 'Boxed', 'better-studio' ),
                ),
                'full-width'    =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/header-menu-full-width.png',
                    'label' =>  __( 'Full Width', 'better-studio' ),
                ),
            ),
        );
        $fields[] = array(
            'name'      =>  __( 'Header Background', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_bg_color'] = array(
            'name'          =>  __( 'Header Background Color', 'better-studio' ),
            'id'            =>  'header_bg_color',
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'header_bg_color' ),
            'save-std'      =>  false,
            'desc'          =>  __( 'Setting a header background pattern below will override it.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .header'
                    ),
                    'prop'      => array(
                        'background-color' => '%%value%%'
                    )
                )
            )
        );
        $fields['header_bg_image'] = array(
            'name'          =>  __( 'Header Background Image', 'better-studio' ),
            'id'            =>  'header_bg_image',
            'type'          =>  'background_image',
            'std'           =>  array( 'img' => '', 'type' => 'cover' ),
            'save-std'      =>  false,
            'upload_label'  =>  __( 'Upload Image', 'better-studio' ),
            'desc'          =>  __( 'Please use a background pattern that can be repeated. Note that it will override the header background color option.','better-studio'),
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .header'
                    ),
                    'prop'      => array( 'background-image' ),
                    'type'      => 'background-image'
                )
            ),

        );
        $fields[] = array(
            'name'      =>  __( 'Header Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['header_top_padding'] = array(
            'name'          =>  __( 'Header Top Padding', 'better-studio' ),
            'id'            =>  'header_top_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .header'
                    ),
                    'prop'      => array( 'padding-top' => '%%value%%px' ),
                )
            ),
        );
        $fields['header_bottom_padding'] = array(
            'name'          =>  __( 'Header Bottom Padding', 'better-studio' ),
            'id'            =>  'header_bottom_padding',
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default value. Values lower than 60px will break the style.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .header'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );


        /**
         * => Sidebar
         */
        $fields[] = array(
            'name'      =>  __( 'Sidebar' , 'better-studio' ),
            'id'        =>  'tab_sidebar',
            'type'      =>  'tab',
            'icon'      =>  'bsai-sidebar',
        );
        $fields['sidebar_layout'] = array(
            'name'          =>  __( 'Sidebar Layout', 'better-studio' ),
            'id'            =>  'sidebar_layout',
            'std'           =>  'default',
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'desc'          =>  __( 'Select the sidebar layout to use by default. This can be overridden per-page, per-post and per category.', 'better-studio' ),
            'options'       => array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-' . Better_Mag::get_option( 'default_sidebar_layout' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'left'      =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png',
                    'label'     =>  is_rtl() ? __( 'Right Sidebar', 'better-studio' ) : __( 'Left Sidebar', 'better-studio' ),
                ),
                'right'     =>  array(
                    'img'       =>  is_rtl() ? BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-left.png' : BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-right.png',
                    'label'     =>  is_rtl() ? __( 'Left Sidebar', 'better-studio' ) : __( 'Right Sidebar', 'better-studio' ),
                ),
                'no-sidebar'=>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/sidebar-no-sidebar.png',
                    'label'     =>  __( 'No Sidebar', 'better-studio' ),
                ),
            )
        );


        /**
         * => Footer Options
         */
        $fields['footer_options'] = array(
            'name'          =>  __( 'Footer', 'better-studio' ),
            'id'            =>  'footer_options',
            'type'          =>  'tab',
            'icon'          =>  'bsai-footer',
        );
        $fields['footer_show_large'] = array(
            'name'          =>  __( 'Display Large Footer', 'better-studio' ),
            'id'            =>  'footer_show_large',
            'desc'          =>  __( 'Choose to show or hide large footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields['footer_show_lower'] = array(
            'name'          =>  __( 'Display Lower Footer', 'better-studio' ),
            'id'            =>  'footer_show_lower',
            'desc'          =>  __( 'Choose to show or hide lower footer', 'better-studio' ),
            'type'          =>  'select',
            'std'           =>  'default',
            'options'       => array(
                'default'   => __( 'Default', 'better-studio' ),
                'show'      => __( 'Show', 'better-studio' ),
                'hide'      => __( 'Hide', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'Large Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_large_top_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_large_bottom_padding'] = array(
            'name'          =>  __( 'Large Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_large_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .footer-larger-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );
        $fields[] = array(
            'name'      =>  __( 'Lower Footer Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['footer_lower_top_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['footer_lower_bottom_padding'] = array(
            'name'          =>  __( 'Lower Footer Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'footer_lower_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Leave empty for default.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .footer-lower-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );


        /**
         * => Slider
         */
        $fields[] = array(
            'name'      =>  __( 'Slider' , 'better-studio' ),
            'id'        =>  'tab_slider',
            'type'      =>  'tab',
            'icon'      =>  'bsai-slider',
        );
        $fields['show_slider'] = array(
            'name'      =>  __( 'Slider Type', 'better-studio' ),
            'desc'      =>  __( 'Select the type of slider that displays.', 'better-studio' ),
            'id'        =>  'show_slider',
            'std'       =>  'no' ,
            'type'      =>  'select',
            'options'   => array(
                'no'    => __( 'No Slider', 'better-studio' ),
                'better'=> __( 'BetterSlider', 'better-studio' ),
                'rev'   => __( 'Revolution Slider', 'better-studio' ),
            )
        );
        $fields[] = array(
            'name'      =>  __( 'BetterSlider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $fields['slider_just_featured'] = array(
            'name'          =>  __( 'Show Only Featured Posts in Slider', 'better-studio' ),
            'id'            =>  'slider_just_featured',
            'std'           =>  '1' ,
            'type'          =>  'switch',
            'desc'          =>  __( 'Turn Off for showing latest posts of category in slider or On for showing posts that specified as featured post in this category as slider.', 'better-studio' )
        );
        $fields['slider_style'] = array(
            'name'          =>  __( 'Slider Style', 'better-studio' ),
            'desc'          =>  __( 'Select slider style', 'better-studio' ),
            'id'            =>  'slider_style',
            'std'           =>  'default',
            'save_default'  =>  false,
            'type'          =>  'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'       =>  array(
                'default' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-' . Better_Mag::get_option( 'slider_style' ) . '.png',
                    'label'     =>  __( 'Default', 'better-studio' ),
                ),
                'style-1' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-1.png',
                    'label'     =>  __( 'Style 1', 'better-studio' ),
                ),
                'style-2' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-2.png',
                    'label'     =>  __( 'Style 2', 'better-studio' ),
                ),
                'style-3' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-3.png',
                    'label'     =>  __( 'Style 3', 'better-studio' ),
                ),
                'style-4' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-4.png',
                    'label'     =>  __( 'Style 4', 'better-studio' ),
                ),
                'style-5' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-5.png',
                    'label'     =>  __( 'Style 5', 'better-studio' ),
                ),
                'style-6' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-6.png',
                    'label'     =>  __( 'Style 6', 'better-studio' ),
                ),
                'style-7' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-7.png',
                    'label'     =>  __( 'Style 7', 'better-studio' ),
                ),
                'style-8' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-8.png',
                    'label'     =>  __( 'Style 8', 'better-studio' ),
                ),
                'style-9' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-9.png',
                    'label'     =>  __( 'Style 9', 'better-studio' ),
                ),
                'style-10' =>  array(
                    'img'       =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/slider-style-10.png',
                    'label'     =>  __( 'Style 10', 'better-studio' ),
                ),
            )
        );
        $fields['slider_bg_color'] = array(
            'name'          =>  __( 'Slider Background Color', 'better-studio' ),
            'id'            =>  'slider_bg_color',
            'desc'          =>  __( 'Customize slider background color.', 'better-studio' ),
            'type'          =>  'color',
            'std'           =>  Better_Mag::get_option( 'slider_bg_color' ),
            'save-std'      =>  false,
            'css'           =>  array(
                array(
                    'selector'  => 'body.author-%%user-id%% .main-slider-wrapper' ,
                    'prop'      => array('background-color')
                )
            ),
        );
        $fields['slider_cats'] = array(
            'name'          =>  __( 'Filter Slider by Categories', 'better-studio' ),
            'id'            =>  'slider_cats',
            'type'          =>  'ajax_select',
            'std'           =>  Better_Mag::get_option( 'slider_cats' ),
            'desc'          =>  __( 'Select categories for showing post of them in slider. you can use combination of multiple category and tag.', 'better-studio' ),
            'placeholder'   =>  __("Search and find category...", 'better-studio'),
            "callback"      => 'BF_Ajax_Select_Callbacks::cats_callback',
            "get_name"      => 'BF_Ajax_Select_Callbacks::cat_name',
        );
        $fields['slider_tags'] = array(
            'name'          =>  __( 'Filter Slider by Tags', 'better-studio' ),
            'id'            =>  'slider_tags',
            'type'          =>  'ajax_select',
            'std'           =>  Better_Mag::get_option( 'slider_tags' ),
            'desc'          =>  __( 'Select tags for showing post of them in slider. you can use combination of multiple category and tag.', 'better-studio' ),
            'placeholder'   =>  __("Search and find tag...", 'better-studio'),
            "callback"      => 'BF_Ajax_Select_Callbacks::tags_callback',
            "get_name"      => 'BF_Ajax_Select_Callbacks::tag_name',
        );
        $fields[] = array(
            'name'  =>  __( 'Slider Custom Post Type', 'better-studio' ),
            'desc'  =>  __( 'Enter your custom post types here. Separate with ,', 'better-studio' ),
            'id'    =>  'slider_post_type',
            'type'  =>  'text',
            'std'   =>  '',
        );
        $fields[] = array(
            'name'      =>  __( 'Revolution Slider Settings', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'open',
        );
        $fields['slider_rev_id'] = array(
            'name'      =>  __( 'Select Default Revolution Slider', 'better-studio' ),
            'desc'      =>  __( 'Select the unique name of the slider.', 'better-studio' ),
            'id'        =>  'slider_rev_id',
            'std'       =>  '0' ,
            'type'      =>  'select',
            'options'   => array(
                    '0'    => __( 'Select A Slider', 'better-studio' ),
                ) + BF_Query::get_rev_sliders()
        );
        $fields[] = array(
            'name'      =>  __( 'Slider Padding', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );
        $fields['slider_top_padding'] = array(
            'name'          =>  __( 'Slider Top Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_top_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .main-slider-wrapper'
                    ),
                    'prop'      => array(
                        'padding-top' => '%%value%%px'
                    ),
                )
            ),
        );
        $fields['slider_bottom_padding'] = array(
            'name'          =>  __( 'Slider Bottom Padding', 'better-studio' ),
            'suffix'        =>  __( 'Pixel', 'better-studio' ),
            'id'            =>  'slider_bottom_padding',
            'desc'          =>  __( 'In pixels without px, ex: 20. <br>Default padding is 20px.', 'better-studio' ),
            'type'          =>  'text',
            'std'           =>  '',
            'css-echo-default'  => false,
            'css'           =>  array(
                array(
                    'selector'  => array(
                        'body.author-%%user-id%% .main-slider-wrapper'
                    ),
                    'prop'      => array( 'padding-bottom' => '%%value%%px' ),
                )
            ),
        );

        /**
         * Breadcrumb
         */
        $fields[] = array(
            'name'      =>  __( 'Breadcrumb' , 'better-studio' ),
            'id'        =>  'breadcrumb_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-link'
        );
        $fields['breadcrumb_style'] = array(
            'name'      =>  __( 'Breadcrumb Style', 'better-studio' ),
            'id'        => 'breadcrumb_style',
            'desc'      =>  __( 'Select breadcrumb style. ', 'better-studio' ),
            'std'       => 'default',
            'type'      => 'image_select',
            'section_class' =>  'style-floated-left bordered',
            'options'   => array(
                'default' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-' . Better_Mag::get_option( 'breadcrumb_style' ) . '.png',
                    'label' =>  __( 'Default', 'better-studio' ),
                ),
                'normal' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal.png',
                    'label' =>  __( 'Normal', 'better-studio' ),
                ),
                'normal-center' =>  array(
                    'img'   =>  BETTER_MAG_ADMIN_ASSETS_URI . 'images/breadcrumb-style-normal-center.png',
                    'label' =>  __( 'Center Align', 'better-studio' ),
                ),
            ),
        );
        /**
         * => Custom Javascript / CSS
         */
        $fields['custom_css_settings'] = array(
            'name'      =>  __( 'Custom CSS' , 'better-studio' ),
            'id'        =>  'custom_css_settings',
            'type'      =>  'tab',
            'icon'      =>  'bsai-css3',
            'margin-top'=>  '20',
        );
        $fields['custom_css_code'] = array(
            'name'      =>  __( 'Custom CSS Code', 'better-studio' ),
            'id'        =>  'custom_css_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', 'better-studio' )
        );
        $fields['custom_css_class'] = array(
            'name'      =>  __( 'Custom Body Class', 'better-studio' ),
            'id'        =>  'custom_css_class',
            'type'      =>  'text',
            'std'       =>  '',
            'desc'      =>  __( 'This classes will be added to body.<br> Separate classes with space.', 'better-studio' )
        );
        $fields[] = array(
            'name'          =>  __( 'Responsive CSS', 'better-studio' ),
            'type'          =>  'group',
            'state'         =>  'close',
            'desc'          =>  'Paste your custom css in the appropriate box, to run only on a specific device',
        );
        $fields['custom_css_desktop_code'] = array(
            'name'      =>  __( 'Desktop', 'better-studio' ),
            'id'        =>  'custom_css_desktop_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1200px +', 'better-studio' )
        );
        $fields['custom_css_ipad_landscape_code'] = array(
            'name'      =>  __( 'iPad Landscape', 'better-studio' ),
            'id'        =>  'custom_css_ipad_landscape_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '1019px - 1199px', 'better-studio' )
        );
        $fields['custom_css_ipad_portrait_code'] = array(
            'name'      =>  __( 'iPad Portrait', 'better-studio' ),
            'id'        =>  'custom_css_ipad_portrait_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );
        $fields['custom_css_phones_code'] = array(
            'name'      =>  __( 'Phones', 'better-studio' ),
            'id'        =>  'custom_css_phones_code',
            'type'      =>  'textarea',
            'std'       =>  '',
            'desc'      =>  __( '768px - 1018px', 'better-studio' )
        );


        /**
         * 3.1. => General Post Options
         */
        $options['better_options'] = array(
            'config' => array(
                'title'         =>  __( 'Better Author Options', 'better-studio' ),
                'pages'         =>  array( 'post', 'page' ),
                'context'       =>  'normal',
                'prefix'        =>  false,
                'priority'      =>  'high'
            ),
            'panel-id'  => '__better_mag__theme_options',
            'fields' => $fields
        );

        return $options;

    } //setup_bf_metabox


	/**
     * Callback: Activate BetterStudio Duplicate Posts
     *
     * @param $active_pages
     *
     * @return array
     */
    function setup_no_duplicate_posts( $active_pages ){

        if( Better_Mag::get_option( 'bm_remove_duplicate_posts_full' ) ){
            $active_pages[] = 'full';
        }else{

            if( Better_Mag::get_option( 'bm_remove_duplicate_posts' ) ){
                $active_pages[] = 'home';
            }

            if( Better_Mag::get_option( 'bm_remove_duplicate_posts_categories' ) ){
                $active_pages[] = 'categories';
            }

            if( Better_Mag::get_option( 'bm_remove_duplicate_posts_tags' ) ){
                $active_pages[] = 'tags';
            }

        }

        return $active_pages;
    }
} 