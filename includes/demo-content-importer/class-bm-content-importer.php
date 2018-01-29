<?php

/**
 * BetterMag Import Demo Content
 *
 * @package  BetterMag
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.0.0
 * @access   public
 * @see      http://www.betterstudio.com
 */
class BM_Content_Importer extends BF_Admin_Page{

    /**
     * Contains list of images sizes
     *
     * @since   2.0.0
     * @var array|null
     */
    public $image_sizes = null;


    /**
     * Initialize Better Rebuild Thumbnails
     *
     * @since   2.0.0
     * @param   array   $args   Configuration
     */
    function __construct( $args = array() ){

        $args['id']     = 'better-mag-content-importer';
        $args['class']  = 'hide-notices';
        $args['slug']   = 'demo-content';

        parent::__construct( $args );

        // Ajax callback for importing demo content
        add_action( 'wp_ajax_bm_import_demo_content', array( $this, 'import_demo_content' ) );

    }


    /**
     * Callback: Used for registering menu to WordPress
     *
     * Action: better-framework/admin-menus/admin-menu/before
     *
     * @since   2.0.0
     * @access  public
     *
     * @return  void
     */
    function add_menu(){

        BF()->admin_menus()->add_menupage( array(
                'id' 				  =>    $this->page_id,
                'slug' 				  =>    'better-studio/' . $this->args['slug'],
                'name' 				  =>    __( 'Demo Content', 'better-studio' ),
                'parent' 			  =>    'better-studio',
                'page_title'		  =>    __( 'Demo Content', 'better-studio' ),
                'menu_title'		  =>    __( 'Demo Content', 'better-studio' ),
                'position'  		  =>    50.01,
                'callback'            =>    array( $this, 'display' ),
            )
        );

    }


    /**
     * Page title
     *
     * @since   2.0.0
     *
     * @return string|void
     */
    function get_title(){
        return __( 'Demo Content', 'better-studio' );
    }


    /**
     * Page desc in header
     *
     * @since   2.0.0
     *
     * @return string
     */
    function get_desc(){
        return '<p>' . __( 'Setup demo content with one click of mouse.', 'better-studio' ) . '</p>';
    }


    /**
     * Page Body
     *
     * @since   1.0.0
     *
     * @return string
     */
    function get_body(){

        ob_start();
        ?>

        <div class="pre-desc">
            <p><?php _e( 'Hit following button to import demo content.', 'better-studio' ); ?></p>
        </div>

        <div class="better-import-demo-content bf-button bf-main-button large-2x" id="better-import-demo-content">
            <span class="text-1"><i class="fa fa-download"></i> <?php _e( 'Import Demo Content', 'better-studio' ) ?></span>
        </div>

        <?php
        return ob_get_clean();
    }


    /**
     * Callback: Used for enqueue scripts in WP backend
     *
     * Action: admin_enqueue_scripts
     *
     * @since   2.0.0
     */
    function admin_enqueue_scripts(){

        parent::admin_enqueue_scripts();

        wp_enqueue_style( 'bm-demo-content-importer', get_template_directory_uri() . '/includes/demo-content-importer/assets/css/bm-demo-content.css', array(), BF()->theme()->get( 'Version' ) );

        wp_enqueue_script( 'bm-demo-content-importer', get_template_directory_uri() . '/includes/demo-content-importer/assets/js/bm-demo-content.js', array(), BF()->theme()->get( 'Version' ) );

        wp_localize_script(
            'bm-demo-content-importer',
            'bm_demo_content_importer_loc',
            apply_filters(
                'better-mag/demo-content/localized-items',
                array(
                    'ajax_url'          => admin_url( 'admin-ajax.php' ),
                    'text_confirm'           => __( "Are you sure do you want import demo content?", "better-studio" ),
                    'text_show_site'         => '<p>' .  __( 'Demo content imported successfully!', 'better-studio' ) . ' <a target="_blank" href="' . get_home_url() . '">' . __( 'View Site', 'better-studio' ) . '</a> </p>',
                    'text_loading'           => '<div class="text-1"><i class="fa fa-refresh"></i><span>' .  __( 'Importing...', 'better-studio' ) . '</span></div>',
                    'text_done'              => '<div class="text-1"><i class="fa fa-check"></i><span></span></div>',
                    'text_error'             => '<div class="text-1"><i class="fa fa-exclamation"></i><span></span></div>',
                )
            )
        );

    }


    /**
    * Ajax Callback: Used to import demo content
    *
    * Ajax Action: bm_import_demo_content
    */
    public function import_demo_content(){

        try{

            ob_start();

            require_once  BETTER_MAG_PATH . 'includes/libs/better-studio-wp-importer/class-better-studio-wp-importer.php';

            $importer = new Better_Studio_WP_Importer();

            // Import posts, pages, comments, custom fields, terms, navigation menus and custom posts.
            $importer->import_wp_xml( get_template_directory() . '/includes/admin-assets/demo-data/content.xml.gz' );

            // Set Menus
            $importer->import_menus( array(
                'main-navigation' => 'main-menu'
            ) );

            // Import Widgest
            delete_option( 'sidebars_widgets' ); // first delete last widgets
            $importer->import_widgets( BETTER_MAG_ADMIN_ASSETS_URI . 'demo-data/widgets.json' );

            // Import Options
            $importer->import_options( BETTER_MAG_ADMIN_ASSETS_URI . 'demo-data/options.json' );

            // set home page
            $homepage = get_page_by_title( 'Home Page' );

            if( isset( $homepage ) && $homepage->ID ){
                update_option( 'show_on_front', 'page');
                update_option( 'page_on_front', $homepage->ID );
            }

            ob_end_clean();

            Better_Framework::factory( 'custom-css-fe' )->clear_cache( 'all' );

        }catch( Exception $e ) {
            die( json_encode( array(
                'status'  => 'error',
                'message'	  => __( 'Demo content was not imported.', 'better-studio' ),
                'refresh' => false
            ) ) );
        }

        die( json_encode( array(
            'status'  => 'success',
            'message'	  => __( 'Demo content imported.', 'better-studio' ),
            'refresh' => true
        ) ) );

    }
};

