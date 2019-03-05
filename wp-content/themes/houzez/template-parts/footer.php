<?php 
if ( !is_active_sidebar( 'footer-sidebar-1' )
    && ! is_active_sidebar( 'footer-sidebar-2' )
    && ! is_active_sidebar( 'footer-sidebar-3' )
    && ! is_active_sidebar( 'footer-sidebar-4' ) )
    return;

$footer_cols = houzez_option('footer_cols');
if( $footer_cols == 'three_cols' ) {
    $f_3_classes = 'col-md-6 col-sm-12';
    $footer = 'footer footer-v2';
} else {
    $f_3_classes = 'col-md-3 col-sm-6';
    $footer = 'footer';
}
?>
<div class="<?php echo esc_attr( $footer ); ?>">
	<div class="container">
        <div class="row">

            <?php
            if( $footer_cols === 'one_col' ) {
                if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
                    echo '<div class="col-md-12 col-sm-12">';
                        dynamic_sidebar( 'footer-sidebar-1' );
                    echo '</div>';
                }
            } elseif( $footer_cols === 'two_col' ) {
                if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
                    echo '<div class="col-md-6 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-1' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-2' ) ) {
                    echo '<div class="col-md-6 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-2' );
                    echo '</div>';
                }
            } elseif( $footer_cols === 'three_cols_middle' ) {
                if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
                    echo '<div class="col-md-4 col-sm-12 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-1' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-2' ) ) {
                    echo '<div class="col-md-4 col-sm-12 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-2' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-3' ) ) {
                    echo '<div class="col-md-4 col-sm-12 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-3' );
                    echo '</div>';
                }
            } elseif( $footer_cols === 'three_cols' ) {
                if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
                    echo '<div class="col-md-3 col-sm-6 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-1' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-2' ) ) {
                    echo '<div class="col-md-3 col-sm-6 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-2' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-3' ) ) {
                    echo '<div class="col-md-6 col-sm-12 col-xs-12">';
                        dynamic_sidebar( 'footer-sidebar-3' );
                    echo '</div>';
                }
            } elseif( $footer_cols === 'four_cols' ) {
                if ( is_active_sidebar( 'footer-sidebar-1' ) ) {
                    echo '<div class="col-md-3 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-1' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-2' ) ) {
                    echo '<div class="col-md-3 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-2' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-3' ) ) {
                    echo '<div class="col-md-3 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-3' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'footer-sidebar-4' ) ) {
                    echo '<div class="col-md-3 col-sm-6">';
                        dynamic_sidebar( 'footer-sidebar-4' );
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>