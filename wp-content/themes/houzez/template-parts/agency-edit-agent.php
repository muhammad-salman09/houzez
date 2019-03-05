<?php
/**
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 26/12/16
 * Time: 2:23 AM
 * Since v1.5.0
 */
global $current_user;
$current_user = wp_get_current_user();
$userID       = $current_user->ID;

$agency_user_id = $_GET['edit_agent'];
$first_name = get_user_meta($agency_user_id, 'first_name', true );
$last_name = get_user_meta($agency_user_id, 'last_name', true );
$agency_user_agent_id = get_user_meta($agency_user_id, 'fave_author_agent_id', true );
$user_meta = get_userdata( $agency_user_id );
?>

<div class="profile-content-area">

    <div class="account-block account-profile-block">
        <div class="row">

            <div class="col-sm-12 col-xs-12">
                <h4><?php esc_html_e( 'Update Agent', 'houzez' ); ?></h4>
                <div class="row">
                    <div id="aa_register_message" class="houzez_messages message"></div>
                    <form method="" action="">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_username"><?php esc_html_e('Username','houzez');?></label>
                            <input type="text" disabled name="aa_username" id="aa_username"  class="form-control" value="<?php echo sanitize_text_field($user_meta->user_login); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_email"><?php esc_html_e('Email','houzez');?></label>
                            <input type="text" name="aa_email" id="aa_email"  class="form-control" value="<?php echo sanitize_text_field($user_meta->user_email); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_firstname"><?php esc_html_e('First Name','houzez');?></label>
                            <input type="text" name="aa_firstname" id="aa_firstname" class="form-control" value="<?php echo sanitize_text_field($first_name); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_lastname"><?php esc_html_e('Last Name','houzez');?></label>
                            <input type="text" name="aa_lastname" id="aa_lastname" class="form-control" value="<?php echo sanitize_text_field($last_name); ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="aa_password"><?php esc_html_e('Password','houzez');?></label>
                            <input type="password" id="aa_password" name="aa_password" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <?php wp_nonce_field( 'houzez_agency_user_agent_ajax_nonce', 'houzez-security-agency-user-agent' );   ?>
                            <input type="hidden" name="action" value="houzez_agency_agent_update" />
                            <input type="hidden" name="agency_user_agent_id" value="<?php echo intval($agency_user_agent_id); ?>" />
                            <input type="hidden" name="agency_user_id" value="<?php echo intval($agency_user_id); ?>" />
                            <button class="btn btn-primary pull-right" id="houzez_agency_agent_update"><?php esc_html_e('Update Agent','houzez');?></button>
                         </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
