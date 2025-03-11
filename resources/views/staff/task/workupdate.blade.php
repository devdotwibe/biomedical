@extends('admin/layouts.app')

@section('title', 'Work Update')

@section('content')
<?php 
if(isset($_GET['date'])) 
{
  $search_date=$_REQUEST['date'];
  $end_date=$_REQUEST['end_date'];
  $staff_id=$_REQUEST['staff_id'];
  
}
else{
  $search_date=date("Y-m-d");
  $end_date=date("Y-m-d");
  $staff_id="";
}

?>
<section class="content-header">
      <h1>
      Work Update
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Work Update</li>
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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/admin/task/deleteAll') }}" />
              @csrf

               <div class="form-group col-md-4">
                  <label for="name">Start Date</label>
                  <input type="text" id="search_date" name="search_date" value="<?php echo date("Y-m-d",strtotime($search_date));?>" class="form-control" placeholder="Start Date">
                  <span class="error_search_date" id="search_date_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4">
                  <label for="name">End Date</label>
                  <input type="text" id="end_date" name="end_date" value="<?php echo date("Y-m-d",strtotime($end_date));?>" class="form-control" placeholder="End Date">
                  <span class="error_end_date" id="end_date_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group col-md-4">
                  <label for="name">Staff</label>
                  <select id="staff_id" name="staff_id" class="form-control" placeholder="Search">
                  <option value="">Select Staff</option>
                  @foreach($staff as $valstaff)
                  <?php
                   $sel = ($valstaff->id ==$staff_id) ? 'selected': '';
                  ?>
                  <option value="{{$valstaff->id}}" <?php echo $sel;?>>{{$valstaff->name}}</option>
                  @endforeach
                 
                  </select>
                  <span class="error_search_date" id="staff_id_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group">
                <button type="button" class="btn btn-primary"  onclick="search_by_date()">Submit</button>
                </div>

