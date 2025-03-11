@extends('layouts.appmasterspace')
<?php
$title       = 'Skills';
$description = 'Skills';
$keywords    = 'Skills';
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
                          <h2>Skills</h2>
                          <div class="add-skill"><a id="addskill"> Add Skills</a> 
                            <a  class="close_skill" style="display: none;">Back to listing</a>
                          </div>
                        </div>
                        <div class="card">
                    <div class="card-top-section">
                     
                    </div>

                      <!--  -->
                    
                      <div class="modal-content skill_sec" style="display:none;">
        <div class="modal-header">
        <h5 class="modal-title">Skill</h5>
      
      </div>
            <div class="modal-body" id="confirmMessage">
            <form method="POST" action="" action="" name="saveSkill" id="saveSkill" class="saveSkill" autocomplete="off" >
             @csrf
            <div class="row">
            <div class="form-group col-md-6">
                  <label for="name">Brand*</label>
                  <select name="brand_id" id="brand_id" class="form-control">
                      <option value="">Select Brand</option>
                      @foreach ($brand as $brands)
                     <option value="{{$brands->id}}">{{$brands->name}}</option>
                        @endforeach
                </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Product Type*</label>
                  <select name="product_type_id" id="product_type_id" class="form-control" onchange="change_prod_type()">
                      <option value="">Select Product Type</option>
                      @foreach ($product_type as $product_type)
                      <option value="{{$product_type->id}}">{{$product_type->name}}</option>
                         @endforeach
                </select>
                </div>

              
            </div>
             
            <div class="row">
          
                <div class="form-group col-md-6">
                  <label for="name">Product Name*</label>
                  <select  name="product_id" id="product_id" class="form-control">
                      <option value="">Select Product</option>
                </select>
                <span class="text-danger" id="product_id_message" style="display:none;">Field is required!</span>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Price*</label>
                  <input type="text" id="price" name="price" value="{{ old('price')}}" class="form-control" placeholder="Price">
                  <input type="hidden" id="skill_id" name="skill_id" value="0" class="form-control" >
                </div>
                

            </div>

            <div class="row">
              <div class="form-group col-md-12">
                <label for="name">Proficiency*</label>
                <select  name="proficiency" id="proficiency" class="form-control">
                    <option value="">Select Proficiency</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
              </select>
              <span class="text-danger" id="product_id_message" style="display:none;">Field is required!</span>
              </div>

            </div>    
             
            <div class="row">
              <div class="form-group col-md-6">
                <label for="name">Orginal Company Training</label>
                <select  name="orginal_company_training" id="orginal_company_training" class="form-control">
                    <option value="">Select Orginal Company Training</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
              </select>
              <span class="text-danger" id="product_id_message" style="display:none;">Field is required!</span>
              </div>

              <div class="form-group col-md-6">
              <label for="name">Upload certificate</label>
              <div class="file-input">
                <input type="hidden" id="skill_current_image" name="skill_current_image" value=""/>
                <input type="file" name="skill_cert" id="skill_cert" class="form-control" accept=".jpg, .png, .jpeg"/>
                <label for="name">Choose file to Upload</label>
                </div>
                <span class="imagenote">Note:File format .jpg, .png, .jpeg<br>Maximum upload file size 2 MB</span>
                <span class="text-danger" id="imagesize_skill" style="display:none;">Please check image size</span>
                          <div class="loader_sec_skill" style="display:none;">
                        <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                          <div id="imagesec_skill"></div>

              </div>


            </div>  


            <div class="row">
              <div class="form-group col-md-6">
                <label for="name">Year of Experience</label>
                <input type="text"  name="year_of_exp" id="year_of_exp" class="form-control" placeholder="1 Month / 1 Year / 2 Year"> 
                   
              <span class="text-danger" id="year_of_exp_message" style="display:none;">Field is required!</span>
              </div>

              <div class="form-group col-md-6">
              <label for="name">Upload certificate</label>
              <div class="file-input">
                <input type="hidden" id="skillex_current_image" name="skillex_current_image" value=""/>
                <input type="file" name="skillex_cert" id="skillex_cert" class="form-control" accept=".jpg, .png, .jpeg"/>
                <label for="name">Choose file to Upload</label>
                </div>
                <span class="imagenote">Note:File format .jpg, .png, .jpeg<br>Maximum upload file size 2 MB</span>
                <span class="text-danger" id="imagesize_skillex" style="display:none;">Please check image size</span>
                          <div class="loader_sec_skillex" style="display:none;">
                        <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                          <div id="imagesec_skillex"></div>

              </div>


            </div>  

              
            </form>
            </div>
            <div class="modal-footer">
                <div class="skill_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" class="btn btn-default close_skill" >Cancel</button>
                <button type="button" class="btn btn-default" id="save_skill">Save</button>
               
            </div>
        </div>
        
                    <!--  -->
                    <div class="outer-table">
                    
                    <table class="table loopskill">
                      @if(count($marketspace_skill)>0)
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Brand</th>
                              
                                <th scope="col">Product Type</th>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        @endif
                        <tbody id="ajax_res" class="t-center">
                        @if(count($marketspace_skill)==0)
                        <tr> <td colspan="6" class="t-center">
                        No skills information has been added.   </td>  </tr>
                        @endif
                        @if(count($marketspace_skill)>0)
                            @foreach($marketspace_skill as $val)
                             <tr>
                                <td data-th="Brand">{{$val->brand->name}}</td>
                                <td data-th="Product Type">{{$val->product_type->name}}</td>
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
                                <td data-th="Price">{{number_format($val->price,2)}}</td>
                                <td  data-th="Action">
                                <div class="skill-edit" data-skillex_cert="{{$val->skillex_cert}}" data-skill_cert="{{$val->skill_cert}}" data-orginal_company_training="{{$val->orginal_company_training}}" data-year_of_exp="{{$val->year_of_exp}}" data-proficiency="{{$val->proficiency}}"  data-product_type_id="{{$val->product_type_id}}" data-id="{{$val->id}}" data-price="{{$val->price}}" data-brand_id="{{$val->brand_id}}" data-product_id="{{$val->product_id}}">
                                    <a ><img src="{{ asset('images/edit-grey.svg') }}" alt="editbtn"></a></div>
                                    <a class="skill-delete" data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
                                  </td>
                            </tr>
                            @endforeach
                            @endif
                           
                        </tbody>
                    </table>
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
    // $('#product_id').multiselect({
    //     enableCollapsibleOptGroups: true,
    //     buttonContainer: '<div id="competition_product" />'
    // });

    $('#training_product_id').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="competition_product" />'
    });
  
});
</script>

