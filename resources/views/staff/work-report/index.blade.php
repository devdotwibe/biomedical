@extends('staff/layouts.app')
@section('title', 'Work Report')
@section('content')
    <section class="content-header">
        <h1>Work Report <span id="work_date_dis"></span> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Work Report </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 workreport">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                @if (session('success'))
                                    <div class="alert alert-success alert-block fade in alert-dismissible show">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ session('success') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" readonly value="{{ date('F') }}"
                                        class="form-control text-center">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="viewcounts">
                                    <span> Work ({{ $attendence }}) </span>
                                    <span> Leave ({{ $leave }})</span>
                                    <span> Not Updated ({{ $notupdated }})</span>
                                </div>
                            </div>
                        </div>
                        <div class="row month_dispaly">
                            @php
                                $date = date('Y-m-d', strtotime('11 days')); //today date
                            @endphp
                            @for ($i = 1; $i <= 44; $i++)
                                @if (strtotime($cur_date) == strtotime($date))
                                    <div class="panel panel-default col-md-3 col-sm-6 col-lg-3 work-green" onclick="change_date('{{route('staff.work-report.show',$date) }}')">
                                        <div class="panel-body">{{ $date }}</div>
                                    </div>
                                @elseif(strtotime($cur_date) < strtotime($date))
                                    <div class="panel panel-default col-md-3 col-sm-6 col-lg-3 " onclick="change_date('{{route('staff.work-report.show',$date) }}')">
                                        <div class="panel-body">{{ $date }} @if (date('D', strtotime($date)) == 'Sun')
                                                (Sunday)
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $work_status = 'work-yellow';
                                        $status_date = '';

                                        $wr_attendence1 = \App\Work_report_for_office::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->count();
                                        $wr_attendence2 = \App\Work_report_for_leave::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->whereIn('type_leave', [
                                                'Request Leave Office Staff',
                                                'Request Leave Field Staff',
                                            ])
                                            ->count();
                                        $wr_fullday = \App\Work_report_for_leave::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('type_leave', 'Request Leave')
                                            ->where('attendance', 'Full Day')
                                            ->count();
                                        $wr_halfday = \App\Work_report_for_leave::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('type_leave', 'Request Leave')
                                            ->where('attendance', 'Half Day')
                                            ->count();
                                        $wr_travel = \App\Dailyclosing_expence::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('travel_task_id', '>', 0)
                                            ->count();
                                        $wr_travel_pending = \App\Dailyclosing_expence::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('travel_task_id', '>', 0)
                                            ->whereNotIn('travel_task_id', [0, 3446, 3447])
                                            ->whereIn(
                                                'travel_task_id',
                                                \App\Task_comment::whereIn('status', ['N', 'R'])->select('task_id'),
                                            )
                                            ->count();
                                        $wr_travel_rejected = \App\Dailyclosing_expence::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('travel_task_id', '>', 0)
                                            ->whereNotIn('travel_task_id', [0, 3446, 3447])
                                            ->whereIn(
                                                'travel_task_id',
                                                \App\Task_comment::where('status', 'R')->select('task_id'),
                                            )
                                            ->count();
                                        $wr_office_pending = App\Work_report_for_office::where('staff_id', $staff_id)
                                            ->where('start_date', \Carbon\Carbon::parse($date)->toDateString())
                                            ->where('task_id', '>', 0)
                                            ->whereNotIn('task_id', [0, 3446, 3447])
                                            ->whereIn(
                                                'task_id',
                                                \App\Task_comment::whereIn('status', ['N', 'R'])->select('task_id'),
                                            )
                                            ->count();
                                        if ($wr_fullday > 0) {
                                            $status_date = 'Leave';
                                        } elseif ($wr_halfday > 0) {
                                            $status_date = 'Leave. ';
                                            if ($wr_attendence1 == 0 && $wr_attendence2 == 0) {
                                                $status_date .= 'Task Not Added';
                                            } else {
                                                if ($wr_travel_pending > 0 || $wr_office_pending > 0) {
                                                    if ($wr_attendence2 > 0) {
                                                        $status_date .= 'Attendance  Added';
                                                    } else {
                                                        $status_date .= 'Approval Pending';
                                                    }
                                                } elseif ($wr_attendence2 == 0) {
                                                    $status_date .= 'Attendance Not Added';
                                                } else {
                                                    $status_date .= 'Attendance  Added';
                                                }
                                            }
                                        } else {
                                            if ($wr_attendence1 == 0 && $wr_attendence2 == 0) {
                                                $status_date = 'Task Not Added';
                                            } else {
                                                if ($wr_travel_pending > 0 || $wr_office_pending > 0) {
                                                    if ($wr_attendence2 > 0) {
                                                        $status_date .= 'Attendance  Added';
                                                    } else {
                                                        $status_date .= 'Approval Pending';
                                                    }
                                                } elseif ($wr_attendence2 == 0) {
                                                    $status_date = 'Attendance Not Added';
                                                } else {
                                                    $status_date = 'Attendance  Added';
                                                }
                                            }
                                        }

                                        if ($wr_travel == 0) { 
                                            if ($wr_attendence2 > 0) {
                                                $work_status = 'work-grey';
                                            } else {
                                                $work_status = 'work-yellow';
                                            }
                                        } else { 
                                            if ($wr_travel_rejected > 0) {
                                                $work_status = 'work-red';
                                            } else { 
                                                if ($wr_travel_pending > 0 || $wr_office_pending > 0) {
                                                    $work_status = 'work-yellow';
                                                } else {
                                                    $work_status = 'work-grey';
                                                }
                                            }
                                        }

                                    @endphp
                                    <div class="panel panel-default col-md-3 col-sm-6 col-lg-3 {{ $work_status }}" onclick="change_date('{{route('staff.work-report.show',$date) }}')">
                                        <div class="panel-body">{{ $date }} @if (date('D', strtotime($date)) == 'Sun') (Sunday)  @endif <br> {{ $status_date }} </div>
                                    </div>
                                @endif

                                @php
                                    $date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
                                @endphp
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
<script>
    function change_date(url){
        window.location.href=url;
    }
</script>
@endsection