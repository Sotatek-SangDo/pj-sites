<?php
/**
 * Functions for loading template parts.
 *
 * @package    BetterTemplate
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2015, BetterStudio
 */

if ( ! function_exists( 'better_get_content_template' ) ) {
	/**
	 * Loads a post content template based off the post type and/or the post format.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	function better_get_content_template() {

		// Set up an empty array and get the post type.
		$templates = array();
		$post_type = get_post_type();

		$style = better_get_style();

		if ( $style == 'default' ) {
			$style = 'general';
		} // fix for new structure

		// Template based on the format if post type supports the post format.
		if ( post_type_supports( $post_type, 'post-formats' ) ) {

			$post_format = get_post_format() ? get_post_format() : 'standard';

			// Template based off the post format.
			$templates[] = "views/{$style}/{$post_type}/content-{$post_format}.php";

			// Fallback to general template
			if ( $style != 'general' ) {
				$templates[] = "views/general/{$post_type}/content-{$post_format}.php";
			}

		}

		// Custom page template support
		if ( is_singular( 'page' ) && basename( get_page_template_slug() ) != '' ) {
			$templates[] = "views/{$style}/{$post_type}/template-" . basename( get_page_template_slug() );
		}

		$templates[] = "views/{$style}/{$post_type}/content-" . get_the_ID() . ".php";

		// Fallback to 'content.php' template.
		$templates[] = "views/{$style}/{$post_type}/content.php";

		if ( $style != 'general' ) {
			// Fallback to 'content.php' template.
			$templates[] = "views/general/{$post_type}/content.php";
		}

		if ( $post_type != 'post' ) {
			$templates[] = "views/{$style}/post/content.php";
		}

		if ( $style != 'general' ) {
			$templates[] = 'views/general/post/content.php';
		}


		// Allow developers to filter the content template hierarchy.
		$templates = apply_filters( 'better-template/content', $templates );

		// Return the found content template.
		include( locate_template( $templates, FALSE, FALSE ) );

	} // better_get_content_template
} // if


if ( ! function_exists( 'better_the_excerpt' ) ) {
	/**
	 * Used to get excerpt of post, Supports word truncation on custom excerpts
	 *
	 * @param   integer|null $length       Length of final excerpt words
	 * @param   string|null  $text         Excerpt manual text
	 * @param   bool         $echo         Echo or return
	 * @param   bool         $post_formats Exception handler to show full content of post formats
	 *
	 * @return string
	 */
	function better_the_excerpt( $length = 55, $text = NULL, $echo = TRUE, $post_formats = FALSE ) {

		// Post format exception to show post full content
		if ( $post_formats ) {

			if ( in_array( get_post_format(), array( 'quote', 'chat' ) ) ) {

				if ( $echo ) {
					the_content( '' );

					return;
				} else {
					ob_start();
					the_content( '' );

					return ob_get_clean();
				}

			}

		}

		// If text not defined get excerpt
		if ( ! $text ) {

			// Manual excerpt should be contracted or not?
			if ( is_null( $length ) ) {

				if ( $echo ) {

					echo apply_filters( 'the_excerpt', get_the_excerpt() );

					return;

				} else {

					return apply_filters( 'the_excerpt', get_the_excerpt() );

				}

			} else {
				$text = get_the_excerpt();
			}

		}

		// Remove shortcodes
		$text = strip_shortcodes( $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		// get plaintext excerpt trimmed to right length
		$excerpt = wp_trim_words( $text, $length, '&hellip;' );

		// fix extra spaces
		$excerpt = trim( str_replace( '&nbsp;', ' ', $excerpt ) );

		if ( $echo ) {
			echo wpautop( $excerpt );
		} else {
			return wpautop( $excerpt );
		}
	} // better_the_excerpt
} // if


if ( ! function_exists( 'better_the_content' ) ) {
	/**
	 * Used to get excerpt of post, Supports word truncation on custom excerpts
	 *
	 * @param   string $more_link_text Optional. Content for when there is more text.
	 * @param   bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
	 * @param   bool   $echo           Echo or return
	 *
	 * @return string
	 */
	function better_the_content( $more_link_text = NULL, $strip_teaser = FALSE, $echo = TRUE ) {

		// Post Links
		$post_links_attr = array(
			'before'   => '<div class="pagination bs-numbered-pagination" itemprop="pagination"><span class="span">' . ( function_exists( 'Better_Translation' ) ? Better_Translation()->_get( 'post_pages' ) : __( 'Post pages:', 'better-studio' ) ) . ' </span>',
			'after'    => '</div>',
			'echo'     => 0,
			'pagelink' => '<span>%</span>',
		);

		// Gallery post format
		if ( get_post_format() == 'gallery' ) {

			$content = get_the_content( $more_link_text, $strip_teaser );

			$content = better_strip_first_shortcode_gallery( $content );

			$content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );

			$content .= wp_link_pages( $post_links_attr );

		} // All Post Formats
		else {
			$content = apply_filters( 'the_content', get_the_content( $more_link_text, $strip_teaser ) ) . wp_link_pages( $post_links_attr );
		}

		if ( $echo ) {
			echo $content;
		} else {
			return $content;
		}
	} // better_the_content
} // if


