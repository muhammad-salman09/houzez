<?php
/**
 * User Profile
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 02/10/15
 * Time: 4:43 PM
 */

global $current_user, $houzez_local;
$current_user = wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;
$username               =   get_the_author_meta( 'user_login' , $userID );
$first_name             =   get_the_author_meta( 'first_name' , $userID );
$last_name              =   get_the_author_meta( 'last_name' , $userID );
$user_email             =   get_the_author_meta( 'user_email' , $userID );
$user_mobile            =   get_the_author_meta( 'fave_author_mobile' , $userID );
$user_phone             =   get_the_author_meta( 'fave_author_phone' , $userID );
$description            =   get_the_author_meta( 'description' , $userID );
$facebook               =   get_the_author_meta( 'fave_author_facebook' , $userID );
$twitter                =   get_the_author_meta( 'fave_author_twitter' , $userID );
$linkedin               =   get_the_author_meta( 'fave_author_linkedin' , $userID );
$pinterest              =   get_the_author_meta( 'fave_author_pinterest' , $userID );
$instagram              =   get_the_author_meta( 'fave_author_instagram' , $userID );
$googleplus             =   get_the_author_meta( 'fave_author_googleplus' , $userID );
$youtube                =   get_the_author_meta( 'fave_author_youtube' , $userID );
$vimeo                  =   get_the_author_meta( 'fave_author_vimeo' , $userID );
$user_skype             =   get_the_author_meta( 'fave_author_skype' , $userID );
$website_url            =   get_the_author_meta( 'user_url' , $userID );
$license                =   get_the_author_meta( 'fave_author_license' , $userID );
$tax_number             =   get_the_author_meta( 'fave_author_tax_no' , $userID );
$fax_number             =   get_the_author_meta( 'fave_author_fax' , $userID );
$gdpr_agreement             =   get_the_author_meta( 'gdpr_agreement' , $userID );
$user_address           =   get_the_author_meta( 'fave_author_address' , $userID );
$user_google_location           =   get_the_author_meta( 'fave_author_google_location' , $userID );
$google_latitude           =   get_the_author_meta( 'fave_author_google_latitude' , $userID );
$google_longitude           =   get_the_author_meta( 'fave_author_google_longitude' , $userID );
$userlangs           =   get_the_author_meta( 'fave_author_language' , $userID );
$user_company           =   get_the_author_meta( 'fave_author_company' , $userID );

$user_title             =   get_the_author_meta( 'fave_author_title' , $userID );
$user_custom_picture    =   get_the_author_meta( 'fave_author_custom_picture' , $userID );
$author_picture_id      =   get_the_author_meta( 'fave_author_picture_id' , $userID );
$about_me               =   get_the_author_meta( 'description' , $userID );
$user_default_currency  =   get_the_author_meta( 'fave_author_currency' , $userID );
if($user_custom_picture==''){
    $user_custom_picture=get_template_directory_uri().'/images/profile-avatar.png';
}
$current_user_meta = get_user_meta( $userID );
$user_data              =   get_userdata( $userID );
$role                   =   $user_data->roles[0];
$user_show_roles_profile = houzez_option('user_show_roles_profile');
$show_hide_roles = houzez_option('show_hide_roles');
$gdpr_enabled = houzez_option('gdpr-enabled');
$gdpr_agreement_content = houzez_option('gdpr-agreement-content');

$user_agent_id = get_user_meta( $userID, 'fave_author_agent_id', true );

if( !empty( $user_agent_id ) ) {
    if( 'publish' == get_post_status ( $user_agent_id ) ) {
        $agent_permalink = get_permalink($user_agent_id);
    } else {
        $agent_permalink = get_author_posts_url( $userID );
    }

} else {
    $agent_permalink = get_author_posts_url( $userID );
}
?>

