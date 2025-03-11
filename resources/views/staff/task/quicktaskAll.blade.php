@extends('staff/layouts.app')



@section('title', 'Quick  Task')



@section('content')



<section class="content-header">

      <h1>

        Quick  Task

      </h1>

      <ol class="breadcrumb">

        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Quick Task</li>

      </ol>



    </section>








    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">



          <div class="box">



                <div class="row">



        <div class="col-lg-12 margin-tb">





            <div class="pull-left">



       


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

                  <th>Task Type</th>

               

                  <th>Start Date</th>

       

                  <th>Assigned To</th> 

                  <th>Source</th>
                  <th>Products</th>
                 
                  <?php 
                /*  if(session('STAFF_ID')==29 || session('STAFF_ID')==55)
                  {*/
                  ?>
                      <th>Verify</th>
                      <?php 
                   // }
                      ?>

              <th>Status</th>

                  <!-- <th class="alignCenter">Action</th> -->

                </tr>

                </thead>

                <tbody>

                    <?php $i = 1; ?>

                    @foreach ($task as $product)

                  

                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" attr-id="{{$product->id}}" data-from ="task">

                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">

                        </td> -->

                        <td>

                            <span class="slNo">{{$i}} </span>

                        </td>

                        <td>
                        <a href="{{ route('staff.task.edit',$product->id) }}">
                        <?php echo $product->name ?>
                        </a>
                        </td>

                    

                        <td><?php echo $product->start_date ?></td>

                     
                      
                          <td><?php

                          $staff_all=explode(',',$product->assigns);

                          foreach($staff_all as $val_staff)

                            {

                              $staff = App\Staff::find($val_staff);

                            echo $staff->name.',';

                            }

                          ?>

                        </td>
                        

                        <?php
                          /*
                        ?>

                         <td><?php

                            $staff_all=explode(',',$product->followers);

                            foreach($staff_all as $val_staff)

                              {

                                $staff = App\Staff::find($val_staff);

                              echo $staff->name.',';

                              }

                            ?>

                            </td>
                            <?php
                        */

                        
                        ?>

                            <td><?php
                              if($product->email=="Y"){
                                echo 'Email,';
                              }
                            
                            ?>
                            <?php
                              if($product->visit=="Y"){
                                echo 'Visit,';
                              }
                             
                            ?>
                            <?php
                              if($product->call_status=="Y"){
                                echo 'Call';
                              }
                            ?>
                            </td>

                              <?php 
                             /*  if(session('STAFF_ID')==29 || session('STAFF_ID')==55)
                               {*/
                              ?>

<td><?php

$product_all=explode(',',$product->product_id);
if(count($product_all)>0)
{
  foreach($product_all as $val_products)

  {

   // $prodt_det = App\Product::find($val_products);
    $val_products_det =DB::select(" SELECT name from products where id='".$val_products."'");
   
if(count($val_products_det)>0)
{
  echo $val_products_det[0]->name;
}
  //echo $val_products.',';

  }

}


?>

