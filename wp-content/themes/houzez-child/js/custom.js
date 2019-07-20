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

    if ($('body').hasClass('page-template-template-user-dashboard-properties') ||
        $('body').hasClass('page-template-template-user-dashboard-document') ||
        $('body').hasClass('page-template-template-document-upload') ||
        $('body').hasClass('page-template-template-advanced-thankyou') ||
        $('body').hasClass('page-template-template-advanced-package') ||
        $('body').hasClass('page-template-template-addon-thankyou') ||
        $('body').hasClass('houzez-dashboard')
        ) {
        var secHeight = $('#section-body').height();
        var headHeight = $('#header-section').height();
        var footHeight = $('#footer-section').height();
        var adminHeight = $('#wpadminbar').height();
        var winHeight = $(window).height();

        var setHeight = winHeight - headHeight - footHeight - adminHeight;

        if (secHeight < setHeight)
            $('#section-body').height(setHeight);
    }

    if ($('body').hasClass('page-template-template-thankyou')) {
        var direct = window.location.protocol + '//' + window.location.hostname + '/add-new-property';
        $('.block-success-inner a').attr('href', direct);
        $('.block-success-inner a').text('Add New Property')
    }

    $('.sel-lang').change(function() {
        var url_string = window.location;
        var url = new URL(url_string);

        url.searchParams.set('lang', $(this).val());
        window.location.href = url.href;
    });

    var url = new URL(window.location.href);
    var login = url.searchParams.get('login');

    if (login && login == 'required') {
        $('.header-right .user a').click();
        $('body').addClass('modal-open');

        $('#pop-login').addClass('in');
        $('#pop-login').css('display', 'block');
    }

    var url = new URL(window.location.href);
    var sign = url.searchParams.get('sign');

    if (sign && sign == 'required') {
        $('.header-right .user a').click();
        $('body').addClass('modal-open');

        $('#pop-login').addClass('in');
        $('#pop-login').css('display', 'block');

        $('#pop-login .login-tabs li').removeClass('active');
        $('#pop-login .login-tabs .houzez_register').addClass('active');

        $('#pop-login .tab-content .tab-pane').removeClass('in active');
        $('#pop-login .tab-content .tab-pane:last-child').addClass('in active');
    }

    var from = $('select[name="currency"]').val();
    if (from == '')
        from = 'EUR';
    var to = from;

    var min_price = '';
    var max_price = '';
    min_price = parseInt($('#min_price').val());
    max_price = parseInt($('#max_price').val());

    $('select[name="currency"]').change(function() {
        to = $(this).val();

        if (to == '')
            to = 'EUR';

        min_price = parseInt($('.min-price-range-hidden').val().replace(',', ''));
        max_price = parseInt($('.max-price-range-hidden').val().replace(',', ''));

        $.ajax({
            type: 'POST',
            url: '/wp-json/v1/houzez_get_rate',
            data: {from: from, to: to},
            success: function(result) {
                var rate = parseFloat(result);

                min_price = parseInt(min_price * rate);
                max_price = parseInt(max_price * rate);

                rangeSlider(min_price, max_price, to);

                from = to;
            }
        });

    });

    if (!isNaN(min_price) && !isNaN(max_price)) {
        from = 'EUR';

        $.ajax({
            type: 'POST',
            url: '/wp-json/v1/houzez_get_rate',
            data: {from: from, to: to},
            success: function(result) {
                var rate = parseFloat(result);

                rangeSlider(min_price, max_price, to);

                from = to;
            }
        });
    }

    function rangeSlider(min_price, max_price, to) {
        var max_val = 500000;

        if (to == 'XBT')
            max_val = 50;
        if (to == 'ETH')
            max_val = 2000;

        if (max_price > max_val)
            max_price = max_val;

        $(".price-range").slider({
            range: true,
            min: 0,
            max: max_val,
            values: [min_price, max_price],
            slide: function (event, ui) {
                var min_price_range = addCommas(ui.values[0]);
                var max_price_range = addCommas(ui.values[1]);

                $(".min-price-range-hidden").val( min_price_range );
                $(".max-price-range-hidden").val( max_price_range );

                $(".min-price-range").text( min_price_range );
                $(".max-price-range").text( max_price_range );
            }
        });

        var min_price = addCommas(min_price);
        var max_price = addCommas(max_price);

        $(".min-price-range-hidden").val(min_price);
        $(".max-price-range-hidden").val(max_price);

        $(".min-price-range").text(min_price);
        $(".max-price-range").text(max_price);
    }

    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

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

	$('#cCalculate').click(function() {
        var monthly_payment = 'Monthly Payment';
        var weekly_payment = 'Weekly Payment';
        var bi_weekly_payment = 'Bi-Weekly Payment';

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
        term_years =  parseInt ($('#mc_term_years').val(), 10) * payment_period;
        interest_rate = parseFloat ($('#mc_interest_rate').val(), 10);
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

    $('.addon-type').click(function() {
        var url_string = $('.btn-next a').attr('href');
        var url = new URL(url_string);

        url.searchParams.set('option', $(this).val());

        $('.btn-next a').attr('href', url.href);
    });

    $('.btn-upload').click(function() {
        var url = new URL(window.location.href);
        var post_id = url.searchParams.get('listing_id');

        var data = new FormData();

        var count = $('.doc_content table tbody tr').length;

        var title = $('#doc_title').val();
        var file = $('#doc_file');
        var fileObject = file[0].files[0];

        if (count == 4) {
            $(this).attr('disabled', 'disabled');
            $('#doc_title').attr('disabled', 'disabled');
            $('#doc_file').attr('disabled', 'disabled');
        }

        if (count < 5 && title != '' && typeof(fileObject) !== 'undefined') {
            var doc_size = Math.ceil(fileObject['size'] / 1024 / 1024);
            var doc_type = fileObject['type'];

            if (doc_size < 10 && doc_type == 'application/pdf') {
                data.append('file', fileObject);
                data.append('title', title);
                data.append('post_id', post_id);

                $.ajax({
                    type: 'POST',
                    url: '/wp-json/v1/houzez_doc_upload',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result != 'fail') {
                            res = result.split('/');

                            count++;

                            $('.doc_content>p').text('List Encrypted files (' + count + ' of 5)');

                            var txt = '';

                            if (count == 1) {
                                txt += '<table><thead><th>No</th><th>Title</th><th>File Name</th>';
                                txt += '<th>Share Email</th><th></th></thead><tbody>';
                                txt += '<tr><td>1</td><td>' + title + '</td><td>' + res[0] + '</td>';
                                txt += '<td><input type="text" class="share_email"></td>';
                                txt += '<td><a href="javascript:void(0);" class="doc_view">View</a> / ';
                                txt += '<a href="javascript:void(0);" class="doc_remove">Remove</a> / ';
                                txt += '<a href="javascript:void(0);" class="doc_share">Share</a>';
                                txt += '<input type="hidden" value="' + title + '/' + result + '" /></td></tr>';
                                txt += '</tbody></table>';

                                $('.doc_content').append(txt);
                            } else {
                                txt += '<tr><td>' + count + '</td><td>' + title + '</td><td>' + res[0] + '</td>';
                                txt += '<td><input type="text" class="share_email"></td>';
                                txt += '<td><a href="javascript:void(0);" class="doc_view">View</a> / ';
                                txt += '<a href="javascript:void(0);" class="doc_remove">Remove</a> / ';
                                txt += '<a href="javascript:void(0);" class="doc_share">Share</a>';
                                txt += '<input type="hidden" value="' + title + '/' + result + '" /></td></tr>';

                                $('.doc_content table tbody').append(txt);
                            }

                            $('#doc_title').val('');
                            file.val('');
                        }
                    }
                });
            } else {
                alert('The uploaded file must be PDF type under 10MB.');
                file.val('');
            }
        }
    });

    $(document).on('click', 'a.doc_view', function() {
        var file = $(this).parent().prev().prev().text();
        var url = new URL(window.location.href);

        window.open(url + '&file=' + file,'_blank');
    });

    $(document).on('click', 'a.doc_remove', function() {
        var url = new URL(window.location.href);
        var post_id = url.searchParams.get('listing_id');

        var title = $(this).parent().prev().prev().prev().text();
        var file = $(this).parent().prev().prev().text();

        $(this).closest('tr').addClass('removeP');

        $.ajax({
            type: 'POST',
            url: '/wp-json/v1/houzez_doc_remove',
            data: {title: title, file: file, post_id: post_id},
            success: function(result) {
                if (result == 'success') {
                    $('.removeP').remove();

                    var count = $('.doc_content table tbody tr').length;
                    if (count == 0)
                        $('.doc_content table').remove();
                    
                    $('.doc_content>p').text('');
                    if (count > 0) {
                        $('.doc_content>p').text('List Encrypted files (' + count + ' of 5)');

                        for (var i = 1; i < count + 1; i++) {
                            $('.doc_content table tbody tr:nth-child(' + i + ') td:first-child').text(i);
                        }
                    }
                }
            }
        });
    });

    $(document).on('click', 'a.doc_share', function() {
        var url = new URL(window.location.href);
        var post_id = url.searchParams.get('listing_id');

        var enc = $(this).next().val();

        var mail = $(this).parent().prev().find('input').val();

        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (re.test(mail)) {
            $.ajax({
                type: 'POST',
                url: '/wp-json/v1/houzez_doc_share',
                data: {post_id: post_id, enc: enc, mail: mail},
                success: function(result) {
                    if (result)
                        alert('Document sent to ' + mail + ' successfully.');
                }
            });
        } else {
            alert('Email address is not valid.');
        }
    });

    $('.payment_option').click(function() {
        var url_string = window.location;
        var url = new URL(url_string);

        url.searchParams.set('option', $(this).val());
        window.location.href = url.href;
    });

    var perspective = $('.map-perspective').val();

    if (typeof(perspective) !== 'undefined' && perspective != '') {
        var LatLng = $('.map-location').val();

        LatLng = LatLng.split(',');

        var latitude = parseFloat(LatLng[0]);
        var longitude = parseFloat(LatLng[1]);

        var m12 = new Model(perspective, 12, latitude, longitude);
        var m16 = new Model(perspective, 16, latitude, longitude);
        
        $('.solar12').attr('src', $('.solar-dir').val() + m12.azimuth + '.png');
        $('.solar16').attr('src', $('.solar-dir').val() + m16.azimuth + '.png');
    }

    $('.dropdown-toggle').each(function() {
        if ($(this).data('id') == 'prop_lifestyles') {
            var cnt = 0;

            $(this).next().find('ul li').each(function() {
                if ($(this).hasClass('selected'))
                    cnt++;
            });

            if (cnt == 0) {
                $(this).attr('title', 'None');
                $(this).find('span.filter-option').text('None');
            }
        }
    });
});