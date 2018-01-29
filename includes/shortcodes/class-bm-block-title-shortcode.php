<?php

class BM_Block_Title_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm-block-title';

        $_options = array(
            'defaults' => array(
                'title'     =>  Better_Translation()->_get( 'widget_block_title' ),
                'link'      =>  false,
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

        if( $content != '' )
            $atts['title'] = $content;

        Better_Mag::generator()->blocks()->get_block_title( $atts['title'], $atts['link'], true );

        return ob_get_clean();

    }

}