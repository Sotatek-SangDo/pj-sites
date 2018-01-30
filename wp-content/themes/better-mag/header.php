<!DOCTYPE html>
<!--[if IE 8]> <html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]> <html class="ie ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>

    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
    <![endif]-->
</head>
<body <?php better_attr( 'body' ); ?>>
<div class="main-wrap"><?php

// Show top bar for posts and pages
if( is_singular() ){
    $show_topbar = BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'header_show_topbar' ) , ! Better_Mag::get_option( 'disable_top_bar' ) );
}
// Show top bar for categories
elseif( is_category() || is_tag() ){

    if( is_category() ){
        $term_id = get_query_var( 'cat' );
    }else{
        $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
        $term_id = $tag->term_id;
    }

    $show_topbar = BF()->taxonomy_meta()->get_term_meta( $term_id, 'header_show_topbar' );

    if( $show_topbar != 'default' ){
        $show_topbar = BM_Helper::result_of_meta_and_option( $show_topbar, ! Better_Mag::get_option( 'disable_top_bar' ) );
    }
    else{
        $show_topbar = ! Better_Mag::get_option( 'disable_top_bar' );
    }

}
// Show topbar for authors
elseif( is_author() ){

    $current_user = bf_get_author_archive_user();

    $show_topbar = BM_Helper::result_of_meta_and_option( BF()->user_meta()->get_meta( 'header_show_topbar', $current_user ), ! Better_Mag::get_option( 'disable_top_bar' ) );

}
else{
    $show_topbar = ! Better_Mag::get_option( 'disable_top_bar' );
}

if( $show_topbar ){ // if topbar is active ?>
<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12 top-bar-left clearfix">
                <?php dynamic_sidebar('top-bar-left'); ?>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12 top-bar-right clearfix">
                <?php dynamic_sidebar('top-bar-right'); ?>
            </div>
        </div>
    </div>
</div>
<?php }

// Show header for posts and pages
if( is_singular() ){
    $show_header = Better_Mag::get_meta( 'header_show_header' );
    if( $show_header != 'show' ){
        $show_header = false;
    }
}
// Show header for categories & tags
elseif( is_category() || is_tag() ){

    if( is_category() ){
        $term_id = get_query_var( 'cat' );
    }else{
        $tag = get_term_by( 'slug', get_query_var('tag'), 'post_tag' );
        $term_id = $tag->term_id;
    }

    $show_header = BF()->taxonomy_meta()->get_term_meta( $term_id, 'header_show_header' );
    if( $show_header == 'show' ){
        $show_header = true;
    }else{
        $show_header = false;
    }
}
// Show header for authors
elseif( is_author() ){

    $show_header = BF()->user_meta()->get_meta( 'header_show_header', $current_user );
    if( $show_header == 'show' ){
        $show_header = true;
    }else{
        $show_header = false;
    }

}
else{
    $show_header = true;
}

if( $show_header ){
    ?>
    <header <?php better_attr( 'header', 'header' ); ?>>
    <div class="container">
        <div class="row">
            <?php if (Better_Mag::get_option('logo_position') == 'left') { ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 left-align-logo logo-container">
                    <?php Better_Mag::generator()->blocks()->site_logo(); ?>
                </div>
                <div
                    class="col-lg-8 col-md-8 col-sm-8 <?php echo Better_Mag::get_option('show_aside_logo_on_small') ? 'col-xs-12' : 'hidden-xs'; ?> left-align-logo aside-logo-sidebar">
                    <?php dynamic_sidebar('aside-logo'); ?>
                </div>
            <?php } elseif (Better_Mag::get_option('logo_position') == 'center') { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-align-logo logo-container">
                    <?php Better_Mag::generator()->blocks()->site_logo(); ?>
                </div>
            <?php } elseif (Better_Mag::get_option('logo_position') == 'right') { ?>
                <div class="col-lg-8 col-md-8 col-sm-8 hidden-xs right-align-logo aside-logo-sidebar">
                    <?php dynamic_sidebar('aside-logo'); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 right-align-logo logo-container">
                    <?php Better_Mag::generator()->blocks()->site_logo(); ?>
                </div>
            <?php } ?>
        </div>
    </div>
    </header><?php

    Better_Mag::generator()->blocks()->menu_main_menu();

    Better_Mag::generator()->blocks()->breadcrumb();

} // show header

do_action( 'better-mag/main/before-slider');

Better_Mag::generator()->get_main_slider();

?><main <?php better_attr( 'content', 'container' ); ?>><?php

do_action( 'better-mag/main/after-slider');

?>