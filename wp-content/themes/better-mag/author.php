<?php

/**
 * Archives Page
 *
 * This page is used for all kind of archives from custom post types to blog to 'by date' archives.
 *
 * @link http://codex.wordpress.org/images/1/18/Template_Hierarchy.png
 */

get_header();

?>
<div class="row main-section">
    <?php if( Better_Mag::current_sidebar_layout() == 'left' ) Better_Mag::get_sidebar(); ?>
    <div <?php better_attr( 'main-content', Better_Mag::current_sidebar_layout() ? 'col-lg-8 col-md-8 col-sm-8 col-xs-12 with-sidebar content-column' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 no-sidebar' ); ?>><?php

        //
        // Author information's
        //
        $current_user = bf_get_author_archive_user();
        Better_Mag::generator()->set_attr( 'user-object', $current_user );
        Better_Mag::generator()->set_attr( 'block-class', 'bottom-line' );
        Better_Mag::generator()->set_attr( 'bio-excerpt', false );
        Better_Mag::generator()->blocks()->block_user_row();
        Better_Mag::generator()->unset_attr( 'block-class' );

        //
        // Author posts
        //
        if( have_posts() ){

            get_template_part( Better_Mag::get_page_listing_template() );

            if( BF()->user_meta()->get_meta( 'show_author_pagination', $current_user ) )
                Better_Mag::generator()->blocks()->get_pagination();

        }

        ?></div>
    <?php if( Better_Mag::current_sidebar_layout() == 'right' ) Better_Mag::get_sidebar(); ?>
</div>
<?php get_footer(); ?>