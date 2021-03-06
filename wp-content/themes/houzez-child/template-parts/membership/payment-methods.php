<?php

global $current_user;

$current_user = wp_get_current_user();
$userID = $current_user->ID;

$selected_package_id = $_GET['selected_package'];

$payment1 = get_post_meta( $selected_package_id, 'fave_payment_option1', true );
$payment2 = get_post_meta( $selected_package_id, 'fave_payment_option2', true );
$payment3 = get_post_meta( $selected_package_id, 'fave_payment_option3', true );
$payment4 = get_post_meta( $selected_package_id, 'fave_payment_option4', true );
$payment5 = get_post_meta( $selected_package_id, 'fave_payment_option5', true );
$payment6 = get_post_meta( $selected_package_id, 'fave_payment_option6', true );
$payment7 = get_post_meta( $selected_package_id, 'fave_payment_option7', true );

$pack_title = get_the_title( $selected_package_id ) . ' Package';

if (isset($_GET['option'])) {
    $option = $_GET['option'];
} else {
    if ($payment7 != '' && $payment7 > 0)
        $option = 'option7';
    if ($payment6 != '' && $payment6 > 0)
        $option = 'option6';
    if ($payment5 != '' && $payment5 > 0)
        $option = 'option5';
    if ($payment4 != '' && $payment4 > 0)
        $option = 'option4';
    if ($payment3 != '' && $payment3 > 0)
        $option = 'option3';
    if ($payment2 != '' && $payment2 > 0)
        $option = 'option2';
    if ($payment1 != '' && $payment1 > 0)
        $option = 'option1';
}

$pack_price = get_post_meta( $selected_package_id, 'fave_payment_' . $option, true );

$tax = get_post_meta( $selected_package_id, 'fave_package_tax', true );

$pack_price = floor(($pack_price * (1 + (int)$tax / 100)) * 100) / 100;

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
$enable_bitcoin = houzez_option('enable_bitcoin');
$enable_applepay = houzez_option('enable_applepay');
$enable_googlepay = houzez_option('enable_googlepay');

$houzez_auto_recurring = houzez_option('houzez_auto_recurring');

