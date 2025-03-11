<?php



namespace App\Http\Controllers\staff;

use App\Brand;
use App\Models\StaffFollower;

use Illuminate\Http\Request;

use App\Http\Controllers\admin\CmsController;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;



use Image;

use Storage;

use URL;

use App\Http\Controllers\ThumbImage;

use App\Contact_person;

use Imagick;

use App\Category;

use App\Product_image;

use App\Quote;

use Validator;

use App\State;

use App\Taluk;

use App\User;

use App\Country;

use App\District;

use App\Task;

use App\Staff;

use App\Service;

use App\ServicePart;

use App\Relatedto_subcategory;

use App\Task_comment;

use App\Task_comment_replay;

use App\Product;
use App\Oppertunity;


use App\Dailyclosing;
use App\Dailyclosing_expence;
use App\Models\StaffTaskTime;
use App\Msp;
use App\Quotehistory;
use App\Rules\EmailExists;
use App\User_permission;
use App\Work_report_for_office;
use Carbon\Carbon;
use Input;

class AdminStaffController extends Controller
{

    public function view_task_comment_staff(Request $request)
    {

        /*

        $task = DB::table('task_comment')

                ->where('task_id', [$request->task_id])

            ->get();

   $task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$request->task_id."' order by id asc");

   //$update= DB::table('task_comment')->where('task_id',$request->task_id)->update(['admin_view' => "Y"]);

       

   //print_r($alltask);

   // echo json_encode($task);

      echo json_encode($task).'*'.json_encode($task_replay);



   

*/

        $task = Task_comment::with('taskCommentServiceParts.servicePartProduct')->where('task_id', $request->task_id)->get();
        $task_replay = Task_comment_replay::where('task_id', $request->task_id)->orderBy('id', 'ASC')->get();
        $task_details = Task::where('id', $request->task_id)->get();


        $staff_names = [];
        ;
        $adminassigns = [];
        foreach ($task_details as $row) {
            $staff_names = array_merge($staff_names, explode(",", $row->assigns));
        }
        foreach ($staff_names as $val_staff) {

            if (StaffFollower::where('staff_id', $val_staff)->exists()) {
                array_push($adminassigns, StaffFollower::where('staff_id', $val_staff)->first()->follower_id);
            }

        }

        echo json_encode($task) . '|*|' . json_encode($task_replay) . '|*|' . json_encode($task_details) . '|*|' . json_encode($adminassigns);



    }


    public function view_task_comment(Request $request)
    {

        /*

        $task = DB::table('task_comment')

                ->where('task_id', [$request->task_id])

            ->get();

   $task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$request->task_id."' order by id asc");

   //$update= DB::table('task_comment')->where('task_id',$request->task_id)->update(['admin_view' => "Y"]);

       

   //print_r($alltask);

   // echo json_encode($task);

      echo json_encode($task).'*'.json_encode($task_replay);



   

*/

        $task = Task_comment::with('taskCommentServiceParts.servicePartProduct')->where('task_id', $request->task_id)->get();
        $task_replay = Task_comment_replay::where('task_id', $request->task_id)->orderBy('id', 'ASC')->get();
        $task_details = Task::where('id', $request->task_id)->get();


        $staff_names = [];
        ;
        $adminassigns = [];
        foreach ($task_details as $row) {
            $staff_names = array_merge($staff_names, explode(",", $row->assigns));
        }
        foreach ($staff_names as $val_staff) {

            if (StaffFollower::where('staff_id', $val_staff)->exists()) {
                array_push($adminassigns, StaffFollower::where('staff_id', $val_staff)->first()->follower_id);
            }

        }

        echo json_encode($task) . '|*|' . json_encode($task_replay) . '|*|' . json_encode($task_details) . '|*|' . json_encode($adminassigns);



    }


