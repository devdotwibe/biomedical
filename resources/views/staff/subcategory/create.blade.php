@extends('staff/layouts.app')

@section('title', 'Add Subcategory')

@section('content')

<section class="content-header">
      <h1>
        Add Subcategory
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('subcategory.index')}}">Manage Subcategory</a></li>
        <li class="active">Add Subcategory</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
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

            <form role="form" name="frm_subcategory" id="frm_subcategory" method="post" action="{{route('subcategory.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">
                  <div class="row">

                 <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label>Category*</label>
                  <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($first as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="category_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Subcategory Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>
              </div>
              <div class="row">
                
                <div class="form-group col-md-4 col-sm-6 col-lg-4">
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
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="mdm-btn submit-btn"  onclick="validate_from()">Submit</button>
                <button type="button" class="mdm-btn cancel-btn" onClick="window.location.href='{{route('subcategory.index')}}'">Cancel</button>
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
        var category_id=$("#category_id").val();
        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

        if(category_id=="")
        {
          $("#category_id_message").show();
        }
        else{
          $("#category_id_message").hide();
        }

         
        if(name!='' && category_id!='')
        {
         $("#frm_subcategory").submit(); 
        }


      }

    </script>
@endsection
