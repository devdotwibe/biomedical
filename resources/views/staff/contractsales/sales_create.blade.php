@extends('staff/layouts.app')

@section('title', 'Create Oppertunity Contract')

@section('content')

<section class="content-header">
  <h1>
    Create Opportunity Sales Contract
  </h1>
  <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

      <?php /*
      <li><a href="{{route('staff.contract-index')}}">Manage Contract</a></li>
      <li class="active">Add Contract</li>
      */ ?>
  </ol>
</section>

<section class="content">
  <div class="row">
      <!-- left column -->
      <div class="col">
          <!-- general form elements -->
          <div class="box box-primary">
              <!-- /.box-header -->
              <!-- form start -->
              @if (session()->has('message'))
                  <div class="alert alert-success">
                      {{ session()->get('message') }}
                  </div>
              @endif
              @if (session()->has('error_message'))
                  <div class="alert alert-danger alert-dismissible">
                      {{ session()->get('error_message') }}
                  </div>
              @endif
              @if (count($errors) > 0)
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              @php
              $inRefno = 'sa'.rand(1000, 100000);
          @endphp


              <p class="error-content alert-danger"></p>
              <form method="post" action="{{ route('staff.oppertunity_storesales') }}" id="contactform" enctype="multipart/form-data">
                  @csrf
                  <div class="box-body">
                   
                    <div class="row">


                        <div class="form-group col-lg-2 col-md-3 col-sm-4">
                            <label for="name">Internal Ref No*</label>
                            <input type="text" id="internal_ref_no" name="internal_ref_no" value="{{ $salescontract->in_ref_no }}" class="form-control" placeholder="" readonly>
                            <span class="error_message" id="name_message" style="display: none">Field is required</span>
                        </div>



                      <div class="form-group col-md-2" style="display:none">
                          <label>State*</label>
                          <select name="state_id" id="state_id" class="form-control select2" data-live-search="true"
                              onchange="change_state()" aria-placeholder="Select State" placeholder="Select State">
                              <option value="">Select State</option>
                              @foreach ($state as $item)
                                  <option value="{{ $item->id }}"
                                      @if (optional($oppertunity)->state == $item->id) selected @endif>{{ $item->name }}</option>
                              @endforeach
                          </select>
                          <div class="alert alert-danger" id="state_id_error" style="display:none"> </div>
                      </div>

                      <div class="form-group col-md-2" style="display:none">
                          <label>District*</label>
                          <select name="district_id" id="district_id" class="form-control select2"
                              data-live-search="true" onchange="change_district()">
                              <option value="">Select District</option>
                              @foreach ($district as $item)
                                  <option value="{{ $item->id }}"
                                      @if (optional($oppertunity)->district == $item->id) selected @endif>{{ $item->name }}</option>
                              @endforeach
                          </select>
                          <div class="alert alert-danger" id="district_id_error" style="display:none"> </div>
                      </div>

                    <div class="form-group col-md-2">

                        <label>Customer Name</label>

                        <input type="text" class="form-control" name="user_name" id="user_name" value="{{optional($oppertunity->customer)->business_name}}" readonly>
                        
                        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{optional($oppertunity)->user_id}}">

                        <div class="alert alert-danger" id="user_id_error" style="display:none"> </div>

                    </div>
                    
                      <div class="form-group col-md-2">
                        <label >Contact Person *</label>
                        <select class="form-control" name="contact_person_id" id="contact_person_id">
                             <option value=""> Select Contact Person </option>
                             @foreach($contactPersons as $item)
                                <option value="{{ $item->id }}"
                                    @if (optional($oppertunity)->coordinator_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="error_message" id="contact_person_id_message" style="display:none"> </span>
                      </div>

                      <input type="hidden" name="oppertunity_id" id="oppertunity_id" value="{{$oppertunity->id}}"> 

                      <input type="hidden" name="sales_contract_id" id="sales_contract_id" value="{{$salescontract->id}}"> 

                      
                      <div class="form-group col-md-2">

                        <label>Third Party Contract Reference</label>

                        <input type="text" class="form-control" name="contract_reference" id="contract_reference" value="{{old('contract_reference')}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Reference</label>

                        <input type="text" class="form-control" name="payment_reference" id="payment_reference" value="{{old('payment_reference')}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Amount</label>

                        <input type="text" class="form-control" name="payment_amount" id="payment_amount" value="{{old('payment_amount')}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>TDS</label>

                        <input type="text" class="form-control" name="tds" id="tds" value="{{old('tds')}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Attachment</label>

                        <input type="file" class="form-control" name="attachment" id="attachment" value="{{old('attachment')}}">
                        
                    </div>

                    <?php /*
                    <div class="form-group col-md-2">

                        <label>Start Date*</label>

                        <input type="text" id="contract_start_date" name="contract_start_date" value="" class="form-control  contract_pm_date start_date" placeholder="Start Date" readonly>
                            
                         <span class="error_message" id="contract_start_date_error" style="display:none"> </span>

                    </div>
                        
                    <div class="form-group col-md-2">

                        <label>End Date*</label>

                        <input type="text" id="contract_end_date" name="contract_end_date" value="" class="form-control  contract_pm_date" onchange="CheckEndDate(this)"  placeholder="End Date" readonly>
                            
                        <span class="error_message" id="contract_end_date_error" style="display:none"> </span>
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Customer Order no</label>

                        <input type="text" class="form-control" name="customer_order" id="customer_order" value="{{old('customer_order')}}">
                        
                    </div>


                    <div class="form-group col-md-2">

                        <label>Contact type*</label>

                        <select name="contract_type" id="contract_type" class="form-control">
                            <option value="">-- Select Type --</option>
                            <option value="AMC" {{ old('contract_type') == 'AMC' ? 'selected' : '' }}>AMC</option>
                            <option value="CMC" {{ old('contract_type') == 'CMC' ? 'selected' : '' }}>CMC</option>
                            <option value="Warranty" {{ old('contract_type') == 'Warranty' ? 'selected' : '' }} disabled>
                                Warranty</option>
                            <option value="HBS" {{ old('contract_type') == 'HBS' ? 'selected' : '' }} disabled>HBS
                            </option>
                        </select>
                        <span class="error_message" id="contract_type_common_message" style="display: none">Field is
                            required</span>
                        
                    </div>
                       */?>



                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Order No</label>
                        <input type="text" id="order_no" name="order_no" value="{{optional($orders)->order_no}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Order Date</label>
                        <input type="text" id="order_date" name="order_date" value="{{optional($orders)->order_date}}" class="form-control datepicker" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Order Received Date</label>
                        <input type="text" id="order_recive_date" name="order_recive_date" value="{{optional($orders)->order_recive_date}}" class="form-control datepicker" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Payment Terms</label>
                        <input type="text" id="payment_term" name="payment_term" value="{{optional($orders)->payment_term}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>



                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Delivery Date</label>
                        <input type="text" id="delivery_date" name="delivery_date" value="{{optional($orders)->delivery_date ? \Carbon\Carbon::parse(optional($orders)->delivery_date)->format('d-m-Y') : ''}}" class="form-control datepicker" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Warrenty Terms</label>
                        <input type="text" id="warrenty_terms" name="warrenty_terms" value="{{optional($orders)->warrenty_terms}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Supply</label>
                        <input type="text" id="supplay" name="supplay" value="{{optional($orders)->supplay}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">
                            Remark</label>
                        <input type="text" id="remark" name="remark" value="{{optional($orders)->remark}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>



                </div>


                    <table class="table table-bordered opper-table" border="0" style="border-collapse: collapse;">

                        <tr style="background-color:#999; color:#fff;" id="table_head">
                            
                            <th >Sl. No.</th>

                            <th>Equi. Name</th>

                            <th >Category</th>

                            <th>Modal No.</th>

                            <th>Rate</th>

                            <th>MSP</th>
                            
                            <th id="pm_no_track">Margin</th>

                            <th >Amount</th>

                            <th>Incentive</th>

                            <th>Qty</th>

                            <th >Tax %</th>

                            <th >Tax</th>
                            <th >Total (Rs.)</th>
        
                          
                        </tr>

                        @php

                            $grand_contact_amount_with_qty = 0;

                            $grand_contact_amount_with_qtyop = 0;
                            
                        @endphp

                        @foreach($productDetails as $key=>$value)

                        @php 
                                $service_tax_percentage =  $value->product_tax_percentage??12; 

                                $contact_amount_with_tax =  $value->product_sale_amount + $value->product_sale_amount * ($service_tax_percentage / 100);


                                $contact_amount_with_qty = $contact_amount_with_tax * ($value->product_quantity ??1);

                                $grand_contact_amount_with_qty += $contact_amount_with_qty;

                                $contact_tax = $value->product_sale_amount * ($service_tax_percentage / 100);

                                /************************************* new requirement  ******************************************************/
                                
                                $product_amount = $value->product_sale_amount * ($value->product_quantity ??1);

                                $product_amount_with_tax = $product_amount * ($service_tax_percentage / 100) ;

                                $msp_value = (optional($value->quoteProduct->productmsp()->latest()->first())->pro_msp) * ($value->product_quantity ??1);

                            @endphp


                        <tr>
                           
                            <td >{{ ++$key }}</td>
                            <td ><input type="text" name="equpment_type[]" id="equpment_type{{$key}}"  class="form-control " value="{{ old('equpment_type.'.$key, optional($value->quoteProduct)->name) }}" readonly></td>

                            <td >{{optional($value->quoteProduct)->category_name}}</td>

                            <td ><input type="text" name="equipment_model_no[]" id="equipment_model_no_{{$key}}"  value="{{ old('equipment_model_no.'.$key,optional($value->quoteProduct)->part_no)}}" readonly class="form-control " placeholder=" Modal No."></td>

                            <td ><input type="text" name="rate[]" id="rate_{{$key}}"  value="{{ old('rate.'.$key,optional($value)->product_sale_amount)}}" readonly class="form-control " placeholder=" Modal No."></td>


                            <td ><input type="text" name="msp_no[]" id="msp_no{{$key}}"  value="{{ old('msp_no.'.$key,$msp_value)}}" class="form-control " placeholder="Msp No" readonly></td>

                        
                            @php
                                $margin_amount =  $product_amount - $msp_value??0;

                            @endphp
                            
                            <td id="before_pm{{$key-1}}">
                                <input type="text" name="margin_amount[]" value="{{ old('margin_amount.'.$key,$margin_amount)}}" class="form-control revenue_cal" placeholder=" Margin Amount" readonly>
                                    <div class="alert alert-danger" id="revanue_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td >
                                <input type="text" name="amount[]" value="{{ $product_amount }}" class="form-control amount" placeholder=" amount" readonly>
                                    <div class="alert alert-danger" id="amount_error_{{$key}}" style="display:none"> </div>
                            </td>

                            @php

                                    $unit_price = optional($value->quoteProduct->productmsp()->latest()->first())->cost??0;
                                    $trans_cost = optional($value->quoteProduct->productmsp()->latest()->first())->trans_cost??10;
                                    $customs_cost = optional($value->quoteProduct->productmsp()->latest()->first())->customs_cost??10;
                                    $other_cost = optional($value->quoteProduct->productmsp()->latest()->first())->other_cost??1;
                                    $profit = optional($value->quoteProduct->productmsp()->latest()->first())->profit??15;
                                    $quote = optional($value->quoteProduct->productmsp()->latest()->first())->quote??20;
                                    $online = optional($value->quoteProduct->productmsp()->latest()->first())->percent_online??15;
                                    $discount = optional($value->quoteProduct->productmsp()->latest()->first())->discount??1;
                                    $incentive = optional($value->quoteProduct->productmsp()->latest()->first())->incentive??0;

                                    $total_per = $trans_cost + $customs_cost + $other_cost;

                                    $total_peramount = ($unit_price * $total_per) / 100;

                                    $tot_price = $unit_price + $total_peramount;
                                    $tot_price = round($tot_price, 2);

                                    $propse_val = ($tot_price * $profit) / 100;
                                    $propse_val = round($propse_val, 2);

                                    $propse_val = $tot_price + $propse_val;

                                    $discount_tot = optional($value->quoteProduct->productmsp()->latest()->first())->discount_price??0;

                                    $online_tot = optional($value->quoteProduct->productmsp()->latest()->first())->online_price??0;

                                    $prop_total = $propse_val - $tot_price;
                                    $incentive_amount = ($incentive * $prop_total) / 10;

                                    $incentive_amount = round($incentive_amount, 2) * ($value->product_quantity ??1);

                            @endphp

                            <td >
                                <input type="text" name="incentive[]" id="incentive{{$key}}"  value="{{ old('incentive.'.$key,$incentive_amount)}}" class="form-control" placeholder="Mps No." readonly>
    
                            </td>

                            <td>

                                <input type="text" name="equipment_qty[]" id="equipment_qty{{$key}}"  class="form-control " value="{{old('equipment_qty.'.$key,$value->product_quantity)}}" readonly>

                            </td>

                            <td >
                                <input type="text" name="taxper[]" id="taxper{{$key}}"  value="{{ old('taxper.'.$key,$value->product_tax_percentage)}}" class="form-control" placeholder="tax per." readonly>
    
                            </td>

                            <td>
                                <input type="text" name="taxdis[]" value="{{ number_format($product_amount_with_tax, 2) }}" class="form-control tax" placeholder=" tax" readonly>

                                <input type="hidden" name="tax[]" value="{{ $product_amount_with_tax }}" class="form-control tax" placeholder=" tax" readonly>

                                    <div class="alert alert-danger" id="tax_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td>
                                <input type="text" name="totaldis[]" value="{{ number_format($contact_amount_with_qty, 2) }}" class="form-control total" placeholder=" total" readonly>

                                <input type="hidden" name="total[]" value="{{ $contact_amount_with_qty }}" class="form-control total" placeholder=" total" readonly>

                                    <div class="alert alert-danger" id="total_error_{{$key}}" style="display:none"> </div>
                            </td>

                            

                        </tr>
                        
                            <input type="hidden" name="equipment_id[]" id="equipment_id_{{$key}}"  class="form-control " value="{{old('equipment_id.'.$key,optional($value->quoteProduct)->id)}}" readonly>
                       
                            <input type="hidden" name="opp_product_id[]" id="opp_product_id_{{$key}}"  class="form-control " value="{{old('opp_product_id.'.$key,$value->id)}}" readonly>

                            

                        {{-- <-----------------------------------------------start o f optional products ----------------------------------------------------------------> --}}


                        @php
                           
                            $addonproducts = App\Models\QuoteProduct::where('main_product_id',$value->id)->where('addon_ptd',1)->get();
                                     
                        @endphp

                        @if(!empty($addonproducts) && count($addonproducts) >0 )

                        @foreach($addonproducts as $index=> $optional)

                        @php 
                              
                              $service_tax_percentageop =  $optional->product_tax_percentage??12; 

                                $contact_amount_with_taxop =  $optional->product_sale_amount + $optional->product_sale_amount * ($service_tax_percentageop / 100);


                                $contact_amount_with_qtyop = $contact_amount_with_taxop * ($optional->product_quantity ??1);

                                $grand_contact_amount_with_qtyop += $contact_amount_with_qtyop;

                                $contact_taxop = $optional->product_sale_amount * ($service_tax_percentageop / 100);

                        /************************************ new required **********************************************************/

                                $product_amount_op = $optional->product_sale_amount * ($optional->product_quantity ??1);

                                $product_amount_op_with_tax = $product_amount_op * ($service_tax_percentage / 100) ;

                                $msp_value_op_no = (optional($optional->quoteProduct->productmsp()->latest()->first())->pro_msp) * ($optional->product_quantity ??1);

                        @endphp


                        <tr>
                           
                            <td >{{ chr(96 + ++$index) }}</td>
                            <td ><input type="text" name="equpment_typeop[]" id="equpment_typeop{{$key}}"  class="form-control " value="{{ old('equpment_typeop.'.$key, optional($optional->quoteProduct)->name) }}" readonly></td>

                            <td >{{optional($value->quoteProduct)->category_name}}</td>

                            <td ><input type="text" name="equipment_model_noop[]" id="equipment_model_no_op{{$key}}"  value="{{ old('equipment_model_noop.'.$key,optional($optional->quoteProduct)->part_no)}}" readonly class="form-control" placeholder=" Modal No."></td>

                            <td>

                                <input type="text" name="rate_op[]" id="rate_op_{{$key}}"  class="form-control " value="{{old('rate_op.'.$key,$optional->product_sale_amount)}}" readonly>

                            </td>

                            <td ><input type="text" name="msp_noop[]" id="msp_noop{{$key}}"  value="{{ old('msp_noop.'.$key,$msp_value_op_no)}}" class="form-control" placeholder="Msp No." readonly></td>

                        
                            @php
                                $margin_amount = $product_amount_op - $msp_value_op_no??0;

                            @endphp
                            
                            <td id="before_pm{{$key-1}}">
                                <input type="text" name="margin_amountop[]" value="{{ old('margin_amountop.'.$key,$margin_amount)}}" class="form-control revenue_cal" placeholder=" Margin Amount" readonly>
                                    <div class="alert alert-danger" id="revanue_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td >
                                <input type="text" name="amountop[]" value="{{ $product_amount_op }}" class="form-control amount" placeholder=" amount" readonly>
                                    <div class="alert alert-danger" id="amount_error_{{$key}}" style="display:none"> </div>
                            </td>

                            @php

                                    $unit_price = optional($optional->quoteProduct->productmsp()->latest()->first())->cost??0;
                                    $trans_cost = optional($optional->quoteProduct->productmsp()->latest()->first())->trans_cost??10;
                                    $customs_cost = optional($optional->quoteProduct->productmsp()->latest()->first())->customs_cost??10;
                                    $other_cost = optional($optional->quoteProduct->productmsp()->latest()->first())->other_cost??1;
                                    $profit = optional($optional->quoteProduct->productmsp()->latest()->first())->profit??15;
                                    $quote = optional($optional->quoteProduct->productmsp()->latest()->first())->quote??20;
                                    $online = optional($optional->quoteProduct->productmsp()->latest()->first())->percent_online??15;
                                    $discount = optional($optional->quoteProduct->productmsp()->latest()->first())->discount??1;
                                    $incentive = optional($optional->quoteProduct->productmsp()->latest()->first())->incentive??0;

                                    $total_per = $trans_cost + $customs_cost + $other_cost;

                                    $total_peramount = ($unit_price * $total_per) / 100;

                                    $tot_price = $unit_price + $total_peramount;
                                    $tot_price = round($tot_price, 2);

                                    $propse_val = ($tot_price * $profit) / 100;
                                    $propse_val = round($propse_val, 2);

                                    $propse_val = $tot_price + $propse_val;

                                    $discount_tot = optional($optional->quoteProduct->productmsp()->latest()->first())->discount_price??0;

                                    $online_tot = optional($optional->quoteProduct->productmsp()->latest()->first())->online_price??0;

                                    $prop_total = $propse_val - $tot_price;
                                    $incentive_amount = ($incentive * $prop_total) / 10;

                                    $incentive_amount = round($incentive_amount, 2) * ($optional->product_quantity ??1);

                            @endphp

                          
                            <td >
                                <input type="text" name="incentiveop[]" id="incentiveop{{$key}}"  value="{{ old('incentiveop.'.$key,$incentive_amount)}}" class="form-control" placeholder="Mps No." readonly>
    
                                
                            </td>

                            <td>

                                <input type="text" name="equipment_qtyop[]" id="equipment_qtyop{{$key}}"  class="form-control " value="{{old('equipment_qty.'.$key,$optional->product_quantity)}}" readonly>

                            </td>

                           
                            <td >
                                <input type="text" name="taxperop[]" id="taxperop{{$key}}"  value="{{ old('taxperop.'.$key,$optional->product_tax_percentage)}}" class="form-control" placeholder="Mps No." readonly>
    
                            </td>

                            <td>
                                <input type="text" name="taxopdis[]" value="{{ number_format($product_amount_op_with_tax, 2) }}" class="form-control tax" placeholder=" tax" readonly>

                                <input type="hidden" name="taxop[]" value="{{ $product_amount_op_with_tax }}" class="form-control tax" placeholder=" tax" readonly>

                                    <div class="alert alert-danger" id="tax_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td>
                                <input type="text" name="totalopdis[]" value="{{ number_format($contact_amount_with_qtyop, 2) }}" class="form-control total" placeholder=" total" readonly>

                                <input type="hidden" name="totalop[]" value="{{ $contact_amount_with_qtyop }}" class="form-control total" placeholder=" total" readonly>

                                    <div class="alert alert-danger" id="total_error_{{$key}}" style="display:none"> </div>
                            </td>

                            

                        </tr>
                        
                            <input type="hidden" name="equipment_idop[]" id="equipment_id_op{{$key}}"  class="form-control " value="{{old('equipment_idop.'.$key,optional($optional->quoteProduct)->id)}}" readonly>
                       
                            <input type="hidden" name="opp_product_idop[]" id="opp_product_idop{{$key}}"  class="form-control " value="{{old('opp_product_idop.'.$key,$value->id)}}" readonly>

                           
                        @endforeach

                         @endif


{{-- <-----------------------------------------------end o f optional products ----------------------------------------------------------------> --}}


                        @endforeach

                        <tr>

                            <td  colspan="9" id="spancolmn">&nbsp;</td>

                            <td><strong>&nbsp;</strong></td>
            
                            <td >&nbsp;&nbsp;</td>
             
                            <td><strong>&nbsp;TOTAL </strong></td>

        
                            <td ><strong id="grand-total1"> {{ number_format($grand_contact_amount_with_qty + $grand_contact_amount_with_qtyop, 2) }}</strong>&nbsp;&nbsp;</td>
                          
                        </tr>

                     </table>

                     @foreach($productDetails as $key=>$value)

                        <div class="
                            equipment-details" id="equipment-details-group"  @if(empty(old('equipment_id',optional($value->quoteProduct)->equipment_id))) style="display: none" @endif >

                        <div class="row">
                                            
                        </div>

                    </div>
                        @endforeach


                   


                  </div>
    

                  <div class="box-footer">

                        @if($salescontract->contract_status ==='verified')

                            <button type="button" disabled id="submit_contract" disabled  class="btn btn-success">Verified</button>
                        
                        @elseif($salescontract->contract_status =='saved')

                            

                            <button type="button" disabled id="" disabled  class="btn btn-danger">Not Verified</button>

                            <?php /*

                            <button type="button" onclick="verifyContract('{{ $salescontract->id }}')" id="verify_button" class="btn btn-success">Verify</button> */?>

                        @endif

                        @if($salescontract->contract_status !='saved')

                        <button type="submit" id="submit_contract" class="btn btn-primary">Save</button>

                        @endif
                        

                        <button type="button" class="btn btn-danger" id="reject_sales" data-id="{{ $salescontract->id }}">Reject</button>

                        <button type="button" class="btn btn-danger" onclick="history.back()">Cancel</button>
                    </div>
              </form>
          </div>

      </div>
  </div>
</section>

<div class="modal fade inprogress-popup" id="BillModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Billing Details</h4>
            </div>

            <div class="modal-body" id="bill-items">

                <form method="post" action="" class="bill-from" id="bill_form">
                    <table class="table table-bordered" border="0" style="border-collapse: collapse;">
                        <tbody>

                            <tr id="bill_head" >
                                <th>Item No</th>
                                <th>Name</th>
                                <th>Order Qty</th>
                                <th>Bill Qty</th>
                            </tr>

                        </tbody>

                    </table>

                        <label for="bill_attach" id="attachlabel">Attach bill</label> 

                        <input type="file" class="bill-attach" name="bill_attach" id="bill_attach">

                        <button type="button" class="btn btn-primary" id="bill_submit" >Submit</button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
        var iblist=@json($iblist);
      
        function CheckEndDate(element) {

            console.log('index');

            // const startDateId = `#contract_start_date`;
            const endDateId = `#contract_end_date`;
            const startDate = $(startDateId).val();
            const endDate = $(endDateId).val();

            const start = new Date(startDate);
            const end = new Date(endDate);
            if (start >= end) {
                $(`#contract_end_date_error`).text('Contract End Date must be greater than Contract Start Date.').show();
                $(endDateId).val("");
            } else {
                $(`#contract_end_date_error`).hide();
                $(`.no_of_pm_field`).prop('readonly', false);
            }
        };

        // $('.end_date').datepicker({
        //     dateFormat: 'yy-mm-dd',
        //     // minDate: 0,
        // }); 

        $('.datepicker').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'dd-mm-yy',
            maxDate: 0 ,
            changeYear: true,
            changeMonth:true,
        });

        $('#supplay').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
        });

        $('.start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth:true,

            onSelect: function(selectedDate) {

                $(`#contract_end_date_error`).hide();
                
                $(`.no_of_pm_field`).prop('readonly', true);

                $('.no_of_pm_field').each(function() {
                    $(this).val('');
                });

                var endDateId = `#contract_end_date`;

                $(endDateId).val(""); 

                // $(endDateId).addClass("end_date"); 
                
                $(endDateId).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeYear: true,
                    changeMonth:true,
                    onSelect: function() {

                        $('.no_of_pm_field').each(function() {
                            $(this).val('');
                        });
                        console.log('End date selected:', this.value);
                        CheckEndDate(this);
                    }
                });

                $(endDateId).datepicker('option', 'minDate', selectedDate);
               
            }
        });
        
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.js"></script>


    <script type="text/javascript">

        function verifyContract(verify_id)
        {
            console.log(verify_id);

            var type = '{{ $opp_type }}';

            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL + '/staff/verify_sales',
                data: {
                    verify_id: verify_id,
                },
                success: function(res) {
                    console.log(res);
                      window.location.href = APP_URL + '/staff/sales?company_type=' + encodeURIComponent(type);
                }

            });

        }


       $(document).ready(function() {

        $('#submit_contract').prop('disabled',false);
        
            $('#contactform').submit(function(event) {

                var isValid = true;
                // var contract_start_date = $('#contract_start_date').val();

                // var contract_end_date = $('#contract_end_date').val();

                var contact_person_id = $('#contact_person_id').val();

                // var contract_type = $('#contract_type').val();

                
                // if (!contract_start_date) {
                //     isValid = false;
                //     $('#contract_start_date_error').text('The field is required').show();
                //     console.log('contract_start_date_error');
                // }
                // else
                // {
                //     $('#contract_start_date_error').hide();
                // }

                // if (!contract_end_date) {
                //     isValid = false;
                //     $('#contract_end_date_error').text('The field is required').show();
                //     console.log('contract_end_date_error');
                // }
                // else
                // {
                //     $('#contract_end_date_error').hide();
                // }
                
                // if (!contract_type) {
                //     isValid = false;
                //     $('#contract_type_common_message').text('The field is required').show();
                //     console.log('contract_type_common_message');
                // }
                // else
                // {
                //     $('#contract_type_common_message').hide();
                // }


                if (!contact_person_id) {
                    isValid = false;
                    $('#contact_person_id_message').text('The field is required').show();
                    console.log('contact_person_id_message');
                }
                else
                {
                    $('#contact_person_id_message').hide();
                }



                //  $('[name^="machine_status_id[]"]').each(function(k, v) {
                //      k++;
                //     if (!$(this).val()) {
                //         isValid = false;
                //         $(`#machine_status_id_error_${k}`).text('The field is required').show();
                //         console.log(`machine_status_id_error_${k}`);
                //     } else {
                //         $(`#machine_status_id_error_${k}`).hide();
                //     }
                // });


                // $('[name^="no_of_pm[]"]').each(function(k,v) {
                //      k++;
                //     if (!$(this).val()) {
                //         isValid = false;
                //         $(`#no_of_pm_error_${k}`).text('The field is required').show();
                //         console.log(`no_of_pm_error_${k}`);
                      
                //     }
                // });

                if (!isValid) {
                    event.preventDefault();
                } 
            });
        });


        function calculateTax(istax,k=""){
            var am = parseFloat($(`#camount${k}`).val()||0);
            var tx = $(`#ctax${k}`).val();
            console.log(tx=="",tx)
            if(istax){
                tx=am*0.18;
                $(`#ctax${k}`).val(tx);
            }
            $(`#ctotal${k}`).val(am+parseFloat(tx))
        }
        $(document).on('keyup change',".ctax",function() {
            if(this.value){
                $(this).val(this.value)
            }
        })
     

        $(document).on('change', '.contract_pm_date', function() {
            var k = $(this).data('k') || "";
            $('.no_of_pm_field').empty();
        })

        $(document).on('keyup', '.revenue_cal', function() {

            var total = 0;

            $('[name^="revanue"]').each(function() {

                var value = parseFloat($(this).val()) || 0;
        
                total += value; 
            });

            $('#revenu_amount').text(total.toFixed(2));
        });

        $(document).on('keyup', '.no_of_pm', function() {

            var k = $(this).data('k') || "";
        
            // var no = parseInt($('#no_of_pm' + k).val());

            var high_no =0;

           $('[name^="no_of_pm"]').each(function() {

                var value = $(this).val();

                var value = parseFloat($(this).val());
    
                if (!isNaN(value) && value > high_no) {
                    high_no = value; 
                }

            });

            console.log(high_no);

            $('#spancolmn').attr('colspan',8);

            $('.pm_dates_head').remove();

            $('.valuetable').remove();

            $('[name^="no_of_pm"]').each(function() {

                var no = parseInt($(this).val());

                var ki = $(this).data('vk') || "0";

                var startDate = new Date($("#contract_start_date").val());
                var endDate = new Date($("#contract_end_date").val());

                var selectstartDate = $("#contract_start_date").val();
                var selectendDate = $("#contract_end_date").val();

                if (selectstartDate !== "" && selectendDate !== "")
                {
                    var totalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                    var intervalDays = Math.ceil(totalDays / (no + 1));

                    console.log(intervalDays,'intervels');

                    var currentDate = new Date(startDate);

                    for (var i = 0; i < high_no; i++) {
                        var pm = i + 1;
                        currentDate.setDate(currentDate.getDate() + intervalDays);
                        var formattedDate = formatDate(currentDate);
                        if(i<no && no!=0)
                        {
                            $('#before_pm'+ki).before('<td class="valuetable"  id="valuetable' + ki + i + '" ><input type="text" class="form-control" name="pm_dates[' + ki + '][]" value="' + formattedDate + '" readonly/></td>');
                        }
                        else
                        {
                            $('#before_pm'+ki).before('<td class="valuetable"  id="valuetable' + ki + i + '" ></td>');
                        }
                    
                    }
                }

            });

            for (var i = 0; i < high_no; i++) {
                var pm = i + 1;
                $('#table_head').find('#pm_no_track').before(`<th class='pm_dates_head'>PM ${pm}</th>`);
                
            }

            var colval = $('#spancolmn').attr('colspan');

            colval = parseInt(colval, 10) || 0; 

            colval = colval + high_no;

            console.log(colval);

            $('#spancolmn').attr('colspan',colval);

        });

        function formatDate(date) {
            var month = '' + (date.getMonth() + 1);
            var day = '' + date.getDate();
            var year = date.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

    <script>
        $('.select2').select2();
    </script>
    <script>
        $('.multiselect').multiselect();
    </script>

    <script>


        function toggleContractType(val) {
            $('#equipment-details-group').html('');
            if (val=="new_product") {
                $('.opportunity-fields').hide();
                $('#equpment_id').prop('multiple',false).val('').attr('name','equipment_id').multiselect('rebuild');
            } else {
                $('.opportunity-fields').show();
                $('#equpment_id').prop('multiple',true).val([]).attr('name','equipment_id[]').multiselect('rebuild');
            }
        }
      </script>
     <script>
        function change_state() {
            var state_id = $("#state_id").val();
            $("#district_id").html('<option value="">Select District</option>');
            $('#district_id').select2();
            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL + '/staff/change_state',
                data: {
                    state_id: state_id,
                },
                success: function(data) {
                    var proObj = JSON.parse(data);
                    states_val = '';
                    states_val += '<option value="">Select District</option>';
                    for (var i = 0; i < proObj.length; i++) {
                        states_val += '<option value="' + proObj[i]["id"] + '">' + proObj[i]["name"] +
                            '</option>';
                    }
                    $("#district_id").html(states_val).select2();
                    change_district()
                }
            });
        }

        function change_district() {
            var state_id = $("#state_id").val();
            var district_id = $("#district_id").val();
            $("#user_id").val('<option value="">Select Customer</option>');
            $("#equpment_id").val('<option value="">Select Equpment</option>');
            $('#user_id').select2();
            $('#equpment_id').multiselect('rebuild');
            // $('.equipment-details').hide()
            // $('#equipment-details-group').html('');
            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL + '/staff/contract-customer',
                data: {
                    state_id: state_id,
                    district_id: district_id
                },
                success: function(data) {
                    var proObj = JSON.parse(data);
                    states_val = '';
                    states_val += '<option value="">Select Customer</option>';
                    for (var i = 0; i < proObj.length; i++) {
                        states_val += '<option value="' + proObj[i]["id"] + '">' + proObj[i]["business_name"] +
                            '</option>';
                    }
                    $("#user_id").html(states_val).select2();
                }
            });
        }

        function change_customer(){

            var user_id = $("#user_id").val();
            $("#equpment_id").val('<option value="">Select Equpment</option>').multiselect('rebuild');
            // $('#equpment_id').select2();
            // $('.equipment-details').hide()
            // $('#equipment-details-group').html('');
            // $.ajax({
            //     type: "POST",
            //     cache: false,
            //     url: APP_URL + '/admin/customer-equpment',
            //     data: {
            //         user_id: user_id,
            //     },
            //     success: function(data) {
            //         var proObj = JSON.parse(data);
            //         states_val = '';
            //         states_val += '<option value="">Select District</option>';
            //         for (var i = 0; i < proObj.length; i++) {
            //             states_val += '<option value="' + proObj[i]["id"] + '">' + proObj[i]["name"] +
            //                 '</option>';
            //         }
            //         $("#equpment_id").html(states_val).select2();
            //     }
            // });

            $("#contact_person_id").val('<option value="">Select Contact Person</option>');
            $('#contact_person_id').select2();
            var html_contact=""
            var html_equipment=""
            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL+'/staff/customer_contact_person',
                data:{
                    user_id : user_id
                },
                success: function (data)
                {
                    html_contact+= "<option value=''> Select Contact Person </option>"
                    $.each(data['contactPersons'],function (){
                        html_contact+="<option value='"+this.id+"'>"+this.name+"</option>";
                    });
                    $("#contact_person_id").html(html_contact);

                    // html_equipment+= "<option value=''> Select Equipment Name </option>"
                    // $.each(data['equipments'],function (){
                    //     html_equipment+="<option value='"+this.id+"'>"+this.name+"</option>";
                    // });
                    // $("#equpment_id").html(html_equipment).multiselect('rebuild');
                }
            });
        }
        function change_equipment(){
            var user_id = $("#user_id").val();
            var equpment_id = $("#equpment_id").val();
            var opportunityId = $('input[name="contract_mode"]:checked').val()=="new_product" ? null : $('#oppertunity_id').val();
            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL + '/staff/customer-equpment-details',
                data: {
                    user_id: user_id,
                    equpment_id: equpment_id,
                    oppertunity_id: opportunityId
                },
                success: function(data) {
                    iblist = JSON.parse(data);
                    // $('#equipment-details-group').html('');
                    var i=0;
                    $.each(iblist,function(k,ibs){

                        if($('input[name="contract_mode"]:checked').val()=="new_product" /* || (opportunityId||"")=="" */){
                            var equipment_serial_no='';
                            $.each(ibs,function(i,v){
                                equipment_serial_no+=`<option value="${ v.equipment_serial_no }" data-id="${v.id}" >${ v.equipment_serial_no }</option>`
                            })
                            var equipmentHtml=`
                                <div class="row">
                                    <input type="hidden" name="ib_id" id="ib_id"  class="form-control " value="" readonly>
                                    <div class="form-group col-md-4"  >
                                        <label>Serial Number*</label>
                                        <select name="equipment_serial_no" id="equipment_serial_no" class="form-control select2" data-live-search="true"  onchange="change_serialnumber()">
                                            <option value=""> Select Serial Number</option>
                                            ${equipment_serial_no}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Modal Number*</label>
                                        <input type="text" name="equipment_model_no" id="equipment_model_no"  class="form-control " value="" readonly>
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Under Contract/Warranty*</label>
                                        <input type="text" name="under_contract" id="equipment_status"  class="form-control " value="" readonly>
                                        <input type="hidden" name="equipment_status_id" id="equipment_status_id" value="equipment_status_id" value="">
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Supply Order Number*</label>
                                        <input type="text" name="supplay_order" id="supplay_order"  class="form-control " value="" readonly>
                                    </div>

                                    <div class="form-group col-md-4" >
                                        <label>Installation Date*</label>
                                        <input type="text" name="installation_date" id="installation_date"  class="form-control " value="" readonly>
                                    </div>

                                    <div class="form-group col-md-4" >
                                        <label>Warranty Expiry Date*</label>
                                        <input type="text" name="warraty_expiry_date" id="warrenty_end_date"  class="form-control " value="" readonly>
                                    </div>


                                </div>
                            `;
                            equipmentHtml+=`<div class="row">
                        <div class="form-group col-md-4">
                            <label>Contract Type*</label>
                            <input type="text" name="contract_type" value=""
                                class="form-control" placeholder="Contract Type">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Amount*</label>
                            <input type="text" name="amount" id="camount" onchange="calculateTax(true)" value="" class="form-control"
                                placeholder="Amount">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tax Amount@ 18%</label>
                            <input type="text" name="tax" id="ctax" onchange="calculateTax(false)" value="" class="form-control ctax"
                                placeholder="Tax">
                        </div>


                        <div class="form-group col-md-4">
                            <label>Amount + Tax*</label>
                            <input type="text" name="amount_tax" id="ctotal" value=""
                                class="form-control" placeholder="Amount + Tax">
                        </div>
                        <div class="form-group col-md-4">
                          <label >Select Machine Status*</label>
                          <select class="form-control" name="machine_status_id" id="machine_status_id">
                               <option value="">-- Select Machine Status --</option>
                               @foreach ($machine_status as $item)
                               <option value="{{ $item->id }}"  >{{ $item->name }}</option>
                               @endforeach
                          </select>
                      </div>

                        <div class="form-group col-md-4">
                            <label>Revanue*</label>
                            <input type="text" name="revanue" value="" class="form-control"
                                placeholder="Revanue">
                        </div>

                        <div class="form-group col-md-4">
                            <label>No of PMs*</label>
                            <input type="text" id="no_of_pm" name="no_of_pm" value=""
                                class="form-control no_of_pm" placeholder="No of PMs">
                        </div>

                        <div class="form-group col-md-12" id="input_field"></div>

                    </div>`;

                            if($('input[name="contract_mode"]:checked').val()!=="new_product"&&k!==equpment_id[equpment_id.length-1]){
                                equipmentHtml+='<hr class="line-break">';
                            }
                            // $('#equipment-details-group').append(equipmentHtml)
                            $("#equipment_serial_no").select2();
                        }else{
                            var oib=null;
                            if(opportunityId&&opportunityId>0){
                                oib=ibs[0];
                            }
                            var ibstatus =  @json($equipment_status);
                            ibstatus=ibstatus.filter((i)=>{
                                return i.id==oib?.equipment_status_id??0
                            })[0]
                            var equipmentHtml=`
                                <div class="row">
                                    <input type="hidden" name="contract_product_id[]" value="">
                                    <input type="hidden" name="ib_id[]" id="ib_id_${k}"  class="form-control " value="${ oib?.id??"" }" readonly>
                                    <div class="form-group col-md-4"  >
                                        <label>Serial Number*</label>
                                        <input type="text" name="equipment_serial_no[]" id="equipment_serial_no_${k}"  class="form-control " value="${ oib?.equipment_serial_no??"" }" readonly>
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Modal Number*</label>
                                        <input type="text" name="equipment_model_no[]" id="equipment_model_no_${k}"  class="form-control " value="${oib?.equipment_model_no??"" }" readonly>
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Under Contract/Warranty*</label>
                                        <input type="text" name="under_contract[]" id="equipment_status_${k}"  class="form-control " value="${ibstatus?.name||""}" readonly>
                                        <input type="hidden" name="equipment_status_id[]" id="equipment_status_id_${k}" value="equipment_status_id" value="${oib?.equipment_status_id??""}">
                                    </div>
                                    <div class="form-group col-md-4" >
                                        <label>Supply Order Number*</label>
                                        <input type="text" name="supplay_order[]" id="supplay_order_${k}"  class="form-control " value="${oib?.supplay_order??""}" readonly>
                                    </div>

                                    <div class="form-group col-md-4" >
                                        <label>Installation Date*</label>
                                        <input type="text" name="installation_date[]" id="installation_date_${k}"  class="form-control " value="${oib?.installation_date??""}" readonly>
                                    </div>

                                    <div class="form-group col-md-4" >
                                        <label>Warranty Expiry Date*</label>
                                        <input type="text" name="warraty_expiry_date[]" id="warrenty_end_date_${k}"  class="form-control " value="${oib?.warrenty_end_date??""}" readonly>
                                    </div>

                                </div>
                            `;

                            if(oib?.oppertunityProduct){
                                equipmentHtml+=`
                                <div class="row">
                                    <input type="hidden" name="tax_percentage[]" id="tax_percentage_${k}"  class="form-control " value="${oib?.oppertunityProduct.tax??"" }" readonly>
                                    <div class="form-group col-md-3"  >
                                        <label>Quantity*</label>
                                        <input type="text" name="equipment_qty[]" id="equipment_qty_${k}"  class="form-control " value="${ oib?.oppertunityProduct.quantity??"" }" readonly>
                                    </div>
                                    <div class="form-group col-md-3" >
                                        <label>Amount*</label>
                                        <input type="text" name="equipment_amount[]" id="equipment_amount_${k}"  class="form-control " value="${oib?.oppertunityProduct.single_amount??"" }" readonly>
                                    </div>
                                    <div class="form-group col-md-3" >
                                        <label>Tax*<small>(${oib?.oppertunityProduct.tax}%)</small></label>
                                        <input type="text" name="equipment_tax[]" id="equipment_tax_${k}"  class="form-control " value="${oib?.oppertunityProduct.tax_amount??"" }" readonly>
                                    </div>
                                    <div class="form-group col-md-3" >
                                        <label>Total*</label>
                                        <input type="text" name="equipment_total[]" id="equipment_total_${k}"  class="form-control " value="${oib?.oppertunityProduct.amount??"" }" readonly>
                                    </div>
                                </div>
                            `;
                            }
                            equipmentHtml+=`<div class="row">
                        <div class="form-group col-md-4">
                            <label>Contract Type*</label>
                            <input type="text" name="contract_type[]" id="contract_type_${k}"
                                class="form-control" placeholder="Contract Type"  value="">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Amount*</label>
                            <input type="text" name="amount[]" id="camount_${k}" onchange="calculateTax(true,'_${k}')" class="form-control"
                                placeholder="Amount" value="">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tax Amount @ 18%</label>
                            <input type="text" name="tax[]" id="ctax_${k}" onchange="calculateTax(false,${k})"  class="form-control ctax"
                                placeholder="Tax"  value="">
                        </div>


                        <div class="form-group col-md-4">
                            <label>Amount + Tax*</label>
                            <input type="text" name="amount_tax[]" id="ctotal_${k}"
                                class="form-control" placeholder="Amount + Tax"  value="">
                        </div>
                        <div class="form-group col-md-4">
                          <label >Select Machine Status*</label>
                          <select class="form-control" name="machine_status_id[]" id="machine_status_id_${k}">
                               <option value="">-- Select Machine Status --</option>
                               @foreach ($machine_status as $item)
                               <option value="{{ $item->id }}" >{{ $item->name }}</option>
                               @endforeach
                          </select>
                      </div>

                        <div class="form-group col-md-4">
                            <label>Revanue*</label>
                            <input type="text" name="revanue[]" name="revanue_${k}" class="form-control"
                                placeholder="Revanue"  value="">
                        </div>

                        <div class="form-group col-md-4">
                            <label>No of PMs*</label>
                            <input type="text" id="no_of_pm_${k}" name="no_of_pm[]" data-k="_${k}" data-vk="${i}"
                                class="form-control no_of_pm" placeholder="No of PMs"  value="">
                        </div>

                        <div class="form-group col-md-12" id="input_field_${k}"></div>

                    </div>`;


                            if(k!==equpment_id[equpment_id.length-1]){
                                equipmentHtml+='<hr class="line-break">';
                            }
                            // $('#equipment-details-group').append(equipmentHtml)
                        }
                        i++;
                    })
                    $('.equipment-details').show();
                }
            });
        }

        function change_serialnumber(){
            var serialnumber= $('#equipment_serial_no').val()
            var equpment_id = $("#equpment_id").val();
            if(serialnumber&&serialnumber!=""){
                var id=$('#equipment_serial_no option:selected').data('id');
                var selectedIB=iblist[equpment_id].filter((i)=>{
                    return i.id==id
                })[0]||{};
                var ibstatus =  @json($equipment_status);
                ibstatus=ibstatus.filter((i)=>{
                    return i.id==selectedIB.equipment_status_id
                })[0]
                $('#equipment_model_no').val(selectedIB.equipment_model_no)
                $('#equipment_status').val(ibstatus.name)
                $('#equipment_status_id').val(selectedIB.equipment_status_id)
                $('#installation_date').val(selectedIB.installation_date)
                $('#warrenty_end_date').val(selectedIB.warrenty_end_date)
                $('#supplay_order').val(selectedIB.supplay_order)
            }else{
                $('#equipment_model_no').val("")
                $('#equipment_status').val("")
                $('#equipment_status_id').val("")
                $('#installation_date').val('')
                $('#warrenty_end_date').val('')
                $('#supplay_order').val('supplay_order')
            }
        }

        function fetchOpportunities() {
            var userId = $('#user_id').val()
            $.ajax({
                url: APP_URL +'/staff/get-opportunities-by-user',
                method: 'POST',
                data: {
                    user_id: userId,
                   },
                success: function(response) {
                    $('#oppertunity_id').empty();
                    $('#oppertunity_id').append('<option value="">-- Select Opportunity --</option>');
                    $.each(response, function(index, opportunity) {
                        $('#oppertunity_id').append('<option value="'+ opportunity.id +'">'+ opportunity.oppertunity_name +'</option>');
                    });
                    // $('#equipment-details-group').html('');
                }
            });
        }

        function fetchProducts() {

            var opportunityId = $('input[name="contract_mode"]:checked').val()=="new_product" ? null : $('#oppertunity_id').val();
            var $userId = $("#user_id").val();
            $.ajax({
                url: APP_URL + '/staff/get-products-by-opportunity',
                method: 'POST',
                data: {
                    opportunity_id: opportunityId,
                    user_id: $userId,

                },
                success: function(response) {
                    $('#equpment_id').empty();
                    $('#equpment_id').append('<option value="">-- Select Product --</option>');
                    $.each(response, function(index, product) {
                        $('#equpment_id').append('<option value="'+ product.id +'">'+ product.name +'</option>');
                    });
                    $('#equpment_id').multiselect('rebuild')
                    $('.equipment-details').hide()
                    // $('#equipment-details-group').html('');
                }
            });
        }


        $(document).on('click', '#reject_sales', function() {
            var sale_contract = $(this).data('id');
            console.log(sale_contract);

            $.ajax({
                url: '/staff/reject-opportunity',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    sale_contract_id: sale_contract,  
                },
                success: function(response) {
                    window.location.href = "{{route('staff.sales.index')}}";
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
    });


 

    </script>



<script>

    function formatCurrency(number) {
        return Math.round(number).toLocaleString(undefined, { 
            style: 'decimal'
        });
    }

    function updateGrandTotal() {
        let grandTotal = 0;

        document.querySelectorAll('.total').forEach(input => {
            const rowTotal = parseFloat(input.value.replace(/,/g, '')) || 0; 
            grandTotal += rowTotal;
        });

        document.getElementById('grand-total').textContent = formatCurrency(grandTotal);
    }

    document.querySelectorAll('.amount').forEach(input => {
        input.addEventListener('input', function () {
            const row = this.closest('tr'); 
            let amount = parseFloat(this.value.replace(/,/g, '')) || 0; 
            const taxPercentage = 18; 
            const tax = Math.round((amount * taxPercentage) / 100); 
            const total = Math.round(amount + tax);

            row.querySelector('.tax').value = formatCurrency(tax);
            row.querySelector('.total').value = formatCurrency(total);

            updateGrandTotal();
        });
    });

    updateGrandTotal();




    
</script>

@endsection





 