<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\District;
use App\Models\OppertunityAttachement;
use App\Models\QuoteOptionalProduct;
use App\Models\QuoteProduct;
use App\Models\StaffPaidTarget;
use App\Models\StaffTarget;
use App\Models\StaffTargetCommission;
use App\Oppertunity;
use App\Oppertunity_product;
use App\Product;
use App\Quotehistory;
use App\Staff;
use App\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\OppertunityApproveStatus;
use App\Models\OppertunityApproveStatusAttachement;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
class StaffTargetController extends Controller
{
    public function targetCommission(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $adminA=["33","36",'37',"31"];

        $adminB=["39","30","32"];
        if(!in_array($staff_id,$adminB)&&!in_array($staff_id,$adminA)){
            return redirect(URL::to('staff'));
        }



        $order=empty($request->order) ? "desc" : $request->order;
        $sort=empty($request->sort) ? "id" : $request->sort;
        $page=empty($request->page) ? 1 : $request->page;
        $staff=empty($request->staff) ? 0 : $request->staff;
        $data=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage', [6,7,8]);

        $availabledates=[];
        $year=$request->year;
        $month=$request->month;
        $status=$request->status;
        $complete=$request->complete;
        $commission_status=$request->billing_status;

        if(!in_array($staff_id,$adminB)&&$commission_status=="Technical Approval"){
            $data->where("commission_status","!=","Technical Approval");
        }

        if($staff>0) {
            $data->where('staff_id', $staff);
            $maxdate=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->where('staff_id', $staff)->where('deal_stage', 8)->max('won_date');
            $mindate=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->where('staff_id', $staff)->where('deal_stage', 8)->min('won_date');
            if(!empty($maxdate)&&!empty($mindate)) {
                $curdate=Carbon::parse($maxdate);
                $dc=0;
                do {
                    if(Oppertunity::where('staff_id', $staff)->where('deal_stage', 8)->whereBetween("won_date", [$curdate->startOfMonth()->toDateString(),$curdate->endOfMonth()->toDateString()])->count()>0) {
                        array_push($availabledates, (object)[
                            "year"=>$curdate->format('Y'),
                            "month"=>$curdate->format('m'),
                            "name"=>$curdate->format('Y F'),

                        ]);
                        $dc++;
                    }
                    $curdate->subMonth();
                } while(Carbon::parse($mindate)->lt($curdate)&&$dc<20);
            }
            if(!empty($request->year)&&!empty($request->month)) {
                $filterdate=Carbon::parse("$year-$month-15");
                $data->whereBetween("won_date", [$filterdate->startOfMonth()->toDateString(),$filterdate->endOfMonth()->toDateString()]);
            }
            if(!empty($request->status)) {
                switch($request->status) {
                    case "Won":$data->where('deal_stage', 8);
                    break;
                    case "Cancel":$data->where('deal_stage', 7);
                    break;
                    case "Lost":$data->where('deal_stage', 6);
                    break;
                    default:break;
                }
            }
        }
        if(!empty($request->billing_status)) {
            $data->where("commission_status", $request->billing_status);
        }
        if(!empty($request->complete)) {
            $data->where("complete_status", "Y");
        }
        $data=$data->orderBy($sort, $order)->paginate(25);

        $neworder=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $initialcheck=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $technichal=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $final=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $billing=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $dispatch=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $documentation=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $supply=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $paymentfollow=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $audit=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);

