<?php
add_action('admin_head', 'custom_styles');
function custom_styles() {
  echo '<style>
    .form-field input, .form-field textarea {
        width: auto !important;
    }
    .rwmb-button-wrapper .rwmb-input {
        text-align: center;
    }
    #fave_billing_unit_add,
    .payment_option {
        margin-top: 25px;
    }
    .payment {
        width: 50%;
    }
    .payment:not(.selected) {
        display: none;
    }
    .wp-core-ui .payment_option button.button {
        background: none !important;
        border: none;
        border-radius: 0;
        box-shadow: none;
        color: #ff0000;
        height: 25px;
        padding: 0 3px;
    }
    .wp-core-ui .payment_option button.button:hover {
        border-bottom: 1px solid #ff0000;
    }
  </style>';
}

add_action( 'wp_enqueue_scripts', 'my_scripts' );
function my_scripts() {
    wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/custom.js', array('jquery') );


    if (is_page_template( 'template-map-search.php' )) {
        $googlemap_api_key = houzez_option('googlemap_api_key');

        $minify_js = houzez_option('minify_js');
        $js_minify_prefix = '';

        if ($minify_js != 0) {
            $js_minify_prefix = '.min';
        }

        wp_enqueue_script('google-map', 'https://maps.googleapis.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key), array('jquery'), '1.0', false);
        wp_enqueue_script('google-map-info-box', get_template_directory_uri() . '/js/infobox' . $js_minify_prefix . '.js', array('google-map'), '1.1.9', false);
        wp_enqueue_script('google-map-marker-clusterer', get_template_directory_uri() . '/js/markerclusterer' . $js_minify_prefix . '.js', array('google-map'), '2.1.1', false);
        wp_enqueue_script('oms.min.js', get_template_directory_uri() . '/js/oms.min.js', array('google-map'), '1.12.2', false);

        wp_enqueue_script( 'richmarker', get_stylesheet_directory_uri() . '/richmarker.js', array('jquery') );
        
        wp_enqueue_script( 'map', get_stylesheet_directory_uri() . '/map.js', array('jquery') );
    }
}

add_action('admin_enqueue_scripts', 'custom_scripts');
if (is_admin() ){
    function custom_scripts(){
        global $pagenow, $typenow;

        wp_enqueue_script('ftmetajs', get_template_directory_uri() .'/js/admin/init.js', array('jquery','media-upload','thickbox'));
        wp_enqueue_style( 'houzez-admin.css', get_template_directory_uri(). '/css/admin/admin.css', array(), HOUZEZ_THEME_VERSION, 'all' );

        wp_enqueue_script('houzez-admin-ajax', get_template_directory_uri() .'/js/admin/houzez-admin-ajax.js', array('jquery'));
        wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/admin.js', array('jquery') );
        wp_localize_script('houzez-admin-ajax', 'houzez_admin_vars',
            array( 'ajaxurl'            => admin_url('admin-ajax.php'),
                'paid_status'        =>  __('Paid','houzez')

            )
        );

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        if ( isset( $_GET['taxonomy'] ) && ( $_GET['taxonomy'] == 'property_lifestyle' || $_GET['taxonomy'] == 'property_region' ) ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'houzez_taxonomies', get_template_directory_uri().'/js/admin/metaboxes-taxonomies.js', array( 'jquery', 'wp-color-picker' ), 'houzez' );
        }
    }
}

/**
 * Override function to display price with currency symbol
 */
function houzez_listing_price() {
    global $wpdb;

    $currency_code = get_post_meta( get_the_ID(), 'fave_currency', true);

    $result = $wpdb->get_results(" SELECT currency_symbol FROM " . $wpdb->prefix . "houzez_currencies where currency_code='$currency_code'");

    if (sizeof($result) > 0)
        $symbol = $result[0]->currency_symbol;
    else
        $symbol = '€';

    $sale_price = get_post_meta( get_the_ID(), 'fave_property_price', true );
    $sale_price = number_format ( $sale_price , 0, '', ',' );

    $status = get_the_terms( get_the_ID(), 'property_status' );

    if ($status[0]->slug == 'for-rent')
        echo $symbol . $sale_price . '/mo';
    else
        echo $symbol . $sale_price;
}

function houzez_listing_price_v1() {
    global $wpdb;

    $currency_code = get_post_meta( get_the_ID(), 'fave_currency', true);

    $result = $wpdb->get_results(" SELECT currency_symbol FROM " . $wpdb->prefix . "houzez_currencies where currency_code='$currency_code'");

    if (sizeof($result) > 0)
        $symbol = $result[0]->currency_symbol;
    else
        $symbol = '€';

    $sale_price = get_post_meta( get_the_ID(), 'fave_property_price', true );
    $sale_price = number_format ( $sale_price , 0, '', ',' );
    
    $status = get_the_terms( get_the_ID(), 'property_status' );
    
    if ($status[0]->slug == 'for-rent')
        return $symbol . $sale_price . '/mo';
    else
        return $symbol . $sale_price;
}

/**
 * Theme Option Update for Redux options
 */
add_filter("redux/options/houzez_options/sections", 'update_redux_options');
function update_redux_options($sections){
    $search_field = 0;
    $search_index = 0;
    $property_section = 0;

    $i = 1;
    $index = 0;
    while ($index == 0) {
        if ($sections[$i]['id'] == 'mem-wire-payment') {
            $index = $i;
        }

        $i++;
    }

    for ($i = sizeof($sections); $i > $index; $i--) {
        $sections[$i + 3] = $sections[$i];
    }

    $sections[$index + 1] = array(
        'title' => 'Bitcoin',
        'id' => 'mem-bitcoin-payment',
        'desc' => '',
        'subsection' => true,
        'priority' => $index + 1,
        'fields' => array(
            array(
                'id' => 'enable_bitcoin',
                'type' => 'switch',
                'title' => 'Enable Bitcoin',
                'required' => array('enable_paid_submission', '!=', 'no'),
                'desc' => '',
                'subtitle' => '',
                'default' => 0,
                'on' => 'Enabled',
                'off' => 'Disabled',
                'section_id' => 'mem-bitcoin-payment'
            )
        )
    );
    $sections[$index + 2] = array(
        'title' => 'Apple Pay',
        'id' => 'mem-apple-payment',
        'desc' => '',
        'subsection' => true,
        'priority' => $index + 2,
        'fields' => array(
            array(
                'id' => 'enable_applepay',
                'type' => 'switch',
                'title' => 'Enable Apple Pay',
                'required' => array('enable_paid_submission', '!=', 'no'),
                'desc' => '',
                'subtitle' => '',
                'default' => 0,
                'on' => 'Enabled',
                'off' => 'Disabled',
                'section_id' => 'mem-apple-payment'
            )
        )
    );
    $sections[$index + 3] = array(
        'title' => 'Google Pay',
        'id' => 'mem-google-payment',
        'desc' => '',
        'subsection' => true,
        'priority' => $index + 3,
        'fields' => array(
            array(
                'id' => 'enable_googlepay',
                'type' => 'switch',
                'title' => 'Enable Google Pay',
                'required' => array('enable_paid_submission', '!=', 'no'),
                'desc' => '',
                'subtitle' => '',
                'default' => 0,
                'on' => 'Enabled',
                'off' => 'Disabled',
                'section_id' => 'mem-google-payment'
            )
        )
    );

    for ($i = 1; $i < sizeof($sections) + 1; $i++) {
        if ($sections[$i]['id'] == 'adv-search-fields') {
            $search_field = $i;

            $fields = $sections[$i]['fields'];
            $keys = array_keys($fields);

            for ($j = $keys[0]; $j < $keys[sizeof($keys) - 1] + 1; $j++) {
                if ($fields[$j]['id'] == 'adv_show_hide') {
                    $search_index = $j;
                }
            }
        }

        if ($sections[$i]['id'] == 'property-section') {
            $property_section = $i;
        }
    }

    $add_option = array(
        'lifestyle' => 'Lifestyle',
        'region' => 'Region'
    );

    $add_default = array(
        'lifestyle' => '1',
        'region' => '1'
    );

    $sections[$search_field]['fields'][$search_index]['options'] = 
        array_insert_after($sections[$search_field]['fields'][$search_index]['options'], 'type', $add_option);
    $sections[$search_field]['fields'][$search_index]['default'] = 
        array_insert_after($sections[$search_field]['fields'][$search_index]['default'], 'type', $add_default);

    unset($sections[$search_field]['fields'][$search_index]['options']['label']);
    unset($sections[$search_field]['fields'][$search_index]['default']['label']);

    $key_arr = array_keys($sections[$property_section]['fields']);
    $property_field_id = $key_arr[0];

    $sections[$property_section]['fields'][$property_field_id]['options']['enabled'] =
        array_insert_after($sections[$property_section]['fields'][$property_field_id]['options']['enabled'], 
            'floor_plans', array('solar_perspective' => 'Solar Perstpective')); 

    return $sections;
}

