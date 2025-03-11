

@extends('staff/layouts.app')

@section('title', 'Order')

@section('content')


<section class="content-header">
      <h1>
        Manage Order 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Order</li>
      </ol>
</section>

    <!-- Main content -->
<div class="se-pre-con1"></div>
<section class="content">
      <div class="row">
        <div class="col-md-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ url('staff/create_order') }}"> <span class="glyphicon glyphicon-plus"></span>Add Order</a>
                
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



              <form name="dataForm" id="dataForm" method="post" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped">
                <thead>
                <tr class="headrole">
                    <th><input type="checkbox" name="select_all" value="1" id="select_all" class="select-checkbox"></th>
                    <th>No.</th>
                    <th>Order No</th>
                    <th>Oppertunity</th>
                    <!--<th>Products</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Net Amount</th>    -->
                    <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if(sizeof($order)>0)
                      @foreach($order as $op)
                      <tr id="tr_{{$op->id}}" data-id="{{$op->id}}" data-from ="subcategory" >
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$op->id}}" id="check{{$op->id}}" data-id="{{$op->id}}">
                        <td><span class="slNo">{{$i++}} </span></td>
                        <td><a href="" class="viewer" data-id="{{$op->order_no}}">{{$op->order_no}}</a></td>
                        <td>
                          @if($op->oppertunity_id!='' && $op->oppertunity_id!=0)
                            {{$op->oppertunity->oppertunity_name}}</td>
                          @endif

                        <!-- <td></td>
                        <td></td> -->
                        <td class="alignCenter">
                          <a href="{{url('staff/edit_order/'.$op->order_no)}}" class="btn btn-info btn-sm">Edit</a>
                        </td>
                      </tr>

                      @endforeach
                    @else
                    <tr>
                      <td colspan="11">No Records found</td>
                    </tr>
                    @endif
                
                
                </tbody>
              </table>
                <br><br>
                <?php if(count($order) > 0) { ?>
                 
                  <div class="deleteAll">
                    <span class="rows_selected" id="select_count">0 Selected</span><br>
                    <a class="btn btn-danger btn-sm"  id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected
                    </a>
                  </div>
                
                <?php } ?>
              
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content modal-lg">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Products</h4>
            </div>
            <div class="modal-body" id="contain">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             
            </div>
          </div>
        </div>
      </div>-->

       <div class="modal fade inprogress-popup" id="orModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" >
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Order Details</h4>
            </div>
            <div class="modal-body" id="contain">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
          </div>
        </div>
      </div>

</section>



@endsection

@section('scripts')

<script>
  $(document).ready(function(){
   $("#cmsTable").on("click", ".viewer", function(e) {
  
      $(".se-pre-con1").fadeIn();
      var data=$(this).attr('data-id');
     // alert(data);
      $.post("{{url('staff/orderdetail')}}", {data: data, "_token": "{{ csrf_token() }}"}, function(result,status){
        
        $('#contain').html(result);
        $('#orModal').modal();
        $(".se-pre-con1").fadeOut();
      });
      e.preventDefault();
    });

  });
  </script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({
           "bInfo" : false,

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
        var oTable = $('#qhTable').DataTable({
           "bInfo" : false,

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
      $('.openTable').on('click',  function () {alert()
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

        // Sortable rows


    });

  </script>

  <script>
$(document).ready(function(){
 
 $('#btn_deleteAll').click(function(){
  
  if(confirm("Are you sure you want to delete this?"))
  {
   var id = [];
   
   $('.dataCheck:checked').each(function(i){
      //id[i] = $(this).data(id);
      //alert($(this).attr('data-id'));
      id.push($(this).attr('data-id'));
   });
   
   //alert(id);
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one checkbox");
   }
   else
   {

    var url   = APP_URL+'/staff/delete_order';
    var op_id = $('#cmsTable').data('id');
    $.ajax({
     url:url,
     method:'POST',
     data:{id:id, op_id:op_id},
     success:function()
     {
      for(var i=0; i<id.length; i++)
      {
       $('#tr_'+id[i]+'').css('background-color', '#ccc');
       $('#tr_'+id[i]+'').fadeOut('slow');
      }
      $("#select_count").html(" 0 Selected");
      location.reload(true);
     }
     
    });
   }
   
  }
  else
  {
   return false;
  }
 });

});
</script>

<script type="text/javascript">
  $(document).on('click', '#select_all', function() {
      $(".dataCheck").prop("checked", this.checked);
      $("#select_count").html($("input.dataCheck:checked").length+" Selected");
  });
  $(document).on('click', '.dataCheck', function() {
      if ($('.dataCheck:checked').length == $('.dataCheck').length) {
      $('#select_all').prop('checked', true);
      } else {
      $('#select_all').prop('checked', false);
      }
      $("#select_count").html($("input.dataCheck:checked").length+" Selected");
  });
</script>

  
@endsection
