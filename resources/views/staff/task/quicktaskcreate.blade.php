@extends('staff/layouts.app')

@section('title', 'Quick Task')

@section('content')

<section class="content-header">
      <h1>
      Quick Task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <!--  -->
        <li class="active">Add Quick Task</li>
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

            <form autocomplete="off" role="form" name="frm_task" id="frm_task" method="post" action="{{route('staff.tasksave')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body ">


                    <div class="form-group col-md-6">
                  <label>State*</label>
                  
                  <select name="state_id" id="state_id" class="form-control selectpicker" onchange="change_state()"  data-live-search="true">
                    <option value="">-- Select State --</option>
                    <?php
                    
                    foreach($state as $item) {
                      $sel = (old('state_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-6">
                  <label>District*</label>
                  <select name="district_id" id="district_id" class="form-control selectpicker" data-live-search="true" onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php
                    
                    foreach($district as $item) {
                      $sel = (old('district_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>




                  <div class="form-group col-md-6">
                  <label>Customer*</label>
                  <select name="user_id" id="user_id" class="form-control selectpicker" data-live-search="true" onchange="change_user_get_contact_person()">
                    <option value="">-- Select Customer --</option>
                   
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                </div>


                   <div class="form-group col-md-6">
                  <label>Contact Person  <a class="contact_link"   style="display:none;" target="_blank">Add Contact</a></label>
                 
                  <select name="contact_person_id" id="contact_person_id" class="form-control" >
                    <option value="">-- Select Contact Person --</option>
                    
                  </select>
                  <span class="error_message" id="contact_person_id_message" style="display: none">Field is required</span>
                </div>


                <!-- <div class="form-group col-md-6">
                  <label for="name">Task Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Task Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div> -->

                  <div class="form-group col-md-12">
                  <label for="name">Task Type* <a onclick="view_task_type()">Add Task Type</a></label>
                  <select name="name" id="name" class="form-control" >
                    <option value="">Select Task Type</option>
                    <?php
                    
                    foreach($task_type as $item) {
                      $sel = (old('name') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                     } ?>
                    <!-- <option value="Amc Followup">Amc Followup</option>
                    <option value="FMC Promotion">FMC Promotion</option>
                    <option value="ENT Promotion">ENT Promotion</option>
                    <option value="MIC Promotion">MIC Promotion</option> -->
                  </select>
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

             

          
          <div class="form-group col-md-12 checkssec"> <div class="col-md-3"><label class="form-check-label">Email 
            <input type="checkbox" name="email_status" id="email_status" class="form-check-input" value="Y">    </label> </div>
            <div class="col-md-3"><label class="form-check-label">Call 
            <input type="checkbox" name="call_status" id="call_status" class="form-check-input" value="Y">    </label> </div>
            <div class="col-md-3"><label class="form-check-label">Visit 
            <input type="checkbox" name="visit_status" id="visit_status" class="form-check-input" value="Y">    </label> </div>
            </div>

            <div class="form-group col-md-4">
            <label for="name">Brand</label>
            <select class="form-control" name="brand_id[]" id="brand_id" multiple="multiple"> 
                      <!-- <option value="">Brand</option> -->
                   @foreach ($brand_search as $brands)
                
                      <option value="{{$brands->brandid}}">{{$brands->brandname}}</option>
                    
                      @endforeach
                  </select>
                  </div>
                  <div class="form-group col-md-4">
                  <label for="name">Category</label>
                   <select class="form-control" name="category_type_id[]" id="category_type_id" multiple="multiple">
                   <!-- <option value="">Select Category</option> -->
                   @foreach ($category_type as $cattype)
                      <option value="{{$cattype->id}}">{{$cattype->name}}</option>
                      @endforeach

                  </select>
                  </div>

                  <div class="form-group col-md-3">
                  <label for="name">Product</label>
                  <select class="form-control" name="product_id[]" id="product_id" multiple="multiple" >
                   <!-- <option value="">Select Product</option> -->
                 

                  </select>
                  <div class="loader_pro" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                 
                </div>
                <div class="form-group col-md-1">
                <button type="button" class="btn btn-primary"  onclick="add_product()">Add</button>
                </div>
                <div class="form-group col-md-12">
                  <table class="table" id="disajax" style="display:none">
                    <tr>
                  <th>Product</th>
                </tr>
                <tbody id="responce_ajax">

                </tbody>

                </table>
                </div>

                   <!-- <select class="custom-select my-select"  name="category_id" id="category_id">
                    <option  value="">Care Area</option>
                        @foreach ($categories as $cat)
                      <option value="{{$cat->catid}}">{{$cat->catname}}</option>
                      @endforeach
                  </select> -->



             <div class="form-group col-md-12">
                  <label for="name">Comment*</label>
                  <textarea id="description" name="description"  class="form-control" placeholder="Comment">{{ old('description')}}</textarea>
                  <span class="error_message" id="description_message" style="display: none">Field is required</span>
                </div>  
              

                  <div class="form-group col-md-12"><br> <br> </div>


                 <!-- <div class="form-group col-md-6">
                  <label>Assignees*</label>
                  <select name="assigns[]" id="assigns" class="form-control" multiple="multiple"  onchange="change_assignes()">
                     <?php
                   /* foreach($staff as $item) {
                      $sel = (old('followers') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                      }*/?>
                     </select>
                  <span class="error_message" id="assigns_message" style="display: none">Field is required</span>
                </div> -->

                <div class="form-group col-md-6">
                  <label>Followup Person</label>
                  <select name="followers" id="followers" class="form-control" >
                    <option value="">-- Select Followup Person --</option>
                    <?php
                    foreach($staff as $item) {
                      $sel = (old('followers') == $item->id) ? 'selected': '';
                     
                      echo '<option value="'.$item->id.'" >'.$item->name.'</option>';
                     
                       
                      
                    
                    } ?>
                  </select>
                  <span class="error_message" id="followers_message" style="display: none">Field is required</span>
                </div>


                  <div class="form-group col-md-6">
                  <label for="name">Followup Date</label>
                  <input type="text" id="start_date" name="start_date" value="{{ old('start_date')}}" class="form-control" placeholder="Start Date">
                  <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
                </div>

  <div class="form-group col-md-12">
                  <label for="name">Followup Action</label>
                  <textarea id="followup_action" name="followup_action"  class="form-control" placeholder="Followup Action">{{ old('description')}}</textarea>
                  <span class="error_message" id="followup_action_message" style="display: none">Field is required</span>
                </div>  

                 <!-- <div class="form-group col-md-6">
                  <label for="name">Followup Time*</label>
                  <input type="text" id="start_time" name="start_time" value="{{ old('start_time')}}" class="form-control" placeholder="Start Time">
                  <span class="error_message" id="start_time_message" style="display: none">Field is required</span>
                </div> -->

                

             
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
              
          
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.task.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>



<div class="modal fade" id="modal_ship" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Add Task Type</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="add_address" id="add_address" method="post">
        
        
          <div class="form-group  col-md-6">
            <label for="name">Task Type</label>
            <input type="text" id="task_type" name="task_type" class="form-control"  placeholder="Task Type">
            <span class="error_message" id="task_type_message" style="display: none">Field is required</span>
          </div>


        </form>
      </div>
      <div class="modal-footer">
      <span class="datasuccess" style="display:none;color:green">Data saved successfully</span>
      <span class="dataerror" style="display:none;color:red">Task Type already exit</span>
    
      <button type="button" class="btn btn-primary"  onclick="save_task_type()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')



    <script type="text/javascript">
    var product_Arr      =   [];
function add_product()
{
  
  $("#disajax").show();
 var product_id=$("#product_id").val();
 $('select[name="product_id[]"] option:selected').each(function() {

  if ($.inArray($(this).val(), product_Arr) >= 0) {

  }
  else{
    product_Arr.push($(this).val());
  }
  
  

 });
 
 var url = APP_URL+'/staff/edit_part';
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id:product_Arr,type:'product'
          },
          success: function (data)
          {  
            $("#responce_ajax").html(data);

          }
    });

 
}



function remove_product(product_id)
{
  
$("#row_"+product_id).remove();

var itemtoRemove = product_id;
product_Arr.splice($.inArray(itemtoRemove, product_Arr),1);


}


$("#brand_id").change(function(){
/**

  var proObj = JSON.parse(data);
       var  htmls=' ';
       htmls +="<option value=''>Select Care Area</option>";
        for (var i = 0; i < proObj.length; i++) {
         htmls +="<option value='"+proObj[i]["category_id"]+"'>"+proObj[i]["category_name"]+"</option>";
            }
    
       $("#category_id").html(htmls);
        */

   var brand_id=$("#brand_id").val();
  var category_type_id=$("#category_type_id").val();
  
   var url = APP_URL+'/staff/edit_part';
   $(".loader_pro").show();
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            brand_id:brand_id,category_type_id:category_type_id,type:'search'
          },
          success: function (data)
          {  $(".loader_pro").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">-- Select Product --</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option data-id="'+proObj[i]["name"]+'" value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#product_id").html(states_val);
             $('#product_id').multiselect('rebuild');

          }
    });

});



