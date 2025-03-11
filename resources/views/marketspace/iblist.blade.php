@extends('layouts.appmasterspace')
<?php
$title       = 'Edit Profile';
$description = 'Edit Profile';
$keywords    = 'Edit Profile';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<script src="{{ asset('js/custom.js') }}"></script>


<section class="form-edit" style="margin-top:200px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-list dropdown">
                        
                        <a href="#" onclick="myFunction()" class="dropbtn">Select Option 
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-content" id="myDropdown">

                      
                        @include('marketspace.sidebar')
                        </div>
                    
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-card">
                                <div class="tab-card-header">
                                    <div class="list-add">
                                    <h5>IB Product List</h5>
                                    <div class="addib">
                                        <a href="{{ route('marketspace/ibcreate') }}"><img src="{{ asset('images/add-btn-fill.svg') }}" alt="addbtn">Add IB</a>
                                    </div>
                                    </div>
                                    
                                </div>
                                <div class="tab-card-form">
                                    

                                    @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
                                    <div class="outer-table">
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
                                                    <a  href="{{ route('marketspace/request-service',$val->equipment_id) }}">Request Service</a> 
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
                            </div>
                        </div>

                    

                        

                       
                      </div>
                </div>
            </div>
        </div>
    </section>



@endsection
@section('scripts')
<script>
</script>
@endsection
