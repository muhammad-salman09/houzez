<?php global $houzez_local; 
	$unique_id = esc_attr( uniqid( 'search-form-' ) );
?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<input value="<?php echo get_search_query(); ?>" name="s" id="<?php echo $unique_id; ?>" type="text" placeholder="<?php echo $houzez_local['blog_search']; ?>">
		<button type="submit"></button>
	</div>
</form>