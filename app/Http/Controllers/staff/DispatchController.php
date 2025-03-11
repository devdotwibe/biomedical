<?php
namespace App\Http\Controllers\staff;
use App\Dispatch;
use App\Invoice;
use App\Dispatch_product;
use App\Transation_product;
use App\Transation_staff_updates;
use App\Invoice_complete_flow;
use App\Courier;
use App\Staff;
use App\Warehouse;
use App\Invoice_product;
use App\Transaction_manage_staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Transation;
use App\Work_report_for_leave;

use Image;
use Storage;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff_id = session('STAFF_ID');
        $dispatch = Dispatch::orderBy('updated_at', 'desc')->get();
        $transation_pending =  DB::select("select * from  transation_staff_updates where staff_id='".$staff_id."' AND  current_status='Pending'  order by id desc");
     
        return view('staff.dispatch.index',compact('dispatch','transation_pending'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(isset($_GET['id'])){
        $id=$_GET['id'];
      }
      else{
        $id=0;
      }
        $courier = Courier::all();
        $staff = Staff::orderBy('name', 'asc')->get();
        $warehouse = Warehouse::all();
        $invoice_product=Invoice_product::where('invoice_id',$id)->get();
        $transaction =  DB::select("select * from  transation where financial_approval_status='Completed' AND invoice_status='Pending'");

        return view('staff.dispatch.create', ['transaction'=>$transaction,'invoice_product'=>$invoice_product,'warehouse'=> $warehouse,'courier'=> $courier,'staff'=> $staff,'invoice_id'=>$id]);
    
    }
    
    public function get_transaction_for_dispatch(Request $request)
    {

       
        $courier = Courier::all();
        $staff = Staff::orderBy('name', 'asc')->get();
        $warehouse = Warehouse::all();
        $transaction_product=Transation_product::where('transation_id',$request->transaction_id)->get();
        $transaction =  DB::select("select * from  transation where financial_approval_status='Completed' AND invoice_status='Pending'");

        return view('staff.dispatch.dispatch_withoutinvoice', ['transaction'=>$transaction,'transaction_product'=>$transaction_product,'warehouse'=> $warehouse,'courier'=> $courier,'staff'=> $staff]);
    
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
            'courier_id' => 'required',
            ));

        $dispatch = new Dispatch;

        $dispatch->invoice_id = $request->invoice_id;
        if($request->invoice_id>0)
        {
            $invoice=Invoice::where('id',$request->invoice_id)->first();   
            $transaction_id=$invoice->transaction_id;
        }
        else{
            $invoice=Transation::where('id',$request->transaction_id)->first();
            $transaction_id=$invoice->id;
        }
        
        $dispatch->courier_id = $request->courier_id;
        $dispatch->verified_staff = $request->verified_staff;
        $dispatch->dispatch_date = date("Y-m-d");
        $dispatch->invoice_no = $request->invoice_id;
        $dispatch->transation_id= $transaction_id;
        $dispatch->save();

        if($request->invoice_id>0)
        {
            $trans_staff_update = new Transation_staff_updates;
            $trans_staff_update->invoice_id = $request->invoice_id;
            $trans_staff_update->transation_id = $transaction_id;
            $trans_staff_update->dispatch_id = $dispatch->id;
            $trans_staff_update->status = 'Dispatch Verify';
            $trans_staff_update->current_status = 'Pending';
            $trans_staff_update->added_date = date("y-m-d");
            $trans_staff_update->staff_id = $request->verified_staff;
            $trans_staff_update->user_id = $invoice->user_id;
            $trans_staff_update->type_approval ='Invoice';
            $trans_staff_update->save();
        }
        else{
            $trans_staff_update = new Transation_staff_updates;
           // $trans_staff_update->invoice_id = $request->invoice_id;
            $trans_staff_update->transation_id = $transaction_id;
            $trans_staff_update->dispatch_id = $dispatch->id;
            $trans_staff_update->status = 'Dispatch Without Invoice';
            $trans_staff_update->current_status = 'Pending';
            $trans_staff_update->added_date = date("y-m-d");
            $mange_staff=Transaction_manage_staff::where('manage_section','Invoice')->first();
            $trans_staff_update->staff_id = $mange_staff->staff_id;
            $trans_staff_update->user_id = $invoice->user_id;
            $trans_staff_update->type_approval ='Invoice';
            $trans_staff_update->save(); 
        }
       
       

        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Verification' WHERE  
          invoice_id='".$request->invoice_id."' AND status='Dispatch Invoice' ");



         $quantity_count=count($request->quantity);
        if($quantity_count>0)
        {

           for($i=0;$i<$quantity_count;$i++)
           {
            if($request->invoice_id>0)
            {

            }
            else{
                // $cur_product=Transation_product::where('transation_id',$request->transaction_id[$i])
                // ->where('product_id',$request->product_id[$i])
                // ->first();
    
                //  $cur_product->out_product_quantity += $request->quantity[$i];
                // $cur_product->save();
                $transation_product = Transation_product::find($request->ids[$i]);
                $out_product_quantity= $transation_product->out_product_quantity;
                $balance=$out_product_quantity+$request->quantity[$i];
                $transation_product->out_product_quantity=$balance;
                $transation_product->save();
            }
           
            
            $dispatch_product = new Dispatch_product;
            $dispatch_product->dispatch_id = $dispatch->id;
            $dispatch_product->quantity = $request->quantity[$i];
            $dispatch_product->product_name = $request->product_name[$i];
            $dispatch_product->product_id = $request->product_id[$i];
            $dispatch_product->hsn = $request->hsn[$i];
            $dispatch_product->source = $request->source[$i];
            if($request->source[$i]=="Staff")
            {
               // $dispatch_product->staff_id = $request->staff_id[$i];
            }
            else{
               // $dispatch_product->warehouse_id = $request->warehouse_id[$i];
            }
            $dispatch_product->save();
             }
        }

        if($request->invoice_id>0)
            {

            }
            else{
        $transaction_chek 		= Transation_product::where('transation_id',$transaction_id)->whereRaw('quantity > out_product_quantity')->get();
        if(count($transaction_chek)==0)
        {
         
            $transation = Transation::find($transaction_id);
       $transation->invoice_status='Completed';
       $transation->save();
       $mange_staff_update=Transation_staff_updates::where('transation_id',$transaction_id)
       ->where('status',"Invoice")
       ->update(array('current_status' => "Approval"));

       $mange_staff_update=Transation_staff_updates::where('transation_id',$transaction_id)
       ->where('status',"Dispatch Invoice")
       ->where('type_approval',"Sale Order")
       ->update(array('current_status' => "Approval"));

        }
    }

      

        return redirect()->route('staff.Pendingtransaction')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatch  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dispatch $dispatch)
    {
       

        return redirect()->route('staff.brand.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispatch $dispatch)
    {
       
    }

    public function deleteAll(Request $request)
    {
       

    }

    public function dispatch_approval_update(Request $request)
    {
       //echo '11';exit;

       $dispatch = Dispatch::find($request->dispatch_id);
       if($request->eway_status)
       {
        $dispatch->eway_status= $request->eway_status;
       }
       else{
        $dispatch->eway_status= 'N';
       }
       
        if($request->upload_eway_bill!='')
        {
        $file      = $request->upload_eway_bill;
        $imageName = time().$file->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path      =  storage_path();
        $img_path  = $file->storeAs('public/transation', $imageName);
        $path      = $path.'/app/'.$img_path;
        chmod($path, 0777);
        $dispatch->upload_eway_bill= $imageName;
        }
        $dispatch->eway_bill_no= $request->eway_bill_no;
        $dispatch->eway_bill_date= $request->eway_bill_date;
        $dispatch->eway_bill_remark= $request->eway_bill_remark;
        
        $dispatch->save();

        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval' WHERE  `status`='Dispatch Invoice' AND  invoice_id='".$request->invoice_id."'  ");
        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval' WHERE  `status`='Dispatch Verify' AND  invoice_id='".$request->invoice_id."'  ");

       // $invoice=Invoice::where('id',$request->invoice_id)->first();
        $transation=Transation::where('id',$request->transaction_id)->first();

       $invoice_complete_flow = Invoice_complete_flow::find($transation->invoice_complete_flow_id);
       $delivery_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->delivery.' days'));
   /* $invoice_update = Invoice::find($request->invoice_id);
    $invoice_update->status = 'Delivery Invoice';
   
    $delivery_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->delivery.' days'));
    $invoice_update->delivery_date = $delivery_date;
    $user_date=date('Y-m-d', strtotime($delivery_date. ' + '.$invoice_complete_flow->user_confirm.' days'));
    $invoice_update->user_date = $user_date;
    $department_date=date('Y-m-d', strtotime($user_date. ' + '.$invoice_complete_flow->dept_confirm.' days'));
    $invoice_update->department_date=$department_date;
    $finance_date=date('Y-m-d', strtotime($department_date. ' + '.$invoice_complete_flow->finance_confirm.' days'));
    $invoice_update->finance_date=$finance_date;
    $payment_date=date('Y-m-d', strtotime($finance_date. ' + '.$invoice_complete_flow->payment_confirm.' days'));
    $invoice_update->payment_date=$payment_date;
    $invoice_update->save();*/

        
        if($transation->owner>0)
        {
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->invoice_id = $request->invoice_id;
        $trans_staff_update->transation_id = $request->transaction_id;
        
        $trans_staff_update->status = 'Delivery Invoice';
        $trans_staff_update->current_status = 'Pending';
        $trans_staff_update->added_date = date("y-m-d");
        $trans_staff_update->due_date =$delivery_date;
        
        $trans_staff_update->staff_id = $transation->owner;
        $trans_staff_update->user_id = $request->user_id;
        $trans_staff_update->type_approval ='Invoice';
        $trans_staff_update->save();
        }

        if($transation->second_owner>0)
        {
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->invoice_id = $request->invoice_id;
        $trans_staff_update->transation_id = $request->transaction_id;
        $trans_staff_update->status = 'Delivery Invoice';
        $trans_staff_update->current_status = 'Pending';
        $trans_staff_update->due_date =$delivery_date;
        $trans_staff_update->added_date = date("y-m-d");
        $trans_staff_update->staff_id = $transation->second_owner;
        $trans_staff_update->user_id = $request->user_id;
        $trans_staff_update->type_approval ='Invoice';
        $trans_staff_update->save();
        }

      

        return redirect()->route('staff.Pendingtransaction')->with('success', 'Data successfully saved.');
    }

    public function cron_for_create_updateinvoice_status()
    {

   
        
$start_date = date("Y-m-d");



    $start_date = date ("Y-m-d", strtotime("-2 days", strtotime($start_date)));

   
   $staff =  DB::select("SELECT * FROM `staff`  ");
    if(count($staff)>0)
        {
            foreach($staff as $values)
            {
              echo "SELECT * FROM `work_report_for_leave` WHERE staff_id='".$values->id."' AND start_date='".$start_date."' ";
              echo '<br>';
                $leavesec =  DB::select("SELECT * FROM `work_report_for_leave` WHERE staff_id='".$values->id."' AND start_date='".$start_date."' ");
                if(count($leavesec)==0)
                    {
                       $work_leave = new Work_report_for_leave;
                        $work_leave->start_date = $start_date;
                        $work_leave->staff_id = $values->id;
                        $work_leave->attendance = 'Full Day';
                        $work_leave->type_leave = 'Request Leave';
                        $work_leave->system_generate_leave = 'Y';
                        
                        $work_leave->save();
                    }
                    
            }
        }


// exit;

//    /* */
// exit;
        
    $tran_updates =  DB::select("SELECT * FROM `transation_staff_updates` WHERE due_date < now() and current_status='Pending' ");
    if(count($tran_updates)>0)
        {
            foreach($tran_updates as $values)
            {
                $invoice_id= $values->invoice_id;
                $trans_staff_update = new Transation_staff_updates;
                $trans_staff_update->invoice_id = $values->invoice_id;
                $trans_staff_update->status = $values->status;
                $trans_staff_update->current_status = 'Pending';
                $trans_staff_update->added_date = date("y-m-d");
                $mange_staff=Transaction_manage_staff::where('manage_section',$values->status)->first();
                $trans_staff_update->staff_id = $mange_staff->staff_id;
                $trans_staff_update->user_id = $values->user_id;
                $trans_staff_update->type_approval ='Invoice';
                $trans_staff_update->save();

            }
        }   

    }
    

}
