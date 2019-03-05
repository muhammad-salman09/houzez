<?php
$instance = null;

if ( Houzez_Currencies::is_edit_field() ) {
    $page_title = esc_html__( 'Update Currency', 'houzez-theme-functionality' );
    $button_title = esc_html__( 'Update', 'houzez-theme-functionality' );
    $instance = Houzez_Currencies::get_edit_field();
} else {
    $page_title = esc_html__( 'Create Currency', 'houzez-theme-functionality' );
    $button_title = esc_html__( 'Submit', 'houzez-theme-functionality' );
}
$add_currency = Houzez_Currencies::currency_add_link();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Currencies', 'houzez-theme-functionality');?></h1>
    <a href="<?php echo esc_url($add_currency);?>" class="page-title-action"><?php esc_html_e('Add New', 'houzez-theme-functionality');?></a>
    <hr class="wp-header-end">

    <form action="" method="POST">
        <div class="form-wrap">
            <?php
            echo Houzez::render_form_field( __( 'Currency Name', 'houzez-theme-functionality' ), 'hz_currency[name]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_name' ),
            ));


            echo Houzez::render_form_field( __( 'Currency Code', 'houzez-theme-functionality' ), 'hz_currency[code]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_code' ),
            ));

            echo Houzez::render_form_field( __( 'Currency Symbol', 'houzez-theme-functionality' ), 'hz_currency[symbol]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_symbol' ),
            ));

            echo Houzez::render_form_field(__( 'Currency Position', 'houzez-theme-functionality' ), 'hz_currency[position]', 'list', array(
                'values' => array('before' => esc_html__('Before', 'houzez'), 'after' => esc_html__('After', 'houzez')),
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_position' )
            ) );

            echo Houzez::render_form_field(__( 'Number of decimal points?', 'houzez-theme-functionality' ), 'hz_currency[decimals]', 'list', array(
                'values' => array( 
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ),
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_decimal' )
            ) );

            echo Houzez::render_form_field( __( 'Decimal Point Separator(eg: .)', 'houzez-theme-functionality' ), 'hz_currency[decimal_separator]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_decimal_separator' ),
            )); 

            echo Houzez::render_form_field( __( 'Thousands Separator(eg: ,)', 'houzez-theme-functionality' ), 'hz_currency[thousand_separator]', 'text', array(
                'required' => 'required',
                'value' => Houzez_Currencies::get_field_value( $instance, 'currency_thousand_separator' ),
            )); 
            ?>

            <input type="submit" class="button button-primary" value="<?php echo esc_attr($button_title);?>"/>
            <?php if ( ! empty( $instance['id'] ) ) : ?>
                <input type="hidden" name="hz_currency[id]" value="<?php echo $instance['id']; ?>"/>
            <?php endif; ?>

            <?php wp_nonce_field( 'houzez_currency_save_field', 'houzez_currency_save_field' ); ?>	
        </div>
    </form>
</div>