$("#category_type_id").change(function(){
 $("#sub_category_id").hide();
 

  var brand_id=$("#brand_id").val();
  var category_type_id=$("#category_type_id").val();
  $(".loader_pro").show();
   var url = APP_URL+'/staff/edit_part';
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            brand_id:brand_id,category_type_id:category_type_id,type:'search'
          },
          success: function (data)
          {  $(".loader_pro").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">-- Select Product --</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'" data-id="'+proObj[i]["name"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#product_id").html(states_val);
             $('#product_id').multiselect('rebuild');
          }
    });

});


function view_task_type()
{
  $(".datasuccess").hide();
  $(".dataerror").hide();
$("#task_type").val('');
$("#modal_ship").modal("show")
}
function save_task_type()
{
  var task_type=$("#task_type").val();

if(task_type=="")
{
  $("#task_type_message").show();
}
else{
  $("#task_type_message").hide();
}
if(task_type!='')
{
  var url = APP_URL+'/staff/save_task_type';
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            task_type: task_type
          },
          success: function (data)
          {  
            $("#task_type").val('');
            var res=data.split("*"); 
            var proObj= JSON.parse(res[1]);; 
            var htmls='';
            htmls +='<option value="">Task Type</option>';
            for (var i=0;i<proObj.length;i++)
            {
              htmls +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
            }
            $("#name").html(htmls);
            if(res[0]==0)
            {
              $(".datasuccess").show();
              $(".dataerror").hide();
            }
            else{
              $(".dataerror").show();
              $(".datasuccess").hide();
            }
          }
    });
}

}

 function change_assignes()
  {
    var assigns=$("#assigns").val();
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
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Followers</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#followers").html(states_val);
             
              
           
          }
        });

    
  }  


    
