<?php
namespace App\Http\Controllers\staff;
use App\Models\StaffTaskTime;
use App\Task;
use App\Task_type;
use App\Product;
use App\Banner;
use App\User;
use App\Staff;
use App\Dailyclosing;
use App\Dailyclosing_expence;
use App\Work_report_for_office;
use App\Work_report_for_leave;
use App\Checklist;
use App\Task_comment;
use App\Contact_person;
use App\Category_type;
use App\Product_type;
use App\CarApproval;

use App\Service;
use App\Models\StaffFollower;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FreezStaff;
use App\OppertunityTask;
use App\Subcategory;
use App\User_permission;
use Carbon\Carbon;
use Image;
use Storage;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    public function task_hospital_start(Request $request){

        $this->validate($request,[
            'task_id'=>"required",
            'travel_id'=>"required"
        ]);

        $staff_id = session('STAFF_ID');
        $tasktime=StaffTaskTime::where('dailyclosing_expence_id',$request->travel_id)->where('task_id',$request->task_id)->where('staff_id',$staff_id)->first();
        if(empty($tasktime)){
            $tasktime=new StaffTaskTime;
            $tasktime->dailyclosing_expence_id=$request->travel_id;
            $tasktime->task_id=$request->task_id;
            $tasktime->staff_id=$staff_id;
            $tasktime->save();
        }
        $tasktime->start_latitude=$request->latitude;
        $tasktime->start_longitude=$request->longitude;
        $tasktime->start_time=Carbon::now();
        $tasktime->save();

        $staff_permission = User_permission::where('staff_id',$staff_id)->first();

        if(!empty($staff_permission))
        {
            if( optional($staff_permission)->work_start_coordinator != optional($staff_permission)->work_update_coordinator)
            {
                $staff_permission->approve_task = "";
            }

            $staff_permission->work_start_coordinator = $staff_permission->work_update_coordinator;

            $staff_permission->save();
        }

        return response()->json(['success'=>"start success"]);
    }
    public function task_hospital_end(Request $request){
        $this->validate($request,[
            'task_id'=>"required",
            'travel_id'=>"required"
        ]);
        $staff_id = session('STAFF_ID');
        $tasktime=StaffTaskTime::where('dailyclosing_expence_id',$request->travel_id)->where('task_id',$request->task_id)->where('staff_id',$staff_id)->first();
        if(empty($tasktime)){
            $tasktime=new StaffTaskTime;
            $tasktime->dailyclosing_expence_id=$request->travel_id;
            $tasktime->task_id=$request->task_id;
            $tasktime->staff_id=$staff_id;
            $tasktime->save();
        }
        $tasktime->end_latitude=$request->latitude;
        $tasktime->end_longitude=$request->longitude;
        $tasktime->end_time=Carbon::now();
        $tasktime->save();
        return response()->json(['success'=>"end success"]);
    }
    public function test_v_task(){
        $staff_id = session('STAFF_ID');
        
        $verify_task = Task::join('task_comment', 'task.id', '=', 'task_comment.task_id')
            // ->where('task_comment.status','N')->where('task.status','!=','Complete')
            ->where(function($qry)use($staff_id){
                $follow_ids=[];
                foreach (StaffFollower::where('follower_id',$staff_id)->get() as $row) {
                    $qry->orWhere(function($iqry)use($row,$staff_id){
                        $iqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at','>=',$row->created_at);
                    });
                }
                $qry->orWhere(function($iqry)use($staff_id){
                    foreach (StaffFollower::where('follower_id','!=',$staff_id)->get() as $row){
                        $iqry->where(function($iiqry)use($row){
                            $iiqry->whereRaw("NOT FIND_IN_SET('$row->staff_id', task.assigns)")->orWhere(function($iiiqry)use($row){
                                $iiiqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at','<',$row->created_at);
                            });
                        });
                    }
                    $iqry->whereRaw("FIND_IN_SET('$staff_id', task.followers)");

                });
                
            })            
            // ->groupBy('task.id')
            ->orderBy('task.updated_at','desc')
            ->get(['task.assigns','task.start_date','task.name','task.start_time','task.id as task_id']);
            print_r(json_encode($verify_task));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $task = Task::all();
       $staff_id = session('STAFF_ID');
       //  $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR `created_by_id`='".$staff_id."' ) AND (`staff_status`='Not Started')  OR (followers='".$staff_id."' and status='Not Started')");
       $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Not Started')    ");
       $staff_id = session('STAFF_ID');
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
       return view('staff.task.index', compact('task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = DB::table('company')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $staff = DB::table('staff')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $user = DB::table('users')
        ->orderBy('business_name', 'asc')
        ->select('business_name','id')
        ->get();
        $designation = DB::table('designation')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_category = DB::table('relatedto_category')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_subcategory = DB::table('relatedto_subcategory')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $state = DB::table('state')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $district = DB::table('district')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        return view('staff.task.create', ['relatedto_subcategory'=> $relatedto_subcategory,'relatedto_category'=> $relatedto_category,'company'=> $company,'staff'=> $staff,'user'=> $user,'designation'=> $designation,'state'=> $state,'district'=> $district]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $this->validate($request, array(
                'name' => 'required|max:100'
            ));
        $random_num = rand(12345, 199999) + rand(123, 999);
        $timestamp = time();
        $unique_code = $random_num . ($timestamp + rand(10, 999));
        $cron_repeat = DB::select("select * from task where unique_code = '" . $unique_code . "'");
        if (count($cron_repeat) > 0) {
            $unique_code= time().$unique_code;
        } else {
            $unique_code= $unique_code;
        }
        $task = new Task;
        $task->name = $request->name;
        $task->unique_code = $unique_code;
        $task->company_id = $request->company_id;
        $task->assigned_team = $request->assigned_team;
      //  $task->assigns = $request->assigns;
        if($request->followers == ''){
            $task->followers = $request->service_follower;
        }else{
            $task->followers = $request->followers;
        }
        
        $task->related_to = $request->related_to;
        $task->related_to_sub = $request->related_to_sub;
        $task->user_id = $request->user_id;
        $task->start_date = $request->start_date;
        if($request->start_time=="00:00:00")
        {
            $task->start_time = date('H:i:s');
        }
        else{
            $task->start_time = $request->start_time;
        }
        
        $task->due_date = $request->due_date_value;
        $task->priority = $request->priority;
        $task->repeat_every = $request->repeat_every;
        // $task->cycles = $request->cycles;
        $task->state_id = $request->state_id;
        $task->district_id = $request->district_id;
        // if($request->unlimited_cycles)
        // {
        //     $task->unlimited_cycles = "Y";
        // }
        // else{
            // $task->unlimited_cycles = "N";
        // }
        // $task->custom_days = $request->custom_days;
        // $task->custom_type = $request->custom_type;
        $task->description = $request->description;
        // $task->infinity_end_date = $request->infinity_end_date;
        if($request->assigns!='')
         {
            $task->assigns = implode(',',$request->assigns);
         }
         if($request->check_list_id!='')
         {
            $task->check_list_id = implode(',',$request->check_list_id);
         }
        //  $task->freq_hour = $request->freq_hour;
         $staff_id = session('STAFF_ID');
         $task->created_by_name="staff";
         $task->created_by_id=$staff_id;
        $task->save();
        $task = Task::find($task->id);
        $task->parent_id=$task->id;
        $task->save();
    //     $cur_date=date('Y-m-d');
    //     $staff_id = session('STAFF_ID');
    //     $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND  (start_date='".$cur_date."') order by updated_at desc");
    //    // $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date>'".$cur_date."' and status='Not Started') order by updated_at desc");
    //    $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date>'".$cur_date."' ) order by updated_at desc");
    //     $completetask= DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (status='Complete') order by updated_at desc");
    //     $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
    //     $notstart= $notstart[0]->notstart;
    //     $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
    //     $testing= $testing[0]->testing;
    //     $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
    //     $progress= $progress[0]->progress;
    //     $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
    //     $feed= $feed[0]->feed;
    //     $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
    //     $complete= $complete[0]->complete;
    //     $user = DB::table('users')
    //     ->orderBy('id', 'asc')->get();
    //     $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date!='".$cur_date."') order by updated_at desc");
    //     $failed_arr=array();
    //     $start_due_arr=array();
    //     foreach($alltask as $values){
    //         $date = strtotime($values->start_date);
    //         $date = strtotime("+7 day", $date);
    //         $add_week= date('Y-m-d', $date);
    //         // echo 'date'.$values->start_date.'addwee'.$add_week;
    //         // echo '<br>';
    //         if($values->status!="Complete")
    //         {
    //            if($values->due_date=="0000-00-00")
    //             {
    //                 if(strtotime($cur_date)>strtotime($add_week))
    //                     {
    //                         $failed_arr[]=$values->id;
    //                     }
    //             }
    //             else
    //             {
    //                 if(strtotime($cur_date)>strtotime($values->due_date))
    //                 {
    //                     $failed_arr[]=$values->id;
    //                 }
    //             }
    //             if($values->due_date=="0000-00-00")
    //             {
    //                 if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
    //                     {
    //                         $start_due_arr[]=$values->id;
    //                     }
    //             }
    //             else
    //             {
    //                 if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
    //                 {
    //                     $start_due_arr[]=$values->id;
    //                 }
    //             }
    //         }
    //    }
        // print_r($start_due_arr);
        // exit;
        // return view('staff.task.AllTask', compact('start_due_arr','failed_arr','completetask','currenttask','nostarttask','user'));
       return redirect()->route('staff.task.index')->with('success','Data successfully saved.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $company = DB::table('company')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $staff = DB::table('staff')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $user = DB::table('users')
        ->orderBy('business_name', 'asc')
        ->select('business_name','id')
        ->get();
        $designation = DB::table('designation')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_category = DB::table('relatedto_category')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $checklist = DB::table('checklist')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $state = DB::table('state')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $district = DB::table('district')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_subcategory = DB::table('relatedto_subcategory')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        return view('staff.task.edit', compact('task'), ['checklist'=> $checklist,'company'=> $company,'staff'=> $staff,'user'=> $user,'designation'=> $designation,'relatedto_category'=> $relatedto_category,'relatedto_subcategory'=> $relatedto_subcategory,'state'=> $state,'district'=> $district]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task = Task::find($task->id);
                $this->validate($request, array(
                    'name' => 'required|max:100',
                ));
                $task->name = $request->name;
                $task->company_id = $request->company_id;
                $task->assigned_team = $request->assigned_team;
              //  $task->assigns = $request->assigns;
                $task->followers = $request->followers;
                $task->related_to = $request->related_to;
                $task->related_to_sub = $request->related_to_sub;
                $task->user_id = $request->user_id;
                $task->start_date = $request->start_date;
                $task->start_time = $request->start_time;
                $task->due_date = $request->due_date;
                $task->priority = $request->priority;
                $task->repeat_every = $request->repeat_every;
                $task->cycles = $request->cycles;
               if($request->unlimited_cycles)
               {
                $task->unlimited_cycles = "Y";
               }
               else{
                $task->unlimited_cycles = "N";
               }
               if($request->assigns!='')
               {
                  $task->assigns = implode(',',$request->assigns);
               }
               if($request->check_list_id!='')
               {
                  $task->check_list_id = implode(',',$request->check_list_id);
               }
               $task->freq_hour = $request->freq_hour;
                $task->custom_days = $request->custom_days;
                $task->custom_type = $request->custom_type;
                $task->description = $request->description;
        $task->save();
        return redirect()->route('staff.task.index')->with('success', 'Data successfully saved.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$task->id]);
    }
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $task = Task::find($id);
            $task->delete();
        }
        return redirect()->route('staff.task.index')->with('success', 'Data has been deleted successfully');
    }
    public function inprogressTask()
    {
        $staff_id = session('STAFF_ID');
     //   $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (`staff_status`='In Progress' and status='In Progress')  ");
        $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='In Progress')    ");
      //  echo "select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='In Progress') UNION SELECT * FROM task where followers='".$staff_id."' and `status`='In Progress'   ";
      $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.progress', compact('user','task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    public function completeTask()
    {
           $task = Task::all();
       $staff_id = session('STAFF_ID');
       //  $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR `created_by_id`='".$staff_id."' ) AND (`staff_status`='Not Started')  OR (followers='".$staff_id."' and status='Not Started')");
       $task = DB::select("select *  from task where `task_quick_or_normal`='quick' OR `task_quick_or_normal`='nostaff' order by id desc");
       $staff_id = session('STAFF_ID');
       $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }

       
         return view('staff.task.quicktaskAll', compact('task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    //echo '111';exit;
       /* $staff_id = session('STAFF_ID');
       // $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."') AND (`staff_status`='Complete') OR (followers='".$staff_id."' and status='Complete')");
        $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Complete')   ");
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.complete', compact('user','task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));*/
    }
    public function pendingTask()
    {
        $staff_id = session('STAFF_ID');
        $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Pending')   ");
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.pending', compact('user','task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    public function dailyclosing()
    {
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        //$task =  DB::select("select * from  dailyclosing where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' )  group by start_date order by start_date desc");
        $task =  DB::select("select * from  dailyclosing where (FIND_IN_SET('".$staff_id."', assigns)  )  group by start_date order by start_date desc");
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.dailyclosing', compact('user','task'));
    }
    public function approvedTask()
    {
        $staff_id = session('STAFF_ID');
       // $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Approved')  OR (followers='".$staff_id."' and status='Approved')");
     //  $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Approved') UNION SELECT * FROM task where followers='".$staff_id."' and `status`='Approved'   ");
     $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' ) AND (`staff_status`='Approved')   ");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.approved', compact('user','task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    public function verify()
    {
        $staff_id = session('STAFF_ID');
        $task = DB::select("SELECT * FROM task where followers='".$staff_id."' AND status!='Complete'  order by updated_at desc ");
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
        $user = DB::table('users')
        ->orderBy('id', 'asc')
        ->get();
        return view('staff.task.verify', compact('user','task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    public function AllTask()
    {
        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND  (start_date='".$cur_date."') order by updated_at desc");
       // $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date>'".$cur_date."' and status='Not Started') order by updated_at desc");
       $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date>'".$cur_date."' ) order by updated_at desc");
        $completetask= DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (status='Complete') order by updated_at desc");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."'  ) AND (start_date!='".$cur_date."') order by updated_at desc");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }
        // print_r($start_due_arr);
        // exit;
        return view('staff.task.AllTask', compact('start_due_arr','failed_arr','completetask','currenttask','nostarttask','user'));
    }
    public function check_travel_from_status(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $travel = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and start_date='".$request->sel_date."' and expence_cat='travel' and travel_from_status='N' ");
        return json_encode($travel);
    }
    public function get_all_hospital_list(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $user = DB::select("select users.id,users.business_name from users as users INNER join task as task ON users.id=task.user_id where FIND_IN_SET('".$staff_id."', task.assigns)  group by users.id order by users.id desc ");    
        return json_encode($user);
    }
    public function WorkReport()
    {
        $staff_id = session('STAFF_ID');
        $cur_date=date("Y-m-d");
        $user= DB::select('CALL getuserandtask("'.$staff_id.'")');//get customer
        $alltask_list =DB::select('CALL alltask("'.$staff_id.'")'); //staff created task
        $alltask=array();
        $admins = DB::table('admin')->get();
        $staffs = DB::table('staff')->get();
        if(count($alltask_list)>0)
        {
            foreach($alltask_list as $values){
                $dailttasks = DB::select("select * from dailyclosing_expence where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', travel_task_child_id) OR travel_task_id='".$values->id."') ");
                $work_report = DB::select("select * from work_report_for_office where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', task_child_id) OR task_id='".$values->id."') ");
                if(count($dailttasks)==0 || count($work_report)==0 && $values->staff_status != 'Approved')
                {
                    $alltask[]=$values;
                }
            }
        }

        $alltask_all =DB::select('CALL pedingtask("'.$staff_id.'","'.$cur_date.'")'); //pending task get
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask_all as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
               
            }
       }

    //    if(StaffFollower::where('staff_id',$staff_id)->exists())
    //    {
    //         $staff_folower = StaffFollower::where('staff_id',$staff_id)->first();
    //         $verify_task = Task::join('task_comment', 'task.id', '=', 'task_comment.task_id')
    //         ->whereRaw("FIND_IN_SET('$staff_id', task.followers)")
    //         ->where('task_comment.status','N')->where('task.status','!=','Complete')->where('task.created_at','<',$staff_folower->created_at)
    //         ->groupBy('task.id')->orderBy('task.updated_at','desc')
    //         ->get(['task.assigns','task.start_date','task.name','task.id as task_id']);
    //     }
    //    elseif(StaffFollower::where('follower_id',$staff_id)->exists())
    //    {
    //         $folower_staff = StaffFollower::where('follower_id',$staff_id)->get();
            
    //         $verify_task = Task::join('task_comment', 'task.id', '=', 'task_comment.task_id')
    //         ->where('task_comment.status','N')->where('task.status','!=','Complete')
    //         ->where(function($qry)use($folower_staff,$staff_id){
    //             foreach ($folower_staff as $row) {
    //                 $qry->orWhere(function($iqry)use($row){
    //                     $iqry->whereRaw("FIND_IN_SET('$row->staff_id', task.followers)")->where('task.created_at','>=',$row->created_at);
    //                 });
    //             }
    //             $qry->orWhereRaw("FIND_IN_SET('$staff_id', task.followers)");
    //         })            
    //         ->groupBy('task.id')->orderBy('task.updated_at','desc')
    //         ->get(['task.assigns','task.start_date','task.name','task.id as task_id']);
  
    //    }
    //    else
    //    {
    //         $verify_task =DB::select('CALL verifytask_staff("'.$staff_id.'")'); 
    //     }
     
    $verify_task = Task::join('task_comment', 'task.id', '=', 'task_comment.task_id')
    ->where('task_comment.status','N')->where('task.status','!=','Complete')
    ->where(function($qry)use($staff_id){
        $follow_ids=[];
        foreach (StaffFollower::where('follower_id',$staff_id)->get() as $row) {
            $qry->orWhere(function($iqry)use($row,$staff_id){
                $iqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at','>=',$row->created_at);
            });
        }
        $qry->orWhere(function($iqry)use($staff_id){
            foreach (StaffFollower::where('follower_id','!=',$staff_id)->get() as $row){
                $iqry->where(function($iiqry)use($row){
                    $iiqry->whereRaw("NOT FIND_IN_SET('$row->staff_id', task.assigns)")->orWhere(function($iiiqry)use($row){
                        $iiiqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at','<',$row->created_at);
                    });
                });
            }
            $iqry->whereRaw("FIND_IN_SET('$staff_id', task.followers)");

        });
        
    })            
    ->groupBy('task.id')->orderBy('task.updated_at','desc')
    ->get(['task.assigns','task.start_date','task.name','task.start_time','task.id as task_id']);
//print_r($verify_task);
      $taskcheck_fullday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Full Day'");
     $taskcheck_halfday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Half Day'");
     $attendence = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) and YEAR(start_date) = YEAR(CURDATE()) AND (type_leave='Request Leave Office Staff' or type_leave='Request Leave Field Staff') ");


       return view('staff.task.WorkReport',compact('alltask','admins','staffs','user','failed_arr','verify_task','taskcheck_fullday','taskcheck_halfday','attendence'));
    }


    public function WorkReportLoad(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $cur_date = date("Y-m-d");
        
            $user = DB::select('CALL getuserandtask("'.$staff_id.'")');
            $admins = DB::table('admin')->get();
            $staffs = DB::table('staff')->get();
            $alltask_list = DB::select('CALL alltask("'.$staff_id.'")');
            $alltask = [];
        
            foreach ($alltask_list as $task) {
                $dailytasks = DB::select("SELECT * FROM dailyclosing_expence WHERE staff_id='".$staff_id."' AND (FIND_IN_SET('".$task->id."', travel_task_child_id) OR travel_task_id='".$task->id."')");
                $work_report = DB::select("SELECT * FROM work_report_for_office WHERE staff_id='".$staff_id."' AND (FIND_IN_SET('".$task->id."', task_child_id) OR task_id='".$task->id."')");
                if (count($dailytasks) == 0 || count($work_report) == 0 && $task->staff_status != 'Approved') {
                    $alltask[] = $task;
                }
            }
        
            $alltask_all = DB::select('CALL pedingtask("'.$staff_id.'","'.$cur_date.'")');
            $failed_arr = [];
            $start_due_arr = [];
        
            foreach ($alltask_all as $task) {
                $date = strtotime($task->start_date);
                $date = strtotime("+7 day", $date);
                $add_week = date('Y-m-d', $date);
                
                if ($task->status != "Complete") {
                    if ($task->due_date == "0000-00-00") {
                        if (strtotime($cur_date) > strtotime($add_week)) {
                            $failed_arr[] = $task->id;
                        }
                    } else {
                        if (strtotime($cur_date) > strtotime($task->due_date)) {
                            $failed_arr[] = $task->id;
                        }
                    }
                }
            }
        
            // Verify pending tasks
            $verify_task = Task::join('task_comment', 'task.id', '=', 'task_comment.task_id')
                ->where('task_comment.status', 'N')
                ->where('task.status', '!=', 'Complete')
                ->where(function($qry) use ($staff_id) {
                    $follow_ids = [];
                    foreach (StaffFollower::where('follower_id', $staff_id)->get() as $row) {
                        $qry->orWhere(function($iqry) use ($row, $staff_id) {
                            $iqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at', '>=', $row->created_at);
                        });
                    }
                    $qry->orWhere(function($iqry) use ($staff_id) {
                        foreach (StaffFollower::where('follower_id', '!=', $staff_id)->get() as $row) {
                            $iqry->where(function($iiqry) use ($row) {
                                $iiqry->whereRaw("NOT FIND_IN_SET('$row->staff_id', task.assigns)")->orWhere(function($iiiqry) use ($row) {
                                    $iiiqry->whereRaw("FIND_IN_SET('$row->staff_id', task.assigns)")->where('task_comment.created_at', '<', $row->created_at);
                                });
                            });
                        }
                        $iqry->whereRaw("FIND_IN_SET('$staff_id', task.followers)");
                    });
                })
                ->groupBy('task.id')
                ->orderBy('task.updated_at', 'desc')
                ->get(['task.assigns', 'task.start_date', 'task.name', 'task.start_time', 'task.id as task_id']);
        
            // Get leave data
            $taskcheck_fullday = DB::select("SELECT * FROM work_report_for_leave WHERE staff_id='".$staff_id."' AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Full Day'");
            $taskcheck_halfday = DB::select("SELECT * FROM work_report_for_leave WHERE staff_id='".$staff_id."' AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURDATE()) AND type_leave='Request Leave' AND attendance='Half Day'");
            $attendence = DB::select("SELECT * FROM work_report_for_leave WHERE staff_id='".$staff_id."' AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURDATE()) AND (type_leave='Request Leave Office Staff' OR type_leave='Request Leave Field Staff')");
            
            $half_day = count($taskcheck_halfday) / 2;
            $leave = count($taskcheck_fullday) + $half_day;
            $notupdated = date("d");
            $noupdated_task = $notupdated - count($attendence);
        
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            
            return view('staff.task.WorkReportLoad', compact(
                'noupdated_task', 
                'leave', 
                'months', 
                'alltask', 
                'failed_arr', 
                'verify_task',
                'attendence',
                'user',
                'admins',
                'staffs'
            ));
            // return view('staff.task.WorkReport',compact('alltask','admins','staffs','user','failed_arr','verify_task','taskcheck_fullday','taskcheck_halfday','attendence'));
    }

    public function workcalender(Request $request)
    {
        $staff_id = session('STAFF_ID');
        
        if($request->ajax())
        {

            $date = date('Y-m-d', strtotime("12 days"));

            $weekOfdays = array();
            $cur_date=date("Y-m-d");

            $calender_data = [];

            foreach(range(0, 44) as $i)
            {
                $date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
                if (strtotime($cur_date) >= strtotime($date)) {

                    $a=1;
                }
                else{

                    $a=0;
                }

                if (strtotime($cur_date) == strtotime($date)) {

                    $calendar_data[] = (object) [
                        'id' => 'calender-'.$i,
                        'date' => $date,
                        'work_status' => 'work-green',
                        'date_status' => ''
                    ];
                }
                else
                {
                    $status_date='';
                    $status_date_half='';

                    if($a>0)
                    {
                        $taskcheck =DB::select('CALL dailyclosing_exit_check("'.$staff_id.'","'.$date.'")');
                        $taskcheck_work_report =DB::select('SELECT id from work_report_for_office where staff_id="'.$staff_id.'" AND start_date="'.$date.'" order by id desc');
                        $task_pending_travel =DB::select('CALL daily_expence_staff_status_pending("'.$staff_id.'","'.$date.'")');
                        $task_pending_office =DB::select('CALL work_office_status_pending("'.$staff_id.'","'.$date.'")');

                        if(count($taskcheck)==0 && count($taskcheck_work_report)==0)
                        {
                        $noadded_task='1';
                        }
                        else{
                        $noadded_task='0';
                        }

                        $leave_added = DB::select('SELECT id,attendance from work_report_for_leave where staff_id="'.$staff_id.'" AND start_date="'.$date.'"  AND type_leave="Request Leave" ');

                        $attendence_added = DB::select('SELECT id from work_report_for_leave where staff_id="'.$staff_id.'" AND start_date="'.$date.'" AND (type_leave="Request Leave Office Staff" OR type_leave="Request Leave Field Staff")  ');

                        if(count($leave_added)>0)
                        {
                        if($leave_added[0]->attendance=="Half Day")
                        {
                        $status_date .="Leave. ";
                        if($noadded_task==1)
                        {
                        $status_date .="Task Not Added";
                        }
                        else{
                            if(count($task_pending_travel)>0 || count($task_pending_office)>0)
                            {
                            if(count($attendence_added)>0 ){
                                $status_date .="Attendance  Added";
                            }
                            else{
                                $status_date .="Approval Pending";
                            }
                            
                            }
                            else if(count($attendence_added)==0 )
                            {
                            $status_date .="Attendance Not Added";
                            }
                            else{
                            $status_date .="Attendance  Added";
                            }
                        }
                        }
                        else{
                        $status_date="Leave";
                        }
                        }
                        else{
                        if($noadded_task==1)
                        {
                        $status_date="Task Not Added";
                        }
                        else{
                            if(count($task_pending_travel)>0 || count($task_pending_office)>0)
                            {
                            
                            if(count($attendence_added)>0 ){
                                $status_date .="Attendance  Added";
                            }
                            else{
                                $status_date .="Approval Pending";
                            }
        
                            }
                            else if(count($attendence_added)==0 )
                            {
                            $status_date="Attendance Not Added";
                            }
                            else{
                            $status_date="Attendance  Added";
                            }
                        }
                        }

                        if(count($taskcheck)==0)
                        {
                        if(count($taskcheck_work_report)==0)
                        {
                        $work_status='work-yellow';
                        }
                        else{
                        $work_status='work-yellow';
                        }
                        if(count($attendence_added)>0){
                            $work_status='work-grey';
                        }
                        else{
                        $work_status='work-yellow';
                        }
                        }
                        else{

                            $pending_task = DB::select('CALL dailyclosing_reject_check("'.$staff_id.'","'.$date.'")');
                            if(count($pending_task)>0)
                            {
                            $work_status='work-red';
                            }
                            else{
                            $pending_task_rej = DB::select('CALL dailyclosing_pending_check("'.$staff_id.'","'.$date.'")');
                            if(count($pending_task_rej)>0)
                            {
                                $work_status='work-yellow';
                            }
                            else{
                                $work_status='work-grey';
                            }
                            }
                        }
                    }
                    else{
                    
                        $work_status='';
                    }

                    $calendar_data[] = (object) [
                        'id' => 'calender-'.$i,
                        'date' => $date,
                        'work_status' => $work_status,
                        'date_status' => $status_date
                    ];

                }

            }

            return response()->json($calendar_data);
        }
    }

    public function OppertunityWork(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $today = "";

        if(empty($request->fetch_date))
        {
            $today =now()->format('Y-m-d');
        }
        else
        {
            $today = $request->fetch_date;
        }
        
        if ($request->ajax()) {
          

            return DataTables::of(OppertunityTask::where('id','>',0)->whereDate('created_at',$today)->where('staff_id',$staff_id))

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
                  
                    $date = $data->created_at->format('Y-m-d');

                    return $date;
                })

                ->addColumn('tstatus', function ($data) {

                    if ($data->staff_status == "Approved") {

                       return '<span class="text-success">Task Approved</span>';

                    } else {

                        return '<span class="text-danger">' . ($data->staff_status ?? "Task Created (Pending)") . '</span>';
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
    public function freezdate(Request $request){ 
        $staff=FreezStaff::where('staff_id',session('STAFF_ID'))->where('taskdate',$request->freezdate)->first();
        return response()->json($staff);
    }
    public function WorkReporttest()
    {

        $staff_id = session('STAFF_ID');
        $cur_date=date("Y-m-d");
        $user= DB::select('CALL getuserandtask("'.$staff_id.'")');//get customer
        $alltask_list =DB::select('CALL alltask("'.$staff_id.'")'); //staff created task
        $alltask=array();
        if(count($alltask_list)>0)
        {
            foreach($alltask_list as $values){
                $dailttasks = DB::select("select * from dailyclosing_expence where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', travel_task_child_id) OR travel_task_id='".$values->id."') ");
                $work_report = DB::select("select * from work_report_for_office where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', task_child_id) OR task_id='".$values->id."') ");
                if(count($dailttasks)==0 && count($work_report)==0)
                {
                    $alltask[]=$values;
                }
            }
        }

        $alltask_all =DB::select('CALL pedingtask("'.$staff_id.'","'.$cur_date.'")'); //pending task get
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask_all as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
               
            }
       }
     
      $verify_task =DB::select('CALL verifytask_staff("'.$staff_id.'")'); 
     
      return view('staff.task.WorkReporttest',compact('alltask','user','failed_arr','verify_task'));
        
    }
    public function save_start_travel(Request $request)
    {
        $staff_id = session('STAFF_ID');
        //$check_task_exit = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and expence_cat='travel' and travel_task_id='".$request->task_id."' and start_date='".$request->search_date."' ");    
        $check_task_exit = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and expence_cat='travel' and travel_task_id='".$request->task_id."' and start_date='".$request->search_date."' AND travel_from_status='N' ");    
        if(count($check_task_exit)>0)
        {
            $dailyclosing_expence = Dailyclosing_expence::find($check_task_exit[0]->id);
        }
        else
        {
            $dailyclosing_expence = new Dailyclosing_expence;
        }
        if($request->fair_doc!='')
        {
        $imageName = time().$request->fair_doc->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->fair_doc->storeAs('public/comment', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName="";
        }
        if($request->fair_doc_end!='')
        {
        $imageName1 = time().$request->fair_doc_end->getClientOriginalName();
        $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
        $path =  storage_path();
        $img_path = $request->fair_doc_end->storeAs('public/comment', $imageName1);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName1="";
        }
        $travel_sec=$request->travel_sec;

        if(!empty($request->travel_start_latitude)){
            $dailyclosing_expence->travel_start_latitude=$request->travel_start_latitude;
        }
        if(!empty($request->travel_start_longitude)){
            $dailyclosing_expence->travel_start_longitude=$request->travel_start_longitude;
        }

        if(!empty($request->travel_end_latitude)){
            $dailyclosing_expence->travel_end_latitude=$request->travel_end_latitude;
        }
        if(!empty($request->travel_end_longitude)){
            $dailyclosing_expence->travel_end_longitude=$request->travel_end_longitude;
        }

        if($travel_sec=="first")
        {
            $dailyclosing_expence->start_date=$request->search_date;
           $task_ids=array();
            $task_names=array();
            $task_dates=array();
            $all_task=explode(",",$request->task_id);
        if(count($all_task)>0)
        {$j=0;
            foreach($all_task as $values)
            {
                $task = Task::find($values);
                if($j==0)
                {
                    $dailyclosing_expence->travel_task_id=$values;
                    $dailyclosing_expence->task_name=$task->name;
                }
                else{
                    $task_ids[]=$values;
                    $task_names[]=$task->name;
                    $task_dates[]=$task->start_date;
                }
                $j++;
            }
        }
        $arr_task=implode(",",$task_ids);
        $arr_task_name=implode(",",$task_names);
        $arr_task_dates=implode(",",$task_dates);
        $dailyclosing_expence->travel_task_child_id=$arr_task;
        $dailyclosing_expence->travel_task_child_name=$arr_task_name;
        $dailyclosing_expence->travel_task_child_date=$arr_task_dates;
          /*  $dailyclosing_expence->travel_task_id=$request->task_id;
            $dailyclosing_expence->task_name=$request->task_name;*/
            $dailyclosing_expence->start_time_travel=$request->start_time;
            $dailyclosing_expence->staff_id=$staff_id;
            $dailyclosing_expence->travel_type=$request->travel_type;
            $dailyclosing_expence->start_meter_reading=$request->meter_start;
            $dailyclosing_expence->travel_start_amount=$request->amount_start;
            $dailyclosing_expence->travel_start_image=$imageName;
            $dailyclosing_expence->expence_cat='travel';
            $dailyclosing_expence->status='N';
            $dailyclosing_expence->user_id=$request->user_id;
            $dailyclosing_expence->travel_from_status='N';
        }
    else{
        $dailyclosing_expence->end_time_travel=$request->end_time;
        $dailyclosing_expence->end_meter_reading=$request->meter_end;
        $dailyclosing_expence->travel_end_amount=$request->amount_end;
        $dailyclosing_expence->travel_end_image=$imageName1;
        $dailyclosing_expence->travel_from_status='Y';
    }
        $dailyclosing_expence->save();
        $cur_date=date('Y-m-d');
    }
    public function save_staff_expence(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $dailyclosing_expence = new Dailyclosing_expence;
        if($request->expence_doc!='')
        {
        $imageName = time().$request->expence_doc->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->expence_doc->storeAs('public/comment', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName="";
        }
            $dailyclosing_expence->start_date=$request->search_date;
            $dailyclosing_expence->travel_task_id=$request->task_id;
            $dailyclosing_expence->task_name=$request->task_name;
            $dailyclosing_expence->staff_id=$staff_id;
            $dailyclosing_expence->travel_type=$request->other_expence;
            $dailyclosing_expence->travel_start_amount=$request->expence_amount;
            $dailyclosing_expence->expence_desc=$request->expence_desc;
            $dailyclosing_expence->travel_start_image=$imageName;
            $dailyclosing_expence->expence_cat='expence';
            $dailyclosing_expence->status='N';
            $dailyclosing_expence->expence_section='field';
        $dailyclosing_expence->save();
        echo '11';exit;
    }
    public function save_staff_expence_office(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $dailyclosing_expence = new Dailyclosing_expence;
        if($request->expence_doc!='')
        {
        $imageName = time().$request->expence_doc->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->expence_doc->storeAs('public/comment', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName="";
        }
            $dailyclosing_expence->start_date=$request->search_date;
            $dailyclosing_expence->travel_task_id=$request->task_id;
            $dailyclosing_expence->task_name=$request->task_name;
            $dailyclosing_expence->staff_id=$staff_id;
            $dailyclosing_expence->travel_type=$request->other_expence;
            $dailyclosing_expence->travel_start_amount=$request->expence_amount;
            $dailyclosing_expence->expence_desc=$request->expence_desc;
            $dailyclosing_expence->travel_start_image=$imageName;
            $dailyclosing_expence->expence_cat='expence';
            $dailyclosing_expence->status='N';
            $dailyclosing_expence->expence_section='office';
        $dailyclosing_expence->save();
        echo '11';exit;
    }
    public function save_field_staff_moretask(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $task_exit= DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' AND start_date='".$request->search_date."' AND travel_from_status='N' ");
        if(count($task_exit)>0)
        {
            //$all_task=array();
            if($task_exit[0]->travel_task_id>0)
            {
                $all_task[]=$task_exit[0]->travel_task_id;
            }
            foreach($request->task_id as $values)
                    {
                        $all_task[]=$values;
                    }
            if($task_exit[0]->travel_task_child_id!='')
            {
                $already_exittask=explode(',',$task_exit[0]->travel_task_child_id);
                foreach($already_exittask as $values)
                    {
                        $all_task[]=$values;
                    }
            }
        // print_r($all_task);exit;
        if(count($all_task)>0)
        {$j=0;
            $work_report_for_office = Dailyclosing_expence::find($task_exit[0]->id);
            $task_name=array();
            $task_id=array();
            $task_date=array();
            foreach($all_task as $values)
            {
                $task = Task::find($values);
                if($j==0)
                {
                    $work_report_for_office->travel_task_id = $values;
                    $work_report_for_office->task_name = $task->name;
                }
                else{
                    $task_name[]=$values;
                    $task_id[]=$task->name;
                    $task_date[]=$task->start_date;
                }
                $j++;
            }
            $arr_task_names=implode(",",$task_name);
            $arr_task_ids=implode(",",$task_id);
            $arr_task_dats=implode(",",$task_date);
            $work_report_for_office->travel_task_child_id = $arr_task_names;
            $work_report_for_office->travel_task_child_name = $arr_task_ids;
            $work_report_for_office->travel_task_child_date = $arr_task_dats;
        }
            $work_report_for_office->save();
        }
    }
    public function save_office_staff_task(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $task_exit= DB::select("select * from work_report_for_office where staff_id='".$staff_id."' AND start_date='".$request->search_date."' AND work_status='N' ");
        if(count($task_exit)>0)
        {
            //$all_task=array();
            if($task_exit[0]->task_id>0)
            {
                $all_task[]=$task_exit[0]->task_id;
            }
            foreach($request->task_id as $values)
                    {
                        $all_task[]=$values;
                    }
            if($task_exit[0]->task_child_id!='')
            {
                $already_exittask=explode(',',$task_exit[0]->task_child_id);
                foreach($already_exittask as $values)
                    {
                        $all_task[]=$values;
                    }
            }
        // print_r($all_task);exit;
        if(count($all_task)>0)
        {$j=0;
            $work_report_for_office = Work_report_for_office::find($task_exit[0]->id);
            $task_name=array();
            $task_id=array();
            $task_date=array();
            foreach($all_task as $values)
            {
                $task = Task::find($values);
                if($j==0)
                {
                    $work_report_for_office->task_id = $values;
                    $work_report_for_office->task_name = $task->name;
                    $work_report_for_office->main_task_date = $task->start_date;
                }
                else{
                    $task_name[]=$values;
                    $task_id[]=$task->name;
                    $task_date[]=$task->start_date;
                }
                $j++;
            }
            $arr_task_names=implode(",",$task_name);
            $arr_task_ids=implode(",",$task_id);
            $arr_task_dats=implode(",",$task_date);
            $work_report_for_office->task_child_id = $arr_task_names;
            $work_report_for_office->task_child_name = $arr_task_ids;
            $work_report_for_office->task_child_date = $arr_task_dats;
        }
            $work_report_for_office->save();
        }
    }
    public function save_office_staff_end_time(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $task_exit= DB::select("select * from work_report_for_office where staff_id='".$staff_id."' AND start_date='".$request->search_date."'  AND work_status='N' ");
        if(count($task_exit)>0)
        {
            $work_report_for_office = Work_report_for_office::find($task_exit[0]->id);
            $work_report_for_office->end_time = $request->office_end_time;
            $work_report_for_office->work_status = 'Y';

            if(!empty($request->end_latitude)){
                $work_report_for_office->end_latitude = $request->end_latitude;
            }
            if(!empty($request->end_longitude)){
                $work_report_for_office->end_longitude = $request->end_longitude;
            }
            $work_report_for_office->save();
        }
        $task_exit_all= DB::select("select * from work_report_for_office where staff_id='".$staff_id."' AND task_id!='3446' AND task_id!='3447' AND start_date='".$request->search_date."'   ");
        $attend_status=array();
        if(count($task_exit_all)>0)
        {
            foreach($task_exit_all as $values)
            {
                $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."'   ");
                if(count($check_task_comment)==0)
                {
                    $attend_status[]=1; 
                }
                else{
                    $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."' AND status='N'  ");
                    if(count($check_task_comment_status)==0)
                    {
                        $attend_status[]=0; 
                    }
                    else{
                        $attend_status[]=1; 
                    }
                }
                if($values->task_child_id!='')
                {
                  $linearray = explode(",",$values->task_child_id);
                  foreach($linearray as $val_child)
                  {
                    $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."'   ");
                    if(count($check_task_comment)==0)
                    {
                        $attend_status[]=1; 
                    }
                    else{
                        $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND status='N'  ");
                        if(count($check_task_comment_status)==0)
                        {
                            $attend_status[]=0; 
                        }
                        else{
                            $attend_status[]=1; 
                        }
                    }
                  }
                }
            }
        }
        else{
            $attend_status[]=1; 
        }
        if (in_array("1", $attend_status)) 
        {
            $attend_status=1;
        }
        else{
            $attend_status=0;
        }
        return json_encode($task_exit_all).'*'.$attend_status;
    }
    public function get_office_staff_all_details(Request $request)
    {
        $staff_id = session('STAFF_ID');
    // echo "select * from work_report_for_office where staff_id='".$staff_id."' AND start_date='".$request->search_date."' ";
        $task_exit= DB::select("select work_report_for_office.* from work_report_for_office INNER JOIN task WHERE task.id=work_report_for_office.task_id AND work_report_for_office.staff_id='".$staff_id."' AND work_report_for_office.start_date='".$request->search_date."' order by work_report_for_office.id ASC");
        $attend_status=array();
        if(count($task_exit)>0)
        {
            //AND task_id!='3446' AND task_id!='3447' 
            foreach($task_exit as $values)
            {
                if($values->task_id==3446 || $values->task_id==3447)
                {
                    $attend_status[]=0; 
                }
                else{
                $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."'   ");
                if(count($check_task_comment)==0)
                {
                    $attend_status[]=1; 
                }
                else{
                    $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."' AND status='N'  ");
                    if(count($check_task_comment_status)==0)
                    {
                        $attend_status[]=0; 
                    }
                    else{
                        $attend_status[]=1; 
                    }
                }
                if($values->task_child_id!='')
                {
                  $linearray = explode(",",$values->task_child_id);
                  foreach($linearray as $val_child)
                  {
                    $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."'   ");
                    if(count($check_task_comment)==0)
                    {
                        $attend_status[]=1; 
                    }
                    else{
                        $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND status='N'  ");
                        if(count($check_task_comment_status)==0)
                        {
                            $attend_status[]=0; 
                        }
                        else{
                            $attend_status[]=1; 
                        }
                    }
                  }
                }
            }
            }
        }
        else{
            $attend_status[]=1; 
        }
        if (in_array("1", $attend_status)) 
        {
            $attend_status=1;
        }
        else{
            $attend_status=0;
        }
        return json_encode($task_exit).'*'.$attend_status;
    }
    public function save_office_staff_start_time(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $work_report_for_office = new Work_report_for_office;
        $work_report_for_office->staff_id = $staff_id;
        $work_report_for_office->start_date = $request->search_date;
        $work_report_for_office->start_time = $request->office_start_time;
        $work_report_for_office->work_status = 'N';
        if(!empty($request->start_latitude)){
            $work_report_for_office->start_latitude = $request->start_latitude;
        }
        if(!empty($request->start_longitude)){
            $work_report_for_office->start_longitude = $request->start_longitude;
        }
         $work_report_for_office->save();
         $task_exit= DB::select("select * from work_report_for_office where staff_id='".$staff_id."' AND start_date='".$request->search_date."' ");
        return json_encode($task_exit);
    }
    public function save_staff_workleave(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $work_report_for_leave = new Work_report_for_leave;
        $work_report_for_leave->staff_id = $staff_id;
        $work_report_for_leave->start_date = $request->search_date;
        $work_report_for_leave->attendance = $request->staff_leave;
        $work_report_for_leave->reason_leave = $request->reson_leave;
        $work_report_for_leave->type_leave = $request->type_leave;
        $work_report_for_leave->save();
        $task_exit= DB::select("select * from work_report_for_office where staff_id='".$staff_id."' AND start_date='".$request->search_date."' ");
        return json_encode($task_exit);
    }

    public function check_attendence_lock(Request $request)
    {
        $staff_id = session('STAFF_ID');
       
        $task_exit= DB::select("select * from work_report_for_leave where staff_id='".$staff_id."' AND start_date='".$request->search_date."' AND system_generate_leave='Y' ");
        return count($task_exit);
    }

    
    public function filter_office_staff_task(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $task_exit= DB::select("select * from work_report_for_office where work_status='N' AND staff_id='".$staff_id."' AND start_date='".$request->search_date."'  ");
        if(count($task_exit)>0)
        {
            $exit_task= $task_exit[0]->task_child_id;
        }
        $exit_task=explode(',',$exit_task);
        $search_cond_array=array();
        if(count($exit_task)>0)
        {
            foreach($exit_task as $val)
            {   
                $search_cond_array[]	= "id!= '". $val . "'";
                $search_cond_array[]	= "id!= '". $task_exit[0]->task_id . "'";
            }
        }
        $search_condition=" AND ".join(" AND ",$search_cond_array);
      // print_r($search_cond_array);
        //$task= DB::select("select id,name,start_date from task where  (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' )  ".$search_condition." ");
        $task= DB::select("select id,name,start_date from task where  task_quick_or_normal!='nostaff' AND FIND_IN_SET('".$staff_id."', assigns)  ".$search_condition." order by start_date desc");
		 if(count($task)>0)
        {
            $output=array();
            foreach($task as $values){
                $dailttasks = DB::select("select * from dailyclosing_expence where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', travel_task_child_id) OR travel_task_id='".$values->id."') ");
                $work_report = DB::select("select * from work_report_for_office where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', task_child_id) OR task_id='".$values->id."') ");
                if(count($dailttasks)==0 && count($work_report)==0)
                {
                    $output[]=$values;
                }
            }
        }
        return json_encode($output);
        exit;
    }
    public function get_hospital_sortbytravel_task(Request $request)
    {
        $user_id=$request->user_id;
        $staff_id = session('STAFF_ID');
        $task_exit= DB::select("select * from dailyclosing_expence where travel_from_status='N' AND staff_id='".$staff_id."' AND start_date='".$request->search_date."' ");
        if(count($task_exit)>0)
        {
            $exit_task= $task_exit[0]->travel_task_child_id;
        }
        $exit_task=explode(',',$exit_task);
        $search_cond_array=array();
        if(count($exit_task)>0)
        {
            $search_cond_array[]	= "id!= '". $task_exit[0]->travel_task_id . "'";
            foreach($exit_task as $val)
            {   
               if($val>0)
               {
                $search_cond_array[]	= "id!= '". $val . "'";
               }
            }
        }
        if(count($search_cond_array)>0)
        {
            $search_condition=" AND ".join(" AND ",$search_cond_array);
        }
        else{
            $search_condition='';
        }
      // print_r($search_cond_array);
        $task= DB::select("select id,name,start_date from task where FIND_IN_SET('".$staff_id."', assigns) AND task_quick_or_normal!='nostaff'  AND user_id='".$request->user_id."'  ".$search_condition." order by start_date desc");
        if(count($task)>0)
        {
            $output=array();
            foreach($task as $values){
                $dailttasks = DB::select("select * from dailyclosing_expence where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', travel_task_child_id) OR travel_task_id='".$values->id."') ");
                $work_report = DB::select("select * from work_report_for_office where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', task_child_id) OR task_child_id='".$values->id."') ");
                if(count($dailttasks)==0 && count($work_report)==0)
                {
                    $output[]=$values;
                }
            }
        }
        return json_encode($output);
        exit;
    }
    public function get_travel_expence_staff(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $user = DB::select("select users.id,users.business_name from users as users INNER join task as task ON users.id=task.user_id where FIND_IN_SET(".$staff_id.", task.assigns) and  task.id NOT in (SELECT travel_task_id from dailyclosing_expence WHERE dailyclosing_expence.staff_id=".$staff_id." and dailyclosing_expence.start_date='".$request->sel_date."' and dailyclosing_expence.expence_cat='travel' ) group by users.id order by users.id desc");
        $travel = DB::select("select *,task.start_date as task_date ,daily_exp.start_date as travel_start_date,daily_exp.id as travel_id  from dailyclosing_expence as daily_exp INNER JOIN task as task ON daily_exp.travel_task_id=task.id where daily_exp.staff_id='".$staff_id."' and daily_exp.start_date='".$request->sel_date."' and daily_exp.expence_cat='travel'  and travel_parent_id=0");
        $expence = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."'  and start_date='".$request->sel_date."' and expence_cat='expence' and expence_section='field' ");
        $attend_status=array();
        $taskTimes=[]; 
        $travelwithchild=[];
        if(count($travel)>0)
        {
            foreach($travel as $values)
            {
                $travelwithchild[]=$values;
                $childtravel=DB::select("select *,task.start_date as task_date ,daily_exp.start_date as travel_start_date,daily_exp.id as travel_id  from dailyclosing_expence as daily_exp INNER JOIN task as task ON daily_exp.travel_task_id=task.id where daily_exp.staff_id='".$staff_id."' and daily_exp.start_date='".$request->sel_date."' and daily_exp.expence_cat='travel'  and travel_parent_id={$values->travel_id}");
                if(count($childtravel)){
                    foreach ($childtravel as $chldrow) {
                        $travelwithchild[]=$chldrow;
                    }
                }
                $taskTimes[$values->travel_id]=[];
                $taskTimes[$values->travel_id][$values->travel_task_id]=StaffTaskTime::where('dailyclosing_expence_id',$values->travel_id)->where('task_id',$values->travel_task_id)->where('staff_id',$staff_id)->first();
                if($values->travel_task_id==3446 || $values->travel_task_id==3447)
                {
                    $attend_status[]=0; 
                }
               else{
                $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->travel_task_id."'   ");
                if(count($check_task_comment)==0)
                {
                    $attend_status[]=1; 
                }
                else{
                    $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->travel_task_id."' AND status='N'  ");
                    if(count($check_task_comment_status)==0)
                    {
                        $attend_status[]=0; 
                    }
                    else{
                        $attend_status[]=1; 
                    }
                }
                if($values->travel_task_child_id!='')
                {
                  $linearray = explode(",",$values->travel_task_child_id);
                  foreach($linearray as $val_child)
                  {
                    if(!empty($val_child)){
                        $taskTimes[$values->travel_id][$val_child]=StaffTaskTime::where('dailyclosing_expence_id',$values->travel_id)->where('task_id',$val_child)->where('staff_id',$staff_id)->first();
                        if($val_child==3446 || $val_child==3447)
                        {
                            $attend_status[]=0; 
                        } 
                        else{
                    
                            $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."'   ");
                            if(count($check_task_comment)==0)
                            {
                                $attend_status[]=1; 
                            }
                            else{
                                $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND status='N'  ");
                                if(count($check_task_comment_status)==0)
                                {
                                    $attend_status[]=0; 
                                }
                                else{
                                    $attend_status[]=1; 
                                }
                            }
                        }
                    }

                  }
                }
 }
            }
        }
        else{
            $attend_status[]=1; 
        }
        if (in_array("1", $attend_status)) 
        {
            $attend_status=1;
        }
        else{
            $attend_status=0;
        }
        return json_encode($travelwithchild).'*'.json_encode($expence).'*'.$attend_status.'*'.json_encode($user)."*".json_encode($taskTimes);
    }
    public function get_travel_expence_staff_office(Request $request)
    {
        $staff_id = session('STAFF_ID');
       $expence = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and start_date='".$request->sel_date."' and expence_cat='expence' and expence_section='office'");
        return json_encode($expence);
    }

    public function carPermission(Request $request)
    {
      $result = CarApproval::where('staff_id',$request->staff_id)->where('start_date',$request->checked_date)->get();

        if($result->isEmpty()){
            return response()->json([ 'approveDate' => 'false' ]);
        }else{
            return response()->json([ 'approveDate' => 'true' ]);            
        }

    }

    public function add_task_replay_comment_expence(Request $request)
    {
        $staff_id = session('STAFF_ID');
       /* $check_task_exit = DB::select("select * from dailyclosing_expence where  travel_task_id='".$request->task_id."'  ");    
        if(count($check_task_exit)>0)
        {*/
            $update= DB::table('dailyclosing_expence')->where('id',$request->task_id)->update(['comment' => $request->replay_comment,'status' => $request->status]);
          /*  $dailyclosing_expence = Dailyclosing_expence::find($check_task_exit[0]->id);
            $dailyclosing_expence->comment=$request->replay_comment;
            $dailyclosing_expence->status=$request->status;
            $dailyclosing_expence->save();*/
       /* }*/
    }
    public function save_expence_edit_details(Request $request)
    {
        $update= DB::table('dailyclosing_expence')->where('id',$request->expence_id)->update(['status' =>"N",'expence_type' => $request->other_expence_edit,'travel_start_amount' => $request->expence_amount_edit,'expence_desc' => $request->expence_desc_edit]);
    }
    public function get_date_sort_task(Request $request)
    {
        $staff_id = session('STAFF_ID');
        // $cur_date=$request->sel_date;
        $alltask = DB::select("select * from task where FIND_IN_SET('".$staff_id."', assigns)  AND staff_status != 'Approved' order by start_date desc");
        return json_encode($alltask);
    }
    public function get_hospital_sort_task(Request $request)
    {
        $staff_id = session('STAFF_ID');
        // $cur_date=$request->sel_date;
        $alltask = DB::select("select * from task where FIND_IN_SET('".$staff_id."', assigns) AND task_quick_or_normal!='nostaff'   AND user_id='".$request->user_id."' AND staff_status != 'Approved' order by start_date desc");
        if(count($alltask)>0)
        {
            $output=array();
            foreach($alltask as $values){
                $dailttasks = DB::select("select * from dailyclosing_expence where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', travel_task_child_id) OR travel_task_id='".$values->id."') ");
                $work_report = DB::select("select * from work_report_for_office where      staff_id='".$staff_id."' AND (FIND_IN_SET('".$values->id."', task_child_id) OR task_child_id='".$values->id."') ");
                if(count($dailttasks)==0 || count($work_report)==0)
                {
                    $service_ref_number="";
                    if($values->service_id>0){
                       $serv= Service::find($values->service_id);
                       if($serv!=null){
                        $service_ref_number="[$serv->internal_ref_no]";
                       }
                    }
                    $values->service_ref_number=$service_ref_number;
                    $output[]=$values;
                }
            }
        }
        return json_encode($output);
    }
    public function get_request_leave(Request $request)
    {
        $staff_id = session('STAFF_ID');
        // $cur_date=$request->sel_date;
        $alltask = DB::select("select * from work_report_for_leave where  `staff_id`='".$staff_id."' AND type_leave='".$request->type_leave."'   AND start_date='".$request->sel_date."' order by updated_at desc");
        $leave_office_staff = DB::select("select * from work_report_for_leave where  `staff_id`='".$staff_id."'   AND start_date='".$request->sel_date."' and  (type_leave='Request Leave Field Staff' OR type_leave='Request Leave Office Staff') ");
        $attend_status=array();
        /**************************************** */
        if($request->type_leave=="Request Leave Field Staff")
        {
        $travel = DB::select("select *,task.start_date as task_date,daily_exp.start_date as travel_start_date from dailyclosing_expence as daily_exp INNER JOIN task as task ON daily_exp.travel_task_id=task.id where daily_exp.staff_id='".$staff_id."' and daily_exp.start_date='".$request->sel_date."' and daily_exp.expence_cat='travel' AND daily_exp.travel_from_status='Y'  ");
        $attend_status=array();
        if(count($travel)>0)
        {
            foreach($travel as $values)
            {
                if($values->travel_task_id=="3446" || $values->travel_task_id=="3447")
                {
                    $attend_status[]=0; 
                }
               else{
                $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->travel_task_id."'   "); 
                if(count($check_task_comment)==0)
                // if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$values->travel_task_id)->whereDate('created_at',$values->travel_start_date)->count()==0)
                {
                    $attend_status[]=1; 
                }
                else{
                    $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->travel_task_id."' AND  (status='N')  "); // OR status='R'
                    if(count($check_task_comment_status)==0)
                    // if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$values->travel_task_id)->where('status','N')->whereDate('created_at',$values->travel_start_date)->count()==0)
                    {
                        // $check_task_comment_approve_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->travel_task_id."' AND  status='Y'  ");  
                        // if($check_task_comment_approve_status>0){
                        if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$values->travel_task_id)->where('status','Y')->whereDate('created_at',$values->travel_start_date)->count()>0){
                            $attend_status[] = 0;
                        }else{
                            $attend_status[]=1; 
                        }
                    }
                    else{
                        $attend_status[]=1; 
                    }
                }
                if($values->travel_task_child_id!='')
                {
                  $linearray = explode(",",$values->travel_task_child_id);
                  foreach($linearray as $val_child)
                  {
                    if($val_child=="3446" || $val_child=="3447")
                    {
                        $attend_status[]=0; 
                    }
                else{
                    $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."'  ");
                    if(count($check_task_comment)==0)
                    // if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$val_child)->whereDate('created_at',$values->travel_start_date)->count()==0)
                    {
                        $attend_status[]=1; 
                    }
                    else{
                        $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND  (status='N' )  ");// OR status='R'
                        if(count($check_task_comment_status)==0)
                        // if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$val_child)->where('status','N')->whereDate('created_at',$values->travel_start_date)->count()==0)
                        {
                            // $check_task_comment_approve_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND  status='Y'  ");  
                            // if($check_task_comment_approve_status>0){
                            if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$val_child)->where('status','Y')->whereDate('created_at',$values->travel_start_date)->count()>0){
                                $attend_status[] = 0;
                            }else{
                                $attend_status[]=1; 
                            } 
                        }
                        else{
                            $attend_status[]=1; 
                        }
                    }
                }

                  }
                }
 }
            }
        }
        else{
            $attend_status[]=1; 
        }
