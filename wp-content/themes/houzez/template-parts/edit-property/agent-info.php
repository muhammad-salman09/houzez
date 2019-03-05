<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 10/08/16
 * Time: 6:12 PM
 */
global $houzez_local, $current_user, $prop_data, $prop_meta_data, $hide_add_prop_fields, $required_fields, $is_multi_steps;
$prop_agent_display_option = isset($prop_meta_data['fave_agent_display_option'][0]) ?$prop_meta_data['fave_agent_display_option'][0] : 'author_info';

$prop_agent_id = '';
if(isset($prop_meta_data['fave_agents'][0])) {
    $prop_agent_id = $prop_meta_data['fave_agents'][0];
}
wp_get_current_user();
$userID = $current_user->ID;
$agents_posts = get_posts(array('post_type' => 'houzez_agent', 'posts_per_page' => -1, 'suppress_filters' => 0));
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <?php if (houzez_is_agency()) { ?>

        <div class="add-title-tab">
            <h3><?php echo $houzez_local['contact_information']; ?></h3>
            <div class="add-expand"></div>
        </div>
        <div class="add-tab-content">
            <div class="add-tab-row push-padding-bottom">
                <p><?php echo $houzez_local['what_display_contactbox']; ?></p>
                <div class="radio">
                    <label><input value="agency_info" <?php checked( $prop_agent_display_option, 'agency_info' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['agency_info']; ?></label>
                </div>
                <div class="radio">
                    <label><input value="agent_info" <?php checked( $prop_agent_display_option, 'agent_info' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['agent_info']; ?></label>
                </div>
                <div class="radio">
                    <label><input value="none" <?php checked( $prop_agent_display_option, 'none' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['hide_info_box']; ?></label>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="fave_agents" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                <option value="-1"><?php echo $houzez_local['none']; ?></option>
                                <?php
                                if (!empty($agents_posts)) {
                                    $agency_id = get_user_meta($userID, 'fave_author_agency_id', true);
                                    foreach ($agents_posts as $agent_post) {
                                        if( $agency_id == get_post_meta($agent_post->ID, 'fave_agent_agencies', true) )
                                            echo '<option '.selected( $prop_agent_id, $agent_post->ID ).' value="' . $agent_post->ID . '">' . $agent_post->post_title . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <div class="add-title-tab">
            <h3><?php echo $houzez_local['agent_information']; ?></h3>
            <div class="add-expand"></div>
        </div>
        <div class="add-tab-content">
            <div class="add-tab-row push-padding-bottom">
                <p><?php echo $houzez_local['what_display_agentbox']; ?></p>
                <div class="radio">
                    <label><input value="author_info" <?php checked( $prop_agent_display_option, 'author_info' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['author_info']; ?></label>
                </div>
                <!--<div class="radio">
                <label><input value="agency_info" type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php /*echo $houzez_local['agency_info']; */?></label>
            </div>-->
                <div class="radio">
                    <label><input value="agent_info" <?php checked( $prop_agent_display_option, 'agent_info' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['agent_info']; ?></label>
                </div>
                <div class="radio">
                    <label><input value="none" <?php checked( $prop_agent_display_option, 'none' ); ?> type="radio" class="rwmb-radio" name="fave_agent_display_option"><?php echo $houzez_local['hide_info_box']; ?></label>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="fave_agents" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                <option value="-1"><?php echo $houzez_local['none']; ?></option>
                                <?php
                                if (!empty($agents_posts)) {
                                    foreach ($agents_posts as $agent_post) {
                                        echo '<option '.selected( $prop_agent_id, $agent_post->ID ).' value="'.$agent_post->ID.'">'.$agent_post->post_title.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>