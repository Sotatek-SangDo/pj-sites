<?php

/**
 * BetterFramework Likebox Shortcode
 */
class BF_Likebox_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_likebox';

        $this->widget_id = 'likebox';

        $this->name = __( 'Like Box', 'better-studio' );

        $this->description = __( 'Display a Facebook Like Box', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-likebox.png';

        $_options = array(
            'defaults'  => array(
                'title'             =>  '',
                'show_title'        =>  0,
                'url'               =>  '',
                'show_faces'        =>  1,
                'style'             =>  'light',
                'show_posts'        =>  0,
                'show_border'       =>  0,
                'show_header'       =>  0,
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

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title'] ){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        $height = 65;
        if( $atts['show_faces'] == true ){
            $height += 175;
        }
        if( $atts['show_posts'] == true ){
            $height += 350;
        }

        ?>
        <div class="bf-shortcode bf-shortcode-likebox style-<?php echo $atts['style']; ?>">
            <div class="the-content">
                <iframe src="https://www.facebook.com/plugins/likebox.php?href=<?php echo urlencode( $atts['url'] ) ?>&amp;width=270&amp;height=<?php echo $height; ?>&amp;show_faces=<?php echo $atts['show_faces']; ?>&amp;colorscheme=<?php echo $atts['style']; ?>&amp;stream=<?php echo $atts['show_posts']; ?>&amp;show_border=<?php echo $atts['show_border']; ?>&amp;header=<?php echo $atts['show_header']; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:<?php echo $height; ?>px;" allowTransparency="true"></iframe>
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
                    'heading'       =>  __( 'Instructions', 'better-studio' ),
                    'param_name'    =>  'help',
                    'type'          =>  'bf_info',
                    'value'         =>  __('<ol>
    <li>Copy the link to you facebook page</li>
    <li>Paste it in the "Link to you Facebook page" input box below</li>
  </ol>
                ', 'better-studio' ),
                    'state'         =>  'open',
                    'info-type'     =>  'help',
                    'section_class' =>  'widefat',
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Link to you Facebook page', 'better-studio' ),
                    "param_name"    =>  'url',
                    "value"         =>  $this->defaults['url'],
                ),
                array(
                    "type"          => 'bf_select',
                    "heading"       => __( 'Style', 'better-studio' ),
                    "param_name"    => 'style',
                    'section_class' => 'style-floated-left',
                    "admin_label"   => true,
                    "options"       => array(
                        'light'     => __( 'Light Schema', 'better-studio' ),
                        'dark'      => __( 'Style Schema', 'better-studio' ),
                    ),
                    "value" => $this->defaults['style'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Show Posts?', 'better-studio' ),
                    "param_name"    =>  'show_posts',
                    "value"         =>  $this->defaults['show_posts'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Show faces?', 'better-studio' ),
                    "param_name"    =>  'show_faces',
                    "value"         =>  $this->defaults['show_faces'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Show Header?', 'better-studio' ),
                    "param_name"    =>  'show_header',
                    "value"         =>  $this->defaults['show_header'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Show Border?', 'better-studio' ),
                    "param_name"    =>  'show_border',
                    "value"         =>  $this->defaults['show_border'],
                ),


            )
        ) );

    }
}


class WPBakeryShortCode_bf_likebox extends BF_VC_Shortcode_Extender { }