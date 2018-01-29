<?php

/**
 * BetterFramework core menu manager.
 */
class BF_Menus {


	/**
	 * Active Fields
	 *
	 * @var array
	 */
	public $fields = array();


	/**
	 * BF Menu Field generator
	 *
	 * @var
	 */
	public $field_generator;


	/**
	 * Default walker for all menus
	 *
	 * @var string
	 */
	private $default_walker = 'BF_Menu_Walker';


	public function __construct() {
		// low priority init, give theme a chance to register hooks
		add_action( 'init', array( $this, 'init' ), 50 );

		// Icons Factory
		Better_Framework::factory( 'icon-factory' );
	}


	public function init() {

		// Load all fields from filters
		$this->fields = apply_filters( 'better-framework/menu/options', $this->fields );

		// have custom fields?
		if ( count( $this->fields ) ) {

			add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_menu_fields' ) );

			// Save and Walker filter only needed for admin
			if ( is_admin() ) {
				add_action( 'wp_update_nav_menu_item', array( $this, 'save_menu_fields' ), 10, 3 );
				add_filter( 'wp_edit_nav_menu_walker', array( $this, 'walker_menu_fields' ) );

				// Bug fix: when create a new menu, menu walker not fire so bf_enqueue_modal()
				// not fire and user cannot set icon for the menu items

				if( $GLOBALS['pagenow'] === 'nav-menus.php' ) {
					bf_enqueue_modal('icon');
				}
			}

			// Front Site Walker
			add_filter( 'wp_nav_menu_args', array( $this, 'walker_front' ) );
		}
	}


	/**
	 * Setup custom walker for editing the menu
	 */
	public function walker_menu_fields( $walker, $menu_id = null ) {

		include_once BF_PATH . 'menu/class-bf-menu-edit-walker.php';

		return 'BF_Menu_Edit_Walker';
	}


	/**
	 * Load the correct walker on demand when needed for the frontend menu
	 */
	public function walker_front( $args ) {

		// Default walker when no any field registered
		if ( empty( $this->fields ) ) {
			return $args;
		}

		$_walker = apply_filters( 'better-framework/menu/walker', $this->default_walker );

		if ( $_walker == $this->default_walker ) {
			require_once BF_PATH . 'menu/class-bf-menu-walker.php';
			$args['walker'] = new BF_Menu_Walker;
		} else {
			$_walker        = "Class" . $_walker;
			$args['walker'] = new $_walker;
		}

		return $args;
	}


	/**
	 * Load custom fields to the menu
	 *
	 * @param $menu_item
	 *
	 * @return
	 */
	public function setup_menu_fields( $menu_item ) {

		foreach ( $this->fields as $key => $field ) {

			// load values
			$value = get_post_meta( $menu_item->ID, '_menu_item_' . $key, true );

			if ( isset( $field['panel-id'] ) ) {
				$std_id = Better_Framework::options()->get_std_field_id( $field['panel-id'] );
			} else {
				$std_id = 'std';
			}

			if ( ! isset( $field[ $std_id ] ) ) {
				$std_id = 'std';
			}

			// load default value when it's not available!
			if ( empty( $value ) && isset( $this->fields[ $key ][ $std_id ] ) ) {
				$menu_item->{$key} = $this->fields[ $key ][ $std_id ];
			} else {
				$menu_item->{$key} = $value;
			}
		}

		return $menu_item;
	}

	/**
	 * Convert menu array to new WP version style
	 *
	 * @since 4.5.3 in menu array data, item key and menu item ID postion fliped
	 *
	 *
	 *
	 * @see   _wp_expand_nav_menu_post_data()
	 */
	protected function convert_data_array() {
		if ( ! is_array( $_POST['bf-m-i'] ) ) {
			return;
		}

		$new_structure = array();
		foreach ( $_POST['bf-m-i'] as $post_ID => $data_array ) {
			if ( ! is_array( $data_array ) ) {
				continue;
			}

			foreach ( $data_array as $item_type => $item_value ) {
				$new_structure[ $item_type ][ $post_ID ] = $item_value;
			}
		}

		$_POST['bf-m-i'] = $new_structure;
	}

	/**
	 * Save menu custom fields
	 *
	 * @global $wp_version wordpress version number
	 */
	public function save_menu_fields( $menu_id, $menu_item_db_id, $args ) {
		global $wp_version;

		$is_data_array = false;
		if ( isset( $_POST['bf-m-i'] ) ) {
			// Parse JSON and convert it to array
			// Parse this one time for better performance
			if ( is_string( $_POST['bf-m-i'] ) ) {
				$_POST['bf-m-i'] = json_decode( urldecode( $_POST['bf-m-i'] ), true );
			} else {

				$is_data_array = is_array( $_POST['bf-m-i'] );
			}
		} else {
			return; // continue if there is not better-menu-field!
		}


		/**
		 * Convert menu array style to new
		 */
		include( ABSPATH . WPINC . '/version.php' ); // include an unmodified $wp_version
		//check wordpress version and make sure $_POST modified by wordpress
		if ( ! $is_data_array && version_compare( $wp_version, '4.5.3', '<' ) ) {
			$this->convert_data_array();
		}

		foreach ( $this->fields as $key => $field ) {

			// add / update meta
			if ( isset( $_POST['bf-m-i'][ $key ][ $menu_item_db_id ] ) ) {

				if ( isset( $field['panel-id'] ) ) {
					$std_id = Better_Framework::options()->get_std_field_id( $field['panel-id'] );
				} else {
					$std_id = 'std';
				}

				if ( ! isset( $field[ $std_id ] ) ) {
					$std_id = 'std';
				}

				// check for saving default or not!?
				if ( isset( $field['save-std'] ) && ! $field['save-std'] ) {
					if ( $_POST['bf-m-i'][ $key ][ $menu_item_db_id ] != $field[ $std_id ] ) {
						update_post_meta( $menu_item_db_id, '_menu_item_' . $key, $_POST['bf-m-i'][ $key ][ $menu_item_db_id ] );
					} else {
						delete_post_meta( $menu_item_db_id, '_menu_item_' . $key );
					}
				} // save anyway ( save-std not defined or is true )
				else {
					update_post_meta( $menu_item_db_id, '_menu_item_' . $key, $_POST['bf-m-i'][ $key ][ $menu_item_db_id ] );
				}

			}

		}
	} // save_menu_fields

}