<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Category;
use App\Contact_person;
use App\Contract;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\District;
use App\EquipmentStatus;
use App\Http\Controllers\Controller;
use App\Ib;
use App\MachineStatus;
use App\MsaContract;
use App\Oppertunity;
use App\Oppertunity_product;
use App\PmDetails;
use App\Product;
use App\Quotehistory;
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
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PmOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function pm_order(Request $request, $id)
    {

        $oppertunity = Oppertunity::find($id);

        $oppertunity->close_status = "closed";

        $oppertunity->save();

        $msacontract = new MsaContract;

        $msacontract->oppertunity_id = $oppertunity->id;

        $msacontract->quote_id = $oppertunity->quote_close_id;

        $msacontract->contract_status = 'pending';

        $msacontract->save();

        return redirect()->route('staff.pm_order.index');

    }

    public function pm_order_submit(Request $request)
    {
        $opper_id = $request->opper_id;

        $quote_id = $request->quote_id;

        $oppertunity = Oppertunity::find($opper_id);

        // $oppertunity->close_status = "closed";

        $oppertunity->quote_close_id = $quote_id;

        $oppertunity->save();

        $qt = Quotehistory::find($quote_id);

        $qt->close_status = 'Y';

        $qt->save();

        $msacontract = new MsaContract;

        $msacontract->oppertunity_id = $oppertunity->id;
        
        $msacontract->quote_id = $oppertunity->quote_close_id;

        $msacontract->contract_status = 'pending';

        $msacontract->save();

        return response()->json([
            'success' => true,
            'message' => 'Opportunity Closed successfully submitted.',
        ]);
    }


    public function index(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $contract_permission =CoordinatorPermission::where('staff_id', $staff_id)->where('type','contract')->first();

        if (optional($contract_permission)->common_view != 'view') {

            return redirect()->route('staff.dashboard');
        }


        if ($request->ajax()) {

            $data = MsaContract::with('msaoppertunity')->where('id', '>', 0)->whereNotNull('oppertunity_id');

            
            $count_total = $data->count();

            if ($request->type == 'pending') {
                $data->where('contract_status', 'pending');
            }

            if ($request->type == 'created') {
                $data->where('contract_status', 'created');
            }

            if (!empty($request->state)) {

                $state = $request->state;

                $data->whereIn('oppertunity_id', function ($query) use ($state) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('state', $state);
                });
            }

            if (!empty($request->district)) {

                $district = $request->district;

                $data->whereIn('oppertunity_id', function ($query) use ($district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('district', $district);
                });
            }


            if (!empty($request->account_name)) {

                $account_name = $request->account_name;

                $data->whereIn('oppertunity_id', function ($query) use ($account_name) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('user_id', $account_name);
                });
            }

            if (!empty($request->engineer)) {

                $engineer = $request->engineer;

                $data->whereIn('oppertunity_id', function ($query) use ($engineer) {
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

            $count_filter = $data->count(); 

            return Datatables::of($data)

                ->addColumn('customer', function ($data) {

                    if (!empty($data->oppertunity_id)) {

                        $oppertunity = Oppertunity::find($data->oppertunity_id);

                        $customer = "";
                        $customer_district= "";

                        if (!empty($oppertunity)) {
                            $customer = optional($oppertunity->customer)->business_name;

                            $customer_district = optional($oppertunity->customer)->userdistrict->name;

                        }

                        return $customer .' ('. $customer_district .')';
                        
                    } else {

                        return "";
                    }
                })

                // ->filterColumn('customer', function ($query, $keyword) {
                //     $query->whereIn('id', Oppertunity::select('user_id')
                //         ->whereIn('user_id', User::where('business_name', 'like', "%" . $keyword . "%")
                //         ->select('id')));
                // })
                

                ->filterColumn('customer', function ($query, $keyword) {

                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('user_id', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('users')
                                ->where('business_name', 'like', "%" . $keyword . "%");
                        });
                    });

                })

                ->addColumn('state', function ($data) {

                    if (!empty($data->oppertunity_id)) {
  
                        $oppertunity = Oppertunity::find($data->oppertunity_id);

                        $state = "";

                        if (!empty($oppertunity)) {
                            $state = optional($oppertunity->oppstate)->name;
                        }

                        return $state;
                    } else {

                        return "";
                    }
                })


                ->filterColumn('state', function ($query, $keyword) {

                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('state', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('state')
                                ->where('name', 'like', "%" . $keyword . "%");
                        });
                    });

                })

                ->addColumn('district', function ($data) {

                    if (!empty($data->oppertunity_id)) {

                        $oppertunity = Oppertunity::find($data->oppertunity_id);

                        $district = "";

                        if (!empty($oppertunity)) {
                            $district = optional($oppertunity->oppdistrict)->name;
                        }

                        return $district;
                    } else {

                        return "";
                    }
                })

                ->filterColumn('district', function ($query, $keyword) {

                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('district', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('district')
                                ->where('name', 'like', "%" . $keyword . "%");
                        });
                    });

                })

                ->addColumn('engineer', function ($data) {

                    if (!empty($data->oppertunity_id)) {

                        $oppertunity = Oppertunity::find($data->oppertunity_id);

                        $engineer = "";

                        if (!empty($oppertunity)) {
                            $engineer = optional($oppertunity->createdBy)->name;
                        }

                        return $engineer;
                    } else {

                        return "";
                    }
                })


                ->filterColumn('engineer', function ($query, $keyword) {

                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('staff_id', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('staff')
                                ->where('name', 'like', "%" . $keyword . "%");
                        });
                    });

                })


                ->addColumn('machine', function ($data) {

                    $machine = "";

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    $opp = Oppertunity::find($data->oppertunity_id);

                    $quote = Quotehistory::find($data->quote_id);

                    $oppertunity_prodt = "";

                    if(!empty($quote))
                    {
                        $opper_products = explode(',', $quote->opper_product_id);

                        $oppertunity_prodt = Oppertunity_product::whereIn('id',$opper_products)->get();
                    }

                    if (!empty($Contracts)) {
                        $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                        foreach ($equipments as $equipment) {
                            $machine .= optional($equipment->equipment)->name ."<br>";

                        }

                    } 
                    elseif (!empty($quote) && !empty($oppertunity_prodt)) {

                        foreach ($oppertunity_prodt as $equipment) {

                            $machine .= optional($equipment->oppertunityProduct)->name. "<br>";

                        }
                    }
                    elseif (!empty($opp)) {

                        foreach ($opp->oppertunityOppertunityProduct as $equipment) {

                            $machine .= optional($equipment->oppertunityProduct)->name. "<br>";

                        }
                    }

                    return $machine;
                })

                ->filterColumn('machine', function ($query, $keyword) {

                    // $query->whereIn('id', function ($subquery) use ($keyword) {
                    //         $subquery->select('msa_contract_id')
                    //         ->from('contracts')
                    //         ->whereIn('id', function ($subqueryinner) use ($keyword) {
                    //             $subqueryinner->select('contract_id')
                    //             ->from('contract_products')
                    //             ->whereIn('equipment_id', function ($subqueryinner) use ($keyword) {
                    //                 $subqueryinner->select('id')
                    //                 ->from('products')
                    //                 ->where('name', 'like', "%" . $keyword . "%");
                    //     });
                    // });

                    // })
                    
                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('id', function ($subqueryinner) use ($keyword) {
                            $subqueryinner->select('oppertunity_id')
                            ->from('oppertunity_products')
                            ->whereIn('product_id', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('products')
                                ->where('name', 'like', "%" . $keyword . "%");
                        });
                    });

                    });

                })


                ->addColumn('serial_no', function ($data) {

                    $serial_no = "";

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    $opp = Oppertunity::find($data->oppertunity_id);

                    $quote = Quotehistory::find($data->quote_id);

                    $oppertunity_prodt = "";

                    if(!empty($quote))
                    {
                        $opper_products = explode(',', $quote->opper_product_id);

                        $oppertunity_prodt = Oppertunity_product::whereIn('id',$opper_products)->get();
                    }

                    if (!empty($Contracts)) {
                        $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                        foreach ($equipments as $equipment) {

                            $serial_no .= optional($equipment)->equipment_serial_no . "<br>";
                        }

                    } 
                    elseif (!empty($quote) && !empty($oppertunity_prodt)) {

                        foreach ($oppertunity_prodt as $equipment) {

                            $serial_no .= optional($equipment->oppertunityProductIb)->equipment_serial_no. "<br>";

                        }
                    }
                    elseif (!empty($opp)) {
                        foreach ($opp->oppertunityOppertunityProduct as $equipment) {
                            $serial_no .= optional($equipment->oppertunityProductIb)->equipment_serial_no .  "<br>";

                        }
                    }

                    return $serial_no;
                })


                ->filterColumn('serial_no', function ($query, $keyword) {

                    $query->whereIn('oppertunity_id', function ($subquery) use ($keyword) {
                            $subquery->select('id')
                            ->from('oppertunities')
                            ->whereIn('id', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('oppertunity_id')
                                ->from('contract_products')
                                ->where('equipment_serial_no', 'like', "%" . $keyword . "%");
   

                    });

                    })
                    
                   ->orWhereIn('oppertunity_id', function ($subquery) use ($keyword) {
                        $subquery->select('id')
                        ->from('oppertunities')
                        ->whereIn('id', function ($subqueryinner) use ($keyword) {
                            $subqueryinner->select('oppertunity_id')
                            ->from('oppertunity_products')
                            ->whereIn('ib_id', function ($subqueryinner) use ($keyword) {
                                $subqueryinner->select('id')
                                ->from('ib')
                                ->where('equipment_serial_no', 'like', "%" . $keyword . "%");
                        });
                    });

                    });

                })


                
                ->addColumn('in_ref_no', function ($data) {

                    $in_ref_no = "";

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    if ($Contracts) {

                        // $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                        // foreach ($equipments as $equipment) {

                        //     $in_ref_no = optional($equipment)->in_ref_no;

                        // }
                        $in_ref_no = $Contracts->in_ref_no;

                    }
                    return optional($Contracts)->in_ref_no; ;
                })



                ->filterColumn('in_ref_no', function ($query, $keyword) {

                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->where('in_ref_no', 'like', "%" . $keyword . "%");
                    });

                })


                ->addColumn('contract_type', function ($data) {

                    $contract_type = "";

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    if ($Contracts) {

                        $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                        foreach ($equipments as $equipment) {

                            $contract_type = optional($equipment)->contract_type;

                        }
                        $contract_type = $Contracts->contract_type;
                    }
                    return $contract_type;
                })


                ->filterColumn('contract_type', function ($query, $keyword) {

                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->where('contract_type', 'like', "%" . $keyword . "%");
                    });

                })


                ->addColumn('contract_start_date', function ($data) {

                    $machine = "";
                
                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();
                
                    if (!empty($Contracts)) {
                        $date = $Contracts->contract_start_date;

                        $formattedDate = $date ? Carbon::parse(str_replace(':', '-',$date))->format('d-m-Y') : '';
             
                        $machine .= $formattedDate;
                    }
                    return $machine;
                })
                
                ->filterColumn('contract_start_date', function ($query, $keyword) {
                
                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->whereRaw("DATE_FORMAT(contract_start_date, '%d-%m-%Y') like ?", ["%" . $keyword . "%"]);
                    });
                
                })
                


                ->addColumn('contract_end_date', function ($data) {

                    $machine = "";

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    if (!empty($Contracts)) {

                        $formattedDate = "";

                        if(!empty(optional($Contracts)->contract_end_date))
                        {
                            $date = $Contracts->contract_end_date;

                            $formattedDate = $date ? Carbon::parse(str_replace(':', '-',$date))->format('d-m-Y') : '';
                        }
                        
                        $machine .= $formattedDate;
                    }

                    return $machine;
                })
                
                ->filterColumn('contract_end_date', function ($query, $keyword) {
                
                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->whereRaw("DATE_FORMAT(contract_end_date, '%d-%m-%Y') like ?", ["%" . $keyword . "%"]);
                    });
                
                })

                ->addColumn('pm_dates_no', function ($data) {

                    $pm_no = 0;

                    $pm_count = 0;

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    if (!empty($Contracts)) {
                        $pmdates = json_decode($Contracts->pm_dates);

                        if (!empty($pmdates) && is_array($pmdates)) {
                            $pm_no = count($pmdates);
                        }

                    }

                    return $pm_no;
                })

                ->filterColumn('pm_dates_no', function ($query, $keyword) {

                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->where('pm_dates', 'like', "%" . $keyword . "%");
                });

                }) 

                ->addColumn('created_at', function ($data) {

                    $created_at = Carbon::parse($data->created_at)->format('d-m-Y');

                    return $created_at;
                })
                                
                ->filterColumn('created_at', function ($query, $keyword) {
                
                    $query ->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') like ?", ["%" . $keyword . "%"]);

                })


                ->addColumn('revenue', function ($data) {

                    $revenue = 0;

                    $Contracts = Contract::where('msa_contract_id', $data->id)->first();

                    if (!empty($Contracts)) {
                        // $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                        // foreach ($equipments as $equipment) {
                        //     $revenue .= optional($equipment)->revanue . '<br>';
                        // }
                        $revenue = $Contracts->revanue;

                    }

                    return $revenue;
                })

                ->filterColumn('revenue', function ($query, $keyword) {

                    $query->whereIn('id', function ($subquery) use ($keyword) {
                        $subquery->select('msa_contract_id')
                        ->from('contracts')
                        ->where('revanue', 'like', "%" . $keyword . "%");
                });

                })


                ->addColumn('action', function ($data) use($contract_permission) {

                    $edit = false;
                    if(optional( $contract_permission)->common_edit=='edit' )
                    {
                        $edit = true;
                    }

                    $button = "";

                    if (!empty($data->oppertunity_id) && $edit) {

                        if ($data->contract_status == 'created') {
                            $button = '

                        <span class="call-task">Closed</span>

                         <a class="call-task" href="' . route('staff.view_oppertunity_contact', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                        ;

                        } 
                        else {
                            $button = '
                        <a class="call-task" href="' . route('staff.oppertunity_contact', $data->id) . '"><span class="glyphicon glyphicon-pencil"></span></a>';

                        }
                    }

                    return $button;
                })

                ->with([
                    "recordsTotal" => $count_total,
                    "recordsFiltered" => $count_filter,
                ])

                ->rawColumns(['customer', 'state', 'district', 'engineer', 'machine', 'serial_no', 'contract_end_date', 'contract_start_date', 'action', 'revenue'])
                ->addIndexColumn()

            // ->setRowClass(function ($data) {

            //     if($data->status == 'created' )  {
            //             $class='black';
            //         }
            //     elseif($data->status == 'Approved'){
            //             $class='orange';
            //         }
            //     elseif($data->status == 'Pending'){
            //             $class='red';
            //         }
            //     elseif($data->status == 'Open'){
            //             $class='blue';
            //         }
            //     elseif($data->status == 'Completed'){
            //             $class='green';
            //         }
            //         return $class;
            //     })

                ->make(true);
        }

        $opperids = MsaContract::pluck('oppertunity_id')->unique()->toArray();
        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $oppIds = $opper->pluck('id')->unique()->toArray();

        $productIds = Oppertunity_product::whereIn('oppertunity_id', $oppIds)->pluck('product_id')->unique()->toArray();

        $stateIds = $opper->pluck('state')->unique()->toArray();

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $userIds = $opper->pluck('staff_id')->unique()->toArray();

        $brandIds = Product::whereIn('id', $productIds)->pluck('brand_id')->unique()->toArray();

        $categoryIds = Product::whereIn('id', $productIds)->pluck('category_id')->unique()->toArray();

        $state = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();

        $district = District::whereIn('id', $districtIds)->orderBy('name', 'asc')->get();

        $engineers = Staff::whereIn('id', $userIds)->orderBy('name', 'asc')->get();

        $brands = Brand::whereIn('id', $brandIds)->orderBy('name', 'asc')->get();

        $categorys = Category::whereIn('id', $categoryIds)->orderBy('name', 'asc')->get();

        return view('staff.pm_order.index', compact('state', 'district', 'engineers', 'brands', 'categorys'));
    } 
 

    public function create($id)
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

        Task::where('service_id', $id)->update([
            'name' => $name,
            'assigns' => $request->engineer_id,
            'user_id' => $request->user_id,
            'description' => $request->call_details,
        ]);

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
        $service = Service::with('serviceServiceType', 'serviceUser', 'serviceProduct')->where('id', $id)->first();
        $task = new Task;

        if ($service_id->service_type == "2" && !empty($service_id->pmContract) && !empty($service->pmContract->oppertunity_id)) {

            $contract_product_id = $request->contract_product_id;

            $contract_product = ContractProduct::find($contract_product_id);

            $equipment_id = $contract_product->equipment_id;

            $task->contract_product_id = $contract_product_id;
            $task->name = $service->serviceUser->business_name . '-' . $service->serviceServiceType->short_name . '-' . $contract_product->equipment->name . '-' . $contract_product->equipment_serial_no;

        } else {
            $task->name = $service->serviceUser->business_name . '-' . $service->serviceServiceType->short_name . '-' . $service->serviceProduct->name . '-' . $service->equipment_serial_no;

            $equipment_id = $service->equipment_id;

        }

        if ($service_id->service_type == "2" && !empty($service_id->pmContract)) {

            $pmdetail = $service_id->productPMList->where('status', 'open')->first();

            if (!empty($pmdetail)) {
                $engineer_id = $pmdetail->engineer_name;
            }
        }
        $task->assigns = $engineer_id;

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
        $task->service_day = $request->schedule_date;
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
                        return $data->serviceContactPerson->name . ' , ' . $data->serviceContactPerson->mobile;
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
                        return $data->created_at;
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

    public function serviceDetail(Request $request, $id)
    {
        $staff_id = session('STAFF_ID');
        $tasks = Task::with('taskCreateBy')->where('service_id', $id)->where('service_task_method', 'visit')->get();
        $service = Service::with('serviceUser.userContact', 'serviceProduct', 'serviceFeedback', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')->where('id', $id)->first();
        $pmDates = optional($service->pmContract)->pm_dates ?? [];
        $callResponse = Task_comment::with('taskCommentUpload', 'taskCommentTask', 'taskCommentReplay', 'taskCommentContactPerson', 'taskCommentStaff')->where('service_id', $id)->get();
        $products = Product::whereRaw('FIND_IN_SET(' . $service->equipment_id . ',related_products)')->get();
        $serviceParts = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'part-intend')->get();
        $servicePartsTests = ServicePart::with('servicePartProduct', 'servicePartTaskComment')->where('service_id', $id)->where('service_part_status', 'test')->get();
        $staffs = Staff::get();
        $serviceTechnicalSupports = ServiceTechnicalSupport::with('supportStaff', 'supportCreatedBy')->where('service_id', $id)->get();
        $serviceTaskComment = Task_comment::with('taskCommentStaff', 'taskCommentTask', 'taskCommentContactPerson', 'taskCommentService.serviceIb', 'taskCommentServiceParts.servicePartProduct')
            ->where('service_id', $id)->latest('created_at')->first();
        $serviceFeedbacks = ServiceFeedback::with('serviceFeedbackStaff', 'serviceFeedbackContact')->where('service_id', $id)->get();
        $serviceOppertunity = Oppertunity::with('oppertunityOppertunityProduct.oppertunityProduct', 'createdBy')->where('service_id', $id)->get();
        $previouslySelectedEngineers = PmDetails::where('service_id', $id)->pluck('engineer_name', 'pm')->toArray();

        $contractpdt = [];
        $contractpdtIDS = [];
        if (!empty($service->pmContract)) {
            if ($staff_id == 55 || $staff_id == 127 || $staff_id == 34 || $staff_id == 56) {
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

        //die('dddd');
        return view('staff.service.serviceDetail', compact('contractpdt', 'contractpdtIDS', 'serviceOppertunity', 'serviceFeedbacks', 'serviceTaskComment', 'serviceTechnicalSupports', 'service', 'pmDates', 'tasks', 'callResponse', 'products', 'serviceParts', 'staffs', 'servicePartsTests', 'previouslySelectedEngineers'));
    }
    public function servicecallResponse(Request $request)
    {
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
        return redirect()->back()->with('success', ' Service Approved');
    }
    public function serviceComplete($id)
    {
        Service::where('id', $id)->update(['status' => 'Completed']);
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
        if ($staff_id == 127 || $staff_id == 55) {

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
            $data = Service::with('serviceProduct', 'serviceUser')->where('service_type', $id)->where('status', 'Completed')->orderBy('id', 'DESC')->get();

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
                ->addColumn('equipment_name', function ($data) {
                    if (!empty($data->serviceProduct)) {
                        return $data->serviceProduct->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('equipment_serial_no', function ($data) {
                    if (!empty($data->equipment_serial_no)) {
                        return $data->equipment_serial_no;
                    } else {
                        return "";
                    }
                })
                ->addColumn('task_comment_observed', function ($data) {
                    $task_comment = Task_comment::where('service_id', $data->id)->where(function ($q) {
                        $q->where('status', 'Y')
                            ->orWhere('status', 'U');
                    })->get();
                    $observed = '';
                    foreach ($task_comment as $task_comments) {
                        $observed .= '<table class="mobile_view"><tr><td class="mobile_view">' . $task_comments->service_task_problem . '</td></tr</table>';
                    }
                    return $observed;
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

            //<a class="task-btn btn btn-primary" attr-task_staff="'.$data->serviceEngineer->id .'" attr-service_id="'.$data->id.'" >Task</a>
            //<a class="approved-btn btn btn-success" attr-service_id="'.$data->id.'" >Summary</a>
            //->rawColumns(['customer'])->addIndexColumn()->make(true);
                ->rawColumns(['in_ref_no', 'customer', 'equipment_name', 'equipment_serial_no', 'task_comment_observed', 'task_comment_action', 'task_comment_final'])
                ->addIndexColumn()
                ->make(true);
        }
        $serviceType = ServiceType::find($id);

        //return view('staff.service.history',compact('services'));
        return view('staff.service.history2', compact('serviceType'));
    }
}



 