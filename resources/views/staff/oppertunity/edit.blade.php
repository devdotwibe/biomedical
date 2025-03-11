

@extends('staff/layouts.app')

@section('title', 'Edit Opportunity')

@section('content')
<?php
if(isset($_GET['type']))
{
  $page_type=$_GET['type'];
}
else{
  $page_type='';
}

?>

<section class="content-header">
      <h1>
        Edit Opportunity
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Edit Opportunity</li>
      </ol>
</section>


<section class="content">
      <div class="row edit_oprty-row">
        <!-- left column -->
        <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">
<!--            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>-->
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

           @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
            <form role="form" name="frm_products" id="frm_products" method="post"  enctype="multipart/form-data" >
               @csrf
               <input type="hidden" id="page_type" name="page_type" value="{{$page_type}}" class="form-control" > 
              <div class="box-body">
                <div class="row oprty-row">
                <div class="form-group col-md-4">
                  <label for="name">Opportunity Reference No*</label>
                  <input type="text" id="op_ref" name="op_ref" class="form-control"  value="{{ old('op_ref',$opp->op_reference_no)}}" readonly="">
                </div>

                <div class="form-group col-md-4">
                  <label for="name">Opportunity Name*</label>
                  <input type="text" id="name" name="op_name" class="form-control" placeholder="Opportunity Name" value="{{ old('op_name',$opp->oppertunity_name) }}" readonly>
                </div>


                  <?php /*
                    <div class="form-group  col-md-4">
                      <label>Type*</label>
                      <select name="type" id="type" class="form-control">
                        <option value="">-- Select Type --</option>
                        <option value="1" @if(old('type',$opp->type)==1){{'selected'}} @endif>Sales</option>
                        <option value="2" @if(old('type',$opp->type)==2){{'selected'}} @endif>Contract</option>
                        <option value="3" @if(old('type',$opp->type)==3){{'selected'}} @endif>HBS</option>
                      </select>
                  </div>
                */?>  

              @php 

                $type ="";
                if($opp->type==1 )
                {
                    $type ="Sales";
                }
                if($opp->type==2 )
                {
                    $type ="Contract";
                }
                if($opp->type==3 )
                {
                    $type ="HBS";
                }

              @endphp

              <div class="form-group col-md-4">

                <label for="name">Type*</label>

                <input type="text" id="type" name="type" class="form-control"  value="{{$type}}" readonly="">

              </div>

              <div class="form-group  col-md-4" id="companynames" @if($opp->type!==1) style="display: none" @endif>
                <label>Select Companey</label>
                <select id="company_type" name="company_type" class="form-control">
                    <option value="">---Select Type---</option>
                    @foreach ($company??[] as $item)
                    <option value="{{$item->id}}"  {{ (old('state', $opp->company_type) == $item->id) ? 'selected' : '' }}>{{$item->name}}</option>                          
                    @endforeach
                </select>
                <span class="error_message" id="company_type_message" style="display: none">Field is required</span>
            </div> 

              <div class="form-group  col-md-4">
                <label for="name">State*</label>
                <select id="state" name="state" class="form-control" onchange="change_state()"> 
                  <option value="">Select State</option>
                  @foreach($state as $values)
                      <option value="{{ $values->id }}" {{ (old('state', $opp->state) == $values->id) ? 'selected' : '' }}>
                          {{ $values->name }}
                      </option>
                  @endforeach
              </select>
              
                <span class="error_message" id="state_message" style="display: none">Field is required</span>
              </div>

            <div class="form-group  col-md-4">
                <label for="name">District*</label>
                <select id="district" name="district" class="form-control" onchange="change_district()">
                <option value="">Select District</option>
                @foreach($district as $values)
            
                <option value="{{ $values->id }}" {{ (old('district', $opp->district) == $values->id) ? 'selected' : '' }}>
                  {{ $values->name }}
              </option>

                @endforeach
                </select>
                <span class="error_message" id="district_message" style="display: none">Field is required</span>
              </div>
                
              </div>
              <div class="row oprty-row">
                <div class="form-group  col-md-4">
                  <label>Account Name*</label>
                  <select name="account_name" id="account_name" class="form-control">
                    <option value="">-- Select Customer Name --</option>
                    @foreach($customer as $item) 
                       <option value='{{$item->id}}' @if(old('account_name',$opp->user_id)==$item->id){{'selected'}} @endif>{{$item->business_name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group  col-md-4">
                  <label>Engineer Name*</label>
                  <select name="engineer_name" id="engineer_name" class="form-control">
                    <option value="">-- Select Engineer Name --</option>
                    @foreach($staff as $item) 
                       <option value='{{$item->id}}' @if(old('engineer_name',$opp->staff_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group  col-md-4">
                  <label>Coordinator Name</label>
                  <select name="coordinator_id" id="coordinator_id" class="form-control">
                    <option value="">-- Select Coordinator Name --</option>
                    @foreach($staff as $item) 
                       <option value='{{$item->id}}' @if(old('coordinator_id',$opp->coordinator_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>


                
              </div>
                <div class="row oprty-row"> 
                                           
                <div class="form-group  col-md-4">
                  <label>Deal Stage*</label>
                  @php $deal_stage = array('Lead Qualified/Key Contact Identified',
                                           'Customer needs analysis',
                                           'Clinical and technical presentation/Demo',
                                           'CPQ(Configure,Price,Quote)',
                                           'Customer Evaluation',
                                           'Final Negotiation',
                                          //  'Closed-Lost',
                                          //  'Closed-Cancel',
                                          //  'Closed Won - Implement'
                                           );
                  @endphp
                  <select name="deal_stage" id="deal_stage" class="form-control">
                    <option value="">-- Select Deal stage --</option>
                    @for($i=0; $i< count($deal_stage);$i++)
                       <option value='{{$i}}' @if(old('deal_stage',$opp->deal_stage)==$i){{'selected'}} @endif>{{$deal_stage[$i]}}</option>
                    @endfor
                  </select>
                </div>

                <div class="form-group col-md-4" id="div_order_date" @if(old('type',$opp->type)==2) style="display:none" @endif>
                  <label for="name">Es.Order Date*</label>
                  <input type="text" id="order_date" name="order_date" value="{{ old('order_date',$opp->es_order_date)}}" class="form-control" placeholder="Es.Order Date">
                  <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4">
                  <label id="es_sales_date" for="name">@if(old('type',$opp->type)!==2) Es.Sales @else Es.Contract @endif Date*</label>
                  <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date',$opp->es_sales_date)}}" class="form-control" placeholder="Es.Sales Date">
                  <span class="error_message" id="sales_date_message" style="display: none">Field is required</span>
                </div>
                
                {{-- <div class="form-group col-md-4" @if(old('type',$opp->type)!==2) style="display:none" @endif>
                  <label for="start_date">Start Date*</label>
                  <input type="text" id="start_date" name="start_date" value="{{ old('start_date',$opp->contract_start_date)}}" class="form-control" placeholder="Start Date">
                  <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group col-md-4" @if(old('type',$opp->type)!==2) style="display:none" @endif >
                  <label for="end_date">End Date*</label>
                  <input type="text" id="end_date" name="end_date" value="{{ old('end_date',$opp->contract_end_date)}}" {{$opp->contract_end_date}} class="form-control" placeholder="End Date">
                  <span class="error_message" id="end_date_message" style="display: none">Field is required</span>
                </div> --}}


            </div>
            <div class="row oprty-row">
              
            
              <div class="form-group  col-md-4" id="div_order_forcast">
                @php $order_forcast =  array('Unqualified',
                                         'Not addressable',
                                         'Open',
                                         'Upside',
                                         'Committed w/risk',
                                         'Committed'
                                   );
                 @endphp
                   <label>Order Forcast Category*</label>
                   <select name="order_forcast" id="order_forcast" class="form-control">
                     <option value="">-- Select Deal stage --</option>
                     @for($i=0;$i< count($order_forcast);$i++)
                        <option value='{{$i}}' @if(old('order_forcast',$opp->order_forcast_category)==$i){{'selected'}} @endif>{{$order_forcast[$i]}}</option>
                     @endfor
                   </select>
               </div>

              <div class="form-group col-md-4" id="div_amount">
                <label for="name">Amount*</label>
                <input type="text" value="{{$opp->oppertunityOppertunityProduct->sum('amount')}}" class="form-control" placeholder="Enter amount" readonly>
                <input type="hidden" name="amount" value="0">
              </div>                             
                                           
              <div class="form-group  col-md-4" id="div_support">
               @php $support =  array('Demo',
                                      'Application/ clinical support',
                                      'Direct company support',
                                      'Senior Engineer Support',
                                      'Price deviation'
                                  );
                @endphp
                  <label>Support</label>
                  <select name="support" id="support" class="form-control">
                    <option value="">-- Select Support --</option>
                    @for($i=0;$i< count($support);$i++)
                       <option value='{{$i}}' @if(old('support',$opp->support)==$i){{'selected'}} @endif>{{$support[$i]}}</option>
                    @endfor
                  </select>
              </div>

              
            </div>
              <div class="form-group oprty-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description" >{{ $opp->description }}</textarea>
              </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer col-md-12">
                <input type="submit" class="mdm-btn submit-btn  ">
                <button type="button" class="mdm-btn cancel-btn " onClick="window.location.href='{{url('staff/list_oppertunity')}}?type={{$page_type}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
        <div class="col-md-3 rightside-menu edit_oprty-right">
          <div class="box box-primary">
              <div class="panel-body padding-10">
                  <h4 class="bold">
                 
                    Products ({{sizeof($op_pdt)}})
                  </h4>
              </div>
              @if($opp->type!='2')
              <table id="cmsTable" class="table table-bordered table-striped data-edit_oprty-table">
                @php $i=1; @endphp
                @if(sizeof($op_pdt)>0)
                  @foreach($op_pdt as $pdts)
                      @if($i<=3)
                        <tr>
                          <td>
                          @php
                        if($pdts->product_id>0)
                        {
                         $products_det=DB::select("select * from products where id='".$pdts->product_id."'");
                 
                         if($products_det){
                            $prod_name=$products_det[0]->name;
                          }else{ $prod_name='';}
                          
                        }else{
                          $prod_name='';
                        }
                        @endphp
                          <b>{{$prod_name}}</b> <br>
                              Quantity : {{$pdts->quantity}}  <br>
                              Amount   : {{$pdts->amount}}
                        </td>
                        </tr>
                      @php $i++; @endphp
                      @endif
                  @endforeach
                  <tr>
                    <td><a href="{{url('staff/list_oppertunity_products/'.$id)}}" class="mdm-btn submit-btn">View All</a></td>
                  </tr>
                @endif
              </table>
              @endif

              @if($opp->type==2)
              <table id="cmsTable" class="table table-bordered table-striped data-edit_oprty-table">
                @php $i=0;

                $diff_date="";

                 $diff = strtotime($opp->contract_end_date) - strtotime($opp->contract_start_date);
                  if(!empty($diff)&& $diff !=0 )
                  {
                    $diff_date= abs(round($diff / 86400));
                  }
               
                @endphp
                @if(sizeof($op_pdt)>0)
                  @foreach($op_pdt as $pdts)
                     
                        <tr>
                          <td>
                          @php
                        if($pdts->product_id>0)
                        {
                         $products_det=DB::select("select * from products where id='".$pdts->product_id."'");
                 
                         if($products_det){
                           if(count($product_arr)>0)
                           {
                            $get_serialno=App\Ib::getibproduct_id($product_arr[$i]);
                           
                           }else{
                            $get_serialno='';
                           }
                           $prod_name=$products_det[0]->name;
                            echo '<br>';
                            
                          }else{ $prod_name='';$$get_serialno='';}
                          
                        }else{
                          $prod_name='';
                        }
                        @endphp
                          <b>{{$prod_name}}</b> Sl.no: {{ $get_serialno }}<br>
                              Quantity : {{$pdts->quantity}}  <br>
                              Amount   : {{$pdts->amount}}
                              @php

                              $divide ="";
                              if(!empty($diff_date))
                              {
                                $divide=abs(round($diff_date/$pdts->pm));

                              }
                                
                                for($k=0;$k<$pdts->pm;$k++)
                                {
                                 
                                  $p=$k+1;
                                  echo '<span>PM - '.$p.'</span>';

                                  if(!empty($divide))
                                  {
                                    echo '  <input type="text" disabled="true" name="pm_date[]" id="pm_date'.$pdts->id.'_'.$i.'" class="pmdate pm_date'.$pdts->id.'" value="'.date('Y-m-d', strtotime($opp->contract_start_date. ' + '.$divide*$p.' days')).'">';
                                  }
                                  else {

                                    $divide =1;
                                    
                                    echo '  <input type="text" disabled="true" name="pm_date[]" id="pm_date'.$pdts->id.'_'.$i.'" class="pmdate pm_date'.$pdts->id.'" value="'.date('Y-m-d', strtotime($opp->contract_start_date. ' + '.$divide*$p.' days')).'">';
                                  }
                                  
                                  echo '<br>';
                                }
                                echo '  <input type="button" name="edit" id="pm_btnedit'.$pdts->id.'" attr-id="'.$pdts->id.'" attr-row="'.$i.'" class="pmbtn_edit" value="Edit">';
                                echo '  <input type="button" style="display:none;" name="save" id="pm_btnsave'.$pdts->id.'" attr-row="'.$i.'" attr-id="'.$pdts->id.'" class="pmbtn_save" value="Save">';
                              @endphp
                        </td>
                        </tr>
                        @php $i++; @endphp
                  @endforeach
                  <tr>
<td>Next Schedule Quote Date
  <input type="text"  name="next_quote_date" id="next_quote_date" class="pmdate" value="{{ date('Y-m-d', strtotime("+2 months", strtotime($opp->contract_end_date))) }}"></td>
                  </tr>
                  <tr>
                  
                    <td><a href="{{url('staff/list_oppertunity_products/'.$id)}}" class="mdm-btn submit-btn">View All</a></td>
                   
                  </tr>
                  <tr>
                  <td><a href="" class="mdm-btn submit-btn">Request for Final Approve</a></td>
                </tr>
                @endif
              </table>
              @endif
              
          </div>
          <div class="row"></div>
          <!-- <div class="box">
            <div class="panel-body padding-10" style="background-color: #3c8dbc;">
                <h4 class="bold">
                  <a href="{{url('staff/prospectus/'.$id)}}" class="btn btn-block btn-primary btn-lg" style="border: none;">Prospectus</a>
                </h4>
            </div>
          </div> -->

        </div>
      </div>
</section>
{{-- 
@if(count($quote_list)>0)
<section class="content-footer">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3>Update Quote Won status</h3>
        </div>
        <div class="box-body">
          <form action="{{route('staff.quote.won',$id)}}" method="post" id="quote-product-status">
            @csrf
          @foreach($quote_list as $qte)
            @if(count($qte->quoteProducts )>0)
            <input type="hidden" name="quote[]" value="{{$qte->id}}">
            <div class="row">
              <div class="col-md-9">
                  <h5>{{$qte->quote_reference_no}} [{{$qte->created_at}}]</h5>
                  
                      <label> Products ({{count($qte->quoteProducts)}})</label>
                      @foreach($qte->quoteProducts as $k=> $qteprdt)
                      <div class="form-group">
                        <div class="row">
                          <div class="col-2">
                            <span>{{$k+1}}</span>
                          </div>
                          <div class="col-4">
                            <span>{{$qteprdt->product_name}}</span>
                          </div>
                          <div class="col-4">
                            <input name="product_amount_{{$qte->id}}[]" class="form-control" id="product_{{$qte->id}}_{{$k}}_0" value="{{$qteprdt->product_sale_amount}}" >
                          </div>
                          <div>
                            <input type="checkbox" class="quote-product quote-product-main" data-quote="{{$qte->id}}" name="product_id_{{$qte->id}}[]" id="product_id_{{$qte->id}}_{{$k}}_0" value="{{$qteprdt->id}}" >
                          </div>
                        </div>
                      </div>
                      @if(count($qteprdt->optionalProducts)>0)
                      
                      @foreach($qteprdt->optionalProducts as $y=> $qteoptprdt)
                      <div class="form-group">
                        <div class="row">
                          <div class="col-2">
                            <span>{{$k+1}}.{{(range('a', 'z'))[$y]}}.</span>
                          </div>
                          <div class="col-4">
                            <span>{{$qteoptprdt->name}}</span>
                          </div>
                          <div class="col-4">
                            <input name="product_amount_{{$qte->id}}_opt_{{$qteprdt->id}}[]" class="form-control quote-product-{{$qte->id}}-{{$qteprdt->id}}" id="product_{{$qte->id}}_{{$k}}_{{$y+1}}" value="{{$qteoptprdt->sale_amount}}"  disabled>
                          </div>
                          <div>
                            <input type="checkbox" class="quote-product quote-product-{{$qte->id}}-{{$qteprdt->id}}" data-quote="{{$qte->id}}"  name="product_id_{{$qte->id}}_opt_{{$qteprdt->id}}[]" id="product_id_{{$qte->id}}_{{$k}}_{{$y+1}}" value="{{$qteoptprdt->id}}" disabled >
                          </div>
                        </div>
                      </div>
                      @endforeach
                      @endif
                      @endforeach
              </div>
            </div>
            @endif
          @endforeach
          <div class="row m-1">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Save Quote Won</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endif
 --}}


@endsection

@section('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
  $('#account_name').select2();
  $('#engineer_name').select2();
  $('#coordinator_id').select2();
  
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

function change_state(){
      var state_id=$("#state").val();
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
                  $("#district").html(states_val);
                 
              }
            });

      }

      function change_district(){

          var district_id=$("#district").val();
          var state_id=$("#state").val();
          var type=$("#type").val();

          var url = APP_URL+'/staff/get_client_use_state_district';
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    district_id: district_id,state_id: state_id,type: type
                  },
                  success: function (data)
                  {    
                    var proObj = JSON.parse(data);
                    states_val='';
                    states_val +='<option value="">Select Customer</option>';
                    for (var i = 0; i < proObj.length; i++) {
                    
                      states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
                  
                      }
                      $("#account_name").html(states_val);
                    
                  }
                });

      }


  $('.quote-product-main').change(function(e){
    var proid = $(this).val();
    var qteid = $(this).data('quote');
    $(`.quote-product-${qteid}-${proid}`).prop("disabled",!(this.checked));
    if(!this.checked){
      $(`input[type="checkbox"].quote-product-${qteid}-${proid}`).prop('checked',false)
    }
  });

$(".pmbtn_edit").click(function() {
  var row_no=$(this).attr('attr-row');
  var row_id=$(this).attr('attr-id');
  $("#pm_btnedit"+row_id).hide();
  $("#pm_btnsave"+row_id).show();
  $('.pm_date'+row_id).removeAttr("disabled");
});

$(".pmbtn_save").click(function() {
  var row_no=$(this).attr('attr-row');
  var row_id=$(this).attr('attr-id');
  $("#pm_btnedit"+row_id).show();
  $("#pm_btnsave"+row_id).hide();
  $('.pm_date'+row_id).prop("disabled", true);
});

$('.pmdate').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0  
    });

$('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            // minDate: 0  
    });
    $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            // minDate: 0  
            
        });

    $('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
        });
    $('#end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
        });
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <script type="text/javascript">

$(document).ready(function() {

  //change_district();
//$('#category_type_id').multiselect();
$('#category_id').multiselect();
/*$('#related_products').multiselect({
  enableClickableOptGroups: true
});*/
var type = $('#type').val();
if(type==2)
{
  $('#div_order_date').hide();
  $('#div_order_forcast').hide();
  $('#div_amount').hide();
  $('#div_support').hide();
}
});

$(document).ready(function() {
    $('#related_products').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="related_products" />'
    });
    $('#related_products .caret-container').click();
});

$(document).ready(function() {
    $('#competition_product').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="competition_product" />'
    });
    $('#competition_product .caret-container').click();
});


 </script>

@endsection
