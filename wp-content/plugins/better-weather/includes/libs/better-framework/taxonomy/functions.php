<?php

if ( ! function_exists( 'bf_use_wp_term_meta' ) ) {

	/**
	 * Check wordpress version support term meta
	 *
	 * @return bool true on support
	 */
	function bf_use_wp_term_meta() {
		static $bf_use_wp_term_meta;

		if ( is_null( $bf_use_wp_term_meta ) ) {
			$bf_use_wp_term_meta = get_option( 'db_version' ) >= 34370 && function_exists( 'add_term_meta' );
		}

		return $bf_use_wp_term_meta;
	}
}

if( ! function_exists( 'bf_get_term_meta' ) ){
	/**
	 * Used For retrieving meta of term
	 *
	 * @param   int|object  $term_id       Term ID or object
	 * @param   string      $meta_key      Custom Field ID
	 * @param   bool|string $force_default Default Value
	 * @param   bool        $single        Whether to return a single value. If false, an array of all values matching the
	 *                                     `$term_id`/`$key` pair will be returned. Default: false.
	 *
	 * @return mixed If `$single` is false, an array of metadata values. If `$single` is true, a single metadata value.
	 */
	function bf_get_term_meta( $meta_key, $term_id = NULL, $force_default = NULL, $single = TRUE ) {

		if ( bf_use_wp_term_meta() ) {
			if(! $term_id) {
				if( is_category() || is_tag() ){
					$term_id = get_queried_object()->term_id;
				}
			}
			$meta_value = get_term_meta( $term_id, $meta_key, $single );

			if ( $meta_value === '' ) {
				return $force_default;
			}

			return $meta_value;
		}
		// Extract ID from term object if passed
		if( is_object( $term_id ) ){
			if( isset( $term_id->term_id ) ){
				$term_id = $term_id->term_id;
			} else {
				return $force_default;
			}
		} else {

			// If term ID not passed
			if( is_null( $term_id ) ){
				// If its category or tag archive get that term ID
				// todo check and fix get_queried_object() for custom taxonomies
				if( is_category() || is_tag() ){
					$term_id = get_queried_object()->term_id;
				} else {
					return $force_default;
				}

			}

		}

		// Return it from cache
		if( isset( BF()->taxonomy_meta()->cache[ $term_id ][ $meta_key ] ) ){
			return BF()->taxonomy_meta()->cache[ $term_id ][ $meta_key ];
		} else if(empty($meta_key)) {
			if($cached =  BF()->taxonomy_meta()->cache[ $term_id ] )
				return $cached;
		}

		if( $output = get_option( 'bf_term_'.$term_id ) ){

			if( isset( $output[ $meta_key ] ) ){
				BF()->taxonomy_meta()->cache[ $term_id ] = $output; // Save to cache
				return $output[ $meta_key ];
			} else if(empty($meta_key)) {
				return $output;
			}

		}

		// Default value for function have more priority to std field
		if( ! is_null( $force_default ) ){
			return $force_default;
		}

		// Load all options one time
		BF()->taxonomy_meta()->load_options();

		// Iterate All Metaboxe
		foreach( BF()->taxonomy_meta()->taxonomy_options as $metabox_id => $metabox ) {

			if( isset( $metabox['fields'][ $meta_key ] ) ){

				if( isset( $metabox['panel-id'] ) ){
					$std_id = BF()->options()->get_std_field_id( $metabox['panel-id'] );
				} else {
					$std_id = 'std';
				}

				if( isset( $metabox['fields'][ $meta_key ][ $std_id ] ) ){
					return $metabox['fields'][ $meta_key ][ $std_id ];
				} elseif( isset( $metabox['fields'][ $meta_key ]['std'] ) ) {
					return $metabox['fields'][ $meta_key ]['std'];
				} else {
					return $force_default;
				}

			}

		}

		// Fallback to return force default value! This return 'null' if not passed
		return $force_default;
	}
}


