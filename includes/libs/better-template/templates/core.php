<?php
/**
 * Core functions for BetterTemplate
 *
 * @package    BetterTemplate
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2015, BetterStudio
 */

//
//
// Global variable that used for save blocks property
//
//

// Used to save all template properties
$GLOBALS['better_template_props_cache'] = array();

// Used to save globals variables
$GLOBALS['better_template_globals_cache'] = array();

// Used to save template query
$GLOBALS['better_template_query'] = null;

if( ! function_exists( 'better_get_style' ) ) {
	/**
	 * Used to get current active style.
	 *
	 * Default style: general
	 *
	 * @return  string
	 */
	function better_get_style(){

		return 'general';

	}
}


if( ! function_exists( 'better_get_view' ) ){
	/**
	 * Used to print view/partials.
	 *
	 * @param   string  $folder     Folder name
	 * @param   string  $file       File name
	 * @param   string  $style      Style
	 * @param   bool    $echo       Echo the result or not
	 *
	 * @return null|string
	 */
	function better_get_view( $folder, $file = '', $style = '', $echo = true ){

		// If style is not provided
		if( empty( $style ) ){
			// Get current style if not defined
			$style = better_get_style();
		}

		if( $style == 'default' )
			$style = 'general'; // fix for new structure

		// If file name passed as folder argument for short method call
		if( ! empty( $folder ) && empty( $file ) ){
			$file = $folder;
			$folder = '';
		}


		$templates = array();

		// File is inside another folder
		if( ! empty( $folder ) ){

			$templates[] = 'views/' . $style . '/' . $folder . '/' . $file . '.php';

			// Fallback to general file
			if( $style != 'general' ){
				$templates[] = 'views/general/' . $folder . '/' . $file . '.php';
			}

		}
		// File is inside style base folder
		else{

			$templates[] = 'views/' . $style . '/' . $file . '.php';

			// Fallback to general file
			if( $style != 'general' ){
				$templates[] = 'views/general/' . $file . '.php';
			}

		}

		$template = locate_template( $templates, false, false );

		if( $echo == false )
			ob_start();

		do_action( 'better-template/view/before/' . $file );

		if( ! empty( $template ) )
			include $template;

		do_action( 'better-template/view/after/' . $file );

		if( $echo == false )
			return ob_get_clean();

	}
}


//
//
// Blocks properties
//
//


if( ! function_exists( 'better_get_prop' ) ){
	/**
	 * Used to get a property value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @return  mixed
	 */
	function better_get_prop( $id, $default = NULL ){

		global $better_template_props_cache;

		if( isset( $better_template_props_cache[$id] ) ){
			return $better_template_props_cache[$id];
		}else{
			return $default;
		}

	}
}


if( ! function_exists( 'better_echo_prop' ) ){
	/**
	 * Used to print a property value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @return  mixed
	 */
	function better_echo_prop( $id, $default = NULL ){

		global $better_template_props_cache;

		if( isset( $better_template_props_cache[$id] ) ){
			echo $better_template_props_cache[$id];
		}else{
			echo $default;
		}

	}
}


if( ! function_exists( 'better_get_prop_class' ) ){
	/**
	 * Used to get block class property.
	 *
	 * @return string
	 */
	function better_get_prop_class(){

		global $better_template_props_cache;

		if( isset( $better_template_props_cache['class'] ) ){
			return $better_template_props_cache['class'];
		}else{
			return '';
		}

	}
}


if( ! function_exists( 'better_get_prop_thumbnail_size' ) ){
	/**
	 * Used to get block thumbnail size property.
	 *
	 * @param   string  $default
	 *
	 * @return  string
	 */
	function better_get_prop_thumbnail_size( $default = 'thumbnail' ){

		global $better_template_props_cache;

		if( isset( $better_template_props_cache['thumbnail-size'] ) ){
			return $better_template_props_cache['thumbnail-size'];
		}else{
			return $default;
		}

	}
}


if( ! function_exists( 'better_set_prop' ) ){
	/**
	 * Used to set a block property value.
	 *
	 * @param   string  $id
	 * @param   mixed   $value
	 *
	 * @return  mixed
	 */
	function better_set_prop( $id, $value ){

		global $better_template_props_cache;

		$better_template_props_cache[$id] = $value;

	}
}


if( ! function_exists( 'better_set_prop_class' ) ){
	/**
	 * Used to set a block class property value.
	 *
	 * @param   mixed   $value
	 * @param   bool    $clean
	 *
	 * @return  mixed
	 */
	function better_set_prop_class( $value, $clean = false ){

		global $better_template_props_cache;

		if( $clean ){
			$better_template_props_cache['class'] = $value;
		}else{
			$better_template_props_cache['class'] = $value . ' ' . better_get_prop_class();
		}

	}
}


if( ! function_exists( 'better_set_prop_thumbnail_size' ) ){
	/**
	 * Used to set a block property value.
	 *
	 * @param   mixed   $value
	 *
	 * @return  mixed
	 */
	function better_set_prop_thumbnail_size( $value = 'thumbnail' ){

		global $better_template_props_cache;

		$better_template_props_cache['thumbnail-size'] = $value;

	}
}


