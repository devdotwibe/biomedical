@extends('layouts.appmasterspace')
<?php
$title       = 'Dashboard';
$description = 'Dashboard';
$keywords    = 'Dashboard';
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
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="{{ asset('images/logo.png') }}"></a>
            <div class="collapse navbar-collapse" id="user-img">
                    
              @include('marketspace/navbar')
                  </div>
        </nav>
    </header>

    <div class="container-fluid height100">
        <div class="row dashboard-row">
            @include('marketspace/sidebar')
            <main class="col-md-10" id="main">
                <section class="content-wrap">
                    <div class="content-col1">
                        <h2>Welcome</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend, faucibus erat non, finibus ex. 
                            Nam posuere mi eu massa pharetra gravida. Aenean pharetra euismod quam, eu mattis magna aliquet et. 
                            Fusce accumsan nec justo sed facilisis. Aliquam quis ullamcorper enim. Integer eleifend eros eu lorem suscipit lobortis.</p>
                    </div>
                    <div class="content-col2">
                        <div class="accordion" id="faq">
                            <div class="card">
                                <div class="card-header" id="faqhead1">
                                    <a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faq1"
                                    aria-expanded="true" aria-controls="faq1">What level of Ambulance Cover do I need?</a>
                                </div>
        
                                <div id="faq1" class="collapse show" aria-labelledby="faqhead1" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead2">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq2"
                                    aria-expanded="true" aria-controls="faq2">Once I join, when do I need to pay?</a>
                                </div>
        
                                <div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead3">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq3"
                                    aria-expanded="true" aria-controls="faq3">When do the benefits of Ambulance cover commence once I join?</a>
                                </div>
        
                                <div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead4">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq4"
                                    aria-expanded="true" aria-controls="faq4">What level of Ambulance cover do I need </a>
                                </div>
        
                                <div id="faq4" class="collapse" aria-labelledby="faqhead4" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead5">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq5"
                                    aria-expanded="true" aria-controls="faq5">Once I join, when do I need to pay?</a>
                                </div>
        
                                <div id="faq5" class="collapse" aria-labelledby="faqhead5" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead6">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq6"
                                    aria-expanded="true" aria-controls="faq6">When do the benefits of Ambulance cover commence once I join?</a>
                                </div>
        
                                <div id="faq6" class="collapse" aria-labelledby="faqhead6" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="faqhead7">
                                    <a href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq7"
                                    aria-expanded="true" aria-controls="faq7">Once I join, when do I need to pay?</a>
                                </div>
        
                                <div id="faq7" class="collapse" aria-labelledby="faqhead7" data-parent="#faq">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris laoreet mi id nulla ullamcorper, 
                            eget elementum nisl aliquet. Orci varius natoque penatibus et magnis dis parturient montes, 
                            nascetur ridiculus mus. Quisque vel dui eleifend,
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="right-side-bar">
                   
                   @include('marketspace/right-sidebar')
                </div>
        </div>
    </div>

@endsection
@section('scripts')
<script>
	$("#image").change(function () {
        
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
				 var fileName = $('#image')[0].files[0].name;
				 var fileSize  = $('#image')[0].files[0].size;
				 fileSize = fileSize / 1024;
				// $(".file-name").html(fileName);
				 if(fileSize > 2001){ 
					 $("#imagesize").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					 $("#imageWH").hide();
					 $("#imagesize").hide();
					 $(".loader_sec").show();
 var formData = new FormData();
   var url = '{{ route("partOneSaveImage") }}';
   var image_name = $('#image').val();
	if(image_name != '') {  
	  var file = document.getElementById('image').files[0];
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
            $("#image").val('');
			 $("#image_message").hide();
			 $("#imagesec").html('<img src="'+data+'" id="category-img-tag">');
			 $("#headeruserimg").html('<img src="'+data+'" id="category-img-tag">');
			 $("#image_name").val(data);
			 $(".loader_sec").hide();
			 $(".del_btn").show();
		 }
	 });
				 }
			 }
		 }
		 });
    </script>

@endsection
<style>
.main-footer{display:none;}
</style>
