<?php

if ( ! empty( $options['deferred-input'] ) && is_callable( $options['deferred-input'] ) ) {
	call_user_func( $options['deferred-input'], $options );
} elseif ( ! empty( $options['input_callback'] ) && is_callable( $options['input_callback'] ) ) {
	echo call_user_func($options['input_callback'],$options);
} else if(isset($options['input'])) {
	echo $options['input'];
}


if ( isset( $options['js-code'] ) ) {
	Better_Framework()->assets_manager()->add_admin_js( $options['js-code'] );
}

if ( isset( $options['css-code'] ) ) {
	Better_Framework()->assets_manager()->add_admin_css( $options['css-code'] );
}