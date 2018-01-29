<?php
/**
 * "loop" to display posts when using an existing query.
 */

$before = '<div class="col-lg-12 col-md-12 col-sm-12">';

?>
<div class="row"><?php

    Better_Mag::generator()->set_attr_thumbnail_size( 'main-post' );

    Better_Mag::generator()->set_attr( 'show-term-banner', true );

    Better_Mag::generator()->set_attr( 'excerpt-length', 75 );

    while( Better_Mag::posts()->have_posts() ){

        Better_Mag::posts()->the_post();

        echo $before;

        Better_Mag::generator()->blocks()->block_modern();

        echo '</div>';
    }

    Better_Mag::generator()->clear_atts();
    ?>
</div>