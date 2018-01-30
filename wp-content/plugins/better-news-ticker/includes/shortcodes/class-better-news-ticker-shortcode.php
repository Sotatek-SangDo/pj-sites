<?php

/**
 * Better News Ticker Shortcode
 */
class Better_News_Ticker_Shortcode extends BF_Shortcode{

    function __construct( $id , $options ){

        $id = 'better-news-ticker';

        $this->name = __( 'Better News Ticker', 'better-studio' );

        $this->description = __( 'BetterStudio News Ticker Add-on', 'better-studio' );

        $this->icon = Better_News_Ticker::dir_url( 'img/vc-newsticker.png' );

        $options = array_merge( array(
            'defaults'  => array(
                'title'         => __( 'Trending', 'better-studio'),
                'show_title'    => 0,
                'ticker_text'   => __( 'Trending' , 'better-studio' ),
                'speed'         => 12,
                'count'         => 10,
                'cat'           => '',
                'tag'           => '',
            ),
            'have_widget'   => true,
            'have_vc_add_on'=> true,
        ), $options );

        parent::__construct( $id , $options );

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
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map( array(
            "name"          =>  $this->name,
            "base"          =>  $this->id,
            "icon"          =>  $this->icon,
            "description"   =>  $this->description,
            "weight"        =>  1,
            "php_class_name"=>  'WPBakeryShortCode_better_news_ticker',

            "wrapper_height"=>  'full',

            "category"      =>  __( 'Content', 'better-studio' ),
            "params"        => array(

                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Title', 'better-studio' ),
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
                    "heading"       =>  __( 'News Ticker Text', 'better-studio' ),
                    "param_name"    =>  'ticker_text',
                    "value"         =>  $this->defaults['ticker_text'],
                ),
                array(
                    "type"          =>  'bf_slider',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Speed', 'better-studio' ),
                    "param_name"    =>  'speed',
                    "value"         =>  $this->defaults['speed'],
                    'dimension'     =>  'second',
                    'min'           =>  '3',
                    'max'           =>  '60',
                    'step'          =>  '1',
                    'std'           =>  '15',
                    'description'          =>  __( 'Set the speed of the ticker cycling, in second.', 'better-studio' ),
                ),
                array(
                    "type"          =>  'bf_select',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Category', 'better-studio' ),
                    "param_name"    =>  'cat',
                    "value"         =>  $this->defaults['cat'],
                    "options"       =>  array( 'all' => __( 'All Posts', 'better-studio' ) ) + BF_Query::get_categories(),
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Posts', 'better-studio' ),
                    "param_name"    =>  'count',
                    "value"         =>  $this->defaults['count'],
                ),
            )
        ) );

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
        $class = '';

        if( $atts['cat'] != 'all' && ! empty( $atts['cat'] ))
            $class = 'term-' . $atts['cat'];

        if( $atts['title'] && ! BF()->widget_manager()->get_current_sidebar() && $atts['show_title']){
            $atts['element-type'] = $this->id;
            echo apply_filters( 'better-framework/shortcodes/title', $atts );
        }

        if( intval( $atts['count'] ) <= 0 || empty( $atts['count'] ) ){
            $atts['count'] = 10;
        }

        ?>
        <div class="bf-shortcode bf-news-ticker <?php echo $class; ?>" data-time="<?php echo intval( $atts['speed'] ) * 1000; ?>">
            <p class="heading "><?php echo $atts['ticker_text']; ?></p>
            <ul class="news-list">
                <?php
                $bf_newsticker_args = array(
                    'posts_per_page'    =>  $atts['count'],
                    'post_type'         => 'post'
                );
                if( $atts['cat'] != 'all' && ! empty( $atts['cat'] )){
                    $bf_newsticker_args['cat'] = $atts['cat'];
                }

                $news_ticker_query = new WP_Query( apply_filters( 'better-news-ticker/query/args', $bf_newsticker_args ) );

                if( $news_ticker_query->have_posts() ){
                    while( $news_ticker_query->have_posts() ){ $news_ticker_query->the_post(); ?>
                <li><a class="limit-line ellipsis" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php
                    }
                }else{ ?>
                <li class="limit-line ellipsis"> ... </li>
                <?php } ?>
            </ul>
        </div>
        <?php
        wp_reset_query();
        return ob_get_clean();
    }
}

class WPBakeryShortCode_better_news_ticker extends BF_VC_Shortcode_Extender { }