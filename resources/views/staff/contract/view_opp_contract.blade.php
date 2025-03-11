@extends('staff/layouts.app')

@section('title', 'Create Oppertunity Contract')

@section('content')

<section class="content-header">
  <h1>
    View Opportunity Contract
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

            
              <p class="error-content alert-danger"></p>
              <form method="post" action="{{ route('staff.oppertunity_store') }}" id="contactform" enctype="multipart/form-data">
                  @csrf
                  <div class="box-body">
                   
                    <div class="row">


                    <div class="form-group col-lg-2 col-md-3 col-sm-4">
                        <label for="name">Internal Ref No*</label>
                        <input type="text" id="internal_ref_no" name="internal_ref_no" value="{{$contract->in_ref_no}}" class="form-control" placeholder="" readonly>
                        <span class="error_message" id="name_message" style="display: none">Field is required</span>
                    </div>


                    <div class="form-group col-md-2">

                        <label>Customer Name</label>

                        <input type="text" class="form-control" name="user_name" id="user_name" value="{{optional($oppertunity->customer)->business_name}}" readonly>
                        
                        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{optional($oppertunity)->user_id}}">

                        <div class="alert alert-danger" id="user_id_error" style="display:none"> </div>

                    </div>
                    
                      <div class="form-group col-md-2">
                        <label >Contact Person</label>

                        <input type="text" class="form-control" name="contactPersons" id="contactPersons" value="{{optional($contactPersons)->name}}" readonly>
                        
                        <span class="error_message" id="contact_person_id_message" style="display:none"> </span>
                      </div>

                      <input type="hidden" name="oppertunity_id" id="oppertunity_id" value="{{$oppertunity->id}}"> 

                      <input type="hidden" name="msa_contract_id" id="msa_contract_id" value="{{$msacontract->id}}"> 

                      
                      <div class="form-group col-md-2">

                        <label>Third Party Contract Reference</label>

                        <input type="text" class="form-control" readonly name="contract_reference" id="contract_reference" value="{{old('contract_reference',$contract->contract_reference)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Reference</label>

                        <input type="text" class="form-control" readonly name="payment_reference" id="payment_reference" value="{{old('payment_reference',$contract->payment_reference)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Payment Amount</label>

                        <input type="text" class="form-control" readonly name="payment_amount" id="payment_amount" value="{{old('payment_amount',$contract->payment_amount)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>TDS</label>

                        <input type="text" class="form-control" readonly name="tds" id="tds" value="{{old('tds',$contract->tds)}}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Attachment</label>

                        @if(!empty($contact->attachment))
                                        
                            @if (strtolower($extension) == 'pdf' || strtolower($extension) == '.xlxs')

                            <a href="{{ asset('storage/app/public/attachments/'.$contact->attachment) }}" target="_blank" ><i class="fa fa-download"></i>

                            @else

                            <img src="{{ asset('storage/app/public/attachments/'.$contact->attachment) }}" >

                            @endif

                        @endif

                        
                    </div>

                    
                    <div class="form-group col-md-2">

                        <label>Start Date*</label>

                        <input type="text" id="contract_start_date" name="contract_start_date" value="{{ $contract->contract_start_date ? \Carbon\Carbon::parse(str_replace(':', '-', $contract->contract_start_date))->format('d-m-Y') : '' }}" class="form-control" placeholder="Start Date" readonly>

                         <span class="error_message" id="contract_start_date_error" style="display:none"> </span>

                    </div>

                    <div class="form-group col-md-2">

                        <label>End Date*</label>

                        <input type="text" id="contract_end_date" name="contract_end_date" value="{{ $contract->contract_end_date ?  \Carbon\Carbon::parse(str_replace(':', '-', $contract->contract_end_date))->format('d-m-Y') : '' }}" class="form-control" onchange="CheckEndDate(this)"  placeholder="End Date" readonly>
                            
                        <span class="error_message" id="contract_end_date_error" style="display:none"> </span>
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Customer Order no</label>

                        <input type="text" readonly class="form-control" name="customer_order" id="customer_order" value="{{ $contract->customer_order }}">
                        
                    </div>

                    <div class="form-group col-md-2">

                        <label>Contact type*</label>

                        <input type="text" readonly class="form-control" name="contract_type" id="contract_type" value="{{optional($contract)->contract_type}}">

                        <span class="error_message" id="contract_type_common_message" style="display: none">Field is
                            required</span>
                        
                    </div>


                </div>


                    <table class="table table-bordered opper-table" border="0" style="border-collapse: collapse;">

                        <tr style="background-color:#999; color:#fff;" id="table_head">
                            
                            <th >Sl. No.</th>

                            <th>Equi. Name</th>

                            <th >Category</th>

                            <th>Modal No.</th>

                            <th>Serial No.</th>

                            <th>Machine Status</th>

                            <th>Installation Date</th>

                            <th >No of PMs</th>

                            @php

                            $total_rows=0

                            @endphp

                            @foreach($productDetails as $index => $item)

                                @php
                                    if ($item->no_of_pm > $total_rows) {
                                        $total_rows = $item->no_of_pm;
                                    }

                                @endphp

                            @endforeach

                            @for($i=0;$i<$total_rows; $i++)

                                <th>PM{{$i+1}}</th>

                            @endfor

                            <th id="pm_no_track">Revenue</th>

                            <th >Amount</th>
                            <th >Tax</th>
                            <th >Total (Rs.)</th>
        
                          
                        </tr>

                        @php 

                            $total = 0.0; 

                            $grand_total =0;

                        @endphp

                        @foreach($productDetails as $key=>$value)

                            @php 
                              
                                $calamount = $value->amount;

                                $cal_tax = $value->tax;

                                $grand_tax_total = $calamount +  $cal_tax;

                                $grand_total += $grand_tax_total;

                            @endphp


                        <tr>
                            <td >{{ ++$key }}</td> 
                            <td ><input type="text" name="equpment_type[]" id="equpment_type{{$key}}"  class="form-control " value="{{ old('equpment_type.'.$key, optional($value->equipment)->name) }}" readonly></td>

                            <td >{{optional($value->equipment)->category_name}}</td>

                            <td><input type="text" name="equipment_model_no[]" id="equipment_model_no_{{$key}}"  class="form-control " value="{{old('equipment_model_no.'.$key,optional($value)->equipment_model_no)}}" readonly></td>

                            <td><input type="text" name="equipment_serial_no[]" id="equipment_serial_no_{{$key}}"  class="form-control " value="{{ old('equipment_serial_no.'.$key, optional($value)->equipment_serial_no) }}" readonly></td>

                           
                            <td><input type="text" name="machine_status_id[]" id="machine_status_id_{{$key}}"  class="form-control " value="{{ old('machine_status_id.'.$key, optional($value->productMachineStatus)->name) }}" readonly></td>

                            <td><input type="text" name="installation_date[]" id="installation_date_{{$key}}"  class="form-control " value="{{ \Carbon\Carbon::parse($value->installation_date)->format('d-m-Y') }}" readonly></td>

                            <td>
                                <input type="text" id="no_of_pm_{{$key}}" name="no_of_pm[]" value="{{old('no_of_pm.'.$key,optional($value)->no_of_pm)}}" readonly
                                class="form-control  " placeholder="No of PMs" readonly>

                                <span class="error_message" id="no_of_pm_error_{{$key}}" style="display:none"> </span>
                            </td>


                            {{-- <------------------------------------------------section pm ---------------------------------------------> --}}

                                    @php
                                      
                                      $pmDates = json_decode($value->pm_dates??"[]", true) ?? [];

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

                                                <td>
                                                  
                                                    <input  type="text" name="visiting_date[]"  id="visiting_date-{{$count}}-{{ $index }}-{{$key}}" class="form-control"  value="{{ $item->productPM("PM".($count+1))->visiting_date  && !str_contains($item->productPM("PM".($count+1))->visiting_date, 'NaN') ? \Carbon\Carbon::parse($item->productPM("PM".($count+1))->visiting_date)->format('d-m-Y') : '' }}" readonly>

                                                </td>

                                            @endif                                              

                                          @endforeach

                                        @if($flag)

                                                @for($i=0;$i<$add_count; $i++)

                                                <td>&nbsp;</td>
                                                
                                                @endfor

                                        @endif

                                @endif

                                


                            {{-- <----------------------------------------------------------end of section ------------------------------------------> --}}

                            
                            <td id="before_pm{{$key-1}}">
                                <input type="text" name="revanue[]" value="{{ old('revanue.'.$key,optional($value)->revanue)}}" class="form-control revenue_cal" placeholder="Revenue" readonly>
                                    <div class="alert alert-danger" id="revanue_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td >
                                <input type="text" name="amount[]" value="{{ $value->amount}}" class="form-control amount" placeholder=" amount" readonly>
                                    <div class="alert alert-danger" id="amount_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td>
                                <input type="text" name="tax[]" value="{{ $value->tax}}" class="form-control tax" placeholder=" tax" readonly>
                                    <div class="alert alert-danger" id="tax_error_{{$key}}" style="display:none"> </div>
                            </td>

                            <td>
                                <input type="text" name="total[]" value="{{ $grand_tax_total }}" class="form-control total" placeholder=" total" readonly>
                                    <div class="alert alert-danger" id="total_error_{{$key}}" style="display:none"> </div>
                            </td>

                            

                        </tr>
                        
                
                        @endforeach

                        <tr>
                            
                            @php 

                            $colpan = 8;

                            $total_col = $colpan + $total_rows;

                            @endphp

                            <td  colspan="{{$total_col}}" id="spancolmn">&nbsp;</td>

                            <td><strong>&nbsp;</strong></td>
            
                            <td >&nbsp;&nbsp;</td>
             
                            <td><strong>&nbsp;TOTAL </strong></td>

        
                            <td ><strong id="grand-total"> {{ number_format($grand_total, 2) }}</strong>&nbsp;&nbsp;</td>
                          
                        </tr>

                     </table>

                
                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                      <button type="button" class="btn btn-danger"
                          onClick="window.location.href='{{ route('staff.pm_order.index') }}'">Cancel</button>
                  </div>
              </form>

            {{-- <-------------------------pm details table ------------------------------> --}}

            @if(!empty($pmdetails) && count($pmdetails) > 0)

                <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">

                    <thead>

                        <tr class="headrole">

                            <th>No.</th>
                            <th>ref no</th>

                            <th>Customer Name</th>

                            <th>Equipment</th>
                            <th>Serial no</th>
                            <th>PM no.</th>
                            <th>PMt (Date)</th>
                            <th>Desired Date</th>
                            <th>Created Date</th>
                            <th>Engineer Name</th>
                            <th>Contract Person</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($pmdetails as $index => $item)

                            <tr>

                                <th>{{ $index+1 }}</th>

                                <th>{{ $item->in_ref_no }}</th>
                                <th>{{ optional($item->service->serviceUser)->business_name ??"" }}</th>
                                <th>{{ optional($item->equipment)->name }}</th>
                                <th>{{ optional($item->contractproduct)->equipment_serial_no }}</th>
                               
                                @php 
                                     $pmDates = json_decode($item->contractproduct->pm_dates ?? "[]", true) ?? [];

                                        $dates_pms = "";

                                        $pm_index = "";

                                        if (!empty($pmDates)) {
                                            foreach ($pmDates as $index => $date) {
                                                if ($item->pm == "PM" . ($index + 1)) {
                                             
                                                    $dates_pms = date('d-m-Y', strtotime($date));
                                                    $pm_index = $index+1;
                                                }

                                                    
                                            }

                                        }

                                @endphp

                                <th>{{ ' (PM' . ($pm_index) . ')' }}</th>

                                <th>{{ $dates_pms }}</th> 

                                @php

                                    $pmDates = json_decode($item->contractproduct->pm_dates ?? "[]", true) ?? [];

                                    $dates_desired = $item->visiting_date;

                                    $dates_pms = "";

                                    if (!empty($pmDates)) {
                                        foreach ($pmDates as $index => $date) {
                                            if ($item->pm == "PM" . ($index + 1)) {
                                                $dates_pms = $date;
                                            }

                                        }

                                    }

                                    $diff = false;

                                    if ($dates_desired === $dates_pms) {
                                        $diff = true;
                                    }

                                    if ($diff) {

                                        $dates_desired ="";

                                    } 

                                @endphp

                                <th>{{ $dates_desired }}</th>

                                <th>{{ $item->created_at->format('d-m-Y') }}</th>

                                <th>{{optional($item->engineer)->name}}</th>

                                <th> {{ optional($item->service->serviceContactPerson)->name . ' , ' . optional($item->service->serviceContactPerson)->mobile }}</th>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @endif


          </div>

      </div>
  </div>
