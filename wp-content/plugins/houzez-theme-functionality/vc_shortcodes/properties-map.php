<?php
/*-----------------------------------------------------------------------------------*/
/*	Properties
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_properties_map') ) {
    function houzez_properties_map($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'property_state' => '',
            'property_city' => '',
            'property_status' => '',
            'posts_limit' => ''
        ), $atts));

        ob_start();

        if( empty( $posts_limit ) ) {
            $posts_limit = -1;
        }

        $houzez_local = houzez_get_localization();

        $wp_query_args = array(
            'post_type' => 'property',
            'posts_per_page' => $posts_limit,
            'meta_query' => array(
                array(
                    'key' => 'fave_property_map_address',
                    'compare' => 'EXISTS'
                )
            )
        );

        $tax_query = array();

        if (!empty($property_type)) {
            $tax_query[] = array(
                'taxonomy' => 'property_type',
                'field' => 'slug',
                'terms' => $property_type
            );
        }
        if (!empty($property_status)) {
            $tax_query[] = array(
                'taxonomy' => 'property_status',
                'field' => 'slug',
                'terms' => $property_status
            );
        }
        if (!empty($property_state)) {
            $tax_query[] = array(
                'taxonomy' => 'property_state',
                'field' => 'slug',
                'terms' => $property_state
            );
        }
        if (!empty($property_city)) {
            $tax_query[] = array(
                'taxonomy' => 'property_city',
                'field' => 'slug',
                'terms' => $property_city
            );
        }

        $tax_count = count( $tax_query );

        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $wp_query_args['tax_query'] = $tax_query;
        }

        $prop_map_query = new WP_Query( $wp_query_args );
        $properties_data = array();

        if ( $prop_map_query->have_posts() ) :
            while ( $prop_map_query->have_posts() ) : $prop_map_query->the_post();

                $prop_images  = get_post_meta( get_the_ID(), 'fave_property_images', false );
                $prop_address = get_post_meta( get_the_ID(), 'fave_property_map_address', true );
                $prop_bed     = get_post_meta( get_the_ID(), 'fave_property_bedrooms', true );
                $prop_bath     = get_post_meta( get_the_ID(), 'fave_property_bathrooms', true );
                $property_location = get_post_meta( get_the_ID(),'fave_property_location',true);
                $prop_images        = get_post_meta( get_the_ID(), 'fave_property_images', false );
                $prop_featured       = get_post_meta( get_the_ID(), 'fave_featured', true );

                $current_prop_array = array();

                $current_prop_array['title'] = get_the_title();
                $current_prop_array['full_address'] = $prop_address;
                $current_prop_array['thumbnail'] = get_the_post_thumbnail( get_the_ID(), 'houzez-property-thumb-image' );
                $current_prop_array['url'] = get_permalink();
                $current_prop_array['prop_meta'] = houzez_listing_meta_v1();
                $current_prop_array['type'] = houzez_taxonomy_simple('property_type');
                $current_prop_array['images_count'] = count( $prop_images );
                $current_prop_array['price'] = houzez_listing_price_v1();

                if( $prop_featured != 0 ) {
                    $current_prop_array['is_featured'] = '<span class="label label-featured">'. $houzez_local['featured'].'</span>';
                } else {
                    $current_prop_array['is_featured'] = '';
                }

                $prop_type = wp_get_post_terms( get_the_ID(), 'property_type', array("fields" => "ids") );
                foreach( $prop_type as $term_id ) {
                    $icon = get_tax_meta( $term_id, 'fave_prop_type_icon');
                    $retinaIcon = get_tax_meta( $term_id, 'fave_prop_type_icon_retina');

                    if( !empty($icon['src']) ) {
                        $current_prop_array['icon'] = $icon['src'];
                    } else {
                        $current_prop_array['icon'] = get_template_directory_uri() . '/images/map/pin-single-family.png';
                    }
                    if( !empty($retinaIcon['src']) ) {
                        $current_prop_array['retinaIcon'] = $retinaIcon['src'];
                    } else {
                        $current_prop_array['retinaIcon'] = get_template_directory_uri() . '/images/map/pin-single-family.png';
                    }
                }


                /* Property Location */
                if(!empty($property_location)){
                    $lat_lng = explode(',',$property_location);
                    $current_prop_array['lat'] = $lat_lng[0];
                    $current_prop_array['lng'] = $lat_lng[1];
                }

                $properties_data[] = $current_prop_array;

            endwhile;
        endif;
        wp_reset_postdata();

        $map_cluster = houzez_option('map_cluster', '', 'url');
        if( !empty($map_cluster) ) {
            $clusterIcon = $map_cluster;
        } else {
            $clusterIcon = get_template_directory_uri() . '/images/map/cluster-icon.png';
        }

        ?>

        <script>
            (function($){
                var theMap;
                function initMap() {

                    var drgflag = true;
                    var houzez_is_mobile = false;
                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                        drgflag = false;
                        houzez_is_mobile = true;
                    }

                    /* Properties Array */
                    var properties = <?php echo json_encode( $properties_data ); ?>;

                    var google_map_style = HOUZEZ_ajaxcalls_vars.google_map_style;
                    var googlemap_default_zoom = HOUZEZ_ajaxcalls_vars.googlemap_default_zoom;
                    var googlemap_pin_cluster = HOUZEZ_ajaxcalls_vars.googlemap_pin_cluster;
                    var googlemap_zoom_cluster = HOUZEZ_ajaxcalls_vars.googlemap_zoom_cluster;

                    var myLatLng = new google.maps.LatLng(properties[0].lat,properties[0].lng);
                    //var myLatLng = new google.maps.LatLng('25.7617', '80.1918');

                    var houzezMapOptions = {
                        zoom: parseInt(googlemap_default_zoom),
                        maxZoom: 15,
                        center: myLatLng,
                        draggable: drgflag,
                        disableDefaultUI: false,
                        scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        scroll:{x:$(window).scrollLeft(),y:$(window).scrollTop()}
                    };
                    var theMap = new google.maps.Map(document.getElementById("houzez-listing-map-vc"), houzezMapOptions);

                    if( google_map_style !== '' ) {
                        var styles = JSON.parse ( google_map_style );
                        theMap.setOptions({styles: styles});
                    }

                    if (Modernizr.mq('only all and (max-width: 1000px)')) {
                        theMap.setOptions({'draggable': false});
                    }

                    var markers = new Array();
                    var current_marker = 0;
                    var visible;

                    var mapBounds = new google.maps.LatLngBounds();

                    for( i = 0; i < properties.length; i++ ) {

                        var marker_url = properties[i].icon;
                        var marker_size = new google.maps.Size( 44, 56 );
                        if( window.devicePixelRatio > 1.5 ) {
                            if ( properties[i].retinaIcon ) {
                                marker_url = properties[i].retinaIcon;
                                marker_size = new google.maps.Size( 84, 106 );
                            }
                        }

                        var marker_icon = {
                            url: marker_url,
                            size: marker_size,
                            scaledSize: new google.maps.Size( 44, 56 ),
                            origin: new google.maps.Point( 0, 0 ),
                            anchor: new google.maps.Point( 7, 27 )
                        };

                        // Markers
                        markers[i] = new google.maps.Marker({
                            map: theMap,
                            draggable: false,
                            position: new google.maps.LatLng( properties[i].lat,properties[i].lng ),
                            icon: marker_icon,
                            title: properties[i].title,
                            animation: google.maps.Animation.DROP,
                            visible: true
                        });

                        mapBounds.extend(markers[i].getPosition());

                        var infoBoxText = document.createElement("div");
                        infoBoxText.className = 'property-item item-grid map-info-box';
                        infoBoxText.innerHTML =
                            '<div class="figure-block">'+
                            '<figure class="item-thumb">'+
                            properties[i].is_featured +
                            '<div class="price hide-on-list">'+
                            properties[i].price +
                            '</div>'+
                            '<a href="'+properties[i].url+'" tabindex="0">'+
                            properties[i].thumbnail +
                            '</a>'+
                            '<figcaption class="thumb-caption cap-actions clearfix">'+
                            '<div class="pull-right">'+
                            '<span title="" data-placement="top" data-toggle="tooltip" data-original-title="Photos">'+
                            '<i class="fa fa-camera"></i> <span class="count">('+ properties[i].images_count +')</span>'+
                            '</span>'+
                            '</div>'+
                            '</figcaption>'+
                            '</figure>'+
                            '</div>' +
                            '<div class="item-body">' +
                            '<div class="body-left">' +
                            '<div class="info-row">' +
                            '<h2><a href="'+properties[i].url+'">'+properties[i].title+'</a></h2>' +
                            '<h4>'+properties[i].full_address+'</h4>' +
                            '</div>' +
                            '<div class="table-list full-width info-row">' +
                            '<div class="cell">' +
                            '<div class="info-row amenities">' +
                            properties[i].prop_meta +
                            '<p>'+properties[i].type+'</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';


                        var infoBoxOptions = {
                            content: infoBoxText,
                            disableAutoPan: true,
                            maxWidth: 0,
                            alignBottom: true,
                            pixelOffset: new google.maps.Size( -122, -48 ),
                            zIndex: null,
                            closeBoxMargin: "0 0 -16px -16px",
                            closeBoxURL: "<?php echo get_template_directory_uri() . '/images/map/close.png'; ?>",
                            infoBoxClearance: new google.maps.Size( 1, 1 ),
                            isHidden: false,
                            pane: "floatPane",
                            enableEventPropagation: false
                        };

                        var infobox = new InfoBox( infoBoxOptions );

                        attachInfoBoxToMarker( theMap, markers[i], infobox );

                    }

                    // Marker Clusters
                    if( googlemap_pin_cluster != 'no' ) {
                        var markerClustererOptions = {
                            ignoreHidden: true,
                            maxZoom: parseInt(googlemap_zoom_cluster),
                            styles: [{
                                textColor: '#ffffff',
                                url: "<?php echo $clusterIcon; ?>",
                                height: 48,
                                width: 48
                            }]
                        };

                        var markerClusterer = new MarkerClusterer(theMap, markers, markerClustererOptions);
                    }


                    theMap.fitBounds(mapBounds);

                    function attachInfoBoxToMarker( map, marker, infoBox ){
                        marker.addListener('click', function() {
                            var scale = Math.pow( 2, map.getZoom() );
                            var offsety = ( (100/scale) || 0 );
                            var projection = map.getProjection();
                            var markerPosition = marker.getPosition();
                            var markerScreenPosition = projection.fromLatLngToPoint( markerPosition );
                            var pointHalfScreenAbove = new google.maps.Point( markerScreenPosition.x, markerScreenPosition.y - offsety );
                            var aboveMarkerLatLng = projection.fromPointToLatLng( pointHalfScreenAbove );
                            map.setCenter( aboveMarkerLatLng );
                            infoBox.close();
                            infoBox.open( map, marker );
                        });
                    }


                }

                google.maps.event.addDomListener( window, 'load', initMap );
            })(jQuery)
        </script>

        <div id="houzez-gmap-vc-module">
            <div style="height: 550px; position: relative" id="houzez-listing-map-vc"></div>
        </div>


        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-properties-map', 'houzez_properties_map');
}
?>