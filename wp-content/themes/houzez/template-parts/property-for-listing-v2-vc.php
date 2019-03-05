<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 27/09/16
 * Time: 3:24 PM
 * Since v1.4.0
 */
global $post, $prop_images, $current_page_template;
$post_meta_data     = get_post_custom($post->ID);
$prop_images        = get_post_meta( get_the_ID(), 'fave_property_images', false );
$prop_address       = get_post_meta( get_the_ID(), 'fave_property_map_address', true );
$prop_featured      = get_post_meta( get_the_ID(), 'fave_featured', true );
$listing_agent      = houzez_get_property_agent( $post->ID );
$disable_agent = houzez_option('disable_agent');
$disable_date = houzez_option('disable_date');
$infobox_trigger    = '';
?>

<div id="ID-<?php the_ID(); ?>" class="item-wrap">
    <div class="property-item-v2 item-grid">
        <div class="figure-block">
            <figure class="item-thumb">

                <?php get_template_part( 'template-parts/featured-property' ); ?>

                <div class="label-wrap label-right">
                    <?php get_template_part('template-parts/listing', 'status' ); ?>
                </div>
                <a href="<?php the_permalink() ?>" class="hover-effect">
                    <?php
                    if( has_post_thumbnail( $post->ID ) ) {
                        the_post_thumbnail( 'houzez-property-thumb-image-v2' );
                    }else{
                        houzez_image_placeholder( 'houzez-property-thumb-image-v2' );
                    }
                    ?>
                </a>
                <div class="item-price-block hide-on-list">
                    <?php echo houzez_listing_price_v1(); ?>
                </div>
                <?php get_template_part( 'template-parts/share', 'favourite' ); ?>
            </figure>
        </div>
        <div class="item-body">
            <div class="item-body-top">
                <div class="item-title">
                    <?php
                    echo '<h2 class="property-title"><a href="'.esc_url( get_permalink() ).'">'. esc_attr( get_the_title() ). '</a></h2>';

                    if( !empty( $prop_address )) {
                        echo '<address class="property-location">'.esc_attr( $prop_address ).'</address>';
                    }
                    ?>
                </div>
                <div class="item-price-block hide-on-grid">
                    <?php echo houzez_listing_price_v1(); ?>
                </div>
            </div>
            <div class="item-body-bottom">

                <?php echo houzez_listing_meta_v3(); ?>

                <?php if( $disable_agent != 0 || $disable_date != 0 ) { ?>
                    <ul class="item-date">
                        <?php if( !empty( $listing_agent ) && $disable_agent != 0 ) { ?>
                            <li class="prop-user-agent"><i class="fa fa-user"></i> <?php echo implode( ', ', $listing_agent ); ?></li>
                        <?php } ?>
                        <?php if( $disable_date != 0 ) { ?>
                            <li class="prop-date"><i class="fa fa-calendar"></i><?php printf( __( '%s ago', 'houzez' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>