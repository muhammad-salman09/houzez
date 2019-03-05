<?php
$instance = null;

if ( Houzez_Fields_Builder::is_edit_field() ) {
    $page_title = esc_html__( 'Update field', 'houzez-theme-functionality' );
    $button_title = esc_html__( 'Update', 'houzez-theme-functionality' );
    $instance = Houzez_Fields_Builder::get_edit_field();
} else {
    $page_title = esc_html__( 'Create field', 'houzez-theme-functionality' );
    $button_title = esc_html__( 'Submit', 'houzez-theme-functionality' );
}
$add_new = Houzez_Fields_Builder::field_add_link();
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Fields Builder', 'houzez-theme-functionality');?></h1>
    <a href="<?php echo esc_url($add_new);?>" class="page-title-action"><?php esc_html_e('Add New', 'houzez-theme-functionality');?></a>
    <hr class="wp-header-end">
    <form action="" method="POST">
        <div class="form-wrap">
            <?php

            echo Houzez::render_form_field( esc_html__( 'Field Name', 'houzez-theme-functionality' ), 'hz_fbuilder[label]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Fields_Builder::get_field_value( $instance, 'label' ),
            ));

            echo Houzez::render_form_field( esc_html__( 'Placeholder', 'houzez-theme-functionality' ), 'hz_fbuilder[placeholder]', 'text', array(
                'value' => Houzez_Fields_Builder::get_field_value( $instance, 'placeholder' ),
            ));


            echo Houzez::render_form_field(esc_html__( 'Type', 'houzez-theme-functionality' ), 'hz_fbuilder[type]', 'list', array(
                'values' => Houzez_Fields_Builder::get_field_types(),
                'placeholder' => esc_html__( '-- Choose field type --', 'houzez-theme-functionality' ),
                'required' => 'required',
                'value' => Houzez_Fields_Builder::get_field_value( $instance, 'type' ),
                'class' => 'houzez-fbuilder-js-on-change',
            ) );

            echo '<div class="houzez_select_options_loader_js">';
            if($instance['type'] == 'select') {
                include HOUZEZ_TEMPLATES . '/fields-builder/multiple.php';
            }
            echo '</div>';
            
            echo Houzez::render_form_field(esc_html__( 'Add in Search?', 'houzez-theme-functionality' ), 'hz_fbuilder[is_search]', 'list', array(
                'values' => array('no' => esc_html__('No', 'houzez'), 'yes' => esc_html__('Yes', 'houzez')),
                'required' => 'required',
                'value' => Houzez_Fields_Builder::get_field_value( $instance, 'is_search' )
            ) );

            echo Houzez::render_form_field( esc_html__( 'Icon (only for luxury homes layout)', 'houzez-theme-functionality' ), 'hz_fbuilder[icon]', 'text', array(
                'id' => 'c_icon',
                'value' => Houzez_Fields_Builder::get_field_value( $instance, 'options' ),
            )); 

            ?>
            <input id="upload_icon_button" type="button" class="button" value="<?php _e( 'Upload Icon', 'houzez' ); ?>" />
            <input type='hidden' name='hz_fbuilder[icon_attachment_id]' id='icon_attachment_id' value=''>


            <input type="submit" class="button button-primary" value="<?php echo esc_attr($button_title);?>"/>
            <?php if ( ! empty( $instance['id'] ) ) : ?>
                <input type="hidden" name="hz_fbuilder[id]" value="<?php echo $instance['id']; ?>"/>
            <?php endif; ?>

            <?php wp_nonce_field( 'houzez_fbuilder_save_field', 'houzez_fbuilder_save_field' ); ?>	
        </div>
    </form>
</div>