@extends('staff/layouts.app')
@section('title', 'Edit Transaction')
@section('content')
<section class="content-header">
      <h1>
      Edit Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.transation.index')}}">Manage Transaction</a></li>
        <li class="active">Edit Transaction</li>
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

            <form role="form" name="frm_brand" id="frm_brand" method="post" action="{{ route('staff.transation.update', $transation->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}

                <div class="box-body">


                  <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li id="purchase_order_click" ><a class="purchase_order_click" href="#purchase_order" data-toggle="tab">Sales order</a></li>

					<li class="active"><a href="#technical_approval" data-toggle="tab">Technical Approval</a></li>
          <li><a href="#otherpro" data-toggle="tab">MSP,Payout,otherprovisions if any</a></li>
          
          <li><a href="#stock_availiability" data-toggle="tab">Stock Availiability</a></li>
          <li><a href="#financial_approval" data-toggle="tab">Financial Approval</a></li>
          <li><a href="#cust_conform" data-toggle="tab">Customer Confirmation on COD/Site readness</a></li>

				</ul>
				<div class="tab-content">


					<div class="tab-pane " id="purchase_order">
            <!-- Purchase Order start  -->
            <div class="panel panel-default">
    <div class="panel-body ">

                   <div class="form-group col-md-12">
                    <label >Source*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Opportunity" @if($transation->status=="Online") checked="true" @endif>
                      Online
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Call" @if($transation->status=="Call") checked="true" @endif>
                      Call
                    </label>
                  </div>
                    <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="E-Mail" @if($transation->status=="E-Mail") checked="true" @endif>
                      E-Mail
                    </label>
                  </div>



                      <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="PO" @if($transation->status=="PO") checked="true" @endif>
                      PO
                    </label>
                  </div>
                       <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Mail" @if($transation->status=="Mail") checked="true" @endif>
                      Mail
                    </label>
                  </div>
                </div>

                </div>
                  </div>


 <div class="panel panel-default">
    <div class="panel-body ">

                      <div class="form-group  col-md-3">
                    <label>Company*</label>
                    <select name="company_id" id="company_id" class="form-control" @if($transation->approval_company=="Y") disabled="true" @endif>
                      <option value="">-- Select Company --</option>
                      <?php
                      foreach($company as $item) {
                        $sel = ($transation->company_id == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                      } ?>
                    </select>
                    <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-group col-md-3">
                    <label for="name">Type *</label>
                    <select class="form-control" name="tran_type" id="tran_type" @if($transation->approval_company=="Y") disabled="true" @endif>
                      <option value="">Type</option>
                    <option value="Intra State Registered Sales" <?php echo ($transation->tran_type == "Intra State Registered Sales") ? 'selected': ''?>>Intra State Registered Sales</option>
                        <option value="Intra State Un-Registered Sales" <?php echo ($transation->tran_type == "Intra State Un-Registered Sales") ? 'selected': ''?>>Intra State Un-Registered Sales</option>
                        <option value="InterState Registered Sales" <?php echo ($transation->tran_type == "InterState Registered Sales") ? 'selected': ''?>>InterState Registered Sales</option>
                        <option value="InterState Un-Registered Sales" <?php echo ($transation->tran_type == "InterState Un-Registered Sales") ? 'selected': ''?>>InterState Un-Registered Sales</option>
                        <option value="Government Sales Registered" <?php echo ($transation->tran_type == "Government Sales Registered") ? 'selected': ''?>>Government Sales Registered</option>
                        <option value="Government Sales Unregistered" <?php echo ($transation->tran_type == "Government Sales Unregistered") ? 'selected': ''?>>Government Sales Unregistered</option>
                      <option value="Export Sales" <?php echo ($transation->tran_type == "Export Sales") ? 'selected': ''?>>Export Sales</option>
                      <option value="Sample Supply" <?php echo ($transation->tran_type == "Sample Supply") ? 'selected': ''?>>Sample Supply</option>
                      <option value="Demo Process" <?php echo ($transation->tran_type == "Demo Process") ? 'selected': ''?>>Demo Process</option>
                    </select>
                    <span class="error_message" id="tran_type_message" style="display: none">Field is required</span>
                    </div>
                  <div class="form-group col-md-3">
                  <label for="name">Select Option *</label>
                  <select class="form-control" name="type_conf" id="type_conf" onchange="change_conf(this.value)" @if($transation->approval_product=="Y") disabled="true" @endif>
                  <option value="">Select Option</option>
                  <option value="Opportunity">Opportunity</option>
                      <option value="Product">Product</option>
                  </select>
                  <span class="error_message" id="tran_type_message" style="display: none">Field is required</span>
                </div>

                    <div class="form-group col-md-3 op_id" style="display:none;">
                        <label>Opportunity*</label>
                        <select name="op_id" id="op_id" class="form-control op_id" onchange="change_oppertunity(this.value)">
                          <option value="">-- Select Opportunity --</option>
                          @if(sizeof($op_name)>0)
                          @foreach($op_name as $item)
                              <option value="{{$item->id}}" >{{$item->oppertunity_name}}</option>
                          @endforeach
                          @endif
                        </select>
                        <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                        <div class="loader_opp" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                      </div>
                   <div class="form-group  col-md-1 op_id_date" style="display: none">
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


               <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Additional Warranty</th>

                    <th>Qty</th>
                    <th>Sale Amount</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                      <th>FOC</th>
                        <th>MSP</th>
                    <th>Net Amount</th>
                  <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {$k=0;
                  foreach($transation_product as $values)
                  {
                    if($values->product_id>0){

                      $product = App\Product::find($values->product_id);

                    }


                    if($product->image_name==null || $product->image_name=='')
                    {
                      $imgs=asset('images/no-image.jpg');

                    }
                    else{
                      $imgs=asset('storage/app/public/products/thumbnail/'.$product->image_name);
                    }

                ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">

                    <td><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
               <td>
              <select name="warrenty_product[]" id="warrenty_product_{{$values->product_id}}"  @if($transation->approval_product=="Y") disabled="true" @endif>
              <option value="">Additional Warranty</option>
              <?php
              if(count($products_warenty)>0)
              {
                foreach($products_warenty as $values_warenty)
                {
              ?>
                <option value="{{$values_warenty->id}}">{{$values_warenty->name}}</option>
                <?php
                }
              }
                ?>

              </select>
             </td>
              <td><input type="text" @if($transation->approval_product=="Y") readonly="true" @endif value="{{$values->quantity}}" id="qn_{{$values->product_id}}" name="quantity[]" class="quantity" onchange="change_qty(this.value,{{$values->product_id}})" data-id="{{$values->product_id}}" style="width:40px;"></td>
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  name="sale_amount[]" value="{{$values->sale_amount}}" id="sa_{{$values->product_id}}" onchange="change_sale_amt(this.value,{{$values->product_id}})" class="sale_amt" data-id="{{$values->product_id}}" style="width:60px;">
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->hsn}}" id="hsn_{{$values->product_id}}"  class="hsn" name="hsn[]" data-id="{{$values->product_id}}" style="width:70px;">
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->cgst}}" id="cgst_{{$values->product_id}}"  class="cgst" name="cgst[]" data-id="{{$values->product_id}}" style="width:40px;">%
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->sgst}}" id="sgst_{{$values->product_id}}}"  class="sgst" name="sgst[]" data-id="{{$values->product_id}}" style="width:40px;">%
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->igst}}" id="igst_{{$values->product_id}}"  class="igst" name="igst[]" data-id="{{$values->product_id}}" style="width:40px;">%
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->cess}}" id="cess_{{$values->product_id}}"  class="cess" name="cess[]" data-id="{{$values->product_id}}" style="width:40px;">%
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->foc}}" id="foc_{{$values->product_id}}"  name="foc[]"  class="foc" data-id="{{$values->product_id}}" style="width:40px;">
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->msp}}" id="msp_{{$values->product_id}}"  class="msp" name="msp[]" data-id="{{$values->product_id}}" style="width:40px;">
               <div style="display:none;" class="error_message error_sale_{{$values->product_id}}"></div></td>
               <td><input type="text"  @if($transation->approval_product=="Y") readonly="true" @endif  value="{{$values->amt}}" id="am_{{$values->product_id}}" class="amt" name="amt[]" data-id="{{$values->product_id}}" readonly></td>
               <td>@if($transation->approval_product=="N") <a class="btn btn-danger btn-xs " onclick="deletepro({{$values->product_id}},{{$k}})" data-id="{{$values->product_id}}"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a> @endif</td>
               <input type="hidden" name="product_id[]" value="{{$values->product_id}}"><input type="hidden" name="quantity[]" value="{{$values->quantity}}" class="hqn_{{$values->product_id}}">
               <input type="hidden" name="amount[]" value="{{$values->amt}}" class="hamt_{{$values->product_id}}">
               <input type="hidden" name="sale_amount[]" value="{{$values->sale_amount}}" class="hsa_{{$values->product_id}}"><input type="hidden" name="company[]" value="">
               <input type="hidden" name="optional[]" value=""><input type="hidden" name="main_pdt[]" value="">
               <input type="hidden" name="transation_product_id[]" value="{{$values->id}}"></tr>

                    </tr>

                    <?php
                    $k++;
                  }

                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>



