<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 03/01/17
 * Time: 4:04 AM
 */
global $houzez_local, $dashboard_invoices, $current_user;
wp_get_current_user();
$userID         = $current_user->ID;
$user_login     = $current_user->user_login;
$user_email     = $current_user->user_email;
$first_name     = $current_user->first_name;
$last_name     = $current_user->last_name;
$user_address = get_user_meta( $userID, 'fave_author_address', true);
if( !empty($first_name) && !empty($last_name) ) {
    $fullname = $first_name.' '.$last_name;
} else {
    $fullname = $current_user->display_name;
}
$invoice_id = $_GET['invoice_id'];
$post = get_post( $invoice_id );
$invoice_data = houzez_get_invoice_meta( $invoice_id );

$publish_date = $post->post_date;
$publish_date = date_i18n( get_option('date_format'), strtotime( $publish_date ) );
$invoice_logo = houzez_option( 'invoice_logo', false, 'url' );
$invoice_company_name = houzez_option( 'invoice_company_name' );
$invoice_address = houzez_option( 'invoice_address' );
$invoice_phone = houzez_option( 'invoice_phone' );
$invoice_additional_info = houzez_option( 'invoice_additional_info' );
$invoice_thankyou = houzez_option( 'invoice_thankyou' );
?>
<div class="container">
<div class="row dashboard-inner-main">
    <div class="col-lg-10 col-md-9 col-sm-12 dashboard-inner-left">
        <div class="invoice-area">
            <div class="invoice-detail">
                <div class="invoice-header">
                    <div class="invoice-head-left">
                        <?php if( !empty($invoice_logo) ) { ?>
                            <img src="<?php echo esc_url($invoice_logo); ?>" alt="logo">
                        <?php } ?>
                    </div>
                    <div class="invoice-date">
                        <p class="invoice-number"><strong> <?php echo esc_html__('INVOICE', 'houzez'); ?> </strong> : <?php echo esc_attr($invoice_id); ?> </p>
                        <p class="invoice-date"><strong><?php echo esc_html__('DATE', 'houzez'); ?></strong> : <?php echo $publish_date; ?> </p>
                    </div>
                </div>

                <div class="invoice-contact">
                    <div class="invoice-contact-left">
                        <p><strong><?php echo esc_html__('To:', 'houzez'); ?></strong><br>
                            <?php echo esc_attr($fullname); ?><br>
                            <?php if( !empty($user_address)) { echo $user_address.'<br>'; }?>
                            <?php echo esc_html__('Email:', 'houzez'); ?> <?php echo esc_attr($user_email);?>
                        </p>
                    </div>
                    <div class="invoice-contact-right">
                        <?php if( !empty($invoice_company_name) ) { ?>
                        <h2> <?php echo esc_attr($invoice_company_name); ?></h2>
                        <?php } ?>
                        <p>
                            <?php
                            echo $invoice_address;
                            if( !empty($invoice_phone) ) {
                                echo '<br>'.esc_html__('Phone:', 'houzez'); echo esc_attr($invoice_phone);
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <table class="table invoice-total">
                    <tr>
                        <td class="description"> <?php echo $houzez_local['billing_for']; ?> </td>
                        <td class="amount">
                            <?php
                                if( $invoice_data['invoice_billion_for'] != 'package' && $invoice_data['invoice_billion_for'] != 'Package' ) {
                                    echo esc_html($invoice_data['invoice_billion_for']);
                                } else {
                                    echo esc_html__('Membership Plan', 'houzez').' '. get_the_title( get_post_meta( $invoice_id, 'HOUZEZ_invoice_item_id', true) );
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="description"> <?php echo $houzez_local['billing_type']; ?> </td>
                        <td class="amount"> <?php echo esc_html( $invoice_data['invoice_billing_type'] ); ?> </td>
                    </tr>
                    <tr>
                        <td class="description"> <?php echo $houzez_local['payment_method']; ?> </td>
                        <td class="amount">
                            <?php if( $invoice_data['invoice_payment_method'] == 'Direct Bank Transfer' ) {
                                echo $houzez_local['bank_transfer'];
                            } else {
                                echo $invoice_data['invoice_payment_method'];
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="description"> </td>
                        <td class="amount"><strong><?php echo $houzez_local['invoice_price']; ?></strong> <?php echo houzez_get_invoice_price( $invoice_data['invoice_item_price'] )?> </td>
                    </tr>
                </table>

                <?php if( !empty($invoice_additional_info) || !empty($invoice_thankyou) ) { ?>
                <div class="invoice-info">
                    <?php if( !empty($invoice_additional_info)) { ?>
                        <h3> <?php echo esc_html__('Additional Information:', 'houzez'); ?> </h3>
                        <p> <?php echo $invoice_additional_info; ?> </p>
                    <?php } ?>
                    <?php if( !empty($invoice_thankyou) ) { ?>
                        <h3> <?php echo $invoice_thankyou; ?> </h3>
                    <?php } ?>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-12 dashboard-inner-right">
        <div class="dashboard-sidebar">
            <div class="dashboard-sidebar-inner">
                <a href="#" id="invoice-print-button" data-id="<?php echo intval($invoice_id); ?>" class="btn btn-primary btn-block"><?php echo esc_html__('Print Invoice', 'houzez'); ?></a>
                <a href="<?php echo esc_url($dashboard_invoices); ?>" class="btn btn-secondary btn-block"><?php echo esc_html__('Go back', 'houzez'); ?></a>
            </div>
        </div>
    </div>
</div>
</div>
