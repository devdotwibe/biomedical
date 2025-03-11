<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Product;
use App\Banner;
use App\Category;
use App\Dailyclosing_details;
use App\Dailyclosing_expence;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Dailyclosing_detailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $staff_id = session('STAFF_ID');
        $dailyclosing_details =  DB::select("select * from dailyclosing_details where `start_date`='".$request->start_date."' AND `staff_id`='".$staff_id."'  ");
      
        if($request->image_name!='')
        {
        $imageName = time().$request->image_name->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->image_name->storeAs('public/comment', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName="";
        }

        if(count($dailyclosing_details)>0)
        {
            
            $dayclose = Dailyclosing_details::find($dailyclosing_details[0]->id);
        }
        else{
            $dayclose = new Dailyclosing_details;
        }
       
       
        $dayclose->message = $request->replay_comment;
        $dayclose->staff_leave = $request->staff_leave;
        $dayclose->staff_id =$staff_id;
        $dayclose->start_date =$request->start_date;
     
        $dayclose->approved_fair ='Pending';
        $dayclose->approved_work ='Pending';
        $dayclose->image_name = $imageName;
     
        $dayclose->save();
        if($request->expence_type!='')
        {
            if(count($request->expence_type)>0)
            {$i=0;
             
      
              if(count($dailyclosing_details)>0)
              {
                $delte_expence=  DB::table('dailyclosing_expence')->where('dailyclosing_details_id', $dailyclosing_details[0]->id)->delete();
              }
                foreach($request->expence_type as $values)
                {
                    if($request->expence_type[$i]!='' && $request->fair[$i]!='')
                    {
                        // if(count($dailyclosing_expence)>0)
                        // {
                        //     $dayexpence = Dailyclosing_expence::find($dailyclosing_expence[0]->id);
                        // }
                        // else{
                            $dayexpence = new Dailyclosing_expence;
                      //  }
                        
                    $dayexpence->expence_type = $request->expence_type[$i];
                    $dayexpence->fair = $request->fair[$i];
                    $dayexpence->dailyclosing_details_id = $dayclose->id;
                    $dayexpence->save();
                    }
                    
                    $i++;
                }
            }
        }
        
       

        return redirect()->route('staff.dailyclosing')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        }

    public function deleteAll(Request $request)
    {
       
    }

}
