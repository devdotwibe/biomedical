@extends('staff/layouts.app')
@section('title', 'Add Transaction')
@section('content')
<section class="content-header">
      <h1>
        Add Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.transation.index')}}">Manage Transaction</a></li>
        <li class="active">Add Transaction</li>
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
            <form role="form" name="frm_transation" id="frm_transation" method="post" action="{{route('admin.transation.store')}}" enctype="multipart/form-data" >
               @csrf

                <div class="box-body">

                  <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#purchase_order" data-toggle="tab">Sales order</a></li>
		
					<li class="disabled"><a href="#technical_approval" data-toggle="tab">Technical Approval</a></li>
          <li class="disabled"><a href="#otherpro" data-toggle="tab">MSP,Payout,otherprovisions if any</a></li>
          <li class="disabled"><a href="#stock_availiability" data-toggle="tab">Stock Availiability</a></li>
          <li class="disabled"><a href="#financial_approval" data-toggle="tab">Financial Approval</a></li>
          <li class="disabled"><a href="#cust_conform" data-toggle="tab">Customer Confirmation on COD/Site readness</a></li>
        </ul>
				<div class="tab-content">
				

					<div class="tab-pane active" id="purchase_order">
            <!-- Purchase Order start  -->
         
            <div class="panel panel-default">
    <div class="panel-body ">
                   <div class="form-group col-md-12">
                    <label >Source*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Online" checked="true">
                      Online
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Call" >
                      Call
                    </label>
                  </div>
                    <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="E-Mail" >
                      E-Mail
                    </label>
                  </div>

                  

                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="PO" >
                      PO
                    </label>
                  </div>
                       <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Mail" >
                      Mail
                    </label>
                  </div>
                </div>



    <div class="form-group col-md-4" >
                  <label>State*</label>
                  <select name="state_id" id="state_id" class="form-control selectpicker" data-live-search="true" onchange="change_state()">
                    <option value="">-- Select State --</option>
                    <?php
                    
                    foreach($state as $item) {
                      $sel = (old('state_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-4" >
                  <label>District*</label>
                  <select name="district_id" id="district_id" class="form-control selectpicker" data-live-search="true" onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php
                    
                    foreach($district as $item) {
                      $sel = (old('district_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                   <div class="form-group col-md-4" >
                  <label for="name">Customer Name *</label>
                  <input type="hidden" name="user_id_hidden" id="user_id_hidden">
                  <input type="hidden" name="oppur_id" id="oppur_id">
                  <input type="hidden" name="tran_type" id="tran_type">
                  <input type="hidden" name="oppur_status" id="oppur_status">
                  <select class="form-control" name="user_id" id="user_id" onchange="change_user_id(this.value)">
                    <option value="">Customer Name</option>
                    @foreach($user as $values)
                    <?php
                    echo '<option value="'.$values->id.'" >'.$values->business_name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                     <div class="loader_user_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                </div>


                  <div class="form-group col-md-6">
                  <label for="name">Select Option *</label>
                  <select class="form-control" name="type_conf" id="type_conf" onchange="change_conf(this.value)">
                  <option value="">Select Option</option>
                  <!-- <option value="Opportunity">Opportunity</option>
                      <option value="Product">Product</option> -->
                  </select>
                  <span class="error_message" id="type_conf_message" style="display: none">Field is required</span>
                </div>

                    <div class="form-group col-md-6 op_id" style="display:none;">
                        <label>Opportunity*</label>
                        <select name="op_id" id="op_id" class="form-control op_id" onchange="change_oppertunity(this.value)">
                          <option value="">-- Select Opportunity --</option>
                         
                        </select>
                        <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                        <div class="loader_opp" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      </div>
                   <div class="form-group  col-md-3 op_id_date" style="display: none">
                  <label for="name">Opportunity Date</label>
                     <span id="opp_date"></span>
                </div>

            

                      <div class="form-group col-md-2 product_id" style="display:none;">
                        <label>Product*</label>
                        <select name="productid" id="product_id" class="form-control">
                          <option value="">-- Select Product --</option>
                          <?php
                          foreach($products as $item) {
                              echo '<option value="'.$item->id.'" >'.$item->name.'</option>';
                          } ?>
                        </select>
                        <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                      </div>
                   <div class="form-group col-md-2 add_oppur_prod" style="display: none">
                <button type="button" class="btn btn-primary"  onclick="add_oppur_prod()">Add</button>
                      <div class="loader" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </div>


               <table id="cmsTable" class="table table-bordered table-striped data-" style="display:none;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                  
                    <th>Qty</th>
                    <th>FOC</th>
                    <th>Unit Price</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                    
                        <th>MSP</th>
                        <th>Surplus / Deficit</th>
                    <th>Net Amount</th>
                  <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                    <tr  data-from ="staffquote">
                       <td colspan="4" class="noresult">No result</td>
                    </tr>
               </table>



</div>
</div>

                 

                  <div class="box-footer col-md-12">
                  
                  <span class="error_message" id="product_error" style="display: none">Product not added</span>
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <!--  -->
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.transation.index')}}'">Cancel</button>
              </div>



            <!-- Purchase order end -->
          </div>
          <div class="tab-pane " id="technical_approval">
            <!-- Technical Approval start -->
           

            <!-- Technical approval end -->
          </div>
          <div class="tab-pane " id="otherpro">33
          </div>
        
          <div class="tab-pane " id="stock_availiability">55
          </div>
          <div class="tab-pane " id="financial_approval">44
          </div>
          <div class="tab-pane " id="cust_conform">66
          </div>
       </div>
       </div>


           
              </div>
              <!-- /.box-body -->

              <!-- End menu start -->
              <div class="box-body">
     <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#po_details" data-toggle="tab">PO Details</a></li>
			<li class=""><a href="#terms_condition" data-toggle="tab">Terms And Condition</a></li>
     
        
				</ul>
				<div class="tab-content">
				

			<div class="tab-pane active" id="po_details">
        
        
 <div class="panel panel-default">
    <div class="panel-body ">

                   <div class="form-group col-md-12">
                 <h3>PO Details</h3>
                </div>
<div class="form-group col-md-12">
                 <div class="form-group col-md-6">
                  <label for="name">PO Collected Date*</label>
                  <input type="text" disabled="true" id="collect_date" name="collect_date" value="<?php echo date("d-m-Y")?>" class="form-control" placeholder="PO Collected Date">
                  <span class="error_message" id="collect_date_message" style="display: none">Field is required</span>
                </div>

                </div>
                  <div class="form-group col-md-6">
                  <label for="name">Customer Address*</label>
                    <textarea name="user_address" disabled="true" id="user_address" class="form-control" placeholder="Customer Address" readonly></textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6">
                  <label for="name">Shipping Address* </label>
                    <textarea name="user_shipping" disabled="true"  id="user_shipping" class="form-control" placeholder="Shipping Address"></textarea>
                  <span class="error_message" id="user_shipping_message" style="display: none">Field is required</span>
                </div>
               
                    <div class="form-group col-md-2">
                  <label for="name">Contact Person *</label>
                  <select class="form-control" disabled="true" name="contact_id" id="contact_id" onchange="change_contact_id(this.value)">
                    <option value="">Contact Person</option>
                  </select>
                       <div class="loader_contact_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-2">
                  <label for="name">Designation *</label>
                  <select class="form-control" disabled="true" name="designation" id="designation" readonly>
                    <option value="">Designationn</option>
                  </select>
                  <span class="error_message" id="designation_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group col-md-2">
                  <label for="name">Department *</label>
                  <select class="form-control" disabled="true" name="department_id" id="department_id" readonly>
                    <option value="">Department</option>
                  </select>
                  <span class="error_message" id="department_id_message" style="display: none">Field is required</span>
                </div>

                   <div class="form-group col-md-2">
                  <label for="name">Phone*</label>
                  <input type="text" id="contact_phone"  name="contact_phone" value="" class="form-control" placeholder="Phone" readonly>
                  <span class="error_message" id="contact_phone_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-2">
                  <label for="name">Mail*</label>
                  <input type="text" id="contact_mail" name="contact_mail" value="" class="form-control" placeholder="Mail" readonly>
                  <span class="error_message" id="contact_mail_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-2">
                  <label for="name">GST Number*</label>
                  <input type="text" id="gst" name="gst" value="" class="form-control" placeholder="GST Number" readonly>
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>
              

    </div>     
    </div>

    
    <div class="panel panel-default">
          <div class="panel-body ">
    <div class="form-group col-md-6">
                  <label for="name">Owner (Engineer) *</label>
                  <select class="form-control" name="owner" id="owner" disabled="true">
                    <option value="">Owner (Engineer)</option>
                    @foreach($staff as $values)
                    <?php
                    echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="owner_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Secondary Owner (If any) *</label>
                  <select class="form-control" name="second_owner" id="second_owner" disabled="true">
                    <option value="">Secondary Owner (If any)</option>
                    @foreach($staff as $values)
                    <?php
                    echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="second_owner_message" style="display: none">Field is required</span>
                </div>
                       <div class="form-group col-md-6">
                  <label for="name">PO*</label>
                  <input type="text" id="po" name="po" value="" class="form-control" placeholder="PO" disabled="true">
                  <span class="error_message" id="po_message" style="display: none">Field is required</span>
                </div>
                       <div class="form-group col-md-6">
                  <label for="name">PO Date*</label>
                  <input type="text" id="po_date" name="po_date" value="" class="form-control" placeholder="PO Date" disabled="true">
                  <span class="error_message" id="po_date_message" style="display: none">Field is required</span>
                </div>

                 <input type="hidden" id="count_addr_photo" name="count_addr_photo" class="form-control" value="1">

<div class="form-group col-md-8" id="p_row_1">
  <label for="image_name">Attach PO Copy</label>
  <input type="file" id="photo" name="photo[]" accept=".jpg,.jpeg,.png" onchange="loadPreview(this,preview_photo)" class="form-control" disabled="true">
  <p class="help-block">(Allowed Type: jpg,jpeg,png )</p>
  <div id="preview_photo" class="form-group col-md-12 mb-2"></div>

    <span class="error_message" id="photo_message" style="display: none">Field is required</span>
</div>
 <div class="form-group col-md-2">
  <button type="button" disabled="true" class="btn btn-default btn-sm" onclick="addmore_photo()"><span class="glyphicon glyphicon-plus-sign"></span> Add</button>
 </div>
 <div id="addphoto"></div>

                 
                   <div class="form-group col-md-6">
                  <label for="name">Attach GST Certificate / Mail confirmation</label>
                  <input disabled="true" type="file" id="attach_gst" name="attach_gst" value="" class="form-control" placeholder="">
                  <span class="error_message" id="attach_gst_message" style="display: none">Field is required</span>
                </div>

              
              </div>   
              </div> 
              

            <!-- configuration order end -->
          </div>
          <div class="tab-pane " id="terms_condition">
            <!-- podetails Approval start -->
           
           
 <div class="panel panel-default">
    <div class="panel-body ">

                <div class="form-group col-md-12 " >
                 <h3>Configuration</h3>
                </div>
                 
                    <div class="form-group col-md-6 " >
                  <label for="name">Standard Warranty*</label>
                  <input type="text" disabled="true" id="stan_warrenty" name="stan_warrenty" value="" class="form-control" placeholder="Standard Warranty">
                  <span class="error_message" id="stan_warrenty_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6 " >
                  <label for="name">Additional Warranty Description</label>
                  <input type="text" disabled="true" id="add_warrenty" name="add_warrenty" value="" class="form-control" placeholder="Additional Warranty Description">
                  <span class="error_message" id="add_warrenty_message" style="display: none">Field is required</span>
                </div>
             

    </div>      
    </div>




              <div class="panel panel-default">
          <div class="panel-body ">
          <div class="form-group col-md-12">
                 <h3>Payment terms </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Payment terms*</label>
                    <textarea disabled="true" name="payment_terms" id="payment_terms" class="form-control" placeholder="Payment terms"></textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
              
              </div>
              </div> 

              
          <div class="panel panel-default">
          <div class="panel-body ">

              <div class="form-group col-md-12">
                 <h3>Delivery terms </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Specific delivery terms if any*</label>
                    <textarea disabled="true" name="del_terms" id="del_terms" class="form-control" placeholder="Specific delivery terms if any"></textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-6">
                  <label for="name">Expected date of supply*</label>
                  <input type="text" disabled="true" id="expect_date" name="expect_date" value="" class="form-control" placeholder="Expected date of supply">
                  <span class="error_message" id="po_date_message" style="display: none">Field is required</span>
                </div> 

                 <div class="form-group col-md-6">
                  <label for="name">Mode of dispatch</label>
                  <select class="form-control" name="mode_dispatch" id="mode_dispatch" disabled="true">
                    <option value="">Mode of dispatch</option>
                    <option value="By Hand">By Hand</option>
                    <option value="Courier">Courier</option>
                    <option value="Vechicle">Vechicle</option>
                  </select>
                  <span class="error_message" id="mode_dispatch_message" style="display: none">Field is required</span>
                </div>      
                

       </div>
    

       <div class="panel panel-default">
    <div class="panel-body ">

          <div class="form-group col-md-12">
                 <h3>Other terms (Warranty, CMC/AMC rates etc.)  </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Other terms (Warranty, CMC/AMC rates etc.) *</label>
                    <textarea disabled="true" name="other_terms" id="other_terms" class="form-control" placeholder="Other terms (Warranty, CMC/AMC rates etc.)"></textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                <div class="box-footer col-md-12">
                <button type="button" class="btn btn-primary" disabled="true" >Save</button>
              </div>  

       </div>
       </div>



            <!-- podetails approval end -->
          </div>

         
         
       </div>
       </div>
     
           
              </div>
              <!-- End menu end -->
            
            </form>
          </div>
        </div>
      </div>
</section>




<div class="modal fade" id="modal_ship" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Add Shipping Address</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="add_address" id="add_address" method="post" enctype="multipart/form-data">
        
        <div class="form-group  col-md-6">
                  <label for="name">Street Address1</label>
                  <input type="text"   id="shipping_address1" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="{{$values->address1}}">
                
                  <span class="error_message" id="shipping_address1_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Street Address2</label>
                  <input type="text"  id="shipping_address2" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="{{$values->address2}}">
                  <span class="error_message" id="shipping_address2_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="shipping_country_id" name="shipping_country_id" class="form-control">
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
                  <label for="name">State</label>
                  <input type="text" id="shipping_state" name="shipping_state" class="form-control" value="{{$values->state}}" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" name="shipping_city" class="form-control" value="{{$values->city}}" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>
                
                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" value="{{$values->zip}}" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>

       

        </form>
      </div>
      <div class="modal-footer">
      <span class="success_msg" style="display:none;color:green">Data saved successfully</span>
      <button type="button" class="btn btn-primary"   onclick="save_shipping()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modal_ship_address_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Select Shipping Address</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        
      <div class="display_address"></div>

       

        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary"  onclick="add_shipaddress()" >Add</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

 
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

    <script type="text/javascript">

jQuery(document).ready(function() {
  if ($(window).width() <= 1024) {
  }
  else{
    $(".sidebar-toggle").trigger("click");
  }
});

    function validate_from()
      {
        var state_id=$("#state_id").val();
        var district_id=$("#district_id").val();
        var user_id=$("#user_id").val();
        var type_conf=$("#type_conf").val();
        if(state_id=="")
        {
          $("#state_id_message").show();
        }
        else{
          $("#owner_message").hide();
        }
        if(district_id=="")
        {
          $("#district_id_message").show();
        }
        else{
          $("#district_id_message").hide();
        }
        if(user_id=="")
        {
          $("#user_id_message").show();
        }
        else{
          $("#user_id_message").hide();
        }

        if(type_conf=="")
        {
          $("#type_conf_message").show();
        }
        else{
          $("#type_conf_message").hide();
        }
       var myTotal=0;
        $('input[name^="amount[]"]').each(function() {
   // alert( $(this).val())
     myTotal = parseFloat($(this).val())+parseFloat(myTotal);
 });
 
        if(myTotal==0)
        {
          $("#product_error").show();
        }
        else{
          $("#product_error").hide();
        }

        if(state_id!='' && type_conf!='' && user_id!='' &&  district_id!='' && myTotal>0)
        {
          $("#state_id").removeAttr("disabled");
        $("#district_id").removeAttr("disabled");
        $("#user_id").removeAttr("disabled");
        
          $("#frm_transation").submit(); 
        }


      }


     var arr_total = [];

        var arr_product = [];
  var prd_array  = [];
  var old_product = [];
  var opt_product = '';
  var main_product= '';
    function change_conf(typpes)
    {
   
      var tran_type=$("#tran_type").val();
      var user_id=$("#user_id").val();
      if(tran_type!='' && user_id>0)
      {
        if(typpes=="Opportunity")
                {
                  var url = APP_URL+'/staff/change_transation_type_oppurtunity';
                  $.ajax({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            user_id: user_id,
                          },
                          success: function (data)
                          {  
                            var proObj = JSON.parse(data);
                          states_val='';
                          states_val +='<option value="">Select Opportunity</option>';
                          for (var i = 0; i < proObj.length; i++) {
                            states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["oppertunity_name"]+'</option>';
                            }
                            $("#op_id").val(states_val);
                            $("#op_id").html(states_val);
              $('#op_id').selectpicker('refresh');
                          }

                        });
                  $(".op_id").show();
                  $(".product_id").hide();

                }
                else{ $(".op_id_date").hide();
                  $(".op_id").hide();
                  $(".product_id").show();
                    $(".add_oppur_prod").show();
                  
                }
                $("#tran_type_message").hide();
      }
      else{
        $("#type_conf").val('');
        if(tran_type=='')
        {
          $("#tran_type_message").show();
        }
        if(user_id=='')
        {
          $("#user_id_message").show();
        }
        
      }
    }
      function change_tran_type(tran_type)
      {
        $(".conf").show();
        if(tran_type=="Intra State Registered Sales")
          {
            $(".cgst").show();
            $(".sgst").show();
             $(".cess").hide();
             $(".igst").hide();
          }
         if(tran_type=="Intra State Un-Registered Sales")
          {
            $(".cgst").show();
            $(".sgst").show();
             $(".cess").show();
             $(".igst").hide();
          }
        if(tran_type=="InterState Registered Sales")
          {
             $(".igst").show();
             $(".cess").hide();
             $(".cgst").hide();
            $(".sgst").hide();
          }
         if(tran_type=="InterState Un-Registered Sales")
          {
             $(".igst").show();
             $(".cess").show();
             $(".cgst").hide();
            $(".sgst").hide();
          }
        if(tran_type=="InterState Un-Registered Sales")
          {
              $(".cgst").show();
            $(".sgst").show();
            $(".igst").hide();
             $(".cess").hide();
          }
         if(tran_type=="Government Sales Registered")
          {
              $(".cgst").show();
            $(".sgst").show();
              $(".cess").show();
            $(".igst").hide();
          }
      }
    </script>
    <script>
    $('#user_id').select2();
    $('#op_id').select2();
    $('#product_id').select2();
    $('#state_id').select2();
    $('#district_id').select2();
 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  //$('#district_id').selectpicker('refresh');
  //$('#user_id').selectpicker('refresh');
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
            //  $('#district_id').selectpicker('refresh');
          }
        });
  }
  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  //$('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
              }
              $("#user_id").html(states_val);
            //  $('#user_id').selectpicker('refresh');
          }
        });
  }
        function change_user_id(user_id){
$(".loader_user_id").show();
$("#user_id_hidden").val(user_id);
$(".shiplink").show();
$("#product_id").val('');

$("#select2-product_id-container").html('Select Product');
$('select[name=product_id]').val('');
$("#product_id").val('<option value="">Select Product</option>');
$("#product_id").selectpicker("refresh");
$(".op_id").hide();
$(".op_id_date").hide();

$("#select2-op_id-container").html('Select Opportunity');
$('select[name=op_id]').val('');
$("#op_id").val('<option value="">Select Opportunity</option>');
$("#op_id").selectpicker("refresh");

$("#select2-product_id-container").html('Select Product');
$('select[name=product_id]').val('');
$("#product_id").val('<option value="">Select Product</option>');
$("#product_id").selectpicker("refresh");

  var url = APP_URL+'/staff/get_user_all_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id: user_id
          },
          success: function (data)
          {    
            $(".loader_user_id").hide();
             var res = data.split("*");
      var contact = JSON.parse(res[1]);
            var proObj = JSON.parse(res[0]);
            var proObj_opper = JSON.parse(res[2]);
            if(proObj_opper.length>0)
            {
              $("#type_conf").html('<option value="">Select Option</option><option value="Opportunity">Opportunity</option><option value="Product">Product</option>')
            }
            else{
              $("#type_conf").html('<option value="">Select Option</option><option value="Product">Product</option>')
            }
console.log(proObj);
           //proObj[i]["id"]
           $("#custnemdis").html(proObj[0]["business_name"]);
           $("#tran_type").val(proObj[0]["tran_type"]);
           $("#user_id").val(proObj[0]["id"]);
            $("#user_address").val(proObj[0]["address1"]+' '+proObj[0]["address2"]);
             $("#user_shipping").val(proObj[0]["shiping_address"]+' '+proObj[0]["shiping_address2"]);
              $("#gst").val(proObj[0]["gst"]);
            var optionval='';
            optionval +='<option value="">Select Contact</option>';
              for (var i = 0; i < contact.length; i++) {
                optionval +='<option value="'+contact[i]["id"]+'">'+contact[i]["name"]+' '+contact[i]["last_name"]+'</option>';
              }
            $("#contact_id").html(optionval);
          }
        });
  }
      function change_contact_id(id){
$(".loader_contact_id").show();
  var url = APP_URL+'/staff/get_contactperson_all_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            id: id
          },
          success: function (data)
          {    
            $(".loader_contact_id").hide();
             var res = data.split("*");
              var contact = JSON.parse(res[0]);
            var desig=res[1];
            $("#designation").html('<option value="'+contact[0]["designation"]+'">'+desig+'</option>');
             $("#contact_phone").val(contact[0]["mobile"]);
             $("#contact_mail").val(contact[0]["email"]);
          }
        });
  }
      function change_oppertunity(opper_id){
        $(".loader_opp").show();
        var url = APP_URL+'/staff/get_oppurtunitydetails';
        $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            opper_id: opper_id
          },
          success: function (data)
          {    
            $(".loader_opp").hide();
            $(".op_id_date").show();
            var proObj = JSON.parse(data);
            $(".add_oppur_prod").show();
            $("#opp_date").html(proObj[0]["es_order_date"]);
            
          }
        });
  }
 

      function add_oppur_prod()
      {
        $("#state_id").attr('disabled',true);
        $("#district_id").attr('disabled',true);
        $("#user_id").attr('disabled',true);

        $(".loader").show();
       var type_conf=$("#type_conf").val();
      if(type_conf=="Opportunity")
        {
          
          $("#oppur_status").val(1);
         $(".cust_name").hide();
          $(".cust_details").show();
      //  $("#type_conf").html('<option value="">Select Type</option><option value="Product">Product</option>');
         var url         = APP_URL+'/staff/get_opportunity_all_details_transation';
         $("#cmsTable").show();
          var prd_array  = [];
          var product_id=$("#op_id").val();
        
          prd_array.push(product_id);
          $.ajax({
           type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {     $(".loader").hide();
            $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();

               var res = data.split("*11*");
            var proObj = JSON.parse(res[0]);
            var products_warenty= JSON.parse(res[1]);
           
             htmls='';
           var quantity=0;
            var sale_amount=0;
           
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
               }
               quantity    = 1;
                    opt_product = 1;
                    sale_amount = proObj[i]["unit_price"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                  amt = quantity * sale_amount;
            var tax=proObj[i]["tax_percentage"];
             var tran_type=$("#tran_type").val();
             var taxab_amount=amt;
             

             if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  var cgst_per=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  var sgst_per=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var cess_per=0;
                  var igst_per=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    var cgst_per=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    var sgst_per=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    var cess_per=cess;
                  var igst_per=0;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);

                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    var igst_per=igst;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var sgst_per=0;
                    var cess_per=0;
                  var igst_per=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    var cgst_per=0;
                    var sgst_per=0;
                    var cess_per=1;
                  var igst_per=tax;

                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                  amt =taxab_amount+tax_cal;

                  var diffe=amt-proObj[i]["msp"];

                          htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
                         
                          htmlscontent += '<td><input type="text" value="'+quantity+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
                          '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"   class="foc form-control" name="foc[]" data-id="'+proObj[i]["id"]+'" style="width:40px;" onchange="change_foc(this.value,'+proObj[i]["id"]+')">'+
                          '<td><input type="text" value="'+sale_amount+'" name="sale_amount[]" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+

                          '<td><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'" name="hsn[]"  class="hsn" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
                          '<td><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cgst_per+'%'+
                          '<td><input type="text" readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+sgst_per+'%'+
                          '<td><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+igst_per+'%'+
                          '<td><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cess_per+'%';
                         if(proObj[i]["msp"]>0)
                         {
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                         }else{
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                         }
                         
                          
                         htmlscontent +='<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'" data-val="'+diffe.toFixed(2)+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',1)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
             // arr_total.push(amt);
           
          arr_total[proObj[i]["id"]] = amt;
             
           }
           console.log(arr_total);


           var myTotal = 0; 

$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });


               $(".footertr").hide();
               htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
               
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';


           $("#tabledata").append(htmlscontent);
          }
        }); 
      }
      else{
          var url         = APP_URL+'/staff/get_multiple_product_all_details_transation';
        $("#cmsTable").show();
          var prd_array  = [];
        
var product_id=$("#product_id").val();
    //  prd_array.push(product_id);
    var oppur_status=$("#oppur_status").val();
    if(oppur_status==1)
    {
    
    }
    else{
      $(".cust_name").show();
        $(".cust_details").hide();
    }

    $("#select2-product_id-container").html('Select Product');
    $('select[name=product_id]').val('');
    $("#product_id").val('<option value="">Select Product</option>');
    $("#product_id").selectpicker("refresh");
      

         $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {      $(".loader").hide();
            $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();
            var res = data.split("*11*");
            var proObj = JSON.parse(res[0]);
            var products_warenty=JSON.parse(res[2]);
             htmls='';
           var quantity=0;
            var sale_amount=0;
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
              }
               quantity    = 1;
                    opt_product = 1;
                    sale_amount = proObj[i]["unit_price"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                
                  amt = quantity * sale_amount;
                //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
                   var tax=proObj[i]["tax_percentage"];
                var tran_type=$("#tran_type").val();
                          
                var taxab_amount=amt;
                  if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  var cgst_per=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  var sgst_per=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var cess_per=0;
                  var igst_per=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    var cgst_per=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    var sgst_per=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    var cess_per=cess;
                  var igst_per=0;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);

                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    var igst_per=igst;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var sgst_per=0;
                    var cess_per=0;
                  var igst_per=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    var cgst_per=0;
                    var sgst_per=0;
                    var cess_per=1;
                  var igst_per=tax;

                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                  amt =taxab_amount+tax_cal;

                  var diffe=amt-res[1];
               
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
              
              htmlscontent += '<td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" name="quantity[]" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
              '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"  name="foc[]"  class="foc form-control" data-id="'+proObj[i]["id"]+'" style="width:40px;" onchange="change_foc(this.value,'+proObj[i]["id"]+')">'+ 
              '<td><input type="text" name="sale_amount[]" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+
               '<td><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'"  class="hsn" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
               '<td><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cgst_per+'%'+
               '<td><input type="text"  readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+sgst_per+'%'+
               '<td><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+igst_per+'%'+
               '<td><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cess_per+'%';
               
               if(res[1]>0)
                {
                htmlscontent +='<td><input type="text" readonly="true" value="'+res[1]+'" data-val="'+res[1]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                }else{
                htmlscontent +='<td><input type="text" readonly="true" value="'+res[1]+'" data-val="'+res[1]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                }

               htmlscontent +=
               '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" data-val="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',0)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
              //arr_total.push(amt);
              arr_total[proObj[i]["id"]] = amt;
           }
           console.log(arr_total);

           var myTotal = 0; 

            $('input[name^="amt"]').each(function() {
                myTotal = parseFloat($(this).val())+parseFloat(myTotal);
            });
            var tot_cgst=0
            $('input[name^="cgst"]').each(function() {
              tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
            });
            var tot_sgst=0
            $('input[name^="sgst"]').each(function() {
              tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
            });
            var tot_igst=0
            $('input[name^="igst"]').each(function() {
              tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
            });
            var tot_cess=0
            $('input[name^="cess"]').each(function() {
              tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
            });
            var tot_taxable=0
            $('input[name^="taxable_amount"]').each(function() {
              tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
            });

      $(".footertr").hide();

      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

          }
        }); 
        var tot_products=[];
        tot_products.push(product_id);
            $('input[name^="product_id"]').each(function() {
              tot_products.push($(this).val());
             
              
            });
      
            
        var url         = APP_URL+'/staff/get_sort_product_transaction';
        $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: tot_products,
          },
          success: function (data)
          {  
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='';
            states_val +='<option value="">Select Products</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#product_id").html(states_val);
              $('#product_id').selectpicker('refresh');

          }

        });

        }

       
      
      }


      function change_qty(qty,product_id)
{

var foc=$("#foc_"+product_id).val();
if(foc>0)
  {
    var quantity   = parseInt(qty)-parseInt(foc);
  }
  else{
    var quantity   = qty;
  }

        var product_id = product_id;
        var tran_type=$("#tran_type").val();
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
              for (var i = 0; i < proObj.length; i++) {
                var  sale_amount = proObj[i]["unit_price"];;
                var amt    = quantity * sale_amount;
               var taxab_amount=amt;
               // taxab_amount taxable_amount
                var tax=proObj[i]["tax_percentage"];
              
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);

                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }

                  var amt=taxab_amount+tax_cal;
                  var one_su_plus=$("#surplus_amt_"+product_id).attr('data-val');
                  var one_msp=$("#msp_"+product_id).attr('data-val');
               
                  $("#surplus_amt_"+product_id).val(one_su_plus*quantity);
                $("#msp_"+product_id).val(one_msp*quantity);

               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
                $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                $("#taxable_amount_"+product_id).val(taxab_amount);
                
             //   $("#sa_"+product_id).val(amt);
                $(".hqn_"+product_id).val(qty);
              //  $(".hamt_"+product_id).val(amt);
               // $(".hsa_"+product_id).val(amt);

               var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });


      $(".footertr").hide();

      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                
                '<td>'+tot_taxable+'</td>'+
               
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            
              }
            }
        });
}


