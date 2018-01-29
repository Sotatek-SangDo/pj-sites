<?php

/**
 * BetterMag Video Shortcode
 */
class BM_Video_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm-video';

        $this->widget_id = 'bm video';

        $this->name = __( 'Video', 'better-studio' );

        $this->description = __( 'Widget for show videos.', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-video.png';

        $_options = array(
            'defaults'  => array(
                'title'             =>  Better_Translation()->_get( 'widget_video' ),
                'show_title'        =>  1,
                'url'               =>  '',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id , $_options );

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
        <div class="bf-shortcode bf-shortcode-video clearfix">
            <?php
            if( ! empty( $atts['url'] ) ){

                echo do_shortcode( apply_filters( 'better-framework/content/video-embed', $atts['url'] ) );

            }
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

            "category"      =>  __( 'Content', 'better-studio' ),
            "params"        => array(
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Section Title', 'better-studio' ),
                    "param_name"    =>  'title',
                    "value"         =>  $this->defaults['title'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Video URL', 'better-studio' ),
                    "param_name"    =>  'url',
                    "value"         =>  $this->defaults['url'],
                ),
            )
        ) );

    }
}


class WPBakeryShortCode_bm_video extends BF_VC_Shortcode_Extender { }







