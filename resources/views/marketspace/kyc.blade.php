@extends('layouts.appmasterspace')
<?php
$title       = 'KYC';
$description = 'KYC';
$keywords    = 'KYC';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
        <!-- <i class="fa fa-bars mobile-menu"></i> -->
        <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
        <div class="collapse navbar-collapse" id="user-img">
            @include('marketspace/navbar')
              </div>
    </nav>
</header>

<div class="container-fluid height100">
    <div class="row dashboard-row leftnone">

        <main class="col-md-10" id="main">
            <section class="content-wrap bg-none">
                <div class="content-col1">
                <div class="content-col1-head">
                      <h2>KYC</h2>
                     
                    </div>

                    <div class="card">
                      <div class="card-top-section">
                                    
<form role="form" name="frm_kyc" id="frm_kyc" method="post" action="{{route('kyc_store')}}" enctype="multipart/form-data" >
               @csrf
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="pannumber">PAN Number*</label>
                                                <input type="text" name="pan_no" id="pan_no" class="form-control" placeholder="PAN Number" value="{{$marketspace->pan_no}}">
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                            <div class='file-input'>
                                            <input type="hidden" id="pan_current_image" name="adhar_current_image" value="{{$marketspace->pan_image}}"/>
                                                <input type='file' name="pan_image" id="pan_image" value="{{$marketspace->pan_image}}" accept=".jpg, .png, .jpeg">
                                                <span class='button'>Choose</span>
                                             
                                              
                                                <span class='label' data-js-label>No file selected</label>
                                              </div>
                                              <span class="text-danger" id="imagesize" style="display:none;">Please check image size</span>
                                          
                                                <div class="loader_secpan" style="display:none;">
			                                    <img src="{{ asset('images/wait.gif') }}" alt=""/></div>

                                              <div id="imagesec">
                    @if($marketspace->pan_image!='')
					<img src="{{ asset('storage/app/public/masterspace/'.$marketspace->pan_image) }}" id="category-img-tag"  width="100" height="100"/>
                    {{-- <a id="remove_pan_img">Remove</a> --}}
					@endif
					
                    </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="pannumber">Adhar Number*</label>
                                                <input type="text" name="adhar_no"  id="adhar_no" class="form-control" placeholder="Adhar Number" value="{{$marketspace->adhar_no}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                            <div class="file-input">
                                            <input type="hidden" id="adhar_current_image" name="adhar_current_image" value="{{$marketspace->adhar_image}}"/>
                                                <input type='file' name="adhar_image" id="adhar_image" value="{{$marketspace->adhar_image}}" accept=".jpg, .png, .jpeg"> 
                                                <span class='button'>Choose</span>
                                                <span class='label' data-js-label>No file selected</label>
                                            </div>
                                            <span class="text-danger" id="imagesize_adhar" style="display:none;">Please check image size</span>
                                            <div class="loader_sec_adhar" style="display:none;">
			                                    <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                                            <div id="imagesec_adhar">
                                            @if($marketspace->adhar_image!='')
                                            <img src="{{ asset('storage/app/public/masterspace/'.$marketspace->adhar_image) }}" id="category-img-tag"  width="100" height="100"/>
                                            {{-- <a id="remove_adhar_img">Remove</a> --}}
                                            @endif
                                          
                                            </div>

                                              
                                            </div>
                                        </div>
                                      
                                       
                                        <div class="card-savebtn">
                                            <button type="button" class="btn btn-default" id="kyc_create">Submit</button>
                                            </div>
                                    </form>
                              
                                </div>
                            </div>
                              {{-- ----------------------------- --}}
                          </div>
                          
                      </section>
                      <div class="right-side-bar">
                          @include('marketspace/right-sidebar')
                      </div>
              </div>
          </div>

          <div class="modal fade modaldel" id="modaldel">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to delete this row?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="delete_data" data-id="" data-href="">Delete</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

