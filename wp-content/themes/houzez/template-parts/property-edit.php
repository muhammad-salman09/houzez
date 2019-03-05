<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/10/15
 * Time: 3:45 PM
 */
global $current_user, $hide_add_prop_fields, $required_fields, $is_multi_steps;

wp_get_current_user();
$userID = $current_user->ID;
$enable_paid_submission = houzez_option('enable_paid_submission');
$select_packages_link = houzez_get_template_link('template/template-packages.php');
$remaining_listings = houzez_get_remaining_listings( $userID );
$show_submit_btn = houzez_option('submit_form_type');

if( is_page_template( 'template/submit_property.php' ) ) {

    $allowed_html = array(
        'i' => array(
            'class' => array()
        ),
        'strong' => array(),
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array()
        )
    );

    // Check Author
    global $current_user, $prop_data, $prop_meta_data;
    $current_user = wp_get_current_user();

    $edit_prop_id = intval( trim( $_GET['edit_property'] ) );
    $prop_data    = get_post( $edit_prop_id );

    if ( ! empty( $prop_data ) && ( $prop_data->post_type == 'property' ) ) {
        $prop_meta_data = get_post_custom( $prop_data->ID );

        if ( $prop_data->post_author == $current_user->ID ) {
        
        if ($prop_data->post_status == 'draft' && $enable_paid_submission == 'membership' && $remaining_listings != -1 && $remaining_listings < 1 && is_user_logged_in() ) {
        if (!houzez_user_has_membership($userID)) {
            print '<div class="user_package_status">
                    <h4>' . esc_html__('You don\'t have any package! You need to buy your package.', 'houzez') . '</h4>
                    <a class="btn btn-primary" href="' . $select_packages_link . '">' . esc_html__('Get Package', 'houzez') . '</a>
                    </div>';
        } else {
            print '<div class="user_package_status"><h4>' . esc_html__('Your current package doesn\'t let you publish more properties! You need to upgrade your membership.', 'houzez') . '</h4>
            <a class="btn btn-primary" href="' . $select_packages_link . '">' . esc_html__('Upgrade Package', 'houzez') . '</a>
            </div>';
        }
    } else { ?>

        <form id="submit_property_form" name="new_post" method="post" action="" enctype="multipart/form-data" class="update-frontend-property">
            <input type="hidden" name="draft_prop_id" value="<?php echo intval($edit_prop_id); ?>">
            <div class="validate-errors alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo wp_kses(__( '<strong>Error!</strong> Please fill out the following required fields.', 'houzez' ), $allowed_html); ?>
            </div>
            <div class="validate-errors-gal alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo wp_kses(__( '<strong>Error!</strong> Upload at least one image.', 'houzez' ), $allowed_html); ?>
            </div>

            <div class="submit-form-wrap">
            <?php
            $layout = houzez_option('property_form_sections');
            $layout = $layout['enabled'];

            if ($layout): foreach ($layout as $key=>$value) {

                switch($key) {

                    case 'description-price':
                        get_template_part( 'template-parts/edit-property/description-price' );
                        break;

                    case 'media':
                        get_template_part( 'template-parts/edit-property/media' );
                        break;

                    case 'details':
                        get_template_part( 'template-parts/edit-property/details' );
                        break;

                    case 'energy_class':
                        get_template_part('template-parts/edit-property/energy-class');
                        break;

                    case 'features':
                        get_template_part( 'template-parts/edit-property/features' );
                        break;

                    case 'location':
                        get_template_part( 'template-parts/edit-property/location' );
                        break;

                    case 'virtual_tour':
                        get_template_part('template-parts/edit-property/virtual-tour');
                        break;

                    case 'floorplans':
                        get_template_part('template-parts/edit-property/floorplans');
                        break;

                    case 'multi-units':
                        get_template_part('template-parts/edit-property/multi-units');
                        break;

                    case 'agent_info':
                        if(houzez_show_agent_box()) {
                            get_template_part('template-parts/edit-property/agent-info');
                        }
                        break;

                    case 'private_note':
                        get_template_part('template-parts/edit-property/private-note');
                        break;

                    case 'attachments':
                        get_template_part('template-parts/edit-property/attachments');
                        break;
                }

            }
            endif;
            ?>

            <?php if($show_submit_btn == 'one_step') { ?>
                <div class="account-block text-right">
                    <button id="add_new_property" type="submit" class="btn btn-primary"><?php esc_html_e('Submit Property', 'houzez'); ?></button>
                </div>
            <?php } ?>

            <?php wp_nonce_field('submit_property', 'property_nonce'); ?>
            <input type="hidden" name="action" value="update_property"/>
            <input type="hidden" name="prop_id" value="<?php echo intval( $prop_data->ID ); ?>"/>
            <input type="hidden" name="prop_featured" value="<?php if( isset( $prop_meta_data['fave_featured'] ) ) { echo sanitize_text_field( $prop_meta_data['fave_featured'][0] ); } ?>">
            <input type="hidden" name="prop_payment" value="<?php if( isset( $prop_meta_data['fave_payment_status'] ) ) { echo sanitize_text_field( $prop_meta_data['fave_payment_status'][0] ); } ?>"/>

            <?php if( get_post_status( $edit_prop_id ) == 'draft' ) {
                echo '<input type="hidden" name="houzez_draft" value="draft">';
            }?>

            <div class="steps-nav">
                <div class="btn-left btn-back action">
                    <button type="button" class="btn"><i class="fa fa-angle-left"></i></button> <span><?php esc_html_e('Back', 'houzez'); ?></span>
                </div>
                <div class="btn-right btn-next action">
                    <span><?php esc_html_e('Next', 'houzez'); ?></span> <button type="button" class="btn"><i class="fa fa-angle-right"></i></button>
                </div>
                <div class="btn-right action btn-submit btn-step-submit">
                    <span><?php esc_html_e('Update Property', 'houzez'); ?></span> <button <?php if ( !is_user_logged_in() ) { ?> disabled="disabled" <?php } ?> id="update_property" type="submit" class="btn"><i class="fa fa-angle-right"></i></button>
                </div>
            </div>
            </div>
        </form>

        <?php
    }
        } else {
            esc_html_e('You are not logged in or This property does not belong to logged in user.', 'homey');
        }
    } else {
        esc_html_e('This is not a valid request', 'homey');
    }
}


