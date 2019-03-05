<?php
/**
 * Template Name: Agencies List
 *
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 20/10/16
 * Time: 4:44 PM
 */
get_header();
global $post, $houzez_local;
$sticky_sidebar = houzez_option('sticky_sidebar');
$page_id = get_the_ID();
?>

<?php get_template_part( 'template-parts/page-title' ); ?>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar">
            <div id="content-area">

                <!--start featured property items-->
                <?php
                global $wp_query, $paged;
                if ( is_front_page()  ) {
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                }

                ?>
                <div class="agency-listing">

                    <?php
                    $number_of_agencies = houzez_option('num_of_agencies');
                    if(!$number_of_agencies){
                        $number_of_agencies = 9;
                    }

                    $qry_args = array(
                        'post_type' => 'houzez_agency',
                        'posts_per_page' => $number_of_agencies,
                        'paged' => $paged
                    );

                    $order = get_post_meta( $page_id, 'fave_agency_order', true );
                    $orderby = get_post_meta( $page_id, 'fave_agency_orderby', true );


                    if( !empty( $orderby ) && $orderby != 'none' ) {
                        $qry_args['orderby'] = $orderby;
                    }
                    if( !empty( $order ) ) {
                        $qry_args['order'] = $order;
                    }

                    $wp_query = new WP_Query( $qry_args );

                    if ( $wp_query->have_posts() ) :
                        while ( $wp_query->have_posts() ) : $wp_query->the_post();

                            $agency_address = get_post_meta( get_the_ID(), 'fave_agency_address', true );
                            $agency_mobile = get_post_meta( get_the_ID(), 'fave_agency_mobile', true );
                            $agency_phone = get_post_meta( get_the_ID(), 'fave_agency_phone', true );
                            $agency_fax = get_post_meta( get_the_ID(), 'fave_agency_fax', true );
                            $agency_email = get_post_meta( get_the_ID(), 'fave_agency_email', true );
                            $agency_licenses = get_post_meta( get_the_ID(), 'fave_agency_licenses', true );
                            $agency_location = get_post_meta( get_the_ID(), 'fave_agency_location', true );
                            $agency_location = explode(',', $agency_location);
                            $agency_latitude = $agency_location[0];
                            $agency_longitude = $agency_location[1];

                            $agency_mobile_call = str_replace(array('(',')',' ','-'),'', $agency_mobile);
                            $agency_phone_call = str_replace(array('(',')',' ','-'),'', $agency_phone);

                            $agency_facebook = get_post_meta( get_the_ID(), 'fave_agency_facebook', true );
                            $agency_twitter = get_post_meta( get_the_ID(), 'fave_agency_twitter', true );
                            $agency_linkedin = get_post_meta( get_the_ID(), 'fave_agency_linkedin', true );
                            $agency_googleplus = get_post_meta( get_the_ID(), 'fave_agency_googleplus', true );
                            $agency_pinterest = get_post_meta( get_the_ID(), 'fave_agency_pinterest', true );
                            $agency_instagram = get_post_meta( get_the_ID(), 'fave_agency_instagram', true );
                            $agency_vimeo = get_post_meta( get_the_ID(), 'fave_agency_vimeo', true );
                            $agency_youtube = get_post_meta( get_the_ID(), 'fave_agency_youtube', true );
                            $agency_website = get_post_meta( get_the_ID(), 'fave_agency_web', true );
                            $agency_name = get_the_title();


                            ?>

                            <div class="agency-block">
                                <div class="media">
                                    <div class="media-left">
                                        <figure>
                                            <a href="<?php the_permalink(); ?>">
                                            <?php
                                            if( has_post_thumbnail( $post->ID ) ) {
                                                the_post_thumbnail( 'houzez-property-thumb-image-v2' );
                                            }else{
                                                houzez_image_placeholder( 'houzez-property-thumb-image-v2' );
                                            }
                                            ?>
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="media-body">
                                        <div class="agency-body-left">
                                            <div class="agency-description">
                                                <h3> <a href="<?php the_permalink(); ?>"><?php echo esc_attr($agency_name);?></a> </h3>
                                                <?php if( !empty( $agency_address) ) { ?>
                                                    <h4 class="position"><?php echo esc_attr( $agency_address ); ?></h4>
                                                <?php } ?>

                                                <p><?php echo houzez_get_excerpt(15); ?></p>

                                            </div>
                                        </div>
                                        <div class="agency-body-right">
                                            <ul class="agency-social social-top">
                                                <?php if( !empty( $agency_facebook ) ) { ?>
                                                    <li><a class="btn-facebook" href="<?php echo esc_url( $agency_facebook ); ?>" target="_blank"><i class="fa fa-facebook-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_twitter ) ) { ?>
                                                    <li><a class="btn-twitter" href="<?php echo esc_url( $agency_twitter ); ?>" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_linkedin ) ) { ?>
                                                    <li><a class="btn-linkedin" href="<?php echo esc_url( $agency_linkedin ); ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_googleplus ) ) { ?>
                                                    <li><a class="btn-google-plus" href="<?php echo esc_url( $agency_googleplus ); ?>" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_youtube ) ) { ?>
                                                    <li><a class="btn-youtube" href="<?php echo esc_url( $agency_youtube ); ?>" target="_blank"><i class="fa fa-youtube-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_instagram ) ) { ?>
                                                    <li><a class="btn-instagram" href="<?php echo esc_url( $agency_instagram ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_pinterest ) ) { ?>
                                                    <li><a class="btn-pinterest" href="<?php echo esc_url( $agency_pinterest ); ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_vimeo ) ) { ?>
                                                    <li><a class="btn-vimeo" href="<?php echo esc_url( $agency_vimeo ); ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a></li>
                                                <?php } ?>
                                            </ul>
                                            <ul class="agency-contact">
                                                <?php if( !empty($agency_phone) ) { ?>
                                                    <li><span><?php echo $houzez_local['office']; ?></span> <a href="tel:<?php echo esc_attr( $agency_phone_call ); ?>"><?php echo esc_attr( $agency_phone ); ?></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_mobile ) ) { ?>
                                                    <li><span><?php echo $houzez_local['mobile']; ?></span> <a href="tel:<?php echo esc_attr( $agency_mobile_call ); ?>"><?php echo esc_attr( $agency_mobile ); ?></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_fax ) ) { ?>
                                                    <li><span><?php echo $houzez_local['fax']; ?></span> <a><?php echo esc_attr( $agency_fax ); ?></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_email ) ) { ?>
                                                    <li class="email"><span><?php echo $houzez_local['email']; ?></span> <a href="mailto:<?php echo esc_attr( $agency_email ); ?>"><?php echo esc_attr( $agency_email ); ?></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_website ) ) { ?>
                                                    <li><span><?php echo $houzez_local['website']; ?></span> <a target="_blank" href="<?php echo esc_url( $agency_website ); ?>"><?php echo esc_url( $agency_website ); ?></a></li>
                                                <?php } ?>
                                            </ul>
                                            <ul class="agency-social  social-bottom">
                                                <?php if( !empty( $agency_facebook ) ) { ?>
                                                    <li><a class="btn-facebook" href="<?php echo esc_url( $agency_facebook ); ?>" target="_blank"><i class="fa fa-facebook-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_twitter ) ) { ?>
                                                    <li><a class="btn-twitter" href="<?php echo esc_url( $agency_twitter ); ?>" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_linkedin ) ) { ?>
                                                    <li><a class="btn-linkedin" href="<?php echo esc_url( $agency_linkedin ); ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_googleplus ) ) { ?>
                                                    <li><a class="btn-google-plus" href="<?php echo esc_url( $agency_googleplus ); ?>" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_youtube ) ) { ?>
                                                    <li><a class="btn-youtube" href="<?php echo esc_url( $agency_youtube ); ?>" target="_blank"><i class="fa fa-youtube-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_instagram ) ) { ?>
                                                    <li><a class="btn-instagram" href="<?php echo esc_url( $agency_instagram ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_pinterest ) ) { ?>
                                                    <li><a class="btn-pinterest" href="<?php echo esc_url( $agency_pinterest ); ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a></li>
                                                <?php } ?>

                                                <?php if( !empty( $agency_vimeo ) ) { ?>
                                                    <li><a class="btn-vimeo" href="<?php echo esc_url( $agency_vimeo ); ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        //wp_reset_postdata();
                    else:
                        esc_html_e('No record found', 'houzez');
                    endif;
                    ?>

                </div>

                <hr>

                <!--start Pagination-->
                <?php houzez_pagination( $wp_query->max_num_pages, $range = 2 ); wp_reset_query(); ?>
                <!--start Pagination-->

            </div>
        </div><!-- end container-content -->

        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-md-offset-0 col-sm-offset-3 container-sidebar <?php if( $sticky_sidebar['agency_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
            <?php get_sidebar('agencies'); ?>
        </div> <!-- end container-sidebar -->
    </div>


<?php get_footer(); ?>