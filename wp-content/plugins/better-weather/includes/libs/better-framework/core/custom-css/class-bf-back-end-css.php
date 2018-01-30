<?php
/**
 * BF automatic custom css generator
 */
class BF_Back_End_CSS extends BF_Custom_CSS {

    /**
     * prepare functionality
     */
    function __construct(){

        if( is_admin() ){

            add_action( 'admin_footer', array( $this, 'display' ) );

        }

        add_action( 'profile_update', array( $this, 'clear_cache' ) );

    }


    /**
     * clear cache (transient)
     */
    public static function clear_cache( ){

        delete_transient( '__better_framework__backend_css' );

    }


    /**
     * Load all fields
     */
    function load_all_fields(){

        // Load from cache if available
        if( true == ( $cached_fields = get_transient( '__better_framework__backend_css' ) ) ){
            $this->fields = array_merge( $this->fields , $cached_fields );
            return;
        }else{
            // Filter custom css code
            $cached_fields = apply_filters( 'better-framework/css/backend' , $this->fields );
        }

        Better_Framework::factory('color');

        foreach( $cached_fields as $key => $field) {

            if( preg_match( '/(?=\d.*)-*\d*/',$cached_fields[$key]['value'], $chanage) ){
                $color = $chanage[0];
            }
            if( preg_match( '/(?<=-\d)[-|+]\d*/',$cached_fields[$key]['value'], $chanage) ){
                $color_change = $chanage[0];
            }


            if( ! isset( $color ) && ! isset( $color_change ) )
                continue ;

            if( isset( $color ) && ! isset( $color_change ) ){
                $cached_fields[$key]['value'] = BF_Color::get_user_profile_color( 'color-' . $color );
            }

            if( isset( $color ) && isset( $color_change ) ){
                $cached_fields[$key]['value'] = BF_Color::change_color( BF_Color::get_user_profile_color( 'color-' . $color), intval($color_change) );
            }

            unset( $color );
            unset( $color_change );
        }

        if( count($cached_fields) ){
            array_unshift( $cached_fields ,
                array(
                    'value' => 'c',
                    'type'  => 'comment',
                    array(
                        'comment' => " BetterFramework Custom CSS For Admin "
                    )
                ));
            $this->fields = array_merge( $this->fields , $cached_fields );

        }
        set_transient( '__better_framework__backend_css' , $cached_fields );

    }


    /**
     * display css
     */
    function display(){

        $this->load_all_fields();

        $final_css = $this->render_css();

        ?><style><?php echo $this->render_fonts();  echo $final_css; ?></style><?php

    }

}