@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

<section class="content-header">
    <h1>
      Manage Service
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Service</li>
    </ol>
</section>

<section class="content">
  <div class="manage-service">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12 outer-sect">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- /.box-header -->
            <!-- form start -->
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>


            @endif

            <div id="callsuccess" class="alert alert-success" style="display: none">
             Call added successfully
           </div>


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif
            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            {{ $errors->first('image_name') }}
            </p>

          @php
          $staff_id = session('STAFF_ID');



          $feedback = false;

          if( ((!empty($pm_details->pmtask()->get()) && count($pm_details->pmtask()->get()) > 0) && $pm_details->pmtaskcheck($pm_details->id) && $pm_details->status !='Approved' ) || ( (!empty($pm_details->callcomments()->get()) ) && (count($pm_details->callcomments()->get()) > 0 ) &&  $pm_details->allcallproved($pm_details->id) && $pm_details->status !='Approved' ) )
          {
            $feedback = true;
          }

          @endphp

      <div class="service-btn-right">

            @if( ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56)  )

                    @if( ((!empty($pm_details->pmfeedback()->get()) && count($pm_details->pmfeedback()->get()) > 0) && (!empty($pm_details->pmtask()->get()) && count($pm_details->pmtask()->get()) > 0) && $pm_details->pmtaskcheck($pm_details->id) && $pm_details->status !='Approved') || ( (!empty($pm_details->pmfeedback()->get()) && count($pm_details->pmfeedback()->get()) > 0) &&  (!empty($pm_details->callcomments()->get()) ) && (count($pm_details->callcomments()->get()) > 0 ) &&  $pm_details->allcallproved($pm_details->id) && $pm_details->status !='Approved' ) )

                      @if( ($staff_id == 127 || $staff_id == 37 || $staff_id == 55 || $staff_id == 34 || $staff_id == 56)  )

                        <a class="approve-service btn btn-success" href="{{ route('staff.pm_approve',$pm_details->id) }}">Approve PM</a>

                      @else

                        <a class="approve-service btn btn-success" disable>Approve PM</a>

                      @endif

                  @elseif( ((!empty($pm_details->pmtask()->get()) && count($pm_details->pmtask()->get()) > 0) && $pm_details->pmtaskcheck($pm_details->id) && $pm_details->status !='Approved') || (  (!empty($pm_details->callcomments()->get()) ) && (count($pm_details->callcomments()->get()) > 0 ) &&  $pm_details->allcallproved($pm_details->id) && $pm_details->status !='Approved' ) )

                  <span style="color:red"> FeedBack Not Added <span>

                  @elseif($pm_details->status =='Approved')

                    <a class="approve-service btn btn-success" disabled >Closed PM</a>

                  @elseif( ((!empty($pm_details->pmtask()->get()) && count($pm_details->pmtask()->get()) > 0) && !$pm_details->pmtaskcheck($pm_details->id) && $pm_details->status !='Approved') || (  (!empty($pm_details->callcomments()->get()) ) && (count($pm_details->callcomments()->get()) > 0 ) &&  !$pm_details->allcallproved($pm_details->id) && $pm_details->status !='Approved' ) )

                  <span style="color:red"> All Tasks Not Approved <span>

                  @else

                  <span style="color:red"> Task Not Created <span>

                  @endif
            @endif
    </div>
@if($service->status == 'Completed')
    <h3 style="color:green;">This service task has been completed  .</h3><br>
