<?php

namespace App\Http\Controllers\marketspace;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Requests;
use App\Settings;
use App\Marketspace;
use App\Message;
use App\User;
use App\Marketspace_chat_user;

use Input;
use Validator;
use Redirect;
use Auth;
use Pusher\Pusher;
use Carbon\Carbon;
class MarketspaceController extends Controller
{
    
    protected $guard = 'marketspace';
    public function __construct()
    {
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        
    }
    public function marketspacelogin()
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            return Redirect::to('marketspace/dashboard');
        }else{
            return view('marketspace.login');
        }
        
    }

    public function marketspaceregister()
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            return Redirect::to('marketspace/dashboard');
        }else{
            return view('marketspace.register');
        }
        
    }
  
    
    public function postLogin(Request $request)
    {

       $rules = array(
        'email'    => 'required', // make sure the email is an actual email
        'password' => 'required|min:5' // password can only be alphanumeric and has to be greater than 3 characters
        );
// run the validation rules on the inputs from the form
$validator = Validator::make(Input::all(), $rules);

if ($validator->fails()) {
return Redirect::to('marketspace')
    ->withErrors($validator) // send back all errors to the login form
    ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
} else {
//echo 1;
$userdata = array(
    'email'     => Input::get('email'),
    'password'  => Input::get('password')
);

$email = Input::get('email');
$password = Input::get('password');
$user = Marketspace::where('email', '=', $email)->where('status','=','Y')->first();
if(isset($user->id) && $user->id > 0 && Hash::check($password, $user->password)) {
    $request->session()->put('MARKETSPACE_ID', $user->id);
    
    return Redirect::to('marketspace/dashboard');
} else {
   
    return redirect()->back()->with('error_message', 'Username or password are wrong.')
            ->withInput(Input::except('password'));
}

}


       
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    
    public function verifyotp(Request $request)
    { 
        
        $user = Marketspace::where('email', '=', $request->email)
        ->where('unique_code', '=', $request->otp)
        ->get();
        if(count($user)>0){
            $request->session()->put('MARKETSPACE_ID', $user[0]->id);
    
           echo count($user);
        }
        else{
            echo 0;
        }
    }
    /**
     *--------------------------------2023-10-19 Start-----------------------------* 
     */
    public function otpsend(Request $request)
    {  


        $token = $this->getToken(4, time()."".rand(100,54863));
        $code = $token . substr(strftime("%Y", time()),2); 
        $user_type=implode(',',$request->user_type);
      
        $user= Marketspace::where('email', '=', $request->email)->where('user_type', '=', $request->user_type)->first();
        if(empty($user)){
            $marketspace =new Marketspace;
            $marketspace->unique_code = $code;
            $marketspace->email = $request->email;
            $marketspace->user_type = implode(',',$request->user_type);
            $marketspace->save();

            if($user_type=="Hire")
            {
                $user = User::where('email', '=', $request->email)->first();
         
            if(!empty($user)){
                $marketspace_update =Marketspace::find($marketspace->id);
                $marketspace_update->name = $user->business_name;
                $marketspace_update->email = $user->email;
                $marketspace_update->address1 = $user->address1;
                $marketspace_update->address2 = $user->address2;
                $marketspace_update->country_id = 101;
                $marketspace_update->state_id = $user->state_id;
                $marketspace_update->district_id = $user->district_id;
                $marketspace_update->zip = $user->zip;
                $marketspace_update->city = $user->city;
                $marketspace_update->user_id = $user->id;
                $marketspace_update->image = $user->image_name1;
                $marketspace_update->save();
                $marketspace=$marketspace_update;
            }
            
            }
        }
        else{
           
            $marketspace =Marketspace::find($user->id);
            $marketspace->unique_code = $code;
            $marketspace->user_type = implode(',',$request->user_type);
            $marketspace->email = $request->email;
            $marketspace->save();
        }
       
 
	// Process your response here

         // echo $code;
        $datas = array(
            'name'=> $marketspace->name??$marketspace->email,
            'email'=>$marketspace->email, 
            'url'=>url('/'),
            'nmessagetxt'=>'Your Beczone verification code is:'.$code.' - BMENGC',
        );

        // $response=    Mail::send('email.otp', $datas, function($message) use ($datas) {

        //     $message->to($datas['email'], ' BMENGC')->subject

        //        (' BMENGC');

        //     $message->from('sales@biomedicalengineeringcompany.com','beczone');

        // });
         
            $mail = new PHPMailer(true);
            
            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
    }
    public function old_otpsend(Request $request)
    {  
        
        $token = $this->getToken(4, $request->phone);
        $code = $token . substr(strftime("%Y", time()),2);
        $user = Marketspace::where('phone', '=', $request->phone)
        ->where('user_type', '=', $request->user_type)
        ->get();
        $user_type=implode(',',$request->user_type);
      

        if(count($user)==0){
            $marketspace =new Marketspace;
            $marketspace->unique_code = $code;
            $marketspace->phone = $request->phone;
            $marketspace->user_type = implode(',',$request->user_type);
            $marketspace->save();

            if($user_type=="Hire")
            {
            $user = User::where('phone', '=', $request->phone)
            ->get();
         
            if(count($user)>0){
                $marketspace_update =Marketspace::find($marketspace->id);
                $marketspace_update->name = $user[0]->business_name;
                $marketspace_update->email = $user[0]->email;
                $marketspace_update->address1 = $user[0]->address1;
                $marketspace_update->address2 = $user[0]->address2;
                $marketspace_update->country_id = 101;
                $marketspace_update->state_id = $user[0]->state_id;
                $marketspace_update->district_id = $user[0]->district_id;
                $marketspace_update->zip = $user[0]->zip;
                $marketspace_update->city = $user[0]->city;
                $marketspace_update->user_id = $user[0]->id;
                $marketspace_update->image = $user[0]->image_name1;
                $marketspace_update->save();
            }
            
            }
        }
        else{
           
            $marketspace =Marketspace::find($user[0]->id);
            $marketspace->unique_code = $code;
            $marketspace->user_type = implode(',',$request->user_type);
            $marketspace->phone = $request->phone;
            $marketspace->save();
        }
       

        // Account details
	$apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
	$phone='91'.$request->phone;
	// Message details
	$numbers = array($phone);
	$sender = urlencode('BMENGC');
    $message = rawurlencode('Your Beczone verification code is:'.$code.' - BMENGC');
    
	$numbers = implode(',', $numbers);
 
	// Prepare data for POST request
	$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
 
	// Send the POST request with cURL
	$ch = curl_init('https://api.textlocal.in/send/');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	
	// Process your response here
	echo $response;

         // echo $code;
    }

    /**
     *--------------------------------2023-10-19 End-----------------------------* 
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'phone' => 'required',
            'user_type' => 'required',
        ]);
          
        $data = $request->all();
        $check = $this->create($data);

        if($check)
        {

       
        $url=url('/');
        $token = $this->getToken(6, $check->id);
        $code = $token . substr(strftime("%Y", time()),2);
        $marketspace =Marketspace::find($check->id);
        $marketspace->unique_code = $code;
        $marketspace->status ='N';
        $marketspace->save();

$datas = array(
    'name'=> $request->name,
    'email'=>$request->email,
    'unique_code'=>$code,
    'url'=>$url
);

// $success=    Mail::send('email.verification', $datas, function($message) use ($datas) {

//     $message->to($datas['email'], ' Verify your email address')->subject

//        (' Verify your email address');

//     $message->from('sales@biomedicalengineeringcompany.com','beczone');

// });
        }

     
      return redirect()->back()->with('message', 'Great! You have Successfully Registered.<br>To user Beczone, click the verification button in the mail we sent to '.$request->email.'. This helps keep your account secure');
       // return view('marketspace.login')->withSuccess('Great! You have Successfully loggedin');;
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        
        if(session('MARKETSPACE_ID')){
           
            
            return view('marketspace.dashboard');
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return Marketspace::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'status' => 'N',
        'password' => Hash::make($data['password'])
      ]);
    }
    
    private function getToken($length, $seed){    
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters))];
        }
        return substr(time(), -3).$randstring;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
   
    
    public function getMessage(Request $request)
    {
        $my_id = session('MARKETSPACE_ID');
        $user_id=$request->receiver_id;
        // Make read all unread message
        Message::where(['from' => $user_id, 'to' => $my_id])->update(['is_read' => 1]);

        // Get all message from selected user
        $messages = Message::where(function ($query) use ($user_id, $my_id) {
            $query->where('from', $user_id)->where('to', $my_id);
        })->oRwhere(function ($query) use ($user_id, $my_id) {
            $query->where('from', $my_id)->where('to', $user_id);
        })->get();

        return view('messages.index', ['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
       // $from = Auth::id();
       $from=session('MARKETSPACE_ID');
        $to = $request->receiver_id;
        $message = $request->message;

        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->message = $message;
        $data->is_read = 0; // message will be unread when sending message
        $data->save();

        $chatexit = Marketspace_chat_user::where('from_user', '=', $from)->where('to_user','=',$to)->get();
        if(count($chatexit)==0){
            $chatrel = new Marketspace_chat_user();
            $chatrel->from_user = $from;
            $chatrel->to_user = $to;
            $chatrel->save();
        }
       

        

        // pusher
        $options = array(
            'cluster' => 'ap2',
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = ['from' => $from, 'to' => $to]; // sending from and to user id when pressed enter
        $pusher->trigger('my-channel', 'my-event', $data);
    }



    public function chat()
    {
        if(session('MARKETSPACE_ID')>0)
        {
            $users = DB::select("select marketspace.id, marketspace.name, marketspace.image, marketspace.email, count(is_read) as unread 
            from marketspace LEFT  JOIN  messages ON marketspace.id = messages.from and is_read = 0 and messages.to = " . session('MARKETSPACE_ID') . "
            where marketspace.id != " . session('MARKETSPACE_ID') . " 
            group by marketspace.id, marketspace.name, marketspace.image, marketspace.email");
        
            return view('marketspace.chat', ['users' => $users]);
        }
    else{
        return view('marketspace.login');
    }
    }


}
