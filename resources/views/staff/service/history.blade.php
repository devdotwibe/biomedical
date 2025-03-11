@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

<section class="content-header">
    <h1>
      Manage Service
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Service</li>
    </ol>
</section>

<!-- Main content -->

<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="row">
              <div class="col-lg-12 margin-tb">
                  
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

              <form name="dataForm" id="dataForm" method="post" action="" >
                @csrf
                <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                  <thead>
                    <tr class="headrole">
                        <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                        <th>No.</th>
                        <th>Service In.Ref.No</th>
                        <th>Customer Name</th>
                        <th>Eqpt Name</th>
                        <th>Eqpt Sr.No</th>
                        <th>Task Comment Method</th>
                        <th>Observed problem</th>
                        <th>Action Performed</th>
                        <th>Final Status</th>
                    </tr>
                  </thead>
                  <tbody>
                        @foreach($services as $key=> $service)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $service->internal_ref_no }}</td>
                                <td>{{ $service->serviceUser->business_name }}</td>
                                <td>{{ $service->serviceProduct->name }}</td>
                                <td>{{ $service->equipment_serial_no }}</td>
                                @php $task_comment = App\Task_comment::where('service_id',$service->id)->where('status','Y')->orwhere('status','U')->get();   @endphp
                                    
                                        <td>
                                            @foreach($task_comment as $task_comments)
                                              *  {{ ($task_comments->visit == 'Y') ? 'Visit' : 'Call' }}<br><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($task_comment as $task_comments)
                                              *  {{ $task_comments->service_task_problem }}<br><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($task_comment as $task_comments)
                                              *  {{ $task_comments->service_task_action }}<br><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($task_comment as $task_comments)
                                              *  {{ $task_comments->service_task_final_status }}<br><br> 
                                            @endforeach
                                        </td>
                                    
                            </tr>        
                        @endforeach
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
<link rel="stylesheet"
    href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  
    $('#cmsTable').DataTable({

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
    });
} );
 </script>

@endsection
