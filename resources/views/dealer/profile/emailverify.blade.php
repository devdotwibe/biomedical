@extends('dealer/layouts.app')
@section('title', 'Email Verification')
@section('content')
<section class="content-header">
  <h1>  Email Verification  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('dealer'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Email Verification</li>
  </ol>
</section>

    <!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-offset-4 col-md-4">
      <div class="box">


    @if (session('success'))
        <div class="alert alert-success alert-block fade in alert-dismissible show">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

        <div class="box-body">

            <form action="{{ route('postRegistration') }}" method="POST" id="postRegistration" name="postRegistration">
                @csrf
                

                <div class="form-group" >
                    <label for="otp" ><span class="text-success suc_otp" >Otp successfully send your registered email  {{$profile->email}}</span></label><br>
                        <input type="text" id="otp" class="form-control" name="otp" placeholder="Enter the OTP. " required autofocus>
                        @if ($errors->has('otp'))
                            <span class="text-danger">{{ $errors->first('otp') }}</span>
                        @endif
                    <!-- <div class="col-md-6">
                        <span class="text-danger error_otp" style="display:none;">Invalid Otp</span>
                        
                    </div> -->
                </div>

                <div class="form-group">                             
                    <button type="button" class="btn btn-primary" id="verfy_otp">Verify OTP</button>
                    <a onclick="resend_otp()">Resend OTP</a>
                </div>
            </form>
                
        </div>

      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>

@endsection


@section('scripts')
<script>
  
$("#verfy_otp").click(function () {
    $("#postRegistration").validate({
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


}); 

    </script>
@endsection

