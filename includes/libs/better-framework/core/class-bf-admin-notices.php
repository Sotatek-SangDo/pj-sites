<?php

/**
 * Handles all message showing in admin panel
 */
class BF_Admin_Notices {

	/**
	 * Store notice data to save in the database
	 * todo check and add custom location for pages
	 *
	 * @var array
	 */
	protected $notices_hook = array(
		'post-new.php' => 'edit_form_top',
		'post.php'     => 'edit_form_top',
	);
	/**
	 * @var mixed|void
	 */
	protected $notice_data;

	function __construct() {

		$this->apply_notice_hook();

		add_action( 'admin_footer', array( $this, 'save_notices' ), 999 );
		add_action( 'switch_theme', array( $this, 'delete_deferred_notices' ) );
		add_action( 'wp_ajax_bf-notice-dismiss', array( $this, 'ajax_dismiss_handler' ) );

		$this->notice_data = get_option( 'bf_notices', array() );
	}

	protected function apply_notice_hook() {
		global $pagenow;

		$hook = isset( $this->notices_hook[ $pagenow ] ) ? $this->notices_hook[ $pagenow ] : 'admin_notices';
		add_action( $hook, array( $this, 'show_notice' ) );
	}

	/**
	 * Adds notice to showing queue
	 *
	 * todo: add custom callback support
	 *
	 * @param array $notice      array {
	 *
	 * @type string $mg          message text
	 * @type string $id          optional for deferred type.notice unique id
	 * @type string $state       optional. success|warning|danger - default:success
	 * @type string $thumbnail   optional. thumbnail image url
	 * @type array  $class       optional. notice custom classes
	 * @type string $type        optional. Notice type is one of the deferred|fixed. - default: deferred.
	 * @type array  $page        optional. display notice on specific page. its an array of $pagenow values
	 * @type bool   $dismissible optional. display close notice button - default:true
	 * }
	 *
	 * @return bool true on success or false on error.
	 */
	function add_notice( $notice ) {

		$notice = wp_parse_args( $notice, array(
			'type'        => 'deferred',
			'dismissible' => true,
			'id'          => false,
			'state'       => 'success',
		) );

		if ( empty( $notice['msg'] ) ) {
			return false;
		}

		/**
		 * Empty id just allowed for deferred type.
		 */
		if ( $notice['type'] !== 'deferred' && empty( $notice['id'] ) ) {
			return false;
		}

		if ( empty( $notice['id'] ) ) {
			$notice['id'] = $this->generate_ID();
		}

		$this->notice_data[ $notice['id'] ] = apply_filters( 'better-framework/admin-notices/new', $notice );

		if ( $this->immediately_save() ) {
			return $this->update_notices( $this->notice_data );
		}

		return true;
	}


	/**
	 * Callback: Removes all BF deferred notices before deactivate theme
	 * Action  : switch_theme
	 */
	function delete_deferred_notices() {

		$nt      = &$this->notice_data;
		$changed = false;

		foreach ( $nt as $idx => $notice ) {
			if ( $notice['type'] === 'deferred' ) {
				$changed = true;
				unset( $nt[ $idx ] );
			}
		}

		if ( $changed ) {
			$this->update_notices( $nt );
		}
	} // delete_deferred_notices


	/**
	 * Remove a notice
	 *
	 * @param string|int|array $id notice unique id
	 *
	 * @return bool true on success or false on error
	 */
	function remove_notice( $id = null ) {

		if ( is_array( $id ) ) {
			$id = isset( $id['id'] ) ? $id['id'] : false;
		}
		if ( ! $id ) {
			return false;
		}

		$nt = &$this->notice_data;;

		if ( isset( $nt[ $id ] ) ) {

			unset( $nt[ $id ] );

			if ( $this->immediately_save() ) {
				return $this->update_notices( $nt );
			} else {

				unset( $this->notice_data[ $id ] );

				return true;
			}
		}

		return false;
	} // remove_notice


	protected function immediately_save() {
		return did_action( 'admin_footer' ) ||
		       ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
		       ( defined( 'DOING_CRON' ) && DOING_CRON );
	}

