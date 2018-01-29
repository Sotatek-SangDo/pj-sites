<?php
/**
 * "loop" to display posts when using an existing query.
 *
 * Uses /blocks/block-modern.php that can be override in child themes.
 */

if( Better_Mag::current_sidebar_layout() ){

    $before = '<div class="column col-lg-6 col-md-6 col-sm-12">';
    $class = 'modern-2-column';

}else{

    $before = '<div class="column col-lg-4 col-md-4 col-sm-6">';
    $class = 'modern-3-column';

}
?>
<div class="row <?php echo $class; ?>"><?php

    Better_Mag::generator()->set_attr( "hide-meta-author-if-review", true );

    Better_Mag::generator()->set_attr( 'show-term-banner', true );

    while( Better_Mag::posts()->have_posts() ){

        Better_Mag::posts()->the_post();

        echo $before;

        Better_Mag::generator()->blocks()->block_modern();

        echo '</div>';
    }

    Better_Mag::generator()->clear_atts();

    ?>
</div>