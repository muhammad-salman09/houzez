<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/01/16
 * Time: 4:23 PM
 */
$allowed_html_array = array(
    'i' => array(
        'class' => array()
    ),
    'span' => array(
        'class' => array()
    ),
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array(),
        'data-toggle' => array(),
        'data-target' => array(),
        'class' => array(),
    )
);
?>
<div id="login_required" class="login-required-block detail-block target-block">
    
    <div class="alert alert-info">
        <?php
        echo wp_kses(__( 'To view this listing please <a class="hhh_login" href="#" data-toggle="modal" data-target="#pop-login">sign in</a>. Don’t you have an account? <a class="hhh_regis" href="#" data-toggle="modal" data-target="#pop-login">Register</a>', 'houzez' ), $allowed_html_array); 
        ?>
    </div>

</div>