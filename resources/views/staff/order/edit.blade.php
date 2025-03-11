

@extends('staff/layouts.app')

@section('title', 'Edit Order')

@section('content')

<section class="content-header">
      <h1>
        Edit Order
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_order')}}">Manage Order</a></li>
        <li class="active">Edit Order</li>
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
            <div class="row">

              <div class="col-lg-12 margin-tb">

                  <div class="pull-left">

                      <a class="btn btn-sm btn-success" href="{{ url('staff/add_order_product/'.$order_no) }}"> <span class="glyphicon glyphicon-plus"></span>Add new product</a>
                      
                  </div>

              </div>
<br><br>
              <div class="form-group col-md-12 border">
            <!-- <label for="name">Service</label> -->
            <select id="service" name="service" class="form-control" onchange="service_change(this.value)">
            <option value="edit_order">Edit Order</option>
            <option value="po_details" >PO Details</option>
            <option value="documents" >Documents</option>
            <option value="comtaskvisit" >Comment/Task/Visit</option>
            <option value="invoices" >Invoices</option>
           
            <option value="dispatch" >Dispatch/Return</option>
            <option value="payments" >Payments</option>

            </select>
          
            </div>



          </div>

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

            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="box-body">

<div class="box-body edit_order">
<h3>Edit Order</h3>

          <table id="chattertable" class="table table-bordered table-striped data-">
            <thead>
              <th>Id</th>
              <th>Products</th>
              <th>Quantity</th>
              <th>Sale Amount</th>
              <th>Amount</th>
              <th>Action</th>
            </thead>
            <tbody>
              <form action ="" method="post">
                
              @if(sizeof($order)>0)
                @php $i = 1; @endphp
                @foreach($order as $ord)
                <tr id="trr_{{$ord->id}}">
                  <td>{{$i++}}</td>
                  <td>
                    <div class="form-group">
                      <label>{{$ord->product->name}}</label>
                       <input type="hidden" id="product_id{{$ord->id}}" name="product_id[]" class="form-control"  value="{{$ord->product_id}}" readonly="">
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <select name="quantitys[]" id="quantity{{$ord->id}}" class="form-control quantity">
                        <option value="">-- Select quantity --</option>
                        @for($i=1;$i<=10;$i++)
                          <option value="{{$i}}" @if(old('quantitys',$ord->quantity)==$i){{'selected'}} @endif>{{$i}}</option>
                        @endfor
                      </select>
                      <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                       <input type="text" id="sale_amount{{$ord->id}}" name="sale_amount[]" class="form-control"  value="{{old('sale_amount',$ord->sale_amount)}}">
                       <span class="error_message" id="sale_message" style="display: none">Invalid amount. Please contact admin</span>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                       <input type="text" id="amount{{$ord->id}}" name="amount[]" class="form-control"  value="{{old('amount',$ord->amount)}}" readonly="">
                    </div>
                  </td>
                  <td>
                    <a href=""class="btn btn-danger btn-sm del"  data-id="{{$ord->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                    <a href=""class="btn btn-info btn-sm upd"  data-id="{{$ord->id}}"  data-order = {{$order_no}} title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
                  </td>
                </tr>
                @endforeach
              @else
              <tr>
                <td>No record found</td>
              </tr>
              @endif
              
            </form>
            </tbody>
          </table>

  </div>


<div class="box-body po_details" style="display:none;">
<h3>PO Details</h3>

