<?php

/**
 * BetterFramework Advertisement Image Shortcode
 */
class BF_Advertisement_Image_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_advertisement_image';

        $this->widget_id = 'advertisement-image';

        $this->name = __( 'Advertisement Image', 'better-studio' );

        $this->description = __( 'BetterStudio Advertisements Image', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-ad-image.png';

        $_options = array(
            'defaults'      => array(
                'title'             => '',
                'show_title'        => 0,
                'image'             => '',
                'link'              => '',
                'caption'           => '',
                'target'            => '_blank',
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
    function display( array $atts  , $content = ''   ){
        ob_start();

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        ?>
        <div class="bf-shortcode bf-advertisement-code">
            <a href="<?php echo $atts['link']; ?>" target="<?php echo $atts['target']; ?>" title="<?php echo $atts['caption']; ?>"><img src="<?php echo $atts['image']; ?>" alt="<?php echo $atts['caption']; ?>" class="img-responsive">
            <?php
            if( ! empty( $atts['caption'] ) )
                echo '<span class="ad-caption">' . $atts['caption'] . '</span>';
            ?>
            </a>
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
                    "type"          =>  'bf_media_image',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Image', 'better-studio' ),
                    "param_name"    =>  'image',
                    "value"         =>  $this->defaults['image'],
                    'upload_label'  =>  __( 'Upload Advertisement Image', 'better-studio' ),
                    'remove_label'  =>  __( 'Remove Advertisement Image', 'better-studio' ),
                    'media_title'   =>  __( 'Upload Advertisement Image', 'better-studio' ),
                    'media_button'  =>  __( 'Select As Advertisement Image', 'better-studio' ),
                ),

                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Caption (Optional)', 'better-studio' ),
                    "param_name"    =>  'caption',
                    "value"         =>  $this->defaults['caption'],
                ),

                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Link', 'better-studio' ),
                    "param_name"    =>  'link',
                    "value"         =>  $this->defaults['link'],
                ),

                array(
                    "type"          =>  'bf_select',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Where To Open The link?', 'better-studio' ),
                    "param_name"    =>  'target',
                    "value"         =>  $this->defaults['target'],
                    "options"       =>  array(
                        '_blank'    => __( '_blank - in new window or tab' , 'better-studio' ),
                        '_self'     => __( '_self - in the same frame as it was clicked' , 'better-studio' ),
                        '_parent'   => __( '_parent - in the parent frame' , 'better-studio' ),
                        '_top'      => __( '_top - in the full body of the window' , 'better-studio' ),
                    ),
                ),


            )

        ) );

    }
}

class WPBakeryShortCode_bf_advertisement_image extends BF_VC_Shortcode_Extender { }