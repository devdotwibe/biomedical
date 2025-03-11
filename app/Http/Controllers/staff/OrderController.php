<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\Oppertunity;
use App\Prospectus;
use App\Product;
use App\Chatter;

use App\Order_govt_sales;
use App\Order_export_sales;
use App\Order_purchase_sales;
use App\Order_registered_local_sales;
use App\Order_unregistered_local_sales;
use App\Order_tender_sales;
use App\Order_sales_option;

use Validator;

class OrderController extends Controller
{
    public function index()
    {
    	$order				= Order::select('id','oppertunity_id','order_no')->groupBy('order_no')->get();
    	return view('staff.order.index',array('order'=>$order));
    }

    public function create()
    {
    	$oppertunity 		= Chatter::select('oppertunity_id')->where('deal_stage',8)->get();
    	$op_id 				= array();
    	if(sizeof($oppertunity)>0)
    	{
    		foreach($oppertunity as $opid)
    		{
    			$op_id[]	= $opid->oppertunity_id;
    		} 

    		$op_name        = Oppertunity::select('id','oppertunity_name')->whereIn('id',$op_id)->get();
    	}
    	else
    	{
    		$op_name 		= array();
    	}

    	$products           = Product::all();

    	return view('staff.order.add',array('oppertunity'=>$op_name,'products'=>$products));

    }

    public function insert(Request $request)
    {
    	$validation = Validator::make($request->all(), [
			//'op_id' 		=> 'required',
			'product_id'    => 'required',
			'quantity'      => 'required',
			'sale_amount'   => 'required'
		]);

    	$op_id 								= $request->oppertunity;
    	//echo $op_id;die;
		$quantity							= $request->quantity;
		$amount								= $request->amount;
        $sale_amount                        = $request->sale_amount;
        $rand_str 							= $this->generateRandomString(5);
		foreach($request->product_id as $key=>$pdt)
		{
			$order 								= new Order;
			$order->product_id 					= $pdt;
			$order->checklist 					= $request->checklist;
			$order->quantity					= $quantity[$key];
			$order->amount						= $amount[$key];
	        $order->sale_amount   				= $sale_amount[$key];
			$order->oppertunity_id				= $op_id;
			$order->order_no 					= 'order'.$rand_str;
			$order->save();
		}

		$request->session()->flash('success', 'Order created Successfully');

		return redirect('staff/list_order');
		
    }

    function generateRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function delete_order(Request $request)
    {
    	$ids				= $request->id;
    	foreach ($ids as $key => $id) 
    	{
    		Order::destroy($id);
    	}

    	$request->session()->flash('success', 'Order deleted Successfully');

    	//return redirect('staff/list_oppertunity_products/'.$request->op_id);
    }

    public function orderdetail(Request $request)
    {
    	$data1              =   $request->input('data');
        
        $data['order']      =   Order::where('order_no',$data1)->get();
        $data['ord_no']     =   $data1;

        return view('staff.order.orderdetail',$data);
    }

    public function edit($id)
    {
    	$oppertunity 		= Prospectus::select('oppertunity_id')->where('deal_stage',8)->get();
    	$op_id 				= array();
    	if(sizeof($oppertunity)>0)
    	{
    		foreach($oppertunity as $opid)
    		{
    			$op_id[]	= $opid->oppertunity_id;
    		} 

    		$op_name        = Oppertunity::select('id','oppertunity_name')->whereIn('id',$op_id)->get();
    	}
    	else
    	{
    		$op_name 		= array();
    	}

    	$products           = Product::all();
		$order 				= Order::where('order_no',$id)->get();
		$govt_sales				= Order_govt_sales::where('order_id',$id)->get();
		$export_sales				= Order_export_sales::where('order_id',$id)->get();

		$purchase_sales				= Order_purchase_sales::where('order_id',$id)->get();
		$registered_sales				= order_registered_local_sales::where('order_id',$id)->get();
		$unregistered_sales				= order_unregistered_local_sales::where('order_id',$id)->get();
		$tender_sales				= Order_tender_sales::where('order_id',$id)->get();
		$order_sales_option           = Order_sales_option::all()->sortBy('id');
		
    	return view('staff.order.edit',array('order_sales_option'=>$order_sales_option,'oppertunity'=>$op_name,'products'=>$products,'order'=>$order,'order_no'=>$id,'govt_sales'=>$govt_sales,'export_sales'=>$export_sales,'purchase_sales'=>$purchase_sales,'registered_sales'=>$registered_sales,'unregistered_sales'=>$unregistered_sales,'tender_sales'=>$tender_sales));
    }

