<?php

// Fire up icon factory
Better_Framework::factory('icon-factory');

// Get fontawesome instance
$fontawesome  = BF_Icons_Factory::getInstance('fontawesome');

// Default selected
$current = array(
	'key'   => '' ,
	'title' => '',
);

?>
<div id="better-icon-modal" class="better-modal icon-modal" data-remodal-id="better-icon-modal" role="dialog">
	<div class="modal-inner">

		<div class="modal-header">
			<span><?php _e( 'Chose an Icon', 'better-studio' ); ?></span>
			<div class="better-icons-search bf-clearfix">
				<input type="text" class="better-icons-search-input" placeholder="<?php _e( 'Search...', 'better-studio' ); ?>"/>
				<i class="clean fa fa-search"></i>
			</div>
		</div><!-- modal header -->

		<div class="modal-body bf-clearfix">

			<div class="icons-container bf-clearfix">

				<div class="icons-inner bf-clearfix">
					<?php

					$custom_icons = get_option( Better_Framework::factory('icon-factory')->get_custom_icons_id() );

					?>
					<h2 class="font-type-header">
						<span class="title"><?php _e( 'Custom Icons', 'better-studio' ); ?></span>
						<span data-button-text="<?php _e( 'Select Icon', 'better-studio' ); ?>" data-media-title="<?php _e( 'Select Icon', 'better-studio' ); ?>" class="upload-custom-icon"><i class="bf-icon fa fa-upload "></i> <?php _e( 'Upload Custom Icon', 'better-studio' ); ?></span>
					</h2>
					<ul class="icons-list custom-icons-list bf-clearfix">
						<?php

						if( $custom_icons )
							foreach( (array) $custom_icons as $icon){

								?>

								<li data-id="<?php echo $icon['id']; ?>" class="icon-select-option custom-icon" data-custom-icon="<?php echo esc_attr( $icon['icon'] ); ?>" data-width="<?php echo esc_attr( $icon['width'] ); ?>" data-height="<?php echo esc_attr( $icon['height'] ); ?>" data-type="custom-icon">
									<?php echo bf_get_icon_tag( $icon ); ?>
									<i class="fa fa-close delete-icon"></i>
								</li>

								<?php
							}

						?>
					</ul><!-- icons list -->

					<p class="no-custom-icon <?php echo $custom_icons ? 'hidden' : ''; ?>"><?php _e( 'No custom icon created!.', 'better-studio' ); ?></p>

					<h2 class="font-type-header"><span class="title"><?php _e( 'Fontawesome Icons', 'better-studio' ); ?></span></h2>
					<ul class="icons-list bf-clearfix">
						<li data-value="" data-label="<?php _e( 'Chose an Icon', 'better-studio' ); ?>" class="icon-select-option default-option">
	                        <p></p>
						</li>
						<?php

						foreach( (array) $fontawesome->icons as $key => $icon){
							$_cats = '';

							if( isset( $icon['category'] ) )
								foreach($icon['category'] as $category){
									$_cats .= ' cat-'.$category;
								}
								?>

							<li data-value="<?php echo $key; ?>" data-label="<?php echo esc_attr( $icon['label'] ); ?>" data-categories="<?php echo $_cats; ?>" class="icon-select-option <?php echo ( $key === $current['key'] ? 'selected' : '' ) . $_cats; ?>"  data-type="fontawesome">
                                <?php echo $fontawesome->getIconTag( $key ); ?> <span class="label"><?php echo $icon['label']; ?></span>
                            </li>

							<?php
						}

						?>
					</ul><!-- icons list -->
				</div><!-- /icons inner -->
			</div><!-- /icons container -->

			<div class="cats-container bf-clearfix">

				<ul class="better-icons-category-list bf-clearfix">
					<li class="icon-category selected" id="cat-all">
						<span data-cat="#cat-all"><?php _e( 'All ', 'better-studio' ); ?></span> <span class="text-muted">(<?php echo count( $fontawesome->icons ); ?>)</span>
					</li>
					<?php

					foreach( (array)$fontawesome->categories as $key => $category){

						?>
						<li class="icon-category" id="cat-<?php echo esc_attr( $category['id'] ); ?>">
							<span data-cat="#cat-<?php echo esc_attr( $category['id'] ); ?>"><?php echo $category['label']; ?></span> <span class="text-muted">(<?php echo $category['counts'] ?>)</span>
						</li>
						<?php
					}

					?>
				</ul><!-- categories list -->

			</div><!-- /cats container -->

			<div class="upload-custom-icon-container">
				<div class="upload-custom-icon-inner">
					<div class="custom-icon-fields">

						<div class="section-header">
							<span><?php _e( 'Inset Custom Icon', 'better-studio' ); ?></span>
						</div>

						<div class="section-body">
							<span class="icon-helper"></span>
							<img src="" class="icon-preview">
						</div>

						<div class="icon-fields">
							<?php _e( 'Width:', 'better-studio' ); ?> <input type="text" name="icon-width" placeholder="<?php _e( 'Auto', 'better-studio' ); ?>">
							<?php _e( 'Height:', 'better-studio' ); ?> <input type="text" name="icon-height" placeholder="<?php _e( 'Auto', 'better-studio' ); ?>">
						</div>

						<div class="section-footer">
							<a href="#" class="bf-button bf-main-button"><?php _e( 'Insert Icon', 'better-studio' ); ?></a>
						</div>
					</div>
					<div class="icon-uploader-loading">
						<i class="fa fa-refresh fa-spin"></i>
					</div>

					<i class="close-custom-icon fa fa-close"></i>
				</div>
			</div>
		</div><!-- /modal body -->
	</div><!-- /modal inner -->
</div><!-- /modal -->