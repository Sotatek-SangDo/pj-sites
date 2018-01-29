<?php

/**
 * BetterFramework Flickr Shortcode
 */
class BF_Flickr_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_flickr';

        $this->widget_id = 'flickr';

        $this->name = __( 'Flickr', 'better-studio' );

        $this->description = __( 'Latest photos from Flickr.', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-flickr.png';

        $_options = array(
            'defaults'  => array(
                'title'             =>  Better_Translation()->_get( 'widget_flickr_photos' ),
                'show_title'        =>  1,
                'user_id'           =>  '',
                'photo_count'       =>  12,
                'tags'              =>  '',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id , $_options );

    }


    /**
     * Retrieve Flickr fresh data
     *
     * @param $atts
     * @return array|bool
     */
    function get_flickr_data( $atts ){

        $file = wp_remote_get( 'http://api.flickr.com/services/feeds/photos_public.gne?format=json&id='. urlencode( $atts['user_id'] ) .'&nojsoncallback=1&tags=' . urlencode( $atts['tags'] ) );

        if( is_wp_error( $file ) || ! $file['body'] ){
            return '';
        }

        // Fix flickr json escape bug
        $file['body'] = str_replace( "\\'", "'", $file['body'] );
        $data = json_decode( $file['body'], true );

        if( ! is_array( $data ) ){
            return array();
        }

        $data = array_slice( $data['items'], 0, $atts['photo_count'] );

        // Replace medium with small square image
        foreach ($data as $key => $item) {
            $data[$key]['media'] = preg_replace('/_m\.(jp?g|png|gif)$/', '_s.\\1', $item['media']['m']);
        }

        return $data;

    }


    /**
     * Wrapper ro getting Flickr data with cache mechanism
     *
     * @param $atts
     * @return array|bool|mixed|void
     */
    public function get_data( $atts ){

        $data_store  = 'bf-fk-' . $atts['user_id'];
        $back_store  = 'bf-fk-bk-' . $atts['user_id'];

        $cache_time = 60 * 10;

        if( ( $data = get_transient( $data_store ) ) === false ){

            $data = $this->get_flickr_data( $atts );

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
     * @param $item
     * @param $atts
     */
    function get_li( $item, $atts ){
        ?>

        <li class="flickr_image">
            <a href="<?php echo esc_url($item['link']); ?>">
                <img src="<?php echo esc_url($item['media']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
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
        <div class="bf-shortcode bf-shortcode-flickr clearfix">
            <div class="the-content">
                <?php
                if( ! empty( $atts['user_id'] ) ){
                    $data = $this->get_data( $atts );

                    if( $data != false ){ ?>
                        <ul class="bf-flickr-photo-list"><?php
                            foreach( (array) $data as $index => $item ){

                                if( $index >= $atts['photo_count'] ){
                                    break;
                                }

                                $this->get_li( $item, $atts );
                            } ?>
                        </ul><?php

                    }
                }
                ?>
            </div>
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
                    'heading'       =>  __( 'Instructions', 'better-studio' ),
                    'param_name'    =>  'help',
                    'type'          =>  'bf_info',
                    'value'         =>  __('<p>You need to get the user id from your Flickr account.</p>
                <ol>
                    <li>Attain your user id using <a href="http://goo.gl/pHx7LV" target="_blank">this tool</a></li>
                    <li>Copy the user id</li>
                    <li>Paste it in the "Flickr ID" input box below</li>
                </ol>
                ', 'better-studio' ),
                    'state'         =>  'open',
                    'info-type'     =>  'help',
                    'section_class' =>  'widefat',
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Flickr ID (<a href="http://www.idgettr.com">Get Your ID</a>)', 'better-studio' ),
                    "param_name"    =>  'user_id',
                    "value"         =>  $this->defaults['user_id'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Photos', 'better-studio' ),
                    "param_name"    =>  'photo_count',
                    "value"         =>  $this->defaults['photo_count'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Tags (comma separated, optional)', 'better-studio' ),
                    "param_name"    =>  'tags',
                    "value"         =>  $this->defaults['tags'],
                ),
            )
        ) );

    }
}


class WPBakeryShortCode_bf_flickr extends BF_VC_Shortcode_Extender { }