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
<script src="{{ asset('js/custom.js') }}"></script>


<section class="form-navbar">
<div class="container">
<div class="row viewbtn">
            <div class="col">
                <div class="viewclientbtn">
                    <a href="{{ route('marketspace/editprofile') }}">View Client Profile</a>
                </div>
            </div>
            <div class="col">
              <div class="userdropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @if($marketspace){{ $marketspace->name }}@endif <span class="caret"><i class="fa fa-caret-down"></i></span>
                                </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                   
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    Edit Profile
                                    </a>

                                    <a class="dropdown-item" href="{{ route('marketspace/editprofile') }}">
                                    Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/mywork') }}">
                                    My Work
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/logout') }}" >
                                        {{ __('Logout') }}
                                    </a>
                                </div>
              </div>
            </div>
        </div>
</div>
</section>


<section class="form-edit" style="margin-top:200px;">
    
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-list dropdown">
                        <div class="dropdown-content">
                            <ul class="nav flex-column">
                            <li class="nav-item">
                              <a class="nav-link " href="{{ route('marketspace/editprofile') }}" >Profile</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#email">Email Notification</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#membership">Membership</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="{{ route('marketspace/changepassword') }}">Password</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#payment">Payment & Financials</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#transaction">Transaction</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#accounting">Accounting security</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#trust">Trust & Verification</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link active" href="{{ route('marketspace/account') }}">Account</a>
                            </li>
                          </ul>
                            <select id="selectOption"> 
                            <option value="" selected="selected">Select Option</option> 
    
                            <option value="{{ route('marketspace/editprofile') }}">Profile</option> 
                            <option value="">Email Notification</option> 
                            <option value="">Membership</option> 
                            <option value="">Password</option> 
                            <option value="">Payment & Financials</option>
                            <option value="">Transaction</option> 
                            <option value="">Accounting security</option>
                            <option value="">Trust & Verification</option>
                            <option value="{{ route('marketspace/account') }}">Account</option>
                            </select> 


                        </div>
                    
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Account</h5>
                                </div>
                                <div class="tab-card-form">
                                <div class="alert alert-success" id="success_editprof" style="display:none;">
                                    Successfully updated
                                </div>
                                <form method="POST" action="" action="" name="saveAccount" id="saveAccount" class="saveAccount" autocomplete="off" >
                                    @csrf
                                    <div class="AccountTypeSave">  
			
                                    <h5>I'm looking to:</h5>
                                    <div class="saveAccountType">  
                                    <div class="form-check">
                                        @php
                                        if($marketspace->user_type!='')
                                        {
                                            $user_type=explode(',',$marketspace->user_type);
                                        }else{
                                            $user_type=array();
                                        }
                                        
                                        @endphp
                                        <input class="form-check-input" type="checkbox" value="Work" id="user_type_work" name="user_type[]" @if(in_array("Work", $user_type)) checked @endif>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Work
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Hire" id="user_type_hire"  name="user_type[]" @if(in_array("Hire", $user_type)) checked @endif>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Hire
                                        </label>
                                        </div>
                                        </div> 

                                        </div>
                                        <div class="card-savebtn">
                                        <button type="button" class="btn btn-default" id="save_account_btn">Save</button>
                                        </div>
                                        </div>
                                     


                                        
                                      
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Email Notification</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="membership" role="tabpanel" aria-labelledby="membership-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Membership</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Password</h5>
                                </div>
                                <div class="tab-card-form">
                                    <div class="password">
                                        <form>
                                            <div class="form-group">
                                                <label for="currentPassword">Current Password</label>
                                                <input type="password" class="form-control" id="currentPassword" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="newPassword">New Password</label>
                                                <input type="password" class="form-control" id="newPassword" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="confirmPassword">Confirm Password</label>
                                                <input type="password" class="form-control" id="confirmPassword" placeholder=" ">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Payment & Financials</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Transaction</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="accounting" role="tabpanel" aria-labelledby="accounting-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Accounting security</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="trust" role="tabpanel" aria-labelledby="trust-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Trust & Verification</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <h5>Account</h5>
                                </div>
                                <div class="tab-card-form">
                                    
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@section('scripts')
<script>
$("#selectOption").change(function() {

var selectbox = document.getElementById("selectOption");
    var selectedValue = selectbox.options[selectbox.selectedIndex].value;
    window.location.href = selectedValue;
});
    
$("#save_account_btn").click(function() {
  var form = $("#saveAccount");
form.validate({
 rules: {
  'user_type[]': {
        required:true,
     },
  
     
 },
 messages: {
    'user_type[]': {
         required:"Field is required!",
     },
   
   
   
 }
}); 
if(form.valid() === true) {
  $(".about_gif").show();

	$.ajax({
    type:'POST',
    url:'{{ route("saveAccount") }}',
    //dataType:'json',
    data: $("#saveAccount").serialize(),
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
@endsection
