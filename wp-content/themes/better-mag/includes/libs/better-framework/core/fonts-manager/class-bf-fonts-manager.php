<?php

/**
 * Better Framework Font Manager
 *
 *
 * @package  BetterFramework
 * @author   BetterStudio <info@betterstudio.com>
 * @access   public
 * @see      http://www.betterstudio.com
 */
class BF_Fonts_Manager {


	/**
	 * Panel ID
	 *
	 * @var string
	 */
	public $option_panel_id = 'better-framework-custom-fonts';


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

		// Callback for adding custom fonts panel
		add_filter( 'better-framework/panel/options', array( $this, 'setup_panel' ), 100 );

		// Callback for resetting data
		add_filter( 'better-framework/panel/reset/result', array( $this, 'callback_panel_reset_result' ), 10, 2 );

		// Callback for importing data
		add_filter( 'better-framework/panel/import/result', array( $this, 'callback_panel_import_result' ), 10, 3 );

		// Callback changing save result
		add_filter( 'better-framework/panel/save/result', array( $this, 'callback_panel_save_result' ), 10, 2 );

		// Adds fonts file types to WP uploader
		add_filter( 'upload_mimes', array( $this, 'filter_upload_mimes_types' ) );

		// Hook BF admin assets enqueue
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Output custom css for custom fonts
		add_action( 'template_redirect', array( $this, 'admin_custom_css' ), 1 );

	}


	/**
	 * Build the required object instance
	 *
	 * @param   string $object
	 * @param   bool   $fresh
	 * @param   bool   $just_include
	 *
	 * @return  null|BF_Fonts_Manager|BF_FM_Custom_Fonts_Helper|BF_FM_Font_Stacks_Helper|BF_FM_Google_Fonts_Helper
	 */
	public static function factory( $object = 'self', $fresh = FALSE, $just_include = FALSE ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main BF_Fonts_Manager Class
			 */
			case 'self':
				$class = 'BF_Fonts_Manager';
				break;

			/**
			 * Theme Fonts: BF_FM_Theme_Fonts_Helper Class
			 */
			case 'theme-fonts':

				bf_require_once( 'core/fonts-manager/class-bf-fm-theme-fonts-helper.php' );

				$class = 'BF_FM_Theme_Fonts_Helper';

				break;

			/**
			 * Google Fonts: BF_FM_Google_Fonts_Helper Class
			 */
			case 'google-fonts':

				bf_require_once( 'core/fonts-manager/class-bf-fm-google-fonts-helper.php' );

				$class = 'BF_FM_Google_Fonts_Helper';

				break;

			/**
			 * Custom Fonts: BF_FM_Custom_Fonts_Helper Class
			 */
			case 'custom-fonts':

				bf_require_once( 'core/fonts-manager/class-bf-fm-custom-fonts-helper.php' );

				$class = 'BF_FM_Custom_Fonts_Helper';

				break;

			/**
			 * Font Stacks: BF_FM_Font_Stacks_Helper Class
			 */
			case 'font-stacks':

				bf_require_once( 'core/fonts-manager/class-bf-fm-font-stacks-helper.php' );

				$class = 'BF_FM_Font_Stacks_Helper';

				break;

			default:
				return NULL;
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
	 * @return BF_FM_Google_Fonts_Helper
	 */
	public static function google_fonts() {
		return self::factory( 'google-fonts' );
	}


	/**
	 * @return BF_FM_Custom_Fonts_Helper
	 */
	public static function custom_fonts() {
		return self::factory( 'custom-fonts' );
	}


	/**
	 * @return BF_FM_Font_Stacks_Helper
	 */
	public static function font_stacks() {
		return self::factory( 'font-stacks' );
	}


	/**
	 * @return BF_FM_Theme_Fonts_Helper
	 */
	public static function theme_fonts() {
		return self::factory( 'theme-fonts' );
	}


	/**
	 * Used for getting protocol for links of google fonts
	 *
	 * @param string $protocol custom protocol for using outside
	 *
	 * @return string
	 */
	public function get_protocol( $protocol = '' ) {

		if ( empty( $protocol ) ) {
			$protocol = $this->get_option( 'google_fonts_protocol' );
		}

		switch ( $protocol ) {

			case 'http':
				$protocol = 'http://';
				break;

			case 'https':
				$protocol = 'https://';
				break;

			case 'relative':
				$protocol = '//';
				break;

			default:
				$protocol = 'https://';

		}

		return $protocol;

	}


	/**
	 * Used for retrieving options simply and safely for next versions
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	public function get_option( $option_key ) {
		return bf_get_option( $option_key, $this->option_panel_id );
	}


	/**
	 * Callback: Output Custom CSS for Custom Fonts
	 *
	 * Filter: template_redirect
	 */
	public function admin_custom_css() {

		// just when custom css requested
		if ( empty( $_GET['better_fonts_manager_custom_css'] ) OR intval( $_GET['better_fonts_manager_custom_css'] ) != 1 ) {
			return;
		}

		// Custom font requested
		if ( ! empty( $_GET['custom_font_id'] ) ) {
			$font_id      = $_GET['custom_font_id'];
			$custom_fonts = self::custom_fonts()->get_all_fonts();
		} // Theme font requested
		elseif ( ! empty( $_GET['theme_font_id'] ) ) {
			$font_id      = $_GET['theme_font_id'];
			$custom_fonts = self::theme_fonts()->get_all_fonts();
		} else {
			die;
		}

		// If custom font is not valid
		if ( ! isset( $custom_fonts[ $font_id ] ) ) {
			return;
		}

		status_header( 200 );
		header( "Content-type: text/css; charset: utf-8" );

		$main_src_printed = FALSE;

		$output = "
@font-face {
    font-family: '" . $font_id . "';";

		// .EOT
		if ( ! empty( $custom_fonts[ $font_id ]['eot'] ) ) {

			$output .= "
    src: url('" . $custom_fonts[ $font_id ]['eot'] . "'); /* IE9 Compat Modes */
    src: url('" . $custom_fonts[ $font_id ]['eot'] . "?#iefix') format('embedded-opentype') /* IE6-IE8 */";

			$main_src_printed = TRUE;

		}

		// .WOFF
		if ( ! empty( $custom_fonts[ $font_id ]['woff'] ) ) {

			if ( $main_src_printed ) {

				$output .= "
    , url('" . $custom_fonts[ $font_id ]['woff'] . "') format('woff') /* Pretty Modern Browsers */";

			} else {

				$main_src_printed = TRUE;

				$output .= "
    src: url('" . $custom_fonts[ $font_id ]['woff'] . "') format('woff') /* Pretty Modern Browsers */";

			}
		}

		// .TTF
		if ( ! empty( $custom_fonts[ $font_id ]['ttf'] ) ) {

			if ( $main_src_printed ) {

				$output .= "
    , url('" . $custom_fonts[ $font_id ]['ttf'] . "') format('truetype') /* Safari, Android, iOS */";

			} else {

				$main_src_printed = TRUE;

				$output .= "
    src: url('" . $custom_fonts[ $font_id ]['ttf'] . "') format('truetype') /* Safari, Android, iOS */";

			}
		}

		// .SVG
		if ( ! empty( $custom_fonts[ $font_id ]['svg'] ) ) {

			if ( $main_src_printed ) {

				$output .= "
    , url('" . $custom_fonts[ $font_id ]['svg'] . "#" . $font_id . "') format('svg') /* Legacy iOS */";

			} else {

				$output .= "
    src: url('" . $custom_fonts[ $font_id ]['svg'] . "#" . $font_id . "') format('svg') /* Legacy iOS */";

			}
		}

		echo $output . ";
    font-weight: normal;
    font-style: normal;
}";

		exit;
	}


	/**
	 * Used for getting font when we do not know what type is the font!
	 *
	 * Priority:
	 *  1. Theme Fonts
	 *  2. Custom Fonts
	 *  3. Font Stacks
	 *  4. Google fonts
	 *
	 * @param   string $font_name Font ID
	 *
	 * @return bool
	 */
	public static function get_font( $font_name ) {

		// Get from theme fonts
		$font = self::theme_fonts()->get_font( $font_name );

		if ( $font !== FALSE ) {
			return $font;
		}

		// Get from custom fonts
		$font = self::custom_fonts()->get_font( $font_name );

		if ( $font !== FALSE ) {
			return $font;
		}

		// Get from font stacks
		$font = self::font_stacks()->get_font( $font_name );

		if ( $font !== FALSE ) {
			return $font;
		}

		// Get from google fonts
		$font = self::google_fonts()->get_font( $font_name );

		if ( $font !== FALSE ) {
			return $font;
		}

		return FALSE;
	}


	/**
	 * Get font variants HTML option tags
	 *
	 * @param   string|array $font            Font ID
	 * @param   string       $current_variant Active or Selected Variant ID
	 */
	public static function get_font_variants_option_elements( $font, $current_variant = '' ) {

		switch ( $font['type'] ) {

			// Theme fonts variants
			case 'theme-font':

				echo self::theme_fonts()->get_font_variants_option_elements( $current_variant );

				return;
				break;

			// Custom fonts variants
			case 'custom-font':

				echo self::custom_fonts()->get_font_variants_option_elements( $current_variant );

				return;
				break;

			// Font stacks variants
			case 'font-stack':

				echo self::font_stacks()->get_font_variants_option_elements( $current_variant );

				return;
				break;

			// Google fonts variants
			case 'google-font':

				echo self::google_fonts()->get_font_variants_option_elements( $font, $current_variant );

				return;
				break;

		}

	}


	/**
	 * Get font subsets HTML option tags
	 *
	 * @param   string|array $font           Font ID
	 * @param   string       $current_subset Active or Selected Subset ID
	 */
	public static function get_font_subset_option_elements( $font, $current_subset = '' ) {

		switch ( $font['type'] ) {

			case 'theme-font':

				echo self::custom_fonts()->get_font_subset_option_elements();

				return;
				break;

			case 'custom-font':

				echo self::custom_fonts()->get_font_subset_option_elements();

				return;
				break;

			case 'font-stack':

				echo self::font_stacks()->get_font_subset_option_elements();

				return;
				break;

			case 'google-font':

				echo self::google_fonts()->get_font_subset_option_elements( $font, $current_subset );

				return;
				break;

		}

	}


	/**
	 * Callback: Used for enqueue font manager assets
	 *
	 * Filter: admin_enqueue_scripts
	 */
	function admin_enqueue_scripts() {

		if ( BF()->sections['admin_panel'] != TRUE || BF()->get_current_page_type() != 'panel' ) {
			return;
		}

		// Better Font Manager admin script
		BF()->assets_manager()->enqueue_script( 'better-fonts-manager' );

		wp_localize_script(
			'bf-better-fonts-manager',
			'better_fonts_manager_loc',
			apply_filters(
				'better-framework/fonts-manager/localized-items',
				array(
					'type'  => 'panel',

					// Fonts lib
					'fonts' => array(
						'theme_fonts'  => BF()->fonts_manager()->theme_fonts()->get_all_fonts(),
						'google_fonts' => BF()->fonts_manager()->google_fonts()->get_all_fonts(),
						'custom_fonts' => BF()->fonts_manager()->custom_fonts()->get_all_fonts(),
						'font_stacks'  => BF()->fonts_manager()->font_stacks()->get_all_fonts()
					),

					'admin_fonts_css_url' => get_site_url() . '/?better_fonts_manager_custom_css=1&ver=' . time(),

					'texts' => array(
						'variant_100'       => __( 'Ultra-Light 100', 'better-studio' ),
						'variant_300'       => __( 'Book 300', 'better-studio' ),
						'variant_400'       => __( 'Normal 400', 'better-studio' ),
						'variant_500'       => __( 'Medium 500', 'better-studio' ),
						'variant_700'       => __( 'Bold 700', 'better-studio' ),
						'variant_900'       => __( 'Ultra-Bold 900', 'better-studio' ),
						'variant_100italic' => __( 'Ultra-Light 100 Italic', 'better-studio' ),
						'variant_300italic' => __( 'Book 300 Italic', 'better-studio' ),
						'variant_400italic' => __( 'Normal 400 Italic', 'better-studio' ),
						'variant_500italic' => __( 'Medium 500 Italic', 'better-studio' ),
						'variant_700italic' => __( 'Bold 700 Italic', 'better-studio' ),
						'variant_900italic' => __( 'Ultra-Bold 900 Italic', 'better-studio' ),

						'subset_unknown' => __( 'Unknown', 'better-studio' ),
					)
				)
			)
		);

	}


	/**
	 * Callback: Used for adding fonts to BetterFramework localized items
	 *
	 * Filter: better-framework/localized-items
	 *
	 * @param $localized_items
	 *
	 * @return mixed
	 */
	function filter_better_framework_localized_items( $localized_items ) {

		$localized_items['fonts_lib']['custom_fonts'] = self::custom_fonts()->get_all_fonts();
		$localized_items['fonts_lib']['font_stacks']  = self::font_stacks()->get_all_fonts();
		$localized_items['fonts_lib']['google_fonts'] = self::google_fonts()->get_all_fonts();

		return $localized_items;
	}


	/**
	 * Callback: Used for adding fonts mimes to WordPress uploader
	 *
	 * Filter: upload_mimes
	 *
	 * @param $mimes
	 *
	 * @return mixed
	 */
	function filter_upload_mimes_types( $mimes ) {

		$mimes['ttf']  = 'font/ttf';
		$mimes['woff'] = 'font/woff';
		$mimes['svg']  = 'font/svg';
		$mimes['eot']  = 'font/eot';

		return $mimes;
	}


	/**
	 * Callback: Setup panel
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function setup_panel( $options ) {

		//
		// Custom Fonts
		//
		$fields[]               = array(
			'name' => __( 'Custom Fonts', 'better-studio' ),
			'id'   => 'custom_fonts_tab',
			'type' => 'tab',
			'icon' => 'bsai-add',
		);
		$custom_fonts           = array();
		$custom_fonts['id']     = array(
			'name'            => __( 'Font Name', 'better-studio' ),
			'id'              => 'id',
			'std'             => '',
			'type'            => 'text',
			'container_class' => 'better-custom-fonts-id',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$custom_fonts['woff']   = array(
			'name'            => __( 'Font .woff', 'better-studio' ),
			'button_text'     => __( 'Upload .woff', 'better-studio' ),
			'id'              => 'woff',
			'std'             => '',
			'type'            => 'media',
			'container_class' => 'better-custom-fonts-woff',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$custom_fonts['ttf']    = array(
			'name'            => __( 'Font .ttf', 'better-studio' ),
			'button_text'     => __( 'Upload .ttf', 'better-studio' ),
			'id'              => 'ttf',
			'std'             => '',
			'type'            => 'media',
			'container_class' => 'better-custom-fonts-ttf',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$custom_fonts['svg']    = array(
			'name'            => __( 'Font .svg', 'better-studio' ),
			'button_text'     => __( 'Upload .svg', 'better-studio' ),
			'id'              => 'svg',
			'std'             => '',
			'type'            => 'media',
			'container_class' => 'better-custom-fonts-svg',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$custom_fonts['eot']    = array(
			'name'            => __( 'Font .eot', 'better-studio' ),
			'button_text'     => __( 'Upload .eot', 'better-studio' ),
			'id'              => 'eot',
			'std'             => '',
			'type'            => 'media',
			'container_class' => 'better-custom-fonts-eot',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$fields['custom_fonts'] = array(
			'name'          => __( 'Upload Custom Fonts', 'better-studio' ),
			'id'            => 'custom_fonts',
			'type'          => 'repeater',
			'save-std'      => TRUE,
			'default'       => array(
				array(
					'id'   => __( 'Font %i%', 'better-studio' ),
					'woff' => '',
					'ttf'  => '',
					'svg'  => '',
					'eot'  => '',
				),
			),
			'add_label'     => '<i class="fa fa-plus"></i> ' . __( 'Add New Font', 'better-studio' ),
			'delete_label'  => __( 'Delete Font', 'better-studio' ),
			'item_title'    => __( 'Custom Font', 'better-studio' ),
			'section_class' => 'full-with-both',
			'options'       => $custom_fonts
		);


		//
		// Fonts Stacks
		//
		$fields[]              = array(
			'name' => __( 'Font Stacks', 'better-studio' ),
			'id'   => 'font_stacks_tab',
			'type' => 'tab',
			'icon' => 'bsai-font',
		);
		$font_stacks['id']     = array(
			'name'            => __( 'Font Name', 'better-studio' ),
			'id'              => 'id',
			'std'             => '',
			'type'            => 'text',
			'container_class' => 'better-font-stack-name',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$font_stacks['stack']  = array(
			'name'            => __( 'Font Stack', 'better-studio' ),
			'id'              => 'stack',
			'std'             => '',
			'type'            => 'text',
			'container_class' => 'better-font-stack-stack',
			'section_class'   => 'full-with-both',
			'repeater_item'   => TRUE
		);
		$fields['font_stacks'] = array(
			'name'          => __( 'Web Safe CSS Font Stacks', 'better-studio' ),
			'id'            => 'font_stacks',
			'type'          => 'repeater',
			'save-std'      => TRUE,
			'default'       => array(
				array(
					'id'    => 'Arial',
					'stack' => 'Arial,"Helvetica Neue",Helvetica,sans-serif',
				),
				array(
					'id'    => 'Arial Black',
					'stack' => '"Arial Black","Arial Bold",Gadget,sans-serif',
				),
				array(
					'id'    => 'Arial Narrow',
					'stack' => '"Arial Narrow",Arial,sans-serif',
				),
				array(
					'id'    => 'Calibri',
					'stack' => 'Calibri,Candara,Segoe,"Segoe UI",Optima,Arial,sans-serif',
				),
				array(
					'id'    => 'Gill Sans',
					'stack' => '"Gill Sans","Gill Sans MT",Calibri,sans-serif',
				),
				array(
					'id'    => 'Helvetica',
					'stack' => '"Helvetica Neue",Helvetica,Arial,sans-serif',
				),
				array(
					'id'    => 'Tahoma',
					'stack' => 'Tahoma,Verdana,Segoe,sans-serif',
				),
				array(
					'id'    => 'Trebuchet MS',
					'stack' => '"Trebuchet MS","Lucida Grande","Lucida Sans Unicode","Lucida Sans",Tahoma,sans-serif',
				),
				array(
					'id'    => 'Verdana',
					'stack' => 'Verdana,Geneva,sans-serif',
				),
				array(
					'id'    => 'Georgia',
					'stack' => 'Georgia,Times,"Times New Roman",serif',
				),
				array(
					'id'    => 'Palatino',
					'stack' => 'Palatino,"Palatino Linotype","Palatino LT STD","Book Antiqua",Georgia,serif',
				),
				array(
					'id'    => 'Courier New',
					'stack' => '"Courier New",Courier,"Lucida Sans Typewriter","Lucida Typewriter",monospace',
				),
				array(
					'id'    => 'Lucida Sans Typewriter',
					'stack' => '"Lucida Sans Typewriter","Lucida Console",monaco,"Bitstream Vera Sans Mono",monospace',
				),
				array(
					'id'    => 'Copperplate',
					'stack' => 'Copperplate,"Copperplate Gothic Light",fantasy',
				),
				array(
					'id'    => 'Papyrus',
					'stack' => 'Papyrus,fantasy',
				),
				array(
					'id'    => 'Brush Script MT',
					'stack' => '"Brush Script MT",cursive',
				),

			),
			'add_label'     => '<i class="fa fa-plus"></i> ' . __( 'Add New Font Stack', 'better-studio' ),
			'delete_label'  => __( 'Delete Font Stack', 'better-studio' ),
			'item_title'    => __( 'CSS Font Stack', 'better-studio' ),
			'section_class' => 'full-with-both',
			'options'       => $font_stacks
		);


		//
		// Advanced Options
		//
		$fields[] = array(
			'name' => __( 'Advanced', 'better-studio' ),
			'id'   => 'typo_opt_tab',
			'type' => 'tab',
			'icon' => 'bsai-gear',
		);
		$fields[] = array(
			'name'          => __( 'Google Fonts Protocol', 'better-studio' ),
			'id'            => 'google_fonts_protocol',
			'desc'          => __( 'Select protocol of fonts link for Google Fonts.', 'better-studio' ),
			'std'           => 'http',
			'type'          => 'select',
			'section_class' => 'style-floated-left',
			'options'       => array(
				'http'     => __( 'HTTP', 'better-studio' ),
				'https'    => __( 'HTTPs', 'better-studio' ),
				'relative' => __( 'Relative to Site', 'better-studio' ),
			),
		);
		$fields[] = array(
			'name'  => __( 'Typography Field Preview Texts', 'better-studio' ),
			'type'  => 'group',
			'state' => 'not',
		);
		$fields[] = array(
			'name' => __( 'Heading Text', 'better-studio' ),
			'id'   => 'typo_text_heading',
			'std'  => __( 'This is a test heading text', 'better-studio' ),
			'type' => 'text',
		);
		$fields[] = array(
			'name' => __( 'Paragraph Text', 'better-studio' ),
			'id'   => 'typo_text_paragraph',
			'std'  => __( 'Grumpy wizards make toxic brew for the evil Queen and Jack. One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin.', 'better-studio' ),
			'type' => 'textarea',
		);
		$fields[] = array(
			'name' => __( 'Divided Text', 'better-studio' ),
			'id'   => 'typo_text_divided',
			'std'  => __( 'a b c d e f g h i j k l m n o p q r s t u v w x y z
A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
0123456789 (!@#$%&.,?:;)', 'better-studio' ),
			'type' => 'textarea',
		);


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
			'file_name' => 'custom-fonts-backup',
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

		$options[ $this->option_panel_id ] = array(
			'panel-name' => _x( 'Font Manager', 'Panel title', 'better-studio' ),
			'panel-desc' => '<p>' . __( 'Upload custom fonts and add CSS font stacks.', 'better-studio' ) . '</p>',
			'fields'     => $fields,

			'config' => array(
				'name'                => __( 'Font Manager', 'better-studio' ),
				'parent'              => 'better-studio',
				'slug'                => 'better-studio/fonts-manager',
				'page_title'          => __( 'Font Manager', 'better-studio' ),
				'menu_title'          => __( 'Font Manager', 'better-studio' ),
				'capability'          => 'manage_options',
				'menu_slug'           => __( 'Font Manager', 'better-studio' ),
				'notice-icon'         => BF_URI . 'assets/img/bs-notice-logo.png',
				'icon_url'            => NULL,
				'position'            => 100.01,
				'exclude_from_export' => FALSE,
				'register_menu'       => apply_filters( 'better-framework/fonts-manager/show-menu', TRUE ),
			),

			'texts' => array(
				'panel-desc-lang'     => '<p>' . __( '%s Language Fonts.', 'better-studio' ) . '</p>',
				'panel-desc-lang-all' => '<p>' . __( 'All Languages Fonts.', 'better-studio' ) . '</p>',

				'reset-button'     => ! empty( $lang ) ? sprintf( __( 'Reset %s Fonts', 'better-studio' ), $lang ) : __( 'Reset Fonts', 'better-studio' ),
				'reset-button-all' => __( 'Reset All Fonts', 'better-studio' ),

				'reset-confirm'     => ! empty( $lang ) ? sprintf( __( 'Are you sure to reset %s fonts?', 'better-studio' ), $lang ) : __( 'Are you sure to reset fonts?', 'better-studio' ),
				'reset-confirm-all' => __( 'Are you sure to reset all fonts?', 'better-studio' ),

				'save-button'     => ! empty( $lang ) ? sprintf( __( 'Save %s Fonts', 'better-studio' ), $lang ) : __( 'Save Fonts', 'better-studio' ),
				'save-button-all' => __( 'Save All Fonts', 'better-studio' ),

				'save-confirm-all' => __( 'Are you sure to save all fonts? this will override specified fonts per languages', 'better-studio' )
			),


		);

		return $options;

	}


	/**
	 * Filter callback: Used for resetting current language on resetting panel
	 *
	 * @param   $options
	 * @param   $result
	 *
	 * @return array
	 */
	function callback_panel_reset_result( $result, $options ) {

		// check panel
		if ( $options['id'] != $this->option_panel_id ) {
			return $result;
		}

		// change messages
		if ( $result['status'] == 'succeed' ) {
			$result['msg'] = __( 'Font manager reset to default.', 'better-studio' );
		} else {
			$result['msg'] = __( 'An error occurred while resetting font manager.', 'better-studio' );
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
			$result['msg'] = __( 'Font manager options imported successfully.', 'better-studio' );
		} else {
			if ( $result['msg'] == __( 'Imported data is not for this panel.', 'better-studio' ) ) {
				$result['msg'] = __( 'Imported translation is not for fonts manager.', 'better-studio' );
			} else {
				$result['msg'] = __( 'An error occurred while importing font manager options.', 'better-studio' );
			}
		}

		return $result;
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
				$output['msg'] = __( 'Fonts saved.', 'better-studio' );
			} else {
				$output['msg'] = __( 'An error occurred while saving fonts.', 'better-studio' );
			}
		}

		return $output;
	}
}