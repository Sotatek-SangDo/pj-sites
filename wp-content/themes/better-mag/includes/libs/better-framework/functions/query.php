<?php

if ( ! function_exists( 'bf_get_pages' ) ) {
	/**
	 * Get Pages
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 2.3
	 *
	 * @return array
	 */
	function bf_get_pages( $extra = array() ) {

		/*
			Extra Usage:

			array(
				'sort_order'        =>  'ASC',
				'sort_column'       =>  'post_title',
				'hierarchical'      =>  1,
				'exclude'           =>  '',
				'include'           =>  '',
				'meta_key'          =>  '',
				'meta_value'        =>  '',
				'authors'           =>  '',
				'child_of'          =>  0,
				'parent'            =>  -1,
				'exclude_tree'      =>  '',
				'number'            =>  '',
				'offset'            =>  0,
				'post_type'         =>  'page',
				'post_status'       =>  'publish'
			)

		*/

		$output = array();

		$query = get_pages( $extra );

		foreach ( $query as $page ) {
			$output[ $page->ID ] = $page->post_title;
		}

		return $output;

	} // bf_get_pages
} // if


if ( ! function_exists( 'bf_get_posts' ) ) {

	/**
	 * Get Posts
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 2.3
	 *
	 * @return array
	 */
	function bf_get_posts( $extra = array() ) {

		/*
			Extra Usage:

			array(
				'posts_per_page'  => 5,
				'offset'          => 0,
				'category'        => '',
				'orderby'         => 'post_date',
				'order'           => 'DESC',
				'include'         => '',
				'exclude'         => '',
				'meta_key'        => '',
				'meta_value'      => '',
				'post_type'       => 'post',
				'post_mime_type'  => '',
				'post_parent'     => '',
				'post_status'     => 'publish',
				'suppress_filters' => true
			)
		*/

		$output = array();

		$query = get_posts( $extra );

		foreach ( $query as $post ) {
			$output[ $post->ID ] = $post->post_title;
		}

		return $output;

	} // bf_get_posts

} // if


if ( ! function_exists( 'bf_get_random_post_link' ) ) {
	/**
	 * Get an link for a random post
	 *
	 * @param bool $echo
	 *
	 * @return bool|string
	 */
	function bf_get_random_post_link( $echo = TRUE ) {

		$query = new WP_Query(
			array(
				'orderby'        => 'rand',
				'posts_per_page' => '1'
			)
		);

		if ( $echo ) {
			echo get_permalink( $query->posts[0] );
		} else {
			return get_permalink( $query->posts[0] );
		}

	} // bf_get_random_post_link
} // if


if ( ! function_exists( 'bf_get_categories' ) ) {
	/**
	 * Get categories
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_categories( $extra = array() ) {

		/*
			Extra Usage:

			array(
				'type'          => 'post',
				'child_of'      => 0,
				'parent'        => '',
				'orderby'       => 'name',
				'order'         => 'ASC',
				'hide_empty'    => 1,
				'hierarchical'  => 1,
				'exclude'       => '',
				'include'       => '',
				'number'        => '',
				'taxonomy'      => 'category',
				'pad_counts'    => false
			)
		*/

		$output = array();

		$query = get_categories( $extra );

		foreach ( $query as $cat ) {
			$output[ $cat->cat_ID ] = $cat->name;
		}

		return $output;

	} // bf_get_categories
} // if


if ( ! function_exists( 'bf_get_categories_by_slug' ) ) {
	/**
	 * Get categories
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_categories_by_slug( $extra = array() ) {

		/*
			Extra Usage:

			array(
				'type'          => 'post',
				'child_of'      => 0,
				'parent'        => '',
				'orderby'       => 'name',
				'order'         => 'ASC',
				'hide_empty'    => 1,
				'hierarchical'  => 1,
				'exclude'       => '',
				'include'       => '',
				'number'        => '',
				'taxonomy'      => 'category',
				'pad_counts'    => false
			)
		*/

		$output = array();

		$query = get_categories( $extra );

		foreach ( $query as $cat ) {
			$output[ $cat->slug ] = $cat->name;
		}

		return $output;

	} // bf_get_categories_by_slug
} // if


