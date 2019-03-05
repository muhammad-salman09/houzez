<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 7:56 PM
 */
global $post, $current_user, $all_meta_for_user;
$houzez_agent_display = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );
$enable_contact_form_7_prop_detail = houzez_option('enable_contact_form_7_prop_detail');
$contact_form_agent_above_image = houzez_option('contact_form_agent_above_image');
$enable_direct_messages = houzez_option('enable_direct_messages');
$agent_forms_terms = houzez_option('agent_forms_terms');
$agent_forms_terms_text = houzez_option('agent_forms_terms_text');
$prop_agent_num = $agent_num_call = $prop_agent_email = '';
$listing_agent = '';
$is_single_agent = true;

$terms_conditions = houzez_option('login_terms_condition');

$allowed_html_array = array(
    'a' => array(
        'href' => array(),
        'title' => array()
    )
);

if( $prop_agent_display != '-1' && $houzez_agent_display == 'agent_info' ) {

    $prop_agent_ids = get_post_meta( get_the_ID(), 'fave_agents' );
    $prop_agent_ids = array_filter( $prop_agent_ids, function($hz){
        return ( $hz > 0 );
    });

    $prop_agent_ids = array_unique( $prop_agent_ids );

    if ( ! empty( $prop_agent_ids ) ) {
        $agents_count = count( $prop_agent_ids );
        if ( $agents_count > 1 ) :
            $is_single_agent = false;
        endif;
        $listing_agent = '';
        foreach ( $prop_agent_ids as $agent ) {
            if ( 0 < intval( $agent ) ) {

                $prop_agent_id = intval( $agent );
                $prop_agent_phone = get_post_meta( $prop_agent_id, 'fave_agent_office_num', true );
                $prop_agent_mobile = get_post_meta( $prop_agent_id, 'fave_agent_mobile', true );
                $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agent_email', true );
                $agent_num_call = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
                $prop_agent = get_the_title( $prop_agent_id );
                $thumb_id = get_post_thumbnail_id( $prop_agent_id );
                $thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
                $prop_agent_photo_url = $thumb_url_array[0];
                $prop_agent_permalink = get_post_permalink( $prop_agent_id );

                $agent_args = array();
                $agent_args[ 'agent_id' ] = $prop_agent_id;
                $agent_args[ 'agent_name' ] = $prop_agent;
                $agent_args[ 'agent_mobile' ] = $prop_agent_mobile;
                $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
                $agent_args['agent_phone'] = $prop_agent_phone;
                $agent_args[ 'agent_email' ] = $prop_agent_email;
                $agent_args[ 'link' ] = $prop_agent_permalink;
                if( empty( $prop_agent_photo_url )) {
                    $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
                } else {
                    $agent_args[ 'picture' ] = $prop_agent_photo_url;
                }
                $listing_agent .= houzez_get_agent_info_top( $agent_args, 'agent_form', $is_single_agent );
            }
        }
    }

} elseif( $houzez_agent_display == 'agency_info' ) {
    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $prop_agent_phone = get_post_meta( $prop_agent_id, 'fave_agency_phone', true );
    $prop_agent_mobile = get_post_meta( $prop_agent_id, 'fave_agency_mobile', true );
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agency_email', true );
    $agent_num_call = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
    $prop_agent = get_the_title( $prop_agent_id );
    $thumb_id = get_post_thumbnail_id( $prop_agent_id );
    $thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
    $prop_agent_photo_url = $thumb_url_array[0];
    $prop_agent_permalink = get_post_permalink( $prop_agent_id );

    $agent_args = array();
    $agent_args[ 'agent_id' ] = $prop_agent_id;
    $agent_args[ 'agent_name' ] = $prop_agent;
    $agent_args[ 'agent_mobile' ] = $prop_agent_mobile;
    $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
    $agent_args[ 'agent_phone' ] = $prop_agent_phone;
    $agent_args[ 'agent_email' ] = $prop_agent_email;
    $agent_args[ 'link' ] = $prop_agent_permalink;
    if( empty( $prop_agent_photo_url )) {
        $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
    } else {
        $agent_args[ 'picture' ] = $prop_agent_photo_url;
    }
    $listing_agent .= houzez_get_agent_info_top( $agent_args, 'agent_form' );

} elseif ( $houzez_agent_display == 'author_info' ) {

    $prop_agent = get_the_author();
    $prop_agent_permalink = get_author_posts_url( get_the_author_meta( 'ID' ) );
    $prop_agent_phone = get_the_author_meta( 'fave_author_phone' );
    $prop_agent_mobile = get_the_author_meta( 'fave_author_mobile' );
    $agent_num_call = str_replace(array('(',')',' ','-'),'', $prop_agent_num);
    $prop_agent_photo_url = get_the_author_meta( 'fave_author_custom_picture' );
    $prop_agent_email = get_the_author_meta( 'email' );

    $agent_args = array();
    $agent_args[ 'agent_id' ] = get_the_author_meta( 'ID' );
    $agent_args[ 'agent_name' ] = $prop_agent;
    $agent_args[ 'agent_mobile' ] = $prop_agent_mobile;
    $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
    $agent_args[ 'agent_phone' ] = $prop_agent_phone;
    $agent_args[ 'agent_email' ] = $prop_agent_email;
    $agent_args[ 'link' ] = $prop_agent_permalink;
    if( empty( $prop_agent_photo_url )) {
        $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
    } else {
        $agent_args[ 'picture' ] = $prop_agent_photo_url;
    }
    $listing_agent .= houzez_get_agent_info_top( $agent_args, 'agent_form' );
} else {

    $prop_agent = get_the_author();
    $prop_agent_permalink = get_author_posts_url( get_the_author_meta( 'ID' ) );
    $prop_agent_phone = get_the_author_meta( 'fave_author_phone' );
    $prop_agent_mobile = get_the_author_meta( 'fave_author_mobile' );
    $agent_num_call = str_replace(array('(',')',' ','-'),'', $prop_agent_num);
    $prop_agent_photo_url = get_the_author_meta( 'fave_author_custom_picture' );
    $prop_agent_email = get_the_author_meta( 'email' );

    $agent_args = array();
    $agent_args[ 'agent_id' ] = get_the_author_meta( 'ID' );
    $agent_args[ 'agent_name' ] = $prop_agent;
    $agent_args[ 'agent_mobile' ] = $prop_agent_mobile;
    $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
    $agent_args[ 'agent_phone' ] = $prop_agent_phone;
    $agent_args[ 'agent_email' ] = $prop_agent_email;
    $agent_args[ 'link' ] = $prop_agent_permalink;
    if( empty( $prop_agent_photo_url )) {
        $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
    } else {
        $agent_args[ 'picture' ] = $prop_agent_photo_url;
    }
    $listing_agent .= houzez_get_agent_info_top( $agent_args, 'agent_form' );
}
if( empty( $prop_agent_photo_url )) {
    $prop_agent_photo_url = get_template_directory_uri().'/images/profile-avatar.png';
}

