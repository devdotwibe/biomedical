<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;

class ServiceTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services =  Service::with('serviceUser','serviceServiceType','serviceProduct','serviceContactPerson','serviceEngineer','serviceMachineStatus')
                    ->where('engineer_id', session('STAFF_ID'))->paginate(5);

        return view('staff.service.service_task.index',compact('services'));
    }
}
