<?php
if(isset($_GET['action']) && ( $_GET['action'] == 'add-new' || $_GET['action'] == 'edit-currency')) {
	load_template( HOUZEZ_TEMPLATES . '/currency/form.php' );
} else {
	load_template( HOUZEZ_TEMPLATES . '/currency/currency-list.php' );
}
?>