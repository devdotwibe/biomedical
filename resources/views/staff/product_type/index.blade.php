@extends('staff/layouts.app')

@section('title', 'Manage Product Type')

@section('content')

<section class="content-header">
  <h1>
    Manage Product Type
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Product Type</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="manage-prdct-table">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="row">
            <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                <a class="add-button" href="{{ route('staff.product_type.create') }}"> <span
                    class="glyphicon glyphicon-plus"></span>Add Product Type</a>
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
            <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/product_type/deleteAll') }}" />
            @csrf
            <table id="cmsTable" class="table table-bordered table-striped data-">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                  <th class="alignCenter">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                @foreach ($product_type as $product)
                  <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from="product_type">
                    <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}"
                      id="check{{$product->id}}">
                    </td>
                    <td>
                    <span class="slNo">{{$i}} </span>
                    </td>
                    <td><?php  echo $product->name ?></td>
                    <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                    <td class="alignCenter">
                    <a class="btn btn-primary btn-xs" href="{{ route('staff.product_type.edit', $product->id) }}"
                      title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                    <?php
            $cat_exit = DB::select('select count(*) as count from products where `product_type_id`="' . $product->id . '" ');
            if ($cat_exit[0]->count == 0) {
                      ?>
                    <a class="btn btn-danger btn-xs deleteItem btn-bkp"
                      href="{{ route('staff.product_type.destroy', $product->id) }}" id="deleteItem{{$product->id}}"
                      data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                    <?php
            }
                      ?>

                    </td>
                  </tr>
                  <?php  $i++ ?>
        @endforeach

                <?php if (count($product_type) > 0) { ?>
                <div class="deleteAll">
                  <a class="btn btn-danger btn-xs btn-bkp" onClick="deleteAll('product_type');" id="btn_deleteAll">
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
  </div>
</section>

@endsection

@section('scripts')
<script type="text/javascript">
  jQuery(document).ready(function () {
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