var Better_Fonts_Manager = (function($) {
    "use strict";

    return {

        init: function(){

            switch( better_fonts_manager_loc.type ){

                // Setup Panel
                case 'panel':

                    this.setup_panel_fonts_manager();

                    // Setup General fields
                    this.setup_field_typography();

                    $(".bf-section[data-id='font_stacks']").on('keyup', 'input[name$="[id]"]', function () {
                        var $this = $(this),
                            $wrapper = $this.closest('.bf-repeater-item');

                        $wrapper.find('.bf-repeater-item-title .handle-repeater-title-label')
                            .text('Font Stack: ' + $this.val());

                    }).find('input[name$="[id]"]').keyup();

                    break;

                // Setup Widgets
                case 'widgets':

                    break;

                // Setup Metaboxes
                case 'metabox':
                    break;

                // Setup Menus
                case 'menus':

                    break;

            }

        },

        load_google_fonts : {},
        load_theme_fonts : {},
        load_custom_fonts : {},

        /**
         * Setup Fonts Manager Panel
         ******************************************/
        setup_panel_fonts_manager: function(){

            // change all default fields font id
            $('.bf-section[data-id=custom_fonts] .bf-repeater-item').each( function( i ){

                var text = $(this).find('.better-custom-fonts-id input').val();

                text = text.replace( '%i%', i + 1 );

                $(this).find('.better-custom-fonts-id input').val(text);
            });

            // change new fonts id
            $('.bf-section[data-id=custom_fonts]').on('repeater_item_added', function(){

                var count = $(this).find('.bf-repeater-items-container').find('>*').size();

                var text = $(this).find('.bf-repeater-item:last-child .better-custom-fonts-id input').val();

                text = text.replace( '%i%', count );

                $(this).find('.bf-repeater-item:last-child .better-custom-fonts-id input').val(text);
            });

        },


        /**
         * Setup Typography Field
         ******************************************/
        setup_field_typography: function(){

            $('.bf-section-container select.font-family').chosen({
                width: "100%"
            });

            // Init preview in page load
            $('.bf-section-typography-option').each(function(){
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'first-time');
            });
            if( Object.keys(Better_Fonts_Manager.load_google_fonts).length > 0 ){
                WebFont.load({
                    google: {
                        families: Object.keys(Better_Fonts_Manager.load_google_fonts).map(function (key) {return Better_Fonts_Manager.load_google_fonts[key]})
                    }
                });
            }
            if( Object.keys(Better_Fonts_Manager.load_custom_fonts).length > 0 ){
                $.each( Better_Fonts_Manager.load_custom_fonts, function( key, value ) {
                    WebFont.load({
                        custom: value
                    });
                });
            }
            if( Object.keys(Better_Fonts_Manager.load_theme_fonts).length > 0 ){
                $.each( Better_Fonts_Manager.load_theme_fonts, function( key, value ) {
                    WebFont.load({
                        custom: value
                    });
                });
            }

            // Prepare active field in page load
            $('.bf-section-typography-option .typo-enable-container input[type=checkbox]').each(function(){

                $(this).closest(".bf-section-typography-option").addClass('have-enable-field');

                if( $(this).attr("checked") ){
                    $(this).closest(".bf-section-typography-option").addClass('enable-field`');
                }else{
                    $(this).closest(".bf-section-typography-option").addClass('disable-field');
                }

            });

            // Active field on change
            $(".bf-section-typography-option .typo-enable-container .cb-enable").click(function(){

                $(this).closest(".bf-section-typography-option").addClass('enable-field').removeClass('disable-field');

            });

            $(".bf-section-typography-option .typo-enable-container .cb-disable").click(function(){
                $(this).closest(".bf-section-typography-option").addClass('disable-field').removeClass('enable-field');
            });

            // When Font Family Changes
            $('.bf-section-container select.font-family').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'family');
            });

            // When Font Variant Changes
            $('.bf-section-container .font-variants').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'variant' );
            });

            // When Font Size Changes
            $('.bf-section-container .font-size').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'size' );
            });

            // When Line Height Changes
            $('.bf-section-container .line-height').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'height' );
            });

            // When Letter Spacing Changes
            $('.bf-section-container .letter-spacing').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'letter-spacing' );
            });

            // When Align Changes
            $(' .bf-section-container .text-align-container select').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'align' );
            });

            // When Color Changes
            $(' .bf-section-container .text-color-container .bf-color-picker').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'color' );
            });

            // When Transform Changes
            $(' .bf-section-container .text-transform').on('change', function(evt, params) {
                Better_Fonts_Manager.refresh_typography( $(this).closest(".bf-section-container"), 'transform' );
            });


            // Preview Tab
            $('.typography-preview .preview-tab .tab').on('click', function() {

                if( $(this).hasClass('current') ){
                    return false;
                }else{

                    $(this).closest('.preview-tab').find('.current').removeClass('current');
                    $(this).closest('.typography-preview').find('.preview-text.current').removeClass('current');

                    $(this).addClass('current');

                    $(this).closest('.typography-preview').find('.preview-text.'+$(this).data('tab')).addClass('current');
                }

            });

        },

        // Used for refreshing typography preview
        refresh_typography_field: function( $parent, type, _css, first_time ){

            switch ( type ){
                case  'size':
                    _css.fontSize = $parent.find('.font-size').val() + 'px';
                    break;

                case 'height':
                    if( $parent.find('.line-height').val() != '' )
                        _css.lineHeight = $parent.find('.line-height').val() + 'px';
                    else
                        delete _css.lineHeight;
                    break;

                case 'letter-spacing':
                    if( $parent.find('.letter-spacing').val() != '' )
                        _css.letterSpacing = $parent.find('.letter-spacing').val();
                    else
                        delete _css.letterSpacing;
                    break;

                case 'align':
                    _css.textAlign = $parent.find('.text-align-container select option:selected').val();
                    break;

                case 'color':
                    _css.color = $parent.find('.text-color-container .bf-color-picker').val();
                    break;

                case 'transform':
                    _css.textTransform = $parent.find('.text-transform').val();
                    break;

                case 'family':
                    _css.fontFamily = "'" + $parent.find('select.font-family option:selected').val() + "', sans-serif";
                    break;

                case 'variant':
                    var variant = $parent.find('.font-variants option:selected').val();

                    if( typeof variant == 'undefined' )
                        break;

                    if( variant.match(/([a-zA-Z].*)/i) != null ){
                        var style = variant.match(/([a-zA-Z].*)/i);
                        if( style[0] == 'regular' )
                            _css.fontStyle = 'normal';
                        else
                            _css.fontStyle = style[0];
                    }else{
                        delete _css.fontStyle;
                    }

                    if( variant.match(/\d*(\s*)/i) != null ){
                        var size = variant.match(/\d*(\s*)/i);
                        _css.fontWeight = size[0];
                    }else{
                        delete _css.fontWeight;
                    }

                    break;

                case 'load-font':


                    var selected_font_id = $parent.find('select.font-family option:selected').val(),
                        selected_font = Better_Fonts_Manager.get_font( selected_font_id),
                        selected_variant = $parent.find('.font-variants option:selected').val();


                    switch( selected_font.type ){

                        case 'google-font':

                            if( first_time == 'first-time' ){
                                Better_Fonts_Manager.load_google_fonts[selected_font_id + ':' + selected_variant] = selected_font_id + ':' + selected_variant;
                            }else{
                                WebFont.load({
                                    google: {
                                        families: [ selected_font_id + ':' + selected_variant ]
                                    }
                                });
                            }
                            _css.fontFamily = "'" + selected_font_id + "', sans-serif";
                            break;

                        case 'custom-font':

                            _css.fontFamily = "'" + selected_font_id + "', sans-serif";

                            if( first_time == 'first-time' ){
                                Better_Fonts_Manager.load_custom_fonts[selected_font_id] = {
                                    families: [ selected_font_id ],
                                    urls: [ better_fonts_manager_loc.admin_fonts_css_url + '&' + 'custom_font_id=' + selected_font_id ]
                                };
                            }else{
                                if( typeof Better_Fonts_Manager.load_custom_fonts[selected_font_id] == "undefined" ) {
                                    WebFont.load({
                                        custom: {
                                            families: [ selected_font_id ],
                                            urls: [ better_fonts_manager_loc.admin_fonts_css_url + '&' + 'custom_font_id=' + selected_font_id ]
                                        }
                                    });
                                    Better_Fonts_Manager.load_custom_fonts[selected_font_id] = {
                                        families: [ selected_font_id ],
                                        urls: [ better_fonts_manager_loc.admin_fonts_css_url + '&' + 'custom_font_id=' + selected_font_id ]
                                    };
                                }
                            }

                            break;

                        case 'theme-font':

                            _css.fontFamily = "'" + selected_font_id + "', sans-serif";

                            if( first_time == 'first-time' ){
                                Better_Fonts_Manager.load_theme_fonts[selected_font_id] = {
                                    families: [ selected_font_id ],
                                    urls: [ better_fonts_manager_loc.admin_fonts_css_url + '&' + 'theme_font_id=' + selected_font_id ]
                                };
                            }else{
                                if( typeof Better_Fonts_Manager.load_theme_fonts[selected_font_id] == "undefined" ) {
                                    WebFont.load({
                                        custom: {
                                            families: [selected_font_id],
                                            urls: [better_fonts_manager_loc.admin_fonts_css_url + '&' + 'theme_font_id=' + selected_font_id]
                                        }
                                    });
                                    Better_Fonts_Manager.load_theme_fonts[selected_font_id] = {
                                        families: [ selected_font_id ],
                                        urls: [ better_fonts_manager_loc.admin_fonts_css_url + '&' + 'theme_font_id=' + selected_font_id ]
                                    };
                                }
                            }


                            break;

                        case 'font-stack':

                            _css.fontFamily = "'" + selected_font_id + "', sans-serif";

                            break;

                    }

            }
            return _css;

        },


    // Used for refreshing all styles of typography field
    refresh_typography: function( $parent, type ){
        type = typeof type !== 'undefined' ? type : 'all';

        var $preview = $parent.find('.typography-preview .preview-text');

        var _css = $preview.css([
            "fontSize", "lineHeight", "textAlign", "fontFamily", "fontStyle", "fontWeight", "textTransform", "color", "letterSpacing"
        ]);

        switch ( type ){

            case 'size':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'size', _css);
                break;

            case 'height':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'height', _css);
                break;

            case 'letter-spacing':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'letter-spacing', _css);
                break;

            case 'transform':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'transform', _css);
                break;

            case 'align':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'align', _css);
                break;

            case 'color':
                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'color', _css);
                break;

            case 'variant':

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'variant', _css);

                var selected_font_id = $parent.find('select.font-family option:selected').val(),
                    selected_font = Better_Fonts_Manager.get_font( selected_font_id),
                    selected_variant = $parent.find('.font-variants option:selected').val();

                if( selected_variant == 'regular' )
                    selected_variant = '';
                else
                    selected_variant = ':' + selected_variant;

                switch( selected_font.type ){

                    case 'google-font':

                        // load new font
                        WebFont.load({
                            google: {
                                families: [ selected_font_id + selected_variant ]
                            }
                        });

                        _css.fontFamily = "'" + selected_font_id + "', sans-serif";

                        break;

                    case 'theme-font':
                    case 'custom-font':
                    case 'font-stack':

                        _css.fontFamily = "'" + selected_font_id + "', sans-serif";

                        break;

                }

                break;

            case 'family':

                var selected_font_id = $parent.find('select.font-family option:selected').val(),
                    selected_font = Better_Fonts_Manager.get_font( selected_font_id),
                    selected_font_variants = Better_Fonts_Manager.get_font_variants( selected_font),
                    selected_font_subsets = Better_Fonts_Manager.get_font_subsets( selected_font );

                // load and adds variants
                $parent.find('.font-variants option').remove();
                var selected = false,
                    _selected = '';

                // generate variant options
                $.each( selected_font_variants, function( index, element){

                    if( element['id'] == '400' || element['id'] == 'regular' ){
                        selected = element['id'];
                    }

                    $parent.find('.font-variants').append('<option value="' + element['id'] +'" ' + ( element['id'] == selected ? ' selected' : '' ) + '>'+ element['name'] +'</option>');

                });

                // select first if 400 is not available in font variants
                if( selected == false )
                    $parent.find('.font-variants option:first-child').attr('selected','selected');

                // load and adds subsets
                $parent.find('.font-subsets option').remove();

                // generate subset options
                $.each( selected_font_subsets, function( index, element){

                    // select latin as default subset
                    if( element['id'] == 'latin' || element['id'] == 'unknown' ){
                        $parent.find('.font-subsets').append('<option value="' + element['id'] +'" selected>'+ element['name'] +'</option>');
                    }
                    else{
                        $parent.find('.font-subsets').append('<option value="' + element['id'] +'">'+ element['name'] +'</option>');
                    }

                });

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'load-font', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'variant', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'family', _css);

                break;


            case 'first-time':

                $parent.find('.load-preview-texts').remove();

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'load-font', _css,'first-time');

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'family', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'size', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'height', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'letter-spacing', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'transform', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'align', _css);

                _css = Better_Fonts_Manager.refresh_typography_field( $parent, 'variant', _css);

                $parent.find('.typography-preview').css('display', 'block');

        }

        $preview.attr('style', '');
        $preview.css( _css );
    },


        // Used for getting font information's
        get_font: function( font_id ){

            // Custom Fonts
            if( typeof better_fonts_manager_loc.fonts.theme_fonts[font_id] != "undefined" ){

                return better_fonts_manager_loc.fonts.theme_fonts[font_id];

            }// Custom Fonts
            else if( typeof better_fonts_manager_loc.fonts.font_stacks[font_id] != "undefined" ){

                return better_fonts_manager_loc.fonts.font_stacks[font_id];

            }
            // Font Stacks
            else if( typeof better_fonts_manager_loc.fonts.custom_fonts[font_id] != "undefined" ){

                return better_fonts_manager_loc.fonts.custom_fonts[font_id];

            }
            // Google Fonts
            else if( typeof better_fonts_manager_loc.fonts.google_fonts[font_id] != "undefined" ){

                return better_fonts_manager_loc.fonts.google_fonts[font_id];

            }


            return false;

        },


        // full list of default variants array
        get_default_variants: function(){

            return [
                {
                    "id"    :   "100",
                    "name"  :   better_fonts_manager_loc.texts.variant_100
                },

                {
                    "id"    :   "300",
                    "name"  :   better_fonts_manager_loc.texts.variant_300
                },
                {
                    "id"    :   "400",
                    "name"  :   better_fonts_manager_loc.texts.variant_400
                },
                {
                    "id"    :   "500",
                    "name"  :   better_fonts_manager_loc.texts.variant_500
                },
                {
                    "id"    :   "700",
                    "name"  :   better_fonts_manager_loc.texts.variant_700
                },
                {
                    "id"    :   "900",
                    "name"  :   better_fonts_manager_loc.texts.variant_900
                },
                {
                    "id"    :   "100italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_100italic
                },
                {
                    "id"    :   "300italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_300italic
                },
                {
                    "id"    :   "400italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_400italic
                },
                {
                    "id"    :   "500italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_500italic
                },

                {
                    "id"    :   "700italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_700italic
                },
                {
                    "id"    :   "900italic",
                    "name"  :   better_fonts_manager_loc.texts.variant_900italic
                }
            ];

        },


        // Used for font variants
        get_font_variants: function( font ){

            // load font if font name is input
            if( typeof font == 'string' ){

                font = Better_Fonts_Manager.get_font( font );

                if( font == false ){

                    return Better_Fonts_Manager.get_default_variants();
                }

            }

            switch( font.type ){

                case 'google-font':

                    return font.variants;
                    break;

                case 'theme-font':
                case 'font-stack':
                case 'custom-font':

                    return Better_Fonts_Manager.get_default_variants();

                    break;

            }

            return false;

        },


        // Used for font variants
        get_font_subsets: function( font ){

            // load font if font name is input
            if( typeof font == 'string' ){

                font = Better_Fonts_Manager.get_font( font );

                if( font == false ){

                    return [
                        {
                            "id"    :   "unknown",
                            "name"  :   better_fonts_manager_loc.texts.subset_unknown
                        }
                    ];
                }

            }

            switch( font.type ){

                case 'google-font':

                    return font.subsets;
                    break;

                case 'theme-font':
                case 'font-stack':
                case 'custom-font':

                    return [
                        {
                            "id"    :   "unknown",
                            "name"  :   better_fonts_manager_loc.texts.subset_unknown
                        }
                    ];

                    break;

            }

            return false;

        }

    };

})(jQuery);

// load when ready
jQuery(function($) {

    Better_Fonts_Manager.init();

});