function change_foc(qty,product_id)
{
  var total_qty=$("#qn_"+product_id).val();
/*if(qty>total_qty)
{*/

 
 if(qty>0)
  {
    var quantity   = parseInt(total_qty)-parseInt(qty);
  }
  else{
    var quantity   = total_qty;
  }

        var product_id = product_id;
        var tran_type=$("#tran_type").val();
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
            
              for (var i = 0; i < proObj.length; i++) {
                var  sale_amount = proObj[i]["unit_price"];;
                var amt    = quantity * sale_amount;
                var taxab_amount=amt;
               // alert(amt)
                var tax=proObj[i]["tax_percentage"];
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);

                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
   amt =taxab_amount+tax_cal;

               
             
                $("#am_"+product_id).val(amt);
             $(".hqn_"+product_id).val(qty);
             $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                $("#taxable_amount_"+product_id).val(taxab_amount);
             

               var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });


      $(".footertr").hide();

      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
                '<td>'+tot_taxable+'</td>'+
            
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            
              }
            }
        });

/*}
else{
  $("#foc_"+product_id).val(0);
 alert("Please check quantity ")
}*/
 



}


function change_sale_amt(sale_amount,product_id)
{
       var product_id = product_id;
        var sale_amount=sale_amount;
        var tran_type=$("#tran_type").val();
        var foc=$("#foc_"+product_id).val();
        if(foc>0)
          {
            var quantity   = parseInt(qty)-parseInt(foc);
          }
          else{
            var quantity   = $("#qn_"+product_id).val();;
          }
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
              for (var i = 0; i < proObj.length; i++) {
                var amt    = quantity * proObj[i]["unit_price"];
                var unit_price=proObj[i]["unit_price"];
                var max_sale_amount=proObj[i]["max_sale_amount"];
                var min_sale_amount=proObj[i]["min_sale_amount"];
                
                //alert(unit_price);
                 max_sale_amount=parseInt(max_sale_amount);
                  min_sale_amount=parseInt(min_sale_amount);
                  unit_price=parseInt(unit_price);
                  sale_amount= $("#sa_"+product_id).val();
               
              
           

                var tax=proObj[i]["tax_percentage"];
                var sale_amount= $("#sa_"+product_id).val();
               var quantity= $("#qn_"+product_id).val();
                var amt    = quantity * sale_amount;

                var diffe=$("#sa_"+product_id).val()-$("#msp_"+product_id).val();

                var taxab_amount=amt;

                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
               
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);

                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }

               amt =amt+tax_cal;

               var one_su_plus=$("#surplus_amt_"+product_id).attr('data-val');
                  var one_msp=$("#msp_"+product_id).attr('data-val');
               
                  $("#surplus_amt_"+product_id).val(one_su_plus*quantity);
                $("#msp_"+product_id).val(one_msp*quantity);

              
               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
                $(".hsa_"+product_id).val(amt);

                $("#am_"+product_id).val(amt);
                $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                
                $("#surplus_amt_"+product_id).val(diffe);
              }

              var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });

      $(".footertr").hide();

      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

            }
        });
}



      function deletepro(product_id,types)
    {
      
      $("#op_id").val('');
      $('#op_id').select2();
      $("#op_id").val('-- Select Opportunity --');
      for( var i = 0; i < old_product.length; i++){ if ( old_product[i] === product_id) { old_product.splice(i, 1); i--; }}
      var count_product=$("#count_product").val();
      var add_counts=parseInt(count_product)-1;
      $("#count_product").val(add_counts); 
      $(".tr_"+product_id).remove();
      //alert(arr_product.length);
      for( var i = 0; i <= arr_product.length; i++){ 
        if ( arr_product[i] == product_id) { 
         // alert(arr_product[i]);
          arr_product.splice(i, 1); 
        }
      }
    
     // arr_total.splice(product_id, 1);
      

      var oppur_id=$("#oppur_id").val();
      console.log(oppur_id+'---'+product_id)
     if(types==1){
      $("#oppur_status").val('');
      $("#oppur_id").val('');
     // $("#type_conf").html('<option value="">Select Type</option><option value="Product">Product</option><option value="Opportunity">Opportunity</option>');
     }else{
      $(".cust_name").show();
        $(".cust_details").hide();
     }

      var myTotal = 0; 
      delete arr_total[product_id]; 



      $(".footertr").hide();

      var myTotal = 0; 

$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
$('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
});
var tot_sgst=0
$('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
});
var tot_igst=0
$('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
});
var tot_cess=0
$('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
});
var tot_taxable=0
$('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
});

