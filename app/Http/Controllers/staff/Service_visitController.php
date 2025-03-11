<?php

namespace App\Http\Controllers\staff;

use App\Service_visit;
use App\Contact_person;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Service_visitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $service_visit = Service_visit::all();
       return view('staff.service_visit.index', compact('service_visit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_visit = Service_visit::all();
      

        return view('staff.service_visit.create', compact('service_visit'));
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
                'work_time' => 'required'
             
             
            ));

        $service_visit = new Service_visit;
        if($request->report_upload!='')
        {
        $imageName = time().$request->report_upload->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->report_upload->storeAs('public/service_part', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        $service_visit->report_upload = $imageName;
        }

        $service_visit->work_time = $request->work_time;
        $service_visit->from = $request->from;
        $service_visit->to = $request->to;
        $service_visit->observed_prob = $request->observed_prob;
        $service_visit->action_taken = $request->action_taken;
        $service_visit->status = $request->status;
       
        $service_visit->generate_report = $request->generate_report;
      
        $service_visit->save();


        return redirect()->route('staff.service_visit.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(State $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(Service_visit $service_visit)
    {
       // $state = State::orderBy('name', 'asc')->get();
     
       
        return view('staff.service_visit.edit', compact('service_visit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service_visit $service_visit)
    {
        
       $this->validate($request, array(
                    'work_time' => 'required|max:100'
                  
                ));
                $service_visit = Service_visit::find($service_visit->id);
                $current = $service_visit->report_upload;

                if(isset($request->report_upload)) {
                    $imageName = time().$request->report_upload->getClientOriginalName();
                    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
                    $path =  storage_path();
                    $img_path = $request->report_upload->storeAs('public/category', $imageName);
                    $path = $path.'/app/'.$img_path;
                    chmod($path, 0777);
                    $image = $request->file('report_upload');
    
                    $destinationPath = storage_path().('/app/public/category/thumbnail');
                    $img = Image::make($image->getRealPath());
    
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$imageName);
    
                    $path =  storage_path().'/app/public/category/';
                   
                    \File::delete($path.$current);
                    \File::delete($path.'thumbnail/'.$current);
    
                 } else {
                    $imageName = $current;
                 }
    
                 $service_visit->report_upload = $imageName;
                 $service_visit->work_time = $request->work_time;
                 $service_visit->from = $request->from;
                 $service_visit->to = $request->to;
                 $service_visit->observed_prob = $request->observed_prob;
                 $service_visit->action_taken = $request->action_taken;
                 $service_visit->status = $request->status;
                
                 $service_visit->generate_report = $request->generate_report;
        $service_visit->save();

        return redirect()->route('staff.service_visit.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service_part  $company
     * @return \Illuminate\Http\Response
     */
   


    public function destroy(Service_visit $service_visit)
    {
        if($service_visit->image_name != '') {
            $path =  storage_path().'/app/public/service_visit/';
          
            \File::delete($path.$service_visit->report_upload);
            \File::delete($path.'thumbnail/'.$service_visit->report_upload);

            deleteFiles( $path, $service_visit->report_upload);
        }
        $service_visit->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$service_visit->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        $path =  storage_path().'/app/public/service_part/';

        foreach($ids as $id) {
            $service_part = Service_part::find($id);
            if($service_part->report_upload != '') {
                \File::delete($path.$service_part->report_upload);
                \File::delete($path.'thumbnail/'.$category->report_upload);
                deleteFiles($path, $service_part->report_upload);
            }
            $service_part->delete();
        }


        return redirect()->route('staff.service_part.index')->with('success', 'Data has been deleted successfully');

    }


}