	protected function generate_ID() {

		do {
			$id = rand();
		} while ( isset( $this->notice_data[ $id ] ) );

		return $id;
	}

	/**
	 * Callback: Shows notice
	 * Action  : admin_notices
	 */
	function show_notice() {

		if ( $notices = apply_filters( 'better-framework/admin-notices/show', $this->notice_data ) ) {

			foreach ( $notices as $notice ) {

				if ( ! $this->is_notice_visible( $notice ) ) {
					continue;
				}
				$dismissible   = ! empty( $notice['dismissible'] );
				$has_thumbnail = ! empty( $notice['thumbnail'] ) && filter_var( $notice['thumbnail'], FILTER_VALIDATE_URL );

				$filter_class = str_replace( '.php', '', current_filter() );
				if ( ! isset( $notice['class'] ) || ! is_array( $notice['class'] ) ) {
					$notice['class'] = array();
				}
				$notice['class'][] = 'bf-notice';
				$notice['class'][] = 'bf-notice-' . sanitize_html_class( $filter_class );
				$notice['class'][] = sprintf( 'bf-notice-%s', $notice['type'] );

				if ( ! isset( $notice['class'] ) ) {
					$notice['class'] = array();
				}
				if ( $dismissible ) {
					$notice['class'][] = 'bf-notice-dismissible';
				}
				$notice['class'][] = $has_thumbnail ? 'bf-notice-has-thumbnail' : 'bf-notice-no-thumbnail';

				$notice['class'][] = 'bf-notice-' . $notice['state'];

				?>
				<div class="bf-notice-wrapper">
					<div
						class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $notice['class'] ) ) ); ?>">

						<div class="bf-notice-container">
							<?php
							if ( $has_thumbnail ) {
								printf( '<div class="bf-notice-thumbnail"><img src="%s" class="bf-notice-thumbnail-img"></div>', esc_attr( $notice['thumbnail'] ) );
							}
							?>
							<div class="bf-notice-text">
								<?php echo wpautop( $notice['msg'] ); ?>
							</div>

							<button type="button" class="bf-notice-dismiss"
								<?php if ( $notice['type'] !== 'deferred' ) : ?>
									data-notice-token="<?php echo esc_attr( wp_create_nonce( 'notice-dismiss-' . $notice['id'] ) ) ?>"
									data-notice-id="<?php echo esc_attr( $notice['id'] ) ?>"
								<?php endif ?>
							><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div>
					</div>
					<?php

					if ( $notice['type'] === 'deferred' ) {
						$this->remove_notice( $notice );
					}
					?>
				</div>
				<?php
			}
		}
	} // show_notice


	/**
	 * Set notices info in db
	 *
	 * @param array $notices
	 *
	 * @return bool true on success or false on failure.
	 */
	protected function update_notices( $notices ) {
		return update_option( 'bf_notices', $notices );
	}


	/**
	 * Callback: Save added notices in db
	 * Action  : admin_footer
	 */
	function save_notices() {
		if ( is_array($this->notice_data) ) {
			update_option( 'bf_notices', $this->notice_data );
		} else if ( $this->notice_data === false ) {
			delete_option( 'bf_notices' );
		}
	}

	protected function is_notice_visible( $notice ) {
		global $pagenow;


		return empty( $notice['page'] ) || in_array( $pagenow, $notice['page'] );
	}

	/**
	 * Callback: close notice ajax request handler
	 * Action  : wp_ajax_bf-notice-dismiss
	 */
	public function ajax_dismiss_handler() {
		$required_params = array(
			'noticeId'    => '',
			'noticeToken' => '',
		);
		if ( array_diff_key( $required_params, $_REQUEST ) ) {

			return;
		}

		$id = &$_REQUEST['noticeId'];
		if ( ! wp_verify_nonce( $_REQUEST['noticeToken'], 'notice-dismiss-' . $id ) ) {
			wp_die( 'Security error occurred' );
		}

		$this->remove_notice( $id );
	}
}