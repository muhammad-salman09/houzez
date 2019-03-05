<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/01/16
 * Time: 5:46 PM
 */
global $hide_add_prop_fields, $required_fields, $is_multi_steps;
$year_built_calender = houzez_option('year_built_calender');
$auto_property_id = houzez_option('auto_property_id');
$area_prefix_default = houzez_option('area_prefix_default');
$area_prefix_changeable = houzez_option('area_prefix_changeable');

if( $area_prefix_default == 'SqFt' ) {
    $area_prefix_default = houzez_option('measurement_unit_sqft_text');
} elseif( $area_prefix_default == 'm²' ) {
    $area_prefix_default = houzez_option('measurement_unit_square_meter_text');
}

?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php esc_html_e('Property Details', 'houzez'); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="row">

                <?php
                if( $auto_property_id != 1 ) {
                    if ($hide_add_prop_fields['prop_id'] != 1) { ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="property_id"><?php echo esc_html__('Property ID', 'houzez').houzez_required_field( $required_fields['prop_id'] ); ?></label>
                                <input type="text" id="property_id"
                                       class="form-control" name="property_id"
                                       placeholder="<?php esc_html_e('Enter property ID', 'houzez'); ?>">
                            </div>
                        </div>
                    <?php }
                }?>

                <?php if( $hide_add_prop_fields['area_size'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_size"><?php echo esc_html__('Area Size ( Only digits )', 'houzez').houzez_required_field( $required_fields['area_size'] ); ?></label>
                        <input type="text" id="prop_size" class="form-control" name="prop_size" placeholder="<?php esc_html_e( 'Enter property area size', 'houzez' ); ?>" value="">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_size_prefix"><?php esc_html_e('Size Prefix', 'houzez'); ?></label>
                        <input type="text" id="prop_size_prefix" class="form-control" name="prop_size_prefix" <?php if( $area_prefix_changeable != 1 ){ echo 'readonly'; }?> value="<?php echo esc_html($area_prefix_default);?>">
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['land_area'] != 1 ) { ?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="prop_land_area"><?php echo esc_html__('Land Area ( Only digits )', 'houzez').houzez_required_field( $required_fields['land_area'] ); ?></label>
                            <input type="text" id="prop_land_area" class="form-control" name="prop_land_area" placeholder="<?php esc_html_e( 'Enter property land area size', 'houzez' ); ?>" value="">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="prop_land_area_prefix"><?php esc_html_e('Land Area Size Postfix', 'houzez'); ?></label>
                            <input type="text" <?php if( $area_prefix_changeable != 1 ){ echo 'readonly'; }?> id="prop_land_area_prefix" class="form-control" name="prop_land_area_prefix" value="<?php echo esc_html($area_prefix_default);?>">
                        </div>
                    </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['bedrooms'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_beds"><?php echo esc_html__('Bedrooms', 'houzez').houzez_required_field( $required_fields['bedrooms'] ); ?></label>
                        <input type="text" id="prop_beds" class="form-control" name="prop_beds" placeholder="<?php esc_html_e( 'Enter number of bedrooms', 'houzez' ); ?>" value="">
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['bathrooms'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_baths"><?php echo esc_html__('Bathrooms', 'houzez').houzez_required_field( $required_fields['bathrooms'] ); ?></label>
                        <input type="text" id="prop_baths" class="form-control" name="prop_baths" placeholder="<?php esc_html_e( 'Enter number of bathrooms', 'houzez' ); ?>" value="">
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['garages'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_garage"><?php echo esc_html__('Garages', 'houzez').houzez_required_field( $required_fields['garages'] ); ?></label>
                        <input type="text" id="prop_garage" class="form-control" name="prop_garage" placeholder="<?php esc_html_e( 'Enter number of garages', 'houzez' ); ?>" value="">
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['garage_size'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_garage_size"><?php esc_html_e('Garages Size', 'houzez'); ?></label>
                        <input type="text" id="prop_garage_size" class="form-control" name="prop_garage_size" placeholder="<?php esc_html_e( 'Enter garage size', 'houzez' ); ?>" value="">
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['year_built'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_year_built"><?php echo esc_html__('Year Built', 'houzez').houzez_required_field( $required_fields['year_built'] ); ?></label>
                        <?php if( $year_built_calender != 'no' ) { ?>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" id="prop_year_built" class="input_date form-control" name="prop_year_built" placeholder="<?php esc_html_e( 'Enter year built', 'houzez' ); ?>" value="">
                        </div>
                        <?php } else { ?>

                            <input type="text" id="prop_year_built" class="form-control" name="prop_year_built" placeholder="<?php esc_html_e( 'Enter year built', 'houzez' ); ?>" value="">

                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <?php if( $hide_add_prop_fields['video_url'] != 1 ) { ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="prop_video_url"><?php esc_html_e( 'Video URL', 'houzez' ); ?></label>
                        <input class="form-control" name="prop_video_url" id="prop_video_url" placeholder="<?php esc_html_e( 'YouTube, Vimeo, SWF File and MOV File are supported', 'houzez' ); ?>">
                    </div>
                </div>
                <?php } ?>

                <?php
                if(class_exists('Houzez_Fields_Builder')) {
                $fields_array = Houzez_Fields_Builder::get_form_fields();
                
                    if(!empty($fields_array)) {
                        foreach ( $fields_array as $value ) {
                            $field_title = $value->label;
                            $field_name = $value->field_id;
                            $field_type = $value->type;
                            $field_placeholder = $value->placeholder;

                            if (function_exists('icl_translate') ){
                                $field_title = icl_translate('houzez_cfield', 'houzez_custom_field_'.sanitize_title($field_title), $field_title );

                                $field_placeholder = icl_translate('houzez_cfield', 'houzez_custom_field_'.sanitize_title($field_placeholder), $field_placeholder );
                                                  
                            }

                            if($field_type == 'select') {

                                $options = unserialize($value->fvalues);
                                $options_option = '';
                                foreach ($options as $key => $val) {
                                    $options_option .= '<option value="'.$key.'">'.$val.'</option>';
                                }

                                if($hide_add_prop_fields[$field_name] != 1) {
                                    echo '<div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="'.$field_name.'">'.$field_title.houzez_required_field( $required_fields[$field_name] ).'</label>
                                            <select '.houzez_required_field_2($required_fields[$field_name]).' name="'.$field_name.'" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                                <option selected="selected" value="">'.esc_html__('None', 'houzez').'</option>
                                                '.$options_option.'
                                            </select>
                                        </div>
                                    </div>';
                                }

                            } else {
                                
                                if($hide_add_prop_fields[$field_name] != 1) {
                                    echo '<div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="'.$field_name.'">'.$field_title.houzez_required_field( $required_fields[$field_name] ).'</label>
                                            <input '.houzez_required_field_2($required_fields[$field_name]).' class="form-control" name="'.$field_name.'" placeholder="'.$field_placeholder.'">
                                        </div>
                                    </div>';
                                }
                            }
                        } 
                    }
                }
                ?>
                            

            </div>
        </div>

        <?php if( $hide_add_prop_fields['additional_details'] != 1 ) { ?>
        <div class="add-tab-row">
            <h4><?php esc_html_e('Additional Features', 'houzez'); ?></h4>
            <table class="additional-block">
                <thead>
                <tr>
                    <td> </td>
                    <td><label><?php esc_html_e( 'Title', 'houzez' ); ?></label></td>
                    <td><label><?php esc_html_e( 'Value', 'houzez' ); ?></label></td>
                    <td> </td>
                </tr>
                </thead>
                <tbody id="houzez_additional_details_main">
                <tr>
                    <td class="action-field">
                        <span class="sort-additional-row"><i class="fa fa-navicon"></i></span>
                    </td>
                    <td class="field-title">
                        <input class="form-control" type="text" name="additional_features[0][fave_additional_feature_title]" id="fave_additional_feature_title_0" placeholder="<?php esc_html_e( 'Eg: Equipment', 'houzez' ); ?>" value="">
                    </td>
                    <td>
                        <input class="form-control" type="text" name="additional_features[0][fave_additional_feature_value]" id="fave_additional_feature_value_0" placeholder="<?php esc_html_e( 'Grill - Gas', 'houzez' ); ?>" value="">
                    </td>
                    <td class="action-field">
                        <span data-remove="0" class="remove-additional-row"><i class="fa fa-remove"></i></span>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td><button data-increment="0" class="add-additional-row"><i class="fa fa-plus"></i> <?php esc_html_e( 'Add New', 'houzez' ); ?></button></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>

        </div>
        <?php } ?>

    </div>
</div>
