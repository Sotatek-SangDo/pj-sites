<?php

new BSBA_Social_Meta_Tag_Generator ();

/**
 * Better Meta Tag generator
 *
 *
 * @package  BetterStudio Social Meta Tag Generator
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.1.0
 * @access   public
 * @see      http://www.betterstudio.com/
 */
class BSBA_Social_Meta_Tag_Generator {


	/**
	 * Includes overall attributes that should be added to the language attributes
	 *
	 * @since  1.0.0
	 *
	 * @var array
	 */
	private $doctypes = array();


	/**
	 * Contains list of OG tags that should be added in header
	 *
	 * @since  1.0.0
	 *
	 * @var array
	 */
	private $tags = array();


	/**
	 * Contains options than can be used in generators
	 *
	 * @since  1.0.0
	 *
	 * @var array
	 */
	private $args = array();


	function __construct( $args = array() ) {

		$this->args = wp_parse_args( $args, array(
			'img' => ''
		) );

		if ( is_admin() ) {
			return;
		}

		add_filter( 'language_attributes', array( $this, 'filter_language_attributes' ) );

		add_action( 'wp_head', array( $this, 'print_header_tags' ), 1 );

		if ( ! $this->is_seo_plugin_active() ) {
			add_filter( 'wp_title', array( $this, 'wp_title' ), 1, 3 );
		}

	}