@endif
      
      <!------------------------------------------above for approve button ------------------------------------------------->
 
    
            @if($service->service_type == 2 && !empty($service->pmContract) && !empty($service->pmContract->oppertunity_id))

            @foreach ($contractpdt as $k=> $item) 

                <div class="service-btn-left" data-sd="{{$k}}">

                       
                     

                        <a class="@if($pm_details->status !='Approved') add-response @endif btn btn-primary" {{ ($pm_details->status =='Approved')  ? 'disabled' : '' }}  >Call</a>

                        @if($staff_id != 55 && $staff_id != 127 && $staff_id != 37 && $staff_id != 34 )

                         <a class="@if($pm_details->status !='Approved') add-task @endif btn btn-primary" data-title="<b>
                          {{ $service->serviceUser->business_name }} -- {{ $item->equipment->name }}
                          -- {{ $item->under_contract }} -- {{ $item->productMachineStatus->name }} --
                          -- {{ $service->created_at }}
                          </b>" data-item="{{$item->id}}" {{ ($pm_details->status =='Approved')  ? 'disabled' : '' }} >Create Visit</a>

                        @endif
                    

                        <a class="btn btn-primary" target="_blank" href="{{ route('staff.create_oppertunity') }}?id={{$service->id}}" {{ ($service->status == "Approved") || ($service->status == "Completed") ? 'disabled' : '' }}>Create Opportunity</a>
                        <a class="technical-support btn btn-primary" {{ ($pm_details->status =='Approved')  ? 'disabled' : '' }} >Request Technical Support</a>
                        <a class="service-audit btn btn-primary" attr-service_id="{{ $service->id }}" >Audit</a>

                      
                        @if($feedback)
                        
                          <a class="@if($pm_details->status !='Approved') feedback btn @endif btn-primary" data-item="{{$item->id}}" {{ ($pm_details->status =='Approved')  ? 'disabled' : '' }} attr-service_id="{{ $service->id }}" >Add Feedback</a>

                        @endif

                    <?php /*
                    @if($service->status == 'Approved')
                    @if(empty($service->serviceFeedback))
                    <h4 style="color:green;">Your service task has approved . Please add the feedback of the customer to complete the service.</h4><br>
                    @endif
                    <a class="feedback btn btn-primary">Add Feedback</a>
                    @endif
                    */?>

                </div>

                <div id="contract-service-{{$k}}">

                <div class="row">
                <h4>Service Details</h4>
                <div class="col-sm-12 service-table-style">
                    <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                        <thead>
                            <tr> 
                                <th>Pm No.</th>
                                <th>Customer Name</th>
                                <th>Contact person</th>
                                <th>Equipment Name</th>
                                <th>Equipment Status</th>
                                <th>Serial Number</th>
                                <th>Complaint details</th>
                                <th>Created date</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata">
                            <tr> 

                                <td class="mobile_view">{{ $pm_details->in_ref_no }}</td>

                                <td class="mobile_view">{{ $service->serviceUser->business_name }}</td>
                                <td class="mobile_view">
                                    @if (!empty($service->serviceContactPerson->id))
                                        {{ $service->serviceContactPerson->name }},
                                        {{ $service->serviceContactPerson->phone }}
                                    @endif
                                </td>
                                <td class="mobile_view">{{ $item->equipment->name }}</td>
                                <td class="mobile_view">{{ $item->under_contract }}</td>
                                <td class="mobile_view">{{ $item->equipment_serial_no }}</td>
                                <td class="mobile_view">{{ $service->call_details }}</td>
                                @php
                                  $created_at_date = \Carbon\Carbon::parse($service->created_at)->format('d-m-Y');
                                @endphp
                                <td class="mobile_view">{{ $created_at_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                   </div>
                  </div> 

                    @if ( ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56  ) )
                     <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>

                    <form method="POST" action="{{ route('staff.store_pm_details') }}">
                        @csrf
                    @else

                    <div>

                    @endif
                        <h4>PM Details</h4>

                        {{-- <div class="form-group col-md-2">
                            <label> Total no of PMs</label>
                            <input type="text" id="no_of_pm_{{$k}}" name="no_of_pm"  value="{{ $item->no_of_pm }}" class="form-control" readonly>
                        </div> --}}

                        <input type="hidden" id="contract_start_date_{{$k}}" name="contract_start_date" value="{{ $item->contract_start_date }}">
                        <input type="hidden" id="contract_end_date_{{$k}}" name="contract_end_date"  value="{{ $item->contract_end_date }}">
                        <input type="hidden" id="service_id_{{$k}}" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" id="equipment_id_{{$k}}" name="equipment_id" value="{{ $item->equipment_id }}">
                        <input type="hidden" id="contract_equipment_id_{{$k}}" name="contract_equipment_id" value="{{ $item->id }}">
                        <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                            <thead>
                                <tr style="display: none">
                                    <th>PM</th>
                                    <th>Visiting Date</th>
                                    <th>Engineer Name</th>
                                    <?php /*<th>Action</th> */?>
                                </tr>
                            </thead>
                            <tbody id="tabledata">
                                @php
                                    $pmDates = json_decode($item->pm_dates??"[]", true) ?? [];
                                @endphp
                                @if (!empty($pmDates) && is_array($pmDates) && count($pmDates) > 0)
                                    @foreach ($pmDates as $index => $date)


                                          @if ( ( ($item->productPM("PM".($index+1))->id == $pm_details->id)  && ( $staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ||$staff_id==(optional($item->productPM("PM".($index+1)))->engineer_name??0) ) ) )
                                            <tr style="display: none">
                                                <td class="mobile_view">
                                                    <span> PM {{ $index + 1 }} </span>
                                                    <input type="hidden" name="pm[]" value="PM{{ $index + 1 }}">
                                                </td>
                                                <td class="mobile_view">

                                                    @if(!empty($item->productPM("PM".($index+1))->visiting_date))

                                                    @php
                                                      $visiting_date = \Carbon\Carbon::parse($item->productPM("PM".($index+1))->visiting_date)->format('d-m-Y');
                                                    @endphp

                                                    <span id="visitdate-name-span-{{ $index }}" class="visitdate-name-span" @if(old("submit","")=="submit") style="display: none" @endif>{{ $visiting_date }}</span>

                                                    <input type="text" readonly  class="form-control visitdate-picker" id="visitdate-picker-{{ $index }}" name="visiting_date[]" value="{{  $item->productPM("PM".($index+1))->visiting_date }}" @if($index==0) onchange="visitedatechange('#contract-service',this)" @endif @if(old("submit","")!="submit") style="display: none;width: fit-content" @endif>

                                                    @else

                                                    <span id="visitdate-name-span-{{ $index }}" class="visitdate-name-span" @if(old("submit","")=="submit") style="display: none" @endif>{{ $date }}</span>

                                                    <input type="text" readonly class="form-control visitdate-picker" id="visitdate-picker-{{ $index }}" name="visiting_date[]" value="{{ $date }}" @if($index==0) onchange="visitedatechange('#contract-service',this)" @endif @if(old("submit","")!="submit") style="display: none; width: fit-content" @endif>

                                                    @endif

                                                </td>

                                                <td class="mobile_view">
                                                    <span  id="engineer-name-{{$k}}-span-{{ $index }}" class="engineer-name-span" @if(old("submit","")=="submit$k") style="display: none" @endif >@if(empty(optional($item->productPM("PM".($index+1)))->engineer)) Select Engineer @else {{optional($item->productPM("PM".($index+1)))->engineer->name}} @endif</span>
                                                    <select class="form-control engineer-dropdown" id="engineer-{{$k}}-dropdown-{{ $index }}" @if($index==0) onchange="engineerdropdownchange('#contract-service-{{$k}}',this)" @endif name="engineer[]" @if(old("submit","")!="submit$k") style="display: none" @endif >
                                                        <option value="">Select Engineer</option>
                                                        @foreach ($staffs as $engineer)
                                                            <option value="{{ $engineer->id }}" @if((old("submit","")=="submit$k"&&$engineer->id==old("engineer.$index",""))||(old("submit","")!=="submit$k"&&$engineer->id==optional($item->productPM("PM".($index+1)))->engineer_name)) selected @endif > {{ $engineer->name }} </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has("engineer.$index") && (old("submit","")=="submit$k"))
                                                        <strong class="error text-danger">{{$errors->first("engineer.$index")}}</strong>
                                                    @endif
                                                </td>
                                              
                                              <?php /*

                                                <td>
                                                   
                                                    @if( (!empty($item->productPM("PM".($index+1))->pmfeedback()->get()) && count($item->productPM("PM".($index+1))->pmfeedback()->get()) > 0) && (!empty($item->productPM("PM".($index+1))->pmtask()->get()) && count($item->productPM("PM".($index+1))->pmtask()->get()) > 0) && $item->productPM("PM".($index+1))->pmtaskcheck($item->productPM("PM".($index+1))->id) && $item->productPM("PM".($index+1))->status !='Approved')

                                                      @if($staff_id == 127 || $staff_id == 55 || $staff_id == 34 || $staff_id == 56)

                                                        <a class="approve-service btn btn-success" href="{{ route('staff.pm_approve',$item->productPM("PM".($index+1))->id) }}">Approve PM</a>

                                                      @else

                                                        <a class="approve-service btn btn-success" disable>Approve PM</a>

                                                      @endif

                                                    @elseif( (!empty($item->productPM("PM".($index+1))->pmtask()->get()) && count($item->productPM("PM".($index+1))->pmtask()->get()) > 0) && $item->productPM("PM".($index+1))->pmtaskcheck($item->productPM("PM".($index+1))->id) && $item->productPM("PM".($index+1))->status !='Approved' )

                                                      <a class="approve-service btn btn-warning" disabled >FeedBack Not Added</a>

                                                    @elseif($item->productPM("PM".($index+1))->status =='Approved')

                                                      <a class="approve-service btn btn-success" disabled >Closed PM</a>

                                                    @elseif((!empty($item->productPM("PM".($index+1))->pmtask()->get()) && count($item->productPM("PM".($index+1))->pmtask()->get()) > 0) && !$item->productPM("PM".($index+1))->pmtaskcheck($item->productPM("PM".($index+1))->id) && $item->productPM("PM".($index+1))->status !='Approved')

                                                      <a class="approve-service btn btn-warning" disabled > All Tasks Not Approved</a>

                                                    @else

                                                      <a class="approve-service btn btn-danger" disabled >Task Not Created</a>

                                                    @endif

                                                </td>
                                                 
                                                */ ?>

                                            </tr>
                                            <?php /*
                                              @if(!in_array( $staff_id,[55,13,34,56])&&(optional($item->productPM("PM".($index+1)))->status??"open")=="open")
                                                  @break
                                              @endif
                                            */?>

                                          @endif


                                          @if( ($item->productPM("PM".($index+1))->id == $pm_details->id) && ( !empty($item->productPM("PM".($index+1))->id) && (!empty($item->productPM("PM".($index+1))->pmtask()->get()) && count($item->productPM("PM".($index+1))->pmtask()->get()) > 0) ) )

                                                
                                                      @foreach($item->productPM("PM".($index+1))->pmtask()->get() as $task)

                                                      <tr>
                                                        <th class="col-wide-4">Task Name</th>
                                                        <th class="col-wide-1">Visiting Date</th>
                                                        <th class="col-wide-1">Visiting Time</th>
                                                        <th class="col-wide-1">Created By</th>
                                                        <th class="col-wide-1">Created At</th>
                                                        <th class="col-wide-1">Status</th>
                                                      </tr>


                                                          <tr>
                                                              @php
                                                                $service_day = \Carbon\Carbon::parse($task->service_day)->format('d-m-Y');
                                                                $pm_created_at = \Carbon\Carbon::parse($task->created_at)->format('d-m-Y');

                                                              @endphp

                                                              <td class="mobile_view">{{ $task->name }}</td>
                                                              <td class="mobile_view">{{ $service_day }}</td>
                                                              <td class="mobile_view">{{ $task->service_time }}</td>
                                                              <td class="mobile_view">{{ $task->taskCreateBy->name }}</td>
                                                              <td class="mobile_view">{{ $pm_created_at }}</td>
                                                              <td class="mobile_view">{{ $task->service_task_status }}</td>
                                                              @if(($staff_id == 127 || $staff_id == 37 || $staff_id == 55)  )
                                                                @if($task->service_task_status == 'Task Created')
                                                                <td><a class="btn delete-task" href="{{ route('staff.service-task-delete',$task->id) }}"><img src="{{ asset('images/delete.svg') }}"></a></td>
                                                                @endif
                                                              @endif
                                                          </tr>

                                                          @if( (!empty($task->taskcomment()->get()) && count($task->taskcomment()->get())>0 )  )

                                                                <tr>
                                                                  <th class="col-wide-1">Response Method</th>
                                                                  <th class="col-wide-1">Created By</th>
                                                                  <th class="col-wide-1">Contacted Person</th>
                                                                  <th class="col-wide-1">Created At</th>
                                                                  <?php /* @if (empty($service->pmContract)) $service->service_type !== 2 || @else  <th>Comment Replay</th> */ ?>
                                                                  <th  class="col-wide-3">Observed Problem</th>
                                                                  <th  class="col-wide-3">Action Performed</th>
                                                                  <th  class="col-wide-3">Final Status</th>
                                                                  <th  class="col-wide-1">Remarks</th>
                                                                  <th  class="col-wide-1">Status</th>
                                                                  @if( ($staff_id == 127  || $staff_id == 37 || $staff_id == 55)  )
                                                                    <th>Action</th>
                                                                  @else
                                                                    <th  class="col-wide-1">Response Status</th>
                                                                  @endif
                                                                </tr>
                                                                       
                                                                    @foreach($task->taskcomment()->get() as $values)
                                                                          <tr>
                                                                              <td class="mobile_view">{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}</td>
                                                                              <td class="mobile_view">{{ $values->taskCommentStaff->name }}</td>
                                                                              <td class="mobile_view">{{ (!empty($values->taskCommentContactPerson->name)) ? $values->taskCommentContactPerson->name : '' }}</td>

                                                                              @php
                                                                                $taskcomment_created = \Carbon\Carbon::parse($values->created_at)->format('d-m-Y');
                                                                              @endphp

                                                                              <td class="mobile_view">{{ $taskcomment_created }}</td>

                                                                              <?php /* @if (empty($service->pmContract)) */?>

                                                                              <td class="mobile_view">{{ $values->service_task_problem }}</td>
                                                                              <td class="mobile_view">{{ $values->service_task_action }}</td>
                                                                              <td class="mobile_view">{{ $values->service_task_final_status }}</td>

                                                                              <td class="mobile_view">{{ $values->task_remark }}</td>
                                                                              <td class="mobile_view status">{{ $values->service_task_status }}</td>

                                                                              <td class="mobile_view">
                                                                                @if( ($staff_id == 127 || $staff_id == 37 || $staff_id == 55 || $staff_id == 34 || $staff_id == 56 ) )
                                                                                 <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>
                                                                                    @if(!empty($values->image_name))
                                                                                        <a class="image-preview" attr-image_name = "{{ $values->image_name }}"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                                    @endif
                                                                                    @if($values->status == 'N' || $values->status == 'R')
                                                                                      <a class="add-Reply btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Reply</a>
                                                                                    @elseif($values->status == 'U')
                                                                                    <span style="color:green;"><b>Remark Update</b></span>
                                                                                    <br><b>Remarks : </b>{{ $values->taskCommentUpload->remarks }}
                                                                                    <br><b>Uploaded date : </b> {{ $values->taskCommentUpload->added_date }}
                                                                                    @else
                                                                                      @if($staff_id == 34 || $staff_id == 56 || $staff_id == 55)
                                                                                        <span style="color:green;"><b>Approved</b></span>
                                                                                        <a class="add-Upload btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Remark Update</a>
                                                                                      @endif
                                                                                    @endif
                                                                                @else
                                                                                    @if($values->status == 'Y')
                                                                                      <span style="color:green;"><b>Approved</b></span>
                                                                                    @elseif($values->status == 'R')
                                                                                      <a class="add-Reply btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Reply</a>

                                                                                      <span style="color:orange;"><b>Waiting for Approval</b></span>
                                                                                    @endif
                                                                                @endif
                                                                              </td>

                                                                          </tr>
                                                                    @endforeach


                                                                    

                                                                  
                                                                @endif

                                                      @endforeach
      
                                                     
                                          @endif


                                    @endforeach


                                    @if( !empty($pm_details->callcomments()->get())  && count($pm_details->callcomments()->get()) > 0 )

                                    @foreach($pm_details->callcomments()->get() as $values)

                                    <tr>
                                      <th class="col-wide-1 {{ $values->id }}">Call Task</th>
                                      <th class="col-wide-1">Created By</th>
                                      <th class="col-wide-1">Contacted Person</th>
                                      <th class="col-wide-1">Created At</th>
                                      <?php /* @if (empty($service->pmContract)) $service->service_type !== 2 || @else  <th>Comment Replay</th> */ ?>
                                      <th  class="col-wide-3">Observed Problem</th>
                                      <th  class="col-wide-3">Action Performed</th>
                                      <th  class="col-wide-3">Final Status</th>
                                      <th  class="col-wide-1">Remarks</th>
                                      <th  class="col-wide-1">Status</th>
                                      
                                      <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>
                                      
                                      @if( ($staff_id == 127  || $staff_id == 37 || $staff_id == 55)  )
                                        <th>Action</th>
                                      @else
                                        <th  class="col-wide-1">Response Status</th>
                                      @endif
                                    </tr>


                                    <tr>
                                        <td class="mobile_view">{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}</td>
                                        <td class="mobile_view">{{ $values->taskCommentStaff->name }}</td>
                                        <td class="mobile_view">{{ (!empty($values->taskCommentContactPerson->name)) ? $values->taskCommentContactPerson->name : '' }}</td>

                                        @php
                                          $taskcomment_created = \Carbon\Carbon::parse($values->created_at)->format('d-m-Y');
                                        @endphp

                                        <td class="mobile_view">{{ $taskcomment_created }}</td>

                                        <?php /* @if (empty($service->pmContract)) */?>

                                        <td class="mobile_view">{{ $values->service_task_problem }}</td>
                                        <td class="mobile_view">{{ $values->service_task_action }}</td>
                                        <td class="mobile_view">{{ $values->service_task_final_status }}</td>

                                        <td class="mobile_view">{{ $values->task_remark }}</td>
                                        <td class="mobile_view status">{{ $values->service_task_status != 'undefined' ? $values->service_task_status : "" }}</td>

                                        <td class="mobile_view ">
                                          @if( ($staff_id == 127  || $staff_id == 37 || $staff_id == 55 || $staff_id == 34 || $staff_id == 56 ) )
                                           <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>
                                              @if(!empty($values->image_name))
                                                  <a class="image-preview" attr-image_name = "{{ $values->image_name }}"><i class="fa fa-download" aria-hidden="true"></i></a>
                                              @endif
                                              @if($values->status == 'N' || $values->status == 'R')
                                                <a class="add-Reply btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Reply</a>
                                              @elseif($values->status == 'U')
                                              <span style="color:green;"><b>Remark Update</b></span>
                                              <br><b>Remarks : </b>{{ $values->taskCommentUpload->remarks }}
                                              <br><b>Uploaded date : </b> {{ $values->taskCommentUpload->added_date }}
                                              @else
                                                @if($staff_id == 34 || $staff_id == 56 || $staff_id == 55 || $staff_id == 127)
                                                  <span style="color:green;"><b>Approved</b></span>
                                                  <a class="add-Upload btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Remark Update</a>
                                                @endif
                                              @endif
                                          @else
                                              @if($values->status == 'Y')
                                                <span style="color:green;"><b>Approved</b></span>
                                              @elseif($values->status == 'R')
                                                <a class="add-Reply btn btn-primary" attr-staff_id= "{{ $staff_id }}" attr-response_type= "{{ ($values->visit == 'Y') ? 'Visit' : 'Call' }}" attr-task_comment_id="{{ $values->id }}" >Reply</a>

                                                <span style="color:orange;"><b>Waiting for Approval</b></span>
                                              @endif
                                          @endif
                                          
                                        </td>

                                    </tr>
                                    @endforeach

                                  @endif


                                    @if(!empty($pm_details->pmfeedback()->get()) && count($pm_details->pmfeedback()->get()) > 0)
                                    
                                            <tr>
                                              <th>Rating</th>
                                              <th>Contact Person</th>
                                              <th>Created By</th>
                                              <th>Created At</th>
                                              <th>Feedback Description</th>
                                            </tr>

                                            @foreach($pm_details->pmfeedback()->get() as $serviceFeedback)
                                                <tr>
                                                    <td class="mobile_view">{{ $serviceFeedback->rating }} star</td>
                                                    <td class="mobile_view">{{ optional($serviceFeedback->serviceFeedbackContact)->name }}</td>
                                                    <td class="mobile_view">{{ optional($serviceFeedback->serviceFeedbackStaff)->name }}</td>
                                                    @php
                                                    $serviceFeedbacks_created = \Carbon\Carbon::parse($serviceFeedback->created_at)->format('d-m-Y');
                                                    @endphp
                                                    <td class="mobile_view">{{ $serviceFeedbacks_created }}</td>
                                                    <td class="mobile_view">{{ $serviceFeedback->description }}</td>
                                                </tr>
                                            @endforeach
                                          
                                  @endif
                                @else
                                    <tr>
                                        <td colspan="4">No PM dates available</td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                    @if ( ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 )  )
                        <?php /*
                        <button type="button" class="btn btn-primary editButton" onclick="editPMDetails('#contract-service-{{$k}}')" @if(old("submit","")=="submit") style="display: none" @endif >Edit PM Details</button>
                        <button type="submit" class="btn btn-primary submitButton" name="submit" value="submit{{$k}}"   @if(old("submit","")!=="submit") style="display: none" @endif>Submit PM Details</button>
                        */?>
                    </form>
                    @else

                    </div>

                    @endif

                </div>

            @endforeach

            @else

            <div class="service-btn-left" data-sd="d">

                    @if($service->service_type != 2)
 
                        <a class="add-response btn btn-primary"  {{ ($service->status == "Approved") || ($service->status == "Completed")  ? 'disabled' : '' }}>Call</a>
                    @endif
                    <a class="add-task btn btn-primary" data-item="" {{ ($service->status == "Approved") || ($service->status == "Completed") ? 'disabled' : '' }}>Create Visit</a>
                    <a class="btn btn-primary" target="_blank" href="{{ route('staff.create_oppertunity') }}?id={{$service->id}}" {{ ($service->status == "Approved") || ($service->status == "Completed") ? 'disabled' : '' }}>Create Opportunity</a>
                    <a class="technical-support btn btn-primary" {{ ($service->status == "Approved") || ($service->status == "Completed") ? 'disabled' : '' }}>Request Technical Support</a>
                    <a class="service-audit btn btn-primary" attr-service_id="{{ $service->id }}" >Audit</a>

                <?php /*
                @if($service->status == 'Approved')
                @if(empty($service->serviceFeedback))
                <h4 style="color:green;">Your service task has approved . Please add the feedback of the customer to complete the service.</h4><br>
                @endif
                <a class="feedback btn btn-primary">Add Feedback</a>
                @endif */?>

            </div>

          <div class="row">
          <h4>Service Details</h4>
            <div class="col-sm-12 service-table-style">
            <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                <thead>
                    <tr>
                        @if ( ( ( $service->service_type !== 2) && ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ) )  )
                        <th class="col-wide-1">Service Engineer</th>
                        @endif
                        <th class="col-wide-1">Customer Name</th>
                        <th class="col-wide-1">Contact person</th>
                        <th class="col-wide-2">Equipment Name</th>
                        <th class="col-wide-1">Equipment Status</th>
                        <th class="col-wide-1">Serial Number</th>
                        <th class="col-wide-1">Complaint details</th>
                        <th class="col-wide-1">Created date</th>
                    </tr>
                </thead>
                <tbody id="tabledata">
                    <tr>
                        @if ( ( ($service->service_type !== 2) && ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ) ) )
                         <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?> 
                        <td class="mobile_view">{{ optional($service->serviceEngineer)->name }}</td>
                        @endif
                        <td class="mobile_view">{{ $service->serviceUser->business_name }}</td>
                        <td class="mobile_view">
                            @if (!empty($service->serviceContactPerson->id))
                                {{ $service->serviceContactPerson->name }},
                                {{ $service->serviceContactPerson->phone }}
                            @endif
                        </td>
                        <td class="mobile_view">{{ $service->serviceProduct->name }}</td>
                        <td class="mobile_view">{{ $service->equipment_status_id }}</td>
                        <td class="mobile_view">{{ $service->equipment_serial_no }}</td>
                        <td class="mobile_view">{{ $service->call_details }}</td>
                        <td class="mobile_view">{{ $service->created_at }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
          </div>


            @if ($service->service_type == 2 && !empty($service->pmContract))

                @if ( ($staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ) )

                    <form method="POST" action="{{ route('staff.store_pm_details') }}" id="contract-service">
                        @csrf
                @else

                <div>

                @endif

             

                <h4>PM Details</h4>
                <div class="form-group col-md-2">
                    <label> Total no of PMs</label>
                    <input type="text" id="no_of_pm" name="no_of_pm"  value="{{ $service->pmContract->no_of_pm }}" class="form-control" readonly>
                </div>
                <input type="hidden" id="contract_start_date" name="contract_start_date" value="{{ $service->pmContract->contract_start_date }}">
                <input type="hidden" id="contract_end_date" name="contract_end_date"  value="{{ $service->pmContract->contract_end_date }}">
                <input type="hidden" id="service_id" name="service_id" value="{{ $service->id }}">
                <input type="hidden" id="equipment_id" name="equipment_id" value="{{ $service->equipment_id }}">
                <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-">
                    <thead>
                        <tr>
                            <th>PM</th>
                            <th>Visiting Date</th>
                            <th>Engineer Name</th>
                        </tr>
                    </thead>
                    <tbody id="tabledata">
                        @php
                            $pmDates = json_decode($service->pmContract->pm_dates??"[]", true) ?? [];
                        @endphp
                        @if (!empty($pmDates) && is_array($pmDates) && count($pmDates) > 0)
                            @foreach ($pmDates as $index => $date)

                                @if ( ( $staff_id == 55 || $staff_id == 127 || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ||$staff_id==optional($service->productPM("PM".($index+1)))->engineer_name ) )
                                 <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>
                                <tr>
                                    <td class="mobile_view">
                                        <span> PM {{ $index + 1 }} </span>
                                        <input type="hidden" name="pm[]" value="PM{{ $index + 1 }}">
                                    </td>
                                    <td class="mobile_view">

                                        @if(!empty($service->productPM("PM".($index+1))->visiting_date))

                                        <span  id="visitdate-name-span-{{ $index }}" class="visitdate-name-span" @if(old("submit","")=="submit") style="display: none" @endif >{{ $service->productPM("PM".($index+1))->visiting_date }}</span>

                                        <input type="text" readonly  class="form-control visitdate-picker" id="visitdate-picker-{{ $index }}" name="visiting_date[]" value="{{  $service->productPM("PM".($index+1))->visiting_date }}" @if($index==0) onchange="visitedatechange('#contract-service',this)" @endif @if(old("submit","")!="submit") style="display: none;width: fit-content" @endif>

                                        @else

                                        <span id="visitdate-name-span-{{ $index }}" class="visitdate-name-span" @if(old("submit","")=="submit") style="display: none" @endif  >{{ $date }}</span>

                                        <input type="text" readonly  class="form-control visitdate-picker" id="visitdate-picker-{{ $index }}" name="visiting_date[]" value="{{ $date }}" @if($index==0) onchange="visitedatechange('#contract-service',this)" @endif @if(old("submit","")!="submit") style="display: none;width: fit-content" @endif>

                                        @endif

                                    </td>

                                    <td class="mobile_view">
                                        <span  id="engineer-name-span-{{ $index }}" class="engineer-name-span" @if(old("submit","")=="submit") style="display: none" @endif >@if(empty(optional($service->productPM("PM".($index+1)))->engineer)) Select Engineer @else {{optional($service->productPM("PM".($index+1)))->engineer->name}} @endif</span>
                                        <select class="form-control engineer-dropdown" id="engineer-dropdown-{{ $index }}" @if($index==0) onchange="engineerdropdownchange('#contract-service',this)" @endif name="engineer[]" @if(old("submit","")!="submit") style="display: none" @endif >
                                            <option value="">Select Engineer</option>
                                            @foreach ($staffs as $engineer)
                                                <option value="{{ $engineer->id }}" @if((old("submit","")=="submit"&&$engineer->id==old("engineer.$index",""))||(old("submit","")!=="submit"&&$engineer->id==optional($service->productPM("PM".($index+1)))->engineer_name)) selected @endif > {{ $engineer->name }} </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has("engineer.$index") && (old("submit","")=="submit"))
                                            <strong class="error text-danger">{{$errors->first("engineer.$index")}}</strong>
                                        @endif
                                    </td>
                                </tr>

                                <?php /*
                                  @if(!in_array( $staff_id,[55,13,34,56])&&(optional($service->productPM("PM".($index+1)))->status??"open")=="open")
                                      @break
                                  @endif
                                */?>

                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No PM dates available</td>
                            </tr>
                        @endif
                    </tbody>

                </table>

            @if ( ($staff_id == 55 || $staff_id == 127  || $staff_id == 37 || $staff_id == 34 || $staff_id == 56 ) )
             <?php /* && !($staff_id == optional($pm_details->engineer)->id ) */?>

                <button type="button" class="btn btn-primary editButton" onclick="editPMDetails('#contract-service')" @if(old("submit","")=="submit") style="display: none" @endif >Edit PM Details</button>
                <button type="submit" class="btn btn-primary submitButton" name="submit" value="submit"   @if(old("submit","")!=="submit") style="display: none" @endif>Submit PM Details</button>
            </form>
            @else

            </div>

            @endif


            @endif

            @endif


            </div>

