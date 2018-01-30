<?php

/**
 * Walker for back-end adding and saving menus custom fields
 */
class BF_Menu_Edit_Walker extends Walker_Nav_Menu_Edit{

    /**
     * Contains all locations. (used for filtering fields just for one location)
     *
     * @var array
     */
    public $locations = array();

    /**
     * current menu location
     *
     * @var
     */
    public $current_menu;


    public function __construct(){

        // load all registered menu locations
        $this->locations = array_flip( (array) get_nav_menu_locations() );

    }


    /**
     * Used for appending admin fields
     *
     * @param string $output
     * @param object $item
     * @param int $depth
     * @param array $args
     * @param int $id
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){

        // get current menu id
        if( ! $this->current_menu ){

            $menu = wp_get_post_terms( $item->ID, 'nav_menu' );

            if( isset($menu[0]) && $menu[0]->term_id )
                $this->current_menu = $menu[0]->term_id;

            if( ! $this->current_menu && $_REQUEST['menu'] ){
                $this->current_menu = $_REQUEST['menu'];
            }

        }

        $item_output = '';

        parent::start_el($item_output, $item, $depth, $args, $id);

        // add new fields before <div class="menu-item-actions description-wide submitbox">
        if( $fields = $this->get_custom_fields($item, $depth) ){

            $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $fields, $item_output);

        }

        $output .= $item_output;
    }


    /**
     * Load and save active custom fields for menus
     *
     * TODO: Add option for showing fields expander
     *
     * @param $item
     * @param int $depth
     * @return string
     */
    public function get_custom_fields($item, $depth = 0) {

        // load fields for admin section
        $fields = apply_filters( 'better-framework/menu/options', array() );

        require_once BF_PATH . 'menu/class-bf-menu-field-generator.php';

        $field_generator = new BF_Menu_Field_Generator( array(), $fields, $item );

        $output = $field_generator->get_fields();

        return $output ;
    }
}
