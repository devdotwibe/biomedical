@extends('layouts.appmasterspace')
<?php
$title       = 'Training Attended';
$description = 'Training Attended';
$keywords    = 'Training Attended';
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
                          <h2>Training Attended</h2>
                          <div class="add-skill"><a id="add-training"> Add Training Attended</a> 
                            <a  class="close_training" style="display: none;">Back to listing</a>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-top-section">
                             
                              <div class="add-detail"><a id="add-training"><img src="{{ asset('images/add-button.svg') }}" alt="addbtn"> Add Training Attended</a> </div>
                          </div>
                          
                          <div class="exp-list-wrap">
        
                           <!--  -->
                          
                           <div class="modal-content training_sec" style="display:none;">
         
                  <div class="modal-body" id="confirmMessage">
                  <form method="POST" action="" action="" name="saveTraining" id="saveTraining" class="saveTraining" autocomplete="off" >
                   @csrf
        
                   <div class="row">
                  
                        <div class="form-group col-md-6">
                          <label for="category_type_id">Product Category*</label>
                          <select name="category_type_id" id="category_type_id" class="form-control" onchange="change_prod_type_training()">
                            <option value="">-- Select Category --</option>
                            <?php
                            foreach($catgory_type as $item) {
                              $sel = (old('category_id') == $item->id) ? 'selected': '';
                                echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
            
                            } ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="training_product_id">Product Name*</label>
                          <select multiple="multiple" name="training_product_id[]" id="training_product_id" class="form-control">
                              <option value="">Select Product</option>
                        </select>
                        <span class="text-danger" id="training_product_id_message" style="display:none;">Field is required!</span>
                        </div>
                      
                    </div>
                   
        
                   <div class="row">
                      <div class="form-group col-md-6">
                        <label for="company_name">Training Type* </label>
                        <input type="text" id="training_type" name="training_type" value="{{ old('training_type')}}" class="form-control" placeholder="Training Type">
                        <input type="hidden" id="training_id" name="training_id" value="0" class="form-control" >
                      </div>
        
                      <div class="form-group col-md-6">
                        <label for="training_institution">Institution* </label>
                        <input type="text" id="training_institution" name="training_institution" value="{{ old('training_institution')}}" class="form-control" placeholder="Institution">
                      </div>
                   
                    </div>
        
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="date_training">Date of Training* </label>
                        <input type="text" id="date_training" name="date_training" value="{{ old('date_training')}}" class="form-control" placeholder="Date of Training">
                      </div>
        
                    </div>
        
                    <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="training_description">Description*</label>
                        <textarea id="training_description" name="training_description" value="{{ old('training_description')}}" class="form-control" placeholder="Description"></textarea>
                      </div>
                      </div>
        
                      <div class="row ">
                      <div class="form-group col-md-12">
                        <label for="name">Training certificate*</label>
                          </div>
                        <input type="hidden" id="training_current_image" name="training_current_image" value=""/>
                        <div class="file-input">
                        <input type="file" name="training_cert" id="training_cert" class="form-control" accept=".jpg, .png, .jpeg"/>
                        <label for="name">Choose file to Upload</label>
                          </div>
                        <span class="imagenote">Note:File format .jpg, .png, .jpeg<br>Maximum upload file size 2 MB</span>
                        <span class="text-danger" id="imagesize_training" style="display:none;">Please check image size</span>
                              <div class="loader_sec_training" style="display:none;">
                            <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                      <div id="imagesec_training">
                                                 
                      </div>
        
                      </div>
                     
        
                 
        
                      
                  </form> 
                </div>
                  
                  <div class="modal-footer">
                      <div class="training_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      <button type="button" class="btn btn-default close_training" >Cancel</button>
                      <button type="button" class="btn btn-default" id="save_training">Save</button>
                     
                  </div>
                </div>
          
                          <!--  -->
        
        
                          <div class="outer-table">
                   
                          <table class="table looptraining">
                            @if(count($marketspace_training)>0)
                              <thead class="thead-light">
                                  <tr>
                                     
                                    <th scope="col">Product</th>
                                      <th scope="col">Institution</th>
                                      
                                      <th scope="col">Action</th>
                                  </tr>
                              </thead>
                              @endif
                              <tbody id="ajax_res" class="t-center">
                              @if(count($marketspace_training)==0)
                              <tr> <td colspan="3" class="t-center">
                              No training attended information has been added.   </td>  </tr>
                              @endif
                              @if(count($marketspace_training)>0)
                                  @foreach($marketspace_training as $val)
                                   <tr>
                                      
                                      
                                      <td data-th="Name">
                                      @php
                                      $proarr=explode(',',$val->product_id);
                                      if(count($proarr)>0){
                                        foreach($proarr as $valpro)
                                        {
                                          $productsall =  DB::select("select * from products where `id`='".$valpro."' order by id desc");
                                          if($productsall){echo $productsall[0]->name;echo "<br>";}
                                        }
                                      }
                                      @endphp  
                                      
                                      
                                      </td>
                                      <td data-th="Institution">{{ $val->training_institution}}</td>
                                      <td  data-th="Action"> 
                                      <div class="training-edit" data-date_training="{{$val->date_training}}" data-category_type_id="{{$val->category_type_id}}" data-training_type="{{$val->training_type}}" data-training_institution="{{$val->training_institution}}" data-training_description="{{$val->training_description}}" data-training_cert="{{$val->training_cert}}"   data-id="{{$val->id}}"  data-product_id="{{$val->product_id}}">
                                          <a ><img src="{{ asset('images/edit-grey.svg') }}" alt="editbtn"></a></div>
                                          <a class="training-delete" data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
                                        </td>
                                  </tr>
                                  @endforeach
                                  @endif
                                 
                              </tbody>
                          </table>
        
                         
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
  $(document).ready(function() {
      $('#product_id').multiselect({
          enableCollapsibleOptGroups: true,
          buttonContainer: '<div id="competition_product" />'
      });
  
      $('#training_product_id').multiselect({
          enableCollapsibleOptGroups: true,
          buttonContainer: '<div id="competition_product" />'
      });
    
  });
  </script>
