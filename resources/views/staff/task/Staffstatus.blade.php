
@extends('staff/layouts.app')

@section('title', 'Staff Status')

@section('content')

<section class="content-header">
      <h1>
      Staff Status
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Staff Status</li>
      </ol>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">


            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/task/deleteAll') }}" />
              @csrf
<div class="ul_staff_work_sec">
              <ul class="staff_current_loca">
               
                    <?php $i = 1; ?>
                    @foreach ($staff as $product)
                    <li>
                        <span class="staff_name"><?php echo $product->name ?> - </span>
                        

                        <?php  
                        $cur_date=date("Y-m-d");
                         $staff_leave = DB::select("select * from work_report_for_leave  where  staff_id='".$product->id."'   AND start_date='".$cur_date."' AND type_leave='Request Leave' ");
                        if(count($staff_leave)>0)
                        {
                          echo ' <span class="loc_status">Leave: '.$staff_leave[0]->attendance.' </span><br>';
                        }
                       
                        $j=1;
                        $cur_date=date("Y-m-d");
                        $field_count=0;
                        $alltask = DB::select("select daily.id as expence_id,task.user_id as task_user_id,daily.task_name,daily.travel_task_child_name,daily.start_time_travel,daily.end_time_travel from dailyclosing_expence as daily INNER JOIN task as task ON daily.travel_task_id=task.id where  daily.staff_id='".$product->id."'   AND daily.start_date='".$cur_date."' AND daily.expence_cat='travel' order by daily.id ASC");
                        if(count($alltask)>0)
                        {$field_count=1;
                          $going='';
                          $in='';
                          $end_time='';
                          $go_time='';
                          foreach($alltask as $values)
                          {
                            
                            if($values->travel_task_child_name!='')
                            {
                            //  echo ','.$values->travel_task_child_name;
                            }
                            if($values->task_user_id>0)
                            {
                              
                              $user = App\User::find($values->task_user_id);
                             
                              $going=$user->business_name;
                              $go_time=$values->start_time_travel;
                            }
                            if($values->end_time_travel!=null)
                            {
                              $in=$user->business_name;
                              $end_time=$values->end_time_travel;
                            }else{
                              $in='';
                              $end_time='';
                            }
                           
                         
                            $j++;
                          }
                          
                          if($going!='' && $in!='')
                          {

                            echo '<span class="loc_status">In: '.$in.'</span>';
                          }
                          else if($going!='' && $in=='')
                          {
                            echo '<span class="loc_status">Going To: '.$going.'</span>';
                          }
                       
                         

                        }
                        ?>
                        
                        <?php  
                        $cur_date=date("Y-m-d");
                        
                        $alltask_office = DB::select("select work.id as expence_id,task.user_id as task_user_id,work.task_name,work.task_child_name,work.start_time,work.end_time from  work_report_for_office as work INNER JOIN task as task ON work.task_id=task.id where  work.staff_id='".$product->id."'   AND work.start_date='".$cur_date."' order by work.id ASC");
                        if(count($alltask_office)>0)
                        {
                          $going='';
                          $in='';
                          foreach($alltask_office as $values_office)
                          {
                           
                  
                            if($values_office->task_child_name!='')
                            {
                             // echo ','.$values_office->task_child_name;
                            }
                            if($values_office->task_user_id>0)
                            {
                              
                           
                              $user = App\User::find($values_office->task_user_id);
                             
                              $going=$user->business_name;
                              $go_time=$values_office->start_time;
                            }
                            if($values_office->end_time!=null)
                            {
                              $in=$user->business_name;
                              $end_time_off=$values_office->end_time;
                            }else{
                              $in='';
                              $end_time_off='';
                            }

                          
                            $j++;
                          }

                         // echo 'end'.$end_time.'--'.$go_time;
                          if($going!='' && $in!='')
                          {
                            if($field_count=="0")
                            {
                              //echo '<span class="loc_status">In: '.$in.'</span>';
                              echo '<span class="loc_status">In: Office</span>';
                            }
                            
                          }
                          else if($going!='' && $in=='')
                          {
                            if($field_count=="0")
                            {
                             // echo '<span class="loc_status">Going To: '.$going.'</span>';
                             echo '<span class="loc_status">In: Office</span>';
                            }

                           
                          }

                          
                        }
                        ?>

                        </li>
                   
                      


                       <?php $i++ ?>
                     @endforeach

              

              </ul>

                </div>


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

 

 
<div class="modal fade" id="travel_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Work Details</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        
         <div class="ajax_resp">
         <img src="{{ asset('images/wait.gif') }}">
         </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>




@endsection

@section('scripts')


<script type="text/javascript">
  
  $(document).on('click',".details_travel",function(){
    
    var id=$(this).attr('attr-id');
    var typework=$(this).attr('attr-type');
    $("#travel_popup").modal("show");
    var url = APP_URL+'/staff/view_travel_all_details';
     $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              id:id,typework:typework
            },
            success: function (data)
            {    
              $(".ajax_resp").html(data);

            }

     });

  });
  jQuery(document).ready(function() {


        var oTable = $('#cmsTable').DataTable({
          "pageLength": 50,
          
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
