<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 25/01/16
 * Time: 11:22 PM
 */
global $houzez_local, $prop_featured;
$prop_featured = get_post_meta( get_the_ID(), 'fave_featured', true );
?>
<?php if( $prop_featured == 1 ) { ?>
    <span class="label-featured label label-success"><?php echo esc_html__( 'Featured', 'houzez' ); ?></span>
<?php } ?>
