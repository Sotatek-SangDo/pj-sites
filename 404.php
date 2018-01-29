<?php
get_header();
?>
<div class="row main-section">
    <div <?php better_attr( 'main-content', 'col-lg-8 col-lg-offset-2  col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 no-sidebar content-column' ); ?>>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <span class="text-404">404</span>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 desc-section">
                <h1><?php Better_Translation()->_echo( '404_not_found' ); ?></h1>
                <p><?php Better_Translation()->_echo( '404_not_found_message' ); ?></p>
                <div class="row action-links">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a href="javascript: history.go(-1);"><i class="fa fa-angle-double-right"></i> <?php Better_Translation()->_echo( '404_go_previous_page' ); ?></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a href="<?php echo site_url(); ?>"><i class="fa fa-angle-double-right"></i> <?php Better_Translation()->_echo( '404_go_homepage' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 100px;">
            <div class="col-lg-12">
                <div class="top-line">
                    <?php
                    Better_Mag::generator()->set_attr( 'submit-label', Better_Translation()->_get( 'search' ) );
                    Better_Mag::generator()->blocks()->partial_search_form(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php get_footer(); ?>