if( ! function_exists( 'better_set_prop_count_multi_column' ) ){
	/**
	 * Used For Finding Best Count For Multiple columns
	 *
	 * @param int $post_counts
	 * @param int $columns
	 * @param int $current_column
	 */
	function better_set_prop_count_multi_column( $post_counts = 0, $columns = 1, $current_column = 1 ){

		if( $post_counts == 0 )
			return;

		$count = floor( $post_counts / $columns );

		$reminder = $post_counts % $columns;

		if( $reminder >= $current_column )
			$count++;

		better_set_prop( "posts-count", $count );
	}
}


if( ! function_exists( 'better_unset_prop' ) ){
	/**
	 * Used to remove a property from block property list.
	 *
	 * @param   string  $id
	 *
	 * @return  mixed
	 */
	function better_unset_prop( $id ){

		global $better_template_props_cache;

		unset( $better_template_props_cache[$id] );

	}
}


if( ! function_exists( 'better_clear_prop' ) ){
	/**
	 * Used to clear all properties.
	 *
	 * @return  void
	 */
	function better_clear_prop(){

		global $better_template_props_cache;

		$better_template_props_cache = array();

	}
}


//
//
// Global Variables
//
//


if( ! function_exists( 'better_set_global' ) ){
	/**
	 * Used to set a global variable.
	 *
	 * @param   string  $id
	 * @param   mixed   $value
	 *
	 * @return  mixed
	 */
	function better_set_global( $id, $value ){

		global $better_template_globals_cache;

		$better_template_globals_cache[$id] = $value;

	}
}


if( ! function_exists( 'better_unset_global' ) ){
	/**
	 * Used to remove a global variable.
	 *
	 * @param   string  $id
	 *
	 * @return  mixed
	 */
	function better_unset_global( $id ){

		global $better_template_globals_cache;

		unset( $better_template_globals_cache[$id] );

	}
}


if( ! function_exists( 'better_get_global' ) ){
	/**
	 * Used to get a global value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @return  mixed
	 */
	function better_get_global( $id, $default = NULL ){

		global $better_template_globals_cache;

		if( isset( $better_template_globals_cache[$id] ) ){
			return $better_template_globals_cache[$id];
		}else{
			return $default;
		}

	}
}


if( ! function_exists( 'better_echo_global' ) ){
	/**
	 * Used to print a global value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @return  mixed
	 */
	function better_echo_global( $id, $default = NULL ){

		global $better_template_globals_cache;

		if( isset( $better_template_globals_cache[$id] ) ){
			echo $better_template_globals_cache[$id];
		}else{
			echo $default;
		}

	}
}


if( ! function_exists( 'better_clear_global' ) ){
	/**
	 * Used to clear all properties.
	 *
	 * @return  void
	 */
	function better_clear_global(){

		global $better_template_globals_cache;

		$better_template_globals_cache = array();

	}
}


//
//
// Queries
//
//


if( ! function_exists( 'better_get_query' ) ){
	/**
	 * Used to get current query.
	 *
	 * @return  WP_Query|null
	 */
	function better_get_query(){

		global $better_template_query;

		// Add default query to better_template query if its not added or default query is used.
		if( ! is_a( $better_template_query, 'WP_Query' ) ){
			global $wp_query;

			$better_template_query = &$wp_query;
		}

		return $better_template_query;

	}
}


if( ! function_exists( 'better_set_query' ) ){
	/**
	 * Used to get current query.
	 *
	 * @param   WP_Query    $query
	 */
	function better_set_query( &$query ){

		global $better_template_query;

		$better_template_query = $query;

	}
}


if( ! function_exists( 'better_clear_query' ) ){
	/**
	 * Used to get current query.
	 *
	 * @param   bool    $reset_query
	 */
	function better_clear_query( $reset_query = true ){

		global $better_template_query;

		$better_template_query = null;

		// This will remove obscure bugs that occur when the previous wp_query object is not destroyed properly before another is set up.
		if( $reset_query )
			wp_reset_query();

	}
}


if( ! function_exists( 'better_have_posts' ) ){
	/**
	 * Used for checking have posts in advanced way!
	 */
	function better_have_posts(){

		// Add default query to better_template query if its not added or default query is used.
		if ( ! better_get_query() instanceof WP_Query ) {
			global $wp_query;

			better_set_query( $wp_query );
		}

		// If count customized
		if( better_get_prop( 'posts-count', null ) != null ){
			if( better_get_prop( 'posts-counter', 1 ) > better_get_prop( 'posts-count' ) ){
				return false;
			}else{
				if( better_get_query()->current_post + 1 < better_get_query()->post_count ){
					return true;
				}
				else{
					return false;
				}
			}
		}else{
			return better_get_query()->current_post + 1 < better_get_query()->post_count;
		}

	}
}


if( ! function_exists( 'better_the_post' ) ){
	/**
	 * Custom the_post for custom counter functionality
	 */
	function better_the_post(){

		// If count customized
		if( better_get_prop( 'posts-count', null ) != null ){
			better_set_prop( 'posts-counter', absint( better_get_prop( 'posts-counter', 1 ) ) + 1 );
		}

		// Do default the_post
		better_get_query()->the_post();

	}
}


if( ! function_exists( 'better_is_main_query' ) ){
	/**
	 * Detects and returns that current query is main query or not? with support of better_{get|set}_query
	 *
	 * @return  WP_Query|null
	 */
	function better_is_main_query(){

		global $better_template_query;

		// Add default query to better_template query if its not added or default query is used.
		if( ! is_a( $better_template_query, 'WP_Query' ) ){
			global $wp_query;

			return $wp_query->is_main_query();
		}

		return $better_template_query->is_main_query();
	}
}
