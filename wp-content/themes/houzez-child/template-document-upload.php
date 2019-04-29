<?php
/**
 * Template Name: Document Upload
 */

if (isset($_GET['file'])) {
    $filename = 'ftp://' . houzez_option('ftp_username'). ':' . houzez_option('ftp_password') . 
                '@' . houzez_option('ftp_url'). '/' . $_GET['file'];  

    $contents = file_get_contents($filename);

    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($contents));
    header('Content-Disposition: inline; filename="' . $_GET['file'] . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    ini_set('zlib.output_compression','0');

    die($contents);
}

global $houzez_local, $current_user;

wp_get_current_user();

$userID = $current_user->ID;
$docs = array();

for ($i = 1; $i < 6; $i++) {
    $doc = get_user_meta($userID, 'package_doc' . $i, true);

    if ($doc != '')
        array_push($docs, $doc);
}

$paid_submission_type = esc_html ( houzez_option('enable_paid_submission','') );
if( $paid_submission_type != 'membership' ) {
    wp_redirect( home_url() );
}
if ( !is_user_logged_in() ) {
    wp_redirect( home_url() );
}

$pack_id = '';

if (isset($_GET['selected_package']) && $_GET['selected_package'] != '') {
	$pack_id = $_GET['selected_package'];
} else {
	wp_redirect( home_url() );
}

$payment_page_link = houzez_get_template_link('template-advanced-payment.php');

$payment_page_link = add_query_arg( 'selected_package', $pack_id, $payment_page_link );

get_header();

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

	<?php get_template_part( 'template-parts/dashboard-title' ); ?>

	<div class="dashboard-content-area">
        <div class="container">

            <?php get_template_part('template-parts/create-listing-top'); ?>

            <div class="row">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="houzez-module package-table-module">
                    	<?php
                        if( have_posts() ):
                            while( have_posts() ): the_post();
                                $content = get_the_content();
                            endwhile;
                         endif;

                        wp_reset_postdata();

                        if( !empty($content) ) {
                            the_content();
                        } else {
                        ?>
                        <div class="container">
                        	<div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                        		<label><?php echo esc_html_e('Title of Document'); ?></label>
                        		<input type="text" class="form-control" id="doc_title" />
                        	</div>
                        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        		<label><?php echo esc_html_e('Upload PDF (Must be under 10MB)'); ?></label>
                        		<input type="file" class="form-control" id="doc_file" name="doc" />
                        	</div>
                        	<div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                        		<button type="button" class="btn btn-primary btn-lg btn-upload">
                        			<?php echo esc_html_e('File Upload'); ?>
                        		</button>
                        	</div>
                        </div>

                        <div class="doc_content container">
                        	<p>
                             <?php
                                if (sizeof($docs) > 0)
                                    echo 'List Encrypted files (' . sizeof($docs) . ' of 5)';
                             ?>   
                            </p>
                        	<div>
                            <?php
                                foreach ($docs as $doc) {
                                    $val = explode('/', $doc);
                            ?>
                                <p>
                                    <span><?php echo $val[0]; ?></span>&nbsp;
                                    ( <a href="javascript:void(0);" class="doc_view">View</a> /&nbsp;
                                    <a href="javascript:void(0);" class="doc_remove">Remove</a> )
                                    <input type="hidden" value="<?php echo $val[1]; ?>" />
                                </p>
                            <?php
                                }
                            ?>
                            </div>
                        </div>

                		<a href="<?php echo esc_url($payment_page_link); ?>" class="btn btn-primary btn-lg step">
			            	<?php echo esc_html_e('Done'); ?>
			            </a>
                        <?php
                        }
	                    ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>