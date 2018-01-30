<?php

// Default selected
$current = array(
    'key'   =>  '' ,
    'title' =>  __( 'Chose an Icon', 'better-studio' ) ,
    'width' =>  '',
    'height'=>  '',
    'type'  =>  '',
);

if( isset($options['value']) ){

    if( is_array( $options['value'] ) ){

        if( in_array( $options['value']['type'], array( 'custom-icon', 'custom' ) ) ){
            $current['key']     =  @$options['value']['icon'];
            $current['title']   =  bf_get_icon_tag( @$options['value'] ) . ' ' . __( 'Custom icon', 'better-studio' );
            $current['width']   =  @$options['value']['width'];
            $current['height']  =  @$options['value']['height'];
            $current['type']    =  'custom-icon';
        }else{
            Better_Framework::factory('icon-factory');

            $fontawesome  = BF_Icons_Factory::getInstance('fontawesome');

            if( isset( $fontawesome->icons[$options['value']['icon']] ) ){
                $current['key']     =  $options['value']['icon'];
                $current['title']   =  bf_get_icon_tag($options['value']) . $fontawesome->icons[$options['value']['icon']]['label'];
                $current['width']   =  $options['value']['width'];
                $current['height']  =  $options['value']['height'];
                $current['type']    =  'fontawesome';
            }
        }

    }elseif( ! empty( $options['value'] ) ){

        Better_Framework::factory('icon-factory');

        $fontawesome  = BF_Icons_Factory::getInstance('fontawesome');

        if( isset( $fontawesome->icons[$options['value']] ) ){
            $current['key']     =  $options['value'];
            $current['title']   =  bf_get_icon_tag($options['value']) . $fontawesome->icons[$options['value']]['label'];
            $current['width']   =  '';
            $current['height']  =  '';
            $current['type']    =  'fontawesome';
        }

    }

}

$icon_handler = 'bf-icon-modal-handler-' . rand( 1, 999999999 );

?>
<div class="bf-icon-modal-handler" id="<?php echo better_attr( $icon_handler ); ?>">

    <div class="select-options">
        <span class="selected-option"><?php echo $current['title']; ?></span>
    </div>

    <input type="hidden" class="icon-input" data-label="<?php //echo esc_attr( $current['title'] ); ?>" name="<?php echo esc_attr( $options['input_name'] ); ?>[icon]" value="<?php echo esc_attr( $current['key'] ); ?>" />
	<input type="hidden" class="icon-input-type" name="<?php echo esc_attr( $options['input_name'] ); ?>[type]" value="<?php echo esc_attr( $current['type'] ); ?>" />
	<input type="hidden" class="icon-input-height" name="<?php echo esc_attr( $options['input_name'] ); ?>[height]" value="<?php echo esc_attr( $current['height'] ); ?>" />
	<input type="hidden" class="icon-input-width" name="<?php echo esc_attr( $options['input_name'] ); ?>[width]" value="<?php echo esc_attr( $current['width'] ); ?>" />

</div><!-- modal handler container -->
<?php

bf_enqueue_modal( 'icon' );
