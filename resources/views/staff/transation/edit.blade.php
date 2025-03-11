@extends('staff/layouts.app')
@section('title', 'Edit Transaction')
@section('content')
<?php
if(isset($_GET['type']))
{
$type=$_GET['type'];
}
else{
  $type='';
}

?>
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
        <div class="col-md-12 outer-sect">
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

            <form autocomplete="off" role="form" name="frm_brand" id="frm_brand" method="post" action="{{ route('staff.transation.update', $transation->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}

                <div class="box-body">


                  <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
				
          @if($type=='')
          <li id="purchase_order_click" ><a class="purchase_order_click" href="#purchase_order" data-toggle="tab" class="active">
          @if($transation->type_conf=="Sales order")Sales order @endif
          @if($transation->type_conf=="Test Return")Test Return @endif
          </a></li>
          <li class="disabled"><a href="#technical_approval" data-toggle="tab">Technical Approval</a></li>
          <li class="disabled"><a href="#otherpro" data-toggle="tab">MSP ,Payout,otherprovisions if any</a></li>
          
          <li class="disabled"><a href="#financial_approval" data-toggle="tab">Financial Approval</a></li> 
        @endif
        @if($type!='')

        @if($type=="tech")
        <li id="purchase_order_click" class="disabled"><a  class="purchase_order_click" href="#purchase_order" data-toggle="tab" >Sales order</a></li>
        <li class="active"><a href="#technical_approval" data-toggle="tab" aria-expanded="true" class="active" >Technical Approval</a></li>
        @endif

        @if($type=="msp")
        <li><a href="#otherpro" data-toggle="tab" class="active">MSP,Payout,otherprovisions if any</a></li>
        @endif
        @if($type=="fin")
        <li><a href="#financial_approval" data-toggle="tab" class="active">Financial Approval</a></li> 
        @endif
        @endif

				</ul>
				<div class="tab-content">


					<div class="tab-pane  @if($type=='') active @endif " id="purchase_order">
            <!-- Purchase Order start  -->
            <div class="panel panel-default">
    <div class="panel-body ">

    <div class="box-body row">

                   <div class="col-md-12 col-sm-6 col-lg-12" >
                    <div class="radio-label"><label >Source*</label></div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Online" @if($transation->status=="Online") checked="true" @endif @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                      Online
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Call" @if($transation->status=="Call") checked="true" @endif @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                      Call
                    </label>
                  </div>
                    <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="E-Mail" @if($transation->status=="E-Mail") checked="true" @endif @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                      E-Mail
                    </label>
                  </div>

                      <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="PO" @if($transation->status=="PO") checked="true" @endif @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                      PO
                    </label>
                  </div>
                       <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Mail" @if($transation->status=="Mail") checked="true" @endif @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                      Mail
                    </label>
                  </div>

                 
                    
                </div>
              </div>
<input type="hidden" name="transaction_type" id="transaction_type" value="{{$type}}">
              <div class="row" style="display:none">
              <div class="form-group  col-md-3">
                    <label>Company*</label>
                    <select name="company_id" id="company_id" class="form-control">
                      <option value="">-- Select Company --</option>
                      <?php
                      foreach($company as $item) {
                        $sel = ($transation->company_id == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                      } ?>
                    </select>
                    <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                    </div>
                  </div>
            

                
                <div class="sales-sltrow row ">
                <div class="col-md-3 col-sm-6 col-lg-3" >
                  <label for="name">Customer Name *</label>
                  <input type="hidden" name="user_id_hidden" id="user_id_hidden">
                  <input type="hidden" name="oppur_id" id="oppur_id">
                  <input type="hidden" name="tran_type" id="tran_type" value="{{$transation->tran_type}}">
                  <input type="hidden" name="oppur_status" id="oppur_status">
                  <select class="form-control" name="user_id" id="user_id"  disabled="true" >
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

                  <div class="col-md-3 col-sm-6 col-lg-3"  @if($transation->type_conf=="Test Return") style="display:none;" @endif>
                  <label for="name">Select Option *</label>
                  <select class="form-control" name="type_conf" id="type_conf" onchange="change_conf(this.value)" @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                  <option value="">Select Option</option>
                  <!-- <option value="Opportunity">Opportunity</option> -->
                  @if($transation->type_conf!="Test Return")
                      <option value="Product">Product</option>
                      @endif
                      @if($transation->type_conf=="Test Return")
                      <option value="Test Return">Test Return</option>
                      @endif
                      
                  </select>
                  <span class="error_message" id="tran_type_message" style="display: none">Field is required</span>
                </div>
              </div>
               <div class="row ">

                    <div class="col-md-6 col-sm-6 col-lg-6 op_id" style="display:none;">
                        <label>Opportunity*</label>
                        <select name="op_id" id="op_id" class="form-control op_id" onchange="change_oppertunity(this.value)" @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
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
                   <div class="col-md-6 col-sm-6 col-lg-6 op_id_date" style="display: none">
                  <label for="name">Opportunity Date</label>
                     <span id="opp_date"></span>
                </div>
              </div>
               <div class="row ">
                      <div class="col-md-6 col-sm-6 col-lg-6 product_id" style="display:none;">
                        <label>Product*</label>
                        <select name="productid" id="product_id" class="form-control" @if($transation->financial_approval_status=="Completed") disabled="true" @endif>
                          <option value="">-- Select Product --</option>
                          <?php
                          foreach($products as $item) {
                              echo '<option value="'.$item->id.'" >'.$item->name.'</option>';
                          } ?>
                        </select>
                        <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                      </div>

                      

                   <div class="col-md-6 col-sm-6 col-lg-6 add_oppur_prod" style="display: none">
                <button type="button" class="btn btn-primary"  onclick="add_oppur_prod()" @if($transation->financial_approval_status=="Completed") disabled="true" @endif>Add</button>
                      <div class="loader" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </div>
            </div>

              <div class=" box-body row"> 
                <div class="col-md-12 sales-table">
               <table id="cmsTable" class="table table-bordered table-striped data- @if($transation->financial_approval_status=="Completed") hideform @endif" >
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                 
                    <th>Qty</th>
                    
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
                <?php
                if(count($transation_product)>0)
                {$k=0;
                  $myTotal = 0; 
                  $tot_cgst=0;
                  $tot_sgst=0;
                  $tot_igst=0;
                  $tot_igst=0;
                  $tot_cess=0;
                  foreach($transation_product as $values)
                  {
                    if($values->product_id>0){
                      $product = App\Product::find($values->product_id);
                      }

                      $tax=$product->tax_percentage;
                    if($product->image_name==null || $product->image_name=='')
                    {
                      $imgs=asset('images/no-image.jpg');
                      }
                    else{
                      $imgs=asset('storage/app/public/products/thumbnail/'.$product->image_name);
                    }

                    if($transation->tran_type=="Intra State Registered Sales" || $transation->tran_type=="Government Sales Registered")
                    {
                    $cgst=$tax/2;
                    $sgst=$tax/2;
                    $igst=0;   
                    $cess=0;
                   
                    }
                    if($transation->tran_type=="Intra State Un-Registered Sales" || $transation->tran_type=="Government Sales Unregistered")
                     {
                      $cgst=$tax/2;
                      $cgst_per=$tax/2;
                     $sgst=tax/2;
                      $igst=0;   
                      $cess=1;
                   
                    }
                   if($transation->tran_type=="InterState Registered Sales")
                    {
                      $igst=$tax;
                      $cgst=0;
                      $sgst=0;
                      $cess=0;
                    }
                    if($transation->tran_type=="InterState Un-Registered Sales")
                     {
                      $igst=$tax;
                      $cgst=0;
                      $sgst=0;
                      $cess=1;
                    }

                ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">

                    <td data-th="No." ><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
              
              <td data-th="Name"><input type="text" @if($transation->financial_approval_status=="Completed") disabled="true" @endif  value="{{$values->quantity}}" id="qn_{{$values->product_id}}" name="quantity[]" class="quantity form-control" onchange="change_qty(this.value,{{$values->product_id}})" data-id="{{$values->product_id}}" style="width:40px;"></td>
              
               <td data-th="Qty"><input type="text"    @if($transation->financial_approval_status=="Completed") disabled="true" @endif  name="sale_amount[]" value="{{$values->sale_amount}}" id="sa_{{$values->product_id}}" onchange="change_sale_amt(this.value,{{$values->product_id}})" class="sale_amt form-control" data-id="{{$values->product_id}}" style="width:60px;">
               <td data-th="Unit Price"><input type="text"   readonly="true"   value="{{$values->hsn}}" id="hsn_{{$values->product_id}}"  class="hsn form-control" name="hsn[]" data-id="{{$values->product_id}}" style="width:70px;">
               <td data-th="HSN"><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->cgst}}" id="cgst_{{$values->product_id}}"  class="cgst form-control" name="cgst[]" data-id="{{$values->product_id}}" ><p>({{$cgst}}%)</p></span>
               <td data-th="CGST"><span class="per_sec_tran"><input type="text"  readonly="true"   value="{{$values->sgst}}" id="sgst_{{$values->product_id}}"  class="sgst form-control" name="sgst[]" data-id="{{$values->product_id}}" ><p>({{$sgst}}%)</p></span>
               <td data-th="SGST"><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->igst}}" id="igst_{{$values->product_id}}"  class="igst form-control" name="igst[]" data-id="{{$values->product_id}}" ><p>({{$igst}}%)</p></span>
               <td data-th="IGST"><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->cess}}" id="cess_{{$values->product_id}}"  class="cess form-control" name="cess[]" data-id="{{$values->product_id}}" ><p>({{$cess}}%)</p></span>
               
               <td data-th="Cess"><input type="text"   readonly="true"   value="{{$values->msp}}" id="msp_{{$values->product_id}}"  class="msp form-control" name="msp[]" data-id="{{$values->product_id}}" style="width:40px;">

               <div style="display:none;" class="error_message error_sale_{{$values->product_id}}"></div></td>
               <td data-th="MSP"><input type="text"   readonly="true"   value="{{$values->surplus_amt}}" id="surplus_amt_{{$values->product_id}}"  class="surplus_amt form-control" name="surplus_amt[]" data-id="{{$values->product_id}}" style="width:40px;">
               </td>
               
               <td data-th="Surplus / Deficit"><input type="text"   readonly="true"   value="{{$values->amt}}" id="am_{{$values->product_id}}" class="amt form-control" name="amt[]" data-id="{{$values->product_id}}" readonly></td>
               <td data-th="Net Amount"> @if($transation->financial_approval_status=="Pending") <a class="delete-btn " onclick="deletepro({{$values->product_id}},{{$k}})" data-id="{{$values->product_id}}"  title="Delete"><img src="{{ asset('images/delete.svg') }}"></a> @endif</td>
               <input type="hidden" name="product_id[]" value="{{$values->product_id}}">
               <input type="hidden" name="amount[]" value="{{$values->amt}}" class="amt_{{$values->product_id}}">
              
               <input type="hidden" name="optional[]" value=""><input type="hidden" name="main_pdt[]" value="">
               <input type="hidden" name="transation_product_id[]" value="{{$values->id}}"></tr>
               <input type="hidden" name="tax_percentage[]" value="{{$values->tax_percentage}}">

                    </tr>

                    <?php
                    $k++;
                    $myTotal = $values->amt+$myTotal;
                    $tot_cgst =$values->cgst+$tot_cgst;
                    $tot_sgst = $values->sgst+$tot_sgst;
                     $tot_igst = $values->igst+$tot_igst;
                    $tot_cess = $values->cess+$tot_cess;
                 

                  }
                  ?>

              <tr class="footertr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                
              
                <td>{{$tot_cgst}}</td>
                <td>{{$tot_sgst}}</td>
                <td>{{$tot_igst}}</td>
              
                <td>{{$tot_cess}}</td>
                <td></td>
                <td></td>
               <td>{{$myTotal}}</td><td></td></tr>
               
                  <?php
                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>
               </div>
            </div>


