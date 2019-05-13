<?php
/**
 * Template Name: Thank You - Additional Package
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
$listings_admin_approved = houzez_option('listings_admin_approved');

$dash_profile_link = houzez_get_dashboard_profile_link();

$is_paypal_live  =   houzez_option('paypal_api');
/*-----------------------------------------------------------------------------------*/
// Paypal payments for membeship packages
/*-----------------------------------------------------------------------------------*/
if (isset($_GET['token'])) {
    $allowed_html = array();
    $token = wp_kses($_GET['token'], $allowed_html);
    $token_recursive = wp_kses($_GET['token'], $allowed_html);
    $paymentMethod = 'Paypal';
    $time = time();
    $date = date('Y-m-d H:i:s',$time);

    // get transfer data
    $save_data = get_option('houzez_paypal_addon_package');
    $payment_execute_url = $save_data[$userID]['payment_execute_url'];
    $token = $save_data[$userID]['access_token'];
    $property_id = $save_data[$userID]['property_id'];
    $property_option = $save_data[$userID]['property_option'];

    $price = array(
    	'featured' => 750,
    	'week'     => 1000
    );

    $recursive = 0;
    if (isset ($save_data[$userID]['recursive'])) {
        $recursive = $save_data[$userID]['recursive'];
    }

    if ($recursive != 1) {
        if (isset($_GET['PayerID'])) {
            $payerId = wp_kses($_GET['PayerID'], $allowed_html);

            $payment_execute = array(
                'payer_id' => $payerId
            );

            $json = json_encode($payment_execute);
            $json_resp = houzez_execute_paypal_request($payment_execute_url, $json, $token);

            $save_data[$current_user->ID] = array();

            update_option('houzez_paypal_addon_package', $save_data);
            update_user_meta($userID, 'houzez_paypal_property', $save_data);

            if ($json_resp['state'] == 'approved') {

                update_post_meta( $property_id, 'fave_' . $property_option, 1 );

                $invoiceID = houzez_generate_invoice( 'package', 'one_time', $property_id, $date, $userID, 0, 0, '', $paymentMethod );

                if ($property_option == 'featured')
		        	$fave_meta['invoice_billion_for'] = 'Featured Property';
		        if ($property_option == 'week')
		        	$fave_meta['invoice_billion_for'] = 'Property of the Week';
                
		        $fave_meta['invoice_billing_type'] = 'one_time';
		        $fave_meta['invoice_item_id'] = $property_id;
		        $fave_meta['invoice_item_price'] = $price[$property_option];
		        $fave_meta['invoice_purchase_date'] = $date;
		        $fave_meta['invoice_buyer_id'] = $userID;
		        $fave_meta['invoice_payment_method'] = $paymentMethod;
		        $fave_meta['invoice_payment_status'] = 1;
		        
		        update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );

                $args = array();

                houzez_email_type( $user_email,'purchase_activated_pack', $args );

            }
        }
    } else {
        $payment_execute = array();
        $json = json_encode($payment_execute);
        $json_resp = houzez_execute_paypal_request($payment_execute_url, $json, $token);

        if($json_resp['state']=='Active' && $json_resp['payer']['status'] == 'verified' ) {

            $profileID = $json_resp['id'];

            update_post_meta( $property_id, 'fave_' . $property_option, 1 );

            delete_post_meta($property_id, 'houzez_paypal_billing_plan_'.$is_paypal_live);

            $invoiceID = houzez_generate_invoice( 'package', 'one_time', $property_id, $date, $userID, 0, 0, '', $paymentMethod );
                
            if ($property_option == 'featured')
	        	$fave_meta['invoice_billion_for'] = 'Featured Property';
	        if ($property_option == 'week')
	        	$fave_meta['invoice_billion_for'] = 'Property of the Week';

	        $fave_meta['invoice_billing_type'] = 'one_time';
	        $fave_meta['invoice_item_id'] = $property_id;
	        $fave_meta['invoice_item_price'] = $price[$property_option];
	        $fave_meta['invoice_purchase_date'] = $date;
	        $fave_meta['invoice_buyer_id'] = $userID;
	        $fave_meta['invoice_payment_method'] = $paymentMethod;
	        $fave_meta['invoice_payment_status'] = 1;
	        
	        update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );

            update_user_meta( $userID, 'houzez_paypal_recurring_profile_id', $profileID );

            $args = array();

            houzez_email_type( $user_email,'purchase_activated_pack', $args );
        }
    }

}
/*-----------------------------------------------------------------------------------*/
// Bitcoin payments for membeship packages
/*-----------------------------------------------------------------------------------*/
if (isset($_GET['state'])) {
    $paymentMethod = 'Bitcoin';
    $time = time();
    $date = date('Y-m-d H:i:s',$time);

    $value = explode(',', urldecode($_GET['state']));

    $price = $value[0];
    $property_id = $value[1];
    $option = $value[2];

    update_post_meta( $property_id, 'fave_' . $option, 1 );

    $invoiceID = houzez_generate_invoice( 'package', 'one_time', '', $date, $userID, 0, 0, '', $paymentMethod );

    if ($option == 'featured')
    	$fave_meta['invoice_billion_for'] = 'Featured Property';
    if ($option == 'week')
    	$fave_meta['invoice_billion_for'] = 'Property of the Week';

    $fave_meta['invoice_billing_type'] = 'One Time';
    $fave_meta['invoice_item_id'] = $property_id;
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

    $option = $_GET['option'];
    $property_id = $_GET['id'];
    $price = $_GET['price'];

    update_post_meta( $property_id, 'fave_' . $option, 1 );

    $invoiceID = houzez_generate_invoice( 'package', 'one_time', $property_id, $date, $userID, 0, 0, '', $paymentMethod );

    if ($option == 'featured')
        $fave_meta['invoice_billion_for'] = 'Featured Property';
    if ($option == 'week')
        $fave_meta['invoice_billion_for'] = 'Property of the Week';

    $fave_meta['invoice_billing_type'] = 'One Time';
    $fave_meta['invoice_item_id'] = $property_id;
    $fave_meta['invoice_item_price'] = $price;
    $fave_meta['invoice_purchase_date'] = $date;
    $fave_meta['invoice_buyer_id'] = $userID;
    $fave_meta['invoice_payment_method'] = $paymentMethod;
    $fave_meta['invoice_payment_status'] = 1;
    
    update_post_meta( $invoiceID, '_houzez_invoice_meta', $fave_meta );

    update_post_meta( $invoiceID, 'invoice_payment_status', 1 );
}

get_header();

$panel_class = 'dashboard-with-panel';
$houzez_loggedin = true;

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right <?php echo esc_attr($panel_class);?>">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">

            <?php get_template_part('template-parts/add-package-option'); ?>

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
                            <a href="<?php echo esc_url( $dash_profile_link ); ?>" class="btn btn-primary btn-long"> <?php echo $houzez_local['goto_dash']; ?> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>