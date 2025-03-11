@extends('staff/layouts.app')

@section('title', 'Manage Products')

@section('content')


<section class="content-header">
  <h1>
    Manage Products
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Products</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="manage-prdct">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">

        @php
          
          $staff_id = session('STAFF_ID');

          $permission = \App\User_permission::where('staff_id', $staff_id)->first();

        @endphp

        @if(optional($permission)->product_create == 'create')

          <div class="row">
            <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                <a class="add-button " href="{{ route('staff.products.create') }}"> Add Product</a>
              </div>
            </div>
          </div>

        @endif

        @if (session('success'))
      <div class="alert alert-success alert-block fade in alert-dismissible show">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ session('success') }}</strong>
      </div>
    @endif
        <!-- /.box-header -->
        <div class="box-body">



          <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/products/deleteAll') }}" />
          @csrf

          <table id="cmsTable" class="table table-bordered table-striped data-">

            <thead>

              <tr class="headrole">
                <th>No.</th>
                <th>Product Name</th>
                <th> Part No.</th>
                <th>Care Area</th>
                <th>Category</th>
                <th>Modality</th>
                <th>Brand</th>
                <th>Company</th>
                <th>Unit</th>
                {{-- <th>Max Sale Amount</th>
                <th>Min Sale Amount</th> --}}
                <th>Tax Percentage</th>
                <th>HSN Code</th>


                <th class="alignCenter">Action</th>


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
  </div>
</section>



@endsection

@section('scripts')

<script type="text/javascript">


  jQuery(document).ready(function () {

    var oTable = $('#cmsTable').DataTable({
      processing: true,
      serverSide: true,

      ajax: {
        url: "{{ route('staff.products.index') }}",
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

      columns: [
        {
          "data": 'DT_RowIndex',
          orderable: false,
          searchable: false
        },


        {
          data: 'name',
          name: 'name',
          orderable: true,
          searchable: true,
        },

        {
          data: 'part_no',
          name: 'part_no',
          orderable: false,
          searchable: true,
        },

        {
          data: 'category_name',
          name: 'category_name',
          orderable: true,
          searchable: true,
        },
        {
          data: 'category_type',
          name: 'category_type',
          orderable: true,
          searchable: true,
        },
        {
          data: 'modality',
          name: 'modality',
          orderable: true,
          searchable: true,
        },

        {
          data: 'brand_name',
          name: 'brand_name',
          orderable: true,
          searchable: true,
        },
        {
          data: 'company_name',
          name: 'company_name',
          orderable: true,
          searchable: true,
        },
        {
          data: 'unit',
          name: 'unit',
          orderable: true,
          searchable: true,
        },

        // {
        //   data: 'max_sale_amount',
        //   name: 'max_sale_amount',
        //   orderable: true,
        //   searchable: true,
        // },
        // {
        //   data: 'min_sale_amount',
        //   name: 'min_sale_amount',
        //   orderable: true,
        //   searchable: true,
        // },
        {
          data: 'tax_percentage',
          name: 'tax_percentage',
          orderable: true,
          searchable: true,
        },
        {
          data: 'hsn_code',
          name: 'hsn_code',
          orderable: true,
          searchable: true,
        },

        {
          data: 'action',
          name: 'action',
          orderable: false
        },
 

      ]


    });

    $('#cmsTable').on('click', '.deleteItem', function () {

      var id = $(this).attr('id');
      var url = $(this).attr('href');
      $('#btnDeleteItem').attr('data-id', id);
      $('#btnDeleteItem').attr('data-href', url);
      $('#modalDelete').modal();
      return false;

    });


  });



</script>
@endsection