        $statuslist=[
            ["name"=>'New Orders',"display"=>'New Orders','count'=>$neworder->where('commission_status',"New Orders")->count()],
            ["name"=>"Initial Check","display"=>'Initial Check','count'=>$initialcheck->where('commission_status',"Initial Check")->count()],
            ["name"=>'Technical Approval',"display"=>'Technical Approval','count'=>$technichal->where('commission_status',"Technical Approval")->count()],
            ["name"=>'Finance Approval',"display"=>'Finance Approval','count'=>$final->where('commission_status',"Finance Approval")->count()],
            ["name"=>'Billing',"display"=>'Billing','count'=>$billing->where('commission_status',"Billing")->count()],
            ["name"=>'Dispatch',"display"=>'Dispatch','count'=>$dispatch->where('commission_status',"Dispatch")->count()],
            ["name"=>'Documentation',"display"=>'Documentation','count'=>$documentation->where('commission_status',"Documentation")->count()],
            ["name"=>'Supply Issue',"display"=>'Supply Issue','count'=>$supply->where('commission_status',"Supply Issue")->count()],
            ["name"=>'Payment Follow Up',"display"=>'Payment Follow Up','count'=>$paymentfollow->where('commission_status',"Payment Follow Up")->count()],
            ["name"=>"Audit","display"=>"Audit",'count'=>$audit->where('commission_status',"Audit")->count()]
        ];  
        $staffs=Staff::orderBy('name', 'asc')->get();
        return view('staff.won_approval.index', compact('statuslist','data', 'order', 'sort', 'staff', 'page', 'staffs', 'availabledates', 'year', 'month', 'status', 'complete', 'commission_status'));
    }

    public function showstatus(Request $request,$oppertunity){
        $opportunity=Oppertunity::find($oppertunity);
        $oppertunitystatus=OppertunityApproveStatus::where("oppertunity_id",$opportunity->id)->get();
        return view("staff.won_approval.oppertunityStatus",compact('oppertunitystatus','opportunity'));
    }

    public function addOpurtunityStatusAttaches(Request $request){
        $this->validate($request,[
            "upload_file"=>['required','file','max:5120','mimes:jpg,bmp,jpeg,png,pdf']
        ]);
        $avathar = "public/comment";
        $file = $request->file('upload_file');
        $name = $file->hashName();
        Storage::put("{$avathar}", $file);
        return response()->json([
            'name' => "{$name}",
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => "{$avathar}/{$name}",
            'url' =>asset("public/storage/comment/$name"),
            'size' => $file->getSize(),
        ]);
    }
    public function approveStatusEdit(Request $request,$oppertunity){
        $item=Oppertunity::find($oppertunity);
        $lastapprove=OppertunityApproveStatus::where("oppertunity_id",$item->id)->where('approve_status','Approve')->where('status','Y')->orderBy("id","DESC")->first();
        $oppertunitystatus=[
            "Initial Check"=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Initial Check")->orderBy("id","DESC")->first(),
            'Technical Approval'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Technical Approval")->orderBy("id","DESC")->first(),
            'Finance Approval'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Finance Approval")->orderBy("id","DESC")->first(),
            'Billing'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Billing")->orderBy("id","DESC")->first(),
            'Dispatch'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Dispatch")->orderBy("id","DESC")->first(),
            'Documentation'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Documentation")->orderBy("id","DESC")->first(),
            'Supply Issue'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Supply Issue")->orderBy("id","DESC")->first(),
            'Payment Follow Up'=>OppertunityApproveStatus::where("oppertunity_id",$item->id)->where("approve_stage","Payment Follow Up")->orderBy("id","DESC")->first(),
        ];
        return view("staff.won_approval.updateOppertunityStatus",compact('oppertunitystatus','item','lastapprove'));
    }
    public function approveStatus(Request $request,$oppertunity){
        $staff_id = session('STAFF_ID');
        $this->validate($request,[
            "approve_comment"=>"required",
            "approve_stage"=>"required",
            "approve_status"=>"required",
        ]);
        $availableStatus=['New Orders',"Initial Check",'Technical Approval','Finance Approval','Billing','Dispatch','Documentation','Supply Issue','Payment Follow Up','Audit'];

        $opportunity=Oppertunity::find($oppertunity);
        if($request->approve_status=="Approve"){
            if($request->approve_stage=="Audit"){
                $opportunity->complete_status="Y";
            }
            $opportunity->commission_status=$request->approve_stage;
            $opportunity->save();
            $oppertunitystatus=new OppertunityApproveStatus;
            $oppertunitystatus->oppertunity_id=$opportunity->id;
            $oppertunitystatus->staff_id=$staff_id;
            $oppertunitystatus->approve_comment=$request->approve_comment;
            $oppertunitystatus->approve_stage=$request->approve_stage;
            $oppertunitystatus->approve_status=$request->approve_status;
            $oppertunitystatus->status="Y";
            $oppertunitystatus->save();
            if(!empty($request->file_name)){
                for ($i=0; $i < count($request->file_name); $i++) { 
                    $file=new OppertunityApproveStatusAttachement;
                    $file->attachement=$request->file_name[$i];
                    $file->name=$request->display_name[$i];
                    $file->attach_type=$request->file_type[$i];
                    $file->attach_path=$request->file_path[$i];
                    $file->attach_url=$request->file_url[$i];
                    $file->oppertunity_approve_status_id=$oppertunitystatus->id;
                    $file->oppertunity_id=$opportunity->id;
                    $file->save();
                }
            }
        }else{
            $oppertunitystatus=new OppertunityApproveStatus;
            $oppertunitystatus->oppertunity_id=$opportunity->id;
            $oppertunitystatus->staff_id=$staff_id;
            $oppertunitystatus->approve_comment=$request->approve_comment;
            $oppertunitystatus->approve_stage=$request->approve_stage;
            $oppertunitystatus->approve_status=$request->approve_status;
            $oppertunitystatus->status="N";
            $oppertunitystatus->save();

            if(!empty($request->file_name)){
                for ($i=0; $i < count($request->file_name); $i++) { 
                    $file=new OppertunityApproveStatusAttachement();
                    $file->attachement=$request->file_name[$i];
                    $file->name=$request->display_name[$i];
                    $file->attach_type=$request->file_type[$i];
                    $file->attach_path=$request->file_path[$i];
                    $file->attach_url=$request->file_url[$i];
                    $file->oppertunity_approve_status_id=$oppertunitystatus->id;
                    $file->oppertunity_id=$opportunity->id;
                    $file->save();
                }
            }
            $index=array_search($request->approve_stage,$availableStatus);
            if($index&&$index>1){
                for($i=$index;$i<count($availableStatus);$i++){
                    foreach(OppertunityApproveStatus::where("oppertunity_id",$opportunity->id)->where("approve_stage",$availableStatus[$i])->where("approve_status","Approve")->where('status',"Y")->get() as $sts ){
                        $sts->staff_id=$staff_id;
                        $sts->status="N";
                        $sts->closed_at=Carbon::now()->toDateTimeString();
                        $sts->save();
                    }
                }
            }else{
                foreach(OppertunityApproveStatus::where("oppertunity_id",$opportunity->id)->where("approve_status","Approve")->where('status',"Y")->get() as $sts ){
                    $sts->staff_id=$staff_id;
                    $sts->status="N";
                    $sts->closed_at=Carbon::now()->toDateTimeString();
                    $sts->save();
                }
                $opportunity->commission_status="New Orders";
                $opportunity->save();
            }                
            $last=OppertunityApproveStatus::where("oppertunity_id",$opportunity->id)->where("approve_status","Approve")->where('status',"Y")->orderby("id","DESC")->first();
            if(!empty($last)){
                $opportunity->commission_status=$last->approve_stage;
                $opportunity->save();
            }else{
                $opportunity->commission_status="New Orders";
                $opportunity->save();
            }
        }
        return response()->json(["success"=>"Status Updated","data"=>$opportunity]);
    }
}