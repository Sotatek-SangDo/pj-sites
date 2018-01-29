<?php
$output = $title = $el_class = $nav_menu = '';
extract( shortcode_atts( array(
	'title' => '',
	'nav_menu' => '',
	'el_class' => ''
), $atts ) );
$el_class = $this->getExtraClass( $el_class );

$output = '<div class="vc_wp_custommenu wpb_content_element' . $el_class . '">';
$type = 'WP_Nav_Menu_Widget';
$args = array(
    'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
    'after_title'   =>  '</span></h4>',
    'before_widget' =>  '<div id="%1$s" class="primary-sidebar-widget widget %2$s">',
    'after_widget'  =>  '</div>'
);


ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();

$output .= '</div>' . $this->endBlockComment( 'vc_wp_custommenu' ) . "\n";

echo $output;