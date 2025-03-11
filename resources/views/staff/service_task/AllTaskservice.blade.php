

@extends('staff/layouts.app')

@section('title', 'Manage All Task')

@section('content')

<section class="content-header">
      <h1>
        Manage All Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage All Task</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

       <!-- left column -->
      
     
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">


            <!-- <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('staff.service_task.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Service task</a>

            </div> -->

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/service_task/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
               -->
                  <th>No.</th> 
                  <th>Schedule Date</th>
                  <th>Schedule Time</th>
                  <th>Status</th>
                  <th>Owner</th>
                
                
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($service_responce as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="service_task">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td> -->
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                       
                   
                        <td><?php echo $product->schedule_date ?></td>
                       
                        <td><?php echo $product->schedule_time ?></td>
                        <td><?php echo $product->status ?></td>

                        <td><?php

                       $service_responce =  DB::select("select * from service_task where `id`='".$product->service_task_id."'  ");
   
                         if($service_responce[0]->staff_id>0){
                            $staff = App\Staff::find($service_responce[0]->staff_id);
                            echo $staff->name;
                         }  ?>
                         </td>
                     

                        <!-- <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.service_task.edit',$product->id) }}" title="Edit">Edit</span></a>
                            
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('staff.service_task.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                            
                        </td> -->
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($service_responce) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('service_task');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div> -->
               <?php } ?>

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
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({

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



    });

</script>
@endsection
