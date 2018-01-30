<?php

class BM_Login_Register_Shortcode extends BF_Shortcode{

    function __construct( $id, $options ){

        $id = 'bm_login_register';

        $this->widget_id = 'bm login register widget';

        $_options = array(
            'defaults' => array(
                'show_register' => get_option( 'users_can_register' ),
            ),

            'have_widget'       => true,
            'have_vc_add_on'    => false,
        );

        $_options = wp_parse_args( $_options, $options );

        parent::__construct( $id, $_options );

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

        if( is_user_logged_in() ) {
            $login['title'] = Better_Translation()->_get( 'widget_your_profile' );
        }else{
            $login['title'] = Better_Translation()->_get( 'login' );
        }

        $login['id'] = 'login-' . rand( 100, 100000 );
        $login['href'] = '#' . $login['id'];
        $login['active'] = true;
        $tabs['login'] = $login;

        if( ! is_user_logged_in() && $atts['show_register'] == true ){
            $register['title'] = Better_Translation()->_get( 'register' );
            $register['id'] = 'register-' . rand(100, 100000);
            $register['href'] = '#' . $register['id'];
            $tabs['register'] = $register;
        }

        Better_Mag::generator()->blocks()->get_tab_block_title( $tabs );

        ?>
        <div class="bf-shortcode bm-login-register">
            <div class="tab-content">
                <?php

                ?>
                <div class="tab-pane active login-tab clearfix" id="<?php echo $login['id']; ?>"><?php

                    if( is_user_logged_in() ){

                        $current_user = wp_get_current_user();

                        // Links to bbPress profile if bbPress is active
                        if( class_exists('bbpress') ){

                            ?>
                            <section <?php better_attr( 'author' ); ?>>

                                <a href="<?php echo esc_url( get_author_posts_url( $current_user->ID, $current_user->user_nicename ) ); ?>" <?php better_attr( 'author-url' ); ?>>
                                    <span <?php better_attr( 'author-avatar' ); ?> ><?php echo get_avatar( $current_user->ID, 50 ); ?></span>
                                    <span <?php better_attr( 'author-name', ' heading' ); ?>><?php echo get_the_author_meta( 'display_name', $current_user->ID ); ?></span>
                                </a>

                                <a href="<?php echo wp_logout_url(); ?>" title="<?php Better_Translation()->_echo_esc_attr( 'logout' ); ?>"><?php Better_Translation()->_echo( 'logout' ); ?></a>

                            </section>
                            <?php

                            // Normal Links
                        }else{?>

                            <section <?php better_attr( 'author' ); ?>>

                                <a href="<?php echo get_edit_user_link(); ?>" <?php better_attr( 'author-url' ); ?>>
                                    <span <?php better_attr( 'author-avatar' ); ?> ><?php echo get_avatar( $current_user->ID, 50 ); ?></span>
                                    <span <?php better_attr( 'author-name', ' heading' ); ?>><?php echo get_the_author_meta( 'display_name', $current_user->ID ); ?></span>
                                </a>

                                <a href="<?php echo wp_logout_url(); ?>" title="<?php Better_Translation()->_echo_esc_attr( 'logout' ); ?>"><?php Better_Translation()->_echo( 'logout' ); ?></a>

                            </section>

                        <?php }

                    }else{

                        wp_login_form();

                    }

                    ?>
                </div>
                <?php

                if( ! is_user_logged_in() && $atts['show_register'] == true ){

                    ?>
                    <div class="tab-pane register-tab" id="<?php echo $register['id']; ?>">

                        <div class="before-message">
                            <?php echo wpautop( Better_Translation()->_get( 'register_acc_message' ) ); ?>
                        </div>

                        <form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
                            <label for="user_login"><?php Better_Translation()->_echo_esc_attr( 'username' ); ?></label>
                            <input type="text" name="user_login" id="user_login" class="input" />
                            <label for="user_email"><?php Better_Translation()->_echo_esc_attr( 'email' ); ?></label>
                            <input type="text" name="user_email" id="user_email" class="input"  />
                            <?php do_action( 'register_form' ); ?>
                            <input type="submit" value="<?php Better_Translation()->_echo_esc_attr( 'register' ); ?>" id="register" />
                            <p class="statement"><?php Better_Translation()->_echo( 'register_form_message' ); ?></p>
                        </form>

                    </div>
                    <?php

                }

                ?>
            </div>
        </div>
        <?php

        return ob_get_clean();

    }

}