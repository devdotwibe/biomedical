@extends('layouts.appmasterspace')
<?php
$title       = 'Education';
$description = 'Education';
$keywords    = 'Education';
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
                          <h2>Education Qualification</h2>
                          <div class="add-skill"><a id="addedu"> Add Education</a> 
                            <a  class="close_edu" style="display: none;">Back to listing</a>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-top-section">
                            
                              <div class="add-detail"><a id="addedu"><img src="{{ asset('images/add-button.svg') }}" alt="addbtn"> Add Education Qualification</a> </div>
                          </div>
                          <div class="edu-list-wrap">
      
                           <!--  -->
                          
                           <div class="modal-content edu_sec" style="display:none;">
              <div class="modal-header">
              <h5 class="modal-title">Education Qualification</h5>
              <button type="button" class="close_edu" >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
                  <div class="modal-body" id="confirmMessage">
                  <form method="POST" action="" action="" name="saveEducation" id="saveEducation" class="saveEducation" autocomplete="off" >
                   @csrf
                   <div class="row">
                      <div class="form-group col-md-6">
                        <label for="name">Course Name*</label>
                        <input type="text" id="education" name="education" value="{{ old('education')}}" class="form-control" placeholder="Course Name">
                        <input type="hidden" id="education_id" name="education_id" value="0" class="form-control" placeholder="Education">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="name">Institution*</label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution')}}" class="form-control" placeholder="Institution">
                        
                      </div>
                    </div>
      
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="name">Percentage of Mark*</label>
                        <input type="text" id="percentage_mark" name="percentage_mark" value="{{ old('percentage_mark')}}" class="form-control" placeholder="Percentage of Mark">
                        
                      </div>
      
                      <div class="form-group col-md-6">
                        <label for="name">Year of Passing*</label>
                        
                        <select id="education_to_year" name="education_to_year"  class="form-control" >
                        <option  value='' selected>Select Year</option>
                        @php
                        $currentYear = date('Y');
                        foreach (range(1950, $currentYear) as $value) {
                            echo "<option >" . $value . "</option > ";
      
                        }
                        @endphp
                        </select>
                      </div>
      
                      
                    </div>
      
      
                      <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="name">Education Certificate*</label>
                        <div class="file-input">
                          <input type="hidden" id="edu_current_image" name="edu_current_image" value=""/>
                          <input type="file" name="edu_cert" id="edu_cert" class="form-control" accept=".jpg, .png, .jpeg"/>
                          <label for="name">Choose file to Upload</label>
                        </div>
                        <span class="imagenote">Note:File format .jpg, .png, .jpeg<br>Maximum upload file size 2 MB</span>
                        <span class="text-danger" id="imagesize_edu" style="display:none;">Please check image size</span>
                                                  <div class="loader_sec_edu" style="display:none;">
                                                <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                                                  <div id="imagesec_edu">
                                                 
                      </div>
      
                      </div>
                      </div>
      
      
                      
      
      
                  </form>
                  </div>
                  <div class="modal-footer">
                      <div class="edu_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      <button type="button" class="btn btn-default close_edu" >Cancel</button>
                      <button type="button" class="btn btn-default" id="save_edu">Save</button>
                     
                  </div>
              </div>
                          <!--  -->
      
                          <div class="loopedu">
                          @if(count($marketspace_edu)>0)
                              @foreach($marketspace_edu as $val)
                              <div class="edu-listing">
                                  <div class="edu-wrap">
                                      <div class="edu-name">{{$val->education}}</div>
                                      <div class="edu-date">{{$val->institution}} Percentage of Mark-{{$val->percentage_mark}} Year- {{$val->education_to_year}} </div>
                                  </div>
                                  
                                  <div class="edu-edit" data-cert="{{$val->edu_cert}}" data-id="{{$val->id}}"  data-institution="{{$val->institution}}" data-percentage_mark="{{$val->percentage_mark}}" data-endyear="{{$val->education_to_year}}" data-edu="{{$val->education}}"><a ><img src="{{ asset('images/edit-grey.svg') }}" alt="editbtn"></a>
                                  </div>
                                  <a class="edu-delete" data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
                              </div>
                              @endforeach
                              @endif
      
                              @if(count($marketspace_edu)==0)
                              No education qualification information has been added.
                              @endif
                            </div>
                            
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

  
$(".loopedu").on("click",".edu-delete", function(){
  var id=$(this).attr("data-id");
  $(".modaldel").modal("show");
  $('#delete_data').attr('data-id', id);
});


  $('#delete_data').click(function(){
    var id=$(this).attr("data-id");
$.ajax({
    type:'POST',
    url:'{{ route("deleteEducation") }}',
    //dataType:'json',
    data:'id='+ id,
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
    location.reload();
    }
  });

});


 /******************************Education start*********************************** */
 $(".loopedu").on("click",".edu-edit", function(){
  var id=$(this).attr("data-id");
  var institution=$(this).attr("data-institution");
  var percentage_mark=$(this).attr("data-percentage_mark");
  var endyear=$(this).attr("data-endyear");
  var quali=$(this).attr("data-edu");

  var cert=$(this).attr("data-cert");

  $("#edu_current_image").val(cert);
  var paths="{{asset("storage/app/public/masterspace/")}}/"+cert;
  if(cert!='')
  {
    $("#imagesec_edu").show();
    $("#imagesec_edu").html('<img src="'+paths+'" id="category-img-tag"  width="100" height="100">');
  }

  $("#institution").val(institution);
  $("#percentage_mark").val(percentage_mark);
  $("#education_to_year").val(endyear);
  $("#education").val(quali);

  $("#education_id").val(id);
  $(".edu_sec").show();
  $(".loopedu").hide();
  $("#addedu").hide();
  $(".close_edu").show();
});