</div>
</div>



 <div class="panel panel-default">
    <div class="panel-body ">

                <div class="form-group col-md-12 " >
                 <h3>Configuration</h3>
                </div>
                   <div class="form-group col-md-6 cgst" style="display: none">
                  <label for="name">CGST</label>
                  <input type="text" id="cgst" name="cgst_conf" value="" class="form-control" placeholder="CGST">
                  <span class="error_message" id="cgst_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-6 sgst" style="display: none">
                  <label for="name">SGST</label>
                  <input type="text" id="sgst" name="sgst_conf" value="" class="form-control" placeholder="SGST">
                  <span class="error_message" id="sgst_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-6 cess" style="display: none">
                  <label for="name">Cess*</label>
                  <input type="text" id="cess" name="cess_conf" value="" class="form-control" placeholder="Cess">
                  <span class="error_message" id="cess_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6 igst" style="display: none">
                  <label for="name">Igst*</label>
                  <input type="text" id="igst" name="igst_conf" value="" class="form-control" placeholder="Igst">
                  <span class="error_message" id="cess_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6 " >
                  <label for="name">Standard Warranty*</label>
                  <input @if($transation->approval_config=="Y") readonly="true" @endif type="text" id="stan_warrenty" name="stan_warrenty" value="{{$transation->stan_warrenty}}" class="form-control" placeholder="Standard Warranty">
                  <span class="error_message" id="stan_warrenty_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6 " >
                  <label for="name">Additional Warranty Description</label>
                  <input @if($transation->approval_config=="Y") readonly="true" @endif type="text" id="add_warrenty" name="add_warrenty" value="{{$transation->add_warrenty}}" class="form-control" placeholder="Additional Warranty Description">
                  <span class="error_message" id="add_warrenty_message" style="display: none">Field is required</span>
                </div>
    </div>
    </div>

 <div class="panel panel-default">
    <div class="panel-body ">

                   <div class="form-group col-md-12">
                 <h3>PO Details</h3>
                </div>
