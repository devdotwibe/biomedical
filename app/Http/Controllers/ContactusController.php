<?php

namespace App\Http\Controllers;


use App\Contactus;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'company' => 'max:100',
            'phone' => 'required|max:50',
            'postcode' => 'max:20',
            'country' => 'max:100',
            'message' => 'max:1000',
            'g-recaptcha-response' => 'required',
        ];
        // 'interested' => 'required',
        // 'g-recaptcha-response' => 'required|captcha',
        $customMessages = [
            'name.required' => 'Name is required!',
            'name.max'  => 'Maximum 100 characters allowed!',
            'email.required' => 'Email is required!',
            'email.email'  => 'Invalid email address!',
            'company.max'  => 'Maximum 100 characters allowed!',
            'phone.max'  => 'Maximum 50 characters allowed!',
            'postcode.max'  => 'Maximum 20 characters allowed!',
            'country.max'  => 'Maximum 100 characters allowed!',
            'message.max'  => 'Maximum 1000 characters allowed!',
            'g-recaptcha-response.required' => 'Captcha is required',
            
        ];
        // 'interested.required' => 'Interested in is required!',
        // 'g-recaptcha-response.required' => 'Captcha is required',
        // 'g-recaptcha-response.captcha' => 'Invalid captcha',
        //print_r($_POST['g-recaptcha-response']);
        //exit;


        $this->validate($request, $rules, $customMessages);

     /*   $contactus = new Contactus;
        $contactus->interested = $request->interested;
        $contactus->name = $request->name;
        $contactus->email = $request->email;
        $contactus->company = $request->company;
        $contactus->phone = $request->phone;
        $contactus->postcode = $request->postcode;
        $contactus->country = $request->country;
        $contactus->message = $request->message;
        $contactus->call_me = ($request->call_me == 'Y') ? $request->call_me: 'N';
        $contactus->status = 'Y';
        $contactus->mail_send = 'N';
        $contactus->save();*/

        //print_r($contactus);

        $data = array(
                    'name'=> $request->name,
                    'email'=> $request->email,
                    'company'=> $request->company,
                    'postcode'=> $request->postcode,
                    'country'=> $request->country,
                    'messages'=> $request->message,
                    'phone'=> $request->phone
                );
                // 'interested'=> $request->interested,
                // 'call_me'=> ($request->call_me == 'Y') ? 'Yes': 'No',


            //Mail::send(['text'=>'mail'], $data, function($message) {
        Mail::send('email.contactus', $data, function($message) {
            $message->to(setting('CONTACT_EMAIL_ID'), setting('CONTACT_EMAIL_NAME'))->subject
               ('Contact us');
            $message->from(setting('ADMIN_EMAIL_ID'),setting('ADMIN_EMAIL_NAME'));
        });

    

        return back()->with('success', 'Thank you for contacting us. One of our staff will contact you soon!');
    }

    public function enquirysave(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'max:50',           
        ];
// 'g-recaptcha-response' => 'required|captcha',
        $customMessages = [
            'name.required' => 'Name is required!',
            'name.max'  => 'Maximum 100 characters allowed!',
            'email.required' => 'Email is required!',
            'email.email'  => 'Invalid email address!',
            'phone.max'  => 'Maximum 50 characters allowed!',
            
        ];
        // 'g-recaptcha-response.required' => 'Captcha is required',
        // 'g-recaptcha-response.captcha' => 'Invalid captcha',
        //print_r($_POST['g-recaptcha-response']);
        //exit;


        $this->validate($request, $rules, $customMessages);

     /*   $contactus = new Contactus;
        $contactus->interested = $request->interested;
        $contactus->name = $request->name;
        $contactus->email = $request->email;
        $contactus->company = $request->company;
        $contactus->phone = $request->phone;
        $contactus->postcode = $request->postcode;
        $contactus->country = $request->country;
        $contactus->message = $request->message;
        $contactus->call_me = ($request->call_me == 'Y') ? $request->call_me: 'N';
        $contactus->status = 'Y';
        $contactus->mail_send = 'N';
        $contactus->save();*/

        //print_r($contactus);

        $data = array(
                    'name'=> $request->name,
                    'email'=> $request->email,
                    'phone'=> $request->phone,
                    'messages'=> $request->message
                );

            //Mail::send(['text'=>'mail'], $data, function($message) {
        Mail::send('email.enquiry', $data, function($message) {
            $message->to(setting('CONTACT_EMAIL_ID'), setting('CONTACT_EMAIL_NAME'))->subject
               ('Quote Enquiry');
            $message->from(setting('ADMIN_EMAIL_ID'),setting('ADMIN_EMAIL_NAME'));
        });

    

        return back()->with('success', 'Thank you for contacting us. One of our staff will contact you soon!');
    }


    public function requestsave(Request $request)
    {
        $rules = [
            
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'max:50',
            'g-recaptcha-response' => 'required',
           
        ];
        $customMessages = [
          
            'name.required' => 'Name is required!',
            'name.max'  => 'Maximum 100 characters allowed!',
            'email.required' => 'Email is required!',
            'email.email'  => 'Invalid email address!',
            'phone.max'  => 'Maximum 50 characters allowed!',
            'g-recaptcha-response.required' => 'Captcha is required'
            
        ];
       
        $this->validate($request, $rules, $customMessages);


        //print_r($contactus);

        $data = array(
                   
                    'name'=> $request->name,
                    'email'=> $request->email,
                 
                  
                    'messages'=> $request->message,
                   
                    'phone'=> $request->phone
                );

            //Mail::send(['text'=>'mail'], $data, function($message) {
        Mail::send('email.home', $data, function($message) {
            $message->to(setting('CONTACT_EMAIL_ID'), setting('CONTACT_EMAIL_NAME'))->subject
               ('Contact us');
            $message->from(setting('ADMIN_EMAIL_ID'),setting('ADMIN_EMAIL_NAME'));
        });

        return back()->with('success', 'Thank you for contacting us. One of our staff will contact you soon!');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function show(Contactus $contactus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function edit(Contactus $contactus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contactus $contactus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactus $contactus)
    {
        //
    }

    public function about()
    {
    	// return view('site_under_construction');

        return view('homenew');
    }
    public function privacy()
    {
    	// return view('site_under_construction');

        return view('privacy');
    }
    public function terms()
    {
    	// return view('site_under_construction');

        return view('terms');
    }
}
