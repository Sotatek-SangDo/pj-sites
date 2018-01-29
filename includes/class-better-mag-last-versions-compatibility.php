<?php
/**
 * Class Better_Mag_Last_Versions_Compatibility
 */
class Better_Mag_Last_Versions_Compatibility{


    /**
     * Contains main theme log option ID
     *
     * @var string
     */
    private $option_id = 'better-studio-better-mag';


    function __construct(){

        add_action( 'better-framework/after_setup', array( $this, 'do_compatibility' ), 1 );

        // changes shortcode to new standard
        add_filter( 'content_edit_pre', array( $this, 'fix_shortcodes') );
        add_filter( 'the_content', array( $this, 'fix_shortcodes' ) );

    }


    /**
     * Logs theme versions and make theme compatible with latest versions
     */
    function do_compatibility(){

        $theme_info = get_option( $this->option_id );
        $active_version = BF()->theme()->get( 'Version' );
        $must_update = false;

        // First time or after version 2
        if( $theme_info === false ){

            $must_update = true;

            // Empty history
            $theme_info['history'] = array();

            // Compatibility
            $theme_info['comp'] = array();

            if( get_option( 'better_mag_comp_v_1_4' ) === false ){

                $this->before_v_1_4_comp();
                $theme_info['comp'][] = '1.4';

            }else{

                $theme_info['history'][] = get_option( 'better_mag_comp_v_1_4' );
                $theme_info['history'][] = '1.4';
                $theme_info['comp'][] = '1.4';
                delete_option( 'better_mag_comp_v_1_4' );

            }

            if( get_option( 'better_mag_comp_v_2' ) === false ){

                $this->before_v_2_comp();
                $theme_info['comp'][] = '2.0';

            }else{

                $theme_info['history'][] = get_option( 'better_mag_comp_v_1_4' );
                $theme_info['history'][] = '2.0';
                $theme_info['comp'][] = '2.0';
                delete_option( 'better_mag_comp_v_2' );

            }

        }else{

            if( ! in_array( '1.4', $theme_info['comp'] ) && version_compare( 1.4, $active_version, '<=' ) ){

                $this->before_v_1_4_comp();

                $theme_info['history'][] = '1.4';

                $theme_info['comp'][] = '1.4';

                $must_update = true;

            }

            if( ! in_array( '2.0', $theme_info['comp'] ) && version_compare( 2.0, $active_version, '<=' ) ){

                $this->before_v_2_comp();

                $theme_info['history'][] = '2.0';

                $theme_info['comp'][] = '2.0';

                $must_update = true;

            }

            if( ! in_array( '2.8', $theme_info['comp'] ) && version_compare( 2.8, $active_version, '<=' ) ){
	            if( $this->before_v2_8_comp() ){
		            $theme_info['history'][] = '2.8';
		            $theme_info['comp'][] = '2.8';
		            $must_update = true;
	            }
            }

            if( ! empty( $theme_info['active'] ) && $theme_info['active'] != $active_version ){
                $theme_info['history'][] = $theme_info['active'];
                $theme_info['active'] = $active_version;
                $must_update = true;
            }elseif( empty( $theme_info['active'] ) ){
                $theme_info['active'] = $active_version;
                $must_update = true;
            }

        }

        // update log
        if( $must_update ){
            update_option( $this->option_id, $theme_info );
        }

    }


    /**
     * Fixes social counter and newsticker shortcodes to new ( our new standard ) shortcodes
     *
     * @param $content
     *
     * @return mixed
     */
    function fix_shortcodes( $content ){

        $content = str_replace( '[bf_social_counter', '[better-social-counter', $content );

        $content = str_replace( '[review', '[better-reviews', $content ); // comp for BetterReviews after version 2 of theme

        return str_replace( '[bf_news_ticker', '[better-news-ticker', $content );

    }