    public function add_task_replay_comment_staff(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $staff = Staff::find($staff_id);

        $cur_date = date('Y-m-d');

        $task_comment = Task_comment::find($request->task_comment_id);

        $task_comment->status = $request->status == "Y" ? "Y" : "R";

        $task_comment->save();


        $task_comment_replay = new Task_comment_replay;

        $task_comment_replay->task_id = $request->task_id;

        $task_comment_replay->comment = $request->replay_comment;

        $task_comment_replay->task_comment_id = $request->task_comment_id;

        $task_comment_replay->parent_id = $request->parent_id;

        $task_comment_replay->added_by = $staff->name;

        $task_comment_replay->added_by_id = $staff_id;

        $task_comment_replay->save();



        if ($request->parent_id > 0) {

            $task_comment_replay_update = Task_comment_replay::find($request->parent_id);

            $task_comment_replay_update->replay_status = "Y";

            $task_comment_replay_update->save();

        }
        $task = Task::find($request->task_id);
        if ($request->status == "Y") {

            $task->status = "In Progress";
            $task->staff_status = "Approved";
            $task_comment_replay->added_by = $staff->name;
            $task->service_task_status = 'Task Approved';

        } else {

            $task->status = "In Progress";
            $task->staff_status = "Pending";
            $task_comment_replay->added_by = $staff->name;
            $task->service_task_status = 'Task Rejected';

        }
        // Task::where('id',$request->task_id)->update(['service_task_status' => 'Task Updated']);



        $task->save();

        $task_comment_replay->save();

        if (!empty($task->service_id)) {

            if ($request->status == "Y") {
                Service::where('id', $task->service_id)->update(['status' => 'Open']);
                ServicePart::where('task_comment_id', $request->task_comment_id)->update(['status' => 'Approved']);
            }

        }

        // $update= DB::table('dailyclosing_details')->where('staff_id',$staff_id)->where('start_date',$cur_date)->update(['approved_fair' => 'Pending','approved_work' => 'Pending']);



        $update = DB::table('task_comment')->where('task_id', $request->task_id)->update(['admin_view' => "Y"]);

    }



    public function view_task_details_staff(Request $request)
    {

        $task = DB::table('task')

            ->where('id', [$request->id])

            ->get();



        if ($task[0]->user_id > 0) {

            $contact_person = DB::table('contact_person')

                ->where('user_id', [$task[0]->user_id])

                ->get();

        } else {

            $contact_person = array();

        }





        $alltask = array();
        $alltask_name = '';
        foreach ($task as $item) {

            $alltask[] = $item->name;

            $alltask[] = $item->description;

            $alltask[] = $item->created_at;

            $alltask[] = $item->start_date;

            $alltask[] = $item->due_date;

            $alltask[] = $item->priority;


            $admin_followe_name = "";

            if ($item->assigns > 0) {

                $staff_names = explode(",", $item->assigns);
                if (count($staff_names) > 1) {
                    foreach ($staff_names as $val_staff) {

                        $staff = Staff::find($val_staff);

                        if ($staff) {
                            if (StaffFollower::where('staff_id', $staff->id)->exists()) {
                                $folowstaff = Staff::find(StaffFollower::where('staff_id', $staff->id)->first()->follower_id);
                                $admin_followe_name .= $staff->name . " - " . $folowstaff->name . ' ,';
                            }
                            $alltask_name .= $staff->name . ',';
                        }

                    }
                } else {
                    $staff = Staff::find($item->assigns);

                    if ($staff) {
                        if (StaffFollower::where('staff_id', $staff->id)->exists()) {
                            $folowstaff = Staff::find(StaffFollower::where('staff_id', $staff->id)->first()->follower_id);
                            $admin_followe_name .= $staff->name . " - " . $folowstaff->name . ' ,';
                        }
                    }
                    $alltask_name .= $staff->name . ',';
                }



            }

            $alltask[] = substr($alltask_name, 0, -1);

            if ($admin_followe_name != "") {
                $admin_followe_name = "Assigned : " . substr($admin_followe_name, 0, -1);
            }
            if ($item->followers > 0) {

                $follower = Staff::find($item->followers);



                $alltask[] = $follower->name;

            }

            $alltask[] = $item->user_id;
            $alltask[] = $admin_followe_name;





        }

        //print_r($alltask);

        echo json_encode($alltask) . '*' . json_encode($contact_person);

    }

    public function save_expence_edit_details_staff(Request $request)
    {
        $update = DB::table('dailyclosing_expence')->where('id', $request->expence_id)->update(['travel_type' => $request->other_expence_edit, 'travel_start_amount' => $request->expence_amount_edit, 'expence_desc' => $request->expence_desc_edit]);
    }