</div>
</div>


@if($transation->financial_approval_status!="Completed")
      <div class="box-footer col-md-12">
    <button type="submit" class="mdm-btn submit-btn" @if($transation->financial_approval_status=="Completed") disabled="true" @endif >Submit</button>
    <!-- onclick="validate_from()" -->
    @if($type=='')
    <button type="button" class="mdm-btn cancel-btn " @if($transation->financial_approval_status=="Completed") disabled="true" @endif onClick="window.location.href='{{route('staff.transation.index')}}'">Cancel</button>
    @endif
  
  </div>
              @endif

             
<!-- ******************************************************************************************************* -->

  <!-- End menu start -->
  <div class="box-body col-md-12">
     <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#po_details" data-toggle="tab"  class="active">PO Details</a></li>
			<li class=""><a href="#terms_condition" data-toggle="tab">Terms And Condition</a></li>
     
        
				</ul>
				<div class="tab-content">
				
			<div class="tab-pane active" id="po_details">
        
    <div class="panel panel-default">
    <div class="panel-body ">
    <div class="box-body row @if($transation->financial_approval_status=="Completed") hideform @endif">

    <div class="col-md-12 col-sm-6 col-lg-12">
                 <h3>PO Details</h3>
                </div>

                 <div class="col-md-12 col-sm-6 col-lg-12">
                  <label for="name">PO Collected Date*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif   disabled="true"  type="text" id="collect_date" name="collect_date" value="{{$transation->collect_date}}" class="form-control" placeholder="PO Collected Date">
                  <span class="error_message" id="collect_date_message" style="display: none">Field is required</span>
                </div>

                  <div class="col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Customer Address*</label>
                    <textarea @if($transation->financial_approval_status=="Completed") disabled="true" @endif  name="user_address" id="user_address" class="form-control" placeholder="Customer Address" readonly>{{$user_details->address1}} {{$user_details->address2}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Shipping Address* 
                  @if($transation->financial_approval_status!="Completed")
                    <a onclick="add_shipping()" class="shiplink" >Add / </a>  <a class="shiplink" onclick="select_shipping()">Select Shipping Address</a>
                  @endif
                  </label>
                    <textarea @if($transation->financial_approval_status=="Completed") disabled="true" @endif  readonly="true"  name="user_shipping" id="user_shipping" class="form-control" placeholder="Shipping Address">{{$user_details->shiping_address}}</textarea>
                  <span class="error_message" id="user_address_message" style="display: none">Field is required</span>
                </div>

                    <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">Contact Person *
                  @if($transation->financial_approval_status!="Completed")
                    <a  target="_blank" class="shiplink addlink" onclick="add_contact()" >Add Contact</a>
                    @endif
                  </label>
                  <select @if($transation->financial_approval_status=="Completed") disabled="true" @endif disabled="true"  class="form-control" name="contact_id" id="contact_id" onchange="change_contact_id(this.value)">
                    <option value="">Contact Person</option>
                    @if(count($contact_persons)>0)
                    @foreach($contact_persons as $values)
                    <?php
                 
                     $sel = ($transation->contact_id == $values->id) ? 'selected': '';
                    echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                    ?>
                    @endforeach
                    @endif
                  </select>
                       <div class="loader_contact_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
                </div>
                   <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">Designation *</label>
                  <select @if($transation->financial_approval_status=="Completed") disabled="true" @endif  class="form-control" name="designation" id="designation" disabled="true">
                    <option value="">Designationn</option>
                  </select>
                  <span class="error_message" id="designation_message" style="display: none">Field is required</span>
                </div>
                <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">Department *</label>
                  <select @if($transation->financial_approval_status=="Completed") disabled="true" @endif class="form-control" disabled="true" name="department_id" id="department_id" readonly>
                    <option value="">Department</option>
                  </select>
                  <span class="error_message" id="department_id_message" style="display: none">Field is required</span>
                </div>
                   <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">Phone*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif  type="text" id="contact_phone" name="contact_phone" value="{{$transation->contact_phone}}" class="form-control" placeholder="Phone" readonly>
                  <span class="error_message" id="contact_phone_message" style="display: none">Field is required</span>
                </div>
                   <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">Mail*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif  type="text" id="contact_mail" name="contact_mail" value="{{$transation->contact_mail}}" class="form-control" placeholder="Mail" readonly>
                  <span class="error_message" id="contact_mail_message" style="display: none">Field is required</span>
                </div>
                    <div class="col-md-2 col-sm-6 col-lg-2">
                  <label for="name">GST Number*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif  type="text" id="gst" name="gst" value="{{$user_details->gst}}" class="form-control" placeholder="GST Number" readonly>
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>
                <div class="col-md-12 col-sm-6 col-lg-12">
                
                <span id="po_success" style="display: none;color:green;">Successfully Updated</span>
                <button type="button" class="lg-btn submit-btn  save_podetails" style="display:none;" onclick="save_podetails()" @if($transation->financial_approval_status=="Completed") disabled="true" @endif>Save</button>
                @if($transation->financial_approval_status!="Completed")
                <button type="button" class="mdm-btn-line submit-btn edit_btn_pode" onclick="edit_podetails()" @if($transation->financial_approval_status=="Completed") disabled="true" @endif >Edit</button>
                @endif
                <button type="button" class="lg-btn-line submit-btn  cancel_btn_pode " onclick="cancel_podetails()" style="display:none;">Cancel</button>
              </div> 

              </div>

    </div>
    </div>

    <div class="panel panel-default">
          <div class="panel-body ">
          
          <div class="box-body  @if($transation->financial_approval_status=="Completed") hideform @endif" >

          <div class="row">
          <div class="col-md-3">
                  <label for="name">Owner (Engineer) *</label>
                  <select @if($transation->financial_approval_status=="Completed") disabled="true" @endif class="form-control" name="owner" id="owner"  disabled=""true" >
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
                    <div class="col-md-3">
                  <label for="name">Secondary Owner (If any) </label>
                  <select @if($transation->financial_approval_status=="Completed") disabled="true" @endif class="form-control" name="second_owner" id="second_owner"  disabled=""true" >
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
                       <div class="col-md-3">
                  <label for="name">PO*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="text" id="po" name="po" value="{{$transation->po}}" class="form-control" placeholder="PO"  readonly=""true" >
                  <span class="error_message" id="po_message" style="display: none">Field is required</span>
                </div>
                       <div class="col-md-3">
                  <label for="name">PO Date*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="text" id="po_date" name="po_date" value="{{$transation->po_date}}"  disabled="true" class="form-control" placeholder="PO Date">
                  <span class="error_message" id="po_date_message" style="display: none">Field is required</span>
                </div>
                </div>
                @if($transation->financial_approval_status!="Completed")
                <div class="row">
                 <input type="hidden" id="count_addr_photo" name="count_addr_photo" class="form-control" value="1">
                
<div class="col-md-3" id="p_row_1">
  <label for="image_name">Attach PO Copy</label>
  <input type="file" disabled="true" @if($transation->financial_approval_status=="Completed") disabled="true" @endif id="photo" name="photo[]" accept=".jpg,.jpeg,.png,.pdf"  class="form-control">
  <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf)</p>

  <div class="progress">
                <div class="bar"></div >
                <div class="percent">0%</div >
            </div>

  <div id="preview_photo" class="form-group col-md-12 mb-2"></div>

    <span class="error_message" id="photo_message" style="display: none">Field is required</span>
