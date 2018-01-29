<?php

class Better_Subscribe_Newsletter_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'better-subscribe-newsletter';

        $this->widget_id = 'better subscribe newsletter';

        $_options = array(
            'defaults' => array(

                'title'         =>  Better_Translation()->_get( 'widget_newsletter' ),
                'feedburner-id' =>  '',
                'msg' =>  '',

            ),

            'have_widget'       => true,
            'have_vc_add_on'    => false,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

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

        ?>
        <div class="better-studio-shortcode better-subscribe-newsletter">
            <?php

            echo wpautop( $atts['msg'] );

            ?>
            <form method="post" action="http://feedburner.google.com/fb/a/mailverify" class="better-subscribe-feedburner clearfix" target="_blank">
                <input type="hidden" value="<?php echo esc_attr( $atts['feedburner-id'] ); ?>" name="uri" />
                <input type="hidden" name="loc" value="<?php echo get_locale(); ?>" />
                <input type="text" id="feedburner-email" name="email" class="feedburner-email" placeholder="<?php Better_Translation()->_echo_esc_attr( 'widget_enter_email' ); ?>" />
                <input class="feedburner-subscribe" type="submit" name="submit" value="<?php Better_Translation()->_echo_esc_attr( 'widget_subscribe' ); ?>" />
            </form>
        </div>
        <?php

        return ob_get_clean();

    }

}