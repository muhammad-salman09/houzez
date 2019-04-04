<?php
/** Template Name: Draw Map Search
*/

get_header();
?>

<div class="row draw-search">
	<div class="col-lg-7 col-md-7" id="map">
		
	</div>
	<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 listing-area">
		
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	var min_price = '';
    var max_price = '';
   	min_price = parseInt($('#min_price').val());
   	max_price = parseInt($('#max_price').val());

    if (min_price != '' && max_price != '') {
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
            }
        });

        var min_price = addCommas(min_price);
        var max_price = addCommas(max_price);

        $(".min-price-range-hidden").val(min_price);
        $(".max-price-range-hidden").val(max_price);

        $(".min-price-range").text(min_price);
        $(".max-price-range").text(max_price);
    }
});

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
</script>

<?php get_footer(); ?>