<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/01/16
 * Time: 2:06 PM
 */
global $post, $prop_address;
$lightbox_agent_contact = houzez_option( 'lightbox_agent_cotnact' );
$enableDisable_agent_forms = houzez_option('agent_forms');

$lightbox_logo = houzez_option( 'lightbox_logo', false, 'url' );
$disable_favorite = houzez_option('disable_favorite');
$prop_gallery_images = get_post_meta( get_the_ID(), 'fave_property_images', false );

$agent_display_option = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );

$prop_agent_email = '';

if( $prop_agent_display != '-1' && $agent_display_option == 'agent_info' ) {
    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_agents', true );
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agent_email', true );

} elseif( $agent_display_option == 'agency_info' ) {
    $prop_agency_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $prop_agent_email = get_post_meta( $prop_agency_id, 'fave_agency_email', true );

} elseif ( $agent_display_option == 'author_info' ) {
    $prop_agent_email = get_the_author_meta( 'email' );
}
?>
<div id="lightbox-popup-main" class="fade">
    <div class="lightbox-popup">
        <div class="popup-inner">
            <div class="lightbox-left">

                <div class="lightbox-header">
                    <div class="header-title">
                        <p>
                            <?php if( !empty( $lightbox_logo ) ) { ?>
                            <span>
                                <img src="<?php echo esc_url( $lightbox_logo ); ?>" alt="<?php the_title(); ?>" width="86" height="13">
                            </span>
                            <?php } ?>
                            <span class="hidden-xs">
                            <?php the_title(); ?>
                            <?php if( !empty($prop_address) ) {  echo '- '. esc_attr( $prop_address ); } ?>
                            </span>
                        </p>
                    </div>
                    <div class="header-actions">
                        <ul class="actions">
                            <li class="share-btn">
                                <?php get_template_part( 'template-parts/share' ); ?>
                            </li>
                            <?php if( $disable_favorite != 0 ) { ?>
                            <li class="favt-btn">
                                <span><?php get_template_part( 'template-parts/favorite' ); ?></span>
                            </li>
                            <?php } ?>
                            <?php if( !empty( $prop_agent_email ) && $enableDisable_agent_forms != 0 ) { ?>
                            <?php if( $lightbox_agent_contact != 0 ) { ?>
                            <li class="lightbox-expand visible-xs compress">
                                <span><i class="fa fa-envelope"></i></span>
                            </li>
                                <?php } ?>
                            <?php } ?>
                            <li class="lightbox-close">
                                <span><i class="fa fa-close"></i></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="gallery-area">
                    <div class="slider-placeholder">
                        <div class="loader-inner">

                            <span class="fa fa-spin fa-spinner"></span> <?php esc_html_e('Loading Slider...', 'houzez');?>

                        </div>
                    </div>
                    <?php if( !empty( $prop_agent_email ) && $enableDisable_agent_forms != 0 ) { ?>
                    <?php if( $lightbox_agent_contact != 0 ) { ?>
                    <div class="expand-icon lightbox-expand hidden-xs"></div>
                        <?php } ?>
                    <?php } ?>
                    <div class="gallery-inner">
                        <div class="lightbox-slide slide-animated owl-carousel owl-theme">
                            <?php if( !empty( $prop_gallery_images ) ) { ?>
                                <?php foreach( $prop_gallery_images as $img_id ): ?>
                                    <div class="item"> <?php echo wp_get_attachment_image( $img_id, 'houzez-imageSize1170_738' ); ?> </div>
                                <?php endforeach; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="lightbox-slide-nav visible-xs">
                        <button class="lightbox-arrow-left lightbox-arrow"><i class="fa fa-angle-left"></i></button>
                        <p class="lightbox-nav-title">
                            <?php the_title(); ?>
                            <?php if( !empty($prop_address) ) {  echo '- '. esc_attr( $prop_address ); } ?>
                        </p>
                        <button class="lightbox-arrow-right lightbox-arrow"><i class="fa fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
            <?php if( !empty( $prop_agent_email ) && $enableDisable_agent_forms != 0 ) { ?>
                <?php if( $lightbox_agent_contact != 0 ) { ?>
                    <div class="lightbox-right fade in">
                        <div class="lightbox-header hidden-xs">
                            <div class="header-title">
                                <p><?php echo houzez_listing_price(); ?></p>
                            </div>
                            <div class="header-actions">
                                <ul class="actions">
                                    <li class="lightbox-close">
                                        <span><i class="fa fa-close"></i></span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="agent-area">
                            <div class="form-small">
                                <?php get_template_part( 'property-details/agent', 'form-lightbox' ); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>