if ( ! function_exists( 'bf_get_tags' ) ) {
	/**
	 * Get Tags
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function bf_get_tags( $extra = array() ) {

		$output = array();
		$query  = get_tags( $extra );

		foreach ( $query as $tag ) {
			$output[ $tag->term_id ] = $tag->name;
		}

		return $output;

	} // bf_get_tags
} // if


if ( ! function_exists( 'bf_get_users' ) ) {
	/**
	 * Get users
	 *
	 * @param array      $extra           Extra Options.
	 * @param array|bool $advanced_output Advanced Query is the results with query other resutls
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_users( $extra = array(), $advanced_output = FALSE ) {

		$output = array();

		if ( count( $extra ) === 0 ) {
			$extra = array(
				'orderby' => 'post_count',
				'order'   => 'DESC'
			);
		}

		$query = new WP_User_Query( $extra );

		foreach ( $query->results as $user ) {
			$output[ $user->data->ID ] = $user->data->display_name;
		}

		if ( $advanced_output ) {
			// Unset the result for make free the memory
			unset( $query->results );

			return array( $output, $query );
		}

		return $output;

	} // bf_get_users
} // if


if ( ! function_exists( 'bf_get_post_types' ) ) {
	/**
	 * Get Post Types
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_post_types( $extra = array() ) {

		$output = array();

		if ( ! isset( $extra['exclude'] ) || ! is_array( $extra['exclude'] ) ) {
			$extra['exclude'] = array();
		}

		// Add revisions, nave menu and attachment post types to excludes
		$extra['exclude'] = array_merge( $extra['exclude'], array( 'revision', 'nav_menu_item', 'attachment' ) );

		$query = get_post_types();

		foreach ( $query as $key => $val ) {

			if ( in_array( $key, $extra['exclude'] ) ) {
				continue;
			}

			$output[ $key ] = ucfirst( $val );
		}

		return $output;

	} // bf_get_post_types
} // if


if ( ! function_exists( 'bf_get_page_templates' ) ) {
	/**
	 * Get Page Templates
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function bf_get_page_templates( $extra = array() ) {

		$output = array();

		if ( ! isset( $extra['exclude'] ) || ! is_array( $extra['exclude'] ) ) {
			$extra['exclude'] = array();
		}

		$query = wp_get_theme()->get_page_templates();

		foreach ( $query as $key => $val ) {

			if ( in_array( $key, $extra['exclude'] ) ) {
				continue;
			}

			$output[ $key ] = $val;
		}

		return $output;

	} // bf_get_page_templates
} // if


if ( ! function_exists( 'bf_get_taxonomies' ) ) {
	/**
	 * Get Taxonomies
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_taxonomies( $extra = array() ) {

		$output = array();

		$query = get_taxonomies();

		if ( ! isset( $extra['exclude'] ) || ! is_array( $extra['exclude'] ) ) {
			$extra['exclude'] = array();
		}

		foreach ( $query as $key => $val ) {

			if ( in_array( $key, $extra['exclude'] ) ) {
				continue;
			}

			$output[ $key ] = ucfirst( str_replace( '_', ' ', $val ) );
		}

		return $output;

	} // bf_get_taxonomies
} // if


if ( ! function_exists( 'bf_get_terms' ) ) {
	/**
	 * Get All Terms of Specific Taxonomy
	 *
	 * @param array|string $tax   Taxonomy Slug
	 * @param array        $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_terms( $tax = 'category', $extra = array() ) {

		if ( ! isset( $extra['exclude'] ) || ! is_array( $extra['exclude'] ) ) {
			$extra['exclude'] = array();
		}

		$query  = get_terms( $tax, $extra );
		$output = array();

		foreach ( $query as $taxonomy ) {

			if ( in_array( $taxonomy->slug, $extra['exclude'] ) ) {
				continue;
			}

			$output[ $taxonomy->slug ] = $taxonomy->name;
		}

		return $output;

	} // bf_get_terms
}// if


if ( ! function_exists( 'bf_get_roles' ) ) {
	/**
	 * Get Roles
	 *
	 * @param array $extra Extra Options.
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_roles( $extra = array() ) {

		global $wp_roles;

		$output = array();

		if ( ! isset( $extra['exclude'] ) || ! is_array( $extra['exclude'] ) ) {
			$extra['exclude'] = array();
		}

		foreach ( $wp_roles->roles as $key => $val ) {

			if ( in_array( $key, $extra['exclude'] ) ) {
				continue;
			}

			$output[ $key ] = $val['name'];
		}

		return $output;

	} // bf_get_roles
} // if


if ( ! function_exists( 'bf_get_menus' ) ) {
	/**
	 * Get Menus
	 *
	 * @param bool $hide_empty
	 *
	 * @since 1.0
	 * @return array
	 */
	function bf_get_menus( $hide_empty = FALSE ) {

		$output = array();

		$menus = get_terms( 'nav_menu', array( 'hide_empty' => $hide_empty ) );

		foreach ( $menus as $menu ) {
			$output[ $menu->term_id ] = $menu->name;
		}

		return $output;

	} // bf_get_menus
} // if

