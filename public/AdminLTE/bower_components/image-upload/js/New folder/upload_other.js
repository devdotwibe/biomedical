var scripts = document.getElementsByTagName("script"),
src = scripts[scripts.length-1].src;

var n=src.lastIndexOf("/"); 
var path	= src.substr(0,(n+1));

/*$.getScript(path+'jquery.fileupload.js');
$.getScript(path+'jquery.fileupload-ui.js');*/

//document.write("<script type='text/javascript' src='"+path+"jquery.min.js'><\/script>");

//The jQuery UI widget factory, can be omitted if jQuery UI is already included
document.write("<script type='text/javascript' src='"+path+"vendor/jquery.ui.widget.js'><\/script>"); 

//The Templates plugin is included to render the upload/download listings
document.write("<script type='text/javascript' src='"+path+"tmpl.min.js'><\/script>");

//The Load Image plugin is included for the preview images and image resizing functionality
document.write("<script type='text/javascript' src='"+path+"load-image.min.js'><\/script>");

//The Canvas to Blob plugin is included for image resizing functionality
document.write("<script type='text/javascript' src='"+path+"canvas-to-blob.min.js'><\/script>");

//Bootstrap JS is not required, but included for the responsive demo navigation 
//document.write("<script type='text/javascript' src='"+path+"bootstrap.min.js'><\/script>");
 
//Blueimp Gallery script
document.write("<script type='text/javascript' src='"+path+"jquery.blueimp-gallery.min.js'><\/script>");

// The Iframe Transport is required for browsers without support for XHR file uploads
document.write("<script type='text/javascript' src='"+path+"jquery.iframe-transport.js'><\/script>");

//The basic File Upload plugin 
document.write("<script type='text/javascript' src='"+path+"jquery.fileupload.js'><\/script>");

// The File Upload processing plugin
document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-process.js'><\/script>");

//The File Upload image preview & resize plugin
//document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-image.js'><\/script>");

//The File Upload audio preview plugin 
//document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-audio.js'><\/script>");

//The File Upload video preview plugin
//document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-video.js'><\/script>");

//The File Upload validation plugin 
document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-validate.js'><\/script>");

//The File Upload user interface plugin 
document.write("<script type='text/javascript' src='"+path+"jquery.fileupload-ui.js'><\/script>");

//For Single form image upload(multiple/single files)
function singleFormUpload()
{
	// Load existing files/uploaded files
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

//Multi form image upload(multi/single file)
function multiFormUpload()
{
	// Load existing files/uploaded files
	$('.fileupload').addClass('fileupload-processing');
		
	$('.fileupload').each(function () {
		$.ajax({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			url: $(this).fileupload('option', 'url'),
			dataType: 'json',
			context: $(this)[0],
		}).always(function () {
		   $(this).removeClass('fileupload-processing');
		}).done(function (result) {
			$(this).fileupload('option', 'done')
				.call(this, null, {result: result});
		});
	});
}
