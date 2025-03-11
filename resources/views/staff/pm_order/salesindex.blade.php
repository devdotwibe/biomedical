@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

<style>

    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    .switch input {
    opacity: 0;
    width: 0;
    height: 0;
    }

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #df2222;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #4CAF50;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #4CAF50;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }

</style>

    @php
        
        $payment = false;

        if(@request('status') =='payment')
        {
            $payment = true;
        }

    @endphp

    <section class="content-header">
        <h1>
            Manage Customer Orders
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

                                    
                                    <a class="all-list btn btn-primary hover-list" href="{{ route('staff.sales.index',['company_type' => @request('company_type')]) }}">All  <span>All Orders</span></a>

                                    <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'pending']) }}">Open <span>All Pending Orders</span></a>

                                    <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'rejected']) }}">Rejected <span>All Rejected Orders</span></a>

                                    <a class="technical-support btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'saved']) }}">Saved <span>All Orders Created</span></a>

                                    <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'verified']) }}">Verified <span>All Verified Orders</span></a>

                                    {{-- <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'payment']) }}">Payment <span>All Orders Payment Details</span></a> --}}
                                     
                                    <a class="pending-list btn btn-primary hover-list" href="{{ route('staff.sales.index', ['company_type' => @request('company_type'),'status'=>'paymentview']) }}">Payment <span>All Orders Payment Details</span></a>

                                    <button type="button" class="popover-btn" id="popover-button">
                                        <img src="{{asset('images/info.svg')}}" alt="Info" />
                                    </button>
                                
                                    <div class="popoverpop" id="popover">
                                        <div class="popover-arrow"></div>
                                        <div class="popover-content">
                                            
                                            <p><strong>All</strong>:Displays all orders, including pending, rejected, saved, and verified orders, providing a complete overview.</p>
                                            <p><strong>Open</strong>:  Displays all orders that are currently pending and awaiting action or approval.</p>
                                            <p><strong>Rejected</strong>: Displays orders that have been rejected and will not proceed further.</p>
                                            <p><strong>Saved</strong>:  Displays all orders that have been created and saved but are not yet processed or verified.</p>
                                            <p><strong>Verified</strong>: Displays orders that have been verified and approved for further processing.</p>
                                            <p><strong>Payment</strong>:Displays the payment details for all orders.</p>

                                        </div>
                                    </div>

                                </div>

                            @if(!$payment)

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
                                            <th>No.</th>
                                            <th>in ref no</th>
                                            <th>Customer Name</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Engineer Name</th>
                                            <th>Equipment Name</th>
                                            <th>Created Date</th>
                                            <th class="alignCenter">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                @else

                                    <table id="payment_table" class="mobile_view_table table table-bordered table-striped data-">
                                        <thead>
                                            <tr class="headrole">
                                                <th>No.</th>
                                                <th>Bill no</th>
                                                <th>Customer Name</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>attachment</th>
                                                <th>Comment</th>
                                                <th class="alignCenter">Payment collected No/Yes</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                @endif



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


            @if(!$payment)

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

                                d.company_type="{{ request('company_type') }}";
                                d.status= "{{ request('status') }}";
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
                                searchable: true
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
                                "class": "mobile_view",
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
                                data: 'created_at',
                                name: 'created_at',
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

            @else

                jQuery(document).ready(function() {

                    var url = '{{ request()->fullUrl() }}';

                    var company_type = "{{ request('company_type') }}";
                    var status = "{{ request('status') }}";
                    var id = "{{ request('id') }}";

                    if (company_type) {
                        url += (url.includes('?') ? '&' : '?') + 'company_type=' + company_type;
                    }
                    if (status) {
                        url += (url.includes('?') ? '&' : '?') + 'status=' + status;
                    }
                    if (id) {
                        url += (url.includes('?') ? '&' : '?') + 'id=' + id;
                    }

                    var oTable = $('#payment_table').DataTable({
                        processing: true,
                        serverSide: true,

                        ajax: {
                            url: url,

                            data: function(d) {
                                d.company_type = company_type;
                                d.status = status;
                            },
                        },
                        initComplete: function(settings) {

                            var api = this.api();

                            var info = this.api().page.info();

                            var billProductData = settings.json.bill_product;

                            console.log('Bill Product Data:', billProductData);

                            api.rows().every(function(rowIdx, tableLoop, rowLoop) {
                            
                                var rowData = this.data();

                                // var tablehead =
                                //  ` <tr class="headrole">
                                //     <th>No.</th>
                                //     <th>Bill no</th>
                                //     <th>Customer Name</th>
                                //     <th>Date</th>
                                //     <th>Total</th>
                                //     <th>attachment</th>
                                //     <th class="alignCenter">Payment collected Yes/No</th>
                                // </tr>`;

                                var appendrow = 'row-'+rowData.id;

                                // if(rowIdx != 0)
                                // {
                                //     $('#' + appendrow).before(tablehead);
                                // }
                                
                                var table=` <tr id="child-${appendrow}">
                                        <th></th>
                                        <th>Equi. Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>

                                        <th></th>
                                        <td></td>
                                       
                                    </tr>
                                `;

                                $('#' + appendrow).after(table);

                                var filteredProducts = billProductData.filter(function(v, k) {

                                    return v.contract_bill_id === rowData.id;

                                });

                                var productRow = ""; 

                                filteredProducts.forEach(function(product) {

                                    productRow  += `<tr>
                                                        <th></th>
                                                        <td>${product.equip_name}</td>
                                                        <td>${product.bill_qty}</td>
                                                        <td>${product.billproduct.amount}</td>
                                                        <td></td> 
                                                        <td></td>
                                                    </tr>`;

                                    
                                });

                                $(`#child-${appendrow}`).after(productRow);

                            });

                        },

                        createdRow: function(row, data, dataIndex) {

                            $(row).attr('id','row-' + data.id);
                             $(row).attr('data-id',data.id);
                        },

                        drawCallback: function() {

                            var json = this.api().ajax.json();

                            var api = this.api();

                            var billProductData = json.bill_product;


                            api.rows().every(function(rowIdx, tableLoop, rowLoop) {

                                var rowData = this.data();

                                var appendrow = 'row-'+rowData.id;

                                var tablehead =
                                 ` <tr class="headrole">
                                    <th>No.</th>
                                    <th>Bill no</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>attachment</th>
                                     <th>Comment</th>
                                    <th class="alignCenter">Payment collected Yes/No</th>
                                </tr>`;

                                if(rowIdx != 0)
                                {
                                    $('#' + appendrow).before(tablehead);
                                }

                                var table=` <tr id="child-${appendrow}">
                                        <th></th>
                                        <th>Equi. Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>

                                        <th></th>
                                        <th></th>
                                    
                                    </tr>
                                `;

                                $('#' + appendrow).after(table);

                                var filteredProducts = billProductData.filter(function(v, k) {

                                    return v.contract_bill_id === rowData.id;

                                });


                                var productRow = ""; 

                                filteredProducts.forEach(function(product) {

                                    var bill_amount_cal = product.billproduct.amount * (product.bill_qty ?? 1);  

                                    var service_tax_percentage = product.billproduct.tax_percentage ?? 12;

                                    var bill_tax = bill_amount_cal * (service_tax_percentage / 100);

                                    var bill_tax_total = bill_amount_cal + bill_tax;

                                    productRow  += `<tr>
                                                        <th></th>
                                                        <td>${product.equip_name}</td>
                                                        <td>${product.bill_qty}</td>
                                                        <td>${(bill_tax_total).toFixed(2)}</td>
                                                        <td></td> 
                                                        <td></td>
                                                    </tr>`;

                                   
                                });

                                $(`#child-${appendrow}`).after(productRow);

                            });

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
                        columns: [
                            {
                                "data": 'DT_RowIndex',
                                name: 'id',
                                orderable: true,
                                searchable: true
                            },
                            {
                                "class": "mobile_view",
                                data: 'bill_no',
                                name: 'bill_no',
                                orderable: true,
                                searchable: true,
                            },
                            
                            {
                                "class": "mobile_view",
                                data: 'customer_name',
                                name: 'billcontractproduct.oppertunity.customer.business_name',
                                orderable: true,
                                searchable: true,
                            },

                            {
                                "class": "mobile_view",
                                data: 'bill_date',
                                name: 'bill_date',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'total',
                                name: 'total',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'attachment',
                                name: 'attachment',
                                orderable: true,
                                searchable: true,
                            },
                            {
                                "class": "mobile_view",
                                data: 'comment',
                                name: 'comment',
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

            @endif
                

                $('#cmsTable').on('click', '.deleteItem', function() {
                    $('#deleteService').modal('show');
                    var id = $(this).attr('attr-service-id');
                    $('#seviceId').val(id);
                });
            </script>



            <script>

                function editComment(id)
                {
                    var textarea = $('#com-' + id);

                    textarea.attr('readonly',false);
                }

                // function PaymentUpdate(id,e)
                // {
                //     var name = $(e).data('name');

                //     var value = $(`input[name="${name}"]:checked`).val();

                //     var url = "{{route('staff.update_payment')}}";

                //     $.ajax({
                //         type: "POST",
                //         cache: false,
                //         url: url,
                //         data: {
                //             id: id,
                //             value:value
                //         },
                //         success: function(res) {

                //             console.log(res);
                //         }
                //     });
                // }

                function AddComment(id)
                {
                    var textarea = $('#com-' + id);

                    var toggle = $('#payment-' + id).prop('checked') ? $('#payment-' + id).val() : null;

                    var value = $(textarea).val();

                    var url = "{{route('staff.update_comment')}}";

                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: url,
                        data: {
                            id: id,
                            value:value,
                            toggle:toggle
                        },
                        success: function(res) {

                            textarea.attr('readonly',true);
                            if(res.payment)
                            {
                                $('#edit-' + id).hide();
                                $('#save-' + id).hide();
                            }
                        }
                    });
                }


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
                        changeYear :true,
                        changeMonth:true,
                    });

                    $('#end_date').datepicker({

                        dateFormat: 'dd-mm-yy',
                        maxDate: 0,
                        changeYear :true,
                        changeMonth:true,
                    });
                });
            </script>
        @endsection
 


         