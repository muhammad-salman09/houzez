<?php
global $houzez_search_data;

$search_args = $houzez_search_data->query;

$search_args_decoded = unserialize( base64_decode( $search_args ) );
$search_uri = $houzez_search_data->url;
$search_uri = explode( '/?', $search_uri );
$search_uri = $search_uri[0];

$user_args = array ();

if ( isset( $search_args_decoded['s'] ) && !empty( $search_args_decoded['s'] ) ) {
    $user_args['keyword'] = $search_args_decoded['s'];
}
if ( isset( $search_args_decoded['date_query'] ) && is_array( $search_args_decoded['date_query'] ) ) {
    $search_day = $search_args_decoded['date_query'][2]['day'];
    $search_month = $search_args_decoded['date_query'][1]['month'];
    $search_year = $search_args_decoded['date_query'][0]['year'];
    $user_args['publish_date'] = $search_month . '/' . $search_day . '/' . $search_year;
}
?>
<div class="saved-search-block clearfix">
    <ul class="list-inline">
        <li>
            <div class="my-actions">
                <div class="btn-group">
                    <a data-propertyid='<?php echo intval($houzez_search_data->id); ?>' href="#" class="btn btn-action remove-search"><i class="fa fa-close"></i></a>
                </div>
            </div>
        </li>
    <li><strong><?php esc_html_e( 'Search Parameters:', 'houzez' ); ?></strong></li>
    
    <?php
    if ( isset( $search_args_decoded['s'] ) && !empty( $search_args_decoded['s'] ) ) {
        echo $search_args_decoded['s'] . ', ';
    } elseif ( isset( $search_args_decoded['meta_query'][0][0]['key'] ) && $search_args_decoded['meta_query'][0][0]['key'] == 'fave_property_map_address' ) {
        echo $search_args_decoded['meta_query'][0][0]['value'] . ', ';
    } elseif ( isset( $search_args_decoded['tax_query'][0][0]['taxonomy'] ) && $search_args_decoded['tax_query'][0][0]['taxonomy'] == 'property_area' ) {
        echo $search_args_decoded['tax_query'][0][0]['terms'][0] . ', ';
    }
    if( isset( $search_args_decoded['tax_query'] ) ) {
        foreach ($search_args_decoded['tax_query'] as $key => $val):

            global $user_args;
            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_city') {
                $page = get_term_by('slug', $val['terms'], 'property_city');
                $user_args['location'] = $val['terms'];
                if (!empty($page)) {
                    echo '<li><strong>' . esc_html__('Location', 'houzez') . ':</strong> ' . esc_attr( $page->name ). '</li> ';
                }
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_type') {
                $page = get_term_by('slug', $val['terms'], 'property_type');
                $user_args['type'] = $val['terms'];
                if (!empty($page)) {
                    echo '<li><strong>' . esc_html__('Type', 'houzez') . ':</strong> ' . esc_attr( $page->name ). '</li> ';
                }
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_status') {
                $page = get_term_by('slug', $val['terms'], 'property_status');
                $user_args['status'] = $val['terms'];
                if (!empty($page)) {
                    echo '<li><strong>' . esc_html__('Status', 'houzez') . ':</strong> ' . esc_attr( $page->name ). '</li> ';
                }
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_area') {
                $page = get_term_by('slug', $val['terms'], 'property_area');
                $user_args['area'] = $val['terms'];
                if (!empty($page)) {
                    echo '<li><strong>' . esc_html__('Neighborhood', 'houzez') . ':</strong> ' . esc_attr( $page->name ). '</li> ';
                }
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_state') {
                $page = get_term_by('slug', $val['terms'], 'property_state');
                $user_args['state'] = $val['terms'];
                if (!empty($page)) {
                    echo '<li><strong>' . esc_html__('State', 'houzez') . ':</strong> ' . esc_attr( $page->name ). '</li> ';
                }
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_feature') {
                $user_args['feature'] = $val['terms'];
            }

            if (isset($val['taxonomy']) && isset($val['terms']) && $val['taxonomy'] == 'property_label') {
                $user_args['label'] = $val['terms'];
            }

        endforeach;
    }

    $meta_query     = array();

    if ( isset( $search_args_decoded['meta_query'] ) ) :

        foreach ( $search_args_decoded['meta_query'] as $key => $value ) :

            if ( is_array( $value ) ) :

                if ( isset( $value['key'] ) ) :

                    $meta_query[] = $value;

                else :

                    foreach ( $value as $key => $value ) :

                        if ( is_array( $value ) ) :

                            foreach ( $value as $key => $value ) :

                                if ( isset( $value['key'] ) ) :

                                    $meta_query[]     = $value;

                                endif;

                            endforeach;

                        endif;

                    endforeach;

                endif;

            endif;

        endforeach;

    endif;

    if( isset( $meta_query ) && sizeof( $meta_query ) !== 0 ) {
        foreach ( $meta_query as $key => $val ) :

            if (isset($val['key']) && $val['key'] == 'fave_property_bedrooms') {
                $user_args['bedrooms'] = esc_attr( $val['value'] );
                echo '<li><strong>' . esc_html__('Bedrooms', 'houzez') . ':</strong> ' . esc_attr( $val['value'] ). '</li> ';
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_bathrooms') {
                $user_args['bathrooms'] = esc_attr( $val['value'] );
                echo '<li><strong>' . esc_html__('Bathrooms', 'houzez') . ':</strong> ' . esc_attr( $val['value'] ). '</li> ';
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_price') {
                if ( isset( $val['value'] ) && is_array( $val['value'] ) ) :
                    $user_args['min-price'] = $val['value'][0];
                    $user_args['max-price'] = $val['value'][1];
                    echo '<li><strong>' . esc_html__('Price', 'houzez') . ':</strong> ' . esc_attr( $val['value'][0] ).' - '.esc_attr( $val['value'][1]). '</li> ';
                else :
                    $user_args['max-price'] = $val['value'];
                    echo '<li><strong>' . esc_html__('Price', 'houzez') . ':</strong> ' . esc_attr( $val['value'] ).'</li> ';
                endif;
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_size') {
                if ( isset( $val['value'] ) && is_array( $val['value'] ) ) :
                    $user_args['min-area'] = $val['value'][0];
                    $user_args['max-area'] = $val['value'][1];
                    echo '<li><strong>' . esc_html__('Size', 'houzez') . ':</strong> ' . esc_attr( $val['value'][0] ).' - '.esc_attr( $val['value'][1]). '</li> ';
                else :
                    $user_args['max-area'] = $val['value'];
                    echo '<li><strong>' . esc_html__('Size', 'houzez') . ':</strong> ' . esc_attr( $val['value'] ).'</li> ';
                endif;
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_id') {
                $user_args['property_id'] = $val['value'];
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_country') {
                $user_args['country'] = $val['value'];
            }

            if (isset($val['key']) && $val['key'] == 'fave_property_country') {
                $user_args['country'] = $val['value'];
            }

        endforeach;
    }
    ?>

    <?php $houzez_site_name     = sprintf( "%s://%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'] ); ?>
    <li>
        <div class="my-actions">
            <div class="btn-group">
                <a target="_blank" href="<?php echo esc_url( add_query_arg( $user_args, $search_uri ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Search', 'houzez' ); ?> <i class="fa fa-angle-right"></i></a>
            </div>
        </div>
    </li>
    
    </ul>
</div>