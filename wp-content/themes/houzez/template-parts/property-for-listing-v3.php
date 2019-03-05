<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 05/04/17
 * Time: 3:10 PM
 */
global $post, $prop_images, $current_page_template, $houzez_local;
$post_meta_data     = get_post_custom($post->ID);
$prop_images        = get_post_meta( get_the_ID(), 'fave_property_images', false );
$prop_address       = get_post_meta( get_the_ID(), 'fave_property_map_address', true );
$prop_featured      = get_post_meta( get_the_ID(), 'fave_featured', true );
$listing_agent = houzez_get_property_agent( $post->ID );
$disable_agent = houzez_option('disable_agent');
$disable_date = houzez_option('disable_date');
$infobox_trigger = '';

if( is_page_template( 'template/property-listings-map.php' ) ) { $infobox_trigger = 'infobox_trigger'; }
?>
<div id="ID-<?php the_ID(); ?>" class="item-wrap infobox_trigger">
    <div class="property-item-grid property-item-grid-v3">
        <figure class="item-thumb">
            <a class="hover-effect" href="<?php the_permalink() ?>">
                <?php
                if( has_post_thumbnail( $post->ID ) ) {
                    the_post_thumbnail( 'houzez-property-thumb-image' );
                }else{
                    houzez_image_placeholder( 'houzez-property-thumb-image' );
                }
                ?>
            </a>
            <div class="label-wrap label-left">
                <?php if( $prop_featured == 1 ) { ?>
                    <span class="label label-success label-featured"><?php echo esc_html__( 'Featured', 'houzez' ); ?></span>
                <?php } ?>
                <?php get_template_part('template-parts/listing-label' ); ?>
            </div>
            <div class="price">
                <?php echo houzez_listing_price_v1(); ?>
            </div>

            <?php get_template_part( 'template-parts/share', 'favourite' ); ?>

            <div class="item-caption">
                <div class="label-wrap">
                    <?php get_template_part('template-parts/listing-status-for-style3' ); ?>
                </div>
                <?php echo '<h4 class="item-caption-title">'. esc_attr( get_the_title() ). '</h4>'; ?>
                <ul class="item-caption-list">
                    <?php houzez_listing_meta_v2(); ?>
                </ul>
            </div>
        </figure>
    </div>
</div>