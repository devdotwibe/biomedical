

@extends('staff/layouts.app')

@section('title', 'Edit Care Area')

@section('content')


<section class="content-header">
      <h1>
        Edit Care Area
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('category.index')}}">Manage Care Area</a></li>
        <li class="active">Edit Care Area</li>
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

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            </p>

            <form role="form" name="frm_cat" id="frm_cat" method="post" action="{{ route('category.update', $category->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">

                <div class="form-group">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$category->name}}" placeholder="Category Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

                 

                <div class="form-group hideRow">
                  <label for="image_name">Image</label>
                  <input type="file" id="image_name" name="image_name" />
                  <input type="hidden" id="current_image" name="current_image" value="<?php echo $category->image_name ?>"/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png <br />
                    Min. Image size: 200 X 150 pixels)</p>

                    <?php if($category->image_name != '') { ?>
                  <img src="<?php echo asset("storage/app/public/category/thumbnail/$category->image_name") ?>" />
                  <?php } ?>
                  <span class="error_message" id="image_name_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo ($category->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo ($category->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('category.index')}}'">Cancel</button>
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
        //var image_name=$("#image_name").val();
       
        
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
