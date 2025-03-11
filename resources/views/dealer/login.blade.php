<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dealer Login</title>
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
    <p class="login-box-msg title text-bold">Dealer Login</p>
    
    @if(session()->has('error_message'))
        <div class="alert alert-danger">
            {{ session()->get('error_message') }}
        </div>
    @endif
          
    <p class="error-content alert-danger">
    {{ $errors->first('username') }}
    {{ $errors->first('password') }}
    </p>
    <form id="login" name="login" id="login" name="login" method="post"  action="{{URL::to('dealer')}}">
        @csrf
      <div class="form-group has-feedback">
        <input type="text" name="username" id="username" value="<?php echo old('username'); ?>" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
        
      <div class="form-group has-feedback">
          <button type="submit" class="lg-btn submit-btn ">LOGIN</button>
        </div>
        <div class="form-group has-feedback text-center">
            <a href="{{URL::to('dealer/register')}}" class="text-center"> Register a new membership</a>
        </div>
   
        
    </form>

<!--    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <a href="#">I forgot my password</a><br>
    -->
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

<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>-->
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

<script>

$(document).ready(function () {
    $('#login').validate({ // initialize the plugin
        errorElement: 'span',
        rules: {
            username: {
                required: true
            },

            password: {
                required: true,
                minlength: 5,
                maxlength:15
            }
        },
        
        messages: {
            username: {
                required: 'Username is required'
            },
            password: {
                required: 'Password is required',
                minlength: "Password should be atleast {0} characters!",
                maxlength: "Password not more than {0} characters!",
            }
        }
    });
});

</script>
</body>
</html>