if ( ! function_exists( 'better_get_related_posts_args' ) ) {
	/**
	 * Get Related Posts
	 *
	 * @param integer      $count number of posts to return
	 * @param string       $type
	 * @param integer|null $post_id
	 *
	 * @return WP_Query
	 */
	function better_get_related_posts_args( $count = 5, $type = 'cat', $post_id = NULL ) {

		global $post;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}

		$args = array(
			'posts_per_page' => $count,
			'post__not_in'   => array( $post_id )
		);

		switch ( $type ) {

			case 'cat':
				$args['category__in'] = wp_get_post_categories( $post_id );
				break;

			case 'tag':
				$args['tag__in'] = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				break;

			case 'author':
				$args['author'] = $post->post_author;
				break;

			case 'cat-tag':
				$args['category__in'] = wp_get_post_categories( $post_id );
				$args['tag__in']      = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				break;

			case 'cat-tag-author':
				$args['author']       = $post->post_author;
				$args['category__in'] = wp_get_post_categories( $post_id );
				$args['tag__in']      = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				break;

		}

		return apply_filters( 'better-template/related-posts/args', $args );

	} // better_get_related_posts_args
} // if


if ( ! function_exists( 'better_get_post_primary_cat' ) ) {
	/**
	 * Returns post main category object
	 *
	 * @param bool $fix_archive
	 *
	 * @return array|mixed|null|object|\WP_Error
	 */
	function better_get_post_primary_cat( $fix_archive = FALSE ) {

		// Fix for in category archive page and having multiple category
		if ( $fix_archive && is_category() ) {
			if ( has_category( get_query_var( 'cat' ) ) ) {
				$category = get_category( get_query_var( 'cat' ) );
			} else {
				$category = current( get_the_category() );
			}
		} // Primary category for singles
		else {

			// todo add YoastSEO and other plugins compatibility!
			$prim_cat = bf_get_post_meta( '_bs_primary_category', NULL, 'auto-detect' );

			if ( $prim_cat === 'auto-detect' ) {
				$category = current( get_the_category() );
			} else {
				$category = get_category( $prim_cat );
			}

		}

		return $category;
	} // better_get_post_primary_cat
} // if


