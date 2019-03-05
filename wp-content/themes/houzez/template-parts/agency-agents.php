<?php
/**
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 26/12/16
 * Time: 5:38 AM
 * Since v1.5.0
 */
global $current_user;
$current_user = wp_get_current_user();
$userID       = $current_user->ID;
$dash_profile_link = houzez_get_template_link_2('template/user_dashboard_profile.php');

$wp_user_query = new WP_User_Query( array(
    array( 'role' => 'houzez_agent' ),
    'meta_key' => 'fave_agent_agency',
    'meta_value' => $userID
));
$agents = $wp_user_query->get_results();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="agents-info-area">
            <!--<div class="agents-info-list-search">
                <form>
                    <div class="single-input-search">
                        <input class="form-control" placeholder="Search Agents" type="text">
                        <button type="submit"></button>
                    </div>
                </form>
            </div>-->
            <div class="agent-info-list-wrap">
                <table class="table table-striped agent-info-table">
                    <thead>
                    <tr>
                        <th><?php echo esc_html__('Agent Name', 'houzez');?></th>
                        <th><?php echo esc_html__('Email', 'houzez');?></th>
                        <th><?php echo esc_html__('Listings', 'houzez');?></th>
                        <th><?php echo esc_html__('Phone', 'houzez');?></th>
                        <th><?php echo esc_html__('Mobile', 'houzez');?></th>
                        <th><?php echo esc_html__('Actions', 'houzez');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if( !empty($agents) ) {
                        foreach ($agents as $agent) {
                            $agent_info = get_userdata($agent->ID);
                            //print_r($agent_info);
                            $agency_agent_edit = add_query_arg('edit_agent', $agent->ID, $dash_profile_link);
                            $first_name = $agent_info->first_name;
                            $last_name = $agent_info->last_name;

                            if( !empty($first_name) && !empty($last_name) ) {
                                $agent_name = $first_name.' '.$last_name;
                            } else {
                                $agent_name = $agent_info->display_name;
                            }
                            $user_agent_id = get_user_meta( $agent->ID, 'fave_author_agent_id', true );

                            if( !empty( $user_agent_id ) ) {
                                if( 'publish' == get_post_status ( $user_agent_id ) ) {
                                    $agent_permalink = get_permalink($user_agent_id);
                                } else {
                                    $agent_permalink = get_author_posts_url( $agent->ID );
                                }

                            } else {
                                $agent_permalink = get_author_posts_url( $agent->ID );
                            }
                            ?>

                            <tr>
                                <td><?php echo esc_attr($agent_name); ?></td>
                                <td><?php echo $agent_info->user_email; ?></td>
                                <td><?php echo count_user_posts( $agent->ID , 'property' );?></td>
                                <td><?php echo get_user_meta( $agent->ID, 'fave_author_phone', true); ?></td>
                                <td><?php echo get_user_meta( $agent->ID, 'fave_author_mobile', true); ?></td>
                                <td class="agent-list-actions">
                                    <a href="<?php echo esc_url($agency_agent_edit); ?>" class="btn btn-primary btn-sm"><i
                                            class="fa fa-edit"></i></a>
                                    <a data-agentid="<?php echo $agent->ID; ?>" href="button" class="houzez_delete_agency_agent btn btn-primary btn-sm"><i class="fa fa-trash"></i></a>
                                    <a href="<?php echo esc_url($agent_permalink); ?>" class="btn btn-primary btn-sm"><i
                                            class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            <?php
                            wp_nonce_field( 'agent_delete_nonce', 'agent_delete_security' );
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!--start pagination-->
            <!--<div class="pagination-main">
                <ul class="pagination">
                    <li class="disabled"><a aria-label="Previous" href="#"><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></a></li>
                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a aria-label="Next" href="#"><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></a></li>
                </ul>
            </div>-->
            <!--end pagination-->
        </div>
    </div>
</div>