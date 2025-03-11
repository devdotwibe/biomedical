@extends('layouts.app')


<?php
$title       = 'Login';
$description = 'Login';
$keywords    = 'Login'
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)


@section('content')
 
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<section class="login-wrapper">
        <div class="container">
          <div class="row ">
            <div class="col-md-7">
                 <div class="login-left">
                      <div class="login-imgtxt">
                          <div class="login-caption">
                              <h2>More products, more choice</h2>
                              <ul>
                              <li>32 major product categories</li>
                              <li>24,000 + products</li>
                              <li>99.8% order accuracy</li>
                              </ul>
                          </div>
                      </div>
                  </div>
            </div>
            <div class="col-md-5">
                 <div class="login-right">
                      <div class="loginform">
                          <h4>Log in to BecZone</h4>
                          <form method="POST"  action="{{ route('login') }}" name="loginform" id="loginform" autocomplete="off">
                                @csrf
                            <div class="form-group">
                              <label>User name</label>
                              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            <div class="form-group">
                              <label>Password</label>
                              <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required >
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                            <input type="button" value="LOG IN TO ORDER" class="btn order-btn" id="loginbtn">
                            <h4>Call</h4>
                            <div class="login-btns">
                            <a href="{{ route('register') }}" class="login-btn">BECOME A CUSTOMER</a>
                            <a href="{{ route('password.request') }}" class="login-btn">LOST PASSWORD</a>
                            </div>
                          </form>
                      </div>
                  </div>
            </div>
          </div>
        </div>
    </section >


@endsection
@section('scripts')
    <script>
      //  

    $( document ).ready(function() {

        $('#loginbtn').click(function() {
         
          
           // var validator = $(".registeruser").validate({
               var form = $("#loginform");
      form.validate({
                rules: {
                  

                    email: {
                       required:true,
                       email   : true,
                    },


                    password: {
                        required: true,
                         minlength : 5,
                    }

                },
                messages: {
                    
                    email: {
                        required:"Email is required!",
                        email   : "Please enter a valid email address!"
                    },


                
                      password: {
                        required: "Password Required!",
                          minlength: "Minimum length 5!"
                    }


                }
            }); 

             if(form.valid() === true) {
                 $("#loginform").submit();
            } else {

                validator.focusInvalid();
                return false;
            }
        });
            
       
    });
</script>
   @endsection     