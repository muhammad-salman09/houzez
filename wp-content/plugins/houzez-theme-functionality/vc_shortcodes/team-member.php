<?php
/*-----------------------------------------------------------------------------------*/
/*  Module 1
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_team') ) {
    function houzez_team($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'team_image' => '',
            'team_name' => '',
            'team_position' => '',
            'team_description' => '',
            'team_link' => '',
            'team_social_facebook' => '',
            'team_social_twitter' => '',
            'team_social_linkedin' => '',
            'team_social_pinterest' => '',
            'team_social_googleplus' => '',
            'team_social_facebook_target' => '',
            'team_social_twitter_target' => '',
            'team_social_linkedin_target' => '',
            'team_social_pinterest_target' => '',
            'team_social_googleplus_target' => ''
        ), $atts));

        ob_start();

        if(is_numeric($team_image)) {
            $team_image_src = wp_get_attachment_url( $team_image );
        } else {
            $team_image_src = $team_image;
        }

        if( !empty($team_image) ) {
            ?>

            <div class="team-block">
                <a class="team-block-mobile" href="<?php echo esc_url($team_link); ?>"></a>
                <img src="<?php echo esc_url($team_image_src); ?>" alt="<?php echo esc_attr($team_name); ?>" align="team">

                <div class="team-caption team-caption-before">
                    <div class="team-caption-inner">
                        <h4 class="team-name"><a href="<?php echo esc_url($team_link); ?>"><?php echo esc_attr($team_name); ?></a></h4>

                        <p class="team-designation"><?php echo esc_attr($team_position); ?></p>
                        <?php if( !empty($team_social_facebook) || !empty($team_social_twitter) || !empty($team_social_linkedin) || !empty($team_social_pinterest) || !empty($team_social_googleplus) ) { ?>
                            <ul class="team-social">
                                <?php
                                if( !empty($team_social_facebook) ) {
                                    echo '<li><a target="'.esc_attr($team_social_facebook_target).'" href="'.esc_url($team_social_facebook).'" class="btn-facebook"><i class="fa fa-facebook-square"></i></a></li>';
                                }
                                if( !empty($team_social_twitter) ) {
                                    echo '<li><a target="'.esc_attr($team_social_twitter_target).'" href="'.esc_url($team_social_twitter).'" class="btn-twitter"><i class="fa fa-twitter-square"></i></a></li>';
                                }
                                if( !empty($team_social_linkedin) ) {
                                    echo '<li><a target="'.esc_attr($team_social_linkedin_target).'" href="'.esc_url($team_social_linkedin).'" class="btn-linkedin"><i class="fa fa-linkedin-square"></i></a></li>';
                                }
                                if( !empty($team_social_pinterest) ) {
                                    echo '<li><a target="'.esc_attr($team_social_pinterest_target).'" href="'.esc_url($team_social_pinterest).'" class="btn-pinterest"><i class="fa fa-pinterest-square"></i></a></li>';
                                }
                                if( !empty($team_social_googleplus) ) {
                                    echo '<li><a target="'.esc_attr($team_social_googleplus_target).'" href="'.esc_url($team_social_googleplus).'" class="btn-google-plus"><i class="fa fa-google-plus-square"></i></a></li>';
                                }
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <div class="team-caption team-caption-after">
                    <div class="team-caption-inner">
                        <h4 class="team-name"><a href="<?php echo esc_url($team_link); ?>"><?php echo esc_attr($team_name); ?></a></h4>

                        <p class="team-designation"><a href="<?php echo esc_url($team_link); ?>"><?php echo esc_attr($team_position); ?></a></p>

                        <p class="team-description">
                            <?php echo esc_attr($team_description); ?>
                        </p>
                        <?php if( !empty($team_social_facebook) || !empty($team_social_twitter) || !empty($team_social_linkedin) || !empty($team_social_pinterest) || !empty($team_social_googleplus) ) { ?>
                            <ul class="team-social">
                                <?php
                                if( !empty($team_social_facebook) ) {
                                    echo '<li><a target="'.esc_attr($team_social_facebook_target).'" href="'.esc_url($team_social_facebook).'" class="btn-facebook"><i class="fa fa-facebook-square"></i></a></li>';
                                }
                                if( !empty($team_social_twitter) ) {
                                    echo '<li><a target="'.esc_attr($team_social_twitter_target).'" href="'.esc_url($team_social_twitter).'" class="btn-twitter"><i class="fa fa-twitter-square"></i></a></li>';
                                }
                                if( !empty($team_social_linkedin) ) {
                                    echo '<li><a target="'.esc_attr($team_social_linkedin_target).'" href="'.esc_url($team_social_linkedin).'" class="btn-linkedin"><i class="fa fa-linkedin-square"></i></a></li>';
                                }
                                if( !empty($team_social_pinterest) ) {
                                    echo '<li><a target="'.esc_attr($team_social_pinterest_target).'" href="'.esc_url($team_social_pinterest).'" class="btn-pinterest"><i class="fa fa-pinterest-square"></i></a></li>';
                                }
                                if( !empty($team_social_googleplus) ) {
                                    echo '<li><a target="'.esc_attr($team_social_googleplus_target).'" href="'.esc_url($team_social_googleplus).'" class="btn-google-plus"><i class="fa fa-google-plus-square"></i></a></li>';
                                }
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-team', 'houzez_team');
}
?>