if ( ! function_exists( 'better_cats_badge_code' ) ) {
	/**
	 * Handy function used to get post category badge
	 *
	 * @param   integer $cats_count Categories count, Default only primary or first cat
	 * @param   string  $sep        Separator for categories
	 * @param   bool    $show_format
	 * @param   bool    $echo       Echo or return
	 * @param   string  $class
	 *
	 * @return string
	 */
	function better_cats_badge_code( $cats_count = 1, $sep = '', $show_format = FALSE, $echo = TRUE, $class = '' ) {

		if ( get_post_type() == 'post' ) {

			$output = '<div class="term-badges ' . $class . '">'; // temp

			$cat_code = array(); // temp


			// Add post format icon
			if ( $show_format ) {

				$format = get_post_format();

				if ( $format ) {

					$cat_code[] = better_format_badge_code( FALSE );

				}

			} // show format if


			// All Cats
			if ( $cats_count == - 1 ) {

				$cats = get_the_category();

				$prim_cat = better_get_post_primary_cat();

				// Show prim cat at first
				if ( $prim_cat && ! is_wp_error( $prim_cat ) && has_category( $prim_cat ) ) {
					$prim_category = get_term( $prim_cat, 'category' );
					$cat_code[]    = '<span class="term-badge term-' . $prim_category->term_id . '"><a href="' . esc_url( get_category_link( $prim_category ) ) . '">' . esc_html( $prim_category->name ) . '</a></span>';
				}

				foreach ( $cats as $cat_id => $cat ) {

					// remove prim cat
					if ( $prim_cat && ! is_wp_error( $prim_cat ) && $cat->cat_ID === $prim_cat->cat_ID ) {
						continue;
					}

					$cat_code[] = '<span class="term-badge term-' . $cat->cat_ID . '"><a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></span>';
				}

			} // Specific Count
			elseif ( $cats_count > 1 ) {

				$cats = get_the_category();

				$prim_cat = better_get_post_primary_cat();

				// Show prim cat at first
				if ( $prim_cat && ! is_wp_error( $prim_cat ) && has_category( $prim_cat ) ) {
					$prim_category = get_term( $prim_cat, 'category' );
					$cat_code[]    = '<span class="term-badge term-' . $prim_category->term_id . '"><a href="' . esc_url( get_category_link( $prim_category ) ) . '">' . esc_html( $prim_category->name ) . '</a></span>';
					$cats_count -= 1;
				}

				$counter = 1;

				if ( $counter <= $cats_count && count( $cats ) > $cats_count ) {
					foreach ( $cats as $cat_id => $cat ) {

						if ( $counter > $cats_count ) {
							break;
						}

						if ( $prim_cat && ! is_wp_error( $prim_cat ) && $cat->cat_ID == $prim_cat->cat_ID ) {
							continue;
						}

						$cat_code[] = '<span class="term-badge term-' . $cat->cat_ID . '"><a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></span>';

						$counter ++;
					}
				}

			} // Only Primary Category
			elseif ( $cats_count == 1 ) {

				// Post primary category
				$prim_cat = better_get_post_primary_cat();

				if ( $prim_cat && ! is_wp_error( $prim_cat ) ) {
					$cat_code[] = '<span class="term-badge term-' . $prim_cat->cat_ID . '"><a href="' . esc_url( get_category_link( $prim_cat ) ) . '">' . esc_html( $prim_cat->name ) . '</a></span>';
				}

			}

			$output .= implode( $sep, $cat_code ) . '</div>';

			if ( $echo ) {
				echo $output;
			} else {
				return $output;
			}
		}

	} // better_cats_badge_code

} // if


if ( ! function_exists( 'better_format_badge_code' ) ) {
	/**
	 * Handy function used to get post format badge
	 *
	 * @param   bool $echo Echo or return
	 *
	 * @return string
	 */
	function better_format_badge_code( $echo = TRUE ) {

		$output = '';

		if ( get_post_type() == 'post' ) {

			$format = get_post_format();

			if ( $format ) {

				switch ( $format ) {

					case 'video':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-video-camera"></i> ' . Better_Translation()->_get( 'format_video' ) . '</a></span>';
						break;

					case 'aside':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-pencil"></i> ' . Better_Translation()->_get( 'format_aside' ) . '</a></span>';
						break;

					case 'quote':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-quote-left"></i> ' . Better_Translation()->_get( 'format_quote' ) . '</a></span>';
						break;

					case 'gallery':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-camera"></i> ' . Better_Translation()->_get( 'format_gallery' ) . '</a></span>';
						break;

					case 'image':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-camera"></i> ' . Better_Translation()->_get( 'format_image' ) . '</a></span>';
						break;

					case 'status':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-refresh"></i> ' . Better_Translation()->_get( 'format_status' ) . '</a></span>';
						break;

					case 'audio':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-music"></i> ' . Better_Translation()->_get( 'format_music' ) . '</a></span>';
						break;

					case 'chat':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-coffee"></i> ' . Better_Translation()->_get( 'format_chat' ) . '</a></span>';
						break;

					case 'link':
						$output = '<span class="format-badge format-' . $format . '"><a href="' . get_post_format_link( $format ) . '"><i class="fa fa-link"></i> ' . Better_Translation()->_get( 'format_link' ) . '</a></span>';
						break;

				}

			}

		}

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}

	} // better_format_badge_code
} // if


add_filter( 'human_time_diff', 'better_human_time_diff_filter', 99, 2 );

