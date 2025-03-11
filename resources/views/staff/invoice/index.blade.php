

@extends('staff/layouts.app')

@section('title', 'Manage Invoice')

@section('content')

<section class="content-header">
      <h1>
        Manage Invoice
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Invoice</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">

          <!-- <div class="box">
               <a class="add-button " href="{{ route('staff.invoice.create') }}"> Create Invoice</a>
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
             <div class="box-body row">
        <div class="col-md-12">
            <div class="box-body table-box">
            <!-- <h3>Pending Transaction </h3> -->
            <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/invoice/deleteAll') }}" />
              @csrf
<!-- 
              <table id="cmsTable_trans" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
                  <th>Transaction Id</th>
                  <th>Date</th>
                  

                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($transaction as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="invoice">
                      
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Transaction Id" >Trans_<?php echo $product->id ?></td>
                      
                        <td data-th="Status" >{{$product->updated_at}}</td>
                       

                        <td data-th="Action"  class="alignCenter">
                        
                         <a class="add-button " href="{{ route('staff.invoice.create') }}?id={{$product->id}}"> Create Invoice</a>
                      

                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach


              </table> -->
            </form>

<h3>Invoice All</h3>
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/invoice/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
                  <th>Invoice Id</th>
                  <th>Invoice Date</th>
                  <th>Status</th>

                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($invoice as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="invoice">
                      
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Invoice Id" ><?php echo $product->invoice_id ?></td>
                      
                        <td data-th="Status">{{ date('d-m-Y', strtotime($product->invoice_date)) }}</td>
                        <td data-th="Status" >{{$product->status}}</td>

                        <td data-th="Action"  class="alignCenter">
                        <a class="view-btn" target="_blank" id="btn_preview" href="{{url('staff/preview_invoice/'.$product->id)}}"> <img src="{{ asset('images/view.svg') }}"></a>
                         
                            <!-- <a class="btn btn-primary btn-xs" href="" title="Send">Send</span></a> -->
                            

                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($invoice) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="sml-btn cancel-btn  " onClick="deleteAll('invoice');" id="btn_deleteAll" >
                                Delete All Selected</a>
              </div> -->
               <?php } ?>

              </table>
            </form>
            </div>
             </div>
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
        var oTable = $('#cmsTable_trans').DataTable({

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
