<?php


add_action( 'admin_init', 'better_studio_theme_updater' );


function better_studio_theme_updater(){

    $update_data = apply_filters( 'better-studio-theme-updater/update-data', array() );

    // If no data added to updater
    if( count( $update_data ) <= 0 )
        return;

    // Theme name is not defined
    if( ! isset( $update_data['theme_name'] ) || empty( $update_data['theme_name'] ) )
        return;

    // Theme slug is not defined
    if( ! isset( $update_data['theme_slug'] ) || empty( $update_data['theme_slug'] ) )
        return;

    // Theme Envato id is not defined
    if( ! isset( $update_data['theme_id'] ) || empty( $update_data['theme_id'] ) )
        return;

    // User name is not defined
    if( ! isset( $update_data['user_name'] ) || empty( $update_data['user_name'] ) )
        return;

    // API key is not defined
    if( ! isset( $update_data['api_key'] ) || empty( $update_data['api_key'] ) )
        return;

    // load manager
    require_once 'class-better-studio-theme-updater.php';

    // Fire up manager
    new Better_Studio_Theme_Updater( $update_data['theme_name'], $update_data['theme_slug'], $update_data['theme_id'], $update_data['user_name'], $update_data['api_key'] );

}