<?php
namespace App\Http\Controllers\staff;

use App\Transation;
use App\ServicePart;

use App\Transation_product;
use App\Credit;
use App\Invoice_complete_flow;
use App\Product;

use App\Transation_pocopy;
use App\Transation_staff_updates;
use App\Transaction_manage_staff;
use App\Staff;

use App\Hosdesignation;
use App\Courier;

use App\Invoice_product;
use App\Chatter;

use App\Company;
use App\Contact_person;
use App\Users_shipping_address;

use App\Oppertunity;
use App\Invoice;
use App\Dispatch;
use App\Dispatch_product;



use App\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



use App\Http\Controllers\Controller;
use App\Warehouse;
use Image;

use Storage;



class TransationController extends Controller

{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
      
        $transation = Transation::all()->sortByDesc("id");
        return view('staff.transation.index', compact('transation'));
    }


    public function transactionindex()
    {
       

       $staff_id = session('STAFF_ID');
       //$transation_pending = Transation_staff_updates::where('staff_id',$staff_id)->where('current_status','Pending')->orwhere('current_status','Verification')->orderBy('transation_id','DESC')->get();
       $transation_pending =  DB::select("select * from  transation_staff_updates where staff_id='".$staff_id."' AND (current_status='Pending' OR current_status='Verification') order by transation_id desc");
     
        $transation_approval = Transation_staff_updates::where('current_status', 'Approval')->where('staff_id',$staff_id)->orderBy('transation_id','DESC')->get();
        return view('staff.transation.transactionindex', compact('transation_pending','transation_approval'));
 
    }

    public function Pendingtransaction()
    {
       
      $staff_id = session('STAFF_ID');
      if(isset($_GET['transaction_type']))
      {
           $transaction_type= $_GET['transaction_type'];
        $transation_pending =  DB::select("select * from  transation_staff_updates where staff_id='".$staff_id."' AND (current_status='Pending' OR current_status='Verification') AND status='".$transaction_type."' order by transation_id desc");
      }
      else{$transaction_type='';
        $transation_pending =  DB::select("select * from  transation_staff_updates where staff_id='".$staff_id."' AND (current_status='Pending' OR current_status='Verification') order by transation_id desc");
      }
       
      return view('staff.transation.Alltransaction', compact('transation_pending','transaction_type'));
 
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()

    {

        $parents = Transation::orderBy('id', 'asc')->get();

        $user 				= User::all();



        $oppertunity 		= Chatter::select('oppertunity_id')->where('deal_stage',8)->get();

		//$oppertunity 		= Prospectus::select('oppertunity_id')->where('deal_stage',8)->get();

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
        $staff = DB::table('staff')

        ->orderBy('name', 'asc')

        ->select('name','id')

        ->get();


      $hosdesignation = Hosdesignation::all();

       $staff = Staff::all();

        return view('staff.transation.create', ['staff'=> $staff,'country'=> $country,'parents'=> $parents,'user'=>$user,'oppertunity'=>$op_name,'products'=>$products,'state'=>$state,'district'=>$district,'company'=>$company,'hosdesignation'=>$hosdesignation,'staff'=>$staff]);

    }



    
    
    public function store(Request $request)
    {
       
        $this->validate($request, array(
            'user_id' => 'required|max:100'
         ));

    $transation = new Transation;
    $transation->tran_type = $request->tran_type;
    $transation->type_conf = $request->type_conf;
    $transation->user_id = $request->user_id;
    $transation->company_id = $request->company_id;
    $transation->status = $request->status;
    $transation->sales_type = $request->sales_type;
    if($request->sales_type=="Existed Transaction")
    {
        $transation->parent_id = $request->exit_trans_id;
        $transation->inandout = $request->inandout;
        $transation->add_product_condition = $request->add_product_condition;
        $transation->description_exit_trans = $request->description_exit_trans;
        $transation->collect_date = $request->collect_date;
        $transation->user_address = $request->user_address;
        $transation->user_shipping = $request->user_shipping;
        $transation->contact_id = $request->contact_id;
        $transation->designation = $request->designation;
        $transation->department_id = $request->department_id;
        $transation->contact_phone = $request->contact_phone;
        $transation->contact_mail = $request->contact_mail;
        $transation->gst = $request->gst;
        $transation->owner = $request->owner;
        $transation->second_owner = $request->second_owner;
        $transation->po = $request->po;
        $transation->po_date = $request->po_date;
        $transation->stan_warrenty = $request->stan_warrenty;
        $transation->add_warrenty = $request->add_warrenty;
        $transation->payment_terms = $request->payment_terms;
        $transation->del_terms = $request->del_terms;
        $transation->expect_date = $request->expect_date;
        $transation->mode_dispatch = $request->mode_dispatch;
        $transation->other_terms = $request->other_terms;
        $transation->attach_gst = $request->attach_gst;
        
    }
    else{
        $transation->inandout = '';
        $transation->add_product_condition = '';
        $transation->description_exit_trans = '';
    }
    

    if($request->user_id>0)
    {
        $user = User::find($request->user_id);
        $transation->state_id = $user->state_id;
        $transation->district_id = $user->district_id;
    }
    $transation->current_status = 'Technical Approval';
     $transation->save();
   
     if($transation->id>0)
     {
        
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $transation->id;
        $trans_staff_update->status = 'Technical Approval';
        $trans_staff_update->current_status = 'Pending';
        $trans_staff_update->added_date = date("y-m-d");
        $mange_staff=Transaction_manage_staff::where('manage_section','Technical Approval')->first();
        $trans_staff_update->staff_id = $mange_staff->staff_id;
        $trans_staff_update->user_id = $request->user_id;;
        $trans_staff_update->type_approval ='Sale Order';
        $trans_staff_update->save();
      

        $product_count=count($request->product_id);
        if($product_count>0)
        {

           for($i=0;$i<$product_count;$i++)
           {
            $transation_product = new Transation_product;
            $transation_product->transation_id = $transation->id;
            $transation_product->product_id = $request->product_id[$i];
            $product_det=Product::where('id',$request->product_id[$i])->first();
            $transation_product->product_name = $product_det->name;
           // $transation_product->warrenty_product = $request->warrenty_product[$i];
            $transation_product->quantity = $request->quantity[$i];
            $transation_product->sale_amount = $request->sale_amount[$i];
            $transation_product->hsn = $request->hsn[$i];
            $transation_product->cgst = $request->cgst[$i];
            $transation_product->sgst = $request->sgst[$i];
            $transation_product->igst = $request->igst[$i];
            $transation_product->cess = $request->cess[$i];
          //  $transation_product->foc = $request->foc[$i];
            $transation_product->msp = $request->msp[$i];
            $transation_product->amt = $request->amt[$i];
            $transation_product->tax_percentage = $request->tax_percentage[$i];
            
            $transation_product->surplus_amt = $request->surplus_amt[$i];
            
            $transation_product->save();


           }
        }


      



     }

  
    return redirect()->route('staff.transation.edit', $transation->id);

    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Transation  $brand
     * @return \Illuminate\Http\Response
     */

    public function show(Transation $transation)

    {
        $type=$_REQUEST['type'];
        
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



    		$op_name        = Oppertunity::select('id','oppertunity_name')->whereIn('id',$op_id)->where('user_id',$transation->id)->get();

    	}

    	else

    	{

    		$op_name 		= array();

        }
        $contact_persons = DB::select("select * from contact_person where user_id='".$transation->user_id."' order by id asc");
        
        $user_details 				=User::find($transation->user_id);
        return view('staff.transation.show', compact('type','user_details','contact_persons','products_warenty','transation_pocopy','transation_product','country','transation','user','op_name','products','state','district','company','staff'));


    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transation  $brand
     * @return \Illuminate\Http\Response
     */

    public function edit(Transation $transation)
    {
       $user 				= User::all();

       $oppertunity 		= Chatter::select('oppertunity_id')->where('deal_stage',8)->get();
       $invoice 		= Invoice::where('user_id',$transation->user_id)->get();
       
       
       $products           = Product::all();
       $invoice_complete_flow           = Invoice_complete_flow::all();
       

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



           $op_name        = Oppertunity::select('id','oppertunity_name')->whereIn('id',$op_id)->where('user_id',$transation->id)->get();

       }

       else

       {

           $op_name 		= array();

       }
       $contact_persons = DB::select("select * from contact_person where user_id='".$transation->user_id."' order by id asc");
     
       $user_details 				=User::find($transation->user_id);
       
       return view('staff.transation.edit', compact('invoice','invoice_complete_flow','user_details','contact_persons','products_warenty','transation_pocopy','transation_product','country','transation','user','op_name','products','state','district','company','staff'));

   }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transation  $brand
     * @return \Illuminate\Http\Response
     */

    
    public function update(Request $request, $id)
        {

      /*  $this->validate($request, array(
            'company_id' => 'required|max:100'
         ));
*/

    $transation = Transation::find($id);
  
    $transation->status = $request->status;
    $transation->company_id = $request->company_id;
    
    $transation->owner = $request->owner;
    $transation->second_owner = $request->second_owner;
    $transation->po = $request->po;
    $transation->po_date = $request->po_date;
    

    if($request->attach_gst!='')
    {
    $file      = $request->attach_gst;
    $imageName = time().$file->getClientOriginalName();
    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
    $path      =  storage_path();
    $img_path  = $file->storeAs('public/transation', $imageName);
    $path      = $path.'/app/'.$img_path;
    chmod($path, 0777);
    $transation->attach_gst= $imageName;
    }

  

     $transation->save();
    if($request->owner>0)
    {
    $exit_staff=Transation_staff_updates::where('transation_id',$transation->id)->where('status','Owner')->get(); 
    if(count($exit_staff)==0){
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $transation->id;
        $trans_staff_update->status = 'Owner';
        $trans_staff_update->current_status = 'Approval';
        $trans_staff_update->added_date = date("y-m-d");
        $trans_staff_update->approved_date = date("y-m-d");
        $trans_staff_update->staff_id =$request->owner;
        $trans_staff_update->user_id =$request->user_id;
        $trans_staff_update->type_approval ='Sale Order';
        $trans_staff_update->save();
    }else{
         
        $trans_staff_update = Transation_staff_updates::find($exit_staff[0]->id);
        $trans_staff_update->transation_id = $transation->id;
        $trans_staff_update->status = 'Owner';
        $trans_staff_update->added_date = date("y-m-d");
        $trans_staff_update->approved_date = date("y-m-d");
        $trans_staff_update->current_status = 'Approval';
        $trans_staff_update->staff_id =$request->owner;
        $trans_staff_update->user_id =$request->user_id;
        $trans_staff_update->type_approval ='Sale Order';
        $trans_staff_update->save();
    }  
   
    }
    if($request->second_owner>0)
    {
        $exit_staff=Transation_staff_updates::where('transation_id',$transation->id)->where('status','Secondary Owner')->get(); 
        if(count($exit_staff)==0){
            $trans_staff_update = new Transation_staff_updates;
            $trans_staff_update->transation_id = $transation->id;
            $trans_staff_update->status = 'Secondary Owner';
            $trans_staff_update->current_status = 'Approval';
            $trans_staff_update->added_date = date("y-m-d");
            $trans_staff_update->approved_date = date("y-m-d");
            $trans_staff_update->staff_id =$request->second_owner;
            $trans_staff_update->user_id =$request->user_id;
            $trans_staff_update->type_approval ='Sale Order';
            $trans_staff_update->save();
        }else{
            $trans_staff_update = Transation_staff_updates::find($exit_staff[0]->id);
            $trans_staff_update->transation_id = $transation->id;
            $trans_staff_update->status = 'Secondary Owner';
            $trans_staff_update->current_status = 'Approval';
            $trans_staff_update->added_date = date("y-m-d");
            $trans_staff_update->approved_date = date("y-m-d");
            $trans_staff_update->staff_id =$request->second_owner;
            $trans_staff_update->user_id =$request->user_id;
            $trans_staff_update->type_approval ='Sale Order';
            $trans_staff_update->save();
        }
   
    }
     

     if($transation->id>0)
     {
      

        $product_count=count($request->product_id);
        if($product_count>0)
        {

           for($i=0;$i<$product_count;$i++)
           {
            if($request->transation_product_id[$i]>0)
            {
                $transation_product = Transation_product::find($request->transation_product_id[$i]);
            }
            else{
                $transation_product = new Transation_product;
            }

            $transation_product->transation_id = $transation->id;
            $transation_product->product_id = $request->product_id[$i];
            $product_det=Product::where('id',$request->product_id[$i])->first();
            $transation_product->product_name = $product_det->name;
          //  $transation_product->warrenty_product = $request->warrenty_product[$i];
            $transation_product->quantity = $request->quantity[$i];
            $transation_product->sale_amount = $request->sale_amount[$i];
            $transation_product->hsn = $request->hsn[$i];
            $transation_product->cgst = $request->cgst[$i];
            $transation_product->sgst = $request->sgst[$i];
            $transation_product->igst = $request->igst[$i];
            $transation_product->cess = $request->cess[$i];
          //  $transation_product->foc = $request->foc[$i];
            $transation_product->msp = $request->msp[$i];
            $transation_product->amt = $request->amt[$i];
            $transation_product->tax_percentage = $request->tax_percentage[$i];
            $transation_product->surplus_amt = $request->surplus_amt[$i];
            $transation_product->save();


           }
        }




        if($request->photo)
        {


        $product_photo=count($request->photo);
        if($product_photo>0)
        {

           for($i=0;$i<$product_photo;$i++)
           {

            $file      = $request->photo[$i];
            $imageName = time().$file->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path      =  storage_path();
            $img_path  = $file->storeAs('public/transation', $imageName);
            $path      = $path.'/app/'.$img_path;
            chmod($path, 0777);

            $transation_pocopy = new Transation_pocopy;
            $transation_pocopy->transation_id = $transation->id;
            $transation_pocopy->image_name = $imageName;

            $transation_pocopy->save();





           }
        }

        }

        



     }

     
     if($request->transaction_type!='')
     {
        return redirect('staff/transation/' . $id . '/edit?type='.$request->transaction_type);
     }
     else{
        return redirect('staff/transation/' . $id . '/edit');
     }
    // return redirect()->route('admin.transation.index')->with('success', 'Data successfully saved.');

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

    public function save_shipping_address_user(Request $request)
    {
        $users_shipping = new Users_shipping_address;
        $users_shipping->user_id=$request->user_id;
        $users_shipping->country_id=$request->shipping_country_id;
        $users_shipping->address1=$request->shipping_address1;
        $users_shipping->address2=$request->shipping_address2;
        $users_shipping->city=$request->shipping_city;
        $users_shipping->state=$request->shipping_state;
        $users_shipping->zip=$request->shipping_zip;

        $users_shipping->save();
    }
    public function select_shipping_address_user(Request $request)
    {
        $ship_address =DB::select("SELECT * from users_shipping_address
        where  user_id='".$request->user_id."' order by id ASC"  );
       $hmls='';
        if(count($ship_address)>0)
        {
           $i=0;
            foreach($ship_address as $values){
                if($i==0)
                {
                    $hmls .='<input type="radio" name="address_option" id="address_option'.$values->id.'" value="'.$values->id.'" checked>';
                }
                else{
                    $hmls .='<input type="radio" name="address_option" id="address_option'.$values->id.'" value="'.$values->id.'" >';
                }

                $hmls .='<div class="address_option" id="addrsec'.$values->id.'">';

                if($values->address1!='')
                {
                    $hmls .=$values->address1.',';
                }
                if($values->address2!='')
                {
                    $hmls .=$values->address2.',';
                }
                if($values->city!='')
                {
                    $hmls .=$values->city.',';
                }
                if($values->state!='')
                {
                    $hmls .=$values->state.',';
                }
                if($values->zip!='')
                {
                    $hmls .=$values->zip;
                }

                $hmls .='</div>';
                $i++;
            }
            $hmls .='  ';

        }else{
            $hmls='Shipping address not found';
        }
        echo $hmls;
    }


    public function approval_transation(Request $request)

    {
        if($request->id>0)
        {
            $transation = Transation::find($request->id);
            if($request->type_approval=="approval_company")
            {
                $transation->approval_company ="Y";
            }
            if($request->type_approval=="approval_product")
            {
                $transation->approval_product ="Y";
            }
            if($request->type_approval=="approval_config")
            {
                $transation->approval_config ="Y";
            }
            if($request->type_approval=="approval_customer")
            {
                $transation->approval_customer ="Y";
            }

            if($request->type_approval=="approval_payment_terms")
            {
                $transation->approval_payment_terms ="Y";
            }
            if($request->type_approval=="approval_delivery_terms")
            {
                $transation->approval_delivery_terms ="Y";
            }
            if($request->type_approval=="stock_terms")
            {
                $transation->stock_terms ="Y";
            }
            

            $transation->save();
        }



    }
    public function delete_product_transation(Request $request)
    {
       // $transation_del= Transation_product::select('id')->where('product_id',$request->product_id)->where('transation_id',$request->transation_id)->get();
        //print_r($transation_del);
        /*$user = Staff::find($id);
        $user->delete();*/
        $transation_del=  DB::table('transation_product')->where('product_id',$request->product_id)->where('transation_id',$request->transation_id)->delete();
    }
    public function save_transation_insentive(Request $request)
    {

        DB::update(" UPDATE `transation_product` SET `insentive`='".$request->insentive."' WHERE  product_id='".$request->product_id."' AND transation_id='".$request->transation_id."' ");
    }

    public function approval_transation_mspowner(Request $request)
    {

        DB::update(" UPDATE `transation` SET `approval_msp_owner`='Y',per_owner='".$request->owner_value."',per_second_owner='".$request->secondowner_value."' WHERE  id='".$request->id."' ");
    }

    public function update_qty_transation(Request $request)
    {

        DB::update(" UPDATE `transation_product` SET `quantity`='".$request->qty."' WHERE   product_id='".$request->product_id."' AND transation_id='".$request->transation_id."' ");
    }

    public function change_transation_type_oppurtunity(Request $request)
    {
        
        $oppertunity        = Oppertunity::select('id','oppertunity_name')->where('deal_stage',8)->where('user_id',$request->user_id)->get();
        echo json_encode($oppertunity);
    }

    public function get_test_retun_product(Request $request)
    {
        
        $products = ServicePart::with('servicePartProduct')->where('service_part_status', 'test')->where('status', 'Approved')->get();
        return response()->json([ 'products' => $products ]);
    }
    public function get_all_product(Request $request)
    {
        
        $products= Product::all()->map->only('id', 'name');
        return response()->json([ 'products' => $products ]);
    }
    
    
    public function save_config_transation(Request $request)
    {
        DB::update(" UPDATE `transation` SET `add_warrenty`='".$request->add_warrenty."',`stan_warrenty`='".$request->stan_warrenty."' WHERE    id='".$request->id."' ");
    }
    public function save_other_transation(Request $request)
    {
        DB::update(" UPDATE `transation` SET `other_terms`='".$request->other_terms."' WHERE    id='".$request->id."' ");
    }
    
    public function save_delivery_transation(Request $request)
    {
        DB::update(" UPDATE `transation` SET `del_terms`='".$request->del_terms."',`expect_date`='".$request->expect_date."' WHERE    id='".$request->id."' ");
    }

    public function save_payment_transation(Request $request)
    {
        DB::update(" UPDATE `transation` SET `payment_terms`='".$request->payment_terms."' WHERE    id='".$request->id."' ");
    }
    public function save_po_transation(Request $request)
    {

        DB::update(" UPDATE `transation` SET `contact_mail`='".$request->contact_mail."',`contact_phone`='".$request->contact_phone."',
        `collect_date`='".$request->collect_date."',`user_address`='".$request->user_address."',
        `user_shipping`='".$request->user_shipping."',`contact_id`='".$request->contact_id."',
        `designation`='".$request->designation."' WHERE    id='".$request->id."' ");
    }
    public function save_certifi_transation(Request $request)
    {

        DB::update(" UPDATE `transation` SET `owner`='".$request->owner."',`second_owner`='".$request->second_owner."',
        `po`='".$request->po."',`po_date`='".$request->po_date."' WHERE    id='".$request->id."' ");
    }
    public function view_transation_all_product(Request $request)
    {
        $productsdet =DB::select("SELECT products.name as name,products.image_name,tran_pro.quantity,tran_pro.sale_amount,tran_pro.hsn,tran_pro.cgst,tran_pro.sgst,tran_pro.igst,tran_pro.cess,tran_pro.foc,tran_pro.msp,tran_pro.amt,tran_pro.surplus_amt,tran_pro.product_id
         from transation_product as tran_pro inner join products as products ON 
        products.id=tran_pro.product_id
        where  tran_pro.transation_id='".$request->transation_id."' order by tran_pro.id ASC"  );
        echo json_encode($productsdet);
    }
    public function get_sort_product_transaction(Request $request)
    {
        $product=implode(',',$request->product_id);
        $productsdet =DB::select("SELECT id,name from products 
        where  id NOT IN (".$product.")"  );
        
        echo json_encode($productsdet);

    }
    

    public function sales_order()
    {
        if(isset($_GET['type']))
        {
            $type=$_GET['type'];
        }
        else{
            $type='';
        }
        $all_transaction = Transation::orderBy('id', 'asc')->get();
        $user 				= User::all();
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
        $staff = DB::table('staff')

        ->orderBy('name', 'asc')

        ->select('name','id')

        ->get();


      $hosdesignation = Hosdesignation::all();

       $staff = Staff::all();
       $contact_person = Contact_person::all();
       
        return view('staff.transation.sales_order', ['type'=>$type,'contact_person'=> $contact_person,'staff'=> $staff,'country'=> $country,'all_transaction'=> $all_transaction,'user'=>$user,'oppertunity'=>$op_name,'products'=>$products,'state'=>$state,'district'=>$district,'company'=>$company,'hosdesignation'=>$hosdesignation,'staff'=>$staff]);

    }

    

    public function approval_transaction_staff(Request $request)
    {
        $transaction = Transation::where('id',$request->trans_id)->first();
        if($request->status=="Technical Approval")
        {
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $request->trans_id;
        $trans_staff_update->status = 'MSP Approval';
        $trans_staff_update->current_status = 'Pending';
        $mange_staff=Transaction_manage_staff::where('manage_section','MSP Approval')->first();
        $trans_staff_update->staff_id = $mange_staff->staff_id;
        $trans_staff_update->user_id =$transaction->user_id;
        $trans_staff_update->save();
        
        DB::update(" UPDATE `transation` SET `current_status`='MSP Approval' WHERE    id='".$request->trans_id."'  ");
        }
        if($request->status=="MSP Approval")
        {
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $request->trans_id;
        $trans_staff_update->status = 'Financial Approval';
        $trans_staff_update->current_status = 'Pending';
        $mange_staff=Transaction_manage_staff::where('manage_section','Financial Approval')->first();
        $trans_staff_update->staff_id = $mange_staff->staff_id;
        $trans_staff_update->user_id =$transaction->user_id;
        $trans_staff_update->save();
        DB::update(" UPDATE `transation` SET `current_status`='Financial Approval' WHERE    id='".$request->trans_id."'  ");
        }
        if($request->status=="Financial Approval")
        {
        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $request->trans_id;
        $trans_staff_update->status = 'Invoice';
        $trans_staff_update->current_status = 'Pending';
        $trans_staff_update->user_id =$transaction->user_id;
        $mange_staff=Transaction_manage_staff::where('manage_section','Invoice')->first();
        $trans_staff_update->staff_id = $mange_staff->staff_id;
        $trans_staff_update->save();

        $trans_staff_update = new Transation_staff_updates;
        $trans_staff_update->transation_id = $request->trans_id;
        $trans_staff_update->status = 'Dispatch Invoice';
        $trans_staff_update->current_status = 'Pending';
        $trans_staff_update->user_id =$transaction->user_id;
        $mange_staff=Transaction_manage_staff::where('manage_section','Dispatch Invoice')->first();
        $trans_staff_update->staff_id = $mange_staff->staff_id;
        $trans_staff_update->save();

        DB::update(" UPDATE `transation` SET invoice_complete_flow_id='1',financial_approval_status='Completed' WHERE    id='".$request->trans_id."'  ");
        }

        $staff_id = session('STAFF_ID');
        
        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',`approved_by`='".$staff_id."' WHERE   status='".$request->status."' AND  transation_id='".$request->trans_id."'  ");
    }

    
    public function create_dispatch($id)
    {
        $courier = Courier::all();
        $staff = Staff::orderBy('name', 'asc')->get();
        $warehouse = Warehouse::all();
        $invoice_product=Invoice_product::where('invoice_id',$id)->get();
        return view('staff.transation.create_dispatch', ['invoice_product'=>$invoice_product,'warehouse'=> $warehouse,'courier'=> $courier,'staff'=> $staff,'invoice_id'=>$id]);
    
    }
    public function dispatch_verify($id)
    {
      
        $dispatch=Dispatch::where('id',$id)->first();
        
        $dispatch_product=Dispatch_product::where('dispatch_id',$id)->get();
        $transaction=Transation::where('id',$dispatch->transation_id)->first();
        if($dispatch->invoice_id>0)
        {
            //$invoice = Invoice::where('id',$id)->first();
            $invoice_id=$dispatch->invoice_id;
        }
        else{
            $invoice = '';
            $invoice_id=0;
        }
        
        $user = User::where('id',$transaction->user_id)->first();
        $courier = Courier::where('id',$dispatch->courier_id)->first();
        
        return view('staff.transation.dispatch_verify', ['invoice_id'=>$invoice_id,'courier'=>$courier,'user'=>$user,'dispatch'=> $dispatch,'dispatch_product'=> $dispatch_product]);
    
    }

    public function dispatch_verify_view($id)
    {
        $invoice = Invoice::where('id',$id)->first();
        $user = User::where('id',$invoice->user_id)->first();
        $dispatch=Dispatch::where('invoice_id',$id)->first();
        $dispatch_product=Dispatch_product::where('dispatch_id',$dispatch->id)->get();
        $courier = Courier::where('id',$dispatch->courier_id)->first();
        
        return view('staff.transation.dispatch_verify_view', ['invoice_id'=>$id,'courier'=>$courier,'user'=>$user,'dispatch'=> $dispatch,'dispatch_product'=> $dispatch_product]);
    
    }

    

    public function delivery_approve(Request $request)
    {
        
        $tran_staff_up = Transation_staff_updates::find($request->tran_staff_id);
         if($request->upload_eway_bill!='')
         {
         $file      = $request->upload_eway_bill;
         $imageName = time().$file->getClientOriginalName();
         $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
         $path      =  storage_path();
         $img_path  = $file->storeAs('public/transation', $imageName);
         $path      = $path.'/app/'.$img_path;
         chmod($path, 0777);
         $tran_staff_up->upload_del_bill= $imageName;
         }
         
         $tran_staff_up->comment=$request->comment;
         $tran_staff_up->del_date=$request->del_date;
         $tran_staff_up->contact_person_id=$request->contact_person_id;
         $tran_staff_up->current_status='Approval';
         $tran_staff_up->save();


         $invoice=Invoice::where('id',$tran_staff_up->invoice_id)->first();
        $transation=Transation::where('id',$invoice->transaction_id)->first();

        $invoice_complete_flow = Invoice_complete_flow::find($transation->invoice_complete_flow_id);
    
    $invoice_update = Invoice::find($tran_staff_up->invoice_id);
    $invoice_update->status = 'User Confirmation';
    $user_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->user_confirm.' days'));
    $invoice_update->user_date = $user_date;
    $department_date=date('Y-m-d', strtotime($user_date. ' + '.$invoice_complete_flow->dept_confirm.' days'));
    $invoice_update->department_date=$department_date;
    $finance_date=date('Y-m-d', strtotime($department_date. ' + '.$invoice_complete_flow->finance_confirm.' days'));
    $invoice_update->finance_date=$finance_date;
    $payment_date=date('Y-m-d', strtotime($finance_date. ' + '.$invoice_complete_flow->payment_confirm.' days'));
    $invoice_update->payment_date=$payment_date;
    $invoice_update->save();


    if($transation->owner>0)
    {
    $trans_staff_update = new Transation_staff_updates;
    $trans_staff_update->invoice_id = $tran_staff_up->invoice_id;
    $trans_staff_update->transation_id = $tran_staff_up->transation_id;
    
    $trans_staff_update->status = 'User Confirmation';
    $trans_staff_update->current_status = 'Pending';
    $trans_staff_update->added_date = date("y-m-d");
    $trans_staff_update->due_date =$user_date;
    
    $trans_staff_update->staff_id = $transation->owner;
    $trans_staff_update->user_id = $invoice->user_id;
    $trans_staff_update->type_approval ='Invoice';
    $trans_staff_update->save();
    }

    if($transation->second_owner>0)
    {
    $trans_staff_update = new Transation_staff_updates;
    $trans_staff_update->invoice_id = $tran_staff_up->invoice_id;
    $trans_staff_update->transation_id = $tran_staff_up->transation_id;
    $trans_staff_update->status = 'User Confirmation';
    $trans_staff_update->current_status = 'Pending';
    $trans_staff_update->due_date =$user_date;
    $trans_staff_update->added_date = date("y-m-d");
    $trans_staff_update->staff_id = $transation->second_owner;
    $trans_staff_update->user_id = $invoice->user_id;
    $trans_staff_update->type_approval ='Invoice';
    $trans_staff_update->save();
    }
    $staff_id = session('STAFF_ID');
    DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',approved_by='".$staff_id."'  WHERE  `status`='Delivery Invoice' AND  invoice_id='".$tran_staff_up->invoice_id."'  ");
        return redirect()->route('staff.Pendingtransaction')->with('success', 'Data successfully saved.');
    }

    public function after_user_approve(Request $request)
    {
      
        $tran_staff_up = Transation_staff_updates::find($request->tran_staff_id_user);
        $tran_staff_up->comment=$request->comment_user;
        $tran_staff_up->bank_details=$request->bank_details;
        $tran_staff_up->contact_person_id=$request->user_contact_person_id;
        
         $tran_staff_up->current_status='Approval';
         $tran_staff_up->save();

         $invoice=Invoice::where('id',$tran_staff_up->invoice_id)->first();
         $transation=Transation::where('id',$invoice->transaction_id)->first();
 
         $invoice_complete_flow = Invoice_complete_flow::find($transation->invoice_complete_flow_id);
     
     $invoice_update = Invoice::find($tran_staff_up->invoice_id);
     $staff_id = session('STAFF_ID');
        if($tran_staff_up->status=="User Confirmation")
        {
        $status = 'Department Confirmation';
        $user_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->dept_confirm.' days'));
        $invoice_update->department_date=$user_date;
        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',approved_by='".$staff_id."'  WHERE  `status`='User Confirmation' AND  invoice_id='".$tran_staff_up->invoice_id."'  ");
        }
        if($tran_staff_up->status=="Department Confirmation")
        {
         
        $status = 'Finance Confirmation';
        $user_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->finance_confirm.' days'));
        $invoice_update->finance_date=$user_date;
        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',approved_by='".$staff_id."'  WHERE  `status`='Department Confirmation' AND  invoice_id='".$tran_staff_up->invoice_id."'  ");
        }

        if($tran_staff_up->status=="Finance Confirmation")
        {
        $status = 'Payment Confirmation';
        
    $user_date=date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$invoice_complete_flow->payment_confirm.' days'));
    $invoice_update->payment_date=$user_date;
    DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',approved_by='".$staff_id."'  WHERE  `status`='Finance Confirmation' AND  invoice_id='".$tran_staff_up->invoice_id."'  ");
       }

        if($tran_staff_up->status=="Payment Confirmation")
        {
        $status = 'Payment Confirmation';
        DB::update(" UPDATE `transation_staff_updates` SET `current_status`='Approval',approved_by='".$staff_id."'  WHERE  `status`='Payment Confirmation' AND  invoice_id='".$tran_staff_up->invoice_id."'  ");
        }


        /*************************** */
        $invoice_update->status = $status;  
   
    $invoice_update->save();

    if($tran_staff_up->status!="Payment Confirmation")
    {

    if($transation->owner>0)
    {
    $trans_staff_update = new Transation_staff_updates;
    $trans_staff_update->invoice_id = $tran_staff_up->invoice_id;
    $trans_staff_update->transation_id = $tran_staff_up->transation_id;
    $trans_staff_update->status = $status;
    $trans_staff_update->current_status = 'Pending';
    $trans_staff_update->added_date = date("y-m-d");
    $trans_staff_update->due_date =$user_date;
    
    $trans_staff_update->staff_id = $transation->owner;
    $trans_staff_update->user_id = $invoice->user_id;
    $trans_staff_update->type_approval ='Invoice';
    $trans_staff_update->save();
    }

    if($transation->second_owner>0)
    {
    $trans_staff_update = new Transation_staff_updates;
    $trans_staff_update->invoice_id = $tran_staff_up->invoice_id;
    $trans_staff_update->transation_id = $tran_staff_up->transation_id;
    $trans_staff_update->status = $status;
    $trans_staff_update->current_status = 'Pending';
    $trans_staff_update->due_date =$user_date;
    $trans_staff_update->added_date = date("y-m-d");
    $trans_staff_update->staff_id = $transation->second_owner;
    $trans_staff_update->user_id = $invoice->user_id;
    $trans_staff_update->type_approval ='Invoice';
    $trans_staff_update->save();
    }

}

   
    
return redirect()->route('staff.Pendingtransaction')->with('success', 'Data successfully saved.');
        
    }

    public function get_user_contact_details(Request $request)
    {
        $contact_person        = Contact_person::where('user_id',$request->user_id)->get();
        echo json_encode($contact_person);
    }

    public function transation_details($transaction_id)
    {
    $transation=Transation::where('id',$transaction_id)->first();
    $transation_product=Transation_product::where('transation_id',$transaction_id)->get();
    $invoice=Invoice::where('transaction_id',$transaction_id)->get();
    $dispatch=Dispatch::where('transation_id',$transaction_id)->get();
    $credit=Credit::where('transaction_id',$transaction_id)->get();
    return view('staff.transation.transation_details', compact('credit','transation','transation_product','invoice','dispatch'));
    }

    
    

    
}