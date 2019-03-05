
<?php $add_new = Houzez_Fields_Builder::field_add_link(); ?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e('Fields Builder', 'houzez-theme-functionality');?></h1>
	<a href="<?php echo esc_url($add_new);?>" class="page-title-action"><?php esc_html_e('Add New', 'houzez-theme-functionality');?></a>
	<hr class="wp-header-end">
	<br/>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-title column-primary desc field-column-1"><span><?php esc_html_e('Field Name', 'houzez-theme-functionality');?></span></span></a></th>
				<th scope="col" class="manage-column column-title column-primary desc field-column-2"><span><?php esc_html_e('Edit', 'houzez-theme-functionality');?></span></span></a></th>
				<th scope="col" class="manage-column column-title column-primary desc field-column-3"><span><?php esc_html_e('Delete', 'houzez-theme-functionality');?></span></span></a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$form_fields = Houzez_Fields_Builder::get_form_fields();

			if($form_fields) {
				foreach ( $form_fields as $data ) { 
					$edit_link = Houzez_Fields_Builder::field_edit_link( $data->id );
					$delete_link = Houzez_Fields_Builder::field_delete_link( $data->id );
					$label = stripslashes($data->label);
				
					if (function_exists('icl_translate') ){
		                $label = icl_translate('houzez_cfield', 'houzez_custom_field_'.sanitize_title($label), $label );
		                                  
		            }	
					?>
					
					<tr>
						<td>
							<?php echo esc_attr($label); ?>
						</td>
						<td>
							<a href="<?php echo esc_url($edit_link); ?>" class=""
								title="<?php esc_html_e( 'Edit field', 'houzez-theme-functionality' ); ?>"><i class="dashicons dashicons-edit"></i>
							</a>
						</td>
						<td>
							<a href="<?php echo esc_url($delete_link); ?>" class=""
								title="<?php esc_html_e( 'Delete field', 'houzez-theme-functionality' ); ?>"><i class="dashicons dashicons-trash" ></i>
							</a>
						</td>
					</tr>
					
					<?php		
				}
			}
			?>

		</tbody>
	</table>
</div>