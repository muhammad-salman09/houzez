<?php if( houzez_option('social-header') != '0' ): ?>
<div class="header-top-social">
    <ul>
        <?php if( houzez_option('hs-facebook') != '' ){ ?>
        <li> <a target="_blank" class="btn-facebook" href="<?php echo esc_url(houzez_option('hs-facebook')); ?>"><i class="fa fa-facebook-square"></i></a> </li>
        <?php } ?>

        <?php if( houzez_option('hs-twitter') != '' ){ ?>
            <li> <a target="_blank" class="btn-twitter" href="<?php echo esc_url(houzez_option('hs-twitter')); ?>"><i class="fa fa-twitter-square"></i></a> </li>
        <?php } ?>

        <?php if( houzez_option('hs-linkedin') != '' ){ ?>
            <li> <a target="_blank" class="btn-linkedin" href="<?php echo esc_url(houzez_option('hs-linkedin')); ?>"><i class="fa fa-linkedin-square"></i></a> </li>
        <?php } ?>

        <?php if( houzez_option('hs-googleplus') != '' ){ ?>
            <li> <a target="_blank" class="btn-google-plus" href="<?php echo esc_url(houzez_option('hs-googleplus')); ?>"><i class="fa fa-google-plus-square"></i></a> </li>
        <?php } ?>

        <?php if( houzez_option('hs-instagram') != '' ){ ?>
            <li> <a target="_blank" class="btn-instagram" href="<?php echo esc_url(houzez_option('hs-instagram')); ?>"><i class="fa fa-instagram"></i></a> </li>
        <?php } ?>

        <?php if( houzez_option('hs-pinterest') != '' ){ ?>
            <li><a target="_blank" class="btn-pinterest" href="<?php echo esc_url(houzez_option('hs-pinterest')); ?>"><i class="fa fa-pinterest-square"></i></a><li>
        <?php } ?>
            <?php if( houzez_option('hs-youtube') != '' ){ ?>
                <a target="_blank" class="btn-youtube" href="<?php echo esc_url(houzez_option('hs-youtube')); ?>"><i class="fa fa-youtube"></i></a>
            <?php } ?>
        <?php if( houzez_option('hs-yelp') != '' ){ ?>
            <a target="_blank" class="btn-yelp" href="<?php echo esc_url(houzez_option('hs-yelp')); ?>"><i class="fa fa-yelp"></i></a>
        <?php } ?>
    </ul>
</div>
<?php endif; ?>