<div class="profile-content-area">

    <div id="profile_message" class="houzez_messages message"></div>
    
    <form action="">
        <div class="account-block account-profile-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="my-avatar">
                        <div id="houzez_profile_photo">
                            <div class="houzez-thumb">
                                <?php
                                if( !empty( $author_picture_id ) ) {
                                    $author_picture_id = intval( $author_picture_id );
                                    if ( $author_picture_id ) {
                                        echo wp_get_attachment_image( $author_picture_id, array( 270, 270 ) );
                                        echo '<input type="hidden" class="profile-pic-id" id="profile-pic-id" name="profile-pic-id" value="' . esc_attr( $author_picture_id ).'"/>';
                                    }
                                } else {
                                    print '<img class="img-circle" id="profile-image" src="'.esc_url( $user_custom_picture ).'" alt="user image" >';
                                }
                                ?>
                            </div>
                        </div><!-- end of user profile image -->
                        <div class="profile-img-controls">
                            <div id="houzez_upload_errors"></div>
                            <div id="plupload-container"></div>
                        </div><!-- end of profile image controls -->
                        <div id="profile_upload_containder">
                            <a id="select_user_profile_photo" class="btn btn-primary btn-block" href="javascript:;"><?php esc_html_e('Update Profile Picture','houzez'); ?></a>
                            <p class="profile-img-info"><?php esc_html_e( '*minimum 270px x 270px', 'houzez' ); ?></p>
                         </div>
                    </div>
                </div>

                <div class="col-md-9 col-sm-12 col-xs-12">
                    <h4><?php esc_html_e( 'Information', 'houzez' ); ?></h4>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="username"><?php esc_html_e('Username','houzez');?></label>
                                <input disabled type="text" name="prof_username" id="prof_username"  class="form-control" value="<?php echo esc_attr( $username );?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="useremail"><?php esc_html_e('Email','houzez');?></label>
                                <input type="text" name="prof_useremail" id="prof_useremail"  class="form-control" value="<?php echo esc_attr( $user_email );?>">
                            </div>
                        </div>
                        <?php if( !houzez_is_agency() ) { ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="firstname"><?php esc_html_e('First Name','houzez');?></label>
                                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo esc_attr( $first_name );?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="lastname"><?php esc_html_e('Last Name','houzez');?></label>
                                <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo esc_attr( $last_name );?>">
                            </div>
                        </div>

                        <?php if(houzez_not_buyer()) { ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="title"><?php esc_html_e('Title / Position','houzez');?></label>
                                <input type="text" id="title" name="title" value="<?php echo esc_attr( $user_title );?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="license"><?php esc_html_e('License','houzez');?></label>
                                <input type="text" id="license" name="license" value="<?php echo esc_attr( $license );?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="tax_number"><?php esc_html_e('Tax Number','houzez');?></label>
                                <input type="text" id="tax_number" name="tax_number" value="<?php echo esc_attr( $tax_number );?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="userphone"><?php esc_html_e('Phone','houzez');?></label>
                                <input type="text" id="userphone" class="form-control" value="<?php echo esc_attr( $user_phone );?>" name="userphone">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="fax_number"><?php esc_html_e('Fax Number','houzez');?></label>
                                <input type="text" id="fax_number" class="form-control" value="<?php echo esc_attr( $fax_number );?>" name="fax_number">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="usermobile"><?php esc_html_e('Mobile','houzez');?></label>
                                <input type="text" id="usermobile" class="form-control" value="<?php echo esc_attr( $user_mobile );?>" name="usermobile">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="userlangs"><?php esc_html_e('Language','houzez');?></label>
                                <input type="text" id="userlangs" placeholder="<?php echo esc_html__('English, Spanish, French', 'houzez'); ?>" class="form-control" value="<?php echo esc_attr( $userlangs );?>" name="userlangs">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label for="user_company"><?php esc_html_e('Company Name','houzez');?></label>
                                <input type="text" id="user_company" placeholder="" class="form-control" value="<?php echo esc_attr( $user_company );?>" name="user_company">
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="about"><?php esc_html_e( 'Address', 'houzez' ); ?></label>
                                <textarea id="user_address" class="form-control" rows="2"><?php echo esc_attr( $user_address );?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="about"><?php esc_html_e( 'About me', 'houzez' ); ?></label>
                                <textarea id="about" class="form-control" rows="7"><?php echo esc_attr( $about_me );?></textarea>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>

                        <?php if( houzez_is_agency() ) {
                            $googlemap_ssl = houzez_option('googlemap_ssl');
                            $googlemap_api_key = houzez_option('googlemap_api_key');
                            if( esc_html( $googlemap_ssl ) == 'yes' || is_ssl() ) {
                                wp_enqueue_script('google-map', 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&language='.get_locale().'&key='.esc_html( $googlemap_api_key ),array('jquery'), '1.0', false);
                            } else {
                                wp_enqueue_script('google-map', 'http://maps.googleapis.com/maps/api/js?libraries=places&language='.get_locale().'&key='.esc_html( $googlemap_api_key ), array('jquery'), '1.0', false);
                            }
                            ?>
                            <script>
                                jQuery(function($){
                                    $("#user_location").geocomplete({
                                        map: ".map_canvas",
                                        details: "form",
                                        types: ["geocode", "establishment"],
                                        markerOptions: {
                                            draggable: true
                                        }
                                    });

                                    $("#user_location").bind("geocode:dragged", function(event, latLng){
                                        $("input[name=lat]").val( );
                                        $("input[name=lng]").val(latLng.lng());
                                        $("#reset").show();

                                        var map = $("#user_location").geocomplete("map");
                                        map.panTo(latLng);
                                        var geocoder = new google.maps.Geocoder();
                                        geocoder.geocode({'latLng': latLng }, function(results, status) {
                                            if (status == google.maps.GeocoderStatus.OK) { //alert(JSON.stringify(results));
                                                if (results[0]) {
                                                    var road = results[0].address_components[1].short_name;
                                                    var town = results[0].address_components[2].short_name;
                                                    var county = results[0].address_components[3].short_name;
                                                    var country = results[0].address_components[4].short_name;
                                                    $("input[name=user_location]").val(road+' '+town+' '+county+' '+country);
                                                }
                                            }
                                        });
                                    });


                                    $("#reset").click(function(){
                                        $("#user_location").geocomplete("resetMarker");
                                        $("#reset").hide();
                                        return false;
                                    });

                                    $("#find").click(function(e){
                                        e.preventDefault();
                                        $("#user_location").trigger("geocode");
                                    });
                                });
                            </script>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="title"><?php esc_html_e('Agency Name','houzez');?></label>
                                    <input type="text" id="title" name="title" value="<?php echo esc_attr( $user_title );?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="title"><?php esc_html_e('Agency License','houzez');?></label>
                                    <input type="text" id="license" name="license" value="<?php echo esc_attr( $license );?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="tax_number"><?php esc_html_e('Tax Number','houzez');?></label>
                                    <input type="text" id="tax_number" name="tax_number" value="<?php echo esc_attr( $tax_number );?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="userphone"><?php esc_html_e('Phone','houzez');?></label>
                                    <input type="text" id="userphone" class="form-control" value="<?php echo esc_attr( $user_phone );?>" name="userphone">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="fax_number"><?php esc_html_e('Fax Number','houzez');?></label>
                                    <input type="text" id="fax_number" class="form-control" value="<?php echo esc_attr( $fax_number );?>" name="fax_number">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="usermobile"><?php esc_html_e('Mobile','houzez');?></label>
                                    <input type="text" id="usermobile" class="form-control" value="<?php echo esc_attr( $user_mobile );?>" name="usermobile">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="userlangs"><?php esc_html_e('Language','houzez');?></label>
                                    <input type="text" id="userlangs" placeholder="<?php echo esc_html__('English, Spanish, French', 'houzez'); ?>" class="form-control" value="<?php echo esc_attr( $userlangs );?>" name="userlangs">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="about"><?php esc_html_e( 'About Agency', 'houzez' ); ?></label>
                                    <textarea id="about" class="form-control" rows="7"><?php echo esc_attr( $about_me );?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="about"><?php esc_html_e( 'Address', 'houzez' ); ?></label>
                                    <textarea id="user_address" class="form-control" rows="2"><?php echo esc_attr( $user_address );?></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="user_location"><?php esc_html_e( 'Agency Location', 'houzez' ); ?></label>
                                    <input class="form-control" name="user_location" id="user_location" value="<?php echo esc_attr($user_google_location);?>" placeholder="<?php esc_html_e( 'Leave it empty if you want to hide map on agency detail page.', 'houzez' ); ?>">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="map_canvas" id="map"></div>
                                    <input type="hidden" name="lat" id="latitude" value="<?php echo esc_attr($google_latitude); ?>">
                                    <input type="hidden" name="lng" id="longitude" value="<?php echo esc_attr($google_longitude); ?>">
                                    <button id="find" class="btn btn-primary"><?php esc_html_e( 'Place the pin the address above', 'houzez' ); ?></button>
                                    <a id="reset" href="#" style="display:none;"><?php esc_html_e('Reset Marker', 'houzez');?></a>
                                </div>
                            </div>

                        <?php } ?>

                        <?php if($gdpr_enabled != 0 ) { ?>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="gdpr_agreement"><?php esc_html_e('GDPR Agreement *','houzez');?></label>
                                <input <?php if($gdpr_agreement == 'checked'){echo 'checked=checked';}?> type="checkbox" id="gdpr_agreement" class="form-control" name="gdpr_agreement">
                                <?php echo houzez_option('gdpr-label'); ?>
                            </div>
                        </div>


                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                
                                <textarea rows="5" readonly="readonly" class="form-control"><?php echo $gdpr_agreement_content;?></textarea>
                                
                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-sm-12 col-xs-12 text-right">
                            <?php  wp_nonce_field( 'houzez_profile_ajax_nonce', 'houzez-security-profile' );   ?>

                            <?php if(houzez_not_buyer()) { ?>
                            <a href="<?php echo esc_url($agent_permalink); ?>" target="_blank" class="btn btn-primary btn-trans"><?php esc_html_e('View Public Profile','houzez');?></a>
                            <?php } ?>

                            <button class="btn btn-primary" id="houzez_update_profile"><?php esc_html_e('Update Profile','houzez');?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="account-block account-block-social account-profile-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <h4><?php esc_html_e('Social Media','houzez');?></h4>
                </div>
                <div class="col-md-9 col-sm-12 col-xs-12">

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="userskype"><?php esc_html_e('Skype','houzez');?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-skype"></i></span>
                                        <input type="text" id="userskype" class="form-control" value="<?php echo esc_attr( $user_skype );?>" name="userskype">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="website"><?php esc_html_e( 'Website URL', 'houzez' ); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                        <input type="text" id="website" class="form-control" value="<?php echo esc_url($website_url); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="facebook"><?php esc_html_e( 'Facebook URL', 'houzez' ); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
                                        <input type="text" id="facebook" name="facebook" value="<?php echo esc_url( $facebook );?>"  class="form-control">
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="twitter"><?php esc_html_e( 'Twitter URL', 'houzez' ); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-twitter-square"></i></span>
                                        <input type="text" id="twitter" class="form-control" value="<?php echo esc_url( $twitter );?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="linkedin"><?php esc_html_e( 'Linkedin URL', 'houzez' ); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-linkedin-square"></i></span>
                                        <input type="text" id="linkedin" class="form-control" value="<?php echo esc_url( $linkedin );?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="instagram"><?php esc_html_e( 'Instagram URL', 'houzez' ); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
                                        <input type="text" id="instagram" class="form-control" value="<?php echo esc_url( $instagram );?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="googleplus"><?php esc_html_e('Google Plus Url','houzez');?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-google-plus-square"></i></span>
                                        <input type="text" id="googleplus" class="form-control" value="<?php echo esc_url( $googleplus );?>" name="googleplus">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="youtube"><?php esc_html_e('Youtube Url','houzez');?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-youtube-square"></i></span>
                                        <input type="text" id="youtube" class="form-control" value="<?php echo esc_url( $youtube );?>" name="youtube">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="pinterest"><?php esc_html_e('Pinterest Url','houzez');?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pinterest-square"></i></span>
                                        <input type="text" id="pinterest" class="form-control" value="<?php echo esc_url( $pinterest );?>" name="pinterest">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="vimeo"><?php esc_html_e('Vimeo Url','houzez');?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-vimeo-square"></i></span>
                                        <input type="text" id="vimeo" class="form-control" value="<?php echo esc_url( $vimeo );?>" name="vimeo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary pull-right" id="houzez_update_profile2"><?php esc_html_e('Update Profile','houzez');?></button>

                </div>
            </div>
        </div>

        <div class="account-block account-profile-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <h4><?php esc_html_e( 'Change password', 'houzez' ); ?></h4>
                </div>

                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="oldpass"><?php esc_html_e('Old Password','houzez');?></label>
                                <input  id="oldpass" value=""  class="form-control" name="oldpass" type="password">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="newpass"><?php esc_html_e('New Password ','houzez');?></label>
                                <input  id="newpass" value="" class="form-control" name="newpass" type="password">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="confirmpass"><?php esc_html_e('Confirm New Password','houzez');?></label>
                                <input id="confirmpass" value="" class="form-control" name="confirmpass" type="password">
                            </div>
                        </div>
                    </div>
                    <?php wp_nonce_field( 'houzez_pass_ajax_nonce', 'houzez-security-pass' );   ?>
                    <button class="btn btn-primary pull-right" id="houzez_change_pass"><?php esc_html_e('Update Password','houzez');?></button>
                    <div id="password_reset_msgs" class="houzez_messages message"></div>
                </div>
            </div>
        </div>

        <?php
        if( $user_show_roles_profile != 0 && !in_array('administrator', (array)$current_user->roles)) { ?>
        <div class="account-block account-profile-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <h4 class="account-action-title"><?php esc_html_e( 'Account role', 'houzez' ); ?></h4>
                </div>
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <?php wp_nonce_field( 'houzez_role_pass_ajax_nonce', 'houzez-role-security-pass' );   ?>
                                <select name="houzez_user_role" id="houzez_user_role" class="selectpicker" data-live-search="false" data-live-search-style="begins" title=" Registered User ">
                                    <?php
                                    if( $show_hide_roles['agent'] != 1 ) {
                                        echo '<option value="houzez_agent" '.selected( 'houzez_agent', $role  ).'> '.houzez_option('agent_role').' </option>';
                                    }
                                    if( $show_hide_roles['agency'] != 1 ) {
                                        echo '<option value="houzez_agency" ' . selected('houzez_agency', $role) . '> ' . houzez_option('agency_role') . ' </option>';
                                    }
                                    if( $show_hide_roles['owner'] != 1 ) {
                                        echo '<option value="houzez_owner" ' . selected('houzez_owner', $role) . '> ' . houzez_option('owner_role') . '  </option>';
                                    }
                                    if( $show_hide_roles['buyer'] != 1 ) {
                                        echo '<option value="houzez_buyer" ' . selected('houzez_buyer', $role) . '> ' . houzez_option('buyer_role') . '  </option>';
                                    }
                                    if( $show_hide_roles['seller'] != 1 ) {
                                        echo '<option value="houzez_seller" ' . selected('houzez_seller', $role) . '> ' . houzez_option('seller_role') . '  </option>';
                                    }
                                    if( $show_hide_roles['manager'] != 1 ) {
                                        echo '<option value="houzez_manager" ' . selected('houzez_manager', $role) . '> ' . houzez_option('manager_role') . ' </option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
        $multi_currency = houzez_option('multi_currency');
        if($multi_currency == 1 && houzez_check_role()) {
            if(class_exists('Houzez_Currencies')) { ?>
                <div class="account-block account-profile-block">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <h4 class="account-action-title"><?php esc_html_e( 'Choose Currency', 'houzez' ); ?></h4>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <?php wp_nonce_field( 'houzez_user_currency_ajax_nonce', 'houzez-user-currency-security-pass' );   ?>
                                        <select name="houzez_user_currency" id="houzez_user_currency" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                            <option value=""><?php esc_html_e( 'Default Currency', 'houzez' ); ?></option>
                                            <?php
                                            $user_currency = $user_default_currency;

                                            $currencies = Houzez_Currencies::get_currency_codes();
                                            if($currencies) {
                                                foreach ($currencies as $currency) {
                                                    echo '<option '.selected( $currency->currency_code, $user_currency, false).' value="'.$currency->currency_code.'">'.$currency->currency_code.'</option>'; 
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php } 
        }?>

        <div class="account-block account-profile-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <h4 class="account-action-title"> <?php esc_html_e( 'Delete account', 'houzez' ); ?> </h4>
                </div>

                <div class="col-md-9 col-sm-12 col-xs-12">
                    <button class="btn btn-danger pull-right" id="houzez_delete_account"> <?php esc_html_e( 'Delete My Account', 'houzez' ); ?> </button>
                </div>
            </div>
        </div>
    </form>
</div>