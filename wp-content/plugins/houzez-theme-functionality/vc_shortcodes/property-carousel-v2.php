<?php
/*-----------------------------------------------------------------------------------*/
/*	Property Carousel v2
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_prop_carousel_v2') ) {
	function houzez_prop_carousel_v2($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'prop_grid_style' => '',
			'property_type' => '',
			'property_status' => '',
			'property_state' => '',
			'property_city' => '',
			'property_area' => '',
			'property_label' => '',
			'featured_prop' => '',
			'property_ids' => '',
			'posts_limit' => '',
			'sort_by' => '',
			'offset' => '',
			'custom_title' => '',
			'all_btn' => '',
			'all_url' => '',
			'slides_to_scroll' => '',
			'slide_auto' => '',
			'slide_infinite' => '',
			'auto_speed' => '',
			'navigation' => '',
			'slide_dots' => ''
		), $atts));

		ob_start();
		global $post;

		$houzez_local = houzez_get_localization();

		$minify_js = houzez_option('minify_js');
		$js_minify_prefix = '';
		if( $minify_js != 0 ) {
			$js_minify_prefix = '.min';
		}

		//do the query
		$the_query = houzez_data_source::get_wp_query($atts); //by ref  do the query

		$token = wp_generate_password(5, false, false);
		wp_register_script('prop_caoursel_v2', get_template_directory_uri() . '/js/property-carousels-v2'.$js_minify_prefix.'.js', array('jquery'), HOUZEZ_THEME_VERSION, true);
		$local_args = array(
			'slide_auto' => $slide_auto,
			'auto_speed' => $auto_speed,
			'slide_dots' => $slide_dots,
			'slide_infinite' => $slide_infinite,
			'slides_to_scroll' => $slides_to_scroll
		);
		wp_localize_script('prop_caoursel_v2', 'prop_carousel_v2_' . $token, $local_args);
		wp_enqueue_script('prop_caoursel_v2');
		?>

		<div id="carousel-module-grid" class="houzez-module carousel-module">
			<div class="module-title-nav clearfix">
				<div>
					<h2><?php echo esc_attr($custom_title); ?></h2>
				</div>
				<div class="module-nav">
					<?php if ($navigation == 'true') { ?>
						<button
							class="btn btn-carousel btn-sm btn-prev-<?php echo esc_attr($token); ?>"><?php echo $houzez_local['prev_text']; ?></button>
						<button
							class="btn btn-carousel btn-sm btn-next-<?php echo esc_attr($token); ?>"><?php echo $houzez_local['next_text']; ?></button>
					<?php } ?>
					<?php if (!empty($all_url)) { ?>
						<a href="<?php echo esc_url($all_url); ?>"
						   class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
					<?php } ?>
				</div>
			</div>
			<div class="row grid-row">
				<div id="properties-carousel-v2-<?php echo esc_attr($token); ?>"
					 data-token="<?php echo esc_attr($token); ?>" class="carousel slide-animated slide-animated owl-carousel owl-theme">
					<?php
					if( $prop_grid_style == "v_2" ) {
						if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post(); ?>
							<div class="item">
								<?php get_template_part('template-parts/property-for-listing-v2-vc'); ?>
							</div>
						<?php endwhile; endif;
					} else {
						if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post(); ?>
							<div class="item">
								<?php get_template_part('template-parts/property-for-listing-vc'); ?>
							</div>
						<?php endwhile; endif;
					}
					?>
				</div>
			</div>
		</div>

		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;

	}

	add_shortcode('houzez-prop-carousel-v2', 'houzez_prop_carousel_v2');
}
?>