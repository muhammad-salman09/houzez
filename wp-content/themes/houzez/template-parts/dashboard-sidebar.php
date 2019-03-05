<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 29/11/16
 * Time: 7:04 PM
 * Since v1.5.0
 */
global $houzez_local, $current_user, $post;

wp_get_current_user();
$userID         = $current_user->ID;
$packages_page_link = houzez_get_template_link('template/template-packages.php');
$enable_paid_submission = houzez_option('enable_paid_submission');
$remaining_listings = houzez_get_remaining_listings( $userID );

$edit_property = isset($_GET['edit_property']) ? $_GET['edit_property'] : '';
if(!empty($edit_property)) {
    $property_status = get_post_status($edit_property);
} else {
    $property_status = 'new';
}
?>
<?php if( is_page_template('template/submit_property.php') ) { ?>
    <div class="dashboard-sidebar">
        <div class="dashboard-sidebar-inner">
            <?php if( ($enable_paid_submission == 'membership' || $enable_paid_submission == 'per_listing') && $property_status == 'publish') { ?>
                <!-- <button id="put_on_hold" data-propid="<?php echo intval($edit_property); ?>" class="put_on_hold btn btn-default btn-block"><?php echo esc_html__('Put on Hold', 'houzez');?></button> -->
            <?php } else { ?>    
                <button id="save_as_draft" class="btn btn-default btn-block"><?php echo esc_html__('Save as draft', 'houzez');?></button>
            <?php } ?>
        </div>
        <?php if( $enable_paid_submission == 'membership' ) { ?>
        <div class="dashboard-sidebar-inner">
            <?php houzez_get_user_current_package( $userID ); ?>
        </div>
        <?php } ?>
    </div>
<?php } ?>