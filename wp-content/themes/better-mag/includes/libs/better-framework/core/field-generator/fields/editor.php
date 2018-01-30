<?php
$options = wp_parse_args($options, array(
	'lang' => 'plain_text'
));

$options['value'] = esc_html($options['value']);

$max_lines = ! empty( $options['max-lines'] ) ? $options['max-lines'] : 15;

$min_lines = ! empty( $options['min-lines'] ) ? $options['min-lines'] : 10;

?>
<div class="bf-editor-wrapper">
	<pre class="bf-editor" data-lang="<?php echo $options['lang'] ?>" data-max-lines="<?php echo $max_lines ?>" data-min-lines="<?php echo $min_lines ?>"><?php echo $options['value'] ?></pre>

	<textarea name="<?php echo esc_attr($options['input_name']) ?>" class="bf-editor-field"><?php echo $options['value'] ?></textarea>
</div>
<?php

echo $this->get_filed_input_desc( $options );