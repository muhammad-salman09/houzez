<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 17/12/15
 * Time: 4:47 PM
 */

global $post, $prop_images, $houzez_local, $current_page_template, $taxonomy_name;
$disable_favorite = houzez_option('disable_favorite');
$disable_compare = houzez_option('disable_compare');
$disable_photo_count = houzez_option('disable_photo_count');

if( $disable_favorite != 0 || $disable_compare != 0 || $disable_photo_count != 0 ) {
?>
<ul class="actions">

    <?php if( $disable_favorite != 0 ) { ?>
    <li>

        <?php get_template_part( 'template-parts/favorite' ); ?>

    </li>
    <?php } ?>

        <?php //get_template_part( 'template-parts/share' ); ?>

    <?php if( $disable_photo_count != 0 ) { ?>
    <li>
        <span data-toggle="tooltip" data-placement="top" title="(<?php echo count( $prop_images ); ?>) <?php echo $houzez_local['photos']; ?>">
            <i class="fa fa-camera"></i>
        </span>
    </li>
    <?php } ?>

    <?php if( $disable_compare != 0 ) { ?>
    <li>
        <span id="compare-link-<?php echo esc_attr( $post->ID ); ?>" class="compare-property" data-propid="<?php echo esc_attr( $post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e( 'Compare', 'houzez' ); ?>">
            <i class="fa fa-plus"></i>
        </span>
    </li>
    <?php } ?>
</ul>
<?php } ?>