</td>
                          <td>
                          <?php 
                           $verify_task =DB::select(" SELECT *,task_comment.status status_task FROM task as task 
                           INNER JOIN task_comment as task_comment ON task.id=task_comment.task_id
                            where task.id='".$product->id."' AND task_comment.status='N' AND task.status!='Complete'
                             group by task.id  order by task.updated_at desc"); 
                             $folw_ids=explode(',',$product->followers);
                          if(count($verify_task)>0 && in_array(session('STAFF_ID'),$folw_ids ) )
                          {
                            ?>
                           
                          <a class="popup" attr-id="{{$product->id}}"  data-id="{{$product->id}}">Verify </a>
                          <?php
                          }
                          ?>
                          
                           </td>

                        

                           <?php
                          //}
                          ?>

                          <?php
                            $status_task =DB::select(" SELECT task_comment.status status_task FROM task_comment as task_comment 
                             where task_comment.task_id='".$product->id."' "); 
                         
                          if($status_task)
                          { 
                            if($status_task[0]->status_task=="N")
                            {
                              echo ' <td>Pending</td>';
                            }
                            else{
                              echo ' <td>Approved</td>';
                            }
                          }
                          else{
                            echo ' <td></td>';
                          }
                            
                          ?>
                         


                        <!-- <td class="alignCenter">

                            <a class="btn btn-primary btn-xs" href="{{ route('staff.task.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>

                            

                        </td> -->

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
              
     
   
       
                  <div class="clearfix"></div>
         <div class="tc-content"><div style="display:none;" id="task_view_description"></div></div>       
       
           
      	<div class="row pgrs-outer">
         <div class="col-md-8 tasks-comments inline-block full-width simple-editor">
  
  <form name="contactformedit" id="contactformedit" method="post" action"" />
            <input type="hidden" name="task_id" id="task_id">       

     
     <h4 class="mbot20 font-medium">Comments</h4>
             <?php
             /*  if(session('STAFF_ID')!=29 && session('STAFF_ID')!=55)
               {*/
  
             ?>
             <div class="commentdisplay">
            <textarea name="comment" placeholder="Add Comment" id="task_comment" rows="3" class="form-control ays-ignore"></textarea>
            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
                  
        
            <button type="button" class="btn btn-info mtop10 pull-left" id="addTaskCommentBtn" autocomplete="off" data-loading-text="Please wait..." >
            Add Comment            </button>
            </div>
            <?php
         //  }
           
             ?>


               <div class="load-sec" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                   

          
            </form>
              

            <div class="res_ajax containerscroll">

        

            

            </div>
           
            <div class="display_add_data"></div>
            <div class="expence_results"></div>
            <div class="section_work_expence" style="display:none">
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

<input type="hidden" name="follower" id="follower1" value=""> 

        



    







      </form>



    </div>



    <div class="modal-footer">



      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>



      <button type="button" class="btn btn-primary" onclick="add_replay_comment()">Update Task</button>



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
                  var folow_check=follower_id.split(',');
                  if(folow_check[0]==staff_id)
                  {
                    $(".commentdisplay").hide();
                  }
                  if(folow_check[1]>0)
                  {
                   if(folow_check[1]==staff_id)
                    {
                      $(".commentdisplay").hide();
                    }
                    
                  }
                  htmls=' ';
                  var j=1;
                  var rplays_but = [];
                    for (var i = 0; i < proObj.length; i++) {
                  var imgs="{{asset('storage/app/public/comment/')}}/"+proObj[i]["image_name"];
                     htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">';
                         if(proObj[i]["contact_name"]!=null)
                        {
                          htmls +='<p>Contact Person: '+proObj[i]["contact_name"]+'</p>';
                        }
                        htmls += '<p>'+proObj[i]["comment"]+'</p>'+
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
                      /*  if(proObj[i]["added_by_id"]=="1" || proObj[i]["added_by_id"]=="2")
                        {
                          htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                        }
                        else if(proObj[i]["added_by_id"]==staff_id){
                          htmls +=  '<p>Added By: <b>Staff</b></p>';
                        }
                        else{
                          htmls +=  '<p>Added By: <b>Follower</b></p>';
                        }*/
                        htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                       /* htmls += '<a class="btn btn-danger btn-xs" onClick="delete_task_comment('+proObj[i]["id"]+','+i+');" id="btn_deleteAll" >'+
                        '<span class="glyphicon glyphicon-trash"></span></a>';*/
                      //  if(proObj[i]["added_by"]=='admin' && proObj[i]["status"]=="N")
                      if( proObj[i]["status"]=="N" && staff_id!=proObj[i]["added_by_id"])
                        {
                          if(proObj[i]["quick_task_comment"]=="Y")
                          {
                            if(staff_id==55 || staff_id==29)
                            {
                              var followerids = follower_id.toString().replace(/\,/g, '*');
                             
                              htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                            }

                          }
                          else{
                            var followerids = follower_id.toString().replace(/\,/g, '*');
                             
                            htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                          }
                       
                        }
                        if( proObj[i]["status"]=="R" && staff_id!=proObj[i]["added_by_id"])
                        {
                        

                        if(proObj[i]["quick_task_comment"]=="Y")
                          {
                            if(staff_id==55 || staff_id==29)
                            {
                              var followerids = follower_id.toString().replace(/\,/g, '*');
                              htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                            }

                          }
                          else{
                            var followerids = follower_id.toString().replace(/\,/g, '*');
                            htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                          }

                        }
                      htmls += '</div>';
                      for (var j = 0; j < proObj_replay.length; j++) {
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          if(staff_id==proObj_replay[j]["added_by_id"])
                          {var p=1;
                          htmls += '<div class="reply-comment staff" >'+
                           ''+proObj_replay[j]["comment"]+''+
                            '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                          /*  if(proObj_replay[j]["added_by_id"]=="1" || proObj_replay[j]["added_by_id"]=="2")
                            {
                              htmls +='<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            }
                            else if(proObj_replay[j]["added_by_id"]==staff_id){
                          htmls +=  '<p>Added By: <b>Staff</b></p>';
                        }
                        else{
                          htmls +=  '<p>Added By: <b>Follower</b></p>';
                        }*/
                        htmls +='<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            htmls +='</p>'+
                          '</div>';
                          }else{var p=2;
                            htmls += '<div class="reply-comment admin"  >';
                            if(proObj_replay[j]["comment"]!=null)
                        {
                          htmls +=proObj_replay[j]["comment"];
                        }
                        htmls +=  '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                           /* if(proObj_replay[j]["added_by_id"]=="1" || proObj_replay[j]["added_by_id"]=="2")
                            {
                              htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            }
                              else if(proObj_replay[j]["added_by_id"]==staff_id){
                            htmls +=  '<p>Added By: <b>Staff</b></p>';
                          }
                          else{
                            htmls +=  '<p>Added By: <b>Follower</b></p>';
                          }*/
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            if(proObj[i]["status"]=="N"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by_id"]){

                             /* if(proObj[i]["quick_task_comment"]=="Y")
                                {
                                    if(staff_id==55 || staff_id==29)
                                    {
                                      var followerids = follower_id.toString().replace(/\,/g, '*');
                                      htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                    }

                                }
                                else{
                                  var followerids = follower_id.toString().replace(/\,/g, '*');
                                  htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                }*/
                                htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                            }
                            if(proObj[i]["status"]=="R"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by_id"]){

                              /* if(proObj[i]["quick_task_comment"]=="Y")
                                {
                                    if(staff_id==55 || staff_id==29)
                                    {
                                      var followerids = follower_id.toString().replace(/\,/g, '*');
                                      htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                    }

                                }
                                else{
                                  var followerids = follower_id.toString().replace(/\,/g, '*');
                                  htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                }*/
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
                   /* for(k=0;k<rplays_but.length;k++)
                    {
                      var rowno=rplays_but[k];
                      $("#replay_but_"+rowno).hide();
                    }*/
                    var lastEl = rplays_but[rplays_but.length-1];
                    // if(p==1){
                    //   $("#replay_but_"+lastEl).show();
                    // }
                   // $("#replay_but_"+lastEl).show();
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



               var image_name = '';

               

              if(image_name != '') {    

          //    formData.append('image_name',$("#image_name")[0].files[0]);

              var file = document.getElementById('image_name').files[0];

              formData.append('image_name',file);  



              }

             // alert(image_name);

            formData.append('task_id',task_id);  

            formData.append('call_status','N');  
            formData.append('email_status','N');
            formData.append('visit_status','N');
           
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
              
  if(proObj.length>0)
  {

 $("#taskname").html(proObj[0]);

$("#task_view_description").html(proObj[1]);

$("#created_at").html(proObj[2]);

$("#start_date").html(proObj[3]);

$("#due_date").html(proObj[4]);

$("#priority_dis").html(proObj[5]);

$("#staff_dis").html(proObj[6]);

$("#follower_dis").html(proObj[7]);

  }


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


  $(document).on('click',".popup",function(){







var id=$(this).attr("data-id");



// alert(id)



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


       $('#contact_id').multiselect('rebuild');



    $("#myModal").modal("show");



  }



});



}); 





  function replay_task_comment(id,replay_id,follower_id,follower_id_new){
$("#replay_modal").modal("show");
$("#task_comment_id").val(id);
$("#parent_id").val(replay_id);
var staff_id=$("#staff_id").val();


// if ($.isNumeric(follower_id)) {
//   var follower_ids=follower_id.split("*");
// console.log(folowerids.length)
// }
if(follower_id_new>0)
{
  $("#follower").val(follower_id);
  $("#follower1").val(follower_id_new);
  if(staff_id==follower_id || staff_id==follower_id_new){
    $(".status-sec").show();
    }
    else{
      $(".status-sec").hide();
    }

}
else{
  $("#follower").val(follower_id);
  $("#follower1").val(follower_id_new);
    if(staff_id==follower_id){
    $(".status-sec").show();
    }
    else{
      $(".status-sec").hide();
    }
}


}



 function add_replay_comment()
{
  var task_id=$("#task_id").val();
  var task_comment_id=$("#task_comment_id").val();
  var replay_comment=$("#replay_comment").val();
  var follower=$("#follower").val();
  var follower1=$("#follower1").val();
  var staff_id=$("#staff_id").val();
if(follower1>0)
{
  if(follower==staff_id || follower1==staff_id){
    var status = $("input[name='status_replay']:checked").val();
  }else{
    var status = "N";
  }

}
else{

if(follower==staff_id){
    var status = $("input[name='status_replay']:checked").val();
  }else{
    var status = "N";
  }


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
      //  window.location.href=APP_URL+'/staff/inprogressTask';
      }
    });
}


</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#contact_id').multiselect();
   
});
</script>


@endsection
