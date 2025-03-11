
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

        <div class="col-md-2 col-xs-6 border-right orange-bdr">
        <div class="prgs-outer">
        <p class="font-medium no-mbot">
        Today</p>
      <h3 class="bold no-mtop"><?php echo count($currenttask);?> </h3>
      		<div class="meter orange">
                  <span style="width:15%" class="today_count"></span>
            </div>
     	</div>
    </div>

      <div class="col-md-2 col-xs-6 border-right red-bdr">
      	<div class="prgs-outer">
              <p class="font-medium no-mbot">
                Pending</p>
              <h3 class="bold no-mtop"> <?php echo count($failed_arr);?> </h3>
              	<div class="meter red">
                  <span style="width:72%" class="fail_count"></span>
             	</div>
        	</div>
    	</div>

      

        <div class="col-md-2 col-xs-6 border-right yellow-bdr">
        <div class="prgs-outer">
        <p class="font-medium no-mbot">
        In Progress </p>
      <h3 class="bold no-mtop"><?php echo count($start_due_arr);?> </h3>
      		<div class="meter yellow">
                  <span style="width:32%" class="start_count"></span>
            </div>

     </div>
    </div>

 <div class="col-md-2 col-xs-6 border-right black-bdr">
        <div class="prgs-outer">
         <p class="font-medium no-mbot">
        Not Started</p>
      <h3 class="bold no-mtop"> <?php echo count($nostarttask);?></h3>
		<div class="meter black">
                  <span style="width:12%"  class="nostart_count"></span>
             	</div>
     </div>
    </div>
    
 
        <div class="col-md-2 col-xs-6 border-right green-bdr">
        <div class="prgs-outer">
         <p class="font-medium no-mbot">
        Completed</p>
      <h3 class="bold no-mtop"> <?php echo count($completetask);?></h3>
		<div class="meter green">
                  <span style="width:12%" class="complete_count"></span>
             	</div>
     </div>
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
                   <th>No.</th>
                  <th>Task</th>
                  <!-- <th>Status</th> -->
                  <th>Start Date</th>
                  <th>Due Date</th>
                  <th>Assigned To</th>
                  <!-- <th>Client</th> -->
                  <!-- <th class="alignCenter">Action</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($task as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-date="{{$product->start_date}}" data-from ="task" <?php if($product->status=="Pending"){ ?> style="background-color:#ccc" <?php } ?>>
                       
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><a class="popup" attr-id="{{$product->id}}" ><?php echo $product->name ?></a></td>
                     

                        <td><?php echo $product->start_date ?></td>
                        <td><?php echo $product->due_date ?></td>

                          <td><?php
                          
                          $staff_all=explode(',',$product->assigns);
                          foreach($staff_all as $val_staff)
                            {
                              if($val_staff>0){
                              $staff = App\Staff::find($val_staff);
                            echo $staff->name.',';
                          }
                            }
                          
                          ?>
                        </td>

                          <!-- <td>
                          <select style="width:150px;" name="priority" id="priority" class="form-control"  onchange="change_priority(this.value,{{$product->id}})">
                        <option value="">Client</option>
                          @foreach ($user as $userlist)
                          <?php
                       /*   $sel = ($userlist->id == $product->user_id) ? 'selected': '';
                          echo '<option value="'.$userlist->id.'" '.$sel.'>'.$userlist->business_name.'</option>';
                         */ ?>
         
                           @endforeach
                           
                        </select>
                          </td> -->
                        
                      
                     
                      

                        <!-- <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.task.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                        </td> -->
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($task) > 0) { ?>
              <div class="deleteAll">
                 <!-- <a class="btn btn-danger btn-xs" onClick="deleteAll('task');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a> -->
              </div>
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
<div id="myModal" class="modal fade inprogress-popup" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="taskname"></h4>
      </div>
      <div class="modal-body">
   <div class="row">
      <div class="col-md-8 task-single-col-left" >
                 <div class="clearfix"></div>
              
     
         <h4 class="th font-medium mbot15 pull-left">Description</h4>
       
                  <div class="clearfix"></div>
         <div class="tc-content"><div id="task_view_description"></div></div>         <div class="clearfix"></div>
       
        
        
         <div class="clearfix"></div>
       
             
            <h4 class="mbot20 font-medium">Comments</h4>
      	<div class="row pgrs-outer">
         <div class="col-md-8 tasks-comments inline-block full-width simple-editor">
  
  <form name="contactformedit" id="contactformedit" method="post" action"" />
            <input type="hidden" name="task_id" id="task_id">   

            <div><label class="form-check-label">Email 
            <input type="checkbox" name="email_status" id="email_status" class="form-check-input" value="Y">    </label> </div>
            <div><label class="form-check-label">Call 
            <input type="checkbox" name="call_status" id="call_status" class="form-check-input">    </label> </div>
            <div><label class="form-check-label">Visit 
            <input type="checkbox" name="visit_status" id="visit_status" class="form-check-input">    </label> </div>
             <a id="contact_link" href="">Add contact</a>     
            <select name="contact_id" id="contact_id" class="form-control">
            <option value="">Select Contact</option>

            </select>
            <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>

            <br>

            <textarea name="comment" placeholder="Add Comment" id="task_comment" rows="3" class="form-control ays-ignore"></textarea>
            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
                  
            <input type="file" id="image_name" name="image_name" accept=".jpg,.jpeg,.png"/>
            <span class="error_message" id="image_name_message" style="display: none">Field is required</span>

            <button type="button" class="btn btn-info mtop10 pull-left" id="addTaskCommentBtn" autocomplete="off" data-loading-text="Please wait..." >
            Add Comment            </button>

               <div class="load-sec" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                     <div class="clearfix"></div>

          
            </form>
              

            <div class="res_ajax containerscroll">

        

            

            </div>

              <!-- <div class="display_add_data"></div>
            <div class="expence_results"></div>
            <div class="section_work_expence" style="display:none"> </div> -->
          

          
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
                <h4 class="task-info-heading font-normal font-medium-xs">
          
            Project Status         </h4>
            <br>
            <div class="projectpending">        
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="projectstatus" name="projectstatus">
              <label class="form-check-label" for="projectstatus">Task Completed</label>
              <span class="error_message" id="projectstatus_message" style="display: none">Field is required</span>
            </div>
            <br>
            <input type="hidden" id="count_msg" name="count_msg">
            
            <span class="error_message" id="message_error_countzero" style="display: none">Can't find any task message</span>
            <span class="error_message" id="message_error" style="display: none">Some task approval pending</span>
            <button type="button" class="btn btn-info mtop10 pull-left" id="task_change_completed"  >Submit </button>
            </div> 
            <div class="projectcompleted" style="display:none"> 
            Task Completed    
            </div> 

                         </div>
         
      </div>

</div>
  </div>





      </div>
      
    </div>

  </div>
</div>


<div class="modal fade" id="replay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reply message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="replay_comment" name="replay_comment"></textarea>
          </div>

           <div class="form-group status-sec" style="display:none;">
              <label >Status</label>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay" id="status_replay1" value="Y" >
                Approved
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay" id="status_replay1" value="R" checked>
                Reject
              </label>
            </div>

          </div>


          <input type="hidden" name="task_comment_id" id="task_comment_id"> 
          <input type="hidden" name="parent_id" id="parent_id"> 
          <input type="hidden" name="staff_id" id="staff_id" value="<?php echo session('STAFF_ID');?>"> 
          <input type="hidden" name="follower" id="follower" value=""> 
          
      

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="add_replay_comment()">Update Task</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="replay_modal_expence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" >Approval</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="replay_comment_expence" name="replay_comment_expence"></textarea>
          </div>

           <div class="form-group ">
              <label >Status</label>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay_expence" id="status_replay1_expence" value="Y" >
                Approved
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay_expence" id="status_replay1_expence" value="Reject" checked>
                Reject
              </label>
            </div>

          </div>


          <input type="hidden" name="task_id_expence" id="task_id_expence"> 
          <input type="hidden" name="start_date_expence" id="start_date_expence"> 
         
          
      

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="add_replay_comment_expence()">Save</button>
      </div>
    </div>
  </div>
</div>




@endsection

@section('scripts')


<script type="text/javascript">


var today="<?php echo count($currenttask);?>";
$(".today_count").css({"width": today});

var fail="<?php echo count($failed_arr);?>";
$(".fail_count").css({"width": fail});

var start="<?php echo count($start_due_arr)-200;?>";
$(".start_count").css({"width": start});

var nostart="<?php echo count($nostarttask);?>";
$(".nostart_count").css({"width": nostart});

var complete="<?php echo count($completetask);?>";
$(".complete_count").css({"width": complete});

function replay_expance(task_id,start_date)
{
$("#replay_modal_expence").modal("show");
$("#task_id_expence").val(task_id);
$("#start_date_expence").val(start_date);
}

function add_replay_comment_expence()
{
  var task_id=$("#task_id_expence").val();
var start_date=$("#start_date_expence").val();
var replay_comment=$("#replay_comment_expence").val();
var status = $("input[name='status_replay_expence']:checked").val();
var url = APP_URL+'/staff/add_task_replay_comment_expence';

    $.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        task_id:task_id,start_date:start_date,replay_comment:replay_comment,status:status
      },
      success: function (data)
      {  
        $("#replay_modal").modal("hide");
        //viewall_comments()
        window.location.href=APP_URL+'/staff/verify';
      }
    });


}
function add_replay_comment()
{
  var task_id=$("#task_id").val();
  var task_comment_id=$("#task_comment_id").val();
  
  var replay_comment=$("#replay_comment").val();
  var follower=$("#follower").val();
  var staff_id=$("#staff_id").val();
  if(follower==staff_id){
    var status = $("input[name='status_replay']:checked").val();
  }else{
    var status = "N";
  }

  
  
  
  var parent_id=$("#parent_id").val();
  var url = APP_URL+'/staff/add_task_replay_comment';

    $.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        parent_id:parent_id,task_id:task_id,task_comment_id:task_comment_id,replay_comment:replay_comment,status:status
      },
      success: function (data)
      {  
        $("#replay_modal").modal("hide");
        viewall_comments()
        window.location.href=APP_URL+'/staff/verify';
      }
    });

}
function replay_task_comment(id,replay_id,follower_id){
  
$("#replay_modal").modal("show");
$("#task_comment_id").val(id);
$("#parent_id").val(replay_id);
var staff_id=$("#staff_id").val();
//alert(staff_id+'=='+follower_id)
$("#follower").val(follower_id);
if(staff_id==follower_id){
  $(".status-sec").show();
}
else{
  $(".status-sec").hide();
}


}

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
    var staff_id=$("#staff_id").val();
    
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
                  var res = data.split("*");
                  var proObj = JSON.parse(res[0]);
                  var proObj_replay = JSON.parse(res[1]);
                  var proObj_task = JSON.parse(res[2]);
                  var follower_id=proObj_task[0]["followers"];
                  var proj_status=proObj_task[0]["status"];

                  if(proj_status=="Pending")
                  {
                    $(".projectpending").show();
                    $(".projectcompleted").hide();
                  }
                  if(proj_status=="Complete")
                  {
                    $(".projectpending").hide();
                    $(".projectcompleted").show();
                  }
                  
                  $("#count_msg").val(proObj.length);
                  

                  htmls=' ';
                  var j=1;
                  var rplays_but = [];
                    for (var i = 0; i < proObj.length; i++) {
                  
              var imgs="{{asset('storage/app/public/comment/')}}/"+proObj[i]["image_name"];
                     htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">'+
                        '<p>Contact Person: '+proObj[i]["contact_name"]+'</p>'+
                        '<p>'+proObj[i]["comment"]+'</p>'+
                        '<p>'+proObj[i]["created_at"]+'</p>';
                        if(proObj[i]["email"]=="Y")
                        {
                          htmls +='<p>Email: Yes</p>';
                        }
                        if(proObj[i]["call_status"]=="Y")
                        {
                          htmls +='<p>Call: Yes</p>';
                        }
                        if(proObj[i]["visit"]=="Y")
                        {
                          htmls +='<p>Visit: Yes</p>';
                        }
                      if(proObj[i]["status"]=="Y"){
                        htmls +='<p style="color:green">Approved</p>';
                      }
                      else if(proObj[i]["status"]=="N"){
                        htmls +='<p style="color:red">Pending</p>';
                      }
                      else if(proObj[i]["status"]=="R"){
                        htmls +='<p style="color:red">Reject</p>';
                      }
                        

                        if(proObj[i]["image_name"]!='')
                        {
                          htmls +='<a href="'+imgs+'" download="'+imgs+'"><img src="'+imgs+'" width="50px" height="50px;"/>Download</a><br>';
                        }
                        htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';

                       /* htmls += '<a class="btn btn-danger btn-xs" onClick="delete_task_comment('+proObj[i]["id"]+','+i+');" id="btn_deleteAll" >'+
                        '<span class="glyphicon glyphicon-trash"></span></a>';*/

                      //  if(proObj[i]["added_by"]=='admin' && proObj[i]["status"]=="N")
                      if( proObj[i]["status"]=="N" && staff_id!=proObj[i]["added_by_id"])
                        {
                          htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                        }

                      htmls += '</div>';
                     
                      for (var j = 0; j < proObj_replay.length; j++) {
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          
                          if(staff_id==proObj_replay[j]["added_by_id"])
                          {var p=1;
                          htmls += '<div class="reply-comment staff" >'+
                           ''+proObj_replay[j]["comment"]+''+
                            '<p>'+proObj_replay[j]["created_at"]+'<br/>'+
                             '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>'+
                            '</p>'+
                          '</div>';
                          }else{var p=2;
                            htmls += '<div class="reply-comment admin"  >'+
                           ''+proObj_replay[j]["comment"]+''+
                            '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                            htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            if(proObj[i]["status"]=="N" && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by_id"]){
                              htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                            }
                            
                              htmls +=   '</p>'+
                          '</div>';
                          rplays_but.push(j);
                          }


                        }

                      }
                      

                        htmls +='</div>';
                     


                      j++;
                    }
                    $(".res_ajax").html(htmls);

                    for(k=0;k<rplays_but.length;k++)
                    {
                      var rowno=rplays_but[k];
                      $("#replay_but_"+rowno).hide();
                    }
                    var lastEl = rplays_but[rplays_but.length-1];
               
                    // if(p==1){
                    //   $("#replay_but_"+lastEl).show();
                    // }
                    $("#replay_but_"+lastEl).show();

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

        
        jQuery("#task_change_completed").click(function() {
          if($('input[name="projectstatus"]').is(':checked'))
            {
             // var projectstatus="Y";
              $("#projectstatus_message").hide();
              
              var url = APP_URL+'/staff/change_task_status_total_task';
              var status='Complete';
              var task_id=$("#task_id").val();
              var count_msg=$("#count_msg").val();
              if(count_msg>0){
                $("#message_error_countzero").hide();
              $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data:{
                      status: status,id:task_id
                    },
                    success: function (data)
                    {    
                      if(data==0)
                      {
                        $("#message_error").hide();
                        location.reload();
                      }
                      else{
                        $("#message_error").show();
                      }
                      //alert(data);
                      
                        
                    
                    }
                  });
              }
              else{
                $("#message_error_countzero").show();
              }
              
          

            }
            else{
             // var projectstatus="N";
              $("#projectstatus_message").show();
            }


        });


        jQuery("#addTaskCommentBtn").click(function() {
          
        var comment=$("#task_comment").val();
        var contact_id=$("#contact_id").val();
       if($('input[name="email_status"]').is(':checked'))
            {
              var email_status="Y";
            }
            else{
              var email_status="N";
            }
            if($('input[name="call_status"]').is(':checked'))
            {
              var call_status="Y";
            }
            else{
              var call_status="N";
            }
            if($('input[name="visit_status"]').is(':checked'))
            {
              var visit_status="Y";
            }
            else{
              var visit_status="N";
            }
      
          if(comment=='')
          {
            
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
            formData.append('contact_id',contact_id);  
              formData.append('email_status',email_status);  
              formData.append('call_status',call_status);  
              formData.append('visit_status',visit_status);  
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
          else{
            if(comment==""){
              $("#task_comment_message").show();
             }
             if(contact_id==""){
              $("#contact_id_message").show();
             }
          }
        });
       // jQuery(".popup").click(function() {
          $(document).on('click',"#cmsTable tr",function(){

          var id=$(this).attr("data-id");
          var data_date=$(this).attr("data-date");
          $("#message_error_countzero").hide();
          $("#message_error").hide();
          
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

                var res = data.split("*");
              var proObj = JSON.parse(res[0]);
              var contact_list = JSON.parse(res[1]);
              var travel = JSON.parse(res[2]);
              var expence = JSON.parse(res[3]);
              var day_expence = JSON.parse(res[4]);
              htmls=' ';
              var j=1;
              
                  
                  $("#taskname").html(proObj[0]);
                  if(proObj[1]!=null){
                      $("#task_view_description").html(proObj[1]);
                    }
                    else{
                      $("#task_view_description").html("No description added");
                    }
                  $("#created_at").html(proObj[2]);
                  $("#start_date").html(proObj[3]);
                  $("#due_date").html(proObj[4]);
                  $("#priority_dis").html(proObj[5]);
                  $("#staff_dis").html(proObj[6]);
                  $("#follower_dis").html(proObj[7]);
                  
                  if(proObj[8]>0)
                    {
                      $("#contact_link"). attr("href", APP_URL+'/staff/customer/'+proObj[8]);
                    }
                    else{
                      $("#contact_link"). attr("href", APP_URL+'/staff/customer/');
                    }

                     var contact_option='';
                    contact_option +='<option value="">Select Contact</option>';
                    for (var j = 0; j < contact_list.length; j++) {
                       contact_option +='<option value="'+contact_list[j]["id"]+'">'+contact_list[j]["name"] +'</option>';

                    }
                  
                 $("#contact_id").html(contact_option);
                 $(".display_add_data").html('');
                   $(".expence_results").html('');
                   $(".section_work_expence").html('');
                 var flag_status='';
                
                 if(travel.length>0)
                  {
                    var htmls='';
                    htmls +='<h3>Travel Details</h3>';
                    htmls +='<table class="table">';
                    htmls +=' <tr>'+
                            '<th>Travel Type</th>'+
                            '<th>Work Date</th>'+
                            '<th>Start Reading</th>'+
                            '<th>End Reading</th>'+
                            '<th>Start  Amount</th>'+
                            '<th>End  Amount</th>'+
                            '<th>Start Time</th>'+
                            '<th>End Time</th>'+
                            '<th>Task</th>'+
                        
                            '</tr>  <tbody id="travel_data">';
                            for (var i = 0; i < travel.length; i++) {

                            if(travel[i]["travel_type"]=="Bike")
                              {
                                
                                
                                var date1 = Date.parse(travel[i]["start_date"]);
                                var date2 = Date.parse("2022-06-01");
                                if (date1 < date2) {
                                  var bike_rate=2.5;
                                }
                                else{
                                  var bike_rate=3;
                                }

                                var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                                var tot_price=total_meter*bike_rate;
                              }
                              if(travel[i]["travel_type"]=="Car")
                              {
                                var car_rate=5;
                                var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                                var tot_price=total_meter*5;  
                              }
                                    
                              htmls +=' <tr>'+
                            '<td data-th="Travel Type">'+travel[i]["travel_type"]+'</td>'+
                            '<td data-th="Work Date">'+travel[i]["start_date"]+'</td>'+
                            '<td data-th="Start Reading">'+travel[i]["start_meter_reading"]+'</td>'+
                            '<td data-th="End Reading">'+travel[i]["end_meter_reading"]+'</td>';
                            if(travel[i]["travel_type"]=="Car"  ||  travel[i]["travel_type"]=="Bike")
                              {
                                htmls +='<td data-th="Start  Amount">'+tot_price+'</td>';
                              }
                              else{
                                htmls +='<td data-th="Start  Amount">'+travel[i]["travel_start_amount"]+'</td>';
                              }
                     htmls += '<td data-th="End  Amount">'+travel[i]["travel_end_amount"]+'</td>'+
                            '<td data-th="Start Time">'+travel[i]["start_time_travel"]+'</td>'+
                            '<td data-th="End Time">'+travel[i]["end_time_travel"]+'</td>'+
                            '<td data-th="Task">'+travel[i]["task_name"]+'</td>'+
                            
                            '</tr>';
                            }
                            htmls +='</tbody></table>';
                  }
                  $(".display_add_data").append(htmls);
                  if(expence.length>0)
                  {
                    var html_expence='';
                    html_expence +='<h3>Expence Details</h3>';
                    html_expence +='<table class="table">';
                    html_expence +=' <tr>'+
                            '<th>Other Expence</th>'+
                            '<th>Work Date</th>'+
                            '<th>Amount</th>'+
                            '<th>Description</th>'+
                            '<th>Task</th>'+
                            '<th>Status</th>'+
                            '</tr>  <tbody id="expence_data">';
                    
                    for (var i = 0; i < expence.length; i++) {
                      html_expence +=' <tr>'+
                            '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
                            '<td data-th="Amount">'+expence[i]["start_date"]+'</td>'+
                            '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
                            '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
                            '<td data-th="Task">'+expence[i]["task_name"]+'</td><td data-th="Status">';
                            if(expence[i]["status"]=="Reject")
                            {
                               html_expence +='<span style="color:red;">Reject</span>';
                            // var app_html=' <a id="replay_but_expence" class="btn btn-success btn-xs" onClick="replay_expance('+id+',\'' + data_date + '\');"  >Approval</a>';
                            }
                            if(expence[i]["status"]=="Y")
                            {
                               html_expence +='<span style="color:green;">Approved</span>';
                            }
                            if(expence[i]["status"]=="N")
                            {
                               html_expence +=' <a id="replay_but_expence" class="btn btn-success btn-xs" onClick="replay_expance('+expence[i]["id"]+',\'' + data_date + '\');"  >Approval</a>';
                            }
                            
                            html_expence +='</td></tr>';
                    }
                    html_expence +='</tbody></table>';

                  }
                  $(".expence_results").append(html_expence);


                 /*  if(travel.length>0 || expence.length>0)
                  {
                    if(day_expence.length>0)
                    {
                      if(day_expence[0]["status"]=="Reject")
                      {
                        var app_html='<span style="color:red;">Reject</span>';
                      // var app_html=' <a id="replay_but_expence" class="btn btn-success btn-xs" onClick="replay_expance('+id+',\'' + data_date + '\');"  >Approval</a>';
                      }
                      if(day_expence[0]["status"]=="Y")
                      {
                        var app_html='<span style="color:green;">Approved</span>';
                      }
                      if(day_expence[0]["status"]=="N")
                      {
                        var app_html=' <a id="replay_but_expence" class="btn btn-success btn-xs" onClick="replay_expance('+id+',\'' + data_date + '\');"  >Approval</a>';
                      }
                     
                    }
                  
                    
                    $(".section_work_expence").append(app_html);
                    $(".section_work_expence").show();
                  }*/

               


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
         
         // location.reload();
       
      }
    });

}


 
</script>


@endsection
