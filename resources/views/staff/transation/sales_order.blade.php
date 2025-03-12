@extends('staff/layouts.app')
@section('title', 'Add Transaction')
@section('content')
<section class="content-header">
      <h1>
        Add Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('transation.index')}}">Manage Transaction</a></li>
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
            <form role="form" name="frm_transation" id="frm_transation" method="post" action="{{route('transation.store')}}" enctype="multipart/form-data" autocomplete="off">
               @csrf

                <div class="box-body">

                  <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#purchase_order" data-toggle="tab">@if($type=="Sale Order") Sales order @endif
          @if($type=="Test Return") Test Return @endif
           </a></li>
		
					<li class="disabled"><a href="#technical_approval" data-toggle="tab">Technical Approval</a></li>
          <li class="disabled"><a href="#otherpro" data-toggle="tab">MSP,Payout,otherprovisions if any</a></li>
          <!-- <li class="disabled"><a href="#stock_availiability" data-toggle="tab">Stock Availiability</a></li> -->
          <li class="disabled"><a href="#financial_approval" data-toggle="tab">Financial Approval</a></li>
          <!-- <li class="disabled"><a href="#cust_conform" data-toggle="tab">Customer Confirmation on COD/Site readness</a></li> -->
        </ul>
				<div class="tab-content">
				

					<div class="tab-pane active" id="purchase_order">
            <!-- Purchase Order start  -->
         
            <div class="panel panel-default">
    <div class="panel-body ">


                  <div class="box-body row noboder-box">
                           <div class="col-md-3 col-sm-6 col-lg-3">
                           <label >
                           @if($type=="Sale Order") Sales Type @endif
          @if($type=="Test Return") Test Return Type @endif
                           </label>
                           <select class="form-control" name="sales_type" id="sales_type">
                                 <option value="">  @if($type=="Sale Order") Sales Type @endif
          @if($type=="Test Return") Test Return Type @endif</option>
                                 <option value="Existed Transaction">Existed Transaction</option>
                                 <option value="New Transaction">New Transaction</option>
                              </select>
                              <span class="error_message" id="sales_type_message" style="display: none">Field is required</span>
                           </div>

                           <div class="col-md-3 col-sm-6 col-lg-3 viewtrans_exited" style="display:none;">
                          <label>Select Transaction*</label>
                        
                          <select name="exit_trans_id" id="exit_trans_id" class="form-control selectpicker" data-live-search="true" onchange="get_value_prev_tran(this.value)">
                            <option value="">-- Select Transaction --</option>
                            <?php