if ( ! function_exists( 'better_human_time_diff_filter' ) ) {
	/**
	 * Function to get readable time of current post with translation panel support
	 *
	 * @param $since
	 * @param $diff
	 *
	 * @return string
	 */
	function better_human_time_diff_filter( $since, $diff ) {

		/**
		 * Todo: Replace following IF's with the _n function -> this is another shit!
		 * but currently we don't this because of following bug in theme-check Plugin
		 * https://github.com/WordPress/theme-check/issues/180
		 */
		if ( $diff < HOUR_IN_SECONDS ) {

			$mins = round( $diff / MINUTE_IN_SECONDS );

			if ( $mins <= 1 ) {
				$mins = 1;
			}

			if( intval( $mins ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_mins' ), $mins );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_min' ), $mins);
			}

		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {

			$hours = round( $diff / HOUR_IN_SECONDS );

			if ( $hours <= 1 ) {
				$hours = 1;
			}

			if( intval( $hours ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_hours' ), $hours );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_hour' ), $hours);
			}

		} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {

			$days = round( $diff / DAY_IN_SECONDS );

			if ( $days <= 1 ) {
				$days = 1;
			}

			if( intval( $days ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_days' ), $days );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_day' ), $days);
			}

		} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {

			$weeks = round( $diff / WEEK_IN_SECONDS );

			if ( $weeks <= 1 ) {
				$weeks = 1;
			}

			if( intval( $weeks ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_weeks' ), $weeks );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_week' ), $weeks);
			}

		} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {

			$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );

			if ( $months <= 1 ) {
				$months = 1;
			}

			if( intval( $months ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_months' ), $months );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_month' ), $months);
			}

		} elseif ( $diff >= YEAR_IN_SECONDS ) {

			$years = round( $diff / YEAR_IN_SECONDS );

			if ( $years <= 1 ) {
				$years = 1;
			}

			if( intval( $years ) > 1 ){
				$since = sprintf( Better_Translation()->_get( 'readable_time_years' ), $years );
			}else{
				$since = sprintf( Better_Translation()->_get( 'readable_time_year' ), $years);
			}

		}

		return $since;
	} // better_human_time_diff_filter

} // if


if ( ! function_exists( 'better_get_readable_date' ) ) {
	/**
	 * Used to get readable time
	 *
	 * @param null $date
	 *
	 * @return string
	 */
	function better_get_readable_date( $date = NULL ) {

		if ( is_null( $date ) ) {
			$date = get_the_time( 'U' );
		}

		return sprintf( Better_Translation()->_get( 'readable_time_ago' ), human_time_diff( $date ) );
	} // better_get_readable_date
} // if


if ( ! function_exists( 'better_get_first_gallery_ids' ) ) {
	/**
	 * Used For Retrieving Post First Gallery and Return Attachment IDs
	 *
	 * @param null $content
	 *
	 * @return array|bool
	 */
	function better_get_first_gallery_ids( $content = NULL ) {

		// when current not defined
		if ( ! $content ) {
			global $post;

			$content = $post->post_content;
		}

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

		if ( ! empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				if ( 'gallery' === $shortcode[2] ) {

					$atts = shortcode_parse_atts( $shortcode[3] );

					if ( ! empty( $atts['ids'] ) ) {
						$ids = explode( ',', $atts['ids'] );

						return $ids;
					}
				}
			}
		}

		return FALSE;
	} // better_get_first_gallery_ids
} // if


if ( ! function_exists( 'better_archive_total_badge_code' ) ) {
	/**
	 * prints archive pages badges
	 */
	function better_archive_total_badge_code( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'wrapper_before' => '<div class="archive-badges term-badges">',
			'wrapper_after'  => '</div>',
			'before'         => '<span class="archive-badge term-badge">',
			'after'          => '</span>',
			'limit'          => '',
		) );

		// calculate limits and type
		if ( is_year() ) {

			if ( empty( $args['limit'] ) ) {
				$args['limit'] = 10;
			}

			$type = 'yearly';

		} elseif ( is_month() ) {

			if ( empty( $args['limit'] ) ) {
				$args['limit'] = 7;
			}

			$type = 'monthly';

		} elseif ( is_day() ) {

			if ( empty( $args['limit'] ) ) {
				$args['limit'] = 5;
			}

			$type = 'daily';

		} else {
			return;
		}


		echo $args['wrapper_before'];

		wp_get_archives( array(
			'limit'  => $args['limit'],
			'type'   => $type,
			'format' => 'anchor',
			'before' => $args['before'],
			'after'  => $args['after'],
		) );

		echo $args['wrapper_after'];

	}
} // if


