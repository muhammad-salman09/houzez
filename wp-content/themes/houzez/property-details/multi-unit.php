<?php
/**
 * Multi Units / Sub Properties
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 14/07/16
 * Time: 11:13 AM
 */
global $enable_multi_units, $multi_units;
$multi_units_ids = get_post_meta(get_the_ID(), 'fave_multi_units_ids', true);
$listing_agent = houzez_get_property_agent( $post->ID );
$disable_agent = houzez_option('disable_agent');
$disable_date = houzez_option('disable_date');
$mu_price_postfix = '';
if( $enable_multi_units != 'disable' && !empty( $enable_multi_units ) ) {

    if(!empty($multi_units_ids)) {?>

    <div id="sub_property" class="detail-multi-properties detail-block target-block property-listing list-view">
            <div class="detail-title">
                <h2 class="title-left"><?php esc_html_e( 'Sub Listings', 'houzez' ); ?></h2>
            </div>
            <?php

                $ids = explode(',', $multi_units_ids);
                $args = array(
                    'post_type' => 'property',
                    'post__in' => $ids,
                    'posts_per_page' => -1,
                );
                $query = new WP_Query($args);

                if($query->have_posts()): while ($query->have_posts()): $query->the_post(); ?>


                <div class="item-wrap item-luxury-family-home">
                    <div class="property-item table-list">
                        <div class="table-cell">
                            <div class="figure-block">
                                <figure class="item-thumb">
                                    
                                    <a class="hover-effect" href="<?php echo esc_url( get_permalink(get_the_ID()) ); ?>">
                                        <?php
                                        if( has_post_thumbnail( $post->ID ) ) {
                                            the_post_thumbnail( 'houzez-property-thumb-image' );
                                        }else{
                                            houzez_image_placeholder( 'houzez-property-thumb-image' );
                                        }
                                        ?>                    
                                    </a>
                                </figure>
                            </div>
                        </div>
                        <div class="item-body table-cell">
                            <div class="body-left table-cell">
                                <div class="info-row">
                                    <h3 class="property-title"><a href="<?php echo esc_url( get_permalink(get_the_ID()) ); ?>"><?php the_title();?></a></h3>
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
                                <div class="info-row price">
                                    <div class="info-row price"><?php echo houzez_listing_price_v1(); ?></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                
                <?php endwhile; endif; wp_reset_query();?>

    </div>

    <?php } else if (!empty($multi_units)) {
        ?>
        <div id="sub_property" class="detail-multi-properties detail-block target-block">
            <div class="detail-title">
                <h2 class="title-left"><?php esc_html_e( 'Sub Listings', 'houzez' ); ?></h2>
            </div>
            <div class="table-wrapper">
                <table class="table  table-striped table-multi-properties">
                    <thead>
                    <tr>
                        <th><?php esc_html_e('Title', 'houzez'); ?></th>
                        <th><?php esc_html_e('Property Type', 'houzez'); ?></th>
                        <th><?php esc_html_e('Price', 'houzez'); ?></th>
                        <th><?php esc_html_e('Beds', 'houzez'); ?></th>
                        <th><?php esc_html_e('Baths', 'houzez'); ?></th>
                        <th><?php esc_html_e('Property Size', 'houzez'); ?></th>
                        <th><?php esc_html_e('Availability Date', 'houzez'); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach( $multi_units as $mu ):

                        if( !empty( $mu['fave_mu_price_postfix'] ) ) {
                            $mu_price_postfix = ' / '.$mu['fave_mu_price_postfix'];
                        }
                        ?>

                        <tr>
                        <td class="title blue" width="25%">
                            <p data-toggle="popover" data-content='
                                                <table class="table table-popover">
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Title', 'houzez'); ?></td>
                                                        <td><a><?php echo esc_attr( $mu['fave_mu_title'] ); ?></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Property Type', 'houzez'); ?></td>
                                                        <td><?php echo esc_attr( $mu['fave_mu_type'] ); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Price', 'houzez'); ?></td>
                                                        <td><?php echo houzez_get_property_price( $mu['fave_mu_price'] ).$mu_price_postfix; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Beds', 'houzez'); ?></td>
                                                        <td><?php echo esc_attr( $mu['fave_mu_beds'] ); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Baths', 'houzez'); ?></td>
                                                        <td><?php echo esc_attr( $mu['fave_mu_baths'] ); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Property Size', 'houzez'); ?></td>
                                                        <td><?php echo houzez_get_area_size($mu['fave_mu_size']).' '.houzez_get_size_unit( $mu['fave_mu_size_postfix'] ); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-popover-title"><?php esc_html_e('Availability Date', 'houzez'); ?></td>
                                                        <td><?php echo esc_attr( $mu['fave_mu_availability_date'] ); ?></td>
                                                    </tr>
                                                </table>
                                            '>
                                <?php echo esc_attr( $mu['fave_mu_title'] ); ?>
                            </p>
                        </td>
                        <td><?php echo esc_attr( $mu['fave_mu_type'] ); ?></td>
                        <td><?php echo houzez_get_property_price( $mu['fave_mu_price'] ).$mu_price_postfix; ?></td>
                        <td><?php echo esc_attr( $mu['fave_mu_beds'] ); ?></td>
                        <td><?php echo esc_attr( $mu['fave_mu_baths'] ); ?></td>
                        <td><?php echo houzez_get_area_size($mu['fave_mu_size']).' '.houzez_get_size_unit( $mu['fave_mu_size_postfix'] ); ?></td>
                        <td><?php echo esc_attr( $mu['fave_mu_availability_date'] ); ?></td>
                    </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
} ?>