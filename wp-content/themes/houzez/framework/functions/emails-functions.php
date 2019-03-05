<?php
/**
 * File Name: Email Functions
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 03/02/16
 * Time: 5:38 PM
 */

if (!function_exists('houzez_email_type')) {
    function houzez_email_type( $email, $email_type, $args ) {

        $value_message = houzez_option('houzez_' . $email_type, '');
        $value_subject = houzez_option('houzez_subject_' . $email_type, '');

        if (function_exists('icl_translate')) {
            $value_message = icl_translate('houzez', 'houzez_email_' . $value_message, $value_message);
            $value_subject = icl_translate('houzez', 'houzez_email_subject_' . $value_subject, $value_subject);
        }

        houzez_emails_filter_replace( $email, $value_message, $value_subject, $args);
    }
}

if( !function_exists('houzez_emails_filter_replace')):
    function  houzez_emails_filter_replace( $email, $message, $subject, $args ) {
        $args ['website_url'] = get_option('siteurl');
        $args ['website_name'] = get_option('blogname');
        $args ['user_email'] = $email;
        $user = get_user_by( 'email',$email );
        $args ['username'] = $user->user_login;

        foreach( $args as $key => $val){
            $subject = str_replace( '%'.$key, $val, $subject );
            $message = str_replace( '%'.$key, $val, $message );
        }

        houzez_send_emails( $email, $subject, $message );
        
    }
endif;

if( !function_exists('houzez_emails_filter_replace_2')):
    function  houzez_emails_filter_replace_2( $email, $message, $subject, $args ) {
        $args ['website_url'] = get_option('siteurl');
        $args ['website_name'] = get_option('blogname');
        $args ['user_email'] = $email;
        $user = get_user_by( 'email',$email );
        $args ['username'] = $user->user_login;

        foreach( $args as $key => $val){
            $subject = str_replace( '%'.$key, $val, $subject );
            $message = str_replace( '%'.$key, $val, $message );
        }

        houzez_send_emails_match_submission( $email, $subject, $message );
        
    }
endif;


if( !function_exists('houzez_send_emails') ):
    function houzez_send_emails( $user_email, $subject, $message ){
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');

        if( $enable_html_emails != 0 ) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        }

        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div style="font-size: 0; text-align: center; padding-top: 20px;">';
            $socials .= '<p style="margin:0;margin-bottom: 10px; text-align: center; font-size: 14px; color:#777777;">'.esc_html__('Follow us on', 'houzez').'</p>';

            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
        }

        if( $enable_email_header != 0 ) {
            $email_content = '<div style="text-align: center; background-color: ' . esc_attr($email_head_bg_color) . '; padding: 16px 0;">
                            <img src="' . esc_url($email_head_logo) . '" alt="logo">
                        </div>';
        }

        $email_content .= '<div style="background-color: #F6F6F6; padding: 30px;">
                            <div style="margin: 0 auto; width: 620px; background-color: #fff;border:1px solid #eee; padding:30px;">
                                <div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:100%;line-height:1.6em;display:block;max-width:600px;margin:0 auto;padding:0">
                                '.$message.'
                                </div>
                            </div>
                        </div>';

        if( $enable_email_footer != 0 ) {
            $email_content .= '<div style="padding-top: 30px; text-align:center; padding-bottom: 30px; font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;">

                            <div style="width: 640px; background-color: ' . $email_foot_bg_color . '; margin: 0 auto;">
                                ' . $email_footer_content . '
                            </div>
                            ' . $socials . '
                        </div>';
        }

        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;



if( !function_exists('houzez_send_emails_match_submission') ):
    function houzez_send_emails_match_submission( $user_email, $subject, $message ){
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');

        if( $enable_html_emails != 0 ) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        }

        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div class="follow">';
            $socials .= '<p>'.esc_html__('Follow us on', 'houzez').'</p>';

            $socials .= '<div>';
            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
            $socials .= '</div>';
        }

        $email_wrap_start = '<!DOCTYPE html>
                        <html>
                        <body style="padding: 0; margin: 0;">
                            
                            <!-- main-wrap -->
                            <div class="main-wrap" style="text-align: center; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; background-color: #F8F8F8; padding-bottom:20px;">';

        $email_wrap_end = '</div></body></html>';

        $email_header = '<div class="header" style="background-color: ' . esc_attr($email_head_bg_color) . '; padding: 20px 0; margin: 0 0 30px;">
            <a style="display: inline-block; text-decoration: none;" href="#"><img src="' . esc_url($email_head_logo) . '"></a>
        </div>';

        $email_footer = '<div class="footer" style="margin-top: 60px; text-align:center; background: ' . $email_foot_bg_color . '; color: #777777; padding: 60px 10px; margin: 0 auto;">
            ' . $email_footer_content . '

            ' . $socials . '

        </div>';

        $email_content = $email_wrap_start;

        if( $enable_email_header != 0 ) {
            $email_content .= $email_header;
        }

        $email_content .= $message;

        if( $enable_email_footer != 0 ) {
            $email_content .= $email_footer;
        }

        $email_content .= $email_wrap_end;


        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;


