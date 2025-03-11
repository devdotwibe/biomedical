@extends('layouts.appmasterspace')
<?php
$title       = 'IB';
$description = 'IB';
$keywords    = 'IB';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
            <!-- <i class="fa fa-bars mobile-menu"></i> -->
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <div class="collapse navbar-collapse" id="user-img">
                @include('marketspace/navbar')
                  </div>
        </nav>
    </header>

    <div class="container-fluid height100">
        <div class="row dashboard-row leftnone">

            <main class="col-md-10" id="main">
                <section class="content-wrap bg-none">
                    <div class="content-col1">
                    <div class="content-col1-head">
                          <h2>IB Product List</h2>
                          <div class="add-skill"><a href="{{ route('marketspace/ibcreate') }}" id="add-reference"> Add IB</a> 
                            <a  class="close_reference" style="display: none;">Back to listing</a>
                          </div>
                        </div>

                        {{-- Ib form --}}
                        <div class="card">

                            <div class="card-top-section">
                              
                               <div class="addib">
                               <a href="{{ route('marketspace/ibcreate') }}"><img src="{{ asset('images/add-button.svg') }}" alt="addbtn">Add IB</a>
                               </div>
                            </div>
 
                           @if (session('success'))
                          <div class="alert alert-success alert-block fade in alert-dismissible show">
                               <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>{{ session('success') }}</strong>
                           </div>
                           @endif
 
              <div class="ib-table outer-table">
                                         <table class="table loopskill">
                                             <thead class="thead-light">
                                                 <tr>
                                                 <th scope="col">Equipment Name</th>
                                                 <th scope="col">Model No</th>
                                                 <th scope="col">Serial No</th>
                                                 <th scope="col">Installation Date</th>
                                                 <th scope="col">Warranty End Date</th>
                                                 <th scope="col">Status</th>
                                                 <th scope="col">Action</th>
                                                 </tr>
                                             </thead>
 
                                             <tbody id="ajax-res" class="t-center">
 
                                             @if(count($data)==0)
                                             <tr>
                                         <td>No Result</td></tr>
                                         @endif
 
                                                 @if(count($data)>0)
                                                 
                                                 @foreach($data as $val)
                                                 <tr>
                                                     <td data-th="Equipment Name">@php  
                                                         if(!empty($val->ibProduct)){
                                                          echo $val->ibProduct->name;
                                                         } @endphp</td>
                                                     <td data-th="Model No">{{$val->equipment_model_no}}</td>
                                                     <td data-th="Serial No">{{$val->equipment_serial_no}}</td>
                                                     <td data-th="Installation Date">{{$val->installation_date}}</td>
                                                     <td data-th="Warranty End Date">{{$val->warrenty_end_date}}</td>
                                                     <td data-th="Status"> @php echo App\EquipmentStatus::get_equipment_status($val->equipment_status_id); @endphp</td>
                                                     
                                                     <td data-th="Action">
                                                       @if($val->equipment_id>0)
                                                       <a  href="{{ route('marketspace/request-service') }}/?id={{ $val->equipment_id }}">Request Service</a> 
                                                       @endif
                                                     </td>
                                                 </tr>
                                                 @endforeach
 
                                                
 
                                                 @endif
                                               
                                             </tbody>
                                         </table>
 
                                         @if(count($data)>0)
                                         {{ $data->links() }}
                                         @endif
    
                                     </div>
   
                 </div>
                        {{-- Ib form end --}}
                    </div>
                    
                </section>
                <div class="right-side-bar">
                    @include('marketspace/right-sidebar')
                </div>
        </div>
    </div>

@endsection

<style>
.main-footer{display:none;}
</style>