<?php
/**
 * Handy functions for making development quicker in getting addresses.
 *
 * @package    BetterFramework
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2015, BetterStudio
 */


if( ! function_exists( 'bf_get_dir' ) ){
	/**
	 * Get BetterFramework directory path
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_dir( $append = '' ) {
		return BF_PATH . $append;
	}
}


if( ! function_exists( 'bf_require' ) ){
	/**
	 * Used to require file inside BetterFramework
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_require( $append = '' ) {
		require BF_PATH . $append;
	}
}


if( ! function_exists( 'bf_require_once' ) ){
	/**
	 * Used to require_once file inside BetterFramework
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_require_once( $append = '' ) {
		require_once BF_PATH . $append;
	}
}


if( ! function_exists( 'bf_get_uri' ) ){
	/**
	 * Get BetterFramework directory URI (URL)
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_uri( $append = '' ) {
		return BF_URI . $append;
	}
}


if( ! function_exists( 'bf_get_theme_dir' ) ){
	/**
	 * Parent theme directory.
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_theme_dir( $append = '' ) {
		return get_template_directory() . '/' . $append;
	}
}


if( ! function_exists( 'bf_get_theme_uri' ) ) {
	/**
	 * Parent theme directory URI.
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_theme_uri( $append = '' ) {
		return get_template_directory_uri() . '/' . $append;
	}
}


if( ! function_exists( 'bf_get_child_theme_dir' ) ) {
	/**
	 * Child theme directory.
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_child_theme_dir( $append = '' ) {
		return get_stylesheet_directory().'/'.$append;
	}
}


if( ! function_exists( 'bf_get_child_theme_uri' ) ) {
	/**
	 * Child theme directory URI.
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	function bf_get_child_theme_uri( $append = '' ) {
		return get_stylesheet_directory_uri().'/'.$append;
	}
}