

@extends('staff/layouts.app')

@section('title', 'Edit Product Type')

@section('content')


<section class="content-header">
      <h1>
        Edit Product Type
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.product_type.index')}}">Manage Product Type</a></li>
        <li class="active">Edit Product Type</li>
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

            <form role="form" name="frm_subcategory" id="frm_subcategory" method="post" action="{{ route('staff.product_type.update', $product_type->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">

                  
                <div class="form-group">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$product_type->name}}" placeholder="Product Type">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

              


                <div class="form-group">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo ($product_type->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo ($product_type->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.product_type.index')}}'">Cancel</button>
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
     
        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

      

         
        if(name!='' )
        {
         $("#frm_subcategory").submit(); 
        }


      }

    </script>

@endsection