//print_r($attend_status);
        if (in_array("1", $attend_status)) 
        {
            $attend_status=1;
        }
        else{
            $attend_status=0;
        }
    }//endif
        /******************************************** */
        else if($request->type_leave=="Request Leave Office Staff"){
            $task_exit_all= DB::select("select * from work_report_for_office where staff_id='".$staff_id."'  AND start_date='".$request->sel_date."' AND work_status='Y'   ");
            $attend_status=array();
            if(count($task_exit_all)>0)
            {
                foreach($task_exit_all as $values)
                {
                    if($values->task_id==3446 || $values->task_id==3447)
                    {
                        $attend_status[]=0; 
                    }
                   else{
                    $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."'   ");
                    if(count($check_task_comment)==0)
                    {
                        $attend_status[]=1; 
                    }
                    else{
                        $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."' AND  (status='N')  ");// OR status='R'
                        if(count($check_task_comment_status)==0)
                        {
                            $check_task_comment_approve_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$values->task_id."' AND  status='Y'  ");  
                            if($check_task_comment_approve_status>0){
                                $attend_status[] = 0;
                            }else{
                                $attend_status[]=1; 
                            }
                        }
                        else{
                            $attend_status[]=1; 
                        }
                    }
                    if($values->task_child_id!='')
                    {
                      $linearray = explode(",",$values->task_child_id);
                      foreach($linearray as $val_child)
                      {
                        $check_task_comment= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."'   ");
                        if(count($check_task_comment)==0)
                        {
                            $attend_status[]=1; 
                        }
                        else{
                            $check_task_comment_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND  (status='N')  ");// OR status='R'
                            if(count($check_task_comment_status)==0)
                            {
                                $check_task_comment_approve_status= DB::select("select * from task_comment where added_by_id='".$staff_id."' AND task_id='".$val_child."' AND  status='Y'  ");  
                                if($check_task_comment_approve_status>0){
                                    $attend_status[] = 0;
                                }else{
                                    $attend_status[]=1; 
                                } 
                            }
                            else{
                                $attend_status[]=1; 
                            }
                        }
                      }
                    }
                    }//id check if  
                }//end foreach
            }
            else{
                $attend_status[]=1; 
            }
            if (in_array("1", $attend_status)) 
            {
                $attend_status=1;
            }
            else{
                $attend_status=0;
            }
        }
          /******************************************** */
          if($request->type_leave=="Request Leave"){
            return json_encode($alltask).'*0';
          }
          else{
            return json_encode($alltask).'*'.$attend_status;
          }
    }
    public function get_current_monthdates(Request $request)
    {
       $cur_date=date("Y-m-d",strtotime($request->cur_year.'-'.$request->months.'-01'));
       $total_days=cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($cur_date)), date("Y",strtotime($cur_date)));
       $arr=array();
       $m= date("m",strtotime($cur_date));
       $de=1;
        $y= date("Y",strtotime($cur_date));
       for($i=0; $i<$total_days; $i++){
        $arr[]=date('Y-m-d',mktime(0,0,0,$m,($de+$i),$y));
       }
       return json_encode($arr);
    }
    public function Staffstatus()
    {
        $staff_id = session('STAFF_ID');
        $cur_date=date("Y-m-d");
        $staff = DB::select("select * from staff where name!=''  order by name ASC ");    
        return view('staff.task.Staffstatus',compact('staff'));
    }
    public function view_travel_all_details(Request $request)
    {
        $cur_date=date("Y-m-d");
        if($request->typework=="field")
        {
        $alltask = DB::select("select daily.id as expence_id,task.user_id as task_user_id,daily.task_name,daily.travel_task_child_name,daily.start_time_travel,daily.end_time_travel from dailyclosing_expence as daily INNER JOIN task as task ON daily.travel_task_id=task.id where      daily.start_date='".$cur_date."' and daily.id='".$request->id."' order by daily.id ASC");
        if(count($alltask)>0)
        {
            foreach($alltask as $values)
            {
            echo '<div class="" style="border:1px solid #ccc;padding:10px;">';
            echo '<b>Task: </b>'.$values->task_name;
            if($values->travel_task_child_name!='')
            {
                echo ','.$values->travel_task_child_name;
            }
            if($values->task_user_id>0)
            {
                echo '<br>';
                $user = User::withTrashed()->find($values->task_user_id);
                echo '<b>Hospital Name: </b>'.$user->business_name;
                echo '<br>';
                echo '<b>Hospital Address: </b>'.$user->address1;
                echo '<br>';
            }
            echo '(<b>Start Time: </b>'.$values->start_time_travel.'<b> End Time: </b> '.$values->end_time_travel.')';
            echo '<br>';
            echo '</div>';
            }
        }
    }else{
        $cur_date=date("Y-m-d");
        $alltask_office = DB::select("select work.id as expence_id,task.user_id as task_user_id,work.task_name,work.task_child_name,work.start_time,work.end_time from  work_report_for_office as work INNER JOIN task as task ON work.task_id=task.id where      work.start_date='".$cur_date."' and work.id='".$request->id."' order by work.id ASC");
        if(count($alltask_office)>0)
        {
            foreach($alltask_office as $values_office)
            {
            echo '<div class="" style="border:1px solid #ccc;padding:10px;">';
            echo '<b>Task: </b>'.$values_office->task_name;
            if($values_office->task_child_name!='')
            {
                echo ','.$values_office->task_child_name;
            }
            if($values_office->task_user_id>0)
            {
                echo '<br>';
                $user = User::withTrashed()->find($values_office->task_user_id);
                echo '<b>Hospital Name: </b>'.$user->business_name;
                echo '<br>';
                echo '<b>Hospital Address: </b>'.$user->address1;
                echo '<br>';
            }
            echo '(<b>Start Time: </b>'.$values_office->start_time.'<b> End Time: </b> '.$values_office->end_time.')';
            echo '<br>';
            echo '</div>';
            }
        }
    }                   
    }
    public function check_session()
    {
        $staff_id = session('STAFF_ID');
        if($staff_id>0)
        {
            echo '0';
        }
        else{
            echo '1';
        }
    }

    public function quicktaskcreate()
    {
       
        $staff_id = session('STAFF_ID');
        $company = DB::table('company')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $staff = DB::table('staff')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $user = DB::table('users')
        ->orderBy('business_name', 'asc')
        ->select('business_name','id')
        ->get();
        $designation = DB::table('designation')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_category = DB::table('relatedto_category')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $relatedto_subcategory = DB::table('relatedto_subcategory')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $state = DB::table('state')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
        $district = DB::table('district')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();

        $task_type = DB::table('task_type')
        ->where('status', 'Y')
        ->orderBy('id', 'asc')
        ->select('name','id')
        ->get();
       
        $category_type         = Category_type::orderBy('id','asc')->get();
   $product_type         = Product_type::orderBy('id','asc')->get();
   $brand_search = DB::select("select *,brand.id as brandid,brand.name as brandname from brand as brand inner join products as products ON brand.id=products.brand_id where brand.status='Y' group by brand.id order by brand.id asc");
   $categories = DB::select("select *,cat.image_name as catimage,cat.slug as catslug,cat.id as catid,cat.name as catname from categories as cat inner join products as products ON cat.id=products.category_id where cat.status='Y' group by cat.id order by cat.id asc");

        //return view('staff.task.create', ['relatedto_subcategory'=> $relatedto_subcategory,'relatedto_category'=> $relatedto_category,'company'=> $company,'staff'=> $staff,'user'=> $user,'designation'=> $designation,'state'=> $state,'district'=> $district]);
 
        return view('staff.task.quicktaskcreate',compact('categories','category_type','product_type','brand_search','task_type','relatedto_subcategory','relatedto_category','company','staff','user','designation','state','district'));
    }

    public function quicktask()
    {
       $task = Task::all();
       $staff_id = session('STAFF_ID');
       //  $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR `created_by_id`='".$staff_id."' ) AND (`staff_status`='Not Started')  OR (followers='".$staff_id."' and status='Not Started')");
       $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR  FIND_IN_SET('".$staff_id."', followers))  AND (task_quick_or_normal='quick' OR task_quick_or_normal='nostaff')  order by id desc");
       $staff_id = session('STAFF_ID');
       $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }

       
         return view('staff.task.quicktask', compact('task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    
     public function quicktaskAll()
    {
   // die('eeeeeeeeeeeeeeee');
       $task = Task::all();
       $staff_id = session('STAFF_ID');
       //  $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR `created_by_id`='".$staff_id."' ) AND (`staff_status`='Not Started')  OR (followers='".$staff_id."' and status='Not Started')");
       $task = DB::select("select *  from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR  FIND_IN_SET('".$staff_id."', followers))  AND (task_quick_or_normal='quick' OR task_quick_or_normal='nostaff')  order by id desc");
       $staff_id = session('STAFF_ID');
       $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $currenttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND  (start_date='".$cur_date."') ");
        $nostarttask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date>'".$cur_date."' and status='Not Started')");
        $completetask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (status='Complete')");
        $notstart =  DB::select("select count(*) as notstart from task where `status`='Not Started' ");
        $notstart= $notstart[0]->notstart;
        $testing =  DB::select("select count(*) as testing from task where `status`='Testing' ");
        $testing= $testing[0]->testing;
        $progress =  DB::select("select count(*) as progress from task where `status`='In Progress' ");
        $progress= $progress[0]->progress;
        $feed =  DB::select("select count(*) as feed from task where `status`='Awaiting Feedback' ");
        $feed= $feed[0]->feed;
        $complete =  DB::select("select count(*) as complete from task where `status`='Complete' ");
        $complete= $complete[0]->complete;
        $user = DB::table('users')
        ->orderBy('id', 'asc')->get();
        $alltask = DB::select("select * from task where (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' OR followers='".$staff_id."' ) AND (start_date!='".$cur_date."') ");
        $failed_arr=array();
        $start_due_arr=array();
        foreach($alltask as $values){
            $date = strtotime($values->start_date);
            $date = strtotime("+7 day", $date);
            $add_week= date('Y-m-d', $date);
            // echo 'date'.$values->start_date.'addwee'.$add_week;
            // echo '<br>';
            if($values->status!="Complete")
            {
               if($values->due_date=="0000-00-00")
                {
                    if(strtotime($cur_date)>strtotime($add_week))
                        {
                            $failed_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($cur_date)>strtotime($values->due_date))
                    {
                        $failed_arr[]=$values->id;
                    }
                }
                if($values->due_date=="0000-00-00")
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($add_week))
                        {
                            $start_due_arr[]=$values->id;
                        }
                }
                else
                {
                    if(strtotime($values->start_date)<=strtotime($cur_date) && strtotime($cur_date) <= strtotime($values->due_date))
                    {
                        $start_due_arr[]=$values->id;
                    }
                }
            }
       }

       
         return view('staff.task.quicktask', compact('task','start_due_arr','failed_arr','completetask','currenttask','nostarttask'));
    }
    
    public function tasksave(Request $request)
    {
        print_r($request->product_val);
       echo implode(",",$request->product_val);
        $this->validate($request, array(
            'name' => 'required|max:100'
        ));
    $random_num = rand(12345, 199999) + rand(123, 999);
    $timestamp = time();
    $unique_code = $random_num . ($timestamp + rand(10, 999));
    $cron_repeat = DB::select("select * from task where unique_code = '" . $unique_code . "'");
    if (count($cron_repeat) > 0) {
        $unique_code= time().$unique_code;
    } else {
        $unique_code= $unique_code;
    }
    $task = new Task;

    if($request->user_id>0)
    {
        $user = User::withTrashed()->find($request->user_id);
        $task_type = Task_type::find($request->name);
        $reqname=$task_type->name.'-'.$user->business_name;
    }
    else{
        $reqname=$request->name;
    }
    

    $task->name = $reqname;
    $task->unique_code = $unique_code;
    //$task->company_id = $request->company_id;
    //$task->assigned_team = $request->assigned_team;
  //  $task->assigns = $request->assigns;
  
    //$task->related_to = $request->related_to;
   // $task->related_to_sub = $request->related_to_sub;
    $task->user_id = $request->user_id;
    if($request->start_date=='')
    {
        $task->start_date = date("Y-m-d");
        $task->due_date =date("Y-m-d");
    }
    else{
        $task->start_date = $request->start_date;
        $task->due_date = $request->start_date;
    }
    
    $task->start_time = $request->start_time;
    $task->contact_person_id = $request->contact_person_id;
    $task->task_type = $request->task_type;
  
    $task->priority = 'Medium';
    $task->repeat_every = 'Days';
    $task->cycles = 0;
    $task->state_id = $request->state_id;
    $task->district_id = $request->district_id;
    $task->unlimited_cycles = "N";
    
    $task->product_id = implode(",",$request->product_val);

    $task->description = $request->description;
    $task->followup_action = $request->followup_action;
   
    $staff_id = session('STAFF_ID');

    $task->company_id = 5;
    $task->related_to = 18;
        
   
        if($request->followers=='')
        {
            $task->task_quick_or_normal='nostaff';
            $task->assigns = $staff_id;
            if($staff_id==55)
            {
                $task->followers = 55;
            }
            else{
                $task->followers = 29;
            }
           
        }
        else{
            $task->task_quick_or_normal='quick';
            $task->assigns = $request->followers;
            
            if($task->followers==$staff_id)
            {

                if($staff_id==55)
                {
                    $task->followers =55;
                }
                else{
                    $task->followers =29;
                }

            }
            else{

                if($staff_id==55)
                {
                    $task->followers = $staff_id.',55';
                }
                else{
                    $task->followers = $staff_id.',29';
                }

            }
           

        }
       
   
     $task->created_by_name="staff";
     $task->created_by_id=$staff_id;
    
    if($request->call_status)
    {
        $task->call_status = $request->call_status;
    }
    if($request->email_status)
    {
        $task->email = $request->email_status;
    }
    if($request->visit_status)
    {
        $task->visit = $request->visit_status;
    }
    $task->status ='Pending';
    $task->save();

    $task_comment = new Task_comment;

    if($request->call_status)
    {
        $task_comment->call_status = $request->call_status;
    }
    else{
        $task_comment->call_status = 'N';
    }
    if($request->email_status)
    {
        $task_comment->email = $request->email_status;
    }
    else{
        $task_comment->email = 'N';
    }
    if($request->visit_status)
    {
        $task_comment->visit = $request->visit_status;
    }else{
        $task_comment->visit = 'N';
    }

   
    $task_comment->contact_id = $request->contact_person_id;
    if($request->contact_person_id!='')
    {
        $contact_names=explode(",",$request->contact_person_id);
        $contact_person_name='';
        foreach($contact_names as $valcon)
        {
            if($valcon>0){
                $contact_person = Contact_person::find($valcon);
                $contact_person_name .=$contact_person->name.',';
            }
        }
    }
   
    
    $task_comment->contact_name = substr($contact_person_name, 0, -1);
    
    $task_comment->comment = $request->description;
    $task_comment->task_id = $task->id;

    $task_comment->added_by = 'staff';

    

    if($request->followers=='')
    {
        $task_comment->added_by_id = session('STAFF_ID');
        $task_comment->quick_task_comment='N';
    }
    else{
        $task_comment->added_by_id = $request->followers;
        $task_comment->quick_task_comment='Y';
    }


    $task_comment->save();

    $cur_date=date('Y-m-d');
    $staff_id = session('STAFF_ID');

  
   
   return redirect()->route('staff.quicktask')->with('success','Data successfully saved.');
    
   // return view('staff.task.quicktask', compact('task'));
    }
    public function change_user_get_contact_person(Request $request)
    {
      $contact = DB::select("select * from contact_person where  `user_id`='".$request->user_id."'  order by name asc");
      return json_encode($contact);
    }

    public function save_task_type(Request $request)
    {
        $task_type_exit = DB::select("select * from task_type where  `name`='".$request->task_type."'  ");
        $task_type_data = DB::select("select * from task_type order by name asc  ");
        if(count($task_type_exit)==0)
        {
            $task_type = new Task_type;
            $task_type->name=$request->task_type;
          
            $task_type->save();
            echo '0*'.json_encode($task_type_data);
        }
        else{
            echo '1*'.json_encode($task_type_data);
        }
       
    }

    public function search_product_cat_brand_type(Request $request)
    {
        echo '11';exit;
    }
    

}
