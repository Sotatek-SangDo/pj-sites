var Better_Admin_Sticky_Sidebar = (function($) {
    "use strict";

    return {

        init: function(){

            // Save field changes
            this.save_sticky_field();

        },

        /**
         * Define elements that use elementQuery on local/cross domain
         */
        save_sticky_field: function(){

            $(document).on( 'change', ".better-sticky-sidebar-fields input", function( e ) {

                var $container = $(this);

                // If children clicked then find the man container
                if( typeof $( e.target ).data('sidebar') == 'undefined' ){
                    $container = $( e.target).closest('.better-sticky-sidebar-fields');
                }

                var $checkbox  = $container.find( 'input' );

                $container.toggleClass('in-loading');

                jQuery.ajax({
                    url: better_sticky_sidebar_loc.ajax_url,
                    type: "POST",
                    data: {
                        action:     'better_sticky_sidebar',
                        sidebar:    $container.data('sidebar'),
                        active:     $checkbox.prop("checked"),
                        input:      '#' + $checkbox.prop("id")
                    },
                    success: function( result ){

                        $container.toggleClass('in-loading');

                        result = JSON.parse(result);

                        if( result.status == 'success' ){

                            if( result.active == 'true' )
                                $container.addClass('is-sticky');
                            else
                                $container.removeClass('is-sticky');

                        }else{

                            $container.addClass( 'is-error' );

                            setTimeout( function(){
                                $container.removeClass( 'is-error' );
                            }, 1000 );

                            if( result == 'active' ){
                                $checkbox.prop( "checked", true );
                            }else{
                                $checkbox.prop( "checked", false );
                            }

                        }

                    }
                });

            });

        }

    };// /return
})(jQuery);

// Load when ready
jQuery(function($) {

    Better_Admin_Sticky_Sidebar.init();

});