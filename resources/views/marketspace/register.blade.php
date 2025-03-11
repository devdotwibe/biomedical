@extends('layouts.appmasterspace')
<?php
$title       = 'Register';
$description = 'Register';
$keywords    = 'Register';
$message="";

?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<section class="hRegisterPage">
  

<main class="login-form">
    <div class="login-left">
        <div class="brand-head">
        <div class="container">
          <div class="brand-top-section">
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <a class="nav-link" href="https://biomedicalengineeringcompany.com/">Home</a>
          </div>
        </div>
      </div>
        <div class="top-space">
        <div class="container">
          
        
          <div class="login-page-content">
            <h1>Hiring’s hard. We’ve made it easy</h1>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perferendis ad, pariatur molestiae animi totam dicta..</p>
          </div>
        
      </div>
      </div>
      </div>

  <div class="login-left">
      <div class="blue-bg">
          <div class="r-login-form">
              <div class="card">
                 <div class="card-header">Sign in to your account</div> 
                  <div class="card-body">
  
                 
  
                        @if(session()->has('message'))
    <div class="alert alert-success">
        {!! session()->get('message') !!}
    </div>
@endif
                      <form autocomplete="off" action="{{ route('postRegistration') }}" method="POST" id="postRegistration" name="postRegistration">
                          @csrf
                           

                          <div class="form-group row AccountTypeSave_otp" style="display:none;">
                              <label for="otp" class="col-md-4 col-form-label text-md-right">Enter OTP</label>
                              <div class="col-md-6">
                                  <input type="text" id="otp" class="form-control" name="otp" required autofocus>
                                  @if ($errors->has('otp'))
                                      <span class="text-danger">{{ $errors->first('otp') }}</span>
                                  @endif
                                  <span class="text-danger error_otp" style="display:none;">Invalid Otp</span>
                                  <span class="text-success suc_otp" style="display:none;">Otp successfully send your registered mobile number</span>
                                  
                              </div>
                          </div>

                          <div class="col-md-6 offset-md-4 AccountTypeSave_otp" style="display:none;">
                             
                              <button type="button" class="btn btn-primary" id="verfy_otp">
                                  Verify OTP
                              </button>
                              <a onclick="resend_otp()" style="cursor:pointer;">Resend OTP</a>
                              <div class="loader_sec_login_resend" style="display:none;">
                                <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                            </div>
                      


                          <div class="AccountTypeSave first_reg">  
			
                                    <h5>I'm looking to:</h5>
                                    <div class="saveAccountType">  
                                    <div class="form-check first_reg">
                                      
                                       
                                        <input class="form-check-input" type="radio" value="Work" id="user_type_work" name="user_type[]" >
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Work
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="Hire" id="user_type_hire"  name="user_type[]" >
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Hire
                                        </label>
                                        </div>
                                        </div> 

                                        </div>

                          <div class="form-group row first_reg">
                              <label for="phone" class="col-md-4 col-form-label text-md-right">Enter Mobile Number</label>
                              <div class="col-md-6">

                                <div class="country-out">
                                {{-- <select class="selectpicker form-control" name="country_code" id="country_code" title="Choose Country" >
                                    <option value="91">91</option>
                                </select> --}}
                                <input type="hidden" id="country_code" class="form-control" name="country_code"  value="1">
                                <input type="hidden" id="phone" class="form-control" name="phone"  value="1">
                                <input type="text" id="email" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                                </div>

                              </div>
                          </div>
  
                      
  
                          <div class="col-md-6 offset-md-4 first_reg">
                          <div class="loader_sec_login" style="display:none;">
			<img src="{{ asset('images/wait.gif') }}" alt=""/></div>

                              <button type="button" class="btn btn-primary" id="register">
                                  Continue to OTP
                              </button>
                              <span class="loginfo">

