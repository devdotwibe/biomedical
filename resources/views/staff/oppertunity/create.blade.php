@extends('staff/layouts.app')

@section('title', 'Add Opportunity')

@section('content')


    <section class="content-header">
        <h1>
            Add Opportunity 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('staff/list_oppertunity') }}">Manage Opportunity</a></li>
            <li class="active">Add Opportunity</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col">
                <!-- general form elements -->
                <div class="box box-primary">
                    <!--            <div class="box-header with-border">
                                                  <h3 class="box-title">Change Password</h3>
                                                </div>-->
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

                    <div class="prdt-cret-box">
                    <form role="form" name="frm_oppertunity" id="frm_oppertunity" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        {{-- {{ dd($permission) }} --}}
                        <div class="box-body">
                            <input type="hidden" id="service_id" name="service_id" class="form-control"
                                value=" {{ $service }} ">

                            <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                <label for="name">Opportunity Reference No*</label>
                                <input type="text" id="op_ref" name="op_ref" class="form-control"
                                    value="{{ $op_ref }}" readonly="">
                                <span class="error_message" id="op_ref_message" style="display: none">Field is
                                    required</span>
                            </div>
                            @php
                                $opprbio_create = optional($permission)->opperbio_create;
                                $opprbec_create = optional($permission)->opperbec_create;
                                $opprmsa_create = optional($permission)->oppermsa_create;
                            @endphp

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label>Type*</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    @if ($opprmsa_create == 'create')
                                    <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Contract</option>
                                    @endif
                                    @if ($opprbio_create == 'create' || $opprbec_create == 'create')
                                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Sales</option>
                                    @endif
                                  
                                </select>
                                <span class="error_message" id="type_message" style="display: none">Field is required</span>
                            </div>

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4" id="companynames" @if(old('type') != 1) style="display: none" @endif>
                                <label>Select Company</label>
                                <select id="company_type" name="company_type" class="form-control">
                                    <option value="">---Select Type---</option>
                                    @foreach ($company ?? [] as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('company_type') == $item->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error_message" id="company_type_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <div class="form-group col-lg-2 col-md-3 col-sm-4" id="service_type_div"
                                @if (old('type') != 2) style="display: none" @endif>
                                <label>Service Type*</label>
                                <select name="service_type" id="service_type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    <option value="AMC" {{ old('service_type') == 'AMC' ? 'selected' : '' }}>AMC</option>
                                    <option value="CMC" {{ old('service_type') == 'CMC' ? 'selected' : '' }}>CMC</option>
                                    <option value="Warranty" {{ old('service_type') == 'Warranty' ? 'selected' : '' }} >
                                        Warranty</option>
                                    <option value="HBS" {{ old('service_type') == 'HBS' ? 'selected' : '' }} disabled>HBS
                                    </option>
                                </select>
                                <span class="error_message" id="service_type_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <!-- <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                                      <label for="name">Opportunity Name*</label>
                                                      <input type="text" id="op_name" name="op_name" class="form-control" placeholder="Opportunity Name" value="{{ old('op_name') }}" >
                                                      <span class="error_message" id="op_name_message" style="display: none">Field is required</span>
                                                    </div> -->

                            <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                <label for="name">State*</label>
                                <select id="state" name="state" class="form-control" onchange="change_state()">
                                    <option value="">Select State</option>

                                    @foreach ($state as $values)
                                        <option value="{{ $values->id }}"
                                            @if (old('state') == $values->id) selected @endif>{{ $values->name }}</option>
                                    @endforeach

                                </select>
                                <span class="error_message" id="state_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label for="name">District*</label>
                                <select id="district" name="district" class="form-control" onchange="change_district()">

                                    <option value="">Select District</option>

                                    @foreach ($district as $values)
                                        <option value="{{ $values->id }}"
                                            @if (old('district') == $values->id) selected @endif>{{ $values->name }}</option>
                                    @endforeach

                                </select>
                                <span class="error_message" id="district_message" style="display: none">Field is
                                    required</span>
                            </div>


                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label>Customer Name*</label>
                                <select name="account_name" id="account_name" class="form-control">
                                    <option value="">-- Select Customer Name --</option>

                                </select>

                                <span class="error_message" id="account_name_message" style="display: none">Field is
                                    required</span>

                            </div>

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label>Engineer Name*</label>
                                <select name="engineer_name" id="engineer_name" class="form-control">
                                    <option value="">-- Select Engineer Name --</option>

                                    @foreach ($staff as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('engineer_name') == $item->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach

                                </select>

                                <span class="error_message" id="engineer_name_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label>Coordinator Name*</label>
                                <select name="coordinator_id" id="coordinator_id" class="form-control">
                                    <option value="">-- Select Coordinator Name --</option>
                                    @foreach ($staff as $item)
                                        <option value="{{ $item->id }}"
                                            @if (old('coordinator_id') == $item->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                <span class="error_message" id="coordinator_id_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <div class="form-group  col-lg-2 col-md-3 col-sm-4">
                                <label>Deal Stage*</label>
                                <select name="deal_stage" id="deal_stage" class="form-control">

                                    <option value="">-- Select Deal stage --</option>

                                    <option value="0" {{ old('deal_stage') == '0' ? 'selected' : '' }}>Lead
                                        Qualified/Key Contact Identified</option>
                                    <option value="1" {{ old('deal_stage') == '1' ? 'selected' : '' }}>Customer needs
                                        analysis</option>
                                    <option value="2" {{ old('deal_stage') == '2' ? 'selected' : '' }}>Clinical and
                                        technical presentation/Demo</option>
                                    <option value="3" {{ old('deal_stage') == '3' ? 'selected' : '' }}>CPQ(Configure,
                                        Price, Quote)</option>
                                    <option value="4" {{ old('deal_stage') == '4' ? 'selected' : '' }}>Customer
                                        Evaluation</option>
                                    <option value="5" {{ old('deal_stage') == '5' ? 'selected' : '' }}>Final
                                        Negotiation</option>

                                    {{-- <option value="6">Closed-Lost</option>
                                    <option value="7">Closed-Cancel</option>
                                    <option value="8">Closed Won - Implement</option> --}}
                                </select>
                                <span class="error_message" id="deal_stage_message" style="display: none">Field is
                                    required</span>
                            </div>

                            <div class="form-group col-lg-2 col-md-3 col-sm-4" id="order_date_div">
                                <label for="name">Es.Order Date*</label>
                                <input type="text" id="order_date" onchange="change_order_date(this.value)" readonly
                                    name="order_date" value="{{ old('order_date') }}" class="form-control"
                                    placeholder="Es.Order Date">
                                <span class="error_message" id="order_date_message" style="display: none">Field is
                                    required</span>
                            </div>



                            <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                <label id="es_sales_date" for="name">
                                    @if (old('type') !== 2)
                                        Es.Sales
                                    @else
                                        Es.Contract
                                    @endif Date*
                                </label>
                                <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date') }}"
                                    class="form-control" placeholder="Es.Sales Date" disabled readonly>
                                <span class="error_message" id="sales_date_message" style="display: none">Field is
                                    required</span>
                            </div>

                            {{-- <div class="form-group col-lg-2 col-md-3 col-sm-4" style="display:none">
                            <label for="start_date">Start Date*</label>
                            <input type="text" id="start_date" name="start_date" value="{{ old('start_date')}}" class="form-control" placeholder="Start Date">
                            <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
                            </div>
                            <div class="form-group col-lg-2 col-md-3 col-sm-4" style="display:none">
                            <label for="end_date">End Date*</label>
                            <input type="text" id="end_date" name="end_date" value="{{ old('end_date')}}" class="form-control" placeholder="End Date">
                            <span class="error_message" id="end_date_message" style="display: none">Field is required</span>
                            </div> --}}



                            <div class="form-group col-lg-2 col-md-3 col-sm-4 {{old('type')}}" id="order_forcast_div"  @if (old('type') != 1) style="display: none" @endif>

                                <label>Order Forcast Category*</label>
                                <select name="order_forcast" id="order_forcast" class="form-control">
                                    <option value="">-- Select Deal stage --</option>

                                    <option value="0" {{ old('order_forcast') == '0' ? 'selected' : '' }}>Unqualified
                                    </option>
                                    <option value="1" {{ old('order_forcast') == '1' ? 'selected' : '' }}>Not
                                        addressable</option>
                                    <option value="2" {{ old('order_forcast') == '2' ? 'selected' : '' }}>Open
                                    </option>
                                    <option value="3" {{ old('order_forcast') == '3' ? 'selected' : '' }}>Upside
                                    </option>
                                    <option value="4" {{ old('order_forcast') == '4' ? 'selected' : '' }}>Committed
                                        w/risk</option>
                                    <option value="5" {{ old('order_forcast') == '5' ? 'selected' : '' }}>Committed
                                    </option>

                                </select>

                                <span class="error_message" id="order_forcast_message" style="display: none">Field is
                                    required</span>
                            </div>

                            {{-- <div class="form-group col-lg-2 col-md-3 col-sm-4" id="amount_div">
                            <label for="name">Amount</label>
                            <input type="text" id="amount" name="amount" value="{{ old('amount')}}" class="form-control" placeholder="Enter amount">
                            </div> --}}

                            <input type="hidden" name="amount" value="0">

                            <div class="form-group col-lg-2 col-md-3 col-sm-4 {{old('type')}}" id="support_div"  @if (old('type') != 1) style="display: none" @endif>
                                <label>Support</label>
                                <select name="support" id="support" class="form-control">

                                    <option value="">-- Select Support --</option>
                                    <option value="0" {{ old('support') == '0' ? 'selected' : '' }}>Demo</option>
                                    <option value="1" {{ old('support') == '1' ? 'selected' : '' }}>Application/
                                        clinical support</option>
                                    <option value="2"{{ old('support') == '2' ? 'selected' : '' }}>Direct company
                                        support</option>
                                    <option value="3" {{ old('support') == '3' ? 'selected' : '' }}>Senior Engineer
                                        Support</option>
                                    <option value="4" {{ old('support') == '4' ? 'selected' : '' }}>Price deviation
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" cols="80" oninput="autoResize(this)"
                                    placeholder="Description">{{ old('description') }}</textarea>
                            </div>

                            <div class="box-footer col-md-12">
                            <input type="submit" class="mdm-btn submit-btn  " name="submit" value="Submit"
                                onclick="return validate_from()">
                            <button type="button" class="mdm-btn cancel-btn  "
                                onClick="window.location.href='{{ url('staff/list_oppertunity') }}'">Cancel</button>
                        </div>
                        </div>
                        <!-- /.box-body -->


                    </form>
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

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        $("#type").change(function() {
            var id = $("#type").val();
            if (id == 2) {
                $("#support_div").hide();
                $("#order_forcast_div").hide();
                $("#order_date_div").show();
                $("#amount_div").hide();
                $("#service_type_div").show();

                $('#start_date').parent().show()
                $('#end_date').parent().show()

                $('#es_sales_date').text('Es.Contract Date*')
            } else {
                $("#support_div").show();
                $("#order_forcast_div").show();
                $("#order_date_div").show();
                $("#amount_div").show();
                $("#service_type_div").hide();

                $('#start_date').parent().hide()
                $('#end_date').parent().hide()
                $('#es_sales_date').text('Es.Sales Date*')
            }

            if (id == 1) {
                $("#companynames").show();
            } else {
                $("#companynames").hide();
            }
        });

        function change_order_date(date_val) {
            console.log(date_val);
            /*$('#sales_date').datepicker({
                       
                        dateFormat:'yy-mm-dd',
                        minDate: 0  
                        
                    });*/
            //       $("#sales_date").datepicker("destroy");
            //       $('#sales_date').datepicker({
            //   // minDate: new Date(date_val),  
            //   dateFormat:'yy-mm-dd',
            //           minDate: 0     
            // });
        }


        function validate_from() {


            var account_name = $("#account_name").val();
            var engineer_name = $("#engineer_name").val();
            var deal_stage = $("#deal_stage").val();
            var order_date = $("#order_date").val();
            var sales_date = $("#sales_date").val();
            var order_forcast = $("#order_forcast").val();
            var type = $("#type").val();
            var company_type = $("#company_type").val();
            var op_ref = $("#op_ref").val();
            var state = $("#state").val();
            var district = $("#district").val();
            var service_type = $("#service_type").val();
            var coordinator_id = $("#coordinator_id").val();

            $("#account_name_message").hide();
            $("#engineer_name_message").hide();
            $("#deal_stage_message").hide();
            $("#order_date_message").hide();
            $("#sales_date_message").hide();
            $("#order_forcast_message").hide();
            $("#type_message").hide();
            $("#company_type_message").hide();
            $("#service_type_message").hide();
            $("#op_ref_message").hide();
            $("#state_message").hide();
            $("#district_message").hide();
            $('#coordinator_id_message').hide();

            if (type == "") {

                $("#type_message").show();
                return false;
            } 

            if (type == 1 && company_type == "") {

                $("#company_type_message").show();
                return false;
            } 
            if (type == 2 && service_type == "") {

                $("#service_type_message").show();
                return false;
            }

            if(service_type =='Warranty' && type==2)
            {
                if (account_name == "") {
                    $("#account_name_message").show();
                } 

                if (state == "") {
                $("#state_message").show();
                } 
                if (district == "") {
                    $("#district_message").show();
                } 
                console.log('warandy');

            }
            else
            {
                console.log('sales');

                if (account_name == "") {
                $("#account_name_message").show();
                } 

                if (engineer_name == "") {
                    $("#engineer_name_message").show();
                } 

                if (deal_stage == "") {
                    $("#deal_stage_message").show();
                }

                if (order_date == "") {
                    $("#order_date_message").show();
                }
                if (sales_date == "" && type == '1') {
                    $("#sales_date_message").show();
                } 
                
                if (order_forcast == "") {
                    $("#order_forcast_message").show();
                } 

                if (type == "") {
                    $("#type_message").show();
                } 
           
                if (op_ref == "") {
                    $("#op_ref_message").show();
                } 

                if (state == "") {
                    $("#state_message").show();
                }
                if (district == "") {
                    $("#district_message").show();
                }
                if (coordinator_id == "") {
                    $("#coordinator_id").show();
                }  
                
            }

           
            console.log(account_name + '--' + engineer_name + '--' + deal_stage + '**')
            console.log(sales_date + '-a-' + order_date + '-b-' + order_forcast + '-c-' + type + '-d-' + op_ref + '-e-' +
                state + '-f-' + district)

        if(service_type =='Warranty' && type==2)
        {
            if (account_name != '' && type != '' && state != '' && district != '')
                {

                $("#frm_oppertunity").submit();

                return true;
            } else {
                return false;
            }

        }
        else
        {
            if (account_name != '' && engineer_name != '' && deal_stage != '' && order_date != '' &&
                type != '' && op_ref != '' && (type == 1 && company_type != "" || type == 2 && service_type != "") &&
                state != '' && district != '' && coordinator_id  != '') {

                $("#frm_oppertunity").submit();

                return true;
            } else {
                return false;
            }
        }

        }
        $('#account_name').select2();
        $('#engineer_name').select2();
        $('#coordinator_id').select2();
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $('#order_date').datepicker({
            dateFormat: 'dd-mm-yy',
            changeYear:true,
            changeMonth:true,
            onSelect: function(selectedDate) {
            
                $('#sales_date').datepicker('option', 'minDate', selectedDate);
                $('#sales_date').prop('disabled', false);
            }
        });

        $('#sales_date').datepicker({
            dateFormat: 'dd-mm-yy',
            changeYear:true,
            changeMonth:true,
            beforeShow: function(input, inst) {
                if ($('#order_date').val() === '') {
                    $(input).prop('disabled', true); 
                }
            }
        });

        $('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            changeYear:true,
            changeMonth:true,
            minDate: 0

        });
        $('#end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            changeYear:true,
            changeMonth:true,
            minDate: 0

        });
    </script>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

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

    <script type="text/javascript">
        @if (!empty(old('state')))

            change_state();
        @endif

        function change_state() {
            var state_id = $("#state").val();
            var url = APP_URL + '/staff/change_state';
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data: {
                    state_id: state_id,
                },
                success: function(data) {
                    var proObj = JSON.parse(data);
                    states_val = '';

                    var district = "{{ old('district', '') }}";

                    states_val += '<option value="">Select District</option>';
                    for (var i = 0; i < proObj.length; i++) {

                        var selected = '';

                        if (district == proObj[i]["id"]) {

                            selected = 'selected';

                        }

                        states_val += '<option value="' + proObj[i]["id"] + '" ' + selected + ' >' + proObj[i][
                            "name"
                        ] + '</option>';

                    }
                    $("#district").html(states_val);

                    @if (!empty(old('district')))

                        change_district();
                    @endif

                }
            });

        }

        function change_district() {
    var district_id = $("#district").val();
    var state_id = $("#state").val();
    var type = $("#type").val();

    var url = APP_URL + '/staff/get_client_use_state_district';
    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data: {
            district_id: district_id,
            state_id: state_id,
            type: type
        },
        success: function(data) {
            var proObj = JSON.parse(data);

            var customer = "{{ old('account_name', '') }}";

            proObj.sort(function(a, b) {
                return a.business_name.localeCompare(b.business_name);
            });

            var states_val = '';
            states_val += '<option value="">Select Customer</option>';

            for (var i = 0; i < proObj.length; i++) {
                var selected = '';

                if (customer == proObj[i]["id"]) {
                    selected = 'selected';
                }
                states_val += '<option value="' + proObj[i]["id"] + '" ' + selected + '>' + proObj[i]["business_name"] + '</option>';
            }

            $("#account_name").html(states_val);
        }
    });
}
    </script>

<script>
    window.onload = function() {
        var dropdowns = ['engineer_name', 'account_name', 'coordinator_id'];

        dropdowns.forEach(function(dropdownId) {
            var select = document.getElementById(dropdownId);
            if (select) {
                var options = Array.from(select.options);
                options.sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                });

                options.forEach(function(option) {
                    select.appendChild(option);
                });
            }
        });
    };
</script>


@endsection
