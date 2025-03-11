@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

    <section class="content-header">
        <h1>
            Manage {{ $serviceType->name }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage {{ $serviceType->name }}</li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="service-index">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="row">

                            <div class="col-lg-12 margin-tb">
                                @if ($serviceType->id != 2)
                                    <div class="pull-left">
                                        <a class="add-button " href="{{ route('staff.service-index', $serviceType->id) }}">
                                            Add {{ $serviceType->name }}</a>
                                    </div>
                                @endif
                                <div class="pull-left history-btn">
                                    <a class="add-button " target="_blank"
                                        href="{{ route('staff.service-response-history', $serviceType->id) }}"> History</a>
                                    <input type="hidden" id="service_id" value="{{ $serviceType->id }}">
                                </div>
                            </div>


                            @if ($serviceType->id == 1)

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                                    <label for="name">State</label>
                                    <select id="state_id" name="state" class="form-control"
                                        onchange="reloadTable('state')">
                                        <option value="">Select State</option>
                                        @foreach ($state as $item)
                                            <option value="{{ $item->id }}" id="state_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                                    <label for="name">District</label>
                                    <select id="district_id" name="district" class="form-control"
                                        onchange="reloadTable('district')">
                                        <option value="">Select District</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                                    <label for="name">Customer</label>
                                    <select id="account_name" name="account_name" class="form-control"
                                        onchange="reloadTable()">

                                        <option value="">Select Customer</option>

                                    </select>
                                </div>


                                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                                    <label for="name">Engineer</label>
                                    <select id="engineer" name="engineer" class="form-control" onchange="reloadTable()">
                                        <option value="">Select Enginner</option>
                                        @foreach ($engineers as $item)
                                            <option value="{{ $item->id }}" id="staff_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>


                                <div class="form-group col-md-2 col-sm-6 col-lg-2 time-period">

                                    <label for="name">Period</label>

                                    <input type="text" id="start_date" placeholder="From" class="time_picker"
                                        name="start_date" readonly onchange="reloadTable()">

                                    <input type="text" id="end_date" placeholder="To" class="time_picker"
                                        name="end_date" readonly onchange="reloadTable()">

                                </div>


                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Brand</label>

                                    <select id="brand" name="brand" class="form-control" onchange="reloadTable()">

                                        <option value="">Select Brand</option>

                                        @foreach ($brands as $item)
                                            <option value="{{ $item->id }}" id="brand_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                    <label for="name">Category</label>

                                    <select id="category" name="category" class="form-control" onchange="reloadTable()">

                                        <option value="">Select Category</option>

                                        @foreach ($categorys as $item)
                                            <option value="{{ $item->id }}" id="staff_{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            @endif

                        </div>


                        @if (session('success'))
                            <div class="alert alert-success alert-block fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        @if ($serviceType->id == 2)
                            <div class="service-btn-left">

                                <a class="pending-list btn btn-primary"
                                    href="{{ route('staff.service-create', 2) }}">All</a>

                                <a class="pending-list btn btn-primary"
                                    href="{{ route('staff.service-create', ['id' => 2, 'type' => 'pending']) }}">Pending</a>

                                <a class="technical-support btn btn-primary"
                                    href="{{ route('staff.service-create', ['id' => 2, 'type' => 'completed']) }}">Completed</a>

                            </div>
                        @endif

                        <div class="box-body">

                            <form name="dataForm" id="dataForm" method="post" action="">
                                @csrf
                                <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                                    <thead>
                                        <tr class="headrole">
                                            <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                                            <th>No.</th>
                                            
                                            
                                            @if ($serviceType->id != 2)

                                                <th>In Ref.No</th>

                                            @else
                                                <th>Ref.No</th>
                                                <th></th>

                                            @endif

                                            @if ($serviceType->id != 2)
                                                <th>district</th>

                                                <th>Ex Ref.No</th>
                                            @endif
                                            <th>Customer Name</th>
                                            <th>Contact Details</th>
                                            <th class="col-wide-0">Eqpt Name</th>
                                            <th>Eqpt Sr.No</th>
                                            <th>Eqpt Status</th>
                                            <th>Mac.cur Status</th>
                                            <th>Ser.Eng</th>

                                            @if ($serviceType->id == 2)
                                            <th>PM Date</th>
                                            @endif

                                            <th>Created By</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="alignCenter">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="modal fade" id="deleteService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="req-part">
                            <h3 class="modal-title">Do you want to delete ?</h3><br>
                            <form id="part-details" method="post" action="{{ route('staff.service-destroy') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="seviceId" name="seviceId" value="">
                                <input type="submit" class="btn btn-primary" name="submit" value="Yes">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection



    @section('scripts')

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {

                //  var service_id = $('#service_id').val();
                //var url = "{{ route('admin.service-create', '+service_id+') }}";
                //  var url = '{{ route('staff.service-create', ':id') }}';

                var url = '{{ request()->fullUrl() }}';

                //  url = url.replace(':id', service_id);
                //alert(url)
                var oTable = $('#cmsTable').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: {
                        url: url,

                        data: function(d) {

                            d.state = $('#state_id').val();
                            d.district = $('#district_id').val();
                            d.account_name = $('#account_name').val();
                            d.engineer = $('#engineer').val();
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                            d.brand = $('#brand').val();
                            d.category = $('#category').val();




                        },


                    },

                    initComplete: function(settings) {

                        var info = this.api().page.info();
                        var api = this.api();

                        if (info.pages > 1) {

                            $(".dataTables_paginate").show();
                        } else {
                            $(".dataTables_paginate").hide();

                        }

                        var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
                        $(".col-sm-7").append(searchInput);

                        if (info.pages > 1) {

                            searchInput.on('input', function() {

                                var searchValue = $(this).val().toLowerCase();

                                var pageNum = searchValue - 1;

                                api.page(pageNum).draw('page');
                            });
                        }


                        if (info.recordsTotal == 0) {

                            $(".dataTables_info").hide();
                        } else {
                            $(".dataTables_info").show();
                        }
                        },

                        createdRow: function(row, data, dataIndex) {

                        // $(row).find('td').each(function(i, e) {

                        //     $(e).attr('data-th', theader[i]);
                            
                        // });
                        },
                        drawCallback: function() {

                        },
                    columns: [{
                            "data": 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                       
                        @if ($serviceType->id != 2)

                            {
                                data: 'in_ref_no_corrective',
                                name: 'in_ref_no_corrective',
                                orderable: true,
                                searchable: true,
                            },

                        @else

                            {
                                data: 'in_ref_no',
                                name: 'in_ref_no',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: 'msa_ref_no',
                                name: 'msa_ref_no',
                                orderable: true,
                                searchable: true,
                                visible:false
                            },

                            

                        @endif

                        @if ($serviceType->id != 2)
                            {
                                data: 'district_id',
                                name: 'district_id',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                data: 'ex_ref_no',
                                name: 'ex_ref_no',
                                orderable: true,
                                searchable: true,
                            },
                        @endif 
                        {
                            "class": "mobile_view",
                            data: 'customer',
                            name: 'customer',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            data: 'contact_person',
                            name: 'contact_person',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'equipment_name',
                            name: 'equipment_name',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'equipment_serial_no',
                            name: 'equipment_serial_no',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'equipment_status',
                            name: 'equipment_status',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'machine_status',
                            name: 'machine_status',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            data: 'engineer',
                            name: 'engineer',
                            orderable: true,
                            searchable: true,
                        },
                        @if ($serviceType->id == 2)

                        {
                            data: 'visiting_date',
                            name: 'visiting_date',
                            orderable: true,
                            searchable: true,
                        },

                        @endif
                        {
                            "class": "mobile_view",
                            data: 'created_by',
                            name: 'created_by',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'created_at',
                            name: 'created_at',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            data: 'status',
                            name: 'status',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true,
                        }

                    ]


                });



            });
            $('#cmsTable').on('click', '.deleteItem', function() {
                $('#deleteService').modal('show');
                var id = $(this).attr('attr-service-id');
                $('#seviceId').val(id);
            });
        </script>




        <script>
            function reloadTable(element) {

                $('#cmsTable').DataTable().ajax.reload();

                if (element == 'state') {
                    change_state();

                    $("#account_name").empty();
                    $("#account_name").append('<option value="">Select Customer</option>');

                }
                if (element == 'district') {
                    change_district();
                }

            }

            function change_state() {
                var state_id = $("#state_id").val();
                var url = APP_URL + '/staff/service_change_state';
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data: {
                        state_id: state_id,
                        id: "{{ $serviceType->id }}"
                    },

                    success: function(data) {
                        var proObj = JSON.parse(data);
                        states_val = '';

                        states_val += '<option value="">Select District</option>';
                        for (var i = 0; i < proObj.length; i++) {

                            var selected = '';

                            states_val += '<option value="' + proObj[i]["id"] + '" ' + selected + ' >' + proObj[i][
                                "name"
                            ] + '</option>';

                        }
                        $("#district_id").html(states_val);

                    }
                });

            }

            function change_district() {
                var district_id = $("#district_id").val();
                var state_id = $("#state_id").val();
                var type = $("#type").val();

                var url = APP_URL + '/staff/service_get_client_use_state_district';
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data: {
                        district_id: district_id,
                        state_id: state_id,
                        type: type,
                        id: "{{ $serviceType->id }}"

                    },
                    success: function(data) {
                        var proObj = JSON.parse(data);

                        states_val = '';
                        states_val += '<option value="">Select Customer</option>';

                        for (var i = 0; i < proObj.length; i++) {
                            var selected = '';

                            states_val += '<option value="' + proObj[i]["id"] + '" ' + selected + '>' + proObj[i][
                                "business_name"
                            ] + '</option>';
                        }

                        $("#account_name").html(states_val);

                    }
                });

            }



            $(function() {

                $('#start_date').datepicker({

                    dateFormat: 'dd-mm-yy',
                    maxDate: 0,
                    changeYear:true,
                    changeMonth:true,

                });

                $('#end_date').datepicker({

                    dateFormat: 'dd-mm-yy',
                    maxDate: 0,
                    changeYear:true,
                    changeMonth:true,
                });
            });
        </script>


<script>
    window.onload = function() {
        var select = document.getElementById('engineer');
        var options = Array.from(select.options);
        
        options.sort(function(a, b) {
            return a.text.localeCompare(b.text);
        });
        
        options.forEach(function(option) {
            select.appendChild(option);
        });
    };
</script>

    @endsection
