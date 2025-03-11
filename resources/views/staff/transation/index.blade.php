@extends('staff/layouts.app')

@section('title', 'Manage Transaction')

@section('content')

<section class="content-header">
  <h1>
    Manage Transaction
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manage Transaction</li>
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

              <a class="add-button " href="{{ route('staff.sales_order') }}">Add Transaction</a>

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
        <div class="row innertable-box">
          <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/transation/deleteAll') }}" />
          @csrf

          <div class="col-sm-12"> 
          <table id="cmsTable" class="table table-bordered table-striped data-">
            <thead>
              <tr>
                <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                <th>No.</th>
                <th>Transation Id</th>
                <th>Type</th>
                <th>PO-Collected Date</th>
                <th>Customer</th>

                <th>Status</th>
                <th>Product</th>

                <th>PO Value</th>

                <th class="alignCenter">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @foreach ($transation as $product)
                <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from="transation">
                <!-- <td data-th="" ><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                    </td> -->
                <td data-th="No.">
                  <span class="slNo">{{$i}} </span>
                </td>
                <td data-th="Transation Id">
                  <a href="{{ route('staff.transation_details', $product->id) }}">
                  Trans<?php  echo $product->id ?></a>
                </td>
                <td data-th="Type">
                  @if($product->type_conf == "Test Return")
            Test Return
          @endif
                  @if($product->type_conf != "Test Return")
            Sales Order
          @endif

                </td>

                <td data-th="PO Collected Date">{{ date('d-m-Y', strtotime($product->collect_date)) }}</td>
                <td data-th="Customer"><?php
          $total_sum = DB::select("SELECT SUM(amt) as amt FROM `transation_product` WHERE `transation_id`=" . $product->id . " ");


          if ($product->user_id > 0) {

          $user = App\User::find($product->user_id);
          if ($user) {
            echo $user->business_name;
          }
          }
                      ?>
                </td>


                @if($product->approval_company == "Y" && $product->approval_product == "Y" && $product->approval_config == "Y" && $product->approval_customer == "Y")

          <td data-th="">Technical Section Approved</td>
        @endif
                @if($product->approval_company == "N" || $product->approval_product == "N" || $product->approval_config == "N" || $product->approval_customer == "N")

          <td data-th="Status"> {{$product->current_status}} Pending</td>
        @endif

                <td data-th="Product"><a onclick="view_product({{$product->id}})">View Product</a></td>

                <td data-th="PO Value">{{ number_format($total_sum[0]->amt, 2, '.', '')}}</td>



                <td data-th="Action" class="alignCenter">
                  <a class="edit-btn" href="{{ route('staff.transation.edit', $product->id) }}" title="Edit"><img
                    src="{{ asset('images/edit.svg') }}" alt="" /></a>

                  <!-- <a class="delete-btn" href="{{ route('staff.transation.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><img src="{{ asset('images/delete.svg') }}" alt="" /></a> -->

                </td>
                </tr>


                <?php  $i++ ?>
        @endforeach

              <?php if (count($transation) > 0) { ?>

              <?php } ?>

          </table>
          </div>
          <!-- <div class="deleteAll">
                 <a class="mdm-btn cancel-btn" onClick="deleteAll('transation');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div> -->
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



<div class="modal fade" id="modal_trans_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Product List</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>

          <table id="cmsTable" class="table table-bordered table-striped data-">
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>

                <!-- <th>Qty</th>
                    <th>FOC</th>
                    <th>Unit Price</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                    
                        <th>MSP</th>
                        <th>Surplus / Deficit</th>
                    <th>Net Amount</th> -->

              </tr>
            </thead>
            <tbody id="tabledata">
            </tbody>
          </table>





        </form>
      </div>
      <div class="modal-footer">

        <button type="button" class="mdm-btn-line submit-btn" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

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


  function view_product(transation_id) {
    var url = APP_URL + '/staff/view_transation_all_product';
    $.ajax({
      type: "POST",
      cache: false,
      url: url,
      data: {
        transation_id: transation_id,
      },
      success: function (data) {
        var proObj = JSON.parse(data);
        var htmlscontent = '';
        var c = 1
        for (var i = 0; i < proObj.length; i++) {

          if (proObj[i]["image_name"] == null || proObj[i]["image_name"] == '') {
            var imgs = "{{asset('images/')}}/no-image.jpg";
          }
          else {
            var imgs = "{{asset('storage/app/public/products/thumbnail/')}}/" + proObj[i]["image_name"];
          }
          var quantity = proObj[i]["qty"];
          var sale_amount = proObj[i]["sale_amount"];
          var amt = proObj[i]["amt"];
          var cgst = proObj[i]["cgst"];
          var sgst = proObj[i]["sgst"];
          var igst = proObj[i]["igst"];
          var cess = proObj[i]["cess"];
          var surplus_amt = proObj[i]["surplus_amt"];
          htmlscontent += '<tr class="tr_' + proObj[i]["id"] + '"><td>' + c + '</td><td>' + proObj[i]["name"] + '</td>';

          htmlscontent += '  </tr>';

          //tabledata
          c++;
        }
        $("#tabledata").html(htmlscontent);
        $("#modal_trans_product").modal("show");
      }
    });
  }

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


    // Add event listener for opening and closing details
    // $('#cmsTable tbody').on('click', 'td.details-control', function () {
    $('.openTable').on('click', function () {
      var tr = $(this).closest('tr');
      var row = oTable.row(tr);

      var id = $(tr).attr('data-id');
      var from = $(tr).attr('data-from');

      if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
      }
      else {
        var resp = getRowDetails(id, from, row, tr);
      }
    });

  });

</script>
@endsection