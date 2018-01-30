<?php

if( ! class_exists( 'Better_Framework_Factory' ) ){

    class Better_Framework_Factory{

        static $frameworks = array();

        static $active_framework;

        public static function setup_framework(){

            self::$frameworks = apply_filters( 'better-framework/loader', array() );

            if( count( self::$frameworks ) == 1 ){
                self::load_framework( current( self::$frameworks ) );
            }else{

                $latest_version = null;

                foreach( self::$frameworks as $framework ){

                    if( $latest_version == null ){
                        $latest_version = $framework;
                        continue;
                    }

                    if( version_compare( $latest_version['version'], $framework['version'] ) <= 0 ){
                        $latest_version = $framework;
                    }

                }

                self::$active_framework = $latest_version;

                self::load_framework( $latest_version );

            }

            /**
             * Fires after BetterFramework fully loaded.
             */
            do_action( 'better-framework/after_setup' );
        }


        /**
         * Loads framework
         *
         * @param $framework
         */
        public static function load_framework( $framework ){

            define( 'BF_URI' , trailingslashit( $framework['uri'] ) );
            define( 'BF_PATH' , trailingslashit( $framework['path'] ) );

            include_once $framework['path'] . 'class-better-framework.php';

        }

    }

    add_action( 'after_setup_theme', array( 'Better_Framework_Factory', 'setup_framework' ) );
}