function array_insert_after( array $array, $key, array $new ) {
    $keys = array_keys( $array );
    $index = array_search( $key, $keys );
    $pos = false === $index ? count( $array ) : $index + 1;
    
    return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

/**
 * Add, Remove, Update Meta box
 * For Package Creation, Solar Perstpective Creation
 */
add_filter('rwmb_meta_boxes', 'update_custom_metabox', 1000);
function update_custom_metabox($meta_boxes) {
    $options = array(
        'One-Time', 'Monthly (recurring basis)',
        'Quarterly (recurring basis)', 'Semi-Annually (recurring basis)'
    );

    for ($j = 0; $j < sizeof($meta_boxes); $j++) {
        // Package Creation
        if ($meta_boxes[$j]['pages'][0] == 'houzez_packages') {
            for ($i = sizeof($meta_boxes[$j]['fields']) + 12; $i > 2; $i--) {
                if ($i > 14) {
                    $meta_boxes[$j]['fields'][$i] = $meta_boxes[$j]['fields'][$i - 13];
                } else {
                    $index = floor($i / 3);

                    switch ($i) {
                        case 3:
                        case 6:
                        case 9:
                        case 12:
                            $meta_boxes[$j]['fields'][$i] = array(
                                'name' => 'Payment Option:',
                                'type' => 'custom_html',
                                'std' => '<span>' . $options[$index - 1] . '</span>',
                                'columns' => 4
                            );
                            break;
                        case 4:
                        case 7:
                        case 10:
                        case 13:
                            $meta_boxes[$j]['fields'][$i] = array(
                                'id' => 'fave_payment_option' . $index,
                                'name' => 'Amount',
                                'type' => 'number',
                                'std' => '',
                                'columns' => 4
                            );
                            break;
                        case 5:
                        case 8:
                        case 11:
                        case 14:
                            $meta_boxes[$j]['fields'][$i] = array(
                                'id' => 'fave_payment_btn' . $index,
                                'name' => '',
                                'type' => 'button',
                                'std' => 'Remove',
                                'class' => 'payment_option',
                                'columns' => 3
                            );
                            break;
                    }
                }
            }

            $meta_boxes[$j]['fields'][2] = $meta_boxes[$j]['fields'][1];

            $meta_boxes[$j]['fields'][1] = array(
                'id' => 'fave_billing_unit_add',
                'name' => '',
                'type' => 'button',
                'std' => 'Add',
                'columns' => 2
            );

            $meta_boxes[$j]['fields'][0]['name'] = 'Payment Options';
            $meta_boxes[$j]['fields'][0]['options'] = array(
                '' => 'Select from the following',
                'option1' => 'One-Time',
                'option2' => 'Monthly (recurring basis)',
                'option3' => 'Quarterly (recurring basis)',
                'option4' => 'Semi-Annually (recurring basis)'
            );

            $meta_boxes[$j]['fields'][0]['columns'] = 4;

            ksort($meta_boxes[$j]['fields']);

            $meta_boxes[$j]['fields'][sizeof($meta_boxes[$j]['fields']) - 1]['columns'] = 6;
            $meta_boxes[$j]['fields'][sizeof($meta_boxes[$j]['fields'])] = array(
                'id' => 'fave_encrypt_doc',
                'name' => 'Encryption and Document Storage',
                'type' => 'checkbox',
                'desc' => 'Enable',
                'std' => '',
                'columns' => 6
            );
        }

        // Solar Perspective Creation
        if ($meta_boxes[$j]['pages'][0] == 'property' && $meta_boxes[$j]['tabs']) {
            $perspective = array(
                'id' => 'fave_perspective',
                'name' => 'What direction does the front of the house face?',
                'type' => 'select',
                'options' => array(
                    '' => '',
                    'north' => 'North',
                    'northeast' => 'Northeast',
                    'east' => 'East',
                    'southeast' => 'Southeast',
                    'south' => 'South',
                    'southwest' => 'Southwest',
                    'west' => 'West',
                    'northwest' => 'Northwest'
                ),
                'std' => '',
                'columns' => 6,
                'tab' => 'property_details'
            );

            $k = 0;
            $fields = array();
            foreach ($meta_boxes[$j]['fields'] as $field) {
                $fields[$k++] = $field;
            }

            $meta_boxes[$j]['fields'] = $fields;

            for ($k = sizeof($meta_boxes[$j]['fields']); $k > 14; $k-- ) {
                $meta_boxes[$j]['fields'][$k] = $meta_boxes[$j]['fields'][$k - 1];
            }

            $meta_boxes[$j]['fields'][15] = $perspective;
        }
    }

    return $meta_boxes;
}

/**
 * Remove theme's template for custom templates
 */
function houzez_remove_page_templates( $templates ) {
    unset( $templates['template/template-packages.php'] );
    unset( $templates['template/user_dashboard_properties.php'] );
    return $templates;
}
add_filter( 'theme_page_templates', 'houzez_remove_page_templates' );

/**
 * Homepage Advanced Search
 */
vc_remove_element('hz-advance-search');

if( !function_exists('houzez_advance_search_update') ) {
    function houzez_advance_search_update($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'search_title' => ''
        ), $atts));

        ob_start();

        $search_template = home_url() . '/advanced-search';
        $houzez_local = houzez_get_localization();
        $adv_search_price_slider = houzez_option('adv_search_price_slider');
        $hide_empty = false;
        ?>

        <div class="advanced-search advanced-search-module houzez-adv-price-range front">
            <h3 class="advance-title"><?php echo esc_html__('Search Properties for Sale'); ?></h3>

            <form autocomplete="off" method="get" action="<?php echo esc_url($search_template); ?>">
                <div class="row">
                    <input type="hidden" id="type" name="status" value="for-sale" />
                    <div class="col-md-2 buy-btn">
                        <button type="button" class="btn btn-primary btn-type"><?php echo esc_html__('Buy'); ?></button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-type"><?php echo esc_html__('Rent'); ?></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-sm-8 has-search">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input type="text" name="city" class="form-control" 
                            placeholder="<?php echo esc_html__('Neighborhood, City'); ?>" />
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <button type="submit" class="btn btn-secondary">
                            <?php echo strtoupper($houzez_local['search']); ?>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        <div class="col-md-3 col-sm-6">
                            <select class="selectpicker bs-select-hidden" name="lifestyle">
                            <?php
                                echo '<option value="">' . esc_html__('Lifestyle') . '</option>';

                                $prop_lifestyle = get_terms(
                                    array(
                                        "property_lifestyle"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => $hide_empty,
                                        'parent' => 0
                                    )
                                );
                                houzez_hirarchical_options('property_lifestyle', $prop_lifestyle, '');
                            ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <select class="selectpicker bs-select-hidden" name="region">
                            <?php
                                echo '<option value="">' . esc_html__('Location') . '</option>';

                                $prop_region = get_terms(
                                    array(
                                        "property_region"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => $hide_empty,
                                        'parent' => 0
                                    )
                                );
                                houzez_hirarchical_options('property_region', $prop_region, '');
                            ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <select class="selectpicker bs-select-hidden" name="type">
                            <?php
                                echo '<option value="">' . esc_html__('Property Type') . '</option>';

                                $prop_type = get_terms(
                                    array(
                                        "property_type"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => $hide_empty,
                                        'parent' => 0
                                    )
                                );
                                houzez_hirarchical_options('property_type', $prop_type, '');
                            ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?php 
                                $searched_currency = isset($_GET['currency']) ? $_GET['currency'] : '';
                                $currencies = Houzez_Currencies::get_currency_codes();
                            ?>
                            <select class="selectpicker bs-select-hidden" name="currency">
                                <option value=""><?php echo esc_html__('Fiat/Crypto') ?></option>
                            <?php
                                foreach($currencies as $currency) {
                                    echo '<option '.selected( $currency->currency_code, $searched_currency, false).' value="'.$currency->currency_code.'">'.$currency->currency_code.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 range-advanced-main">
                        <?php if( $adv_search_price_slider != 0 ) { ?>
                            <div class="range-text col-md-3">
                                <input type="hidden" name="min-price" class="min-price-range-hidden range-input" readonly >
                                <input type="hidden" name="max-price" class="max-price-range-hidden range-input" readonly >
                                <span class="range-title"><?php echo $houzez_local['price_range']; ?></span>
                            </div>
                            <div class="range-wrap col-md-9">
                                <span class="min-price-range"></span>
                                <div class="price-range-advanced"></div>
                                <span class="max-price-range"></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    add_shortcode('hz-advance-search-update', 'houzez_advance_search_update');
}

vc_map( array(
    "name"  =>  esc_html__( "Advanced Search", "houzez" ),
    "description"           => '',
    "base"                  => "hz-advance-search-update",
    'category'              => "By Favethemes",
    "class"                 => "",
    'admin_enqueue_js'      => "",
    'admin_enqueue_css'     => "",
    "icon"                  => "icon-advance-search",
    "params"                => array(
        array(
            "param_name" => "search_title",
            "type" => "textfield",
            "value" => '',
            "heading" => esc_html__("Title:", "houzez" ),
            "description" => esc_html__( "Enter section title", "houzez" ),
            "save_always" => true
        )
    )
) );

/**
 * Draw Map Search
 */
add_action('rest_api_init', 'register_api');
function register_api() {
    register_rest_route( 'v1', '/houzez_map_search', array(
      'methods' => 'GET',
      'callback' => 'houzez_map_search',
    ));

    register_rest_route( 'v1', '/houzez_map_listing', array(
      'methods' => 'POST',
      'callback' => 'houzez_map_listing',
    ));
}

function houzez_map_search() {
    global $wp_query;

    $status = $_GET['status'];
    $city = $_GET['city'];
    $lifestyle = $_GET['lifestyle'];
    $region = $_GET['region'];
    $type = $_GET['type'];
    $currency = $_GET['currency'];
    $min_price = $_GET['min_price'];
    $max_price = $_GET['max_price'];
    $target = $_GET['target'];

    $search_query = array(
        'post_type' => 'property',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    if ( !empty($status) ) {
        $tax_query[] = array(
            'taxonomy' => 'property_status',
            'field' => 'slug',
            'terms' => $status
        );
    }

    if ( !empty($city) ) {
        $tax_query[] = array(
            'taxonomy' => 'property_city',
            'field' => 'slug',
            'terms' => $city
        );
    }

    if ( !empty($lifestyle) ) {
        $tax_query[] = array(
            'taxonomy' => 'property_lifestyle',
            'field' => 'slug',
            'terms' => $lifestyle
        );
    }

    if ( !empty($region) ) {
        $tax_query[] = array(
            'taxonomy' => 'property_region',
            'field' => 'slug',
            'terms' => $region
        );
    }

    if ( !empty($type) ) {
        $tax_query[] = array(
            'taxonomy' => 'property_type',
            'field' => 'slug',
            'terms' => $type
        );
    }

    $tax_count = count($tax_query);

    if ($tax_count > 0) {
        $tax_query['relation'] = 'AND';

        $search_query['tax_query'] = $tax_query;
    }

    if ( !empty($currency) ) {
        $meta_query[] = array(
            'key' => 'fave_currency',
            'value' => $currency,
            'type' => 'CHAR',
            'compare' => '=',
        );
    }

    if ( !empty($min_price) && !empty($min_price) ) {
        $min_price = doubleval( houzez_clean( $min_price ) );
        $max_price = doubleval( houzez_clean( $max_price ) );

        if ( $min_price > 0 && $max_price > $min_price ) {
            $meta_query[] = array(
                'key' => 'fave_property_price',
                'value' => array($min_price, $max_price),
                'type' => 'NUMERIC',
                'compare' => 'BETWEEN',
            );
        }
    }

    $meta_count = count($meta_query);

    if ($meta_count > 0) {
        $meta_query['relation'] = 'AND';

        $search_query['meta_query'] = $meta_query;
    }

    $location_arr = array();
    $price_arr = array();
    $id_arr = array();

    $wp_query = new WP_Query( $search_query );

    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) : $wp_query->the_post();            
            $location = get_post_meta(get_the_ID(), 'fave_property_location', true);
            array_push($location_arr, $location);

            $price = get_post_meta(get_the_ID(), 'fave_property_price', true);
            $price = number_format ( $price , 0, '', ',' );

            $currency = get_post_meta(get_the_ID(), 'fave_currency', true);

            switch ($currency) {
                case 'EUR':
                    $price = '€' . $price;
                    break;
                case 'USD':
                    $price = '$' . $price;
                    break;
                case 'GBP':
                    $price = '£' . $price;
                    break;
                case 'XBT':
                    $price = '฿' . $price;
                    break;
                case '':
                    $price = '€' . $price;
                    break;
            }

            array_push($price_arr, $price);
            array_push($id_arr, get_the_ID());
        endwhile;
        wp_reset_postdata();
    } else {
       
    }

    $result = array(
        'location' => $location_arr,
        'price' => $price_arr,
        'id' => $id_arr
    );

    return $result;
}

