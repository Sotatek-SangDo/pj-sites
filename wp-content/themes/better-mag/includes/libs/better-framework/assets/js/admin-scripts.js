var bf_ignore_reload_notice = false,
    Better_Framework = (function($) {
    "use strict";

    // module
    return {

        init: function(){

            // Setup General fields
            this.setup_fields();

            this.handle_editor();

            this.handle_notices();

            switch( better_framework_loc.type ){

                // Setup Widgets
                case 'widgets':

                    // Setup fields after ajax request on widgets page
                    this.setup_widget_fields();
                    this.sort_widgets();
                    break;

                // Setup Panel
                case 'panel':

                    this.setup_panel_tabs();
                    this.panel_save_action();
                    this.panel_reset_action();
                    this.panel_sticky_header();
                    this.panel_import_export();
                    this.change_panel_data_notice();
                    break;

                // Setup Meta Boxes
                case 'metabox':
                    this.set_metabox();

                    // Metabox Filter For Post Format
                    this.metabox_filter_postformat();

                    // Metabox Fields Filter For Post Format
                    this.metabox_field_filter_postformat();

                    this.setup_fields_for_vc();

                    this.setup_interactive_fields_for_vc();

                    this.change_metabox_data_notice();

                    this.vc_design_option_tab_fix();

                    break;

                // Setup Taxonomy Meta Boxes
                case 'taxonomy':

                    this.taxonomy_page_reload_status();

                    this.set_metabox();

                    this.change_metabox_data_notice();

                    break;

                // Setup User Meta Boxes
                case 'users':

                    this.set_metabox();

                    break;

                // Setup Menus
                case 'menus':

                    this.menus_collect_fields_before_save();
                    break;

            }

        },


        /**
         * Panel
         ******************************************/

        // Setup panel tabs
        setup_panel_tabs: function(){

            $('#bf-main #bf-content').css( 'min-height',  $('#bf-main #bf-nav').height() + 50 );


            // TODO: Vahid shit! Refactor this

            var panelID = $('#bf-panel-id').val();

            var _bf_current_tab = $.cookie( 'bf_current_tab_of_' + panelID  );

            function bf_show_first_tab(){
                $('#bf-nav').find('li:first').find('a:first').addClass("active_tab");

                $('#bf_options_form').find( '#bf-group-' + $('#bf-nav').find('li:first').data("go") ).show(function(){
                    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                    elems.forEach(function(html) {
                        var switchery = new Switchery(html);
                    });
                } );
            }

            if( typeof _bf_current_tab == 'undefined' ){
                bf_show_first_tab();
            }
            else
            {
                var _curret_ = $.cookie( 'bf_current_tab_of_' + panelID  );

                if( !$('#bf_options_form').find( '#bf-group-' + _curret_ ).exist() || !$('#bf-nav').find("a[data-go='"+_curret_+"']").exist() ){
                    bf_show_first_tab();
                    $.removeCookie('bf_current_tab_of_' + panelID);
                }

                $('#bf_options_form').find( '#bf-group-' + _curret_ ).show();
                $('#bf-nav').find("a[data-go='"+_curret_+"']").addClass("active_tab");
                if( $('#bf-nav').find("a[data-go='"+_curret_+"']").is(".bf-tab-subitem-a") ){
                    $('#bf-nav').find("a[data-go='"+_curret_+"']").closest(".has-children").addClass("child_active");
                }

            }

            $('#bf-nav').find('a').click( function(){

                if( $(this).hasClass('active_tab') )
                    return false;

                var _this = $(this);
                var _hasNotGroup = ( ( _this.parent().hasClass('has-children') ) && ( ! $('#bf_options_form').find( '#bf-group-' + _this.data("go") ).find(">*").exist() ) );
                var _isChild = ! _this.parent().hasClass('has-children');

                if( _hasNotGroup ){
                    var _clicked = _this.siblings('ul.sub-nav').find('a:first');
                    var _target = $('#bf_options_form').find( '#bf-group-' + _clicked.data("go") );
                } else {
                    var _clicked = _this;
                    var _target = $('#bf_options_form').find( '#bf-group-' + _clicked.data("go") );
                }

                $('#bf-nav').find('a').removeClass("active_tab");
                $('#bf-nav').find('ul.sub-nav').find('a').removeClass("active_tab");
                $('#bf-nav').find('li').removeClass("child_active");

                $('#bf_options_form').find('>div').hide();
                _target.fadeIn(500);

                $.cookie( 'bf_current_tab_of_' + panelID, _clicked.data("go"), { expires: 7 });

                _clicked.addClass("active_tab");

                if( _this.parent().hasClass('has-children') || _this.parent().parent().hasClass('sub-nav') ){
                    _clicked.closest('.has-children').addClass("child_active");
                }

                $('body,html').animate({
                    scrollTop: 0
                }, 400);

                return false;
            });

        },
        _get_metabox_data:function() {
            return $('.bf-metabox-container').bf_serialize();
        },
        change_metabox_data_notice: function () {
            var self = this,
                default_values = this._get_metabox_data();

            $('.bf-metabox-container').on('change bf-changed', ':input', function() {
                var changed = default_values !== self._get_metabox_data();
                if( changed ) {
                    bf_ignore_reload_notice = false;
                    $(window).on('beforeunload.bs-admin', function(e) {
                        if(! bf_ignore_reload_notice)
                            return true;
                    });
                } else {
                    self.turn_refresh_notice_off();
                }
            });

            $("#post,#edittag").on('submit', function() {
                self.turn_refresh_notice_off();
            });
        },
        /**
         *
         * @private
         */
        _get_panel_data:function() {
            var _serialized = $('#bf-content').bf_serialize();
            _serialized += '&' + $('#bf_options_form :radio').serialize();

            return _serialized;
        },
        turn_refresh_notice_off: function() {
            $(window).off('beforeunload.bs-admin');
        },
        change_panel_data_notice:function() {
            var self = this,
                default_values = this._get_panel_data();

            $('#bf-content').on('change bf-changed', ':input', function() {
                var changed = default_values !== self._get_panel_data();

                $("#bf-panel .bf-options-change-notice")
                    [changed ? 'addClass' : 'removeClass' ]('bf-option-changed')
                    [changed ? 'fadeIn' : 'fadeOut' ](300);

                if( changed ) {
                    bf_ignore_reload_notice = false;
                    $(window).on('beforeunload.bs-admin', function(e) {
                        if(! bf_ignore_reload_notice)
                            return true;
                    });
                } else {
                    self.turn_refresh_notice_off();
                }
            });
        },

        taxonomy_page_reload_status: function() {
            $(document).ajaxSuccess(function (e, xhr, settings) {
                var data = $.unserialize(settings.data);
                if(data.action === 'add-tag') {
                    bf_ignore_reload_notice  = true;
                }
            });
        },
        /**
         * @see _get_panel_data
         */
        handle_editor:function() {
            var self = this;

            jQuery(document).ready(function($) {
                var have_ace = typeof ace === "object";

                $('.bf-editor-wrapper').each(function() {
                    var $wrapper  = $(this),
                        $editor   = $wrapper.find('.bf-editor'),
                        $textarea = $wrapper.find('.bf-editor-field');

                    if( have_ace ) {
                        $textarea.hide();

                        var
                            lang = $editor.data('lang'),
                            max_lines = $editor.data('max-lines'),
                            min_lines = $editor.data('min-lines'),
                            theme = $editor.data('theme'),
                            editor = ace.edit($editor[ 0 ]),
                            session = editor.getSession();

                        editor.setOptions({
                            maxLines: max_lines,
                            minLines: min_lines,
                            mode: "ace/mode/" + lang
                        });
                        if (theme)
                            editor.setTheme("ace/theme/theme");

                        session.setUseWorker(false);
                        session.on('change', function (e, EditSession) {

                            $textarea
                                    .val(editor.getSession().getValue())
                                    .trigger('bf-changed');
                        });
                    } else {
                        $editor.remove();
                        $textarea.show();
                    }
                });
            });
        },
        handle_notices: function () {
            var wrapper = '.bf-notice-wrapper';
            $(wrapper).on('click', '.bf-notice-dismiss', function () {
                var $this = $(this),
                    $wrapper = $this.closest(wrapper),
                    data = $this.data();

                $wrapper.slideUp(300);

                setTimeout( function () {
                    $wrapper.remove();
                }, 300 );

                if (data) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        data: $.extend({action: 'bf-notice-dismiss'}, data)
                    });
                }
            });
        },
        // Panel save ajax action
        panel_save_action: function(){

            var self = this;
            $(document).on( 'click', '.bf-save-button', function(e){

                e.preventDefault();

                if( $(this).data('confirm') != '' && ! confirm( $(this).data('confirm') ) )
                    return false;

                Better_Framework.panel_loader('loading');

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: better_framework_loc.bf_ajax_url,
                    data:{
                        action   : 'bf_ajax',
                        reqID    : 'save_admin_panel_options',
                        type     : better_framework_loc.type,
                        panelID  : $('#bf-panel-id').val(),
                        nonce    : better_framework_loc.nonce,
                        lang     : better_framework_loc.lang,
                        data   	 : self._get_panel_data()
                    },
                    success: function(data, textStatus, XMLHttpRequest){
                        if( data.status == 'succeed' ) {

                            $("#bf-panel .bf-options-change-notice")
                                .removeClass('bf-option-changed')
                                .slideUp();
                            self.turn_refresh_notice_off();

                            if( typeof data.msg != 'undefined' ){
                                Better_Framework.panel_loader('succeed',data.msg);
                            }else{
                                Better_Framework.panel_loader('succeed');
                            }
                        } else {
                            if( typeof data.msg != 'undefined' ){
                                Better_Framework.panel_loader('error',data.msg);
                            }else{
                                Better_Framework.panel_loader('error');
                            }
                            Better_Framework.panel_loader('error',data.msg);
                        }

                        if( typeof data.refresh != 'undefined' && data.refresh ){

                            if( data.status == 'succeed' ){

                                setTimeout( function(){
                                    location.reload();
                                }, 1000 );

                            }else{
                                setTimeout( function(){
                                    location.reload();
                                }, 1500 );
                            }

                        }
                    },
                    error: function(MLHttpRequest, textStatus, errorThrown){
                        Better_Framework.panel_loader('error');
                    }
                });

            });
        },


        // Panel Options Import & Export
        panel_import_export: function(){

            // Export Button
            $(document).on( 'click', '#bf-download-export-btn', function(){

                var _go = $(this).attr('href');

                var _file_name =  $(this).data('file_name');
                var _panel_id =  $(this).data('panel_id');

                $().redirect(_go,{
                    'bf-export' :   1,
                    'nonce'     :   better_framework_loc.nonce,
                    'file_name' :   _file_name,
                    'panel_id'  :   _panel_id,
                    lang        :   better_framework_loc.lang
                });

                return false;

            });

            // Import
            var bf_import_submit;
            $('.bf-import-file-input').fileupload({
                limitMultiFileUploads: 1,
                url: better_framework_loc.bf_ajax_url,
                autoUpload: false,
                replaceFileInput: false,
                formData: {
                    nonce  : better_framework_loc.nonce,
                    action : 'bf_ajax',
                    type   : better_framework_loc.type,
                    reqID  : 'import',
                    'panel-id': $('.bf-import-file-input').data('panel_id'),
                    lang        :   better_framework_loc.lang
                },
                add: function (e, data) {
                    bf_import_submit = function () {
                        return data.submit();
                    };
                },
                start: function (e) {
                    Better_Framework.panel_loader('loading');
                },
                done: function (e, data) {

                    var result = JSON.parse( data.result );

                    if( result.status == 'succeed' ) {
                        if( typeof result.msg != 'undefined' ){
                            Better_Framework.panel_loader('succeed',result.msg);
                        }else{
                            Better_Framework.panel_loader('succeed');
                        }
                    } else {
                        if( typeof result.msg != 'undefined' ){
                            Better_Framework.panel_loader('error',result.msg);
                        }else{
                            Better_Framework.panel_loader('error');
                        }
                    }

                    if( typeof result.refresh != 'undefined' && result.refresh ){

                        if( data.status == 'succeed' ){

                            setTimeout( function(){
                                location.reload();
                            }, 1000 );

                        }else{
                            setTimeout( function(){
                                location.reload();
                            }, 1500 );
                        }

                    }

                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                    Better_Framework.panel_loader('error');
                },
                progressall: function (e, data) {
                    var progress = parseInt( data.loaded / data.total * 100, 10);
                },
                drop: function (e, data) {
                    return false;
                }
            });

            $('.bf-import-upload-btn').click( function(){

                if( typeof bf_import_submit != "undefined" ){

                    if( confirm( better_framework_loc.translation.import_panel.prompt ) == true ){
                        bf_import_submit();
                    }

                }

                return false;
            });

        },


        // Ajax Action Field
        setup_ajax_action: function(){

            $(document).on( 'click', '.bf-ajax_action-field-container .bf-action-button', function(e){

                e.preventDefault();

                Better_Framework.panel_loader('loading');

                var _confirm_msg =  $(this).data('confirm');

                if( typeof  _confirm_msg  != "undefined" )
                    if( ! confirm( _confirm_msg ) ){
                        Better_Framework.panel_loader( 'hide' );
                        return false;
                    }

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: better_framework_loc.bf_ajax_url,
                    data:{
                        action   : 'bf_ajax',
                        reqID    : 'ajax_action',
                        type     : better_framework_loc.type,
                        panelID  : $('#bf-panel-id').val(),
                        lang     : better_framework_loc.lang,
                        nonce    : better_framework_loc.nonce,
                        callback: $(this).data('callback'),
                        bf_call_token: $(this).data('token')
                    },
                    success: function(data, textStatus, XMLHttpRequest){

                        if( data.status == 'succeed' ) {
                            if( typeof data.msg != 'undefined' ){
                                Better_Framework.panel_loader('succeed',data.msg);
                            }else{
                                Better_Framework.panel_loader('succeed');
                            }
                        } else {
                            if( typeof data.msg != 'undefined' ){
                                Better_Framework.panel_loader('error',data.msg);
                            }else{
                                Better_Framework.panel_loader('error');
                            }
                        }

                        if( typeof data.refresh != 'undefined' && data.refresh ){

                            if( data.status == 'succeed' ){

                                setTimeout( function(){
                                    location.reload();
                                }, 1000 );

                            }else{
                                setTimeout( function(){
                                    location.reload();
                                }, 1500 );
                            }

                        }
                    },
                    error: function(MLHttpRequest, textStatus, errorThrown){
                        Better_Framework.panel_loader('error');
                    }
                });

            });


        },


        // Panel Ajax Reset Action
        panel_reset_action: function(){

            var _this = this;
            $(document).on( 'click', '.bf-reset-button', function(){

                $.bs_modal({
                    content: {
                        header: better_framework_loc.translation.reset_panel.header,
                        title: better_framework_loc.translation.reset_panel.title,
                        body: better_framework_loc.translation.reset_panel.body
                    },
                    styles: {
                        container: 'overflow:visible;max-width: 460px;'
                    },
                    buttons: {
                        custom_event: {
                            label: better_framework_loc.translation.reset_panel.button_yes,
                            type: 'primary',
                            clicked: function() {
                                var self = this;
                                self.change_skin( {
                                    skin:'loading',
                                    animations:{
                                        body:'bs-animate bs-fadeInLeft'
                                    },
                                    content: {
                                        loading_heading: better_framework_loc.translation.reset_panel.resetting
                                    }
                                });

                                $.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: better_framework_loc.bf_ajax_url,
                                    data:{
                                        action 	 : 'bf_ajax',
                                        reqID  	 : 'reset_options_panel',
                                        panelID  : $('#bf-panel-id').val(),
                                        lang     : better_framework_loc.lang,
                                        type	 : 'panel',
                                        nonce    : better_framework_loc.nonce,
                                        to_reset : $('.bf-reset-options-frame-tabs').bf_serialize()
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){

                                        if( data.status == 'succeed' ){
                                           /* if( typeof data.msg != 'undefined' ){
                                                Better_Framework.panel_loader('succeed',data.msg);
                                            }else{
                                                Better_Framework.panel_loader('succeed');
                                            }*/

                                            self.change_skin( {
                                                skin:'success',
                                                animations:{
                                                    body: 'bs-animate bs-fadeInLeft'
                                                },
                                                content: {
                                                    success_heading: data.msg
                                                },
                                                timer: {
                                                    delay: 2000,
                                                    callback: function () {
                                                        this.close_modal();
                                                    }
                                                }
                                            });
                                            _this.turn_refresh_notice_off();
                                            setTimeout( function(){
                                                location.reload();
                                            }, 1000 );
                                        } else {
                                            if( typeof data.msg != 'undefined' ){
                                                Better_Framework.panel_loader('error',data.msg);
                                            }else{
                                                Better_Framework.panel_loader('error');
                                            }
                                        }

                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                        alert( 'An error occurred!' );
                                    }
                                });
                            }
                        },
                        close_modal: {
                            type: 'secondary',
                            action: 'close',
                            label: better_framework_loc.translation.reset_panel.button_no,
                            focus: true
                        }
                    }
                });
            });

        },


        // Panel loader
        // status: loading, succeed, error
        panel_loader: function( status, message ){

            var $bf_loading = $('.bf-loading');

            message = typeof message !== 'undefined' ? message : '';

            if( status == 'loading'){

                $bf_loading.removeClass().addClass('bf-loading in-loading');

                if( message != '' ){
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

            }
            else if(status == 'error'){

                $bf_loading.removeClass().addClass('bf-loading not-loaded');

                if( message != '' ){
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

                setTimeout(function(){
                    $bf_loading.removeClass('not-loaded');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                },1500);

            }else if(status == 'succeed'){

                $bf_loading.removeClass().addClass('bf-loading loaded');

                if( message != '' ){
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

                setTimeout(function(){
                    $bf_loading.removeClass('loaded');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                },1000);

            }else if( status == 'hide' ){

                setTimeout(function(){
                    $bf_loading.removeClass(' in-loading');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                },500 );
            }

        },


        // Setup sticky header
        panel_sticky_header: function(){

            var $main_menu = $('#bf-panel .bf-page-header');

            var main_menu_offset_top = 110 ;

            var sticky_func = function(){

                if( $(window).scrollTop() > main_menu_offset_top )
                    $main_menu.addClass('sticky').parent().addClass('sticky');
                else
                    $main_menu.removeClass('sticky').parent().removeClass('sticky');

            };

            sticky_func();

            $( window ).scroll( function(){
                sticky_func();
            } );
        },


        // Setup filter fields
        setup_filter_fields: function(){

            if( better_framework_loc.type == 'widgets' ){


                $( '#widgets-right .widget').each( function(){

                    var $widget = $(this);

                    var fields_must_check = {};

                    $widget.find( '.bf-section-container').each( function() {

                        if( typeof $(this).data( 'filter-field' ) != "undefined" ){

                            fields_must_check[$(this).data( 'filter-field' )] = $(this).data( 'filter-field' );

                        }

                    });

                    $.each( fields_must_check, function( index, value ){

                        var $_field = $widget.find( '.bf-section[data-id=' + index + ']');

                        if( $_field.hasClass('bf-repeater-section-option') ) {
                            return 1;
                        }


                        // select
                        if( $_field.hasClass( 'bf-section-select-option' ) ){

                            $_field.on( 'change', '.bf-controls .bf-select-option-container select', function(){

                                var changed_value = this.value;

                                $widget.find( '.bf-section-container[data-filter-field=\'' + index + '\']').each( function(){

                                    if( $(this).data( 'filter-field-value' ) == changed_value ){
                                        $(this).fadeIn('200');
                                    }else{
                                        $(this).css({'display':'none'});
                                    }

                                });

                            });
                        }


                        // select
                        else if( $_field.hasClass( 'bf-section-image_select-option' ) ){

                            $_field.on( 'change', '.bf-controls .bf-section-image_select-option input', function(){

                                var changed_value = this.value;

                                $widget.find( '.bf-section-container[data-filter-field=\'' + index + '\']').each( function(){

                                    if( $(this).data( 'filter-field-value' ) == changed_value ){
                                        $(this).fadeIn('200');
                                    }else{
                                        $(this).css({'display':'none'});
                                    }

                                });

                            });
                        }



                    });

                } );



            }else{

                var fields_must_check = {};
                $( '.bf-section-container').each( function() {

                    if( typeof $(this).data( 'filter-field' ) != "undefined" ){

                        fields_must_check[$(this).data( 'filter-field' )] = $(this).data( 'filter-field' );

                    }

                });
                $.each( fields_must_check, function( index, value ){

                    var $_field = $( '.bf-section[data-id=' + index + ']');

                    if( $_field.hasClass('bf-repeater-section-option') ) {
                        return 1;
                    }

                    // select
                    if( $_field.hasClass( 'bf-section-select-option' ) ){

                        $_field.on( 'change', '.bf-controls .bf-select-option-container select', function(){

                            Better_Framework.process_filter_field( index, this.value );

                        });
                    }

                    // switch
                    else if( $_field.hasClass( 'bf-section-switch-option' ) ){

                        $_field.on( 'change', '.bf-controls input', function(){
                            Better_Framework.process_filter_field( index, this.value );
                        });

                    }

                    // image select
                    else if( $_field.hasClass( 'bf-section-image_select-option' ) ){
                        $_field.on( 'change', '.bf-controls input', function(){
                            Better_Framework.process_filter_field( index, this.value );
                        });
                    }

                    // image radio
                    else if( $_field.hasClass( 'bf-section-image_radio-option' ) ){
                        $_field.on( 'change', '.bf-controls input', function(){
                            Better_Framework.process_filter_field( index, this.value );
                        });
                    }

                });

            }

        },


        // Setup filter fields
        setup_filter_fields_repeater: function( scope ){


            // For all repeaters that saved before
            if( typeof scope == "undefined" ){

                var $repeater_items_container = $('.bf-repeater-items-container');

                $repeater_items_container.each( function(){

                    var $this_container = $(this),
                        fields_must_check = {};

                    $this_container.find('.bf-repeater-item .bf-section-container').each( function() {
                        if( typeof $(this).data( 'filter-field' ) != "undefined" ){
                            fields_must_check[$(this).data( 'filter-field' )] = $(this).data( 'filter-field' );
                        }
                    });


                    // Filter field
                    $.each( fields_must_check, function( index, value ){

                        $this_container.find('.bf-repeater-item').each( function(){

                            var $this_item = $(this);

                            var $_field =  $this_item.find('.bf-section[data-id=' + index + ']');

                            if( $_field.hasClass('bf-section-select-option') ){

                                $_field.on('change', '.bf-controls .bf-select-option-container select', function () {

                                    var val = $_field.find('.bf-controls .bf-select-option-container select').val();

                                    $_field.closest('.bf-repeater-item').find('.bf-section-container').each(function () {

                                        if( typeof $(this).data('filter-field-value') == "undefined" ) {
                                            return 1;
                                        }

                                        if($(this).data('filter-field-value') == val ) {
                                            $(this).fadeIn('200');
                                        } else {
                                            $(this).css({'display': 'none'});
                                        }

                                    });

                                });
                            }

                        } );
                    });
                } );
            }
            // For new repeaters
            else{

                var $repeater_items_container = scope;
                var fields_must_check = {};


                $repeater_items_container.find('.bf-repeater-item:last-child .bf-section-container').each( function() {
                    if( typeof $(this).data( 'filter-field' ) != "undefined" ){
                        fields_must_check[$(this).data( 'filter-field' )] = $(this).data( 'filter-field' );
                    }
                });


                // Filter field
                $.each( fields_must_check, function( index, value ){

                    var $_field =  $repeater_items_container.find('.bf-repeater-item:last-child .bf-section[data-id=' + index + ']');

                    if ($_field.hasClass('bf-section-select-option')) {

                        $_field.on('change', '.bf-controls .bf-select-option-container select', function () {

                            var val = $_field.find('.bf-controls .bf-select-option-container select').val();

                            $_field.closest('.bf-repeater-item').find('.bf-section-container').each(function () {

                                if( typeof $(this).data('filter-field-value') == "undefined" ) {
                                    return 1;
                                }

                                if($(this).data('filter-field-value') == val ) {
                                    $(this).fadeIn('200');
                                } else {
                                    $(this).css({'display': 'none'});
                                }

                            });

                        });
                    }
                });
            }




        },


        process_filter_field: function( field_id, field_value ){

            $( '.bf-section-container[data-filter-field=\'' + field_id + '\']').each( function(){

                if( $(this).data( 'filter-field-value' ) == field_value ){
                    $(this).fadeIn('200');
                }else{
                    $(this).css({'display':'none'});
                }

            });

        },



        /**
         * Meta Box
         ******************************************/

        set_metabox: function(){

            // todo refactor this and remove cookie
            $('.bf-metabox-wrap').each( function(i,o){

                var _metabox__id = $(this).data("id");

                var current_box_cookie = $.cookie( 'bf_metabox_current_tab_' + _metabox__id  );

                function bf_show_first_tab( tab ){
                    tab.find('li:first').find('a:first').addClass("active_tab");

                    tab.siblings('.bf-metabox-container').find( '#bf-metabox-' + _metabox__id + "-" + tab.find('li:first').data("go") ).fadeIn(500);
                }

                if( typeof current_box_cookie == 'undefined' ){
                    bf_show_first_tab( $(this).find('.bf-metabox-tabs') );
                }
                else {
                    if( ! $(this).find( '#bf-metabox-' + _metabox__id + "-" + current_box_cookie ).exist() || ! $(this).find(".bf-metabox-tabs").find("a[data-go='"+current_box_cookie+"']").exist() ){
                        bf_show_first_tab( $(this).find('>.bf-metabox-tabs') );
                        $.removeCookie('bf_metabox_current_tab_' + _metabox__id);
                        return;
                    }

                    $(this).find( '#bf-metabox-' + _metabox__id + "-" + current_box_cookie ).fadeIn();
                    $(this).find(".bf-metabox-tabs").find("a[data-go='"+current_box_cookie+"']").addClass("active_tab");
                    if( $(this).find(".bf-metabox-tabs").find("a[data-go='"+current_box_cookie+"']").is(".bf-tab-subitem-a") ){
                        $(this).find(".bf-metabox-tabs").find("a[data-go='"+current_box_cookie+"']").closest(".has-children").addClass("child_active");
                    }
                }

                $(this).find('.bf-metabox-tabs').find('a').click( function(){

                    var _this = $(this);

                    if( _this.hasClass( 'active_tab' ) ){
                        return false;
                    }

                    var _metabox_wrap = $(this).closest(".bf-metabox-wrap");
                    var _metabox_nav = _metabox_wrap.find(".bf-metabox-tabs");
                    if( typeof _metabox__id == 'undefined' )
                        var _metabox__id = _metabox_wrap.data("id");

                    var _hasNotGroup = (
                    ( _this.parent().hasClass('has-children') )
                    &&
                    ( ! _metabox_wrap.find( '#bf-metabox-' + _metabox__id + "-" + _this.data("go") ).find(">*").exist() )
                    );

                    var _isChild = ! _this.parent().hasClass('has-children');

                    if( _hasNotGroup ){
                        var _clicked = _this.siblings('ul.sub-nav').find('a:first');
                        var _target = _metabox_wrap.find( '#bf-metabox-' + _metabox__id + "-" + _clicked.data("go") );
                    } else {
                        var _clicked = _this;
                        var _target = _metabox_wrap.find( '#bf-metabox-' + _metabox__id + "-" + _clicked.data("go") );
                    }

                    _metabox_nav.find('a').removeClass("active_tab");
                    _metabox_nav.find('ul.sub-nav').find('a').removeClass("active_tab");
                    _metabox_nav.find('li').removeClass("child_active");

                    _metabox_wrap.find(".bf-metabox-container").find('>div').hide();
                    _target.fadeIn(500);

                    $.cookie( 'bf_metabox_current_tab_' + _metabox__id, _clicked.data("go"), { expires: 7 });

                    _clicked.addClass("active_tab");

                    if( _this.parent().hasClass('has-children') || _this.parent().parent().hasClass('sub-nav') ){

                        _clicked.closest('.has-children').addClass("child_active");

                    }

                    return false;
                });

                $(this).find( '.bf-metabox-container.bf-with-tabs').css( 'min-height',  $(this).find('.bf-metabox-tabs').height() + 50 );

            });


        },

        // Advanced filter for filter metaboxes for post format's
        metabox_filter_postformat: function(){

            var _current_format = $('#post-formats-select input[type=radio][name=post_format]:checked').attr('value');
            if( parseInt( _current_format ) ==0 )
                _current_format='standard';

            $('.bf-metabox-wrap').each(function(){
                if(typeof  $(this).data('bf_pf_filter') == 'undefined' || $(this).data('bf_pf_filter') == '')
                    return 1;

                var _metabox_id = '#bf_'+$(this).data('id'),
                    __metabox_id = 'bf_'+$(this).data('id'),

                    _formats = $(this).data('bf_pf_filter').split(',');

                if($.inArray(_current_format,_formats)== -1)
                    $(_metabox_id).hide();
                else
                    $(_metabox_id).show();
            });

            $('#post-formats-select input[type=radio][name=post_format]').change(function(){
                Better_Framework.metabox_filter_postformat();
            });

        },


        // Advanced filter for filter metabox fields for post format's
        metabox_field_filter_postformat: function(){

            var _current_format = $('#post-formats-select input[type=radio][name=post_format]:checked').attr('value');
            if(parseInt(_current_format)==0)
                _current_format='standard';

            $('.bf-field-post-format-filter').each(function(){

                if(typeof  $(this).data('bf_pf_filter') == 'undefined' || $(this).data('bf_pf_filter') == '')
                    return 1;

                var _formats = $(this).data('bf_pf_filter').split(',');

                if( $.inArray( _current_format, _formats ) == -1 )
                    $(this).hide();
                else
                    $(this).show();
            });


            $('#post-formats-select input[type=radio][name=post_format]').change(function(){
                Better_Framework.metabox_field_filter_postformat();
            });

        },

        vc_design_option_tab_fix:function() {
            $(document).ajaxSuccess(function (e, xhr, settings) {

                var _data = $.unserialize(settings.data);

                if (_data.action == "wpb_show_edit_form" || _data.action == "vc_edit_form") {
                    $("#vc_ui-panel-edit-element")
                        .find('.bf-css-edit-switch')
                        .closest('.vc_column')
                        .addClass('bf-css-edit-switch-container')
                }
            });
        },
        /**
         * Visual Composer
         ******************************************/

        setup_interactive_fields_for_vc: function() {

            $(document).ajaxSuccess(function (e, xhr, settings) {

                var _data = $.unserialize(settings.data);

                if (_data.action == "wpb_show_edit_form" || _data.action == "vc_edit_form") {
                    var $wrapper =  $("#vc_ui-panel-edit-element")
                              .find('.bf-interactive-fields');

                    $wrapper
                        .on('change force-change',':input', function (e) {

                            var $this = $(this),
                                active_type = this.value,
                                $row = $this.closest('.vc_row'),
                                columns_value = {};

                            $row.children('.vc_column').each(function () {
                                var $col = $(this),
                                    settings = $col.data('param_settings'),
                                    param_name = $col.data('vc-shortcode-param-name');

                                columns_value[ param_name ] = $col.find(':input').val();

                                if ('always_show' in settings && settings.always_show)
                                    return;

                                var action = e.type === 'force-change' ? 'hide' : 'slideUp';
                                if (settings.show_on instanceof Array) {

                                    var length = settings.show_on.length;

                                    for (var i = 0; i < length; i++) {
                                        var array_values = typeof settings.show_on[ i ] === 'string' ? [ settings.show_on[ i ] ] : settings.show_on[ i ],
                                            condition = true;

                                        for (var j = 0; j < array_values.length; j++) {
                                            var ac = array_values[ j ].split('=', 2),
                                                _param_name = ac[ 0 ],
                                                _param_value = typeof ac[ 1 ] === 'undefined' ? true : ac[ 1 ];

                                            if (typeof columns_value[ _param_name ] === 'undefined' || columns_value[ _param_name ] != _param_value) {

                                                condition = false;
                                                break;
                                            }
                                        }

                                        if (condition) {
                                            action = e.type === 'force-change' ? 'show' : 'slideDown';
                                            break;
                                        }
                                    }

                                    $col.stop()[ action ]();
                                }
                            })
                      });

                        //auto trigger event
                        var match,
                            classes = $wrapper.attr('class'),
                            $form_wrapper = $("#vc_ui-panel-edit-element"),
                            _regex =  /bf-filter-field-([^\s]+)/gi;

                    while (match = _regex.exec(classes)) {
                        var el_name = match[ 1 ];

                        $form_wrapper
                            .find('.bf-interactive-fields [name=' + el_name + ']')
                            .trigger('force-change');
                    }
                }
            });
        },

        // Setup fields when VC create new popup window
        setup_fields_for_vc: function(){

            jQuery(document).ajaxSuccess(function(e, xhr, settings) {

                var _data = $.unserialize(settings.data);

                if( _data.action == "wpb_show_edit_form" || _data.action == "vc_edit_form" ){
                    // TODO do this for just new elements

                    Better_Framework.setup_field_color_picker();
                    Better_Framework.setup_field_switch();
                    Better_Framework.setup_field_slider();
                    Better_Framework.setup_field_ajax_select();
                    Better_Framework.setup_vc_field_sorter();

                }

            });

            Better_Framework.set_up_vc_field_image_radio();
        },

        // Setup Sorter field
        setup_vc_field_sorter: function(){

            $( ".bf-vc-sorter-list" ).sortable({
                placeholder: "placeholder-item",
                cancel: "li.disable-item"
            });

            $('.bf-section-container.vc-input .bf-vc-sorter-list li input').on('change', function(evt, params) {

                if( typeof $(this).attr('checked') != "undefined" ){
                    $(this).closest('li').addClass('checked-item');
                }else{
                    $(this).closest('li').removeClass('checked-item');
                }

            });

            $('.bf-section-container.vc-input .bf-vc-sorter-checkbox-list li input, .bf-vc-sorter-list').on('change', function(evt, params) {

                var $parent = $(this).closest('.bf-section-container.vc-input'),
                $input = $parent.find('input.wpb_vc_param_value');

                rearrange_bf_vc_sorter_checkbox( $parent, $input );

            });

            $('.bf-vc-sorter-list').on('sortupdate', function(evt, params) {
                var $parent = $(this).closest('.bf-section-container.vc-input'),
                    $input = $parent.find('input.wpb_vc_param_value');

                rearrange_bf_vc_sorter_checkbox( $parent, $input );

            });

            function rearrange_bf_vc_sorter_checkbox( $parent, $input ){
                var _val = '';
                $('.bf-section-container.vc-input .bf-vc-sorter-checkbox-list li input[type=checkbox]:checked').each( function(){
                    if( _val.length == 0 ){
                        _val = $(this).attr( 'name' );
                    }else{
                        _val = _val + ',' + $(this).attr( 'name' );
                    }
                });
                $input.attr( 'value', _val );
            }
        },


        /**
         * Setup Visual Composer Image Radio Field
         */
        set_up_vc_field_image_radio: function(){

            $(document).on( 'click', '.vc-bf-image-radio-option', function(e){

                $(this).parent().find('input[type=hidden]').val( $(this).data( 'id' ) );

                // Remove checked class from field options and add checked class to clicked option
                $(this).siblings().removeClass('checked').end().addClass('checked');

                // Prevent Browser Default Behavior
                e.preventDefault();
            });

        },

        // Setup Switchery check box field
        setup_vc_field_switchery: function(){

            $('.bf-section-container.vc-input .js-switch').each( function(){

                if( $(this).prop( 'checked' ) ){
                    $(this).val( 1 );
                }else{
                    $(this).val( 0 );
                }

                new Switchery( this );

                $(this).on('change', function() {

                    if( $(this).prop( 'checked' ) ){
                        $(this).val( 1 );
                    }else{
                        $(this).val( 0 );
                    }

                });

            });
        },


        /**
         * Widgets
         ******************************************/

        // Setup widgets fields after ajax submit
        setup_widget_fields: function(){

            jQuery(document).ajaxSuccess(function(e, xhr, settings) {

                var _data = $.unserialize(settings.data);

                if( _data.action == "save-widget" ){
                    // TODO do this for just new elements
                    Better_Framework.setup_fields();
                    Better_Framework.setup_field_image_select();
                    Better_Framework.setup_field_color_picker();
                    Better_Framework.setup_filter_fields();
                    Better_Framework.setup_filter_fields_repeater();
                }

            });

            // Clone Repeater Item by click
            // TODO refactor this
            $(document).on( 'click', '.bf-widget-clone-repeater-item', function(e){
                e.preventDefault();
                var name_format = undefined === $(this).data( 'name-format' ) ? '$1[$2][$3][$4][$5]' : $(this).data( 'name-format'), _html = $(this).siblings('script').html();

                var _new = $(this).siblings('.bf-repeater-items-container').find('>*').size();
                var new_num = _new + 1;
//                new_num = '[' + new_num + ']';
                $(this).siblings('.bf-repeater-items-container').append(
                    _html
                       .replace( /([\"\'])(\|)(_to_clone_)(.+)?-num-(.+)?(\|)(\1)/g, '$1$2$3$4-num-'+new_num+'-$5$6$7$8')
                       .replace( /[\"\']\|_to_clone_(.+)?-(\d+)-(.+)?-num-(\d+)-(.+)\|[\"\']/g, '"'+name_format+'"' )
                );
                bf_color_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-color-picker') );
                bf_date_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-date-picker-input') );
                bf_image_upload_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-image-upload-choose-file') );
            });

        },
        sort_widgets: function() {

            return ;
            var $widgets = $("#widget-list"),
                widgets_count = $widgets.find('.widget').length - 1;

            $widgets.find(".bf-widget-position").sort(function (a, b) {
                if (parseInt(a.value) <= parseInt(b.value)) {
                    return -1;
                }
                return 1;
            }).each(function (index) {
                var $this = $(this),
                    $widget = $this.closest('.widget'),
                    position = Math.min($this.val(), widgets_count) + 1,
                    $target = $widgets.find('.widget:nth-child(' + position + ')');


                $widget.insertAfter($target);
            });
        },

        /**
         * Menus
         ******************************************/

        // Advanced trick for sending all extra fields inside one field for enabling user to add huge bunch of menu items
        // and our to add menu fields without worry about variable limitation
        menus_collect_fields_before_save: function(){

            // Temp variable for collecting all fields to one place.
            var betterMenuItems = {};

            $('form#update-nav-menu').submit(function( e ){

                //e.preventDefault();

                // disable extra fields for preventing send them to server
                $('*[name^="bf-m-i["]').attr("disabled", "disabled");

                // Iterate all extra fields
                $('*[name^="bf-m-i["]').each(function(){

                    var raw_name = $(this).attr('name'),
                        type = '',
                        post_id = '',
                        field_id = '';

                    if( raw_name.indexOf('[img]') > 0 ){
                        post_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4" );
                        field_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2" );
                        type = 'img';
                    }
                    else if( raw_name.indexOf('[icon]') > 0 ){
                        post_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4" );
                        field_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2" );
                        type = 'icon';
                    }
                    else if( raw_name.indexOf('[type]') > 0 ){
                        post_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4" );
                        field_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2" );
                        type = 'type';
                    }
                    else if( raw_name.indexOf('[width]') > 0 ){
                        post_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4" );
                        field_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2" );
                        type = 'width';
                    }
                    else if( raw_name.indexOf('[height]') > 0 ){
                        post_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4" );
                        field_id = raw_name.replace( /(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2" );
                        type = 'height';
                    }
                    else{
                        post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\])/g, "$4");
                        field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\])/g, "$2");
                        type = 'normal';
                    }

                    if( typeof betterMenuItems[post_id] == "undefined" ){
                        betterMenuItems[post_id] = {};
                    }

                    if( type == 'img' || type == 'type' || type == 'icon' || type == 'width' || type == 'height' ){

                        if( typeof betterMenuItems[post_id][field_id] == "undefined" ){
                            betterMenuItems[post_id][field_id] = {};
                        }
                        betterMenuItems[post_id][field_id][type] = $(this).val();

                    }else{
                        betterMenuItems[post_id][field_id] = $(this).val();
                    }

                });

                $(this).append( '<input type="hidden" name="bf-m-i" value="' + encodeURI( JSON.stringify( betterMenuItems ) ) + '" />' );
                //console.log( betterMenuItems );
            });

        },


        /**
         * General Fields For All Sections
         ******************************************/

        // Setup General Fields
        setup_fields: function() {

            Better_Framework.setup_fields_group();

            Better_Framework.setup_field_with_prefix_or_postfix();

            Better_Framework.setup_field_switch();

            Better_Framework.setup_field_slider();
            Better_Framework.setup_field_sorter();

            Better_Framework.setup_field_color_picker();

            Better_Framework.setup_field_image_radio();
            Better_Framework.setup_field_image_checkbox();
            Better_Framework.setup_field_image_select();

            Better_Framework.setup_field_date_picker();

            Better_Framework.setup_field_media_uploader();
            Better_Framework.setup_field_media_image();
            Better_Framework.setup_field_background_image();

            Better_Framework.setup_field_ajax_select();

            Better_Framework.setup_field_border();

            Better_Framework.setup_field_repeater();

            Better_Framework.setup_ajax_action();

            Better_Framework.setup_filter_fields();
            Better_Framework.setup_filter_fields_repeater();

        },


        // Setup Fields Group
        setup_fields_group: function(){

            $( document ).off( 'click', '.fields-group-title-container' );

            $( document ).on( 'click', '.fields-group-title-container', function() {

                var $_group = $(this).closest( '.fields-group'),
                    $_button = $_group.find( '.fields-group-title-container .collapse-button' );

                if( $_group.hasClass( 'open' ) ){

                    $_group.find('.bf-group-inner').slideUp(400);

                    $_group.removeClass('open').addClass('close');
                    $_button.find('.fa').removeClass('fa-minus').addClass('fa-plus');

                }else{

                    $_group.removeClass('close').addClass('open');
                    $_button.find('.fa').removeClass('fa-plus').addClass('fa-minus');

                    $_group.find('.bf-group-inner').slideDown(400);

                }

            });

        },


        // TODO Refactor This
        setup_field_repeater: function(){

            // Add jQuery UI Sortable to Repeater Items
            $('.bf-repeater-items-container').sortable({
                revert: true,
                cursor: 'move',
                delay: 150,
                handle: ".bf-repeater-item-title",
                start: function( event, ui ) {
                    ui.item.addClass('drag-start');
                },
                beforeStop: function( event, ui ) {
                    ui.item.removeClass('drag-start');
                }
            });

            // Remove Repeater Item
            $(document).on( 'click', '.bf-remove-repeater-item-btn', function( e ){

                var $section =  $(this).closest('.bf-section');
                if( confirm( 'Are you sure?' ) ){
                    $(this).closest('.bf-repeater-item').slideUp( function(){
                        $(this).remove();

                        // Event for when item removed
                        $section.trigger( 'after_repeater_item_removed' );
                    });
                }

                e.preventDefault();

            });

            // Collapse
            $(document).on( 'click', '.handle-repeater-item', function(){
                $(this).toggleClass('closed').closest('.bf-repeater-item').find('.repeater-item-container').slideToggle(400);
            });

            // Clone Repeater Item by click
            $(document).on( 'click', '.bf-clone-repeater-item', function(e){
                e.preventDefault();

                var $repeater_items_container = $(this).siblings('.bf-repeater-items-container'),
                    name_format = undefined === $(this).data( 'name-format' ) ? '$1[$2][$3]' : $(this).data( 'name-format'),
                    _html = $(this).siblings('script').html(),
                    count = $repeater_items_container.find('>*').size(),
                    index = count + 1;

                $repeater_items_container.append(
                    _html
                        .replace( /([\"\'])(\|)(_to_clone_)(.+)?(-)(num)(-)(.+)?(\|)(\1)/g, '$1$2$3$4$5'+index+'$7$8$9$10') // panel and widget
                        .replace( /[\"\'](\|)(_to_clone_)(.+)?-child-(.+)?-(\d+)-(.+)\|[\"\']/g, '"'+name_format+'"' ) // metabox
                        .replace( /[\"\']\|_to_clone_(.+)?-(\d+)-(.+)\|[\"\']/g, '"'+name_format+'"' ) // metabox
                );

                Better_Framework.setup_filter_fields_repeater( $repeater_items_container );

                // Event for when new item added
                $(this).closest('.bf-section').trigger('repeater_item_added');
            });

        },

        setup_field_border: function(){

            $('.bf-section-border-option').each(function(){
                refresh_border( $(this).closest(".bf-section-container"), 'first-time');
            });


            // When all changed
            $('.bf-section-container .single-border.border-all select, .bf-section-container .single-border.border-all input').on('change', function(evt, params) {
                refresh_border( $(this).closest(".bf-section-container"), 'all');
            });
            $('.bf-section-container .single-border.border-all input.bf-color-picker').on('change', function() {
                refresh_border( $(this).closest(".bf-section-container"), 'all');
            });

            // When top border changed
            $('.bf-section-container .single-border.border-top select, .bf-section-container .single-border.border-top input').on('change', function(evt, params) {
                refresh_border( $(this).closest(".bf-section-container"), 'top');
            });

            // When right border changed
            $('.bf-section-container .single-border.border-right select, .bf-section-container .single-border.border-right input').on('change', function(evt, params) {
                refresh_border( $(this).closest(".bf-section-container"), 'right');
            });

            // When bottom border changed
            $('.bf-section-container .single-border.border-bottom select, .bf-section-container .single-border.border-bottom input').on('change', function(evt, params) {
                refresh_border( $(this).closest(".bf-section-container"), 'bottom');
            });

            // When left border changed
            $('.bf-section-container .single-border.border-left select, .bf-section-container .single-border.border-left input').on('change', function(evt, params) {
                refresh_border( $(this).closest(".bf-section-container"), 'left');
            });


            // Used for refreshing all styles of border field
            function refresh_border( $parent, type ){
                type = typeof type !== 'undefined' ? type : 'all';

                var $preview = $parent.find('.border-preview .preview-box');

                var _css = $preview.css([]);

                switch ( type ){

                    case 'top':
                        _css = refresh_border_field( $parent, 'top', _css);
                        break;

                    case 'right':
                        _css = refresh_border_field( $parent, 'right', _css);
                        break;

                    case 'bottom':
                        _css = refresh_border_field( $parent, 'bottom', _css);
                        break;

                    case 'left':
                        _css = refresh_border_field( $parent, 'left', _css);
                        break;

                    case 'all':
                        _css = refresh_border_field( $parent, 'all', _css);
                        break;

                    case 'first-time':

                        if( $parent.find('.single-border.border-all').length ){
                            _css = refresh_border_field( $parent, 'all', _css);
                        }else{

                            if( $parent.find('.single-border.border-top').length ){
                                _css = refresh_border_field( $parent, 'top', _css);
                            }

                            if( $parent.find('.single-border.border-right').length ){
                                _css = refresh_border_field( $parent, 'right', _css);
                            }

                            if( $parent.find('.single-border.border-bottom').length ){
                                _css = refresh_border_field( $parent, 'bottom', _css);
                            }

                            if( $parent.find('.single-border.border-left').length ){
                                _css = refresh_border_field( $parent, 'left', _css);
                            }

                        }

                }

//                $preview.attr('style', '');
                $preview.css( _css );
            }

            // Used for refreshing border preview
            function refresh_border_field( $parent, type, _css ){

                switch ( type ){

                    case  'top':
                        _css.borderTopWidth = $parent.find('.single-border.border-top .border-width input').val() +'px';
                        _css.borderTopStyle = $parent.find('.single-border.border-top select option:selected').val();
                        _css.borderTopColor = $parent.find('.single-border.border-top .bf-color-picker').val();
                        break;

                    case  'right':
                        _css.borderRightWidth = $parent.find('.single-border.border-right .border-width input').val() +'px';
                        _css.borderRightStyle = $parent.find('.single-border.border-right select option:selected').val();
                        _css.borderRightColor = $parent.find('.single-border.border-right .bf-color-picker').val();
                        break;

                    case  'bottom':
                        _css.borderBottomWidth = $parent.find('.single-border.border-bottom .border-width input').val() +'px';
                        _css.borderBottomStyle = $parent.find('.single-border.border-bottom select option:selected').val();
                        _css.borderBottomColor = $parent.find('.single-border.border-bottom .bf-color-picker').val();
                        break;

                    case  'left':
                        _css.borderLeftWidth = $parent.find('.single-border.border-left .border-width input').val() +'px';
                        _css.borderLeftStyle = $parent.find('.single-border.border-left select option:selected').val();
                        _css.borderLeftColor = $parent.find('.single-border.border-left .bf-color-picker').val();
                        break;

                    case 'all':
                        _css.borderWidth = $parent.find('.single-border.border-all .border-width input').val() +'px';
                        _css.borderStyle = $parent.find('.single-border.border-all select option:selected').val();
                        _css.borderColor = $parent.find('.single-border.border-all .bf-color-picker').val();
                        break;

                }

                return _css;

            }


        },

        // Setup Image Select Field
        setup_field_image_select: function(){

            // Open Close Select Options Box
            $(document).on('click', '.better-select-image' ,function(e){
                var $_target = $(e.target);

                if ( $_target.hasClass('selected-option')  ) {
                    // close All Other open boxes
                    $(this).toggleClass('opened');
                    return;
                }
                if( $_target.hasClass('select-options') ){
                    $(this).toggleClass('opened');
                    return;
                }

                if( $_target.hasClass('image-select-option') ){
                    return;
                }

            });

            // Close Everywhere clicked
            $(document).on('click',function( e ){
                if (e.target.class !== 'better-select-image' && $(e.target).parents('.better-select-image').length === 0) {
                    $('.better-select-image').each(function(){
                        if($(this).hasClass('opened')){
                            $(this).removeClass('opened');
                        }
                    });
                }
            });
            // Select when clicked
            $(document).on('click', '.better-select-image .image-select-option' ,function(e){
                var $this = $(this);
                var $parent = $this.closest('.better-select-image');
                var $input = $parent.find('input[type=hidden]');
                var $selected_label = $parent.find('.selected-option');

                if($this.hasClass('selected')){
                    e.preventDefault();
                    $parent.find('.select-options').toggleClass('opened');
                }
                else{
                    $input.attr('value',$this.data('value')).trigger('change');
                    $parent.find('.image-select-option.selected').removeClass('selected');
                    $this.addClass('selected');
                    $selected_label.html($this.data('label'));
                    $parent.toggleClass('opened');
                }
            });


        },


        // Setup input fields prefix and postfix
        setup_field_with_prefix_or_postfix: function(){

            $(document).on('click', '.bf-prefix-suffix', function(){
                $(this).siblings(':input').focus();
            })


            $('.bf-field-with-prefix-or-suffix').each(function(){
                if( $(this).find('.bf-prefix').exist() )
                    $(this).find(':input').css( 'padding-left', ( $(this).find('.bf-prefix').width() + 15 ) );
                if( $(this).find('.bf-suffix').exist() )
                    $(this).find(':input').css( 'padding-right', ( $(this).find('.bf-suffix').width() + 15 ) );
            });
        },

        // Setup Switch check box field
        setup_field_switch: function(){

            $(document).on( 'click', ".cb-enable", function(){
                var parent = $(this).parents('.bf-switch');
                $('.cb-disable',parent).removeClass('selected');
                $(this).addClass('selected');

                if( $('.checkbox',parent).hasClass('bf_switchery_field') ){
                    $('.checkbox',parent).attr('value', 1).trigger('change');
                }else{
                    $('.checkbox',parent).attr('value', 1).trigger('change');
                }
            });

            $(document).on( 'click', ".cb-disable", function(){
                var parent = $(this).parents('.bf-switch');
                $('.cb-enable',parent).removeClass('selected');
                $(this).addClass('selected');

                if( $('.checkbox',parent).hasClass('bf_switchery_field') ){
                    $('.checkbox',parent).attr('value', 0).trigger('change');
                }else{
                    $('.checkbox',parent).attr('value', 0).trigger('change');
                }
            });

        },

        // Set up Slider filed
        setup_field_slider: function(){

            var selector = '';

            // prepare selector
            if( better_framework_loc.type == 'widgets' ){
                selector = '#widgets-right .bf-slider-slider';
            }
            else{
                selector = '.bf-slider-slider';
            }

            $(selector).each( function(){

                var _min = $(this).data('min');
                var _max = $(this).data('max');
                var _step = $(this).data('step');
                var _animate = $(this).data('animation') == 'enable' ? true : false;
                var _dimension = ' ' + $(this).data('dimension');
                var _val = $(this).data('val');
                var _this = $(this);

                $(this).slider({
                    range: 'min',
                    animate: _animate,
                    value: _val,
                    step: _step,
                    min: _min,
                    max: _max,
                    slide: function( event, ui ) {
                        _this.find(".ui-slider-handle").html( '<span>'+ui.value+_dimension+'</span>' );
                        _this.siblings('.bf-slider-input').val( ui.value );
                    },
                    create: function( event, ui ) {
                        _this.find(".ui-slider-handle").html( '<span>'+_val+_dimension+'</span>' );
                    }
                });

                $(this).removeClass('not-prepared');

            });
        },

        // Setup color picker fields
        setup_field_color_picker: function(){
            $(document.body).on('click', '.bs-color-picker-wrapper .wp-color-result:not(.bs-clicked)', function(e) {
                e.preventDefault();

                var $this = $(this);
                $this.bsWpColorPicker();
                $this.addClass('bs-clicked');
            });
        },

        // Setup Sorter field
        setup_field_sorter: function(){

            // Sorters in Widgets Page
            if( better_framework_loc.type == 'widgets' ){
                $( "#widgets-right .bf-sorter-list" ).sortable({
                    placeholder: "placeholder-item",
                    cancel: "li.disable-item"
                });
            }
            // Sorters Everywhere
            else{
                $( ".bf-sorter-list" ).sortable({
                    placeholder: "placeholder-item",
                    cancel: "li.disable-item"
                });
            }

            $('.bf-section-container li input').on('change', function(evt, params) {

                if( typeof $(this).attr('checked') != "undefined" ){
                    $(this).closest('li').addClass('checked-item');
                }else{
                    $(this).closest('li').removeClass('checked-item');
                }

            });
        },

        // Setup image radio
        setup_field_image_radio: function(){

            $(document).on( 'click', '.bf-image-radio-option', function(e){

                // Uncheck all radio button for this field
                $(this).parent().find(':radio').prop("checked", false);

                // Checked the clicked radio button
                // Fires change for third party code usage
                $(this).find(':radio').prop("checked", true).change();

                // Remove checked class from field options and add checked class to clicked option
                $(this).siblings().removeClass('checked').end().addClass('checked');

                // Prevent Browser Default Bahavior
                e.preventDefault();
            });

        },

        // Setup Background Field
        setup_field_background_image: function(){

            $('body').on( 'click', '.bf-background-image-upload-btn' ,function() {

                var _this = $(this);

                var media_title = _this.attr('data-mediaTitle');
                var media_button = _this.attr('data-mediaButton');

                // prepare uploader
                var custom_uploader;

                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }

                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: media_title,
                    button: {
                        text: media_button
                    },
                    multiple: false,
                    library: { type : 'image'}
                });

                // when select pressed in uploader popup
                custom_uploader.on('select', function() {


                    var attachment = custom_uploader.state().get('selection').first().toJSON();

                    _this.siblings('.bf-background-image-preview').find("img").attr( "src", attachment.url );

                    _this.siblings('.bf-background-image-input').val( attachment.url );

                    _this.siblings('.bf-background-image-preview').show(100);
                    _this.siblings('.bf-background-image-uploader-select-container').removeClass('hidden').show(100);
                    _this.siblings('.bf-background-image-remove-btn').show(100);


                });

                // open media poup
                custom_uploader.open();

                return false;
            });
            $('body').on( 'click', '.bf-background-image-remove-btn' ,function() {
                var _this = $(this);

                _this.siblings('.bf-background-image-input').val( '' );

                // hide remove button, select and preview
                _this.hide( 100 );
                _this.siblings('.bf-background-image-uploader-select-container').addClass('hidden').hide( 100 );
                _this.siblings('.bf-background-image-preview').hide( 100 );

            });
        },

        // Setup Date Picker Field
        setup_field_date_picker: function(){

            $('.bf-date-picker-input').each( function(){

                var _date_format = $(this).data('date-format');
                $(this).datepicker({ dateFormat: _date_format });

            });


        },

        // Setup Image Checkbox field
        setup_field_image_checkbox: function(){

            // Image Checkbox Codes
            $(document).on( 'click', '.bf-image-checkbox-option', function(e){
                var _this = $(this);
                var _checkbox = _this.find(':checkbox');

                // If checkbox is check uncheck it and remove checked class from it

                if ( _checkbox.is(':checked') ) {
                    _checkbox.prop( 'checked', false );
                    _this.removeClass('checked');
                }
                else {
                    _checkbox.prop( 'checked', true );
                    _this.addClass('checked');
                }

                // Prevent Browser Default Bahavior
                e.preventDefault();
            });

            $('.is-sortable .bf-controls-image_checkbox-option').sortable({
                helper: 'clone',
                revert: true,
                forcePlaceholderSize: true,
                opacity: 0.5
            });

        },

        // Setup Media Uploader Field
        setup_field_media_uploader: function(){

            $('body').on( 'click', '.bf-media-upload-btn' ,function() {


                var _this = $(this);
                var custom_uploader;

                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }

                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: _this.data('mediatitle'),
                    button: {
                        text: _this.data('buttontext')
                    },
                    multiple: false
                });

                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    _this.siblings(':input').val( attachment.url );
                    custom_uploader.state().get('selection').each( function(i,o){
                    });
                });
                custom_uploader.open();
                return false;
            });

        },

        // Setup Media Image upload field
        setup_field_media_image: function(){

            var _media_image_selector = '.bf-media-image-upload-btn';

            $(document).off('click', _media_image_selector).on( 'click', _media_image_selector ,function( e ) {
                var _this = $(this),
                    $_input = _this.siblings(':input');

                var custom_uploader;

                var media_title = _this.data('media-title');
                var media_button_text = _this.data('button-text');

                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: media_title,
                    button: {
                        text: media_button_text
                    },
                    multiple: false,
                    library: { type : 'image'}
                });

                custom_uploader.on('select', function() {

                    var attachment = custom_uploader.state().get('selection').first().toJSON();

                    if( _this.hasClass( 'bf-media-type-id' ) ){
                        $_input.val( attachment.id );
                    }else{
                        $_input.val( attachment.url );
                    }

                    var preview = '';

                    if( typeof _this.data('size') != "undefined" ){

                        var var_name = _this.data('size');

                        if( typeof attachment.sizes[var_name] != "undefined" ){
                            preview = attachment.sizes[var_name].url;
                        }else{
                            preview = attachment.url;
                        }

                    }else{
                        preview = attachment.url;
                    }

                    _this.siblings('.bf-media-image-remove-btn').show();
                    _this.siblings('.bf-media-image-preview').find('img').attr( 'src', preview );
                    _this.siblings('.bf-media-image-preview').show();

                    // Global change event
                    _this.trigger( 'bf-media-image-changed', {
                        'type': _this.hasClass( 'bf-media-type-id' ) ? 'id' : 'src',
                        'name': $_input.attr('name'),
                        'attachment': attachment
                    } );

                    // field Global change event
                    _this.trigger( 'bf-media-image-changed:' + $_input.attr('name'), {
                        'type': _this.hasClass( 'bf-media-type-id' ) ? 'id' : 'src',
                        'name': $_input.attr('name'),
                        'attachment': attachment
                    } );

                });

                custom_uploader.open();

                return false;
            });

            $(document).on( 'click', '.bf-media-image-remove-btn' ,function() {
                var _this = $(this);

                _this.siblings('.bf-media-image-input').val( '' );

                // hide remove button, select and preview
                _this.hide();
                _this.siblings('.bf-media-image-preview').hide();

            });

        },

        // Setup Ajax Select Field
        setup_field_ajax_select: function(){

            // TODO : Vahid Shit! Refactor this

            function bf_ajax_generate_options_object( _that ){
                var _object_ = {};
                _object_.preloader 	    = _that.siblings('.bf-search-loader');
                _object_.static_parent  = _that.closest('.bf-ajax_select-field-container');
                _object_.field_controls = _that.closest('.bf-ajax_select-field-container');
                _object_.controls_box   = _that.siblings('.bf-ajax-suggest-controls');
                _object_.hidden_field   = _that.siblings('input[type=hidden]');
                _object_.result_box	    = _that.siblings('.bf-ajax-suggest-search-results');
                _object_._this	    	= _that;
                return _object_;
            };

            var bf_ajax_input_timeOut  = null;

            var bf_ajax_input_interval = 850;

            $(document).on( 'keyup', '.bf-ajax-suggest-input', function(e){

                var _this   =   $(e.target),
                    _s      =   bf_ajax_generate_options_object( _this );

                _this.appendHTML = function( html ){
                    _s.result_box.append(html);
                };

                _this.removeResults = function(){
                    _s.result_box.find('li').remove();
                };

                _this.generateResultItems = function( json ){

                    var result = '';

                    $.each( $.parseJSON(json), function(i,o){
                        result += '<li class="ui-state-default" data-id="'+i+'">'+o+' <i class="del fa fa-remove"></i></li>';
                    });

                    return result;
                };

                _this.get = function( key, callback ){

                    var is_repeater = _this.parent().is('.bf-repeater-controls-option'),
                        form_data   = {
                            action 		     : 'bf_ajax',
                            reqID  	 	     : 'ajax_field',
                            type	 	     : better_framework_loc.type,
                            field_ID 	     : _s.hidden_field.attr('name'),
                            nonce 	 	     : better_framework_loc.nonce,
                            key   	         : key,
                            is_repeater      : is_repeater ? 1 : 0,
                            callback         : _s.hidden_field.data('callback'),
                            bf_call_token    : _s.hidden_field.data('token'),
                            exclude          : _s.hidden_field.val()
                        },
                        result;

                    if( is_repeater )
                        form_data.repeater_id = _this.closest('.bf-nonrepeater-section').data('id');

                    $.ajax({
                        type	 : 'POST',
                        dataType : 'html',
                        url		 : better_framework_loc.bf_ajax_url,
                        data	 : form_data,
                        success  : function(data, textStatus, XMLHttpRequest){
                            callback(data);
                        },
                        error: function(MLHttpRequest, textStatus, errorThrown){
                            callback(false);
                        }
                    });
                };

                clearTimeout( bf_ajax_input_timeOut );

                bf_ajax_input_timeOut = setTimeout(function() {

                    _s.preloader.addClass('loader');

                    _this.get( _this[0].value, function(data){

                        _s.preloader.removeClass('loader');

                        if( data === false ){
                            alert( 'Something Wrong Happend!' );
                            return;
                        }

                        _s.controls_box.sortable({

                            update: function(event, ui){
                                var _this = ui.item, value = '', _s_ = bf_ajax_generate_options_object( _this.parent().siblings('.bf-ajax-suggest-input') );

                                _s_.controls_box.find('li:not(".ui-sortable-placeholder")').each( function(){
                                    value += $(this).data('id') + ','
                                });

                                _s_.hidden_field.val( value.replace( ',,', ',' ).replace( /^,+/ ,'' ).replace( /,+$/, '' ) );
                            }

                        });

                        if( data == -1 ){
                            return;
                        }

                        _this.removeResults(); // Remove Current Results

                        var HTML = _this.generateResultItems( data ); // Generate HTML tags from JSON

                        _this.appendHTML( HTML ); // Append The HTMLs

                        _s.result_box.fadeIn();

                    })

                }, bf_ajax_input_interval);
            });

            $(document).on( 'blur', '.bf-ajax-suggest-input', function(){

                var _s = bf_ajax_generate_options_object($(this));

                _s.result_box.fadeOut();

            });

            $(document).on( 'focus', '.bf-ajax-suggest-input', function(){

                var _s = bf_ajax_generate_options_object($(this));

                if( _s.result_box.find('li').size() > 0 )
                    _s.result_box.fadeIn();

            });

            $(document).on( 'click', '.bf-ajax-suggest-search-results li', function(e){
                var _this   = $( e.target ),
                    _s      = bf_ajax_generate_options_object( _this.parent().siblings('.bf-ajax-suggest-input') );

                _s.result_box.fadeOut();

                if( _s.controls_box.find('li[data-id="'+_this.data('id')+'"]').exist() )
                    return true;

                var value = _s.hidden_field.val() === undefined ? [] : _s.hidden_field.val().split(',');

                value.push( _this.data('id') );

                _s.controls_box.append( e.target.outerHTML );

                _s.hidden_field.val($.array_unique( value ).join(',').replace(',,',',').replace(/^,+/,'').replace(/,+$/,''));

                $(this).remove();

                return false;

            });

            $(document).on( 'click', '.bf-ajax-suggest-controls li .del', function(e){

                if( confirm('Are You Sure?') ){
                    var _new,
                        _this = $(e.target).parent(),
                        _array,
                        ID = _this.data('id'),
                        _s = bf_ajax_generate_options_object( _this.parent().siblings('.bf-ajax-suggest-input') );

                    _this.remove();

                    _array = _s.hidden_field.val().split(',');

                    _new = $.grep( _array, function(value) {
                        return value != ID;
                    });

                    _s.hidden_field.val( _new.join( ',' ).replace( ',,', ',' ).replace( /^,+/, '' ).replace( /,+$/, '' ) );
                }
            });

            $('.bf-ajax-suggest-controls').sortable({
                update: function(event, ui){
                    var _this   =   ui.item,
                        value   =   '',
                        _s_     =   bf_ajax_generate_options_object( _this.parent().siblings('.bf-ajax-suggest-input') );

                    _s_.controls_box.find('li:not(".ui-sortable-placeholder")').each( function(){
                        value += $(this).data('id') + ','
                    });

                    _s_.hidden_field.val( value.replace( ',,', ',' ).replace( /^,+/ ,'' ).replace( /,+$/, '' ) );

                }
            });

        }





    };

})(jQuery);