function change_district(){
  var state_id=$("#state_id").val();
  $("#user_id").val('<option value="">Select Customer</option>');
  $('#user_id').selectpicker('refresh');
  var district_id=$("#district_id").val();
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
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Customer</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
           
              }
              $("#user_id").html(states_val);
              $('#user_id').selectpicker('refresh');
              
           
          }
        });

  }

 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  $('#district_id').selectpicker('refresh');
  $('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
              $('#district_id').selectpicker('refresh');
              
           
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
            
          $("#alldate").html(htmls);
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
        var description=$("#description").val();
        
           var state_id=$("#state_id").val();
        var district_id=$("#district_id").val();
        
        var due_date=$("#due_date").val();
        var priority=$("#priority").val();
        var repeat_every=$("#repeat_every").val();
        var start_date=$("#start_date").val();
        
        var infinity_end_date=$("#infinity_end_date").val();
        var unlimited_cycles=$('#unlimited_cycles:checkbox:checked').length;
        
        var cycles=$("#cycles").val();
        var custom_days=$("#custom_days").val();
        
        var infinity=0;
        var contact_person_id=$("#contact_person_id").val();
     

     

        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
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

        if(user_id=="")
        {
          $("#user_id_message").show();
        }
        else{
          $("#user_id_message").hide();
        }

         if(start_time=="")
        {
          $("#start_time_message").show();
        }
        else{
          $("#start_time_message").hide();
        }

          if(description=="")
        {
          $("#description_message").show();
        }
        else{
          $("#description_message").hide();
        }

       if(contact_person_id=="")
        {
          $("#contact_person_id_message").show();
        }
        else{
          $("#contact_person_id_message").hide();
        }

      var followup_action=$("#followup_action").val();
      var status_folowup=0;
        if(start_date!='')
        {
            if(followup_action=="")
            {
              status_folowup=1;
              $("#followup_action_message").show();
            }
            else{
              $("#followup_action_message").hide();
            }
        }
        else{
          status_folowup=0;
        }
        
       
        
        if(status_folowup==0 && district_id!='' && state_id!='' &&  start_time!='' && name!='' &&  assigns!=''  && user_id!=''  && description!=''  && contact_person_id!='' )
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


function change_user_get_contact_person(){
  var user_id=$("#user_id").val();
  if(user_id>0)
  {
    $(".contact_link").show();
  }
  else{
    $(".contact_link").hide();
  }
  
  $(".contact_link").attr("href", "https://biomedicalengineeringcompany.com/staff/customer/"+user_id);
  var url = APP_URL+'/staff/change_user_get_contact_person';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id: user_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">-- Select Contact Person --</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#contact_person_id").html(states_val);
             
              
           
          }
        });

  }



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

  $('#brand_id').multiselect({
      enableFiltering: true,
     // enableCaseInsensitiveFiltering: true,
    });

    $('#category_type_id').multiselect({
      enableFiltering: true,
     // enableCaseInsensitiveFiltering: true,
    });
    $('#product_id').multiselect({
      enableFiltering: true,
    //  enableCaseInsensitiveFiltering: true,
    });


 
    $("#state_id").selectpicker({
      enableFiltering: true,
    });
  /* $("#followers").selectpicker({
      enableFiltering: true,
    });*/
    $("#user_id").selectpicker({
      enableFiltering: true,
    });
    $("#district_id").selectpicker({
      enableFiltering: true,
    });
});
</script>



@endsection
