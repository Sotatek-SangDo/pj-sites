<?php


// Callback For Video Format auto-embed
add_filter( 'better-framework/content/video-embed', 'bf_auto_embed_content' );
add_filter( 'better-framework/content/auto-embed', 'bf_auto_embed_content' );


if( ! function_exists( 'bf_auto_embed_content' ) ){
	/**
	 * Filter Callback: Auto-embed using a link
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	function bf_auto_embed_content( $content ) {

		global $wp_embed;

		if( ! is_object( $wp_embed ) ){
			return $content;
		}

		return $wp_embed->autoembed( $content );
	}
}
