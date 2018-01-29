<?php
$classes = $this->get_classes( $options );
$iri     = isset( $options['repeater_item'] ) && $options['repeater_item'] == true; // Is this section for a repeater item

$section_classes  = $classes['section'];

$heading_classes  = $classes['heading'];
$controls_classes = $classes['controls'];
$explain_classes = $classes['explain'];

if( $iri ) {

    $section_classes  .= ' ' . $classes['repeater-section'];
    $heading_classes  .= ' ' . $classes['repeater-heading'];
    $controls_classes .= ' ' . $classes['repeater-controls'];
    $explain_classes  .= ' ' . $classes['repeater-explain'];

} else {

    $section_classes  .= ' ' . $classes['nonrepeater-section'];
    $heading_classes  .= ' ' . $classes['nonrepeater-heading'];
    $controls_classes .= ' ' . $classes['nonrepeater-controls'];
    $explain_classes  .= ' ' . $classes['nonrepeater-explain'];

}

$section_classes  .= ' ' . $classes['section-class-by-filed-type'];
$heading_classes  .= ' ' . $classes['heading-class-by-filed-type'];
$controls_classes .= ' ' . $classes['controls-class-by-filed-type'];
$explain_classes  .= ' ' . $classes['explain-class-by-filed-type'];

?>
<div class="bf-section-container bf-clearfix">
    <div class="<?php echo esc_attr( $section_classes ); ?> bf-clearfix" data-id="<?php echo esc_attr( $options['id'] ); ?>">

        <div class="<?php echo esc_attr( $heading_classes ); ?> bf-clearfix">
            <h3><label><?php echo $options['name']; ?></label></h3>
        </div>

        <div class="<?php echo esc_attr( $controls_classes ); ?> bf-clearfix">
            <?php echo $input; ?>
        </div>

        <?php if ( !empty( $options['desc'] ) ||
            ( isset( $options['preview'] ) && $options['preview'] )
        ) { ?>
            <div class="<?php echo esc_attr( $explain_classes ); ?> bf-clearfix">
                <?php if(  isset( $options['preview'] ) && $options['preview'] ){ ?>

                    Preview

                    <hr/>

                <?php } ?>

                <?php echo $options['desc']; ?>
            </div>
        <?php } ?>

    </div>
</div>