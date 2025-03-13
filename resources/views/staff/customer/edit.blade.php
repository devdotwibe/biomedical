
@extends('staff/layouts.app')

@section('title', 'Edit Customer')

@section('content')


<section class="content-header">
      <h1>
        Edit Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('customer.index')}}">Manage Customer</a></li>
        <li class="active">Edit Customer</li>
      </ol>
    </section>

<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-3 leftside-menu">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                  {{ucfirst($user->business_name)}}
                     <!-- <div class="btn-group">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                                                      <li>
                              <a href="#" target="_blank">
                              <i class="fa fa-share-square-o"></i> Login as client                              </a>
                           </li>
                                                                                 <li>
                              <a href="#" class="text-danger delete-text _delete"><i class="fa fa-remove"></i> Delete                               </a>
                           </li>
                                                   </ul>
                     </div> -->
                     </h4>
               </div>
            </div>
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
      <!-- <li class="customer_tab_profile active">
      <a data-group="profile" href="{{ route('customer.edit',$user->id) }}">
                    <i class="fa fa-user-circle menu-icon" aria-hidden="true"></i>
                Edit User     </a>
    </li> -->
      <li class="customer_tab_contacts ">
      <a data-group="contacts" href="{{ route('customer.show',$user->id) }}">
                    <i class="fa fa-users menu-icon" aria-hidden="true"></i>
                    Contact person      </a>
    </li>
     
  </ul>
         </div>
        <div class="col-md-9">
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

           <form role="form" name="frm_user" id="frm_user" method="post" action="{{ route('customer.update', $user->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
               
       
           
               <div class="box-body">

               
