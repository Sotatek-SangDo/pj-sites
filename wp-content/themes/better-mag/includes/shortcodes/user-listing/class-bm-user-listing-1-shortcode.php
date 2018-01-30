<?php

/**
 * BetterMag User Listing 1
 */
class BM_User_Listing_1_Shortcode extends BF_Shortcode {

    function __construct(){

        $id = 'bm_user_listing_1';

        $this->name = __( 'User Listing 1', 'better-studio' );

        $this->description = __( '', 'js_composer' );

        $this->icon = ( BETTER_MAG_ADMIN_ASSETS_URI . 'images/vc-' . $id . '.png' );

        $options = array(
            'defaults' => array(

                'title'             =>  Better_Translation()->_get( 'widget_authors' ),
                'hide_title'        =>  0,
                'icon'              =>  'fa-users',
                'order'             =>  'ASC',
                'orderby'           =>  'ID',
                'role'              =>  'Author',
                'column'            =>  '2',
                'count'             =>  5,
                'show_total'        =>  0,
                'include'           =>  '',
                'exclude'           =>  '',
                'show_posts_count'  =>  0,
                'show_pagination'   =>  0,
                'bio'               =>  'excerpt',
            ),

            'have_widget'       => false,
            'have_vc_add_on'    => true,
        );

        parent::__construct($id, $options);

    }


    /**
     * Used for showing listing title
     */
    function the_block_title( &$atts, &$user_query ){

        if( $atts['hide_title'] ){
            return false;
        }

        $atts['title-class'] = '';

        // Add icon
        if( $atts['icon'] )
            $atts['icon'] = bf_get_icon_tag( $atts['icon'] ) . ' ';
        else
            $atts['icon'] = '';

        $other_links = '';

        if( $atts['show_total'] ){
            $other_links = ' <span class="total-count">' . Better_Translation()->_get( 'widget_total' ) . ': ' . $user_query->total_users . '</span>' ;
        }

        Better_Mag::generator()->blocks()->get_page_title( $atts['icon'] . $atts['title'] . $other_links, false, true, 'h4', '' );

    }



    /**
     * Handle displaying of shortcode
     *
     * @param $atts
     * @param $content
     * @return string
     */
    function display(array $atts, $content = ''){

        ob_start();

        $_user_query_args = array(
            'orderby'   =>  $atts['orderby'],
            'order'   =>  $atts['order'],
            'number'    =>  $atts['count']
        );

        if( $atts['role'] != 'read' ){
            $_user_query_args['role'] = $atts['role'];
        }

        if( $atts['show_total'] ){
            $_user_query_args['count_total'] = true;
        }

        if( $atts['exclude'] ){
            $_user_query_args['exclude'] = explode( ',', $atts['exclude'] );
        }

        if( $atts['include'] ){
            $_user_query_args['include'] = explode( ',', $atts['include'] );
        }

        if( $atts['show_pagination'] ){
            $_user_query_args['paged'] = get_query_var('paged') ? get_query_var('paged') : ( get_query_var('page') ? get_query_var('page') : 1 );

            $_user_query_args['offset'] = $atts['count'] * ( $_user_query_args['paged'] - 1 );
        }

        $_user_query = new WP_User_Query( apply_filters( 'better-mag/user-listing-1/args', $_user_query_args ) );

        if( $atts['bio'] == 'full' ){
            Better_Mag::generator()->set_attr( 'bio-excerpt', false );
        }else{
            Better_Mag::generator()->set_attr( 'bio-excerpt', true );
        }

        ?>
        <div class="user-listing user-listing-1 columns-<?php echo $atts['column']; ?>  clearfix">
        <?php

            echo '<div class="title-row">';
            $this->the_block_title( $atts, $_user_query );
            echo '</div>';

            if( $atts['show_posts_count'] ){
                Better_Mag::generator()->set_attr( 'user-show-post-count', true );
            }

            if( ! empty( $_user_query->results ) ){

                foreach( $_user_query->results as $user ){

                    Better_Mag::generator()->set_attr( 'user-object', $user );

                    Better_Mag::generator()->blocks()->block_user_row();

                }

            }


        if( $atts['show_pagination'] ){
            Better_Mag::generator()->blocks()->get_pagination( array( 'users_per_page' => $atts['count'] ), $_user_query );
        }
        ?>
        </div>
        <?php
        Better_Mag::posts()->clear_query();
        Better_Mag::generator()->clear_atts();

        return ob_get_clean();
    }

