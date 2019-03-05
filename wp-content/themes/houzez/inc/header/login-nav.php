<?php
global $current_user, $houzez_local;
wp_get_current_user();
$userID  =  $current_user->ID;
$first_name  =  $current_user->first_name;
$last_name  =  $current_user->last_name;
$display_name = $current_user->display_name;

//$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $userID );
$author_picture_id      =   get_the_author_meta( 'fave_author_picture_id' , $userID );
$user_custom_picture = wp_get_attachment_image_src( $author_picture_id, array( 270, 270 ) );
$user_custom_picture = $user_custom_picture[0];

$header_type = houzez_option('header_style');

if( !empty($first_name) && !empty($last_name) ) {
    $display_name = $first_name.' '.$last_name;
}

if( empty( $user_custom_picture )) {
    $user_custom_picture = get_template_directory_uri().'/images/profile-avatar.png';
}

$dash_profile_link = houzez_get_template_link_2('template/user_dashboard_profile.php');
$dashboard_listings = houzez_get_template_link_2('template/user_dashboard_properties.php');
$dashboard_add_listing = houzez_get_template_link_2('template/submit_property.php');
$dashboard_favorites = houzez_get_template_link_2('template/user_dashboard_favorites.php');
$dashboard_search = houzez_get_template_link_2('template/user_dashboard_saved_search.php');
$dashboard_invoices = houzez_get_template_link_2('template/user_dashboard_invoices.php');
$dashboard_msgs = houzez_get_template_link_2('template/user_dashboard_messages.php');
$dashboard_membership = houzez_get_template_link_2('template/user_dashboard_membership.php');
$dashboard_gdpr = houzez_get_template_link_2('template/user_dashboard_gdpr.php');
$dashboard_seen_msgs = add_query_arg( 'view', 'seen', $dashboard_msgs );
$dashboard_unseen_msgs = add_query_arg( 'view', 'unseen', $dashboard_msgs );
$home_link = home_url('/');
$enable_paid_submission = houzez_option('enable_paid_submission');

$header_create_listing_template = houzez_get_template_link('template/submit_property.php');
$create_listing_button_required_login = houzez_option('create_listing_button');
$create_lisiting_enable = houzez_option('create_lisiting_enable');

$home_link = home_url('/');

$ac_profile = $ac_props = $ac_add_prop = $ac_fav = $ac_search = $ac_invoices = $ac_msgs = $ac_mem = $ac_gdpr = '';
if( is_page_template( 'template/user_dashboard_profile.php' ) ) {
    $ac_profile = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_properties.php' ) ) {
    $ac_props = 'class=active';
} elseif ( is_page_template( 'template/submit_property.php' ) ) {
    $ac_add_prop = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_saved_search.php' ) ) {
    $ac_search = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_favorites.php' ) ) {
    $ac_fav = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_invoices.php' ) ) {
    $ac_invoices = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_messages.php' ) ) {
    $ac_msgs = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_membership.php' ) ) {
    $ac_mem = 'class=active';
} elseif ( is_page_template( 'template/user_dashboard_gdpr.php' ) ) {
    $ac_gdpr = 'class=active';
}

$agency_agents = add_query_arg( 'agents', 'list', $dash_profile_link );
$agency_agent_add = add_query_arg( 'agent', 'add_new', $dash_profile_link );

$all = add_query_arg( 'prop_status', 'all', $dashboard_listings );
$approved = add_query_arg( 'prop_status', 'approved', $dashboard_listings );
$pending = add_query_arg( 'prop_status', 'pending', $dashboard_listings );
$expired = add_query_arg( 'prop_status', 'expired', $dashboard_listings );
$draft = add_query_arg( 'prop_status', 'draft', $dashboard_listings );
$ac_approved = $ac_pending = $ac_expired = $ac_all = $ac_draft = $ac_agents = $ac_agent_new = '';

if( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'approved' ) {
    $ac_approved = 'class=active';

} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'pending' ) {
    $ac_pending = 'class=active';

} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'expired' ) {
    $ac_expired = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'approved' ) {
    $ac_approved = 'class=active';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'draft' ) {
    $ac_draft = 'class=active';
} else {
    $ac_all = 'class=active';
}

if( isset( $_GET['agents'] ) && $_GET['agents'] == 'list' ) {
    $ac_agents = 'class=active';
} elseif( isset( $_GET['agent'] ) && $_GET['agent'] == 'add_new' ) {
    $ac_agent_new = 'class=active';
}
?>

