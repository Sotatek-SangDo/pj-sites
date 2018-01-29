<?php


if( ! function_exists( 'bf_get_post_meta' ) ){

	/**
	 * Used for retrieving meta fields ofr  posts and pages
	 *
	 * @param null        $key           Field ID
	 * @param null        $post_id       Post ID (Optional)
	 * @param null|string $force_default Default value (Optional)
	 *
	 * @return mixed|void
	 */
	function bf_get_post_meta( $key = NULL, $post_id = NULL, $force_default = NULL ) {

		if( is_null( $post_id ) ){
			global $post;
			$post_id = $post->ID;
		}

		$meta = get_post_meta( $post_id, $key, TRUE );

		if( empty( $meta ) && ! is_null( $force_default ) ){
			return $force_default;
		}
		// If Meta check for default value
		if( $meta === '' ){

			// Load all meta box fields (one time!)
			BF()->post_meta()->load_options();

			foreach( (array) BF()->post_meta()->options as $metabox_key => $metabox ) {

				// get style id for current metabox
				if( isset( $metabox['panel-id'] ) ){
					$std_id = BF()->options()->get_std_field_id( $metabox['panel-id'] );
				} else {
					$std_id = 'std';
				}

				foreach( (array) $metabox['fields'] as $field_key => $field ) {

					// check for value
					if( ! isset( $field['id'] ) || $field['id'] != $key ){
						continue;
					}


					if( isset( $field[ $std_id ] ) ){
						return $field[ $std_id ];
					} elseif( isset( $field['std'] ) ) {
						return $field['std'];
					}

				}

			}

		} else {

			return $meta;

		}

		return '';
	}
}


if( ! function_exists( 'bf_echo_post_meta' ) ){

	/**
	 * Used for retrieving meta fields ofr  posts and pages
	 *
	 * @param null        $key           Field ID
	 * @param null        $post_id       Post ID (Optional)
	 * @param null|string $force_default Default value (Optional)
	 *
	 * @return mixed|void
	 */
	function bf_echo_post_meta( $key = NULL, $post_id = NULL, $force_default = NULL ) {

		echo bf_get_post_meta( $key, $post_id, $force_default );

	}
}