    public function save_travel_edit_details_staff(Request $request)
    {
        if ($request->req_type == "attendence") {
            if ($request->leave_type == "Leave") {
                $update = DB::table('work_report_for_leave')->where('staff_id', $request->staff_id)->where('start_date', $request->leave_date)->update(['type_leave' => 'Request Leave', 'attendance' => $request->leave]);
            }
            if ($request->leave_type == "Attendence") {
                if ($request->type_staff == "Field") {
                    $update = DB::table('work_report_for_leave')->where('staff_id', $request->staff_id)->where('start_date', $request->leave_date)->update(['attendance' => $request->leave, 'type_leave' => 'Request Leave Field Staff']);
                } else {
                    $update = DB::table('work_report_for_leave')->where('staff_id', $request->staff_id)->where('start_date', $request->leave_date)->update(['attendance' => $request->leave, 'type_leave' => 'Request Leave Office Staff']);
                }
            }
        }
        if ($request->req_type == "travel") {
            $update = DB::table('dailyclosing_expence')->where('id', $request->travel_id)->update(['travel_type' => $request->travel_type, 'start_meter_reading' => $request->start_meter_reading, 'end_meter_reading' => $request->end_meter_reading, 'travel_end_amount' => $request->travel_amount]);
        }
        if ($request->req_type == "leavetravel") {
            DB::table('dailyclosing_expence')->where('id', '=', $request->id)->delete();
        }
    }


    public function delete_admin_generate_leave_staff(Request $request)
    {
        DB::table('work_report_for_leave')->where('staff_id', '=', $request->staff_id)
            ->where('start_date', '=', $request->leave_date)
            ->where('type_leave', '=', $request->cur_leavetype)
            ->delete();
    }


    public function staff_task_location_staff(Request $request)
    {
        $staff_id = session('STAFF_ID');

        // $permission_staffs = User_permission::where('work_update_coordinator',$staff_id)->pluck('staff_id')->unique()->toArray();

        $permission_staffs = User_permission::query()
            ->where(function ($query) use ($staff_id) {

                $query->whereNotNull('work_start_coordinator')
                    ->where('work_start_coordinator', '<>', 'work_update_coordinator')
                    ->whereNull('approve_task')
                    ->where('work_update_coordinator', $staff_id)
                    ->orWhere(function ($q) use ($staff_id) {
                        // If work_start_coordinator is empty, check work_update_coordinator
                        $q->whereNull('work_start_coordinator')
                            ->where('work_update_coordinator', $staff_id);
                    })

                    ->orWhere(function ($q) use ($staff_id) {
                        $q->whereNotNull('work_update_coordinator')
                            ->whereNotNull('work_start_coordinator')
                            ->where('work_update_coordinator', '<>', 'work_start_coordinator')
                            ->where('work_start_coordinator', $staff_id);
                    });
            })
            ->pluck('staff_id')
            ->unique()
            ->toArray();

        if ($request->ajax()) {
            $stafflist = Staff::where('status', 'Y')->whereIn('id', $permission_staffs)
                ->whereIn('staff_category', [
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

        $staff = Staff::whereIn('id', $permission_staffs)->where('status', 'Y')->get();

        $admins = DB::table('admin')->get();

        return view('staff.task_manage.task_location', compact('staff', 'admins'));
    }


    public function staff_task_time_staff(Request $request)
    {


        $staff_id = session('STAFF_ID');

        // $permission_staffs = User_permission::where('work_update_coordinator',$staff_id)->pluck('staff_id')->unique()->toArray();

        $permission_staffs = User_permission::query()
            ->where(function ($query) use ($staff_id) {

                $query->whereNotNull('work_start_coordinator')
                    ->where('work_start_coordinator', '<>', 'work_update_coordinator')
                    ->whereNull('approve_task')
                    ->where('work_update_coordinator', $staff_id)
                    ->orWhere(function ($q) use ($staff_id) {
                        // If work_start_coordinator is empty, check work_update_coordinator
                        $q->whereNull('work_start_coordinator')
                            ->where('work_update_coordinator', $staff_id);
                    })

                    ->orWhere(function ($q) use ($staff_id) {
                        $q->whereNotNull('work_update_coordinator')
                            ->whereNotNull('work_start_coordinator')
                            ->where('work_update_coordinator', '<>', 'work_start_coordinator')
                            ->where('work_start_coordinator', $staff_id);
                    });
            })
            ->pluck('staff_id')
            ->unique()
            ->toArray();


        if ($request->ajax()) {
            $stafflist = Staff::where('status', 'Y')->whereIn('id', $permission_staffs)
                ->whereIn('staff_category', [
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


}