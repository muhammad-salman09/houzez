<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/06/16
 * Time: 11:08 PM
 */
global $measurement_unit_adv_search, $houzez_local, $post;

if( $measurement_unit_adv_search == 'sqft' ) {
    $measurement_unit_adv_search = houzez_option('measurement_unit_sqft_text');
} elseif( $measurement_unit_adv_search == 'sq_meter' ) {
    $measurement_unit_adv_search = houzez_option('measurement_unit_square_meter_text');
}

$adv_search_price_slider = houzez_option('adv_search_price_slider');
$status = $type = $location = $area = $searched_country = $state = $label = '';
$adv_show_hide = houzez_option('adv_show_hide_halmap');
$enable_disable_save_search = houzez_option('enable_disable_save_search');

$state_city_area_dropdowns = houzez_option('state_city_area_dropdowns');
if( $state_city_area_dropdowns != 0 ) {
    $hide_empty = true;
} else {
    $hide_empty = false;
}

$meta_states = get_post_meta($post->ID, 'fave_states', true);
$meta_locations = get_post_meta($post->ID, 'fave_locations', true);
$meta_types = get_post_meta($post->ID, 'fave_types', true);
$meta_status = get_post_meta($post->ID, 'fave_status', true);
$meta_labels = get_post_meta($post->ID, 'fave_labels', true);
$meta_area = get_post_meta($post->ID, 'fave_area', true);

if( isset( $_GET['status'] ) ) {
    $status = $_GET['status'];
} elseif(!empty($meta_status)) {
    $status = $meta_status;
}
if( isset( $_GET['type'] ) ) {
    $type = $_GET['type'];
} elseif(!empty($meta_types)) {
    $type = $meta_types;
}

if( isset( $_GET['location'] ) ) {
    $location = $_GET['location'];
} elseif(!empty($meta_locations)) {
    $location = $meta_locations;
}

if( isset( $_GET['area'] ) ) {
    $area = $_GET['area'];
} elseif(!empty($meta_area)) {
    $area = $meta_area;
}

if( isset( $_GET['state'] ) ) {
    $state = $_GET['state'];
} elseif(!empty($meta_states)) {
    $state = $meta_states;
}

if( isset( $_GET['label'] ) ) {
    $label = $_GET['label'];
} elseif(!empty($meta_labels)) {
    $label = $meta_labels;
}
if( isset( $_GET['country'] ) ) {
    $searched_country = $_GET['country'];
}

$keyword_field = houzez_option('keyword_field');

if( $keyword_field == 'prop_title' ) {
    $keyword_field_placeholder = $houzez_local['keyword_text'];

} else if( $keyword_field == 'prop_city_state_county' ) {
    $keyword_field_placeholder = $houzez_local['city_state_area'];

} else if( $keyword_field == 'prop_address' ) {
    $keyword_field_placeholder = $houzez_local['search_address'];

} else {
    $keyword_field_placeholder = $houzez_local['enter_location'];
}
$checked = true;
$radius_unit = houzez_option('radius_unit');
$enable_radius_search = houzez_option('enable_radius_search_halfmap');

