@extends('staff/layouts.app')



@section('title', 'Dashboard')



@section('content')

     

    <!-- Content Header (Page header) -->
    <section class="content-header dashboard-header">
      <h1> Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->

    <section class="content dashboard-wrap">

      <!-- Info boxes -->

      @php

        $staff_id = session('STAFF_ID');

        $permission = \App\Models\User_permission::where('staff_id', $staff_id)->first();

        $cor_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','customer')->first();

        $permission_staffs = \App\Models\User_permission::where('work_update_coordinator',$staff_id)->pluck('staff_id')->unique()->toArray();

      @endphp

      <div class="row">
        
        <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
            <div class="info-box-content">
            <a href="">
              <span class="info-box-text">
                This Quarter Report
              <img src="{{ asset('images/quarter-repot.svg') }}" class="info-icon"> </span>
              <div class="viewcounts">
                <span> Target : {{round($monthtarget,2)}} </span>
                <span> Achived : {{round($monthtargetachive,2)}} </span>
                <span> Commision : {{round($monthcommision,2)}} </span>
                <span> Paid : {{round($monthpaid,2)}} </span>
              </div>
              <span class="info-box-number"></span>
              </a> 
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
          <div class="info-box-content">
            <a href="{{ route('quote') }}">
              <span class="info-box-text">Quote
              <img src="{{ asset('images/Qoute.svg') }}" class="info-icon"> 
              </span>
              <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
              </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        @if(optional($permission)->customer_view == 'view' || optional($cor_permission)->customer_view == 'view')

          <div class="col-md-3 col-sm-6 col-xs-12  ">
            <div class="info-box">
            <div class="info-box-content">
                <a href="{{ route('customer.index') }}">
                <span class="info-box-text">Customer
                <img src="{{ asset('images/customer.svg') }}" class="info-icon"> 
                </span>
                <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
                </a>
              </div>
            </div>
          </div>

        @endif

        @if( count($permission_staffs) > 0 && isset($permission_staffs))

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <div class="info-box-content">
              <a href="{{ route('manage-task.index') }}">
                <span class="info-box-text">All Task<img src="{{ asset('images/alltask.svg') }}" class="info-icon"> </span>
                <div class="viewcounts">
                  <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
                  <span><a href="{{ route('manage-task.index') }}">Task Calendar</a></span>
                  <span><a href="{{route('manage-staff-task-location_staff')}}">Task Location</a></span>

                </div>
              </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        @endif

        <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
          <div class="info-box-content">
              <a href="{{ route('WorkReport') }}">
              <span class="info-box-text">Work Report
              <img src="{{ asset('images/work-report.svg') }}" class="info-icon"> 
              </span>
                 <div class="viewcounts">
              <?php

  $staff_id = session('STAFF_ID');

     $taskcheck_fullday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Full Day'");

     $taskcheck_halfday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Half Day'");

     $attendence = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND (type_leave='Request Leave Office Staff' or type_leave='Request Leave Field Staff') ");

     $half_day=count($taskcheck_halfday)/2;

   $leave=count($taskcheck_fullday)+$half_day;

    $notupdated=date("d");

    $noupdated_task=$notupdated-count($attendence);

              ?>
              <span> Work (<?php echo count($attendence);?>) </span>

              <span>Leave (<?php echo $leave;?>)</span>

              <span>Not Updated (<?php echo $noupdated_task-$leave;?>)</span>

               </div>

        <span class="info-box-number"><?php //echo App\Product::all()->count();?><span>
              </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        

         <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
            <div class="info-box-content">
              <a href="{{ route('task.create') }}">
              <span class="info-box-text">Create Task
              <img src="{{ asset('images/creat-task.svg') }}" class="info-icon"> 
              </span>
              <div class="viewcounts">
              <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
              </div>  
            </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        
         <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
            <div class="info-box-content">
            <a href="{{ route('task.index') }}">
              <span class="info-box-text">All Task
              <img src="{{ asset('images/all-task.svg') }}" class="info-icon"> 
              </span>
              <div class="viewcounts">
              <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
              </div>  
            </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
            <div class="info-box-content">
              <a href="{{ route('Staffstatus') }}">
              <span class="info-box-text">Staff Status
              <img src="{{ asset('images/staff-status.svg') }}" class="info-icon"> 
              </span>
              <div class="viewcounts">
              <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
              </div>  
            </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>



      <div class="col-md-3 col-sm-6 col-xs-12  ">
        <div class="info-box">
          <div class="info-box-content">
          <a href="{{ route('quicktask') }}">
            <span class="info-box-text">Quick Task
            <img src="{{ asset('images/quick-task.svg') }}" class="info-icon"> 
            </span>
            <div class="viewcounts">
            <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>
            </div>  
          </a>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>




        <div class="col-md-3 col-sm-6 col-xs-12  ">
          <div class="info-box">
              <div class="info-box-content">
              <a href="{{ route('Pendingtransaction') }}">
              <span class="info-box-text">Transaction
              <img src="{{ asset('images/transaction.svg') }}" class="info-icon"> 
              </span>

              <div class="viewcounts">
              <?php
              $staff_id = session('STAFF_ID');
              $transation_pending =  DB::select("select * from  transation_staff_updates where staff_id='".$staff_id."' AND (current_status='Pending' OR current_status='Verification') order by transation_id desc");
  
              ?>
              <span >Total (<?php echo count($transation_pending);?>)</span>
              </div>
              </a>
            </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>

        
      <div class="col-md-3 col-sm-6 col-xs-12  ">
        <div class="info-box">
          <div class="info-box-content">
            <span class="info-box-text">Service
            <img src="{{ asset('images/service.svg') }}" class="info-icon"> 
            </span>
            <div class="viewcounts">
            <?php
                  $staff_id = session('STAFF_ID');
                  if($staff_id == 127 || $staff_id == 34 ){
                    $cr = \App\Models\Service::where('service_type',1)->get();
                    $pm = \App\Models\Service::where('service_type',2)->get();
                    $in = \App\Models\Service::where('service_type',3)->get();  
                  }else{
                  $cr = \App\Models\Service::where('service_type',1)->where('engineer_id',$staff_id)->get();
                  $pm = \App\Models\Service::where('service_type',2)->where('engineer_id',$staff_id)->get();
                  $in = \App\Models\Service::where('service_type',3)->where('engineer_id',$staff_id)->get();
                  }
            ?>
            <span><a href="{{ route('service-create',1) }}">Cr-Repair  [<?php echo count($cr);?>]</a></span>
            <span><a href="{{ route('service-create',2) }}">Pr-Maintenance  [<?php echo count($pm);?>]</a></span>
            <span><a href="{{ route('service-create',3) }}">Installation  [<?php echo count($in);?>]</a></span>    
            </div>
          </div>
          <!-- /.info-box-content -->

        </div>

        <!-- /.info-box -->

        </div>


             
