<?php

/**
 * Used for adding fields for all WordPress widgets
 */
class BF_Widgets_General_Fields {


    /**
     * Contain active general fields
     *
     * @var array
     */
    var $fields = array();


    /**
     * Contain current fields options
     *
     * @var array
     */
    private $options = array();


	/**
     * Contains list of all valid general field ID
     *
     * @var array
     */
    static $valid_general_fields = array(

        // Advanced Fields
        'bf-widget-bg-color',
        'bf-widget-title-color',
        'bf-widget-title-bg-color',
        'bf-widget-title-icon',
        'bf-widget-title-link',

        // Responsive Fields
        'bf-widget-show-desktop',
        'bf-widget-show-tablet',
        'bf-widget-show-mobile',

    );


	/**
     * Contains default value of general fields
     *
     * @var array
     */
    static $default_values = array();


    function __construct(){

        // Load and prepare options only in backend for better performance
        if( is_admin() ){

            // Loads all active fields
            $this->fields = apply_filters( 'better-framework/widgets/options/general', $this->fields );

            // Prepare fields for generator
            $this->prepare_options();

            // Add input fields(priority 10, 3 parameters)
            add_action( 'in_widget_form', array( $this , 'in_widget_form' ), 5, 3 );

            // Callback function for options update (priority 5, 3 parameters)
            add_filter( 'widget_update_callback', array( $this , 'in_widget_form_update'), 99, 2 );

        }else{

            add_filter( 'dynamic_sidebar_params', array( $this, 'dynamic_sidebar_params'), 99, 2 );

        }

    }


    /**
     * Check for when a field is general field
     *
     * @param $field
     * @return bool
     */
    public static function is_valid_field( $field ){

        return in_array( $field , self::$valid_general_fields );

    }


    /**
     * Returns list of all valid general fields
     *
     * @return array
     */
    public static function get_general_fields(){
        return self::$valid_general_fields;
    }


    /**
     * Get default value for general fields
     *
     * @param $field
     * @return bool
     */
    public static function get_default_value( $field ){

        // Return default value from cache
        if( isset( self::$default_values[ $field ] ) )
            return self::$default_values[ $field ];

        $_default = '';

        switch( $field ){

            case 'bf-widget-show-desktop':
            case 'bf-widget-show-tablet':
            case 'bf-widget-show-mobile':
                $_default = 'show';

        }


        // Get field default value from filters
        self::$default_values[ $field ] = apply_filters( "better-framework/widgets/options/general/{$field}/default" , $_default );

        return self::$default_values[ $field ];

    }


    /**
     * Save active fields values
     *
     * @param $instance
     * @param $new_instance
     * @return mixed
     */
    function in_widget_form_update( $instance, $new_instance ){

        // Remove default fields
        foreach( $this->options as $option ){
            $def[$option['attr_id']] = $option['std'];
        }

	    // Save all valid general fields
	    foreach( $this->get_general_fields() as $field ){
		    if( isset( $new_instance[$field] ) ){
			    if( $new_instance[$field] != $def[$field] ){
				    $instance[$field] = $new_instance[$field];
			    }else{
				    unset( $new_instance[$field] );
				    unset( $instance[$field] );
			    }
		    }
	    }

	    return $instance;
    }