By clicking login,you agree to Biomedical Engineering Company <a href="{{ route('terms') }}">Condition of Use</a> and <a href="{{ route('privacy') }}">Privacy</a>
</span>

                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
      <div class="copyright"><p>© 2023 Bio Medical Engineering Company | All Rights Reserved</p></div>
  </div>
  
</main>

</section>
@endsection


@section('scripts')
<script>
    function resend_otp()
    {
        $(".error_otp").hide();
        
        $(".loader_sec_login_resend").show();
        $("#otp").val('');
        var phone='91'+$("#phone").val();
        $("#otp").val();
        $.ajax({
			type:'POST',
			url:'{{ route("otpsend") }}',
            data: $("#postRegistration").serialize(),
			//dataType:'json',
			headers: {
					'X-CSRF-Token': '{{ csrf_token() }}',
			},
			success:function(data){
                $(".loader_sec_login_resend").hide();
			//	$(".first_reg").hide();
              //  $(".AccountTypeSave_otp").show();
                
              /*  $.ajax({
                type: "POST",
                url: "https://betablaster.in/api/send.php?number="+phone+"&type=text&message=Your Beczone verification code is: "+data+"&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
            
                    dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });*/

			}, 
			error: function(data){
				console.log('error')
			}
		});

    }
    $("#register").click(function () {
 var form = $("#postRegistration");
form.validate({
 rules: {
    
    'user_type[]': {
        required:true,
     },
    phone: {
        required:true,
        minlength:10,
  maxlength:10,
  number: true
     },
  
     
     
 },
 messages: {
    'user_type[]': {
         required:"Field is required!",
     },
     phone: {
         required:"Field is required!",
        
     },
    
      
 }
}); 
if(form.valid() === true) {
 var phone='91'+$("#phone").val();
 $(".loader_sec_login").show();
 
    $.ajax({
			type:'POST',
			url:'{{ route("otpsend") }}',
            data: $("#postRegistration").serialize(),
            processData: false,
			//dataType:'json',
			headers: {
					'X-CSRF-Token': '{{ csrf_token() }}',
			},
			success:function(data){
                
                
				$(".first_reg").hide();
                $(".AccountTypeSave_otp").show();
                
               /* $.ajax({
                type: "POST",
                url: "https://betablaster.in/api/send.php?number="+phone+"&type=text&message=Your Beczone verification code is: "+data+" Please copy and paste verification code for continue login.&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
                    
                dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                    },
                success: function(resultData){
                    }
                });*/

			}, 
			error: function(data){
				console.log('error')
			}
		});


    //$("#postRegistration").submit();
}

}); 


$("#verfy_otp").click(function () {
 var form = $("#postRegistration");
form.validate({
 rules: {
    
  
    otp: {
        required:true,
      
     },
  
     
     
 },
 messages: {
   
    otp: {
         required:"Field is required!",
        
     },
    
      
 }
}); 
if(form.valid() === true) {
 var phone='91'+$("#phone").val();
    $.ajax({
			type:'POST',
			url:'{{ route("verifyotp") }}',
            data: $("#postRegistration").serialize(),
			//dataType:'json',
			headers: {
					'X-CSRF-Token': '{{ csrf_token() }}',
			},
			success:function(data){
				if(data>0){
                    location.href="{{ route('dashboard') }}"
                    $(".error_otp").hide();
                }
                else{
                    $(".error_otp").show();
                }

			}, 
			error: function(data){
				console.log('error')
			}
		});


    //$("#postRegistration").submit();
}

}); 


document.onkeydown = function(e) {
  if(event.keyCode == 123) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
     return false;
  }
}

document.addEventListener('contextmenu', function(e) {
  e.preventDefault();
});


    </script>
@endsection
<style>
.login-form .form-group.row .country-out {
    float: left;
    width: 100%;
    height: 44px;
    background: #fff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.login-form .form-group.row .country-out select.selectpicker {
    width: 60px;
    border: none !important;
    box-shadow: none !important;
}

.login-form .form-group.row .country-out .form-control {
    border: none !important;
}
    </style>