    /**
     * Registers Visual Composer Add-on
     */
    function register_vc_add_on(){

        vc_map(array(
            "name"      => $this->name,
            "base"      => $this->id,
            "icon"      => $this->icon,
            "description"      => $this->description,
            "weight"    => 10,
            "wrapper_height"    => 'full',
            "category" => __( 'BetterMag Addons', 'better-studio' ),
            "params" => array(
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Number of Users', 'better-studio' ),
                    "param_name"    =>  'count',
                    "value"         =>  $this->defaults['count'],
                    "description"   =>  __( 'Configures number of users to show. default is 5.', 'better-studio' )
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Show Each User Posts Count', 'better-studio'),
                    "param_name"=>  'show_posts_count',
                    "value"     =>  $this->defaults['show_posts_count'],
                    "description"   => __( 'Shows each user posts count.', 'better-studio'),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Show All Users Count', 'better-studio'),
                    "param_name"=>  'show_total',
                    "value"     =>  $this->defaults['show_total'],
                    "description"   => __( 'Show all users count number with current conditions.', 'better-studio'),
                ),
                array(
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'Order by', 'better-studio' ),
                    "param_name"    => 'orderby',
                    "value"         => $this->defaults['orderby'],
                    "options"       => array(
                        'ID'            =>  __( 'ID', 'better-studio' ),
                        'post_count'    =>  __( 'Post Count', 'better-studio' ),
                        'display_name'  =>  __( 'Display Name', 'better-studio' ),
                        'name'          =>  __( 'User Name', 'better-studio' ),
                        'nicename'      =>  __( 'Nicename', 'better-studio' ),
                        'email'         =>  __( 'Email', 'better-studio' ),
                        'rand'          =>  __( 'Random', 'better-studio' ),
                    ),
                ),
                array(
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'Order', 'better-studio' ),
                    "param_name"    => 'order',
                    "value"         => $this->defaults['order'],
                    "options"       => array(
                        'ASC'           =>  __( 'ASC', 'better-studio' ),
                        'DESC'          =>  __( 'DESC', 'better-studio' ),
                    ),
                ),
                array(
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'User Role', 'better-studio' ),
                    "param_name"    => 'role',
                    "value"         => $this->defaults['role'],
                    "options"       => array(
                        'read'          =>  __( 'All',      'better-studio' ),
                        'Super Admin'   =>  __( 'Super Admin',      'better-studio' ),
                        'Administrator' =>  __( 'Administrator',    'better-studio' ),
                        'Editor'        =>  __( 'Editor',           'better-studio' ),
                        'Author'        =>  __( 'Author',           'better-studio' ),
                        'Contributor'   =>  __( 'Contributor',      'better-studio' ),
                        'Subscriber'    =>  __( 'Subscriber',       'better-studio' ),
                    ),
                ),
                array(
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'Columns', 'better-studio' ),
                    "param_name"    => 'column',
                    "value"         => $this->defaults['column'],
                    "options"       => array(
                        '1'             =>  __( '1 Column', 'better-studio' ),
                        '2'             =>  __( '2 Column', 'better-studio' ),
                        '3'             =>  __( '3 Column', 'better-studio' ),
                    ),
                ),
                array(
                    "type"          => 'bf_select',
                    "admin_label"   => true,
                    "heading"       => __( 'Biography', 'better-studio' ),
                    "param_name"    => 'bio',
                    "value"         => $this->defaults['bio'],
                    "options"       => array(
                        'full'          =>  __( 'Full', 'better-studio' ),
                        'excerpt'       =>  __( 'Excerpt', 'better-studio' ),
                    ),
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Heading', 'better-studio' ),
                    "param_name"    =>  'title',
                    "value"         =>  $this->defaults['title'],
                ),
                array(
                    "type"          =>  'bf_icon_select',
                    "heading"       =>  __( 'Heading Icon (Optional)', 'better-studio' ),
                    "param_name"    =>  'icon',
                    "admin_label"   =>  true,
                    "value"         =>  $this->defaults['icon'],
                    "description"   =>  __( 'Select custom icon for listing.', 'better-studio' ),
                ),
                array(
                    "type"      =>  'bf_switchery',
                    "heading"   =>  __( 'Hide listing Heading?', 'better-studio'),
                    "param_name"=>  'hide_title',
                    "value"     =>  $this->defaults['hide_title'],
                    'section_class' =>  'style-floated-left bordered',
                    "description"   => __( 'You can hide listing heading with turning on this field.', 'better-studio'),
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Exclude Users', 'better-studio' ),
                    "param_name"    =>  'exclude',
                    "value"         =>  $this->defaults['exclude'],
                    "description"   =>  __( 'Separate users IDs with comma ( , ) for excluding them from result.', 'better-studio' )
                ),
                array(
                    "type"          =>  'textfield',
                    "admin_label"   =>  true,
                    "heading"       =>  __( 'Include Users', 'better-studio' ),
                    "param_name"    =>  'include',
                    "value"         =>  $this->defaults['include'],
                    "description"   =>  __( 'Separate users IDs with comma ( , ) for including them from result.', 'better-studio' )
                ),
                array(
                    "type"          =>  'bf_switchery',
                    "heading"       =>  __( 'Show Pagination?', 'better-studio' ),
                    "param_name"    =>  'show_pagination',
                    "value"         =>  $this->defaults['show_pagination'],
                ),
            )
        ));
    }
}

class WPBakeryShortCode_bm_user_listing_1 extends BM_VC_Shortcode_Extender { }
