@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

    <section class="content-header">
        <h1>
            History of {{ $serviceType->name }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage {{ $serviceType->name }} </li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <input type="hidden" id="service_id" value="{{ $serviceType->id }}">
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-block fade in alert-dismissible show">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif

                    <!-- /.box-header -->

                    <div class="col-lg-3">
                        <label for="stateFilter">Select State:</label>
                        <select id="stateFilter" class="form-control">
                            <option value="">All States</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                        </select>
                    </div>

                    
                    <div class="box-body">

                        <form name="dataForm" id="dataForm" method="post" action="">
                            @csrf
                            <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                                <thead>
                                    <tr class="headrole">
                                        <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                                        <th class ="mobile_view">No.</th>
                                        <th class ="mobile_view">In Ref.No</th>
                                        <th class ="mobile_view">Customer Name</th>
                                        <th class ="mobile_view">Eqpt Name</th>
                                        <th class ="mobile_view">Eqpt Sr.No</th>
                                        <th class ="mobile_view">Observed Problem</th>
                                        <th class ="mobile_view">Action Performed</th>
                                        <th class ="mobile_view">Final Status</th>
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

    @endsection



    @section('scripts')

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {

                var service_id = $('#service_id').val();
                //var url = "{{ route('admin.service-create', '+service_id+') }}";
                var url = '{{ route('staff.service-response-history', ':id') }}';
                url = url.replace(':id', service_id);
                //alert(url)
                var oTable = $('#cmsTable').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: {
                        url: url,
                        data: function(d) {
                        d.state_id = $('#stateFilter').val(); 
                    }
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
                            orderable: false,
                            searchable: false
                        },


                        {
                            "class": "mobile_view",
                            data: 'in_ref_no',
                            name: 'internal_ref_no',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'customer',
                            name: 'serviceUser.business_name',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'equipment_name',
                            name: 'serviceProduct.name',
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
                            data: 'task_comment_observed',
                            name: 'task_comment_observed',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'task_comment_action',
                            name: 'task_comment_action',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'task_comment_final',
                            name: 'task_comment_final',
                            orderable: true,
                            searchable: true,
                        }


                    ]
 

                });

                $('#stateFilter').change(function() {
                    oTable.ajax.reload();
                });

                $('#cmsTable').on('click', '.deleteItem', function() {

                    var id = $(this).attr('id');
                    var url = $(this).attr('href');
                    $('#btnDeleteItem').attr('data-id', id);
                    $('#btnDeleteItem').attr('data-href', url);
                    $('#modalDelete').modal();
                    return false;

                });

            });
        </script>

    @endsection
 