<?php if( is_user_logged_in() ) { ?>
    <ul class="account-action">
        <li>
            <span class="user-name"><span><?php echo esc_attr( $display_name ); ?></span> <i class="fa fa-angle-down"></i></span>
            <span class="user-image">
                <?php echo houzez_messages_notification( 'user-alert' ); ?>
                <img src="<?php echo esc_url( $user_custom_picture ); ?>" width="36" height="36" class="img-circle" alt="profile image">
            </span>

            <div class="account-dropdown">
                <ul>
                    <?php
                    if( !empty( $dash_profile_link ) ) {
                        echo '<li ' .esc_attr( $ac_profile ). '> <a href="' . esc_url($dash_profile_link) . '"><i class="fa fa-user"></i>' . esc_html__('My profile', 'houzez') . '</a>';
                        if ( in_array('houzez_agency', (array)$current_user->roles) ) {
                            echo '<ul class="sub-menu">
                        <li ' . esc_attr($ac_agents) . '><a href="' . esc_url($agency_agents) . '">' . esc_html__('Agents', 'houzez') . '</a></li>
                        <li ' . esc_attr($ac_agent_new) . '><a href="' . esc_url($agency_agent_add) . '" ' . esc_attr($ac_agent_new) . '>' . esc_html__('Add New Agent', 'houzez') . '</a></li>
                    </ul>';
                        }
                        echo '</li>';
                    }
                    if( !empty( $dashboard_listings ) && houzez_check_role() ) {
                        echo '<li ' .esc_attr( $ac_props ). '> <a href="' . esc_url($dashboard_listings) . '"><i class="fa fa-building"></i>' . esc_html__('My Properties', 'houzez') . '</a>
                    <ul class="sub-menu">
                        <li '.esc_attr( $ac_all ).'><a href="' . esc_url($all) . '">'.$houzez_local['all'].'</a></li>
                        <li '.esc_attr( $ac_approved ).'><a href="'.esc_url($approved).'" '.esc_attr($ac_approved).'>'.$houzez_local['published'].'</a></li>
                        <li '.esc_attr( $ac_pending ).'><a href="'.esc_url($pending).'" '.esc_attr($ac_pending).'>'.$houzez_local['pending'].'</a></li>
                        <li '.esc_attr( $ac_expired ).'><a href="'.esc_url($expired).'" '.esc_attr($ac_expired).'>'.$houzez_local['expired'].'</a></li>
                        <li '.esc_attr( $ac_draft ).'><a href="'.esc_url($draft).'" '.esc_attr($ac_draft).'>'.$houzez_local['draft'].'</a></li>
                    </ul>
                    </li>';
                    }
                    if( !empty( $dashboard_add_listing ) && houzez_check_role() ) {
                        echo '<li ' .esc_attr( $ac_add_prop ). '> <a href="' . esc_url($dashboard_add_listing) . '"><i class="fa fa-plus-circle"></i>' . esc_html__('Add new property', 'houzez') . '</a></li>';
                    }
                    if( !empty( $dashboard_favorites ) ) {
                        echo '<li ' .esc_attr( $ac_fav ). '> <a href="' . esc_url($dashboard_favorites) . '"><i class="fa fa-heart"></i>' . esc_html__('Favourite properties', 'houzez') . '</a></li>';
                    }
                    if( !empty( $dashboard_search ) ) {
                        echo '<li ' .esc_attr( $ac_search ). '> <a href="' . esc_url($dashboard_search) . '"><i class="fa fa-search-plus"></i>' . esc_html__('Saved Searches', 'houzez') . '</a></li>';
                    }
                    if( !empty( $dashboard_invoices ) && houzez_check_role() ) {
                        echo '<li ' .esc_attr(  $ac_invoices ). '> <a href="' . esc_url($dashboard_invoices) . '"><i class="fa fa-file"></i>' . esc_html__('Invoices', 'houzez') . '</a></li>';
                    }
                    if( !empty($dashboard_msgs) ) {
                        echo '<li ' . esc_attr($ac_msgs) . '> <a href="' . esc_url($dashboard_msgs) . '"> <i class="fa fa-comments-o"></i>' . esc_html__('Messages', 'houzez') . houzez_messages_notification() . '</a></li>';
                    }
                    if( !empty($dashboard_membership) && $enable_paid_submission == 'membership' && houzez_check_role() ) {
                        echo '<li ' . esc_attr($ac_mem) . '> <a href="' . esc_url($dashboard_membership) . '"> <i class="fa fa-address-card-o"></i>' . esc_html__('Membership', 'houzez').'</a></li>';
                    }
                    if( !empty($dashboard_gdpr) ) {
                        echo '<li ' . esc_attr($ac_gdpr) . '> <a href="' . esc_url($dashboard_gdpr) . '"> <i class="fa fa-envelope"></i>' . esc_html__('GDPR Data Request', 'houzez').'</a></li>';
                    }
                    echo '<li><a href="' . wp_logout_url(home_url('/')) . '"> <i class="fa fa-unlock"></i>' . esc_html__('Log out', 'houzez') . '</a></li>';
                    ?>

                </ul>
            </div>

        </li>
    </ul>
<?php } else { ?>
    <div class="user">

            <?php
            if( houzez_option('header_login') != 'no' ) {
                echo '<a href="#" data-toggle="modal" data-target="#pop-login"><i class="fa fa-user hidden-md hidden-lg"></i> <span class="hidden-sm hidden-xs">'.esc_html__( 'Sign In / Register', 'houzez' ).'</span></a>';
            }
            if( $create_lisiting_enable != 0 ) {
                if( $create_listing_button_required_login == 'yes' ) {
                    echo '<a href="#" data-toggle="modal" data-target="#pop-login" class="btn btn-default hidden-xs hidden-sm">'.esc_html__( 'Create Listing', 'houzez' ).'</a>';

                } else {
                    if( !empty($header_create_listing_template) && $header_create_listing_template != $home_link ) {
                        echo '<a href="'.esc_url( $header_create_listing_template ).'" class="btn btn-default hidden-xs hidden-sm">'.esc_html__( 'Create Listing', 'houzez' ).'</a>';
                    }
                }
            }?>
    </div>
<?php } ?>
