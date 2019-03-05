/**
 * Created by waqasriaz on 11/04/17.
 */
if (typeof houzez_reCaptcha !== "undefined") {
    var reCaptchaIDs = [];
    var site_key = houzez_reCaptcha.site_key;
    var secret_key = houzez_reCaptcha.secret_key;
    var lightbox_agent_cotnact = houzez_reCaptcha.lightbox_agent_cotnact;
    var is_singular_property = houzez_reCaptcha.is_singular_property;
    var houzez_logged_in = houzez_reCaptcha.houzez_logged_in;
    var houzez_show_captcha = houzez_reCaptcha.houzez_show_captcha;
    
    var houzezReCaptchaLoad = function() {
        jQuery( '.houzez_google_reCaptcha' ).each( function( index, el ) {
            var tempID = grecaptcha.render( el, {
                'sitekey' : site_key
            } );
            reCaptchaIDs.push( tempID );
        } );
    };

    //Reset reCaptcha
    var houzezReCaptchaReset = function() {
        if( typeof reCaptchaIDs != 'undefined' ) {
            var arrayLength = reCaptchaIDs.length;
            for( var i = 0; i < arrayLength; i++ ) {
                grecaptcha.reset( reCaptchaIDs[i] );
            }
        }
    };     
}