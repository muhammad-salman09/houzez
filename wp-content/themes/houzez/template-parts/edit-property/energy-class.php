<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/04/18
 * Time: 5:45 PM
 */
global $prop_data, $prop_meta_data, $hide_add_prop_fields, $required_fields, $is_multi_steps;
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">

    <div class="add-title-tab">
        <h3><?php esc_html_e( 'Energy Class', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="energy_class"><?php echo esc_html__( 'Energy Class', 'houzez' ).houzez_required_field( $required_fields['energy_class'] ); ?></label>

                        <select name="energy_class" id="energy_class" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                            <option value=""><?php esc_html_e('Select Energy Class', 'houzez'); ?></option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'A+'); ?> value="A+">A+</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'A'); ?> value="A">A</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'B'); ?> value="B">B</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'C'); ?> value="C">C</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'D'); ?> value="D">D</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'E'); ?> value="E">E</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'F'); ?> value="F">F</option>
                            <option <?php selected($prop_meta_data['fave_energy_class'][0], 'G'); ?> value="G">G</option>              
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="energy_global_index"><?php echo esc_html__( 'Global energy performance index', 'houzez' ).houzez_required_field( $required_fields['energy_global_index'] ); ?></label>

                        <input type="text" id="energy_global_index" class="form-control" name="energy_global_index" value="<?php if (isset($prop_meta_data['fave_energy_global_index'])) {
                                           echo sanitize_text_field($prop_meta_data['fave_energy_global_index'][0]);
                                       } ?>" placeholder="<?php esc_html_e('Eg: 92.42 kWh / m²a', 'houzez'); ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="renewable_energy_global_index"><?php echo esc_html__( 'Renewable energy performance index', 'houzez' ).houzez_required_field( $required_fields['renewable_energy_global_index'] ); ?></label>

                        <input type="text" id="renewable_energy_global_index" class="form-control" value="<?php if (isset($prop_meta_data['fave_renewable_energy_global_index'])) {
                                           echo sanitize_text_field($prop_meta_data['fave_renewable_energy_global_index'][0]);
                                       } ?>" name="renewable_energy_global_index" placeholder="<?php esc_html_e('Eg: 0.00 kWh / m²a', 'houzez'); ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="energy_performance"><?php echo esc_html__( 'Energy performance of the building', 'houzez' ).houzez_required_field( $required_fields['energy_performance'] ); ?></label>

                        <input type="text" id="energy_performance" class="form-control" name="energy_performance" value="<?php if (isset($prop_meta_data['fave_energy_performance'])) {
                                           echo sanitize_text_field($prop_meta_data['fave_energy_performance'][0]);
                                       } ?>" placeholder="">
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>