// load when ready
jQuery(function($) {

    Better_Framework.init();

});


/**
 * Plugins and 3rd Party Libraries
 */

jQuery(function($) {
    $.unserialize = function(serializedString){
        var str = decodeURI(serializedString);
        var pairs = str.split('&');
        var obj = {}, p, idx, val;
        for (var i=0, n=pairs.length; i < n; i++) {
            p = pairs[i].split('=');
            idx = p[0];

            if (idx.indexOf("[]") == (idx.length - 2)) {
                // Eh um vetor
                var ind = idx.substring(0, idx.length-2)
                if (obj[ind] === undefined) {
                    obj[ind] = [];
                }
                obj[ind].push(p[1]);
            }
            else {
                obj[idx] = p[1];
            }
        }
        return obj;
    };
});


(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);

(function($) {
    String.prototype.sprintf = function(format){
        var formatted = this;
        for (var i = 0; i < arguments.length; i++) {
            var regexp = new RegExp('%'+i, 'gi');
            formatted = formatted.replace(regexp, arguments[i]);
        }
        return formatted;
    };
})(jQuery);// Custom Plugins

(function($) {
    $.array_unique = function (inputArr) {
        // Removes duplicate values from array
        var key = '',
            tmp_arr2 = [],
            val = '';

        var __array_search = function (needle, haystack) {
            var fkey = '';
            for (fkey in haystack) {
                if (haystack.hasOwnProperty(fkey)) {
                    if ((haystack[fkey] + '') === (needle + '')) {
                        return fkey;
                    }
                }
            }
            return false;
        };

        for (key in inputArr) {
            if (inputArr.hasOwnProperty(key)) {
                val = inputArr[key];
                if (false === __array_search(val, tmp_arr2)) {
                    tmp_arr2[key] = val;
                }
            }
        }

        return tmp_arr2;
    };

    $.removeFromArray = function(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }

    // Custom Serializer
    $.fn.bf_serialize = function() {
        var toReturn	= [];
        var els		 = $(this).find(':input').get();
        $.each(els, function() {
            var $this = $(this);
            if ( this.name && !this.disabled ) {
                var val = $this.val();
                if( $this.is(':checkbox,:radio') ){
                    val = $this.is(':checked') ? '1' : '0';
                }
                toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
            }

        });
        return toReturn.join("&").replace(/%20/g, "+");
    }

    // Elemnt Exist Check Plugin
    $.fn.exist = function() {
        return this.size() > 0;
    }
})(jQuery);

// Update query string : http://stackoverflow.com/questions/5999118/add-or-update-query-string-parameter
function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            var hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?',
                hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}


// res : http://stackoverflow.com/questions/1766299/make-search-input-to-filter-through-list-jquery
// custom css expression for a case-insensitive contains()
(function($) {
    jQuery.expr[':'].Contains = function(a,i,m){
        return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
    };
})(jQuery);