@endsection
@section('scripts')
<script>
      $("#remove_adhar_img").click(function () {
        $("#modaldel").modal("show");
        $('#delete_data').attr('data-id','adhar');
      });
      $("#remove_pan_img").click(function () {
        $("#modaldel").modal("show");
        $('#delete_data').attr('data-id','pan');
      });
      
     $(".closeib").click(function () {
location.href="{{ route('marketspace/iblist') }}";
     });
    $("#kyc_create").click(function () {
 var form = $("#frm_kyc");
form.validate({
 rules: {
         
    pan_no: {
        required:true,
     },
     adhar_no: {
        required:true,
     },
     adhar_current_image: {
        required:true,
     },
     pan_current_image: {
        required:true,
     },
    
 },
 messages: {
    pan_no: {
         required:"Field is required!",
     },
     adhar_no: {
         required:"Field is required!",
     },
     adhar_current_image: {
         required:"Field is required!",
     },
     pan_current_image: {
         required:"Field is required!",
     },

 }
}); 
if(form.valid() === true) {
 
    $("#frm_kyc").submit();

}

}); 


$("#pan_image").change(function () {
			 if ($(this).val()) {
                 
					 //var fileInput = $(this).find("input[type=file]")[0],
				 //file = fileInput.files && fileInput.files[0];
				 var file = this.files[0], img;
				 //alert(file);
				 var img = new Image();
		 img.src = window.URL.createObjectURL( file );
		 img.onload = function() {
			 var width = img.naturalWidth,
				 height = img.naturalHeight;
			 window.URL.revokeObjectURL( img.src );
			 //alert(width+'````'+height);
				 var fileName = $('#pan_image')[0].files[0].name;
				 var fileSize  = $('#pan_image')[0].files[0].size;
				 fileSize = fileSize / 1024;
				 $(".file-name").html(fileName);
				 if(fileSize > 512000){ 
					 $("#imagesize").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_secpan").show();
 var formData = new FormData();
   var url = '{{ route("pan_image") }}';
   var image_name = $('#pan_image').val();
	if(image_name != '') {  
	  var file = document.getElementById('pan_image').files[0];
	   formData.append('image',file);  
	   }
	   $.ajax({
	   type: "POST",
	   cache: false,
	   processData: false,
	   contentType: false,
	   url: url,
	   headers: {
			 'X-CSRF-Token': '{{ csrf_token() }}',
		 },
	   data:formData,
		 success: function (data)
		 {
           
         
			 $("#imagesec").html('<img src="'+data+'" id="category-img-tag" width="100" height="100">');
			 
			 $("#image_name").val(data);
			 $(".loader_secpan").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });




         $("#adhar_image").change(function () {
			 if ($(this).val()) {
                 
					 //var fileInput = $(this).find("input[type=file]")[0],
				 //file = fileInput.files && fileInput.files[0];
				 var file = this.files[0], img;
				 //alert(file);
				 var img = new Image();
		 img.src = window.URL.createObjectURL( file );
		 img.onload = function() {
			 var width = img.naturalWidth,
				 height = img.naturalHeight;
			 window.URL.revokeObjectURL( img.src );
			 //alert(width+'````'+height);
				 var fileName = $('#adhar_image')[0].files[0].name;
				 var fileSize  = $('#adhar_image')[0].files[0].size;
				 fileSize = fileSize / 1024;
				 $(".file-name").html(fileName);
				 if(fileSize > 512000){ 
					 $("#imagesize_adhar").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_sec_adhar").show();
 var formData = new FormData();
   var url = '{{ route("adhar_image") }}';
   var image_name = $('#adhar_image').val();
	if(image_name != '') {  
	  var file = document.getElementById('adhar_image').files[0];
	   formData.append('image',file);  
	   }
	   $.ajax({
	   type: "POST",
	   cache: false,
	   processData: false,
	   contentType: false,
	   url: url,
	   headers: {
			 'X-CSRF-Token': '{{ csrf_token() }}',
		 },
	   data:formData,
		 success: function (data)
		 {
           
         
			 $("#imagesec_adhar").html('<img src="'+data+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#image_name").val(data);
			 $(".loader_sec_adhar").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#installation_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                    changeYear: true,
                    yearRange: "1990:2040",
                   // minDate: 0  
                });
        $('#warrenty_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            changeYear: true,
            yearRange: "1990:2040",
            //minDate: 0  
        });
    </script>

@endsection

<style>
    .main-footer{display:none;}
    </style>