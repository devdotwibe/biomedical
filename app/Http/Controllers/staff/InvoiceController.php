<?php
namespace App\Http\Controllers\staff;

use App\Invoice;
use App\Transation_product;
use App\Inoutregister;
use App\Transation;
use App\Vendor;
use App\Product;
use App\Task_type;
use App\Banner;
use App\Transation_pocopy;

use App\Staff;
use App\Country;
use App\Hosdesignation;

use App\Category;
use App\Purchase;
use App\Chatter;
use App\Invoice_product;
use App\Company;
use App\Users_shipping_address;

use App\Oppertunity;

use App\State;

use App\District;

use App\User;
use App\Dispatch;

use App\Transation_staff_updates;
use App\Transaction_manage_staff;
use App\Invoice_complete_flow;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



use App\Http\Controllers\Controller;



use Image;

use Storage;
use PDF;
use Carbon\Carbon;

class InvoiceController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {
    $invoice = Invoice::orderBy('id', 'desc')->get();
    $transaction =  DB::select("select * from  transation where financial_approval_status='Completed' AND invoice_status='Pending' order by id desc");
       return view('staff.invoice.index', compact('invoice','transaction'));
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()
    {
      $transaction =  DB::select("select * from  transation where financial_approval_status='Completed' AND invoice_status='Pending' order by id desc");
      
      $dispatch=Dispatch::where('invoice_id','=',0)->orderBy('id', 'asc')->get();

      $invoice  =  DB::select("select * from  invoice order by id desc  limit 1");

     $last_id=$invoice[0]->id+1;
        return view('staff.invoice.create', ['transaction'=> $transaction,'dispatch'=> $dispatch,'last_id'=>$last_id]);

    }