if( !function_exists('houzez_send_messages_emails') ):
    function houzez_send_messages_emails( $user_email, $subject, $message ){
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $enable_html_emails = houzez_option('enable_html_emails');
        $enable_email_header = houzez_option('enable_email_header');
        $enable_email_footer = houzez_option('enable_email_footer');


        $enable_html_emails = houzez_option('enable_html_emails');
        $email_head_logo = houzez_option('email_head_logo', false, 'url');
        $email_head_bg_color = houzez_option('email_head_bg_color');
        $email_foot_bg_color = houzez_option('email_foot_bg_color');
        $email_footer_content = houzez_option('email_footer_content');

        $social_1_icon = houzez_option('social_1_icon', false, 'url');
        $social_1_link = houzez_option('social_1_link');
        $social_2_icon = houzez_option('social_2_icon', false, 'url');
        $social_2_link = houzez_option('social_2_link');
        $social_3_icon = houzez_option('social_3_icon', false, 'url');
        $social_3_link = houzez_option('social_3_link');
        $social_4_icon = houzez_option('social_4_icon', false, 'url');
        $social_4_link = houzez_option('social_4_link');

        $socials = '';
        if( !empty($social_1_icon) || !empty($social_2_icon) || !empty($social_3_icon) || !empty($social_4_icon) ) {
            $socials = '<div style="font-size: 0; text-align: center; padding-top: 20px;">';
            $socials .= '<p style="margin:0;margin-bottom: 10px; text-align: center; font-size: 14px; color:#777777;">'.esc_html__('Follow us on', 'houzez').'</p>';

            if( !empty($social_1_icon) ) {
                $socials .= '<a href="'.esc_url($social_1_link).'" style="margin-right: 5px"><img src="'.esc_url($social_1_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_2_icon) ) {
                $socials .= '<a href="'.esc_url($social_2_link).'" style="margin-right: 5px"><img src="'.esc_url($social_2_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_3_icon) ) {
                $socials .= '<a href="'.esc_url($social_3_link).'" style="margin-right: 5px"><img src="'.esc_url($social_3_icon).'" width="" height="" alt=""> </a>';
            }
            if( !empty($social_4_icon) ) {
                $socials .= '<a href="'.esc_url($social_4_link).'" style="margin-right: 5px"><img src="'.esc_url($social_4_icon).'" width="" height="" alt=""> </a>';
            }

            $socials .= '</div>';
        }

        if( $enable_email_header != 0 ) {
            $email_content = '<div style="text-align: center; background-color: ' . esc_attr($email_head_bg_color) . '; padding: 16px 0;">
                            <img src="' . esc_url($email_head_logo) . '" alt="logo">
                        </div>';
        }

        $email_content .= '<div style="background-color: #F6F6F6; padding: 30px;">
                            <div style="margin: 0 auto; width: 620px; background-color: #fff;border:1px solid #eee; padding:30px;">
                                <div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:100%;line-height:1.6em;display:block;max-width:600px;margin:0 auto;padding:0">
                                '.$message.'
                                </div>
                            </div>
                        </div>';

        if( $enable_email_footer != 0 ) {
            $email_content .= '<div style="padding-top: text-align:center; 30px; padding-bottom: 30px; font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;">

                            <div style="width: 640px; background-color: ' . $email_foot_bg_color . '; margin: 0 auto;">
                                ' . $email_footer_content . '
                            </div>
                            ' . $socials . '
                        </div>';
        }

        if( $enable_html_emails != 0 ) {
            $email_messages = $email_content;
        } else {
            $email_messages = $message;
        }

        @wp_mail(
            $user_email,
            $subject,
            $email_messages,
            $headers
        );
    };
endif;