<div class="form-group col-md-12">
                 <div class="form-group col-md-6">
                  <label for="name">PO Collected Date*</label>
                  <input @if($transation->approval_customer=="Y") readonly="true" @endif type="text" id="collect_date" name="collect_date" value="{{$transation->collect_date}}" class="form-control" placeholder="PO Collected Date">
                  <span class="error_message" id="collect_date_message" style="display: none">Field is required</span>
                </div>


                   <div class="form-group col-md-2 cust_name" >
                  <label>State*</label>
                  <select @if($transation->approval_customer=="Y") disabled="true" @endif name="state_id" id="state_id" class="form-control selectpicker" data-live-search="true" onchange="change_state()">
                    <option value="">-- Select State --</option>
                    <?php

                    foreach($state as $item) {
                      $sel = ($transation->state_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-2 cust_name" >
                  <label>District*</label>
                  <select @if($transation->approval_customer=="Y") disabled="true" @endif name="district_id" id="district_id" class="form-control selectpicker" data-live-search="true" onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php

                    foreach($district as $item) {
                      $sel = ($transation->district_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group col-md-2 cust_details" style="display:none;">
                  <label for="name">Customer Name *</label>

                  <span id="custnemdis"></span>
                  </div>
                   <div class="form-group col-md-2 cust_name" >
                  <label for="name">Customer Name *</label>
                  <input type="hidden" name="user_id_hidden" id="user_id_hidden">
                  <input type="hidden" name="oppur_id" id="oppur_id">
                  <input type="hidden" name="oppur_status" id="oppur_status">
                  <select class="form-control" name="user_id" id="user_id" @if($transation->approval_customer=="Y") disabled="true" @endif onchange="change_user_id(this.value)">
                    <option value="">Customer Name</option>
                    @foreach($user as $values)
                    <?php
                       $sel = ($transation->user_id == $values->id) ? 'selected': '';
                    echo '<option value="'.$values->id.'" '.$sel.'>'.$values->business_name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="tran_type_message" style="display: none">Field is required</span>
                     <div class="loader_user_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                </div>
                </div>
                  <div class="form-group col-md-6">
                  <label for="name">Customer Address*</label>
                    <textarea @if($transation->approval_customer=="Y") readonly="true" @endif name="user_address" id="user_address" class="form-control" placeholder="Customer Address" readonly>{{$transation->user_address}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6">
                  <label for="name">Shipping Address* <a onclick="add_shipping()" class="shiplink" style="display:none;">Add / </a>  <a class="shiplink" onclick="select_shipping()" style="display:none;">Select Shipping Address</a></label>
                    <textarea @if($transation->approval_customer=="Y") readonly="true" @endif name="user_shipping" id="user_shipping" class="form-control" placeholder="Shipping Address">{{$transation->user_shipping}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>

                    <div class="form-group col-md-2">
                  <label for="name">Contact Person *<a href="" target="_blank" class="shiplink addlink" onclick="add_contact()" style="display:none;">Add Contact</a></label>
                  <select @if($transation->approval_customer=="Y") readonly="true" @endif class="form-control" name="contact_id" id="contact_id" onchange="change_contact_id(this.value)">
                    <option value="">Contact Person</option>
                  </select>
                       <div class="loader_contact_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-2">
                  <label for="name">Designation *</label>
                  <select @if($transation->approval_customer=="Y") readonly="true" @endif class="form-control" name="designation" id="designation" readonly>
                    <option value="">Designationn</option>
                  </select>
                  <span class="error_message" id="designation_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-2">
                  <label for="name">Phone*</label>
                  <input @if($transation->approval_customer=="Y") readonly="true" @endif type="text" id="contact_phone" name="contact_phone" value="{{$transation->contact_phone}}" class="form-control" placeholder="Phone" readonly>
                  <span class="error_message" id="contact_phone_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-2">
                  <label for="name">Mail*</label>
                  <input @if($transation->approval_customer=="Y") readonly="true" @endif type="text" id="contact_mail" name="contact_mail" value="{{$transation->contact_mail}}" class="form-control" placeholder="Mail" readonly>
                  <span class="error_message" id="contact_mail_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-2">
                  <label for="name">GST Number*</label>
                  <input @if($transation->approval_customer=="Y") readonly="true" @endif type="text" id="gst" name="gst" value="{{$transation->gst}}" class="form-control" placeholder="GST Number" readonly>
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>

    </div>
    </div>


    <div class="form-group col-md-6">
                  <label for="name">Owner (Engineer) *</label>
                  <select class="form-control" name="owner" id="owner">
                    <option value="">Owner (Engineer)</option>
                    @foreach($staff as $values)
                    <?php
                     $sel = ($transation->owner == $values->id) ? 'selected': '';
                    echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="owner_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-6">
                  <label for="name">Secondary Owner (If any) *</label>
                  <select class="form-control" name="second_owner" id="second_owner">
                    <option value="">Secondary Owner (If any)</option>
                    @foreach($staff as $values)
                    <?php
                      $sel = ($transation->second_owner == $values->id) ? 'selected': '';
                    echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="second_owner_message" style="display: none">Field is required</span>
                </div>
                       <div class="form-group col-md-6">
                  <label for="name">PO*</label>
                  <input type="text" id="po" name="po" value="{{$transation->po}}" class="form-control" placeholder="PO">
                  <span class="error_message" id="po_message" style="display: none">Field is required</span>
                </div>
                       <div class="form-group col-md-6">
                  <label for="name">PO Date*</label>
                  <input type="text" id="po_date" name="po_date" value="{{$transation->po_date}}" class="form-control" placeholder="PO Date">
                  <span class="error_message" id="po_date_message" style="display: none">Field is required</span>
                </div>

                 <input type="hidden" id="count_addr_photo" name="count_addr_photo" class="form-control" value="1">

<div class="form-group col-md-8" id="p_row_1">
  <label for="image_name">Attach PO Copy</label>
  <input type="file" id="photo" name="photo[]" accept=".jpg,.jpeg,.png" onchange="loadPreview(this,preview_photo)" class="form-control">
  <p class="help-block">(Allowed Type: jpg,jpeg,png )</p>
  <div id="preview_photo" class="form-group col-md-12 mb-2"></div>

    <span class="error_message" id="photo_message" style="display: none">Field is required</span>
</div>
 <div class="form-group col-md-2">
  <button type="button" class="btn btn-default btn-sm" onclick="addmore_photo()"><span class="glyphicon glyphicon-plus-sign"></span> Add</button>
 </div>
 <div id="addphoto"></div>

                   <!-- <div class="form-group col-md-6">
                  <label for="name">Attach PO Copy</label>
                  <input type="file" id="attach_copy" name="attach_copy" value="" class="form-control" placeholder="" multiple>
                  <span class="error_message" id="attach_copy_message" style="display: none">Field is required</span>
                </div> -->
                   <div class="form-group col-md-6">
                  <label for="name">Attach GST Certificate / Mail confirmation</label>
                  <input type="file" id="attach_gst" name="attach_gst" value="{{$transation->attach_gst}}" class="form-control" placeholder="">
                  <span class="error_message" id="attach_gst_message" style="display: none">Field is required</span>
                </div>

                   <div class="form-group col-md-12">
                 <h3>Payment terms </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Payment terms*</label>
                    <textarea name="payment_terms" id="payment_terms" class="form-control" placeholder="Payment terms">{{$transation->payment_terms}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-12">
                 <h3>Delivery terms </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Specific delivery terms if any*</label>
                    <textarea name="del_terms" id="del_terms" class="form-control" placeholder="Specific delivery terms if any">{{$transation->del_terms}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                   <div class="form-group col-md-6">
                  <label for="name">Expected date of supply*</label>
                  <input type="text" id="expect_date" name="expect_date" value="{{$transation->expect_date}}" class="form-control" placeholder="Expected date of supply">
                  <span class="error_message" id="po_date_message" style="display: none">Field is required</span>
                </div>
                    <div class="form-group col-md-12">
                 <h3>Other terms (Warranty, CMC/AMC rates etc.)  </h3>
                </div>
                   <div class="form-group col-md-12">
                  <label for="name">Other terms (Warranty, CMC/AMC rates etc.) *</label>
                    <textarea name="other_terms" id="other_terms" class="form-control" placeholder="Other terms (Warranty, CMC/AMC rates etc.)">{{$transation->other_terms}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>

                  <div class="box-footer col-md-12">
                <button type="submit" class="btn btn-primary"  >Submit</button>
                <!-- onclick="validate_from()" -->
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.transation.index')}}'">Cancel</button>
              </div>



            <!-- Purchase order end -->
          </div>
          <div class="tab-pane active" id="technical_approval">
            <!-- Technical Approval start -->
            <div class="col-md-6">

              <div class="form-group col-md-12">
                <div class="panel panel-default">
                <div class="panel-body ">
                <div class="form-group col-md-6">
                    <h3>Company</h3>
                     <?php
                        if( $transation->company_id>0){
                          $company = App\Company::find($transation->company_id);
                          if($company){
                            echo $company->name;
                          }
                      }
                     ?>
                </div>

                 <div class="form-group col-md-6">
                  <h3>Type</h3>
                  {{$transation->tran_type}}
                </div>

                <div class="form-group col-md-12  text-center" >
                  <?php
                  if($transation->approval_company=="N")
                  {
                    echo '<button type="button" class="btn btn-primary approval_company_approval"  onclick="approval_company()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary approval_company_approved" style="display:none;"  >Approved</button>';
                    echo '<button type="button" class="btn btn-warning"  onclick="edit_purchase()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';

                  }
                  ?>


                </div>

                </div>
                </div>

                <div class="panel panel-default">
                <div class="panel-body ">
                <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>

                    <th>Name</th>
                    <th>Qty</th>
                    <th>Sale Amount</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                      <th>FOC</th>
                        <th>MSP</th>
                    <th>Net Amount</th>

                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {
                  foreach($transation_product as $values)
                  {

                    if( $values->product_id>0){
                      $product = App\Product::find($values->product_id);

                  }
                   ?>
                    <tr>

                      <td><?php   if($product){
                        echo $product->name;
                      }?></td>
                      <td>{{$values->quantity}}</td>
                      <td>{{$values->sale_amount}}</td>
                      <td>{{$values->hsn}}</td>
                      <td>{{$values->cgst}}</td>
                      <td>{{$values->sgst}}</td>
                      <td>{{$values->igst}}</td>
                      <td>{{$values->cess}}</td>
                      <td>{{$values->foc}}</td>
                      <td>{{$values->msp}}</td>
                      <td>{{$values->amt}}</td>

                    </tr>
                    <?php
                  }
                }
                else{
                  ?>
                   <tr  data-from ="staffquote">
                       <td colspan="4" class="noresult">No result</td>
                    </tr>
                  <?php
                }
                ?>

               </table>

               <div class="form-group col-md-12  text-center" >

                <?php
                  if($transation->approval_product=="N")
                  {
                    echo '<button type="button" class="btn btn-primary approval_product_approval"  onclick="approval_product()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary approval_product_approved" style="display:none;"  >Approved</button>';
                    echo '<button type="button" class="btn btn-warning"  onclick="edit_purchase()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>

                </div>

                </div>
                </div>


                 <div class="form-group col-md-12">
                <div class="panel panel-default">
                <div class="panel-body ">
                <div class="form-group col-md-12  text-center" ><h2>Configuration </h2></div>
                <div class="form-group col-md-6">
                    <h3>Standard Warranty</h3>
                    {{$transation->stan_warrenty}}
                </div>

                 <div class="form-group col-md-6">
                  <h3>Additional Warranty</h3>
                  {{$transation->add_warrenty}}
                </div>
                <div class="form-group col-md-12  text-center" >

                <?php
                  if($transation->approval_config=="N")
                  {
                    echo '<button type="button" class="btn btn-primary approval_config_approval"  onclick="approval_config()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary approval_config_approved" style="display:none;"  >Approved</button>';
                    echo '<button type="button" class="btn btn-warning"  onclick="edit_purchase()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>

                </div>
                </div>
                </div>
                </div>

                <?php
                if($transation->user_id>0)
                {
                  $user = App\User::find($transation->user_id);
                  $gstno=$user->gst;
                  if($user)
                  {
                    $customer_name=$user->business_name;
                  }
                  if($user->state_id>0)
                  {
                    $state = App\State::find($user->state_id);
                    $state_name=$state->name;
                  }
                  if($user->district_id>0)
                  {
                    $district = App\District::find($user->district_id);
                    $district_name=$district->name;
                  }

                }

                if($transation->contact_id>0)
                {
                  $contact_person = App\Contact_person::find($transation->contact_id);
                  $contact_person_name=$contact_person->name;
                  $contact_person_email=$contact_person->email;
                  $contact_person_phone=$contact_person->phone;
                }
                else{
                  $contact_person_name='';
                  $contact_person_email='';
                  $contact_person_phone='';
                }

                ?>

                <div class="form-group col-md-12">
                <div class="panel panel-default">
                <div class="panel-body ">

                <div class="form-group col-md-3">
                    <h3>PO Details</h3>
                    {{$transation->collect_date}}
                </div>
               <div class="form-group col-md-3">
                  <h3>State</h3>
                  @if($state_name)
                  {{$state_name}}
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <h3>District</h3>
                  @if($district_name)
                  {{$district_name}}
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <h3>Customer Name</h3>
                  @if($customer_name)
                  {{$customer_name}}
                  @endif
                </div>

                <div class="form-group col-md-6">
                  <h3>Shipping Address</h3>
                  {{$transation->user_shipping}}
                </div>
                <div class="form-group col-md-6">
                  <h3>Contact Person</h3>
                  @if($contact_person_name)
                  {{$contact_person_name}}
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <h3>Designation</h3>
                  {{$transation->add_warrenty}}
                </div>
                <div class="form-group col-md-3">
                  <h3>Phone</h3>
                  @if($contact_person_phone)
                  {{$contact_person_phone}}
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <h3>Mail</h3>
                  @if($contact_person_email)
                  {{$contact_person_email}}
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <h3>GST Number</h3>
                  @if($gstno)
                  {{$gstno}}
                  @endif


                </div>

                 <div class="form-group col-md-12  text-center" >

                <?php
                  if($transation->approval_customer=="N")
                  {
                    echo '<button type="button" class="btn btn-primary approval_customer_approval"  onclick="approval_customer()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary approval_customer_approved" style="display:none;"  >Approved</button>';
                    echo '<button type="button" class="btn btn-warning"  onclick="edit_purchase()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>

                </div>


                </div>
                </div>
                </div>






              </div>

            </div>


            <div class="col-md-6">
            <div class="form-group col-md-12">
                <div class="panel panel-default">
                <div class="panel-body ">
                <?php
                if(count($transation_pocopy)>0)
                {
                  foreach($transation_pocopy as $values)
                  {
                    $imgpath=asset("storage/app/public/transation/$values->image_name");
                    ?>
                    <!-- <a download="<?php echo $imgpath;?>" href="<?php echo $imgpath;?>">Download</a> -->
                    <iframe src="<?php echo $imgpath;?>" height="300" width="600"></iframe>
                    <br>
                    <?php
                  }
                }
                ?>

                </div>
                </div>
                </div>
             </div>

            <!-- Technical approval end -->
          </div>
          <div class="tab-pane " id="otherpro">

          <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                  
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Sale Amount</th>
                    <th>MSP</th>
                    <th>Net Amount</th>
                    <th>Surplus / Deficit</th>
                    <th>Insentive</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {$k=0;$p=1;$total_msp=0;$total_net=0;$total_ins=0;
                  foreach($transation_product as $values)
                  {
                    if($values->product_id>0){
                      $product = App\Product::find($values->product_id);
                    }
                   


                    ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">
                    <td>{{$p}}</td>
                  

                    <td>{{$product->name}}</td>
                <td>{{$values->quantity}}</td>
               <td>{{$values->sale_amount}}

               </td>

               <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" style="width:80px;" value="{{$values->msp}}" id="mspamount_{{$values->product_id}}"  class="msp" name="msp[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_msp({{$values->product_id}})"  onkeyup="change_msp({{$values->product_id}})">
              </td>
               <td>{{$values->amt}}
               <input type="hidden" value="{{$values->amt}}" id="saleamount_{{$values->product_id}}"  class="salemaount" name="salemaount[]" data-id="{{$values->product_id}}" style="width:60px;" >
               </td>
               <td>
               <?php
               $net_amount=$values->amt;
               
               $total_net +=$net_amount;
               $msp=$values->msp;
               $total_msp +=$msp;
               $diffe=$net_amount-$msp;
               $total_ins +=$values->insentive;
               if($diffe>0)
               {
                $column_color="green";
               }
               else if($diffe<0){
                $column_color="red";
               }
               else if($diffe==0){
                $column_color="orange";
                }

               ?>
               <span style="background-color:<?php echo $column_color;?>" class="surplus_{{$values->product_id}}">{{$diffe}}</span> </td>
               <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" value="{{$values->insentive}}" id="insentive_{{$values->product_id}}"  class="insentive" name="insentive[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_incentive({{$values->product_id}})"  onkeyup="change_incentive({{$values->product_id}})">
               <div class="inse_loader_{{$values->product_id}}" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </td>

         

                    </tr>
                    

                    <?php
                    $k++; $p++;
                  }
                  ?>
                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td>Total</td>
                  <td id="msp_val">{{$total_msp}}</td>
                  <td>{{$total_net}}</td>
                  <td></td>
                  <td id="owner_val">{{$total_ins}}</td>
                  
                  </tr>
                  <?php
                  if($transation->owner>0)
                    {
                      $staff_owner = App\Staff::find($transation->owner);
                      if($staff_owner)
                      {
                        $owner=$staff_owner->name;
                      }else{$owner='';}
                    }else{$owner='';}

                    if($transation->second_owner>0)
                    {
                      $staff_second_owner = App\Staff::find($transation->second_owner);
                      if($staff_second_owner)
                      {
                        $second_owner=$staff_second_owner->name;
                      }else{$second_owner='';}
                    }else{$second_owner='';}
                    ?>
                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Owner</td>
                  <td >{{$owner}}</td>
                  <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')"
                   type="text"  onchange="change_ownerval()"  onkeyup="change_ownerval()" value="{{$transation->per_owner}}" id="owner_value"  class="owner_value" name="owner_value"  style="width:60px;" >%
                   <?php 
                   if($transation->per_owner>0)
                   {
                    $owner_persen_persa = ($transation->per_owner / 100) * $total_ins;
                   }
                   else{
                     $owner_persen_persa='';
                   }
                   ?>
                   <span id="owner_persen">{{$owner_persen_persa}}</span>
                   </td>
                  
                  </tr>

                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Secondary Owner</td>
                  <td>{{$second_owner}}</td>
                  <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')"
                   type="number" readonly="true" value="{{$transation->per_second_owner}}" id="secondowner_value"  class="secondowner_value" name="secondowner_value"  style="width:60px;" >%
                   <?php 
                   if($transation->per_second_owner>0)
                   {
                    $sownser_persen_persa = ($transation->per_second_owner / 100) * $total_ins;
                   }
                   else{
                     $sownser_persen_persa='';
                   }
                   ?>

                    <span id="secondowner_persen">{{$sownser_persen_persa}}</span>
                   </td>
                  
                  </tr>

                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  
                  <td>
                  @if($transation->approval_msp_owner=="Y")
                  
                    <button type="button" class="btn btn-secondary"  >Approved</button>
                  @endif
                  @if($transation->approval_msp_owner=="N")
                  
                    <button type="button" class="btn btn-primary approval_msp_not_approve"  onclick="approval_msp_owner()">Approve</button>
                    <button type="button" class="btn btn-secondary approval_msp_owner" style="display:none;" >Approved</button>
                    @endif
                
                  
                   </td>
                  
                  </tr>




                  <?php
                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>

          </div>
          <div class="tab-pane " id="stock_availiability">

          <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                   

                    <th>Qty</th>
                    <th>Available Qty</th>
                 
                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {$k=0;
                  foreach($transation_product as $values)
                  {
                    $date=date("Y-m-d");
                    $stock_exit =  DB::select("select * from stock_register where `product_id`='".$values->product_id."' AND created_date='".$date."'  ");
                    if($stock_exit)
                    {
                      $stock_count=$stock_exit[0]->stock_in_hand;
                    }
                    else{
                      $stock_count=0;
                    }
                    if($values->product_id>0){

                      $product = App\Product::find($values->product_id);
                    }
                    if($product->image_name==null || $product->image_name=='')
                    {
                      $imgs=asset('images/no-image.jpg');
                    }
                    else{
                      $imgs=asset('storage/app/public/products/thumbnail/'.$product->image_name);
                    }

                ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">
               <td><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
              <td><input type="text" @if($transation->stock_terms=="Y") readonly="true" @endif    value="{{$values->quantity}}" id="qn_stock_{{$values->product_id}}" name="stock_quantity[]" class="quantity" onkeyup="update_qty(this.value,{{$values->product_id}})" data-id="{{$values->product_id}}" style="width:40px;">
              <div class="update_qty_{{$values->product_id}}" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </td>
              <td><input type="text"  disabled="true"  value="{{$stock_count}}" id="qn__avail_stock{{$values->product_id}}" name="stok_in_hand_qty[]" class="quantity"  data-id="{{$values->product_id}}" style="width:40px;"></td>
              
              </tr>

                    </tr>

                    <?php
                    $k++;
                  }

                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>

               
               <?php
                  if($transation->stock_terms=="N")
                  {
                    echo '<button type="button" class="btn btn-primary stock_terms_approval"  onclick="stock_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary stock_terms_approved" style="display:none;"  >Approved</button>';
                   // echo '<button type="button" class="btn btn-warning"  onclick="edit_stock_terms()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>


               <div class="panel panel-default">
                 <div class="panel-body ">

                  <div class="form-group col-md-12 ">
                  <h3>Delivery terms </h3>
                  <p>{{$transation->del_terms}}</p>
                  <h3>Expected date of supply </h3>
                  <p>{{$transation->expect_date}}</p>

                  <?php
                  if($transation->approval_delivery_terms=="N")
                  {
                    echo '<button type="button" class="btn btn-primary delivery_terms_approval"  onclick="delivery_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary delivery_terms_approved" style="display:none;"  >Approved</button>';
                  
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>


                  </div>
                </div>
              </div>

              


          </div>
          <div class="tab-pane " id="financial_approval">

          <div class="panel panel-default">
                 <div class="panel-body ">

                  <div class="form-group col-md-12 ">
                  <h3>Payment terms </h3>
                  <p>{{$transation->payment_terms}}</p>
                
                  <?php
                  if($transation->approval_payment_terms=="N")
                  {
                    echo '<button type="button" class="btn btn-primary payment_terms_approval"  onclick="payment_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="btn btn-secondary payment_terms_approved" style="display:none;"  >Approved</button>';
                  
                  }
                  else{
                    echo '  <button type="button" class="btn btn-secondary "  >Approved</button>';
                  }
                  ?>

                  </div>
                </div>
              </div>

          </div>
         
          <div class="tab-pane " id="cust_conform">66
          </div>
       </div>
       </div>








              </div>
              <!-- /.box-body -->

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
        <form name="add_address" id="add_address" method="post">

        <div class="form-group  col-md-6">
                  <label for="name">Street Address1</label>
                  <input type="text"   id="shipping_address1" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="">

                  <span class="error_message" id="shipping_address1_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Street Address2</label>
                  <input type="text"  id="shipping_address2" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="">
                  <span class="error_message" id="shipping_address2_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="shipping_country_id" name="shipping_country_id" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values_con)
                  <?php
               // $sel = ($values->country_id == $values_con->id) ? 'selected': '';
                  echo '<option value="'.$values_con->id.'" >'.$values_con->name.'</option>';
                  ?>

                  @endforeach
                  </select>
                  <span class="error_message" id="shipping_country_id_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">State</label>
                  <input type="text" id="shipping_state" name="shipping_state" class="form-control" value="" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" name="shipping_city" class="form-control" value="" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" value="" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>



        </form>
      </div>
      <div class="modal-footer">
      <span class="success_msg" style="display:none;color:green">Data saved successfully</span>
      <button type="button" class="btn btn-primary"  onclick="save_shipping()">Save</button>
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
    function approval_msp_owner(){
      
      $(".approval_msp_not_approve").hide();
      $(".approval_msp_owner").show();

      var url = APP_URL+'/staff/approval_transation_mspowner';
      var owner_value=$("#owner_value").val();
        var secondowner_value=$("#secondowner_value").val();
       if(owner_value!='' && secondowner_value!='')
       {
        var id='<?php echo $transation->id;?>';
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:id,owner_value:owner_value,secondowner_value:secondowner_value
                  },
                  success: function (data)
                  {
                  
                  }
          });
       }
       else{
         alert("Please check owner persentage");
       }
        

    }
    function change_ownerval(){
      var owner_value=$("#owner_value").val();
     
      var send_ow_val=100-parseFloat(owner_value);
      $("#secondowner_value").val(send_ow_val);
      var myTotal=0;
      $('input[name^="insentive[]').each(function() {
      // alert( $(this).val())
        myTotal = parseFloat($(this).val())+parseFloat(myTotal);
    });
    
    
    var discount_owner= (myTotal - ( myTotal * owner_value / 100 )).toFixed(2);
    var discount_secondowner= (myTotal - ( myTotal * send_ow_val / 100 )).toFixed(2);
    
    
    $("#owner_persen").html(discount_secondowner);
    $("#secondowner_persen").html(discount_owner);
    }

    function change_incentive(product_id)
    {

        $(".inse_loader_"+product_id).show();
        var transation_id='<?php echo $transation->id;?>';
        var insentive=$("#insentive_"+product_id).val();
        var url = APP_URL+'/staff/save_transation_insentive';
      
var tot=0;
$('input[name="insentive[]"]').each(function(){
 tot =parseFloat($(this).val())+parseFloat(tot);
});
$("#owner_val").html(tot);

        $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            insentive: insentive,product_id:product_id,transation_id:transation_id
          },
          success: function (data)
          {
            $(".inse_loader_"+product_id).hide();

          }
   });


    }
    function change_msp(product_id)
    {
        var net_amount=$("#saleamount_"+product_id).val();
        var msp=$("#mspamount_"+product_id).val();


        var diffe=parseFloat(net_amount)-parseFloat(msp);
               if(diffe>0)
               {
                var column_color="green";
               }
               else if(diffe<0){
                var column_color="red";
               }
               else if(diffe==0){
                var column_color="orange";
                }
                $(".surplus_"+product_id).html(diffe.toFixed(2));
                $(".surplus_"+product_id).css("background-color",column_color );

    }
    function edit_purchase()
    {
      $(".purchase_order_click").trigger("click");

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
      if(tran_type!='')
      {
        if(typpes=="Opportunity")
                {
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
        $("#tran_type_message").show();
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

function stock_terms_approval(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='stock_terms';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".stock_terms_approval").hide();
            $(".stock_terms_approved").show();
          }
   });
}

function delivery_terms_approval(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_delivery_terms';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".delivery_terms_approval").hide();
            $(".delivery_terms_approved").show();
          }
   });
}


function payment_terms_approval(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_payment_terms';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".payment_terms_approval").hide();
            $(".payment_terms_approved").show();
          }
   });
}

      function approval_company(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_company';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_company_approval").hide();
            $(".approval_company_approved").show();
          }
   });

      }


         function approval_product(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_product';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_product_approval").hide();
            $(".approval_product_approved").show();
          }
   });

      }



      function approval_config(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_config';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_config_approval").hide();
            $(".approval_config_approved").show();
          }
   });

      }

         function approval_customer(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_customer';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_customer_approval").hide();
            $(".approval_customer_approved").show();
          }
   });

      }



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
console.log(proObj);
           //proObj[i]["id"]
           $("#custnemdis").html(proObj[0]["business_name"]);
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
           // $("#user_id").val(user_id);
             //$('#user_id').select2('refresh');
          //  $("#user_id").value(proObj[0]["user_id"]);
            change_user_id(proObj[0]["user_id"]);


            //$("#user_id").select2("val", user_id);
            //  $('#user_id').selectpicker('refresh');
          }
        });
  }



     function add_oppur_prod()
      {
        $(".loader").show();
       var type_conf=$("#type_conf").val();
      if(type_conf=="Opportunity")
        {


          $("#oppur_status").val(1);
         $(".cust_name").hide();
          $(".cust_details").show();
        $("#type_conf").html('<option value="">Select Type</option><option value="Product">Product</option>');
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
                           if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                             {
                             var cgst=tax/2;
                             var sgst=tax/2;
                             var igst=0;
                             var cess=0;
                             var tax_cal= amt*tax/100;
                             }
                             if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                              {
                               var cgst=tax/2;
                               var sgst=tax/2;
                               var igst=0;
                               var cess=1;
                               tax=parseInt(tax)+parseInt(cess);
                               var tax_cal= amt*tax/100;
                             }
                            if(tran_type=="InterState Registered Sales")
                             {
                               var igst=tax;
                               var cgst=0;
                               var sgst=0;
                               var cess=0;
                               var tax_cal= amt*tax/100;
                             }
                             if(tran_type=="InterState Un-Registered Sales")
                              {
                               var igst=tax;
                               var cgst=0;
                               var sgst=0;
                               var cess=1;
                               tax=parseInt(tax)+parseInt(cess);
                               var tax_cal= amt*tax/100;
                             }
                             amt =amt+tax_cal;
                          htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
                          htmlscontent += '<td>';
                          htmlscontent += '<select name="warrenty_product[]" id="warrenty_product_'+proObj[i]["id"]+'">';
                          htmlscontent += '<option value="">Additional Warranty</option>';
                          for (var k = 0; k < products_warenty.length; k++) {
                            htmlscontent += '<option value="'+products_warenty[i]["id"]+'">'+products_warenty[i]["name"]+'</option>';
                          }
                          htmlscontent += '</select>';
                          htmlscontent += '</td>';
                          htmlscontent += '<td><input type="text" value="'+quantity+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
                          '<td><input type="text" value="'+sale_amount+'" name="sale_amount[]" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+

                          '<td><input type="text" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'" name="hsn[]"  class="hsn" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
                          '<td><input type="text" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
                          '<td><input type="text" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
                          '<td><input type="text" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
                          '<td><input type="text" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
                          '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"   class="foc" name="foc[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<td><input type="text" value="'+proObj[i]["msp"]+'" id="msp_'+proObj[i]["id"]+'"  class="msp" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',1)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="transation_product_id[]" value=""><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="transation_product_id[]" value=""><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
             // arr_total.push(amt);

          arr_total[proObj[i]["id"]] = amt;

           }
           console.log(arr_total);


