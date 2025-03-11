

@extends('staff/layouts.app')

@section('title', 'Add Care Area')

@section('content')

<section class="content-header">
      <h1>
        Add Care Area
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.category.index')}}">Manage Care Area</a></li>
        <li class="active">Add Care Area</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            {{ $errors->first('image_name') }}
            </p>

            <form role="form" name="frm_cat" id="frm_cat" method="post" action="{{route('staff.category.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">


                <div class="form-group">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Category Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

               


                <div class="form-group hideRow">
                  <label for="image_name">Image</label>
                  <input type="file" id="image_name" name="image_name" required/>
                  <input type="hidden" id="current_image" name="current_image" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png <br />
                    Min. Image size: 200 X 150 pixels)</p>
                    <span class="error_message" id="image_name_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo (old('status') == 'Y' || old('status') == '') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo (old('status') == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.category.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')
    <script type="text/javascript">

   
      function validate_from()
      {
        var name=$("#name").val();
      
      //  var image_name=$("#image_name").val();
        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

       /* if(image_name=="")
        {
          $("#image_name_message").show();
        }
        else{
          $("#image_name_message").hide();
        }*/



         
        if(name!=''  )
        {
         $("#frm_cat").submit(); 
        }


      }

    </script>

@endsection
