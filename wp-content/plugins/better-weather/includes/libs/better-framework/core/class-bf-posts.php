<?php


/**
 * Contain Functionality Related To Posts and Pages That Used In Themes
 */
class BF_Posts {


	/**
	 * Deprecated!
	 *
	 * Custom excerpt
	 *
	 * @param  integer $length
	 * @param  string|null $text
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function excerpt( $length = 24, $text = null, $echo = true ){

		// If text not defined get excerpt
		if( ! $text ){

			// have a manual excerpt?
			if( has_excerpt( get_the_ID() ) ){

				if( $echo ){
					echo apply_filters( 'the_excerpt', get_the_excerpt() );
					return;
				}else
					return apply_filters( 'the_excerpt', get_the_excerpt() );

			}else{

				$text = get_the_content( '' );

			}

		}

		$text = strip_shortcodes( $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		// get plaintext excerpt trimmed to right length
		$excerpt = wp_trim_words( $text, $length, '&hellip;' );

		// fix extra spaces
		$excerpt = trim( str_replace('&nbsp;', ' ', $excerpt ) );


		if( $echo )
			echo $excerpt;
		else
			return $excerpt;
	}


}