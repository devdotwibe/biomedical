@extends('staff/layouts.app')

@section('title', 'Manage Modality')

@section('content')

<section class="content-header">
  <h1>
    Manage Modality
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Modality</li>
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
              <a class="add-button" href="{{ route('modality.create') }}">Add Modality</a>
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
          <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/modality/deleteAll') }}" />
          @csrf

          <table id="cmsTable" class="table table-bordered table-striped data-">
            <thead>
              <tr>
                <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                <th>No.</th>
                <th>Name</th>
                <th>Date</th>
                <th>Status</th>
                <!-- <th>Thumbnail</th> -->

                <th class="alignCenter">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @foreach ($modalities as $modality)
                <tr id="tr_{{$modality->id}}" data-id="{{$modality->id}}" data-from="category_type">
                <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$modality->id}}"
                  id="check{{$modality->id}}">
                </td>
                <td>
                  <span class="slNo">{{$i}} </span>
                </td>
                <td><?php  echo $modality->name ?></td>

                <td>{{ date('d-m-Y h:i A', strtotime($modality->created_at)) }}</td>

                <td class="alignCenter">
                  <a class="btn table-icon <?php  echo ($modality->status == 'Y') ? 'btn-success' : 'btn-danger' ?>  btn-xs statusChange"
                  id="statusItem{{$modality->id}}" data-id="{{$modality->id}}" data-from="modality"
                  title="<?php  echo ($modality->status == 'Y') ? 'Active' : 'Inactive' ?>">
                  <span
                    class="glyphicon <?php  echo ($modality->status == 'Y') ? 'glyphicon-ok' : 'glyphicon-ban-circle' ?>"></span></a>

                </td>

                <td class="alignCenter">
                  <a class="btn btn-primary btn-xs" href="{{ route('modality.edit', $modality->id) }}"
                  title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>


                  <?php
          $modality_exit_asset = DB::select('select count(*) as count from assets where `modality`="' . $modality->id . '" ');
          $modality_exit_products = DB::select('select count(*) as count from products where `category_type_id`="' . $modality->id . '" ');

          if ($modality_exit_asset[0]->count == 0 || $modality_exit_products[0]->count == 0) {
                      ?>
                  <a class="btn btn-danger btn-xs deleteItem" href="{{ route('modality.destroy', $modality->id) }}"
                  id="deleteItem{{$modality->id}}" data-tr="tr_{{$modality->id}}" title="Delete"><span
                    class="glyphicon glyphicon-trash"></span></a>
                  <?php
          }
                      ?>

                </td>
                </tr>


                <?php  $i++ ?>
        @endforeach

              <?php if (count($modalities) > 0) { ?>
              <div class="deleteAll">
                <a class="btn btn-danger btn-xs btn-bkp" onClick="deleteAll('modalities');" id="btn_deleteAll">
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