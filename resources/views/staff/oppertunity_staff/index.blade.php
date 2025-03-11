

@extends('staff/layouts.app')

@section('title', 'Manage Opportunity')

@section('content')


<section class="content-header">
      <h1>
        Manage Opportunity
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Opportunity</li>
      </ol>
</section>

    <!-- Main content -->
<div class="se-pre-con1"></div>
<section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ url('staff/create_oppertunity') }}"> <span class="glyphicon glyphicon-plus"></span>Add Opportunity</a>

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



              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/oppertunities/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-" onmousedown="return false" onselectstart="return false">
                <thead>
                <tr class="headrole">
                    <th><input type="checkbox" name="select_all" value="1" id="select_all" class="select-checkbox"></th>
                    <!-- <th>No.</th> -->
                    <th>Opportunity Reference</th>
                    <th>Opportunity</th>
                    <th>Account Name</th>
                    <th>Created By</th>
                    <!-- <th>Engineer Name</th> -->
                    <th>Amount</th>
                    <th>Deal Stage</th>
                    <th>Es.Order</th>
                    <th>Es.Sales</th>
                    <th>Order Forcast Category</th>
                    <th>Support</th>
                    <th>Type</th>
                    <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if(sizeof($oppertunity)>0)
                      @foreach($oppertunity as $op)
                      <tr id="tr_{{$op->id}}" data-id="{{$op->id}}" data-from ="subcategory">
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$op->id}}" id="check{{$op->id}}" data-id="{{$op->id}}">
                        <!-- <td><span class="slNo">{{$i}} </span></td> -->
                        <td>{{$op->op_reference_no}}</td>
                        <td><a href="" class="viewer" data='<?=json_encode($op) ?>' data-id="{{$op->id}}" data-user_id="{{$op->user_id}}">{{$op->oppertunity_name}}</a></td>
                        <td><a href="{{ url('staff/view_customer/'.$op->user_id) }}">{{$op->customer->business_name}}</a></td>
                        <td>{{$op->created_by_name}}</td>
                        <td>{{$op->amount}}</td>
                        @php $deal_stage = array('Lead Qualified/Key Contact Identified',
                                                 'Customer needs analysis',
                                                 'Clinical and technical presentation/Demo',
                                                 'CPQ(Configure,Price,Quote)',
                                                 'Customer Evaluation',
                                                 'Final Negotiation',
                                                 'Closed-Lost',
                                                 'Closed-Cancel',
                                                 'Closed Won - Implement'
                                                 );
                        @endphp
                        <td>{{$deal_stage[$op->deal_stage]}}</td>
                        <td>{{$op->es_order_date}}</td>
                        <td>{{$op->es_sales_date}}</td>
                        @php
                          $order_forcast =  array('Unqualified',
                                                  'Not addressable',
                                                  'Open',
                                                  'Upside',
                                                  'Committed w/risk',
                                                  'Committed'
                                            );
                        @endphp
                        <td>{{$order_forcast[$op->order_forcast_category]}}</td>
                        <!--<td>{{$op->description}}</td>-->
                        <td>
                          @if($op->support!='')
                            @php $support =  array('Demo',
                                                  'Application/ clinical support',
                                                  'Direct company support',
                                                  'Senior Engineer Support',
                                                  'Price deviation'
                                              );
                            @endphp
                            {{$support[$op->support]}}
                          @else
                          
                          @endif
                        </td>
                         <td>
                          @if($op->type==1) Sales @else Contract @endif
                        </td>
                    
                        <td>
                        <a href="{{url('staff/list_oppertunity_products/'.$op->id)}}" class="btn btn-primary btn-sm">Products</a>
                        @if($user_permission>0)
                        
                          <a href="{{url('staff/edit_oppertunity/'.$op->id)}}" class="btn btn-info btn-sm">View</a> 
                          @endif
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

                 <?php if(count($oppertunity) > 0) { ?>
                  
                  <!-- <div class="deleteAll">
                    <span class="rows_selected" id="select_count">0 Selected</span><br>
                    <button type="button"class="btn btn-danger btn-xs" id="btn_deleteAll" >
                       <span class="glyphicon glyphicon-trash"></span> Delete All Selected</button>
                  </div> -->
                
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

      <div class="modal fade inprogress-popup" id="chModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" >
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Opportunity Details</h4>
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
   $("#cmsTable").on("click", ".viewer", function(e) {
  
        $(".se-pre-con1").fadeIn();
      var datas   = $(this).attr('data');
      var id      = $(this).attr('data-id');
      var user_id = $(this).attr('data-user_id');
     
      $.post("{{url('staff/chatterdetail')}}", {data: id,user_id:user_id, "_token": "{{ csrf_token() }}"}, function(result,status){
        
        $('#contain').html(result);
        $('#chModal').modal();
        $(".se-pre-con1").fadeOut();
      });
      e.preventDefault();
    });

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
      id.push($(this).data('id'));
   });
   
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one checkbox");
   }
   else
   {

    var url = APP_URL+'/staff/delete_oppertunity';
    $.ajax({
     url:url,
     method:'POST',
     data:{id:id},
     success:function()
     {
      for(var i=0; i<id.length; i++)
      {
       $('#tr_'+id[i]+'').css('background-color', '#ccc');
       $('#tr_'+id[i]+'').fadeOut('slow');
      }
      $("#select_count").html(" 0 Selected");
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#contact').multiselect();
   
});
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

        
    $('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
            minDate: 0  
            
    });
    $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
    });
</script>

  
@endsection
