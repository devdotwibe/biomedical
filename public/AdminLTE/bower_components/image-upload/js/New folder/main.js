/*
 * jQuery File Upload Plugin JS Example 8.3.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, regexp: true */
/*global $, window, blueimp */

$(function () {
    'use strict';
	
    // Initialize the jQuery File Upload widget:
	
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        //url: 'server/php/',
		//url: 'admin/upload.php?folder=userfiles/my',
		url: 'upload.php?folder=userfiles/image_gallery',
		//formData: {example1: 'test'},
		
		disableImageResize: /Android(?!.*Chrome)|Opera/
        .test(window.navigator && navigator.userAgent),
    	//imageMaxWidth: 800,
    	//imageMaxHeight: 800,
    	imageCrop: false
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '../cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<span class="alert alert-error"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
		//var formData = $('form').serializeArray();
		
		/* $('#fileupload').bind('fileuploadsubmit', function (e, data) {												
			// The example input, doesn't have to be part of the upload form:
			var inputs = $('#example2');
			data.formData = {example: inputs.val()};
			alert(data.formData.example);
			if (!data.formData.example) {
			  input.focus();
			  return false;
			}
		});*/
		
		
		$('#fileupload').bind('fileuploadsubmit', function (e, data) {
															
			var input = $('#category_id');
			//data.formData = {example1: input.val()};
			
   			 var inputs = data.context.find(':input');
			//alert(inputs.length);
			 //alert($.trim(inputs.filter('.required').first().val()).length);
			 //alert(inputs.filter('.required1').first().val());
	 
		 
			//if (inputs.filter('[required][value=""]').first().focus().length) {
			if($.trim(inputs.filter('.required').first().val()).length == 0) {
				//return false;
			}
			
			data.formData = inputs.serializeArray();
			data.formData.push({name:'category_id', value:input.val()});
		});
		
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0],
        }).always(function () {
           $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, null, {result: result});
        });
    }

});