$agent_email = is_email( $prop_agent_email );
$user_info = get_userdata( get_the_author_meta( 'ID' ) );
$user_role = implode(', ', $user_info->roles);

if( $enable_contact_form_7_prop_detail == 0 ) {
    ?>
    <form method="post" action="#">
    <?php

}
?>
<?php echo $listing_agent; ?>
<?php
if( $enable_contact_form_7_prop_detail != 0 ) {
    if( !empty($contact_form_agent_above_image) ) {
        echo do_shortcode($contact_form_agent_above_image);
    }
} else {
    if( $is_single_agent == true && is_user_logged_in() && $enable_direct_messages != 0 ) { ?>
            <input type="hidden" name="start_thread_form_ajax"
                   value="<?php echo wp_create_nonce('start-thread-form-nonce'); ?>"/>
            <input type="hidden" name="property_id" value="<?php echo $post->ID; ?>"/>
            <input type="hidden" name="action" value="houzez_start_thread">
            <div class="form-group">
                   <textarea class="form-control" name="message" rows="5" placeholder="<?php esc_html_e('Description', 'houzez'); ?>"><?php esc_html_e("Hello, I am interested in", "houzez"); ?> [<?php echo get_the_title(); ?>]</textarea>
            </div>

            <button class="start_thread_form btn btn-secondary btn-block"><?php esc_html_e('Send Message', 'houzez'); ?></button>
            <div class="form_messages"></div>
        </form>
    <?php
    } else {
        if ($agent_email) { ?>
                <?php if ( $is_single_agent == true ) : ?>
                    <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
                <?php endif; ?>
                <input type="hidden" name="agent_contact_form_ajax" value="<?php echo wp_create_nonce('agent-contact-form-nonce'); ?>"/>
                <input type="hidden" name="property_permalink" value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
                <input type="hidden" name="property_title" value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
                <input type="hidden" name="action" value="houzez_agent_send_message">

                <div class="form-group">
                    <input class="form-control" name="name" value="<?php echo $current_user->display_name; ?>" type="text"
                           placeholder="<?php esc_html_e('Your Name', 'houzez'); ?>">
                </div>
                <div class="form-group">
                    <input class="form-control" name="phone" value="<?php if(isset($all_meta_for_user['fave_author_phone'][0])) { echo $all_meta_for_user['fave_author_phone'][0]; }?>" type="text"
                           placeholder="<?php esc_html_e('Phone', 'houzez'); ?>">
                </div>
                <div class="form-group">
                    <input class="form-control" name="email" value="<?php echo $current_user->user_email; ?>" type="email"
                           placeholder="<?php esc_html_e('Email', 'houzez'); ?>">
                </div>
                <div class="form-group">
                <textarea class="form-control" name="message" rows="4" placeholder="<?php esc_html_e('Description', 'houzez'); ?>"><?php _e("Hello, I am interested in", "houzez"); ?> [<?php echo get_the_title(); ?>]</textarea>
                </div>

                <?php if($agent_forms_terms != 0) { ?>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input name="term_condition" type="checkbox">
                            <?php echo $agent_forms_terms_text; ?>
                        </label>
                    </div>
                </div>
                <?php } ?>

                <?php get_template_part('template-parts/google', 'reCaptcha'); ?>

                <button class="agent_contact_form btn btn-secondary btn-block"><?php esc_html_e('Request info', 'houzez'); ?></button>
                <?php if( $is_single_agent == true  && $enable_direct_messages != 0 ) { ?>
                    <button type="button" class="btn btn-secondary btn-trans btn-block" data-toggle="modal" data-target="#pop-login"> <?php esc_html_e('Send Direct Message', 'houzez'); ?> </button>
                <?php } ?>
                <div class="form_messages"></div>
            </form>
        <?php }
    }
}?>