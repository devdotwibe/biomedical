@extends('staff/layouts.app')
@section('title', 'Add Invoice')
@section('content')
<?php
if(isset($_GET['id']))
{
$trans_id=$_GET['id'];
}else{
  $trans_id=0;
}
if(isset($_GET['type']))
{
$type=$_GET['type'];
}else{
  $type=0;
}
if(isset($_GET['dispatch_id']))
{
$dispatch_id=$_GET['dispatch_id'];
}else{
  $dispatch_id=0;
}

?>
<section class="content-header">
      <h1>
        Add Invoice
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.invoice.index')}}">Manage Invoice</a></li>
        <li class="active">Add Invoice</li>
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
            <form role="form" name="frm_invoice" id="frm_invoice" method="post" action="{{route('staff.invoice.store')}}" enctype="multipart/form-data" >
               @csrf

              

                <div class="box-body row noboder-box">
                <div class="form-group  col-md-3 col-sm-6 col-lg-3"  >
                  <label for="name">Invoice Id</label>
                  <input type="text" id="invoice_id" name="invoice_id" class="form-control" value="INVOICE_{{$last_id}}" onkeyup="change_invoice_id(this.value)" onchange="change_invoice_id(this.value)">
                  <span class="error_message" id="invoice_id_message" style="display: none">Field is required</span>
                  <span class="error_message" id="invoice_id_exit_message" style="display: none">Invoice id already exit</span>
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3" style="display:none;" >
                  <label for="name">IN/OUT</label>
                  <select id="inandout" name="inandout" class="form-control" >
                  <option value="">IN/OUT</option>
                  <option value="IN">IN</option>
                  <option value="OUT" selected="true">OUT</option>
                 
                  </select>
                  <span class="error_message" id="inandout_message" style="display: none">Field is required</span>
                  <input type="hidden" name="tran_type" id="tran_type">
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3" @if($type!="") style="display:none;" @endif>
                  <label for="name">Type</label>
                  <select id="invoice_type" name="invoice_type" class="form-control" onchange="change_invoice_type(this.value)">
                  <option value="">Type</option>
                  <option value="Transaction" @if($type=="invoice") selected="true" @endif>Transaction</option>
                  <option value="Dispatch" @if($type=="dispatch") selected="true" @endif>Dispatch</option>
                 
                  </select>
                  <span class="error_message" id="invoice_type_message" style="display: none">Field is required</span>
                  <input type="hidden" name="tran_type" id="tran_type">
                </div>
             

                <div class="form-group  col-md-3 col-sm-6 col-lg-3 transaction_sec" style="display:none">
                  <label for="name">Transaction</label>
                  <select id="transaction_id"  name="transaction_id" class="form-control" onchange="get_transaction_details(this.value)">
                  <option value="">Transaction</option>
                  <?php
                      foreach($transaction as $item) {
                        $sel = ($trans_id == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>Trans_'.$item->id.'</option>';
                      } 

                      ?>
                  </select>
                  <div class="loader_trans_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="transaction_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-3 col-sm-6 col-lg-3 dispatch_sec" style="display:none">
                  <label for="name">Dispatch</label>
                  <select id="dispatch_id" name="dispatch_id" class="form-control" onchange="get_dispatch_details_invoice(this.value)">
                  <option value="">Dispatch</option>
                  <?php
                       foreach($dispatch as $item) {
                        $sel = ($dispatch_id == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>Dispatch_'.$item->id.'</option>';
                      }
                      ?>
                  </select>
                  <div class="loader_dispatch_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="dispatch_id_message" style="display: none">Field is required</span>
                </div>


              

              </div>
                <div class="box-body row cmsTable" style="display:none;">

                <table id="cmsTable" class="table table-bordered table-striped data-" style="display:none;">
                <thead>
                  <tr>
                  <th><input checked="true" type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                    <th>No.</th>
                    <th>Name</th>
                  
                    <th>Qty</th>
                  
                    <th>Unit Price</th>
                    <th>HSN</th>
                   
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                     
                   
                    <th>Net Amount</th>
              
                  </tr>
                </thead>
                <tbody id="tabledata">
                    
               </table>

               <table id="cmsTable_user" class="table table-bordered table-striped data-" style="display:none;">
                <thead>
                  <tr>
                    <th>Customer Name</th>
                    <th>Address</th>
                 
              
                  </tr>
                </thead>
                <tbody id="tabledata_user">
                    
               </table>
              </div>
               <div class="box-footer">
                  
               
               <span class="error_message" id="product_message" style="display: none">Please select  product</span>
                <button type="button" class="mdm-btn submit-btn   "  onclick="validate_from()">Submit</button>
                <!--  -->
                @if($trans_id>0)
                <button type="button" class="mdm-btn cancel-btn   " onClick="window.location.href='{{route('staff.Pendingtransaction')}}'">Cancel</button>
                @endif
                @if($trans_id==0)
                <button type="button" class="mdm-btn cancel-btn   " onClick="window.location.href='{{route('staff.invoice.index')}}'">Cancel</button>
                @endif
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
var trans_id="<?php echo $trans_id;?>";
var type="<?php echo $type;?>";
var dispatch_id="<?php echo $dispatch_id;?>";
if(trans_id>0 && type=="invoice")
{
  change_invoice_type('Transaction');
  $("#transaction_id").val(trans_id);
  get_transaction_details(trans_id)
}
if(dispatch_id>0 && type=="dispatch")
{
  change_invoice_type('Dispatch');
  $("#dispatch_id").val(dispatch_id);
  get_dispatch_details_invoice(dispatch_id)
}

function change_invoice_type(invoice_type)
    {
      $("#tabledata").html('');
      $(".cmsTable").hide();
      $("#dispatch_id").val('');
      $("#transaction_id").val('');
      if(type=='')
    {
      if(invoice_type=="Transaction")
      {
        $(".transaction_sec").show();
        $(".dispatch_sec").hide();
      }
      if(invoice_type=="Dispatch")
      {
        $(".dispatch_sec").show();
        $(".transaction_sec").hide();
      }
    }
      
    }

    
    function get_dispatch_details_invoice(dispatch_id)
    {
      
      $("#tabledata").html('');
      $("#tabledata_user").html('');
      $(".loader_dispatch_id").show();
      
   
           $.post("{{url('staff/get_dispatch_details_for_invoice')}}", {dispatch_id: dispatch_id, "_token": "{{ csrf_token() }}"}, function(result,status){
          
            $("#tabledata").append(result);
            $(".loader_dispatch_id").hide();
            $("#cmsTable_user").hide();
                        
            $("#cmsTable").show();
            $(".cmsTable").show();
            
            $("#selectAll").prop( "disabled", true );
      });



    }

function change_invoice_id(invoice_id)
{
  var url = APP_URL+'/staff/check_invoice_id_exit';
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            invoice_id: invoice_id,
          },
          success: function (data)
          { 
            if(data=="0")
            {
              $("#invoice_id_exit_message").hide();
            }
            else{
              $("#invoice_id_exit_message").show();
            }
            
          }
      });
}
    function get_transaction_details(trans_id)
    {
      $("#tabledata").html('');
      $("#tabledata_user").html('');
      $(".loader_trans_id").show();
      var url = APP_URL+'/staff/get_transaction_details_for_invoice';
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            trans_id: trans_id,
          },
          success: function (data)
          {    $(".loader_trans_id").hide();
              var res = data.split("*");
              var user = JSON.parse(res[0]);
              var proObj = JSON.parse(res[1]);
            
              $("#cmsTable_user").show();
              var htmlscontents='';
              htmlscontents +='<tr><td>'+user[0]["business_name"]+'</td>';
             
              htmlscontents += '<td>'+user[0]["address1"]+'</td></tr>';

              $("#tabledata_user").html(htmlscontents);
              $("#tran_type").val(user[0]["tran_type"]);
              var sale_amount=0;
              var j=1;

              var myTotal = 0; 
              var tot_cgst=0;
              var tot_sgst=0;
              var tot_igst=0;
              var tot_cess=0;
              var tot_taxable=0;

            for (var i = 0; i < proObj.length; i++) {
              
             if(proObj[i]["quantity"]!=proObj[i]["out_product_quantity"] || proObj[i]["out_product_quantity"]==0)
             {
             
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'">'+
               '<td data-th="" ><input type="checkbox" class="dataCheck" checked="true" name="ids[]" value="'+proObj[i]["id"]+'" id="check'+proObj[i]["id"]+'"></td>'+
               '<td data-th="No." >'+j+'</td><td>'+proObj[i]["product_name"]+'</td>';
               
               var balance=proObj[i]["quantity"]-proObj[i]["out_product_quantity"];
               if(balance>1)
               {
              htmlscontent += '<td data-th="Name" ><input type="number" value="'+balance+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" >  <span class="error_message" id="error_qty_'+proObj[i]["id"]+'" style="display: none"></span></td>';
               }
               else{
                htmlscontent += '<td data-th="Qty" ><input readonly="true" type="number" value="'+balance+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control"  data-id="'+proObj[i]["id"]+'"  ></td>';
               }
             
               
             
              htmlscontent += '<td data-th="Unit Price" ><input type="text" readonly="true" name="sale_amount[]" value="'+proObj[i]["sale_amount"]+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;"></td>';
               if(proObj[i]["hsn_code"]=='' || proObj[i]["hsn_code"]==undefined)
               {
                htmlscontent += '<td data-th="" ></td>';
               }
               else{
                htmlscontent += '<td data-th="HSN" >'+proObj[i]["hsn_code"]+'</td>';
               }
               
              
               htmlscontent +=  '<td data-th="CGST" ><input type="text" readonly="true" value="'+proObj[i]["cgst"]+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" ></td>'+
               '<td data-th="SGST" ><input type="text"  readonly="true" value="'+proObj[i]["sgst"]+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'"></td>'+
               '<td data-th="IGST" ><input type="text" readonly="true" value="'+proObj[i]["igst"]+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" ></td>'+
               '<td data-th="Cess" ><input type="text" readonly="true" value="'+proObj[i]["cess"]+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" ></td>'+
             '<td data-th="Net Amount" ><input type="text" value="'+proObj[i]["amt"]+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly>'+
             '<input type="hidden" value="'+proObj[i]["tax_percentage"]+'" id="tax_percentage_'+proObj[i]["id"]+'" class="tax_percentage form-control" name="tax_percentage[]" data-id="'+proObj[i]["id"]+'" readonly></td></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
              //arr_total.push(amt);
              arr_total[proObj[i]["id"]] = proObj[i]["amt"];
              j++;

              myTotal = parseFloat(proObj[i]["amt"])+parseFloat(myTotal);
 tot_cgst = parseFloat(proObj[i]["cgst"])+parseFloat(tot_cgst);
tot_sgst = parseFloat(proObj[i]["sgst"])+parseFloat(tot_sgst);
 tot_igst = parseFloat(proObj[i]["igst"])+parseFloat(tot_igst);
  tot_cess = parseFloat(proObj[i]["cess"])+parseFloat(tot_cess);
 

              }
           }
         


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
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

$("#cmsTable").show();
$(".cmsTable").show();
$("#selectAll").prop( "disabled", false );
          }
        });


    }
 var arr_total = [];
 

    function validate_from()
      {
        var inandout=$("#inandout").val();
       
        var transaction_id=$("#transaction_id").val();
     
        var dispatch_id=$("#dispatch_id").val();
        var invoice_type=$("#invoice_type").val();

        var product = []; 
        if(invoice_type=="Transaction")
        {
            $("input:checkbox[name='ids[]']:checked").each(function() { 
              product.push($(this).val()); 
            });
        }
        else{
          $('input[name^="ids[]"]').each(function() {
            product.push($(this).val()); 
          });
        }

            var flag=0; 
        $('input[name^="quantity[]"]').each(function() {
          var product_id=$(this).attr('data-id');

          if ($.inArray($(this).attr('data-id'), product) >= 0) {
            if($(this).val()==''){
            $("#error_qty_"+product_id).html('');
              $("#error_qty_"+product_id).html('Required Field!');
              $("#error_qty_"+product_id).show();
              flag=1;
          } else{
            $("#error_qty_"+product_id).html('');
            $("#error_qty_"+product_id).hide();
          } 
    }else {
      $("#error_qty_"+product_id).html('');
            $("#error_qty_"+product_id).hide();
    }

         
          
            });

            
      
        var flags_type=0;
        if(invoice_type=="Transaction")
        {
          if(transaction_id=="")
          {flags_type=1;
            $("#transaction_id_message").show();
          }
          else{flags_type=0;
            $("#transaction_id_message").hide();
          }
        }
        else{
          if(dispatch_id=="")
          {flags_type=1;
            $("#dispatch_id_message").show();
          }
          else{flags_type=0;
            $("#dispatch_id_message").hide();
          }
        }
       

        if(invoice_type=="")
        {
          $("#invoice_type_message").show();
        }
        else{
          $("#invoice_type_message").hide();
        }

        if(inandout=="")
        {
          $("#inandout_message").show();
        }
        else{
          $("#inandout_message").hide();
        }
       
        if(product.length==0)
        {
          $("#product_message").show();
        }else{
          $("#product_message").hide();
        }


        
        if(inandout!='' && flags_type==0 && product.length>0 && flag==0 && invoice_type!='')
        {
         $("#frm_invoice").submit(); 
        }


      }


 function change_qty(quantity,trans_pro_id)
  {
    if(quantity!=0)
    {

      $("#error_qty_"+trans_pro_id).hide();
var trans_id=$("#transaction_id").val();
    var url = APP_URL+'/staff/get_transaction_product_qty_check_invoice';
    
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            trans_pro_id: trans_pro_id,trans_id:trans_id,quantity:quantity
          },
          success: function (data)
          { var product_id=trans_pro_id;
            var res = data.split("*");
            if(res[0]==0)
            {
              $("#error_qty_"+product_id).html('');
              $("#error_qty_"+product_id).css('color', 'red');
              $("#error_qty_"+product_id).html('Available product only '+res[1]);
              $("#error_qty_"+product_id).show();
              $("#qn_"+product_id).val('');
              
            }else{
              $("#error_qty_"+product_id).html('');
              $("#error_qty_"+product_id).hide();
              if(res[1]>0)
              {
                $("#error_qty_"+product_id).show();
                var bal=res[1]-quantity;
                if(bal!=0)
                {
                  $("#error_qty_"+product_id).css('color', 'green');
                $("#error_qty_"+product_id).html('Balance Product '+bal);
                }
                
              }
              
              var product_id = product_id;
        var tran_type=$("#tran_type").val();
         var  sale_amount = $("#sa_"+product_id).val();
                var amt    = quantity * sale_amount;
               var taxab_amount=amt;
              
                var tax=$("#tax_percentage_"+product_id).val();
           
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
                
                $("#am_"+product_id).val(amt);
                $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
              
                $(".hqn_"+product_id).val(quantity);
         

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
                '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);

            }

          }

        });

      }
      else{
        $("#qn_"+trans_pro_id).val('');
        $("#error_qty_"+trans_pro_id).show();
        $("#error_qty_"+trans_pro_id).html('Please enter greater than zero');
      }
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