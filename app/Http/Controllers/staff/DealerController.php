<?php



namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;


class DealerController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)
    {

        if($request->ajax()){
           
            $data=   Dealer::orderBy('dealer_id','desc')->get();

            return Datatables::of($data)->addColumn('name',function($data){
                    return $data->dealer_name;
                })->addColumn('register_email',function($data){
                    return $data->username;
                })->addColumn('register_date',function($data){
                    return $data->created_at->format('d-m-Y');
                })->addColumn('status',function($data){
                    return $data->status=='Y'?"<span class='text-success'>verified</span>":"<span class='text-warning'>un-verified</span>";
                })->addColumn('action',function($data){
                    $button = ' <a class="btn btn-info btn-xs" href="'.route('dealer.show',"$data->dealer_id").'" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                                <a class="btn btn-primary btn-xs" href="'.route('dealer.edit',"$data->dealer_id").'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a class="delete-btn" href="javascript:deleteItem('."'".route('dealer.destroy',$data->dealer_id) ."','#dealer-table'".')" title="Delete"><img src="'. asset('images/delete.svg') .'"></a>';
                return $button;
                })->rawColumns(['name','register_email','register_date','status','action'])->addIndexColumn()->make(true);
        }

       return view('staff.dealer.index');

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
        // return redirect()->route('admin.dealer.index')->with('success','Data successfully saved.');

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Model\Dealer  $dealer

     * @return \Illuminate\Http\Response

     */

    public function show(Dealer $dealer)
    {
        return view('staff.dealer.view', compact('dealer'));
    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Model\Dealer  $dealer

     * @return \Illuminate\Http\Response

     */

    public function edit(Dealer $dealer)

    {
        return view('staff.dealer.edit', compact('dealer'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Model\Dealer  $dealer

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Dealer $dealer)
    {

        $rules = array(
            'name'      => 'required|max:100', // make sure the email is an actual email
            'email'  => 'required|email|max:100', // make sure the email is an actual email
            'phone'  => 'required', // password can only be alphanumeric and has to be greater than 3 characters
            'address' => 'max:100' // password can only be alphanumeric and has to be greater than 3 characters
        );
        $customMessages = [
            'name.required' => 'Name is required!',
            'name.max'  => 'Maximum 100 characters allowed!',
            'email.required' => 'Email is required!',
            'email.email'  => 'Invalid email address!',
            'email.max'  => 'Maximum 100 characters allowed!',
            'phone.required' => 'Name is required!',
            'address.max'  => 'Maximum 100 characters allowed!',
            
        ];
        // run the validation rules on the inputs from the form
        $this->validate($request, $rules, $customMessages);
        $dealer->dealer_name = $request->name;
        $dealer->email = $request->email;
        $dealer->phone = $request->phone;
        $dealer->alter_phone = $request->alter_phone;
        $dealer->address = $request->address;
        $dealer->save();
        return redirect()->back()->with('success','Dealer Profile Update was successfully completed.');

    }
    public function upload(Request $request , Dealer $dealer)
    {

        $rules = array(
            'image'    => 'mimes:jpeg,png,jpg|max:1024',
        );
        $customMessages = [
            'image.max'  => 'Maximum size 1MB!',
            'image.mimes'  => 'jpeg,png,jpg mimes supported!',
            
        ];
        // run the validation rules on the inputs from the form
        $this->validate($request, $rules, $customMessages);
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $imageName = time().$request->image->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $file->move('profile-pic/dealer',$imageName);
            $dealer->profile_pic       =    isset($imageName) ? $imageName: '';
            $dealer->save();
            return asset("profile-pic/dealer/".$imageName);
        }
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Model\Dealer  $dealer

     * @return \Illuminate\Http\Response

     */

  
    public function destroy(Dealer $dealer)
    {
        $dealer->delete();
        return response()->json(['success'=>"Data has been deleted successfully."]);
    }
    public function verify(Dealer $dealer)
    {
        $dealer->status = "Y";
        $dealer->save();
        try
        {
            $mailData = array(
                "name"    => $dealer->dealer_name,
                "email"   => $dealer->email,
                "maildata"=> "https://biomedicalengineeringcompany.com/dealer/",
                "siteurl"=> "https://biomedicalengineeringcompany.com/",
                "user_want"=> "Dealer",
            );
            Mail::send('email.verifysuccess', $mailData, function($message)use ($dealer) {
                $message->to($dealer->username, $dealer->dealer_name)->subject
                   ('Dealer Verifyed ');
                $message->from(setting('ADMIN_EMAIL_ID'),setting('ADMIN_EMAIL_NAME'));
            });
            return response()->json(['success'=>"Dealer ".$dealer->dealer_name." Verified."]);
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
          
        }
    }

    public function deleteAll(Request $request)
    {
        

        return redirect()->route('staff.index')->with('success', 'Data has been deleted successfully');

    }




}