function houzez_map_listing() {
    $result = array();

    $id_arr = $_POST['ids'];

    if (sizeof($id_arr) > 0) {
        for ($i = 0; $i < sizeof($id_arr); $i++) {
            $content = '';

            $content .= '<div id="ID-' . $id_arr[$i] .'" class="item-wrap infobox_trigger prop_addon">';
            $content .= '<div class="property-item-v2">';

            $content .= '<div class="figure-block">';
            $content .= '<figure class="item-thumb">';

            $week = get_post_meta($id_arr[$i], 'fave_week', true);
            if ($week == '1')
                $content .= '<span class="label-week label">Property of the Week</span>';

            $featured = get_post_meta($id_arr[$i], 'fave_featured', true);
            if ($featured == '1')
                $content .= '<span class="label-featured label">Featured</span>';

            $content .= get_the_post_thumbnail($id_arr[$i], 150, 120);
            $content .= '<ul class="actions">';
            $content .= '<li><span class="add_fav" data-placement="top" data-toggle="tooltip"';
            $content .= ' data-original-title="Favorite" data-propid="' . $id_arr[$i] . '">';
            $content .= '<i class="fa fa-heart"></i></span></li>';
            $content .= '<li><span data-toggle="tooltip" data-placement="top" title=""';
            $content .= ' data-original-title="(' . sizeof(get_post_meta($id_arr[$i], 'fave_property_images')) . ')">';
            $content .= '<i class="fa fa-camera"></i></span></li>';
            $content .= '</ul>';
            $content .= '</figure>';
            $content .= '</div>';

            $content .= '<div class="item-body">';
            $content .= '<div class="item-detail"><p>';
            $content .= wp_trim_words(get_post_field('post_content', $id_arr[$i]), 20);
            $content .= '</p></div>';
            $content .= '<div class="item-title"><h2 class="property-title">' . get_the_title($id_arr[$i]) .'</h2></div>';
            $content .= '<div class="item-info">';
            $content .= '<ul class="item-amenities">';

            $bed = get_post_meta($id_arr[$i], 'fave_property_bedrooms', true);
            $content .= '<li>';
            $content .= '<img src="' . get_stylesheet_directory_uri() . '/icons/rooms.png">';
            $content .= '<span>' . $bed . '</span>';
            $content .= '</li>';

            $bath = get_post_meta($id_arr[$i], 'fave_property_bathrooms', true);
            $content .= '<li>';
            $content .= '<img src="' . get_stylesheet_directory_uri() . '/icons/bathtub.png">';
            $content .= '<span>' . $bath . '</span>';
            $content .= '</li>';

            $size = get_post_meta($id_arr[$i], 'fave_property_size', true);
            $content .= '<li>';
            $content .= '<img src="' . get_stylesheet_directory_uri() . '/icons/house.png">';
            $content .= '<span>' . $size . ' m²</span>';
            $content .= '</li>';

            $content .= '<li><a target="_blank" href="' . get_the_permalink($id_arr[$i]) . '" class="btn btn btn-primary">';
            $content .= 'Details &gt;</a></li>';

            $content .= '</ul>';
            $content .= '</div>';
            $content .= '<div class="item-price-block"><span class="item-price">';

            $price = get_post_meta($id_arr[$i], 'fave_property_price', true);
            $price = number_format ( $price , 0, '', ',' );

            $currency = get_post_meta($id_arr[$i], 'fave_currency', true);

            switch ($currency) {
                case 'EUR':
                    $price = '€' . $price;
                    break;
                case 'USD':
                    $price = '$' . $price;
                    break;
                case 'GBP':
                    $price = '£' . $price;
                    break;
                case 'XBT':
                    $price = '฿' . $price;
                    break;
                case '':
                    $price = '€' . $price;
                    break;
            }

            $status = wp_get_post_terms($id_arr[$i], 'property_status', array('fields' => 'slugs'));
            $status = $status[0];

            if ($status == 'for-rent')
                $status = '/mo';
            else
                $status = '';

            $content .= $price . $status . '</span></div>';
            $content .= '</div>';

            $content .= '</div></div>';

            array_push($result, $content);
        }
    }

    return $result;
}

