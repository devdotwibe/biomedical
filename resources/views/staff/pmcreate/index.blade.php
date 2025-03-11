@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

    <section class="content-header">
        <h1>
            PMs 

        </h1>

        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PMs</li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">


                    @if (session('success'))
                        <div class="alert alert-success alert-block fade in alert-dismissible show">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif

                    <div class="service-btn-left">

                        <a class="pending-list btn btn-primary hover-list {{ request()->get('type') == '' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2]) }}">All <span>All PMs</span></a>

                        <a class="pending-list btn btn-primary hover-list {{ request()->get('type') == 'open' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2, 'type' => 'open']) }}">Open <span>Staffs not assigned</span></a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'pending' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2, 'type' => 'pending']) }}">Pending <span>Staffs assigned and status not completed</span></a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'reported' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2,'type' => 'reported']) }}">Report <span>Work Updated</span> </a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'verified_task' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2 ,'type' => 'verified_task']) }}">Verified Task<span>Task Approved</span></a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'verified_call' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2 ,'type' => 'verified_call']) }}">Verified Call<span>Approved</span></a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'feedback' ? 'active' : '' }}"
                            href="{{ route('staff.pm_create', ['id' => 2,'type' => 'feedback']) }}">Feedback <span>Feedback</span></a>

                        <a class="technical-support btn btn-primary hover-list {{ request()->get('type') == 'closed' ? 'active' : '' }} "
                            href="{{ route('staff.pm_create', ['id' => 2, 'type' => 'closed']) }}">Closed <span>status completed</span></a>

                            <button type="button" class="popover-btn" id="popover-button">
                                <img src="{{asset('images/info.svg')}}" alt="Info" />
                            </button>
                        
                            <div class="popoverpop" id="popover">
                                <div class="popover-arrow"></div>
                                <div class="popover-content">
                                    
                                    <p><strong>All</strong>: Displays all tasks, including those that are in progress, completed, and awaiting assignment.</p>
                                    <p><strong>Open</strong>:  Displays tasks where staff has not been assigned yet, and action is still pending.</p>
                                    <p><strong>Pending</strong>:Displays tasks that have been assigned to staff but are not yet completed</p>
                                    <p><strong>Report</strong>: Shows tasks where work has been updated or submitted for review.</p>
                                    <p><strong>Verified</strong>: Displays tasks that have been reviewed and approved.</p>
                                    <p><strong>Feedback</strong>: Displays tasks where feedback has been provided and is awaiting further action.</p>
                                    <p><strong>Closed</strong>: Displays tasks that have been completed and marked as finished.</p>
                                    
                                </div>
                            </div>

                    </div>




                    <div class="form-group col-md-2 col-sm-6 col-lg-2">

                        <label for="name">State</label>
                        <select id="state_id" name="state" class="form-control" onchange="reloadTable('state')">
                            <option value="">Select State</option>

                            @foreach ($state as $item)
                                <option value="{{ $item->id }}" id="state_{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach

                        </select>

                    </div>


                    <div class="form-group col-md-2 col-sm-6 col-lg-2">

                        <label for="name">District</label>
                        <select id="district_id" name="district" class="form-control" onchange="reloadTable('district')">
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

                    <div class="form-group col-md-2 col-sm-6 col-lg- time-period">

                        <label for="name">Period</label>

                        <input type="text" id="start_date" placeholder="From" class="time_picker" name="start_date"
                            readonly onchange="reloadTable()">

                        <input type="text" id="end_date" placeholder="To" class="time_picker" name="end_date" readonly
                            onchange="reloadTable()">

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




                    <div class="box-body">

                        <form name="dataForm" id="dataForm" method="post" action="">
                            @csrf
                            <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                                <thead>
                                    <tr class="headrole">
                                        <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
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
                                        <th>Report Date</th>
                                        <th>Feedback</th>
                                        <th>Rating</th>
                                        <th>Contract Person</th>
                                        <th>Report</th>
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

        <div class="modal fade" id="deletepm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            <form id="part-details" method="post" action="{{ route('staff.destroypm') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="pmid" name="id" value="">
                                <input type="submit" class="btn btn-primary" name="submit" value="Yes">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="pm_detail_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                <h3 class="modal-title">Pm Details</h3>
                    </div>

                    <p id="no_task_created"></p>

                    <div class="modal-body">
                       <table id="" class="mobile_view_table table table-bordered table-striped data-" >
                        <thead>
                          {{-- <tr>
                            <th>Task Name</th>
                            <th>Created By</th>
                            <th>Contract Person</th>
                            <th>Created At</th>
                            <th>Observed Problem</th>
                            <th>Final Status</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Response Status</th>

                            
                          </tr> --}}
                        </thead>

                        <tbody id="task_details">
        
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="feed_back_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                <h3 class="modal-title">FeedBack Details</h3>
                    </div>
                    <div class="modal-body">
                       <table id="" class="mobile_view_table table table-bordered table-striped data-" >
                        <thead>
                          <tr>
                            <th>Rating</th>
                            <th>Contact Person</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Feedback Description</th>
                          </tr>
                        </thead>

                        <tbody id="feed_back_html">
        
                        </tbody>
                    </table>
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

                var service_id = 2;
                //var url = "{{ route('admin.service-create', '+service_id+') }}";
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

                        // if (info.pages > 1) {

                        //     var pageDropdown = $('<select class="page-size-dropdown">');
                        //     for (var i = 1; i <= info.pages; i++) {
                        //         pageDropdown.append('<option value="' + i + '">' + i + '</option>');
                        //     }

                        //     $(".col-sm-7").append(pageDropdown);

                        //     pageDropdown.val(info.page + 1);

                        //     pageDropdown.on('change', function() {
                        //         var pageNum = $(this).val() - 1;
                        //         api.page(pageNum).draw('page');
                        //     });
                        // }

                        var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
                        $(".col-sm-7").append(searchInput);

                        if (info.pages > 1) {

                            // var pageDropdown = $('<select class="page-size-dropdown">');
                            // for (var i = 1; i <= info.pages; i++) {
                            //     pageDropdown.append('<option value="' + i + '">' + i + '</option>');
                            // }

                            // $(".col-sm-7").append(pageDropdown);

                            // pageDropdown.val(info.page + 1); 

                            // pageDropdown.on('change', function() {
                            //     var pageNum = $(this).val() - 1;
                            //     api.page(pageNum).draw('page');
                            // });

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
                            data: 'no_of_pm',
                            name: 'no_of_pm',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'pm_dates',
                            name: 'pm_dates',
                            orderable: true,
                            searchable: true,
                        },
                      
                        {
                            "class": "mobile_view",
                            data: 'desired_date',
                            name: 'desired_date',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view test",
                            data: 'created_at_date',
                            name: 'created_at',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'engineer',
                            name: 'engineer',
                            orderable: true,
                            searchable: true,
                        },


                        {
                            "class": "mobile_view",
                            data: 'report_date',
                            name: 'report_date',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'feedback',
                            name: 'feedback',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            data: 'rating',
                            name: 'rating',
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
                            data: 'report',
                            name: 'report',
                            orderable: true,
                            searchable: true,
                        },

                        {
                            "class": "mobile_view",
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            "class": "mobile_view",
                            data: 'msa_ref_no',
                            name: 'msa_ref_no',
                            orderable: true,
                            searchable: true,
                            visible:false
                        }


                        


                    ]


                });



            });
            $('#cmsTable').on('click', '.deleteItem', function() {
                $('#deleteService').modal('show');
                var id = $(this).attr('attr-service-id');
                $('#seviceId').val(id);
            });
            $('#cmsTable').on('click', '.deleteItempm', function() {
                $('#deletepm').modal('show');
                var id = $(this).data('pm-id'); 
                $('#pmid').val(id);
            });


        </script>




        <script>

            
            $(document).on('click', '.feed_back_details', function(event) {

                    var id = $(this).data('id');

                    var url = APP_URL + '/staff/show_feed_back';
                    $.ajax({
                        type: "GET",
                        cache: false,
                        url: url,
                        data: {
                            id: id,
                        },
                        success: function(res) {

                            $('#feed_back_html').html('');

                            $('#feed_back_popup').modal('show');

                            console.log(res.pmfeedback);

                            var feed_back_html='';

                            $.each(res.pmfeedback.pmfeedback, function(i, v) {

                    
                                feed_back_html += `
                                    <tr>
                                        <td>${v.rating}</td>
                                        <td>${v.service_feedback_contact?.name??''}</td>
                                        <td>${v.service_feedback_staff?.name??''}</td>
                                        <td>${v.created_at}</td>
                                        <td>${v.description}</td>
                                     
                                    </tr>
                                `;
                            });


                            $('#feed_back_html').append(feed_back_html);



                        }
                    });

                    });


            $(document).on('click', '.show_pmdtails', function(event) {

                var id = $(this).data('id');

                $('#no_task_created').hide();

                var url = APP_URL + '/staff/show_pmdetails';
                $.ajax({
                    type: "GET",
                    cache: false,
                    url: url,
                    data: {
                        id: id,
                    },
                    success: function(res) {
                     
                        $('#pm_detail_popup').modal('show');

                        $('#task_details').html('');

                        console.log(res.pm_details);

                        if(res.pm_details.callcomments.length > 0 || res.pm_details.pmtask.length > 0)
                        {
                            var call_html='';

                            $.each(res.pm_details.callcomments, function(i, v) {

                                call_html +=`
                                    <tr>

                                        <th>Call Task</th>
                                        <th>Created By</th>
                                        <th>Contract Person</th>
                                        <th>Created At</th>
                                        <th>Observed Problem</th>
                                        <th>Final Status</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th>Response Status</th>
                                    </tr>
                                `;

                                call_html += `
                                    <tr>
                                        <td>${v.visit == 'Y' ? 'Visit' : 'Call'}</td>
                                        <td>${v.task_comment_staff?.name??''}</td>
                                        <td>${v.task_comment_contact_person?.name??''}</td>
                                        <td>${v.created_at}</td>
                                        <td>${v.service_task_problem}</td>
                                        <td>${v.service_task_action}</td>
                                        <td>${v.service_task_final_status}</td>
                                        <td>${v.task_remark}</td>
                                        <td>${v.status == 'Y' ? 'Approved' : 'Not Approved'}</td>
                                    </tr>
                                `;
                            });


                            var task_head='';

                            $.each(res.pm_details.pmtask, function(i, v) {

                                task_head +=`
                                <tr>
                                    <th >Task Name</th>

                                    <th >Visiting Date</th>

                                    <th >Visiting Time</th>

                                    <th >Created By</th>

                                    <th >Created At</th>

                                    <th >Status</th>

                                    </tr>
                                `;

                                task_head += `
                                    <tr>
                                        <td>${v.name}</td>
                                        <td>${v.service_day}</td>
                                        <td>${v.service_time}</td>
                                        <td>${v.task_create_by?.name??''}</td>
                                        <td>${v.created_at}</td>
                                        <td>${v.service_task_status}</td>
                                    
                                    </tr>
                                `;

                                $.each(v.taskcomment, function(j,k) {


                                    task_head +=`
                                    <tr>

                                        <th>Response Method</th>
                                        <th>Created By</th>
                                        <th>Contract Person</th>
                                        <th>Created At</th>
                                        <th>Observed Problem</th>
                                        <th>Final Status</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th>Response Status</th>
                                    </tr>
                                    `;

                                    task_head += `
                                    <tr>

                                        <td>${k.visit == 'Y' ? 'Visit' : 'Call'}</td>
                                        <td>${k.task_comment_staff?.name??''}</td>
                                        <td>${k.task_comment_contact_person?.name??''}</td>
                                        <td>${k.created_at}</td>
                                        <td>${k.service_task_problem}</td>
                                        <td>${k.service_task_action}</td>
                                        <td>${k.service_task_final_status}</td>
                                        <td>${k.task_remark}</td>
                                        <td>${k.status == 'Y' ? 'Approved' : 'Not Approved'}</td>
                                    
                                    </tr>
                                `;


                                });

                            });

                            $('#task_details').append(task_head);

                            $('#task_details').append(call_html);

                            console.log(call_html);

                        }
                        else
                        {
                            $('#no_task_created').text('No Task Created').show();
                        }

                       

                    }
                });
                
            });



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
                var url = APP_URL + '/staff/pm_change_state';
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

                var url = APP_URL + '/staff/pm_get_client_use_state_district';
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
                    maxDate: 0
                });

                $('#end_date').datepicker({

                    dateFormat: 'dd-mm-yy',
                    maxDate: 0
                });
            });
        </script>



    @endsection
