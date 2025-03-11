<?php

namespace App\Http\Controllers\Auth;

use App\Quote;
use App\Cart;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDF;
use Session;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {}

    /**
     * Display the specified resource.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function show(Contactus $contactus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function edit(Contactus $contactus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contactus $contactus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactus $contactus)
    {
        //
    }

    public function mycart()
    {
        $user = auth()->user();
        if($user)
        {
            $cart     = Cart::where([['user_id',$user->id],['status','add']])->get();
            return view('auth.mycart',array('cart'=>$cart));
        }
        else{ 
           return view('auth.login');
        }
    }

    public function removecart($id)
    {
        $cart           = Cart::find($id);
        $cart->status   = 'remove';
        $cart->save();

        Session::flash('message', 'Item removed from cart!'); 

        return redirect('mycart');
    }
    public function quote()
    {
        $user = auth()->user();
        if($user)
        {
         /*  $quote    = Quote::where('user_id',$user->id)->where('status','recive')
            ->get();*/
            $quote_recive = DB::select('select * from quote where `status`="recive" AND `user_id`="'.$user->id.'" ');   
            $quote_request= DB::select('select * from quote where  `user_id`="'.$user->id.'" ');   
            return view('auth.quote', compact('quote_request','quote_recive'));
        }
        else{
            
        return view('auth.login');
        }
      
    }

    public function quotepdf(Request $request) {
        //  echo $request->id;exit;
          $quote = Quote::where('id', $request->id)->orderBy('id', 'asc')->get();
          $product_id= $quote[0]->product_id;
          $products_det = Product::where('id', $product_id)->orderBy('id', 'asc')->get();
          $company_id= $products_det[0]->company_id;
          // print_r($request->product_id);exit;
           $filename='quote'.time().'.pdf';
            $path=$_SERVER['DOCUMENT_ROOT'].'/beczone/pdf/'.$filename;
            
            $data=10;
   
            $html ='<table width="520" cellpadding="0" cellspacing="0" border="0"  align="center">
            <tr>
                <td>
                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center">
                        <tr>
                            <td>
                                <table width="520" cellpadding="0" cellspacing="0" border="0" bgcolor="#91d8f7">
                                    <tr>
                                        <td>
                                            <img src="'.asset("images/head.jpg").'" width="100%" height="231" alt="">
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
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:bold; margin:0;line-height:40px;">Price Quotation of Medical Equipment, Accessories, and Parts</h3>	
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
                      //  foreach($products as $values)
                      //  {
                     //  $products_det =  DB::select("select * from products where `id`='".$values."' order by id desc");
                      
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
                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Workstation<br/>
                                            Manufature:</p>	
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$products_det[0]->brand_name.'</h3><br/>
                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Product Category:</p>
                                            <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">'.$products_det[0]->category_name.'</h3>
                                        </td>
                                      <td width="24%" valign="bottom" bgcolor="#ffffff" style="padding:10px 2% 10px 2%;">
                                        <P style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;">'.$products_det[0]->description.'</P>
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
                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">:'.$products_det[0]->unit_price.'</p></td>
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
                           $i++;
                      // }
   
                        $html.='<tr>
                            <td height="50">
                            </td>
                        </tr>	
                    </table>
                </td>
            </tr>
        </table>';
   
           $pdf = PDF::loadHTML($html);
           return $pdf->stream();
                }


}
