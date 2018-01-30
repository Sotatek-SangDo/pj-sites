<?php
/**
 * "loop" to display posts when using an existing query.
 */

Better_Mag::generator()->set_attr_class( 'bottom-line' );

Better_Mag::generator()->set_attr( 'show-term-banner', true );

// Change thumbnail size to bigger and more excerpt text when sidebar is disable and column with is bigger
if( ! Better_Mag::current_sidebar_layout() ){
    Better_Mag::generator()->set_attr_thumbnail_size( 'main-post' );
    Better_Mag::generator()->set_attr( 'excerpt-length', 65 );
}

while( Better_Mag::posts()->have_posts() ){

    Better_Mag::posts()->the_post();

    Better_Mag::generator()->blocks()->block_blog();
}

Better_Mag::generator()->clear_atts();