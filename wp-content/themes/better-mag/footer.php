<?php

do_action( 'better-mag/main/end');

?>
</main> <!-- /container -->

<?php

// Custom display large footer for singles
if( is_singular() ){
    $show_large = BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'footer_show_large' ) , Better_Mag::get_option( 'footer_large_active' ) );
}
// Custom display large footer for tags & categories
elseif( is_category() || is_tag() ){

    if( is_category() ){
        $term_id = get_query_var('cat');
    }else{
        $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
        $term_id = $tag->term_id;
    }

    $show_large = BF()->taxonomy_meta()->get_term_meta( $term_id, 'footer_show_large' );
    if( $show_large == 'default' ){
        $show_large = Better_Mag::get_option( 'footer_large_active' );
    }
    elseif( $show_large  ){
        $show_large = BM_Helper::result_of_meta_and_option( $show_large , Better_Mag::get_option( 'footer_lower_active' ) );
    }else{
        $show_large = false;
    }
}
// Show large footer for authors
elseif( is_author() ){

    $current_user = bf_get_author_archive_user();

    $show_large = BM_Helper::result_of_meta_and_option( BF()->user_meta()->get_meta( 'footer_show_large', $current_user ), Better_Mag::get_option( 'footer_large_active' ) );

}
else{
    $show_large = Better_Mag::get_option( 'footer_large_active' );
}

if( $show_large ){ ?>
<footer <?php better_attr( 'footer', 'footer-larger-wrapper', 'large' ); ?>>
    <div class="container">
        <div class="row">
            <?php

            switch( Better_Mag::get_option( 'footer_large_columns' ) ){

                case 2: ?>
                    <aside class="col-lg-6 col-md-6 col-sm-6 col-xs-12 footer-aside footer-aside-1">
                        <?php dynamic_sidebar( 'footer-column-1' ); ?>
                    </aside>
                    <aside class="col-lg-6 col-md-6 col-sm-6 col-xs-12 footer-aside footer-aside-2">
                        <?php dynamic_sidebar( 'footer-column-2' ); ?>
                    </aside>
                <?php
                    break;

                case 3: ?>
                    <aside class="col-lg-4 col-md-4 col-sm-4 col-xs-12 footer-aside footer-aside-1">
                        <?php dynamic_sidebar( 'footer-column-1' ); ?>
                    </aside>
                    <aside class="col-lg-4 col-md-4 col-sm-4 col-xs-12 footer-aside footer-aside-2">
                        <?php dynamic_sidebar( 'footer-column-2' ); ?>
                    </aside>
                    <aside class="col-lg-4 col-md-4 col-sm-4 col-xs-12 footer-aside footer-aside-3">
                        <?php dynamic_sidebar( 'footer-column-3' ); ?>
                    </aside>
                <?php
                    break;

                case 4: ?>
                    <aside class="col-lg-3 col-md-3 col-sm-6 col-xs-12 footer-aside footer-aside-1">
                        <?php dynamic_sidebar( 'footer-column-1' ); ?>
                    </aside>
                    <aside class="col-lg-3 col-md-3 col-sm-6 col-xs-12 footer-aside footer-aside-2">
                        <?php dynamic_sidebar( 'footer-column-2' ); ?>
                    </aside>
                    <aside class="col-lg-3 col-md-3 col-sm-6 col-xs-12 footer-aside footer-aside-3">
                        <?php dynamic_sidebar( 'footer-column-3' ); ?>
                    </aside>
                    <aside class="col-lg-3 col-md-3 col-sm-6 col-xs-12 footer-aside footer-aside-4">
                        <?php dynamic_sidebar( 'footer-column-4' ); ?>
                    </aside>
                <?php
                    break;
            }

            ?>
        </div>
    </div>
</footer>
<?php }

// Custom display lower footer for singles
if( is_singular() ){
    $show_lower = BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'footer_show_lower' ) , Better_Mag::get_option( 'footer_lower_active' ) );
}
// Custom display lower footer for categories & tags
elseif( is_category() || is_tag() ){

    if( is_category() ){
        $term_id = get_query_var('cat');
    }else{
        $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
        $term_id = $tag->term_id;
    }

    $show_lower = BF()->taxonomy_meta()->get_term_meta( $term_id, 'footer_show_lower' );
    if( $show_lower == 'default' ){
        $show_lower = Better_Mag::get_option( 'footer_lower_active' );
    }
    elseif( $show_lower  ){
        $show_lower = BM_Helper::result_of_meta_and_option( $show_lower , Better_Mag::get_option( 'footer_lower_active' ) );
    }else{
        $show_lower = Better_Mag::get_option( 'footer_lower_active' );
    }
}
// Show lower footer for authors
elseif( is_author() ){
    $show_lower = BM_Helper::result_of_meta_and_option( BF()->user_meta()->get_meta( 'footer_show_lower', $current_user ), Better_Mag::get_option( 'footer_lower_active' ) );
}
else{
    $show_lower = Better_Mag::get_option( 'footer_lower_active' );
}

if( $show_lower ){ ?>
<footer <?php better_attr( 'footer', 'footer-lower-wrapper', 'lower' ); ?>>
    <div class="container">
        <div class="row">
            <aside class="col-lg-6 col-md-6 col-sm-6 col-xs-12 lower-footer-aside lower-footer-aside-1">
                <?php dynamic_sidebar( 'footer-lower-left-column' ); ?>
            </aside>
            <aside class="col-lg-6 col-md-6 col-sm-6 col-xs-12 lower-footer-aside lower-footer-aside-2">
                <?php dynamic_sidebar( 'footer-lower-right-column' ); ?>
            </aside>
        </div>
    </div>
</footer><?php
}

if( Better_Mag::get_option( 'back_to_top' ) ){?>
<span class="back-top"><i class="fa fa-chevron-up"></i></span>
<?php }

?>
</div> <!-- /main-wrap -->
<?php wp_footer(); // WordPress hook for loading JavaScript, toolbar, and other things in the footer. ?>
</body>
</html>