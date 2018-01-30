<?php
get_header();

the_post();

?>
<div class="row main-section">
    <?php if( Better_Mag::current_sidebar_layout() == 'left' ) Better_Mag::get_sidebar(); ?>
    <div <?php better_attr( 'main-content', Better_Mag::current_sidebar_layout() ? 'col-lg-8 col-md-8 col-sm-8 col-xs-12 with-sidebar content-column' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 no-sidebar' ); ?>>

        <article <?php better_attr( 'post', Better_Mag::generator()->get_attr_class( 'single-content' ) ); ?>>

            <?php if( ! Better_Mag::get_meta( 'hide_page_title', false ) ){ ?>
            <h1 <?php better_attr('post-title', 'page-heading') ?>><span class="h-title"><?php the_title(); ?></span></h1><?php
            }

            $share_box = Better_Mag::get_meta( 'social_share', 'hide' );

            if( $share_box != 'hide' && ( $share_box == 'top' || $share_box == 'bottom-top' ) ){
                Better_Mag::generator()->blocks()->partial_share_box( true, array( 'class' => 'top-location' ) );
            }

            Better_Mag::posts()->the_content( null, false, '', 'propname="articleBody"' );


            if( $share_box != 'hide' && ( $share_box == 'bottom' || $share_box == 'bottom-top' ) ){
                Better_Mag::generator()->blocks()->partial_share_box( true, array( 'class' => 'bottom-location' ) );
            }

            ?>
        </article>
        <?php

        if( BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_comments', 'default' ), Better_Mag::get_option( 'content_show_comments_pages' ) ) ){
            ?>
            <div class="comments">
            <?php comments_template( '', true ); ?>
            </div><?php
        }

        ?>
    </div>
    <?php if( Better_Mag::current_sidebar_layout() == 'right' ) Better_Mag::get_sidebar(); ?>
</div>
<?php get_footer(); ?>