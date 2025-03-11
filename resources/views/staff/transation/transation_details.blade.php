@extends('staff/layouts.app')
@section('title', 'Transaction Detail')
@section('content')
<section class="content-header">
      <h1>
       Transaction Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.transation.index')}}">Manage Transaction</a></li>
        <li class="active">Transaction Detail</li>
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

            <form role="form" name="frm_brand" id="frm_brand" method="post" action="{{ route('staff.transation.update', $transation->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}

                <div class="box-body">


               
              <div class=" box-body row"> 
                <div class="col-md-12 sales-table">

                <p><b>Customer Name:{{$transation->getcustomer->business_name}}</b></p>

                <br>
               <table id="cmsTable" class="table table-bordered table-striped data- hideform" >
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

                    <td><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
              
              <td><input type="text" readonly="true"  value="{{$values->quantity}}" id="qn_{{$values->product_id}}" name="quantity[]" class="quantity form-control"  style="width:40px;"></td>
              
               <td><input type="text"   readonly="true"   name="sale_amount[]" value="{{$values->sale_amount}}" id="sa_{{$values->product_id}}" onchange="change_sale_amt(this.value,{{$values->product_id}})" class="sale_amt form-control" data-id="{{$values->product_id}}" style="width:60px;">
               <td><input type="text"   readonly="true"   value="{{$values->hsn}}" id="hsn_{{$values->product_id}}"  class="hsn form-control" name="hsn[]" data-id="{{$values->product_id}}" style="width:70px;">
               <td><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->cgst}}" id="cgst_{{$values->product_id}}"  class="cgst form-control" name="cgst[]" data-id="{{$values->product_id}}" style="width:40px;"><p>({{$cgst}}%)</p></span>
               <td><span class="per_sec_tran"><input type="text"  readonly="true"   value="{{$values->sgst}}" id="sgst_{{$values->product_id}}}"  class="sgst form-control" name="sgst[]" data-id="{{$values->product_id}}" style="width:40px;"><p>({{$sgst}}%)</p></span>
               <td><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->igst}}" id="igst_{{$values->product_id}}"  class="igst form-control" name="igst[]" data-id="{{$values->product_id}}" style="width:40px;"><p>({{$igst}}%)</p></span>
               <td><span class="per_sec_tran"><input type="text"   readonly="true"   value="{{$values->cess}}" id="cess_{{$values->product_id}}"  class="cess form-control" name="cess[]" data-id="{{$values->product_id}}" style="width:40px;"><p>({{$cess}}%)</p></span>
               
               <td><input type="text"   readonly="true"   value="{{$values->msp}}" id="msp_{{$values->product_id}}"  class="msp form-control" name="msp[]" data-id="{{$values->product_id}}" style="width:40px;">

               <div style="display:none;" class="error_message error_sale_{{$values->product_id}}"></div></td>
               <td><input type="text"   readonly="true"   value="{{$values->surplus_amt}}" id="surplus_amt_{{$values->product_id}}"  class="surplus_amt form-control" name="surplus_amt[]" data-id="{{$values->product_id}}" style="width:40px;">
               </td>
               
               <td><input type="text"   readonly="true"   value="{{$values->amt}}" id="am_{{$values->product_id}}" class="amt form-control" name="amt[]" data-id="{{$values->product_id}}" readonly></td>
               
               <input type="hidden" name="product_id[]" value="{{$values->product_id}}"><input type="hidden" name="quantity[]" value="{{$values->quantity}}" class="hqn_{{$values->product_id}}">
               <input type="hidden" name="amount[]" value="{{$values->amt}}" class="hamt_{{$values->product_id}}">
               <input type="hidden" name="sale_amount[]" value="{{$values->sale_amount}}" class="hsa_{{$values->product_id}}"><input type="hidden" name="company[]" value="">
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

               <br>
               <br><br>
                  <h4>Transaction Count Details</h4>
               <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Transaction Total Count</th>
                    <th>Invoice Count</th>
                    <th>Dispatch Verified Count</th>
                    <th>Dispatch Not Verified Count</th>
                    <th>Pending Product Transaction</th>
                 
                  </tr>
                </thead>
                <tbody id="tabledata">
<?php
               if(count($transation_product)>0)
                {
                  $k=1;

                  foreach($transation_product as $values)
                  {
                    ?>
                     <tr>
                    <td>{{$k}}</td>
                    <td>{{$values->product_name}}</td>
                    <td>{{$values->quantity}}</td>
                    <td>{{ App\Transation::getinvoice_productcount($values->transation_id,$values->product_id) }}</td>
                    <td> {{ App\Transation::getdispatch_verifycount($values->transation_id,$values->product_id) }}</td>
                    <td>0</td>
                    <td>{{$values->quantity-$values->out_product_quantity}}</td>
                 
                  </tr>
                  <?php
                  $k++;
                  }
                }
                  ?>
                  </tbody>
                  </table>
