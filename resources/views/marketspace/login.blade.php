@extends('layouts.appmasterspace')
<?php
$title       = 'Login';
$description = 'Login';
$keywords    = 'Login';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<section class="hLoginPage">
    

<main class="login-form">
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Login</div>
                  <div class="card-body">
                  @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                      <form action="{{ route('postLogin') }}" method="POST" id="postLogin" name="postLogin">
                          @csrf
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password" required>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>
                          @if(session()->has('error_message'))
                            <div class="alert alert-danger">
                                {{ session()->get('error_message') }}
                            </div>
                        @endif
  
                          <!-- <div class="form-group row">
                              <div class="col-md-6 offset-md-4">
                                  <div class="checkbox">
                                      <label>
                                          <input type="checkbox" name="remember"> Remember Me
                                      </label>
                                  </div>
                              </div>
                          </div>
   -->
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary" id="login_btn">
                                  Login
                              </button>
                              <div class="accountimfo">
                              Don't have an account?
                              <a class="nav-link" href="{{ route('marketspace/register') }}">Sign up</a>
                                                </div>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>


</section>
@endsection


@section('scripts')
<script>
    
    $("#login_btn").click(function () {
 var form = $("#postLogin");
form.validate({
 rules: {
    
   
    email_address: {
        required:true,
        email: true,
     },
     password: {
        required:true,
     },
     
     
 },
 messages: {
  
    email_address: {
         required:"Field is required!",
         
         email: "Please enter a valid email address",
     },
     password: {
         required:"Field is required!",
     },
      
 }
}); 
if(form.valid() === true) {
    $("#postLogin").submit();
}

}); 
    </script>
@endsection