	/**
	 * Detects there is any active seo plugin
	 *
	 * @since  1.1.0
	 *
	 * @return bool
	 */
	function is_seo_plugin_active() {

		if ( defined( 'WPSEO_VERSION' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * Detects the facebook plugin is active
	 *
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	function is_facebook_plugin_active() {

		if ( class_exists( 'Facebook_Loader' ) ) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * Adds custom attributes to base language attributes
	 *
	 * @since  1.0.0
	 *
	 * @param $output
	 *
	 * @return string
	 */
	function filter_language_attributes( $output ) {

		return $output . ' ' . implode( ' ', $this->doctypes );

	}


	/**
	 * Adds new language attribute to defaults
	 *
	 * @since  1.0.0
	 *
	 * @param $attribute
	 */
	function add_language_attribute( $attribute ) {

		$this->doctypes[] = $attribute;

	}


	/**
	 * Used for adding element to Open Graph tags
	 *
	 * @since  1.0.0
	 *
	 * @param        $id
	 * @param        $value
	 * @param string $type
	 */
	function add_header_tag( $id, $value, $type = 'og' ) {

		$this->tags[] = array(
			'id'    => $id,
			'value' => $value,
			'type'  => $type
		);

	}

	/**
	 * Prints meta tags
	 *
	 * @since  1.0.0
	 */
	function print_header_tags() {


		if ( ! $this->is_seo_plugin_active() ) {


			// Locale
			$this->add_header_tag( 'og:lcoale', esc_attr( strtolower( get_locale() ) ) );

			// Title
			$this->add_header_tag( 'og:title', esc_attr( get_the_title() ) );
			$this->add_header_tag( 'name', esc_attr( get_the_title() ), 'itemprop' );
			$this->add_header_tag( 'twitter:title', esc_attr( get_the_title() ) );

			// Type
			$this->add_header_tag( 'og:type', $this->get_the_type() );

			// URL
			$this->add_header_tag( 'og:url', esc_attr( $this->get_url() ) );
			$this->add_header_tag( 'twitter:url', esc_attr( $this->get_url() ) );

			// Site Name
			$this->add_header_tag( 'og:site_name', esc_attr( get_bloginfo( 'name' ) ) );

			// Twitter
			$this->add_header_tag( 'twitter:card', 'summary' );

			// Singulars: Posts and Pages
			if ( is_singular() && ! ( is_front_page() || is_home() ) ) {

				// Description
				$desc = better_the_excerpt( 24, NULL, FALSE );
				$desc = strip_shortcodes( $desc );
				$desc = strip_tags( $desc );
				$desc = esc_attr( $desc );

				if ( ! $this->is_seo_plugin_active() ) {
					$this->add_header_tag( 'og:description', $desc );
					$this->add_header_tag( 'description', $desc, 'itemprop' );
				}


				// Image
				if ( has_post_thumbnail( get_the_ID() ) ) {

					$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );

					if ( ! $this->is_seo_plugin_active() ) {
						$this->add_header_tag( 'og:image', esc_attr( $thumbnail_src[0] ) );
						$this->add_header_tag( 'image', esc_attr( $thumbnail_src[0] ), 'itemprop' );
					}
					$this->add_header_tag( 'twitter:image', esc_attr( $thumbnail_src[0] ) );

				} elseif ( ! empty( $args['image'] ) ) {

					if ( ! $this->is_seo_plugin_active() ) {
						$this->add_header_tag( 'og:image', esc_attr( $args['image'] ) );
						$this->add_header_tag( 'image', esc_attr( $args['image'] ), 'itemprop' );
					}
					$this->add_header_tag( 'twitter:image', esc_attr( $args['image'] ) );

				}

			}

		} else {

			// Title
			$this->add_header_tag( 'twitter:title', esc_attr( get_the_title() ) );

			// URL
			$this->add_header_tag( 'twitter:url', esc_attr( $this->get_url() ) );

			// Twitter
			$this->add_header_tag( 'twitter:card', 'summary' );


			// Singulars: Posts and Pages
			if ( is_singular() && ! ( is_front_page() || is_home() ) ) {

				// Description
				$desc = better_the_excerpt( 24, NULL, FALSE );
				$desc = strip_shortcodes( $desc );
				$desc = strip_tags( $desc );
				$desc = esc_attr( $desc );
				$this->add_header_tag( 'twitter:description', $desc );

				// Image
				if ( has_post_thumbnail( get_the_ID() ) ) {

					$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
					$this->add_header_tag( 'twitter:image', esc_attr( $thumbnail_src[0] ) );

				} elseif ( ! empty( $args['image'] ) ) {

					$this->add_header_tag( 'twitter:image', esc_attr( $args['image'] ) );

				}
			}

		}

		if ( count( $this->tags ) == 0 ) {
			return;
		}

		echo "\n<!-- Better Open Graph, Schema.org & Twitter Integration -->\n";

		foreach ( (array) $this->tags as $tag ) {

			switch ( $tag['type'] ) {

				case 'og':
					echo '<meta property="' . $tag['id'] . '" content="' . $tag['value'] . '"/>' . "\n";
					break;

				case 'itemprop':
					echo '<meta itemprop="' . $tag['id'] . '" content="' . $tag['value'] . '"/>' . "\n";
					break;

			}

		}

		echo "<!-- / Better Open Graph, Schema.org & Twitter Integration. -->\n";
	}


	/**
	 * Used for finding current page type
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function get_the_type() {

		if ( is_front_page() || is_home() ) {

			return 'website';

		} elseif ( is_singular() ) {

			return 'article';

		}

		// "object" used for archives etc. because article doesn't apply there
		return 'object';

	}


	/**
	 * Used for finding current page link
	 *
	 * @since  1.0.0
	 *
	 * @return bool|string|void|WP_Error
	 */
	function get_url() {

		if ( is_front_page() || is_home() ) {

			$canonical = home_url();

		} elseif ( is_singular() ) {

			$canonical = get_the_permalink();

		} elseif ( is_search() ) {

			$canonical = get_search_link();

		} elseif ( is_tax() || is_tag() || is_category() ) {
			$term = get_queried_object();

			$canonical = get_term_link( $term, $term->taxonomy );

		} elseif ( is_post_type_archive() ) {

			$post_type = get_query_var( 'post_type' );

			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}

			$canonical = get_post_type_archive_link( $post_type );

		} elseif ( is_author() ) {
			$canonical = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
		} elseif ( is_archive() ) {

			if ( is_date() ) {

				if ( is_day() ) {
					$canonical = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
				} elseif ( is_month() ) {
					$canonical = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
				} elseif ( is_year() ) {
					$canonical = get_year_link( get_query_var( 'year' ) );
				}
			}
		} elseif ( is_404() ) {
			$canonical = get_home_url() . $_SERVER['REQUEST_URI'];
		}

		return $canonical;

	}


	/**
	 * Callback: Filters the `wp_title` output early.
	 *
	 * Filter: wp_title
	 *
	 * @since  1.1.0
	 * @access publc
	 *
	 * @param  string $title
	 * @param  string $separator
	 * @param  string $seplocation
	 *
	 * @return string
	 */
	function wp_title( $title, $separator = '|', $seplocation ) {

		if ( is_front_page() ) {
			$title = get_bloginfo( 'name' ) . $separator . ' ' . get_bloginfo( 'description' );
		} elseif ( is_home() || is_singular() ) {
			$title = single_post_title( '', FALSE );
		} elseif ( is_category() ) {
			$title = single_cat_title( '', FALSE );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', FALSE );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', FALSE );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', FALSE );
		} elseif ( is_author() ) {
			$title = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		} elseif ( get_query_var( 'minute' ) && get_query_var( 'hour' ) ) {
			$title = get_the_time( _x( 'g:i a', 'minute and hour archives time format', 'better-studio' ) );
		} elseif ( get_query_var( 'minute' ) ) {
			$title = sprintf( __( 'Minute %s', 'better-studio' ), get_the_time( 'i' ) );
		} elseif ( get_query_var( 'hour' ) ) {
			$title = get_the_time( _x( 'g a', 'hour archives time format', 'better-studio' ) );
		} elseif ( is_day() ) {
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'better-studio' ) );
		} elseif ( get_query_var( 'w' ) ) {
			$title = sprintf( __( 'Week %1$s of %2$s', 'better-studio' ), get_the_time( _x( 'W', 'weekly archives date format', 'better-studio' ) ), get_the_time( _x( 'Y', 'yearly archives date format', 'better-studio' ) ) );
		} elseif ( is_month() ) {
			$title = single_month_title( ' ', FALSE );
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'better-studio' ) );
		} elseif ( is_archive() ) {
			$title = __( 'Archives', 'better-studio' );
		} elseif ( is_search() ) {
			$title = sprintf( __( 'Search results for &#8220;%s&#8221;', 'better-studio' ), get_search_query() );
		} elseif ( is_404() ) {
			$title = __( '404 Not Found', 'better-studio' );
		}

		return trim( strip_tags( $title ), "{$separator} " );

	} // wp_title

} // BSBA_Social_Meta_Tag_Generator