<br><br>
 <h4>Transaction Sections</h4>

 <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                   
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Added Date</th>
                    <th>Approved Date</th>
                    <th>Staff Name</th>
                   
                  
                  </tr>
                </thead>
                <tbody id="tabledata">
                @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Technical Approval')
                @endphp
                <tr>
                  <td>Technical Approval</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>
                
                 @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'MSP Approval')
                @endphp
                <tr>
                  <td>MSP,Payout,otherprovisions if any</td>
                   <td>@if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
             
                 
                 </tr>
           
                 @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Financial Approval')
                @endphp
                <tr>
                  <td>Financial Approval</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 
                 </tr>
             
                </tbody>
                </table>
<br>
<br>
                <h4>Invoice</h4>
                <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Invoice Id</th>
                  <th>Created At</th>
                <th class="alignCenter rightalign">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($invoice as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="invoice">
                      
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Invoice Id" ><?php echo $product->invoice_id ?></td>
                        <td data-th="Created At" ><?php echo date("Y-m-d",strtotime($product->created_at)); ?></td>
                       <td data-th="Action "  class="alignCenter rightalign">
                        <a class="view-btn" target="_blank" id="btn_preview" href="{{url('staff/preview_invoice/'.$product->id)}}"> <img src="{{ asset('images/view.svg') }}"></a>
                        </td>
                      </tr>
                     <?php $i++ ?>
                     @endforeach

              </table>
              <br>

              <h4>Credit Note</h4>

<table id="cmsTable" class="table table-bordered table-striped data-">
  <thead>
  <tr>
   
    <th>No.</th>
    <th>Transaction</th>
    <th>Invoice</th>
    <th>Credit Date</th>

    <th class="alignCenter rightalign">Action</th>
  </tr>
  </thead>
  <tbody>
      <?php $i = 1; ?>
      @foreach ($credit as $product)
      <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="invoice">
        
          <td data-th="No." >
              <span class="slNo">{{$i}} </span>
          </td>
          <td data-th="Transaction" >TRANS_<?php echo $product->transaction_id ?></td>
          <td data-th="Invoice" >INVOICE_<?php echo $product->invoice_id ?></td>
        
          <td data-th="Credit Date" ><?php echo $product->credit_date ?></td>
       

          <td data-th="Action "  class="alignCenter rightalign">
       
              <!-- <a class="btn btn-primary btn-xs" href="" title="Send">Send</span></a> -->
              <a class="view-btn" target="_blank" id="btn_preview" href="{{url('admin/preview_credit/'.$product->id)}}"> <img src="{{ asset('images/view.svg') }}"></a> 

          </td>
        </tr>


         <?php $i++ ?>
       @endforeach

 

</table>

<br>
                <h4>Dispatch</h4>
                <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                
                  <th>Dispatch Id</th>
                  <th>Created At</th>
                <th class="alignCenter rightalign">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($dispatch as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="invoice">
                      
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Dispatch Id" >DISPATCH_<?php echo $product->id ?></td>
                        <td data-th="Created At" ><?php echo date("Y-m-d",strtotime($product->created_at)); ?></td>
                       <td data-th="Action "  class="alignCenter rightalign">
                      
                        <a href="{{ route('staff.dispatch_verify_view',$product->transation_id) }}" target="_blank" > View</a>
                        </td>
                      </tr>
                     <?php $i++ ?>
                     @endforeach

              </table>


              <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                   
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Added Date</th>
                    <th>Approved Date</th>
                    <th>Staff Name</th>
                   
                  
                  </tr>
                </thead>
                <tbody id="tabledata">

              <tr>
              @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Delivery Invoice')
                @endphp
                  <td>Delivery Invoice</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>

                 <tr>
              @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'User Confirmation')
                @endphp
                  <td>User Confirmation</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>

                 <tr>
              @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Department Confirmation')
                @endphp
                  <td>Department Confirmation</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>

                 <tr>
              @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Finance Confirmation')
                @endphp
                  <td>Finance Confirmation</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>

                 <tr>
              @php 
                $tech_details=App\Transation::getstaff_update_details($values->transation_id,'Payment Confirmation')
                @endphp
                  <td>Payment Confirmation</td>
                   <td> @if($tech_details) {{$tech_details->current_status}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->added_date}} @endif</td>
                   <td>@if($tech_details) {{$tech_details->approved_date}} @endif</td>
                   <td>@if($tech_details)
                   @if($tech_details->approved_by=='1')
                    Admin
                   @endif
                   @if($tech_details->approved_by!='1')
                   {{ App\Transation::getstaff($tech_details->staff_id) }}
                   @endif
                  @endif</td>
                
                 </tr>

                 </tbody>
                 </table>


<div class="bacicdetails">

</div>


            </div>
 
</div>
</div>



          </div>

         
         
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







@endsection
@section('scripts')


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

    <script type="text/javascript">


  
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

@endsection