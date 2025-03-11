

@extends('staff/layouts.app')

@section('title', 'Manage Customer')

@section('content')

@php
  
  $staff_id =session('STAFF_ID');

@endphp
<section class="content-header">
      <h1>
        Manage Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Customer</li>
      </ol>
    </section>

    @php

        $staff_id = session('STAFF_ID');
        $permission = \App\Models\User_permission::where('staff_id', $staff_id)->first();
        $cor_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','customer')->first();

    @endphp

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="row">

                        <div class="col-lg-12 margin-tb">

                            @if(optional($permission)->customer_create == 'create')

                                <div class="pull-left">

                                    <a class="btn btn-sm btn-success" href="{{ route('staff.customer.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Customer</a>

                                </div>

                            @endif

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

                            
                            <div class="form-group col-md-2 col-sm-6 col-lg-2">

                                <label for="name">State</label>
                                <select id="state_id" name="state" class="form-control" onchange="reloadTable('state')">
                                    <option value="">Select State</option>

                                    @foreach ($states as $item)

                                    <option value="{{$item->id}}" id="state_{{$item->id}}">{{$item->name}}</option>
                                        
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

                                <label for="name">Taluk</label>
                                <select id="taluk_id" name="taluk_id" class="form-control" onchange="reloadTable('taluk_id')">
                                    <option value="">Select Taluk</option>

                                </select>
                                
                            </div>

                    <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/customer/deleteAll') }}" />
                            @csrf

                        <table id="cmsTable" class="table table-bordered table-striped data-" onmousedown="return false"
                            onselectstart="return false">
                            <thead>
                                <tr class="headrole">
                                    <th class="col-wide-1"></th>
                                    <th class="col-wide-1">No.</th>
                                    <th class="col-wide-2">Hospital Name</th>
                                    <th class="col-wide-1">Head of the Institution</th>
                                    <th class="col-wide-1">Customer Category</th>
                                    
                                    <th class="col-wide-1">Email</th>
                                    <th class="col-wide-1">Phone</th>
                                
                                    <th class="col-wide-2">Address</th>  
                                    <th class="col-wide-1">Taluk</th>
                                    <th class="col-wide-1">Pincode</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            
                            @if(optional($permission)->customer_delete == 'delete' || optional($cor_permission)->customer_delete == 'delete')

                                <div class="deleteAll">
                                   <a class="btn btn-danger btn-xs" onClick="deleteAll('user');" id="btn_deleteAll" >
                                                  <span class="glyphicon glyphicon-trash"></span> Delete Selected</a>
                                </div>
                            @endif

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

    </section>



@endsection

@section('scripts')
    <script type="text/javascript">
        function cloneOpurtunity(oid) {
            $('#clone-id').val(oid)
            $('#modalClone').modal('show')
        }


        $(function() {  

            var oTable = $('#cmsTable').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [1, 'desc']
                ],
                ajax: {
                    url: "{{ request()->url() }}",

                    data: function(d) {

                        d.state = $('#state_id').val();
                        d.district = $('#district_id').val();
                        d.taluk =$('#taluk_id').val();
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

                columns: [
                    {
                        data: "delete_action",
                       "name":"delete_action",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "data": 'DT_RowIndex',
                        "name":"id",
                        orderable: false,
                        searchable: true,
                    },

                    {
                        data: 'business_name_edit',
                        name: 'business_name',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'customer_category',
                        name: 'customer_category.name',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'address1',
                        name: 'address1',
                        orderable: true,
                        searchable: true,
                    },

                    {
                        data: 'taluk',
                        name: 'usertaluk.name',
                        orderable: true,
                        searchable: false,
                    },

                    {
                        data: 'zip',
                        name: 'zip',
                        orderable: true,
                        searchable: true,
                    },

                ]

            });


            $('#clone-form').submit(function(w) {
                $('#modalClone').modal('hide')
            })

        });

        $(function() {

            $('#start_date').datepicker({
            
                dateFormat: 'yy-mm-dd',
                maxDate: 0
            });

            $('#end_date').datepicker({
            
                dateFormat: 'yy-mm-dd',
                maxDate: 0
            });
         });


        jQuery(document).ready(function() {

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

            if(element =='state')
            {
                change_state();

                $("#taluk_id").empty();
                $("#taluk_id").append('<option value="">Select Taluk</option>');

                $("#district_id").val("");

                $('#cmsTable').DataTable().ajax.reload();

            }
            if(element =='district')
            {
                change_district();

                $('#cmsTable').DataTable().ajax.reload();
            }
            if(element =='taluk_id')
            {
                $('#cmsTable').DataTable().ajax.reload();
            }
              
        }

        function change_state() {
            var state_id = $("#state_id").val();
            var url = APP_URL + '/staff/user_change_state';
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

            var url = APP_URL + '/staff/taluk_state_district';
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data: {
                    district_id: district_id,
                    state_id: state_id,
            
                },
                success: function(data) {
                    var proObj = JSON.parse(data);

                    states_val = '';
                    states_val += '<option value="">Select Taluk</option>';

                    for (var i = 0; i < proObj.length; i++) {
                        var selected = '';

                        states_val += '<option value="' + proObj[i]["id"] + '" ' + selected + '>' + proObj[i][
                            "name"
                        ] + '</option>';
                    }

                    $("#taluk_id").html(states_val);

                }
            });

        }

    </script>

 
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

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