<script>

function change_prod_type(product_type_id)
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
    product_type_id:$("#product_type_id").val()
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
   
           $("#product_id").html(htmls);
     
      //$('#product_id').multiselect('rebuild');
   
      
  }
  });

}

$(".loopskill").on("click",".skill-delete", function(){
  var id=$(this).attr("data-id");
  $(".modaldel").modal("show");
  $('#delete_data').attr('data-id', id);
});


  $('#delete_data').click(function(){
    var id=$(this).attr("data-id");
$.ajax({
    type:'POST',
    url:'{{ route("deleteSkill") }}',
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

/******************************Skill start*********************************** */
$(".loopskill").on("click",".skill-edit", function(){
  var id=$(this).attr("data-id");
  var price=$(this).attr("data-price");
  var brand_id=$(this).attr("data-brand_id");
  var product_type_id=$(this).attr("data-product_type_id");
  
  var product_id=$(this).attr("data-product_id");
  var year_of_exp=$(this).attr("data-year_of_exp");
  var orginal_company_training=$(this).attr("data-orginal_company_training");
  var proficiency=$(this).attr("data-proficiency");
  var skill_cert=$(this).attr("data-skill_cert");
  var skillex_cert=$(this).attr("data-skillex_cert");
  
  $(".close_skill").show();
  $( "#addskill" ).hide();
  //$( "#rightside-menu" ).hide();
 
  $("#price").val(price);
  $("#brand_id").val(brand_id);
  $("#product_type_id").val(product_type_id);
  $("#product_id").val(product_id);
  $("#year_of_exp").val(year_of_exp);
  $("#orginal_company_training").val(orginal_company_training);
  $("#proficiency").val(proficiency);
  $("#skill_current_image").val(skill_cert);
  $("#skillex_current_image").val(skillex_cert);

  var paths="{{asset("storage/app/public/masterspace/")}}/"+skill_cert;
  if(skill_cert!='')
  {
    $("#imagesec_skill").show();
    $("#imagesec_skill").html('<img src="'+paths+'" id="category-img-tag"  width="100" height="100">');
  }

  var paths="{{asset("storage/app/public/masterspace/")}}/"+skillex_cert;
  if(skillex_cert!='')
  {
    $("#imagesec_skillex").show();
    $("#imagesec_skillex").html('<img src="'+paths+'" id="category-img-tag"  width="100" height="100">');
  }
  change_prod_type(product_id);
 
  $("#skill_id").val(id);
  $(".skill_sec").show();
  $(".loopskill").hide();
});

$( "#addskill" ).click(function() {
  $(".skill_sec").show();
  $(".loopskill").hide();
  $("#skill_id").val(0);
  $("#price").val('');
  $("#product_id").val('');
  $("#brand_id").val('');
  $("#product_type_id").val('');
  $("#year_of_exp").val('');
  $("#orginal_company_training").val('');
  $("#proficiency").val('');
  $("#skill_cert").val('');
  $("#skillex_cert").val('');
  $(".close_skill").show();
  $( "#addskill" ).hide();
  //$( "#rightside-menu" ).hide();
  
});
$( ".close_skill" ).click(function() {
  $(".skill_sec").hide();
  $(".loopskill").show();
  $(".close_skill").hide();
  $( "#addskill" ).show();
  //$( "#rightside-menu" ).show();
});

$("#save_skill").click(function() {
  var product_id=$("#product_id").val();
  if(product_id=='' || product_id==null)
  {$("#product_id_message").show();
  }else{
    $("#product_id_message").hide();
  }
  var form = $("#saveSkill");
form.validate({
 rules: {
     price: {
        required:true,
     },
     brand_id: {
         required: true,
     },
     product_type_id: {
         required: true,
     },
     proficiency: {
         required: true,
     },
     
     
     
 },
 messages: {
    price: {
         required:"Field is required!",
     },
     brand_id: {
         required: "Field is required!",
     },
     product_type_id: {
         required: "Field is required!",
     },
     proficiency: {
         required: "Field is required!",
     },
    
 }
}); 
if(form.valid() === true) {
  var product_id=$("#product_id").val();
  
  if(product_id!=null)
  {
    $("#product_id_message").hide();
  $(".skill_gif").show();



	$.ajax({
    type:'POST',
    url:'{{ route("saveSkill") }}',
    //dataType:'json',
    data: $("#saveSkill").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".skill_gif").hide();
     
      $("#ajax_res").html(data);
      $(".skill_sec").hide();
  $(".loopskill").show();
      
    }, 
    error: function(data){
      console.log('error')
    }
    });
  }else{
    $("#product_id_message").show();
  }


}
});



     
$("#skill_cert").change(function () {
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
				 var fileName = $('#skill_cert')[0].files[0].name;
				 var fileSize  = $('#skill_cert')[0].files[0].size;
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
   var image_name = $('#skill_cert').val();
	if(image_name != '') {  
	  var file = document.getElementById('skill_cert').files[0];
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
      $("#imagesec_skill").show();
			 $("#imagesec_skill").html('<img src="'+pro[0]+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#skill_current_image").val(pro[1]);
			 $(".loader_sec_skill").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });


     
$("#skillex_cert").change(function () {
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
				 var fileName = $('#skillex_cert')[0].files[0].name;
				 var fileSize  = $('#skillex_cert')[0].files[0].size;
				 fileSize = fileSize / 1024;
         
				 $(".file-name").html(fileName);
				 if(fileSize > 2000){ 
					 $("#imagesize_skillex").show();
				 }
				//  else if( width < 640 && height < 480 ) {
				// 	 $("#imageWH").show();
				//  }
				 else{
					
					 $(".loader_sec_skillex").show();
 var formData = new FormData();
   var url = '{{ route("exp_cert") }}';
   var image_name = $('#skillex_cert').val();
	if(image_name != '') {  
	  var file = document.getElementById('skillex_cert').files[0];
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
      $("#imagesec_skillex").show();
			 $("#imagesec_skillex").html('<img src="'+pro[0]+'" id="category-img-tag"  width="100" height="100">');
			 
			 $("#skillex_current_image").val(pro[1]);
			 $(".loader_sec_skillex").hide();
		
		 }
	 });
				 }
			 }
		 }
		 });
     
     
/******************************Skill end*********************************** */
  </script>

  
@endsection

<style>
.main-footer{display:none;}
</style>