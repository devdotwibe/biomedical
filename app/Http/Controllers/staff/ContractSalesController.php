<?php

namespace App\Http\Controllers\staff;

use App\BillProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
use App\ContractBill;
use App\Contractowner;
use App\ContractProduct;
use App\Oppertunity;
use App\Quotehistory;
use App\Oppertunity_product;
use App\Http\Controllers\Controller;
use App\Ib;
use App\Models\OppertunityOrder;
use App\Models\QuoteProduct;
use App\PmDetails;
use App\SalesContract;
use App\Service;
use Carbon\Carbon;

class ContractSalesController extends Controller
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function oppertunity_contactsales($id)
    {
        // $contract        = Contract::find($id);

        $salescontract = SalesContract::find($id);

        $oppertunity = Oppertunity::find($salescontract->oppertunity_id);

        $contractOwners = Contractowner::all();

        $equipment_status = EquipmentStatus::all();

        $state = State::orderBy('id', 'asc')->get();

        $district = District::orderBy('id', 'asc')->get();

        $user = User::orderBy('id', 'asc')->get();

        $oppertunitys = Oppertunity::orderBy('id', 'asc')->get();

        $iblist = Ib::orderBy('id', 'asc')->get();

        $contactPersons = Contact_person::where('user_id', $oppertunity->user_id)->orderBy('id', 'asc')->get();

        $machine_status = MachineStatus::all();

        $orders = OppertunityOrder::find($salescontract->opp_order_id);


        $contractdata = [];

        $productDetails = "";

        if (QuoteProduct::where('quote_id', $salescontract->quote_id)->count() > 0) {

            $quote = Quotehistory::find($salescontract->quote_id);

            $quoteProduct = QuoteProduct::where('quote_id', $salescontract->quote_id)->get();

            $productId = explode(',', $quote->product_id);

            $productDetails = QuoteProduct::where('quote_id',$quote->id)->where('oppertunity_id', $quote->oppertunity_id)->whereNull('addon_ptd')->get();

            if ($oppertunity->company_type == 5) {
                $opp_type = 'bio';
            } else {
                $opp_type = 'bec';
            }

            $service_type = $oppertunity->service_type;
        }
        elseif(QuoteProduct::where('quote_id', $oppertunity->quote_close_id)->count() > 0)
        {
            $quote = Quotehistory::find($oppertunity->quote_close_id);

            $quoteProduct = QuoteProduct::where('quote_id', $oppertunity->quote_close_id)->get();

            $opurtunity_product = Oppertunity_product::where('oppertunity_id', $oppertunity->id)->first();

            $productId = explode(',', $quote->product_id);

            $productDetails = Oppertunity_product::where('oppertunity_id', $quote->oppertunity_id)->whereNull('addon_ptd')->whereIn('product_id', $productId)->get();

            if ($oppertunity->company_type == 5) {
                $opp_type = 'bio';
            } else {
                $opp_type = 'bec';
            }

            $service_type = $oppertunity->service_type;
        }

        return view('staff.contractsales.sales_create', compact(
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
            'salescontract',
            'orders',
            'opp_type'

        ));

    } 

    public function view_sales_contract($id)
    {
        // $contract        = Contract::find($id);

        $salescontract = SalesContract::find($id);

        $sales_service = Service::find($salescontract->service_id);

        $oppertunity = Oppertunity::find($salescontract->oppertunity_id);

        $contract = Contract::find($salescontract->contract_id);

        $equipment_status = EquipmentStatus::all();

        $state = State::orderBy('id', 'asc')->get();

        $district = District::orderBy('id', 'asc')->get();

        $user = User::orderBy('id', 'asc')->get();

        $oppertunitys = Oppertunity::orderBy('id', 'asc')->get();

        $iblist = Ib::orderBy('id', 'asc')->get();

        $contactPersons = Contact_person::where('user_id', $oppertunity->user_id)->orderBy('id', 'asc')->get();

        $machine_status = MachineStatus::all();

        $orders = OppertunityOrder::find($salescontract->opp_order_id);


        $contractdata = [];

        $productDetails = ContractProduct::where('contract_id', $contract->id)
            // ->where(function ($query) {
            //     $query->where('addon_ptd', '!=', 1)
            //         ->orWhereNull('addon_ptd');
            // })
            ->get();

        $bill_contract = ContractBill::where('bill_contract_id',$contract->id)->get();

        $service_type = $oppertunity->service_type;


        return view('staff.contractsales.sales_view', compact(
            'contract',
            'sales_service',
            'oppertunity',
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
            'salescontract',
            'orders',
            'bill_contract'

        ));

    }

    public function fetch_optional_products(Request $request)
    {

        $main_products = $request->input('product_ids');

        if (!is_array($main_products)) {
            return response()->json(['error' => 'Invalid product_ids'], 400);
        }

        $optional_products = ContractProduct::whereIn('id', $main_products)->get();

        // foreach ($optional_products as $item) {
        //     $item->product_name = optional($item->equipment)->name;
        // }

        $bill_products_data = collect();

        foreach ($optional_products as $item) {

            // $item->product_name = optional($item->equipment)->name;

            $bill_product = BillProduct::with('billproduct')->where('contract_product_id',$item->id)->latest()->first();

            $bill_product_qty = BillProduct::where('contract_product_id',$item->id)->sum('bill_qty');

            $bill_products = new \stdClass();

            if(empty($bill_product))
            {
                $bill_products->contract_id = $item->id;

                $bill_products->equipment_name = $item->equipment->name;

                $bill_products->bill_amount = $item->amount;
    
                $bill_products->equipment_qty = $item->equipment_qty;
    
                $bill_products->bill_qty = $item->bill_qty;

                $bill_products->bill_created = false;

                $bill_products->balance_qty = $item->equipment_qty;

                $bill_products->tax = $item->tax_percentage;

            }
            else
            {   
                $bill_products->contract_id = $item->id;

                $bill_products->equipment_name = $bill_product->equip_name;

                $bill_products->bill_amount = $item->amount;
    
                $bill_products->equipment_qty = $bill_product->order_qty;
    
                $bill_products->bill_qty = $bill_product->bill_qty;

                $bill_products->balance_qty = $bill_product->order_qty - $bill_product_qty;

                $bill_products->bill_created = true;

                $bill_products->tax = $bill_product->billproduct->tax_percentage;

            }

            $bill_products_data->push($bill_products);
        
        }

        return response()->json($bill_products_data);
    }

    public function oppertunity_storesales(Request $request)
    {
        $staff = session('STAFF_ID');

        // $this->validate(
        //     $request,
        //     [
        //         // 'contract_type'        => 'required',
        //         // 'revanue' => 'required',
        //         // 'contract_start_date'  => 'required',
        //         // 'contract_end_date'    => 'required',
        //         // 'no_of_pm'             => 'required',
        //         'attachment' => 'mimes:jpeg,png,jpg,pdf|max:1024',
        //     ],
        //     [
        //         'attachment.max' => 'Maximum size 1MB!',
        //         'attachment.mimes' => 'jpeg,png,jpg,pdf mimes supported!',
        //     ]
        // );


        $equpment = (object) [
            // "equipment_id" => $request->equipment_id[0] ?? null,
            // "ib_id" => $request->ib_id[0] ?? null,
            "in_ref_no" => $request->internal_ref_no ?? null,
            // "equipment_serial_no" => $request->equipment_serial_no[0] ?? null,
            // "equipment_status_id" => $request->equipment_status_id[0] ?? null,
            // "machine_status_id" => $request->machine_status_id[0] ?? null,
            "warraty_expiry_date" => $request->warraty_expiry_date[0] ?? null,
            "under_contract" => $request->under_contract[0] ?? null,
            "equipment_model_no" => $request->equipment_model_no[0] ?? null,
            "supplay_order" => $request->supplay_order[0] ?? null,
            "installation_date" => $request->installation_date[0] ?? null,
            "contract_type" => $request->contract_type ?? null,
            "amount" => $request->amount[0] ?? null,
            "tax" => $request->taxper[0] ?? null,
            "amount_tax" => $request->amount_tax[0] ?? null,
            "revanue" => $request->revanue[0] ?? null,
            "contract_start_date" => $request->contract_start_date ?? null,
            "contract_end_date" => $request->contract_end_date ?? null,
            // "no_of_pm" => $request->no_of_pm[0] ?? null,
            // "pm_dates" => $request->pm_dates[0] ?? [],
        ];


        $user = User::find($request->user_id);
        $service = new Service;
        $service->service_type = 2;
        $service->user_id = $request->user_id;
        $service->state_id = $request->state_id ?? optional($user)->state_id;
        $service->district_id = $request->district_id ?? optional($user)->district_id;
        $service->contact_person_id = $request->contact_person_id;
        // $service->equipment_id = $equpment->equipment_id;
        // $service->equipment_serial_no = $equpment->equipment_serial_no;
        // $service->equipment_status_id = $equpment->equipment_status_id;
        // $service->machine_status_id = $equpment->machine_status_id;
        $service->engineer_id = 0;
        $service->created_by = $staff;
        $service->save();


        $contract = new Contract;
        $contract->warraty_expiry_date = $equpment->warraty_expiry_date ? Carbon::parse($equpment->warraty_expiry_date)->format('d-m-y') : '';
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
        // $contract->ib_id = $equpment->ib_id;
        $contract->in_ref_no = $equpment->in_ref_no;
        $contract->contract_start_date = $equpment->contract_start_date ? Carbon::parse($equpment->contract_start_date)->format('d-m-y') : '';

        $contract->contract_end_date = $equpment->contract_end_date ? Carbon::parse($equpment->contract_end_date)->format('d-m-y') : '';
        // $contract->no_of_pm = $equpment->no_of_pm;
        $contract->service_id = $service->id;
        // $pmDatesJson = json_encode($equpment->pm_dates);
        // $contract->pm_dates = $pmDatesJson;                    


        $contract->contract_reference = $request->contract_reference;
        $contract->payment_reference = $request->payment_reference;
        $contract->payment_amount = $request->payment_amount;
        $contract->tds = $request->tds;

        $contract->sales_contract_id = $request->sales_contract_id;

        $contract->customer_order = $request->customer_order;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $imageName = time() . $request->attachment->getClientOriginalName();
            $imageName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $file->move('public/storage/attachments', $imageName);
        }

        $contract->attachment = isset($imageName) ? $imageName : '';

        $contract->save();


        $salescontract = SalesContract::find($request->sales_contract_id);
        $salescontract->contract_id = $contract->id;
        $salescontract->service_id = $service->id;
        $salescontract->contract_status = "saved";

        $salescontract->save();

        $order = OppertunityOrder::find($salescontract->opp_order_id);
        $order->order_no = $request->order_no;
        $order->order_date = $request->order_date;
        $order->order_recive_date = $request->order_recive_date;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date ? Carbon::parse($request->delivery_date)->format('Y-m-d') : '';
        $order->warrenty_terms = $request->warrenty_terms;
        $order->supplay = $request->supplay;
        $order->remark = $request->remark;
        $order->save();



        $equipment = [];

        foreach ($request->equipment_id as $k => $equipment_id) {

            $equipment[] = [
                "equipment_id" => $equipment_id,
                // "ib_id" => $request->ib_id[$k] ?? null,
                "in_ref_no" => $request->in_ref_no[$k] ?? null,
                "equipment_serial_no" => $request->equipment_serial_no[$k] ?? null,
                "equipment_model_no" => $request->equipment_model_no[$k] ?? null,
                "under_contract" => $request->under_contract[$k] ?? null,
                "supplay_order" => $request->supplay_order[$k] ?? null,
                "installation_date" => $request->installation_date[$k] ?? null,
                "warraty_expiry_date" => $request->warraty_expiry_date[$k] ?? null,
                "equipment_qty" => $request->equipment_qty[$k] ?? null,
                "equipment_amount" => $request->equipment_amount[$k] ?? null,
                "equipment_tax" => $request->equipment_tax[$k] ?? null,
                "tax_percentage" => $request->taxper[$k] ?? null,
                "equipment_total" => $request->equipment_total[$k] ?? null,
                "contract_type" => $request->contract_type ?? null,
                "amount" => $request->amount[$k] ?? null,
                "tax" => $request->tax[$k] ?? null,
                "amount_tax" => $request->amount_tax[$k] ?? null,
                "machine_status_id" => $request->machine_status_id[$k] ?? null,
                "revanue" => $request->revanue[$k] ?? null,
                "contract_start_date" => $request->contract_start_date ?? null,
                "contract_end_date" => $request->contract_end_date ?? null,

                "opp_product_id" => $request->opp_product_id[$k] ?? null,

                "msp_no" => $request->msp_no[$k] ?? null,

                "rate" => $request->rate[$k] ?? null,

                "margin_amount" => $request->margin_amount[$k] ?? null,

                // // "no_of_pm" => $request->no_of_pm[$k] ?? null,
                // "pm_dates" => $request->pm_dates[$k] ?? [],

            ];
        }

        foreach ($equipment as $k => $equip) {

            // $opper_product = Oppertunity_product::where('main_product_id', $equip['opp_product_id'])->where('addon_ptd', 1)->get();

            $main_contractProduct = new ContractProduct;

            $main_contractProduct->contract_id = $contract->id;
            $main_contractProduct->service_id = $service->id;
            $main_contractProduct->oppertunity_id = $request->oppertunity_id;
            $main_contractProduct->equipment_id = $equip['equipment_id'];
            // $main_contractProduct->ib_id = $equip['ib_id'];
            $main_contractProduct->equipment_serial_no = $equip['equipment_serial_no'];
            $main_contractProduct->equipment_model_no = $equip['equipment_model_no'];
            $main_contractProduct->under_contract = $equip['under_contract'];
            $main_contractProduct->supplay_order = $equip['supplay_order'];
            $main_contractProduct->installation_date = $equip['installation_date'];
            $main_contractProduct->warraty_expiry_date = $equip['warraty_expiry_date'];
            $main_contractProduct->equipment_qty = $equip['equipment_qty'];
            $main_contractProduct->equipment_amount = $equip['equipment_amount'];
            $main_contractProduct->equipment_tax = $equip['equipment_tax'];
            $main_contractProduct->tax_percentage = $equip['tax_percentage'];
            $main_contractProduct->equipment_total = $equip['equipment_total'];
            $main_contractProduct->contract_type = $equip['contract_type'];
            $main_contractProduct->in_ref_no = $equip['in_ref_no'];
            $main_contractProduct->amount = $equip['amount'];
            $main_contractProduct->tax = $equip['tax'];
            $main_contractProduct->amount_tax = $equip['amount_tax'];

            $main_contractProduct->msp_no = $equip['msp_no'];

            $main_contractProduct->rate = $equip['rate'];

            $main_contractProduct->margin_amount = $equip['margin_amount'];
            $main_contractProduct->addon_ptd = 0;

            $main_contractProduct->save();


            $equipmentop = [];

        if(!empty($request->equipment_idop) && count($request->equipment_idop) > 0)
        {

            foreach ($request->equipment_idop as $k => $equipment_idop) {

                $equipmentop[] = [
                    "equipment_id" => $equipment_idop,
                    // "ib_id" => $request->ib_id[$k] ?? null,
                    "in_ref_no" => $request->in_ref_no[$k] ?? null,
                    "equipment_serial_no" => $request->equipment_serial_noop[$k] ?? null,
                    "equipment_model_no" => $request->equipment_model_noop[$k] ?? null,
                    "under_contract" => $request->under_contractop[$k] ?? null,
                    "supplay_order" => $request->supplay_orderop[$k] ?? null,
                    "installation_date" => $request->installation_dateop[$k] ?? null,
                    "warraty_expiry_date" => $request->warraty_expiry_dateop[$k] ?? null,
                    "equipment_qty" => $request->equipment_qtyop[$k] ?? null,
                    "equipment_amount" => $request->equipment_amountop[$k] ?? null,
                    "equipment_tax" => $request->equipment_taxop[$k] ?? null,
                    "tax_percentage" => $request->taxperop[$k] ?? null,
                    "equipment_total" => $request->equipment_totalop[$k] ?? null,
                    "contract_type" => $request->contract_type ?? null,
                    "amount" => $request->amountop[$k] ?? null,
                    "tax" => $request->taxop[$k] ?? null,
                    "amount_tax" => $request->amount_taxop[$k] ?? null,
                    "machine_status_id" => $request->machine_status_idop[$k] ?? null,
                    "revanue" => $request->revanueop[$k] ?? null,
                    "contract_start_date" => $request->contract_start_date ?? null,
                    "contract_end_date" => $request->contract_end_date ?? null,

                    "opp_product_id" => $request->opp_product_id[$k] ?? null,

                    "msp_no" => $request->msp_noop[$k] ?? null,

                    "rate" => $request->rate_op[$k] ?? null,

                    "margin_amount" => $request->margin_amountop[$k] ?? null,
                ];
            }

            foreach ($equipmentop as $k => $equipop) {

                if ($equipop['opp_product_id'] == $equip['opp_product_id']) {

                    $op_contractProduct = new ContractProduct;

                    $op_contractProduct->contract_id = $contract->id;
                    $op_contractProduct->service_id = $service->id;
                    $op_contractProduct->oppertunity_id = $request->oppertunity_id;
                    $op_contractProduct->equipment_id = $equipop['equipment_id'];

                    $op_contractProduct->equipment_serial_no = $equipop['equipment_serial_no'];
                    $op_contractProduct->equipment_model_no = $equipop['equipment_model_no'];
                    $op_contractProduct->under_contract = $equipop['under_contract'];
                    $op_contractProduct->supplay_order = $equipop['supplay_order'];
                    $op_contractProduct->installation_date = $equipop['installation_date'];
                    $op_contractProduct->warraty_expiry_date = $equipop['warraty_expiry_date'];
                    $op_contractProduct->equipment_qty = $equipop['equipment_qty'];
                    $op_contractProduct->equipment_amount = $equipop['equipment_amount'];
                    $op_contractProduct->equipment_tax = $equipop['equipment_tax'];
                    $op_contractProduct->tax_percentage = $equipop['tax_percentage'];
                    $op_contractProduct->equipment_total = $equipop['equipment_total'];
                    $op_contractProduct->contract_type = $equipop['contract_type'];
                    $op_contractProduct->in_ref_no = $equipop['in_ref_no'];
                    $op_contractProduct->amount = $equipop['amount'];
                    $op_contractProduct->tax = $equipop['tax'];
                    $op_contractProduct->amount_tax = $equipop['amount_tax'];

                    $op_contractProduct->msp_no = $equipop['msp_no'];

                    $op_contractProduct->rate = $equipop['rate_op'];

                    $op_contractProduct->margin_amount = $equipop['margin_amount'];

                    $op_contractProduct->main_product_id = $main_contractProduct->id;
                    $op_contractProduct->addon_ptd = 1;

                    $op_contractProduct->save();

                }


            }

        }


            // if (!empty($opper_product) && count($opper_product) > 0) {
            //     foreach ($opper_product as $opp_item) {

            //         $contractProduct = new ContractProduct;

            //         $contractProduct->contract_id = $contract->id;
            //         $contractProduct->service_id = $service->id;
            //         $contractProduct->oppertunity_id = $request->oppertunity_id;
            //         $contractProduct->equipment_id = $opp_item->product_id;
            //         // $contractProduct->ib_id = $equip['ib_id'];
            //         // $contractProduct->equipment_serial_no = $opp_item->id;
            //         // $contractProduct->equipment_model_no = $equip['equipment_model_no'];
            //         $contractProduct->under_contract = $equip['under_contract'];
            //         $contractProduct->supplay_order = $equip['supplay_order'];
            //         $contractProduct->installation_date = $equip['installation_date'];
            //         $contractProduct->warraty_expiry_date = $equip['warraty_expiry_date'];
            //         $contractProduct->equipment_qty = $opp_item->quantity;
            //         $contractProduct->equipment_amount = $opp_item->amount;
            //         $contractProduct->equipment_tax = $opp_item->tax_amount;
            //         $contractProduct->tax_percentage = $opp_item->tax_percentage;
            //         $contractProduct->equipment_total = $opp_item->total_cost;
            //         $contractProduct->contract_type = $equip['contract_type'];
            //         $contractProduct->in_ref_no = $equip['in_ref_no'];
            //         $contractProduct->amount = $opp_item->sale_amount;
            //         $contractProduct->tax = $opp_item->tax;
            //         $contractProduct->amount_tax = $opp_item->tax_amount;

            //         $contractProduct->main_product_id = $main_contractProduct->id;
            //         $contractProduct->addon_ptd = 1;

            //         // $contractProduct->machine_status_id = $equip['machine_status_id'];
            //         // $contractProduct->revanue = $equip['revanue'];
            //         // $contractProduct->contract_start_date = $equip['contract_start_date'];
            //         // $contractProduct->contract_end_date = $equip['contract_end_date'];
            //         $contractProduct->save();
            //     }
            // }

        }

        $opp = Oppertunity::find($request->oppertunity_id);

        if($opp->company_type =='5')
        {
            $type ='bio';
        }
        else
        {
            $type ='bec';
        }

        $request->session()->flash('success', 'Oppertunity added Successfully');

        return redirect()->route('staff.sales.index', ['company_type' => $type]);

    }

    public function create_bill(Request $request)
    {

        $contract_product_ids = $request->contract_product_id;

        $equip_names = $request->equip_name;

        $order_qtys = $request->order_qty;

        $bill_qtys = $request->bill_qty;

        $salescontract_id = $request->salescontract_id;

        // foreach($order_qtys as $k => $item)
        // {

        //     if($order_qtys[$k] < $bill_qtys[$k])
        //     {
    
        //         return response()->json(['bill_qty'-$k => 'Bill Quanty is geater than Order Quantity.']);
        //     }

        // }


      


        // if(!empty($contract_product_ids) && count($contract_product_ids))
        // {
        //     foreach($contract_product_ids as $k => $item)
        //     {
        //         $contract_product = ContractProduct::find($item);

        //         $contract_product->bill_qty = $bill_qtys[$k];

        //         $contract_product->save();
        //     } 
        // }

        $contract_bill = new ContractBill;

        $contract_bill->bill_no =Str::random(10);

        $date = now()->format('Y-m-d');

        $contract_bill->bill_date = $date;

        $contract_bill->contract_product_id = implode(',',$contract_product_ids);

        $contract_bill->bill_contract_id = $request->bill_contract_id;

        if ($request->hasFile('bill_attach')) {

            $file = $request->file('bill_attach');
            $imageName = time() . $request->bill_attach->getClientOriginalName();
            $imageName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $file->move('public/storage/bill_attachment', $imageName);
        }

        $contract_bill->attachment =  isset($imageName) ? $imageName : '';

        $contract_bill->save();

        if(!empty($contract_product_ids) && count($contract_product_ids))
        {
            foreach($contract_product_ids as $k => $item)
            {
                $bill_product = new BillProduct;

                $bill_product->equip_name = $equip_names[$k];

                $bill_product->order_qty = $order_qtys[$k];

                $bill_product->bill_qty = $bill_qtys[$k];

                $bill_product->bill_qty = $bill_qtys[$k];

                $bill_product->contract_product_id = $item;

                $bill_product->contract_bill_id = $contract_bill->id;

                $bill_product->save();
            } 
        }

        $salescontract = SalesContract::find($salescontract_id);

        $salescontract->payment_status = 'Y';

        $salescontract->save();

        return response()->json(['message' => 'Bill created successfully.']);

    }
    
    public function rejectOpportunity(Request $request)
    {
        $reject_id = $request->sale_contract_id;  
    
        $sale_contract = SalesContract::find($reject_id);
    
        if (!$sale_contract) {
            return response()->json(['message' => 'Sales contract not found'], 404);
        }
    
        $sale_contract->contract_status = 'rejected';
    
        $sale_contract->save();
    
        return response()->json(['success' => 'Sales contract rejected']);
    }
    



}


