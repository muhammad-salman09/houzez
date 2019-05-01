<?php
/**
 * Template Name: Document Upload
 */

global $houzez_local, $current_user;

wp_get_current_user();
$userID = $current_user->ID;

$listing_id = '';

if (isset($_GET['listing_id']) && $_GET['listing_id'] != '' &&
    ($userID != 0 || (isset($_GET['uname']) && $_GET['uname'] != ''))) {
	$listing_id = $_GET['listing_id'];
} else if (isset($_GET['sign']) && $_GET['sign'] == 'required') {
    $current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $to_url = urlencode(substr($current_url, 0, strlen($current_url) - 14));

    $url = '/?sign=required&to=' . $to_url;

    wp_redirect(home_url() . $url);
} else {
	wp_redirect( home_url() );
}

if (isset($_GET['file'])) {
    $filename = 'ftp://' . houzez_option('ftp_username'). ':' . houzez_option('ftp_password') . 
                '@' . houzez_option('ftp_url'). '/' . $listing_id . '/' . $_GET['file'];  

    $contents = file_get_contents($filename);

    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($contents));
    header('Content-Disposition: inline; filename="' . $_GET['file'] . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    ini_set('zlib.output_compression','0');

    die($contents);
}

$docs = array();

for ($i = 1; $i < 6; $i++) {
    $doc = get_post_meta($listing_id, 'doc' . $i, true);

    if ($doc != '')
        array_push($docs, $doc);
}

get_header();

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

	<?php get_template_part( 'template-parts/dashboard-title' ); ?>

	<div class="dashboard-content-area">
        <div class="container">

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
                            <?php
                                if (sizeof($docs) > 0) {
                            ?>
                            <table>
                                <thead>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>Share Email</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 1;
                                    foreach ($docs as $doc) {
                                        $val = explode('/', $doc);
                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $val[0]; ?></td>
                                        <td><?php echo $val[1]; ?></td>
                                        <td>
                                            <input type="text" class="share_email">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="doc_view">View</a> /&nbsp;
                                            <a href="javascript:void(0);" class="doc_remove">Remove</a> /&nbsp;
                                            <a href="javascript:void(0);" class="doc_share">Share</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                                }
                            ?>
                        </div>
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