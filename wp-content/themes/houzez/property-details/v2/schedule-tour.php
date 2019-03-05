<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 05/05/17
 * Time: 7:06 PM
 */
global $post, $user_role;
$houzez_agent_display = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );
$enable_contact_form_7_prop_detail = houzez_option('enable_contact_form_7_prop_detail');
$contact_form_agent_bottom = houzez_option('contact_form_agent_bottom');
$enableDisable_agent_forms = houzez_option('agent_forms');
$enable_direct_messages = houzez_option('enable_direct_messages');
$schedule_time_slots = houzez_option('schedule_time_slots');
$agent_forms_terms = houzez_option('agent_forms_terms');
$agent_forms_terms_text = houzez_option('agent_forms_terms_text');
$is_single_agent = true;
$listing_agent = '';
$prop_agent_email = '';

if( $prop_agent_display != '-1' && $houzez_agent_display == 'agent_info' ) {

    $prop_agent_ids = get_post_meta( get_the_ID(), 'fave_agents' );
    $prop_agent_ids = array_filter( $prop_agent_ids, function($hz){
        return ( $hz > 0 );
    });

    $prop_agent_ids = array_unique( $prop_agent_ids );

    $agents_count = count( $prop_agent_ids );

    if ( $agents_count > 1 ) :
        $is_single_agent = false;
    endif;

    foreach ( $prop_agent_ids as $agent ) :

        if ( 0 < intval( $agent ) ) :

            $prop_agent_id = intval( $agent );
            $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agent_email', true );

        endif;

    endforeach;

} elseif( $houzez_agent_display == 'agency_info' ) {

    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agency_email', true );

} elseif( $houzez_agent_display == 'author_info' ) {

    $prop_agent_email = get_the_author_meta( 'email' );
}

$agent_email = is_email($prop_agent_email);
$user_info = get_userdata(get_the_author_meta('ID'));
$user_role = implode(', ', $user_info->roles);
?>
<div id="schedule_tour" class="detail-contact detail-block target-block">
    <div class="detail-contact-inner">
        <div class="detail-title">
            <h2 class="title-left"><?php esc_html_e('Schedule a Tour', 'houzez'); ?></h2>
        </div>
        <form method="post" action="#">
            <input type="hidden" name="schedule_contact_form_ajax"
                   value="<?php echo wp_create_nonce('schedule-contact-form-nonce'); ?>"/>
            <input type="hidden" name="property_permalink"
                   value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
            <input type="hidden" name="property_title"
                   value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
            <input type="hidden" name="action" value="houzez_schedule_send_message">

            <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">

            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php esc_html_e('Date', 'houzez'); ?></label>
                        <input name="schedule_date" class="form-control input_date" placeholder="<?php esc_html_e('Select tour date', 'houzez'); ?>" type="text">
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label><?php esc_html_e('Time', 'houzez'); ?></label>
                        <select name="schedule_time" class="selectpicker">
                            <?php 
                            $time_slots = explode(',', $schedule_time_slots); 
                            foreach ($time_slots as $time) {
                                echo '<option value="'.trim($time).'">'.$time.'</option>';
                            }
                            ?>
                            
                        </select>
                    </div>
                </div>
            </div>

            <div class="detail-title-inner">
                <h4 class="title-inner"><?php esc_html_e('Your information', 'houzez'); ?></h4>
            </div>

            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <input class="form-control" name="name"
                               placeholder="<?php esc_html_e('Your Name', 'houzez'); ?>" type="text">
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <input class="form-control" name="phone"
                               placeholder="<?php esc_html_e('Phone', 'houzez'); ?>" type="text">
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <input class="form-control" name="email"
                               placeholder="<?php esc_html_e('Email', 'houzez'); ?>" type="email">
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="5" placeholder="<?php esc_html_e('Message', 'houzez'); ?>"></textarea>
                    </div>
                </div>
                <?php if($agent_forms_terms != 0) { ?>
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input name="term_condition" type="checkbox">
                                <?php echo $agent_forms_terms_text; ?>
                            </label>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <button class="schedule_contact_form btn btn-secondary"><?php esc_html_e('Submit', 'houzez'); ?></button>
            <div class="form_messages"></div>
        </form>
    </div>
</div><!-- #schedule_tour -->
