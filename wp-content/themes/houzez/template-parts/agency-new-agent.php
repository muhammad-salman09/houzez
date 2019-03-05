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
$agency_id = get_user_meta($userID, 'fave_author_agency_id', true );
$agency_ids_cpt = get_post_meta($agency_id, 'fave_agency_cpt_agent', false );
?>

<div class="profile-content-area">

    <div class="account-block account-profile-block">
        <div class="row">

            <div class="col-sm-12 col-xs-12">
                <h4><?php esc_html_e( 'Add New Agent', 'houzez' ); ?></h4>
                <div class="row">
                    <div id="aa_register_message" class="houzez_messages message"></div>
                    <form method="" action="">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_username"><?php esc_html_e('Username','houzez');?></label>
                            <input type="text" name="aa_username" id="aa_username"  class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_email"><?php esc_html_e('Email','houzez');?></label>
                            <input type="text" name="aa_email" id="aa_email"  class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_firstname"><?php esc_html_e('First Name','houzez');?></label>
                            <input type="text" name="aa_firstname" id="aa_firstname" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_lastname"><?php esc_html_e('Last Name','houzez');?></label>
                            <input type="text" name="aa_lastname" id="aa_lastname" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="aa_password"><?php esc_html_e('Password','houzez');?></label>
                            <input type="password" id="aa_password" name="aa_password" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="checkbox">
                                <label for="aa_notification"><input type="checkbox" id="aa_notification" name="aa_notification" checked value=""><?php echo esc_html__('Send the new user an email about their account.', 'houzez');?><?php /*esc_html_e('Send Agent Notification','houzez');*/?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <?php wp_nonce_field( 'houzez_agency_agent_ajax_nonce', 'houzez-security-agency-agent' );   ?>
                            <input type="hidden" name="action" value="houzez_agency_agent" />
                            <input type="hidden" name="agency_id" value="<?php echo $userID; ?>" />
                            <?php if( !empty($agency_ids_cpt)) {
                                foreach( $agency_ids_cpt as $ag_id ): ?>
                                <input type="hidden" name="agency_ids_cpt[]" value="<?php echo $ag_id; ?>" />
                            <?php
                                endforeach;
                                } else { ?>
                                <input type="hidden" name="agency_ids_cpt[]" value='' />
                           <?php } ?>
                            <input type="hidden" name="agency_id_cpt" value='<?php echo $agency_id; ?>' />
                            <button class="btn btn-primary pull-right" id="houzez_agency_agent_register"><?php esc_html_e('Add New Agent','houzez');?></button>
                         </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