$checked_paypal = $checked_stripe = $checked_bank = '';
if($enable_paypal != 0 && !isset($_GET['state'])) {
    $checked_paypal = 'checked';
} elseif( $enable_paypal != 1 && $enable_stripe != 0 ) {
    $checked_stripe = 'checked';
} elseif( $enable_paypal != 1 && $enable_stripe != 1 && $enable_wireTransfer != 0 ) {
    $checked_bank = 'checked';
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
        <div class="method-type">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/paypal-icon.png" alt="paypal">
        </div>
    </div>
    <?php if( $houzez_auto_recurring != 1 ) { ?>
        <div class="method-option">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="paypal_package_recurring" id="paypal_package_recurring" value="1">
                    <?php esc_html_e( 'Set as recurring payment', 'houzez' ); ?>
                </label>
            </div>
        </div>
    <?php
        } else {
            if ($option == 'option1') {
                echo '<input style="display: none;" type="checkbox" checked name="paypal_package_recurring" id="paypal_package_recurring" value="0">';
            } else {
                echo '<input style="display: none;" type="checkbox" checked name="paypal_package_recurring" id="paypal_package_recurring" value="1">';
            }
        }
    }
    ?>

    <?php if( $enable_stripe != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-stripe" name="houzez_payment_type" value="stripe" <?php echo $checked_stripe;?>>
                    <?php esc_html_e( 'Pay by Credit Card', 'houzez'); ?>
                </label>
                <?php houzez_stripe_payment_membership( $selected_package_id, $pack_price, $pack_title ); ?>
            </div>
        </div>
        <div class="method-type">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/credit-card-icon.png" alt="credit card">
        </div>
    </div>
    <?php if( $houzez_auto_recurring != 1 ) { ?>
        <div class="method-option">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="stripe_package_recurring" id="stripe_package_recurring" value="1">
                    <?php esc_html_e( 'Set as recurring payment', 'houzez' ); ?>
                </label>
            </div>
        </div>
    <?php
        } else {
            if ($option == 'option1') {
                echo '<input type="hidden" name="houzez_stripe_recurring" id="houzez_stripe_recurring" value="0">';
            } else {
                echo '<input type="hidden" name="houzez_stripe_recurring" id="houzez_stripe_recurring" value="1">';
            }
        }
    }
    ?>

    <?php if( $enable_2checkout != 0 && is_user_logged_in() && $enable_stripe != 1 ) { ?>
        <div class="method-row">
            <div class="method-select">
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-2checkout" name="houzez_payment_type" value="2checkout">
                        <?php esc_html_e( 'Credit Card', 'houzez'); ?>
                    </label>
                </div>
            </div>
            <div class="method-type">
                <img src="<?php echo get_template_directory_uri(); ?>/images/2checkout.jpg" alt="2checkout">
            </div>
        </div>
        <div class="method-option payment_method_twocheckout">
            <?php houzez_2checkout_payment_membership(); ?>
        </div>
    <?php } ?>

    <?php if( $enable_bitcoin != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-bitcoin" name="houzez_payment_type" value="bitcoin"
                        <?php if (isset($_GET['state'])) echo 'checked'; ?>>
                    <?php esc_html_e( 'Bitcoin', 'houzez' ); ?>
                </label>
            </div>
            <input type="hidden" value="https://www.coinbase.com/oauth/authorize/?response_type=code&client_id=<?php echo houzez_option('coinbaseID')?>&redirect_uri=https%3A%2F%2Famstaging.unfstaging.com%2Fcomplete-order&state=<?php echo $pack_price; ?>%2C<?php echo $selected_package_id; ?>%2C<?php echo $option; ?>" />
        </div>
        <div class="method-type">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/bitcoin-icon.png" alt="bitcoin">
        </div>
    </div>
    <?php } ?>

    <?php if( $enable_googlepay != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-googlepay" name="houzez_payment_type" value="googlepay">
                    <?php esc_html_e( 'Google Pay', 'houzez' ); ?>
                </label>
                <?php houzez_googlepay_payment( $selected_package_id, $pack_price, $pack_title, $option ); ?>
            </div>
        </div>
        <div class="method-type">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/googlepay-icon.png" alt="googlepay">
        </div>
    </div>
    <?php } ?>

    <?php if( $enable_applepay != 0 ) { ?>
    <div class="method-row">
        <div class="method-select">
            <div class="radio">
                <label>
                    <input type="radio" class="payment-applepay" name="houzez_payment_type" value="applepay">
                    <?php esc_html_e( 'Apple Pay', 'houzez' ); ?>
                </label>
                <?php houzez_applepay_payment( $selected_package_id, $pack_price, $pack_title, $option ); ?>
            </div>
        </div>
        <div class="method-type">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/applepay-icon.png" alt="applepay">
        </div>
    </div>
    <?php } ?>

</div>

<input type="hidden" name="houzez_package_id" value="<?php echo esc_attr($selected_package_id); ?>">
<input type="hidden" class="houzez_package_price" name="houzez_package_price" value="<?php echo esc_attr($pack_price); ?>">

<button id="houzez_complete_membership" type="submit" class="btn btn-success btn-submit"> <?php esc_html_e( 'Complete Membership', 'houzez' ); ?> </button>
<button id="houzez_complete_membership_2checkout" type="submit"
        class="btn btn-success btn-submit hidden"> <?php esc_html_e('Complete Membership', 'houzez'); ?> </button>
<span class="help-block"><?php echo sprintf( wp_kses(__( 'By clicking "Complete Membership" you agree to our <a target="_blank" href="%s">Terms & Conditions</a>', 'houzez' ), $allowed_html_array), 'https://www.affordablemallorca.com/legal'/*get_permalink($terms_conditions)*/ ); ?></span>