    /**
     * Prepare compatibility for versions before 1.4
     */
    function before_v_1_4_comp(){

        // Updates current style
        $style = get_option( '__better_mag__current_style' );
        if( $style != false ){
            add_option( '__better_mag__theme_options_current_style', $style );
            delete_option( '__better_mag__current_style' );
        }

        // Updates social counter widget id to new
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        foreach( (array) $sidebars_widgets as $sidebar_location => $sidebar_value ){

            if( $sidebar_location == 'array_version' )
                continue;

            foreach( (array) $sidebar_value as $widget_key => $widget_id ){

                if( substr( $widget_id, 0, 14 ) == 'social-counter' ){

                    $sidebars_widgets[$sidebar_location][$widget_key] = 'better-' . $widget_id;

                }elseif( substr( $widget_id, 0, 11 ) == 'news_ticker' ){
                    $sidebars_widgets[$sidebar_location][$widget_key] = 'better-' . str_replace( '_', '-', $widget_id );
                }
            }
        }

        update_option( 'sidebars_widgets', $sidebars_widgets );

        // Updates social counter widget id to new in custom sidebars
        $sidebars_widgets = get_option( 'cs_sidebars' );

        if( $sidebars_widgets !== false ){

            foreach( (array) $sidebars_widgets as $sidebar_location => $sidebar_value ){

                if( $sidebar_location == 'array_version' )
                    continue;

                foreach( (array) $sidebar_value as $widget_key => $widget_id ){

                    if( substr( $widget_id, 0, 14 ) == 'social-counter' ){

                        $sidebars_widgets[$sidebar_location][$widget_key] = 'better-' . $widget_id;

                    }elseif( substr( $widget_id, 0, 11 ) == 'news_ticker' ){

                        $sidebars_widgets[$sidebar_location][$widget_key] = 'better-' . str_replace('_', '-', $widget_id);

                    }
                }
            }

            update_option( 'cs_sidebars', $sidebars_widgets );

        }

        // Updates social counter widget data
        $social_counter_widget = get_option( 'widget_social-counter' );
        if( $social_counter_widget !== false ){
            delete_option( 'widget_social-counter' );
            update_option( 'widget_better-social-counter', $social_counter_widget );
        }

        // Updates news ticker widget data
        $news_ticker_widget = get_option( 'widget_news_ticker' );
        if( $news_ticker_widget !== false ){
            delete_option( 'widget_news_ticker' );
            update_option( 'widget_better-news-ticker', $news_ticker_widget );
        }

        // Social Counter options compatibility
        // options will be moved to "Better Social Counter" plugin automatically
        $theme_options = get_option( '__better_mag__theme_options' );
        if( $theme_options !== false && isset( $theme_options['facebook_page'] ) ){

            $social_counter_options = array();

            $fields = array(
                'facebook_page',
                'facebook_title',

                'twitter_username',
                'twitter_title',
                'twitter_api_key',
                'twitter_api_secret',

                'google_page',
                'google_page_key',
                'google_title',

                'youtube_username',
                'youtube_type',

                'dribbble_username',
                'dribbble_title',

                'vimeo_username',
                'vimeo_type',
                'vimeo_title',

                'delicious_username',
                'delicious_title',

                'soundcloud_username',
                'soundcloud_api_key',
                'soundcloud_title',

                'github_username',
                'github_title',

                'behance_username',
                'behance_api_key',
                'behance_title',

                'vk_username',
                'vk_title',

                'vine_profile',
                'vine_email',
                'vine_pass',
                'vine_title',

                'pinterest_username',
                'pinterest_title',

                'flickr_group',
                'flickr_key',
                'flickr_title',

                'steam_group',
                'steam_title',
            );


            foreach( $fields as $id ){

                if( isset( $theme_options[$id] ) ){

                    $social_counter_options[$id] = $theme_options[$id];

                    unset( $theme_options[$id] );

                }

            }

            update_option( '__better_mag__theme_options', $theme_options );

            update_option( 'better_social_counter_options', $social_counter_options );


            if( class_exists('Better_Social_Counter') ) {
                BF()->admin_notices()->add_notice(array(
                    'class' => 'updated',
                    'msg' => __('BetterMag social counter options successfully moved to <b>Better Social Counter</b> plugin options.', 'better-studio') . ' <a href="' . admin_url('options-general.php?page=better-studio/better_social_counter_options') . '"><i>' . __('Better Social Counter Options', 'better-studio') . '</i></a>'
                ));
            }else{
                BF()->admin_notices()->add_notice(array(
                    'class' => 'updated',
                    'msg' => __('BetterMag social counter options successfully moved to <b>Better Social Counter</b> plugin options.', 'better-studio') . ' <a href="' . admin_url('themes.php?page=install-required-plugins') . '"><i>' . __('Active/Install Better Social Counter Here', 'better-studio') . '</i></a>'
                ));
            }
        }

    } // before_v_1_4_comp


