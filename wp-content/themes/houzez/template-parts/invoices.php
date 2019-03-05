<?php
/**
 * Invoices - template/user_dashboard_invoices
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/04/16
 * Time: 11:34 PM
 */
global $houzez_local, $dashboard_invoices;
$invoice_data = houzez_get_invoice_meta( get_the_ID() );
$user_info = get_userdata($invoice_data['invoice_buyer_id']);
$invoice_detail = add_query_arg( 'invoice_id', get_the_ID(), $dashboard_invoices );
?>
<tr>
    <td>#<?php echo get_the_ID(); ?></td>
    <td><?php echo get_the_date(); ?></td>
    <td class="<?php echo $houzez_local['billing_for']; ?>">
        <?php
            if( $invoice_data['invoice_billion_for'] != 'package' && $invoice_data['invoice_billion_for'] != 'Package' ) {
                echo esc_html($invoice_data['invoice_billion_for']);
            } else {
                echo get_the_title( get_post_meta( get_the_ID(), 'HOUZEZ_invoice_item_id', true) );
            }
        ?>
    </td>
    <td class="<?php echo $houzez_local['billing_type']; ?>"> <?php echo esc_html( $invoice_data['invoice_billing_type'] ); ?> </td>
    <td>
        <?php
        $invoice_status = get_post_meta(  get_the_ID(), 'invoice_payment_status', true );
        if( $invoice_status == 0 ) {
            echo '<span class="label label-warning">'.esc_html__( 'Not Paid', 'houzez' ).'</span>';
        } else {
            echo '<span class="label label-success">'.esc_html__( 'Paid', 'houzez' ).'</span>';
        }
        ?>
    </td>
    <td class="<?php echo $houzez_local['payment_method']; ?>">
        <?php if( $invoice_data['invoice_payment_method'] == 'Direct Bank Transfer' ) {
            echo $houzez_local['bank_transfer'];
        } else {
            echo $invoice_data['invoice_payment_method'];
        } ?>
    </td>
    <td><?php echo houzez_get_invoice_price( $invoice_data['invoice_item_price'] );?></td>
    <td><a href="<?php echo esc_url($invoice_detail); ?>" class="btn btn-invoice btn-primary"><?php echo esc_html__( 'View Details', 'houzez' ); ?></a></td>
</tr>