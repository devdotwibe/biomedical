

@extends('staff/layouts.app')

@section('title', 'Manage Service Task')

@section('content')

<section class="content-header">
      <h1>
        Manage Service Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Service Task</li>
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


            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('staff.service_task.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Service task</a>

            </div>

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

              <table id="cmsTable" class=" table table-striped w-auto">
                <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
               
                  <th>Opened</th>
                  <!-- <th>Desired Date/Time</th> -->
                  <th>Schedule Date</th>
                  <th>SR Type</th>
                  <th>SR </th>
                  <th>Status </th>
                  <th>Serial </th>
                  <th>Product Description </th>
                  <th>Customer Name </th>
                  <th>Contact Name </th>
                
                  <th>Description </th>
                  <th>Owner </th>
                 
                
               
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($service_task as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="service_task">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td> -->
                       
                       
                   
                        <td ><?php echo date("Y-m-d",strtotime($product->start_date)) ?>..</td>
                       
                        <td><?php echo $product->end_date ?></td>
                        <!-- <td><?php echo $product->schedule_date ?></td> -->
                        <td><?php echo $product->job_type ?></td>
                        <td><?php echo $product->serial_no ?></td>
                        <td><?php echo $product->status ?></td>
                        <td><?php echo $product->service_type ?></td>
                        <td>
                        <?php
                         if($product->product_id>0){
                            $products = App\Product::find($product->product_id);
                            echo $products->name;
                         } ?>
                         </td>
                        <td>
                        <?php
                         if($product->user_id>0){
                            $user = App\User::find($product->user_id);
                            echo $user->name;
                         } ?>
                         </td>
                        <td>
                        <?php
                         if($product->contact_id>0){
                            $contact_person = App\Contact_person::find($product->contact_id);
                            echo $contact_person->name;
                         } ?>
                        </td>
                        <td data-toggle="tooltip" data-placement="top" title="<?php echo $product->prob_desc ?>"><?php echo substr($product->prob_desc,0,6); ?></td>
                        <td><?php
                         if($product->staff_id>0){
                            $staff = App\Staff::find($product->staff_id);
                            echo $staff->name;
                         } ?></td>
                        <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.service_task.edit',$product->id) }}" title="Edit">Edit</span></a>
                            
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('staff.service_task.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                            
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($service_task) > 0) { ?>
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

<!-- <script src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script> -->


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
    $(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection
<style>
  
</style>