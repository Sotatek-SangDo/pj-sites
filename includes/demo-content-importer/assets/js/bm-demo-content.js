var BM_Demo_Content_Importer = (function($) {
    "use strict";

    return {

        init: function(){
            
            $('.better-import-demo-content').click(function() {

                // Importing
                if( $(this).hasClass('importing') || $(this).hasClass('completed') ){
                    return false;
                }

                // Confirm regeneration
                if( ! confirm( bm_demo_content_importer_loc.text_confirm ) )
                    return;

                // Prepare loading
                jQuery(".better-import-demo-content").html(bm_demo_content_importer_loc.text_loading).addClass('importing');

               jQuery.ajax({
                    url: bm_demo_content_importer_loc.ajax_url,
                    type: "POST",
                    data: {
                        action:             'bm_import_demo_content'
                    },
                    success: function( data ){

                        var result = JSON.parse( data );

                        if( result.status == 'success' ){

                            jQuery(".pre-desc").html(bm_demo_content_importer_loc.text_show_site);
                            jQuery(".better-import-demo-content").html(bm_demo_content_importer_loc.text_done).addClass('completed')
                                .find('.text-1 span').html(result.message);


                        }else{

                            jQuery(".better-import-demo-content").addClass('error').html(bm_demo_content_importer_loc.text_error)
                                .find('.text-1 span').html(result.message);
                        }

                   },
                   error: function(request, status, error) {
                       jQuery(".better-import-demo-content").addClass('error').html(bm_demo_content_importer_loc.text_no_image);

                   }
               });


            });

        }
        //
        //
        //rebuild_next_image: function(){
        //
        //    // If Finished
        //    if( BM_Demo_Content_Importer.current_step >= BM_Demo_Content_Importer.imagesList.length ){
        //        jQuery(".better-import-demo-content").addClass('completed');
        //        jQuery(".thumbnails-rebuild-wrapper .pre-desc").addClass('completed');
        //        jQuery(".rebuild-log-container").slideDown('400');
        //        jQuery(".better-import-demo-content .loader").css('width', '100%').html(bm_demo_content_importer_loc.text_done);
        //        return;
        //    }
        //
        //    jQuery.ajax({
        //        url: bm_demo_content_importer_loc.ajax_url,
        //        type: "POST",
        //        data: {
        //            action:     'BRT_rebuild_image',
        //            id:         BM_Demo_Content_Importer.imagesList[BM_Demo_Content_Importer.current_step].id,
        //            title:      BM_Demo_Content_Importer.imagesList[BM_Demo_Content_Importer.current_step].title
        //        },
        //        success: function(data) {
        //
        //            var result = JSON.parse( data );
        //
        //            // Show image preview
        //            if( result.status == 'success' ){
        //                jQuery(".thumbnails-rebuild-wrapper .pre-desc img").attr("src",result.url);
        //            }
        //
        //            // Update loader
        //            BM_Demo_Content_Importer.plus_loader();
        //
        //            // Log result
        //            BM_Demo_Content_Importer.log( result );
        //
        //            // Rebuild next image with ajax
        //            BM_Demo_Content_Importer.rebuild_next_image();
        //        }
        //    });
        //
        //},
        //
        //plus_loader: function(){
        //
        //    BM_Demo_Content_Importer.current_step = BM_Demo_Content_Importer.current_step + 1;
        //
        //    jQuery(".better-import-demo-content .loader").css('width', ( BM_Demo_Content_Importer.current_step * BM_Demo_Content_Importer.step ) + '%' );
        //
        //    // update building text
        //    var temp = bm_demo_content_importer_loc.text_rebuilding_state;
        //    temp = temp.replace('%number%', BM_Demo_Content_Importer.current_step == 0 ? 1 : BM_Demo_Content_Importer.current_step );
        //    temp = temp.replace('%all%', BM_Demo_Content_Importer.imagesList.length );
        //    jQuery(".better-import-demo-content .text-1 span, .better-import-demo-content .text-2 span").html( temp );
        //
        //},
        //
        //log: function( result ){
        //
        //    var tempHTML = $('.rebuild-log-container .rebuild-log ol').html();
        //
        //    tempHTML += result.message;
        //
        //    $('.rebuild-log-container .rebuild-log ol').html(tempHTML);
        //
        //    // Scroll down
        //    $('.rebuild-log-container .rebuild-log').animate({
        //        scrollTop:$('.rebuild-log-container .rebuild-log')[0].scrollHeight - $('.rebuild-log-container .rebuild-log').height()
        //    },400);
        //}

    };

})(jQuery);

// load when ready
jQuery(function($) {

    BM_Demo_Content_Importer.init();

});