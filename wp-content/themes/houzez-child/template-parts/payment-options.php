<?php
if(!session_id()) {
	session_start();
}

$option = '';
$selected_package_id = $_GET['selected_package'];

if (isset($_GET['state'])) {
	$value = explode(',', urldecode($_GET['state']));

	$option = $value[0];
	$selected_package_id = $value[1];
}

$payment1 = get_post_meta( $selected_package_id, 'fave_payment_option1', true );
$payment2 = get_post_meta( $selected_package_id, 'fave_payment_option2', true );
$payment3 = get_post_meta( $selected_package_id, 'fave_payment_option3', true );
$payment4 = get_post_meta( $selected_package_id, 'fave_payment_option4', true );
$payment5 = get_post_meta( $selected_package_id, 'fave_payment_option5', true );
$payment6 = get_post_meta( $selected_package_id, 'fave_payment_option6', true );
$payment7 = get_post_meta( $selected_package_id, 'fave_payment_option7', true );

$cValue  = get_post_meta( $selected_package_id, 'fave_billing_custom_value', true );
$cOption = get_post_meta( $selected_package_id, 'fave_billing_custom_option', true );

if (isset($_GET['option'])) {
	$option = $_GET['option'];
} else if ($option == '') {
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

$_SESSION['option'] = $option;
?>

<div class="info-title">
    <h2 class="info-title-left"> <?php echo esc_html('Select Payment Option', 'houzez'); ?> </h2>
</div>

<div class="option-select-block">
	<?php if ($payment1 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option1" value="option1" 
	    	<?php if ($option == 'option1') echo 'checked'; ?>/>
	    <label for="option1"><?php echo esc_attr('Daily €' . $payment1); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment2 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option2" value="option2" 
	    	<?php if ($option == 'option2') echo 'checked'; ?>/>
	    <label for="option2"><?php echo esc_attr('Weekly €' . $payment2); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment3 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option3" value="option3" 
	    	<?php if ($option == 'option3') echo 'checked'; ?>/>
	    <label for="option3"><?php echo esc_attr('Monthly €' . $payment3); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment4 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option4" value="option4" 
	    	<?php if ($option == 'option4') echo 'checked'; ?>/>
	    <label for="option4"><?php echo esc_attr('Every 3 months €' . $payment4); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment5 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option5" value="option5" 
	    	<?php if ($option == 'option5') echo 'checked'; ?>/>
	    <label for="option5"><?php echo esc_attr('Every 6 months €' . $payment5); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment6 != '') { ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option6" value="option6" 
	    	<?php if ($option == 'option6') echo 'checked'; ?>/>
	    <label for="option6"><?php echo esc_attr('Yearly €' . $payment6); ?></label>
	</div>
	<?php } ?>

	<?php if ($payment7 != '') { 
        $arr = array(
            'custom1' => 'days',
            'custom2' => 'weeks',
            'custom3' => 'months'
        );

        $str = $cValue . ' ' . $arr[$cOption];
    ?>
	<div class="radio">
	    <input type="radio" class="payment_option" name="payment_option" id="option7" value="option7" 
	    	<?php if ($option == 'option7') echo 'checked'; ?>/>
	    <label for="option7"><?php echo esc_attr($str . ' €' . $payment7); ?></label>
	</div>
	<?php } ?>
</div>