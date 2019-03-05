<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/09/16
 * Time: 12:57 PM
 */
$price_listing_submission = houzez_option('price_listing_submission');
$price_featured_listing_submission = houzez_option('price_featured_listing_submission');
$property_id = isset( $_GET['prop-id'] ) ? $_GET['prop-id'] : '';
$upgrade_id = isset( $_GET['upgrade_id'] ) ? $_GET['upgrade_id'] : '';

if( empty( $property_id ) ) {
    $property_id = $upgrade_id;
}
$terms_conditions = houzez_option('payment_terms_condition');
$allowed_html_array = array(
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array()
    )
);
$enable_paypal = houzez_option('enable_paypal');
$enable_stripe = houzez_option('enable_stripe');
$enable_2checkout = houzez_option('enable_2checkout');
$enable_wireTransfer = houzez_option('enable_wireTransfer');
$is_upgrade = 0;
if( !empty( $upgrade_id ) ) {
    $is_upgrade = 1;
}

$checked_paypal = $checked_stripe = $checked_bank = '';
if($enable_paypal != 0 ) {
    $checked_paypal = 'checked';
} elseif( $enable_paypal != 1 && $enable_stripe != 0 ) {
    $checked_stripe = 'checked';
} elseif( $enable_paypal != 1 && $enable_stripe != 1 && $enable_wireTransfer != 0 ) {
    $checked_bank = 'checked';
} else {

}
?>
<div class="method-select-block">

    <?php if( $enable_paypal != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-paypal" name="houzez_payment_type" value="paypal" <?php echo $checked_paypal;?>>
                    <?php esc_html_e( 'Paypal', 'houzez'); ?>
                </label>
            </div>
        </div>
        <div class="method-type"><img src="<?php echo get_template_directory_uri(); ?>/images/paypal-icon.jpg" alt="paypal"></div>
    </div>
    <?php } ?>

    <?php if( $enable_stripe != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-stripe" name="houzez_payment_type" value="stripe" <?php echo $checked_stripe;?>>
                    <?php esc_html_e( 'Stripe', 'houzez'); ?>
                </label>
                <?php houzez_stripe_payment_perlisting( $property_id, $price_listing_submission, $price_featured_listing_submission ); ?>
            </div>
        </div>
        <div class="method-type"><img src="<?php echo get_template_directory_uri(); ?>/images/stripe-icon.jpg" alt="stripe"></div>
    </div>
    <?php } ?>

    <?php if( $enable_2checkout != 0  && is_user_logged_in()  && $enable_stripe != 1 ) { ?>
        <div class="method-row">
            <div class="method-select">
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-2checkout" name="houzez_payment_type" value="2checkout">
                        <?php esc_html_e( 'Credit Card', 'houzez'); ?>
                    </label>
                </div>
            </div>
            <div class="method-type"><img src="<?php echo get_template_directory_uri(); ?>/images/2checkout.jpg" alt="2checkout"></div>
        </div>
        <div class="method-option payment_method_twocheckout">
            <?php houzez_2checkout_payment(); ?>
        </div>
    <?php } ?>

    <?php if( $enable_wireTransfer != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" name="houzez_payment_type" value="direct_pay" <?php echo $checked_bank;?>>
                    <?php esc_html_e( 'Direct Bank Transfer', 'houzez' ); ?>
                </label>
            </div>
        </div>
        <div class="method-type method-description">
            <p> <?php esc_html_e( 'Make your payment direct into our bank account. Please use order ID as the payment reference', 'houzez' ); ?> </p>
        </div>
    </div>
    <?php } ?>

</div>
<input type="hidden" id="houzez_property_id" name="houzez_property_id" value="<?php echo intval( $property_id ); ?>">
<input type="hidden" id="houzez_listing_price" name="houzez_listing_price" value="<?php echo esc_attr($price_listing_submission); ?>">
<input type="hidden" id="featured_pay" name="featured_pay" value="0">
<input type="hidden" id="is_upgrade" name="is_upgrade" value="<?php echo intval($is_upgrade); ?>">
<button id="houzez_complete_order" type="button" class="btn btn-success btn-submit"> <?php esc_html_e( 'Complete Payment', 'houzez' ); ?> </button>
<button id="houzez_complete_order_2checkout" type="button" class="btn btn-success btn-submit hidden"> <?php esc_html_e( 'Complete Payment', 'houzez' ); ?> </button>
<span class="help-block"><?php echo sprintf( wp_kses(__( 'By clicking "Complete Payment" you agree to our <a target="_blank" href="%s">Terms & Conditions</a>', 'houzez' ), $allowed_html_array), get_permalink($terms_conditions) ); ?></span>