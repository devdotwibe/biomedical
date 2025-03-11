

@extends('staff/layouts.app')

@section('title', 'Manage Lead Option')

@section('content')


<section class="content-header">
      <h1>
        Lead Option
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Lead Option</li>
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

                <a class="btn btn-sm btn-success" href="{{ url('staff/create_lead_option') }}"> <span class="glyphicon glyphicon-plus"></span>Add Lead Option</a>

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

               <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr class="headrole">
                   <!--  <th><input type="checkbox" name="select_all" value="1" id="select_all" class="select-checkbox"></th> -->
                    <th>No.</th>
                    <th>Account Name</th>
                    <th>Contact Name</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Assigned By</th>
                    <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if(sizeof($leadop)>0)
                      @foreach($leadop as $op)
                      <tr id="tr_{{$op->id}}" data-id="{{$op->id}}" data-from ="subcategory">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$op->id}}" id="check{{$op->id}}" data-id="{{$op->id}}"> -->
                        <td><span class="slNo">{{$i}} </span></td>
                        <td>
                          @if($op->user_id!='')<a href="{{ route('admin.customer.edit',$op->user_id) }}">{{optional($op->customer)->business_name}}</a>
                          @else {{$op->customer_name}}
                          @endif
                        </td>
                        <td>
                          @if($op->contact_person_id!=''){{$op->contact->name}}
                          @else {{$op->contact_person_name}}
                          @endif
                        </td>
                        <td>{{$op->description}}</td>
                        @php
                        if($op->staff_id>0)
                        {
                         $staff_det=DB::select("select * from staff where id='".$op->staff_id."'");
                 
                         if($staff_det){
                            $staff_name=$staff_det[0]->name;
                          }else{ $staff_name='';}
                             
                        }else{
                          $staff_name='';
                        }
                        @endphp

                        <td>{{$staff_name}}</td>
                        <td>{{$op->created_by_name}}</td>
                        <td>
                          @if(($op->status=='Active')&&($op->staff_status==''))
                          @if($user_permission>0)
                            <a href="{{url('staff/convert_opportunity/'.$op->id)}}" class="btn btn-success btn-sm">Convert to opportunity</a> 
                            <a href="{{url('staff/cancel_lead_option/'.$op->id)}}" class="btn btn-danger btn-sm">Cancel</a> 
                            @endif
                          @else
                            @if($op->status=='Cancel'))
                              Cancelled
                            @else
                              {{$op->staff_status}}
                            @endif
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

    var url = APP_URL+'/admin/delete_lead_option';
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


@endsection
