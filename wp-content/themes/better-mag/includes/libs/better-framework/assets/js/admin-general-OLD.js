/*-----------------------------------------------------------------------------------*/
/* GENERAL SCRIPTS */
/*-----------------------------------------------------------------------------------*/


//TODO do this in oop and rewrite all the file!!
jQuery(document).ready(function($){



	// Slider
	$('.bf-slider-slider').each( function(){
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
		    slide: function(  event, ui ) {
		        $(this).find(".ui-slider-handle").html( '<span><span></span>'+ui.value+_dimension+'</span>' );
		        _this.siblings('.bf-slider-input').val( ui.value );
		    },
		    create: function(  event, ui ) {
		        $(this).find(".ui-slider-handle").html( '<span><span></span>'+_val+_dimension+'</span>' );
		    }
		});
	});	


	// Image uploader Stuff
    function bf_image_upload_plugin( elemnt ){
        elemnt.each( function(){
            var _this = $(this);
            $(this).fileupload({
                limitMultiFileUploads: 1,
                formData: {
                    action	: 'bf_ajax',
                    reqID	: 'image_upload',
                    type	: bf.type,
                    file_id : $(this).attr('name'),
                    nonce   : bf.nonce
                },
                url: bf.bf_ajax_url,
                dataType: 'json',
                dropZone: $(this).closest('.bf-section'),
                start: function (e) {
                    $(this).parent().siblings('.bf-image-upload-progress-bar').slideDown();
                },
                done: function (e, data) {
                    if ( data.result.status == 'succeed' ) {
                        $(this).parent().siblings(':input').val( data.result.url );
                        alert('Succeed');
                    } else if ( data.result.status == 'error' ){
                        alert('ERROR');
                    }
                    $(this).parent().parent().find('.bf-image-upload-progress-bar').slideUp();
                    $(this).parent().parent().find('.bf-image-upload-progress-bar .bar').css('width', '0%');
                    $(this).parent().parent().find('.bf-image-upload-preview img').attr('src', data.result.url );
                    console.log(data.result);
                },
                progressall: function (e, data) {
                    var progress = parseInt( data.loaded / data.total * 100, 10);
                    $(this).parent().parent().find('.bf-image-upload-progress-bar').find('.bar').css( 'width', progress + '%' );
                }
            });
        });
    }
    bf_image_upload_plugin( $('.bf-image-upload-choose-file') );


	/* Code Editor */