/**
 * Custom taxonomy for custom post type 'Property'
 */
add_action( 'admin_menu', 'remove_label_taxonomy', 999 );
function remove_label_taxonomy() {
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_label&amp;post_type=property');
    remove_meta_box('property_labeldiv', 'property', 'normal');
}

add_action('init', 'overwrite_theme_post_types', 1000);
function overwrite_theme_post_types() {
    $labels_lifestyle = array(
        'name' => __( 'Lifestyles', 'read' ),
        'singular_name' => __( 'Lifestyle', 'read' ),
        'search_items' =>  __( 'Search', 'read' ),
        'all_items' => __( 'All', 'read' ),
        'parent_item' => __( 'Parent Lifestyle', 'read' ),
        'parent_item_colon' => __( 'Parent Lifestyle:', 'read' ),
        'edit_item' => __( 'Edit', 'read' ),
        'update_item' => __( 'Update', 'read' ),
        'add_new_item' => __( 'Add New Lifestyle', 'read' ),
        'new_item_name' => __( 'New Lifestyle Name', 'read' ),
        'menu_name' => __( 'Lifestyles', 'read' )
    );

    register_taxonomy(
        'property_lifestyle', 'property',
        array(
            'hierarchical' => true,
            'labels' => $labels_lifestyle,
            'show_ui' => true,
            'public' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property_lifestyle'
            )
        )
    );

    $labels_region = array(
        'name' => __( 'Regions', 'read' ),
        'singular_name' => __( 'Region', 'read' ),
        'search_items' =>  __( 'Search', 'read' ),
        'all_items' => __( 'All', 'read' ),
        'parent_item' => __( 'Parent Region', 'read' ),
        'parent_item_colon' => __( 'Parent Region:', 'read' ),
        'edit_item' => __( 'Edit', 'read' ),
        'update_item' => __( 'Update', 'read' ),
        'add_new_item' => __( 'Add New Region', 'read' ),
        'new_item_name' => __( 'New Region Name', 'read' ),
        'menu_name' => __( 'Regions', 'read' )
    );

    register_taxonomy(
        'property_region', 'property',
        array(
            'hierarchical' => true,
            'labels' => $labels_region,
            'show_ui' => true,
            'public' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property_region'
            )
        )
    );

    $prop_city = array(
        'id' => 'fave_prop_region_meta',
        'title' => 'Property Region',
        'pages' => array('property_region'),
        'context' => 'normal',
        'fields' => array(),
        'local_images' => false,
        'use_with_theme' => false
    );

    $taxnow = isset($_REQUEST['taxonomy'])? $_REQUEST['taxonomy'] : '';

    $prop_city_meta =  new Tax_Meta_Class( $prop_city );
    $prop_city_meta->addImage('fave_prop_type_image',array('name'=> __('Thumbnail ','houzez')));

    if ($taxnow == 'property_region') {  
        $prop_city_meta->check_field_upload();
        $prop_city_meta->check_field_color();
        $prop_city_meta->check_field_date();
        $prop_city_meta->check_field_time();

        $plugin_path = plugins_url('houzez-theme-functionality/extensions/Tax-meta-class');
      
        wp_enqueue_style( 'tax-meta-clss', $plugin_path . '/css/Tax-meta-class.css' );

        wp_enqueue_script( 'tax-meta-clss', $plugin_path . '/js/tax-meta-clss.js', array( 'jquery' ), null, true );

    }
}

if ( !function_exists( 'houzez_get_property_lifestyle_meta' ) ):
    function houzez_get_property_lifestyle_meta( $term_id = false, $field = false ) {
        $defaults = array(
            'color_type' => 'inherit',
            'color' => '#bcbcbc',
            'ppp' => ''
        );

        if ( $term_id ) {
            $meta = get_option( '_houzez_property_lifestyle_'.$term_id );
            $meta = wp_parse_args( (array) $meta, $defaults );
        } else {
            $meta = $defaults;
        }

        if ( $field ) {
            if ( isset( $meta[$field] ) ) {
                return $meta[$field];
            } else {
                return false;
            }
        }
        return $meta;
    }
