@extends('layouts.appmasterspace')
<?php
$title       = 'Edit Profile';
$description = 'Edit Profile';
$keywords    = 'Edit Profile';
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
                      <h2>Edit Profile</h2>
                      
                    </div>

                    <div class="card">
                      <div class="card-top-section">


                      
                <div class="col-md-12">
                    <div class="alert alert-success" id="success_editprof" style="display:none;">
                        Successfully updated
                    </div>
                    <form autocomplete="off" method="POST" action="" action="" name="saveProfile" id="saveProfile" class="saveProfile" autocomplete="off" >
                        @csrf
                        <div class="row">
                             <div class="form-group col-md-6">
                            <label for="name">Head of the Institution*</label>
                            <input type="text" id="name" name="name" value="{{ $marketspace->name}}" class="form-control" placeholder="Head of the Institution">
                             </div>

                            <div class="form-group col-md-6">
                            <label for="last_name">Business  Name*</label>
                            <input type="text" id="business_name" name="business_name" value="{{ $marketspace->business_name}}" class="form-control" placeholder="Business Name">
                            </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                               <label for="name">Email*</label>
                               <input type="text" id="email" name="email" value="{{ $marketspace->email}}" class="form-control" placeholder="Email">
                                </div>

                               <div class="form-group col-md-6">
                               <label for="last_name">Phone*</label>
                               <input type="text" id="phone" name="phone" value="{{ $marketspace->phone}}" class="form-control" placeholder="Phone">
                               </div>
                             </div>

                            <div class="row">
                            <div class="form-group col-md-12 formTitle">
                            Address </div>

                            <div class="form-group col-md-12">
                            <label for="address1">Address line 1*</label>
                            <textarea  id="address1" name="address1"  class="form-control" placeholder="Address line 1">{{ $marketspace->address1}}</textarea>
                            </div>
                          
                            </div>
                            <div class="row">
                            <div class="form-group  col-md-3">
                            <label for="country_id">Country*</label>
                            <select id="country_id" name="country_id" class="form-control" onchange="change_country()">
                            <option value="">Select Country</option>
                            @foreach($country as $values)
                            <?php
                            $sel = ($values->id == "101") ? 'selected': '';
                            echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                            ?>
                            @endforeach
                            </select>
                            <span class="error_message" id="country_id_message" style="display: none">Field is required</span>
                            </div>

                            <div class="form-group  col-md-3">
                            <label for="state_id">State*</label>
                            <select id="state_id" name="state_id" class="form-control" onchange="change_state()">
                            <option value="">Select State</option>
                            
                            </select>
                            <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                            </div>

                            <div class="form-group  col-md-3">
                            <label for="district_id">District*</label>
                            <select id="district_id" name="district_id" class="form-control" onchange="change_district()">
                            <option value="">Select District</option>
                            
                            </select>
                            <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                            </div>

                            <div class="form-group  col-md-3">
                            <label for="taluk_id">Taluk*</label>
                            <select id="taluk_id" name="taluk_id" class="form-control" >
                            <option value="">Select Taluk</option>
                            
                            </select>
                            <span class="error_message" id="taluk_id_message" style="display: none">Field is required</span>
                            </div>

                            </div>

                            <div class="row">

                           

                            <div class="form-group col-md-6">
                            <label for="zip">Zip*</label>
                            <input type="text" id="zip" name="zip" value="{{ $marketspace->zip}}" class="form-control" placeholder="Zip">
                            </div>

                            <div class="form-group col-md-6">
                            <label for="gst">GST</label>
                            <input type="text" id="gst" name="gst" value="{{ $marketspace->gst}}" class="form-control" placeholder="GST">
                            <input type="hidden" id="user_id" name="user_id" value="{{ $marketspace->user_id}}" class="form-control" >
                            </div>

                            </div>

                           

                            <div class="card-savebtn">
                            <button type="button" class="btn btn-default" id="save_prof">Save</button>
                            </div>
                            
                        </form>
                </div>
          
            </div>
        </div>
                {{--  --}}


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


$("#selectOption").change(function() {

var selectbox = document.getElementById("selectOption");
    var selectedValue = selectbox.options[selectbox.selectedIndex].value;
    window.location.href = selectedValue;
});
    
$("#save_prof").click(function() {
  var form = $("#saveProfile");
form.validate({
    invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {                    
                var firstInvalidElement = $(validator.errorList[0].element);
                $('html,body').scrollTop(firstInvalidElement.offset().top);
                firstInvalidElement.focus();
            }
        },
 rules: {
  name: {
        required:true,
     },
     business_name: {
    required:true,
    },
    email: {
    required:true,
    },
    phone: {
    required:true,
    },
address1: {
    required:true,
    },
    country_id: {
        required:true,
     },
  state_id: {
    required:true,
    },
    district_id: {
    required:true,
    },
    taluk_id: {
    required:true,
    },
    zip: {
        required:true,
     },
  
     
 },
 messages: {
    name: {
         required:"Field is required!",
     },
     business_name: {
         required:"Field is required!",
     },
     email: {
         required:"Field is required!",
     },
     phone: {
         required:"Field is required!",
     },
     address1: {
         required:"Field is required!",
     },
     country_id: {
         required:"Field is required!",
     },
   
     state_id: {
         required:"Field is required!",
     },
     district_id: {
         required:"Field is required!",
     },
     taluk_id: {
         required:"Field is required!",
     },
     zip: {
         required:"Field is required!",
     },
   
   
   
 }
}); 
if(form.valid() === true) {
  $(".about_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveEditprofile") }}',
    //dataType:'json',
    data: $("#saveProfile").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
        $("#success_editprof").show();
        $(window).scrollTop( $("#myTabContent").offset().top );
     //location.reload();
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
});

function change_country(){
  var country_id=$("#country_id").val();
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
                var stateid="{{$marketspace->state_id}}";
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

    function change_state(state_id){
     
        if(state_id>0)
        {
            var state_id=state_id;
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
                var stateid="{{$marketspace->district_id}}";
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


  
  function change_district(district_id){
  
  
        if(district_id>0)
        {
            var district_id=district_id;
        }
  else{
    var district_id=$("#district_id").val();
  }

  var url = '{{ route("change_district") }}';;
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
          data:{
            district_id: district_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Taluk</option>';
            for (var i = 0; i < proObj.length; i++) {
             
                var taluk_id="{{$marketspace->taluk_id}}";
               
             if(proObj[i]["id"]==taluk_id)
             {
                 var sel="selected";
             }
             else{
                var sel="";
             }

              states_val +='<option value="'+proObj[i]["id"]+'" '+sel+'>'+proObj[i]["name"]+'</option>';
           
              }
              $("#taluk_id").html(states_val);
             
              
           
          }
        });

  }


  change_country();
  change_state('{{$marketspace->state_id}}');
  change_district('{{$marketspace->district_id}}');
</script>



<script>

$(function() {
  $('#user_id').selectpicker();
});

</script>

@endsection
<style>
    .main-footer{display:none;}
    </style>