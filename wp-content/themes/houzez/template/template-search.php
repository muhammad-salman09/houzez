<?php
/** Template Name: Advanced Search Results
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 20/01/16
 * Time: 6:44 PM
 */
get_header();
$listing_page_link = houzez_properties_listing_link();
$listing_view = houzez_option('search_result_posts_layout');
if( $listing_view == 'grid-view-3-col' ) {
    $listing_view_class = 'grid-view grid-view-3-col';
} else if( $listing_view == 'listing-style-3' ) {
    $listing_view_class = 'grid-view';
} else if( $listing_view == 'listing-style-2' ) {
    $listing_view_class = 'list-view listing-style-2';
} else if( $listing_view == 'listing-style-2-grid-view' ) {
    $listing_view_class = 'grid-view listing-style-2-grid-view';
} else if( $listing_view == 'listing-style-2-grid-view-3-col' ) {
    $listing_view_class = 'grid-view grid-view-3-col listing-style-2-grid-view';
} else {
    $listing_view_class = $listing_view;
}

global $houzez_local, $wp_query, $paged, $post, $search_qry, $current_page_template;
if ( is_front_page()  ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}
$fave_prop_no = houzez_option('search_num_posts');
$show_featured_on_top = houzez_option('show_featured_on_top');
$enable_disable_save_search = houzez_option('enable_disable_save_search');
$search_result_layout = houzez_option('search_result_layout');
$sticky_sidebar = houzez_option('sticky_sidebar');
$current_page_template = get_post_meta( $post->ID, '_wp_page_template', true );

$number_of_prop = $fave_prop_no;
if(!$number_of_prop){
    $number_of_prop = 9;
}
$style_3_full_classes = '';
if( $search_result_layout == 'no-sidebar' && $listing_view == 'listing-style-3') {
    $style_3_full_classes = ' grid-view-3-col';
}

if( $search_result_layout == 'no-sidebar' ) {
    $content_classes = 'col-lg-12 col-md-12 col-sm-12';
} else if( $search_result_layout == 'left-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar';
} else if( $search_result_layout == 'right-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area pull-left container-contentbar';
} else {
    $content_classes = 'col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar';
}

$search_qry = array(
    'post_type' => 'property',
    'posts_per_page' => $number_of_prop,
    'paged' => $paged,
    'post_status' => 'publish'
);
$sortby = houzez_option('search_default_order');
if($show_featured_on_top != 0 ) {
    $sortby = '';
}
if( isset( $_GET['sortby'] ) ) {
    $sortby = $_GET['sortby'];
}

$active = "";
$search_qry = apply_filters( 'houzez_search_parameters_2', $search_qry );

$search_qry = houzez_prop_sort ( $search_qry );
$wp_query = new WP_Query( $search_qry );
?>

<?php get_template_part('template-parts/page', 'title'); ?>

<div class="row">
    <div class="<?php echo esc_attr($content_classes); ?>">
        <div id="content-area">

            <!--start list tabs-->
            <div class="table-list full-width">
                <?php if( $enable_disable_save_search != 0 ) { ?>
                <div class="tabs table-cell v-align-top">
                    <p><?php echo $wp_query->found_posts.' '.esc_html__('Results', 'houzez').' - '.$houzez_local['save_search'];?></p>
                </div>
                <?php } else { ?>
                        <div class="tabs table-cell v-align-top">
                            <p><?php echo $wp_query->found_posts.' '.esc_html__('Results', 'houzez');?></p>
                        </div>
                <?php } ?>

                <div class="sort-tab table-cell text-right v-align-top">
                    <p>
                    <?php echo $houzez_local['sort_by']; ?>
                    <select id="sort_properties" class="selectpicker bs-select-hidden" title="" data-live-search-style="begins" data-live-search="false">
                        <option value=""><?php echo $houzez_local['default_order']; ?></option>
                        <option <?php if( $sortby == 'a_price' ) { echo "selected"; } ?> value="a_price"><?php echo $houzez_local['price_low_high']; ?></option>
                        <option <?php if( $sortby == 'd_price' ) { echo "selected"; } ?> value="d_price"><?php echo $houzez_local['price_high_low']; ?></option>
                        <option <?php if( $sortby == 'featured' ) { echo "selected"; } ?> value="featured"><?php echo $houzez_local['featured']; ?></option>
                        <option <?php if( $sortby == 'a_date' ) { echo "selected"; } ?> value="a_date"><?php echo $houzez_local['date_old_new']; ?></option>
                        <option <?php if( $sortby == 'd_date' ) { echo "selected"; } ?> value="d_date"><?php echo $houzez_local['date_new_old']; ?></option>
                    </select>
                    </p>
                </div>
            </div>
            <!--end list tabs-->

            <?php
            if( $enable_disable_save_search != 0 ) {
                get_template_part('template-parts/save', 'search');
            }?>


            <!--start property items-->
            <div class="property-listing <?php echo esc_attr($listing_view_class); echo esc_attr($style_3_full_classes);?>">
                <div class="row">

                    <?php

                    if ( $wp_query->have_posts() ) :
                        while ( $wp_query->have_posts() ) : $wp_query->the_post();

                            if($listing_view == 'listing-style-3') {
                                get_template_part('template-parts/property-for-listing-v3');

                            } else if($listing_view == 'listing-style-2' || $listing_view == 'listing-style-2-grid-view' || $listing_view == 'listing-style-2-grid-view-3-col') {
                                get_template_part('template-parts/property-for-listing', 'v2');

                            } else {     
                                get_template_part('template-parts/property-for-listing');
                            }

                        endwhile;
                        wp_reset_postdata();
                    else:
                       get_template_part('template-parts/property', 'none');
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

    <?php if( $search_result_layout != 'no-sidebar' ) { ?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-md-offset-0 col-sm-offset-3 container-sidebar <?php if( $sticky_sidebar['search_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
        <aside id="sidebar" class="sidebar-white">
            <?php
            if( is_active_sidebar( 'search-sidebar' ) ) {
                dynamic_sidebar( 'search-sidebar' );
            }
            ?>
        </aside>
    </div> <!-- end container-sidebar -->
    <?php } ?>

</div>


<?php get_footer(); ?>