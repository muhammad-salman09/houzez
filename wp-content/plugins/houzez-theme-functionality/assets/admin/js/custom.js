( function( $ ) {
    'use strict';

    $( function() {

        $('.houzez-clone').cloneya();

        $( '.houzez-fbuilder-js-on-change' ).change( function() {
            var field_type = $( this ).val();
            $('.houzez-clone').cloneya();

            if(field_type == 'select') {
                $.post( ajaxurl, { action: 'houzez_load_select_options', type: field_type }, function( response ) {
                    $( '.houzez_select_options_loader_js' ).html( response );
                    $('.houzez-clone').cloneya();
                } );
            } else {
                $( '.houzez_select_options_loader_js' ).html('');
            }
        } );
    } );
} )( jQuery );