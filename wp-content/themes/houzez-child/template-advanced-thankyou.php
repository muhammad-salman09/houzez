<?php
/**
 * Template Name: Thank You & Payment Process - Not Paypal
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
$add_new_link = houzez_get_template_link('template/submit_property.php');

if( $enable_paid_submission == 'membership' ) {
    /*-----------------------------------------------------------------------------------*/
    // Stripe payments for membeship packages
    /*-----------------------------------------------------------------------------------*/
    if (isset($_GET['state'])) {
        $paymentMethod = 'Bitcoin';
        $time = time();
        $date = date('Y-m-d H:i:s', $time);

        $value = explode(',', urldecode($_GET['state']));

        $price = $value[0];
        $pack_id = $value[1];
        $option = $value[2];

        houzez_update_membership_package( $userID, $pack_id );

        if ($option == 'option1')
            $invoice_billing_type = 'One Time';
        else
            $invoice_billing_type = 'Recurring';

        $invoiceID = houzez_generate_invoice( 'package', 'one_time', $pack_id, $date, $userID, 0, 0, '', $paymentMethod );

        $fave_meta['invoice_billion_for'] = 'package';
        $fave_meta['invoice_billing_type'] = $invoice_billing_type;
        $fave_meta['invoice_item_id'] = $pack_id;
        $fave_meta['invoice_item_price'] = $price;
        $fave_meta['invoice_purchase_date'] = $date;
        $fave_meta['invoice_buyer_id'] = $userID;
        $fave_meta['invoice_payment_method'] = $paymentMethod;
        $fave_meta['invoice_payment_status'] = 1;
        
        update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );

        update_post_meta( $invoiceID, 'invoice_payment_status', 1 );
    }

    /*-----------------------------------------------------------------------------------*/
    // Googlepay payments for membeship packages
    /*-----------------------------------------------------------------------------------*/
    if (isset($_GET['pay']) && $_GET['pay'] == 'google') {
        $paymentMethod = 'Google Pay';
        $time = time();
        $date = date('Y-m-d H:i:s', $time);

        $pack_id = $_GET['id'];
        $price = $_GET['price'];

        houzez_update_membership_package( $userID, $pack_id );

        if ($_GET['option'] == 'option1')
            $invoice_billing_type = 'One Time';
        else
            $invoice_billing_type = 'Recurring';

        $invoiceID = houzez_generate_invoice( 'package', 'one_time', $pack_id, $date, $userID, 0, 0, '', $paymentMethod );

        $fave_meta['invoice_billion_for'] = 'package';
        $fave_meta['invoice_billing_type'] = $invoice_billing_type;
        $fave_meta['invoice_item_id'] = $pack_id;
        $fave_meta['invoice_item_price'] = $price;
        $fave_meta['invoice_purchase_date'] = $date;
        $fave_meta['invoice_buyer_id'] = $userID;
        $fave_meta['invoice_payment_method'] = $paymentMethod;
        $fave_meta['invoice_payment_status'] = 1;
        
        update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );

        update_post_meta( $invoiceID, 'invoice_payment_status', 1 );
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
                                the_title();
                                echo '</h2>';
                                echo '<p>';
                                the_content();
                                echo '</p>';
                            ?>
                            <a href="<?php echo esc_url( $add_new_link ); ?>" class="btn btn-primary btn-long"> <?php echo esc_html__('Add New Property'); ?> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>