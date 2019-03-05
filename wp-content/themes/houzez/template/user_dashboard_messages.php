<?php
/**
 * Template Name: User Dashborad Messages
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/12/16
 * Time: 7:47 PM
 * Since v1.5.0
 */

if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $wpdb, $current_user;
wp_get_current_user();
$userID = $current_user->ID;

?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-content-area-table dashboard-fix">
        

        <?php

        if ( isset( $_REQUEST['thread_id'] ) && !empty( $_REQUEST['thread_id'] ) ) {

            get_template_part('template-parts/messages/message-detail');

        } else {

            get_template_part('template-parts/messages/messages');

        }

        ?>

                
    </div>
</div>

<?php get_footer(); ?>