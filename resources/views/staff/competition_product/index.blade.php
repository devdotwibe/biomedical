@extends('staff/layouts.app')

@section('title', 'Manage Competition Product')

@section('content')

<section class="content-header">
  <h1>
    Manage Competition Product
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Competition Product</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="manage-comp-pdt">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="row">
            <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                <a class="add-button" href="{{ route('staff.competition_product.create') }}"> Add Competition
                  Product</a>
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
            <form name="dataForm" id="dataForm" method="post"
              action="{{ url('/staff/competition_product/deleteAll') }}" />
            @csrf

            <table id="cmsTable" class="table table-bordered table-striped data-">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Brand</th>
                  <th>Category</th>
                  <th>Care Area</th>
                  <th>Product Type</th>

                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->

                  <th class="alignCenter">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                @foreach ($competition_product as $product)
                  <?php 
                     $brands = App\Brand::find($product->brand_id);
            $cats = App\Category::find($product->category_id);
            $catstype = App\Category_type::find($product->category_type_id);
            $protype = App\Product_type::find($product->product_type_id);
                    ?>
                  <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from="competition_product">
                    <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}"
                      id="check{{$product->id}}">
                    </td>
                    <td>
                    <span class="slNo">{{$i}} </span>
                    </td>
                    <td><?php  echo $product->name ?></td>
                    <td> @if($brands) {{$brands->name}} @endif</td>
                    <td> @if($catstype) {{$catstype->name}} @endif</td>
                    <td> @if($cats) {{$cats->name}} @endif</td>
                    <td> @if($protype) {{$protype->name}} @endif</td>


                    <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>


                    <td class="alignCenter">
                    <a class="btn btn-primary btn-xs" href="{{ route('staff.competition_product.edit', $product->id) }}"
                      title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a class="btn btn-danger btn-xs deleteItem table-icon"
                      href="{{ route('staff.competition_product.destroy', $product->id) }}"
                      id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span
                      class="glyphicon glyphicon-trash"></span></a>
                    </td>
                  </tr>


                  <?php  $i++ ?>
        @endforeach

                <?php if (count($competition_product) > 0) { ?>
                <div class="deleteAll">
                  <a class="btn btn-danger btn-xs btn-bkp" onClick="deleteAll('competition_product');"
                    id="btn_deleteAll">
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