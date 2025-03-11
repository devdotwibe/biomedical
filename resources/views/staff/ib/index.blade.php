@extends('staff/layouts.app')

@section('title', 'Manage IB')

@section('content')

<section class="content-header">
    <h1>
      Manage IB
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage IB</li>
    </ol>
</section>



    <!-- Main content -->

<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
          
          <?php /*
          @php $staff_id = session('STAFF_ID'); @endphp
          @if($staff_id==55 || $staff_id==127 || $staff_id==94) 
           */?>
          @php

              $staff_id = session('STAFF_ID');

              $permission = App\Models\User_permission::where('staff_id', $staff_id)->first();

          @endphp
        
            @if(optional($permission)->ib_access_create =='create')

              <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <a class="add-button " href="{{ route('ib-create') }}"> Add IB</a>
                    </div>
                </div>      
              </div>

            @endif
           

          <?php /*
          @endif
          */?>

          @php $staff_id = session('STAFF_ID'); @endphp
          
          <?php /*
          @if($staff_id==121) 

            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="add-button " href="{{ route('staff.staff_import_ib') }}">Ib Import List</a>
                    </div>
                </div>      
              </div>

            @endif
              */?>

            @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- /.box-header -->

            <div class="box-body">

              <form name="dataForm" id="dataForm" method="post" action="" />
                @csrf
                <table id="cmsTable" class="table table-bordered table-striped data-">
                  <thead>
                    <tr class="headrole">
                        <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                        <th>No.</th>
                        <th>Customer</th>
                        <th>District</th>
                        <!-- <th>Customer Name</th> -->
                        <th>Equipment Name</th>
                        <th>Equipment Model No</th>
                        <th>Equipment Serial No</th>
                        <th>Staff Name</th>
                        <th>Installation Date</th>
                        <th>Warranty Date</th>
                        <th>Supply Order No</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
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

<script type="text/javascript">


jQuery(document).ready(function() {
       
       var oTable = $('#cmsTable').DataTable({
           processing:true,
           serverSide:true,
          
           ajax:{
             url:"{{ route('ib-index') }}",
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
           columns:[
             {
                 "data": 'DT_RowIndex',
                 orderable: false, 
                 searchable: false
             },
          
             
             {
               data:'customer',
               name:'ibUser.business_name',
               orderable: true, 
                searchable: true,
             },
           

             {
               data:'district',
               name:'ibUser.userdistrict.name',
               orderable: true, 
                searchable: true,
             },
             {
               data:'equipment_name',
               name:'ibProduct.name',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'equipment_model',
               name:'equipment_model_no',
               orderable: true, 
                 searchable: true,
             },
             {
               data:'equipment_serial',
               name:'equipment_serial_no',
               orderable: true, 
                 searchable: true,
             },
            {
               data:'staff',
               name:'ibStaff.name',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'installation_date',
               name:'installation_date',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'warrenty_end_date',
               name:'warrenty_end_date',
               orderable: true, 
                 searchable: true,
             },
             {
               data:'supplay_order',
               name:'supplay_order',
               orderable: true, 
                 searchable: true,
             },
             {
               data:'invoice_number',
               name:'invoice_number',
               orderable: true, 
                 searchable: true,
             },
             {
               data:'invoice_date',
               name:'invoice_date',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'action',
               name:'action',
               orderable: true, 
                 searchable: true,
             }
             
           ]
            
 
 
         });
 
         $('#cmsTable').on('click', '.deleteItem', function () {
      
          var id= $(this).attr('id');
        var url = $(this).attr('href');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();        
        return false;

    });


     });
     


  </script>

@endsection


