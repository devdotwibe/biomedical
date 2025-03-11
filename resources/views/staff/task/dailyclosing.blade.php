
@extends('staff/layouts.app')

@section('title', 'Manage Daily Closing')

@section('content')

<section class="content-header">
      <h1>
        Manage Daily Closing
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Daily Closing</li>
      </ol>

    </section>


    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">



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
                
                  <th>Task Date</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($task as $product)
                    <?php 
                    $staff_id = session('STAFF_ID');
                    $nu_hostpital=DB::select("select * from dailyclosing as daily inner join task_comment tcomment on daily.task_id=tcomment.task_id where (daily.start_date='".$product->start_date."' AND daily.assigns='".$staff_id."') AND (tcomment.call_status='Y' OR tcomment.visit='Y')  group by daily.user_id");
   
                

                 $arr_contact=array();
                 $arrcon=array();
                 $task_fairs=array();
                 $task_replays=DB::select("select * from  dailyclosing_details where `start_date`='".$product->start_date."' AND staff_id='".$staff_id."'");
                 if(count($task_replays)>0)
                 {
                    
                   $task_contact=DB::select("select * from dailyclosing as daily inner join task_comment tcomment on daily.task_id=tcomment.task_id where daily.start_date='".$product->start_date."'  AND daily.assigns='".$staff_id."'");
                   if(count($task_contact)>0)
                   {$i=0;
                     foreach($task_contact as $val_counts){
                         $staff_names=explode(",",$val_counts->contact_id);
                         //$arr_contact[]=$staff_names;
                         foreach($staff_names as $val_staff) {
                             $arr_contact[$i]=$val_staff;
                             $i++;
                         }
                     
                     }
                    
             
                   }

                   $k=0;
                 foreach(array_unique($arr_contact) as $arrconval) {
                     
                             
                     $arrcon[]=$arrconval;
             
                     //$arrcon[]=$arrconval;
                     $k++;
                 }

                 $task_fairs=DB::select("select * from  dailyclosing_expence  where `dailyclosing_details_id`='".$task_replays[0]->id."' ");
                 $approved_work=$task_replays[0]->approved_work;
                 } 
                 else{
                  $approved_work="Pending";
                 }


               


                 if(count($task_fairs)>0)
                 {
                    if($task_replays[0]->approved_fair=="Reject")
                    {
                      $fair_status="Reject";
                    }
                    else{
                      if(count($task_replays)>0)
                  {$fair_status="Approved";
                  }
                  else{
                    $fair_status="Pending";
                  }
                    }
                 }
                 else{
                  if(count($task_replays)>0)
                  {$fair_status="Approved";
                  }
                  else{
                    $fair_status="Pending";
                  }
                 }

                 if($fair_status=="Reject" || $approved_work=="Reject")
                 {
                   $status_poup="Reject";
                 }
                 else if($fair_status=="Pending" || $approved_work=="Pending"){
                  $status_poup="Pending";
                 }
                 else if($fair_status=="Approved" && $approved_work=="Pending"){
                  $status_poup="Approved";
                 }
                 else if($fair_status=="Pending" && $approved_work=="Approved"){
                  $status_poup="Approved";
                 }    

                    ?>
<tr <?php if( $approved_work=="Reject"){ ?> attr-status="Reject" style="background-color:#ccc;" <?php } else if( $approved_work=="Pending"){ ?> attr-status="Pending" <?php } else if( $approved_work=="Approved"){ ?> attr-status="Approved" <?php }?> id="tr_{{$product->id}}" data-id="{{$product->id}}" attr-id="{{$product->start_date}}"   data-from ="task">
                       
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                      
                        <td><a class="popup" attr-id="{{$product->start_date}}"><?php echo $product->start_date ?></a></td>
                        <td>
                           <?php 
                           if($fair_status=="Reject"  || $approved_work=="Reject")
                           {
                             echo "Reject";
                           }
                           else if($fair_status=="Pending"  || $approved_work=="Pending")                           {
                            echo "Pending";
                           }
                           else if($fair_status=="Approved"  )
                           {
                            echo "Approved";
                           }
                            //echo '--'.$fair_status.'<br>'.$product->approved_work.'<br>'.$hos_status;
                           ?>
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


