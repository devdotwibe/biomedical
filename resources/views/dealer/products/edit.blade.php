@extends('dealer/layouts.app')

@section('title', 'Edit Product')

@section('content')

<section class="content-header">
      <h1>
        Edit Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('dealer'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('dealer.products.index')}}">Manage Products</a></li>
        <li class="active">Edit Product</li>
      </ol>
</section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">

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


            <form role="form" name="frm_products" id="frm_products" method="post" action="{{ route('dealer.products.update', $product->id) }}" enctype="multipart/form-data">
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">

                 <div class="form-group  col-md-6">
                  <label for="name">Product Name*</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" value="{{ $product->name }}" onkeyup="javascript: auto_fill();">

                    <p class="error-content alert-danger">
                    {{ $errors->first('name') }}
                    </p>
                </div>

                <div class="form-group  col-md-6">
                  <label for="slug">SEO Url*</label>
                  <input type="text"  disabled  class="form-control" placeholder="SEO Url" value="{{ $product->slug }}" onkeyup="javascript: filter_string();">

            <p class="error-content alert-danger">
            {{ $errors->first('slug') }}
            </p>
                </div>

                <div class="form-group  col-md-6">
                  <label>Product Type</label>
                  <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = ($product->product_type_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>

            <p class="error-content alert-danger">
            {{ $errors->first('product_type_id') }}
            </p>
                </div>


                <div class="form-group  col-md-6">
                  <label>Category</label>
                  <select name="category_type_id" id="category_type_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($catgory_type as $item) {
                      $sel = ($product->category_type_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>

            <p class="error-content alert-danger">
            {{ $errors->first('category_type_id') }}
            </p>
                </div>


                  <div class="form-group  col-md-6">
                  <label>Care Area*</label>
                  <select multiple="multiple" name="category_id[]" id="category_id" class="form-control">
                    <option value="">-- Select Care Area --</option>
                    <?php
                    foreach($first as $item) {
                      $array=explode(',',$product->category_id);
                     // print_r($array);
                     if (in_array($item->id, $array)){
                      $sel = 'selected';
                     }else{
                      $sel = '';
                     }
                    
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>

            <p class="error-content alert-danger">
            {{ $errors->first('category_id') }}
            </p>
                </div>

                <div class="form-group  col-md-6">
                  <label>Modality</label>
                  <select name="modality" id="modality" class="form-control">
                    <option value="">-- Select Modality --</option>
                    <?php
                    foreach($modality as $item) {
                      $sel = ($product->modality == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                       
                    } ?>
                  </select>

            <p class="error-content alert-danger">
            {{ $errors->first('modality') }}
            </p>
                </div>

                  <div class="form-group  col-md-6">
                  <label>Brand*</label>
                  <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = ($product->brand_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>

            <p class="error-content alert-danger">
            {{ $errors->first('brand_id') }}
            </p>
                </div>



                <div class="form-group col-md-12">
                  <label for="description">Description*</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description">{{ $product->description }}</textarea>
                    
            <p class="error-content alert-danger">
            {{ $errors->first('description') }}
            </p>
                </div>

                 <div class="form-group col-md-12">
                  <label for="description">Short Description</label>
                  <textarea class="form-control" id="short_description" name="short_description" rows="10" cols="80" placeholder="Short Description">{{ $product->short_description }}</textarea>
                    
            <p class="error-content alert-danger">
            {{ $errors->first('short_description') }}
            </p>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Item Code</label>
                  <input type="text" id="item_code" name="item_code" class="form-control" placeholder="Item Code" value="{{ $product->item_code }}" >
                </div>

                 <div class="form-group  col-md-6">
                  <label for="name">Unit</label>
                  <input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="{{ $product->unit }}" >
                </div>

        
                 <div class="form-group  col-md-6">
                  <label for="name">Warrenty</label>
                  <input type="text" id="warrenty" name="warrenty" class="form-control" placeholder="Warrenty" value="{{ $product->warrenty }}" >
                </div>


                <div class="form-group  col-md-6">
                  <label for="name">Validity</label>
                  <input type="text" id="validity" name="validity" class="form-control" placeholder="Validity" value="{{ $product->validity }}" >
                </div>



                <div class="form-group col-md-12">
                  <label for="image_name">Image</label>
                  <input type="file" id="image_name" accept="image/jpg,image/jpeg,image/png"  name="image_name" />
                  <input type="hidden" id="current_image" name="current_image" value="<?php echo $product->image_name ?>"/>
     
                  <p class="error-content alert-danger">
            {{ $errors->first('image_name') }}
            </p>
                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png )</p>
                     <?php if($product->image_name != '') { ?>
                  <img src="<?php echo asset("public/storage/products/$product->image_name") ?>" width="100" height="100"/>
                  <?php } ?>
                </div>



                <div class="form-group">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo ($product->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo ($product->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('dealer.products.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')



<script src="{{ asset('js/jquery-bootstrap-tooltip-validate.js') }}"></script>
<script type="text/javascript">
$(function(){
                CKEDITOR.replace('description',
             {
                        customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
                        height: '300',
                        width:'885',
                        toolbar: 'Cms'
                });
                
  $('#frm_products').validate({ // initialize the plugin
      errorElement: 'span',
      rules: {
          name: {
              required: true,
              maxlength:250
          },
          category_id: {
              required: true
          },

          brand_id: {
              required: true,
          },
          status : {
              required: true,
          }
      },

      messages: {
          name: {
              required: "<strong class='text-warning'>Name</strong> is required!",
              maxlength: "<strong class='text-warning'>Name</strong> not more than {0} characters!",
          },
          category_id: {
              required: "<strong class='text-warning'>Care Area</strong> is required!"
          },
          brand_id: {
              required: "<strong class='text-warning'>Brand</strong> is required!",
          },
          status : {
            required: "<strong class='text-warning'>Status</strong> is required!",
          }
      },
      tooltip_options: {
          name: {placement:'bottom',html:true},
          category_id: {placement:'bottom',html:true},
          brand_id: {placement:'bottom',html:true},
          status: {placement:'bottom',html:true}
      },
      });
      

            
    });

     function auto_fill()
    {
        var name  = $('#name').val();
        var seo_url = get_url_string(name);
        $('#slug').val(seo_url);
    }

    function filter_string()
    {
        var seo_url = $('#slug').val();
        seo_url = get_url_string(seo_url);
        $('#slug').val(seo_url);
    }

    function get_url_string(string)
    {
        string    = string.toLowerCase();
        var seo_url = string.replace(/[^a-zA-Z 0-9]+/g,'-');
        seo_url   = seo_url.replace(/ /g,'-');
        seo_url  = seo_url.replace(/--*/g,'-');
        return seo_url;
    }
  </script>

  
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  <script type="text/javascript">

$(document).ready(function() {

  
$('#category_id').multiselect();

});


 </script>

@endsection
