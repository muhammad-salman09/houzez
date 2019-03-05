<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 16/12/15
 * Time: 6:21 PM
 */
global $post, $prop_images, $current_page_template, $prop_featured;
$post_meta_data     = get_post_custom($post->ID);
$prop_images        = get_post_meta( get_the_ID(), 'fave_property_images', false );
$prop_address       = get_post_meta( get_the_ID(), 'fave_property_map_address', true );
$prop_featured      = get_post_meta( get_the_ID(), 'fave_featured', true );
$listing_agent = houzez_get_property_agent( $post->ID );
$disable_agent = houzez_option('disable_agent');
$disable_date = houzez_option('disable_date');
$infobox_trigger = '';

$feat_class = '';
if( $prop_featured == 1 ) {
    $feat_class = "featured_prop";
}

if( is_page_template( 'template/property-listings-map.php' ) ) { $infobox_trigger = 'infobox_trigger'; }
?>

<div id="ID-<?php the_ID(); ?>" class="item-wrap infobox_trigger <?php echo esc_attr($feat_class); ?> item-<?php echo sanitize_title(get_the_title())?>">
    <div class="property-item table-list">
        <div class="table-cell">
            <div class="figure-block">
                <figure class="item-thumb">

                    <?php get_template_part( 'template-parts/featured-property' ); ?>

                    <div class="label-wrap label-right hide-on-list"> 
                        <?php get_template_part('template-parts/listing', 'status' ); ?>
                    </div>

                    <div class="price hide-on-list"><?php echo houzez_listing_price_v1(); ?></div>
                    <a class="hover-effect" href="<?php the_permalink() ?>">
                        <?php
                        if( has_post_thumbnail( $post->ID ) ) {
                            the_post_thumbnail( 'houzez-property-thumb-image' );
                        }else{
                            houzez_image_placeholder( 'houzez-property-thumb-image' );
                        }
                        ?>
                    </a>
                    <?php get_template_part( 'template-parts/share', 'favourite' ); ?>
                </figure>
            </div>
        </div>
        <div class="item-body table-cell">

            <div class="body-left table-cell">
                <div class="info-row">
                    <div class="label-wrap hide-on-grid">
                        <?php get_template_part('template-parts/listing', 'status' ); ?>
                    </div>
                    <?php

                    echo '<h2 class="property-title"><a href="'.esc_url( get_permalink() ).'">'. esc_attr( get_the_title() ). '</a></h2>';

                    if( !empty( $prop_address )) {
                        echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
                    }
                    ?>
                </div>
                <div class="info-row amenities hide-on-grid">
                    <?php echo houzez_listing_meta_v1(); ?>
                    <p><?php echo houzez_taxonomy_simple('property_type'); ?></p>
                </div>

                <?php if( $disable_agent != 0 || $disable_date != 0 ) { ?>
                <div class="info-row date hide-on-grid">
                    <?php if( !empty( $listing_agent ) && $disable_agent != 0 ) { ?>
                        <p class="prop-user-agent"><i class="fa fa-user"></i> <?php echo implode( ', ', $listing_agent ); ?></p>
                    <?php } ?>
                    <?php if( $disable_date != 0 ) { ?>
                        <p><i class="fa fa-calendar"></i><?php printf( __( '%s ago', 'houzez' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></p>
                    <?php } ?>
                </div>
                <?php } ?>

            </div>
            <div class="body-right table-cell hidden-gird-cell">

                <div class="info-row price"><?php echo houzez_listing_price_v1(); ?></div>

                <div class="info-row phone text-right">
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary"><?php esc_html_e( 'Details', 'houzez' ); ?> <i class="fa fa-angle-right fa-right"></i></a>
                </div>
            </div>

            <div class="table-list full-width hide-on-list">
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