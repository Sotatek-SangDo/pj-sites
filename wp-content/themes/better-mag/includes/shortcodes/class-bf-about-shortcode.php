<?php

/**
 * BetterFramework About Shortcode
 */
class BF_About_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_about';

        $this->widget_id = 'about';

        $this->name = __( 'About', 'better-studio' );

        $this->description = __( 'BetterStudio "About" Site Add-on', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-about.png';

        $_options = array(
            'defaults'  => array(
                'title'         =>  Better_Translation()->_get( 'widget_about' ),
                'show_title'    =>  1,
                'text'          =>  '',
                'logo_text'     =>  '',
                'logo_img'      =>  '',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        );


        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id , $_options );

    }


    /**
     * Filter custom css codes for shortcode widget!
     *
     * @param $fields
     * @return array
     */
    function register_custom_css( $fields ){

        return $fields;

    }


    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        ?>
        <div class="bf-shortcode bs-about">
            <div class="the-content">
                <?php if( ! Better_Framework::widget_manager()->is_footer_sidebar() ){ ?>
                <h4>
                <?php
                if( $atts['logo_img'] ){ ?>
                   <img class="logo-image img-responsive" src="<?php echo $atts['logo_img'] ?>" alt="<?php echo $atts['logo_text']; ?>">
                <?php }else{
                    echo $atts['logo_text'];
                } ?>
                </h4><?php
                }

                echo wpautop( do_shortcode( $atts['text'] ) ); ?>
            </div>
        </div>
        <?php

        return ob_get_clean();

    }

    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map( array(
            "name"          =>  $this->name,
            "base"          =>  $this->id,
            "icon"          =>  $this->icon,
            "description"   =>  $this->description,
            "weight"        =>  1,

            "wrapper_height"=>  'full',

            "category"      =>  __( 'BetterMag Addons', 'better-studio' ),
            "params"        => array(

                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Section Title', 'better-studio' ),
                    "param_name"    =>  'title',
                    "value"         =>  $this->defaults['title'],
                ),

                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show Title?', 'better-studio'),
                    "param_name"    =>  'show_title',
                    "value"         =>  $this->defaults['show_title'],
                ),

                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Logo Text', 'better-studio' ),
                    "param_name"    =>  'logo_text',
                    "value"         =>  $this->defaults['logo_text'],
                ),

                array(
                    "type"          =>  'bf_media_image',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Logo Image', 'better-studio' ),
                    "param_name"    =>  'logo_img',
                    "value"         =>  $this->defaults['logo_text'],
                    'upload_label'  =>  __( 'Upload Logo', 'better-studio' ),
                    'remove_label'  =>  __( 'Remove Logo', 'better-studio' ),
                    'media_title'   =>  __( 'Upload Logo', 'better-studio' ),
                    'media_button'  =>  __( 'Select As Logo', 'better-studio' ),
                ),

                array(
                    "type"          =>  'textarea',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'About Text', 'better-studio' ),
                    "param_name"    =>  'text',
                    "value"         =>  $this->defaults['logo_text'],
                ),
            )
        ) );

    }
}


class WPBakeryShortCode_bf_about extends BF_VC_Shortcode_Extender { }