</section>

@endsection

@section('scripts')


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
        var iblist=@json($iblist);
      
        function CheckEndDate(element) {

            console.log('index');

            const startDateId = `#contract_start_date`;
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


       $(document).ready(function() {

        $('#submit_contract').prop('disabled',false);
        
            $('#contactform').submit(function(event) {

                var isValid = true;
                var contract_start_date = $('#contract_start_date').val();

                var contract_end_date = $('#contract_end_date').val();

                var contact_person_id = $('#contact_person_id').val();

                var contract_type = $('#contract_type').val();

                
                if (!contract_start_date) {
                    isValid = false;
                    $('#contract_start_date_error').text('The field is required').show();
                    console.log('contract_start_date_error');
                }
                else
                {
                    $('#contract_start_date_error').hide();
                }

                if (!contract_end_date) {
                    isValid = false;
                    $('#contract_end_date_error').text('The field is required').show();
                    console.log('contract_end_date_error');
                }
                else
                {
                    $('#contract_end_date_error').hide();
                }
                
                if (!contract_type) {
                    isValid = false;
                    $('#contract_type_common_message').text('The field is required').show();
                    console.log('contract_type_common_message');
                }
                else
                {
                    $('#contract_type_common_message').hide();
                }


                if (!contact_person_id) {
                    isValid = false;
                    $('#contact_person_id_message').text('The field is required').show();
                    console.log('contact_person_id_message');
                }
                else
                {
                    $('#contact_person_id_message').hide();
                }



                 $('[name^="machine_status_id[]"]').each(function(k, v) {
                     k++;
                    if (!$(this).val()) {
                        isValid = false;
                        $(`#machine_status_id_error_${k}`).text('The field is required').show();
                        console.log(`machine_status_id_error_${k}`);
                    } else {
                        $(`#machine_status_id_error_${k}`).hide();
                    }
                });


                $('[name^="no_of_pm[]"]').each(function(k,v) {
                     k++;
                    if (!$(this).val()) {
                        isValid = false;
                        $(`#no_of_pm_error_${k}`).text('The field is required').show();
                        console.log(`no_of_pm_error_${k}`);
                      
                    }
                });

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












 