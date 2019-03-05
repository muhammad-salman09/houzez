<?php
/**
 * Template Name: Submit Property
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 06/10/15
 * Time: 3:49 PM
 */
global $houzez_local, $current_user, $properties_page, $hide_add_prop_fields, $required_fields, $is_multi_steps;

wp_get_current_user();
$userID = $current_user->ID;

$user_email = $current_user->user_email;
$admin_email =  get_bloginfo('admin_email');
$panel_class = '';

$invalid_nonce = false;
$submitted_successfully = false;
$updated_successfully = false;
$dashboard_listings = houzez_dashboard_listings();
$hide_add_prop_fields = houzez_option('hide_add_prop_fields');
$required_fields = houzez_option('required_fields');
$enable_paid_submission = houzez_option('enable_paid_submission');
$payment_page_link = houzez_get_template_link('template/template-payment.php');
$thankyou_page_link = houzez_get_template_link('template/template-thankyou.php');
$select_packages_link = houzez_get_template_link('template/template-packages.php');
$sticky_sidebar = houzez_option('sticky_sidebar');
$allowed_html = array();
$submit_form_type = houzez_option('submit_form_type');

if( $submit_form_type == 'one_step' ) {
    $submit_form_main_class = 'houzez-one-step-form';
    $is_multi_steps = 'active';
} else {
    $submit_form_main_class = 'houzez-m-step-form';
    $is_multi_steps = 'form-step';
}

