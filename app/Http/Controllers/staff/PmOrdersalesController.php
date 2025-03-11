<?php

namespace App\Http\Controllers\staff;

use App\BillProduct;
use App\Brand;
use App\Category;
use App\Contact_person;
use App\Contract;
use App\ContractBill;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\District;
use App\EquipmentStatus;
use App\Http\Controllers\Controller;
use App\Ib;
use App\MachineStatus;
use App\Models\QuoteProduct;
use App\SalesContract;
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

class PmOrdersalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index(Request $request)
    {
 
        $staff_id = session('STAFF_ID');

        if ($request->company_type == 'bio') {

            $company_type = 5;
        } else {
            $company_type = 6;

        }

        $type = $request->company_type;

        $sales_bio_permission =CoordinatorPermission::where('staff_id', $staff_id)->where('type','sales_bio')->first();

        $sales_bec_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type','sales_bec')->first();

        if ( (optional($sales_bio_permission)->common_view != 'view' && $request->company_type == 'bio' ) || (optional($sales_bec_permission)->common_view != 'view' &&  $request->company_type == 'bec' ) || (optional($sales_bio_permission)->common_view != 'view' &&  optional($sales_bec_permission)->common_view != 'view' ) || ($request->company_type != 'bio' && $request->company_type != 'bec')) {

            return redirect()->route('staff.dashboard');
        }

        if (!empty($request->status)) {

            $status = $request->status;
        } else {
            $status = "";
        }


        if($status != 'payment')
        {

            if ($request->ajax()) {

                $data = SalesContract::with('salesoppertunity')->where('id', '>', 0)->where('company_type', $company_type)->whereNotNull('oppertunity_id');

                $count_total = $data->count();

                if(!empty($status) && $status!='paymentview')
                {
                    $data->where('contract_status', $status);
                }

                elseif ($status == 'paymentview') {
                    $data->whereIn('contract_id', function ($query) {
                        $query->select('contract_products.contract_id')
                            ->from('contract_products')
                            ->join('contract_bill', 'contract_products.id', '=', 'contract_bill.contract_product_id')
                            ->groupBy('contract_products.contract_id');
                    });
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

                            if (!empty($oppertunity)) {

                                $customer = optional($oppertunity->customer)->business_name;

                                $customer_district = optional($oppertunity->customer)->userdistrict->name;
                            }

                            return $customer .' ('. $customer_district .')';

                        } else {

                            return "";
                        }
                    })

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
                            ->whereIn('created_by_id', function ($subqueryinner) use ($keyword) {
                                    $subqueryinner->select('id')
                                    ->from('staff')
                                    ->where('name', 'like', "%" . $keyword . "%");
                            });
                        });

                    })

                    ->addColumn('machine', function ($data) {

                        $machine = "";
    
                        $Contracts = Contract::where('sales_contract_id', $data->id)->first();
    
                        $opp = Oppertunity::find($data->oppertunity_id);
    
                        if (!empty($Contracts)) {
                            $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();
    
                            foreach ($equipments as $equipment) {
                                $machine .= optional($equipment->equipment)->name . "<br>";
    
                            }
    
                        } elseif (!empty($opp)) {
    
                            $qh = Quotehistory::find($data->quote_id);
    
                            
    
                            if(!empty($qh))
                            {
                               
                                foreach (QuoteProduct::with('quoteProduct')->where('quote_id',$qh->id)->get() as $equipment) {
    
                                    $machine .= optional($equipment->quoteProduct)->name . "<br>";
    
                                }
                            }
                           
                        }
    
                        return $machine;
                    })


                    ->filterColumn('machine', function ($query, $keyword) {
                        
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




                    // ->addColumn('in_ref_no', function ($data) {

                    //     $in_ref_no = "";

                    //     $Contracts = Contract::where('sales_contract_id', $data->id)->first();

                    //     if ($Contracts) {

                    //         // $equipments = ContractProduct::where('contract_id', $Contracts->id)->get();

                    //         // foreach ($equipments as $equipment) {

                    //         //     $in_ref_no = optional($equipment)->in_ref_no;

                    //         // }
                    //         $in_ref_no = $Contracts->in_ref_no;

                    //     }
                    //     return optional($Contracts)->in_ref_no;
                    //     ;
                    // })


                    // ->filterColumn('in_ref_no', function ($query, $keyword) {

                    //     $query->whereIn('id', function ($subquery) use ($keyword) {
                    //         $subquery->select('sales_contract_id')
                    //         ->from('contracts')
                    //         ->where('in_ref_no', 'like', "%" . $keyword . "%");
                    //     });

                    // })




                    ->addColumn('created_at', function ($data) {

                        $created_at = Carbon::parse($data->created_at)->format('d-m-Y');
    
                        return $created_at;
                    })
                                    
                    ->filterColumn('created_at', function ($query, $keyword) {
                    
                        $query ->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') like ?", ["%" . $keyword . "%"]);
    
                    })


                    ->addColumn('action', function ($data) use($sales_bec_permission,$sales_bio_permission,$type,$status) {


                        $edit = false;

                        if( (optional( $sales_bec_permission)->common_edit=='edit' && $type =='bec' ) || (optional( $sales_bio_permission)->common_edit=='edit'  && $type =='bio') )
                        {
                            $edit = true;
                        }

                        $button = "";

                        if (!empty($data->oppertunity_id) && $edit) {

                            if ($data->contract_status == 'created') {
                                $button .= '
                                <a class="call-task">Closed</a>';

                            } elseif ($data->contract_status == 'rejected') {
                                $button .= '
                            <a class="call-task">Rejected</a>';

                            } elseif ($data->contract_status == 'saved' || $data->contract_status == 'verified') {
                                $button .= '
                                <a class="call-task" href="' . route('staff.view_sales_contract', $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                            } else {
                                $button .= '
                            <a class="call-task" href="' . route('staff.oppertunity_contactsales', $data->id) . '"><span class="glyphicon glyphicon-pencil"></span></a>';

                            }
                        if($status == 'paymentview'){
                            $button .= '
                            <a class="call-task" href="' . route('staff.sales.index', ['id'=>$data->id,'company_type'=>$type,'status'=>'payment']) . '"><span class="glyphicon glyphicon-usd"></span></a>';

                        }

                        }

                        return $button;
                    })

                    ->with([
                        "recordsTotal" => $count_total,
                        "recordsFiltered" => $count_filter,
                    ])

                    ->rawColumns(['in_ref_no','customer', 'state', 'district', 'engineer', 'machine', 'created_date',  'action'])
                    ->addIndexColumn()

    

                    ->make(true);
            }
        }
        else
        {
            $contract_sale_id = $request->id;

            if ($request->ajax()) {

                // dd($contract_sale_id);  


                $data = ContractBill::with('billcontractproduct.oppertunity.customer')->where('id','>',0);

                $data->whereIn('contract_product_id', function ($query) use ($contract_sale_id) {
                    $query->select('id')
                        ->from('contract_products')
                        ->where('contract_id', function ($queryin) use ($contract_sale_id) {
                            $queryin->select('id')
                                ->from('contracts')
                                ->where('id', function ($subqueryin) use ($contract_sale_id) {
                                    $subqueryin->select('contract_id')
                                ->from('sales_contract')
                                ->where('id', $contract_sale_id);
                            });
                        });
                });
                
                



                $bill_product = BillProduct::with('billproduct')->get();
    
                return Datatables::of($data)
    
                    ->addColumn('bill_date', function ($data) {
    
                        $bill_date  = $data->created_at->format('d-m-Y');

                       return $bill_date;
                    })

                    ->addColumn('customer_name', function ($data) {
    
                        $customer = optional($data->billcontractproduct->oppertunity->customer)->business_name??"";

                       return $customer;
                    })

                    // ->filterColumn('customer_name', function ($query, $keyword) {
                      
                    //     $query->whereHas('billcontractproduct.oppertunity.customer', function ($subquery) use ($keyword) {
                    //         $subquery->where('business_name', 'like', "%" . $keyword . "%");
                    //     });
                    // })

                    ->addColumn('total', function ($data) {
    
                        $bill_product = BillProduct::where('contract_bill_id',$data->id)->get();

                        $bill_total = 0;

                        $bill_grand_tax_total = 0;

                        $bill_grand_total = 0; 

                        foreach ($bill_product as $k=> $prodt)
                        {

                            // $contract_product = ContractProduct::where('id',$prodt->contract_bill_id)->first();

                            $bill_amount_cal = $prodt->billproduct->amount* ($prodt->bill_qty ??1);

                            $bill_total += $bill_amount_cal;

                            $service_tax_percentage =  $prodt->billproduct->tax_percentage??12; 

                            $bill_tax =0;

                            $bill_tax = $bill_amount_cal * ($service_tax_percentage / 100);

                            $bill_tax_total = $bill_amount_cal + $bill_tax;

                            $bill_grand_tax_total += $bill_tax;

                            $bill_grand_total = $bill_total + $bill_grand_tax_total;

                        }

                        return number_format($bill_grand_total, 2) ;
                    })

                    ->addColumn('attachment', function ($data) {
                        $extension = pathinfo($data->attachment, PATHINFO_EXTENSION);
                    
                        if(!empty($data->attachment))
                        {
                            if (strtolower($extension) == 'pdf' || strtolower($extension) == 'xlsx') {
                            
                                return '<a href="' . asset('storage/app/public/bill_attachment/' . $data->attachment) . '" target="_blank"><i class="fa fa-download"></i></a>';
                            } else {
                            
                                return '<img src="' . asset('storage/app/public/bill_attachment/' . $data->attachment) . '" />';
                            }
                        }
                        else
                        {
                            return "";
                        }

                    })

                    ->addColumn('comment', function ($data) {

                        $payment_status = $data->payment_status;
                        
                        $payment_yes = ($payment_status == 'Y') ? 'style="display:none;"' : '';
    
                        $comment = '<textarea name="comment" class="form-control" id="com-' . $data->id . '" rows="4"  readonly>
                                    ' . htmlspecialchars($data->comment) . '
                                    </textarea>';

                        $editButton = '<button type="button" '.$payment_yes.'  class="btn btn-success btn-sm" id="edit-' . $data->id . '" onclick="editComment(' . $data->id . ')"><span class="glyphicon glyphicon-pencil"></span></button>';

                       
                        return $comment . ' ' . $editButton;
                    })

                    
                    ->addColumn('action', function ($data) {

                        $payment_status = $data->payment_status;

                        $payment_yes = ($payment_status == 'Y') ? 'checked disabled' : '';

                        $save_yes = ($payment_status == 'Y') ? 'style="display:none;"' : '';

                        // $payment_no = ($payment_status == 'N') ? 'checked' : '';
    
                        // $button = ' <label for="yes-.'.$data->id.'.">Yes</label>
                        //             <input type="radio" name="payment'.$data->id.'" '.$payment_yes.' data-name="payment'.$data->id.'" id="yes-.'.$data->id.'." value="Y" onchange="PaymentUpdate('.$data->id.',this)"> 
                        //             <label for="no-.'.$data->id.'.">No</label>
                        //             <input type="radio" name="payment'.$data->id.'" '.$payment_no.' data-name="payment'.$data->id.'" id="no-.'.$data->id.'." value="N" onchange="PaymentUpdate('.$data->id.',this)" >';
    
                        $button = ' <label class="switch" for="payment-' . $data->id . '">

                                <input type="checkbox" name="payment" '.$payment_yes.' data-name="payment'.$data->id.'" id="payment-' . $data->id . '" value="Y" >
                                 <span class="slider round"></span>
                                 </label>
                                ';

                        $saveButton = '<button type="button" '.$save_yes.'  class="btn btn-success btn-sm" id="save-' . $data->id . '" onclick="AddComment(' . $data->id . ')">Save</button>';

                        return $button  . ' ' .$saveButton ;
                    })

                    ->rawColumns(['action','attachment','comment'])
                    ->addIndexColumn()

                    ->with(['bill_product' => $bill_product])

                    ->make(true);
            }

        }

        $opperids = SalesContract::pluck('oppertunity_id')->unique()->toArray();
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

        return view('staff.pm_order.salesindex', compact('state', 'district', 'engineers', 'brands', 'categorys'));
    }

    // public function update_payment(Request $request)
    // {
    //     $id = $request->id;

    //     $value = $request->value;

    //     $contract_bill = ContractBill::find($id);

    //     $contract_bill->payment_status = $value;

    //     $contract_bill->save();

    //     return response()->json(['success','payment status updated']);
    // }
    
    public function update_comment(Request $request)
    {
        $id = $request->id;

        $value = $request->value;

        $toggle = $request->toggle;

        $contract_bill = ContractBill::find($id);

        $contract_bill->comment = $value;

        $contract_bill->payment_status = $toggle;

        $contract_bill->save();

        $payment = false;

        if($toggle =='Y')
        {
            $payment = true;
        }

        return response()->json(['success'=>'Comment status updated','payment'=>$payment]);
    }


    public function verify_sales(Request $request)
    {
        $verify_id = $request->verify_id;

        $sale_contract = SalesContract::find($verify_id);

        $sale_contract->contract_status = 'verified';

        $sale_contract->save();

        return response()->json(['success' => 'sales contract verified']);
    }

}



