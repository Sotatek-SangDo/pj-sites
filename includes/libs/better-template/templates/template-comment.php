<?php
/**
 * Functions for loading comment templates and some other handy function about comments..
 *
 * @package    BetterTemplate
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2015, BetterStudio
 */

// Used to save comment template for better performance
$GLOBALS['better_comment_templates_cache'] = null;


if( ! function_exists( 'better_list_comments_args' ) ){
	/**
	 * Arguments for the wp_list_comments_function() used in comments.php.
	 *
	 * @since  1.0.0
	 * @param  array  $args
	 * @return array
	 */
	function better_list_comments_args( $args = array() ){

		// Default arguments for listing comments.
		$defaults = array(
			'style'         =>   'ol',
			'type'          =>   'all',
			'avatar_size'   =>   60,
			'callback'      =>   'better_comments_callback',
			'end-callback'  =>   'better_comments_end_callback'
		);

		// Filter default arguments to enable developers to change it. also return it.
		return apply_filters( 'better-template/comments/list-args', wp_parse_args( $args, $defaults ) );
	}
}


if( ! function_exists( 'better_comments_callback' ) ){
	/**
	 * Determine which comment template should be used and save it to ache and locate.
	 *
	 * @since  1.0.0
	 *
	 * @param  $comment object  Comment object.
	 * @param  $args    Array   Arguments passed from wp_list_comments().
	 * @param  $depth   Int     Comment level.
	 *
	 * @return void
	 */
	function better_comments_callback( $comment, $args, $depth ){

		global $better_comment_templates_cache;

		// current comment type
		$comment_type = get_comment_type( $comment->comment_ID );

		$style = better_get_style();

		if( $style == 'default' )
			$style = 'general'; // fix for new structure

		// Not cached before
		if( ! isset( $better_comment_templates_cache[$comment_type] ) ){

			$templates = array();

			// Extra comment/ping.php for both pingback and trackback
			if( 'pingback' == $comment_type || 'trackback' == $comment_type ){

				$templates[] = "views/{$style}/comments/ping.php";

				// Fallback to general ping comment template
				if( $style != 'general' )
					$templates[] = 'views/general/comments/ping.php';

			}

			$templates[] = "views/{$style}/comments/comment.php";

			// fallback to default comment template
			if( $style != 'general' )
				$templates[] = 'views/general/comments/comment.php';

			$template = locate_template( $templates );

			// Cache comment template.
			$better_comment_templates_cache[$comment_type] = $template;
		}

		// Include if not empty
		if( $better_comment_templates_cache[$comment_type] != '' )
			include $better_comment_templates_cache[$comment_type];
	}
}


if( ! function_exists( 'better_comments_end_callback' ) ){
	/**
	 * Ends the display of comments.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	function better_comments_end_callback(){

		echo '</li><!-- .comment -->';

	}
}


if( ! function_exists( 'better_echo_comment_reply_link' ) ){
	/**
	 * Outputs the comment reply link.
	 * Only use outside of `wp_list_comments()`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  array $args
	 *
	 * @return void
	 */
	function better_echo_comment_reply_link( $args = array() ) {

		echo better_get_comment_reply_link( $args );

	}
}


if( ! function_exists( 'better_get_comment_reply_link' ) ){
	/**
	 * Outputs the comment reply link.
	 * Only use outside of `wp_list_comments()`.
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @param  array $args
	 *
	 * @return string
	 */
	function better_get_comment_reply_link( $args = array() ) {

		if( ! get_option( 'thread_comments' ) || in_array( get_comment_type(), array( 'pingback', 'trackback' ) ) ){
			return '';
		}

		$args = wp_parse_args(
			$args,
			array(
				'depth'         =>  intval( $GLOBALS['comment_depth'] ),
				'max_depth'     =>  get_option( 'thread_comments_depth' ),
				'reply_text'    =>  '<i class="fa fa-reply"></i> ' . Better_Translation()->_get( 'comments_reply' ),
				'reply_to_text' =>  '<i class="fa fa-reply"></i> ' . Better_Translation()->_get( 'comments_reply_to' ),
				'login_text'    =>  '<i class="fa fa-reply"></i> ' . Better_Translation()->_get( 'comments_logged_as' ),
			)
		);

		return get_comment_reply_link( $args );
	}
}


if( ! function_exists( 'better_get_comment_avatar' ) ){
	/**
	 * @param string $id_or_email
	 * @param string $size
	 * @param string $default
	 * @param bool   $alt
	 *
	 * @return false|string
	 */
	function better_get_comment_avatar( $id_or_email, $size = '60', $default = '', $alt = FALSE ) {

		return get_avatar( $id_or_email, $size, $default, $alt );

	}
}


if( ! function_exists( 'better_echo_comment_avatar' ) ){
	/**
	 * @param string $id_or_email
	 * @param string $size
	 * @param string $default
	 * @param bool   $alt
	 *
	 * @return false|string
	 */
	function better_echo_comment_avatar( $id_or_email, $size = '60', $default = '', $alt = FALSE ) {

		echo better_get_comment_avatar( $id_or_email, $size, $default, $alt );

	}
}
