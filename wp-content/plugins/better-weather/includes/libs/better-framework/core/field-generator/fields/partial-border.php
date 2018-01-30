<div class="single-border border-<?php echo esc_attr( $border['type'] ); ?>"><?php

    if( isset( $border['label'] ) ){
        echo '<span class="border-label">'  . $border['label'] . '</span>';
    }

    if( in_array( 'width', $border ) ){
        ?>
    <span class="bf-field-with-suffix bf-field-with-prefix border-width">
        <span class='bf-prefix-suffix bf-prefix'><?php _e('Width:', 'better-studio'); ?> </span><input type="text" name="<?php echo esc_attr( $options['input_name'] ); ?>[<?php echo esc_attr( $border['type'] ); ?>][width]" value="<?php echo esc_attr( $options['value'][$border['type']]['width'] ); ?>" class="border-width"/><span class='bf-prefix-suffix bf-suffix'>px</span>
    </span>
        <?php
    }// width


    if( in_array( 'style', $border ) ){
    ?>

        <span class="border-style-container"><?php
            $styles = array(
                'dotted'        =>  __( 'Dotted', 'better-studio' ),
                'dashed'        =>  __( 'Dashed', 'better-studio' ),
                'solid'         =>  __( 'Solid', 'better-studio' ),
                'double'        =>  __( 'Double', 'better-studio' ),
                'groove'        =>  __( 'Groove', 'better-studio' ),
                'ridge'         =>  __( 'Ridge', 'better-studio' ),
                'inset'         =>  __( 'Inset', 'better-studio' ),
                'outset'        =>  __( 'Outset', 'better-studio' ),
            );

            ?>
            <select name="<?php echo esc_attr( $options['input_name'] ); ?>[<?php echo esc_attr( $border['type'] ); ?>][style]" class="border-style">
                <?php foreach( $styles as $key => $style ){
                    echo '<option value="'. esc_attr( $key ) . '" '. ( $key == $options['value'][$border['type']]['style'] ? 'selected':'' ) . '>' . $style . '</option>';
                }?>
            </select>
        </span>

    <?php
    } //style

    if( in_array( 'color', $border ) ){

        echo '<span>';

        $input   = Better_Framework::html()->add( 'input' )->type( 'text' )->name( $options['input_name'] . '[' . $border['type'] . '][color]' )->class( 'bf-color-picker' );

        $preview = Better_Framework::html()->add( 'div' )->class( 'bf-color-picker-preview' );

        if( ! empty( $options['value'][$border['type']]['color']  ) ){
            $input->value( $options['value'][$border['type']]['color'] )->css('border-color', $options['value'][$border['type']]['color']);
            $preview->css( 'background-color', $options['value'][$border['type']]['color'] );
        }

        echo $input->display();
        echo $preview->display();
        echo '</span>';

    }

    ?>
</div>