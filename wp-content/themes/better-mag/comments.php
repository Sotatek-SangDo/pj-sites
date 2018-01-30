<?php if( post_password_required() ): ?>
    <p class="nocomments"><?php Better_Translation()->_echo( 'enter_pass_to_see_comment' ); ?></p>
    <?php return; endif;?>

<div id="comments"><?php

    $comments_arg = array(
        'title_reply'           =>  Better_Mag::generator()->blocks()->get_block_title( Better_Translation()->_get( 'comments_leave_reply' ), false, false  ),
        'title_reply_to'        =>  Better_Mag::generator()->blocks()->get_block_title( Better_Translation()->_get( 'comments_reply_to' ), false, false  ),
        'comment_notes_before'  => wpautop( Better_Translation()->_get( 'comment_notes_before' ) ),
        'comment_notes_after'  => wpautop( Better_Translation()->_get( 'comment_notes_after' ) ),

        'logged_in_as'          => '<p class="logged-in-as">' . sprintf( Better_Translation()->_get( 'comments_logged_as' ) . ' <a href="%1$s">%2$s</a>. <a href="%3$s" title="' . Better_Translation()->_get_esc_attr( 'comments_logout_this' ) . '">' . Better_Translation()->_get( 'comments_logout' ) . '</a>' ,
                admin_url('profile.php'), $user_identity, wp_logout_url( get_permalink() ) ) . '</p>',

        'comment_field'         =>  '<p><textarea name="comment" id="comment" cols="45" rows="10" aria-required="true" placeholder="'. Better_Translation()->_get_esc_attr( 'comments_your_comment' ) .'"></textarea></p>',
        'id_submit'             => 'comment-submit',
        'label_submit'          =>  Better_Translation()->_get_esc_attr( 'comments_post_comment' ),
        'cancel_reply_link'     =>  Better_Translation()->_get_esc_attr( 'comments_cancel_reply' ),
        'fields'                => array(
            'author'    =>  '<p><input name="author" id="author" type="text" value="" size="45" aria-required="true" placeholder="'. Better_Translation()->_get_esc_attr( 'comments_your_name' ) .'" /></p>',
            'email'     =>  '<p><input name="email" id="email" type="text" value="" size="45" aria-required="true" placeholder="'. Better_Translation()->_get_esc_attr( 'comments_your_email' ) .'" /></p>',
        ),
    );


    // Removes url field from form
    if( ! Better_Mag::get_option( 'comment_form_remove_url' ) ){

        $comments_arg['fields']['url'] = '<p><input name="url" id="url" type="text" value="" size="45" placeholder="'. Better_Translation()->_get_esc_attr( 'comments_your_website' ) .'" /></p>';

    }

    // Comment form in top
    if( Better_Mag::get_option( 'comment_form_position' ) == 'top' || Better_Mag::get_option( 'comment_form_position' ) == 'both' ){
        comment_form( $comments_arg );
    }


    if( have_comments() ): ?>
        <?php

        $num_comments = get_comments_number();

        if( $num_comments == 0 ){
            $comments_text = Better_Translation()->_get( 'no_comment_title' );
        } elseif ( $num_comments > 1 ) {
            $comments_text = str_replace('%', number_format_i18n( $num_comments ), Better_Translation()->_get( 'comments_count_title' ) );
        } else {
            $comments_text = Better_Translation()->_get( 'comments_1_comment' );
        }

        Better_Mag::generator()->blocks()->get_block_title( $comments_text, false, true, '', 'itemprop="commentCount"' ); ?>

        <ol class="comments-list">
            <?php

            wp_list_comments( array( 'callback' => 'better_mag_comment' ) );

            ?>
        </ol>

        <?php if( get_comment_pages_count() > 1 && get_option('page_comments') ): // are there comments to navigate through ?>
            <nav class="comment-nav">
                <div class="nav-previous"><?php previous_comments_link( Better_Translation()->_get( 'comments_older' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( Better_Translation()->_get( 'comments_newer' ) ); ?></div>
            </nav>
        <?php endif; // check for comment navigation ?>

    <?php elseif( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments') ): ?>
        <p class="nocomments"><?php Better_Translation()->_echo( 'comments_closed' ); ?></p>
    <?php endif;

    // Comment form in bottom
    if( Better_Mag::get_option( 'comment_form_position' ) == 'bottom' || Better_Mag::get_option( 'comment_form_position' ) == 'both' ){
        comment_form( $comments_arg );
    }

    ?>
</div><!-- #comments -->
