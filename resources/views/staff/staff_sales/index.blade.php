

@extends('staff/layouts.app')

@section('title', 'Manage Staff Sales')

@section('content')

<section class="content-header">
      <h1>
        Manage Staff Sales
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Staff Sales</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

                <div class="col-lg-12 margin-tb">


                {{-- <div class="pull-left">

                        <a class="add-button " href="{{ route('staff.staff.create') }}">Add Staff Sales</a>

                    </div> --}}

                </div>

    </div>

            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form name="dataForm" id="dataForm" method="post" action="#" >
              @csrf

              <table id="staffSalesTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  {{-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> --}}
                  <th>No.</th>
                  <th>Engineer Name</th>
                  <th>Customer</th>
                  <th>Care Area</th>
                  <th>Contact Person</th>
                  <th>Number</th>
                  <th>Designation</th>
                  <th>Date</th>
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
    </section>


@endsection

@section('scripts')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script type="text/javascript">
function approvedstatus(url,status){
  $.post(url,{approve:(status?"Y":"N")},function(res) {
    if(res.success){
      popup_notifyMe('success',res.success)
    }
    $('#staffSalesTable').DataTable().ajax.reload();
  },"json")
}

$(function(){

$('#staffSalesTable').DataTable({
    processing:true,
    serverSide:true,
    order: [[7, 'desc']],
    columnDefs: [
      { "width": "150px", "targets": 7 }
    ],
    ajax:{
      url:"{{ route('staff.staff.sales.index') }}",
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

    drawCallback:function (settings) {
      $('.togglebutton').bootstrapToggle();
    },
    columns:[
      {
          "data": 'DT_RowIndex',
          orderable: false, 
          searchable: false
      },
      {
        data:'engneer',
        name:'engineer_name',
        orderable: true, 
        searchable: true,
      },
      {
        data:'customer',
        name:'customer_name',
        orderable: true, 
        searchable: true,
      },
      {
        data:'carearea',
        name:'care_area',
        orderable: true, 
        searchable: true,
      },
      {
        data:'contact_name',
        name:'contact_person_name',
        orderable: true, 
        searchable: true,
      },
      {
        data:'contact_phone',
        name:'contact_person_phone',
        orderable: true, 
        searchable: true,
      },
      {
        data:'contact_designation',
        name:'contact_person_designation',
        orderable: true, 
        searchable: true,
      },
      {
        data:'created_at',
        name:'created_at',
        orderable: true, 
        searchable: true,
      },
      {
        data:'approvestatus',
        name:'status',
        orderable: true, 
        searchable: true,
      },
      {
        data:'action',
        name:'action',
        orderable:false
      }
    ]
    

  });
})
</script>
@endsection
