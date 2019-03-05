<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/02/16
 * Time: 12:36 AM
 */
global $post, $prop_images;
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
    <div class="property-item item-grid">
        <div class="figure-block">
            <figure class="item-thumb">
                <?php get_template_part( 'template-parts/featured-property' ); ?>

                <div class="label-wrap label-right hide-on-list">
                    <?php get_template_part('template-parts/listing', 'status' ); ?>
                </div>

                <div class="price hide-on-list">
                    <?php echo houzez_listing_price_v1(); ?>
                </div>
                <a class="hover-effect" href="<?php the_permalink() ?>">
                    <?php
                    if( has_post_thumbnail( ) ) {
                        the_post_thumbnail( 'houzez-property-thumb-image' );
                    }else{
                        houzez_image_placeholder( 'houzez-property-thumb-image' );
                    }
                    ?>
                </a>
                <?php get_template_part( 'template-parts/share', 'favourite' ); ?>
            </figure>
        </div>
        <div class="item-body">

            <div class="body-left">
                <div class="info-row">

                    <?php
                    echo '<h3 class="property-title"><a href="'.esc_url( get_permalink() ).'">'. esc_attr( get_the_title() ). '</a></h3>';

                    if( !empty( $prop_address )) {
                        echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
                    }
                    ?>
                </div>
                <div class="table-list full-width info-row">
                    <div class="cell">
                        <div class="info-row amenities">
                            <?php echo houzez_listing_meta_v1(); ?>
                            <p><?php echo houzez_taxonomy_simple('property_type'); ?></p>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="phone">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary"> <?php esc_html_e( 'Details', 'houzez' ); ?> <i class="fa fa-angle-right fa-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php if( $disable_agent != 0 || $disable_date != 0 ) { ?>
        <div class="item-foot date hide-on-list">
            <?php if( $disable_agent != 0 ) { ?>
                <div class="item-foot-left">
                    <?php if( !empty( $listing_agent ) ) { ?>
                        <p class="prop-user-agent"><i class="fa fa-user"></i> <?php echo implode( ', ', $listing_agent ); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if( $disable_date != 0 ) { ?>
                <div class="item-foot-right">
                    <p class="prop-date"><i class="fa fa-calendar"></i><?php printf( __( '%s ago', 'houzez' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
