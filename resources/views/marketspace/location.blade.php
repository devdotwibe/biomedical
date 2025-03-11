
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

 
                @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
					<h5>	Available Location</h5>
				</div>
				
                <form role="form" name="frm_location" id="frm_location" method="post" action="{{route('location_store')}}" enctype="multipart/form-data" >
               @csrf
					<div class="form">
					
                    <div class="row">
                    <div class="form-group  col-md-6">
                    <label for="state_id">State*</label>
                    <select id="state_id" name="state_id" class="form-control" onchange="change_state()">
                    <option value="">Select State</option>
                    
                    </select>
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
                        @if(count($data)>0)
						<table class="table loopskill">
							<thead class="thead-light">
								<tr>
								<th scope="col">State</th>
								<th scope="col">District</th>
								<th scope="col">Edit  </th>
								</tr>
							</thead>

							<tbody id="ajax-res" class="t-center">
                            @if(count($data)==0)
                                            <tr>
                                        <td>No Result</td></tr>
                                        @endif
                                        
                                @if(count($data)>0)
                                @foreach($data as $val)
								<tr>
									<td data-th="Date">{{$val->getstate->name}}</td>
									<td data-th="Start Time">{{$val->getdistrict->name}}</td>
									<td data-th="Edit"><a href="{{ route('location_edit',$val->id) }}">Edit</a> </td>
									
								</tr>
                                @endforeach
                                @endif
                                @if(count($data)>0)
                                {{ $data->links() }}
                                @endif

							</tbody>
						</table>
                        @endif
					</div>
					
   {{-- ----------------------------- --}}
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



change_country(101);

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
            states_val +='<option value="" selected>Select State</option>';
            for (var i = 0; i < proObj.length; i++) {
                //var stateid="{{$marketspace->state_id}}";
           
              states_val +='<option value="'+proObj[i]["id"]+'" >'+proObj[i]["name"]+'</option>';
           
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
            states_val +='<option value="" selected>Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
               
           
              states_val +='<option value="'+proObj[i]["id"]+'" >'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
             
              
           
          }
        });

  }
</script>
@endsection
<style>
.main-footer{display:none;}
</style>