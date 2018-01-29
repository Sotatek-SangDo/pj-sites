<?php

/**
 * BetterFramework Twitter Shortcode
 */
class BF_Twitter_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bf_twitter';

        $this->widget_id = 'twitter';

        $this->name = __( 'Twitter', 'better-studio' );

        $this->description = __( 'Latest tweets widget.', 'better-studio' );

        $this->icon = BETTER_MAG_URI . 'includes/admin-assets/images/vc-twitter.png';

        $_options = array(
            'defaults'  => array(
                'title'             =>  Better_Translation()->_get( 'widget_latest_tweets' ),
                'show_title'        =>  1,
                'style'             =>  'style-1',
                'username'          =>  '',
                'consumer_key'      =>  '',
                'consumer_secret'   =>  '',
                'access_token'      =>  '',
                'access_secret'     =>  '',
                'tweets_count'      =>  4,
                'link_new_page'     =>  1,
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id , $_options );

    }


    /**
     * Retrieve twitter fresh data
     *
     * @param $atts
     * @return array|bool
     */
    function get_twitter_data( $atts ){

        if( ! class_exists( 'OAuthToken' ) )
            require_once BF_PATH .  '/libs/oauth/oauth.php';
        require_once BF_PATH .  '/libs/oauth/twitter-oauth.php';

        $twitterConnection = new TwitterOAuth(
            $atts['consumer_key'],
            $atts['consumer_secret'],
            $atts['access_token'],
            $atts['access_secret']
        );

        $data = $twitterConnection->get( 'statuses/user_timeline', array(
            'screen_name' => $atts['username'] ,
            'count' => $atts['tweets_count'],
            'exclude_replies' => false
        ));

        if ($twitterConnection->http_code === 200) {
            return $data;
        }

        return false;
    }


    /**
     * Wrapper ro getting twitter data with cache mechanism
     *
     * @param $atts
     * @return array|bool|mixed|void
     */
    public function get_data( $atts ){

        $data_store  = 'bf-tww-' . $atts['username'];
        $back_store  = 'bf-tww-bk-' . $atts['username'];

        $cache_time = 60 * 10;

        if( ( $data = get_transient( $data_store ) ) === false ){

            $data = $this->get_twitter_data( $atts );

            if( $data ){

                // save a transient to expire in $cache_time and a permanent backup option ( fallback )
                set_transient( $data_store, $data, $cache_time );
                update_option( $back_store, $data );

            }
            // fall to permanent backup store
            else {
                $data = get_option($back_store);
            }
        }

        return $data;
    }


    /**
     * Generates HTML code for each Tweet
     *
     * @param $tweet
     * @param $atts
     */
    function get_li( $tweet, $atts ){

        echo '<li class="tweet clearfix">';

        switch( $atts['style'] ){

            case 'style-4':

                echo '<a ' . ( $atts['link_new_page'] ? 'target="_blank"' : '' ) . ' href="http://twitter.com/' . $tweet->user->screen_name .'">';
                echo '<span class="user-name">' . $tweet->user->name .'</span></a><span class="sep">-</span>';
                echo '<span class="time" title="' . $tweet->created_at .'">' . human_time_diff(  strtotime( $tweet->created_at ) ) . ' ' . Better_Translation()->_get( 'widget_ago' ) .'</span>';
                echo '<p class="tweet-text" >' . $tweet->text .'</p>';

                break;

            case 'style-3':

                echo '<a ' . ( $atts['link_new_page'] ? 'target="_blank"' : '' ) . ' href="http://twitter.com/' . $tweet->user->screen_name .'">';
                echo '<span class="user-name">' . $tweet->user->name .'</span></a><span class="sep">-</span>';
                echo '<span class="time" title="' . $tweet->created_at .'">' . human_time_diff(  strtotime( $tweet->created_at ) ) . ' ' . Better_Translation()->_get( 'widget_ago' ) .'</span>';
                echo '<p class="tweet-text" >' . $tweet->text .'</p>';
                echo '<ul class="tweet-actions" >
                <li class="action replay"><a href="https://twitter.com/intent/tweet?in_reply_to=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-mail-reply"></i> ' . Better_Translation()->_get( 'widget_reply' ) . '</a></li>
                <li class="action retweet"><a href="https://twitter.com/intent/retweet?tweet_id=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-retweet"></i> ' . Better_Translation()->_get( 'widget_retweet' ) . '</a></li>
                <li class="action favorite"><a href="https://twitter.com/intent/favorite?tweet_id=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-star"></i> ' . Better_Translation()->_get( 'widget_favorite' ) . '</a></li>
                </ul>';

                break;

            case 'style-2':
            case 'style-1':
            default:
                echo '<div class="tweet-header"><a ' . ( $atts['link_new_page'] ? 'target="_blank"' : '' ) . ' href="http://twitter.com/' . $tweet->user->screen_name .'">';
                echo '<img alt="' . $tweet->user->name .'" class="user-profile-image" src="' . $tweet->user->profile_image_url .'">';
                echo '<span class="user-name">' . $tweet->user->name .'</span></a>';
                echo '<span class="time" title="' . $tweet->created_at .'">' . human_time_diff(  strtotime( $tweet->created_at ) ) . ' ' . Better_Translation()->_get( 'widget_ago' ) .'</span></div>';
                echo '<p class="tweet-text" >' . $tweet->text .'</p>';
                echo '<ul class="tweet-actions" >
                <li class="action replay"><a href="https://twitter.com/intent/tweet?in_reply_to=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-mail-reply"></i> ' . Better_Translation()->_get( 'widget_reply' ) . '</a></li>
                <li class="action retweet"><a href="https://twitter.com/intent/retweet?tweet_id=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-retweet"></i> ' . Better_Translation()->_get( 'widget_retweet' ) . '</a></li>
                <li class="action favorite"><a href="https://twitter.com/intent/favorite?tweet_id=' . $tweet->id .'" onclick="window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600\');return false"><i class="fa fa-star"></i> ' . Better_Translation()->_get( 'widget_favorite' ) . '</a></li>
                </ul>';

                break;
        }

        echo '</li>';
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
        <div class="bf-shortcode bf-shortcode-twitter">
            <div class="the-content">
                <?php
                if( ! empty( $atts['username'] ) && ! empty( $atts['consumer_key'] ) && ! empty( $atts['consumer_secret'] ) && ! empty( $atts['access_token'] ) && ! empty( $atts['access_secret'] ) ){
                    $data = $this->get_data( $atts );

                    if( $data != false ){?>
                        <ul class="bf-tweets-list <?php echo $atts['style'] ?>"><?php
                            foreach( $data as $index => $tweet ){

                                if( $index >= $atts['tweets_count'] ){
                                    break;
                                }

                                // todo convert images src to img tag

                                // convert links and @ references
                                if( $atts['link_new_page'] ){
                                    $tweet->text = preg_replace('/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;\'">\:\s\<\>\)\]\!])/', '<a target="_blank" href="\\1">\\1</a>', $tweet->text);
                                    $tweet->text = preg_replace('/\B@([_a-z0-9]+)/i', '<a target="_blank" href="http://twitter.com/\\1">@\\1</a>', $tweet->text);

                                }
                                else{
                                    $tweet->text = preg_replace('/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;\'">\:\s\<\>\)\]\!])/', '<a href="\\1">\\1</a>', $tweet->text);
                                    $tweet->text = preg_replace('/\B@([_a-z0-9]+)/i', '<a href="http://twitter.com/\\1">@\\1</a>', $tweet->text);
                                }

                                $this->get_li( $tweet, $atts );

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
                    "type"          => 'bf_image_radio',
                    "heading"       => __( 'Style', 'better-studio' ),
                    "param_name"    => 'style',
                    'section_class' => 'style-floated-left',
                    "admin_label"   => true,
                    "options"       => array(
                        'style-1' => array(
                            'label'     => __( 'Style 1', 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-1.png',
                        ),
                        'style-2' => array(
                            'label' => __( 'Style 2', 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-2.png',
                        ),
                        'style-3' => array(
                            'label' => __( 'Style 3', 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-3.png',
                        ),
                        'style-4' => array(
                            'label' => __( 'Style 4', 'better-studio' ),
                            'img'       => BETTER_MAG_URI . 'includes/admin-assets/images/widget-twitter-style-4.png',
                        ),
                    ),
                    "value" => $this->defaults['style'],
                ),
                array(
                    'heading'       =>  __( 'Instructions', 'better-studio' ),
                    'param_name'    =>  'help',
                    'type'          =>  'bf_info',
                    'value'         =>  __('
<p>You need to authenticate yourself to Twitter with creating an app for get access information to retrieve your tweets and display them on your page.</p><ol>
    <li>Go to <a href="http://goo.gl/tyCR5W" target="_blank">https://apps.twitter.com/app/new</a> and log in, if necessary</li>
    <li>Enter your Application Name, Description and your website address. You can leave the callback URL empty.</li>
    <li>Submit the form by clicking the Create your Twitter Application</li>
    <li>Go to the "Keys and Access Token" tab and copy the consumer key (API key) and consumer secret</li>
    <li>Paste them in the following input boxes</li>
    <li>Click on the "Create my access token" in bottom of page for creating access token and copy them</li>
    <li>Paste them in the following input boxes</li>
  </ol>
                ', 'better-studio' ),
                    'state'         =>  'open',
                    'info-type'     =>  'help',
                    'section_class' =>  'widefat',
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Username', 'better-studio' ),
                    "param_name"    =>  'username',
                    "value"         =>  $this->defaults['username'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Consumer Key', 'better-studio' ),
                    "param_name"    =>  'consumer_key',
                    "value"         =>  $this->defaults['consumer_key'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Consumer Secret', 'better-studio' ),
                    "param_name"    =>  'consumer_secret',
                    "value"         =>  $this->defaults['consumer_secret'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Access Secret', 'better-studio' ),
                    "param_name"    =>  'access_secret',
                    "value"         =>  $this->defaults['access_secret'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  false,
                    "heading"       =>  __( 'Access Token', 'better-studio' ),
                    "param_name"    =>  'access_token',
                    "value"         =>  $this->defaults['access_token'],
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Tweets', 'better-studio' ),
                    "param_name"    =>  'tweets_count',
                    "value"         =>  $this->defaults['tweets_count'],
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Open Tweet Links in New Page', 'better-studio' ),
                    "param_name"    =>  'link_new_page',
                    "value"         =>  $this->defaults['link_new_page'],
                ),
            )
        ) );

    }
}


class WPBakeryShortCode_bf_twitter extends BF_VC_Shortcode_Extender { }