<?php
/** Template Name: Advanced Search Results
 */
get_header();

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

if (isset($_GET['lifestyle']) && $_GET['lifestyle'] != '') {
    $lifestyle = array(
        'taxonomy' => 'property_lifestyle',
        'field' => 'slug',
        'terms' => $_GET['lifestyle']
    );

    array_push($search_qry['tax_query'], $lifestyle);
}

if (isset($_GET['region']) && $_GET['region'] != '') {
    $region = array(
        'taxonomy' => 'property_region',
        'field' => 'slug',
        'terms' => $_GET['region']
    );

    array_push($search_qry['tax_query'], $region);
}

$search_qry = houzez_prop_sort ( $search_qry );

$wp_query = new WP_Query( $search_qry );
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
            <div id="houzez-gmap-main" class="fave-screen-fix">
                <div id="mapViewHalfListings" class="map-half">
                </div>
                <div id="houzez-map-loading">
                    <div class="mapPlaceholder">
                        <div class="loader-ripple">
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
                <?php wp_nonce_field('houzez_header_map_ajax_nonce', 'securityHouzezHeaderMap', true); ?>

                <div  class="map-arrows-actions">
                    <button id="listing-mapzoomin" class="map-btn"><i class="fa fa-plus"></i> </button>
                    <button id="listing-mapzoomout" class="map-btn"><i class="fa fa-minus"></i></button>
                    <input type="text" id="google-map-search" placeholder="<?php esc_html_e('Google Map Search', 'houzez'); ?>" class="map-search">
                </div>
                <div class="map-next-prev-actions">
                    <ul class="dropdown-menu" aria-labelledby="houzez-gmap-view">
                        <li><a href="#" class="houzezMapType" data-maptype="roadmap"><span><?php esc_html_e( 'Roadmap', 'houzez' ); ?></span></a></li>
                        <li><a href="#" class="houzezMapType" data-maptype="satellite"><span><?php esc_html_e( 'Satelite', 'houzez' ); ?></span></a></li>
                        <li><a href="#" class="houzezMapType" data-maptype="hybrid"><span><?php esc_html_e( 'Hybrid', 'houzez' ); ?></span></a></li>
                        <li><a href="#" class="houzezMapType" data-maptype="terrain"><span><?php esc_html_e( 'Terrain', 'houzez' ); ?></span></a></li>
                    </ul>
                    <button id="houzez-gmap-view" class="map-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-globe"></i> <span><?php esc_html_e( 'View', 'houzez' ); ?></span></button>

                    <button id="houzez-gmap-prev" class="map-btn"><i class="fa fa-chevron-left"></i> <span><?php esc_html_e('Prev', 'houzez'); ?></span></button>
                    <button id="houzez-gmap-next" class="map-btn"><span><?php esc_html_e('Next', 'houzez'); ?></span> <i class="fa fa-chevron-right"></i></button>
                </div>
                <div  class="map-zoom-actions">
                    <?php if( $geo_location != 0 ) { ?>
                        <span id="houzez-gmap-location" class="map-btn"><i class="fa fa-map-marker"></i> <span><?php esc_html_e('My location', 'houzez'); ?></span></span>
                    <?php } ?>
                    <?php if( $map_fullscreen != 0 ) { ?>
                        <span id="houzez-gmap-full"  class="map-btn"><i class="fa fa-arrows-alt"></i> <span><?php esc_html_e('Fullscreen', 'houzez'); ?></span></span>
                    <?php } ?>
                </div>

            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
            <div class="module-half map-module-half fave-screen-fix <?php echo esc_attr($show_switch); ?>">
                <div class="property-listing <?php echo esc_attr($listing_view); ?>">
                    <div class="row">
                        <div id="houzez_ajax_container">
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

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>