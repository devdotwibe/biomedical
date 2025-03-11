
@extends('staff/layouts.app')

@section('title', 'Manage Quote')

@section('content')

<section class="content-header">
      <h1>
        Manage Quote
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Quote</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        {{-- <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('staff.quote.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Quote</a>

            </div>

        </div> --}}

    </div>

            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/staffquote/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-" onmousedown="return false" onselectstart="return false">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Engineer</th>
                    <th>Customer</th>
                    {{-- <!-- <th>District</th> --> --}}
                    <th>Quote No</th>
                    <th>Quote Send Date</th>
                  
                    <th>Opportunity Name</th>
                   
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


    <?php /*<div class="container">
    @foreach ($users as $user)
        {{ $user->name }}
    @endforeach
    </div>
    {{ $users->links() }}
    */ ?>

@endsection

@section('scripts')
<script type="text/javascript">

    // jQuery(document).ready(function() {
    //     var oTable = $('#cmsTable').DataTable({
    //     });



    // });

    jQuery(document).ready(function() {
     
            var oTable = $('#cmsTable').DataTable({
                processing:true,
                serverSide:true,
                
                ajax:{
                url:"{{ route('staff.quote.index') }}",
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
                    data:'name',
                    name: 'staff.name',
                    orderable: true, 
                    searchable: true,
                },
                
                    {
                    data:'user_name',
                    name:'user_name',
                    orderable: true, 
                    searchable: true,
                },
                //     {
                //    data:'district',
                //    name:'district',
                //    orderable: true, 
                //      searchable: true,
                //  },

                {
                    data:'quote_no',
                    name:'quote_reference_no',
                    orderable: true, 
                    searchable: true,
                },

                {
                    data:'quote_send_date',
                    name:'quote_send_date',
                    orderable: true, 
                    searchable: true,
                },

                // {
                //   data:'created_at_time',
                //   name:'created_at',
                //   orderable: true, 
                //   searchable: true,
                // },
                // {
                //     data:'opper_amount',
                //     name:'quote_amount',
                //     orderable: true, 
                //     searchable: true,
                // },

                {
                    data:'oppertunity_name',
                    name:'oppertunities.oppertunity_name',
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