</div>

</section>


<div class="modal fade" id="create-Task" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Vist Task Details</h3>
			</div>
			<div class="modal-body">

        <form  id="frm_newVisitTaskform" method="post" action="{{ route('staff.service-newVisitTask') }}" enctype="multipart/form-data">

              <div class="form-group" id="visit_details">
                      @csrf
                      <span id="create-Task-title">
                                        
                                  </span><br><br>

                        <input id="contract_product_id" name="contract_product_id" type="hidden" value="">

                        <input id="service_id2" name="service_id2" type="hidden" value="{{ $service->id }}">


                        <input id="engineer_name" name="engineer_name" type="hidden" value="{{ $pm_details->engineer_name }}">
                        
                        <input class="form-control" name="task_name" id="pm_task_name" type="text" placeholder="Task Name" value="">

                        <input class="form-control" name="pm_no" id="pm_no" type="hidden" placeholder="Task Name" value="{{$pm_details->id}}">

                        <span class="error" id="error-pm_task_name" style="display:none"> </span>
                        <br><br>

                        {{-- <select name="pm_no" class="form-control" id="pm_no_value" onchange="UpdatePmData(this)">
                          <option value="" >Select Pm.No</option>
                          @foreach ($service->pmDetails()->get() as $item)

                            @if(!empty($item->engineer_name))

                              <option value="{{$item->id}}" data-contract="{{$item->contract_equipment_id}}" data-id="{{$item->visiting_date}}"> {{$item->pm}} </option>

                            @endif
                            
                          @endforeach
                        </select> --}}

                        <span class="error" id="error-pm_no_value" style="display:none"> </span>

                        <input type="text" style="z-index:99999 !important; " value="{{ $pm_details->visiting_date ? \Carbon\Carbon::parse($pm_details->visiting_date)->format('d-m-Y') : "" }}" class="form-control" id="schedule_date" name="schedule_date" placeholder="Schedule Date" readonly><br><br>

                        <input type="text" class="form-control" id="schedule_time" name="schedule_time" placeholder="Schedule Time"><br><br>
                        <textarea class="form-control" placeholder="Description" name="description" ></textarea><br><br>

              </div>
						<div class="modal-footer">
  
                <button type="button" class="btn btn-primary" onclick="VallidateForm()">Submit</button>
              
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>

          </form>

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="create-Response" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Service Call Response</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="responce_details">
                        <form id="frm_callResponse" method="post" action="{{ route('staff.service-callResponse') }}" enctype="multipart/form-data">
                            @csrf
                            <span>
                                    <b>
                                    {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
                                    -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} --
                                    -- {{ $service->created_at }}
                                    </b>
                              </span><br><br>
                                <span><b>Customer Reported Problem :</b>  {{ $service->call_details }}</span><br><br>
                                <input id="service_id" name="service_id" type="hidden" value="{{ $service->id }}">
                                <span><b>Observed Problem : </b></span>
                                <textarea class="form-control" placeholder="Observed Problem" name="observed_problem" id="observed_problem"></textarea><br><br>
                                <span><b>Action Taken : </b></span>
                                <textarea class="form-control" placeholder="Action Performed" name="action_performed" id="action_performed"></textarea><br><br>
                                <span><b>Final Status : </b></span>
                                <textarea class="form-control" placeholder="Final Status" name="final_status" id="final_status" ></textarea><br><br>
                                <input type="hidden" name="pm_detail_id_comment" id="pm_detail_id_comment" value="{{ $pm_details->id }}" >
                                <span><b>Contact Person : </b></span>
                                <select name="contact_person_id" id="contact_person_id" class="form-control" id="contact_person_id" >
                                <option value="" >Select Contact Person</option>
                                      @foreach($service->serviceUser->userContact as $contactPerson)
                                        <option value="{{ $contactPerson->id }}"> {{ $contactPerson->name }} </option>
                                      @endforeach
                                </select><br>
                                <a href=" {{ route('staff.view_customer',$service->user_id) }}">Add Contact</a><br><br>
                                <b>
                                    <input type="radio" id="part-intend" name="service_part_status" value="part-intend">
                                    Part Intend
                                    <input type="radio" id="test-return" name="service_part_status" value="test">
                                    Test & Return
                                    <input type="radio" id="none" name="service_part_status" value="none" checked="checked">
                                    None
                                </b><br>
                                <div style="display: none" id="part-req">
                                  <select name="product_part_id[]" id="product_part_id" class="form-control" multiple="multiple">
                                      @foreach($products as $product)
                                        <option value="{{ $product->id }}"> {{ $product->name }} </option>
                                      @endforeach
                                  </select><br>
                                </div><br><br>
                                <span><b>Remark :  </b></span>
                                <textarea class="form-control" placeholder="Remark" name="task_remark" id="task_remark" ></textarea><br><br>
                                <div class="form-group">
                                <span><b>Service status :
                                    <input type="radio" id="closed" name="service_task_status" value="Call Closed">
                                    Call Closed
                                    <input type="radio" id="not-closed" name="service_task_status" value="Call Not Closed">
                                    Call Not Closed
                                </b></span><br>
                                </div><br><br>

                                <input type="file" id="image_name" name="image_name"  accept=".jpg,.jpeg,.png,.pdf"/><span class="text-muted" id="maximum-upload-img-info">* Maximum 5 Files allowed</span>
                                <span class="error_message" id="image_name_message" style="display: none">Field is required</span>
                                <span  class="error_message" id="maximum-upload-img-errror"></span>
                                <div id="upload-image-preview" ></div>

                              </div>
                              <div class="modal-footer">
                              <input type="button" id="addTaskCommentBtn" class="btn btn-primary" name="submit" value="submit">
                        </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div class="load-sec" style="display:none;">
                      <img src="{{ asset('images/wait.gif') }}">
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Part Intended Modal -->
<div class="modal fade" id="part-Intend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Select Parts</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="req-part">
                        <form id="part-details" method="post" action="{{ route('staff.service-RequestPart') }}" enctype="multipart/form-data">
                            @csrf
                            <span>
                                    <b>
                                    {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
                                    -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} --
                                    -- {{ $service->created_at }}
                                    </b>
                              </span><br><br>
                                <input id="service_id" name="service_id" type="hidden" value="{{ $service->id }}">
                                <select name="product_part_id[]" id="product_part_id" class="form-control" multiple="multiple">
                                      @foreach($products as $product)
                                        <option value="{{ $product->id }}"> {{ $product->name }} </option>
                                      @endforeach
                                </select><br><br>
                                <textarea class="form-control" placeholder="Description" name="part_description" ></textarea><br><br>

                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="submit" value="submit">
                        </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- End Part Intended Modal -->