    public function update(Request $request)
    {
    	$product_id 		= $request->product_id;
    	$quantity 			= $request->quantity;
    	$sale_amount 		= $request->sale_amount;
    	$amount 			= $request->amount;
    	$order_no 			= $request->order_no;
    	$id 				= $request->id;

    	$order              = Order::where([['id',$id],['order_no',$order_no],['product_id',$product_id]])->first();
    	$order->quantity    = $quantity;
    	$order->sale_amount = $sale_amount;
    	$order->amount      = $amount;
    	$order->save();

    	$request->session()->flash('success', 'Product details updated Successfully');
    }

    public function delete_order_product(Request $request)
    {
    	
    	$id					= $request->id;
    	Order::destroy($id);

    	$request->session()->flash('success', 'Product deleted Successfully');
	}
	
	public function save_order_gov_sale(Request $request)
    {
		if($request->gov_id>0)
		{
			$order_govt_sales     				= Order_govt_sales::find($request->gov_id);
		}
		else{
			$order_govt_sales 								= new Order_govt_sales;
		}

		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Government Sales")
			{ $name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
			}
		
		}

	
		/*$order_govt_sales->gov_po 					= $request->gov_po;
		$order_govt_sales->gov_po_status =($request->gov_po_status) ? 'Y' : 'N';
		$order_govt_sales->gov_config 					= $request->gov_config;
		$order_govt_sales->gov_config_status =($request->gov_config_status) ? 'Y' : 'N';
		$order_govt_sales->gov_payment 					= $request->gov_payment;
		$order_govt_sales->gov_payment_status =($request->gov_payment_status) ? 'Y' : 'N';
		$order_govt_sales->gov_delivery 					= $request->gov_delivery;
		$order_govt_sales->gov_delivery_status =($request->gov_delivery_status) ? 'Y' : 'N';
		$order_govt_sales->gov_gst 					= $request->gov_gst;
		$order_govt_sales->gov_gst_status =($request->gov_gst_status) ? 'Y' : 'N';
		$order_govt_sales->gov_technical 					= $request->gov_technical;
		$order_govt_sales->gov_technical_status =($request->gov_technical_status) ? 'Y' : 'N';
		$order_govt_sales->gov_financial 					= $request->gov_financial;
		$order_govt_sales->gov_financial_status =($request->gov_financial_status) ? 'Y' : 'N';
		$order_govt_sales->gov_stock_avl 					= $request->gov_stock_avl;
		$order_govt_sales->gov_stock_avl_status =($request->gov_stock_avl_status) ? 'Y' : 'N';
		$order_govt_sales->gov_cus_confirm 					= $request->gov_cus_confirm;
		$order_govt_sales->gov_cus_confirm_status =($request->gov_cus_confirm_status) ? 'Y' : 'N';
		$order_govt_sales->gov_invoice 					= $request->gov_invoice;
		$order_govt_sales->gov_invoice_status =($request->gov_invoice_status) ? 'Y' : 'N';
		$order_govt_sales->gov_warranty 					= $request->gov_warranty;
		$order_govt_sales->gov_warranty_status =($request->gov_warranty_status) ? 'Y' : 'N';
		$order_govt_sales->gov_install 					= $request->gov_install;
		$order_govt_sales->gov_install_status =($request->gov_install_status) ? 'Y' : 'N';
		$order_govt_sales->gov_po1 					= $request->gov_po1;
		$order_govt_sales->gov_po1_status =($request->gov_po1_status) ? 'Y' : 'N';
		$order_govt_sales->gov_kmscl 					= $request->gov_kmscl;
		$order_govt_sales->gov_kmscl_status =($request->gov_kmscl_status) ? 'Y' : 'N';
		$order_govt_sales->gov_verify 					= $request->gov_verify;
		$order_govt_sales->gov_verify_status =($request->gov_verify_status) ? 'Y' : 'N';
		$order_govt_sales->gov_eway 					= $request->gov_eway;
		$order_govt_sales->gov_eway_status =($request->gov_eway_status) ? 'Y' : 'N';
		$order_govt_sales->gov_stock_entry 					= $request->gov_stock_entry;
		$order_govt_sales->gov_stock_entry_status =($request->gov_stock_entry_status) ? 'Y' : 'N';
		$order_govt_sales->gov_photo_equip 					= $request->gov_photo_equip;
		$order_govt_sales->gov_photo_equip_status =($request->gov_photo_equip_status) ? 'Y' : 'N';
		$order_govt_sales->gov_invo_pd 					= $request->gov_invo_pd;
		$order_govt_sales->gov_invo_pd_status =($request->gov_invo_pd_status) ? 'Y' : 'N';
		$order_govt_sales->gov_agreement 					= $request->gov_agreement;
		$order_govt_sales->gov_agreement_status =($request->gov_agreement_status) ? 'Y' : 'N';
		$order_govt_sales->gov_undertaking 					= $request->gov_undertaking;
		$order_govt_sales->gov_undertaking_status =($request->gov_undertaking_status) ? 'Y' : 'N';
		$order_govt_sales->gov_bgagreement 					= $request->gov_bgagreement;
		$order_govt_sales->gov_bgagreement_status =($request->gov_bgagreement_status) ? 'Y' : 'N';
		$order_govt_sales->gov_fd 					= $request->gov_fd;
		$order_govt_sales->gov_fd_status =($request->gov_fd_status) ? 'Y' : 'N';
		$order_govt_sales->gov_req_latter 					= $request->gov_req_latter;
		$order_govt_sales->gov_req_latter_status =($request->gov_req_latter_status) ? 'Y' : 'N';
		$order_govt_sales->gov_letter_party 					= $request->gov_letter_party;
		$order_govt_sales->gov_letter_party_status =($request->gov_letter_party_status) ? 'Y' : 'N';*/