endif;

if ( !function_exists( 'houzez_property_lifestyle_add_meta_fields' ) ) :
    function houzez_property_lifestyle_add_meta_fields() {
        $houzez_meta = houzez_get_property_lifestyle_meta();
        ?>

        <div class="form-field">
            <label for="Color"><?php _e( 'Global Color', 'houzez'); ?></label><br/>
            <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label>
            <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
            <div id="fave_color_wrap">
                <p>
                    <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                </p>
                <?php if ( !empty( $colors ) ) { echo $colors; } ?>
            </div>
            <div class="clear"></div>
            <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
        </div>

        <?php
    }
endif;

add_action( 'property_lifestyle_add_form_fields', 'houzez_property_lifestyle_add_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_property_lifestyle_edit_meta_fields' ) ) :
    function houzez_property_lifestyle_edit_meta_fields( $term ) {
        $houzez_meta = houzez_get_property_lifestyle_meta( $term->term_id );
        ?>
        <?php

        $most_used = get_option( 'houzez_recent_colors' );

        $colors = '';

        if ( !empty( $most_used ) ) {
            $colors .= '<p>'.__( 'Recently used', 'houzez' ).': <br/>';
            foreach ( $most_used as $color ) {
                $colors .= '<a href="#" style="width: 20px; height: 20px; background: '.$color.'; float: left; margin-right:3px; border: 1px solid #aaa;" class="fave_colorpick" data-color="'.$color.'"></a>';
            }
            $colors .= '</p>';
        }

        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Color', 'houzez' ); ?></label></th>
            <td>
                <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label> <br/>
                <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
                <div id="fave_color_wrap">
                    <p>
                        <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                    </p>
                    <?php if ( !empty( $colors ) ) { echo $colors; } ?>
                </div>
                <div class="clear"></div>
                <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
            </td>
        </tr>

        <?php
    }
endif;

add_action( 'property_lifestyle_edit_form_fields', 'houzez_property_lifestyle_edit_meta_fields', 10, 2 );


if ( !function_exists( 'houzez_save_property_lifestyle_meta_fields' ) ) :
    function houzez_save_property_lifestyle_meta_fields( $term_id ) {

        if ( isset( $_POST['fave'] ) ) {

            $houzez_meta = array();

            $houzez_meta['color'] = isset( $_POST['fave']['color'] ) ? $_POST['fave']['color'] : 0;
            $houzez_meta['color_type'] = isset( $_POST['fave']['color_type'] ) ? $_POST['fave']['color_type'] : 0;

            update_option( '_houzez_property_lifestyle_'.$term_id, $houzez_meta );

            if ( $houzez_meta['color_type'] == 'custom' ) {
                houzez_update_recent_colors( $houzez_meta['color'] );
            }

            houzez_update_property_lifestyle_colors( $term_id, $houzez_meta['color'], $houzez_meta['color_type'] );
        }

    }
endif;

add_action( 'edited_property_lifestyle', 'houzez_save_property_lifestyle_meta_fields', 10, 2 );
add_action( 'create_property_lifestyle', 'houzez_save_property_lifestyle_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_update_property_lifestyle_colors' ) ):
    function houzez_update_property_lifestyle_colors( $cat_id, $color, $type ) {

        $colors = (array)get_option( 'fave_lifestyle_colors' );

        if ( array_key_exists( $cat_id, $colors ) ) {

            if ( $type == 'inherit' ) {
                unset( $colors[$cat_id] );
            } elseif ( $colors[$cat_id] != $color ) {
                $colors[$cat_id] = $color;
            }

        } else {

            if ( $type != 'inherit' ) {
                $colors[$cat_id] = $color;
            }
        }

        update_option( 'houzez_property_lifestyle_colors', $colors );

    }
endif;

if ( !function_exists( 'houzez_get_property_region_meta' ) ):
    function houzez_get_property_region_meta( $term_id = false, $field = false ) {
        $defaults = array(
            'color_type' => 'inherit',
            'color' => '#bcbcbc',
            'ppp' => ''
        );

        if ( $term_id ) {
            $meta = get_option( '_houzez_property_region_'.$term_id );
            $meta = wp_parse_args( (array) $meta, $defaults );
        } else {
            $meta = $defaults;
        }

        if ( $field ) {
            if ( isset( $meta[$field] ) ) {
                return $meta[$field];
            } else {
                return false;
            }
        }
        return $meta;
    }
endif;

if ( !function_exists( 'houzez_property_region_add_meta_fields' ) ) :
    function houzez_property_region_add_meta_fields() {
        $houzez_meta = houzez_get_property_region_meta();
        ?>

        <div class="form-field">
            <label for="Color"><?php _e( 'Global Color', 'houzez'); ?></label><br/>
            <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label>
            <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
            <div id="fave_color_wrap">
                <p>
                    <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                </p>
                <?php if ( !empty( $colors ) ) { echo $colors; } ?>
            </div>
            <div class="clear"></div>
            <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
        </div>

        <?php
    }
endif;

add_action( 'property_region_add_form_fields', 'houzez_property_region_add_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_property_region_edit_meta_fields' ) ) :
    function houzez_property_region_edit_meta_fields( $term ) {
        $houzez_meta = houzez_get_property_region_meta( $term->term_id );
        ?>
        <?php

        $most_used = get_option( 'houzez_recent_colors' );

        $colors = '';

        if ( !empty( $most_used ) ) {
            $colors .= '<p>'.__( 'Recently used', 'houzez' ).': <br/>';
            foreach ( $most_used as $color ) {
                $colors .= '<a href="#" style="width: 20px; height: 20px; background: '.$color.'; float: left; margin-right:3px; border: 1px solid #aaa;" class="fave_colorpick" data-color="'.$color.'"></a>';
            }
            $colors .= '</p>';
        }

        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Color', 'houzez' ); ?></label></th>
            <td>
                <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label> <br/>
                <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
                <div id="fave_color_wrap">
                    <p>
                        <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                    </p>
                    <?php if ( !empty( $colors ) ) { echo $colors; } ?>
                </div>
                <div class="clear"></div>
                <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
            </td>
        </tr>

        <?php
    }
endif;

add_action( 'property_region_edit_form_fields', 'houzez_property_region_edit_meta_fields', 10, 2 );


if ( !function_exists( 'houzez_save_property_region_meta_fields' ) ) :
    function houzez_save_property_region_meta_fields( $term_id ) {

        if ( isset( $_POST['fave'] ) ) {

            $houzez_meta = array();

            $houzez_meta['color'] = isset( $_POST['fave']['color'] ) ? $_POST['fave']['color'] : 0;
            $houzez_meta['color_type'] = isset( $_POST['fave']['color_type'] ) ? $_POST['fave']['color_type'] : 0;

            update_option( '_houzez_property_region_'.$term_id, $houzez_meta );

            if ( $houzez_meta['color_type'] == 'custom' ) {
                houzez_update_recent_colors( $houzez_meta['color'] );
            }

            houzez_update_property_region_colors( $term_id, $houzez_meta['color'], $houzez_meta['color_type'] );
        }

    }
endif;

add_action( 'edited_property_region', 'houzez_save_property_region_meta_fields', 10, 2 );
add_action( 'create_property_region', 'houzez_save_property_region_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_update_property_region_colors' ) ):
    function houzez_update_property_region_colors( $cat_id, $color, $type ) {

        $colors = (array)get_option( 'fave_region_colors' );

        if ( array_key_exists( $cat_id, $colors ) ) {

            if ( $type == 'inherit' ) {
                unset( $colors[$cat_id] );
            } elseif ( $colors[$cat_id] != $color ) {
                $colors[$cat_id] = $color;
            }

        } else {

            if ( $type != 'inherit' ) {
                $colors[$cat_id] = $color;
            }
        }

        update_option( 'houzez_property_region_colors', $colors );

    }
