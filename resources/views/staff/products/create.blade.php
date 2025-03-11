

@extends('staff/layouts.app')

@section('title', 'Add Product')

@section('content')


<section class="content-header">
      <h1>
        Add Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.products.index')}}">Manage Products</a></li>
        <li class="active">Add Product</li>
      </ol>
</section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col">
          <!-- general form elements -->
          <div class="box box-primary">
<!--            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>-->
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
            {{ $errors->first('category_id') }}
            {{ $errors->first('brand_id') }}
            {{ $errors->first('name') }}
            {{ $errors->first('title') }}
            {{ $errors->first('slug') }}
            {{ $errors->first('description') }}
            {{ $errors->first('image_name') }}
            {{ $errors->first('meta_description') }}
            {{ $errors->first('meta_keywords') }}
            {{ $errors->first('short_title') }}
            {{ $errors->first('short_description') }}
            {{ $errors->first('banner_image') }}
            </p>


            <div class="prdt-cret-box">
            <form role="form" name="frm_products" id="frm_products" method="post" action="{{route('staff.products.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">

                <div class="form-group col-md-2">
                  <label for="name">Product Name*</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" value="{{ old('name') }}" onkeyup="javascript: auto_fill();">
                </div>

                <div class="form-group  col-md-2">
                  <label for="part_no">Part. No.</label>
                  <input type="text" id="part_no" name="part_no" class="form-control" placeholder="Part. No." value="{{ old('part_no') }}" onkeyup="javascript: filter_string();">
                </div>

                <div class="form-group  col-md-2">
                  <label for="slug">SEO Url*</label>
                  <input type="text" id="slug" name="slug" class="form-control" placeholder="SEO Url" value="{{ old('slug') }}" onkeyup="javascript: filter_string();">
                </div>

                   <div class="form-group  col-md-2">
                  <label>Product Type</label>
                  <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = (old('product_type_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>

                 <div class="form-group col-md-2">
                  <label for="name">Product Short Name</label>
                  <input type="text" id="short_title" name="short_title" class="form-control" placeholder="Product Short Name" value="{{ old('short_title') }}" >
                </div>

                <div class="form-group  col-md-2">
                  <label>Category</label>
                  <select name="category_type_id" id="category_type_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($catgory_type as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>



                <div class="form-group  col-md-2">
                  <label>Care Area*</label>
                  <select multiple="multiple" name="category_id[]" id="category_id" class="form-control">
                    <option value="">-- Select Care Area --</option>
                    <?php
                    foreach($first as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                </div>


                <div class="form-group  col-md-2">
                  <label>Brand*</label>
                  <select name="brand_id" id="brand_id" class="form-control" onchange="ChangeModality(this)">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                </div>

                <div class="form-group  col-md-2">
                  <label>Modality</label>
                  <select name="modality" id="modality" class="form-control">
                    <option value="">-- Select Modality --</option>
                    <?php
                    foreach($modality as $k=> $item) {
                      $sel = (old('modality') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.' id="mod_'.$item->id.'" style="display:none;" class="mod_class">'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                </div>
                  
              
                 <div class="form-group  col-md-2">
                  <label>Related Product</label>

                  <select multiple="multiple" name="related_products[]" id="related_products" class="form-control">
                   
                  <?php
                    foreach($catgory_type_sort as $item) {
                     
                     
                      ?>
                  <optgroup label="{{$item->name}}">
                      
                  <?php 
                    $catlist =  DB::select("select * from products where `product_type_id`='".$item->id."' order by id desc");
                     
                       foreach($catlist as $prod) {
                      ?>
                    <option value="{{$prod->id}}">{{$prod->name}}</option>
                          <?php
                           }
                          ?>  
                    </optgroup>

                   <?php 
                    }
                   ?>
                  </select>
                </div>

                 

                 <div class="form-group  col-md-2">
                  <label>Competition Product</label>
                  <select multiple="multiple" name="competition_product[]" id="competition_product" class="form-control">
                    <option value="">-- Select Competition Product --</option>
                  
                     <?php
                     foreach($catgory_type_compi as $item) {
                         ?>
                   <optgroup label="{{$item->name}}">
                   <?php 
                    $cmplist =  DB::select("select * from competition_product where `product_type_id`='".$item->id."' order by id desc");
                     
                       foreach($cmplist as $prod) {
                      ?>
                          <option value="{{$prod->id}}">{{$prod->name}}</option>
                      <?php
                       }
                      ?>
                    </optgroup>
                   <?php
                     }
                     ?>

                  
                  </select>
                </div> 


                  <div class="form-group  col-md-2">
                  <label>Option1</label>
                  <select name="option1" id="option1" class="form-control">
                    <option value="">-- Select Option1 --</option>
                    <option value="Brand New">Brand New</option>
                    <option value="Refrebrished">Refrebrished</option>
                   
                  </select>
                </div>

                 <div class="form-group  col-md-2">
                  <label>Option2</label>
                  <select name="option2" id="option2" class="form-control">
                    <option value="">-- Select Option2 --</option>
                    <option value="Original">Original</option>
                    <option value="Compatable">Compatable</option>
                   
                  </select>
                </div>

                 <div class="form-group  col-md-2">
                  <label>Company*</label>
                  <select name="company_id" id="company_id" class="form-control">
                    <option value="">-- Select Company --</option>
                    <?php
                    foreach($company as $item) {
                      //$sel = (old('company_id') == $item->id) ? 'selected': '';
                      $sel = ( $item->id==5) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>



            


                <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="8" cols="80" placeholder="Description">{{ old('description') }}</textarea>
                </div>

                <div class="form-group col-md-12">
                  <label for="description">Short Description</label>
                  <textarea class="form-control" id="short_description" name="short_description" rows="8" cols="80" placeholder="Short Description">{{ old('short_description') }}</textarea>
                </div>

                <div class="form-group  col-md-2">
                  <label for="name">Item Code</label>
                  <input type="text" id="item_code" name="item_code" class="form-control" placeholder="Item Code" value="{{ old('item_code') }}" >
                </div>

                 <div class="form-group  col-md-2">
                  <label for="name">Unit</label>
                  <input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="{{ old('unit') }}" >
                </div>

                 <div class="form-group  col-md-2">
                  <label for="name">Quantity</label>
                  <input type="text" id="quantity" name="quantity" class="form-control" placeholder="Item Code" value="{{ old('quantity') }}" >
                </div>

                   <div class="form-group  col-md-2">
                  <label for="name">Unit Price</label>
                  <input type="text" id="unit_price" name="unit_price" class="form-control" placeholder="Unit Price" value="{{ old('unit_price') }}" >
                </div>

                   <div class="form-group  col-md-2">
                  <label for="name">Tax Percentage</label>
                  <input type="text" id="tax_percentage" name="tax_percentage" class="form-control" placeholder="Tax Percentage" value="{{ old('tax_percentage') }}" >
                </div>
                

                <div class="form-group  col-md-2">
                  <label for="name">HSN Code</label>
                  <input type="text" id="hsn_code" name="hsn_code" class="form-control" placeholder="HSN Code" value="{{ old('hsn_code') }}" >
                </div>

                 <div class="form-group  col-md-2">
                  <label for="name">Warranty</label>
                  <input type="text" id="warrenty" name="warrenty" class="form-control" placeholder="Warranty" value="{{ old('warrenty') }}" >
                </div>

                <div class="form-group  col-md-2">
                  <label for="name">Payment</label>
                  <input type="text" id="payment" name="payment" class="form-control" placeholder="Payment" value="{{ old('payment') }}" >
                </div>

                <div class="form-group  col-md-2">
                  <label for="name">Validity</label>
                  <input type="text" id="validity" name="validity" class="form-control" placeholder="Validity" value="{{ old('validity') }}" >
                </div>

                <div class="form-group  col-md-2">
                  <label>Optional Products</label>
                  <select name="op_pdt[]" id="op_pdt" class="form-control" multiple="multiple">
                    <option value="">-- Select Optional products --</option>
                  
                    @foreach($products as $item) 
                      <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach  
                    
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group col-md-2">
                  <label for="image_name">Image</label>
                  <input type="file" id="image_name" name="image_name" />
                  <input type="hidden" id="current_image" name="current_image" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png )</p>
                </div>

                  <div class="form-group col-md-2">
                  <label for="image_name1">Brochure</label>
                  <input type="file" id="image_name1" name="image_name1" accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image1" name="current_image1" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf) </p>
                </div>


                <div class="form-group col-md-2">
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
              <input type="hidden" name="comp_count" id="comp_count" value="0"> 
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.products.index')}}'">Cancel</button>
              </div>
            </form>
            </div>

          </div>

        </div>
      </div>
</section>



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <form name="contactform" id="contactform" method="post" action"" />
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Competition Product</h4>
      </div>
      
      <div class="modal-body">

      
      
      <div class="form-group col-md-12">
                  <label for="name">Name*</label>
                  <input type="text" id="comp_name" name="comp_name" class="form-control" placeholder="Name" value="" >
                  <span class="error_message" id="comp_name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-2">
                  <label>Category*</label>
                  <select name="comp_category_type_id" id="comp_category_type_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($catgory_type as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                  <span class="error_message" id="comp_category_type_id_message" style="display: none">Field is required</span>
                </div>



                <div class="form-group  col-md-2">
                  <label>Care Area*</label>
                  <select  name="comp_category_id" id="comp_category_id" class="form-control">
                    <option value="">-- Select Care Area --</option>
                    <?php
                    foreach($first as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="comp_category_id_message" style="display: none">Field is required</span>
                </div>

                  
                  <div class="form-group  col-md-2">
                  <label>Brand*</label>
                  <select name="comp_brand_id" id="comp_brand_id" class="form-control">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="comp_brand_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-2">
                  <label>Product Type*</label>
                  <select name="comp_product_type_id" id="comp_product_type_id" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = (old('product_type_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                  <span class="error_message" id="comp_product_type_id_message" style="display: none">Field is required</span>
                </div>



           
      <div class="modal-footer">
      <div class="load-add" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      <span id="successshow" style="display:none;color:green;">Contact Person Added</span>
        <button type="button" class="btn btn-primary"  onclick="validate_contact()">Add Competition</button>
      </div>
    </div>
    </form>
  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript">



jQuery(document).ready(function() {

CKEDITOR.replace('description',
             {
                        customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
                        height: '300',
                        width:'885',
                        toolbar: 'Cms'
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

  <script type="text/javascript">

$(document).ready(function() {
//$('#category_type_id').multiselect();
$('#category_id').multiselect();
/*$('#related_products').multiselect({
  enableClickableOptGroups: true
});*/
var options = $('#op_pdt option');
    options.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#op_pdt').append(options);

$('#op_pdt').multiselect({
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true
});

});

$(document).ready(function() {
    $('#related_products').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="related_products" />'
    });
    $('#related_products .caret-container').click();
});

$(document).ready(function() {
    $('#competition_product').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="competition_product" />'
    });
    $('#competition_product .caret-container').click();
});


  function ChangeModality(e)
  {
    var value = $(e).val();

      $.post('{{route("staff.modality_change")}}',{

        value:value,
          
      },function(res){

          $('.mod_class').hide();

          $.each(res.mod,function(i,v)
          {
              $('#mod_'+v).show();
          })
          
      },'json');
    
  }




$(document).ready(function() {
    var selectedCategoryValue = $('#category_type_id').val();
    var categoryOptions = $('#category_type_id option'); 
    categoryOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#category_type_id').append(categoryOptions);
    $('#category_type_id').val(selectedCategoryValue);

    var selectedProductValue = $('#product_type_id').val();
    var productOptions = $('#product_type_id option');
    productOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#product_type_id').append(productOptions);
    $('#product_type_id').val(selectedProductValue);

    var selectedProductValue = $('#option1').val();
    var productOptions = $('#option1 option');
    productOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#option1').append(productOptions);
    $('#option1').val(selectedProductValue);

    var selectedProductValue = $('#option2').val();
    var productOptions = $('#option2 option');
    productOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#option2').append(productOptions);
    $('#option2').val(selectedProductValue);

    var selectedProductValue = $('#company_id').val();
    var productOptions = $('#company_id option');
    productOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#company_id').append(productOptions);
    $('#company_id').val(selectedProductValue);


});


document.addEventListener("DOMContentLoaded", function () {
      function sortSelectElement(selectId, preserveDefault = false) {
          const selectElement = document.getElementById(selectId);
          if (!selectElement) return;

          const optgroups = Array.from(selectElement.querySelectorAll("optgroup"));

          optgroups.sort((a, b) => a.label.localeCompare(b.label));

          const defaultOption = preserveDefault ? selectElement.querySelector('option[value=""]') : null;

          selectElement.innerHTML = "";
          if (defaultOption) selectElement.appendChild(defaultOption);

          optgroups.forEach(optgroup => {
              const options = Array.from(optgroup.querySelectorAll("option"));
              options.sort((a, b) => a.textContent.localeCompare(b.textContent));
              optgroup.innerHTML = ""; 
              options.forEach(option => optgroup.appendChild(option)); 
              selectElement.appendChild(optgroup); 
          });
      }

      sortSelectElement("related_products");
      sortSelectElement("competition_product", true); 
  });

 </script>

@endsection


 