<?php

if( isset( $options['panel_id'] ) && ! empty( $options['panel_id'] ) ){
    $panel_id = 'data-panel_id="' . esc_attr( $options['panel_id'] ) . '"';
}else{
    return '';
}

?>

<input type="file" <?php echo $panel_id; ?> name="bf-import-file-input" id="bf-import-file-input" class="bf-import-file-input">

<a class="bf-import-upload-btn bf-button bf-main-button"><i class="fa fa-upload"></i><?php _e( 'Import', 'better-studio' ); ?></a>