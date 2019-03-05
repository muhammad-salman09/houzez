<?php
/**
 * Single Property Media tabs
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 7:49 PM
 */
global $post, $property_streetView, $property_map, $map_in_section;
$disable_favorite = houzez_option('disable_favorite');
$print_property_button = houzez_option('print_property_button');
?>
<div class="media-tabs">
    <ul class="media-tabs-list">
        <li class="popup-trigger" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php esc_html_e( 'View Photos', 'houzez' ); ?>">
            <a href="#gallery" data-toggle="tab">
                <i class="fa fa-camera"></i>
            </a>
        </li>
        
        <?php if( $map_in_section != 1 ) { ?>
            <?php if( $property_map != 0 ) { ?>
            <li data-placement="bottom" data-toggle="tooltip" data-original-title="<?php esc_html_e('Map View', 'houzez');?>">
                <a href="#singlePropertyMap" id="houzezToggleMapView" data-toggle="tab">
                    <i class="fa fa-map"></i>
                </a>
            </li>
            <?php } ?>

            <?php if( ( $property_streetView != 'hide' && !empty( $property_streetView) ) && ( $property_map != 0 ) ) { ?>
            <li data-placement="bottom" data-toggle="tooltip" data-original-title="<?php esc_html_e('Street View', 'houzez');?> ">
                <a href="#street-map" data-toggle="tab">
                    <i class="fa fa-street-view"></i>
                </a>
            </li>
            <?php } ?>
        <?php } ?>

    </ul>
    <ul class="actions">
        <li class="share-btn">
            <?php get_template_part( 'template-parts/share' ); ?>
        </li>
        <?php if( $disable_favorite != 0 ) { ?>
        <li>
            <?php get_template_part( 'template-parts/favorite' ); ?>
        </li>
        <?php } ?>
        <?php if( $print_property_button != 0 ) { ?>
        <li class="print-btn">
            <span data-toggle="tooltip" data-placement="right" data-original-title="<?php esc_html_e('Print', 'houzez'); ?>"><i id="" class="fa fa-print houzez-print" data-propid="<?php echo esc_attr( $post->ID );?>"></i></span>
        </li>
        <?php } ?>
    </ul>
</div>