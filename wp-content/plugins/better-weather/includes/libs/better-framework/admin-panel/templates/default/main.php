<?php
$template = $this->template;

$lang = bf_get_current_lang();

?>
<div class="panel-wrapper">
	<div id="bf-panel"
	     class="panel-<?php echo esc_attr( $template['id'] ); ?> <?php echo isset( $template['css-class'] ) ? implode( ' ', $template['css-class'] ) : ''; ?>">

		<header class="bf-page-header">
			<div class="bf-page-header-inner bf-clearfix">
				<h2 class="page-title"><?php echo $template['data']['panel-name']; ?></h2>
				<?php
				if ( ! empty( $template['desc'] ) ) {
					echo '<div class="page-desc">' . $template['desc'] . '</div>';
				} ?>

				<div class="reset-sec">
					<div class="btn-sec">
						<a class="fleft bf-button bf-reset-button"
						   data-confirm="<?php echo $lang == 'all' ? $template['texts']['reset-confirm-all'] : $template['texts']['reset-confirm']; ?>"><i
								class="bsai-reset-clean"></i><?php echo $lang == 'all' ? $template['texts']['reset-button-all'] : $template['texts']['reset-button']; ?>
						</a>
					</div>
				</div>
				<div class="btn-sec">
					<a class="fright bf-save-button bf-button bf-main-button"
					   data-confirm="<?php echo $lang == 'all' ? $template['texts']['save-confirm-all'] : $template['texts']['save-confirm']; ?>"><i
							class="bsai-save-clean"></i> <?php echo $lang == 'all' ? $template['texts']['save-button-all'] : $template['texts']['save-button']; ?>
					</a>
					<input type="hidden" id="bf-panel-id" value="<?php echo esc_attr( $template['id'] ); ?>"/>
				</div>

				<div class="bf-options-change-notice" style="display: none;">
					<?php _e('Options Changed', 'better-studio') ?>
				</div>
			</div>
		</header>

		<div id="bf-main" class="bf-clearfix">

			<div id="bf-nav"><?php echo $template['tabs']; ?></div>

			<div id="bf-content">
				<form id="bf_options_form">
					<?php echo $template['fields']; ?>
				</form>
			</div>
		</div>

		<div class="bf-loading">
			<div class="loader">
				<div class="loader-icon in-loading-icon "><i class="dashicons dashicons-update"></i></div>
				<div class="loader-icon loaded-icon"><i class="dashicons dashicons-yes"></i></div>
				<div class="loader-icon not-loaded-icon"><i class="dashicons dashicons-no-alt"></i></div>
				<div class="message"><?php _e( 'An Error Occurred!', 'better-studio' ); ?></div>
			</div>
		</div>
	</div>
</div>