$(".footertr").hide();

htmlscontent='<tr class="footertr">'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+

  
    '<td>'+tot_cgst.toFixed(2)+'</td>'+
    '<td>'+tot_sgst.toFixed(2)+'</td>'+
    '<td>'+tot_igst.toFixed(2)+'</td>'+
    
    '<td>'+tot_cess.toFixed(2)+'</td>'+
    '<td></td>'+
    '<td></td>'+
   '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

      if(myTotal==0)
      {
        $("#cmsTable").hide();
        $(".cust_name").hide();
        $(".cust_details").hide();
        $("#user_address").val('');
        $("#designation").val('');
        $("#contact_phone").val('');
        $("#contact_mail").val('');
        $("#contact_id").val('');
        
        
      }
      if(add_counts=="0"){
            $("#preview_btn").hide();
            $("#save_btn").hide();
            $(".noresult").show();
            $("#user_id").val('');
            $("#product_id").val('');
            $("#user_id").attr('disabled', false);
      }
    }

    
 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  $('#district_id').selectpicker('refresh');
  $('#user_id').selectpicker('refresh');

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
            states_val +='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
              $('#district_id').selectpicker('refresh');
              
           
          }
        });

  }


  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  $('#user_id').selectpicker('refresh');

  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
           
              }
              $("#user_id").html(states_val);
             
              $('#user_id').selectpicker('refresh');
           
          }
        });

  }


  function add_shipping(){

    var shipping_address1=$("#shipping_address1").val('');
  var shipping_address2=$("#shipping_address2").val('');
  var shipping_country_id=$("#shipping_country_id").val('');
  var shipping_city=$("#shipping_city").val('');
  var shipping_state=$("#shipping_state").val('');
  var shipping_zip=$("#shipping_zip").val('');

    $(".success_msg").hide();
    $("#modal_ship").modal("show");
  }

    function select_shipping(){
      var user_id=$("#user_id").val();

        var user_id=$("#user_id").val();
  if(user_id==0)
  {
    var  user_id=$("#user_id_hidden").val();
  }

   
     var url = APP_URL+'/staff/select_shipping_address_user';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id:user_id
          },
          success: function (data)
          {  
             
            $(".display_address").html(data);

            $("#modal_ship_address_view").modal("show");

          }
   });

    }

  

