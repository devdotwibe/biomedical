@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

    <section class="content-header">

        <h1>
            Manage MSA Contract 
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Manage Pm orders</li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box">


                        @if (session('success'))
                            <div class="alert alert-success alert-block fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <!-- /.box-header -->
                        <div class="box-body list-oprty-page">



                            <form name="dataForm" id="dataForm" method="post" action="">
                                @csrf
                                <input type="hidden" id="type" name="type" value="" class="form-control">


                                <div class="service-btn-left">
                                    <a class="all-list btn btn-primary hover-list" href="{{ route('staff.pm_order.index') }}" >All <span>All MSA Contract</span> </a>
                                    <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.pm_order.index', ['type' => 'pending']) }}">Open <span>Pending MSA Contract</span></a>
                                    <a class="rejected-list btn btn-primary hover-list" href="{{ route('staff.pm_order.index', ['type' => 'rejected']) }}" >Rejected <span>Rejected Contracts</span></a>
                                    <a class="closed-list btn btn-primary hover-list" href="{{ route('staff.pm_order.index', ['type' => 'created']) }}" >Closed <span>Created MSA Contract</span></a>

                                    <button type="button" class="popover-btn" id="popover-button">
                                        <img src="{{asset('images/info.svg')}}" alt="Info" />
                                    </button>
                                
                                    <div class="popoverpop" id="popover">
                                        <div class="popover-arrow"></div>
                                        <div class="popover-content">
                                            
                                            <p><strong>All</strong>: Displays all MSA Contracts, including those in progress and completed.</p>
                                            <p><strong>Open</strong>: Shows the Pending MSA Contracts that are awaiting approval.</p>
                                            <p><strong>Rejected</strong>: Displays contracts that have been rejected and will not proceed.</p>
                                            <p><strong>Closed</strong>: Shows the Created MSA Contracts that are finalized and completed.</p>
                                            
                                        </div>
                                    </div>
                                    
                                </div>


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




                                <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                                    <thead>
                                        <tr class="headrole">
                                            <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                                            <th>No.</th>
                                            <th>in ref no</th>
                                            <th>Customer Name</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Engineer Name</th>
                                            <th>Equipment Name</th>
                                            <th>Serial No</th>
                                            <th>Contact Type</th>
                                            <th>MSA Start Date</th>
                                            <th>MSA End Date</th>
                                            <th>PM Count</th>
                                            <th>Created Date</th>
                                            <th>Revenue</th>
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
            <div class="modal fade" id="deleteService" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                // $(document).ready(function() {
                
                //     $('.service-btn-left a').hover(function() {
                      
                //         console.log('hover couur');

                //         var tooltipText = $(this).attr('data-tooltip');
                        
                //         $('#tooltip').text(tooltipText);

                //         $('#tooltip').css({
                //             display: 'block',
                //             top: $(this).position().top - 35,
                //             left: $(this).position().left + $(this).width() / 2 - $('#tooltip').width() / 2
                //         });
                //     }, function() {
                  
                //         $('#tooltip').css('display', 'none');
                //     });
                // });


                jQuery(document).ready(function() {

                    //var url = "{{ route('admin.service-create', '+service_id+') }}";
                    var url = '{{ request()->fullUrl() }}';
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


                        order: [
                            [0, 'desc']
                        ],
                        columns: [{
                                "data": 'DT_RowIndex',
                                name: 'id',
                                orderable: true,
                                searchable: false
                            },
                            {
                                "class": "mobile_view",
                                data: 'in_ref_no',
                                name: 'in_ref_no',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                "class": "mobile_view",
                                data: 'customer',
                                name: 'customer',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                data: 'state',
                                name: 'state',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'district',
                                name: 'district',
                                orderable: true,
                                searchable: true,
                            },

      

                            {
                                "class": "mobile_view",
                                data: 'engineer',
                                name: 'engineer',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'machine',
                                name: 'machine',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                "class": "mobile_view",
                                data: 'serial_no',
                                name: 'serial_no',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'contract_type',
                                name: 'contract_type',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                "class": "mobile_view",
                                data: 'contract_start_date',
                                name: 'contract_start_date',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'contract_end_date',
                                name: 'contract_end_date',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'pm_dates_no',
                                name: 'pm_dates_no',
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
                                "class": "mobile_view",
                                data: 'revenue',
                                name: 'revenue',
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
                    var url = APP_URL + '/staff/msa_change_state';
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

                    var url = APP_URL + '/staff/msa_get_client_use_state_district';
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
        @endsection
 

          