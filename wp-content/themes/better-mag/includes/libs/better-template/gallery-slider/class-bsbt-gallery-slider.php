<?php

new BSBT_Gallery_Slider();

/**
 * Better Gallery Slider
 *
 * WordPress gallery wrapper for adding advanced gallery slider.
 *
 * @package  Better Gallery Slider
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.2.0
 * @access   public
 * @see      http://www.betterstudio.com
 */
class BSBT_Gallery_Slider {

	function __construct() {

		// Extends gallery fields
		add_action( 'print_media_templates', array( $this, 'extend_gallery_settings' ) );

		// Wrapper for change gallery output
		add_filter( 'post_gallery', array( $this, 'extend_gallery_shortcode' ), 10, 4 );

	}


	/**
	 * Extends gallery fields and add new ones for better gallery slider
	 */
	function extend_gallery_settings() {
		?>
		<script type="text/html" id="tmpl-bgs-gallery-setting">
			<hr style="margin: 20px 0 16px;">

			<label class="setting">
				<span><?php _e( 'Gallery Type', 'better-studio' ); ?></span>
				<select data-setting="bgs_gallery_type">
					<option value=""><?php _e( 'Default', 'better-studio' ); ?></option>
					<option value="slider"><?php _e( 'Better Gallery Slider', 'better-studio' ); ?></option>
				</select>
			</label>

			<label class="setting">
				<span><?php _e( 'Gallery Skin', 'better-studio' ); ?></span>
				<select data-setting="bgs_gallery_skin">
					<option value=""><?php _e( 'Dark (Default)', 'better-studio' ); ?></option>
					<option value="light"><?php _e( 'Light', 'better-studio' ); ?></option>
					<option value="beige"><?php _e( 'Beige', 'better-studio' ); ?></option>
				</select>
			</label>

			<label class="setting">
				<span><?php _e( 'Gallery Title', 'better-studio' ); ?></span>
				<input type="text" value="" data-setting="bgs_gallery_title"/>
			</label>

			<style>
				.media-sidebar .collection-settings .setting {
					float: none;
					clear: left;
				}
			</style>
		</script>

		<script>
			jQuery(document).ready(function () {

				_.extend(wp.media.gallery.defaults, {
					bgs_gallery_type: '',
					bgs_gallery_skin: '',
					bgs_gallery_title: ''
				});

				wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
					template: function (view) {
						return wp.media.template('gallery-settings')(view)
							+ wp.media.template('bgs-gallery-setting')(view);
					}
				});

			});

		</script>
		<?php

	} // extend_gallery_settings


	/**
	 * Extends gallery fields
	 *
	 * @param string $output - is empty !!!
	 * @param        $atts
	 * @param bool   $content
	 * @param bool   $tag
	 *
	 * @return mixed
	 */
	function extend_gallery_shortcode( $output = '', $atts, $content = FALSE, $tag = FALSE ) {

		if ( ! is_feed() && isset( $atts['bgs_gallery_type'] ) && $atts['bgs_gallery_type'] == 'slider' ) {

			// Slider title
			if ( isset( $atts['bgs_gallery_title'] ) ) {
				$slider_title = $atts['bgs_gallery_title'];
			} else {
				$slider_title = get_the_title();
			}

			$id = get_the_ID();

			if ( ! empty( $atts['include'] ) ) {

				$_attachments = get_posts( array(
					'include'        => $atts['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'orderby'        => 'post__in'
				) );

				$image_ids = array();

				foreach ( $_attachments as $key => $val ) {
					$image_ids[ $val->ID ] = $_attachments[ $key ];
				}

			} elseif ( ! empty( $atts['exclude'] ) ) {

				$image_ids = get_children( array(
					'post_parent'    => $id,
					'exclude'        => $atts['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'orderby'        => 'post__in'
				) );

			} else {

				$image_ids = get_children( array(
					'post_parent'    => $id,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'ASC',
					'orderby'        => 'menu_order ID'
				) );

			}

			// Check for valid images
			if ( count( $image_ids ) == 1 and ! is_numeric( $image_ids[0] ) ) {
				return $output;
			}

			$gallery_popup_id  = rand( 100, 1000000000 );
			$js_gallery_images = array();
			$js_gallery_descs  = array();

			$gallery_class = '';

			if ( isset( $atts['bgs_gallery_skin'] ) ) {
				$gallery_class .= ' skin-' . $atts['bgs_gallery_skin'];
			}

			$new_output = '<div id="gallery-' . $gallery_popup_id . '" class="better-gallery ' . $gallery_class . '" data-gallery-id="' . $gallery_popup_id . '">
                <div class="gallery-title clearfix">
                    <span class="main-title">' . $slider_title . '</span>
                    <span class="next"><i class="fa fa-chevron-right"></i></span>
                    <span class="prev"><i class="fa fa-chevron-left"></i></span>
                    <span class="count"><i class="current">1</i> Of <i class="total">' . count( $image_ids ) . '</i></span>
                </div>
                <div class="fotorama" data-nav="thumbs" data-auto="false" data-ratio="16/7">';


			foreach ( $image_ids as $key => $image_id ) {

				if ( is_a( $image_id, 'WP_Post' ) ) {
					$image_id = $image_id->ID;
				}

				$image = $this->get_attachment_full_info( $image_id, 'publisher-lg' );

				$image_full = $this->get_attachment_src( $image_id, 'full' );

				$image_thumb = $this->get_attachment_src( $image_id, 'thumbnail' );

				$new_output .= '<div data-thumb="' . $image_thumb['src'] . '">
                        <a href="' . $image_full['src'] . '" class="slide-link" data-not-rel="true"><img data-id="' . $key . '" src="' . $image['src'] . '"></a><div class="slide-title-wrap">';

				if ( ! empty( $image['caption'] ) ) {
					$new_output .= '<span class="slide-title">' . $image['caption'] . '</span>';
				}

				if ( ! empty( $image['alt'] ) ) {
					$new_output .= '<br><span class="slide-copy">' . $image['alt'] . '</span>';
				}

				$new_output .= '</div></div>';

				$js_gallery_images[] = "'" . $image_full['src'] . "'";
				$js_gallery_descs[]  = "'" . $image['caption'] . "'";

			}

			$new_output .= '</div></div>';

			$new_output .= "<script>";
			$new_output .= 'var prt_gal_img_' . $gallery_popup_id . " = [" . implode( ',', $js_gallery_images ) . "]; ";
			$new_output .= 'var prt_gal_cap_' . $gallery_popup_id . " = [" . implode( ',', $js_gallery_descs ) . "]; ";
			$new_output .= "</script>";

			return $new_output;
		}

		return $output;
	} // extend_gallery_shortcode


	/**
	 * Used for retrieving full information of attachment
	 *
	 * @param        $id
	 * @param string $size
	 *
	 * @return array
	 */
	function get_attachment_full_info( $id, $size = 'full' ) {

		$attachment = get_post( $id );

		$data = $this->get_attachment_src( $id, $size );

		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', TRUE ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => $data['src'],
			'title'       => $attachment->post_title,
			'width'       => $data['width'],
			'height'      => $data['height']
		);

	} // get_attachment_full_info


	/**
	 * Safe wrapper for getting an attachment image url + size information.
	 *
	 * @param        $id
	 * @param string $size
	 *
	 * @return mixed
	 */
	function get_attachment_src( $id, $size = 'full' ) {

		$image_src_array = wp_get_attachment_image_src( $id, $size );

		$data = array();

		if ( empty( $image_src_array[0] ) ) {
			$data['src'] = '';
		} else {
			$data['src'] = $image_src_array[0];
		}

		if ( empty( $image_src_array[1] ) ) {
			$data['width'] = '';
		} else {
			$data['width'] = $image_src_array[1];
		}

		if ( empty( $image_src_array[2] ) ) {
			$data['height'] = '';
		} else {
			$data['height'] = $image_src_array[2];
		}

		return $data;
	} // get_attachment_src

} // BSBT_Gallery_Slider
