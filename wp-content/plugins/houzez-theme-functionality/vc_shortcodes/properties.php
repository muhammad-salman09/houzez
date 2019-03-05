<?php
/*-----------------------------------------------------------------------------------*/
/*	Properties
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_properties') ) {
	function houzez_properties($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'prop_grid_style' => '',
			'module_type' => '',
			'property_type' => '',
			'property_status' => '',
			'property_state' => '',
			'property_city' => '',
			'property_area' => '',
			'property_label' => '',
			'houzez_user_role' => '',
			'featured_prop' => '',
			'posts_limit' => '',
			'sort_by' => '',
			'offset' => ''
		), $atts));

		ob_start();
		global $paged;
		if (is_front_page()) {
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		}

		if( $module_type == "grid_3_cols" ) {
			$css_classes = "grid-view grid-view-3-col";
		} elseif( $module_type == "grid_2_cols" ) {
			$css_classes = "grid-view";
		} elseif( $module_type == "list" ) {
			$css_classes = "list-view";
		} else {
			$css_classes = "grid-view grid-view-3-col";
		}

		//do the query
		$the_query = houzez_data_source::get_wp_query($atts, $paged); //by ref  do the query
		?>
		<div id="properties_module_section" class="houzez-module property-item-module">
			<div id="properties_module_container">
				<div id="module_properties" class="property-listing <?php echo esc_attr($css_classes);?>">

					<?php
					if( $prop_grid_style == "v_2" ) {
						if ($the_query->have_posts()) :
							while ($the_query->have_posts()) : $the_query->the_post();

								get_template_part('template-parts/property-for-listing-v2');

							endwhile;
							wp_reset_postdata();
						else:
							get_template_part('template-parts/property', 'none');
						endif;
					} else {
						if ($the_query->have_posts()) :
							while ($the_query->have_posts()) : $the_query->the_post();

								get_template_part('template-parts/property-for-listing');

							endwhile;
							wp_reset_postdata();
						else:
							get_template_part('template-parts/property', 'none');
						endif;
					}
					?>

				</div>
				<!-- end container-content -->
			</div>
			<div class="clearfix"></div>
			<div id="fave-pagination-loadmore" class="pagination-wrap fave-load-more">
                <div class="pagination">
                    <a 
                    data-paged="2" 
                    data-prop-limit="<?php esc_attr_e($posts_limit); ?>" 
                    data-grid-style="<?php esc_attr_e($prop_grid_style); ?>" 
                    data-type="<?php esc_attr_e($property_type); ?>" 
                    data-status="<?php esc_attr_e($property_status); ?>" 
                    data-state="<?php esc_attr_e($property_state); ?>" 
                    data-city="<?php esc_attr_e($property_city); ?>" 
                    data-area="<?php esc_attr_e($property_area); ?>" 
                    data-label="<?php esc_attr_e($property_label); ?>" 
                    data-user-role="<?php esc_attr_e($houzez_user_role); ?>" 
                    data-featured-prop="<?php esc_attr_e($featured_prop); ?>" 
                    data-offset="<?php esc_attr_e($offset); ?>"
                    data-sortby="<?php esc_attr_e($sort_by); ?>"
                    href="#">
                    	<?php esc_html_e('Load More', 'houzez'); ?>		
                    </a>               
                </div>
            </div>
		</div>

		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;

	}

	add_shortcode('houzez-properties', 'houzez_properties');
}
?>