function save_shipping()
{
  var shipping_address1=$("#shipping_address1").val();
  var shipping_address2=$("#shipping_address2").val();
  var shipping_country_id=$("#shipping_country_id").val();
  var shipping_city=$("#shipping_city").val();
  var shipping_state=$("#shipping_state").val();
  var shipping_zip=$("#shipping_zip").val();

  if(shipping_address1=="")
  {
    $("#shipping_address1_message").show();
  }
  else{
    $("#shipping_address1_message").hide();
  }
  if(shipping_country_id=="")
  {
    $("#shipping_country_id_message").show();
  }
  else{
    $("#shipping_country_id_message").hide();
  }
  if(shipping_city=="")
  {
    $("#shipping_city_message").show();
  }
  else{
    $("#shipping_city_message").hide();
  }
  if(shipping_state=="")
  {
    $("#shipping_state_message").show();
  }
  else{
    $("#shipping_state_message").hide();
  }

  if(shipping_zip=="")
  {
    $("#shipping_zip_message").show();
  }
  else{
    $("#shipping_zip_message").hide();
  }


  var user_id=$("#user_id").val();
  if(user_id==0)
  {
    var  user_id=$("#user_id_hidden").val();
  }

if(user_id!='' && shipping_zip!='' && shipping_state!='' && shipping_city!='' && shipping_country_id!='' && shipping_address1!='')
{
  var url = APP_URL+'/staff/save_shipping_address_user';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id:user_id,shipping_address1: shipping_address1,shipping_address2:shipping_address2,shipping_country_id:shipping_country_id,shipping_city:shipping_city,shipping_state:shipping_state,shipping_zip:shipping_zip
          },
          success: function (data)
          {  
            $(".success_msg").show();
            var shipping_address1=$("#shipping_address1").val('');
            var shipping_address2=$("#shipping_address2").val('');
            var shipping_country_id=$("#shipping_country_id").val('');
            var shipping_city=$("#shipping_city").val('');
            var shipping_state=$("#shipping_state").val('');
            var shipping_zip=$("#shipping_zip").val('');

          }
   });

}
    

  
}

  function add_shipaddress()
    {
      var address_option = $("input[name='address_option']:checked").val();
    
      var address=$("#addrsec"+address_option).html();
      $("#user_shipping").val(address);
      $("#modal_ship").modal("hide");
    }


