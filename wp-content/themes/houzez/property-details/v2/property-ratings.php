<?php
global $wpdb;

$notification = 'none';
$current_user = wp_get_current_user();
$userID = $current_user->ID;

$rating = 0;
$_ratings = get_post_meta( get_the_ID(), 'fave_rating', true );
$prop_ID = get_the_ID();

if ( !empty( $_ratings ) ) {

    $totalStars = 0;
    $voters = array_sum($_ratings);

    foreach ($_ratings as $stars => $votes) {
        $totalStars += intval($stars) * intval($votes);
    }
    if ( $voters != 0 ) {
        $rating = ($totalStars / $voters);
    }
}

$comments_table = $wpdb->comments;
$comments_meta_table = $wpdb->commentmeta;
$comments_query = "SELECT * FROM $comments_table as comment INNER JOIN $comments_meta_table AS meta WHERE comment.comment_post_ID = $prop_ID AND meta.meta_key = 'rating' AND meta.comment_id = comment.comment_ID AND comment.comment_approved = 1";
$get_comments = $wpdb->get_results( $comments_query );

$check_comment_query = "SELECT * FROM $comments_table as comment INNER JOIN $comments_meta_table AS meta WHERE comment.comment_post_ID = $prop_ID AND comment.user_id = $userID  AND meta.meta_key = 'rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC";
$check_comment = $wpdb->get_row( $check_comment_query );
$prop_total_reviews = sizeof( $get_comments );

?>

