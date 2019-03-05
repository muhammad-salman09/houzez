<?php
/*-----------------------------------------------------------------------------------*/
/*	Module 1
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_grids') ) {
	function houzez_grids($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'houzez_grid_type' => '',
			'houzez_grid_from' => '',
			'houzez_show_child' => '',
			'orderby' 			=> '',
			'order' 			=> '',
			'houzez_hide_empty' => '',
			'no_of_terms' 		=> '',
			'property_type' => '',
			'property_status' => '',
			'property_area' => '',
			'property_state' => '',
			'property_city' => '',
			'property_label' => ''
		), $atts));

		ob_start();
		$module_type = '';
		$houzez_local = houzez_get_localization();

		$slugs = '';

		if( $houzez_grid_from == 'property_city' ) {
			$slugs = $property_city;

		} else if ( $houzez_grid_from == 'property_area' ) {
			$slugs = $property_area;

		} else if ( $houzez_grid_from == 'property_label' ) {
			$slugs = $property_label;

		} else if ( $houzez_grid_from == 'property_state' ) {
			$slugs = $property_state;

		} else if ( $houzez_grid_from == 'property_status' ) {
			$slugs = $property_status;

		} else {
			$slugs = $property_type;
		}

		if ($houzez_show_child == 1) {
			$houzez_show_child = '';
		}
		if ($houzez_grid_type == 'grid_v2') {
			$module_type = 'location-module-v2';
		}

		if( $houzez_grid_from == 'property_type' ) {
			$custom_link_for = 'fave_prop_type_custom_link';
		} else {
			$custom_link_for = 'fave_prop_taxonomy_custom_link';
		}
		?>
		<div id="location-module"
			 class="houzez-module location-module <?php echo esc_attr( $module_type ); ?> grid <?php echo esc_attr( $houzez_grid_type ); ?>">
			<div class="row">
				<?php
				$tax_name = $houzez_grid_from;
				$taxonomy = get_terms(array(
					'hide_empty' => $houzez_hide_empty,
					'parent' => $houzez_show_child,
					'slug' => houzez_traverse_comma_string($slugs),
					'number' => $no_of_terms,
					'orderby' => $orderby,
					'order' => $order,
					'taxonomy' => $tax_name,
				));
				$i = 0;
				$j = 0;
				if ( !is_wp_error( $taxonomy ) ) {
				
					foreach ($taxonomy as $term) {

						$i++;
						$j++;

						if ($houzez_grid_type == 'grid_v1') {
							if ($i == 1 || $i == 4) {
								$col = 'col-sm-4';
							} else {
								$col = 'col-sm-8';
							}
							if ($i == 4) {
								$i = 0;
							}
						} elseif ($houzez_grid_type == 'grid_v2') {
							$col = 'col-sm-4';
						}

						$term_img = get_tax_meta($term->term_id, 'fave_prop_type_image');
						$taxonomy_custom_link = get_tax_meta($term->term_id, $custom_link_for);

						if( !empty($taxonomy_custom_link) ) {
							$term_link = $taxonomy_custom_link;
						} else {
							$term_link = get_term_link($term, $tax_name);
						}

						?>
						<div class="<?php echo esc_attr($col); ?>">
							<div class="location-block" <?php if (!empty($term_img['src'])) {
								echo 'style="background-image: url(' . esc_url($term_img['src']) . ');"';
							} ?>>
								<a href="<?php echo esc_url($term_link); ?>">
									<div class="location-fig-caption">
										<h3 class="heading"><?php echo esc_attr($term->name); ?></h3>

										<p class="sub-heading">
											<?php echo esc_attr($term->count); ?>
											<?php
											if ($term->count < 2) {
												echo $houzez_local['property'];
											} else {
												echo $houzez_local['properties'];
											}
											?>
										</p>
									</div>
								</a>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;

	}

	add_shortcode('hz-grids', 'houzez_grids');
}
?>