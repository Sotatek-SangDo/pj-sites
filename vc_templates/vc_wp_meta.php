<?php
$output = $title = $el_class = '';
extract( shortcode_atts( array(
	'title' => Better_Translation()->_get( 'widget_meta' ),
	'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );

$output = '<div class="vc_wp_meta wpb_content_element' . $el_class . '">';
$type = 'WP_Widget_Meta';
$args = array(
    'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
    'after_title'   =>  '</span></h4>',
    'before_widget' =>  '<div id="%1$s" class="primary-sidebar-widget widget %2$s">',
    'after_widget'  =>  '</div>'
);


ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();

$output .= '</div>' . $this->endBlockComment( 'vc_wp_meta' ) . "\n";

echo $output;