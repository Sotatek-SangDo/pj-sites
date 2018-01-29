<?php


/**
 * Contain Functionality Related To Posts and Pages
 */
class BM_Posts{


    /**
     * Contain instance of WP_Query that used in blocks and listings
     *
     * @var WP_Query
     */
    static private $query = null;


    /**
     * Setter for query
     *
     * @param \WP_Query $query
     */
    public static function set_query( &$query ){
        self::$query = &$query;
    }


    /**
     * Getter for query
     *
     * @return \WP_Query
     */
    public static function get_query(){

        if( ! is_a( self::$query, 'WP_Query' ) ){
            global $wp_query;

            self::$query = &$wp_query;
        }

        return self::$query;
    }


    /**
     * Used for clearing query
     *
     * @param bool $reset_query
     */
    public static function clear_query( $reset_query = true ){

        self::$query = null;

        // This will remove obscure bugs that occur when the previous wp_query object is not destroyed properly before another is set up.
        if( $reset_query )
            wp_reset_query();
    }


    /**
     * Check if post has an image attached.
     *
     * @return bool
     */
    public function has_post_thumbnail(){
        return has_post_thumbnail();
    }

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


    /**
     * Deletes First Gallery Shortcode and Returns Content
     */
    public function _strip_shortcode_gallery( $content ){

        preg_match_all('/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER);

        if (!empty($matches)){

            foreach ($matches as $shortcode){

                if ( $shortcode[2] === 'gallery' ){

                    $pos = strpos($content, $shortcode[0]);

                    if ($pos !== false) {
                        return substr_replace($content, '', $pos, strlen($shortcode[0]));
                    }
                }
            }
        }

        return $content;
    }


    /**
     * Used For Retrieving Post First Gallery and Return Attachment IDs
     *
     * @param null $content
     * @return array|bool
     */
    public function get_first_gallery_ids( $content = null ){

        // whn current not defined
        if( ! $content ){
            global $post;

            $content = $post->post_content;
        }

        preg_match_all('/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER);

        if( ! empty($matches) ){

            foreach( $matches as $shortcode ){

                if( 'gallery' === $shortcode[2] ){

                    $atts = shortcode_parse_atts($shortcode[3]);

                    if (!empty($atts['ids'])) {
                        $ids = explode(',', $atts['ids']);

                        return $ids;
                    }
                }
            }
        }

        return false;
    }


    /**
     * Get Related Posts
     *
     * @param integer $count number of posts to return
     * @param string $type
     * @param integer|null $post_id
     * @return WP_Query
     */
    public function get_related( $count = 5, $type = 'cat', $post_id = null ){

        if( ! $post_id ){

            global $post;

            $post_id = $post->ID;

        }

        $args = array(
            'posts_per_page' => $count,
            'post__not_in' => array( $post_id )
        );

        switch( $type ){

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
                $args['tag__in'] = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
                break;

            case 'cat-tag-author':
                $args['author'] = $post->post_author;
                $args['category__in'] = wp_get_post_categories( $post_id );
                $args['tag__in'] = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
                break;

        }

        $related = new WP_Query( apply_filters( 'better-framework/posts/related/args', $args ) );

        return $related;

    }


    /**
     * Used for retrieving next post link
     * Note: if there is no next post then a random post will be returned.
     *
     * @param array $args
     *
     * @return string
     *
     */
    static function next_post_link( $args = array() ){

        $args = wp_parse_args( $args, array(
            'format'            =>  '%link &raquo;',
            'link'              =>  '%title',
            'random-adjust'     =>  false,
            'in-same-term'      =>  false,
            'taxonomy'          =>  'category',
            'exclude-terms'     =>  '',
        ));

        $next_post = get_next_post_link( $args['format'], $args['link'], $args['in-same-term'], $args['exclude-terms'], $args['taxonomy'] );

        // If there is next post
        if( $next_post != '' || $args['random-adjust'] == false ){
            echo $next_post;
            return '';
        }

        // There is no next post. a random post should be shown
        $prev_post = get_adjacent_post( $args['in-same-term'], $args['exclude-terms'], true, $args['taxonomy'] );

        $excluded_posts = array();

        // exclude prev post
        if( $prev_post ){
            $excluded_posts[] = $prev_post->ID;
        }

        // exclude current post
        $excluded_posts[] = get_the_ID();

        $random_query = new WP_Query(
            array(
                'orderby'           => 'rand',
                'posts_per_page'    => '1',
                'post__not_in'      => $excluded_posts,
                'post_type'         => get_post_type(),
            )
        );

        // No another post!
        if( $random_query->post_count == 0 )
            return '';

        $title = $random_query->post->post_title;

        if( empty( $random_query->post->post_title ) )
            $title = __( 'Next Post', 'better-studio' );

        $title = apply_filters( 'the_title', $title, $random_query->post->ID );

        $date = mysql2date( get_option( 'date_format' ), $random_query->post->post_date );
        $rel = 'next';

        $string = '<a href="' . get_permalink( $random_query->post ) . '" rel="'.$rel.'">';
        $inlink = str_replace( '%title', $title, $args['link'] );
        $inlink = str_replace( '%date', $date, $inlink );
        $inlink = $string . $inlink . '</a>';

        echo str_replace( '%link', $inlink, $args['format'] );

    }


    /**
     * Used for retrieving previous post link
     * Note: if there is no previous post then a random post will be returned.
     *
     * @param array $args
     *
     * @return string
     */
    static function previous_post_link( $args = array() ){

        $args = wp_parse_args( $args, array(
            'format'            =>  '%link &raquo;',
            'link'              =>  '%title',
            'random-adjust'     =>  false,
            'in-same-term'      =>  false,
            'taxonomy'          =>  'category',
            'exclude-terms'     =>  '',
        ));

        $previous_post = get_previous_post_link( $args['format'], $args['link'], $args['in-same-term'], $args['exclude-terms'], $args['taxonomy'] );

        // If there is next post
        if( $previous_post != '' ){
            echo $previous_post;
            return '';
        }

        // There is no prev post. a random post should be shown
        $next_post = get_adjacent_post( $args['in-same-term'], $args['exclude-terms'], false, $args['taxonomy'] );

        $excluded_posts = array();

        // exclude prev post
        if( $next_post ){
            $excluded_posts[] = $next_post->ID;
        }

        // exclude current post
        $excluded_posts[] = get_the_ID();

        $random_query = new WP_Query(
            array(
                'orderby'           => 'rand',
                'posts_per_page'    => '1',
                'post__not_in'      => $excluded_posts,
                'post_type'         => get_post_type(),
            )
        );

        // No another post!
        if( $random_query->post_count == 0 )
            return '';

        $title = $random_query->post->post_title;

        if( empty( $random_query->post->post_title ) )
            $title = __( 'Previous Post', 'better-studio' );

        $title = apply_filters( 'the_title', $title, $random_query->post->ID );

        $date = mysql2date( get_option( 'date_format' ), $random_query->post->post_date );
        $rel = 'prev';

        $string = '<a href="' . get_permalink( $random_query->post ) . '" rel="'.$rel.'">';
        $inlink = str_replace( '%title', $title, $args['link'] );
        $inlink = str_replace( '%date', $date, $inlink );
        $inlink = $string . $inlink . '</a>';

        echo str_replace( '%link', $inlink, $args['format'] );

    }


    /**
     * Deprecated!
     *
     * Contains posts and pages custom fields default
     *
     * @var array
     */
    private $post_meta_defaults = array();


    /**
     * Deprecated!
     *
     * Updates query args for removing duplicate posts if needed.
     *
     * @param array $args
     * @return array
     */
    public function update_query_for_duplicate_posts( $args = array() ){
        return $args;
    }


    /**
     * Used For Checking Have Posts in Advanced Way!
     *
     * @return bool
     */
    public function have_posts( ){

        if( ! is_a( Better_Mag::posts()->get_query(), 'WP_Query' ) ){
            global $wp_query;

            Better_Mag::posts()->set_query( $wp_query );
        }

        // if count customized
        if( absint( Better_Mag::generator()->get_attr( 'count', 0 ) ) ){

            if( Better_Mag::generator()->get_attr( 'counter', 1 ) > ( Better_Mag::generator()->get_attr( 'count' ) ) ){
                return false;
            }else{
                if( self::get_query()->current_post + 1 < self::get_query()->post_count ){
                    return true;
                }
                else{
                    return false;
                }
            }

        }else{
            return self::get_query()->current_post + 1 < self::get_query()->post_count;
        }

    }



    /**
     * Extends the_post for adding new functionality to it.
     */
    public function the_post(){

        // if count customized
        if( absint( Better_Mag::generator()->get_attr( 'count', 0 ) ) ){

            Better_Mag::generator()->set_attr( 'counter', absint( Better_Mag::generator()->get_attr( 'counter', 1 ) ) + 1 );

        }

        // default the_post
        self::get_query()->the_post();

        // Adds .last-item to last item in loop
        // or loop with specific count
        if( Better_Mag::generator()->get_attr( 'count' ) ){
            if( Better_Mag::generator()->get_attr( 'count' ) == Better_Mag::generator()->get_attr( 'counter' ) ){
                Better_Mag::generator()->set_attr_class( 'last-item' );
            }
        }
        elseif( Better_Mag::posts()->get_query()->current_post + 1 === Better_Mag::posts()->get_query()->post_count ){
            Better_Mag::generator()->set_attr_class( 'last-item' );
        }

    }


    /**
     * Wrapper for the_content()
     *
     * @see the_content()
     * @param null $more_link_text
     * @param bool $strip_teaser
     * @param string $class
     * @param string $attr
     */
    public function the_content( $more_link_text = null, $strip_teaser = false, $class = '', $attr ='' ){

        // Post Links
        $post_links_attr = array(
            'before'        => '<div class="pagination" itemprop="pagination"><span class="current">' . Better_Translation()->_get( 'post_pages' ) . ' </span>',
            'after'         => '</div>',
            'echo'          =>  0,
            'pagelink'      => '<span>%</span>',

        );

        $class = '';

        if( is_page() ){
            $class = 'page-content';
        }elseif( is_singular() ){
            $class = 'post-content';
        }

        $show_featured = Better_Mag::get_meta( 'bm_disable_post_featured' );

        if( is_bool( $show_featured ) && $show_featured ){ // support for < versions 2
            $show_featured = true;
        }else{
            $show_featured = BM_Helper::result_of_meta_and_option( $show_featured, Better_Mag::get_option( 'content_show_featured_image' ) );
        }

        if( get_post_format() == 'gallery' && $show_featured ){

            $content = get_the_content( $more_link_text, $strip_teaser );
            $content = $this->_strip_shortcode_gallery( $content );
            $content = str_replace(']]>', ']]&gt;', apply_filters( 'better-framework/content/the_content', apply_filters( 'the_content', $content ) ) );
            $content .= wp_link_pages( $post_links_attr );
            echo '<div class="the-content ' . $class . ' clearfix" ' . $attr . '>' . $content . '</div>';

            return;
        }

        // All Post Formats
        echo '<div class="the-content ' . $class . ' clearfix" ' . $attr . '>' . apply_filters( 'better-framework/content/the_content', apply_filters( 'the_content', get_the_content( $more_link_text, $strip_teaser ) ) ) . wp_link_pages( $post_links_attr )  . '</div>';
    }


    /**
     * Used For Generating Single Post/Page Title
     *
     * @param bool $link
     * @param string $heading
     * @param string $class
     * @param string $attr
     */
    public function the_title( $link = false, $heading = 'h1', $class = '', $attr = '' ){

        if( ! Better_Mag::get_meta( 'hide_page_title' ) )
            if( $link )
                Better_Mag::generator()->blocks()->get_page_title( get_the_title(), get_permalink( get_the_ID() ), true, $heading, $class, $attr );
            else
                Better_Mag::generator()->blocks()->get_page_title( get_the_title(), false, true, $heading, $class, $attr );

    }


    /**
     * Used For Generating Post Meta
     */
    public function the_post_meta(){

        $show_meta = Better_Mag::get_meta( 'hide_post_meta' );

        switch( $show_meta ){

            case 'show':
                $show_meta = true;
                break;

            case 'hide':
                $show_meta = false;
                break;

            case 'default':
                $show_meta = ! Better_Mag::get_option( 'meta_hide_in_single' );
                break;

            default:
                $show_meta = ! Better_Mag::get_option( 'meta_hide_in_single' );
                break;

        }

        if( is_bool( $show_meta ) === true && $show_meta ){ // support for < versions 2
            $show_meta = true;
        }else{
            $show_meta = BM_Helper::result_of_meta_and_option( $show_meta, ! Better_Mag::get_option( 'meta_hide_in_single' ) );
        }

        if( $show_meta )
            Better_Mag::generator()->blocks()->partial_meta();

    }


    /**
     * Used for retrieving post and page meta
     *
     * @param null $key
     * @param null $post_id
     * @param bool|null $default
     * @param string $prefix
     * @return mixed|void
     */
    function get_meta( $key = null, $post_id = null, $default = false, $prefix = '_' ){

        if( ! $post_id ){
            global $post;
            $post_id = $post->ID;
        }

        if( is_int( $post_id ) && is_string( $key ) ){

            $meta = get_post_meta( $post_id, $prefix . $key, true );

            // If Meta check for default value
            if( empty($meta) && $default ){

                if( isset( $this->post_meta_defaults[$key] ) ){
                    return apply_filters( 'better-mag/meta/' . $key . '/value', $this->post_meta_defaults[$key] );
                }

            }else{
                return apply_filters( 'better-mag/meta/' . $key . '/value', $meta );
            }

        }

        return apply_filters( 'better-mag/meta/' . $key . '/value', '' );

    }



    /**
     * Used for printing post and page meta
     *
     * @param null $key
     * @param null $post_id
     * @param bool|null $default
     * @param string $prefix
     * @return mixed|void
     */
    function echo_meta( $key = null, $post_id = null, $default = false, $prefix = '_' ){

        echo Better_Mag::get_meta( $key, $default, $post_id );

    }

}