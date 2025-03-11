<?php
namespace App\Http\Controllers\Staff;

use App\Dailyclosing_expence;
use App\Http\Controllers\Controller;
use App\Models\FreezStaff;
use App\Models\StaffFollower;
use App\Models\StaffTaskTime;
use App\OppertunityTask;
use App\User;
use App\Staff;
use App\StaffCategory;
use App\Task;
use App\Task_comment;
use App\User_permission;
use App\Work_report_for_leave;
use App\Work_report_for_office;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TaskManageController extends Controller
{
    public function index(Request $request)
    {

        $staff_id = session('STAFF_ID');

        // $permission_staffs = User_permission::where('work_update_coordinator',$staff_id)->pluck('staff_id')->unique()->toArray();

        // $permission_staffs = User_permission::query()
        //     ->where(function ($query) use ($staff_id) {

        //         $query->whereNotNull('work_start_coordinator')
        //             ->where('work_start_coordinator', '<>', 'work_update_coordinator')
        //             ->whereNull('approve_task')
        //             ->where('work_update_coordinator', $staff_id)
        //             ->orWhere(function ($q) use ($staff_id) {

        //                 $q->whereNull('work_start_coordinator')
        //                     ->where('work_update_coordinator', $staff_id);
        //             })

        //             ->orWhere(function ($q) use ($staff_id) {
        //                 $q->whereNotNull('work_update_coordinator')
        //                     ->whereNotNull('work_start_coordinator')
        //                     ->where('work_update_coordinator', '<>', 'work_start_coordinator')
        //                     ->where('work_start_coordinator', $staff_id);
        //             });
        //     })
        //     ->pluck('staff_id')
        //     ->unique()
        //     ->toArray();


        $permission_staffs = User_permission::query()
        ->where(function ($query) use ($staff_id) {
            $query->where('work_update_coordinator', $staff_id); 
        })
        ->pluck('staff_id')  
        ->unique()           
        ->toArray();          
    



        if ($request->input('ajax', 'no') == "yes") {

            // $staffsm=Staff::where('id','>',0)->whereIn('id',$permission_staffs)
            //     ->whereIn('staff_category',[
            //     'Staff Service Team',
            //     'Staff Service Support Team',
            //     'Staff Sales Team',
            //     'Staff Sales Support Team',
            //     'Staff Admin Team',
            //     'Outsource Sales Team',
            //     'Outsource Service Team',
            //     'Direct Company Staff'
            // ])->where('status','Y');

            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->whereIn('id', $permission_staffs)->where('status', 'Y');

            $events = [];

            // if(!empty($request->staff_id)||!empty($request->category)) {
            if (!empty($request->staff_id)) {
                $staffsm->where('id', $request->staff_id);
            }
            if (!empty($request->category)) {

                $staffsm->where('category_id', $request->category);
            }
            // foreach ($staffsm->pluck('id') as $staff_id) {
            // $staffs=$staffsm->pluck('id');

            // $staffids=$staffsm->pluck('id');
            $staffcount = $staffsm->count();
            $staffids = $staffsm->select('id');
            foreach (CarbonPeriod::create($request->start, $request->end) as $row) {
                $date = $row->format('Y-m-d');

                $task = Task::whereIn('assigns', $staffids)
                    // ->where(function($qry)use($staffids){
                    //     foreach ($staffids as $id) { 
                    //         $qry->whereRaw("find_in_set($id,assigns)");
                    //     }
                    // })
                    ->where('start_date', $date);
                //  ->where(function($qry)use($date){
                //     $qry->where('start_date',$date);
                //     $qry->orWhere('due_date',$date);
                // });

                $tskcount = $task->count();

                $taskapprove = Task::where('start_date', $date)->where('staff_status', 'Approved')->where('status', '!=', 'Complete')
                    ->whereIn('id', Task_comment::where('status', 'N')->where('added_by', 'staff')->whereIn('added_by_id', $staffids)->whereDate('created_at', $date)->select('task_id'));

                if ($task->where('staff_status', '!=', 'Approved')->count() > 0) {
                    $events[] = [
                        "groupId" => "--------",
                        "title" => 'Task',
                        "backgroundColor" => 'red',
                        "color" => 'red',
                        "start" => $date,
                        "etype" => 'task',
                        "ecategory" => $request->category ?? "",
                        "estatus" => false,
                        "allDay" => false
                    ];
                    $events[] = [
                        "groupId" => "--------",
                        "title" => 'Track',
                        "backgroundColor" => '#4c3770',
                        "color" => '#4c3770',
                        "start" => $date,
                        "etype" => 'track',
                        "ecategory" => $request->category ?? "",
                        "estatus" => false,
                        "allDay" => false
                    ];
                } elseif ($tskcount > 0) {
                    if ($taskapprove->count() > 0) {
                        $events[] = [
                            "groupId" => "--------*",
                            "title" => 'Task',
                            "backgroundColor" => 'red',
                            "color" => 'red',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'task',
                            "estatus" => false,
                            "allDay" => false
                        ];
                    } else {
                        $events[] = [
                            "groupId" => "++++++++",
                            "title" => 'Task',
                            "backgroundColor" => 'green',
                            "color" => 'green',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'task',
                            "estatus" => true,
                            "allDay" => false
                        ];
                    }

                    $events[] = [
                        "groupId" => "--------",
                        "title" => 'Track',
                        "backgroundColor" => '#4c3770',
                        "color" => '#4c3770',
                        "start" => $date,
                        "etype" => 'track',
                        "ecategory" => $request->category ?? "",
                        "estatus" => false,
                        "allDay" => false
                    ];
                }

                if (Work_report_for_leave::whereIn('staff_id', $staffids)->where('start_date', $date)->where('type_leave', 'Request Leave')->where('system_generate_leave', 'N')->count() > 0) {
                    $events[] = [
                        "groupId" => "********",
                        "title" => 'Leave',
                        "backgroundColor" => 'orange',
                        "color" => 'orange',
                        "start" => $date,
                        "ecategory" => $request->category ?? "",
                        "etype" => 'leave',
                        "estatus" => false,
                        "allDay" => false
                    ];
                }

                if (Work_report_for_office::whereIn('staff_id', $staffids)->where('start_date', $date)->count() > 0) {
                    if (
                        Work_report_for_leave::whereIn('staff_id', $staffids)->where('start_date', $date)->where(function ($qry) {
                            $qry->where('type_leave', 'Request Leave Office Staff');
                            $qry->orWhere('type_leave', 'Request Leave Field Staff');
                        })->count() >= $staffcount
                    ) {

                        $events[] = [
                            "groupId" => "++++++++",
                            "title" => 'Attendance',
                            "backgroundColor" => 'green',
                            "color" => 'green',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'attendance',
                            "estatus" => true,
                            "allDay" => false
                        ];
                    } else {

                        $events[] = [
                            "groupId" => "--------",
                            "title" => 'Attendance',
                            "backgroundColor" => 'orange',
                            "color" => 'orange',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'attendance',
                            "estatus" => false,
                            "allDay" => false
                        ];
                    }
                } else {

                    // $events[] = [
                    //     "groupId" => "++++++++",
                    //     "title" => 'No-Task', 
                    //     "backgroundColor" => 'orange',
                    //     "color" => 'orange',
                    //     "start" => $date,
                    //     "etype"=> 'no-task',
                    //     "estatus"=> false,
                    //     "allDay" => false
                    // ];
                }
                $expcnt = Dailyclosing_expence::where('expence_cat', 'expence')->whereIn('staff_id', $staffids)->where('start_date', $date)->count();
                if ($expcnt > 0) {
                    if ($expcnt <= Dailyclosing_expence::where('expence_cat', 'expence')->where('status', 'Y')->whereIn('staff_id', $staffids)->where('start_date', $date)->count()) {
                        $events[] = [
                            "groupId" => "++++++++",
                            "title" => 'Expence',
                            "backgroundColor" => 'green',
                            "color" => 'green',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'expence',
                            "estatus" => true,
                            "allDay" => false
                        ];
                    } else {

                        $events[] = [
                            "groupId" => "--------",
                            "title" => 'Expence',
                            "backgroundColor" => 'yellow',
                            "color" => 'yellow',
                            "start" => $date,
                            "ecategory" => $request->category ?? "",
                            "etype" => 'expence',
                            "estatus" => false,
                            "allDay" => false
                        ];
                    }
                }


            }

            // } 

            // }
            return response()->json($events);
        }

        $staff = Staff::with('staffcategory')->whereIn('id', $permission_staffs)
            ->where('status', 'Y')->get();



        $categoryIds = Staff::whereIn('id', $permission_staffs)
            ->where('status', 'Y')
            ->pluck('category_id')
            ->unique();

        $categories_array = StaffCategory::whereIn('id', $categoryIds)->pluck('id')->toArray();

        $categories = StaffCategory::whereIn('id', $categoryIds)->get();

        $admins = DB::table('admin')->get();

        return view('staff.task_manage.index', compact('staff', 'admins', 'categories', 'categories_array'));
    }

    public function staff_location()
    {
        $staff = Staff::where('status', 'Y')->get();
        $admins = DB::table('admin')->get();
        return view('staff.task_manage.staff_location', compact('staff', 'admins'));
    }
    public function staff_task_time(Request $request)
    {
        if ($request->ajax()) {
            $stafflist = Staff::where('status', 'Y')->whereIn('staff_category', [
                'Staff Service Team',
                'Staff Service Support Team',
                'Staff Sales Team',
                'Staff Sales Support Team',
                'Staff Admin Team',
                'Outsource Sales Team',
                'Outsource Service Team',
                'Direct Company Staff'
            ]);
            if (!empty($request->category)) {
                $stafflist->where('staff_category', $request->category);
            }
            $result = [];
            foreach ($stafflist->get() as $stf) {
                $date = $request->input('date', date('Y-m-d'));
                $travellist = [];
                $totaltravel = 0.0;
                $totaltask = 0.0;
                foreach (Dailyclosing_expence::whereNotIn('travel_task_id', [3446, 3447])->where('travel_parent_id', 0)->where('expence_cat', 'travel')->where("staff_id", $stf->id)->where('start_date', $date)->get() as $travel) {
                    $traveltime = 0;
                    $tasktime = 0;
                    $taskname = "";
                    if (!empty($travel->start_time_travel) && !empty($travel->end_time_travel)) {
                        try {
                            $tr1 = Carbon::createFromFormat('h:i A', $travel->start_time_travel);
                            $tr2 = Carbon::createFromFormat('h:i A', $travel->end_time_travel);
                            $traveltime += $tr1->diffInMinutes($tr2);
                        } catch (\Throwable $th) {

                        }
                    }
                    foreach (Dailyclosing_expence::whereNotIn('travel_task_id', [3446, 3447])->where('travel_parent_id', $travel->id)->where('expence_cat', 'travel')->where("staff_id", $stf->id)->where('start_date', $date)->get() as $ctravel) {
                        if (!empty($ctravel->start_time_travel) && !empty($ctravel->end_time_travel)) {
                            try {
                                $tr1 = Carbon::createFromFormat('h:i A', $ctravel->start_time_travel);
                                $tr2 = Carbon::createFromFormat('h:i A', $ctravel->end_time_travel);
                                $traveltime += $tr1->diffInMinutes($tr2);
                            } catch (\Throwable $th) {

                            }
                        }
                    }
                    $tsk = Task::find($travel->travel_task_id);
                    $taskname .= '<span class="badge badge-secondary">' . (optional($tsk)->name ?? "-") . '</span>';
                    $tm = StaffTaskTime::whereNotNull('start_time')->whereNotNull('end_time')->where('dailyclosing_expence_id', $travel->id)->where('task_id', $travel->travel_task_id)->where('staff_id', $stf->id)->first();
                    if (!empty($tm)) {
                        $tm1 = Carbon::parse($tm->start_time);
                        $tm2 = Carbon::parse($tm->end_time);
                        $tasktime += $tm1->diffInMinutes($tm2);
                    }

                    foreach (explode(",", $travel->travel_task_child_id ?? "") as $tid) {
                        if (!empty($tid)) {
                            $ctask = Task::find($tid);
                            if (!empty($ctask)) {
                                $taskname .= '<span class="badge badge-secondary">' . ($ctask->name ?? "-") . '</span>';
                                $tm = StaffTaskTime::whereNotNull('start_time')->whereNotNull('end_time')->where('dailyclosing_expence_id', $travel->id)->where('task_id', $ctask->id)->where('staff_id', $stf->id)->first();
                                if (!empty($tm)) {
                                    $tm1 = Carbon::parse($tm->start_time);
                                    $tm2 = Carbon::parse($tm->end_time);
                                    $tasktime += $tm1->diffInMinutes($tm2);
                                }
                            }
                        }
                    }
                    $travellist[] = [
                        "taskname" => $taskname,
                        "travelTime" => $traveltime,
                        "taskTime" => $tasktime,
                    ];
                    $totaltravel += $traveltime;
                    $totaltask += $tasktime;
                }
                $result[] = [
                    'staff' => $stf,
                    'travels' => $travellist,
                    'totalTravelTime' => $totaltravel,
                    'totalTaskTime' => $totaltask,
                ];
            }
            return response()->json($result);
        }
    }

    public function staff_task_location(Request $request)
    {
        $staff_id = session('STAFF_ID');

        // $permission_staffs = User_permission::query()
        //     ->where(function ($query) use ($staff_id) {

        //         $query->whereNotNull('work_start_coordinator')
        //             ->where('work_start_coordinator', '<>', 'work_update_coordinator')
        //             ->whereNull('approve_task')
        //             ->where('work_update_coordinator', $staff_id)
        //             ->orWhere(function ($q) use ($staff_id) {

        //                 $q->whereNull('work_start_coordinator')
        //                     ->where('work_update_coordinator', $staff_id);
        //             })

        //             ->orWhere(function ($q) use ($staff_id) {
        //                 $q->whereNotNull('work_update_coordinator')
        //                     ->whereNotNull('work_start_coordinator')
        //                     ->where('work_update_coordinator', '<>', 'work_start_coordinator')
        //                     ->where('work_start_coordinator', $staff_id);
        //             });
        //     })
        //     ->pluck('staff_id')
        //     ->unique()
        //     ->toArray();

            $permission_staffs = User_permission::query()
                ->where(function ($query) use ($staff_id) {
                    $query->where('work_update_coordinator', $staff_id); 
                })
            ->pluck('staff_id')  
            ->unique()           
            ->toArray();   

        if ($request->ajax()) {
            $stafflist = Staff::where('status', 'Y');
            if (!empty($request->category)) {
                $stafflist->where('category_id', $request->category);
            }
            $result = [];
            foreach ($stafflist->get() as $stf) {
                $date = $request->input('date', date('Y-m-d'));
                $travellist = [];
                foreach (Dailyclosing_expence::where('travel_parent_id', 0)->where('expence_cat', 'travel')->where("staff_id", $stf->id)->where('start_date', $date)->get() as $travel) {
                    $travel->staff_task_time = StaffTaskTime::where('dailyclosing_expence_id', $travel->id)->where('task_id', $travel->travel_task_id)->where('staff_id', $stf->id)->first();
                    $travel->childtravel = Dailyclosing_expence::where('travel_parent_id', $travel->id)->where('expence_cat', 'travel')->where("staff_id", $stf->id)->where('start_date', $date)->get();
                    $travel_task_child = [];
                    $childname = '';
                    if (!empty($travel->travel_task_child_id)) {
                        foreach (explode(',', $travel->travel_task_child_id) as $i => $id) {
                            if (!empty($id)) {
                                $tsk = Task::find($id);
                                if (!empty($tsk)) {
                                    $childname .= "," . $tsk->name;
                                    $travel_task_child[] = [
                                        'task_name' => $tsk->name,
                                        'task_id' => $tsk->id,
                                        'staff_task_time' => StaffTaskTime::where('dailyclosing_expence_id', $travel->id)->where('task_id', $tsk->id)->where('staff_id', $stf->id)->first()
                                    ];
                                }
                            }
                        }
                    }
                    $travel->travel_task_childname = trim($childname, ',');
                    $travel->travel_task_child = $travel_task_child;
                    $travellist[] = $travel;
                }
                $stf->travel = $travellist;
                $stf->office_work = Work_report_for_office::where('staff_id', $stf->id)->where('start_date', $date)->get();
                $result[] = $stf;
            }
            return $result;
        }
        $staff = Staff::where('status', 'Y')->get();

        $categoryIds = Staff::whereIn('id', $permission_staffs)
            ->where('status', 'Y')
            ->pluck('category_id')
            ->unique();

        $categories_array = StaffCategory::whereIn('id', $categoryIds)->pluck('id')->toArray();

        $categories = StaffCategory::whereIn('id', $categoryIds)->get();

        $admins = DB::table('admin')->get();
        return view('staff.task_manage.task_location', compact('staff', 'admins', 'categories'));
    }

    public function work_update(Request $request)
    {

        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;

            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');

            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
            } else {
                $staffsm->where('id', 0);
            }
            $staffsm = $staffsm->select('id');
            $staffids = $staffsm->pluck('id')->toArray();
            return DataTables::of(Task::where(function ($qry) use ($staffids) {
                foreach ($staffids as $id) {
                    $qry->orWhereRaw("find_in_set($id,assigns)");
                }
                if (count($staffids) == 0) {
                    $qry->where('id', 0);
                }
            })
                ->where(function ($qry) use ($start, $end) {
                    if ($start == $end) {
                        $qry->whereDate('start_date', Carbon::parse($start)->toDateString());
                    } else {
                        foreach (CarbonPeriod::create($start, $end) as $row) {
                            $date = $row->format('Y-m-d');
                            $qry->orWhere('start_date', $date);
                            // $qry->orWhere('due_date',$date);
                        }
                    }
                })->get())

                //->where('staff_status', '!=', 'Approved') pre code

                ->addColumn('taskname', function ($data) {
                    if (empty($data->service_task_status)) {
                        return $data->name;
                    } else {
                        return "<a class='popup' onclick='popupclick(" . $data->id . ")'>" . $data->name . "</a>";
                    }
                })
                ->addColumn('start', function ($data) {

                    return $data->start_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '';

                })->addColumn('due', function ($data) {

                    return $data->due_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '';

                })
                ->addColumn('client', function ($data) {
                    if ($data->user_id > 0) {
                        $client = User::withTrashed()->find($data->user_id);
                        return $client->business_name;
                    }
                    return "";
                })->addColumn('assignees', function ($data) {
                    $staff_all = explode(',', $data->assigns . "");
                    $staffs = "";
                    foreach ($staff_all as $val_staff) {
                        if ($val_staff > 0) {
                            $staff = Staff::find($val_staff);
                            if ($staff) {
                                $staffs .= $staff->name . '<br>';
                            }
                        }
                    }
                    return $staffs;
                })->addColumn('followers', function ($data) {
                    $staff_all = explode(',', $data->followers . "");
                    $staffs = "";
                    foreach ($staff_all as $val_staff) {
                        if ($val_staff > 0) {
                            $staff = Staff::find($val_staff);
                            if ($staff) {
                                $staffs .= $staff->name . '<br>';
                            }
                        }
                    }
                    return $staffs;
                })
                ->addColumn('tstatus', function ($data) use ($staffids) {
                    if ($data->staff_status == "Approved") {
                        if ($data->status !== 'Complete' && Task_comment::where('status', 'N')->where('task_id', $data->id)->where('added_by', 'staff')->whereIn('added_by_id', $staffids)->count()) {
                            return '<span class="text-danger">Task In Progress </span>';
                        } else {
                            return '<span class="text-success">Task Approved</span>';
                        }
                    } else {
                        return '<span class="text-danger">' . ($data->service_task_status ?? "Task Created") . '</span>';
                    }
                })
                ->rawColumns([
                    'taskname',
                    'start',
                    'due',
                    'assignees',
                    'followers',
                    'tstatus'
                ])
                ->addIndexColumn()
                ->make(true);
        }


    }

    
    public function staff_update(Request $request)
    {

        if ($request->ajax()) {
          
            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');

            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
            } else {
                $staffsm->where('id', 0);
            }
            $staffsm = $staffsm->select('id');
            $staffids = $staffsm->pluck('id')->toArray();

            return DataTables::of(OppertunityTask::where(function ($qry) use ($staffids) {

                if (count($staffids) > 0) {

                    $qry->whereIn('staff_id',$staffids);
                }
                if (count($staffids) == 0) {
                    $qry->where('id', 0);
                }
            })->get())

                ->addColumn('taskname',function($data){
                    
                    if($data->staff_status == 'Approved'){
                        
                        return $data->name;

                    }else{
                        return "<a class='popup' onclick='staffclick(".$data->id.")'>".$data->name."</a>";
                    }
                })
               
                ->addColumn('client', function ($data) {
                    if ($data->user_id > 0) {
                        $client = User::withTrashed()->find($data->user_id);
                        return $client->business_name;
                    }
                    return "";

                })->addColumn('assignees', function ($data) {
                  
                    $staff = Staff::find($data->staff_id);

                    return optional($staff)->name??"";

                })
                ->addColumn('followers', function ($data) {
                    $staff_all = explode(',', $data->followers . "");
                    $staffs = "";
                    foreach ($staff_all as $val_staff) {
                        if ($val_staff > 0) {
                            $staff = Staff::find($val_staff);
                            if ($staff) {
                                $staffs .= $staff->name . '<br>';
                            }
                        }
                    }
                    return $staffs;
                })

                ->addColumn('date_created', function ($data) {
                  
                    $date = $data->created_at->format('d-m-Y');

                    return $date;
                })

                ->addColumn('tstatus', function ($data) {

                    if ($data->staff_status == "Approved") {

                       return '<span class="text-success">Task Approved</span>';

                    } else {

                        return '<span class="text-danger">' . ($data->service_task_status ?? "Task Created") . '</span>';
                    }
                })
                ->rawColumns([
                    'taskname',
                    'assignees',
                    'followers',
                    'tstatus'
                ])
                ->addIndexColumn()
                ->make(true);
        }


    }

    public function work_update_staff_detail(Request $request)
    {
        $this->validate($request, [
            "staff_id" => 'required',
            "freezdate" => 'required'
        ]);
        return response()->json([
            "freez" => FreezStaff::where('staff_id', $request->staff_id)->where('taskdate', $request->freezdate)->first(),
            "attendance" => Work_report_for_leave::where('staff_id', $request->staff_id)->where('start_date', $request->freezdate)->first()
        ]);
    }
    public function work_update_staffs(Request $request)
    {
        $date = $request->date;
        $etype = $request->etype ?? "task";
        $ecategory = $request->ecategory ?? "";
        $status = $request->estatus ?? "n";
        $staffs = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');
        if (!empty($ecategory)) {
            $staffs->where('category_id', $ecategory);
        }
        $result = [];
        switch ($etype) {
            case 'task':
                // if($status=="y"){
                $staffs->whereIn('id', function ($query) use ($date) {
                    $query->select(DB::raw('assigns'))
                        ->from('task')
                        ->whereRaw('FIND_IN_SET(staff.id, task.assigns)')
                        ->where('start_date', $date);
                });
                // ->whereIn('id',      
                // // Task::where('start_date',$date)->select('assigns')
                // );
                // }else{
                //     $staffs->where(function($qry)use($date){
                //         $qry->whereIn('id', function($query) use($date){
                //             $query->select(DB::raw('assigns'))
                //                 ->from('task')
                //                 ->whereRaw('FIND_IN_SET(staff.id, task.assigns)')->where('staff_status','!=','Approved')
                //                 ->where('start_date',$date);
                //         });
                //         $qry->orWhereIn('id',Task_comment::where('status','N')->where('added_by','staff')->whereIn('task_id',Task::where('staff_status','Approved')->where('status','!=','Complete')->where('start_date',$date)->select('id'))->whereDate('created_at',$date)->select('added_by_id'));                     
                //     });
                // }
                foreach ($staffs->get() as $row) {
                    $tasknotcomment = Task::where('start_date', $date)->whereNotIn('id', Task_comment::where('added_by', 'staff')->where('added_by_id', $row->id)->whereDate('created_at', $date)->select('task_id'))->whereRaw("find_in_set(" . $row->id . ",assigns)")->count();
                    $row->task_count_nocomment = $tasknotcomment;
                    $taskcound = Task::where('staff_status', '!=', 'Approved')->whereNotNull('service_task_status')->where('service_task_status', '!=', 'Task Created')->where('start_date', $date)->whereRaw("find_in_set(" . $row->id . ",assigns)")->count();
                    $stafftaskcound = Task::where('staff_status', 'Approved')->where('status', '!=', 'Complete')->whereNotNull('service_task_status')->where('service_task_status', '!=', 'Task Created')->where('start_date', $date)->whereIn('id', Task_comment::where('status', 'N')->where('added_by', 'staff')->where('added_by_id', $row->id)->whereDate('created_at', $date)->select('task_id'))->whereRaw("find_in_set(" . $row->id . ",assigns)")->count();
                    $row->task_count = $taskcound + $stafftaskcound;
                    $alltaskcount = Task::where('start_date', $date)->whereRaw("find_in_set(" . $row->id . ",assigns)")->count();
                    $row->task_count_approve = $alltaskcount - ($taskcound + $stafftaskcound + $tasknotcomment);
                    $result[] = $row;
                }
                break;
            case 'leave':
                $staffs->whereIn('id', Work_report_for_leave::where('start_date', $date)->where('type_leave', 'Request Leave')->where('system_generate_leave', 'N')->select('staff_id'));
                $result = $staffs->get();
                break;
            case 'attendance':
                if ($status == "y") {
                    $staffs->whereIn('id', Work_report_for_leave::where('start_date', $date)->where(function ($qry) {
                        $qry->where('type_leave', 'Request Leave Office Staff');
                        $qry->orWhere('type_leave', 'Request Leave Field Staff');
                    })->select('staff_id'));

                } else {
                    $staffs->whereNotIn('id', Work_report_for_leave::where('start_date', $date)->where(function ($qry) {
                        $qry->where('type_leave', 'Request Leave Office Staff');
                        $qry->orWhere('type_leave', 'Request Leave Field Staff');
                    })->select('staff_id'));
                }
                foreach ($staffs->get() as $row) {
                    $row->attedance_mark = Work_report_for_leave::where('start_date', $date)->where(function ($qry) {
                        $qry->where('type_leave', 'Request Leave Office Staff');
                        $qry->orWhere('type_leave', 'Request Leave Field Staff');
                    })->where("staff_id", $row->id)->count();
                    $result[] = $row;
                }
                break;
            case 'expence':
                if ($status == "y") {
                    $staffs->whereIn('id', Dailyclosing_expence::where('expence_cat', 'expence')->where('status', 'Y')->where('start_date', $date)->select('staff_id'));
                } else {
                    $staffs->whereIn('id', Dailyclosing_expence::where('expence_cat', 'expence')->where('status', '!=', 'Y')->where('start_date', $date)->select('staff_id'));
                }
                $result = $staffs->get();
                break;
            default:
                break;
        }
        return response()->json($result);
    }
    public function manage_task_entry_location(Request $request, $id)
    {
        $res = StaffTaskTime::findOrFail($id);
        return response()->json($res);
    }


    public function work_update_travel(Request $request)
    {

        if ($request->ajax()) {

            $start = $request->start;
            $end = $request->end;
            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');
            $staff_id = 0;
            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
                $staff_id = $request->staff;
            } else {
                $staffsm->where('id', 0);
            }
            return DataTables::of(Dailyclosing_expence::where('expence_cat', 'travel')->whereIn('staff_id', $staffsm->select('id'))->where(function ($qry) use ($start, $end) {
                foreach (CarbonPeriod::create($start, $end) as $row) {
                    $date = $row->format('Y-m-d');
                    $qry->orWhere('start_date', $date);
                }
            })->get())
                ->addColumn('start_date', function ($data) {
                    $icon = "";
                    if (!empty($data->travel_start_latitude)) {
                        $icon = '<a class="location-btn"onclick="show_travellocation(' . "'" . $data->start_time_travel . "'" . ',' . $data->travel_start_latitude . ',' . $data->travel_start_longitude . ',' . "'" . ($data->end_time_travel ?? '') . "'," . ($data->travel_end_latitude ?? 'null') . ',' . ($data->travel_end_longitude ?? 'null') . ')" title="Location" ><img src="' . asset('images/location.svg') . '"></a>';
                    }
                    return $data->start_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '' . " $icon";
                })
                ->addColumn('taskname', function ($data) use ($staff_id) {
                    $taskname = "";
                    $timestring = "";
                    $tasktime = StaffTaskTime::where('dailyclosing_expence_id', $data->id)->where('task_id', $data->travel_task_id)->where('staff_id', $staff_id)->first();
                    if (!empty($tasktime)) {
                        // if(!empty($tasktime->start_time)){
                        //     $timestring.="Started: ".Carbon::parse($tasktime->start_time)->format('d-m-Y h:i a') ." ";
                        // }
                        // if(!empty($tasktime->end_time)){
                        //     $timestring.="Ended: ".Carbon::parse($tasktime->end_time)->format('d-m-Y h:i a') ." ";
                        // }
    
                        $timestring = '<a onclick="locationverify(' . "'" . route('staff.manage-task-entry-location', $tasktime->id) . "'" . ')"><i class="fa fa-eye"></i></a>';
                    }
                    if (in_array($data->travel_task_id, [3446, 3447])) {
                        $taskname .= $data->task_name . " [ " . $timestring . " ]" . "<br>";
                    } else {
                        $taskname .= "<a class='popup' onclick='popupclick(" . $data->travel_task_id . ")'>" . $data->task_name . " </a> &nbsp;&nbsp; " . $timestring . "<br>";
                    }
                    if (!empty($data->travel_task_child_id)) {
                        $namelist = explode(',', $data->travel_task_child_name);
                        foreach (explode(',', $data->travel_task_child_id) as $i => $id) {

                            $timestring = "";
                            $tasktime = StaffTaskTime::where('dailyclosing_expence_id', $data->id)->where('task_id', $id)->where('staff_id', $staff_id)->first();
                            if (!empty($tasktime)) {
                                // if(!empty($tasktime->start_time)){
                                //     $timestring.="Started: ".Carbon::parse($tasktime->start_time)->format('d-m-Y h:i a') ." ";
                                // }
                                // if(!empty($tasktime->end_time)){
                                //     $timestring.="Ended: ".Carbon::parse($tasktime->end_time)->format('d-m-Y h:i a') ." ";
                                // }
    
                                $timestring = '<a onclick="locationverify(' . "'" . route('staff.manage-task-entry-location', $tasktime->id) . "'" . ')"><i class="fa fa-eye"></i></a>';
                            }
                            if (in_array($id, [3446, 3447])) {
                                $taskname .= ($namelist[$i] ?? "") . " [ " . $timestring . " ]" . "<br>";
                            } else {
                                $taskname .= "<a class='popup' onclick='popupclick(" . $id . ")'>" . ($namelist[$i] ?? "") . "  </a> &nbsp;&nbsp; " . $timestring . "<br>";
                            }
                        }
                    }

                    return $taskname;
                })
                ->addColumn('startreading', function ($data) {
                    if (strtolower($data->travel_type) == "car" || strtolower($data->travel_type) == "bike") {
                        return $data->start_meter_reading ?? 0;
                    } else {
                        return "N/A";
                    }
                })
                ->addColumn('endreading', function ($data) {
                    if (strtolower($data->travel_type) == "car" || strtolower($data->travel_type) == "bike") {
                        return $data->end_meter_reading ?? 0;
                    } else {
                        return "N/A";
                    }
                })
                ->addColumn('travelkm', function ($data) {

                    $start_meter = (int) $data->start_meter_reading;
                    $end_meter = (int) $data->end_meter_reading;
                    return $end_meter - $start_meter;
                    //    return intval($data->end_meter_reading-$data->start_meter_reading);
                })
                ->addColumn('travelamount', function ($data) {
                    $tot_price = 0;
                    switch (strtolower($data->travel_type)) {
                        case 'bike':
                            $today_time = strtotime("2022-06-01");
                            if (strtotime($data->start_date) < $today_time) {
                                $bike_rate = setting('BIKE_RATE_BEFORE_MAY');
                            } else {
                                $bike_rate = setting('BIKE_RATE');
                            }
                            $total_meter = intval($data->end_meter_reading) - intval($data->start_meter_reading);
                            $tot_price = $total_meter * intval($bike_rate);
                            break;
                        case 'car':
                            $total_meter = intval($data->end_meter_reading) - intval($data->start_meter_reading);
                            $tot_price = $total_meter * 5;

                        default:
                            $tot_price = $data->travel_end_amount;
                            break;
                    }
                    return $tot_price;
                })
                ->addColumn('travelstartimage', function ($data) {
                    if (empty($data->travel_start_image)) {
                        return "";
                    } else {
                        return '<a href="' . asset('public/storage/comment/' . $data->travel_start_image) . '" class="view-img"><object data="' . asset('public/storage/comment/' . $data->travel_start_image) . '" width="50" height="50"></object> View</a>';
                    }
                })
                ->addColumn('travelendimage', function ($data) {
                    if (empty($data->travel_end_image)) {
                        return "";
                    } else {
                        return '<a href="' . asset('public/storage/comment/' . $data->travel_end_image) . '" class="view-img"><object data="' . asset('public/storage/comment/' . $data->travel_end_image) . '" width="50" height="50"></object> View</a>';
                    }
                })
                ->addColumn('action', function ($data) {

                    return ' 
                    <a class="edit-btn"onclick="edit_travel(\'' . $data->id . '\',\'' . $data->travel_type . '\',\'' . intval($data->start_meter_reading) . '\',\'' . intval($data->end_meter_reading) . '\',\'' . $data->travel_end_amount . '\')" title="Edit" ><img src="' . asset('images/edit.svg') . '"></a>
                    <a class="delete-btn"  onclick="delete_travel(\'' . $data->id . '\',\'' . $data->travel_type . '\',\'' . intval($data->start_meter_reading) . '\',\'' . intval($data->end_meter_reading) . '\',\'' . $data->travel_end_amount . '\')" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                })
                ->rawColumns([
                    'taskname',
                    'startreading',
                    'endreading',
                    'travelstartimage',
                    'travelendimage',
                    'action',
                    'start_date'
                ])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function work_update_office(Request $request)
    {

        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;
            // $staffsm = Staff::where('id', '>', 0)->whereIn('staff_category', [
            //     'Staff Service Team',
            //     'Staff Service Support Team',
            //     'Staff Sales Team',
            //     'Staff Sales Support Team',
            //     'Staff Admin Team',
            //     'Outsource Sales Team',
            //     'Outsource Service Team',
            //     'Direct Company Staff'
            // ])->where('status', 'Y');

            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');

            $staff_id = 0;
            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
                $staff_id = $request->staff;
            } else {
                $staffsm->where('id', 0);
            }
            return DataTables::of(Work_report_for_office::whereIn('staff_id', $staffsm->select('id'))->where(function ($qry) use ($start, $end) {
                foreach (CarbonPeriod::create($start, $end) as $row) {
                    $date = $row->format('Y-m-d');
                    $qry->orWhere('start_date', $date);
                }
            })->get())
                ->addColumn('taskname', function ($data) use ($staff_id) {
                    $taskname = "";
                    if (in_array($data->task_id, [3446, 3447])) {
                        $taskname .= $data->task_name . "<br>";
                    } else {
                        $taskname .= "<a class='popup' onclick='popupclick(" . $data->task_id . ")'>" . $data->task_name . " </a> <br>";
                    }
                    if (!empty($data->task_child_id)) {
                        $namelist = explode(',', $data->task_child_name);
                        foreach (explode(',', $data->task_child_id) as $i => $id) {
                            if (in_array($id, [3446, 3447])) {
                                $taskname .= ($namelist[$i] ?? "") . "<br>";
                            } else {
                                $taskname .= "<a class='popup' onclick='popupclick(" . $id . ")'>" . ($namelist[$i] ?? "") . "  </a> <br>";
                            }
                        }
                    }
                    return $taskname;
                })

                ->addColumn('action', function ($data) {
                    $action = "";
                    if (!empty($data->start_latitude)) {
                        $action = '<a class="location-btn"onclick="show_travellocation(' . "'" . $data->start_time . "'" . ',' . $data->start_latitude . ',' . $data->start_longitude . ',' . "'" . ($data->end_time ?? '') . "'," . ($data->end_latitude ?? 'null') . ',' . ($data->end_longitude ?? 'null') . ')" title="Location" ><img src="' . asset('images/location.svg') . '"></a>';
                    }
                    return $action;
                })
                ->rawColumns([
                    'taskname',
                    'action',
                ])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function work_update_expence(Request $request)
    {

        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;
            // $staffsm = Staff::where('id', '>', 0)->whereIn('staff_category', [
            //     'Staff Service Team',
            //     'Staff Service Support Team',
            //     'Staff Sales Team',
            //     'Staff Sales Support Team',
            //     'Staff Admin Team',
            //     'Outsource Sales Team',
            //     'Outsource Service Team',
            //     'Direct Company Staff'
            // ])->where('status', 'Y');

            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');

            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
            } else {
                $staffsm->where('id', 0);
            }
            return DataTables::of(Dailyclosing_expence::where('expence_cat', 'expence')->whereIn('staff_id', $staffsm->select('id'))->where(function ($qry) use ($start, $end) {
                foreach (CarbonPeriod::create($start, $end) as $row) {
                    $date = $row->format('Y-m-d');
                    $qry->orWhere('start_date', $date);
                }
            })->get())
                ->addColumn('taskname', function ($data) {
                    return $data->task_name;
                    // if(in_array($data->travel_task_id,[3446,3447])){
                    //     return $data->task_name;
                    // }else{
                    //     return "<a class='popup' onclick='popupclick(".$data->travel_task_id.")'>".$data->task_name."</a>";
                    // }
                })

                ->addColumn('start_date', function ($data) {

                    return $data->start_date  ? Carbon::parse($data->start_date)->format('d-m-Y') : '';
                })

                ->addColumn('travelstartimage', function ($data) {
                    if (empty($data->travel_start_image)) {
                        return "";
                    } else {
                        return '<a href="' . asset('public/storage/comment/' . $data->travel_start_image) . '" class="view-img"><object data="' . asset('public/storage/comment/' . $data->travel_start_image) . '" width="50" height="50"></object> View</a>';
                    }
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == "Reject") {
                        return '<span  style="color:red">Reject</span>';
                    }
                    if ($data->status == "Y") {
                        return '<span  style="color:green">Approved</span>';
                    }
                    return '<a onclick="updatedexpenceStatus(' . "'" . route('staff.manage-task.show', ['task_manage' => 'work-update-expence-detail', 'expence' => $data->id]) . "'" . ')" data-s="' . $data->status . '"><span  style="color:red">Pending</span></a>';
                })
                ->addColumn('action', function ($data) {
                    return '
                    <!-- <a class="view-btn"  title="View" onclick="updatedexpenceStatus(' . "'" . route('staff.manage-task.show', ['task_manage' => 'work-update-expence-detail', 'expence' => $data->id]) . "'" . ')">Udate Status</a> -->
                    
                    <a class="edit-btn" onclick="edit_expence(\'' . $data->id . '\',\'' . $data->travel_type . '\',\'' . $data->travel_start_amount . '\',\'' . $data->expence_desc . '\')" title="Edit" ><img src="' . asset('images/edit.svg') . '"></a>
                    <a class="delete-btn" onclick="delete_expence(\'' . $data->id . '\',\'' . $data->travel_type . '\',\'' . $data->travel_start_amount . '\',\'' . $data->expence_desc . '\')" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>
                    ';
                })
                ->rawColumns([
                    'taskname',
                    'travelstartimage',
                    'status',
                    'action'
                ])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function work_update_attendance(Request $request)
    {

        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;
            // $staffsm = Staff::where('id', '>', 0)->whereIn('staff_category', [
            //     'Staff Service Team',
            //     'Staff Service Support Team',
            //     'Staff Sales Team',
            //     'Staff Sales Support Team',
            //     'Staff Admin Team',
            //     'Outsource Sales Team',
            //     'Outsource Service Team',
            //     'Direct Company Staff'
            // ])->where('status', 'Y');

            $staffsm = Staff::with('staffcategory')->where('id', '>', 0)->where('status', 'Y');
            
            if (!empty($request->staff)) {
                $staffsm->where('id', $request->staff);
            } else {
                $staffsm->where('id', 0);
            }
            return DataTables::of(Work_report_for_leave::whereIn('staff_id', $staffsm->select('id'))->where(function ($qry) use ($start, $end) {
                foreach (CarbonPeriod::create($start, $end) as $row) {
                    $date = $row->format('Y-m-d');
                    $qry->orWhere('start_date', $date);
                }
            })->get())
                ->addColumn('marked', function ($data) {
                    // if(Work_report_for_office::where('staff_id', $data->staff_id)->where('start_date', $data->start_date)->count()>0){
                    //     if($data->type_leave=="Request Leave Office Staff"||$data->type_leave=="Request Leave Field Staff"){
                    //         return $data->attendance;
                    //     }else{
                    //         return $data->attendance;
                    //     }
                    // }else{
                    //     if($data->system_generate_leave=="N"&&$data->type_leave=="Request Leave"){
                    //         return  "Leave"; 
                    //     } 
                    // } 
                  
                    if ($data->system_generate_leave == "N") {
                        $pre = "";
                        if ($data->type_leave == "Request Leave Office Staff" || $data->type_leave == "Request Leave Field Staff") {
                            $pre .= 'Attendance ';
                        }
                        if ($data->type_leave == "Request Leave") {
                            $pre .= "Leave ";
                        }
                        return $pre . $data->attendance;
                    }
                    return "Not Marked";
                })

                ->addColumn('start_date', function ($data) {

                    return $data->start_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '';
                })

                ->addColumn('action', function ($data) {
                    $type = "office";
                    if ($data->type_leave == "Request Leave Field Staff") {
                        $type = "Field";
                    }
                    if ($data->system_generate_leave == "N") {
                        return '<a onclick="viewattendence_popup(' . "'" . $data->start_date . "','$type','" . $data->attendance . "','" . $data->type_leave . "'" . ')">Edit Attendence</a>';
                    } else {
                        return '<a class="text-danger" onclick="unlock_attendence(' . "'" . $data->start_date . "','$type','" . $data->attendance . "','" . $data->type_leave . "'" . ')">Unlock Attendence</a>';
                    }
                })
                ->rawColumns([
                    'action'
                ])
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function work_update_expence_detail(Request $request)
    {
        $this->validate($request, [
            'expence' => 'required',
        ]);
        return response()->json(Dailyclosing_expence::find($request->expence ?? 0));
    }
    public function show(Request $request, $task_manage)
    {
        switch ($task_manage) {
            case 'work-update':
                return $this->work_update($request);
            case 'staff_update':
                return $this->staff_update($request);
            case 'work-update-staffs':
                return $this->work_update_staffs($request);
            case 'work-update-staff-detail':
                return $this->work_update_staff_detail($request);
            case 'work-update-travel':
                return $this->work_update_travel($request);
            case 'work-update-office':
                return $this->work_update_office($request);
            case 'work-update-expence':
                return $this->work_update_expence($request);
            case 'work-update-expence-detail':
                return $this->work_update_expence_detail($request);
            case 'work-update-attendance':
                return $this->work_update_attendance($request);
            default:
                return abort('404');
        }
    }
    public function work_update_expence_update(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'expence' => 'required',
        ]);
        $expence = Dailyclosing_expence::findOrFail($request->expence ?? 0);
        $expence->status = $request->status;
        $expence->save();
        if (!empty($request->update)) {
            $this->validate($request, [
                'status' => 'required',
                'expence' => 'required',
            ]);
        }
        return response()->json(['success' => 'status updated']);

    }
    public function work_update_staff_detail_change(Request $request)
    {
        $this->validate($request, [
            "staff_id" => 'required',
            "freezdate" => 'required'
        ]);
        $staff = FreezStaff::where('staff_id', $request->staff_id)->where('taskdate', $request->freezdate)->first();
        if (empty($staff)) {
            $staff = new FreezStaff;
            $staff->staff_id = $request->staff_id;
            $staff->taskdate = $request->freezdate;
        }
        $staff->status = ($request->freezstatus ?? "N") == "Y" ? "Y" : "N";
        $staff->save();
        return response()->json(["success" => "updated"]);
    }
}