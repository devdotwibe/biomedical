@extends('staff/layouts.app')

@section('title', 'Create Oppertunity Contract')

@section('content')

@php 

    $verified = false;

    if($salescontract->contract_status =='verified')
    {
        $verified = true;
    }

@endphp

<section class="content-header">
  <h1>
     Opportunity Sales Contract
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
                            <input type="text" id="internal_ref_no" name="internal_ref_no" value="{{ $inRefno }}" class="form-control" placeholder="" readonly>
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
                        <label >Contact Person</label>
                        <select class="form-control" name="contact_person_id" id="contact_person_id" readonly>
                             <option value=""> Select Contact Person </option>
                             @foreach($contactPersons as $item)
                                <option value="{{ $item->id }}"
                                    @if (optional($sales_service)->contact_person_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="error_message" id="contact_person_id_message" style="display:none"> </span>
                      </div>

                      <input type="hidden" name="oppertunity_id" id="oppertunity_id" value="{{$oppertunity->id}}"> 

                      <input type="hidden" name="sales_contract_id" id="sales_contract_id" value="{{$salescontract->id}}"> 

                      
                      <div class="form-group col-md-2">

                        <label>Third Party Contract Reference</label>

                        <input readonly type="text" class="form-control" name="contract_reference" id="contract_reference" value="{{old('contract_reference',$contract->contract_reference)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Reference</label>

                        <input readonly type="text" class="form-control" name="payment_reference" id="payment_reference" value="{{old('payment_reference',$contract->payment_reference)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Amount</label>

                        <input readonly type="text" class="form-control" name="payment_amount" id="payment_amount" value="{{old('payment_amount',$contract->payment_amount)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>TDS</label>

                        <input readonly type="text" class="form-control" name="tds" id="tds" value="{{old('tds',$contract->tds)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Attachment</label>

                        @if (!empty($contact->attachment))

                        <img src="{{asset($contact->attachment)}}" >
                            
                        @endif

                        <input readonly type="file" class="form-control" name="attachment" id="attachment" value="{{old('attachment')}}">
                        
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
                        <input readonly type="text" id="order_no" name="order_no" value="{{optional($orders)->order_no}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Order Date</label>
                        <input readonly type="text" id="order_date" name="order_date" value="{{optional($orders)->order_date}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Order Received Date</label>
                        <input readonly type="text" id="order_recive_date" name="order_recive_date" value="{{optional($orders)->order_recive_date}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Payment Terms</label>
                        <input readonly type="text" id="payment_term" name="payment_term" value="{{optional($orders)->payment_term}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>



                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Delivery Date</label>
                        <input readonly type="text" id="delivery_date" name="delivery_date" value="{{optional($orders)->delivery_date ? \Carbon\Carbon::parse(optional($orders)->delivery_date)->format('d-m-Y') : ''}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Warrenty Terms</label>
                        <input readonly type="text" id="warrenty_terms" name="warrenty_terms" value="{{optional($orders)->warrenty_terms}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Supply</label>
                        <input readonly type="text" id="supplay" name="supplay" value="{{optional($orders)->supplay}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">
                            Remark</label>
                        <input readonly type="text" id="remark" name="remark" value="{{optional($orders)->remark}}" class="form-control" placeholder="" >
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>



                </div>


                    <table class="table table-bordered opper-table" border="0" style="border-collapse: collapse;">

                        <tr style="background-color:#999; color:#fff;" id="table_head">
                            
                            @if($verified)

                                <th ></th>

                            @endif

                            <th >Sl. No.</th>

                            <th>Equi. Name</th>

                            <th >Category</th>

                            <th>Modal No.</th>

                            <th>Rate</th>

                            <th>MSP</th>
                            
                            <th id="pm_no_track">Margin</th>

                            <th >Amount</th>

                            <th>Incentive</th>

                            <th >Tax</th>

                            @if($verified)

                            <th >Total qty</th>

                            @endif

                            <th >Tax %</th>

                            <th >Total (Rs.)</th>

                            @if($verified && !empty($bill_contract) && count($bill_contract) > 0)

                            <th >Total Billed  qty</th>

                            <th >Total Billed price</th>

                            @endif
        
                          
                        </tr>

                        @php 
                        
                            $grand_contact_amount_with_qty = 0;

                            $grand_bill_amount_with_qty = 0;

                            $rate_total = 0;
                            $msp_total = 0;

                            $margin_total = 0;

                            $amount_total = 0;

                            $incentive_total = 0;

                            $tax_total = 0;

                        @endphp

                        @foreach($productDetails as $key=>$value)

                        @php 

        // <----------------------------------------------incentive calculation  ------------------------------------------------------------>

                                    $unit_price = optional($value->equipment->productmsp()->latest()->first())->cost??0;
                                    $trans_cost = optional($value->equipment->productmsp()->latest()->first())->trans_cost??10;
                                    $customs_cost = optional($value->equipment->productmsp()->latest()->first())->customs_cost??10;
                                    $other_cost = optional($value->equipment->productmsp()->latest()->first())->other_cost??1;
                                    $profit = optional($value->equipment->productmsp()->latest()->first())->profit??15;
                                    $quote = optional($value->equipment->productmsp()->latest()->first())->quote??20;
                                    $online = optional($value->equipment->productmsp()->latest()->first())->percent_online??15;
                                    $discount = optional($value->equipment->productmsp()->latest()->first())->discount??1;
                                    $incentive = optional($value->equipment->productmsp()->latest()->first())->incentive??0;

                                    $total_per = $trans_cost + $customs_cost + $other_cost;

                                    $total_peramount = ($unit_price * $total_per) / 100;

                                    $tot_price = $unit_price + $total_peramount;
                                    $tot_price = round($tot_price, 2);

                                    $propse_val = ($tot_price * $profit) / 100;
                                    $propse_val = round($propse_val, 2);

                                    $propse_val = $tot_price + $propse_val;

                                    $discount_tot = optional($value->equipment->productmsp()->latest()->first())->discount_price??0;

                                    $online_tot = optional($value->equipment->productmsp()->latest()->first())->online_price??0;

                                    $prop_total = $propse_val - $tot_price;
                                    $incentive_amount = ($incentive * $prop_total) / 10;

                                    $incentive_amount = round($incentive_amount, 2) * ($value->equipment_qty ??1);


 // <----------------------------------------------incentive calculation  ------------------------------------------------------------>

                              
                                $bill_product_qty = App\BillProduct::where('contract_product_id',$value->id)->sum('bill_qty');

                                $service_tax_percentage =  $value->tax_percentage??12; 

                                $contact_amount_with_tax =  $value->amount + $value->amount * ($service_tax_percentage / 100);

                                $bill_amount_with_tax =  $value->amount + $value->amount * ($service_tax_percentage / 100);

                                $contact_amount_with_qty = $contact_amount_with_tax * ($value->equipment_qty ??1);

                                $bill_amount_with_qty = $bill_amount_with_tax * ($bill_product_qty ??0);

                                $bill_amount_cal = $value->amount* ($bill_product_qty ??0);

                                // $grand_contact_amount_with_qty += $contact_amount_with_qty;

                                $grand_bill_amount_with_qty += $bill_amount_with_qty;

                                $contact_tax = $value->amount * ($service_tax_percentage / 100);

            /****************************new requirement code **************************************************************************/

                                $rate = $value->rate ? $value->rate : ($value->amount/($value->equipment_qty ??1) );

                                $product_amount = $rate * ($value->equipment_qty ??1);

                                $product_amount_with_tax = $product_amount * ($service_tax_percentage / 100) ;

                                $product_amount_with_tax_qty = $product_amount +  $product_amount_with_tax;

                                $grand_contact_amount_with_qty += $product_amount_with_tax_qty;

                                $msp_value = optional($value->equipment->productmsp()->latest()->first())->pro_msp * ($value->equipment_qty ??1);

                                $rate_total += $rate;

                                $msp_total +=$msp_value;

                                $margin_amount = $value->amount - ($msp_value)??0;

                                $margin_total += $margin_amount;

                                $amount_total += $product_amount;

                                $incentive_total += $incentive_amount;

                                $tax_total += $product_amount_with_tax;



                        @endphp

                        <tr>
                            @php

                                $hide_bill = false;

                                if(($value->equipment_qty - $bill_product_qty) == 0)
                                {
                                    $hide_bill = true;
                                }

                            @endphp


                            @if($verified)

                                <td> <input type="checkbox" name="billbutton" id="billcheck-{{ $key }}" value="{{ $value->id }}"  @if($hide_bill) style="display:none" disabled @endif > </td>

                            @endif

                            <td >{{ ++$key }}</td>
                            <td ><input type="text" name="equpment_type[]" id="equpment_type{{$key}}"  class="form-control " value="{{ old('equpment_type.'.$key, optional($value->equipment)->name) }}" readonly></td>

                            <td >{{optional($value->equipment)->category_name}}</td>

                            <td ><input type="text" name="equipment_model_no[]" id="equipment_model_no_{{$key}}"  value="{{ old('equipment_model_no.'.$key,optional($value)->equipment_model_no)}}" class="form-control " placeholder=" Modal No." readonly></td>


                            <td ><input type="text" name="rate[]" id="rate_{{$key}}"  value="{{ old('rate.'.$key,$rate)}}" class="form-control " placeholder="Rate" readonly></td>


                            <td ><input type="text" name="msp_no[]" id="msp_no{{$key}}"  value="{{ old('msp_no.'.$key,$msp_value )}}" class="form-control " placeholder=" Mps No." readonly></td>


                            <td id="before_pm{{$key-1}}">
                                <input type="text" name="margin_amount[]" value="{{ old('margin_amount.'.$key,$margin_amount)}}" class="form-control revenue_cal" placeholder=" Revenue" readonly>
                                    <div class="alert alert-danger" id="revanue_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td >
                                <input type="text" name="amount[]" value="{{$product_amount}}" class="form-control amount" placeholder=" amount" readonly>
                                    <div class="alert alert-danger" id="amount_error_{{$key}}" style="display:none"> </div>
                            </td>

                        
                            <td >
                                <input type="text" name="incentive[]" id="incentive{{$key}}"  value="{{ old('incentive.'.$key,$incentive_amount)}}" class="form-control" placeholder="Mps No." readonly>
    
                            </td>

                            <td>
                                <input type="text" name="tax[]" value="{{ number_format($product_amount_with_tax, 2) }}" class="form-control tax" placeholder=" tax" readonly>
                                    <div class="alert alert-danger" id="tax_error_{{$key}}" style="display:none"> </div>
                            </td>

                         
                            @if($verified)

                                <td >
                                    <input type="text" name="total_qty[]" value="{{ $value->equipment_qty}} Bal({{$value->equipment_qty - $bill_product_qty }})" class="form-control amount" placeholder=" Total qty" readonly>
                                        <div class="alert alert-danger" id="total_qty_{{$key}}" style="display:none"> </div>
                                </td>

                            @endif

                            <td >
                                <input type="text" name="taxper[]" id="taxper{{$key}}"  value="{{ old('taxper.'.$key,$value->tax_percentage)}}" class="form-control" placeholder="tax per." readonly>
    
                            </td>

                            <td>
                                <input type="text" name="total[]" value="{{ number_format($product_amount_with_tax_qty, 2) }}" class="form-control total" placeholder=" total" readonly>
                                    <div class="alert alert-danger" id="total_error_{{$key}}" style="display:none"> </div>
                            </td>

                            @if($verified && !empty($bill_contract) && count($bill_contract) > 0)

                                <td >
                                    <input type="text" name="billed_qty[]" value="{{ $bill_product_qty }}" class="form-control amount" placeholder="Billed qty" readonly>
                                        <div class="alert alert-danger" id="billed_qty_{{$key}}" style="display:none"> </div>
                                </td>

                                <td>
                                    <input type="text" name="bill_total[]" value="{{ number_format($bill_amount_with_qty, 2) }}" class="form-control total" placeholder=" total" readonly>
                                        <div class="alert alert-danger" id="bill_total_{{$key}}" style="display:none"> </div>
                                </td>

                            @endif

                            

                            

                        </tr>
                        
                            {{-- <input type="hidden" name="ib_id[]" id="ib_id_{{$key}}"  class="form-control " value="{{old('ib_id.'.$key,optional($value->oppertunityProductIb)->id)}}" readonly> --}}

                            <input type="hidden" name="equipment_id[]" id="equipment_id_{{$key}}"  class="form-control " value="{{old('equipment_id.'.$key,optional($value->equipment)->equipment_id)}}" readonly>
                        
                            <input type="hidden" name="equipment_status_id[]" id="equipment_status_id" value="{{ old('equipment_status_id',optional($value->equipment)->equipment_status_id) }}">

                            <input type="hidden" name="equpment_type[]" id="equpment_type{{$key}}" value="{{ old('equpment_type.'.$key,optional($value->equipment)->name) }}">

                            {{-- <input type="hidden" name="under_contract[]" id="under_contract{{$key}}" value="{{ old('under_contract.'.$key,optional($value->oppertunityProductIb->ibEquipmentStatus)->name) }}"> --}}

                            <input type="hidden" name="supplay_order[]" id="supplay_order_{{$key}}" value="{{ old('supplay_order.'.$key,optional($value->equipment)->supplay_order) }}">

                            <input type="hidden" name="warraty_expiry_date[]" id="warrenty_end_date_{{$key}}" value="{{ old('warranty_expiry_date.'.$key,optional($value->equipment)->warrenty_end_date) }}">
                            

                        @endforeach

                        <tr>

                            @if($verified && !empty($bill_contract) && count($bill_contract) > 0)

                                <td  colspan="4" id="spancolmn">&nbsp;</td>

                            @else

                                <td  colspan="3" id="spancolmn">&nbsp;</td>

                            @endif

                            <td ><strong>&nbsp;TOTAL </strong></td>

                            <td><strong> {{$rate_total}} </strong></td>

                            <td><strong> {{$msp_total}}</strong></td>

                            <td><strong> {{$margin_total}}</strong></td>
                            <td><strong> {{$amount_total}}</strong></td>
                            <td><strong> {{$incentive_total }} </strong></td>
                            <td><strong> {{$tax_total}}</strong></td>

                            @if($verified)

                            <td><strong></strong></td>

                            @endif
                            <td><strong>&nbsp;</strong></td>
                       
                            <td ><strong id="grand-total1"> {{ number_format($grand_contact_amount_with_qty, 2) }}</strong>&nbsp;&nbsp;</td>

                            {{-- <td><strong>&nbsp;Billing TOTAL </strong></td> --}}

                            @if($verified && !empty($bill_contract) && count($bill_contract) > 0)

                            <td >&nbsp;&nbsp;</td>
        
                            <td ><strong id="grand-total1"> {{ number_format($grand_bill_amount_with_qty, 2) }}</strong>&nbsp;&nbsp;</td>
                          
                            @endif

                        </tr>

                     </table>

                     @foreach($productDetails as $key=>$value)

                        <div class="
                            equipment-details" id="equipment-details-group"  @if(empty(old('equipment_id',optional($value->equipment)->equipment_id))) style="display: none" @endif >

                        <div class="row">

                                                                       

                        </div>

                        </div>

                        @endforeach


                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">

                    <?php /*
                      <button type="submit" id="submit_contract" disabled  class="btn btn-primary">Approve</button>
                      */?>
                      @if($verified)

                        <button type="button" id="" disabled  class="btn btn-success">verified</button>

                        <button type="button" id="billbutton" onclick="ShowBill()" class="btn btn-danger bill-btn" > Bill</button>
                      @else

                      <button type="button" id="" disabled  class="btn btn-danger">Not verified</button>

                      @endif

                      <button type="button" class="btn btn-danger" onclick="history.back()">Cancel</button>
                      
                    </div>
              </form>
          </div>

      </div>

  </div>


    @if(!empty($bill_contract) && count($bill_contract) > 0 )

        <div class="row">

                <table class="table table-bordered" border="0" style="border-collapse: collapse;">
                    <tbody>

                        <tr>
                            <th>Sl.no</th>
                            <th>Bill no</th>
                            <th>Date</th>
                            <th></th>

                            <th>attachment</th>

                            <th>Total</th>

                        </tr>

                        @foreach ($bill_contract as $k=> $item)

                            <tr>

                              

                                    @php
                                        $extension = pathinfo($item->attachment, PATHINFO_EXTENSION);

                                        $bill_product =  App\BillProduct::where('contract_bill_id',$item->id)->get();

                                        $grand_total = 0;
                                    @endphp

                                    <td>{{$k+1}}</td>
                                    <td>{{$item->bill_no}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->data)->format('d-m-Y') }}</td>



                                    @foreach ($bill_product as $k=> $prodt)

                                 

                                
                                @endforeach


                                    <td>  </td>

                                    @if(!empty($item->attachment))
                                        
                                        @if (strtolower($extension) == 'pdf' || strtolower($extension) == '.xlxs')

                                        <td> <a href="{{ asset('storage/app/public/bill_attachment/'.$item->attachment) }}" target="_blank" ><i class="fa fa-download"></i> </td>

                                        @else

                                        <td> <img src="{{ asset('storage/app/public/bill_attachment/'.$item->attachment) }}" ></td>

                                        @endif

                                    @else

                                         <td>-</td>

                                    @endif

                                @php
                            
                                    $bill_total = 0;

                                    $bill_grand_tax_total = 0;

                                    $bill_grand_total = 0; 

                                @endphp
    
                                @foreach ($bill_product as $k=> $prodt)
    
                                    @php 
    
                                    $contract_product =  App\ContractProduct::where('id',$prodt->contract_bill_id)->first();

                                    $bill_amount_cal = $prodt->billproduct->amount* ($prodt->bill_qty ??1);

                                    $bill_total += $bill_amount_cal;

                                    $service_tax_percentage =  $prodt->billproduct->tax_percentage??12; 

                                    $bill_tax =0;

                                    $bill_tax = $bill_amount_cal * ($service_tax_percentage / 100);

                                    $bill_tax_total = $bill_amount_cal + $bill_tax;

                                    $bill_grand_tax_total += $bill_tax;

                                    $bill_grand_total = $bill_total + $bill_grand_tax_total;
    
                                @endphp

                                @endforeach

                                <td> {{ number_format($bill_grand_total, 2) }} </td>
                               
                            </tr>
                            @php

                                $bill_product =  App\BillProduct::where('contract_bill_id',$item->id)->get();

                                $grand_total = 0;
                            @endphp

                            @if(!empty($bill_product) && count($bill_product) > 0)


                            <tr>
                                <th></th>
                                <th>Equi. Name</th>
                                <th>Qty</th>
                                <th>Price</th>

                                <th></th>

                                <th></th>
                            </tr>


                                @foreach ($bill_product as $k=> $prodt)

                                   

                                    <tr>
                                        <td></td>

                                        <td> {{$prodt->equip_name}}</td>

                                        <td> {{$prodt->bill_qty}}</td>

                                        @php
                            
                                            $bill_total = 0;
    
                                            $bill_grand_tax_total = 0;
        
                                            $bill_grand_total = 0; 
        
                                        @endphp

                                        @php 
    
                                            $contract_product =  App\ContractProduct::where('id',$prodt->contract_bill_id)->first();
        
                                            $bill_amount_cal = $prodt->billproduct->amount* ($prodt->bill_qty ??1);
        
                                            $bill_total += $bill_amount_cal;
        
                                            $service_tax_percentage =  $prodt->billproduct->tax_percentage??12; 
        
                                            $bill_tax =0;
        
                                            $bill_tax = $bill_amount_cal * ($service_tax_percentage / 100);
        
                                            $bill_tax_total = $bill_amount_cal + $bill_tax;
        
                                            $bill_grand_tax_total += $bill_tax;
        
                                            $bill_grand_total = $bill_total + $bill_grand_tax_total;
            
                                        @endphp

                                        <td> {{ number_format($bill_tax_total, 2) }}</td>

                                        <td></td>

                                    </tr>

                                @endforeach

                            @endif

                        @endforeach



                    </tbody>

                </table>

        </div>

    @endif

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

                <form method="post" action="{{route('staff.create_bill')}}" class="bill-from" id="bill_form" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-bordered" border="0" style="border-collapse: collapse;">
                        <tbody>

                            <tr id="bill_head" >
                                <th>Item No</th>
                                <th>Name</th>
                                <th>Order Qty</th>
                                <th>Balance Qty</th>
                                <th>Amount</th>
                                <th>Bill Qty</th>
                                <th>Total Billed Price</th>

                            </tr>

                        </tbody>

                    </table>

                        <input type="hidden"  name="bill_contract_id"  value="{{$contract->id}}" id="bill_contract_id" >

                        <input type="hidden"  name="salescontract_id"  value="{{$salescontract->id}}" id="" >

                        <label for="bill_attach" id="attachlabel">Attach bill</label> 

                        <input type="file" class="bill-attach" name="bill_attach" id="bill_attach">

                        <button type="button" class="btn btn-primary" onclick="BillSubmit()" id="bill_submit" >Submit</button>
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
        //     //dateFormat:'yy-mm-dd',
        //     dateFormat: 'yy-mm-dd',
        //     // minDate: 0,
        //     onSelect: function() {
        //         console.log('End date selected:', this.value);
        //         CheckEndDate(this);
        //     }
        // }); 

        $('.datepicker').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });

        $('.start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',

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

        function ShowBill(mainPdt)
        {
              var billids = []; 

        $('input[name="billbutton"]:checked').each(function() {
        
            billids.push($(this).val());
        });

        console.log(billids);

            $.ajax({
                type: "POST",
                cache: false,
                url: APP_URL + '/staff/fetch_optional_products',
                data: {
                    product_ids: billids,
                },
                success: function(res) {

                    console.log(res);

                        $('.bill_items').remove();

                        $('#BillModal').modal('show');      
                        var billDetails = '';

                        $.each(res, function(i, v)
                        {
                            billDetails += `
                            <tr class="bill_items">

                                <td> ${i+1}  <input type="hidden" class="form-control" name="contract_product_id[]"  value="${v.contract_id}"> </td>

                                <td> <input type="text" class="form-control" name="equip_name[]"  value="${v.equipment_name}" readonly> </td>

                                <td> <input type="text" class="form-control" name="order_qty[]" id="order_qty-${i}" value="${v.equipment_qty}" readonly></td>

                                <td> ${v.balance_qty} </td>

                                <td> <input type="text" class="form-control" name="bill_amount[]" id="amount_bill-${i}" value="${v.bill_amount}" readonly> </td>

                                <td> 

                                 

                                    <input type="hidden" class="form-control" name="balance_qty[]" id="balance_qty-${i}"  value="${v.balance_qty}">

                                    <input type="text" class="form-control" name="bill_qty[]" id="bill_qty-${i}" value="" data-id="${i}" oninput="CalTotalPrice(this)">

                                    <span class="error" id="error-${i}" style="display:none"> </span>
                                    
                                </td>

                                 <td> 
                                    <input type="text" class="form-control" name="total_bill_amount[]"  id="total_bill_amount-${i}" value="" > 

                                    <input type="hidden" class="form-control" name="tax_total_bill[]"  id="tax_total_bill-${i}" value="${v.tax}" > 

                                </td>
                                

                            </tr>

                            `;
                        });

                        $('#bill_head').after(billDetails);
                }
            });

        }

        function CalTotalPrice(element) {

            var id = $(element).data('id');

                var amount = parseFloat($(`#amount_bill-${id}`).val());  
                var tax = parseFloat($(`#tax_total_bill-${id}`).val()); 

              
                if (isNaN(tax)) {
                    tax = 12;
                }

                var qty = parseFloat($(element).val());

           
                if (isNaN(qty)) {
                    qty = 0;
                }

                var with_tax_amount = amount + (amount * (tax / 100));

                var with_tax_amount_qty = qty * with_tax_amount;

                $(`#total_bill_amount-${id}`).val(with_tax_amount_qty.toFixed(2));

                // console.log(id, '*', amount, '*', tax, '*', qty, '*', with_tax_amount, '*', with_tax_amount_qty);
            }


        function BillSubmit()
        {

            var isValid = true;

            $('tr.bill_items').each(function(i) {

                var orderQty = parseInt($('#balance_qty-' + i).val());  
                var billQty = parseInt($('#bill_qty-' + i).val());  
                
                if (billQty > orderQty) {
                    isValid = false;

                    $('#bill_qty-' + i).addClass('is-invalid');

                    $(`#error-${i}`).text('Bill quantity cannot be greater than Balance quantity').show();
                    
                    // alert('Bill quantity cannot be greater than order quantity for item ' + (i + 1));
                } else {

                    $('#bill_qty-' + i).removeClass('is-invalid');

                    $(`#error-${i}`).hide();
                }
            });

            if (!isValid) {
                return false; 
            }


            var url = $('#bill_form').attr('action');

            // var formdata = $('#bill_form').serialize();

            // var formfile = $('#bill_attach').val();

            var formdata = new FormData($('#bill_form')[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: formdata,
                processData: false,
                contentType: false, 
                cache: false,       
                success: function(res) {
                    console.log(res);

                    window.location.reload();

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
                let opportunityId = $(this).data('id'); 
                console.log(opportunityId);

                $.ajax({
                    url: '/staff/reject-opportunity',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        opportunity_id: opportunityId,

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





 