<?php
if($staff_id>0)
{
$date = $search_date;
$arr_fair=array();
$arr_leave=array();
$arr_hosvisit=array();
$arr_contact=array();
$hospital=array();
$all_date=array();
$k=0;
/*while (strtotime($date) <= strtotime($end_date)) {


}*/

while (strtotime($date) <= strtotime($end_date)) {
               
  $j=1;
              
              $staff_date=$end_date;
              $arr_field=array();
              $arr_field_reject=array();
              $arr_office=array();
              $arr_office_reject=array();
              
                
echo '<div class="row">';

echo '<div style="border:1px solid #ccc;padding:10px;" class="col-md-12">';

/*********************************************************************************************** */
echo '<h3>Travel</h3>';
echo '<h3>'.$staff_date.'</h3>';
              $alltask_travel = DB::select("select * from dailyclosing_expence as daily  where  daily.staff_id='".$staff_id."'   AND daily.start_date='".$staff_date."' and daily.expence_cat='travel' and expence_section='field' order by daily.id ASC");
              if(count($alltask_travel)>0)
              {
             
                echo ' <table class="table">
                <tr>
                <th>Travel Type</th>
                <th>Start Reading</th>
                <th>End Reading</th>
                <th>kilometers</th>
                <th>Amount</th>
               
                <th>Start Time</th>
                <th>End Time</th>
                <th>Task</th>
                       
                       </tr>
                     ';
                     echo '  <tbody id="expence_data">';
                foreach($alltask_travel as $values)
                {
                  $total_meter=0;
                  $tot_price=0;
                  if($values->travel_type=="Bike")
                {
                  $curdate=2022-06-01;
                  $today_time=strtotime("2022-06-01");
  if (strtotime($values->start_date) < $today_time) {  $bike_rate=setting('BIKE_RATE_BEFORE_MAY'); }else{$bike_rate=setting('BIKE_RATE');}

                  $total_meter=$values->end_meter_reading-$values->start_meter_reading;
                  $tot_price=$total_meter*$bike_rate;
                }
                if($values->travel_type=="Car")
                {
                  $car_rate=5;
                  $total_meter=$values->end_meter_reading-$values->start_meter_reading;
                  $tot_price=$total_meter*5;  
                }
                if($values->travel_type!="Car" || $values->travel_type!="Bike")
                {
                  $tot_price=$values->travel_end_amount;
                }
             
                      echo '<tr>
                      <td>'.$values->travel_type.'</td>';
                      if($values->start_meter_reading>0)
                      {
                        echo ' <td>'.$values->start_meter_reading.'</td>';
                      }
                      else{
                        echo "<td>NA</td>";
                      }
                      if($values->end_meter_reading>0)
                      {
                        echo '    <td>'.$values->end_meter_reading.'</td>';
                      }
                      else{
                        echo "<td>NA</td>";
                      }
                  
                      echo '   <td>'.$total_meter.'</td>';
                            if($tot_price>0)
                            {
                              echo ' <td>'.$tot_price.'</td>';
                            }
                            else{
                              echo '<td>NA</td>';
                            }
                           
                            echo '<td>'.$values->start_time_travel.'</td>
                            <td>'.$values->end_time_travel.'</td>
                            <td>'.$values->task_name.'</td>';

                      echo '</tr>';
              
                
                }
                echo '</tbody></table>';
              }
              else{
                echo '<p>Travel not added</p>';
              }
              


      

 echo '<h3>Expence</h3>';


                 $alltask = DB::select("select * from dailyclosing_expence as daily  where  daily.staff_id='".$staff_id."'   AND daily.start_date='".$staff_date."' and daily.expence_cat='expence' and expence_section='field' order by daily.id ASC");
               
                 if(count($alltask)>0)
                 {
                
                   echo '<div class="exp_field'.$j.'"> <table class="table">
                   <tr>
                   <th>Action</th>
                          <th>Other Expence</th>
                          <th>Amount</th>
                          <th>Description</th>
                          <th>Task</th>
                          <th>Status</th>
                          
                          </tr>
                        ';
                        echo '  <tbody id="expence_data">';
                   foreach($alltask as $values)
                   {
                    
                         echo '<tr>';
                         if($values->status=="Y")
                         {
                         echo '<td></td>';
                         }
                         else{
                         echo '<td><input type="checkbox" class="dataCheck" name="ids'.$j.'field[]" value="'.$values->id.'" id=""></td>';
                         }

                         
                         echo '<td>'.$values->travel_type.'</td>
                               <td>'.$values->travel_start_amount.'</td>
                               <td>'.$values->expence_desc.'</td>
                               <td>'.$values->task_name.'</td>';
                               if($values->status=="Y")
                               {
                                   echo '<td>Approved</td>';
                               }
                               else if($values->status=="N"){
                                $arr_field[]=$values->id;
                                 echo '<td>Pending</td>';
                               }
                               else if($values->status=="Reject"){
                                $arr_field_reject[]=$values->id;
                                 echo '<td>Reject</td>';
                               }
                         echo '</tr>';
                       
                   
                   }
                   echo '</tbody></table></div>';
                 }
                 else{
                  echo '<p>Expence not added</p>';
                }

  echo '<h3>Attendance</h3>';
  
  $attend = DB::select("select * from work_report_for_leave   where  staff_id='".$staff_id."'   AND start_date='".$staff_date."' and type_leave='Request Leave Field Staff' ");
  if(count($attend)>0)
  {
    echo ' <table class="table">
    <tr>
           <th>Attendance</th>
          
           
           </tr>
         ';
         foreach($attend as $values)
         {
             echo '<tr>
               <td>'.$values->attendance.'</td>';
               echo '</tr>';
       
         
         }
         echo '</tbody></table>';
  }
  else{
    echo '<p>Attendance not added</p>';
  }

  if(count($alltask)>0 && count($attend)>0 )
  {
    if(count($arr_field)>0)
    {
    
  echo '
  <div class="form'.$j.'_field">
  <div class="form-group">
  <label for="exampleFormControlTextarea1">Comment</label>
  <textarea class="form-control" id="comment'.$j.'_field" rows="3" placeholder="Comment"></textarea>
</div>
<div class="form-group" >
<label >Status</label>

<select class="form-control" name="status_replay'.$j.'_field" id="status_replay'.$j.'_field">
<option value="Reject">Reject</option>
</select>
</div>
<div class="form-group" >
<button type="button" class="btn btn-primary save_comment" attr-date="'.$end_date.'" attr-type="field" attr-row_no="'.$j.'" >Submit</button>
</div>
</div>

';
}
else{
  if(count($alltask)>0)
  {
      if(count($arr_field_reject)>0)
      {
      echo "<span style='color:red'>Reject</span>";
      }
      else{
      echo "<span style='color:green'>Approved</span>";
      }
  }
  
}


  }

/*********************************************************************************************** */



/********************************************Office*************************************************** */



   $alltask_office = DB::select("select * from dailyclosing_expence as daily  where  daily.staff_id='".$staff_id."'   AND daily.start_date='".$staff_date."' and daily.expence_cat='expence' and expence_section='office' order by daily.id ASC");
                 if(count($alltask_office)>0)
                 { 
                  echo '<h3>Expence Office Staff</h3>';      
                   echo '<div class="exp_office'.$j.'"> <table class="table">
                   <tr>
                   <th>Action</th>
                          <th>Other Expence</th>
                          <th>Amount</th>
                          <th>Description</th>
                          <th>Task</th>
                        
                          <th>Status</th>
                          </tr>
                        ';
                        echo '  <tbody id="expence_data">';
                   foreach($alltask_office as $values)
                   {
                    
                    
                
                         echo '<tr>';
                         if($values->status=="Y")
                         {
                         echo '<td></td>';
                         }
                         else{
                         echo ' <td><input type="checkbox" class="dataCheck" name="ids'.$j.'office[]" value="'.$values->id.'" id=""></td>';
                         }
                        
                        echo ' <td>'.$values->travel_type.'</td>
                               <td>'.$values->travel_start_amount.'</td>
                               <td>'.$values->expence_desc.'</td>
                               <td>'.$values->task_name.'</td>';
                               if($values->status=="Y")
                               {
                                   echo '<td>Approved</td>';
                               }
                               else if($values->status=="N"){
                                 echo '<td>Pending</td>';
                                 $arr_office[]=$values->id;
                               }
                               else if($values->status=="Reject"){
                                 echo '<td>Reject</td>';
                                 $arr_office_reject[]=$values->id;
                               }
                              

                         echo '</tr>';
                 
                   
                   }
                   echo '</tbody></table></div>';
                 }
                 else{
                //  echo '<p>Expence not added</p>';
                }

 
  
  $attend_office = DB::select("select * from work_report_for_leave   where  staff_id='".$staff_id."'   AND start_date='".$staff_date."' and type_leave='Request Leave Office Staff' ");
  if(count($attend_office)>0)
  {
    echo '<h3>Attendance</h3>';
    echo ' <table class="table">
    <tr>
           <th>Attendance</th>
          
           
           </tr>
         ';
         foreach($attend_office as $values)
         {
             echo '<tr>
               <td>'.$values->attendance.'</td>';
               echo '</tr>';
       
         
         }
         echo '</tbody></table>';
  }
  else{
    echo '<p>Attendance not added</p>';
  }
  
  if(count($alltask_office)>0 && count($attend_office)>0)
  {
   if(count($arr_office)>0)
    {
  echo '
  <div class="form'.$j.'_office">
  <div class="form-group">
  <label for="exampleFormControlTextarea1">Comment</label>
  <textarea class="form-control" id="comment'.$j.'_office" rows="3" placeholder="Comment"></textarea>
</div>
<div class="form-group" >
<label >Status</label>
<select class="form-control" name="status_replay'.$j.'_office" id="status_replay'.$j.'_office">
<option value="Reject">Reject</option>
</select>


</div>
<div class="form-group" >
<button type="button" class="btn btn-primary save_comment" attr-date="'.$end_date.'" attr-type="office" attr-row_no="'.$j.'"  >Submit</button>
</div>

</div>
';
   }
   else{
   if(count($alltask_office)>0)
   {
      if(count($arr_office_reject)>0)
      {
      echo "<span style='color:red'>Reject</span>";
      }
      else{
      echo "<span style='color:green'>Approved</span>";
      }
   }  
    
   
   }
   

  }
/*********************************************************************************************** */


    echo '</div>';//border
    echo '</div>';//row

   echo ' <div class="mt-2 col-md-12"></div>';

              $end_date = date ("Y-m-d", strtotime("-1 day", strtotime($end_date)));
              $j++;
            
}

  
 
        
      


        echo '<div class="row">';     
     
        $date_exp= $search_date;

        while (strtotime($date_exp) <= strtotime($end_date)) {
               
          $j=1;
                      
                      $staff_date=$date_exp;
               
      
                      $date_exp = date ("Y-m-d", strtotime("+1 day", strtotime($date_exp)));
      
                    
        }
        

        echo '</div>';

      }
         ?>
              

             
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


