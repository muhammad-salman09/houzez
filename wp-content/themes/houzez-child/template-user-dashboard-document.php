<?php
/**
 * Template Name: User Dashboard Encrypt Document
 */

$page_link = houzez_get_template_link('template-user-dashboard-document.php');

if ( !is_user_logged_in() ) {
	$to_url = urlencode($page_link);

	$url = '/?to=' . $to_url;
	
	if (isset($_GET['encrypt']) && $_GET['encrypt'] != '')
	    $url .= '&sign=required';
	else
		$url .= '&login=required';

	wp_redirect(home_url() . $url);
}

$listing_id = '';

if (isset($_GET['listing_id']) && $_GET['listing_id'] != '') {
	$listing_id = $_GET['listing_id'];
}

if ($listing_id != '' && isset($_GET['file'])) {
    $filename = 'ftp://' . houzez_option('ftp_username'). ':' . houzez_option('ftp_password') . 
                '@' . houzez_option('ftp_url'). '/' . $listing_id . '/' . $_GET['file'];

	$contents = file_get_contents($filename);

    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($contents));
    header('Content-Disposition: attachment; filename="' . $_GET['file'] . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    ini_set('zlib.output_compression','0');

    readfile($filename);

    die($contents);
}

global $houzez_local, $current_user, $wpdb;

wp_get_current_user();
$userEmail = $current_user->user_email;

$metas = $wpdb->get_results("
	SELECT DISTINCT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '" . $userEmail . "'");

$docs = array();

foreach ($metas as $meta) {
	$property = get_post($meta->post_id);

	$author = $property->post_author;
	$author_name = get_the_author_meta('user_nicename', $author);

	$name = $property->post_title;

	$values = explode('/', $meta->meta_value);

	$title = $values[0];
	$date = $values[3];

	$id = $meta->post_id;
	$file = $values[1];

	array_push($docs, array($author_name, $name, $title, $date, $id, $file));
}

get_header();

get_template_part( 'template-parts/dashboard', 'menu' );

?>

<div class="user-dashboard-right dashboard-with-panel">

	<?php get_template_part( 'template-parts/dashboard-title' ); ?>

	<div class="dashboard-content-area dashboard-fix">
            <div class="container">
                <div class="row">
                	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                		<span><b>
                			If you are looking to add documents to your listing, from your listing profile, select Actions  and Document Upload.
                		</b></span>
                	</div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    	<?php if (sizeof($docs) > 0) { ?>
                    	<div class="doc_content container">
	                    	<table>
	                            <thead>
	                                <th>From</th>
	                                <th>Property</th>
	                                <th>Title</th>
	                                <th>Date</th>
	                                <th>Actions</th>
	                            </thead>
	                            <tbody>
	                            <?php foreach ($docs as $doc) { ?>
	                                <tr>
	                                    <td><?php echo $doc[0]; ?></td>
	                                    <td><?php echo $doc[1]; ?></td>
	                                    <td><?php echo $doc[2]; ?></td>
	                                    <td><?php echo date("d/m/Y", strtotime($doc[3])); ?></td>
	                                    <td>
	                                        <a href="<?php echo $page_link . '?listing_id=' . $doc[4] . '&file=' . $doc[5]; ?>" class="btn btn-primary">Download</a>
	                                    </td>
	                                </tr>
	                            <?php } ?>
	                            </tbody>
	                        </table>
	                    </div>
	                <?php } else { ?>
	                	<div class="container">
	                		<span>No documents have been shared with you at this time.</span>
	                	</div>
	                <?php } ?>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<?php get_footer(); ?>