<div class="govsec" <?php if($order[0]->checklist=="Government Sales"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >

   <form action ="" method="post" name="govt_sale_form" id="govt_sale_form"> 
<input type="hidden" name="gov_order_id" id="gov_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="gov_id" id="gov_id" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->id : '';?>">



  <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Government Sales")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif


        

  <!-- <div class="form-group col-md-3">
      <label>PO</label>
      <input type="text" id="gov_po" name="gov_po" class="form-control" placeholder="PO" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_po : '';?>">
      <span class="error_message" id="gov_po_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_po_status" name="gov_po_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_po_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_po_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Configuration</label>
      <input type="text" id="gov_config" name="gov_config" class="form-control" placeholder="Configuration" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_config : '';?>">
      <span class="error_message" id="gov_config_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" name="gov_config_status" id="gov_config_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_config_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_config_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Payment terms</label>
      <input type="text" id="gov_payment" name="gov_payment" class="form-control" placeholder="Payment terms" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_payment : '';?>">
      <span class="error_message" id="gov_payment_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_payment_status" name="gov_payment_status"  <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_payment_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_payment_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Delivery terms</label>
      <input type="text" id="gov_delivery" name="gov_delivery" class="form-control" placeholder="Delivery terms" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_delivery : '';?>">
      <span class="error_message" id="gov_delivery_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_delivery_status" name="gov_delivery_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_delivery_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_delivery_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>GST Certificate / Mail confirmation</label>
      <input type="text" id="gov_gst" name="gov_gst" class="form-control" placeholder="GST Certificate / Mail confirmation" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_gst : '';?>">
      <span class="error_message" id="gov_gst_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_gst_status" name="gov_gst_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_gst_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_gst_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Technical Approval</label>
      <input type="text" id="gov_technical" name="gov_technical" class="form-control" placeholder="Technical Approval" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_technical : '';?>">
      <span class="error_message" id="gov_technical_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_technical_status" name="gov_technical_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_technical_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_technical_status">
     Verify
      </label>
    </div>

    </div>

  <div class="form-group col-md-3">
      <label>Financial Approval</label>
      <input type="text" id="gov_financial" name="gov_financial" class="form-control" placeholder="Financial Approval" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_financial : '';?>">
      <span class="error_message" id="gov_financial_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_financial_status" name="gov_financial_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_financial_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_financial_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Stock availability</label>
      <input type="text" id="gov_stock_avl" name="gov_stock_avl" class="form-control" placeholder="Stock availability" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_stock_avl : '';?>">
      <span class="error_message" id="gov_stock_avl_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_stock_avl_status" name="gov_stock_avl_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_stock_avl_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_stock_avl_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Customer Confirmation</label>
      <input type="text" id="gov_cus_confirm" name="gov_cus_confirm" class="form-control" placeholder="Customer Confirmation" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_cus_confirm : '';?>">
      <span class="error_message" id="gov_cus_confirm_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_cus_confirm_status" name="gov_cus_confirm_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_cus_confirm_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_cus_confirm_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Invoice</label>
      <input type="text" id="gov_invoice" name="gov_invoice" class="form-control" placeholder="Invoice" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_invoice : '';?>">
      <span class="error_message" id="gov_invoice_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_invoice_status" name="gov_invoice_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_invoice_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_invoice_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Warranty Certificate</label>
      <input type="text" id="gov_warranty" name="gov_warranty" class="form-control" placeholder="Warranty Certificate" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_warranty : '';?>">
      <span class="error_message" id="gov_warranty_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_warranty_status" name="gov_warranty_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_warranty_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_warranty_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Instalation Certificate</label>
      <input type="text" id="gov_install" name="gov_install" class="form-control" placeholder="Instalation Certificate" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_install : '';?>">
      <span class="error_message" id="gov_install_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_install_status" name="gov_install_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_install_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_install_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>PO</label>
      <input type="text" id="gov_po1" name="gov_po1" class="form-control" placeholder="PO" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_po1 : '';?>">
      <span class="error_message" id="gov_po1_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_po1_status" name="gov_po1_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_po1_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_po1_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Sticker from KMSCL</label>
      <input type="text" id="gov_kmscl" name="gov_kmscl" class="form-control" placeholder="Sticker from KMSCL" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_kmscl : '';?>">
      <span class="error_message" id="gov_kmscl_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_kmscl_status" name="gov_kmscl_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_kmscl_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_kmscl_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Verify Item with invoice and PO</label>
      <input type="text" id="gov_verify" name="gov_verify" class="form-control" placeholder="Verify Item with invoice and PO" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_verify : '';?>">
      <span class="error_message" id="gov_verify_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_verify_status" name="gov_verify_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_verify_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_verify_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Eway Bill</label>
      <input type="text" id="gov_eway" name="gov_eway" class="form-control" placeholder="Eway Bill" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_eway : '';?>">
      <span class="error_message" id="gov_eway_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_eway_status" name="gov_eway_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_eway_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_eway_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Stock entry form customer</label>
      <input type="text" id="gov_stock_entry" name="gov_stock_entry" class="form-control" placeholder="Stock entry form customer" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_stock_entry : '';?>">
      <span class="error_message" id="gov_stock_entry_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_stock_entry_status"  name="gov_stock_entry_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_stock_entry_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_stock_entry_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Photo of the equipment with user</label>
      <input type="text" id="gov_photo_equip" name="gov_photo_equip" class="form-control" placeholder="Photo of the equipment with user" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_photo_equip : '';?>">
      <span class="error_message" id="gov_photo_equip_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_photo_equip_status" name="gov_photo_equip_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_photo_equip_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_photo_equip_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Invoice / PO / Instalation / Warranty certificate from customer</label>
      <input type="text" id="gov_invo_pd" name="gov_invo_pd" class="form-control" placeholder="Invoice / PO / Instalation / Warranty certificate from customer" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_invo_pd : '';?>">
      <span class="error_message" id="gov_invo_pd_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_invo_pd_status" name="gov_invo_pd_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_invo_pd_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_invo_pd_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Agreement</label>
      <input type="text" id="gov_agreement" name="gov_agreement" class="form-control" placeholder="Agreement" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_agreement : '';?>">
      <span class="error_message" id="gov_agreement_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_agreement_status" name="gov_agreement_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_agreement_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_agreement_status">
     Verify
      </label>
    </div>

    </div>

<div class="form-group col-md-12">
<h3>Bank Guarantee from the date of installation</h3>
</div>

    <div class="form-group col-md-3">
  
      <label>a. BG Undertaking</label>
      <input type="text" id="gov_undertaking" name="gov_undertaking" class="form-control" placeholder="a. BG Undertaking" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_undertaking : '';?>">
      <span class="error_message" id="gov_undertaking_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_undertaking_status" name="gov_undertaking_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_undertaking_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_undertaking_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>b. BG Agreement</label>
      <input type="text" id="gov_bgagreement" name="gov_bgagreement" class="form-control" placeholder="b. BG Agreement" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_bgagreement : '';?>">
      <span class="error_message" id="gov_bgagreement_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_bgagreement_status" name="gov_bgagreement_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_bgagreement_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_bgagreement_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>c. FD</label>
      <input type="text" id="gov_fd" name="gov_fd" class="form-control" placeholder="c. FD" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_fd : '';?>">
      <span class="error_message" id="gov_fd_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_fd_status" name="gov_fd_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_fd_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_fd_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>D. Request latter</label>
      <input type="text" id="gov_req_latter" name="gov_req_latter" class="form-control" placeholder="D. Request latter" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_req_latter : '';?>">
      <span class="error_message" id="gov_req_latter_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_req_latter_status" name="gov_req_latter_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_req_latter_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_req_latter_status">
     Verify
      </label>
    </div>

    </div>

    <div class="form-group col-md-3">
      <label>Letter to Party with all the above documents (8 to 13)</label>
      <input type="text" id="gov_letter_party" name="gov_letter_party" class="form-control" placeholder="Letter to Party with all the above documents (8 to 13)" value="<?php echo (count($govt_sales) > 0) ? $govt_sales[0]->gov_letter_party : '';?>">
      <span class="error_message" id="gov_letter_party_message" style="display: none">Field is required</span>
     
      <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gov_letter_party_status" name="gov_letter_party_status" <?php echo (count($govt_sales) > 0 && $govt_sales[0]->gov_letter_party_status=="Y") ? 'checked' : '';?>>
      <label class="form-check-label" for="gov_letter_party_status">
     Verify
      </label>
    </div>

    </div> -->
    

                      <div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_gov_sale()">Save</button>
                      </div>

    </form>
    
  </div>   
    

    <div class="exportsec"  <?php if($order[0]->checklist=="Export Sales"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

 <form action ="" method="post" name="export_sale_form" id="export_sale_form"> 
<input type="hidden" name="export_order_id" id="export_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="export_id" id="export_id" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->id : '';?>">



          <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Export Sales")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif


      <!-- <div class="form-group col-md-3">
        <label>PO</label>
        <input type="text" id="export_po" name="export_po" class="form-control" placeholder="PO" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_po : '';?>">
        <span class="error_message" id="export_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_po_status" name="export_po_status" <?php echo (count($export_sales) > 0 && $export_sales[0]->export_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_po_status">
          Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Configuration</label>
        <input type="text" id="export_config" name="export_config" class="form-control" placeholder="Configuration" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_config : '';?>">
        <span class="error_message" id="export_config_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_config_status" name="export_config_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_config_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_config_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Payment terms</label>
        <input type="text" id="export_payment" name="export_payment" class="form-control" placeholder="Payment terms" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_payment : '';?>">
        <span class="error_message" id="export_payment_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_payment_status" name="export_payment_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_payment_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_payment_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Delivery terms</label>
        <input type="text" id="export_delivery_terms" name="export_delivery_terms" class="form-control" placeholder="Delivery terms" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_delivery_terms : '';?>">
        <span class="error_message" id="export_delivery_terms_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_delivery_terms_status" name="export_delivery_terms_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_delivery_terms_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_delivery_terms_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>GST Certificate / Mail confirmation</label>
        <input type="text" id="export_gst" name="export_gst" class="form-control" placeholder="GST Certificate / Mail confirmation" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_gst : '';?>">
        <span class="error_message" id="export_gst_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_gst_status" name="export_gst_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_gst_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_gst_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Technical Approval</label>
        <input type="text" id="export_technical" name="export_technical" class="form-control" placeholder="Technical Approval" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_technical : '';?>">
        <span class="error_message" id="export_technical_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_technical_status" name="export_technical_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_technical_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_technical_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Financial Approval</label>
        <input type="text" id="export_financial" name="export_financial" class="form-control" placeholder="Financial Approval" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_financial : '';?>">
        <span class="error_message" id="export_financial_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_financial_status" name="export_financial_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_financial_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_financial_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Stock availability</label>
        <input type="text" id="export_stock" name="export_stock" class="form-control" placeholder="Stock availability" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_stock : '';?>">
        <span class="error_message" id="export_stock_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_stock_status" name="export_stock_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_stock_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_stock_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Customer Confirmation</label>
        <input type="text" id="export_cust_conf" name="export_cust_conf" class="form-control" placeholder="Customer Confirmation" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_cust_conf : '';?>">
        <span class="error_message" id="export_cust_conf_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_cust_conf_status" name="export_cust_conf_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_cust_conf_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_cust_conf_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Shippers Letter SLI</label>
        <input type="text" id="export_sli" name="export_sli" class="form-control" placeholder="Shippers Letter SLI" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_sli : '';?>">
        <span class="error_message" id="export_sli_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_sli_status" name="export_sli_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_sli_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_sli_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Annexure 1</label>
        <input type="text" id="export_annexure" name="export_annexure" class="form-control" placeholder="Annexure 1" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_annexure : '';?>">
        <span class="error_message" id="export_annexure_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_annexure_status" name="export_annexure_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_annexure_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_annexure_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>SFD</label>
        <input type="text" id="export_sfd" name="export_sfd" class="form-control" placeholder="SFD" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_sfd : '';?>">
        <span class="error_message" id="export_sfd_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_sfd_status" name="export_sfd_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_sfd_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_sfd_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Non Hazards Certificate</label>
        <input type="text" id="export_haz_cert" name="export_haz_cert" class="form-control" placeholder="Non Hazards Certificate" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_haz_cert : '';?>">
        <span class="error_message" id="export_haz_cert_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_haz_cert_status" name="export_haz_cert_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_haz_cert_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_haz_cert_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>IGST Non-claim declaration</label>
        <input type="text" id="export_igst" name="export_igst" class="form-control" placeholder="IGST Non-claim declaration" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_igst : '';?>">
        <span class="error_message" id="export_igst_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_igst_status" name="export_igst_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_igst_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_igst_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Invoice</label>
        <input type="text" id="export_invoice" name="export_invoice" class="form-control" placeholder="Invoice" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_invoice : '';?>">
        <span class="error_message" id="export_invoice_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_invoice_status" name="export_invoice_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_invoice_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_invoice_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Packing slip </label>
        <input type="text" id="export_packing" name="export_packing" class="form-control" placeholder="Packing slip" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_packing : '';?>">
        <span class="error_message" id="export_packing_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_packing_status" name="export_packing_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_packing_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_packing_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Technical Writeup</label>
        <input type="text" id="export_tech_write" name="export_tech_write" class="form-control" placeholder="Technical Writeup" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_tech_write : '';?>">
        <span class="error_message" id="export_tech_write_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_tech_write_status" name="export_tech_write_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_tech_write_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_tech_write_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>IEC Certificate</label>
        <input type="text" id="export_iec" name="export_iec" class="form-control" placeholder="IEC Certificate" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_iec : '';?>">
        <span class="error_message" id="export_iec_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_iec_status" name="export_iec_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_iec_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_iec_status">
      Verify
        </label>
      </div>

      </div>
      

      <div class="form-group col-md-3">
        <label>AD code from bank copy</label>
        <input type="text" id="export_ad_code" name="export_ad_code" class="form-control" placeholder="AD code from bank copy" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_ad_code : '';?>">
        <span class="error_message" id="export_ad_code_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_ad_code_status" name="export_ad_code_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_ad_code_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_ad_code_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Proof of Origin from CII</label>
        <input type="text" id="export_cli" name="export_cli" class="form-control" placeholder="Proof of Origin from CII" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_cli : '';?>">
        <span class="error_message" id="export_cli_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_cli_status" name="export_cli_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_cli_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_cli_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Verify Item with invoice and PO</label>
        <input type="text" id="export_inv_po" name="export_inv_po" class="form-control" placeholder="Verify Item with invoice and PO" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_inv_po : '';?>">
        <span class="error_message" id="export_inv_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_inv_po_status" name="export_inv_po_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_inv_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_inv_po_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Eway Bil</label>
        <input type="text" id="export_eway" name="export_eway" class="form-control" placeholder="Eway Bil" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_eway : '';?>">
        <span class="error_message" id="export_eway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_eway_status" name="export_eway_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_eway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_eway_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Despatch</label>
        <input type="text" id="export_despatch" name="export_despatch" class="form-control" placeholder="Despatch" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_despatch : '';?>">
        <span class="error_message" id="export_despatch_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_despatch_status" name="export_despatch_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_despatch_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_despatch_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Delivery</label>
        <input type="text" id="export_delivery" name="export_delivery" class="form-control" placeholder="Delivery" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_delivery : '';?>">
        <span class="error_message" id="export_delivery_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_delivery_status" name="export_delivery_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_delivery_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_delivery_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Shipping Bill </label>
        <input type="text" id="export_shipping" name="export_shipping" class="form-control" placeholder="Shipping Bill " value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_shipping : '';?>">
        <span class="error_message" id="export_shipping_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_shipping_status" name="export_shipping_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_shipping_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_shipping_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Airway Bill copy</label>
        <input type="text" id="export_airway" name="export_airway" class="form-control" placeholder="Airway Bill copy" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_airway : '';?>">
        <span class="error_message" id="export_airway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_airway_status" name="export_airway_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_airway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_airway_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Invoice Packing slip copy</label>
        <input type="text" id="export_slip_copy" name="export_slip_copy" class="form-control" placeholder="Invoice Packing slip copy" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_slip_copy_status : '';?>">
        <span class="error_message" id="export_slip_copy_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_slip_copy_status" name="export_slip_copy_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_slip_copy=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_slip_copy_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Foreign Inward remittance form</label>
        <input type="text" id="export_foreign" name="export_foreign" class="form-control" placeholder="Foreign Inward remittance form" value="<?php echo (count($export_sales) > 0) ? $export_sales[0]->export_foreign : '';?>">
        <span class="error_message" id="export_foreign_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="export_foreign_status" name="export_foreign_status"  <?php echo (count($export_sales) > 0 && $export_sales[0]->export_foreign_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="export_foreign_status">
      Verify
        </label>
      </div>

      </div> -->

       <div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_export_sale()">Save</button>
                      </div>

    </form>




    </div>

    
    <div class="purchasesec" <?php if($order[0]->checklist=="Purchase import"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

<form action ="" method="post" name="purchase_sale_form" id="purchase_sale_form"> 
<input type="hidden" name="purchase_order_id" id="purchase_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->id : '';?>">
    

      <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Purchase import")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif


    

    <!-- <div class="form-group col-md-3">
        <label>PO</label>
        <input type="text" id="purchase_po" name="purchase_po" class="form-control" placeholder="PO" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_po : '';?>">
        <span class="error_message" id="purchase_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_po_status" name="purchase_po_status"  <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_po_status">
      Verify
        </label>
      </div>

      </div>

      

       <div class="form-group col-md-3">
        <label>Order Confirmation / Performa Invoice</label>
        <input type="text" id="purchase_order" name="purchase_order" class="form-control" placeholder="Order Confirmation / Performa Invoice" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_order : '';?>">
        <span class="error_message" id="purchase_order_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_order_status" name="purchase_order_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_order_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_order_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>LC/prepayment</label>
        <input type="text" id="purchase_prepay" name="purchase_prepay" class="form-control" placeholder="LC/prepayment" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_prepay : '';?>">
        <span class="error_message" id="purchase_prepay_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_prepay_status" name="purchase_prepay_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_prepay_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_prepay_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Invoice and Packing slip from supplier</label>
        <input type="text" id="purchase_inco_slip" name="purchase_inco_slip" class="form-control" placeholder="Invoice and Packing slip from supplier" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_inco_slip : '';?>">
        <span class="error_message" id="purchase_inco_slip_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_inco_slip_status" name="purchase_inco_slip_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_inco_slip_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_inco_slip_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Clearing Agent details to supplier</label>
        <input type="text" id="purchase_clear_det" name="purchase_clear_det" class="form-control" placeholder="Clearing Agent details to supplier" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_clear_det : '';?>">
        <span class="error_message" id="purchase_clear_det_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_clear_det_status" name="purchase_clear_det_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_clear_det_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_clear_det_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Pickup request to transporter</label>
        <input type="text" id="purchase_transport" name="purchase_transport" class="form-control" placeholder="Pickup request to transporter" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_transport : '';?>">
        <span class="error_message" id="purchase_transport_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_transport_status" name="purchase_transport_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_transport_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_transport_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Book Airway bill</label>
        <input type="text" id="purchase_airway" name="purchase_airway" class="form-control" placeholder="Book Airway bill" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_airway : '';?>">
        <span class="error_message" id="purchase_airway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_airway_status" name="purchase_airway_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_airway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_airway_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Payment of customs duty</label>
        <input type="text" id="purchase_custom" name="purchase_custom" class="form-control" placeholder="Payment of customs duty" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_custom : '';?>">
        <span class="error_message" id="purchase_custom_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_custom_status" nmae="purchase_custom_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_custom_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_custom_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Verify Item with invoice and PO</label>
        <input type="text" id="purchase_gov_po" name="purchase_gov_po" class="form-control" placeholder="Verify Item with invoice and PO" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_gov_po : '';?>">
        <span class="error_message" id="purchase_gov_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_gov_po_status" name="purchase_gov_po_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_gov_po_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Eway Bill</label>
        <input type="text" id="purchase_gov_eway" name="purchase_gov_eway" class="form-control" placeholder="Eway Bill" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_gov_eway : '';?>">
        <span class="error_message" id="purchase_gov_eway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_gov_eway_status" name="purchase_gov_eway_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_gov_eway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_gov_eway_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>A1 Foreign outward remittance form</label>
        <input type="text" id="purchase_foreign" name="purchase_foreign" class="form-control" placeholder="A1 Foreign outward remittance form" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_foreign : '';?>">
        <span class="error_message" id="purchase_foreign_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_foreign_status" name="purchase_foreign_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_foreign_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_foreign_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Bill of Entry </label>
        <input type="text" id="purchase_bill" name="purchase_bill" class="form-control" placeholder="Bill of Entry " value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_bill : '';?>">
        <span class="error_message" id="purchase_bill_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_bill_status" name="purchase_bill_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_bill_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_bill_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Invoice</label>
        <input type="text" id="purchase_invoice" name="purchase_invoice" class="form-control" placeholder="Invoice" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_invoice : '';?>">
        <span class="error_message" id="purchase_invoice_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_invoice_status" name="purchase_invoice_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_invoice_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_invoice_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Purchase order</label>
        <input type="text" id="purchase_pur_order" name="purchase_pur_order" class="form-control" placeholder="Purchase order" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_pur_order : '';?>">
        <span class="error_message" id="purchase_pur_order_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_pur_order_status" name="purchase_pur_order_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_pur_order_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_pur_order_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Payment reference number, date & Amount mail</label>
        <input type="text" id="purchase_ref_no" name="purchase_ref_no" class="form-control" placeholder="Payment reference number, date & Amount mail" value="<?php echo (count($purchase_sales) > 0) ? $purchase_sales[0]->purchase_ref_no : '';?>">
        <span class="error_message" id="purchase_ref_no_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="purchase_ref_no_status" name="purchase_ref_no_status" <?php echo (count($purchase_sales) > 0 && $purchase_sales[0]->purchase_ref_no_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="purchase_ref_no_status">
      Verify
        </label>
      </div>

      </div> -->

<div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_purchase_sale()">Save</button>
                      </div>

    </form>

      

    </div>


 <div class="localsaleregsec"   <?php if($order[0]->checklist=="Local Sales to Registered Dealer"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

<form action ="" method="post" name="register_sale_form" id="register_sale_form"> 
<input type="hidden" name="register_order_id" id="register_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="register_id" id="register_id" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->id : '';?>">
  

  

         <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Local Sales to Registered Dealer")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif

 
      <!-- <div class="form-group col-md-3">
        <label>PO</label>
        <input type="text" id="salereg_po" name="salereg_po" class="form-control" placeholder="PO" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_po : '';?>">
        <span class="error_message" id="salereg_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_po_status" name="salereg_po_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_po_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Configuration</label>
        <input type="text" id="salereg_config" name="salereg_config" class="form-control" placeholder="Configuration" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_config : '';?>">
        <span class="error_message" id="salereg_config_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_config_status" name="salereg_config_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_config_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_config_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Payment terms </label>
        <input type="text" id="salereg_payment" name="salereg_payment" class="form-control" placeholder="Payment terms " value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_payment : '';?>">
        <span class="error_message" id="salereg_payment_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_payment_status" name="salereg_payment_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_payment_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_payment_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Delivery terms </label>
        <input type="text" id="salereg_purchase" name="salereg_purchase" class="form-control" placeholder="Delivery terms " value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_purchase : '';?>">
        <span class="error_message" id="salereg_purchase_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_purchase_status" name="salereg_purchase_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_purchase_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_purchase_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>GST Certificate / Mail confirmation</label>
        <input type="text" id="salereg_gst" name="salereg_gst" class="form-control" placeholder="GST Certificate / Mail confirmation" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_gst : '';?>">
        <span class="error_message" id="salereg_gst_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_gst_status" name="salereg_gst_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_gst_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_gst_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Technical Approval</label>
        <input type="text" id="salereg_technical" name="salereg_technical" class="form-control" placeholder="Technical Approval" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_technical : '';?>">
        <span class="error_message" id="salereg_technical_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_technical_status" name="salereg_technical_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_technical_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_technical_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Financial Approval</label>
        <input type="text" id="salereg_financial" name="salereg_financial" class="form-control" placeholder="Financial Approval" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_financial : '';?>">
        <span class="error_message" id="salereg_financial_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_financial_status" name="salereg_financial_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_financial_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_financial_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Stock availability</label>
        <input type="text" id="salereg_stock_avil" name="salereg_stock_avil" class="form-control" placeholder="Stock availability" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_stock_avil : '';?>">
        <span class="error_message" id="salereg_stock_avil_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_stock_avil_status" name="salereg_stock_avil_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_stock_avil_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_stock_avil_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Customer Confirmation</label>
        <input type="text" id="salereg_cust_conf" name="salereg_cust_conf" class="form-control" placeholder="Customer Confirmation" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_cust_conf : '';?>">
        <span class="error_message" id="salereg_cust_conf_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_cust_conf_status" name="salereg_cust_conf_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_cust_conf_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_cust_conf_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Invoice</label>
        <input type="text" id="salereg_invoice" name="salereg_invoice" class="form-control" placeholder="Invoice" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_invoice : '';?>">
        <span class="error_message" id="salereg_invoice_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_invoice_status" name="salereg_invoice_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_invoice_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_invoice_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Verify Item with invoice and PO</label>
        <input type="text" id="salereg_inv_po" name="salereg_inv_po" class="form-control" placeholder="Verify Item with invoice and PO" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_inv_po : '';?>">
        <span class="error_message" id="salereg_inv_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_inv_po_status" nmae="salereg_inv_po_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_inv_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_inv_po_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Eway Bill</label>
        <input type="text" id="salereg_eway" name="salereg_eway" class="form-control" placeholder="Eway Bill" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_eway : '';?>">
        <span class="error_message" id="salereg_eway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_eway_status" name="salereg_eway_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_eway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_eway_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Despatch</label>
        <input type="text" id="salereg_despatch" name="salereg_despatch" class="form-control" placeholder="Despatch" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_despatch : '';?>">
        <span class="error_message" id="salereg_despatch_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_despatch_status" name="salereg_despatch_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_despatch_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_despatch_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Delivery</label>
        <input type="text" id="salereg_delivery" name="salereg_delivery" class="form-control" placeholder="Delivery" value="<?php echo (count($registered_sales) > 0) ? $registered_sales[0]->salereg_delivery : '';?>">
        <span class="error_message" id="salereg_delivery_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="salereg_delivery_status" name="salereg_delivery_status" <?php echo (count($registered_sales) > 0 && $registered_sales[0]->salereg_delivery_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="salereg_delivery_status">
      Verify
        </label>
      </div>

      </div> -->


<div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_register_sale()">Save</button>
                      </div>

    </form>
       

  </div>
        

 <div class="localsaleunregsec"   <?php if($order[0]->checklist=="Local Sales to unRegistered Dealer"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

<form action ="" method="post" name="unregister_sale_form" id="unregister_sale_form"> 
<input type="hidden" name="unregister_order_id" id="unregister_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="unregister_id" id="unregister_id" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->id : '';?>">
 


        <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Local Sales to unRegistered Dealer")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif



  <!-- <div class="form-group col-md-3">
        <label>PO</label>
        <input type="text" id="saleunreg_po" name="saleunreg_po" class="form-control" placeholder="PO" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_po : '';?>">
        <span class="error_message" id="saleunreg_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_po_status" name="saleunreg_po_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_po_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Configuration</label>
        <input type="text" id="saleunreg_config" name="saleunreg_config" class="form-control" placeholder="Configuration" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_config : '';?>">
        <span class="error_message" id="saleunreg_config_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_config_status" name="saleunreg_config_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_config_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_config_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Payment terms </label>
        <input type="text" id="saleunreg_payment_term" name="saleunreg_payment_term" class="form-control" placeholder="Payment terms " value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_payment_term : '';?>">
        <span class="error_message" id="saleunreg_payment_term_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_payment_term_status" name="saleunreg_payment_term_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_payment_term_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_payment_term_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Delivery terms </label>
        <input type="text" id="saleunreg_delivery_terms" name="saleunreg_delivery_terms" class="form-control" placeholder="Delivery terms " value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_delivery_terms : '';?>">
        <span class="error_message" id="saleunreg_delivery_terms_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_delivery_terms_status" name="saleunreg_delivery_terms_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_delivery_terms_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_delivery_terms_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Flood Cess</label>
        <input type="text" id="saleunreg_flood" name="saleunreg_flood" class="form-control" placeholder="Flood Cess" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_flood : '';?>">
        <span class="error_message" id="saleunreg_flood_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_flood_status" name="saleunreg_flood_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_flood_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_flood_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Technical Approval</label>
        <input type="text" id="saleunreg_technical" name="saleunreg_technical" class="form-control" placeholder="Technical Approval" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_technical : '';?>">
        <span class="error_message" id="saleunreg_technical_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_technical_status" name="saleunreg_technical_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_technical_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_technical_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Financial Approval</label>
        <input type="text" id="saleunreg_financial" name="saleunreg_financial" class="form-control" placeholder="Financial Approval" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_financial : '';?>">
        <span class="error_message" id="saleunreg_financial_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_financial_status" name="saleunreg_financial_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_financial_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_financial_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Stock availability</label>
        <input type="text" id="saleunreg_stock_avil" name="saleunreg_stock_avil" class="form-control" placeholder="Stock availability" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_stock_avil : '';?>">
        <span class="error_message" id="saleunreg_stock_avil_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_stock_avil_status" name="saleunreg_stock_avil_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_stock_avil_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_stock_avil_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Customer Confirmation</label>
        <input type="text" id="saleunreg_cust_conf" name="saleunreg_cust_conf" class="form-control" placeholder="Customer Confirmation" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_cust_conf : '';?>">
        <span class="error_message" id="saleunreg_cust_conf_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_cust_conf_status" name="saleunreg_cust_conf_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_cust_conf_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_cust_conf_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Invoice</label>
        <input type="text" id="saleunreg_invoice" name="saleunreg_invoice" class="form-control" placeholder="Invoice" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_invoice : '';?>">
        <span class="error_message" id="saleunreg_invoice_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_invoice_status" name="saleunreg_invoice_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_invoice_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_invoice_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Verify Item with invoice and PO</label>
        <input type="text" id="saleunreg_invo_po" name="saleunreg_invo_po" class="form-control" placeholder="Verify Item with invoice and PO" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_invo_po : '';?>">
        <span class="error_message" id="saleunreg_invo_po_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_invo_po_status" name="saleunreg_invo_po_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_invo_po_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_invo_po_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Eway Bill</label>
        <input type="text" id="saleunreg_eway" name="saleunreg_eway" class="form-control" placeholder="Eway Bill" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_eway : '';?>">
        <span class="error_message" id="saleunreg_eway_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_eway_status" name="saleunreg_eway_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_eway_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_eway_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Despatch</label>
        <input type="text" id="saleunreg_dispatch" name="saleunreg_dispatch" class="form-control" placeholder="Despatch" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_dispatch : '';?>">
        <span class="error_message" id="saleunreg_dispatch_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_dispatch_status" name="saleunreg_dispatch_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_dispatch_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_dispatch_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Delivery</label>
        <input type="text" id="saleunreg_delivery" name="saleunreg_delivery" class="form-control" placeholder="Delivery" value="<?php echo (count($unregistered_sales) > 0) ? $unregistered_sales[0]->saleunreg_delivery : '';?>">
        <span class="error_message" id="saleunreg_delivery_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="saleunreg_delivery_status" name="saleunreg_delivery_status" <?php echo (count($unregistered_sales) > 0 && $unregistered_sales[0]->saleunreg_delivery_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="saleunreg_delivery_status">
      Verify
        </label>
      </div>

      </div> -->

      <div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_unregister_sale()">Save</button>
                      </div>

    </form>
       


</div>


 <div class="tendersec" <?php if($order[0]->checklist=="Tender"){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

<form action ="" method="post" name="tender_sale_form" id="tender_sale_form"> 
<input type="hidden" name="tender_order_id" id="tender_order_id" value="{{$order[0]->order_no}}">
<input type="hidden" name="tender_id" id="tender_id" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->id : '';?>">
 <?php
$array_options=array();
 ?>
@if(sizeof($order_sales_option)>0)
             
             @foreach($order_sales_option as $tenderval)
              @if($tenderval->sale_type=="Tender")
           
            @if($tenderval->subname!='')
            <?php

            $status=$tenderval->subname_val.'_status';
             $name=$tenderval->subname_val;
         // print_r($array_options);
             if (!in_array($tenderval->name, $array_options))
             {
               ?>
                <div class="form-group col-md-12">
            <h3>{{$tenderval->name}}</h3>
            </div>
               <?php
             }
             $array_options[]=$tenderval->name;
            ?>
           

            <div class="form-group col-md-3">
                <label>{{$tenderval->subname}} </label>
                <input type="text" id="{{$tenderval->subname_val}}" name="{{$tenderval->subname_val}}" class="form-control" placeholder="{{$tenderval->subname}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->subname_val}}_status" name="{{$tenderval->subname_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->subname_val}}_status">
              Verify
                </label>
              </div>
        
              </div>

            @endif

            @if($tenderval->subname=='')
            <?php
            $status=$tenderval->name_val.'_status';
             $name=$tenderval->name_val;
        
            ?>
             <div class="form-group col-md-3">
                <label>{{$tenderval->name}} </label>
                <input type="text" id="{{$tenderval->name_val}}" name="{{$tenderval->name_val}}" class="form-control" placeholder="{{$tenderval->name}}" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0][$name] : '';?>">
                <span class="error_message" id="tender_document_message" style="display: none">Field is required</span>
             
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{$tenderval->name_val}}_status" name="{{$tenderval->name_val}}_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0][$status]=="Y") ? 'checked' : '';?>>
                <label class="form-check-label" for="{{$tenderval->name_val}}_status">
              Verify
                </label>
              </div>
        
              </div>
              @endif


              @endif
        @endforeach
        @endif


       <!-- <div class="form-group col-md-3">
        <label>Terms and conditions and price expected from customers</label>
        <input type="text" id="tender_terms" name="tender_terms" class="form-control" placeholder="Terms and conditions and price expected from customers" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_terms : '';?>">
        <span class="error_message" id="tender_terms_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_terms_status" name="tender_terms_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_terms_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_terms_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Match specification / configuration</label>
        <input type="text" id="tender_spec_conf" name="tender_spec_conf" class="form-control" placeholder="Match specification / configuration" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_spec_conf : '';?>">
        <span class="error_message" id="tender_spec_conf_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_spec_conf_status" name="tender_spec_conf_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_spec_conf_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_spec_conf_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Authorisation from the manufacturer</label>
        <input type="text" id="tender_auth_manu" name="tender_auth_manu" class="form-control" placeholder="Authorisation from the manufacturer" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_auth_manu : '';?>">
        <span class="error_message" id="tender_auth_manu_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_auth_manu_status" name="tender_auth_manu_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_auth_manu_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_auth_manu_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Periodic maintenance / service chart</label>
        <input type="text" id="tender_main_serv" name="tender_main_serv" class="form-control" placeholder="Periodic maintenance / service chart" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_main_serv : '';?>">
        <span class="error_message" id="tender_main_serv_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_main_serv_status" name="tender_main_serv_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_main_serv_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_main_serv_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Price from supplier</label>
        <input type="text" id="tender_price_supp" name="tender_price_supp" class="form-control" placeholder="Price from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_price_supp : '';?>">
        <span class="error_message" id="tender_price_supp_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_price_supp_status" name="tender_price_supp_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_price_supp_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_price_supp_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Spare price list from supplier</label>
        <input type="text" id="tender_spare_price" name="tender_spare_price" class="form-control" placeholder="Spare price list from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_spare_price : '';?>">
        <span class="error_message" id="tender_spare_price_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_spare_price_status" name="tender_spare_price_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_spare_price_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_spare_price_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Product certification from supplier</label>
        <input type="text" id="tender_prod_certi" name="tender_prod_certi" class="form-control" placeholder="Product certification from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_prod_certi : '';?>">
        <span class="error_message" id="tender_prod_certi_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_prod_certi_status" name="tender_prod_certi_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_prod_certi_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_prod_certi_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Product literature from supplier</label>
        <input type="text" id="tender_literature" name="tender_literature" class="form-control" placeholder="Product literature from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_literature : '';?>">
        <span class="error_message" id="tender_literature_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_literature_status" name="tender_literature_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_literature_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_literature_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Compliance statement from supplier</label>
        <input type="text" id="tender_state_supplier" name="tender_state_supplier" class="form-control" placeholder="Compliance statement from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_state_supplier : '';?>">
        <span class="error_message" id="tender_state_supplier_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_state_supplier_status" name="tender_state_supplier_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_state_supplier_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="gridCheck">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Warranty terms confirmation from supplier</label>
        <input type="text" id="tender_warrenty" name="tender_warrenty" class="form-control" placeholder="Warranty terms confirmation from supplier" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_warrenty : '';?>">
        <span class="error_message" id="tender_warrenty_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_warrenty_status" name="tender_warrenty_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_warrenty_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_warrenty_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>EMD and Tender fee </label>
        <input type="text" id="tender_emd" name="tender_emd" class="form-control" placeholder="EMD and Tender fee " value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_emd : '';?>">
        <span class="error_message" id="tender_emd_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_emd_status" name="tender_emd_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_emd_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_emd_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Price bid preparation</label>
        <input type="text" id="tender_price_bid" name="tender_price_bid" class="form-control" placeholder="Price bid preparation" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_price_bid : '';?>">
        <span class="error_message" id="tender_price_bid_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_price_bid_status" name="tender_price_bid_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_price_bid_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_price_bid_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Documents upload / Send</label>
        <input type="text" id="tender_upload_send" name="tender_upload_send" class="form-control" placeholder="Documents upload / Send" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_upload_send : '';?>">
        <span class="error_message" id="tender_upload_send_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_upload_send_status" name="tender_upload_send_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_upload_send_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_upload_send_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Technical / Demo</label>
        <input type="text" id="tender_tech_demo" name="tender_tech_demo" class="form-control" placeholder="Technical / Demo" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_tech_demo : '';?>">
        <span class="error_message" id="tender_tech_demo_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_tech_demo_status" name="tender_tech_demo_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_tech_demo_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_tech_demo_status">
      Verify
        </label>
      </div>

      </div>

       <div class="form-group col-md-3">
        <label>Technical Bid opening</label>
        <input type="text" id="tender_tech_bid" name="tender_tech_bid" class="form-control" placeholder="Technical Bid opening" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_tech_bid : '';?>">
        <span class="error_message" id="tender_tech_bid_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_tech_bid_status" name="tender_tech_bid_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_tech_bid_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_tech_bid_status">
      Verify
        </label>
      </div>

      </div>

      <div class="form-group col-md-3">
        <label>Financial Bid opening</label>
        <input type="text" id="tender_financial_bid" name="tender_financial_bid" class="form-control" placeholder="Financial Bid opening" value="<?php echo (count($tender_sales) > 0) ? $tender_sales[0]->tender_financial_bid : '';?>">
        <span class="error_message" id="tender_financial_bid_message" style="display: none">Field is required</span>
      
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tender_financial_bid_status" name="tender_financial_bid_status" <?php echo (count($tender_sales) > 0 && $tender_sales[0]->tender_financial_bid_status=="Y") ? 'checked' : '';?>>
        <label class="form-check-label" for="tender_financial_bid_status">
      Verify
        </label>
      </div>

      </div> -->

       <div class="box-footer col-md-12">
                         <button type="button" class="btn btn-primary"  onclick="add_tender_sale()">Save</button>
                      </div>

    </form>


</div>




</div>


<div class="box-body documents" style="display:none;">
<h3>Documents</h3>

</div>

<div class="box-body comtaskvisit" style="display:none;">
<h3>Comment/Task/Visit</h3>

</div>

<div class="box-body invoices" style="display:none;">
<h3>Invoices</h3>

</div>

<div class="box-body dispatch" style="display:none;">
<h3>Dispatch/Return</h3>

</div>

<div class="box-body payments" style="display:none;">
<h3>Payments</h3>

</div>

          </div>

          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />



  <script type="text/javascript">

$(document).ready(function() {

$('#govt_sales').multiselect();
$('#export_sales').multiselect();

});

  function service_change(service_val){
    if(service_val=="edit_order")
    {
      $(".edit_order").show();
      $(".po_details").hide();
      $(".comtaskvisit").hide();
      $(".invoices").hide();
      $(".dispatch").hide();
      $(".payments").hide();
      $(".documents").hide();
    }
    if(service_val=="po_details")
    {
      
      $(".edit_order").hide();
      $(".po_details").show();
      $(".comtaskvisit").hide();
      $(".invoices").hide();
      $(".dispatch").hide();
      $(".payments").hide();
      $(".documents").hide();
    }
    if(service_val=="documents")
    {
      $(".documents").show();
      $(".edit_order").hide();
      $(".po_details").hide();
      $(".comtaskvisit").hide();
      $(".invoices").hide();
      $(".dispatch").hide();
      $(".payments").hide();
    }

    if(service_val=="comtaskvisit")
    {
     
      $(".edit_order").hide();
      $(".po_details").hide();
      $(".comtaskvisit").show();
      $(".invoices").hide();
      $(".dispatch").hide();
      $(".payments").hide();
      $(".documents").hide();
    }
    if(service_val=="invoices")
    {
      $(".edit_order").hide();
      $(".po_details").hide();
      $(".comtaskvisit").hide();
      $(".invoices").show();
      $(".dispatch").hide();
      $(".payments").hide();
      $(".documents").hide();
    }
    if(service_val=="dispatch")
    {
      $(".edit_order").hide();
      $(".po_details").hide();
      $(".comtaskvisit").hide();
      $(".invoices").hide();
      $(".dispatch").show();
      $(".payments").hide();
      $(".documents").hide();
    }
    if(service_val=="payments")
    {
      $(".edit_order").hide();
      $(".po_details").hide();
      $(".comtaskvisit").hide();
      $(".invoices").hide();
      $(".dispatch").hide();
      $(".payments").show();
      $(".documents").hide();
    }
  }

function add_gov_sale()
{
  var url   = APP_URL+'/staff/save_order_gov_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#govt_sale_form").serialize(),
         success:function()
         {
           alert('Saved Successfully');
         }
         
        });
}
  
function add_export_sale()
{
  var url   = APP_URL+'/staff/save_order_export_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#export_sale_form").serialize(),
         success:function()
         {
          alert('Saved Successfully');
         }
         
        });
}