if ( ! function_exists( 'bf_is_a_category' ) ) {
	/**
	 * Used to detect category from id
	 *
	 * @param null $id
	 *
	 * @return bool|mixed
	 */
	function bf_is_a_category( $id = NULL ) {

		if ( is_null( $id ) ) {
			return FALSE;
		}

		$cat = get_category( $id );

		if ( count( $cat ) > 0 ) {
			return current( $cat );
		} else {
			return FALSE;
		}

	} // bf_is_a_category
} // if


if ( ! function_exists( 'bf_is_a_tag' ) ) {
	/**
	 * Used to detect tag from id
	 *
	 * @param null $id
	 *
	 * @return bool|mixed
	 */
	function bf_is_a_tag( $id = NULL ) {

		if ( is_null( $id ) ) {
			return FALSE;
		}

		$tag = get_tag( $id );

		if ( count( $tag ) > 0 ) {
			return current( $tag );
		} else {
			return FALSE;
		}

	} // bf_is_a_tag
} // if


if ( ! function_exists( 'bf_get_rev_sliders' ) ) {
	/**
	 * Used to find list of all RevolutionSlider Sliders.zip
	 *
	 * @return array
	 */
	function bf_get_rev_sliders() {

		$sliders = array();

		if ( function_exists( 'rev_slider_shortcode' ) ) {

			global $wpdb;

			$temp_sliders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'revslider_sliders' );

			if ( $temp_sliders ) {

				foreach ( $temp_sliders as $slider ) {

					$sliders[ $slider->alias ] = $slider->title;

				}

			}
		}

		return $sliders;

	} // bf_get_rev_sliders
} // if


if ( ! function_exists( 'bf_get_wp_query_vars' ) ) {
	/**
	 * Creats flatted and valid query_vars from an instance of WP_Query object
	 *
	 * @param WP_Query $wp_query
	 *
	 * @return array
	 */
	function bf_get_wp_query_vars( $wp_query ) {

		if ( ! is_a( $wp_query, 'WP_Query' ) ) {
			return array();
		}

		$args = $wp_query->query_vars;

		// remove empty vars
		foreach ( $args as $_a => $_v ) {
			if ( is_array( $_v ) ) {
				if ( count( $_v ) === 0 ) {
					unset( $args[ $_a ] );
				}
			} else {
				if ( empty( $_v ) || $_v === 0 ) {
					unset( $args[ $_a ] );
				}
			}
		}

		// Remove extra vars
		unset( $args['suppress_filters'] );
		unset( $args['cache_results'] );
		unset( $args['update_post_term_cache'] );
		unset( $args['update_post_meta_cache'] );
		unset( $args['comments_per_page'] );
		unset( $args['no_found_rows'] );
		unset( $args['search_orderby_title'] );

		// create tax query
		if ( ! empty( $args['tax_query']['queries'] ) ) {
			$args['tax_query'] = $args['tax_query']['queries'];
		}

		return $args;

	} // bf_get_wp_query_vars
} // if


