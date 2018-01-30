<?php

/**
 * BetterFramework Social Share Shortcode
 */
class BF_Advertisement_Code_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_advertisement_code';

        $this->widget_id = 'advertisement-code';

        $this->name = __( 'Advertisement Code', 'better-studio' );

        $this->description = __( 'BetterStudio Advertisements Code', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share.png';

        $_options = array(
            'defaults'      => array(
                'title'             => '',
                'show_title'        => 0,
                'code'              => '',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> false,
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
    function display( array $atts  , $content = ''   ){
        ob_start();

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        ?>
        <div class="bf-shortcode bf-advertisement-code">
            <?php
                echo $atts['code'];
            ?>
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
                    "heading"       =>  __( 'Title', 'better-studio' ),
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
                    "type"          =>  'textarea',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Code', 'better-studio' ),
                    "param_name"    =>  'code',
                    "value"         =>  $this->defaults['code'],
                    "description"   =>  __( 'You can use shortcodes.', 'better-studio' ),
                ),
            )

        ) );

    }
}

class WPBakeryShortCode_bf_advertisement_code extends BF_VC_Shortcode_Extender { }