<!-- Part Ordered Details Modal -->
<div class="modal fade" id="part-Details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Add Order Details</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="req-part">
                        <form id="part-details" method="post" action="{{ route('staff.service-product_order') }}" enctype="multipart/form-data">
                            @csrf
                              <span>
                                    <b>
                                    {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
                                    -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} --
                                    -- {{ $service->created_at }}
                                    </b>
                              </span><br><br>
                              <span><b>Part No : </b><input type="text" class="form-control" name="part_no" value=""></span><br>
                              <span><b>External / Transaction : </b><input type="text" class="form-control" name="part_no" value=""></span><br>
                              <span>Ordered date : <input style="z-index:99999 !important;" class="form-control" type="text" id="ordered_date" name="ordered_date" readonly> </span><br>
                              <span>Expected Date : <input style="z-index:99999 !important;" class="form-control" type="text" id="expected_date" name="expected_date" readonly> </span><br>
                              <textarea class="form-control" placeholder="Remark" name="part_remark" ></textarea><br>
                              <span><b>
                                    <input type="radio" id="approve-part" name="approve_part" value="Y" checked="checked">
                                    Approve
                                    <input type="radio" id="pending-part" name="approve_part" value="N">
                                    Pending
                                 </b></span>
                              <br><br>
                              <input type="hidden" id="service-PartId" name="service_part_id" value="" >
                              <input type="hidden" name="service_id" value="{{ $service->id }}" >

                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="submit" value="submit">
                        </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
			</div>
		</div>
	</div>
