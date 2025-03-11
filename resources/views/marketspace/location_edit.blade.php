@extends('layouts.appmasterspace')
<?php
$title       = 'Location';
$description = 'Location';
$keywords    = 'Location';
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

                        <h2>Available Location</h2>
                        <div class="add-skill">
                          <a  class="close_exp" href="{{ route('marketspace/location') }}" >Back to listing</a>
                        </div>
                    </div>
                <form role="form" name="frm_location" id="frm_location" method="post" action="{{route('available_location_update')}}" enctype="multipart/form-data" >
               @csrf
					<div class="form">
						<div class="row">
                        <div class="form-group  col-md-6">
                        <label for="state_id">State*</label>
                        <select id="state_id" name="state_id" class="form-control" onchange="change_state()">
                        <option value="">Select State</option>
                        
                        </select>
                        <input type="hidden" name="id" id="id" value="{{$data->id}}">
                        <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                        </div>

                        <div class="form-group  col-md-6">
                        <label for="district_id">District*</label>
                        <select id="district_id" name="district_id" class="form-control" >
                        <option value="">Select District</option>
                        
                        </select>
                        <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                        </div>
						</div>
					<div class="card-savebtn">
						<button type="button" class="btn btn-default" id="create-date">Submit</button>
					</div>

    </form>
					<div class="date-table outer-table">
					
					</div>
					
                      {{-- ----------------------------- --}}
                  
                    
                </section>
                <div class="right-side-bar">
                    @include('marketspace/right-sidebar')
                </div>
        </div>
    </div>



@endsection
@section('scripts')
<script>
   
    $("#create-date").click(function () {
 var form = $("#frm_location");
form.validate({
 rules: {
         
    state_id: {
        required:true,
     },
     district_id: {
        required:true,
     },
    
     
 },
 messages: {
    state_id: {
         required:"Field is required!",
     },
     district_id: {
         required:"Field is required!",
     },
   
 }
}); 
if(form.valid() === true) {
 
    $("#frm_location").submit();

}

}); 



function change_country(country_id){
  
  var url = '{{ route("change_country") }}';
  
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            country_id: country_id,
          },
          headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
            },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select State</option>';
            for (var i = 0; i < proObj.length; i++) {
                var stateid="{{$data->state_id}}";
             if(proObj[i]["id"]==stateid)
             {
                 var sel="selected";
             }
             else{
                var sel="";
             }
              states_val +='<option value="'+proObj[i]["id"]+'" '+sel+'>'+proObj[i]["name"]+'</option>';
           
              }
              $("#state_id").html(states_val);
             
          }
        });

  }

    function change_state(){
        var stateid="{{$marketspace->state_id}}";
        if(stateid>0)
        {
            var state_id=stateid;
        }
  else{
    var state_id=$("#state_id").val();
  }
 
  var url = '{{ route("change_state") }}';;
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
                var stateid="{{$data->district_id}}";
             if(proObj[i]["id"]==stateid)
             {
                 var sel="selected";
             }
             else{
                var sel="";
             }
              states_val +='<option value="'+proObj[i]["id"]+'" '+sel+'>'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
             
              
           
          }
        });

  }


  change_country(101);
  change_state();

</script>

@endsection
<style>
    .main-footer{display:none;}
    </style>