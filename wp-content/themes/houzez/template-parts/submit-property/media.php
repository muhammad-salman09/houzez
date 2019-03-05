<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/01/16
 * Time: 5:44 PM
 */
global $is_multi_steps;
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