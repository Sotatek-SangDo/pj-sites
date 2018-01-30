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

        // Categories & Tags
        if( is_category() || is_tag() ){

            if( is_category() ){
                $term_id = get_query_var( 'cat' );
            }else{
                $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
                $term_id = $tag->term_id;
            }

            // Term Title
            if( ! BF()->taxonomy_meta()->get_term_meta( $term_id, 'hide_term_title' ) ){

                echo '<h1 class="page-heading"><span class="h-title">';

                // Custom title
                if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'term_custom_title' ) != '' ){
                    echo BF()->taxonomy_meta()->get_term_meta( $term_id, 'term_custom_title' );
                }else{
                    echo sprintf( Better_Translation()->_get( 'archive_cat_title' ), '<i>' . single_cat_title( '', false )  . '</i>' );
                }

                echo '</span>';

                if( is_category() ){

                    $show_rss = BM_Helper::result_of_meta_and_option( BF()->taxonomy_meta()->get_term_meta( $term_id, 'show_rss_link' ), Better_Mag::get_option( 'archive_cat_show_rss' ) );

                    if( $show_rss )
                        echo '<a class="rss-link" href="' . get_category_feed_link( $term_id ) .'"><i class="fa fa-rss"></i></a>';

                }
                elseif( is_tag() ){

                    $show_rss = BM_Helper::result_of_meta_and_option( BF()->taxonomy_meta()->get_term_meta( $term_id, 'show_rss_link' ), Better_Mag::get_option( 'archive_tag_show_rss' ) );

                    if( $show_rss )
                        echo '<a class="rss-link" href="' . get_tag_feed_link( $term_id ) .'"><i class="fa fa-rss"></i></a>';

                }

                echo '</h1>';
            }

            // Term Description
            if( ! BF()->taxonomy_meta()->get_term_meta( $term_id, 'hide_term_description' ) && term_description() ){
                echo Better_Mag::generator()->blocks()->get_block_desc( do_shortcode( term_description() ) );
            }

        }

        // Custom Taxonomy Terms Page
        elseif( is_tax() ){
            Better_Mag::generator()->blocks()->get_page_title( sprintf( Better_Translation()->_get( 'archive_tax_title' ), '<i>' . single_term_title( '', false )  . '</i>' ), false, true, 'h1' );

            if( term_description() ){
                echo Better_Mag::generator()->blocks()->get_block_desc( do_shortcode(term_description()) );
            }
        }

        // Search Page
        elseif( is_search() ){
            Better_Mag::generator()->blocks()->get_page_title( sprintf( Better_Translation()->_get( 'archive_search_title' ), '<i>' . get_search_query() . '</i>', '<span class="result-count">' . $wp_query->found_posts . '</span>' ), false, true, 'h1' );
        }

        // Daily Archive
        elseif( is_day() ){
            Better_Mag::generator()->blocks()->get_page_title( sprintf( Better_Translation()->_get( 'archive_daily_title' ), '<i>' . get_the_date() . '</i>' ), false, true, 'h1' );
        }

        // Monthly Archive
        elseif( is_month() ){
            Better_Mag::generator()->blocks()->get_page_title( sprintf( Better_Translation()->_get( 'archive_monthly_title' ), '<i>' . get_the_date( Better_Translation()->_get( 'archive_monthly_format' ) ) . '</i>' ), false, true, 'h1' );
        }

        // Yearly Archive
        elseif( is_year() ){
            Better_Mag::generator()->blocks()->get_page_title( sprintf( Better_Translation()->_get( 'archive_yearly_title' ), '<i>' . get_the_date( Better_Translation()->_get( 'archive_year_format' ) ) . '</i>' ), false, true, 'h1' );
        }


        // Main query
        if( have_posts() ){

            get_template_part( Better_Mag::get_page_listing_template() );

            if( is_category() ){

                if( BF()->taxonomy_meta()->get_term_meta( get_query_var('cat'), 'show_term_pagination' ) )
                    Better_Mag::generator()->blocks()->get_pagination();

            }else{

                Better_Mag::generator()->blocks()->get_pagination();

            }

        }
        elseif( is_search() ){ ?>

            <article class="post-0">
                <h2 class="title"><?php Better_Translation()->_echo( 'nothing_found' ); ?></h2>
                <div class="the-content">
                    <p><?php Better_Translation()->_echo( 'search_nothing_found_message' ); ?></p>
                </div>
            </article><?php

        }
        else{ ?>

            <article class="post-0">
                <h2 class="title"><?php Better_Translation()->_echo( 'nothing_found' ); ?></h2>
                <div class="the-content">
                    <p><?php Better_Translation()->_echo( 'nothing_found_message' ); ?></p>
                </div>
            </article><?php

        }

        // Categories & Tags
        if( is_category() || is_tag() ){

            if( is_category() ){
                $term_id = get_query_var( 'cat' );
            } else {
                $tag = get_term_by( 'slug', get_query_var( 'tag' ), 'post_tag' );
                $term_id = $tag->term_id;
            }

            if( BF()->taxonomy_meta()->get_term_meta( $term_id, 'bottom_description' ) != '' )
                echo '<div class="term-bottom-description">' . wpautop( BF()->taxonomy_meta()->get_term_meta( $term_id, 'bottom_description' ) ) . '</div>';

        }

    ?></div>
    <?php if( Better_Mag::current_sidebar_layout() == 'right' ) Better_Mag::get_sidebar(); ?>
</div>
<?php get_footer(); ?>