<div class="form-group">
    <div class="row">
		<div class="col-xs-12">

			<!-- tabs -->
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#personal_details" data-toggle="tab">Business Name</a></li>
					<!-- <li><a href="#about" data-toggle="tab">Feature</a></li> -->
					<li><a href="#account_details" data-toggle="tab">Bank Details</a></li>
          <li><a href="#business_details" data-toggle="tab">Shipping Address</a></li>
          
				</ul>
				<div class="tab-content">
				

					<div class="tab-pane active" id="personal_details">
						<div class="">
            <!-- <h4>Personal Details</h4> -->
            <div class="form-group  col-md-6">
                  <label for="name">Head of the Institution*</label>
                  <input type="text" id="name"  name="name" class="form-control" value="{{$user->name}}" placeholder="Head of the Institution">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>
             <div class="form-group  col-md-6">
                  <label for="name">Hospital Name*</label>
                  <input type="text" disabled="true" id="business_name" name="business_name" class="form-control" value="{{$user->business_name}}" placeholder="Hospital Name">
                  <span class="error_message" id="business_name_message" style="display: none">Field is required</span>
                </div>

                 

                <div class="form-group  col-md-6">
                  <label for="name">Customer Category</label>
                  <select  id="customer_category_id" name="customer_category_id" class="form-control" >
                  <option value="">Select Customer Category</option>
                  @foreach($customer_category as $values)

                    <?php
                  $sel = ($user->customer_category_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  
                  @endforeach
                  </select>
                  <span class="error_message" id="customer_category_id_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group  col-md-6">
                  <label for="name">Email*</label>
                  <input  type="email" id="email" disabled="true" name="email" class="form-control" value="{{$user->email}}" placeholder="Email">
                  <span class="error_message" id="email_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group  col-md-6">
                  <label for="name">Phone*</label>
                  <input type="text" id="phone" disabled="true" name="phone" class="form-control" value="{{$user->phone}}" placeholder="Phone">
                  <span class="error_message" id="phone_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-12">
                  <label for="name">Address*</label>
                  <textarea disabled="true"  id="address1" name="address1" class="form-control"  placeholder="Address">{{$user->address1}}</textarea>
                
                  <span class="error_message" id="address1_message" style="display: none">Field is required</span>
                </div>

                 

                  <div class="form-group  col-md-3">
                  <label for="name">Country*</label>
                  <select id="country_id" name="country_id" class="form-control" onchange="change_country()" >
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <?php
                    if($user->country_id>0)
                    {
                      $sel = ($user->country_id == $values->id) ? 'selected': '';
                    }
                    else{
                      $sel = ($values->id == "101") ? 'selected': '';
                    }
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="country_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">State*</label>
                  <select id="state_id" name="state_id" class="form-control" onchange="change_state();" >
                  <option value="">Select State</option>
                  @foreach($state as $values)
                  <?php
                  $sel = ($user->state_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">District*</label>
                  <select id="district_id" name="district_id" class="form-control" onchange="change_district();" >
                  <option value="">Select District</option>
                  @foreach($district as $values)
                  <?php
                  $sel = ($user->district_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-3">
                  <label for="name">Taluk*</label>
                  <select id="taluk_id" name="taluk_id" class="form-control" >
                  <option value="">Select Taluk</option>
                  @foreach($taluk as $values)
                  <?php
                  $sel = ($user->taluk_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="taluk_id_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group  col-md-6">
                  <label for="name">GST</label>
                  <input type="text"  id="gst" name="gst" class="form-control" value="{{$user->gst}}" placeholder="GST">
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>

              
                <div class="form-group  col-md-6">
                  <label for="name">Zip*</label>
                  <input type="text" id="zip" name="zip"   class="form-control" value="{{$user->zip}}" placeholder="Zip">
                  <span class="error_message" id="zip_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group hideRow col-md-12">
                  <label for="image_name">Image1</label>
                  <input type="file" disabled="true" id="image_name1" name="image_name1"  accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image1" name="current_image1" value="<?php echo $user->image_name1 ?>"/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>

                    <?php if($user->image_name1 != '') { 
                     $nameimg= explode(".",$user->image_name1);
                     if($nameimg[1]!='pdf')
                     {
                      ?> 
                <a download="<?php echo asset("storage/app/public/user/$user->image_name1") ?>" href="<?php echo asset("storage/app/public/user/$user->image_name1") ?>">  <img  src="<?php echo asset("storage/app/public/user/$user->image_name1") ?>" width="100px" height="100px" /></a>
                  <?php
                     }
                     else{
                       ?>
                       <a download="<?php echo asset("storage/app/public/user/$user->image_name1") ?>" href="<?php echo asset("storage/app/public/user/$user->image_name1") ?>">
                       <img src="{{ asset('images/pdf.png') }}" width="100px" height="100px">
                       </a>
                       <?php
                     }
                } ?>
                  <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group hideRow col-md-12">
                  <label for="image_name">Image2</label>
                  <input type="file" disabled="true" id="image_name2" name="image_name2"  accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image2" name="current_image2" value="<?php echo $user->image_name2; ?>"/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>

                    <?php if($user->image_name2 != '') { 
                      
                     $nameimg= explode(".",$user->image_name2);
                     
                     if($nameimg[1]!='pdf')
                     {
                      ?> 
                      <a href="<?php echo asset("storage/app/public/user/$user->image_name2") ?>" download="<?php echo asset("storage/app/public/user/$user->image_name2") ?>">
                  <img  src="<?php echo asset("storage/app/public/user/$user->image_name2") ?>" width="100px" height="100px" />
                  </a>
                  <?php
                     }
                     else{
                       ?>
                       <a download="<?php echo asset("storage/app/public/user/$user->image_name2") ?>" href="<?php echo asset("storage/app/public/user/$user->image_name2") ?>">
                       <img src="{{ asset('images/pdf.png') }}" width="100px" height="100px">
                       </a>
                       <?php
                     }
                } ?>
                  <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>

 <?php
        $staff_id = session('STAFF_ID');   
         $user_list= DB::select('select * from assign_supervisor where  `supervisor_id`="'.$staff_id.'" ');
         if(count($user_list)>0)
         {
        ?>  

                
                <div class="form-group col-md-12">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" disabled="true" id="status1" value="Y" <?php echo ($user->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" disabled="true" id="status1" value="N" <?php echo ($user->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div> 

<?php
         }
?>

              

               
                           

						</div>
					</div>



					<div class="tab-pane" id="account_details">
						<div class="">
            <h2>Bank Details</h2>

                 <div class="form-group  col-md-6">
                  <label for="name">Account Name</label>
                  <input type="text" disabled="true" id="account_name" name="account_name" class="form-control" value="{{$user->account_name}}" placeholder="Account Name">
                  <span class="error_message" id="account_name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Bank</label>
                  <input type="text" disabled="true" id="bank_name" name="bank_name" class="form-control" value="{{$user->bank_name}}" placeholder="Bank">
                  <span class="error_message" id="bank_name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-12">
                  <label for="name">Bank Address</label>
                  <textarea id="bank_address" disabled="true" name="bank_address" class="form-control" placeholder="Bank Address">{{$user->bank_address}}</textarea>
                  <span class="error_message" id="bank_address_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-6">
                  <label for="name">IFSC Code</label>
                  <input type="text" id="ifsc_code" disabled="true" name="ifsc_code" class="form-control" value="{{$user->ifsc_code}}" placeholder="IFSC Code">
                  <span class="error_message" id="ifsc_code_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Account No</label>
                  <input type="text" id="account_no" disabled="true" name="account_no" class="form-control" value="{{$user->account_no}}" placeholder="Account No">
                  <span class="error_message" id="account_nomessage" style="display: none">Field is required</span>
                </div>
               

						</div>
          </div>

          <div class="tab-pane" id="business_details">
         
            <?php
            $i=0;$j=0;

            if(count($users_shipping_address)>0)
            {
              ?>
               <input type="hidden" id="count_addr" name="count_addr" class="form-control" value="<?php echo count($users_shipping_address)?>">
              <?php
            foreach($users_shipping_address as $values)
            {
             // print_r($values);
           
            ?>
             
						<div class="panel panel-default" id="row_<?php echo $j;?>">
            <input type="hidden" id="shipping_id" name="shipping_id[]" class="form-control" value="{{$values->id}}">
            <div class="panel-body">

                <div class="form-group  col-md-6">
                  <label for="name">Street Address1</label>
                  <input type="text" disabled="true"  id="shipping_address1" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="{{$values->address1}}">
                
                  <span class="error_message" id="shipping_address1_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Street Address2</label>
                  <input type="text" disabled="true" id="shipping_address2" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="{{$values->address2}}">
                  <span class="error_message" id="shipping_address2_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="shipping_country_id" disabled="true" name="shipping_country_id[]" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values_con)
                  <?php
                  $sel = ($values->country_id == $values_con->id) ? 'selected': '';
                  echo '<option value="'.$values_con->id.'" '.$sel.'>'.$values_con->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="shipping_country_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" disabled="true" name="shipping_city[]" class="form-control" value="{{$values->city}}" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">State</label>
                  <input type="text" id="state" disabled="true" name="shipping_state[]" class="form-control" value="{{$values->state}}" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" disabled="true" name="shipping_zip[]" class="form-control" value="{{$values->zip}}" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>
                @if($i!=0)
                <div class="form-group  col-md-6">
               <br>
               <button type="button" class="btn btn-danger" onClick="remove_ajax({{$j}},{{$values->id}})">Remove</button>
               </div>
               @endif

                
                </div>
						</div>
            <?php 
            $i++;$j++;
            }
          }
          else{
            ?>
            <div class="tab-pane" id="business_details">
          <input type="hidden" id="count_addr" name="count_addr" class="form-control" value="1">
						<div class="panel panel-default" id="row_1">
             
            <div class="panel-body">

                <div class="form-group  col-md-6">
                  <label for="name">Street Address1</label>
                  <input type="text" disabled="true"  id="shipping_address1" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="">
                
                  <span class="error_message" id="shipping_address1_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Street Address2</label>
                  <input type="text"  disabled="true"  id="shipping_address2" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="">
                  <span class="error_message" id="shipping_address2_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="shipping_country_id" disabled="true" name="shipping_country_id[]" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <option value="{{$values->id}}">{{$values->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="shipping_country_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" disabled="true" name="shipping_city[]" class="form-control" value="" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">State</label>
                  <input type="text" id="state" disabled="true" name="shipping_state[]" class="form-control" value="" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" disabled="true" name="shipping_zip[]" class="form-control" value="" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>
                
                </div>

                  


						</div>
            <?php
          }
            ?>


            <div id="responcesec"></div>
       


          </div>

         

				</div>
			</div>
			<!-- /tabs -->
		</div>
	</div>
</div>

               

              


            
              <!-- /.box-body -->

 <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="validate_from()">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('customer.index')}}'">Cancel</button>
              </div> 
            </form>


          
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
 <script type="text/javascript">

  function addmore()
      {
        
        var count_addr=$("#count_addr").val();
        var add_count=parseInt(count_addr)+1;
        $("#count_addr").val(add_count);
        var htmls='<div class="panel panel-default" id="row_'+add_count+'">'+
       ' <input type="hidden" id="shipping_id" name="shipping_id[]" class="form-control" value="0">'+
           ' <div class="panel-body">'+

              '<div class="form-group  col-md-6">'+
              '<label for="name">Street Address1</label>'+
              '<input type="text"   id="shipping_address1'+add_count+'" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="">'+
                
              '<span class="error_message" id="address1_message" style="display: none">Field is required</span>'+
              '</div>'+
              ' <div class="form-group  col-md-6">'+
              '  <label for="name">Street Address2</label>'+
              '  <input type="text"  id="shipping_address2'+add_count+'" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="">'+
              '  <span class="error_message" id="address2_message" style="display: none">Field is required</span>'+
              ' </div>'+

              ' <div class="form-group  col-md-6">'+
              ' <label for="name">Country</label>'+
              '  <select id="shipping_country_id" name="shipping_country_id[]" class="form-control">'+
              '   <option value="">Select Country</option>'+
              '  @foreach($country as $values)'+
                  '   <option value="{{$values->id}}">{{$values->name}}</option>'+
                  '  @endforeach'+
                  '</select>'+
                  '<span class="error_message" id="bank_city_message" style="display: none">Field is required</span>'+
                '</div>'+

               '<div class="form-group  col-md-6">'+
               ' <label for="name">City</label>'+
               ' <input type="text" id="shipping_city'+add_count+'" name="shipping_city[]" class="form-control" value="" placeholder="City">'+
               '  <span class="error_message" id="city_message" style="display: none">Field is required</span>'+
               ' </div>'+
               ' <div class="form-group  col-md-6">'+
               ' <label for="name">State</label>'+
               ' <input type="text" id="shipping_state'+add_count+'" name="shipping_state[]" class="form-control" value="" placeholder="State">'+
               ' <span class="error_message" id="state_message" style="display: none">Field is required</span>'+
               ' </div>'+
               '  <div class="form-group  col-md-6">'+
               '  <label for="name">Zip</label>'+
               '  <input type="text" id="shipping_zip'+add_count+'" name="shipping_zip[]" class="form-control" value="" placeholder="Zip">'+
               ' <span class="error_message" id="zip_message" style="display: none">Field is required</span>'+
               ' </div>'+
              
                 '  <div class="form-group  col-md-6">'+
               ' <br>'+
               ' <button type="button" class="btn btn-danger" onClick="remove('+add_count+')">Remove</button>'+
               ' </div>'+
                
               ' </div>'+
               '</div>';
        //responcesec
        $("#responcesec").append(htmls);
      }
      function remove(row_no)
      {
        var count_addr=$("#count_addr").val();
        var add_count=parseInt(count_addr)-1;
        $("#count_addr").val(add_count);
        $("#row_"+row_no).remove();
      }

       function remove_ajax(row_no,product_id)
      {
        var count_addr=$("#count_addr").val();
        var add_count=parseInt(count_addr)-1;
        $("#count_addr").val(add_count);
        $("#row_"+row_no).remove();
        var url = APP_URL+'/staff/remove_shippingaddress';
    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data:{
            product_id: product_id,
        },
        success: function (data)
        {
         // alert()
        }
    });

      }

      

 function validate_from()
      {
        
        var name=$("#name").val();
        var phone=$("#phone").val();
        var email=$("#email").val();
    
        var business_name=$("#business_name").val();
        var address1=$("#address1").val();
        var state_id=$("#state_id").val();
        //var city=$("#city").val();
        var zip=$("#zip").val();
        var staff_id=$("#staff_id").val();

         var gst=$("#gst").val();
        var country_id=$("#country_id").val();

         var district_id=$("#district_id").val();
         var taluk_id=$("#taluk_id").val();
         var customer_category_id=$("#customer_category_id").val();

         
       if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }
       
     
      if(state_id=="")
        {
          $("#state_id_message").show();
        }
        else{
          $("#state_id_message").hide();
        }

      if(zip=="")
        {
          $("#zip_message").show();
        }
        else{
          $("#zip_message").hide();
        }

        if(country_id=="")
        {
          $("#country_id_message").show();
        }
        else{
          $("#country_id_message").hide();
        }

         if(district_id=="")
        {
          $("#district_id_message").show();
        }
        else{
          $("#district_id_message").hide();
        }

         if(taluk_id=="")
        {
          $("#taluk_id_message").show();
        }
        else{
          $("#taluk_id_message").hide();
        }
        if(customer_category_id=="")
        {
          $("#customer_category_id_message").show();
        }
        else{
          $("#customer_category_id_message").hide();
        }
      
   //console.log(name+'--'+customer_category_id+'--'+country_id+'--'+state_id+'--'+zip+'--'+district_id+'--'+taluk_id)
         
        if( name!=''  && customer_category_id!='' && country_id!=''    && state_id!='' && zip!='' && district_id!='' && taluk_id!='')
        {
         $("#frm_user").submit(); 
        }


      }
      
    
   

      
      function change_country(){
  var country_id=$("#country_id").val();
  var url = APP_URL+'/staff/change_country';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            country_id: country_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select State</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#state_id").html(states_val);
             
              
           
          }
        });

  }

    function change_state(){
  var state_id=$("#state_id").val();
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
             
              
           
          }
        });

  }


  function change_district(){
  var district_id=$("#district_id").val();
  var url = APP_URL+'/staff/change_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            district_id: district_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Taluk</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#taluk_id").html(states_val);
             
              
           
          }
        });

  }
  

    </script>

      <?php
                    if($user->country_id>0)
                    {
                     
                    }
                    else{
                    ?>
<script>change_country();</script>
                    <?php
                    }?>

@endsection
