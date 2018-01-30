<?php

/**
 * BetterFramework Social Share Shortcode
 */
class BF_Social_Share_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_social_share';

        $this->widget_id = 'social-share';

        $this->name = __( 'Social Share', 'better-studio' );

        $this->description = __( 'BetterStudio Social Share Add-on', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share.png';

        $_options = array(
            'defaults'      => array(
                'title'             => Better_Translation()->_get( 'widget_share' ),
                'show_title'        => 1,
                'show-section-title'=> true,
                'style'             => Better_Framework::options()->get( 'share_box_style' ) ,
                'colored'           => Better_Framework::options()->get( 'share_box_colored' ) ? true : false,
                'sites'             => Better_Framework::options()->get( 'social_share_list' ),
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
     * Used for generating lis for social share list
     *
     * @param string $id
     * @param $show_title
     * @return string
     */
    function get_li( $id = '', $show_title = true ){

        if( empty($id) ) return '';

        switch( $id ){

            case 'facebook':
                $link  =    "javascript: window.open('http://www.facebook.com/sharer.php?u=" . esc_url( get_permalink( get_the_ID() ) ) ."','_blank', 'width=900, height=450');" ;
                $title =    __( 'Facebook', 'better-studio' );
                $icon  =    '<i class="fa fa-facebook"></i>';
                break;

            case 'twitter':
                $link  =    "javascript: window.open('http://twitter.com/home?status=" . esc_url( get_permalink( get_the_ID() ) ) ."','_blank', 'width=900, height=450');" ;
                $title =    __( 'Twitter', 'better-studio' );
                $icon  =    '<i class="fa fa-twitter"></i>';
                break;

            case 'google_plus':
                $link  =    "javascript: window.open('http://plus.google.com/share?url=" . esc_url( get_permalink( get_the_ID() ) ) ."','_blank', 'width=500, height=450');" ;
                $title =    __( 'Google+', 'better-studio' );
                $icon  =    '<i class="fa fa-google-plus"></i>';
                break;

            case 'pinterest':
                $_img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
                $link  =    "javascript: window.open('http://pinterest.com/pin/create/button/?url=" . esc_url( get_permalink( get_the_ID() ) ) . '&media=' . esc_url( $_img_src[0] ) . "&description=" . esc_attr( get_the_title() ) ."','_blank', 'width=900, height=450');" ;
                $title =    __( 'Pinterest', 'better-studio' );
                $icon  =    '<i class="fa fa-pinterest"></i>';
                break;

            case 'linkedin':
                $link  =    "javascript: window.open('http://www.linkedin.com/shareArticle?mini=true&url=" . esc_url( get_permalink( get_the_ID() ) ) . "&title=" . esc_attr( get_the_title() ) . "','_blank', 'width=500, height=450');" ;
                $title =    __( 'Linkedin', 'better-studio' );
                $icon  =    '<i class="fa fa-linkedin"></i>';
                break;

            case 'tumblr':
                $link  =    "javascript: window.open('http://www.tumblr.com/share/link?url=" . esc_url( get_permalink( get_the_ID() ) ) . "&name=" . esc_attr( get_the_title() ) . "','_blank', 'width=500, height=450');" ;
                $title =    __( 'Tumblr', 'better-studio' );
                $icon  =    '<i class="fa fa-tumblr"></i>';
                break;

            case 'email':
                $link  =    "mailto:?subject=" . get_the_title() . "&body=" . esc_url( get_permalink( get_the_ID() ) ) ;
                $title =    Better_Translation()->_get( 'widget_email' );
                $icon  =    '<i class="fa fa-envelope"></i>';
                break;


            default:
                return '';
        }

        $output = '<li class="social-item '.$id.'"><a href="' . $link . '" target="_blank">';
        $output .= $icon ;
        if( $show_title )
            $output .= '<span class="item-title">'. $title .'</span></a>';
        $output .= '</a></li>';

        return $output;
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

        $style = $atts['style'];

        $show_title = true;

        if( $style == 'button-no-text' ){
            $style = 'button';
            $show_title = false;
        }elseif( $style == 'outline-button-no-text' ){
            $style = 'outline-button';
            $show_title = false;
        }

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        if( ! isset($atts['class']) ){
            $atts['class'] = '';
        }

        ?>
        <div class="bf-shortcode bf-social-share style-<?php echo $style; ?> <?php echo $atts['colored'] == 1 ? 'colored' :'';   echo $show_title ? '' : ' no-title-style'; echo ' ' . $atts['class']; ?>">
            <ul class="social-list clearfix"><?php
                if( ! is_array( $atts['sites'] ) ){
                    $atts['sites'] = explode( ',', $atts['sites'] );
                    foreach( $atts['sites'] as $site ){
                        echo $this->get_li( $site, $show_title );
                    }
                }else{
                    foreach( $atts['sites'] as $site_key => $site ){
                        if( $site )
                            echo $this->get_li( $site_key, $show_title );
                    }
                }
            ?>
            </ul>
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
                    'heading'       =>  __( 'Style', 'better-studio'),
                    'type'          =>  'bf_image_radio',
                    "admin_label"   =>  true,
                    "param_name"    =>  'style',
                    "value"         =>  $this->defaults['style'],
                    'section_class' =>  'style-floated-left',
                    'options' => array(
                        'button' => array(
                            'label'     => __( 'Button Style' , 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-button.png'
                        ),
                        'button-no-text' => array(
                            'label'     => __( 'Icon Button Style' , 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-button-no-text.png'
                        ),
                        'outline-button' => array(
                            'label'     => __( 'Outline Style' , 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-outline-button.png'
                        ),
                        'outline-button-no-text' => array(
                            'label'     => __( 'Icon Outline Style' , 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/vc-social-share-outline-button-no-text.png'
                        ),
                    ),
                ),

                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show in colored  style?', 'better-studio'),
                    "param_name"    =>  'colored',
                    "value"         =>  $this->defaults['colored'],
                ),

                array(
                    "type"          =>  'bf_sorter_checkbox',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Active and Sort Sites', 'better-studio' ),
                    "param_name"    =>  'sites',
                    "value"         =>  '',
                    'section_class' =>  'bf-social-share-sorter',
                    'options' =>  array(
                        'facebook'      => array(
                            'label'     => '<i class="fa fa-facebook"></i> ' . __( 'Facebook', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'twitter'  => array(
                            'label'     => '<i class="fa fa-twitter"></i> ' . __( 'Twitter', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'google_plus'  => array(
                            'label'     => '<i class="fa fa-google-plus"></i> ' . __( 'Google+', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'pinterest'  => array(
                            'label'     => '<i class="fa fa-pinterest"></i> ' . __( 'Pinterest', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'linkedin'  => array(
                            'label'     => '<i class="fa fa-linkedin"></i> ' . __( 'Linkedin', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'tumblr'  => array(
                            'label'     => '<i class="fa fa-tumblr"></i> ' . __( 'Tumblr', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                        'email'  => array(
                            'label'     => '<i class="fa fa-envelope "></i> ' . __( 'Email', 'better-studio' ),
                            'css-class' => 'active-item'
                        ),
                    ),
                ),

            )

        ) );

    }
}

class WPBakeryShortCode_bf_social_share extends BF_VC_Shortcode_Extender { }