/*
 Theme Name: Houzez Child Theme
 Description: Houzez Child Theme
 Version: 1.0
 */

jQuery(document).ready(function() {
    jQuery('.billing').closest('.rwmb-row').addClass('billing');
    jQuery('.payment_option').closest('.rwmb-row').addClass('payment');

    for (var i = 1; i < 8; i++) {
    	if (jQuery('#fave_payment_option' + i).val() != '') {
            if (i > 1)
                jQuery('#fave_billing_unit').closest('.rwmb-column').hide();

    		jQuery('#fave_payment_option' + i).closest('.rwmb-row').addClass('selected');
    		jQuery('#fave_billing_time_unit').find('option[value=option' + i + ']').hide();
    	}
    }

    if (jQuery('#fave_payment_option7').closest('.payment').hasClass('selected')) {
        jQuery('#fave_payment_option7').closest('.payment').find('.rwmb-column:first-child .rwmb-input span')
        .text(jQuery('#fave_billing_custom_value').val() + ' ' + jQuery('#fave_billing_custom_option option:selected').text());
    }

    jQuery('#fave_billing_time_unit').change(function() {
        if (jQuery(this).val() == 'option7') {
            jQuery('.billing.rwmb-row>div:nth-child(2)').show();
            jQuery('.billing.rwmb-row>div:nth-child(3)').show();
        } else {
            jQuery('.billing.rwmb-row>div:nth-child(2)').hide();
            jQuery('.billing.rwmb-row>div:nth-child(3)').hide();
        }
    });

    jQuery('#fave_billing_unit_add').click(function() {
    	var option = jQuery('#fave_billing_time_unit').val();

    	if (option == '') {
    		jQuery('#fave_billing_time_unit').css('border', '1px solid #ff0000');
    	} else {
            if (option != 'option1')
                jQuery('#fave_billing_unit').closest('.rwmb-column').hide();

            var flag = true;

            if (option == 'option7') {
                var cVal = jQuery('#fave_billing_custom_value').val();
                var cOpt = jQuery('#fave_billing_custom_option').val();

                if (cVal == '' || cVal == 0) {
                    jQuery('#fave_billing_custom_value').css('border', '1px solid #ff0000');
                    flag = false;
                }

                if (cOpt == '') {
                    jQuery('#fave_billing_custom_option').css('border', '1px solid #ff0000');
                    flag = false;
                }

                if (flag) {
                    jQuery('.billing.rwmb-row>div:nth-child(2)').hide();
                    jQuery('.billing.rwmb-row>div:nth-child(3)').hide();

                    jQuery('#fave_payment_' + option).closest('.payment').find('.rwmb-column:first-child .rwmb-input span')
                        .text(cVal + ' ' + jQuery('#fave_billing_custom_option option:selected').text());
                }
            }

            if (flag) {
                jQuery('#fave_billing_time_unit').css('border', '1px solid #ddd');
                
                jQuery('#fave_billing_time_unit').find('option[value=' + option + ']').hide();
                jQuery('#fave_payment_' + option).closest('.payment').show();
            }
    	}
    });

    jQuery('.payment_option button').click(function() {
    	var len = jQuery(this).attr('id').length;
	    var option = 'option' + jQuery(this).attr('id').substring(len - 1, len );

        jQuery(this).closest('.payment').hide();
        jQuery('#fave_billing_time_unit').find('option[value=' + option + ']').show();

        jQuery('#fave_payment_' + option).val('');
        jQuery('#fave_plan_' + option).val('');

    	if (jQuery(this).closest('.payment').hasClass('selected')) {
    		var ajaxurl = houzez_admin_vars.ajaxurl;
	    	var postID = jQuery('#post_ID').val();
	    	var metaKey = jQuery(this).closest('.rwmb-column').prev().prev().find('input').attr('id');

	    	jQuery.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'JSON',
	            data: {
	                'action' : 'houzez_remove_payment_option',
	                'postID' : postID,
	                'metaKey' : metaKey
	            },
	            success: function(data) {
	            }
	        });
    	}

        jQuery(this).closest('.payment').removeClass('selected');
        
        var flag = true;

        jQuery('.payment').each(function() {
            if (jQuery(this).css('display') != 'none')
                flag = false;
        });

        if (flag)
            jQuery('#fave_billing_unit').closest('.rwmb-column').show();
    });
});