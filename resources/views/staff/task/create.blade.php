@extends('staff/layouts.app')

@section('title', 'Add Task')

@section('content')

<section class="content-header">
      <h1>
        Add Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.task.index')}}">Manage Task</a></li>
        <li class="active">Add Task</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12 creat-taskpage">
          <!-- general form elements -->
          <div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
          
            </p>

            <form autocomplete="off" role="form" name="frm_task" id="frm_task" method="post" action="{{route('staff.task.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body border-row">
                  <div class="row">
                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Task Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Task Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Company*</label>
                  <select name="company_id" id="company_id" class="form-control">
                    <option value="">-- Select Company --</option>
                    <?php
                    foreach($company as $item) {
                      $sel = (old('company_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>

                <!-- <div class="form-group col-md-6">
                  <label>Assigned Team*</label>
                  <select name="assigned_team" id="assigned_team" class="form-control" onchange="change_assigned_team()">
                    <option value="">-- Select Assigned Team --</option>
                    <?php
                    foreach($designation as $item) {
                      $sel = (old('assigned_team') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                    } ?>
                  </select>
                  <span class="error_message" id="assigned_team_message" style="display: none">Field is required</span>
                </div> -->

                 <div class="form-group col-md-3 col-sm-6 col-lg-3 zndex-top1">
                  <label>Assignees*</label>
                  <select name="assigns[]" id="assigns" class="form-control"
                  <?php
                     if( session('STAFF_ID')!="21" && session('STAFF_ID')!="17"
                     && session('STAFF_ID')!="23" && session('STAFF_ID')!="39" && session('STAFF_ID')!="16"
                     && session('STAFF_ID')!="70" && session('STAFF_ID')!="94" && session('STAFF_ID')!="69"
                     && session('STAFF_ID')!="18" && session('STAFF_ID')!="15" )
                     {?> multiple="multiple" onchange="change_assignes()" <?php }else{?> onchange="change_assignes_staff()" <?php } ?>  >
                    <!-- <option value="">-- Select Assignees --</option> -->
                   
                    <option value="" >Select Assignee</option>
                   <?php
                    foreach($staff as $item) {
                     // $sel = (old('followers') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" >'.$item->name.'</option>';

                    } ?>
                   
                  
                  </select>
                  <span class="error_message" id="assigns_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Related To*</label>
                  <select name="related_to" id="related_to" class="form-control" onchange="change_related_to()">
                    <option value="">-- Select Related To --</option>

                     <?php
                    foreach($relatedto_category as $item) {
                      $sel = (old('related_to') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                    } ?>

                  </select>
                  <span class="error_message" id="related_to_message" style="display: none">Field is required</span>
                </div>
              </div>
              <div class="row">
                
                    
                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Followers*</label>
                  <select name="followers" id="followers" class="form-control" >
                    <option value="">-- Select Followers --</option>
                    <?php
                     if( session('STAFF_ID')=="21" || session('STAFF_ID')=="17"
                     || session('STAFF_ID')=="23" || session('STAFF_ID')=="39" || session('STAFF_ID')=="16"
                     || session('STAFF_ID')=="70" || session('STAFF_ID')=="94" || session('STAFF_ID')=="69"
                     || session('STAFF_ID')=="18" || session('STAFF_ID')=="15"
                     || session('STAFF_ID')=="90" || session('STAFF_ID')=="96" || session('STAFF_ID')=="89" 
                     || session('STAFF_ID')=="93" || session('STAFF_ID')=="87" || session('STAFF_ID')=="91"
                     )
                     {
                      foreach($staff as $item) {
                        if($item->id=="127")
                        {
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                        }
                          
                      }
                     }
                     else{
                      foreach($staff as $item) {
                        $sel = (old('followers') == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
  
                      }
                     }
                    ?>
                  </select>
                  <span class="error_message" id="followers_message" style="display: none">Field is required</span>
                  <input type="hidden" id="service_follower" name="service_follower" value="">
                </div>
            


                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Related To Sub Category</label>
                  <select onchange="viewchecklist_details()" name="related_to_sub" id="related_to_sub" class="form-control">
                    <option value="">-- Select Related To Sub Category --</option>

                  </select>
                  <span class="error_message" id="related_to_sub_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group col-md-3 col-sm-6 col-lg-3 zndex-top2">
                  <label>Check list</label>
                  <select name="check_list_id[]" id="check_list_id" class="form-control" multiple="multiple">
                    <option value="">-- Select Check List --</option>

                  </select>
                  <span class="error_message" id="check_list_id_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group col-md-3 col-sm-6 col-lg-3 zndex-top3">
                  <label>State*</label>
                  
                  <select name="state_id" id="state_id" class="form-control " onchange="change_state()" >
                    <option value="">-- Select State --</option>
                    <?php
                    
                    foreach($state as $item) {
                      $sel = (old('state_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>
              </div>
              <div class="row">

                 <div class="form-group col-md-3 col-sm-6 col-lg-3 zndex-top4">
                  <label>District*</label>
                  <select name="district_id" id="district_id" class="form-control " onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php
                    
                    foreach($district as $item) {
                      $sel = (old('district_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>




                  <div class="form-group col-md-3 col-sm-6 col-lg-3 zndex-top5">
                  <label>Client*</label>
                  <select name="user_id" id="user_id" class="form-control " >
                    <option value="">-- Select Client --</option>
                    <?php
                    
                    /*foreach($user as $item) {
                      $sel = (old('user_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->business_name.'</option>';
                     } */ ?>
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Start Date*</label>
                  <input type="text" id="start_date" name="start_date" value="{{ old('start_date')}}" class="form-control" placeholder="Start Date">
                  <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Start Time*</label>
                  <input type="text" id="start_time" name="start_time" value="{{ old('start_time')}}" class="form-control" placeholder="Start Time">
                  <span class="error_message" id="start_time_message" style="display: none">Field is required</span>
                </div>
              </div>
              <div class="row">
                
                 <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Priority*</label>
                  <select name="priority" id="priority" class="form-control">
                    <option value="">-- Select Priority --</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                   
                  </select>
                  <span class="error_message" id="priority_message" style="display: none">Field is required</span>
                </div>


                   <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label>Repeat Every</label>
                  <select  name="repeat_every" id="repeat_every" class="form-control" onchange="change_repaeat(this.value)">
                    <option value="">-- Select Repeat Every --</option>
                    <option value="Days">Days</option>
                    <option value="Week">Week</option>
                    <option value="2weeks">2 Weeks</option>
                    <option value="1Month">1 Month</option>
                    <option value="2Month">2 Months</option>
                    <option value="3Month">3 Months</option>
                    <option value="6Month">6 Months</option>
                    <option value="1year">1 Year</option>
                    <!-- <option value="Custom">Custom</option> -->
                  </select>
                  <span class="error_message" id="repeat_every_message" style="display: none">Field is required</span>
                </div>

                
<!-- 
                <div class="form-group col-md-3 col-sm-6 col-lg-3 customtype" style="display:none;">
                  <label for="name">Custom Days Type</label>
                  <select name="custom_type" id="custom_type" class="form-control">
                   
                   
                    <option value="Weeks">Weeks</option>
                    <option value="Months">Months</option>
                    <option value="Years">Years</option>
                   
                  </select>
                  <span class="error_message" id="custom_type_message" style="display: none">Field is required</span>
                </div> -->
<!-- 
                <div class="form-group col-md-3 col-sm-6 col-lg-3 customtype" style="display:none;"> 
                  <label for="name">Custom Days</label>
                  <input type="text" id="custom_days" name="custom_days" value="{{ old('custom_days')}}" class="form-control" >
                  <span class="error_message" id="custom_days_message" style="display: none">Field is required</span>
                  <span class="error_message" id="custom_days_no_message" style="display: none"></span>
                </div> -->
              <!-- </div>
              <div class="row"> -->


                <!-- <div class="form-group col-md-3 col-sm-6 col-lg-3 repeat-sec" style="display:none;">
                  <label>Repeat Every*</label>      
                <div class="input-group">
                        <input type="number"  class="form-control" disabled="" name="cycles" id="cycles" value="0">
                        <div class="input-group-addon">
                           <div class="checkbox">
                              <input type="checkbox" checked="" id="unlimited_cycles" name="unlimited_cycles">
                              <label for="unlimited_cycles">Infinity</label>
                           </div>
                        </div>
                      
                     </div>
                     <span class="error_message" id="cycles_message" style="display: none">Field is required</span>

               


                </div> -->

                
                
                <!-- <div class="form-group col-md-3 col-sm-6 col-lg-3 " id="infdate" >
                  <label for="name">Infinity End Date</label>
                  <input type="text" id="infinity_end_date" name="infinity_end_date" value="{{ old('infinity_end_date')}}" class="form-control" placeholder="Infinity End Date">
                  <span class="error_message" id="infinity_end_date_message" style="display: none">Field is required</span>
                </div> -->
                     
                 
                  

                <!-- <div class="form-group date_dis_sec  col-md-3 col-sm-6 col-lg-3" style="display:none;">
                     <table class="table allday_taks">
                     <thead>
                      <tr>
                        <th scope="col">Days</th>
                       
                      </tr>
                    </thead>
                    <tbody id="alldate">
                      
                     
                      
                    </tbody>

                     </table>
                </div>
                 -->

              
                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Due Date*</label>
                  <input type="text" id="due_date" disabled="true" name="due_date" value="{{ old('due_date')}}" class="form-control" placeholder="Due Date">
                  <input type="hidden" id="due_date_value" name="due_date_value" value="{{ old('due_date')}}">
                  <span class="error_message" id="due_date_message" style="display: none">Field is required</span>
                </div>
              <!-- </div>
              <div class="row"> -->

                  <!-- <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Updated Frequency Hour</label>
                  <input type="number" id="freq_hour" name="freq_hour" value="{{ old('freq_hour')}}" class="form-control" placeholder="Updated Frequency Hour">
                  <span class="error_message" id="freq_hour_message" style="display: none">Field is required</span>
                </div> -->

                  <div class="form-group col-md-3 col-sm-6 col-lg-3">
                  <label for="name">Amount</label>
                  <input type="text" id="amount" name="amount" value="{{ old('amount')}}" class="form-control" placeholder="Amount">
                  <span class="error_message" id="amount_message" style="display: none">Field is required</span>
                </div>
              </div>
              <div class="row">

                <div class="form-group col-md-12 col-sm-12">
                  <label for="name">Task Description</label>
                  <textarea id="description" name="description"  class="form-control" placeholder="Task Description">{{ old('description')}}</textarea>
                  <span class="error_message" id="description_message" style="display: none">Field is required</span>
                </div>    
               
                </div>
             
                <div class="box-footer">
                <button type="button" class="mdm-btn submit-btn  "  onclick="validate_from()">Submit</button>
                <button type="button" class="mdm-btn cancel-btn" onClick="window.location.href='{{route('staff.task.index')}}'">Cancel</button>
              </div>

              </div>
              <!-- /.box-body -->

            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')



    <script type="text/javascript">


 function change_assignes()
  {
    var assigns=$("#assigns").val();
    var related_to=$("#related_to").val();
    var url = APP_URL+'/staff/change_assignes';
   
    
    
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            assigns: assigns
          },
          success: function (data)
          {    
            var log_staff_id=assigns;
    
            
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Followers</option>';
            if( log_staff_id=="21" ||log_staff_id=="17"
                     || log_staff_id=="23" || log_staff_id=="39" || log_staff_id=="16"
                     || log_staff_id=="70" || log_staff_id=="94" || log_staff_id=="69"
                     || log_staff_id=="18" || log_staff_id=="15" )
                     {
                      for (var i = 0; i < proObj.length; i++) {
                        if(proObj[i]["id"]=="127")
                        {
                          states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
                        }
              
           }

                     }else{
                      for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           }
                     }

           
              if(related_to == 17){
                $("#followers").val(127);
              }else{
              $("#followers").html(states_val);
              }
             
              
           
          }
        });

    
  }  



  
 function change_assignes_staff()
  {
    var assigns=$("#assigns").val();
    var related_to=$("#related_to").val();
    var url = APP_URL+'/staff/change_assignes';
   
    var arr=[assigns];
   
    
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            assigns: arr
          },
          success: function (data)
          {    
            var log_staff_id=assigns;
    
            
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Followers</option>';
            if( log_staff_id=="21" ||log_staff_id=="17"
                     || log_staff_id=="23" || log_staff_id=="39" || log_staff_id=="16"
                     || log_staff_id=="70" || log_staff_id=="94" || log_staff_id=="69"
                     || log_staff_id=="18" || log_staff_id=="15" )
                     {
                      for (var i = 0; i < proObj.length; i++) {
                        if(proObj[i]["id"]=="13")
                        {
                          states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
                        }
              
           }

                     }else{
                      for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           }
                     }

           
              if(related_to == 17){
                $("#followers").val(13);
              }else{
              $("#followers").html(states_val);
              }
             
              
           
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
             $('#assigns').multiselect('rebuild');
              
           
          }
        });

  }
  function change_related_to(){
  var related_to=$("#related_to").val();
  if(related_to == 17){
    $('#followers').val(127);
    $('#service_follower').val(127);
    
    $("#followers").prop("disabled", true);

  }else{
    $('#service_follower').val();
    $('#followers').val();
    $("#followers").prop("disabled", false);
  }
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
        var start_date=$("#start_date").val();
        var start_time=$("#start_time").val();
        var start_time=$("#start_time").val();
        var cycles=$("#cycles").val();

        var custom_type=$("#custom_type").val();
        var custom_days=$("#custom_days").val();
        var infinity_end_date=$("#infinity_end_date").val();
        var unlimited_cycles=$('#unlimited_cycles:checkbox:checked').length;
        
        if(start_date!='' && start_time!='')
        {
          $("#start_date_message").hide();
          $("#start_time_message").hide();
            var url = APP_URL+'/staff/change_repeat_every';
          
            $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              repeat_type: repeat_val,start_date:start_date,cycles:cycles,custom_type:custom_type,custom_days:custom_days,start_time:start_time,infinity_end_date:infinity_end_date,unlimited_cycles:unlimited_cycles
            },
            success: function (data)
            {  
              var datas=data.split("*");  
              $("#due_date").val(datas[0]);
              $("#due_date_value").val(datas[0]);
              var proObj = JSON.parse(datas[1]);
            console.log(proObj);
            var htmls='';
            for (var i=0;i<proObj.length;i++)
            {
              htmls +=' <tr> <th scope="row">'+proObj[i]+'</th> </tr>';
            }
            
          //$("#alldate").html(htmls);
          $(".date_dis_sec").show();
           // alert(data);
            }
          });
        }
        else{
          $("#repeat_every").val('');
            if(start_date=="")
              {
                $("#start_date_message").show();
              }
              else{
                $("#start_date_message").hide();
              }

              if(start_time=="")
              {
                $("#start_time_message").show();
              }
              else{
                $("#start_time_message").hide();
              }

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
        var start_time=$("#start_time").val();

           var state_id=$("#state_id").val();
        var district_id=$("#district_id").val();
        
        var due_date=$("#due_date").val();
        var priority=$("#priority").val();
        var repeat_every=$("#repeat_every").val();

        var infinity_end_date=$("#infinity_end_date").val();
        var unlimited_cycles=$('#unlimited_cycles:checkbox:checked').length;
        
        var cycles=$("#cycles").val();
        var custom_days=$("#custom_days").val();
        
        var infinity=0;
        if(unlimited_cycles>0)
        {
          if(infinity_end_date=="")
          {
            infinity=1;
            $("#infinity_end_date_message").show();
          }
          else{
            infinity=0;
            $("#infinity_end_date_message").hide();
          }
          $("#cycles_message").hide();
        }
        else{
          $("#infinity_end_date_message").hide();
          if(cycles=="")
          {
            infinity=1;
            $("#cycles_message").show();
          }
          else{
            infinity=0;
            $("#cycles_message").hide();
          }

         
          $("#infinity_end_date_message").hide();
        }
    //   alert(infinity)
    var custom=0;
        if(repeat_every=="Custom")
        {
          
          if(custom_days=="")
          {
            custom=1;
            $("#custom_days_message").show();
          }
          else{
            custom=0;
            $("#custom_days_message").hide();
          }
          
        }

        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }
        if(repeat_every=="")
        {
          $("#repeat_every_message").show();
        }
        else{
          $("#repeat_every_message").hide();
        }

          if(state_id=="")
        {
          $("#state_id_message").show();
        }
        else{
          $("#state_id_message").hide();
        }

         if(district_id=="")
        {
          $("#district_id_message").show();
        }
        else{
          $("#district_id_message").hide();
        }

        
          if(priority=="")
        {
          $("#priority_message").show();
        }
        else{
          $("#priority_message").hide();
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

         if(start_time=="")
        {
          $("#start_time_message").show();
        }
        else{
          $("#start_time_message").hide();
        }
        
         if(due_date=="")
        {
          // $("#due_date_message").show();
        }
        else{
          $("#due_date_message").hide();
        }
        
        if(district_id!='' && state_id!='' &&  custom==0 && infinity==0 && repeat_every!='' && priority!='' && start_time!='' && name!='' &&  assigns!='' && followers!='' && related_to!='' && user_id!='' && start_date!=''   && company_id!='')
        {
         $("#frm_task").submit(); 
        }


      }
      
     
     /* $("#cycles").change(function() {
        change_repaeat($("#repeat_every").val())
      });*/
      $("#cycles").keyup(function() {
        change_repaeat($("#repeat_every").val())
      });
      $("#custom_days").change(function() {
        
        var custom_type=$("#custom_type").val();
        var custom_days=$("#custom_days").val();
        
        if(custom_type=="Weeks")
        {
          if(custom_days<=7)
          {
            $("#custom_days_no_message").hide();
            
          }
          else{
            $("#custom_days_no_message").html('Maximum custom day count for week will be 7');
            $("#custom_days_no_message").show();
            $("#custom_days").val('');
          }
        }
        if(custom_type=="Months")
        {
          if(custom_days<=30)
          {
            $("#custom_days_no_message").hide();
          }
          else{
            $("#custom_days_no_message").html('Maximum custom day count for month will be 30');
            $("#custom_days_no_message").show();
            $("#custom_days").val('');
           
          }
        }
        if(custom_type=="Years")
        {
          if(custom_days<=365)
          {
          
            $("#custom_days_no_message").hide();
          }
          else{
            $("#custom_days_no_message").html('Maximum custom day count for year will be 365');
            $("#custom_days_no_message").show();
            $("#custom_days").val('');
          }
        }

        
        change_repaeat($("#repeat_every").val())
      });

      $("#custom_type").change(function() {
        
        change_repaeat($("#repeat_every").val())
      });
      $("#infinity_end_date").change(function() {
        
        change_repaeat($("#repeat_every").val())
      });
      
      

      $("#unlimited_cycles").change(function() {
    if(this.checked) {  $("#infinity_end_date").val('');
      $("#cycles").val(0);
      $("#cycles").attr("disabled", "disabled"); 
   
      $("#infdate").show();
      change_repaeat($("#repeat_every").val())
      
    }
    else{  $("#infinity_end_date").val('');
    $("#infdate").hide();
      $("#cycles").removeAttr("disabled"); 
      change_repaeat($("#repeat_every").val())
    }
});


    </script>

    
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

 
<script type="text/javascript">

$('#start_time').timepicker({
  timeFormat: 'H:mm',
  'scrollDefaultNow': true
        });
        
$('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
            minDate: 0  
            
        });

        
$('#infinity_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
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

 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />


<script type="text/javascript">


$(document).ready(function() {
  $('#state_id').select2();
  $('#district_id').select2();
  $('#user_id').select2();
    $('#assigns').multiselect({
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
    });
    $('#check_list_id').multiselect({
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
    });
    // $("#state_id").selectpicker({
    //   enableFiltering: true,
    // });
  /* $("#followers").selectpicker({
      enableFiltering: true,
    });*/
    // $("#user_id").selectpicker({
    //   enableFiltering: true,
    // });
    // $("#district_id").selectpicker({
    //   enableFiltering: true,
    // });
});


function change_state(){
  var state_id=$("#state_id").val();
   $("#district_id").html('<option value="">Select District</option>');
  // $("#user_id").html('<option value="">Select Client</option>');
  // $('#district_id').selectpicker('refresh');
  // $('#user_id').selectpicker('refresh');
$(".loader_district_id").show();
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    $(".loader_district_id").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
          //    $('#district_id').selectpicker('refresh');
              
           
          }
        });

  }


  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  //$('#user_id').selectpicker('refresh');
  $(".loader_user_id").show();
  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
          },
          success: function (data)
          {    
            $(".loader_user_id").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
           
              }
              $("#user_id").html(states_val);
             
            //  $('#user_id').selectpicker('refresh');
           
          }
        });

  }