var myTotal = 0;

for(key in arr_total){
  myTotal += arr_total[key];
}


               $(".footertr").hide();
              htmlscontent='<tr class="footertr">'+
              '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td>Total</td>'+
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
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  var sgst=tax/2;
                  var igst=0;
                  var cess=0;
                  var tax_cal= amt*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    var sgst=tax/2;
                    var igst=0;
                    var cess=1;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= amt*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= amt*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= amt*tax/100;
                  }
                  amt =amt+tax_cal;
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
               htmlscontent += '<td>';
              htmlscontent += '<select name="warrenty_product[]" id="warrenty_product_'+proObj[i]["id"]+'">';
              htmlscontent += '<option value="">Additional Warranty</option>';
              for (var k = 0; k < products_warenty.length; k++) {
                htmlscontent += '<option value="'+products_warenty[i]["id"]+'">'+products_warenty[i]["name"]+'</option>';
              }
              htmlscontent += '</select>';
              htmlscontent += '</td>';
              htmlscontent += '<td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" name="quantity[]" class="quantity" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
               '<td><input type="text" name="sale_amount[]" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+
               '<td><input type="text" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'"  class="hsn" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
               '<td><input type="text" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
               '<td><input type="text" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
               '<td><input type="text" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
               '<td><input type="text" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">%'+
               '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"  name="foc[]"  class="foc" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<td><input type="text" value="'+res[1]+'" id="msp_'+proObj[i]["id"]+'"  class="msp" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',0)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
              //arr_total.push(amt);
              arr_total[proObj[i]["id"]] = amt;
           }
           console.log(arr_total);
