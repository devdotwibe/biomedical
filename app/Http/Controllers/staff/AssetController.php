<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\State;
use App\District;
use App\Product_type;
use App\Http\Controllers\Controller;
use Validator;
use App\Asset;
use App\User;
use App\Modality;
use App\Assetdepartment;


class AssetController extends Controller
{
    /**
     * Display asset index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search =  $request->input('q');
        if($search!=""){
            $assets = Asset::where(function ($query) use ($search){
                $query->where('serial_no', 'like', '%'.$search.'%')
                      ->orWhere('installed_at', 'like', '%'.$search.'%')
                      ->orWhere('product_descrption', 'like', '%'.$search.'%');            
            })
            ->paginate(10);
        }
        else{
            $assets          = Asset::paginate(10);
        }

        $state           = State::all();
        $district        = District::all();
        $customer        = User::all();
        $product_type    = Product_type::all();
        $brand           = DB::table('brand')
                            ->orderBy('name', 'asc')
                            ->select('name','id')
                            ->get();
        $manageProducts  = DB::table('products')->select('name','id')->get();   
        
        foreach ($manageProducts as $product) {
            $product->product_type = 'product';
        }
        $competitionProduct = DB::table('competition_product')->select('name','id')->get();
        foreach ($competitionProduct as $product) {
            $product->product_type = 'compitition';
        }
        $products        = $manageProducts->merge($competitionProduct); 
        $modality        = Modality::all();

        return view('staff.asset.index', compact('assets','state','district','customer','product_type','products','brand','modality'));
    }
    /**
     * Show asset registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       $state        = State::all();
       $district     = District::all();
       $brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $manageProducts = DB::table('products')->select('name','id')->get();
        foreach ($manageProducts as $product) {
            $product->product_type = 'product';
        }
        $competitionProduct = DB::table('competition_product')->select('name','id')->get();
        foreach ($competitionProduct as $product) {
            $product->product_type = 'compitition';
        }
        $modality = Modality::all();
        $products = $manageProducts->merge($competitionProduct);
        $product_type = Product_type::all();
        $department = Assetdepartment::all();
       return view('staff.asset.create', array('state' => $state, 'district' => $district, 'brand' => $brand, 'product_type' => $product_type, 'department' => $department,'products' => $products,'modality' => $modality ));
    }
    /**
     * Store asset registration data into table.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$validation = Validator::make($request->all(), [
			'installed_at'		=> 'required',
			'state'				=> 'required',
			'district'			=> 'required',
			'account_name'		=> 'required',
			'asset_description'	=> 'required',
			'manufacturer'		=> 'required',
			'product_name'		=> 'required',
			'modality'          => 'required',	
	    ]);

	    if ($validation->fails()) {
		    return redirect()->back()->withErrors($validation->errors());
		}
        $productName = explode(',', $request->product_name);
		$asset 						= new Asset;
		$asset->asset_no		  	= $request->asset_no;
		$asset->serial_no			= $request->serial_no;
		$asset->system_id			= $request->system_id;
		$asset->company				= $request->company;
		$asset->product_no			= $request->product_no;
		$asset->product_descrption	= $request->product_descrption;
		$asset->assign_segment 		= $request->assign_segment;
		$asset->modality 			= $request->modality;
		$asset->installed_at		= $request->installed_at;
	    $asset->state               = $request->state;
	    $asset->district            = $request->district;
	    $asset->account_name        = $request->account_name;
	    $asset->asset_description   = $request->asset_description;
	    $asset->manufacturer        = $request->manufacturer;
	    $asset->product_name        = $productName[0];
        $asset->product_type        = $productName[1];
	    $asset->department          = $request->department;
	    $asset->equipment_status    = $request->equipment_status;
	    $asset->installed_on        = date('Y:m:d',  strtotime($request->installed_on));
		$asset->save();

		$request->session()->flash('success', 'Oppertunity added Successfully');

		return redirect('staff/asset');
		
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
    */

    public function edit($id)
    {
    	$user 		  = User::all();
        $state        = State::all();
        $district     = District::all();
    	$asset 		  = Asset::find($id);
    	$brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $modality     = Modality::all(); 
        $department = Assetdepartment::all();
        $product_type = Product_type::all();
        $manageProducts = DB::table('products')->select('name','id')->get();
        foreach ($manageProducts as $product) {
            $product->product_type = 'product';
        }
        $competitionProduct = DB::table('competition_product')->select('name','id')->get();
        foreach ($competitionProduct as $product) {
            $product->product_type = 'compitition';
        }
        $products = $manageProducts->merge($competitionProduct);

        return view('staff.asset.edit',array('customer'=>$user,'asset'=>$asset,'id'=>$id,'state'=>$state,'district'=>$district, 'brand' => $brand, 'product_type' => $product_type, 'department' => $department, 'products' => $products,'modality' => $modality));
    }
    /**
     * Update the form specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
    */