if( !function_exists('houzez_email_to_admin') ) {
    function houzez_email_to_admin($email_type) {
        $admin_email = get_option('admin_email');

        if ($email_type == 'email_upgrade') {
            $args = array();
            houzez_email_type( $admin_email, 'featured_submission', $args );
        } else {
            $args = array();
            houzez_email_type( $admin_email, 'paid_submission', $args );
        }
    }
}


add_action( 'wp_ajax_nopriv_houzez_contact_agent', 'houzez_contact_agent' );
add_action( 'wp_ajax_houzez_contact_agent', 'houzez_contact_agent' );
if( !function_exists( 'houzez_contact_agent' ) ) {
    function houzez_contact_agent() {

        $nonce = $_POST['agent_detail_ajax_nonce'];
        if (!wp_verify_nonce( $nonce, 'agent-contact-nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Unverified Nonce!', 'houzez')
            ));
            wp_die();
        }

        $sender_phone = sanitize_text_field( $_POST['phone'] );

        $target_email = sanitize_email($_POST['target_email']);
        $target_email = is_email($target_email);
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Target Email address is not properly configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }


        $sender_name = sanitize_text_field($_POST['name']);
        if ( empty($sender_name) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Provided Email address is invalid!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = wp_kses_post( $_POST['message'] );
        if ( empty($sender_msg) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message empty!', 'houzez')
            ));
            wp_die();
        }

        houzez_google_recaptcha_callback();

        $email_subject = sprintf( esc_html__('New message sent by %s using contact form at %s', 'houzez'), $sender_name, get_bloginfo('name') );

        $email_body = esc_html__("You have received a message from: ", 'houzez') . $sender_name . " <br/>";
        if (!empty($sender_phone)) {
            $email_body .= esc_html__("Phone Number : ", 'houzez') . $sender_phone . " <br/>";
        }
        $email_body .= esc_html__("Additional message is as follows.", 'houzez') . " <br/>";
        $email_body .= wpautop( $sender_msg ) . " <br/>";
        $email_body .= sprintf( esc_html__( 'You can contact %s via email %s', 'houzez'), $sender_name, $sender_email );


        $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
        //$header .= 'From: ' . $sender_name . " <" . $sender_email . "> \r\n";

        $header  .= "From: $sender_name <$sender_email>\r\n";
        $header .= "MIME-Version: 1.0\r\n";

        if (wp_mail( $target_email, $email_subject, $email_body, $header)) {
            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Message Sent Successfully!", 'houzez')
            ));
            wp_die();
        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
            wp_die();
        }

        wp_die();
    }
}

add_action( 'wp_ajax_nopriv_houzez_agent_send_message', 'houzez_agent_send_message' );
add_action( 'wp_ajax_houzez_agent_send_message', 'houzez_agent_send_message' );

if( !function_exists('houzez_agent_send_message') ) {
    function houzez_agent_send_message() {

        $agent_forms_terms = houzez_option('agent_forms_terms');

        $nonce = $_POST['agent_contact_form_ajax'];
        if (!wp_verify_nonce( $nonce, 'agent-contact-form-nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid Nonce!', 'houzez')
            ));
            wp_die();
        }

        $sender_phone = sanitize_text_field( $_POST['phone'] );
        $property_link = esc_url( $_POST['property_permalink'] );
        $property_title = sanitize_text_field( $_POST['property_title'] );
        $response = $_POST["g-recaptcha-response"];

        $target_email = $_POST['target_email'];
        if ( !is_array( $target_email ) ) {
            $target_email = is_email($target_email);
        }
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Email address is not configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = sanitize_text_field($_POST['name']);
        if ( empty($sender_name) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        
        if ( empty($sender_phone) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Phone field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid email address!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = wp_kses_post( $_POST['message'] );
        if ( empty($sender_msg) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message is empty!', 'houzez')
            ));
            wp_die();
        }

        houzez_google_recaptcha_callback();

        if( $agent_forms_terms != 0 ) {
            $term_condition = $_POST['term_condition'];
            if ( empty($term_condition) ) {
                echo json_encode(array(
                    'success' => false,
                    'msg' => houzez_option('agent_forms_terms_validation')
                ));
                wp_die();
            }
        }


        $subject = sprintf( esc_html__('New message sent by %s using agent contact form at %s', 'houzez'), $sender_name, get_bloginfo('name') );

        $body = esc_html__("You have received new message from: ", 'houzez') . $sender_name . " <br/>";

        if ( ! empty( $property_title ) ) {
            $body .= "<br/>" . esc_html__("Property Title : ", 'houzez') . $property_title . " <br/>";
        }

        if ( ! empty( $property_link ) ) {
            $body .= esc_html__("Property URL : ", 'houzez') . '<a href="'. $property_link. '">' . $property_link . "</a><br/>";
        }

        if (! empty($sender_phone)) {
            $body .= esc_html__("Phone Number : ", 'houzez') . $sender_phone . " <br/>";
        }

        $body .= "<br/>" . esc_html__("Additional message is.", 'houzez') . " <br/>";
        $body .= wpautop( $sender_msg ) . " <br/>";
        $body .= sprintf( esc_html__( 'You can contact %s via email %s', 'houzez'), $sender_name, $sender_email );

        $header = 'Content-type: text/html; charset=utf-8' . "\r\n";

        $header  .= "From: $sender_name <$sender_email>\r\n";
        $header .= "MIME-Version: 1.0\r\n";


        /*
        // Send copy of message to admin */
        $send_message_copy = houzez_option('send_agent_message_copy');
        if( $send_message_copy == '1' ){
            $cc_email = houzez_option( 'send_agent_message_email' );
            $cc_email = explode( ',', $cc_email );
            if( !empty( $cc_email ) ){
                foreach( $cc_email as $email ){
                    $email = sanitize_email( $email );
                    $email = is_email( $email );
                    if ( $email ) {
                        $header .= 'Cc: ' . $email . " \r\n";
                    }
                }
            }

        }

        if ( wp_mail( $target_email, $subject, $body, $header ) ) {
            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Email Sent Successfully!", 'houzez')
            ));
            wp_die();
        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
            wp_die();
        }
        wp_die();

    }
}

