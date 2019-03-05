<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 28/11/16
 * Time: 8:52 PM
 */
get_header();

$listing_view = houzez_option('taxonomy_posts_layout');

if( $listing_view == 'grid-view-3-col' ) {
    $listing_view_class = 'grid-view grid-view-3-col';
} else if( $listing_view == 'listing-style-3' ) {
    $listing_view_class = 'grid-view';
} else if( $listing_view == 'listing-style-2-grid-view' ) {
    $listing_view_class = 'grid-view listing-style-2-grid-view';
} else if( $listing_view == 'listing-style-2' ) {
    $listing_view_class = 'list-view listing-style-2';
} else {
    $listing_view_class = $listing_view;
}

// Title
$taxonomy_title = '';
$sticky_sidebar = houzez_option('sticky_sidebar');

$taxonomy_layout = houzez_option('taxonomy_layout');
$taxonomy_num_posts = houzez_option('taxonomy_num_posts');

if( $taxonomy_layout == 'no-sidebar' ) {
    $content_classes = 'col-lg-12 col-md-12 col-sm-12';
} else if( $taxonomy_layout == 'left-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar';
} else if( $taxonomy_layout == 'right-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area pull-left container-contentbar';
} else {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar';
}

$number_of_prop = $taxonomy_num_posts;
if(!$number_of_prop){
    $number_of_prop = 9;
}
?>

<?php get_template_part( 'template-parts/page-title' ); ?>

    <div class="row">
        <div class="<?php echo esc_attr($content_classes); ?>">
            <div id="content-area">

                <!--start property items-->
                <div class="property-listing <?php echo esc_attr($listing_view_class); ?>">
                    <div class="row">

                        <?php
                        global $wp_query;

                        if ( have_posts() ) :
                            while ( have_posts() ) : the_post();

                                if($listing_view == 'listing-style-3') {
                                    get_template_part('template-parts/property-for-listing-v3');

                                } else if($listing_view == 'listing-style-2') {
                                    get_template_part('template-parts/property-for-listing', 'v2');

                                } else {     
                                    get_template_part('template-parts/property-for-listing');
                                }

                            endwhile;
                            wp_reset_postdata();
                        else:
                            ?>
                            <h4><?php esc_html_e('Sorry No Result Found', 'houzez') ?></h4>
                            <?php
                        endif;
                        ?>

                    </div>
                </div>
                <!--end property items-->

                <hr>

                <!--start Pagination-->
                <?php houzez_pagination( $wp_query->max_num_pages, $range = 2 ); ?>
                <!--start Pagination-->

            </div>
        </div><!-- end container-content -->

        <?php if( $taxonomy_layout != 'no-sidebar' ) { ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-md-offset-0 col-sm-offset-3 container-sidebar <?php if( $sticky_sidebar['property_listings'] != 0 ){ echo 'houzez_sticky'; }?>">
                <?php get_sidebar('property'); ?>
            </div> <!-- end container-sidebar -->
        <?php } ?>

    </div>

<?php get_footer(); ?>