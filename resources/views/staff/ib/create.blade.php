@extends('staff/layouts.app')

@section('title', 'Add IB')

@section('content')

    <section class="content-header">
        <h1>
            Add IB
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.ib-index') }}">Manage IB</a></li>
            <li class="active">Add IB</li>
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

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif


                    @if (session()->has('error_message'))
                        <div class="alert alert-danger alert-dismissible">
                            {{ session()->get('error_message') }}
                        </div>
                    @endif

                    <p class="error-content alert-danger">
                        {{ $errors->first('name') }}
                        {{ $errors->first('image_name') }}
                    </p>
                    @php
                        $inRefno = 'IB-' . rand(1000, 100000);
                    @endphp

                    <div class="prdt-cret-box">
                        <form role="form" name="frm_ib" id="frm_ib" method="post"
                            action="{{ route('staff.ib-store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body row">

                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label for="name">Internal Ref No*</label>
                                    <input type="text" id="internal_ref_no" name="internal_ref_no"
                                        value="{{ $inRefno }}" class="form-control" placeholder="" readonly>
                                    <span class="error_message" id="name_message" style="display: none">Field is
                                        required</span>
                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label for="name">External Ref No</label>
                                    <input type="text" id="external_ref_no" name="external_ref_no" value=""
                                        class="form-control" placeholder="External Ref No">
                                    <span class="error_message" id="external_ref_no_message" style="display: none">Field is
                                        required</span>
                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label>Select Customer*</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($users->sortBy('business_name') as $user)
                                            <option value="{{ $user->id }}" {{ app('request')->input('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->business_name }} ({{ $user->district_name }})                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error_message" id="user_id_message" style="display: none">Field is required</span></br></br>
                                </div>
                                

                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label>Select Department</label>
                                    <select class="form-control" name="department_id" id="department_id">
                                        <option value="">-- Select Department --</option>
                                        @foreach ($categories->sortBy('name') as $department)
                                            <option value="{{ $department->id }}" {{ app('request')->input('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error_message" id="department_id_message" style="display: none">Field is required</span></br></br>
                                </div>
                                
                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label>Select Equipment*</label>
                                    <select class="form-control selectpicker" name="equipment_id" id="equipment_id">
                                        <option value="">-- Select Equipment --</option>
                                        @foreach ($products->sortBy('name') as $equipment)
                                            <option value="{{ $equipment->id }}" {{ app('request')->input('equipment_id') == $equipment->id ? 'selected' : '' }}>
                                                {{ $equipment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                
                                    <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
                                </div>
                                

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="name">Equipment Serial No</label>

                                    <input type="text" id="equipment_serial_no" name="equipment_serial_no" value=""
                                        class="form-control" placeholder="Equipment Serial No">

                                    <span class="error_message" id="equipment_serial_no_message" style="display: none">Field
                                        is required</span>

                                    <span class="error_message" id="exist_id_message" style="display: none">The equipment
                                        serial number already exist.</span>

                                    @if ($errors->has('equipment_serial_no'))
                                        <span class="error_message"> {{ $errors->first('equipment_serial_no') }}</span>
                                    @endif

                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="name">Equipment Model No</label>
                                    <input type="text" id="equipment_model_no" name="equipment_model_no" value=""
                                        class="form-control" placeholder="Equipment Model No">
                                    <span class="error_message" id="equipment_model_no_message" style="display: none">Field
                                        is required</span>

                                </div>
                                
                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label>Select Equipment Status</label>
                                    <select class="form-control" name="equipment_status_id" id="equipment_status_id">
                                        <option value="">-- Select Equipment Status --</option>
                                        @foreach ($equipment_status->sortBy('name') as $equipment_stat)
                                            <option value="{{ $equipment_stat->id }}" {{ app('request')->input('equipment_status_id') == $equipment_stat->id ? 'selected' : '' }}>
                                                {{ $equipment_stat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                

                                <div class="form-group col-lg-2 col-md-3 col-sm-4">
                                    <label>Select Staff</label>
                                    <select class="form-control" name="staff_id" id="staff_id">
                                        <option value="">-- Select Staff --</option>
                                        @foreach ($staffs->sortBy('name') as $staff)
                                            <option value="{{ $staff->id }}" {{ app('request')->input('staff_id') == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error_message" id="staff_id_message" style="display: none">Field is required</span></br></br>
                                </div>
                                

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="name">Installation Date*</label>
                                    <input type="text" id="installation_date" name="installation_date" value=""
                                        class="form-control" placeholder="Select Date" readonly>
                                    <span class="error_message" id="installation_date_message"
                                        style="display: none">Field is required</span>
                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="name">Warranty End Date</label>
                                    <input type="text" id="warrenty_end_date" name="warrenty_end_date" value=""
                                        class="form-control" placeholder="Select Date" readonly>
                                    <span class="error_message" id="warrenty_end_date_message"
                                        style="display: none">Field is required</span>
                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="supplay_order">Supply Order No.</label>
                                    <input type="text" id="supplay_order" name="supplay_order" value=""
                                        class="form-control" placeholder="Equipment Model No">
                                    <span class="error_message" id="supplay_order_message" style="display: none">Field is
                                        required</span>
                                </div>

                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="invoice_number">Invoice No.</label>
                                    <input type="text" id="invoice_number" name="invoice_number" value=""
                                        class="form-control" placeholder="Equipment Model No">
                                    <span class="error_message" id="invoice_number_message" style="display: none">Field
                                        is required</span>
                                </div>


                                <div class="form-group col-lg-2 col-md-3 col-sm-4 ">
                                    <label for="invoice_date">Invoice date</label>
                                    <input type="text" id="invoice_date" name="invoice_date" value=""
                                        class="form-control" placeholder="Select Date" readonly>
                                    <span class="error_message" id="invoice_date_message" style="display: none">Field is
                                        required</span>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="name">Description*</label>
                                    <textarea id="description" name="description" class="form-control" placeholder=""></textarea>
                                    <span class="error_message" id="description_message" style="display: none">Field is
                                        required</span>
                                </div>

                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" class="btn btn-primary" onclick="validate_from()">Submit</button>
                                <button type="button" class="btn btn-danger"
                                    onClick="window.location.href='{{ route('staff.ib-index') }}'">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#installation_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{ intval(date('Y')) + 40 }}",
            // minDate: 0  
        });
        $('#warrenty_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{ intval(date('Y')) + 40 }}",
            //minDate: 0  
        });
        $('#invoice_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{ intval(date('Y')) + 40 }}",
            //minDate: 0  
        });
    </script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#equipment_serial_no, #user_id').on('keyup change', function() {

                var serialNo = $('#equipment_serial_no').val();

                var user_id = $('#user_id').val();

                console.log(user_id);

                if (user_id != null && user_id != "" && serialNo != null && serialNo != "") {
                    $('#user_id_message').hide();
                    handleKeyUp(serialNo, user_id);

                } else {
                    $('#user_id_message').show();
                }

            });

            function handleKeyUp(serialNo, user_id) {

                console.log('Serial No:', serialNo);

                var url = APP_URL + '/staff/check_equp_no';
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        serial_no: serialNo,
                        user_id: user_id,
                    },
                    success: function(res) {

                        console.log(res);
                        if (res.exists) {
                            $('#exist_id_message').show();
                        } else {
                            $('#exist_id_message').hide();
                        }
                    }

                });


            }
        });


        $(document).ready(function() {

            $("#user_id").select2({
                enableFiltering: true,
            });
            $("#department_id").select2({
                enableFiltering: true,
            });
            $("#equipment_id").select2({
                enableFiltering: true,
            });
            $("#staff_id").select2({
                enableFiltering: true,
            });

        });
    </script>

    <script type="text/javascript">
        function validate_from() {

            var external_ref_no = $("#external_ref_no").val();
            var user = $("#user_id").val();
            var department = $("#department_id").val();
            var equipment = $("#equipment_id").val();
            var equipment_serial = $("#equipment_serial_no").val();
            var equipment_model = $("#equipment_model_no").val();
            var equipment_status = $("#equipment_status_id").val();
            var installation_date = $("#installation_date").val();
            var warrenty_end_date = $("#warrenty_end_date").val();
            var description = $("#description").val();


            if (user == "") {
                $("#user_id_message").show();
            } else {
                $("#user_id_message").hide();
            }

            if (equipment == "") {
                $("#equipment_id_message").show();
            } else {
                $("#equipment_id_message").hide();
            }
            if (equipment_serial == "") {
                $("#equipment_serial_no_message").show();
            } else {
                $("#equipment_serial_no_message").hide();
            }
            if (equipment_model == "") {
                $("#equipment_model_no_message").show();
            } else {
                $("#equipment_model_no_message").hide();
            }
            if (equipment_status == "") {
                $("#equipment_status_id_message").show();
            } else {
                $("#equipment_status_id_message").hide();
            }
            if (installation_date == "") {
                $("#installation_date_message").show();
            } else {
                $("#installation_date_message").hide();
            }
            if (description == "") {
                $("#description_message").show();
            } else {
                $("#description_message").hide();
            }

            if (user != '' && equipment != '' && equipment_serial != '' && equipment_model != '' && installation_date !=
                '' && description != '') {
                $("#frm_ib").submit();
            }


        }
    </script>
@endsection
 