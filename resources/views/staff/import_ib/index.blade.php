@extends('staff/layouts.app')

@section('title', 'Import IB')

@section('content')

<section class="content-header">
    <h1>
      Import IB Lists
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Import IB</li>
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
                        {{-- <th>Equipment Model No444</th> --}}
                        <th>Equipment Serial No</th>
                        <th>Staff Name</th>
                        <th>Installation Date</th>
                        {{-- <th>Warranty Date</th> --}}
                        {{-- <th>Supply Order No</th> --}}
                        {{-- <th>Invoice No</th> --}}
                        {{-- <th>Invoice Date</th> --}}
                        <th class="alignCenter">Action</th>
                        <th></th>
                        <th></th>
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
</section>

@endsection



@section('scripts')

<script type="text/javascript">


jQuery(document).ready(function() {
       
       var oTable = $('#cmsTable').DataTable({
           processing:true,
           serverSide:true,
          
           ajax:{
             url: "{{ request()->url() }}",
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
               data:'customer_name',
               name:'customer_name_row',
               orderable: true, 
                searchable: true,
             },
           
             {
               data:'district',
               name:'district',
               orderable: true, 
                searchable: true,
             },
             {
               data:'equipment',
               name:'equipment_row',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'serial',
               name:'serial_row',
               orderable: true, 
                 searchable: true,
             },
            {
               data:'sales_person',
               name:'sales_person',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'install_date',
               name:'install_date',
               orderable: true, 
                 searchable: true,
             },
            
             {
               data:'action',
               name:'action',
               orderable: true, 
               searchable: true,
             },

             {
               data:'customer_name',
               name:'customer_name',
               orderable: true, 
                searchable: true,
                visible:false,
             },
             {
               data:'equipment',
               name:'equipment',
               orderable: true, 
                searchable: true,
                visible:false,
             },
             {
               data:'serial',
               name:'serial',
               orderable: true, 
              searchable: true,
              visible:false,
             },
             
           ]
            
 
 
         });
  


     });
     


  </script>

@endsection

