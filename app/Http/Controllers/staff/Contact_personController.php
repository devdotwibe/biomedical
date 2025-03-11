<?php

namespace App\Http\Controllers\staff;

use App\Contact_person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Image;

class Contact_personController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cms_id)
    {
        if($cms_id != 6) {
            return redirect()->route('staff.cms.index');
        }

        $persons = Contact_person::where('content_id', $cms_id)->orderBy('id', 'asc')->get();
       //echo session('downContId');
       return view('staff.contact-persons.index', compact('persons'), ['cms_id'=>$cms_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($cms_id)
    {
        if($cms_id != 6) {
            return redirect()->route('staff.cms.index');
        }

        return view('staff.contact-persons.create', ['cms_id'=>$cms_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($cms_id, Request $request)
    {
        if($cms_id != 6) {
            return redirect()->route('staff.cms.index');
        }

        $this->validate($request, array(
            'name' => 'required|max:100',
            'designation' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|max:50',
            'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=350,min_height=230',
        ));

        $slug_e = Contact_person::where([['email', $request->email]])->count();
        if($slug_e > 0) {
            return redirect()->back()->with('error_message', 'Email is already exists.')
                        ->withInput();
        } 

        $imageName = time().$request->image_name->getClientOriginalName();        
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path(); 
        $img_path = $request->image_name->storeAs('public/contacts', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
       //echo $imageName;

        $def = Contact_person::where([['default_status', 'Y']])->count();
        
        
        $cpersons = new Contact_person;
        $cpersons->content_id = $cms_id;
        $cpersons->name = $request->name;
        $cpersons->designation = $request->designation;
        $cpersons->email = $request->email;
        $cpersons->phone = $request->phone;
        $cpersons->fax = $request->fax;
        $cpersons->image_name = $imageName;
        $cpersons->status = $request->status;
        $cpersons->default_status = ($def == 0) ? 'Y': 'N';
        $cpersons->save();
        
        //echo storage_path().'/app/public/banners/'.$imageName;
        //exit;
        
        $image = $request->file('image_name');

        //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = storage_path().('/app/public/contacts/thumbnail');

        $img = Image::make($image->getRealPath());

        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
        
        return redirect()->route('staff.cms.contact-persons.index', $cms_id)->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact_person  $contact_person
     * @return \Illuminate\Http\Response
     */
    public function show(Contact_persons $contact_persons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact_person  $contact_person
     * @return \Illuminate\Http\Response
     */
    public function edit($cms_id, $id, Contact_person $contact_persons)
    {
        $contact_persons = Contact_person::find($id);
        return view('staff.contact-persons.edit', compact('contact_persons'), ['cms_id'=>$cms_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact_person  $contact_person
     * @return \Illuminate\Http\Response
     */
    public function update($cms_id, $id, Request $request, Contact_person $contact_persons)
    {
        if($cms_id != 6) {
            return redirect()->route('staff.cms.index');
        }

        $contact_persons = Contact_person::find($id);

        $current = $contact_persons->image_name;
        $imageUploaded = '';

        $slug_e = Contact_person::where([['email', $request->email]])
                  ->whereNotIn('id', [$id])
                  ->count();
        if($slug_e > 0) {
            return redirect()->back()->with('error_message', 'Email is already exists.')
                        ->withInput();
        } 

        if(isset($request->image_name)) {

            $this->validate($request, array(
                'name' => 'required|max:100',
                'designation' => 'required|max:100',
                'email' => 'required|email',
                'phone' => 'required|max:50',
                'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=350,min_height=230',
            ));

            $imageName = time().$request->image_name->getClientOriginalName();        
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path =  storage_path(); 
            $img_path = $request->image_name->storeAs('public/contacts', $imageName);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777); 

            $imageUploaded = $imageName; 
            
            $image = $request->file('image_name');

            //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path().('/app/public/contacts/thumbnail');

            $img = Image::make($image->getRealPath());

            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);
            
            $path =  storage_path().'/app/public/contacts/'; 
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$current);
            \File::delete($path.'thumbnail/'.$current);

            deleteFiles( $path, $current);
            
        } else {
            $this->validate($request, array(
                'name' => 'required|max:100',
                'designation' => 'required|max:100',
                'email' => 'required|email',
                'phone' => 'required|max:50'
            ));

            $imageUploaded = $current ; 
        }

        $contact_persons->content_id = $cms_id;
        $contact_persons->name = $request->name;
        $contact_persons->designation = $request->designation;
        $contact_persons->email = $request->email;
        $contact_persons->phone = $request->phone;
        $contact_persons->fax = $request->fax;
        $contact_persons->image_name = $imageUploaded;
        $contact_persons->status = $request->status;
        $contact_persons->save();
        
        return redirect()->route('staff.cms.contact-persons.index', $cms_id)->with('success','Data successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact_person  $contact_persons
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact_person = Contact_person::find($id);
      
        
        $contact_person->delete(); 
        
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$contact_person->id]);
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        
    

        foreach($ids as $id) { 
            $contact_persons = Contact_person::find($id);
           
            $contact_persons->delete();
        }

        return redirect()->route('staff.cms.contact-persons.index', $cms_id)->with('success', 'Data has been deleted successfully');

    }


    public static function ajaxDataDetails(Request $request) { 
        $cms1 = Contact_person::find($request->id);
        
        //echo storage_path('public/','');;
        
        $out = '<table class="detailsTable white" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
                <tr>
                    <td><span class="detailHead">Name:</span> </td>
                    <td>'.$cms1->name .'</td>
                </tr>

                <tr>
                    <td><span class="detailHead">Designation:</span> </td>
                    <td>'.$cms1->designation .'</td>
                </tr>

                <tr>
                    <td><span class="detailHead">Email:</span> </td>
                    <td>'.$cms1->email .'</td>
                </tr>

                <tr>
                    <td><span class="detailHead">Phone:</span> </td>
                    <td>'.$cms1->phone .'</td>
                </tr>';

                if($cms1->fax != '') {

               $out  .= '<tr>
                    <td><span class="detailHead">Fax:</span> </td>
                    <td>'.$cms1->fax .'</td>
                </tr>';
            }

            $path = storage_path().'/app/public/contacts/';
                    if(file_exists($path.'thumb_'.$cms1->image_name)) {
                        $img_name = asset("storage/app/public/contacts/thumb_".$cms1->image_name);
                    }  else {
                        $img_name = asset("storage/app/public/contacts/$cms1->image_name");
                    }

             $out  .= '<tr>
                    <td><span class="detailHead">Image:</span> </td>
                    <td><img src="'.$img_name.'" width="350px"></td>
                </tr>';

               $out  .= '
                
                <tr>
                    <td><span class="detailHead">Created Date:</span> </td>
                    <td>'.date('d-m-Y h:i A', strtotime($cms1->created_at)).'</td>
                </tr>

                 <tr>
                    <td><span class="detailHead">Default:</span> </td>
                    <td>'.(($cms1->default_status== 'Y') ? 'Yes': 'No').'</td>
                </tr>

                <tr>
                    <td><span class="detailHead">Status:</span> </td>
                    <td>'.(($cms1->status== 'Y') ? 'Active': 'Inactive').'</td>
                </tr>
                </table>';
        
        return $out;
        
    }
}