endif;

if ( ! function_exists( 'HOUZEZ_property_taxonomies_remove' ) ) {
    function HOUZEZ_property_taxonomies_remove (){
        unregister_widget( 'HOUZEZ_property_taxonomies' );
    }
    add_action( 'widgets_init', 'HOUZEZ_property_taxonomies_remove', 11 );

    require_once( get_stylesheet_directory(). '/houzez-property-taxonomies.php' );
}

function houzez_custom_menu_order() {
    global $submenu;

    $i = 0;
    $features = 0;
    $lifestyles = 0;
    $order = array();

    foreach ($submenu['edit.php?post_type=property'] as $item) {
        array_push($order, $item);

        if ($item[0] == 'Features')
            $features = $i;

        if ($item[0] == 'Lifestyles')
            $lifestyles = $i;

        $i++;
    }

    $lifestyle = $order[$lifestyles];

    for ($i = $lifestyles; $i > $features; $i--) {
        $order[$i] = $order[$i - 1];
    }

    $order[$features + 1] = $lifestyle;
    
    $submenu['edit.php?post_type=property'] = $order;
}

add_filter( 'custom_menu_order', 'houzez_custom_menu_order' );
add_filter( 'menu_order', 'houzez_custom_menu_order' );

/**
 *  Property Addon
 */
if ( !function_exists('houzez_property_addon') ) {
    function houzez_property_addon($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'hz_limit_post_number' => '',
            'hz_select_addon' => ''
        ), $atts));

        ob_start();

        global $paged;
        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        }

        if ($atts['hz_select_addon'] == 'fave_week')
            $css_classes = 'list-view';
        if ($atts['hz_select_addon'] == 'fave_featured')
            $css_classes = 'grid-view';

        $args = array(
            'order' => 'DESC',
            'orderby' => 'id',
            'post_status' => 'publish',
            'post_type' => 'property',
            'posts_per_page' => $atts['hz_limit_post_number'],
            'meta_key' => $atts['hz_select_addon'],
            'meta_value' => 1,
            'meta_compare' => '='
        );

        $the_query = new WP_Query($args);
        ?>

        <div id="properties_module_section" class="houzez-module property-item-module">
            <div id="properties_module_container">
                <div id="module_properties" class="property-listing <?php echo esc_attr($css_classes);?>">

                    <?php
                        if ($the_query->have_posts()) :
                            while ($the_query->have_posts()) : $the_query->the_post();
                                get_template_part('template-parts/property-for-addon');
                            endwhile;

                            wp_reset_postdata();
                        else:
                            get_template_part('template-parts/property', 'none');
                        endif;
                    ?>

                </div>
            </div>
        </div>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-property_addon', 'houzez_property_addon');
}

vc_map( array(
    "name"  =>  esc_html__( "Property Addon", "houzez" ),
    "description"           => '',
    "base"                  => "houzez-property_addon",
    'category'              => "By Favethemes",
    "class"                 => "",
    'admin_enqueue_js'      => "",
    'admin_enqueue_css'     => "",
    "icon"                  => "icon-addon-settings",
    "params"                => array(
        array(
            "param_name" => "hz_limit_post_number",
            "type" => "textfield",
            "value" => '',
            "heading" => esc_html__("Limit post number:", "houzez" ),
            "description" => esc_html__( "Enter limit post number", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "hz_select_addon",
            "type" => "dropdown",
            "value" => array( 'Featured Listing' => 'fave_featured', 'Property of the week' => 'fave_week' ),
            "heading" => esc_html__("Select Property Add On", "houzez" ),
            "save_always" => true
        ),
    )
) );



/**
 *  Add Regions to Houzez Grids
 */
vc_remove_element('hz-grids');

$houzez_grids_tax = array();

if (function_exists('vc_remove_param'))
    vc_remove_param('vc_row', 'font_color');
    
$houzez_grids_tax['Property Types'] = 'property_type';
$houzez_grids_tax['Property Status'] = 'property_status';
$houzez_grids_tax['Property Region'] = 'property_region';
$houzez_grids_tax['Property State'] = 'property_state';
$houzez_grids_tax['Property City'] = 'property_city';
$houzez_grids_tax['Property Neighborhood'] = 'property_area';

if( !function_exists('houzez_grid_update') ) {
    function houzez_grid_update($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'houzez_grid_type' => '',
            'houzez_grid_from' => '',
            'houzez_show_child' => '',
            'orderby'           => '',
            'order'             => '',
            'houzez_hide_empty' => '',
            'no_of_terms'       => '',
            'property_type' => '',
            'property_status' => '',
            'property_area' => '',
            'property_state' => '',
            'property_city' => '',
            'property_region' => ''
        ), $atts));

        ob_start();
        $module_type = '';
        $houzez_local = houzez_get_localization();

        $slugs = '';

        if( $houzez_grid_from == 'property_city' ) {
            $slugs = $property_city;

        } else if ( $houzez_grid_from == 'property_area' ) {
            $slugs = $property_area;

        } else if ( $houzez_grid_from == 'property_region' ) {
            $slugs = $property_region;

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

    add_shortcode('hz-grids-update', 'houzez_grid_update');
}

