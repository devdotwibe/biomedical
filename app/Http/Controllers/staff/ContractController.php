<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Product;
use App\MachineStatus;
use App\Staff;
use App\EquipmentStatus;
use App\District;
use App\State;
use App\Company;
use App\Contact_person;
use App\Contract;
use App\Contractowner;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\Oppertunity;
use App\Quotehistory;
use App\Oppertunity_product;




use App\Http\Controllers\Controller;
use App\Ib;
use App\Models\QuoteProduct;
use App\MsaContract;
use App\PmDetails;
use App\Service;
use Carbon\Carbon;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('staff.contract.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user             = User::get();
        $products           = Product::get();
        $equipment_status   = EquipmentStatus::get();
        $machine_status     = MachineStatus::get();  
        $staffs             = Staff::get();  
        $state              = State::get();
        $district           = District::get();
        $company            = Company::get();


        return view('staff.contract.create2',compact('state','company','district','user','products','equipment_status','machine_status','staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function oppertunity_contact($id)
    {
       
        $staff = session('STAFF_ID');

        $contract_permission =CoordinatorPermission::where('staff_id', $staff)->where('type','contract')->first();

        if (optional($contract_permission)->common_edit != 'edit') {

            return redirect()->route('staff.dashboard');
        }

        $msacontract = MsaContract::find($id);

        $oppertunity= Oppertunity::find($msacontract->oppertunity_id);

        $contractOwners = Contractowner::all();

        $equipment_status = EquipmentStatus::all();

        $state = State::orderBy('id', 'asc')->get();

        $district = District::orderBy('id', 'asc')->get();

        $user = User::orderBy('id', 'asc')->get();
     
        $oppertunitys = Oppertunity::orderBy('id', 'asc')->get();

        $iblist = Ib::orderBy('id', 'asc')->get();

        $contactPersons =Contact_person::where('user_id',$oppertunity->user_id)->orderBy('id', 'asc')->get();
    
        $machine_status = MachineStatus::all();

        $contractdata =[];

        $productDetails="";
            
        // if (QuoteProduct::where('quote_id', $oppertunity->quote_close_id)->count() > 0) {

            $quote = Quotehistory::find($msacontract->quote_id);

            if(empty($quote))
            {
                $quote = Quotehistory::find($oppertunity->quote_close_id);

                if(empty($quote))
                {
                    $quote = Quotehistory::where('approved_status','Y')->where('oppertunity_id',$oppertunity->id)->latest()->first();
                }
            }
          
            $opurtunity_product = Oppertunity_product::where('oppertunity_id', $oppertunity->id)->first();

            if($quote->history_status !='new')
            {
                $productId = explode(',', $quote->product_id);

                $no_of_pms = QuoteProduct::where('quote_id', $quote->id)->count();

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
            }
            elseif(!empty($quote))
            {
                $quoteProduct = QuoteProduct::where('quote_id', $quote->id)->get();

                $no_of_pms = QuoteProduct::where('quote_id', $quote->id)->count();

                $quoteIds      = explode(',', $quote->opper_product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
            }

            $opp_type = $oppertunity->company_type;

            $service_type =$oppertunity->service_type;
        // }

        return view('staff.contract.opp_create', compact(
            'oppertunity',
            'contractOwners',
            'equipment_status',
            'state',
            'district',
            'user',
            'oppertunitys',
            'contactPersons',
            'iblist',
            'machine_status',
            'productDetails',
            'quote',
            'service_type',
            'no_of_pms',
            'msacontract'

        ));

    }

    public function view_oppertunity_contact($id)
    {
    
        $staff = session('STAFF_ID');

        $contract_permission =CoordinatorPermission::where('staff_id', $staff)->where('type','contract')->first();

        if (optional($contract_permission)->common_edit != 'edit') {

            return redirect()->route('staff.dashboard');
        }

        $msacontract = MsaContract::find($id);

        $oppertunity= Oppertunity::find($msacontract->oppertunity_id);

        $contract = Contract::where('msa_contract_id',$msacontract->id)->first();

        $service= Service::find($contract->service_id);

        $contractOwners = Contractowner::all();

        $equipment_status = EquipmentStatus::all();

        $state = State::orderBy('id', 'asc')->get();

        $district = District::orderBy('id', 'asc')->get();

        $user = User::orderBy('id', 'asc')->get();
     
        $oppertunitys = Oppertunity::orderBy('id', 'asc')->get();

        $iblist = Ib::orderBy('id', 'asc')->get();

        $contactPersons =Contact_person::find($service->contact_person_id);
    
        $machine_status = MachineStatus::all();

        $contractdata =[];

        $productDetails="";

        $service_type =$oppertunity->service_type;
        
        $productDetails = ContractProduct::where('contract_id',$contract->id)->get();

        $no_of_pms = ContractProduct::where('contract_id',$contract->id)->count();

        $pmdetails =  PmDetails::with('service','contract','equipment','contractproduct')->where('contract_id',$contract->id)->get();

        return view('staff.contract.view_opp_contract', compact(
            'oppertunity',
            'contractOwners',
            'equipment_status',
            'state',
            'district',
            'user',
            'oppertunitys',
            'contactPersons',
            'iblist',
            'machine_status',
            'productDetails',
            'service_type',
            'no_of_pms',
            'msacontract',
            'contract',
            'service',
            'pmdetails'
        ));

    }


    public function oppertunity_storesingle(Request $request)
    {
        $this->validate($request, [
            'contract_type'        => 'required',
            'revanue'              => 'required',
            'contract_start_date'  => 'required',
            'contract_end_date'    => 'required',
            'no_of_pm'             => 'required',
            'attachment'           => 'mimes:jpeg,png,jpg,pdf|max:1024',
        ],
        [
            'attachment.max'  => 'Maximum size 1MB!',
            'attachment.mimes'  => 'jpeg,png,jpg,pdf mimes supported!',  
        ]);

     
        $contractProducts = [];

        $equpment = [];

        foreach ($request->equipment_id as $k => $equipment_id) {

            $equpment[] = (object) [
                "equipment_id" => $equipment_id,
                "ib_id" => $request->ib_id[$k] ?? null,
                "equipment_serial_no" => $request->equipment_serial_no[$k] ?? null,
                "equipment_status_id" => $request->equipment_status_id[$k] ?? null,
                "machine_status_id" => $request->machine_status_id[$k] ?? null,
                "warraty_expiry_date" => $request->warraty_expiry_date[$k] ?? null,
                "under_contract" => $request->under_contract[$k] ?? null,
                "equipment_model_no" => $request->equipment_model_no[$k] ?? null,
                "supplay_order" => $request->supplay_order[$k] ?? null,
                "installation_date" => $request->installation_date[$k] ?? null,
                "contract_type" => $request->contract_type[$k] ?? null,
                "in_ref_no" => $request->in_ref_no[$k] ?? null,
                "amount" => $request->amount[$k] ?? null,
                "tax" => $request->tax[$k] ?? null,
                "amount_tax" => $request->amount_tax[$k] ?? null,
                "revanue" => $request->revanue[$k] ?? null,
                "contract_start_date" => $request->contract_start_date[$k] ?? null,
                "contract_end_date" => $request->contract_end_date[$k] ?? null,
                "no_of_pm" => $request->no_of_pm[$k] ?? null,
                "pm_dates" => $request->pm_dates[$k] ?? [],
            ];

        }

        $imageName ="";
        if($request->hasFile('attachment'))
        {
           
            $file = $request->file('attachment');
            $imageName = time() . '_' . preg_replace("/[^a-z0-9\_\-\.]/i", '', $file->getClientOriginalName());
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);            
            $file->move('public/storage/attachments',$imageName);
           
        }

        foreach ($equpment as $k => $equip) {

            $service = new Service;
            $service->service_type = 2;
            $service->user_id = $request->user_id;
            $service->state_id = optional(User::find($request->user_id))->state_id;
            $service->district_id = optional(User::find($request->user_id))->district_id;
            $service->contact_person_id = $request->contact_person_id;
            $service->equipment_id = $equip->equipment_id;
            $service->equipment_serial_no = $equip->equipment_serial_no;
            $service->equipment_status_id = $equip->equipment_status_id;
            $service->machine_status_id = $equip->machine_status_id;
            $service->engineer_id = 0;
            $service->save();

            $contract = new Contract;
            $contract->warraty_expiry_date = date('Y:m:d', strtotime($equip->warraty_expiry_date));
            $contract->under_contract = $equip->under_contract;
            $contract->equipment_model_no = $equip->equipment_model_no;
            $contract->supplay_order = $equip->supplay_order;
            $contract->installation_date = $equip->installation_date;
            $contract->contract_type = $equip->contract_type;
            $contract->in_ref_no = $equip->in_ref_no;
            $contract->amount = $equip->amount;
            $contract->tax = $equip->tax;
            $contract->amount_tax = $equip->amount_tax;
            $contract->revanue = $equip->revanue;
            $contract->oppertunity_id = $request->oppertunity_id;
            $contract->ib_id = $equip->ib_id;
            $contract->contract_start_date = date('Y:m:d', strtotime($equip->contract_start_date));
            $contract->contract_end_date = date('Y:m:d', strtotime($equip->contract_end_date));
            $contract->no_of_pm = $equip->no_of_pm;
            $contract->service_id = $service->id;
            $pmDatesJson = json_encode($equip->pm_dates);
            $contract->pm_dates = $pmDatesJson;
    
            $contract->contract_reference = $request->contract_reference;
            $contract->payment_reference = $request->payment_reference;
            $contract->payment_amount = $request->payment_amount;
            $contract->tds = $request->tds;

            $contract->msa_contract_id = $request->msa_contract_id;
        
            $contract->attachment = $imageName;

            $contract->save();

            $pmDatesJson = json_encode($equip->pm_dates);
            $contractProduct = new ContractProduct;
            $contractProduct->contract_id = $contract->id;
            $contractProduct->service_id = $service->id;
            $contractProduct->oppertunity_id = $request->oppertunity_id;
            $contractProduct->equipment_id = $equip->equipment_id;
            $contractProduct->ib_id = $equip->ib_id;
            $contractProduct->equipment_serial_no = $equip->equipment_serial_no;
            $contractProduct->equipment_model_no = $equip->equipment_model_no;
            $contractProduct->under_contract = $equip->under_contract;
            $contractProduct->supplay_order = $equip->supplay_order;
            $contractProduct->installation_date = $equip->installation_date;
            $contractProduct->warraty_expiry_date = $equip->warraty_expiry_date;
            $contractProduct->equipment_qty = $request->equipment_qty[$k] ?? null;
            $contractProduct->equipment_amount = $request->equipment_amount[$k] ?? null;
            $contractProduct->equipment_tax = $request->equipment_tax[$k] ?? null;
            $contractProduct->tax_percentage = $request->tax_percentage[$k] ?? null;
            $contractProduct->equipment_total = $request->equipment_total[$k] ?? null;
            $contractProduct->contract_type = $equip->contract_type;
            $contractProduct->in_ref_no = $equip->in_ref_no;
            $contractProduct->amount = $equip->amount;
            $contractProduct->tax = $equip->tax;
            $contractProduct->amount_tax = $equip->amount_tax;
            $contractProduct->machine_status_id = $equip->machine_status_id;
            $contractProduct->revanue = $equip->revanue;
            $contractProduct->contract_start_date = $equip->contract_start_date;
            $contractProduct->contract_end_date = $equip->contract_end_date;
            $contractProduct->no_of_pm = $equip->no_of_pm;
            $contractProduct->pm_dates = $pmDatesJson;

            $contractProduct->save();
        }
        
        $msacontract = MsaContract::find($request->msa_contract_id);
        $msacontract->contract_status = "created";
        $msacontract->save();

        $request->session()->flash('success', 'Oppertunity added Successfully');
        return redirect()->route('staff.service-create',2); 
    }


    public function oppertunity_store(Request $request)
    {
            $staff = session('STAFF_ID');

            $this->validate($request, [
                'contract_type'        => 'required',
                'revanue'              => 'required',
                'contract_start_date'  => 'required',
                'contract_end_date'    => 'required',
                'no_of_pm'             => 'required',
                // 'attachment'    => 'mimes:jpeg,png,jpg,pdf|max:1024',
            ],
            [
                // 'attachment.max'  => 'Maximum size 1MB!',
                // 'attachment.mimes'  => 'jpeg,png,jpg,pdf mimes supported!',  
            ]);


           

        $equpment = (object) [
            "equipment_id" => $request->equipment_id[0] ?? null,
            "ib_id" => $request->ib_id[0] ?? null,
            "in_ref_no" => $request->internal_ref_no ?? null,
            "equipment_serial_no" => $request->equipment_serial_no[0] ?? null,
            "equipment_status_id" => $request->equipment_status_id[0] ?? null,
            "machine_status_id" => $request->machine_status_id[0] ?? null,
            "warraty_expiry_date" => $request->warraty_expiry_date[0] ?? null,
            "under_contract" => $request->under_contract[0] ?? null,
            "equipment_model_no" => $request->equipment_model_no[0] ?? null,
            "supplay_order" => $request->supplay_order[0] ?? null,
            "installation_date" => $request->installation_date[0] ? Carbon::parse($request->installation_date[0])->format('Y-m-d') : null,
            "contract_type" => $request->contract_type ?? null,
            "amount" => $request->amount[0] ?? null,
            "tax" => $request->tax[0] ?? null,
            "amount_tax" => $request->amount_tax[0] ?? null,
            "revanue" => $request->revanue[0] ?? null,
            "contract_start_date" => $request->contract_start_date ? Carbon::parse($request->contract_start_date)->format('Y-m-d') : null,
            "contract_end_date" => $request->contract_end_date ? Carbon::parse($request->contract_end_date)->format('Y-m-d') : null,
            "no_of_pm" => $request->no_of_pm[0] ?? null,
            "pm_dates" => $request->pm_dates[0] ?? [],
        ];
    
        $user= User::find($request->user_id);
        $service = new Service;
        $service->service_type = 2;
        $service->user_id = $request->user_id;
        $service->state_id = $request->state_id??optional($user)->state_id;
        $service->district_id = $request->district_id??optional($user)->district_id;
        $service->contact_person_id = $request->contact_person_id;
        $service->equipment_id = $equpment->equipment_id;
        $service->equipment_serial_no = $equpment->equipment_serial_no;
        $service->equipment_status_id = $equpment->equipment_status_id;
        $service->machine_status_id = $equpment->machine_status_id;
        $service->engineer_id = 0;
        $service->created_by = $staff;
        $service->save();


        $contract = new Contract;
        $contract->warraty_expiry_date = $equpment->warraty_expiry_date;
        $contract->under_contract = $equpment->under_contract;
        $contract->equipment_model_no = $equpment->equipment_model_no;
        $contract->supplay_order = $equpment->supplay_order;
        $contract->installation_date = $equpment->installation_date;
        $contract->contract_type = $equpment->contract_type;
        $contract->amount = $equpment->amount;
        $contract->tax = $equpment->tax;
        $contract->amount_tax = $equpment->amount_tax;
        $contract->revanue = $equpment->revanue;
        $contract->oppertunity_id = $request->oppertunity_id;
        $contract->ib_id = $equpment->ib_id;
        $contract->in_ref_no = $equpment->in_ref_no;
        $contract->contract_start_date = $equpment->contract_start_date;
        $contract->contract_end_date = $equpment->contract_end_date;
        $contract->no_of_pm = $equpment->no_of_pm;
        $contract->service_id = $service->id;
        
        $datesArray = $equpment->pm_dates;

        $formattedDates = array_map(function($date) {

            return Carbon::parse($date)->format('Y-m-d');
        }, $datesArray);

        $pmDatesJson = json_encode($formattedDates);

        $contract->pm_dates = $pmDatesJson;                    


        $contract->contract_reference = $request->contract_reference;
        $contract->payment_reference = $request->payment_reference;  
        $contract->payment_amount = $request->payment_amount;
        $contract->tds = $request->tds;

        $contract->msa_contract_id = $request->msa_contract_id;

        $contract->customer_order = $request->customer_order;

        if($request->hasFile('attachment'))
        {
            $file = $request->file('attachment');
            // dd($file);
            $imageName = time().$request->attachment->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);            
            $file->move('public/storage/attachments',$imageName);
        }

        $contract->attachment = isset($imageName) ? $imageName: '';

        $contract->save();

        $msacontract = MsaContract::find($request->msa_contract_id);

        $msacontract->service_id = $service->id;

        $msacontract->contract_id =$contract->id;

        $msacontract->contract_status = "created";
        $msacontract->save();


            $equipment = [];
            foreach ($request->equipment_id as $k => $equipment_id) {
                $equipment[] = [
                    "equipment_id" => $equipment_id,
                    "ib_id" => $request->ib_id[$k] ?? null,
                    "in_ref_no" => $request->in_ref_no[$k] ?? null,
                    "equipment_serial_no" => $request->equipment_serial_no[$k] ?? null,
                    "equipment_model_no" => $request->equipment_model_no[$k] ?? null,
                    "under_contract" => $request->under_contract[$k] ?? null,
                    "supplay_order" => $request->supplay_order[$k] ?? null,
                    "installation_date" => $request->installation_date[$k] ? Carbon::parse($request->installation_date[$k])->format('Y-m-d') : null,
                    "warraty_expiry_date" => $request->warraty_expiry_date[$k] ?? null,
                    "equipment_qty" => $request->equipment_qty[$k] ?? null,
                    "equipment_amount" => $request->equipment_amount[$k] ?? null,
                    "equipment_tax" => $request->equipment_tax[$k] ?? null,
                    "tax_percentage" => $request->tax_percentage[$k] ?? null,
                    "equipment_total" => $request->equipment_total[$k] ?? null,
                    "contract_type" => $request->contract_type ?? null,
                    "amount" => $request->amount[$k] ?? null,
                    "tax" => $request->tax[$k] ?? null,
                    "amount_tax" => $request->amount_tax[$k] ?? null,
                    "machine_status_id" => $request->machine_status_id[$k] ?? null,
                    "revanue" => $request->revanue[$k] ?? null,
                    "contract_start_date" => $request->contract_start_date ? Carbon::parse($request->contract_start_date)->format('Y-m-d') : null,
                    "contract_end_date" => $request->contract_end_date ? Carbon::parse($request->contract_end_date)->format('Y-m-d') : null,
                    "no_of_pm" => $request->no_of_pm[$k] ?? null,
                    "pm_dates" => $request->pm_dates[$k] ?? [],

                ];
            }


            foreach ($equipment as $k=>$equip) {

                $datesArray = $equip['pm_dates'];

                $formattedDates = array_map(function($date) {

                    return Carbon::parse($date)->format('Y-m-d');
                }, $datesArray);

                $pmDatesJson = json_encode($formattedDates);

                $contractProduct = new ContractProduct;
                $contractProduct->contract_id = $contract->id;
                $contractProduct->service_id = $service->id;
                $contractProduct->oppertunity_id = $request->oppertunity_id;
                $contractProduct->equipment_id = $equip['equipment_id'];
                $contractProduct->ib_id = $equip['ib_id'];
                $contractProduct->equipment_serial_no = $equip['equipment_serial_no'];
                $contractProduct->equipment_model_no = $equip['equipment_model_no'];
                $contractProduct->under_contract = $equip['under_contract'];
                $contractProduct->supplay_order = $equip['supplay_order'];
                $contractProduct->installation_date = $equip['installation_date'];
                $contractProduct->warraty_expiry_date = $equip['warraty_expiry_date'];
                $contractProduct->equipment_qty = $equip['equipment_qty'];
                $contractProduct->equipment_amount = $equip['equipment_amount'];
                $contractProduct->equipment_tax = $equip['equipment_tax'];
                $contractProduct->tax_percentage = $equip['tax_percentage'];
                $contractProduct->equipment_total = $equip['equipment_total'];
                $contractProduct->contract_type = $equip['contract_type'];
                $contractProduct->in_ref_no = $equip['in_ref_no'];
                $contractProduct->amount = $equip['amount'];
                $contractProduct->tax = $equip['tax'];
                $contractProduct->amount_tax = $equip['amount_tax'];
                $contractProduct->machine_status_id = $equip['machine_status_id'];
                $contractProduct->revanue = $equip['revanue'];
                $contractProduct->contract_start_date = $equip['contract_start_date'];
                $contractProduct->contract_end_date = $equip['contract_end_date'];
                $contractProduct->no_of_pm = $equip['no_of_pm'];
                $contractProduct->pm_dates = $pmDatesJson;

                $contractProduct->save();

                $datesArray =$equip['pm_dates'];

                $formattedDates = array_map(function($date) {

                    return Carbon::parse($date)->format('Y-m-d');
                }, $datesArray);

                $pmDatesJson = $formattedDates;

                foreach ($pmDatesJson as $index => $pm) {

                    $pmDetails=new PmDetails;

                    $pmDetails->visiting_date=$pm;

                    $pmDetails->service_id= $service->id;

                    $pmDetails->contract_id= $contract->id;
                    
                    $pmDetails->equipment_id= $equip['equipment_id'];

                    $pmDetails->contract_equipment_id = $contractProduct->id;
                    
                    $pmDetails->pm = "PM" . ($index + 1);

                    $pmDetails->in_ref_no = 'pm' . rand(1000, 100000);

                    $pmDetails->save();
                }	


            }


        $request->session()->flash('success', 'Oppertunity added Successfully');

        return redirect()->route('staff.pm_create',2);

    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        
    }

    /**
     * Add product through json
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function contractProduct(Request $request)
    {
        $product = Product::where('id',$request->product_id)->first();
        return response()->json([ 'product' => $product ]);
    }

    public function contractOppertunity(Request $request)
    {
        $oppertunities = Oppertunity::where('user_id',$request->user_id)->where('type',2)->get();
        //print_r($oppertunities);
        return response()->json([ 'oppertunities' => $oppertunities ]);
    }
    public function contractOppertunityQuote(Request $request)
    {
        $quotes = Quotehistory::where('oppertunity_id',$request->oppertunity_id)->get();
        //print_r($quotes);
        return response()->json([ 'quotes' => $quotes ]);
    }
    
    public function contractOppertunityQuoteProduct(Request $request)
    {
        $quoteHistory = Quotehistory::where('id',$request->quote_id)->first();
        $quoteProducts = explode(',',$quoteHistory->product_id);
        $oppProducts = Oppertunity_product::with('oppertunityProductIb','oppertunityProduct')->where('oppertunity_id',$quoteHistory->oppertunity_id)->whereIn('product_id',$quoteProducts)->get();
       // print_r($oppProucts);
        return response()->json([ 'oppProducts' => $oppProducts ]);
    }

    public function getequpmentdetails(Request $request){
        $equpments=[];
        if(!empty( $request->user_id)&&!empty($request->equpment_id)){
            if(is_array($request->equpment_id)){
                foreach($request->equpment_id as $IbID){
                    $ibs=Ib::where('user_id', $request->user_id)->where('equipment_id',$IbID);
                    if(!empty($request->oppertunity_id))
                    {
                        $userId=$request->user_id;
                        $opportunityId=$request->oppertunity_id;
                        $ibs->whereIn('id',Oppertunity_product::where('product_id',$IbID)->where('oppertunity_id', $opportunityId)->whereHas('oppertunity',function($qry)use($userId){ $qry->where('user_id',$userId); })->select('ib_id'));
                        $equpments[$IbID]=[];
                        foreach ($ibs->get() as $ib) {
                            $ib->oppertunityProduct=Oppertunity_product::with('oppertunity')->where('product_id',$IbID)->where('oppertunity_id', $opportunityId)->whereHas('oppertunity',function($qry)use($userId){ $qry->where('user_id',$userId); })->where('ib_id',$ib->id)->first();
                            $equpments[$IbID][]=$ib;
                        }
                    }else{
                        $equpments[$IbID] = $ibs->get();
                    }
                }
            }else{

                $ibs=Ib::where('user_id', $request->user_id)->where('equipment_id',$request->equpment_id);
                if(!empty($request->oppertunity_id))
                {
                    $userId=$request->user_id;
                    $opportunityId=$request->oppertunity_id;
                    $ibs->whereIn('id',Oppertunity_product::where('product_id',$request->equpment_id)->where('oppertunity_id', $opportunityId)->whereHas('oppertunity',function($qry)use($userId){ $qry->where('user_id',$userId); })->select('ib_id'));
                    $equpments[$request->equpment_id]=[];
                    foreach ($ibs->get() as $ib) {
                        $ib->oppertunityProduct=Oppertunity_product::with('oppertunity')->where('product_id',$request->equpment_id)->where('oppertunity_id', $opportunityId)->whereHas('oppertunity',function($qry)use($userId){ $qry->where('user_id',$userId); })->where('ib_id',$ib->id)->first();
                        $equpments[$request->equpment_id][]=$ib;
                    }
                } else{
                    $equpments[$request->equpment_id] = $ibs->get();
                }
                $equpments[$request->equpment_id] =  $ibs->get();
            }
        }
        echo json_encode($equpments);
    }


    public function getOpportunitiesByUser(Request $request)
    {
        $userId = $request->input('user_id');

        $opportunities = Oppertunity::where('user_id', $userId)
            ->whereHas('oppertunityOppertunityProduct',function($iqry)use($userId){
                $iqry->whereNotIn('id',Oppertunity_product::join('contract_products','contract_products.oppertunity_id','oppertunity_products.oppertunity_id')->whereHas('oppertunity',function($oiq)use($userId){
                    $oiq->where('user_id',$userId);
                })->select('oppertunity_products.id'));
            })
            ->whereNotNull('won_date')
            ->whereIn('deal_stage', [6, 7, 8])
            ->where('type', 2)
            ->select('id', 'oppertunity_name')
            ->get();


        return response()->json($opportunities);
    }

    public function getProductsByOpportunity(Request $request)
    {

        $userId = $request->input('user_id');
        $opportunityId = $request->input('opportunity_id');


        $query = Product::whereIn('id',Ib::where('user_id',$userId)->select('equipment_id'));




        if (!empty($opportunityId)) {
            
            $query->whereIn('id', Oppertunity_product::where('oppertunity_id', $opportunityId)->whereHas('oppertunity',function($qry)use($userId){ $qry->where('user_id',$userId); })->whereNotIn('id',Oppertunity_product::join('contract_products','contract_products.oppertunity_id','oppertunity_products.oppertunity_id')->where('oppertunity_products.oppertunity_id', $opportunityId)->whereHas('oppertunity',function($oiq)use($userId){
                $oiq->where('user_id',$userId);
            })->select('oppertunity_products.id'))->select('product_id'));
        }

        $products = $query->select('id', 'name')->get();

        return response()->json($products);
    }

}




 