</div>
<!--End Part Ordered Details Modal -->

<!-- Test Part Ordered Details Modal -->
<div class="modal fade" id="testPart-Details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Add Order Details</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="req-part">
                        <form id="testPart-details" method="post" action="{{ route('staff.service-product_order') }}" enctype="multipart/form-data">
                            @csrf
                            <span>
                                    <b>
                                    {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
                                    -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} --
                                    -- {{ $service->created_at }}
                                    </b>
                              </span><br><br>
                              <span><b>Part No : </b><input type="text" class="form-control" name="part_no" value=""></span><br>
                              <span><b>External / Transaction : </b><input type="text" class="form-control" name="part_no" value=""></span><br>
                              <span>Expected Date : <input style="z-index:99999 !important;" class="form-control" type="text" id="expected_date1" name="expected_date" readonly> </span><br>
                              <textarea class="form-control" placeholder="Remark" name="part_remark" ></textarea><br><br>
                              <input type="hidden" id="service-TestPartId" name="service_part_id" value="" >
                              <input type="hidden" name="service_id" value="{{ $service->id }}" >

                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="submit" value="submit">
                        </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
			</div>
		</div>
	</div>
</div>
<!--End Test Part Ordered Details Modal -->

<div class="modal fade" id="tech-support" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h5 class="modal-title">Technical Support Engineer</h5>
			</div>
			<div class="modal-body">
                <div class="form-group" id="tech_support">
                        <form id="tech_support-details" method="post" action="{{ route('staff.service-technical_support') }}" enctype="multipart/form-data">
                            @csrf
                            <span>
                                    <b>
                                    {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
                                    -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} -
                                    -- {{ $service->created_at }}
                                    </b>
                              </span><br><br>
                                <input id="service_id" name="service_id" type="hidden" value="{{ $service->id }}">
                                <label><b>External No :  </b><br>{{ $service->external_ref_no }} </label><br>
                                <label><b>Equipment Name :  </b><br>{{ $service->serviceProduct->name }} </label><br>
                                <label><b>Equipment Sr.NO :  </b><br>{{ $service->equipment_serial_no }} </label><br>
                                <label><b>Equipment Software Ver : </b><br><input type="text" class="form-control" name="eq_software_v" value=""><br>
                                <label><b>Nature of Fault Reported By Customer : </b><br>{{ $service->call_details }}</label><br>
                                @if(!empty($serviceTaskComment))
                                  <label><b>Fault observed at site by engg :  </b><br>{{ $serviceTaskComment->service_task_problem }} </label><br>
                                  <label><b>Technical action peformed :  </b><br>{{ $serviceTaskComment->service_task_action }} </label><br>
                                  <label><b>Conclusion of the engg : </b><br>{{ $serviceTaskComment->service_task_final_status }} </label><br>
                                  @if(!empty($serviceTaskComment->taskCommentServiceParts))
                                    <label><b>Parts support reqd : </b><br>{{ $serviceTaskComment->taskCommentServiceParts->servicePartProduct->name }} </label><br>
                                    <label><b>Part no# of required part : </b><br> {{ (!empty($serviceTaskComment->taskCommentServiceParts->part_no)) ? $serviceTaskComment->taskCommentServiceParts->part_no : 'Not Added' }} </label><br>
                                  @endif
                                  <label><b>End User Name ( Who knows about this problem ) : </b><br> {{ (!empty($serviceTaskComment->taskCommentContactPerson->name)) ? $serviceTaskComment->taskCommentContactPerson->name : 'Not Added' }} </label><br>
                                  <label><b>End User Phone NO : </b><br>{{ (!empty($serviceTaskComment->taskCommentContactPerson->phone)) ? $serviceTaskComment->taskCommentContactPerson->phone : 'Not Added' }}</label><br>
                                @endif
                                <label><b>Special Remarks if  any</b></label>
                                <textarea class="form-control" placeholder="Special Remarks if  any" name="tech_support_description" ></textarea><br><br>
                                <label><b>Select the Support Engineer : </b></label>
                                <select name="tech_support_id" id="tech_support_id" class="form-control">
                                      @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}"> {{ $staff->name }} </option>
                                      @endforeach
                                </select><br><br>

                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="submit" value="submit">
                        </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Feed Back Details Modal -->