var myTotal = 0;


for(key in arr_total){
  myTotal += arr_total[key];
}

      $(".footertr").hide();

               htmlscontent='<tr class="footertr">'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td></td>'+
               '<td>Total</td>'+
              '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

          }
        });
        }

        console.log('arr_product'+arr_total)

      }
      

function change_qty(qty,product_id)
{
var quantity   = qty;
        var product_id = product_id;
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
                var sale_amount= $("#sa_"+product_id).val();
                var amt    = quantity * sale_amount;
               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
             //   $("#sa_"+product_id).val(amt);
                $(".hqn_"+product_id).val(qty);
              //  $(".hamt_"+product_id).val(amt);
               // $(".hsa_"+product_id).val(amt);
              }
            }
        });
}
function change_sale_amt(sale_amount,product_id)
{
       var product_id = product_id;
        var sale_amount=sale_amount;

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
                //  alert(sale_amount)
                if(sale_amount!=0)
                {
                  $(".error_sale_"+product_id).html('');
                //  alert(max_sale_amount+'--'+min_sale_amount+'--'+sale_amount)
                if(max_sale_amount==0 && min_sale_amount==0 && sale_amount!=unit_price)
                {
                  $(".error_sale_"+product_id).html('Maximum and minimum sale amount is given zero. So can not make change in actual sales amount.');
                  $(".error_sale_"+product_id).show();
                }
               else if(sale_amount!=unit_price && unit_price==0)
                {
                  $(".error_sale_"+product_id).html('Actual sale amount is given zero. So can not give sales amount other than zero');
                  $(".error_sale_"+product_id).show();
                }
               // alert(max_sale_amount+'--'+min_sale_amount+'--'+sale_amount)
                if(sale_amount>=min_sale_amount && sale_amount<=max_sale_amount)
                  {//alert('111')
                    $(".error_sale_"+product_id).html('');
                  $(".error_sale_"+product_id).hide();
                  }
                  else{//alert('222')
                   /* */
                  $(".error_sale_"+product_id).html('Sale amount is between '+min_sale_amount+' and '+max_sale_amount+' ');
                  $(".error_sale_"+product_id).show();
                  }
                }//not zero if
               // $("#sa_"+product_id).val(amt);
               var sale_amount= $("#sa_"+product_id).val();
               var quantity= $("#qn_"+product_id).val();
                var amt    = quantity * sale_amount;

               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
                $(".hsa_"+product_id).val(amt);
              }
            }
        });
}
  function deletepro(product_id,types)
    {

      $(".tr_"+product_id).remove();
      var transation_id='<?php echo $transation->id?>'
      var url = APP_URL+'/staff/delete_product_transation';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,transation_id:transation_id
            },
            success: function (data)
            {

            }
        });

    }

 function update_qty(qty,product_id)
{
  if(qty>0)
  {
    var url = APP_URL+'/staff/update_qty_transation';
 $(".update_qty_"+product_id).show();
 var transation_id='<?php echo $transation->id?>'
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,qty:qty,transation_id:transation_id
            },
            success: function (data)
            {
              $(".update_qty_"+product_id).hide();
            }
        });

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
  $(".addlink").attr("href", "https://biomedicalengineeringcompany.com/staff/customer/"+user_id+"/edit");


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

@endsection
