<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 29/09/16
 * Time: 5:46 PM
 * Since v1.4.0
 */
global $post;

$agency_email = get_post_meta( $post->ID, 'fave_agency_email', true );
$agency_name = get_the_title();

$agency_email = is_email( $agency_email ); ?>

<div class="form-small">

    <?php
    //if( $enable_contact_form_7_agent_detail != 1 ) {
        if ($agency_email) { ?>
            <p class="agent-contact-title"><?php esc_html_e('CONTACT', 'houzez'); ?> <?php echo esc_attr($agency_name); ?></p>

            <form method="post" action="" id="agent_detail_contact_form">
                <input type="hidden" id="target_email" name="target_email"
                       value="<?php echo antispambot($agency_email); ?>">
                <input type="hidden" name="agent_detail_ajax_nonce" id="agent_detail_ajax_nonce"
                       value="<?php echo wp_create_nonce('agent-contact-nonce'); ?>"/>
                <input type="hidden" name="action" value="houzez_contact_agent" />

                <div class="form-group">
                    <input type="text" name="name" id="name" placeholder="<?php esc_html_e( 'Your Name', 'houzez' ); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <input type="text" name="phone" id="phone" placeholder="<?php esc_html_e( 'Phone', 'houzez' ); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email" placeholder="<?php esc_html_e( 'Email', 'houzez' ); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <textarea name="message" id="message" rows="3"
                              class="form-control"><?php echo sprintf(esc_html__('Hi %s, I saw your profile on %s and wanted to see if you could help me', 'houzez'), $agency_name, get_option('blogname')); ?></textarea>
                </div>
                
                <?php get_template_part('template-parts/google', 'reCaptcha'); ?>

                <button type="submit" id="agent_detail_contact_btn" class="btn btn-secondary btn-block">
                    <?php esc_html_e('SEND MESSAGE', 'houzez'); ?>
                </button>
            </form>
            <div id="form_messages"></div>
        <?php }
    /*} else {
        echo do_shortcode($contact_form_agent_detail);
    }*/?>

</div>
