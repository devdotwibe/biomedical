@extends('staff/layouts.app')

@section('title', 'Add Competition Product')

@section('content')

<section class="content-header">
      <h1>
        Add Competition Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('competition_product.index')}}">Manage Competition Product</a></li>
        <li class="active">Add Competition Product</li>
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
       
            </p>

            <form role="form" name="frm_subcategory" id="frm_subcategory" method="post" action="{{route('competition_product.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">

                <div class="form-group">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group  col-md-6">
                  <label>Category*</label>
                  <select name="category_type_id" id="category_type_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($catgory_type as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                  <span class="error_message" id="category_type_id_message" style="display: none">Field is required</span>
                </div>



                <div class="form-group  col-md-6">
                  <label>Care Area*</label>
                  <select  name="category_id" id="category_id" class="form-control">
                    <option value="">-- Select Care Area --</option>
                    <?php
                    foreach($first as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="category_id_message" style="display: none">Field is required</span>
                </div>

                  
                  <div class="form-group  col-md-6">
                  <label>Brand*</label>
                  <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="brand_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label>Product Type*</label>
                  <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = (old('product_type_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                  <span class="error_message" id="product_type_id_message" style="display: none">Field is required</span>
                </div>



              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('competition_product.index')}}'">Cancel</button>
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
        var category_type_id=$("#category_type_id").val();
        var category_id=$("#category_id").val();
        var brand_id=$("#brand_id").val();
        var product_type_id=$("#product_type_id").val();
  
 
  if(name=="")
      {
        $("#name_message").show();
      }
      else{
        $("#name_message").hide();
      }

  if(category_type_id=="")
      {
        $("#category_type_id_message").show();
      }
      else{
        $("#category_type_id_message").hide();
      }
      if(category_id=="")
      {
        $("#category_id_message").show();
      }
      else{
        $("#category_id_message").hide();
      }
      if(brand_id=="")
      {
        $("#brand_id_message").show();
      }
      else{
        $("#brand_id_message").hide();
      }
      if(product_type_id=="")
      {
        $("#product_type_id_message").show();
      }
      else{
        $("#product_type_id_message").hide();
      }

  if(name!='' && category_type_id!='' && category_id!='' && brand_id!='' && product_type_id!='')
  {
         $("#frm_subcategory").submit(); 
        }


      }

    </script>
@endsection
