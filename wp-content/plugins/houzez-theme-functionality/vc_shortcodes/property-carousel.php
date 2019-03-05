<?php
/*-----------------------------------------------------------------------------------*/
/*	Properties carousel
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_prop_carousel') ) {
	function houzez_prop_carousel($atts, $content = null)
	{
		extract(shortcode_atts(array(
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
			'slides_meta_position' => 'caption-above',
			'slides_to_show' => '',
			'slides_to_scroll' => '',
			'slide_infinite' => '',
			'slide_auto' => '',
			'auto_speed' => '',
			'navigation' => '',
			'slide_dots' => ''
		), $atts));

		ob_start();

		$houzez_local = houzez_get_localization();

		$minify_js = houzez_option('minify_js');
		$js_minify_prefix = '';
		if( $minify_js != 0 ) {
			$js_minify_prefix = '.min';
		}
		
		//do the query
		$the_query = houzez_data_source::get_wp_query($atts); //by ref  do the query

		$token = wp_generate_password(5, false, false);
		wp_register_script('prop_caoursel', get_template_directory_uri() . '/js/property-carousels'.$js_minify_prefix.'.js', array('jquery'), HOUZEZ_THEME_VERSION, true);
		$local_args = array(
			'slides_to_show' => $slides_to_show,
			'slides_to_scroll' => $slides_to_scroll,
			'slide_auto' => $slide_auto,
			'auto_speed' => $auto_speed,
			'slide_dots' => $slide_dots,
			'slide_infinite' => $slide_infinite
		);
		wp_localize_script('prop_caoursel', 'prop_carousel_' . $token, $local_args);
		wp_enqueue_script('prop_caoursel');

		global $prop_images;
		?>

		<div id="carousel-module-4"
			 class="houzez-module carousel-thumbs-grid <?php echo esc_attr($slides_meta_position); ?> carousel-module">
			<!-- <div class="container"> -->

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
				<!--<div class="grid-row">-->
					<div id="properties-carousel-v1-<?php echo esc_attr($token); ?>"
							 data-token="<?php echo esc_attr($token); ?>" class="carousel carousel-column-<?php esc_attr_e($slides_to_show);?> slide-animated owl-carousel owl-theme">
							<?php
							if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();
								$prop_images = get_post_meta(get_the_ID(), 'fave_property_images', false);

								?>
								<div class="item">
									<div class="figure-block">
										<figure class="item-thumb">

											<?php get_template_part('template-parts/featured-property'); ?>

											<div class="label-wrap label-right hide-on-list">
							                    <?php get_template_part('template-parts/listing', 'status' ); ?>
							                </div>
							        

											<a href="<?php the_permalink(); ?>" class="hover-effect">
												<?php
												if (has_post_thumbnail()) {
													if ($slides_to_show == 1) {
														the_post_thumbnail('houzez-single-big-size');
													} else {
														the_post_thumbnail('houzez-image570_340');
													}

												} else {
													houzez_image_placeholder('houzez-image570_340');
												}
												?>
											</a>
											<?php get_template_part('template-parts/share', 'favourite'); ?>
											<figcaption class="thumb-caption">
												<div class="cap-price pull-left"><?php echo houzez_listing_price(); ?></div>
												<?php get_template_part('template-parts/share', 'favourite'); ?>
											</figcaption>
											<figcaption class="detail-above detail">
												<div class="fig-title clearfix">
													<h3 class="pull-left"><?php the_title(); ?></h3>
												</div>

												<ul class="list-inline">
													<li class="cap-price"><?php echo houzez_listing_price(); ?></li>
													<?php houzez_listing_meta_v2(); ?>
												</ul>
											</figcaption>
										</figure>
										<div class="detail-bottom detail">
											<h3><?php the_title(); ?></h3>
											<ul class="list-inline">
												<?php houzez_listing_meta_v2(); ?>
											</ul>
										</div>
									</div>
								</div>
							<?php endwhile; endif; ?>

						</div>
				<!--</div>-->
			<!-- </div> -->
		</div>

		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;

	}

	add_shortcode('houzez-prop-carousel', 'houzez_prop_carousel');
}
?>