// function change_district(){
//   var state_id=$("#state_id").val();
//   $("#user_id").val('<option value="">Select Client</option>');
//   $('#user_id').selectpicker('refresh');
//   var district_id=$("#district_id").val();
//   var url = APP_URL+'/staff/get_client_use_state_district';
//    $.ajax({
//           type: "POST",
//           cache: false,
//           url: url,
//           data:{
//             state_id: state_id,district_id:district_id
//           },
//           success: function (data)
//           {    
//             var proObj = JSON.parse(data);
//             states_val='';
//             states_val +='<option value="">Select Client</option>';
//             for (var i = 0; i < proObj.length; i++) {
             
//               states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
           
//               }
//               $("#user_id").html(states_val);
//               $('#user_id').selectpicker('refresh');
              
           
//           }
//         });

//   }

//  function change_state(){
//   var state_id=$("#state_id").val();
//   $("#district_id").html('<option value="">Select District</option>');
//   $("#user_id").html('<option value="">Select Client</option>');
//   $('#district_id').selectpicker('refresh');
//   $('#user_id').selectpicker('refresh');
//   var url = APP_URL+'/staff/change_state';
//    $.ajax({
//           type: "POST",
//           cache: false,
//           url: url,
//           data:{
//             state_id: state_id,
//           },
//           success: function (data)
//           {    
//             var proObj = JSON.parse(data);
//             states_val='';
//             states_val +='<option value="">Select District</option>';
//             for (var i = 0; i < proObj.length; i++) {
             
//               states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
//               }
//               $("#district_id").html(states_val);
//               $('#district_id').selectpicker('refresh');
              
           
//           }
//         });

//   }


document.addEventListener('DOMContentLoaded', function() {
  const relatedToSelect = document.getElementById('related_to');
  const options = Array.from(relatedToSelect.options);

  const placeholder = options.shift();

  options.sort((a, b) => a.text.localeCompare(b.text));

  const selectedValue = relatedToSelect.value;

  options.unshift(placeholder);
  relatedToSelect.innerHTML = '';
  options.forEach(option => relatedToSelect.add(option));

  relatedToSelect.value = selectedValue;
});


</script>



@endsection
