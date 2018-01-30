<?php
$output = $title = $options = $el_class = '';
extract( shortcode_atts( array(
	'title' => Better_Translation()->_get( 'widget_archives' ),
	'options' => '',
	'el_class' => ''
), $atts ) );
$options = explode( ",", $options );
if ( in_array( "dropdown", $options ) ) $atts['dropdown'] = true;
if ( in_array( "count", $options ) ) $atts['count'] = true;

$el_class = $this->getExtraClass( $el_class );

$output = '<div class="vc_wp_archives wpb_content_element' . $el_class . '">';
$type = 'WP_Widget_Archives';
$args = array(
    'before_title'  =>  '<h4 class="section-heading"><span class="h-title">',
    'after_title'   =>  '</span></h4>',
    'before_widget' =>  '<div id="%1$s" class="primary-sidebar-widget widget %2$s">',
    'after_widget'  =>  '</div>'
);

ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();

$output .= '</div>' . $this->endBlockComment( 'vc_wp_archives' ) . "\n";

echo $output;