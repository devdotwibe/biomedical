<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CoordinatorPermission;
use App\Models\District;
use App\Models\EquipmentStatus;
use App\Models\Ib;
use App\Models\Product;
use App\Models\Staff;
use App\Models\User;
use App\Models\User_permission;
use App\Task;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $ib_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'ib')->first();

        if (optional($permission)->ib_access_view != 'view' && optional($ib_permission)->ib_view != 'view') {
            return redirect()->route('staff.dashboard');
        }

        $noData = " ";
        if ($request->ajax()) {

            $data = Ib::with('ibEquipmentStatus', 'ibProduct', 'ibBrand', 'ibUser', 'ibUser.userdistrict', 'ibStaff', 'ibDepartment')->where('id', '>', 0);

            if (optional($ib_permission)->ib_view != 'view') {
                $data->where('staff_id', $staff_id);
            }

            if (optional($permission)->ib_access_view != 'view') {
                $data->where('staff_id', '!=', $staff_id);
            }

            return Datatables::of($data)

                ->addColumn('customer', function ($data) {
                    if (!empty($data->ibUser)) {
                        return $data->ibUser->business_name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('district', function ($data) {
                    if (!empty($data->ibUser)) {
                        $district = District::where('id', $data->ibUser->district_id)->first();
                        if ($district) {return $district->name;} else {return '';}
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_name', function ($data) {
                    if (!empty($data->ibProduct)) {
                        return $data->ibProduct->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_model', function ($data) {
                    if (!empty($data->equipment_model_no)) {
                        return $data->equipment_model_no;
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_serial', function ($data) {
                    if (!empty($data->equipment_serial_no)) {
                        return $data->equipment_serial_no;
                    } else {
                        return "";
                    }
                })
                ->addColumn('staff', function ($data) {
                    if (!empty($data->ibStaff)) {
                        return $data->ibStaff->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('installation_date', function ($data) {
                    if (!empty($data->installation_date)) {
                        return date('d-m-Y', strtotime($data->installation_date));

                    } else {
                        return "";
                    }
                })
                ->addColumn('warrenty_end_date', function ($data) {
                    if (!empty($data->warrenty_end_date)) {
                        return date('d-m-Y', strtotime($data->warrenty_end_date));
                    } else {
                        return "";
                    }
                })

                ->addColumn('invoice_date', function ($data) {
                    if (!empty($data->invoice_date)) {
                        return date('d-m-Y', strtotime($data->invoice_date));  
                    } else {
                        return "";
                    }
                })


                ->addColumn('action', function ($data) use ($permission, $ib_permission, $staff_id) {

                    $button = '';

                    if (optional($ib_permission)->ib_edit == 'edit' || optional($permission)->ib_access_edit == 'edit') {

                        if ($data->staff_id == $staff_id && optional($permission)->ib_access_edit == 'edit') {
                            $button .= '<a class="btn btn-primary btn-xs" target="_blank" href="' . route('ib-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                        } elseif ($data->staff_id != $staff_id && optional($ib_permission)->ib_edit == 'edit') {
                            $button .= '<a class="btn btn-primary btn-xs" target="_blank" href="' . route('ib-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                        }

                    }

                    if (optional($ib_permission)->ib_delete == 'delete' || optional($permission)->ib_access_delete == 'delete') {

                        if ($data->staff_id == $staff_id && optional($permission)->ib_access_delete == 'delete') {
                            $button .= '<a class="delete-btn   deleteItem" href="' . route('ib-destroy', $data->id) . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                        } elseif ($data->staff_id != $staff_id && optional($ib_permission)->ib_delete == 'delete') {
                            $button .= '<a class="delete-btn   deleteItem" href="' . route('ib-destroy', $data->id) . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                        }

                    }

                    return $button;
                })

                ->rawColumns(['customer', 'district', 'equipment_name', 'equipment_model', 'equipment_serial', 'staff', 'installation_date', 'warrenty_end_date', 'invoice_date','action'])->addIndexColumn()->make(true);

        }
        return view('staff.ib.index');
    }

    public function create()
    {
        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if (optional($permission)->ib_access_create != 'create') {
            return redirect()->route('staff.dashboard');
        }

        $products = Product::get();
        $users = User::get();
        $equipment_status = EquipmentStatus::get();
        $categories = Category::get();
        $staffs = Staff::get();
        //print_r($products);
        //die("ee");

        // if($staff_id ==56)
        // {
        //     date_default_timezone_set('Asia/Kolkata');
   
        //     $cur_date = date('Y-m-d');
    
        //     $cron_repeat = DB::select("select * from task where  start_date<='" . $cur_date . "'  AND created_by_id ='" . $staff_id . "'  AND task_closed='N'  AND repeat_every!='' AND repeat_every!='Custom' ");
       
        //     if (count($cron_repeat) > 0) {
        //         $random_num = rand(12345, 199999) + rand(123, 999);
        //         $timestamp = time();
        //         $unique_code = $random_num . ($timestamp + rand(10, 999));
        //         $cron_repeat_uni = DB::select("select * from task where unique_code = '" . $unique_code . "'");
        //         if (count($cron_repeat_uni) > 0) {
        //             $unique_code = time() . $unique_code;
        //         } else {
        //             $unique_code = $unique_code;
        //         }
        //         foreach ($cron_repeat as $values) {
        //             if ($values->repeat_every == "Days") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +1 day");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "Week") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +1 week");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "2weeks") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +2 week");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "1Month") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +1 month");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "2Month") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +2 month");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "3Month") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +3 month");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "6Month") {
        //                 $add = strtotime(date("Y-m-d", strtotime($values->start_date)) . " +6 month");
        //                 $start_date = date("Y-m-d", $add);
        //             }
        //             if ($values->repeat_every == "1year") {
        //                 $start_date = date('Y-m-d', strtotime('+1 year', strtotime($values->start_date)));
        //             }
        //             $task_count = DB::select("select count(*) as task_count from task where parent_id='" . $values->parent_id . "' AND  task_create_type='auto'");
        //             $tot_tasks = $task_count[0]->task_count;
        //             if ($values->start_date != "0000-00-00") {
        //                 $startTimeStamp = strtotime($values->start_date);
        //                 $endTimeStamp = strtotime($values->due_date);
        //                 $timeDiff = abs($endTimeStamp - $startTimeStamp);
        //                 $numberDays = $timeDiff / 86400;
        //                 $numberDays = intval($numberDays);
        //                 $due_date = date('Y-m-d', strtotime($start_date . ' + ' . $numberDays . ' days'));
        //             }
        //             $task_exit = DB::select("select count(*) as task_exit_count from task where parent_id='" . $values->parent_id . "' AND start_date='" . $start_date . "'");
        //             $cur_task_exit = $task_exit[0]->task_exit_count;

        //             if ($cur_task_exit == 0) {
        //                 if ($values->unlimited_cycles == "N" && $values->cycles > 0) { 
        //                     if ($tot_tasks < $values->cycles) {
        //                         /*****************************************/
        //                         if (strtotime($cur_date) == strtotime($start_date)) {
        //                             $task = new Task;
        //                             $task->name = $values->name;
        //                             $task->unique_code = $unique_code;
        //                             $task->company_id = $values->company_id;
        //                             $task->assigned_team = $values->assigned_team;
        //                             $task->followers = $values->followers;
        //                             $task->related_to = $values->related_to;
        //                             $task->related_to_sub = $values->related_to_sub;
        //                             $task->user_id = $values->user_id;
        //                             $task->start_date = $start_date;
        //                             $task->due_date = $due_date;
        //                             $task->priority = $values->priority;
        //                             $task->repeat_every = $values->repeat_every;
        //                             $task->cycles = $values->cycles;
        //                             $task->unlimited_cycles = $values->unlimited_cycles;
        //                             $task->custom_days = $values->custom_days;
        //                             $task->custom_type = $values->custom_type;
        //                             $task->description = $values->description;
        //                             $task->assigns = $values->assigns;
        //                             $task->check_list_id = $values->check_list_id;
        //                             $task->freq_hour = $values->freq_hour;
        //                             $task->created_by_name = $values->created_by_name;
        //                             $task->created_by_id = $values->created_by_id;
        //                             $task->amount = $values->amount;
        //                             $task->start_time = $values->start_time;
        //                             $task->parent_id = $values->parent_id;
        //                             $task->state_id = $values->state_id;
        //                             $task->district_id = $values->district_id;
        //                             $task->infinity_end_date = $values->infinity_end_date;
        //                             $task->task_create_type = "auto";
        //                             $task->save();
        //                             $task_update = Task::find($values->id);
        //                             $task_update->task_closed = "Y";
        //                             $task_update->save();
        //                         }
        //                         /*****************************************/
        //                     } else {
        //                         $update = DB::table('task')->where('parent_id', $values->parent_id)->update(['task_closed' => "Y"]);
        //                     }
        //                 } else {

                   

        //                     if (strtotime($start_date) < strtotime($values->infinity_end_date)) { 
                      
        //                         if (strtotime($cur_date) == strtotime($start_date)) {
        //                             $task = new Task;
        //                             $task->name = $values->name;
        //                             $task->unique_code = $unique_code;
        //                             $task->company_id = $values->company_id;
        //                             $task->assigned_team = $values->assigned_team;
        //                             $task->followers = $values->followers;
        //                             $task->related_to = $values->related_to;
        //                             $task->related_to_sub = $values->related_to_sub;
        //                             $task->user_id = $values->user_id;
        //                             $task->start_date = $start_date;
        //                             $task->start_time = $values->start_time;
        //                             $task->state_id = $values->state_id;
        //                             $task->district_id = $values->district_id;
        //                             $task->due_date = $due_date;
        //                             $task->priority = $values->priority;
        //                             $task->repeat_every = $values->repeat_every;
        //                             $task->cycles = $values->cycles;
        //                             $task->unlimited_cycles = $values->unlimited_cycles;
        //                             $task->custom_days = $values->custom_days;
        //                             $task->custom_type = $values->custom_type;
        //                             $task->description = $values->description;
        //                             $task->assigns = $values->assigns;
        //                             $task->check_list_id = $values->check_list_id;
        //                             $task->freq_hour = $values->freq_hour;
        //                             $task->created_by_name = $values->created_by_name;
        //                             $task->created_by_id = $values->created_by_id;
        //                             $task->amount = $values->amount;
        //                             $task->parent_id = $values->parent_id;
        //                             $task->infinity_end_date = $values->infinity_end_date;
        //                             $task->task_create_type = "auto";
        //                             $task->save();
        //                             $task_update = Task::find($values->id);
        //                             $task_update->task_closed = "Y";
        //                             $task_update->save();
        //                         }
                               
        //                     } else {
        //                         $update = DB::table('task')->where('parent_id', $values->parent_id)->update(['task_closed' => "Y"]);
        //                     }
        //                 }
        //             }
        //         } 
        //     }
            
        //     dd('success');
        // }

        
        return view('staff.ib.create', compact('products', 'users', 'equipment_status', 'categories', 'staffs'));
    }

    public function check_equp_no(Request $request)
    {

        $user_id = $request->user_id;

        $serial_no = $request->serial_no;

        $ib = Ib::where('user_id', $user_id)->where('equipment_serial_no', $serial_no)->first();

        if (!empty($ib)) {

            return response()->json([
                'exists' => true,
                'message' => 'The equipment serial number already exist.',
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'The equipment serial number is available.',
        ]);

    }

    public function store(Request $request)
    {
        $values = $request->validate([
            'user_id' => 'required',
            'department_id' => 'nullable',
            'equipment_id' => 'required',
            'equipment_serial_no' => 'required',
            'equipment_model_no' => 'required',
            'equipment_status_id' => 'nullable',
            'staff_id' => 'nullable',
            'installation_date' => 'required',
            'warrenty_end_date' => 'nullable',
            'description' => 'required',
            'invoice_date' => 'nullable',
        ]);

        $ib = Ib::where('user_id', $request->user_id)->where('equipment_serial_no', $request->equipment_serial_no)->first();

        if (!empty($ib)) {
            return redirect()->back()
                ->withErrors(['equipment_serial_no' => 'The equipment serial number already exist.'])
                ->withInput();
        }

        $installation_date = Carbon::parse($request->installation_date)->format('Y-m-d');
        $warrenty_end_date = Carbon::parse($request->warrenty_end_date)->format('Y-m-d');
        $invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');

        $values['installation_date'] = $installation_date;
        $values['warrenty_end_date'] = $warrenty_end_date;
        $values['invoice_date'] = $invoice_date;

        Ib::create($values);
        return redirect()->route('staff.ib-index')->with('success', 'Data successfully saved.');
    }

    public function destroy($id)
    {
        Ib::find($id)->delete();

        return response()->json(['success' => 'Data successfully deleted.']);
        // return redirect()->route('staff.ib-index')->with('success', 'Data successfully deleted.');
    }
    public function edit($id)
    {
        $ib = Ib::find($id);
        $products = Product::get();
        $users = User::get();
        $equipment_status = EquipmentStatus::get();
        $categories = Category::get();
        $staffs = Staff::get();

        return view('staff.ib.edit', compact('ib', 'products', 'users', 'staffs', 'equipment_status', 'categories'));

    }
    public function update(Request $request, $id)
    {

        //$staff_id = session('STAFF_ID');
        //echo $staff_id;
        // die('yes');
        // if($staff_id != 55 || $staff_id != 127)
        // {
        //     $request->validate([
        //         'user_id'=>'required',
        //         'department_id'=>'required',
        //         'equipment_id'=>'required',
        //         'equipment_serial_no'=>'required',
        //         'equipment_model_no'=>'required' ,
        //         'equipment_status_id'=>'required',
        //         'installation_date'=>'required',
        //         'warrenty_end_date'=>'required',
        //         'description'=>'required'
        //     ]);
        //     //die('yes');
        // }
        // die('ddd');

        $values = $request->all();

        $installation_date = Carbon::parse($request->installation_date)->format('Y-m-d');
        $warrenty_end_date = Carbon::parse($request->warrenty_end_date)->format('Y-m-d');
        $invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');

        $values['installation_date'] = $installation_date;
        $values['warrenty_end_date'] = $warrenty_end_date;
        $values['invoice_date'] = $invoice_date;

        Ib::find($id)->update($values);
        if ($request->internal_ref_no == '') {
            $inRefno = 'IB-' . rand(1000, 100000);
            Ib::find($id)->update(['internal_ref_no' => $inRefno]);
        }
        return redirect()->route('staff.ib-index')->with('success', 'Data successfully updated.');
    }
}
