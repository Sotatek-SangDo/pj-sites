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

		$icon_label = '';
		if( substr( $options['value'], 0, 3 ) == 'fa-' ){
			$icon_label = bf_get_icon_tag( $options['value'] ) . ' ' . $fontawesome->icons[$options['value']]['label'];
			$current['type']    =  'fontawesome';
		}else{
			$icon_label = bf_get_icon_tag( $options['value'] );
			$current['type']    =  'custom-icon';
		}

		$current['key']     =  $options['value'];
		$current['title']   =  $icon_label;
		$current['width']   =  '';
		$current['height']  =  '';

	}

}

$icon_handler = 'bf-icon-modal-handler-' . rand( 1, 999999999 );


?>
	<div class="bf-icon-modal-handler" id="<?php echo better_attr( $icon_handler ); ?>">

		<div class="select-options">
			<span class="selected-option"><?php echo $current['title']; ?></span>
		</div>

		<input type="hidden" class="wpb_vc_param_value wpb-textinput title textfield icon-input" data-label="<?php //echo esc_attr( $current['title'] ); ?>" name="<?php echo esc_attr( $options['input_name'] ); ?>" value="<?php echo esc_attr( $current['key'] ); ?>" />

	</div><!-- modal handler container -->
<?php

bf_enqueue_modal( 'icon' );
