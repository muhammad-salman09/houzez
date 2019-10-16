<?php

global $current_user;

wp_get_current_user();
$userID = $current_user->ID;
$package_id = houzez_get_user_package_id( $userID );

$tax = 0;

if(!empty($package_id)) {
    $tax = (int)get_post_meta( $package_id, 'fave_package_tax', true );
}

$dashboard_package = houzez_get_template_link_2('template-user-dashboard-package.php');
$package = add_query_arg( array('option' => $_GET['option'], 'post' => $_GET['post']), $dashboard_package );

if (isset($_GET['state'])) {
    $value = explode(',', urldecode($_GET['state']));
    
    if ($value[0] == '15') {
        $addon = 'Featured:';
        $price = 15;
    }

    if ($value[0] == '25') {
        $addon = 'Property of the Week:';
        $price = 25;
    }
}

if ($_GET['option'] == 'featured') {
    $addon = 'Featured:';
    $price = 15;
}

if ($_GET['option'] == 'week') {
    $addon = 'Property of the Week:';
    $price = 25;
}

$total = $price * (100 + $tax) / 100;

?>
<h3 class="side-block-title"> <?php esc_html_e( 'Property Add On', 'houzez' ); ?> </h3>

<ul class="pkg-total-list">
    <li>
        <span id="houzez_package_name" class="pull-left"><?php echo $addon; ?></span>
        <span class="pull-right">
            <a href="<?php echo esc_url( $package ); ?>">
                <?php esc_html_e( 'Change Add On', 'houzez' ); ?>
            </a>
        </span>
    </li>
    <li>
        <span class="pull-left"><?php esc_html_e( 'Package Price:', 'houzez' ); ?></span>
        <span class="pull-right"><?php echo '€' . number_format( $price , 0, '', ',' ); ?>/week</span>
    </li>
    <?php if ($tax > 0) { ?>
    <li>
        <span class="pull-left"><?php echo esc_attr( $tax ); ?>%<?php esc_html_e( ' Tax:', 'houzez' ); ?></span>
        <span class="pull-right">
            <?php echo '€' . number_format( $price * $tax / 100, 2, '.', ',' ); ?>
        </span>
    </li>
    <?php } ?>
    <li>
        <span class="pull-left"><?php esc_html_e( 'Total Price:', 'houzez' ); ?></span>
        <span class="pull-right"><?php echo '€' . number_format( $total , 2, '.', ',' ); ?>/week</span>
    </li>
</ul>