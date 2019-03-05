<?php
/**
 * Single Property Header Details
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 7:45 PM
 */
global $post, $prop_address;
$print_property_button = houzez_option('print_property_button');
$disable_favorite = houzez_option('prop_detail_favorite');
$prop_detail_share = houzez_option('prop_detail_share');
?>
<div class="header-detail">
    <div class="header-left">
        <?php get_template_part( 'inc/breadcrumb' ); ?>
        <div class="table-list">
            <div class="table-cell"><h1><?php the_title(); ?></h1></div>
            <div class="table-cell hidden-sm hidden-xs">
                <span class="label-wrap">
                    <?php get_template_part('template-parts/listing', 'status' ); ?>
                </span>
            </div>
        </div>

        <?php
        if( !empty( $prop_address )) {
        echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
        } ?>
    </div>
    <div class="header-right">
        <ul class="actions">
            <?php if( $prop_detail_share != 0 ) { ?>
            <li class="share-btn">
                <?php get_template_part( 'template-parts/share' ); ?>
            </li>
            <?php } ?>
            <?php if( $disable_favorite != 0 ) { ?>
            <li class="fvrt-btn">
                <?php get_template_part( 'template-parts/favorite' ); ?>
            </li>
            <?php } ?>
            <?php if( $print_property_button != 0 ) { ?>
            <li class="print-btn">
                <span data-toggle="tooltip" data-placement="right" data-original-title="<?php esc_html_e('Print', 'houzez'); ?>"><i id="" class="fa fa-print houzez-print" data-propid="<?php echo esc_attr( $post->ID );?>"></i></span>
            </li>
            <?php } ?>
        </ul>
        <?php echo houzez_listing_price_v1(); ?>
    </div>
</div>