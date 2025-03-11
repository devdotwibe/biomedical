

@extends('staff/layouts.app')

@section('title', 'Manage Msp')

@section('content')

<section class="content-header">
      <h1>
        Manage Msp
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Msp</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">


            <div class="pull-left">

                <a class="add-button " href="{{ route('staff.msp.create') }}">Add Msp</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/msp/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                  <th>Status</th>
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($msp as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="msp">
                        <td data-th="" ><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Name" ><?php echo $product->name ?></td>
                      
                        <td data-th="Image" >{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                      
                        <td data-th="Date"  class="alignCenter">
                            <a class="btn <?php echo ($product->status== 'Y') ? 'btn-success': 'btn-danger' ?>  btn-xs statusItem" id="statusItem{{$product->id}}" data-id="{{$product->id}}" data-from ="msp" title="<?php echo ($product->status == 'Y') ? 'Active': 'Inactive' ?>">
                                <span class="glyphicon <?php echo ($product->status== 'Y') ? 'glyphicon-ok': 'glyphicon-ban-circle' ?>"></span></a>
                        </td>

                        <td data-th="Status"  class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.msp.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                          
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('staff.msp.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                          
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($msp) > 0) { ?>
              <div class="deleteAll">
                 <a class="mdm-btn cancel-btn  " onClick="deleteAll('msp');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div>
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


        // Add event listener for opening and closing details
       // $('#cmsTable tbody').on('click', 'td.details-control', function () {
        $('.openTable').on('click',  function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );

            var id = $(tr).attr('data-id');
            var from = $(tr).attr('data-from');

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                var resp = getRowDetails(id, from, row,tr);
            }
        });

    });

</script>
@endsection
