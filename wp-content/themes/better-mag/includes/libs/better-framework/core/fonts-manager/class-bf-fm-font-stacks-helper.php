<?php

/**
 * Used For retrieving Font Stacks.
 */
class BF_FM_Font_Stacks_Helper{


    /**
     * Contain array of all font stacks
     *
     * @var array
     */
    private static $fonts_list = null;


    /**
     * Used for Retrieving list of all Google Fonts
     */
    public static function get_all_fonts(){

        if( self::$fonts_list != null ){
            return self::$fonts_list;
        }

        // load option
        $font_stacks = Better_Framework::options()->get( 'font_stacks', Better_Framework::fonts_manager()->option_panel_id );

        // create array
        $_font_stacks = array();
        foreach( (array) $font_stacks as $stack ){

            $_font_stacks[$stack['id']]['stack'] = $stack['stack'];
            $_font_stacks[$stack['id']]['type'] = 'font-stack';

        }

        // save to cache
        return self::$fonts_list = $_font_stacks;

    }


    /**
     * Used for retrieving single font info
     *
     * @param $font_name
     *
     * @return bool
     */
    public static function get_font( $font_name ){

        $fonts =  self::get_all_fonts();

        if( isset( $fonts[ $font_name ] ) ){
            return $fonts[ $font_name ];
        }else{
            return false;
        }

    }


    /**
     * Generate and return Option elements of all font for select element
     *
     * @param   string  $active_font    Family name of selected font in options
     * @param   bool    $option_group   Active or selected font
     * @return  string
     */
    public static function get_fonts_family_option_elements( $active_font = '', $option_group = true ){

        $output = '';

        if( $option_group )
            $output .= '<optgroup label="' . __( 'Font Stacks', 'better-studio' ) . '">';

        foreach( self::get_all_fonts() as $key => $font ){
            $output .= '<option value="'. $key .'" ' . ( $key == $active_font ? 'selected' : '' ) . '>' .  $key . '</option>';
        }

        if( $option_group )
            $output .= '</optgroup>';

        return $output;

    }


    /**
     * Generate and return Option elements of font variants
     *
     * @param   string  $font_variant       Active or selected variant
     *
     * @return string
     */
    public static function get_font_variants_option_elements( $font_variant = '' ){

        if( empty( $font_variant ) ){

            $font_variant = '400';

        }

        $output = '';

        $variants = array(
            '100' => __( 'Ultra-Light 100', 'better-studio' ),
            '300' => __( 'Book 300', 'better-studio' ),
            '400' => __( 'Normal 400', 'better-studio' ),
            '500' => __( 'Medium 500', 'better-studio' ),
            '700' => __( 'Bold 700', 'better-studio' ),
            '900' => __( 'Ultra-Bold 900', 'better-studio' ),
            '100italic' => __( 'Ultra-Light 100 Italic', 'better-studio' ),
            '300italic' => __( 'Book 300 Italic', 'better-studio' ),
            '400italic' => __( 'Normal 400 Italic', 'better-studio' ),
            '500italic' => __( 'Medium 500 Italic', 'better-studio' ),
            '700italic' => __( 'Bold 700 Italic', 'better-studio' ),
            '900italic' => __( 'Ultra-Bold 900 Italic', 'better-studio' ),
        );

        foreach( $variants as $variant_id => $variant_name ){
            $output .= '<option value="'. $variant_id .'" ' . ( $variant_id == $font_variant ? 'selected' : '' ) . '>' .  $variant_name  . '</option>';
        }

        return $output;

    }


    /**
     * Generate and return Option elements of font subsets
     *
     * @return string
     */
    public static function get_font_subset_option_elements(){

        return '<option value="unknown">' .  __( 'Unknown', 'better-studio' ) . '</option>';

    }

}