    /**
     * load options and prepare to admin form generation for active fields
     */
    function prepare_options(){

        $color_fields['group-1'] = array(
            'name'      =>  __( 'Color Options', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );

        $title_fields['group-2'] = array(
            'name'      =>  __( 'Title Options', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );

        $responsive_fields['group-3'] = array(
            'name'      =>  __( 'Responsive Options', 'better-studio' ),
            'type'      => 'group',
            'state'     => 'close',
        );


        // Iterate all fields to find active fields
        foreach( (array) $this->fields as $field_id => $field ){

            // detect advanced fields category
            if( self::is_valid_field( $field ) ){

                // Color Fields
                $raw_field = $this->register_color_option( $field );

                if( $raw_field != false){
                    $color_fields[] = $raw_field;
                    continue;
                }

                // Advanced Fields
                $raw_field = $this->register_advanced_option( $field );

                if( $raw_field != false){
                    $title_fields[] = $raw_field;
                    continue;
                }

                // Responsive Fields
                $raw_field = $this->register_responsive_option( $field );

                if( $raw_field != false ){
                    $responsive_fields[] = $raw_field;
                    continue;
                }

            }

        }

        // Add color fields to main fields
        if( count( $color_fields ) > 1 ){
			// Fix group title for 1 field
	        if( count( $color_fields ) == 2 ){
		        $color_fields['group-1']['name'] = $color_fields[0]['name'];
		        $color_fields[0]['name'] = '';
	        }

            $this->options = array_merge( $this->options, $color_fields );
        }

        // Add advanced fields to main fields
        if( count( $title_fields ) > 1 ){
	        // Fix group title for 1 field
	        if( count( $title_fields ) == 2 ){
		        $title_fields['group-2']['name'] = $title_fields[0]['name'];
		        $title_fields[0]['name'] = '';
	        }

	        $this->options = array_merge( $this->options, $title_fields );
        }

        // Add responsive fields to main fields
        if( count( $responsive_fields ) > 1 ){
	        // Fix group title for 1 field
	        if( count( $responsive_fields ) == 2 ){
		        $responsive_fields['group-3']['name'] = $responsive_fields[0]['name'];
		        $responsive_fields[0]['name'] = '';
	        }

            $this->options = array_merge( $this->options, $responsive_fields );
        }

    }


    /**
     * Init a general field generator options
     *
     * @param $field
     *
     * @return array|bool
     */
    private function register_color_option( $field ){

        switch( $field ){

            case 'bf-widget-title-color':
                return array(
                    'name'      =>  __( 'Widget Title Color','better-studio'),
                    'attr_id'   =>  $field,
                    'type'      =>  'color',
                    'std'       =>  $this->get_default_value( $field ),
                );
                break;

            case 'bf-widget-title-bg-color':
                return array(
                    'name'      =>  __( 'Widget Title Background Color', 'better-studio' ),
                    'attr_id'   =>  $field,
                    'type'      =>  'color',
                    'std'       =>  $this->get_default_value( $field ),
                );
                break;


	        case 'bf-widget-bg-color':
		        return array(
			        'name'      =>  __( 'Widget Background Color','better-studio'),
			        'attr_id'   =>  $field,
			        'type'      =>  'color',
			        'std'       =>  $this->get_default_value( $field ),
		        );
		        break;

        }

        return false;
    }


    /**
     * Init a general field generator options
     *
     * @param $field
     *
     * @return array|bool
     */
    private function register_advanced_option( $field ){

        switch( $field ){

	        case 'bf-widget-title-icon':
                return array(
                    'name'      =>  __( 'Widget Title Icon', 'better-studio' ),
                    'attr_id'   =>  $field,
                    'type'      =>  'icon_select',
                    'std'       =>  $this->get_default_value( $field ),
                );
                break;

            case 'bf-widget-title-link':
                return array(
                    'name'      =>  __( 'Widget Title Link', 'better-studio' ),
                    'attr_id'   =>  $field,
                    'type'      =>  'text',
                    'std'       =>  $this->get_default_value( $field ),
                    'ltr'       =>  true,
                );
                break;

        }

        return false;
    }


    /**
     * Init a general field generator options
     *
     * @param $field
     *
     * @return array|bool
     */
    private function register_responsive_option( $field ){

        switch( $field ){

            case 'bf-widget-show-desktop':
                return array(
                    'name'      =>  __( 'Show On Desktop', 'better-studio'),
                    'attr_id'   =>  $field,
                    'type'      =>  'select',
                    'std'       =>  $this->get_default_value( $field ),
                    'options'   =>  array(
                        'show'  => __( 'Show', 'better-studio' ),
                        'hide'  => __( 'Hide', 'better-studio' ),
                    ),
                );
                break;

            case 'bf-widget-show-tablet':
                return array(
                    'name'      =>  __( 'Show On Tablet', 'better-studio'),
                    'attr_id'   =>  $field,
                    'type'      =>  'select',
                    'std'       =>  $this->get_default_value( $field ),
                    'options'   =>  array(
                        'show'  => __( 'Show', 'better-studio' ),
                        'hide'  => __( 'Hide', 'better-studio' ),
                    ),
                );
                break;

            case 'bf-widget-show-mobile':
                return array(
                    'name'      =>  __( 'Show On Mobile', 'better-studio'),
                    'attr_id'   =>  $field,
                    'type'      =>  'select',
                    'std'       =>  $this->get_default_value( $field ),
                    'options'   =>  array(
                        'show'  => __( 'Show', 'better-studio' ),
                        'hide'  => __( 'Hide', 'better-studio' ),
                    ),
                );
                break;

        }

        return false;
    }


    /**
     * @param $widget WP_Widget
     */
    function prepare_fields( $widget ){

        for( $i=0 ; $i < count( $this->options ) ; $i++ ){

            // Do not do anything with fields without attr_id
            if( ! isset( $this->options[$i]['attr_id'] ) ){
                continue;
            }

            $this->options[$i]['input_name']    = $widget->get_field_name( $this->options[$i]['attr_id'] );
            $this->options[$i]['id']            = $widget->get_field_id( $this->options[$i]['attr_id']);
            $this->options[$i]['id-raw']        = $this->options[$i]['attr_id'];
        }

    }


    /**
     * Add input fields to widget form
     *
     * @param $t
     * @param $return
     * @param $instance
     */
    function in_widget_form( $t, $return, $instance ){

        Better_Framework::factory( 'widgets-field-generator' , false , true );

        $this->prepare_fields( $t );

        // Return if there is no general field
        if( count( $this->options ) <= 0 )
            return;

        // Prepare generator config file
        $options = array(
            'fields'    => $this->options
        );

        // Create generator instance
        $generator = new BF_Widgets_Field_Generator( $options , $instance );

        echo $generator->get_fields();

    }


	/**
     * Callback: Used to change sidebar params to add general fields
     *
     * Filter: dynamic_sidebar_params
     *
     * @param $params
     *
     * @return mixed
     */
    public function dynamic_sidebar_params( $params ) {

        global $wp_registered_widgets;

        $id = $params[0]['widget_id']; // Current widget ID

        if( isset( $wp_registered_widgets[$id]['callback'][0] ) && is_object( $wp_registered_widgets[$id]['callback'][0] ) ){

            $custom_class = array();

            // Get settings for all widgets of this type
            $settings = $wp_registered_widgets[$id]['callback'][0]->get_settings();

            // Get settings for this instance of the widget
            $instance = $settings[substr( $id, strrpos( $id, '-' ) + 1 )];

            // Add custom link to widget title
            if( ! empty( $instance['bf-widget-title-link'] ) ){
                $params[0]['before_title'] .= "<a href='{$instance['bf-widget-title-link']}'>";
                $params[0]['after_title'] = "</a>" . $params[0]['after_title'];
            }

            // Add icon before widget title
            if( ! empty( $instance['bf-widget-title-icon'] ) ){

                if( is_array( $instance['bf-widget-title-icon'] ) && $instance['bf-widget-title-icon']['icon'] != '' ){
                    $params[0]['before_title'] .= bf_get_icon_tag( $instance['bf-widget-title-icon'] ) . ' ';
                    $custom_class[] = 'widget-have-icon';
                }elseif( is_string( $instance['bf-widget-title-icon'] )  ){
                    $params[0]['before_title'] .= bf_get_icon_tag( $instance['bf-widget-title-icon'] ) . ' ';
                    $custom_class[] = 'widget-have-icon';
                }else{
                    $custom_class[] = 'widget-havent-icon';
                }

            }else{
                $custom_class[] = 'widget-havent-icon';
            }

            // Show on desktop
            if( ! empty( $instance['bf-widget-show-desktop'] ) ){
                if( $instance['bf-widget-show-desktop'] == 'hide' ){
                    $custom_class[] = 'hidden-lg';
                    $custom_class[] = 'hidden-md';
                }
            }

            // Show on tablet
            if( ! empty( $instance['bf-widget-show-tablet'] ) ){
                if( $instance['bf-widget-show-tablet'] == 'hide' )
                    $custom_class[] = 'hidden-sm';
            }

            // Show on mobile
            if( ! empty( $instance['bf-widget-show-mobile'] ) ){
                if( $instance['bf-widget-show-mobile'] == 'hide' )
                    $custom_class[] = 'hidden-xs';
            }

            // Prepare custom classes
            $class_to_add = 'class=" ' . implode( ' ', $custom_class ) . ' '; // Make sure you leave a space at the end

            // Add classes
            $params[0]['before_widget'] = str_replace(
                'class="',
                $class_to_add,
                $params[0]['before_widget']
            );
        }

        return $params;
    }
}