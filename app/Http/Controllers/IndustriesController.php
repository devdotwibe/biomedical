<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Industries;
use App\Banner;

//use Route;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;



class IndustriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $industries    = Industries::where('status','Y')
                         ->orderBy('order_id', 'asc')
                         ->get(); //count
        if(count($industries) == 0) {
            //abort(404);
            //return view('404');
            return redirect('404');
        }

    	return view('industries.index', compact('industries'));
    }
}
