<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Category;
use App\Contact_person;
use App\ContractProduct;
use App\District;
use App\EquipmentStatus;
use App\Http\Controllers\Controller;
use App\Ib;
use App\MachineStatus;
use App\Oppertunity;
use App\Oppertunity_product;
use App\PmDetails;
use App\Product;
use App\Service;
use App\ServiceFeedback;
use App\ServicePart;
use App\ServiceTechnicalSupport;
use App\ServiceType;
use App\Staff;
use App\State;
use App\Task;
use App\TaskCommentUpload;
use App\Task_comment;
use App\Task_comment_replay;
use App\User;
use App\User_permission;
use Carbon\Carbon;
use DataTables;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {

        $staff_id = session('STAFF_ID');



        if ($staff_id == 34 || $staff_id == 127 && $id == 1) {

            if ($request->ajax()) {
                $data = Service::with('pmDetails','serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus', 'pmContract')
                    ->where('service_type', $id);
                // if($id==2){
                //     $data->orderByRaw("(select visiting_date from pm_details where visiting_date < '".Carbon::now()->addMonth()->toDateString()."' AND pm_details.service_id = services.id order by visiting_date DESC LIMIT 1)  desc");
                // }

                $count_total = $data->count();
                if (isset($request->search['value']) && $request->search['value'] != "") {
                    $search = $request->search['value'];
                    $data->where(function ($qry) use ($search) {
                        $qry->orWhere("internal_ref_no", "like", "%$search%");
                        $qry->orWhere("external_ref_no", "like", "%$search%");
                        $qry->orWhere("equipment_serial_no", "like", "%$search%");
                        $qry->orWhere("equipment_status_id", "like", "%$search%");
                        $qry->orWhere("created_at", "like", "%$search%");
                        $qry->orWhere("status", "like", "%$search%");
                        $qry->orWhereHas('serviceUser', function ($iqry) use ($search) {
                            $iqry->where("users.business_name", "like", "%$search%");
                        });
                        $qry->orWhereHas('serviceProduct', function ($iqry) use ($search) {
                            $iqry->where("products.name", "like", "%$search%");
                        });
                        $qry->orWhereHas('serviceContactPerson', function ($iqry) use ($search) {
                            $iqry->where(DB::raw("CONCAT(contact_person.name, ' , ', contact_person.mobile)"), 'like', "%$search%");
                        });
                        $qry->orWhereHas('serviceMachineStatus', function ($iqry) use ($search) {
                            $iqry->where("machine_status.name", "like", "%$search%");
                        });
                        $qry->orWhereHas('serviceCreatedBy', function ($iqry) use ($search) {
                            $iqry->where("staff.name", "like", "%$search%");
                        });
                        $qry->orWhereHas('serviceEngineer', function ($iqry) use ($search) {
                            $iqry->where("staff.name", "like", "%$search%");
                        });
                        $qry->orWhereHas('pmContract', function ($iqry) use ($search) {
                            $iqry->where("under_contract", "like", "%$search%");
                        });

                    });
                }

                if (!empty($request->state)) {

                    $state = $request->state;

                    $data->whereIn('user_id', function ($query) use ($state) {
                        $query->select('id')
                            ->from('users')
                            ->where('state_id', $state);
                    });
                }

                if (!empty($request->district)) {

                    $district = $request->district;

                    $data->whereIn('user_id', function ($query) use ($district) {
                        $query->select('id')
                            ->from('users')
                            ->where('district_id', $district);
                    });
                }

                if (!empty($request->account_name)) {

                    $account_name = $request->account_name;

                    $data->whereIn('user_id', function ($query) use ($account_name) {
                        $query->select('id')
                            ->from('users')
                            ->where('id', $account_name);
                    });
                }

                if (!empty($request->engineer)) {

                    $engineer = $request->engineer;

                    $data->whereIn('engineer_id', function ($query) use ($engineer) {
                        $query->select('id')
                            ->from('staff')
                            ->where('id', $engineer);
                    });
                }

                if (!empty($request->start_date)) {
                    $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->toDateString();

                    $data->Wheredate('created_at', '>=', $start_date);
                }

                if (!empty($request->end_date)) {
                    $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->toDateString();

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

                $count_filter = $data->count();
                $pageSize = ($request->length) ? $request->length : 10;
                $start = ($request->start) ? $request->start : 0;
                $data = $data->orderBy('updated_at', 'DESC')->skip($start)->take($pageSize);

                return Datatables::of($data)

                    ->addColumn('in_ref_no_corrective', function ($data) use($id) {

                        if (!empty($data->internal_ref_no)) {
                            return $data->internal_ref_no;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn('district_id', function ($data) {
                        if (!empty($data->serviceUser)) {
                            return $data->serviceUser->userdistrict->name;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn('ex_ref_no', function ($data) {
                        if (!empty($data->external_ref_no)) {
                            return $data->external_ref_no;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn('customer', function ($data) {
                        if (!empty($data->serviceUser)) {
                            return $data->serviceUser->business_name;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('contact_person', function ($data) {
                        if (!empty($data->serviceContactPerson)) {
                            return $data->serviceContactPerson->name . ' , ' . optional($data->serviceContactPerson)->mobile;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn('equipment_name', function ($data) {
                        if (!empty($data->pmContract) && !empty($data->pmContract->oppertunity_id)) {
                            $id = ($data->pmContract)->id;
                            $products = Product::whereIn('id', ContractProduct::where('contract_id', $id)->select('equipment_id'))->orderBy('id')->pluck('name')->toArray();
                            $equipmentNames = '';
                            foreach ($products as $product) {
                                $equipmentNames .= $product . "<br>";
                            }

                            return $equipmentNames;
                        } elseif (!empty($data->serviceProduct)) {
                            return $data->serviceProduct->name;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn('equipment_serial_no', function ($data) {
                        if (!empty($data->pmContract) && !empty($data->pmContract->oppertunity_id)) {
                            $id = $data->pmContract->id;
                            $serialnum = ContractProduct::where('contract_id', $id)->orderBy('equipment_id')->pluck('equipment_serial_no')->toArray();

                            $serialnumbers = '';
                            foreach ($serialnum as $serial) {
                                $serialnumbers .= $serial . "<br>";
                            }

                            return $serialnumbers;
                        } else if (!empty($data->equipment_serial_no)) {
                            return $data->equipment_serial_no;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('equipment_status', function ($data) {
                        if (!empty($data->pmContract)) {
                            $id = $data->pmContract->id;
                            if (!empty($data->pmContract->oppertunity_id)) {
                                $contract = ContractProduct::where('contract_id', $id)->orderBy('equipment_id')->pluck('under_contract')->toArray();

                                $undercontract = '';
                                foreach ($contract as $con) {
                                    $undercontract .= $con . "<br>";
                                }

                                return $undercontract;
                            } else {
                                $data->pmContract->under_contract;
                            }

                        } elseif (!empty($data->equipment_status_id)) {

                            $ep_status = EquipmentStatus::find($data->equipment_status_id);

                            if (!empty($ep_status)) {
                                return $ep_status->name;
                            } else {
                                return "";
                            }

                        } else {
                            return "";
                        }
                    })
                    ->addColumn('machine_status', function ($data) {
                        if (!empty($data->pmContract) && !empty($data->pmContract->oppertunity_id)) {
                            $id = $data->pmContract->id;
                            $contractProducts = ContractProduct::where('contract_id', $id)->with('productMachineStatus')->get();

                            $machineStatus = '';
                            foreach ($contractProducts as $contractProduct) {
                                if (!empty($contractProduct->productMachineStatus)) {
                                    $machineStatus .= $contractProduct->productMachineStatus->name . "<br>";
                                }
                            }

                            return $machineStatus;

                        } else if (!empty($data->serviceMachineStatus)) {
                            return $data->serviceMachineStatus->name;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('engineer', function ($data) {
                        if (!empty($data->serviceEngineer)) {
                            return $data->serviceEngineer->name;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('created_by', function ($data) {
                        if (!empty($data->created_by)) {
                            return $data->serviceCreatedBy->name;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('created_at', function ($data) {
                        if (!empty($data->created_at)) {
                            
                            return date('d-m-Y', strtotime($data->created_at));

                        } else {
                            return "";
                        }
                    })
                    ->addColumn('status', function ($data) {
                        if (!empty($data->status)) {
                            return $data->status;
                        } else {
                            return "";
                        }
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == "created") {

                            if ($data->service_type == 2) {
                                $button = '
                            <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                            } else {
                                $button = '
                                <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a class="delete-btn deleteItem" attr-service-id="' . $data->id . '"  id="deleteItem' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                            }
                        } elseif ($data->status == "Approved" || $data->status == "Completed") {
                            $button = '<a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                        } else {
                            // $serviceTasks = Task::where('service_id',$data->id)->orWhere('staff_status','Pending')
                            // ->orWhere('staff_status','Not Started')->orWhere('staff_status','In Progress')
                            // ->orWhere('staff_status','Complete')->get();
                            if (Task::where('service_id', $data->id)->orWhere('staff_status', 'Pending')
                                ->orWhere('staff_status', 'Not Started')->orWhere('staff_status', 'In Progress')
                                ->orWhere('staff_status', 'Complete')->exists()) {
                                // if(!empty($serviceTasks)){
                                if ($data->service_type == 2) {

                                    $button = '
                            <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                                } else {
                                    $button = '
                                <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a class="delete-btn deleteItem" attr-service-id="' . $data->id . '"  id="deleteItem' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';

                                }
                            } else {

                                if ($data->service_type == 2) {

                                    $button = "";
                                } else {
                                    $button = '
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a class="delete-btn deleteItem" attr-service-id="' . $data->id . '"  id="deleteItem' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';

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
                    ->rawColumns(['in_ref_no_corrective', 'ex_ref_no', 'customer', 'comtact_person', 'equipment_name', 'equipment_serial_no', 'equipment_status', 'machine_status', 'engineer', 'created_by', 'created_at', 'status', 'action'])
                    ->addIndexColumn()
                    ->setRowClass(function ($data) {
                        if ($data->status == 'created') {
                            $class = 'black';
                        } elseif ($data->status == 'Approved') {
                            $class = 'orange';
                        } elseif ($data->status == 'Pending') {
                            $class = 'red';
                        } elseif ($data->status == 'Open') {
                            $class = 'blue';
                        } elseif ($data->status == 'Completed') {
                            $class = 'green';
                        }
                        return $class;
                    })
                    ->make(true);
            }
        } else {

            if ($id == 1) {

                if ($request->ajax()) {

                    $data = Service::with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus');
                        // ->where('service_type', $id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

                    // if ($id == 2) {
                    //     $data->whereHas('productPMList', function ($qry) use ($staff_id) {
                    //         $qry->where('engineer_name', $staff_id)->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    //     });

                    //     // ->orderByRaw("(select visiting_date from pm_details where engineer_name ='$staff_id' AND visiting_date < '" . Carbon::now()->addMonth()->toDateString() . "' AND pm_details.service_id = services.id order by visiting_date DESC LIMIT 1)  desc");
                    // }
                     if($staff_id!=121){
                        $data->where('engineer_id', $staff_id);
                    }
                  
                    // $data = ContractProduct::with('contract','service','oppertunity','equipment','productPMList')->where('id','>',0)

                    // ->whereHas('service', function ($query) use ($staff_id) {
                    //     $query->orderByRaw("FIELD(status, 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

                    //         $query->whereHas('productPMList', function ($qry) use ($staff_id) {
                    //             $qry->where('engineer_name', $staff_id)
                    //                 ->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    //         })
                    //         ->orderByRaw("(SELECT visiting_date FROM pm_details
                    //                     WHERE engineer_name = '$staff_id'
                    //                     AND visiting_date < '" . Carbon::now()->addMonth()->toDateString() . "'
                    //                     AND pm_details.service_id = services.id
                    //                     ORDER BY visiting_date DESC LIMIT 1) DESC");
                    // });

                    // $data = ContractProduct::where('id','>',0);

                    // $data->whereHas('service', function($query) {
                    //     // You can still eager load relationships but use it separately
                    //     $query->with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus', 'pmContract');
                    // });

                    // if($id==2){

                    //         $data->whereHas('productPMList', function($iqr) use ($staff_id) {
                    //         $iqr->where('engineer_name', $staff_id)
                    //             ->where('visiting_date', '<', Carbon::now()->addMonth()->toDateString());
                    //     });
                    // }

                    $count_total = $data->count();

                    if (isset($request->search['value']) && $request->search['value'] != "") {
                        $search = $request->search['value'];
                        $data->where(function ($qry) use ($search) {
                            $qry->orWhere("internal_ref_no", "like", "%$search%");
                            $qry->orWhere("external_ref_no", "like", "%$search%");
                            $qry->orWhere("equipment_serial_no", "like", "%$search%");
                            $qry->orWhere("equipment_status_id", "like", "%$search%");
                            // $qry->orWhere("created_at", "like", "%$search%");
                            $qry->orwhereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", ["%{$search}%"]);
                            $qry->orWhere("status", "like", "%$search%");
                            $qry->orWhereHas('serviceUser', function ($iqry) use ($search) {
                                $iqry->where("users.business_name", "like", "%$search%");
                            });
                            $qry->orWhereHas('serviceProduct', function ($iqry) use ($search) {
                                $iqry->where("products.name", "like", "%$search%");
                            });
                            $qry->orWhereHas('serviceContactPerson', function ($iqry) use ($search) {
                                $iqry->where(DB::raw("CONCAT(contact_person.name, ' , ', contact_person.mobile)"), 'like', "%$search%");
                            });
                            $qry->orWhereHas('serviceMachineStatus', function ($iqry) use ($search) {
                                $iqry->where("machine_status.name", "like", "%$search%");
                            });
                            $qry->orWhereHas('serviceCreatedBy', function ($iqry) use ($search) {
                                $iqry->where("staff.name", "like", "%$search%");
                            });
                            $qry->orWhereHas('serviceEngineer', function ($iqry) use ($search) {
                                $iqry->where("staff.name", "like", "%$search%");
                            });

                            $qry->whereHas('pmContract', function ($iqry) use ($search) {
                                $iqry->whereHas('contractProducts', function ($subQuery) use ($search) {
                                $subQuery->where('equipment_serial_no', 'like', "%{$search}%");
                            })
                                ->orWhere('equipment_serial_no', 'like', "%{$search}%");
                            });

                        });
                    }

                    if (!empty($request->state)) {

                        $state = $request->state;

                        $data->whereIn('user_id', function ($query) use ($state) {
                            $query->select('id')
                                ->from('users')
                                ->where('state_id', $state);
                        });
                    }

                    if (!empty($request->district)) {

                        $district = $request->district;

                        $data->whereIn('user_id', function ($query) use ($district) {
                            $query->select('id')
                                ->from('users')
                                ->where('district_id', $district);
                        });
                    }

                    if (!empty($request->account_name)) {

                        $account_name = $request->account_name;

                        $data->whereIn('user_id', function ($query) use ($account_name) {
                            $query->select('id')
                                ->from('users')
                                ->where('id', $account_name);
                        });
                    }

                    if (!empty($request->engineer)) {

                        $engineer = $request->engineer;

                        $data->whereIn('engineer_id', function ($query) use ($engineer) {
                            $query->select('id')
                                ->from('staff')
                                ->where('id', $engineer);
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

                        $data->whereIn('equipment_id', function ($subqueryinner) use ($brand_id) {
                            $subqueryinner->select('id')
                                ->from('products')
                                ->where('brand_id', $brand_id);
                        });
                    }

                    if (!empty($request->category)) {

                        $category_id = $request->category;

                        $data->whereIn('equipment_id', function ($subqueryinner) use ($category_id) {
                            $subqueryinner->select('id')
                                ->from('products')
                                ->where('category_id', $category_id);
                        });
                    }

                    $count_filter = $data->count();

                    $pageSize = ($request->length) ? $request->length : 10;

                    $start = ($request->start) ? $request->start : 0;

                    $data = $data->orderBy('updated_at', 'DESC')->skip($start)->take($pageSize);

                    // dd($data->toSql());

                    return Datatables::of($data)

                        ->addColumn('in_ref_no_corrective', function ($data) {
                            if (!empty($data->internal_ref_no)) {
                                return $data->internal_ref_no;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('district_id', function ($data) {
                            if (!empty($data->serviceUser)) {
                                return $data->serviceUser->userdistrict->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('ex_ref_no', function ($data) {
                            if (!empty($data->external_ref_no)) {
                                return $data->external_ref_no;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('customer', function ($data) {
                            if (!empty($data->serviceUser)) {
                                return $data->serviceUser->business_name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('contact_person', function ($data) {
                            if (!empty($data->serviceContactPerson)) {
                                return $data->serviceContactPerson->name . ' , ' . optional($data->serviceContactPerson)->mobile;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('equipment_name', function ($data) {

                            if (!empty($data->equipment)) {

                                return $data->equipment->name;

                            } elseif (!empty($data->serviceProduct)) {
                                return $data->serviceProduct->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('equipment_serial_no', function ($data) {

                            if (!empty($data->equipment_serial_no)) {

                                return $data->equipment_serial_no;
                            } else if (!empty($data->equipment_serial_no)) {
                                return $data->equipment_serial_no;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('equipment_status', function ($data) {

                            if (!empty($data->under_contract)) {

                                return $data->under_contract;
                            } elseif (!empty($data->equipment_status_id)) {

                                $ep_status = EquipmentStatus::find($data->equipment_status_id);

                                if (!empty($ep_status)) {
                                    return $ep_status->name;
                                } else {
                                    return "";
                                }

                            } else {
                                return "";
                            }
                        })

                    //  ->addColumn('machine_status',function($data){

                    //             if (!empty($data->productMachineStatus->name)) {

                    //                 return $data->productMachineStatus->name;

                    //             }

                    //            else if(!empty($data->serviceMachineStatus)){
                    //                 return $data->serviceMachineStatus->name;
                    //             }
                    //             else{
                    //                 return "";
                    //             }
                    //         })

                        ->addColumn('machine_status', function ($data) {
                            if (!empty($data->pmContract) && !empty($data->pmContract->oppertunity_id)) {
                                $id = $data->pmContract->id;
                                $contractProducts = ContractProduct::where('contract_id', $id)->with('productMachineStatus')->get();

                                $machineStatus = '';
                                foreach ($contractProducts as $contractProduct) {
                                    if (!empty($contractProduct->productMachineStatus)) {
                                        $machineStatus .= $contractProduct->productMachineStatus->name . "<br>";
                                    }
                                }

                                return $machineStatus;

                            } else if (!empty($data->serviceMachineStatus)) {
                                return $data->serviceMachineStatus->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('engineer', function ($data) {

                            if (!empty($data->serviceEngineer)) {
                                return $data->serviceEngineer->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('created_by', function ($data) {
                            if (!empty($data->created_by)) {
                                return $data->serviceCreatedBy->name;
                            } else {
                                return "";
                            }
                        })
                   

                        ->addColumn('created_at', function ($data) {
                    
                            return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y') : '';
                        })

                        // ->filterColumn('created_at', function ($query, $keyword) {
                        
                        //     if ($keyword) {
                            
                        //         $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]);
                        //     }
                        // })

                        ->addColumn('status', function ($data) {
                            if (!empty($data->status)) {
                                return $data->status;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('action', function ($data) {

                            if ($data->status == "created" && empty($data->pmContract)) {
                                $button = '
                            <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                            } elseif ($data->status == "Approved" || $data->status == "Completed" || !empty($data->pmContract)) {
                                $button = '<a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            } else {
                                // $serviceTasks = Task::where('service_id',$data->id)->orWhere('staff_status','Pending')
                                // ->orWhere('staff_status','Not Started')->orWhere('staff_status','In Progress')
                                // ->orWhere('staff_status','Complete')->get();
                                if (Task::where('service_id', $data->id)->orWhere('staff_status', 'Pending')
                                    ->orWhere('staff_status', 'Not Started')->orWhere('staff_status', 'In Progress')
                                    ->orWhere('staff_status', 'Complete')->exists()) {
                                    // if(!empty($serviceTasks)){
                                    $button = '
                                <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                                } else {
                                    $button = '
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
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
                        ->rawColumns(['in_ref_no_corrective', 'ex_ref_no', 'customer', 'comtact_person', 'equipment_name', 'equipment_serial_no', 'equipment_status', 'machine_status', 'engineer', 'created_by', 'created_at', 'status', 'action'])
                        ->addIndexColumn()
                        ->setRowClass(function ($data) {
                            if ($data->status == 'created') {
                                $class = 'black';
                            } elseif ($data->status == 'Approved') {
                                $class = 'orange';
                            } elseif ($data->status == 'Pending') {
                                $class = 'red';
                            } elseif ($data->status == 'Open') {
                                $class = 'blue';
                            } elseif ($data->status == 'Completed') {
                                $class = 'green';
                            }
                            return $class;
                        })
                        ->make(true);
                }

            } else {

                if ($request->ajax()) {

                    // $data = Service::with('serviceUser','serviceProduct','serviceContactPerson','serviceEngineer','serviceMachineStatus','serviceEquipmentStatus')
                    // ->where('service_type',$id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

                    // if($id==2){
                    //     $data->whereHas('productPMList',function($qry)use($staff_id){
                    //          $qry->where('engineer_name',$staff_id)->where('visiting_date',"<",Carbon::now()->addMonth()->toDateString());
                    //      })->orderByRaw("(select visiting_date from pm_details where engineer_name ='$staff_id' AND visiting_date < '".Carbon::now()->addMonth()->toDateString()."' AND pm_details.service_id = services.id order by visiting_date DESC LIMIT 1)  desc");
                    //  }else{
                    //      $data->where('engineer_id',$staff_id);
                    //  }

                    // $data = ContractProduct::with('contract','service','oppertunity','equipment','productPMList')->where('id','>',0)

                    // ->whereHas('service', function ($query) use ($staff_id) {
                    //     $query->orderByRaw("FIELD(status, 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

                    //         $query->whereHas('productPMList', function ($qry) use ($staff_id) {
                    //             $qry->where('engineer_name', $staff_id)
                    //                 ->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    //         })
                    //         ->orderByRaw("(SELECT visiting_date FROM pm_details
                    //                     WHERE engineer_name = '$staff_id'
                    //                     AND visiting_date < '" . Carbon::now()->addMonth()->toDateString() . "'
                    //                     AND pm_details.service_id = services.id
                    //                     ORDER BY visiting_date DESC LIMIT 1) DESC");
                    // });

                    $data = PmDetails::where('id', '>', 0);

                    $data->whereHas('service', function ($query) {
                        // You can still eager load relationships but use it separately
                        // $query->whereHas('serviceUser')
                        // ->whereHas('serviceProduct')
                        // ->whereHas('serviceContactPerson');
                        // ->whereHas('serviceEngineer')
                        // ->whereHas('serviceMachineStatus')
                        // ->whereHas('serviceEquipmentStatus')
                        // ->whereHas('pmContract');

                        $query->with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus', 'pmContract');
                    });

                    if ($id == 2  ) {

                        if($staff_id == 34 || $staff_id == 127 || $staff_id ==32)
                        {

                        }
                        else
                        {
                            if($staff_id ==37)
                            {
                                $data->whereHas('service', function ($qry) use($staff_id) {
                                    $qry->where('created_by', 37);
                                });

                            }
                            else
                            {

                                $data->where('engineer_name', $staff_id);
                                // ->orwhereHas('service', function ($qry) use($staff_id) {
                                //     $qry->where('created_by', 37);
                                // })
                                // ->whereDate('visiting_date', '<', Carbon::now()->addMonth()->toDateString());
                                // ->whereRaw("STR_TO_DATE(visiting_date, '%Y-%m-%d') < ?", [Carbon::now()->addMonth()->toDateString()]);

                                // ->whereDate('visiting_date', '<', Carbon::now()->addMonth()->toDateString());

                            }                
                        }
                    }

                    if ($request->type == 'pending') {

                        $data->whereHas('service', function ($qry) {
                            $qry->where('status', '!=', 'Completed');
                        });

                    }

                    if ($request->type == 'completed') {
                        $data->whereHas('service', function ($qry) {
                            $qry->where('status', 'Completed');
                        });
                    }

                    if (!empty($request->state)) {

                        $state = $request->state;

                        $data->whereIn('user_id', function ($query) use ($state) {
                            $query->select('id')
                                ->from('users')
                                ->where('state_id', $state);
                        });
                    }

                    if (!empty($request->district)) {

                        $district = $request->district;

                        $data->whereIn('user_id', function ($query) use ($district) {
                            $query->select('id')
                                ->from('users')
                                ->where('district_id', $district);
                        });
                    }

                    if (!empty($request->account_name)) {

                        $account_name = $request->account_name;

                        $data->whereIn('user_id', function ($query) use ($account_name) {
                            $query->select('id')
                                ->from('users')
                                ->where('id', $account_name);
                        });
                    }

                    if (!empty($request->engineer)) {

                        $engineer = $request->engineer;

                        $data->whereIn('engineer_id', function ($query) use ($engineer) {
                            $query->select('id')
                                ->from('staff')
                                ->where('id', $engineer);
                        });
                    }

                    if (!empty($request->start_date)) {
                        $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date)->toDateString();

                        $data->Wheredate('created_at', '>=', $start_date);
                    }

                    if (!empty($request->end_date)) {
                        $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date)->toDateString();

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

                    $count_total = $data->count();

                    $count_filter = $data->count();

                    $pageSize = ($request->length) ? $request->length : 10;

                    $start = ($request->start) ? $request->start : 0;

                    $data = $data->orderBy('updated_at', 'DESC')->skip($start)->take($pageSize);

                    return Datatables::of($data)

                        ->addColumn('in_ref_no_corrective', function ($data) {

                            if (!empty($data->service->internal_ref_no)) {
                                return $data->service->internal_ref_no;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('visiting_date', function ($data) {
                          
                            return carbon::parse($data->visiting_date)->format('d-m-Y');
                        })

                        ->filterColumn('visiting_date', function ($query, $keyword) {
                            if ($keyword) {
        
                                $query->whereRaw("DATE_FORMAT(visiting_date, '%d-%m-%Y') LIKE ?", ["%$keyword%"]);
                            }
                        })


                        ->addColumn('district_id', function ($data) {
                            if (!empty($data->serviceUser)) {

                                return optional($data->serviceUser->userdistrict)->name;

                            } else {
                                return "";
                            }
                        })

                        ->addColumn('ex_ref_no', function ($data) {
                            if (!empty($data->service->external_ref_no)) {
                                return $data->service->external_ref_no;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('customer', function ($data) {
                            if (!empty($data->service->serviceUser)) {
                                $customerName = optional($data->service->serviceUser)->business_name;
                                $districtName = !empty($data->service->serviceUser->userdistrict) ? optional($data->service->serviceUser->userdistrict)->name : "";
                                return $customerName . ' (' . $districtName . ')';
                            } else {
                                return "";
                            }
                        })
                        




                        ->filterColumn('customer', function ($query, $keyword) {
                            $query->whereHas('service.serviceUser', function ($q) use ($keyword) {
                                $q->where('business_name', 'like', "%$keyword%");
                            });
                        })

                        ->addColumn('contact_person', function ($data) {
                            if (!empty($data->service->serviceContactPerson)) {
                                return optional($data->service->serviceContactPerson)->name . ' , ' . optional($data->serviceContactPerson)->mobile;
                            } else {
                                return "";
                            }
                        })

                        ->filterColumn('contact_person', function ($query, $keyword) {
                            $query->whereHas('service.serviceContactPerson', function ($q) use ($keyword) {
                                $q->where('name', 'like', "%$keyword%")
                                    ->orwhere('mobile', 'like', "%$keyword%");
                            });
                        })

                        ->addColumn('equipment_name', function ($data) {

                            if (!empty($data->equipment)) {

                                return optional($data->equipment)->name;

                            } 
                            elseif (!empty($data->service->serviceProduct)) {
                                return optional($data->service->serviceProduct)->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('equipment_serial_no', function ($data) {

                            if (!empty($data->service->equipment_serial_no)) {

                                return $data->service->equipment_serial_no;
                            } else {
                                return "";
                            }
                        })

                        ->filterColumn('equipment_serial_no', function ($query, $keyword) {
                            $query->whereHas('service', function ($q) use ($keyword) {
                                $q->where('equipment_serial_no', 'like', "%{$keyword}%");
                            });
                        })

                        ->addColumn('equipment_status', function ($data) {

                            if (!empty($data->service->under_contract)) {

                                return $data->service->under_contract;
                            } elseif (!empty($data->service->equipment_status_id)) {

                                $ep_status = EquipmentStatus::find($data->service->equipment_status_id);

                                if (!empty($ep_status)) {
                                    
                                    return optional($ep_status)->name;
                                } else {
                                    return "";
                                }

                            } else {
                                return "";
                            }
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

                            //  ->addColumn('machine_status',function($data){

                            //             if (!empty($data->productMachineStatus->name)) {

                            //                 return $data->productMachineStatus->name;

                            //             }

                            //            else if(!empty($data->serviceMachineStatus)){
                            //                 return $data->serviceMachineStatus->name;
                            //             }
                            //             else{
                            //                 return "";
                            //             }
                            //         })

                        ->addColumn('machine_status', function ($data) {
                            if (!empty($data->contract->id)) {
                                $id = $data->contract->id;
                                $contractProducts = ContractProduct::where('contract_id', $id)->with('productMachineStatus')->get();

                                $machineStatus = '';
                                foreach ($contractProducts as $contractProduct) {
                                    if (!empty($contractProduct->productMachineStatus)) {
                                        $machineStatus .= $contractProduct->productMachineStatus->name . "<br>";
                                    }
                                }

                                return $machineStatus;

                            } else if (!empty($data->service->serviceMachineStatus)) {
                                return optional($data->service->serviceMachineStatus)->name;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('engineer', function ($data) {

                            if (!empty($data->engineer)) {
                                return optional($data->engineer)->name;
                            } else {
                                return "";
                            }
                        })

                        ->filterColumn('engineer', function ($query, $keyword) {
                            $query->whereHas('engineer', function ($q) use ($keyword) {
                                $q->where('name', 'like', "%{$keyword}%");
                            });
                        })

                        ->addColumn('created_by', function ($data) {
                            if (!empty($data->service->created_by)) {
                                return optional($data->service->serviceCreatedBy)->name;
                            } else {
                                return "";
                            }
                        })

                        ->filterColumn('created_by', function ($query, $keyword) {
                            $query->whereHas('service.serviceCreatedBy', function ($q) use ($keyword) {
                                $q->where('name', 'like', "%{$keyword}%");
                            });
                        })

                        // ->addColumn('created_at', function ($data) {
                        //     if (!empty($data->service->created_at)) {
                                
                        //         return date('d-m-Y H:i:s', strtotime($data->service->created_at));

                        //         return Carbon::parse($data->->created_at)->format('d-m-Y');

                        //     } else {
                        //         return "";
                        //     }
                        // })
                        
                        ->addColumn('created_at', function ($data) {
                    
                            return Carbon::parse($data->created_at)->format('d-m-Y');
                        })

                        ->filterColumn('created_at', function ($query, $keyword) {
                            if ($keyword) {
        
                                $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", ["%$keyword%"]);
                            }
                        })

                        ->addColumn('status', function ($data) {
                            if (!empty($data->service->status)) {
                                return $data->service->status;
                            } else {
                                return "";
                            }
                        })

                        ->addColumn('action', function ($data) use ($id) {

                            if ($data->service->status == "created" && empty($data->service->pmContract)) {
                                $button = '
                                <a class="call-task" href="' . route('staff.service-detail', ['id' => $data->id, 'type' => $id]) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->service->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                            } elseif ($data->service->status == "Approved" || $data->service->status == "Completed" || !empty($data->service->pmContract)) {
                                $button = '<a class="call-task" href="' . route('staff.service-detail', ['id' => $data->id, 'type' => $id]) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            } else {
                                // $serviceTasks = Task::where('service_id',$data->id)->orWhere('staff_status','Pending')
                                // ->orWhere('staff_status','Not Started')->orWhere('staff_status','In Progress')
                                // ->orWhere('staff_status','Complete')->get();
                                if (Task::where('service_id', $data->service->id)->orWhere('staff_status', 'Pending')
                                    ->orWhere('staff_status', 'Not Started')->orWhere('staff_status', 'In Progress')
                                    ->orWhere('staff_status', 'Complete')->exists()) {
                                    // if(!empty($serviceTasks)){
                                    $button = '
                                    <a class="call-task" href="' . route('staff.service-detail', ['id' => $data->id, 'type' => $id]) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->service->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                                } else {
                                    $button = '
                                    <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', $data->service->id) . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                                    ';
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
                            ->rawColumns(['in_ref_no_corrective', 'ex_ref_no', 'customer', 'comtact_person', 'equipment_name', 'equipment_serial_no', 'equipment_status', 'machine_status', 'engineer', 'created_by', 'created_at', 'status', 'action'])
                            ->addIndexColumn()
                            ->setRowClass(function ($data) {
                                if ($data->service->status == 'created') {
                                    $class = 'black';
                                } elseif ($data->service->status == 'Approved') {
                                    $class = 'orange';
                                } elseif ($data->service->status == 'Pending') {
                                    $class = 'red';
                                } elseif ($data->service->status == 'Open') {
                                    $class = 'blue';
                                } elseif ($data->service->status == 'Completed') {
                                    $class = 'green';
                                }
                                return $class;
                            })
                    ->make(true);
                }

            }

        }

        $service = Service::with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')
            ->where('service_type', $id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

        // $service->where('engineer_id', $staff_id);

        $opperids = $service->pluck('user_id')->unique()->toArray();

        $opper = User::whereIn('id', $opperids)->get();

        $oppIds = $opper->pluck('id')->unique()->toArray();

        $productIds = $service->pluck('equipment_id')->unique()->toArray();

        $stateIds = $opper->pluck('state_id')->unique()->toArray();

        $districtIds = $opper->pluck('district_id')->unique()->toArray();

        $brandIds = Product::whereIn('id', $productIds)->pluck('brand_id')->unique()->toArray();

        $categoryIds = Product::whereIn('id', $productIds)->pluck('category_id')->unique()->toArray();

        $userIds = $opper->pluck('staff_id')->unique()->toArray();

        $serviceType = ServiceType::find($id);

        $staffs = Staff::get();

        $state = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();

        $district = District::whereIn('id', $districtIds)->orderBy('name', 'asc')->get();

        $engineerids = $service->pluck('engineer_id')->unique()->toArray();

        $engineers = Staff::whereIn('id', $engineerids)->get();

        $brands = Brand::whereIn('id', $brandIds)->orderBy('name', 'asc')->get();

        $categorys = Category::whereIn('id', $categoryIds)->orderBy('name', 'asc')->get();

        return view('staff.service.index', compact('serviceType', 'staffs', 'state', 'district', 'engineers', 'brands', 'categorys'));
       
    }

    public function index($id)
    {
 
        $users = User::whereHas('userIb')->get();
        $equipment_status = EquipmentStatus::get();
        $type = ServiceType::find($id);
        $machine_status = MachineStatus::get();
        $staffs = Staff::get();
        $state = State::get();

        return view('staff.service.create', compact('type', 'state', 'equipment_status', 'users', 'machine_status', 'staffs'));
    }

    public function customerContactEquipment(Request $request)
    {
        $contactPersons = Contact_person::where('user_id', $request->user_id)->get();
        $product_id = Ib::where('user_id', $request->user_id)->pluck('equipment_id');
        $equipments = Product::whereIn('id', $product_id)->get();
        return response()->json(['equipments' => $equipments, 'contactPersons' => $contactPersons]);
        // return view('staff.service.create', compact('type', 'users', 'machine_status', 'staffs'));
    }

    public function customerContactDetail(Request $request)
    {
        $contactDetails = Contact_person::where('id', $request->id)->first();
        return response()->json(['contactDetails' => $contactDetails]);
        // return view('staff.service.create', compact('type', 'users', 'machine_status', 'staffs'));
    }

    public function serviceTaskStaff(Request $request)
    {
        $taskStaff = Staff::where('id', $request->task_id)->first();
        return response()->json(['taskStaff' => $taskStaff]);
        // return view('staff.service.create', compact('type', 'users', 'machine_status', 'staffs'));
    }

    public function equipmentSerial(Request $request)
    {
        $equipmentSerial = Ib::where('user_id', $request->user_id)->where('equipment_id', $request->id)->get();

        return response()->json(['equipmentSerial' => $equipmentSerial]);
        // return view('staff.service.create', compact('type', 'users', 'machine_status', 'staffs'));
    }

    public function equipmentDetail(Request $request)
    {
        $equipmentDetails = Ib::with('ibEquipmentStatus')->where('equipment_serial_no', $request->id)->first();
        $eqStatus = $equipmentDetails->ibEquipmentStatus->id;
        // echo $eqStatus;
        //die();
        return response()->json(['equipmentDetails' => $equipmentDetails]);
        // return view('staff.service.create', compact('type', 'users', 'machine_status', 'staffs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'contact_person_id' => 'required',
            'contact_no' => 'required',
            'equipment_id' => 'required',
            'equipment_serial_no' => 'required',
            'equipment_status_id' => 'required',
            'machine_status_id' => 'required',
            'call_details' => 'required',
            'engineer_id' => 'required',
            'created_by' => 'required',
        ]);

        $service = Service::create($request->all());
        $service_id = $service->id;
        $date = Carbon::now();
        $staff_id = session('STAFF_ID');
        $serviceType = ServiceType::find($request->service_type);
        $users = User::where('id', $request->user_id)->withTrashed()->first();

        $task = new Task;
        $task->name = $serviceType->name . '--' . $users->business_name;
        $task->assigns = $request->engineer_id;
        $task->followers = $staff_id;
        $task->user_id = $request->user_id;
        //$task->company_id  = 5;
        $task->start_date = $date->toDateString();
        $task->due_date = $date->toDateString();
        $task->priority = "Urgent";
        $task->custom_type = "Weeks";
        $task->description = $request->call_details;
        $task->created_by_id = $staff_id;
        $task->service_id = $service_id;
        $task->service_task_method = "call";
        $task->service_type_id = $request->service_type;
        //$task->save();

        return redirect()->route('staff.service-create', $request->service_type)->with('success', 'Data successfully saved.');

    }

    public function edit($id)
    {
        $users = User::whereHas('userIb')->get();
        $type = ServiceType::get();
        $machine_status = MachineStatus::get();
        $staffs = Staff::get();
        $service = Service::with('serviceIb')->where('id', $id)->first();
        $editContactPersons = Contact_person::where('user_id', $service->user_id)->get();
        $product_id = Ib::where('user_id', $service->user_id)->pluck('equipment_id');
        $editEquipments = Product::whereIn('id', $product_id)->get();
        $editEquipmentSerials = Ib::with('ibEquipmentStatus')->where('user_id', $service->user_id)->where('equipment_id', $service->equipment_id)->get();
        return view('staff.service.edit', compact('service', 'users', 'type', 'machine_status', 'staffs', 'editEquipmentSerials', 'editEquipments', 'editContactPersons'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'contact_person_id' => 'required',
            'contact_no' => 'required',
            'equipment_id' => 'required',
            'equipment_serial_no' => 'required',
            'equipment_status_id' => 'required',
            'machine_status_id' => 'required',
            'call_details' => 'required',
            'engineer_id' => 'required',
        ]);

        Service::find($id)->update($request->all());
        $serviceType = ServiceType::find($request->service_type);
        $users = User::where('id', $request->user_id)->withTrashed()->first();
        $name = $serviceType->name . '--' . $users->business_name;

        Task::where('service_id', $id)->update(['name' => $name,
            'assigns' => $request->engineer_id, 'user_id' => $request->user_id,
            'description' => $request->call_details]);

        //return redirect()->route('staff.ib-index')->with('success','Data successfully updated.');
        return redirect()->route('staff.service-create', $request->service_type)->with('success', ' Data successfully saved.');

    }
    public function destroy(Request $request)
    {
        $service = Service::find($request->seviceId);
        $service->delete();
        if (Task::where('service_id', $request->seviceId)->count() > 0) {
            Task::where('service_id', $request->seviceId)->delete();
        }
        return redirect()->back()->with('success', 'Service deleted Successfully');
    }
    public function newServiceVistTask(Request $request)
    {
        $id = $request->service_id2;
        if ($request->has('engineer_id2')) {
            $engineer_id = $request->engineer_id2;
        } else {
            $engineer = Service::where('id', $id)->first();
            $engineer_id = $engineer->engineer_id;
            //die();
        }

        $service_id = Service::find($id);

        $date = Carbon::now();
        $staff_id = session('STAFF_ID');
        Service::where('id', $id)->update(['engineer_id' => $engineer_id, 'status' => 'created']);
        $service = Service::with('serviceServiceType', 'serviceUser', 'serviceProduct','pmContract')->where('id', $id)->first();
        $task = new Task;

        if ($service_id->service_type == "2" && !empty($service_id->pmContract) && !empty($service->pmContract->oppertunity_id)) {

            $contract_product_id = $request->contract_product_id;

            $contract_product = ContractProduct::find($contract_product_id);

            $equipment_id = $contract_product->equipment_id;

            $task->contract_product_id = $contract_product_id;

            $pm_detail = PmDetails::find($request->pm_no);

            // $task->name = $service->serviceUser->business_name . '-' . $service->serviceServiceType->short_name . '-' . $contract_product->equipment->name . '-' . $contract_product->equipment_serial_no;

            $task->name = $request->task_name .'-'.  $pm_detail->pm . '-' . $request->schedule_date;

            $task->pm_detail_id = $pm_detail->id;

        } 
        
        
        else {
            $task->name = $service->serviceUser->business_name . '-' . $service->serviceServiceType->short_name . '-' . $service->serviceProduct->name . '-' . $service->equipment_serial_no;

            $equipment_id = $service->equipment_id;

        }

        if ($service_id->service_type == "2" && !empty($service_id->pmContract)) {

            $pmdetail = $service_id->productPMList->where('status', 'open')->first();

            if (!empty($pmdetail)) {
                $engineer_id = $pmdetail->engineer_name;
            }
        }

        if(!empty($request->engineer_name))
        {
            $engineer_id = $request->engineer_name;
        }
        
        $task->assigns = $engineer_id;

// dd($request->engineer_id);

        $task->equipment_id = $equipment_id;

        $task->followers = 127;
        $task->user_id = $service->user_id;
        $task->company_id = '5';
        $task->start_date = $date->toDateString();
        $task->due_date = $date->toDateString();
        $task->priority = "Urgent";
        $task->custom_type = "Weeks";
        $task->description = $request->description;
        $task->created_by_id = $staff_id;
        $task->service_id = $id;
        $task->service_type_id = $service->service_type;
        $task->service_task_method = "visit";
        $task->service_task_status = "Task Created";
        $task->service_day = $request->schedule_date ? Carbon::parse($request->schedule_date)->format('Y-m-d') : '';
        $task->service_time = $request->schedule_time;
        $task->save();
        Service::where('id', $request->service_id2)->update(['status' => 'Open']);
        return redirect()->back()->with('success', 'New task updated for service');
    }
    public function serviceNewTask(Request $request)
    {

        $id = $request->service_id2;
        if ($request->has('engineer_id2')) {
            $engineer_id = $request->engineer_id2;
        } else {
            $engineer = Service::where('id', $id)->first();
            $engineer_id = $engineer->engineer_id;
            //die();
        }

        $date = Carbon::now();
        $staff_id = session('STAFF_ID');
        Service::where('id', $id)->update(['engineer_id' => $engineer_id, 'status' => 'created']);
        $serviceTask = Task::where('service_id', $id)->first();
        $task = new Task;
        $task->name = 'Call-' . $serviceTask->name;
        $task->assigns = $engineer_id;
        $task->followers = $staff_id;
        $task->user_id = $serviceTask->user_id;
        $task->company_id = $serviceTask->company_id;
        $task->start_date = $date->toDateString();
        $task->due_date = $date->toDateString();
        $task->priority = "Urgent";
        $task->custom_type = "Weeks";
        $task->description = $request->description;
        $task->created_by_id = $staff_id;
        $task->service_id = $id;
        $task->service_type_id = $serviceTask->service_type_id;
        $task->service_task_method = "call";

        $task->save();
        Service::where('id', $request->service_id)->update(['status' => 'Pending']);
        return redirect()->back()->with('success', 'New task updated for service ');
    }

    public function serviceTaskDetails(Request $request)
    {
        $taskComments = Task_comment::with('taskCommentStaff', 'taskCommentTask', 'taskCommentService.serviceIb', 'taskCommentServiceParts.servicePartProduct')
            ->where('service_id', $request->service_id)->orderBy('id', 'ASC')->get();

        return response()->json(['taskComments' => $taskComments]);

    }

    public function staffIndex(Request $request)
    {

        $staff_id = session('STAFF_ID');
        if ($request->ajax()) {

            $data = Service::with('serviceUser')->where('engineer_id', $staff_id)
                ->orderByRaw("FIELD(status , 'Pending', 'Open', 'created', 'Approved', 'Completed') ASC")
                ->orderBy('id', 'DESC')->get();

            return Datatables::of($data)

                ->addColumn('in_ref_no', function ($data) {
                    if (!empty($data->internal_ref_no)) {
                        return $data->internal_ref_no;
                    } else {
                        return "";
                    }
                })
                ->addColumn('ex_ref_no', function ($data) {
                    if (!empty($data->external_ref_no)) {
                        return $data->external_ref_no;
                    } else {
                        return "";
                    }
                })
                ->addColumn('customer', function ($data) {
                    if (!empty($data->serviceUser)) {
                        return $data->serviceUser->business_name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('contact_person', function ($data) {
                    if (!empty($data->serviceContactPerson)) {
                        return $data->serviceContactPerson->name . ' , ' . optional($data->serviceContactPerson)->mobile;
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_name', function ($data) {
                    if (!empty($data->serviceProduct)) {
                        return $data->serviceProduct->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_status', function ($data) {
                    if (!empty($data->equipment_status_id)) {
                        return $data->equipment_status_id;
                    } else {
                        return "";
                    }
                })
                ->addColumn('machine_status', function ($data) {
                    if (!empty($data->serviceMachineStatus)) {
                        return $data->serviceMachineStatus->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('engineer', function ($data) {
                    if (!empty($data->serviceEngineer)) {
                        return $data->serviceEngineer->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('created_by', function ($data) {
                    if (!empty($data->created_by)) {
                        return $data->serviceCreatedBy->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('created_at', function ($data) {
                    if (!empty($data->created_at)) {
                        return date('d-m-Y H:i:s', strtotime($data->created_at));
                    } else {
                        return "";
                    }
                })
                ->addColumn('status', function ($data) {
                    if (!empty($data->status)) {
                        return $data->status;
                    } else {
                        return "";
                    }
                })
                ->addColumn('action', function ($data) {
                    if ($data->status == "created") {
                        $button = '
                    <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a class="delete-btn   deleteItem" href="' . route('staff.service-destroy', $data->id) . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                    } elseif ($data->status == "Approved" || $data->status == "Completed") {
                        $button = '<a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    } else {
                        $serviceTasks = Task::where('service_id', $data->id)->orWhere('staff_status', 'Pending')
                            ->orWhere('staff_status', 'Not Started')->orWhere('staff_status', 'In Progress')
                            ->orWhere('staff_status', 'Complete')->get();
                        if (!empty($serviceTasks)) {
                            $button = '
                        <a class="call-task" href="' . route('staff.service-detail', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a class="delete-btn   deleteItem" href="' . route('staff.service-destroy', $data->id) . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                        } else {
                            $button = '
                        <a class="btn btn-primary btn-xs" href="' . route('staff.service-edit', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a class="delete-btn   deleteItem" href="' . route('staff.service-destroy', $data->id) . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                        }
                    }
                    return $button;
                })
            //<a class="task-btn btn btn-primary" attr-task_staff="'.$data->serviceEngineer->id .'" attr-service_id="'.$data->id.'" >Task</a>
            //<a class="approved-btn btn btn-success" attr-service_id="'.$data->id.'" >Summary</a>
            //->rawColumns(['customer'])->addIndexColumn()->make(true);
                ->rawColumns(['in_ref_no', 'ex_ref_no', 'customer', 'comtact_person', 'equipment_name', 'equipment_status', 'machine_status', 'engineer', 'created_by', 'created_at', 'status', 'action'])
                ->addIndexColumn()
                ->setRowClass(function ($data) {
                    if ($data->status == 'created') {
                        $class = 'black';
                    } elseif ($data->status == 'Approved') {
                        $class = 'orange';
                    } elseif ($data->status == 'Pending') {
                        $class = 'red';
                    } elseif ($data->status == 'Open') {
                        $class = 'blue';
                    } elseif ($data->status == 'Completed') {
                        $class = 'green';
                    }
                    return $class;
                })
                ->make(true);
        }

        return view('staff.service.staffIndex');
    }
    // public function storePmdetails(Request $request)
    // {
    //     // Validate the form data
    //     $validatedData = $request->validate([
    //         'service_id' => 'required|integer',
    //         'pm.*' => 'nullable|string',
    //         'visiting_dates.*' => 'nullable|date',
    //         'engineer_names.*' => 'nullable|string',
    //     ]);

    //     // Retrieve the PM details for the specified service ID
    //     $existingPmDetails = PmDetails::where('service_id', $validatedData['service_id'])->get();

    //     // Loop through the submitted data
    //     foreach ($validatedData['pm'] as $index => $pm) {
    //         // Check if there is an existing PM detail for this index
    //         $existingDetail = $existingPmDetails->where('pm', $pm)->first();

    //         if ($existingDetail) {
    //             // If an existing detail is found, update it
    //             $existingDetail->update([
    //                 'visiting_date' => $validatedData['visiting_dates'][$index],
    //                 'engineer_name' => $validatedData['engineer_names'][$index],
    //             ]);
    //         } else {
    //             // Otherwise, create a new PM detail
    //             PmDetails::create([
    //                 'service_id' => $validatedData['service_id'],
    //                 'pm' => $pm,
    //                 'visiting_date' => $validatedData['visiting_dates'][$index],
    //                 'engineer_name' => $validatedData['engineer_names'][$index],
    //             ]);
    //         }
    //     }

    //     // Redirect back or to a success page
    //     return redirect()->back()->with('success', 'PM details stored successfully.');
    // }

    public function storePmdetails(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'service_id' => 'required',
            'equipment_id' => 'required',
            'pm.*' => 'required',
            'visiting_date.*' => 'required',
            'engineer.*' => 'required',
        ], [
            'engineer.*.required' => " The field is required",
        ]);

        $pmIDs = [];
        foreach ($validatedData['pm'] as $index => $pm) {
            $pmDetails = PmDetails::where('equipment_id', $validatedData['equipment_id'])->where('service_id', $validatedData['service_id'])->where('pm', $pm)->first();
            if (empty($pmDetails)) {
                $pmDetails = new PmDetails;
            }
            $pmDetails->visiting_date = $validatedData['visiting_date'][$index];
            $pmDetails->engineer_name = $validatedData['engineer'][$index];
            $pmDetails->service_id = $validatedData['service_id'];
            $pmDetails->equipment_id = $validatedData['equipment_id'];
            $pmDetails->contract_equipment_id = $request->input('contract_equipment_id');
            $pmDetails->pm = $pm;
            $pmDetails->save();
            $pmIDs[] = $pmDetails->id;
        }
        PmDetails::whereNotIn('id', $pmIDs)->where('equipment_id', $validatedData['equipment_id'])->where('service_id', $validatedData['service_id'])->delete();
        return redirect()->back()->with('success', 'PM details stored successfully.');
    }

    public function assignPmdetails(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'visiting_dates.*' => 'required',

        ], [
            'visiting_dates.*.required' => " The field is required",
        ]);

        $pmIds = $request->pm_ids;
        $visitingDates = $request->visiting_dates;
        $engineers = $request->engineers;

        $class_id = $request->class_id;

        $updatedVisitingDates = [];
        $updatedEngineers = [];

        $diff_color = [];

        foreach ($pmIds as $index => $pm) {

            $pmDetails = PmDetails::find($pm);

            if (!empty($pmDetails)) {

                $pmDetails->visiting_date = $visitingDates[$index];
                $pmDetails->engineer_name = $engineers[$index];
                $pmDetails->save();

                $contract_products = ContractProduct::find($request->contract_product_id);

                if (!empty($contract_products)) {
                    $pmDates = json_decode($contract_products->pm_dates ?? "[]", true) ?? [];

                    foreach ($pmDates as $count => $pms) {
                        if ($count == $index) {
                            $dates_pms = $pms;

                            $color = 'orange';

                            if ($visitingDates[$index] != $dates_pms) {
                                $color = 'yellow';
                            } else {
                                $color = 'grey';
                            }

                            $desired_date_format = DateTime::createFromFormat('Y-m-d', $visitingDates[$index]);

                            $today = now()->format('Y-m-d');

                            if ($desired_date_format && $desired_date_format->format('Y-m-d') < $today) {

                                $color = 'red';

                            }

                            $diff_color[] = $color;

                        }
                    }

                }
            }

            $updatedVisitingDates[$pm] = $pmDetails->visiting_date;
            $updatedEngineers[$pm] = $pmDetails->engineer_name;

        }

        return response()->json([
            'success' => true,
            'updated_visiting_dates' => $updatedVisitingDates,
            'updated_engineers' => $updatedEngineers,
            'class_id' => $class_id,
            'diff_color' => $diff_color,
        ]);
    }

    public function serviceDetail(Request $request, $id)
    {

        $staff_id = session('STAFF_ID');

        if ($request->type == 2) {

            $pm_details = PmDetails::find($id);

            $id = $pm_details->service->id;

            $tasks = Task::with('taskCreateBy')->where('service_id', $id)->where('service_task_method', 'visit')->get();

            $service = Service::with('serviceUser.userContact', 'serviceProduct', 'serviceFeedback', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')->where('id', $id)->first();

            $pmDates = optional($service)->pmContract->pm_dates ?? [];

            $callResponse = Task_comment::with('taskCommentUpload', 'taskCommentTask', 'taskCommentReplay', 'taskCommentContactPerson', 'taskCommentStaff')->where('service_id', $id)->get();

            $products = Product::whereRaw('FIND_IN_SET(' . optional($service)->equipment_id . ',related_products)')->get();

            $serviceParts = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'part-intend')->get();
            $servicePartsTests = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'test')->get();
            $staffs = Staff::get();
            $serviceTechnicalSupports = ServiceTechnicalSupport::with('supportStaff', 'supportCreatedBy')->where('service_id', $id)->get();
            $serviceTaskComment = Task_comment::with('taskCommentStaff', 'taskCommentTask', 'taskCommentContactPerson', 'taskCommentService.serviceIb', 'taskCommentServiceParts.servicePartProduct')
                ->where('service_id', $id)->latest('created_at')->first();
            $serviceFeedbacks = ServiceFeedback::with('serviceFeedbackStaff', 'serviceFeedbackContact')->where('service_id', $id)->get();
            $serviceOppertunity = Oppertunity::with('oppertunityOppertunityProduct.oppertunityProduct', 'createdBy')->where('service_id', $id)->get();
            $previouslySelectedEngineers = PmDetails::where('service_id', $id)->pluck('engineer_name', 'pm')->toArray();

            if (!empty($service->pmContract)) {

                if ($staff_id == 34 || $staff_id == 127 || $staff_id == 34 || $staff_id == 56 ||  $staff_id == 37) {
                
                    $contractpdt = ContractProduct::where('id',$pm_details->contract_equipment_id)->where('contract_id', $service->pmContract->id)->get();
    
                    $contractpdtIDS = ContractProduct::where('id',$pm_details->contract_equipment_id)->where('contract_id', $service->pmContract->id)->pluck('equipment_id')->toArray();

                }
                else
                {
                    $contractpdt = ContractProduct::where('id',$pm_details->contract_equipment_id)->whereHas('productPMList', function ($iqr) use ($staff_id) {
                        $iqr->where('engineer_name', $staff_id)->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
    
                    })->where('contract_id', $service->pmContract->id)->get();
    
                    $contractpdtIDS = ContractProduct::where('id',$pm_details->contract_equipment_id)->whereHas('productPMList', function ($iqr) use ($staff_id) {
                        $iqr->where('engineer_name', $staff_id)->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    })->where('contract_id', $service->pmContract->id)->pluck('equipment_id')->toArray();
                }
                
            }

            // return view('staff.service.serviceDetail', compact('contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));

            return view('staff.service.pm_single_detail', compact('pm_details','contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));

        }
        else
        {
            $tasks = Task::with('taskCreateBy')->where('service_id', $id)->where('service_task_method', 'visit')->get();

            $service = Service::with('serviceUser.userContact', 'serviceProduct', 'serviceFeedback', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')->where('id', $id)->first();

            $pmDates = optional($service)->pmContract->pm_dates ?? [];

            $callResponse = Task_comment::with('taskCommentUpload', 'taskCommentTask', 'taskCommentReplay', 'taskCommentContactPerson', 'taskCommentStaff')->where('service_id', $id)->get();

            $products = Product::whereRaw('FIND_IN_SET(' . optional($service)->equipment_id . ',related_products)')->get();

            $serviceParts = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'part-intend')->get();
            $servicePartsTests = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'test')->get();
            $staffs = Staff::get();
            $serviceTechnicalSupports = ServiceTechnicalSupport::with('supportStaff', 'supportCreatedBy')->where('service_id', $id)->get();

            $serviceTaskComment = Task_comment::with('taskCommentStaff', 'taskCommentTask', 'taskCommentContactPerson', 'taskCommentService.serviceIb', 'taskCommentServiceParts.servicePartProduct')
                ->where('service_id', $id)->latest('created_at')->first();

            $serviceTaskCommentApproved = Task_comment::with('taskCommentStaff', 'taskCommentTask', 'taskCommentContactPerson', 'taskCommentService.serviceIb', 'taskCommentServiceParts.servicePartProduct')
            ->where('service_id', $id)->latest('created_at')->get();

            $isApproved = $serviceTaskCommentApproved->every(function ($taskComment) {
                return $taskComment->alltaskcommentapproved();
            });

            $serviceFeedbacks = ServiceFeedback::with('serviceFeedbackStaff', 'serviceFeedbackContact')->where('service_id', $id)->get();
            $serviceOppertunity = Oppertunity::with('oppertunityOppertunityProduct.oppertunityProduct', 'createdBy')->where('service_id', $id)->get();
            $previouslySelectedEngineers = PmDetails::where('service_id', $id)->pluck('engineer_name', 'pm')->toArray();

            $contractpdt = [];

            $contractpdtIDS = [];

            if (!empty($service->pmContract)) {
                if ($staff_id == 34 || $staff_id == 127 || $staff_id == 34 || $staff_id == 56) {
                    $contractpdt = ContractProduct::where('contract_id', $service->pmContract->id)->get();
                    $contractpdtIDS = ContractProduct::where('contract_id', $service->pmContract->id)->pluck('equipment_id')->toArray();
                } else {
                    $contractpdt = ContractProduct::whereHas('productPMList', function ($iqr) use ($staff_id) {
                        $iqr->where('engineer_name', $staff_id)->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    })->where('contract_id', $service->pmContract->id)->get();
                    $contractpdtIDS = ContractProduct::whereHas('productPMList', function ($iqr) use ($staff_id) {
                        $iqr->where('engineer_name', $staff_id)->where('visiting_date', "<", Carbon::now()->addMonth()->toDateString());
                    })->where('contract_id', $service->pmContract->id)->pluck('equipment_id')->toArray();
                }
            }

            return view('staff.service.CorrectiveDetail', compact('isApproved','contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));
        }

        // if ($request->type != 2) {
          

        // } else {

        // } 

        // if ($request->type == 2) {

        //     return view('staff.service.serviceDetail', compact('contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));
        // }
        // else
        // {
        //     return view('staff.service.CorrectiveDetail', compact('contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));
        // }

    }

    public function servicecallResponse(Request $request)
    {

        // dd($request);
        $staff_id = session('STAFF_ID');
        $task_comment = new Task_comment;

        $task_comment->call_status = 'Y';

        //$task_comment->contact_id = $request->contact_id;
        $task_comment->task_id = 0;
        $task_comment->service_id = $request->service_id;
        $task_comment->service_task_problem = $request->observed_problem;
        $task_comment->service_task_action = $request->action_performed;
        $task_comment->service_task_final_status = $request->final_status;
        $task_comment->contact_id = $request->contact_person_id;
        $task_comment->added_by_id = $staff_id;
        $task_comment->service_task_status = $request->service_task_status;
        $task_comment->service_part_status = $request->service_part_status;
        $task_comment->task_remark = $request->task_remark;

        $task_comment->pm_detail_id = $request->pm_detail_id;

        $imageName = [];
        if (isset($request->image_name)) {

            foreach ($request->image_name as $request_image) {

                $imgname = time() . $request_image->getClientOriginalName();

                $imgname = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imgname);

                $path = storage_path();

                $img_path = $request_image->storeAs('public/comment', $imgname);

                $path = $path . '/app/' . $img_path;

                chmod($path, 0777);
                $imageName[] = $imgname;

            }

        }

        $task_comment->image_name = implode(",", $imageName);


        $task_comment->save();
        $task_comment_id = $task_comment->id;

        //$task_comment->part_no = $request->part_no;
        if ($request->product_part_id != '') {
            foreach ($request->product_part_id as $value) {
                $servicePart = new ServicePart;

                $servicePart->product_id = $value;
                $servicePart->task_comment_id = $task_comment_id;
                $servicePart->service_id = $request->service_id;
                $servicePart->staff_id = $staff_id;
                $servicePart->status = "Requested";
                $servicePart->description = $request->part_description;
                $servicePart->service_part_status = $request->service_part_status;
                $servicePart->save();
            }
        }

        Service::where('id', $request->service_id)->update(['status' => 'Open']);
        return redirect()->back()->with('success', ' Response Details Added ');
    }



    public function serviceRequestPart(Request $request)
    {
        $staff_id = session('STAFF_ID');
        if ($request->product_part_id != '') {
            foreach ($request->product_part_id as $value) {
                $servicePart = new ServicePart;

                $servicePart->product_id = $value;
                $servicePart->service_id = $request->service_id;
                $servicePart->staff_id = $staff_id;
                $servicePart->status = "Requested";
                $servicePart->description = $request->part_description;
                $servicePart->save();
            }
        }
        return redirect()->back()->with('success', ' Parts Requested Succcessfully');
    }
    public function serviceCreateOppertunity($id)
    {
        $rand = 'op' . mt_rand(000000, 999999);
        $staff_id = session('STAFF_ID');
        $service = Service::where('id', $id)->first();

        $oppertunity = new Oppertunity;
        $oppertunity->op_reference_no = $rand;
        $oppertunity->oppertunity_name = 'Service --' . $service->equipment_serial_no;
        $oppertunity->user_id = $service->user_id;
        $oppertunity->staff_id = $service->engineer_id;
        $oppertunity->created_by_id = $staff_id;
        $oppertunity->service_id = $id;
        $oppertunity->save();

        $oppertunity_id = $oppertunity->id;

        $serviceParts = ServicePart::where('service_id', $id)->where('service_part_status', 'part-intend ')->where('status', 'Approved')->get();

        foreach ($serviceParts as $value) {
            //echo $value->product_id.'``````````````';
            if (!empty($value->product_id)) {
                $oppertunityProduct = new Oppertunity_product;
                $oppertunityProduct->oppertunity_id = $oppertunity_id;
                $oppertunityProduct->product_id = $value->product_id;
                $oppertunityProduct->company_id = '5';
                $oppertunityProduct->save();
            }
        }
        //die();
        ServicePart::where('service_id', $id)->where('service_part_status', 'part-intend ')->where('status', 'Approved')->update(['status' => 'Oppertunity Created']);
        return redirect()->back()->with('success', 'Oppertunity Created for Approved Parts Succcessfully');
    }
    public function servicePartApprove($id)
    {
        ServicePart::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Part Approved Successfully');
    }
    public function serviceTechApprove($id)
    {
        ServiceTechnicalSupport::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Technical Support Approved Successfully');
    }
    public function servicePartDelete(Request $request)
    {
        ServicePart::where('id', $request->service_part_id)->delete();
        return redirect()->back()->with('success', 'Part Deleted Successfully');
    }

    public function serviceTaskDelete($id)
    {
        Task::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Task Deleted Successfully');
    }
    public function serviceProductOrder(Request $request)
    {

        if (!empty($request->part_no)) {

            ServicePart::where('id', $request->service_part_id)
                ->update(['part_no' => $request->part_no]);
        } else {
            $orderedDate = $request->ordered_date;
            $expectedDate = $request->expected_date;
            if ($request->approve_part == 'Y') {
                $status = 'Approved';
            } else {
                $status = 'Requested';
            }
            ServicePart::where('id', $request->service_part_id)
                ->update(['ordered_date' => $orderedDate, 'expected_date' => $expectedDate, 'status' => $status]);

        }
        return redirect()->back()->with('success', ' Product Part Details Added ');
    }
    public function serviceAddProduct(Request $request)
    {
        $service = Service::where('id', $request->service_id)->first();

        $product = new Product;
        $product->name = $request->part_name;
        $product->related_products = $service->equipment_id;
        $product->save();

        $products = Product::whereRaw('FIND_IN_SET(' . $service->equipment_id . ',related_products)')->get();

        return response()->json(['products' => $products]);
    }
    public function serviceTechnicalSupport(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $serviceSupport = new ServiceTechnicalSupport;
        $serviceSupport->service_id = $request->service_id;
        $serviceSupport->staff_id = $request->tech_support_id;
        $serviceSupport->status = "Requested";
        $serviceSupport->created_by = $staff_id;
        $serviceSupport->description = $request->tech_support_description;
        $serviceSupport->save();

        return redirect()->back()->with('success', ' Techical Support Requested ');
    }
    public function serviceApprove($id)
    {
        Service::where('id', $id)->update(['status' => 'Approved']);

        $service = Service::find($id);

        $staff_id = optional($service->serviceEngineer)->name;

        if (!empty($staff_id)) {
            $staff_permission = User_permission::where('staff_id', $staff_id)->first();

            if (!empty($staff_permission)) {
                if (optional($staff_permission)->work_start_coordinator != optional($staff_permission)->work_update_coordinator) {
                    $staff_permission->approve_task = 'Y';

                    $staff_permission->save();
                }

            }
        }

        return redirect()->back()->with('success', ' Service Approved');
    }

    public function pm_approve($id)
    {
        PmDetails::where('id', $id)->update(['status' => 'Approved']);

        return redirect()->back()->with('success', ' PM Approved');
    }

    
    public function serviceComplete($id)
    {
        Service::where('id', $id)->update(['status' => 'Completed']);

        $tasks = Task::with('taskCreateBy')->where('service_id', $id)->where('service_task_method', 'visit')->get();

        foreach($tasks as $save)
        {
            $save->status = 'Complete'; 

            $save->save();
        }

        return redirect()->back()->with('success', ' Service Completed');

    }
    public function serviceFeedback(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $serviceFeedback = new ServiceFeedback;
        $serviceFeedback->contact_person_id = $request->contact_person_id;
        $serviceFeedback->service_id = $request->service_id;
        $serviceFeedback->created_by = $staff_id;
        $serviceFeedback->rating = $request->feedback_rating;
        $serviceFeedback->description = $request->feedback_description;
        $serviceFeedback->pm_detail_id = $request->pm_detail_id;

        $serviceFeedback->save();

        return redirect()->back()->with('success', ' Feedback added successfully ');

    }
    public function serviceWithoutResponse()
    {
        $services = Service::where('created_at', '<=', Carbon::now()->subMinutes(5)->toDateTimeString())->where('status', 'created')->get();
        foreach ($services as $service) {
            Service::where('id', $service->id)->update(['status' => 'Pending']);
        }
    }
    public function serviceVisitTaskResponse()
    {
        $tasks = Task::where('service_id', '!=', 'NULL')->where('service_task_status', 'Task Created')->where('created_at', '<=', Carbon::now()->subMinutes(5)->toDateTimeString())->get();
        //print_r($tasks);
        //die();
        foreach ($tasks as $task) {
            Service::where('id', $task->service_id)->update(['status' => 'Pending']);
        }
    }

    public function serviceAudit(Request $request)
    {
        $tasks = Task::with('taskCreateBy')->where('service_id', $request->service_id)->get();
        $task_comments = Task_comment::with('taskCommentStaff')->where('service_id', $request->service_id)->get();
        $result = new \Illuminate\Database\Eloquent\Collection;
        $result = $result->merge($task_comments)->merge($tasks)->sortBy('created_at');

        $data = '';
        $i = 1;
        foreach ($result as $results) {
            $data .= '<tr><td>' . $i . '</td><td>';
            if ($results->getTable() == 'task_comment') {
                if ($results->call_status == 'Y') {
                    $data .= 'Call Responce Added</td>';
                } else {
                    $data .= 'Visit Responce Added</td>';
                }
                $data .= '<td>' . $results->taskCommentStaff->name . '</td>';
            } else {
                $data .= 'Visit Task Created </td>';
                $data .= '<td>' . $results->taskCreateBy->name . '</td>';
            }
            $data .= '<td>' . $results->created_at . '</td></tr>';
            $i++;
        }

        return response()->json(['result' => $data]);
    }
    public function taskResponseDetails(Request $request)
    {
        $task_comment = Task_comment::find($request->task_comment_id);
        return response()->json(['result' => $task_comment]);

    }

    public function addReplyToTaskComment(Request $request)
    {

        $staff_id = session('STAFF_ID');
        $task_status = '';
        if ($request->task_comment_type == 'Visit') {
            if ($request->task_status == 'Closed') {
                $task_status = 'Visit Closed';
            } else {
                $task_status = 'Visit Not Closed';
            }
        } else {
            if ($request->task_status == 'Closed') {
                $task_status = 'Call Closed';
            } else {
                $task_status = 'Call Not Closed';
            }
        }
        //echo $task_status;
        //die('aaa');
        Task_comment::where('id', $request->task_comment_id)
            ->update(['service_task_problem' => $request->service_task_problem, 'service_task_action' => $request->service_task_action, 'service_task_final_status' => $request->service_task_final_status, 'service_task_status' => $task_status]);
        // echo $request->service_task_action.'``````````````';
        //die('wwwww');
        if ($staff_id == 127 || $staff_id == 34) {

            if ($request->task_comment_type == 'Visit') {

                $cur_date = date('Y-m-d');

                $task_comment = Task_comment::find($request->task_comment_id);

                $task_comment->status = $request->status;

                $task_comment->save();

                $task_comment_replay = new Task_comment_replay;

                $task_comment_replay->task_id = $task_comment->task_id;

                $task_comment_replay->comment = $request->replay_comment;

                $task_comment_replay->task_comment_id = $request->task_comment_id;

                $task_comment_replay->parent_id = $task_comment->task_id;

                $task_comment_replay->added_by_id = $staff_id;

                if ($request->parent_id > 0) {

                    $task_comment_replay_update = Task_comment_replay::find($request->parent_id);

                    $task_comment_replay_update->replay_status = "Y";

                    $task_comment_replay_update->save();

                }

                //Task::where('id',$request->task_id)->update(['service_task_status' => 'Task Updated']);

                $task = Task::find($task_comment->task_id);

                if ($staff_id == $task->followers) {

                    if ($request->status == "Y") {

                        $task->status = "In Progress";
                        $task->staff_status = "Approved";
                        $task_comment_replay->added_by = 'admin';
                        $task->service_task_status = 'Task Approved';

                    } else {

                        $task->status = "In Progress";
                        $task->staff_status = "Pending";
                        $task_comment_replay->added_by = 'admin';
                        $task->service_task_status = 'Task Rejected';

                    }
                } else {

                    $task->staff_status = "In Progress";
                    $task->status = "Approved";
                    $task_comment_replay->added_by = 'staff';
                    $task->service_task_status = 'Task Approved';

                }
                // $task->staff_status = "In Progress";

                // $task->status = "Pending";

                $task->save();

                $task_comment_replay->save();

                if (!empty($task->service_id)) {

                    if ($request->status == "Y") {
                        Service::where('id', $task->service_id)->update(['status' => 'Open']);
                        // ServicePart::where('task_comment_id',$request->task_comment_id)->update(['status' => 'Approved']);
                    }

                }

                $update = DB::table('dailyclosing_details')->where('staff_id', $staff_id)->where('start_date', $cur_date)->update(['approved_fair' => 'Pending', 'approved_work' => 'Pending']);

                $update = DB::table('task_comment')->where('task_id', $task_comment->task_id)->update(['admin_view' => "Y"]);
            } else {
                $task_comment = Task_comment::find($request->task_comment_id);

                $task_comment->status = $request->status;

                $task_comment->save();

                $task_comment_replay = new Task_comment_replay;

                $task_comment_replay->task_id = 0;

                $task_comment_replay->comment = $request->replay_comment;

                $task_comment_replay->task_comment_id = $request->task_comment_id;

                $task_comment_replay->parent_id = 0;

                $task_comment_replay->added_by = 'staff';

                $task_comment_replay->added_by_id = $staff_id;

                $task_comment_replay->save();

                if ($request->status == "Y") {
                    Service::where('id', $task_comment->service_id)->update(['status' => 'Open']);
                    // ServicePart::where('task_comment_id',$request->task_comment_id)->update(['status' => 'Approved']);
                }

            }
        } else {
            Task_comment::where('id', $request->task_comment_id)->update(['status' => 'N']);
        }
        return redirect()->back()->with('success', 'Reply Added to Response');

    }

    public function serviceUploadRemark(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $taskCommentUpload = new TaskCommentUpload;
        $taskCommentUpload->added_date = $request->added_date;
        $taskCommentUpload->task_comment_id = $request->task_comment_id . '    ';
        $taskCommentUpload->remarks = $request->remark;
        $taskCommentUpload->created_by = $staff_id;

        $taskCommentUpload->save();

        Task_comment::where('id', $request->task_comment_id)->update(['status' => 'U']);
        return redirect()->back()->with('success', 'Upload details added');

    }
    public function serviceResponseHistory(Request $request, $id)
    {
        // $service = Service::with('serviceTaskComment', function ($query) {
        // return $query->where('status','Y')->orwhere('status','U');})->where('service_type',$id)->get();
        // $services = Service::with('serviceProduct','serviceUser')->where('service_type',$id)->orderBy('id', 'DESC')->get();
        //$service = Service::where('service_type',$id)->pluck('id');
        //$task_comment = Task_comment::with('taskCommentService.serviceProduct','taskCommentService.serviceProduct')->whereIn('service_id',$service)->where('status','Y')->orwhere('status','U')->get();
        // print_r($services);

        if ($request->ajax()) {
            
            $data = Service::with('serviceUser','serviceProduct')-> where('id', '>', '0')->where('service_type', $id);


            if (!empty($request->state_id)) {  
                $data->whereHas('serviceUser', function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                });
            }

            return Datatables::of($data)

                ->addColumn('in_ref_no', function ($data) {
                    if (!empty($data->internal_ref_no)) {
                        return $data->internal_ref_no;
                    } else {
                        return "";
                    }
                })

                ->addColumn('customer', function ($data) {
                    if (!empty($data->serviceUser)) {
                        return $data->serviceUser->business_name . '-' . Service::userdistrict($data->serviceUser->district_id);
                    } else {
                        return "";
                    }
                })

                // ->filterColumn('customer', function ($query, $keyword) {
                //     $query->whereIn('id', Service::whereHas('serviceUser', function ($q) use ($keyword) {
                //         $q->where('state_id', 'like', "%" . $keyword . "%");
                //     })->pluck('id'));
                // })
                
                
                ->addColumn('equipment_name', function ($data) {
                    if (!empty($data->serviceProduct)) {
                        return $data->serviceProduct->name;
                    } else {
                        return "";
                    }
                })

                ->addColumn('equipment_sr_no', function ($data) {
                    if (!empty($data->equipment_serial_no)) {
                        return $data->equipment_serial_no;
                    } else {
                        return "";
                    }
                })

                ->addColumn('task_comment_observed', function ($data) {
                    $task_comment = Task_comment::where('service_id', $data->id)
                        ->where(function ($q) {
                            $q->where('status', 'Y')
                                ->orWhere('status', 'U');
                        })->get();

                    $observed = '';
                    foreach ($task_comment as $task_comments) {
                        $observed .= '<table class="mobile_view"><tr><td class="mobile_view">' . $task_comments->service_task_problem . '</td></tr></table>';
                    }

                    return $observed;
                })

                ->filterColumn('task_comment_observed', function ($query, $keyword) {
                     $query->whereIn('id',Task_comment::select('service_id')->where('service_task_problem', "like", "%" . $keyword . "%"));
                    // $query->whereHas('task_comments', function ($iq) use ($keyword) {
                    //     $iq->where('service_task_problem', "like", "%" . $keyword . "%");
                    // });
                })

                ->addColumn('task_comment_action', function ($data) {
                    $task_comment = Task_comment::where('service_id', $data->id)->where(function ($q) {
                        $q->where('status', 'Y')
                            ->orWhere('status', 'U');
                    })->get();
                    $action = '';
                    foreach ($task_comment as $task_comments) {
                        $action .= '<table class="mobile_view"><tr><td class="mobile_view">' . $task_comments->service_task_action . '</td></tr</table>';
                    }
                    return $action;
                })

                ->filterColumn('task_comment_action', function ($query, $keyword) {
                $query->whereIn('id',Task_comment::select('service_id')->where('service_task_action', "like", "%" . $keyword . "%"));

                    // $query->whereHas('task_comments', function ($iq) use ($keyword) {
                    //     $iq->where('service_task_action', "like", "%" . $keyword . "%");
                    // });
                })

                ->addColumn('task_comment_final', function ($data) {
                    $task_comment = Task_comment::where('service_id', $data->id)->where(function ($q) {
                        $q->where('status', 'Y')
                            ->orWhere('status', 'U');
                    })->get();
                    $final = '';
                    foreach ($task_comment as $task_comments) {
                        $final .= '<table class="mobile_view"><tr><td class="mobile_view">' . $task_comments->service_task_final_status . '</td></tr</table>';
                    }
                    return $final;
                })
                ->filterColumn('task_comment_final', function ($query, $keyword) {
                $query->whereIn('id',Task_comment::select('service_id')->where('service_task_final_status', "like", "%" . $keyword . "%"));

                    // $query->whereHas('task_comments', function ($iq) use ($keyword) {
                    //     $iq->where('service_task_final_status', "like", "%" . $keyword . "%");
                    // });
                })

                
            //<a class="task-btn btn btn-primary" attr-task_staff="'.$data->serviceEngineer->id .'" attr-service_id="'.$data->id.'" >Task</a>
            //<a class="approved-btn btn btn-success" attr-service_id="'.$data->id.'" >Summary</a>
            //->rawColumns(['customer'])->addIndexColumn()->make(true);
                ->rawColumns(['in_ref_no_corrective', 'customer', 'equipment_name', 'equipment_serial_no', 'task_comment_observed', 'task_comment_action', 'task_comment_final'])
                ->addIndexColumn()
                ->make(true);
        }
        $serviceType = ServiceType::find($id);
        $states = State::all();

        //return view('staff.service.history',compact('services'));
        return view('staff.service.history2', compact('serviceType','states'));
    }
}
