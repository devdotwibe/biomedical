@extends('staff/layouts.app')

@section('title', 'View Dealer')

@section('content')

<section class="content-header">
      <h1>
        View Dealer
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Dealer</li>
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
                    @if($dealer->profile_pic!='')
                    <img src="{{ asset('profile-pic/dealer/'.$dealer->profile_pic) }}"   />
                    @else
                    <img src="{{ asset('images/noimage.jpg') }}" >
                    @endif
                    </div>
                </div>
            <h3 class="profile-username text-center">{{$dealer->dealer_name}}</h3>

            <p class="text-muted text-center"> Membership ID : {{$dealer->dealer_code}}</p>


            <a href="{{route('dealer.edit',$dealer->dealer_id)}}" class="text-warning col-md-offset-5">Edit Profile <i class="fa fa-pencil"></i></a>


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
            {{$dealer->created_at->format('Y-m-d')}}
            </p>

            <hr>
            <strong> Registered Email</strong>

            <p class="text-muted">
            {{$dealer->username}}
            </p>

            <hr>

            <strong><i class="fas fa-map-marker-alt mr-1"></i> Verification Status</strong>
            @if($dealer->status=="Y")
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
                    {{$dealer->dealer_name}}
                    </p>
                    <hr>
                    <strong> Phone Number</strong>
                    <p class="text-muted">
                    {{$dealer->phone}} &nbsp;&nbsp;@if($dealer->phone!="") <small class="text-success">verified <i class="fa fa-check"></i> </small> @endif
                    </p>
                    <hr>
                    <strong> Alternate Phone Number</strong>
                    <p class="text-muted">
                    {{$dealer->alter_phone}}
                    </p>
                    <hr>
                    <strong> Email</strong>
                    <p class="text-muted">
                    {{$dealer->email}} &nbsp;&nbsp; <a href="#"><small class="text-primary">verify</small></a>
                    </p>
                    <hr>
                    <strong> Address</strong>
                    <p class="text-muted">
                    {{$dealer->address}}
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

