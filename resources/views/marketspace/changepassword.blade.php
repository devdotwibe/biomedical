@extends('layouts.appmasterspace')
<?php
$title       = 'Change Password';
$description = 'Change Password';
$keywords    = 'Change Password';
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
                    @include('marketspace.sidebar')

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
                                    <h5>Change Password</h5>
                                </div>
                                <div class="tab-card-form">
                                <div class="alert alert-success" id="success_editprof" style="display:none;">
                                    Successfully updated
                                </div>
                                <form method="POST" action="" action="" name="changepassword" id="changepassword" class="changepassword" autocomplete="off" >
                                    @csrf
                                    <div class="row">
                                       

                                        <div class="form-group col-md-12">
                                        <label for="password">Password*</label>
                                        <input type="password" id="password" name="password" value="" class="form-control" placeholder="Password">
                                        </div>

                                        <div class="form-group col-md-12">
                                        <label for="confirm_password">Confirm Password*</label>
                                        <input type="password" id="confirm_password" name="confirm_password" value="" class="form-control" placeholder="Confirm Password">
                                        </div>
                                        </div>
                                       
                                       


                                        

                                        <div class="card-savebtn">
                                        <button type="button" class="btn btn-default" id="save_prof">Save</button>
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

$("#save_prof").click(function() {
  var form = $("#changepassword");
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
  
  password: {
    required:true,
    },
confirm_password: {
    required:true,
    equalTo: "#password"
    },
  
     
 },
 messages: {
  
     password: {
         required:"Field is required!",
     },
     confirm_password: {
         required:"Field is required!",
     },
   
   
   
 }
}); 
if(form.valid() === true) {
   
  $(".about_gif").show();
	$.ajax({
    type:'POST',
    url:'{{ route("saveChangepassword") }}',
    //dataType:'json',
    data: $("#changepassword").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){
        $("#password").val('');
        $("#confirm_password").val('');
       $("#success_editprof").show();
        /* $(window).scrollTop( $("#myTabContent").offset().top );*/
     //location.reload();
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
});

  
</script>
@endsection
