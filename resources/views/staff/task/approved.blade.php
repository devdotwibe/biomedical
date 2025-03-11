
@extends('staff/layouts.app')

@section('title', 'Manage Task')

@section('content')

<section class="content-header">
      <h1>
        Manage Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Task</li>
      </ol>

    </section>

<section class="content-header">
    <div class="row">
      <div class="col-md-2 col-xs-6 border-right">
      <h3 class="bold no-mtop">{{$notstart}}</h3>
      <p style="color:#989898" class="font-medium no-mbot">
        Not Started      </p>
     
    </div>
        <div class="col-md-2 col-xs-6 border-right">
      <h3 class="bold no-mtop">{{$progress}}</h3>
      <p style="color:#03A9F4" class="font-medium no-mbot">
        In Progress      </p>
     
    </div>
        <div class="col-md-2 col-xs-6 border-right">
      <h3 class="bold no-mtop">{{$testing}}</h3>
      <p style="color:#2d2d2d" class="font-medium no-mbot">
        Testing      </p>
     
    </div>
        
        <div class="col-md-2 col-xs-6 border-right">
      <h3 class="bold no-mtop">{{$complete}}</h3>
      <p style="color:#84c529" class="font-medium no-mbot">
        Complete      </p>
     
    </div>
      </div>

         </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">


            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('staff.task.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Task</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/task/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                  <th>No.</th>
                  <th>Task</th>
                  <th>Status</th>
                  <th>Start Date</th>
                  <th>Due Date</th>
                  <th>Assigned To</th>
             
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($task as $product)
                  
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="task">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td> -->
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><a class="popup" attr-id="{{$product->id}}"><?php echo $product->name ?></a></td>
                        <td>
                          <select name="status" id="status" class="form-control" onchange="change_status(this.value,{{$product->id}})">
                           
                            <option value="Not Started" <?php if($product->status=="Not Started"){echo "selected";}?>>Not Started</option>
                            <option value="In Progress"  <?php if($product->status=="In Progress"){echo "selected";}?>>In Progress</option>
                            <option value="Complete"  <?php if($product->status=="Complete"){echo "selected";}?>>Complete</option>
                          </select>
                          </td>

                        <td><?php echo $product->start_date ?></td>
                        <td><?php echo $product->due_date ?></td>

                         

                          <td><?php
                          $staff_all=explode(',',$product->assigns);
                          foreach($staff_all as $val_staff)
                            {
                              $staff = App\Staff::find($val_staff);
                            echo $staff->name.',';
                            }
                          ?>
                        </td>


                        <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.task.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                            
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($task) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('task');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div> -->
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
    </section>

 
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Details</h4>
      </div>
      <div class="modal-body">
        
        <div class="modal-body">
   <div class="row">
      <div class="col-md-8 task-single-col-left" style="min-height: 916px;">
                  <div class="task-single-related-wrapper"><h4 class="bold font-medium mbot15"> <a id="taskname">Test Task</a></h4></div>         <div class="clearfix"></div>
              
     
         <h4 class="th font-medium mbot15 pull-left">Description</h4>
       
                  <div class="clearfix"></div>
         <div class="tc-content"><div id="task_view_description"></div></div>         <div class="clearfix"></div>
       
        
        
         <div class="clearfix"></div>
       
             
            <h4 class="mbot20 font-medium">Comments</h4>
      
         <div class="tasks-comments inline-block full-width simple-editor">
            <input type="hidden" name="task_id" id="task_id">        
            <textarea name="comment" placeholder="Add Comment" id="task_comment" rows="3" class="form-control ays-ignore"></textarea>
            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
                  
            <input type="file" id="image_name" name="image_name" accept=".jpg,.jpeg,.png"/>
            <span class="error_message" id="image_name_message" style="display: none">Field is required</span>

            <button type="button" class="btn btn-info mtop10 pull-right" id="addTaskCommentBtn" autocomplete="off" data-loading-text="Please wait..." onclick="add_task_comment('1');">
            Add Comment            </button>

               <div class="load-sec" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                     <div class="clearfix"></div>

            <hr>   
              

            <div class="res_ajax">

            <!-- <div class="panel panel-default">
            <div class="panel-body">
            <p>Test</p>
            <p>2020-04-30 03:52:52</p>
            <a class="btn btn-danger btn-xs" onClick="deleteAll('task');" id="btn_deleteAll" >
             <span class="glyphicon glyphicon-trash"></span></a>
           </div>
            </div> -->

            

            </div>
         

         </div>
      </div>
      <div class="col-md-4 task-single-col-right">
        
         <h4 class="task-info-heading">Task Info</h4>
         <div class="clearfix"></div>
         <h5 class="no-mtop task-info-created">
                        <small class="text-dark">Created at <span class="text-dark" id="created_at"></span></small>
                     </h5>
         <hr class="task-info-separator">
        
                  <div class="task-info task-single-inline-wrap task-info-start-date">
            <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
               Start Date:
                              <span id="start_date"></span>
                           </h5>
         </div>
         <div class="task-info task-info-due-date task-single-inline-wrap">
            <h5>
               <i class="fa fa-calendar-check-o task-info-icon fa-fw fa-lg pull-left"></i>
               Due Date:
                            <span  id="due_date"></span>
                           </h5>
         </div>
         <div class="task-info task-info-priority">
            <h5>
               <i class="fa task-info-icon fa-fw fa-lg pull-left fa-bolt"></i>
               Priority:
                              <span class="task-single-menu task-menu-priority">
                  <span id="priority_dis" class="trigger pointer manual-popover text-has-action" style="color:#fc2d42;" data-original-title="" title="">
                                    </span>
               
               </span>
                           </h5>
         </div>
                
      
       
                
                  <div class="clearfix"></div>
       
         <hr class="task-info-separator">
         <div class="clearfix"></div>
         <h4 class="task-info-heading font-normal font-medium-xs"><i class="fa fa-user-o" aria-hidden="true"></i> Assignees</h4>
                 
                  <div class="task_users_wrapper" id="staff_dis">
            
               <!-- <div class="task-user" data-toggle="tooltip" data-title="Cleo Cremin">
             <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"></a>  <a href="#" class="remove-task-user text-danger" onclick="remove_assignee(2,1); return false;">
               </div>
               <div class="task-user" data-toggle="tooltip" data-title="Frederik Rohan">
               <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"></a>  <a href="#" class="remove-task-user text-danger" onclick="remove_assignee(1,1); return false;">
               </div>        -->
                </div> 
         <hr class="task-info-separator">
         <div class="clearfix"></div>
         <h4 class="task-info-heading font-normal font-medium-xs">
            <i class="fa fa-user-o" aria-hidden="true"></i>
            Followers         </h4>
                 
                  <div class="task_users_wrapper">
            
                <span class="task-user" id="follower_dis" data-toggle="tooltip" data-title="Frederik Rohan">
                <!-- <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"> -->
                </span> 
                         </div>
         
      </div>
   </div>
</div>

</div>
  </div>





      </div>
      
    </div>

  </div>
</div>



@endsection

@section('scripts')
<script type="text/javascript">
  function delete_task_comment(id,order_no){
    var url = APP_URL+'/staff/delete_task_comment';
    $("#row"+order_no).remove();
    
    $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  id:id
                },
                success: function (data)
                {    

                }
    });
  }
  function viewall_comments(){
    var task_id=$("#task_id").val();
    var url = APP_URL+'/staff/view_task_comment';
    $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  task_id:task_id
                },
                success: function (data)
                {    
                  var proObj = JSON.parse(data);
                  htmls=' ';
                  var j=1;
                  
                    for (var i = 0; i < proObj.length; i++) {
                  
                  var imgs="{{asset('storage/app/public/comment/')}}/"+proObj[i]["image_name"];
                         htmls +='<div class="panel panel-default" id="row'+i+'">'+
                            '<div class="panel-body">'+
                            '<p>'+proObj[i]["comment"]+'</p>'+
                            '<p>'+proObj[i]["created_at"]+'</p>';
                            if(proObj[i]["image_name"]!='')
                            {
                              htmls +='<a href="'+imgs+'" download="'+imgs+'"><img src="'+imgs+'" width="50px" height="50px;"/>Download</a><br>';
                            }
    
                            htmls += '<a class="btn btn-danger btn-xs" onClick="delete_task_comment('+proObj[i]["id"]+','+i+');" id="btn_deleteAll" >'+
                            '<span class="glyphicon glyphicon-trash"></span></a>'+
                          '</div>'+
                            '</div>';
                         
    
    
                          j++;
                        }
                    $(".res_ajax").html(htmls);
                    $(".load-sec").hide();
                
                }
              });

  }
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

        


        jQuery("#addTaskCommentBtn").click(function() {
          
        var comment=$("#task_comment").val();
          if(comment=='')
          {
            $("#task_comment_message").show();
          }
          else{

            
            $(".load-sec").show();
            $("#task_comment_message").hide();
            var task_id=$("#task_id").val();
            var url = APP_URL+'/staff/add_task_comment';
            var formData = new FormData();

               var image_name = $('#image_name').val();
               
              if(image_name != '') {    
          //    formData.append('image_name',$("#image_name")[0].files[0]);
              var file = document.getElementById('image_name').files[0];
              formData.append('image_name',file);  

              }
             // alert(image_name);
            formData.append('task_id',task_id);  
            formData.append('comment',comment);  
          //    console.log(formData)


            $.ajax({
              type: "POST",
          cache: false,
          processData: false,
          contentType: false,
            url: url,
            data:formData,
              
              success: function (data)
              {  
                $("#task_comment").val('');  
                //alert(data);
                viewall_comments()
              }
            });

         

           }
        });
        jQuery(".popup").click(function() {


          var id=$(this).attr("attr-id");
          
          $("#task_id").val(id);
          viewall_comments()
          var url = APP_URL+'/staff/view_task_details';
          $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              id:id
            },
            success: function (data)
            {    
              //alert(data);

              var proObj = JSON.parse(data);
              htmls=' ';
              var j=1;
              
               
                  
                  $("#taskname").html(proObj[0]);
                  $("#task_view_description").html(proObj[1]);
                  $("#created_at").html(proObj[2]);
                  $("#start_date").html(proObj[3]);
                  $("#due_date").html(proObj[4]);
                  $("#priority_dis").html(proObj[5]);
                  $("#staff_dis").html(proObj[6]);
                  $("#follower_dis").html(proObj[7]);
                  
                  
                  
               


              $("#myModal").modal("show");
                
            
            }
          });


         
         }); 
        
    });

    
function change_status(status,id){

var url = APP_URL+'/staff/change_task_status';
$.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        status: status,id:id
      },
      success: function (data)
      {    
        //alert(data);
        location.reload();
          
       
      }
    });

}


function change_priority(status,id){

var url = APP_URL+'/staff/change_task_priority';
$.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        status: status,id:id
      },
      success: function (data)
      {    
       // alert(data);
         
          location.reload();
       
      }
    });

}


 
</script>
@endsection
