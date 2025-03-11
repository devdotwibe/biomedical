

@extends('staff/layouts.app')

@section('title', 'Update Prospectus')

@section('content')


<section class="content-header">
      <h1>
        Update Prospectus
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Oppertunity</a></li>
        <li class="active">Update Prospectus</li>
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
                  <label for="name">Company Name*</label>
                  <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Company Name" value="{{ old('company_name',$oppertunity->customer->name) }}" >
                </div>


                <div class="form-group  col-md-6">
                  <label>Spoken With</label>
                  <select name="contact[]" id="contact" class="form-control" multiple="multiple" required>
                    <option value="">-- Select Contact Person --</option>
                    @foreach($contact as $item) 
                       <option value='{{$item->id}}'>{{$item->name}}</option>
                    @endforeach
                  </select>
                  <a href="{{url('staff/customer/'.$oppertunity->customer->id)}}" target='_blank'>Add Contact</a>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">{{$user->business_name}}</label>
                  <label for="name">{{$user->name}}</label>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Summary of Discussion</label>
                  <input type="text" id="summary" name="summary" class="form-control" placeholder="summary" >
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Phone : {{$user->phone}}</label>
                  <label for="name">Email : {{$user->email}}</label>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Next Steps</label>
                  <input type="text" id="next_steps" name="next_steps" class="form-control" placeholder="Next steps" >
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Address:</label> 
                  <label for="name">{{$user->address1}},{{$user->address2}}</label>
                  <label for="name">{{$user->city}}</label> 
                  <label for="name">{{$user->zip}}</label>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Next Step Date*</label>
                  <input type="text" id="next_step_date" name="next_step_date"  class="form-control" placeholder="Next stepDate">
                  <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
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
                       <option value='{{$i}}'>{{$deal_stage[$i]}}</option>
                    @endfor
                  </select>
                </div>

                <div class="form-group  col-md-6 cancel_stat" style="display: none">
                  <label>Cancel Status*</label>
                  <select name="cancel_stat" id="cancel_stat" class="form-control">
                     <option value="" selected="selected">-- Select Cancel Status --</option>
                     <option value="1">Cancel Opportunity</option>
                     <option value="2">Duplicate Opportunity</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Es.Order Date*</label>
                  <input type="text" id="order_date" name="order_date" value="{{ old('order_date',$oppertunity->es_order_date)}}" class="form-control" placeholder="Es.Order Date">
                  <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Es.Sales Date*</label>
                  <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date',$oppertunity->es_sales_date)}}" class="form-control" placeholder="Es.Sales Date">
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
                    <option value="">-- Select Order Forcast Category --</option>
                    @for($i=0;$i< count($order_forcast);$i++)
                       <option value='{{$i}}'>{{$order_forcast[$i]}}</option>
                    @endfor
                  </select>
              </div>


              </div>
              <!-- /.box-body -->

              <div class="box-footer col-md-12">
                <input type="submit" class="btn btn-primary">
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{url('staff/prospectus/'.$id)}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
        
      </div>
</section>




@endsection

@section('scripts')

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
    $('#next_step_date').datepicker({
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

 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#contact').multiselect();
   
});
</script>

<script>
  $(document).ready(function(){
    $('#deal_stage').change(function(){
      var id= $('#deal_stage option:selected').val();
     // alert(id);
      if(id==7)
      {
        $('.cancel_stat').show();
      }
      else
      {
        $('.cancel_stat').hide();
      }
    });
      

  });

  </script>


@endsection
