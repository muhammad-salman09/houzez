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

    $('.advanced-search .bootstrap-select').prev().change(function() {
        $(this).closest('form').submit();
    });

    $('.advanced-search .bootstrap-select button').mouseover(function() {
        $(this).find('.filter-option').css('color', '#55d2d8');
        $(this).find('.filter-option').css('cursor', 'pointer');
        $(this).find('.filter-option').css('font-style', 'italic');
        $(this).find('.fa-sort').css('border', 'solid #55d2d8');
        $(this).find('.fa-sort').css('border-width', '0 2px 2px 0');
    });

    $('.advanced-search .bootstrap-select button').mouseout(function() {
        var color = '#001489';

        if ($(this).closest('.advanced-search').hasClass('front'))
            color = '#ffffff';

        $(this).find('.filter-option').css('color', color);
        $(this).find('.filter-option').css('cursor', 'pointer');
        $(this).find('.filter-option').css('font-style', 'normal');
        $(this).find('.fa-sort').css('border', 'solid ' + color);
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

    $('.btn-upload').click(function() {
        var data = new FormData();

        var count = $('.doc_content div').children().length;

        var title = $('#doc_title').val();
        var file = $('#doc_file');
        var fileObject = file[0].files[0];

        if (count < 5 && title != '' && typeof(fileObject) !== 'undefined') {
            var doc_size = Math.ceil(fileObject['size'] / 1024 / 1024);
            var doc_type = fileObject['type'];

            if (doc_size < 10 && doc_type == 'application/pdf') {
                var ajaxurl = HOUZEZ_ajaxcalls_vars.admin_url + 'admin-ajax.php';

                data.append('file', fileObject);
                data.append('action', 'houzez_doc_upload');
                
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        result = JSON.parse(result.substring(0, result.length - 1));

                        count++;

                        $('.doc_content p').text('List Encrypted files (' + count + ' of 5)');

                        var url = result['url'];
                        var link = ' ( <a href="' + url + '">View</a> )'
                        $('.doc_content div').append('<p>' + title + link + '</p>');

                        $('#doc_title').val('');
                        file.val('');
                    }
                });
            } else {
                alert('The uploaded file must be PDF type under 10MB.');
                file.val('');
            }
        }
    });

    /*var geocoder = new google.maps.Geocoder();
    var address = "new york";

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();
            alert(latitude);
        } 
    });*/
});