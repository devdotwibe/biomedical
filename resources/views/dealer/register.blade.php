<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dealer Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->

  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/iCheck/square/blue.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style type="text/css">
    span.error {
        color:red;
    }
  </style>

</head>
<body class="hold-transition login-page">
  <div class="loginpage-wrapper h-100">
    <div class="row h-100">
      <div class="col-sm-6 col-lg-6 blue-bg"></div>
       <div class="col-sm-6 col-lg-6 white-bg">
<div class="login-box">
  <div class="login-logo">
      <a ><img src="{{ asset('images/company_logo.png') }}" alt=""/></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg title text-bold">Dealer Register</p>
    
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
            
    
    <form id="login" name="login" id="login" name="login" method="post"  action="{{URL::to('dealer/register')}}">
        @csrf
      <div class="form-group has-feedback">
        <input type="text" name="name" id="name" value="<?php echo old('name'); ?>" class="form-control" placeholder="Organization Name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <p class="error-content alert-danger">{{ $errors->first('name') }}</p>
      </div>
      <div class="form-group has-feedback">
        <input type="email" name="username" id="username" value="<?php echo old('username'); ?>" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <p class="error-content alert-danger">{{ $errors->first('username') }}</p>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <p class="error-content alert-danger">{{ $errors->first('password') }}</p>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="Confirm Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <p class="error-content alert-danger">{{ $errors->first('cpassword') }}</p>
      </div>
      <div class="form-group has-feedback">
          <input type="checkbox" value="yes" id="acceptcondition"  name="acceptcondition" >
          &nbsp;&nbsp;&nbsp;<span  for="acceptcondition"> I agree to BEC <a href="{{ route('terms') }}" style="color:dodgerblue"> Terms & conditions </a> and <a href="{{ route('privacy') }}" style="color:dodgerblue"> Privacy Policy</a>.</span>

      </div>
      
      <div class="form-group has-feedback">
          <button type="submit" class="lg-btn submit-btn ">Register</button>
        </div>
   
        <div class="form-group has-feedback text-center">
            <a href="{{URL::to('dealer')}}" class="text-center"> Click Here to Login.! </a>
        </div>

        
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
</div>
</div>
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>

<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate-additional-methods.js') }}"></script>



<script>

$(document).ready(function () {
    $('#login').validate({ // initialize the plugin
        errorElement: 'span',
        rules: {
            name: {
                required: true
            },
            username: {
                required: true
            },

            password: {
                required: true,
                minlength: 5,
                maxlength:15
            },
            cpassword : {
                equalTo : "#password"
            },
            acceptcondition: {
                required: true
            }
        },
        
        messages: {
            name: {
                required: 'Name is required'
            },
            username: {
                required: 'Username is required'
            },
            password: {
                required: 'Password is required',
                minlength: "Password should be atleast {0} characters!",
                maxlength: "Password not more than {0} characters!",
            },
            cpassword : {
                equalTo : "Mismatch Confirm Password"
            },
            acceptcondition: {
                required: "By creating an account you agree to our Terms & Privacy."
            }
        }
    });
});

</script>
</body>
</html>