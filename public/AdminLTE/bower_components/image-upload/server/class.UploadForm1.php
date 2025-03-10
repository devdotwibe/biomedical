<?php

/**
 * jQuery File Upload Plugin PHP Class 6.9.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 **/


class UploadForm
{
    protected $options;
	
	
    function __construct($initialize = true) {
        if ($initialize) {
            $this->initialize();
        }
    }

    protected function initialize() {
        //
    }
	
	//Multi form objects upload(can upload multiple/single files)
	public function createMultipleForm($form_number =1, $multiple = true, $progress= true, $start= true, $cancel = true, $delete = true)
	{
		/*
			form_number	:  required field(for creating seperate form object ids)
			multiple	:  enable/disable multiple file select (default :true)
			progress	:  show/hide global progress bar  (default: true)
			start		:  show/hide global start button  (default: true)
			cancel		:  show/hide global cancel button  (default: true)
			delete		:  show/hide global delete button  (default: true)
		*/
		
		?>
		
		 <!-- <div class="container">-->
		 <!-- The file upload form used as target for the file upload widget -->
   		 <!-- <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">-->
		 
    	<form class="fileupload" action="#" method="POST" enctype="multipart/form-data" data-upload-template-id="template-upload-<?php echo $form_number ?>"
    data-download-template-id="template-download-<?php echo $form_number ?>">
        
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
					
                    <?php echo ($multiple) ? '<span>Add files...</span>' : '<span>Add file</span>'; ?>
					
                    <input type="file" name="files[]" <?php if($multiple) { echo 'multiple'; } ?> id="up_file" />  <!-- accept="image/*" -->
                </span>
				
				<?php
				if($start) { ?>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
				
				<?php
				}
				
				if($cancel) { ?>
				
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
				<?php
				}
				
				if($delete) { ?>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
				<?php } ?>
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>
				
				<!--<input type="hidden" name="category_id" id="category_id" value="<?php echo $image_category_id ?>">-->

            </div>
			
			<?php 
			if($progress)
			{ ?>
				<!-- The global progress information -->
				<div class="col-lg-5 fileupload-progress fade">
					<!-- The global progress bar -->
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar progress-bar-success" style="width:0%;"></div>
					</div>
					<!-- The extended global progress information -->
					<div class="progress-extended">&nbsp;</div>
				</div>
			<?php 
			} ?>
			
       		</div>
		
			<!-- The table listing the files available for upload/download -->
			<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
		</form>
		
		<!--</div>-->

		<?php 
			//The template to display files available for upload
			$this->createUploadListing($form_number);
			
			//The template to display files available for download
			$this->createDownloadListing($delete, $form_number);		
	}
	
	//Single form  object upload(can upload multiple/single files)
	public function createSingleForm($multiple = true, $progress= true, $start= true, $cancel = true, $delete = true, $edit= 0)
	{
		/*
			multiple	:  enable/disable multiple file select (default :true)
			progress	:  show/hide global progress bar  (default: true)
			start		:  show/hide global start button  (default: true)
			cancel		:  show/hide global cancel button  (default: true)
			delete		:  show/hide global delete button  (default: true)
		
		*/
	
		//echo $edit;
		
		?>
		 <!-- <div class="container">-->
		 <!-- The file upload form used as target for the file upload widget -->
   		 <!-- <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">-->
		 
    	<form id="fileupload" action="#" method="POST" enctype="multipart/form-data">
        
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
					
                    <?php 
							echo ($multiple) ? '<span>Add files...</span>' : ( ($edit == 1) ? '<span>Edit file</span>' :'<span>Add file</span>'); 
							
							
				    ?>
					
                    <input type="file" name="files[]" <?php if($multiple) { echo 'multiple'; } ?>  id="up_file" />  <!-- accept="image/*" <?php if($edit == 1) { echo 'disabled="disabled"'; } ?> --> 
                </span>
				
                
				<?php
				if($start) { ?>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
				
				<?php
				}
				
				if($cancel) { ?>
				
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
				<?php
				}
				
				if($delete) { ?>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
				<?php } ?>
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>
				
				<!--<input type="hidden" name="property_id" id="property_id" value="<?php echo $property_id ?>">-->

            </div>
			
			<?php 
			if($progress)
			{ ?>
				<!-- The global progress information -->
				<div class="col-lg-5 fileupload-progress fade">
					<!-- The global progress bar -->
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar progress-bar-success" style="width:0%;"></div>
					</div>
					<!-- The extended global progress information -->
					<div class="progress-extended">&nbsp;</div>
				</div>
			<?php 
			} ?>
			
       		</div>
		
			<!-- The table listing the files available for upload/download -->
			<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
		</form>
		
		<!--</div>-->
		
		
		<?php 
			//The template to display files available for upload
			$this->createUploadListing();
			
			//The template to display files available for download
			$this->createDownloadListing($delete);
	}
	
