@extends('layouts.appmasterspace')
<?php
$title       = 'Work Experience';
$description = 'Work Experience';
$keywords    = 'Work Experience';
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
                          <h2>Work Experience</h2>
                          <div class="add-skill"><a id="addexp"> Add Work Experience</a> 
                            <a  class="close_exp" style="display: none;">Back to listing</a>
                          </div>
                        </div>

                        {{-- ----------------------------- --}}
                        <div class="card">
                          <div class="card-top-section">
                              
                              <div class="add-detail"><a id="addexp"><img src="{{ asset('images/add-button.svg') }}" alt="addbtn"> Add Work Experience</a> </div>
                          </div>
                          
                          <div class="exp-list-wrap">
      
                           <!--  -->
                          
                           <div class="modal-content exp_sec" style="display:none;">
         
                  <div class="modal-body" id="confirmMessage">
                  <form method="POST" action="" action="" name="saveExperience" id="saveExperience" class="saveExperience" autocomplete="off" >
                   @csrf
                   <div class="row">
                      <div class="form-group col-md-6">
                        <label for="company_name">Company Name* </label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name')}}" class="form-control" placeholder="Company Name">
                        <input type="hidden" id="experience_id" name="experience_id" value="0" class="form-control" >
                      </div>
      
                      <div class="form-group col-md-6">
                        <label for="designation">Designation* </label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation')}}" class="form-control" placeholder="Designation">
                      </div>
                   
                    </div>
      
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="from_date">From Date* </label>
                        <input type="text" id="from_date" name="from_date" value="{{ old('from_date')}}" class="form-control" placeholder="From Date">
                        
                      </div>
      
                      <div class="form-group col-md-6">
                        <label for="to_date">To Date* </label>
                        <input type="text" id="to_date" name="to_date" value="{{ old('to_date')}}" class="form-control" placeholder="To Date">
                      </div>
                   
                    </div>
      
                    <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="responsibilities">Responsibilities*</label>
                        <textarea id="responsibilities" name="responsibilities" value="{{ old('responsibilities')}}" class="form-control" placeholder="Responsibilities"></textarea>
                      </div>
                      </div>
      
                      <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="name">Work Description*</label>
                        <textarea id="exp_det" name="exp_det" value="{{ old('exp_det')}}" class="form-control" placeholder="Work Description"></textarea>
                      </div>
                      </div>
      
                      <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="name">Experience certificate*</label>
                        <div class="file-input">
                          <input type="hidden" id="exp_current_image" name="exp_current_image" value=""/>
                          <input type="file" name="exp_cert" id="exp_cert" class="form-control" accept=".jpg, .png, .jpeg"/>
                          <label for="name">Choose file to Upload</label>
                        </div>
                        <span class="imagenote">Note:File format .jpg, .png, .jpeg<br>Maximum upload file size 2 MB</span>
                        <span class="text-danger" id="imagesize_exp" style="display:none;">Please check image size</span>
                                                  <div class="loader_sec_exp" style="display:none;">
                                                <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                                                  <div id="imagesec_exp">
                                                 
                      </div>
      
                      </div>
                      </div>
      
                      <div class="row">
                        <div class="form-check col-md-12 current-work">
                        <input class="form-check-input" type="checkbox" value="Y" id="current_work"  name="current_work" >
                        <label class="form-check-label" for="current_work">
                            I'm current working here
                        </label>
                        </div>
                        </div>
      
                      
                  </form> 
                  </div>
                  <div class="modal-footer">
                      <div class="exp_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      <button type="button" class="btn btn-default close_exp" >Cancel</button>
                      <button type="button" class="btn btn-default" id="save_exp">Save</button>
                     
                  </div>
              </div>
                          <!--  -->
      
      
                          <div class="loopexp">
                        
                          @if(count($marketspace_exp)>0)
                              @foreach($marketspace_exp as $val)
                            
                              <div class="exp-listing">
                                  <div class="exp-wrap">
                                      <div class="exp-name">{{$val->company_name}}</div>
                                      <div class="exp-date">{{$val->designation}} {{$val->from_date}} - {{$val->to_date}}</div>
                                  </div>
                                  <div class="exp-edit" data-id="{{$val->id}}" data-company_name="{{$val->company_name}}" data-designation="{{$val->designation}}" data-from_date="{{$val->from_date}}" data-to_date="{{$val->to_date}}" data-responsibilities="{{$val->responsibilities}}"  data-cert="{{$val->exp_cert}}" data-work="{{$val->current_work}}"   data-summary="{{$val->exp_det}}"><a ><img src="{{ asset('images/edit-grey.svg') }}" alt="editbtn"></a>
                                    
                                  </div>
                                  <a class="exp-delete" data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
                                 
                              </div>
                              @endforeach
                              @endif
      
                              @if(count($marketspace_exp)==0)
                              No Experience information has been added.
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

