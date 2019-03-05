<?php
/**
 * Template Name: User Dashboard Invoices
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/09/16
 * Time: 11:00 PM
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}

global $houzez_local, $current_user, $dashboard_invoices;

wp_get_current_user();
$userID         = $current_user->ID;
$user_login     = $current_user->user_login;
$dashboard_invoices = houzez_get_template_link_2('template/user_dashboard_invoices.php');

get_header();

get_template_part( 'template-parts/dashboard', 'menu' );

if( isset( $_GET['invoice_id']) && !empty($_GET['invoice_id']) ) {
    $table_class = '';
} else {
    $table_class = 'dashboard-content-area-table';
}

global $paged;
if ( is_front_page()  ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

if( ! isset( $_GET['invoice_id']) ) {
    ob_start();

    $invoices_args = array(
        'post_type' => 'houzez_invoice',
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
                'key' => 'HOUZEZ_invoice_buyer',
                'value' => $userID,
                'compare' => '='
            )
        ),
        'paged' => $paged
    );

    $invoice_query = new WP_Query($invoices_args);
    $total = 0;
    if ($invoice_query->have_posts()) :
        while ($invoice_query->have_posts()) : $invoice_query->the_post();

            $fave_meta = houzez_get_invoice_meta(get_the_ID());
            get_template_part('template-parts/invoices');

            if(!empty($fave_meta['invoice_item_price'])) {
                $total += $fave_meta['invoice_item_price'];
            }

        endwhile; endif;

    $invoices_content = ob_get_contents();
    ob_end_clean();
    wp_reset_postdata();
}
?>
<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area dashboard-fix <?php esc_attr_e($table_class); ?>">
        
            <?php if( isset( $_GET['invoice_id']) && !empty($_GET['invoice_id']) ) {
                    get_template_part('template-parts/invoice-detail');
            } else { ?>
                

                <div class="invoice-area">
                    <div class="area-title">
                        <h2 class="title-left"><?php echo $houzez_local['search_invoices']; ?></h2>
                        <div class="title-right"><?php echo $houzez_local['total_invoices']; ?> <span id="invoices_total_price"><?php echo houzez_get_invoice_price($total); ?></span></div>
                    </div>
                    <div class="invoice-form">
                        <form>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="startDate"><?php echo $houzez_local['start_date']; ?></label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" id="startDate" class="input_date form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="endDate"><?php echo $houzez_local['end_date']; ?></label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" id="endDate" class="input_date form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="invoice_type"><?php echo $houzez_local['invoice_type']; ?></label>
                                        <select class="selectpicker" id="invoice_type" data-live-search="false" data-live-search-style="begins">
                                            <option value=""><?php echo $houzez_local['any']; ?></option>
                                            <option value="Listing"><?php echo $houzez_local['invoice_listing']; ?></option>
                                            <option value="package"><?php echo $houzez_local['invoice_package']; ?></option>
                                            <option value="Listing with Featured"><?php echo $houzez_local['invoice_feat_list']; ?></option>
                                            <option value="Upgrade to Featured"><?php echo $houzez_local['invoice_upgrade_list']; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="invoice_status"><?php echo $houzez_local['invoice_status']; ?></label>
                                        <select class="selectpicker" id="invoice_status" data-live-search="false" data-live-search-style="begins">
                                            <option value=""><?php echo $houzez_local['any']; ?></option>
                                            <option value="1"><?php echo $houzez_local['paid']; ?></option>
                                            <option value="0"><?php echo $houzez_local['not_paid']; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="invoice-list">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><?php echo $houzez_local['order']; ?></th>
                                <th><?php echo $houzez_local['date']; ?></th>
                                <th><?php echo $houzez_local['billing_for']; ?></th>
                                <th><?php echo $houzez_local['billing_type']; ?></th>
                                <th><?php echo $houzez_local['invoice_status']; ?></th>
                                <th><?php echo $houzez_local['payment_method']; ?></th>
                                <th><?php echo $houzez_local['total']; ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="invoices_content">
                            <?php echo $invoices_content; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <hr>

                <!--start Pagination-->
                <?php houzez_pagination( $invoice_query->max_num_pages, $range = 2 ); ?>
                <!--start Pagination-->

                    
            <?php } ?>
        
    </div>
</div>

<?php get_footer(); ?>