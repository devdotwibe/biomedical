

@extends('staff/layouts.app')

@section('title', 'Comment Task')

@section('content')


<section class="content-header">
      <h1>
         Task Comment - {{ $task->name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <!-- <li><a href="{{route('staff.task.index')}}">Manage Task</a></li> -->
        <li class="active"> Task Comment</li>
      </ol>


      
    </section>

<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            </p>

            <form role="form" name="frm_subcategory" id="frm_subcategory" method="post" action="{{ route('staff.task.update', $task->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">

                 <?php
 $task_comment = DB::table('task_comment')

 ->where('task_id', [$task->id])

->get();


$task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$task->id."' order by id asc");

if(count($task_comment)>0)
{
$k=0;
  foreach($task_comment as $val_com)
  {
    echo '<div class="panel panel-default">
    <div class="panel-body">';
  
  
    echo $val_com->comment;
    echo '<br>';
    echo $val_com->created_at;
  
    echo '<p>Source: ';
    
    if($val_com->email=="Y")
    {
      echo 'Email,';
    }
    if($val_com->call_status=="Y")
    {
      echo 'Call,';
    }
    if($val_com->visit=="Y")
    {
     echo 'Visit';
    }
    echo '</p>';
  if($val_com->status=="Y"){
    echo '<p style="color:green">Approved</p>';
  }
  else if($val_com->status=="R"){
    echo '<p style="color:red">Reject</p>';
  }
  else{
    echo '<p style="color:red">Pending</p>';
  }

  if($val_com->added_by=="staff" && $val_com->added_by_id>0)
  {
    $staff_det = App\Staff::find($val_com->added_by_id);
    if($staff_det)
    {
      echo '<p>Assign to: <b>'.$staff_det->name.'</b></p>';
    }

  }

  if($task->followers>0 && $val_com->quick_task_comment=="Y")
  {

    $staff_det = App\Staff::find($task->followers);
    if($staff_det)
    {
      echo '<p>Added by: <b>'.$staff_det->name.'</b></p>';
    }

  }

  
  if($task->contact_person_id>0 && $k==0)
  {

    $contact_det = App\Contact_person::find($task->contact_person_id);
    if($contact_det)
    {
      echo '<p>Contact Person: <b>'.$contact_det->name.'</b></p>';
    }

  }

  
  if($val_com->added_by=="admin" && $val_com->added_by_id>0)
  {
    $staff_det = App\Staff::find($val_com->added_by_id);
    if($staff_det)
    {
      echo '<p>Added by: <b>'.$staff_det->name.'</b></p>';
    }

  }


  if(count($task_replay)>0)
{

  foreach($task_replay as $val_replay)
  {
    echo '<div class="reply-comment staff" >';
    if($val_replay->comment!='')
    {
      echo $val_replay->comment;
      echo '<br>';
      echo $val_replay->created_at;
      echo '<br>';
      echo $val_replay->added_by;
      echo '<br>';
      if($val_replay->added_by=="staff" && $val_replay->added_by_id>0)
  {
    $staff_det = App\Staff::find($val_replay->added_by_id);
    if($staff_det)
    {
      echo '<p>Assign to: <b>'.$staff_det->name.'</b></p>';
    }

  }


  if($val_replay->added_by=="admin" && $val_replay->added_by_id>0)
  {
    $staff_det = App\Staff::find($val_replay->added_by_id);
    if($staff_det)
    {
      echo '<p>Added by: <b>'.$staff_det->name.'</b></p>';
    }

  }
      
    }
    echo '</div>';
  }
}



    echo '</div></div>';
    $k++;
  }
}else{
  echo "<p>No comments added</p>";
}
?>


                

              </div>
              <!-- /.box-body -->

              <!-- <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.task.index')}}'">Cancel</button>
              </div> -->
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
 

 <script type="text/javascript">


function change_assigned_team(){
var assigned_team=$("#assigned_team").val();
var url = APP_URL+'/staff/change_assigned_team';
$.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        assigned_team: assigned_team,
      },
      success: function (data)
      {    
        var proObj = JSON.parse(data);
        states_val='';
        states_val +='<option value="">-- Select Assignees --</option>';
        for (var i = 0; i < proObj.length; i++) {
         
          states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
       
          }
          $("#assigns").html(states_val);
         
      }
    });

}
function change_related_to(){
var related_to=$("#related_to").val();
var url = APP_URL+'/staff/change_related_to';
$.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        related_to: related_to,
      },
      success: function (data)
      {    
        var proObj = JSON.parse(data);
        states_val='';
        states_val +='<option value="">-- Select Related To Sub Category --</option>';
        for (var i = 0; i < proObj.length; i++) {
         
          states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
       
          }
          $("#related_to_sub").html(states_val);
         
          
       
      }
    });

}


     function viewchecklist_details(){
  var related_to_sub=$("#related_to_sub").val();
  var url = APP_URL+'/staff/viewchecklist_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            related_to_sub: related_to_sub,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">-- Select Checklist --</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#check_list_id").html(states_val);
             $('#check_list_id').multiselect('rebuild');
              
           
          }
        });

  }


  function change_repaeat(repeat_val)
  {
    if(repeat_val=="")
    {
      $(".repeat-sec").hide();
    }else{
      $(".repeat-sec").show();
    }
  
    if(repeat_val=="Custom")
    {
      $(".customtype").show();
    }
    else{
      $(".customtype").hide();
    }
  

  }
  function validate_from()
  {
    var name=$("#name").val();
    var company_id=$("#company_id").val();
  //  var assigned_team=$("#assigned_team").val();
    var assigns=$("#assigns").val();
    var followers=$("#followers").val();
    var related_to=$("#related_to").val();
    var user_id=$("#user_id").val();
    var start_date=$("#start_date").val();
    var due_date=$("#due_date").val();
    

    
    if(name=="")
    {
      $("#name_message").show();
    }
    else{
      $("#name_message").hide();
    }

     if(company_id=="")
    {
      $("#company_id_message").show();
    }
    else{
      $("#company_id_message").hide();
    }

    //  if(assigned_team=="")
    // {
    //   $("#assigned_team_message").show();
    // }
    // else{
    //   $("#assigned_team_message").hide();
    // }

    if(assigns=="")
    {
      $("#assigns_message").show();
    }
    else{
      $("#assigns_message").hide();
    }

    if(followers=="")
    {
      $("#followers_message").show();
    }
    else{
      $("#followers_message").hide();
    }
    
    if(related_to=="")
    {
      $("#related_to_message").show();
    }
    else{
      $("#related_to_message").hide();
    }

    if(user_id=="")
    {
      $("#user_id_message").show();
    }
    else{
      $("#user_id_message").hide();
    }

     if(start_date=="")
    {
      $("#start_date_message").show();
    }
    else{
      $("#start_date_message").hide();
    }

     if(due_date=="")
    {
      $("#due_date_message").show();
    }
    else{
      $("#due_date_message").hide();
    }
    
    if(name!='' &&  assigns!='' && followers!='' && related_to!='' && user_id!='' && start_date!='' && due_date!=''  && company_id!='')
    {
     $("#frm_task").submit(); 
    }


  }


  $("#unlimited_cycles").change(function() {
if(this.checked) {

  $("#cycles").attr("disabled", "disabled"); 
}
else{
  $("#cycles").removeAttr("disabled"); 
}
});


</script>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$('#start_date').datepicker({
      dateFormat:'yy-mm-dd',
       // dateFormat:'dd/mm/yy',
     
     
        minDate: 0  
        
    });
    $('#due_date').datepicker({
        //dateFormat:'yy-mm-dd',
        dateFormat:'yy-mm-dd',
       
     
        minDate: 0  
        
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script>
$(document).ready(function() {
    $('#assigns').multiselect();
    $('#check_list_id').multiselect();
});
</script>


@endsection