//	bf_code_editors = new Array;
//	$('.bf-code-editor').each(function(index) {
//		var __this = this;
//		//$(this).attr('id', 'bf-code-editor-' + index);
//		var _code_type = $(this).data('lang');
//		var tesdex = 'abcd' + index;
//		var _line_numbers_ = $(this).data('line-numbers') == 'enable' ? true : false;
//		var _auto_close_brackets = $(this).data('auto-close-brackets') == 'enable' ? true : false;
//		var _auto_close_tags = $(this).data('auto-close-tags') == 'enable' ? true : false;
//		tesdex = CodeMirror.fromTextArea(
//		$(this)[0],
//			{
//				mode: _code_type,
//				lineNumbers: _line_numbers_,
//				autoCloseBrackets: _auto_close_brackets,
//				autoCloseTags: _auto_close_tags,
//			}
//		);
//		tesdex.on("change", function() {
//			$(__this).val( tesdex.getValue() );
//		});
//		tesdex.refresh();
//		bf_code_editors[ index ]	= tesdex;
//	});
//	function refresh_bf_code_editors(){
//		$.each( bf_code_editors, function(i,o){
//			o.refresh();
//		});
//	}


	// Refresh Typography Preview
	$(document).on( 'change', '.bf-controls[refreshonchange="enable"] :input', function(){
		$(this).closest('.bf-controls').find('.bf-refresh-typo-btn').trigger('click');
	});

	// Typography Refresh Button
	$(document).on( 'click', '.bf-refresh-typo-btn', function(e){
		var _t = $(this);
		var _css = {};
		
		if( _t.siblings('.bf-typography-font-size').exist() )
			_css.fontSize = _t.siblings('.bf-typography-font-size').find(':input').val() + _t.siblings('.bf-typography-font-size-unit').find(':input').val();
		
		if( _t.siblings('.bf-typography-font-family').exist() )
			_css.fontFamily = _t.siblings('.bf-typography-font-family').find(':input').val();
		
		if( _t.siblings('.bf-typography-bold').exist() )
			_css.fontWeight = _t.siblings('.bf-typography-bold').find(':input').val() == 'on' ? 'bold' : 'normal';
		
		if( _t.siblings('.bf-typography-italic').exist() )
			_css.fontStyle = _t.siblings('.bf-typography-italic').find(':input').val() == 'on' ? 'italic' : 'normal';
		
		if( _t.siblings('.bf-typography-color').exist() )
			_css.color = _t.siblings('.bf-typography-color').find(':input').val();

		_t.siblings('.bf-typo-preview').find('p').css(_css);
		
		_t.closest('.bf-controls').attr( 'RefreshOnChange', 'enable' );
		
		console.log( _css );
		
		e.preventDefault();
		
	});

	// Clone Repeater Item by click
	$(document).on( 'click', '.bf-clone-repeater-item', function(e){
		e.preventDefault();
        var neme_format = undefined === $(this).data( 'name-format' ) ? '$1[$2][$3]' : $(this).data( 'name-format'), _html = $(this).siblings('script').html();

		var _new = $(this).siblings('.bf-repeater-items-container').find('>*').size();
        var new_num = _new + 1;
		$(this).siblings('.bf-repeater-items-container').append(
            _html
                .replace( /([\"\'])(\|)(_to_clone_)(.+)?(-)(num)(-)(.+)?(\|)(\1)/g, '$1$2$3$4$5'+new_num+'$7$8$9$10')
                .replace( /[\"\']\|_to_clone_(.+)?-(\d+)-(.+)\|[\"\']/g, '"'+neme_format+'"' )
        );
        bf_color_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-color-picker') );
        bf_date_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-date-picker-input') );
        bf_image_upload_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-image-upload-choose-file') );
	});

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
	$(document).on( 'click', '.bf-remove-repeater-item-btn', function(){
		if( confirm( 'Are you sure?' ) )
			$(this).closest('.bf-repeater-item').slideUp( function(){
				$(this).remove();
			});
	});

	// Colapse
	$(document).on( 'click', '.handle-repeator-item', function(){
        $(this).toggleClass('closed').closest('.bf-repeater-item').find('.repeater-item-container').toggle();
	});




    // Reset Frame
    $('.bf-reset-options').click(function(){
        var _frame =  $(this).siblings('.bf-reset-options-frame');
        _frame.is(':visible') ? _frame.hide(200) : _frame.show(200);
    });
    $('.bf-reset-options-frame-tabs-list').find('li:first').each(function(){
        $(this).addClass('current').parent().siblings('.bf-reset-options-frame-tabs').find('>[data-id="'+$(this).data('id')+'"]').fadeIn();
    });
    $('.bf-reset-options-frame-tabs-list').find('li').click(function(){
        var _this       = $(this);
        var _id         = $(this).data('id');
        var _tabs_list  = _this.parent();
        var _tabs       = _tabs_list.siblings('.bf-reset-options-frame-tabs');
        _tabs_list.find('.current').removeClass('current').end().find(this).addClass('current');
        _tabs.find('div:visible').hide(250).siblings(this).show(250);
    });

	// Save Options by hitting Enterw
	$(document).on( 'keydown', '#bf_options_form :input:not(textarea)', function (e){
		if(e.keyCode == 13){
			$('.bf-save-button:first').trigger('click');
			return false;
		}
	});

//
    $('.bf-import-file-input').fileupload({
        limitMultiFileUploads: 1,
        url: bf.bf_ajax_url,
        autoUpload: false,
//        dataType: 'json',
        formData: {
            nonce  : bf.nonce,
			action : 'bf_ajax',
			type   : bf.type,
			reqID  : 'import'
        },
        add: function (e, data) {
            bf_import_submit = function () {
                return data.submit();
            };
        },
        fail: function (e, data) {
            alert("failed!!");
        },
        start: function (e) {
            alert("start");
        },
        done: function (e, data) {
            alert( data.result );
			setTimeout( function(){

				location.reload();

			}, 1000);
        },
        progressall: function (e, data) {
            var progress = parseInt( data.loaded / data.total * 100, 10);
        },
        drop: function (e, data) {
            return false;
        }
    });
    
    
    $('.bf-import-upload-btn').click( function(){
		bf_import_submit();
        alert('asd');
		return false;
    });







    /* Background Image Field*/


});
// shortcut for console.log
function hlog(log){
    console.log(log);
}