	//The template to display files available for upload 
	public function createUploadListing($form_number = '')
	{
		/*
			form_number	: for template-download id(required only for multiform objects)
		*/
		
		?>
		<!-- The template to display files available for upload -->
		<script id="template-upload<?php echo ($form_number !='') ? '-'.$form_number : ''; ?>" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { 
			//alert(file.error);
		%}	
			<tr class="template-upload fade">
			
				<!-- <td class="title"><label>Title: <input name="title[]" class="required" value="" required="true"></label>  </td> -->
				
				<!--<input type="hidden" name="title[]" value="Title" required>-->
				 
				<td>
					<span class="preview"></span>
				</td>
				<td>
					<p class="name">{%=file.name%}</p>
					{% if (file.error) { %}
						<div><span class="label label-important">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				<td>
					<p class="size">{%=o.formatFileSize(file.size)%}</p>
					{% if (!o.files.error) { %}
						<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
					{% } %}
				</td>
				<td>
					{% if (!o.files.error && !i && !o.options.autoUpload) { %}
						<button class="btn btn-primary start">
							<i class="glyphicon glyphicon-upload"></i>
							<span>Start</span>
						</button>
					{% } %}
					{% if (!i) { %}
						<button class="btn btn-warning cancel">
							<i class="glyphicon glyphicon-ban-circle"></i>
							<span>Cancel</span>
						</button>
					{% } %}
				</td>
			</tr>
		{% } %}
		</script> 
	<?php
	
	}
	
	//The template to display files available for download
	public function createDownloadListing($delete = true, $form_number = '')
	{
		/*
			delete		: show/hide delete checkbox depending on global delete( show if it is true)
		    form_number	: for template-download id(required only for multiform objects)
		*/
		
		?>
		
		<!-- The template to display files available for download -->
		<script id="template-download<?php echo ($form_number !='') ? '-'.$form_number : ''; ?>" type="text/x-tmpl">
		{%
		 for (var i=0, file; file=o.files[i]; i++) { 
		 
		 //alert(file.name);
		%}
			<tr class="template-download fade">
				<td> <!--<input type="hidden" name="image_gallery_id[]" value="1" required>-->
					<span class="preview"> 
						{% if (file.thumbnailUrl) { %}
							<a href="{%=file.url%}" title="{%=(file.name).substring(15)%}" download="{%=file.name%}" data-gallery> <img src="{%=file.thumbnailUrl%}" /></a>	
						{% } %}
					</span>
				</td>
				<td>
					<p class="name">
						<a href="{%=file.url%}" title="{%=(file.name).substring(15)%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=(file.name).substring(15)%}</a>
					</p>
					{% if (file.error) { %}
						<div><span class="label label-important">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				<td>
					<span class="size">{%=o.formatFileSize(file.size)%}</span>
				</td>
				<td>
					<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
						<i class="glyphicon glyphicon-trash"></i>
						<span>Delete</span>
					</button>
					<?php if($delete) { ?>
						<input type="checkbox" name="delete" value="1" class="toggle">
					<?php } ?>
				</td>
			</tr>
		{% } %}
		</script>
		
		
	<?php
	
	}
	
	
	
	
}
