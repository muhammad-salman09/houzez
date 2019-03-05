<?php
/**
 * Template Name: User Dashboard GDPR Request
 */


global $current_user;
wp_get_current_user();
$userID = $current_user->ID;
?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="profile-content-area">

                        <form action="" method="post" id="houzez_gdpr_form">

                            <div class="account-block account-profile-block">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <h4><?php esc_html_e( 'GDPR Request', 'houzez' ); ?></h4>
                                        <p><?php esc_html_e('An email will be sent to the user at this email address asking them to verify the request.', 'houzez');?></p>


                                        <ul class="list-unstyled">
                                            <li><strong><?php esc_html_e( 'Select your request*', 'houzez' ); ?></strong></li>
                                            <li class="checkbox">
                                                <label for="gdrf_data_export">
                                                    <input id="gdrf_data_export" type="radio" name="gdrf_data_type" value="export_personal_data"> 

                                                    <?php esc_html_e( 'Export Personal Data', 'houzez' ); ?>
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label for="gdrf_data_remove">
                                                    <input id="gdrf_data_remove" type="radio" name="gdrf_data_type" value="remove_personal_data"> 
                                                    <?php esc_html_e( 'Remove Personal Data', 'houzez' ); ?>
                                                </label>
                                            </li>
                                            <li>
                                                <label for="gdrf_data_email">
                                                    <?php esc_html_e( 'Your email address*', 'houzez' ); ?>
                                                </label>
                                                <input class="form-control" type="email" id="gdrf_data_email" name="gdrf_data_email" />
                                            </li>
                                        </ul>
                                        <div>
                                            <input type="hidden" name="houzez_gdrf_data_nonce" id="houzez_gdrf_data_nonce" value="<?php echo wp_create_nonce( 'houzez_gdrf_nonce' ); ?>" />

                                            <button class="btn btn-primary" id="houzez_gdpr_data"><?php esc_html_e('Send Request','houzez');?></button>
                                        </div>
                                        <div id="houzez_gdpr_messages" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php get_footer(); ?>