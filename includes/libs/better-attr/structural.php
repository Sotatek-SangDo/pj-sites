<?php


//
//
// Attributes for base structural elements.
//
//

add_filter( 'better_attr_body',         'better_attr_body',         5, 3 );
add_filter( 'better_attr_header',       'better_attr_header',       5, 3 );
add_filter( 'better_attr_footer',       'better_attr_footer',       5, 3 );
add_filter( 'better_attr_content',      'better_attr_content',      5, 3 );
add_filter( 'better_attr_main-content', 'better_attr_main_content', 5, 3 );
add_filter( 'better_attr_sidebar',      'better_attr_sidebar',      5, 3 );
add_filter( 'better_attr_menu',         'better_attr_menu',         5, 3 );
add_filter( 'better_attr_pagination',   'better_attr_pagination',   5, 3 );


if( ! function_exists( 'better_attr_body' ) ){
    /**
     * <body> element attributes.
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
    function better_attr_body( $attr, $class = '', $context = '' ){

        $attr['class'] = join( ' ', get_body_class() );

        $attr['dir'] = is_rtl() ? 'rtl' : 'ltr';

        $attr['itemscope'] = 'itemscope';

        $attr['itemtype'] = 'http://schema.org/WebPage';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_header' ) ){
    /**
     * Page <header> element attributes.
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
    function better_attr_header( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = "header-{$context}";
        }else{
            $attr['id'] = 'header';
        }

        if( ! empty( $class ) ){
            if( isset( $attr['class'] ) ){
                $attr['class'] .= ' ' . $class;
            }else{
                $attr['class'] = $class;
            }
        }

        $attr['role'] = 'banner';

        $attr['itemscope'] = 'itemscope';

        $attr['itemtype'] = 'http://schema.org/WPHeader';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_footer' ) ){
    /**
     * Page <footer> element attributes.
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
    function better_attr_footer( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = "footer-{$context}";
        }else{
            $attr['id'] = 'footer';
        }

        if( ! empty( $class ) ){
            if( isset( $attr['class'] ) ){
                $attr['class'] .= ' ' . $class;
            }else{
                $attr['class'] = $class;
            }
        }

        $attr['role'] = 'contentinfo';

        $attr['itemscope'] = 'itemscope';

        $attr['itemtype'] = 'http://schema.org/WPFooter';

        return $attr;

    }
}


if( ! function_exists( 'better_attr_content' ) ){
    /**
     * Main content container of the page.
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
    function better_attr_content( $attr, $class = '', $context = '' ){

        if( ! empty( $context ) ){
            $attr['id'] = $context;
        }

        if( ! empty( $context ) ){
            $attr['id'] = "content-{$context}";
        }else{
            $attr['id'] = 'content';
        }

        $attr['class'] = 'content-container';

        if( ! empty( $class ) ){
            $attr['class'] .= ' ' . $class;
        }

        $attr['role'] = 'main';

        $attr['itemscope'] = '';

        $attr['itemprop'] = 'mainContentOfPage';

        $attr['itemtype'] = 'http://schema.org/WebPageElement';

        return $attr;
    }
}


if( ! function_exists( 'better_attr_main_content' ) ){
	/**
	 * Main content of the page.
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
	function better_attr_main_content( $attr, $class = '', $context = '' ){

		if( ! empty( $context ) ){
			$attr['id'] = $context;
		}

		$attr['class'] = 'main-content';

		if( ! empty( $class ) ){
			$attr['class'] .= ' ' . $class;
		}

		$attr['itemscope'] = 'itemscope';

		// Posts or home page
		if( is_singular( 'post' ) || is_home() || is_archive()){
			$attr['itemtype'] = 'http://schema.org/Blog';
		}

		// Page
		elseif( is_singular( 'page' ) ){
			$attr['itemtype'] = 'http://schema.org/Blog';
		}

		// Search Page
		elseif( is_search() ){
			$attr['itemscope'] = 'itemscope';
			$attr['itemtype'] = 'http://schema.org/SearchResultsPage';
		}

		// Author archive page
		elseif( is_author() ){
			$attr['itemtype'] = 'http://schema.org/ProfilePage';
		}

		// Movies & Movie post type
		elseif( is_singular('movies') || is_singular('movie') ){
			$attr['itemtype'] = 'http://schema.org/Movie';
		}

		// Books & Book post type
		elseif( is_singular('books') || is_singular('book') ){
			$attr['itemtype'] = 'http://schema.org/Book';
		}

		// Products and Product post type
		elseif( is_singular('products') || is_singular('product') ){
			$attr['itemtype'] = 'http://schema.org/Product';
		}

		// Recipe post type
		elseif( is_singular('recipe') ){
			$attr['itemtype'] = 'http://schema.org/Recipe';
		}

		// App post type
		elseif( is_singular('app') ){
			$attr['itemtype'] = 'http://schema.org/MobileApplication';
		}

		// Event post type
		elseif( is_singular('event') ){
			$attr['itemtype'] = 'http://schema.org/Event';
		}

		// Music & Musics post type
		elseif( is_singular('music') | is_singular('Musics' )){
			$attr['itemtype'] = 'http://schema.org/Music';
		}

		// General web page
		else{
			$attr['itemtype'] = 'http://schema.org/WebPage';
		}

		return $attr;
	}
}


if( ! function_exists( 'better_attr_sidebar' ) ){
	/**
	 * Sidebar attributes.
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
	function better_attr_sidebar( $attr, $class = '', $context = '' ){

		if( ! empty( $context ) ){
			$attr['id'] = "sidebar-{$context}";
		}else{
			$attr['id'] = 'sidebar';
		}

		$attr['class'] = 'sidebar';

		if( ! empty( $class ) ){
			$attr['class'] .= ' ' . $class;
		}

		$attr['role'] = 'complementary';

		if( ! empty( $context ) ){

			$sidebar_name = bf_get_sidebar_name_from_id( $context );

			if( ! empty( $sidebar_name ) ){
				$attr['aria-label'] = esc_attr( sprintf( _x( '%s Sidebar', 'sidebar aria label', 'better-studio' ), $sidebar_name ) );
			}
		}

		$attr['itemscope'] = 'itemscope';

		$attr['itemtype'] = 'http://schema.org/WPSideBar';

		return $attr;
	}
}


if( ! function_exists( 'better_attr_menu' ) ){
	/**
	 * Nav menu attributes.
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
	function better_attr_menu( $attr, $class = '', $context = '' ){

		if( ! empty( $context ) ){
			$attr['id'] = "menu-{$context}";
		}else{
			$attr['id'] = 'menu';
		}

		$attr['class'] = 'menu';

		if( ! empty( $class ) ){
			$attr['class'] .= ' ' . $class;
		}

		$attr['role'] = 'navigation';

		if( ! empty( $context ) ){

			$menu_name = BF()->helper()->get_menu_location_name( $context );

			if( ! empty( $menu_name ) ){
				$attr['aria-label'] = esc_attr( sprintf( _x( '%s Menu', 'nav menu aria label', 'better-studio' ), $menu_name ) );
			}
		}

		$attr['itemscope'] = 'itemscope';

		$attr['itemtype'] = 'http://schema.org/SiteNavigationElement';

		return $attr;
	}
}


if( ! function_exists( 'better_attr_pagination' ) ){
	/**
	 * Pagination attributes.
	 *
	 * todo add next and prev code snippet functions
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
	function better_attr_pagination( $attr, $class = '', $context = '' ){

		if( ! empty( $context ) ){
			$attr['id'] = $context;
		}

		$attr['class'] = 'pagination';

		if( ! empty( $class ) ){
			$attr['class'] .= ' ' . $class;
		}

		$attr['itemscope'] = 'itemscope';

		$attr['itemtype'] = 'http://schema.org/SiteNavigationElement/Pagination';

		return $attr;
	}
}