<?php

/**
 * Front Site Walker
 */
class BF_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * item is in a mega menu
	 *
	 * @var bool
	 */
	public $in_mega_menu = FALSE;


	/**
	 * Flag to detect mega menu is printed or not
	 *
	 * @var bool
	 */
	public $mega_menu_printed = FALSE;


	/**
	 * Holds current menu item
	 *
	 * @var array
	 */
	public $current_item;


	/**
	 * Stores mega menu inner-data
	 */
	public $last_lvl;


	/**
	 * Contains list of field ID's that should be behave as mega menu
	 *
	 * @var array
	 */
	public static $mega_menu_field_ids = array(
		'mega_menu' => array(
			'default' => 'disabled',
			'depth'   => 0,
		),
	);


	/**
	 * Sub menu animations
	 */
	public $animations = array(
		'fade',
		'slide-fade',
		'bounce',
		'tada',
		'shake',
		'swing',
		'wobble',
		'buzz',
		'slide-top-in',
		'slide-bottom-in',
		'slide-left-in',
		'slide-right-in',
		'filip-in-x',
		'filip-in-y',
	);


	/**
	 * Show parent items description
	 */
	public $show_desc_parent = FALSE;


	function __construct() {
		$this->show_desc_parent = apply_filters( 'better-framework/menu/show-parent-desc', $this->show_desc_parent );
	}


	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		$item_output = '';

		parent::start_lvl( $item_output, $depth, $args );

		if ( $this->in_mega_menu && ! $this->mega_menu_printed ) {

			if ( $depth >= 1 ) {
				$this->last_lvl .= $item_output;
			}

			return;
		}

		$output .= $item_output;
	}


	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		$item_output = '';

		parent::end_lvl( $item_output, $depth, $args );

		if ( $this->in_mega_menu ) {

			if ( $depth == 0 ) {

				$mega_menu = apply_filters( 'better-framework/menu/mega/end_lvl', array(
						'depth'    => $depth,
						'this'     => &$this,
						'sub_menu' => &$this->last_lvl,
						'item'     => &$this->current_item,
						'output'   => '',
					)
				);

				$output .= $mega_menu['output'];
				$this->mega_menu_printed = TRUE;
				$this->last_lvl          = '';

				return;
			}

			$this->last_lvl .= $item_output;

			return;
		}

		$output .= $item_output;
	}


	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$_class = array();

		//
		// Responsive Options
		//
		// Hide On Desktop
		if ( isset( $item->resp_desktop ) && $item->resp_desktop == 'hide' ) {
			$_class[] = 'hidden-lg';
		}
		// Hide On Desktop
		if ( isset( $item->resp_tablet ) && $item->resp_tablet == 'hide' ) {
			$_class[] = 'hidden-md';
		}
		// Hide On Mobile
		if ( isset( $item->resp_mobile ) && $item->resp_mobile == 'hide' ) {
			$_class[] = 'hidden-sm';
			$_class[] = 'hidden-xs';
		}

		// add specific class for identical usages for categories
		if ( $item->object == 'category' ) {
			$_class[] = 'menu-term-' . $item->object_id;
		}

		// Delete item title when hiding title set
		if ( ! isset( $item->hide_menu_title ) || $item->hide_menu_title == 1 ) {
			$_class[]    = 'menu-title-hide';
			$item->title = '<span class="hidden">' . $item->title . '</span>';
		}


		//
		// Menu Animations
		//
		if ( ! isset( $item->drop_menu_anim ) || $item->drop_menu_anim != '' ) {
			if ( $item->drop_menu_anim == 'random' ) {
				$_class[] = 'better-anim-' . $this->animations[ array_rand( $this->animations ) ];
			} else {
				$_class[] = 'better-anim-' . $item->drop_menu_anim;
			}
		} else {
			$_class[] = 'better-anim-fade';
		}


		//
		// Generate Icons HTML
		//
		if ( isset( $item->menu_icon ) ) {

			if ( is_array( $item->menu_icon ) && ! empty( $item->menu_icon['icon'] ) ) {
				if ( ! isset( $_temp_args ) ) {
					$_temp_args = (object) $args;
					$_temp_args = clone $_temp_args;
				}
				$_temp_args->link_before = $this->generate_icon_HTML( $item->menu_icon, $item->menu_icon_pos ) . $_temp_args->link_before;
				$_class[]                = 'menu-have-icon';
				$_class[]                = 'menu-icon-type-' . $item->menu_icon['type'];
			} elseif ( is_string( $item->menu_icon ) && $item->menu_icon != 'none' ) {
				if ( ! isset( $_temp_args ) ) {
					$_temp_args = (object) $args;
					$_temp_args = clone $_temp_args;
				}
				$_temp_args->link_before = $this->generate_icon_HTML( $item->menu_icon, $item->menu_icon_pos ) . $_temp_args->link_before;
				$_class[]                = 'menu-have-icon';
			}

		}


		//
		// Generate Badges html
		//
		if ( ! empty( $item->badge_label ) ) {

			if ( ! isset( $_temp_args ) ) {
				$_temp_args = (object) $args;
				$_temp_args = clone $_temp_args;
			}

			if ( ! empty( $item->badge_position ) ) {
				$badge_position = $item->badge_position;
				$_class[]       = 'menu-badge-' . $item->badge_position;
			} else {
				$badge_position = 'right';
				$_class[]       = 'menu-badge-right';
			}

			if ( $badge_position == 'right' ) {
				$_temp_args->link_after = $this->generate_badge_HTML( $item->badge_label ) . $_temp_args->link_after;
			} else {
				$_temp_args->link_before = $this->generate_badge_HTML( $item->badge_label ) . $_temp_args->link_before;
			}

			$_class[] = 'menu-have-badge';
		}


		//
		// Add description to parent items
		//
		if ( $depth == 0 && $this->show_desc_parent && isset( $item->description ) && ! empty( $item->description ) ) {

			if ( ! isset( $_temp_args ) ) {
				$_temp_args = (object) $args;
				$_temp_args = clone $_temp_args;
			}

			$_temp_args->link_after .= '<span class="description">' . $item->description . '</span>';
			$_class[] = 'menu-have-description';
		}


		$this->current_mega_menu_id = '';
		$this->in_mega_menu         = FALSE;
		$this->current_item         = NULL;
		$this->mega_menu_printed    = FALSE;


		foreach ( self::$mega_menu_field_ids as $mega_id => $mega_value ) {
			if ( property_exists( $item, 'mega_menu' ) && ! empty( $item->{$mega_id} ) && $item->{$mega_id} != $mega_value['default'] && $depth <= intval( $mega_value['depth'] ) ) {
				$this->current_mega_menu_id = $mega_id;
				$this->in_mega_menu         = TRUE;
				$this->current_item         = $item;
				break;
			}
		}

		// Mega menu classes
		if ( $this->in_mega_menu ) {
			$_class[] = 'menu-item-has-children menu-item-has-mega';
		}

		$item->classes = array_merge( (array) $item->classes, $_class );
		unset( $_class );

		// continue with new args that changed
		if ( isset( $_temp_args ) ) {
			parent::start_el( $item_output, $item, $depth, $_temp_args, $id );
		} else {
			parent::start_el( $item_output, $item, $depth, $args, $id );
		}


		//
		// in mega menu
		//
		if ( $this->in_mega_menu ) {

			if ( $depth > self::$mega_menu_field_ids[ $this->current_mega_menu_id ]['depth'] ) {
				$this->last_lvl .= $item_output;

				return;
			}

		}

		$output .= $item_output;
	}


	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker_Nav_Menu::end_el()
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		$item_output = '';

		parent::end_el( $item_output, $item, $depth, $args );

		// Mega menu items without child
		if ( $this->in_mega_menu && ! $this->mega_menu_printed && $depth <= self::$mega_menu_field_ids[ $this->current_mega_menu_id ]['depth'] ) {


			$mega_menu = apply_filters( 'better-framework/menu/mega/end_lvl', array(
					'depth'    => $depth,
					'this'     => &$this,
					'sub_menu' => &$this->last_lvl,
					'item'     => &$this->current_item,
					'output'   => '',
				)
			);

			$this->mega_menu_printed = TRUE;

			$output .= $mega_menu['output'] . $item_output;
		} // Mega menu items with child
		elseif ( $this->in_mega_menu && $depth < self::$mega_menu_field_ids[ $this->current_mega_menu_id ]['depth'] ) {
			$this->last_lvl .= $item_output;
		} // Simple drop down menu items
		else {
			$output .= $item_output;
		}

	}


	/**
	 * Used for generating custom badge html
	 *
	 * @param $badge_label
	 *
	 * @return string
	 */
	public function generate_badge_HTML( $badge_label ) {
		return '<span class="better-custom-badge ">' . $badge_label . '</span>';
	}


	/**
	 * Used for generating custom icon html
	 *
	 * @param $menu_icon
	 * @param $menu_icon_pos
	 *
	 * @return string
	 */
	public function generate_icon_HTML( $menu_icon, $menu_icon_pos ) {
		return bf_get_icon_tag( $menu_icon );
	}

} // BF_Menu_Walker