<div class="modal fade" id="replay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Daily Closing</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="res_ajax"></div>

        <div class="display_add_data"></div>
            <div class="expence_results"></div>
      <form name="dailyclosing" method="post" id="dailyclosing" enctype="multipart/form-data"  action="{{route('staff.dailyclosing_details.store')}}">
      @csrf
      <div class="expform">
        <div class="col-md-12 ">
        <div class="form-group col-md-6">
            <label for="message-text" class="col-form-label">Expense :</label>
            <select class="form-control" id="expence_type" name="expence_type[]">
            <option value="">Select Expense </option>
            <option value="Bus">Bus</option>
            <option value="Train">Train</option>
            <option value="Car">Car</option>
            <option value="Bike">Bike</option>
            <option value="Print Out">Print Out</option>
            <option value="Purchase">Purchase</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="message-text" class="col-form-label">Fare</label>
            <input type="text" class="form-control" id="fair" name="fair[]">
             </div>
             <div class="res_add"></div>

             <div class="form-group">
            <button type="button" id="add_fair" name="add_fair"  ><span  onclick="add_fair()" class="glyphicon glyphicon-plus"></span> </button>
             </div>

      </div>          
        <input type="hidden" name="row_no" id="row_no" value="0">
          <div class="form-group col-md-12">
            <label for="message-text" class="col-form-label">Message</label>
            <textarea class="form-control" id="replay_comment" name="replay_comment"></textarea>
            <span class="error_message" id="replay_comment_message" style="display: none">Field is required</span>
          </div>
      

          <div class="form-group col-md-12">
            <label for="message-text" class="col-form-label">Attendance</label>
            <select class="form-control" id="staff_leave" name="staff_leave">
            <option value="">Select Attendance</option>
            <option value="Full Day">Full Day</option>
            <option value="Half Day">Half Day</option>
            <option value="Leave">Leave</option>
            
            </select>
            <span class="error_message" id="staff_leave_message" style="display: none">Field is required</span>
          </div>
          <div class="form-group col-md-12">
              <input type="file" id="image_name" name="image_name" accept=".jpg,.jpeg,.png"/>
            <span class="error_message" id="image_name_message" style="display: none">Field is required</span>
            </div>
          <input type="hidden" name="task_comment_id" id="task_comment_id"> 
          <input type="hidden" name="parent_id" id="parent_id"> 
          <input type="hidden" name="start_date" id="start_date"> 
          <input type="hidden" name="msg_count" id="msg_count"> 

          <input type="hidden" name="aprroval_count" id="aprroval_count" value="0"> 

           <div class="form-check">
              <input type="checkbox" class="form-check-input" id="leavefor_check" name="leavefor_check">
              <label class="form-check-label" for="leavefor_check">Submit only for leave</label>
            </div>
      
      </div>

      


      <div class="expenceshow"></div>

      <div class="approvalshow"></div>

      <div class="modal-footer">
      
      <span class="error_message" id="mesag_countzero" style="display: none">Can't find any task message

</span>
<span class="error_message" id="message_error" style="display: none">Some task approval pending</span>
     <div class="expform">   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="add_replay_btn"  onclick="add_replay_comment()">Save</button>
        </div>   </div>
      </div>
      </form>

  
    </div>
  </div>
</div>




@endsection

@section('scripts')
<script type="text/javascript">

