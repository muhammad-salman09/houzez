<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/09/16
 * Time: 12:49 PM
 */
?>
<h3 class="side-block-title"> <?php esc_html_e( 'Pay Listing', 'houzez' ); ?> </h3>

<?php
$currency_symbol = houzez_option( 'currency_symbol' );
$where_currency = houzez_option( 'currency_position' );
$price_listing_submission = houzez_option('price_listing_submission');
$price_featured_submission = houzez_option('price_featured_listing_submission');
$price_per_submission = floatval($price_listing_submission);
$price_featured_submission = floatval($price_featured_submission);

$upgrade_id = isset( $_GET['upgrade_id'] ) ? $_GET['upgrade_id'] : '';
$prop_id = isset( $_GET['prop-id'] ) ? $_GET['prop-id'] : '';

if ( $where_currency == 'before' ) {
    $price_listing_submission_cn = $currency_symbol.' '.$price_listing_submission;
    $price_featured_submission_cn = $currency_symbol.' '.$price_featured_submission;
} else {
    $price_listing_submission_cn = $price_listing_submission.' '.$currency_symbol;
    $price_featured_submission_cn = $price_featured_submission.' '.$currency_symbol;
}
?>
<ul class="pkg-total-list">

    <?php if( !empty( $upgrade_id ) ) {

        $prop_featured = get_post_meta($upgrade_id, 'fave_featured', true);
            if ($prop_featured != 1) {
                ?>
                <li>
                    <span class="pull-left"><?php esc_html_e('Featured Fee:', 'houzez'); ?></span>
                    <span class="pull-right"><?php echo esc_attr($price_featured_submission_cn); ?></span>
                    <span class="submission_featured_price hidden"><?php echo $price_featured_submission; ?></span>
                </li>
            <?php } ?>

        <li>
            <span class="pull-left"><?php esc_html_e('Total Price:', 'houzez' ); ?></span>
            <span class="pull-right submission_total_price"><?php echo esc_attr( $price_featured_submission_cn ); ?></span>
        </li>

    <?php } else { ?>
    <li>
        <span class="pull-left"><?php esc_html_e('Submission Fee:', 'houzez' ); ?></span>
        <span class="pull-right"><?php echo esc_attr($price_listing_submission_cn); ?></span>
        <span class="submission_price hidden"><?php echo $price_per_submission; ?></span>
    </li>

    <?php if( !empty( $prop_id ) ) {
        $prop_featured = get_post_meta($prop_id, 'fave_featured', true);
        if ($prop_featured != 1) {
            ?>
            <li>
                <span class="pull-left"><?php esc_html_e('Featured Fee:', 'houzez'); ?></span>
                <span class="pull-right"><?php echo esc_attr($price_featured_submission_cn); ?></span>
                <span class="submission_featured_price hidden"><?php echo $price_featured_submission; ?></span>
            </li>
            <li>
                <span class="pull-left"><label for="prop_featured"><?php esc_html_e('Make Featured', 'houzez'); ?></label></span>
                <span class="pull-right"><input type="checkbox" class="prop_featured" name="prop_featured" id="prop_featured" value="1"></span>
            </li>
        <?php }
    }?>

    <li>
        <span class="pull-left"><?php esc_html_e('Total Price:', 'houzez' ); ?></span>
        <span class="pull-right submission_total_price"><?php echo esc_attr( $price_listing_submission_cn ); ?></span>
    </li>
    <?php } ?>
</ul>
