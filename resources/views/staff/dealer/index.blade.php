

@extends('staff/layouts.app')

@section('title', 'Manage Dealer')

@section('content')

<section class="content-header">
      <h1>
        Manage Dealer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Dealer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
              <form >
              <table id="dealer-table" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Organization Name</th>
                  <th>Register Email</th>                
                  <th>Register Date</th>
                  <th>Status</th>
                  <th>Action</th>
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

$(function(){
    $('#dealer-table').DataTable({
        processing:true,
        serverSide:true,    
        ajax:{
            url:"{{ route('dealer.index') }}",
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
            data: 'DT_RowIndex',
            orderable: false, 
            searchable: false
        },
    
        
        {
            data:'name',
            name:'Organization Name',
            orderable: true, 
            searchable: true,
        },
        

        {
            data:'register_email',
            name:'Register Email',
            orderable: true, 
            searchable: true,
        },
        {
            data:'register_date',
            name:'Register Date',
            orderable: true, 
            searchable: true,
        },
        {
            data:'status',
            name:'Status',
            orderable: true, 
            searchable: true,
        },
    
        {
            data:'action',
            name:'Action',
            orderable:false
        }
        ]
        

    });
})
</script>
@endsection