</div>
 <div class="col-md-3">
  <button type="button" @if($transation->financial_approval_status=="Completed") disabled="true" @endif  disabled="true class="btn btn-default btn-sm"  id="addmore_photo"><span class="glyphicon glyphicon-plus-sign"></span> Add</button>
 </div>

                </div> 

                <div class="row">
                <div id="addphoto"></div>
                </div>
                <div class="row">
                <div class="col-md-3">
                  <label for="name">Attach GST Certificate / Mail confirmation</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="file" disabled="true" id="attach_gst" name="attach_gst" value="{{$transation->attach_gst}}" class="form-control" placeholder="">
                  <span class="error_message" id="attach_gst_message" style="display: none">Field is required</span>
                </div>
                </div>
@endif
                <div class="row">
                <div class="col-md-3">
                <span id="cert_success" style="display: none;color:green;">Successfully Updated</span>
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="lg-btn submit-btn  save_certification" style="display:none" onclick="save_certification()" >Save</button>
                  
                @if($transation->owner!='' && $transation->second_owner!='' && $transation->po!='' && $transation->po_date!='')
               
                @endif
                @if($transation->financial_approval_status!="Completed")
                <button type="button" class="mdm-btn-line submit-btn edit_btn_certification" @if($transation->financial_approval_status=="Completed") disabled="true" @endif onclick="edit_certification()" >Edit</button>
                @endif
                <button type="button" class="lg-btn-line submit-btn  cancel_btn_certification " onclick="cancel_certification()" style="display:none;">Cancel</button>
              </div> 

              </div> 

        
        
        </div><!-- box-body -->
        
        </div>
        </div>   

                
              

   
            <!-- configuration order end -->
          </div>
          <div class="tab-pane " id="terms_condition">
            <!-- podetails Approval start -->
           
            
      <div class="panel panel-default">
    <div class="panel-body ">
    <div class="box-body row @if($transation->financial_approval_status=="Completed") hideform @endif">
                <div class="col-md-12 col-sm-6 col-lg-12" >
                 <h3>Configuration</h3>
                </div>
                 
                    <div class="col-md-6 col-sm-6 col-lg-6" >
                  <label for="name">Standard Warranty*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="text" id="stan_warrenty"  readonly="true" name="stan_warrenty" value="{{$transation->stan_warrenty}}" class="form-control" placeholder="Standard Warranty">
                  <span class="error_message" id="stan_warrenty_message" style="display: none">Field is required</span>
                </div>
                    <div class="col-md-6 col-sm-6 col-lg-6" >
                  <label for="name">Additional Warranty Description*</label>
                  <input  @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="text" id="add_warrenty"  readonly="true"  name="add_warrenty" value="{{$transation->add_warrenty}}" class="form-control" placeholder="Additional Warranty Description">
                  <span class="error_message" id="add_warrenty_message" style="display: none">Field is required</span>
                </div>

                <div class="col-md-12 col-sm-6 col-lg-12">
                
                <span id="conf_success" style="display: none;color:green;">Successfully Updated</span>
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="lg-btn submit-btn  save_warrenty" onclick="save_warrenty()" style="display:none;">Save</button>
                @if($transation->stan_warrenty!='' && $transation->add_warrenty!='')
               
                @endif
                @if($transation->financial_approval_status!="Completed")
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="mdm-btn-line submit-btn edit_btn_warrenty" onclick="edit_warrenty()" >Edit</button>
                @endif
                <button type="button" class="lg-btn-line submit-btn  cancel_btn_warrenty " onclick="cancel_warrenty()" style="display:none;">Cancel</button>
              </div>
    </div>
    </div>
    </div>

      

              <div class="panel panel-default">
          <div class="panel-body ">
          <div class="box-body row @if($transation->financial_approval_status=="Completed") hideform @endif">
          <div class="col-md-12 col-sm-6 col-lg-12">
                 <h3>Delivery terms </h3>
                </div>
                   <div class="col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Specific delivery terms if any*</label>
                    <textarea @if($transation->financial_approval_status=="Completed") disabled="true" @endif  readonly="true"  name="del_terms" id="del_terms" class="form-control" placeholder="Specific delivery terms if any">{{$transation->del_terms}}</textarea>
                  <span class="error_message" id="del_terms_message" style="display: none">Field is required</span>
                </div>
                   <div class="col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Expected date of supply*</label>
                  <input @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="text" readonly="true"  id="expect_date" name="expect_date" value="{{$transation->expect_date}}" class="form-control" placeholder="Expected date of supply">
                  <span class="error_message" id="expect_date_message" style="display: none">Field is required</span>
                </div>
                  
                <div class="col-md-12 col-sm-6 col-lg-12">
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="lg-btn submit-btn  save_delivery" onclick="save_delivery()" style="display:none">Save</button>
                @if($transation->del_terms!='' && $transation->expect_date!='')
               
                @endif
                @if($transation->financial_approval_status!="Completed")
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="mdm-btn-line submit-btn edit_btn_delivery" onclick="edit_delivery()" >Edit</button>
                @endif
                <button type="button" class="mdm-btn-line submit-btn  cancel_btn_delivery " onclick="cancel_delivery()" style="display:none;">Cancel</button>
                <span id="del_success" style="display: none;color:green;">Successfully Updated</span>
               
              </div>
             
              </div>

              </div>
              </div>

              <div class="panel panel-default">
          <div class="panel-body ">
          <div class="box-body row @if($transation->financial_approval_status=="Completed") hideform @endif">
          <div class="col-md-12 col-sm-6 col-lg-12">
                 <h3>Other terms (Warranty, CMC/AMC rates etc.)  </h3>
                </div>
                   <div class="col-md-12 col-sm-6 col-lg-12">
                  <label for="name">Other terms (Warranty, CMC/AMC rates etc.) *</label>
                    <textarea @if($transation->financial_approval_status=="Completed") disabled="true" @endif  readonly="true"  name="other_terms" id="other_terms" class="form-control" placeholder="Other terms (Warranty, CMC/AMC rates etc.)">{{$transation->other_terms}}</textarea>
                  <span class="error_message" id="other_terms_message" style="display: none">Field is required</span>
                </div>
       
                <div class="col-md-12 col-sm-6 col-lg-12">
                <span id="otherterm_success" style="display: none;color:green;">Successfully Updated</span>
               
                <button @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="lg-btn submit-btn  save_other_terms" onclick="save_other_terms()"   style="display:none" >Save</button>


              
                @if($transation->financial_approval_status!="Completed")
                <button  @if($transation->financial_approval_status=="Completed") disabled="true" @endif type="button" class="mdm-btn-line submit-btn  edit_btn_other_terms" onclick="edit_other_terms()" >Edit</button>
                @endif
                <button type="button" class="lg-btn-line submit-btn  cancel_btn_other_terms " onclick="cancel_other_terms()" style="display:none;">Cancel</button>
               
              
              </div>  

              </div> 
            </div>  
            </div> 



            <!-- podetails approval end -->
          </div>

         
         
       </div>
       </div>
     
           
              </div>
              <!-- End menu end -->
