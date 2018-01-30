<?php

/**
 * Used for adding custom fields to Taxonomy fields
 */
class BF_Taxonomy_Meta_Field{

    /**
     * Contain config and fields for Taxonomy
     *
     * @var array
     */
    private $taxonomy_fields;


    /**
     * Initialize Taxonomy Meta
     *
     * @param $tax
     */
    public function __construct( $tax ){

        $this->taxonomy_fields = $tax;

        if( ! is_array( $this->taxonomy_fields['config']['taxonomies'] ) ) {

            add_action( $this->taxonomy_fields['config']['taxonomies'].'_add_form_fields', array( $this, 'add_form_fields' ), 10, 2);
            add_action( $this->taxonomy_fields['config']['taxonomies'].'_edit_form', array( $this, 'add_form_fields' ), 10, 2);

            add_action( 'create_'.$this->taxonomy_fields['config']['taxonomies'], array( $this, 'save_fields' ) );
            add_action( 'edited_'.$this->taxonomy_fields['config']['taxonomies'], array( $this, 'save_fields' ), 10, 2);

        }else{

            foreach( $this->taxonomy_fields['config']['taxonomies'] as $taxonomy ){

                add_action( $taxonomy.'_add_form_fields', array( $this, 'add_form_fields' ), 10, 2);
                add_action( $taxonomy.'_edit_form', array( $this, 'add_form_fields' ), 10, 2);

                add_action( 'create_'.$taxonomy, array( $this, 'save_fields' ) );
                add_action( 'edited_'.$taxonomy, array( $this, 'save_fields' ) );

            }

        }

        add_action( 'delete_term', array( __CLASS__, 'delete_tax_data'), 10, 2 );
    }


    /**
     * Adds fields to add and edit form in Taxonomy admin form
     *
     * @param null
     */
    public function add_form_fields( $term = null ){

        $values = array();

        if( is_object( $term ) ){
            $term_id  = $term->term_id;
        }
        else{
            $term_id = '';
        }

        foreach( $this->taxonomy_fields['fields'] as $key => $field ){

            if( $field['type'] == 'tab' || $field['type'] == 'group' )
                continue;

            //  this returns default value if term mta is not saved before
            $values[$field['id']] = bf_get_term_meta( $field['id'], $term_id );

        }

        $options = $this->taxonomy_fields;

        $front_end = new BF_Taxonomy_Front_End_Generator( $options, $term_id, $values );

        echo $front_end->callback();

    }


    /**
     * Save taxonomy custom options as an option
     *
     * @param $term_id
     */
    public function save_fields( $term_id ){

        if( ! isset( $_POST['bf-term-meta'] ) )
            return;

        if( isset( $this->taxonomy_fields['panel-id'] ) ){
            $std_id = Better_Framework::options()->get_std_field_id( $this->taxonomy_fields['panel-id'] );
        }else{
            $std_id = 'std';
        }

        foreach( $this->taxonomy_fields['fields'] as $key => $field ){

            // add / update meta
            if( isset( $_POST['bf-term-meta'][$key] ) ){

                $save = true;
                // check for saving default or not!?
                if( isset( $field['save-std'] ) && ! $field['save-std'] ){

                    if( isset( $field[$std_id] ) && $_POST['bf-term-meta'][$key] == $field[$std_id] )
                        $save = false;
                    elseif( isset( $field['std'] ) && $_POST['bf-term-meta'][$key] == $field['std'] )
                        $save = false;
                }
	            if ( $save === false ) {
		            bf_delete_term_meta( $term_id, $key );
	            } else {
                    bf_update_term_meta( $term_id, $key, $_POST['bf-term-meta'][ $key ] );
	            }
            }
        }
    }


    /**
     * Delete taxonomy option from option table
     *
     * @param $term
     * @param $term_id
     */
    public static function delete_tax_data( $term, $term_id ){
        if($all_meta = bf_get_term_meta( '', $term_id )) {
            foreach($all_meta as $meta_key => $meta_value) {
                bf_delete_term_meta($term_id, $meta_key);
            }
        }
    }
}