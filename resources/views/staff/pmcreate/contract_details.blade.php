@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

<section class="content-header">
    <h1>
       PMs Assign Window
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">PMs Assign Window</li>
    </ol>
</section>

<!-- Main content -->

<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
           

              <div class="alert alert-success alert-block fade in alert-dismissible" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong id="success_pm"></strong>
            </div>
          
           
            <div class="box-body">

              <img src="{{ asset('images/approval.png') }}" class="approve-info">

              <form name="dataForm" id="dataForm" method="post" action="" >
                @csrf

                    @foreach($services as $key=>$services_item)

                          <ul class="table-head-list"  id="updated-values-list">

                                <li>{{$services_item->serviceUser->business_name}} </li>

                                <li>{{$services_item->pmContract->contract_type}} </li>

                                <li>{{$services_item->serviceContactPerson->mobile}} </li>

                                <li>{{$services_item->pmContract->contract_start_date ? \Carbon\Carbon::parse(str_replace(':','-',$services_item->pmContract->contract_start_date))->format('d-m-Y') : '' }} </li>

                                <li>{{$services_item->pmContract->contract_end_date ? \Carbon\Carbon::parse( str_replace(':','-',$services_item->pmContract->contract_end_date))->format('d-m-Y') : '' }} </li>

                                <li>{{$services_item->pmContract->in_ref_no}} </li>

                      @php 
                                
                                $contract_products = \App\ContractProduct::with('productPMList')
                                                    ->whereHas('productPMList') 
                                                    ->whereNotNull('pm_dates')
                                                    ->where('service_id', $services_item->id)
                                                    ->get();

                      @endphp

                      @if(!empty($contract_products) && count($contract_products)>0)

                          <div class="table-outer-pm">
                            
                            <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                              <thead>

                                    <tr class="headrole">
                                        
                                        <th>No.</th>
                                        <th>Equipment</th>
                                        <th>Serial no. </th>

                                        @if(!empty($contract_products))

                                            @php

                                            $total_rows=0

                                            @endphp

                                          @foreach($contract_products as $index => $item)

                                            @php
                                                  if ($item->no_of_pm > $total_rows) {
                                                      $total_rows = $item->no_of_pm;
                                                  }

                                            @endphp

                                          @endforeach

                                            @for($i=0;$i<$total_rows; $i++)

                                              <th>PM{{$i+1}} (Date)</th>

                                              <th> Engineer Name</th>

                                            @endfor

                                        @endif
                                    </tr>

                                </thead>

                              <tbody>

                                @if(!empty($contract_products) && count($contract_products)>0)

                                @foreach($contract_products as $index => $item)

                                      <tr>

                                        <td class="mobile_view">{{$index + 1}}</td>
                                        <td class="mobile_view">{{$item->equipment->name }}  </td>
                                        <td class="mobile_view">{{$item->equipment_serial_no }}  </td>

                                        @php
                                          
                                          $pmDates = json_decode($item->pm_dates??"[]", true) ?? [];

                                          $pm_count= 0;

                                          if(isset($pmDates))
                                          {
                                            $pm_count = count($pmDates);
                                          }

                                          $flag=false;

                                          $add_count=0;

                                          if($pm_count < $total_rows)
                                          {
                                            $add_count = $total_rows -$pm_count;

                                            $flag=true;
                                          }
                                          
                                          
                                        @endphp

                                            @if(!empty($pmDates))

                                              @foreach($pmDates as $count => $pms)

                                                @if(!empty(optional($item->productPM("PM".($count+1)))->id))

                                                    @php 

                                                      $desired_date = optional($item->productPM("PM".($count+1)))->visiting_date;

                                                      $dates_pms = $pms;

                                                      $diff_color = 'style="background-color:orange !important"';

                                                      if($desired_date != $dates_pms)
                                                      {
                                                        $diff_color = 'style="background-color:yellow !important"'; 
                                                      }
                                                      else
                                                      {
                                                          $diff_color = 'style="background-color:grey !important"'; 
                                                      }

                                                      $desired_date_format = \DateTime::createFromFormat('Y-m-d', $desired_date);

                                                      $today = now()->format('Y-m-d'); 

                                                      if ($desired_date_format && $desired_date_format->format('Y-m-d') < $today) {

                                                        $diff_color = 'style="background-color:red !important"'; 

                                                      }

                                                      if(optional($item->productPM("PM".($count+1)))->status =='Approved')
                                                      {
                                                        $diff_color = 'style="background-color:#33cc00 !important"'; 
                                                      }

                                                      $engineer_style =false;

                                                      if(!empty(optional($item->productPM("PM".($count+1)))->engineer_name))
                                                      {
                                                          $engineer_style =true;
                                                      }
                                                    

                                                    @endphp

                                                    <td>
                                                      
                                                        <input  type="text" name="visiting_date[]" {!! $diff_color !!}  id="visiting_date-{{$count}}-{{ $index }}-{{$key}}" class="form-control visitdate-picker status{{ $index }}{{$item->id}}{{$count}} picker{{ $index }}{{$item->id}}" data-id="{{$item->productPM("PM".($count+1))->id}}" value="{{ $item->productPM("PM".($count+1))->visiting_date ? \Carbon\Carbon::parse($item->productPM("PM".($count+1))->visiting_date)->format('d-m-Y') : ''}}" readonly>

                                                    </td>

                                                    <td>

                                                      <select {!! $engineer_style ? $diff_color : 'style="background-color:grey !important"' !!} class="form-control engineer-dropdown status{{ $index }}{{$item->id}}{{$count}} engineer{{ $index }}{{$item->id}}" id="engineer-{{$count}}-dropdown-{{ $index }}" data-id="{{$item->productPM("PM".($count+1))->id}}" name="engineer[]">
                                                          <option value="">Select Engineer</option>
                                                          @foreach  ($staffs->sortBy('name') as $engineer)

                                                              <option value="{{ $engineer->id }}" @if( $engineer->id == optional($item->productPM("PM".($count+1)))->engineer_name) selected @endif > {{ $engineer->name }} </option>

                                                          @endforeach
                                                      </select>

                                                    </td>  

                                                @endif                                              

                                              @endforeach

                                              @if($flag)

                                                      @for($i=0;$i<$add_count; $i++)

                                                      <td>&nbsp;</td>

                                                      <td>&nbsp;</td>

                                                      @endfor

                                              @endif

                                              <td>

                                                  @php
                                                    $allaprove = '';
                                                    if($item->productPMIsallAppproved($item->id))
                                                    {
                                                      $allaprove = 'disabled';
                                                    }

                                                  @endphp
                                                
                                                  <button type="button" class="btn-save btn-btn-primary  saveshow{{$index}}{{$item->id}}" {{$allaprove}} id="cancel{{$index}}" onclick="SavePm(this)" data-pdt="{{$item->id}}" data-id="{{$index}}{{$item->id}}">Save</button>

                                                  <?php /*
                                                  <button type="button" class="btn-edit" id="editbutton{{$index}}{{$item->id}}" onclick="EditPm(this)" data-id="{{$index}}{{$item->id}}">Edit </button>

                                                  <button type="button" class="btn-cancel saveshow{{$index}}{{$item->id}}" id="cancelbutton{{$index}}" onclick="CancelPm(this)" data-id="{{$index}}{{$item->id}}" style="display:none;">Cancel</button>
                                                */?>
                                      
                                              </td>               

                                            @endif

                                      </tr>

                                  @endforeach               

                                @endif
                                
                            </tbody>

                          </table>
                        </div>
                        @endif

                    @endforeach

              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection



