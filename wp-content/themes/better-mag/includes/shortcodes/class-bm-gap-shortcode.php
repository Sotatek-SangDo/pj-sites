<?php

class BM_Gap_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'gap';

        $_options = array(
            'defaults' => array(
                'space'    =>  '',
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

        ob_start();

        echo '<div class="bf-shortcode bf-shortcode-gap" style="' . ( $atts['space'] != '' ? ('height:' . $atts['space'] . 'px;') : '' ) . '"></div>';

        return ob_get_clean();

    }

}