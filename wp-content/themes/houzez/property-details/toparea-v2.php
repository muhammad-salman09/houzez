<?php
/**
 * Property Top Area V2
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/01/16
 * Time: 2:46 PM
 */
global $post, $map_in_section, $property_map, $property_streetView, $prop_address, $prop_agent_email, $property_layout, $property_top_area;

$featured_img = houzez_get_image_url('full');
if( !empty($featured_img) ) {
    $featured_img = $featured_img[0];
} else {
    $featured_img = '';
}

$agent_display_option = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );
$prop_agent_num = $agent_num_call = $prop_agent_email = '';

if( $prop_agent_display != '-1' && $agent_display_option == 'agent_info' ) {
    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_agents', true );
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agent_email', true );

} elseif( $agent_display_option == 'agency_info' ) {
    $prop_agency_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $prop_agent_email = get_post_meta( $prop_agency_id, 'fave_agency_email', true );

} elseif ( $agent_display_option == 'author_info' ) {
    $prop_agent_email = get_the_author_meta( 'email' );
}
$print_property_button = houzez_option('print_property_button');
$prop_detail_share = houzez_option('prop_detail_share');
$disable_favorite = houzez_option('prop_detail_favorite');

$gallery_view = $map_view = $street_view = '';
$prop_default_active_tab = houzez_option('prop_default_active_tab');
if( $prop_default_active_tab == "image_gallery" ) {
    $gallery_view = 'in active';
} elseif( $prop_default_active_tab == "map_view" ) {
    $map_view = 'in active';
} elseif( $prop_default_active_tab == "street_view" ) {
    $street_view = 'in active';
} else {
    $gallery_view = 'in active';
}

$layout_class = '';
if( $property_layout == 'v2' ) {
    $layout_class = "no-margin";
}

?>
<section class="detail-top detail-top-full <?php echo esc_attr($property_top_area.' '.$layout_class);?>">
    <div class="detail-media">
    <div class="tab-content">

        <div id="gallery" class="tab-pane fade <?php echo esc_attr( $gallery_view );?>" style="background-image: url('<?php echo esc_url( $featured_img ); ?>')">
            <a href="#" class="popup-trigger popup-trigger-v2"></a>
            <div class="media-tabs-up">
                <div class="container">
                    <span class="label-wrap">
                        <?php get_template_part('template-parts/listing', 'status' ); ?>
                    </span>
                </div>
            </div>
            <div class="media-detail-down">
                <div class="container">
                    <div class="header-detail">
                        <div class="header-left">

                            <?php get_template_part('inc/breadcrumb'); ?>
                            <div class="table-cell"><h1><?php the_title(); ?></h1></div>

                            <div class="table-cell">
                                <ul class="actions">
                                    <?php if( $prop_detail_share != 0 ) { ?>
                                    <li class="share-btn"><?php get_template_part( 'template-parts/share' ); ?></li>
                                    <?php } ?>
                                    <?php if( $disable_favorite != 0 ) { ?>
                                    <li class="fvrt-btn"><?php get_template_part( 'template-parts/favorite' ); ?></li>
                                    <?php } ?>
                                    <?php if( $print_property_button != 0 ) { ?>
                                        <li class="print-btn">
                                            <span class="houzez-print" data-placement="right" data-toggle="tooltip" data-original-title="<?php esc_html_e('Print', 'houzez'); ?>" data-propid="<?php echo esc_attr( $post->ID );?>"><i class="fa fa-print"></i></span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <?php
                            if( !empty( $prop_address )) {
                                echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
                            } ?>
                        </div>
                        <div class="header-right"><?php echo houzez_listing_price_v1(); ?></div>
                    </div>
                </div>
            </div>

        </div>

        <?php if( $map_in_section != 1 ) { ?>
        <?php if( $property_map != 0 ) { ?>
            <div id="singlePropertyMap" class="tab-pane fade <?php echo esc_attr( $map_view );?>">
                <div class="mapPlaceholder">
                    <div class="loader-ripple">
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <?php wp_nonce_field('houzez_map_ajax_nonce', 'securityHouzezMap', true); ?>
            <input type="hidden" name="prop_id" id="prop_id" value="<?php echo esc_attr($post->ID); ?>" />
        <?php } ?>

        <?php if( $property_streetView != 'hide' ) { ?>
            <div id="street-map" class="tab-pane fade <?php echo esc_attr( $street_view );?>"></div>
        <?php } ?>
        <?php } ?>

    </div>
    <div class="media-tabs-up">
        <div class="container">
            <?php get_template_part( 'property-details/media', 'tabs' ); ?>
        </div>
    </div>
    </div>
</section>