$( "#addedu" ).click(function() {
  $(".edu_sec").show();
  $(".loopedu").hide();
  $("#education_id").val(0);
  $("#percentage_mark").val('');
  $("#education_to_date").val('');
  $("#education").val('');
  $("#percentage_mark").val('');
  $("#education_to_year").val('');
  $("#addedu").hide();
  $(".close_edu").show();
  $(".loader_sec_edu").hide();
  $("#imagesec_edu").hide();
  
  $("#edu_cert").val('');
  $("#edu_current_image").val('');
});
$( ".close_edu" ).click(function() {
  $(".edu_sec").hide();
  $(".loopedu").show();
  $("#addedu").show();
  $(".close_edu").hide();
});


$("#save_edu").click(function() {
  var form = $("#saveEducation");
form.validate({
 rules: {
  education: {
        required:true,
     },
     institution: {
         required: true,
     },
     percentage_mark: {
         required: true,
     },
     education_to_year: {
         required: true,
     },
    
     edu_cert:{
          required: function (element) {
             if($("#edu_current_image").val()!=''){
              return false;                           
             }
             else
             {
                 return true;
             }  
          }  
       }
     
 },
 messages: {
  education: {
         required:"Field is required!",
     },
     institution: {
         required: "Field is required!",
     },
     percentage_mark: {
         required: "Field is required!",
     },
     education_to_year: {
         required: "Field is required!",
     },
     edu_cert: {
         required: "Field is required!",
     },
 }
}); 
if(form.valid() === true) {
  $(".edu_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveEducation") }}',
    //dataType:'json',
    data: $("#saveEducation").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".edu_gif").hide();
      var response = JSON.parse(data);
      var html='';
      for (var i = 0; i < response.length; i++) {
         html +='<div class="edu-listing">'+
                            '<div class="edu-wrap">'+
                                '<div class="edu-name">'+response[i]["education"]+'</div>'+
                                '<div class="edu-date">'+response[i]["institution"]+' Percentage of Mark-'+response[i]["percentage_mark"]+' Year- '+response[i]["education_to_year"]+' </div>'+
                                
                            '</div>'+
                            '<div class="edu-edit" data-cert="'+response[i]["edu_cert"]+'"  data-institution="'+response[i]["institution"]+'"  data-percentage_mark="'+response[i]["percentage_mark"]+'" data-endyear="'+response[i]["education_to_year"]+'" data-id="'+response[i]["id"]+'"  data-edu="'+response[i]["education"]+'"><a ><img src="{{ asset("images/edit-grey.svg") }}" alt="editbtn"></a></div>'+
                            '<a class="edu-delete" data-id="'+response[i]["id"]+'"><i class="fa fa-times"></i></a>'+
                        '</div>';
        }
     
      $(".loopedu").html(html);
      $(".edu_sec").hide();
  $(".loopedu").show();
      
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
});


$("#edu_cert").change(function () {
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
				 var fileName = $('#edu_cert')[0].files[0].name;
				 var fileSize  = $('#edu_cert')[0].files[0].size;
				 fileSize = fileSize / 1024;
				 $(".file-name").html(fileName);
				 if(fileSize > 2000){ 
					 $("#imagesize_edu").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_sec_edu").show();
 var formData = new FormData();
   var url = '{{ route("edu_cert") }}';
   var image_name = $('#edu_cert').val();
	if(image_name != '') {  
	  var file = document.getElementById('edu_cert').files[0];
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
      var pro=data.split('||');
      
      $("#imagesec_edu").show();
			 $("#imagesec_edu").html('<img src="'+pro[0]+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#edu_current_image").val(pro[1]);
			 $(".loader_sec_edu").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });
/******************************Education end*********************************** */
  </script>

  
@endsection

<style>
.main-footer{display:none;}
</style>