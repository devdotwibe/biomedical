@extends('layouts.appmasterspace')
<?php
$title       = 'Edit Profile';
$description = 'Edit Profile';
$keywords    = 'Edit Profile';
$message="";
if(count($contact_person)>0)
{
$title=$contact_person[0]->title;
$name=$contact_person[0]->name;
$last_name=$contact_person[0]->last_name;
$designation=$contact_person[0]->designation;
$department=$contact_person[0]->department;
$email=$contact_person[0]->email;
$remark=$contact_person[0]->remark;
}
if(count($contact_person)==0)
{
    $title='';
    $name='';
    $last_name='';
    $designation='';
    $department='';
    $email='';
    $remark='';
}


?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')

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
                        


                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-card">
                           
                                <div class="tab-card-form">
                                <div class="alert alert-success" id="success_editprof" style="display:none;">
                                    Successfully updated
                                </div>
                                <form autocomplete="off" method="POST" action="" action="" name="saveProfile" id="saveProfile" class="saveProfile" autocomplete="off" >
                                    @csrf
                                    <div class="row">

                                    <div class="form-group col-md-12">
                                    <label for="name">Hospital Name*</label>
                                    @if($marketspace->contact_person_id==NULL && $marketspace->user_id>0)
                                    @endif
                                    @if($marketspace->user_type=="Hire")
                                    <a href="{{ route('marketspace/hospitalcreate') }}">Edit Hospital</a>
                                    @else     <a href="{{ route('marketspace/servicestaffprofile') }}">Edit Profile</a> @endif
                          

                                    @if($marketspace->contact_person_id==NULL && $marketspace->user_id==NULL)
                                    <a href="{{ route('marketspace/hospitalcreate') }}">If your hospital not add, Click here to add</a>
                                    @endif
                                    <select class="form-control" id="user_id" name="user_id" data-live-search="true" data-live-search-style="startsWith">
                                    <option  value="">Select Hospital</option>   
                                    @if(count($user))
                                    @foreach($user as $val)
                                    <option value="{{$val->id}}" attr-addr="{{$val->address1}}" @if($marketspace->user_id==$val->id) selected @endif>{{$val->business_name}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                                    </div>
                                    </div>

                                    <div class="row hos_contactsec"   @if($marketspace->contact_person_id==NULL) style="display:none;" @endif>
                                    <div class="form-group col-md-6 hos_name" >

                                    </div>

                                    <div class="form-group col-md-6">
                                        @if($title=='')
                                    <a id="viewcontact_form">Add as a contact under this hospital</a>
                                    @endif
                                    <input type="hidden" name="add_contact" id="add_contact" value="0">
                                    <input type="hidden" name="contact_person_id" id="contact_person_id" value="{{$marketspace->contact_person_id}}">
                                    </div>
                                    </div>


       <div class="contact_form"  @if($marketspace->contact_person_id==NULL) style="display:none;" @endif>
       <h3>Contact Person</h3>
                                    <div class="row">
                                       
      <div class="form-group  col-md-6">
          <label for="name">Title*</label>
          <select  id="title" name="title" class="form-control" >
          <option value="">Select Title</option>
          <option value="Mr." @if($title=="Mr.") selected @endif>Mr.</option>
          <option value="Ms." @if($title=="Ms.") selected @endif>Ms.</option>
          <option value="Dr." @if($title=="Dr.") selected @endif>Dr.</option>
          <option value="Fr." @if($title=="Fr.") selected @endif>Fr.</option>
          <option value="Sr." @if($title=="Sr.") selected @endif>Sr.</option>

          </select>
          <span class="error_message" id="title_message" style="display: none;color:red;">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">First Name*</label>
          <input type="text" id="name" name="name" class="form-control" value="{{$name}}" placeholder="Name">
          <span class="error_message" id="name_message" style="display: none;color:red;">Field is required</span>
        </div>
      </div>
      

        
        <div class="row">
        <div class="form-group  col-md-6">
          <label for="name">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $last_name }}" placeholder="Last Name">
          <span class="error_message" id="last_name_message" style="display: none;color:red;">Field is required</span>
        </div>

         <div class="form-group  col-md-6">
          <label for="name">Designation*</label>
         
          <select id="designation" name="designation" class="form-control" >
              <option value="">Select Designation</option>      
              @foreach ($hosdesignation as $desigval)
              <option value="{{$desigval->id}}" @if($designation==$desigval->id) selected @endif>{{$desigval->name}}</option> 
              @endforeach
          </select>

          <span class="error_message" id="designation_message" style="display: none;color:red;">Field is required</span>
        </div>
        </div>

        <div class="row">
        <div class="form-group  col-md-6">
          <label for="name">Department*</label>
          <select id="department" name="department" class="form-control" >
              <option value="">Select Department</option>      
              @foreach ($hosdeparment as $hosdepart)
              <option value="{{$hosdepart->id}}" @if($department==$hosdepart->id) selected @endif>{{$hosdepart->name}}</option> 
              @endforeach
          </select>
          <span class="error_message" id="department_message" style="display: none;color:red;">Field is required</span>
        </div>

             
        <div class="form-group  col-md-6" >
          <label for="name">Email*</label>
          <input type="text" id="email" name="email" class="form-control" value="{{ $email}}" placeholder="Email">
          <span class="error_message" id="email_message" style="display: none;color:red;">Field is required</span>
          <span class="error_message" id="email_invalid_message" style="display: none;color:red;">Invalid email address</span>
        </div>
        
          <div class="form-group  col-md-12">
          <label for="name">Remark</label>
          <textarea  id="remark" name="remark" class="form-control" placeholder="Remark">{{ $remark}}</textarea>
          <span class="error_message" id="remark_message" style="display: none;color:red;">Field is required</span>
        </div>
        
        

      </div>

                                    <!-- /********************** */ -->