<div class="modal fade" id="feedback-Details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">FeedBack</h3>
			</div>
			<div class="modal-body">

              <form id="feedback-details-form" method="post" action="{{ route('staff.service-feedback') }}" enctype="multipart/form-data">
                @csrf
                      <div class="form-group" id="req-part">
                        
                            <label><b>Contact Person : </b></label>

                                <select name="contact_person_id" id="feed_back_contact_person_id" class="form-control">

                                <option value="" >Select Contact Person</option>
                                      @foreach($service->serviceUser->userContact as $contactPerson)
                                        <option value="{{ $contactPerson->id }}"> {{ $contactPerson->name }} </option>
                                      @endforeach
                                </select>

                                <span class="error" id="feed_back_contact_person_id_error" style="display:none;"> </span>
                                
                                <br>

                                <label><b>Rating : </b></label>
                                <select name="feedback_rating" id="feedback_rating" class="form-control">
                                <option value="" >Select Rating</option>
                                      <option value="1"> 1 star </option>
                                      <option value="2"> 2 star </option>
                                      <option value="3"> 3 star </option>
                                      <option value="4"> 4 star </option>
                                      <option value="5"> 5 star </option>
                                      <option value="6"> 6 star </option>
                                      <option value="7"> 7 star </option>
                                      <option value="8"> 8 star </option>
                                      <option value="9"> 9 star </option>
                                      <option value="10"> 10 star </option>
                                </select>
                                <span class="error" id="feedback_rating_error" style="display:none;"> </span>
                                <br>
                                <input type="hidden" name="pm_detail_id" value="{{$pm_details->id}}" >

                                <?php /*
                                <label><b>Select Pm.No </b></label>

                                <select name="pm_detail_id" class="form-control" id="pm_detail_id" >
                                  <option value="" >Select Pm.No</option>
                                  @foreach ($service->pmDetails()->get() as $item)
        
                                    @if(!empty($item->engineer_name))
        
                                      <option value="{{$item->id}}" data-contract="{{$item->contract_equipment_id}}"> {{$item->pm}} </option>
        
                                    @endif
                                    
                                  @endforeach
                                </select>
                                <span class="error" id="pm_detail_id_error" style="display:none;"> </span>
                                 */ ?>
                                
                                <br>

                                <label><b>Comment : </b></label>

                              <textarea class="form-control" placeholder="Add comments" name="feedback_description" id="feedback_description" ></textarea><br><br>
                              <input type="hidden" name="service_id" value="{{ $service->id }}" >
                              <span class="error" id="feedback_description_error" style="display:none;"> </span>
                        </div>

                    <div class="modal-footer">

                      <button type="button" class="btn btn-primary" onclick="ValidateFeedBack(this)">   

                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                  </div>

              </form>
			</div>
		</div>
	</div>
</div>
<!--Feed Back Details Modal -->

<!-- Part Ordered Details Modal -->
<div class="modal fade" id="deletePart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="form-group" id="req-part">
                <h3 class="modal-title">Do you want to delete ?</h3><br>
                        <form id="part-details" method="post" action="{{ route('staff.service-partDelete') }}" enctype="multipart/form-data">
                            @csrf
                              <input type="hidden" id="seviceDeletePartId" name="service_part_id" value="" >
                              <input type="submit" class="btn btn-primary" name="submit" value="Yes">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                </div>
			</div>
		</div>
	</div>
</div>
<!--End Part Ordered Details Modal -->

<div class="modal fade" id="serviceAudit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Service Brief Details</h3>
			</div>
			<div class="modal-body">
               <table id="cmsTable" class="mobile_view_table table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>Sl.No</th>
                    <th>Action</th>
                    <th>Performed By</th>
                    <th>Performed At</th>
                  </tr>
                </thead>
                <tbody id="show-audit">

                </tbody>
            </table>
			</div>
		</div>
	</div>
</div>

<!-- Part Ordered Details Modal -->
<div class="modal fade" id="responseReply" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Response Reply</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="req-response">
                        <form id="response-details" method="post" action="{{ route('staff.service-response-reply') }}" enctype="multipart/form-data">


                        </form>
                </div>
			</div>
		</div>
	</div>
</div>
<!--End Part Ordered Details Modal -->

<!-- Part Ordered Details Modal -->
<div class="modal fade" id="uploadRemark" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h3 class="modal-title">Response Remark Update</h3>
			</div>
			<div class="modal-body">
                <div class="form-group" id="upload-rem">

                        <form id="upload-remark" method="post" action="{{ route('staff.service-uploadRemark') }}" enctype="multipart/form-data">
                            @csrf
                              <input type="hidden" id="remarkTaskComment" name="task_comment_id" value="">
                              <input type="text" style="z-index:99999 !important;" id="remark_added_date" name="added_date" class="form-control" value="" placeholder="Select Date" readonly><br><br>
                              <textarea class="form-control" placeholder="Enter Remark" name="remark" ></textarea><br><br>
                              <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                </div>
			</div>
		</div>
	</div>
</div>
<!--End Part Ordered Details Modal -->
<!--Image Preview Modal -->
<div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
        <div id="image-show">

        </div>
			</div>
		</div>
	</div>
</div>
<!--Image Preview Modal End -->

@endsection
@section('scripts')


 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>



