jQuery(document).ready(function($) {

    "use strict";


    if ( typeof houzezUserProfile !== "undefined" ) {

        var ajaxURL = houzezUserProfile.ajaxURL;
        var houzez_upload_nonce = houzezUserProfile.houzez_upload_nonce;
        var verify_file_type = houzezUserProfile.verify_file_type;
        var houzez_site_url = houzezUserProfile.houzez_site_url;
        var process_loader_refresh = houzezUserProfile.process_loader_refresh;
        var sending_info = houzezUserProfile.sending_info;
        var process_loader_spinner = houzezUserProfile.process_loader_spinner;
        var process_loader_circle = houzezUserProfile.process_loader_circle;
        var process_loader_cog = houzezUserProfile.process_loader_cog;
        var success_icon = houzezUserProfile.success_icon;
        var processing_text = houzezUserProfile.processing_text;
        var gdpr_agree_text = houzezUserProfile.gdpr_agree_text;


        /*-------------------------------------------------------------------
         *  GDPR Request
         * ------------------------------------------------------------------*/
         $('#houzez_gdpr_form').on('submit', function(e) {
            e.preventDefault();

            var data = {
                'action' : 'houzez_gdrf_data_request',
                'gdpr_data_type' : $('input[name=gdrf_data_type]:checked', '#houzez_gdpr_form').val(),
                'gdrf_data_email' : $('#gdrf_data_email').val(),
                'gdrf_data_nonce' : $('#houzez_gdrf_data_nonce').val(),
            };

            $( '.houzez-errors' ).remove();
            $( '.houzez-success' ).remove();

            var $messages = $('#houzez_gdpr_messages');
            
            $.ajax({
                type: 'POST',
                url: ajaxURL,
                data: data,
                success: function (res) {
                    console.log(res);

                    if(res.success) {
                        $messages.append( '<div class="houzez-success">' + res.data + '</div>' );
                    } else {
                        $messages.append( '<div class="houzez-errors">' + res.data + '</div>' ); 
                    }

                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });

         });

         /*-------------------------------------------------------------------
         *  Cancel Stripe
         * ------------------------------------------------------------------*/
        $('#houzez_stripe_cancel').click(function(){
            var stripe_user_id, cancel_msg;
            stripe_user_id = $(this).attr('data-stripeid');
            cancel_msg = $(this).attr('data-message');
            $('#stripe_cancel_success').text(processing_text);

            $.ajax({
                type: 'POST',
                url: ajaxURL,
                data: {
                    'action' : 'houzez_cancel_stripe'
                },
                success: function (data) {
                    $('#stripe_cancel_success').text(cancel_msg);
                },
                error: function (errorThrown) {
                }
            });
        });

        /*-------------------------------------------------------------------
         *  Register Agency agent
         * ------------------------------------------------------------------*/
        $('#houzez_agency_agent_register').on('click', function(e){
            e.preventDefault();

            var $form = $(this).parents('form');
            var $messages = $('#aa_register_message');

            $.ajax({
                type: 'post',
                url: ajaxURL,
                dataType: 'json',
                data: $form.serialize(),
                beforeSend: function () {
                    $messages.empty().append('<p class="success text-success">'+sending_info+'</p>');
                },
                success: function( response ) {
                    if( response.success ) {
                        $messages.empty().append('<p class="success text-success"><i class="fa fa-check"></i> '+ response.msg +'</p>');
                    } else {
                        $messages.empty().append('<p class="error text-danger"><i class="fa fa-close"></i> '+ response.msg +'</p>');
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
            return;
        });


        /*-------------------------------------------------------------------
         *  Register Agency agent update
         * ------------------------------------------------------------------*/
        $('#houzez_agency_agent_update').on('click', function(e){
            e.preventDefault();

            var $form = $(this).parents('form');
            var $messages = $('#aa_register_message');

            $.ajax({
                type: 'post',
                url: ajaxURL,
                dataType: 'json',
                data: $form.serialize(),
                beforeSend: function () {
                    $messages.empty().append('<p class="success text-success">'+sending_info+'</p>');
                },
                success: function( response ) {
                    if( response.success ) {
                        $messages.empty().append('<p class="success text-success"><i class="fa fa-check"></i> '+ response.msg +'</p>');
                    } else {
                        $messages.empty().append('<p class="error text-danger"><i class="fa fa-close"></i> '+ response.msg +'</p>');
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
            return;
        });

        /*-------------------------------------------------------------------
         *  Update Profile [user_profile.php]
         * ------------------------------------------------------------------*/
        $("#houzez_update_profile, #houzez_update_profile2").click( function(e) {
            e.preventDefault();

            var $this = $(this);

            var gdpr_agreement;

            if($('#gdpr_agreement').length > 0 ) {
                if(!$('#gdpr_agreement').is(":checked")) {
                    jQuery('#profile_message').empty().append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+gdpr_agree_text+'</div>');
                    $(".dashboard-content-area").animate({ scrollTop: 0 }, "slow");
                    return false;
                } else {
                    gdpr_agreement = 'checked';
                }
            } 


            var firstname   = $("#firstname").val(),
                lastname    = $("#lastname").val(),
                useremail   = $('#prof_useremail').val(),
                title       = $("#title").val(),
                about       = $("#about").val(),
                userphone   = $("#userphone").val(),
                fax_number   = $("#fax_number").val(),
                usermobile  = $("#usermobile").val(),
                userskype   = $("#userskype").val(),
                facebook    = $("#facebook").val(),
                twitter     = $("#twitter").val(),
                linkedin    = $("#linkedin").val(),
                instagram   = $("#instagram").val(),
                pinterest   = $("#pinterest").val(),
                youtube     = $("#youtube").val(),
                vimeo       = $("#vimeo").val(),
                googleplus  = $("#googleplus").val(),
                website     = $("#website").val(),
                license     = $("#license").val(),
                user_company     = $("#user_company").val(),
                tax_number     = $("#tax_number").val(),
                user_address     = $("#user_address").val(),
                user_location     = $("#user_location").val(),
                latitude     = $("#latitude").val(),
                longitude     = $("#longitude").val(),
                userlangs     = $("#userlangs").val(),
                profile_pic = $("#profile-pic-id").val(),

                securityprofile = $('#houzez-security-profile').val();

            $.ajax({
                type: 'POST',
                url: ajaxURL,
                dataType: 'json',
                data: {
                    'action'     : 'houzez_ajax_update_profile',
                    'firstname'  : firstname,
                    'lastname'   : lastname,
                    'useremail'  : useremail,
                    'title'      : title,
                    'about'      : about,
                    'userphone'  : userphone,
                    'fax_number' : fax_number,
                    'usermobile' : usermobile,
                    'userskype'  : userskype,
                    'facebook'   : facebook,
                    'twitter'    : twitter,
                    'linkedin'   : linkedin,
                    'instagram'  : instagram,
                    'youtube'    : youtube,
                    'vimeo'      : vimeo,
                    'googleplus' : googleplus,
                    'pinterest'  : pinterest,
                    'website'    : website,
                    'license'    : license,
                    'user_company'    : user_company,
                    'tax_number'    : tax_number,
                    'user_address': user_address,
                    'user_location': user_location,
                    'latitude'    : latitude,
                    'longitude'   : longitude,
                    'userlangs'   : userlangs,
                    'profile_pic': profile_pic,
                    'gdpr_agreement': gdpr_agreement,
                    'houzez-security-profile'  : securityprofile,
                },
                beforeSend: function( ) {
                    $this.children('i').remove();
                    $this.prepend('<i class="fa-left '+process_loader_spinner+'"></i>');
                },
                success: function(data) {
                    if( data.success ) {
                        jQuery('#profile_message').empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ data.msg +'</div>');
                        $(".dashboard-content-area").animate({ scrollTop: 0 }, "slow");
                    } else {
                        jQuery('#profile_message').empty().append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ data.msg +'</div>');
                        $(".dashboard-content-area").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(errorThrown) {

                },
                complete: function(){
                    $this.children('i').removeClass(process_loader_spinner);
                    $this.children('i').addClass(success_icon);
                }
            });

        });

        /*-------------------------------------------------------------------
         *  Change Password [user-profile.php]
         * ------------------------------------------------------------------*/
        $("#houzez_change_pass").click( function(e) {
            e.preventDefault();
            var securitypassword, oldpass, newpass, confirmpass;

            var $this = $(this);

            oldpass          = $("#oldpass").val();
            newpass          = $("#newpass").val();
            confirmpass      = $("#confirmpass").val();
            securitypassword = $("#houzez-security-pass").val();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:   ajaxURL,
                data: {
                    'action'      : 'houzez_ajax_password_reset',
                    'oldpass'     : oldpass,
                    'newpass'     : newpass,
                    'confirmpass' : confirmpass,
                    'houzez-security-pass' : securitypassword,
                },
                beforeSend: function( ) {
                    $this.children('i').remove();
                    $this.prepend('<i class="fa-left '+process_loader_spinner+'"></i>');
                },
                success: function(data) {
                    if( data.success ) {
                        jQuery('#password_reset_msgs').empty().append('<p class="success text-success"><i class="fa fa-check"></i> '+ data.msg +'</p>');
                        jQuery('#oldpass, #newpass, #confirmpass').val('');
                    } else {
                        jQuery('#password_reset_msgs').empty().append('<p class="error text-danger"><i class="fa fa-close"></i> '+ data.msg +'</p>');
                    }
                },
                error: function(errorThrown) {

                },
                complete: function(){
                    $this.children('i').removeClass(process_loader_spinner);
                    $this.children('i').addClass(success_icon);
                }
            });

        });

        $('#houzez_delete_account').click(function(e){
            e.preventDefault();

            var confirm = window.confirm("Are you sure!, you want to delete a account.");

            if ( confirm == true ) {

                $.ajax({
                    type: 'post',
                    url: ajaxURL,
                    dataType: 'json',
                    data: {
                        'action': 'houzez_delete_account'
                    },
                    beforeSend: function () {
                        profile_processing_modal(processing_text);
                    },
                    success: function( response ) {
                        if( response.success ) {
                            window.location.href = houzez_site_url;
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });

            }

        });

        $('.houzez_delete_agency_agent').click(function(e){
            e.preventDefault();

            var confirm = window.confirm("Are you sure!, you want to delete a account.");
            var agent_id = $(this).attr('data-agentid');
            var agent_delete_security = $('#agent_delete_security').val();

            if ( confirm == true ) {

                $.ajax({
                    type: 'post',
                    url: ajaxURL,
                    dataType: 'json',
                    data: {
                        'action': 'houzez_delete_agency_agent',
                        'agent_delete_security': agent_delete_security,
                        'agent_id': agent_id
                    },
                    beforeSend: function () {
                        profile_processing_modal(processing_text);
                    },
                    success: function( response ) {
                        if( response.success ) {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });

            }

        });


        $( '#houzez_user_role' ).on( 'change', function(e) {
            e.preventDefault();

            var user_role = $( this ).val();
            var nonce    = $('#houzez-role-security-pass').val();
            var _wp_http_referer = $( 'input[name="_wp_http_referer"]' ).val();

            $.ajax({
                type: 'post',
                url: ajaxURL,
                dataType: 'json',
                data: {
                    'action': 'houzez_change_user_role',
                    'role': user_role,
                    'houzez-role-security-pass' : nonce,
                    '_wp_http_referer' : _wp_http_referer
                },
                beforeSend: function () {
                    profile_processing_modal(processing_text);
                },
                success: function( response ) {
                    if( response.success ) {
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
        });

        $( '#houzez_user_currency' ).on( 'change', function(e) {
            e.preventDefault();

            var user_currency = $( this ).val();
            var nonce    = $('#houzez-user-currency-security-pass').val();

            $.ajax({
                type: 'post',
                url: ajaxURL,
                dataType: 'json',
                data: {
                    'action': 'houzez_change_user_currency',
                    'currency': user_currency,
                    'houzez-user-currency-security-pass' : nonce
                },
                beforeSend: function () {
                    profile_processing_modal(processing_text);
                },
                success: function( response ) {
                    if( response.success ) {
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
        });

        var profile_processing_modal = function ( msg ) {
            var process_modal ='<div class="modal fade" id="fave_modal" tabindex="-1" role="dialog" aria-labelledby="faveModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body houzez_messages_modal">'+msg+'</div></div></div></div></div>';
            jQuery('body').append(process_modal);
            jQuery('#fave_modal').modal();
        }

        var profile_processing_modal_close = function ( ) {
            jQuery('#fave_modal').modal('hide');
        }

        /*-------------------------------------------------------------------
         *  Upload user profile image
         * ------------------------------------------------------------------*/
        var houzez_plupload = new plupload.Uploader({
            browse_button: 'select_user_profile_photo',
            file_data_name: 'houzez_file_data_name',
            container: 'profile_upload_containder',
            multi_selection : false,
            url: ajaxURL + "?action=houzez_user_picture_upload&verify_nonce=" + houzez_upload_nonce,
            filters: {
                mime_types : [
                    { title : verify_file_type, extensions : "jpg,jpeg,gif,png" }
                ],
                max_file_size: '2500kb',
                prevent_duplicates: true
            }
        });
        houzez_plupload.init();

        houzez_plupload.bind('FilesAdded', function(up, files) {
            var houzez_thumbnail = "";
            plupload.each(files, function(file) {
                houzez_thumbnail += '<div id="imageholder-' + file.id + '" class="houzez-thumb">' + '' + '</div>';
            });
            document.getElementById('houzez_profile_photo').innerHTML = houzez_thumbnail;
            up.refresh();
            houzez_plupload.start();
        });

        houzez_plupload.bind('UploadProgress', function(up, file) {
            document.getElementById( "imageholder-" + file.id ).innerHTML = '<span>' + file.percent + "%</span>";
        });

        houzez_plupload.bind('Error', function( up, err ) {
            document.getElementById('houzez_upload_errors').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
        });

        houzez_plupload.bind('FileUploaded', function ( up, file, ajax_res ) {
            var response = $.parseJSON( ajax_res.response );

            if ( response.success ) {

                var houzez_profile_thumb = '<img src="' + response.url + '" alt="" />' +
                    '<input type="hidden" class="profile-pic-id" id="profile-pic-id" name="profile-pic-id" value="' + response.attachment_id + '"/>';

                document.getElementById( "imageholder-" + file.id ).innerHTML = houzez_profile_thumb;

            } else {
                console.log ( response );
            }
        });

    }

});