</div>

                                       
                                        <div class="card-savebtn">
                                        <button type="button" class="btn btn-default" id="save_prof">Save</button>
                                        
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                      
                      
                       
                        
                      
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
var contact_person_id="{{$marketspace->contact_person_id}}";
if(contact_person_id>0)
{
    var name=$('#user_id option:selected'). text();
    if(name!='')
    {
        $(".hos_contactsec").show();
        $(".hos_name").html(name);
    }else{
        $(".hos_contactsec").hide();
        $(".hos_name").html('');
    }
}


function isValidEmail(email)
{
    return /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(email)
        && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(email);
}

$("#selectOption").change(function() {

var selectbox = document.getElementById("selectOption");
    var selectedValue = selectbox.options[selectbox.selectedIndex].value;
    window.location.href = selectedValue;
});

$("#user_id").change(function() {
    var name=$('#user_id option:selected'). text();
    var address=$('option:selected', this).attr('attr-addr');
    if(name!='')
    {
        $(".hos_contactsec").show();
        $(".hos_name").html(name+'<br>'+address);
    }else{
        $(".hos_contactsec").hide();
        $(".hos_name").html('');
    }

});

$("#viewcontact_form").click(function() {
$(".contact_form").show();
$("#add_contact").val(1);

});

$("#save_prof").click(function() {
  
var user_id=$("#user_id").val();
var add_contact=$("#add_contact").val();
if(user_id=='')
{
    $("#user_id_message").show();
}
else{
    $("#user_id_message").hide();
}
var flag=0;
if(add_contact==1)
{
    var title=$("#title").val();
var name=$("#name").val();
var last_name=$("#last_name").val();
var designation=$("#designation").val();
var department=$("#department").val();
var email=$("#email").val();

    if(title=='')
        {
            $("#title_message").show();
            flag=1;
        }
        else{
            $("#title_message").hide();
        }
    if(name=='')
    {
        $("#name_message").show();
        flag=1;
    }
    else{
        $("#name_message").hide();
    }
    
    if(last_name=='')
    {
        $("#last_name_message").show();
        flag=1;
    }
    else{
        $("#last_name_message").hide();
    }
    
    if(designation=='')
    {
        $("#designation_message").show();
        flag=1;
    }
    else{
        $("#designation_message").hide();
    }
    if(department=='')
    {
        $("#department_message").show();
        flag=1;
    }
    else{
        $("#department_message").hide();
    }
    if(email=='')
    {
        $("#email_message").show();
        flag=1;
    }
    else{
        if(!isValidEmail(email))
        {
        $("#email_invalid_message").show();
        flag=1;
        }
        else{
            $("#email_invalid_message").hide();
        }
        $("#email_message").hide();
    }
    
    
    
}
else{
    var flag=0;
}
if(user_id>0 && flag==0) {
    

  $(".about_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("savehospital") }}',
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


  change_country();
  change_state();
</script>



<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
function matchStart(params, data) {
    params.term = params.term || '';
    if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
        return data;
    }
    return false;
}
$(function() {
  //$('#user_id').selectpicker();
  $('#user_id').select2({
    matcher: function(params, data) {
        return matchStart(params, data);
    },
});
  
});


</script>
<style>
    .main-footer{display:none;}
    </style>
@endsection
