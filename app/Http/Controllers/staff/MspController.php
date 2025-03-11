<?php



namespace App\Http\Controllers\Staff;



use App\Msp;

use App\Product;

use App\Brand;

use App\Category;

use App\Chatter;

use App\Oppertunity;

use App\State;

use App\District;
use App\Category_type;

use App\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



use App\Http\Controllers\Controller;



use Image;

use Storage;



class MspController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index()
    {

        $staff_id = session('STAFF_ID');

        if($staff_id != 32)
        {
            return redirect()->back();
        }

        $msp = Msp::all();

        return view('admin.msp.index', compact('msp'));

    }



    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {

        $staff_id = session('STAFF_ID');

        if($staff_id != 32)
        {
            return redirect()->back();
        }

        $brand = DB::select("select brand.id,brand.name from brand as brand inner join products as products ON brand.id=products.brand_id group by brand.id ORDER BY brand.name asc  ");

        $msp = DB::table('msp')

            ->orderBy('id', 'asc')

            ->get();

        $category_type = Category_type::orderBy('id', 'asc')->get();
        $category_type = DB::select("select category_type.id,category_type.name from category_type as category_type inner join products as products ON category_type.id=products.category_type_id group by category_type.id ORDER BY category_type.name asc  ");

        $msp = DB::select("select * from msp order by id desc   ");
        return view('staff.msp.create', ['brand' => $brand, 'msp' => $msp, 'category_type' => $category_type]);

    }



    public function store(Request $request)
    {
        
        $staff_id = session('STAFF_ID');

        if($staff_id != 32)
        {
            return redirect()->back();
        }

        if (count($request->product_id??[]) > 0) {
            $brand = Brand::find($request->brand_id);

            /* echo count($request->product_id);
            echo '<br>';
            echo count($request->percent_online);
            exit;*/
            for ($i = 0; $i < count($request->product_id); $i++) {
                //echo $request->incentive_amount[$i];
                //die('eee');
                $msp = new Msp();

                $msp->brand_id = $request->brand_id;
                $msp->product_id = $request->product_id[$i];
                $msp->cost = $request->cost[$i];
                $msp->trans_cost = $request->trans_cost[$i];
                $msp->customs_cost = $request->customs_cost[$i];
                $msp->other_cost = $request->other_cost[$i];
                $msp->total_cost = $request->total_cost[$i];
                $msp->profit = $request->profit[$i];
                $msp->pro_msp = $request->pro_msp[$i];
                $msp->quote = $request->quote[$i];
                $msp->pro_quote_price = $request->pro_quote_price[$i];
                $msp->tax_per = $request->tax_per[$i];
                $msp->hsn_code = $request->hsn_code[$i];
                $msp->percent_online = $request->percent_online[$i];
                $msp->online_price = $request->online_price[$i];
                $msp->discount = $request->discount[$i];
                $msp->incentive = $request->incentive[$i];
                $msp->incentive_amount = $request->incentive_amount[$i];
                $msp->discount_price = $request->discount_price[$i];
                $product = Product::find($request->product_id[$i]);
                if ($product) {
                    $msp->product_name = $product->name;
                } else {
                    $msp->product_name = '';
                }

                if ($brand) {
                    $msp->brand_name = $brand->name;
                } else {
                    $msp->brand_name = '';
                }


                $msp->save();

            }
        }



        return redirect()->route('staff.msp.create')->with('success', 'Data successfully saved.');


    }

    public function product_show_status(Request $request)
    {
        if (isset($request->product_id) && $request->product_id > 0) {
            $product = Product::find($request->product_id);
            if ($request->status == "Y") {
                $product->verified = "Y";
                $product->show_inPage = "Y";
            } else {
                $product->verified = "N";
                $product->show_inPage = "N";
            }
            $product->save();

        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Msp  $brand
    * @return \Illuminate\Http\Response
    */

    public function show(Msp $brand)
    {



    }



    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Msp  $brand
    * @return \Illuminate\Http\Response
    */

    public function edit(Msp $brand)
    {



    }



    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Msp  $brand
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request, Brand $brand)
    {





    }



    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Msp  $brand
    * @return \Illuminate\Http\Response
    */

    public function destroy(Msp $brand)
    {





    }



    public function deleteAll(Request $request)
    {



    }



}