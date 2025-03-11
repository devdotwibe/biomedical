
@extends('staff/layouts.app')

@section('title', 'Manage Task')

@section('content')

<section class="content-header">
    <h1> Manage Task  </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Task</li>
    </ol>
</section>



<!-- Main content -->
<section class="content  manage-task-wrap">
    <div class="row manage-task">
        <div class="col-md-3  task-col1">
          <div class="box"> 
              <div class="box-body">
                  <div class="row">

                    <div class="col-xs-12 ">
                      <div class="calendar-wrap ">
                        <div id="datetimepicker-task" class="date-calendar" ></div>
                      </div> 
                    </div> 
                    <div class="col-xs-12">
                      <div class="filter-group">
                          <div class="fileter-item m-1">
                              <select name="category" id="category">

                                  @foreach ($categories as $k => $category)
                                      <option value="{{ $category->id }}" 
                                          {{ $k == 0 ? 'selected' : '' }}>
                                          {{ $category->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <input type="hidden" name="staff" id="staff" value="0"> 
                          <input type="hidden" name="staff_id" id="staff_id" value="0"> 
                          <input type="hidden" name="staff_date" id="staff_date" value=""> 
                          <div class="fileter-item m-1">
                              <div class="list-group">
                                <div class="list-group-item staff-filter-list-item"  onclick="selectfilterstaff(0)">All</div>
                                @foreach ($staff as $item)
                                  @if(in_array($item->category_id,$categories_array))
                                  <div class="list-group-item staff-filter-list-item" data-cat="{{$item->category_id}}" onclick="selectfilterstaff({{$item->id}})">{{ucfirst($item->name)}}</div>                                    
                                  @endif
                                @endforeach
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-9 task-col2">
            <div class="box"> 
              <div class="box-body">
                <div class="row">  
                  <div class="date-title">
                    <a title="Previus Date" onclick="dateshiftleft()" class="previus-date"><img src="{{asset('images/arrow-left-circle.svg')}}" alt=""></a><a title="Next Date" onclick="dateshiftright()" class="next-date"><img src="{{asset('images/arrow-right-circle.svg')}}" alt=""></a>
                    <span class="title-dateday strong" id="titledateday"></span>
                    <span class="title-month h5" id="titlemonth"></span>
                    <span class="title-staff h4" id="titlestaff"></span>
                    <span class="title-attendance strong" id="titleattendance"></span>
                    <button class="freezstaff btn" style="display: none" data-freez="" onclick="freezunfreez(this)" >Freez</button>
                  </div>
                    <div class="col-xs-12">
                        <div class="table-geader">
                            <h3>Work Update</h3>
                        </div>
                        <table class="table table-striped " id="task-manage-task">
                            <thead>
                                <tr> 
                                    <th>No</th>
                                    <th>Task</th>
                                    <th>Client</th>
                                    <th>Assignees</th>
                                    <th>Followers</th>
                                    <th>Start Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>


                    <div class="col-xs-12">
                      <div class="table-geader">
                          <h3>Oppertunity Update</h3>
                      </div>
                      <table class="table table-striped " id="user_task_update">
                          <thead>
                              <tr> 
                                <th>No</th>
                                <th>Task</th>
                                <th>Client</th>
                                <th>Assignees</th>
                                <th>Followers</th>   
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                              </tr>
                          </thead>
                          <tbody>
                              
                          </tbody>
                      </table>
                  </div>

                    <div class="col-xs-12">
                        <div class="table-geader">
                            <h3>Travel</h3>
                        </div>
                        <table class="table table-striped " id="task-manage-travel">
                            <thead>
                                <tr> 
                                    <th>No</th> 
                                    <th>Travel Type	</th>
                                    <th>Start Reading	</th>
                                    <th>End Reading	</th>
                                    <th>kilometers</th>
                                    <th>Amount</th> 
                                    <th>Start Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Task</th>
                                    <th>Start Image</th>
                                    <th>End Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12">
                      <div class="table-geader">
                          <h3>Office Work</h3>
                      </div>
                      <table class="table table-striped " id="task-manage-office">
                          <thead>
                              <tr> 
                                  <th>No</th> 
                                  <th>Start Date</th>
                                  <th>Start Time	</th>
                                  <th>Task</th>
                                  <th>End Time</th> 
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              
                          </tbody>
                      </table>
                  </div>
                    <div class="col-xs-12">
                      <div class="table-geader">
                          <h3>Other Expence</h3>
                      </div>
                      <table class="table table-striped " id="task-manage-expence">
                          <thead>
                              <tr> 
                                  <th>No</th> 
                                  <th>Expence Type	</th> 
                                  <th>Amount</th> 
                                  <th>Description</th>
                                  <th>Task</th> 
                                  <th>Date</th>
                                  {{-- <th>Status</th> --}}
                                  <th>Image</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              
                          </tbody>
                      </table>
                  </div>

                  <div class="col-xs-12">
                    <div class="table-geader">
                        <h3>Attendance</h3>
                    </div>
                    <table class="table table-striped " id="task-manage-attendance">
                        <thead>
                            <tr> 
                                <th>No</th> 
                                <th>Attendance</th> 
                                <th>Description</th>
                                <th>Start Date</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>





                </div>
              </div>
            </div>
        </div>
    </div>
</section>




@endsection


@section('scripts') 


<div class="modal fade" id="attachement_preview_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Attachement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 

        <div class="modal-body">
        
          <div id="attachement_preview_modal_area">
             
          </div>
        </div>
        <div class="modal-footer">
         
        </div> 
    </div>
  </div>
</div>

  
  
<div class="modal fade" id="expence_status_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Task Expence</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('staff.manage-task.expence.update')}}" method="post" id="expence_status_update_modal_form">
        @csrf
        <input type="hidden" name="expence" id="expence_status_update_id" >

        <div class="modal-body">
        
          <div id="modal-expence-detail">
             
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-primary"  >Update Status</button> 
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="location_status_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Staff Task Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
        <div class="modal-body">
        
          <div id="modal-location-task">
             
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
        </div> 
    </div>
  </div>
</div>
 
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
        <div class="col-md-8 task-single-col-left"  >
                           <div class="clearfix"></div>
                
       
           <h4 class="th font-medium mbot15 pull-left">Description</h4>
         
                    <div class="clearfix"></div>
           <div class="tc-content"><div id="task_view_description"></div></div>         <div class="clearfix"></div>
         
          
          
           <div class="clearfix"></div>
         
               
              <h4 class="mbot20 font-medium">Comments</h4>
            <div class="row pgrs-outer">
           <div class="col-md-12 tasks-comments inline-block full-width simple-editor">
    
    <form name="contactformedit" id="contactformedit" method="post" action="" >
      <div id="contactformcontent">
  
      <input type="hidden" name="task_id" id="task_id">  
  
              
  <div><label class="form-check-label">Email 
  <input type="checkbox" name="email_status" id="email_status" class="form-check-input" value="Y">    </label> </div>
  <div><label class="form-check-label">Call 
  <input type="checkbox" name="call_status" id="call_status" class="form-check-input">    </label> </div>
  <div><label class="form-check-label">Visit 
  <input type="checkbox" name="visit_status" id="visit_status" class="form-check-input">    </label> </div>
  <div class="addcon_link">  <a id="contact_link" href="" target='_blank'>Add contact</a>  </div>   
  <select name="contact_id" id="contact_id" class="form-control" multiple="multiple">
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
  
      </div>
            
              </form>
                
  
              <div class="res_ajax scrollbar thin-scroll">
  
          
  
              
  
              </div>
            
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
                  </span> <br><br>
                  <span class="task-user" id="follower_dis_admin_assign" data-toggle="tooltip" data-title="Frederik Rohan">
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
            <input type="hidden" name="task_comment_id" id="task_comment_id"> 
            <input type="hidden" name="parent_id" id="parent_id"> 
            
            <div class="form-group">
                      <label >Status</label>
                    <div class="radio">
                      <label>
                        <input type="radio" name="status_replay" id="status_replay1" value="Y" >
                        Approved
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="status_replay" id="status_replay1" value="N" checked>
                        Reject
                      </label>
                    </div>
  
                  </div>
  
  
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="add_replay_comment()">Update Task</button>
        </div>
      </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="staff_date_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Task Staffs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="list-group" id="stafflist">
             
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
        </div>
      </div>
    </div>
  </div>
  
  
<!-- --------------------------------------------Comment Replay Modal------------------------------------------------ -->
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
              <div id="edit_service_problem">

              </div>
              <input type="hidden" name="task_comment_id" id="task_comment_id">
                <input type="hidden" name="parent_id" id="parent_id"> 
                    <input type="hidden" name="follower" id="follower" value="">
                      <input type="hidden" name="follower1" id="follower1" value="">
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="mdm-btn submit-btn" onclick="add_replay_comment()">Update Task</button>
                    </div>
                  </div>
                </div>
              </div>   
<!-------------------------Comment Replay Modal------------------------------------------------ --> 

<!-----------------------------Modal Add parts------------------------------->

<div class="modal fade" id="add-parts-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel"></h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">

<span><b>Part Name : </b><input type="text" class="form-control" id="add_part_name" name="product" value=""></span><br>
<input type="hidden" id="service_id_part" name="service_id_part" value="" >
<a id="addPart-submit" class="btn btn-primary">Submit</a>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<!-- ----------------------------Modal Add parts End------------------------------------- -->

<div class="modal fade" id="replay_modal_expence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" >Edit Expence</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
        <div class="form-group">
          <label for="meter_start">Other Expence </label>
          <select class="form-control" name="other_expence_edit" id="other_expence_edit" attr-id='0' >
          <option value="">Select Other Expence</option>
          <option value="Courier">Courier</option>
          <option value="Print Out">Print Out</option>
          <option value="Other">Other (with prior approval)</option>
          </select>
         <span class="error_message" id="other_expence_edit_message" style="display: none">Field is required</span>
        </div>
        <div class="form-group">
          <label for="meter_start">Amount </label>
          <input class="form-check-input form-control" type="number" name="expence_amount_edit" id="expence_amount_edit" value="" placeholder="Amount">
          <span class="error_message" id="expence_amount_edit_message" style="display: none">Field is required</span>
        </div>
        <div class="form-group" >
          <label for="meter_start">Description </label>
          <textarea class="form-check-input form-control" name="expence_desc_edit" id="expence_desc_edit" placeholder="Description"></textarea>
          <span class="error_message" id="expence_desc_edit_message" style="display: none">Field is required</span>
        </div> 
        <input type="hidden" name="expence_id" id="expence_id"> 
        <input type="hidden" name="type_expence" id="type_expence">
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="update_edit_expence()">Update Expence</button>
    </div>
  </div>
 </div>
</div> 
    
 <div class="modal fade" id="replay_modal_travel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" >Edit Travel</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
        <div class="form-group">
        <label for="meter_start">Travel Type </label>
        <select class="form-control" name="travel_type" id="travel_type" attr-id='0' onchange="change_travel(this.value)">
        <option value="">Select Travel Type</option>
        <option value="Bike">Bike</option>
        <option value="Car">Car (with prior approval)</option>
        <option value="Train">Train</option>
        <option value="Bus">Bus</option>
        <option value="Auto">Auto</option>
        </select>
        <span class="error_message" id="travel_type_message" style="display: none">Field is required</span>
        </div> 
        <div class="form-group bikesec" style="display:none">
        <label for="meter_start">Start Meter Reading </label>
        <input class="form-check-input form-control" type="number" name="start_meter_reading" id="start_meter_reading" value="" placeholder="Start Meter Reading ">
        <span class="error_message" id="start_meter_reading_message" style="display: none">Field is required</span>
        </div> 
        <div class="form-group bikesec" style="display:none">
        <label for="meter_start">End Meter Reading </label>
        <input class="form-check-input form-control" type="number" name="end_meter_reading" id="end_meter_reading" value="" placeholder="End Meter Reading ">
        <span class="error_message" id="end_meter_reading_message" style="display: none">Field is required</span>
        </div> 
        <div class="form-group notbike" style="display:none">
        <label for="meter_start">Amount </label>
        <input class="form-check-input form-control" type="number" name="travel_amount" id="travel_amount" value="" placeholder="Amount">
        <span class="error_message" id="travel_amount_message" style="display: none">Field is required</span>
        </div>
        <input type="hidden" name="travel_id" id="travel_id"> 
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="update_edit_travel()">Update Expence</button>
    </div>
  </div>
 </div>
</div> 

<div class="modal fade" id="leave_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" >Edit Attendence: <span id="date_att"></span></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        <div class="form-group">
          <label for="meter_start">Leave Type </label>
          <select class="form-control" name="leave_type" id="leave_type">
                <option value="">Leave Type</option>
                <option value="Leave">Leave</option>
                <option value="Attendence">Attendence</option>
              </select>
              
          <span class="error_message" id="leave_type_message" style="display: none">Field is required</span>
        </div> 

        <div class="form-group">
          <label for="meter_start">Leave </label>
          <select class="form-control" name="leave" id="leave">
          <option value="">Leave</option>
          <option value="Half Day">Half Day</option>
                <option value="Full Day">Full Day</option>
              </select>
              
          <span class="error_message" id="leave_message" style="display: none">Field is required</span>
        </div> 
        
        <input type="hidden" name="leave_date" id="leave_date"> 
        <input type="hidden" name="type_staff" id="type_staff"> 
 
      </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="update_attendence()">Update Attendence</button>
    </div>
  </div>
  </div>
</div> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"  />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" ></script>
 

    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js' ></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js'></script> 

    <script async defer>
        
        var img_pdf=[];
        $(function(){
          localStorage.clear();
        })
        var admin_list=<?=json_encode($admins)?>;
        var staff_list=<?=json_encode($staff)?>;
        var enentsurlid=1;
        var monthNameList=[
                              {
                                "abbreviation": "Jan",
                                "name": "January"
                              },
                              {
                                "abbreviation": "Feb",
                                "name": "February"
                              },
                              {
                                "abbreviation": "Mar",
                                "name": "March"
                              },
                              {
                                "abbreviation": "Apr",
                                "name": "April"
                              },
                              {
                                "abbreviation": "May",
                                "name": "May"
                              },
                              {
                                "abbreviation": "Jun",
                                "name": "June"
                              },
                              {
                                "abbreviation": "Jul",
                                "name": "July"
                              },
                              {
                                "abbreviation": "Aug",
                                "name": "August"
                              },
                              {
                                "abbreviation": "Sep",
                                "name": "September"
                              },
                              {
                                "abbreviation": "Oct",
                                "name": "October"
                              },
                              {
                                "abbreviation": "Nov",
                                "name": "November"
                              },
                              {
                                "abbreviation": "Dec",
                                "name": "December"
                              }
                          ]
        var calendar = new FullCalendar.Calendar($("#datetimepicker-task").get(0), {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: '',
                center: 'title',
                right: 'prev,next'
            },
            weekends: true,
            weekNumbers: false,
            editable: true,
            navLinks: false,
            dayMaxEvents: true,
            displayEventTime: false,
            unselectAuto:false,
            selectable: true, 
            eventClick: function(info) { 
                try {                    
                    if(info.event){ 
                      showevent(info.event.start,info.event.extendedProps.etype,info.event.extendedProps.estatus,info.event.extendedProps.ecategory); 
                      console.log(info.event)
                    }
                } catch (error) {
                  console.log("event-error",error)
                }
            },
             
            // dateClick:function(info){
                // selectedDates=[`${info.date.getFullYear()}-${info.date.getMonth()+1}-${info.date.getDate()}`];
            // },
            // select:function(info){ 
            // },
            // selectAllow:function(info){ 
            // },
            // events: [    {
            //     start: '2023-10-18 12:00:00',
            //     // end: '2023-10-18 14:00:00',
            //     rendering: 'background',
            //     backgroundColor: '#f00f00',
            //     title: '',
            //     textColor: '#000',
            //     className: 'event-full',
            //     allDay:false,
            // },
            //             {
            //     start: '2023-10-19 12:00:00',
            //     // end: '2023-10-19 14:00:00',
            //     allDay:false,
            //     rendering: 'background',
            //     backgroundColor: '#f00f00',
            //     title: '',
            //     textColor: '#000',
            //     className: 'event-full', 
            // }
            // ],
            // eventAfterRender: function(event, element, view) {
            //     // element.append('FULL');
            // }, 
            // datesSet: function(view, element) { 
            //     console.log(view)
            // }
 
            eventSources: [ 
                {
                    id:`event-sorce-${enentsurlid}`,
                    url: "{{route('staff.manage-task.index')}}",
                    method: 'GET',
                    extraParams: {
                        ajax: 'yes',
                        staff_id: $('#staff').val()||0,
                        category: $('#category').val()||'',
                    },  
                } 

            ]

        });
 
        calendar.render();
        const nthNumber = (number) => {
          return number > 0
            ? ["th", "st", "nd", "rd"][
                (number > 3 && number < 21) || number % 10 > 3 ? 0 : number % 10
              ]
            : "";
        };

        function showevent(dte,etype="",estatus=false,ecategory=""){
            var showdte=`${dte.getFullYear()}-${(dte.getMonth()+1).toString().padStart(2, '0')}-${dte.getDate().toString().padStart(2, '0')}`;
              if(etype=="track"){
                window.open(`{{route('staff.manage-staff-task-location_staff')}}?date=${showdte}&category=${ecategory}`, '_blank');
              }else{
                
              $.get("{{route('staff.manage-task.show','work-update-staffs')}}",{
                  date:showdte,
                  etype:etype,
                  estatus:estatus?'y':"n", 
                  ecategory:ecategory
              },function(res){
                  $('#stafflist').html('')
                  $.each(res,function(k,v){
                    var gclick='';
                    var gstatus='';
                    // if(etype!="attendance"&&etype!="leave"){
                      gclick=`onclick="selectstaff(${v.id},'${showdte}');scrolltable();"`;
                    // }
                    if(etype=="task"&&v.task_count_approve>0){
                      gstatus+=` <small class="text-success"> Approved [${v.task_count_approve}] </small>`
                    }
                    if(etype=="task"&&v.task_count_nocomment>0){
                      gstatus+=` <small class="text-warning"> Not-Commented [${v.task_count_nocomment}] </small>`
                    }
                    if(etype=="task"&&v.task_count>0){
                      gstatus+=` <small class="text-danger"> Pending [${v.task_count}] </small>`
                    }

                    if($('#staff').val()>0){
                      if($('#staff').val()==v.id){

                      $('#stafflist').append(`
                          <div class="list-group-item" ${gclick} >${v.name} ${gstatus}</div>
                      `)
                      }
                    }else{

                      $('#stafflist').append(`
                          <div class="list-group-item ${status}"  ${gclick} >${v.name} ${gstatus}</div>
                      `)
                    }
                  })
                  if(res.length>0){
                    if($('#staff').val()>0){
                      // if(etype!="attendance"&&etype!="leave"){
                        selectstaff($('#staff').val(),showdte)
                        scrolltable();
                      // }
                    }else{
                      $('#staff_date_modal').modal('show')
                    }
                  }
              },'json')

            }
          
        }
        function scrolltable(){
          $('html, body').animate({
              scrollTop: $("#task-manage-task").offset().top-100
          }, 800);
        }
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        function selectstaff(staffId,date=''){
          $('.freezstaff').hide()
            $('#staff_id').val(staffId)
            $('#staff_date').val(date)
            $('#task-manage-task').DataTable().ajax.reload()
            $('#user_task_update').DataTable().ajax.reload()
            $('#task-manage-travel').DataTable().ajax.reload()
            $('#task-manage-office').DataTable().ajax.reload()
            $('#task-manage-expence').DataTable().ajax.reload()
            $('#task-manage-attendance').DataTable().ajax.reload()
            $('#staff_date_modal').modal('hide') 

            $('#titleattendance').html('')
            if(staffId>0){
              var stf=$.map(staff_list,function(v,k){
                if(v.id==staffId)
                {
                  return v;
                }                            
              })
              $.each(stf,function(ik,iv){
                $('#titlestaff').text(capitalizeFirstLetter(iv.name))
              })
            }else{
              $('#titlestaff').text('')
            }

            if(date==""){
              
              var tstart=calendar.view.activeStart; 
              var tend=calendar.view.activeEnd;
              var month=tstart.getMonth();
              var year=tstart.getFullYear();
              if(tstart.getMonth()!=tend.getMonth()){
                if((tend.getMonth()-tstart.getMonth())>1||(tend.getMonth()-tstart.getMonth())<-1){
                  month=month+1
                }else if(tstart.getDate()<15){
                  month=tstart.getMonth();
                }else if(tend.getDate()>15){
                  month=tend.getMonth();
                } 
              }
              month=month%12; 
              $('#titlemonth').text(monthNameList[month].name+" "+year )
              $('#titledateday').text('')
            }else{
              var tdc=new Date(date);
              $('#titledateday').text(tdc.toLocaleString('default', { weekday: 'long' })) 
              $('#titlemonth').text(tdc.getDate()+nthNumber(tdc.getDate())+" " +tdc.toLocaleString('default', { month: 'long' })+' '+tdc.getFullYear()) 
              if(staffId>0){
                $.get("{{route('staff.manage-task.show','work-update-staff-detail')}}",{
                  staff_id:staffId,
                  freezdate:date
                },function(rstf){
                  console.log(rstf.freez)
                  $('.freezstaff').removeClass('btn-danger').addClass('btn-info').text('Freeze').data('freez','N').show()
                  if(rstf.freez){ 
                    if(rstf.freez.status=="Y"){
                      $('.freezstaff').addClass('btn-danger').removeClass('btn-info').text('Freezed').data('freez','Y').show()
                    }
                  }
                  if(rstf.attendance){
                    if(rstf.attendance.system_generate_leave=="N"){
                      var pret="Attendance ";
                      if(rstf.attendance.type_leave=="Request Leave"){
                        pret=   "Leave "; 
                      }
                      $('#titleattendance').html(`
                      ${pret} ${rstf.attendance.attendance}
                      `)
                    }else{
                      $('#titleattendance').html(`
                      Attendance not added System Generated Attendance <a class="text-danger" onclick="unlock_attendence('${rstf.attendance.start_date}','office','${rstf.attendance.attendance}','${rstf.attendance.type_leave}')" >Unlock Attendance</a>
                      `)
                    }
                  }
                },'json')
              } 
            }


        }
        function freezunfreez(e){
            var sts=$(e).data('freez')||'N';
            if(sts=="Y"){
              sts="N";
              $(e).removeClass('btn-danger').addClass('btn-info').text('Freeze').data('freez','N')
            }else{
              sts="Y";
              $(e).addClass('btn-danger').removeClass('btn-info').text('Freezed').data('freez','Y')
            }
            $.post("{{route('staff.manage-task.staff.freez')}}",{
              staff_id:$('#staff_id').val(),
              freezdate:$('#staff_date').val(),
              freezstatus:sts
            },function(d){

            })
        }
        function dateshiftleft(){
          if( $('#staff_date').val()!=""){
            var dte=new Date($('#staff_date').val());
            dte.setDate(dte.getDate()-1);
            var showdte=`${dte.getFullYear()}-${dte.getMonth()+1}-${dte.getDate()}`;
            $('#staff_date').val(showdte)
            calendar.gotoDate( dte );
            selectstaff($('#staff_id').val(),$('#staff_date').val())
            // $('#task-manage-task').DataTable().ajax.reload()
            // $('#task-manage-travel').DataTable().ajax.reload()
            // $('#task-manage-expence').DataTable().ajax.reload()
            // $('#task-manage-attendance').DataTable().ajax.reload()
            // $('#titlemonth').text(dte.getDate()+nthNumber(dte.getDate())+" " +dte.toLocaleString('default', { month: 'long' })+' '+dte.getFullYear()) 
            // $('#titledateday').text(dte.toLocaleString('default', { weekday: 'long' }))  
          }
        }
        function dateshiftright(){
          var ndate=new Date();
          var dte=new Date($('#staff_date').val());
            dte.setDate(dte.getDate()+1);
          if( $('#staff_date').val()!=""&&ndate.getTime()>=dte.getTime()){
            var showdte=`${dte.getFullYear()}-${dte.getMonth()+1}-${dte.getDate()}`;
            $('#staff_date').val(showdte)
            calendar.gotoDate( dte );
            selectstaff($('#staff_id').val(),$('#staff_date').val())
            // $('#task-manage-task').DataTable().ajax.reload()
            // $('#task-manage-travel').DataTable().ajax.reload()
            // $('#task-manage-expence').DataTable().ajax.reload()
            // $('#task-manage-attendance').DataTable().ajax.reload()
            // $('#titlemonth').text(dte.getDate()+nthNumber(dte.getDate())+" " +dte.toLocaleString('default', { month: 'long' })+' '+dte.getFullYear()) 
            // $('#titledateday').text(dte.toLocaleString('default', { weekday: 'long' }))  
          }
        }
        function filterchange(){
            var eventSource=calendar.getEventSourceById(`event-sorce-${enentsurlid}`);
            if(eventSource){
                eventSource.remove(); 
            }
            enentsurlid++;
            calendar.addEventSource({
                id:`event-sorce-${enentsurlid}`,
                url: "{{route('staff.manage-task.index')}}",
                method: 'GET',
                extraParams: {
                    ajax: 'yes',
                    staff_id: $('#staff').val()||0,
                    category: $('#category').val()||'',
                },  
            });
            selectstaff($('#staff').val()||0)
        }
        function selectfilterstaff(v=0){
            $('#staff').val(v);
            console.log($('#staff').val(v));
            filterchange();
        }
        function updatedexpenceStatus(link){
          $.get(link,function(v){
            if(v){
              $('#expence_status_update_id').val(v.id)
              $('#modal-expence-detail').html(`
              
                <div class="task-expence">
                    <div class="row">
                        <div class="col-md-4">
                            <span>Expence Type</span>
                        </div>
                        <div class="col-md-8">
                            <span>:${v.travel_type}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <span>Amount</span>
                        </div>
                        <div class="col-md-8">
                            <span>:${v.travel_start_amount}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <span>Description</span>
                        </div>
                        <div class="col-md-8">
                            <span>:${v.expence_desc}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <span>Task</span>
                        </div>
                        <div class="col-md-8">
                            <span>:${v.task_name}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <span>Status</span>
                        </div>
                        <div class="col-md-8">  
                            <div class="form-group">
                                <div class="form-check">
                                    <label for="expence-status-approved">Approve</label>
                                    <input type="radio" name="status" id="expence-status-approved" value="Y" ${v.status=="Y"?"checked":""}>
                                </div>
                                <div class="form-check">
                                    <label for="expence-status-reject">Reject</label>
                                    <input type="radio" name="status" id="expence-status-reject" value="Reject" ${v.status=="Reject"?"checked":""}>
                                </div>
                                <div class="form-check">
                                    <label for="expence-status-pending">Pending</label>
                                    <input type="radio" name="status" id="expence-status-pending" value="N" ${v.status=="N"?"checked":""}>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
              `)
              $('#expence_status_update_modal').modal('show')
            }
          },'json')
        }

        function edit_expence(id,exp_type,amount,expence_desc)
        {
          $("#other_expence_edit").val(exp_type);
          $("#expence_amount_edit").val(amount);
          $("#expence_desc_edit").val(expence_desc);
          $("#expence_id").val(id);
          $("#replay_modal_expence").modal("show");
        } 


        function update_edit_expence(){ 
          var expence_id=$("#expence_id").val();
          var other_expence_edit=$("#other_expence_edit").val();
          var expence_amount_edit=$("#expence_amount_edit").val();
          var expence_desc_edit=$("#expence_desc_edit").val();
          var type_expence=$("#type_expence").val();
          //alert(expence_type)
          var error_flag=0;
          var url = APP_URL+'/staff/save_expence_edit_details_staff';
          $.ajax({
              type: "POST",
              cache: false,
              url: url,
              data:{
                expence_id:expence_id,other_expence_edit:other_expence_edit,expence_amount_edit:expence_amount_edit,expence_desc_edit:expence_desc_edit
              },
              success: function (data)
              {                  
                $('#task-manage-task').DataTable().ajax.reload()
                $('#user_task_update').DataTable().ajax.reload()
                $('#task-manage-travel').DataTable().ajax.reload()
                $('#task-manage-office').DataTable().ajax.reload()
                $('#task-manage-expence').DataTable().ajax.reload()
                $('#task-manage-attendance').DataTable().ajax.reload()
                $("#replay_modal_expence").modal("hide");
              }
          });
        }

      function edit_travel(id,travel_type,start_meter_reading,end_meter_reading,travel_end_amount)
      {
          if(travel_type=="Car" || travel_type=="Bike")
          {
          $(".bikesec").show();
          $(".notbike").hide();
          }
          else{
          $(".notbike").show();
          $(".bikesec").hide();
          } 
          $("#travel_type").val(travel_type);
          $("#start_meter_reading").val(start_meter_reading);
          $("#end_meter_reading").val(end_meter_reading);
          $("#travel_amount").val(travel_end_amount);
          $("#travel_id").val(id);
          $("#replay_modal_travel").modal("show"); 
      } 

        function update_edit_travel(){ 
        var travel_id=$("#travel_id").val();
        var travel_type=$("#travel_type").val();
        var start_meter_reading=$("#start_meter_reading").val();
        var end_meter_reading=$("#end_meter_reading").val();
        var travel_amount=$("#travel_amount").val();
        //alert(expence_type)
        var error_flag=0;
        var url = APP_URL+'/staff/save_travel_edit_details_staff';
        $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data:{
          
          req_type:'travel',travel_id:travel_id,travel_type:travel_type,start_meter_reading:start_meter_reading,end_meter_reading:end_meter_reading,end_meter_reading:end_meter_reading,travel_amount:travel_amount
        },
        success: function (data)
        {  
            $('#task-manage-task').DataTable().ajax.reload()
            $('#user_task_update').DataTable().ajax.reload()
            
            $('#task-manage-travel').DataTable().ajax.reload()
            $('#task-manage-office').DataTable().ajax.reload()
            $('#task-manage-expence').DataTable().ajax.reload()
            $('#task-manage-attendance').DataTable().ajax.reload()
          $("#replay_modal_travel").modal("hide"); 

        }
        });
        } 

        function change_travel(travel_type)
{
if(travel_type=="Bike" || travel_type=="Car")
{
 $(".bikesec").show(); 
 $(".notbike").hide(); 
 }
else{
 $(".bikesec").hide(); 
 $(".notbike").show();
} 
 } 

 function viewattendence_popup(leave_date,type_staff,cur_att,cur_leavetype)
 {
  $("#leave").val('');
  $("#leave_date").val('');

   $("#leave_date").val(leave_date);
   $("#type_staff").val(type_staff);
   
   $("#date_att").html(leave_date);
  if(cur_att=="Half Day"){
    $("#leave").val('');
    $('#leave').val('Half Day');
    //$('#leave option[value="Half Day"]').attr("selected", "selected");
  }
  else{
    $("#leave").val('');
    $('#leave').val('Full Day');
   // $('#leave option[value="Full Day"]').attr("selected", "selected");
  }
  if(cur_leavetype=="Request Leave"){
    $('#leave_type').val('Leave');
   // $('#leave_type option[value="Leave"]').attr("selected", "selected");
  }
  else{
    $('#leave_type').val('Attendence');
   // $('#leave_type option[value="Attendence"]').attr("selected", "selected");
  }

   
   $("#leave_add").modal("show");
 }
 
        function delete_travel(id,travel_type,start_meter_reading,end_meter_reading,travel_end_amount)
        {
          var result = confirm("Want to delete?");
        if (result) {
          var url = APP_URL+'/staff/delete_travel_staff'; 
        $.ajax({ 
          type: "POST", 
          cache: false, 
          url: url, 
          data:{ 
            id:id
          }, 
          success: function (data) 
          { 


                  $('#task-manage-task').DataTable().ajax.reload()
                  $('#user_task_update').DataTable().ajax.reload()
                  
                  $('#task-manage-travel').DataTable().ajax.reload()
                  $('#task-manage-office').DataTable().ajax.reload()
                  $('#task-manage-expence').DataTable().ajax.reload()
                  $('#task-manage-attendance').DataTable().ajax.reload()
          }
        });
        }
        }
      function delete_expence(id,exp_type,amount,expence_desc)
      {
        var result = confirm("Want to delete?");
      if (result) {
        var url = APP_URL+'/staff/save_travel_edit_details_staff'; 
      $.ajax({ 
        type: "POST", 
        cache: false, 
        url: url, 
        data:{ 
          id:id,req_type:'leavetravel',
        }, 
        success: function (data) 
        { 

                $('#task-manage-task').DataTable().ajax.reload()
                $('#user_task_update').DataTable().ajax.reload()
                
                $('#task-manage-travel').DataTable().ajax.reload()
                $('#task-manage-office').DataTable().ajax.reload()
                $('#task-manage-expence').DataTable().ajax.reload()
                $('#task-manage-attendance').DataTable().ajax.reload()
        }
      });
      }
      }


      function unlock_attendence(leave_date,type_staff,cur_att,cur_leavetype)
 {
   var staff_id=$("#staff_id").val();

   var result = confirm("Want to unlock  attendance?");
if (result) {
   
  var url = APP_URL+'/staff/delete_admin_generate_leave_staff';
    $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      staff_id:staff_id,leave_date:leave_date,cur_leavetype:cur_leavetype
    },
    success: function (data)
    { 
        alert("Attendance Unlocked"); 
                $('#task-manage-task').DataTable().ajax.reload()
                $('#user_task_update').DataTable().ajax.reload()
                $('#task-manage-travel').DataTable().ajax.reload()
                $('#task-manage-office').DataTable().ajax.reload()
                $('#task-manage-expence').DataTable().ajax.reload()
                $('#task-manage-attendance').DataTable().ajax.reload()
    }

    });
}


 }