    /**
     * Prepare compatibility for versions before 2
     */
    function before_v_2_comp(){

        // clear backend custom css
        delete_transient( '__better_framework__backend_css' );

        global $wpdb;

        // Update option for theme options
        $theme_options = get_option( '__better_mag__theme_options' );
        if( $theme_options !== false ){

            if( $theme_options['style'] == 'clean' || $theme_options['style'] == 'clean-beige' ){
                $theme_options['style'] = 'default';
                BF()->options()->add_option( '__better_mag__theme_options_current_style', 'default' );

                $std_id = Better_Framework::options()->get_std_field_id( '__better_mag__theme_options' );

                foreach( (array) Better_Framework::options()->options['__better_mag__theme_options']['fields'] as $field ){

                    // Not save if field have style filter
                    if( ! isset( $field['style'] ) || ! in_array( 'default', $field['style'] ) )  continue;

                    // If field have std value then change current value std std value
                    if( isset( $field[$std_id] ) ){
                        $theme_options[$field['id']] = $field[$std_id];
                    }elseif( isset( $field['std'] ) ){
                        $theme_options[$field['id']] = $field['std'];
                    }

                }
                BF()->factory( 'custom-css-fe' )->clear_cache( 'all' );
            }
            update_option( '__better_mag__theme_options', $theme_options );

            $theme_options = get_option( '__better_mag__theme_options' );

            if( isset( $theme_options['show_slider'] ) ){

                if( $theme_options['show_slider'] == '0' ){
                    $theme_options['show_slider'] = 'no';
                }elseif( $theme_options['show_slider'] == '1' ){
                    $theme_options['show_slider'] = 'better';
                }

                update_option( '__better_mag__theme_options', $theme_options );
            }

        }

        // Update show slider for posts and pages
        $wpdb->query( "
            UPDATE $wpdb->postmeta
            SET meta_value='no'
            WHERE meta_key='_show_slider' AND meta_value='0'
        " );
        $wpdb->query( "
            UPDATE $wpdb->postmeta
            SET meta_value='better'
            WHERE meta_key='_show_slider' AND meta_value='1'
        " );

        // Update option for categories
        foreach( (array) get_categories( array( 'hide_empty' => 0 ) ) as $cat ){

            $cat_meta = get_option( 'bf_term_' . $cat->cat_ID );

            if( $cat_meta !== false ){

                if( ! isset( $cat_meta['show_slider'] ) ) continue;

                if( $cat_meta['show_slider'] == '0' )
                    $cat_meta['show_slider'] = 'no';
                elseif( $cat_meta['show_slider'] == '1' )
                    $cat_meta['show_slider'] = 'better';

                update_option( 'bf_term_' . $cat->cat_ID, $cat_meta );
            }

        }

        // Update option for tags
        foreach( (array) get_tags( array( 'hide_empty' => 0 ) ) as $tag ){

            $tag_meta = get_option( 'bf_term_' . $tag->term_id );

            if( $tag_meta !== false ){

                if( ! isset( $tag_meta['show_slider'] ) ) continue;

                if( $tag_meta['show_slider'] == '0' )
                    $tag_meta['show_slider'] = 'no';
                elseif( $tag_meta['show_slider'] == '1' )
                    $tag_meta['show_slider'] = 'better';

                update_option( 'bf_term_' . $tag->term_id, $tag_meta );
            }

        }

    } // before_v_2_comp


	function before_v2_8_comp(){

		global $wpdb, $wp_version;

		if ( version_compare( $wp_version, '4.4.0', '<' ) ) {
			return FALSE;
		}

		$SQL = 'SELECT * FROM ' . $wpdb->options . ' WHERE option_name LIKE \'bf_term_%\'';

		foreach ( $wpdb->get_results( $SQL ) as $option ) {
			if ( preg_match( '/^bf_term_(\d+)$/i', $option->option_name, $match ) ) {
				$term_id = &$match[1];
				if ( $all_meta = maybe_unserialize( $option->option_value ) ) {
					foreach ( $all_meta as $meta_key => $meta_value ) {
						add_term_meta( $term_id, $meta_key, $meta_value );
					}
					$wpdb->delete( $wpdb->options, array( 'option_id' => $option->option_id ) );
				}
			}
		}

		return TRUE;
	}
}