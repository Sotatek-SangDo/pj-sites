<?php

$value = empty( $options['value'] ) ? '' : $options['value'];

$settings = empty( $options['settings'] ) ? array() : $options['settings'];

if( ! isset( $settings['textarea_name'] ) )
	$settings['textarea_name'] = $options['input_name'];

wp_editor( $value, $options['id'], $settings );