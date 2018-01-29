<?php


if( ! function_exists( 'bf_enqueue_modal' ) ){
	/**
	 * Enqueue BetterFramework modals safely
	 *
	 * @param $modal_key
	 */
	function bf_enqueue_modal( $modal_key = '' ) {

		Better_Framework::assets_manager()->add_modal( $modal_key ) ;

	}
}

if( ! function_exists( 'bf_enqueue_style' ) ){
	/**
	 * Enqueue BetterFramework styles safely
	 *
	 * @param $style_key
	 */
	function bf_enqueue_style( $style_key = '' ) {

		Better_Framework::assets_manager()->enqueue_style( $style_key );

	}
}


if( ! function_exists( 'bf_enqueue_script' ) ){
	/**
	 * Enqueue BetterFramework scripts safely
	 *
	 * @param $script_key
	 */
	function bf_enqueue_script( $script_key ) {

		Better_Framework::assets_manager()->enqueue_script( $script_key );

	}
}


if( ! function_exists( 'bf_add_jquery_js' ) ){
	/**
	 * Used for adding inline js to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_jquery_js( $code = '', $to_top = FALSE, $force = false  ) {

		Better_Framework::assets_manager()->add_jquery_js( $code, $to_top,$force );

	}
}


if ( ! function_exists( 'bf_add_js' ) ) {
	/**
	 * Used for adding inline js to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_js( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_js( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_css' ) ) {
	/**
	 * Used for adding inline css to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_css( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_css( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_admin_js' ) ) {
	/**
	 * Used for adding inline js to back end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_admin_js( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_admin_js( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_admin_css' ) ) {
	/**
	 * Used for adding inline css to back end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_admin_css( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_admin_css( $code, $to_top, $force );

	}
}
