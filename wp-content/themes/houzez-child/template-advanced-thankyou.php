<?php
/**
 * Template Name: Thank You & Payment Process - Bitcoin
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !is_user_logged_in() ) {
    wp_redirect( home_url() );
}
global $houzez_local, $current_user;
wp_get_current_user();
$userID = $current_user->ID;

$user_email = $current_user->user_email;
$admin_email      =  get_bloginfo('admin_email');

$allowed_html   =   array();

$enable_paid_submission = houzez_option('enable_paid_submission');
$dash_profile_link = houzez_get_dashboard_profile_link();

if( $enable_paid_submission == 'membership' ) {
    /*-----------------------------------------------------------------------------------*/
    // Stripe payments for membeship packages
    /*-----------------------------------------------------------------------------------*/
    if (isset($_GET['state'])) {
        $paymentMethod = 'Bitcoin';
        $time = time();
        $date = date('Y-m-d H:i:s',$time);

        $value = explode(',', urldecode($_GET['state']));

        $price = $value[0];
        $pack_id = $value[1];

        $invoiceID = houzez_generate_invoice( 'package', 'one_time', $pack_id, $date, $userID, 0, 0, '', $paymentMethod );
        update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

        $fave_meta['invoice_billion_for'] = 'package';
        $fave_meta['invoice_billing_type'] = 'one_time';
        $fave_meta['invoice_item_id'] = $pack_id;
        $fave_meta['invoice_item_price'] = $price;
        $fave_meta['invoice_purchase_date'] = $date;
        $fave_meta['invoice_buyer_id'] = $userID;
        $fave_meta['invoice_payment_method'] = $paymentMethod;
        update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );
    }
}
get_header();

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">

            <?php get_template_part('template-parts/create-listing-top'); ?>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="block-success">
                        <div class="block-success-inner">
                            <div class="done-icon"><i class="fa fa-check"></i></div>
                            <?php
                                echo '<h2>';
                                echo houzez_option('thankyou_title');
                                echo '</h2>';
                                echo '<p>';
                                echo houzez_option('thankyou_des');
                                echo '</p>';
                            ?>
                            <a href="<?php echo esc_url( $dash_profile_link ); ?>" class="btn btn-primary btn-long"> <?php echo $houzez_local['goto_dash']; ?> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>