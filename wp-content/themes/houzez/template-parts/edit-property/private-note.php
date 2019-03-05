<?php
/**
 * Created by PhpStorm.
 * User: Waqas Riaz
 * Date: 12/12/16
 * Time: 5:51 PM
 * Since v1.5.0
 */
global $prop_data, $is_multi_steps;
$private_note = get_post_meta( $prop_data->ID, 'fave_private_note', true );
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php esc_html_e( 'Private Note', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <textarea class="form-control" name="private_note" id="private_note" rows="6" placeholder="<?php esc_html_e('Write private note for this property, it will not display for public.', 'houzez');?>"><?php if( isset($private_note) && !empty($private_note) ) { echo esc_attr( $private_note ); }?></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