$(".loopexp").on("click",".exp-delete", function(){
  var id=$(this).attr("data-id");
  $(".modaldel").modal("show");
  $('#delete_data').attr('data-id', id);
});


  $('#delete_data').click(function(){
    var id=$(this).attr("data-id");
$.ajax({
    type:'POST',
    url:'{{ route("deleteExperience") }}',
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

/******************************Experiance start*********************************** */
$(".loopexp").on("click",".exp-edit", function(){
  var id=$(this).attr("data-id");
  var company_name=$(this).attr("data-company_name");
  var designation=$(this).attr("data-designation");
  var from_date=$(this).attr("data-from_date");
  var to_date=$(this).attr("data-to_date");
  var responsibilities=$(this).attr("data-responsibilities");
  var exp=$(this).attr("data-summary");
  var work=$(this).attr("data-work");
  var cert=$(this).attr("data-cert");
 
  $("#addexp").hide();
  $(".close_exp").show();
  
  $('#company_name').val(company_name).trigger('change');

  if(work=="Y")
  {
    $("#current_work").prop('checked', true);
  }else{
    $("#current_work").prop('checked', false);
  }
  
  $("#exp_current_image").val(cert);
  var paths="{{asset("storage/app/public/masterspace/")}}/"+cert;
  
  if(cert!='')
  {
    $("#imagesec_exp").show();
    $("#imagesec_exp").html('<img src="'+paths+'" id="category-img-tag"  width="100" height="100">');
  }
  

  $("#company_name").val(company_name);
  $("#designation").val(designation);
  $("#from_date").val(from_date);
  $("#to_date").val(to_date);



  $("#responsibilities").val(responsibilities);
  $("#exp_det").val(exp);
  
  $("#experience_id").val(id);
  $(".exp_sec").show();
  $(".loopexp").hide();
});

$( "#addexp" ).click(function() {
  $("#addexp").hide();
  $(".close_exp").show();
  $(".exp_sec").show();
  $(".loopexp").hide();
  $("#experience_id").val(0);
  $("#company_name").val('');
  $("#designation").val('');
  $("#from_date").val('');
  $("#to_date").val('');
  
  $("#responsibilities").val('');
  $("#experience").val('');
  $("#company").val('');
  $("#exp_det").val('');
  $("#current_work").prop('checked', false);

  $("#exp_cert").val('');
  $("#exp_current_image").val('');

  $("#imagesec_exp").hide();
  $(".loader_sec_exp").hide();
});


$( ".close_exp" ).click(function() {
  $(".exp_sec").hide();
  $(".loopexp").show();
  $("#addexp").show();
  $(".close_exp").hide();
});


$("#save_exp").click(function() {
  var form = $("#saveExperience");
form.validate({
 rules: {
  company_name: {
        required:true,
     },
     designation: {
        required:true,
     },
     from_date: {
         required: true,
     },
     to_date: {
         required: true,
     },
     responsibilities: {
         required: true,
     },
  
     exp_det: {
         required: true,
     },

    /* exp_cert: {
         required: true,
     },*/
     exp_cert:{
          required: function (element) {
             if($("#exp_current_image").val()!=''){
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
  company_name: {
         required:"Field is required!",
     },
     designation: {
         required:"Field is required!",
     },
     from_date: {
         required: "Field is required!",
     },
     to_date: {
         required: "Field is required!",
     },
     responsibilities: {
         required: "Field is required!",
     },
    
     exp_det: {
         required: "Field is required!",
     },
     
     exp_cert: {
         required: "Field is required!",
     },
     
 }
}); 
if(form.valid() === true) {
  $(".edu_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveExperience") }}',
    //dataType:'json',
    data: $("#saveExperience").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".edu_gif").hide();
      var response = JSON.parse(data);
      var html='';
      for (var i = 0; i < response.length; i++) {
         html +='<div class="exp-listing">'+
                            '<div class="exp-wrap">'+
                                '<div class="exp-name">'+response[i]["company_name"]+'</div>'+
                                
                                '<div class="exp-date">'+response[i]["designation"]+' '+response[i]["from_date"]+' - '+response[i]["to_date"]+' </div>'+
                            '</div>'+
                            '<div class="exp-edit" data-work="'+response[i]["current_work"]+'" data-summary="'+response[i]["exp_det"]+'" data-company_name="'+response[i]["company_name"]+'" data-designation="'+response[i]["designation"]+'" data-from_date="'+response[i]["from_date"]+'" data-cert="'+response[i]["exp_cert"]+'" data-to_date="'+response[i]["to_date"]+'"  data-responsibilities="'+response[i]["responsibilities"]+'"  data-summary="'+response[i]["exp_det"]+'"   data-id="'+response[i]["id"]+'" ><a ><img src="{{ asset("images/edit-grey.svg") }}" alt="editbtn"></a></div>'+
                            ' <a class="exp-delete" data-id="'+response[i]["id"]+'"><i class="fa fa-times"></i></a>'+
                             
                        '</div>';
        }
        
  

     
      $(".loopexp").html(html);
      $(".exp_sec").hide();
  $(".loopexp").show();
      
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
});


$("#exp_cert").change(function () {
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
				 var fileName = $('#exp_cert')[0].files[0].name;
				 var fileSize  = $('#exp_cert')[0].files[0].size;
				 fileSize = fileSize / 1024;
         
				 $(".file-name").html(fileName);
				 if(fileSize > 2000){ 
					 $("#imagesize_exp").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_sec_exp").show();
 var formData = new FormData();
   var url = '{{ route("exp_cert") }}';
   var image_name = $('#exp_cert').val();
	if(image_name != '') {  
	  var file = document.getElementById('exp_cert').files[0];
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
      $("#imagesec_exp").show();
			 $("#imagesec_exp").html('<img src="'+pro[0]+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#exp_current_image").val(pro[1]);
			 $(".loader_sec_exp").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });

     $('#from_date').datepicker({
    dateFormat:'yy-mm-dd',
changeMonth: true, 
    changeYear: true, maxDate: '0',
    yearRange: "-50:+00"
});
$('#to_date').datepicker({
    dateFormat:'yy-mm-dd',
changeMonth: true, 
    changeYear: true, maxDate: '0',
    yearRange: "-50:+00",
});
/******************************Experiance end*********************************** */
</script>

  
@endsection

<style>
.main-footer{display:none;}
</style>