<div class="col-md-3 col-sm-6 col-xs-12  ">

<div class="info-box">
  <div class="info-box-content">
    <a href="{{url('staff/create_oppertunity')}}"><span class="info-box-text">Opportunity
    <img src="{{ asset('images/oppertunity.svg') }}" class="info-icon"> 
    </span></a>

    <div class="viewcounts">

    @if (optional($permission)->opperbio_view == 'view')

    <span><a href="{{url('staff/list_oppertunity')}}?type=bio">List Opportunity (BIO)</a></span>

    @endif

    @if (optional($permission)->opperbec_view == 'view')

    <span><a href="{{url('staff/list_oppertunity')}}?type=bec">List Opportunity (BEC)</a></span>

    @endif

    @if (optional($permission)->oppermsa_view == 'view')

    <span><a href="staff/list_oppertunity')}}?type=msa">List Opportunity (MSA Proposal)</a></span>

    @endif
   
    <span><a href="{{url('staff/list_oppertunity')}}?type=hotProspects">Hot Prospects  [<?php echo number_format($hot_pro,2);  ?>]</a></span>
    <span><a href="{{url('staff/list_oppertunity')}}?type=newProspects">New Prospects  [<?php echo count($last_created);?>]</a></span>
    <span><a href="{{url('staff/list_oppertunity')}}?type=staleProspects">Stale Prospects   [<?php echo count($stale); ?>]</a></span> 
    <span><a href="{{url('staff/list_oppertunity')}}?type=closedProspects">Closed Prospects   [<?php echo number_format($last_created_closed,2); ?>]</a></span>   
    <span><a href="{{url('staff/list_oppertunity')}}?type=otherProspects">Others    [<?php echo number_format($other_opper,2); ?>]</a></span>    
    </div>
  </div>
  <!-- /.info-box-content -->