    public function store(Request $request)
    {
        $this->validate($request, array(
            'inandout' => 'required|max:100'
         ));
      if($request->dispatch_id>0)
      {
         $dispatch=Dispatch::where('id',$request->dispatch_id)->first(); 
         $transaction_id=$dispatch->transation_id;

      } else{
         $transaction_id=$request->transaction_id;
      }
   
   $mange_customer=Transation::where('id',$transaction_id)->first();
   $invoice_complete_flow = Invoice_complete_flow::find($mange_customer->invoice_complete_flow_id);
  /* if($request->dispatch_id>0)
   {

   }
   else{*/
      $invoice = new Invoice;
      $invoice->transaction_id = $transaction_id;
      $invoice->inandout = $request->inandout;
      $invoice->invoice_date = date("Y-m-d");
      $invoice->status = 'Dispatch Invoice';
      $invoice->user_id = $mange_customer->user_id;
      $dispatch_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->dispatch.' days'));
      $invoice->dispatch_date = $dispatch_date;
      $delivery_date=date('Y-m-d', strtotime($dispatch_date. ' + '.$invoice_complete_flow->delivery.' days'));
      $invoice->delivery_date = $delivery_date;
      $user_date=date('Y-m-d', strtotime($delivery_date. ' + '.$invoice_complete_flow->user_confirm.' days'));
      $invoice->user_date = $user_date;
      $department_date=date('Y-m-d', strtotime($user_date. ' + '.$invoice_complete_flow->dept_confirm.' days'));
      $invoice->department_date=$department_date;
      $finance_date=date('Y-m-d', strtotime($department_date. ' + '.$invoice_complete_flow->finance_confirm.' days'));
      $invoice->finance_date=$finance_date;
      $payment_date=date('Y-m-d', strtotime($finance_date. ' + '.$invoice_complete_flow->payment_confirm.' days'));
      $invoice->payment_date=$payment_date;
      $invoice->save();
   //}
    if($request->invoice_id=="")
    {
      $invoice_update = Invoice::find($invoice->id);
    $invoice_update->invoice_id = 'INVOICE_'.$invoice->id;
    $invoice_update->save();
    }
    else{
      $invoice_update = Invoice::find($invoice->id);
      $invoice_update->invoice_id = $request->invoice_id;
      $invoice_update->save();
    }
    

    if($request->dispatch_id==0)
   {
    $trans_staff_update = new Transation_staff_updates;
    $trans_staff_update->invoice_id = $invoice->id;
    $trans_staff_update->transation_id=$transaction_id;
    $trans_staff_update->status = 'Dispatch Invoice';
    $trans_staff_update->current_status = 'Pending';
    $trans_staff_update->added_date = date("y-m-d");
    $mange_staff=Transaction_manage_staff::where('manage_section','Dispatch Invoice')->first();
    $trans_staff_update->staff_id = $mange_staff->staff_id;
    
    $trans_staff_update->user_id = $mange_customer->user_id;
    $trans_staff_update->type_approval ='Invoice';
    $trans_staff_update->save();
   }
   else{
      $trans_staff_update = new Transation_staff_updates;
            $trans_staff_update->invoice_id = $invoice->id;
            $trans_staff_update->transation_id = $transaction_id;
            $trans_staff_update->dispatch_id = $request->dispatch_id;
            $trans_staff_update->status = 'Dispatch Verify';
            $trans_staff_update->current_status = 'Pending';
            $trans_staff_update->added_date = date("y-m-d");
            $mange_staff=Transation_staff_updates::where('dispatch_id',$request->dispatch_id)->first();
            $trans_staff_update->staff_id = $mange_staff->staff_id;
            $trans_staff_update->user_id = $mange_staff->user_id;
            $trans_staff_update->type_approval ='Invoice';
            $trans_staff_update->save();
   }

     $product_count=count($request->ids);
     if($product_count>0)
     {
     
        for($i=0;$i<$product_count;$i++)
        {
           if($request->dispatch_id>0)
           {
            $transation_product = Transation_product::find($request->ids[$i]);
           }
           else{
            $transation_product = Transation_product::find($request->ids[$i]);
          $out_product_quantity= $transation_product->out_product_quantity;
          $balance=$out_product_quantity+$request->quantity[$i];
          $transation_product->out_product_quantity=$balance;
          $transation_product->save();
           }
          

          
          $invoice_product = new Invoice_product;
          $invoice_product->transation_id=$transaction_id;
          $invoice_product->invoice_id=$invoice->id;
          $invoice_product->product_id=$transation_product->product_id;
          $invoice_product->product_name=$transation_product->product_name;
          $invoice_product->quantity=$request->quantity[$i];
          $invoice_product->sale_amount=$transation_product->sale_amount;
          $invoice_product->hsn=$transation_product->hsn;
          $invoice_product->cgst=$request->cgst[$i];
          $invoice_product->sgst=$request->sgst[$i];
          $invoice_product->igst=$request->igst[$i];
          $invoice_product->cess=$request->cess[$i];
          $invoice_product->amt=$request->amt[$i];
          $invoice_product->tax_percentage=$request->tax_percentage[$i];
          
          $invoice_product->save();

        }
     }
     
    
     

     if($request->dispatch_id>0)
     {
      $dispatch_update = Dispatch::find($request->dispatch_id);
      $dispatch_update->invoice_id=$invoice->id;
      $dispatch_update->invoice_no='INVOICE_'.$invoice->id;
      $dispatch_update->save();

      $mange_staff_update=Transation_staff_updates::where('transation_id',$transaction_id)
      ->where('status',"Dispatch Without Invoice")
      ->where('dispatch_id',$request->dispatch_id)
      ->update(array('current_status' => "Approval"));

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

     * @param  \App\Invoice  $brand

     * @return \Illuminate\Http\Response

     */

    public function show(Invoice $Invoice)

    {



    }

    public function change_purchase_in_out(Request $request)
    {
        $inoutregister =  DB::select("select * from  inoutregister where id='".$request->inout."' ");
        if($inoutregister[0]->purchase_id>0)
        {
          $purchase = Purchase::find($inoutregister[0]->purchase_id);
          $vendor_address=$purchase->address;

          $company = Company::find($purchase->company_id);
          $admin_company=$company->name;
        }
        else{
          $vendor_address='';
          $admin_company='';
        }
        if($inoutregister[0]->user_id>0)
        {
          $user = User::withTrashed()->find($inoutregister[0]->user_id);
          $user_name=$user->business_name;
          $user_address=$user->address1;
        }
        else{
          $user_name='';
          $user_address='';
        }
        if($inoutregister[0]->vendor_id>0)
        {
          $vendor = Vendor::find($inoutregister[0]->vendor_id);
          $vendor_name=$vendor->name;
        }
        else{
          $vendor_name='';
        }

        $inoutregister_product =  DB::select("select * from  inoutregister_product where inoutregister_id='".$request->inout."' ");
        echo json_encode($inoutregister).'*'.json_encode($inoutregister_product).'*'.$vendor_name.'*'.$user_name.'*'.$user_address.'*'.$vendor_address.'*'.$admin_company;
    }

    public function get_inout_details_search(Request $request)
    {
      
      $condition='';
      if($request->vendor_id>0)
      {
        $condition .=" AND vendor_id=".$request->vendor_id;
      }
      if($request->materialout!='')
      {
        $condition .=" AND materialout=".'"'.$request->materialout.'"';
      }
      if($request->service_material!='')
      {
        $condition .=" AND service_material=".'"'.$request->service_material.'"';
      }
      if($request->purchase_material!='')
      {
        $condition .=" AND purchase_material=".'"'.$request->purchase_material.'"';
      }
      if($request->sales_material!='')
      {
        $condition .=" AND sales_material=".'"'.$request->sales_material.'"';
      }
      if($request->other_material!='')
      {
        $condition .=" AND other_material=".'"'.$request->other_material.'"';
      }

     
      $inoutregister =  DB::select("select * from  inoutregister where type_register='In' AND inandout_type='Material' ".$condition." ");
      echo json_encode($inoutregister);
    }

    public function get_sales_outout_details_search(Request $request)
    {
      
      $condition='';
      if($request->vendor_id>0)
      {
        $condition .=" AND vendor_id=".$request->vendor_id;
      }
      if($request->materialout!='')
      {
        $condition .=" AND materialout=".'"'.$request->materialout.'"';
      }
      if($request->service_material!='')
      {
        $condition .=" AND service_material=".'"'.$request->service_material.'"';
      }
      if($request->purchase_material!='')
      {
        $condition .=" AND purchase_material=".'"'.$request->purchase_material.'"';
      }
      if($request->sales_material!='')
      {
        $condition .=" AND sales_material=".'"'.$request->sales_material.'"';
      }
      if($request->other_material!='')
      {
        $condition .=" AND other_material=".'"'.$request->other_material.'"';
      }

     
      $inoutregister =  DB::select("select * from  inoutregister where type_register='Out' AND inandout_type='Material' ".$condition." ");
      echo json_encode($inoutregister);
    }

    
    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Invoice  $invoice

     * @return \Illuminate\Http\Response

     */

    public function edit(Invoice $invoice)

    {


        $user 				= User::all();



        $oppertunity 		= Chatter::select('oppertunity_id')->where('deal_stage',8)->get();

        $products           = Product::all();

        $company = Company::all();

        $state = DB::table('state')

        ->orderBy('id', 'asc')

        ->select('name','id')

        ->get();



        $district = DB::table('district')

        ->orderBy('id', 'asc')

        ->select('name','id')

        ->get();
        $country = DB::table('countries')

        ->orderBy('name', 'asc')

        ->select('name','id')

        ->get();


       $staff = Staff::all();

       $transation_product = DB::select("select * from transation_product where transation_id='".$transation->id."' order by id asc");
       $transation_pocopy = DB::select("select * from transation_pocopy where transation_id='".$transation->id."' order by id asc");

       $products_warenty =  DB::select("select * from  products where category_type_id='6' ");

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

        return view('staff.transation.edit', compact('products_warenty','transation_pocopy','transation_product','country','transation','user','op_name','products','state','district','company','staff'));

    }


    



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Transation  $brand

     * @return \Illuminate\Http\Response

     */

    
    public function check_invoice_id_exit(Request $request)
    {
      $checkinvoice =  DB::select("select * from  invoice where invoice_id='".$request->invoice_id."' ");
      if(count($checkinvoice)>0){
         echo '1';
      }
      else{
         echo '0';
      }
    }

    public function update(Request $request, $id)

    {


        $this->validate($request, array(
            'company_id' => 'required|max:100'
         ));

     return redirect()->route('staff.invoice.index')->with('success', 'Data successfully saved.');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Transation  $brand

     * @return \Illuminate\Http\Response

     */

    public function destroy(Transation $brand)

    {





    }



    public function deleteAll(Request $request)

    {



    }

   public function get_transaction_details_for_invoice(Request $request)
   {
    
    $transaction=Transation::where('id',$request->trans_id)->get(); 
    $user=User::where('id',$transaction[0]->user_id)->get(); 
    $transaction_product=Transation_product::where('transation_id',$request->trans_id)->get(); 
    echo json_encode($user).'*'.json_encode($transaction_product);
   }

   public function get_dispatch_details_for_invoice(Request $request)
   {
   $dispatch=Dispatch::where('id',$request->dispatch_id)->first(); 
    $transaction=Transation::where('id',$dispatch->transation_id)->get(); 
    $user=User::where('id',$transaction[0]->user_id)->get(); 
    //$transaction_product=Transation_product::where('transation_id',$dispatch->transation_id)->get();

    $transaction_product =  DB::select("select *,des_prod.quantity as des_quantity,tras_prod.id as trans_id from transation_product as tras_prod
     inner join dispatch_product as des_prod 
     on tras_prod.product_id=des_prod.product_id
     where tras_prod.transation_id='".$dispatch->transation_id."' AND  des_prod.dispatch_id='".$request->dispatch_id."' ");

  
    return view('staff.invoice.dispatchproduct', ['user'=>$user,'transaction_product'=>$transaction_product,'dispatch_id'=>$request->dispatch_id]);
 }


   
   public function get_transaction_product_qty_check_invoice(Request $request)
   {
    $transaction_product=Transation_product::where('transation_id',$request->trans_id)->where('id',$request->trans_pro_id)->first(); 
    $tot_quantity= $transaction_product->quantity;
    $quantity=$request->quantity;
    $out_product_quantity= $transaction_product->out_product_quantity;
    $balance_qty=$tot_quantity-$out_product_quantity;
    if($quantity>$balance_qty)
    {
      echo '0'.'*'.$balance_qty;
    }
    else{
      echo '1'.'*'.$balance_qty;
    }

    }
   
    public function preview_invoice(Request $request,$id) {
     
      $invoice = Invoice::find($id);
      $invoice_product=Invoice_product::where('invoice_id',$id)->get(); 
      $transaction=Transation::where('id',$invoice->transaction_id)->first(); 
      $user=User::where('id',$transaction->user_id)->first(); 
        $html ='
        <html>

        <head>

            <style>

            @page {size :794.993324432577px 1123.7650200267px; margin: 134.15220293725px 0px 114.15220293725px 0px;}

            .otherpages {margin:0px 3% 0px 3%;color:#000; 

      font-family:Arial, Helvetica, sans-serif; 

      font-size:15px;

              font-weight:normal;position:relative;display:inline-block;page-break-before: always;overflow:auto; }

            .firstpage{page-break-inside: never;}

            #body {color:#000; 

      font-family:Arial, Helvetica, sans-serif; 

      font-size:15px;

      font-weight:normal;display:inline-block; margin-top:114.15220293725px; margin-right:19.9599465954606px; margin-bottom:72.0961281708945px; margin-left:0;} 

           

            ._page:after { content: counter(page);}

            .header { position: fixed; top:-125px; left: -1.5%; right:0; height:145px; }

       .footer { position: fixed; left:0; bottom:-120px; right:0; height:80px; background:#003f86;}

            .footer-wrap{width:94%; margin-left:3%; margin-right:3%;page-break-inside: never;}

      .footer-wrap h3{top:-40px !important;position:relative;}

       ul{

      margin:20px 0 0 0!important;

      padding:0 !important;

      }	

     ul li, li, ol li{

      color:#000; 

      font-family:Arial, Helvetica, sans-serif; 

      font-size:15px;

      font-weight:normal; 

      margin:0 0 5px 15px!important;

      line-height:22px;

      padding:0 !important;

      

     }
     .h3tag h3{
      color:#003f86;
      font-family:Arial, Helvetica, sans-serif;
      font-size:19px;
      font-weight:normal; 
      margin:30px 0 20px 0;
      line-height:normal; 
      padding:0;
      text-align:left;
     }
            </style>

        </head>

        <body>

            <!-- Define header and footer blocks before your content -->

           <div class="footer">

      <div class="footer-wrap">

            <h3 style="color:#666; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:normal; margin:0;line-height:normal;text-transform:uppercase; padding:0 0 10px 0;text-align:left; width:100%;">Thank You For Your Business</h3>

            <table width="100%"  cellpadding="0" cellspacing="0" border="0" style="  padding:0; margin-top:-20px !important; ">

         

                           <tr>

                                  <td align="left" height="22"><p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal; text-align:left;">39/878A2 Palarivattom, Kochi- 682025</p>

                                 

                                  

                                  </td>

                                  <td align="left" height="22"><p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal; text-align:left;">0484 2887207</p>

                                  

                                  

                                  </td>

                            </tr>

                            <tr>

                                

                                <td align="left" height="22">

                                <p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 ;line-height:normal;text-align:left;"> sales@biomedicalengineeringcompany.com</p> 

                                </td>

                                <td align="left" height="22">

                                <p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 ;line-height:normal;text-align:left;"> www.biomedicalengineeringcompany.com</p>

                                </td>

                            </tr>

                     

               

                                                    

        </table>



            </div>

        </div>

          

            

            <!-- Wrap the content of your PDF inside a main tag -->

            <main>

            

        

<table width="100%" cellpadding="0" cellspacing="0" border="0"  align="center" style="">

                  <tr>

                      <td>

                          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="">

              <tr>

                                  <td>

                                      <table width="100%"   cellpadding="0" cellspacing="0" border="0" style="background-color: #91d7f8;  padding:0; margin-top:-125px;">

                                          <tr valign="top">

                                              <td align="left" >

                                                  <img src="'.asset("images/head-main.png").'"  alt="" width="800"   style="margin:0;padding:0;">

                                              </td>

                                              

                                          </tr>

                                      </table>

                                  </td>

                              </tr>';



                            $html .= '  </table>

                            </td>

                        </tr>

                   </table>

                   

            

            </main>
          <div class="otherpages">

                   
            <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" style="background: #fff; margin:0 auto;">
            <tr>
               <td >
                  <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;">
                     
                              <tr>
                                 <td width="100%" colspan="3">
                                    <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #ccc; margin:0 auto;">
                                       <tr>
                                          <td height="1"  style="background: #000; width: 100%;"></td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;">
                                                <tr>
                                                   <td align="left" width="15"></td>
                                                   <td align="center">
                                                      <h2 style="font-family:Arial, Helvetica, sans-serif;color: #000;font-size: 20px;font-weight: bold;line-height: normal;margin:10px 0 ;">INVOICE</h2>
                                                   </td>
                                                   <td align="right" width="15"></td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;border:1px solid #000;">
                                                <tr>
                                                   <td align="left" width="15"></td>
                                                   <td align="left"  width="38%" style="border-right: 1px solid #000" >
                                                      <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                         <tr>
                                                            <td  height="10"></td>
                                                         </tr>
                                                         <tr>
                                                            <td>
                                                               <h2 style="font-family:Arial, Helvetica, sans-serif;color: #000;font-size: 18px;font-weight: bold;line-height: normal;margin:0 0 10px 0;">'.$user->business_name.' </h2>
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;">'.$transaction->user_address.'
                                                               </P>
                                                            </td>
                                                         </tr>
                                                        
                                                         <tr>
                                                            <td height="15"></td>
                                                         </tr>
                                                      </table>
                                                   </td>
                                                   <td width="30">
                                                   <td align="left" valign="top">
                                                      <table  width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                         <tr>
                                                            <td  height="10"></td>
                                                         </tr>
                                                         <tr>
                                                            <td>
                                                               <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                                  <tr>
                                                                     <td style="padding :5px; " >
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">Invoice No</P>
                                                                     </td>
                                                                     <td style="padding :5px; " >
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">: '.$invoice->invoice_id.'</P>
                                                                     </td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                            <td>
                                                               <table  width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                                  <tr>
                                                                     <td style="padding :5px; "  align="right">
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">Date</P>
                                                                     </td>
                                                                     <td style="padding :5px; "   align="left">
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">: '.$invoice->invoice_date.'</P>
                                                                     </td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                         </tr>

                                                         <tr>
                                                            <td  height="10"></td>
                                                         </tr>
                                                         <tr>
                                                            <td>
                                                               <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                                  <tr>
                                                                     <td style="padding :5px; " >
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">Shipping Address</P>
                                                                     </td>
                                                                     <td style="padding :5px; " >
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">: '.$transaction->user_shipping.'</P>
                                                                     </td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                            
                                                         </tr>
                                                        
                                                         
                                                         <tr>
                                                            <td  height="10"></td>
                                                         </tr>
                                                      </table>
                                                   </td>
                                                   <td align="left" width="15"></td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;">
                                                <tr>
                                                   <td align="left" >
                                                      <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                         <tr>
                                                            <th align="left"  style="border-left:1px solid #000;border-bottom:1px solid #000; padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Product</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                            <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">HSN</P>
                                                         </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Quantity</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Unit Price</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">CGST</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">SGST</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">IGST</P>
                                                            </th>
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Cess</P>
                                                            </th>

                                                           
                                                            <th align="left" style="border-left:1px solid #000;border-bottom:1px solid #000;padding :5px; border-right :1px solid #000;">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Amount</P>
                                                            </th>
                                                         </tr>';
                                                         $qty=0;
                                                         $cgst=0;
                                                         $sgst=0;
                                                         $igst=0;
                                                         $cess=0;
                                                         $amt=0;
                                                         foreach($invoice_product as $product)
                                                         {
                                                        $html .=' <tr>
                                                            <td align="left" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->product_name.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                            <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->hsn.'</P>
                                                         </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->quantity.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->sale_amount.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->cgst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->sgst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->igst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->cess.'</P>
                                                            </td>
                                                           
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; border-right :1px solid #000;">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$product->amt.'</P>
                                                            </td>
                                                         </tr>';
                                                         $qty +=$product->quantity;
                                                         $cgst +=$product->cgst;
                                                         $sgst +=$product->sgst;
                                                         $igst +=$product->igst;
                                                         $cess +=$product->cess;
                                                         $amt +=$product->amt;
                                                         }
                                                         $html .=' <tr>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 14px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Sub Total</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"></P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;">'.$qty.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"> </P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"> '.$cgst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"> '.$sgst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"> '.$igst.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"> '.$cess.'</P>
                                                            </td>
                                                            <td align="right" style="border-left:1px solid #000;padding :5px; border-right :1px solid #000;">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;"></P>
                                                            </td>
                                                         </tr>
                                                        
                                                        
                                                      </table>
                                                   </td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;">
                                                <tr>
                                                   <td align="left" >
                                                      <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                         <tr>
                                                            <td align="left" style="border-top:1px solid #000;border-left:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">Rupees :</P>
                                                            </td>
                                                            <td align="left" style="border-top:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">'.ucfirst($this->convert_number_to_words($amt)).'</P>
                                                            </td>
                                                            <td align="right" style="border-top:1px solid #000;border-right:1px solid #000;padding :5px; ">
                                                               <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin: 0;font-weight: bold;">'.$amt.'</P>
                                                            </td>
                                                         </tr>
                                                      </table>
                                                   </td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0 auto;border:1px solid #000;">
                                                <tr>
                                                   <td align="left" width="15"></td>
                                                   <td align="left"  width="100%" style="border-right: 1px solid #000" >
                                                      <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                         <tr>
                                                            <td  height="10"></td>
                                                         </tr>
                                                        
                                                         <tr>
                                                            <td>
                                                               <table  width="100%" border="0" align="left" cellspacing="0" cellpadding="0"   style="background: #fff; margin:0;">
                                                                  <tr>
                                                                     <td align="left">
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin-bottom:5px; "><span style="font-weight: bold;">Terms Conditions</span></P>
                                                                        <P style="color: #000; font-size: 13px;font-family:Arial, Helvetica, sans-serif;line-height: normal;margin:0;">Lorem Ipsum is simply dummy
                                                                           Printing and typesetting industry.
                                                                           Text of the printing and typesetting industry. Lorem Ipsum is simply dummy
                                                                           Printing and typesetting industry.
                                                                        </P>
                                                                     </td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td height="15"></td>
                                                         </tr>
                                                      </table>
                                                   </td>
                                                 
                                                  
                                                  
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                      
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
              
                </div>
     
            

        </body>

    </html>

';

$pdf = PDF::loadHTML($html);



      

      

       return $pdf->stream();


    }

   public function convert_number_to_words($number) {

      $hyphen      = '-';
      $conjunction = ' and ';
      $separator   = ', ';
      $negative    = 'negative ';
      $decimal     = ' point ';
      $dictionary  = array(
          0                   => 'zero',
          1                   => 'one',
          2                   => 'two',
          3                   => 'three',
          4                   => 'four',
          5                   => 'five',
          6                   => 'six',
          7                   => 'seven',
          8                   => 'eight',
          9                   => 'nine',
          10                  => 'ten',
          11                  => 'eleven',
          12                  => 'twelve',
          13                  => 'thirteen',
          14                  => 'fourteen',
          15                  => 'fifteen',
          16                  => 'sixteen',
          17                  => 'seventeen',
          18                  => 'eighteen',
          19                  => 'nineteen',
          20                  => 'twenty',
          30                  => 'thirty',
          40                  => 'fourty',
          50                  => 'fifty',
          60                  => 'sixty',
          70                  => 'seventy',
          80                  => 'eighty',
          90                  => 'ninety',
          100                 => 'hundred',
          1000                => 'thousand',
          100000             => 'lakh',
          10000000          => 'crore'
      );
  
      if (!is_numeric($number)) {
          return false;
      }
  
      if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
          // overflow
          trigger_error(
              'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
              E_USER_WARNING
          );
          return false;
      }
  
      if ($number < 0) {
          return $negative . $this->convert_number_to_words(abs($number));
      }
  
      $string = $fraction = null;
  
      if (strpos($number, '.') !== false) {
          list($number, $fraction) = explode('.', $number);
      }
  
      switch (true) {
          case $number < 21:
              $string = $dictionary[$number];
              break;
          case $number < 100:
              $tens   = ((int) ($number / 10)) * 10;
              $units  = $number % 10;
              $string = $dictionary[$tens];
              if ($units) {
                  $string .= $hyphen . $dictionary[$units];
              }
              break;
          case $number < 1000:
              $hundreds  = $number / 100;
              $remainder = $number % 100;
              $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
              if ($remainder) {
                  $string .= $conjunction . $this->convert_number_to_words($remainder);
              }
              break;
          case $number < 100000:
              $thousands   = ((int) ($number / 1000));
              $remainder = $number % 1000;
  
              $thousands = $this->convert_number_to_words($thousands);
  
              $string .= $thousands . ' ' . $dictionary[1000];
              if ($remainder) {
                  $string .= $separator . $this->convert_number_to_words($remainder);
              }
              break;
          case $number < 10000000:
              $lakhs   = ((int) ($number / 100000));
              $remainder = $number % 100000;
  
              $lakhs = $this->convert_number_to_words($lakhs);
  
              $string = $lakhs . ' ' . $dictionary[100000];
              if ($remainder) {
                  $string .= $separator . $this->convert_number_to_words($remainder);
              }
              break;
          case $number < 1000000000:
              $crores   = ((int) ($number / 10000000));
              $remainder = $number % 10000000;
  
              $crores = $this->convert_number_to_words($crores);
  
              $string = $crores . ' ' . $dictionary[10000000];
              if ($remainder) {
                  $string .= $separator . $this->convert_number_to_words($remainder);
              }
              break;
          default:
              $baseUnit = pow(1000, floor(log($number, 1000)));
              $numBaseUnits = (int) ($number / $baseUnit);
              $remainder = $number % $baseUnit;
              $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
              if ($remainder) {
                  $string .= $remainder < 100 ? $conjunction : $separator;
                  $string .= $this->convert_number_to_words($remainder);
              }
              break;
      }
  
      if (null !== $fraction && is_numeric($fraction)) {
          $string .= $decimal;
          $words = array();
          foreach (str_split((string) $fraction) as $number) {
              $words[] = $dictionary[$number];
          }
          $string .= implode(' ', $words);
      }
  
      return $string;
  }




}

