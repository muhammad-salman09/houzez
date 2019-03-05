<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 20/01/16
 * Time: 6:31 PM
 */
global $status,
       $search_template,
       $type, $location,
       $searched_country,
       $state,
       $measurement_unit_adv_search,
       $area,
       $label,
       $adv_search_price_slider,
       $hide_advanced,
       $keyword_field_placeholder,
       $adv_show_hide,
       $houzez_local;

$mobile_menu_sticky = houzez_option('mobile-menu-sticky');

if( $mobile_menu_sticky != 1 ) {
    $mobile_search_sticky = houzez_option('mobile-search-sticky');
} else {
    $mobile_search_sticky = '0';
}
$checked = true;
$radius_unit = houzez_option('radius_unit');
$enable_radius_search = houzez_option('enable_radius_search');
$selected_radius = houzez_option('houzez_default_radius');
if( isset( $_GET['radius'] ) ) {
    $selected_radius = $_GET['radius'];
}

$state_city_area_dropdowns = houzez_option('state_city_area_dropdowns');
if( $state_city_area_dropdowns != 0 ) {
    $hide_empty = true;
} else {
    $hide_empty = false;
}
?>
<div class="advanced-search-mobile houzez-adv-price-range" data-sticky='<?php echo esc_attr( $mobile_search_sticky ); ?>'>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form autocomplete="off" method="get" action="<?php echo esc_url( $search_template ); ?>">
                    <div class="single-search-wrap">
                        <div class="single-search-inner advance-btn">
                            <button class="table-cell text-left" type="button"><i class="fa fa-gear"></i></button>
                        </div>
                        <?php if ($adv_show_hide['keyword'] != 1) { ?>
                        <div class="single-search-inner single-search">
                            <input type="text" class="form-control" value="<?php echo isset ( $_GET['keyword'] ) ? stripcslashes($_GET['keyword']) : ''; ?>" name="keyword" placeholder="<?php echo $keyword_field_placeholder; ?>">
                            <div id="auto_complete_ajax" class="auto-complete"></div>
                        </div>
                        <?php } elseif($enable_radius_search == 1) {?>
                            <div class="single-search-inner single-search">
                                <div class="search-location">
                                    <input type="text" class="form-control search_location" value="<?php echo isset ( $_GET['search_location'] ) ? $_GET['search_location'] : ''; ?>" name="search_location" placeholder="<?php echo esc_html__('Location', 'houzez'); ?>">
                                    
                                </div>
                            </div>

                        <?php } ?>
                        <div class="single-search-inner single-seach-btn">
                            <button class="table-cell text-right" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="advance-fields">
                        <div class="row">
                            <?php if( $enable_radius_search == 1 ) { ?>
                                <input type="hidden" name="lat" value="<?php echo isset ( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>" id="latitude">
                                <input type="hidden" name="lng" value="<?php echo isset ( $_GET['lng'] ) ? $_GET['lng'] : ''; ?>" id="longitude">
                                <input type="checkbox" name="use_radius" id="use_radius" <?php checked( true, $checked ); ?>">
                                <?php if ($adv_show_hide['keyword'] != 1) { ?>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <div class="search-location">
                                            <input type="text" class="form-control search_location" value="<?php echo isset ( $_GET['search_location'] ) ? $_GET['search_location'] : ''; ?>" name="search_location" placeholder="<?php echo esc_html__('Location', 'houzez'); ?>">
                                            <i class="location-trigger fa fa-dot-circle-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                    <select name="radius" class="selectpicker" data-live-search="true" data-live-search-style="begins">
                                        <option value="0"><?php esc_html_e('Radius','houzez');?></option>
                                        <?php
                                        $i = 0;
                                        for( $i = 1; $i <= 100; $i++ ) {
                                            echo '<option '.selected( $selected_radius, $i, false).' value="'.$i.'">'.$i.' '.$radius_unit.'</option>';
                                        }
                                        ?>
                                    </select>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['countries'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="country" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.esc_html__('All Countries', 'houzez').'</option>';

                                        countries_dropdown( $searched_country );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['states'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="state" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.esc_html__('All States', 'houzez').'</option>';

                                        $prop_state = get_terms (
                                            array(
                                                "property_state"
                                            ),
                                            array(
                                                'orderby' => 'name',
                                                'order' => 'ASC',
                                                'hide_empty' => $hide_empty,
                                                'parent' => 0
                                            )
                                        );
                                        houzez_hirarchical_options('property_state', $prop_state, $state );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['cities'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="location" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.$houzez_local['all_cities'].'</option>';

                                        $prop_city = get_terms (
                                            array(
                                                "property_city"
                                            ),
                                            array(
                                                'orderby' => 'name',
                                                'order' => 'ASC',
                                                'hide_empty' => $hide_empty,
                                                'parent' => 0
                                            )
                                        );
                                        houzez_hirarchical_options('property_city', $prop_city, $location );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['areas'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="area" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.$houzez_local['all_areas'].'</option>';

                                        $prop_area = get_terms (
                                            array(
                                                "property_area"
                                            ),
                                            array(
                                                'orderby' => 'name',
                                                'order' => 'ASC',
                                                'hide_empty' => $hide_empty,
                                                'parent' => 0
                                            )
                                        );
                                        houzez_hirarchical_options('property_area', $prop_area, $area );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['status'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select class="selectpicker" id="selected_status_mobile" name="status" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.$houzez_local['all_status'].'</option>';

                                        $prop_status = get_terms (
                                            array(
                                                "property_status"
                                            ),
                                            array(
                                                'orderby' => 'name',
                                                'order' => 'ASC',
                                                'hide_empty' => false,
                                                'parent' => 0
                                            )
                                        );
                                        houzez_hirarchical_options('property_status', $prop_status, $status );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['type'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select class="selectpicker" name="type" data-live-search="false" data-live-search-style="begins">
                                        <?php
                                        // All Option
                                        echo '<option value="">'.$houzez_local['all_types'].'</option>';

                                        $prop_type = get_terms (
                                            array(
                                                "property_type"
                                            ),
                                            array(
                                                'orderby' => 'name',
                                                'order' => 'ASC',
                                                'hide_empty' => false,
                                                'parent' => 0
                                            )
                                        );
                                        houzez_hirarchical_options('property_type', $prop_type, $type );
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['beds'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="bedrooms" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                        <option value=""><?php echo $houzez_local['beds'];; ?></option>
                                        <?php houzez_number_list('bedrooms'); ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['baths'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <select name="bathrooms" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                        <option value=""><?php echo $houzez_local['baths']; ?></option>
                                        <?php houzez_number_list('bathrooms'); ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['min_area'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo isset ( $_GET['min-area'] ) ? $_GET['min-area'] : ''; ?>" name="min-area" placeholder="<?php echo $houzez_local['min_area']; echo " ($measurement_unit_adv_search)";?>">
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['max_area'] != 1 ) { ?>
                            <div class="col-sm-3 col-xs-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo isset ( $_GET['max-area'] ) ? $_GET['max-area'] : ''; ?>" name="max-area" placeholder="<?php echo $houzez_local['max_area']; echo " ($measurement_unit_adv_search)"; ?>">
                                </div>
                            </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['label'] != 1 ) { ?>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <select class="selectpicker" name="label" data-live-search="false" data-live-search-style="begins">
                                            <?php
                                            // All Option
                                            echo '<option value="">'.$houzez_local['all_labels'].'</option>';

                                            $prop_label = get_terms (
                                                array(
                                                    "property_label"
                                                ),
                                                array(
                                                    'orderby' => 'name',
                                                    'order' => 'ASC',
                                                    'hide_empty' => false,
                                                    'parent' => 0
                                                )
                                            );
                                            houzez_hirarchical_options('property_label', $prop_label, $label );
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if( $adv_show_hide['property_id'] != 1 ) { ?>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="<?php echo isset ( $_GET['property_id'] ) ? $_GET['property_id'] : ''; ?>" name="property_id" placeholder="<?php echo $houzez_local['property_id']; ?>">
                                    </div>
                                </div>
                            <?php } ?>

                            <?php
                            if(class_exists('Houzez_Fields_Builder')) {
                                $fields_array = Houzez_Fields_Builder::get_form_fields();
                                            
                                if(!empty($fields_array)) {
                                    foreach ( $fields_array as $value ) {
                                        $field_title = $value->label;
                                        
                                        if (function_exists('icl_translate')) {
                                            $field_title = icl_translate('houzez_cfield', 'houzez_custom_field_'.sanitize_title($field_title), $field_title );
                                        }
                                        $field_name = $value->field_id;
                                        $field_type = $value->type;
                                        $is_search = $value->is_search;

                                        if(isset($_GET[$field_name])) {
                                            $get_field_name = $_GET[$field_name];
                                        } else {
                                            $get_field_name = '';
                                        }

                                            if( $is_search == 'yes' ) { 

                                                if($adv_show_hide[$field_name] != 1 ) {
                                                    if($field_type == 'select') { ?>

                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="form-group">
                                                                <select name="<?php echo esc_attr($field_name);?>" class="selectpicker <?php echo esc_attr($field_name);?>" data-live-search="false" data-live-search-style="begins" title="">
                                                                <option value=""><?php echo $field_title; ?></option>
                                                                <?php
                                                                $options = unserialize($value->fvalues);
                                                                
                                                                foreach ($options as $key => $val) {
                                                                    echo '<option '.selected( $key, $get_field_name, false).' value="'.$key.'">'.$val.'</option>';
                                                                }
                                                                ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    <?php
                                                    } else {
                                                    ?>

                                                    <div class="col-sm-3 col-xs-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control <?php echo esc_attr($field_name);?>" value="<?php echo isset ( $_GET[$field_name] ) ? $_GET[$field_name] : ''; ?>" name="<?php echo esc_attr($field_name);?>" placeholder="<?php esc_attr_e($field_title);?>">
                                                        </div>
                                                    </div>

                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            ?>

                            <?php 
                                $multi_currency = houzez_option('multi_currency');
                                if($multi_currency == 1 ) {
                                    if(class_exists('Houzez_Currencies')) {

                                        $searched_currency = isset($_GET['currency']) ? $_GET['currency'] : '';

                                        $currencies = Houzez_Currencies::get_currency_codes();
                                        if($currencies) {
                                            echo '<div class="col-sm-12 col-xs-12">';
                                            echo '<div class="form-group">';
                                            echo '<select name="currency" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">';
                                            echo '<option value="">'.$houzez_local['currency_label'].'</option>';
                                            echo '<option value="">'.$houzez_local['any'].'</option>';
                                            foreach ($currencies as $currency) {
                                                echo '<option '.selected( $currency->currency_code, $searched_currency, false).' value="'.$currency->currency_code.'">'.$currency->currency_code.'</option>'; 
                                            }
                                            echo '</select>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                }
                            ?>

                            <?php if( $adv_search_price_slider != 0 ) { ?>
                                <?php if( $adv_show_hide['price_slider'] != 1 ) { ?>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="range-advanced-main">
                                            <div class="range-text">
                                                <input type="hidden" name="min-price" class="min-price-range-hidden range-input">
                                                <input type="hidden" name="max-price" class="max-price-range-hidden range-input">
                                                <p><span class="range-title"><?php echo $houzez_local['price_range'];?></span> <?php echo $houzez_local['from']; ?> <span class="min-price-range"></span> <?php echo $houzez_local['to']; ?> <span class="max-price-range"></span></p>
                                            </div>
                                            <div class="range-wrap">
                                                <div class="price-range-advanced"></div>
                                            </div>
                                        </div>
                                    </div>
                                  <?php } ?>
                            <?php } else { ?>

                                <?php if( $adv_show_hide['min_price'] != 1 ) { ?>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group prices-for-all">
                                        <select name="min-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                            <option value=""><?php echo $houzez_local['min_price']; ?></option>
                                            <?php houzez_adv_searches_min_price(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group hide prices-only-for-rent">
                                        <select name="min-price" disabled class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                            <option value=""><?php echo $houzez_local['min_price']; ?></option>
                                            <?php houzez_adv_searches_min_price_rent_only(); ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if( $adv_show_hide['max_price'] != 1 ) { ?>
                                <div class="col-sm-3 col-xs-6">
                                    <div class="form-group prices-for-all">
                                        <select name="max-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                            <option value=""><?php echo $houzez_local['max_price']; ?></option>
                                            <?php houzez_adv_searches_max_price() ?>
                                        </select>
                                    </div>
                                    <div class="form-group hide prices-only-for-rent">
                                        <select name="max-price" disabled class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                            <option value=""><?php echo $houzez_local['max_price']; ?></option>
                                            <?php houzez_adv_searches_max_price_rent_only() ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>

                            <?php if( $adv_show_hide['other_features'] != 1 ) { ?>
                            <div class="col-sm-12 col-xs-12">
                                <label class="advance-trigger"><i class="fa fa-plus-square"></i> <?php echo $houzez_local['other_feature']; ?> </label>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="features-list field-expand">
                                    <div class="clearfix"></div>
                                    <?php get_template_part('template-parts/advanced-search/search-features'); ?>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-secondary btn-block houzez-theme-button"><i class="fa fa-search pull-left"></i> <?php echo $houzez_local['search']; ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>