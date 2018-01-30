<?php
get_header();

the_post();

?><div class="row main-section">
    <?php if( Better_Mag::current_sidebar_layout() == 'left' ) Better_Mag::get_sidebar(); ?>
    <div <?php better_attr( 'main-content', Better_Mag::current_sidebar_layout() ?  'col-lg-8 col-md-8 col-sm-8 col-xs-12 with-sidebar content-column' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12 no-sidebar' ); ?>><?php

        ?>
        <article <?php better_attr( 'post', 'single-content clearfix' ); ?>>
            <?php

            $show_featured = Better_Mag::get_meta( 'bm_disable_post_featured' );

            if( is_bool( $show_featured ) && $show_featured ){ // support for < versions 2
                $show_featured = true;
            }else{
                $show_featured = BM_Helper::result_of_meta_and_option( $show_featured, Better_Mag::get_option( 'content_show_featured_image' ) );
            }

            if( $show_featured ): ?>
                <div class="featured" itemprop="thumbnailUrl"><?php

                // Gallery Post Format
                if( get_post_format() == 'gallery' ){
                    Better_Mag::generator()->blocks()->partial_gallery_slider();
                }

                // Video Post Format
                elseif( get_post_format() == 'video' ){
                    echo do_shortcode( apply_filters( 'better-framework/content/auto-embed', Better_Mag::posts()->get_meta( 'featured_video_code' ) ) );
                }

                // Featured Image
                else{

                    $img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                    $img = $img[0];

                    // featured image for layouts width sidebar
                    if( Better_Mag::current_sidebar_layout() ){
                        echo '<a href="'. $img .'" rel="prettyPhoto" title="' . the_title_attribute( 'echo=0' ) .'" property="image">';
                        the_post_thumbnail( 'main-post', array( 'title' => get_the_title(), 'class' => 'img-responsive' ));
                        echo '</a>';
                    }
                    // full width layout style
                    else{
                        echo '<a href="'. $img .'" rel="prettyPhoto" title="' . the_title_attribute( 'echo=0' ) .'" property="image">';
                        the_post_thumbnail( 'main-full', array( 'title' => get_the_title(), 'class' => 'img-responsive' ));
                        echo '</a>';
                    }
                }
                ?>
                </div><?php
            endif;

            if( ! Better_Mag::get_meta( 'hide_page_title', false ) ){ ?>
            <h1 <?php better_attr('post-title', 'page-heading') ?>><span class="h-title"><?php the_title(); ?></span></h1><?php
            }

            Better_Mag::posts()->the_post_meta();

            $show_share_box = BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_social_share' ), Better_Mag::get_option( 'content_show_share_box' ) );

            if( $show_share_box && ( Better_Mag::get_option( 'bm_share_box_location' ) == 'top' || Better_Mag::get_option( 'bm_share_box_location' ) == 'bottom-top' ) ){

                Better_Mag::generator()->blocks()->partial_share_box( true, array( 'class' => 'top-location' ) );

            }

            Better_Mag::posts()->the_content( null, false, '', 'propname="articleBody"' );

            // Shows post categories
            if( has_category() && BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_post_categories' ), Better_Mag::get_option( 'content_show_categories' ) ) ){
                echo "<div " . better_get_attr( 'post-terms', 'the-content', 'category' ) . "><p class='terms-list'>";
                echo '<span class="fa fa-folder-open"></span> ' . Better_Translation()->_get( 'post_categories' ) . ' ';
                the_category( "<span class='sep'>,</span>" );
                echo "</p></div>";
            }

            // Shows post tags
            if( has_tag() && BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_post_tags' ), Better_Mag::get_option( 'content_show_tags' ) ) ){
                echo "<div " . better_get_attr( 'post-terms', 'the-content', 'post_tag' ) . "><p class='terms-list'>";
                echo '<span class="fa fa-tag"></span> ' . Better_Translation()->_get( 'post_tag' ) . ' ';
                the_tags( "", "<span class='sep'>,</span>", "" );
                echo "</p></div>";
            }

            ?>
        </article>
        <?php

        if( $show_share_box && ( Better_Mag::get_option( 'bm_share_box_location' ) == 'bottom' || Better_Mag::get_option( 'bm_share_box_location' ) == 'bottom-top' ) )
            Better_Mag::generator()->blocks()->partial_share_box();

        if( BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_author_box' ), Better_Mag::get_option( 'content_show_author_box' ) ) ){
            Better_Mag::generator()->set_attr( 'bio-excerpt', false );
            Better_Mag::generator()->set_attr_class( 'single-post-author' );
            Better_Mag::generator()->blocks()->block_user_row();
            Better_Mag::generator()->clear_atts();
        }

        if( BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_post_navigation' ), Better_Mag::get_option( 'bm_content_show_post_navigation' ) ) ){
            Better_Mag::generator()->blocks()->partial_navigate_posts();
        }

        if( BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_related_posts' ), Better_Mag::get_option( 'content_show_related_posts' ) ) )
            Better_Mag::generator()->blocks()->partial_related_posts();

        if( BM_Helper::result_of_meta_and_option( Better_Mag::get_meta( 'show_comments' ), Better_Mag::get_option( 'content_show_comments' ) ) ){
            ?>
            <div class="comments">
                <?php comments_template( '', true ); ?>
            </div><?php
        }

        ?>
    </div>
    <?php if( Better_Mag::current_sidebar_layout() == 'right' ) Better_Mag::get_sidebar(); ?>
</div>
<?php get_footer(); ?>