<?php

/**
 * Handles admin functionality of shortcodes
 */
class BetterStudio_Editor_Shortcodes {

    function __construct(){

        if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
            return;
        }

        // Check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {

            add_filter('mce_buttons', array( $this, 'editor_button' ) );

            add_filter( 'mce_external_plugins', array( $this, 'editor_plugin' ) );

            add_action('wp_ajax_betterstudio_editor_shortcode_plugin', array( $this, 'render_plugin_js' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'editor_style' ) );
        }

    }


    /**
     * Filter Callback: Adds style
     */
    public function editor_style(){

        wp_enqueue_style( 'betterstudio-editor-shortcodes', BetterStudio_Shortcodes::instance()->url( 'css/bs-shortcodes-editor.css' ) );

    }


    /**
     * Filter Callback: Adds shortcode list button to TinyMCE
     *
     * @param array $buttons
     * @return array
     */
    public function editor_button( $buttons ){

        array_push( $buttons, 'separator', 'betterstudio_shortcodes');

        return $buttons;
    }


    /**
     * Filter Callback: Registers js file for shortcode button
     *
     * @param $plugin_array
     */
    public function editor_plugin( $plugin_array ) {

        $plugin_array['betterstudio_shortcodes'] = admin_url( 'admin-ajax.php' ) . '?action=betterstudio_editor_shortcode_plugin';

        return $plugin_array;
    }


    /**
     * Render item and all nested items ( unlimited child )
     *
     * @param $item_key
     * @param $item
     * @param bool $echo
     * @return string
     */
    public function render_item( $item_key, $item, $echo = true ){

        $output = '';

        // Renders simple buttons
        if( isset( $item['type'] ) && $item['type'] == 'button' ){

            $output .= $this->render_single_button( $item_key, $item, false );

        }
        // Renders Separator
        if( isset( $item['type'] ) && $item['type'] == 'separator' ){

            $output .= $this->render_separator( false );

        }

        // Renders drop down menu items
        elseif( isset( $item['type'] ) && $item['type'] == 'menu' ){

            if( isset( $item['items'] ) ){

                $output .= $this->render_menu( $item_key, $item, false );

            }

        }

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }

    }


    /**
     * Renders Separator
     *
     * @param bool $echo
     * @return string
     */
    public function render_separator( $echo = true ){

        $output = "{
                    text: 'separator',";

        if( isset( $item['classes'] ) ){
            $output .= "classes: '" . $item['classes'] . ' ' . "bs-separator',";
        }else{
            $output .= "classes: 'bs-separator',";
        }

        $output .= "},";

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }

    }


    /**
     * Renders menu element
     *
     * @param $item_key
     * @param $item
     * @param bool $echo
     * @return string
     */
    public function render_menu( $item_key, $item, $echo = true ){

        $output = "{
                    text: '" . $item['label'] . "',";

        if( isset( $item['classes'] ) ){
            $output .= "classes: '" . $item['classes'] . ' ' . $item_key . "',";
        }else{
            $output .= "classes: '" . $item_key . "',";
        }

        if( isset( $item['icon'] ) ){
            $output .= "icon: '" . $item['icon'] . "',";
        }

        $output .= "menu: [";

        foreach( (array) $item['items'] as $_item_key => $_item_value ){
            $output .= $this->render_item( $_item_key, $_item_value, false );
        }

        $output .= "]},";

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }
    }


    /**
     * Used for rendering single button
     *
     * @param $item_key
     * @param $item
     * @param bool $echo
     * @return string
     */
    public function render_single_button( $item_key, $item, $echo = true ){

        $output = "
            {
                text: '" . $item['label'] . "'," ;

        if( isset( $item['classes'] ) ){
            $output .= "classes: '" . $item['classes'] . ' ' . $item_key . "',";
        }else{
            $output .= "classes: '" . $item_key . "',";
        }

        if( isset( $item['icon'] ) ){
            $output .= "icon: '" . $item['icon'] . "',";
        }

        $output .= "onclick: function() {
                    editor.insertContent('" . $item['content'] . "');
                }
            },
        ";

        if( $echo ){
            echo $output;
        }else{
            return $output;
        }

    }


    /**
     * Renders editor plugin js
     *
     * TODO Add support versions before 3.9
     */
    public function render_plugin_js(){

        // global $tinymce_version;

        // Check auth
        if( ! is_user_logged_in() || ! current_user_can( 'edit_posts') ){
            die( __( 'You do not have the right type of authorization. You must be logged in and be able to edit pages and posts.', 'betterstudio-shortcodes' ) );
        }

        // javascript will be output
        header('Content-type: application/x-javascript');

        echo "(function() {
                tinymce.PluginManager.add( 'betterstudio_shortcodes', function( editor, url ) {
                    editor.addButton( 'betterstudio_shortcodes', {
                        text: '" . __( 'Shortcodes', 'betterstudio-shortcodes' ) . "',
                        icon: 'betterstudio_shortcodes',
                        type: 'menubutton',

                        menu: [";

            foreach( BetterStudio_Shortcodes::instance()->shortcodes as $item_key => $item_value ){

                echo $this->render_item( $item_key, $item_value, false );

            }

        echo "
                    ]

                    });
                });
            })();";

        die(); // end ajax request

    }

}