$(document).ready(function() {
 
    $("#state_id").selectpicker({
      enableFiltering: true,
    });

    $("#user_id").selectpicker({
      enableFiltering: true,
    });
    $("#district_id").selectpicker({
      enableFiltering: true,
    });
});

              
function add_contact()
{
  var user_id=$("#user_id").val();
  if(user_id==0)
  {
    var  user_id=$("#user_id_hidden").val();
  }
  $(".addlink").attr("href", "http://dentaldigital.in/staff/customer/"+user_id+"/edit");
                
  
}

function addmore_photo()
  {
    var count_addr_photo = $("#count_addr_photo").val();
    var add_count        = parseInt(count_addr_photo)+1;
    $("#count_addr_photo").val(add_count);

    var htmls='<div class="form-group col-md-8" id="p_row_'+add_count+'">'+
                  '<input type="file" id="photo'+add_count+'" name="photo[]" accept=".jpg,.jpeg,.png" onchange="loadPreview(this,preview_photo'+add_count+')" class="form-control">'+
                  '<p class="help-block">(Allowed Type: jpg,jpeg,png )</p>'+
                  '<div id="preview_photo'+add_count+'" class="form-group col-md-12 mb-2"></div>'+

                    '<span class="error_message" id="photo_message" style="display: none">Field is required</span>'+
                '</div>'+
                '  <div class="form-group  col-md-4" id="pr_row_'+add_count+'">'+
               ' <button type="button" class="btn btn-danger" onClick="remove_photo('+add_count+')">Remove</button>'+
               ' </div>';
    $("#addphoto").append(htmls);
  }
  function remove_photo(row_no)
  {
    var count_addr=$("#count_addr_photo").val();
    var add_count=parseInt(count_addr)-1;
    $("#count_addr_photo").val(add_count);
    $("#p_row_"+row_no).remove();
    $("#pr_row_"+row_no).remove();
  }
  </script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#po_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0  
        });

$('#collect_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0  
        });
$('#expect_date').datepicker({
    //dateFormat:'yy-mm-dd',
    dateFormat:'yy-mm-dd',
    minDate: 0  
});                  
</script>
  <style>
.disabled {
    pointer-events:none; 
    opacity:0.6;        
}
  </style>
@endsection