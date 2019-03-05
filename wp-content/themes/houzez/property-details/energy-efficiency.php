<?php
global $post;
$energy_class = get_post_meta($post->ID, 'fave_energy_class', true);
$energy_global_index = get_post_meta($post->ID, 'fave_energy_global_index', true);
$renewable_energy_index = get_post_meta($post->ID, 'fave_renewable_energy_global_index', true);
$energy_performance = get_post_meta($post->ID, 'fave_energy_performance', true);
$energy_array = array('A+', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');

if(!empty($energy_class)) {
?>
<div id="energy_efficiency" class="detail-energy-efficiency detail-block target-block">
    <div class="detail-title">
        <h2 class="title-left"><?php esc_html_e( 'Energy Efficiency', 'houzez' ); ?></h2>
    </div>

    <div class="houzez-energy-container">

        
            <dl class="houzez-energy-table">
                                            
                <dt><?php esc_html_e('Energetic class', 'houzez'); ?></dt>
                <dd><?php esc_attr_e($energy_class); ?></dd>
            </dl>

            <dl class="houzez-energy-table">
                <dt><?php esc_html_e('Global energy performance index', 'houzez'); ?></dt>
                <dd><?php esc_attr_e($energy_global_index); ?></dd>
            </dl>
            
            <?php if(!empty($renewable_energy_index)) { ?>
            <dl class="houzez-energy-table">    
                    <dt><?php esc_html_e('Renewable energy performance index', 'houzez'); ?></dt>
                    <dd><?php esc_attr_e($renewable_energy_index); ?></dd>
            </dl>                    
            <?php } ?>
            
            <?php if(!empty($energy_performance)) { ?>
            <dl class="houzez-energy-table">
                    <dt><?php esc_html_e('Energy performance of the building', 'houzez'); ?></dt>
                    <dd><?php esc_attr_e($energy_performance); ?></dd>
            </dl>
            <?php } ?>

            <ul class="class-energy">

                <?php 
                foreach($energy_array as $energy) {
                    $indicator_energy = '';
                    if( $energy == $energy_class ) {
                        $indicator_energy = '<div class="indicator-energy" data-energyclass="'.$energy_class.'">'.$energy_global_index.' | '.esc_html__('Energy class', 'houzez').' '.$energy_class.'</div>';
                    }
                    echo '<li class="class-energy-indicator">
                        '.$indicator_energy.'
                        <span class="energy-'.$energy.'">'.$energy.'</span>
                    </li>';
                }
                ?>
                                        
            </ul>


    </div><!--.houzez-energy-container-->

    
</div><!--#energy_efficiency-->
<?php } ?>