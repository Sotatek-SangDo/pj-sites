<?php


if( ! function_exists( 'bf_get_user_meta' ) ){
	/**
	 * Used for finding user meta field value.
	 *
	 * @since   2.0
	 *
	 * @param   string         $field_key     User field ID
	 * @param   string|WP_User $user          User ID or object
	 * @param   null           $force_default Default value (Optional)
	 *
	 * @return mixed
	 */
	function bf_get_user_meta( $field_key, $user = NULL, $force_default = NULL ) {

		if( is_null( $user ) ){

			// Get current post author id
			if( is_singular() ){
				$user = get_the_author_meta( 'ID' );
			} // Get current archive user
			elseif( is_author() ) {
				$user = bf_get_author_archive_user();
			} // Return default value
			else {
				return $force_default;
			}
		}

		// Get user id from object
		if( is_object( $user ) ){
			$user = $user->ID;
		}

		// get value if saved in DB
		$value = get_user_meta( $user, $field_key );
		if( ( $value !== FALSE ) && count( $value ) > 0 ){
			return current( $value );
		} // Or return force default value
		elseif( ! is_null( $force_default ) ) {
			return $force_default;
		}

		// load user options if not loaded
		BF()->user_meta()->load_options();

		// Iterate all meta boxes
		foreach( BF()->user_meta()->options as $metabox ) {

			// if meta box have this field
			if( ! isset( $metabox['fields'][ $field_key ] ) ){
				continue;
			}

			// if this meta box connected to a panel for style field
			if( isset( $metabox['panel-id'] ) ){
				$std_id = BF()->options()->get_std_field_id( $metabox['panel-id'] );
			} else {
				$std_id = 'std';
			}

			// retrieve default value
			if( isset( $metabox['fields'][ $field_key ][ $std_id ] ) ){
				return $metabox['fields'][ $field_key ][ $std_id ];
			} elseif( isset( $metabox['fields'][ $field_key ]['std'] ) ) {
				return $metabox['fields'][ $field_key ]['std'];
			}

		}

		return FALSE;

	}
}


if( ! function_exists( 'bf_echo_user_meta' ) ){
	/**
	 * Used to echo user meta field value.
	 *
	 * @since   2.0
	 *
	 * @param   string         $field_key     User field ID
	 * @param   string|WP_User $user          User ID or object
	 * @param   null           $force_default Default value (Optional)
	 *
	 * @return mixed
	 */
	function bf_echo_user_meta( $field_key, $user = NULL, $force_default = NULL ) {

		echo bf_get_user_meta( $field_key, $user, $force_default );

	}
}