@section('scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">


    $('.visitdate-picker').datepicker({
        //dateFormat:'yy-mm-dd',
        dateFormat:'dd-mm-yy',
      // minDate: 0,
        changeYear: true,
        changeMonth: true,
    });

    // function EditPm(element)
    //   {
    //     var raw_id = $(element).data('id');

    //     console.log(raw_id);

    //     $('.raw' + raw_id).prop('disabled', false);

    //     $('#editbutton' + raw_id).hide();

    //     $('.saveshow' + raw_id).show();


    //   }

      // function CancelPm(element)
      // {
      //   var raw_id = $(element).data('id');

      //   console.log(raw_id);

      //   $('.raw' + raw_id).prop('disabled', true);

      //   $('#editbutton' + raw_id).show();

      //   $('.saveshow' + raw_id).hide();


      // }

   
function SavePm(element) {
   
    var raw_id = $(element).data('id');

    var contrct_pdt = $(element).data('pdt');

    var visitingDates = [];
    var engineers = [];

    var pm_ids = [];

    $('.picker' + raw_id).each(function() {

        var visiting_date = $(this).val(); 

        var pm_id = $(this).data('id'); 

        visitingDates.push(visiting_date);

        pm_ids.push(pm_id);
    });

    $('.engineer' + raw_id).each(function() {

      var engineer = $(this).val();  
      engineers.push(engineer);

    });

    var data = {
        _token: '{{ csrf_token() }}',  
        visiting_dates: visitingDates,
        engineers: engineers,
        pm_ids : pm_ids,
        contract_product_id: contrct_pdt,
        class_id : raw_id  
    };

    console.log(data);

    $.ajax({
        url: '{{ route('staff.assignPmdetails') }}', 
        type: 'POST',
        data: data,
        success: function(response) {
           
            if (response.success) {
               
                  // $('.picker' + raw_id).each(function() {

                  //   $(this).prop('disabled', true)

                  // });

                  // $('.engineer' + raw_id).each(function() {

                  //   $(this).prop('disabled', true)

                  // });
                  // $('.raw' + raw_id).prop('disabled', true);

                  // $('#editbutton' + raw_id).show();

                  // $('.saveshow' + raw_id).hide();

                  if (response.class_id && response.diff_color) {
                      console.log(response.class_id);
                      console.log(response.diff_color);
                      $.each(response.diff_color, function(k, v) {
                          var selector = '.status' + response.class_id + k;
                          var $elements = $(selector);
                          if ($elements.length > 0) {
                        
                              $elements.each(function() {
                                  this.style.setProperty('background-color', v, 'important');
                              });
                          } 
                      });
                  }
                
        
                  $('#success_pm').text('PM data has been saved successfully!');

                $('.alert-success').show().addClass('show');  
                
                setTimeout(function() {
                    $('.alert-success').removeClass('show').fadeOut(); 
                }, 5000);


            } else {
                
                alert('Failed to save PM data. Please try again.');
            }
        },
        error: function(xhr, status, error) {
          
            console.error("AJAX error:", error);
            alert('There was an error while saving the data.');
        }
    });
}




</script>


@endsection

