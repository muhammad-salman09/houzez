<?php
/**
 * Template Name: Mollie Testing template
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 20/12/16
 * Time: 11:22 PM
 * Since v1.5.0
 */
require_once( get_template_directory() . '/framework/mollie-api-php/src/Mollie/API/Autoloader.php' );

$mollie = new Mollie_API_Client;
$mollie->setApiKey("test_Rm8HhW8y3sexP6whAUCtDUn2u2TQ32");

global $houzez_local, $current_user;
wp_get_current_user();
$userID = $current_user->ID;
$user_email = $current_user->user_email;
$paymentMethod = 'IDEAL';
$time = time();
$date = date('Y-m-d H:i:s',$time);

// get transfer data
$save_data = get_option('houzez_mollie_package');
$id = $save_data[$userID]['id'];
$pack_id = $save_data[$userID]['package_id'];

//$payment = $mollie->payments->get($id);

$payment    = $mollie->payments->get($_POST["id"]);

/*print_r($payment);*/
/*
 * The order ID saved in the payment can be used to load the order and update it's
 * status
 */
$order_id = $payment->metadata->order_id;
$userID = $payment->metadata->user_id;
$pack_id = $payment->metadata->package_id;

if ($payment->isPaid())
{
    /*
     * At this point you'd probably want to start the process of delivering the product
     * to the customer.
     */
    houzez_save_user_packages_record($userID);
    if( houzez_check_user_existing_package_status($userID, $pack_id) ){
        houzez_downgrade_package($userID, $pack_id);
        houzez_update_membership_package($userID, $pack_id);
    }else{
        houzez_update_membership_package($userID, $pack_id);
    }

    $invoiceID = houzez_generate_invoice( 'package', 'one_time', $pack_id, $date, $userID, 0, 0, '', $paymentMethod );
    update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

    $args = array();
    houzez_email_type( $user_email,'purchase_activated_pack', $args );
}
elseif ($payment->isOpen())
{
    /*
     * The payment is open anymore.
     */
    $new_property = array(
        'post_type'	=> 'property',
        'post_title' => 'Just Open'
    );

    $prop_id = wp_insert_post( $new_property );
}
elseif (! $payment->isOpen())
{
    /*
     * The payment isn't paid and isn't open anymore. We can assume it was aborted.
     */
    $new_property = array(
        'post_type'	=> 'property',
        'post_title' => "The payment isn't paid and isn't open anymore. We can assume it was aborted."
    );

    $prop_id = wp_insert_post( $new_property );
} else {
    $new_property = array(
        'post_type'	=> 'property',
        'post_title' => "Wooooooooooow"
    );

    $prop_id = wp_insert_post( $new_property );
}