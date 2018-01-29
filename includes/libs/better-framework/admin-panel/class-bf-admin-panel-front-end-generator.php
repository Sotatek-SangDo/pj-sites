<?php

class BF_Admin_Panel_Front_End_Generator extends BF_Admin_Fields{


    /**
     * Constructor Function
     *
     * @param array $items      Panel All Options
     * @param $id               Panel ID
     * @param array $values     Panel Saved Values
     *
     * @since 1.0
     * @access public
     * @return \BF_Admin_Panel_Front_End_Generator
     */
	public function __construct( array &$items, &$id, &$values = array() ){

        $default = array(
            'templates_dir' => bf_get_dir( 'admin-panel/templates/' )
        );
		// Parent Constructor
		parent::__construct( $default );

		$this->items  = $items;
		$this->id	  = $id;
		$this->values = $values;
	}


    /**
     * Display HTML output of panel array
     *
     * Display full html of panel array which is defined in object parameter
     *
     * @since 1.0
     * @access public
     * @param bool $repeater
     * @return string
     */
	public function get_fields( $repeater = false ){

        /**
         * Fires before generating panel HTML
         *
         * @since 2.0
         *
         * @param string    $args        arguments
         */
        do_action( 'better-framework/panel/' . $this->id . '/generate/before', $this->items, $this->values );

		$is_repeater = is_array( $repeater );
		$fields	 = $is_repeater ? $repeater['options'] : $this->items['fields'];
		$output	 = '';
		$counter = 0;
        $group_counter = 0;
		$has_tab = false;

        $_lang = bf_get_current_language_option_code();

        $current_style = get_option( $this->id . $_lang . '_current_style' );

        $std_id = Better_Framework::options()->get_std_field_id( $this->id );

		foreach( $fields as $field ){

            if( isset( $field['style'] ) && ! in_array( $current_style, $field['style'] ) )  continue;

            // If value have been saved before
            if( isset( $field['id'] ) && isset( $this->values[ $field['id'] ] ) ){
                $field['value'] = $this->values[ $field['id'] ];
            }
            // Default value for current style
            elseif( $field['type'] != 'repeater' && isset( $field[$std_id] ) ){
                $field['value'] = $field[$std_id];
            }
            // Default value for default style!
            elseif( isset( $field['std'] ) && $field['type'] != 'repeater' ){
                $field['value'] = $field['std'];
            }

            if( $field['type'] != 'repeater' ){

                $field['input_name'] = $this->input_name( $field );

                if( ! isset( $field['value'] ) ){
                    $field['value'] = false;
                }

            }

            if( isset( $field['filter-field'] ) && $field['filter-field-value'] ){

                if( $field['filter-field-value'] != BF()->options()->get( $field['filter-field'], $this->id ) ){

                    $field['section-css']['display'] = "none";

                }

            }

			if( $field['type'] == 'tab' || $field['type'] == 'subtab' ){

                // close tag for latest group in tab
                if( $group_counter != 0 ){
                    $group_counter = 0;
                    $output .= $this->get_fields_group_close( $field );
                }

                $is_subtab = $field['type'] == 'subtab';
				if( $counter != 0 )
					$output .= '</div>';
				if( $is_subtab )
					$output .= "\n\n<!-- Section -->\n<div class='group subtab-group' id='bf-group-{$field['id']}'>\n";
				else
					$output .= "\n\n<!-- Section -->\n<div class='group' id='bf-group-{$field['id']}'>\n";
				$has_tab = true;
				continue;
			}

			if( $field['type'] == 'group_close'  ){

				// close tag for latest group in tab
				if( $group_counter != 0 ){
					$group_counter = 0;
					$output .= $this->get_fields_group_close( $field );
				}
				continue;
			}

			if( $field['type'] == 'group'  ){

                // close tag for latest group in tab
				if( $group_counter != 0 ){
                    $group_counter = 0;
                    $output .= $this->get_fields_group_close( $field );
                }

                $output .= $this->get_fields_group_start( $field );

                $group_counter++;
			}


			if( ! in_array( $field['type'], $this->supported_fields ) )
				continue;

            // for image checkbox sortable option
            if( isset($field['is_sortable']) && ($field['is_sortable']=='1') )
                $field['section_class'] .=' is-sortable';


            if( isset( $field['template'] ) ){

                $input = call_user_func( array( $this, $field['type'] ), $field );

                $output .= str_replace( '%%input%%', $input, $field['template'] );


            }else{
                $output .= $this->section(
                    call_user_func(
                        array( $this, $field['type'] ),
                        $field
                    ),
                    $field
                );
            }

			$counter++;

		} // foreach

		if( $has_tab )
			$output .= '</div>';

        /**
         * Fires after generating panel HTML
         *
         * @since 2.0
         *
         * @param string    $args        arguments
         */
        do_action( 'better-framework/panel/' . $this->id . '/generate/after', $this->items, $this->values, $output );

		return $output;	
	}


    /**
     * PHP __call Magic Function
     *
     * @param $name
     * @param $arguments
     * @throws Exception
     * @internal param $ (string) $name      name of requested method
     * @internal param $ (array)  $arguments arguments of requested method
     *
     * @since 1.0
     * @access public
     * @return mixed
     */
    public function __call( $name, $arguments ){

        $file = $this->options['fields_dir'] . $name . '.php';

        // Check if requested field (method) does exist!
        if( ! file_exists( $file ) )
            throw new Exception( $name . ' does not exist!' );

        $options = $arguments[0];
        $panel_id  = $this->id;

        // Capture output
        ob_start();
        require $file;

        $data = ob_get_clean();

        return $data;
    }


}