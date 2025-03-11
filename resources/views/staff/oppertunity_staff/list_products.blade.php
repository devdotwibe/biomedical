

@extends('staff/layouts.app')

@section('title', 'Opportunity Products')

@section('content')


<section class="content-header">
      <h1>
        Manage Opportunity Products ({{$op_name->oppertunity_name}})
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
</section>

    <!-- Main content -->
<div class="se-pre-con1"></div>
<section class="content">
      <div class="row">
        <div class="col-md-9">

          <div class="box">

            <div class="row">

              <div class="col-lg-12 margin-tb">

                  <div class="pull-left">

                      <a class="add-button " href="{{ url('staff/create_oppertunity_product/'.$id) }}"> Add Products</a>

                  </div>

                  <div class="pull-right">
                    <a href="{{url('staff/edit_oppertunity/'.$id)}}" class="btn btn-warning btn-sm">Opportunity - {{$op_name->oppertunity_name}}</a>
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

              <table id="cmsTable" class="table table-bordered table-striped data-" data-id="{{$id}}">
                <thead>
                <tr class="headrole">
                    <th><input type="checkbox" name="select_all" value="1" id="select_all" class="select-checkbox"></th>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price </th>
                    <th>Net Amount</th>
                    
                    <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if(sizeof($products)>0)
                      @foreach($products as $op)
                      <tr id="tr_{{$op->id}}" data-id="{{$op->id}}" data-from ="subcategory" >
                        @if($op->optional==0)
                        <td data-th=""><input type="checkbox" class="dataCheck" name="ids[]" value="{{$op->id}}" id="check{{$op->id}}" data-id="{{$op->id}}" data-prid="{{$op->product_id}}">
                        <td data-th="No."><span class="slNo">{{$i++}} </span></td>
                        @else
                        <td  data-th="Product" colspan="2">Optional</td>
                        @endif
                        <td data-th="Quantity">{{$op->product->name}}</td>
                        <td data-th="Unit Price">{{$op->quantity}}</td>
                        <td data-th="Net Amount">{{$op->sale_amount}}</td>
                        <td data-th="Action">{{$op->amount}}</td>
                        <td data-th="">
                          <a href="{{url('staff/edit_oppertunity_product/'.$op->id.'/'.$id)}}" class="edit-btn"><img src="{{ asset('images/edit.svg') }}" alt="" /></a>
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
              <br><br><br>

                <?php if(count($products) > 0) { ?>
                 
                  <div class="deleteAll">
                    <span class="rows_selected" id="select_count">0 Selected</span><br>
                    <a class="sml-btn cancel-btn  "  id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected
                    </a>
                      @if($op_name->quote_status==0)
                        <a class="mdm-btn submit-btn  " id="btn_quote"> <span class="glyphicon glyphicon-plus"></span>Generate Quote</a>
                      @endif
                  </div>
                
                <?php } ?>
              
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

      
        <div class="col-md-3 rightside-menu">
          <div class="box box-primary">
              <div class="panel-body padding-10">
                  <h4 class="bold">
                    Quote History 
                  </h4>
              </div>
              <table id="qhTable" class="table table-bordered table-striped ">
                <tr>
                  <td>Id</td>
                  <td>Created at</td>
                  <td>Quote</td>
                  <td>Preview</td>
                </tr>
                @php $i=1; @endphp
                @if(sizeof($qhistory)>0)
                  @foreach($qhistory as $qh)
                    <tr>
                      <td>{{$i++}}</td>
                      <td>{{$qh->created_at}}</td>
                      <td>{{$qh->quote_reference_no}}</td>
                      <td><a class="btn btn-sm btn-default" target="_blank" id="btn_preview" href="{{url('staff/preview_quote/'.$qh->id)}}"> Preview</a>
                          @if($qh->quote_status=='request')
                            <a class="viewer btn btn-sm btn-warning" data-id="{{$qh->id}}" > Send</a> <!-- id="btn_send" href="{{url('staff/send_quote/'.$qh->id)}}" -->
                          @else
                            <a class="btn btn-sm btn-warning" disabled> </span>Already Send</a>
                          @endif
                      
                      </td>
                    </tr>
                  @endforeach
                @else
                <tr><td>No Record Found</td></tr>
                @endif
              </table>
           </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="modal fade inprogress-popup" id="mailModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" >
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Mail</h4>
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
           "bInfo" : false
        });
        var oTable = $('#qhTable').DataTable({
           "bInfo" : false
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
   $("#qhTable").on("click", ".viewer", function(e) {
  
        $(".se-pre-con1").fadeIn();
      var data=$(this).data('id');
     // alert(data);
      $.post("{{url('staff/quote_send')}}", {id: data, "_token": "{{ csrf_token() }}"}, function(result,status){
        
        $('#contain').html(result);
        $('#mailModal').modal();
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

    var url = APP_URL+'/staff/delete_oppertunity_product';
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

 $('#btn_quote').click(function(){
  
  
   var id = [];
   $('.dataCheck:checked').each(function(i){
      //id[i] = $(this).data(id);
      id.push($(this).data('prid'));
   });
   
   
   if(id.length === 0) //tell you if the array is empty
   {
    alert("Please Select atleast one product");
   }
   else
   {

    var url   = APP_URL+'/staff/generate_quote';
    var op_id = $('#cmsTable').data('id');
    $.ajax({
     url:url,
     method:'POST',
     data:{id:id, op_id:op_id},
     success:function()
     {
       /*$('#btn_quote').hide();
       $('#btn_preview').show();
       $('#btn_send').show();*/
       location.reload(true);
     }
     
    });
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
    $('#contact').multiselect({
      nonSelectedText: 'Select Contact',
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      buttonWidth:'400px'
     });
   
});
</script>
  
@endsection
