<?php

//
//
// Post specified attributes
//
//
add_filter( 'better_attr_post',                 'better_attr_post',                 5, 3 );
add_filter( 'better_attr_post-title',           'better_attr_post_title',           5, 3 );
add_filter( 'better_attr_post-url',             'better_attr_post_url',             5, 3 );
add_filter( 'better_attr_post-thumbnail-url',   'better_attr_post_thumbnail_url',   5, 3 );
add_filter( 'better_attr_post-content',         'better_attr_post_content',         5, 3 );
add_filter( 'better_attr_post-summary',         'better_attr_post_summary',         5, 3 );
add_filter( 'better_attr_post-excerpt',         'better_attr_post_summary',         5, 3 ); // Alias for summary
add_filter( 'better_attr_post-lead',            'better_attr_post_summary',         5, 3 ); // Alias for summary
add_filter( 'better_attr_post-terms',           'better_attr_post_terms',           5, 3 );


//
//
// Post meta attributes + Author meta attributes
//
//
add_filter( 'better_attr_post-meta',                    'better_attr_post_meta',                5, 3 );
add_filter( 'better_attr_post-meta-author',             'better_attr_post_meta_author',         5, 3 );
add_filter( 'better_attr_author',                       'better_attr_post_meta_author',         5, 3 );
add_filter( 'better_attr_post-meta-author-url',         'better_attr_post_meta_author_url',     5, 3 );
add_filter( 'better_attr_author-url',                   'better_attr_post_meta_author_url',     5, 3 );
add_filter( 'better_attr_post-meta-author-name',        'better_attr_post_meta_author_name',    5, 3 );
add_filter( 'better_attr_author-name',                  'better_attr_post_meta_author_name',    5, 3 );
add_filter( 'better_attr_post-meta-author-bio',         'better_attr_post_meta_author_bio',     5, 3 );
add_filter( 'better_attr_author-bio',                   'better_attr_post_meta_author_bio',     5, 3 );
add_filter( 'better_attr_post-meta-author-avatar',      'better_attr_post_meta_author_avatar',  5, 3 );
add_filter( 'better_attr_author-avatar',                'better_attr_post_meta_author_avatar',  5, 3 );
add_filter( 'better_attr_post-meta-published',          'better_attr_post_meta_published',      5, 3 );
add_filter( 'better_attr_post-meta-comments',           'better_attr_post_meta_comments',       5, 3 );
add_filter( 'better_attr_post-meta-views',              'better_attr_post_meta_views',          5, 3 );


