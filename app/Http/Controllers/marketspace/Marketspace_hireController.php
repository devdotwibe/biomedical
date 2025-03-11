<?php

namespace App\Http\Controllers\marketspace;

use App\Marketspace;
use App\Marketspace_hire;
use App\Marketspace_chat;
use App\Marketspace_chat_user;


use App\Http\Middleware\MarketspaceAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Image;
use Storage;
use Session;
use Auth;
class Marketspace_hireController extends Controller
{
    public function saveHire(Request $request)
    {
        
        $token = $this->getToken(3,6546546584);
        $code = $token . substr(strftime("%Y", time()),2);

            $hire = new Marketspace_hire;
            $hire->title = $request->title;
            $hire->details = $request->details;
            $hire->job_id = $code;
            $hire->hire_service_id = $request->hire_service_id;
            $hire->unique_code = $code;
            $hire->marketspace_id =session('MARKETSPACE_ID');
            $hire->save();

    }

    function statuscompleted(Request $request)
        {
            $hire =Marketspace_hire::find($request->id);
             $hire->status ='Completed';
            $hire->save();
        }


    private function getToken($length, $seed){    
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";

        mt_srand($seed);      // Call once. Good since $application_id is unique.

        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }
        return $token;
    }
    public function mywork()
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        }else{
            $marketspace=array();
            return redirect("marketspace.register")->withSuccess('Opps! You do not have access');
        }
        //where('status', '=','Pending')
        $marketspace_hire = Marketspace_hire::
        where(function($query){
            $query->where('marketspace_id', session('MARKETSPACE_ID'))
            ->orWhere('hire_service_id', session('MARKETSPACE_ID'));
        })
        ->where('status', '=','Pending')
        ->paginate(20);
     
        $marketspace_hire_complete = Marketspace_hire::
        where(function($query){
            $query->where('marketspace_id', session('MARKETSPACE_ID'))
            ->orWhere('hire_service_id', session('MARKETSPACE_ID'));
        })
        ->where('status', '=','Completed')
        ->paginate(20);

        
        return view('marketspace.mywork',compact('marketspace_hire','marketspace_hire_complete','marketspace'));
    }

    
    
function chat(Request $request)
{

    

    $chatexit = Marketspace_chat_user::where('from_user', '=', session('MARKETSPACE_ID'))->where('to_user','=',$request->service_id)->get();
    if(count($chatexit)==0){
        $chatrel = new Marketspace_chat_user();
        $chatrel->from_user = session('MARKETSPACE_ID');;
        $chatrel->to_user =$request->service_id;
        $chatrel->save();
    }
    

   /* $chat_details = Marketspace_chat::
        where('from_id','=', session('MARKETSPACE_ID'))->orwhere('to_id','=', $request->service_id)
        ->orwhere('to_id','=', session('MARKETSPACE_ID'))->orwhere('from_id','=', $request->service_id)
        ->get();
$to_id=$request->service_id;
    return view('marketspace.chat', compact('chat_details','to_id'))->render();*/
}

function saveChat(Request $request)
{
    $chat = new Marketspace_chat;
    $chat->message = $request->message;
    $chat->to_id = $request->to_id;
    $chat->from_id =session('MARKETSPACE_ID');
    $chat->save();
    $chat_details = Marketspace_chat::
        where('from_id','=', session('MARKETSPACE_ID'))->orwhere('to_id','=', $request->service_id)
        ->orwhere('to_id','=', session('MARKETSPACE_ID'))->orwhere('from_id','=', $request->service_id)
        ->get();
$to_id=$request->to_id;
    return view('marketspace.chat', compact('chat_details','to_id'))->render();
}




}
