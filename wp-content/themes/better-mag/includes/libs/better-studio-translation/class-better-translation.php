<?php
/**
 * Handy Function for accessing to Better_Translation
 *
 * @return Better_Translation
 */
function Better_Translation() {

	return Better_Translation::self();

}

// Init Better Translation
Better_Translation();

/**
 * Better Translation
 *
 * Includes all functionality for adding advanced translation panel for theme
 *
 *
 * @package  Better Translation
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.0.3
 * @access   public
 * @see      http://www.betterstudio.com
 */
class Better_Translation {


	/**
	 * BetterTranslation version
	 *
	 * @var string
	 */
	private $version = '1.0.3';


	/**
	 * Theme ID
	 *
	 * @var string
	 */
	private $theme_id;


	/**
	 * Theme name
	 *
	 * @var string
	 */
	private $theme_name;


	/**
	 * Notice Icon
	 *
	 * @var string
	 */
	private $notice_icon;


	/**
	 * Translation panel ID
	 *
	 * @var string
	 */
	public $option_panel_id;


	/**
	 * BetterTranslation directory URL
	 *
	 * @var string
	 */
	private $dir_url;


	/**
	 * BetterTranslation directory path
	 *
	 * @var string
	 */
	private $dir_path;


	/**
	 * BetterTranslation Parent Menu
	 *
	 * @var string
	 */
	private $menu_parent = 'better-studio';


	/**
	 * BetterTranslation menu position
	 *
	 * @var string
	 */
	private $menu_position = '30';


	/**
	 * Pre translations array
	 *
	 * @var array
	 */
	private $translations = array();


	/**
	 * Inner array of object instances and caches
	 *
	 * @var array
	 */
	protected static $instances = array();


	/**
	 *
	 */
	function __construct() {

		/**
		 * Filter BetterTranslation config
		 *
		 * @since 1.0.0
		 *
		 * @param string $config configurations
		 */
		$config = apply_filters( 'better-translation/config', array() );

		// check config
		if ( ! isset( $config['dir-url'] ) ||
		     ! isset( $config['dir-path'] ) ||
		     ! isset( $config['theme-id'] ) ||
		     ! isset( $config['theme-name'] )
		) {
			return false;
		}

		$this->dir_url = trailingslashit( $config['dir-url'] );

		$this->dir_path = trailingslashit( $config['dir-path'] );

		// include functions
		include_once  $this->dir_path . 'functions.php';

		$this->theme_id = $config['theme-id'];

		$this->theme_name = $config['theme-name'];

		if ( ! empty( $config['notice-icon'] ) ) {
			$this->notice_icon = $config['notice-icon'];
		}

		if ( ! empty( $config['menu-parent'] ) ) {
			$this->menu_parent = $config['menu-parent'];
		}

		if ( ! empty( $config['menu-position'] ) ) {
			$this->menu_position = $config['menu-position'];
		}

		$this->option_panel_id = 'better-translation-' . $config['theme-id'];

		// check and save translations
		if ( isset( $config['translations'] ) && is_array( $config['translations'] ) ) {
			$this->translations = $config['translations'];
		}

		// Callback for adding translation panel
		add_filter( 'better-framework/panel/options', array( $this, 'setup_translation_panel' ), 100 );

		// Callback for resetting data
		add_filter( 'better-framework/panel/reset/result', array( $this, 'callback_panel_reset_result' ), 10, 2 );

		// Callback for importing data
		add_filter( 'better-framework/panel/import/result', array( $this, 'callback_panel_import_result' ), 10, 3 );

		// Callback for adding current language to export file
		add_filter( 'better-framework/panel/export/data', array( $this, 'callback_panel_export_data' ) );

		// Callback for changing export file name
		add_filter( 'better-framework/panel/export/file-name', array(
			$this,
			'callback_panel_export_file_name'
		), 10, 2 );

		// Callback changing save result
		add_filter( 'better-framework/panel/save/result', array( $this, 'callback_panel_save_result' ), 10, 2 );


		// Admin style and scripts
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

	}


	/**
	 * Used for accessing alive instance of Better_Translation
	 *
	 * @since 1.0
	 *
	 * @return Better_Translation
	 */
	public static function self() {

		return self::factory( 'self' );

	}