<script>

      $('.visitdate-picker').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
          // minDate: 0
        });

        function UpdatePmData(selectElement) {
  
          var visitingDate = $(selectElement).find('option:selected').data('id');

          $('#schedule_date').val(visitingDate);

        }

        function ValidateFeedBack()
        {
            var feed_back_contact_person_id = $('#feed_back_contact_person_id').val();
            var feedback_rating = $('#feedback_rating').val();
            // var pm_detail_id = $('#pm_detail_id').val();
            var feeback = $('#feedback_description').val();

            $('#feed_back_contact_person_id_error').hide();
            $('#feedback_rating_error').hide();
            // $('#pm_detail_id_error').hide();
            $('#feeback_error').hide();

            var validate = true;

            if(feed_back_contact_person_id =="" || feed_back_contact_person_id == null)
            {
              $('#feed_back_contact_person_id_error').text('The field is required').show();
              validate = false;
            }

            if(feedback_rating =="" || feedback_rating == null)
            {
              $('#feedback_rating_error').text('The field is required').show();
              validate = false;
            }

            // if(pm_detail_id =="" || pm_detail_id == null)
            // {
            //   $('#pm_detail_id_error').text('The field is required').show();
            //   validate = false;
            // }

            if(feeback =="" || feeback == null)
            {
              $('#feedback_description_error').text('The field is required').show();
              validate = false;
            }

            if(validate)
            {
              $('#feedback-details-form').submit();
            }
           
        }
        
        function VallidateForm() {

              console.log('test clicks');

              // Get values from input fields
              var task_name = document.getElementById('pm_task_name').value;
              // var pm_no_value = document.getElementById('pm_no_value').value;

              // Validate Task Name
              if (task_name == "") {
                  console.log('err clicks');
                  document.getElementById('error-pm_task_name').textContent = 'The field is required';
                  document.getElementById('error-pm_task_name').style.display = 'block';
              } else {
                  document.getElementById('error-pm_task_name').style.display = 'none';
              }

              // // Validate PM No
              // if (pm_no_value == "") {
              //     console.log('err1 clicks');
              //     document.getElementById('error-pm_no_value').textContent = 'The field is required';
              //     document.getElementById('error-pm_no_value').style.display = 'block';
              // } else {
              //     document.getElementById('error-pm_no_value').style.display = 'none';
              // }

              // If both fields are not empty, submit the form
              if (task_name != "" ) {
                  console.log('inside form');

                  // Manually trigger form submission
                  document.getElementById('frm_newVisitTaskform').submit();
              }
          }



    function editPMDetails(parent){
        $(parent).find('.editButton,.engineer-name-span').hide();
        $(parent).find('.submitButton,.engineer-dropdown').show();

        $(parent).find('.editButton,.visitdate-name-span').hide();
        $(parent).find('.submitButton,.visitdate-picker').show();
    }

    function engineerdropdownchange(parent,target){
        var selectedEngineer = $(parent).find(target).val();
        $(parent).find('.engineer-dropdown').not(target).each(function(){
            var thisval=$(this).val()||"";
            if(thisval==""){
                $(this).val(selectedEngineer)
            }
        })
    }

</script>

<script>

    // $(document).ready(function() {

    //     $('#submitButton').hide();
    //     $('select.engineer-dropdown').prop('disabled', true);visitdate-name-span
    //     $('span[id^="engineer-name-span"]').show();  // Show all spans
    //     $('select.engineer-dropdown').hide();  // Hide all selects

    //     // Show the submit button and hide the edit button when the edit button is clicked
    //     $('#editButton').click(function() {
    //         $(this).hide();
    //         $('#submitButton').show();
    //         $('select.engineer-dropdown').prop('disabled', false); // Enable all select fields
    //         $('span[id^="engineer-name-span"]').hide();  // Hide all spans
    //         $('select.engineer-dropdown').show();  // Show all selects
    //     });

    //     $('#engineer-dropdown0').change(function() {
    //         var selectedEngineer = $(this).val();
    //         console.log('Selected Engineer:', selectedEngineer);
    //        $('.engineer-dropdown').not(this).val(selectedEngineer);
    //     });
    // });

</script>


<script type="text/javascript">

$('.add-task').on('click', function () {
    var titletext=$(this).data('title')||"";
    if(titletext==""){
      titletext ==`
      <b>
      {{ $service->serviceUser->business_name }} -- {{ $service->serviceProduct->name }}
      -- {{ $service->equipment_status_id }} -- {{ $service->serviceMachineStatus->name }} --
      -- {{ $service->created_at }}
      </b>
      `
    }
    $('#create-Task-title').html(titletext);

    var item_id = $(this).data('item');

    $('#pm_no_value option').each(function(i, v) {

        if (item_id == $(v).data('contract')) {
            $(v).show();
        } else {
            $(v).hide();
        }
    });

    $('#schedule_date').val();

    $('#create-Task').modal('show');


    $('#contract_product_id').val(item_id);

    $('#visit_details').show();
    $('#schedule_date').datepicker({
          //dateFormat:'yy-mm-dd',
          dateFormat:'dd-mm-yy',
          changeYear : true,
          changeMonth:true,
          // minDate: 0
      });
    $('#call_details').hide();

    $("input[name=job_type]:radio").click(function () {
        if ($('input[name=job_type]:checked').val() == "call") {
          $('#visit_details').hide();
          $('#call_details').show();
        } else if ($('input[name=job_type]:checked').val() == "visit") {

            $('#visit_details').show();
            $('#schedule_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                   // minDate: 0
                });
            $('#call_details').hide();
        }
    });
  });
  $('.add-response').on('click', function () {
    $('#create-Response').modal('show');
    $("#product_part_chk").change(function() {
            if(this.checked) {
              $("#part-req").show();
                $('#product_part_id').multiselect({
                });
            }else{
              $("#part-req").hide();
            }
        });
        $("#part-intend").change(function() {
            if(this.checked) {
              $("#part-req").show();
                $('#product_part_id').multiselect({
                });
            }
        });
        $("#test-return").change(function() {
            if(this.checked) {
              $("#part-req").show();
                $('#product_part_id').multiselect({
                });
            }
        });
        $("#none").change(function() {
            if(this.checked) {
              $("#part-req").hide();

            }
        });
  });
  $('.req-part').on('click', function () {
    $('#part-Intend').modal('show');
    $('#product_part_id').multiselect({
      enableFiltering: true,
    });
  });
  $('.add-Order-Detail').on('click', function () {
    var service_part_id = $(this).attr('attr-service-id');
    $('#part-Details').modal('show');
    $('#service-PartId').val(service_part_id);
    $('#ordered_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                   // minDate: 0
                });
    $('#expected_date').datepicker({
        //dateFormat:'yy-mm-dd',
        dateFormat:'yy-mm-dd',
        // minDate: 0
    });

  });
  $('.technical-support').on('click', function () {
    $('#tech-support').modal('show');

  });
  $('.add-testPart-details').on('click', function () {

    var service_part_id = $(this).attr('attr-service-id');
    $('#testPart-Details').modal('show');
    $("#tech_support_id").select2({
            enableFiltering: true,
          });
    $('#service-TestPartId').val(service_part_id);
    $('#expected_date1').datepicker({
        //dateFormat:'yy-mm-dd',
        dateFormat:'yy-mm-dd',
        // minDate: 0
    });
  });

  $('.feedback').on('click', function () {

      var item_id = $(this).data('item');

      $('#pm_detail_id option').each(function(i, v) {

          if (item_id == $(v).data('contract')) {
              $(v).show();
          } else {
              $(v).hide();
          }
      });

    $('#feedback-Details').modal('show');

  });

  $('.delete-part').on('click', function () {
    $('#deletePart').modal('show');
    var service_part_id = $(this).attr('attr-partId');
    $('#seviceDeletePartId').val(service_part_id);
  });
  $('.add-Upload').on('click', function () {
    $('#uploadRemark').modal('show');
    var task_comment_id = $(this).attr('attr-task_comment_id');
    $('#remarkTaskComment').val(task_comment_id);
    $('#remark_added_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                   // minDate: 0
                });
  });
  $('.image-preview').on('click', function () {
    $('#image-modal').modal('show');
    htmls = '';
    var image_name = $(this).attr('attr-image_name');
    var re = /(?:\.([^.]+))?$/;
    $.each(image_name.split(","),function(k,v){
      htmls += '<a href="{{asset("download/storage/comment/")}}/'+v+'" target="_blank">Download</a>'
      if(re.exec(v)[1]=='pdf')
      {
        htmls += '<object data="{{asset("public/storage/comment/")}}/'+v+'" style="width:100%;height:700px"></object>';
      }
      else if(["jpg","png","jpeg"].indexOf(re.exec(v)[1]) != -1)
      {
        htmls += '<img src="{{asset("public/storage/comment/")}}/'+v+'" style="width:100%;height:700px">';
      }
      else
      {
        htmls +='<div >This file can\'t preview</div>';
      }

    })
    $('#image-show').html(htmls);
  });
  $('.service-audit').on('click', function () {
    $('#serviceAudit').modal('show');
    var service_id = $(this).attr('attr-service_id');

              var APP_URL = {!! json_encode(url('/')) !!};
              var url = APP_URL+'/staff/service-audit';
                  $.ajax
                      ({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            service_id : service_id
                      },
                    success: function (data)
                    {
                      $('#show-audit').html(data.result);
                    }

                  });
  });


  $('.add-Reply').on('click', function () {

    console.log('test file');

    var task_comment_id = $(this).attr('attr-task_comment_id');
    var responseType = $(this).attr('attr-response_type');
    var staff_id = $(this).attr('attr-staff_id');
    $('#response-details').html("");
    $('#responseReply').modal('show');

    var APP_URL = {!! json_encode(url('/')) !!};
              var url = APP_URL+'/staff/service-response-details';

                  $.ajax
                      ({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            task_comment_id : task_comment_id
                      },
                    success: function (data)
                    {
                      htmls = '';
                      htmls += '@csrf';
                      htmls += '<label><b>Observed Problem :</label></b><textarea readonly class="form-control" name="service_task_problem">'+data.result.service_task_problem+'</textarea><br><br>';
                      htmls += '<label><b>Action Performed :</label></b><textarea readonly class="form-control" name="service_task_action">'+data.result.service_task_action+'</textarea><br><br>';
                      htmls += '<label><b>Final Status :</label></b><textarea readonly class="form-control" name="service_task_final_status">'+data.result.service_task_final_status+'</textarea><br><br>';
                      if( staff_id == 127 || $staff_id == 37 || staff_id == 55){
                      htmls += '<div id="response-details"><label><b>Reply Comment :</label></b><textarea id="reply_comment" class="form-control" placeholder="Add Reply Comment" name="replay_comment" >'+data.result.task_remark+'</textarea><br><br>'+
                            '<span><b>'+
                                    '<input type="radio" id="approve-response" name="status" value="Y" checked="checked">'+
                                    'Approve'+
                                    '<input type="radio" id="pending-response" name="status" value="R">'+
                                    'Reject'+
                            '</b></span><br><br>';
                          if(data.result.service_task_status == 'Visit Closed' || data.result.service_task_status == 'Call Closed'){
                            htmls += '<input type="radio" id="closed-response" name="task_status" value="Closed" checked="checked">'+
                                        'Closed'+
                                        '<input type="radio" id="open-response" name="task_status" value="Open">'+
                                        'Open'+
                                '</b></span><br><br>'+
                                '<div>';
                          }else{
                            htmls += '<input type="radio" id="closed-response" name="task_status" value="Closed">'+
                                        'Closed'+
                                        '<input type="radio" id="open-response" name="task_status" value="Open" checked="checked">'+
                                        'Open'+
                                '</b></span><br><br>'+
                                '<div>';
                          }
                      }
                      htmls += '<input type="hidden" id="responseTaskComment" name="task_comment_id" value="'+task_comment_id+'">'+
                              '<input type="hidden" id="responseType" name="task_comment_type" value="'+responseType+'">'+
                              '<input type="submit" class="btn btn-primary" name="submit" value="Submit">'+
                              '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                      $('#response-details').html(htmls);
                    }

                  });

    $('#responseTaskComment').val(task_comment_id);

  });

