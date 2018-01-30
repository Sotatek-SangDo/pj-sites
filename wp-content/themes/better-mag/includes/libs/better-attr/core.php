<?php


if( ! function_exists( 'better_attr' ) ){
	/**
	 * Outputs an HTML element's attributes.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string  $slug       Slug/ID of the element/tag
	 * @param   string  $class      Extra classes
	 * @param   string  $context    Specific context ex: primary
	 *
	 * @return void
	 */
	function better_attr( $slug, $class = '', $context = '' ){
		echo better_get_attr( $slug, $class, $context );
	}
}


if( ! function_exists( 'better_get_attr' ) ){
	/**
	 * Gets an HTML element's attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param   string  $slug       Slug/ID of the element/tag
	 * @param   string  $class      Extra classes
	 * @param   string  $context    Specific context ex: primary
	 *
	 * @return string
	 */
	function better_get_attr( $slug, $class = '', $context = '' ){

		$output = '';

		$attributes   = apply_filters( "better_attr_{$slug}", array(), $class, $context );

		if( empty( $attributes ) ){
			$attributes['class'] = $slug;
		}

		foreach( $attributes as $attr_id => $attr ){
			$output .= ! empty( $attr ) ? sprintf( ' %s="%s"', esc_html( $attr_id ), esc_attr( $attr ) ) : esc_html( " {$attr_id}" );
		}

		return trim( $output );
	}
}


if( ! function_exists( 'better_attr_meta' ) ){
	/**
	 * Outputs an HTML meta tag.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string  $prop       Meta itemprop value
	 * @param   string  $content    Default meta content value
	 *
	 * @return void
	 */
	function better_attr_meta( $prop , $content = '' ){
		echo better_get_attr_meta( $prop, $content );
	}
}


if( ! function_exists( 'better_get_attr_meta' ) ){
	/**
	 * Gets an HTML meta tag.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string  $prop       Meta itemprop value
	 * @param   string  $content    Default meta content value
	 *
	 * @return string
	 */
	function better_get_attr_meta( $prop, $content = '' ){

		if( $prop == 'full' ){

			$list = array(
				'headline' => 'headline',
				'url'      => 'url',
				'date'     => 'date',
				'image'    => 'image',
				'author'   => 'author',
				'comments' => 'comments',
			);

			switch ( get_post_format() ){

				case 'video':
					unset( $list['headline'] );
					$list[] = 'name';

					$list[] = 'description';
					$list[] = 'date_upload';
					break;

			}

			foreach( $list as $item ){
				better_attr_meta( $item, '' );
			}

		}elseif( in_array( $prop, array( 'headline', 'url', 'date', 'image', 'author', 'comments', 'description', 'name', 'date_upload' ) ) ){

			$output = '';

			$attr   = apply_filters( "better_attr_meta_{$prop}", $content );

			// exception for empty data, ex when there is no featured image
			if( isset( $attr['empty'] ) )
				return '';

			if( empty( $attr ) ){
				$attr['itemprop'] = $prop;
				$attr['content'] = $content;
			}

			foreach( $attr as $name => $value ){
				$output .= $value != '' ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );
			}

			return '<meta ' . trim( $output ) . ' />';

		}else{

			$output = '';

			$attr['itemprop'] = $prop;
			$attr['content'] = $content;

			foreach( $attr as $name => $value ){

				$output .= ! empty( $value ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );

			}

			return '<meta ' . trim( $output ) . ' />';

		}

	} // better_get_attr_meta
}