if( ! function_exists( 'bf_echo_term_meta' ) ){
	/**
	 * Used For echo meta of term
	 *
	 * @param   int|object  $term_id       Term ID or object
	 * @param   string      $meta_id       Custom Field ID
	 * @param   bool|string $force_default Default Value
	 *
	 * @return bool
	 */
	function bf_echo_term_meta( $meta_id, $term_id = NULL, $force_default = NULL ) {

		echo bf_get_term_meta( $meta_id, $term_id, $force_default );

	}
}


if( ! function_exists( 'bf_update_term_meta' ) ){

	/**
	 * Updates term metadata.
	 *
	 * Use the `$prev_value` parameter to differentiate between meta fields with the same key and term ID.
	 *
	 * If the meta field for the term does not exist, it will be added.
	 *
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 * @param mixed  $prev_value Optional. Previous value to check before removing.
	 *
	 * @return int|WP_Error|bool Meta ID if the key didn't previously exist. True on successful update.
	 *                           WP_Error when term_id is ambiguous between taxonomies. False on failure.
	 */
	function bf_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {

		if ( bf_use_wp_term_meta() ) {
			return update_term_meta( $term_id, $meta_key, $meta_value, $prev_value );
		}

		//use old method
		$all_meta  = get_option( 'bf_term_' . $term_id, array() );
		$old_value = isset( $all_meta[ $meta_key ] ) ? $all_meta[ $meta_key ] : NULL;

		// Compare existing value to new value if no prev value given and the key exists only once.
		if ( empty( $prev_value ) ) {
			if ( $old_value === $meta_value ) {
				return FALSE;
			}
		} else {
			$prev_value = maybe_serialize( $prev_value );
			if ( maybe_serialize( $old_value ) !== $prev_value ) {
				return FALSE;
			}
		}

		$all_meta[ $meta_key ] = $meta_value;

		return update_option( 'bf_term_' . $term_id, $all_meta, 'no' );
	}
}

if( ! function_exists( 'bf_delete_term_meta' ) ) {

	/**
	 * Removes metadata matching criteria from a term.
	 *
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata name.
	 * @param mixed  $meta_value Optional. Metadata value. If provided, rows will only be removed that match the value.
	 *
	 * @return bool True on success, false on failure.
	 */
	function bf_delete_term_meta( $term_id, $meta_key, $meta_value = '' ) {

		if ( bf_use_wp_term_meta() ) {
			return delete_term_meta( $term_id, $meta_key, $meta_value );
		}

		//use old method
		$all_meta  = get_option( 'bf_term_' . $term_id, array() );
		$old_value = isset( $all_meta[ $meta_key ] ) ? $all_meta[ $meta_key ] : NULL;

		// Compare existing value to new value if no prev value given and the key exists only once.
		if ( ! empty( $meta_value ) ) {
			$meta_value = maybe_serialize( $meta_value );
			if ( maybe_serialize( $old_value ) !== $meta_value ) {
				return FALSE;
			}
		}
		unset( $all_meta[ $meta_key ] );

		return update_option( 'bf_term_' . $term_id, $all_meta, 'no' );
	}
}


if ( ! function_exists( 'bf_add_term_meta' ) ) {

	/**
	 * Adds metadata to a term.
	 *
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata name.
	 * @param mixed  $meta_value Metadata value.
	 * @param bool   $unique     Optional. Whether to bail if an entry with the same key is found for the term.
	 *                           Default false.
	 *
	 * @return int|WP_Error|bool Meta ID on success. WP_Error when term_id is ambiguous between taxonomies.
	 *                           False on failure.
	 */
	function bf_add_term_meta( $term_id, $meta_key, $meta_value, $unique = FALSE ) {

		if ( bf_use_wp_term_meta() ) {
			return add_term_meta( $term_id, $meta_key, $meta_value, $unique );
		}

		return bf_update_term_meta( $term_id, $meta_key, $meta_value );
	}
}

