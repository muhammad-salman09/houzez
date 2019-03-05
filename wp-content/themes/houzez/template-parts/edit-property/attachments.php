<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 04/05/17
 * Time: 8:12 PM
 */
global $prop_data, $is_multi_steps;
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php echo esc_html__('Attachments', 'houzez');?></h3>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="add-attachment">
                <div class="attach-list" id="houzez_property_attachments_container">
                    <?php
                    $property_attachs = get_post_meta( $prop_data->ID, 'fave_attachments', false );
                    $property_attachs = array_unique($property_attachs);

                    if( !empty($property_attachs[0])) {
                        foreach ($property_attachs as $prop_attach_id) {

                            $fullimage_url  = wp_get_attachment_image_src( $prop_attach_id, 'full' );
                            $attachment_title = get_the_title($prop_attach_id);

                            echo '<div class="media attach-thumb">';
                            echo '<div>';
                            echo '<div class="media-left">';
                            echo '<div class="attach-icon"><i class="fa fa-file-o"></i></div>';
                            echo '</div>';
                            echo '<div class="media-body">';
                            echo '<h4 class="media-heading"><a target="_blank" href="'.$fullimage_url[0].'">'.$attachment_title.'</a></h4>';
                            echo '<ul class="attach-actions">';
                            echo '<input type="hidden" class="propperty-attach-id" name="propperty_attachment_ids[]" value="' . intval($prop_attach_id) . '"/>' ;
                            echo '<li><a class="attachment-delete" data-attach-id="' . intval($prop_data->ID) . '"  data-attachment-id="' . intval($prop_attach_id) . '" href="javascript:;"><i class="fa fa-trash"></i></a>' ;
                            echo '<a style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></a>' ;
                            echo '</li>';
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    ?>
                </div>
                <div id="plupload-container"></div>
                <div id="houzez_errors"></div>
                <div id="houzez_attachment_dragDrop">
                    <a id="select_attachments" href="javascript:;" class="btn btn-primary"><?php esc_html_e( 'Select Attachment', 'houzez' ); ?></a>
                </div>
                <p><?php echo esc_html__('You can attach PDF files, Map images OR other documents to provide further details related to property.', 'houzez');?></p>
            </div>

        </div>
    </div>
</div>