if ($adv_show_hide['keyword'] != 1) {
    $geo_location_field_classes = 'col-md-6 col-sm-6 col-xs-6';
} else {
    $geo_location_field_classes = 'col-md-12 col-sm-12 col-xs-12';
}
?>
<div class="advanced-search houzez-adv-price-range">

    <!-- <form autocomplete="off" method="get" class="save_search_form" action="#"> -->

        <?php if( $enable_radius_search != 0 ) { ?>
            <input type="hidden" name="search_radius" id="radius-range-value">
            <input type="hidden" name="lat" value="<?php echo isset ( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>" id="latitude">
            <input type="hidden" name="lng" value="<?php echo isset ( $_GET['lng'] ) ? $_GET['lng'] : ''; ?>" id="longitude">
        <div class="row">
            <?php if ($adv_show_hide['keyword'] != 1) { ?>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group table-list search-long">
                    <div class="input-search input-icon">
                        <input type="text" class="form-control" value="<?php echo isset ( $_GET['keyword'] ) ? stripcslashes($_GET['keyword']) : ''; ?>" name="keyword" placeholder="<?php echo $keyword_field_placeholder; ?>">
                        <div id="auto_complete_ajax" class="auto-complete"></div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="<?php echo esc_attr($geo_location_field_classes);?>">
                <div class="form-group">
                    <div class="search-location">
                        <input type="text" class="form-control search_location" value="<?php echo isset ( $_GET['search_location'] ) ? $_GET['search_location'] : ''; ?>" name="search_location" placeholder="<?php echo esc_html__('Location', 'houzez'); ?>">
                        <i class="location-trigger fa fa-dot-circle-o"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 col-xs-3">
                <div class="form-group">
                    <div class="radius-text-wrap">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="use_radius" id="use_radius" <?php checked( true, $checked ); ?>> <?php echo esc_html__('Radius:', 'houzez'); ?> <strong><span id="radius-range-text">0</span> <?php echo esc_attr($radius_unit); ?></strong>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 col-xs-9">
                <div class="radius-range-wrap">
                    <div id="radius-range-slider"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="row">

            <?php if( $enable_radius_search != 1 ) {
                if ($adv_show_hide['keyword'] != 1) { ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group table-list search-long">
                            <div class="input-search input-icon">
                                <input type="text" class="form-control"
                                       value="<?php echo isset ($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                                       name="keyword" placeholder="<?php echo $keyword_field_placeholder; ?>">
                                <div id="auto_complete_ajax" class="auto-complete"></div>
                            </div>
                        </div>
                    </div>
                <?php }
            }?>

            <?php if( $adv_show_hide['countries'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <select name="country" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                        <?php
                        // All Option
                        echo '<option value="">'.$houzez_local['all_countries'].'</option>';

                        countries_dropdown( $searched_country );
                        ?>
                    </select>
                </div>
            </div>
            <?php } ?>

            <?php if( $adv_show_hide['states'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <select name="state" class="selectpicker" data-live-search="false" data-live-search-style="begins">
                        <?php
                        // All Option
                        echo '<option value="">'.$houzez_local['all_states'].'</option>';

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
            <div class="col-md-3 col-sm-6 col-xs-6">
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
            <div class="col-md-3 col-sm-6 col-xs-6">
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
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <select class="selectpicker" name="status" data-live-search="false" data-live-search-style="begins">
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
            <div class="col-md-3 col-sm-6 col-xs-6">
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

            <?php if( $adv_show_hide['label'] != 1 ) { ?>
                <div class="col-md-3 col-sm-6 col-xs-6">
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
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <input type="text" class="form-control" value="<?php echo isset ( $_GET['property_id'] ) ? $_GET['property_id'] : ''; ?>" name="property_id" placeholder="<?php echo $houzez_local['property_id']; ?>">
                    </div>
                </div>
            <?php } ?>

            <?php if( $adv_show_hide['beds'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <select name="bedrooms" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                        <option value=""><?php echo $houzez_local['bedrooms']; ?></option>
                        <?php houzez_number_list('bedrooms'); ?>
                    </select>
                </div>
            </div>
            <?php } ?>

            <?php if( $adv_show_hide['baths'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <select name="bathrooms" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                        <option value=""><?php echo $houzez_local['bathrooms']; ?></option>
                        <?php houzez_number_list('bathrooms'); ?>
                    </select>
                </div>
            </div>
            <?php } ?>


            <?php if( $adv_show_hide['min_area'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <input type="text" class="form-control" value="<?php echo isset ( $_GET['min-area'] ) ? $_GET['min-area'] : ''; ?>" name="min-area" placeholder="<?php echo $houzez_local['min_area']; ?>">
                </div>
            </div>
            <?php } ?>

            <?php if( $adv_show_hide['max_area'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group">
                    <input type="text" class="form-control" value="<?php echo isset ( $_GET['max-area'] ) ? $_GET['max-area'] : ''; ?>" name="max-area" placeholder="<?php echo $houzez_local['max_area']; ?>">
                </div>
            </div>
            <?php } ?>

            <?php if( $adv_show_hide['date_field'] != 1 ) { ?>
            <div class="col-md-3 col-sm-6 col-xs-6 sech_avl_date">
                <div class="form-group">
                    <div class="input-calendar input-icon input-icon-right">
                        <input name="publish_date" class="form-control search-date" placeholder="<?php echo $houzez_local['available_from']; ?>" type="text">
                    </div>
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
                        $is_search = $value->is_search;

                        if (function_exists('icl_translate')) {
                            $field_title = icl_translate('houzez_cfield', 'houzez_custom_field_'.sanitize_title($field_title), $field_title );
                        }

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

            
            $multi_currency = houzez_option('multi_currency');
            if($multi_currency == 1 ) {
                if(class_exists('Houzez_Currencies')) {

                    $searched_currency = isset($_GET['currency']) ? $_GET['currency'] : '';

                    $currencies = Houzez_Currencies::get_currency_codes();
                    if($currencies) {
                        echo '<div class="col-sm-3 col-xs-6">';
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
                                <input type="hidden" name="min-price" class="min-price-range-hidden range-input" readonly >
                                <input type="hidden" name="max-price" class="max-price-range-hidden range-input" readonly >
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
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="form-group prices-for-all">
                            <select name="min-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                <option value=""><?php echo $houzez_local['min_price']; ?></option>
                                <?php houzez_adv_searches_min_price(); ?>
                            </select>
                        </div>
                        <div class="form-group hide prices-only-for-rent">
                            <select name="min-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                <option value=""><?php echo $houzez_local['min_price']; ?></option>
                                <?php houzez_adv_searches_min_price_rent_only(); ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <?php if( $adv_show_hide['max_price'] != 1 ) { ?>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="form-group prices-for-all">
                            <select name="max-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                <option value=""><?php echo $houzez_local['max_price']; ?></option>
                                <?php houzez_adv_searches_max_price() ?>
                            </select>
                        </div>
                        <div class="form-group hide prices-only-for-rent">
                            <select name="max-price" class="selectpicker" data-live-search="false" data-live-search-style="begins" title="">
                                <option value=""><?php echo $houzez_local['max_price']; ?></option>
                                <?php houzez_adv_searches_max_price_rent_only() ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>

            <?php } ?>

        </div>

        <div class="row">
            <div class="col-sm-12 col-xs-12 advance-trigger-wrap">
                <?php if( $adv_show_hide['other_features'] != 1 ) { ?>
                <label class="advance-trigger"><i class="fa fa-plus-square"></i> <?php echo $houzez_local['other_feature']; ?> </label>
                <?php } ?>
                <?php if( $enable_disable_save_search != 0 ) { ?>
                <span  id="save_search_click" class="save-btn"><?php esc_html_e( 'Save', 'houzez' ); ?></span>
                <input type="hidden" name="search_args" value="">
                <input type="hidden" name="search_URI" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="action" value='houzez_save_search'>
                <input type="hidden" name="houzez_save_search_ajax" value="<?php echo wp_create_nonce('houzez-save-search-nounce')?>">
                <?php } ?>
            </div>
            <div class="col-sm-12 col-xs-12">
                <div class="features-list field-expand">
                    <div class="clearfix"></div>
                    <?php get_template_part('template-parts/advanced-search/search-features'); ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <button type="submit" id="half_map_update" class="btn btn-primary btn-block"><?php echo esc_html__('Update', 'houzez'); ?></button>
            </div>
        </div>

    <!-- </form> -->
</div>