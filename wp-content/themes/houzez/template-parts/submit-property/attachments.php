<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 04/05/17
 * Time: 7:04 PM
 */
global $is_multi_steps;
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php echo esc_html__('Attachments', 'houzez');?></h3>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="add-attachment">
                <div class="attach-list" id="houzez_property_attachments_container">
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