<?php

global $post_meta_data;

$show_similer = houzez_option( 'houzez_similer_properties' );
$similer_type = houzez_option( 'houzez_similer_properties_type' );
$similer_view = houzez_option( 'houzez_similer_properties_view' );
$similer_count = houzez_option( 'houzez_similer_properties_count' );

if( $show_similer ) {

	$term_ids = Array ();
	$city_ids = Array ();
	$terms = get_the_terms(get_the_ID(), $similer_type, 'string');
	$prop_city = get_the_terms( get_the_ID(), 'property_city', 'string' );

	if ( !empty( $terms ) ) :

		$term_ids = wp_list_pluck($terms, 'term_id');

	endif;

	if ( !empty( $prop_city ) ) :

		$city_ids = wp_list_pluck( $prop_city, 'term_id' );

	endif;

	$tax_query = Array ();

	$tax_query[] = array(
		'taxonomy' => $similer_type,
		'field' => 'id',
		'terms' => $term_ids,
		'operator' => 'IN' //Or 'AND' or 'NOT IN'
	);


	$tax_count = count( $tax_query );

	if ($tax_count > 1) :

        $tax_query['relation'] = 'AND';

    endif;

	$second_query = array(
		'post_type' => 'property',
		'tax_query' => $tax_query,
		'posts_per_page' => $similer_count,
		'orderby' => 'rand',
		'post__not_in' => array(get_the_ID())
	);

	$wp_query = new WP_Query($second_query);

	if ($wp_query->have_posts()) : ?>

		<div class="property-similer">
			<div class="detail-title">
				<h2 class="title-left"><?php esc_html_e('Similar Properties', 'houzez'); ?></h2>
			</div>

				<div class="property-listing <?php echo esc_attr($similer_view); ?>">
					<div class="row">

						<?php
						while ($wp_query->have_posts()) : $wp_query->the_post();

							get_template_part('template-parts/property-for-listing');

						endwhile;
						?>

					</div>
				</div>
			<hr>
		</div>
		<?php
		endif;
		wp_reset_query();
}?>