</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<script>
  var img_pdf = [];
  var imag_upload_id = 1;

  function remove_file(key, prev_id) {
      $('#upload-file-' + prev_id).remove();
      img_pdf = img_pdf.filter(function(ele, ke) {
          return ke != key;
      });
  }



  function add_file(key, file) {
      const filetmpurl = URL.createObjectURL(file);
      const filetype = file.type.toUpperCase();

      const previewId = `upload-file-${imag_upload_id}`;

      if (filetype.indexOf("IMAGE/") !== -1) {
          jQuery('#upload-image-preview').append(
              `<div style='width:20%; display: inline-block; margin: 10px;' id='${previewId}'>
                  <img src='${filetmpurl}' style='min-height:100px; width:100%; object-fit: cover; border: 1px solid #ddd;' />
                  <a href='javascript:remove_file(${key},${imag_upload_id});'>Remove</a>
              </div>`
          );

          const doc = new jsPDF({
              orientation: 'p',
              unit: 'px',
              format: 'a4',
          });

          const img = new Image();
          img.src = filetmpurl;

          img.onload = function() {
              const docWidth = doc.internal.pageSize.width;
              const docHeight = doc.internal.pageSize.height;

              let thisWidth, thisHeight;

              if (img.width > img.height) {
                  const scale = docHeight / img.height;
                  thisWidth = img.width * scale;
                  thisHeight = docHeight;
              } else {
                  const scale = docWidth / img.width;
                  thisWidth = docWidth;
                  thisHeight = img.height * scale;
              }

              doc.addImage(img, filetype.replace("IMAGE/", ""), 10, 10, thisWidth - 20, thisHeight - 20);

              const pdfBlob = doc.output('blob');
              const pdfFile = new File([pdfBlob], `${Math.random().toString(36).substring(2, 10)}.pdf`, {
                  type: "application/pdf",
              });

              img_pdf[key] = pdfFile;
          };
      } else {
          jQuery('#upload-image-preview').append(
              `<div style='width:20%; display: inline-block; margin: 10px;' id='${previewId}'>
                  <object data='${filetmpurl}' style='min-height:100px; width:100%; border: 1px solid #ddd;'></object>
                  <a href='javascript:remove_file(${key},${imag_upload_id});'>Remove</a>
              </div>`
          );
          img_pdf[key] = file;
      }

      imag_upload_id++;
  }



      jQuery(document).ready(function() {
        @if(request('success'))
            $('#callsuccess').show();
        @else
            $('#callsuccess').hide();
        @endif
    });


      jQuery(document).ready(function() {

          jQuery('#image_name').change(function(e){
            file_list=document.getElementById('image_name').files;
            
            if (file_list.length > 5 || (file_list.length+img_pdf.length)>5) {
                $('#maximum-upload-img-errror').text("Only 5 files accepted.")
                
            }
            else
            {
              $.each(file_list,function(key,file){
                if(file)
                {
                  add_file(img_pdf.length+key,file)
                }
              })
              $('#maximum-upload-img-errror').text("")
            }

          })

        jQuery("#addTaskCommentBtn").click(function() {
         var observed_problem=$("#observed_problem").val();
         var action_performed=$("#action_performed").val();
         var service_id=$("#service_id").val();
         var contact_person_id=$("#contact_person_id").val();
         var product_part_id=$("#product_part_id").val();
         var task_remark=$("#task_remark").val();
         var final_status=$("#final_status").val();
         var pm_detail_id = $('#pm_detail_id_comment').val();
         var service_task_status = $('input[name="service_task_status"]:checked').val();
         var service_part_status = $('input[name="service_part_status"]:checked').val();

         var image_name = $('#image_name').val();
         $(".load-sec").show();
         $('#image_name_message').hide()
             var task_id=$("#task_id").val();
             var url = "{{ route('staff.service-callResponse') }}";
             var formData = new FormData();
               if(image_name != '') {    
              $.each( img_pdf,function(key,file)
              {
                if(file)
                {
        
                  formData.append('image_name['+key+']',file);
                }
              })
            }
               formData.append('task_id',task_id);  
               formData.append('service_id',service_id);  
               formData.append('observed_problem',observed_problem);  
               formData.append('action_performed',action_performed);
               formData.append('product_part_id',product_part_id);  
               formData.append('final_status',final_status);  
               formData.append('contact_person_id',contact_person_id);  
               formData.append('service_task_status',service_task_status); 
               formData.append('service_part_status',service_part_status);  
               formData.append('task_remark',task_remark);  
               formData.append('pm_detail_id',pm_detail_id);

              console.log(formData)
              $.ajax({
                type: "POST",
              cache: false,
              processData: false,
              contentType: false,
              url: url,
              data:formData,
                success: function (data)
                {  
                  $(".load-sec").hide();

                  jQuery('#upload-image-preview').html("");
                 

                  window.location.href = "{{ request()->fullUrl() }}?success=true";
                
                }
             });
       
         });
        }); 
  
</script>

@endsection
 