<script>
/******************************Training start*********************************** */

$( ".close_training" ).click(function() {
  $(".training_sec").hide();
  $(".looptraining").show();
  $( "#add-training" ).show();
  $(".close_training").hide();
});

$( "#add-training" ).click(function() {
  $( "#add-training" ).hide();
  $(".close_training").show();
  $(".training_sec").show();
  $(".looptraining").hide();
  $("#training_id").val(0);
  $("#category_type_id").val('');
  $("#training_type").val('');
  $("#training_institution").val('');
  $("#date_training").val('');
  
  $("#training_description").val('');
  $("#training_current_image").val('');
  $("#training_product_id").val('');
  $('#training_product_id').multiselect('rebuild');
  $("#training_cert").val('');
  

  $("#imagesec_exp").hide();
  $(".loader_sec_exp").hide();
});


$("#save_training").click(function() {
  var form = $("#saveTraining");
form.validate({
 rules: {
  category_type_id: {
        required:true,
     },
     training_type: {
        required:true,
     },
     training_institution: {
         required: true,
     },
     date_training: {
         required: true,
     },
     training_description: {
         required: true,
     },
  
 },
 messages: {
  category_type_id: {
         required:"Field is required!",
     },
     training_type: {
         required:"Field is required!",
     },
     training_institution: {
         required: "Field is required!",
     },
     date_training: {
         required: "Field is required!",
     },
     training_description: {
         required: "Field is required!",
     },

     
 }
}); 
if(form.valid() === true) {


    $("#training_product_id_message").hide();

  var product_id=$("#training_product_id").val();
  
  if(product_id!=null)
  {
  $(".training_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveTraining") }}',
    //dataType:'json',
    data: $("#saveTraining").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".training_gif").hide();
 
     
      $(".looptraining").html(data);
      $(".training_sec").hide();
  $(".looptraining").show();
      
    }, 
    error: function(data){
      console.log('error')
    }
    });
  }else{
    $("#training_product_id_message").show();
  }


}
});


