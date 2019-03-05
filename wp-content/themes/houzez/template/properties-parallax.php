<?php
/**
 * Template Name: Property Listing Parallax
 */
get_header(); ?>

<style type="text/css">
.parallax-property-caption {
	background-color: rgba(255,255,255,1);
}
.parallax-property-caption h2 {
	color: #000;
}
.parallax-property-caption address  {
	color: #000;
}
.parallax-property-caption .item-price {
	color: #000;
}
.parallax-property-caption .price .item-sub-price {
	color: #000;
}
.parallax-property-caption .amenities {
	color: #000;	
}
</style>

<?php
global $wp_query, $paged, $post;
$fave_prop_no = get_post_meta( $post->ID, 'fave_prop_no_halfmap', true );


if(!$fave_prop_no){
    $posts_per_page  = 9;
} else {
    $posts_per_page = $fave_prop_no;
}
$latest_listing_args = array(
    'post_type' => 'property',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => 'publish'
);

$latest_listing_args = apply_filters( 'houzez_property_filter', $latest_listing_args );

$latest_listing_args = houzez_prop_sort ( $latest_listing_args );

$wp_query = new WP_Query( $latest_listing_args );

$i = 1;
if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) : $wp_query->the_post(); 

    $post_meta_data     = get_post_custom(get_the_ID());
	$prop_images        = get_post_meta( get_the_ID(), 'fave_property_images', false );
	$prop_address       = get_post_meta( get_the_ID(), 'fave_property_map_address', true );
	$prop_featured      = get_post_meta( get_the_ID(), 'fave_featured', true );

	$thumb_id = get_post_thumbnail_id( $post->ID );
	$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', false);
	$thumb_url = $thumb_url_array[0];
	
	$feat_class = '';
	if( $prop_featured == 1 ) {
	    $feat_class = "featured_prop";
	}

	if($i % 2 == 1 ) {
		$class_pos = 'left';
	} else {
		$class_pos = 'right';
	}
	$i++;
    ?>

        <a class="parallax-properties-wrap-link" href="<?php the_permalink(); ?>">
			<div class="parallax-properties-wrap">
				<div class="parallax-properties-media">
					<div class="parallax-properties-banner-parallax">
						<div class="parallax-properties-bg-wrap">
							<div class="parallax-properties-inner" data-parallax="scroll" data-image-src="<?php echo $thumb_url; ?>"></div>
						</div>
					</div>
					<div class="parallax-property-caption-wrap <?php echo esc_attr($class_pos);?>">
						<div class="parallax-property-caption">
							
							<div class="label-wrap">
								<?php get_template_part( 'template-parts/featured-property' ); ?>
								<?php get_template_part('template-parts/listing-status', 'parallax' ); ?>
							</div>

							<h2><?php the_title(); ?></h2>
							<?php
							if( !empty( $prop_address )) {
		                        echo '<address class="property-address">'.esc_attr( $prop_address ).'</address>';
		                    }
							?>

							<div class="price">
								<?php echo houzez_listing_price_v1(); ?>
							</div>

							<div class="info-row amenities">
								<?php echo houzez_listing_meta_v1_without_p(); ?>
								<span class="p-type"><?php echo houzez_taxonomy_simple('property_type'); ?></span>
							</div>
						</div>
					</div>

				</div>
			</div>
		</a>

<?php
    endwhile;
else:
    get_template_part('template-parts/property', 'none');
endif;
?>
<!-- <hr> -->
<!--start Pagination-->
<?php //houzez_pagination( $wp_query->max_num_pages, $range = 2 ); wp_reset_query(); ?>
<!--start Pagination-->

<?php get_footer(); ?>