<!-- ******************************************************************************************************** -->
            <!-- Purchase order end -->
          </div>
          <div class="tab-pane @if($type=="tech") active @endif" id="technical_approval">
            <!-- Technical Approval start -->
        

            <div class="box-body row">

                <div class="panel panel-default">
                <div class="panel-body ">
                  <h2>Configuration</h2>
               
                  <table id="cmsTable" class="table table-bordered table-striped data- @if($transation->financial_approval_status=="Completed") hideform @endif" >
                <thead>
                  <tr>

                    <th>Name</th>
                    <th>Qty</th>
            
                    <th>Unit Price</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                    
                        <th>MSP</th>
                        <th>Surplus / Deficit</th>
                    <th>Net Amount</th>

                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {
                  $myTotal = 0; 
                  $tot_cgst=0;
                  $tot_sgst=0;
                  $tot_igst=0;
                  $tot_igst=0;
                  $tot_cess=0;
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
                      <td>{{$values->surplus_amt}}</td>
                      <td>{{$values->msp}}</td>
                      <td>{{$values->amt}}</td>

                    </tr>
                    <?php
                        $myTotal = $values->amt+$myTotal;
                        $tot_cgst =$values->cgst+$tot_cgst;
                        $tot_sgst = $values->sgst+$tot_sgst;
                         $tot_igst = $values->igst+$tot_igst;
                        $tot_cess = $values->cess+$tot_cess;
                        
                  }
                  ?>
