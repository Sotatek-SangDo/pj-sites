<?php

/**
 * BetterMag Google+ Shortcode
 */
class BM_Google_Plus_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm-google-plus';

        $this->widget_id = 'bm google plus';

        $_options = array(
            'defaults'  => array(
                'title'             =>  Better_Translation()->_get( 'widget_google_plus' ),
                'show_title'        =>  true,
                'type'              =>  'profile', // or page, community
                'url'               =>  '',
                'width'             =>  '356',
                'scheme'            =>  'light', // or dark
                'layout'            =>  'portrait', // or Landscape
                'cover'             =>  'show',
                'tagline'           =>  'show',
                'lang'              =>  'en-US',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> false,
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
        <div class="bf-shortcode bf-shortcode-google-plus clearfix">
            <?php

            if( ! empty( $atts['url'] ) ){

                switch( $atts['type'] ){

                    case 'page':
                        $type = 'class="g-page"';
                        break;

                    case 'community':
                        $type = 'class="g-community"';
                        break;

                    default:
                        $type = 'class="g-person"';
                        break;

                }

                ?>
                <div <?php echo $type; ?> data-width="<?php echo $atts['width']; ?>" data-href="<?php echo $atts['url'] ?>" data-layout="<?php echo $atts['layout']; ?>" data-theme="<?php echo $atts['scheme']; ?>" data-rel="publisher" data-showtagline="<?php echo $atts['tagline'] == 'show' ? 'true' : 'false'; ?>" data-showcoverphoto="<?php echo $atts['cover'] == 'show' ? 'true' : 'false'; ?>"></div>
                <script type="text/javascript">
                    var lang = '<?php echo $atts['lang']; ?>';
                    if (lang !== '') {
                        window.___gcfg = {lang: lang};
                    }
                    (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    })();
                </script>
                <?php

            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }

}