vc_map( array(
    "name"  =>  esc_html__( "Houzez Grids", "houzez" ),
    "description"           => 'Show Locations, Property Types, Cities, States in grid',
    "base"                  => "hz-grids-update",
    'category'              => "By Favethemes",
    "class"                 => "",
    'admin_enqueue_js'      => "",
    'admin_enqueue_css'     => "",
    "icon"                  => "icon-hz-grid",
    "params"                => array(

        array(
            "param_name" => "houzez_grid_type",
            "type" => "dropdown",
            "value" => array( 'Grid v1' => 'grid_v1', 'Grid v2' => 'grid_v2' ),
            "heading" => esc_html__("Choose Grid:", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "houzez_grid_from",
            "type" => "dropdown",
            "value" => $houzez_grids_tax,
            "heading" => esc_html__("Choose Taxonomy", "houzez" ),
            "save_always" => true
        ),
        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property Types", "houzez"),
            'taxonomy'      => 'property_type',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_type',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_type")),
            'save_always'   => true,
            'std'           => '',
        ),
        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property Status", "houzez"),
            'taxonomy'      => 'property_status',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_status',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_status")),
            'save_always'   => true,
            'std'           => '',
        ),
        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property Regions", "houzez"),
            'taxonomy'      => 'property_region',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_region',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_region")),
            'save_always'   => true,
            'std'           => '',
        ),
        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property States", "houzez"),
            'taxonomy'      => 'property_state',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_state',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_state")),
            'save_always'   => true,
            'std'           => '',
        ),
        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property Cities", "houzez"),
            'taxonomy'      => 'property_city',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_city',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_city")),
            'save_always'   => true,
            'std'           => '',
        ),

        array(
            'type'          => 'houzez_get_taxonomy_list',
            'heading'       => esc_html__("Property Areas", "houzez"),
            'taxonomy'      => 'property_area',
            'is_multiple'   => true,
            'is_hide_empty'   => false,
            'description'   => '',
            'param_name'    => 'property_area',
            "dependency" => Array("element" => "houzez_grid_from", "value" => array("property_area")),
            'save_always'   => true,
            'std'           => '',
        ),

        array(
            "param_name" => "houzez_show_child",
            "type" => "dropdown",
            "value" => array( 'No' => '0', 'Yes' => '1' ),
            "heading" => esc_html__("Show Child:", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "orderby",
            "type" => "dropdown",
            "value" => array( 'Name' => 'name', 'Count' => 'count', 'ID' => 'id' ),
            "heading" => esc_html__("Order By:", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "order",
            "type" => "dropdown",
            "value" => array( 'ASC' => 'ASC', 'DESC' => 'DESC' ),
            "heading" => esc_html__("Order:", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "houzez_hide_empty",
            "type" => "dropdown",
            "value" => array( 'Yes' => '1', 'No' => '0' ),
            "heading" => esc_html__("Hide Empty:", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "no_of_terms",
            "type" => "textfield",
            "value" => '',
            "heading" => esc_html__("Number of Items to Show:", "houzez" ),
            "save_always" => true
        )

    ) // end params
) );

/*
 * Widget Name: Property Add On: Property of the week
 */

function widget_content($args, $instance, $type) {
    global $before_widget, $after_widget, $before_title, $after_title, $post;
    extract( $args );

    $allowed_html_array = array(
        'div' => array(
            'id' => array(),
            'class' => array()
        ),
        'h3' => array(
            'class' => array()
        )
    );

    $title = apply_filters('widget_title', $instance['title'] );
    $items_num = $instance['items_num'];
    $widget_type = $instance['widget_type'];
    
    echo wp_kses( $before_widget, $allowed_html_array );

    if ($title) 
        echo wp_kses( $before_title, $allowed_html_array ) . $title . wp_kses( $after_title, $allowed_html_array );

    $wp_qry = new WP_Query(
        array(
            'post_type' => 'property',
            'posts_per_page' => $items_num,
            'meta_key' => $type,
            'meta_value' => '1',
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish'
        )
    );
    ?>
    
    <div class="widget-body">

        <?php if( $widget_type == "slider" ) { ?>
        <div class="property-widget-slider slide-animated owl-carousel owl-theme">
        <?php } else { ?>
        <div class="item-wrap infobox_trigger prop_addon">
        <?php } ?>

        <?php if ($wp_qry->have_posts()): while($wp_qry->have_posts()): $wp_qry->the_post(); ?>
            <?php $prop_featured = get_post_meta( get_the_ID(), 'fave_featured', true ); ?>
            <?php $prop_week = get_post_meta( get_the_ID(), 'fave_week', true ); ?>            
            <?php $prop_images = get_post_meta( get_the_ID(), 'fave_property_images', false ); ?>

            <?php if( $widget_type == "slider" ) { ?>
                <div class="item">
                    <div class="figure-block">
                        <figure class="item-thumb">
                            <?php if( $prop_featured != 0 ) { ?>
                                <span class="label-featured label label-success">
                                    <?php esc_html_e( 'Featured', 'houzez' ); ?>
                                </span>
                            <?php } ?>
                            <?php if( $prop_week == 1 ) { ?>
                                <span class="label-week label">
                                    <?php echo esc_html__( 'Property of the Week', 'houzez' ); ?>
                                </span>
                            <?php } ?>
                            <div class="label-wrap label-right">
                                <?php get_template_part('template-parts/listing', 'status' ); ?>
                            </div>

                            <a href="<?php the_permalink() ?>" class="hover-effect">
                                <?php
                                if( has_post_thumbnail( $post->ID ) ) {
                                    the_post_thumbnail( 'houzez-property-thumb-image' );
                                }else{
                                    houzez_image_placeholder( 'houzez-property-thumb-image' );
                                }
                                ?>
                            </a>
                            <figcaption class="thumb-caption">
                                <div class="cap-price pull-left"><?php echo houzez_listing_price(); ?></div>
                                <ul class="list-unstyled actions pull-right">
                                    <li>
                                        <span title="" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo count($prop_images); ?> <?php echo esc_html__('Photos', 'houzez'); ?>">
                                            <i class="fa fa-camera"></i>
                                        </span>
                                    </li>
                                </ul>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            <?php } else { ?>
                <div class="figure-block">
                    <figure class="item-thumb">
                        <?php if( $prop_featured != 0 ) { ?>
                                <span class="label-featured label label-success">
                                    <?php esc_html_e( 'Featured', 'houzez' ); ?>
                                </span>
                            <?php } ?>
                            <?php if( $prop_week == 1 ) { ?>
                                <span class="label-week label">
                                    <?php echo esc_html__( 'Property of the Week', 'houzez' ); ?>
                                </span>
                            <?php } ?>
                        <div class="label-wrap label-right">
                            <?php get_template_part('template-parts/listing', 'status' ); ?>
                        </div>

                        <a href="<?php the_permalink() ?>" class="hover-effect">
                            <?php
                            if( has_post_thumbnail( $post->ID ) ) {
                                the_post_thumbnail( 'houzez-property-thumb-image' );
                            }else {
                                houzez_image_placeholder( 'houzez-property-thumb-image' );
                            }
                            ?>
                        </a>
                        <figcaption class="thumb-caption clearfix">
                            <div class="cap-price pull-left"><?php echo houzez_listing_price(); ?></div>

                            <ul class="list-unstyled actions pull-right">
                                <li>
                                    <span title="" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo count($prop_images); ?> <?php echo esc_html__('Photos', 'houzez'); ?>">
                                        <i class="fa fa-camera"></i>
                                    </span>
                                </li>
                            </ul>
                        </figcaption>
                    </figure>
                </div>
                <div class="item-body">
                    <div class="item-detail">
                        <p><?php echo wp_trim_words( get_the_content(), 20 ); ?></p>
                    </div>

                    <div class="item-title">
                        <?php
                            echo '<h2 class="property-title">'. esc_attr( get_the_title() ). '</h2>';
                        ?>
                    </div>

                    <div class="item-info">
                        <?php 
                            $propID = get_the_ID();
                            $prop_bed     = get_post_meta( get_the_ID(), 'fave_property_bedrooms', true );
                            $prop_bath     = get_post_meta( get_the_ID(), 'fave_property_bathrooms', true );
                            $prop_size     = get_post_meta( $propID, 'fave_property_size', true );

                            if (empty($prop_bed)) $prop_bed = 0;
                            if (empty($prop_bath)) $prop_bath = 0;
                            if (empty($prop_size)) $prop_size = 0;
                        ?>
                        <ul class="item-amenities">
                            <li>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/rooms.png">
                                <span><?php echo $prop_bed; ?></span>
                            </li>
                            <li>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/bathtub.png">
                                <span><?php echo $prop_bath; ?></span>
                            </li>
                            <li>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/icons/house.png">
                                <span><?php echo $prop_size; ?> m²</span>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary btn-block">
                                    <?php echo esc_html__( 'Details >', 'houzez' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                     <div class="item-price-block">
                        <span class="item-price">
                            <?php echo houzez_listing_price_v1(); ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
        <?php endwhile; endif; ?>

        </div>
        <?php wp_reset_postdata(); ?>
        
    </div>


<?php 
    echo wp_kses( $after_widget, $allowed_html_array );
}
 
class HOUZEZ_property_week extends WP_Widget {
    /**
     * Register widget
    **/
    public function __construct() {
        
        parent::__construct(
            'houzez_property_week', // Base ID
            esc_html__( 'HOUZEZ: Property Add On: Property of the Week', 'houzez' ), // Name
            array( 'description' => esc_html__( 'Show property of the week', 'houzez' ), ) // Args
        );
        
    }
    /**
     * Front-end display of widget
    **/
    public function widget( $args, $instance ) {
        widget_content($args, $instance, 'fave_week');
    }
    /**
     * Sanitize widget form values as they are saved
    **/
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        /* Strip tags to remove HTML. For text inputs and textarea. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['items_num'] = strip_tags( $new_instance['items_num'] );
        $instance['widget_type'] = strip_tags( $new_instance['widget_type'] );
        
        return $instance;
    }
    /**
     * Back-end widget form
    **/
    public function form( $instance ) {
        /* Default widget settings. */
        $defaults = array(
            'title' => 'Property of the Week',
            'items_num' => '1',
            'widget_type' => 'entries'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        
    ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>"><?php esc_html_e('Maximum posts to show:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items_num' ) ); ?>" value="<?php echo esc_attr( $instance['items_num'] ); ?>" size="1" />
        </p>
        <p>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'slider')  echo 'checked="checked"'; ?> value="slider" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Display Properties as Slider', 'houzez' ); ?></label><br />

            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'entries') echo 'checked="checked"'; ?> value="entries" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>"><?php esc_html_e( 'Display Properties as List', 'houzez' ); ?></label>
        </p>
        
    <?php
    }

}

if ( ! function_exists( 'HOUZEZ_property_week_loader' ) ) {
    function HOUZEZ_property_week_loader (){
     register_widget( 'HOUZEZ_property_week' );
    }
     add_action( 'widgets_init', 'HOUZEZ_property_week_loader' );
}

/*
 * Widget Name: Property Add On: Featured Listing
 */
 
class HOUZEZ_featured_listing extends WP_Widget {
    /**
     * Register widget
    **/
    public function __construct() {
        
        parent::__construct(
            'houzez_featured_listing', // Base ID
            esc_html__( 'HOUZEZ: Property Add On: Featured Listing', 'houzez' ), // Name
            array( 'description' => esc_html__( 'Show featured listing', 'houzez' ), ) // Args
        );
        
    }
    /**
     * Front-end display of widget
    **/
    public function widget( $args, $instance ) {
        widget_content($args, $instance, 'fave_featured');
    }
    /**
     * Sanitize widget form values as they are saved
    **/
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        /* Strip tags to remove HTML. For text inputs and textarea. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['items_num'] = strip_tags( $new_instance['items_num'] );
        $instance['widget_type'] = strip_tags( $new_instance['widget_type'] );
        
        return $instance;
    }
    /**
     * Back-end widget form
    **/
    public function form( $instance ) {
        
        /* Default widget settings. */
        $defaults = array(
            'title' => 'Featured Listing',
            'items_num' => '5',
            'widget_type' => 'entries'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );    
    ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>"><?php esc_html_e('Maximum posts to show:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items_num' ) ); ?>" value="<?php echo esc_attr( $instance['items_num'] ); ?>" size="1" />
        </p>
        <p>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'slider')  echo 'checked="checked"'; ?> value="slider" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Display Properties as Slider', 'houzez' ); ?></label><br />

            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'entries') echo 'checked="checked"'; ?> value="entries" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>"><?php esc_html_e( 'Display Properties as List', 'houzez' ); ?></label>
        </p>
        
    <?php
    }

}