$(".looptraining").on("click",".training-delete", function(){
  var id=$(this).attr("data-id");
  $(".modaldel").modal("show");
  $('#delete_data').attr('data-id', id);
});


  $('#delete_data').click(function(){
    var id=$(this).attr("data-id");
$.ajax({
    type:'POST',
    url:'{{ route("deleteTraining") }}',
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

$(".looptraining").on("click",".training-edit", function(){
  var id=$(this).attr("data-id");
  var price=$(this).attr("data-price");
  var brand_id=$(this).attr("data-brand_id");
  var category_type_id=$(this).attr("data-category_type_id");
  
  var product_id=$(this).attr("data-product_id");
  var training_type=$(this).attr("data-training_type");
  var training_institution=$(this).attr("data-training_institution");
  var date_training=$(this).attr("data-date_training");
  var training_description=$(this).attr("data-training_description");
  var training_cert=$(this).attr("data-training_cert");
  $("#category_type_id").val(category_type_id);
  change_prod_type_training(product_id)
  $("#training_id").val(id);
  $("#date_training").val(date_training);
  $("#training_type").val(training_type);
  $("#training_institution").val(training_institution);
  $("#training_product_id").val(product_id);
  $("#training_description").val(training_description);
  $("#training_current_image").val(training_cert);

  var paths="{{asset("storage/app/public/masterspace/")}}/"+training_cert;
  if(training_cert!='')
  {
    $("#imagesec_training").show();
    $("#imagesec_training").html('<img src="'+paths+'" id="category-img-tag"  width="100" height="100">');
  }

  //change_prod_type(product_id);
 
  $("#training_id").val(id);
  $(".training_sec").show();
  $(".looptraining").hide();
  $( "#add-training" ).hide();
  $(".close_training").show();
});


$("#training_cert").change(function () {
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
				 var fileName = $('#training_cert')[0].files[0].name;
				 var fileSize  = $('#training_cert')[0].files[0].size;
				 fileSize = fileSize / 1024;
         
				 $(".file-name").html(fileName);
				 if(fileSize > 2000){ 
					 $("#imagesize_training").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_sec_exp").show();
 var formData = new FormData();
   var url = '{{ route("exp_cert") }}';
   var image_name = $('#training_cert').val();
	if(image_name != '') {  
	  var file = document.getElementById('training_cert').files[0];
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
      $("#imagesec_training").show();
			 $("#imagesec_training").html('<img src="'+pro[0]+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#training_current_image").val(pro[1]);
			 $(".loader_sec_training").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });

     
function change_prod_type_training(product_type_id)
{
  
var APP_URL = {!! json_encode(url('/')) !!}
$("#product_id").val('');

$.ajax({
    url:'{{ route("searchproduct_typemarketspace") }}',
  headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
  type: 'post',
  //dataType: "json",
  data: {
    product_type_id:$("#category_type_id").val()
  },
  success: function( data ) {
    //alert( data.length );
    var proObj = JSON.parse(data);
    
    $("#product_id option:selected").removeAttr("selected");
      var  htmls=' ';
      htmls +="<option value=''>Select Product Type</option>";
    
      if(product_type_id==undefined)
      {
        
        var pro=[];
      }
      else{
       
        var pro=product_type_id.split(',');
      }
    
      
   
       for (var i = 0; i < proObj.length; i++) {
         
    
         /* if (jQuery.inArray("'"+proObj[i]["id"]+"'", pro) == -1) {
         
          var sel='';
      }
      else{
        var sel='selected';
      }*/
      var sel='';
      for (var d = 0; d < pro.length; d++) {
        
        if(pro[d]==proObj[i]["id"])
        {
        
         var sel='selected';
        }
      }


     
        htmls +="<option value='"+proObj[i]["id"]+"' "+sel+">"+proObj[i]["name"]+"</option>";
           }
   
           $("#training_product_id").html(htmls);
     
      $('#training_product_id').multiselect('rebuild');
   
      
  }
  });

}
/******************************Training end*********************************** */
  </script>

  
@endsection

<style>
.main-footer{display:none;}
</style>