if ( ! function_exists( 'better_strip_first_shortcode_gallery' ) ) {
	/**
	 * Deletes First Gallery Shortcode and Returns Content
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	function better_strip_first_shortcode_gallery( $content = '' ) {

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

		if ( ! empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				if ( $shortcode[2] === 'gallery' ) {

					$pos = strpos( $content, $shortcode[0] );

					if ( $pos !== FALSE ) {
						return substr_replace( $content, '', $pos, strlen( $shortcode[0] ) );
					}
				}
			}
		}

		return $content;

	} // better_strip_first_shortcode_gallery
} // if


if ( ! function_exists( 'better_get_post_thumbnail_src' ) ) {
	/**
	 * Handy function to get post thumbnail src
	 *
	 * @param string $size    Featured image size
	 *
	 * @param null   $post_id Post ID
	 * @param null   $thumbnail_id
	 *
	 * @return string
	 */
	function better_get_post_thumbnail_src( $size = 'thumbnail', $post_id = NULL, $thumbnail_id = NULL ) {

		$post_id = ( NULL === $post_id ) ? get_the_ID() : $post_id;

		if ( is_null( $thumbnail_id ) ) {
			$thumbnail_id = get_post_thumbnail_id( $post_id );
		}

		$attachment = wp_get_attachment_image_src( $thumbnail_id, $size );

		return $attachment[0];
	} // better_get_post_thumbnail_src
} // if


if ( ! function_exists( 'better_get_media_src' ) ) {
	/**
	 * Handy function to media src
	 *
	 * @param null   $media_id
	 * @param string $size Featured image size
	 *
	 * @return string
	 */
	function better_get_media_src( $media_id = NULL, $size = 'thumbnail' ) {

		$media_id = ( NULL === $media_id ) ? get_the_ID() : $media_id;

		if ( is_null( $media_id ) ) {
			return better_get_post_thumbnail_src( $size ); // return current post thumbnail src for size!
		}

		$attachment = wp_get_attachment_image_src( $media_id, $size );

		return $attachment[0];

	} // better_get_media_src
} // if


if ( ! function_exists( 'better_has_tag' ) ) {
	/**
	 * Wrapper function for WP has_tag for enable it to be customized from theme or child theme
	 *
	 * @return string
	 */
	function better_has_tag() {
		return has_tag();
	}
}


if ( ! function_exists( 'better_has_category' ) ) {
	/**
	 * Wrapper function for WP has_category for enable it to be customized from theme or child theme
	 *
	 * @return string
	 */
	function better_has_category() {
		return has_category();
	}
}


if ( ! function_exists( 'better_is_thumbnail_placeholder_active' ) ) {
	/**
	 * Handy function used to check thumbnails placeholder is active or not!
	 * This can be override in theme for more functionality
	 *
	 * @return bool
	 */
	function better_is_thumbnail_placeholder_active() {
		return (bool) better_get_option( 'bsbt_thumbnail_placeholder', FALSE );
	}
}


if ( ! function_exists( 'better_has_post_thumbnail' ) ) {
	/**
	 * Handy function fo checking to post have post thumbnail or not
	 *
	 * @param null $post_id
	 *
	 * @return bool
	 */
	function better_has_post_thumbnail( $post_id = NULL ) {

		if ( is_null( $post_id ) ) {
			$post    = get_post();
			$post_id = $post->ID;
		}

		if ( has_post_thumbnail( $post_id ) ) {
			return TRUE;
		}

		return (bool) better_is_thumbnail_placeholder_active();

	}
}


if ( ! function_exists( 'better_get_thumbnail' ) ) {
	/**
	 * Used to get thumbnail image for posts with support of default thumbnail image
	 *
	 * @param string $thumbnail_size
	 * @param null   $post_id
	 *
	 * @return string
	 */
	function better_get_thumbnail( $thumbnail_size = 'thumbnail', $post_id = NULL ) {

		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$thumbnail_id = get_post_thumbnail_id( $post_id );

		$img = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );

		if ( $img ) {
			return array(
				'src'    => $img[0],
				'width'  => $img[1],
				'height' => $img[2],
			);
		}

		$img = array(
			'src'    => '',
			'width'  => '',
			'height' => '',
		);

		if ( ! better_is_thumbnail_placeholder_active() ) {
			return $img;
		}

		global $_wp_additional_image_sizes;

		if ( isset( $_wp_additional_image_sizes[ $thumbnail_size ] ) ) {
			$img['width']  = $_wp_additional_image_sizes[ $thumbnail_size ]['width'];
			$img['height'] = $_wp_additional_image_sizes[ $thumbnail_size ]['height'];
		}

		// Default image from panel
		if ( better_get_option( 'bsbt_default_thumbnail' ) ) {
			$img['src'] = better_get_post_thumbnail_src( $thumbnail_size, $post_id, better_get_option( 'bsbt_default_thumbnail' ) );
			if ( ! empty( $img['src'] ) ) // check for valid size!
			{
				return $img;
			}
		}

		$img['src'] = BS_THEME_URI . 'images/default-thumb/' . $thumbnail_size . '.png';

		return $img;

	} // better_get_thumbnail
} // if
