<?php

// Fire up
new BSBT_Duplicate_Posts();


/**
 * Better Studio Duplicate Posts
 *
 * Used to remove duplicate posts from page. there will no duplicate posts with using this.
 * With it's advanced functionality prevent every duplicate posts!
 *
 *
 * @package  BetterStudio Duplicate Posts
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.0.0
 * @access   public
 * @see      http://betterstudio.com
 */
class BSBT_Duplicate_Posts {

	/**
	 * Contains array of active pages
	 * @var array
	 */
	private $active_pages = array();


	/**
	 * Contains current page state, It's cache field!
	 *
	 * @var bool
	 */
	private $current_page_active = false;


	/**
	 * Appeared posts queue for removing theme in next queries for removing duplicate posts
	 *
	 * @var array
	 */
	private $appeared_posts = array();


	/**
	 * Flag used to detect main and first query of each page was processed for collecting all appeared posts IDs
	 *
	 * @var bool
	 */
	private $first_query_processed = false;


	function __construct(){
		add_action( 'better-framework/after_setup', array( $this, 'init' ) );
	}


	/**
	 * Initialization
	 */
	function init(){

		/**
		 * Filters list of active pages for removing duplicate posts in them.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $active_pages    All active pages
		 */
		$this->active_pages = apply_filters( 'better-template/duplicate-posts/config', $this->active_pages );

		// hooked to pre_get_posts because we should current page!
		add_action( 'pre_get_posts', array( $this, 'hack_pre_get_posts' ) );

	}


	/**
	 * Callback: Hooked to pre_get_posts to remove appeared posts from query
	 *
	 * @param   WP_Query    $query      WP_Query instance
	 */
	function hack_pre_get_posts( $query ) {

		// Process hiding appeared posts from queries
		if( ! is_admin() && $query->is_main_query() ){

			// Remove this
			remove_action( 'pre_get_posts', array( $this, 'hack_pre_get_posts' ) );

			$this->determine_is_active( $query );

			// Action if current page is active
			if( ! $this->is_active() )
				return;

			// Filter WP_Query
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

			// Filter WP_Query
			add_action( 'the_posts', array( $this, 'the_posts' ) );

			// Filter WP_Query
			add_action( 'the_post', array( $this, 'the_post' ) );

		}

	}


	/**
	 * @return array
	 */
	public function get_appeared_posts(){
		return $this->appeared_posts;
	}


	/**
	 * Adds post to appeared posts queue
	 *
	 * @param $appeared_post
	 */
	public function add_appeared_post( $appeared_post ){
		$this->appeared_posts[$appeared_post] = $appeared_post;
	}


	/**
	 * Clears appeared posts queue
	 */
	public function clear_appeared_posts(){
		$this->appeared_posts = array();
	}


	/**
	 * Callback: simple hack to collect posts from first and main query to removing them from queries that are
	 * retrieving before main query!
	 *
	 * Filter: the_posts
	 *
	 * @param $posts
	 *
	 * @return mixed
	 */
	function the_posts( $posts ){

		// Do one time
		if( ! $this->first_query_processed ){

			foreach( $posts as $post_id => $post_data ){
				$this->add_appeared_post( $post_data->ID );
			}

			$this->first_query_processed = true;
		}

		return $posts;
	}


	/**
	 * Callback: Adds current post ID
	 *
	 * Action: the_post
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	function the_post( $post ){

		$this->add_appeared_post( $post->ID );

	}


	/**
	 * Callback: Hooked to pre_get_posts to remove appeared posts from query
	 *
	 * @param   WP_Query    $query      WP_Query instance
	 */
	function pre_get_posts( $query ) {

		// Process hiding appeared posts from queries
		if( ! is_admin() ){
			$query->set( 'post__not_in', $this->get_appeared_posts() );
		}

	}


	/**
	 * Determine current page is activated or not and cache it
	 *
	 * @param $query WP_Query
	 */
	function determine_is_active( $query ){

		// Process current page state
		if( count( $this->active_pages ) > 0 ){

			// Whole site activation
			if( in_array( 'full', $this->active_pages ) ){
				$this->current_page_active = true;
				return;
			}


			// Hack to detect home page safely
			$is_home = false;
			if( $query->is_home() )
				$is_home = true;
			elseif( $query->is_page() )
				if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) &&  $query->query_vars['page_id'] == get_option( 'page_on_front' ) )
					$is_home = true;


			// Home page
			if( $is_home && in_array( 'home', $this->active_pages ) ){
				$this->current_page_active = true;
				return;
			}

			// Categories
			elseif( $query->is_category() ){

				// All categories
				if( in_array( 'categories', $this->active_pages ) ){
					$this->current_page_active = true;
					return;
				}
				// Current category
				elseif( in_array( 'category-' . $query->get_queried_object_id(), $this->active_pages ) ){
					$this->current_page_active = true;
					return;
				}

			}

			// Tags
			elseif( $query->is_tag() ){

				// All categories
				if( in_array( 'tags', $this->active_pages ) ){
					$this->current_page_active = true;
					return;
				}
				// Current category
				elseif( in_array( 'tag-' . $query->get_queried_object_id(), $this->active_pages ) ){
					$this->current_page_active = true;
					return;
				}

			}


		}

	}


	/**
	 * Handy function to get current page state
	 *
	 * @return bool
	 */
	function is_active(){
		return $this->current_page_active;
	}

}
