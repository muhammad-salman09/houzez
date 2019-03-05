<?php
/**
 * Template Name: Property Listing Half Map
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/06/16
 * Time: 11:00 PM
 */
get_header();
global $post, $listings_tabs, $fave_featured_listing;
$listing_view = houzez_option('halfmap_listings_layout');
$show_switch = houzez_option('halfmap_listings_layout');
$listings_tabs = get_post_meta( $post->ID, 'fave_listings_tabs', true );
$listings_tab_1 = get_post_meta( $post->ID, 'fave_listings_tab_1', true );
$listings_tab_2 = get_post_meta( $post->ID, 'fave_listings_tab_2', true );
$fave_featured_listing = get_post_meta( $post->ID, 'fave_featured_listing', true );
$fave_featured_prop_no = get_post_meta( $post->ID, 'fave_featured_prop_no', true );
$fave_prop_no = get_post_meta( $post->ID, 'fave_prop_no', true );
$search_result_page = houzez_option('search_result_page');
$geo_location = houzez_option('geo_location');
$map_fullscreen = houzez_option('map_fullscreen');

if(isset($_GET['half_map_style'])) {
    $listing_view = $_GET['half_map_style'];
    $show_switch = $_GET['half_map_style'];
}

$listing_page_link = houzez_properties_listing_link();
$active_grid = $active_list = '';
if( $listing_view == 'grid-view' || $listing_view == 'grid-view-style3' ) {
    $listing_view = 'grid-view';
    $active_grid = 'active';
} else {
    $listing_view = 'list-view';
    $active_list = 'active';
}
$sortby = '';
if( isset($_GET['prop_featured']) && $_GET['prop_featured'] == 'no' ) {
    $fave_featured_listing = 'disable';
}

$sortby = get_post_meta($post->ID, 'fave_properties_sort_halfmap', true);
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
                <form autocomplete="off" method="get" id="half_map_search_form" class="save_search_form" action="#">
                <?php get_template_part('template-parts/advanced-search/half-map'); ?>
                <!--start latest listing module-->
                <div class="houzez-module">
                    <!--start list tabs-->
                    <div class="list-tabs table-list full-width">
                        <div class="tabs table-cell">
                            <h2 class="tabs-title"><span></span> <?php esc_html_e('Results', 'houzez');?></h2>
                        </div>
                        
                        <div class="sort-tab table-cell text-right">
                            <?php esc_html_e( 'Sort by:', 'houzez' ); ?>
                            <select id="houzez_sort_half_map" name="sort_half_map" class="selectpicker bs-select-hidden" title="" data-live-search-style="begins" data-live-search="false">
                                <option value=""><?php esc_html_e( 'Default Order', 'houzez' ); ?></option>
                                <option <?php if( $sortby == 'a_price' ) { echo "selected"; } ?> value="a_price"><?php esc_html_e( 'Price (Low to High)', 'houzez' ); ?></option>
                                <option <?php if( $sortby == 'd_price' ) { echo "selected"; } ?> value="d_price"><?php esc_html_e( 'Price (High to Low)', 'houzez' ); ?></option>
                                
                                <option <?php if( $sortby == 'featured_top' ) { echo "selected"; } ?> value="featured_top"><?php esc_html_e( 'Show Featured on Top', 'houzez' ); ?></option>
                                
                                <option <?php if( $sortby == 'a_date' ) { echo "selected"; } ?> value="a_date"><?php esc_html_e( 'Date Old to New', 'houzez' ); ?></option>
                                <option <?php if( $sortby == 'd_date' ) { echo "selected"; } ?> value="d_date"><?php esc_html_e( 'Date New to Old', 'houzez' ); ?></option>
                            </select>
                            <?php if( $show_switch != 'grid-view-style3') { ?>
                            <span class="view-btn btn-list <?php echo esc_attr($active_list);?>"><i class="fa fa-th-list"></i></span>
                            <span class="view-btn btn-grid <?php echo esc_attr($active_grid);?>"><i class="fa fa-th-large"></i></span>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <!--end list tabs-->
                    <div class="property-listing <?php echo esc_attr($listing_view); ?>">
                        <div class="row">
                            <div id="houzez_ajax_container">

                            </div>
                        </div>

                    </div>
                </div>
                <!--end latest listing module-->
                </form>

            </div>
        </div>
    </div>
</div>

</div><!-- #section Body -->
<?php wp_footer(); ?>

</body>
</html>