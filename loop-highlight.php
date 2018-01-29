<?php
/**
 * "loop" to display posts when using an existing query.
 */

if( Better_Mag::current_sidebar_layout() ){

    $before = '<div class="col-lg-6 col-md-6 col-sm-12">';

}else{

    $before = '<div class="col-lg-4 col-md-4 col-sm-6">';

}
?>
<div class="row"><?php

    Better_Mag::generator()->set_attr( "hide-meta-author-if-review", true );

    Better_Mag::generator()->set_attr( 'show-term-banner', true );

    while( Better_Mag::posts()->have_posts() ){

        Better_Mag::posts()->the_post();

        echo $before;

        Better_Mag::generator()->blocks()->block_highlight();

        echo '</div>';
    }

    Better_Mag::generator()->clear_atts();

    ?>
</div>