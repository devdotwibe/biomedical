<?php

namespace App\Http\Livewire;
use App\Models\Message;
use Livewire\Component;

use App\Models\Masterspace;

class Messages extends Component
{
	public $message;
	public $allmessages;
	public $sender;
    public function render()
    {
    	$users=Masterspace::all();
    	$sender=$this->sender;
        $this->allmessages;
        return view('livewire.messages',compact('users','sender'));
    }
    public function mountdata()
    {
        
        if(isset($this->sender->id))
        {
            
              $this->allmessages=Message::where('user_id',session('MARKETSPACE_ID'))->where('receiver_id',$this->sender->id)->orWhere('user_id',$this->sender->id)->where('receiver_id',session('MARKETSPACE_ID'))->orderBy('id','desc')->get();

               $not_seen= Message::where('user_id',$this->sender->id)->where('receiver_id',session('MARKETSPACE_ID'));
               $not_seen->update(['is_seen'=> true]);
        }

    }
    public function resetForm()
    {
    	$this->message='';
    }

    public function SendMessage()
    {
    	$data=new Message;
    	$data->message=$this->message;
    	$data->user_id=session('MARKETSPACE_ID');
    	$data->receiver_id=$this->sender->id;
    	$data->save();

    	$this->resetForm();


    }
    public function getUser($userId)
    {
        
       $user=Masterspace::find($userId);
       $this->sender=$user;
       $this->allmessages=Message::where('user_id',session('MARKETSPACE_ID'))->where('receiver_id',$userId)->orWhere('user_id',$userId)->where('receiver_id',session('MARKETSPACE_ID'))->orderBy('id','desc')->get();
    }

}