	/**
	 * Build the required object instance
	 *
	 * @param   string $object
	 * @param   bool   $fresh
	 * @param   bool   $just_include
	 *
	 * @return  null|Better_Translation
	 */
	public static function factory( $object = 'self', $fresh = false, $just_include = false ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main Better_Translation Class
			 */
			case 'self':
				$class = 'Better_Translation';
				break;


			default:
				return null;
		}


		// Just prepare/includes files
		if ( $just_include ) {
			return;
		}

		// don't cache fresh objects
		if ( $fresh ) {
			return new $class;
		}

		self::$instances[ $object ] = new $class;

		return self::$instances[ $object ];
	}


	/**
	 * Used for retrieving BetterTranslation version
	 *
	 * @return string
	 */
	function get_version() {

		return $this->version;

	}


	/**
	 * Used for retrieving BetterTranslation directory URL
	 *
	 * @return string
	 */
	function get_dir_url() {

		return $this->dir_url;

	}


	/**
	 * Used for retrieving BetterTranslation directory path
	 *
	 * @return string
	 */
	function get_dir_path() {

		return $this->dir_path;

	}


	/**
	 * Used for retrieving current language ( active )
	 *
	 * @return string
	 */
	function get_current_lang() {

		$multilingual = bf_get_current_language_option_code();

		if ( ( $current = get_option( $this->option_panel_id . $multilingual . '-current' ) ) == true ) {

			return $current;

		} else {

			return false;

		}

	}


	/**
	 * Used for saving current language ( active )
	 *
	 * @param      $lang
	 * @param null $multilingual
	 */
	function set_current_lang( $lang, $multilingual = null ) {

		if ( is_null( $multilingual ) ) {
			$multilingual = bf_get_current_lang();
		}

		if ( $multilingual == 'en' || $multilingual == 'none' || empty( $multilingual ) || $multilingual == 'all' ) {
			$multilingual = '';
		} else {
			$multilingual = '_' . $multilingual;
		}

		update_option( $this->option_panel_id . $multilingual . '-current', $lang );

	}


	/**
	 * Used for retrieving pre translations
	 *
	 * @return array
	 */
	function get_translations() {
		return $this->translations;
	}


	/**
	 * Setup translation panel
	 *
	 * @param $options
	 *
	 * @return array
	 */
	function setup_translation_panel( $options ) {

		// Do translation panel smart multilingual support
		$this->setup_translation_panel_before();

		$fields = array();

		$fields[] = array(
			'name' => __( 'Texts', 'better-studio' ),
			'id'   => 'general_tab',
			'type' => 'tab',
			'icon' => 'bsai-global',
		);
		$fields[] = array(
			'name'            => '',
			'id'              => 'send_translation',
			'section_class'   => 'full-width-controls',
			'container_class' => 'share-translation-field-container',
			'type'            => 'custom',
			'input_callback'  => 'bs_better_translations_send_translations_cb'
		);

		// Translation texts
		$fields = apply_filters( 'better-translation/translations/fields', $fields );

		//
		// Backup & restore
		//
		$fields[] = array(
			'name'       => __( 'Backup & Restore', 'better-studio' ),
			'id'         => 'backup_restore',
			'type'       => 'tab',
			'icon'       => 'bsai-export-import',
			'margin-top' => '30',
		);
		$fields[] = array(
			'name'      => __( 'Backup / Export', 'better-studio' ),
			'id'        => 'backup_export_options',
			'type'      => 'export',
			'file_name' => $this->theme_id . '-translation-backup',
			'panel_id'  => $this->option_panel_id,
			'desc'      => __( 'This allows you to create a backup of your translation. Please note, it will not backup anything else.', 'better-studio' )
		);
		$fields[] = array(
			'name'     => __( 'Restore / Import', 'better-studio' ),
			'id'       => 'import_restore_options',
			'type'     => 'import',
			'panel_id' => $this->option_panel_id,
			'desc'     => __( '<strong>It will override your current translation!</strong> Please make sure to select a valid translation file.', 'better-studio' )
		);

		// Language  name for smart admin texts
		$lang = bf_get_current_lang_raw();
		if ( $lang != 'none' ) {
			$lang = bf_get_language_name( $lang );
		} else {
			$lang = '';
		}

		$page_title = sprintf( __( '%s Translation Panel', 'better-studio' ), $this->theme_name );

		$options[ $this->option_panel_id ] = array(
			'panel-name' => $page_title,
			'panel-desc' => '<p>' . __( 'Translate all strings of theme or select pre translation.', 'better-studio' ) . '</p>',
			'fields'     => $fields,
			'texts'      => array(

				'panel-desc-lang'     => '<p>' . __( '%s Language Translation.', 'better-studio' ) . '</p>',
				'panel-desc-lang-all' => '<p>' . __( 'All Languages Translations.', 'better-studio' ) . '</p>',

				'reset-button'     => ! empty( $lang ) ? sprintf( __( 'Reset %s Translation', 'better-studio' ), $lang ) : __( 'Reset Translation', 'better-studio' ),
				'reset-button-all' => __( 'Reset All Translations', 'better-studio' ),

				'reset-confirm'     => ! empty( $lang ) ? sprintf( __( 'Are you sure to reset %s translation?', 'better-studio' ), $lang ) : __( 'Are you sure to reset translation?', 'better-studio' ),
				'reset-confirm-all' => __( 'Are you sure to reset all translations?', 'better-studio' ),

				'save-button'     => ! empty( $lang ) ? sprintf( __( 'Save %s Translation', 'better-studio' ), $lang ) : __( 'Save Translation', 'better-studio' ),
				'save-button-all' => __( 'Save All Translations', 'better-studio' ),

				'save-confirm-all' => __( 'Are you sure to save all translations? this will override specified translations per languages', 'better-studio' )

			),
			'config'     => array(
				'name'                => $page_title,
				'parent'              => $this->menu_parent,
				'slug'                => 'better-studio/translations/' . $this->theme_id . '-translation',
				'page_title'          => $page_title,
				'menu_title'          => __( 'Theme Translation', 'better-studio' ),
				'capability'          => 'manage_options',
				'menu_slug'           => $page_title,
				'notice-icon'         => $this->notice_icon,
				'icon_url'            => null,
				'position'            => $this->menu_position,
				'exclude_from_export' => false,
				'on_admin_bar'        => true
			),
		);

		return $options;

	}


	/**
	 * Used for retrieving a translation from panel
	 *
	 * Use like __() function
	 *
	 * todo add default value for this function
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	function _get( $option_key ) {
		return bf_get_option( $option_key, $this->option_panel_id );
	}


	/**
	 * Used for retrieving a translation from panel
	 *
	 * Use like esc_attr__() function
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	function _get_esc_attr( $option_key ) {
		return esc_attr( bf_get_option( $option_key, $this->option_panel_id ) );
	}


	/**
	 * Used for retrieving a translation from panel
	 *
	 * Use like _e() function
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	function _echo( $option_key ) {
		bf_echo_option( $option_key, $this->option_panel_id );
	}


	/**
	 * Used for retrieving a translation from panel
	 *
	 * Use like esc_attr__() function
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	function _echo_esc_attr( $option_key ) {
		echo esc_attr( bf_get_option( $option_key, $this->option_panel_id ) );
	}


	/**
	 * Enqueue scripts and styles
	 */
	function enqueue_scripts() {

		// Better translation style
		wp_enqueue_style( 'better-translation', $this->get_dir_url() . 'css/style.css', array(), $this->get_version() );

		// Better translation script
		wp_enqueue_script( 'better-translation', $this->get_dir_url() . 'js/script.js', array(), $this->get_version(), true );

		bf_enqueue_script( 'bs-modal' );
		bf_enqueue_style( 'bs-modal' );

		$change_translation_callback = 'Better_Translation::change_translation';
		$share_translation_callback  = 'Better_Translation::callback_send_translation';
		wp_localize_script(
			'better-translation',
			'better_translation_loc',
			apply_filters(
				'better-translation/localized-items',
				array(
					'ajax_url'                          => admin_url( 'admin-ajax.php' ),
					'nonce'                             => wp_create_nonce( 'bf_nonce' ),
					'type'                              => 'panel',
					'callback_change_translation'       => $change_translation_callback,
					'callback_change_translation_token' => Better_Framework::callback_token( $change_translation_callback ),
					'callback_send_translation'         => $share_translation_callback,
					'callback_send_translation_token'   => Better_Framework::callback_token( $share_translation_callback ),
					'current_lang'                      => $this->get_current_lang(),
					'lang'                              => bf_get_current_lang(),

					'change_confirm' => array(
						'header'     => __( 'Change Translation', 'better-studio' ),
						'title'      => __( 'Are you sure to change translation?', 'better-studio' ),
						'body'       => __( 'Do you want to change translation? all current translations will be lost!', 'better-studio' ),
						'button_yes' => __( 'Yes, Change', 'better-studio' ),
						'button_no'  => __( 'No', 'better-studio' ),
						'loading'    => __( 'Changing language', 'better-studio' ),
						'success'    => __( 'new language activated successfully', 'better-studio' ),
					),
					
					'share_confirm' => array(
						'icon'       => 'fa-share-alt',
						'header'     => __( 'Share your translation or correction', 'better-studio' ),
						'title'      => '',
						'body'       => __( 'Your translations will be sent to our server and after a review process it will be available to all of the members of the community. Please make sure that you sent it for the correct language.<br/>%%language_dropdown%%<br/>Thank you for your trust and contribution and we will do our best to give back.', 'better-studio' ),
						'button_yes' => __( 'Send translation or correction', 'better-studio' ),
						'loading'    => __( 'Sending Translation ...', 'better-studio' ),
						'success'    => __( 'Translation sent. thank you', 'better-studio' ),
					)
				)
			)
		);

	}


	/**
	 * Callback for changing translations
	 *
	 * @param   string      $lang_id      Selected Language ID
	 * @param   null|string $multilingual Current WP Language ( Multilingual )
	 *
	 * @return array
	 */
	public static function change_translation( $lang_id, $multilingual = null ) {
		/**
		 * Fires before changing translation
		 *
		 * @since 1.0.0
		 *
		 * @param string $lang_id      Selected translation id for change
		 * @param array  $translations All active translations list
		 */
		do_action( 'better-translation/change-translation/before', $lang_id, Better_Translation()->translations );


		// Check for valid translation
		if ( ! isset( Better_Translation()->translations[ $lang_id ] ) || ! isset( Better_Translation()->translations[ $lang_id ]['url'] ) ) {

			return array(
				'status'  => 'error',
				'msg'     => __( 'Translation for selected language not found!', 'better-studio' ),
				'refresh' => false
			);

		}

		/**
		 * Filter translation file url
		 *
		 * @since 1.0.0
		 */
		$translations = apply_filters( 'better-translation/change-translation/file-url', Better_Translation()->translations[ $lang_id ]['url'] );


		// Read translation json file
		$translation_options_data = wp_remote_get( $translations );
		$translation_options_data = wp_remote_retrieve_body( $translation_options_data );


		if ( ! $translation_options_data ) {
			return array(
				'status'  => 'error',
				'msg'     => __( 'Translation file for selected language not found!', 'better-studio' ),
				'refresh' => false
			);
		}

		/**
		 * Filter translation data
		 *
		 * @since 1.0.0
		 */
		$data = apply_filters( 'better-translation/change-translation/data', json_decode( $translation_options_data, true ) );

		// Validate translation file data
		if ( ! isset( $data['panel-id'] ) || empty( $data['panel-id'] ) || ! isset( $data['panel-data'] ) ) {

			return array(
				'status'  => 'error',
				'msg'     => __( 'Translation file for selected language is not valid!', 'better-studio' ),
				'refresh' => false
			);

		}

		// Validate translation panel id
		if ( $data['panel-id'] != Better_Translation()->option_panel_id ) {

			return array(
				'status'  => 'error',
				'msg'     => sprintf( __( 'Translation file is not valid for "%s"', 'better-studio' ), Better_Translation()->theme_name ),
				'refresh' => false
			);

		}

		if ( $multilingual == 'en_US' || $multilingual == 'none' || empty( $multilingual ) ) {
			$_multilingual = '';
		} else {
			$_multilingual = '_' . $multilingual;
		}

		// Save translation and update current lang
		update_option( Better_Translation()->option_panel_id . $_multilingual, $data['panel-data'], ! empty( $_multilingual ) ? 'no' : 'yes' );
		Better_Translation()->set_current_lang( $lang_id, $multilingual );

		$result = array(
			'status'  => 'succeed',
			'msg'     => sprintf( __( 'Theme translation changed to "%s"', 'better-studio' ), Better_Translation()->translations[ $lang_id ]['name'] ),
			'refresh' => true
		);

		// Add admin notice
		Better_Framework()->admin_notices()->add_notice( array(
			'msg' => $result['msg'],
			'thumbnail' => Better_Translation()->notice_icon
		) );

		return $result;
	}


	/**
	 * Callback for send translations
	 *
	 * @param $lang_name
	 * @param $lang_code
	 * @param $translator
	 * @param $desc
	 *
	 * @return array
	 */
	public static function callback_send_translation( $lang_name = '', $lang_code = '', $translator = '' ) {

		/**
		 * Fires before send translation
		 *
		 * @since 1.0.0
		 *
		 * @param string $lang_name Language name
		 * @param array  $lang_code Country name
		 */
		do_action( 'better-translation/send-translation/before', $lang_name, $lang_code );

		// Make email body
		$email_body = '';
		$email_body .= "<p><em>Language Name:</em> <strong>$lang_name</strong></p>";
		$email_body .= "<p><em>Language Code:</em> <strong>$lang_code</strong></p>";
		$email_body .= "<p><em>Translator Site:</em> <strong>" . esc_url( get_home_url() ) . "</strong></p>";
		$email_body .= "<p><em>Translation:</em> <br><strong>" . json_encode( get_option( Better_Translation()->option_panel_id ) ) . "</strong></p>";
		add_filter( 'wp_mail_content_type', array( Better_Translation(), 'set_html_content_type' ), 1000 );


		// Email Header
		$_current_user                              = wp_get_current_user();
		$translator                                 = $_current_user->data->display_name;
		$headers                                    = 'From: ' . $translator . ' <' . $_current_user->user_email . '>';
		Better_Translation::$instances['from-name'] = $translator;
		add_filter( 'wp_mail_from_name', array( Better_Translation(), 'wp_mail_from_name' ) );


		// Subject
		$subject = 'NEW TRANSLATION: ' . Better_Translation()->theme_name . ' - ' . $lang_name;

		// Send email
		$send_mail = wp_mail( 'info@betterstudio.com', $subject, $email_body, $headers );

		// Reset content-type to avoid conflicts
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

		// Reset from name to default to avoid conflict
		remove_filter( 'wp_mail_from_name', array( Better_Translation(), 'wp_mail_from_name' ) );

		if ( $send_mail ) {
			$result = array(
				'status' => 'succeed',
				'msg'    => __( "Translation sent successfully.", 'better-studio' ),
			);
		} else {
			$result = array(
				'status' => 'error',
				'msg'    => __( "There is a problem in sending translation.", 'better-studio' ),
			);
		}

		/**
		 * Filter result of import options
		 *
		 * @since 1.0.0
		 *
		 * @param array  $result     contains result of reset
		 * @param string $lang_name  Language name
		 * @param array  $lang_code  Country name
		 * @param array  $translator Translator name
		 * @param array  $desc       Desc
		 */
		return apply_filters( 'better-translation/send-translation/result', $result, $lang_name, $lang_code );
	}


	/**
	 * Filter callback: For changing email content type to HTML
	 *
	 * @return string
	 */
	function set_html_content_type() {

		return 'text/html';
	}


	/**
	 * Filter callback: For changing email from name
	 *
	 * @param $name
	 *
	 * @return string
	 */
	function wp_mail_from_name( $name ) {

		$name = Better_Translation::$instances['from-name'];

		if ( empty( $name ) ) {
			$name = 'Anonymous';
		}

		// remove cache
		unset( Better_Translation::$instances['from-name'] );

		return $name;
	}


	/**
	 * Filter callback: Used for resetting current language on resetting panel
	 *
	 * @param $options
	 * @param $result
	 *
	 * @return
	 */
	function callback_panel_reset_result( $result, $options ) {

		// check panel
		if ( $options['id'] != $this->option_panel_id ) {
			return $result;
		}

		// reset current language
		$this->set_current_lang( 'en_US' );

		// change messages
		if ( $result['status'] == 'succeed' ) {
			$result['msg'] = __( 'Theme translation reset to default.', 'better-studio' );
		} else {
			$result['msg'] = __( 'An error occurred while resetting theme translation.', 'better-studio' );
		}

		return $result;
	}


	/**
	 * Filter callback: Used for changing current language on importing translation panel data
	 *
	 * @param $result
	 * @param $data
	 * @param $args
	 *
	 * @return
	 */
	function callback_panel_import_result( $result, $data, $args ) {

		// check panel
		if ( $args['panel-id'] != $this->option_panel_id ) {
			return $result;
		}

		// change messages
		if ( $result['status'] == 'succeed' ) {
			$result['msg'] = __( 'Theme translation successfully imported.', 'better-studio' );
		} else {
			if ( $result['msg'] == __( 'Imported data is not for this panel.', 'better-studio' ) ) {
				$result['msg'] = __( 'Imported translation is not for this theme.', 'better-studio' );
			} else {
				$result['msg'] = __( 'An error occurred while importing theme translation.', 'better-studio' );
			}
		}


		// current lang
		if ( isset( $data['panel-data']['lang-id'] ) ) {
			$this->set_current_lang( $data['panel-data']['lang-id'] );
		} else {
			$this->set_current_lang( 'en_US' );
		}

		return $result;
	}


	/**
	 * Filter callback: Used for adding current language to export data
	 *
	 * @param $options_array
	 */
	function callback_panel_export_data( $options_array ) {

		if ( $options_array['panel-id'] == $this->option_panel_id ) {
			$options_array['panel-data']['lang-id'] = $this->get_current_lang();
		}

		return $options_array;
	}


	/**
	 * Filter callback: Used for changing export file name
	 *
	 * @param $file_name
	 * @param $options_array
	 *
	 * @return string
	 */
	function callback_panel_export_file_name( $file_name, $options_array ) {

		// change only for translation panel
		if ( $options_array['panel-id'] == $this->option_panel_id ) {

			$file_name = $this->theme_id . '-' . $this->get_current_lang();

		}

		return $file_name;
	}


	/**
	 * Filter callback: Used for changing save translation panel result
	 *
	 * @param $output
	 * @param $args
	 *
	 * @return string
	 */
	function callback_panel_save_result( $output, $args ) {

		// change only for translation panel
		if ( $args['id'] == $this->option_panel_id ) {
			if ( $output['status'] == 'succeed' ) {
				$output['msg'] = __( 'Translations saved.', 'better-studio' );
			} else {
				$output['msg'] = __( 'An error occurred while saving translations.', 'better-studio' );
			}
		}

		return $output;
	}


	/**
	 * Callback: Used to add smart support of multilingual translation panel
	 */
	function setup_translation_panel_before() {

		if ( count( $this->translations ) <= 0 ) {
			return;
		}

		// Get current multilingual language
		$multilingual = bf_get_current_lang();

		// All languages page
		if ( $multilingual == 'all' ) {

			// Add current language to all languages
			foreach ( bf_get_all_languages() as $lang ) {

				if ( $lang['id'] == 'en' || $lang == 'all' ) {
					$_lang = '';
				} else {
					$_lang = '_' . $lang['id'];
				}

				if ( false == get_option( $this->option_panel_id . $_lang . '-current' ) ) {
					$this->set_current_lang( 'en_US', $lang['id'] );
				}

			}

			return;
		} elseif ( $multilingual == 'en' || $multilingual == 'none' ) {

			if ( false == get_option( $this->option_panel_id . '-current' ) ) {
				$this->set_current_lang( 'en_US' );
			}

			return;

		}

		$_multilingual = '_' . $multilingual;

		$opt_current = get_option( $this->option_panel_id . $_multilingual . '-current' );

		// if data is saved before
		if ( $opt_current !== false ) {
			return;
		}

		// Get language all information and locale
		$language = bf_get_language_data( $multilingual );

		// Use pre-translation if available
		if ( isset( $this->translations[ $language['locale'] ] ) ) {

			/**
			 * Filter translation file url
			 *
			 * @since 1.0.0
			 */
			$translation = apply_filters( 'better-translation/change-translation/file-url', Better_Translation()->translations[ $language['locale'] ]['url'] );

			// Read translation json file
			$translation_options_data = wp_remote_get( $translation );
			$translation_options_data = wp_remote_retrieve_body( $translation_options_data );

			/**
			 * Filter translation data
			 *
			 * @since 1.0.0
			 */
			$data = apply_filters( 'better-translation/change-translation/data', json_decode( $translation_options_data, true ) );

			// Validate translation file data
			if ( ! isset( $data['panel-id'] ) || empty( $data['panel-id'] ) || ! isset( $data['panel-data'] ) ) {
				return;
			}

			// Validate translation panel id
			if ( $data['panel-id'] != Better_Translation()->option_panel_id ) {
				return;
			}


			// Save translation and update current lang
			update_option( Better_Translation()->option_panel_id . $_multilingual, $data['panel-data'], ! empty( $_multilingual ) ? 'no' : 'yes' );
			Better_Translation()->set_current_lang( $language['locale'], $multilingual );

		} else {
			$this->set_current_lang( 'en_US', $multilingual );
		}

	}

}
