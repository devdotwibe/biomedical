

@extends('staff/layouts.app')

@section('title', 'Edit Opportunity')

@section('content')


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
      <div class="row">
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
              <div class="box-body">

                <div class="form-group col-md-6">
                  <label for="name">Opportunity Reference No*</label>
                  <input type="text" id="op_ref" name="op_ref" class="form-control"  value="{{ old('op_ref',$opp->op_reference_no)}}" readonly="">
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Opportunity Name*</label>
                  <input type="text" id="name" name="op_name" class="form-control" placeholder="Opportunity Name" value="{{ old('op_name',$opp->oppertunity_name) }}" >
                </div>

                
                <div class="form-group  col-md-6">
                  <label>Account Name*</label>
                  <select name="account_name" id="account_name" class="form-control">
                    <option value="">-- Select Account Name --</option>
                    @foreach($customer as $item) 
                       <option value='{{$item->id}}' @if(old('account_name',$opp->user_id)==$item->id){{'selected'}} @endif>{{$item->business_name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Deal Stage*</label>
                  @php $deal_stage = array('Lead Qualified/Key Contact Identified',
                                           'Customer needs analysis',
                                           'Clinical and technical presentation/Demo',
                                           'CPQ(Configure,Price,Quote)',
                                           'Customer Evaluation',
                                           'Final Negotiation',
                                           'Closed-Lost',
                                           'Closed-Cancel',
                                           'Closed Won - Implement'
                                           );
                  @endphp
                  <select name="deal_stage" id="deal_stage" class="form-control">
                    <option value="">-- Select Deal stage --</option>
                    @for($i=0; $i< count($deal_stage);$i++)
                       <option value='{{$i}}' @if(old('deal_stage',$opp->deal_stage)==$i){{'selected'}} @endif>{{$deal_stage[$i]}}</option>
                    @endfor
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Es.Order Date*</label>
                  <input type="text" id="order_date" name="order_date" value="{{ old('order_date',$opp->es_order_date)}}" class="form-control" placeholder="Es.Order Date">
                  <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
                </div>

                

                <div class="form-group col-md-6">
                  <label for="name">Es.Sales Date*</label>
                  <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date',$opp->es_sales_date)}}" class="form-control" placeholder="Es.Sales Date">
                  <span class="error_message" id="sales_date_message" style="display: none">Field is required</span>
                </div>
                
              

              <div class="form-group  col-md-6">
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

              <div class="form-group col-md-6">
                <label for="name">Amount</label>
                  <input type="text" id="amount" name="amount" value="{{ old('amount',$opp->amount)}}" class="form-control" placeholder="Enter amount">
              </div>

              <div class="form-group  col-md-6">
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

              <div class="form-group  col-md-6">
                  <label>Type*</label>
                  <select name="type" id="type" class="form-control">
                     <option value="">-- Select Type --</option>
                     <option value="1" @if(old('type',$opp->type)==1){{'selected'}} @endif>Sales</option>
                     <option value="2" @if(old('type',$opp->type)==2){{'selected'}} @endif>Contract</option>
                     <option value="3" @if(old('type',$opp->type)==3){{'selected'}} @endif>HBS</option>
                  </select>
              </div>

              <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description" >{{ $opp->description }}</textarea>
              </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer col-md-12">
                <!-- <input type="submit" class="btn btn-primary"> -->
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{url('staff/list_oppertunity')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
        <div class="col-md-3 rightside-menu">
          <div class="box box-primary">
              <div class="panel-body padding-10">
                  <h4 class="bold">
                    Products ({{sizeof($op_pdt)}})
                  </h4>
              </div>
              <table id="cmsTable" class="table table-bordered table-striped data-">
                @php $i=1; @endphp
                @if(sizeof($op_pdt)>0)
                  @foreach($op_pdt as $pdts)
                      @if($i<=3)
                        <tr>
                          <td><b>{{$pdts->product->name}}</b> <br>
                              Quantity : {{$pdts->quantity}}  <br>
                              Amount   : {{$pdts->amount}}
                        </td>
                        </tr>
                      @php $i++; @endphp
                      @endif
                  @endforeach
                  <tr>
                    <td><a href="{{url('staff/list_oppertunity_products/'.$id)}}" class="btn btn-warning btn-sm pull-right">View All</a></td>
                  </tr>
                @endif
              </table>
              
          </div>
          <div class="row"></div>
          <div class="box">
            <div class="panel-body padding-10" style="background-color: #3c8dbc;">
                <h4 class="bold">
                  <a href="{{url('staff/prospectus/'.$id)}}" class="btn btn-block btn-primary btn-lg" style="border: none;">Prospectus</a>
                </h4>
            </div>
          </div>

        </div>
      </div>
</section>




@endsection

@section('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
  $('#account_name').select2();
  $('#engineer_name').select2();
  
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

        
    $('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
            minDate: 0  
            
    });
    $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
        });
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <script type="text/javascript">

$(document).ready(function() {
//$('#category_type_id').multiselect();
$('#category_id').multiselect();
/*$('#related_products').multiselect({
  enableClickableOptGroups: true
});*/


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