function add_fair()
{
  if($('input[name="leavefor_check"]').is(':checked'))
            {
//alert()
            }
            else{
              var row_no=$("#row_no").val();
  var row_val=parseInt(row_no)+1;
  $("#row_no").val(row_val);
var htmldata='<div class="row'+row_val+'"> <div class="form-group col-md-6">'+
            '<label for="message-text" class="col-form-label">Expense :</label>'+
            '<select class="form-control"  name="expence_type[]">'+
            '<option value="">Select Expense </option>'+
            '<option value="Bus">Bus</option>'+
            '<option value="Train">Train</option>'+
            '<option value="Car">Car</option>'+
            '<option value="Bike">Bike</option>'+
            '<option value="Print Out">Print Out</option>'+
            '<option value="Purchase">Purchase</option>'+
            '</select>'+
          '</div>'+
          '<div class="form-group col-md-6">'+
            '<label for="message-text" class="col-form-label">Fare</label>'+
            '<input type="text" class="form-control"  name="fair[]">'+
            '<a onclick="delete_item('+row_val+')">Delete</a>'
             '</div></div>';
             $(".res_add").append(htmldata);
            }
  
}
function delete_item(row_no)
{
  $(".row"+row_no).remove();
  
}
function add_replay_comment()
{
  
  var replay_comment=$("#replay_comment").val();
  var start_date=$("#start_date").val();
  var staff_leave=$("#staff_leave").val();

  if($('input[name="leavefor_check"]').is(':checked'))
            {
            
              
              var leavefor_check="Y";
              if(staff_leave!='')
              {
                $("#staff_leave_message").hide();
              }
              else{
                $("#staff_leave_message").show();
              }

       
              if( staff_leave!=''){
                $("#dailyclosing").submit();

              }
              
            }
            else{
              var leavefor_check="N";

              
  var msg_count=$("#msg_count").val();
  var aprroval_count=$("#aprroval_count").val();
  
  if(staff_leave!=''){
    if(msg_count>0)
    {

      if(aprroval_count==0)
    {
      $("#message_error").hide();
      $("#dailyclosing").submit();
    
    }
    else{
      
      $("#message_error").show();
    }
    
     
      $("#mesag_countzero").hide();
    }
    else{
      $("#mesag_countzero").show();
    }
    

  }
  else{
    if(staff_leave!='')
    {
      $("#staff_leave_message").hide();
    }
    else{
      $("#staff_leave_message").show();
    }


  }


            }

  

    


}


  
 

 




    jQuery(document).ready(function() {

      $('#leavefor_check').change(function () {
        if($('input[name="leavefor_check"]').is(':checked'))
            {
              $("#expence_type").val('');
              $("#fair").val('');
              $(".res_add").html('');
              
              $("#expence_type").prop('disabled', true);
              $("#fair").prop('disabled', true);
              $("#add_fair").prop('disabled', true);
              $("#image_name").prop('disabled', true);
              
              
            }
            else{
              $("#expence_type").prop('disabled', false);
              $("#fair").prop('disabled', false);
              $("#add_fair").prop('disabled', false);
              $("#image_name").prop('disabled', false);
            }

 });
  


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

        
         // jQuery(".popup").click(function() {
          $(document).on('click',"#cmsTable tr",function(){
  
//var id=$(this).attr("data-id");
  
           var id=$(this).attr("attr-id");
           var attr_status=$(this).attr("attr-status");
           
            var url = APP_URL+'/staff/view_task_comment_dailytask';
            
            $("#start_date").val(id);
             $("#replay_modal").modal("show");
             
             $(".error_message").hide();
              $(".approvalshow").html('');
             $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  start_date:id
                },
                success: function (data)
                {    
                  var res = data.split("*");
                  var proObj = JSON.parse(res[0]);
                  var proObj_replay = JSON.parse(res[1]);
                  console.log('replay'+proObj_replay)
                 console.log('len'+proObj_replay.length)
                  var expence = JSON.parse(res[3]);
                  var expence_type = JSON.parse(res[4]);
                  var approve_comment = JSON.parse(res[5]);
                  var travel = JSON.parse(res[6]);
              var expence_work = JSON.parse(res[7]);
                 // console.log(approve_comment);
                  htmls=' ';
                  var j=1;
                  var rplays_but = [];
                  var roll_count=1;
                 /* if(expence.length>0)
                  {
                    $(".expform").hide();
                  }
                  else{
                    $(".expform").show();
                  }*/
                 
                  //console.log(proObj.length+'---')
                
                    $("#msg_count").val(proObj.length);
                    var alltask_name = [];
                    var aprroval_count=0;
                    for (var i = 0; i < proObj.length; i++) {
                      //console.log(proObj[i]);
                      if(jQuery.inArray(proObj[i]["task_name"], alltask_name) == -1)
                      {
                        htmls +='<h3>'+proObj[i]["task_name"]+'</h3>';
                      }

                      if(proObj[i]["status"]=="N")
                      {
                        aprroval_count++
                      }
                     
                      
                      alltask_name.push(proObj[i]["task_name"]);
                      var imgs="{{asset('storage/app/public/comment/')}}/"+proObj[i]["image_name"];
                      htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">';
                        if(proObj[i]["comment"]!=null)
                            {
                        htmls += '<p>'+proObj[i]["comment"]+'</p>';
                            }
                        htmls +=  '<p>'+proObj[i]["created_at"]+'</p>';
                        htmls +=  '<p>Added By: <b>'+proObj[i]["added_by_name"]+'</b></p>';
                       
                      if(proObj[i]["status"]=="Y"){
                     htmls +='<p style="color:green">Approved</p>';
                      }
                      else{
                        htmls +='<p style="color:red">Pending</p>';
                      }
                        
                      for (var j = 0; j < proObj_replay.length; j++) {
                       
                        //console.log(proObj[i]["id"]==proObj_replay[j+i]["task_comment_id"]);
                      
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          if(proObj_replay[j]["added_by"]=="staff")
                          {
                          htmls += '<div class="reply-comment staff" >';
                            if(proObj_replay[j]["comment"]!=null)
                            {
                              htmls += ''+proObj_replay[j]["comment"]+'';
                            }
                          

                          htmls +=   '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                            htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by_name"]+'</b></p>';
                            htmls +='</p>'+
                          '</div>';
                           
                           rplays_but.push(j);
                          }else{
                            htmls += '<div class="reply-comment admin"  >';
                           
                            if(proObj_replay[j]["comment"]!=null)
                            {
                              htmls += ''+proObj_replay[j]["comment"]+'';
                            }

                             htmls +='<p>'+proObj_replay[j]["created_at"]+'<br/>'+
                            '<p>Added By: <b>'+proObj_replay[j]["added_by_name"]+'</b></p>'+
                           '</p>'+
                          '</div>';
                          }


                        }

                      }
                      
                      htmls += '</div>';
                        htmls +='</div>';
                     

                      roll_count++;
                      j++;
                    }
                    
                    $("#aprroval_count").val(aprroval_count);
                    htmls_expence="";
                      for (var k = 0; k < expence.length; k++) {
                        var imgs="{{asset('storage/app/public/comment/')}}/"+expence[k]["image_name"];

                        htmls_expence += '<div class="expence"  >';
                        if(expence[k]["message"]!=null)
                          {
                          htmls_expence +=  'Message:'+expence[k]["message"]+'';
                          }

                        if(expence[k]["replay_comment_expence"]!=null)
                          {

                           htmls_expence += '<br>Replay Message:'+expence[k]["replay_comment_expence"]+'';
                          }
                          
                          
                          if(expence[k]["staff_leave"]!=null)
                          {
                            htmls_expence += '<p>Attendance:'+expence[k]["staff_leave"]+'<br/>';
                          }
                           if(expence[k]["image_name"]!='')
                           {
                            htmls_expence +=   '<img src='+imgs+' width="100px" height="100px">';
                           }

                           if(expence[k]["approved_fair"]=="Reject")
                          {

                           htmls_expence += '<br><span style="color:red">Reject</span>';
                          }
                          
                           
                           htmls_expence += '</p>'+
                          '</div>';
                      }
                    
                      for (var p = 0; p < expence_type.length; p++) {
                        if(p==0)
                        {
                          if(expence_type[p]["expence_type"]!=null || expence_type[p]["fair"]!=null)
                          {
                            htmls_expence +='<h3>Expense </h3>';
                          }
                          
                        }

                          if(expence_type[p]["expence_type"]!=null || expence_type[p]["fair"]!=null)
                          {
                        htmls_expence += '<div class="expencetype"  >'+
                          
                            '<p>'+expence_type[p]["expence_type"]+' - '+expence_type[p]["fair"]+'<br/>'+
                          
                           '</p>'+
                          '</div>';
                          }

                      }

                  
                  $(".expenceshow").html(htmls_expence);

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
                   var htmls_approve='';   
                   if(approve_comment.length>0)
                   {
                    htmls_approve +='<h3>Approval Status</h3>';
                   }
                   console.log(approve_comment);
                     for (var y = 0; y < approve_comment.length; y++) {

                         htmls_approve += '<div class="comment_approval"  >'+
                          
                          '<p>'+approve_comment[y]["comment"]+'<br/>'+
                          approve_comment[y]["created_at"]+'<br/>'+
                         '</p>'+
                        '</div>';
                     }
                    // $(".approvalshow").show();
                    // $(".approvalshow").html(htmls_approve);
            //   alert(attr_status)
                    if(attr_status=="Reject" || attr_status=="Pending")
                  {
                    $(".expform").show();
                  }
                  else{
                    $(".expform").hide();
                  }


                      if(travel.length>0)
                  {
                    var htmls='';
                    htmls +='<h3>Travel Details</h3>';
                    htmls +='<table class="table">';
                    htmls +=' <tr>'+
                            '<th>Travel Type</th>'+
                            '<th>Start Reading</th>'+
                            '<th>End Reading</th>'+
                            '<th>Start  Amount</th>'+
                            '<th>End  Amount</th>'+
                            '<th>Start Time</th>'+
                            '<th>End Time</th>'+
                            '<th>Task</th>'+
                        
                            '</tr>  <tbody id="travel_data">';
                            for (var i = 0; i < travel.length; i++) {
                              htmls +=' <tr>'+
                            '<td>'+travel[i]["travel_type"]+'</td>'+
                            '<td>'+travel[i]["start_meter_reading"]+'</td>'+
                            '<td>'+travel[i]["end_meter_reading"]+'</td>'+
                            '<td>'+travel[i]["travel_start_amount"]+'</td>'+
                            '<td>'+travel[i]["travel_end_amount"]+'</td>'+
                            '<td>'+travel[i]["start_time_travel"]+'</td>'+
                            '<td>'+travel[i]["end_time_travel"]+'</td>'+
                            '<td>'+travel[i]["task_name"]+'</td>'+
                            
                            '</tr>';
                            }
                            htmls +='</tbody></table>';
                  }
                  $(".display_add_data").html(htmls);
                  if(expence_work.length>0)
                  {
                    var html_expence_work='';
                    html_expence_work +='<h3>Expence Details</h3>';
                    html_expence_work +='<table class="table">';
                    html_expence_work +=' <tr>'+
                            '<th>Other Expence</th>'+
                            '<th>Amount</th>'+
                            '<th>Description</th>'+
                            '<th>Task</th>'+
                            '</tr>  <tbody id="expence_data">';
                    
                  

                    for (var i = 0; i < expence_work.length; i++) {
                      html_expence_work +=' <tr>'+
                            '<td>'+expence_work[i]["travel_type"]+'</td>'+
                            '<td>'+expence_work[i]["travel_start_amount"]+'</td>'+
                            '<td>'+expence_work[i]["expence_desc"]+'</td>'+
                            '<td>'+expence_work[i]["task_name"]+'</td>'+
                            
                            '</tr>';
                    }
                    html_expence_work +='</tbody></table>';

                  }
                  $(".expence_results").html(html_expence_work);


                }
              });

              
           
           }); 
        
    });

</script>

@endsection
