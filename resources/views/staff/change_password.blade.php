@extends('staff/layouts.app')

@section('title', 'Change Password')

@section('content')


<section class="content-header">
  <h1>
    Change Password
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo URL::to('staff'); ?>/dashboard">Dashboard</a></li>
    <li class="active">Change Password</li>
  </ol>
</section>


<section class="content">
    <div class="row">
      <div class="col">
        <div class="box box-primary change-passwrd-index">

          <!--<div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>-->
          <!-- /.box-header -->
          <!-- form start -->

          @if(session()->has('message'))
        <div class="alert alert-success">
        {{ session()->get('message') }}
        </div>
      @endif


          @if(session()->has('error_message'))
        <div class="alert alert-danger">
        {{ session()->get('error_message') }}
        </div>
      @endif

          <p class="error-content alert-danger">
            {{ $errors->first('username') }}
            {{ $errors->first('password') }}
          </p>

          <form role="form" name="frm_change_password" id="frm_change_password" method="post" action="">
            @csrf
            <div class="box-body">
              <div class="form-group">
                <label for="current_password">Current Password*</label>
                <input type="password" id="current_password" name="current_password" class="form-control"
                  placeholder="Current Password">
              </div>
              <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>

              <div class="form-group">
                <label for="confirm_password">Confirm Password*</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                  placeholder="Confirm Password">
              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>

@endsection