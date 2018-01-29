var BetterMag = (function($) {
    "use strict";

    return {

        init: function(){

            // Pretty Photo Settings
            this.prettyPhotoSettings = {
                social_tools: false,
                show_title: false,
                markup: '<div class="pp_pic_holder"> \
						<div class="ppt"></div> \
						<div class="pp_content_container"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a href="#" class="pp_expand" title="Expand the image"></a> \
                                        <a class="pp_close" href="#"></a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#"><i class="fa fa-chevron-right"></i></a> \
											<a class="pp_previous" href="#"><i class="fa fa-chevron-left"></i></a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous"><i class="fa fa-backward"></i></a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next"><i class="fa fa-forward"></i></a> \
											</div> \
											<p class="pp_description"></p> \
										</div> \
									</div> \
								</div> \
							</div> \
					</div> \
					<div class="pp_overlay"></div>',
                gallery_markup: '<div class="pp_gallery"> \
								<a href="#" class="pp_arrow_previous"><i class="fa fa-chevron-left"></i></a> \
								<div> \
									<ul> \
										{gallery} \
									</ul> \
								</div> \
								<a href="#" class="pp_arrow_next"><i class="fa fa-chevron-right"></i></a> \
							</div>'
            };

            // Setup gallery
            this.gallery();

            // Setup Widgets and Shortcodes
            this.setup_widgets();

            // Setup Sliders
            this.setup_sliders();

            // Setup Video Players
            this.setup_video_players();

            // Small Fixes With jQuery For Elements Styles
            this.small_style_fixes();

            // Setup WooCommerce Related Functionality
            this.woocommerce();

            // Setup Gallery Background Slide Show
            this.setup_gallery_bg_slide_show();

            // BetterStudio Editor Shortcodes Setup
            this.betterstudio_editor_shortcodes();

            // Setup sticky menu
            this.setup_menu();

            // Define elements that use elementQuery
            this.fix_element_query();

            // Setup Back To Top Button
            this.back_to_top();

            // Setup Popup
            this.popup();


        },


        /**
         * Setup Menu
         */
        setup_menu: function() {

            BetterMag.setup_responsive_menu();

            BetterMag.setup_sticky_menu();

        },


        /**
         * Setup mobile menu from normal menu
         */
        setup_responsive_menu: function() {

            // clone navigation for mobile
            var clone = $('.main-menu .main-menu-container').clone().addClass('mobile-menu-container clearfix').removeClass('desktop-menu-container');

            // Appends cloned menu to page
            clone.appendTo('.main-menu .container');

            // Remove modals from mobile menu
            // TODO Add style for this and not delete
            clone.find("#login-modal").remove();

            // Add mobile menu
            clone.prepend( '<div class="mobile-button"><a href="#"><span class="text">' + better_mag_vars.text_navigation + '</span><span class="current"></span><i class="fa fa-bars"></i></a></div>' );

            // Show/Hide menu on click mobile-button
            $('.main-menu .main-menu-container.mobile-menu-container .mobile-button').click(function() {

                $('.main-menu .main-menu-container.mobile-menu-container').toggleClass('active');

                return false;

            });

            // Add show children list button
            $('.main-menu .main-menu-container.mobile-menu-container li > .mega-menu,.main-menu .main-menu-container.mobile-menu-container li > .sub-menu').each(function() {
                $(this).parent().append('<a href="#" class="children-button"><i class="fa fa-angle-down"></i></a>');
            });

            // Show/Hide children list on click "children-button"
            $('.main-menu .main-menu-container.mobile-menu-container li a.children-button').click(function() {

                $(this).closest('li').find('.mega-menu,.sub-menu').first().toggle().parent().toggleClass('active item-active');

                if( $(this).find('.fa-angle-down').length ){
                    $(this).find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-up');
                }else{
                    $(this).find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
                }

                return false;
            });

            // Adds current menu item name after navigation text
            var $last_current = $('.main-menu .main-menu-container.mobile-menu-container .current-menu-item').last().find('> a');
            if( $last_current.length ){

                var $mobile_button = $('.main-menu .main-menu-container.mobile-menu-container .mobile-button'),
                    current  = $mobile_button.find('.current'),
                    nav_text = $mobile_button.find('.text');

                // Adds : after navigation text if its not available currently
                if( nav_text.text().slice(-1) !== ':' ){
                    nav_text.text( nav_text.text() + ':');
                }

                // Set current item text to responsive menu button ( filter just text not elements )
                current.text( $last_current.contents()
                    .filter(function() {
                        // 3 equals to Node.TEXT_NODE
                        return this.nodeType == 3;
                    }).text()
                );
            }

        },


        /**
         * Setup sticky menu
         */
        setup_sticky_menu: function(){

            var $main_menu = $('.main-menu');

            // If sticky menu is enable
            if( ! $main_menu.hasClass( 'sticky-menu' ) ){
                return;
            }


            var main_menu_offset_top = $main_menu.offset().top;

            // fix for admin toolbar
            if( $('body').hasClass('admin-bar') ){
                main_menu_offset_top -= 32;
            }

            var sticky_func = function(){

                if( $(window).scrollTop() > main_menu_offset_top )
                    $main_menu.addClass('sticky');
                else
                    $main_menu.removeClass('sticky');

            };

            sticky_func();

            $( window ).scroll( function(){
                sticky_func();
            } );
        },


        /**
         * Setup Widgets and Shortcodes
         */
        setup_widgets: function(){

            $('.bf-news-ticker').betterNewsTicker();

        },


        /**
         * Setup Sliders
         */
        setup_sliders: function(){

            if( ! $.fn.flexslider ){
                return;
            }

            $('.gallery-slider .flexslider').flexslider({
                animation   :   "fade",
                prevText    :   "",
                nextText    :   "",
                controlNav  :   false
            });

            $('.main-slider .flexslider').flexslider({
                animation   :   better_mag_vars.main_slider.animation,
                slideshowSpeed   :   better_mag_vars.main_slider.slideshowSpeed,
                animationSpeed   :   better_mag_vars.main_slider.animationSpeed,
                prevText    :   "",
                nextText    :   "",
                controlNav  :   true,
                directionNav:   false
            });

            $('.gallery-listing.column-3 .flexslider').flexslider({
                animation   :   "slide",
                animationLoop:  true,
                itemWidth   :   200,
                itemMargin  :   20,
                minItems    :   2,
                maxItems    :   3,
                controlNav  :   false,
                animationSpeed: 800
            });

            $('.gallery-listing.column-2 .flexslider').flexslider({
                animation   :   "slide",
                animationLoop:  true,
                itemWidth   :   335,
                itemMargin  :   20,
                minItems    :   2,
                maxItems    :   2,
                controlNav  :   false,
                animationSpeed: 800
            });

        },


        /**
         * Setup Video Players
         */
        setup_video_players: function(){

            $('.single-post .featured, .the-content, .sidebar').fitVids();

        },


        /**
         * Setup Gallery Post Format as Background Slide Show
         */
        setup_gallery_bg_slide_show: function(){

            var $gallery_parent = $( '.single-post .gallery-slider.gallery-as-background-slide-show:first');

            if( $gallery_parent.length > 0){

                var images = [];

                $gallery_parent.find( '.slides li img').each( function(){

                    if( typeof $(this).data('img') != 'undefined' ){
                        images.push( $(this).data('img') );
                    }else{
                        images.push( $(this).attr('src') );
                    }

                });

                $.backstretch( images, {duration: 7000, fade: 600});

            }
        },



        /**
         * Small style fixes with jquery that can't done with css!
         */
        small_style_fixes: function(){

            // add and remove class "have-focus" for search forms
            $( ".search-form .search-field" ).on('focusin',function() { $( this ).closest('.search-form').addClass('have-focus'); });
            $( ".search-form .search-field" ).on('focusout',function() { $( this ).closest('.search-form').removeClass('have-focus'); });
            $( "#searchform #s" ).on('focusin',function() { $( this ).closest('#searchform').addClass('have-focus'); });
            $( "#searchform #s" ).on('focusout',function() { $( this ).closest('#searchform').removeClass('have-focus'); });
            $( "#bbp-search-form #bbp_search" ).on('focusin',function() { $( this ).closest('#bbp-search-form').addClass('have-focus'); });
            $( "#bbp-search-form #bbp_search" ).on('focusout',function() { $( this ).closest('#bbp-search-form').removeClass('have-focus'); });


            // small fix for cauterizing elements in header aside
            if( ! $('html').hasClass('ie8') && ! $('html').hasClass('ie9') )
                $(window).load(function() {
                    $("header.header .row").height(function(){
                        var logo_height = $("header.header .logo-container").outerHeight();
                        var aside_logo_height = $("header.header .aside-logo-sidebar").outerHeight();

                        if( logo_height > aside_logo_height ){
                            return logo_height + 'px';
                        }else{
                            return aside_logo_height + 'px';
                        }
                    });
                    $("header.header .aside-logo-sidebar").css( 'margin-top', Math.floor( ( $("header.header .aside-logo-sidebar").height() - 5 ) / 2 * -1) + 'px').css('top','50%');

                });

            // last-aligned class for selecting first floated li that is noy posiblr with css
            $( ".main-menu .menu li.alignright").first().addClass('first-aligned');

            // mega menu height fix
            $(".main-menu .mega-menu.style-category .mega-menu-links").each(function(){
                if( $(this).height() > $(this).closest(".mega-menu").find('.mega-menu-listing-container').height() ){
                    $(this).closest(".mega-menu").find('.mega-menu-listing-container').height( $(this).height() );
                }else{
                    $(this).height( $(this).closest(".mega-menu").find('.mega-menu-listing-container').height() + 5 );
                }
            });


            // calendar widget fix
            $(".widget.widget_calendar table td a").each( function(){
                $(this).parent().addClass('active-day');
            });


            // add active term for tab heading
            $('.section-heading.tab-heading .nav-tabs li.active').each(function(){

                var classNames = $(this).attr('class').split(" ");

                var selected_tab_term = '';

                // find term class
                jQuery.each( classNames, function( index, value){
                    if( value.match(/term-/)){
                        selected_tab_term = value;
                        return false;
                    }
                });

                $(this).closest('.section-heading').addClass(selected_tab_term);

            });

            // add term class to tab heading
            $('.section-heading.tab-heading .nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                if( typeof $(e.target).parent().attr('class') == 'undefined' ){
                    return true;
                }

                var $parent = $(e.target).closest('.section-heading'),
                    classNames = $(e.target).parent().attr('class').split(" ");

                var selected_tab_term = '';

                // find term class
                jQuery.each( classNames, function( index, value){
                    if( value.match(/term-/)){
                        selected_tab_term = value;
                        return false;
                    }
                });

                // remove term classes from parent
                jQuery.each( $parent.attr('class').split(" "), function( index, value){
                    if( value.match(/term-/)){
                        $parent.removeClass(value);
                    }
                });

                $parent.addClass( selected_tab_term );

            });

            // Cross Browser Placeholder
            $('input, textarea').placeholder();

            // IE 10 detection for style fixing
            if( $.browser.msie && $.browser.version == 10) {
                $("html").addClass("ie ie10");
            }

        },


        /**
         * Setup WooCommerce Related Functionality
         */
        woocommerce: function(){

            // Order list
            $('.woocommerce-ordering .drop-down li a').click(function(e) {
                var form = $(this).closest('form');

                form.find('[name=orderby]').val($(this).parent().data('value'));
                form.submit();

                e.preventDefault();
            });

            // Update Menu Cart and Count
            $(window).on('added_to_cart',function( e, data ){

                $( '#main-menu > li.shop-cart-item .widget_shopping_cart .widget_shopping_cart_content').replaceWith( data['div.widget_shopping_cart_content'] );

                $(".bm-wc-cart .items-list .widget_shopping_cart_content").each(function(){
                    $(this).replaceWith( data['div.widget_shopping_cart_content'] );
                });

                if( typeof data['total-items-in-cart'] != 'undefined' ){
                    $( '#main-menu > li.shop-cart-item .better-custom-badge ').html( data['total-items-in-cart'] );

                    $(".bm-wc-cart .cart-link .total-items").html( data['total-items-in-cart'] );

                    if( data['total-items-in-cart'] > 0 ){
                        $(".bm-wc-cart").removeClass('empty-cart').find('.total-items').removeClass('empty');
                    }else{
                        $(".bm-wc-cart").addClass('empty-cart').find('.total-items').addClass('empty');
                    }
                }

            });

            $("header.header .widget_shopping_cart").each(function(i,e){
                $(this).parent().append("<div class='bm-woocommerce-cart-widget' id='bm-cart-widget-" + i + "'></div>");
                $(this).appendTo("#bm-cart-widget-"+i);
            });

        },


        /**
         * BetterStudio Editor Shortcodes Setup
         */
        betterstudio_editor_shortcodes: function(){

            $('.bs-accordion-shortcode').on( 'show.bs.collapse', function(e) {
                $(e.target).prev('.panel-heading').addClass('active');
            }).on('hide.bs.collapse', function(e) {
                    $(e.target).prev('.panel-heading').removeClass('active');
            });

        },


        /**
         * Back to top button
         */
        back_to_top: function(){


            if( ! $('body').hasClass( 'enabled_back_to_top' ) )
                return;

            var $back_to_top = $('.back-top');

            $back_to_top.click(function(){
                $('body,html').animate({
                        scrollTop: 0
                    }, 700
                );
            });

            $(window).scroll(function(){
                ( $(this).scrollTop() > 300 ) ? $back_to_top.addClass('is-visible') : $back_to_top.removeClass('is-visible fade-out1 fade-out2 fade-out3 fade-out4');

                switch ( true ){

                    case ( $(this).scrollTop() > 2400 ):
                        $back_to_top.addClass('fade-out4');
                        break;

                    case ( $(this).scrollTop() > 1700 ):
                        $back_to_top.removeClass('fade-out3').addClass('fade-out3');
                        break;

                    case ( $(this).scrollTop() > 1000 ):
                        $back_to_top.removeClass('fade-out4 fade-out3').addClass('fade-out2');
                        break;

                    case ( $(this).scrollTop() > 500 ):
                        $back_to_top.removeClass('fade-out4 fade-out3 fade-out2').addClass('fade-out1');
                        break;
                }

            });

            // Fix bottom space in box padded style
            if( $('body').hasClass( 'boxed-padded' ) ){
                $(window).scroll(function() {
                    if( ( ( $(window).scrollTop()  ) + $(window).height() ) >= ( $(document).height() - 50 ) ) {
                        $('.back-top-wrapper .back-top').addClass('end-page');
                    }else{
                        $('.back-top-wrapper .back-top').removeClass('end-page');
                    }
                });
            }
        },


        /**
         * Define elements that use elementQuery on local/cross domain
         */
        fix_element_query: function(){

            if( typeof elementQuery != 'function' ){
                return;
            }

            elementQuery({
                ".bf-news-ticker" :{ "max-width":["250px", "320px"] },
                ".section-heading.extended.tab-heading" :{ "max-width":["300px"]}
            });

        },



        /**
         * Setup Popup
         */
        popup: function(){

            // If light box is not active
            if( ! $('body').hasClass( 'active-lighbox' ) )
                return;

            // disabled on mobile screens
            if( ! $.fn.prettyPhoto || $(window).width() < 700 )
                return;

            var filter_only_images = function() {

                if( ! $(this).attr('href') )
                    return false;

                if( typeof $(this).data('not-rel') != 'undefined')
                    return false;

                return $(this).attr('href').match(/\.(jp?g|png|bmp|gif)$/);
            };

            $('.the-content.post-content a, .the-content.page-content a').has('img').filter(filter_only_images).attr('rel', 'prettyPhoto');


            var gallery_id = 1;

            $('.the-content .gallery, .the-content .tiled-gallery').each(function() {

                $(this).find('a').has('img').filter(filter_only_images).attr('rel', 'prettyPhoto[gallery_'+ gallery_id +']');

                gallery_id++;

            });

            $("a[rel^='prettyPhoto']").prettyPhoto( BetterMag.prettyPhotoSettings );
        },


        /**
         * Setup Gallery
         */
        gallery: function(){

            var $fotoramaDiv = jQuery('.fotorama').fotorama({
                width: '100%',
                loop: true,
                margin: 10,
                thumbwidth: 85,
                thumbheight: 45,
                thumbmargin: 9,
                transitionduration: 800,
                arrows: false,
                click:false,
                swipe:true
            }).on('fotorama:show', function(e, fotorama, extra){

                var $gallery = $(this).closest('.better-gallery');

                $gallery.find('.count .current').html( fotorama.activeFrame.i );

            });

            // Activate light box gallery if active
            if( $('body').hasClass( 'active-lighbox' ) && $.fn.prettyPhoto || $(window).width() < 700 ){

                jQuery('.better-gallery').on( 'click', '.slide-link', function(){

                    event.preventDefault();

                    var $gallery = $(this).closest('.better-gallery');
                    var gallery_id = $gallery.data('gallery-id');

                    var pps = BetterMag.prettyPhotoSettings;

                    pps.changepicturecallback = function(){
                        $('#gallery-' + gallery_id).find('.fotorama').data('fotorama').show( $('.pp_gallery').find('li').index($('.selected')) );
                    };

                    $.fn.prettyPhoto( pps );

                    $.prettyPhoto.open( window["prt_gal_img_"+gallery_id], window["prt_gal_cap_"+gallery_id], window["prt_gal_cap_"+gallery_id]);

                    $.prettyPhoto.changePage( $('#gallery-' + gallery_id).find('.fotorama').data('fotorama').activeFrame.i - 1 );

                    return false;
                });

            }

            // Next Button
            jQuery('.better-gallery .gallery-title .next').click(function(){

                var fotorama = $(this).closest('.better-gallery').find('.fotorama').data('fotorama');
                fotorama.show('>');

            });

            // Previous Button
            jQuery('.better-gallery .gallery-title .prev').click(function(){

                var fotorama = $(this).closest('.better-gallery').find('.fotorama').data('fotorama');
                fotorama.show('<');

            });

        }


    };// /return
})(jQuery);

// Load when ready
jQuery(document).ready(function() {

    BetterMag.init();

});