<?php

if ( ! class_exists( 'BSBT_Version_Compatibility' ) ) {

	class BSBT_Version_Compatibility {

		/**
		 * Contains main compatibility log option ID
		 *
		 * @var string
		 */
		private $option_id = 'bs-theme-comp-info';

		/**
		 * live instance of class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Library config  array {
		 *
		 * }
		 *
		 * @var array
		 */
		private $config = array();


		public static function get_instance() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * BSBT_Version_Compatibility constructor.
		 */
		function __construct() {
			add_action( 'better-framework/after_setup', array( $this, 'do_compatibility' ), 1 );
		}

		/**
		 * Get information about compatibility situation
		 *
		 * @return array none empty array on success. array {
		 * @type string $active  active theme version number
		 * @type array  $history theme updates history array{
		 *     key:version number => value: updated time stamp
		 *      ...
		 *    }
		 *
		 * }
		 */
		public function get_compatibility_info() {
			if ( $info = get_option( $this->option_id, array() ) ) {
				return $info;
			}

			//First installation
			$this->set_default_compatibility_info();

			return get_option( $this->option_id, array() );
		}

		public function set_current_theme_version() {
			update_option( 'bs-theme-version', $this->WP_Theme_Object()->get( 'Version' ) );
		}

		public function get_current_theme_version() {
			if ( $version = get_option( 'bs-theme-version' ) ) {
				return $version;
			}
			$this->set_current_theme_version();

			return get_option( 'bs-theme-version' );
		}

		protected function WP_Theme_Object() {
			if ( is_callable( 'BF' ) ) {
				return BF()->theme();
			}

			return wp_get_theme( get_template() );
		}

		/**
		 * Set compatibility status array
		 *
		 * @param array $comp_info {@see get_compatibility_info return value}
		 */
		public function set_compatibility_info( $comp_info ) {

			$comp_info = wp_parse_args( $comp_info );
			update_option( $this->option_id, $comp_info, true );
		}

		protected function set_default_compatibility_info() {
			$this->set_compatibility_info( array(
				'history' => array(),
				'comp'    => array(),
			) );

			$this->set_current_theme_version();
		}

		/**
		 * Logs theme versions and make theme compatible with latest version
		 */
		public function do_compatibility() {

			/**
			 * Config version compatibility array {
			 * @type array $products the product information array{
			 *
			 *   key: unique-id => value: array(
			 *      'active-version' => current version of the product
			 *    }
			 *    ...
			 *  }
			 *
			 * @type array ${'compatibility-actions'}  the product update actions array {
			 *
			 *   key: unique-id => value: array(
			 *      'version number' => custom callback
			 *    }
			 *    ...
			 * }
			 * }
			 *
			 */
			$this->config = apply_filters( 'better-template/version-compatibility/config', array( 'compatibility-actions' => array() ) );

			if ( empty( $this->config['products'] ) ) {
				return false;
			}


			$comp_info   = $this->get_compatibility_info();
			$must_update = false;
			$history     = &$comp_info['history'];
			$comp        = &$comp_info['comp'];

			if ( ! empty( $this->config['compatibility-actions'] ) ) {

				foreach ( $this->config['compatibility-actions'] as $product_id => $list_of_updates ) {
					$product_info  = &$this->config['products'][ $product_id ];
					$product_ver   = &$product_info['active-version']; // product active version
					$last_comp_ver = isset( $comp_info['last'][ $product_id ] ) ? $comp_info['last'][ $product_id ] : 0;
					// maybe need compatibility
					if ( $last_comp_ver ) {
						uksort($list_of_updates,array($this,'version_compare'));
						foreach ( $list_of_updates as $version => $callback ) {
							/**
							 * Just apply update when:
							 * $last_comp_ver < update version <= active product version
							 */
							if (
								$this->version_compare( $last_comp_ver, $version, '<' ) &&
								$this->version_compare( $version, $product_ver, '<=' )
							) {

								if ( ! isset( $comp[ $product_id ] ) || ! in_array( $version, $comp[ $product_id ] ) ) {
									if ( is_callable( $callback ) ) {
										if(call_user_func( $callback, $version, $product_id )) {
											$comp_info['last'][ $product_id ] = $version;

											$comp[ $product_id ][] = $version;
											$must_update           = true;
										} else {
											break;
										}
									}
								}
							}
						}
					} else {
						// first installation

						//setup compatibility pointer
						$comp_info['last'][ $product_id ] = $product_ver;
						$must_update = true;
					}
				}
			}

			if ( ! $must_update ) {
				$active_theme_version = $this->WP_Theme_Object()->get( 'Version' );

				//Set version number in history if necessary
				if ( empty( $history[ $active_theme_version ] ) ) {
					$history[ $active_theme_version ] = time();
					$must_update                      = true;
				}

				// update current version number if necessary
				if ( $this->get_current_theme_version() !== $active_theme_version ) {
					$this->set_current_theme_version();
				}
			}

			// Update log
			if ( $must_update ) {
				$this->set_compatibility_info( $comp_info );
				$this->set_current_theme_version();
			}

			return true;
		} // do_compatibility

		/**
		 * Compare two version
		 *
		 * @param string $current_version
		 * @param string $another_version
		 * @param string $operator [optional] comparison operator
		 *
		 * @return int
		 *
		 * -1 if $current_version is lower than $another_version,
		 *  0 if they are equal
		 *  1 if $another_version is lower.
		 */
		protected function version_compare( $current_version, $another_version, $operator = '>' ) {
			return version_compare( $current_version, $another_version, $operator );
		}

		/**
		 * Get least version of several versions
		 *
		 * @param array $history_array list of version numbers
		 *
		 * @return string|null|bool string version number on success or null|false on failure.
		 */
		protected function get_initial_version( $history_array ) {
			if ( $history_array ) {
				$history_array = array_flip( $history_array );
				usort( $history_array, array( $this, 'version_compare' ) );

				return array_shift( $history_array );
			}

			return false;
		}
	}

	BSBT_Version_Compatibility::get_instance();
}