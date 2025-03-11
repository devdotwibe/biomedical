@extends('dealer/layouts.app')
@section('title', 'Manage Products')
@section('content')
<section class="content-header">
  <h1>  Update Profile  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('dealer'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Update Profile</li>
  </ol>
</section>

    <!-- Main content -->
<section class="content">

@if (session('success'))
        <div class="alert alert-success alert-block fade in alert-dismissible show">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif
  <div class="row">
  
    <div class="col-md-4 col-md-offset-2">
        <!-- Profile Image -->
        <div class="box box-primary box-outline">
        <div class="box-body box-profile">
        
            
            <div class="profileId">
                <div class="profilPic">                        
                    <div id="imagesec">
                        @if($profile->profile_pic!='')
                        <img src="{{ asset('profile-pic/dealer/'.$profile->profile_pic) }}"   />
                        @else
                        <img src="{{ asset('images/noimage.jpg') }}" >
                        @endif
                    </div>
                </div>
                <div class="profileOverlay">
                    <div class="imageupload_sec" style="display:none;">
                        <form id="frofile_form" method="post" enctype="multipart/form-data" action="{{ URL::to('dealer/profile/upload') }}">
                            @csrf       
                            {{method_field('PUT')}}         
                            <span class="text-danger" id="image_message" style="display:none;">Field Required</span>
                            <span class="text-danger" id="imagesize" style="display:none;">Please check image size</span>
                            <span class="text-danger" id="imageWH" style="display:none;">Required minimum width & height (640 x 480)</span>
                            <p class="file-name"></p>
                            <div class="custome-file">
                                <input type="file" name="image" id="image" class="form-control" accept=".jpg, .png, .jpeg"/>
                                <label for="image"></label>
                            </div>
                            <div class="loader_sec" style="display:none;">
                                <img src="{{ asset('images/wait.gif') }}" alt=""/>
                            </div>
                        </form>
                    </div>

                    <button class="edit-content" id="imageedit"><img src="{{ asset('images/edit-icon-white.svg') }}" alt=""/>Edit</button>   
                    <button class="edit-content" id="imagecancel" style="display:none;">Cancel</button>   
                </div>
            </div>




            <hr>
            <h3 class="profile-username text-center">{{$profile->dealer_name}}</h3>

            <p class="text-muted text-center"> Membership ID : {{$profile->dealer_code}}</p>


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
                    <div class="form">
                        <form method="POST" id="profile-form"action="{{ route('dealer.profile.update', $profile->dealer_id) }}" enctype="multipart/form-data">
                        @csrf

               {{method_field('PUT')}}
                            <div class="form-group">
                                <label for="name"> Organization Name *</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old('name',$profile->dealer_name)}}">
                                <p class="error-content alert-danger">
                                    {{ $errors->first('name') }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="phone"> Phone Number *</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{old('phone',$profile->phone)}}">
                                <p class="error-content alert-danger">
                                    {{ $errors->first('phone') }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="alter_phone"> Alternate Phone Number </label>
                                <input type="text" name="alter_phone" id="alter_phone" class="form-control" value="{{old('alter_phone',$profile->alter_phone)}}">
                                <p class="error-content alert-danger">
                                    {{ $errors->first('alter_phone') }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="email"> Email *</label>
                                <input type="text" name="email" id="email" class="form-control" value="{{old('email',$profile->email)}}">
                                <p class="error-content alert-danger">
                                    {{ $errors->first('email') }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="address"> Address </label>
                                <input type="text" name="address" id="address" class="form-control" rows=5 value="{{old('address',$profile->address)}}">
                                <p class="error-content alert-danger">
                                    {{ $errors->first('address') }}
                                </p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Save <i class="fa fa-save fa-sm"></i></button> &nbsp;&nbsp; <button type="button" class="btn" onclick="window.location.href='{{route('dealer.profile.index')}}'">Back to Profile <i class="fa fa-times fa-sm"></i></button>
                            </div>

                        </form>
                    </div>
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

<script src="{{ asset('js/jquery-bootstrap-tooltip-validate.js') }}"></script>
<script type="text/javascript">
    $(function(){
        //$('#myModal').modal();
        $('#profile-form').validate({ // initialize the plugin
            errorElement: 'span',
            rules: {
                name: {
                    required: true,
                    maxlength:100
                },
                phone: {
                    required: true
                },

                email: {
                    required: true,
                    email:true,
                    maxlength:100
                },
                address : {
                    maxlength:100
                }
            },
            
            messages: {
                name: {
                    required: "<strong class='text-warning'>Name</strong> is required!",
                    maxlength: "<strong class='text-warning'>Name</strong> not more than {0} characters!",
                },
                phone: {
                    required: "<strong class='text-warning'>Phone</strong> is required!"
                },
                email: {
                    required: "<strong class='text-warning'>Email</strong> is required!",
                    email: "<strong class='text-warning'>Email</strong> should be valid!",
                    maxlength: "<strong class='text-warning'>Email</strong> not more than {0} characters!",
                },
                address : {
                    maxlength: "<strong class='text-warning'>Address</strong> not more than {0} characters!",
                }
            },
            tooltip_options: {
                name: {placement:'bottom',html:true},
                phone: {placement:'bottom',html:true},
                email: {placement:'bottom',html:true},
                address: {placement:'bottom',html:true}
            },
        });
        
    })




    /*************image********** */
    
    $("#imagecancel").click(function () {
        $("#imageedit").show();
        $("#imagecancel").hide();
        $(".imageupload_sec").hide();
    });

    $("#imageedit").click(function () {
        $("#imageedit").hide();
        $("#imagecancel").show();
        $(".imageupload_sec").show();
    });
    $(".del_btn").click(function () {
		$(".loader_sec").show();
		$.ajax({
			type:'POST',
			url:'{{ route('dealer.profile.update', $profile->dealer_id) }}',
			
			success:function(data){
				$(".loader_sec").hide();
				$("#imagesec").html('<img src="'+data+'" id="category-img-tag">');
				$("#headeruserimg").html('<img src="'+data+'" id="category-img-tag">');
				$(".del_btn").hide();
			}, 
			error: function(data){
				console.log('error')
			}
		});
	});

	$("#image").change(function () {
			 if ($(this).val()) {                 
                var file = this.files[0], img;
                var img = new Image();
                img.src = window.URL.createObjectURL( file );
                img.onload = function() {
                    var width = img.naturalWidth,height = img.naturalHeight;
			        window.URL.revokeObjectURL( img.src );
                    var fileName = $('#image')[0].files[0].name;
                    var fileSize  = $('#image')[0].files[0].size;
				    fileSize = fileSize / 1024;
				    $(".file-name").html(fileName);
                    if(fileSize > 512000){ 
                        $("#imagesize").show();
                    }
                    else{
                        $("#imageWH").hide();
                        $("#imagesize").hide();
                        $(".loader_sec").show();
                        var formData = new FormData($('#frofile_form')[0]);
                        var url = "{{ route('dealer.profile.upload')  }}";
                        $.ajax({
                            type: "POST",
                            cache: false,
                            processData: false,
                            contentType: false,
                            url: url,
                            data:formData,
                                success: function (data)
                                {
                                    $(".imageupload_sec").hide();
                                    $("#imageedit").show();
                                    $("#imagecancel").hide();
                                    $("#image_message").hide();
                                    $("#imagesec").html('<img src="'+data+'" id="category-img-tag">');
                                    $("#headeruserimg").html('<img src="'+data+'" id="category-img-tag">');
                                    $("#image_name").val(data);
                                    $(".loader_sec").hide();
                                    $(".del_btn").show();
                                }
                            });
                        }
                    }
                }
		 });
		
	
    /****************image end*********** */


</script>

@endsection

