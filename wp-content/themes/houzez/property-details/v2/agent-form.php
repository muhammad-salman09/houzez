<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 27/09/16
 * Time: 5:04 PM
 * Since v1.4.0
 */
global $post, $current_user, $all_meta_for_user;
$agent_display_option = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );
$enable_contact_form_7_prop_detail = houzez_option('enable_contact_form_7_prop_detail');
$contact_form_agent_bottom = houzez_option('contact_form_agent_bottom');
$enableDisable_agent_forms = houzez_option('agent_forms');
$enable_direct_messages = houzez_option('enable_direct_messages');
$is_single_agent = true;
$listing_agent = '';
$prop_agent_email = '';

$agent_forms_terms = houzez_option('agent_forms_terms');
$agent_forms_terms_text = houzez_option('agent_forms_terms_text');

if( $prop_agent_display != '-1' && $agent_display_option == 'agent_info' ) {

    $prop_agent_ids = get_post_meta( get_the_ID(), 'fave_agents' );
    $prop_agent_ids = array_filter( $prop_agent_ids, function($v){
        return ( $v > 0 );
    });
    // remove duplicated ids
    $prop_agent_ids = array_unique( $prop_agent_ids );

    $agents_count = count( $prop_agent_ids );

    if ( $agents_count > 1 ) :
        $is_single_agent = false;
    endif;

    foreach ( $prop_agent_ids as $agent ) :

        if ( 0 < intval( $agent ) ) :

            $agent_args = array();
            $agent_args[ 'agent_id' ] = intval( $agent );
            $agent_args[ 'agent_name' ] = get_the_title( $agent_args[ 'agent_id' ] );
            $agent_args[ 'agent_mobile' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_mobile', true );
            $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $agent_args[ 'agent_mobile' ]);
            $agent_args[ 'agent_phone' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_office_num', true );
            $prop_agent_email = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_email', true );
            $agent_args[ 'agent_email' ] = $prop_agent_email;
            $agent_args[ 'agent_skype' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_skype', true );
            $prop_agent_permalink = get_permalink($agent_args[ 'agent_id' ]);
            $agent_args[ 'link' ] = $prop_agent_permalink;
            $agent_args[ 'facebook' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_facebook', true );
            $agent_args[ 'twitter' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_twitter', true );
            $agent_args[ 'linkedin' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_linkedin', true );
            $agent_args[ 'googleplus' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_googleplus', true );
            $agent_args[ 'youtube' ] = get_post_meta( $agent_args[ 'agent_id' ], 'fave_agent_youtube', true );
            $thumb_id = get_post_thumbnail_id( $agent_args[ 'agent_id' ] );
            $thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
            $prop_agent_photo_url = $thumb_url_array[0];
            if( empty( $prop_agent_photo_url )) :
                $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
            else :
                $agent_args[ 'picture' ] = $prop_agent_photo_url;
            endif;
            $listing_agent .= houzez_get_agent_info_bottom_v2( $agent_args, 'agent_form', $is_single_agent );

        endif;

    endforeach;

} elseif( $agent_display_option == 'agency_info' ) {

    $agent_args = array();
    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $agent_args[ 'agent_id' ] = $prop_agent_id;
    $agent_args[ 'agent_phone' ] = get_post_meta( $prop_agent_id, 'fave_agency_phone', true );
    $prop_agent_mobile = get_post_meta( $prop_agent_id, 'fave_agency_mobile', true );
    $agent_args[ 'agent_mobile' ] = $prop_agent_mobile;
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agency_email', true );
    $agent_args[ 'agent_email' ] = $prop_agent_email;
    $agent_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', $prop_agent_mobile);
    $agent_args[ 'agent_name' ] = get_the_title( $prop_agent_id );
    $prop_agent_permalink = get_post_permalink( $prop_agent_id );
    $agent_args[ 'link' ] = $prop_agent_permalink;

    $agent_args[ 'agent_skype' ] = get_post_meta( $prop_agent_id, 'fave_agency_skype', true );
    $agent_args[ 'facebook' ] = get_post_meta( $prop_agent_id, 'fave_agency_facebook', true );
    $agent_args[ 'twitter' ] = get_post_meta( $prop_agent_id, 'fave_agency_twitter', true );
    $agent_args[ 'linkedin' ] = get_post_meta( $prop_agent_id, 'fave_agency_linkedin', true );
    $agent_args[ 'googleplus' ] = get_post_meta( $prop_agent_id, 'fave_agency_googleplus', true );
    $agent_args[ 'youtube' ] = get_post_meta( $prop_agent_id, 'fave_agency_youtube', true );

    $thumb_id = get_post_thumbnail_id( $prop_agent_id );
    $thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
    $prop_agent_photo_url = $thumb_url_array[0];

    if( empty( $prop_agent_photo_url )) :
        $agent_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
    else :
        $agent_args[ 'picture' ] = $prop_agent_photo_url;
    endif;
    $listing_agent .= houzez_get_agent_info_bottom_v2( $agent_args, 'agent_form', $is_single_agent );

} elseif( $agent_display_option == 'author_info' ) {

    $listing_agent = '';
    $author_args = array();
    $author_args[ 'agent_name' ] = get_the_author();
    $author_args[ 'agent_mobile' ] = get_the_author_meta( 'fave_author_mobile' );
    $author_args[ 'agent_mobile_call' ] = str_replace(array('(',')',' ','-'),'', get_the_author_meta( 'fave_author_mobile' ));
    $prop_agent_email = get_the_author_meta( 'email' );
    $author_args[ 'agent_email' ] = $prop_agent_email;
    $author_args[ 'agent_phone' ] = get_the_author_meta( 'fave_author_phone' );
    $author_args[ 'agent_skype' ] = get_the_author_meta( 'fave_author_skype' );
    $prop_agent_permalink = get_author_posts_url( get_the_author_meta( 'ID' ) );
    $author_args[ 'link' ] = $prop_agent_permalink;
    $author_args[ 'facebook' ] = get_the_author_meta( 'fave_author_facebook' );
    $author_args[ 'twitter' ] = get_the_author_meta( 'fave_author_twitter' );
    $author_args[ 'linkedin' ] = get_the_author_meta( 'fave_author_linkedin' );
    $author_args[ 'googleplus' ] = get_the_author_meta( 'fave_author_googleplus' );
    $author_args[ 'youtube' ] = get_the_author_meta( 'fave_author_youtube' );
    $prop_agent_photo_url = get_the_author_meta( 'fave_author_custom_picture' );
    if( empty( $prop_agent_photo_url )) {
        $author_args[ 'picture' ] = get_template_directory_uri().'/images/profile-avatar.png';
    } else {
        $author_args[ 'picture' ] = $prop_agent_photo_url;
    }
    $listing_agent .= houzez_get_agent_info_bottom_v2( $author_args, 'agent_form', true );
}

$agent_email = is_email($prop_agent_email);
$user_info = get_userdata(get_the_author_meta('ID'));
$user_role = implode(', ', $user_info->roles);
?>
<div class="detail-contact detail-block">
    <div class="detail-contact-inner">
        <div class="detail-title">
            <h2 class="title-left"> <?php esc_html_e('Contact Agent', 'houzez' ); ?> </h2>
        </div>

        <form method="post" action="#">
            <?php echo $listing_agent; ?>
            <div class="inquiry-form">
                <h3 class="inquiry-title"><?php esc_html_e( 'Enquire about this property', 'houzez' ); ?></h3>
                <?php
                    if( $enable_contact_form_7_prop_detail != 1 ) {
                    if( $is_single_agent == true && is_user_logged_in() && $enable_direct_messages != 0 ) {
                        ?>
                        <input type="hidden" name="start_thread_form_ajax"
                               value="<?php echo wp_create_nonce('start-thread-form-nonce'); ?>"/>
                        <input type="hidden" name="property_id" value="<?php echo $post->ID; ?>"/>
                        <input type="hidden" name="action" value="houzez_start_thread">
                        <div class="form-group">
                            <textarea class="form-control" name="message" rows="13" placeholder="<?php esc_html_e('Description', 'houzez'); ?>"><?php esc_html_e("Hello, I am interested in", "houzez"); ?> [<?php echo get_the_title(); ?>]</textarea>
                        </div>

                        <button class="start_thread_form btn btn-secondary btn-block"><?php esc_html_e('Request info', 'houzez'); ?></button>
                        <div class="form_messages"></div>
                        <?php
                    } else {
                    if ($agent_email) { ?>
                        <?php if ($is_single_agent == true) : ?>
                            <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="agent_contact_form_ajax"
                               value="<?php echo wp_create_nonce('agent-contact-form-nonce'); ?>"/>
                        <input type="hidden" name="property_permalink"
                               value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
                        <input type="hidden" name="property_title"
                               value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
                        <input type="hidden" name="action" value="houzez_agent_send_message">

                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" name="name" value="<?php echo $current_user->display_name; ?>" 
                                           placeholder="<?php esc_html_e('Your Name', 'houzez'); ?>" type="text">
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" name="phone" value="<?php if(isset($all_meta_for_user['fave_author_phone'][0])) { echo $all_meta_for_user['fave_author_phone'][0]; }?>" 
                                           placeholder="<?php esc_html_e('Phone', 'houzez'); ?>" type="text">
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" name="email" value="<?php echo $current_user->user_email; ?>" 
                                           placeholder="<?php esc_html_e('Email', 'houzez'); ?>" type="email">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                 <textarea class="form-control" name="message" rows="5" placeholder="<?php esc_html_e('Message', 'houzez'); ?>"><?php esc_html_e("Hello, I am interested in", "houzez"); ?> [<?php echo get_the_title(); ?>]</textarea>
                                </div>
                            </div>

                            <?php if($agent_forms_terms != 0) { ?>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input name="term_condition" type="checkbox">
                                            <?php echo $agent_forms_terms_text; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                
                            <div class="col-sm-12 col-xs-12">
                                <?php get_template_part('template-parts/google', 'reCaptcha'); ?>
                            </div>
                        </div>
                        <button class="agent_contact_form btn btn-secondary btn-block"><?php esc_html_e('Request info', 'houzez'); ?></button>

                        <?php if( $is_single_agent == true  && $enable_direct_messages != 0 ) { ?>
                            <button type="button" class="btn btn-secondary btn-trans btn-block" data-toggle="modal" data-target="#pop-login"> <?php esc_html_e('Send Direct Message', 'houzez'); ?> </button>
                        <?php } ?>
                        <div class="form_messages"></div>
                </form>
            <?php }
            }
            } else {
                if( !empty($contact_form_agent_bottom) ) {
                    echo do_shortcode($contact_form_agent_bottom);
                }
            }?>
            </div>
        </form>
    </div>
</div>