<?php

if( isset( $options['file_name'] ) && ! empty( $options['file_name'] ) ){
    $file_name = 'data-file_name="' . esc_attr( $options['file_name'] ) . '"';
}else{
    $file_name = '';
}


if( isset( $options['panel_id'] ) && ! empty( $options['panel_id'] ) ){
    $panel_id = 'data-panel_id="' . esc_attr( $options['panel_id'] ) . '"';
}else{
    return '';
}


?>
<div>
    <a class="bf-button bf-main-button" id="bf-download-export-btn" <?php echo $file_name; ?> <?php echo $panel_id; ?>><i class="fa fa-download"></i> <?php _e( 'Download Backup', 'better-studio' ); ?></a>
</div>