@extends('layouts.appmasterspace')
<?php
$title       = 'Reference';
$description = 'Reference';
$keywords    = 'Reference';
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
                          <h2>Reference</h2>
                          <div class="add-skill"><a id="add-reference"> Add Reference</a> 
                            <a  class="close_reference" style="display: none;">Back to listing</a>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-top-section">
                              
                              <div class="add-detail"><a id="add-reference"><img src="{{ asset('images/add-button.svg') }}" alt="addbtn"> Add Reference</a> </div>
                          </div>
                          
                          <div class="exp-list-wrap">
        
                           <!--  -->
                          
                           <div class="modal-content reference_sec" style="display:none;">
         
                  <div class="modal-body" id="confirmMessage">
                  <form method="POST" action="" action="" name="saveReference" id="saveReference" class="saveReference" autocomplete="off" >
                   @csrf
        
                   
        
                   <div class="row">
                      <div class="form-group col-md-6">
                        <label for="name_of_person">Name of person* </label>
                        <input type="text" id="name_of_person" name="name_of_person" value="{{ old('name_of_person')}}" class="form-control" placeholder="Name of person">
                        <input type="hidden" id="reference_id" name="reference_id" value="0" class="form-control" >
                      </div>
        
                      <div class="form-group col-md-6">
                        <label for="organisation">Organisation* </label>
                        <input type="text" id="organisation" name="organisation" value="{{ old('organisation')}}" class="form-control" placeholder="Organisation">
                      </div>
                   
                    </div>
        
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="refer_designation">Designation* </label>
                        <input type="text" id="refer_designation" name="refer_designation" value="{{ old('refer_designation')}}" class="form-control" placeholder="Designation">
                        
                      </div>
                      <div class="form-group col-md-6">
                        <label for="refer_email">Email* </label>
                        <input type="text" id="refer_email" name="refer_email" value="{{ old('refer_email')}}" class="form-control" placeholder="Email">
                        
                      </div>
        
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="refer_contact">Contact no* </label>
                        <input type="text" id="refer_contact" name="refer_contact" value="{{ old('refer_contact')}}" class="form-control" placeholder="Contact no">
                        
                      </div>
                   
        
                    </div>
                 
        
                      
                  </form> 
                  </div>
                  <div class="modal-footer">
                      <div class="reference_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      <button type="button" class="btn btn-default close_reference" >Cancel</button>
                      <button type="button" class="btn btn-default" id="save_reference">Save</button>
                     
                  </div>
              </div>
                          <!--  -->
        
        
                          <div class="outer-table">
                   
                          <table class="table loopreference">
                            @if(count($marketspace_reference)>0)
                              <thead class="thead-light">
                                  <tr>
                                    <th scope="col">Name of person</th>
                                      <th scope="col">Email</th>
                                      <th scope="col">Contact no</th>
                                      <th scope="col">Action</th>
                                  </tr>
                              </thead>
                              @endif
                              <tbody id="ajax_res" class="t-center">
                              @if(count($marketspace_reference)==0)
                              <tr> <td colspan="3" class="t-center">
                              No reference has been added.   </td>  </tr>
                              @endif
                              @if(count($marketspace_reference)>0)
                                  @foreach($marketspace_reference as $val)
                                   <tr>
                                      <td data-th="Name of person">{{ $val->name_of_person}}</td>
                                      <td data-th="Email">{{ $val->refer_email}}</td>
                                      <td data-th="Contact no">{{ $val->refer_contact}}</td>
                                      <td  data-th="Action"> 
                                      <div class="reference-edit" data-name_of_person="{{$val->name_of_person}}" data-organisation="{{$val->organisation}}" data-refer_designation="{{$val->refer_designation}}" data-refer_email="{{$val->refer_email}}" data-refer_contact="{{$val->refer_contact}}"    data-id="{{$val->id}}"  >
                                          <a ><img src="{{ asset('images/edit-grey.svg') }}" alt="editbtn"></a></div>
                                          <a class="reference-delete" data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
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

/******************************Reference start*********************************** */

$( ".close_reference" ).click(function() {
  $(".reference_sec").hide();
  $(".loopreference").show();
  $( "#add-reference" ).show();
  $(".close_reference").hide();
});

$( "#add-reference" ).click(function() {
  $( "#add-reference" ).hide();
  $(".close_reference").show();
  $(".reference_sec").show();
  $(".loopreference").hide();
  $("#reference_id").val(0);
  $("#name_of_person").val('');
  $("#organisation").val('');
  $("#refer_designation").val('');
  $("#refer_email").val('');
  
  $("#refer_contact").val('');
 
  $("#imagesec_reference").hide();
  $(".loader_sec_reference").hide();
});


$("#save_reference").click(function() {
  var form = $("#saveReference");
form.validate({
 rules: {
  name_of_person: {
        required:true,
     },
     organisation: {
        required:true,
     },
     refer_designation: {
         required: true,
     },
     refer_email: {
         required: true,
     },
     refer_contact: {
         required: true,
     },
  
 },
 messages: {
  name_of_person: {
         required:"Field is required!",
     },
     organisation: {
         required:"Field is required!",
     },
     refer_designation: {
         required: "Field is required!",
     },
     refer_email: {
         required: "Field is required!",
     },
     refer_contact: {
         required: "Field is required!",
     },
    
     
 }
}); 
if(form.valid() === true) {

  $(".reference_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveReference") }}',
    //dataType:'json',
    data: $("#saveReference").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
      $(".reference_gif").hide();
 
     
      $(".loopreference").html(data);
      $(".reference_sec").hide();
  $(".loopreference").show();
      
    }, 
    error: function(data){
      console.log('error')
    }
    });



}
});


$(".loopreference").on("click",".reference-delete", function(){
  var id=$(this).attr("data-id");
  $(".modaldel").modal("show");
  $('#delete_data').attr('data-id', id);
});


  $('#delete_data').click(function(){
    var id=$(this).attr("data-id");
$.ajax({
    type:'POST',
    url:'{{ route("deleteReference") }}',
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

$(".loopreference").on("click",".reference-edit", function(){
  var id=$(this).attr("data-id");
  var name_of_person=$(this).attr("data-name_of_person");
  var organisation=$(this).attr("data-organisation");
  var refer_designation=$(this).attr("data-refer_designation");
  
  var refer_email=$(this).attr("data-refer_email");
  var refer_contact=$(this).attr("data-refer_contact");
  $( "#add-reference" ).hide();
  $(".close_reference").show();
  $("#reference_id").val(id);
  $("#name_of_person").val(name_of_person);
  $("#organisation").val(organisation);
  $("#refer_designation").val(refer_designation);
  $("#refer_email").val(refer_email);
  $("#refer_contact").val(refer_contact);
 
  //change_prod_type(product_id);
 
  $("#reference_id").val(id);
  $(".reference_sec").show();
  $(".loopreference").hide();
});
/******************************Reference end*********************************** */

  </script>

  
@endsection

<style>
.main-footer{display:none;}
</style>