if ( ! function_exists( 'bf_get_wp_query_total_pages' ) ) {
	/**
	 * Calculates query total pages with support of offset and custom posts per page
	 *
	 * @param WP_Query $wp_query
	 * @param int      $offset
	 * @param int      $posts_per_page
	 * @param bool     $use_query_offset
	 *
	 * @return float|int
	 */
	function bf_get_wp_query_total_pages( &$wp_query, $offset = 0, $posts_per_page = 0, $use_query_offset = TRUE ) {

		$offset = intval( $offset );

		$posts_per_page = intval( $posts_per_page );
		if ( $posts_per_page <= 0 ) {
			$posts_per_page = $wp_query->get( 'posts_per_page' );
		}

		// use the query offset if it was set
		if ( $use_query_offset && $offset <= 0 ) {
			$offset = intval( $wp_query->get( 'offset' ) );
		}

		if ( $offset > 0 ) {
			$total = ceil( ( $wp_query->found_posts - $offset ) / $posts_per_page );
		} else {
			$total = $wp_query->max_num_pages;
		}

		return $total;
	}
}


if ( ! function_exists( 'bf_get_child_categories' ) ) {
	/**
	 * Gets category child or siblings if enabled
	 *
	 * @param null $term        Term object or ID
	 * @param int  $limit       Number of cats
	 * @param bool $or_siblings Return siblings if there is nor child
	 *
	 * @return array
	 */
	function bf_get_child_categories( $term = NULL, $limit = - 1, $or_siblings = FALSE ) {

		if ( ! $term ) {
			return array();
		} elseif ( ! is_object( $term ) ) {
			$term = get_term( $term, 'category' );
			if ( ! $term || is_wp_error( $term ) ) {
				return array();
			}
		} else {
			return array();
		}

		// fix limit number for get_categories
		if ( $limit === - 1 ) {
			$limit = 0;
		}

		$cat_args = array(
			'parent'     => $term->term_id,
			'hide_empty' => 0,
			'number'     => $limit === - 1 ? 0 : $limit
		);

		// Get child categories
		$child_categories = get_categories( $cat_args );

		// Get sibling cats if there is no child category
		if ( count( $child_categories ) == 0 && $or_siblings ) {
			$cat_args['parent'] = $term->parent;
			$child_categories   = get_categories( $cat_args );
		}

		return $child_categories;

	} // bf_get_wp_query_vars
} // if


if ( ! function_exists( 'bf_get_term_posts_count' ) ) {

	/**
	 * Returns count of all posts of category
	 *
	 * @param null  $term_id
	 * @param array $args
	 *
	 * @return int
	 */
	function bf_get_term_posts_count( $term_id = NULL, $args = array() ) {

		if ( is_null( $term_id ) ) {
			return 0;
		}

		$args = wp_parse_args( $args, array(
			'include_childs' => FALSE,
			'post_type'      => 'post',
			'taxonomy'       => 'category',
			'term_field'     => 'term_id',
		) );


		// simple term posts count using get_term, this will work quicker because of WP Cache
		// but this is not real post count, because this wouldn't count sub terms posts count in hierarchical taxonomies
		if ( ! is_taxonomy_hierarchical( $args['taxonomy'] ) || ! $args['include_childs'] ) {

			$term = get_term( get_queried_object()->term_id, $args['taxonomy'] );

			if ( ! is_wp_error( $term ) ) {
				return $term->count;
			} else {
				return 0;
			}

		} // Real term posts count in hierarchical taxonomies
		else {

			$query = new WP_Query( array(
				'post_type'      => $args['post_type'],
				'tax_query'      => array(
					array(
						'taxonomy' => $args['taxonomy'],
						'field'    => $args['term_field'],
						'terms'    => $term_id,
					),
				),
				'posts_per_page' => - 1
			) );

			return $query->post_count;

		}

	} // bf_get_term_posts_count
}