add_action( 'wp_ajax_nopriv_houzez_schedule_send_message', 'houzez_schedule_send_message' );
add_action( 'wp_ajax_houzez_schedule_send_message', 'houzez_schedule_send_message' );

if( !function_exists('houzez_schedule_send_message') ) {
    function houzez_schedule_send_message() {

        $agent_forms_terms = houzez_option('agent_forms_terms');

        $nonce = $_POST['schedule_contact_form_ajax'];
        if (!wp_verify_nonce( $nonce, 'schedule-contact-form-nonce') ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid Nonce!', 'houzez')
            ));
            wp_die();
        }

        $sender_phone = sanitize_text_field( $_POST['phone'] );
        $property_link = esc_url( $_POST['property_permalink'] );
        $property_title = sanitize_text_field( $_POST['property_title'] );

        $target_email = $_POST['target_email'];
        if ( !is_array( $target_email ) ) {
            $target_email = is_email($target_email);
        }
        if (!$target_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => sprintf( esc_html__('%s Email address is not configured!', 'houzez'), $target_email )
            ));
            wp_die();
        }

        $sender_name = sanitize_text_field($_POST['name']);
        if ( empty($sender_name) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Name field is empty!', 'houzez')
            ));
            wp_die();
        }

        if ( empty($sender_phone) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Phone field is empty!', 'houzez')
            ));
            wp_die();
        }

        $sender_email = sanitize_email($_POST['email']);
        $sender_email = is_email($sender_email);
        if (!$sender_email) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Invalid email address!', 'houzez')
            ));
            wp_die();
        }

        $sender_msg = wp_kses_post( $_POST['message'] );
        if ( empty($sender_msg) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Your message is empty!', 'houzez')
            ));
            wp_die();
        }

        $schedule_date = wp_kses_post( $_POST['schedule_date'] );
        if ( empty($schedule_date) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Date field is empty!', 'houzez')
            ));
            wp_die();
        }

        $schedule_time = wp_kses_post( $_POST['schedule_time'] );
        if ( empty($schedule_time) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__('Time field is empty!', 'houzez')
            ));
            wp_die();
        }

        if( $agent_forms_terms != 0 ) {
            $term_condition = $_POST['term_condition'];
            if ( empty($term_condition) ) {
                echo json_encode(array(
                    'success' => false,
                    'msg' => houzez_option('agent_forms_terms_validation')
                ));
                wp_die();
            }
        }


        $subject = sprintf( esc_html__('New message sent by %s using schedule contact form at %s', 'houzez'), $sender_name, get_bloginfo('name') );

        $body = esc_html__("You have received new message from: ", 'houzez') . $sender_name . " <br/>";

        if ( ! empty( $property_title ) ) {
            $body .= "<br/>" . esc_html__("Property Title : ", 'houzez') . $property_title . " <br/>";
        }

        if ( ! empty( $property_link ) ) {
            $body .= esc_html__("Property URL : ", 'houzez') . '<a href="'. $property_link. '">' . $property_link . "</a><br/>";
        }

        if (! empty($sender_phone)) {
            $body .= esc_html__("Phone Number : ", 'houzez') . $sender_phone . " <br/>";
        }

        if (! empty($schedule_date)) {
            $body .= esc_html__("Date : ", 'houzez') . $schedule_date . " <br/>";
        }

        if (! empty($schedule_time)) {
            $body .= esc_html__("Time : ", 'houzez') . $schedule_time . " <br/>";
        }

        $body .= "<br/>" . esc_html__("Additional message is.", 'houzez') . " <br/>";
        $body .= wpautop( $sender_msg ) . " <br/>";
        $body .= sprintf( esc_html__( 'You can contact %s via email %s', 'houzez'), $sender_name, $sender_email );

        $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
        //$header .= 'From: ' . $sender_name . " <" . $sender_email . "> \r\n";

        $header  .= "From: $sender_name <$sender_email>\r\n";
        $header .= "MIME-Version: 1.0\r\n";

        /*
        // Send copy of message to admin */
        $send_message_copy = houzez_option('send_agent_message_copy');
        if( $send_message_copy == '1' ){
            $cc_email = houzez_option( 'send_agent_message_email' );
            $cc_email = explode( ',', $cc_email );
            if( !empty( $cc_email ) ){
                foreach( $cc_email as $email ){
                    $email = sanitize_email( $email );
                    $email = is_email( $email );
                    if ( $email ) {
                        $header .= 'Cc: ' . $email . " \r\n";
                    }
                }
            }

        }

        if ( wp_mail( $target_email, $subject, $body, $header ) ) {
            echo json_encode( array(
                'success' => true,
                'msg' => esc_html__("Email Sent Successfully!", 'houzez')
            ));
            wp_die();
        } else {
            echo json_encode(array(
                    'success' => false,
                    'msg' => esc_html__("Server Error: Make sure Email function working on your server!", 'houzez')
                )
            );
            wp_die();
        }
        wp_die();

    }
}

