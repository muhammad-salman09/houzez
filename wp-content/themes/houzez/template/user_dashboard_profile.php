<?php
/**
 * Template Name: User Dashboard Profile Page
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 02/10/15
 * Time: 4:22 PM
 */

global $current_user;
wp_get_current_user();
$userID = $current_user->ID;
$dash_profile_link = houzez_get_dashboard_profile_link();

/*-----------------------------------------------------------------------------------*/
// Social Logins
/*-----------------------------------------------------------------------------------*/
if( ( isset($_GET['code']) && isset($_GET['state']) ) ){
    houzez_facebook_login($_GET);

} else if( isset( $_GET['openid_mode']) && $_GET['openid_mode'] == 'id_res' ) {
    houzez_openid_login($_GET);

} else if (isset($_GET['code'])){
    houzez_google_oauth_login($_GET);

} else {
    if ( !is_user_logged_in() ) {
        wp_redirect(  home_url() );
    }
}
?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    if( isset( $_GET['agents'] ) && $_GET['agents'] == 'list' ) {
                        get_template_part('template-parts/agency-agents');
                    } elseif( isset( $_GET['agent'] ) && $_GET['agent'] == 'add_new' ) {
                        get_template_part('template-parts/agency-new-agent');
                    }  elseif( isset( $_GET['edit_agent'] ) && !empty( $_GET['edit_agent'] ) ) {
                        get_template_part('template-parts/agency-edit-agent');
                    } else {
                        get_template_part('template-parts/user-profile');
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>