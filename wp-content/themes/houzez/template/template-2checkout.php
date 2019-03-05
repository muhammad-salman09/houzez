<?php
/**
 * Template Name: 2Checkout
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 19/12/16
 * Time: 4:06 PM
 * Since v1.5.0
 */
global $current_user;

$current_user = wp_get_current_user();
$userID       =   $current_user->ID;
$admin_email  =  get_bloginfo('admin_email');
$username     =   $current_user->user_login;
$price_listing_submission = houzez_option('price_listing_submission');
$price_featured_listing_submission = houzez_option('price_featured_listing_submission');
$submission_currency = houzez_option('currency_paid_submission');
$thankyou_page_link = houzez_get_template_link('template/template-thankyou.php');
$time = time();
$date = date('Y-m-d H:i:s',$time);
$privateKey = houzez_option('tco_private_key');
$publishableKey = houzez_option('tco_publishable_key');
$sellerId = houzez_option('tco_sellerID');
$paymentAPI = houzez_option('paypal_api');
$paymentMethod = '2Checkout';
$allowed_html = array();

require_once( get_template_directory() . '/framework/2checkout/lib/Twocheckout.php' );

Twocheckout::privateKey($privateKey); //Private Key
Twocheckout::sellerId($sellerId); // 2Checkout Account Number
Twocheckout::verifySSL(false);  // this is set to true by default

if( $paymentAPI == 'sandbox' ) {
    Twocheckout::sandbox(true); // Set to false for production accounts.
}

$houzez_2checkout    =  $_POST['houzez_2checkout'];
$tc_chname    =  $_POST['tc_chname'];
$tc_chaddress    =  $_POST['tc_chaddress'];
$tc_chcity    =  $_POST['tc_chcity'];
$tc_chstate    =  $_POST['tc_chstate'];
$tc_chzipCode    =  $_POST['tc_chzipCode'];
$tc_chcountry    =  $_POST['tc_chcountry'];
$tc_chemail    =  $_POST['tc_chemail'];
$tc_chphone    =  $_POST['tc_chphone'];

if( $houzez_2checkout == 'per_listing' ) {

    $userId      = intval( $_POST['userID'] );
    $listing_id  = intval( $_POST['houzez_property_id'] );
    $is_featured = intval( $_POST['featured_pay'] );
    $is_upgrade  = intval( $_POST['is_upgrade'] );

    $token  = wp_kses ( $_POST['token'] ,$allowed_html);
    $houzez_listing_price = floatval($price_listing_submission);

    if( $is_featured == 1 ) {
        $houzez_listing_price = floatval($price_listing_submission) + floatval($price_featured_listing_submission);
    }

    if( $is_upgrade == 1 ) {
        $houzez_listing_price = floatval($price_featured_listing_submission);
    }

    try {
        $charge = Twocheckout_Charge::auth(array(
            "merchantOrderId" => $listing_id,
            "token" => $token,
            "currency" => $submission_currency,
            "total" => $houzez_listing_price,
            "billingAddr" => array(
                "name" => $tc_chname,
                "addrLine1" => $tc_chaddress,
                "city" => $tc_chcity,
                "state" => $tc_chstate,
                "zipCode" => $tc_chzipCode,
                "country" => $tc_chcountry,
                "email" => $tc_chemail,
                "phoneNumber" => $tc_chphone
            )
        ));

        if ($charge['response']['responseCode'] == 'APPROVED') {

            if( $is_upgrade == 1 ) {
                update_post_meta( $listing_id, 'fave_featured', 1 );
                $invoice_id = houzez_generate_invoice( 'Upgrade to Featured', 'one_time', $listing_id, $date, $userID, 0, 1, '', $paymentMethod );
                update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $invoice_id,
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'featured_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_featured_submission_listing', $args);

            } else {
                update_post_meta( $listing_id, 'fave_payment_status', 'paid' );

                $paid_submission_status    = houzez_option('enable_paid_submission');
                $listings_admin_approved = houzez_option('listings_admin_approved');

                if( $listings_admin_approved != 'yes'  && $paid_submission_status == 'per_listing' ){
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                    );
                    $post_id =  wp_update_post($post );
                } else {
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'pending'
                    );
                    $post_id =  wp_update_post($post );
                }


                if( $is_featured == 1 ) {
                    update_post_meta( $listing_id, 'fave_featured', 1 );
                    $invoice_id = houzez_generate_invoice( 'Publish Listing with Featured', 'one_time', $listing_id, $date, $userID, 1, 0, '', $paymentMethod );
                } else {
                    $invoice_id = houzez_generate_invoice( 'Listing', 'one_time', $listing_id, $date, $userID, 0, 0, '', $paymentMethod );
                }
                update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $invoice_id,
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'paid_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_paid_submission_listing', $args);
            }

            wp_redirect( $thankyou_page_link );
            exit;

        }
    } catch (Twocheckout_Error $e) {
        print_r($e->getMessage());
    }

} else if( $houzez_2checkout == 'membership' ) {

    $houzez_package_price = floatval($_POST['houzez_package_price']);
    $pack_id = intval($_POST['houzez_package_id']);
    $invoiceID = houzez_generate_invoice('package', 'one_time', $pack_id, $date, $userID, 0, 0, '', $paymentMethod);
    $token  = wp_kses ( $_POST['token'] ,$allowed_html);

    try {
        $charge = Twocheckout_Charge::auth(array(
            "merchantOrderId" => $invoiceID,
            "token" => $token,
            "currency" => $submission_currency,
            "total" => $houzez_package_price,
            "billingAddr" => array(
                "name" => $tc_chname,
                "addrLine1" => $tc_chaddress,
                "city" => $tc_chcity,
                "state" => $tc_chstate,
                "zipCode" => $tc_chzipCode,
                "country" => $tc_chcountry,
                "email" => $tc_chemail,
                "phoneNumber" => $tc_chphone
            )
        ));

        if ($charge['response']['responseCode'] == 'APPROVED') {
            houzez_save_user_packages_record($userID);
            if (houzez_check_user_existing_package_status($current_user->ID, $pack_id)) {
                houzez_downgrade_package($current_user->ID, $pack_id);
                houzez_update_membership_package($userID, $pack_id);
            } else {
                houzez_update_membership_package($userID, $pack_id);
            }
            update_post_meta($invoiceID, 'invoice_payment_status', 1);
            $args = array();
            houzez_email_type($user_email, 'purchase_activated_pack', $args);

            wp_redirect($thankyou_page_link);
            exit;

        }
    } catch (Twocheckout_Error $e) {
        print_r($e->getMessage());
    }
}
wp_die();