if ( ! function_exists( 'HOUZEZ_featured_listing_loader' ) ) {
    function HOUZEZ_featured_listing_loader (){
     register_widget( 'HOUZEZ_featured_listing' );
    }
     add_action( 'widgets_init', 'HOUZEZ_featured_listing_loader' );
}

/**
 * Footer Mortgage Calculator
 */
if ( ! function_exists( 'HOUZEZ_mortgage_calculator_remove' ) ) {
    function HOUZEZ_mortgage_calculator_remove (){
        unregister_widget( 'HOUZEZ_mortgage_calculator' );
    }
    add_action( 'widgets_init', 'HOUZEZ_mortgage_calculator_remove', 11 );

    require_once( get_stylesheet_directory() . '/houzez-mortgage-calculator.php' );
}

/**
 * Footer Mortgage Sitemap
 */
/* Add 2 widgets for footer */
add_action('widgets_init', 'houzez_add_widget', 20);
if( !function_exists('houzez_add_widget') ) {
    function houzez_add_widget() {
        register_sidebar(array(
            'name' => esc_html__('Footer Area 5', 'houzez'),
            'id' => 'footer-sidebar-5',
            'description' => esc_html__('Widgets in this area will be show in footer column five', 'houzez'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-top"><h3 class="widget-title">',
            'after_title' => '</h3></div>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Area 6', 'houzez'),
            'id' => 'footer-sidebar-6',
            'description' => esc_html__('Widgets in this area will be show in footer column six', 'houzez'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-top"><h3 class="widget-title">',
            'after_title' => '</h3></div>',
        ));
    }
}

/**
 * Featured Listing/Property of the Week
 */

/* -----------------------------------------------------------------------------------------------------------
 *  Make Property of the Week
 -------------------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_houzez_make_prop_week', 'houzez_make_prop_week');
add_action( 'wp_ajax_houzez_make_prop_week', 'houzez_make_prop_week' );

if( !function_exists('houzez_make_prop_week') ):
    function  houzez_make_prop_week(){
        global $current_user;
        wp_get_current_user();
        $userID =   $current_user->ID;

        $prop_id = intval( $_POST['propid'] );
        $post = get_post( $prop_id );

        if( $post->post_author != $userID ) {
            wp_die();
        } else {
            update_post_meta($prop_id, 'fave_week', 1);
            wp_die();
        }

    }
endif;

/* -----------------------------------------------------------------------------------------------------------
 *  Remove Property of the Week
 -------------------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_houzez_remove_prop_week', 'houzez_remove_prop_week');
add_action( 'wp_ajax_houzez_remove_prop_week', 'houzez_remove_prop_week' );

if( !function_exists('houzez_remove_prop_week') ):
    function  houzez_remove_prop_week(){
        global $current_user;
        wp_get_current_user();
        $userID =   $current_user->ID;

        $prop_id = intval( $_POST['propid'] );
        $post = get_post( $prop_id );

        if( $post->post_author != $userID ) {
            wp_die();
        } else {
            update_post_meta($prop_id, 'fave_week', 0);
            wp_die();
        }
        wp_die();
    }
endif;

/**
 * Package Creation
 */

// Use update_custom_metabox
add_action( 'wp_ajax_nopriv_houzez_remove_payment_option', 'houzez_remove_payment_option');
add_action( 'wp_ajax_houzez_remove_payment_option', 'houzez_remove_payment_option' );

if( !function_exists('houzez_remove_payment_option') ):
    function  houzez_remove_payment_option(){
        $postID = $_POST['postID'];
        $metaKey = $_POST['metaKey'];

        delete_post_meta($postID, $metaKey);

        wp_die();
    }
endif;

/**
 * Encrypt Document Upload
 */
add_action( 'wp_ajax_nopriv_houzez_doc_upload', 'houzez_doc_upload');
add_action( 'wp_ajax_houzez_doc_upload', 'houzez_doc_upload' );

function houzez_doc_upload() {
    $filename = $_FILES['file']['name'];

    $name = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $increment = '';

    $basename = '';

    if (file_exists('../Documents/' . $filename)) {
        $increment = 1;

        while(file_exists('../Documents/' . $name . '_' . $increment . '.' . $extension)) {
            $increment++;
        }

        $basename = '../Documents/' . $name . '_' . $increment . '.' . $extension;
    } else {
        $basename = '../Documents/' . $filename;
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $basename)) {
        echo "success";
    } else {
        echo "fail";
    }
}
?>