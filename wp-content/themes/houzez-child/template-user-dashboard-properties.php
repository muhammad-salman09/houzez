<?php
/**
 * Template Name: User Dashboard Properties
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $wpdb, $houzez_local, $prop_featured, $current_user, $post;

wp_get_current_user();
$userID         = $current_user->ID;
$user_login     = $current_user->user_login;
$edit_link      = houzez_dashboard_add_listing();
$paid_submission_type = esc_html ( houzez_option('enable_paid_submission','') );
$packages_page_link = houzez_get_template_link('template-advanced-package.php');

$package_id = houzez_get_user_package_id( $userID );
$enableDoc = get_post_meta( $package_id, 'fave_encrypt_doc', true );

get_header();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

$meta_query = array();

if( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'approved' ) {
    $qry_status = 'publish';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'package' ) {
    $qry_status = 'publish';

    array_push($meta_query, array(
        'key' => 'fave_featured',
        'value' => 1,
        'compare' => '=',
    ));

    array_push($meta_query, array(
        'key' => 'fave_week',
        'value' => 1,
        'compare' => '=',
    ));

    $meta_query['relation'] = 'OR';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'pending' ) {
    $qry_status = 'pending';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'expired' ) {
    $qry_status = 'expired';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'draft' ) {
    $qry_status = 'draft';
} elseif( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'on_hold' ) {
    $qry_status = 'on_hold';
} else {
    $qry_status = 'any';
}

$sortby = '';

if( isset( $_GET['sortby'] ) ) {
    $sortby = $_GET['sortby'];
}

$no_of_prop   =  '10';
$paged        = (get_query_var('paged')) ? get_query_var('paged') : 1;

if (empty($meta_query)) {
    $args = array(
        'post_type'      => 'property',
        'author'         => $userID,
        'paged'          => $paged,
        'posts_per_page' => $no_of_prop,
        'post_status'    => array( $qry_status )
    );

    if( isset ( $_GET['keyword'] ) ) {
        $keyword = trim( $_GET['keyword'] );
        if ( ! empty( $keyword ) ) {
            $args['s'] = $keyword;
        }
    }

    $args = houzez_prop_sort ( $args );
    $prop_qry = new WP_Query($args);
} else {
    $args = array(
        'post_type'      => 'property',
        'author'         => $userID,
        'posts_per_page' => -1,
        'post_status'    => array( $qry_status ),
        'meta_query'     => $meta_query
    );

    $featured = new WP_Query($args);

    $ids = array();
    if ($featured->have_posts())
        $ids = wp_list_pluck( $featured->posts, 'ID' );

    $args = array(
        'post_type'      => 'property',
        'author'         => $userID,
        'paged'          => $paged,
        'posts_per_page' => $no_of_prop,
        'post_status'    => array( $qry_status ),
        'post__not_in'   => $ids
    );

    if( isset ( $_GET['keyword'] ) ) {
        $keyword = trim( $_GET['keyword'] );
        if ( ! empty( $keyword ) ) {
            $args['s'] = $keyword;
        }
    }

    $args = houzez_prop_sort ( $args );
    $prop_qry = new WP_Query($args);
}

if ($lang != 'en') {
    $query = $prop_qry->request;

    $search = "( wpml_translations.language_code = '" . $lang . "' OR 0 ) AND";
    $replace = "( wpml_translations.language_code = 'en' OR 0 ) AND";

    $enQuery = str_replace($search, $replace, $query);

    $enProps = $wpdb->get_results($enQuery);
}

?>
<?php get_template_part( 'template-parts/dashboard', 'menu' ); ?>

    <div class="user-dashboard-right dashboard-with-panel">

        <?php get_template_part( 'template-parts/dashboard-title' ); ?>

        <div class="dashboard-content-area dashboard-fix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="my-profile-search">
                            <div class="profile-top-left">
                                <form method="get" action="">
                                    <input type="hidden" name="prop_status" value="<?php echo isset($_GET['prop_status']) ? $_GET['prop_status'] : '';?>">
                                    <div class="single-input-search">
                                        <input class="form-control" name="keyword" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '';?>" placeholder="<?php echo esc_html__('Search listing', 'houzez'); ?>" type="text">
                                        <button type="submit"></button>
                                    </div>
                                </form>
                            </div>
                            <div class="profile-top-right">
                                <div class="sort-tab text-right">
                                    <?php esc_html_e( 'Sort by:', 'houzez' ); ?>
                                    <select id="sort_properties" class="selectpicker bs-select-hidden" title="" data-live-search-style="begins" data-live-search="false">
                                        <option value=""><?php esc_html_e( 'Default Order', 'houzez' ); ?></option>
                                        <option <?php if( $sortby == 'a_price' ) { echo "selected"; } ?> value="a_price"><?php esc_html_e( 'Price (Low to High)', 'houzez' ); ?></option>
                                        <option <?php if( $sortby == 'd_price' ) { echo "selected"; } ?> value="d_price"><?php esc_html_e( 'Price (High to Low)', 'houzez' ); ?></option>
                                        <option <?php if( $sortby == 'featured' ) { echo "selected"; } ?> value="featured"><?php esc_html_e( 'Featured', 'houzez' ); ?></option>
                                        <option <?php if( $sortby == 'a_date' ) { echo "selected"; } ?> value="a_date"><?php esc_html_e( 'Date Old to New', 'houzez' ); ?></option>
                                        <option <?php if( $sortby == 'd_date' ) { echo "selected"; } ?> value="d_date"><?php esc_html_e( 'Date New to Old', 'houzez' ); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="my-property-listing">

                            <?php if( $prop_qry->have_posts() ) { ?>

                                <div class="row grid-row">
                                    <?php

                                    $en_IDs = array();

                                    while ($prop_qry->have_posts()): $prop_qry->the_post();

                                        if ($lang != 'en') {
                                            $en_ID = strval(icl_object_id(get_the_ID(), 'property', false, 'en'));

                                            array_push($en_IDs, $en_ID);
                                        }

                                        get_template_part('template-parts/dashboard_property_unit');

                                    endwhile;

                                    if ($lang != 'en') {
                                        foreach ($enProps as $property) {
                                            if (!in_array($property->ID, $en_IDs)) {
                                                $post_id    = $property->ID;

                                                $prop_featured = get_post_meta( $post_id, 'fave_featured', true );
                                                $prop_week = get_post_meta( $post_id, 'fave_week', true );
                                                $houzez_local['week'] = 'Property of the Week';

                                                $edit_link  = add_query_arg( 'edit_property', $post_id, $edit_link ) ;
                                                $delete_link  = add_query_arg( 'property_id', $post_id, $dashboard_listings ) ;
                                                $property_status = get_post_status ( $post_id );
                                                $property_status_text = $property_status;
                                                $payment_status = get_post_meta( $post_id, 'fave_payment_status', true );

                                                $paid_submission_type  = houzez_option('enable_paid_submission');
                                                $price_per_submission = houzez_option('price_listing_submission');
                                                $price_featured_submission = houzez_option('price_featured_listing_submission');
                                                $price_per_submission = floatval($price_per_submission);
                                                $price_featured_submission = floatval($price_featured_submission);
                                                $currency = houzez_option('currency_paid_submission');

                                                $add_floor_plans = houzez_get_template_link_2('template/user_dashboard_floor_plans.php');
                                                $payment_page = houzez_get_template_link('template/template-payment.php');
                                                $payment_page_link = add_query_arg( 'prop-id', $post_id, $payment_page );
                                                $payment_page_link_featured = add_query_arg( 'upgrade_id', $post_id, $payment_page );
                                                $add_floor_plans_link = add_query_arg( 'listing_id', $post_id, $add_floor_plans );

                                                $add_multiunits = houzez_get_template_link_2('template/user_dashboard_multi_units.php');
                                                $add_multiunits_link = add_query_arg( 'listing_id', $post_id, $add_multiunits );

                                                $upload_link = houzez_get_template_link('template-document-upload.php');
                                                $upload_link = add_query_arg( 'listing_id', $post_id, $upload_link );

                                                $dashboard_package = houzez_get_template_link_2('template-user-dashboard-package.php');

                                                if( $property_status == 'publish' ) {
                                                    $property_status = '<span class="label label-success">'.esc_html__('Approved', 'houzez').'</span>';
                                                } elseif( $property_status == 'on_hold' ) {
                                                    $property_status = '<span class="label label-success">'.$houzez_local['on_hold'].'</span>';
                                                } elseif( $property_status == 'pending' ) {
                                                    $property_status = '<span class="label label-warning">'.esc_html__('Under Approved', 'houzez').'</span>';
                                                }  elseif( $property_status == 'expired' ) {
                                                    $property_status = '<span class="label label-danger">'.esc_html__('Expired', 'houzez').'</span>';
                                                    $payment_status_label = '<span class="label label-danger">'.esc_html__('Expired', 'houzez').'</span>';
                                                } else {
                                                    $property_status = '';
                                                }

                                                if( $property_status_text != 'expired' ) {
                                                    if ($paid_submission_type != 'no' && $paid_submission_type != 'membership' && $paid_submission_type != 'free_paid_listing' ) {
                                                        if ($payment_status == 'paid') {
                                                            $payment_status_label = '<span class="label label-success">' . esc_html__('PAID', 'houzez') . '</span>';
                                                        } elseif ($payment_status == 'not_paid') {
                                                            $payment_status_label = '<span class="label label-warning">' . esc_html__('NOT PAID', 'houzez') . '</span>';
                                                        } else {
                                                            $payment_status_label = '';
                                                        }
                                                    } else {
                                                        $payment_status_label = '';
                                                    }
                                                }
                                    ?>
                                    <div class="item-wrap">
                                        <div class="media my-property">
                                            <div class="media-left">
                                                <div class="figure-block">
                                                    <figure class="item-thumb">
                                                        <a href="<?php echo get_the_permalink($post_id) ?>">
                                                            <?php
                                                            if( has_post_thumbnail($post_id) ) {
                                                                echo get_the_post_thumbnail($post_id, 'houzez-widget-prop');
                                                            }else{
                                                                houzez_image_placeholder( 'houzez-widget-prop' );
                                                            }
                                                            ?>
                                                        </a>
                                                        <?php if( $prop_featured != 0 ) { ?>
                                                            <span class="label-featured label"><?php echo $houzez_local['featured'] ?></span>
                                                        <?php } ?>
                                                        <?php if( $prop_week != 0 ) { ?>
                                                            <span class="label-week label"><?php echo $houzez_local['week'] ?></span>
                                                        <?php } ?>
                                                    </figure>
                                                </div>
                                            </div>
                                            <div class="media-body media-middle">
                                                <div class="my-description">
                                                    <h4 class="my-heading">
                                                        <a href="<?php echo get_the_permalink($post_id) ?>">
                                                            <?php echo get_the_title($post_id); ?>
                                                            <?php echo $payment_status_label; ?>
                                                        </a>
                                                    </h4>
                                                    <?php if( !empty( $prop_address )) { ?>
                                                        <address class="address">
                                                            <?php echo esc_attr( $prop_address ); ?>
                                                        </address>
                                                    <?php } ?>
                                                    <div class="status">
                                                        <p>
                                                            <span>
                                                                <strong><?php esc_html_e( 'Status:', 'houzez' ); ?></strong> <?php echo houzez_taxonomy_simple( 'property_status' ); ?>
                                                            </span>
                                                            <span>
                                                                <strong><?php esc_html_e( 'Price:', 'houzez' ); ?></strong> <?php echo houzez_listing_price_v2($post_id); ?>
                                                            </span>
                                                            <?php
                                                            $listing_area_size = houzez_get_listing_area_size( $post_id );
                                                            if( !empty( $listing_area_size ) ) {
                                                                echo '<span>';
                                                                echo '<strong>'.houzez_get_listing_size_unit($post_id) . ': </strong> ' . houzez_get_listing_area_size($post_id);
                                                                echo '</span>';
                                                            }
                                                            ?>
                                                            <span><?php echo houzez_taxonomy_simple('property_type'); ?></span>
                                                        </p>
                                                        <?php if( houzez_user_role_by_post_id($post_id) != 'administrator' && get_post_status ( $post_id ) == 'publish' ) { ?>
                                                            <p class="expiration_date"><strong><?php echo esc_html__('Expiration:', 'houzez'); ?></strong> <?php houzez_listing_expire(); ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="my-actions">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php esc_html_e('Actions', 'houzez');?> <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu actions-dropdown">
                                                            <li><a href="<?php echo esc_url($edit_link); ?>"><i class="fa fa-edit"></i> <?php esc_html_e('Edit', 'houzez');?></a></li>
                                                            <li><a class="delete-property" data-id="<?php echo $post_id; ?>" data-nonce="<?php echo wp_create_nonce('delete_my_property_nonce') ?>" onclicks="return confirm('<?php esc_html_e( 'Are you sure you want to delete?', 'houzez' ); ?>')" href="#"><i class="fa fa-close"></i> <?php esc_html_e('Delete', 'houzez');?></a></li>

                                                            <li><a href="#" class="clone-property" data-property="<?php echo $post_id; ?>"><i class="fa fa-edit"></i> <?php esc_html_e('Duplicate', 'houzez');?></a></li>

                                                            <?php if(houzez_is_published( $post_id )) { ?>
                                                            <li><a href="#" class="put-on-hold" data-property="<?php echo $post_id; ?>"><i class="fa fa-stop"></i> <?php esc_html_e('Put On Hold', 'houzez');?></a></li>
                                                            <?php } elseif (houzez_on_hold( $post_id )) { ?>
                                                                <li><a href="#" class="put-on-hold" data-property="<?php echo $post_id; ?>"><i class="fa fa-play"></i> <?php esc_html_e('Go Live', 'houzez');?></a></li>
                                                            <?php } ?>

                                                            <?php if( houzez_check_post_status( $post_id ) ) { ?>

                                                                <?php if( !empty($add_floor_plans) ) { ?>
                                                                    <li><a href="<?php echo $add_floor_plans_link; ?>"><i class="fa fa-book"></i> <?php esc_html_e( 'Floor Plans', 'houzez' ); ?></a></li>
                                                                <?php } ?>
                                                                <?php if( !empty($add_multiunits) ) { ?>
                                                                    <li><a href="<?php echo $add_multiunits_link; ?>"><i class="fa fa-th-large"></i> <?php esc_html_e( 'Multi Units / Sub Properties', 'houzez' ); ?></a></li>
                                                                <?php } ?>

                                                            <?php } ?>
                                                            <?php if ($enableDoc == 1) { ?>
                                                            <li><a href="<?php echo esc_url($upload_link); ?>"><i class="fa fa-upload"></i> <?php esc_html_e('Document Upload', 'houzez'); ?></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <?php
                                                    if( $paid_submission_type == 'per_listing' && $property_status_text != 'expired' ) {
                                                        echo '<div class="btn-group">';
                                                        if ($payment_status != 'paid') {
                                                            echo '<a href="' . esc_url($payment_page_link) . '" class="btn pay-btn">' . esc_html__('Pay Now', 'houzez') . '</a>';
                                                        } else {
                                                            if( $prop_featured != 1 && $property_status_text == 'publish' ) {
                                                                echo '<a href="' . esc_url($payment_page_link_featured) . '" class="btn pay-btn">' . esc_html__('Upgrade to Featured', 'houzez') . '</a>';
                                                            }
                                                        }
                                                        echo '</div>';
                                                    }

                                                    if( $property_status_text == 'expired' && ( $paid_submission_type == 'per_listing') ) {
                                                        echo '<div class="btn-group">';
                                                            echo '<a href="' . esc_url($payment_page_link) . '" class="btn pay-btn">'.esc_html__( 'Re-List', 'houzez' ).'</a>';
                                                        echo '</div>';
                                                    }

                                                    if( $property_status_text == 'expired' && ( $paid_submission_type == 'free_paid_listing' || $paid_submission_type == 'no' ) ) {
                                                        echo '<div class="btn-group">';
                                                            echo '<a href="#" data-property="'.$post_id.'" class="relist-free btn pay-btn">'.esc_html__( 'Re-List', 'houzez' ).'</a>';
                                                        echo '</div>';
                                                    }

                                                    if( houzez_check_post_status( $post_id ) ) {

                                                        if (isset($_GET['prop_status']) && $_GET['prop_status'] == 'package') {
                                                            if ( $paid_submission_type == 'membership' ) {
                                                                echo '<div class="btn-group">';
                                                                echo '<a href="'.esc_url($dashboard_package).'?option=featured&post='.intval( $post_id ).'" class="btn pay-btn">' . esc_html__('Set as Featured', 'houzez') . '</a>';
                                                                echo '</div>';
                                                            }

                                                            if ( $paid_submission_type == 'membership' ) {
                                                                echo '<div class="btn-group">';
                                                                echo '<a href="'.esc_url($dashboard_package).'?option=week&post='.intval( $post_id ).'" class="btn btn-primary">' . esc_html__('Property of the week', 'houzez') . '</a>';
                                                                echo '</div>';
                                                            }
                                                            
                                                        }

                                                        if ( $paid_submission_type == 'membership' && $prop_featured == 1 ) {
                                                            echo '<div class="btn-group">';
                                                            echo '<a href="#" data-proptype="membership" data-propid="'.intval( $post_id ).'" class="remove-prop-featured btn pay-btn">' . esc_html__('Remove From Featured', 'houzez') . '</a>';
                                                            echo '</div>';
                                                        }

                                                        if ( $paid_submission_type == 'membership' && $prop_week == 1 ) {
                                                            echo '<div class="btn-group">';
                                                            echo '<a href="#" data-propid="'. intval( $post_id ) .'" class="remove-prop-week btn btn-primary">' . esc_html__('Remove From Week', 'houzez') . '</a>';
                                                            echo '</div>';
                                                        }

                                                        if( $property_status_text == 'expired' && $paid_submission_type == 'membership' ) {
                                                            echo '<div class="btn-group">';
                                                                echo '<a href="#" data-propid="'.intval( $post_id ).'" class="resend-for-approval btn pay-btn">' . esc_html__('Reactivate Listing', 'houzez') . '</a>';
                                                            echo '</div>';
                                                        }

                                                        //Paid Featured
                                                        if( $paid_submission_type == 'free_paid_listing' && $property_status_text == 'publish' ) {
                                                            echo '<div class="btn-group">';
                                                            if( $prop_featured != 1 ) {
                                                                echo '<a href="' . esc_url($payment_page_link_featured) . '" class="btn pay-btn">' . esc_html__('Upgrade to Featured', 'houzez') . '</a>';
                                                            }
                                                            echo '</div>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        }
                                    }

                                    ?>
                                </div>
                                <?php
                            } else {
                                print '<h4>'.$houzez_local['properties_not_found'].'</h4>';
                            }?>

                            <hr>
                            
                            <!--start Pagination-->
                            <?php houzez_pagination( $prop_qry->max_num_pages, $range = 2 ); ?>
                            <!--start Pagination-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>