</div>

<!-- /.info-box -->

</div>


@php

$staff_id = session('STAFF_ID');

$adminA=["33","36",'37',"31"];

$adminB=["39","30","32"];
@endphp


@if(in_array($staff_id,$adminB)||in_array($staff_id,$adminA))
<div class="col-md-3 col-sm-6 col-xs-12  ">

  <div class="info-box">
  <div class="info-box-content">
      <a href="{{route('target.commission.index',["billing_status"=>"New Orders"]) }}">

      <span class="info-box-text">Billing</span>

      <span class="info-box-number"></span>
      </a>
    </div>
    <!-- /.info-box-content -->
  </div>

  <!-- /.info-box -->

  </div>
@endif

@if($staff_id==56)


<div class="col-md-3 col-sm-6 col-xs-12  ">
  <div class="info-box">
    <div class="info-box-content">
    <a href="{{ route('staff.target.index') }}">
      <span class="info-box-text">Staff Sales Target
      <img src="{{ asset('images/sales-target.svg') }}" class="info-icon"> 
      </span>
      <div class="viewcounts">
      <span class="info-box-number"><?php //echo \App\Product::all()->count();?></span>
      </div>
      </a>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>

<div class="col-md-3 col-sm-6 col-xs-12  ">
  <div class="info-box">
    <div class="info-box-content">
    <a href="{{ route('staff.target.commission.index') }}">
      <span class="info-box-text">Staff Sales Commission
      <img src="{{ asset('images/sales.svg') }}" class="info-icon"> 
      </span>
      <span class="info-box-number"><?php //echo \App\Product::all()->count();?></span>
      <span><a href="{{ route('staff.target.report') }}">Report</a></span>
      </a>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>


@if(optional($permission)->report_view == 'view' )

<div class="col-md-3 col-sm-6 col-xs-12  ">
  <div class="info-box">
  <div class="info-box-content">
      <a href="{{ route('staff-report') }}">
      <span class="info-box-text">REPORT
      <img src="{{ asset('images/oppertunity.svg') }}" class="info-icon"> 
      </span>
      <span class="info-box-number"><?php //echo \App\Product::all()->count();?></span>
      </a>
    </div>
  </div>
</div>

@endif


@endif


<div class="col-md-3 col-sm-6 col-xs-12  ">
  <div class="info-box">
    <div class="info-box-content">
    <a href="{{ route('customer_location') }}">
      <span class="info-box-text"> Customer Location
      <img src="{{ asset('images/sales-target.svg') }}" class="info-icon"> 
      </span>
      <div class="viewcounts">
      <span class="info-box-number"><?php //echo \App\Product::all()->count();?></span>
      </div>
      </a>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>

<!-- ***********************************************APP Download**************************** -->
<div class="col-md-3 col-sm-6 col-xs-12  ">
  <div class="info-box">
  <div class="info-box-content">
      @php
      $applatest=\App\Models\AppVersionControll::where("vid","android")->orderBy("id","DESC")->first();
      $vcode = $applatest->code."";
      @endphp
      <a href="{{ route('android.download') }}" target="_blank" download="Beczone-v{{$vcode!=""?$vcode[0]:1}}.apk">

          <span class="info-box-text">Download APK
            <img src="{{ asset('images/download-apk.svg') }}" class="info-icon"> 
          </span>
          <span >Beczone-v{{$vcode!=""?$vcode[0]:1}}.apk</span>
          <span >Version : {{$applatest->version}}</span>
      </a>
      </div>
  </div>
</div>






<!-- ***********************************************APP Download**************************** -->



      </div>

      <!-- /.row -->



 

      <!-- /.row -->

    </section>

    <!-- /.content -->

@endsection



