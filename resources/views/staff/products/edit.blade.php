@extends('staff/layouts.app')

@section('title', 'Edit Product')

@section('content')

<section class="content-header">
      <h1>
        Edit Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('product.index')}}">Manage Products</a></li>
        <li class="active">Edit Product</li>
      </ol>
</section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
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

            <p class="error-content alert-danger">
            {{ $errors->first('category_id') }}
            {{ $errors->first('name') }}
            {{ $errors->first('title') }}
            {{ $errors->first('slug') }}
            {{ $errors->first('description') }}
            {{ $errors->first('image_name') }}
            </p>

            <form role="form" name="frm_products" id="frm_products" method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
               @csrf
               {{method_field('PUT')}}
                <div class="box-body border-row">
                  <div class="row">

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Product Name*</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" value="{{ $product->name }}" onkeyup="javascript: auto_fill();">
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="part_no">Part. No.</label>
                  <input type="text" id="part_no" name="part_no" class="form-control" placeholder="Part. No." value="{{ $product->part_no }}" onkeyup="javascript: filter_string();">
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="slug">SEO Url*</label>
                  <input type="text" id="slug" name="slug" class="form-control" placeholder="SEO Url" value="{{ $product->slug }}" onkeyup="javascript: filter_string();">
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Product Type</label>
                  <select name="product_type_id" id="product_type_id" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = ($product->product_type_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>

                 <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Product Short Name</label>
                  <input type="text" id="short_title" name="short_title" class="form-control" placeholder="Product Short Name" value="{{ $product->short_title }}" >
                </div>
              </div>
              <div class="row">

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Category</label>
                  <select name="category_type_id" id="category_type_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php
                    foreach($catgory_type as $item) {
                      $sel = ($product->category_type_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>


                  <div class="form-group  col-md-3 col-sm-6 col-lg-3">
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
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Brand*</label>
                  <select name="brand_id" id="brand_id" class="form-control" onchange="ChangeModality(this)">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = ($product->brand_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                </div>


                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Modality</label>
                  <select name="modality" id="modality" class="form-control">
                    <option value="">-- Select Modality --</option>
                    <?php
                    foreach($modality as $item) {
                      $sel = ($product->modality == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.' id="mod_'.$item->id.'" style="display:none;" class="mod_class" >'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                </div>
                

                 
              </div>
              <div class="row">
                 

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Related Product</label>

                  <select multiple="multiple" name="related_products[]" id="related_products" class="form-control">
                   
                  <?php
                    foreach($catgory_type_sort as $item) {
                      $catlist =  DB::select("select * from products where `product_type_id`='".$item->id."' AND id!='".$product->id."' order by id desc");
                      
                     
                      ?>
                  <optgroup label="{{$item->name}}">
                      
                  <?php 
                       foreach($catlist as $prod) {
                        $array_rels=explode(',',$product->related_products);
                     
                      if (in_array($prod->id, $array_rels)){
                       $sel = 'selected';
                      }else{
                       $sel = '';
                      }
                        
                      ?>
                    <option value="{{$prod->id}}" <?php echo $sel;?>>{{$prod->name}}</option>
                          <?php
                           }
                          ?>  
                    </optgroup>

                   <?php 
                    }
                   ?>
                  </select>
                </div>


                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
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
    $array_comp=explode(',',$product->competition_product);
    // print_r($array);
    if (in_array($prod->id, $array_comp)){
     $sel = 'selected';
    }else{
     $sel = '';
    }

 ?>
     <option value="{{$prod->id}}" <?php echo $sel;?>>{{$prod->name}}</option>
 <?php
  }
 ?>
</optgroup>
<?php
}
?>


                  </select>
                </div> 


              


                  <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Option1</label>
                  <select name="option1" id="option1" class="form-control">
                    <option value="">-- Select Option1 --</option>
                    <option value="Brand New" <?php if($product->option1=="Brand New"){echo "selected";}?>>Brand New</option>
                    <option value="Refrebrished" <?php if($product->option1=="Refrebrished"){echo "selected";}?>>Refrebrished</option>
                   
                  </select>
                </div>

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Option2</label>
                  <select name="option2" id="option2" class="form-control">
                    <option value="">-- Select Option2 --</option>
                    <option value="Original" <?php if($product->option2=="Original"){echo "selected";}?>>Original</option>
                    <option value="Compatable" <?php if($product->option2=="Compatable"){echo "selected";}?>>Compatable</option>
                   
                  </select>
                </div>
              </div>
              <div class="row">
                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Company*</label>
                  <select name="company_id" id="company_id" class="form-control">
                    <option value="">-- Select Company --</option>
                    <?php
                    foreach($company as $item) {
                      $sel = ($product->company_id == $item->id) ? 'selected': '';
                      echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>
              </div>
               <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="description">Description*</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description">{{ $product->description }}</textarea>
                </div>

                 <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="description">Short Description</label>
                  <textarea class="form-control" id="short_description" name="short_description" rows="10" cols="80" placeholder="Short Description">{{ $product->short_description }}</textarea>
                </div>
              </div>
               <div class="row">
                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Item Code</label>
                  <input type="text" id="item_code" name="item_code" class="form-control" placeholder="Item Code" value="{{ $product->item_code }}" >
                </div>

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Unit</label>
                  <input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="{{ $product->unit }}" >
                </div>

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Quantity</label>
                  <input type="text" id="quantity" name="quantity" class="form-control" placeholder="Item Code" value="{{ $product->quantity }}" >
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Unit Price</label>
                  <input type="text" id="unit_price" name="unit_price" class="form-control" placeholder="Unit Price" value="{{ $product->unit_price }}" >
                </div>
              </div>
              <div class="row">
                   <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Tax Percentage</label>
                  <input type="text" id="tax_percentage" name="tax_percentage" class="form-control" placeholder="Tax Percentage" value="{{ $product->tax_percentage }}" >
                </div>
                

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">HSN Code</label>
                  <input type="text" id="hsn_code" name="hsn_code" class="form-control" placeholder="HSN Code" value="{{ $product->hsn_code }}" >
                </div>

                 <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Warranty</label>
                  <input type="text" id="warrenty" name="warrenty" class="form-control" placeholder="Warranty" value="{{ $product->warrenty }}" >
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Payment</label>
                  <input type="text" id="payment" name="payment" class="form-control" placeholder="Payment" value="{{ $product->payment }}" >
                </div>
              </div>
              <div class="row">
                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Validity</label>
                  <input type="text" id="validity" name="validity" class="form-control" placeholder="Validity" value="{{ $product->validity }}" >
                </div>
                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Optional Products</label>
                  <select name="op_pdt[]" id="op_pdt" class="form-control" multiple="multiple">
                    <option value="">-- Select Optional products --</option>
                    
                    @php $array_comp=explode(',',$product->optional_products); @endphp
                    @foreach($products as $item)
                      <option value="{{$item->id}}" @if (in_array($item->id, $array_comp)) {{'selected'}} @endif >{{$item->name}}</option>
                    @endforeach  
                    
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>



                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name">Image</label>
                  <input type="file" id="image_name" name="image_name" />
                  <input type="hidden" id="current_image" name="current_image" value="<?php echo $product->image_name ?>"/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png )</p>
                    <?php if($product->image_name != '') { ?>
                  <img src="<?php echo asset("public/storage/products/$product->image_name") ?>" width="100" height="100"/>
                  <?php } ?>
                </div>


                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name1">Brochure </label>
                  <input type="file" id="image_name1" name="image_name1" accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image1" name="current_image1" value="<?php echo $product->image_name1; ?>"/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>
                  <?php if($product->image_name1 != '') { ?>
                     <a href="<?php echo asset("public/storage/products/$product->image_name1") ?>" download="<?php echo asset("storage/app/public/products/$product->image_name1") ?>">
                  Download</a>
                  <?php } ?>
                </div>
              </div>


    <div class="row">
		<div class="col-md-12">

			<!-- tabs -->
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#about" data-toggle="tab">Feature</a></li>
					<!-- <li><a href="#about" data-toggle="tab">Feature</a></li> -->
					<li><a href="#services" data-toggle="tab">Images</a></li>
				</ul>
				<div class="tab-content">
					<!-- <div class="tab-pane active" id="home">
						<div class="">
							<h3>Specifications</h3>
                            <div class="form-group">
                                <label for="specifications">Specifications*</label>
                                <textarea class="form-control" id="specification" name="specification" rows="10" cols="80" placeholder="Description">{{ $product->specification }}</textarea>
                              </div>
						</div>
					</div> -->
					<div class="tab-pane active" id="about">
						<div class="">
                            <h3>Feature</h3>
                            <div class="form-group pd-lr-none">
                                <label for="feature">Feature*</label>
                                <textarea class="form-control" id="feature" name="feature" rows="10" cols="80" placeholder="Description">{{ $product->feature }}</textarea>
                              </div>

						</div>
					</div>

					<div class="tab-pane" id="services">
						<div class="">
							
                <a class="add-button " id="add_image"> Add Image</a>
                <div class="load-sec-del" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr class="headrole">
                    
                    <th>No.</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody id="ajaxdata">
                    <?php $i = 1;
                     ?>
                     @foreach ($product_image as $product_image)
                    <tr data-from ="products">
                        <td>{{$i}}</td>
                        <td>{{ $product_image->title}}</td>
                        <td><a rel="example1" class="example1"  href="<?php echo asset("storage/app/public/product_gallery/$product_image->image_name") ?>" ><img src="<?php echo asset("storage/app/public/product_gallery/thumbnail/$product_image->image_name") ?>" /></td>
                        <td> <a class="btn btn-danger btn-xs deleteItem" onclick="delete_image({{$product_image->id}})" title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td>
                        
                    </tr>

                    <?php $i++ ?>
                    @endforeach
                  </tbody>
              
                    

              </table>


						</div>
                    </div>

				</div>
			</div>
			<!-- /tabs -->
		</div>
	</div>

             <div class="row">
              <div class="col-md-12">
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

                <?php
                  $staff_id = session('STAFF_ID');
                  if($staff_id=="29" || $staff_id=="4")
                  {
                ?>      
                 <div class="form-group">
                    <label >Verified*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="verified" id="verified1" value="Y" <?php echo ($product->verified == 'Y') ? 'checked': '' ?>>
                      Verified
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="verified" id="verified1" value="N" <?php echo ($product->verified == 'N') ? 'checked': '' ?>>
                      Not Verified
                    </label>
                  </div>

                </div>
                <?php
                  }
                ?>

                 </div>  
            </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="mdm-btn-line submit-btn">Submit</button>
                 <button type="button" class="mdm-btn-line cancel-btn" onClick="window.location.href='{{route('product.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>




<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Upload Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
                <div class="form-group">
                  <label for="name">Title*</label>
                  <input type="text" id="img_title" name="img_title" class="form-control" placeholder="Title" value="" >
                  <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                </div>

                <div class="form-group">
                  <label for="img_product">Image*</label>
                  <input type="file" id="img_product" name="img_product" />
                  <input type="hidden" id="current_image_product" name="current_image_product" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png )</p>
                </div>


      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
                          <div id="succesupload" style="display: none;color: green;">Image Uploaded</div>
                  <div id="failupload" style="display: none;color: red;">Image Upload Failed</div>
            <div class="load-sec" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
        <button type="button" class="btn btn-primary" id="img_upload_btn">Upload Image</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="modalDelete_images">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Confirm Delete</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this row?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btnDeleteItem_images" data-id="" data-href="">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
    


@endsection

@section('scripts')
<script type="text/javascript">

function delete_image(id)
{
$("#modalDelete_images").modal('show');
 $('#btnDeleteItem_images').attr('data-id', id);

}  

function getall_image_product()
{
  //  $("#succesupload").hide();
  //  $("#failupload").hide();
    var url = APP_URL+'/staff/get_productimagegallery';
    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data:{
            product_id: $('#product_id').val(),
        },
        success: function (data)
        {
          var proObj = JSON.parse(data);
          htmls=' ';
          var j=1;
          
            for (var i = 0; i < proObj.length; i++) {
              var imgs_large="{{asset('storage/app/public/product_gallery/')}}/"+proObj[i]["image_name"];
              var imgs="{{asset('storage/app/public/product_gallery/thumbnail/')}}/"+proObj[i]["image_name"];
              htmls +='<tr>';
              htmls +='<td>'+j+'</td><td>'+proObj[i]["title"]+'</td><td><img src="'+imgs+'" /></td><td><a class="btn btn-danger btn-xs deleteItem" onclick="delete_image('+proObj[i]["id"]+')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td>';
              htmls +='</tr>';


              j++;
            }
            
$("#ajaxdata").html(htmls);
 //$('#cmsTable').DataTable();
            //var oTable = $('#cmsTable').DataTable({});
        }
    });
}




    jQuery(document).ready(function() {
      var oTable = $('#cmsTable').DataTable({});
      getall_image_product();
      
      jQuery("#btnDeleteItem_images").click(function() {
         var url = APP_URL+'/staff/delete_productimagegallery';
        var id=$(this).attr('data-id');
        $("#modalDelete_images").modal('hide');
        
        $('.load-sec-del').show();
         $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
              id: id,
          },
          success: function (data)
          {  
            getall_image_product()
            $('.load-sec-del').hide();
          }
        });  
      });
      jQuery("#add_image").click(function() {
        $("#myModal").modal('show')
        $("#succesupload").hide();
    $("#failupload").hide();
      });

      jQuery("#img_upload_btn").click(function() {

        var formData = new FormData();
            
  var image_name = $('#img_product').val();
  
  var file_flag = 0;
  if(image_name != '') {    
    //console.log($('input[type=file]')[1].files[1])
    formData.append('image_name', $("#img_product")[0].files[0]);
      file_flag++;
  }
  var img_title=$("#img_title").val();

formData.append('img_title',img_title);
formData.append('product_id',$('#product_id').val());
$(".load-sec").show();
          var url = APP_URL+'/staff/productimagegallery';
         $.ajax({
          type: "POST",
          cache: false,
          processData: false,
          contentType: false,
            url: url,
            data:formData,
            success: function (data)
            {//alert(data)
              $(".load-sec").hide();
              $("#img_title").val('');
              $("#img_product").val('');
              
                if(data==0)
                {
                  $("#failupload").show();
                  $("#succesupload").hide();
                }
                else{
                  $("#succesupload").show();
                  $("#failupload").hide();
                  getall_image_product();
                }
            }
        });


      });
      
        //CKEDITOR.replace('description');
   
            // CKEDITOR.replace('specification',
            // { 
            //          customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
            //          height: '300',
            //          width:'885',
            //          toolbar: 'Cms'
            //  });
             CKEDITOR.replace('feature',
             {
                        customConfig: '{{ asset('AdminLTE/bower_components/ckeditor/custom/config.js') }}',
                        height: '300',
                        width:'885',
                        toolbar: 'Cms'
                });

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

    function ChangeModality(e)
    {
      var value = $(e).val();

        $.post('{{route("modality_change")}}',{

          value:value,
            
        },function(res){

            $('.mod_class').hide();

            $.each(res.mod,function(i,v)
            {
                $('#mod_'+v).show();
            })
            
        },'json');
      
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
