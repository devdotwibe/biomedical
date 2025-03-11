

@extends('staff/layouts.app')

@section('title', 'Manage Dispatch')

@section('content')

<section class="content-header">
      <h1>
        Manage Dispatch
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Dispatch</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">

          <div class="box">
               <a class="add-button " href="{{ route('staff.dispatch.create') }}"></span>  Add Dispatch Without Invoice</a>
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
             <div class="box-body row">
        <div class="col-md-12">
            <div class="box-body table-box">
            <h3>Pending Dispatch</h3>
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/dispatch/deleteAll') }}" />
              @csrf
        
              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
               
                  <th>Invoice Id</th>
                
                  <th>Added Date</th>
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($transation_pending as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="dispatch">
                      
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td>INVOICE_<?php echo $product->invoice_id ?></td>
                  
                        <td>{{$product->created_at}}</td>

                        <td class="alignCenter">

                        @if($product->invoice_id>0)
                        <!-- <a href="{{ route('staff.dispatch_verify',$product->invoice_id) }}" > Despatch Verify</a> -->
                        @endif
                        @if($product->invoice_id==0)
                        <!-- <a href="{{ route('staff.dispatch_verify',$product->dispatch_id) }}" > Despatch Verify</a> -->
                        @endif
                        @if($product->status=="Dispatch Invoice")
                        @if($product->current_status=="Pending")
                        <a href="{{ route('staff.create_dispatch',$product->invoice_id) }}" >Create Despatch With Invoice</a>
                        @endif

                        @if($product->current_status=="Verification")
                        Waiting For Verification
                        @endif


                        @endif

                        @if($product->status=="Dispatch Verify")
                        @if($product->invoice_id>0)
                        <a href="{{ route('staff.dispatch_verify',$product->invoice_id) }}" > Despatch Verify</a>
                        @endif
                        @if($product->invoice_id==0)
                        <a href="{{ route('staff.dispatch_verify',$product->dispatch_id) }}" > Despatch Verify</a>
                        @endif
                        @endif


                            <!-- <a class="btn btn-primary btn-xs" href="" title="Send">Send</span></a> -->
                            

                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

           

              </table>
            </form>
            </div>
             </div>
              </div>


              <div class="box-body row">
        <div class="col-md-12">
            <div class="box-body table-box">
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/dispatch/deleteAll') }}" />
              @csrf
              <h3>Completed Dispatch</h3>
              <table id="cmsTable1" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                 
                  <th>No.</th>
               
                  <th>Invoice Id</th>
                
                  <th>Added Date</th>
                
                  <!-- <th class="alignCenter">Action</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($dispatch as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="dispatch">
                      
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td>
                        @if($product->invoice_no>0)
                        INVOICE_{{$product->invoice_id}}
                        @endif
                        @if($product->invoice_id=="")
                        Dispatch Without invoice
                        @endif
                       
                        </td>
                    
                       
                  
                        <td>{{$product->created_at}}</td>
                       
                        <!-- <td class="alignCenter">
                      
                        </td> -->
                      </tr>


                       <?php $i++ ?>
                     @endforeach

           

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
        var oTable1 = $('#cmsTable1').DataTable({

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
