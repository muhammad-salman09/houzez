<?php
/**
 * Template Name: User Dashboard Favorite Properties
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/01/16
 * Time: 4:35 PM
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $houzez_local, $current_user;

wp_get_current_user();
$userID         = $current_user->ID;
$user_login     = $current_user->user_login;
$fav_ids = 'houzez_favorites-'.$userID;
$fav_ids = get_option( $fav_ids );
get_header();

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="my-property-listing">
                        <div class="row grid-row">

                            <?php
                            if( !empty( $fav_ids ) ) {
                                $args = array('post_type' => 'property', 'post__in' => $fav_ids, 'numberposts' => -1 );

                                $myposts = get_posts($args);
                                foreach ($myposts as $post) : setup_postdata($post);
                                    $prop_images = get_post_meta( get_the_ID(), 'fave_property_images', false );
                                    ?>
                                    <div class="item-wrap">
                                        <div class="media my-property">
                                            <div class="media-left">
                                                <div class="figure-block">
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
                                                    </figure>
                                                </div>
                                            </div>
                                            <div class="media-body media-middle">
                                                <div class="my-description">
                                                    <h4 class="my-heading">
                                                        <a href="<?php echo esc_url( get_permalink() ); ?>"> 
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h4>
                                                    <?php
                                                    if( !empty( $prop_address )) {
                                                        echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
                                                    }
                                                    ?>
                                                    <div class="status">
                                                        <p>
                                                            <span><strong><?php esc_html_e( 'Status:', 'houzez' ); ?></strong> <?php echo houzez_taxonomy_simple( 'property_status' ); ?></span>
                                                            <span><strong><?php esc_html_e( 'Price:', 'houzez' ); ?></strong> <?php echo houzez_listing_price(); ?></span>
                                                            <?php
                                                            $listing_area_size = houzez_get_listing_area_size( $post->ID );
                                                            if( !empty( $listing_area_size ) ) {
                                                                echo '<span>';
                                                                echo '<strong>'.houzez_get_listing_size_unit($post->ID) . ': </strong> ' . houzez_get_listing_area_size($post->ID);
                                                                echo '</span>';
                                                            }
                                                            ?>
                                                        </p>
                                                        <p><span><?php echo houzez_taxonomy_simple('property_type'); ?></span></p>
                                                    </div>
                                                </div>
                                                <div class="my-actions">
                                                    <div class="btn-group">
                                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo esc_html__('Details', 'houzez');?> <i class="fa fa-angle-right"></i></a>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a data-propid="<?php echo intval( $post->ID ); ?>" href="#" class="btn btn-action remove_fav"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                endforeach;
                                wp_reset_postdata();
                            } else {
                                echo '<div class="col-sm-12">';
                                echo $houzez_local['favorite_not_found'];
                                echo '</div>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>