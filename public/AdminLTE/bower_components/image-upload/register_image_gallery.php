<?php
/*********************************************************************************************
Author 	: SUMITH
Date	: 28-August-2013
Purpose	: Register image gallery
*********************************************************************************************/

ob_start();
session_start();
include_once("../includes/config.php");

set_time_limit(0);

// Check the admin user is loged in or not
if (!isset($_SESSION[ADMIN_ID]))
{
	functions::redirect("login.php");
	exit;
}

$image_category_id	= (isset($_REQUEST['image_category_id']) &&  $_REQUEST['image_category_id']) > 0 ? $_REQUEST['image_category_id'] : 0;
$image_category 	= new image_category($image_category_id);
$image_category_id	= $image_category->image_category_id;

if($image_category_id == 0)
{
	functions::redirect("manage_image_category.php");
	exit;
}

$_SESSION['category_id']	= $image_category_id;


$page_title = 'Add Image';


$default_page_title		= 'Manage Images';
$default_page_uri		= 'manage_image_gallery.php';
// Cancel button action starts here
if(isset($_POST['cancel']))	
{
	functions::redirect($default_page_uri . "?image_category_id=" . $image_category_id);
}

// Set template details
$template 				= new template();
$template->type			= 'ADMIN';
$template->left_menu	= true;
$template->admin_id		= $_SESSION[ADMIN_ID];
//$template->title		= $page_title;
$template->js			= '';
$template->heading();

?>

<link rel="stylesheet" href="<?php echo URI_LIBRARY ?>image-upload/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo URI_LIBRARY ?>image-upload/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="<?php echo URI_LIBRARY ?>image-upload/css/jquery.fileupload-ui.css">
<script src="<?php echo URI_LIBRARY ?>jquery/jquery-min.js"> </script>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="whiteBox" >
  <tr>
    <td width="30" align="right" valign="bottom" class="cornerTopLeft"><img src="images/content-box-top-left.png" alt="box corner" width="30" height="30" /></td>
    <td class="topRepeat">&nbsp;</td>
    <td width="30" align="left" valign="bottom" class="cornerTopRight"><img src="images/content-box-top-right.png" alt="box corner" width="30" height="30" /></td>
  </tr>
  <tr>
    <td rowspan="2" class="leftRepeat">&nbsp;</td>
    <td bgcolor="#FFFFFF"><div class="contentHeader">
        <div class="pageTitle">
          <?php echo functions::deformat_string($page_title); ?>
        </div>
        <div class="contentSublinks txtBold"> <img src="images/manage-image.png" alt="<?php echo functions::deformat_string($default_page_title); ?>" title="<?php echo functions::deformat_string($default_page_title); ?>" width="24" height="24" class="imgBlock" /> <a href="<?php echo $default_page_uri . "?image_category_id=" . $image_category_id; ?>"><?php echo functions::deformat_string($default_page_title); ?></a> </div>
      </div>
      <?php if(!empty($image_gallery->message)) { ?>
      <span class="<?php echo $image_gallery->warning ? 'warningMesg' : 'infoMesg'; ?>  formPageWidth"> <?php echo $image_gallery->message; ?> </span>
      <?php } ?>
      <div class="spacer"></div></td>
    <td rowspan="2" class="rightRepeat">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" style="overflow:visible">
	
	 <?php
	 	  //Initialize the upload form and listings
	 	  $uploadForm		= new UploadForm(); 		//library class
	      $uploadForm->createSingleForm(true, true, true, true, true);  //Single form upload(can upload multiple/single files)
	 ?>
	
	
	</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="cornerBottomLeft"><img src="images/content-box-bottom-left.png" alt="box corner" width="30" height="30" /></td>
    <td class="bottomRepeat">&nbsp;</td>
    <td align="left" valign="top" class="cornerBottomRight"><img src="images/content-box-bottom-right.png" alt="box corner" width="30" height="30" /></td>
  </tr>
</table>
<?php 
	$template->footer();
?>

<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <!--<a class="prev">‹</a>-->
	<a class="prev">&lsaquo;</a>
   <!-- <a class="next">›</a>-->
    <a class="next">&rsaquo;</a>
    <!--<a class="close">×</a>-->
	<a class="close">&times</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>


<!--   Required jquery-min.js, can be omitted if jQuery-min.js is already included  -->
<!--    includes required js for  upload and gallery/listing  -->

<script src="<?php echo URI_LIBRARY ?>image-upload/js/upload_image.js"> </script>

<script>


$(function () {
    'use strict';
	
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        //url: 'server/php/',
		url: 'upload_image.php?folder=image_gallery',		
		maxNumberOfFiles:1000,
		
		disableImageResize: /Android(?!.*Chrome)|Opera/
        .test(window.navigator && navigator.userAgent),
    	/*imageMaxWidth: 800,
    	imageMaxHeight: 600,
		maxHeight: 800,
		maxWidth: 600,
    	imageCrop: false,*/
		previewMaxWidth:80,
		previewMaxHeight:80
    });
	
	$('.fileupload').each(function () {
		$(this).fileupload({
			dropZone: $(this),
			url: 'upload_image.php?folder=image_gallery',		
			maxNumberOfFiles:1000,
			
		
		});
	});
	
	
		
	$('#fileupload').bind('fileuploadsubmit', function (e, data) {												
		var input = $('#category_id');
    	var inputs = data.context.find(':input');
		
		//if (inputs.filter('[required][value=""]').first().focus().length) {
		if($.trim(inputs.filter('.required').first().val()).length == 0) {
			//return false;
		}
		
		data.formData = inputs.serializeArray();
		data.formData.push({name:'category_id', value:input.val()});
	});
		
	// Load existing files/uploaded files
	singleFormUpload();
	//multipleFormUpload();

});



</script>