if( ! function_exists( 'better_attr_post' ) ){
    /**
     * Post/Page <article> element attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post( $attr, $class = '', $context = '' ){

        $post = get_post();

        if( ! empty( $post ) ){

	        // to be valid in W3 validator
	        if ( is_single( get_the_ID() ) && better_is_main_query() ) {
		        $attr['id'] = 'post-' . get_the_ID();
	        }

	        if( is_single( get_the_ID() ) ){
		        $attr['class'] = join( ' ', array_diff( get_post_class(), array( 'hentry' ) ) );  // Remove hentry
	        }else{

		        $classes = array();
		        $classes[] = 'post-' . $post->ID;
		        $classes[] = 'type-' . $post->post_type;

		        // Post Format
		        if ( post_type_supports( $post->post_type, 'post-formats' ) ) {
			        $post_format = get_post_format( $post->ID );

			        if ( $post_format && !is_wp_error($post_format) )
				        $classes[] = 'format-' . sanitize_html_class( $post_format );
			        else
				        $classes[] = 'format-standard';
		        }

		        if( ! is_attachment( $post ) && current_theme_supports( 'post-thumbnails' ) && better_has_post_thumbnail( $post->ID ) ) {
			        $classes[] = 'has-post-thumbnail';
		        }else{
                    $classes[] = 'has-not-post-thumbnail';
                }

		        $attr['class'] = join( ' ', $classes );
		        unset( $classes );

	        }

            $attr['itemscope'] = 'itemscope';

            // Post post type
            if( 'post' === get_post_type() ) {

                $format = get_post_format();

                switch( $format ){

                    case 'audio':
                        $attr['itemtype'] = 'http://schema.org/AudioObject';
                        break;

                    case 'video':
                        $attr['itemtype'] = 'http://schema.org/VideoObject';
                        break;

                    case 'image':
                    case 'gallery':
                        $attr['itemtype'] = 'http://schema.org/ImageObject';
                        break;

                    default:
                        $attr['itemtype']  = 'http://schema.org/Article';
                }

            }

            // Image attachment
            elseif( 'attachment' === get_post_type() && wp_attachment_is_image() ) {
                $attr['itemtype'] = 'http://schema.org/ImageObject';
            }

            // Audio attachment
            elseif( 'attachment' === get_post_type() && wp_attachment_is( 'audio' ) ) {
                $attr['itemtype'] = 'http://schema.org/AudioObject';
            }

            // Video attachment
            elseif( 'attachment' === get_post_type() && wp_attachment_is( 'video' ) ) {
                $attr['itemtype'] = 'http://schema.org/VideoObject';
            }

            else{
                $attr['itemtype']  = 'http://schema.org/CreativeWork';
            }

        }else{
            //        $attr['id']    = 'post-0';
            $attr['class'] = join( ' ', array_diff( get_post_class(), array( 'hentry' ) ) );  // Remove hentry
        }

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }


        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_title' ) ){
    /**
     * Post title attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_title( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-title';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'headline';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_url' ) ){
    /**
     * Post title attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_url( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-url';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'url';

        $attr['rel'] = 'bookmark';

        // Link post format should point to first a in content
        if( has_post_format( 'link' ) ){

            $has_url = get_url_in_content( get_the_content() );

            if( $has_url ){
                $attr['href'] = $has_url;
            }
            // Link fallback
            else{
                $attr['href'] = apply_filters( 'the_permalink', get_permalink() );
            }

        }else{
            $attr['href'] = apply_filters( 'the_permalink', get_permalink() );
        }

        $attr['title'] = the_title_attribute( array( 'echo' => false ) );

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_thumbnail_url' ) ){
    /**
     * Post title attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Custom image size as anchor URL or "post-url" to set post URL
     *
     * @return  array
     */
    function better_attr_post_thumbnail_url( $attr, $class = '', $context = 'post-url' ){

        $attr['class'] = 'post-thumbnail';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'thumbnailUrl';

        // Post URL
        if( $context == 'post-url' || $context == '' ){
            $attr['href'] = get_the_permalink();
        }

        // Custom image size url, ex: full
        else{
            $img = wp_get_attachment_image_src( get_post_thumbnail_id(), $context );
            $attr['href'] = $img[0];
        }

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_content' ) ){
    /**
     * Post full content attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_content( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'entry-content';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        // todo add post format and type support
        if ( 'post' === get_post_type() )
            $attr['itemprop'] = 'articleBody';
        else
            $attr['itemprop'] = 'text';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_summary' ) ){
    /**
     * Post summary/excerpt attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_summary( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-summary';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'about';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_terms' ) ){
    /**
     * Post terms (tags, categories, etc.) attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_terms( $attr, $class = '', $context = '' ){

        $attr['class']    = 'entry-terms';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        if( 'category' === $context )
            $attr['itemprop'] = 'articleSection';

        elseif( 'post_tag' === $context )
            $attr['itemprop'] = 'keywords';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-meta';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_author' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_author( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-author';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'author';

        $attr['itemscope'] = 'itemscope';

        $attr['itemtype'] = 'http://schema.org/Person';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_author_url' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $url        Custom user URL
     *
     * @return  array
     */
    function better_attr_post_meta_author_url( $attr, $class = '', $url = '' ){

        $attr['class'] = 'post-author-url';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop']  = 'url';

        if( $url == '' )
            $attr['href'] = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
        else{
            $attr['href'] = esc_url( $url );
        }

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_author_name' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_author_name( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-author-name';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'givenName';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_author_bio' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_author_bio( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-author-bio';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'description';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_author_avatar' ) ){
    /**
     * Post author attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_author_avatar( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-author-avatar';

        if( ! empty( $class ) ){
            if( isset( $attr['class'] ) ){
                $attr['class'] .= ' ' . $class;
            }else{
                $attr['class'] = $class;
            }
        }

        $attr['itemprop'] = 'image';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_published' ) ){
    /**
     * Post time/published attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_published( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'post-published updated';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        global $post;

        // datetime should be raw and valid english time to be valid in W3.or validator
        $attr['datetime'] = mysql2date( 'Y-m-d\TH:i:sP', $post->post_date, false);

        $attr['title']    = get_the_time( 'l, F j, Y, g:i a' );

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_comments' ) ){
    /**
     * Post time/published attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_comments( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'comments';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'interactionCount';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_post_meta_views' ) ){
    /**
     * Post time/published attributes.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $attr       Default or filtered attributes
     * @param   string  $class      Extra classes
     * @param   string  $context    Specific context ex: primary
     *
     * @return  array
     */
    function better_attr_post_meta_views( $attr, $class = '', $context = '' ) {

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        $attr['class'] = 'views';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['itemprop'] = 'interactionCount';

        return $attr;
    }
}