

@extends('staff/layouts.app')

@section('title', 'Edit Subcategory')

@section('content')


<section class="content-header">
      <h1>
        Edit Subcategory
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('subcategory.index')}}">Manage Subcategory</a></li>
        <li class="active">Edit Subcategory</li>
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

            <form role="form" name="frm_subcategory" id="frm_subcategory" method="post" action="{{ route('subcategory.update', $subcategory->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">
                   <div class="row">
                   <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                  <label>Category*</label>
                  <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($first as $item) {
                      $sel = ($subcategory->categories_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                    <span class="error_message" id="category_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$subcategory->name}}" placeholder="Subcategory Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>
              </div>
               <div class="row">
              


                <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo ($subcategory->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo ($subcategory->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="mdm-btn submit-btn">Submit</button>
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