foreach ($all_transaction as $item)
{
    $sel = (old('exit_trans_id') == $item->id) ? 'selected' : '';
    echo '<option value="' . $item->id . '" ' . $sel . '>Trans_' . $item->id . '</option>';
} ?>
                          </select>
                          <div class="loader_prev_trans" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                          <span class="error_message" id="exit_trans_id_message" style="display: none">Field is required</span>
                        </div>

                        <div class="col-md-3 col-sm-6 col-lg-3 viewtrans_exited" style="display:none;">
                  <label>Transaction IN/OUT*</label>
                  <select name="inandout" id="inandout" class="form-control " data-live-search="true" >
                    <option value="">-- Transaction IN/OUT--</option>
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                   </select>
                  <span class="error_message" id="inandout_message" style="display: none">Field is required</span>
                </div>

                <div class="col-md-3 col-sm-6 col-lg-3 viewtrans_exited" style="display:none;">
                  <label>Product Add Condition</label>
                  <select name="add_product_condition" id="add_product_condition" class="form-control " data-live-search="true" >
                    <option value="">-- Product Add Condition--</option>
                    <option value="Defective Product">Defective Product</option>
                    <option value="Product Wrong Case">Product Wrong Case</option>
                   </select>
                  <span class="error_message" id="add_product_condition_message" style="display: none">Field is required</span>
                </div>

                        
                  </div>

                  
                  <div class="box-body row noboder-box">            
                <div class="col-md-12 col-sm-6 col-lg-12 viewtrans_exited" style="display:none;">
                  <label>Description*</label>
                  <textarea type="comment" class="form-control" placeholder="" name="description_exit_trans" id="description_exit_trans"></textarea>
                  <span class="error_message" id="description_exit_trans_message" style="display: none">Field is required</span>
                </div>
                </div>


                <div class="box-body row noboder-box"> 

                <div class="col-md-12 col-sm-6 col-lg-12 viewtrans_form" style="display:none;">
                    <div class="radio-label"><label >Source*</label></div>
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

           </div>

           <div class="box-body row noboder-box">
           <div class="form-group  col-md-3 viewtrans_form" style="display:none;">
                    <label>Company*</label>
                    <select name="company_id" id="company_id" class="form-control">
                      <option value="">-- Select Company --</option>
                      <?php
                      foreach($company as $item) {
                        $sel = (old('company_id') == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                      } ?>
                    </select>
                    <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                    </div>
                  </div>

           <div class="box-body row noboder-box">


            <div class="col-md-3 col-sm-6 col-lg-3  viewtrans_form" style="display:none;">
                  <label>State*</label>
                  <select name="state_id" id="state_id" class="form-control selectpicker" data-live-search="true" onchange="change_state()">
                    <option value="">-- Select State --</option>
                    <?php
                    foreach ($state as $item)
                    {
                        $sel = (old('state_id') == $item->id) ? 'selected' : '';
                        echo '<option value="' . $item->id . '" ' . $sel . '>' . $item->name . '</option>';
                    } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
              </div>

                <div class="col-md-3 col-sm-6 col-lg-3 viewtrans_form" style="display:none;">
                  <label>District*</label>
                  <select name="district_id" id="district_id" class="form-control selectpicker" data-live-search="true" onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php
                    foreach ($district as $item)
                    {
                        $sel = (old('district_id') == $item->id) ? 'selected' : '';
                        echo '<option value="' . $item->id . '" ' . $sel . '>' . $item->name . '</option>';
                    } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                  <div class="loader_district_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                </div>


                

                <div class="col-md-3 col-sm-6 col-lg-3 viewtrans_form" style="display:none;">
                  <label for="name">Customer Name *</label>
                  <input type="hidden" name="user_id_hidden" id="user_id_hidden">
                  <input type="hidden" name="oppur_id" id="oppur_id">
                  <input type="hidden" name="tran_type" id="tran_type">
                  <input type="hidden" name="oppur_status" id="oppur_status">
                  <select class="form-control" name="user_id" id="user_id" onchange="change_user_id(this.value)">
                    <option value="">Customer Name</option>
                    @foreach($user->sortBy(fn($value) => strtolower($value->business_name)) as $values)
                      <option value="{{ $values->id }}">{{ $values->business_name }}</option>
                    @endforeach
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                  <div class="loader_user_id" style="display:none;">
                    <img src="{{ asset('images/wait.gif') }}">
                  </div>
                </div>
                

                </div>

                <div class="box-body row noboder-box">

                  <div class="col-md-3 col-sm-6 col-lg-3  viewtrans_form type_conf_sec" style="display:none;">
                  <label for="name">Select Option *</label>
                  <select class="form-control" name="type_conf" id="type_conf" onchange="change_conf(this.value)">
                  <option value="">Select Option</option>
                  
                  </select>
                  <span class="error_message" id="type_conf_message" style="display: none">Field is required</span>
                </div>

                    <div class="col-md-3 col-sm-6 col-lg-3 op_id" style="display:none;">
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

            

                      <div class="col-md-3 col-sm-6 col-lg-3 product_id" style="display:none;">
                        <label>Product*</label>
                        <select name="productid" id="product_id" class="form-control">
                          <option value="">-- Select Product --</option>
                          <?php
                          foreach ($products as $item)
                          {
                              echo '<option value="' . $item->id . '" >' . $item->name . '</option>';
                          } ?>
                        </select>
                        <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                      </div>
                   <div class="col-md-4 col-sm-6 col-lg-4 add_oppur_prod" style="display: none">
                <button type="button" class="add-button "  onclick="add_oppur_prod()">Add</button>
                      <div class="loader" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </div>

              </div>
               <div class="box-body row cmsTable" style="display:none;">

               <table id="cmsTable" class="table table-bordered table-striped data- hideform" style="display:none;">
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
                    <tr  data-from ="staffquote">
                       <td colspan="4" class="noresult">No result</td>
                    </tr>
               </table>
               </div>


</div>
</div>

                 

                  <div class="box-footer col-md-12">
                  
                  <span class="error_message" id="product_error" style="display: none">Product not added</span>
                <button type="button" class="mdm-btn-line submit-btn"  onclick="validate_from()">Submit</button>
                <!--  -->
                <button type="button" class="mdm-btn-line cancel-btn" onClick="window.location.href='{{route('transation.index')}}'">Cancel</button>
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
        <form name="add_address" id="add_address" method="post" enctype="multipart/form-data" autocomplete="off">
        
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
$sel = ($values->country_id == $values_con->id) ? 'selected' : '';
echo '<option value="' . $values_con->id . '" ' . $sel . '>' . $values_con->name . '</option>';
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

    function get_value_prev_tran(trans_id)
    {
      $(".loader_prev_trans").show();
     
     var url = APP_URL+'/staff/get_value_prev_transaction';
      $.ajax({
              type: "POST",
              cache: false,
              url: url,
              data:{
                trans_id: trans_id,
              },
              success: function (data)
              {  
                var Obj = JSON.parse(data);
               $("#collect_date").val(Obj[0]["collect_date"]);
               $("#user_address").val(Obj[0]["user_address"]);
               $("#user_shipping").val(Obj[0]["user_shipping"]);
               $("#contact_phone").val(Obj[0]["contact_phone"]);
               $("#contact_mail").val(Obj[0]["contact_mail"]);
               $("#gst").val(Obj[0]["gst"]);
               $("#po").val(Obj[0]["po"]);
               $("#po_date").val(Obj[0]["po_date"]);
               $("#stan_warrenty").val(Obj[0]["stan_warrenty"]);
               $("#add_warrenty").val(Obj[0]["add_warrenty"]);
               $("#payment_terms").val(Obj[0]["payment_terms"]);
               $("#del_terms").val(Obj[0]["del_terms"]);
               $("#other_terms").val(Obj[0]["other_terms"]);
               $("#expect_date").val(Obj[0]["expect_date"]);
               $("#owner").val(Obj[0]["owner"]);
               $("#second_owner").val(Obj[0]["second_owner"]);
               $("#mode_dispatch").val(Obj[0]["mode_dispatch"]);
               $("#contact_id").val(Obj[0]["contact_id"]);
               $("#attach_gst").val(Obj[0]["attach_gst"]);

               $("#user_id").val(Obj[0]["user_id"]);
               $("#state_id").val(Obj[0]["state_id"]);
               $("#district_id").val(Obj[0]["district_id"]);

              
            
               
           /*    $('#user_id').selectpicker('refresh');
               $('#state_id').selectpicker('refresh');
               $('#district_id').selectpicker('refresh');*/
             /*  
               designation
               department_id
                
               */
                $(".loader_prev_trans").hide();
              }
            });
    }



    function validate_from()
      {
        var state_id=$("#state_id").val();
        var district_id=$("#district_id").val();
        var user_id=$("#user_id").val();
        var type_conf=$("#type_conf").val();
        var sales_type=$("#sales_type").val();
        if(sales_type=="")
        {
          $("#sales_type_message").show();
        }
        else{
          $("#sales_type_message").hide();
        }
        var flag=0;
        if(sales_type=="Existed Transaction")
        {
          var exit_trans_id=$("#exit_trans_id").val();
          var inandout=$("#inandout").val();
          var description_exit_trans=$("#description_exit_trans").val();
          if(exit_trans_id=="")
          {flag=1;
            $("#exit_trans_id_message").show();
          }
          else{flag=0;
            $("#exit_trans_id_message").hide();
          }
          if(inandout=="")
          {flag=1;
            $("#inandout_message").show();
          }
          else{flag=0;
            $("#inandout_message").hide();
          }
          if(description_exit_trans=="")
          {flag=1;
            $("#description_exit_trans_message").show();
          }
          else{flag=0;
            $("#description_exit_trans_message").hide();
          }

        }
        else{
          flag=0;
        }
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
 
        if(myTotal==0 && sales_type!='')
        {
          $("#product_error").show();
        }
        else{
          $("#product_error").hide();
        }

        if(state_id!='' && type_conf!='' && user_id!='' &&  district_id!='' && myTotal>0 && sales_type!='' && flag==0)
        {
          $("#state_id").removeAttr("disabled");
        $("#district_id").removeAttr("disabled");
        $("#user_id").removeAttr("disabled");
        
        $('#collect_date').removeAttr("disabled");
        $('#contact_id').removeAttr("disabled");
        $('#designation').removeAttr("disabled");
        $('#department_id').removeAttr("disabled");
        $('#owner').removeAttr("disabled");
        $('#second_owner').removeAttr("disabled");
        $('#po_date').removeAttr("disabled");
        $('#mode_dispatch').removeAttr("disabled");

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
      console.log(tran_type+'--'+user_id)
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
                else if(typpes=="Product"){ 
                  
                  $("#product_id").val('');
                            $("#product_id").html('');
                  var url = APP_URL+'/staff/get_all_product';
                  $.ajax({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            user_id: user_id,
                          },
                          success: function (data)
                          {  

                          var  html_contact= "<option value=''>---Select Product---</option>"             
      
                  $.each( data.products, function( key, value ) {
                  
                      html_contact +='<option value="'+value.id+'">'+value.name+'</option>';
                   
                    
                  });

                  console.log('option'+html_contact)
                  $("#product_id").val(html_contact);
                            $("#product_id").html(html_contact);
              $('#product_id').selectpicker('refresh');
                     
                          }

                        });

                  $(".op_id_date").hide();
                  $(".op_id").hide();
                  $(".product_id").show();
                    $(".add_oppur_prod").show();
                  
                }
                else if(typpes=="Test Return"){

                  /************* */
                  $("#product_id").val('');
                            $("#product_id").html('');
                  var url = APP_URL+'/staff/get_test_retun_product';
                  $.ajax({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            user_id: user_id,
                          },
                          success: function (data)
                          {  

                          var  html_contact= "<option value=''>---Select Product---</option>"             
      
                  $.each( data.products, function( key, value ) {
                    if(!jQuery.isEmptyObject(value.service_part_product))
                    {
                      html_contact +='<option value="'+value.product_id+'">'+value.service_part_product.name+'</option>';
                    }
                    
                  });

                  console.log('option'+html_contact)
                  $("#product_id").val(html_contact);
                            $("#product_id").html(html_contact);
              $('#product_id').selectpicker('refresh');
                     
                          }

                        });
                  /*********** */
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
    $('#exit_trans_id').select2();
    $('#state_id').select2();
    $('#district_id').select2();
 
 function change_user_id(user_id){
$(".loader_user_id").show();
$("#user_id_hidden").val(user_id);
$(".shiplink").show();
$("#product_id").val('');
var type="{{$type}}";

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
              if(type=="Test Return")
            {
              $("#type_conf").html('<option value="">Select Option</option><option value="Opportunity">Opportunity</option><option value="Product">Product</option><option value="Test Return">Test Return</option> ')
            }else{
              $("#type_conf").html('<option value="">Select Option</option><option value="Opportunity">Opportunity</option><option value="Product">Product</option> ')
            }
             
            }
            else{

              if(type=="Test Return")
            {
              $("#type_conf").html('<option value="">Select Option</option><option value="Product">Product</option><option value="Test Return">Test Return</option>')
            }else{
              $("#type_conf").html('<option value="">Select Option</option><option value="Product">Product</option>')
            }

              
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

            if(type=="Test Return")
            {
              
              $(".type_conf_sec").hide();
              $("#type_conf").val('Test Return');
              change_conf('Test Return')
            }

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
        $("#product_error").hide();
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
         $(".cmsTable").show();
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
               quantity    = proObj[i]["quantity"];;
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

                          htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td data-th=""><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
                         
                          htmlscontent += '<td data-th="No."><input type="text" value="'+quantity+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
                        
                          '<td data-th="Name"><input type="text" value="'+sale_amount+'" name="sale_amount[]" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+

                          '<td data-th="Qty"><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'" name="hsn[]"  class="hsn" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
                          '<td data-th="Unit Price"><span class="per_sec_tran"><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+cgst_per+'%)</p></span>'+
                          '<td data-th="HSN"><span class="per_sec_tran"><input type="text" readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+sgst_per+'%)</p></span>'+
                          '<td data-th="CGST"><span class="per_sec_tran"><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" ><p>('+igst_per+'%)</p></span>'+
                          '<td data-th="SGST"><span class="per_sec_tran"><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" ><p>('+cess_per+'%)</p></span>';
                         if(proObj[i]["msp"]>0)
                         {
                          htmlscontent +='<td data-th="IGST"><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                         }else{
                          htmlscontent +='<td data-th="Cess"><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                         }
                         
                          
                         htmlscontent +='<td data-th="MSP"><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'" data-val="'+diffe.toFixed(2)+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="delete-btn" onclick="deletepro('+proObj[i]["id"]+',1)" data-id="'+proObj[i]["id"]+'"  title="Delete"><img src="{{ asset('images/delete.svg') }}"></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"><input type="hidden" name="tax_percentage[]" value="'+proObj[i]["tax_percentage"]+'"></tr>';
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
        $(".cmsTable").show();
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
                    if(!jQuery.isEmptyObject(proObj_msp[0])){
                      sale_amount =proObj_msp[0]["cost"];
                    }
                    else{
                      sale_amount =0;
                    }
                   
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                
                  amt = quantity * sale_amount;
                //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
                if(!jQuery.isEmptyObject(proObj_msp[0])){
                  var tax=proObj_msp[0]["tax_per"];
                }else{
                  var tax=0;
                }
                  
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
                  if(!jQuery.isEmptyObject(proObj_msp[0])){
                  var diffe=sale_amount-proObj_msp[0]["pro_msp"];
                  }
                  else{
                    var diffe=sale_amount-0;
                  }
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
              
              htmlscontent += '<td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" name="quantity[]" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
            
              '<td><input type="text" name="sale_amount[]" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">';
              if(!jQuery.isEmptyObject(proObj_msp[0]))
                {
              htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'"  class="hsn" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">';
                }
                else{
                htmlscontent +='<td><input type="text" readonly="true" value="" id="hsn_'+proObj[i]["id"]+'"  class="hsn" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">';
                }
                htmlscontent +='<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+cgst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text"  readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" ><p>('+sgst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" ><p>('+igst_per+'%)</p></span>'+
               '<td><span class="per_sec_tran"><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" ><p>('+cess_per+'%)</p></span>';
               
               if(!jQuery.isEmptyObject(proObj_msp[0]))
                {
                htmlscontent +='<td><input type="text" readonly="true" value="'+proObj_msp[0]["pro_msp"]+'" data-val="'+proObj_msp[0]["pro_msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                }else{
                htmlscontent +='<td><input type="text" readonly="true" value="0" data-val="0"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                }

               htmlscontent +=
               '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" data-val="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="delete-btn" onclick="deletepro('+proObj[i]["id"]+',0)" data-id="'+proObj[i]["id"]+'"  title="Delete"><img src="{{ asset('images/delete.svg') }}"></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"><input type="hidden" name="tax_percentage[]" value="'+proObj[i]["tax_percentage"]+'"></tr>';
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
      
         if(type_conf!='Test Return')
         {
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
      if (confirm("Are you sure you want to delete this row?")) {
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
        $(".cmsTable").show();
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

    }

    
 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  $('#district_id').selectpicker('refresh');
  $('#user_id').selectpicker('refresh');
$(".loader_district_id").show();
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    $(".loader_district_id").hide();
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
  $(".loader_user_id").show();
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
            $(".loader_user_id").hide();
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

    $("#exit_trans_id").selectpicker({
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

  $( document ).ready(function() {
    $( "#sales_type" ).change(function() {
     
        if($(this).val()=="Existed Transaction")
        {
          $(".viewtrans_exited").show();
          $(".viewtrans_form").show();
        }
        else{
          $(".viewtrans_exited").hide();
          $(".viewtrans_form").show();
        }
        var type="{{$type}}";
      if(type=="Test Return")
            {
              $(".type_conf_sec").hide();
            }
        });
  }); 


  
  $(document).ready(function() {
    var selectedCategoryValue = $('#add_product_condition').val();
    var categoryOptions = $('#add_product_condition option'); 
    categoryOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#add_product_condition').append(categoryOptions);
    $('#add_product_condition').val(selectedCategoryValue);

    var selectedCategoryValue = $('#company_id').val();
    var categoryOptions = $('#company_id option'); 
    categoryOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#company_id').append(categoryOptions);
    $('#company_id').val(selectedCategoryValue);

    var selectedCategoryValue = $('#district_id').val();
    var categoryOptions = $('#district_id option'); 
    categoryOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#district_id').append(categoryOptions);
    $('#district_id').val(selectedCategoryValue);


    var selectedCategoryValue = $('#business_name').val();
    var categoryOptions = $('#business_name option'); 
    categoryOptions.sort(function(a, b) {
        return $(a).text().localeCompare($(b).text());
    });
    $('#business_name').append(categoryOptions);
    $('#business_name').val(selectedCategoryValue);

    
});


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
