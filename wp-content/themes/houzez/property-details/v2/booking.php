<?php
global $post;
$booking_shortcode = get_post_meta($post->ID, 'fave_booking_shortcode', true);

if(!empty($booking_shortcode)) {
?>
<div id="houzez-booking" class="detail-booking-calendar detail-booking-calendar-v2 detail-block target-block">
	<div class="detail-contact-inner">
    <div class="detail-title">
        <h2 class="title-left"><?php esc_html_e( 'Availability Calendar', 'houzez' ); ?></h2>
    </div>

    <div class="houzez-booking-container">

        <?php echo do_shortcode($booking_shortcode);?>

    </div><!--.houzez-booking-container-->

    </div>
</div><!--#houzez-booking-->
<?php } ?>