@extends('dealer/layouts.app')
@section('title', 'Manage Products')
@section('content')
<section class="content-header">
  <h1>  Manage Profile  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('dealer'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Profile</li>
  </ol>
</section>

    <!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-4 col-md-offset-2">
        <!-- Profile Image -->
        <div class="box box-primary box-outline">
            <div class="box-body box-profile">            
                <div class="profilPic">                        
                    <div id="imagesec">
                    @if($profile->profile_pic!='')
                    <img src="{{ asset('profile-pic/dealer/'.$profile->profile_pic) }}"   />
                    @else
                    <img src="{{ asset('images/noimage.jpg') }}" >
                    @endif
                    </div>
                </div>
                <h3 class="profile-username text-center">{{$profile->dealer_name}}</h3>

                <p class="text-muted text-center"> Membership ID : {{$profile->dealer_code}}</p>


            <a href="{{route('dealer.profile.edit',$profile->dealer_id)}}" class="text-warning col-md-offset-5">Edit Profile <i class="fa fa-pencil"></i></a>


        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">About Membership</h3>
        </div>
        <!-- /.card-header -->
        <div class="box-body">
            <strong> Date of Registration</strong>

            <p class="text-muted">
            {{$profile->created_at->format('Y-m-d')}}
            </p>

            <hr>
            <strong> Registered Email</strong>

            <p class="text-muted">
            {{$profile->username}}
            </p>

            <hr>

            <strong><i class="fas fa-map-marker-alt mr-1"></i> Verification Status</strong>
            @if($profile->status=="Y")
                <p class="text-success">verified</p>
            @else
                <p class="text-warning">un-verified</p>
            @endif


        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
            <div class="box">                    
                <div class="box-body">
                    <strong> Organization Name</strong>
                    <p class="text-muted">
                    {{$profile->dealer_name}}
                    </p>
                    <hr>
                    <strong> Phone Number</strong>
                    <p class="text-muted">
                    {{$profile->phone}} &nbsp;&nbsp;@if($profile->phone!="") <small class="text-success">verified <i class="fa fa-check"></i> </small> @endif
                    </p>
                    <hr>
                    <strong> Alternate Phone Number</strong>
                    <p class="text-muted">
                    {{$profile->alter_phone}}
                    </p>
                    <hr>
                    <strong> Email</strong>
                    <p class="text-muted">
                    {{$profile->email}} &nbsp;&nbsp;@if($profile->email==$profile->verifyed_email) <small class="text-success">verified <i class="fa fa-check"></i> </small> @elseif($profile->email!="") <a href="{{route('dealer.profile.emailverify')}}"><small class="text-primary">verify</small></a> @endif
                    </p>
                    <hr>
                    <strong> Address</strong>
                    <p class="text-muted">
                    {{$profile->address}}
                    </p>
                    <hr>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->   
  </div>
  <!-- /.row -->
</section>

@endsection

@section('scripts')

<script type="text/javascript">

  </script>

@endsection