    public function update(Request $request,$id)
    {
    	$validation = Validator::make($request->all(), [
			'installed_at'       => 'required',
            'state'             => 'required',
            'district'          => 'required',
            'account_name'      => 'required',
            'asset_description' => 'required',
            'manufacturer'      => 'required',
            'product_name'      => 'required',
            'modality'          => 'required',
	    ]);

	    if ($validation->fails()) {
		    return redirect()->back()->withErrors($validation->errors());
		}
        $productName = explode(',', $request->product_name);
		$asset 						= Asset::find($id);
		$asset->asset_no		  	= $request->asset_no;
		$asset->serial_no			= $request->serial_no;
		$asset->system_id			= $request->system_id;
		$asset->company				= $request->company;
		$asset->product_no			= $request->product_no;
		$asset->product_descrption	= $request->product_descrption;
		$asset->assign_segment 		= $request->assign_segment;
		$asset->modality 			= $request->modality;
		$asset->installed_at		= $request->installed_at;
	    $asset->account_name        = $request->account_name;
	    $asset->asset_description   = $request->asset_description;
	    $asset->manufacturer        = $request->manufacturer;
	    $asset->product_name        = $productName[0];
        $asset->product_type        = $productName[1];
	    $asset->department          = $request->department;
	    $asset->equipment_status    = $request->equipment_status;
	    $asset->installed_on        = date('Y:m:d',  strtotime($request->installed_on));
		$asset->save();

		$request->session()->flash('success', 'Asset added Successfully');

		return redirect('staff/asset');
    }
    /**
     * Save the required field specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
    */

    public function save(Request $request)
    {
       
        $asset                      = Asset::find($request->id);
        $asset->product_name        = '';
        $asset->product_type        = '';
        $asset->installed_at        = $request->installed_at;
        $asset->state               = $request->state;
        $asset->district            = $request->district;
        $asset->account_name        = $request->account_name;
        $asset->asset_description   = $request->asset_description;
        $asset->manufacturer        = $request->manufacturer;
        if($request->product){
            $productName = explode(',', $request->product);
            $asset->product_name        = $productName[0];
            $asset->product_type        = $productName[1];
        }
        
        $asset->modality            = $request->modality;
        $asset->serial_no           = $request->serialNo;
        $asset->product_descrption  = $request->description;
        
        $asset->save();

        $request->session()->flash('success', 'Asset added Successfully');

        return redirect('staff/asset');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);
        $asset->delete(); 
        
        return response()->json(['success'=>"Data has been deleted successfully.",'tr'=>'tr_'.$asset->id]);
    }
    /**
     * Remove all rows selected resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;        
        DB::table("assets")->whereIn('id',$ids)->delete();
        return redirect()->route('staff.asset')->with('success', 'Data has been deleted successfully');
    }
    /**
     * Show asset department.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function assetDepartmentShow(){

        $assetDepartment = Assetdepartment::all();
        return view('staff.assetdepartment.index',compact('assetDepartment'));
    }
    /**
     * Show asset department registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function assetDepartmentCreate(){

        return view('staff.assetdepartment.create');
    }
    /**
     * Store asset Department data into table.
     *
     * @return \Illuminate\Http\Response
     */
    public function assetDepartmentStore(Request $request){

        $validation = Validator::make($request->all(), [
            'name'      => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }
        $assetDepartment            = new Assetdepartment;
        $assetDepartment->name  = $request->name;
        $assetDepartment->save();

        $request->session()->flash('success', 'Asset Department added Successfully');

        return redirect('staff/assetDepartment');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
    */

    public function assetDepartmentEdit($id)
    {
        $department = Assetdepartment::find($id);

        return view('staff.assetdepartment.edit',array('department' => $department));
    }
    /**
     * Update the form specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
    */

    public function assetDepartmentUpdate(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }
        $assetDepartment            = Assetdepartment::find($id);
        $assetDepartment->name      = $request->name;
        $assetDepartment->save();

        $request->session()->flash('success', 'Asset Department added Successfully');

        return redirect('staff/assetDepartment');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assetdepartment  $assetdepartment
     * @return \Illuminate\Http\Response
     */
    public function assetDepartmentDestroy($id)
    {
        $assetdepartment = Assetdepartment::find($id);
        $assetdepartment->delete(); 
        
        return response()->json(['success'=>"Data has been deleted successfully.",'tr'=>'tr_'.$assetdepartment->id]);
    }
    /**
     * Remove all rows selected resource from storage.
     *
     * @param  \App\Assetdepartment  $assetdepartment
     * @return \Illuminate\Http\Response
     */
    public function assetDepartmentDeleteAll(Request $request)
    {
        $ids = $request->ids;        
        DB::table("asset_department")->whereIn('id',$ids)->delete();
        return redirect()->route('staff.assetdepartment.index')->with('success', 'Data has been deleted successfully');
    }


}
