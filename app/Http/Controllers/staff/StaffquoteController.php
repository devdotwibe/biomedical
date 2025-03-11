<?php

namespace App\Http\Controllers\staff;

use App\Company;
use App\Product;
use App\Banner;
use App\User;
use App\Category;
use App\Staff_quote;
use App\Staff_quote_details;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Oppertunity;
use App\Quotehistory;
use App\Staff;
use PDF;
use Image;
use Storage;
use carbon\carbon;
use Yajra\DataTables\DataTables;


class StaffquoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    //    $quote = Staff_quote::orderBy('id','desc')->get();



    if ($request->ajax()) {

        
        $staff_id = session('STAFF_ID');

        // $data = Quotehistory::join('oppertunities', 'quotehistories.oppertunity_id', '=', 'oppertunities.id')
             
        //     ->join('staff', 'oppertunities.staff_id', '=', 'staff.id')
        //     ->select('quotehistories.*', 'staff.name as staff_name');

        $data = Quotehistory::where('id','>',0);

        if($staff_id==32 || $staff_id==55 || $staff_id==35 || $staff_id==121 || $staff_id==56 || $staff_id== 127 ||  $staff_id== 129 ||  $staff_id== 130 )
        {

        }
        else
        {
            $data = Quotehistory::whereHas('quoteopper',function($q) use($staff_id)
            {
                $q->where('created_by_id',$staff_id);
            });
        }
       



        // $opportunityIds = Oppertunity::where('created_by_id',$staff)->pluck('id')->toArray();

        // $data->whereIn('oppertunity_id', $opportunityIds);
        

        $data->where('quote_status','receive')->where('approved_status','Y');

        $data->orderBy('id', 'desc')->get();

        return Datatables::of($data)

            ->addColumn('name', function ($data) {
                $oppertunity = Oppertunity::find($data->oppertunity_id);
                if ($oppertunity) {
                    $staff = Staff::find($oppertunity->staff_id);

                    if ($staff) {
                        return $staff->name;
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }
                // return optional($data->quotestaff->staff)->name??"";

            })
            ->addColumn('user_name', function ($data) {
                $oppertunity = Oppertunity::find($data->oppertunity_id);
                if ($oppertunity) {
                    $user = User::find($oppertunity->user_id);
                    if ($user) {
                        return $user->business_name;
                    } else {
                        return '';
                    }

                } else {
                    return '';
                }

            })
      
            ->addColumn('quote_no', function ($data) {
                return $data->quote_reference_no;
            })
            ->addColumn('created_at_time', function ($data) {

                return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y H:i:s') : '';
            })

            ->addColumn('quote_send_date', function ($data) {
             
                $quote_send_date ="";

                if ($data->updated_at) {
                   
                    $quote_send_date = $data->updated_at ? Carbon::parse($data->updated_at)->format('d-m-Y') : '';
                } 

                return  $quote_send_date;

            })

            ->addColumn('created_by_name', function ($data) {

                $oppertunity = Oppertunity::find($data->oppertunity_id);
                if ($oppertunity) {

                    $staff = Staff::find($oppertunity->created_by_id);

                    if ($staff) {
                        return $staff->name;
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }

            })
            

            // ->addColumn('opper_amount', function ($data) {
            //     // $oppertunity         = Oppertunity::find($data->oppertunity_id);
            //     if ($data->quote_amount) {
            //         return $this->IND_money_format($data->quote_amount);

            //     } else {
            //         return 0;
            //     }

            // })

            ->addColumn('oppertunity_name', function ($data) {
                $oppertunity = Oppertunity::find($data->oppertunity_id);

                if ($oppertunity) {
                    return '<a target="_blank" class="btn btn-primary btn-xs" href="' . route('staff.edit_oppertunity', "$oppertunity->id") . '" title="Edit">' . $oppertunity->oppertunity_name . '</a>';

                } else {
                    return '';
                }

            })

            ->addColumn('action', function ($data) {

                $button = '';

                $oppertunity = Oppertunity::find($data->oppertunity_id);

                if (! empty($oppertunity->type) && $oppertunity->type == 2) {
                    $button .= '
                    <a class="btn btn-sm btn-default table-icon" title="Preview" target="_blank" id="btn_preview" href="' . url('staff/preview_contract_quote/' . $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    ';
                    } else {
                        $button .= '
                        <a class="btn btn-sm btn-default table-icon" title="Preview" target="_blank" id="btn_preview" href="' . url('staff/preview_quote/' . $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        ';
                }

                return $button;
            })

            ->rawColumns(['name', 'opper_amount', 'action', 'oppertunity_name'])->addIndexColumn()->make(true);
        }


       return view('staff.quote.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Company::all();
        $product =  DB::select("select * from products where `company_id`>0 order by id desc");
        $user = User::all();
        return view('staff.quote.create',['company'=> $company,'product'=> $product,'user'=> $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $this->validate($request, array(
                //'company_id' => 'required',
                'customer_id' => 'required'
            ));

            $company_arr=array();
            $product_arr=array();
            foreach($request->company_id as $values)
            {
                $company_arr[]= $values;

            }
           
           $arr=array_unique($company_arr);
            $order_arr=array();
           foreach($arr as $values)
            {
               
                $staff_quote = new Staff_quote;
                $staff_quote->company_id = $values;
                $staff_quote->user_id = $request->customer_id;;
                $staff_quote->save();
                $staff_quote_update = Staff_quote::find($staff_quote->id);
                $order_no="order-no".$staff_quote->id;
                $staff_quote_update->quote_no=$order_no;
                $staff_quote_update->save();
                $order_arr[$values]=$staff_quote->id;
            }

            $k=0;
            foreach($request->product_id as $values)
            {
                $data   = DB::table("products")->where([['id', $values]])->first();
                $staff_quote_details = new Staff_quote_details;
                $staff_quote_details->product_id = $values;
                $staff_quote_details->staff_quote_id = $order_arr[$request->company_id[$k]];
                $staff_quote_details->user_id = $request->customer_id;
                $staff_quote_details->price = $data->unit_price;
                $staff_quote_details->name = $data->name;
                $staff_quote_details->company_id = $request->company_id[$k];
                $staff_quote_details->quantity = $request->quantity[$k];
                $staff_quote_details->sale_amount = $request->sale_amount[$k];
                $staff_quote_details->amount = $request->amount[$k];
                $staff_quote_details->optional      = $request->optional[$k];
                if($request->optional[$k]!=0)
                {
                    $staff_quote_details->main_product_id= $request->main_pdt[$k];
                }
                $staff_quote_details->save();
                $k++;
            }

      


        return redirect()->route('staff.quote.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff_quote $staff_quote,$id)
    {
       // $company = Company::orderBy('name', 'asc')->get();
       $company = Company::all();
        $user = User::all();
        $staff_quote = Staff_quote::find($id);
        $products =  DB::select("select * from products where `company_id`='".$staff_quote->company_id."' order by id desc");
        $quote_details =  DB::select("select * from  staff_quote_details where `staff_quote_id`='".$id."' order by id desc");
       
        return view('staff.quote.edit',compact('staff_quote'), ['company'=> $company,'user'=> $user,'products'=> $products,'quote_details'=> $quote_details]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // echo '111';exit;
       $this->validate($request, array(
          //  'company_id' => 'required',
            'user_id' => 'required'
        ));

        $staff_quote = Staff_quote::find($id);
        $staff_quote->company_id = $request->company_id;
        $staff_quote->user_id = $request->user_id;;
        $staff_quote->update();
        $j=0;
        foreach($request->product_id as $values)
        {
            if($request->staff_quote_id[$j]>0)
            {

            }
            else{
            $data   = DB::table("products")->where([['id', $values]])->first();
            $staff_quote_details = new Staff_quote_details;
            $staff_quote_details->product_id = $values;
            $staff_quote_details->staff_quote_id = $id;
            $staff_quote_details->user_id = $request->user_id;
            $staff_quote_details->price = $data->unit_price;
            $staff_quote_details->name = $data->name;
            $staff_quote_details->company_id = $request->company_id;

            $staff_quote_details->save();
            }
           
            $j++;
        }
      //  exit;
        return redirect()->route('staff.quote.index')->with('success','Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
       $company->delete();
       return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$company->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $company = Company::find($id);
           
            $company->delete();
        }


        return redirect()->route('admin.company.index')->with('success', 'Data has been deleted successfully');

    }


    
    public function sendquote(Request $request) {
        $quote = Staff_quote::find($request->id);
        $quote->status = 'recive';
     
        
        $quote->save();
        return redirect()->route('staff.quote.index')->with('success', 'Quote send successfully');
    }

    
    public function quotepdf(Request $request) {
        // echo $request->id;exit;
          $products = Staff_quote_details::where([['staff_quote_id', $request->id],['optional',0]])->orderBy('id', 'asc')->get();
        
          // print_r($request->product_id);exit;
           $filename='quote'.time().'.pdf';
            $path=$_SERVER['DOCUMENT_ROOT'].'/beczone/pdf/'.$filename;
            
            $data=10;
   
            $html ='
            <style>
			@page
{
margin-top:0 !importnat;
margin-bottom:0!importnat;
margin-left:0!importnat;
margin-right:0!importnat;
}
</style>
            <table width="575" cellpadding="0" cellspacing="0" border="0"  align="center" >
            <tr>
                <td>
                    <table width="575" cellpadding="0" cellspacing="0" border="0" align="center">
                        <tr>
                            <td>
                                <table width="575" height="231" cellpadding="0" cellspacing="0" border="0" style="background: url(http://dentaldigital.in/beczone/public/images/head-right-bg.jpg) #e8f7fe;
    background-position: right top;
    background-size: auto;background-repeat: no-repeat; height:231px; padding:0; ">
                                    <tr valign="top">
                                        <td align="left" width="58.5%" >
                                            <img src="'.asset("images/logo.png").'" width="200" height="52" alt="" style="margin-left:20px;margin-top:20px;">
                                        </td>
                                        <td align="right" width="41.5%">
											<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:35px 0 0 0;line-height:20px; text-align:left; padding-right:20px;">
											39/878 A2(YMJ Stadium Link Road)
Opp.J.N International Stadium, Palarivattom
Kochi PIN 682025 Kerala, India.
Email: mail@bechealthcare.com
Tele: 0484 2887207, Mobile: 8921065594
TIN 32071362164, DL 20 B # KL-EKM-111891
DL 21B # KL-EKM-111892
GST32AAGFB1151K1ZV</p>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#72c7f0">
                                    <tr>
                                        <td align="center" height="30px" width="58.5%">
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:18px; font-weight:normal;margin:0; line-height:30px;">Quote #</h3>	
                                        </td>
                                        <td align="left" height="30px" width="41.5%" >
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:18px;font-weight:normal; margin:0;line-height:30px;">Dated</h3>	
                                        </td>
                                        
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                                    <tr>
                                        <td align="left" width="58.5%"  style="padding:30px 15px;">
                                                
                                        </td>
                                        <td align="left"  width="41.5%"  style="padding:30px 0;">
                                            <p3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:16px;font-weight:normal; margin:0;line-height:22px;">To,<br/>
        Dr.Rajendran, HOD<br/>
        Department of Anaesthesiology<br/>
        Medical College, Kottayam</hp>	
                                        </td>
                                        
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#d2d3d5">
                                    <tr>
                                        <td width="5%" height="40"></td>
                                        <td align="left" width="95%" height="40" >
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:bold; margin:0;line-height:25px;">Price Quotation of Medical Equipment, Accessories, and Parts</h3>	
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="50">
                            </td>
                        </tr>';
                       $i=1;
                        foreach($products as $values)
                        {
                      $products_det =  DB::select("select * from products where `id`='".$values->product_id."' order by id desc");
                      if($products_det[0]->subcategory_id>0)
                      {
                        $subcat = Subcategory::find($products_det[0]->subcategory_id);
                        $subcat_name=$subcat->name;
                      }
                      else{
                        $subcat_name='';
                      }
                      
                       $html.='  <tr>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="border-bottom:1px solid #0098da;">
                                    <tr>
                                        <td width="5%" valign="top" bgcolor="#e6e7e8" style="padding:30px 2% 10px 2%; text-align:center;">
                                             <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">'. $i.'</p>
                                        </td>
                                  <td width="25%" valign="top" bgcolor="#e8f6fd" style="padding:30px 2% 10px 2%;">
                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Product Name:</p>
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$products_det[0]->name.'</h3><br/>
                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Brand:</p>	
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$products_det[0]->brand_name.'</h3><br/>
                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Category:</p>
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$products_det[0]->category_name.'</h3><br/>
                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Sub Category:</p>
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$subcat_name.'</h3>
                                        </td>
                                      <td width="24%" valign="bottom" bgcolor="#ffffff" style="padding:10px 2% 10px 2%;">
                                        <P style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;">'.substr($products_det[0]->feature,0, 120).'</P>
                                        </td>
                                  <td width="19%" valign="middle" bgcolor="#ffffff" style="padding:10px 2%; text-align:center">';
                                  if($products_det[0]->image_name!='')
                                  {
                                   $html.='  <img  src="'.asset("storage/app/public/products/thumbnail/".$products_det[0]->image_name).'" width="128" height="199" alt="">  '; 
                                  }
                                  else{
                                   $html.='  <img src="'.asset("images/no-image.jpg").'" width="128" height="199" alt="">  '; 
                                  }
                                 
                                            $html.=' </td>
                                  <td width="27%" valign="top" bgcolor="#ffffff" style="padding:0;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" >
                                              <tr bgcolor="#e6e7e8">
                                                   <td bgcolor="#e6e7e8" width="11%"></td>
                                          <td width="50%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                               <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit</p>
                                                    </td>
                                                <td width="39%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->unit.' No</p></td>
                            </tr>
                                              <tr bgcolor="#fff">
                                                   <td bgcolor="#fff" width="11%"></td>
        <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Box Quality</p>
                                                </td>
                                                    <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->quantity.' No</p></td>
                                                </tr>
                                                <tr>
                                                    <td bgcolor="#e6e7e8" width="11%"></td>
                      <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit Price</p>
                                                    </td>
                                                    <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$values->sale_amount.'</p></td>
                                                </tr>
                                                <tr>
                                                    <td bgcolor="#ffffff" width="11%"></td>
                      <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Tax Percenage </p>
                                                    </td>
                                                    <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->tax_percentage.'</p></td>
                                                </tr>
                                                <tr>
                                                    <td bgcolor="#e6e7e8" width="11%"></td>
                      <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">HSN Code </p>
                                                    </td>
                                                    <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->hsn_code.'</p></td>
                                                </tr>
                                                <tr>
                                                    <td bgcolor="#ffffff" width="11%"></td>
                      <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Warranty</p>
                                                    </td>
                                                    <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">
                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->warrenty.'</p></td>
                                                </tr>
                                                <tr>
                                                     <td bgcolor="#e6e7e8" width="11%"></td>
                      <td colspan="2" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">
                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">'.$products_det[0]->payment.'</p>
                                                    </td>
                                                 </tr>
                                          </table>	
                                        </td>
                                        
                                  </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="80">
                            </td>
                        </tr>';

                        $opt_pdt      = Staff_quote_details::where([['staff_quote_id', $request->id],['optional',1],['main_product_id',$values->product_id]])->get();
                        if(sizeof($opt_pdt))
                        {
                          $j = 1;
                          $html.='<tr>
                                <td><h4>Optional Products</h4></td>
                              </tr>
                              <tr><td>
                              <table style="width:80%" border="1">
                          <thead>
                                <tr>
                                 <th>No</th>
                                 <th>Product Name</th>
                                 <th>Sale Amount</th>
                                </tr>
                              </thead>
                              <tbody>';
                          foreach($opt_pdt as $opd)
                          {
                            //$pname = Product::where('id',$opd->product_id)->first()->name;
                            $html.='<tr>
                                       <td>'. $j++.'</td>
                                       <td>'. $opd->name.'</td>
                                       <td>'. $opd->sale_amount.'</td>
                                </tr>
                              ';
                          }
                          $html.= '</tbody>
                              </table>
                              </td></tr>
                              ';
                        }
                           $i++;
                       }
   
                        $html.='
						<tr>
                            <td>
								<table  width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
									<tr bgcolor="#e6e7e8">
										<td width="5%"></td>
										<td>
											<h2 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:20px;font-weight:normal; margin:0;line-height:normal; padding:10px 0; text-align:left">Technical Details <strong>of Medical Equipment, Accessories, and Parts</strong></h2>
										</td>
										<td width="5%"></td>
									</tr>
									<tr>
										<td width="5%"></td>
										<td>
											<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">';
                                           $k=1;
                                            foreach($products as $values)
                                            {
                                                $products_det =  DB::select("select * from products where `id`='".$values->product_id."' order by id desc");
                                                $html.='	<tr>
													<td>
														<h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:18px;font-weight:bold; margin:0 0 10px 0;line-height:35px; padding:10px 0; text-align:left" >'.$k.') <span style="background-color:#e6e7e8; padding:10px;">'.$products_det[0]->name.'</span></h3>
														<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">'.$products_det[0]->description.'</p>
													</td>
												</tr>
												<tr>
													<td height="30">
													</td>
                                                </tr>	';
                                                $k++;
                                            }
                                        $html.='</table>	
										</td>
										<td width="5%"></td>
									</tr>
									<tr>
										<td height="50">
										</td>
									</tr>
									<tr >
										<td colspan="2">
											<h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:20px;font-weight:bold; margin:0 0 10px 0; padding:10px 0; text-align:left" >Terms and Conditions</h3>
											<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;margin:0 0 25px 0;">1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in eros urna. Donec faucibus lectus at interdum consectetur. Duis ac elementum sapien. Vivamus sed ultricies sem, nec blandit urna. Maecenas semper elit eu gravida tristique. Sed scelerisque sem efficitur risus elementum, vitae sollicitudin nulla rutrum. In hac habitasse platea dictumst. Curabitur non porttitor nulla, ut tincidunt ante. Aenean luctus dictum purus id accumsan.</p>
											<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;margin:0 0 25px 0;">2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in eros urna. Donec faucibus lectus at interdum consectetur. Duis ac elementum sapien.</p>
											<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;margin:0 0 25px 0;">3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in eros urna. Donec faucibus lectus at interdum consectetur.</p>
											<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;margin:0 0 25px 0;">4. Vivamus sed ultricies sem, nec blandit urna. Maecenas semper elit eu gravida tristique. Sed scelerisque sem efficitur risus elementum, vitae sollicitudin nulla rutrum. In hac habitasse platea dictumst. Curabitur non porttitor nulla, ut tincidunt ante. Aenean luctus dictum purus id accumsan.</p>
											
										</td>
										</tr>
										<tr>
										<td height="40">
										</td>
									</tr>
									<tr >
										<td colspan="2" align="center">
											<p style="color:#999; font-family:Arial, Helvetica, sans-serif; font-size:14px;font-weight:normal; margin:0;line-height:normal;margin:0 0 25px 0; text-align:center">If you have any questions about this quotation. Please contact John Dow, PH: 919834535353</p>
											
										</td>
										</tr>
										<tr >
										<td colspan="2" align="center" style="padding:25px 0;background:#72c7f0;" >
											<p style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:20px;font-weight:normal; line-height:normal;margin:0 0 0 0; text-align:center">THANK YOU FOR YOUR BUSINESS!</p>
											
										</td>
										</tr>
								</table>
                            </td>
                        </tr>	
						<tr>
                            <td height="50">
                            </td>
                        </tr>	
                    </table>
                </td>
            </tr>
        </table>';
   
           $pdf = PDF::loadHTML($html);
          // $pdf->setPaper('A4', 'port');
           return $pdf->stream();
                }




}