/*
 * Google reCaptcha filter
 * */
if(!function_exists('houzez_google_recaptcha_callback')) {
    function houzez_google_recaptcha_callback() {

        $recaptha_site_key = houzez_option('recaptha_site_key');
        $recaptha_secret_key = houzez_option('recaptha_secret_key');
        $enable_reCaptcha = houzez_option('enable_reCaptcha');

        if( $enable_reCaptcha != 1 ) {
            return true;
        }

        // include library https://github.com/google/recaptcha
        include_once(  get_template_directory() . '/framework/recaptcha/src/autoload.php' );

        // If the form submission includes the "g-captcha-response" field
        // Create an instance of the service using your secret
        //$recaptcha = new \ReCaptcha\ReCaptcha($recaptha_secret_key);
        $recaptcha = new \ReCaptcha\ReCaptcha( $recaptha_secret_key, new \ReCaptcha\RequestMethod\CurlPost() );

        // If file_get_contents() is locked down on your PHP installation to disallow
        // its use with URLs, then you can use the alternative request method instead.
        // This makes use of fsockopen() instead.
        //  $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

        // Make the call to verify the response and also pass the user's IP address
        $resp = $recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER['REMOTE_ADDR']);


        if ($resp->isSuccess()):
            return true;
        else:

            $error_codes   = $resp->getErrorCodes();

            //Error codes - https://developers.google.com/recaptcha/docs/verify
            $captach_errors  = array(
                'missing-input-secret'   => esc_html__('The secret parameter is missing.', 'houzez'),
                'invalid-input-secret'   => esc_html__('The secret parameter is invalid or malformed.', 'houzez'),
                'missing-input-response' => esc_html__('The response parameter is missing.', 'houzez'),
                'invalid-input-response' => esc_html__('The response parameter is invalid or malformed.', 'houzez'),
                'bad-request' => esc_html__('The request is invalid or malformed.', 'houzez'),
            );
            $error_message = $captach_errors[ $error_codes[ 0 ]];
            echo json_encode( array(
                'success' => false,
                'msg' => esc_html__( 'reCAPTCHA Failed:', 'houzez' ) . ' ' . $error_message
            ) );
            wp_die();
        endif;
    }
}

//add_action('wpcf7_before_send_mail', 'wpcf7_update_email_body');