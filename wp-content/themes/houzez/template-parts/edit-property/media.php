<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/01/16
 * Time: 5:44 PM
 */
global $prop_data, $is_multi_steps;
?>
<div class="account-block form-step-gal <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php esc_html_e( 'Property media', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row">
            <div class="property-media">
                <div class="media-gallery">
                    <div class="row">
                        <div id="houzez_property_gallery_container">
                            <?php
                            $property_images = get_post_meta( $prop_data->ID, 'fave_property_images', false );

                            $featured_image_id = get_post_thumbnail_id( $prop_data->ID );
                            $property_images[] = $featured_image_id;
                            $property_images = array_unique($property_images);

                            if( !empty($property_images[0])) {
                                foreach ($property_images as $prop_image_id) {

                                    $is_featured_image = ($featured_image_id == $prop_image_id);
                                    $featured_icon = ($is_featured_image) ? 'fa-star' : 'fa-star-o';

                                    $img_available = wp_get_attachment_image($prop_image_id, 'thumbnail');

                                    if( !empty($img_available)) {
                                        echo '<div class="col-sm-2 property-thumb">';
                                        echo '<figure class="gallery-thumb">';
                                        echo wp_get_attachment_image($prop_image_id, 'thumbnail');
                                        echo '<a class="icon icon-delete" data-property-id="' . intval($prop_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '" href="javascript:;">';
                                        echo '<i class="fa fa-trash-o"></i>';
                                        echo '</a>';
                                        echo '<a class="icon icon-fav icon-featured" data-property-id="' . intval($prop_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '" href="javascript:;">';
                                        echo '<i class="fa ' . esc_attr($featured_icon) . '"></i>';
                                        echo '</a>';
                                        echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval($prop_image_id) . '">';
                                        echo '<span style="display: none;" class="icon icon-loader">';
                                        echo '<i class="fa fa-spinner fa-spin"></i>';
                                        echo '</span>';

                                        if ($is_featured_image) {
                                            echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                                        }
                                        echo '</figure>';
                                        echo '</div>';
                                    }
                                }
                            }
                            ?>
                         </div>
                    </div>
                </div>
                <div id="houzez_gallery_dragDrop" class="media-drag-drop">
                    <span class="icon-cloud-upload text-primary"><i class="fa fa-cloud-upload"></i></span>
                    <h4 class="drag-title"><?php esc_html_e( 'Drag and drop images here', 'houzez' ); ?></h4>
                    <a id="select_gallery_images" href="javascript:;" class="btn btn-primary"><?php esc_html_e( 'Select Images', 'houzez' ); ?></a>
                </div>
                <div id="plupload-container"></div>
                <div id="houzez_errors"></div>
            </div>
        </div>

    </div>
</div>
