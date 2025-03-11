<?php
 
namespace App\Http\Controllers\staff;

use App\Dailyclosing_expence;
use App\Http\Controllers\Controller;
use App\Models\StaffTaskTime;
use App\OppertunityTask;
use App\Task;
use App\Task_comment;
use App\Work_report_for_leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkReportController extends Controller{
    // public function index(){
    //     $staff_id = session('STAFF_ID');
    //     $cur_date=date("Y-m-d"); 
    //     $months = array('January','February','March','April','May','June','July ','August','September','October','November','December');
    //     $attendence = Work_report_for_leave::where('staff_id',$staff_id)->whereBetween('start_date',[Carbon::now()->startOfMonth()->toDateString(),Carbon::now()->endOfMonth()->toDateString()])->whereIn('type_leave',['Request Leave Office Staff','Request Leave Field Staff'])->count();
    //     $fullday = Work_report_for_leave::where('staff_id',$staff_id)->whereBetween('start_date',[Carbon::now()->startOfMonth()->toDateString(),Carbon::now()->endOfMonth()->toDateString()])->where('type_leave','Request Leave')->where('attendance','Full Day')->count();
    //     $halfday = Work_report_for_leave::where('staff_id',$staff_id)->whereBetween('start_date',[Carbon::now()->startOfMonth()->toDateString(),Carbon::now()->endOfMonth()->toDateString()])->where('type_leave','Request Leave')->where('attendance','Half Day')->count()/2;
    //     $leave=$fullday+$halfday;
    //     $notupdated=intval(date("d"))-($attendence+$leave);  
    //     return view("staff.work-report.index",compact('staff_id','cur_date','months','attendence','leave','notupdated'));
    // }
    public function expence(Request $request){
        $staff_id = session('STAFF_ID');
        $date=$request->date??date("Y-m-d");  
        $opt=$request->option??"";
        $expence=[];
        if($opt=="Work Update Field Staff"){
            $expence=Dailyclosing_expence::where('start_date',$date)->where('staff_id', $staff_id)->where('expence_cat','expence')->where('expence_section','field')->get();
        }
        return $expence;
    }
    public function attendance(Request $request){
        $staff_id = session('STAFF_ID');
        $date=$request->date??date("Y-m-d"); 
        $attendance=false;

        $opt=$request->option??"";
        if($opt=="Work Update Field Staff"){
            foreach (Dailyclosing_expence::join("task", "dailyclosing_expence.travel_task_id", "task.id")->whereNotIn("dailyclosing_expence.travel_task_id",["3446","3447"])->where('dailyclosing_expence.travel_parent_id',0)->where('dailyclosing_expence.start_date',$date)->where('dailyclosing_expence.staff_id', $staff_id)->where('dailyclosing_expence.expence_cat', 'travel')->get(['dailyclosing_expence.*', 'task.*', 'dailyclosing_expence.id as expence_id', 'task.start_date as task_date', "dailyclosing_expence.start_date as travel_start_date"]) as $row) {
                if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$row->travel_task_id)->whereDate('created_at',$row->travel_start_date)->count()==0){
                    $attendance=true;
                }else{
                    if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$row->travel_task_id)->where('status','N')->whereDate('created_at',$row->travel_start_date)->count()>0){
                        $attendance=true;
                    }
                }
                foreach (explode(",",$row->travel_task_child_id??"") as $cid) {
                    if(!empty($cid)){
                        if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$cid)->whereDate('created_at',$row->travel_start_date)->count()==0){
                            $attendance=true;
                        }else{
                            if(Task_comment::where('added_by_id',$staff_id)->where('task_id',$cid)->where('status','N')->whereDate('created_at',$row->travel_start_date)->count()>0){
                                $attendance=true;
                            }
                        }
                    }
                }
            }
            if(Dailyclosing_expence::join("task", "dailyclosing_expence.travel_task_id", "task.id")->whereNotIn("dailyclosing_expence.travel_task_id",["3446","3447"])->where('dailyclosing_expence.travel_parent_id',0)->where('dailyclosing_expence.start_date',$date)->where('dailyclosing_expence.staff_id', $staff_id)->where('dailyclosing_expence.expence_cat', 'travel')->count()==0){
                if(Dailyclosing_expence::where('start_date',$date)->where('staff_id', $staff_id)->where('expence_cat','expence')->where('expence_section','field')->count()==0){
                    $attendance=true;
                }
            }

            $today_date = now()->format('Y-m-d');

            $opper_update = OppertunityTask::whereDate('created_at', $today_date)->get();

            foreach ($opper_update as $item) {
               
                if ($item->staff_status != 'Approved') {
                    $attendance = true; 
                }
            }

        }
        return response()->json([
            "attendance"=>$attendance?"N":"Y"
        ]);
    }
    public function show(Request $request){
        $staff_id = session('STAFF_ID');
        $maxdate = date('Y-m-d', strtotime('11 days'));
        $mindate = date('Y-m-d', strtotime('-30 days'));
        $date=$request->date??date("Y-m-d");

        if(empty($date)||strtotime($date)>strtotime($maxdate)||strtotime($date)<strtotime($mindate)){
            return redirect()->route('staff.WorkReport')->with('error','Date Not Allowed');
        }else{ 
            $cur_date=Carbon::parse($date);
            if($request->ajax()){
                $opt=$request->option??"";
                if($opt=="Work Update Field Staff"){
                    $travels=[];
                    foreach (Dailyclosing_expence::join("task", "dailyclosing_expence.travel_task_id", "task.id")->where('dailyclosing_expence.travel_parent_id',0)->where('dailyclosing_expence.start_date',$date)->where('dailyclosing_expence.staff_id', $staff_id)->where('dailyclosing_expence.expence_cat', 'travel')->get(['dailyclosing_expence.*', 'task.*', 'dailyclosing_expence.id as expence_id', 'task.start_date as task_date', "dailyclosing_expence.start_date as travel_start_date"]) as $row) {
                        $row->start_date =$row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '';
                        $row->endurl=route("staff.work-report.update",$row->expence_id);
                        $row->addurl=route("staff.work-report.store",$row->expence_id);
                        $childtravels=[];
                        foreach (Dailyclosing_expence::join("task", "dailyclosing_expence.travel_task_id", "task.id")->where('dailyclosing_expence.travel_parent_id',$row->expence_id)->where('dailyclosing_expence.start_date',$date)->where('dailyclosing_expence.staff_id', $staff_id)->where('dailyclosing_expence.expence_cat', 'travel')->get(['dailyclosing_expence.*', 'task.*', 'dailyclosing_expence.id as expence_id', 'task.start_date as task_date', "dailyclosing_expence.start_date as travel_start_date"]) as $ch) {
                            $ch->endurl=route("staff.work-report.update",$ch->expence_id);
                            $childtravels[]=$ch;
                        }
                        $row->child_travel=$childtravels;
                        $tasks=[];
                        $task=Task::find($row->travel_task_id);
                        if(!empty($task)){
                            $task->staff_task_time=StaffTaskTime::where('dailyclosing_expence_id',$row->expence_id)->where('task_id',$task->id)->where('staff_id',$staff_id)->first();
                            $tasks[]=$task;
                        }  
                        foreach (explode(",",$row->travel_task_child_id??"") as $tid) {
                            if(!empty($tid)){
                                $ctask=Task::find($tid);
                                if(!empty($ctask)){
                                    $ctask->staff_task_time=StaffTaskTime::where('dailyclosing_expence_id',$row->expence_id)->where('task_id',$ctask->id)->where('staff_id',$staff_id)->first();
                                    $tasks[]=$ctask;
                                }  
                            }
                        }
                        $row->travel_tasks=$tasks;
                        $travels[]=$row;
                    }
                    return $travels;
                }
            }
            return view('staff.work-report.show',compact('staff_id','cur_date'));
        }
    }
    public function store(Request $request,$id){
        $staff_id = session('STAFF_ID');
        $parent=Dailyclosing_expence::findOrFail($id);

        $type=$request->travel_type??"";
        
        if($type=="Bike"||$type=="Car"){
            $this->validate($request,[
                'start_time'=>["required"],
                'travel_type'=>["required"],
                'meter_start'=>['required','numeric']
            ]);
        }elseif(!empty($type)){
            $this->validate($request,[
                'start_time'=>["required"],
                'travel_type'=>["required"],
                'amount'=>['required','numeric']
            ]);
        }else{
            $this->validate($request,[
                'start_time'=>["required"],
                'travel_type'=>["required"], 
            ]);
        }



        $dailyclosing_expence = new Dailyclosing_expence();
        $dailyclosing_expence->travel_parent_id=$parent->id;

        if ($request->fair_doc != '') {
            $imageName = time() . $request->fair_doc->getClientOriginalName();
            $imageName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path = storage_path();
            $img_path = $request->fair_doc->storeAs('public/comment', $imageName);
            $path = $path . '/app/' . $img_path;
            chmod($path, 0777);
        } else {
            $imageName = "";
        }
        $dailyclosing_expence->start_date = $parent->start_date;
             
        $dailyclosing_expence->travel_task_id =  $parent->travel_task_id ;
        $dailyclosing_expence->task_name =  $parent->task_name ;
        
        $dailyclosing_expence->travel_task_child_id = $parent->travel_task_child_id;
        $dailyclosing_expence->travel_task_child_name = $parent->travel_task_child_name;
        $dailyclosing_expence->travel_task_child_date = $parent->travel_task_child_date;
        $dailyclosing_expence->start_time_travel = $request->start_time;
        $dailyclosing_expence->staff_id = $staff_id;
        $dailyclosing_expence->travel_type = $request->travel_type;
        $dailyclosing_expence->start_meter_reading = $request->meter_start??"";
        $dailyclosing_expence->travel_start_amount = $request->amount??"";
        $dailyclosing_expence->travel_start_image = $imageName;
        $dailyclosing_expence->expence_cat = 'travel';
        $dailyclosing_expence->status = 'N';
        $dailyclosing_expence->user_id = $parent->user_id;
        $dailyclosing_expence->travel_from_status = 'N';

        if(!empty($request->travel_start_latitude)){
            $dailyclosing_expence->travel_start_latitude=$request->travel_start_latitude;
        }
        if(!empty($request->travel_start_longitude)){
            $dailyclosing_expence->travel_start_longitude=$request->travel_start_longitude;
        }

        $dailyclosing_expence->save();

        return response()->json([
            "success"=>"Travel added successfuly!."
        ]);
    }
    public function update(Request $request,$id){
        $staff_id = session('STAFF_ID');
        $dailyclosing_expence=Dailyclosing_expence::findOrFail($id);
        if($dailyclosing_expence->travel_type=="Bike"||$dailyclosing_expence->travel_type=="Car"){
            $this->validate($request,[
                'meter_end'=>["required","numeric","gt:".($dailyclosing_expence->start_meter_reading??0)],
                'end_time'=>["required"], 
            ],[
                "meter_end.gt"=>"The meter end must be greater than meter start."
            ]);
        }else{
            $this->validate($request,[
                'amount'=>["required","numeric"],
                'end_time'=>["required"], 
            ]);
        }

        if ($request->fair_doc_end != '') {
            $imageName1 = time() . $request->fair_doc_end->getClientOriginalName();
            $imageName1 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
            $path = storage_path();
            $img_path = $request->fair_doc_end->storeAs('public/comment', $imageName1);
            $path = $path . '/app/' . $img_path;
            chmod($path, 0777);
        } else {
            $imageName1 = "";
        }
         
        $dailyclosing_expence->end_time_travel = $request->end_time;
        $dailyclosing_expence->end_meter_reading = $request->meter_end??"";
        $dailyclosing_expence->travel_end_amount = $request->amount??"";
        $dailyclosing_expence->travel_end_image = $imageName1;
        $dailyclosing_expence->travel_from_status = 'Y'; 
 
        if(!empty($request->travel_end_latitude)){
            $dailyclosing_expence->travel_end_latitude=$request->travel_end_latitude;
        }
        if(!empty($request->travel_end_longitude)){
            $dailyclosing_expence->travel_end_longitude=$request->travel_end_longitude;
        }
        $dailyclosing_expence->save();
        return response()->json([
            "success"=>"Travel ended successfuly!."
        ]);
    }
}