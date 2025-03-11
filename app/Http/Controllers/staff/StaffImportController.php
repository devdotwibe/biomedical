<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ib;
use App\Product;
use App\User;
use App\Staff;
use App\EquipmentStatus;
use App\Category;
use App\State;
use App\District;
use App\Jobs\ImportIbDataJob;
use File;
use App\Models\IbImport;
use App\Models\ImportSave;
use DataTables;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StaffImportController extends Controller
{
    
    public function index(Request $request)
    {

        $staff_id = session('STAFF_ID');

     

        if($request->ajax()){

            $staff_id = session('STAFF_ID');

            $data = IbImport::where('id','>',0)->where(function($query){ $query->where('status', '!=', 'conform')->orwhereNull('status');});

            return DataTables::of($data)


            ->addColumn('customer_name_row',function($data){

                    if(!empty($data->customer_name)){

                        // $user = User::where('business_name',$data->customer_name)->first();

                        $state = $data->state;

                        $user = User::where('business_name', 'LIKE', '%' . strtolower($data->customer_name) . '%')->whereIn('state_id',State::where('name',$state)->pluck('id'))->first();

                        // $state  = State::where('id',$user->state_id)->get();
                        
                        if(!empty($user) )
                        {
                            return '<span  style="background-color: #02ff00; color: black; padding: 4px; display: inline-block;">'.$data->customer_name.'</span>';
                        }
                        else
                        {
                            return  $data->customer_name;
                        }
                        
                    }

                    else{

                        return "";
                    }
                })

                ->addColumn('equipment_row',function($data){

                    if(!empty($data->equipment)){

                        // $product = Product::where('name',$data->equipment)->first();

                        $product = Product::where('name', 'LIKE', '%' . strtolower($data->equipment) . '%')->first();
                        
                        if(!empty($product) )
                        {
                            return '<span style="background-color: #ed5d2f; color: black; padding: 4px; display: inline-block;">'.$data->equipment.'</span>';
                        }
                        else
                        {
                            return $data->equipment;
                        }
                        
                    }

                    else{

                        return "";
                    }
                })

                ->addColumn('serial_row',function($data){

                    if(!empty($data->serial)){

                        // $product = Product::where('name',$data->equipment)->first();

                        $ib = Ib::where('equipment_serial_no',$data->serial)->first();
                        
                        if(!empty($ib) )
                        {
                            return '<span style="background-color: #a42fed; color: black; padding: 4px; display: inline-block;">'.$data->serial.'</span>';
                        }
                        else
                        {
                            return $data->serial;
                        }
                        
                    }

                    else{

                        return "";
                    }
                })

                ->addColumn('sales_person_row',function($data){

                    if(!empty($data->sales_person)){

                        // $product = Product::where('name',$data->equipment)->first();

                        $ib = Ib::where('staff_id', 'LIKE', '%' . strtolower($data->sales_person) . '%')->first();
                        
                        if(!empty($ib) )
                        {
                            return '<span style="background-color: #02ff00; color: black; padding: 4px; display: inline-block;">'.$data->sales_person.'</span>';
                        }
                        else
                        {
                            return $data->sales_person;
                        }
                        
                    }

                    else{

                        return "";
                    }
                })

            ->addColumn('action',function($data){
                $button='
                <a class="btn btn-primary btn-xs" target="_blank" href="'.route('staff.import-edit-staff',"$data->id").'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                <a class="delete-btn   deleteItem" onclick="deleteItem('."'".route('staff.import-destroy-staff',$data->id)."','#cmsTable'".')" id="deleteItem'.$data->id.'" data-tr="tr_'.$data->id.'" title="Delete"><img src="'. asset('images/delete.svg') .'"></a>';
                return $button;
            })

            //->rawColumns(['customer'])->addIndexColumn()->make(true);    
            ->rawColumns(['action','customer_name_row','equipment_row','serial_row'])->addIndexColumn()->make(true);            
        }

        $state = State::get();

        return view('staff.import_ib.index',compact('state'));

    }

    public function edit($id)
    {   
        
        // $staff_id = session('STAFF_ID');

        $ib_import = IbImport::find($id);

        $import_save = ImportSave::where('ib_import_id',$id)->first(); 

        $products           = Product::get();
        $users              = User::get();
        $equipment_status   = EquipmentStatus::get();
        $categories         = Category::get();
        $staffs             = Staff::get();
        $state              = State::get();

        // $selectedCustomer   = User::where('id',$ib->user_id)->withTrashed()->first();
        
        if(!empty($import_save))
        {
            $selectedCustomer = $import_save->user_id;

            $states = State::where('id',$import_save->state_id)->first();

            if(!empty($selectedCustomer)) {

            $users = User::where('id',$selectedCustomer)->first(); 

            }
            else
            {
                $users              = User::get();
            }
             
            if(!empty($selectedCustomer) && !empty($states) && !empty($users)){
    
                $district           = District::where('state_id',$states->id)->get();
    
                $users              = User::where('district_id',$users->district_id)->get();
                }else
                {
    
                    $district = District::get();
    
                    $users              = User::get();  
                } 
        }
        else
        {
            $selectedCustomer = $ib_import->customer_name??"";

            // $states = State::whereRaw('TOLOWER(name)',strtolower($ib_import->state))->first();

            $stateNameLower = strtolower($ib_import->state);

            $states = State::whereRaw('LOWER(name) = ?', [$stateNameLower])->first();

            $users = User::where('business_name', 'LIKE', '%' . $selectedCustomer . '%')->first(); 
    
            if(!empty($selectedCustomer) && !empty($states) && !empty($users)){
    
                $district           = District::where('state_id',$states->id)->get();
    
                $users              = User::where('district_id',$users->district_id)->get();
                }else
                {
    
                    $district = District::get();
    
                    $users              = User::get();  
                } 
        }
       

        // $users = $ib_import->customer_name;

        

        //echo $selectedCustomer;
        return view('staff.import_ib.edit',compact('import_save','state','staffs','district', 'selectedCustomer', 'ib_import','products', 'users','equipment_status', 'categories'));

    }


    public function import_update(Request $request, $id)
    {
       
        $inRefno = 'IB-'.rand(1000, 100000);

        if($request->submit =='Save')
        {
            $import_save = ImportSave::where('ib_import_id',$id)->first();

            if(empty($import_save))
            {
                $import_save = new ImportSave;
            }
            
            $import_save->internal_ref_no =$inRefno;

            $import_save->external_ref_no = $request->external_ref_no;

            $import_save->state_id = $request->state_id;
            $import_save->district_id = $request->district_id;
            $import_save->user_id = $request->user_id;
            $import_save->department_id = $request->department_id;
            $import_save->equipment_id = $request->equipment_id;
            $import_save->equipment_serial_no = $request->equipment_serial_no;
            $import_save->equipment_model_no = $request->equipment_model_no;
            $import_save->equipment_status_id = $request->equipment_status_id;
            $import_save->staff_id = $request->staff_id;
            $import_save->installation_date = $request->installation_date;
            $import_save->warrenty_end_date = $request->warrenty_end_date;
            $import_save->supplay_order = $request->supplay_order;
            $import_save->invoice_number = $request->invoice_number;
            $import_save->invoice_date = $request->invoice_date;
            $import_save->description = $request->description;

            $import_save->ib_import_id = $id;
            
            $import_save->save();
        }
        else
        {

            $request->validate([

                'external_ref_no'=>'required',
                'state_id'=>'required',
                'district_id'=>'required',
                'user_id'=>'required',
                'department_id'=>'required' ,
                'equipment_id'=>'required',
                'equipment_serial_no'=>'required',  
                // 'equipment_model_no'=>'required',  
                'equipment_status_id'=>'required',
                'staff_id'=>'required',
                'installation_date'=>'required',
                'warrenty_end_date'=>'required',
                // 'supplay_order'=>'required',
                // 'invoice_number'=>'required',
                // 'invoice_date'=>'required',
                // 'description'=>'required',
                
            ]);
           
                $ib_import = IbImport::find($id);

                $ib = new Ib;
                
                $ib->fill($request->all());

                    $inRefno = 'IB-' . rand(1000, 100000);

                    $ib->internal_ref_no = $inRefno;

                $ib->save();

                $ib_import->status = 'conform';

                $ib_import->save();

        }

        return redirect()->route('staff.staff_import_ib')->with('success','Data successfully updated.');
    }

    public function destroy(Request $request ,$id)
    {
        IbImport::find($id)->delete();
        if($request->ajax()){
            return response()->json(['success'=>'Data successfully deleted.']);
        }

        return redirect()->route('staff.staff_import_ib')->with('success', 'Data successfully deleted.');
    }


}
