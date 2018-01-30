<?php
/**
 * Manage all functionality for generating fields and retrieving fields data from them
 */
class BF_Taxonomy_Core {

    /**
     * Contain all options that retrieved from better-framework/taxonomy/options and used for generating forms
     *
     * @var array
     */
    public $taxonomy_options = array();


    /**
     * Used for protect redundancy loading
     *
     * @var bool
     */
    public $options_loaded = false;


    /**
     * Used for caching terms data for next calls
     *
     * @var array
     */
    public $cache = array();


    /**
     *
     */
    function __construct(){
        add_action( 'admin_init', array( $this, 'register_taxonomies' ) );
    }


    /**
     * Filter all taxonomy options
     *
     * @param bool $fresh_load
     */
    function load_options( $fresh_load = false ){

        // load if not loaded before
        if( $this->options_loaded && $fresh_load )
            return;

        $this->taxonomy_options = apply_filters( 'better-framework/taxonomy/options', array() );

    }


    /**
     * Register taxonomy fields
     */
    function register_taxonomies(){

        // Load all options
        $this->load_options();

        foreach( $this->taxonomy_options as $taxonomy ){
            new BF_Taxonomy_Meta_Field( $taxonomy );
        }

    }


    /**
     * Return all taxonomy options
     *
     * @return array
     */
    public function get_taxonomy_options(){

        // Load all options
        $this->load_options();

        return $this->taxonomy_options;

    }


    /**
     * Deprecated: Use bf_get_term_meta
     *
     * Used For retrieving meta of term
     *
     * @param   int|object  $term       Term ID or object
     * @param   string      $meta_id    Custom Field ID
     * @param   bool|string $default    Default Value
     *
     * @return bool
     */
    public function get_term_meta( $term, $meta_id, $default = null ){

        return bf_get_term_meta( $meta_id, $term, $default );

    }
}