<tr class="footertr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
        
                
              
                <td>{{$tot_cgst}}</td>
                <td>{{$tot_sgst}}</td>
                <td>{{$tot_igst}}</td>
              
                <td>{{$tot_cess}}</td>
                <td></td>
                <td></td>
               <td>{{$myTotal}}</td><td></td></tr>
                  <?php
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

             

                </div>
                </div>

                </div>

                
            <div class="box-body row bdr-split">
                <div class="tech-left col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group config-sect box-boder">
                      <div class="panel panel-default">
                      <div class="panel-body ">
                     <h2>Warranty </h2>
                     <table class="table tech-table2" >
                            <thead>
                              <tr>
                                <th>Standard Warranty</th>
                                <th>Additional Warranty</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$transation->stan_warrenty}}</td>
                                    <td>{{$transation->add_warrenty}}</td>
                                </tr>
                            </tbody>
                         </table>
                      </div>
                      </div>
                </div>
                <div class="form-group po-sect box-boder">
                <div class="panel panel-default">
                <div class="panel-body ">
                    <h2>PO Details</h2>
                     <table class="table tech-table3" >
                      <thead>
                        <tr>
                           <?php
                if($transation->user_id>0)
                {
                  $user_det = App\User::find($transation->user_id);
                  $gstno=$user_det->gst;
                  if($user_det)
                  {
                    $customer_name=$user_det->business_name;
                  }
                  if($user_det->state_id>0)
                  {
                    $state = App\State::find($user_det->state_id);
                    $state_name=$state->name;
                  }
                  if($user_det->district_id>0)
                  {
                    $district = App\District::find($user_det->district_id);
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
                          <th>PO Details</th>
                          <th>State</th>
                          <th>District</th>
                          <th>Customer Name</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{{$transation->collect_date}}</td>
                              <td>@if($state_name)
                              {{$state_name}}
                              @endif</td>
                              <td>@if($district_name)
                  {{$district_name}}
                  @endif</td>
                              <td>@if($customer_name)
                  {{$customer_name}}
                  @endif</td>
                          </tr>
                      </tbody>
                   </table>


               <table class="table tech-table4" >
                    <thead>
                        <tr>
                          <th>Shipping Address</th>
                          <th>Contact Person</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{{$transation->user_shipping}}</td>
                              <td>@if($contact_person_name)
                  {{$contact_person_name}}
                  @endif</td>
                          </tr>
                        </tbody>
                   </table>



                   <table class="table tech-table5" >
                  <thead>
                    <tr>
                      <th>Mail</th>
                      <th>GST Number </th>
                      <th>Designation*</th>
                      <th>Phone*</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>@if($contact_person_email)
                  {{$contact_person_email}}
                  @endif</td>
                          <td>@if($gstno)
                  {{$gstno}}
                  @endif</td>
                          <td>{{$transation->add_warrenty}}</td>
                          <td>@if($contact_person_phone)
                  {{$contact_person_phone}}
                  @endif</td>
                      </tr>
                  </tbody>
               </table>

          


                </div>
                </div>
                </div>
                 <div class="form-group buton-sect" >
                 
                
                  <?php 
                    if($transation->current_status=="Technical Approval")
                    {
                      echo '<button type="button" class="lg-btn submit-btn  approval_customer_approval"  onclick="approval_customer()">Approve</button>';
                      echo '  <button type="button" class="lg-btn submit-btn  approval_customer_approved" style="display:none;"  >Approved</button>';
                      echo '  <button type="button" class="lg-btn submit-btn "  onclick="edit_sale_order()" >Edit</button>';
                      
                    }
                    else{
                      echo '  <button type="button" class="mdm-btn-line submit-btn "  >Approved</button>';
                    }
                    ?>
 <span id="ownererror" style="color:red;display:none;">
                Please add owner engineer!
                </span>
                  </div>
                </div>
                 <div class="tech-right col-md-6 col-sm-6 col-lg-6">
                     <div class="technical-dtl box-boder">
           
                       <div class="panel panel-default">
                       <div class="panel-body ">
                         <div class="row">
                       <?php
                       if(count($transation_pocopy)>0)
                       {
                         foreach($transation_pocopy as $values)
                         {
                           $imgpath=asset("storage/app/public/transation/$values->image_name");
                           ?>
                           
                           <div class="col-md-12">
                           <iframe src="<?php echo $imgpath;?>" height="300" width="600"></iframe>
                           </div>
                         </div>
                         <div class="row">
                           <?php
                         }
                       }
                       if($transation->attach_gst!='')
                       {
                         $imgpath=asset("storage/app/public/transation/$transation->attach_gst");
                         ?>
                         <div class="col-md-12">
                         <iframe src="<?php echo $imgpath;?>" height="300" width="600"></iframe>
                         </div>
                         <?php
                       }
                       ?>
                     </div>
                       </div>
                       </div>

                 </div>
                 </div>


                 
                </div>

                


               

            <!-- Technical approval end -->
          </div>
          <div class="tab-pane @if($type=="msp") active @endif" id="otherpro">

          <table id="cmsTable" class="table table-bordered table-striped data- @if($transation->financial_approval_status=="Completed") hideform @endif" >
                <thead>
                  <tr>
                    <th>No.</th>
                  
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>MSP of one Unit</th>
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
                    <td data-th="No." >{{$p}}</td>
                  

                    <td data-th="Name" >{{$product->name}}</td>
                <td data-th="Qty" >{{$values->quantity}}</td>
               <td data-th="Unit Price" >{{number_format((float)$values->sale_amount, 2, '.', '')}}

               </td>

               <td data-th="MSP Of One Unit" ><input @if($transation->current_status!="MSP Approval") disabled="true" @endif  oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" style="width:80px;" value="{{$values->msp/$values->quantity}}" id="mspamount_{{$values->product_id}}"  class="msp" name="msp[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_msp({{$values->product_id}})"  onkeyup="change_msp({{$values->product_id}})">
              </td>
               <td data-th="Net Amount" >{{$values->sale_amount*$values->quantity}}
               <input type="hidden" value="{{$values->amt}}" id="saleamount_{{$values->product_id}}"  class="salemaount" name="salemaount[]" data-id="{{$values->product_id}}" style="width:60px;" >
               </td>
               <td data-th="Surplus / Deficit" >
               <?php
               $net_amount=$values->amt;
               
               $total_net +=$values->sale_amount*$values->quantity;
               $msp=$values->msp;
               $total_msp +=$msp;
               $diffe=$net_amount-$msp;
               $total_ins +=$values->insentive;

               $diffe= $values->sale_amount-$values->msp;
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
               <span style="color:<?php echo $column_color;?>" class="surplus_{{$values->product_id}}">{{$diffe}}</span> </td>
               <td data-th="Insentive" ><input @if($transation->current_status!="MSP Approval") disabled="true" @endif  oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" value="{{$values->insentive}}" id="insentive_{{$values->product_id}}"  class="insentive" name="insentive[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_incentive({{$values->product_id}})"  onkeyup="change_incentive({{$values->product_id}})">
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
                  <td id="total_insentive">{{$total_ins}}</td>
                  
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
                   type="text" @if($transation->current_status!="MSP Approval") disabled="true" @endif  onchange="change_ownerval()"  onkeyup="change_ownerval()" value="{{$transation->per_owner}}" id="owner_value"  class="owner_value" name="owner_value"  style="width:60px;" ><span>%</span>
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
                   type="number"  @if($transation->current_status!="MSP Approval") disabled="true" @endif onchange="change_secondval()"   onkeyup="change_secondval()"  value="{{$transation->per_second_owner}}" id="secondowner_value"  class="secondowner_value" name="secondowner_value"  style="width:60px;" ><span>%</span>
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
                  @if($transation->current_status!="Technical Approval" && $transation->current_status!="MSP Approval")
                  
                    <button type="button" class="lg-btn submit-btn "  >Approved</button>
                  @endif
                  @if($transation->current_status=="MSP Approval")
                  
                    <button type="button" class="lg-btn submit-btn  approval_msp_not_approve"  onclick="approval_msp_owner()">Approve</button>
                    <button type="button" class="lg-btn submit-btn  approval_msp_owner" style="display:none;" >Approved</button>
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
                   // $stock_exit =  DB::select("select * from stock_register where `product_id`='".$values->product_id."' order by id desc limit 1  ");
                   $stock_exit =  DB::select(" select *,sum(stock_in_hand) as tot_stock from  stock_register where `product_id`='".$values->product_id."'  group by product_id ");
                  
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
               <td data-th="No." ><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
              <td data-th="Name" ><input type="text" @if($transation->stock_terms=="Y") readonly="true" @endif    value="{{$values->quantity}}" id="qn_stock_{{$values->product_id}}" name="stock_quantity[]" class="quantity" onkeyup="update_qty(this.value,{{$values->product_id}})" data-id="{{$values->product_id}}" style="width:40px;">
              <div class="update_qty_{{$values->product_id}}" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </td>
              <td data-th="Qty" ><input type="text"  disabled="true"  value="{{$stock_count}}" id="qn__avail_stock{{$values->product_id}}" name="stok_in_hand_qty[]" class="quantity"  data-id="{{$values->product_id}}" style="width:40px;"></td>
              
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
                   // echo '<button type="button" class="mdm-btn-line submit-btn"  onclick="edit_stock_terms()">Edit</button>';
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
          <div class="tab-pane @if($type=="fin") active @endif"" id="financial_approval">

          <div class="panel panel-default">
                 <div class="panel-body ">

                  <div class="form-group col-md-12 ">
                  <h3>Payment terms </h3>
                  <p>{{$transation->payment_terms}}</p>
                
                  <div class="box-body row">

                  <div class="col-md-4 col-sm-6 col-lg-4" >
                  <label for="name">Customer Name</label>
                  <select class="form-control" name="user_id" id="user_id"  disabled="true" >
                    <option value="">Customer Name</option>
                    @foreach($user as $val_user)
                    <?php
                       $sel = ($transation->user_id == $val_user->id) ? 'selected': '';
                    echo '<option value="'.$val_user->id.'" '.$sel.'>'.$val_user->business_name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  </div>
                <div class="col-md-4 col-sm-6 col-lg-4" >
                  <label for="name">Out Standing Amount{{$transation->current_status}}</label>
                  <input type="number"  @if($transation->current_status!="Financial Approval" ) disabled="true" @endif  id="out_standing_amount" name="out_standing_amount" value="{{$transation->out_standing_amount}}" class="form-control" placeholder="Out Standing Amount">
                  <span class="error_message" id="out_standing_amount_message" style="display: none">Field is required</span>
                </div>
                <!-- <div class="col-md-4 col-sm-6 col-lg-4" >
                  <label for="name">Due Date</label>
                  <input type="text" @if($transation->current_status!="Financial Approval") disabled="true" @endif  id="duedate" name="duedate" value="{{$transation->duedate}}" class="form-control" placeholder="Due Date">
                  <span class="error_message" id="duedate_message" style="display: none">Field is required</span>
                </div> -->

                <div class="col-md-4 col-sm-6 col-lg-4" >
                  <label for="name">Payment Flow</label>
                  <select class="form-control" name="invoice_complete_flow_id" id="invoice_complete_flow_id"  @if($transation->current_status!="Financial Approval" && $transation->current_status!="Technical Approval"  && $transation->current_status!="MSP Approval") disabled="true" @endif  >
                    <option value="">Payment Flow</option>
                    @foreach($invoice_complete_flow as $val_invoice_com)
                    <?php
                       $sel = ($transation->invoice_complete_flow_id == $val_invoice_com->id) ? 'selected': '';
                    echo '<option value="'.$val_invoice_com->id.'" '.$sel.'>'.$val_invoice_com->type_name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="invoice_complete_flow_id_message" style="display: none">Field is required</span>
                  </div>



                  
                  
                  <?php  
                  if($transation->current_status=="Financial Approval")
                  {
                    echo '<button type="button" class="lg-btn submit-btn  payment_terms_approval"  onclick="payment_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="lg-btn submit-btn  payment_terms_approved" style="display:none;"  >Approved</button>';
                  
                  }

                  if($transation->current_status!="Financial Approval" && $transation->current_status!="Technical Approval"  && $transation->current_status!="MSP Approval" ){
                    echo '  <button type="button" class="lg-btn submit-btn  "  >Approved</button>';
                  }
                  ?>


                <div class="form-group col-md-12 ">
               
                @if(count($invoice)>0)
                <h3>Invoice History </h3>
                <table id="cmsTable_invoice" class="table table-bordered table-striped data-"> 
                  <tr>
                   
                    <th>Invoice Id</th>
                    <th>Status</th>
                    <th>Invoice Date</th>
                    <th>Dispatch Confirmation</th>
                    <th>Delivery Confirmation</th>
                    <th>User Confirmation</th>
                    <th>Department Confirmation</th>
                    <th>Finance  Confirmation</th>
                    <th>Payment  Confirmation</th>
                  
                </tr>
                  @foreach($invoice as $values_inv)
                 <tr>
                
                    <td>{{$values_inv->invoice_id}}</td>
                    <td>{{$values_inv->status}}</td>
                    <td>{{$values_inv->invoice_date}}</td>
                    <td>{{$values_inv->dispatch_date}}</td>
                    <td>{{$values_inv->delivery_date}}</td>
                    <td>{{$values_inv->user_date}}</td>
                    <td>{{$values_inv->department_date}}</td>
                    <td>{{$values_inv->finance_date}}</td>
                    <td>{{$values_inv->payment_date}}</td>
                </tr> 
                @endforeach
                </table>
                @endif

                </div>


                  </div>

                  </div>

                


                </div>
              </div>

          </div>
         
          <div class="tab-pane " id="cust_conform">Customer 
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
        <form name="add_address" id="add_address" method="post" autocomplete="off">

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



<div class="modal fade" id="modal_success_tran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Successfully Approved Transaction</p>
      </div>
      <div class="modal-footer">
       
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
        <form autocomplete="off">

      <div class="display_address"></div>



        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="mdm-btn submit-btn"  onclick="add_shipaddress()" >Add</button>
        <button type="button" class="mdm-btn cancel-btn " data-dismiss="modal">Close</button>

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

function cancel_podetails()
    {
      $("#collect_date").attr("disabled", "disabled");
      $("#user_shipping").attr("readonly", "readonly");
    
      $("#contact_id").attr("disabled", "disabled");
      $("#designation").attr("disabled", "disabled");
      $("#department_id").attr("disabled", "disabled");
      $(".save_podetails").hide();
       
      $(".edit_btn_pode").show();
      $(".cancel_btn_pode").hide();
    }

     function edit_podetails()
    {
      $(".save_podetails").show();
      $("#collect_date").removeAttr("disabled")
      $("#user_shipping").removeAttr("readonly");
      $("#contact_id").removeAttr("disabled");
      $("#designation").removeAttr("disabled");
      $("#department_id").removeAttr("disabled");
      $(".edit_btn_pode").hide();
      $(".cancel_btn_pode").show();
     
      
    }
    function save_podetails()
    {
      var collect_date=$("#collect_date").val();
      
      if(collect_date!='')
      {
        $("#collect_date_message").hide();
      }
      else{
        $("#collect_date_message").show();
      }
      var user_address=$("#user_address").val();
      var user_shipping=$("#user_shipping").val();
      var designation=$("#designation").val();
      var contact_phone=$("#contact_phone").val();
      var contact_mail=$("#contact_mail").val();
      var contact_id=$("#contact_id").val();
      
      var gst=$("#gst").val();
      
      
      if(collect_date!='')
      {
        var url = APP_URL+'/staff/save_po_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,collect_date:collect_date,user_address:user_address,user_shipping:user_shipping,contact_id:contact_id,designation:designation,contact_phone:contact_phone,contact_mail:contact_mail,gst:gst
                  },
                  success: function (data)
                  {
                    $(".save_podetails").hide();
                    $("#po_success").show();
                    setTimeout(function(){ $("#po_success").hide(); }, 3000);
                    $("#collect_date").attr("disabled", "disabled");
                    $("#user_shipping").attr("readonly", "readonly");
                 
                    $("#contact_id").attr("disabled", "disabled");
                    $("#designation").attr("disabled", "disabled");
                    $("#department_id").attr("disabled", "disabled");
                   

                  }
          });

      }

    }
    function edit_certification(){
     
      $(".save_certification").show();
      $("#owner").removeAttr("disabled");
      $("#second_owner").removeAttr("disabled");
      $("#po").removeAttr("readonly");
      $("#po_date").removeAttr("disabled");
      $("#photo").removeAttr("disabled");
      $("#addmore_photo").removeAttr("disabled");
      $("#attach_gst").removeAttr("disabled");
      $(".cancel_btn_certification").show();
      $(".edit_btn_certification").hide();

    }

    function  cancel_certification()
    {
      $(".save_certification").hide();
 
    
      $("#owner").attr("disabled", "disabled");
      $("#second_owner").attr("disabled", "disabled");
      $("#po").attr("readonly", "readonly");
      $("#po_date").attr("readonly", "readonly");
      $(".edit_btn_certification").show();
      $(".cancel_btn_certification").hide();
    }

    function save_certification(){
    
      var owner=$("#owner").val();
      var second_owner=$("#second_owner").val();
      var po=$("#po").val();
      var po_date=$("#po_date").val();
      if(owner!='')
      {
        $("#owner_message").hide();
      }
      else{
        $("#owner_message").show();
      }
      // if(second_owner!='')
      // {
      //   $("#second_owner_message").hide();
      // }
      // else{
      //   $("#second_owner_message").show();
      // }
      if(po!='')
      {
        $("#po_message").hide();
      }
      else{
        $("#po_message").show();
      }
      if(po_date!='')
      {
        $("#po_date_message").hide();
      }
      else{
        $("#po_date_message").show();
      }

      if(owner!='' &&   po!='' && po_date!='')
      {
       
       $("#ownererror").hide();
        var url = APP_URL+'/staff/save_certifi_transation';
        var transation_id="<?php echo $transation->id?>";
        var bar = $('.bar');
            var percent = $('.percent');
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,owner:owner,second_owner:second_owner,po:po,po_date:po_date
                  },
                  beforeSend: function() {
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
                  success: function (data)
                  {
                    $(".save_certification").hide();
                    $("#frm_brand").submit();
                    $("#cert_success").show();
                    setTimeout(function(){ $("#cert_success").hide(); }, 3000);
                    $("#owner").attr("readonly", "readonly");
                    $("#second_owner").attr("readonly", "readonly");
                    $("#po").attr("readonly", "readonly");
                    $("#po_date").attr("readonly", "readonly");

                  }
          });

      }

    }
    
    function edit_payment_term()
    {
      $("#payment_terms").removeAttr("readonly");
    }
    function save_payment_term()
    {
      
      var payment_terms=$("#payment_terms").val();
      if(payment_terms!='')
      {
        $("#payment_terms_message").hide();
      }
      else{
        $("#payment_terms_message").show();
      }
      if(payment_terms!='')
      {
        var url = APP_URL+'/staff/save_payment_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,payment_terms:payment_terms
                  },
                  success: function (data)
                  {
                    $("#pay_success").show();
                    setTimeout(function(){ $("#pay_success").hide(); }, 3000);
                    $("#payment_terms").attr("readonly", "readonly");
                  }
          });

      }

    }
    function edit_delivery()
    {
      $(".save_delivery").show();
      $("#del_terms").removeAttr("readonly");
      $("#expect_date").removeAttr("readonly");
      $(".edit_btn_delivery").hide();
      $(".cancel_btn_delivery").show();
    }
    function cancel_delivery()
    {
    $(".edit_btn_delivery").show();
    $(".save_delivery").hide();
    $(".cancel_btn_delivery").hide();
    $("#del_terms").attr("readonly", "readonly");
    $("#expect_date").attr("readonly", "readonly");
    }

    function save_delivery()
    {  
      var del_terms=$("#del_terms").val();
      var expect_date=$("#expect_date").val();
      if(del_terms!='')
      {
        $("#del_terms_message").hide();
      }
      else{
        $("#del_terms_message").show();
      }
      if(expect_date!='')
      {
        $("#expect_date_message").hide();
      }
      else{
        $("#expect_date_message").show();
      }
      if(del_terms!='' && expect_date!='')
      {
            
        var url = APP_URL+'/staff/save_delivery_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,del_terms:del_terms,expect_date:expect_date
                  },
                  success: function (data)
                  {
                    $(".save_delivery").hide();
                    $("#del_success").show();
                    setTimeout(function(){ $("#del_success").hide(); }, 3000);
                    $("#del_terms").attr("readonly", "readonly");
                    $("#expect_date").attr("readonly", "readonly");
                    $(".save_delivery").hide();
                    $(".cancel_btn_delivery").hide();
                    $(".edit_btn_delivery").show();
                  }
          });


      }

    }

    
    function edit_other_terms()
    {
      $(".save_other_terms").show();
      $("#other_terms").removeAttr("readonly");
      $(".edit_btn_other_terms").hide();
        
        $(".cancel_btn_other_terms").show();
        
    }

    function cancel_other_terms()
    {
    $(".edit_btn_other_terms").show();
    $(".save_other_terms").hide();
    $(".cancel_btn_other_terms").hide();
    $("#other_terms").attr("readonly", "readonly");
    }

    function save_other_terms()
    {
      var other_terms=$("#other_terms").val();
      if(other_terms!='')
      {
        $("#other_terms_message").hide();
      }
      else{
        $("#other_terms_message").show();
      }
      if(other_terms!='')
      {
        var url = APP_URL+'/staff/save_other_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,other_terms:other_terms
                  },
                  success: function (data)
                  {
                    $(".save_other_terms").hide();
                    $("#otherterm_success").show();
                    setTimeout(function(){ $("#otherterm_success").hide(); }, 3000);
                    $("#other_terms").attr("readonly", "readonly");
                    $(".edit_btn_other_terms").show();
                    $(".save_other_terms").hide();
                    $(".cancel_btn_other_terms").hide();
                  }
          });

      }
    }
    
    function edit_warrenty()
    {
    $(".save_warrenty").show();
      $("#stan_warrenty").removeAttr("readonly");
      $("#add_warrenty").removeAttr("readonly");
      $(".cancel_btn_warrenty").show();
      $(".edit_btn_warrenty").hide();
      
    }
    function cancel_warrenty()
    {
$(".edit_btn_warrenty").show();
$(".save_warrenty").hide();
$(".cancel_btn_warrenty").hide();
$("#stan_warrenty").attr("readonly", "readonly");
                    $("#add_warrenty").attr("readonly", "readonly");  
    }

    function save_warrenty()
    {
      var stan_warrenty=$("#stan_warrenty").val();
      var add_warrenty=$("#add_warrenty").val();
      if(stan_warrenty!='')
      {
        $("#stan_warrenty_message").hide();
      }
      else{
        $("#stan_warrenty_message").show();
      }

      if(add_warrenty!='')
      {
        $("#add_warrenty_message").hide();
      }
      else{
        $("#add_warrenty_message").show();
      }

      if(stan_warrenty!='' && add_warrenty!='')
      {
        var url = APP_URL+'/staff/save_config_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,add_warrenty:add_warrenty,stan_warrenty:stan_warrenty
                  },
                  success: function (data)
                  {
                    $(".save_warrenty").hide();
                    $("#conf_success").show();
                    setTimeout(function(){ $("#conf_success").hide(); }, 3000);
                    $("#stan_warrenty").attr("readonly", "readonly");
                    $("#add_warrenty").attr("readonly", "readonly");
                    $(".edit_btn_warrenty").show();
                    $(".save_warrenty").hide();
                    $(".cancel_btn_warrenty").hide();

                  }
          });
    
      }
    }

   

    function approval_msp_owner(){
        var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='MSP Approval';
        var id='<?php echo $transation->id;?>';
        var user_id=$("#user_id").val();
        var owner_value=$("#owner_value").val();
        var secondowner_value=$("#secondowner_value").val();
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            status: type_approval,trans_id:id,user_id:user_id,owner_value:owner_value,secondowner_value:secondowner_value,
          },
          success: function (data)
          {
            $("#modal_success_tran").modal('show');
            $(".approval_msp_not_approve").hide();
                    $(".approval_msp_owner").show();
                    var url = APP_URL+'/staff/Pendingtransaction';
                    setTimeout(function(){ location.href=url; }, 2000);
          }
   });

      }

    function change_ownerval(){
      var owner_value=$("#owner_value").val();
      if(owner_value>100)
      {
        alert('Please enter less than 100')
        $("#owner_value").val('');
        $("#owner_persen").html(' ');
      }
      else{

        var secondowner_value=$("#secondowner_value").val();
        if(secondowner_value>0)
        {
          var send_ow_val=parseInt(secondowner_value)+parseInt(owner_value);
          if(send_ow_val>100)
          {
            alert('Please check value')
            $("#owner_value").val('');
            $("#owner_persen").html(' ');
            
          }else{
            var total_insentive=$("#total_insentive").html();
            var per=total_insentive * owner_value / 100;
            $("#owner_persen").html(per);
          }
         
           

        }
        else{
          var total_insentive=$("#total_insentive").html();
            var per=total_insentive * owner_value / 100;
            $("#owner_persen").html(per);
        }
      
        

      }
     
      
      
    /*  var owner_value=$("#owner_value").val();
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
    $("#secondowner_persen").html(discount_owner);*/
    }
    function change_secondval(){
      
      var secondowner_value=$("#secondowner_value").val();
      if(secondowner_value>100)
      {
        alert('Please enter less than 100')
        $("#secondowner_value").val('');
        $("#secondowner_persen").html(' ');
      }
      else{

        var owner_value=$("#owner_value").val();
        if(owner_value>0)
        {
          var send_ow_val=parseInt(secondowner_value)+parseInt(owner_value);
          if(send_ow_val>100)
          {
            alert('Please check value');
            $("#secondowner_persen").html(' ');
           
          }else{
            var total_insentive=$("#total_insentive").html();
      var per=total_insentive * secondowner_value / 100;
      $("#secondowner_persen").html(per);
          }
          
          
        }else{
          var total_insentive=$("#total_insentive").html();
      var per=total_insentive * secondowner_value / 100;
      $("#secondowner_persen").html(per);
        }

        

      }

      /*var owner_value=$("#owner_value").val();
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
    $("#secondowner_persen").html(discount_owner);*/


    }
    
    function change_incentive(product_id)
    {

        $(".inse_loader_"+product_id).show();
        var transation_id='<?php echo $transation->id;?>';
        var insentive=$("#insentive_"+product_id).val();
        var url = APP_URL+'/staff/save_transation_insentive';
      
var tot=0;
$('input[name="insentive[]"]').each(function(){
  if($(this).val()>0)
  {
    tot =parseInt($(this).val())+parseInt(tot);
  }
 
});
$("#total_insentive").html(tot);

if($("#owner_value").val()>0)
{
  //tot
  var total_insentive=$("#total_insentive").html();
var per=total_insentive * $("#owner_value").val() / 100;
$("#owner_persen").html(per);
}

if($("#secondowner_value").val()>0)
{
  //tot
  var total_insentive=$("#total_insentive").html();
var per=total_insentive * $("#secondowner_value").val() / 100;
$("#secondowner_persen").html(per);
}





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
                $(".surplus_"+product_id).css("color",column_color );

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
        var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='Financial Approval';
        var id='<?php echo $transation->id;?>';
        var user_id=$("#user_id").val();
        var duedate=$("#duedate").val();
        
        var invoice_complete_flow_id=$("#invoice_complete_flow_id").val();
        var out_standing_amount=$("#out_standing_amount").val();
        if(invoice_complete_flow_id=='')
        {
          $("#invoice_complete_flow_id_message").show();
        }
        else{
          $("#invoice_complete_flow_id_message").hide();
          $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  status: type_approval,trans_id:id,user_id:user_id,duedate:duedate,out_standing_amount:out_standing_amount,invoice_complete_flow_id:invoice_complete_flow_id,
                },
                success: function (data)
                {
                  $("#modal_success_tran").modal('show');
                  $(".payment_terms_approval").hide();
                  $(".payment_terms_approved").show();
                  var url = APP_URL+'/staff/Pendingtransaction';
                  setTimeout(function(){ location.href=url; }, 2000);
                }
        });
        }
        

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

      function edit_sale_order()
      {
       $(".purchase_order_click").trigger("click");
       $(".purchase_order_click").focus();
       $("#purchase_order_click").removeClass('disabled')
      }

         function approval_customer(){
          var owner=$("#owner").val();
          if(owner>0)
          {
            $("#ownererror").hide();
            var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='Technical Approval';
        var id='<?php echo $transation->id;?>';
        var user_id=$("#user_id").val();
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    status: type_approval,trans_id:id,user_id:user_id,
                  },
                  success: function (data)
                  {
                    $("#modal_success_tran").modal('show');
                    $(".approval_customer_approval").hide();
                    $(".approval_customer_approved").show();
                    var url = APP_URL+'/staff/Pendingtransaction';
                    setTimeout(function(){ location.href=url; }, 2000);
                  }
          });
          }
          else{
          /*  $(".purchase_order_click").trigger("click");
       $("#owner").focus();
       $("#purchase_order_click").removeClass('disabled')*/
       $("#ownererror").show();
          }
       

      }



    $('#user_id').select2();
    $('#op_id').select2();
    $('#product_id').select2();
    $('#state_id').select2();
    $('#district_id').select2();
    $('#owner').select2();
    $('#second_owner').select2();
    
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
        $("#product_error").hide();
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
            var tax=proObj[i]["tax_per"];
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

                  var diffe=sale_amount-proObj[i]["msp"];
                          htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
                         
                          htmlscontent += '<td><input type="text" value="'+quantity+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
                         
                          '<td><input type="text" value="'+sale_amount+'" name="sale_amount[]" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+

                          '<td><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'" name="hsn[]"  class="hsn form-control" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
                          '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+cgst_per+'%)</p></span>'+
                          '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+sgst_per+'%)</p></span>'+
                          '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" ><p>('+igst_per+'%)</p></span>'+
                          '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" ><p>('+cess_per+'%)</p></span>'+
                          '<input type="hidden" name="purchase_product_id[]" value="0">'+
                          ' <input type="hidden" name="transation_product_id[]" value="0"><input type="hidden" name="tax_percentage[]" value="'+proObj[i]["tax_percentage"]+'">';
                          if(proObj_msp[0]["pro_msp"]>0)
                          {
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["pro_msp"]+'" data-val="'+proObj_msp[0]["pro_msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                          }else{
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["pro_msp"]+'" data-val="'+proObj_msp[0]["pro_msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                          }
                          htmlscontent +=
                          '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="delete-btn " onclick="deletepro('+proObj[i]["id"]+',1)" data-id="'+proObj[i]["id"]+'"  title="Delete"><img src="{{ asset('images/delete.svg') }}"></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
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
            var proObj_msp = JSON.parse(res[1]);
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
                    sale_amount = proObj_msp[0]["cost"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                
                  amt = quantity * sale_amount;
                //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
                   var tax=proObj_msp[0]["tax_per"];
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
                  var diffe=sale_amount-proObj_msp[0]["pro_msp"];
                 
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
              
              htmlscontent += '<td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" name="quantity[]" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
              
              '<td><input type="text" name="sale_amount[]" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+
               '<td><input type="text" readonly="true" value="'+proObj_msp[0]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'"  class="hsn form-control" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
               '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+cgst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text"  readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+sgst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" ><p>('+igst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" ><p>('+cess_per+'%)</p></span>'+
               '<input type="hidden" name="purchase_product_id[]" value="0">'+
               ' <input type="hidden" name="transation_product_id[]" value="0"><input type="hidden" name="tax_percentage[]" value="'+proObj[i]["tax_percentage"]+'">';
               if(proObj_msp[0]["pro_msp"]>0)
                {
                htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["pro_msp"]+'" data-val="'+proObj_msp[0]["pro_msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                }else{
                htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["pro_msp"]+'" data-val="'+proObj_msp[0]["pro_msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                }
                htmlscontent +=
               '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="delete-btn " onclick="deletepro('+proObj[i]["id"]+',0)" data-id="'+proObj[i]["id"]+'"  title="Delete"><img src="{{ asset('images/delete.svg') }}"></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
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

        console.log('arr_product'+arr_total)
      
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
              var res = data.split("*1*");
              var proObj = JSON.parse(res[0]);
              var proObj_msp = JSON.parse(res[1]);

              for (var i = 0; i < proObj.length; i++) {
              //  var  sale_amount = proObj[i]["unit_price"];;
              var sale_amount=$("#sa_"+product_id).val();
                var amt    = quantity * sale_amount;
               var taxab_amount=amt;
               // taxab_amount taxable_amount
               var tax=proObj_msp[i]["tax_per"];

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
               
               //   $("#surplus_amt_"+product_id).val(one_su_plus*quantity);
             //   $("#msp_"+product_id).val(one_msp*quantity);

               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt.toFixed(2));
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
             var res = data.split("*1*");
              var proObj = JSON.parse(res[0]);
              var proObj_msp = JSON.parse(res[1]);

              for (var i = 0; i < proObj.length; i++) {
                var amt    = quantity * sale_amount;
                var unit_price=sale_amount;
                var max_sale_amount=proObj[i]["max_sale_amount"];
                var min_sale_amount=proObj[i]["min_sale_amount"];
                
                //alert(unit_price);
                 max_sale_amount=parseInt(max_sale_amount);
                  min_sale_amount=parseInt(min_sale_amount);
                  unit_price=parseInt(unit_price);
                  sale_amount= $("#sa_"+product_id).val();
               
              
           

                var tax=proObj_msp[i]["tax_per"];
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
               
                //  $("#surplus_amt_"+product_id).val(one_su_plus*quantity);
              //  $("#msp_"+product_id).val(one_msp*quantity);

              
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
      if (confirm("Are you sure you want to delete this product?")) {

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

  
  $( "#sales_type" ).change(function() {
    if($(this).val()==""){
      $("#sales_type_message").show();
    }
    else{
      $("#sales_type_message").hide();
    }
});

$( "#state_id" ).change(function() {
    if($(this).val()==""){
      $("#state_id_message").show();
    }
    else{
      $("#state_id_message").hide();
    }
});
$( "#district_id" ).change(function() {
    if($(this).val()==""){
      $("#district_id_message").show();
    }
    else{
      $("#district_id_message").hide();
    }
});
$( "#user_id" ).change(function() {
    if($(this).val()==""){
      $("#user_id_message").show();
    }
    else{
      $("#user_id_message").hide();
    }
});
$( "#type_conf" ).change(function() {
    if($(this).val()==""){
      $("#type_conf_message").show();
    }
    else{
      $("#type_conf_message").hide();
    }
});

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
  var user_id='<?php echo $transation->user_id;?>'
 
  $(".addlink").attr("href", "http://dentaldigital.in/beczone/staff/customer/"+user_id+"/edit");


}

jQuery("#addmore_photo").click(function() {
//alert()
var count_addr_photo = $("#count_addr_photo").val();
    var add_count        = parseInt(count_addr_photo)+1;
    $("#count_addr_photo").val(add_count);

    var htmls=' <div class="row"><div class="form-group col-md-8" id="p_row_'+add_count+'">'+
                  '<input type="file" id="photo'+add_count+'" name="photo[]" accept=".jpg,.jpeg,.png" onchange="loadPreview(this,preview_photo'+add_count+')" class="form-control">'+
                  '<p class="help-block">(Allowed Type: jpg,jpeg,png )</p>'+
                  '<div id="preview_photo'+add_count+'" class="form-group col-md-12 mb-2"></div>'+

                    '<span class="error_message" id="photo_message" style="display: none">Field is required</span>'+
                '</div>'+
                '  <div class="form-group  col-md-4" id="pr_row_'+add_count+'">'+
               ' <button type="button" class="btn btn-danger" onClick="remove_photo('+add_count+')">Remove</button>'+
               ' </div></div>';
    $("#addphoto").append(htmls);

});

function addmore_photo()
  {alert()
   
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
           // minDate: 0
        });
        $('#duedate').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0
        });
$('#collect_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           // minDate: 0
        });
$('#expect_date').datepicker({
    //dateFormat:'yy-mm-dd',
    dateFormat:'yy-mm-dd',
    minDate: 0
});
</script>
<style>
.disabled{
    pointer-events:none;
    opacity:0.4;
}
 
        .progress { position:relative; width:100%; }
        .bar { background-color: #b5076f; width:0%; height:20px; }
        .percent { position:absolute; display:inline-block; left:50%; color: #040608;}
   </style>
@endsection