if( isset( $_POST['action'] ) ) {

    $submission_action = $_POST['action'];

    //if (wp_verify_nonce($_POST['property_nonce'], 'submit_property')) {

        $new_property = array(
            'post_type'	=> 'property'
        );

        if( $enable_paid_submission == 'per_listing' ) {

            if ( !is_user_logged_in() ) {
                $email = wp_kses( $_POST['user_email'], $allowed_html );
                if( email_exists( $email ) ) {
                    $errors[] = $houzez_local['email_already_registerd'];
                }

                if( !is_email( $email ) ) {
                    $errors[] = $houzez_local['invalid_email'];
                }

                if( empty($errors) ) {
                    $username = explode("@", $email);

                    if( username_exists( $username[0] ) ) {
                        $username = $username[0].rand(5, 999);
                    } else {
                        $username = $username[0];
                    }

                    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                    $user_id = wp_create_user( $username, $random_password, $email );

                    if( !is_wp_error( $user_id ) ) {
                        $user = get_user_by( 'id', $user_id );

                        houzez_update_profile( $user_id );
                        houzez_wp_new_user_notification( $user_id, $random_password );
                        $user_as_agent = houzez_option('user_as_agent');
                        if( $user_as_agent == 'yes' ) {
                            houzez_register_as_agent ( $username, $email, $user_id );
                        }

                        if( !is_wp_error($user) ) {
                            wp_clear_auth_cookie();
                            wp_set_current_user( $user->ID, $user->user_login );
                            wp_set_auth_cookie( $user->ID );
                            do_action( 'wp_login', $user->user_login );

                            $property_id = apply_filters( 'houzez_submit_listing', $new_property );

                            if (!empty($payment_page_link)) {
                                $separator = (parse_url($payment_page_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                                $parameter = 'prop-id=' . $property_id;
                                wp_redirect($payment_page_link . $separator . $parameter);

                            } elseif( !empty($payment_page_link) && isset($_POST['houzez_draft']) ) {
                                $separator = (parse_url($payment_page_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                                $parameter = 'prop-id=' . $property_id;
                                wp_redirect($payment_page_link . $separator . $parameter);

                            } else {
                                if (!empty($dashboard_listings)) {
                                    $separator = (parse_url($dashboard_listings, PHP_URL_QUERY) == NULL) ? '?' : '&';
                                    $parameter = ($updated_successfully) ? '' : '';
                                    wp_redirect($dashboard_listings . $separator . $parameter);
                                }
                            }
                            exit();
                        }

                    }

                }

            } else {
                $property_id = apply_filters('houzez_submit_listing', $new_property);
                if (!empty($payment_page_link) && $submission_action != 'update_property' ) {
                    $separator = (parse_url($payment_page_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                    $parameter = 'prop-id=' . $property_id;
                    wp_redirect($payment_page_link . $separator . $parameter);

                } elseif( !empty($payment_page_link) && isset($_POST['houzez_draft']) ) {
                    $separator = (parse_url($payment_page_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                    $parameter = 'prop-id=' . $property_id;
                    wp_redirect($payment_page_link . $separator . $parameter);
                } else {
                    if (!empty($dashboard_listings)) {
                        $separator = (parse_url($dashboard_listings, PHP_URL_QUERY) == NULL) ? '?' : '&';
                        $parameter = 'updated=1';
                        wp_redirect($dashboard_listings . $separator . $parameter);
                    }
                }
            }
        // End per listing if
        } else if( $enable_paid_submission == 'membership' ) {

            if ( !is_user_logged_in() ) {
                $email = wp_kses( $_POST['user_email'], $allowed_html );
                if( email_exists( $email ) ) {
                    $errors[] = $houzez_local['email_already_registerd'];
                }

                if( !is_email( $email ) ) {
                    $errors[] = $houzez_local['invalid_email'];
                }

                if( empty($errors) ) {
                    $username = explode("@", $email);

                    if( username_exists( $username[0] ) ) {
                        $username = $username[0].rand(5, 999);
                    } else {
                        $username = $username[0];
                    }

                    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                    $user_id = wp_create_user( $username, $random_password, $email );

                    if( !is_wp_error( $user_id ) ) {

                        $user = get_user_by( 'id', $user_id );

                        houzez_update_profile( $user_id );
                        houzez_wp_new_user_notification( $user_id, $random_password );
                        $user_as_agent = houzez_option('user_as_agent');
                        if( $user_as_agent == 'yes' ) {
                            houzez_register_as_agent ( $username, $email, $user_id );
                        }

                        if( !is_wp_error($user) ) {
                            wp_clear_auth_cookie();
                            wp_set_current_user( $user->ID, $user->user_login );
                            wp_set_auth_cookie( $user->ID );
                            do_action( 'wp_login', $user->user_login );

                            $property_id = apply_filters( 'houzez_submit_listing', $new_property );

                            $args = array(
                                'listing_title'  =>  get_the_title($property_id),
                                'listing_id'     =>  $property_id
                            );

                            /*
                             * Send email
                             * */
                            houzez_email_type( $user_email, 'free_submission_listing', $args);
                            houzez_email_type( $admin_email, 'admin_free_submission_listing', $args);

                            $separator = (parse_url($select_packages_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                            $parameter = '';//'prop-id=' . $property_id;
                            wp_redirect($select_packages_link . $separator . $parameter);
                            exit();
                        }

                    }

                }

            // end is_user_logged_in if
            } else {
                $property_id = apply_filters('houzez_submit_listing', $new_property);
                $args = array(
                    'listing_title'  =>  get_the_title($property_id),
                    'listing_id'     =>  $property_id
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'free_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_free_submission_listing', $args);

                if (houzez_user_has_membership($userID)) {
                    wp_redirect($thankyou_page_link);
                } // end membership check
                else {
                    $separator = (parse_url($select_packages_link, PHP_URL_QUERY) == NULL) ? '?' : '&';
                    $parameter = '';//'prop-id=' . $property_id;
                    wp_redirect($select_packages_link . $separator . $parameter);
                    exit();
                }
            }

        // End membership else if
        } else {

            if ( !is_user_logged_in() ) {
                $email = wp_kses( $_POST['user_email'], $allowed_html );
                if( email_exists( $email ) ) {
                    $errors[] = $houzez_local['email_already_registerd'];
                }

                if( !is_email( $email ) ) {
                    $errors[] = $houzez_local['invalid_email'];
                }

                if( empty($errors) ) {
                    $username = explode("@", $email);

                    if( username_exists( $username[0] ) ) {
                        $username = $username[0].rand(5, 999);
                    } else {
                        $username = $username[0];
                    }

                    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                    $user_id = wp_create_user( $username, $random_password, $email );

                    if( !is_wp_error( $user_id ) ) {

                        $user = get_user_by( 'id', $user_id );

                        houzez_update_profile( $user_id );
                        houzez_wp_new_user_notification( $user_id, $random_password );
                        $user_as_agent = houzez_option('user_as_agent');
                        if( $user_as_agent == 'yes' ) {
                            houzez_register_as_agent ( $username, $email, $user_id );
                        }

                        if( !is_wp_error($user) ) {
                            wp_clear_auth_cookie();
                            wp_set_current_user( $user->ID, $user->user_login );
                            wp_set_auth_cookie( $user->ID );
                            do_action( 'wp_login', $user->user_login );

                            $property_id = apply_filters( 'houzez_submit_listing', $new_property );

                            $args = array(
                                'listing_title'  =>  get_the_title($property_id),
                                'listing_id'     =>  $property_id
                            );

                            /*
                             * Send email
                             * */
                            houzez_email_type( $user_email, 'free_submission_listing', $args);
                            houzez_email_type( $admin_email, 'admin_free_submission_listing', $args);

                            if (!empty($thankyou_page_link)) {
                                wp_redirect($thankyou_page_link);

                            } else {
                                if (!empty($dashboard_listings)) {
                                    $separator = (parse_url($dashboard_listings, PHP_URL_QUERY) == NULL) ? '?' : '&';
                                    $parameter = ($updated_successfully) ? '' : '';
                                    wp_redirect($dashboard_listings . $separator . $parameter);
                                }
                            }
                            exit();
                        }

                    }

                }

            } else {

                $property_id = apply_filters('houzez_submit_listing', $new_property);

                $args = array(
                    'listing_title'  =>  get_the_title($property_id),
                    'listing_id'     =>  $property_id
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'free_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_free_submission_listing', $args);
                wp_redirect($thankyou_page_link);
            }
        }

    /*}// end verify nonce
    else {
        //nonce fail
    }*/
}

$create_listing_sidebar = false;
if( is_active_sidebar( 'create-listing-sidebar' ) ) {
    $create_listing_sidebar = true;
}

get_header(); ?>

<?php
$houzez_loggedin = false;
if ( is_user_logged_in() ) {
    get_template_part('template-parts/dashboard', 'menu');
    $panel_class = 'dashboard-with-panel';
    $houzez_loggedin = true;
    $column_classes = 'col-lg-10 col-md-9 col-sm-12 dashboard-inner-left';
} else {
    $column_classes = 'col-lg-12 col-md-12 col-sm-12';
}
?>

<div class="user-dashboard-right <?php echo esc_attr($submit_form_main_class); ?> <?php echo esc_attr($panel_class);?>">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">

            <?php get_template_part('template-parts/create-listing-top'); ?>

            <div class="row dashboard-inner-main">
                <div class="<?php echo esc_attr($column_classes);?>">
                <?php
                if( !empty($errors) ) {
                    foreach ($errors as $error ) {
                        echo esc_attr( $error );
                    }
                }
                if (is_plugin_active('houzez-theme-functionality/houzez-theme-functionality.php')) {
                    if (isset($_GET['edit_property']) && !empty($_GET['edit_property'])) {

                        get_template_part('template-parts/property-edit');

                    } else {

                        get_template_part('template-parts/property-submit');

                    } /* end of add/edit property*/
                } else {
                    echo $houzez_local['houzez_plugin_required'];
                }
                ?>
                </div>
                <?php if($houzez_loggedin) { ?>
                <div class="col-lg-2 col-md-3 col-sm-12 dashboard-inner-right">
                    <?php get_template_part('template-parts/dashboard-sidebar'); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer();?>