		$order_govt_sales->order_id 					= $request->gov_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}
	
	public function save_order_export_sale(Request $request)
    {
		if($request->export_id>0)
		{
			$order_govt_sales     				= Order_export_sales::find($request->export_id);
		}
		else{
			$order_govt_sales 								= new Order_export_sales;
		}

		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Export Sales")
			{ $name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
			}
		
		}

	
		/*$order_govt_sales->export_po 					= $request->export_po;
		$order_govt_sales->export_po_status =($request->export_po_status) ? 'Y' : 'N';
		$order_govt_sales->export_config 					= $request->export_config;
		$order_govt_sales->export_config_status =($request->export_config_status) ? 'Y' : 'N';
		$order_govt_sales->export_payment 					= $request->export_payment;
		$order_govt_sales->export_payment_status =($request->export_payment_status) ? 'Y' : 'N';
		$order_govt_sales->export_delivery_terms 					= $request->export_delivery_terms;
		$order_govt_sales->export_delivery_terms_status =($request->export_delivery_terms_status) ? 'Y' : 'N';
		$order_govt_sales->export_gst 					= $request->export_gst;
		$order_govt_sales->export_gst_status =($request->export_gst_status) ? 'Y' : 'N';
		$order_govt_sales->export_technical 					= $request->export_technical;
		$order_govt_sales->export_technical_status =($request->export_technical_status) ? 'Y' : 'N';
		$order_govt_sales->export_financial 					= $request->export_financial;
		$order_govt_sales->export_financial_status =($request->export_financial_status) ? 'Y' : 'N';
		$order_govt_sales->export_stock 					= $request->export_stock;
		$order_govt_sales->export_stock_status =($request->export_stock_status) ? 'Y' : 'N';
		$order_govt_sales->export_cust_conf 					= $request->export_cust_conf;
		$order_govt_sales->export_cust_conf_status =($request->export_cust_conf_status) ? 'Y' : 'N';
		$order_govt_sales->export_sli 					= $request->export_sli;
		$order_govt_sales->export_sli_status =($request->export_sli_status) ? 'Y' : 'N';
		$order_govt_sales->export_annexure 					= $request->export_annexure;
		$order_govt_sales->export_annexure_status =($request->export_annexure_status) ? 'Y' : 'N';
		$order_govt_sales->export_sfd 					= $request->export_sfd;
		$order_govt_sales->export_sfd_status =($request->export_sfd_status) ? 'Y' : 'N';
		$order_govt_sales->export_haz_cert 					= $request->export_haz_cert;
		$order_govt_sales->export_haz_cert_status =($request->export_haz_cert_status) ? 'Y' : 'N';
		$order_govt_sales->export_igst 					= $request->export_igst;
		$order_govt_sales->export_igst_status =($request->export_igst_status) ? 'Y' : 'N';
		$order_govt_sales->export_invoice 					= $request->export_invoice;
		$order_govt_sales->export_invoice_status =($request->export_invoice_status) ? 'Y' : 'N';
		$order_govt_sales->export_packing 					= $request->export_packing;
		$order_govt_sales->export_packing_status =($request->export_packing_status) ? 'Y' : 'N';
		$order_govt_sales->export_tech_write 					= $request->export_tech_write;
		$order_govt_sales->export_tech_write_status =($request->export_tech_write_status) ? 'Y' : 'N';
		$order_govt_sales->export_iec 					= $request->export_iec;
		$order_govt_sales->export_iec_status =($request->export_iec_status) ? 'Y' : 'N';
		$order_govt_sales->export_ad_code 					= $request->export_ad_code;
		$order_govt_sales->export_ad_code_status =($request->export_ad_code_status) ? 'Y' : 'N';
		$order_govt_sales->export_cli 					= $request->export_cli;
		$order_govt_sales->export_cli_status =($request->export_cli_status) ? 'Y' : 'N';
		$order_govt_sales->export_inv_po 					= $request->export_inv_po;
		$order_govt_sales->export_inv_po_status =($request->export_inv_po_status) ? 'Y' : 'N';
		$order_govt_sales->export_eway 					= $request->export_eway;
		$order_govt_sales->export_eway_status =($request->export_eway_status) ? 'Y' : 'N';
		$order_govt_sales->export_despatch 					= $request->export_despatch;
		$order_govt_sales->export_despatch_status =($request->export_despatch_status) ? 'Y' : 'N';
		$order_govt_sales->export_delivery 					= $request->export_delivery;
		$order_govt_sales->export_delivery_status =($request->export_delivery_status) ? 'Y' : 'N';
		$order_govt_sales->export_shipping 					= $request->export_shipping;
		$order_govt_sales->export_shipping_status =($request->export_shipping_status) ? 'Y' : 'N';
		$order_govt_sales->export_airway 					= $request->export_airway;
		$order_govt_sales->export_airway =($request->export_airway) ? 'Y' : 'N';
		$order_govt_sales->export_airway_status 					= $request->export_airway_status;
		$order_govt_sales->export_airway_status =($request->export_airway_status) ? 'Y' : 'N';
		$order_govt_sales->export_slip_copy 					= $request->export_slip_copy;
		$order_govt_sales->export_slip_copy_status =($request->export_slip_copy_status) ? 'Y' : 'N';
		$order_govt_sales->export_foreign 					= $request->export_foreign;
		$order_govt_sales->export_foreign_status =($request->export_foreign_status) ? 'Y' : 'N';*/
	

		$order_govt_sales->order_id 					= $request->export_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}


	public function save_order_purchase_sale(Request $request)
    {
		if($request->purchase_id>0)
		{
			$order_govt_sales     				= Order_purchase_sales::find($request->purchase_id);
		}
		else{
			$order_govt_sales 								= new Order_purchase_sales;
		}

		$order_sales_option           = Order_sales_option::all();
	
		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Purchase import")
			{ $name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
			}
		
		}
	
		/*$order_govt_sales->purchase_po 					= $request->purchase_po;
		$order_govt_sales->purchase_po_status =($request->purchase_po_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_order 					= $request->purchase_order;
		$order_govt_sales->purchase_order_status =($request->purchase_order_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_inco_slip 					= $request->purchase_inco_slip;
		$order_govt_sales->purchase_inco_slip_status =($request->purchase_inco_slip_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_clear_det 					= $request->purchase_clear_det;
		$order_govt_sales->purchase_clear_det_status =($request->purchase_clear_det_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_transport 					= $request->purchase_transport;
		$order_govt_sales->purchase_transport_status =($request->purchase_transport_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_airway 					= $request->purchase_airway;
		$order_govt_sales->purchase_airway_status =($request->purchase_airway_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_custom 					= $request->purchase_custom;
		$order_govt_sales->purchase_custom_status =($request->purchase_custom_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_gov_po 					= $request->purchase_gov_po;
		$order_govt_sales->purchase_gov_po_status =($request->purchase_gov_po_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_gov_eway 					= $request->purchase_gov_eway;
		$order_govt_sales->purchase_gov_eway_status =($request->purchase_gov_eway_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_foreign 					= $request->purchase_foreign;
		$order_govt_sales->purchase_foreign_status =($request->purchase_foreign_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_bill 					= $request->purchase_bill;
		$order_govt_sales->purchase_bill_status =($request->purchase_bill_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_invoice 					= $request->purchase_invoice;
		$order_govt_sales->purchase_invoice_status =($request->purchase_invoice_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_pur_order 					= $request->purchase_pur_order;
		$order_govt_sales->purchase_pur_order_status =($request->purchase_pur_order_status) ? 'Y' : 'N';
		$order_govt_sales->purchase_ref_no 					= $request->purchase_ref_no;
		$order_govt_sales->purchase_ref_no_status =($request->purchase_ref_no_status) ? 'Y' : 'N';*/
	
	

		$order_govt_sales->order_id 					= $request->purchase_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}


	public function save_order_registered_sale(Request $request)
    {
		if($request->register_id>0)
		{
			$order_govt_sales     				= Order_registered_local_sales::find($request->register_id);
		}
		else{
			$order_govt_sales 								= new Order_registered_local_sales;
		}

		$order_sales_option           = Order_sales_option::all();
	
		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Local Sales to Registered Dealer")
			{ $name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
			}
		
		}

	
	/*	$order_govt_sales->salereg_po 					= $request->salereg_po;
		$order_govt_sales->salereg_po_status =($request->salereg_po_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_config 					= $request->salereg_config;
		$order_govt_sales->salereg_config_status =($request->salereg_config_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_payment 					= $request->salereg_payment;
		$order_govt_sales->salereg_payment_status =($request->salereg_payment_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_purchase 					= $request->salereg_purchase;
		$order_govt_sales->salereg_purchase_status =($request->salereg_purchase_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_gst 					= $request->salereg_gst;
		$order_govt_sales->salereg_gst_status =($request->salereg_gst_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_technical 					= $request->salereg_technical;
		$order_govt_sales->salereg_technical_status =($request->salereg_technical_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_financial 					= $request->salereg_financial;
		$order_govt_sales->salereg_financial_status =($request->salereg_financial_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_stock_avil 					= $request->salereg_stock_avil;
		$order_govt_sales->salereg_stock_avil_status =($request->salereg_stock_avil_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_cust_conf 					= $request->salereg_cust_conf;
		$order_govt_sales->salereg_cust_conf_status =($request->salereg_cust_conf_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_invoice 					= $request->salereg_invoice;
		$order_govt_sales->salereg_invoice_status =($request->salereg_invoice_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_inv_po 					= $request->salereg_inv_po;
		$order_govt_sales->salereg_inv_po_status =($request->salereg_inv_po_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_eway 					= $request->salereg_eway;
		$order_govt_sales->salereg_eway_status =($request->salereg_eway_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_despatch 					= $request->salereg_despatch;
		$order_govt_sales->salereg_despatch_status =($request->salereg_despatch_status) ? 'Y' : 'N';
		$order_govt_sales->salereg_delivery 					= $request->salereg_delivery;
		$order_govt_sales->salereg_delivery_status =($request->salereg_delivery_status) ? 'Y' : 'N';*/
	
	

		$order_govt_sales->order_id 					= $request->register_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}


	public function save_order_unregistered_sale(Request $request)
    {
		if($request->unregister_id>0)
		{
			$order_govt_sales     				= Order_unregistered_local_sales::find($request->unregister_id);
		}
		else{
			$order_govt_sales 								= new Order_unregistered_local_sales;
		}

		$order_sales_option           = Order_sales_option::all();
	
		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Local Sales to unRegistered Dealer")
			{ $name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
			}
		
		}

	
	/*	$order_govt_sales->saleunreg_po 					= $request->saleunreg_po;
		$order_govt_sales->saleunreg_po_status =($request->saleunreg_po_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_config 					= $request->saleunreg_config;
		$order_govt_sales->saleunreg_config_status =($request->saleunreg_config_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_payment_term 					= $request->saleunreg_payment_term;
		$order_govt_sales->saleunreg_payment_term_status =($request->saleunreg_payment_term_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_delivery_terms 					= $request->saleunreg_delivery_terms;
		$order_govt_sales->saleunreg_delivery_terms_status =($request->saleunreg_delivery_terms_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_flood 					= $request->saleunreg_flood;
		$order_govt_sales->saleunreg_flood_status =($request->saleunreg_flood_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_technical 					= $request->saleunreg_technical;
		$order_govt_sales->saleunreg_technical_status =($request->saleunreg_technical_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_financial 					= $request->saleunreg_financial;
		$order_govt_sales->saleunreg_financial_status =($request->saleunreg_financial_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_stock_avil 					= $request->saleunreg_stock_avil;
		$order_govt_sales->saleunreg_stock_avil_status =($request->saleunreg_stock_avil_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_cust_conf 					= $request->saleunreg_cust_conf;
		$order_govt_sales->saleunreg_cust_conf_status =($request->saleunreg_cust_conf_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_invoice 					= $request->saleunreg_invoice;
		$order_govt_sales->saleunreg_invoice_status =($request->saleunreg_invoice_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_invo_po 					= $request->saleunreg_invo_po;
		$order_govt_sales->saleunreg_invo_po_status =($request->saleunreg_invo_po_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_eway 					= $request->saleunreg_eway;
		$order_govt_sales->saleunreg_eway_status =($request->saleunreg_eway_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_dispatch 					= $request->saleunreg_dispatch;
		$order_govt_sales->saleunreg_dispatch_status =($request->saleunreg_dispatch_status) ? 'Y' : 'N';
		$order_govt_sales->saleunreg_delivery 					= $request->saleunreg_delivery;
		$order_govt_sales->saleunreg_delivery_status =($request->saleunreg_delivery_status) ? 'Y' : 'N';*/
	
	

		$order_govt_sales->order_id 					= $request->unregister_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}


	public function save_order_tender_sale(Request $request)
    {
		if($request->tender_id>0)
		{
			$order_govt_sales     				= Order_tender_sales::find($request->tender_id);
		}
		else{
			$order_govt_sales 								= new Order_tender_sales;
		}
		$order_sales_option           = Order_sales_option::all();
	
		foreach($order_sales_option as $values)
		{
			if($values->sale_type=="Tender")
			{  
				if($values->subname=="")
				{
				$name_val=$values->name_val;
				$status=$values->name_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
				}
				else{
				$name_val=$values->subname_val;
				$status=$values->subname_val.'_status';
				$order_govt_sales->$name_val 					= $request->$name_val;
				$order_govt_sales->$status			= ($request->$status) ? 'Y' : 'N';;
				}
				

			}
		
		}

	/*	$order_govt_sales->tender_document 					= $request->tender_document;
		$order_govt_sales->tender_document_status =($request->tender_document_status) ? 'Y' : 'N';
		$order_govt_sales->tender_terms 					= $request->tender_terms;
		$order_govt_sales->tender_terms_status =($request->tender_terms_status) ? 'Y' : 'N';
		$order_govt_sales->tender_spec_conf 					= $request->tender_spec_conf;
		$order_govt_sales->tender_spec_conf_status =($request->tender_spec_conf_status) ? 'Y' : 'N';
		$order_govt_sales->tender_auth_manu 					= $request->tender_auth_manu;
		$order_govt_sales->tender_auth_manu_status =($request->tender_auth_manu_status) ? 'Y' : 'N';
		$order_govt_sales->tender_main_serv 					= $request->tender_main_serv;
		$order_govt_sales->tender_main_serv_status =($request->tender_main_serv_status) ? 'Y' : 'N';
		$order_govt_sales->tender_price_supp 					= $request->tender_price_supp;
		$order_govt_sales->tender_price_supp_status =($request->tender_price_supp_status) ? 'Y' : 'N';
		$order_govt_sales->tender_prod_certi 					= $request->tender_prod_certi;
		$order_govt_sales->tender_prod_certi_status =($request->tender_prod_certi_status) ? 'Y' : 'N';
		$order_govt_sales->tender_literature 					= $request->tender_literature;
		$order_govt_sales->tender_literature_status =($request->tender_literature_status) ? 'Y' : 'N';
		$order_govt_sales->tender_state_supplier 					= $request->tender_state_supplier;
		$order_govt_sales->tender_state_supplier_status =($request->tender_state_supplier_status) ? 'Y' : 'N';
		$order_govt_sales->tender_warrenty 					= $request->tender_warrenty;
		$order_govt_sales->tender_warrenty_status =($request->tender_warrenty_status) ? 'Y' : 'N';
		$order_govt_sales->tender_emd 					= $request->tender_emd;
		$order_govt_sales->tender_emd_status =($request->tender_emd_status) ? 'Y' : 'N';
		$order_govt_sales->tender_price_bid 					= $request->tender_price_bid;
		$order_govt_sales->tender_price_bid_status =($request->tender_price_bid_status) ? 'Y' : 'N';
		$order_govt_sales->tender_upload_send 					= $request->tender_upload_send;
		$order_govt_sales->tender_upload_send_status =($request->tender_upload_send_status) ? 'Y' : 'N';
		$order_govt_sales->tender_tech_demo 					= $request->tender_tech_demo;
		$order_govt_sales->tender_tech_demo_status =($request->tender_tech_demo_status) ? 'Y' : 'N';
		$order_govt_sales->tender_tech_bid 					= $request->tender_tech_bid;
		$order_govt_sales->tender_tech_bid_status =($request->tender_tech_bid_status) ? 'Y' : 'N';
		$order_govt_sales->tender_financial_bid 					= $request->tender_financial_bid;
		$order_govt_sales->tender_financial_bid_status =($request->tender_financial_bid_status) ? 'Y' : 'N';*/
	
	
	

		$order_govt_sales->order_id 					= $request->tender_order_id;
	
		
		$order_govt_sales->save();

    	
    	
	}



	

    public function add_order_product($id)
    {

    	$order 				= Order::where('order_no',$id)->get();
    	$prd 				= array();

    	foreach($order as $ord)
    	{
    		$prd[]			= $ord->product_id;
    	}

    	$products           = Product::whereNotIn('id',$prd)->get();

    	return view('staff.order.add_product',array('products'=>$products));
    }

    public function insert_order_product(Request $request,$id)
    {
    	$op_id 								= Order::where('order_no',$id)->first()->oppertunity_id;

    	$quantity							= $request->quantity;
		$amount								= $request->amount;
        $sale_amount                        = $request->sale_amount;

        foreach($request->product_id as $key=>$pdt)
		{
			$order 								= new Order;
			$order->product_id 					= $pdt;
			$order->quantity					= $quantity[$key];
			$order->amount						= $amount[$key];
	        $order->sale_amount   				= $sale_amount[$key];
			$order->oppertunity_id				= $op_id;
			$order->order_no 					= $id;
			$order->save();
		}

		$request->session()->flash('success', 'Product inserted Successfully');

		return redirect('staff/edit_order/'.$id);
    }

}
