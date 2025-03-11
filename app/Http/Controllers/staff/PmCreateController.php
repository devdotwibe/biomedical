<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Category;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\District;
use App\EquipmentStatus;
use App\Http\Controllers\Controller;
use App\Oppertunity;
use App\Oppertunity_product;
use App\PmDetails;
use App\Product;
use App\Service;
use App\ServiceType;
use App\Staff;
use App\State;
use App\Task;
use App\Task_comment;
use Carbon\Carbon;
use DataTables;
use DateTime;
use Illuminate\Http\Request;

class PmCreateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function reportcall($id)
    {

        $call_aproved = Task_comment::where('pm_detail_id',$id)->where('status','Y')->count();

        $call_count = Task_comment::where('pm_detail_id',$id)->count();

        if($call_aproved == $call_count)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function reporttaskcheck($id)
    {
      
        $pm_tasks_aproved = Task::where('pm_detail_id',$id)->where('staff_status','Approved')->count();

        $pm_tasks = Task::where('pm_detail_id',$id)->count();

        if($pm_tasks_aproved == $pm_tasks)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function pm_create(Request $request, $id)
    {

        $staff_id = session('STAFF_ID');

        $pm_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type','pm')->first();

        if (optional($pm_permission)->common_view != 'view') {

            return redirect()->route('staff.dashboard');
        }

        // if ($staff_id == 55 || $staff_id == 127 || $staff_id == 34 || $staff_id == 56 ){

        if ($request->ajax()) {

            // $data = ContractProduct::with('contract','service','oppertunity','equipment','productPMList')->where('id','>',0);

            $data = PmDetails::whereHas('service')->with('callcomments','pmtask','service', 'contract', 'equipment', 'contractproduct')->where('id', '>', 0)

                // ->orderBy('visiting_date', 'desc');
                ->orderBy('created_at', 'desc');

            $data->whereHas('contract', function ($query) {

                $query->whereNotNull('msa_contract_id');

            });

            $tab_type = $request->type;

            if ($request->type == 'open') {

                $data->whereNull('engineer_name')->where('status','!=','Approved')->whereDoesntHave('pmfeedback');;
            }

            if ($request->type == 'verified_task') {

                
                $data->where('status', '!=', 'Approved')
                ->whereDoesntHave('pmfeedback')
                
                ->where(function($query) {
        
                    $query->whereHas('pmtask', function($subQuery) {

                        $subQuery->whereIn('pm_detail_id', function($subSubQuery) {
                            $subSubQuery->select('pm_detail_id')
                                ->from('task') 
                                ->where('staff_status','Approved')
                                ->whereRaw('
                                    (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id) = 
                                    (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id AND task.staff_status = "Approved")
                                ');
                        });
                    });

                    // $query->whereHas('callcomments', function($subQuery) {
                    //     $subQuery->whereIn('pm_detail_id', function($subSubQuery) {
                    //         $subSubQuery->select('pm_detail_id')
                    //             ->from('task_comment') 
                    //             ->whereRaw('
                    //                 (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id) = 
                    //                 (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id AND task_comment.status = "Y")
                    //             ');
                    //     });
                    // });
        
                
                });
              
            }

            if ($request->type == 'verified_call') {

                
                $data->where('status', '!=', 'Approved')
                ->whereDoesntHave('pmfeedback')
                
                ->where(function($query) {
        
                    // $query->whereHas('pmtask', function($subQuery) {

                    //     $subQuery->whereIn('pm_detail_id', function($subSubQuery) {
                    //         $subSubQuery->select('pm_detail_id')
                    //             ->from('task') 
                    //             ->where('staff_status','Approved')
                    //             ->whereRaw('
                    //                 (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id) = 
                    //                 (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id AND task.staff_status = "Approved")
                    //             ');
                    //     });
                    // });

                    $query->whereHas('callcomments', function($subQuery) {
                        $subQuery->whereIn('pm_detail_id', function($subSubQuery) {
                            $subSubQuery->select('pm_detail_id')
                                ->from('task_comment') 
                                ->whereRaw('
                                    (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id) = 
                                    (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id AND task_comment.status = "Y")
                                ');
                        });
                    });
        
                
                });
              
            }

            if ($request->type == 'reported') {

                $data->where(function ($query) {
                    $query->where('status', 'reported')
                    ->orWhereHas('callcomments', function($query) {
                        $query->where('status','!=', 'Y');
                    });
                })->where('status','!=','Approved')
                ->whereDoesntHave('pmfeedback');


                $pm_details = PmDetails::with('service', 'contract', 'equipment', 'contractproduct')->where('id', '>', 0)

                    ->whereHas('contract', function ($query) {

                        $query->whereNotNull('msa_contract_id');

                    })

                    ->whereIn('id', function($query) {
                        $query->select('pm_detail_id')
                              ->from('task')
                              ->where('staff_status', 'Approved');
                    })
                    ->whereRaw('
                        (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id) = 
                        (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id AND task.staff_status = "Approved")
                    ')
                    ->orWhere(function($query) {
    
                        $query->whereHas('callcomments', function($subQuery) {
    
                           $subQuery->where('status','Y');
                        //    ->whereRaw('1 = 1');
                         
                        }) ->whereRaw('
                            (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id) = 
                            (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id AND task_comment.status = "Y")
                        ');
                    })->where('status','!=','Approved')
                    ->whereDoesntHave('pmfeedback')->pluck('id');

                    $data->whereNotIn('id',$pm_details);
                
            }

            if ($request->type == 'feedback') {
                $data->whereIn('id', function($query) {
                    $query->select('pm_detail_id')
                          ->from('service_feedbacks');
                })->where('status','!=','Approved');
                
            }

            if ($request->type == 'closed') {
                
                $data->where('status','Approved');
            }
            

            if ($request->type == 'pending') {

                $data->whereNotNull('engineer_name')->whereIn('service_id', function ($qry) {
                    $qry->select('id')
                        ->from('services')
                        ->where('status', '!=', 'Completed');
                })->where('status','!=','Approved')
                ->whereDoesntHave('pmfeedback');


                $pm_details = PmDetails::whereHas('service')->with('service', 'contract', 'equipment', 'contractproduct')->where('id', '>', 0)

                    ->whereHas('contract', function ($query) {

                        $query->whereNotNull('msa_contract_id');

                    })

                    ->whereIn('id', function($query) {
                        $query->select('pm_detail_id')
                              ->from('task')
                              ->where('staff_status', 'Approved');
                    })
                    ->whereRaw('
                        (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id) = 
                        (SELECT COUNT(*) FROM task WHERE task.pm_detail_id = pm_details.id AND task.staff_status = "Approved")
                    ')
                    ->orWhere(function($query) {
    
                        $query->whereHas('callcomments', function($subQuery) {
    
                           $subQuery->where('status','Y');
                        //    ->whereRaw('1 = 1');
                         
                        }) ->whereRaw('
                            (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id) = 
                            (SELECT COUNT(*) FROM task_comment WHERE task_comment.pm_detail_id = pm_details.id AND task_comment.status = "Y")
                        ');
                    })->where('status','!=','Approved')
                    ->whereDoesntHave('pmfeedback')->pluck('id');

                    $data->whereNotIn('id',$pm_details);
            }

            if (!empty($request->state)) {

                $state = $request->state;

                $data->whereIn('service_id', function ($query) use ($state) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('state', $state);
                });
            }

            if (!empty($request->district)) {

                $district = $request->district;

                $data->whereIn('service_id', function ($query) use ($district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('district', $district);
                });
            }

            if (!empty($request->account_name)) {

                $account_name = $request->account_name;

                $data->whereIn('service_id', function ($query) use ($account_name) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('user_id', $account_name);
                });
            }

            if (!empty($request->engineer)) {

                $engineer = $request->engineer;

                $data->whereIn('service_id', function ($query) use ($engineer) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('staff_id', $engineer);
                });
            }

            if (!empty($request->start_date)) {
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->toDateString();

                $data->Wheredate('created_at', '>=', $start_date);
            }

            if (!empty($request->end_date)) {
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->toDateString();

                $data->Wheredate('created_at', '<=', $end_date);

            }

            if (!empty($request->brand)) {
                $brand_id = $request->brand;

                $data->whereIn('id', function ($subquery) use ($brand_id) {
                    $subquery->select('oppertunity_id')
                        ->from('oppertunity_products')
                        ->whereIn('product_id', function ($subqueryinner) use ($brand_id) {
                            $subqueryinner->select('id')
                                ->from('products')
                                ->where('brand_id', $brand_id);
                        });
                });
            }

            if (!empty($request->category)) {

                $category_id = $request->category;

                $data->whereIn('id', function ($subquery) use ($category_id) {
                    $subquery->select('oppertunity_id')
                        ->from('oppertunity_products')
                        ->whereIn('product_id', function ($subqueryinner) use ($category_id) {
                            $subqueryinner->select('id')
                                ->from('products')
                                ->where('category_id', $category_id);
                        });
                });
            }

            $count_total = PmDetails::whereHas('service')->with('service', 'contract', 'equipment', 'contractproduct')->where('id', '>', 0)
                ->whereHas('contract', function ($query) {

                    $query->whereNotNull('msa_contract_id');

                })->count();

            $count_filter = $data->count();

            $pageSize = ($request->length) ? $request->length : 10;

            $start = ($request->start) ? $request->start : 0;

            $data = $data->skip($start)->take($pageSize);

            return Datatables::of($data)

                // ->addColumn('in_ref_no', function ($data) {

                //     if (!empty($data->service->internal_ref_no)) {

                //         return $data->service->internal_ref_no;
                //     } else {
                //         return "";
                //     }
                // })

                
            // ->addColumn('ex_ref_no',function($data){
            //     if(!empty($data->service->external_ref_no)){
            //         return $data->service->external_ref_no;
            //     }
            //     else{
            //         return "";
            //     }
            //     })
  
                ->addColumn('customer', function ($data) {
                    if (!empty($data->service->serviceUser)) {

                        $custome_name = $data->service->serviceUser->business_name;

                        $customer_district = $data->service->serviceUser->userdistrict->name;

                        return $custome_name .' ('. $customer_district .')';

                    } else {
                        return "";
                    }
                })

                ->filterColumn('customer', function ($query, $keyword) {
                    $query->whereHas('service.serviceUser', function ($q) use ($keyword) {
                        $q->where('business_name', 'like', "%{$keyword}%")
                          ->orWhereHas('userdistrict', function ($subQuery) use ($keyword) {
                              $subQuery->where('name', 'like', "%{$keyword}%");
                          });
                    });
                })

                ->addColumn('contact_person', function ($data) {
                    if (!empty($data->service->serviceContactPerson)) {
                        return $data->service->serviceContactPerson->name . ' , ' . $data->service->serviceContactPerson->mobile;
                    } else {
                        return "";
                    }
                })

                ->addColumn('equipment_name', function ($data) use($tab_type) {

                    $equip_name ="";

                     if (!empty($data->equipment)) {


                            return $equip_name .='<a class="show_pmdtails" data-id="'.$data->id.'">'.$data->equipment->name.'</a>';
    
                        } elseif (!empty($data->service->serviceProduct)) {
    
    
                            return $equip_name .='<a class="show_pmdtails">'.$data->serviceProduct->name.'</a>';
    
                        }

                    return "";
                })

                ->filterColumn('equipment_name', function ($query, $keyword) {
                    $query->whereHas('equipment', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                    // ->orWhereHas('service.serviceProduct', function ($q) use ($keyword) {
                    //     $q->where('name', 'like', "%{$keyword}%");
                    // });
                })

                ->addColumn('equipment_serial_no', function ($data) {

                    if (!empty($data->contractproduct->equipment_serial_no)) {

                        return $data->contractproduct->equipment_serial_no;
                    } else if (!empty($data->service->equipment_serial_no)) {
                        return $data->service->equipment_serial_no;
                    } else {
                        return "";
                    }
                })

                ->filterColumn('equipment_serial_no', function ($query, $keyword) {
                    $query->whereHas('contractproduct', function ($q) use ($keyword) {
                        $q->where('equipment_serial_no', 'like', "%{$keyword}%");
                    });
                    // ->orWhereHas('service', function ($q) use ($keyword) {
                    //     $q->where('equipment_serial_no', 'like', "%{$keyword}%");
                    // });
                })

                ->addColumn('pm_dates', function ($data) {

                    $pmDates = json_decode($data->contractproduct->pm_dates ?? "[]", true) ?? [];

                    $dates_pms = "";

                    if (!empty($pmDates)) {
                        foreach ($pmDates as $index => $date) {
                            if ($data->pm == "PM" . ($index + 1)) {
                            
                                $dates_pms = date('d-m-Y', strtotime($date)) . ' (PM' . ($index + 1) . ')';
                            }

                        }

                    }

                    return $dates_pms;
                })

                // ->filterColumn('pm_dates', function ($query, $keyword) {
                //     $query->whereHas('contractproduct', function ($q) use ($keyword) {
                //         $q->whereRaw("JSON_SEARCH(pm_dates, 'one', ?) IS NOT NULL", [$keyword]);
                //     });
                // })
                

                ->addColumn('created_at_date', function ($data) {
                    
                    return Carbon::parse($data->created_at)->format('d-m-Y');
                })
                

                ->addColumn('equipment_status', function ($data) {

                    if (!empty($data->contractproduct->under_contract)) {

                        return $data->contractproduct->under_contract;
                    } elseif (!empty($data->service->equipment_status_id)) {

                        $ep_status = EquipmentStatus::find($data->service->equipment_status_id);

                        if (!empty($ep_status)) {
                            return $ep_status->name;
                        } else {
                            return "";
                        }

                    } else {
                        return "";
                    }
                })

            //     ->addColumn('machine_status',function($data){

            //     if (!empty($data->productMachineStatus->name)) {

            //         return $data->productMachineStatus->name;

            //     }

            //    else if(!empty($data->service->serviceMachineStatus)){
            //         return $data->service->serviceMachineStatus->name;
            //     }
            //     else{
            //         return "";
            //     }
            // })

                ->addColumn('engineer', function ($data) {

                    if (!empty($data->engineer->name)) {
                        return $data->engineer->name;
                    }

                })

                ->filterColumn('engineer', function($query, $keyword) {
                    $query->whereHas('engineer', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                

                ->addColumn('no_of_pm', function ($data) {

                    if (!empty($data->contractproduct->no_of_pm)) {
                        return $data->contractproduct->no_of_pm;
                    }

                })

                ->filterColumn('no_of_pm', function($query, $keyword) {
                    $query->whereHas('contractproduct', function($q) use ($keyword) {
                        $q->where('no_of_pm', 'like', "%{$keyword}%");
                    });
                })
                

                ->addColumn('desired_date', function ($data) {

                    $pmDates = json_decode($data->contractproduct->pm_dates ?? "[]", true) ?? [];

                    $dates_desired = $data->visiting_date;

                    $dates_pms = "";

                    if (!empty($pmDates)) {
                        foreach ($pmDates as $index => $date) {
                            if ($data->pm == "PM" . ($index + 1)) {
                                $dates_pms = $date;
                            }

                        }

                    }

                    $diff = false;

                    if ($dates_desired === $dates_pms) {
                        $diff = true;
                    }

                    if ($diff) {
                        return '';
                    } else {
                        $desired_date_format = DateTime::createFromFormat('Y-m-d', $dates_desired);

                        $today = now()->format('Y-m-d');

                        if ($desired_date_format && $desired_date_format->format('Y-m-d') < $today) {

                            return '<span class="red"  style="color: red;" >' . date('d-m-Y', strtotime($dates_desired)) . '</span>';
                        } else {

                            return '<span class="yellow"  style="color: yellow;" >' . date('d-m-Y', strtotime($dates_desired)) . '</span>';
                        }
                    }

                })

                ->filterColumn('desired_date', function($query, $keyword) {
                    $query->where('visiting_date', 'like', "%{$keyword}%");
                })
                

                ->addColumn('report_date', function ($data) {

                    return "";
                })

                ->addColumn('feedback', function ($data) use($tab_type) {

                    $feedback ="";

                    if ($tab_type =='feedback') {

                        return $feedback .='<a class="feed_back_details" data-id="'.$data->id.'">Feed back</a>';

                    }

                    return "";
                })

                ->addColumn('rating', function ($data) {

                    return "";
                })
                ->addColumn('report', function ($data) {

                    return "";
                })

                ->addColumn('msa_ref_no', function ($data) {

                    return "";
                })

              
                ->filterColumn('msa_ref_no', function ($data, $keyword) {
                    if ($keyword) {
                        $data->whereIn('contract_id', function($query) use ($keyword) {
                            $query->select('id')
                                ->from('contracts')
                                ->whereIn('msa_contract_id', function($subquery) use ($keyword) {
                                    $subquery->select('id')
                                        ->from('msa_contract')
                                        ->where('in_ref_no', 'like', "%$keyword%");
                                });
                        });
                    }
                })
                

            
                ->filterColumn('created_at_date', function ($query, $keyword) {
                    if ($keyword) {

                        $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", ["%$keyword%"]);
                    }
                })

            // ->addColumn('created_by',function($data){
            //     if(!empty($data->service->serviceCreatedBy)){
            //         return $data->service->serviceCreatedBy->name;
            //     }
            //     else{
            //         return "";
            //     }
            // })
            // ->addColumn('created_at',function($data){
            //     if(!empty($data->service->created_at)){
            //         return $data->service->created_at;
            //     }
            //     else{
            //         return "";
            //     }
            // })
            // ->addColumn('status',function($data){
            //     if(!empty($data->service->status)){
            //         return $data->service->status;
            //     }
            //     else{
            //         return "";
            //     }
            // })
                ->addColumn('action', function ($data) use($pm_permission,$tab_type) {

                    $edit = false;

                    if(optional( $pm_permission)->common_edit=='edit' )
                    {
                        $edit = true;
                    }

                    $delete = false;

                    if(optional( $pm_permission)->common_delete=='delete' )
                    {
                        $delete = true;
                    }


                    $button ='';

                    /*<a class="table-text" href="' . route('staff.service-edit', "$data->service->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a> */

                    if($edit && ($tab_type =='open' || empty($tab_type) ))
                    {
                        if ($data->service->status == "created") {

                            if ($data->service->service_type == 2 ) {
                                $button = '
                                <a class="table-icon call-task" href="' . route('staff.pm_servide_edit', $data->service->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                            } else {

                                $button .= '
                                    <a class="table-icon call-task" href="' . route('staff.pm_servide_edit', $data->service->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
                                   
                                 
                                if($delete)
                                {
                                    $button .= '<a class="delete-btn deleteItem" attr-service-id="' . $data->service->id . '"  id="deleteItem' . $data->service->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                                }
                                
                            }
                        } elseif ($data->service->status == "Approved" || $data->service->status == "Completed") {
                            $button = '<a class="call-task" href="' . route('staff.pm_servide_edit', $data->service->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                        } else {
                            // $serviceTasks = Task::where('service_id',$data->service->id)->orWhere('staff_status','Pending')
                            // ->orWhere('staff_status','Not Started')->orWhere('staff_status','In Progress')
                            // ->orWhere('staff_status','Complete')->get();
                            if (
                                Task::where('service_id', $data->service->id)->orWhere('staff_status', 'Pending')
                                ->orWhere('staff_status', 'Not Started')->orWhere('staff_status', 'In Progress')
                                ->orWhere('staff_status', 'Complete')->exists()
                            ) {
                                // if(!empty($serviceTasks)){
                                    if ($data->service->service_type == 2) {
                                        $button = '
                                            <a class="table-icon call-task" href="' . route('staff.pm_servide_edit', $data->service->id) . '">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>';
                                        
                                        if (optional($pm_permission)->common_delete == 'delete') {
                                            $button .= '
                                            <a class="delete-btn deleteItempm" data-pm-id="' . $data->id . '" id="deleteItempm' . $data->id . '" title="Delete">
                                                <img src="' . asset('images/delete.svg') . '">
                                            </a>';
                                        }
                                    }
                                    
                                     else {

                                    $button .= '
                                    <a class="table-icon call-task" href="' . route('staff.pm_servide_edit', $data->service->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    ';
                                    
                                    if($delete)
                                    {
                                        $button .= '<a class="delete-btn deleteItem" attr-service-id="' . $data->service->id . '"  id="deleteItem' . $data->service->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                                    }

                                }
                            } else {

                                if ($data->service->service_type == 2 ) {

                                    $button = "";
                                } else {
                                    $button .= '
                                    <a class="table-text" href="' . route('staff.pm_servide_edit', $data->service->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                                    
                                    if($delete)
                                    {
                                        $button .= '<a class="delete-btn deleteItem" attr-service-id="' . $data->service->id . '"  id="deleteItem' . $data->service->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                                    }

                                }

                            }
                        }
                    }

                    return $button;
                })
                ->with([
                    "recordsTotal" => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
            //<a class="task-btn btn btn-primary" attr-task_staff="'.$data->serviceEngineer->id .'" attr-service_id="'.$data->id.'" >Task</a>
            //<a class="approved-btn btn btn-success" attr-service_id="'.$data->id.'" >Summary</a>
            //->rawColumns(['customer'])->addIndexColumn()->make(true);
                ->rawColumns(['in_ref_no','feedback', 'ex_ref_no', 'customer', 'comtact_person', 'equipment_name', 'equipment_serial_no', 'equipment_status', 'machine_status', 'desired_date', 'engineer', 'created_by', 'created_at', 'status', 'action'])
                ->addIndexColumn()
            // ->setRowClass(function ($data) {
            //     if($data->service->status == 'created' )  {
            //             $class='black';
            //         }
            //     elseif($data->service->status == 'Approved'){
            //             $class='orange';
            //         }
            //     elseif($data->service->status == 'Pending'){
            //             $class='red';
            //         }
            //     elseif($data->service->status == 'Open'){
            //             $class='blue';
            //         }
            //     elseif($data->service->status == 'Completed'){
            //             $class='green';
            //         }
            //         return $class;
            //     })
                ->make(true);
        }

        $opperids = PmDetails::pluck('service_id')->unique()->toArray();

        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $oppIds = $opper->pluck('id')->unique()->toArray();

        $productIds = Oppertunity_product::whereIn('oppertunity_id', $oppIds)->pluck('product_id')->unique()->toArray();

        $serviceType = ServiceType::find($id);

        $stateIds = $opper->pluck('state')->unique()->toArray();

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $userIds = $opper->pluck('staff_id')->unique()->toArray();

        $brandIds = Product::whereIn('id', $productIds)->pluck('brand_id')->unique()->toArray();

        $categoryIds = Product::whereIn('id', $productIds)->pluck('category_id')->unique()->toArray();

        $staffs = Staff::get();

        $state = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();

        $district = District::whereIn('id', $districtIds)->orderBy('name', 'asc')->get();

        $engineers = Staff::whereIn('id', $userIds)->orderBy('name', 'asc')->get();

        $brands = Brand::whereIn('id', $brandIds)->orderBy('name', 'asc')->get();

        $categorys = Category::whereIn('id', $categoryIds)->orderBy('name', 'asc')->get();

        return view('staff.pmcreate.index', compact('serviceType', 'staffs', 'state', 'district', 'engineers', 'brands', 'categorys'));
    }

    public function destroypm(Request $request)
    {
        $id = $request->input('id');
        $service = PmDetails::find($id);
        
        if ($service) {
            $service->delete();
        }
    
        if ($request->ajax()) {
            return response()->json(['success' => 'Data successfully deleted.']);
        }
    
        return redirect()->back()->with('success', 'Data successfully deleted.');
    }
    
    
    


    public function show_pmdetails(Request $request)
    {
        $id = $request->id;


        $pm_details = PmDetails::with('pmtask','pmtask.taskcomment.taskCommentContactPerson','pmtask.taskcomment.taskCommentStaff','pmtask.taskcomment','pmtask.taskCreateBy','callcomments.taskCommentContactPerson','callcomments.taskCommentStaff','callcomments','pmfeedback')->where('id',$id)->first();

        return response()->json(['pm_details'=>$pm_details]);
    }

    public function show_feed_back(Request $request)
    {
        $id = $request->id;

        $pm_details = PmDetails::with('pmfeedback','pmfeedback.serviceFeedbackStaff','pmfeedback.serviceFeedbackContact')->where('id',$id)->first();

        return response()->json(['pmfeedback'=>$pm_details]);
    }

    // public function pm_servide_edit(Request $request,$id) 
    // {
    //     $staff_id = session('STAFF_ID');

    //     $conproduct = ContractProduct::find($id);

    //     $tasks = Task::with('taskCreateBy')->where('service_id',$conproduct->service->id)->where('service_task_method','visit')->get();

    //     $service = Service::with('serviceUser.userContact','serviceProduct','serviceFeedback','serviceContactPerson','serviceEngineer','serviceMachineStatus','serviceEquipmentStatus')->where('id',$conproduct->service->id)->first();

    //     $pmDates = optional($service->pmContract)->pm_dates ?? [];

    //     $callResponse = Task_comment::with('taskCommentUpload','taskCommentTask','taskCommentReplay','taskCommentContactPerson','taskCommentStaff')->where('service_id',$conproduct->service->id)->get();

    //     $products = Product::whereRaw('FIND_IN_SET('.$service->equipment_id.',related_products)')->get();

    //     $serviceParts = ServicePart::with('servicePartProduct','servicePartTaskComment')->where('service_id',$conproduct->service->id)->where('service_part_status', 'part-intend')->get();

    //     $servicePartsTests = ServicePart::with('servicePartProduct','servicePartTaskComment')->where('service_id',$conproduct->service->id)->where('service_part_status', 'test')->get();

    //     $staffs = Staff::get();

    //     $serviceTechnicalSupports = ServiceTechnicalSupport::with('supportStaff','supportCreatedBy')->where('service_id',$conproduct->service->id)->get();

    //     $serviceTaskComment = Task_comment::with('taskCommentStaff','taskCommentTask','taskCommentContactPerson','taskCommentService.serviceIb','taskCommentServiceParts.servicePartProduct')

    //     ->where('service_id',$conproduct->service->id)->latest('created_at')->first();

    //     $serviceFeedbacks = ServiceFeedback::with('serviceFeedbackStaff','serviceFeedbackContact')->where('service_id',$conproduct->service->id)->get();

    //     $serviceOppertunity = Oppertunity::with('oppertunityOppertunityProduct.oppertunityProduct','createdBy')->where('service_id',$conproduct->service->id)->get();

    //     $previouslySelectedEngineers = PmDetails::where('service_id',$conproduct->service->id)->pluck('engineer_name', 'pm')->toArray();

    //     $contractpdt=[];

    //     $contractpdtIDS=[];

    //     if(!empty($service->pmContract)){

    //        if ($staff_id == 55 || $staff_id == 127 || $staff_id == 34 || $staff_id == 56 ){

    //         $contractpdt = ContractProduct::where('id',$id)->get();
    //         $contractpdtIDS = ContractProduct::where('contract_id', $service->pmContract->id)->pluck('equipment_id')->toArray();
    //        }else{

    //         $contractpdt = ContractProduct::whereHas('productPMList',function($iqr)use($staff_id){
    //             $iqr->where('engineer_name',$staff_id)->where('visiting_date',"<",Carbon::now()->addMonth()->toDateString());
    //         })->where('id',$id)->get();

    //         $contractpdtIDS = ContractProduct::whereHas('productPMList',function($iqr)use($staff_id){
    //             $iqr->where('engineer_name',$staff_id)->where('visiting_date',"<",Carbon::now()->addMonth()->toDateString());
    //         })->where('id',$id)->pluck('equipment_id')->toArray();
    //        }

    //     }

    //     //die('dddd');
    //     return view('staff.pmcreate.pm_service_Detail', compact('contractpdt','contractpdtIDS','serviceOppertunity','serviceFeedbacks','serviceTaskComment','serviceTechnicalSupports', 'service','pmDates' ,'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests','previouslySelectedEngineers'));
    // }

    public function pm_servide_edit(Request $request, $id)
    {
        $staff_id = session('STAFF_ID');

        $service = Service::find($id);

        // $services = Service::where('user_id', $service->user_id)->get();

        $services = Service::where('user_id', $service->user_id)
            ->whereIn('id', function ($query) {
                $query->select('service_id')
                    ->from('contracts')
                    ->whereIn('id', function ($qry) {
                        $qry->select('contract_id')
                            ->from('pm_details')
                            ->where('id', '>', 0);
                    })
                    ->whereNotNull('msa_contract_id');
            })
            ->get();

        $staffs = Staff::get();

        return view('staff.pmcreate.contract_details', compact('services', 'staffs'));
    }

}
  
 
 
 