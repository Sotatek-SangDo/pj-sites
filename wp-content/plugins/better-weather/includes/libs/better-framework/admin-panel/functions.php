<?php


if( ! function_exists( 'bf_get_option' ) ){
	/**
	 * Get an option from the database (cached) or the default value provided
	 * by the options setup.
	 *
	 * @param   string $key       Option ID
	 * @param   string $panel_key Panel ID
	 * @param   string $lang      Language
	 *
	 * @return  mixed|null
	 */
	function bf_get_option( $key, $panel_key = '', $lang = NULL ) {

		if( empty( $panel_key ) ){
			$panel_key = Better_Framework::options()->get_theme_panel_id();
		}

		// Prepare Language
		if( is_null( $lang ) || $lang == 'en' || $lang == 'none' ){
			$lang = bf_get_current_lang();
		}

		if( $lang == 'en' || $lang == 'none' || $lang == 'all' ){
			$_lang = '';
		} else {
			$_lang = '_'.$lang;
		}

		if( isset( Better_Framework::options()->cache[ $panel_key.$_lang ][ $key ] ) ){
			return Better_Framework::options()->cache[ $panel_key.$_lang ][ $key ];
		}

		$std_id = Better_Framework::options()->get_std_field_id( $panel_key, $lang );

		foreach( Better_Framework::options()->options[ $panel_key ]['fields'] as $option ) {

			if( ! isset( $option['id'] ) || $option['id'] != $key ){
				continue;
			}

			if( isset( $option[ $std_id ] ) ){
				return $option[ $std_id ];
			} elseif( isset( $option['std'] ) ) {
				return $option['std'];
			} else {
				return NULL;
			}

		}

		return NULL;
	} // bf_get_option
} // if


if( ! function_exists( 'bf_echo_option' ) ){
	/**
	 * echo an option from the database (cached) or the default value provided
	 * by the options setup.
	 *
	 * Uses bf_get_option function.
	 *
	 * @param   string $key       Option ID
	 * @param   string $panel_key Panel ID
	 * @param   string $lang      Language
	 *
	 * @return  mixed|null
	 */
	function bf_echo_option( $key, $panel_key = '', $lang = NULL ) {

		echo bf_get_option( $key, $panel_key, $lang );

	} // bf_echo_option
} // if


if( ! function_exists( 'bf_set_option' ) ){
	/**
	 * Used to change option only in cache
	 *
	 * @param   string  $key        Option ID
	 * @param   complex $value      Value
	 * @param   string  $panel_key  Panel ID
	 * @param   string  $lang       Language
	 *
	 * @return mixed|null
	 */
	function bf_set_option( $key, $value, $panel_key = '', $lang = NULL ) {

		if( empty( $panel_key ) ){
			$panel_key = Better_Framework::options()->get_theme_panel_id();
		}

		// Prepare Language
		if( is_null( $lang ) || $lang == 'en' || $lang == 'none' ){
			$lang = bf_get_current_lang();
		}

		if( $lang == 'en' || $lang == 'none' || $lang == 'all' ){
			$_lang = '';
		} else {
			$_lang = '_'.$lang;
		}

		Better_Framework::options()->cache[ $panel_key.$_lang ][ $key ] = $value;

	} // bf_set_option
} // if


if( ! function_exists( 'bf_get_panel_default_style' ) ) {
	/**
	 * Handy function to get panels default style field id
	 *
	 * @param string $panel_id
	 *
	 * @return string
	 */
	function bf_get_panel_default_style( $panel_id = '' ) {
		return 'default';
	}
}
