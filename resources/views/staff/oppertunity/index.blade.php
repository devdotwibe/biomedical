@extends('staff/layouts.app')

@section('title', 'Manage Opportunity')

@section('content')
    <?php
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    } else {
        $type = '';
    }
    
    $opprbio_create = optional($permission)->opperbio_create;
    $opprbec_create = optional($permission)->opperbec_create;
    $opprtechsure_create = optional($permission)->oppertechsure_create;
    $opprmsa_create = optional($permission)->oppermsa_create;
    $create = 0;
    if ($opprbio_create == 'create' && $type == 'bio') {
        $create = 1;
    }
    
    if ($opprbec_create == 'create' && $type == 'bec') {
        $create = 1;
    }
    if ($opprtechsure_create == 'create' && $type == 'techsure') {
        $create = 1;
    }
    if ($opprmsa_create == 'create' && $type == 'msa') {
        $create = 1;
    }
    
    ?>


    <section class="content-header">
        <h1>
            Manage Opportunity
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Opportunity</li>
        </ol>
    </section>

    <!-- Main content -->
    <div class="se-pre-con1"></div>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="row">

                        <div class="col-lg-12 margin-tb">

                            <div class="pull-left">
                                @if ($create == 1)
                                    <a class="add-button " href="{{ url('staff/create_oppertunity') }}"> Add Opportunity</a>
                                @endif

                            </div>

                        </div>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-block fade in alert-dismissible show">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif
                    <!-- /.box-header -->
                    <div class="box-body list-oprty-page">



                        <form name="dataForm" id="dataForm" method="post"
                            action="{{ url('/staff/oppertunities/deleteAll') }}">
                            @csrf
                            <input type="hidden" id="type" name="type" value="{{ $type }}"
                                class="form-control">


                            <div class="form-group col-md-3 col-sm-6 col-lg-3 filer-btns">

                                <label for="all_filter" class="option-btn active" id="all_filter-label">All</label>

                                <input type="radio" name="filer_option" id="all_filter" value="all"
                                    style="display:none">

                                <label for="open_label" class="option-btn" id="all_filter-label">Open</label>

                                <input type="radio" name="filer_option" id="open_label" value="open"
                                    style="display:none">

                                <label for="pending" class="option-btn" id="pending-label">Pending</label>

                                <input type="radio" name="filer_option" id="pending" value="pending"
                                    style="display:none">

                                <label for="closed" class="option-btn" id="closed-label">Closed</label>

                                <input type="radio" name="filer_option" id="closed" value="closed"
                                    style="display:none">

                                <input type="hidden" name="filter_option_value" id="filter_option_value" value="">

                                    <button type="button" class="popover-btn" id="popover-button">
                                        <img src="{{asset('images/info.svg')}}" alt="Info" />
                                    </button>
                                
                                    <div class="popoverpop" id="popover">
                                        <div class="popover-arrow"></div>
                                        <div class="popover-content">
                                            
                                            <p><strong>All</strong>: Displays all Oppertunities </p>
                                            <p><strong>Open</strong>:  Displays all opportunities where a quote has not been generated yet.</p>
                                            <p><strong>Pending</strong>: Displays all opportunities where a quote has been generated, but the quote has not been approved yet.</p>
                                            <p><strong>Closed</strong>:  Displays all opportunities where all associated quotes have been approved.</p>
                                            
                                        </div>
                                    </div>

                            </div>


                            <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                <label for="name">State</label>
                                <select id="state_id" name="state" class="form-control" onchange="reloadTable('state')">
                                    <option value="">Select State</option>

                                    @foreach ($states as $item)
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
                                <select id="account_name" name="account_name" class="form-control" onchange="reloadTable()">

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

                            <?php /*
                            onmousedown="return false"
                            onselectstart="return false"
                        */ 
                        ?>


                            <table id="cmsTable" class="table table-bordered table-striped data-">
                                <thead>
                                    <tr class="headrole">
                                        <!-- <th><input type="checkbox" name="select_all" value="1" id="select_all" class="select-checkbox"></th> -->
                                        <th>No.</th>
                                        <th>Opportunity Reference</th>
                                        <th>Opportunity</th>
                                        <th>Account Name</th>
                                        <th>Engineer Name</th>
                                        <th>Amount</th>
                                        <th>Deal Stage</th>
                                        <th>Es.Order</th>
                                        <th>Es.Sales</th>
                                        <th>Order Forcast Category</th>
                                        <th>Support</th>
                                        <th>Type</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Updated At</th>
                                        <th class="alignCenter">Action</th>
                                        <th></th>
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

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-lg">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Products</h4>
                </div>
                <div class="modal-body" id="contain">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
                </div>
            </div>
        </div>

        <div class="modal fade inprogress-popup" id="productModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Opportunity Products</h4>
                    </div>
                    <div class="modal-body" id="product">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade inprogress-popup" id="chModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Opportunity Details</h4>
                    </div>
                    <div class="modal-body " id="contain-op">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade inprogress-popup" id="viewProductModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">View Oppurtunity Products</h4>
                    </div>
                    <div class="modal-body" id="show-products">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" id="modalDelete_opper">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this row?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <a class="btn btn-primary" id="btnDeleteItem_opper" onclick="DeleteOpper(this)" data-id=""
                        data-href="">Delete</a>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="modalClone">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm Clone</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to clone this row?</p>
                </div>
                <form action="{{ route('staff.oppertunity.clone') }}" method="post" id="clone-form">
                    @csrf
                    <input type="hidden" name="id" id="clone-id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Clone</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection

