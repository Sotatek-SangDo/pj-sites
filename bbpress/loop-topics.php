<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">

	<li class="bbp-body">

        <div class="category-forum">
            <ul>
                <li class="bbp-header">

                    <ul class="forum-titles">
                        <li class="bbp-topic-freshness"><?php Better_Translation()->_echo( 'bbp_freshness' ); ?></li>
                        <li class="bbp-topic-title"><?php Better_Translation()->_echo( 'bbp_topics' ); ?></li>
                        <li class="bbp-topic-reply-posts-count"><?php Better_Translation()->_echo( 'bbp_voices' ); ?> / <?php bbp_show_lead_topic() ? Better_Translation()->_echo( 'bbp_replies' ) : Better_Translation()->_echo( 'bbp_posts' ); ?></li>
                    </ul>

                </li>
            </ul>
        </div>
		<?php while ( bbp_topics() ) : bbp_the_topic(); ?>

			<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>

		<?php endwhile; ?>

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' ); ?>