function search_by_date()
{
  var search_date=$("#search_date").val();
  var staff_id=$("#staff_id").val();
  var end_date=$("#end_date").val();
  if(search_date=="")
  {
    $("#search_date_message").show();
  }
  else{
    $("#search_date_message").hide();

  }

  if(end_date=="")
  {
    $("#end_date_message").show();
  }
  else{
    $("#end_date_message").hide();

  }

  if(staff_id=="")
  {
    $("#staff_id_message").show();
  }
  else{
    $("#staff_id_message").hide();

  }
  if(search_date!='' && staff_id!='' && end_date!='')
  {
    var url = APP_URL+'/admin/workupdate?date='+search_date+'&staff_id='+staff_id+'&end_date='+end_date;
    window.location=url;
  }

}
 


 jQuery(".save_comment").click(function() {
var row_no=$(this).attr("attr-row_no");
var type=$(this).attr("attr-type");
var search_date=$(this).attr("attr-date");
var check_name='status_replay'+row_no+'_'+type;
var cmt_name='comment'+row_no+'_'+type;
var staff_id=$("#staff_id").val();
var status = $("#"+check_name).val();

if(status=="" || status==undefined)
{
$("#"+check_name+'_error').show();
}
else{
  $("#"+check_name+'_error').hide();
  var comment=$("#"+cmt_name).val();

var checked = []
$("input[name='ids"+row_no+type+"[]']:checked").each(function ()
{
    checked.push(parseInt($(this).val()));
});
console.log(checked);
  
var url = APP_URL+'/admin/update_expence_status_staff';

$.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        type:type,search_date:search_date,status:status,staff_id:staff_id,comment:comment,checked:checked,row_no:row_no
      },
      success: function (data)
      {  location.reload();
       /* var res = data.split("*");
      
        if(type=="office")
        {
          $(".exp_office"+row_no).html(res[0]);
        }
        else{
          $(".exp_field"+row_no).html(res[0]);
        }
        var div_sec='form'+row_no+'_'+type;
        if(res[1]!="0")
        {
          $("."+div_sec).html('<span style="color:red">Reject</span>');
        }
        else{
          $("."+div_sec).html('<span style="color:green">Approved</span>');
        }*/
      
      }
    });



}



      });

    jQuery(document).ready(function() {
     
    });
        



</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

$("#search_date").datepicker( {
    format: "mm-yyyy",
    dateFormat:'yy-mm-dd',
    changeMonth:true,
       changeYear:true,
       yearRange:"-1:+5",
       maxDate: '0', 
 
      
});

$("#end_date").datepicker( {
    format: "mm-yyyy",
    dateFormat:'yy-mm-dd',
    changeMonth:true,
       changeYear:true,
       yearRange:"-1:+5",
       maxDate: '0', 
});
</script>
@endsection