function add_purchase_sale()
{
  var url   = APP_URL+'/staff/save_order_purchase_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#purchase_sale_form").serialize(),
         success:function()
         {
          alert('Saved Successfully');
         }
         
        });
}

function add_register_sale()
{
  var url   = APP_URL+'/staff/save_order_registered_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#register_sale_form").serialize(),
         success:function()
         {
          alert('Saved Successfully');
         }
         
        });
}

function add_unregister_sale()
{
  var url   = APP_URL+'/staff/save_order_unregistered_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#unregister_sale_form").serialize(),
         success:function()
         {
          alert('Saved Successfully');
         }
         
        });
}

function add_tender_sale()
{
  var url   = APP_URL+'/staff/save_order_tender_sale';
  $.ajax({
         url:url,
         method:'POST',
         data:$("#tender_sale_form").serialize(),
         success:function()
         {
          alert('Saved Successfully');
         }
         
        });
}


  $(function() {

    $('.del').click(function(){
      if(confirm("Are you sure you want to delete this?"))
      {
        var id    = $(this).attr('data-id');
        var url   = APP_URL+'/staff/delete_order_product';
        $.ajax({
         url:url,
         method:'POST',
         data:{id:id},
         success:function()
         {
           $('#trr_'+id+'').css('background-color', '#ccc');
           $('#trr_'+id+'').fadeOut('slow');
         }
         
        });
      }
      else
      {
        return false;
      }
    });

    $('.upd').click(function(){

      var id          = $(this).attr('data-id');
      var order_no    = $(this).attr('data-order');
      var url         = APP_URL+'/staff/update_order';
      var product_id  = $('#product_id'+id).val();
      var quantity    = $('#quantity'+id).val();
      var sale_amount = parseInt($('#sale_amount'+id).val());
      var amount      = $('#amount'+id).val();

      var net_amt     = quantity * sale_amount;
      $('#amount'+id).val(net_amt);
      var flag        = 1;
      var maxs        = '';
      var mins        = '';

      var url1        = APP_URL+'/staff/get_product_all_details';
      $.ajax({
            type: "POST",
            cache: false,
            url: url1,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
               var proObj   = JSON.parse(data);
               for (var i = 0; i < proObj.length; i++) {
                 var amount     = proObj[i]["unit_price"];
                 maxs           = proObj[i]["max_sale_amount"];
                 mins           = proObj[i]["min_sale_amount"]; 
               }

              // alert("sale" +sale_amount+ "min" +mins+ "max" +maxs);
              
              if(maxs!=0 && sale_amount>maxs) //
              {
                
                flag = 0;
                $("#sale_message").show();
                
              }
              if(mins!=0 && sale_amount<mins) //
              {

                flag = 0;
                $("#sale_message").show();
              }
              
              if(flag==1)
              {
                $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    product_id : product_id,
                    quantity : quantity,
                    sale_amount : sale_amount,
                    amount : net_amt,
                    order_no : order_no,
                    id : id,
                  },
                  success: function (data1)
                  { 
                     $('#amount'+id).val(net_amt);
                     $('#sale_amount'+id).val(sale_amount);
                     $("#quantity").val(quantity);
                  }
                });    
              }
               location.reload(true);          
            }

          });   
    });

  });
</script>
   
  
@endsection