function update_attendence(){ 
 var leave_type=$("#leave_type").val();
 var leave=$("#leave").val();
 var leave_date=$("#leave_date").val();
 var type_staff=$("#type_staff").val();
 
 if(leave_type!='')
 {
  $("#leave_type_message").hide();
 }
 else{
  $("#leave_type_message").show();
 }
 if(leave!='')
 {
  $("#leave_message").hide();
 }
 else{
  $("#leave_message").show();
 }

 if(leave!='' && leave_type!='')
 {

var error_flag=0;
var staff_id=$("#staff_id").val()
var url = APP_URL+'/staff/save_travel_edit_details_staff';
$.ajax({
 type: "POST",
 cache: false,
 url: url,
 data:{
  req_type:'attendence',staff_id:staff_id,leave_type:leave_type,leave:leave,leave_date:leave_date,type_staff:type_staff
 },
 success: function (data)
 {  
  
                $('#task-manage-task').DataTable().ajax.reload()
                $('#user_task_update').DataTable().ajax.reload()
                $('#task-manage-travel').DataTable().ajax.reload()
                $('#task-manage-office').DataTable().ajax.reload()
                $('#task-manage-expence').DataTable().ajax.reload()
                $('#task-manage-attendance').DataTable().ajax.reload()
   $("#leave_add").modal("hide");
 }
});

}


}
        $(function(){
            $('#task-manage-task').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                // processing:true,
                // serverSide:true,
                // order: [[0, 'desc']],
                ajax:{
                    url:"{{route('staff.manage-task.show','work-update')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },

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
                columns:[
                    // {
                    //     data:'updated_at',
                    //     name:'updated_at',
                    //     orderable: true,
                    //     searchable: false,
                    //     visible:false
                    // },
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    },
                    {
                        data:'taskname',
                        name:'name',
                        orderable: true,
                        searchable: false,
                    },

                    {
                        data:'client',
                        name:'client',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data:'assignees',
                        name:'assignees',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data:'followers',
                        name:'followers',
                        orderable: false,
                        searchable: false,
                    }, 
                    {
                        data:'start',
                        name:'start_date',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'due',
                        name:'due_date',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'tstatus',
                        name:'tstatus',
                        orderable: false,
                        searchable: false,
                    },
                ]
            })


            $('#user_task_update').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                // processing:true,
                // serverSide:true,
                // order: [[0, 'desc']],
                ajax:{
                    url:"{{route('staff.manage-task.show','staff_update')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },
                columns:[
                    // {
                    //     data:'updated_at',
                    //     name:'updated_at',
                    //     orderable: true,
                    //     searchable: false,
                    //     visible:false
                    // },
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    },
                    {
                        data:'taskname',
                        name:'name',
                        orderable: true,
                        searchable: false,
                    },

                    {
                        data:'client',
                        name:'client',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data:'assignees',
                        name:'assignees',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data:'followers',
                        name:'followers',
                        orderable: false,
                        searchable: false,
                    }, 

                    {
                        data:'description',
                        name:'description',
                        orderable: false,
                        searchable: false,
                    }, 
                 
                    {
                        data:'date_created',
                        name:'created_at',
                        orderable: true,
                        searchable: false,
                    },
                    
                    {
                        data:'tstatus',
                        name:'tstatus',
                        orderable: false,
                        searchable: false,
                    },
                ]
            })


            $('#task-manage-travel').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                ajax:{
                    url:"{{route('staff.manage-task.show','work-update-travel')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },
                columns:[
                    // {
                    //     data:'updated_at',
                    //     name:'updated_at',
                    //     orderable: true,
                    //     searchable: false,
                    //     visible:false
                    // },
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    }, 
                    {
                        data:'travel_type',
                        name:'travel_type',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'startreading',
                        name:'start_meter_reading',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'endreading',
                        name:'end_meter_reading',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'travelkm', 
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data:'travelamount', 
                        orderable: false,
                        searchable: false,
                    }, 
                    {
                        data:'start_date',
                        name:'start_date',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'start_time_travel',
                        name:'start_time_travel',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'end_time_travel',
                        name:'end_time_travel',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'taskname',
                        name:'task_name',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'travelstartimage',
                        name:'travel_start_image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data:'travelendimage',
                        name:'travel_end_image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data:'action', 
                        orderable: false,
                        searchable: false,
                    },
                ]
            })
            $('#task-manage-office').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                ajax:{
                    url:"{{route('staff.manage-task.show','work-update-office')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },
                columns:[ 
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    }, 
                    {
                        data:'start_date',
                        name:'start_date',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'start_time',
                        name:'start_time',
                        orderable: true,
                        searchable: false,
                    }, 
                    {
                        data:'taskname',
                        name:'task_name',
                        orderable: true,
                        searchable: false,
                    }, 
                    {
                        data:'end_time',
                        name:'end_time',
                        orderable: true,
                        searchable: false,
                    }, 
                    {
                        data:'action', 
                        orderable: false,
                        searchable: false,
                    },
                ]
            })
            $('#task-manage-expence').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                ajax:{
                    url:"{{route('staff.manage-task.show','work-update-expence')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },
                columns:[
                    // {
                    //     data:'updated_at',
                    //     name:'updated_at',
                    //     orderable: true,
                    //     searchable: false,
                    //     visible:false
                    // },
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    }, 
                    {
                        data:'travel_type',
                        name:'travel_type',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'travel_start_amount',
                        name:'travel_start_amount',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'expence_desc',
                        name:'expence_desc',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'taskname',
                        name:'task_name',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data:'start_date',
                        name:'start_date',
                        orderable: true,
                        searchable: false,
                    },
                    // {
                    //     data:'status',
                    //     name:'status', 
                    //     searchable: false,
                    // },
                    {
                        data:'travelstartimage',
                        name:'travel_start_image',
                        orderable: false,
                        searchable: false,
                    }, 
                    {
                        data:'action',
                        name:'action', 
                        searchable: false,
                    },
                ]
            })
            $('#task-manage-attendance').DataTable({
                bFilter: false,
                bLengthChange: false,
                // paging: false,
                bAutoWidth: false,
                ajax:{
                    url:"{{route('staff.manage-task.show','work-update-attendance')}}",
                    data: function (d) {
                        if($('#staff_date').val()==''){
                            var tstart=calendar.view.activeStart;
                            d.start = `${tstart.getFullYear()}-${tstart.getMonth()+1}-${tstart.getDate()}`;
                            var tend=calendar.view.activeEnd;
                            d.end =  `${tend.getFullYear()}-${tend.getMonth()+1}-${tend.getDate()}`;
                        }else{
                            d.start=$('#staff_date').val();
                            d.end=$('#staff_date').val();
                        }
                        d.staff =$('#staff_id').val()||0;
                    }
                },
                columns:[
                    // {
                    //     data:'updated_at',
                    //     name:'updated_at',
                    //     orderable: true,
                    //     searchable: false,
                    //     visible:false
                    // },
                    {
                        data:'DT_RowIndex',
                        name:'id',
                        orderable: true,
                        searchable: false,
                        visible:false
                    }, 
                    {
                        data:'marked', 
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data:'reason_leave', 
                        orderable: false,
                        searchable: false,
                    }, 
                    {
                        data:'start_date',
                        name:'start_date',
                        orderable: true,
                        searchable: false,
                    }, 
                    {
                        data:'action',
                        name:'action', 
                        searchable: false,
                    },
                ]
            })
            $('#category').change(function(){
              if($(this).val()==""){
                $('.staff-filter-list-item').show()
              }else{
                $('.staff-filter-list-item').hide()
                $(`.staff-filter-list-item[data-cat="${$(this).val()}"]`).show()
              }
              selectfilterstaff(0)
            })
            $('#category').change()
            // $('#staff').select2({ 
            //     ajax: {
            //         url: '{{route('staff.manage-task.index')}}',
            //         data: function (params) {
            //             var query = {
            //                 category: $('#category').val(),
            //                 search: params.term,
            //                 page: params.page || 1
            //             } 
            //             return query;
            //         },
            //         processResults: function (res, params) { 

            //             return {
            //                 results: res.data,
            //                 pagination: {
            //                     more: (res.current_page * res.per_page) < res.total
            //                 }
            //             };
            //         }
            //     }
            // }).on('change',filterchange)
        })


        $('#expence_status_update_modal_form').submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(),function(res){
              if(res.success){
                $('#expence_status_update_modal').modal('hide')
                $('#task-manage-task').DataTable().ajax.reload()
                $('#user_task_update').DataTable().ajax.reload()
                $('#task-manage-travel').DataTable().ajax.reload()
                $('#task-manage-expence').DataTable().ajax.reload()
                $('#task-manage-attendance').DataTable().ajax.reload()
              }
            },'json')
            
        })
        $(document).on('click','a.view-img',function(e){
          e.preventDefault();
          $('#attachement_preview_modal_area').html(`
          <object data="${$(this).attr('href')}" ></object>
          `)
          $('#attachement_preview_modal').modal('show')
        })
        
        /*
        *old code
        * */



  function viewall_comments(){
    var task_id=$("#task_id").val();
    var staff_id=0;
    var url = APP_URL+'/staff/view_task_comment_staff';
    $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  task_id:task_id
                },
                success: function (data)
                {    
                  var res = data.split("|*|");

                  /*
                  var proObj = JSON.parse(res[0]);
                  var proObj_replay = JSON.parse(res[1]);
                  htmls=' ';
                  var j=1;
                  var rplays_but = [];
                  admin_id='<?php echo session('ADMIN_ID') ;?>';
                  
                  if(proObj.length>0)
                  {

                    if(proObj[0]["added_by"]==="admin"&&admin_id===proObj[0]["added_by_id"])
                    {
                      $('#contactformcontent').show();
                    }
                    else{
                      $('#contactformcontent').hide();
                    }
                  }
                  else
                  {
                    $('#contactformcontent').show();
                  }
                    for (var i = 0; i < proObj.length; i++) {
                  
                     htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">';
                        if(proObj[i]["contact_name"]!=null)
                        {
                          htmls +='<p>Contact Person: '+proObj[i]["contact_name"]+'</p>';
                        }
                      

                       htmls +='<p>'+proObj[i]["comment"]+'</p>'+
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
                      else{
                        htmls +='<p style="color:red">Pending</p>';
                      }
                        

                        if(proObj[i]["image_name"]!='')
                        {
                          $.each(proObj[i]["image_name"].split(","),function(k,v){

                                var imgs="{{asset('storage/app/public/comment/')}}/"+v;
                          htmls +='<a href="'+imgs+'" download="'+imgs+'"><object data="'+imgs+'" width="50" height="50"></object>Download</a><br>';
                          })
                        }
 

                        if(proObj[i]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj[i]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj[i]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj[i]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                        }
                        if(proObj[i]["added_by"]!='admin' && proObj[i]["status"]=="N")
                        {
                          htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+');"  >'+
                        'Reply</a>';
                        }
                      
                       
                        
                        htmls += '</div>';
                     
                      for (var j = 0; j < proObj_replay.length; j++) {
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          if(proObj_replay[j]["added_by"]=="staff")
                          {
                          htmls += '<div class="reply-comment staff" >';
                          if(proObj_replay[j]["comment"]!=null){
                            htmls +=  ''+proObj_replay[j]["comment"]+'';
                          }
                          

                            htmls += '<p>'+proObj_replay[j]["created_at"]+'<br/>';

                            if(proObj_replay[j]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj_replay[j]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                        }
                            // htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            // if(proObj[i]["status"]=="N" && proObj_replay[j]["replay_status"]=="N"){  
                            // htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+');"  >Reply</a>';
                            // }
                            htmls +='</p>'+
                          '</div>';
                           
                           rplays_but.push(j);
                          }else{
                            htmls += '<div class="reply-comment admin"  >';
                           
                            if(proObj_replay[j]["comment"]!=null){
                            htmls +=  ''+proObj_replay[j]["comment"]+'';
                          }





                          htmls += '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                        if(proObj_replay[j]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj_replay[j]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                        }
                             htmls += '</p>'+'</div>';
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

                    */
 
                    var proObj = JSON.parse(res[0]);
                  var proObj_replay = JSON.parse(res[1]);
                  var proObj_task = JSON.parse(res[2]);
                  var adminassine = JSON.parse(res[3]);
                  var follower_id_new = 0; 
                  
                  var follower_id=proObj_task[0]["followers"];
                  var assains_id=proObj_task[0]["assigns"].split(",");
                  htmls=' ';
                  var j=1;
                  var rplays_but = [];

                        $('#contactformedit').hide();
                  if(proObj.length>0)
                  {
                      added_list = $.map(proObj,function(v,k){
                        return v.added_by_id;
                      })
                      if(proObj[0]["added_by"]=="staff" &&($.inArray(staff_id, added_list) == -1 && $.inArray(staff_id, assains_id) !== -1))
                      {
                        // $('#contactformedit').show();
                      }
                      else{
                        // $('#contactformedit').hide();
                      }
                  }
                  else
                  {
                    // $('#contactformedit').show();
                  }
                  
                    for (var i = 0; i < proObj.length; i++) {
                      
                     htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">';
                         if(proObj[i]["contact_name"]!=null)
                        {
                          htmls +='<p>Contact Person : '+proObj[i]["contact_name"]+'</p>';
                        }
                        htmls += '<p>Comment : '+proObj[i]["comment"]+'</p>';
                        if(proObj[i]["service_task_problem"]!=null)       
                        {
                          htmls +=  '<p>Observed Problem : '+proObj[i]["service_task_problem"]+'</p>'+
                                    '<p>Action Performed : '+proObj[i]["service_task_action"]+'</p>'+
                                    '<p>Final Status : '+proObj[i]["service_task_final_status"]+'</p>';
                        }          
                        htmls +=        '<p>Created : '+proObj[i]["created_at"]+'</p>';
                        if(proObj[i]["task_comment_service_parts"]!=null)       
                        {
                         htmls += '<p>Product : '+proObj[i]["task_comment_service_parts"]["service_part_product"]["name"]+'</p>'+
                                  '<p>Product Status : '+proObj[i]["task_comment_service_parts"]["status"]+'</p>';
                        }
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
                          var re = /(?:\.([^.]+))?$/;
                          $.each(proObj[i]["image_name"].split(","),function(k,v){
                        
                            var imgs="{{asset('public/storage/comment/')}}/"+v;
                            htmls +='<a href="{{asset('download/storage/comment/')}}/'+v+'" download>';
                            if(re.exec(imgs)[1]=='pdf')
                            {
                              htmls += '<object data="'+imgs+'" width="70" height="70"></object>';
                            }
                            else if(["jpg","png","jpeg"].indexOf(re.exec(imgs)[1]) != -1)
                            {
                              htmls += '<img src="'+imgs+'" width="70" height="70">';
                            }
                            else
                            {
                              htmls +='<div > </div>';
                            }
                            htmls +='  Download</a><br>';
                          })
                          
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
                        if(proObj[i]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj[i]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj[i]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj[i]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                        }
                       /* htmls += '<a class="btn btn-danger btn-xs" onClick="delete_task_comment('+proObj[i]["id"]+','+i+');" id="btn_deleteAll" >'+
                        '<span class="glyphicon glyphicon-trash"></span></a>';*/
                      //  if(proObj[i]["added_by"]=='admin' && proObj[i]["status"]=="N")
                      if( proObj[i]["status"]=="N" && staff_id!=proObj[i]["added_by_id"])
                        {
                           
                            var followerids = follower_id.toString().replace(/\,/g, '*');
                             
                            htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+','+follower_id_new+');"  >'+
                        'Reply</a>'; 
                       
                        }
                      //   if( proObj[i]["status"]=="R" && staff_id!=proObj[i]["added_by_id"])
                      //   {
                         

                      //   if(proObj[i]["quick_task_comment"]=="Y")
                      //     {
                             
                      //       var followerids = follower_id.toString().replace(/\,/g, '*');
                      //       htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+','+follower_id_new+');"  >'+
                      //   'Reply</a>';

                      //   }
                      // }
                      htmls += '</div>';
                      for (var j = 0; j < proObj_replay.length; j++) {
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          if(staff_id==proObj_replay[j]["added_by_id"])
                          {
                            var p=1;
                          htmls += '<div class="reply-comment staff" >'+
                           ''+proObj_replay[j]["comment"]+''+
                            '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                         
                        if(proObj_replay[j]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj_replay[j]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                        }




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
                          // htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';

                        if(proObj_replay[j]["added_by"]=="admin")
                        {
                          adminlist=$.map(admin_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(adminlist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.surname+'</b></p>';
                          })
                        }
                        else if(proObj_replay[j]["added_by"]=="staff")
                        {
                          stafflist=$.map(staff_list,function(v,k){
                            if(v.id==proObj_replay[j]["added_by_id"])
                            {
                              return v;
                            }                            
                          });
                          $.each(stafflist,function(k,v){

                            htmls +=  '<p>Added By: <b>'+v.name+'</b></p>';
                          })
                        }
                        else{
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                        }
                            if(proObj[i]["status"]=="N"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by"]){

                          // htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
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
                             
                            }
                            if(proObj[i]["status"]=="R"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by"]){
                              console.log(111);
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
                                // htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                             
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
                    $("#replay_but_"+lastEl).show()
                    $(".load-sec").hide(); 
  
                } 
            });

  }

  function replay_task_comment(id,replay_id,follower_id,follower_id_new){
      var task_id=$("#task_id").val();
      var staff_id=0;
      var url = APP_URL+'/staff/view_task_comment_staff';
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            task_id:task_id
          },
          success: function (data)
          {    

            var res = data.split("|*|");
            var proObj = JSON.parse(res[0]);
            var proObj_replay = JSON.parse(res[1]);
            var proObj_task = JSON.parse(res[2]);
            var adminassaines = JSON.parse(res[3]);
            var follower_id=proObj_task[0]["followers"];
            console.log(proObj[0]);
            console.log('dddd');
            htmls=' ';
            var j=1;
            var rplays_but = [];
              for (var i = 0; i < proObj.length; i++) {
                if(proObj[i]["service_id"] > 0)
                htmls +=  '<p>Observed Problem :<textarea class="form-control" id="edit_service_task_problem" name="edit_service_task_problem">'+proObj[i]["service_task_problem"]+'</textarea></p>'+
                              '<p>Action Performed :<textarea class="form-control" id="edit_service_task_action" name="edit_service_task_action"> '+proObj[i]["service_task_action"]+'</textarea></p>'+
                              '<p>Final Status :<textarea class="form-control" id="edit_service_task_final_status" name="edit_service_task_final_status"> '+proObj[i]["service_task_final_status"]+'</textarea></p>';
              }
              htmls += '<input type="hidden" id="service" value="service">';
            $('#edit_service_problem').html(htmls);

          }
      })

      $("#replay_modal").modal("show");
      $("#task_comment_id").val(id);
      $("#parent_id").val(replay_id); 


      // if ($.isNumeric(follower_id)) {
      //   var follower_ids=follower_id.split("*");
      // console.log(folowerids.length)
      // }
      if(follower_id_new>0)
      {
        $("#follower").val(follower_id);
        $("#follower1").val(follower_id_new);
        if(staff_id==follower_id || staff_id==follower_id_new ){
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

  var service_task_problem = $("#edit_service_task_problem").val();
  var service_task_action = $("#edit_service_task_action").val();
  var service_task_final_status = $("#edit_service_task_final_status").val();


  var follower=$("#follower").val();
  var follower1=$("#follower1").val();
  var status=$("input[name='status_replay']:checked").val(); 
  
  var parent_id=$("#parent_id").val();
  var url = APP_URL+'/staff/add_task_replay_comment_staff';
    $.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        parent_id:parent_id,
        task_id:task_id,
        task_comment_id:task_comment_id,
        replay_comment:replay_comment,
        status:status,
        service_task_problem:service_task_problem,
        service_task_action:service_task_action,
        service_task_final_status:service_task_final_status
      },
      success: function (data)
      {  
        $("#replay_modal").modal("hide");

        $("#task_comment_id").val('');
        $("#replay_comment").val('');

        $("#edit_service_task_problem").val('');
         $("#edit_service_task_action").val('');
          $("#edit_service_task_final_status").val('');
        viewall_comments() 
      }
    });
}
  function show_travellocation(start_time,start_latitude=null,start_longitude=null,end_time='',end_latitude=null,end_longitude=null){
     
    var strpos=`<label>Started : ${start_time}</label><br>`;
    if(start_latitude&&start_longitude){
      strpos+=`<div class="location-map"><br><iframe src="https://maps.google.com/maps?q=${start_latitude},${start_longitude}&hl=es;z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="100%"></iframe></div><br><br>`;
    }

    if(end_time){ 
      strpos+=`
      <label>Ended : ${end_time}</label>
      `;

      if(end_latitude&&end_longitude){
        strpos+=`<div class="location-map"><br><iframe src="https://maps.google.com/maps?q=${end_latitude},${end_longitude}&hl=es;z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="100%"></iframe></div>`;
      }
    } 
      $('#modal-location-task').html(`
      <div class="row">
        <div class="col-md-12"> 
          ${strpos}
        </div
      </div>
      `)
    $('#location_status_update_modal').modal('show')
  }
        function locationverify(url){
          $.get(url,function(res){
            if(res){
              var starttime=new Date(res.start_time)
              var strpos=`<label>Started : ${starttime.toLocaleString('en-IN')}</label><br>`;
              if(res.start_latitude&&res.start_longitude){
                strpos+=`<div class="location-map"><br><iframe src="https://maps.google.com/maps?q=${res.start_latitude},${res.start_longitude}&hl=es;z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="100%"></iframe></div><br><br>`;
              }

              if(res.end_time){
                var endtime=new Date(res.end_time)
                strpos+=`
                <label>Ended : ${endtime.toLocaleString('en-IN')}</label>
                `;

                if(res.end_latitude&&res.end_longitude){
                  strpos+=`<div class="location-map"><br><iframe src="https://maps.google.com/maps?q=${res.end_latitude},${res.end_longitude}&hl=es;z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="100%"></iframe></div>`;
                }
              } 
              $('#modal-location-task').html(`
              <div class="row">
                <div class="col-md-12"> 
                  ${strpos}
                </div
              </div>
              `)
            $('#location_status_update_modal').modal('show')
            }
          },'json')
        }

        function popupclick(id) {
           // $(document).on('click',"#cmsTable tr",function(){
  
            
            $("#task_id").val(id);
            viewall_comments()
            var url = APP_URL+'/staff/view_task_details_staff';
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
                    $("#follower_dis_admin_assign").html(proObj[9]);
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
                 $('#contact_id').select2();
                $("#myModal").modal("show");
                  
              
              }
            });
  
  
           
           } 


           function ApproveStaff() {
         
         var id =  $("#opp_task_id").val();

         var url = APP_URL+'/staff/approve_staff';
         $.ajax({
             type: "POST",
             cache: false,
             url: url,
             data:{
               id:id
             },
             success: function (data)
             {    
             
               $('#user_task_update').DataTable().ajax.reload();

               $("#staff_update_modal").modal("hide");
                   
             }

           });
        }

        function staffclick(id) {
        // $(document).on('click',"#cmsTable tr",function(){

         
         $("#opp_task_id").val(id);

         staff_comments();

         var url = APP_URL+'/staff/view_staff_task';
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

             var task = JSON.parse(res[0]);

             var staff_follower = JSON.parse(res[1]);

             var chatter = JSON.parse(res[2]);

             var opper = JSON.parse(res[3]);

             var contact_person_names = JSON.parse(res[4]);

             htmls=' ';

             var j=1;
             
              
                 
                 $("#staff_taskname").html(task.name);

                 if(task.description!=null){

                   $("#staff_task_view_description").html(task.description);
                 }
                 else{
                   $("#staff_task_view_description").html("No description added");
                 }

                 $(".staff_email_status").hide();
                 $(".staff_call_status").hide();
                 $(".staff_visit_status").hide();

                 if(task.email_status =='Yes')
                 {
                   $(".staff_email_status").show();
                   $("#staff_email_status").html(task.email_status);
                 }

                 if(task.call_status =='Yes')
                 {
                   $(".staff_call_status").show();
                   $("#staff_call_status").html(task.call_status);
                 }

                 if(task.visit_status =='Yes')
                 {
                   $(".staff_visit_status").show();
                   $("#staff_visit_status").html(task.visit_status);
                 }
    
                 $("#est_order_date").html(opper?.es_order_date ?? "");

                 $("#es_sales_date").html(opper?.es_sales_date ?? "");

                 $("#deal_stage").html(staff_follower[2]);
                 $("#order_forcast").html(staff_follower[3]);
                 $("#support").html(staff_follower[4]);
                 


                 $("#staff_created_at").html(task.created_at);
               
                 $("#staff_staff_dis").html(staff_follower[0]);

                 $("#staff_follower_dis").html(staff_follower[1]);

                 if(task.user_id)
                 {
                   $("#staff_contact_link"). attr("href", APP_URL+'/admin/customer/'+task.user_id);
                 }
                 else{
                   $("#staff_contact_link"). attr("href", APP_URL+'/admin/customer/');
                 }

                 htmls=' ';

                 if(chatter.image!='')
                     {
                       var re = /(?:\.([^.]+))?$/;
                       
                       $.each(chatter.image.split(","),function(k,v){
                     
                         var imgs="{{asset('public/storage/chatter/')}}/"+v;
                         htmls +='<a href="{{asset('download/storage/chatter/')}}/'+v+'" download>';
                         if(re.exec(imgs)[1]=='pdf')
                         {
                           htmls += '<object data="'+imgs+'" width="70" height="70"></object>';
                         }
                         else if(["jpg","png","jpeg"].indexOf(re.exec(imgs)[1]) != -1)
                         {
                           htmls += '<img src="'+imgs+'" width="70" height="70">';
                         }
                         else
                         {
                           htmls +='<div > </div>';
                         }
                         htmls +='  Download</a><br>';
                       })
                       
                     }

             $('#staff_image').html(htmls);

             $('#contact_persons').text(contact_person_names);
                 

             $("#staff_update_modal").modal("show");
                 
           }
         });
        
        }


        
 
    </script>

@endsection