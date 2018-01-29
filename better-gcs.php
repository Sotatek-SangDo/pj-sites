<?php

/**
 * BetterMag Custom Template For Better Google Custom Search Plugin
 */

get_header();

?>
<div class="row main-section">
    <?php if( Better_Mag::current_sidebar_layout() == 'left' ) Better_Mag::get_sidebar(); ?>
    <div <?php better_attr( 'main-content', Better_Mag::current_sidebar_layout() ? 'col-lg-8 col-md-8 col-sm-8 col-xs-12 with-sidebar content-column' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 no-sidebar' ); ?>><?php

        Better_GCS_Search_Box();

        ?></div>
    <?php if( Better_Mag::current_sidebar_layout() == 'right' ) Better_Mag::get_sidebar(); ?>
</div><?php

get_footer();

?>