@section('scripts')
    <script type="text/javascript">
        function cloneOpurtunity(oid) {
            $('#clone-id').val(oid)
            $('#modalClone').modal('show')
        }

        $(function() {

            $('input[type="radio"][name="filer_option"]').change(function() {

                $('.option-btn').removeClass('active');

                $('#filter_option_value').val($(this).val());

                $('label[for="' + $(this).attr('id') + '"]').addClass('active');

                reloadTable();

            });

            $('#filter_option_value').on('change', function() {

                $('#cmsTable').ajax.reload();
                console.log('tet');
            });

        });

        $(function() {

            var oTable = $('#cmsTable').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [14, 'desc']
                ],
                ajax: {
                    url: "{{ route('staff.list_oppertunity') }}",

                    data: function(d) {

                        d.type = $('#type').val();

                        d.filter_option = $('#filter_option_value').val();
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

                    {
                        data: 'op_reference_no',
                        name: 'oppertunities.op_reference_no',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'oppertunity_name',
                        name: 'oppertunities.oppertunity_name',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'business_name',
                        name: 'users_business_name',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'staff_name',
                        name: 'staff_name',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'amount',
                        name: 'oppertunities.amount',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'deal_stage',
                        name: 'deal_stage_name',
                        orderable: true,
                        searchable: false,
                    },

                    {
                        data: 'es_order_date',
                        name: 'oppertunities.es_order_date',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'es_sales_date',
                        name: 'oppertunities.es_sales_date',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'order_forcast',
                        name: 'order_forcast_name',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'support',
                        name: 'support_name',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'created_by',
                        name: 'oppertunities.created_by_name',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'created_time',
                        name: 'oppertunities.created_at',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'updated_at',
                        name: 'oppertunities.updated_at',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                    {
                        data: 'quote_reference_no',
                        name: 'quote_reference_no',
                        orderable: true,
                        visible:false
                    }

                    
                ]


            });


            $('#cmsTable').on('click', '.view_expand', function() {
                var tr = $(this).closest('tr');
                var row = oTable.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    var product_id = $(this).attr('attr-view-product');
                    var url = APP_URL + '/admin/view_oppertunity_products';
                    $.post("{{ url('staff/view_oppertunity_products') }}", {
                        product_id: product_id,
                        "_token": "{{ csrf_token() }}"
                    }, function(result, status) {
                        row.child(result).show();
                        tr.addClass('shown');

                    });


                }
            });

            $("#cmsTable").on("click", ".deleteItem_opper", function(e) {

                var id = $(this).attr('data-id');
                var url = $(this).attr('href');
                $('#btnDeleteItem_opper').attr('data-id', id);

                $('#modalDelete_opper').modal();
                return false;
            });

            $('#clone-form').submit(function(w) {
                $('#modalClone').modal('hide')
            })
        });


        function DeleteOpper(element) {
            var id = $(element).data('id');

            var url = "{{ route('staff.delete_oppertunity') }}";
            var table = $('#cmsTable').DataTable();
            var currentPage = table.page();


            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {


                        $('#modalDelete_opper').modal('hide');

                        table.ajax.reload(null, false);
                        table.page(currentPage).draw(false);

                    } else {

                        $('#modalDelete_opper').modal('show');
                    }
                },
                error: function(xhr, status, error) {

                    console.error(error);
                    $('#modalDelete_opper').modal('show');
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


        jQuery(document).ready(function() {


            // Add event listener for opening and closing details
            // $('#cmsTable tbody').on('click', 'td.details-control', function () {
            $('.openTable').on('click', function() {
                alert()
                var tr = $(this).closest('tr');
                var row = oTable.row(tr);

                var id = $(tr).attr('data-id');
                var from = $(tr).attr('data-from');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    var resp = getRowDetails(id, from, row, tr);
                }
            });

            // Sortable rows


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
            var url = APP_URL + '/staff/opp_change_state';
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

            var url = APP_URL + '/staff/opp_get_client_use_state_district';
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

        $(document).ready(function() {

            $("#cmsTable").on("click", ".viewer", function(e) {

                $(".se-pre-con1").fadeIn();
                //var datas   = $(this).attr('data');
                //var tr = $(this).closest('tr');

                var id = $(this).closest('tr').attr('id');
                var user_id = $(this).closest('tr').attr('data-user_id');

                $.post("{{ url('staff/chatterdetail') }}", {
                    data: id,
                    user_id: user_id,
                    "_token": "{{ csrf_token() }}"
                }, function(result, status) {

                    $('#contain-op').html(result);
                    console.log(result);
                    $('#chModal').modal();
                    $(".se-pre-con1").fadeOut();
                });
                e.preventDefault();
            });

            $("#cmsTable").on("click", ".hoslink", function(e) {

                $(".se-pre-con1").fadeIn();
                //var datas   = $(this).attr('data');
                //var tr = $(this).closest('tr');

                var id = $(this).closest('tr').attr('id');
                var user_id = $(this).closest('tr').attr('data-user_id');

                var links = "{{ url('staff/customer') }}/" + user_id;

                location.href = links;
                e.preventDefault();
            });


        });
    </script>

    <script>
        $(document).ready(function() {

            $('#btn_deleteAll').click(function() {

                if (confirm("Are you sure you want to delete this?")) {
                    var id = [];

                    $('.dataCheck:checked').each(function(i) {
                        //id[i] = $(this).data(id);
                        id.push($(this).data('id'));
                    });


                    if (id.length === 0) //tell you if the array is empty
                    {
                        alert("Please Select atleast one checkbox");
                    } else {

                        var url = APP_URL + '/admin/delete_oppertunity';
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                for (var i = 0; i < id.length; i++) {
                                    $('#tr_' + id[i] + '').css('background-color', '#ccc');
                                    $('#tr_' + id[i] + '').fadeOut('slow');
                                }
                                $("#select_count").html(" 0 Selected");
                            }

                        });
                    }

                } else {
                    return false;
                }
            });

        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '#select_all', function() {
            $(".dataCheck").prop("checked", this.checked);
            $("#select_count").html($("input.dataCheck:checked").length + " Selected");
        });

        $('.view_products').click(function() {

            var contentId = this.dataset.href;
            $(contentId).toggleClass("collapse");

            var product_id = $(this).attr('attr-view-product');
            //$('#therapist_id').val(therapist_id);
            //$('#viewProductModal').modal('show');

            $.post("{{ url('staff/view_oppertunity_products') }}", {
                product_id: product_id,
                "_token": "{{ csrf_token() }}"
            }, function(result, status) {


                $('#' + product_id).html(result);

            });

             $.ajax({
             url:url,
             method:'POST',
             data: {product_id:product_id},
             success:function(data)
             {
              console.log('received this response: '+data['productsResult'].length);
              var htmls='';

              if(data['productsResult'].length > 0){
                  htmls +='<td width="100%" style="width: 100% !important;" colspan="13"><table width="50%" style="border: 1px solid #ccc !important;">'+
                  '<tbody><tr>'+
                  '<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Product</th>'+
                  '<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Quantity</th>'+
                  '<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Unit Price</th>'+
                  '<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Net Amount</th>'+
                  '</tr>';

                  for(var j=0;j<data['productsResult'].length;j++)
                    {
                      htmls +='<tr>';
                       htmls +='<td>'+data['productsResult'][j]['name']+'</td>';
                       htmls +='<td>'+data['resultopper'][j]['quantity']+'</td>';
                       htmls +='<td>'+data['resultopper'][j]['sale_amount']+'</td>';
                       htmls +='<td>'+data['resultopper'][j]['amount']+'</td>';

                      htmls +='</tr>';

                    }

                    htmls +=' </tbody></table></td>';
                }
                else{
                  htmls +='No Products Added';
                }


                    $('#'+product_id).html(htmls);


             }
            });
        });

        $(document).on('click', '.dataCheck', function() {
            if ($('.dataCheck:checked').length == $('.dataCheck').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
            $("#select_count").html($("input.dataCheck:checked").length + " Selected");
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

    <script>
        $(document).ready(function() {
            $('#contact').multiselect();

        });
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
        $('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',


            minDate: 0

        });
        $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',


            minDate: 0

        });
    </script>


@endsection
