/*
 Theme Name: Houzez Child Theme
 Description: Houzez Child Theme
 Version: 1.0
 */

$ = jQuery;

$(document).ready(function() {
	var left = parseInt($('.header-left').width());
	var logo = parseInt($('.logo').width());
	var nav = parseInt($('.main-nav').width());

	var right = parseInt($('.header-right').width());
	right -= parseInt($('.header-right>:first-child').width());
	right -= parseInt($('.header-right>:last-child').width());

	var val = (left - logo - nav + right) / 2;

	$('.main-nav').css('margin-left', val + 'px');

    $('.bootstrap-select button').mouseover(function() {
        $(this).find('.filter-option').css('color', '#55d2d8');
        $(this).find('.filter-option').css('cursor', 'pointer');
        $(this).find('.filter-option').css('font-style', 'italic');
        $(this).find('.fa-sort').css('border', 'solid #55d2d8');
        $(this).find('.fa-sort').css('border-width', '0 2px 2px 0');
    });

    $('.bootstrap-select button').mouseout(function() {
        $(this).find('.filter-option').css('color', '#ffffff');
        $(this).find('.filter-option').css('cursor', 'pointer');
        $(this).find('.filter-option').css('font-style', 'normal');
        $(this).find('.fa-sort').css('border', 'solid #ffffff');
        $(this).find('.fa-sort').css('border-width', '0 2px 2px 0');
    });

	$('.btn-type').click(function() {
		$('.btn-type').removeClass('btn-primary');
		$(this).addClass('btn-primary');

		if ($(this).text() == 'Buy') {
			$('#type').val('for-sale');
			$('.advance-title').text('Search Properties for Sale');
		}

		if ($(this).text() == 'Rent') {
			$('#type').val('for-rent');
			$('.advance-title').text('Search Properties for Rent');
		}
	});

	var min_price = parseInt($('#min_price').val());
	var max_price = parseInt($('#max_price').val());

	var thousands_separator = HOUZEZ_ajaxcalls_vars.thousands_separator;

	$(".price-range").slider({
        range: true,
        min: 1000,
        max: 500000,
        values: [min_price, max_price],
        slide: function (event, ui) {
            var min_price_range = addCommas(ui.values[0]);
            var max_price_range = addCommas(ui.values[1]);

            $(".min-price-range-hidden").val( min_price_range );
            $(".max-price-range-hidden").val( max_price_range );

            $(".min-price-range").text( min_price_range );
            $(".max-price-range").text( max_price_range );
        },
        stop: function( event, ui ) {

            if($("#houzez-listing-map").length > 0 || $('#mapViewHalfListings').length > 0 ) {
                var current_page = 0;
                var current_form = $(this).parents('form');
                var form_widget = $(this).parents('form');
                houzez_search_on_change(current_form, form_widget, current_page);
            }
        }
    });

    var min_price = addCommas(min_price);
    var max_price = addCommas(max_price);

    $(".min-price-range-hidden").val(min_price);
    $(".max-price-range-hidden").val(max_price);

    $(".min-price-range").text(min_price);
    $(".max-price-range").text(max_price);

    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + thousands_separator + '$2');
        }
        return x1 + x2;
    }

	$('#cCalculate').click(function() {
        var monthly_payment = HOUZEZ_ajaxcalls_vars.monthly_payment;
        var weekly_payment = HOUZEZ_ajaxcalls_vars.weekly_payment;
        var bi_weekly_payment = HOUZEZ_ajaxcalls_vars.bi_weekly_payment;
        var currency_symb = HOUZEZ_ajaxcalls_vars.currency_symbol;

        var totalPrice  = 0;
        var down_payment = 0;
        var term_years  = 0;
        var interest_rate = 0;
        var amount_financed  = 0;
        var monthInterest = 0;
        var intVal = 0;
        var mortgage_pay = 0;
        var annualCost = 0;
        var payment_period;
        var mortgage_pay_text;


        payment_period = $('#mc_payment_period').val();

        totalPrice = $('#mc_total_amount').val().replace(/,/g, '');
        down_payment = $('#mc_down_payment').val().replace(/,/g, '');
        amount_financed = totalPrice - down_payment;
        term_years =  parseInt ($('#mc_term_years').val(),10) * payment_period;
        interest_rate = parseFloat ($('#mc_interest_rate').val(),10);
        monthInterest = interest_rate / (payment_period * 100);
        intVal = Math.pow( 1 + monthInterest, -term_years );
        mortgage_pay = amount_financed * (monthInterest / (1 - intVal));
        annualCost = mortgage_pay * payment_period;

        if( payment_period == '12' ) {
            mortgage_pay_text = monthly_payment;

        } else if ( payment_period == '26' ) {
            mortgage_pay_text = bi_weekly_payment;

        } else if ( payment_period == '52' ) {
            mortgage_pay_text = weekly_payment;

        }

        var currency = $('#mc_currency').val();
        var currency_symb = '';

        switch(currency) {
			case 'eur':
				currency_symb = '€';
				break;
			case 'usd':
				currency_symb = '$';
				break;
			case 'gbp':
				currency_symb = '£';
				break;
			case 'btc':
				currency_symb = '฿';
				break;
		}

        $('#mortgage_mwbi').text(mortgage_pay_text + ": " + currency_symb + (Math.round(mortgage_pay * 100)) / 100);
        $('#amount_financed').text(currency_symb + (Math.round(amount_financed * 100)) / 100);
        $('#mortgage_pay').text(currency_symb + (Math.round(mortgage_pay * 100)) / 100);
        $('#annual_cost').text(currency_symb + (Math.round(annualCost * 100)) / 100);

        $('#total_mortgage_with_interest').html();
        $('.morg-detail').show();
		/*var price = 0;
		var mortgage = 0;
		var interest = 0;
		var length = 0;

		if ($('#cPrice').val() != '')
			price = parseInt($('#cPrice').val());
		if ($('#cMortgage').val() != '')
			mortgage = parseInt($('#cMortgage').val());
		if ($('#cInterest').val() != '')
			interest = parseInt($('#cInterest').val());
		if ($('#cLength').val() != '')
			length = parseInt($('#cLength').val());

		var currency = $('#cCurrency').val();
		var type = $('#cType').val();

		var monthly = 0;
		var total_interest = 0;
		var total_pay = 0;
		var prefix = '';

		if (price != 0 && mortgage != 0 && interest != 0 && length != 0) {
			if (type == 'repayment') {
				rate = interest / 100 / 12;
				monthly = mortgage * rate * Math.pow((1 + rate), 12 * length) / (Math.pow((1 + rate), 12 * length) - 1);
				total_interest = monthly * (12 * length) - mortgage;
			}

			if (type == 'interest') {
				monthly = mortgage / 12 * (interest / 100);
				total_interest = mortgage / (interest / 100) * length;
			}

			total_pay = mortgage + Math.round(total_interest);

			switch(currency) {
				case 'eur':
					prefix = '€';
					break;
				case 'usd':
					prefix = '$';
					break;
				case 'gbp':
					prefix = '£';
					break;
				case 'btc':
					prefix = '฿';
					break;
			}

			var txt = '';
			txt += '<h2>Your Mortgage information</h2>';
			txt += '<p><b>Monthly Payments : </b>' + prefix + Math.round(monthly) + '</p>';
			txt += '<p><b>House Price : </b>' + prefix + price + '</p>';
			txt += '<p><b>Mortgage Amount : </b>' + prefix + mortgage + '</p>';
			txt += '<p><b>Total Interest : </b>' + prefix + Math.round(total_interest) + '</p>';
			txt += '<p><b>Total to Pay : </b>' + prefix + total_pay + '</p>';
			txt += '<p><b>Total Repayments : </b>' + (12 * length) + '</p>';

			$('.mortgage-info').empty().append(txt);
		}*/
	});

	$('input[type=number]').on('keydown', function(evt) {
	    var key = evt.charCode || evt.keyCode || 0;

	    return (key == 8 || key == 9 || key == 46 || key == 110 || key == 190 ||
	            (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
	});

	/*--------------------------------------------------------------------------
     *   Make Property of the Week - only for membership
     * -------------------------------------------------------------------------*/
    $('.make-prop-week').click(function (e) {
        e.preventDefault();

        if (confirm('Are you sure you want to make this a property of the week?')) {
            var prop_id = $(this).attr('data-propid');

            make_prop_week(prop_id, $(this));
            $(this).unbind("click");
        }
    });

    function make_prop_week( prop_id, currentDiv ) {
    	var ajaxurl = HOUZEZ_ajaxcalls_vars.admin_url + 'admin-ajax.php';

    	$.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'JSON',
            data: {
                'action' : 'houzez_make_prop_week',
                'propid' : prop_id
            },
            success: function() {

                var prnt = currentDiv.parents('.item-wrap');
                prnt.find('.item-thumb').append('<span class="label-week label">Property of the Week</span>');
                currentDiv.remove();
                window.location.reload();

            }

        });
    }

    $('.remove-prop-week').click(function (e) {
        e.preventDefault();

        if (confirm('Are you sure you want to remove from property of the week?')) {
            var prop_id = $(this).attr('data-propid');

            remove_prop_week(prop_id, $(this));
            $(this).unbind("click");
        }
    });

    function remove_prop_week( prop_id, currentDiv ) {
    	var ajaxurl = HOUZEZ_ajaxcalls_vars.admin_url + 'admin-ajax.php';

    	$.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'JSON',
            data: {
                'action' : 'houzez_remove_prop_week',
                'propid' : prop_id
            },
            success: function() {

                var prnt = currentDiv.parents('.item-wrap');
                prnt.find('.label-week').remove();
                currentDiv.remove();
                window.location.reload();

            }
        });
    }
});