<div class="property-reviews detail-block">
    <div class="detail-title" data-score="<?php echo round($rating, 2); ?>" itemprop="aggregateRating" itemscope itemtype="<?php echo houzez_http_or_https(); ?>://schema.org/AggregateRating">
        <h2 class="title-left">
            <?php

            if ( $prop_total_reviews == 0 ) {
                esc_html_e( 'No Review', 'houzez' );
            } elseif ( $prop_total_reviews == 1 ) {
                echo '<span itemprop="reviewCount">'.$prop_total_reviews . '</span> ' . esc_html( 'Review' );
            } else {
                echo '<span itemprop="reviewCount">'.$prop_total_reviews . '</span> ' . esc_html( 'Reviews' );
            }

            ?>
            <span class="rating-wrap">
                <input class="rating-display-only" name="rating" value="<?php echo $rating; ?>" type="number" min="0" max="5" step=1 data-size="md" class="rating" >
                <span class="star-text star-text-right">
                    (
                    <span itemprop="ratingValue"><?php echo round($rating, 2); ?></span> <?php esc_html_e('out of', 'houzez');?>
                    <span itemprop="bestRating">5</span>
                    )
                </span>
            </span>
        </h2>
        <div class="title-right"><strong><a href="#writ-review-block"> <?php esc_html_e( 'Write a Review', 'houzez' ); ?> </a></strong></div>
    </div>
    <ul class="reviews-list">
        <?php

        if ( sizeof( $get_comments ) ) {
            foreach ( $get_comments as $comment ) {

                $user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $comment->user_id );

                if ( empty( $user_custom_picture ) ) {
                    $user_custom_picture = get_template_directory_uri().'/images/profile-avatar.png';
                }

                ?>
                <li class="media" itemprop="review" itemscope itemtype="<?php echo houzez_http_or_https(); ?>://schema.org/Review">
                    <div class="media-left" itemprop="author" itemscope itemtype="<?php echo houzez_http_or_https(); ?>://schema.org/Person">
                        <a href="#">
                            <img class="media-object" src="<?php echo esc_url($user_custom_picture); ?>" alt="<?php the_author_meta( 'display_name', $comment->user_id ); ?>" width="60" height="60">
                        </a>
                    </div>
                    <div class="media-body" itemprop="reviewBody">
                        <div class="review-top">
                            <h3 class="media-heading"><a href="#"><?php the_author_meta( 'display_name', $comment->user_id ); ?></a></h3>
                            <p class="review-date" itemprop="datePublished" content="<?php echo date(DATE_W3C, strtotime( $comment->comment_date )) ?>">
                                <time datetime="<?php echo date(DATE_W3C, strtotime( $comment->comment_date )) ?>"><?php echo date( 'F d, Y', strtotime( $comment->comment_date ) ); ?></time>
                            </p>
                        </div>

                        <h4 class="review-title-inner"> <?php echo get_comment_meta( $comment->comment_ID, 'title', true ) ?>
                            <span class="rating-wrap">
                                <input class="rating-display-only" name="rating" value="<?php echo $comment->meta_value; ?>" type="number" min="0" max="5" step=1 data-size="md" class="rating" >
                            </span>
                        </h4>
                        <p> <?php echo $comment->comment_content; ?> </p>
                    </div>
                </li>
                <?php
            }

        }

        ?>
    </ul>
    <div id="writ-review-block" class="add-review-block">
        <h4 class="review-title"> <?php esc_html_e( 'Write a Review', 'houzez' ); ?> </h4>
        <?php
        if ( !is_user_logged_in() ) {
            echo '<a href="#" data-toggle="modal" data-target="#pop-login">'.esc_html__('Login for Review', 'houzez').'</a>';
        } else {
            if ( sizeof( $check_comment ) == 0 ) {
                ?>
                <form method="post" action="#">
                    <input type="hidden" name="start_thread_message_form_ajax" value="<?php echo wp_create_nonce('property-rating-form-nonce'); ?>"/>
                    <input type="hidden" name="action" value="houzez_property_raring">
                    <input type="hidden" name="property_id" value="<?php the_ID(); ?>">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="review_title"><?php esc_html_e('Review Title', 'houzez'); ?></label>
                                <input class="form-control" id="review_title" name="title"
                                       placeholder="<?php esc_html_e('Enter a title for your review', 'houzez'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="add-rating">
                                <label> <?php esc_html_e('Rate This Property', 'houzez'); ?> </label>
                                <div class="add-rating-inner">
                                    <div class="rating-wrap">
                                        <input id="property-rating" name="rating" value="4" type="number" min="0" max="5" step="1" data-size="xl" class="rating">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" name="message" placeholder="<?php esc_html_e('Your review', 'houzez'); ?>"></textarea>
                            </div>
                        </div>
                        <div class="form_messages"></div>
                        <div class="col-sm-12 col-xs-12">
                            <button class="property_rating btn btn-secondary"><?php esc_html_e('Submit Review', 'houzez'); ?></button>
                        </div>
                    </div>
                </form>
                <?php
            } else {
                ?>
                <form method="post" action="#">
                    <input type="hidden" name="start_thread_message_form_ajax" value="<?php echo wp_create_nonce('property-rating-form-nonce'); ?>"/>
                    <input type="hidden" name="action" value="houzez_property_raring">
                    <input type="hidden" name="property_id" value="<?php the_ID(); ?>">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="review_title"><?php esc_html_e('Review Title', 'houzez'); ?></label>
                                <input class="form-control" id="review_title" name="title" value="<?php echo get_comment_meta( $comment->comment_ID, 'title', true ) ?>" placeholder="<?php esc_html_e('Enter a title for your review', 'houzez'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="add-rating">
                                <label> <?php esc_html_e('Rate This Property', 'houzez'); ?> </label>
                                <div class="add-rating-inner">
                                    <div class="rating-wrap">
                                        <input id="property-rating" name="rating" value="<?php echo $comment->meta_value; ?>" type="number" min="0" max="5" step="1" data-size="xl" class="rating">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" name="message" placeholder="<?php esc_html_e('Your review', 'houzez'); ?>"><?php echo $check_comment->comment_content; ?></textarea>
                            </div>
                        </div>
                        <div class="form_messages"></div>
                        <div class="col-sm-12 col-xs-12">
                            <button class="property_rating btn btn-secondary"><?php esc_html_e('Update Review', 'houzez'); ?></button>
                        </div>
                    </div>
                </form>
                <?php
            }
        }
        ?>
    </div>
</div>