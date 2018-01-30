<?php

/**
 * BetterMag Dribbble Shortcode
 */
class BM_Dribbble_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm-dribbble';

        $this->widget_id = 'bm dribbble';

        $this->name = __( 'Dribbble', 'better-studio' );

        $this->description = __( 'Latest shots from Dribbble.', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-dribbble.png';

        $_options = array(
            'defaults'  => array(
                'title'             =>  Better_Translation()->_get( 'widget_dribbble_shots' ),
                'show_title'        =>  1,
                'user_id'           =>  '',
                'access_token'      =>  '',
                'photo_count'       =>  6,
                'tags'              =>  '',
                'column'            =>  3,
                'more'              =>  'show',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        );


        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id , $_options );

    }


    /**
     * Retrieve Dribbble fresh data
     *
     * @param $atts
     * @return array|bool
     */
    function get_fresh_data( $atts ){

        require_once BETTER_MAG_PATH . 'includes/libs/dribbble-api/Client.php';

        $client = new Better_Dribbble_Client( $atts['access_token'] );

        try{
            $shots = $client->getUserShots( $atts['user_id'] );
        }
        catch( Exception $e ){
            $shots = array();
        }

        return $shots;
    }


    /**
     * Wrapper ro getting Dribbble data with cache mechanism
     *
     * @param $atts
     * @return array|bool|mixed|void
     */
    public function get_data( $atts ){

        $data_store  = 'bf-drb-' . $atts['user_id'];
        $back_store  = 'bf-drb-bk-' . $atts['user_id'];

        $cache_time = 60 * 10;

        if( ( $data = get_transient( $data_store ) ) === false ){

            $data = $this->get_fresh_data( $atts );

            if( $data ){

                // save a transient to expire in $cache_time and a permanent backup option ( fallback )
                set_transient( $data_store, $data, $cache_time );
                update_option( $back_store, $data );

            }
            // fall to permanent backup store
            else {
                $data = get_option( $back_store );
            }
        }

        return $data;
    }


    /**
     * Generates HTML code for each image
     *
     * @param $shot
     * @param $atts
     */
    function get_li( $shot, $atts ){

        ?>
        <li class="dribbble-shot">
            <a href="<?php echo esc_url($shot->url); ?>" target="_blank">
                <img src="<?php echo esc_url($shot->images->normal); ?>" alt="<?php echo esc_attr($shot->title); ?>" />
            </a>
        </li>
        <?php

    }


    /**
     * Filter custom css codes for shortcode widget!
     *
     * @param $fields
     * @return array
     */
    function register_custom_css( $fields ){

        return $fields;

    }


    /**
     * Handle displaying of shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    function display( array $atts  , $content = '' ){

        ob_start();

        if( $atts['title'] && ! Better_Framework::widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        ?>
        <div class="bf-shortcode bf-shortcode-dribbble clearfix">
            <?php

            if( empty( $atts['access_token'] ) && is_user_admin() ){

                _e( 'Please fill Dribbble Access Token.', 'better-studio' );

            } elseif( ! empty( $atts['user_id'] ) ){
                $data = $this->get_data( $atts );

                if( $data != false ){ ?>
                    <ul class="bm-dribbble-shot-list columns-<?php echo $atts['column']; ?> clearfix"><?php
                        foreach( $data as $index => $item ){

                            if( $index >= $atts['photo_count'] ){
                                break;
                            }

                            $this->get_li( $item, $atts );
                        } ?>
                    </ul><?php

                }

                if( $atts['more'] == 'show' )
                    echo '<div class="tab-read-more"><a target="_blank" href="https://dribbble.com/' . $atts['user_id'] .'">'. Better_Translation()->_get( 'widget_dribbble_more' ) .'<i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) .'"></i></a></div>';

            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map( array(
            "name"          =>  $this->name,
            "base"          =>  $this->id,
            "icon"          =>  $this->icon,
            "description"   =>  $this->description,
            "weight"        =>  1,

            "wrapper_height"=>  'full',

            "category"      =>  __( 'BetterMag Addons', 'better-studio' ),
            "params"        => array(
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Section Title', 'better-studio' ),
                    "param_name"    =>  'title',
                    "value"         =>  $this->defaults['title'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show Title?', 'better-studio'),
                    "param_name"    =>  'show_title',
                    "value"         =>  $this->defaults['show_title'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Dribbble ID', 'better-studio' ),
                    "param_name"    =>  'user_id',
                    "value"         =>  $this->defaults['user_id'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Access Token', 'better-studio' ),
                    "param_name"    =>  'access_token',
                    "value"         =>  $this->defaults['access_token'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Shots', 'better-studio' ),
                    "param_name"    =>  'photo_count',
                    "value"         =>  $this->defaults['photo_count'],
                ),
                array(
                    'heading'       =>  __( 'Columns', 'better-studio' ),
                    'type'          =>  'bf_select',
                    "admin_label"   =>  false,
                    'options'       =>  array(
                        2  =>  __( '2 column', 'better-studio' ),
                        3  =>  __( '3 column', 'better-studio' ),
                    ),
                    "value"         =>  $this->defaults['column'],
                    "param_name"    =>  'column',
                ),
                array(
                    'heading'       =>  __( 'Show More Shots Link:', 'better-studio' ),
                    'type'          =>  'bf_select',
                    "admin_label"   =>  true,
                    'options'       =>  array(
                        'show'  =>  __( 'Show', 'better-studio' ),
                        'hide'  =>  __( 'Hide', 'better-studio' ),
                    ),
                    "value"         =>  $this->defaults['more'],
                    "param_name"    =>  'more',
                )
            )
        ) );

    }
}


class WPBakeryShortCode_bm_dribbble extends BF_VC_Shortcode_Extender { }







