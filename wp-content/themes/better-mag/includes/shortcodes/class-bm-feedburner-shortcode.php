<?php

class BM_Feedburner_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'feedburner';

        $_options = array(
            'defaults' => array(
                'heading'       =>  '',
                'label'         =>  '',
                'button_text'   =>  Better_Translation()->_get( 'subscribe' ),
                'user'          =>  '',
            ),

            'have_widget'       => false,
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

        return '
		<div class="feedburner">
			<p class="heading">'. esc_html( $atts['heading'] ) .'</p>
			<form method="post" action="http://feedburner.google.com/fb/a/mailverify" class="clearfix"><input type="hidden" value="'. esc_attr( $atts['user'] ) .'" name="uri" /><input type="hidden" name="loc" value="en_US" /><input type="text" id="feedburner-email" name="email" class="feedburner-email" placeholder="'. esc_attr( $atts['label'] ) .'" /><input class="feedburner-subscribe" type="submit" name="submit" value="'. esc_attr( $atts['button_text'] ) .'" /></form>
		</div>
		';

    }

}