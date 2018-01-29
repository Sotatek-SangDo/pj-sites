<?php


if( ! function_exists( 'better_is_pagebuilder_used' ) ){
	/**
	 * Used to find current page uses VC for content or not!
	 *
	 * @return bool
	 */
	function better_is_pagebuilder_used(){

		global $post;

		$used = false;

		if( method_exists( 'WPBMap', 'getShortCodes') ){

			$valid_shortcodes = array();

			$registered_shortcodes = array_keys( WPBMap::getShortCodes() );

			if( is_array( $registered_shortcodes ) && ! empty( $registered_shortcodes ) ) {
				foreach( $registered_shortcodes as $short_code_name ){
					$valid_shortcodes[] = '[' .  $short_code_name;
				}
			}

			if( ! empty( $valid_shortcodes ) && better_strpos_array( $post->post_content, $valid_shortcodes ) === true ){
				$used = true;
			}

		}

		return $used;
	}
} // better_is_pagebuilder_used


if(! function_exists('bsbt_is_vc_frontend_editor')) {
	/**
	 * Hard code to checking VC frontend editor because of their shit code! >.<
	 *
	 * @return bool
	 */
	function bsbt_is_vc_frontend_editor() {
		return ! is_admin() && is_user_logged_in() &&
		       ! empty( $_GET['vc_editable'] ) && ! empty( $_GET['vc_post_id'] );
	}
}
