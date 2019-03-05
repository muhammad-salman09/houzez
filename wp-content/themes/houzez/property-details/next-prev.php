<div class="next-prev-block clearfix">

    <?php
    $prevPost = get_previous_post(false);
    if($prevPost) {
    $args = array(
        'post_type' => 'property',
        'posts_per_page' => 1,
        'include' => $prevPost->ID
    );
    $prevPost = get_posts($args);
    foreach ($prevPost as $post) {
    setup_postdata($post);
    ?>
    <div class="prev-box pull-left">
        <div class="media">
            <div class="media-left">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
            </div>
            <div class="media-body media-middle">
                <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><i class="fa fa-angle-left"></i> <?php esc_html_e('PREVIOUS PROPERTY', 'houzez'); ?></a></h3>
                <h4><?php the_title(); ?></h4>
            </div>
        </div>
    </div>
        <?php
        wp_reset_postdata();
    } //end foreach
    } // end if

    $nextPost = get_next_post(false);
    if($nextPost) {
    $args = array(
        'post_type' => 'property',
        'posts_per_page' => 1,
        'include' => $nextPost->ID
    );
    $nextPost = get_posts($args);
    foreach ($nextPost as $post) {
    setup_postdata($post);
    ?>

    <div class="next-box pull-right">
        <div class="media">
            <div class="media-body media-middle text-right">
                <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php esc_html_e('NEXT PROPERTY', 'houzez'); ?> <i class="fa fa-angle-right"></i></a></h3>
                <h4><?php the_title(); ?></h4>
            </div>
            <div class="media-right">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
            </div>
        </div>
    </div>
        <?php
        wp_reset_postdata();
    } //end foreach
    } // end if
    ?>

</div>